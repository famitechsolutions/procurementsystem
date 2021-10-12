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
                            $id = isset($_GET["id"]) ? $crypt->decode($_GET["id"]) : "";
                            if ($id) {
                                $roles = DB::getInstance()->getRow("user_roles", $id, "*", "role_id");

                                //$permissions = unserialize($roles->permissions);
                            } else {
                                $permissions = array();
                            }
                            ?>
                            <div class="card card-body">
                                <div class="card-title">
                                    <?php echo ($id) ? "Edit" : "New"; ?> user role
                                </div>
                                <form action="" method="POST" name="roleForm">
                                    <div class="form-group">
                                        <label>Role Name</label>
                                        <input type="hidden" name="id" value="<?php echo $id ?>">
                                        <input type="text" class="form-control" name="role_name" value="<?php echo $roles->role_name ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Permissions</label>
                                        <div class="row">
                                            <?php
                                            $modules = DB::getInstance()->querySample("SELECT DISTINCT(module) module FROM permissions");
                                            foreach ($modules as $module) {
                                                $q="SELECT up.*,p.name,p.code,p.id FROM permissions p LEFT JOIN user_permission up ON(p.id=up.permission_id AND up.role_id='$id') WHERE p.module='$module->module' GROUP BY p.id,p.code,p.name";
                                                $permissions = DB::getInstance()->querySample($q);
                                                echo '<div class="col-sm-4 col-md-2"><h6 class="text-primary">' . $module->module . '</h6>';
                                                foreach ($permissions as $permission) {
                                                    $checked = $permission->role_id ? "checked" : "";
                                                    echo '<div><label><input type="checkbox" name="permissions[]" ' . $checked . ' value="' . $permission->id . '"> ' . $permission->name . '</label></div>';
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="action" value="addUserRole">
                                    <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page='.$crypt->encode("user_roles")); ?>">
                                    <div class="modal-footer">
                                        <a onclick="javascript:checkAll('roleForm', true);" href="javascript:void();" class="btn btn-default"><i class="fa fa-check-square-o"></i> Check All</a>
                                        <a onclick="javascript:checkAll('roleForm', false);" href="javascript:void();" class="btn btn-default"><i class="fa fa-square-o"></i> Uncheck All</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
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