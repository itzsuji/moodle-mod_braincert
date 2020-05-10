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
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Braincert Curl Request.
 *
 * @param array $data
 * @return array
 */
function braincert_get_curl_info($data) {
    global $CFG;
    require_once($CFG->libdir . '/filelib.php');

    $key = $CFG->mod_braincert_apikey;
    $baseurl = $CFG->mod_braincert_baseurl;

    $urlfirstpart = $baseurl . "/" . $data['task'] . "?apikey=" . $key;

    if (($data['task'] == BRAINCERT_TASK_GET_PAYMENT_INFO) || ($data['task'] == BRAINCERT_TASK_GET_PLAN)) {
        $location = $baseurl;
    } else {
        $location = braincert_post_url($urlfirstpart, $data);
    }

    $postdata = '';
    if ($data['task'] == BRAINCERT_TASK_GET_PAYMENT_INFO) {
        $postdata = 'task=getPaymentInfo&apikey=' . $key;
    } else if ($data['task'] == BRAINCERT_TASK_GET_PLAN) {
        $postdata = 'task=getplan&apikey=' . $key;
    }

    $options = array(
        'CURLOPT_RETURNTRANSFER' => true, 'CURLOPT_SSL_VERIFYHOST' => false, 'CURLOPT_SSL_VERIFYPEER' => false,
    );

    $curl = new curl();
    $result = $curl->post($location, $postdata, $options);

    $finalresult = json_decode($result, true);
    return $finalresult;
}

/**
 *
 * @param string $urlfirstpart
 * @param array $data
 * @return string
 */
function task_add_scheme_params($urlfirstpart, $data) {
    $initurl = $urlfirstpart . "&class_id=" . $data['class_id'] . "&price=" . $data['price'] . "&scheme_days="
        . $data['scheme_days'] . "&times=" . $data['times'];
    if (isset($data['numbertimes'])) {
        $initurl = $initurl . "&numbertimes=" . $data['numbertimes'];
    }
    if (isset($data['id'])) {
        $initurl = $initurl . "&id=" . $data['id'];
    }
    return $initurl;
}

/**
 *
 * @param string $urlfirstpart
 * @param array $data
 * @return string
 */
function task_add_special_params($urlfirstpart, $data) {
    $initurl = $urlfirstpart . "&class_id=" . $data['class_id'] . "&discount=" . $data['discount']
        . "&start_date=" . $data['start_date'] . "&discount_type=" . $data['discount_type'];
    if (isset($data['end_date'])) {
        $initurl = $initurl . "&end_date=" . $data['end_date'];
    }
    if (isset($data['discount_code']) && isset($data['discount_limit'])) {
        $initurl = $initurl . "&discount_code=" . $data['discount_code'] . "&discount_limit="
            . $data['discount_limit'];
    }
    if (isset($data['discountid'])) {
        $initurl = $initurl . "&discountid=" . $data['discountid'];
    }
    return $initurl;
}

/**
 *
 * @param string $urlfirstpart
 * @param array $data
 * @return string
 */
function task_scheduler_params($urlfirstpart, $data) {
    $initurl = $urlfirstpart . "&title=" . $data['title'] . "&timezone=" . $data['timezone'] .
        "&start_time=" . $data['start_time'] . "&end_time=" . $data['end_time'] . "&date=" .
        $data['date'] . "&isVideo=" . $data['isvideo'] . "&ispaid=" . $data['ispaid']
        . "&is_recurring=" . $data['is_recurring'] . "&seat_attendees=" . $data['seat_attendees'] . "&record="
        . $data['record'] . "&isBoard=" . $data['isBoard'] . "&isLang=" . $data['isLang'] . "&isRegion=" .
        $data['isRegion'] . "&isCorporate=" . $data['isCorporate'] . "&isScreenshare="
        . $data['isScreenshare'] . "&isPrivateChat=" . $data['isPrivateChat'];

    if (($data['ispaid'] == 1) && isset($data['currency'])) {
        $initurl = $initurl . "&currency=" . $data['currency'];
    }
    if (isset($data['isRecordingLayout'])) {
        $initurl = $initurl . "&isRecordingLayout=" . $data['isRecordingLayout'];
    }
    if (($data['is_recurring'] == 1) && isset($data['repeat'])) {
        $initurl = $initurl . "&repeat=" . $data['repeat'] . "&end_classes_count=" . $data['end_classes_count'];
        if ($data['repeat'] == 6) {
            $initurl = $initurl . "&weekdays=" . $data['weekdays'];
        }
    }
    if (isset($data['cid'])) {
        $initurl = $initurl . "&cid=" . $data['cid'];
    }

    return $initurl;
}

/**
 *
 * @param string $urlfirstpart
 * @param array $data
 * @return string
 */
