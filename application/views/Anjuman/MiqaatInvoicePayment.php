<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    font-size: 14.5px;
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

  /* Autocomplete for extra contribution modal */
  #extra-member-autocomplete-list .autocomplete-item {
    display: block;
    width: 100%;
    padding: 10px 14px;
    cursor: pointer;
    background: #fff;
    border: none;
    border-bottom: 1px solid #f0ece0;
    font-size: 0.85rem;
    font-weight: 600;
    color: #1a1610;
    text-align: left;
  }
  #extra-member-autocomplete-list .autocomplete-item:hover {
    background: #f5e9c0;
    color: #b8860b;
    text-decoration: none;
  }
  #extra-member-autocomplete-list .autocomplete-item:last-child {
    border-bottom: none;
  }

  /* Modals Premium Styling */
  .modal-content-premium {
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: var(--sh2);
    background: var(--surface);
    overflow: hidden;
  }
  .modal-header-premium {
    background: linear-gradient(135deg, #78520a 0%, var(--gold) 50%, #c9a227 100%);
    color: #fff;
    border: none;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modal-header-premium .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    font-size: 1.15rem;
    margin: 0;
  }
  .modal-header-premium .close {
    color: #fff;
    opacity: 0.85;
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    margin: 0;
  }
  .modal-header-premium .close:hover {
    opacity: 1;
  }
  .modal-body-premium {
    padding: 24px;
  }
  .form-group-premium label {
    font-weight: 700;
    font-size: 0.75rem;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
  }
  .form-control-premium {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.85rem;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    width: 100%;
    font-family: inherit;
  }
  .form-control-premium:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
    background: var(--surface);
  }

  /* Premium buttons */
  .btn-action {
    font-family: inherit;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 10px 16px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.2px;
    cursor: pointer;
    border: none;
  }
  .btn-action-primary {
    background: linear-gradient(135deg, var(--gold) 0%, #8f6808 100%);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
  }
  .btn-action-primary:hover {
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.3);
    transform: translateY(-2px);
    text-decoration: none;
  }
  .btn-action-secondary {
    background: var(--surface);
    color: var(--gold) !important;
    border: 1.5px solid var(--gold) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
  }
  .btn-action-secondary:hover {
    background: var(--gold-muted);
    color: var(--gold) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.12);
    text-decoration: none;
  }

  /* Autocomplete suggestions dropdown container */
  .autocomplete-dropdown {
    position: absolute;
    z-index: 1050;
    left: 0;
    right: 0;
    top: 100%;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--sh2);
    max-height: 250px;
    overflow-y: auto;
  }

  /* Banner Right Layout */
  .miqaat-banner-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  /* Responsive Banner Stack for Mobile */
  @media (max-width: 768px) {
    .miqaat-banner-inner {
      flex-direction: column;
      align-items: stretch !important;
      gap: 16px;
    }
    .miqaat-banner-right {
      width: 100%;
      flex-direction: column;
      align-items: stretch !important;
      gap: 8px;
      margin-top: 8px;
    }
    .miqaat-banner-right a,
    .miqaat-banner-right button {
      width: 100%;
      justify-content: center;
      text-align: center;
      padding: 10px 16px !important;
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
  }
  if (!empty($extra_contributions) && is_array($extra_contributions)) {
    foreach ($extra_contributions as $ec) {
      $sector = isset($ec['Sector']) ? $ec['Sector'] : '';
      $subSector = isset($ec['Sub_Sector']) ? $ec['Sub_Sector'] : '';
      if ($sector !== '' && !in_array($sector, $sectors, true)) $sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $sub_sectors, true)) {
        $sub_sectors[] = $subSector;
      }
      $iy = '';
      if (!empty($ec['contri_year'])) {
        $iy = (string)$ec['contri_year'];
      }
      if (empty($iy) && !empty($ec['hijri_date_from_cal'])) {
        $parts = explode('-', (string)$ec['hijri_date_from_cal']);
        if (count($parts) === 3) {
          $month = (int)$parts[1];
          $year = (int)$parts[2];
          if ($month >= 7 && $month <= 12) {
            $iy = $year . '-' . substr((string)($year + 1), -2);
          } else {
            $iy = ($year - 1) . '-' . substr((string)$year, -2);
          }
        }
      }
      if (empty($iy) && !empty($ec['hijri_year_from_cal'])) {
        $single_year = (int)$ec['hijri_year_from_cal'];
        $iy = ($single_year - 1) . '-' . substr((string)$single_year, -2);
      }
      if ($iy !== '' && !in_array($iy, $years_from_invoices, true)) $years_from_invoices[] = $iy;
    }
  }
  $sectors = array_values(array_unique(array_filter($sectors)));
  $sub_sectors = array_values(array_unique(array_filter($sub_sectors)));
  $years_from_invoices = array_values(array_unique(array_filter($years_from_invoices)));
  sort($sectors);
  sort($sub_sectors);
  rsort($years_from_invoices);

  // Combine both lists (controller-provided from calendar and derived from invoices)
  $filter_hijri_years = [];
  if (isset($hijri_years) && is_array($hijri_years)) {
    $filter_hijri_years = $hijri_years;
  }
  if (!empty($years_from_invoices)) {
    $filter_hijri_years = array_merge($filter_hijri_years, $years_from_invoices);
  }
  $filter_hijri_years = array_values(array_unique(array_filter($filter_hijri_years, function ($y) {
    return $y !== null && (string)$y !== '';
  })));
  rsort($filter_hijri_years);

  // Determine default year: prefer controller-provided $current_hijri_year if set, else latest from list.
  $default_hijri_year = '';
  if (isset($current_hijri_year) && $current_hijri_year !== '') {
    $default_hijri_year = $current_hijri_year;
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

  // Prefer full Hijri years list passed from controller (hijri_calendar); fallback to deriving from invoice data.
  $all_hijri_years = $filter_hijri_years;
  rsort($all_hijri_years);

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

  $rows = [];
  $invoicePaymentsMap = [];
  $grand_total = 0.0;
  $grand_collected_total = 0.0;
  $grand_due_total = 0.0;
  if (!empty($members)) {
    foreach ($members as $m) {
      $its = isset($m['ITS_ID']) ? $m['ITS_ID'] : '';
      $name = isset($m['Full_Name']) ? $m['Full_Name'] : '';
      $sector = isset($m['Sector']) ? $m['Sector'] : (isset($m['sector']) ? $m['sector'] : '');
      $subSector = isset($m['Sub_Sector']) ? $m['Sub_Sector'] : (isset($m['sub_sector']) ? $m['sub_sector'] : '');

      // Group payments by invoice_id so each invoice row can show its own history
      $memberPayments = isset($m['payments']) && is_array($m['payments']) ? $m['payments'] : [];
      foreach ($memberPayments as $p) {
        $invId = isset($p['invoice_id']) ? (string)$p['invoice_id'] : '';
        if ($invId === '') continue;
        if (!isset($invoicePaymentsMap[$invId])) $invoicePaymentsMap[$invId] = [];
        $p['invoice_type'] = 'regular';
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
            'invoice_type' => 'regular',
            'hof_fm_type'  => isset($m['hof_fm_type']) ? $m['hof_fm_type'] : 'HOF',
          ];
          $rows[] = $row;

          $grand_total += (float)$amount;
          $grand_collected_total += (float)$paid;
          $grand_due_total += (float)$due;
        }
      }
    }
  }

  // Merge extra contributions
  if (!empty($extra_contributions) && is_array($extra_contributions)) {
    foreach ($extra_contributions as $ec) {
      $invoiceId = isset($ec['id']) ? (string)$ec['id'] : '';
      $amount = isset($ec['amount']) ? (float)$ec['amount'] : 0.0;
      $paid = isset($ec['paid_amount']) ? (float)$ec['paid_amount'] : 0.0;
      $due = $amount - $paid;
      if ($due < 0) $due = 0.0;

      $its = isset($ec['ITS_ID']) ? $ec['ITS_ID'] : '';
      $name = isset($ec['Full_Name']) ? $ec['Full_Name'] : '';
      $sector = isset($ec['Sector']) ? $ec['Sector'] : '';
      $subSector = isset($ec['Sub_Sector']) ? $ec['Sub_Sector'] : '';
      $contri_type = isset($ec['contri_type']) ? $ec['contri_type'] : '';

      $payments = isset($extra_payments_map[$invoiceId]) ? $extra_payments_map[$invoiceId] : [];
      $normalized_payments = [];
      foreach ($payments as $p) {
        $normalized_payments[] = [
          'payment_id' => $p['payment_id'] ?? $p['id'] ?? '',
          'amount' => $p['amount'] ?? 0.0,
          'payment_date' => $p['payment_date'] ?? $p['date'] ?? '',
          'payment_method' => $p['payment_method'] ?? '',
          'invoice_id' => $invoiceId,
          'miqaat_name' => $contri_type,
          'invoice_type' => 'extra',
        ];
      }

      // Use the composite Hijri year directly from contri_year
      $hijri_year_for_filter = '';
      if (!empty($ec['contri_year'])) {
        $hijri_year_for_filter = (string)$ec['contri_year'];
      }
      if (empty($hijri_year_for_filter) && !empty($ec['hijri_date_from_cal'])) {
        $parts = explode('-', (string)$ec['hijri_date_from_cal']);
        if (count($parts) === 3) {
          $month = (int)$parts[1];
          $year = (int)$parts[2];
          if ($month >= 7 && $month <= 12) {
            $hijri_year_for_filter = $year . '-' . substr((string)($year + 1), -2);
          } else {
            $hijri_year_for_filter = ($year - 1) . '-' . substr((string)$year, -2);
          }
        }
      }
      if (empty($hijri_year_for_filter) && !empty($ec['hijri_year_from_cal'])) {
        $single_year = (int)$ec['hijri_year_from_cal'];
        $hijri_year_for_filter = ($single_year - 1) . '-' . substr((string)$single_year, -2);
      }

      $hijriLabel = '-';
      if (!empty($ec['hijri_date_from_cal'])) {
        $parts = explode('-', (string)$ec['hijri_date_from_cal']);
        if (count($parts) === 3) {
          $day = $parts[0] ?? '';
          $month_id = $parts[1] ?? '';
          $hy = '';
          if (!empty($ec['contri_year'])) {
            $hy = (string)substr($ec['contri_year'], 0, 4);
          }
          if (empty($hy)) {
            $hy = $parts[2] ?? '';
          }
          $month_name = $this->HijriCalendar->hijri_month_name($month_id);
          $hijriLabel = trim($day . ' ' . ($month_name['hijri_month'] ?? '') . ' ' . $hy);
        }
      }

      $row = [
        'invoice_id'       => $invoiceId,
        'its_id'           => $its,
        'full_name'        => $name,
        'sector'           => $sector,
        'sub_sector'       => $subSector,
        'invoice_year'     => $hijri_year_for_filter,
        'display_date_iso' => isset($ec['created_at']) ? (string)$ec['created_at'] : '',
        'invoice_date_iso' => isset($ec['created_at']) ? (string)$ec['created_at'] : '',
        'hijri_date'       => $hijriLabel,
        'miqaat_id'        => '',
        'raza_id'          => '',
        'miqaat_name'      => $contri_type,
        'assigned_to'      => 'Niyaz Extra Contribution',
        'assigned_display' => 'Niyaz Extra Contribution',
        'individual_count' => 0,
        'amount'           => $amount,
        'paid'             => $paid,
        'due'              => $due,
        'description'      => isset($ec['description']) ? $ec['description'] : '',
        'details'          => trim($name . ($its !== '' ? (' (' . $its . ')') : '')),
        'payments'         => $normalized_payments,
        'invoice_type'     => 'extra',
        'contri_year'      => isset($ec['contri_year']) ? (string)$ec['contri_year'] : '',
        'hof_fm_type'      => isset($ec['hof_fm_type']) ? $ec['hof_fm_type'] : 'HOF',
      ];
      $rows[] = $row;

      $grand_total += $amount;
      $grand_collected_total += $paid;
      $grand_due_total += $due;
    }
  }

  $hijri_years = !empty($all_hijri_years) ? $all_hijri_years : ($years_from_invoices ?? []);
  if ($default_hijri_year === '' && !empty($hijri_years)) $default_hijri_year = $hijri_years[0];

  $fala_groups = [];
  foreach ($rows as $r) {
    $assigned_key = strtolower(trim($r['assigned_to'] ?? ''));
    if ($assigned_key === 'fala ni niyaz' || (empty($r['miqaat_id']) && stripos($r['miqaat_name'] ?? '', 'Fala') !== false)) {
      $year = $r['invoice_year'] ?? '';
      $group_key = (isset($miqaat_type) ? $miqaat_type : '') . ' ' . $year;
      if (!isset($fala_groups[$group_key])) {
        $fala_groups[$group_key] = [
          'group_key'   => $group_key,
          'miqaat_id'   => null,
          'miqaat_name' => 'Fala ni Niyaz ' . $year,
          'miqaat_type' => isset($miqaat_type) ? $miqaat_type : '',
          'miqaat_date' => null,
          'year'        => $year,
          'amount'      => $r['amount'],
          'invoice_ids' => [],
          'is_generated'=> true,
          'amount_counts' => [],
        ];
      }
      $fala_groups[$group_key]['invoice_ids'][] = (int)$r['invoice_id'];
      $amtKey = number_format($r['amount'], 2, '.', '');
      if (!isset($fala_groups[$group_key]['amount_counts'][$amtKey])) {
        $fala_groups[$group_key]['amount_counts'][$amtKey] = 0;
      }
      $fala_groups[$group_key]['amount_counts'][$amtKey]++;
    }
  }

  foreach ($fala_groups as $gk => &$fg) {
    if (!empty($fg['amount_counts'])) {
      $modeAmountKey = null;
      $modeCount = -1;
      foreach ($fg['amount_counts'] as $amountKey => $count) {
        if ($count > $modeCount || ($count === $modeCount && (float)$amountKey > (float)$modeAmountKey)) {
          $modeCount = $count;
          $modeAmountKey = $amountKey;
        }
      }
      $fg['amount'] = (float)$modeAmountKey;
    }
    unset($fg['amount_counts']);
  }
  unset($fg);

  // Merge pending Fala ni Niyaz groups from $fala_ni_niyaz_summary
  if (isset($fala_ni_niyaz_summary) && is_array($fala_ni_niyaz_summary)) {
    foreach ($fala_ni_niyaz_summary as $summary) {
      $year = $summary['year'] ?? '';
      $group_key = (isset($miqaat_type) ? $miqaat_type : '') . ' ' . $year;
      if (!isset($fala_groups[$group_key])) {
        $fala_groups[$group_key] = [
          'group_key'   => $group_key,
          'miqaat_id'   => null,
          'miqaat_name' => 'Fala ni Niyaz ' . $year,
          'miqaat_type' => isset($miqaat_type) ? $miqaat_type : '',
          'miqaat_date' => null,
          'year'        => $year,
          'amount'      => 0.0,
          'invoice_ids' => [],
          'is_generated'=> false,
          'pending_count' => $summary['count'] ?? 0,
          'earliest_date' => $summary['earliest_date'] ?? null,
          'latest_date' => $summary['latest_date'] ?? null,
        ];
      }
    }
  }

  $miqaats_list = array_values($fala_groups);
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
      <div class="miqaat-banner-right">
        <button type="button" class="btn btn-light font-weight-bold d-inline-flex align-items-center" data-toggle="modal" data-target="#createExtraContributionModal" style="border-radius: 8px; padding: 6px 16px; gap: 6px;">
          <i class="fa-solid fa-plus-circle"></i> Create Invoice
        </button>
        <button id="fala-ni-niyaz-invoices" class="btn btn-light font-weight-bold d-inline-flex align-items-center" data-toggle="modal" data-target="#falaNiyazInvoicesModal" style="border-radius: 8px; padding: 6px 16px; gap: 6px;">
          <i class="fa-solid fa-file-signature"></i> Create & Update Hoob Invoices
        </button>
      </div>
    </div>
  </div>

  <?php if (!empty($rows)) : ?>
    <div id="miqaat-payment-filters" class="miqaat-filters-card">
      <div class="form-row">
        <div class="col-md-2 mb-2">
          <label for="pf-name">Name or ITS</label>
          <input type="text" id="pf-name" class="form-control" placeholder="Search name or ITS...">
        </div>
        <div class="col-md-1 mb-2">
          <label for="pf-hoffm">HOF/FM</label>
          <select id="pf-hoffm" class="form-control">
            <option value="">All</option>
            <option value="hof">HOF</option>
            <option value="fm">FM</option>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-sector">Sector</label>
          <select id="pf-sector" class="form-control">
            <option value="">All Sectors</option>
            <?php if (!empty($sectors)) : foreach ($sectors as $s) : if ($s === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($s), ENT_QUOTES); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
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
          <label for="pf-invoice-type">Category</label>
          <select id="pf-invoice-type" class="form-control">
            <option value="">All Categories</option>
            <option value="individual">Individual Niyaz</option>
            <option value="fala">Fala ni Niyaz</option>
            <option value="extra">Niyaz Extra Contribution</option>
          </select>
        </div>
        <div class="col-md-1 mb-2">
          <label for="pf-status">Paid / Unpaid</label>
          <select id="pf-status" class="form-control">
            <option value="">All Status</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
          </select>
        </div>
        <div class="col-md-1 mb-2">
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
          <button type="button" id="pf-clear" class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center" style="height:34px; border-radius:8px; gap: 6px;"><i class="fa-solid fa-filter-circle-xmark"></i> Clear</button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php
  // Processing done above.
  ?>

  <?php if (empty($rows)) : ?>
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
      <div class="miqaat-stat-title">Niyaz Hoob Collection</div>
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
              data-invoice-type="<?php echo htmlspecialchars($r['invoice_type'], ENT_QUOTES); ?>"
              data-name="<?php echo htmlspecialchars(strtolower($r['full_name']), ENT_QUOTES); ?>"
              data-its="<?php echo htmlspecialchars(strtolower($r['its_id']), ENT_QUOTES); ?>"
              data-sector="<?php echo htmlspecialchars(strtolower($r['sector']), ENT_QUOTES); ?>"
              data-subsector="<?php echo htmlspecialchars(strtolower($r['sub_sector']), ENT_QUOTES); ?>"
              data-hof-fm="<?php echo htmlspecialchars(strtolower((string)($r['hof_fm_type'] ?? 'hof')), ENT_QUOTES); ?>"
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
              <td class="km-cell-wrap"><?php echo !empty($r['invoice_id']) ? ('I#' . htmlspecialchars((string)$r['invoice_id'])) : '-'; ?></td>
              <td class="km-cell-wrap">
                <b><?php echo htmlspecialchars((string)($r['miqaat_name'] ?? '')); ?></b>
                <?php if ($r['invoice_type'] === 'extra' && !empty($r['contri_year'])) : ?>
                  <div class="small text-muted" style="font-size: 0.8em; margin-top: 2px;">(<?php echo htmlspecialchars($r['contri_year']); ?>)</div>
                <?php endif; ?>
              </td>
              <td class="km-cell-wrap"><b><?php echo htmlspecialchars((string)($r['assigned_display'] ?? '')); ?></b></td>
              <td class="km-cell-wrap"><?php echo htmlspecialchars((string)($r['details'] ?? '')); ?></td>
              <td class="invoice-date-cell km-cell-wrap"><?php echo htmlspecialchars($invFmt); ?></td>
              <td class="text-right amount-cell">₹<?php echo inr_format($amount); ?></td>
              <td class="text-right paid-cell">₹<?php echo inr_format($paid); ?></td>
              <td class="text-right due-cell"><span class="<?php echo $dueClass; ?>">₹<?php echo inr_format($due); ?></span></td>
               <td class="text-center km-actions">
                <div class="btn-group-vertical btn-group-sm" role="group" aria-label="Actions">
                  <button type="button" class="btn btn-success receive-payment-btn<?php echo $btnExtraClass; ?> d-inline-flex align-items-center justify-content-center" style="gap: 5px;"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"
                    data-amount="<?php echo htmlspecialchars((string)$due, ENT_QUOTES); ?>"
                    data-block-reason="<?php echo htmlspecialchars($blockReason, ENT_QUOTES); ?>"
                    aria-disabled="<?php echo $ariaDisabled; ?>"
                    title="<?php echo htmlspecialchars($blockReason, ENT_QUOTES); ?>"><i class="fa-solid fa-receipt"></i> Receive Payment</button>
                  <button type="button" class="btn btn-outline-primary invoice-edit-btn d-inline-flex align-items-center justify-content-center" style="gap: 5px;"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"><i class="fa-solid fa-pen-to-square"></i> Edit Invoice</button>
                  <button type="button" class="btn btn-outline-danger invoice-delete-btn d-inline-flex align-items-center justify-content-center" style="gap: 5px;"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"><i class="fa-solid fa-trash"></i> Delete Invoice</button>
                  <button type="button" class="btn btn-outline-info view-payments-btn d-inline-flex align-items-center justify-content-center" style="gap: 5px;"
                    data-toggle="modal"
                    data-target="#memberPaymentsModal"
                    data-its="<?php echo htmlspecialchars((string)($r['its_id'] ?? '')); ?>"
                    data-name="<?php echo htmlspecialchars((string)($r['full_name'] ?? '')); ?>"
                    data-invoice-id="<?php echo htmlspecialchars((string)($r['invoice_id'] ?? ''), ENT_QUOTES); ?>"
                    data-payments='<?php echo htmlspecialchars(json_encode($r['payments'] ?? []), ENT_QUOTES, "UTF-8"); ?>'><i class="fa-solid fa-clock-rotate-left"></i> Payment History</button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
    <!-- Member Payments Modal -->
    <div class="modal fade" id="memberPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="memberPaymentsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="memberPaymentsModalLabel"><i class="fa-solid fa-history mr-2"></i> Payment History</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-premium">
            <div class="mb-3 p-3 rounded" style="background: var(--surface-2); border: 1px solid var(--border);">
              <span class="mr-4"><strong>ITS:</strong> <span id="payments-modal-its" style="font-weight: 600;"></span></span>
              <span class="mr-4"><strong>Name:</strong> <span id="payments-modal-name" style="font-weight: 600;"></span></span>
              <span><strong>Invoice:</strong> <span id="payments-modal-invoice" style="font-weight: 600;"></span></span>
            </div>
            <div id="payments-modal-table-wrapper" class="table-responsive"></div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="editInvoiceModalLabel"><i class="fa-solid fa-pen-to-square mr-2"></i> Edit Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editInvoiceForm">
            <div class="modal-body modal-body-premium">
              <input type="hidden" id="edit-invoice-id" value="">

              <div class="form-group-premium mb-3">
                <label><b>Miqaat Details:</b></label>
                <div id="edit-invoice-details" class="form-control-plaintext" style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; font-weight: 600;"></div>
              </div>

              <div class="form-group-premium mb-3">
                <label for="edit-amount">Amount (₹)</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text" style="background: var(--surface-2); border: 1.5px solid var(--border); border-right: none; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">₹</span></div>
                  <input type="number" class="form-control-premium" id="edit-amount" min="0" step="1" required style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                </div>
              </div>

              <div class="form-group-premium mb-3">
                <label for="edit-description">Description</label>
                <textarea class="form-control-premium" id="edit-description" rows="2"></textarea>
              </div>

              <div class="form-group-premium mb-3">
                <label for="edit-invoice-date">Invoice Date</label>
                <input type="date" class="form-control-premium" id="edit-invoice-date" required>
              </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
              <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
              <button type="submit" class="btn-action btn-action-primary" id="edit-invoice-save"><i class="fa-solid fa-check"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Fala ni Niyaz Invoices Modal -->
    <div class="modal fade" id="falaNiyazInvoicesModal" tabindex="-1" role="dialog" aria-labelledby="falaNiyazInvoicesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="falaNiyazInvoicesModalLabel"><i class="fa-solid fa-file-signature mr-2"></i> Create & Update Hoob Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-premium">
            <div id="fala-modal-table-wrapper" class="table-responsive"></div>
            <!-- Loading overlay for bulk operations -->
            <div id="fala-loading-overlay" class="fala-loading-overlay d-none">
              <div class="fala-loading-inner text-center">
                <div class="mb-2"><i class="fa-solid fa-circle-notch fa-spin fa-2x"></i></div>
                <div class="message">Please wait...</div>
              </div>
            </div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Single reusable Invoice Modal for Takhmeen -->
    <div class="modal fade" id="generateInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="generateInvoiceModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="generateInvoiceModalLabel"><i class="fa-solid fa-file-invoice-dollar mr-2"></i> Generate Miqaat Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="generateInvoiceForm" method="post" action="<?php echo base_url('anjuman/create_miqaat_invoice') ?>">
            <div class="modal-body modal-body-premium">
              <input type="hidden" name="miqaat_id" id="modal_miqaat_index">
              <input type="hidden" name="raza_id" id="modal_raza_index">
              <input type="hidden" name="miqaat_type" id="input_miqaat_type">
              <input type="hidden" name="year" id="input_hijri_year">
              <input type="hidden" name="assigned_to" id="input_assigned_to">
              <input type="hidden" name="member_id" id="input_member_id">
              <input type="hidden" name="details" id="input_details">
              
              <div class="form-group-premium mb-3">
                <label><b>Miqaat Details:</b></label>
                <div id="modal_miqaat_details" class="form-control-plaintext"></div>
              </div>

              <div class="form-group-premium mb-3">
                <label for="modal-amount">Amount (₹)</label>
                <input type="number" id="modal-amount" class="form-control-premium" name="amount" placeholder="Enter amount" required min="1">
              </div>
              <div class="form-group-premium mb-3">
                <label for="modal-description">Description</label>
                <textarea id="modal-description" class="form-control-premium" name="description" rows="2" placeholder="Optional remarks..."></textarea>
              </div>
              <div class="form-group-premium mb-3">
                <label for="modal-invoice-date">Invoice Date</label>
                <input type="date" id="modal-invoice-date" class="form-control-premium" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
              <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
              <button type="submit" id="create-miqaat-invoice-btn" class="btn-action btn-action-primary"><i class="fa-solid fa-check"></i> Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Payment Modal (amount only for now) -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="editPaymentModalLabel"><i class="fa-solid fa-pen-to-square mr-2"></i> Edit Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-premium">
            <form id="edit-payment-form">
              <input type="hidden" id="ep-payment-id" name="payment_id" />
              <input type="hidden" id="ep-invoice-id" name="invoice_id" />
              <div class="form-group-premium mb-3">
                <label>Payment ID</label>
                <input type="text" id="ep-payment-id-display" class="form-control-premium" readonly />
              </div>
              <div class="form-group-premium mb-3">
                <label>Amount</label>
                <input type="number" id="ep-amount" name="amount" class="form-control-premium" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="ep-max-hint"></small>
              </div>
            </form>
            <div id="ep-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button type="button" id="ep-submit" class="btn-action btn-action-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Receive Payment Modal (invoice-wise) -->
    <div class="modal fade" id="receiveInvoicePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receiveInvoicePaymentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="receiveInvoicePaymentModalLabel"><i class="fa-solid fa-receipt mr-2"></i> Receive Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-premium">
            <div class="p-3 rounded mb-3" style="background: var(--surface-2); border: 1px solid var(--border);">
              <label class="form-label mb-0"><b>Miqaat Details:</b></label>
              <div id="rip-details" class="small text-dark mt-2" style="font-weight: 600;"></div>
            </div>
            <form id="invoice-payment-form">
              <input type="hidden" id="rip-invoice-id" name="invoice_id" />
              <div class="form-group-premium mb-3">
                <label>Invoice ID</label>
                <input type="text" id="rip-invoice-id-display" class="form-control-premium" readonly />
              </div>
              <div class="form-group-premium mb-3">
                <label>Amount</label>
                <input type="number" id="rip-amount" name="amount" class="form-control-premium" step="0.01" min="0.01" required />
                <small class="form-text text-muted" id="rip-max-hint"></small>
              </div>
              <div class="form-group-premium mb-3">
                <label>Payment Date</label>
                <input type="date" id="rip-date" name="payment_date" class="form-control-premium" required />
              </div>
              <div class="form-group-premium mb-3">
                <label>Payment Method</label>
                <select id="rip-method" name="payment_method" class="form-control-premium">
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                  <option value="NEFT">NEFT</option>
                </select>
              </div>
              <div class="form-group-premium mb-3">
                <label>Remarks</label>
                <textarea id="rip-remarks" name="remarks" class="form-control-premium" rows="2" placeholder="Optional"></textarea>
              </div>
            </form>
            <div id="rip-alert" class="alert d-none" role="alert"></div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; background: var(--surface-2); display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn-action btn-action-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
            <button type="button" id="rip-submit" class="btn-action btn-action-primary"><i class="fa-solid fa-check"></i> Save Payment</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Extra Contribution Modal -->
    <div class="modal fade" id="createExtraContributionModal" tabindex="-1" role="dialog" aria-labelledby="createExtraContributionModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-premium">
          <div class="modal-header modal-header-premium">
            <h5 class="modal-title" id="createExtraContributionModalLabel"><i class="fa-solid fa-circle-plus mr-2"></i> Create Miqaat Niyaz Invoice</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-premium">
            <form id="create-extra-contribution-form">
              <div class="form-group form-group-premium position-relative mb-3">
                <label for="extra-member-autocomplete">Name or ITS</label>
                <input type="text" id="extra-member-autocomplete" class="form-control-premium" placeholder="Type name or ITS..." autocomplete="off" required />
                <input type="hidden" name="user_id" id="extra-user-id" required />
                <div id="extra-member-autocomplete-list" class="autocomplete-dropdown" style="display:none;"></div>
              </div>
              <div class="form-group form-group-premium mb-3">
                <label for="extra-contri-year">Contribution Year</label>
                <select name="contri_year" id="extra-contri-year" class="form-control-premium" required>
                  <option value="">Select Contribution Year</option>
                  <?php if (!empty($composite_hijri_years)): ?>
                    <?php foreach ($composite_hijri_years as $y): ?>
                      <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>"><?php echo htmlspecialchars($y); ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group form-group-premium mb-3">
                <label>Miqaat Type</label>
                <input type="text" class="form-control-premium" value="<?php echo htmlspecialchars($miqaat_type, ENT_QUOTES); ?>" readonly style="background: var(--surface-2); cursor: not-allowed; opacity: 0.8;" />
                <input type="hidden" name="miqaat_type" value="<?php echo htmlspecialchars($miqaat_type, ENT_QUOTES); ?>" />
              </div>
              <div class="form-group form-group-premium mb-3">
                <label for="extra-contri-type">Contribution Type</label>
                <select name="contri_type" id="extra-contri-type" class="form-control-premium" required>
                  <option value="">Select Type</option>
                  <?php if (!empty($contri_type_gc)): ?>
                    <?php foreach ($contri_type_gc as $value): ?>
                      <option value="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>"
                              data-amount="<?php echo htmlspecialchars($value["amount"] ?? '', ENT_QUOTES); ?>"
                              data-hijri-year="<?php echo htmlspecialchars($value["hijri_year"] ?? '', ENT_QUOTES); ?>"
                              data-miqaat-type="<?php echo htmlspecialchars($value["miqaat_type"] ?? '', ENT_QUOTES); ?>">
                        <?php echo htmlspecialchars($value["name"]); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group form-group-premium mb-3">
                <label for="extra-amount">Amount (₹)</label>
                <input type="number" name="amount" id="extra-amount" class="form-control-premium" placeholder="Enter amount" min="1" required />
              </div>
              <div class="form-group form-group-premium mb-4">
                <label for="extra-description">Description</label>
                <textarea name="description" id="extra-description" class="form-control-premium" rows="2" placeholder="Optional remarks..."></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-2);">
            <button type="button" class="btn-action btn-action-secondary d-inline-flex align-items-center justify-content-center" data-dismiss="modal" style="padding: 8px 16px; font-size: 0.82rem; min-width: 80px; gap: 5px;"><i class="fa-solid fa-xmark"></i> Close</button>
            <button type="button" id="create-extra-contribution-submit" class="btn-action btn-action-primary d-inline-flex align-items-center justify-content-center" onclick="jQuery('#create-extra-contribution-form').submit();" style="padding: 8px 20px; font-size: 0.82rem; gap: 5px;"><i class="fa-solid fa-plus-circle"></i> Create Invoice</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      const extraContributions = <?php echo json_encode($extra_contributions ?? []); ?>;
      let FALA_MIQAATS = <?php echo json_encode($miqaats_list ?? []); ?>;
      const NIYAZ_AMOUNTS_BY_YEAR = <?php echo isset($niyaz_amounts_by_year) ? json_encode($niyaz_amounts_by_year) : '{}'; ?>;
      const CURRENT_MIQAAT_TYPE = <?php echo json_encode(isset($miqaat_type) ? $miqaat_type : ''); ?>;
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

        // Filters for invoice list (name/its/sector/sub-sector/year/type) with totals
        function applyPaymentFilters() {
          const pfName = document.getElementById('pf-name');
          const pfHofFm = document.getElementById('pf-hoffm');
          const pfSector = document.getElementById('pf-sector');
          const pfSub = document.getElementById('pf-subsector');
          const pfYear = document.getElementById('pf-year');
          const pfType = document.getElementById('pf-invoice-type');
          const pfStatus = document.getElementById('pf-status');

          if (!pfName || !pfHofFm || !pfSector || !pfSub || !pfYear || !pfType || !pfStatus) {
            return;
          }

          const nameVal = (pfName.value || '').trim().toLowerCase();
          const hoffmVal = (pfHofFm.value || '').trim().toLowerCase();
          const sectorVal = (pfSector.value || '').trim().toLowerCase();
          const subVal = (pfSub.value || '').trim().toLowerCase();
          const yearVal = (pfYear.value || '').trim().toLowerCase();
          const typeVal = (pfType.value || '').trim().toLowerCase();
          const statusVal = (pfStatus.value || '').trim().toLowerCase();

          const rows = document.querySelectorAll('#miqaat-payments-table tbody tr.miqaat-payment-row');
          let index = 1;
          let visibleCount = 0;

          let individualInvoiced = 0;
          let individualCollected = 0;
          let individualDue = 0;

          let falaInvoiced = 0;
          let falaCollected = 0;
          let falaDue = 0;

          let extraInvoiced = 0;
          let extraCollected = 0;
          let extraDue = 0;

          rows.forEach(r => {
            const rName = (r.getAttribute('data-name') || '').trim();
            const rIts = (r.getAttribute('data-its') || '').trim();
            const rSector = (r.getAttribute('data-sector') || '').trim();
            const rSub = (r.getAttribute('data-subsector') || '').trim();
            const rHofFm = (r.getAttribute('data-hof-fm') || '').trim();
            const rYear = (r.getAttribute('data-year') || '').trim();
            const rType = (r.getAttribute('data-invoice-type') || '').trim();
            const assignedTo = (r.getAttribute('data-assigned-to') || '').trim();
            const rDue = parseFloat(r.getAttribute('data-due') || '0') || 0;

            let category = 'individual';
            if (rType === 'extra') {
              category = 'extra';
            } else if (assignedTo === 'fala ni niyaz' || assignedTo.startsWith('fala ni niyaz')) {
              category = 'fala';
            }

            let show = true;
            if (nameVal && rName.indexOf(nameVal) === -1 && rIts.indexOf(nameVal) === -1) show = false;
            if (hoffmVal && rHofFm !== hoffmVal) show = false;
            if (sectorVal && rSector !== sectorVal) show = false;
            if (subVal && rSub !== subVal) show = false;
            if (yearVal && rYear.toLowerCase() !== yearVal) show = false;
            if (typeVal && category !== typeVal) show = false;
            if (statusVal) {
              const isPaid = (rDue <= 0.000001);
              if (statusVal === 'paid' && !isPaid) show = false;
              if (statusVal === 'unpaid' && isPaid) show = false;
            }

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
            
            if (category === 'extra') {
              extraInvoiced += amt;
              extraCollected += paid;
              extraDue += due;
            } else if (category === 'fala') {
              falaInvoiced += amt;
              falaCollected += paid;
              falaDue += due;
            } else {
              individualInvoiced += amt;
              individualCollected += paid;
              individualDue += due;
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
        const pfHofFm = document.getElementById('pf-hoffm');
        const pfSector = document.getElementById('pf-sector');
        const pfSub = document.getElementById('pf-subsector');
        const pfYear = document.getElementById('pf-year');
        const pfType = document.getElementById('pf-invoice-type');
        const pfStatus = document.getElementById('pf-status');
        if (pfName) pfName.addEventListener('input', applyPaymentFilters);
        if (pfHofFm) pfHofFm.addEventListener('change', applyPaymentFilters);
        if (pfSector) pfSector.addEventListener('change', applyPaymentFilters);
        if (pfSub) pfSub.addEventListener('change', applyPaymentFilters);
        if (pfType) pfType.addEventListener('change', applyPaymentFilters);
        if (pfStatus) pfStatus.addEventListener('change', applyPaymentFilters);
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
            if (pfHofFm) pfHofFm.value = '';
            if (pfSector) pfSector.value = '';
            if (pfSub) pfSub.value = '';
            if (pfYear) pfYear.value = '';
            if (pfType) pfType.value = '';
            if (pfStatus) pfStatus.value = '';
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

        // Handle search query parameter from URL to auto-filter on load
        const urlParams = new URLSearchParams(window.location.search);
        const searchVal = urlParams.get('search');
        if (searchVal && pfName) {
          pfName.value = searchVal;
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
            const invoiceType = p.invoice_type || 'regular';
            const viewBtn = `<button type="button" class="btn btn-sm btn-outline-secondary view-receipt-btn" data-payment-id="${p.payment_id || ''}" data-invoice-type="${invoiceType}"><i class="fa-solid fa-file-pdf"></i></button>`;
            const actionBtns = showActions ? `
                <td class="text-center">
                  ${viewBtn}
                  <button type="button" class="btn btn-sm btn-outline-primary ml-2 edit-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-type="${invoiceType}"
                          data-invoice-id="${invId}"><i class="fa-solid fa-pen"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-danger ml-2 delete-payment-btn" 
                          data-payment-id="${p.payment_id || ''}" 
                          data-amount="${isNaN(amt) ? 0 : amt}" 
                          data-invoice-type="${invoiceType}"
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

          const invoiceType = btn.getAttribute('data-invoice-type') || 'regular';
          const forVal = (invoiceType === 'extra') ? '3' : '2';

          fetch(endpoint, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: 'id=' + encodeURIComponent(pid) + '&for=' + encodeURIComponent(forVal)
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
          const invoiceType = btn.getAttribute('data-invoice-type') || 'regular';

          document.getElementById('ep-payment-id').value = pid || '';
          document.getElementById('ep-invoice-id').value = invId || '';
          document.getElementById('ep-payment-id-display').value = pid || '';
          document.getElementById('ep-amount').value = isNaN(amt) ? '' : amt.toFixed(2);
          
          const form = document.getElementById('edit-payment-form');
          if (form) {
            form.setAttribute('data-invoice-type', invoiceType);
          }

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
          const form = document.getElementById('edit-payment-form');
          const invoiceType = form ? (form.getAttribute('data-invoice-type') || 'regular') : 'regular';
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

          const endpoint = invoiceType === 'extra'
            ? '<?php echo base_url("anjuman/update_fmbgc_payment_amount_ajax"); ?>'
            : '<?php echo base_url("anjuman/update_miqaat_payment_amount"); ?>';

          fetch(endpoint, {
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
                alertBox.textContent = (data && data.message) ? data.message : ((data && data.error) ? data.error : 'Update failed.');
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
          const invoiceType = btn.getAttribute('data-invoice-type') || 'regular';
          if (!pid) return;
          if (!confirm('Are you sure you want to delete this payment?')) return;

          const endpoint = invoiceType === 'extra'
            ? '<?php echo base_url("anjuman/fmbgc_delete_payment"); ?>'
            : '<?php echo base_url("anjuman/delete_miqaat_payment"); ?>';

          fetch(endpoint, {
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
          const invoiceType = row ? (row.getAttribute('data-invoice-type') || 'regular') : 'regular';

          // Populate details block (match Update page structure as closely as possible)
          (function fillDetails() {
            const detailsEl = document.getElementById('rip-details');
            if (!detailsEl || !row) return;
            const tds = row.querySelectorAll('td');
            const dateText = tds[1] ? (tds[1].textContent || '-').trim() : '-';
            const hijriText = tds[2] ? (tds[2].textContent || '-').trim() : '-';
            const miqaatIdVal = row.getAttribute('data-miqaat-id');
            const miqaatIdText = miqaatIdVal ? 'M#' + miqaatIdVal : '-';
            const razaIdVal = row.getAttribute('data-raza-id');
            const razaIdText = razaIdVal ? 'R#' + razaIdVal : '-';
            const invoiceIdText = tds[3] ? (tds[3].textContent || '-').trim() : '-';
            const miqaatNameText = tds[4] ? (tds[4].textContent || '-').trim() : '-';
            const assignedText = tds[5] ? (tds[5].textContent || '-').trim() : '-';
            const extraDetailsText = tds[6] ? (tds[6].textContent || '-').trim() : '-';
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
          
          const form = document.getElementById('invoice-payment-form');
          if (form) {
            form.setAttribute('data-invoice-type', invoiceType);
          }

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
          const form = document.getElementById('invoice-payment-form');
          const invoiceType = form ? (form.getAttribute('data-invoice-type') || 'regular') : 'regular';
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
          const endpoint = invoiceType === 'extra'
            ? '<?php echo base_url("anjuman/add_fmbgc_payment_ajax"); ?>'
            : '<?php echo base_url("anjuman/add_miqaat_invoice_payment"); ?>';

          fetch(endpoint, {
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
              if (data && (data.success || data.status === true)) {
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
                  window.location.reload(); // Ensure everything is fully re-synced (e.g. payments cache)
                }, 800);
              } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = (data && data.error) ? data.error : (data && data.message ? data.message : 'Failed to save payment.');
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

        // Edit Invoice button click
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.invoice-edit-btn');
          if (!btn) return;
          const invoiceId = btn.getAttribute('data-invoice-id');
          const row = btn.closest('tr.miqaat-payment-row');
          const amount = parseFloat(row.getAttribute('data-amount') || '0');
          const description = row.getAttribute('data-description') || '';
          const invoiceDateIso = row.getAttribute('data-invoice-date') || '';
          const invoiceType = row ? (row.getAttribute('data-invoice-type') || 'regular') : 'regular';
          const contriYear = row ? (row.getAttribute('data-year') || '') : '';
          const contriType = row ? (row.getAttribute('data-miqaat-name') || '') : '';

          // Prefill details
          let miqaatIdText = '-';
          let razaIdText = '-';
          let miqaatNameText = '-';
          let dateText = '-';
          let hijriText = '-';
          let assignedText = '-';
          let assignmentDetailsText = '-';
          if (row) {
            const tds = row.querySelectorAll('td');
            if (tds && tds.length >= 9) {
              dateText = (tds[1].textContent || '-').trim();
              hijriText = (tds[2].textContent || '-').trim();
              const miqaatIdVal = row.getAttribute('data-miqaat-id');
              miqaatIdText = miqaatIdVal ? 'M#' + miqaatIdVal : '-';
              const razaIdVal = row.getAttribute('data-raza-id');
              razaIdText = razaIdVal ? 'R#' + razaIdVal : '-';
              miqaatNameText = (tds[4].textContent || '-').trim();
              assignedText = (tds[5].textContent || '-').trim();
              assignmentDetailsText = (tds[6].textContent || '-').trim();
            }
          }

          const detailsHtml = `
            <div><b>Miqaat ID:</b> ${escapeHtml(miqaatIdText)}</div>
            <div><b>Raza ID:</b> ${escapeHtml(razaIdText)}</div>
            <div><b>Miqaat Name:</b> ${escapeHtml(miqaatNameText)}</div>
            <div><b>Date:</b> ${escapeHtml(dateText)}</div>
            <div><b>Hijri:</b> ${escapeHtml(hijriText)}</div>
            <div><b>Assigned:</b> ${escapeHtml(assignedText)}</div>
            <div><b>Assignment Details:</b> ${escapeHtml(assignmentDetailsText)}</div>
          `;

          const form = document.getElementById('editInvoiceForm');
          if (form) {
            form.setAttribute('data-invoice-type', invoiceType);
            form.setAttribute('data-contri-year', contriYear);
            form.setAttribute('data-contri-type', contriType);
          }

          document.getElementById('edit-invoice-id').value = invoiceId || '';
          document.getElementById('edit-amount').value = Math.round(amount);
          document.getElementById('edit-description').value = description;
          document.getElementById('edit-invoice-date').value = invoiceDateIso;
          document.getElementById('edit-invoice-details').innerHTML = detailsHtml;

          if (window.jQuery && typeof jQuery.fn !== 'undefined' && typeof jQuery.fn.modal === 'function') {
            jQuery('#editInvoiceModal').modal('show');
          }
        });

        // Submit handler for Edit Invoice
        document.getElementById('editInvoiceForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const form = document.getElementById('editInvoiceForm');
          const invoiceType = form ? (form.getAttribute('data-invoice-type') || 'regular') : 'regular';
          const contriYear = form ? (form.getAttribute('data-contri-year') || '') : '';
          const contriType = form ? (form.getAttribute('data-contri-type') || '') : '';
          const miqaatType = '<?php echo isset($miqaat_type) ? $miqaat_type : ""; ?>';

          const invoiceId = document.getElementById('edit-invoice-id').value;
          const amount = parseFloat(document.getElementById('edit-amount').value || '0');
          const description = document.getElementById('edit-description').value;
          const invoiceDate = document.getElementById('edit-invoice-date').value;

          if (!invoiceId || isNaN(amount) || amount < 0) {
            alert('Please enter a valid amount.');
            return;
          }

          const saveBtn = document.getElementById('edit-invoice-save');
          saveBtn.disabled = true;

          const endpoint = invoiceType === 'extra'
            ? '<?php echo base_url("anjuman/updatefmbgcinvoice"); ?>'
            : '<?php echo base_url("anjuman/updateMiqaatInvoiceAmount"); ?>';

          let bodyData = `invoice_id=${encodeURIComponent(invoiceId)}&amount=${encodeURIComponent(amount.toFixed(2))}&description=${encodeURIComponent(description)}&invoice_date=${encodeURIComponent(invoiceDate)}`;
          if (invoiceType === 'extra') {
            bodyData += `&contri_year=${encodeURIComponent(contriYear)}&contri_type=${encodeURIComponent(contriType)}&miqaat_type=${encodeURIComponent(miqaatType)}`;
          }

          fetch(endpoint, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: bodyData
            })
            .then(res => res.json().catch(() => ({})))
            .then(data => {
              const isSuccess = invoiceType === 'extra' ? (data && data.success) : (data && data.status === true);
              if (isSuccess) {
                // Find row in table
                const row = document.querySelector(`tr.miqaat-payment-row[data-invoice-id="${invoiceId}"]`);
                if (row) {
                  const oldAmt = parseFloat(row.getAttribute('data-amount') || '0');
                  const oldPaid = parseFloat(row.getAttribute('data-paid') || '0');
                  const diff = amount - oldAmt;

                  // Update amount, due
                  const newAmt = amount;
                  const newDue = Math.max(0, newAmt - oldPaid);

                  row.setAttribute('data-amount', String(newAmt));
                  row.setAttribute('data-due', String(newDue));
                  row.setAttribute('data-description', description);
                  row.setAttribute('data-invoice-date', invoiceDate);

                  // Update cells
                  const amountCell = row.querySelector('.amount-cell');
                  const dueCell = row.querySelector('.due-cell');
                  const invoiceDateCell = row.querySelector('.invoice-date-cell');

                  if (amountCell) amountCell.textContent = currency(newAmt);
                  if (dueCell) {
                    dueCell.innerHTML = '<span class="' + (newDue > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') + '">' + currency(newDue) + '</span>';
                  }
                  if (invoiceDateCell && invoiceDate) {
                    const d = new Date(invoiceDate + 'T00:00:00');
                    if (!isNaN(d.getTime())) {
                      const day = String(d.getDate()).padStart(2, '0');
                      const month = d.toLocaleString('en-US', { month: 'long' });
                      const year = String(d.getFullYear());
                      invoiceDateCell.textContent = `${day} ${month} ${year}`;
                    } else {
                      invoiceDateCell.textContent = invoiceDate;
                    }
                  }

                  // Update receive button data-amount/disabled state
                  const rbtn = row.querySelector('.receive-payment-btn');
                  if (rbtn) {
                    rbtn.setAttribute('data-amount', String(newDue));
                    const reason = (rbtn.getAttribute('data-block-reason') || '').trim();
                    if (newDue <= 0.000001) {
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

                  // Flash row
                  row.classList.remove('flash-highlight');
                  void row.offsetWidth;
                  row.classList.add('flash-highlight');
                }

                if (window.jQuery) {
                  jQuery('#editInvoiceModal').modal('hide');
                }
                applyPaymentFilters();
              } else {
                alert(invoiceType === 'extra' ? (data && data.message ? data.message : 'Update failed.') : (data && data.error ? data.error : 'Update failed.'));
              }
            })
            .catch(() => {
              alert('Network error. Please try again.');
            })
            .finally(() => {
              saveBtn.disabled = false;
            });
        });

        // Delete Invoice button click
        document.addEventListener('click', function(e) {
          const btn = e.target.closest('.invoice-delete-btn');
          if (!btn) return;
          const invoiceId = btn.getAttribute('data-invoice-id');
          if (!invoiceId) return;
          if (!confirm('Are you sure you want to delete this invoice? This cannot be undone.')) return;
          
          btn.disabled = true;

          const row = btn.closest('tr.miqaat-payment-row');
          const invoiceType = row ? (row.getAttribute('data-invoice-type') || 'regular') : 'regular';

          const endpoint = invoiceType === 'extra'
            ? '<?php echo base_url("anjuman/fmbgc_delete_invoice"); ?>'
            : '<?php echo base_url("anjuman/deleteMiqaatInvoice"); ?>';
          
          fetch(endpoint, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `invoice_id=${encodeURIComponent(invoiceId)}`
            }).then(r => r.json().catch(() => ({
              success: false
            })))
            .then(data => {
              const isSuccess = invoiceType === 'extra' ? (data && data.success) : (data && data.status === true);
              if (isSuccess) {
                // Find row in table
                const row = document.querySelector(`tr.miqaat-payment-row[data-invoice-id="${invoiceId}"]`);
                if (row) {
                  // Fade out and remove
                  row.style.transition = 'opacity 0.3s';
                  row.style.opacity = 0;
                  setTimeout(() => {
                    row.parentNode.removeChild(row);
                    applyPaymentFilters();
                  }, 300);
                } else {
                  window.location.reload();
                }
              } else {
                alert(invoiceType === 'extra' ? (data && data.message ? data.message : 'Delete failed.') : (data && data.error ? data.error : 'Delete failed.'));
                btn.disabled = false;
              }
            })
            .catch(() => {
              alert('Network error while deleting invoice.');
              btn.disabled = false;
            });
        });

        // Styles for loading overlay (scoped to Fala modal)
        (function addFalaOverlayStyles() {
          try {
            const tag = document.createElement('style');
            tag.textContent = `
              #falaNiyazInvoicesModal .modal-body { position: relative; }
              .fala-loading-overlay { position: absolute; left:0; top:0; right:0; bottom:0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 1050; }
            `;
            document.head.appendChild(tag);
          } catch (e) {
            /* no-op */
          }
        })();

        // ========== Fala ni Niyaz Modal Logic: render miqaats ==========
        function buildFalaMiqaatsTable(miqaList, selectedYear = '') {
          if (!miqaList || !miqaList.length) {
            if (selectedYear) {
              const yearAmounts = NIYAZ_AMOUNTS_BY_YEAR[selectedYear] || NIYAZ_AMOUNTS_BY_YEAR['default'] || {individual_amount: 0, fala_amount: 0};
              const yearFalaAmt = parseFloat(yearAmounts.fala_amount) || 0;
              if (yearFalaAmt <= 0) {
                return `
                  <div class="text-center py-4">
                    <div class="alert alert-warning d-inline-block mb-3">
                      <i class="fa-solid fa-triangle-exclamation mr-1"></i> No invoices generated for <b>${escapeHtml(selectedYear)}</b> yet, and no Fala amount is set in Admin.
                    </div>
                    <div>
                      <span class="text-muted">Please configure the Fala amount under Manage Niyaz Invoice Amounts.</span>
                    </div>
                  </div>
                `;
              } else {
                const formattedAmt = '₹' + yearFalaAmt.toLocaleString('en-IN');
                return `
                  <div class="text-center py-4">
                    <div class="alert alert-info d-inline-block mb-4">
                      No invoices generated for <b>${escapeHtml(selectedYear)}</b> yet.
                    </div>
                    <div>
                      <button type="button" class="btn btn-lg btn-primary fala-do-takhmeen"
                        data-year="${escapeHtml(selectedYear)}"
                        data-miqaat-count="0"
                        data-earliest-date=""
                        data-latest-date=""
                        data-amount="${escapeHtml(String(yearFalaAmt))}"
                        style="border-radius: 8px; padding: 10px 24px;"
                      ><i class="fa-solid fa-calculator mr-2"></i> Do Takhmeen (Amount: ${formattedAmt})</button>
                    </div>
                  </div>
                `;
              }
            }
            return '<div class="alert alert-info">No miqaats found.</div>';
          }
          // Deduplicate groups by group_key to avoid repeated rows
          const unique = [];
          const seenGK = new Set();
          (Array.isArray(miqaList) ? miqaList : []).forEach(m => {
            const gk = m.group_key || '';
            const key = String(gk);
            if (key && seenGK.has(key)) return;
            if (key) seenGK.add(key);
            unique.push(m);
          });
          const rows = unique.map((m, idx) => {
            const gk = m.group_key || '';
            const name = m.miqaat_name || '';
            const date = m.miqaat_date ? formatDate(m.miqaat_date) : '';
            const year = (m.year !== undefined && m.year !== null) ? String(m.year) : '';
            const isGenerated = m.is_generated ? true : false;
            
            const yearAmounts = NIYAZ_AMOUNTS_BY_YEAR[year] || NIYAZ_AMOUNTS_BY_YEAR['default'] || {individual_amount: 0, fala_amount: 0};
            const yearFalaAmt = parseFloat(yearAmounts.fala_amount) || 0;
            const invoiceIds = Array.isArray(m.invoice_ids) ? m.invoice_ids : [];

            let amountColumnHtml = '';
            let actionColumnHtml = '';

            if (isGenerated) {
              const amt = (m.amount !== undefined && m.amount !== null) ? parseFloat(m.amount) : null;
              const amtText = (amt !== null && !Number.isNaN(amt)) ? currency(amt) : '-';
              amountColumnHtml = `
                <div class="fala-amount-view d-flex align-items-center justify-content-end">
                  <span class="fala-amount-text mr-2">${amtText}</span>
                  <button type="button" class="btn btn-link btn-sm fala-edit-amount" title="Edit amount"><i class="fa-solid fa-pencil"></i></button>
                </div>
                <div class="fala-amount-edit d-none">
                  <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₹</span>
                    </div>
                    <input type="number" class="form-control fala-amount-input" value="${(amt !== null && !Number.isNaN(amt)) ? Math.round(amt) : '0'}" step="1" min="0">
                  </div>
                  <div class="mt-2 text-right">
                    <button type="button" class="btn btn-sm btn-primary fala-save-amount"><i class="fa-solid fa-check"></i> Save</button>
                    <button type="button" class="btn btn-sm btn-secondary fala-cancel-amount"><i class="fa-solid fa-xmark"></i> Cancel</button>
                  </div>
                </div>
              `;
              actionColumnHtml = `
                <div class="btn-group btn-group-sm" role="group">
                  <button type="button" class="btn btn-sm btn-outline-success fala-generate-new-hofs" title="Generate invoices for newly added active HOFs"><i class="fa-solid fa-user-plus"></i> Generate for New HOFs</button>
                  <button type="button" class="btn btn-sm btn-outline-danger fala-delete-group ml-2" title="Delete all invoices in this group"><i class="fa-solid fa-trash"></i></button>
                </div>
              `;
            } else {
              const formattedAmt = '₹' + yearFalaAmt.toLocaleString('en-IN');
              if (yearFalaAmt <= 0) {
                amountColumnHtml = `<span class="text-danger font-weight-bold"><i class="fa-solid fa-triangle-exclamation"></i> Amount not set in Admin</span>`;
                actionColumnHtml = `<span class="text-muted">Please set Fala amount in Admin</span>`;
              } else {
                amountColumnHtml = `<b>${formattedAmt}</b>`;
                actionColumnHtml = `
                  <button type="button" class="btn btn-sm btn-primary fala-do-takhmeen"
                    data-year="${escapeHtml(year)}"
                    data-miqaat-count="${escapeHtml(String(m.pending_count || 0))}"
                    data-earliest-date="${escapeHtml(m.earliest_date || '')}"
                    data-latest-date="${escapeHtml(m.latest_date || '')}"
                    data-amount="${escapeHtml(String(yearFalaAmt))}"
                  ><i class="fa-solid fa-calculator"></i> Do Takhmeen</button>
                `;
              }
            }

            return `
              <tr data-group-key="${gk}" data-invoice-ids='${JSON.stringify(invoiceIds)}'>
                <td>${idx + 1}</td>
                <td>${gk}</td>
                <td>${name}</td>
                <td>${date || '-'}</td>
                <td>${year || '-'}</td>
                <td class="text-right align-middle">
                  ${amountColumnHtml}
                </td>
                <td class="text-center align-middle">
                  ${actionColumnHtml}
                </td>
              </tr>
            `;
          }).join('');
          return `
            <table class="table table-striped table-bordered">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Miqaat ID</th>
                  <th>Miqaat Name</th>
                  <th>Date</th>
                  <th>Year</th>
                  <th class="text-right">Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                ${rows}
              </tbody>
            </table>
          `;
        }

        const falaBtn = document.getElementById('fala-ni-niyaz-invoices');
        if (falaBtn) {
          falaBtn.addEventListener('click', function() {
            const list = Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : [];
            const selectedYear = document.getElementById('pf-year') ? document.getElementById('pf-year').value : '';
            const filteredList = list.filter(m => {
              const year = (m.year !== undefined && m.year !== null) ? String(m.year) : '';
              // Exclude single hijri years (no hyphen)
              if (!year.includes('-')) {
                return false;
              }
              // Only show the selected hijri year
              if (selectedYear && year !== selectedYear) {
                return false;
              }
              return true;
            });
            document.getElementById('fala-modal-table-wrapper').innerHTML = buildFalaMiqaatsTable(filteredList);
          });
        }

        // Group-level edit/delete/generate handlers for Fala modal
        const falaWrapper = document.getElementById('fala-modal-table-wrapper');
        const falaOverlay = document.getElementById('fala-loading-overlay');
        const falaLoading = {
          show(msg) {
            if (!falaOverlay) return;
            falaOverlay.classList.remove('d-none');
            const m = falaOverlay.querySelector('.message');
            if (m) m.textContent = msg || 'Please wait...';
          },
          hide() {
            if (!falaOverlay) return;
            falaOverlay.classList.add('d-none');
          }
        };

        if (falaWrapper) {
          falaWrapper.addEventListener('click', function(e) {
            // Do Takhmeen
            if (e.target.closest('.fala-do-takhmeen')) {
              const btn = e.target.closest('.fala-do-takhmeen');
              const year = btn.getAttribute('data-year');
              const count = btn.getAttribute('data-miqaat-count');
              const earliest = btn.getAttribute('data-earliest-date');
              const latest = btn.getAttribute('data-latest-date');
              const amount = btn.getAttribute('data-amount');
              const mtype = CURRENT_MIQAAT_TYPE;

              const genFormEl = document.getElementById('generateInvoiceForm');
              if (genFormEl) genFormEl.reset();

              const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val;
              };
              const setHtml = (id, html) => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = html;
              };

              setVal('modal_miqaat_index', '');
              setVal('modal_raza_index', '');
              setVal('input_member_id', '');

              setVal('input_miqaat_type', mtype);
              setVal('input_hijri_year', year);
              setVal('input_assigned_to', 'Fala ni Niyaz');

              const earliestFmt = earliest ? new Date(earliest).toLocaleDateString(undefined, {day: '2-digit', month: 'long', year: 'numeric'}) : '';
              const latestFmt = latest ? new Date(latest).toLocaleDateString(undefined, {day: '2-digit', month: 'long', year: 'numeric'}) : '';

              const details = `Yearly ${mtype} Fala ni Niyaz — Year: ${year}` +
                (count && count !== '0' ? ` | Miqaat Count: ${count}` : '') +
                (earliestFmt && latestFmt ? ` | ${earliestFmt} - ${latestFmt}` : '');
              setVal('input_details', details);

              const detailsHtml = `
                <div class="miqaat-details-card" style="background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-bottom: 15px;">
                  <div class="miqaat-details-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="miqaat-detail-item">
                      <div class="miqaat-detail-label" style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted);">Type</div>
                      <div class="miqaat-detail-value" style="font-weight: 600;">${escapeHtml(mtype)}</div>
                    </div>
                    <div class="miqaat-detail-item">
                      <div class="miqaat-detail-label" style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted);">Assigned To</div>
                      <div class="miqaat-detail-value" style="font-weight: 600;">Fala ni Niyaz</div>
                    </div>
                    <div class="miqaat-detail-item" style="grid-column: span 2;">
                      <div class="miqaat-detail-label" style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted);">Year</div>
                      <div class="miqaat-detail-value" style="font-weight: 600;">${escapeHtml(year)}</div>
                    </div>
                  </div>
                </div>
              `;
              setHtml('modal_miqaat_details', detailsHtml);

              const amountInput = document.getElementById('modal-amount');
              if (amountInput) {
                amountInput.value = amount;
                amountInput.readOnly = true;
              }

              const genModalEl = document.getElementById('generateInvoiceModal');
              if (genModalEl) {
                const genModal = new bootstrap.Modal(genModalEl);
                genModal.show();
              }
              return;
            }

            const row = e.target.closest('tr[data-group-key]');
            if (!row) return;
            const amountCell = row.querySelector('.text-right');
            const groupKey = row.getAttribute('data-group-key') || '';
            const invoiceIds = (() => {
              try {
                return JSON.parse(row.getAttribute('data-invoice-ids') || '[]');
              } catch {
                return [];
              }
            })();

            // Edit amount
            if (e.target.closest('.fala-edit-amount')) {
              const view = amountCell.querySelector('.fala-amount-view');
              const edit = amountCell.querySelector('.fala-amount-edit');
              view.classList.add('d-none');
              edit.classList.remove('d-none');
              const input = edit.querySelector('.fala-amount-input');
              if (input) input.focus();
              return;
            }

            // Cancel amount edit
            if (e.target.closest('.fala-cancel-amount')) {
              const view = amountCell.querySelector('.fala-amount-view');
              const edit = amountCell.querySelector('.fala-amount-edit');
              edit.classList.add('d-none');
              view.classList.remove('d-none');
              return;
            }

            // Save amount edit: apply to all invoices in the group
            if (e.target.closest('.fala-save-amount')) {
              const edit = amountCell.querySelector('.fala-amount-edit');
              const input = edit.querySelector('.fala-amount-input');
              const newVal = parseFloat(input.value);
              if (isNaN(newVal) || newVal < 0) {
                alert('Please enter a valid amount.');
                return;
              }
              const saveBtn = edit.querySelector('.fala-save-amount');
              const cancelBtn = edit.querySelector('.fala-cancel-amount');
              saveBtn.disabled = true;
              cancelBtn.disabled = true;

              // Update all invoices in the group sequentially
              const updateOne = (invoiceId) => fetch('<?php echo base_url("anjuman/updateMiqaatInvoiceAmount"); ?>', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}&amount=${encodeURIComponent(newVal.toFixed(2))}`
              }).then(res => {
                if (!res.ok) throw new Error('fail');
                return res;
              });

              (async () => {
                try {
                  falaLoading.show('Updating invoices...');
                  for (const id of invoiceIds) {
                    await updateOne(id);
                  }
                  // Update UI cell
                  const view = amountCell.querySelector('.fala-amount-view');
                  const amtText = view.querySelector('.fala-amount-text');
                  amtText.textContent = currency(newVal);
                  const edit = amountCell.querySelector('.fala-amount-edit');
                  edit.classList.add('d-none');
                  view.classList.remove('d-none');
                  // Update FALA_MIQAATS cached list
                  const idx = (Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : []).findIndex(m => (m.group_key || '') === groupKey);
                  if (idx > -1) {
                    FALA_MIQAATS[idx].amount = newVal;
                  }
                } catch (err) {
                  alert('Update failed for one or more invoices.');
                } finally {
                  saveBtn.disabled = false;
                  cancelBtn.disabled = false;
                  falaLoading.hide();
                }
              })();
              return;
            }

            // Generate for new HOFs
            if (e.target.closest('.fala-generate-new-hofs')) {
              if (!invoiceIds.length) {
                alert('No existing invoices in this group to copy.');
                return;
              }
              if (!confirm('Are you sure you want to generate Fala ni Niyaz invoices for any newly added active HOFs for this year?')) return;

              falaLoading.show('Generating invoices for new active HOFs...');
              fetch('<?php echo base_url("anjuman/generate_fala_ni_niyaz_for_new_hofs"); ?>', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_ids=${encodeURIComponent(JSON.stringify(invoiceIds))}`
              })
              .then(async res => {
                const text = await res.text();
                let data;
                try {
                  data = JSON.parse(text);
                } catch (e) {
                  throw new Error('Response is not valid JSON. Raw response: ' + text.substring(0, 300));
                }
                if (!res.ok) {
                  throw new Error(data.error || 'Server error ' + res.status);
                }
                return data;
              })
              .then(data => {
                if (data && data.status) {
                  alert(`Successfully generated ${data.created_count} new invoices.`);
                  window.location.reload(); // Reload the page to reflect new invoices
                } else {
                  alert('Generation failed: ' + (data.error || 'unknown error'));
                }
              })
              .catch(err => {
                alert('An error occurred during generation: ' + err.message);
              })
              .finally(() => {
                falaLoading.hide();
              });
              return;
            }

            // Delete all invoices in the group
            if (e.target.closest('.fala-delete-group')) {
              if (!invoiceIds.length) {
                alert('No invoices to delete in this group.');
                return;
              }
              if (!confirm('Delete ALL invoices in this group? This cannot be undone.')) return;

              const deleteOne = (invoiceId) => fetch('<?php echo base_url("anjuman/deleteMiqaatInvoice"); ?>', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `invoice_id=${encodeURIComponent(invoiceId)}`
              }).then(res => {
                if (!res.ok) throw new Error('fail');
                return res;
              });

              (async () => {
                try {
                  falaLoading.show('Deleting invoices...');
                  for (const id of invoiceIds) {
                    await deleteOne(id);
                  }
                  // Remove row from table
                  row.parentNode.removeChild(row);
                  // Remove from cached list
                  FALA_MIQAATS = (Array.isArray(FALA_MIQAATS) ? FALA_MIQAATS : []).filter(m => (m.group_key || '') !== groupKey);
                } catch (err) {
                  alert('Delete failed for one or more invoices.');
                } finally {
                  falaLoading.hide();
                }
              })();
            }
          });
        }


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

        // Ensure user confirms before proceeding with YEAR-LEVEL (ALL families) invoices
        (function() {
          if (typeof jQuery === 'undefined') return;
          jQuery(document).on('submit', '#generateInvoiceForm', function(e) {
            var mtype = (jQuery('#input_miqaat_type').val() || '').toLowerCase();
            var year = jQuery('#input_hijri_year').val() || '';
            var assignedTo = (jQuery('#input_assigned_to').val() || '').toLowerCase();
            var miqaatIndex = jQuery('#modal_miqaat_index').val() || '';
            var razaIndex = jQuery('#modal_raza_index').val() || '';

            // Year-level flow has no specific miqaat/raza index and targets all families
            var isYearLevel = assignedTo === 'fala ni niyaz' &&
              (mtype === 'shehrullah' || mtype === 'ashara') &&
              miqaatIndex === '' &&
              razaIndex === '';

            if (isYearLevel) {
              var confirmMsg = 'This will create invoices for ALL families for ' + (mtype.charAt(0).toUpperCase() + mtype.slice(1)) + ' (' + year + ').\n\nDo you want to continue?';
              if (!window.confirm(confirmMsg)) {
                e.preventDefault();
                return false;
              }
            }
          });
        })();

        // Autocomplete and form submission logic for Niyaz Extra Contribution modal
        (function() {
          if (typeof jQuery === 'undefined') return;

          let originalContriTypeOptions = [];
          jQuery(document).ready(function() {
            jQuery('#extra-contri-type option').each(function() {
              if (jQuery(this).val() !== '') {
                originalContriTypeOptions.push({
                  value: jQuery(this).val(),
                  text: jQuery(this).text(),
                  amount: jQuery(this).data('amount'),
                  hijriYear: jQuery(this).data('hijri-year'),
                  miqaatType: jQuery(this).data('miqaat-type')
                });
              }
            });
          });

          function filterContriTypes() {
            const selectedYear = jQuery('#extra-contri-year').val();
            const currentMiqaat = typeof CURRENT_MIQAAT_TYPE !== 'undefined' ? CURRENT_MIQAAT_TYPE : '';
            
            const contriTypeSelect = jQuery('#extra-contri-type');
            const currentValue = contriTypeSelect.val();
            
            contriTypeSelect.find('option:not([value=""])').remove();
            
            originalContriTypeOptions.forEach(function(opt) {
              const yearMatches = (opt.hijriYear === selectedYear);
              const miqaatMatches = (opt.miqaatType === currentMiqaat);
              
              if (yearMatches && miqaatMatches) {
                const newOpt = jQuery('<option></option>')
                  .val(opt.value)
                  .text(opt.text)
                  .attr('data-amount', opt.amount)
                  .attr('data-hijri-year', opt.hijriYear)
                  .attr('data-miqaat-type', opt.miqaatType);
                contriTypeSelect.append(newOpt);
              }
            });
            
            if (contriTypeSelect.find('option[value="' + currentValue + '"]').length > 0) {
              contriTypeSelect.val(currentValue);
            } else {
              contriTypeSelect.val('');
              jQuery('#extra-amount').val('');
            }
          }

          jQuery(document).on('change', '#extra-contri-year', function() {
            filterContriTypes();
          });

          jQuery('#createExtraContributionModal').on('show.bs.modal', function () {
            jQuery('#create-extra-contribution-form')[0].reset();
            jQuery('#extra-user-id').val('');
            
            const activeYear = '<?php echo htmlspecialchars($current_hijri_year ?? "", ENT_QUOTES); ?>';
            if (activeYear) {
              jQuery('#extra-contri-year').val(activeYear);
            }
            
            filterContriTypes();
          });

          jQuery(document).on('input', '#extra-member-autocomplete', function() {
            const query = jQuery(this).val();
            const listContainer = jQuery('#extra-member-autocomplete-list');
            const userIdInput = jQuery('#extra-user-id');
            if (!query || query.length < 2) {
              listContainer.empty().hide();
              userIdInput.val('');
              return;
            }
            jQuery.ajax({
              url: "<?php echo base_url('anjuman/searchmembers'); ?>",
              type: "POST",
              data: { query: query },
              dataType: "json",
              success: function(res) {
                if (res.success && Array.isArray(res.members) && res.members.length > 0) {
                  let listHtml = '';
                  res.members.forEach(function(member) {
                    listHtml += `<button type="button" class="autocomplete-item extra-member-suggestion" data-id="${member.ITS_ID}" data-name="${member.Full_Name}">${member.Full_Name} (${member.ITS_ID})</button>`;
                  });
                  listContainer.html(listHtml).show();
                } else {
                  listContainer.html('<div class="p-3 text-muted text-center" style="font-size:0.85rem;">No members found</div>').show();
                }
              },
              error: function() {
                listContainer.html('<div class="p-3 text-danger text-center" style="font-size:0.85rem;">Error searching members</div>').show();
              }
            });
          });

          // Select member from autocomplete
          jQuery(document).on('click', '.extra-member-suggestion', function() {
            const itsId = jQuery(this).attr('data-id');
            const name = jQuery(this).attr('data-name');
            jQuery('#extra-user-id').val(itsId);
            jQuery('#extra-member-autocomplete').val(name);
            jQuery('#extra-member-autocomplete-list').empty().hide();
          });

          // Auto-fill amount and hijri year on contribution type change
          jQuery(document).on('change', '#extra-contri-type', function() {
            const selectedOption = jQuery(this).find('option:selected');
            const amount = selectedOption.data('amount');
            if (amount !== undefined && amount !== null && amount !== '') {
              jQuery('#extra-amount').val(amount);
            }
            const hijriYear = selectedOption.data('hijri-year');
            if (hijriYear) {
              jQuery('#extra-contri-year').val(hijriYear);
            }
          });

          // Hide suggestions on outside click
          jQuery(document).on('click', function(e) {
            if (!jQuery(e.target).closest('#extra-member-autocomplete, #extra-member-autocomplete-list').length) {
              jQuery('#extra-member-autocomplete-list').empty().hide();
            }
          });

          // Form submission
          jQuery(document).on('submit', '#create-extra-contribution-form', function(e) {
            e.preventDefault();
            const btn = jQuery('#create-extra-contribution-submit');
            btn.prop('disabled', true);
            
            const formData = jQuery(this).serialize();
            jQuery.ajax({
              url: "<?php echo base_url('anjuman/addfmbgc_ajax'); ?>",
              type: "POST",
              data: formData,
              dataType: "json",
              success: function(res) {
                if (res && res.success) {
                  alert(res.message || 'Invoice created successfully.');
                  const itsId = jQuery('#extra-user-id').val();
                  if (itsId) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', itsId);
                    window.location.href = url.toString();
                  } else {
                    window.location.reload();
                  }
                } else {
                  alert(res.message || 'Failed to create invoice.');
                  btn.prop('disabled', false);
                }
              },
              error: function() {
                alert('An error occurred. Please try again.');
                btn.prop('disabled', false);
              }
            });
          });
        })();
      })();
    </script>
</div>