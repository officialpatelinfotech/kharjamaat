<?php
// Ekram Funds per-HOF view (mirrors CorpusFundsDetails)
?>
<div class="container margintopcontainer pt-5">
  <div class="d-flex align-items-center mt-5 mt-md-0 mb-3">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="text-center mb-3">Ekram Funds Details</h4>
  <?php
  $tot_assigned = isset($ekram_details['tot_assigned']) ? (float)$ekram_details['tot_assigned'] : 0;
  $tot_paid     = isset($ekram_details['tot_paid']) ? (float)$ekram_details['tot_paid'] : 0;
  $tot_due      = isset($ekram_details['tot_due']) ? (float)$ekram_details['tot_due'] : 0;
  ?>

  <div class="dashboard-card">
    <div class="card-header">
      <span>Summary</span>
    </div>
    <div class="card-body" style="height:auto;">
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

  <div class="dashboard-card">
    <div class="card-header">
      <span>Per Year</span>
    </div>
    <div class="card-body" style="height:auto;">
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th style="width:100px;">Sr. No</th>
              <th>Year</th>
              <th class="text-end">Assigned</th>
              <th class="text-end">Paid</th>
              <th class="text-end">Outstanding</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            foreach (($ekram_details['rows'] ?? []) as $r): ?>
              <tr>
                <td><?php echo $i++; ?></td>
                <?php
                $year = '';
                if (!empty($r['hijri_year'])) {
                  $year = (string)$r['hijri_year'];
                } else {
                  $title = $r['title'] ?? '';
                  if (preg_match('/(\d{3,4})/', $title, $m)) {
                    $year = $m[1];
                  } else {
                    $year = $title;
                  }
                }
                ?>
                <td><?php echo htmlspecialchars($year); ?></td>
                <td class="text-end">₹<?php echo format_inr_no_decimals((float)($r['amount_assigned'] ?? 0)); ?></td>
                <td class="text-end text-success">₹<?php echo format_inr_no_decimals((float)($r['amount_paid'] ?? 0)); ?></td>
                <td class="text-end text-danger">₹<?php echo format_inr_no_decimals((float)($r['amount_due'] ?? 0)); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>