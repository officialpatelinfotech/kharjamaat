<style>
  :root {
    --gold: #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --bg: #faf7f0;
    --surface: #fff;
    --surface-2: #f7f4ec;
    --border: #e8e0cc;
    --text-1: #1a1610;
    --text-2: #5a5244;
    --text-3: #9c8f7a;
    --green: #1a6645;
    --green-bg: #eaf4ee;
    --green-border: rgba(26,102,69,.25);
    --red: #b91c1c;
    --red-bg: #fef2f2;
    --red-border: rgba(185,28,28,.2);
    --blue: #1d4ed8;
    --blue-bg: #eff6ff;
    --blue-border: rgba(29,78,216,.2);
    --amber: #b45309;
    --amber-bg: #fffbeb;
    --amber-border: rgba(180,83,9,.2);
    --sh: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --sh2: 0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
  }

  body {
    background-color: var(--bg) !important;
  }

  /* ── Banner ── */
  .miqaat-banner {
    background: linear-gradient(135deg, #78520a 0%, var(--gold) 60%, #c9a227 100%);
    padding: 16px 20px;
    border-radius: 12px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--sh);
  }
  .miqaat-banner::after {
    content: '';
    position: absolute;
    right: -60px;
    top: -60px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .miqaat-banner-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
  }
  .miqaat-banner-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .miqaat-back {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.3);
    background: rgba(255,255,255,.15);
    color: #fff !important;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    transition: background .15s;
    backdrop-filter: blur(4px);
  }
  .miqaat-back:hover {
    background: rgba(255,255,255,.25);
  }
  .miqaat-banner-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
  }
  .miqaat-banner-sub {
    font-size: 12px;
    color: rgba(255,255,255,.75);
    font-weight: 500;
    margin-top: 2px;
  }

  /* ── Filters Card ── */
  .miqaat-filters-card {
    background: var(--surface);
    border: 1px solid var(--border) !important;
    border-radius: 12px;
    box-shadow: var(--sh);
    padding: 16px;
    margin-bottom: 20px;
  }
  .miqaat-filters-card label {
    font-weight: 700;
    font-size: 11px;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }
  .miqaat-filters-card input,
  .miqaat-filters-card select {
    height: 34px !important;
    border-radius: 8px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface-2) !important;
    font-size: 13px !important;
    color: var(--text-1) !important;
    transition: border-color 0.15s, background-color 0.15s;
  }
  .miqaat-filters-card input:focus,
  .miqaat-filters-card select:focus {
    border-color: var(--gold) !important;
    background: var(--surface) !important;
    box-shadow: none !important;
  }

  /* ── Stats grid ── */
  .miqaat-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .miqaat-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    box-shadow: var(--sh);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .miqaat-stat-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-2);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .stat-breakdown-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    margin-top: 4px;
    border-bottom: 1px dashed rgba(0,0,0,0.05);
    padding-bottom: 4px;
  }
  .stat-breakdown-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .stat-breakdown-row span {
    font-weight: 500;
    color: var(--text-2);
  }
  .stat-breakdown-row strong {
    font-weight: 700;
  }

  /* ── Table Card ── */
  .miqaat-table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--sh);
    overflow: hidden;
    margin-bottom: 30px;
  }
  .miqaat-table-responsive {
    max-height: 60vh;
    overflow-y: auto;
  }
  .miqaat-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
  }
  .miqaat-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: var(--text-1) !important;
    color: #fff !important;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border: none;
    border-bottom: 2px solid var(--border);
  }
  .miqaat-table tbody tr {
    transition: background-color 0.15s;
  }
  .miqaat-table tbody tr:nth-of-type(even) {
    background-color: var(--surface-2);
  }
  .miqaat-table tbody tr:hover {
    background-color: rgba(184, 134, 11, 0.05);
  }
  .miqaat-table tbody td {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .miqaat-table tbody td b {
    color: var(--text-1);
  }

  /* Prevent long text from forcing table overflow */
  .km-table-fixed {
    table-layout: fixed;
    width: 100%;
    min-width: 1720px;
  }
  .km-table-fixed th,
  .km-table-fixed td {
    overflow-wrap: anywhere;
  }
  .km-cell-nowrap {
    white-space: nowrap;
  }
  .km-cell-wrap {
    white-space: normal;
  }
  .km-actions {
    white-space: nowrap;
  }
  
  /* Flash highlight for updated rows */
  .flash-highlight {
    animation: km-flash-bg 1.2s ease-in-out 1;
  }
  @keyframes km-flash-bg {
    0% {
      background-color: #fff3cd;
    }
    100% {
      background-color: transparent;
    }
  }

  /* Sortable headers */
  th.km-sortable {
    cursor: pointer;
    user-select: none;
    white-space: nowrap;
  }
  th.km-sortable .sort-indicator {
    font-size: 11px;
    margin-left: 4px;
    opacity: 0.8;
  }
  
  /* Visually disable buttons but keep them clickable to show reason */
  .km-disabled-btn {
    opacity: 0.55;
    cursor: not-allowed;
  }

  /* Stacked modals z-indexing */
  @media (min-width: 576px) {
    #memberPaymentsModal .modal-dialog {
      max-width: 95% !important;
      width: 95%;
    }
  }
</style>

