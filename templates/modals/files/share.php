<?php
$id = $_GET['id'];
$share_type = $_GET['type'];
?>
<div class="modal-header">
    <h4 class="modal-title"><?php _e('Share'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
</form>
<form action="" id="shareFileForm" method="POST" onsubmit="event.preventDefault();shareFiles(this);">
    <div class="modal-body">
        <table class="table table-bordered" id="share-table">
            <tr>
                <th>User</th>
                <th style="width: 30%;">Can</th>
            </tr>
            <?php
            $query = "SELECT u.user_id, CONCAT(fname,' ',lname) AS full_names, fs.* FROM user u LEFT JOIN file_shared fs ON (fs.shared_to=u.user_id  AND fs.$share_type='$id') WHERE u.Status=1 AND u.user_id!='$user_id' ORDER BY full_names";
            $userList = DB::getInstance()->querySample($query);
            foreach ($userList as $users) {
            ?>
                <tr>
                    <td>
                        <label><input type="checkbox" <?php echo ($users->shared_to != '') ? 'checked' : '' ?> id="user_share_id<?php echo $users->user_id . 'folder_' . $id ?>" onchange="returnUserSharedAction(this, 'folder_<?php echo $id ?>');" value="<?php echo $users->user_id ?>" name="shared_to[]"> <?php echo $users->full_names; ?></label>
                    </td>
                    <td id="user_share_td<?php echo $users->user_id . 'folder_' . $id ?>">
                        <?php
                        if ($users->shared_to != '') {
                            $can_edit_checked = ($users->can_edit == 1) ? 'checked' : '';
                            $can_delete_checked = ($users->can_delete == 1) ? 'checked' : '';
                            echo '<label><input type="checkbox" ' . $can_edit_checked . ' value="1" name="can_edit[' . $users->user_id . ']"> Edit</label>&nbsp;&nbsp;&nbsp;<label><input type="checkbox" ' . $can_delete_checked . ' value="1" name="can_delete[' . $users->user_id . ']"> Delete</label>';
                        }
                        ?>
                    </td>
                <?php }
                ?>
        </table>
    </div>

    <input type="hidden" name="action_made" value="shareFile">
    <input type="hidden" name="executeUserAction" value="executeUserAction">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="share_type" value="<?php echo $share_type ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-share"></i> <?php _e('Share'); ?></button>
    </div>
</form>