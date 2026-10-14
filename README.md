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

## Installation and upgrade

Place the plugin at `local/backupftp` and install it in Site administration > Notifications.

New installations automatically have a local storage destination enabled in the Moodle
dataroot `backup` directory. Existing installations migrate their local destination
only when `local_backupftp/localfileenable` is enabled. FTP is migrated only when
both `local_backupftp/ftpenable` and `local_backupftp/ftpurl` are configured.
Migrated settings are not deleted, to preserve rollback compatibility, but they are no
longer shown or used for new FTP backup destinations.

The upgrade is idempotent and does not create duplicate destination types when rerun.
Use the `Storage systems` area on `local/backupftp/index.php` to configure or edit
destinations. The former FTP and local global settings have been replaced by provider forms.

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

## License

GNU GPL version 3 or later. See [LICENSE](LICENSE).
