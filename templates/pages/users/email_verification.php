<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title ?> Verification</title>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta name="msapplication-TileColor" content="#5bc0de" />

        <link rel="stylesheet" href="assets/lib/twitter-bootstrap/3.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/lib/animate.css/3.2.0/animate.min.css">

        <link rel="stylesheet" href="assets/css/main.min.css">
        <link rel="shortcut icon" href="<?php echo $logo_small; ?>" /> 
    </head>
    <body class="login">
        <div class="form-signin">
            <div class="text-center">
                <img src="<?php echo $logo ?>" style="width:100%" alt="">
            </div>
            <hr>
            <div class="tab-content">
                <?php
                if (isset($_GET["user_verification"]) && $_GET["user_verification"] == "true" && $_GET["user"] != "") {
                    $user_id = $crypt->decode(Input::get("user"));
                    if ($user_id) {
                        DB::getInstance()->update("user", $user_id, array("is_verified" => 1), "user_id");
                        echo '<div class="alert alert-success">Your account verified successfully<br/>'
                        . 'You can login now</div>';
                        Redirect::go_to("index.php");
                    }
                }
                ?>
            </div>
        </div>

        <script src="assets/lib/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>

        <script src="assets/lib/twitter-bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/rocket-loader.min.js" data-cf-settings="|49" defer=""></script>
    </body>
</html>