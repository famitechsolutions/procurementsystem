<?php
$id = $_GET['id'];
?>

<div class="modal-header">
    <h4 class="modal-title">Open Request for proposal</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
    <div class="modal-body">
        <p class="alert alert-warning"><i class=" fa fa-warning"></i> Once opened, there will be no more edits and adding attachments, and interested vendors will be allowed to submit their proposals</p>
        <input type="hidden" name="action" value="openRFP">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> <?php _e('Continue'); ?></button>
    </div>
