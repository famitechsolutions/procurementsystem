<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/header.php'; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php require_once 'includes/header_menu.php'; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->



            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <?php
            require_once 'includes/side_menu.php';
            $year = date("Y");
            $month_year = date("Y-m");
            ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="d-lg-flex align-items-baseline">
                                <h4 class="text-dark mb-0">
                                    Hi <?php echo $userInfo->email ?>, welcome back!
                                </h4>
                                <p class="ml-md-3 font-weight-light mb-0 mt-1">Last login was <?php echo timeAgo($_SESSION['system_last_login']) ?>.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $proposalFilter='';
                    if($userInfo->category == 'Supplier'){
                        $proposalFilter=" AND ca.user_id='$user_id'";
                    }
                    $users = DB::getInstance()->querySample("SELECT COUNT(id) total_users,SUM(CASE WHEN DATE(last_login)='$date_today' THEN 1 ELSE 0 END) todays_logs,SUM(CASE WHEN YEAR(time_created)='" . date("Y") . "' THEN 1 ELSE 0 END)new_users FROM user WHERE status=1 $filterCondition")[0];
                    $requisition = DB::getInstance()->querySample("SELECT COUNT(id)total,SUM(CASE WHEN requisition_status='Pending' THEN 1 ELSE 0 END)pending, SUM(CASE WHEN (requisition_status='Rejected') THEN 1 ELSE 0 END)rejected FROM requisition WHERE status=1")[0];
                    $rfp = DB::getInstance()->querySample("SELECT COUNT(r.id)total,SUM(CASE WHEN rfp_status='Open' THEN 1 ELSE 0 END)open, SUM(CASE WHEN (SELECT COUNT(id) FROM contract_application WHERE rfp_id=r.id LIMIT 1)=0 THEN 1 ELSE 0 END)pending_application FROM rfp r WHERE  r.status=1")[0];
                    $proposals = DB::getInstance()->querySample("SELECT COUNT(id)total,SUM(CASE WHEN application_status='Pending' THEN 1 ELSE 0 END)pending, SUM(CASE WHEN (application_status='Rejected') THEN 1 ELSE 0 END)rejected FROM contract_application ca WHERE status=1 $proposalFilter")[0];
                    $items = DB::getInstance()->querySample("SELECT i.name, COUNT(ri.item_id)total_requisitions, SUM(CASE WHEN ri.purchase_order_id IS NOT NULL THEN 1 ELSE 0 END)total_lpos FROM item i LEFT JOIN requisition_item ri ON (ri.item_id=i.id AND ri.status=1) WHERE i.status=1 GROUP BY i.id");
                    ?>
                    <div class="row mt-2">
                        <?php if($userInfo->category!='Supplier'){?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="admin-content card analysis-progrebar-ctn res-mg-t-15">
                                <div class="text-left widget-thumb">
                                    <div class="widget-thumb-wrap">
                                        <h5 class="card-title">Requisitions</h5>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">
                                                Total: <span class="value"><?php echo $requisition->total; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode("requisition") ?>">View all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Pending Approval: <span class="value"><?php echo $requisition->pending; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode('requisition'); ?>">view all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Rejected: <span class="value"><?php echo $requisition->rejected; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode("requisition") . '&requisition_status=Rejected' ?>">View all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="admin-content card analysis-progrebar-ctn res-mg-t-15">
                                <div class="text-left widget-thumb">
                                    <div class="widget-thumb-wrap">
                                        <h5 class="card-title">RFPs</h5>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">
                                                Total: <span class="value"><?php echo $rfp->total; ?></span>
                                                <?php if (in_array("viewRFP", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode("requisition") ?>">View all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Open: <span class="value"><?php echo $rfp->open; ?></span>
                                                <?php if (in_array("viewRFP", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode('requisition'); ?>">view all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Not Applied For: <span class="value"><?php echo $rfp->pending_application; ?></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="admin-content card analysis-progrebar-ctn res-mg-t-15">
                                <div class="text-left widget-thumb">
                                    <div class="widget-thumb-wrap">
                                        <h5 class="card-title">Supplier Proposals</h5>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">
                                                Total: <span class="value"><?php echo $proposals->total; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode("requisition") ?>">View all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Pending Approval: <span class="value"><?php echo $proposals->pending; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode('requisition'); ?>">view all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                            <hr />
                                            <span class="widget-thumb-subtitle">Rejected: <span class="value"><?php echo $proposals->rejected; ?></span>
                                                <?php if (in_array("viewRequisition", $user_permissions)) { ?>
                                                    <a href="index.php?page=<?php echo $crypt->encode("requisition") . '&requisition_status=Rejected' ?>">View all <i class="fa fa-angle-double-right"></i></a>
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($userInfo->category != 'Supplier') { ?>
                        <div class="row mt-2">
                            <div class="col-md-7 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Items Statistics</h4>
                                        <canvas id="barChart" class="mt-4"></canvas>
                                        <div id="chart-legendsBar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-sm-12 grid-margin">
                                        <div class="card">
                                            <div>
                                                <?php
                                                if (in_array("viewUsers", $user_permissions)) { ?>
                                                    <a class="quick-btn">
                                                        <i class="fa fa-folder-open-o fa-2x"></i>
                                                        <span>All Files</span>
                                                        <span class="label label-warning"><?php echo DB::getInstance()->countElements("SELECT id FROM attachment WHERE status=1") ?></span>
                                                    </a>
                                                <?php }
                                                if (in_array("viewLogs", $user_permissions)) { ?>
                                                    <a class="quick-btn" href="index.php?page=<?php echo $crypt->encode("system_logs") ?>">
                                                        <i class="fa fa-lock fa-2x"></i>
                                                        <span>Today's log</span>
                                                        <span class="label label-danger"><?php echo $users->todays_logs; ?></span>
                                                    </a>
                                                <?php }
                                                $suppliers = DB::getInstance()->countElements("SELECT u.id FROM user u WHERE u.status=1 AND is_verified=1 AND category='Supplier' GROUP BY u.id");
                                                if (in_array("viewUsers", $user_permissions)) { ?>
                                                    <a class="quick-btn" href="index.php?page=<?php echo $crypt->encode('users') . '&category=Supplier' ?>">
                                                        <i class="fa fa-user-circle fa-2x"></i>
                                                        <span>Suppliers</span>
                                                        <span class="label label-success"><?php echo $suppliers; ?></span>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php if (in_array("viewUsers", $user_permissions)) { ?>
                                                    <div class="d-flex flex-wrap justify-content-between">
                                                        <h5 class="card-title">System Users</h5>
                                                    </div>
                                                    <p class="text-muted mb-3">Summary of system users, today's logins, and recent signups </p>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="d-flex purchase-detail-legend align-items-center mt-1">
                                                                <div id="circleProgress1" class="p-2 circle-progress-dimension"></div>
                                                                <div>
                                                                    <p class="font-weight-medium text-dark text-small mb-0">Total</p>
                                                                    <h3 class="font-weight-medium text-dark   mb-0"><?php echo $users->total_users ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="d-flex purchase-detail-legend align-items-center">
                                                                <div id="circleProgress2" class="p-2 circle-progress-dimension"></div>
                                                                <div>
                                                                    <p class="font-weight-medium text-dark text-small mb-0">Today's Logins</p>
                                                                    <h3 class="font-weight-medium text-dark   mb-0"><?php echo $users->todays_logs ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="d-flex purchase-detail-legend align-items-center">
                                                                <div id="circleProgress4" class="p-2 circle-progress-dimension"></div>
                                                                <div>
                                                                    <p class="font-weight-medium text-dark text-small mb-0">New</p>
                                                                    <h3 class="font-weight-medium text-dark   mb-0"><?php echo $users->new_users ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?php require_once 'includes/footer_menu.php'; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script>
        const itemsObject = <?php echo json_encode(($items) ? $items : array()) ?>;
        const itemLabels = <?php echo json_encode(($labels) ? $labels : array()) ?>;
    </script>
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>