<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link
  href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap"
  rel="stylesheet">

<style>
  :root {
    --gold: #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --gold-deep: #8a6408;
    --bg: #faf7f0;
    --surface: #ffffff;
    --surface-2: #f7f4ec;
    --border: #e8e0cc;
    --text-1: #1a1610;
    --text-2: #5a5244;
    --text-3: #9c8f7a;
    --green: #1a6645;
    --green-bg: #eaf4ee;
    --green-border: #bbf7d0;
    --red: #b91c1c;
    --red-bg: #fef2f2;
    --red-border: #fecaca;
    --blue: #1d4ed8;
    --blue-bg: #eff6ff;
    --blue-border: #bfdbfe;
    --amber: #b45309;
    --amber-bg: #fffbeb;
    --radius-sm: 8px;
    --radius: 14px;
    --radius-lg: 20px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    --shadow: 0 4px 16px rgba(0, 0, 0, 0.07), 0 1px 4px rgba(0, 0, 0, 0.04);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.10), 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
  }

  /* ── Page header ── */
  .page-header-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    margin-bottom: 6px;
  }

  .btn-back-nav {
    position: absolute;
    left: 0;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-size: 14px;
    text-decoration: none;
    box-shadow: var(--shadow-sm);
    transition: all .15s;
  }

  .btn-back-nav:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
  }

  .page-heading {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold);
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: -.3px;
    margin: 0;
    text-align: center;
  }

  .page-sub {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-3);
    text-align: center;
    margin-top: 4px;
  }

  .section-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 18px 0 24px;
  }

  /* ── Summary stat tiles ── */
  .stat-tile {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 20px 16px;
    text-align: center;
    transition: box-shadow .2s, border-color .2s;
    overflow: hidden;
    position: relative;
  }

  .stat-tile::before {
    content: '';
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
  }

  .stat-tile.tile-due::before {
    background: linear-gradient(90deg, var(--red), #f87171);
  }

  .stat-tile.tile-total::before {
    background: linear-gradient(90deg, var(--blue), #60a5fa);
  }

  .stat-tile.tile-paid::before {
    background: linear-gradient(90deg, var(--green), #4cc790);
  }

  .stat-tile.tile-pending::before {
    background: linear-gradient(90deg, var(--red), #f87171);
  }

  .stat-tile:hover {
    box-shadow: var(--shadow);
    border-color: rgba(184, 134, 11, 0.3);
  }

  .stat-tile .tile-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 8px;
  }

  .stat-tile .tile-value {
    font-size: 1.7rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -1px;
  }

  .tile-value.red {
    color: var(--red);
  }

  .tile-value.green {
    color: var(--green);
  }

  .tile-value.blue {
    color: var(--blue);
  }

  .tile-value.gold {
    color: var(--gold);
  }

  /* ── Pay Now button ── */
  .btn-pay-now {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-top: 14px;
    padding: 9px 22px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.28);
    transition: all .18s;
    text-decoration: none;
  }

  .btn-pay-now:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.38);
    transform: translateY(-1px);
    color: #fff;
  }

  /* ── Current year label ── */
  .cy-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--gold-deep);
    background: var(--gold-muted);
    border: 1px solid rgba(184, 134, 11, 0.3);
    border-radius: 40px;
    padding: 4px 14px;
    margin-bottom: 14px;
  }

  /* ── Section cards (tables) ── */
  .section-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: 20px;
    transition: box-shadow .2s;
  }

  .section-card:hover {
    box-shadow: var(--shadow);
  }

  .section-card::before {
    content: '';
    display: block;
    height: 3px;
  }

  .section-card.est::before {
    background: linear-gradient(90deg, var(--blue), #60a5fa);
  }

  .section-card.res::before {
    background: linear-gradient(90deg, var(--green), #4cc790);
  }

  .section-card.pay::before {
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
  }

  .section-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 20px 11px;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }

  .section-card-title {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-2);
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
  }

  .section-card-title .fa {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    background: var(--gold-muted);
    color: var(--gold);
  }

  /* ── Tables ── */
  .t-wrap {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    max-height: 40vh;
  }

  .themed-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
  }

  .themed-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    font-size: 0.63rem;
    font-weight: 700;
    letter-spacing: .55px;
    text-transform: uppercase;
    color: var(--text-3);
    padding: 10px 16px;
    border-bottom: 1.5px solid var(--border);
    background: var(--surface-2);
    white-space: nowrap;
  }

  .themed-table tbody td {
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-2);
    vertical-align: middle;
  }

  .themed-table tbody tr:last-child td {
    border-bottom: none;
  }

  .themed-table tbody tr:hover {
    background: #fdfbf5;
  }

  .themed-table tbody tr:nth-of-type(odd) {
    background: var(--surface-2);
  }

  .themed-table tbody tr:nth-of-type(even) {
    background: var(--surface);
  }

  .themed-table tbody tr:hover {
    background: var(--gold-muted) !important;
  }

  .t-year {
    font-weight: 700;
    color: var(--text-1);
  }

  .t-grade {
    font-size: 0.75rem;
    color: var(--text-3);
    font-weight: 500;
  }

  .t-amt {
    font-weight: 700;
    font-variant-numeric: tabular-nums;
  }

  .t-blue {
    color: var(--blue);
  }

  .t-green {
    color: var(--green);
  }

  .t-red {
    color: var(--red);
  }

  /* highlight current year row */
  .themed-table tbody tr.row-current td {
    background: var(--gold-muted) !important;
    font-weight: 700;
  }

  .themed-table tbody tr.row-current .t-year {
    color: var(--gold-deep);
  }

  /* ── Empty state ── */
  .t-empty {
    text-align: center;
    padding: 28px;
    font-size: 0.83rem;
    color: var(--text-3);
  }

  /* ── View Invoice btn ── */
  .btn-invoice {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--blue-border);
    background: var(--blue-bg);
    color: var(--blue);
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
  }

  .btn-invoice:hover {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
  }

  /* ── Pay Modal ── */
  #sabeelPayModal .modal-content {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  #sabeelPayModal .modal-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  #sabeelPayModal .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gold);
    margin: 0;
  }

  #sabeelPayModal .close {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    cursor: pointer;
    color: var(--text-2);
    transition: all .15s;
    padding: 0;
    opacity: 1;
    text-shadow: none;
  }

  #sabeelPayModal .close:hover {
    background: var(--red-bg);
    border-color: var(--red-border);
    color: var(--red);
  }

  #sabeelPayModal .modal-body {
    background: var(--bg);
    padding: 20px 24px;
  }

  #sabeelPayModal .modal-footer {
    background: var(--surface-2);
    border-top: 1px solid var(--border);
    padding: 14px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  /* modal form fields */
  #sabeelPayModal label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 6px;
    display: block;
  }

  /* Modal Form Controls */
  #sabeelPayModal .form-control {
    width: 100%;
    height: 52px;
    padding: 0 16px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 15px;
    font-weight: 500;
    color: var(--text-1);
    line-height: 50px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
    -webkit-appearance: none;
    box-sizing: border-box;
  }

  #sabeelPayModal select.form-control {
    padding-right: 42px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
  }

  #sabeelPayModal .form-control:focus {
    border-color: var(--gold);
    outline: none;
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.12);
  }

  #sabeelPayModal .form-text {
    font-size: 0.72rem;
    color: var(--text-3);
    margin-top: 6px;
  }

  #sabeelPayModal .form-group {
    margin-bottom: 16px;
  }

  .btn-modal-cancel {
    padding: 9px 20px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
  }

  .btn-modal-cancel:hover {
    background: var(--surface-2);
  }

  .btn-modal-proceed {
    padding: 9px 22px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 7px;
  }

  .btn-modal-proceed:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
  }

  .btn-modal-proceed::before {
    content: '\f023';
    font-family: FontAwesome;
    font-size: 12px;
  }

  @media (max-width: 575px) {
    .stat-tile .tile-value {
      font-size: 1.4rem;
    }

    .page-heading {
      font-size: 1.2rem;
    }
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap">
    <a href="<?php echo base_url('accounts') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Sabeel Takhmeen</h1>
  </div>
  <p class="page-sub">Establishment &amp; Residential dues overview</p>
  <hr class="section-divider">

  <?php
  $overall = isset($sabeel_takhmeen_details['overall']) && is_array($sabeel_takhmeen_details['overall']) ? $sabeel_takhmeen_details['overall'] : [];
  $ov_total_due = (float) ($overall['total_due'] ?? 0);
  $ov_total_paid = (float) ($overall['total_paid'] ?? 0);
  $ov_total_amount = (float) ($overall['total_amount'] ?? 0);
  $ov_est_due = (float) ($overall['establishment_due'] ?? 0);
  $ov_res_due = (float) ($overall['residential_due'] ?? 0);
  $ov_mut_due = (float) ($overall['mutawatteneen_due'] ?? 0);
  $default_type = ($ov_est_due > 0) ? 'establishment' : (($ov_res_due > 0) ? 'residential' : 'mutawatteneen');
  $its_id_for_pay = $_SESSION['user_data']['ITS_ID'] ?? '';
  ?>

  <!-- ── Overall due + Pay Now ── -->
  <div class="row justify-content-center mb-4">
    <div class="col-12">
      <div class="stat-tile tile-due text-center">
        <div class="tile-label">All-Year Due</div>
        <div class="tile-value red">&#8377;<?php echo format_inr(round($ov_total_due), 0); ?></div>
        <button type="button" class="btn-pay-now" data-toggle="modal" data-target="#sabeelPayModal">
          <i class="fa fa-credit-card"></i> Pay Now
        </button>
      </div>
    </div>
  </div>

  <?php
  /* ── Current year breakdown (identical logic to original) ── */
  $CI = &get_instance();
  $CI->load->model('HijriCalendar');
  $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
  $hy = isset($parts['hijri_year']) ? (int) $parts['hijri_year'] : 0;
  $hm = isset($parts['hijri_month']) ? (int) $parts['hijri_month'] : 0;
  if ($hy === 0) {
    $hd = $CI->HijriCalendar->get_hijri_date(date('Y-m-d'));
    if ($hd && isset($hd['hijri_date'])) {
      $p = explode('-', $hd['hijri_date']);
      if (count($p) === 3) {
        $hm = (int) $p[1];
        $hy = (int) $p[2];
      }
    }
  }
  $currentCompositeYear = '';
  if ($hy > 0) {
    $sy = ($hm >= 9) ? $hy : $hy - 1;
    $ey = $sy + 1;
    $currentCompositeYear = $sy . '-' . substr((string) $ey, -2);
  }
  $cy_est_total = null;
  $cy_res_year_total = null;
  $cy_mut_total = null;
  $cy_paid = null;
  $cy_due = null;
  if (!empty($sabeel_takhmeen_details['e_takhmeen']) && is_array($sabeel_takhmeen_details['e_takhmeen'])) {
    foreach ($sabeel_takhmeen_details['e_takhmeen'] as $row) {
      if (isset($row['year']) && (string) $row['year'] === $currentCompositeYear) {
        $cy_est_total = (float) ($row['total'] ?? 0);
        $e_paid = (float) ($row['paid'] ?? 0);
        $e_due = (float) ($row['due'] ?? max(0, $cy_est_total - $e_paid));
        $cy_paid = ($cy_paid ?? 0) + $e_paid;
        $cy_due = ($cy_due ?? 0) + $e_due;
        break;
      }
    }
  }
  if (!empty($sabeel_takhmeen_details['r_takhmeen']) && is_array($sabeel_takhmeen_details['r_takhmeen'])) {
    foreach ($sabeel_takhmeen_details['r_takhmeen'] as $row) {
      if (isset($row['year']) && (string) $row['year'] === $currentCompositeYear) {
        $cy_res_year_total = (float) ($row['total'] ?? 0);
        $r_paid = (float) ($row['paid'] ?? 0);
        $r_due = (float) ($row['due'] ?? max(0, $cy_res_year_total - $r_paid));
        $cy_paid = ($cy_paid ?? 0) + $r_paid;
        $cy_due = ($cy_due ?? 0) + $r_due;
        break;
      }
    }
  }
  if (!empty($sabeel_takhmeen_details['m_takhmeen']) && is_array($sabeel_takhmeen_details['m_takhmeen'])) {
    foreach ($sabeel_takhmeen_details['m_takhmeen'] as $row) {
      if (isset($row['year']) && (string) $row['year'] === $currentCompositeYear) {
        $cy_mut_total = (float) ($row['total'] ?? 0);
        $m_paid = (float) ($row['paid'] ?? 0);
        $m_due = (float) ($row['due'] ?? max(0, $cy_mut_total - $m_paid));
        $cy_paid = ($cy_paid ?? 0) + $m_paid;
        $cy_due = ($cy_due ?? 0) + $m_due;
        break;
      }
    }
  }
  if ($cy_est_total !== null || $cy_res_year_total !== null || $cy_mut_total !== null):
    $cy_total = (float) ($cy_est_total + $cy_res_year_total + $cy_mut_total);
    ?>
    <!-- ── Current year tiles ── -->
    <div class="cy-label"><i class="fa fa-calendar"></i> Current Year:
      <?php echo htmlspecialchars($currentCompositeYear); ?></div>
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-4">
        <div class="stat-tile tile-total">
          <div class="tile-label">Takhmeen</div>
          <div class="tile-value blue">&#8377;<?php echo format_inr(round($cy_total), 0); ?></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stat-tile tile-paid">
          <div class="tile-label">Paid</div>
          <div class="tile-value green">&#8377;<?php echo format_inr(round($cy_paid ?? 0), 0); ?></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stat-tile tile-pending">
          <div class="tile-label">Pending</div>
          <div class="tile-value red">
            &#8377;<?php echo format_inr(round(max(0, ($cy_total ?? 0) - ($cy_paid ?? 0))), 0); ?></div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- ── Establishment table ── -->
  <div class="section-card est">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-building"></i> Establishment Sabeel Takhmeen</h5>
    </div>
    <div class="t-wrap">
      <table class="themed-table">
        <thead>
          <tr>
            <th>Year</th>
            <th>Grade</th>
            <th class="text-right">Total</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Due</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($sabeel_takhmeen_details['e_takhmeen'])): ?>
            <?php foreach ($sabeel_takhmeen_details['e_takhmeen'] as $row): ?>
              <?php
              $eyear = isset($row['year']) ? htmlspecialchars((string) $row['year']) : '';
              $egrade = isset($row['grade']) && trim($row['grade']) !== '' ? htmlspecialchars($row['grade']) : '—';
              $etotal = (float) ($row['total'] ?? 0);
              $epaid = (float) ($row['paid'] ?? 0);
              $edue = (float) ($row['due'] ?? max(0, $etotal - $epaid));
              $isCurrent = ($currentCompositeYear && (string) $row['year'] === $currentCompositeYear);
              ?>
              <tr <?php if ($isCurrent)
                echo 'class="row-current"'; ?>>
                <td><span class="t-year"><?php echo $eyear; ?></span><?php if ($isCurrent): ?> <span
                      style="font-size:.6rem;color:var(--gold);font-weight:700;margin-left:4px;">CURRENT</span><?php endif; ?>
                </td>
                <td><span class="t-grade"><?php echo $egrade; ?></span></td>
                <td class="text-right"><span class="t-amt t-blue">&#8377;<?php echo format_inr($etotal, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-green">&#8377;<?php echo format_inr($epaid, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-red">&#8377;<?php echo format_inr($edue, 0); ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="t-empty">No establishment takhmeen found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Residential table ── -->
  <div class="section-card res">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-home"></i> Residential Sabeel Takhmeen</h5>
    </div>
    <div class="t-wrap">
      <table class="themed-table">
        <thead>
          <tr>
            <th>Year</th>
            <th>Grade</th>
            <th class="text-right">Total</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Due</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($sabeel_takhmeen_details['r_takhmeen'])): ?>
            <?php foreach ($sabeel_takhmeen_details['r_takhmeen'] as $row): ?>
              <?php
              $ryear = isset($row['year']) ? htmlspecialchars((string) $row['year']) : '';
              $rgrade = isset($row['grade']) && trim($row['grade']) !== '' ? htmlspecialchars($row['grade']) : '—';
              $rtotal = (float) ($row['total'] ?? 0);
              $rpaid = (float) ($row['paid'] ?? 0);
              $rdue = (float) ($row['due'] ?? max(0, $rtotal - $rpaid));
              $isCurrent = ($currentCompositeYear && (string) $row['year'] === $currentCompositeYear);
              ?>
              <tr <?php if ($isCurrent)
                echo 'class="row-current"'; ?>>
                <td><span class="t-year"><?php echo $ryear; ?></span><?php if ($isCurrent): ?> <span
                      style="font-size:.6rem;color:var(--gold);font-weight:700;margin-left:4px;">CURRENT</span><?php endif; ?>
                </td>
                <td><span class="t-grade"><?php echo $rgrade; ?></span></td>
                <td class="text-right"><span class="t-amt t-blue">&#8377;<?php echo format_inr($rtotal, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-green">&#8377;<?php echo format_inr($rpaid, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-red">&#8377;<?php echo format_inr($rdue, 0); ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="t-empty">No residential takhmeen found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Mutawatteneen table ── -->
  <div class="section-card mut">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa-solid fa-people-group"></i> Mutawatteneen Sabeel Takhmeen</h5>
    </div>
    <div class="t-wrap">
      <table class="themed-table">
        <thead>
          <tr>
            <th>Year</th>
            <th>Grade</th>
            <th class="text-right">Total</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Due</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($sabeel_takhmeen_details['m_takhmeen'])): ?>
            <?php foreach ($sabeel_takhmeen_details['m_takhmeen'] as $row): ?>
              <?php
              $myear = isset($row['year']) ? htmlspecialchars((string) $row['year']) : '';
              $mgrade = isset($row['grade']) && trim($row['grade']) !== '' ? htmlspecialchars($row['grade']) : '—';
              $mtotal = (float) ($row['total'] ?? 0);
              $mpaid = (float) ($row['paid'] ?? 0);
              $mdue = (float) ($row['due'] ?? max(0, $mtotal - $mpaid));
              $isCurrent = ($currentCompositeYear && (string) $row['year'] === $currentCompositeYear);
              ?>
              <tr <?php if ($isCurrent)
                echo 'class="row-current"'; ?>>
                <td><span class="t-year"><?php echo $myear; ?></span><?php if ($isCurrent): ?> <span
                      style="font-size:.6rem;color:var(--gold);font-weight:700;margin-left:4px;">CURRENT</span><?php endif; ?>
                </td>
                <td><span class="t-grade"><?php echo $mgrade; ?></span></td>
                <td class="text-right"><span class="t-amt t-blue">&#8377;<?php echo format_inr($mtotal, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-green">&#8377;<?php echo format_inr($mpaid, 0); ?></span></td>
                <td class="text-right"><span class="t-amt t-red">&#8377;<?php echo format_inr($mdue, 0); ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="t-empty">No mutawatteneen takhmeen found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Payment History table ── -->
  <div class="section-card pay">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-history"></i> Payment History</h5>
    </div>
    <div class="t-wrap">
      <table class="themed-table">
        <thead>
          <tr>
            <th>Date</th>
            <th class="text-right">Amount</th>
            <th>Method</th>
            <th>Type</th>
            <th>Remarks</th>
            <th class="text-right">Receipt</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($sabeel_takhmeen_details['all_payments'])): ?>
            <?php foreach ($sabeel_takhmeen_details['all_payments'] as $pay): ?>
              <?php
              $pdate = isset($pay['payment_date']) && $pay['payment_date'] ? date('d M Y', strtotime($pay['payment_date'])) : '-';
              $pamount = (float) ($pay['amount'] ?? 0);
              $pmethod = isset($pay['payment_method']) ? htmlspecialchars((string) $pay['payment_method']) : '';
              $ptype = isset($pay['type']) ? htmlspecialchars(ucfirst((string) $pay['type'])) : '';
              $premarks = isset($pay['remarks']) ? htmlspecialchars((string) $pay['remarks']) : '-';
              ?>
              <tr>
                <td style="font-weight:600; color:var(--text-1);"><?php echo $pdate; ?></td>
                <td class="text-right"><span
                    class="t-amt t-green">&#8377;<?php echo format_inr(round($pamount), 0); ?></span></td>
                <td><?php echo $pmethod; ?></td>
                <td><?php echo $ptype; ?></td>
                <td style="color:var(--text-3); font-size:0.78rem;"><?php echo $premarks; ?></td>
                <td class="text-right">
                  <button class="btn-invoice view-invoice" data-payment-id="<?php echo $pay['id']; ?>">
                    <i class="fa fa-file-pdf-o"></i> Invoice
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="t-empty">No payments found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /.container -->

