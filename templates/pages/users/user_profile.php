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
                                $usersCheck = "SELECT * FROM user u,user_roles r,user_grades g, department d WHERE g.grade_id=u.grade_id AND r.role_id=u.role_id AND u.department_id=d.department_id AND d.status=1 AND u.status=1 AND u.is_verified=1 AND u.user_id='$profile_user_id' ORDER BY fname";
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
                                                                        <div class="form-group">
                                                                            <label>Employee No.</label>
                                                                            <input type="text" class="form-control" name="employee_number" value="<?php echo $users->employee_number ?>">
                                                                        </div>
                                                                        <label>Date of birth</label>
                                                                        <input type="date" max="<?php echo $date_today ?>" class="form-control" name="dob" value="<?php echo $users->dob ?>">
                                                                        <label>National ID</label>
                                                                        <input type="text" class="form-control" name="national_id" value="<?php echo $users->national_id ?>">
                                                                        <label>Phone Number</label>
                                                                        <input type="text" class="form-control" name="user_phone" value="<?php echo $users->user_phone ?>">
                                                                        <label>Email Address</label>
                                                                        <input type="text" name="email" value="<?php echo $users->user_email ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label>Gender</label>
                                                                        <select class="form-control" name="gender">
                                                                            <option value="">Choose</option>
                                                                            <?php
                                                                            foreach ($genderList AS $gender) {
                                                                                $selected = $users->gender == $gender ? ' selected' : '';
                                                                                echo '<option value="' . $gender . '" ' . $selected . '>' . $gender . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Profile pic <small class="text-danger">optional</small></label>
                                                                        <input type="file" accept="image/" name="profile_picture" class="form-control">
                                                                        <label><?php _e('Theme'); ?></label>
                                                                        <select class="form-control" name="theme">
                                                                            <?php
                                                                            foreach ($theme_colors_list AS $code => $value) {
                                                                                $selected = ($code == $users->theme) ? 'selected' : '';
                                                                                echo '<option class="bg-' . $code . '" value="' . $code . '" ' . $selected . '>' . $value . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <label for="sidebar"><?php _e('Sidebar'); ?></label>
                                                                        <select class="form-control" name="sidebar">
                                                                            <?php
                                                                            foreach ($sidebarOptions AS $option => $value) {
                                                                                $selected = ($option == $users->sidebar) ? 'selected' : '';
                                                                                echo '<option value="' . $option . '" ' . $selected . '>' . $value . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <label for="layout"><?php _e('Layout'); ?></label>
                                                                        <select class="form-control" id="layout" name="layout">
                                                                            <?php
                                                                            foreach ($layoutOptions AS $option => $value) {
                                                                                $selected = ($option == $users->layout) ? 'selected' : '';
                                                                                echo '<option value="' . $option . '" ' . $selected . '>' . $value . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
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
                                                                    <input name="user_id" value="<?php echo $users->user_id ?>" type="hidden">
                                                                    <input name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page']) ?>" type="hidden">
                                                                    <button type="submit" name="edit_user_btn" value="edit_user_btn" class="btn btn-sm btn-primary">Save</button>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="card">
                                                <header class="card-title">
                                                    <h5>Bank details</h5>
                                                </header>
                                                <?php
                                                $isDisabled = ($users->bank_id != "") ? "disabled" : "";
                                                ?>
                                                <div class="card-body">
                                                    <form action="" method="POST">
                                                        <div class="form-group">
                                                            <label>Bank Name</label>
                                                            <select class="form-control select2" <?php echo $isDisabled ?> style="width: 100%" name="bank_id" required>
                                                                <option value="">Choose..</option>
                                                                <?php
                                                                $banksList = DB::getInstance()->querySample("SELECT * FROM bank WHERE status=1 ORDER BY name");
                                                                foreach ($banksList AS $list) {
                                                                    $selected = ($list->id == $users->bank_id) ? "selected" : "";
                                                                    echo '<option value="' . $list->id . '" ' . $selected . '>' . $list->name . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <label>Branch Location</label>
                                                            <input type="text" <?php echo $isDisabled ?> class="form-control" value="<?php echo $users->branch_location ?>" name="branch_location" required>
                                                            <label>Account Number</label>
                                                            <input type="text" <?php echo $isDisabled ?> class="form-control" value="<?php echo $users->account_number ?>" name="account_number" required>
                                                            <label>Account Name</label>
                                                            <input type="text" <?php echo $isDisabled ?> class="form-control" value="<?php echo $users->account_name ?>" name="account_name" required>
                                                            <label>NSSF Number</label>
                                                            <input type="text" <?php echo $isDisabled ?> class="form-control" value="<?php echo $users->nssf_number ?>" name="nssf_number" required>
                                                            <label>TIN</label>
                                                            <input type="text" <?php echo $isDisabled ?> class="form-control" value="<?php echo $users->tin ?>" name="tin" required>
                                                        </div>
                                                        <label style="color: red"><i class="fa fa-warning"></i> Once submitted cannot be edited</label><br/>
                                                        <?php if ($profile_user_id == $user_id) { ?>
                                                            <input name="action" value="editBankDetails" type="hidden">
                                                            <input name="user_id" value="<?php echo $users->user_id ?>" type="hidden">
                                                            <input name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page']) ?>" type="hidden">
                                                            <button <?php echo $isDisabled ?> class="btn btn-sm btn-primary" type="submit">Save</button>
                                                        <?php } ?>
                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($subsystem == 'leave_mgt') { ?>
                                        <div class="row" id="leave-forms">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <header class="card-title">
                                                        <h5>Leave Statistics</h5>
                                                    </header>
                                                    <div class="card-body">
                                                        <?php
                                                        $userLeaves = DB::getInstance()->querySample("SELECT l.reason_for_leave, COUNT(l.submitted_by) AS total_leave,
                                        SUM(CASE WHEN l.is_accepted IS NULL THEN 1 ELSE 0 END) pending_leave,
                                        SUM(CASE WHEN l.is_accepted=1 THEN 1 ELSE 0 END) reviewed_leave,
                                        SUM(CASE WHEN l.is_accepted=0 THEN 1 ELSE 0 END) abandoned_leave,
                                        SUM(CASE WHEN l.is_approved=1 AND l.reason_for_leave='Annual' THEN l.leave_days_given ELSE 0 END) total_approved_annual_days,
                                        SUM(CASE WHEN l.is_approved=1 THEN l.leave_days_given ELSE 0 END) total_approved_days,
                                        SUM(CASE WHEN l.is_approved=1 THEN 1 ELSE 0 END) approved_leave,
                                        SUM(CASE WHEN l.is_approved=0 THEN 1 ELSE 0 END) rejected_leave
                                        FROM user AS u JOIN leave_form AS l ON (u.user_id=l.submitted_by AND l.status=1)
                                        WHERE u.status=1 AND u.user_id='$profile_user_id' AND YEAR(leave_start_date)='$current_year' GROUP BY u.user_id,l.reason_for_leave");
                                                        if ($userLeaves) {
                                                            ?>
                                                            <div class="table-responsive">
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead><tr><th>Type</th><th>Requested</th><th>Pending</th><th>Rejected</th><th>Approved</th><th>Appr.Days</th><th>Rem.</th></tr></thead>
                                                                    <tbody>
                                                                        <?php foreach ($userLeaves AS $leave) { ?>
                                                                            <tr>
                                                                                <td><?php echo $leave->reason_for_leave ?></td>
                                                                                <td><?php echo $leave->total_leave ?></td>
                                                                                <td><?php echo $leave->pending_leave ?></td>
                                                                                <td><?php echo $leave->rejected_leave ?></td>
                                                                                <td><?php echo $leave->approved_leave ?></td>
                                                                                <td><?php echo $leave->total_approved_days ?></td>
                                                                                <td><?php echo ($leave->reason_for_leave == 'Annual' && $maximum_annual_leave_days > $leave->total_approved_annual_days) ? $maximum_annual_leave_days - $leave->total_approved_annual_days : '' ?></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <?php
                                                        } else {
                                                            echo '<div class="alert alert-danger">No data to show</div>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
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
