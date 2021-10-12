<?php
$id = $_GET['id'];
$area = DB::getInstance()->getRow("onboarding_areas", $id, "*", "id");
?>

<div class="modal-header">
    <h4 class="modal-title"><?php _e($id?'Edit  Area':'New Area'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">

    <div class="form-group">
        <label for="message" class="control-label"><?php _e('Details'); ?></label>
        <textarea class="form-control summernote-" name="name"><?php echo $area->name; ?></textarea>
    </div>

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="action" value="editOnboardingArea">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
</div>

<script type="text/javascript">
    $(function () {
        $(".select2").select2();
    });

    //$(document).ready(function () {
    $('.summernote').summernote({height: 300});
    //});
</script>
