<?php
$id = $_GET["id"];
if ($id) {
    $roles = DB::getInstance()->getRow("user_roles", $id, "*", "role_id");
    
    //$permissions = unserialize($roles->permissions);
} else {
    $permissions = array();
}
?>
<form action="" method="POST" name="roleForm">
    <div class="modal-header">
        <h4 class="modal-title"><?php echo ($id) ? "Edit" : "New"; ?> user role</h4>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Role Name</label>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="text" class="form-control" name="role_name" value="<?php echo $roles->role_name ?>" required>
        </div>
        <div class="form-group">
            <label>Permissions</label>
            <div class="row">
                <?php
                $modules=DB::getInstance()->querySample("SELECT DISTINCT(module) module FROM permissions");
                foreach($modules AS $module){
                    $permissions=DB::getInstance()->querySample("SELECT *,p.id FROM permissions p LEFT JOIN user_permission up ON(p.id=up.permission_id AND up.role_id='$id') WHERE p.module='$module->module' GROUP BY p.id");
                    echo '<div class="col-sm-4 col-md-2"><h6 class="text-primary">' . $module->module . '</h6>';
                foreach($permissions AS $permission){
                    $checked = $permission->role_id ? "checked" : "";
                        echo '<div><label><input type="checkbox" name="permissions[]" ' . $checked . ' value="' . $permission->id . '"> ' . $permission->name . '</label></div>';
                }
                echo '</div>';
            }
                // foreach ($permissions_list_array AS $key => $value) {
                //     echo '<div class="col-sm-4 col-md-2"><h6 class="text-primary">' . $key . '</h6>';
                //     foreach ($value AS $perm_id => $perm_value) {
                //         $checked = (in_array($perm_id, $permissions)) ? "checked" : "";
                //         echo '<div><label><input type="checkbox" name="permissions[]" ' . $checked . ' value="' . $perm_id . '"> ' . $perm_value . '</label></div>';
                //     }
                //     echo '</div>';
                // }
                ?>
            </div>
        </div>
        <input type="hidden" name="action" value="addUserRole">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <a onclick="javascript:checkAll('roleForm', true);" href="javascript:void();" class="btn btn-default" ><i class="fa fa-check-square-o"></i> Check All</a>
        <a onclick="javascript:checkAll('roleForm', false);" href="javascript:void();" class="btn btn-default" ><i class="fa fa-square-o"></i> Uncheck All</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>