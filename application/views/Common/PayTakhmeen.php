<?php
// Simple page showing a Pay Now button and minimal modal for takhmeen payments
?>
<div class="container margintopcontainer pt-4">
  <h4 class="heading mb-3">Takhmeen Payment</h4>

  <div class="text-center mb-3">
    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adminTakhmeenPayModal">
      <i class="fa fa-credit-card me-1"></i> Pay Now
    </button>
  </div>

  <div class="modal fade" id="adminTakhmeenPayModal" tabindex="-1" aria-labelledby="adminTakhmeenPayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminTakhmeenPayModalLabel">Admin: Initiate Takhmeen Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form method="post" action="<?= base_url('payment/ccavenue_takhmeen'); ?>">
          <div class="modal-body">
            <div class="form-group mb-2">
              <label for="admin_its_id">ITS ID (optional)</label>
              <input type="text" id="admin_its_id" name="its_id" class="form-control" placeholder="Enter ITS ID to associate payment" />
            </div>
            <div class="form-group mb-2">
              <label for="admin_pay_amount">Amount (₹)</label>
              <input type="number" step="0.01" min="0.01" id="admin_pay_amount" name="amount" class="form-control" required />
            </div>
            <input type="hidden" name="order_id" value="ADMIN-TAKHMEEN-<?= date('YmdHis'); ?>" />
            <input type="hidden" name="takhmeen_year" value="<?= htmlspecialchars($hijri_year); ?>" />
            <div class="form-text text-muted">Provide ITS ID to reconcile this payment with a member (optional).</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Proceed to Pay</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
