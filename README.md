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

## Native automated backups

Open **Configure automatic backups** on the home page. Moodle continues to generate the scheduled course backups; BackupFTP observes successful native automated backup events, matches the archive's manifest backup ID, stages a private copy before native cleanup, and transfers it through cron. One active supported destination is selected automatically; with multiple, choose the intended destinations. Manual backups and restores remain separate. Automatic copies use unique names and ignore the manual filename/category naming preferences.

Choose never to delete, or delete only the automatic copies tracked by this plugin a specified number of days after all selected transfers complete. The original Moodle files and manual backups are not deleted. Disabling the feature pauses processing and deletion. A failed destination is retried, without resending completed destinations; a changed or disabled destination pauses the corresponding upload or deletion until its original configuration is restored. Staged backups may contain personal data and require secure Moodledata and storage access; monitor pending transfers and free disk space. The native backup schedule and local retention remain configured in Moodle. S3 retention requires DeleteObject permission; bucket versioning or retention policies may retain previous object versions.

### Automatic backup filenames and retention

New native automatic backups retain their Moodle-generated MBZ basename inside an
exclusive `automatic-<backupid>-<storageid>/` directory. This prevents equal
filenames from different backup runs from overwriting one another. Previously
transferred backups retain their existing paths and remain eligible for retention.

Retention may be disabled, based on days since successful transfer, or based on
the latest N completed backups per course and selected storage destination. Failed
or pending transfers do not count as replacements. Only recorded automatic
copies at unchanged destinations are eligible for deletion. Moodle's native
backup retention is configured separately.