function braincert_post_url($urlfirstpart, $data) {
    switch ($data['task']) {
        case BRAINCERT_TASK_GET_CLASS_LAUNCH:
            $initurl = $urlfirstpart . "&class_id=" . $data['class_id'] . "&userId=" . $data['userId'] . "&userName="
            . urlencode($data['userName']) . "&isTeacher=" . $data['isTeacher'] . "&courseName="
            . $data['courseName'] . "&lessonName=" . $data['lessonName'];
            break;
        case BRAINCERT_TASK_CLASS_LIST:
            $initurl = $urlfirstpart;
            break;
        case BRAINCERT_TASK_REMOVE_CLASS:
            $initurl = $urlfirstpart . "&cid=" . $data['cid'];
            break;
        case BRAINCERT_TASK_CANCEL_CLASS:
            $initurl = $urlfirstpart . "&class_id=" . $data['class_id'] . "&isCancel=" . $data['isCancel'];
            break;

        case BRAINCERT_TASK_REMOVE_PRICE:
            $initurl = $urlfirstpart . "&id=" . $data['id'];
            break;

        case BRAINCERT_TASK_REMOVE_DISCOUNT:
            $initurl = $urlfirstpart . "&discountid=" . $data['discountid'];
            break;
        case BRAINCERT_TASK_GET_CLASS_REPORT:
            $initurl = $urlfirstpart . "&classId=" . $data['classId'];
            break;

        default:
            $initurl = set_braincert_url($urlfirstpart, $data);
            break;
    }
    return $initurl;
}

function set_braincert_url($urlfirstpart, $data) {
    if ($data['task'] == BRAINCERT_TASK_GET_CLASS_RECORDING) {
        return $urlfirstpart . "&class_id=" . $data['class_id'];
    } else if ($data['task'] == BRAINCERT_TASK_CHANGE_STATUS_RECORDING || $data['task'] == BRAINCERT_TASK_REMOVE_CLASS_RECORDING) {
        return $urlfirstpart . "&id=" . $data['rid'];
    } else if ($data['task'] == BRAINCERT_TASK_SCHEDULE) {
        return task_scheduler_params($urlfirstpart, $data);
    } else if ($data['task'] == BRAINCERT_TASK_ADD_SCHEMES) {
        return task_add_scheme_params($urlfirstpart, $data);
    } else if ($data['task'] == BRAINCERT_TASK_ADD_SPECIALS) {
        return task_add_special_params($urlfirstpart, $data);
    } else if ($data['task'] == BRAINCERT_TASK_LIST_SCHEMES || $data['task'] == BRAINCERT_TASK_LIST_DISCOUNT) {
        return $urlfirstpart . "&class_id=" . $data['class_id'];
    } else {
        return $urlfirstpart;
    }
}

/**
 * braincert_get_plan.
 *
 * @return array
 */
