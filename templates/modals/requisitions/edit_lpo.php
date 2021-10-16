<?php
$id = $_GET['id'];
$lpo_id=$_GET['lpo_id'];
$itemsList = DB::getInstance()->querySample("SELECT * FROM requisition_item ri WHERE ri.requisition_id='$id' AND ri.purchase_order_id='$lpo_id' AND ri.status=1");
$lpo= DB::getInstance()->getRow("purchase_order",$lpo_id,"*","id");
?>

<div class="modal-header">
    <h4 class="modal-title">LPO</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-md-2">
                <label>Vendors Name & Address. </label>
                <select class="form-control" name="supplier" value="<?php echo $lpo->supplier?>">
                    <?php
                    $fetchSuppliers = DB::getInstance()->querySample(""
                            . "select * from contract_application ca, user u where ca.user_id=u.id AND ca.application_status='Approved' AND ca.status=1 AND u.is_verified=1 ");
                    echo '<option value="" >Choose</option>';
                        foreach ($fetchSuppliers as $i => $item) {
                        ?>
                    <option value="<?php echo $item->user_id ?>"><?php echo $item->fname.'-'.$item->lname ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Serial No. </label>
                <input class="form-control" name="order_number" value="<?php echo $lpo->order_number?>">
            </div>
            <div class="col-md-2">
                <label>Delivery Date</label>
                <input class="form-control" type="date" name="delivery_date" value="<?php echo $lpo->delivery_date?>">
            </div>
            <div class="col-md-2">
                <label>Order Date</label>
                <input type="date" class="form-control" name="order_date" value="<?php echo $lpo->date?>">
            </div>
            <div class="col-md-2">
                <label>Payment Mode</label>
                <input class="form-control" name="payment_mode" value="<?php echo $lpo->payment_mode?>">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Goods Description [Check to include]</th>
                    <th>Unit of Measure</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody  id="requisition_div">
                <?php $percentage_tax= getConfigValue("percentage_tax");
                $lpoValue=0;
//                echo "SELECT * FROM requisition_item WHERE requisition_id='$id' AND purchase_order_id='$lpo_id' AND status=1";
//                var_dump($itemsList);
                foreach ($itemsList AS $i => $item) {
                    $lpoValue+=$item->quantity * $item->unit_price;
                    ?>
                    <tr>
                        <td><label><?php if($item->lpo_id==''){?><input type="checkbox" name="item_id[]" class="lpo-item" data-amount="<?php echo $item->quantity_requested * $item->unit_price ?>" value="<?php echo $item->id?>" onchange="calculateLPOAsmount('.lpo-item','#general_total')"> <?php }echo DB::getInstance()->getName('item', $item->item_id, 'name', 'id') ?></label></td>
                        <td><?php echo $item->unit_measure ?></td>
                        <td><?php echo $item->quantity ?></td>
                        <td><?php echo $item->unit_price ?></td>
                        <td><?php echo $item->quantity * $item->unit_price ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr><td colspan="3"></td><td>TOTAL:</td><td><input type="text" readonly class="form-control" id="general_total" name="lpo_amount" value="<?php echo $lpoValue?>"></td></tr>
            </tfoot>
        </table>
        <input type="hidden" name="action" value="editLPO">
        <input type="hidden" name="requisition_id" value="<?php echo $id ?>">
        <input type="hidden" name="lpo_id" value="<?php echo $lpo_id ?>">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(function () {
        $(".select2").select2();
    });
</script>
