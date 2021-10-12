<?php
$id = $_GET['id'];
$list = DB::getInstance()->querySample("SELECT u.*,CONCAT(fname,' ',lname) name,l.* FROM user u,leave_form l WHERE l.submitted_by=u.user_id AND u.status=1 AND l.status=1 AND l.form_id='$id'")[0];
$additional = unserialize($list->additional_info);
?>
<div class="modal-header">
    <h4 class="modal-title"><?php _e('Leave Application Form'); ?>
        <button type="button" class="btn btn-primary" onclick="PrintSection('single-leave-form', '21.0', '29.7')"><?php _e('Print'); ?></button>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body" id="single-leave-form">
    <img src="<?php echo $logo?>" alt="" style="max-width: 100%; max-height: 70px"/>
    <br/>
    <h3>STUDY APPLICATION FORM</h3>
    <table class="table table-bordered">
        <tr><th>
                NAME OF EMPLOYEE:</th><td><?php echo $list->name ?></td><th>	EMPLOYEE NO:</th><td><?php echo $list->employee_number ?></td></tr>
        <tr><th>DESIGNATION:</th><td><?php echo $list->designation ?></td><th> DATE: </th><td><?php echo english_date($list->time_submitted) ?></td></tr>
        <tr><th>LEVEL OF EDUCATION:</th><td colspan="3"> <?php echo $additional['education_level'] ?></td></tr>
        <tr><th>INSTITUTE:</th><td> <?php echo $additional['institute'] ?></td><th> STUDY PERIOD:</th><td> <?php echo $additional['study_period'] ?></td></tr>
        <tr><th>COURSE UNITS TO BE COVERED:</th><td colspan="3"><ol><?php echo '<li>' . str_replace("\n", "</li><li>", $additional['covered_course_units']) . '</li>' ?></ol></td></tr>
        <tr><th>Course units to be examined</th><td colspan="3"> <ol><?php echo '<li>' . str_replace("\n", "</li><li>", $additional['examined_course_units']) . '</li>' ?></ol></td></tr>
        <tr><th>Course units to be examined in the next sitting</th><td colspan="3"> <ol><?php echo '<li>' . str_replace("\n", "</li><li>", $additional['course_units_next_sitting']) . '</li>' ?></ol></td>
        </tr>
        <tr><th>EMPLOYEE SIGNATURE</th><td colspan="3"></td></tr>
        <tr><th>Supervisor’s Signature and Date</th><td colspan="3"></td></tr>
        <tr><th>HROO Signature and Date</th><td colspan="3"></td></tr>
    </table>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="PrintSection('single-leave-form', '21.0', '29.7')"><?php _e('Print'); ?></button>
    <button type="button" class="btn btn-warning" data-dismiss="modal"><?php _e('Close'); ?></button>
</div>
