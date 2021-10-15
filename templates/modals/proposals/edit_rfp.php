<?php
$id = $_GET['id'];
$rfpItems = DB::getInstance()->querySample("SELECT ri.*,i.* FROM item i LEFT JOIN rfp_item ri ON(ri.item_id=i.id AND ri.rfp_id='$id') WHERE i.status=1");
$rfp = DB::getInstance()->querySample("SELECT r.* FROM rfp r WHERE r.id='$id'")[0];
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
                    <div class="col-sm-4">
                        <label>Open Date </label>
                        <input type="date" class="form-control" name="rfp[open_date]" value="<?php echo $rfp->open_date?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label>Close Date </label>
                        <input type="date" class="form-control" name="rfp[close_date]" value="<?php echo $rfp->close_date?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label>Desired delivery date </label>
                        <input type="date" class="form-control" name="rfp[expected_delivery_date]" value="<?php echo $rfp->expected_delivery_date?>" required>
                    </div>
                </div>
                <label>Purpose statement</label>
                <textarea class="form-control summernote" name="rfp[purpose_statement]"><?php echo $rfp->purpose_statement?></textarea>
                <div class="">
                    <label>Expected attachments [<small>Add title names comma separated</small>]</label>
                    <textarea class="form-control" name="rfp[expected_attachments]"><?php echo $rfp->expected_attachments?></textarea>
                </div>
                <div class="">
                    <label>Expected question response [<small>Add title names comma separated</small>]</label>
                    <textarea class="form-control" name="rfp[expected_response]"><?php echo $rfp->expected_response?></textarea>
                </div>
                <div class="">
                    <label>Terms of Payment</label>
                    <textarea class="form-control summernote" name="rfp[payment_terms]"><?php echo $rfp->payment_terms?></textarea>
                </div>
            </div>
            <div class="col">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Goods Description [Check to include]</th>
                            <th>Description</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="requisition_div">
                        <?php foreach ($rfpItems as $i => $item) {
                        ?>
                            <tr>
                                <td><label><input type="checkbox" name="item_id[]" <?php echo $item->item_id?'checked':''?> value="<?php echo $item->id ?>">     <?php echo $item->name ?></label></td>
                                <td><textarea type="text" class="form-control" name="description[<?php echo $item->id?>]"><?php echo $item->description?></textarea></td>
                                <td><input type="number" min="0" step="any" class="form-control" name="quantity[<?php echo $item->id?>]" value="<?php echo $item->quantity?>"></td>
                            </tr>
                        <?php }
                        foreach ($itemsList as $i => $item) {
                            ?>
                                <tr>
                                    <td><label><input type="checkbox" name="item_id[]" value="<?php echo $item->id ?>">     <?php echo $item->name ?></label></td>
                                    <td><textarea type="text" class="form-control" name="description[<?php echo $item->id?>]"></textarea></td>
                                    <td><input type="number" min="0" step="any" class="form-control" name="quantity[<?php echo $item->id?>]"></td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>


        <input type="hidden" name="action" value="<?php echo $id?'editRFP':'addRFP'?>">
        <input type="hidden" name="requisition_id" value="<?php echo $requisition_id ?>">
        <input type="hidden" name="id" value="<?php echo $id ?>">
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