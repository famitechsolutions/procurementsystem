<?php
$id = $_GET['id'];
$request = DB::getInstance()->querySample("SELECT r.* FROM requisition r WHERE r.id='$id'")[0];
?>

<div class="modal-header">
    <h4 class="modal-title">Department Requisition - <?php echo $request->requisition_number ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group">
            <label>Signature</label>
            <input class="form-control" name="signature" required>
            <label>Any Comment?</label>
            <textarea class="form-control" name="comment"></textarea>
        </div>
        <input type="hidden" name="action" value="approveRequisition">
        <input type="hidden" name="status" value="Approved">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
    </div>
</form>
