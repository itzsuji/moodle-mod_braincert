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
 * Add the discount for paid class.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir.'/formslib.php');
require_once('locallib.php');

$bcid    = required_param('bcid', PARAM_INT);   // Virtual Class ID.
$did     = optional_param('did', 0, PARAM_INT); // Discount ID.
$action  = optional_param('action', 'edit', PARAM_TEXT);

$PAGE->set_url('/mod/braincert/adddiscount.php', array('bcid' => $bcid));

$braincertrec = $DB->get_record('braincert', array('class_id' => $bcid));
if (!$course = $DB->get_record('course', array('id' => $braincertrec->course))) {
    print_error('invalidcourseid');
}

require_login($course);
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add(get_string('pluginname', 'braincert'));
$adddiscount = get_string('adddiscount', 'braincert');
$PAGE->navbar->add($adddiscount);

if ($action == 'delete') {
    $data['task']      = 'removediscount';
    $data['discountid'] = $did;
    $removediscount = braincert_get_curl_info($data);
    if ($removediscount['status'] == "ok") {
        echo "Removed Successfully.";
        redirect(new moodle_url('/mod/braincert/adddiscount.php?bcid='.$bcid));
    } else {
        echo $removediscount['error'];
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('addclassdiscount', 'braincert'));

$dislistdata['task']     = 'listdiscount';
$dislistdata['class_id'] = $bcid;
$discountlists = braincert_get_curl_info($dislistdata);


/**
 * class add discount form
 * @copyright Dualcube (https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class adddiscount_form extends moodleform {
    /**
     * Define add discount form
     */
    public function definition() {
        global $CFG, $DB, $braincertrec, $bcid, $did, $action, $discountlists;
        $isusecode        = 0;
        $defaultlimit     = '';
        $defaultcode      = '';
        $defaulttype      = 0;
        $defaultdiscount  = '';
        $defaultstartdate = time();
        $defaultenddate   = time();
        $isexpire         = 0;

        if ($action == 'edit') {
            if (!empty($discountlists)) {
                if (!isset($discountlists['Discount'])) {
                    foreach ($discountlists as $discountlist) {
                        if (isset($discountlist['id'])) {
                            if ($discountlist['id'] == $did) {
                                $isusecode = $discountlist['is_use_discount_code'];
                                if ($discountlist['is_use_discount_code'] == 1) {
                                    $defaultlimit  = $discountlist['discount_limit'];
                                    $defaultcode   = $discountlist['discount_code'];
                                }
                                if ($discountlist['discount_type'] == 'percentage') {
                                    $defaulttype   = 1;
                                } else {
                                    $defaulttype   = 0;
                                }
                                $defaultdiscount   = $discountlist['special_price'];
                                $defaultstartdate  = strtotime($discountlist['start_date']);
                                $isexpire      = $discountlist['is_never_expire'];
                                if ($discountlist['is_never_expire'] == 0) {
                                    $defaultenddate = strtotime($discountlist['end_date']);
                                }
                            }
                        }
                    }
                } else if (isset($discountlists['status']) && ($discountlists['status'] == 'error')) {
                    echo $discountlists['error'];
                }
            }
        }

        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('hidden', 'did', $did);
        $mform->setType('did', PARAM_INT);

        $mform->addElement('advcheckbox', 'is_use_discount_code', get_string('usediscountcode', 'braincert'), '',
                array('group' => 1), array(0, 1));
        $mform->setDefault('is_use_discount_code', $isusecode);

        $mform->addElement('text', 'discount_limit', get_string('discountlimit', 'braincert'));
        $mform->setType('discount_limit', PARAM_INT);
        $mform->addRule('discount_limit', '', 'numeric', null, 'client');
        $mform->addHelpButton('discount_limit', 'discountlimit', 'braincert');
        $mform->disabledIf('discount_limit', 'is_use_discount_code', 'notchecked');
        $mform->setDefault('discount_limit', $defaultlimit);

        $mform->addElement('text', 'discount_code', get_string('discountcode', 'braincert'));
        $mform->setType('discount_code', PARAM_RAW);
        $mform->addHelpButton('discount_code', 'discountcode', 'braincert');
        $mform->disabledIf('discount_code', 'is_use_discount_code', 'notchecked');
        $mform->setDefault('discount_code', $defaultcode);

        if (!empty($braincertrec->currency)) {
            $currency = strtoupper($braincertrec->currency);
        } else {
            $currency = 'USD';
        }
        $discounttypeoptions = array(
         '0' => '$ '.$currency,
         '1' => '% percentage');
        $mform->addElement('select', 'discount_type', get_string('discounttype', 'braincert'), $discounttypeoptions);
        $mform->addRule('discount_type', null, 'required', null, 'client');
        $mform->addHelpButton('discount_type', 'discounttype', 'braincert');
        $mform->setDefault('discount_type', $defaulttype);

        $mform->addElement('text', 'discount', get_string('amountofdiscount', 'braincert'));
        $mform->setType('discount', PARAM_INT);
        $mform->addRule('discount', '', 'numeric', null, 'client');
        $mform->addRule('discount', null, 'required', null, 'client');
        $mform->addHelpButton('discount', 'amountofdiscount', 'braincert');
        $mform->setDefault('discount', $defaultdiscount);

        $dtoption = array(
            'startyear' => 1970,
            'stopyear'  => 2020,
            'timezone'  => $braincertrec->braincert_timezone
        );
        $mform->addElement('date_selector', 'start_date', get_string('dis_startdate', 'braincert'), $dtoption);
        $mform->addHelpButton('start_date', 'dis_startdate', 'braincert');
        $mform->addRule('start_date', null, 'required', null, 'client');
        $mform->setDefault('start_date', $defaultstartdate);

        $mform->addElement('advcheckbox', 'is_never_expire', get_string('neverexpire', 'braincert'),
                '', array('group' => 1), array(0, 1));
        $mform->setDefault('is_never_expire', $isexpire);

        $mform->addElement('date_selector', 'end_date', get_string('end_date', 'braincert'), $dtoption);
        $mform->disabledIf('end_date', 'is_never_expire', 'checked');
        $mform->setDefault('end_date', $defaultenddate);

        $this->add_action_buttons();

    }
    /**
     * validation check
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $currentdate = strtotime(date("Y-m-d"));
        if (isset($data['did']) && ($data['is_never_expire'] == 0)) {
            if (($data['end_date'] < $currentdate) || ($data['end_date'] <= $data['start_date'])) {
                $errors['end_date'] = get_string('wrongenddate', 'braincert');
            }
        } else {
            if ($data['start_date'] < $currentdate) {
                $errors['start_date'] = get_string('wrongstartdate', 'braincert');
                if ($data['end_date'] <= $data['start_date']) {
                    $errors['end_date'] = get_string('wrongenddate', 'braincert');
                }
            }
        }
        return $errors;
    }
}

$mform = new adddiscount_form($CFG->wwwroot.'/mod/braincert/adddiscount.php?bcid='.$bcid);

if ($classdiscount = $mform->get_data()) {

    $discountstartdate = date('Y-m-d', $classdiscount->start_date);
    $classdiscountstartdate = new DateTime($discountstartdate, new DateTimeZone($braincertrec->default_timezone));
    $discountstartdatetimestamp = $classdiscountstartdate->getTimestamp();

    $discountenddate = date('Y-m-d', $classdiscount->end_date);
    $classdiscountenddate = new DateTime($discountenddate, new DateTimeZone($braincertrec->default_timezone));
    $discountenddatetimestamp = $classdiscountenddate->getTimestamp();

    $data['task']           = 'addSpecials';
    $data['class_id']  = $bcid;
    $data['discount']       = $classdiscount->discount;
    $data['start_date']     = date('Y-m-d', $discountstartdatetimestamp);
    $data['discount_type']  = $classdiscount->discount_type;
    if ($classdiscount->is_never_expire == 0) {
        $data['end_date']   = date('Y-m-d', $discountenddatetimestamp);
    }
    if ($classdiscount->is_use_discount_code == 1) {
        $data['discount_code']  = $classdiscount->discount_code;
        $data['discount_limit'] = $classdiscount->discount_limit;
    }
    if ($classdiscount->did > 0) {
        $data['discountid']     = $classdiscount->did;
    }

    $getdiscount = braincert_get_curl_info($data);
    if ($getdiscount['status'] == "ok") {
        if ($getdiscount['method'] == "updateDiscount") {
            echo "Discount Updated Successfully.";
        } else if ($getdiscount['method'] == "addDiscount") {
            echo "Discount Added Successfully.";
        }
    } else {
        echo $getdiscount['error'];
    }
    $mform->display();
} else {
    $mform->display();
}

$dislistdata['task']     = 'listdiscount';
$dislistdata['class_id'] = $bcid;
$discountlists = braincert_get_curl_info($dislistdata);

$table = new html_table();
$table->head = array ();
$table->head[] = get_string('discountid', 'braincert');
$table->head[] = get_string('amountofdiscount', 'braincert');
$table->head[] = get_string('discountcode', 'braincert');
$table->head[] = get_string('discounttype', 'braincert');
$table->head[] = get_string('start_date', 'braincert');
$table->head[] = get_string('end_date', 'braincert');
$table->head[] = get_string('actions', 'braincert');

if (!empty($discountlists)) {
    if (isset($discountlists['Discount'])) {
        echo $discountlists['Discount'];
    } else if (isset($discountlists['status']) && ($discountlists['status'] == 'error')) {
        echo $discountlists['error'];
    } else {
        foreach ($discountlists as $discountlist) {
            $row = array ();
            $row[] = $discountlist['id'];
            $row[] = $discountlist['special_price'];
            $row[] = $discountlist['discount_code'];
            if ($discountlist['discount_type'] == "fixed_amount") {
                $row[] = 'Fixed Amount';
            } else {
                $row[] = 'Percentage';
            }
            $row[] = date("F d, Y", strtotime($discountlist['start_date']));
            if (strtotime($discountlist['end_date']) > 0) {
                $row[] = date("F d, Y", strtotime($discountlist['end_date']));
            } else {
                $row[] = 'Unlimited';
            }
            $row[] = '<a href="'.$CFG->wwwroot.'/mod/braincert/adddiscount.php?action=edit&bcid='
                     .$bcid.'&did='.$discountlist['id'].'" value="edit-'.$discountlist['id'].'">Edit</a>'
                     .' '.'<a href="'.$CFG->wwwroot.'/mod/braincert/adddiscount.php?action=delete&bcid='
                     .$bcid.'&did='.$discountlist['id'].'" value="delete-'.$discountlist['id'].'">Delete</a>';
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