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
 * Add the pricing scheme for paid class.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir.'/formslib.php');
require_once('locallib.php');
require_once($CFG->dirroot.'/mod/braincert/classes/addpricingscheme_form.php');


$bcid = required_param('bcid', PARAM_INT);   // Virtual class.
$pid = optional_param('pid', 0, PARAM_INT); // Price ID.
$action = optional_param('action', 'edit', PARAM_TEXT);

$PAGE->set_url('/mod/braincert/addpricingscheme.php', array('bcid' => $bcid));

$braincertrec = $DB->get_record('braincert', array('class_id' => $bcid));
if (!$course = $DB->get_record('course', array('id' => $braincertrec->course))) {
    print_error('invalidcourseid');
}

require_login($course);
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add(get_string('pluginname', 'braincert'));
$addprice = get_string('addprice', 'braincert');
$PAGE->navbar->add($addprice);

$PAGE->requires->css('/mod/braincert/css/styles.css', true);

if ($action == 'delete') {
    require_sesskey();
    $data['task']      = BRAINCERT_TASK_REMOVE_PRICE;
    $data['id']        = $pid;
    $removepricescheme = braincert_get_curl_info($data);
    if ($removepricescheme['status'] == BRAINCERT_STATUS_OK) {
        echo get_string('removedsuccessfully', 'braincert');
        redirect(new moodle_url('/mod/braincert/addpricingscheme.php?bcid='.$bcid));
    } else {
        echo $removepricescheme['error'];
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('addpricingscheme', 'braincert'));

$pricelistdata['task']     = BRAINCERT_TASK_LIST_SCHEMES;
$pricelistdata['class_id'] = $bcid;
$pricelists = braincert_get_curl_info($pricelistdata);

$mform = new addpricingscheme_form($CFG->wwwroot.'/mod/braincert/addpricingscheme.php?bcid='.$bcid);

if ($pricingscheme = $mform->get_data()) {
    $data['task']        = BRAINCERT_TASK_ADD_SCHEMES;
    $data['price']       = $pricingscheme->price;
    $data['scheme_days'] = $pricingscheme->schemedays;
    $data['times']       = $pricingscheme->accesstype;
    $data['class_id']    = $bcid;
    if (isset($pricingscheme->numbertimes) && ($pricingscheme->accesstype == 1)) {
        $data['numbertimes'] = $pricingscheme->numbertimes;
    }
    if ($pricingscheme->pid > 0) {
        $data['id']     = $pricingscheme->pid;
    }
    $getscheme = braincert_get_curl_info($data);
    if ($getscheme['status'] == BRAINCERT_STATUS_OK) {
        if ($getscheme['method'] == BRAINCERT_METHOD_PRICE_UPDATE) {
            echo get_string('schemaupdated', 'braincert');
        } elseif ($getscheme['method'] == BRAINCERT_METHOD_PRICE_ADD) {
            echo get_string('schemaadded', 'braincert');
        }
    } else {
        echo $getscheme['error'];
    }
    $mform->display();
} else {
    $mform->display();
}

$pricelistdata['task']     = BRAINCERT_TASK_LIST_SCHEMES;
$pricelistdata['class_id'] = $bcid;
$pricelists = braincert_get_curl_info($pricelistdata);

$table = new html_table();
$table->head = array ();
$table->head[] = 'Price ID';
$table->head[] = 'Price';
$table->head[] = 'Scheme days';
$table->head[] = 'Access type';
$table->head[] = 'Numbertimes';
$table->head[] = 'Actions';

if (!empty($pricelists)) {
    if (isset($pricelists['Price'])) {
        echo $pricelists['Price'];
    } elseif (isset($pricelists['status']) && ($pricelists['status'] == BRAINCERT_STATUS_ERROR)) {
        echo $pricelists['error'];
    } else {
        $sesskey = sesskey();
        foreach ($pricelists as $pricelist) {
            $row = array ();
            $row[] = $pricelist['id'];
            $row[] = $pricelist['scheme_price'];
            $row[] = $pricelist['scheme_days'];
            if ($pricelist['times'] == 0) {
                $row[] = get_string('unlimited', 'braincert');
            } else {
                $row[] = get_string('limited', 'braincert');
            }
            $row[] = $pricelist['numbertimes'];
            $row[] = '<a href="'.$CFG->wwwroot.'/mod/braincert/addpricingscheme.php?action=edit&bcid='.$bcid.'&pid='
                     . $pricelist['id'] . '" value="edit-' . $pricelist['id'] . '&sesskey=' . $sesskey . '">'
                . get_string('edit', 'braincert') . '</a>'
                .' '.'<a href="'.$CFG->wwwroot.'/mod/braincert/addpricingscheme.php?action=delete&bcid='.$bcid
                     . '&pid=' . $pricelist['id'] . '" value="delete-' . $pricelist['id'] .
                '&sesskey=' . $sesskey . '">' . get_string('delete', 'braincert') . '</a>';
            $table->data[] = $row;
        }
    }
}

if (!empty($table)) {
    echo html_writer::start_tag('div', array('class' => 'no-overflow display-table'));
    echo html_writer::table($table);
    echo html_writer::end_tag('div');
}
echo $OUTPUT->footer();
