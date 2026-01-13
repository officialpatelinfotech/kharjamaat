<style>
  .member-summary-wrap {
    max-width: 980px;
  }

  .member-summary-title {
    font-weight: 700;
    letter-spacing: 0.3px;
    line-height: 1.6;
    margin-bottom: 1.5rem;
  }

  .member-summary-card {
    border-radius: 12px;
  }

  .member-summary-card .card-header {
    background: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    padding: 12px 16px;
    font-weight: 600;
  }

  .member-summary-card .mini-card {
    padding: 10px 8px;
  }

  .member-summary-card .stats-value {
    font-weight: 800;
    font-size: 1.6rem;
    line-height: 1.1;
    margin-bottom: 6px;
  }

  .member-summary-card .stats-label {
    font-weight: 600;
    color: #444;
    letter-spacing: 0.2px;
  }

  @media (max-width: 576px) {
    .member-summary-card .stats-value {
      font-size: 1.35rem;
    }
  }
</style>

<div class="container margintopcontainer pt-5 member-summary-wrap">
  <div class="d-flex align-items-center mb-3">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="text-center mb-3 member-summary-title">Qardan Hasana Details</h4>
  <?php
  $tot_assigned = isset($qardan_hasana['amount']) ? (float)$qardan_hasana['amount'] : 0;
  $tot_due      = isset($qardan_hasana['due']) ? (float)$qardan_hasana['due'] : null;
  $tot_paid     = null;

  // Prefer explicit paid field if present; otherwise infer from amount-due
  foreach (['paid', 'amount_paid', 'paid_amount', 'total_paid'] as $k) {
    if (isset($qardan_hasana[$k]) && $qardan_hasana[$k] !== '' && $qardan_hasana[$k] !== null) {
      $tot_paid = (float)$qardan_hasana[$k];
      break;
    }
  }
  if ($tot_paid === null) {
    $tot_paid = max(0, $tot_assigned - (float)($tot_due ?? 0));
  }
  if ($tot_due === null) {
    $tot_due = max(0, $tot_assigned - $tot_paid);
  }

  $lastUpdatedTs = null;
  if (!empty($qardan_hasana) && is_array($qardan_hasana)) {
    foreach (['updated_at', 'updated_on', 'last_updated', 'modified_at', 'modified_on', 'created_at', 'created_on', 'created_date', 'date', 'created'] as $k) {
      if (!empty($qardan_hasana[$k])) {
        $ts = strtotime((string)$qardan_hasana[$k]);
        if ($ts) {
          $lastUpdatedTs = $ts;
          break;
        }
      }
    }
  }
  $lastUpdatedText = $lastUpdatedTs ? date('d-m-Y H:i', $lastUpdatedTs) : '—';
  ?>

  <div class="card dashboard-card member-summary-card border border-secondary shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Summary</span>
      <small class="text-muted">Last Updated: <?php echo htmlspecialchars($lastUpdatedText); ?></small>
    </div>
    <div class="card-body" style="height:auto; padding: 18px 16px;">
      <div class="row text-center">
        <div class="col-4">
          <div class="mini-card">
            <div class="stats-value text-dark">₹<?php echo format_inr_no_decimals($tot_assigned); ?></div>
            <div class="stats-label">Total Assigned</div>
          </div>
        </div>
        <div class="col-4">
          <div class="mini-card">
            <div class="stats-value text-success">₹<?php echo format_inr_no_decimals($tot_paid); ?></div>
            <div class="stats-label">Total Paid</div>
          </div>
        </div>
        <div class="col-4">
          <div class="mini-card">
            <div class="stats-value text-danger">₹<?php echo format_inr_no_decimals($tot_due); ?></div>
            <div class="stats-label">Outstanding</div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
