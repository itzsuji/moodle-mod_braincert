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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the capabilities for braincert module.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * upgrade this braincert instance
 * @param int $oldversion The old version of the assign module
 * @return bool
 */
function xmldb_braincert_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2019012202) {
        // Define new fields to be added to braincert.
        $table = new xmldb_table('braincert');

        $field = new xmldb_field('recording_layout', XMLDB_TYPE_INTEGER, '1', null, null, null, 0);
        // Conditionally launch add field recording_layout.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Braincert savepoint reached.
        upgrade_mod_savepoint(true, 2019012202, 'braincert');
    }
    return true;
}
