<?php 
$edit_supplier=$_GET['edit_supplier'];
$fetchSupplier = DB::getInstance()->getRow("suppliers", $edit_supplier, "*", 'id');
?>
<div class="modal-header">
    <h4 class="modal-title"> <?php $add_edit=($edit_supplier!="")?"Edit":"Add"; echo $add_edit.' Supplier '.$fetchSupplier->name?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <form action=""> 
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Names</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="name" value="<?php echo $fetchSupplier->name; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Address</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="address" value="<?php echo $fetchSupplier->address; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Contact Number</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="contact_number" value="<?php echo $fetchSupplier->contactnumber; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Phone</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="phone" value="<?php echo $fetchSupplier->phone; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Email</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="email" value="<?php echo $fetchSupplier->email; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Web</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <input type="text" class="form-control" name="web" value="<?php echo $fetchSupplier->web; ?>">
                    <span class="help-block"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="input-mask-title">
                    <label>Notes</label>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="input-mark-inner mg-b-22">
                    <textarea type="text" rows="6" class="form-control" name="notes" ><?php echo $fetchSupplier->notes; ?></textarea>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>
    </form>       
    <input type="hidden" name="action" value="saveSuppliers">
    <input type="hidden" name="edit_supplier" value="<?php echo $edit_supplier;?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save'); ?></button>
</div>