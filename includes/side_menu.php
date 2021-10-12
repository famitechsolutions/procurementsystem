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
        <li class="nav-item <?php echo ($page == 'users') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('users') ?>">
                <i class="fa fa-users menu-icon"></i> <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($page == 'requisition') ? 'active' : '' ?>">
            <a class="nav-link" href="index.php?page=<?php echo $crypt->encode('requisition') ?>">
                <i class="fa fa-bars menu-icon"></i> <span class="menu-title">Requistions</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($page_in_asset_attributes) ? 'active' : '' ?>">
            <a class="nav-link <?php echo ($page_in_asset_attributes) ? '' : 'collapsed' ?>" aria-expanded="<?php echo ($page_in_settings) ? 'true' : 'false' ?>" data-toggle="collapse" href="#ui-asset-attributes">
                <i class="fa fa-wrench menu-icon"></i><span class="menu-title">Asset Attributes</span> <i class="menu-arrow"></i> </a>
            <div class="collapse <?php echo ($page_in_asset_attributes) ? 'show' : '' ?>" id="ui-asset-attributes">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('asset_categories') ?>">Asset Categories</a> </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('license_categories') ?>">License Categories</a> </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('asset_status_labels') ?>">Status Labels</a> </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('manufacturers') ?>">Manufacturers</a> </li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('view_suppliers') ?>">Suppliers</a> </li>
                </ul>
            </div>
        </li>
        <li class="nav-item active">
            <a class="nav-link collapsed" aria-expanded="<?php echo ($page_in_settings) ? 'true' : 'false' ?>" data-toggle="collapse" href="#ui-bids">
                <i class="fa fa-dashcube menu-icon"></i><span class="menu-title">Bids</span> <i class="menu-arrow"></i> </a>
            <div class="collapse show" id="ui-bids">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="#">Create Request For proposal</a> </li>
                    <li class="nav-item"><a class="nav-link" href="#">Manage suppliers</a> </li>
                    <li class="nav-item"><a class="nav-link" href="">Criteria For Bid Selection</a> </li>
                </ul>
            </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=<?php echo $crypt->encode('logout') ?>"><i class="fa fa-power-off menu-icon"></i><span class="menu-title"> Logout</span></a></li>
    </ul>
</nav>