<!-- ── Pay Modal (identical form logic) ── -->
<div class="modal fade" id="sabeelPayModal" tabindex="-1" aria-labelledby="sabeelPayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sabeelPayModalLabel">
          <i class="fa fa-credit-card" style="margin-right:8px; color:var(--gold); font-size:0.9rem;"></i>Pay Sabeel
          Takhmeen
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      </div>
      <form method="post" action="<?= base_url('payment/ccavenue_sabeel'); ?>">
        <div class="modal-body">
          <div class="form-group">
            <label for="sabeel_pay_type">Payment For</label>
            <select id="sabeel_pay_type" name="payment_type" class="form-control" required>
              <option value="establishment" <?= $default_type === 'establishment' ? 'selected' : '' ?>>
                Establishment — Due: ₹<?= htmlspecialchars(format_inr(round($ov_est_due), 0)); ?>
              </option>
              <option value="residential" <?= $default_type === 'residential' ? 'selected' : '' ?>>
                Residential — Due: ₹<?= htmlspecialchars(format_inr(round($ov_res_due), 0)); ?>
              </option>
              <option value="mutawatteneen" <?= $default_type === 'mutawatteneen' ? 'selected' : '' ?>>
                Mutawatteneen — Due: ₹<?= htmlspecialchars(format_inr(round($ov_mut_due), 0)); ?>
              </option>
            </select>
          </div>
          <div class="form-group">
            <label for="sabeel_pay_amount">Amount (₹)</label>
            <input type="number" step="0.01" min="0.01" id="sabeel_pay_amount" name="amount" class="form-control"
              placeholder="Enter amount" required />
          </div>
          <input type="hidden" name="its_id" value="<?= htmlspecialchars((string) $its_id_for_pay); ?>" />
          <input type="hidden" name="order_id" value="SABEEL-TAKHMEEN-<?= date('YmdHis'); ?>" />
          <p class="form-text">Payment will be recorded against the selected category.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modal-proceed">Proceed to Pay</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS identical to original -->
<script>
  $(document).on("click", ".view-invoice", function (e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");
    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: { id: paymentId, for: 4 },
      xhrFields: { responseType: 'blob' },
      success: function (response) {
        var url = window.URL.createObjectURL(new Blob([response], { type: "application/pdf" }));
        window.open(url, "_blank");
      },
      error: function () { alert("Failed to generate invoice PDF"); }
    });
  });
</script>