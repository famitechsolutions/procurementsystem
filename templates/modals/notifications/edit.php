<?php
$code = $_GET['code'];
$template = DB::getInstance()->getRow("notificationtemplate", $code, "*", "code");
?>

<div class="modal-header">
    <h4 class="modal-title"><?php _e('Edit ' . $template->name . ' Template'); ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="subject"><?php _e('Subject'); ?> *</label>
        <input type="hidden" name="name" value="<?php echo $template->name; ?>">
        <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $template->subject; ?>" required>
    </div>

    <?php if ($code == 'sms') { ?>
        <div class="form-group">
            <label for="sms"><?php _e('SMS'); ?> *</label>
            <input type="text" class="form-control" id="sms" name="sms" value="<?php echo $template->sms; ?>" required>
        </div>
    <?php } ?>

    <div class="form-group">
        <label for="message" class="control-label"><?php _e('Message'); ?></label>
        <textarea class="form-control summernote" id="message" name="message"><?php echo $template->message; ?></textarea>
    </div>

    <p><?php echo $template->info; ?></p>

    <?php if ($code != 'sms') { ?>
        <input type="hidden" name="sms" value="">
    <?php } ?>

    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <input type="hidden" name="action" value="editNotificationTemplate">
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
