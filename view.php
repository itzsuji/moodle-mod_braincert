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
require('../../config.php');
require_once('lib.php');
require_once('locallib.php');
global $DB, $CFG, $USER;
$id = required_param('id', PARAM_INT); // Course Module ID.
$braincertid = optional_param('bcid', 0, PARAM_INT); // Braincert ID.
$all = optional_param('all', 1, PARAM_INT); // Cancel class details.
$bcid = optional_param('bcid', 0, PARAM_INT); // Virtual Class ID.
$task = optional_param('task', '', PARAM_ALPHA);
$classid = optional_param('class_id', '', PARAM_INT);
$amount = optional_param('amount', '', PARAM_TEXT);
$paymentmode = optional_param('payment_mode', '', PARAM_TEXT);
if ($id) {
    if (!$cm = get_coursemodule_from_id('braincert', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record("course", array("id" => $cm->course))) {
        print_error('coursemisconf');
    }
    if (!$braincert = $DB->get_record("braincert", array("id" => $cm->instance))) {
        print_error('invalidcoursemodule');
    }
} else {
    if (!$braincert = $DB->get_record("braincert", array("id" => $braincertid))) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record("course", array("id" => $braincert->course))) {
        print_error('coursemisconf');
    }
    if (!$cm = get_coursemodule_from_instance("braincert", $braincert->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
}
require_login($course, true, $cm);
$context = context_module::instance($cm->id);
$PAGE->set_url('/mod/braincert/view.php', array('id' => $cm->id, 'bcid' => $braincertid));
$url = $CFG->wwwroot . '/mod/braincert/view.php?id=' . $cm->id;
$baseurl = get_config('mod_braincert', 'baseurl');
$PAGE->set_title(format_string($braincert->name));
$pagetitle = get_string('braincert_class', 'braincert');
$pagetitlename = $pagetitle . " " . $braincert->name;
$PAGE->set_heading(format_string($pagetitlename));
$PAGE->set_context($context);
$PAGE->requires->css('/mod/braincert/css/styles.css', true);
if ($CFG->version < 2017051500) {
    $PAGE->requires->css('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', true);
}
$PAGE->requires->js('/mod/braincert/js/jquery.min.js', true);
$PAGE->requires->js('/mod/braincert/js/classsettings.js', true);
$PAGE->requires->js('/mod/braincert/js/video.js', true);
$PAGE->requires->js('/mod/braincert/js/util.js', true);

if ($bcid > 0) {
    $getremovestatus = braincert_cancel_class($bcid, $all);
    if ($getremovestatus['status'] == BRAINCERT_STATUS_OK) {
        echo get_string('braincert_class_removed', 'braincert');
        redirect(new moodle_url('/mod/braincert/view.php?id=' . $id));
    } else {
        echo $getremovestatus['error'];
    }
}
if ($task == "returnpayment") {
    require_sesskey();
    $record = new stdClass();
    $record->class_id = $classid;
    $record->mc_gross = $amount;
    $record->payer_id = $USER->id;
    $record->payment_mode = $paymentmode;
    $record->date_purchased = date('Y-m-d H:i:s', time());
    $insert = $DB->insert_record('braincert_class_purchase', $record);
    redirect($url);
}
echo $OUTPUT->header();
$braincertclass = $DB->get_record('braincert', array('id' => $cm->instance));
$contextid = context_course::instance($braincertclass->course);
$roles = get_user_roles($contextid, $USER->id);
$admins = get_admins();
$sesskey = sesskey();
$isadmin = false;
foreach ($admins as $admin) {
    if ($USER->id == $admin->id) {
        $isadmin = true;
        $SESSION->persona = BRAINCERT_MODE_PERSONA_ADMIN;
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
            $SESSION->persona = BRAINCERT_MODE_PERSONA_TEACHER;
        } else if (!$isstudent && $role->shortname == 'student') {
            $isstudent = 1;
            $SESSION->persona = BRAINCERT_MODE_PERSONA_STUDENT;
        }
    }
}
$getplan = braincert_get_plan();
$paymentinfo = braincert_get_payment_info();
$getclassdetail = braincert_get_class($braincertclass->class_id);
$pricelist = braincert_get_price_list($braincertclass->class_id);

if ($getclassdetail["ispaid"] == 1 && !$isteacher) {
    $getuserpaymentdetails = $DB->get_record(
        'braincert_class_purchase',
        array('class_id' => $braincertclass->class_id, 'payer_id' => $USER->id)
    );
} else {
    $getuserpaymentdetails = false;
}
if (!empty($braincertclass)) {
    $currencysymbol = '';
    $currencycode = '';
    $lauchbutton = braincert_dispaly_luanch_button($getclassdetail, $course->id,
            $cm, $getuserpaymentdetails, $isteacher);
    $duration = $getclassdetail['duration'] / 60;
    if ($getclassdetail['status'] == BRAINCERT_STATUS_PAST) {
        $class = "bc-alert bc-alert-danger";
    } else if ($getclassdetail['status'] == BRAINCERT_STATUS_LIVE) {
        $class = "bc-alert bc-alert-success";
    } else if ($getclassdetail['status'] == BRAINCERT_STATUS_UPCOMING) {
        $class = "bc-alert bc-alert-warning";
    }

    switch ($getclassdetail['currency']) {
        case BRAINCERT_CURRENCY_GBP:
            $currencysymbol = BRAINCERT_CURRENCY_GBP_SYMBOL;
            break;
        case BRAINCERT_CURRENCY_CAD:
            $currencysymbol = BRAINCERT_CURRENCY_CAD_SYMBOL;
            break;
        case BRAINCERT_CURRENCY_AUD:
            $currencysymbol = BRAINCERT_CURRENCY_AUD_SYMBOL;
            break;
        case BRAINCERT_CURRENCY_EUR:
            $currencysymbol = BRAINCERT_CURRENCY_EUR_SYMBOL;
            break;
        case BRAINCERT_CURRENCY_INR:
            $currencysymbol = BRAINCERT_CURRENCY_INR_SYMBOL;
            break;
        default:
            $currencysymbol = "$";
    }

    $currencycode = strtoupper($getclassdetail['currency']);
    if ($getclassdetail["ispaid"] == 1) {
        if (isset($pricelist['Price']) && $pricelist['Price'] == BRAINCERT_STATUS_NO_PRICE && $isteacher) {
            braincert_dispaly_no_price_modal($pricelist['Price']);
        } else {
            // Displaying payment methods in modal.
            braincert_display_payment_modal($pricelist, $currencysymbol, $paymentinfo);
            // Ending of payment modal.
        }
        braincert_paypal_payment_form($baseurl);
    }
    if ($getclassdetail) {
        // Displaying action menu for the calss.
        echo html_writer::start_div('class_list');
        if ($isteacher) {
            echo braincert_action_menu_list(braincert_teacher_action_list($getclassdetail,
                    $braincertclass, $cm), $getclassdetail['id']);
        } else if ($isstudent) {
            echo braincert_action_menu_list(braincert_view_recording_button($getclassdetail['id']), $getclassdetail['id']);
        }
        echo html_writer::end_div();
        // End of action menu.
        // Dispalying class details.
        echo html_writer::start_div('class_div cl_list');
        braincert_dispaly_class_name_info($braincertclass, $getclassdetail, $class);
        braincert_display_class_info($getclassdetail, $duration);
        echo $lauchbutton;
        echo html_writer::end_div();
        // End of dispalying class details.
    }
}
if (!empty($braincertclass)) {
    braincert_display_class_recording($braincertclass->class_id);
}
$params = new stdClass();
$params->plan_commission = $getplan['commission'];
$params->class_id = $braincertclass->class_id;
$params->url = $url;
$params->sesskey = $sesskey;
$params->user_email = $USER->email;
$params->currencysymbol = $currencysymbol;
$params->base_url_api = 'https://www.braincert.com/';
if (strpos($baseurl, 'braincert.org') !== false) {
    $params->base_url_api = "https://www.braincert.org/";
}

$PAGE->requires->js_function_call('init', array($params), false);
if (isset($paymentinfo['paypal_id'])) {
    echo braincert_paypal_form($baseurl, $paymentinfo['paypal_id'], $braincertclass->name, $currencycode, $url);
}
echo braincert_get_vaiw_all_class_link($course);

echo $OUTPUT->footer();
