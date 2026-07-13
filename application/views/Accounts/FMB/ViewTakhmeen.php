<?php
// ==== Aggregations (identical to original) ====
$gc_total_amount = 0; $gc_total_paid = 0; $gc_invoice_count = 0;
$gc_fully_paid = 0; $gc_partial = 0; $gc_unpaid = 0;
if (!empty($fmb_takhmeen_details['general_contributions'])) {
  foreach ($fmb_takhmeen_details['general_contributions'] as $gcRow) {
    $gc_invoice_count++;
    $amt  = (float)($gcRow['amount'] ?? 0);
    $paid = (float)($gcRow['amount_paid'] ?? 0);
    $due  = max(0, $amt - $paid);
    $gc_total_amount += $amt;
    $gc_total_paid   += min($paid, $amt);
    if ($due <= 0.00001) $gc_fully_paid++;
    elseif ($paid > 0) $gc_partial++;
    else $gc_unpaid++;
  }
}
$gc_total_due = max(0, $gc_total_amount - $gc_total_paid);

$miqaat_total_amount = 0; $miqaat_total_paid = 0; $miqaat_invoice_count = 0;
$miqaat_paid_count = 0; $miqaat_partial = 0; $miqaat_unpaid = 0;
if (!empty($miqaat_invoices)) {
  foreach ($miqaat_invoices as $mv) {
    $miqaat_invoice_count++;
    $amt  = (float)($mv['amount'] ?? 0);
    $paid = (float)($mv['paid_amount'] ?? 0);
    $due  = max(0, $amt - $paid);
    $miqaat_total_amount += $amt;
    $miqaat_total_paid   += min($paid, $amt);
    if ($due <= 0.00001) $miqaat_paid_count++;
    elseif ($paid > 0) $miqaat_partial++;
    else $miqaat_unpaid++;
  }
}
$miqaat_total_due = max(0, $miqaat_total_amount - $miqaat_total_paid);

$fmb_overall_amount = (float)($fmb_takhmeen_details['overall']['total_amount'] ?? 0);
$fmb_overall_paid   = (float)($fmb_takhmeen_details['overall']['total_paid'] ?? 0);
$fmb_overall_due    = (float)($fmb_takhmeen_details['overall']['total_due'] ?? max(0, $fmb_overall_amount - $fmb_overall_paid));
$fmb_takhmeen_count = !empty($fmb_takhmeen_details['all_takhmeen']) ? count($fmb_takhmeen_details['all_takhmeen']) : 0;
$currentYearLabel   = $fmb_takhmeen_details['current_year']['year'] ?? ($fmb_takhmeen_details['latest']['year'] ?? '—');

