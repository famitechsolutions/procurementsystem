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
                                ?>
                                    <div class="card-title">
                                        Request for proposal #<?php echo $rfp->id . ' <small class="ml-5">Deadline:</small><span class="btn btn-success btn-xs">' . $rfp->close_date . '</span>' ?>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($rfp->rfp_id) {
                                            echo '<div class="alert alert-warning">Your Application already submitted on ' . $rfp->application_date . '</div>';
                                        } else { ?>
                                            <form method="POST" enctype="multipart/form-data">
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
                                                <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $crypt->encode('rfp') . '&id='.$crypt->encode($id).'&tab=proposals-tab'); ?>">
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

</html>