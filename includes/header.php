<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>Users List - SB Admin Pro</title>
<link href="assets/css/styles.css" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
<script data-search-pseudo-elements="" defer="" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>


<?php
if ((empty($_SESSION['system_user_id'])) && (empty($_SESSION['system_emmergencepassword']))) {
    Redirect::to('index.php?page=' . $crypt->encode("logout"));
}
$user_role = $_SESSION['system_user_role'];
$user_id = $_SESSION['system_user_id'];
$user_department_id = $_SESSION['user_department_id'];
$user_department_name = DB::getInstance()->getName("department", $user_department_id, "name", "id");
$userInfo = DB::getInstance()->getRow("user", $user_id, "*", "id");