$takh_pct = $fmb_overall_amount > 0 ? ($fmb_overall_paid / $fmb_overall_amount) * 100 : 0;
$gc_pct   = $gc_total_amount   > 0 ? ($gc_total_paid   / $gc_total_amount)   * 100 : 0;
$miq_pct  = $miqaat_total_amount > 0 ? ($miqaat_total_paid / $miqaat_total_amount) * 100 : 0;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

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
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --green-border:#bbf7d0;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #b45309;
    --amber-bg:    #fffbeb;
    --amber-border:#fde68a;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:      0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap {
    position: relative; display: flex; align-items: center;
    justify-content: center; min-height: 44px; margin-bottom: 6px;
  }
  .btn-back-nav {
    position: absolute; left: 0; width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface); color: var(--text-2); font-size: 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
  }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.55rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 28px; }

  /* ── Summary cards ── */
  .dash-card {
    background: var(--surface); border-radius: var(--radius-lg);
    border: 1.5px solid var(--border); box-shadow: var(--shadow);
    overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .2s;
  }
  .dash-card:hover { box-shadow: var(--shadow-lg); }
  .dash-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

  .dash-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px 12px; border-bottom: 1px solid var(--border); background: var(--surface-2);
  }
  .dash-card-header .card-title {
    font-size: 0.78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    color: var(--text-2); display: flex; align-items: center; gap: 8px; margin: 0;
  }
  .dash-card-header .card-title .fa {
    width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.75rem; background: var(--gold-muted); color: var(--gold);
  }
  .badge-pill { font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 40px; letter-spacing: .4px; }
  .badge-danger  { background: var(--red-bg);   color: var(--red);   border: 1px solid var(--red-border); }
  .badge-success { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }

  .dash-card-body { padding: 18px 20px; flex: 1; display: flex; flex-direction: column; }

  /* ── Stat tiles ── */
  .stat-tile {
    background: var(--surface-2); border-radius: var(--radius); border: 1px solid var(--border);
    padding: 14px 12px; text-align: center; transition: background .15s;
  }
  .stat-tile:hover { background: var(--gold-muted); }
  .stat-tile .tile-label { font-size: 0.65rem; font-weight: 700; color: var(--text-3); letter-spacing: .5px; text-transform: uppercase; margin-bottom: 6px; }
  .stat-tile .tile-value { font-size: 1.25rem; font-weight: 800; line-height: 1; }
  .tile-value.green { color: var(--green); }
  .tile-value.red   { color: var(--red); }
  .tile-value.blue  { color: var(--blue); }
  .tile-value.amber { color: var(--amber); }
  .amount-big { font-size: 1.55rem; font-weight: 800; letter-spacing: -1px; }

  .breakdown-row { display: flex; gap: 10px; margin-top: 12px; }
  .breakdown-row .stat-tile { flex: 1; }

  /* progress bar */
  .prog-wrap { height: 6px; background: var(--border); border-radius: 4px; overflow: hidden; margin-bottom: 6px; }
  .prog-bar  { height: 100%; border-radius: 4px; }

  .prog-meta { font-size: 0.65rem; color: var(--text-3); text-transform: uppercase; font-weight: 600; letter-spacing: .5px; margin-bottom: 14px; }

  /* view details button */
  .btn-view {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    padding: 7px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    color: var(--text-2); background: var(--surface); text-decoration: none;
    transition: all .15s; cursor: pointer;
  }
  .btn-view:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

  /* ── Detail sections (inside modals) ── */
  .detail-section.card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius); overflow: hidden; box-shadow: none;
  }
  .detail-section.card .card-header {
    background: var(--surface-2); border-bottom: 1px solid var(--border); padding: 12px 20px;
    display: flex; align-items: center; justify-content: center;
  }
  .detail-section.card .card-header h5.card-title {
    font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-2);
    font-size: 0.83rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    margin: 0; display: flex; align-items: center; gap: 8px;
  }
  .detail-section.card .card-header h5.card-title .fa {
    width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.75rem; background: var(--gold-muted); color: var(--gold);
  }

  /* ── Tables ── */
  .table { font-size: 0.8rem; border-collapse: collapse; background: var(--surface); width: 100%; }
  .table thead th {
    font-size: 0.65rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    color: var(--text-3); padding: 11px 16px; border-bottom: 1.5px solid var(--border) !important;
    background: var(--surface-2); border-top: none; white-space: nowrap;
  }
  .table tbody td { padding: 11px 16px; border-bottom: 1px solid var(--border); color: var(--text-1); vertical-align: middle; }
  .table tbody tr:hover { background: #fdfbf5 !important; }
  .table tbody tr:last-child td { border-bottom: none; }
  .table tr.table-warning { background: var(--gold-muted) !important; font-weight: 700; }
  .table-striped tbody tr:nth-of-type(odd)  { background: var(--surface-2) !important; }
  .table-striped tbody tr:nth-of-type(even) { background: var(--surface) !important; }
  .table-striped tbody tr:hover { background: #fdfbf5 !important; }

  .text-success { color: var(--green) !important; font-weight: 600; }
  .text-danger  { color: var(--red)   !important; font-weight: 600; }
  .text-primary { color: var(--blue)  !important; }

  /* ── GC / Miqaat cards ── */
  #gc-card-list, #miqaat-card-list { display: block; }
  .gc-card, .miq-card {
    background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius);
    padding: 14px 16px; margin-bottom: 12px; box-shadow: var(--shadow-sm);
    transition: box-shadow .15s, border-color .15s;
  }
  .gc-card:hover, .miq-card:hover { box-shadow: var(--shadow); border-color: rgba(184,134,11,0.3); }

  .gc-card-header, .miq-card-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 8px; gap: 8px;
  }
  .gc-card-header strong, .miq-card-header strong { font-size: 0.85rem; color: var(--text-1); }

  .gc-badge, .miq-badge {
    font-size: .62rem; padding: 3px 9px; border-radius: 40px;
    font-weight: 700; letter-spacing: .4px; text-transform: uppercase;
  }
  .gc-badge.paid,    .miq-badge.paid    { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .gc-badge.partial, .miq-badge.partial { background: var(--amber-bg); color: var(--amber); border: 1px solid var(--amber-border); }
  .gc-badge.unpaid,  .miq-badge.unpaid  { background: var(--red-bg);   color: var(--red);   border: 1px solid var(--red-border); }

  .gc-meta, .miq-meta {
    font-size: .7rem; color: var(--text-3); display: flex; flex-wrap: wrap;
    gap: .4rem .8rem; margin-bottom: 10px;
  }
  .gc-meta span strong, .miq-meta span strong { color: var(--text-2); }

  .gc-amounts, .miq-amounts { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 10px; }
  .gc-amounts .amt, .miq-amounts .amt { flex: 1 1 80px; }
  .gc-amounts .lbl, .miq-amounts .lbl { font-size: .6rem; text-transform: uppercase; letter-spacing: .5px; color: var(--text-3); font-weight: 700; }
  .gc-amounts .val, .miq-amounts .val { font-size: .9rem; font-weight: 700; }

  .gc-inline-divider, .miq-inline-divider { height: 1px; background: var(--border); margin: 8px 0 10px; }

  .gc-desc-empty { font-size: .72rem; color: var(--text-3); font-style: italic; }

  .gc-history-btn-wrapper, .miq-history-btn-wrapper { display: flex; justify-content: flex-end; margin-top: 8px; }
  .gc-actions, .miq-actions { display: flex; gap: 6px; }

  /* Themed outline button for secondary actions */
  .btn-outline-primary, .btn-outline-secondary {
    border-radius: var(--radius-sm) !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 0.75rem !important; font-weight: 600 !important;
  }
  .btn-outline-secondary { border-color: var(--border) !important; color: var(--text-2) !important; background: var(--surface) !important; }
  .btn-outline-secondary:hover { border-color: var(--gold) !important; color: var(--gold) !important; background: var(--gold-muted) !important; }
  .btn-outline-primary { border-color: var(--blue-border) !important; color: var(--blue) !important; background: var(--blue-bg) !important; }
  .btn-outline-primary:hover { background: var(--blue) !important; color: #fff !important; }

  /* ── Modals ── */
  #details-modal { z-index: 1060; }
  .modal-backdrop { z-index: 1050; }
  .modal.dimmed-underlay { filter: blur(2px) brightness(.9); }
  .modal.dimmed-underlay .modal-content { pointer-events: none; }

  .modal-content {
    background: var(--surface); border-radius: var(--radius-lg) !important;
    border: 1.5px solid var(--border) !important; box-shadow: var(--shadow-lg) !important;
    overflow: hidden;
  }
  .modal-header {
    background: var(--surface-2); border-bottom: 1px solid var(--border);
    padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif; color: var(--gold);
    font-size: 1.05rem; font-weight: 600; margin: 0;
  }
  .modal-header .close {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; cursor: pointer; color: var(--text-2);
    transition: all .15s; padding: 0; opacity: 1;
    text-shadow: none;
  }
  .modal-header .close:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }
  .modal-body { background: var(--surface); padding: 20px 24px; }
  .modal-footer {
    background: var(--surface-2); border-top: 1px solid var(--border);
    padding: 12px 24px; display: flex; justify-content: flex-end; gap: 8px;
  }

  /* Modal action buttons */
  .btn-secondary {
    padding: 8px 20px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border) !important; background: var(--surface) !important;
    color: var(--text-2) !important; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-secondary:hover { background: var(--surface-2) !important; }

  /* ── GC payments summary strip ── */
  #gc-payments-modal .gcph-summary-wrapper,
  #miqaat-history-modal .miqaat-summary-wrapper {
    padding: 14px 20px 12px; background: var(--surface-2); border-bottom: 1px solid var(--border);
  }
  .gcph-summary-grid, .miqaat-summary-grid {
    display: flex; flex-wrap: wrap; gap: 14px 28px; font-size: .78rem;
  }
  .gcph-summary-grid .item .lbl, .miqaat-summary-grid .item .lbl {
    text-transform: uppercase; font-size: .6rem; letter-spacing: .5px;
    color: var(--text-3); font-weight: 700; display: block; margin-bottom: 1px;
  }
  .gcph-summary-grid .item .val, .miqaat-summary-grid .item .val { font-weight: 700; font-size: .88rem; }

  .gcph-status-badge, .miqaat-status-badge {
    display: inline-block; padding: 2px 9px; border-radius: 40px;
    font-size: .62rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
  }
  .gcph-status-paid,    .miqaat-status-paid    { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .gcph-status-partial, .miqaat-status-partial { background: var(--amber-bg); color: var(--amber); border: 1px solid var(--amber-border); }
  .gcph-status-unpaid,  .miqaat-status-unpaid  { background: var(--red-bg);   color: var(--red);   border: 1px solid var(--red-border); }

  .gcph-progress-wrap, .miqaat-progress-wrap { margin-top: 10px; }
  .progress.gcph-progress, .progress.miqaat-progress {
    height: 6px; background: var(--border); border-radius: 4px; overflow: hidden;
  }
  .progress.gcph-progress .progress-bar    { background: linear-gradient(90deg, var(--green), var(--gold-light)); height: 100%; }
  .progress.miqaat-progress .progress-bar  { background: linear-gradient(90deg, var(--blue),  var(--gold-light)); height: 100%; }

  .table thead th.sticky-head { position: sticky; top: 0; background: var(--surface-2); z-index: 2; }

  .loading-overlay {
    position: absolute; inset: 0; background: rgba(255,255,255,.8);
    display: flex; align-items: center; justify-content: center; z-index: 15;
  }
  .loading-overlay.d-none { display: none !important; }

  .btn.btn-shrink { padding: .25rem .45rem; font-size: .62rem !important; }

  /* ── Assigned dates modal ── */
  #assigned-thaali-days-container .modal-body p { font-size: 0.85rem; color: var(--text-2); }
  #assigned-thaali-days-container #assigned-dates-list { padding-left: 20px; }
  #assigned-thaali-days-container #assigned-dates-list li { font-size: 0.83rem; color: var(--text-1); padding: 4px 0; border-bottom: 1px solid var(--border); }
  #assigned-thaali-days-container #assigned-dates-list li:last-child { border-bottom: none; }

  /* ── Desktop grid for GC/Miqaat cards ── */
  @media (min-width: 992px) {
    #gc-card-list, #miqaat-card-list {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding: 16px;
    }
    #gc-card-list .gc-card, #miqaat-card-list .miq-card { margin-bottom: 0; display: flex; flex-direction: column; height: 100%; }
    #gc-card-list .gc-history-btn-wrapper, #miqaat-card-list .miq-history-btn-wrapper { margin-top: auto; }
  }
  @media (min-width: 768px) and (max-width: 991.98px) {
    #gc-card-list, #miqaat-card-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; padding: 12px; }
    #gc-card-list .gc-card, #miqaat-card-list .miq-card { margin-bottom: 0; }
  }
  @media (max-width: 767px) {
    #gc-card-list, #miqaat-card-list { padding: 12px; }
  }

  /* Mobile table cards */
  @media (max-width: 575.98px) {
    #gc-payments-modal .modal-dialog, #miqaat-history-modal .modal-dialog { margin: .5rem auto; max-width: 100%; }
    #gc-payments-modal .table thead, #miqaat-history-modal .table thead { display: none; }
    #gc-payments-modal .table tbody tr, #miqaat-history-modal .table tbody tr {
      display: block; background: var(--surface); margin: 0 0 .75rem;
      border: 1.5px solid var(--border); border-radius: var(--radius-sm);
      box-shadow: var(--shadow-sm); padding: .5rem .65rem;
    }
    #gc-payments-modal .table tbody td, #miqaat-history-modal .table tbody td {
      display: flex; padding: .35rem .25rem; border: none !important;
      font-size: .76rem; justify-content: space-between; gap: 8px;
    }
    #gc-payments-modal .table tbody td[data-label]::before,
    #miqaat-history-modal .table tbody td[data-label]::before {
      content: attr(data-label); font-weight: 700; color: var(--text-3);
      text-transform: uppercase; letter-spacing: .5px; font-size: .62rem;
    }
    .modal-header, .modal-footer { padding: 12px 16px !important; }
    .modal-body { padding: 14px 16px !important; }
    .gcph-summary-wrapper, .miqaat-summary-wrapper { padding: 12px 14px 10px !important; }
    .gc-history-btn-wrapper .btn, .miq-history-btn-wrapper .btn { width: 100%; }
  }
