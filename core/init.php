<?php

error_reporting(0);
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'procurement_db'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

define('MINUTE_IN_SECONDS', 60);
define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);

spl_autoload_register(function($class) {
    if (file_exists('classes/' . $class . '.php')) {
        require_once 'classes/' . $class . '.php';
        return TRUE;
    }
    return FALSE;
});
require_once 'core/functions.php';
//Declarations
$SITE_NAME = getConfigValue("site_name");
$COMPANY_NAME = getConfigValue("company_name");
$SITE_LOGO = getConfigValue("company_logo");
$SITE_FAVICON = getConfigValue("company_favicon");
$logo = ($SITE_LOGO) ? $SITE_LOGO : $logo;
$logo_small = ($SITE_FAVICON) ? $SITE_FAVICON : $logo_small;
$SITE_DESCRIPTION = getConfigValue("company_description");
$registered_mail = getConfigValue("email_from_address");
$SYSTEM_EMAIL = ($registered_mail != '') ? $registered_mail : 'test@gmail.com';
$SYSTEM_URL = getConfigValue("site_url");
define("COMPANY_NAME", $COMPANY_NAME);
define("SYSTEM_URL", $SYSTEM_URL);
define("COMPANY_LOGO", $logo);
define("COMPANY_LOCATION", getConfigValue("office_location"));
define('TIME_ZONE',getConfigValue("timezone"));
define("CURRENCY_SYMBOL",getConfigValue("currency_symbol"));
define("CURRENCY_SYMBOL_LOCATION", getConfigValue("currency_symbol_location"));

$roles=array("Admin","Supplier");

$REQUISITION_EDITABLE_STATUS_LIST=array("Requested","Directly Rejected","Financially Rejected","Rejected");

$items_per=getConfigValue("items_per_page");
$ITEMS_PER_PAGE=($items_per)?$items_per:10;

$genderList=array("Male","Female");
$marital_status_array = array("Single", "Married", "Divorced");
$current_country = "Uganda";
$countries_list = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint LUCIA", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Viet Nam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");


$permissionList=array(
	'Admin'=>array("viewRequisition","addRequisition","editRequisition","deleteRequisition","approveRequisition","viewLPOs","addLPO","editLPO","deleteLPO"),
	'Supplier'=>array()
);


