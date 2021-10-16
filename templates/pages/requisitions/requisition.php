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
                                    Requisitions
                                    <?php if (in_array("addRequisition", $user_permissions)) { ?><button onclick='showModal("index.php?modal=requisitions/add&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) ?>", "large");return false' class="btn btn-primary btn-xs pull-right">New Requisition</button><?php } ?>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $queryCondition = "";
                                    if (isset($_GET['requisition_status'])) {
                                        $status = $_GET['requisition_status'];
                                        $queryCondition .= ($status != '') ? " AND r.requisition_status LIKE '%$status%'" : "";
                                    }
                                    $queryCondition .= in_array('viewOtherRequisitions', $user_permissions) ? "" : " AND (r.user_id='$user_id')";
                                    $requisitionList = DB::getInstance()->querySample("SELECT r.*,d.name department_name, CONCAT(fname,' ',lname) user FROM requisition r, department d,user u WHERE r.user_id=u.id AND r.department_id=d.id AND r.status=1 $queryCondition");
                                    if ($requisitionList) {
                                    ?>
                                        <div class="datatable-dashv1-list custom-datatable-overright table-responsive">
                                            <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Requisition No.</th>
                                                        <th>Department</th>
                                                        <th>User</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($requisitionList as $list) {
                                                        $single_link = "index.php?page=" . $crypt->encode('manage_requisition') . '&id=' . $crypt->encode($list->id);
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $list->date ?></td>
                                                            <td><a href="<?php echo $single_link ?>"><?php echo $list->requisition_number ?></a></td>
                                                            <td><?php echo $list->department_name ?></td>
                                                            <td><?php echo $list->user ?></td>
                                                            <td><?php echo $list->amount_requested ?></td>
                                                            <td><span class='btn btn-primary btn-xs'><?php echo $list->requisition_status ?></span></td>
                                                            <td>
                                                                <a href="<?php echo $single_link ?>" class="fa fa-eye"></a>
                                                                <?php if (in_array("editRequisition", $user_permissions) && in_array($list->requisition_status, $REQUISITION_EDITABLE_STATUS_LIST)) { ?><a onclick='showModal("index.php?modal=requisitions/edit&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) . '&id=' . $list->id; ?>&tab=general-tab", "large");return false' class="fa fa-edit"></a><?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php
                                    } else {
                                        echo '<div class="alert alert-danger">Nothing to display</div>';
                                    }
                                    ?>
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