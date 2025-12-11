<?php
$summary = isset($summary) ? $summary : [];
$totalFmb = (float)($summary['total_fmb_takhmeen_amount'] ?? 0);
$totalOutstanding = (float)($summary['total_outstanding_fmb_amount'] ?? 0);
$sectorBreakdown = isset($summary['sector_breakdown']) ? $summary['sector_breakdown'] : [];
$years = isset($hijri_years) ? $hijri_years : [];

// Ensure we always have a current Hijri year selected
$selectedYear = isset($selected_hijri_year) && $selected_hijri_year !== ''
  ? $selected_hijri_year
  : (is_array($years) && !empty($years) ? $years[0] : '');

// Prefer backend-provided year total, else derive from sector breakdown
$yearTotal = 0;
  // Compute year outstanding if provided by backend or derivable
  $yearOutstanding = null;
  $yearOutstandingKnown = null; // outstanding excluding unknown sectors (model may provide)
if (!empty($selectedYear)) {
  if (isset($summary['year_total_fmb_takhmeen_amount'])) {
    $yearTotal = (float)$summary['year_total_fmb_takhmeen_amount'];
  }
  // If model provided known-sector outstanding, use it
  if (isset($summary['year_outstanding_known_fmb_amount'])) {
    $yearOutstandingKnown = (float)$summary['year_outstanding_known_fmb_amount'];
  }
  if (isset($summary['year_outstanding_fmb_amount'])) {
    $yearOutstanding = (float)$summary['year_outstanding_fmb_amount'];
  }
  // If we have paid figure, derive outstanding: outstanding = total - paid
  if ($yearOutstanding === null && isset($summary['year_total_paid_fmb_amount'])) {
    $yearOutstanding = max(0, (float)$yearTotal - (float)$summary['year_total_paid_fmb_amount']);
  }
  if ($yearTotal <= 0 && !empty($sectorBreakdown)) {
    // Fallback: sum sector totals for the selected year breakdown provided
    $sum = 0;
    foreach ($sectorBreakdown as $row) {
      $sum += (float)($row['sector_total'] ?? 0);
    }
    $yearTotal = $sum;
  }
}
?>
<?php
// Local INR formatter: Indian grouping (lakhs/crores)
if (!function_exists('format_inr')) {
  function format_inr($number, $decimals = 0) {
    $number = round((float)$number, $decimals);
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $parts = explode('.', number_format($number, $decimals, '.', ''));
    $int = $parts[0];
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '') {
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',';
    } else {
      $rest = '';
    }
    $formatted = $sign . $rest . $last3;
    if ($decimals > 0) {
      $formatted .= '.' . str_pad($parts[1] ?? '0', $decimals, '0');
    }
    return $formatted;
  }
}
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo base_url($active_controller); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="container mt-4">
    <h4 class="heading text-center mb-3">FMB Thaali Takhmeen<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h4>

    <div class="row mb-4">
      <div class="col-md-6 mb-2">
        <div class="card p-3 text-center">
          <h5>Total FMB Takhmeen</h5>
          <div class="display-6 text-primary">₹<?= format_inr($yearTotal) ?></div>
          <div class="mt-2">
            <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('common/fmb_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">View user details</a>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-2">
        <div class="card p-3 text-center">
          <h5>Outstanding FMB</h5>
          <div class="display-6 text-danger">
            <?php if ($yearOutstandingKnown !== null): ?>
              ₹<?= format_inr($yearOutstandingKnown) ?>
            <?php elseif ($yearOutstanding !== null): ?>
              ₹<?= format_inr($yearOutstanding) ?>
            <?php endif; ?>
          </div>
          <div class="mt-2">
            <a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('common/fmb_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">Drill down</a>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-3">

    <form method="get" action="<?php echo site_url('common/fmbtakhmeen'); ?>" class="row g-2 align-items-center mb-3" id="yearFilterForm">
      <?php if (!empty($from)): ?>
        <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
      <?php endif; ?>
      <div class="col-12 col-sm-auto">
        <div class="d-flex align-items-center">
          <label for="hijri_year" class="form-label me-2 mr-2 mb-0" style="white-space: nowrap;">Hijri&nbsp;Year</label>
          <select name="hijri_year" id="hijri_year" class="form-control form-select" style="min-width: 160px;" onchange="document.getElementById('yearFilterForm').submit();">
            <?php foreach ($years as $y): ?>
              <option value="<?php echo htmlspecialchars($y); ?>" <?php echo ($selectedYear == $y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>

    <?php if (!empty($selectedYear) && !empty($sectorBreakdown)): ?>
        <h4 class="mt-4 mb-2">Sector-wise</h4>
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                  <th>Sector</th>
                  <th>Total FMB Takhmeen</th>
                  <th>Due</th>
                  <th>HOF Member Count</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($sectorBreakdown as $row):
                $secname = isset($row['sector']) ? trim((string)$row['sector']) : '';
                if ($secname === '' || strcasecmp($secname, 'unknown') === 0) continue; // skip Unknown or empty sectors
              ?>
                <tr>
                  <td>
                    <a href="<?= site_url('common/fmb_sector_details') . '?hijri_year=' . urlencode($selectedYear) . '&sector=' . urlencode($secname); ?>">
                      <?= htmlspecialchars($secname) ?>
                    </a>
                  </td>
                  <td><?= '₹' . format_inr((float)($row['sector_total'] ?? 0)) ?></td>
                  <td class="text-danger"><?= '₹' . format_inr((float)($row['sector_due'] ?? 0)) ?></td>
                  <td><?= (int)($row['member_count'] ?? 0) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
    <?php endif; ?>
  </div>
</div>
