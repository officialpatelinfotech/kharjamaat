<?php
$summary = isset($user_summary) ? $user_summary : null;
$records = isset($records) ? $records : [];
$payments = isset($payments) ? $payments : [];
$selectedYear = $selected_hijri_year ?? '';
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo site_url('common/fmb_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="container mt-3">
    <h4 class="heading text-center mb-3">FMB Details<?= $summary && !empty($summary['full_name']) ? ': ' . htmlspecialchars($summary['full_name']) : '' ?></h4>

    <?php if ($summary): ?>
      <div class="row mb-3">
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Total Takhmeen</div>
            <div class="h4 text-primary">₹<?= number_format((float)($summary['total_takhmeen'] ?? 0)) ?></div>
          </div>
        </div>
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Total Paid</div>
            <div class="h4 text-success">₹<?= number_format((float)($summary['total_paid'] ?? 0)) ?></div>
          </div>
        </div>
        <div class="col-md-4 mb-2">
          <div class="card p-3 text-center">
            <div>Outstanding</div>
            <div class="h4 text-danger">₹<?= number_format(max(0, (float)($summary['outstanding'] ?? 0))) ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <h5 class="mt-3">Takhmeen Records<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h5>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Year</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($records as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r['year'] ?? '') ?></td>
                  <td>₹<?= number_format((float)($r['total_amount'] ?? 0)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-12">
        <h5 class="mt-3">Payments</h5>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payments as $p): ?>
                <tr>
                  <td><?= htmlspecialchars($p['payment_date'] ?? '') ?></td>
                  <td>₹<?= number_format((float)($p['amount'] ?? 0)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
