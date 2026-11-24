# Amazon S3 / DigitalOcean Spaces

Choose Amazon S3 or DigitalOcean Spaces when creating an S3 storage destination. Enter the region (for example us-east-1 or nyc1), bucket or Space name, access key ID, secret and optional object prefix. The endpoint is derived from the service and region; custom endpoints are not supported in this version.

Requires PHP cURL and SimpleXML. The credentials must permit listing the configured prefix, reading and writing its objects, and aborting multipart uploads. Enable a bucket lifecycle rule to abort incomplete multipart uploads. Keep the bucket private. AWS temporary credentials with a session token are not supported. Backups above 64 MiB are sent in multipart uploads. Transfers use HTTPS and do not follow redirects. The server must be able to reach the selected regional endpoint directly.

The endpoint selection and AWS Signature V4 algorithm are adapted from the S3 implementation in [local_alternative_file_system](https://github.com/EduardoKrausME/moodle-local_alternative_file_system/tree/master/classes/storages/s3), with instance-specific credentials and bounded-memory backup transfers.