$permissions_list_array = array(
    "Subsystems Accessed" => $subsystems_array,
    "FIles and Folders" => array("createFolder" => "Create Folder", "editFolder" => "Edit Folder", "deleteFolder" => "Delete Folder", "shareFolder" => "Share Folder", "uploadFile" => "Upload File", "copyFile" => "Copy File", "moveFile" => "Move File", "editFile" => "Edit File","deleteFile"=>"Delete File", "downloadZIPFolder" => "Download ZIP", "viewArchivedFiles" => "View Archived Files", "accessAllFiles" => "Access all files", "accessOtherDepartmentsFiles" => "Access Other Departments Files"),
    "Policies and Procedures" => array("addPolicy" => "Add Policy","approvePolicy"=>"Approve Policy", "editPolicy" => "Edit Policy", "deletePolicy" => "Delete Policy", "addProcedure" => "Add Procedure", "editProcedure" => "Edit Procedure", "deleteProcedure" => "Delete Procedure"),
    "Users" => array("addUser" => "Add User", "viewUsers" => "View Users", "editUser" => "Edit User", "deleteUser" => "Delete User", "uploadKPI" => "Upload KPIs"),
    "Training" => array("addTraining" => "Add Training", "editTraining" => "Edit Training", "deleteTraining" => "Delete Training", "canAttendTraining" => "Attend Training", "viewAttendance" => "View Attendance", "addAssessment" => "Add assessment"),
    "Client Tools" => array("addTool" => "Add Tool", "editTool" => "Edit Tool", "deleteTool" => "Delete Tool"),
    "Guidelines" => array("addGuideline" => "Add Guideline", "editGuideline" => "Edit Guideline", "deleteGuideline" => "Delete Guideline"),
    "Settings" => array("manageSettings" => "Manage Settings", "viewLogs" => "View Logs", "addUserRole" => "Add user roles", "viewUserStatistics" => "View User Statistics", "viewAssetAttributes" => "View Asset Attributes"),
    "Clients" => array("addClient" => "Add Client", "viewClients" => "View Clients","manageClient"=>"Manage Client","assignAdmin"=>"Assign/Unassign Admin", "editClient" => "Edit Client", "deleteClient" => "Delete Client"),
    "Forms (General)" => array("viewOtherForms" => "View other Forms", "HRSignatory" => "HR Signatory", "PartnerSignatory" => "Partner Signatory", "deleteForm" => "Delete Form", "editForm" => "Edit Form", "canArchiveForms" => "Can archive forms", "viewArchivedForms" => "View Archive", "printArchivedForms" => "Print archived forms"),
    "Appraisal Forms" => array("enableAppraisal" => "Can enable appraisal", "canAppraise" => "Can  Appraise"),
    "Assignment & performance" => array("canRecommendPerformance" => "Can Recommend", "givePerformaceScore" => "Give performance score"),
    "Leave forms" => array("requestLeave" => "Request Leave", "reviewLeave" => "Review Leave form", "approveLeave" => "Approve Leave", "viewLeaveForms" => "View all leave forms"),
    "Mentorship forms" => array("requestMentorship" => "Request Mentorship", "reviewMentorship" => "Review Mentorship"),
    "Audit Job Chart" => array("canAssignStaff" => "Can assign staff", "canLeadTeam" => "Can lead a team"),
    "Assets" => array("addAsset" => "Add Asset", "viewAssets" => "View Assets","manageAsset"=>"Manage","editAsset"=>"Edit Asset", "deleteAsset" => "Delete Asset", "importAssets" => "Import Assets"),
    "Licenses" => array("addLicense" => "Add License", "viewLicenses" => "View Licenses"),
    "Issues" => array("addIssue" => "Add Issue", "viewIssues" => "View Issues","editIssue"=>"Edit Issue","deleteIssue"=>"Delete Issue"),
    "Tickets" => array("addTicket" => "Add Ticket","assignTicket"=>"Assign Ticket", "viewTickets" => "View Tickets","manageTicket"=>"Manage","editTicket"=>"Edit Ticket","deleteTicket"=>"Delete Ticket","manageTicketNotes"=>"Manage Ticket Notes","manageTicketRules"=>"Manage Ticket Rules"),
    "Inventory" => array("addInventory" => "Add Inventory", "importInventory" => "Import Inventory", "viewInventory" => "View Inventory"),
    "Projects"=>array("addProject"=>"Add Project","viewProjects"=>"View Projects","manageProject"=>"Manage","uploadProjectFile"=>"Upload Project File","editProject"=>"Edit Project","deleteProject"=>"Delete","uploadProjectFile"=>"Upload Project File","viewProjectFiles"=>"View Project Files","manageProjectNotes"=>"Manage Project Notes","assignProject"=>"Assign Project","viewAllProjectAssignments"=>"View All Project Assignments"),
    "Reports" => array("viewDepreciationReport" => "Depreciation Report"),
    "Requisition & LPOs"=>array("viewRequisition"=>"View Requisition","addRequisition"=>"Add Requisition","editRequisition"=>"Edit Requisition","deleteRequisition"=>"Delete Requisition","approveRequisition"=>"Approve Requisition","viewLPOs"=>"View LPOs","addLPO"=>"Add LPO","editLPO"=>"Edit LPO","deleteLPO"=>"Delete LPO"),
    "Recruitment"=>array("addRecruitment"=>"Add Recruitment","viewRecruitment"=>"View Recruitment","editRecruitment"=>"Edit Recruitment","approveHODRecruitment"=>"HOD Approve?","approveCEORecruitment"=>"CEO Approve?","deleteRecruitment"=>"Delete Recruitment"),
    "General"=>array("search"=>"Search","addComment"=>"Add Comment","viewComments"=>"View Comments","editComment"=>"Edit Comment","deleteComment"=>"Delete Comment","viewPredefinedReplies"=>"View Predefined Replies","assignComment"=>"Assign Comment"),
    "Client Portal"=>array("createClientFolder"=>"Can Create Folder","uploadInputFile"=>"Upload Input File","uploadOutputFile"=>"Upload Output File")
);

