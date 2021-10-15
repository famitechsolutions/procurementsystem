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
                    <div class="col-md-8 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <?php
                            if (isset($_SESSION["message"])) {
                                echo '<div class="alert alert-'.$_SESSION["message"]['status'].'">'.$_SESSION["message"]['message'].'</div>';
                                if ($_SESSION["message"]['counts'] > 0) {
                                    unset($_SESSION["message"]);
                                } else {
                                    $_SESSION["message"]['counts']++;
                                }
                            }
                            ?>
                            <h2 class="font-weight-light text-center card-title">Sign Up to create an account.</h2>
                            <form class="pt-3" action="" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        First Name
                                        <input type="text" name="fname" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        Last Name</label>
                                        <input type="text" name="lname" class="form-control" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required />

                                    </div>
                                    <div class="col-md-6">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Phone Number</label>
                                        <input type="phone" name="phone" class="form-control" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Designation</label>
                                        <input type="text" name="designation" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label>NIN</label>
                                        <input type="text" name="nin" class="form-control" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Gender</label>
                                        <select class="form-control" name="gender">
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Date of Birth</label>
                                        <input class="form-control" name="dob" type="date" placeholder="dd/mm/yyyy" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Address</label>
                                        <textarea type="text" name="address" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <input name="action" value="registerUser" type="hidden">
                                        <input name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page']) ?>" type="hidden">
                                        <button type="submit" name="registerUser" value="registerUser" class="btn btn-lg font-weight-medium auth-form-btn btn-primary btn-block">Sign Up</button>
                                        Already Have an Account? <a href="index.php?page=<?php echo $crypt->encode('login'); ?>" class="text-primary">Login</a>
                                    </div>
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