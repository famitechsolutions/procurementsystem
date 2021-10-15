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
                                    <div class="card-body">
                                    <div class="card-title">System logs</div>
                                        <?php
                                        $logsQuery = "SELECT * FROM user u,logs l WHERE l.user_id=u.id ORDER BY log_time DESC";
                                        if (DB::getInstance()->checkRows($logsQuery)) {
                                            ?>
                                            <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                <thead>
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Action made</th>
                                                        <th>Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $logs_list = DB::getInstance()->querySample($logsQuery);
                                                    foreach ($logs_list AS $logs):
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $logs->fname . ' ' . $logs->lname ?></td>
                                                            <td><?php echo $logs->log_action ?></td>
                                                            <td><?php echo english_date_time($logs->log_time) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                            <?php
                                        } else {
                                            echo '<div class="alert alert-warning">No User Details registered</div>';
                                        }
                                        ?>
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
