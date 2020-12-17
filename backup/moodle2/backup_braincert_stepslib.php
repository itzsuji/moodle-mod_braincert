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
 * This page is the entry page into the online class
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * class backup_braincert_activity_structure_step
 * @copyright Dualcube (https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_braincert_activity_structure_step extends backup_activity_structure_step {

    /**
     * Function describes the structure of a backup file.
     *
     * @return string
     */
    protected function define_structure() {
        global $DB;
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $braincert = new backup_nested_element('braincert', array('id'), array(
            'class_id', 'name', 'intro', 'introformat',
            'braincert_timezone', 'default_timezone', 'start_date', 'start_time', 'end_time',
            'is_region', 'is_recurring', 'end_classes_count', 'class_repeats',
            'weekdays', 'change_language', 'bc_interface_language', 'record_type',
            'classroomtype', 'is_corporate', 'screen_sharing', 'private_chat',
            'class_type', 'currency', 'maxattendees', 'groupingid',
            'timemodified', 'recording_layout', 'isvideo'));

        $classpurchases = new backup_nested_element('classpurchases');

        $classpurchase = new backup_nested_element('classpurchase', array('id'), array('class_id',
            'mc_gross',
            'payer_id',
            'payment_mode',
            'date_purchased'));

        $managetemplates = new backup_nested_element('managetemplates');

        $managetemplate = new backup_nested_element('managetemplate', array('id'), array('bcid',
            'emailsubject',
            'emailmessage'));

        $braincertrecord = $DB->get_record('braincert', ['id' => $this->task->get_activityid()]);

        // Build the tree.
        $braincert->add_child($classpurchases);
        $classpurchases->add_child($classpurchase);

        $braincert->add_child($managetemplates);
        $managetemplates->add_child($managetemplate);

        // Define sources.
        $braincert->set_source_array([$braincertrecord]);

        if ($userinfo) {
            $params = [backup_helper::is_sqlparam($braincertrecord->class_id)];
            $classpurchase->set_source_sql("SELECT id, class_id, mc_gross, payer_id,
            payment_mode, date_purchased FROM {braincert_class_purchase}
                  WHERE class_id = ?", $params);
        }

        $params = [backup_helper::is_sqlparam($braincertrecord->id)];
        $managetemplate->set_source_sql("SELECT id, bcid, emailsubject, emailmessage
         FROM {braincert_manage_template}
                WHERE bcid = ?", $params);

        // Define id annotations.
        $classpurchase->annotate_ids('braincert', 'class_id');
        $classpurchase->annotate_ids('user', 'payer_id');

        return $this->prepare_activity_structure($braincert);
    }

}
