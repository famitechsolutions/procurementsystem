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
                                <?php
                                $id = $crypt->decode($_GET['id']);
                                $rfp = DB::getInstance()->querySample("SELECT * FROM rfp WHERE id='$id' AND status=1")[0];
                                if ($rfp) {
                                    $files = DB::getInstance()->querySample("SELECT * FROM attachment WHERE rfp_id='$id' AND status=1");
                                    $itemsList = DB::getInstance()->querySample("SELECT * FROM rfp_item ri,item i WHERE i.id=ri.item_id AND ri.rfp_id='$id' AND ri.status=1");
                                ?>
                                    <div class="card-title">
                                        Request for proposal #<?php echo $id ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="nav-tabs-custom">
                                            <?php
                                            $tab = (isset($_GET['tab']) && $_GET['tab'] != "") ? $_GET['tab'] : 'summary-tab';
                                            $summary_tab_active = ($tab == "summary-tab") ? 'active' : '';
                                            $proposals_tab_active = ($tab == "proposals-tab") ? 'active' : '';
                                            $attachments_tab_active = ($tab == "attachments-tab") ? 'active' : '';
                                            ?>
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item"><a class="nav-link <?php echo $summary_tab_active ?>" href="#summary-tab" data-toggle="tab"><?php _e('Summary'); ?></a></li>
                                                <li class="nav-item"><a class="nav-link <?php echo $proposals_tab_active ?>" href="#proposals-tab" data-toggle="tab"><?php _e('Proposals'); ?></a></li>
                                                <li class="nav-item"><a class="nav-link <?php echo $attachments_tab_active ?>" href="#attachments-tab" data-toggle="tab"><?php _e('Attachments'); ?></a></li>

                                                <div class="btn-group- pull-right" style="padding:6px;">
                                                    <?php if (in_array("openRFP", $user_permissions) && $rfp->rfp_status == 'Pending') { ?>
                                                        <a data-toggle='tooltip' title='<?php _e('Open '); ?>' class="btn btn-primary btn-xs " onClick='showModal("index.php?modal=requisitions/open_rfp&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=summary-tab') . '&id=' . $rfp->id; ?>&tab=summary-tab");return false'>Make Open</a>
                                                    <?php } ?>
                                                    <?php if (in_array("editRFP", $user_permissions) && ($rfp->rfp_status == 'Pending')) { ?><a data-toggle='tooltip' title='<?php _e('Edit '); ?>' class="btn btn-default btn-xs " href="#" onClick='showModal("index.php?modal=requisitions/edit_rfp&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=summary-tab') . '&id=' . $rfp->id; ?>&tab=summary-tab", "large");return false'>Edit</a>
                                                        <a data-toggle='tooltip' title='<?php _e('Upload Attachment'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=files/upload&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=attachments-tab') . '&rfp_id=' . $rfp->id ?>&tab=attachments-tab");return false'>Upload Attachment</a><?php } ?>
                                                    <?php if (in_array("deleteRFP", $user_permissions) && $rfp->rfp == 'Pending') { ?><a data-toggle='tooltip' title='<?php _e('Delete '); ?>' class="btn btn-danger btn-xs " href="#" onClick='showModal("index.php?modal=requisitions/delete<?php echo '&reroute=' . $crypt->encode('page=' . $crypt->encode('requisition')) . '&id=' . $rfp->id; ?>");return false'><i class="fa fa-trash"></i></a><?php } ?>
                                                </div>
                                            </ul>
                                            <div class="tab-content p-2">
                                                <div class="tab-pane  <?php echo $summary_tab_active ?>" id="summary-tab">
                                                    <h3>Valid from <?php echo $rfp->open_date ?> to <?php echo $rfp->close_date ?></h3>
                                                    <h5>Expected delivery date: <?php echo $rfp->expected_delivery_date ?></h5>
                                                    <h3>Purpose Statement</h3>
                                                    <?php echo $rfp->purpose_statement ?>
                                                    <h3>Expected Attachments</h3>
                                                    <ul class="list-ticked">
                                                        <li><?php echo str_replace(',', '</li><li>', $rfp->expected_attachments) ?></li>
                                                    </ul>

                                                    <h3>Expected from biders</h3>
                                                    <ul class="list-arrow">
                                                        <li><?php echo str_replace(',', '</li><li>', $rfp->expected_response) ?></li>
                                                    </ul>
                                                    <hr />
                                                    <h3>Terms and Conditions</h3>
                                                    <?php echo $rfp->payment_terms ?>
                                                    <hr />
                                                    <h3>Items</h3>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Description</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($itemsList as $i => $item) {
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $item->name ?></td>
                                                                    <td><?php echo $item->description ?></td>
                                                                    <td><?php echo $item->quantity ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane  <?php echo $proposals_tab_active ?>" id="proposals-tab">
                                                    <div class="panel-group accordion accordion-solid-header" id="accordion3" role="tablist">
                                                        <?php
                                                        $proposalsList = DB::getInstance()->querySample("SELECT l.* FROM purchase_order l WHERE l.requisition_id='$id' AND l.status=1");
                                                        if (empty($proposalsList)) {
                                                            echo '<div class="alert alert-info m-3">No proposals submitted</div>';
                                                        }
                                                        $expanded = count($proposalsList) == 1 ? true : false;
                                                        foreach ($proposalsList as $lpo) {
                                                            $files = DB::getInstance()->querySample("SELECT * FROM attachment WHERE purchase_order_id='$lpo->id' AND status=1");
                                                        ?>
                                                            <!--Start Loop-->
                                                            <div class="card" role="tab" id="lpo-item-<?php echo $lpo->id ?>">
                                                                <div class="card-header">
                                                                    <h6 class="mb-0">
                                                                        <a class="accordion-toggle accordion-toggle-styled <?php echo $expanded ? '' : 'collapsed' ?>" data-toggle="collapse" aria-expanded="<?php echo $expanded ? 'true' : 'false' ?>" data-parent="#accordion3" href="#collapse-<?php echo $lpo->id ?>"><i class='fa fa-check'></i> Serial No. <?php echo $lpo->serial_number; ?> </a>
                                                                    </h6>
                                                                </div>
                                                                <div id="collapse-<?php echo $lpo->id ?>" class=" <?php echo $expanded ? 'show' : 'collapse' ?>" aria-labelledby="lpo-item-<?php echo $lpo->id ?>">


                                                                    <div class="nav-tabs custom pt-1">
                                                                        <ul class="nav nav-tabs">
                                                                            <li class="nav-item"><a class="nav-link active" href="#lpo-summary-<?php echo $lpo->id ?>-tab" data-toggle="tab"><?php _e('Summary'); ?></a></li>
                                                                            <li class="nav-item"><a class="nav-link" href="#lpo-files-<?php echo $lpo->id ?>-tab" data-toggle="tab"><?php _e('Files'); ?></a></li>

                                                                            <div class="btn-group- pull-right" style="padding:6px;">
                                                                                <?php if (in_array("editLPO", $user_permissions)) { ?><a data-toggle='tooltip' title='<?php _e('Edit LPO'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=requisitions/edit_lpo&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=proposals-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id; ?>&tab=lpo-tab", "large");return false'>Edit</a><?php } ?>
                                                                                <?php if ($request->requisition_status == 'Approved') { ?><a data-toggle='tooltip' title='<?php _e('Upload File'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=files/upload&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=proposals-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id . '&project_id=' . $request->project_id . '&client_id=' . $request->client_id; ?>&tab=proposals-tab");return false'>Upload File</a><?php } ?>
                                                                                <?php if (in_array("deleteLPO", $user_permissions)) { ?><a data-toggle='tooltip' title='<?php _e('Delete LPO'); ?>' class="btn btn-default" onClick='showModal("index.php?modal=requisitions/delete_lpo&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=proposals-tab') . '&id=' . $request->id . '&lpo_id=' . $lpo->id; ?>&tab=lpo-tab");return false'><i class='fa fa-trash text-danger'></i></a><?php } ?>
                                                                                <a data-toggle='tooltip' onclick="PrintSection('proposalsection<?php echo $lpo->id ?>', '21.0', '29.7')" title='<?php _e('Print LPO'); ?>' class="btn btn-default btn-xs">Print</a>
                                                                            </div>

                                                                        </ul>
                                                                        <div class="tab-content">
                                                                            <div class="tab-pane active" id="lpo-summary-<?php echo $lpo->id ?>-tab">
                                                                                <div id="proposalsection<?php echo $lpo->id ?>" class="">

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
                                                <div class="tab-pane  <?php echo $attachments_tab_active ?>" id="attachments-tab">
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
                                                                </div>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else {
                                    echo '<div class="alert alert-danger">Invalid item</div>';
                                } ?>
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