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
                                <?php
                                $id = $crypt->decode($_GET['rfp']);
                                $rfp = DB::getInstance()->querySample("SELECT ca.*,r.* FROM rfp r LEFT JOIN contract_application ca ON (ca.rfp_id=r.id AND ca.user_id='$user_id') WHERE r.id='$id' AND r.rfp_status='Open' AND r.open_date<='$date_today' AND r.close_date>='$date_today' AND r.status=1 GROUP BY r.id")[0];
                                if ($rfp) {
                                    $attachments = explode(",", $rfp->expected_attachments);
                                    $questions = explode(",", $rfp->expected_response);
                                    $rfpItems = DB::getInstance()->querySample("SELECT ri.*,i.* FROM item i, rfp_item ri WHERE ri.item_id=i.id AND ri.rfp_id='$id' AND i.status=1 GROUP BY i.id");
                                ?>
                                    <div class="card-title">
                                        Request for proposal #<?php echo $rfp->id . ' <small class="ml-5">Deadline:</small><span class="btn btn-success btn-xs">' . $rfp->close_date . '</span>' ?>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($rfp->rfp_id) {
                                            echo '<div class="alert alert-warning">Your Application already submitted on ' . $rfp->application_date . '</div>';
                                        } else { ?>
                                            <form method="POST" enctype="multipart/form-data">
                                                <h4>Items List. Kindly check what you are capable of supplying and add the price list</h4>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Item</th>
                                                            <th>Unit Measure</th>
                                                            <th>Description</th>
                                                            <th>Your Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($rfpItems as $item) { ?>
                                                            <tr>
                                                                <td><label><input type="checkbox" name="items[id][]" id="item_<?php echo $item->item_id ?>" value="<?php echo $item->item_id ?>" onchange="toggleProposalItem(this,'<?php echo $item->item_id ?>')" /> <?php echo $item->name ?></label></td>
                                                                <td><?php echo $item->unit_measure ?></td>
                                                                <td><?php echo $item->description ?></td>
                                                                <td><input type="number" disabled id="price_<?php echo $item->item_id ?>" step="any" class="form-control" name="items[price][]" required/></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php if (!empty($questions)) {
                                                    foreach ($questions as $question) { ?>
                                                        <label><?php echo $question ?></label>
                                                        <textarea name="question[<?php echo $question ?>]" class="form-control mb-2"></textarea>
                                                    <?php }
                                                }
                                                if (!empty($attachments)) { ?>
                                                    <hr />
                                                    <h3>Attachments needed</h3>
                                                    <p class="text-warning">Any attachment needed MUST be attached from here. once submitted, there won't be additional submission</p>
                                                    <div class="row">
                                                        <?php foreach ($attachments as $attachment) { ?>
                                                            <div class="col">
                                                                <label><?php echo $attachment ?></label><br />
                                                                <input type="hidden" name="attachment_title[]" value="<?php echo $attachment ?>" />
                                                                <input type="file" name="file[]" class="mb-2" />
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                                <input type="hidden" name="action" value="createBid">
                                                <input type="hidden" name="rfp_id" value="<?php echo $id ?>">
                                                <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $crypt->encode('rfp') . '&id=' . $crypt->encode($id) . '&tab=proposals-tab'); ?>">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                <?php } else {
                                    echo '<div class="alert alert-warning">The request for proposal not available, the deadline might have passed.</div>';
                                } ?>
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
<script>
    function toggleProposalItem(el, id) {
        if (el.checked) {
            $("#price_" + id).removeAttr('disabled');
        } else {
            $("#price_" + id).attr({
                'disabled': true
            })
        }
    }
</script>

</html>