function braincert_get_plan() {
    $data['task'] = BRAINCERT_TASK_GET_PLAN;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_class_list.
 *
 * @return array
 */
function braincert_get_class_list() {
    $data['task'] = BRAINCERT_TASK_CLASS_LIST;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_class.
 *
 * @param int $classid
 * @return int
 */
function braincert_get_class($classid) {
    global $CFG;
    require_once($CFG->libdir . '/filelib.php');
    $curl = new curl();
    $options = array(
        'CURLOPT_RETURNTRANSFER' => true,
        'CURLOPT_SSL_VERIFYHOST' => false,
        'CURLOPT_SSL_VERIFYPEER' => false,
    );

    $result = $curl->post($CFG->mod_braincert_baseurl, 'task=getclass&apikey='
        . $CFG->mod_braincert_apikey . '&class_id=' . $classid, $options);

    $result = json_decode($result, true);
    if ($result) {
        return $result[0];
    }
}

/**
 * braincert_remove_class.
 *
 * @param int $classid
 * @return array
 */
function braincert_remove_class($classid) {
    $data['task'] = BRAINCERT_TASK_REMOVE_CLASS;
    $data['cid'] = $classid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_cancel_class.
 *
 * @param int $classid
 * @param int $all
 * @return array
 */
function braincert_cancel_class($classid, $all) {
    $data['task'] = BRAINCERT_TASK_CANCEL_CLASS;
    $data['class_id'] = $classid;
    $data['isCancel'] = $all;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_class_report.
 *
 * @param int $classid
 * @return array
 */
function braincert_get_class_report($classid) {
    $data['task'] = BRAINCERT_TASK_GET_CLASS_REPORT;
    $data['classId'] = $classid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_payment_info.
 *
 * @return array
 */
function braincert_get_payment_info() {
    $data['task'] = BRAINCERT_TASK_GET_PAYMENT_INFO;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_launch_url.
 *
 * @param array $item
 * @return array
 */
function braincert_get_launch_url($item) {
    $data['task'] = BRAINCERT_TASK_GET_CLASS_LAUNCH;
    $data['userId'] = $item['userid'];
    $data['userName'] = $item['username'];
    $data['lessonName'] = preg_replace('/\s+/', '', $item['classname']);
    $data['courseName'] = preg_replace('/\s+/', '', $item['classname']);
    $data['isTeacher'] = $item['isteacher'];
    $data['class_id'] = $item['classid'];

    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_class_recording.
 *
 * @param int $classid
 * @return array
 */
function braincert_get_class_recording($classid) {
    $data['task'] = BRAINCERT_TASK_GET_CLASS_RECORDING;
    $data['class_id'] = $classid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_change_status_recording.
 *
 * @param int $rid
 * @return array
 */
function braincert_change_status_recording($rid) {
    $data['task'] = BRAINCERT_TASK_CHANGE_STATUS_RECORDING;
    $data['rid'] = $rid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_remove_recording.
 *
 * @param int $rid
 * @return array
 */
function braincert_remove_recording($rid) {
    $data['task'] = BRAINCERT_TASK_REMOVE_CLASS_RECORDING;
    $data['rid'] = $rid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_add_price.
 *
 * @param array $item
 * @return array
 */
function braincert_add_price($item) {
    $data['task'] = BRAINCERT_TASK_ADD_SCHEMES;
    $data['price'] = $item['price'];
    $data['scheme_days'] = $item['schemedays'];
    $data['times'] = $item['times'];
    $data['class_id'] = $item['classid'];
    if (isset($item['numbertimes'])) {
        $data['numbertimes'] = $item['numbertimes'];
    }
    if (isset($item['id'])) {
        $data['id'] = $item['id'];
    }
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_get_price_list.
 *
 * @param int $classid
 * @return array
 */
function braincert_get_price_list($classid) {
    $data['task'] = BRAINCERT_TASK_LIST_SCHEMES;
    $data['class_id'] = $classid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_remove_price.
 *
 * @param int $pid
 * @return array
 */
function braincert_remove_price($pid) {
    $data['task'] = BRAINCERT_TASK_REMOVE_PRICE;
    $data['id'] = $pid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_add_discount.
 *
 * @param array $item
 * @return array
 */
function braincert_add_discount($item) {
    $data['task'] = BRAINCERT_TASK_ADD_SPECIALS;
    $data['class_id'] = $item['classid'];
    $data['discount'] = $item['discount'];
    $data['start_date'] = $item['startdate'];
    $data['discount_type'] = $item['dtype'];
    if (isset($item['enddate'])) {
        $data['end_date'] = $item['enddate'];
    }
    if (isset($item['dcode']) && isset($item['dlimit'])) {
        $data['discount_code'] = $item['dcode'];
        $data['discount_limit'] = $item['dlimit'];
    }
    if ($item['did'] > 0) {
        $data['discountid'] = $item['did'];
    }
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_discount_list.
 *
 * @param int $classid
 * @return array
 */
function braincert_discount_list($classid) {
    $data['task'] = BRAINCERT_TASK_LIST_DISCOUNT;
    $data['class_id'] = $classid;
    $result = braincert_get_curl_info($data);
    return $result;
}

/**
 * braincert_remove_discount.
 *
 * @param int $did
 * @return array
 */
function braincert_remove_discount($did) {
    $data['task'] = BRAINCERT_TASK_REMOVE_DISCOUNT;
    $data['discountid'] = $did;
    $result = braincert_get_curl_info($data);
    return $result;
}

function action_menu_list($menuitmes, $classid) {
     $output = html_writer::start_tag('div', array('class' => 'span6 drop_fr_icon'));
    $output .= html_writer::start_tag('div', array('class' => 'dropdown'));
    $output .= html_writer::tag('a', '<i class="fa fa-cog" aria-hidden="true"></i><b class="caret"></b>', array(
            'class' => 'dropbtn', 'id' => 'dropbtn', 'href' => 'javascript:void(0);',
            'onclick' => 'dropdownmenu(' . $classid . ')',
    ));
    $output .= html_writer::start_tag(
        'div',
        array('class' => 'dropdown-content', 'id' => 'dropdown-' . $classid)
    );
    $output .= $menuitmes;
    $output .= html_writer::end_tag('div');
    $output .= html_writer::end_tag('div');
    $output .= html_writer::end_tag('div');
    return $output;
}

/**
 * This method will display action menu
 *
 * @global global $CFG
 * @param array $getclasslist
 * @param object $braincertrec
 * @param object $cm
 * @param boolean $isteacher
 * @param boolean $isstudent
 * @return string
 */
function teacher_action_list($getclasslist, $braincertrec, $cm) {
     global $CFG, $PAGE;
    $output = '';
    $output .= html_writer::tag('a', '<i class="fa fa-users" aria-hidden="true"></i>'
            . get_string('attendancereport', 'braincert'), array(
            'href' => $CFG->wwwroot . '/mod/braincert/attendance_report.php?bcid=' . $getclasslist['id'],
    ));
    $output .= html_writer::tag('a', '<i class="fa fa-minus-circle" aria-hidden="true"></i>'
            . get_string('cancelclass', 'braincert'), array(
            'href' => $PAGE->url . '&bcid=' . $getclasslist['id'],
            'onclick' => 'return confirm("' . get_string("areyousure", "braincert") . '")'
    ));
    if ($braincertrec->is_recurring == 1) {
        $output .= html_writer::tag('a', '<i class="fa fa-minus-circle" aria-hidden="true"></i>'
                . get_string('cancelclassall', 'braincert'), array(
                'href' => $PAGE->url . '&all=2&bcid='
                . $getclasslist['id'],
                'onclick' => 'return confirm("' . get_string("areyousureall", "braincert") . '")'
        ));
    }
    $output .= html_writer::tag('hr', '');
    $output .= html_writer::tag('a', '<i class="fa fa-envelope" aria-hidden="true"></i>'
            . get_string('inviteemail', 'braincert'), array(
            'href' => $CFG->wwwroot . '/mod/braincert/inviteemail.php?bcid=' . $getclasslist['id'],
    ));
    if ($cm->groupmode != 0) {
        $output .= html_writer::tag('a', '<i class="fa fa-envelope" aria-hidden="true"></i>'
                . get_string('inviteusergroup', 'braincert'), array(
                'href' => $CFG->wwwroot . '/mod/braincert/inviteusergroup.php?bcid=' . $getclasslist['id'],
        ));
    }
    if ($getclasslist['ispaid'] == 1) {
        $output .= html_writer::tag('a', '<i class="fa fa-shopping-cart" aria-hidden="true"></i>'
                . get_string('shoppingcart', 'braincert'), array(
                'href' => $CFG->wwwroot . '/mod/braincert/addpricingscheme.php?bcid=' . $getclasslist['id'],
        ));
        $output .= html_writer::tag('a', '<i class="fa ticket" aria-hidden="true"></i>'
                . get_string('discounts', 'braincert'), array(
                'href' => $CFG->wwwroot . '/mod/braincert/adddiscount.php?bcid=' . $getclasslist['id'],
        ));
        $output .= html_writer::tag('a', '<i class="fa cc-paypal" aria-hidden="true"></i>'
                . get_string('payments', 'braincert'), array(
                'href' => $CFG->wwwroot . '/mod/braincert/payments.php?bcid=' . $getclasslist['id'],
        ));
    }
    $output .= html_writer::tag('hr', '');
    $output .= view_recording_button($getclasslist['id']);
    $output .= html_writer::tag(
            'a', '<i class="fa fa-play-circle-o" aria-hidden="true"></i>'
            . get_string('managerecording', 'braincert'), array(
            'href' => $CFG->wwwroot . '/mod/braincert/recording.php?action=managerecording&bcid=' .
            $getclasslist['id'],
            )
    );
    $output .= html_writer::tag('a', '<i class="fa fa-envelope" aria-hidden="true"></i>' .
            get_string('managetemplate', 'braincert'), array(
            'href' => $CFG->wwwroot . '/mod/braincert/managetemplate.php?bcid=' . $getclasslist['id'],
    ));
    return $output;
}

/**
 *
 * @global global $CFG
 * @param int $classid
 * @return string
 */
function view_recording_button ($classid) {
    global $CFG;
    $output = html_writer::tag(
        'a',
        '<i class="fa fa-play-circle-o" aria-hidden="true"></i>'
            . get_string('viewclassrecording', 'braincert'),
        array(
            'href' => $CFG->wwwroot . '/mod/braincert/recording.php?action=viewrecording&bcid='
            . $classid,
            )
    );
    return $output;
}

/**
 *
 * @global global $USER
 * @global global $SESSION
 * @global global $DB
 * @param array $getclasslist
 * @param int - course $id
 * @param object $cm
 * @param array $paymentdetails
 * @param boolean $isteacher
 * @param string $buttontype - button | link
 * @return string
 */
function dispaly_luanch_button(&$getclasslist, $id, $cm, $paymentdetails, $isteacher=0, $buttontype = 'button') {
    global $USER, $SESSION, $DB;
    if ($getclasslist['status'] == BRAINCERT_STATUS_PAST) {
        return '';
    }
    $braincert = $DB->get_record('braincert', array('course' => $id, 'class_id' => $getclasslist['id']));
    if (!$braincert) {
        echo "<strong>" . get_string('invalidclassid', 'braincert') . "</strong>";
        return;
    }
    $item['userid'] = $USER->id;
    $item['username'] = $USER->firstname;
    $item['classname'] = $braincert->name;
    $item['isteacher'] = $isteacher;
    $item['classid'] = $braincert->class_id;
    $getlaunchurl = braincert_get_launch_url($item);
    if ($getlaunchurl['status'] == BRAINCERT_STATUS_OK) {
        switch($SESSION->persona) {
            case 1:
            case 2:
                return teacher_lunch_button($getclasslist, $getlaunchurl['launchurl']);
            case 3:
                return student_lunch_button($getclasslist, $cm, $paymentdetails, $getlaunchurl['launchurl'], $buttontype);
            default:
                break;
        }
    } else if ($getlaunchurl['status'] == BRAINCERT_STATUS_ERROR) {
        return "<strong>" . $getlaunchurl["error"] . "</strong>";
    }
    return '';
}

/**
 *
 * @param array $getclasslist
 * @param string $launchurl
 * @return string
 */
function teacher_lunch_button(&$getclasslist, $launchurl) {
    if ($getclasslist['status'] == BRAINCERT_STATUS_LIVE) {
        return create_launch_button($launchurl);
    } else if ($getclasslist['status'] = BRAINCERT_STATUS_UPCOMING ) {
        date_default_timezone_set($getclasslist['timezone_country']);
        $afterminutes = (strtotime($getclasslist['date'].' '.$getclasslist['start_time']) - time()) / 60;
        if ($afterminutes > 0 && $afterminutes <= 30) {
            $getclasslist['status'] = BRAINCERT_STATUS_LIVE;
            return create_launch_button($launchurl, get_string('prepareclass', 'braincert'));
        } else {
            return create_launch_button($launchurl);
        }
    }
    return '';
}

/**
 *
 * @param array $getclasslist
 * @param object $cm
 * @param array $paymentdetails
 * @param string $launchurl
 * @param string $buttontype - button | link
 * @return string
 */
function student_lunch_button($getclasslist, $cm, $paymentdetails, $launchurl, $buttontype = 'button') {
    $launch = '';

    if ($getclasslist['ispaid'] == 1 && !$paymentdetails) {
        $launch = dispaly_buy_button($cm, $getclasslist['id'], $buttontype);
    } else if ($getclasslist['status'] == BRAINCERT_STATUS_LIVE) {
        $launch = get_lauch_button($cm, $launchurl);
    }

    return $launch;
}
/**
 *
 * @global global $USER
 * @global global $DB
 * @param object $cm
 * @param string $launchurl
 * @return string
 */
function get_lauch_button($cm, $launchurl) {
    global $USER, $DB;
    if ($cm->groupmode != 1) {
        return create_launch_button($launchurl);
    } else {
        $getbraincertgroup = $DB->get_records(
            'groupings_groups', array('groupingid' => $cm->groupingid)
        );
        foreach ($getbraincertgroup as $getbraincertgroupval) {
            $getgroupmembers = $DB->get_records(
                'groups_members', array(
                'groupid' => $getbraincertgroupval->groupid, 'userid' => $USER->id
                )
            );
            if ($getgroupmembers) {
                return create_launch_button($launchurl);
                break;
            }
        }
    }
}

/**
 *
 * @param string $url
 * @param string $button_text
 * @return string
 */
function create_launch_button($url, $buttontext = '') {
    $buttontext = $buttontext ? $buttontext : get_string('launch', 'braincert');
    return '<a target="_blank" class="btn btn-primary" id="launch-btn"'
        . ' href="' . $url . '" return false;>' . $buttontext . '</a>';
}

/**
 *
 * @param string $baseurl
 * @param int $paypalid
 * @param string $itemname
 * @param string $currencycode
 * @param url $url
 * @return string of HTML tags
 */
function paypal_form($baseurl, $paypalid, $itemname, $currencycode, $url) {
    $paypalurl = 'https://www.';
    $paypalurl .= strpos($baseurl, 'braincert.org') !== false ?
        'sandbox.paypal.com/cgi-bin/webscr' : 'paypal.com/cgi-bin/webscr';
    $form = html_writer::start_tag('form', array('action' => $paypalurl, 'method' => 'post',
            'class' => 'paypal-form', 'target' => '_top', 'id' => 'paypal_form_one_time'));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'cmd', 'value' => '_xclick'));
    $form .= html_writer::empty_tag('input', array(
        'type' => 'hidden', 'name' => 'amount', 'id' => 'one_time_amount', 'value' => ''
        ));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'business', 'value' => $paypalid));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'item_name', 'value' => $itemname));
    $form .= html_writer::empty_tag('input', array(
        'type' => 'hidden', 'name' => 'currency_code', 'value' => $currencycode
        ));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'no_note', 'value' => 1));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'no_shipping', 'value' => 1));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'rm', 'value' => 1));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'custom', 'value' => ''));
    $form .= html_writer::empty_tag('input', array(
        'type' => 'hidden', 'name' => 'return', 'id' => 'return_url', 'value' => ''
        ));
    $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'cancel_return', 'value' => $url));
    $form .= html_writer::empty_tag('input', array(
        'type' => 'hidden', 'name' => 'notify_url', 'class' => "one_time_notify_url", 'value' => $url));
    $form .= html_writer::end_tag('form');
    return $form;
}

