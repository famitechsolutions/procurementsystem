<?php
$id = $_GET['id'];
?>

<div class="modal-header">
    <h4 class="modal-title">Reject Requisition</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <label>Reason/Comment</label>
    <textarea class="form-control" name="comment" required></textarea>
    <br/>
    <div class="alert alert-warning"> The whole request will be rejected</div>
    <input type="hidden" name="action" value="rejectRequisition">
    <input type="hidden" name="requisition_id" value="<?php echo $id ?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> <?php _e('Continue'); ?></button>
</div>