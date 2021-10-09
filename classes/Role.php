<?php
class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }

    // return a role object with associated permissions
    public static function getRolePerms($role_id) {
        $role = new Role();
        $sql = "SELECT * FROM user_permission up
                JOIN permissions p ON up.permission_id = p.id
                WHERE up.role_id = '$role_id'";

        $data=DB::getInstance()->querySample($sql);
        $perms=array();
        foreach ($data as $value) {
            array_push($perms,$value->code);
            //$role->permissions[$value->code] = $value->code;
        }
        return $perms;
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
    public static function registerPermissions(){
        GLOBAL $permissions_list_array;
        foreach ($permissions_list_array as $module => $perms) {
            foreach($perms AS $key=>$value){
                if(!DB::getInstance()->checkRows("SELECT * FROM permissions WHERE code='$key'")){
                    DB::getInstance()->insert("permissions",array("module"=>$module,"code"=>$key,"name"=>$value));
                }
            }
        }
    }
    public static function registerUserPermissions(){
       $roles=DB::getInstance()->querySample("SELECT * FROM user_roles");
       foreach($roles AS $role){
           if(!DB::getInstance()->checkRows("SELECT * FROM user_permission WHERE role_id='$role->role_id'")){
               $permissions=unserialize($role->permissions);
               //$data=DB::getInstance()->querySample("SELECT * FROM permission WHERE code IN (".implode(",",$permissions).")");
               foreach($permissions AS $perm_name){
				   $perm=DB::getInstance()->getRow("permissions",$perm_name,"*","code");
					if($perm->id){
					   DB::getInstance()->insert("user_permission",array("role_id"=>$role->role_id,"permission_id"=>$perm->id));
				   }
               }
           }
       }
    }
}

