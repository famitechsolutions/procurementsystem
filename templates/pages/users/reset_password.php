<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title ?> Reset password</title>
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
                if (Input::exists() && Input::get("password_reset_button") == "password_reset_button") {
                    $password = SHA1(Input::get("password"));
                    $confirm_password = SHA1(Input::get("confirm_password"));
                    $user_id = $crypt->decode($_GET['user_id']);
                    if ($password == $confirm_password && $user_id != "") {
                        $query = "SELECT * FROM user WHERE user_id='$user_id'";
                        if (DB::getInstance()->checkRows($query)) {
                            //To update the password
                            DB::getInstance()->update("user", $user_id, array("password" => $password), "user_id");
                            echo '<div class="alert alert-success">Your password has been reset, you can now login</div>';
                            Redirect::go_to('index.php?page=' . $crypt->encode('login'));
                        } else {
                            echo '<div class="alert alert-danger"><span>Error while resetting your password.</span></div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger">Your entered passwords do not match</div>';
                    }
                }
                ?>
                <div id="login" class="tab-pane active">
                    <form action="" method="POST">
                        <p class="text-muted text-center">
                            Reset your password
                        </p>
                        <?php $password_length = getConfigValue("password_generator_length"); ?>
                        <div class="form-group">
                            <input type="password" placeholder="Password" autofocus name="password" class="form-control" required minlength="<?php echo $password_length ?>" title="<?php _e("Password must be " . $password_length . " or more  characters") ?>">
                        </div>
                        <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control" required minlength="<?php echo $password_length ?>" title="<?php _e("Password must be " . $password_length . " or more  characters") ?>">
                        <button class="btn btn-lg btn-primary btn-block" name="password_reset_button" value="password_reset_button" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="assets/lib/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>

        <script src="assets/lib/twitter-bootstrap/3.3.1/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/js/rocket-loader.min.js" data-cf-settings="|49" defer=""></script>
    </body>
</html>