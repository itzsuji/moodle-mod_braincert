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
 * Internal library of functions for module braincert
 *
 * All the braincert specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_braincert
 * @author     BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_braincert\privacy;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/braincert/locallib.php');

use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\contextlist;
use \core_privacy\local\request\writer;
use \core_privacy\local\request\approved_contextlist;
use \core_privacy\local\request\transform;
use \core_privacy\local\request\helper;
use \core_privacy\local\request\userlist;
use \core_privacy\local\request\approved_userlist;
use \core_privacy\manager;

/**
 * Privacy class for requesting user data.
 *
 * @package    mod_braincert
 * @copyright  BrainCert <support@braincert.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
\core_privacy\local\metadata\provider, \core_privacy\local\request\plugin\provider,
        \core_privacy\local\request\core_userlist_provider{

    /**
     * Provides meta data that is stored about a user with mod_braincert
     *
     * @param  collection $collection A collection of meta data items to be added to.
     * @return  collection Returns the collection of metadata.
     */
    public static function get_metadata(collection $collection) : collection {

        $collection->add_database_table('braincert_class_purchase', [
            'payer_id' => 'privacy:metadata:braincert_class_purchase:payer_id',
            'payment_mode' => 'privacy:metadata:braincert_class_purchase:payment_mode',
            'date_purchased' => 'privacy:metadata:braincert_class_purchase:date_purchased',
            'mc_gross' => 'privacy:metadata:braincert_class_purchase:mc_gross',
            'class_id' => 'privacy:metadata:braincert_class_purchase:class_id',
                ], 'privacy:metadata:braincert_class_purchase');

        $collection->add_external_location_link('braincert_client', [
            'userId' => 'privacy:metadata:braincert_client:userId',
            'userName' => 'privacy:metadata:braincert_client:userName',
            'class_id' => 'privacy:metadata:braincert_client:class_id',
                ], 'privacy:metadata:braincert_client');

        return $collection;
    }

        /**
         * Returns all of the contexts that has information relating to the userid.
         *
         * @param  int $userid The user ID.
         * @return contextlist an object with the contexts related to a userid.
         */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $params = ['modulename' => 'braincert',
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];

        $sql = "SELECT ctx.id
                  FROM {course_modules} cm
                  JOIN {modules} m ON cm.module = m.id AND m.name = :modulename
                  JOIN {braincert} a ON cm.instance = a.id
                  JOIN {context} ctx ON cm.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {braincert_class_purchase} ag ON a.class_id = ag.class_id AND (ag.payer_id = :userid )";

        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);
        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {

        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $params = [
            'modulename' => 'braincert',
            'contextid' => $context->id,
            'contextlevel' => CONTEXT_MODULE
        ];

        $sql = "SELECT g.user_id userid
                  FROM {context} ctx
                  JOIN {course_modules} cm ON cm.id = ctx.instanceid
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {braincert} a ON a.id = cm.instance
                  JOIN {braincert_class_purchase} g ON a.class_id = g.class_id
                 WHERE ctx.id = :contextid AND ctx.contextlevel = :contextlevel";
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Write out the user data filtered by contexts.
     *
     * @param approved_contextlist $contextlist contexts that we are writing data out from.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (!$contextlist->count()) {
            return;
        }

        $user = $contextlist->get_user();

        foreach ($contextlist->get_contexts() as $context) {
            // Check that the context is a module context.
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }
            $params = [
                'modulename' => 'braincert',
                'contextid' => $context->id,
                'contextlevel' => CONTEXT_MODULE,
                'userid' => $user->id
            ];
            $sql = "SELECT ctx.id, g.class_id, a.name,
            g.mc_gross,g.payer_id, g.payment_mode, g.date_purchased
                  FROM {course_modules} cm
                  JOIN {modules} m ON cm.module = m.id AND m.name = :modulename
                  JOIN {braincert} a ON cm.instance = a.id
                  JOIN {context} ctx ON cm.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {braincert_class_purchase} g ON a.class_id = g.class_id AND (g.payer_id = :userid )
                   WHERE ctx.id = :contextid";
            $r = $DB->get_record_sql($sql, $params);
            if ($r) {
                $data = new \stdClass();
                    $data->payer_id = transform::user($user->id);
                    $data->mc_gross = $r->mc_gross;
                    $data->date_purchased = transform::datetime($r->date_purchased);
                    $data->payment_mode = $r->payment_mode;
                    $data->class_id = $r->class_id;
                writer::with_context($context)->export_data([], $data);
            }
        }
    }

    /**
     * Delete all use data which matches the specified context.
     *
     * @param \context $context The module context.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if ($context->contextlevel == CONTEXT_MODULE) {
            $cm = get_coursemodule_from_id('braincert', $context->instanceid);
            if ($cm) {
                $classid = $DB->get_field("braincert", "class_id", array("id" => $cm->instance));
                $DB->delete_records('braincert_class_purchase', ['class_id' => $classid]);
            }
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $user = $contextlist->get_user();

        foreach ($contextlist as $context) {
            $cm = get_coursemodule_from_id('braincert', $context->instanceid);
            if ($cm) {
                $classid = $DB->get_field("braincert", "class_id", array("id" => $cm->instance));
                $DB->delete_records('braincert_class_purchase', ['class_id' => $classid, 'payer_id' => $user->id]);
            }
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param  approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }
        $cm = get_coursemodule_from_id('braincert', $context->instanceid);
        $userids = $userlist->get_userids();
        list($sql, $params) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
         $classid = $DB->get_field("braincert", "class_id", array("id" => $cm->instance));
        $params['class_id'] = $classid;
        $DB->delete_records_select('braincert_class_purchase', "class_id = :class_id AND userid $sql", $params);
    }

}
