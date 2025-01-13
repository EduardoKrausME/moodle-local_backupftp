# local_backupftp

This is a Moodle plugin that facilitates the backup and restoration process of courses. The plugin performs backups of Moodle courses and automatically transfers them to a configured FTP server, as well as organizes the backups by Moodle categories, making backup management more efficient. The plugin also offers a tool to restore courses directly from the FTP server.

## Features

- **Course backup:** Performs backup of courses in Moodle and automatically sends them to the FTP.
- **Category organization:** Organizes backups on the FTP server by course categories, simplifying management.
- **Course restoration:** Allows restoration of courses directly from backups stored on the FTP server.
- **Flexible configuration:** The FTP server configuration can be easily set up in Moodle’s administration panel.

## Installation

1. Download the plugin from [GitHub](https://github.com/EduardoKrausME/moodle-local_backupftp).
2. Unzip the file and place the **local_backupftp** directory in your Moodle's **local** folder.
3. In the Moodle admin panel, navigate to **Site Administration > Notifications** to complete the installation.
4. Configure the FTP server in the plugin settings.

## FTP Configuration

After installation, you can configure the FTP server to store the backups:

1. Go to **Site Administration > Plugins > Local Plugins > Backup FTP**.
2. Provide the credentials for your FTP server, such as server address, username, password, and destination folder.
3. Ensure that Moodle has permission to write to the specified folder on the FTP.

## How to Use

### Performing a Backup

- After configuring the FTP, the plugin will automatically back up the courses and transfer them to the configured FTP server.
- The backups will be organized by category and accessible on the FTP server for easier management.

### Restoring Courses

- To restore a course from the backup on the FTP, simply access the plugin's restoration tool and select the desired backup directly from the FTP server.

## Contributions

If you wish to contribute to the project, feel free to open an issue or submit a pull request.

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
