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
                                <h4 class="font-weight-light text-center">Sign in to continue.</h4>
                                <form class="pt-3" action="" method="POST">
                                    <div class="form-group">
                                        Activate <?php activate_user(); ?>
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
