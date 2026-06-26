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
 * Tree checkbox helpers for backup/restore pages.
 *
 * @module     local_backupftp/categoryselector
 * @copyright 2025 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery'], function($) {
    'use strict';

    var SELECT_ALL = '[data-action="local-backupftp-select-all"]';
    var DESELECT_ALL = '[data-action="local-backupftp-deselect-all"]';
    var NODE = '[data-region="local-backupftp-node"]';
    var TREE = '[data-region="local-backupftp-tree"]';

    /**
     * Find the nearest checkbox scope for the clicked button.
     *
     * @param {HTMLElement} element Clicked element.
     * @return {jQuery}
     */
    var getScope = function(element) {
        var scope = $(element).closest(NODE);

        if (!scope.length) {
            scope = $(element).closest(TREE);
        }

        return scope;
    };

    /**
     * Mark or unmark all checkboxes inside a scope.
     *
     * @param {jQuery} scope Checkbox container.
     * @param {Boolean} checked Desired checked state.
     */
    var setChecked = function(scope, checked) {
        scope.find('input[type="checkbox"]').prop('checked', checked).trigger('change');
    };

    /**
     * Initialise event listeners.
     */
    var init = function() {
        $(document)
            .off('click.localBackupftpSelector', SELECT_ALL)
            .on('click.localBackupftpSelector', SELECT_ALL, function(e) {
                e.preventDefault();
                setChecked(getScope(this), true);
            });

        $(document)
            .off('click.localBackupftpSelector', DESELECT_ALL)
            .on('click.localBackupftpSelector', DESELECT_ALL, function(e) {
                e.preventDefault();
                setChecked(getScope(this), false);
            });
    };

    return {
        init: init
    };
});
