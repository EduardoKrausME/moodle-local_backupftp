<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * S3 backup storage.
 *
 * @package backupftpstorage_s3
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace backupftpstorage_s3;

/**
 * Instance-scoped HTTPS transport using AWS Signature V4.
 *
 * Endpoint and signing rules adapted from local_alternative_file_system's S3 implementation.
 * Requests do not set object ACLs; bucket policy remains under the administrator's control.
 */
class client {
    /** @var array Destination configuration. */
    private $config;
    /** @var string Request hostname. */
    private $host;
    /** @var string Path-style bucket prefix, if necessary. */
    private $basepath;
    /** @var int Default multipart part size. */
    private const PARTSIZE = 67108864;

    /**
     * Configure the client.
     *
     * @param array $config Normalized destination settings.
     */
    public function __construct(array $config) {
        $this->config = $config;
        if (!function_exists('curl_init') || !function_exists('simplexml_load_string')) {
            throw new \moodle_exception('extensionrequired', 'backupftpstorage_s3');
        }
        if (isset($config['endpoint'])) {
            $address = self::resolve_endpoint($config['endpoint'], $config['bucket'], $config['urlstyle']);
            $this->host = $address['host'];
            $this->basepath = $address['basepath'];
        } else {
            $region = $config['region'];
            if ($config['service'] === 'space') {
                $endpoint = $region . '.digitaloceanspaces.com';
            } else {
                $suffix = strpos($region, 'cn-') === 0 ? 'amazonaws.com.cn' : 'amazonaws.com';
                $endpoint = 's3.' . $region . '.' . $suffix;
            }
            // Dotted bucket names do not match a single-label TLS wildcard.
            $pathstyle = strpos($config['bucket'], '.') !== false;
            $this->host = $pathstyle ? $endpoint : $config['bucket'] . '.' . $endpoint;
            $this->basepath = $pathstyle ? '/' . rawurlencode($config['bucket']) : '';
        }
    }


