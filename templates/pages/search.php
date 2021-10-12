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
                                <?php
                                $assetSearchCondition = "";
                                $inventorySearchCondition = "";
                                if (isset($_GET["s"]) && $_GET['s'] != "") {
                                    $value = htmlentities(Input::get("s"), ENT_QUOTES);
                                    $like_variable = " LIKE '%$value%'";
                                    $assetSearchCondition = " AND (ass.name $like_variable OR ass.tag $like_variable OR cat.name $like_variable OR lab.name $like_variable OR man.name $like_variable OR ass.notes $like_variable)";
                                    $inventorySearchCondition = " AND (inv.name $like_variable OR inv.tag $like_variable OR inv.serial $like_variable OR inv.notes $like_variable)";

                                    $assetsQuery = "SELECT ass.*,cat.name AS category_name, cat.color AS category_color, lab.name AS label_name,lab.color AS label_color,depreciation_rate,man.name AS manufacturer FROM assets ass, asset_categories cat,manufacturers man,asset_labels lab WHERE ass.label_id=lab.id AND ass.manufacturer_id=man.id AND ass.category_id=cat.id AND ass.status=1 AND cat.status=1 $assetSearchCondition ORDER BY ass.name";
                                    $assets_list = DB::getInstance()->querySample($assetsQuery);
                                    $inventoryQuery = "SELECT inv.*, CASE WHEN TIMESTAMPDIFF(DAY,inv.expiry_date,'$date_today')>0 THEN 1 ELSE 0 END is_expired,  sup.name AS supplier FROM inventory inv,suppliers sup WHERE inv.supplier_id=sup.id AND inv.status=1 AND sup.status=1 $inventorySearchCondition ORDER BY inv.name";
                                    $inventory_list = DB::getInstance()->querySample($inventoryQuery);
                                    if ($assets_list && in_array("viewAssets", $user_permissions)) {
                                        ?> 
                                        <h4>Assets</h4>
                                        <div class="row">
                                            <?php
                                            foreach ($assets_list AS $assets):
                                                $asset_link = "index.php?page=" . $crypt->encode("manage_asset") . '&id=' . $crypt->encode($assets->id);
                                                ?>
                                                <div class="col-sm-3 col-xs-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="stats-title">
                                                                <a href="<?php echo $asset_link; ?>"><h4 class="text-info"><?php echo $assets->tag; ?></h4></a>
                                                            </div>
                                                            <p class="page-description text-muted"><?php echo $assets->name; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php
                                    }
                                    if ($inventory_list && in_array("viewInventory", $user_permissions)) {
                                        ?>
                                        <h4>Inventory</h4>
                                        <div class="row">
                                            <?php
                                            foreach ($inventory_list AS $inventory):
                                                $inventory_link = "#"; // "index.php?page=" . $crypt->encode("manage_inventory") . '&id=' . $crypt->encode($inventory->id);
                                                ?>
                                                <div class="col-sm-3 col-xs-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="stats-title">
                                                                <a href="<?php echo $inventory_link; ?>"><h4 class="text-info"><?php echo $inventory->tag; ?></h4></a>
                                                            </div>
                                                            <p class="page-description text-muted"><?php echo $inventory->name; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php
                                    }
                                    if(!$assets_list&&!$inventory_list){
                                        echo '<div class="alert alert-danger">No search results for '.$value.'</div>';
                                    }
                                }
                                ?>
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
