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
                    Data....
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
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>