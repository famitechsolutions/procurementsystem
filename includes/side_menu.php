<?php
$_SESSION["PREVIOUS_URL"] = $_SERVER["REQUEST_URI"];
$user_role = $_SESSION['system_user_role'];
$user_id = $_SESSION['system_user_id'];
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?php echo ($page == 'dashboard') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('dashboard') ?>">
                <i class="fa fa-home menu-icon"></i> <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <?php if(in_array("viewUsers",$user_permissions)){?>
        <li class="nav-item <?php echo ($page == 'users') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('users') ?>">
                <i class="fa fa-users menu-icon"></i> <span class="menu-title">Users</span>
            </a>
        </li>
        <?php } if(in_array("viewRequisition",$user_permissions)){?>
        <li class="nav-item <?php echo ($page == 'requisition') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('requisition') ?>">
                <i class="fa fa-bars menu-icon"></i> <span class="menu-title">Requistions</span>
            </a>
        </li>
        <?php }?>
        <li class="nav-item <?php echo ($page == 'rfps') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('rfps') ?>">
                <i class="fa fa-bars menu-icon"></i> <span class="menu-title">Request for proposals</span>
            </a>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('logout') ?>"><i class="fa fa-power-off menu-icon"></i><span class="menu-title"> Logout</span></a></li>
    </ul>
</nav>