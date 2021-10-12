<?php
$id = $_GET["id"];
$userList = DB::getInstance()->querySample("SELECT u.user_id, CONCAT(fname,' ',lname) AS full_names FROM user u,user_permission up,permissions p WHERE u.status=1 AND u.user_id!='$user_id' AND up.role_id=u.role_id AND up.permission_id=p.id AND p.code='approveLeave' GROUP BY u.user_id ORDER BY full_names");
?>

<div class="modal-header">
    <h4 class="modal-title">New Leave Form</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">

    <div class="form-group">
        <label>Employee</label>
        <input class="form-control" type="text" value="<?php echo $_SESSION['user_full_names'] ?>" readonly>
    </div>
    <div class="form-group">
        <label>Reason for leave</label>
        <select class="form-control" name="leave_reason" required onchange="calculateLeaveDays(this.value, '<?php echo DB::getInstance()->getName("user", $user_id, "gender", "user_id") ?>');">
            <option value="">Select..</option>
            <?php
            for ($i = 0; $i < count($leave_categories_array); $i++) {
                echo '<option value="' . $leave_categories_array[$i] . '">' . $leave_categories_array[$i] . '</option>';
            }
            ?>
        </select>
    </div>
    <div id="leaveDays"></div>
    <div class="form-group">
        <label>Start date</label>
        <input class="form-control" type="date" name="leave_start_date" required>
    </div>
    <div class="form-group">
        <label>End date</label>
        <input class="form-control" type="date" name="leave_end_date" required>
    </div>
    <div class="form-group">
        <label>Manager/Senior</label>
        <select class="form-control select2" name="supervisor" style="width:100%" required>
            <option value="">Choose</option>
            <?php
            
            foreach ($userList as $user) {
                echo '<option value="' . $user->user_id . '">' . $user->full_names . '</option>';
            }
            ?>
        </select>
    </div>

    <input type="hidden" name="action" value="addLeaveForm">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php _e('Create'); ?></button>
</div>

<script type="text/javascript">
    $(".select2").select2();

    function formatIcon(icon) {
        if (!icon.id) {
            return icon.text;
        }
        var originalOption = icon.element;
        var $icon = $('<span></span>').append($('<i class="fa ' + $(originalOption).data('icon') + '"></i>')).append(icon.text);
        return $icon;
    }

    $('.select2-icon').select2({
        templateResult: formatIcon,
        templateSelection: formatIcon
    });
    $('.summernote').summernote({
        height: 300
    });
</script>