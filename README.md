# Moodle BackupFTP

Backup and restore Moodle courses to independently configured storage systems.

## Storage provider plugins

Moodle discovers the provider type `backupftpstorage` through `db/subplugins.json`.
Bundled implementations are located in `storage/ftp` and `storage/local`, with their
own `version.php`, language strings and `classes/provider.php`. A new implementation
extends `local_backupftp\storage\provider`, supplies its own configuration fields, and
implements file upload, listing, ownership validation and retrieval.

The core plugin owns the configuration instances and scheduled tasks. Each destination
has its own name, enabled state, provider type and settings. You can create multiple
instances of the same provider, with different hosts or local directories.

## Backups and restores

On the backup screen select one or more active configured destinations along with Moodle
categories. The selected storage IDs are persisted on each queued backup, so cron writes
to the intended destinations even when more systems exist. Old pending jobs without a
selection use all currently enabled destinations.

On the restore screen, files are listed by their source destination, and queue entries
record that destination to avoid ambiguity when multiple servers contain the same filename.
The old HTTP transfer-token API for Moodle-to-Moodle migration is still supported.

FTP credentials are stored in destination configurations on the Moodle database; restrict
access to database backups and the `local/backupftp:manage` capability. FTP is not encrypted
in transit; prefer FTPS when a remote server supports it.

## Web service contract comparison

Before migrating between Moodle versions, open **Web service contract comparison**
from the Backup FTP/Local home page or Site administration. Download a JSON
snapshot from the source installation and upload it on the destination's comparison
page. Optionally supply a second snapshot to compare two remote/archived installations
without network access. The target defaults to the current Moodle installation.

The snapshot covers registered Moodle External API functions, including the structure
of their parameters and return values. Results highlight functions missing in the
destination, newly added functions, signature changes and contracts whose descriptions
could not be read. Reported differences need review: the comparison does not prove
whether a change is backwards-compatible or whether a course backup will restore.

The feature is read-only, requires `local/backupftp:manage`, and does not execute
any external service functions. It does not send authentication tokens or contact
remote servers. Downloaded JSON excludes parameter descriptions, default values,
credentials and personal records. Snapshots are processed in memory, never
persisted, and uploads are limited to 8 MiB per file. Existing backup/restore
queues, storage providers and transfer tokens are not changed.

## License

GNU GPL version 3 or later. See [LICENSE](LICENSE).
