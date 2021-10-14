<?php
$departmentsList = DB::getInstance()->querySample("SELECT * FROM department WHERE status=1 ORDER BY name");
$id = $_GET['id'];
$request = DB::getInstance()->querySample("SELECT r.* FROM requisition r WHERE r.id='$id'")[0];
$requisitionItems = DB::getInstance()->querySample("SELECT * FROM requisition_item WHERE requisition_id='$id' AND status=1");
$itemsList = DB::getInstance()->querySample("SELECT * FROM item WHERE status=1 ORDER BY name");
?>

<div class="modal-header">
    <h4 class="modal-title">Department requisition</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col">
                <label>Date</label>
                <input type="date" class="form-control" name="date" max="<?php echo $date_today ?>" value="<?php echo $request->date ?>" required>
            </div>
            <div class="col">
                <label for="location"><?php _e('Dept'); ?> *</label>
                <select class="form-control select2" name="department_id" style="width: 100%;" required>
                    <option value="">Choose</option>
                    <?php
                    foreach ($departmentsList as $department) {
                        $selected = ($request->department_id == $department->id) ? ' selected' : '';
                        echo '<option value="' . $department->id . '" ' . $selected . '>' . $department->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <label>Requisition No. </label>
                <input class="form-control" name="requisition_number" value="<?php echo $request->requisition_number ?>" required>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Goods Description</th>
                    <th>Quantity</th>
                    <th>Unit of Measure</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th><button type="button" class="btn btn-primary btn-xs" onclick="add_element('requisition');"><i class="fa fa-plus-circle"></i></button></th>
                </tr>
            </thead>
            <tbody id="requisition_div">
                <?php
                foreach ($requisitionItems as $i => $requisitionItem) {
                ?>
                    <tr>
                        <td><select name="item[]" class="form-control" required>
                            <?php
                            foreach($itemsList AS $item){
                                $selected=$requisitionItem->item_id==$item->id?' selected':'';
                                echo '<option value="'.$item->id.'" '.$selected.'>'.$item->name.'</option>';
                            }
                            ?>
                        </select></td>
                        <td><input type="number" id="quantity_<?php echo $requisitionItem->id ?>" oninput="calculateTotal(<?php echo $requisitionItem->id ?>);" min="0" step="0.01" class="form-control" name="quantity[]" value="<?php echo $requisitionItem->quantity ?>" required></td>
                        <td><input type="text" name="unit_measure[]" class="form-control" value="<?php echo $requisitionItem->unit_measure ?>"></td>
                        <td><input type="number" id="unit_price_<?php echo $requisitionItem->id ?>" oninput="calculateTotal(<?php echo $requisitionItem->id ?>);" min="0" class="form-control" name="unit_cost[]" value="<?php echo $requisitionItem->unit_price ?>"></td>
                        <td><input type="text" id="total_cost_<?php echo $requisitionItem->id ?>" class="form-control" name="total_cost[]" readonly value="<?php echo $requisitionItem->quantity * $requisitionItem->unit_price ?>"></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>TOTAL:</td>
                    <td><input type="text" readonly class="form-control" id="general_total" value="<?php echo $request->amount_requested ?>" name="amount_requested"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <input type="hidden" name="action" value="editRequisition">
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
        $(".select2").select2();
    });
</script>