/**
 *
 * @global global $CFG
 * @param object $course
 * @return string of HTML tags
 */
function get_vaiw_all_class_link($course) {
    global $CFG;
    $return = html_writer::start_div('allclassdiv');
    $return .= html_writer::link(
        $CFG->wwwroot . '/mod/braincert/index.php?id=' . $course->id,
        get_string('viewallclass', 'braincert')
    );
    $return .= html_writer::end_div();
    return $return;
}

/**
 *
 * @global global $SESSION
 * @param type $classid
 */
function display_class_recording($classid) {
    global $SESSION;
    $getrecordinglist = braincert_get_class_recording($classid);
    if (!isset($getrecordinglist['Recording']) &&
        isset($getrecordinglist['status']) && ($getrecordinglist['status'] != BRAINCERT_STATUS_ERROR)
    ) {
        if ($SESSION->persona == BRAINCERT_MODE_PERSONA_ADMIN || $SESSION->persona == BRAINCERT_MODE_PERSONA_TEACHER) {
            $table = new html_table();
            $table->head = array();
            $table->head[] = get_string('colno', 'braincert');
            $table->head[] = get_string('name', 'braincert');
            $table->head[] = get_string('datetime', 'braincert');
            $table->head[] = get_string('action', 'braincert');
            $table->data = get_class_recording_rows($getrecordinglist);
            if (!empty($table->data)) {
                echo html_writer::start_tag('div', array('class' => 'no-overflow display-table'));
                echo html_writer::table($table);
                echo html_writer::end_tag('div');
            }

            echo html_writer::tag('video', '', array('id' => "recording-video",
                'class' => "video-js vjs-default-skin",
                'controls' => true, 'width' => "800", 'height' => '350'
                ));
        }
    }
}

