<?php
$id = $_GET['id'];
$request = DB::getInstance()->querySample("SELECT r.*,d.department_name, CONCAT(fname,' ',lname) user FROM requisition r,department d,user u WHERE r.user_id=u.user_id AND r.department_id=d.department_id AND r.id='$id'")[0];
$permissionCode=$request->requisition_status=='Requested'?'financialRequisitionApproval':'finalRequisitionApproval';
$approvers = DB::getInstance()->querySample("SELECT u.user_id,CONCAT(fname,' ',lname) name FROM user u,user_permission up,permissions p WHERE p.id=up.permission_id AND up.role_id=u.role_id AND p.code='$permissionCode' AND u.user_id!='$user_id' AND u.status=1 AND u.is_approved=1 AND u.is_verified=1 GROUP BY u.user_id");
$itemsList = DB::getInstance()->querySample("SELECT * FROM requisition_items WHERE requisition_id='$id' AND status=1");
?>

<div class="modal-header">
    <h4 class="modal-title">Department Requisition - <?php echo $request->requisition_number ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form action="" method="POST">
    <div class="modal-body">
        <div class="form-group row">
            <div class="col-sm-2">
                <label>Date</label>
                <input type="date" class="form-control" value="<?php echo $request->date_submitted ?>" readonly>
            </div>
            <div class="col-sm-2">
                <label for="location"><?php _e('Location'); ?></label>
                <input class="form-control" readonly value="<?php echo $request->department_name ?>">
            </div>
            <div class="col-sm-1">
                <label>Ref No. </label>
                <input class="form-control" readonly value="<?php echo $request->reference_number ?>">
            </div>
            <div class="col-sm-1">
                <label>Req No. </label>
                <input class="form-control" readonly value="<?php echo $request->requisition_number ?>">
            </div>
            <div class="col-sm-2">
                <label>User</label>
                <input class="form-control" value="<?php echo $request->user ?>" readonly>
            </div>
            <?php if ($request->requisition_status == 'Requested') { ?>
                <div class="col-sm-2">
                    <label>Financial Approver</label>
                    <select class="form-control select2" name="financial_approver" style="width:100%" required>
                        <option value="">Choose</option>
                        <?php
                        foreach ($approvers as $admin) {
                            $selected = ($admin->user_id == $user_id) ? " selected" : "";
                            echo '<option value="' . $admin->user_id . '" ' . $selected . '>' . $admin->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php }else if ($request->requisition_status == 'Directly Approved') { ?>
                <div class="col-sm-2">
                    <label>Final Approver</label>
                    <select class="form-control select2" name="final_approver" style="width:100%" required>
                        <option value="">Choose</option>
                        <?php
                        foreach ($approvers as $admin) {
                            $selected = ($admin->user_id == $user_id) ? " selected" : "";
                            echo '<option value="' . $admin->user_id . '" ' . $selected . '>' . $admin->name . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item No.</th>
                    <th>Goods Description</th>
                    <th>Unit of Measure</th>
                    <?php echo $request->requisition_status == 'Requested' ? '' : '<th>Qty Directly Approved</th>' ?>
                    <th>Quantity Approved</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody id="requisition_div">
                <?php
                foreach ($itemsList as $i => $item) {
                ?>
                    <tr>
                        <td><?php echo ($i + 1) ?></td>
                        <td><?php echo $item->name ?><input type="hidden" name="item_id[]" value="<?php echo $item->id ?>"></td>
                        <td><?php echo $item->unit_measure ?></td>
                        <?php echo $request->requisition_status == 'Requested' ? '' : '<td>'.$item->quantity_directly_approved.'</td>' ?>
                        <td><input type="number" id="quantity_<?php echo $item->id ?>" oninput="calculateTotal(<?php echo $item->id ?>);" min="0" step="0.01" class="form-control" name="quantity[]" value="<?php echo $item->quantity_requested ?>" required></td>
                        <td><input type="number" id="unit_price_<?php echo $item->id ?>" oninput="calculateTotal(<?php echo $item->id ?>);" min="0" class="form-control" name="unit_cost[]" value="<?php echo $item->unit_price ?>"></td>
                        <td><input type="text" id="total_cost_<?php echo $item->id ?>" class="form-control" name="total_cost[]" readonly value="<?php echo $item->quantity_requested * $item->unit_price ?>"></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $request->requisition_status == 'Requested' ? 4 : 5 ?>"></td>
                    <td>TOTAL:</td>
                    <td><input type="text" readonly class="form-control" id="general_total" value="<?php echo $request->amount_requested ?>" name="amount_approved"></td>
                </tr>
            </tfoot>
        </table>
        <div class="form-group">
            <label>Signature</label>
            <input class="form-control" name="signature" required>
            <label>Any Comment?</label>
            <textarea class="form-control" name="comment"></textarea>
        </div>
        <input type="hidden" name="action" value="approveRequisition">
        <input type="hidden" name="status" value="<?php echo $request->requisition_status == 'Requested' ? 'Directly Approved' : ($request->requisition_status =='Directly Approved'?'Financially Approved':'Approved') ?>">
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