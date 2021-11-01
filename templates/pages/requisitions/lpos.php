<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/header.php'; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php require_once 'includes/header_menu.php'; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php require_once 'includes/side_menu.php'; ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-title">
                                    LPOs
                                </div>
                                <div class="card-body">
                                    <div class="panel-group accordion accordion-solid-header" id="accordion3" role="tablist">
                                        <?php
                                        $filterCondition = in_array("viewRequisition", $user_permissions) ? "" : " AND l.supplier='$user_id'";
                                        $lposList = DB::getInstance()->querySample("SELECT l.*, CONCAT(u.fname,' ',u.lname)supplier FROM purchase_order l LEFT JOIN user u ON(u.id=l.supplier) WHERE l.status=1 $filterCondition");
                                        if (empty($lposList)) {
                                            echo '<div class="alert alert-info m-3">No LPOs generated</div>';
                                        }
                                        $expanded = count($lposList) == 1 ? true : false;
                                        foreach ($lposList as $lpo) {
                                            $request = DB::getInstance()->querySample("SELECT r.*,d.name department_name, CONCAT(fname,' ',lname) prepared_by,(SELECT CONCAT(fname,' ',lname) name FROM user WHERE id=r.approval_by LIMIT 1) approver FROM requisition r,department d,user u WHERE r.user_id=u.id AND r.department_id=d.id AND r.id='$lpo->requisition_id'")[0];
                                            $files = DB::getInstance()->querySample("SELECT * FROM attachment WHERE purchase_order_id='$lpo->id' AND status=1");
                                            $itemsList = DB::getInstance()->querySample("SELECT * FROM requisition_item ri,item i WHERE ri.item_id=i.id AND ri.purchase_order_id='$lpo->id' AND ri.status=1");
                                        ?>
                                            <!--Start Loop-->
                                            <div class="card" role="tab" id="lpo-item-<?php echo $lpo->id ?>">
                                                <div class="card-header">
                                                    <h6 class="mb-0">
                                                        <a class="accordion-toggle accordion-toggle-styled <?php echo $expanded ? '' : 'collapsed' ?>" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true' : 'false' ?>" data-parent="#accordion3" href="#collapse-<?php echo $lpo->id ?>"><i class='fa fa-check'></i> Serial No. <?php echo $lpo->order_number; ?> </a>
                                                    </h6>
                                                </div>
                                                <div id="collapse-<?php echo $lpo->id ?>" class=" <?php echo $expanded ? 'show' : 'collapse' ?>" aria-labelledby="lpo-item-<?php echo $lpo->id ?>">


                                                    <div class="nav-tabs custom pt-1">
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item"><a class="nav-link active" href="#lpo-summary-<?php echo $lpo->id ?>-tab" data-toggle="tab"><?php _e('Summary'); ?></a></li>
                                                            <li class="nav-item"><a class="nav-link" href="#lpo-files-<?php echo $lpo->id ?>-tab" data-toggle="tab"><?php _e('Files'); ?></a></li>

                                                            <div class="btn-group- pull-right" style="padding:6px;">
                                                                <?php if ($lpo->lpo_status != 'Complete') {
                                                                    if (in_array("editLPO", $user_permissions)) { ?><a data-toggle='tooltip' title='<?php _e('Edit LPO'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=requisitions/edit_lpo&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=lpos-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id; ?>&tab=lpo-tab", "large");return false'>Edit</a><?php } ?>
                                                                    <?php if ($request->requisition_status == 'Approved') { ?><a data-toggle='tooltip' title='<?php _e('Upload File'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=files/upload&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=lpos-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id . '&project_id=' . $request->project_id . '&client_id=' . $request->client_id; ?>&tab=lpos-tab");return false'>Upload File</a><?php } ?>
                                                                    <?php if (in_array("deleteLPO", $user_permissions)) { ?><a data-toggle='tooltip' title='<?php _e('Delete LPO'); ?>' class="btn btn-default" onClick='showModal("index.php?modal=requisitions/delete_lpo&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=lpos-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id; ?>&tab=lpo-tab");return false'><i class='fa fa-trash text-danger'></i></a>
                                                                        
                                                                <?php }
                                                                } ?>
                                                                <a data-toggle='tooltip' onclick="PrintSection('lpoSection<?php echo $lpo->id ?>', '21.0', '29.7')" title='<?php _e('Print LPO'); ?>' class="btn btn-default btn-xs">Print</a>
                                                            </div>

                                                        </ul>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="lpo-summary-<?php echo $lpo->id ?>-tab">
                                                                <div id="lpoSection<?php echo $lpo->id ?>" class="">
                                                                    <h2>
                                                                        <img class="pull-right-" style="max-height: 40px;" src="<?php echo COMPANY_LOGO ?>">
                                                                    </h2><br />
                                                                    <p><?php echo COMPANY_LOCATION ?></p>
                                                                    <p><?php echo $SITE_DESCRIPTION ?></p>
                                                                    <p><strong><u>Local Purchase Order</u></strong></p>
                                                                    <table class="table-" style="width: 100%;">
                                                                        <tr>
                                                                            <td><strong>Serial No. </strong></td>
                                                                            <td><?php echo $lpo->order_number ?></td>
                                                                            <td><strong>Delivery Date: </strong></td>
                                                                            <td><?php echo $lpo->delivery_date ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Vendors Name & Address</strong></td>
                                                                            <td><?php echo $lpo->supplier ?></td>
                                                                            <td><strong>Terms of Payment: </strong></td>
                                                                            <td><?php echo $lpo->payment_mode ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Order Date: </strong></td>
                                                                            <td><?php echo $lpo->date ?></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Item No.</th>
                                                                                <th>Specification</th>
                                                                                <th>Unit of Measure</th>
                                                                                <th>Quantity</th>
                                                                                <th>Unit Price</th>
                                                                                <th>Total Value</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $lpoValue = 0 + $lpo->tax;
                                                                            foreach ($itemsList as $i => $item) {
                                                                                $lpoValue += ($item->quantity * $item->unit_price);
                                                                            ?>
                                                                                <tr>
                                                                                    <td><?php echo ($i + 1) ?></td>
                                                                                    <td><?php echo $item->name ?></td>
                                                                                    <td><?php echo $item->unit_measure ?></td>
                                                                                    <td><?php echo $item->quantity ?></td>
                                                                                    <td><?php echo $item->unit_price ?></td>
                                                                                    <td><?php echo $item->quantity * $item->unit_price ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <th colspan="5">Total Value</th>
                                                                                <th><?php echo formatMoney($lpoValue) ?></th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <td colspan="3"><strong>Amount in Words: </strong><u><?php echo NumberToWord::getInstance()->toText($lpoValue) . ' only' ?></u></td>
                                                                            <td><strong>Currency </strong><?php echo getConfigValue("currency_symbol") ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Prepared by: </td>
                                                                            <td><strong><?php echo $lpo->user ?></strong></td>
                                                                            <td>Date: </td>
                                                                            <td><strong><?php echo english_date($lpo->time_created); ?></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Procurement Manager: </td>
                                                                            <td><strong><?php echo $request->prepared_by ?></strong></td>
                                                                            <td>Date: </td>
                                                                            <td><strong><?php echo english_date($request->date_submitted); ?></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Chief Executive Officer: </td>
                                                                            <td><strong><?php echo ($request->approver) ? $request->approver : '________________' ?></strong></td>
                                                                            <td>Date: </td>
                                                                            <td><strong><?php echo ($request->time_approved) ? substr($request->time_approved, 0, 10) : '________________' ?></strong></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="lpo-files-<?php echo $lpo->id ?>-tab">
                                                                <?php if (empty($files)) { ?>
                                                                    <div class="alert alert-info m-3">
                                                                        <i class="icon fa fa-info"></i> <?php _e('No files have been uploaded yet!'); ?>
                                                                    </div>
                                                                <?php } ?>

                                                                <ul class="todo-list list-inline" id="fileslist">
                                                                    <?php foreach ($files as $file) { ?>
                                                                        <li id="" style="width:28%;margin:10px;padding:12px;">
                                                                            <div class="row">
                                                                                <div class="col-sm-1" style="vertical-align:middle"><i class="fa fa-<?php echo File::icon($file->url); ?>"></i></div>
                                                                                <div class="col-sm-10">
                                                                                    <?php echo $file->title . "<br><small>" . $file->url . "</small>"; ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="pull-right">
                                                                                <a href="uploads/files/<?php echo $file->url; ?>" download class='btn-right text-dark'><i class='fa fa-download'></i></a>&nbsp;
                                                                                <?php if (in_array("deleteFile", $user_permissions)) { ?><a href="index.php?page=<?php echo $_GET['page'] . '&id=' . $_GET['id'] . '&action=deleteFile&file_id=' . $crypt->encode($file->id) ?>" class='btn-right text-danger'><i class='fa fa-trash-o'></i></a><?php } ?>
                                                                            </div>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>

                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                            </div>
                                        <?php } ?>
                                        <!--End loop-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <?php require_once 'includes/footer_menu.php'; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>