/**
 *
 * @param type $getrecordinglist
 * @return array
 */
function get_class_recording_rows($getrecordinglist) {
    $rows = [];
    $i = 1;
    foreach ($getrecordinglist as $recordinglist) {
        if ($recordinglist['status'] == 1) {
            $row = array();
            $row[] = $i;
            if (!empty($recordinglist['fname'])) {
                $row[] = $recordinglist['fname'];
            } else {
                $row[] = $recordinglist['name'];
            }
            $row[] = $recordinglist['date_recorded'];
            $row[] = '<a href="javascript:void(0)" data-rpath="' . $recordinglist['record_path'] .
                '" class="viewrecording">' . get_string('viewclassrecording', 'braincert') . '</a>';
            $rows[] = $row;
            $i++;
        }
    }
    return $rows;
}

/**
 *
 * @param string $baseurl
 */
function paypal_payment_form($baseurl) {
    echo html_writer::script('', 'https://www.paypalobjects.com/js/external/dg.js');
    if (strpos($baseurl, 'braincert.org') !== false) {
        $paypalurl = 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay';
    } else {
        $paypalurl = 'https://www.paypal.com/webapps/adaptivepayment/flow/pay';
    }
    echo html_writer::start_tag('form', array('action' => $paypalurl, 'target' => 'PPDGFrame', 'class' => 'standard'));
    echo html_writer::empty_tag('input', array('type' => 'image', 'id' => 'submitBtn', 'value' => 'Pay with PayPal',
        'style' => 'display: none;'));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'id' => "type", 'name' => 'expType',
        'value' => 'lightbox'));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'id' => "paykey", 'name' => 'paykey',
        'value' => ''));
    echo html_writer::end_tag('form');
    echo html_writer::script(' var embeddedPPFlow = new PAYPAL.apps.DGFlow({trigger: "submitBtn"});
                        if (window != top) {
                            top.location.replace(document.location);
                        }
                        embeddedPPFlow = top.embeddedPPFlow || top.opener.top.embeddedPPFlow;
                        embeddedPPFlow.closeFlow();');
}
/**
 *
 * @global global $DB
 * @global global $USER
 * @param object $cm
 * @param int $classid
 * @param string $buttontype - button or link
 * @return string
 */
