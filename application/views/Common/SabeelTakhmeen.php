<?php
$report = isset($takhmeens) ? $takhmeens : [];
$gradeBreakdown = $report['grade_wise_breakdown'] ?? [];
$estBreakdown = $report['establishment_grade_breakdown'] ?? [];
$resBreakdown = $report['residential_grade_breakdown'] ?? [];
$totalSabeel = (float)($report['total_sabeel_takhmeen_amount'] ?? 0);
$totalOutstandingCumulative = (float)($report['total_outstanding_sabeel_amount'] ?? 0);
$yearOutstanding = (float)($report['year_outstanding_sabeel_amount'] ?? 0);
$yearPaid = (float)($report['year_paid_sabeel_amount'] ?? 0);
$gradeCounts = $report['grade_wise_mumineen_count'] ?? [];
$sectorBreakdown = $report['sector_breakdown'] ?? [];
$selectedYear = isset($selected_hijri_year) ? $selected_hijri_year : '';
$years = isset($hijri_years) ? $hijri_years : [];
// Default to current financial Hijri year on initial load: pick latest available year
if ($selectedYear === '' && !empty($years)) {
  // Prefer entries already in financial-year format like "1446-47"
  $rangeYears = array_filter($years, function($y) {
    return is_string($y) && preg_match('/^\d{4}-\d{2}$/', $y);
  });
  if (!empty($rangeYears)) {
    // Sort by the starting year numerically and pick the latest
    usort($rangeYears, function($a, $b) {
      $sa = (int)substr($a, 0, 4);
      $sb = (int)substr($b, 0, 4);
      return $sa <=> $sb;
    });
    $selectedYear = end($rangeYears);
  } else {
    // Fallback: derive financial year from the latest single year in the list
    $last = end($years);
    reset($years);
    if (is_numeric($last)) {
      $start = (int)$last - 1;
      $endTwo = ((int)$last) % 100;
      $selectedYear = sprintf('%d-%02d', $start, $endTwo);
    } else {
      $selectedYear = (string)$last; // last resort
    }
  }
}
?>
<?php
// Small helper to format numbers in Indian (INR) grouping: 1,23,45,678
if (!function_exists('format_inr')) {
  function format_inr($number, $decimals = 0) {
    $number = round((float)$number, $decimals);
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $parts = explode('.', number_format($number, $decimals, '.', ''));
    $int = $parts[0];
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '' ) {
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
// Explicit helper to enforce no decimals consistently
if (!function_exists('format_inr_no_decimals')) {
  function format_inr_no_decimals($number) {
    return format_inr((int)round((float)$number), 0);
  }
}
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo base_url($active_controller); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="container mt-4">
    <h4 class="heading text-center mb-3">Sabeel Takhmeen Summary<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h4>
    <div class="row mb-4">
      <div class="col-md-4 mb-2">
        <div class="card p-3 text-center">
          <h5>Total Sabeel Takhmeen</h5>
          <div class="display-6 text-primary">
            ₹<?= format_inr_no_decimals($totalSabeel) ?>
          </div>
          <div class="mt-2">
            <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('common/sabeel_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">View user details</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-2">
        <div class="card p-3 text-center">
          <h5>Paid (Selected Year)</h5>
          <div class="display-6 text-success">
            ₹<?= format_inr_no_decimals($yearPaid) ?>
          </div>
          <div class="mt-2">
            <a class="btn btn-sm btn-outline-success" href="<?php echo site_url('common/sabeel_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">Drill down</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-2">
        <div class="card p-3 text-center">
          <h5>Due (Selected Year)</h5>
          <div class="display-6 text-danger">
            ₹<?= format_inr_no_decimals($yearOutstanding) ?>
          </div>
          <div class="mt-2">
            <a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('common/sabeel_users') . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">Drill down</a>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-3">

    <form method="get" action="<?php echo site_url('common/sabeeltakhmeen'); ?>" class="row g-2 align-items-center mb-3" id="yearFilterForm">
      <?php if (!empty($from)): ?>
        <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
      <?php endif; ?>
      <div class="col-12 col-sm-auto">
        <div class="d-flex align-items-center">
              <label for="hijri_year" class="form-label me-2 mr-2 mb-0" style="white-space: nowrap;">Hijri&nbsp;Year</label>
          <select name="hijri_year" id="hijri_year" class="form-control form-select" style="min-width: 160px;" onchange="document.getElementById('yearFilterForm').submit();">
            <?php foreach ($years as $y): ?>
              <?php
                $isRange = is_string($y) && preg_match('/^\d{4}-\d{2}$/', $y);
                if ($isRange) {
                  $optVal = (string)$y;
                  $optLabel = (string)$y;
                } else {
                  $ys = (int)$y;
                  $optVal = sprintf('%d-%02d', $ys, ($ys + 1) % 100);
                  $optLabel = $optVal;
                }
              ?>
              <option value="<?php echo htmlspecialchars($optVal); ?>" <?php echo ($selectedYear == $optVal) ? 'selected' : ''; ?>><?php echo htmlspecialchars($optLabel); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>

    <?php if (empty($selected_hijri_year)): ?>
      <script>
        (function() {
          var sel = document.getElementById('hijri_year');
          if (!sel) return;
          // If PHP computed a default $selectedYear, set it client-side and submit to load data
          var defaultYear = <?php echo json_encode($selectedYear); ?>;
          if (defaultYear && (!sel.value || sel.value !== defaultYear)) {
            sel.value = defaultYear;
            // Submit the form to fetch server-side data for the selected year
            var form = document.getElementById('yearFilterForm');
            if (form) form.submit();
          }
        })();
      </script>
    <?php endif; ?>

    <?php if (!empty($selectedYear) && empty($estBreakdown) && empty($resBreakdown)): ?>
      <div class="alert alert-warning" role="alert">No takhmeen done for this year.</div>
    <?php endif; ?>

    <h4 class="mt-4 mb-2">Establishment Grades</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Grade</th>
            <th>Establishment Takhmeen</th>
            <th>Due</th>
            <th>Member Count</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($estBreakdown as $row): 
              $g = isset($row['grade']) ? trim((string)$row['grade']) : '';
              $amt = isset($row['est_total']) ? (float)$row['est_total'] : (isset($row['est_total']) ? (float)$row['est_total'] : 0.0);
              $due_amt = isset($row['est_due']) ? (float)$row['est_due'] : 0.0;
              if ($g === '' || strcasecmp($g,'no grade') === 0 || strcasecmp($g,'unknown') === 0) continue; // skip unknown grade
              if ($amt <= 0 && $due_amt <= 0) continue; // skip zero amount rows
            ?>
            <tr>
              <td><?= htmlspecialchars($g) ?></td>
              <td><?= '₹' . format_inr_no_decimals($amt) ?></td>
              <td class="text-danger"><?= '₹' . format_inr_no_decimals($due_amt) ?></td>
              <td><?= isset($row['member_count']) ? (int)$row['member_count'] : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <h4 class="mt-4 mb-2">Residential Grades</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Grade</th>
            <th>Residential Takhmeen</th>
            <th>Due</th>
            <th>Member Count</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resBreakdown as $row): 
              $g = isset($row['grade']) ? trim((string)$row['grade']) : '';
              $amt = isset($row['res_total']) ? (float)$row['res_total'] : 0.0;
              $due_amt = isset($row['res_due']) ? (float)$row['res_due'] : 0.0;
              if ($g === '' || strcasecmp($g,'no grade') === 0 || strcasecmp($g,'unknown') === 0) continue;
              if ($amt <= 0 && $due_amt <= 0) continue;
            ?>
            <tr>
              <td><?= htmlspecialchars($g) ?></td>
              <td><?= '₹' . format_inr_no_decimals($amt) ?></td>
              <td class="text-danger"><?= '₹' . format_inr_no_decimals($due_amt) ?></td>
              <td><?= isset($row['member_count']) ? (int)$row['member_count'] : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <h4 class="mt-4 mb-2">Sector-wise</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Sector</th>
            <th>Total Sabeel Takhmeen</th>
            <th>Due</th>
            <th>Member Count</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sectorBreakdown as $row): 
              $sector = isset($row['sector']) ? trim((string)$row['sector']) : '';
              $amt = isset($row['sector_total']) ? (float)$row['sector_total'] : 0.0;
              $due_amt = isset($row['sector_due']) ? (float)$row['sector_due'] : 0.0;
              if ($sector === '' || strcasecmp($sector,'unknown') === 0) continue;
              if ($amt <= 0 && $due_amt <= 0) continue;
            ?>
            <tr>
              <td><?= htmlspecialchars($sector) ?></td>
              <td><?= '₹' . format_inr_no_decimals($amt) ?></td>
              <td class="text-danger"><?= '₹' . format_inr_no_decimals($due_amt) ?></td>
              <td><?= isset($row['member_count']) ? (int)$row['member_count'] : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>