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
                                    Request for proposals
                                </div>
                                <div class="card-body">
                                    <?php
                                    $rfps = DB::getInstance()->querySample("SELECT * FROM rfp WHERE status=1");
                                    if ($rfps) {
                                    ?>
                                        <div class="datatable-dashv1-list custom-datatable-overright">
                                            <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                <thead>
                                                    <tr>
                                                        <th>Open Date</th>
                                                        <th>Close Date</th>
                                                        <th>Expected Delivery Date</th>
                                                        <th>Expected Attachments</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rfps as $rfp) { ?>
                                                        <tr style="position: relative;">
                                                            <td><a class="stretched-link-" href="?page=<?php echo $crypt->encode('rfp').'&id='.$crypt->encode($rfp->id)?>"><?php echo $rfp->open_date?></a></td>
                                                            <td><?php echo $rfp->close_date?></td>
                                                            <td><?php echo $rfp->expected_delivery_date?></td>
                                                            <td><?php echo $rfp->expected_attachments?></td>
                                                            <td><?php echo $rfp->rfp_status?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } else {
                                        echo '<div class="alert alert-danger">No requests available for display</div>';
                                    } ?>
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