function dispaly_buy_button($cm, $classid, $buttontype = 'button') {
    global $DB, $USER;
    $getbraincertgroup = $DB->get_records(
        'groupings_groups',
        array('groupingid' => $cm->groupingid)
    );
    $param = $buttontype == 'button' ? $classid : $cm->id;
    $method = 'get_buy_'.$buttontype;
    if ($getbraincertgroup) {
        foreach ($getbraincertgroup as $getbraincertgroupval) {
            $getgroupmembers = $DB->get_records(
                'groups_members',
                array(
                'groupid' => $getbraincertgroupval->groupid, 'userid' => $USER->id
                )
            );
            if ($getgroupmembers) {
                return $method($param);
                break;
            }
        }
    } else {
        return $method($param);
    }
}

/**
 *
 * @param int $classid
 * @return string
 */
function get_buy_button($classid) {
    $button = html_writer::start_tag('button', array(
            'class' => 'btn btn-danger btn-sm',
            'onclick' => 'buyingbtn(' . $classid . ')', 'id' => 'buy-btn'
    ));
    $button .= html_writer::start_tag('h4');
    $button .= html_writer::tag('i', '', array('class' => 'fa fa-shopping-cart', 'aria-hidden' => 'true'));
    $button .= get_string('buy', 'braincert');
    $button .= html_writer::end_tag('h4');
    $button .= html_writer::end_tag('button');
    return $button;
}

/**
 *
 * @global global $CFG
 * @param int $moduleid
 * @return string
 */
function get_buy_link($moduleid) {
    global $CFG;
        return '<a target="_blank" class="btn btn-primary" id="buy-btn" '
        . 'href="' . $CFG->wwwroot . '/mod/braincert/view.php?id=' . $moduleid . '" return false;>'
            . '<i class="fa fa-shopping-cart" aria-hidden="true"></i>'. get_string('buy', 'braincert') . '</a>';
}

/**
 *
 * @param array $getclassdetail
 * @param int $duration
 */
function display_class_info($getclassdetail, $duration) {
    date_default_timezone_set($getclassdetail['timezone_country']);
    echo html_writer::start_div('course_info');
    echo html_writer::start_tag('p');
    echo html_writer::tag('i', '', array('class' => 'fa fa-calendar', 'aria-hidden' => true));
    echo $getclassdetail['date'];
    echo html_writer::end_tag('p');
    echo html_writer::start_tag('p');
    echo html_writer::tag('i', '', array('class' => 'fa fa-clock-o', 'aria-hidden' => true));
    echo $getclassdetail['start_time'] . " - " . $getclassdetail['end_time'] . "
                 (" . $duration . " " . get_string('minutes', 'braincert') . ")";
    echo html_writer::end_tag('p');
    echo html_writer::start_tag('p');
    echo html_writer::tag('i', '', array('class' => 'fa fa-globe', 'aria-hidden' => true));
    echo "Time Zone: " . $getclassdetail['timezone_label'];
    echo html_writer::end_tag('p');
    echo html_writer::end_div();
}

/**
 *
 * @param string $message
 */
function dispaly_no_price_modal($message) {
    echo html_writer::start_div('modal pricedescription', array('id' => 'modal-content-buying'));
    echo html_writer::start_div('modal-content', array('style' => 'overflow: hidden;'));
    echo html_writer::tag('span', '<b>' . $message . '</b>');
    echo html_writer::tag('span', '&times;', array('class' => 'close'));
    echo html_writer::end_div();
    echo html_writer::end_div();
}

/**
 *
 * @param array $pricelist
 * @param string $currencysymbol
 */