    /**
     * Validate a custom HTTPS endpoint and resolve the bucket addressing style.
     *
     * @param string $endpoint HTTPS service origin or hostname, optionally with a port.
     * @param string $bucket Bucket name.
     * @param string $style auto, path-style or virtual-hosted.
     * @return array Canonical endpoint, request host and bucket path.
     */
    public static function resolve_endpoint(string $endpoint, string $bucket, string $style): array {
        $endpoint = trim($endpoint);
        if ($endpoint === '' || preg_match('/[\x00-\x20\x7f\\\\]/', $endpoint) ||
                !in_array($style, ['auto', 'path-style', 'virtual-hosted'], true) ||
                !preg_match('/\A[a-z0-9][a-z0-9.-]{1,61}[a-z0-9]\z/', $bucket) ||
                strpos($bucket, '..') !== false || filter_var($bucket, FILTER_VALIDATE_IP)) {
            throw new \invalid_parameter_exception('Invalid S3 endpoint or addressing style');
        }
        if (strpos($endpoint, '://') === false) {
            $endpoint = 'https://' . $endpoint;
        }
        $parts = parse_url($endpoint);
        if (!$parts || strtolower($parts['scheme'] ?? '') !== 'https' || empty($parts['host']) ||
                isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) ||
                isset($parts['fragment']) || !in_array($parts['path'] ?? '', ['', '/'], true) ||
                (isset($parts['port']) && ($parts['port'] < 1 || $parts['port'] > 65535))) {
            throw new \invalid_parameter_exception('S3 endpoint must be an HTTPS origin without a path or credentials');
        }
        $hostname = strtolower($parts['host']);
        $isip = filter_var(trim($hostname, '[]'), FILTER_VALIDATE_IP) !== false;
        if (!$isip && (!filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) ||
                preg_match('/\A[0-9.]+\z/', $hostname) || substr($hostname, -1) === '.')) {
            throw new \invalid_parameter_exception('Invalid S3 endpoint hostname');
        }
        $port = isset($parts['port']) && $parts['port'] !== 443 ? ':' . $parts['port'] : '';
        // Auto uses path-style for IPs, single-label hosts and dotted buckets.
        $pathstyle = $style === 'path-style' || ($style === 'auto' &&
            ($isip || strpos($hostname, '.') === false || strpos($bucket, '.') !== false));
        if (!$pathstyle && $isip) {
            throw new \invalid_parameter_exception('Virtual-hosted S3 addressing requires a DNS hostname');
        }
        return [
            'endpoint' => 'https://' . $hostname . $port,
            'host' => ($pathstyle ? $hostname : $bucket . '.' . $hostname) . $port,
            'basepath' => $pathstyle ? '/' . rawurlencode($bucket) : '',
        ];
    }

    /**
     * Generate an encoded, non-normalized S3 request path.
     *
     * @param string $key Object key.
     * @return string
     */
    private function uri(string $key): string {
        return $this->basepath . '/' . implode('/', array_map('rawurlencode', explode('/', $key)));
    }

    /**
     * Build a signed request and transfer data over HTTPS.
     *
     * @param string $method HTTP verb.
     * @param string $key Object key.
     * @param array $query Query arguments.
     * @param string $body Small request body.
     * @param resource|null $input Upload stream positioned at start of part.
     * @param int $length Upload part length.
     * @param resource|null $output Download stream.
     * @return array Response body and headers.
     */
    private function request(string $method, string $key, array $query = [], string $body = '',
            $input = null, int $length = 0, $output = null): array {
        $uri = $this->uri($key);
        ksort($query, SORT_STRING);
        $pairs = [];
        foreach ($query as $name => $value) {
            $pairs[] = rawurlencode((string)$name) . '=' . rawurlencode((string)$value);
        }
        $querystring = implode('&', $pairs);
        if ($input !== null) {
            $start = ftell($input);
            $hash = hash_init('sha256');
            if ($start === false || hash_update_stream($hash, $input, $length) !== $length ||
                    fseek($input, $start) !== 0) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            $payloadhash = hash_final($hash);
        } else {
            $payloadhash = hash('sha256', $body);
        }
        $datetime = gmdate('Ymd\THis\Z');
        $date = substr($datetime, 0, 8);
        $region = $this->config['region'];
        $scope = $date . '/' . $region . '/s3/aws4_request';
        $signed = 'host;x-amz-content-sha256;x-amz-date';
        $canonicalheaders = "host:{$this->host}\nx-amz-content-sha256:{$payloadhash}\nx-amz-date:{$datetime}\n";
        $canonical = implode("\n", [$method, $uri, $querystring, $canonicalheaders, $signed, $payloadhash]);
        $tosign = implode("\n", ['AWS4-HMAC-SHA256', $datetime, $scope, hash('sha256', $canonical)]);
        $signingkey = hash_hmac('sha256', $date, 'AWS4' . $this->config['password'], true);
        foreach ([$region, 's3', 'aws4_request'] as $item) {
            $signingkey = hash_hmac('sha256', $item, $signingkey, true);
        }
        $signature = hash_hmac('sha256', $tosign, $signingkey);
        $authorization = 'AWS4-HMAC-SHA256 Credential=' . $this->config['accesskey'] . '/' . $scope .
            ', SignedHeaders=' . $signed . ', Signature=' . $signature;
        $headers = [
            'Host: ' . $this->host, 'x-amz-content-sha256: ' . $payloadhash, 'x-amz-date: ' . $datetime,
            'Authorization: ' . $authorization, 'Expect:',
        ];
        $responseheaders = [];
        $url = 'https://' . $this->host . $uri . ($querystring === '' ? '' : '?' . $querystring);
        $curl = curl_init($url);
        if ($curl === false) {
            throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
        }
        try {
            if ($method === 'POST') {
                $headers[] = 'Content-Type: application/xml';
            }
            $options = [
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_LOW_SPEED_LIMIT => 1024,
                CURLOPT_LOW_SPEED_TIME => 120,
                CURLOPT_HEADERFUNCTION => static function($handle, $line) use (&$responseheaders) {
                    if (strpos($line, 'HTTP/') === 0) {
                        $responseheaders = [];
                    } else if (strpos($line, ':') !== false) {
                        [$name, $value] = explode(':', $line, 2);
                        $responseheaders[strtolower(trim($name))] = trim($value);
                    }
                    return strlen($line);
                },
            ];
            if ($input !== null) {
                $remaining = $length;
                $options[CURLOPT_UPLOAD] = true;
                $options[CURLOPT_INFILESIZE] = $length;
                $options[CURLOPT_READFUNCTION] = static function($handle, $stream, $size) use ($input, &$remaining) {
                    if ($remaining <= 0) {
                        return '';
                    }
                    $data = fread($input, min($size, $remaining));
                    if ($data === false || $data === '') {
                        return '';
                    }
                    $remaining -= strlen($data);
                    return $data;
                };
            } else if ($method === 'POST' || $method === 'PUT') {
                $options[CURLOPT_POSTFIELDS] = $body;
            }
            if ($output !== null) {
                $options[CURLOPT_RETURNTRANSFER] = false;
                $options[CURLOPT_FILE] = $output;
            }
            if (!curl_setopt_array($curl, $options)) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            $response = curl_exec($curl);
            $status = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($response === false || $status < 200 || $status >= 300 ||
                    ($input !== null && $remaining !== 0)) {
                // Do not expose credentials, signed requests or raw remote error bodies in Moodle logs.
                throw new \moodle_exception('httperror', 'backupftpstorage_s3', '', $status);
            }
            return ['body' => is_string($response) ? $response : '', 'headers' => $responseheaders];
        } finally {
            curl_close($curl);
        }
    }

    /**
     * Safely parse S3 XML responses, including errors returned with HTTP 200.
     *
     * @param string $body XML response.
     * @param string $expected Expected root element.
     * @return \SimpleXMLElement
     */
    private function xml(string $body, string $expected): \SimpleXMLElement {
        if (stripos($body, '<!DOCTYPE') !== false) {
            throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
        }
        $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NONET);
        if ($xml === false || $xml->getName() !== $expected) {
            throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
        }
        return $xml;
    }

    /**
     * Upload a file using a single PUT or multipart upload.
     *
     * @param string $source Local backup.
     * @param string $key Object key.
     */
    public function upload(string $source, string $key): void {
        $input = @fopen($source, 'rb');
        if ($input === false) {
            throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
        }
        $uploadid = '';
        try {
            $stat = fstat($input);
            $size = $stat === false ? 0 : $stat['size'];
            if ($size < 10 || $size > 5497558138880) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            if ($size <= self::PARTSIZE) {
                $this->request('PUT', $key, [], '', $input, $size);
                return;
            }
            $init = $this->request('POST', $key, ['uploads' => '']);
            $uploadid = (string)$this->xml($init['body'], 'InitiateMultipartUploadResult')->UploadId;
            if ($uploadid === '') {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            // Keep the number of parts below S3's 10,000-part limit.
            $partsize = max(self::PARTSIZE, (int)ceil($size / 10000));
            $complete = new \SimpleXMLElement('<CompleteMultipartUpload/>');
            for ($offset = 0, $part = 1; $offset < $size; $offset += $length, $part++) {
                $length = min($partsize, $size - $offset);
                $result = $this->request('PUT', $key, ['partNumber' => $part, 'uploadId' => $uploadid], '', $input, $length);
                $etag = $result['headers']['etag'] ?? '';
                if ($etag === '') {
                    throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
                }
                $node = $complete->addChild('Part');
                $node->addChild('PartNumber', (string)$part);
                $node->addChild('ETag', htmlspecialchars($etag, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
            }
            $result = $this->request('POST', $key, ['uploadId' => $uploadid], $complete->asXML());
            $this->xml($result['body'], 'CompleteMultipartUploadResult');
            $uploadid = '';
        } finally {
            if ($uploadid !== '') {
                try {
                    $this->request('DELETE', $key, ['uploadId' => $uploadid]);
                } catch (\Exception $ignored) {
                    // A bucket lifecycle rule can clean abandoned multipart uploads.
                    // Preserve the original upload failure.
                }
            }
            fclose($input);
        }
    }

    /**
     * List all objects in the prefix using continuation tokens.
     *
     * @param string $prefix Object prefix.
     * @return \Generator
     */
    public function objects(string $prefix): \Generator {
        $token = '';
        do {
            $query = ['list-type' => '2', 'prefix' => $prefix, 'encoding-type' => 'url'];
            if ($token !== '') {
                $query['continuation-token'] = $token;
            }
            $result = $this->request('GET', '', $query);
            $xml = $this->xml($result['body'], 'ListBucketResult');
            foreach ($xml->Contents as $object) {
                $modified = strtotime((string)$object->LastModified);
                yield ['path' => rawurldecode((string)$object->Key), 'size' => (int)$object->Size,
                    'modified' => $modified === false ? '' : gmdate('YmdHis', $modified)];
            }
            if (strtolower((string)$xml->IsTruncated) !== 'true') {
                break;
            }
            $next = (string)$xml->NextContinuationToken;
            if ($next === '' || $next === $token) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            $token = $next;
        } while (true);
    }

    /**
     * Stream a backup to a temporary file and publish it only after success.
     *
     * @param string $key Object key.
     * @param string $destination Restore staging filename.
     */
    public function download(string $key, string $destination): void {
        $temp = tempnam(dirname($destination), 's3-');
        if ($temp === false) {
            throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
        }
        try {
            $output = fopen($temp, 'wb');
            if ($output === false) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
            try {
                $result = $this->request('GET', $key, [], '', null, 0, $output);
                $stat = fstat($output);
                $size = $stat === false ? 0 : $stat['size'];
                if ($size < 10 || (isset($result['headers']['content-length']) &&
                        $size !== (int)$result['headers']['content-length'])) {
                    throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
                }
            } finally {
                fclose($output);
            }
            if (!rename($temp, $destination)) {
                throw new \moodle_exception('transferfailed', 'backupftpstorage_s3');
            }
        } finally {
            if (is_file($temp)) {
                @unlink($temp);
            }
        }
    }
}
