<div class="modal-header">
    <h4 class="modal-title"><?php _e('Upload File'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">

    <div class="form-group">
        <label for="name"><?php _e('Name'); ?></label>
        <input type="text" class="form-control" id="name" name="name" value="">
    </div>

    <div class="form-group">
        <label for="file"><?php _e('File'); ?></label>
        <input type="file" id="file" name="file[]" multiple required>
        <p class="help-block"><?php _e('Max upload file size on your server is'); ?> <?php echo ini_get('upload_max_filesize'); ?>.</p>
    </div>

    <input type="hidden" name="action" value="uploadFile">
    <input type="hidden" name="requisition_id" value="<?php if (isset($_GET['requisition_id'])) echo $_GET['requisition_id']; ?>">
    <input type="hidden" name="lpo_id" value="<?php if (isset($_GET['lpo_id'])) echo $_GET['lpo_id']; ?>">
    <input type="hidden" name="rfp_id" value="<?php if (isset($_GET['rfp_id'])) echo $_GET['rfp_id']; ?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> <?php _e('Upload'); ?></button>
</div>

