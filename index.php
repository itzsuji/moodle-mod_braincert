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
 * The braincert activity
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../config.php");
require_once("lib.php");
require_once('locallib.php');

$id = required_param('id', PARAM_INT);   // Course ID.
$bcid = optional_param('bcid', 0, PARAM_INT); // Virtual Class ID.
$all = optional_param('all', 1, PARAM_INT); // Cancel class details.

$PAGE->set_url('/mod/braincert/index.php', array('id' => $id));

if (!$course = $DB->get_record('course', array('id' => $id))) {
    print_error('invalidcourseid');
}

require_login($course);
$PAGE->set_pagelayout('incourse');
$pluginname = get_string('pluginname', 'braincert');
$PAGE->navbar->add($pluginname);
$PAGE->set_heading($course->fullname);

$PAGE->requires->css('/mod/braincert/css/styles.css', true);
if ($CFG->version < 2017051500) {
    $PAGE->requires->css('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', true);
}
$PAGE->requires->js('/mod/braincert/js/jquery.min.js', true);
$PAGE->requires->js('/mod/braincert/js/classsettings.js', true);
?>

<?php
if ($bcid > 0) {
    $getremovestatus = braincert_cancel_class($bcid, $all);
    if ($getremovestatus['status'] == BRAINCERT_STATUS_OK) {
        echo get_string('braincert_class_removed', 'braincert');
        redirect(new moodle_url('/mod/braincert/index.php?id=' . $id));
    } else {
        echo $getremovestatus['error'];
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('braincert_class', 'braincert'));

$coursecontext = context_course::instance($id);
$admins = get_admins();
$isadmin = false;
$isteacher = 0;
$isstudent = 0;
if (is_siteadmin($USER->id)) {
    $isadmin = true;
    $SESSION->persona = BRAINCERT_MODE_PERSONA_ADMIN;
    $isteacher = 1;
} else if (has_capability('mod/braincert:addinstance', $coursecontext)) {
    $isteacher = 1;
    $SESSION->persona = BRAINCERT_MODE_PERSONA_TEACHER;
} else if (has_capability('mod/braincert:braincert_view', $coursecontext)) {
    $isstudent = 1;
    $SESSION->persona = BRAINCERT_MODE_PERSONA_STUDENT;
}


$braincertclasses = $DB->get_records('braincert');
$allclassid = array();
foreach ($braincertclasses as $class) {
    array_push($allclassid, $class->class_id);
}

if ($CFG->version >= 2016120500) {
    $courseclasses = $DB->get_records_sql("SELECT bct.* FROM {braincert} bct,
    {course_modules} cm, {modules} m WHERE m.name = ? AND cm.module = m.id
    AND cm.deletioninprogress = ? AND cm.visible = ? AND cm.course = ?
    AND bct.id = cm.instance", array('braincert', 0, 1, $id));
} else {
    $courseclasses = $DB->get_records_sql("SELECT bct.* FROM {braincert} bct,
    {course_modules} cm, {modules} m WHERE m.name = ? AND cm.module = m.id
    AND cm.visible = ? AND cm.course = ?
    AND bct.id = cm.instance", array('braincert', 1, $id));
}
$thiscourseclassid = array();
foreach ($courseclasses as $class) {
    array_push($thiscourseclassid, $class->class_id);
}

$getclasslists = braincert_get_class_list();
foreach ($thiscourseclassid as $getclasslist) {
    $getclasslist = braincert_get_class($getclasslist);
    if ($getclasslist) {
        $module = $DB->get_record('modules', array('name' => 'braincert'));
        $braincertrec = $DB->get_record('braincert', array('class_id' => $getclasslist['id'], 'course' => $id));
        $cm = $DB->get_record('course_modules', array(
            'instance' => $braincertrec->id, 'course' => $id, 'module' => $module->id));
        if ($getclasslist["ispaid"] == 1 && !$isteacher) {
            $getuserpaymentdetails = $DB->get_record('braincert_class_purchase', array(
                'class_id' => $braincertrec->class_id, 'payer_id' => $USER->id));
        } else {
            $getuserpaymentdetails = false;
        }
        $duration = $getclasslist['duration'] / 60;
        $lauchbutton = braincert_dispaly_luanch_button($getclasslist, $id, $cm, $getuserpaymentdetails, $isteacher, 'link');
        if ($getclasslist['status'] == BRAINCERT_STATUS_PAST) {
            $class = "bc-alert bc-alert-danger";
        } else if ($getclasslist['status'] == BRAINCERT_STATUS_LIVE) {
            $class = "bc-alert bc-alert-success";
        } else if ($getclasslist['status'] == BRAINCERT_STATUS_UPCOMING) {
            $class = "bc-alert bc-alert-warning";
        }
        echo html_writer::start_tag('div', array('class' => 'row'));
        echo html_writer::start_tag('div', array('class' => 'class_list'));
        if ($isteacher) {
            echo braincert_action_menu_list(braincert_teacher_action_list($getclasslist, $braincertrec, $cm), $getclasslist['id']);
        } else if ($isstudent) {
            echo braincert_action_menu_list(braincert_view_recording_button($getclasslist['id']), $getclasslist['id']);
        }

        echo html_writer::start_tag('div', array('class' => 'class_div cl_list span6'));
        // Class name.
        braincert_dispaly_class_name_info($braincertrec, $getclasslist, $class);
        // Class info.
        braincert_display_class_info($getclasslist, $duration);

        // Launch button.
        echo $lauchbutton;

        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
    }
}

echo $OUTPUT->footer();
