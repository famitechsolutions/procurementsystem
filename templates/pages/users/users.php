<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/header.php'; ?>
</head>

<body class="nav-fixed">
    <?php require_once 'includes/header_menu.php'; ?>
    <div id="layoutSidenav">
        <?php require_once 'includes/side_menu.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid mt-2">
                    <div class="card p-2">
                        <div class="card-title">Users List
                            <button class="btn btn-primary btn-xs" onclick="showModal('index.php?modal=users/edit_user&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) ?>');return false" data-toggle="modal">Add User</button>
                        </div>
                        <?php
                        if (isset($_GET['action']) && $_GET['action'] == "delete_user" && $_GET['id'] != "") {
                            $id = $crypt->decode($_GET['id']);
                            $updateUser = DB::getInstance()->update('user', $id, array('status' => 0), 'id');
                            echo '<div class="alert alert-warning">User successfully deleted</div>';
                            Redirect::go_to('index.php?page=' . $crypt->encode("users"));
                        }
                        $usersCheck = "SELECT u.*, d.name department_name FROM user u LEFT JOIN department d ON(d.id=u.department_id) WHERE u.status=1 AND u.is_verified=1 ORDER BY CONCAT(fname,lname)";
                        $users_list = DB::getInstance()->querySample($usersCheck);
                        if ($users_list) {
                        ?>
                            <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                <thead>
                                    <tr>
                                        <th>Names</th>
                                        <th>Gender</th>
                                        <th>Email Address</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Phone No.</th>
                                        <th>DOB</th>
                                        <th>NIN</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($users_list as $users) :
                                    ?>
                                        <tr>
                                            <td><?php echo $users->fname . ' ' . $users->lname ?></td>
                                            <td><?php echo $users->gender ?></td>
                                            <td><?php echo $users->email ?></td>
                                            <td><?php echo $users->category ?></td>
                                            <td><?php echo $users->department_name ?></td>
                                            <td><?php echo $users->phone ?></td>
                                            <td><?php echo $users->dob ?></td>
                                            <td><?php echo $users->nin ?></td>
                                            <td>
                                                <a onclick="showModal('index.php?modal=users/edit_user&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) . '&id=' . $users->id ?>');return false" data-toggle="modal" class="btn btn-primary btn-xs">Edit</a>
                                                <a href="index.php?page=<?php echo $crypt->encode("users") . '&action=delete_user&id=' . $crypt->encode($users->id) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Do you really want to remove this user from the system')">Remove</a>
                                            </td>
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
            </main>
            <?php require_once 'includes/footer_menu.php'; ?>
        </div>
    </div>
    <?php require_once 'includes/footer.php'; ?>

</body>

</html>