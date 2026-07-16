<?php
if (!function_exists('inr_digits')) {
  function inr_digits($num)
  {
    if ($num === null || $num === '' || !is_numeric($num)) return '';
    $neg = $num < 0;
    $num = abs(floor($num));
    $str = (string)$num;
    $len = strlen($str);
    if ($len <= 3) return ($neg ? '-' : '') . $str;
    $last3 = substr($str, -3);
    $rest = substr($str, 0, $len - 3);
    $rest_rev = strrev($rest);
    $rest_groups = str_split($rest_rev, 2);
    $rest_formatted = strrev(implode(',', $rest_groups));
    return ($neg ? '-' : '') . $rest_formatted . ',' . $last3;
  }
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --red:         #ef4444;
    --radius-sm:   8px;
    --radius:      12px;
    --radius-lg:   16px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.05);
    --shadow:      0 4px 16px rgba(184,134,11,0.06);
    --shadow-lg:   0 10px 30px rgba(184,134,11,0.12);
  }

  .page-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
  }

  /* Page Header and Back button */
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    position: relative;
  }
  .btn-back-nav {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    box-shadow: var(--shadow-sm);
    transition: all .2s;
    text-decoration: none !important;
  }
  .btn-back-nav:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold-deep);
    transform: translateX(-3px);
  }
  .page-title {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold-deep);
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
    letter-spacing: -.5px;
  }

  /* Filters Card */
  .filter-card {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 28px;
    box-shadow: var(--shadow);
  }
  .filter-card .card-body {
    padding: 24px;
  }
  .filter-card .form-label {
    font-weight: 700;
    color: var(--text-2);
    font-size: 0.72rem;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .filter-card .form-control {
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    font-size: 0.88rem;
    height: 42px;
    transition: all 0.2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .filter-card .form-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12);
    outline: none;
  }
  .filter-card select.form-control {
    padding-right: 32px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    appearance: none;
    -webkit-appearance: none;
  }
  .btn-filter {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    border: none;
    color: #fff;
    font-weight: 700;
    height: 42px;
    border-radius: var(--radius-sm);
    transition: all .2s;
  }
  .btn-filter:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
    color: #fff;
  }
  .btn-clear {
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-weight: 700;
    height: 42px;
    border-radius: var(--radius-sm);
    transition: all .2s;
  }
  .btn-clear:hover {
    background: var(--surface-2);
    color: var(--text-1);
  }

  /* Table Card */
  .table-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
  }
  .premium-table {
    margin-bottom: 0;
  }
  .premium-table thead th {
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 18px;
    vertical-align: middle;
  }
  .premium-table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    color: var(--text-2);
    font-size: 0.85rem;
    vertical-align: middle;
  }

  .hidden {
    display: none;
  }

  .all-years-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 14px;
    margin-top: 4px;
  }

  .all-years-badge.est {
    background: linear-gradient(135deg, #ffb347, #ff7b00);
    color: #222;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.15);
  }

  .all-years-badge.res {
    background: linear-gradient(135deg, #7f9cff, #3456d1);
    color: #fff;
    box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.25);
  }

  .all-years-badge.zero {
    background: #28a745;
    color: #fff;
  }

  .all-years-badge .label {
    opacity: 0.85;
    font-weight: 500;
  }

  .all-years-badge .value {
    font-weight: 700;
    margin-left: 2px;
  }

  .due-wrapper {
    line-height: 1.15;
  }

  .due-wrapper small {
    display: block;
  }

  .glow-pulse {
    position: relative;
  }

  .glow-pulse::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    animation: glowPulse 2.2s ease-in-out infinite;
  }

  @keyframes glowPulse {
    0% {
      box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.5);
    }

    70% {
      box-shadow: 0 0 0 8px rgba(255, 255, 255, 0);
    }

    100% {
      box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
  }

  /* Sortable headers */
  th.km-sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
  }

  th.km-sortable .sort-indicator {
    font-size: 11px;
    margin-left: 4px;
    color: #666;
  }

  th.km-sortable[data-sort-dir="asc"] .sort-indicator,
  th.km-sortable[data-sort-dir="desc"] .sort-indicator {
    color: var(--gold);
  }

  /* Distinct colors for current-year dues */
  .est-due {
    color: var(--red);
    font-weight: 600;
  }

  .res-due {
    color: var(--red);
    font-weight: 600;
  }

  .mut-due {
    color: var(--red);
    font-weight: 600;
  }

  .due-wrapper .label-prefix {
    opacity: 0.8;
    font-weight: 500;
    margin-right: 2px;
  }

  /* Scrollable table with sticky header (Sabeel dashboard) */
  .table-scroll-fixed {
    max-height: calc(100vh - 180px);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    background: var(--surface);
    margin-bottom: 30px;
  }

  .table-scroll-fixed table {
    width: 100%;
    border-collapse: collapse;
  }

  .table-scroll-fixed thead tr:first-child th {
    position: sticky;
    top: 0;
    z-index: 4;
    background: var(--surface-2) !important;
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Prevent small year text from wrapping onto two lines */
  .table-scroll-fixed td small {
    white-space: nowrap;
  }

  .table-scroll-fixed thead tr:nth-child(2) th {
    position: sticky;
    top: 38px;
    /* offset to sit below the first header row */
    z-index: 3;
    background: var(--surface-2) !important;
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Center Grade columns (Establishment and Residential) */
  .table-scroll-fixed thead tr:nth-child(2) th:nth-child(5),
  .table-scroll-fixed thead tr:nth-child(2) th:nth-child(9),
  .table-scroll-fixed tbody td:nth-child(5),
  .table-scroll-fixed tbody td:nth-child(9) {
    text-align: center;
    vertical-align: middle;
  }

  /* Center Action column (last column) both horizontally and vertically */
  .table-scroll-fixed thead th:last-child,
  .table-scroll-fixed tbody td:last-child {
    text-align: center;
    vertical-align: middle;
  }

  /* Thick vertical separators around column groups */
  .table-scroll-fixed table th,
  .table-scroll-fixed table td {
    border-color: var(--border);
  }

  /* Member Info group: cols 1-4 */
  .table-scroll-fixed table th:first-child,
  .table-scroll-fixed table td:first-child {
    border-left: 2px solid var(--text-2);
  }

  .table-scroll-fixed table th:nth-child(4),
  .table-scroll-fixed table td:nth-child(4) {
    border-right: 2px solid var(--text-2);
  }

  /* Establishment group: cols 5-8 */
  .table-scroll-fixed table th:nth-child(5),
  .table-scroll-fixed table td:nth-child(5) {
    border-left: 2px solid var(--text-2);
  }

  .table-scroll-fixed table th:nth-child(8),
  .table-scroll-fixed table td:nth-child(8) {
    border-right: 2px solid var(--text-2);
  }

  /* Residential group: cols 9-12 */
  .table-scroll-fixed table th:nth-child(9),
  .table-scroll-fixed table td:nth-child(9) {
    border-left: 2px solid var(--text-2);
  }

  .table-scroll-fixed table th:nth-child(12),
  .table-scroll-fixed table td:nth-child(12) {
    border-right: 2px solid var(--text-2);
  }
  
  tr.clickable-row {
    cursor: pointer;
  }
  tr.clickable-row:hover td {
    background-color: #fdfbf5 !important;
  }
</style>

<div class="page-wrap container-fluid px-md-5 margintopcontainer pt-5 pb-5">
  
  <!-- Header -->
  <div class="page-header">
    <a href="<?php echo base_url("anjuman"); ?>" class="btn-back-nav" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 class="page-title">Receive Sabeel Payments</h1>
    <div style="width: 42px;"></div>
  </div>

  <!-- Filters Card -->
  <div class="filter-card">
    <div class="card-body">
      <form method="POST" action="<?php echo base_url("anjuman/filteruserinsabeeltakhmeen"); ?>" class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="form-label">Name or ITS</label>
          <input type="text" name="member_name" id="member-name" class="form-control" placeholder="Name or ITS" value="<?php echo isset($member_name) ? htmlspecialchars($member_name) : ''; ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Hijri Financial Year</label>
          <select name="sabeel_year" id="sabeel-year" class="form-control">
            <?php
            $years = isset($hijri_years) && is_array($hijri_years) ? $hijri_years : [];
            $sel = isset($selected_year) ? $selected_year : (isset($current_year) ? $current_year : '');
            if (!empty($years)) {
              foreach ($years as $y) {
                $isSel = ($sel === $y) ? 'selected' : '';
                echo '<option value=' . htmlspecialchars($y) . ' ' . $isSel . '>' . htmlspecialchars($y) . '</option>';
              }
            } else if (!empty($sel)) {
              echo '<option value=' . htmlspecialchars($sel) . ' selected>' . htmlspecialchars($sel) . '</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-filter flex-fill mr-2"><i class="fa-solid fa-magnifying-glass me-1"></i> Submit</button>
          <a href="<?php echo base_url("anjuman/sabeeltakhmeendashboard"); ?>" class="btn btn-clear flex-fill d-inline-flex align-items-center justify-content-center">Clear</a>
        </div>
      </form>
    </div>
  </div>
  <?php
    $estGradeCounts = [];
    $resGradeCounts = [];
    $mutGradeCounts = [];
    $estGradeAmounts = [];
    $resGradeAmounts = [];
    $mutGradeAmounts = [];
    $allUniqueGrades = [];
    $estNoGradeCount = 0;
    $resNoGradeCount = 0;
    $mutNoGradeCount = 0;
    $estNoGradeAmount = 0.0;
    $resNoGradeAmount = 0.0;
    $mutNoGradeAmount = 0.0;
    $currentCompYear = isset($selected_year) ? $selected_year : (isset($current_year) ? $current_year : '');

    if (!empty($all_user_sabeel_takhmeen)) {
      foreach ($all_user_sabeel_takhmeen as $user) {
        $current = null;
        if (!empty($user['takhmeens']) && is_array($user['takhmeens'])) {
          foreach ($user['takhmeens'] as $tRow) {
            if (isset($tRow['year']) && $tRow['year'] === $currentCompYear) { $current = $tRow; break; }
          }
        }
        if (!$current && isset($user['current_year_takhmeen']) && isset($user['current_year_takhmeen']['year']) && $user['current_year_takhmeen']['year'] === $currentCompYear) {
          $current = $user['current_year_takhmeen'];
        }
        
        $estGrade = '';
        $resGrade = '';
        $mutGrade = '';
        $estYearly = 0.0;
        $resYearly = 0.0;
        $mutYearly = 0.0;
        if ($current) {
          $estGrade = isset($current['establishment']['grade']) ? trim((string)$current['establishment']['grade']) : '';
          $resGrade = isset($current['residential']['grade']) ? trim((string)$current['residential']['grade']) : '';
          $mutGrade = isset($current['mutawatteneen']['grade']) ? trim((string)$current['mutawatteneen']['grade']) : '';

          $estYearly = isset($current['establishment']['yearly']) ? (float)$current['establishment']['yearly'] : 0.0;
          $resYearly = isset($current['residential']['yearly']) ? (float)$current['residential']['yearly'] : 0.0;
          $mutYearly = isset($current['mutawatteneen']['yearly']) ? (float)$current['mutawatteneen']['yearly'] : 0.0;
        }
        
        if ($estGrade !== '' && strcasecmp($estGrade, 'no grade') !== 0 && strcasecmp($estGrade, 'unknown') !== 0) {
          $estGradeCounts[$estGrade] = ($estGradeCounts[$estGrade] ?? 0) + 1;
          $estGradeAmounts[$estGrade] = ($estGradeAmounts[$estGrade] ?? 0.0) + $estYearly;
          if (!in_array($estGrade, $allUniqueGrades)) {
            $allUniqueGrades[] = $estGrade;
          }
        } else {
          $estNoGradeCount++;
          $estNoGradeAmount += $estYearly;
        }
        
        if ($resGrade !== '' && strcasecmp($resGrade, 'no grade') !== 0 && strcasecmp($resGrade, 'unknown') !== 0) {
          $resGradeCounts[$resGrade] = ($resGradeCounts[$resGrade] ?? 0) + 1;
          $resGradeAmounts[$resGrade] = ($resGradeAmounts[$resGrade] ?? 0.0) + $resYearly;
          if (!in_array($resGrade, $allUniqueGrades)) {
            $allUniqueGrades[] = $resGrade;
          }
        } else {
          $resNoGradeCount++;
          $resNoGradeAmount += $resYearly;
        }

        if ($mutGrade !== '' && strcasecmp($mutGrade, 'no grade') !== 0 && strcasecmp($mutGrade, 'unknown') !== 0) {
          $mutGradeCounts[$mutGrade] = ($mutGradeCounts[$mutGrade] ?? 0) + 1;
          $mutGradeAmounts[$mutGrade] = ($mutGradeAmounts[$mutGrade] ?? 0.0) + $mutYearly;
          if (!in_array($mutGrade, $allUniqueGrades)) {
            $allUniqueGrades[] = $mutGrade;
          }
        } else {
          $mutNoGradeCount++;
          $mutNoGradeAmount += $mutYearly;
        }
      }
    }
    sort($allUniqueGrades);
  ?>
  <div class="row mb-4">
    <div class="col-12">
      <div class="table-card">
        <div class="p-3 bg-white border-bottom">
          <h6 class="font-weight-bold m-0" style="color: var(--gold-deep); font-size: 15px;"><i class="fa-solid fa-chart-simple mr-2" style="color: var(--gold);"></i> Sabeel Grade Wise Summary (<?php echo htmlspecialchars($currentCompYear); ?>)</h6>
        </div>
        <div class="card-body p-3">
          <div class="table-responsive">
            <table class="table premium-table table-sm table-hover table-bordered mb-0 text-center align-middle" style="font-size: 15px; border-radius: 8px; overflow: hidden;">
              <thead>
                <tr>
                  <th class="text-left font-weight-bold align-middle" style="background-color: #f8f9fa;">Sabeel Type</th>
                  <?php foreach ($allUniqueGrades as $g): ?>
                    <th class="font-weight-bold align-middle"><?php echo htmlspecialchars($g); ?></th>
                  <?php endforeach; ?>
                  <?php if ($estNoGradeCount > 0 || $resNoGradeCount > 0 || $mutNoGradeCount > 0): ?>
                    <th class="font-weight-bold align-middle" style="font-style: italic;">No Grade</th>
                  <?php endif; ?>
                  <th class="font-weight-bold align-middle" style="background-color: #e9ecef;">Total</th>
                </tr>
              </thead>
              <tbody>
                <!-- Establishment Row -->
                <tr>
                  <td class="text-left font-weight-bold align-middle" style="background-color: #fdf6e3; color: #6c4f00;">Establishment</td>
                  <?php 
                  $totEst = 0;
                  $totEstAmt = 0.0;
                  foreach ($allUniqueGrades as $g): 
                    $cEst = $estGradeCounts[$g] ?? 0;
                    $aEst = $estGradeAmounts[$g] ?? 0.0;
                    $totEst += $cEst;
                    $totEstAmt += $aEst;
                  ?>
                    <td style="background-color: #fffdf9;" class="align-middle">
                      <?php if ($cEst > 0): ?>
                        <strong><?php echo $cEst; ?></strong>
                        <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($aEst)); ?></span>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                  <?php if ($estNoGradeCount > 0 || $resNoGradeCount > 0 || $mutNoGradeCount > 0): 
                    $totEst += $estNoGradeCount;
                    $totEstAmt += $estNoGradeAmount;
                  ?>
                    <td style="background-color: #fffdf9;" class="align-middle">
                      <?php if ($estNoGradeCount > 0): ?>
                        <strong><?php echo $estNoGradeCount; ?></strong>
                        <?php if ($estNoGradeAmount > 0): ?>
                          <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($estNoGradeAmount)); ?></span>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>
                  <td style="background-color: #fcf4dd; color: #6c4f00; font-weight: bold;" class="align-middle">
                    <strong><?php echo $totEst; ?></strong>
                    <br><span style="font-size: 12px;">₹<?php echo inr_digits(round($totEstAmt)); ?></span>
                  </td>
                </tr>

                <!-- Residential Row -->
                <tr>
                  <td class="text-left font-weight-bold align-middle" style="background-color: #eaf3fb; color: #1a4a6b;">Residential</td>
                  <?php 
                  $totRes = 0;
                  $totResAmt = 0.0;
                  foreach ($allUniqueGrades as $g): 
                    $cRes = $resGradeCounts[$g] ?? 0;
                    $aRes = $resGradeAmounts[$g] ?? 0.0;
                    $totRes += $cRes;
                    $totResAmt += $aRes;
                  ?>
                    <td style="background-color: #fafdff;" class="align-middle">
                      <?php if ($cRes > 0): ?>
                        <strong><?php echo $cRes; ?></strong>
                        <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($aRes)); ?></span>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                  <?php if ($estNoGradeCount > 0 || $resNoGradeCount > 0 || $mutNoGradeCount > 0): 
                    $totRes += $resNoGradeCount;
                    $totResAmt += $resNoGradeAmount;
                  ?>
                    <td style="background-color: #fafdff;" class="align-middle">
                      <?php if ($resNoGradeCount > 0): ?>
                        <strong><?php echo $resNoGradeCount; ?></strong>
                        <?php if ($resNoGradeAmount > 0): ?>
                          <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($resNoGradeAmount)); ?></span>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>
                  <td style="background-color: #e0eef9; color: #1a4a6b; font-weight: bold;" class="align-middle">
                    <strong><?php echo $totRes; ?></strong>
                    <br><span style="font-size: 12px;">₹<?php echo inr_digits(round($totResAmt)); ?></span>
                  </td>
                </tr>

                <!-- Mutawatteneen Row -->
                <tr>
                  <td class="text-left font-weight-bold align-middle" style="background-color: #f5f3ff; color: #5b21b6;">Mutawatteneen</td>
                  <?php 
                  $totMut = 0;
                  $totMutAmt = 0.0;
                  foreach ($allUniqueGrades as $g): 
                    $cMut = $mutGradeCounts[$g] ?? 0;
                    $aMut = $mutGradeAmounts[$g] ?? 0.0;
                    $totMut += $cMut;
                    $totMutAmt += $aMut;
                  ?>
                    <td style="background-color: #fbfbfe;" class="align-middle">
                      <?php if ($cMut > 0): ?>
                        <strong><?php echo $cMut; ?></strong>
                        <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($aMut)); ?></span>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                  <?php if ($estNoGradeCount > 0 || $resNoGradeCount > 0 || $mutNoGradeCount > 0): 
                    $totMut += $mutNoGradeCount;
                    $totMutAmt += $mutNoGradeAmount;
                  ?>
                    <td style="background-color: #fbfbfe;" class="align-middle">
                      <?php if ($mutNoGradeCount > 0): ?>
                        <strong><?php echo $mutNoGradeCount; ?></strong>
                        <?php if ($mutNoGradeAmount > 0): ?>
                          <br><span class="text-muted" style="font-size: 12px;">₹<?php echo inr_digits(round($mutNoGradeAmount)); ?></span>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="text-muted">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>
                  <td style="background-color: #ede9fe; color: #5b21b6; font-weight: bold;" class="align-middle">
                    <strong><?php echo $totMut; ?></strong>
                    <br><span style="font-size: 12px;">₹<?php echo inr_digits(round($totMutAmt)); ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="table-scroll-fixed">
    <table class="table table-bordered table-striped">
      <?php
      // Helper: Indian Rupee formatting (no decimals, Indian grouping)
      if (!function_exists('inr_format')) {
        function inr_format($num)
        {
          if ($num === null || $num === '' || !is_numeric($num)) return '';
          $neg = $num < 0;
          $num = abs(floor($num));
          $str = (string)$num;
          $len = strlen($str);
          if ($len <= 3) return ($neg ? '-' : '') . '&#8377;' . $str;
          $last3 = substr($str, -3);
          $rest = substr($str, 0, $len - 3);
          $rest_rev = strrev($rest);
          $rest_groups = str_split($rest_rev, 2);
          $rest_formatted = strrev(implode(',', $rest_groups));
          return ($neg ? '-' : '') . '&#8377;' . $rest_formatted . ',' . $last3;
        }
      }
      if (!function_exists('inr_digits')) {
        function inr_digits($num)
        {
          if ($num === null || $num === '' || !is_numeric($num)) return '';
          $neg = $num < 0;
          $num = abs(floor($num));
          $str = (string)$num;
          $len = strlen($str);
          if ($len <= 3) return ($neg ? '-' : '') . $str;
          $last3 = substr($str, -3);
          $rest = substr($str, 0, $len - 3);
          $rest_rev = strrev($rest);
          $rest_groups = str_split($rest_rev, 2);
          $rest_formatted = strrev(implode(',', $rest_groups));
          return ($neg ? '-' : '') . $rest_formatted . ',' . $last3;
        }
      }
      ?>
      <thead>
        <tr>
          <th colspan="4" class="text-center">Member Info</th>
          <th colspan="4" class="text-center">Establishment Sabeel (Current Year)</th>
          <th colspan="4" class="text-center">Residential Sabeel (Current Year)</th>
          <th colspan="4" class="text-center">Mutawatteneen Sabeel (Current Year)</th>
          <th rowspan="2" class="text-center">Action</th>
        </tr>
        <tr>
          <th class="km-sortable" data-sort-type="number">Sr. No. <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">ITS ID <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">Name <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">Sector <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">Grade <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Establishment Monthly is derived as Yearly/12">Monthly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Establishment Yearly comes from grade yearly amount">Yearly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number">Due <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">Grade <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Residential Monthly comes from grade monthly amount">Monthly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Residential Yearly comes directly from grade yearly_amount">Yearly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number">Due <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="string">Grade <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Mutawatteneen Monthly comes from grade monthly amount">Monthly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number" title="Mutawatteneen Yearly comes directly from grade yearly_amount">Yearly <span class="sort-indicator"></span></th>
          <th class="km-sortable" data-sort-type="number">Due <span class="sort-indicator"></span></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($all_user_sabeel_takhmeen)): foreach ($all_user_sabeel_takhmeen as $idx => $user):
            $curr = $user['current_year_takhmeen'] ?? null;
            $est = $curr['establishment'] ?? null;
            $res = $curr['residential'] ?? null;
            $estDue = $est ? max(0, (float)($est['yearly'] ?? 0) - (float)($est['paid'] ?? 0)) : 0;
            $resDue = $res ? max(0, (float)($res['yearly'] ?? 0) - (float)($res['paid'] ?? 0)) : 0;
            $mut = $curr['mutawatteneen'] ?? null;
            $mutDue = $mut ? max(0, (float)($mut['yearly'] ?? 0) - (float)($mut['paid'] ?? 0)) : 0;
            $yearLabel = $curr['year'] ?? '';
            // Aggregated totals across all years already provided by model
            $agg = $curr['aggregate'] ?? null;
            $allEstDue = $user['total_establishment_due'] ?? null;
            $allResDue = $user['total_residential_due'] ?? null;
            $allMutDue = $user['total_mutawatteneen_due'] ?? null;
        ?>
            <tr class="clickable-row" data-its-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? ''), ENT_QUOTES); ?>">
              <td><?php echo $idx + 1; ?></td>
              <td><?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?></td>
              <td><?php
                  $fullName = trim((string)($user['Full_Name'] ?? ''));
                  if ($fullName === '') {
                    $fullName = trim((string)($user['First_Name'] ?? '') . ' ' . (string)($user['Surname'] ?? ''));
                  }
                  echo htmlspecialchars($fullName);
                  ?></td>
              <td><?php echo htmlspecialchars(trim((string)($user['Sector'] ?? '') . ' - ' . (string)($user['Sub_Sector'] ?? ''))); ?></td>
              <?php if (!$curr): ?>
                <td colspan="12" class="text-center align-middle font-weight-bold text-danger" style="background-color: #fafafa; font-size: 13px;">Takhmeen Not Done</td>
              <?php else: ?>
              <td>
                <?php
                $estGrade = ($est && isset($est['grade'])) ? trim((string)$est['grade']) : '';
                $showEstGrade = ($estGrade !== '' && strcasecmp($estGrade, 'no grade') !== 0 && strcasecmp($estGrade, 'unknown') !== 0);
                echo $showEstGrade ? htmlspecialchars($estGrade) : '';
                ?>
                <br><small class="text-muted"><?php echo $yearLabel ? '(' . htmlspecialchars((string)$yearLabel) . ')' : ''; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo ($est && is_numeric($est['monthly']) && $est['monthly'] > 0) ? inr_format(round($est['monthly'])) : '0'; ?></td>
              <td class="takhmeen-amount"><?php echo ($est && is_numeric($est['yearly']) && $est['yearly'] > 0) ? inr_format(round($est['yearly'])) : ''; ?></td>
              <td class="takhmeen-amount due-wrapper">
                <?php if ($est && $estDue > 0) { ?>
                  <span class="est-due"><?php echo inr_format(round($estDue)); ?></span>
                <?php } else { ?>
                  0
                <?php } ?>
                <?php
                $allEstVal = is_numeric($allEstDue) ? round($allEstDue) : 0;
                if ($allEstVal > 0 && $allEstVal != round($estDue)) { ?>
                  <span class="all-years-badge est glow-pulse" title="All outstanding across every year for Establishment Sabeel">
                    <span class="label">All Yrs</span>
                    <span class="value">&#8377;<?php echo inr_digits($allEstVal); ?></span>
                  </span>
                <?php } ?>
              </td>
              <td>
                <?php
                $resGrade = ($res && isset($res['grade'])) ? trim((string)$res['grade']) : '';
                $showResGrade = ($resGrade !== '' && strcasecmp($resGrade, 'no grade') !== 0 && strcasecmp($resGrade, 'unknown') !== 0);
                echo $showResGrade ? htmlspecialchars($resGrade) : '';
                ?>
                <br><small class="text-muted"><?php echo $yearLabel ? '(' . htmlspecialchars((string)$yearLabel) . ')' : ''; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo ($res && is_numeric($res['monthly']) && $res['monthly'] > 0) ? inr_format(round($res['monthly'])) : ''; ?></td>
              <td class="takhmeen-amount"><?php echo ($res && is_numeric($res['yearly']) && $res['yearly'] > 0) ? inr_format(round($res['yearly'])) : '0'; ?></td>
              <td class="takhmeen-amount due-wrapper">
                <?php if ($res && $resDue > 0) { ?>
                  <span class="res-due"><?php echo inr_format(round($resDue)); ?></span>
                <?php } else { ?>
                  0
                <?php } ?>
                <?php
                $allResVal = is_numeric($allResDue) ? round($allResDue) : 0;
                if ($allResVal > 0 && $allResVal != round($resDue)) { ?>
                  <span class="all-years-badge res glow-pulse" title="All outstanding across every year for Residential Sabeel">
                    <span class="label">All Yrs</span>
                    <span class="value">&#8377;<?php echo inr_digits($allResVal); ?></span>
                  </span>
                <?php } ?>
              </td>
              <!-- Mutawatteneen Sabeel -->
              <td>
                <?php
                $mutGrade = ($mut && isset($mut['grade'])) ? trim((string)$mut['grade']) : '';
                $showMutGrade = ($mutGrade !== '' && strcasecmp($mutGrade, 'no grade') !== 0 && strcasecmp($mutGrade, 'unknown') !== 0);
                echo $showMutGrade ? htmlspecialchars($mutGrade) : '';
                ?>
                <br><small class="text-muted"><?php echo $yearLabel ? '(' . htmlspecialchars((string)$yearLabel) . ')' : ''; ?></small>
              </td>
              <td class="takhmeen-amount"><?php echo ($mut && is_numeric($mut['monthly']) && $mut['monthly'] > 0) ? inr_format(round($mut['monthly'])) : ''; ?></td>
              <td class="takhmeen-amount"><?php echo ($mut && is_numeric($mut['yearly']) && $mut['yearly'] > 0) ? inr_format(round($mut['yearly'])) : '0'; ?></td>
              <td class="takhmeen-amount due-wrapper">
                <?php if ($mut && $mutDue > 0) { ?>
                  <span class="mut-due"><?php echo inr_format(round($mutDue)); ?></span>
                <?php } else { ?>
                  0
                <?php } ?>
                <?php
                $allMutVal = is_numeric($allMutDue) ? round($allMutDue) : 0;
                if ($allMutVal > 0 && $allMutVal != round($mutDue)) { ?>
                  <span class="all-years-badge mut glow-pulse" title="All outstanding across every year for Mutawatteneen Sabeel">
                    <span class="label">All Yrs</span>
                    <span class="value">&#8377;<?php echo inr_digits($allMutVal); ?></span>
                  </span>
                <?php } ?>
              </td>
              <?php endif; ?>
              <td>
                <div>
                  <?php $safeFullName = htmlspecialchars(str_replace("'", "\\'", $fullName)); ?>
                  <button class="view-takhmeen btn btn-sm btn-primary" data-user-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?>" data-user-name="<?php echo htmlspecialchars($fullName); ?>" data-takhmeens='<?php echo htmlspecialchars(json_encode($user['takhmeens'] ?? []), ENT_QUOTES, "UTF-8"); ?>' <?php echo empty($user['takhmeens']) ? 'disabled' : ''; ?>>View Takhmeens</button>
                </div>
                <div class="mt-2">
                  <button class="payment-history btn btn-sm btn-secondary" onclick="openPaymentHistoryModal('<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?>', '<?php echo $safeFullName; ?>')">Payment History</button>
                </div>
                <div class="mt-2">
                  <button class="update-payment-btn btn btn-sm btn-success" onclick="openUpdatePaymentModal('<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? '')); ?>', '<?php echo $safeFullName; ?>', '<?php echo (string)$estDue; ?>', '<?php echo (string)$resDue; ?>', '<?php echo (string)$mutDue; ?>', '<?php echo (string)($allEstDue !== null ? $allEstDue : $estDue); ?>', '<?php echo (string)($allResDue !== null ? $allResDue : $resDue); ?>', '<?php echo (string)($allMutDue !== null ? $allMutDue : $mutDue); ?>')" <?php echo !$curr ? '' : ''; ?>>Receive Payment</button>
                </div>
              </td>
            </tr>
        <?php endforeach;
        endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="update-payment-container" tabindex="-1" aria-labelledby="update-payment-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="update-payment-label">Receive Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("anjuman/updatesabeeltakhmeenpayment"); ?>">
          <input type="hidden" name="user_id" id="payment-user-id">
          <p><b>Member Name: <span id="payment-user-name">Member Name</span></b></p>
          <div class="form-group">
            <label for="payment-method">Payment Method</label>
            <select name="payment_method" id="payment-method" class="form-control" required onchange="togglePaymentFields(this.value)">
              <option value="">-----</option>
              <option value="Cash">Cash</option>
              <option value="Cheque">Cheque</option>
              <option value="NEFT">NEFT</option>
            </select>
          </div>
          
          <div id="extra-payment-fields" style="display: none;">
            <div class="form-group">
              <label for="reference_no" class="form-label">Cheque No. / NEFT Ref No.</label>
              <input type="text" name="reference_no" id="reference_no" class="form-control" placeholder="Enter reference number">
            </div>
            <div class="form-group">
              <label for="bank_name" class="form-label">Drawn on Bank</label>
              <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter bank name">
            </div>
          </div>
          <label for="sabeel-type" class="form-label">Sabeel Type</label>
          <select name="type" id="sabeel-type" class="form-control" required data-e-due="" data-r-due="" data-m-due="">
            <option value="">-----</option>
            <option value="establishment">Establishment</option>
            <option value="residential">Residential</option>
            <option value="mutawatteneen">Mutawatteneen</option>
          </select>
          <br>
          <label for="payment-amount" class="form-label">Amount</label>
          <input type="number" name="amount" id="payment-amount" class="form-control" min="1" placeholder="Enter amount" required>
          <br>
          <label for="payment-date" class="form-label">Payment Date</label>
          <input type="date" name="payment_date" id="payment-date" class="form-control" required>
          <br>
          <label for="payment-remarks" class="form-label">Remarks</label>
          <textarea name="remarks" id="payment-remarks" class="form-control" placeholder="Optional" rows="2"></textarea>
          <br>
          <button type="submit" id="update-payment-btn" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Payment History Modal -->
