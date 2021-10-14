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
                                                <?php if (in_array("approveRequisition",$user_permissions)&&$request->requisition_status=='Pending') { ?>
                                                    <a data-toggle='tooltip' title='<?php _e('Approve '.$request->category); ?>' class="btn btn-primary btn-xs " onClick='showModal("index.php?modal=requisitions/approve&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=summary-tab') . '&id=' . $request->id; ?>&tab=summary-tab");return false'>Approve</a>
                                                    <a data-toggle='tooltip' title='<?php _e('Reject '.$request->category); ?>' class="btn btn-danger btn-xs " onClick='showModal("index.php?modal=requisitions/reject&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=summary-tab') . '&id=' . $request->id; ?>&tab=summary-tab");return false'>Reject</a>
                                                <?php } ?>
                                                <?php if (in_array("editRequisition", $user_permissions) && ($request->requisition_status=='Pending'||$request->requisition_status=='Rejected')) { ?><a data-toggle='tooltip' title='<?php _e('Edit '.$request->category); ?>' class="btn btn-default btn-xs " href="#" onClick='showModal("index.php?modal=requisitions/edit&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=summary-tab') . '&id=' . $request->id; ?>&tab=summary-tab", "large");return false'>Edit</a><?php } ?>
                                                <?php if (in_array("addLPO", $user_permissions) && $request->requisition_status == 'Approved' && $lpo_enabled) { ?><a data-toggle='tooltip' title='<?php _e('Add LPO'); ?>' class="btn btn-primary btn-xs " href="#" onClick='showModal("index.php?modal=requisitions/add_lpo&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=proposals-tab') . '&id=' . $request->id; ?>&tab=proposals-tab", "large");return false'>Add LPO</a><?php } ?>
                                                <a data-toggle='tooltip' title='<?php _e('Upload File'); ?>' class="btn btn-default btn-xs" onClick='showModal("index.php?modal=attachments/upload&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&id=' . $_GET['id'] . '&tab=attachments-tab') . '&id=' . $request->id . '&requisition_id=' . $request->id ?>&tab=attachments-tab");return false'>Upload File</a>
                                                <?php if (in_array("deleteRequisition", $user_permissions) && $request->requisition_status == 'Requested') { ?><a data-toggle='tooltip' title='<?php _e('Delete '.$request->category); ?>' class="btn btn-danger btn-xs " href="#" onClick='showModal("index.php?modal=requisitions/delete<?php echo '&reroute=' . $crypt->encode('page=' . $crypt->encode('requisition')) . '&id=' . $request->id; ?>");return false'><i class="fa fa-trash"></i></a><?php } ?>
                                            </div>


                                        </ul>
                                        <div class="tab-content p-2">
                                            <div class="tab-pane  <?php echo $summary_tab_active ?>" id="summary-tab">
                                                    <h2>Valid from <?php echo $rfp->open_date?> to <?php echo $rfp->close_date?></h2>
                                                    <h4>Expected delivery date: <?php echo $rfp->expected_delivery_date?></h4>
                                                    <h2>Expected Attachments</h2>
                                                    <ul class="list-ticked">
                                                        <li><?php echo str_replace(',','</li><li>',$rfp->expected_attachments)?></li>
                                                    </ul>
                                            </div>
                                            <div class="tab-pane  <?php echo $proposals_tab_active ?>" id="proposals-tab">
                                            </div>
                                            <div class="tab-pane  <?php echo $attachments_tab_active ?>" id="attachments-tab">
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