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
 * Strings for component 'braincert', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    mod_braincert
 * @author BrainCert <support@braincert.com>
 * @copyright  BrainCert (https://www.braincert.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['configdndmedia'] = 'Offer to create a BrainCert VC when media files are dragged & dropped onto a course';
$string['description'] = 'Description';
$string['dndmedia'] = 'Media drag and drop';
$string['dndresizeheight'] = 'Resize drag and drop height';
$string['dndresizewidth'] = 'Resize drag and drop width';
$string['dnduploadbraincert'] = 'Add media to course page';
$string['dnduploadbraincerttext'] = 'Add a BrainCert VC to the course page';
$string['indicator:cognitivedepth'] = 'BrainCert cognitive';
$string['indicator:cognitivedepth_help'] = 'This indicator is based on the cognitive depth reached    by the student in a BrainCert resource.';
$string['indicator:socialbreadth'] = 'BrainCert social';
$string['indicator:socialbreadth_help'] = 'This indicator is based on the social breadth
    reached by the student in a BrainCert resource.';
$string['braincert:addinstance'] = 'Add a new BrainCert';
$string['braincert:braincert_view'] = 'View BrainCert';
$string['braincerttext'] = 'BrainCert text';
$string['modulename_help'] = 'BrainCert HTML5 virtual classroom is designed to deliver    live classes, meetings, webinars, and collaborative group conferences    to audience anywhere right from your Moodle site!<br><br>HTML5 Virtual Classroom features:<br>
    <ul><li> WebRTC based Ultra HD audio and video conferencing with great resiliency and multiple    full HD participants.</li><li> Works across mobile, tablet and desktop devices without installing    additional software or browser plugins. </li><li> Support for WebRTC in macOS and iOS devices using    Safari 11 browser. Android support using Opera, Chrome and Samsung internet browsers.    Desktop support using Chrome and Firefox browsers.</li><li> Available in 50 languages. Use API calls    to force an interface language or allow attendees to select a language.</li><li> Cloud-based session    recording without the need to install any other software or browser plugins. Download recorded lessons
     as 720p HD file, share and play online for attendees.</li><li> Server-side secure recording in    the cloud. Record classes manually or automatically and download multiple recordings in a session or    combine all in to one file - all using a simple API call.</li><li> Group HTML5-based HD Screen    Sharing in tabbed interface. Enhance your computer-based training classes by sharing entire screen    or just a single application. No software downloads or installations necessary.</li>
    <li> Multiple interactive whiteboards. The staple of all classroom instruction is the whiteboard that    supports drawing tool, LaTEX math equations, draw shapes & symbols, line tools, save snapshots,    and share documents in multiple tabs.</li><li> Share documents & presentations. Stream Audio/Video
     files securely.</li><li> Wolfram|Alpha gives you access to the world facts and data and calculates    answers across a range of topics, including science, engineering, mathematics.</li>
    <li> Equations editor, group chat, and powerful annotation feature to draw over uploaded    documents & presentations. </li><li> Responsive whiteboard fits any screen and browser resolution    for seamless same viewing experience by all attendees.</li></ul>';
$string['modulename_link'] = 'mod/braincert/view';
$string['modulenameplural'] = 'BrainCert';
$string['pluginadministration'] = 'Virtual classroom administration';
$string['pluginname'] = 'BrainCert virtual classroom';
$string['modulename'] = 'BrainCert virtual classroom';
$string['search:activity'] = 'BrainCert virtual classroom';
$string['generalconfig'] = 'General configuration';
$string['explaingeneralconfig'] = 'API credentials:- required for authentication';
$string['apikey'] = 'BrainCert API key';
$string['baseurl'] = 'BrainCert base URL';
$string['configapikey'] = 'If this is your first time here, we recommend you    to <a href="https://www.braincert.com/app/virtualclassroom?_wpnonce=5efc93dcfe">
    signup for your API</a> key first.';
$string['configbaseurl'] = 'Default base URL: https://api.braincert.com/v2';
$string['braincert_class'] = 'BrainCert class';
$string['title'] = 'Title';
$string['title_help'] = 'Your class title';
$string['braincertdatetimesetting'] = 'Set timing of the class.';
$string['bc_timezone'] = 'Timezone';
$string['bc_timezone_help'] = 'Select the time-zone for which you want to schedule the class.';
$string['timezone_required'] = 'Timezone required';
$string['start_date'] = 'Start date';
$string['start_date_help'] = 'Select the start date for class. You cannot schedule class for past time.';
$string['bc_starttime'] = 'Start time';
$string['bc_starttime_help'] = 'Select the start time for class. You cannot schedule class for past time.';
$string['bc_endtime'] = 'End time';
$string['bc_endtime_help'] = 'Minimum duration is 30 minutes and maximum is 180 minutes.';
$string['max_number'] = 'Must be a valid number';
$string['braincertclasssettings'] = 'BrainCert class settings';
$string['setregion'] = 'Set location';
$string['setregion_help'] = 'Set your location for your live class.';
$string['region_required'] = 'Location required';
$string['recurring_class'] = 'Recurring class';
$string['recurring_class_help'] = 'Want to schedule a recurring class?';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['repeat_class'] = 'When class repeats';
$string['weekday'] = 'Select days to repeat';
$string['change_language'] = 'Allow attendees to change interface language';
$string['change_language_help'] = 'Allow attendees to change virtual classroom interface to '
        . 'the language of their choice. The default language is english.';
$string['set_language'] = 'Force interface language';
$string['set_language_help'] = 'Select the interface language';
$string['record_class'] = 'Record this class';
$string['record_manually'] = 'Start recording manually';
$string['record_automatically'] = 'Start recording automatically';
$string['record_disable_rec_btn'] = 'Start recording automatically and disable recording button';
$string['record_class_help'] = 'No - disable recording.<br>Enable following recording options in    your live sessions and meetings.<ul><li>Instructor has to manually start/stop recording button.</li>
    <li>Start recording automatically when class starts.</li>
    <li>Start recording automatically when class starts and disable instructor from managing the recording button.</li>
    Recording will be produced at the end of class time.</li></ul>';
$string['classroom_type'] = 'Classroom type';
$string['classroom_type_help'] = 'Allow loading the entire app with audio/video, attendee list,    chat, or only selected features.';
$string['classroom_type_zero'] = 'whiteboard + audio/video + attendee list + chat';
$string['classroom_type_one'] = 'whiteboard + attendee list';
$string['classroom_type_two'] = 'whiteboard + attendee list + chat';
$string['is_corporate'] = 'Enable webcam and microphone upon entry';
$string['is_corporate_help'] = 'If "NO", the classroom is moderated and the instructor    has to pass microphone and webcam controls to attendees. If "Yes",
    attendees can enable microphone and webcam without permission from the instructor.';
$string['screen_sharing'] = 'Enable screen sharing';
$string['screen_sharing_help'] = 'Enable or disable screen sharing.';
$string['private_chat'] = 'Enable private chat';
$string['private_chat_help'] = 'Yes - All attendees should be able to    private chat with each other.<br>No - Only    instructor can private chat with students and students cannot private chat with each other.';
$string['class_type'] = 'Class type';
$string['class_type_help'] = 'Set the price of your live class.    You can set the price, create discount under class-management options.';
$string['free'] = 'Free';
$string['paid'] = 'Paid';
$string['currency'] = 'Currency';
$string['max_attendees'] = 'Max. attendees';
$string['max_attendees_help'] = 'Max. attendees';
$string['wrongtime'] = 'Cannot schedule class for past time';
$string['wrongduration'] = 'Duration should be between 30 minutes to 300 minutes';
$string['end_classes'] = 'End after';
$string['settings'] = 'BrainCert settings';
$string['addpricingscheme'] = 'Add pricing scheme';
$string['addprice'] = 'Add price';
$string['price'] = 'Price';
$string['schemedays'] = 'Days (to give access for)';
$string['accesstype'] = 'Access type';
$string['unlimited'] = 'Unlimited';
$string['limited'] = 'Limited';
$string['numbertimes'] = 'Number of times';
$string['adddiscount'] = 'Add discount';
$string['addclassdiscount'] = 'Add class discount';
$string['discountlimit'] = 'Discount limit';
$string['discountlimit_help'] = 'How many times can this discount be used';
$string['discountcode'] = 'Discount code';
$string['discountcode_help'] = 'Discount coupon code';
$string['discounttype'] = 'Discount type';
$string['discounttype_help'] = 'Discount type in class ( fixed_amount and percentage )';
$string['amountofdiscount'] = 'Discount';
$string['amountofdiscount_help'] = 'Take off for all orders';
$string['dis_startdate'] = 'Start date';
$string['dis_startdate_help'] = 'To give access for days of class';
$string['end_date'] = 'end date';
$string['neverexpire'] = 'Never expire';
$string['usediscountcode'] = 'Use discount code';
$string['wrongenddate'] = 'End date must be greater than start date and current date.';
$string['wrongstartdate'] = 'Please enter start_date after current date';
$string['attendancereport'] = 'Attendance report';
$string['classattendees'] = 'Class attendance';
$string['inviteemail'] = 'Invite by e-mail';
$string['viewclassrecording'] = 'View class recording';
$string['viewrecording'] = 'View recordings';
$string['inviteusergroup'] = 'Invite user group';
$string['inviteuserofselectedgroup'] = 'Invite users of selected groups';
$string['emailto'] = 'To';
$string['emailto_help'] = 'Enter email address using (,) separator';
$string['emailsubject'] = 'Subject';
$string['emailmessage'] = 'Message';
$string['send'] = 'Send';
$string['invalidemail'] = 'is invalid email format';
$string['managetemplate'] = 'Manage email template';
$string['recordinglist'] = 'Recording list';
$string['wrongclasscount'] = 'Please enter a number greater than two';
$string['payments'] = 'Payments';
$string['duration'] = 'Duration';
$string['cardholdername'] = 'Cardholder name';
$string['cardnumber'] = 'Card Number';
$string['ccv'] = 'CCV';
$string['securely'] = 'Your payment information is encrypted and securely processed by';
$string['stripe'] = 'Stripe';
$string['subtotal'] = 'Subtotal';
$string['buyclass'] = 'Buy class';
$string['processing'] = 'Processing... Don\'t close.';
$string['buy'] = 'Buy';
$string['launch'] = 'Launch';
$string['viewallclass'] = 'View all class';
$string['recordingslist'] = 'Recordings list';
$string['recording'] = 'Recording';
$string['nopayment'] = 'No payment record found!';
$string['liveclassinvitationsubject'] = 'Live class invitation';
$string['liveclassinvitationmessage'] = '<p>{owner_name} has invited you to join the live class at    BrainCert.</p><p>Class Name: {class_name}</p>
    <p>Date/Time: {class_date_time}</p>
    <p>Time zone: {class_time_zone}</p>
    <p>Duration: {class_duration}</p>
    <p>Click on the link below to join the class:</p>
    <p>{class_join_url}</p><p>Thank you.</p><br><p></p>';
$string['emailsent'] = 'Email has been sent successfully';
$string['emailnotsent'] = 'Sorry email could not be sent to';
$string['nogroups'] = '<strong>Sorry!</strong> No groups available.';
$string['cancelclass'] = 'Cancel class';
$string['discounts'] = 'Discounts';
$string['shoppingcart'] = 'Shopping cart';
$string['managerecording'] = 'Manage recording';
$string['norecordfound'] = 'No record found!';
$string['id'] = 'ID';
$string['name'] = 'Name';
$string['edit'] = 'Edit';
$string['delete'] = 'Delete';
$string['attendence'] = 'Attendence';
$string['timein'] = 'Time in';
$string['timeout'] = 'Time out';
$string['discountid'] = 'Discount ID';
$string['actions'] = 'Actions';
$string['cancelclassall'] = 'Cancel all recurring class';
$string['areyousure'] = 'Are you sure you want to cancel this class?';
$string['areyousureall'] = 'Are you sure you want to cancel all recurring class?';
$string['canceled'] = 'Canceled';
$string['singlevideofile'] = 'Single video file';
$string['multiplevideofile'] = 'Multiple video files';
$string['isvideo'] = 'Video delivery';
$string['videodelivery_group'] = 'Video delivery';
$string['videodelivery_group_help'] = 'Video delivery type';
$string['buyingoption'] = 'Buying Option';
$string['braincert_class_removed'] = 'Class removed successfully.';
$string['expiration_date'] = 'Expiration date';
$string['usessecurely'] = 'Uses secure SSL technology';
$string['acceptvisa'] = 'We accept Visa, Mastercard, Discover, and American Express';
$string['minutes'] = 'Minutes';
$string['discountupdated'] = 'Discount updated successfully.';
$string['discountadded'] = 'Discount added successfully.';
$string['limited'] = 'Limited';
$string['unlimited'] = 'Unlimited';
$string['removedsuccess'] = 'Removed successfully';
$string['fixedamount'] = 'Fixed amount';
$string['percentage'] = 'Percentage';
$string['removedsuccessfully'] = 'Removed successfully.';
$string['schemaupdated'] = 'Scheme updated successfully.';
$string['schemaadded'] = 'Scheme added successfully.';
$string['sendemail'] = 'Send email';
$string['paymentid'] = 'Payment ID';
$string['classid'] = 'Class ID';
$string['amount'] = 'Amount';
$string['payername'] = 'Payer name';
$string['paymentmode'] = 'Payment mode';
$string['paymentdate'] = 'Payment date';
$string['recordid'] = 'Record ID';
$string['datecreated'] = 'Date created';
$string['filename'] = 'File name';
$string['colno'] = '#';
$string['datetime'] = 'Date/Time';
$string['action'] = 'Action';
$string['privacy:metadata'] = 'The BrainCert does not store any user related data.';
$string['cannotupdated'] = 'Schedule class can not be updated.';
$string['recording_layout'] = 'Recorded videos layout';
$string['recording_layout_help'] = 'Set your recorded videos layout for class';
$string['standard_view'] = 'Standard view (Whiteboard, Videos and Chat view with no icons)';
$string['enhanced_view'] = 'Enhanced view (Entire browser tab with all the icons)';
$string['invalidclassid'] = 'Invalid class id.';
$string['prepareclass'] = 'Enter to prepare class';
$string['unknownerror'] = 'System encountered an internal error';