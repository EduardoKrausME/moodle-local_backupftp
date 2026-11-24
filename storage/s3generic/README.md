# Generic S3-compatible backup storage

Install alongside `backupftpstorage_s3`. This subplugin reuses its transport, signing, multipart upload, listing, and restore implementation without copying the client.

Set the HTTPS endpoint supplied by your provider (without the bucket or object prefix), signing region, bucket, access key, secret, backup prefix, and bucket URL style. For IDrive e2, use the endpoint and signing region shown by IDrive for your account. For MinIO and other self-hosted services, a custom HTTPS port is accepted; select path-style if needed. TLS certificate verification is mandatory, and redirects are not followed.

Only trusted storage administrators should configure endpoints: a configured destination receives course backup data. Apply outbound firewall/DNS policies as appropriate; private network endpoints are intentionally supported. Endpoints with URL paths, query strings, embedded credentials or HTTP are not supported. Use the provider's actual signing region and long-lived S3 credentials. See the shared S3 provider README for permissions and multipart requirements.

Compatibility depends on the service implementing AWS Signature V4, ListObjectsV2, PutObject, GetObject and multipart upload. No provider-specific SDK is required.
