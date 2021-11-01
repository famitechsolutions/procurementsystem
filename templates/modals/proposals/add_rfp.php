<?php
$id = $_GET['id'];
$itemsList = DB::getInstance()->querySample("SELECT i.* FROM item i WHERE i.status=1");
$request = DB::getInstance()->querySample("SELECT r.* FROM requisition r WHERE r.id='$id'")[0];
?>

<div class="modal-header">
    <h4 class="modal-title">Request for proposal</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <div class="col">
                        <label>Open Date </label>
                        <input type="date" class="form-control" name="rfp[open_date]" min="<?php echo $date_today ?>" required>
                    </div>
                    <div class="col">
                        <label>Close Date </label>
                        <input type="date" class="form-control" name="rfp[close_date]" min="<?php echo $date_today ?>" required>
                    </div>
                </div>
                <label>Purpose statement</label>
                <textarea class="form-control summernote" name="rfp[purpose_statement]"></textarea>
                <div class="">
                    <label>Expected attachments [<small>Add title names comma separated</small>]</label>
                    <textarea class="form-control" name="rfp[expected_attachments]"></textarea>
                </div>
                <div class="">
                    <label>Expected question [<small>Add title names comma separated</small>]</label>
                    <textarea class="form-control" name="rfp[expected_response]"></textarea>
                </div>
                <div class="">
                    <label>Terms of Payment</label>
                    <textarea class="form-control summernote" name="rfp[payment_terms]"></textarea>
                </div>
            </div>
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Goods Description [Check to include]</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="requisition_div">
                        <?php foreach ($itemsList as $i => $item) {
                        ?>
                            <tr>
                                <td><label><input type="checkbox" name="item_id[]" class="lpo-item" data-amount="<?php echo $item->quantity * $item->unit_price ?>" value="<?php echo $item->id ?>">     <?php echo $item->name ?></label></td>
                                <td><textarea type="text" class="form-control" name="description[<?php echo $item->id?>]"></textarea></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>


        <input type="hidden" name="action" value="addRFP">
        <input type="hidden" name="requisition_id" value="<?php echo $id ?>">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(function() {
    $('.summernote').summernote({height: 150,tabsize:1,toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
        ]});
    });
</script>