<div class="modal fade" id="payment-history-container" tabindex="-1" aria-labelledby="payment-history-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payment-history-label">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6><b>Member Name: <span id="history-user-name"></span></b></h6>
        <div class="d-flex mb-2 align-items-end flex-wrap">
          <div class="mr-3 mb-2">
            <label class="small text-muted m-0">Filter Type</label>
            <select id="history-filter-type" class="form-control form-control-sm">
              <option value="">All</option>
              <option value="establishment">Establishment</option>
              <option value="residential">Residential</option>
              <option value="mutawatteneen">Mutawatteneen</option>
            </select>
          </div>
          <div class="mr-3 mb-2">
            <label class="small text-muted m-0">Min Date</label>
            <input type="date" id="history-filter-from" class="form-control form-control-sm" />
          </div>
          <div class="mr-3 mb-2">
            <label class="small text-muted m-0">Max Date</label>
            <input type="date" id="history-filter-to" class="form-control form-control-sm" />
          </div>
          <div class="mb-2">
            <button id="history-apply-filter" class="btn btn-sm btn-outline-primary mr-1">Apply</button>
            <button id="history-reset-filter" class="btn btn-sm btn-outline-secondary">Reset</button>
          </div>
        </div>
        <div id="payment-history-summary" class="alert alert-info py-2 small d-none"></div>
        <div id="payment-history-table-container">
          <table class="table table-bordered table-sm">
            <thead>
              <tr class="thead-light">
                <th>#</th>
                <th>Payment Date</th>
                <th>Type</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="payment-history-rows"></tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th id="history-total-amount" class="text-right"></th>
                <th colspan="2"></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- View All Takhmeens Modal -->