function display_class_pricing_table($pricelist, $currencysymbol) {
    echo html_writer::start_tag('table', array('class' => 'table table-bordered', 'id' => 'cartcontainer'));
    echo html_writer::start_tag('thead', array('class' => 'alert alert-info'));
    echo html_writer::start_tag('tr', array('class' => 'success'));
    echo html_writer::tag('td', '#', array('style' => 'width: 40px;'));
    echo html_writer::tag('td', get_string('price', 'braincert'));
    echo html_writer::tag('td', get_string('duration', 'braincert'));
    echo html_writer::tag('td', get_string('accesstype', 'braincert'));
    echo html_writer::end_tag('tr');
    echo html_writer::end_tag('thead');
    echo html_writer::start_tag('tbody');
    $xx = 0;
    if (!isset($pricelist['Price'])) {
        echo html_writer::script('jQuery(document).ready(function () {
            jQuery("#pricescheme0").trigger("click");
            });');
        foreach ($pricelist as $value) {
            $price = $value['scheme_price'];
            $optionid = $value['id'];
            $subprice = $price;
            $discount = $price;
            $chkprice = '<span id="displayprice' . $xx . '">' . $currencysymbol
                . ' ' . number_format($price, 2) . '</span>';
            $duration = ($value['lifetime'] == '1') ? "Unlimited" : $value['scheme_days']
                . ($value['scheme_days'] > 1 ? " days" : " day");
            $dur = ($value['lifetime'] == '1') ? 9999 : $value['scheme_days'];
            $times = ($value['times'] == 0) ? "Unlimited" : $value['numbertimes'] .
                ($value['numbertimes'] > 1 ? " times" : " time");
            $tms = ($value['times'] == 0) ? -1 : $value['numbertimes'];
            echo html_writer::start_tag('tr', array('class' => 'warning'));
            echo html_writer::start_tag('td');
            echo html_writer::empty_tag('input', array('type' => 'hidden',
                'id' => 'subpricebeforecoupondiscount' . $xx, 'value' => $discount));
            echo html_writer::empty_tag('input', array('type' => 'hidden',
                'id' => 'originalprice' . $xx, 'value' => $price));
            echo html_writer::empty_tag('input', array('type' => 'radio', 'name' => 'pricescheme',
                'id' => 'pricescheme' . $xx, 'value' => $subprice, 'duration' => $dur, 'times' => $tms,
                'option_id' => $optionid));

            echo html_writer::end_tag('td');
            echo html_writer::tag('td', $chkprice);
            echo html_writer::tag('td', $duration);
            echo html_writer::tag('td', $times);
            echo html_writer::end_tag('tr');
            $xx++;
        }
    }
    echo html_writer::end_tag('tbody');
    echo html_writer::end_tag('table');
}

/**
 *
 * @global global $CFG
 * @param array $pricelist
 * @param string $currencysymbol
 * @param array $paymentinfo
 */
function display_payment_modal($pricelist, $currencysymbol, $paymentinfo) {
    global $CFG;
    echo html_writer::start_div('modal pricedescription initialhide', array('id' => 'modal-content-buying'));
    echo html_writer::start_div('modal-content', array('style' => 'overflow: hidden;'));

    echo html_writer::span('<b>' . get_string('buyingoption', 'braincert') . '</b>');
    echo html_writer::span('&times;', 'close');
    echo html_writer::div('', 'card_error', array('style' => 'display: none;
                         color: #a94442;background-color: #f2dede;
                         border-color: #ebccd1;border-radius: 5px;
                         margin-bottom: 10px;padding: 8px;'));
    display_class_pricing_table($pricelist, $currencysymbol);
    // Payment container div start.
    echo html_writer::start_div('modal-content', array('id' => 'paymentcontainer'));

    if ($paymentinfo['type'] == '1') {
        card_payment_fields($paymentinfo);
    } else {
        echo html_writer::img($CFG->wwwroot . '/mod/braincert/images/secured-by-paypal.jpg', '');
    }
    echo html_writer::end_div();
    // Payment container div start.
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'class_final_amount',
        'id' => 'class_final_amount', 'value' => ''));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'class_price_id',
        'id' => 'class_price_id', 'value' => ''));
    echo html_writer::tag('h5', get_string('subtotal', 'braincert') . ':  ', array(
        'style' => 'float: left; font-size: 20px; line-height: 35px; margin: 0;'
    ));
    echo html_writer::div('', '', array(
        'id' => 'subvalue', 'style' => 'float: left; margin-top: 8px; font-color: blue;'
    ));

    echo html_writer::start_div('', array('id' => 'btncontainer', 'style' => 'float: right;'));
    if (!isset($pricelist['Price'])) {
        echo html_writer::tag('button', get_string('buyclass', 'braincert'), array(
            'id' => 'btnCheckout', 'class' => 'btn btn-primary'
            ));
    }
    echo html_writer::end_div('div');

    echo html_writer::div(get_string('processing', 'braincert'), '', array(
        'id' => 'txtprocessing', 'style' => 'display:none;float: right;'
    ));
    echo html_writer::tag('p', '');

    echo html_writer::end_div();
    echo html_writer::end_div();
}

/**
 *
 * @param array $paymentinfo
 */
