<?php
$id = $_GET['id'];
$lpo_id=$_GET['lpo_id'];
?>

<div class="modal-header">
    <h4 class="modal-title">Deleting LPO</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <div class="modal-body">
        <p class="alert alert-warning fa fa-warning"> The LPO will be permanently removed. And all items that are attached to this lpo will be marked as un attached</p>
        <input type="hidden" name="action" value="deleteLPO">
        <input type="hidden" name="requisition_id" value="<?php echo $id ?>">
        <input type="hidden" name="lpo_id" value="<?php echo $lpo_id ?>">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> <?php _e('Continue'); ?></button>
    </div>
