<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/header.php'; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php require_once 'includes/header_menu.php'; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php require_once 'includes/side_menu.php'; ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $profile_user_id = isset($_GET['user_id']) ? $crypt->decode($_GET['user_id']) : $user_id;
                            $usersCheck = "SELECT * FROM user u LEFT JOIN department d ON (u.department_id=d.id) WHERE u.status=1 AND u.is_verified=1 AND u.id='$profile_user_id' ORDER BY fname";
                            if (DB::getInstance()->checkRows($usersCheck)) {
                                $users = DB::getInstance()->querySample($usersCheck)[0];
                            ?>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="card">
                                            <header class="card-title">
                                                <h5>Biodata</h5>
                                            </header>
                                            <div class="card-body">
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="review-content-section">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>First Name *</label>
                                                                    <input type="text" class="form-control" name="fname" value="<?php echo $users->fname ?>" required>
                                                                    <label>Last Name *</label>
                                                                    <input type="text" class="form-control" name="lname" value="<?php echo $users->lname ?>" required>
                                                                    <div class="form-group">
                                                                        <label>Designation</label>
                                                                        <input type="text" class="form-control" name="designation" value="<?php echo $users->designation ?>" required>
                                                                    </div>
                                                                    <label>Date of birth</label>
                                                                    <input type="date" max="<?php echo $date_today ?>" class="form-control" name="dob" value="<?php echo $users->dob ?>">
                                                                    <label>National ID</label>
                                                                    <input type="text" class="form-control" name="nin" value="<?php echo $users->nin ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Phone Number</label>
                                                                    <input type="text" class="form-control" name="phone" value="<?php echo $users->user_phone ?>">
                                                                    <label>Email Address</label>
                                                                    <input type="text" name="email" value="<?php echo $users->email ?>" class="form-control" readonly>
                                                                    <label>Gender</label>
                                                                    <select class="form-control" name="gender">
                                                                        <option value="">Choose</option>
                                                                        <?php
                                                                        foreach ($genderList as $gender) {
                                                                            $selected = $users->gender == $gender ? ' selected' : '';
                                                                            echo '<option value="' . $gender . '" ' . $selected . '>' . $gender . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Password <small class="text-danger">optional</small></label>
                                                                    <?php $password_length = getConfigValue("password_generator_length"); ?>
                                                                    <input type="password" minlength="<?php echo $password_length ?>" title="<?php _e("Password must be " . $password_length . " or more  characters") ?>" name="password" class="form-control">
                                                                    <label>Confirm password</label>
                                                                    <input type="password" name="confirm_password" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($profile_user_id == $user_id) { ?>
                                                            <div class="form-group">
                                                                <input name="action" value="editUserProfile" type="hidden">
                                                                <input name="user_id" value="<?php echo $users->id ?>" type="hidden">
                                                                <input name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page']) ?>" type="hidden">
                                                                <button type="submit" name="edit_user_btn" value="edit_user_btn" class="btn btn-sm btn-primary">Save</button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                echo '<div class="alert alert-warning">Nothing to display</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <?php require_once 'includes/footer_menu.php'; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>