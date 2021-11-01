<?php
$departmentsList = DB::getInstance()->querySample("SELECT * FROM department WHERE status=1 ORDER BY name");
$itemsList = DB::getInstance()->querySample("SELECT * FROM item WHERE status=1 AND unit_price IS NOT NULL ORDER BY name");
$itemsString='<option value="">Choose</option>';
foreach($itemsList AS $item){
    $itemsString.='<option value="'.$item->id.'" data-price="'.$item->unit_price.'" data-measure="'.$item->unit_measure.'">'.$item->name.': ('.$item->unit_measure.')</option>';
}
?>

<div class="modal-header">
    <h4 class="modal-title">Department Requisition</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col">
                <label>Date</label>
                <input type="date" class="form-control" name="date_submitted" max="<?php echo $date_today ?>" required>
            </div>
            <div class="col">
                <label for="location"><?php _e('Location/Dept'); ?> *</label>
                <select class="form-control select2" name="department_id" style="width: 100%;" required>
                    <option value="">Choose</option>
                    <?php
                    foreach ($departmentsList as $department) {
                        echo '<option value="' . $department->id . '">' . $department->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label>Requisition No. </label>
                <input class="form-control" id="requisition_number" value="<?php echo date('YmdHis')?>" name="requisition_number">
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Goods Description</th>
                    <th>Unit of Measure</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th><button type="button" class="btn btn-primary btn-xs" onclick="add_element('requisition');"><i class="fa fa-plus-circle"></i></button></th>
                </tr>
            </thead>
            <tbody id="requisition_div">
                <tr>
                    <td>
                        <select id="item_1" class="form-control" name="item[]" onchange="calculateTotal(1);" required>
                            <?php echo $itemsString?>
                        </select>
                    </td>
                    <td><input id="measure_1" class="form-control" readonly></td>
                    <td><input id="unit_price_1" readonly class="form-control"></td>
                    <td><input type="number" id="quantity_1" oninput="calculateTotal(1);" min="0" step="0.01" class="form-control" name="quantity[]" required></td>
                    <td><input type="text" id="total_cost_1" class="form-control" name="total_cost[]" readonly></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>TOTAL:</td>
                    <td><input type="text" readonly class="form-control" id="general_total" value="" name="amount_requested"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <input type="hidden" name="action" value="addRequisition">
        <input type="hidden" name="category" value="requisition">
        <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(function() {
        $(".select2").select2();
    });
</script>