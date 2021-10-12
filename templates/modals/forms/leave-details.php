<?php
$id = $_GET['id'];
$list = DB::getInstance()->querySample("SELECT u.*,CONCAT(fname,' ',lname) name,l.*,d.department_name,(SELECT CONCAT(fname,' ',lname) name FROM user us WHERE us.user_id=l.supervisor) supervisor,(SELECT CONCAT(fname,' ',lname) name FROM user us WHERE us.user_id=l.approved_by) approved_by FROM user u,leave_form l, department d WHERE d.department_id=u.department_id AND l.submitted_by=u.user_id AND u.status=1 AND l.status=1 AND l.form_id='$id'")[0];
?>
<div class="modal-header">
    <h4 class="modal-title"><?php _e('Leave Application Form'); ?>
        <button type="button" class="btn btn-primary" onclick="PrintSection('single-leave-form', '21.0', '29.7')"><?php _e('Print'); ?></button>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body" id="single-leave-form">
    <img src="<?php echo $logo ?>" alt="" style="max-width: 100%; max-height: 70px"/>
    <h2>LOCATION: <?php echo getConfigValue("office_location") ?></h2>
    <br/>
    <h3>Employee Leave Statistics For Year <?php echo substr($list->time_submitted, 0, 4) ?></h3>
    <table class="table table-bordered">
        <tr><th>
                NAME OF EMPLOYEE:</th><td><?php echo $list->name ?></td><th>	Department</th><td><?php echo $list->department_name ?></td></tr>
        <tr><th>	EMPLOYEE NO:</th><td><?php echo $list->employee_number ?></td><th> Email </th><td><?php echo $list->user_email ?></td></tr>
    </table>
    <h4></h4>
    <table class="table table-bordered">
        <tr><th>Category</th><td><?php echo $list->reason_for_leave ?></td></tr>
        <tr><th>Date Submitted</th><td><?php echo english_date_time($list->time_submitted) ?></td></tr>
        <tr><th>Approved Days</th><td><?php echo $list->leave_days_given ?></td></tr>
        <?php if ($list->reason_for_leave == "Annual") { ?><tr><th>Remaining Days</th><td><?php echo ($maximum_annual_leave_days > $list->leave_days_given) ? $maximum_annual_leave_days - $list->leave_days_given : 0 ?></td></tr><?php } ?>
        <tr><th>Start Date</th><td><?php echo english_date($list->leave_start_date) ?></td></tr>
        <tr><th>End Date</th><td><?php echo english_date($list->leave_end_date) ?></td></tr>
        <tr><th>Reviewed By</th><td><?php echo $list->supervisor ?></td></tr>
        <tr><th>Approved On</th><td><?php echo english_date($list->date_approved) ?></td></tr>
        <tr><th>Approved By</th><td><?php echo $list->approved_by ?></td></tr>
    </table>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="PrintSection('single-leave-form', '21.0', '29.7')"><?php _e('Print'); ?></button>
    <button type="button" class="btn btn-warning" data-dismiss="modal"><?php _e('Close'); ?></button>
</div>
