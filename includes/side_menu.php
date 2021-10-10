<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboards
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDashboards" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="?page=<?php echo $crypt->encode('dashboard')?>">
                            Default
                            <span class="badge bg-primary-soft text-primary ms-auto">Updated</span>
                        </a>
                        <a class="nav-link" href="#">Multipurpose</a>
                        <a class="nav-link" href="#">Affiliate</a>
                    </nav>
                </div>
                <a class="nav-link" href="?page=<?php echo $crypt->encode("users")?>">
                    <div class="nav-link-icon"><i class="fa fa-users"></i></div>
                    Users
                </a>
                <a class="nav-link" href="?page=<?php echo $crypt->encode("requisition")?>">
                    <div class="nav-link-icon"><i class="fa fa-bars"></i></div>
                    Requisitions
                </a>
            </div>
        </div>
    </nav>
</div>