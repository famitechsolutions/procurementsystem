<?php

session_start();
unset($_SESSION['system_user_role']);
unset($_SESSION['system_user_grade']);
unset($_SESSION['system_username']);
unset($_SESSION['system_emmergencepassword']);
unset($_SESSION['system_user_id']);
unset($_SESSION['system_staff_id']);
unset($_SESSION['system_staff_names']);
unset($_SESSION['system_profile_picture']);
unset($_SESSION['system_organisation_id']);
unset($_SESSION['system_organisations']);
unset($_SESSION['system_modules_accessed']);
unset($_SESSION["PREVIOUS_URL"]);
Redirect::to('index.php?page=' . $crypt->encode("login"));
