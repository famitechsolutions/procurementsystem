<?php
$rfp_id = $_GET['rfp_id'];
$proposal_id = $_GET['proposal_id'];
?>

<div class="modal-header">
    <h4 class="modal-title">Proposal Approval</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <label>Contract Title</label>
    <input class="form-control" name="contract[contract_title]" required />

    <label>Contract Terms and conditions</label>
    <textarea class="form-control" name="contract[contract_terms]"></textarea>
    <div class="row form-group">
        <div class="col">
            <label>Start Date</label>
            <input class="form-control" type="date" name="contract[start_date]" required min="<?php $proposal->close_date ?>" />
        </div>
        <div class="col">
            <label>End Date</label>
            <input class="form-control" type="date" name="contract[end_date]" required min="<?php $proposal->close_date ?>" />
        </div>
    </div>
    <div class="alert alert-warning"><i class=" fa fa-warning"></i> Once approved, all other requests will be rejected, and the Request will be marked complete</div>
    <input type="hidden" name="action" value="approveProposal">
    <input type="hidden" name="rfp_id" value="<?php echo $rfp_id ?>">
    <input type="hidden" name="proposal_id" value="<?php echo $proposal_id ?>">
    <input type="hidden" name="reroute" value="<?php echo $_GET['reroute']; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php _e('Cancel'); ?></button>
    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> <?php _e('Continue'); ?></button>
</div>