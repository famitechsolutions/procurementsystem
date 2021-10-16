<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-info">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <a class="navbar-brand brand-logo"><img src="<?php echo $logo; ?>" alt="" /></a>
        <a class="navbar-brand brand-logo-mini"><img src="<?php echo $logo_small; ?>" alt="" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <ul class="navbar-nav navbar-nav-right">
            <?php if (in_array("manageSettings", $user_permissions) || in_array("viewLogs", $user_permissions) || in_array("addUserRole", $user_permissions) || in_array("uploadKPI", $user_permissions)) {
                $page_in_settings = ($page == 'site_settings' || $page == 'system_logs' || $page == 'user_roles') ? TRUE : FALSE;
            ?>
                <li class="nav-item d-flex dropdown mr-1 ml-1">
                    <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" href="#" title="System" data-toggle="dropdown">
                        <i class="mdi mdi-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                        <?php if (in_array("manageSettings", $user_permissions)) { ?>
                            <a class="dropdown-item" href="index.php?page=<?php echo $crypt->encode('site_settings') ?>"><i class="fa fa-gear"></i> System Settings</a>
                        <?php
                        }
                        if (in_array("viewLogs", $user_permissions)) {
                        ?>
                            <a class="dropdown-item" href="index.php?page=<?php echo $crypt->encode('system_logs') ?>"><i class="fa fa-history"></i> System Logs</a>
                        <?php }
                        ?>
                    </div>
                </li>
            <?php } ?>
            <li class="nav-item d-flex dropdown mr-1 ml-1">
                <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-plus-circle mx-0"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <?php if (in_array("addUser", $user_permissions)) { ?>
                        <a onclick="showModal('index.php?modal=users/edit_user&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) ?>');return false" data-toggle="modal" class="dropdown-item">New User</a>
                    <?php
                    }
                    if (in_array("addRequisition", $user_permissions)) { ?><button onclick="showModal('index.php?modal=requisitions/add&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) ?>', 'large');return false" class="dropdown-item">New Requisition</button><?php }
                                                                                                                                                                                                                                                                            if (in_array("addRFP", $user_permissions)) { ?><button onclick="showModal('index.php?modal=proposals/add_rfp&reroute=<?php echo $crypt->encode('page=' . $_GET['page']) ?>', 'large');return false" class="dropdown-item">Request for proposal</button><?php }

                                                                                                                                                                                                                                                                            ?>
                </div>
            </li>
            <li class="nav-item d-flex nav-profile dropdown ml-1">
                <a class="nav-link" data-toggle="dropdown" id="profileDropdown">
                    <img src="<?php echo $_SESSION['user_profile_picture'] ?>" alt="" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a href="index.php?page=<?php echo $crypt->encode("user_profile") ?>" class="dropdown-item"><i class="mdi mdi-account-outline text-primary"></i> My Profile</a>
                    <a class="dropdown-item" href="index.php?page=<?php echo $crypt->encode("logout") ?>"><i class="fa fa-power-off text-danger"></i> Logout</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>

</nav>