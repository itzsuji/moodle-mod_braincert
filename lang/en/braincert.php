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
$string['weeksunday'] = 'Sunday';
$string['weekmonday'] = 'Monday';
$string['weektuesday'] = 'Tuesday';
$string['weekwednesday'] = 'Wednesday';
$string['weekthursday'] = 'Thursday';
$string['weekfriday'] = 'Friday';
$string['weeksaturday'] = 'Saturday';
$string['recurrance1'] = 'Daily (all 7 days)';
$string['recurrance2'] = '6 Days(Mon-Sat)';
$string['recurrance3'] = '5 Days(Mon-Fri)';
$string['recurrance4'] = 'Weekly';
$string['recurrance5'] = 'Once every month';
$string['recurrance6'] = 'On selected days';
$string['am'] = 'AM';
$string['pm'] = 'PM';
$string["cnarabic"] = "Arabic";
$string["cnbosnian"] = "Bosnian";
$string["cnbulgarian"] = "Bulgarian";
$string["cncatalan"] = "Catalan";
$string["cnchinese-simplified"] = "Chinese-simplified";
$string["cnchinese-traditional"] = "Chinese-traditional";
$string["cncroatian"] = "Croatian";
$string["cnczech"] = "Czech";
$string["cndanish"] = "Danish";
$string["cndutch"] = "Dutch";
$string["cnenglish"] = "English";
$string["cnestonian"] = "Estonian";
$string["cnfinnish"] = "Finnish";
$string["cnfrench"] = "French";
$string["cngerman"] = "German";
$string["cngreek"] = "Greek";
$string["cnhaitian-creole"] = "Haitian-creole";
$string["cnhebrew"] = "Hebrew";
$string["cnhindi"] = "Hindi";
$string["cnhmong-daw"] = "Hmong-daw";
$string["cnhungarian"] = "Hungarian";
$string["cnindonesian"] = "Indonesian";
$string["cnitalian"] = "Italian";
$string["cnjapanese"] = "Japanese";
$string["cnkiswahili"] = "Kiswahili";
$string["cnklingon"] = "Klingon";
$string["cnkorean"] = "Korean";
$string["cnlithuanian"] = "Lithuanian";
$string["cnmalayalam"] = "Malayalam";
$string["cnmalay"] = "Malay";
$string["cnmaltese"] = "Maltese";
$string["cnnorwegian-bokma"] = "Norwegian-bokma";
$string["cnpersian"] = "Persian";
$string["cnpolish"] = "Polish";
$string["cnportuguese"] = "Portuguese";
$string["cnromanian"] = "Romanian";
$string["cnrussian"] = "Russian";
$string["cnserbian"] = "Serbian";
$string["cnslovak"] = "Slovak";
$string["cnslovenian"] = "Slovenian";
$string["cnspanish"] = "Spanish";
$string["cnswedish"] = "Swedish";
$string["cntamil"] = "Tamil";
$string["cntelugu"] = "Telugu";
$string["cnthai"] = "Thai";
$string["cnturkish"] = "Turkish";
$string["cnukrainian"] = "Ukrainian";
$string["cnurdu"] = "Urdu";
$string["cnvietnamese"] = "Vietnamese";
$string["cnwelsh"] = "Welsh";
$string["region1"] = "US East (Dallas, TX)";
$string["region2"] = "US West (Los Angeles, CA)";
$string["region3"] = "US East (New York)";
$string["region4"] = "Europe (Frankfurt, Germany)";
$string["region5"] = "Europe (London)";
$string["region6"] = "Asia Pacific (Bangalore, India)";
$string["region7"] = "Asia Pacific (Singapore)";
$string["region8"] = "US East (Miami, FL)";
$string["region9"] = "Europe (Milan, Italy)";
$string["region10"] = "Asia Pacific (Tokyo, Japan)";
$string["region11"] = "Middle East (Dubai, UAE)";
$string["region12"] = "Australia (Sydney)";
$string["region13"] = "Europe (Paris, France)";
$string["region14"] = "Asia Pacific (Beijing, China)";
$string["timezone28"] = "(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London";
$string["timezone30"] = "(GMT) Monrovia, Reykjavik";
$string["timezone72"] = "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna";
$string["timezone53"] = "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris";
$string["timezone14"] = "(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb";
$string["timezone71"] = "(GMT+01:00) West Central Africa";
$string["timezone83"] = "(GMT+02:00) Amman";
$string["timezone84"] = "(GMT+02:00) Beirut";
$string["timezone24"] = "(GMT+02:00) Cairo";
$string["timezone61"] = "(GMT+02:00) Harare, Pretoria";
$string["timezone27"] = "(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius";
$string["timezone35"] = "(GMT+02:00) Jerusalem";
$string["timezone21"] = "(GMT+02:00) Minsk";
$string["timezone86"] = "(GMT+02:00) Windhoek";
$string["timezone31"] = "(GMT+03:00) Athens, Istanbul, Minsk";
$string["timezone2"] = "(GMT+03:00) Baghdad";
$string["timezone49"] = "(GMT+03:00) Kuwait, Riyadh";
$string["timezone54"] = "(GMT+03:00) Moscow, St. Petersburg, Volgograd";
$string["timezone19"] = "(GMT+03:00) Nairobi";
$string["timezone87"] = "(GMT+03:00) Tbilisi";
$string["timezone34"] = "(GMT+03:30) Tehran";
$string["timezone1"] = "(GMT+04:00) Abu Dhabi, Muscat";
$string["timezone88"] = "(GMT+04:00) Baku";
$string["timezone9"] = "(GMT+04:00) Baku, Tbilisi, Yerevan";
$string["timezone89"] = "(GMT+04:00) Port Louis";
$string["timezone47"] = "(GMT+04:30) Kabul";
$string["timezone25"] = "(GMT+05:00) Ekaterinburg";
$string["timezone90"] = "(GMT+05:00) Islamabad, Karachi";
$string["timezone73"] = "(GMT+05:00) Islamabad, Karachi, Tashkent";
$string["timezone33"] = "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi";
$string["timezone62"] = "(GMT+05:30) Sri Jayawardenepura";
$string["timezone91"] = "(GMT+05:45) Kathmandu";
$string["timezone42"] = "(GMT+06:00) Almaty, Novosibirsk";
$string["timezone12"] = "(GMT+06:00) Astana, Dhaka";
$string["timezone41"] = "(GMT+06:30) Rangoon";
$string["timezone59"] = "(GMT+07:00) Bangkok, Hanoi, Jakarta";
$string["timezone50"] = "(GMT+07:00) Krasnoyarsk";
$string["timezone17"] = "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi";
$string["timezone46"] = "(GMT+08:00) Irkutsk, Ulaan Bataar";
$string["timezone60"] = "(GMT+08:00) Kuala Lumpur, Singapore";
$string["timezone70"] = "(GMT+08:00) Perth";
$string["timezone63"] = "(GMT+08:00) Taipei";
$string["timezone65"] = "(GMT+09:00) Osaka, Sapporo, Tokyo";
$string["timezone77"] = "(GMT+09:00) Seoul";
$string["timezone75"] = "(GMT+09:00) Yakutsk";
$string["timezone10"] = "(GMT+09:30) Adelaide";
$string["timezone4"] = "(GMT+09:30) Darwin";
$string["timezone20"] = "(GMT+10:00) Brisbane";
$string["timezone5"] = "(GMT+10:00) Canberra, Melbourne, Sydney";
$string["timezone74"] = "(GMT+10:00) Guam, Port Moresby";
$string["timezone64"] = "(GMT+10:00) Hobart";
$string["timezone69"] = "(GMT+10:00) Vladivostok";
$string["timezone15"] = "(GMT+11:00) Magadan, Solomon Is., New Caledonia";
$string["timezone44"] = "(GMT+12:00) Auckland, Wellington";
$string["timezone26"] = "(GMT+12:00) Fiji, Kamchatka, Marshall Is.";
$string["timezone6"] = "(GMT-01:00) Azores";
$string["timezone8"] = "(GMT-01:00) Cape Verde Is.";
$string["timezone39"] = "(GMT-02:00) Mid-Atlantic";
$string["timezone22"] = "(GMT-03:00) Brasilia";
$string["timezone94"] = "(GMT-03:00) Buenos Aires";
$string["timezone55"] = "(GMT-03:00) Buenos Aires, Georgetown";
$string["timezone29"] = "(GMT-03:00) Greenland";
$string["timezone95"] = "(GMT-03:00) Montevideo";
$string["timezone45"] = "(GMT-03:30) Newfoundland";
$string["timezone3"] = "(GMT-04:00) Atlantic Time (Canada)";
$string["timezone57"] = "(GMT-04:00) Georgetown, La Paz, San Juan";
$string["timezone96"] = "(GMT-04:00) Manaus";
$string["timezone51"] = "(GMT-04:00) Santiago";
$string["timezone76"] = "(GMT-04:30) Caracas";
$string["timezone56"] = "(GMT-05:00) Bogota, Lima, Quito";
$string["timezone23"] = "(GMT-05:00) Eastern Time (US & Canada)";
$string["timezone67"] = "(GMT-05:00) Indiana (East)";
$string["timezone11"] = "(GMT-06:00) Central America";
$string["timezone16"] = "(GMT-06:00) Central Time (US & Canada)";
$string["timezone37"] = "(GMT-06:00) Guadalajara, Mexico City, Monterrey";
$string["timezone7"] = "(GMT-06:00) Saskatchewan";
$string["timezone68"] = "(GMT-07:00) Arizona";
$string["timezone38"] = "(GMT-07:00) Chihuahua, La Paz, Mazatlan";
$string["timezone40"] = "(GMT-07:00) Mountain Time (US & Canada)";
$string["timezone52"] = "(GMT-08:00) Pacific Time (US & Canada)";
$string["timezone104"] = "(GMT-08:00) Tijuana, Baja California";
$string["timezone48"] = "(GMT-09:00) Alaska";
$string["timezone32"] = "(GMT-10:00) Hawaii";
$string["timezone58"] = "(GMT-11:00) Midway Island, Samoa";
$string["timezone18"] = "(GMT-12:00) International Date Line West";
$string["timezone105"] = "(GMT-4:00) Eastern Daylight Time (US & Canada)";
$string["timezone13"] = "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague";
$string["currencyaud"] = "AUD"; // Kept uppercase to symbolize.
$string["currencycad"] = "CAD";
$string["currencyeur"] = "EUR";
$string["currencygbp"] = "GBP";
$string["currencynzd"] = "NZD";
$string["currencyusd"] = "USD";
$string["permissiondeined"] = "Permission denied.";