$timezoneList = array (
	    '(UTC-11:00) Midway Island' => 'Pacific/Midway',
	    '(UTC-11:00) Samoa' => 'Pacific/Samoa',
	    '(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
	    '(UTC-09:00) Alaska' => 'US/Alaska',
	    '(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
	    '(UTC-08:00) Tijuana' => 'America/Tijuana',
	    '(UTC-07:00) Arizona' => 'US/Arizona',
	    '(UTC-07:00) Chihuahua' => 'America/Chihuahua',
	    '(UTC-07:00) La Paz' => 'America/Chihuahua',
	    '(UTC-07:00) Mazatlan' => 'America/Mazatlan',
	    '(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
	    '(UTC-06:00) Central America' => 'America/Managua',
	    '(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
	    '(UTC-06:00) Guadalajara' => 'America/Mexico_City',
	    '(UTC-06:00) Mexico City' => 'America/Mexico_City',
	    '(UTC-06:00) Monterrey' => 'America/Monterrey',
	    '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
	    '(UTC-05:00) Bogota' => 'America/Bogota',
	    '(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
	    '(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
	    '(UTC-05:00) Lima' => 'America/Lima',
	    '(UTC-05:00) Quito' => 'America/Bogota',
	    '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
	    '(UTC-04:30) Caracas' => 'America/Caracas',
	    '(UTC-04:00) La Paz' => 'America/La_Paz',
	    '(UTC-04:00) Santiago' => 'America/Santiago',
	    '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
	    '(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
	    '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
	    '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
	    '(UTC-03:00) Greenland' => 'America/Godthab',
	    '(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
	    '(UTC-01:00) Azores' => 'Atlantic/Azores',
	    '(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
	    '(UTC+00:00) Casablanca' => 'Africa/Casablanca',
	    '(UTC+00:00) Edinburgh' => 'Europe/London',
	    '(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
	    '(UTC+00:00) Lisbon' => 'Europe/Lisbon',
	    '(UTC+00:00) London' => 'Europe/London',
	    '(UTC+00:00) Monrovia' => 'Africa/Monrovia',
	    '(UTC+00:00) UTC' => 'UTC',
	    '(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
	    '(UTC+01:00) Belgrade' => 'Europe/Belgrade',
	    '(UTC+01:00) Berlin' => 'Europe/Berlin',
	    '(UTC+01:00) Bern' => 'Europe/Berlin',
	    '(UTC+01:00) Bratislava' => 'Europe/Bratislava',
	    '(UTC+01:00) Brussels' => 'Europe/Brussels',
	    '(UTC+01:00) Budapest' => 'Europe/Budapest',
	    '(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
	    '(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
	    '(UTC+01:00) Madrid' => 'Europe/Madrid',
	    '(UTC+01:00) Paris' => 'Europe/Paris',
	    '(UTC+01:00) Prague' => 'Europe/Prague',
	    '(UTC+01:00) Rome' => 'Europe/Rome',
	    '(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
	    '(UTC+01:00) Skopje' => 'Europe/Skopje',
	    '(UTC+01:00) Stockholm' => 'Europe/Stockholm',
	    '(UTC+01:00) Vienna' => 'Europe/Vienna',
	    '(UTC+01:00) Warsaw' => 'Europe/Warsaw',
	    '(UTC+01:00) West Central Africa' => 'Africa/Lagos',
	    '(UTC+01:00) Zagreb' => 'Europe/Zagreb',
	    '(UTC+02:00) Athens' => 'Europe/Athens',
	    '(UTC+02:00) Bucharest' => 'Europe/Bucharest',
	    '(UTC+02:00) Cairo' => 'Africa/Cairo',
	    '(UTC+02:00) Harare' => 'Africa/Harare',
	    '(UTC+02:00) Helsinki' => 'Europe/Helsinki',
	    '(UTC+02:00) Istanbul' => 'Europe/Istanbul',
	    '(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
	    '(UTC+02:00) Kyiv' => 'Europe/Helsinki',
	    '(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
	    '(UTC+02:00) Riga' => 'Europe/Riga',
	    '(UTC+02:00) Sofia' => 'Europe/Sofia',
	    '(UTC+02:00) Tallinn' => 'Europe/Tallinn',
	    '(UTC+02:00) Vilnius' => 'Europe/Vilnius',
	    '(UTC+03:00) Baghdad' => 'Asia/Baghdad',
	    '(UTC+03:00) Kuwait' => 'Asia/Kuwait',
	    '(UTC+03:00) Minsk' => 'Europe/Minsk',
	    '(UTC+03:00) Nairobi' => 'Africa/Nairobi',
	    '(UTC+03:00) Riyadh' => 'Asia/Riyadh',
	    '(UTC+03:00) Volgograd' => 'Europe/Volgograd',
	    '(UTC+03:30) Tehran' => 'Asia/Tehran',
	    '(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
	    '(UTC+04:00) Baku' => 'Asia/Baku',
	    '(UTC+04:00) Moscow' => 'Europe/Moscow',
	    '(UTC+04:00) Muscat' => 'Asia/Muscat',
	    '(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
	    '(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
	    '(UTC+04:00) Yerevan' => 'Asia/Yerevan',
	    '(UTC+04:30) Kabul' => 'Asia/Kabul',
	    '(UTC+05:00) Islamabad' => 'Asia/Karachi',
	    '(UTC+05:00) Karachi' => 'Asia/Karachi',
	    '(UTC+05:00) Tashkent' => 'Asia/Tashkent',
	    '(UTC+05:30) Chennai' => 'Asia/Calcutta',
	    '(UTC+05:30) Kolkata' => 'Asia/Kolkata',
	    '(UTC+05:30) Mumbai' => 'Asia/Calcutta',
	    '(UTC+05:30) New Delhi' => 'Asia/Calcutta',
	    '(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
	    '(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
	    '(UTC+06:00) Almaty' => 'Asia/Almaty',
	    '(UTC+06:00) Astana' => 'Asia/Dhaka',
	    '(UTC+06:00) Dhaka' => 'Asia/Dhaka',
	    '(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
	    '(UTC+06:30) Rangoon' => 'Asia/Rangoon',
	    '(UTC+07:00) Bangkok' => 'Asia/Bangkok',
	    '(UTC+07:00) Hanoi' => 'Asia/Bangkok',
	    '(UTC+07:00) Jakarta' => 'Asia/Jakarta',
	    '(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
	    '(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
	    '(UTC+08:00) Chongqing' => 'Asia/Chongqing',
	    '(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
	    '(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
	    '(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
	    '(UTC+08:00) Perth' => 'Australia/Perth',
	    '(UTC+08:00) Singapore' => 'Asia/Singapore',
	    '(UTC+08:00) Taipei' => 'Asia/Taipei',
	    '(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
	    '(UTC+08:00) Urumqi' => 'Asia/Urumqi',
	    '(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
	    '(UTC+09:00) Osaka' => 'Asia/Tokyo',
	    '(UTC+09:00) Sapporo' => 'Asia/Tokyo',
	    '(UTC+09:00) Seoul' => 'Asia/Seoul',
	    '(UTC+09:00) Tokyo' => 'Asia/Tokyo',
	    '(UTC+09:30) Adelaide' => 'Australia/Adelaide',
	    '(UTC+09:30) Darwin' => 'Australia/Darwin',
	    '(UTC+10:00) Brisbane' => 'Australia/Brisbane',
	    '(UTC+10:00) Canberra' => 'Australia/Canberra',
	    '(UTC+10:00) Guam' => 'Pacific/Guam',
	    '(UTC+10:00) Hobart' => 'Australia/Hobart',
	    '(UTC+10:00) Melbourne' => 'Australia/Melbourne',
	    '(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
	    '(UTC+10:00) Sydney' => 'Australia/Sydney',
	    '(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
	    '(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
	    '(UTC+12:00) Auckland' => 'Pacific/Auckland',
	    '(UTC+12:00) Fiji' => 'Pacific/Fiji',
	    '(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
	    '(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
	    '(UTC+12:00) Magadan' => 'Asia/Magadan',
	    '(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
	    '(UTC+12:00) New Caledonia' => 'Asia/Magadan',
	    '(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
	    '(UTC+12:00) Wellington' => 'Pacific/Auckland',
	    '(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
	);

