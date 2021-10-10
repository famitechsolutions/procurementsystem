<?php
$id = $_GET["id"];
$users = DB::getInstance()->getRow("user", $id, "*", "id");
$departmentsList = DB::getInstance()->querySample("SELECT * FROM department WHERE status=1 ORDER BY name");
?>
<div class="modal-header">

    <h4 class="modal-title">User Account</h4>
    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="fname" value="<?php echo $users->fname ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="lname" value="<?php echo $users->lname ?>" required>
            </div>
            <div class="form-group">
                <label>Designation</label>
                <input type="text" class="form-control" name="designation" value="<?php echo $users->designation ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $users->phone ?>" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
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
                <label>NIN</label>
                <input type="text" class="form-control" name="nin" value="<?php echo $users->nin ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" name="address"><?php echo $users->address ?></textarea>
            </div>
            <div class="form-group">
                <label>Department <small class="text-danger">[optional]</small></label>
                <select name="department_id" class="form-control">
                    <option value="">Choose</option>
                    <?php
                    foreach ($departmentsList as $depts) {
                        $selected = ($users->department_id == $depts->id) ? ' selected' : '';
                        echo '<option value="' . $depts->id . '" ' . $selected . '>' . $depts->name . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" name="email" <?php echo ($id) ? 'readonly' : '' ?> value="<?php echo $users->email ?>" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" <?php echo ($id) ? 'readonly' : '' ?> value="<?php echo $users->username ?>" required>
            </div>
            <div class="form-group">
                <?php if ($id) { ?>
                    <label>Password <small class="text-danger">[optional]</small></label>
                    <input type="text" class="form-control" id="user_reg_password" name="password">
                <?php } else { ?>
                    <label>Password</label>
                    <input type="text" class="form-control" id="user_reg_password" value="<?php echo generatePassword(); ?>" name="password" required>
                <?php } ?>
            </div>
            <div class="form-group">
                <label>User Role</label>
                <select class="form-control" name="role" required>
                    <option value="">Choose</option>
                    <?php
                    foreach ($roles as $role) {
                        $selected = ($users->category == $role) ? ' selected' : '';
                        echo '<option value="' . $role . '" ' . $selected . '>' . $role . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="action" value="<?php echo ($id) ? 'editUser' : 'addUser' ?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Submit</button>
</div>