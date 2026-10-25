# Changelog

This changelog documents **only the two most recently published GitHub releases** of `local_backupftp`, using the exact differences between each published tag and the immediately preceding published release tag. Release `2.4.5`, published on 2026-10-25, supersedes `2.4.3` in the two-release selection. No changes from earlier release ranges or unreleased commits are included.

## [2.4.5] - 2026-10-25

Published: 2026-10-25 03:31:51 UTC (00:31:51 America/Sao_Paulo).

Comparison: [2.4.4...2.4.5](https://github.com/EduardoKrausME/moodle-local_backupftp/compare/2.4.4...2.4.5)

### Changed: subplugin discovery metadata

- Added a `subplugintypes` mapping for `backupftpstorage` pointing to `storage` in `db/subplugins.json`, while preserving the existing `plugintypes` mapping to `local/backupftp/storage`.
- Removed the legacy `db/subplugins.php` file, which previously loaded subplugin types from `db/subplugins.json`. This changes how the plugin declares its storage subplugin type; it does **not** change or remove the actual `storage/` subplugins in the tag comparison.

### Maintenance

- Removed an unused `core_text` import from `classes/util/category.php`.
- Adjusted the license/header formatting of `templates/service_contract.mustache`; the change is to the template comment, not to its rendered functionality.
- Advanced `version.php` metadata from `2026101502` / release `2.4.4` to `2026102400` / release `2.4.5`. The minimum Moodle requirement (`2020011900`) remains unchanged.

### Scope

The comparison includes four commits and changes only `classes/util/category.php`, `db/subplugins.json`, `db/subplugins.php` (removed), `templates/service_contract.mustache`, and `version.php`. No backup/restore queue, transfer or provider implementation changes appear in this released-tag diff.

## [2.4.4] - 2026-10-15

Published: 2026-10-15 22:10:05 UTC.

Comparison: [2.4.3...2.4.4](https://github.com/EduardoKrausME/moodle-local_backupftp/compare/2.4.3...2.4.4)

### Added: Moodle web service contract comparison

- Added the `classes/service_contract.php` service for read-only snapshots of registered Moodle External API functions, including declared input and output structures and the Moodle version/release.
- Added comparison of a source JSON contract snapshot against the current Moodle installation or an optional second uploaded snapshot. The results distinguish functions added at the destination, missing at the destination, signatures that have changed, unchanged functions, and contracts whose declarations could not be resolved.
- Added `service-contract.php` and `templates/service_contract.mustache` to download a local contract snapshot and upload snapshots for comparison. The page requires authentication, `local/backupftp:manage` and a sesskey-protected POST for comparison.
- Limited each uploaded JSON snapshot to **8 MiB**; snapshots are processed in memory rather than saved as plugin records. The comparison inspects declared external service signatures and does **not** call those services, connect to other Moodle installations, or send API tokens.
- Exposed the comparison page from the Backup FTP/Local home template and a Site administration link via updates to `templates/index.mustache`, `lib.php` and `settings.php`.
- Added English and Brazilian Portuguese language strings, plus `tests/service_contract_test.php` to cover comparison of added, removed and changed functions and snapshot handling.
- Documented use and limitations in `README.md`, including that a changed API signature is **not**, by itself, proof of an incompatible migration or a failed course restore.

### Version metadata and scope

- Updated `version.php` from plugin version `2026101501` / release `2.4.3` to `2026101502` / release `2.4.4`; the minimum Moodle requirement (`2020011900`) was unchanged.
- The four-commit comparison adds the contract service, its UI and tests, and updates documentation, translations and navigation. No changes to the existing backup/restore queues, storage-provider implementations or transfer tokens appear in the published-tag diff.

---

All statements are based on published Git tag differences, not inferred functionality from release numbers. This file does not claim that tests were run during changelog creation.
