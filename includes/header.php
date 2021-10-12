<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta name="description" content="<?php echo $SITE_DESCRIPTION ?>" />
<meta name="author" content="Wilber Ninsiima" />
<title><?php echo $title . $page_title ?> </title>

<!-- base:css -->
<link rel="stylesheet" href="assets/lib/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="assets/lib/css/vendor.bundle.base.css">
<link rel="stylesheet" href="assets/lib/font-awesome/css/font-awesome.min.css"/>
<!-- endinject -->
<!-- plugin css for this page -->
<link rel="stylesheet" href="assets/lib/select2/select2.min.css">
<link rel="stylesheet" href="assets/lib/select2-bootstrap-theme/select2-bootstrap.min.css">
<link rel="stylesheet" href="assets/lib/summernote/dist/summernote-bs4.css">
<link rel="stylesheet" href="assets/lib/jquery-asColorPicker/css/asColorPicker.min.css">
<link rel="stylesheet" href="assets/lib/colorpicker/colorpicker.css">
<link rel="stylesheet" href="assets/lib/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="assets/lib/bootstrap-slider/bootstrap-slider.min.css">
<link rel="stylesheet" href="assets/lib/jquery-toast-plugin/jquery.toast.min.css">
  <!-- <link rel="stylesheet" href="assets/lib/jquery-contextmenu/jquery.contextMenu.min.css"> -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="assets/css/style-latest.css">
<!-- favicon -->
<link rel="shortcut icon" href="<?php echo $logo_small; ?>" /> 

<?php
if ((empty($_SESSION['system_user_id'])) && (empty($_SESSION['system_emmergencepassword']))) {
    if ($page != 'answer_tool') {
        Redirect::to('index.php?page=' . $crypt->encode("logout"));
    }
}
$user_role = $_SESSION['system_user_role'];
$user_id = $_SESSION['system_user_id'];
$user_department_id = $_SESSION['user_department_id'];
$user_department_name = DB::getInstance()->getName("department", $user_department_id, "name", "id");
$user_permissions = $_SESSION['user_permissions'];
$_SESSION['subsystem'] = (isset($_SESSION['subsystem'])) ? $_SESSION['subsystem'] : $subsystems_array[0];
$userInfo = DB::getInstance()->getRow("user", $user_id, "*", "id");