<div class="modal fade" id="view-all-takhmeen-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">All Sabeel Takhmeen Years</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="vtm-summary" class="small mb-2 d-flex flex-column"></div>
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0" id="vtm-table">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Year</th>
                <th>Est. Grade</th>
                <th>Est. Monthly</th>
                <th>Est. Yearly</th>
                <th>Est. Due</th>
                <th>Res. Grade</th>
                <th>Res. Monthly</th>
                <th>Res. Yearly</th>
                <th>Res. Due</th>
                <th>Mut. Grade</th>
                <th>Mut. Monthly</th>
                <th>Mut. Yearly</th>
                <th>Mut. Due</th>
                <th class="section-start-total" style="background-color:#e8f5e9; color:#1b5e20;">Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function formatAmounts() {
    $(".takhmeen-amount").each(function() {
      const $cell = $(this);
      const $colored = $cell.find('.est-due, .res-due, .mut-due');
      if ($colored.length) {
        const rawSpan = $colored.text().trim();
        if (rawSpan !== '' && !isNaN(rawSpan)) {
          let n = Number(rawSpan);
          if (!isNaN(n)) {
            n = Math.round(n);
            const formatted = "&#8377;" + new Intl.NumberFormat('en-IN').format(n);
            $colored.html(formatted);
          }
        }
        return; // preserve other cell content (badges, labels)
      }
      const raw = $cell.text().trim();
      if (raw === '' || isNaN(raw)) return;
      let n = Number(raw);
      if (isNaN(n)) return;
      n = Math.round(n); // remove decimals
      const formatted = "&#8377;" + new Intl.NumberFormat('en-IN').format(n);
      $cell.html(formatted);
    });
  }
  $(document).ready(function() {
    formatAmounts();
    enableSabeelSorting();
  });

  function openUpdatePaymentModal(user_id, name, establishment_due, residential_due, mutawatteneen_due, est_all_years_due, res_all_years_due, mut_all_years_due) {
    $('#payment-user-id').val(user_id);
    $('#payment-user-name').text(name);
    const today = new Date().toISOString().split("T")[0];
    $("#payment-date").val(today);
    $("#sabeel-type").data("e-due", establishment_due);
    $("#sabeel-type").data("r-due", residential_due);
    $("#sabeel-type").data("m-due", mutawatteneen_due);
    $("#sabeel-type").data("e-due-all", est_all_years_due);
    $("#sabeel-type").data("r-due-all", res_all_years_due);
    $("#sabeel-type").data("m-due-all", mut_all_years_due);
    $("#payment-method").val("");
    $("#reference_no").val("");
    $("#bank_name").val("");
    togglePaymentFields("");
    $("#sabeel-type").val("");
    $("#payment-amount").val("");
    $("#payment-remarks").val("");
    $('#update-payment-container').modal('show');
  }
  $("#sabeel-type").on("change", function() {
    // Use all-years due if available; fallback to current year due
    const eDue = $(this).data("e-due-all") ?? $(this).data("e-due");
    const rDue = $(this).data("r-due-all") ?? $(this).data("r-due");
    const mDue = $(this).data("m-due-all") ?? $(this).data("m-due");
    const type = $(this).val();
    if (type === 'establishment') {
      $("#payment-amount").prop("max", eDue);
    } else if (type === 'residential') {
      $("#payment-amount").prop("max", rDue);
    } else if (type === 'mutawatteneen') {
      $("#payment-amount").prop("max", mDue);
    }
  });

  function togglePaymentFields(method) {
    if (method === 'Cheque' || method === 'NEFT') {
      $('#extra-payment-fields').show();
    } else {
      $('#extra-payment-fields').hide();
      $('#reference_no').val('');
      $('#bank_name').val('');
    }
  }

  let __historyCache = {
    rows: [],
    user: null
  };

  function formatCurrency(val) {
    if (val === null || val === '' || isNaN(val)) return '';
    let n = Number(val);
    if (isNaN(n)) return '';
    n = Math.round(n); // remove decimals universally
    return '&#8377;' + new Intl.NumberFormat('en-IN').format(n);
  }

  function capType(txt) {
    return txt ? txt.charAt(0).toUpperCase() + txt.slice(1) : '';
  }

  function normalizeHistoryRows(rows) {
    return rows.map(r => ({
      id: r.id,
      type: (r.type || '').toLowerCase(),
      payment_method: r.payment_method || '',
      payment_date: r.payment_date || '',
      amount: r.amount || 0,
      remarks: r.remarks || ''
    }));
  }

  function renderHistoryTable() {
    const typeFilter = ($('#history-filter-type').val() || '').toLowerCase();
    const from = $('#history-filter-from').val();
    const to = $('#history-filter-to').val();

    let filtered = __historyCache.rows.slice();
    if (typeFilter) {
      filtered = filtered.filter(r => r.type === typeFilter);
    }
    if (from) {
      filtered = filtered.filter(r => r.payment_date && r.payment_date >= from);
    }
    if (to) {
      filtered = filtered.filter(r => r.payment_date && r.payment_date <= to);
    }

    const $tbody = $('#payment-history-rows');
    $tbody.empty();
    if (!filtered.length) {
      $tbody.append('<tr><td colspan="7" class="text-center text-muted">No payments found for selected filters</td></tr>');
      $('#history-total-amount').html('');
      $('#payment-history-summary').addClass('d-none');
      return;
    }

    let total = 0;
    let estTotal = 0;
    let resTotal = 0;

    function fmtDate(d) {
      if (!d) return '';
      // Expecting YYYY-MM-DD or ISO; parse safely
      const parts = d.split('T')[0].split('-');
      if (parts.length === 3) {
        const yyyy = parts[0],
          mm = parts[1],
          dd = parts[2];
        return `${('0'+dd).slice(-2)}-${('0'+mm).slice(-2)}-${yyyy}`;
      }
      const dt = new Date(d);
      if (isNaN(dt.getTime())) return '';
      const dd = ('0' + dt.getDate()).slice(-2);
      const mm = ('0' + (dt.getMonth() + 1)).slice(-2);
      const yyyy = dt.getFullYear();
      return `${dd}-${mm}-${yyyy}`;
    }
    filtered.forEach((p, i) => {
      const amt = Number(p.amount) || 0;
      total += amt;
      if (p.type === 'establishment') estTotal += amt;
      else if (p.type === 'residential') resTotal += amt;
      $tbody.append(`<tr data-payment-id="${p.id}">
        <td>${i+1}</td>
        <td>${fmtDate(p.payment_date)}</td>
        <td>${capType(p.type)}</td>
        <td>${p.payment_method}</td>
        <td class='text-right'>${formatCurrency(amt)}</td>
        <td>${p.remarks ? $('<div/>').text(p.remarks).html() : ''}</td>
        <td class='text-nowrap'>
          <button class="view-invoice btn btn-sm btn-primary mr-1" data-payment-id="${p.id}">Receipt</button>
          <button class="delete-payment btn btn-sm btn-outline-danger" data-payment-id="${p.id}">&times;</button>
        </td>
      </tr>`);
    });
    $('#history-total-amount').html(formatCurrency(total));
    $('#payment-history-summary')
      .removeClass('d-none')
      .html(`<strong>${filtered.length}</strong> payment(s). Establishment: <strong>${formatCurrency(estTotal)}</strong> | Residential: <strong>${formatCurrency(resTotal)}</strong> | Mutawatteneen: <strong>${formatCurrency(total - estTotal - resTotal)}</strong> | Combined: <strong>${formatCurrency(total)}</strong>`);
  }

  // Delegated handler for viewing invoice (PDF)
  $(document).off('click', '.view-invoice').on('click', '.view-invoice', function(e) {
    e.preventDefault();
    const paymentId = $(this).data('payment-id');
    if (!paymentId) return;
    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: 'POST',
      data: {
        id: paymentId,
        for: 4
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(response) {
        const blob = new Blob([response], {
          type: 'application/pdf'
        });
        const url = window.URL.createObjectURL(blob);
        window.open(url, '_blank');
      },
      error: function() {
        alert('Failed to generate invoice PDF');
      }
    });
  });

  // Delegated handler for deleting payment
  $(document).off('click', '.delete-payment').on('click', '.delete-payment', function(e) {
    e.preventDefault();
    const paymentId = $(this).data('payment-id');
    if (!paymentId) return;
    if (!confirm('Delete this payment? This cannot be undone.')) return;
    $.ajax({
      url: "<?php echo base_url('anjuman/deleteSabeelPayment'); ?>",
      type: 'POST',
      data: {
        payment_id: paymentId
      },
      dataType: 'json',
      success: function(res) {
        if (res && res.success) {
          // Remove from cache
          __historyCache.rows = __historyCache.rows.filter(r => r.id != paymentId);
          renderHistoryTable();
        } else {
          alert('Delete failed');
        }
      },
      error: function() {
        alert('Network error');
      }
    });
  });

  function openPaymentHistoryModal(user_id, name) {
    $('#history-user-name').text(name);
    $('#payment-history-rows').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
    $('#history-total-amount').html('');
    $('#payment-history-summary').addClass('d-none').html('');
    $('#history-filter-type').val('');
    $('#history-filter-from').val('');
    $('#history-filter-to').val('');

    $.ajax({
      url: "<?php echo base_url('anjuman/getPaymentHistory/2'); ?>",
      type: "POST",
      data: {
        user_id
      },
      dataType: "json",
      success: function(response) {
        const rows = Array.isArray(response) ? response : [];
        __historyCache.rows = normalizeHistoryRows(rows);
        __historyCache.user = user_id;
        renderHistoryTable();
      },
      error: function() {
        $('#payment-history-rows').html('<tr><td colspan="7" class="text-center text-danger">Error loading history</td></tr>');
      }
    });
    $('#payment-history-container').modal('show');
  }

  $('#history-apply-filter').on('click', function() {
    renderHistoryTable();
  });
  $('#history-reset-filter').on('click', function() {
    $('#history-filter-type').val('');
    $('#history-filter-from').val('');
    $('#history-filter-to').val('');
    renderHistoryTable();
  });

  // Override existing openPaymentHistoryModal binding if previously defined
  window.openPaymentHistoryModal = openPaymentHistoryModal;

  // View all takhmeens
  $(document).on('click', '.view-takhmeen', function() {
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    let takhmeens = [];
    try {
      takhmeens = JSON.parse($(this).attr('data-takhmeens'));
    } catch (e) {
      takhmeens = [];
    }
    const $tbody = $('#vtm-table tbody');
    $tbody.empty();
    if (!takhmeens.length) {
      $tbody.append('<tr><td colspan="15" class="text-center text-muted">No takhmeen records.</td></tr>');
    } else {
      // Sort descending (latest year first)
      takhmeens.sort((a, b) => b.year.localeCompare(a.year));
      takhmeens.forEach((t, i) => {
        const estY = t.establishment && t.establishment.yearly ? Number(t.establishment.yearly) : 0;
        const estP = t.establishment && t.establishment.paid ? Number(t.establishment.paid) : 0;
        const estD = Math.max(0, Math.round(estY - Math.min(estP, estY)));
        const resY = t.residential && t.residential.yearly ? Number(t.residential.yearly) : 0;
        const resP = t.residential && t.residential.paid ? Number(t.residential.paid) : 0;
        const resD = Math.max(0, Math.round(resY - Math.min(resP, resY)));
        const mutY = t.mutawatteneen && t.mutawatteneen.yearly ? Number(t.mutawatteneen.yearly) : 0;
        const mutP = t.mutawatteneen && t.mutawatteneen.paid ? Number(t.mutawatteneen.paid) : 0;
        const mutD = Math.max(0, Math.round(mutY - Math.min(mutP, mutY)));
        const totalYearly = estY + resY + mutY;

        $tbody.append(`<tr>
          <td>${i+1}</td>
          <td>${t.year}</td>
          <td>${t.establishment && t.establishment.grade ? t.establishment.grade : ''}</td>
          <td class='takhmeen-amount'>${t.establishment && t.establishment.monthly ? Math.round(t.establishment.monthly) : ''}</td>
          <td class='takhmeen-amount'>${estY ? Math.round(estY) : ''}</td>
          <td class='takhmeen-amount'>${estD ? `<span class="est-due">${estD}</span>` : '0'}</td>
          <td>${t.residential && t.residential.grade ? t.residential.grade : ''}</td>
          <td class='takhmeen-amount'>${t.residential && t.residential.monthly ? Math.round(t.residential.monthly) : ''}</td>
          <td class='takhmeen-amount'>${resY ? Math.round(resY) : ''}</td>
          <td class='takhmeen-amount'>${resD ? `<span class="res-due">${resD}</span>` : '0'}</td>
          <td>${t.mutawatteneen && t.mutawatteneen.grade ? t.mutawatteneen.grade : ''}</td>
          <td class='takhmeen-amount'>${t.mutawatteneen && t.mutawatteneen.monthly ? Math.round(t.mutawatteneen.monthly) : ''}</td>
          <td class='takhmeen-amount'>${mutY ? Math.round(mutY) : ''}</td>
          <td class='takhmeen-amount'>${mutD ? `<span class="mut-due">${mutD}</span>` : '0'}</td>
          <td class='takhmeen-amount section-start-total' style="background-color:#e8f5e9; font-weight:bold;">${totalYearly ? Math.round(totalYearly) : ''}</td>
        </tr>`);
      });
    }
    // Compute totals across all years for dues (yearly - paid). If paid not present, assume unpaid.
    let estYearlyTotal = 0,
      estPaidTotal = 0,
      resYearlyTotal = 0,
      resPaidTotal = 0,
      mutYearlyTotal = 0,
      mutPaidTotal = 0;
    takhmeens.forEach(t => {
      if (t.establishment) {
        const y = Number(t.establishment.yearly || 0);
        const p = Number(t.establishment.paid || 0);
        estYearlyTotal += y;
        estPaidTotal += Math.min(p, y);
      }
      if (t.residential) {
        const y = Number(t.residential.yearly || 0);
        const p = Number(t.residential.paid || 0);
        resYearlyTotal += y;
        resPaidTotal += Math.min(p, y);
      }
      if (t.mutawatteneen) {
        const y = Number(t.mutawatteneen.yearly || 0);
        const p = Number(t.mutawatteneen.paid || 0);
        mutYearlyTotal += y;
        mutPaidTotal += Math.min(p, y);
      }
    });
    const estDueTotal = Math.max(0, estYearlyTotal - estPaidTotal);
    const resDueTotal = Math.max(0, resYearlyTotal - resPaidTotal);
    const mutDueTotal = Math.max(0, mutYearlyTotal - mutPaidTotal);
    const overallDue = estDueTotal + resDueTotal + mutDueTotal;

    function f(n) {
      return new Intl.NumberFormat('en-IN').format(Math.round(n));
    }
    // Try to get current year takhmeen for precise current-year dues
    let estCurrentDue = 0,
      resCurrentDue = 0,
      mutCurrentDue = 0;
    if (takhmeens.length) {
      // current year assumed first element in descending list
      const cy = takhmeens[0];
      if (cy.establishment) {
        const y = Number(cy.establishment.yearly || 0);
        const p = Number(cy.establishment.paid || 0);
        estCurrentDue = Math.max(0, y - Math.min(p, y));
      }
      if (cy.residential) {
        const y = Number(cy.residential.yearly || 0);
        const p = Number(cy.residential.paid || 0);
        resCurrentDue = Math.max(0, y - Math.min(p, y));
      }
      if (cy.mutawatteneen) {
        const y = Number(cy.mutawatteneen.yearly || 0);
        const p = Number(cy.mutawatteneen.paid || 0);
        mutCurrentDue = Math.max(0, y - Math.min(p, y));
      }
    }
    const btnDisabled = (estDueTotal + resDueTotal + mutDueTotal) <= 0 ? 'disabled' : '';
    $('#vtm-summary').html(`
      <div class="mb-1">
        <strong style="font-size:15px;">${userName}</strong> (ITS: ${userId}) - Years: ${takhmeens.length}
      </div>
      <div class="mb-2">
        <span class="badge ${estDueTotal>0 ? 'badge-danger' : 'badge-secondary'} mr-2" style="font-size:13px; padding:6px 10px;">Est. Due: ₹${f(estDueTotal)}</span>
        <span class="badge ${resDueTotal>0 ? 'badge-danger' : 'badge-secondary'} mr-2" style="font-size:13px; padding:6px 10px;">Res. Due: ₹${f(resDueTotal)}</span>
        <span class="badge ${mutDueTotal>0 ? 'badge-danger' : 'badge-secondary'} mr-2" style="font-size:13px; padding:6px 10px;">Mut. Due: ₹${f(mutDueTotal)}</span>
        <span class="badge ${overallDue>0 ? 'badge-danger' : 'badge-secondary'} mr-3" style="font-size:13px; padding:6px 10px;">Overall Due: ₹${f(overallDue)}</span>
        <button class="btn btn-sm btn-success" id="vtm-receive-btn" ${btnDisabled}
          data-user-id="${userId}"
          data-user-name="${userName.replace(/"/g,'&quot;')}"
          data-est-due-current="${estCurrentDue}"
          data-res-due-current="${resCurrentDue}"
          data-mut-due-current="${mutCurrentDue}"
          data-est-due-all="${estDueTotal}"
          data-res-due-all="${resDueTotal}"
          data-mut-due-all="${mutDueTotal}"
        >Receive Payment</button>
      </div>
    `);
    $('#view-all-takhmeen-modal').modal('show');
    formatAmounts();
  });

  // Receive payment button inside View Takhmeens modal
  $(document).on('click', '#vtm-receive-btn', function() {
    const $b = $(this);
    const userId = $b.data('user-id');
    const userName = $b.data('user-name');
    const estCurr = $b.data('est-due-current');
    const resCurr = $b.data('res-due-current');
    const mutCurr = $b.data('mut-due-current');
    const estAll = $b.data('est-due-all');
    const resAll = $b.data('res-due-all');
    const mutAll = $b.data('mut-due-all');
    // Reuse existing modal logic
    openUpdatePaymentModal(userId, userName, estCurr, resCurr, mutCurr, estAll, resAll, mutAll);
    // Keep the view modal open in background or hide? We'll hide to focus on payment.
    $('#view-all-takhmeen-modal').modal('hide');
  });

  // --- Sorting logic for main Sabeel Takhmeen table ---
  function enableSabeelSorting() {
    const table = document.querySelector('.margintopcontainer table.table');
    if (!table) return;
    const headerRow = table.querySelectorAll('thead tr')[1]; // second header row
    if (!headerRow) return;
    const headers = headerRow.querySelectorAll('th.km-sortable');
    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    function extractNumber(cell) {
      if (!cell) return 0;
      // Remove label prefixes and currency symbol, keep first numeric sequence only
      let text = cell.textContent.replace(/Est\.:|Res\.:/gi, '').replace(/₹/g, '').trim();
      // Stop at badge text if present (All Yrs) to avoid picking aggregate second number
      const badgeIndex = text.toLowerCase().indexOf('all yrs');
      if (badgeIndex !== -1) {
        text = text.substring(0, badgeIndex).trim();
      }
      // Remove commas (Indian grouping) then extract first number
      text = text.replace(/,/g, '');
      const match = text.match(/[-+]?[0-9]+(?:\.[0-9]+)?/);
      if (!match) return 0;
      const num = parseFloat(match[0]);
      return isNaN(num) ? 0 : num;
    }
    headers.forEach((th, colIndex) => {
      th.addEventListener('click', () => {
        const currentDir = th.getAttribute('data-sort-dir');
        const newDir = currentDir === 'asc' ? 'desc' : 'asc';
        headers.forEach(h => {
          if (h !== th) {
            h.removeAttribute('data-sort-dir');
            const si = h.querySelector('.sort-indicator');
            if (si) si.textContent = '';
          }
        });
        th.setAttribute('data-sort-dir', newDir);
        const ind = th.querySelector('.sort-indicator');
        if (ind) ind.textContent = newDir === 'asc' ? '▲' : '▼';
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const sortType = th.getAttribute('data-sort-type') || 'string';
        rows.sort((a, b) => {
          let aVal = a.children[colIndex];
          let bVal = b.children[colIndex];
          if (sortType === 'number') {
            aVal = extractNumber(aVal);
            bVal = extractNumber(bVal);
            return newDir === 'asc' ? aVal - bVal : bVal - aVal;
          } else {
            aVal = (aVal ? aVal.textContent.trim().toLowerCase() : '');
            bVal = (bVal ? bVal.textContent.trim().toLowerCase() : '');
            if (aVal === bVal) return 0;
            return newDir === 'asc' ? (aVal < bVal ? -1 : 1) : (aVal > bVal ? -1 : 1);
          }
        });
        rows.forEach((r, i) => {
          tbody.appendChild(r);
          const first = r.children[0];
          if (first) first.textContent = i + 1;
        });
      });
    });
  }

  $(document).ready(function() {
    $(document).on('click', 'tr.clickable-row', function(e) {
      if ($(e.target).is('button, a, input, select, option, i') || $(e.target).closest('button, a, input, select, option').length) {
        return;
      }
      var itsId = $(this).data('its-id');
      if (itsId) {
        window.location.href = '<?php echo base_url("admin/viewmember/"); ?>' + itsId;
      }
    });
  });
</script>