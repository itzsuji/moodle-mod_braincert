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
 * Invite by E-mail form for upcoming class.
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir.'/formslib.php');

GLOBAL $USER, $CFG, $COURSE;

$bcid = required_param('bcid', PARAM_INT);   // Virtual Class ID.

$PAGE->set_url('/mod/braincert/inviteemail.php', array('bcid' => $bcid));

$braincertrec = $DB->get_record('braincert', array('class_id' => $bcid));
if (!$course = $DB->get_record('course', array('id' => $braincertrec->course))) {
    print_error('invalidcourseid');
}

require_login($course);
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add(get_string('pluginname', 'braincert'));
$inviteemail = get_string('inviteemail', 'braincert');
$PAGE->navbar->add($inviteemail);

$PAGE->requires->css('/mod/braincert/css/styles.css', true);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('inviteemail', 'braincert'));

/**
 * class add invite_by_email_form
 * @copyright Dualcube (https://dualcube.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invite_by_email_form extends moodleform {
    /**
     * Define add discount form
     */
    public function definition() {

        global $DB, $bcid, $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        $mform->addElement('textarea', 'emailto', get_string('emailto', 'braincert'));
        $mform->addHelpButton('emailto', 'emailto', 'braincert');
        $getbody = $DB->get_record('braincert_manage_template', array('bcid' => $bcid));
        if ($getbody) {
            $s = $getbody->emailsubject;
            $m = $getbody->emailmessage;
        } else {
            $s = get_string('liveclassinvitationsubject', 'braincert');
            $m = get_string('liveclassinvitationmessage', 'braincert');
        }
        $mform->addElement('text', 'emailsubject', get_string('emailsubject', 'braincert'));
        $mform->setType('emailsubject', PARAM_RAW);
        $mform->setDefault('emailsubject', $s);
        $mform->addElement('editor', 'emailmessage', get_string('emailmessage', 'braincert'))->setValue(array('text' => $m));
        $mform->setType('emailmessage', PARAM_RAW);
        $this->add_action_buttons($cancel = false, get_string('send', 'braincert'));
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
        $emaillists = explode(",", $data['emailto']);
        foreach ($emaillists as $emaillist) {
            if (!filter_var($emaillist, FILTER_VALIDATE_EMAIL)) {
                $errors['emailto'] = $emaillist." ".get_string('invalidemail', 'braincert');
            }
        }
        return $errors;
    }
}

$mform = new invite_by_email_form($CFG->wwwroot.'/mod/braincert/inviteemail.php?bcid='.$bcid);

if ($invitebyemail = $mform->get_data()) {
    $module = $DB->get_record('modules', array('name' => 'braincert'));
    $cm = $DB->get_record('course_modules', array('instance' => $braincertrec->id,
                          'course' => $COURSE->id,
                          'module' => $module->id));
    $starttime = new DateTime($braincertrec->start_time);
    $endtime = new DateTime($braincertrec->end_time);
    $interval = $starttime->diff($endtime);
    $durationinmin = ($interval->h * 60) + $interval->i;
    $find = array('{owner_name}', '{class_name}', '{class_date_time}', '{class_time_zone}', '{class_duration}', '{class_join_url}');
    $replace = array($USER->firstname.' '.$USER->lastname, $braincertrec->name,
                     date('d-m-Y', $braincertrec->start_date), $braincertrec->default_timezone,
                     $durationinmin, $CFG->wwwroot.'/mod/braincert/view.php?id='.$cm->id);
    $emailmessage = str_replace($find, $replace, $invitebyemail->emailmessage['text']);
    $emaillists = explode(",", $invitebyemail->emailto);
    foreach ($emaillists as $emailid) {
        if ($emailuserrec = $DB->get_record('user', array('email' => $emailid))) {
            $emailuser = $emailuserrec;
        } else {
            $emailuser = new stdClass();
            $emailuser->email             = $emailid;
            $emailuser->firstname         = '';
            $emailuser->lastname          = '';
            $emailuser->maildisplay       = true;
            $emailuser->mailformat        = 1;
            $emailuser->id                = -1;
            $emailuser->firstnamephonetic = '';
            $emailuser->lastnamephonetic  = '';
            $emailuser->middlename        = '';
            $emailuser->alternatename     = '';
        }
        $mailresults = email_to_user($emailuser, $USER, $invitebyemail->emailsubject, $emailmessage, $emailmessage);
        if ($mailresults == 1) {
            echo '<div class="alert alert-success">'.get_string('emailsent', 'braincert').' - '.$emailid.'.</strong></div>';
        } else {
            echo '<div class="alert alert-danger">'.get_string('emailnotsent', 'braincert').' '.$emailid.'.</strong></div>';
        }
    }
    $mform->display();
} else {
    $mform->display();
}

echo $OUTPUT->footer();