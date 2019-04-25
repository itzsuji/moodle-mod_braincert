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

$contextid = context_course::instance($id);
$roles = get_user_roles($contextid, $USER->id);
$admins = get_admins();
$isadmin = false;
foreach ($admins as $admin) {
    if ($USER->id == $admin->id) {
        $isadmin = true;
        break;
    }
}
$isteacher = 0;
$isstudent = 0;
if ($isadmin) {
    $isteacher = 1;
} else {
    foreach ($roles as $role) {
        if (!$isteacher && (($role->shortname == 'editingteacher') || ($role->shortname == 'teacher'))) {
            $isteacher = 1;
        } elseif (!$isstudent && $role->shortname == 'student') {
            $isstudent = 1;
        }
    }
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
            $getuserpaymentdetails = $DB->get_record('virtualclassroom_purchase', array(
                'class_id' => $braincertrec->class_id, 'payer_id' => $USER->id));
        } else {
            $getuserpaymentdetails = false;
        }
        $duration = $getclasslist['duration'] / 60;
        if ($getclasslist['status'] == BRAINCERT_STATUS_PAST) {
            $class = "bc-alert bc-alert-danger";
        } elseif ($getclasslist['status'] == BRAINCERT_STATUS_LIVE) {
            $class = "bc-alert bc-alert-success";
        } elseif ($getclasslist['status'] == BRAINCERT_STATUS_UPCOMING) {
            $class = "bc-alert bc-alert-warning";
        }
        echo html_writer::start_tag('div', array('class' => 'row'));
        echo html_writer::start_tag('div', array('class' => 'class_list'));
        echo action_list($getclasslist, $id, $braincertrec, $cm, $isteacher, $isstudent);
        echo html_writer::start_tag('div', array('class' => 'class_div cl_list span6'));
        //class name
        dispaly_class_name_info($braincertrec, $getclasslist, $class);
        //class info
        display_class_info($getclasslist, $duration);
        
        if (($getclasslist['ispaid'] == 1) &&
            ($getclasslist['status'] != BRAINCERT_STATUS_PAST) &&
            ($isteacher == 0) && !$getuserpaymentdetails) {
            $getbraincertgroup = $DB->get_records('groupings_groups', array('groupingid' => $cm->groupingid));
            if ($getbraincertgroup) {
                foreach ($getbraincertgroup as $getbraincertgroupkey => $getbraincertgroupval) {
                    $getgroupmembers = $DB->get_records('groups_members', array(
                        'groupid' => $getbraincertgroupval->groupid,
                        'userid' => $USER->id));
                    if ($getgroupmembers) {
                        echo '<a target="_blank" class="btn btn-primary" id="buy-btn"
                           href="' . $CFG->wwwroot . '/mod/braincert/view.php?id="' . $cm->id . '" return false;>
                           <i class="fa fa-shopping-cart" aria-hidden="true"></i>'
                        . get_string('buy', 'braincert') . '
                        </a>';
                    }
                }
            } else {
                echo '<a target="_blank" class="btn btn-primary" id="buy-btn"
                           href="' . $CFG->wwwroot . '/mod/braincert/view.php?id="' . $cm->id . '" return false;>
                           <i class="fa fa-shopping-cart" aria-hidden="true"></i>'
                . get_string('buy', 'braincert') . '
                        </a>';
            }
        }
        if ($getclasslist['status'] == BRAINCERT_STATUS_LIVE) {
            $braincertclass = $DB->get_record('braincert', array('course' => $id, 'class_id' => $getclasslist['id']));
            if (!empty($braincertclass) && ($getclasslist['ispaid'] == 0 || $getclasslist['ispaid'] == 1)) {
                echo get_launch_button(
                    $braincertclass,
                    $cm,
                    $getuserpaymentdetails,
                    $getclasslist['ispaid'],
                    $isadmin,
                    $isteacher
                );
            }
        } elseif ($isteacher && $getclasslist['status'] != BRAINCERT_STATUS_PAST) {
            $braincertclass = $DB->get_record('braincert', array('course' => $id, 'class_id' => $getclasslist['id']));
            if (!empty($braincertclass)) {
                $data['task'] = BRAINCERT_TASK_GET_CLASS_LAUNCH;
                $data['userId'] = $USER->id;
                $data['userName'] = $USER->firstname;
                $data['lessonName'] = preg_replace('/\s+/', '', $braincertclass->name);
                $data['courseName'] = preg_replace('/\s+/', '', $braincertclass->name);
                $data['isTeacher'] = $isteacher;
                $data['class_id'] = $braincertclass->class_id;
                echo get_launch_button(
                    $braincertclass,
                    $cm,
                    $getuserpaymentdetails,
                    $getclasslist['ispaid'],
                    $isadmin,
                    $isteacher,
                    $data
                );
            }
        }


        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
    }
}

echo $OUTPUT->footer();