function card_payment_fields ($paymentinfo) {
    echo html_writer::start_div('row');
    echo html_writer::start_div('span5');

    echo html_writer::start_tag('fieldset');

    echo html_writer::tag('p', '', array('style' => 'display:none', 'class' => 'alert payment-message'));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => "access_token",
        'id' => "access_token", 'value' => $paymentinfo['access_token']));
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'name' => "item_number",
        'id' => "item_number", 'value' => ''));

    echo html_writer::start_div('control-group');
    echo html_writer::label(get_string('cardholdername', 'braincert'), null, false, array(
        'style' => 'width: 140px; padding-top: 5px; float: left;text-align: right;'
    ));
    echo html_writer::start_div('', array('style' => 'margin-left: 160px;'));
    echo html_writer::empty_tag('input', array('type' => 'text', 'tabindex' => '4',
        'class' => 'required', 'name' => 'full_name', 'id' => 'full_name'));
    echo html_writer::end_div();
    echo html_writer::end_div();

    // Card details fields.
    echo html_writer::start_div('control-group');
    echo html_writer::label(get_string('cardnumber', 'braincert') . '&nbsp;&amp;&nbsp;'
        . get_string('ccv', 'braincert'), null, false, array(
        'style' => 'width: 140px; padding-top: 5px; float: left; text-align: right;'
    ));
    echo html_writer::start_div('', array('style' => 'margin-left: 160px;'));
    echo html_writer::empty_tag('input', array('type' => 'text', 'tabindex' => '5',
        'name' => 'card-number', 'class' => 'card-number stripe-sensitive required',
        'autocomplete' => 'off', 'style' => 'width: 130px;', 'maxlength' => '16'));
    echo html_writer::empty_tag('input', array('type' => 'text', 'tabindex' => '6',
        'name' => 'card-cvc', 'class' => 'card-cvc stripe-sensitive required',
        'autocomplete' => 'off', 'style' => 'width: 50px;', 'maxlength' => '16'));
    echo html_writer::tag('i', '', array('class' => 'icon-lock'));
    echo html_writer::end_div();
    echo html_writer::end_div();
    // End.
    // Card expiration date field.
    echo html_writer::start_div('control-group');
    echo html_writer::label(get_string('expiration_date', 'braincert'), null, false, array(
        'style' => 'width: 140px; padding-top: 5px; float: left; text-align: right;'
    ));
    echo html_writer::start_div('', array('style' => 'margin-left: 160px;'));
    echo html_writer::select(range(1, 12), 'card-expiry-month', '', false, array(
        'tabindex' => '7', 'class' => 'card-expiry-month stripe-sensitive required',
        'style' => 'width: 60px;'));
    echo html_writer::span(' / ');
    $startyear = date('y');

    echo html_writer::select(range($startyear, $startyear + 10), 'card-expiry-year', '', false, array(
        'tabindex' => '8', 'class' => 'card-expiry-year stripe-sensitive required', 'style' => 'width: 80px;'));
    echo html_writer::end_div();
    echo html_writer::end_div();
    // End.

    echo html_writer::end_tag('fieldset');

    echo html_writer::end_div();

    echo html_writer::start_div('span3 helptext');
    echo html_writer::start_tag('p');
    echo get_string('securely', 'braincert');
    echo html_writer::link('https://stripe.com', get_string('stripe', 'braincert'), array(
        'target' => '_blank', 'class' => 'stripe'
        ));
    echo html_writer::end_tag('p');
    echo html_writer::start_tag('p');
    echo html_writer::img(
        'https://drpyjw32lhcoa.cloudfront.net/9d61ecb/img/lock.png',
        get_string('usessecurely', 'braincert')
    );
    echo html_writer::img(
        'https://drpyjw32lhcoa.cloudfront.net/9d61ecb/img/cards.png',
        get_string('acceptvisa', 'braincert')
    );
    echo html_writer::end_tag('p');
    echo html_writer::end_div();

    echo html_writer::end_div();
}

/**
 *
 * @param object $braincertclass
 * @param array $getclassdetail
 * @param string $class
 */
function dispaly_class_name_info($braincertclass, $getclassdetail, $class) {
    echo html_writer::start_tag('h4');
    echo html_writer::tag('i', '', array('class' => 'fa fa-bullhorn', 'aria-hidden' => true));
    echo html_writer::tag('strong', $braincertclass->name, array('class' => 'class-heading'));
    echo html_writer::start_span($class);
    if ($getclassdetail['isCancel'] == 0) {
        echo $getclassdetail['status'];
    }
    if ($getclassdetail['isCancel'] == 2) {
        echo get_string('canceled', 'braincert');
    }
    if ($getclassdetail['isCancel'] == 1) {
        if (!isset($getclassdetail['class_next_date'])) {
            echo get_string('canceled', 'braincert');
        } else {
            date_default_timezone_set($getclassdetail['timezone_country']);
            $date1 = date_create(date('Y-m-d', time()));
            $date2 = date_create($getclassdetail['canceled_date']);
            $diff = date_diff($date1, $date2);
            if ($diff->d > 0) {
                echo $getclassdetail['status'];
            } else {
                echo get_string('canceled', 'braincert');
            }
        }
    }
    echo html_writer::end_span();
    echo html_writer::end_tag('h4');
}

/**
 *
 * @global global $DB
 * @global global $USER
 * @global type $COURSE
 * @global global $CFG
 * @param boject $braincertrec
 * @param sting $template
 */
function invitation_email_body($braincertrec, $template) {
    global $DB, $USER, $COURSE, $CFG;
    $module = $DB->get_record('modules', array('name' => 'braincert'));
    $cm = $DB->get_record('course_modules', array('instance' => $braincertrec->id,
        'course' => $COURSE->id,
        'module' => $module->id));

    $starttime = new DateTime($braincertrec->start_time);
    $endtime = new DateTime($braincertrec->end_time);
    $interval = $starttime->diff($endtime);
    $durationinmin = ($interval->h * 60) + $interval->i;

    $find = array('{owner_name}', '{class_name}', '{class_date_time}',
        '{class_time_zone}', '{class_duration}', '{class_join_url}');

    $replace = array($USER->firstname . ' ' . $USER->lastname, $braincertrec->name,
        date('d-m-Y', $braincertrec->start_date), $braincertrec->default_timezone,
        $durationinmin, $CFG->wwwroot . '/mod/braincert/view.php?id=' . $cm->id);
    return str_replace($find, $replace, $template);
}
