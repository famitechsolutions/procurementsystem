<!DOCTYPE html>
<html lang="en">

    <head>
        <?php require_once 'includes/header.php';?>
    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:../../partials/_navbar.html -->
            <?php require_once 'includes/header_menu.php';?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:../../partials/_sidebar.html -->
                <?php require_once 'includes/side_menu.php';?>
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
                                        <div class="datatable-dashv1-list custom-datatable-overright">
                                            <table class="table table-bordered" id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                <thead>
                                                    <tr>
                                                        <th>User Ref. No</th>
                                                        <th>Requisition No.</th>
                                                        <th>Department</th>
                                                        <th>Price Requested</th>
                                                        <th>Price Approved</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <a class="fa fa-eye"></a>
                                                            <?php if (in_array("editLPO", $user_permissions)) { ?><a class="fa fa-edit"></a><?php }?>
                                                            <?php if (in_array("deleteLPO", $user_permissions)) { ?><a class="fa fa-trash text-danger"></a><?php }?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:../../partials/_footer.html -->
                    <?php require_once 'includes/footer_menu.php';?>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <?php require_once 'includes/footer.php';?>
    </body>

</html>