</style>

<div class="container margintopcontainer pt-4 pb-5">

  <!-- Back Button & Page Header -->
  <div class="row mb-3">
    <div class="col-12">
      <a href="<?php echo base_url('accounts'); ?>" class="btn-gold-outline"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <div class="page-header-wrap mb-4">
    <h1 class="page-heading">FMB Takhmeen</h1>
  </div>

  <!-- TAKHMEEN SECTION DIRECTLY ON PAGE -->
  <div id="sec-takhmeen" class="card shadow-sm">
    <div class="card-header"><h5 class="card-title mb-0"><i class="fa fa-cutlery"></i> FMB Takhmeen (All Years)</h5></div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
          <thead>
            <tr>
              <th>Year</th>
              <th class="text-right">Thaali Days</th>
              <th class="text-right">Amount (₹)</th>
              <th class="text-right">Paid (₹)</th>
              <th class="text-right">Due (₹)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rows_input = !empty($fmb_takhmeen_details['all_takhmeen']) && is_array($fmb_takhmeen_details['all_takhmeen'])
              ? $fmb_takhmeen_details['all_takhmeen'] : [];
            if (!empty($rows_input)) {
              $per_year_paid_present = 0.0;
              foreach ($rows_input as $r) { $per_year_paid_present += (float)($r['total_paid'] ?? 0); }
              $overall_paid_sum = 0.0;
              if (!empty($fmb_takhmeen_details['all_payments']) && is_array($fmb_takhmeen_details['all_payments'])) {
                foreach ($fmb_takhmeen_details['all_payments'] as $p) { $overall_paid_sum += (float)($p['amount'] ?? 0); }
              }
              $allocated = [];
              if ($overall_paid_sum > 0 && $per_year_paid_present < 0.00001) {
                $years_sorted = $rows_input;
                usort($years_sorted, function($a,$b){
                  $aBase = (int)preg_replace('/^(\d{4}).*$/','$1',preg_replace('/[^0-9\-]/','', $a['year']??''));
                  $bBase = (int)preg_replace('/^(\d{4}).*$/','$1',preg_replace('/[^0-9\-]/','', $b['year']??''));
                  return $aBase <=> $bBase;
                });
                $remaining = $overall_paid_sum;
                foreach ($years_sorted as $yrRow) {
                  $yearLabel = $yrRow['year'] ?? '';
                  $amt = (float)($yrRow['total_amount'] ?? 0);
                  $pay = min(max($remaining,0), $amt);
                  $allocated[$yearLabel] = $pay;
                  $remaining -= $pay;
                  if ($remaining <= 0) break;
                }
              }
              foreach ($rows_input as $row) {
                $rowYear  = $row['year'] ?? '';
                $basePaid = (float)($row['total_paid'] ?? 0);
                $paidVal  = isset($allocated[$rowYear]) ? (float)$allocated[$rowYear] : $basePaid;
                $amtVal   = (float)($row['total_amount'] ?? 0);
                $assignedDaysVal = isset($row['assigned_thaali_days']) ? (int)$row['assigned_thaali_days'] : 0;
                $dueVal   = max(0, $amtVal - $paidVal);
                $highlight = ($currentYearLabel && strpos($rowYear,(string)$currentYearLabel) !== false) ? 'table-warning fw-bold' : '';
                echo '<tr class="'.$highlight.'">';
                echo '<td>'.htmlspecialchars($rowYear).'</td>';
                echo '<td class="text-right"><a href="#" class="view-assigned-thaali-days" data-year="'.htmlspecialchars($rowYear,ENT_QUOTES).'">'.$assignedDaysVal.'</a></td>';
                echo '<td class="text-right">₹'.format_inr_no_decimals($amtVal).'</td>';
                echo '<td class="text-right text-success">₹'.format_inr_no_decimals($paidVal).'</td>';
                echo '<td class="text-right text-danger">₹'.format_inr_no_decimals($dueVal).'</td>';
                echo '</tr>';
              }
            } else { ?>
              <tr><td colspan="5" class="text-center" style="color:var(--text-3);padding:24px;">Takhmeen not found.</td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div style="border-top:1px solid var(--border);">
        <div style="padding:14px 20px 10px; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:0.78rem; font-weight:700; color:var(--text-2);">Overall Payment History</span>
          <span style="font-size:0.65rem; color:var(--text-3); font-style:italic;">Payments allocated oldest-year first</span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-striped mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th class="text-right">Amount (₹)</th>
                <th>Method</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($fmb_takhmeen_details['all_payments'])): ?>
                <?php foreach ($fmb_takhmeen_details['all_payments'] as $pay): ?>
                  <tr>
                    <td><?php echo $pay['payment_date'] ? date('d-M-Y',strtotime($pay['payment_date'])) : '-'; ?></td>
                    <td class="text-right text-success"><?php echo format_inr_no_decimals((float)$pay['amount']); ?></td>
                    <td><?php echo htmlspecialchars($pay['payment_method'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($pay['remarks'] ?? '-'); ?></td>
                    <td><button class="view-invoice btn btn-sm btn-outline-primary" data-payment-id="<?php echo (int)$pay['id']; ?>" title="View Receipt"><i class="fa fa-file-pdf-o"></i></button></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="5" class="text-center" style="color:var(--text-3);padding:16px;">No payments found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    </div>

    <!-- GENERAL CONTRIBUTIONS SECTION -->
    <div id="sec-gc" class="detail-section card d-none">
      <div class="card-header"><h5 class="card-title mb-0"><i class="fa fa-gift"></i> FMB Extra Contributions</h5></div>
      <div class="card-body p-0">
        <div id="gc-card-list" class="p-2">
          <?php if (!empty($fmb_takhmeen_details['general_contributions'])): ?>
            <?php foreach ($fmb_takhmeen_details['general_contributions'] as $idx => $gc): ?>
              <?php
              $amount     = (float)$gc['amount'];
              $paid       = isset($gc['amount_paid']) ? (float)$gc['amount_paid'] : 0.0;
              $dueRaw     = isset($gc['total_due']) ? (float)$gc['total_due'] : max($amount - $paid, 0);
              $statusFlag = (int)$gc['payment_status'];
              $due        = $dueRaw < 0.00001 ? 0 : $dueRaw;
              $badgeClass = 'unpaid'; $badgeText = 'Unpaid';
              if ($paid > 0 && $due > 0) { $badgeClass='partial'; $badgeText='Partial'; }
              if ($statusFlag === 1 || $due === 0) { $badgeClass='paid'; $badgeText='Paid'; }
              ?>
              <div class="gc-card" data-gc-id="<?php echo (int)$gc['id']; ?>">
                <div class="gc-card-header">
                  <strong>#<?php echo $idx+1; ?> &bull; <?php echo htmlspecialchars($gc['contri_type']); ?></strong>
                  <span class="gc-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                </div>
                <div class="gc-meta">
                  <span>Year: <strong><?php echo htmlspecialchars($gc['contri_year']); ?></strong></span>
                  <span>Type: <strong><?php echo htmlspecialchars($gc['fmb_type']); ?></strong></span>
                  <span>Date: <strong><?php echo $gc['created_at'] ? date('d-M-Y',strtotime($gc['created_at'])) : '-'; ?></strong></span>
                </div>
                <div class="gc-amounts">
                  <div class="amt"><div class="lbl">Amount</div><div class="val text-primary">₹<?php echo format_inr_no_decimals($amount); ?></div></div>
                  <div class="amt"><div class="lbl">Paid</div><div class="val text-success">₹<?php echo format_inr_no_decimals($paid); ?></div></div>
                  <div class="amt"><div class="lbl">Due</div><div class="val text-danger">₹<?php echo format_inr_no_decimals($due); ?></div></div>
                </div>
                <div class="gc-inline-divider"></div>
                <div class="gc-actions">
                  <?php if (!empty($gc['description'])): ?>
                    <button class="btn btn-outline-primary btn-sm view-description" data-description="<?php echo htmlspecialchars($gc['description']); ?>" data-toggle="modal" data-target="#description-modal" title="View Description"><i class="fa fa-eye"></i></button>
                  <?php else: ?>
                    <span class="gc-desc-empty">No description</span>
                  <?php endif; ?>
                </div>
                <div class="gc-history-btn-wrapper">
                  <button class="btn btn-outline-secondary btn-sm view-gc-payments"
                    data-fmbgc-id="<?php echo (int)$gc['id']; ?>"
                    data-amount="<?php echo number_format($amount,2,'.',''); ?>"
                    data-paid="<?php echo number_format($paid,2,'.',''); ?>"
                    data-due="<?php echo number_format($due,2,'.',''); ?>"
                    title="View Payment History">
                    <i class="fa fa-history"></i> Payment History
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div style="text-align:center;color:var(--text-3);font-size:0.83rem;padding:24px;">No general contributions found.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- /#details-sections -->

  <!-- ── Description Modal ── -->
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle" style="margin-right:8px;color:var(--gold);"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p id="modal-view-description" style="font-size:0.88rem;color:var(--text-1);line-height:1.6;"></p>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

  <!-- ── GC Payment History Modal ── -->
  <div class="modal fade" id="gc-payments-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-file-text-o" style="margin-right:8px;color:var(--gold);"></i> Contribution Payment History</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0 position-relative">
          <div class="gcph-summary-wrapper">
            <div class="d-flex justify-content-between flex-wrap align-items-start">
              <div class="gcph-summary-grid">
                <div class="item"><span class="lbl">Invoice ID</span><span class="val" id="gcph-invoice-id">-</span></div>
                <div class="item"><span class="lbl">Year</span><span class="val" id="gcph-year">-</span></div>
                <div class="item"><span class="lbl">Type</span><span class="val" id="gcph-type">-</span></div>
                <div class="item"><span class="lbl">Amount</span><span class="val text-primary">₹<span id="gcph-amount">0</span></span></div>
                <div class="item"><span class="lbl">Received</span><span class="val text-success">₹<span id="gcph-received">0</span></span></div>
                <div class="item"><span class="lbl">Balance</span><span class="val text-danger">₹<span id="gcph-balance">0</span></span></div>
                <div class="item"><span class="lbl">Status</span><span class="val"><span id="gcph-status-badge" class="gcph-status-badge gcph-status-unpaid">UNPAID</span></span></div>
                <div class="item"><span class="lbl">Paid %</span><span class="val" id="gcph-paid-pct">0%</span></div>
              </div>
            </div>
            <div class="gcph-progress-wrap"><div class="progress gcph-progress"><div class="progress-bar" id="gcph-progress-bar" style="width:0%"></div></div></div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" id="gc-payments-table">
              <thead>
                <tr>
                  <th class="sticky-head">#</th><th class="sticky-head">Date</th>
                  <th class="sticky-head text-right">Amount (₹)</th><th class="sticky-head">Method</th>
                  <th class="sticky-head">Remarks</th><th class="sticky-head text-right">Receipt</th>
                  <th class="sticky-head text-right">Cumulative (₹)</th>
                </tr>
              </thead>
              <tbody><tr><td colspan="7" style="text-align:center;color:var(--text-3);padding:16px;">Select a contribution to view payments.</td></tr></tbody>
            </table>
          </div>
          <div class="loading-overlay d-none" id="gcph-loading"><div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem"><span class="sr-only">Loading...</span></div></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

  <!-- ── Miqaat Payment History Modal ── -->
  <div class="modal fade" id="miqaat-history-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-history" style="margin-right:8px;color:var(--gold);"></i> Miqaat Invoice Payments</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0 position-relative">
          <div class="miqaat-summary-wrapper">
            <div class="miqaat-summary-grid">
              <div class="item"><span class="lbl">Invoice ID</span><span class="val" id="mq-invoice-id">-</span></div>
              <div class="item"><span class="lbl">Miqaat</span><span class="val" id="mq-miqaat-name">-</span></div>
              <div class="item"><span class="lbl">Type</span><span class="val" id="mq-miqaat-type">-</span></div>
              <div class="item"><span class="lbl">Amount</span><span class="val text-primary">₹<span id="mq-amount">0</span></span></div>
              <div class="item"><span class="lbl">Received</span><span class="val text-success">₹<span id="mq-received">0</span></span></div>
              <div class="item"><span class="lbl">Balance</span><span class="val text-danger">₹<span id="mq-balance">0</span></span></div>
              <div class="item"><span class="lbl">Status</span><span class="val"><span id="mq-status-badge" class="miqaat-status-badge miqaat-status-unpaid">UNPAID</span></span></div>
              <div class="item"><span class="lbl">Paid %</span><span class="val" id="mq-paid-pct">0%</span></div>
            </div>
            <div class="miqaat-progress-wrap"><div class="progress miqaat-progress"><div class="progress-bar" id="mq-progress-bar" style="width:0%"></div></div></div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" id="miqaat-history-table">
              <thead>
                <tr>
                  <th class="sticky-head">#</th><th class="sticky-head">Payment ID</th>
                  <th class="sticky-head text-right">Amount (₹)</th><th class="sticky-head">Date</th>
                  <th class="sticky-head">Method</th><th class="sticky-head">Remarks</th>
                  <th class="sticky-head text-right">Receipt</th><th class="sticky-head text-right">Cumulative (₹)</th>
                </tr>
              </thead>
              <tbody><tr><td colspan="8" style="text-align:center;color:var(--text-3);padding:16px;">Select an invoice.</td></tr></tbody>
            </table>
          </div>
          <div class="loading-overlay d-none" id="miqaat-loading"><div class="spinner-border" style="color:var(--gold);width:2rem;height:2rem" role="status"><span class="sr-only">Loading...</span></div></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

  <!-- ── Universal Details Modal ── -->
  <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="details-modal-title">Details</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body p-0" id="details-modal-body">
          <div style="text-align:center;color:var(--text-3);padding:40px;">Loading…</div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

  <!-- ── Assigned Thaali Dates Modal ── -->
  <div class="modal fade" id="assigned-thaali-days-container" tabindex="-1" aria-labelledby="assigned-thaali-days-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assigned-thaali-days-label">Assigned Thaali Dates</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p><strong>Member:</strong> <span id="assigned-user-name"><?php echo htmlspecialchars($member_name ?? '-', ENT_QUOTES); ?></span></p>
          <p><strong>FY:</strong> <span id="assigned-fy">-</span></p>
          <div id="assigned-dates-loading" style="color:var(--text-3);font-size:0.83rem;">Loading…</div>
          <div id="assigned-dates-empty" class="d-none" style="color:var(--text-3);font-size:0.83rem;">No dates assigned.</div>
          <ul id="assigned-dates-list" style="padding-left:18px;margin:0;"></ul>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

</div><!-- /.container -->

<!-- ALL JS IDENTICAL TO ORIGINAL -->
<script>
  function formatINRClient(num) {
    const n = parseFloat(num);
    if (isNaN(n)) return '0';
    let intPart = Math.round(n).toString();
    if (intPart.length > 3) {
      const last3 = intPart.slice(-3);
      const rest = intPart.slice(0, -3);
      intPart = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + last3;
    }
    return intPart;
  }

  (function() {
    function buildSectionModal(sectionEl) {
      const modalTitleEl = document.getElementById('details-modal-title');
      const bodyEl = document.getElementById('details-modal-body');
      if (!modalTitleEl || !bodyEl) return;
      let title = 'Details';
      const foundHeader = sectionEl.querySelector('.card-header h5, .card-header h4, h5, h4');
      if (foundHeader) title = foundHeader.textContent.trim();
      modalTitleEl.textContent = title;
      const clone = sectionEl.cloneNode(true);
      clone.classList.remove('d-none');
      clone.removeAttribute('id');
      clone.classList.add('mb-0');
      bodyEl.innerHTML = '';
      bodyEl.appendChild(clone);
    }
    document.querySelectorAll('.view-details-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const targetSel = this.getAttribute('data-target');
        if (!targetSel) return;
        const section = document.querySelector(targetSel);
        if (!section) return;
        buildSectionModal(section);
        $('#details-modal').modal('show');
      });
    });
    let modalZBase = 1060;
    $(document).on('show.bs.modal', '.modal', function() {
      const $this = $(this);
      const $openModals = $('.modal.show').not($this);
      $openModals.addClass('dimmed-underlay');
      const newZ = modalZBase + ($openModals.length * 30);
      $this.css('z-index', newZ).removeClass('dimmed-underlay');
      setTimeout(() => {
        const $backs = $('.modal-backdrop');
        if ($backs.length > 1) { $backs.not(':last').remove(); $backs.last().css('z-index', newZ - 10); }
        else { $backs.last().css('z-index', newZ - 10); }
      }, 50);
    });
    $(document).on('hidden.bs.modal', '.modal', function() {
      const $visible = $('.modal.show');
      $visible.removeClass('dimmed-underlay');
      let base = 1060, i = 0;
      $('.modal.show').each(function() { $(this).css('z-index', base + (i * 30)); i++; });
      if ($('.modal.show').length && $('.modal-backdrop').length === 0) {
        $('<div class="modal-backdrop fade show"></div>').appendTo(document.body).css('z-index', base - 10);
      } else if (!$('.modal.show').length) { $('.modal-backdrop').remove(); }
    });
    let detailsExplicitlyClosed = false;
    $('#details-modal').on('shown.bs.modal', function() { if (!detailsExplicitlyClosed) $(this).data('keep-open', true); });
    $('#details-modal').on('hide.bs.modal', function() { detailsExplicitlyClosed = true; $(this).data('keep-open', false); });
    $(document).on('show.bs.modal', '#gc-payments-modal, #miqaat-history-modal, #description-modal', function() {
      const $details = $('#details-modal');
      if ($details.hasClass('show')) { detailsExplicitlyClosed = false; $details.data('keep-open', true); }
    });
  })();

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");
    $.ajax({ url:"<?php echo base_url('common/generate_pdf'); ?>", type:"POST", data:{id:paymentId,for:1}, xhrFields:{responseType:'blob'},
      success:function(response){ var url=window.URL.createObjectURL(new Blob([response],{type:"application/pdf"})); window.open(url,"_blank"); },
      error:function(){ alert("Failed to generate invoice PDF"); }
    });
  });

  (function() {
    const cache = {};
    function pad2(n){ return String(n).padStart(2,'0'); }
    function fmtGregDateDMY(d){
      try{ const dt=new Date(String(d)+'T00:00:00'); if(isNaN(dt.getTime())) return String(d); return pad2(dt.getDate())+'-'+pad2(dt.getMonth()+1)+'-'+dt.getFullYear(); }
      catch(e){ return String(d); }
    }
    function renderDatesList(dates){
      const $list=$('#assigned-dates-list'); $list.empty();
      if(!dates||!dates.length){ $('#assigned-dates-empty').removeClass('d-none'); return; }
      $('#assigned-dates-empty').addClass('d-none');
      dates.forEach(function(item){ $list.append($('<li></li>').text(item&&item.greg_date?fmtGregDateDMY(item.greg_date):'-')); });
    }
    $(document).on('click','.view-assigned-thaali-days',function(e){
      e.preventDefault();
      const year=String($(this).data('year')||'').trim();
      if(!year) return;
      $('#assigned-fy').text(year);
      $('#assigned-dates-loading').removeClass('d-none').text('Loading...');
      $('#assigned-dates-empty').addClass('d-none');
      $('#assigned-dates-list').empty();
      $('#assigned-thaali-days-container').modal('show');
      if(cache[year]){ $('#assigned-dates-loading').addClass('d-none'); renderDatesList(cache[year]); return; }
      $.ajax({ url:'<?php echo base_url('accounts/getfmbassignedthaalidates'); ?>', type:'POST', dataType:'json', data:{year:year},
        success:function(res){
          $('#assigned-dates-loading').addClass('d-none');
          if(!res||res.success===false){ $('#assigned-dates-empty').removeClass('d-none').text((res&&res.message)?String(res.message):'Failed to load.'); return; }
          const dates=(res&&res.dates)?res.dates:[];
          cache[year]=dates; renderDatesList(dates);
        },
        error:function(){ $('#assigned-dates-loading').addClass('d-none'); $('#assigned-dates-empty').removeClass('d-none').text('Failed to load.'); }
      });
    });
  })();

  $(".view-description").on("click",function(e){
    e.preventDefault();
    $("#modal-view-description").text($(this).data("description")||"No description found!");
  });

  $(document).on('click','.view-gc-payments',function(e){
    e.preventDefault();
    const invoiceId=$(this).data('fmbgc-id');
    if(!invoiceId) return;
    $('#gcph-loading').removeClass('d-none');
    $('#gc-payments-table tbody').html('<tr><td colspan="7" style="text-align:center;color:var(--text-3);padding:16px;">Loading...</td></tr>');
    $('#gcph-invoice-id').text(invoiceId);
    $('#gcph-year,#gcph-type').text('-');
    $('#gcph-amount,#gcph-received,#gcph-balance').text('0');
    $('#gcph-status-badge').removeClass('gcph-status-paid gcph-status-partial gcph-status-unpaid').addClass('gcph-status-unpaid').text('UNPAID');
    $('#gcph-paid-pct').text('0%'); $('#gcph-progress-bar').css('width','0%');
    $.ajax({ url:'<?php echo base_url('accounts/gc_payment_history'); ?>', type:'POST', dataType:'json', data:{fmbgc_id:invoiceId},
      success:function(res){
        if(!res||!res.success){ $('#gc-payments-table tbody').html('<tr><td colspan="7" style="text-align:center;color:var(--red);padding:16px;">'+(res&&res.message?res.message:'Failed to load')+'</td></tr>'); $('#gc-payments-modal').modal('show'); return; }
        const inv=res.invoice||{};
        const totalAmt=parseFloat(inv.amount||0)||0;
        const totalReceived=parseFloat(res.total_received||0)||0;
        const balance=parseFloat(res.balance_due||(totalAmt-totalReceived))||0;
        $('#gcph-year').text(inv.contri_year||'-'); $('#gcph-type').text(inv.contri_type||'-');
        $('#gcph-amount').text(formatINRClient(totalAmt)); $('#gcph-received').text(formatINRClient(totalReceived)); $('#gcph-balance').text(formatINRClient(balance));
        let statusClass='gcph-status-unpaid',statusText='UNPAID';
        if(totalReceived>0&&balance>0){statusClass='gcph-status-partial';statusText='PARTIAL';}
        if(balance<=0&&totalAmt>0){statusClass='gcph-status-paid';statusText='PAID';}
        $('#gcph-status-badge').removeClass('gcph-status-paid gcph-status-partial gcph-status-unpaid').addClass(statusClass).text(statusText);
        const pct=totalAmt>0?(totalReceived/totalAmt)*100:0;
        $('#gcph-paid-pct').text(pct.toFixed(1)+'%'); $('#gcph-progress-bar').css('width',pct.toFixed(2)+'%');
        const pays=res.payments||[];
        if(!pays.length){ $('#gc-payments-table tbody').html('<tr><td colspan="7" style="text-align:center;color:var(--text-3);padding:16px;">No payments recorded.</td></tr>'); }
        else {
          let rows='',cumulative=0;
          function fmtDate(d){ if(!d) return '-'; var dt=new Date(d); if(isNaN(dt.getTime())){ var parts=(d+'').replace('T',' ').split(/[ :\-]/); if(parts.length>=3) dt=new Date(parts[0],parseInt(parts[1],10)-1,parts[2]); } if(isNaN(dt.getTime())) return '-'; var day=('0'+dt.getDate()).slice(-2); var mths=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; return day+'-'+mths[dt.getMonth()]+'-'+dt.getFullYear(); }
          pays.forEach(function(p,i){ const amt=parseFloat(p.amount||0)||0; cumulative+=amt; const pid=p.id||p.payment_id||''; const remarksSafe=(p.remarks?$('<div/>').text(p.remarks).html():'-'); const remarksDisplay=remarksSafe.length>60?remarksSafe.substring(0,57)+'…':remarksSafe; const methodDisp=(p.payment_method||'-');
            rows+='<tr><td data-label="#">'+(i+1)+'</td><td data-label="Date">'+fmtDate(p.payment_date)+'</td><td data-label="Amount" class="text-right text-success">'+formatINRClient(amt)+'</td><td data-label="Method">'+methodDisp+'</td><td data-label="Remarks" title="'+remarksSafe+'">'+remarksDisplay+'</td><td data-label="Receipt" class="text-right"><button class="btn btn-outline-primary gc-view-receipt btn-shrink btn-sm" data-payment-id="'+pid+'" title="View Receipt"><i class="fa fa-file-pdf-o"></i></button></td><td data-label="Cumulative" class="text-right">'+formatINRClient(cumulative)+'</td></tr>';
          });
          $('#gc-payments-table tbody').html(rows);
        }
        $('#gc-payments-modal').modal('show');
      },
      error:function(){ $('#gc-payments-table tbody').html('<tr><td colspan="7" style="text-align:center;color:var(--red);padding:16px;">Error loading data.</td></tr>'); $('#gc-payments-modal').modal('show'); },
      complete:function(){ $('#gcph-loading').addClass('d-none'); }
    });
  });

  $(document).on('click','.gc-view-receipt',function(e){
    e.preventDefault();
    const pid=$(this).data('payment-id'); if(!pid) return;
    const btn=$(this); btn.prop('disabled',true);
    $.ajax({ url:'<?php echo base_url('common/generate_pdf'); ?>', type:'POST', data:{id:pid,for:3}, xhrFields:{responseType:'blob'},
      success:function(blob){ var pdfUrl=URL.createObjectURL(new Blob([blob],{type:'application/pdf'})); window.open(pdfUrl,'_blank'); setTimeout(()=>URL.revokeObjectURL(pdfUrl),60000); },
      error:function(){ alert('Failed to load receipt PDF'); },
      complete:function(){ btn.prop('disabled',false); }
    });
  });

  $(document).on('click','.view-miqaat-history',function(e){
    e.preventDefault();
    const invoiceId=$(this).data('invoice-id'); if(!invoiceId) return;
    $('#miqaat-history-modal').modal('show');
    $('#miqaat-loading').removeClass('d-none');
    $('#miqaat-history-table tbody').html('<tr><td colspan="8" style="text-align:center;color:var(--text-3);padding:16px;">Loading...</td></tr>');
    $('#mq-invoice-id').text(invoiceId); $('#mq-miqaat-name,#mq-miqaat-type').text('-');
    $('#mq-amount,#mq-received,#mq-balance').text('0');
    $('#mq-status-badge').removeClass('miqaat-status-paid miqaat-status-partial miqaat-status-unpaid').addClass('miqaat-status-unpaid').text('UNPAID');
    $('#mq-paid-pct').text('0%'); $('#mq-progress-bar').css('width','0%');
    $.ajax({ url:'<?php echo base_url('accounts/miqaat_invoice_history'); ?>', type:'POST', dataType:'json', data:{invoice_id:invoiceId},
      success:function(res){
        if(!res||!res.success){ $('#miqaat-history-table tbody').html('<tr><td colspan="8" style="text-align:center;color:var(--red);padding:16px;">'+(res&&res.message?res.message:'Failed')+'</td></tr>'); return; }
        const inv=res.invoice||{};
        const totalAmt=parseFloat(inv.amount||0)||0;
        const totalReceived=parseFloat(inv.paid_amount||res.total_received||0)||0;
        const balance=parseFloat(inv.due_amount||(totalAmt-totalReceived))||0;
        $('#mq-miqaat-name').text(inv.miqaat_name||'-'); $('#mq-miqaat-type').text(inv.miqaat_type||'-');
        $('#mq-amount').text(formatINRClient(totalAmt)); $('#mq-received').text(formatINRClient(totalReceived)); $('#mq-balance').text(formatINRClient(balance));
        let statusClass='miqaat-status-unpaid',statusText='UNPAID';
        if(totalReceived>0&&balance>0){statusClass='miqaat-status-partial';statusText='PARTIAL';}
        if(balance<=0&&totalAmt>0){statusClass='miqaat-status-paid';statusText='PAID';}
        $('#mq-status-badge').removeClass('miqaat-status-paid miqaat-status-partial miqaat-status-unpaid').addClass(statusClass).text(statusText);
        const pct=totalAmt>0?(totalReceived/totalAmt)*100:0;
        $('#mq-paid-pct').text(pct.toFixed(1)+'%'); $('#mq-progress-bar').css('width',pct.toFixed(2)+'%');
        const pays=res.payments||[];
        if(!pays.length){ $('#miqaat-history-table tbody').html('<tr><td colspan="8" style="text-align:center;color:var(--text-3);padding:16px;">No payments recorded.</td></tr>'); return; }
        function fmtDate(d){ if(!d) return '-'; var dt=new Date(d); if(isNaN(dt.getTime())){ var parts=(d+'').replace('T',' ').split(/[ :\-]/); if(parts.length>=3) dt=new Date(parts[0],parseInt(parts[1],10)-1,parts[2]); } if(isNaN(dt.getTime())) return '-'; var day=('0'+dt.getDate()).slice(-2); var mths=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; return day+'-'+mths[dt.getMonth()]+'-'+dt.getFullYear(); }
        let rows='',cumulative=0;
        pays.forEach(function(p,i){ const amt=parseFloat(p.amount||0)||0; cumulative+=amt; const pid=p.payment_id||p.id||''; const remarksSafe=(p.remarks?$('<div/>').text(p.remarks).html():'-'); const remarksDisplay=remarksSafe.length>60?remarksSafe.substring(0,57)+'…':remarksSafe; const methodDisp=(p.payment_method||'-');
          rows+='<tr><td data-label="#">'+(i+1)+'</td><td data-label="Payment ID">'+pid+'</td><td data-label="Amount" class="text-right text-success">'+formatINRClient(amt)+'</td><td data-label="Date">'+fmtDate(p.payment_date)+'</td><td data-label="Method">'+methodDisp+'</td><td data-label="Remarks" title="'+remarksSafe+'">'+remarksDisplay+'</td><td data-label="Receipt" class="text-right"><button class="btn btn-outline-primary miqaat-view-receipt btn-shrink btn-sm" data-payment-id="'+pid+'" title="View Receipt"><i class="fa fa-file-pdf-o"></i></button></td><td data-label="Cumulative" class="text-right">'+formatINRClient(cumulative)+'</td></tr>';
        });
        $('#miqaat-history-table tbody').html(rows);
      },
      error:function(){ $('#miqaat-history-table tbody').html('<tr><td colspan="8" style="text-align:center;color:var(--red);padding:16px;">Server error.</td></tr>'); },
      complete:function(){ $('#miqaat-loading').addClass('d-none'); }
    });
  });

  $(document).on('click','.miqaat-view-receipt',function(e){
    e.preventDefault();
    const pid=$(this).data('payment-id'); if(!pid) return;
    const btn=$(this); btn.prop('disabled',true);
    $.ajax({ url:'<?php echo base_url('common/generate_pdf'); ?>', type:'POST', data:{id:pid,for:2}, xhrFields:{responseType:'blob'},
      success:function(blob){ if(!(blob instanceof Blob)){alert('Unexpected response');return;} var pdfUrl=URL.createObjectURL(new Blob([blob],{type:'application/pdf'})); window.open(pdfUrl,'_blank'); setTimeout(()=>URL.revokeObjectURL(pdfUrl),60000); },
      error:function(){ alert('Failed to load receipt PDF'); },
      complete:function(){ btn.prop('disabled',false).removeClass('disabled'); }
    });
  });
</script>