<div class="margintopcontainer mx-2 mx-md-5 pt-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
  <?php
  $members = [];
  if (isset($member_miqaat_payments)) {
    if (is_array($member_miqaat_payments) && isset($member_miqaat_payments['members']) && is_array($member_miqaat_payments['members'])) {
      $members = $member_miqaat_payments['members'];
    } elseif (is_array($member_miqaat_payments)) {
      $members = $member_miqaat_payments;
    }
  }

  // Build filter values (Sector/Subsector + Hijri years)
  $sectors = [];
  $sub_sectors = [];
  $years_from_invoices = [];
  if (!empty($members) && is_array($members)) {
    foreach ($members as $m) {
      $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
      $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');
      if ($sector !== '' && !in_array($sector, $sectors, true)) $sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $sub_sectors, true)) $sub_sectors[] = $subSector;

      if (isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices'])) {
        foreach ($m['miqaat_invoices'] as $inv) {
          $iy = isset($inv['invoice_year']) ? (string)$inv['invoice_year'] : '';
          if ($iy !== '' && !in_array($iy, $years_from_invoices, true)) $years_from_invoices[] = $iy;
        }
      }
    }
    sort($sectors);
    sort($sub_sectors);
    rsort($years_from_invoices);
  }

  // Prefer controller-provided Hijri years list; fallback to deriving from invoice data.
  $filter_hijri_years = [];
  if (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years)) {
    $filter_hijri_years = array_values(array_unique(array_filter($hijri_years, function ($y) {
      return $y !== null && (string)$y !== '';
    })));
    rsort($filter_hijri_years);
  } else {
    $filter_hijri_years = $years_from_invoices;
  }

  // Determine default year: prefer controller-provided $current_hijri_year if set, else latest from list.
  $default_hijri_year = '';
  if (isset($current_hijri_year) && $current_hijri_year !== '') {
    $default_hijri_year = $current_hijri_year;
  } elseif (!empty($filter_hijri_years)) {
    $default_hijri_year = $filter_hijri_years[0];
  }

  $title_display_year = $default_hijri_year;

  if (!function_exists('inr_format')) {
    function inr_format($num)
    {
      $num = (int)round($num);
      $n = (string)$num;
      if (strlen($n) <= 3) return $n; // no grouping needed
      $last3 = substr($n, -3);
      $rest = substr($n, 0, -3);
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
      return $rest . ',' . $last3;
    }
  }
  ?>

  <!-- Premium Banner -->
  <div class="miqaat-banner">
    <div class="miqaat-banner-inner">
      <div class="miqaat-banner-left">
        <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="miqaat-back">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
          <div class="miqaat-banner-title">
            <i class="fa-solid fa-credit-card" style="margin-right:6px"></i>
            <?php echo isset($miqaat_type) ? htmlspecialchars($miqaat_type) : ''; ?> Miqaat Invoice Payments
            <span id="payment-title-hijri-year" style="font-size:0.9em; font-weight:normal; opacity:0.85; margin-left:4px;">
              <?php echo $title_display_year ? '(Hijri ' . htmlspecialchars($title_display_year) . ')' : ''; ?>
            </span>
          </div>
          <div class="miqaat-banner-sub">View and receive payments for the selected year</div>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($members)) : ?>
    <div id="miqaat-payment-filters" class="miqaat-filters-card">
      <div class="form-row">
        <div class="col-md-3 mb-2">
          <label for="pf-name">Name or ITS</label>
          <input type="text" id="pf-name" class="form-control" placeholder="Search name or ITS...">
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-sector">Sector</label>
          <select id="pf-sector" class="form-control">
            <option value="">All Sectors</option>
            <?php if (!empty($sectors)) : foreach ($sectors as $s) : if ($s === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($s), ENT_QUOTES); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-subsector">Sub Sector</label>
          <select id="pf-subsector" class="form-control">
            <option value="">All Sub Sectors</option>
            <?php if (!empty($sub_sectors)) : foreach ($sub_sectors as $ss) : if ($ss === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($ss), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-year">Hijri Year</label>
          <select id="pf-year" class="form-control" data-default-year="<?php echo htmlspecialchars($default_hijri_year, ENT_QUOTES); ?>">
            <option value="">All Years</option>
            <?php if (!empty($filter_hijri_years)) : foreach ($filter_hijri_years as $y) : ?>
                <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($default_hijri_year === $y ? 'selected' : ''); ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-1 mb-2 d-flex align-items-end">
          <button type="button" id="pf-clear" class="btn btn-outline-secondary w-100" style="height:34px; border-radius:8px;">Clear</button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php
  // Prefer full Hijri years list passed from controller (hijri_calendar); fallback to deriving from invoice data.
  $all_hijri_years = [];
  if (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years)) {
    $all_hijri_years = array_values(array_unique(array_filter($hijri_years, function ($y) {
      return $y !== null && (string)$y !== '';
    })));
    rsort($all_hijri_years);
  }

  // Default sorting: Sector -> Sub_Sector -> Full_Name (case-insensitive)
  if (!empty($members) && is_array($members)) {
    usort($members, function ($a, $b) {
      $sa = isset($a['Sector']) ? strtolower(trim($a['Sector'])) : (isset($a['sector']) ? strtolower(trim($a['sector'])) : '');
      $sb = isset($b['Sector']) ? strtolower(trim($b['Sector'])) : (isset($b['sector']) ? strtolower(trim($b['sector'])) : '');
      if ($sa !== $sb) return $sa < $sb ? -1 : 1;
      $ssa = isset($a['Sub_Sector']) ? strtolower(trim($a['Sub_Sector'])) : (isset($a['sub_sector']) ? strtolower(trim($a['sub_sector'])) : '');
      $ssb = isset($b['Sub_Sector']) ? strtolower(trim($b['Sub_Sector'])) : (isset($b['sub_sector']) ? strtolower(trim($b['sub_sector'])) : '');
      if ($ssa !== $ssb) return $ssa < $ssb ? -1 : 1;
      $na = isset($a['Full_Name']) ? strtolower(trim($a['Full_Name'])) : (isset($a['full_name']) ? strtolower(trim($a['full_name'])) : '');
      $nb = isset($b['Full_Name']) ? strtolower(trim($b['Full_Name'])) : (isset($b['full_name']) ? strtolower(trim($b['full_name'])) : '');
      if ($na === $nb) return 0;
      return $na < $nb ? -1 : 1;
    });
  }

  // Show both HOF and FM rows: remove prior HOF-only filter so family members with invoices/payments appear too.

  $rows = [];
  $invoicePaymentsMap = [];
  $grand_total = 0.0;
  $grand_collected_total = 0.0;
  $grand_due_total = 0.0;
  if (!empty($members)) {
    // Build distinct Sectors, Sub Sectors, and Hijri Years from data
    $sectors = [];
    $sub_sectors = [];
    $years_from_invoices = [];
    foreach ($members as $m) {
      $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
      $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
      $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
      $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');
      if ($sector !== '' && !in_array($sector, $sectors, true)) $sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $sub_sectors, true)) $sub_sectors[] = $subSector;

      // Group payments by invoice_id so each invoice row can show its own history
      $memberPayments = isset($m['payments']) && is_array($m['payments']) ? $m['payments'] : [];
      foreach ($memberPayments as $p) {
        $invId = isset($p['invoice_id']) ? (string)$p['invoice_id'] : '';
        if ($invId === '') continue;
        if (!isset($invoicePaymentsMap[$invId])) $invoicePaymentsMap[$invId] = [];
        $invoicePaymentsMap[$invId][] = $p;
      }

      if (isset($m['miqaat_invoices']) && is_array($m['miqaat_invoices'])) {
        foreach ($m['miqaat_invoices'] as $inv) {
          $invoiceId = isset($inv['invoice_id']) ? (string)$inv['invoice_id'] : '';
          $miqaatDateIso = isset($inv['miqaat_date']) ? (string)$inv['miqaat_date'] : '';
          $invoiceDateIso = isset($inv['invoice_date']) ? (string)$inv['invoice_date'] : '';
          $displayDateIso = $miqaatDateIso !== '' ? $miqaatDateIso : $invoiceDateIso;
          $hijriDate = isset($inv['hijri_date']) ? (string)$inv['hijri_date'] : '';
          $assignedTo = isset($inv['assigned_to']) ? (string)$inv['assigned_to'] : '';
          $individualCount = isset($inv['individual_count']) ? (int)$inv['individual_count'] : 0;
          $assignedKey = strtolower(trim($assignedTo));
          $assignedDisplay = $assignedTo;
          if ($assignedKey === 'individual') {
            $assignedDisplay = $assignedTo . ' (' . $individualCount . ')';
          }

          $amount = isset($inv['invoice_amount']) ? (float)$inv['invoice_amount'] : 0.0;
          $paid = isset($inv['paid_amount']) ? (float)$inv['paid_amount'] : 0.0;
          $due = isset($inv['due_amount']) ? (float)$inv['due_amount'] : ($amount - $paid);
          if ($due < 0) $due = 0.0;

          $row = [
            'invoice_id'   => $invoiceId,
            'its_id'       => $its,
            'full_name'    => $name,
            'sector'       => $sector,
            'sub_sector'   => $subSector,
            'invoice_year' => isset($inv['invoice_year']) ? (string)$inv['invoice_year'] : '',
            'display_date_iso' => $displayDateIso,
            'invoice_date_iso' => $invoiceDateIso,
            'hijri_date'   => $hijriDate,
            'miqaat_id'    => isset($inv['miqaat_id']) ? (string)$inv['miqaat_id'] : '',
            'raza_id'      => isset($inv['raza_id']) ? (string)$inv['raza_id'] : '',
            'miqaat_name'  => isset($inv['miqaat_name']) ? (string)$inv['miqaat_name'] : '',
            'assigned_to'  => $assignedTo,
            'assigned_display' => $assignedDisplay,
            'individual_count' => $individualCount,
            'amount'       => $amount,
            'paid'         => $paid,
            'due'          => $due,
            'description'  => isset($inv['description']) ? $inv['description'] : '',
            'details'      => trim($name . ($its !== '' ? (' (' . $its . ')') : '')),
            'payments'     => ($invoiceId !== '' && isset($invoicePaymentsMap[$invoiceId]) ? $invoicePaymentsMap[$invoiceId] : []),
          ];
          $rows[] = $row;

          $grand_total += (float)$amount;
          $grand_collected_total += (float)$paid;
          $grand_due_total += (float)$due;
          if (isset($inv['invoice_year']) && $inv['invoice_year'] !== '' && !in_array($inv['invoice_year'], $years_from_invoices, true)) {
            $years_from_invoices[] = $inv['invoice_year'];
          }
        }
      }
    }
    sort($sectors);
    sort($sub_sectors);
    rsort($years_from_invoices); // show latest first if present
  }

  // Year dropdown should list all years available; prefer controller list.
  $hijri_years = !empty($all_hijri_years) ? $all_hijri_years : ($years_from_invoices ?? []);

  // Note: Payment view is read-only; Fala ni Niyaz edit/delete logic removed.
  ?>

  <?php if (empty($members)) : ?>
    <div class="col-12 alert alert-info">No invoices found for members.</div>
  <?php else : ?>
  <div class="miqaat-stats-grid">
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Overall Totals</div>
      <div class="stat-breakdown-row text-primary"><span>Invoiced:</span> <strong id="overall-invoiced">₹0</strong></div>
      <div class="stat-breakdown-row text-success"><span>Collected:</span> <strong id="overall-collected">₹0</strong></div>
      <div class="stat-breakdown-row text-danger"><span>Due:</span> <strong id="overall-due">₹0</strong></div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Individual Niyaz</div>
      <div class="stat-breakdown-row text-primary"><span>Invoiced:</span> <strong id="individual-invoiced">₹0</strong></div>
      <div class="stat-breakdown-row text-success"><span>Collected:</span> <strong id="individual-collected">₹0</strong></div>
      <div class="stat-breakdown-row text-danger"><span>Due:</span> <strong id="individual-due">₹0</strong></div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Fala ni Niyaz</div>
      <div class="stat-breakdown-row text-primary"><span>Invoiced:</span> <strong id="fala-invoiced">₹0</strong></div>
      <div class="stat-breakdown-row text-success"><span>Collected:</span> <strong id="fala-collected">₹0</strong></div>
      <div class="stat-breakdown-row text-danger"><span>Due:</span> <strong id="fala-due">₹0</strong></div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Extra Contribution</div>
      <div class="stat-breakdown-row text-primary"><span>Invoiced:</span> <strong id="extra-invoiced">₹0</strong></div>
      <div class="stat-breakdown-row text-success"><span>Collected:</span> <strong id="extra-collected">₹0</strong></div>
      <div class="stat-breakdown-row text-danger"><span>Due:</span> <strong id="extra-due">₹0</strong></div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Invoices Shown</div>
      <div class="miqaat-stat-value text-dark" id="miqaat-payment-invoices-count">0</div>
    </div>
  </div>

  <div class="miqaat-table-card mt-3">
    <div class="miqaat-table-responsive">
      <table class="miqaat-table" id="miqaat-payments-table">
        <colgroup>
          <col style="width: 44px;">
          <col style="width: 128px;">
          <col style="width: 140px;">
          <col style="width: 92px;">
          <col style="width: 92px;">
          <col style="width: 96px;">
          <col style="width: 220px;">
          <col style="width: 140px;">
          <col style="width: 190px;">
          <col style="width: 128px;">
          <col style="width: 96px;">
          <col style="width: 96px;">
          <col style="width: 96px;">
          <col style="width: 160px;">
        </colgroup>
        <thead class="thead-dark table-dark">
          <tr>
            <th class="km-sortable" data-sort-key="index" data-sort-type="number"># <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="date" data-sort-type="date">Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="hijri" data-sort-type="string">Hijri Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatId" data-sort-type="string">Miqaat ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="razaId" data-sort-type="string">Raza ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="invoiceId" data-sort-type="number">Invoice ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatName" data-sort-type="string">Miqaat Name <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="assignedTo" data-sort-type="string">Assigned To <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="details" data-sort-type="string">Details <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="invoiceDate" data-sort-type="date">Invoice Date <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-key="amount" data-sort-type="number">Amount <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-key="paid" data-sort-type="number">Paid <span class="sort-indicator"></span></th>
            <th class="km-sortable text-right" data-sort-key="due" data-sort-type="number">Due <span class="sort-indicator"></span></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="tbody-light">
          <?php foreach ($rows as $i => $r) : ?>
            <?php
            $greg = isset($r['display_date_iso']) ? (string)$r['display_date_iso'] : '';
            $gregFmt = '-';
            if ($greg !== '') {
              $t = strtotime($greg);
              if ($t) $gregFmt = date('d F Y', $t);
            }
            $invIso = isset($r['invoice_date_iso']) ? (string)$r['invoice_date_iso'] : '';
            $invFmt = '-';
            if ($invIso !== '') {
              $ti = strtotime($invIso);
              if ($ti) $invFmt = date('d F Y', $ti);
            }
            $miqaatId = isset($r['miqaat_id']) ? (string)$r['miqaat_id'] : '';
            $razaId = isset($r['raza_id']) ? (string)$r['raza_id'] : '';

            $due = isset($r['due']) ? (float)$r['due'] : 0.0;
            $paid = isset($r['paid']) ? (float)$r['paid'] : 0.0;
            $amount = isset($r['amount']) ? (float)$r['amount'] : 0.0;

            $requiresRaza = ($miqaatId !== '');
            $hasRaza = ($razaId !== '');
            $blockReason = '';
            if ($due <= 0.000001) {
              $blockReason = 'Invoice has no due amount (already fully paid).';
            } elseif ($requiresRaza && !$hasRaza) {
              $blockReason = 'Payment cannot be received for this invoice as Raza has not been submitted for this Miqaat yet.';
            }
            $btnExtraClass = $blockReason ? ' disabled km-disabled-btn' : '';
            $ariaDisabled = $blockReason ? 'true' : 'false';

            $dueClass = ($due > 0.0) ? 'text-danger font-weight-bold' : 'text-success font-weight-bold';
            ?>
            <tr class="miqaat-payment-row"
              data-name="<?php echo htmlspecialchars(strtolower($r['full_name']), ENT_QUOTES); ?>"
              data-its="<?php echo htmlspecialchars(strtolower($r['its_id']), ENT_QUOTES); ?>"
              data-sector="<?php echo htmlspecialchars(strtolower($r['sector']), ENT_QUOTES); ?>"
              data-subsector="<?php echo htmlspecialchars(strtolower($r['sub_sector']), ENT_QUOTES); ?>"
              data-year="<?php echo htmlspecialchars((string)($r['invoice_year'] ?? ''), ENT_QUOTES); ?>"
              data-greg-date="<?php echo htmlspecialchars($greg, ENT_QUOTES); ?>"
              data-invoice-date="<?php echo htmlspecialchars($invIso, ENT_QUOTES); ?>"
              data-hijri-date="<?php echo htmlspecialchars(strtolower((string)($r['hijri_date'] ?? '')), ENT_QUOTES); ?>"
              data-miqaat-id="<?php echo htmlspecialchars($miqaatId, ENT_QUOTES); ?>"
              data-raza-id="<?php echo htmlspecialchars($razaId, ENT_QUOTES); ?>"
              data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"
              data-miqaat-name="<?php echo htmlspecialchars(strtolower((string)($r['miqaat_name'] ?? '')), ENT_QUOTES); ?>"
              data-assigned-to="<?php echo htmlspecialchars(strtolower((string)($r['assigned_display'] ?? '')), ENT_QUOTES); ?>"
              data-individual-count="<?php echo htmlspecialchars((string)($r['individual_count'] ?? 0), ENT_QUOTES); ?>"
              data-amount="<?php echo htmlspecialchars((string)$amount, ENT_QUOTES); ?>"
              data-paid="<?php echo htmlspecialchars((string)$paid, ENT_QUOTES); ?>"
              data-due="<?php echo htmlspecialchars((string)$due, ENT_QUOTES); ?>"
              data-description="<?php echo htmlspecialchars((string)($r['description'] ?? ''), ENT_QUOTES); ?>">
              <td class="km-cell-nowrap"><b><?php echo $i + 1; ?></b></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars($gregFmt); ?></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars((string)($r['hijri_date'] ?? '')); ?></td>
              <td class="km-cell-wrap"><?php echo $miqaatId !== '' ? ('M#' . htmlspecialchars($miqaatId)) : '-'; ?></td>
              <td class="km-cell-wrap"><?php echo $razaId !== '' ? ('R#' . htmlspecialchars($razaId)) : '-'; ?></td>
              <td class="km-cell-wrap"><?php echo !empty($r['invoice_id']) ? ('I#' . htmlspecialchars((string)$r['invoice_id'])) : '-'; ?></td>
              <td class="km-cell-wrap"><b><?php echo htmlspecialchars((string)($r['miqaat_name'] ?? '')); ?></b></td>
              <td class="km-cell-wrap"><b><?php echo htmlspecialchars((string)($r['assigned_display'] ?? '')); ?></b></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars((string)($r['details'] ?? '')); ?></td>
              <td class="invoice-date-cell km-cell-wrap"><?php echo htmlspecialchars($invFmt); ?></td>
              <td class="text-right amount-cell">₹<?php echo inr_format($amount); ?></td>
              <td class="text-right paid-cell">₹<?php echo inr_format($paid); ?></td>
              <td class="text-right due-cell"><span class="<?php echo $dueClass; ?>">₹<?php echo inr_format($due); ?></span></td>
              <td class="text-center km-actions">
                <div class="btn-group-vertical btn-group-sm" role="group" aria-label="Actions">
                  <button type="button" class="btn btn-success receive-payment-btn<?php echo $btnExtraClass; ?>"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"
                    data-amount="<?php echo htmlspecialchars((string)$due, ENT_QUOTES); ?>"
                    data-block-reason="<?php echo htmlspecialchars($blockReason, ENT_QUOTES); ?>"
                    aria-disabled="<?php echo $ariaDisabled; ?>"
                    title="<?php echo htmlspecialchars($blockReason, ENT_QUOTES); ?>">Receive Payment</button>
                  <button type="button" class="btn btn-outline-info view-payments-btn"
                    data-toggle="modal"
                    data-target="#memberPaymentsModal"
                    data-its="<?php echo htmlspecialchars((string)($r['its_id'] ?? '')); ?>"
                    data-name="<?php echo htmlspecialchars((string)($r['full_name'] ?? '')); ?>"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"
                    data-payments='<?php echo htmlspecialchars(json_encode($r['payments'] ?? []), ENT_QUOTES, "UTF-8"); ?>'>Payment History</button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
    <!-- Member Payments Modal -->
    <div class="modal fade" id="memberPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="memberPaymentsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="memberPaymentsModalLabel">Payment History</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <strong>ITS:</strong> <span id="payments-modal-its"></span>
              <strong>Name:</strong> <span id="payments-modal-name"></span>
              <strong>Invoice:</strong> <span id="payments-modal-invoice"></span>
            </div>
            <div id="payments-modal-table-wrapper" class="table-responsive"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Payment Modal (amount only for now) -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="edit-payment-form">
              <input type="hidden" id="ep-payment-id" name="payment_id" />
              <input type="hidden" id="ep-invoice-id" name="invoice_id" />
              <div class="form-group">
                <label>Payment ID</label>
                <input type="text" id="ep-payment-id-display" class="form-control" readonly />
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input type="number" id="ep-amount" name="amount" class="form-control" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="ep-max-hint"></small>
              </div>
            </form>
            <div id="ep-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" id="ep-submit" class="btn btn-primary">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Receive Payment Modal (invoice-wise) -->
    <div class="modal fade" id="receiveInvoicePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receiveInvoicePaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="receiveInvoicePaymentModalLabel">Receive Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="border rounded p-2 mb-3 bg-light">
              <label class="form-label mb-0"><b>Miqaat Details:</b></label>
              <div id="rip-details" class="small text-dark mt-2"></div>
            </div>
            <form id="invoice-payment-form">
              <input type="hidden" id="rip-invoice-id" name="invoice_id" />
              <div class="form-group">
                <label>Invoice ID</label>
                <input type="text" id="rip-invoice-id-display" class="form-control" readonly />
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input type="number" id="rip-amount" name="amount" class="form-control" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="rip-max-hint"></small>
              </div>
              <div class="form-group">
                <label>Payment Date</label>
                <input type="date" id="rip-date" name="payment_date" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Payment Method</label>
                <select id="rip-method" name="payment_method" class="form-control">
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                  <option value="NEFT">NEFT</option>
                </select>
              </div>
              <div class="form-group">
                <label>Remarks</label>
                <textarea id="rip-remarks" name="remarks" class="form-control" rows="2" placeholder="Optional"></textarea>
              </div>
            </form>
            <div id="rip-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="rip-submit" class="btn btn-primary">Save Payment</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      const extraContributions = <?php echo json_encode($extra_contributions ?? []); ?>;
      (function() {
        function currency(n) {
          if (n === undefined || n === null) return '₹0';
          const num = Math.round(parseFloat(n) || 0);
          return '₹' + num.toLocaleString('en-IN');
        }

        function parseCurrency(text) {
          if (text == null) return 0;
          const n = String(text).replace(/[^0-9.-]/g, '');
          const v = parseFloat(n);
          return isNaN(v) ? 0 : v;
        }

        function escapeHtml(str) {
          return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        function formatDate(d) {
          if (!d) return '';
          const dt = new Date(String(d).replace(/-/g, '/'));
          if (isNaN(dt)) return String(d);
          const dd = String(dt.getDate()).padStart(2, '0');
          const mm = String(dt.getMonth() + 1).padStart(2, '0');
          const yyyy = dt.getFullYear();
          return `${dd}-${mm}-${yyyy}`;
        }

        // Filters for invoice list (name/its/sector/sub-sector/year) with totals
        function applyPaymentFilters() {
          const nameVal = (document.getElementById('pf-name').value || '').trim().toLowerCase();
          const sectorVal = (document.getElementById('pf-sector').value || '').trim().toLowerCase();
          const subVal = (document.getElementById('pf-subsector').value || '').trim().toLowerCase();
          const yearVal = (document.getElementById('pf-year').value || '').trim().toLowerCase();

          const rows = document.querySelectorAll('#miqaat-payments-table tbody tr.miqaat-payment-row');
          let index = 1;
          let visibleCount = 0;

          let individualInvoiced = 0;
          let individualCollected = 0;
          let individualDue = 0;

          let falaInvoiced = 0;
          let falaCollected = 0;
          let falaDue = 0;

          rows.forEach(r => {
            const rName = (r.getAttribute('data-name') || '').trim();
            const rIts = (r.getAttribute('data-its') || '').trim();
            const rSector = (r.getAttribute('data-sector') || '').trim();
            const rSub = (r.getAttribute('data-subsector') || '').trim();
            const rYear = (r.getAttribute('data-year') || '').trim();

            let show = true;
            if (nameVal && rName.indexOf(nameVal) === -1 && rIts.indexOf(nameVal) === -1) show = false;
            if (sectorVal && rSector !== sectorVal) show = false;
            if (subVal && rSub !== subVal) show = false;
            if (yearVal && rYear.toLowerCase() !== yearVal) show = false;

            if (!show) {
              r.style.display = 'none';
              return;
            }

            r.style.display = '';
            const firstCell = r.querySelector('td');
            if (firstCell) firstCell.textContent = index++;

            visibleCount += 1;
            
            const amt = parseFloat(r.getAttribute('data-amount') || '0') || 0;
            const paid = parseFloat(r.getAttribute('data-paid') || '0') || 0;
            const due = parseFloat(r.getAttribute('data-due') || '0') || 0;
            const assignedTo = (r.getAttribute('data-assigned-to') || '').trim();
            if (assignedTo === 'fala ni niyaz' || assignedTo.startsWith('fala ni niyaz')) {
              falaInvoiced += amt;
              falaCollected += paid;
              falaDue += due;
            } else {
              individualInvoiced += amt;
              individualCollected += paid;
              individualDue += due;
            }
          });

          let extraInvoiced = 0;
          let extraCollected = 0;
          let extraDue = 0;

          extraContributions.forEach(function(c) {
            const cName = (c.Full_Name || '').trim().toLowerCase();
            const cIts = (c.ITS_ID || '').trim().toLowerCase();
            const cSector = (c.Sector || '').trim().toLowerCase();
            const cSub = (c.Sub_Sector || '').trim().toLowerCase();
            const cYear = (c.contri_year || '').trim().toLowerCase();

            let show = true;
            if (nameVal && cName.indexOf(nameVal) === -1 && cIts.indexOf(nameVal) === -1) show = false;
            if (sectorVal && cSector !== sectorVal) show = false;
            if (subVal && cSub !== subVal) show = false;
            if (yearVal && !cYear.includes(yearVal)) show = false;

            if (show) {
              const amt = parseFloat(c.amount || '0') || 0;
              const paid = parseFloat(c.paid_amount || '0') || 0;
              const due = Math.max(0, amt - paid);
              extraInvoiced += amt;
              extraCollected += paid;
              extraDue += due;
            }
          });

          // Overall totals
          const overallInvoiced = individualInvoiced + falaInvoiced + extraInvoiced;
          const overallCollected = individualCollected + falaCollected + extraCollected;
          const overallDue = individualDue + falaDue + extraDue;

          // Populate Overall Totals
          const oInv = document.getElementById('overall-invoiced');
          if (oInv) oInv.textContent = currency(overallInvoiced);
          const oCol = document.getElementById('overall-collected');
          if (oCol) oCol.textContent = currency(overallCollected);
          const oDue = document.getElementById('overall-due');
          if (oDue) oDue.textContent = currency(overallDue);

          // Populate Individual Niyaz
          const iInv = document.getElementById('individual-invoiced');
          if (iInv) iInv.textContent = currency(individualInvoiced);
          const iCol = document.getElementById('individual-collected');
          if (iCol) iCol.textContent = currency(individualCollected);
          const iDue = document.getElementById('individual-due');
          if (iDue) iDue.textContent = currency(individualDue);

          // Populate Fala ni Niyaz
          const fInv = document.getElementById('fala-invoiced');
          if (fInv) fInv.textContent = currency(falaInvoiced);
          const fCol = document.getElementById('fala-collected');
          if (fCol) fCol.textContent = currency(falaCollected);
          const fDue = document.getElementById('fala-due');
          if (fDue) fDue.textContent = currency(falaDue);

          // Populate Extra Contribution
          const eInv = document.getElementById('extra-invoiced');
          if (eInv) eInv.textContent = currency(extraInvoiced);
          const eCol = document.getElementById('extra-collected');
          if (eCol) eCol.textContent = currency(extraCollected);
          const eDue = document.getElementById('extra-due');
          if (eDue) eDue.textContent = currency(extraDue);

          // Populate Count
          const countEl = document.getElementById('miqaat-payment-invoices-count');
          if (countEl) countEl.textContent = String(visibleCount);
        }

        const pfName = document.getElementById('pf-name');
        const pfSector = document.getElementById('pf-sector');
        const pfSub = document.getElementById('pf-subsector');
        const pfYear = document.getElementById('pf-year');
        if (pfName) pfName.addEventListener('input', applyPaymentFilters);
        if (pfSector) pfSector.addEventListener('change', applyPaymentFilters);
        if (pfSub) pfSub.addEventListener('change', applyPaymentFilters);
        if (pfYear) pfYear.addEventListener('change', function() {
          applyPaymentFilters();
          const titleYearEl = document.getElementById('payment-title-hijri-year');
          if (titleYearEl) {
            const y = pfYear.value.trim();
            if (y) {
              titleYearEl.textContent = '(Hijri ' + y + ')';
            } else {
              // fallback to default-year data attribute
              const defYear = (pfYear.getAttribute('data-default-year') || '').trim();
              titleYearEl.textContent = defYear ? '(Hijri ' + defYear + ')' : '';
            }
          }
        });
        const pfClear = document.getElementById('pf-clear');
        if (pfClear) {
          pfClear.addEventListener('click', function() {
            if (pfName) pfName.value = '';
            if (pfSector) pfSector.value = '';
            if (pfSub) pfSub.value = '';
            if (pfYear) pfYear.value = '';
            applyPaymentFilters();
          });
        }

        // Apply default year filter on initial load
        if (pfYear) {
          const defYear = (pfYear.getAttribute('data-default-year') || '').trim();
          if (defYear) {
            pfYear.value = defYear;
            applyPaymentFilters();
            const titleYearEl = document.getElementById('payment-title-hijri-year');
            if (titleYearEl) {
              titleYearEl.textContent = '(Hijri ' + defYear + ')';
            }
          }
        }

        // Ensure summary bar is initialized even when default year is empty
        applyPaymentFilters();

        // Ensure proper overlay handling for stacked modals (member invoices -> receive payment)
        if (window.jQuery) {
          // When a modal is shown, bump its z-index above existing modals and adjust the backdrop
          jQuery(document).on('show.bs.modal', '.modal', function() {
            var $visibleModals = jQuery('.modal:visible');
            var zIndex = 1040 + (10 * $visibleModals.length);
            jQuery(this).css('z-index', zIndex);
            // Next tick so backdrop is inserted first
            setTimeout(function() {
              jQuery('.modal-backdrop').not('.modal-stack')
                .css('z-index', zIndex - 1)
                .addClass('modal-stack');
            }, 0);
          });

          // Keep body from losing scroll lock when one of multiple modals closes
          jQuery(document).on('hidden.bs.modal', '.modal', function() {
            if (jQuery('.modal:visible').length) {
              jQuery('body').addClass('modal-open');
            }
          });

        }

        function buildPaymentsTable(payments, showActions = true) {
          if (!payments || !payments.length) {
            return '<div class="alert alert-info mb-0">No payments found.</div>';
          }
          let total = 0;
          const rows = payments.map(p => {
            const amt = parseFloat(p.amount || 0);
            total += amt;
            const dt = p.date || p.payment_date || '';
            const method = p.payment_method || '';
            const invId = p.invoice_id || '';
            const miqaatName = p.miqaat_name || '';
            const viewBtn = `<button type="button" class="btn btn-sm btn-outline-secondary view-receipt-btn" data-payment-id="${p.payment_id || ''}"><i class="fa-solid fa-file-pdf"></i></button>`;
            const actionBtns = showActions ? `
                <td class="text-center">
                  ${viewBtn}
                  <button type="button" class="btn btn-sm btn-outline-primary ml-2 edit-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-id="${invId}"><i class="fa-solid fa-pen"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-danger ml-2 delete-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-id="${invId}"><i class="fa-solid fa-trash"></i></button>
                </td>` : '';
            return `
              <tr data-payment-id="${p.payment_id || ''}" data-invoice-id="${invId}">
                <td>${p.payment_id ? p.payment_id : ''}</td>
                <td class="text-right">${currency(amt)}</td>
                <td>${formatDate(dt)}</td>
                <td>${method}</td>
                <td>${invId}</td>
                <td>${miqaatName}</td>
                ${actionBtns}
              </tr>
            `;
          }).join('');
          const actionsHeader = showActions ? '<th>Actions</th>' : '';
          const colCount = showActions ? 7 : 6; // total columns rendered
          const colWidth = (100 / colCount).toFixed(2) + '%';
          const cols = Array.from({
            length: colCount
          }).map(() => `<col style="width:${colWidth}">`).join('');
          const tfootColsSpan = showActions ? 5 : 4; // remaining columns after 2 in total row
          return `
            <table class="table table-sm table-striped table-bordered mb-0 km-flex-columns km-table-accent">
              <thead class="thead-light">
                <tr>
                  <th>Payment ID</th>
                  <th class="text-right">Amount</th>
                  <th>Date</th>
                  <th>Method</th>
                  <th>Invoice</th>
                  <th>Miqaat</th>
                  ${actionsHeader}
                </tr>
              </thead>
              <tbody>${rows}</tbody>
              <tfoot>
                <tr>
                  <th class="text-right">Total</th>
                  <th class="text-right">${currency(total)}</th>
                  <th colspan="${tfootColsSpan}"></th>
                </tr>
              </tfoot>
            </table>
          `;
        }
        // View Receipt button: fetch PDF via AJAX (POST) with payment_id & for=2, then open blob
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-receipt-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          if (!pid) return;
          const endpoint = '<?php echo base_url('common/generate_pdf'); ?>';

          // Provide light UI feedback
          btn.disabled = true;
          btn.classList.add('disabled');

          fetch(endpoint, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: 'id=' + encodeURIComponent(pid) + '&for=2'
            })
            .then(async (res) => {
              const ct = (res.headers.get('Content-Type') || '').toLowerCase();
              if (!res.ok) {
                // Try to surface any error text
                let msg = 'HTTP ' + res.status;
                try {
                  const t = await res.text();
                  if (t) msg += ': ' + t.substring(0, 200);
                } catch (_) {}
                throw new Error(msg);
              }
              if (ct.includes('application/pdf')) {
                return res.blob();
              }
              // Unexpected content-type; attempt to parse for message
              try {
                const t = await res.text();
                throw new Error(t.substring(0, 300) || 'Unexpected response (not PDF).');
              } catch (err) {
                throw err;
              }
            })
            .then((blob) => {
              const fileURL = URL.createObjectURL(blob);
              window.open(fileURL, '_blank');
              // Revoke after a minute to free memory
              setTimeout(() => URL.revokeObjectURL(fileURL), 60000);
            })
            .catch((err) => {
              alert('Unable to fetch receipt: ' + err.message);
            })
            .finally(() => {
              btn.disabled = false;
              btn.classList.remove('disabled');
            });
        });

        let lastReceiveBtn = null;

        // Payment History: Modal
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.view-payments-btn');
          if (!btn) return;
          window.currentPaymentsTriggerBtn = btn; // track which member triggered this modal
          const its = btn.getAttribute('data-its') || '';
          const name = btn.getAttribute('data-name') || '';
          const invoiceId = btn.getAttribute('data-invoice-id') || '';
          let payments = [];
          try {
            payments = JSON.parse(btn.getAttribute('data-payments') || '[]');
          } catch (_) {
            payments = [];
          }
          document.getElementById('payments-modal-its').textContent = its;
          document.getElementById('payments-modal-name').textContent = name;
          document.getElementById('payments-modal-invoice').textContent = invoiceId ? ('I#' + invoiceId) : '';
          document.getElementById('payments-modal-table-wrapper').innerHTML = buildPaymentsTable(payments, true);
        });

        // Payment History: Inline toggle
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.toggle-payments-inline');
          if (!btn) return;
          const targetSel = btn.getAttribute('data-target');
          const row = document.querySelector(targetSel);
          let payments = [];
          try {
            payments = JSON.parse(btn.getAttribute('data-payments') || '[]');
          } catch (_) {
            payments = [];
          }
          if (row) {
            const container = row.querySelector('.payment-history-container');
            container.innerHTML = buildPaymentsTable(payments, false);
            row.classList.toggle('d-none');
          }
        });

        // Edit Payment button click (open modal)
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.edit-payment-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');
          const invId = btn.getAttribute('data-invoice-id') || '';
          document.getElementById('ep-payment-id').value = pid || '';
          document.getElementById('ep-invoice-id').value = invId || '';
          document.getElementById('ep-payment-id-display').value = pid || '';
          document.getElementById('ep-amount').value = isNaN(amt) ? '' : amt.toFixed(2);
          // Set max for editing: cannot exceed (invoice total - other payments)
          // We don't have full context for invoice total here, so we'll compute max from current modal/payment table if available
          (function setEditMax() {
            const epMaxHint = document.getElementById('ep-max-hint');
            const input = document.getElementById('ep-amount');
            let maxAllowed = null;
            try {
              const trigger = window.currentPaymentsTriggerBtn;
              const mainRow = trigger ? trigger.closest('tr.miqaat-payment-row') : null;
              if (mainRow) {
                const due = parseFloat(mainRow.getAttribute('data-due') || '0');
                // Max = current amount + remaining due
                maxAllowed = (isNaN(amt) ? 0 : amt) + (isNaN(due) ? 0 : due);
              }
            } catch (e) {}
            if (maxAllowed !== null) {
              input.max = maxAllowed.toFixed(2);
              epMaxHint.textContent = 'Max allowed: ' + currency(maxAllowed);
            } else {
              input.removeAttribute('max');
              epMaxHint.textContent = '';
            }
          })();
          const alertBox = document.getElementById('ep-alert');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery('#editPaymentModal').modal('show');
          }
        });

        // Save edited payment amount
        document.getElementById('ep-submit').addEventListener('click', function() {
          const pid = document.getElementById('ep-payment-id').value;
          const invId = document.getElementById('ep-invoice-id').value;
          const newAmt = parseFloat(document.getElementById('ep-amount').value || '0');
          const alertBox = document.getElementById('ep-alert');
          const submitBtn = document.getElementById('ep-submit');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          if (!pid || isNaN(newAmt) || newAmt <= 0) {
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = 'Please enter a valid amount (> 0).';
            alertBox.classList.remove('d-none');
            return;
          }
          // Client-side guard: respect max if set
          const epMaxAttr = document.getElementById('ep-amount').getAttribute('max');
          if (epMaxAttr) {
            const maxVal = parseFloat(epMaxAttr);
            if (!isNaN(maxVal) && newAmt - maxVal > 1e-6) {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Amount exceeds allowed maximum (' + currency(maxVal) + ').';
              alertBox.classList.remove('d-none');
              return;
            }
          }
          submitBtn.disabled = true;
          fetch('<?php echo base_url('anjuman/update_miqaat_payment_amount'); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `payment_id=${encodeURIComponent(pid)}&amount=${encodeURIComponent(newAmt.toFixed(2))}`
            }).then(r => r.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                // Update UI in payments modal table
                const table = document.querySelector('#memberPaymentsModal table');
                if (table) {
                  const tr = table.querySelector(`tbody tr[data-payment-id="${pid}"]`);
                  if (tr) {
                    const amtCell = tr.children[1];
                    const oldAmt = parseCurrency(amtCell.textContent);
                    const delta = newAmt - oldAmt;
                    amtCell.textContent = currency(newAmt);
                    // Update footer total
                    const tfootAmtCell = table.querySelector('tfoot th.text-right:nth-child(2)');
                    if (tfootAmtCell) {
                      const prevTotal = parseCurrency(tfootAmtCell.textContent);
                      tfootAmtCell.textContent = currency(prevTotal + delta);
                    }
                    // Update main row totals
                    if (window.currentPaymentsTriggerBtn) {
                      const mainRow = window.currentPaymentsTriggerBtn.closest('tr.miqaat-payment-row');
                      if (mainRow) {
                        const paidPrev = parseFloat(mainRow.getAttribute('data-paid') || '0') || 0;
                        const duePrev = parseFloat(mainRow.getAttribute('data-due') || '0') || 0;
                        const paidNew = paidPrev + delta;
                        const dueNew = Math.max(0, duePrev - delta);
                        mainRow.setAttribute('data-paid', String(paidNew));
                        mainRow.setAttribute('data-due', String(dueNew));
                        const paidCell = mainRow.querySelector('.paid-cell');
                        const dueCell = mainRow.querySelector('.due-cell');
                        if (paidCell) paidCell.textContent = currency(paidNew);
                        if (dueCell) {
                          dueCell.innerHTML = '<span class="' + (dueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(dueNew) + '</span>';
                        }
                        // Update receive button remaining due
                        const rbtn = mainRow.querySelector('.receive-payment-btn');
                        if (rbtn) {
                          rbtn.setAttribute('data-amount', String(dueNew));
                          const reason = (rbtn.getAttribute('data-block-reason') || '').trim();
                          if (dueNew <= 0.000001) {
                            rbtn.setAttribute('data-block-reason', 'Invoice has no due amount (already fully paid).');
                            rbtn.setAttribute('aria-disabled', 'true');
                            rbtn.classList.add('disabled', 'km-disabled-btn');
                            rbtn.title = 'Invoice has no due amount (already fully paid).';
                          } else if (reason === 'Invoice has no due amount (already fully paid).') {
                            rbtn.setAttribute('data-block-reason', '');
                            rbtn.setAttribute('aria-disabled', 'false');
                            rbtn.classList.remove('disabled', 'km-disabled-btn');
                            rbtn.title = '';
                          }
                        }
                        mainRow.classList.remove('flash-highlight');
                        void mainRow.offsetWidth;
                        mainRow.classList.add('flash-highlight');
                      }
                    }
                    // Update payments data payload on trigger button
                    if (window.currentPaymentsTriggerBtn) {
                      try {
                        const pjson = window.currentPaymentsTriggerBtn.getAttribute('data-payments') || '[]';
                        const parr = JSON.parse(pjson);
                        for (let i = 0; i < parr.length; i++) {
                          if (String(parr[i].payment_id) === String(pid)) {
                            parr[i].amount = newAmt.toFixed(2);
                            break;
                          }
                        }
                        window.currentPaymentsTriggerBtn.setAttribute('data-payments', JSON.stringify(parr));
                      } catch (e) {
                        /* ignore */
                      }
                    }
                    applyPaymentFilters();
                  }
                }
                // Success alert and close
                alertBox.className = 'alert alert-success';
                alertBox.textContent = 'Payment updated.';
                alertBox.classList.remove('d-none');
                setTimeout(() => {
                  if (window.jQuery) jQuery('#editPaymentModal').modal('hide');
                }, 600);
              } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = (data && data.error) ? data.error : 'Update failed.';
                alertBox.classList.remove('d-none');
              }
            })
            .catch(() => {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Network error. Please try again.';
              alertBox.classList.remove('d-none');
            })
            .finally(() => {
              submitBtn.disabled = false;
            });
        });

        // Delete Payment button click
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.delete-payment-btn');
          if (!btn) return;
          const pid = btn.getAttribute('data-payment-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');
          const invId = btn.getAttribute('data-invoice-id') || '';
          if (!pid) return;
          if (!confirm('Are you sure you want to delete this payment?')) return;
          fetch('<?php echo base_url('anjuman/delete_miqaat_payment'); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `payment_id=${encodeURIComponent(pid)}`
            }).then(r => r.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                // Remove row from table and update totals
                const table = document.querySelector('#memberPaymentsModal table');
                if (table) {
                  const tr = table.querySelector(`tbody tr[data-payment-id="${pid}"]`);
                  if (tr) {
                    // Update footer total (subtract amt)
                    const tfootAmtCell = table.querySelector('tfoot th.text-right:nth-child(2)');
                    if (tfootAmtCell) {
                      const prevTotal = parseCurrency(tfootAmtCell.textContent);
                      tfootAmtCell.textContent = currency(Math.max(0, prevTotal - amt));
                    }
                    tr.parentNode.removeChild(tr);
                  }
                  // If no more rows, show empty state
                  if (!table.querySelector('tbody tr')) {
                    document.getElementById('payments-modal-table-wrapper').innerHTML = '<div class="alert alert-info mb-0">No payments found.</div>';
                  }
                }
                // Update main row totals (reduce paid, increase due)
                if (window.currentPaymentsTriggerBtn) {
                  const mainRow = window.currentPaymentsTriggerBtn.closest('tr.miqaat-payment-row');
                  if (mainRow) {
                    const paidPrev = parseFloat(mainRow.getAttribute('data-paid') || '0') || 0;
                    const duePrev = parseFloat(mainRow.getAttribute('data-due') || '0') || 0;
                    const paidNew = Math.max(0, paidPrev - amt);
                    const dueNew = duePrev + amt;
                    mainRow.setAttribute('data-paid', String(paidNew));
                    mainRow.setAttribute('data-due', String(dueNew));
                    const paidCell = mainRow.querySelector('.paid-cell');
                    const dueCell = mainRow.querySelector('.due-cell');
                    if (paidCell) paidCell.textContent = currency(paidNew);
                    if (dueCell) {
                      dueCell.innerHTML = '<span class="' + (dueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(dueNew) + '</span>';
                    }
                    const rbtn = mainRow.querySelector('.receive-payment-btn');
                    if (rbtn) {
                      rbtn.setAttribute('data-amount', String(dueNew));
                      // If it was disabled due to fully paid, enable back (unless blocked for other reasons)
                      const reason = (rbtn.getAttribute('data-block-reason') || '').trim();
                      if (reason === 'Invoice has no due amount (already fully paid).') {
                        rbtn.setAttribute('data-block-reason', '');
                        rbtn.setAttribute('aria-disabled', 'false');
                        rbtn.classList.remove('disabled', 'km-disabled-btn');
                        rbtn.title = '';
                      }
                    }
                    mainRow.classList.remove('flash-highlight');
                    void mainRow.offsetWidth;
                    mainRow.classList.add('flash-highlight');
                  }
                }
                // Update payments data payload on trigger button
                if (window.currentPaymentsTriggerBtn) {
                  try {
                    const pjson = window.currentPaymentsTriggerBtn.getAttribute('data-payments') || '[]';
                    let parr = JSON.parse(pjson);
                    parr = parr.filter(x => String(x.payment_id) !== String(pid));
                    window.currentPaymentsTriggerBtn.setAttribute('data-payments', JSON.stringify(parr));
                  } catch (e) {
                    /* ignore */
                  }
                }
                applyPaymentFilters();
              } else {
                alert('Delete failed.');
              }
            })
            .catch(() => alert('Network error while deleting.'));
        });

        // Receive Payment (invoice-wise)
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.receive-payment-btn');
          if (!btn) return;
          const blockReason = (btn.getAttribute('data-block-reason') || '').trim();
          if (blockReason) {
            alert(blockReason);
            return;
          }
          lastReceiveBtn = btn;
          const row = btn.closest('tr.miqaat-payment-row');
          const invId = btn.getAttribute('data-invoice-id');
          const amt = parseFloat(btn.getAttribute('data-amount') || '0');

          // Populate details block (match Update page structure as closely as possible)
          (function fillDetails() {
            const detailsEl = document.getElementById('rip-details');
            if (!detailsEl || !row) return;
            const tds = row.querySelectorAll('td');
            const dateText = tds[1] ? (tds[1].textContent || '-').trim() : '-';
            const hijriText = tds[2] ? (tds[2].textContent || '-').trim() : '-';
            const miqaatIdText = tds[3] ? (tds[3].textContent || '-').trim() : '-';
            const razaIdText = tds[4] ? (tds[4].textContent || '-').trim() : '-';
            const invoiceIdText = tds[5] ? (tds[5].textContent || '-').trim() : '-';
            const miqaatNameText = tds[6] ? (tds[6].textContent || '-').trim() : '-';
            const assignedText = tds[7] ? (tds[7].textContent || '-').trim() : '-';
            const extraDetailsText = tds[8] ? (tds[8].textContent || '-').trim() : '-';
            detailsEl.innerHTML = `
              <div><b>Miqaat ID:</b> ${escapeHtml(miqaatIdText)}</div>
              <div><b>Raza ID:</b> ${escapeHtml(razaIdText)}</div>
              <div><b>Invoice ID:</b> ${escapeHtml(invoiceIdText)}</div>
              <div><b>Miqaat Name:</b> ${escapeHtml(miqaatNameText)}</div>
              <div><b>Date:</b> ${escapeHtml(dateText)}</div>
              <div><b>Hijri:</b> ${escapeHtml(hijriText)}</div>
              <div><b>Assigned:</b> ${escapeHtml(assignedText)}</div>
              <div><b>Details:</b> ${escapeHtml(extraDetailsText)}</div>
            `;
          })();

          // Prefill modal fields
          document.getElementById('rip-invoice-id').value = invId || '';
          document.getElementById('rip-invoice-id-display').value = invId || '';
          document.getElementById('rip-amount').value = isNaN(amt) ? '' : amt.toFixed(2);
          // Set max to current due
          const ripAmountInput = document.getElementById('rip-amount');
          if (!isNaN(amt) && amt > 0) {
            ripAmountInput.max = amt.toFixed(2);
            const hint = document.getElementById('rip-max-hint');
            if (hint) hint.textContent = 'Max allowed: ' + currency(amt);
          } else {
            ripAmountInput.removeAttribute('max');
            const hint = document.getElementById('rip-max-hint');
            if (hint) hint.textContent = '';
          }
          const today = new Date();
          const yyyy = today.getFullYear();
          const mm = String(today.getMonth() + 1).padStart(2, '0');
          const dd = String(today.getDate()).padStart(2, '0');
          document.getElementById('rip-date').value = `${yyyy}-${mm}-${dd}`;
          document.getElementById('rip-method').value = 'Cash';
          document.getElementById('rip-remarks').value = '';
          const alertBox = document.getElementById('rip-alert');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';
          // Show modal (stacked over the member invoices modal). If Bootstrap JS is available, it will handle backdrops.
          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery('#receiveInvoicePaymentModal').modal('show');
          } else {
            document.getElementById('receiveInvoicePaymentModal').classList.add('show');
            // Minimal fallback: add a backdrop if Bootstrap JS isn't present
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'fallback-modal-backdrop';
            document.body.appendChild(backdrop);
            document.body.classList.add('modal-open');
          }
        });

        document.getElementById('rip-submit').addEventListener('click', function() {
          const invId = document.getElementById('rip-invoice-id').value;
          const amount = parseFloat(document.getElementById('rip-amount').value || '');
          const date = document.getElementById('rip-date').value;
          const method = document.getElementById('rip-method').value;
          const remarks = document.getElementById('rip-remarks').value;

          const alertBox = document.getElementById('rip-alert');
          const submitBtn = document.getElementById('rip-submit');
          alertBox.classList.add('d-none');
          alertBox.textContent = '';

          if (!invId || isNaN(amount) || amount <= 0 || !date) {
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = 'Please provide valid amount and date.';
            alertBox.classList.remove('d-none');
            return;
          }
          // Client-side guard: ensure amount <= max
          const ripMaxAttr = document.getElementById('rip-amount').getAttribute('max');
          if (ripMaxAttr) {
            const maxVal = parseFloat(ripMaxAttr);
            if (!isNaN(maxVal) && amount - maxVal > 1e-6) {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Amount exceeds due. Max: ' + currency(maxVal);
              alertBox.classList.remove('d-none');
              return;
            }
          }

          submitBtn.disabled = true;
          fetch('<?php echo base_url("anjuman/add_miqaat_invoice_payment"); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `invoice_id=${encodeURIComponent(invId)}&amount=${encodeURIComponent(amount.toFixed(2))}&payment_date=${encodeURIComponent(date)}&payment_method=${encodeURIComponent(method)}&remarks=${encodeURIComponent(remarks || '')}`
            })
            .then(res => res.json().catch(() => ({
              success: false
            })))
            .then(data => {
              if (data && data.success) {
                alertBox.className = 'alert alert-success';
                alertBox.textContent = 'Payment recorded successfully.';
                alertBox.classList.remove('d-none');

                // Sync UI: update the invoice row Paid/Due and history payload
                try {
                  const paidAmount = amount; // amount just posted

                  if (lastReceiveBtn) {
                    const mainRow = lastReceiveBtn.closest('tr.miqaat-payment-row');
                    if (mainRow) {
                      const paidPrev = parseFloat(mainRow.getAttribute('data-paid') || '0') || 0;
                      const duePrev = parseFloat(mainRow.getAttribute('data-due') || '0') || 0;
                      const paidNew = paidPrev + paidAmount;
                      const dueNew = Math.max(0, duePrev - paidAmount);
                      mainRow.setAttribute('data-paid', String(paidNew));
                      mainRow.setAttribute('data-due', String(dueNew));
                      const paidCell = mainRow.querySelector('.paid-cell');
                      const dueCell = mainRow.querySelector('.due-cell');
                      if (paidCell) paidCell.textContent = currency(paidNew);
                      if (dueCell) {
                        dueCell.innerHTML = '<span class="' + (dueNew > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(dueNew) + '</span>';
                      }

                      lastReceiveBtn.setAttribute('data-amount', String(dueNew));
                      if (dueNew <= 0.000001) {
                        lastReceiveBtn.setAttribute('data-block-reason', 'Invoice has no due amount (already fully paid).');
                        lastReceiveBtn.setAttribute('aria-disabled', 'true');
                        lastReceiveBtn.classList.add('disabled', 'km-disabled-btn');
                        lastReceiveBtn.title = 'Invoice has no due amount (already fully paid).';
                      }

                      mainRow.classList.remove('flash-highlight');
                      void mainRow.offsetWidth;
                      mainRow.classList.add('flash-highlight');
                    }
                  }
                } catch (e) {
                  // Swallow UI sync errors; server has already saved
                }

                applyPaymentFilters();

                // Optionally close after short delay
                setTimeout(() => {
                  if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
                    jQuery('#receiveInvoicePaymentModal').modal('hide');
                  }
                  // Fallback close if Bootstrap JS isn't present
                  else {
                    var modal = document.getElementById('receiveInvoicePaymentModal');
                    modal.classList.remove('show');
                    var fb = document.getElementById('fallback-modal-backdrop');
                    if (fb) {
                      fb.parentNode.removeChild(fb);
                    }
                    document.body.classList.remove('modal-open');
                  }
                }, 800);
              } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = (data && data.error) ? data.error : 'Failed to save payment.';
                alertBox.classList.remove('d-none');
              }
            })
            .catch(() => {
              alertBox.className = 'alert alert-danger';
              alertBox.textContent = 'Network error. Please try again.';
              alertBox.classList.remove('d-none');
            })
            .finally(() => {
              submitBtn.disabled = false;
            });
        });

        // ---- Sortable table headers for invoice rows ----
        (function enableInvoiceTableSorting() {
          const table = document.getElementById('miqaat-payments-table');
          if (!table) return;
          const headers = Array.from(table.querySelectorAll('thead th.km-sortable'));
          const tbody = table.querySelector('tbody');
          if (!tbody || !headers.length) return;

          function getRowValue(row, key, type) {
            if (!row) return '';
            if (key === 'index') {
              const first = row.children && row.children[0] ? row.children[0].textContent : '';
              const n = parseInt(String(first || '').replace(/[^0-9]/g, ''), 10);
              return isNaN(n) ? 0 : n;
            }

            if (key === 'invoiceId') {
              const raw = row.getAttribute('data-invoice-id') || '';
              const n = parseInt(raw, 10);
              return isNaN(n) ? 0 : n;
            }

            if (key === 'amount') return parseFloat(row.getAttribute('data-amount') || '0') || 0;
            if (key === 'paid') return parseFloat(row.getAttribute('data-paid') || '0') || 0;
            if (key === 'due') return parseFloat(row.getAttribute('data-due') || '0') || 0;

            if (type === 'number') {
              const raw = row.getAttribute('data-' + key) || '';
              const n = parseFloat(raw);
              return isNaN(n) ? 0 : n;
            }

            if (type === 'date') {
              // stored as ISO (YYYY-MM-DD)
              if (key === 'date') return (row.getAttribute('data-greg-date') || '');
              if (key === 'invoiceDate') return (row.getAttribute('data-invoice-date') || '');
              return '';
            }

            if (key === 'assignedTo') {
              const label = (row.getAttribute('data-assigned-to') || '').trim();
              const typeKey = label.split('(')[0].trim();
              if (typeKey === 'individual') {
                const c = parseInt(row.getAttribute('data-individual-count') || '0', 10) || 0;
                const padded = String(c).padStart(6, '0');
                return 'individual|' + padded;
              }
              return label;
            }

            if (key === 'miqaatId') return (row.getAttribute('data-miqaat-id') || '');
            if (key === 'razaId') return (row.getAttribute('data-raza-id') || '');
            if (key === 'miqaatName') return (row.getAttribute('data-miqaat-name') || '');
            if (key === 'details') return ((row.getAttribute('data-name') || '') + '|' + (row.getAttribute('data-its') || ''));
            if (key === 'hijri') return (row.getAttribute('data-hijri-date') || '');
            return '';
          }

          function updateIndicators(activeTh, dir) {
            headers.forEach(th => {
              const si = th.querySelector('.sort-indicator');
              if (th === activeTh) {
                th.setAttribute('data-sort-dir', dir);
                if (si) si.textContent = dir === 'asc' ? '▲' : '▼';
              } else {
                th.removeAttribute('data-sort-dir');
                if (si) si.textContent = '';
              }
            });
          }

          headers.forEach(th => {
            th.addEventListener('click', () => {
              const key = th.getAttribute('data-sort-key') || '';
              const type = th.getAttribute('data-sort-type') || 'string';
              if (!key) return;
              const current = th.getAttribute('data-sort-dir');
              const dir = current === 'asc' ? 'desc' : 'asc';
              updateIndicators(th, dir);

              const allRows = Array.from(tbody.querySelectorAll('tr.miqaat-payment-row'));
              const visible = allRows.filter(r => r.style.display !== 'none');
              const hidden = allRows.filter(r => r.style.display === 'none');

              visible.sort((a, b) => {
                const av = getRowValue(a, key, type);
                const bv = getRowValue(b, key, type);
                if (typeof av === 'number' && typeof bv === 'number') {
                  return dir === 'asc' ? (av - bv) : (bv - av);
                }
                const as = String(av).toLowerCase();
                const bs = String(bv).toLowerCase();
                if (as === bs) return 0;
                return dir === 'asc' ? (as < bs ? -1 : 1) : (as > bs ? -1 : 1);
              });

              visible.forEach((r, idx) => {
                tbody.appendChild(r);
                const first = r.children[0];
                if (first) first.textContent = idx + 1;
              });
              hidden.forEach(r => tbody.appendChild(r));
            });
          });
        })();
      })();
    </script>
  <?php endif; ?>
</div>