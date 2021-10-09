<?php

ob_start();
session_start();
//error_reporting(E_ALL);
$logo = "uploads/logo.jpg";
$logo_small = "uploads/small_logo.jpg";
$default_avator = 'uploads/user_profiles/default.jpg';
include 'core/init.php';
date_default_timezone_set(TIME_ZONE);
$date_today = date("Y-m-d");
$cu_time = date('Y-m-d H:i');
$current_year = date("Y");
$current_month_year = date("Y-m");
// error_reporting(E_ALL);
$title = $SITE_NAME . " | ";
$page_title = "";
$user_id = (isset($_SESSION['system_user_id'])) ? $_SESSION["system_user_id"] : "";
$user_permissions = (isset($_SESSION['user_permissions'])) ? $_SESSION['user_permissions'] : array();

//Reset the MySQL global variable <sql_mode>
DB::getInstance()->query("SET SESSION sql_mode=''");


$crypt = new Encryption();
$encoded_page = isset($_GET['page']) ? $_GET['page'] : ('login');
$page = $crypt->decode($encoded_page);

if (isset($_GET['modal'])) {
    require('templates/modals/' . $_GET['modal'] . '.php');
    //    die();
}
include 'controllers/actions.php';
include 'controllers/quickactions.php';
//$page = $encoded_page;
if (!isset($_GET['modal'])) {
    switch ($page) {
        default:
            $page = "login";
            include 'templates/pages/users/login.php';
            break;

        case 'dashboard':
            if (file_exists('templates/pages/' . $page . '.php'))
                $page_title = "Dashboard";
            include 'templates/pages/' . $page . '.php';
            break;
        case 'search':
            if (file_exists('templates/pages/' . $page . '.php'))
                $page_title = "Search";
            include 'templates/pages/' . $page . '.php';
            break;
        case 'ajax_data':
            if (file_exists('templates/pages/' . $page . '.php'))
                include 'templates/pages/' . $page . '.php';
            break;

            /* Users***************************** */

        case 'user_roles':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                $page_title = "Users Roles";
            include 'templates/pages/users/' . $page . '.php';
            break;
        case 'manage_role':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                $page_title = "Users Role";
            include 'templates/pages/users/' . $page . '.php';
            break;
        case 'view_users':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                $page_title = "Users";
            include 'templates/pages/users/' . $page . '.php';
            break;
        case 'email_verification':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                $page_title = "Users";
            include 'templates/pages/users/' . $page . '.php';
            break;
        case 'reset_password':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                include 'templates/pages/users/' . $page . '.php';
            break;
        case 'user_profile':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                $page_title = "User Profile";
            include 'templates/pages/users/' . $page . '.php';
            break;
        case 'logout':
            if (file_exists('templates/pages/users/' . $page . '.php'))
                include 'templates/pages/users/' . $page . '.php';
            break;

        case 'site_settings':
            if (file_exists('templates/pages/general_settings/' . $page . '.php'))
                $page_title = "Site Settings";
            include 'templates/pages/general_settings/' . $page . '.php';
            break;
        case 'system_logs':
            if (file_exists('templates/pages/general_settings/' . $page . '.php'))
                $page_title = "System Logs";
            include 'templates/pages/general_settings/' . $page . '.php';
            break;
        case 'download_excel':
            if (file_exists('templates/pages/exports/' . $page . '.php'))
                include 'templates/pages/exports/' . $page . '.php';
            break;
        case 'download_pdf':
            if (file_exists('templates/pages/exports/pdf/' . $page . '.php'))
                include 'templates/pages/exports/pdf/' . $page . '.php';
            break;


        case 'view_suppliers':
            if (file_exists('templates/pages/suppliers/' . $page . '.php'))
                $page_title = "Suppliers list";
            include 'templates/pages/suppliers/' . $page . '.php';
            break;
        case 'auto_email':
            if (file_exists('templates/pages/general_settings/' . $page . '.php'))
                include 'templates/pages/general_settings/' . $page . '.php';
            break;

            //Requisition and LPOs
        case 'requisition':
            if (file_exists('templates/pages/requisitions/' . $page . '.php'))
                $page_title = "Requisition";
            include 'templates/pages/requisitions/' . $page . '.php';
            break;
        case 'lpos':
            if (file_exists('templates/pages/requisitions/' . $page . '.php'))
                $page_title = "LPOs";
            include 'templates/pages/requisitions/' . $page . '.php';
            break;
        case 'manage_requisition':
            if (file_exists('templates/pages/requisitions/' . $page . '.php'))
                $page_title = "Single";
            include 'templates/pages/requisitions/' . $page . '.php';
            break;
    }
}
ob_flush();
