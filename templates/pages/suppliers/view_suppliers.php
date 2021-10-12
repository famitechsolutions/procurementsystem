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
                                    <h4 class="card-title">Suppliers List
                                        <div class="pull-right">
                                            <button onClick='showModal("index.php?modal=suppliers/add_suppliers&reroute=<?php echo $crypt->encode('page='.$_GET['page']) ?>");return false' data-toggle="modal" class="btn btn-primary btn-xs">Add Suppliers</button>
                                        </div>
                                    </h4>

                                    <div class="card-body">
                                        <?php
                                        //Delete Supplier
                                        if (isset($_GET['action']) && $_GET['action'] == $crypt->encode("remove_supplier") && $_GET['supplier_id'] != "") {
                                            $supplier_id = $_GET['supplier_id'];
                                            DB::getInstance()->update('suppliers', $supplier_id, array('status' => 0), 'id');
                                        }
                                        $supplier_query = "select * from suppliers where status=1 order by id desc";
                                        $suppliers_list = DB::getInstance()->querySample($supplier_query);
                                        if (DB::getInstance()->checkRows($supplier_query)) {
                                            $no = 1;
                                            ?>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
                                                <div id="toolbar">
                                                    <select class="form-control">
                                                        <option value="">Export Basic</option>
                                                        <option value="all">Export All</option>
                                                        <option value="selected">Export Selected</option>
                                                    </select>
                                                </div>
                                                <table class="table table-bordered" id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="state" data-checkbox="true"></th>
                                                            <th>No</th>
                                                            <th>Names</th>
                                                            <th>Address</th>
                                                            <th>Contacts</th>
                                                            <th>Phone</th>
                                                            <th>Email</th>
                                                            <th>Web</th>
                                                            <th>Notes</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($suppliers_list AS $list) {
                                                            ?>
                                                            <tr>
                                                                <td></td>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $list->name; ?></td>
                                                                <td><?php echo $list->address; ?></td>
                                                                <td><?php echo $list->contactnumber; ?></td>
                                                                <td><?php echo $list->phone; ?></td>
                                                                <td><?php echo $list->email; ?></td>
                                                                <td><?php echo $list->web; ?></td>
                                                                <td><?php echo $list->notes; ?></td>
                                                                <td>

                                                                    <input type="hidden" name="edit_supplier" value="<?php echo $list->id; ?>">
                                                                    <a onClick='showModal("index.php?modal=suppliers/add_suppliers&reroute=<?php echo $crypt->encode('page='.$_GET['page']) . '&edit_supplier=' . $list->id ?>");return false' data-toggle="modal" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                                                    <a href="index.php?page=<?php echo $crypt->encode('view_suppliers') . '&action=' . $crypt->encode('remove_supplier') . '&supplier_id=' . $list->id; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Do you really want to Delete this Supplier?');"><i class="fa fa-trash-o"></i></a> 
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $no++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php
                                        } else {
                                            echo '<div class="alert alert-warning">NO Suppliers Already Registered!</div>';
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
