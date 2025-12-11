<div class="container margintopcontainer pt-5">
  <div class="container pt-5">
    <a href="<?php echo base_url("accounts") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <h4 class="text-center mt-4">Sabeel Takhmeen Details</h4>

  <div class="container pt-3">
    <style>
      /* Increase readable font sizes for Latest Takhmeen card */
      .latest-takhmeen-card .h5,
      .latest-takhmeen-card .h4 {
        font-size: 1.6rem !important;
        font-weight: 700;
      }

      .latest-takhmeen-card .small,
      .latest-takhmeen-card .text-muted {
        font-size: 1rem !important;
      }

      .latest-takhmeen-card .card-body {
        padding-top: .9rem;
        padding-bottom: .9rem;
      }

      /* Totals cards slightly larger amounts */
      .totals-card .h4 {
        font-size: 1.5rem !important;
        font-weight: 700;
      }

      .totals-card .text-muted {
        font-size: 0.95rem;
      }

      /* Left side: make establishment/residential/total labels larger for readability */
      .latest-takhmeen-card .card-body>.d-flex>div:first-child h6 {
        font-size: 1.05rem;
      }

      .latest-takhmeen-card .card-body>.d-flex>div:first-child .small,
      .latest-takhmeen-card .card-body>.d-flex>div:first-child .text-muted {
        font-size: 1.05rem;
      }

      /* Right side (paid/due): make the numeric amounts slightly smaller */
      .latest-takhmeen-card .card-body .h5 {
        font-size: 1.05rem !important;
        font-weight: 700;
      }

      .latest-takhmeen-card .card-body .h5.text-success,
      .latest-takhmeen-card .card-body .h5.text-danger {
        line-height: 1;
      }

      /* Payment history vertical scroll container */
      .payment-history-scroll {
        max-height: 40vh;
        min-height: 180px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }

      .payment-history-scroll .table {
        margin-bottom: 0;
      }
    </style>
    <?php
    // `format_inr()` is provided by the autoloaded `inr_helper`.
    // Null-safe helpers for amounts
    $overall = isset($sabeel_takhmeen_details['overall']) && is_array($sabeel_takhmeen_details['overall']) ? $sabeel_takhmeen_details['overall'] : [];
    $ov_total_due = (float)($overall['total_due'] ?? 0);
    $ov_total_paid = (float)($overall['total_paid'] ?? 0);
    $ov_total_amount = (float)($overall['total_amount'] ?? 0);
    ?>
    <div class="row g-3 mb-4">
      <div class="col-12">
        <div class="card shadow-sm text-center h-100">
          <div class="card-body py-3">
            <h6 class="text-muted mb-2">All Year Sabeel Due</h6>
            <div class="h4 text-danger mb-0">&#8377;<?php echo format_inr(round($ov_total_due), 0); ?></div>
          </div>
        </div>
      </div>

    </div>

    <?php
    // Show Current Year Takhmeen card (replace latest)
    // Derive current Hijri composite year using HijriCalendar model
    $CI = &get_instance();
    $CI->load->model('HijriCalendar');
    $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    $hy = isset($parts['hijri_year']) ? (int)$parts['hijri_year'] : 0;
    $hm = isset($parts['hijri_month']) ? (int)$parts['hijri_month'] : 0;
    if ($hy === 0) {
      $hd = $CI->HijriCalendar->get_hijri_date(date('Y-m-d'));
      if ($hd && isset($hd['hijri_date'])) {
        $p = explode('-', $hd['hijri_date']); // d-m-Y
        if (count($p) === 3) {
          $hm = (int)$p[1];
          $hy = (int)$p[2];
        }
      }
    }
    $currentCompositeYear = '';
    if ($hy > 0) {
      if ($hm >= 9) {
        $sy = $hy;
        $ey = $hy + 1;
      } else {
        $sy = $hy - 1;
        $ey = $hy;
      }
      $currentCompositeYear = $sy . '-' . substr((string)$ey, -2);
    }
    // Find matching rows for current year in establishment/residential arrays
    $cy_est_total = null;
    $cy_res_year_total = null;
    $cy_total = null;
    $cy_paid = null;
    $cy_due = null;
    if (!empty($sabeel_takhmeen_details['e_takhmeen']) && is_array($sabeel_takhmeen_details['e_takhmeen'])) {
      foreach ($sabeel_takhmeen_details['e_takhmeen'] as $row) {
        if (isset($row['year']) && (string)$row['year'] === $currentCompositeYear) {
          $cy_est_total = (float)($row['total'] ?? 0);
          $e_paid = (float)($row['paid'] ?? 0);
          $e_due  = (float)($row['due'] ?? max(0, $cy_est_total - $e_paid));
          // accumulate into overall paid/due
          $cy_paid = ($cy_paid ?? 0) + $e_paid;
          $cy_due  = ($cy_due  ?? 0) + $e_due;
          break;
        }
      }
    }
    if (!empty($sabeel_takhmeen_details['r_takhmeen']) && is_array($sabeel_takhmeen_details['r_takhmeen'])) {
      foreach ($sabeel_takhmeen_details['r_takhmeen'] as $row) {
        if (isset($row['year']) && (string)$row['year'] === $currentCompositeYear) {
          $cy_res_year_total = (float)($row['total'] ?? 0);
          $r_paid = (float)($row['paid'] ?? 0);
          $r_due  = (float)($row['due'] ?? max(0, $cy_res_year_total - $r_paid));
          $cy_paid = ($cy_paid ?? 0) + $r_paid;
          $cy_due  = ($cy_due  ?? 0) + $r_due;
          break;
        }
      }
    }
    if ($cy_est_total !== null || $cy_res_year_total !== null) {
      $cy_total = (float)($cy_est_total + $cy_res_year_total);
      ?>
      <div class="mb-2"><h6 class="text-muted m-0">Current Year: <?php echo htmlspecialchars($currentCompositeYear); ?></h6></div>
      <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
          <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
              <h6 class="text-muted mb-2">Takhmeen</h6>
              <div class="h4 text-primary mb-0">&#8377;<?php echo format_inr(round($cy_total), 0); ?></div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
              <h6 class="text-muted mb-2">Paid</h6>
              <div class="h4 text-success mb-0">&#8377;<?php echo format_inr(round($cy_paid ?? 0), 0); ?></div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card shadow-sm text-center h-100">
            <div class="card-body py-3">
              <h6 class="text-muted mb-2">Pending</h6>
              <div class="h4 text-danger mb-0">&#8377;<?php echo format_inr(round(max(0, ($cy_total ?? 0) - ($cy_paid ?? 0))), 0); ?></div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Establishment Sabeel Takhmeen List</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive payment-history-scroll">
          <table class="table table-striped table-hover mb-0">
            <tr class="thead-dark">
              <th>Year</th>
              <th>Grade</th>
              <th>Total</th>
              <th>Paid</th>
              <th>Due</th>
            </tr>
            <?php foreach ($sabeel_takhmeen_details["e_takhmeen"] as $row): ?>
              <?php
              $eyear = isset($row['year']) ? htmlspecialchars((string)$row['year']) : '';
              $egrade = isset($row['grade']) && $row['grade'] !== null && trim($row['grade']) !== '' ? htmlspecialchars($row['grade']) : '—';
              $etotal = (float)($row['total'] ?? 0);
              $epaid  = (float)($row['paid'] ?? 0);
              $edue   = (float)($row['due'] ?? max(0, $etotal - $epaid));
              ?>
              <tr>
                <td><?php echo $eyear; ?></td>
                <td><?php echo $egrade; ?></td>
                <td class="text-primary">&#8377;<?php echo format_inr($etotal, 0); ?></td>
                <td class="text-success">&#8377;<?php echo format_inr($epaid, 0); ?></td>
                <td class="text-danger">&#8377;<?php echo format_inr($edue, 0); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Residential Sabeel Takhmeen List</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive takhmeen-scroll">
          <table class="table table-striped table-hover mb-0">
            <tr class="thead-dark">
              <th>Year</th>
              <th>Grade</th>
              <th>Total</th>
              <th>Paid</th>
              <th>Due</th>
            </tr>
            <?php foreach ($sabeel_takhmeen_details["r_takhmeen"] as $row): ?>
              <?php
              $ryear = isset($row['year']) ? htmlspecialchars((string)$row['year']) : '';
              $rgrade = isset($row['grade']) && $row['grade'] !== null && trim($row['grade']) !== '' ? htmlspecialchars($row['grade']) : '—';
              $rtotal = (float)($row['total'] ?? 0);
              $rpaid  = (float)($row['paid'] ?? 0);
              $rdue   = (float)($row['due'] ?? max(0, $rtotal - $rpaid));
              ?>
              <tr>
                <td><?php echo $ryear; ?></td>
                <td><?php echo $rgrade; ?></td>
                <td class="text-primary">&#8377;<?php echo format_inr($rtotal, 0); ?></td>
                <td class="text-success">&#8377;<?php echo format_inr($rpaid, 0); ?></td>
                <td class="text-danger">&#8377;<?php echo format_inr($rdue, 0); ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>

    <div class="card shadow-sm rounded-3 my-4">
      <div class="card-header bg-light">
        <h5 class="mb-0">Payment History</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive takhmeen-scroll">
          <table class="table table-striped table-hover mb-0">
            <thead class="thead-dark">
              <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Type</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($sabeel_takhmeen_details['all_payments'])): ?>
                <?php foreach ($sabeel_takhmeen_details['all_payments'] as $pay): ?>
                  <?php
                  $pdate = isset($pay['payment_date']) && $pay['payment_date'] ? date('d-M-Y', strtotime($pay['payment_date'])) : '-';
                  $pamount = (float)($pay['amount'] ?? 0);
                  $pmethod = isset($pay['payment_method']) ? htmlspecialchars((string)$pay['payment_method']) : '';
                  $ptype = isset($pay['type']) ? htmlspecialchars(ucfirst((string)$pay['type'])) : '';
                  $premarks = isset($pay['remarks']) ? htmlspecialchars((string)$pay['remarks']) : '';
                  ?>
                  <tr>
                    <td><?php echo $pdate ?></td>
                    <td class="text-success"><?php echo '&#8377;' . format_inr(round($pamount), 0) ?></td>
                    <td><?php echo $pmethod ?></td>
                    <td><?php echo $ptype; ?></td>
                    <td><?php echo $premarks ?></td>
                    <td>
                      <button class="view-invoice btn btn-sm btn-primary" data-payment-id="<?php echo $pay['id'] ?>">View Invoice</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">No payments found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 2,
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(response) {
        var blob = new Blob([response], {
          type: "application/pdf"
        });
        var url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
      },
      error: function() {
        alert("Failed to generate invoice PDF");
      }
    });
  });
</script>