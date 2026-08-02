// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle modal confirmation for destructive plugin actions.
 *
 * @module     local_backupftp/confirmation
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/notification'], function($, Notification) {
    'use strict';

    var SELECTOR = '[data-action="local-backupftp-confirm"]';

    /**
     * Initialise confirmation links.
     */
    var init = function() {
        $(document)
            .off('click.localBackupftpConfirmation', SELECTOR)
            .on('click.localBackupftpConfirmation', SELECTOR, function(e) {
                e.preventDefault();

                var target = $(this);
                var url = target.attr('href');
                var message = target.attr('data-confirm-message');

                if (!url || !message) {
                    return;
                }

                Notification.confirm(
                    M.util.get_string('confirmation'),
                    message,
                    M.util.get_string('yes'),
                    M.util.get_string('no'),
                    function() {
                        window.location.assign(url);
                    }
                );
            });
    };

    return {
        init: init
    };
});
