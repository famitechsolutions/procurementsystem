<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title ?> Login</title>
    <link rel="stylesheet" href="assets/libs/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/libs/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style-latest.css">
    <link rel="shortcut icon" href="<?php echo $logo_small; ?>" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <h4 class="font-weight-light text-center">Account Verification</h4>
                            <form class="pt-3" action="" method="POST">
                                <div class="form-group">
                                    <?php
                                    if (isset($_GET["user"]) && $_GET["user_verification"] == "true" && $_GET["user"] != "") {
                                        $user_id = $crypt->decode(Input::get("user"));
                                        if ($user_id) {
                                            DB::getInstance()->update("user", $user_id, array("is_verified" => 1), "id");
                                            echo '<div class="alert alert-success">Your account verified successfully<br/>'
                                            . 'You can login now</div>';
                                            ?>
                                            <a class="btn btn-primary btn-lg btn-block" href="?page=<?php echo $crypt->encode('login')?>">Login now</a>
                                            <?php
                                            // Redirect::go_to("index.php");
                                        }
                                    }else{
                                        echo '<div class="alert alert-danger">No account attached</div>';
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <script src="assets/libs/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
</body>

</html>