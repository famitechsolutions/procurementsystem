<?php
$departmentsList = DB::getInstance()->querySample("SELECT * FROM department WHERE status=1 AND department_id!='$dept' ORDER BY department_name");
$id = $_GET['id'];
$request = DB::getInstance()->querySample("SELECT r.*,d.department_name, CONCAT(fname,' ',lname) user FROM requisition r,department d,user u WHERE r.user_id=u.user_id AND r.department_id=d.department_id AND r.id='$id'")[0];
$itemsList = DB::getInstance()->querySample("SELECT * FROM requisition_items WHERE requisition_id='$id' AND status=1");
$projects = DB::getInstance()->querySample("SELECT p.*,CONCAT(p.requisition_tag,(COUNT(r.project_id)+1))next_requisition_number FROM projects p LEFT JOIN requisition r ON(r.project_id=p.id) WHERE p.status=1 GROUP BY p.id");
?>

<div class="modal-header">
    <h4 class="modal-title">Department <?php echo $request->category ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-sm-2">
                <label>Date</label>
                <input type="date" class="form-control" name="date_submitted" max="<?php echo $date_today ?>" value="<?php echo $request->date_submitted ?>" required>
            </div>
            <div class="col-sm-3">
                <label for="location"><?php _e('Dept'); ?> *</label>
                <select class="form-control select2" name="department_id" style="width: 100%;" required>
                    <option value="">Choose</option>
                    <?php
                    foreach ($departmentsList as $department) {
                        $selected = ($request->department_id == $department->department_id) ? ' selected' : '';
                        echo '<option value="' . $department->department_id . '" ' . $selected . '>' . $department->department_name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="project"><?php _e('Project'); ?></label>
                <select class="form-control select2-" name="project_id" style="width: 100%;" onchange="getRequisitionNumber(this)">
                    <option value="" requisition_number="">Choose</option>
                    <?php
                    foreach ($projects as $project) {
                        $selected = ($request->project_id == $project->id) ? ' selected' : '';
                        echo '<option value="' . $project->id . '" ' . $selected . ' requisition_number="' . $project->next_requisition_number . '">' . $project->name . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-2">
                <label>Ref. No. </label>
                <input class="form-control" name="reference_number" value="<?php echo $request->reference_number ?>">
            </div>
            <div class="col-sm-2">
                <label>Requisition No. </label>
                <input class="form-control" name="requisition_number" id="requisition_number" <?php echo $request->project_id ? ' readonly' : '' ?> value="<?php echo $request->requisition_number ?>">
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
                    <?php echo ($request->category == 'requisition') ? '<th>Payee</th>' : ''; ?>
                    <th><button type="button" class="btn btn-primary btn-xs" onclick="add_element('<?php echo $request->category ?>');"><i class="fa fa-plus-circle"></i></button></th>
                </tr>
            </thead>
            <tbody id="<?php echo $request->category ?>_div">
                <?php
                foreach ($itemsList as $i => $item) {
                ?>
                    <tr>
                        <td><textarea name="name[]" class="form-control"><?php echo $item->name ?></textarea></td>
                        <td><input type="number" id="quantity_<?php echo $item->id ?>" onkeyup="calculateTotal(<?php echo $item->id ?>);" min="0" step="0.01" class="form-control" name="quantity[]" value="<?php echo $item->quantity_requested ?>" required></td>
                        <td><input type="text" name="unit_measure[]" class="form-control" value="<?php echo $item->unit_measure ?>"></td>
                        <td><input type="number" id="unit_price_<?php echo $item->id ?>" onkeyup="calculateTotal(<?php echo $item->id ?>);" min="0" class="form-control" name="unit_cost[]" value="<?php echo $item->unit_price ?>"></td>
                        <td><input type="text" id="total_cost_<?php echo $item->id ?>" class="form-control" name="total_cost[]" readonly value="<?php echo $item->quantity_requested * $item->unit_price ?>"></td>
                        <?php if ($request->category == 'requisition') { ?>
                            <td>
                                <select class="form-control" name="payee[]">
                                    <option value="">Select</option>
                                    <?php foreach ($ALLOWED_PAYEES_LIST as $payee) {
                                        $selected = $item->payee == $payee ? ' selected' : '';
                                        echo '<option value="' . $payee . '"' . $selected . '>' . $payee . '</option>';
                                    } ?>
                                </select>
                            </td>
                        <?php } ?>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>TOTAL:</td>
                    <td><input type="text" readonly class="form-control" id="general_total" value="<?php echo $request->amount_requested ?>" name="amount_requested"></td>
                    <?php echo ($request->category == 'requisition') ? '<td></td>' : ''; ?>
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