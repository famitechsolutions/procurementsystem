<?php

##################################
###       QUICK ACTIONS        ###
##################################

if (isset($_GET['qa'])) {
    $user_id = $_SESSION['system_user_id'];
    $status = "";
    $message = "";
    switch ($_GET['qa']) {

        case "approveCEORecruitment":
            $id = $crypt->decode($_GET['id']);
            break;
    } // end switch
    if ($message != "") {
        $_SESSION["message"] = array('status' => $status, 'message' => $message);
    }
    Redirect::to('?'.$crypt->decode($_GET['reroute']));
    //Redirect::to("index.php?page=" . $_GET['page'] . "&id=" . $_GET['id'] . "&tab=" . $_GET['tab']);
}


