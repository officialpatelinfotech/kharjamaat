<?php
// ==============================================================
// Aggregations (done once at top, BEFORE output)
// ==============================================================
$gc_total_amount = 0;
$gc_total_paid = 0;
$gc_invoice_count = 0;
$gc_fully_paid = 0;
$gc_partial = 0;
$gc_unpaid = 0;
if (!empty($fmb_takhmeen_details['general_contributions'])) {
  foreach ($fmb_takhmeen_details['general_contributions'] as $gcRow) {
    $gc_invoice_count++;
    $amt  = (float) ($gcRow['amount'] ?? 0);
    $paid = (float) ($gcRow['amount_paid'] ?? 0);
    $due  = max(0, $amt - $paid);
    $gc_total_amount += $amt;
    $gc_total_paid   += min($paid, $amt);
    if ($due <= 0.00001) {
      $gc_fully_paid++;
    } elseif ($paid > 0) {
      $gc_partial++;
    } else {
      $gc_unpaid++;
    }
  }
}
$gc_total_due = max(0, $gc_total_amount - $gc_total_paid);

$miqaat_total_amount = 0;
$miqaat_total_paid = 0;
$miqaat_invoice_count = 0;
$miqaat_paid_count = 0;
$miqaat_partial = 0;
$miqaat_unpaid = 0;
if (!empty($miqaat_invoices)) {
  foreach ($miqaat_invoices as $mv) {
    $miqaat_invoice_count++;
    $amt  = (float) ($mv['amount'] ?? 0);
    $paid = (float) ($mv['paid_amount'] ?? 0);
    $due  = max(0, $amt - $paid);
    $miqaat_total_amount += $amt;
    $miqaat_total_paid   += min($paid, $amt);
    if ($due <= 0.00001) {
      $miqaat_paid_count++;
    } elseif ($paid > 0) {
      $miqaat_partial++;
    } else {
      $miqaat_unpaid++;
    }
  }
}
$miqaat_total_due = max(0, $miqaat_total_amount - $miqaat_total_paid);

$fmb_overall_amount = (float) ($fmb_takhmeen_details['overall']['total_amount'] ?? 0);
$fmb_overall_paid   = (float) ($fmb_takhmeen_details['overall']['total_paid'] ?? 0);
$fmb_overall_due    = (float) ($fmb_takhmeen_details['overall']['total_due'] ?? max(0, $fmb_overall_amount - $fmb_overall_paid));
$fmb_takhmeen_count = !empty($fmb_takhmeen_details['all_takhmeen']) ? count($fmb_takhmeen_details['all_takhmeen']) : 0;
$currentYearLabel   = $fmb_takhmeen_details['current_year']['year'] ?? ($fmb_takhmeen_details['latest']['year'] ?? '—');

$takh_pct = $fmb_overall_amount > 0 ? ($fmb_overall_paid / $fmb_overall_amount) * 100 : 0;
$gc_pct   = $gc_total_amount > 0 ? ($gc_total_paid / $gc_total_amount) * 100 : 0;
$miq_pct  = $miqaat_total_amount > 0 ? ($miqaat_total_paid / $miqaat_total_amount) * 100 : 0;
?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<div class="container margintopcontainer pt-5">
  <div class="mb-4">
    <a href="<?php echo base_url('accounts'); ?>" class="btn-view"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
  </div>
  <h1 class="text-center page-heading mb-4">FMB Details Overview</h1>
  <!-- Toggle buttons relocated inside cards as View Details -->
  <style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --amber:       #b45309;
    --amber-bg:    #fffbeb;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:      0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page heading ── */
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.6rem; font-weight: 600; letter-spacing: -.3px; }

  /* ── Dashboard cards ── */
  .dash-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: box-shadow .2s;
  }
  .dash-card:hover { box-shadow: var(--shadow-lg); }

  .dash-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px 12px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
  }
  .dash-card-header .card-title {
    font-size: 0.82rem; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: var(--text-2);
    display: flex; align-items: center; gap: 8px;
    margin: 0;
  }
  .dash-card-header .card-title i {
    width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.75rem; background: var(--gold-muted); color: var(--gold);
  }
  .dash-card-header .badge-pill {
    font-size: 0.65rem; font-weight: 700; padding: 3px 10px;
    border-radius: 40px; letter-spacing: .4px;
  }
  .badge-danger  { background: var(--red-bg);   color: var(--red);   border: 1px solid #fecaca; }
  .badge-success { background: var(--green-bg); color: var(--green); border: 1px solid #bbf7d0; }

  .dash-card-body { padding: 18px 20px; flex: 1; display: flex; flex-direction: column; }

  /* ── Stat tiles ── */
  .stat-tile {
    background: var(--surface-2); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 14px 12px;
    text-align: center; transition: background .15s;
    width: 100%;
  }
  .stat-tile:hover { background: var(--gold-muted); }
  .stat-tile .tile-label { font-size: 0.68rem; font-weight: 600; color: var(--text-3); letter-spacing: .5px; text-transform: uppercase; margin-bottom: 6px; }
  .stat-tile .tile-value { font-size: 1.3rem; font-weight: 800; line-height: 1; }
  .tile-value.green  { color: var(--green); }
  .tile-value.red    { color: var(--red); }
  .tile-value.blue   { color: var(--blue); }
  .tile-value.amber  { color: var(--amber); }
  .tile-value.gold   { color: var(--gold); }

  /* ── Amount display ── */
  .amount-big { font-size: 1.6rem; font-weight: 800; letter-spacing: -1px; }

  /* ── View details btn ── */
  .btn-view {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; padding: 7px 16px;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    color: var(--text-2); background: var(--surface); text-decoration: none;
    transition: all .15s;
    cursor: pointer;
  }
  .btn-view:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

  /* ── Breakdown row ── */
  .breakdown-row { display: flex; gap: 10px; margin-top: 12px; width: 100%; }
  .breakdown-row .stat-tile { flex: 1; }

  /* ── Modal stacking & general styles ── */
  #details-modal.modal { z-index: 1060; }
  .modal-backdrop { z-index: 1050; }
  .modal.dimmed-underlay { filter: blur(2px) brightness(.9); }
  .modal.dimmed-underlay .modal-content { pointer-events: none; }

  .modal-content {
    background: var(--surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-lg);
  }
  .modal-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 16px 24px;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
  }
  .modal-footer {
    background: var(--surface-2);
    border-top: 1px solid var(--border);
    padding: 12px 24px;
  }
  .modal-header .close {
    color: var(--text-2);
    text-shadow: none;
    opacity: 0.7;
  }
  .modal-header .close:hover {
    opacity: 1;
    color: var(--gold);
  }

  /* ── Premium Tables styling ── */
  .table {
    font-size: 0.8rem;
    border-collapse: collapse;
    background: var(--surface);
  }
  .table thead th {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-3);
    padding: 12px 16px;
    border-bottom: 2px solid var(--border) !important;
    background: var(--surface-2);
    border-top: none;
  }
  .table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
  }
  .table tbody tr:hover {
    background: var(--gold-muted) !important;
    transition: background 0.15s;
  }
  .table tr.table-warning {
    background-color: var(--gold-muted) !important;
    font-weight: 700;
    color: var(--gold) !important;
  }

  /* ── GC payments modal ── */
  #gc-payments-modal .gcph-summary-wrapper {
    padding: 1rem 1rem .85rem;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }
  #gc-payments-modal .gcph-summary-grid {
    display: flex; flex-wrap: wrap; gap: 1.25rem 2.5rem;
    font-size: .78rem; line-height: 1.05rem;
  }
  #gc-payments-modal .gcph-summary-grid .item .lbl {
    text-transform: uppercase; font-size: .6rem; letter-spacing: .5px;
    color: var(--text-3); font-weight: 600; display: block;
  }
  #gc-payments-modal .gcph-summary-grid .item .val { font-weight: 600; font-size: .85rem; }
  #gc-payments-modal .gcph-status-badge {
    display: inline-block; padding: .25rem .55rem; border-radius: 30px;
    font-size: .62rem; font-weight: 600; letter-spacing: .5px; text-transform: uppercase;
  }
  #gc-payments-modal .gcph-status-paid { background: var(--green); color: #fff; }
  #gc-payments-modal .gcph-status-partial { background: var(--amber); color: #fff; }
  #gc-payments-modal .gcph-status-unpaid { background: var(--red); color: #fff; }
  #gc-payments-modal .gcph-progress-wrap { margin-top: .6rem; }
  #gc-payments-modal .progress.gcph-progress { height: 8px; background: var(--border); }
  #gc-payments-modal .progress.gcph-progress .progress-bar { background: linear-gradient(90deg, var(--green), var(--gold-light)); }
  #gc-payments-modal .table thead th.sticky-head { position: sticky; top: 0; background: var(--surface-2); color: var(--text-1); z-index: 2; }
  #gc-payments-modal .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,.75); display: flex; align-items: center; justify-content: center; z-index: 15; }
  #gc-payments-modal .loading-overlay.d-none { display: none !important; }
  #gc-payments-modal .btn.gc-view-receipt.btn-shrink { padding: .25rem .45rem; font-size: .55rem; line-height: 1; }
  #gc-payments-modal .btn.gc-view-receipt.btn-shrink i { font-size: .7rem; }

  /* ── Miqaat history modal ── */
  #miqaat-history-modal .miqaat-summary-wrapper {
    padding: 1rem 1rem .85rem;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }
  #miqaat-history-modal .miqaat-summary-grid {
    display: flex; flex-wrap: wrap; gap: 1.25rem 2.5rem;
    font-size: .78rem; line-height: 1.05rem;
  }
  #miqaat-history-modal .miqaat-summary-grid .item .lbl {
    text-transform: uppercase; font-size: .6rem; letter-spacing: .5px;
    color: var(--text-3); font-weight: 600; display: block;
  }
  #miqaat-history-modal .miqaat-summary-grid .item .val { font-weight: 600; font-size: .85rem; }
  #miqaat-history-modal .miqaat-status-badge {
    display: inline-block; padding: .25rem .55rem; border-radius: 30px;
    font-size: .62rem; font-weight: 600; letter-spacing: .5px; text-transform: uppercase;
  }
  #miqaat-history-modal .miqaat-status-paid { background: var(--blue); color: #fff; }
  #miqaat-history-modal .miqaat-status-partial { background: var(--amber); color: #fff; }
  #miqaat-history-modal .miqaat-status-unpaid { background: var(--red); color: #fff; }
  #miqaat-history-modal .miqaat-progress-wrap { margin-top: .6rem; }
  #miqaat-history-modal .progress.miqaat-progress { height: 8px; background: var(--border); }
  #miqaat-history-modal .progress.miqaat-progress .progress-bar { background: linear-gradient(90deg, var(--blue), var(--gold-light)); }
  #miqaat-history-modal .table thead th.sticky-head { position: sticky; top: 0; background: var(--surface-2); color: var(--text-1); z-index: 2; }
  #miqaat-history-modal .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,.75); display: flex; align-items: center; justify-content: center; z-index: 15; }
  #miqaat-history-modal .loading-overlay.d-none { display: none !important; }
  #miqaat-history-modal .btn.miqaat-view-receipt.btn-shrink { padding: .25rem .45rem; font-size: .55rem; line-height: 1; }
  #miqaat-history-modal .btn.miqaat-view-receipt.btn-shrink i { font-size: .7rem; }

  /* ── GC & Miqaat card lists ── */
  #gc-card-list, #miqaat-card-list { display: block; }
  .gc-card, .miq-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: .85rem .9rem;
    margin-bottom: .9rem;
    position: relative;
    box-shadow: var(--shadow-sm);
  }
  .miq-card { display: flex; flex-direction: column; }

  .gc-card-header, .miq-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; gap: .5rem; }
  .gc-badge, .miq-badge { font-size: .62rem; padding: .25rem .45rem; border-radius: 4px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase; }
  .gc-badge.paid, .miq-badge.paid { background: var(--green-bg); color: var(--green); border: 1px solid #bbf7d0; }
  .gc-badge.partial, .miq-badge.partial { background: var(--amber-bg); color: var(--amber); border: 1px solid #fde68a; }
  .gc-badge.unpaid, .miq-badge.unpaid { background: var(--red-bg); color: var(--red); border: 1px solid #fecaca; }

  .gc-meta, .miq-meta { font-size: .64rem; color: var(--text-3); display: flex; flex-wrap: wrap; gap: .55rem .9rem; margin-bottom: .4rem; }
  .gc-meta span, .miq-meta span { white-space: nowrap; }

  .gc-amounts, .miq-amounts { display: flex; gap: 1.25rem; flex-wrap: wrap; margin-bottom: .45rem; }
  .gc-amounts .amt, .miq-amounts .amt { flex: 1 1 90px; }
  .gc-amounts .lbl, .miq-amounts .lbl { font-size: .58rem; text-transform: uppercase; letter-spacing: .5px; color: var(--text-3); font-weight: 600; }
  .gc-amounts .val, .miq-amounts .val { font-size: .9rem; font-weight: 600; }

  .gc-inline-divider, .miq-inline-divider { height: 1px; background: linear-gradient(90deg, var(--border), rgba(0, 0, 0, 0)); margin: .4rem 0 .55rem; }
  .gc-actions, .miq-actions { display: flex; gap: .5rem; margin-top: .3rem; }
  .gc-actions .btn, .miq-actions .btn { padding: .4rem .6rem; font-size: .7rem; }
  .gc-history-btn-wrapper, .miq-history-btn-wrapper { margin-top: .55rem; display: flex; justify-content: flex-end; }
  .gc-history-btn-wrapper .btn, .miq-history-btn-wrapper .btn { font-size: .7rem; padding: .45rem .9rem; }

  @media (max-width:575.98px) {
    #gc-payments-modal .modal-dialog, #miqaat-history-modal .modal-dialog { margin: .6rem auto; max-width: 100%; }
    #gc-payments-modal .table thead, #miqaat-history-modal .table thead { display: none; }
    #gc-payments-modal .table tbody tr, #miqaat-history-modal .table tbody tr {
      display: block; background: var(--surface); margin: 0 0 .75rem; border: 1px solid var(--border); border-radius: 8px; box-shadow: var(--shadow-sm); padding: .5rem .65rem;
    }
    #gc-payments-modal .table tbody tr:last-child, #miqaat-history-modal .table tbody tr:last-child { margin-bottom: 0; }
    #gc-payments-modal .table tbody td, #miqaat-history-modal .table tbody td { display: flex; padding: .35rem .25rem; border: none !important; font-size: .76rem; line-height: 1.1rem; }
    #gc-payments-modal .table tbody td[data-label], #miqaat-history-modal .table tbody td[data-label] { justify-content: space-between; gap: 8px; }
    #gc-payments-modal .table tbody td[data-label]::before, #miqaat-history-modal .table tbody td[data-label]::before {
      content: attr(data-label); font-weight: 600; color: var(--text-2); text-transform: uppercase; letter-spacing: .5px; font-size: .63rem;
    }
    #gc-payments-modal .table tbody td:last-child, #miqaat-history-modal .table tbody td:last-child { justify-content: flex-end; }
    #gc-payments-modal .table tbody td .btn, #miqaat-history-modal .table tbody td .btn { padding: .3rem .45rem; font-size: .65rem; }
    #gc-payments-modal .table-sm, #miqaat-history-modal .table-sm { font-size: inherit; }
    #gc-payments-modal .modal-header, #gc-payments-modal .modal-footer, #miqaat-history-modal .modal-header, #miqaat-history-modal .modal-footer { padding: .5rem .75rem; }

    .gc-history-btn-wrapper, .miq-history-btn-wrapper { justify-content: stretch; }
    .gc-history-btn-wrapper .btn, .miq-history-btn-wrapper .btn { width: 100%; padding: .5rem .75rem; }
    .gc-amounts, .miq-amounts { gap: .75rem; }
    .gc-amounts .amt, .miq-amounts .amt { flex: 1 1 70px; }
    .gc-amounts .val, .miq-amounts .val { font-size: .85rem; }
  }

  /* ── Desktop & Tablet grid layouts ── */
  @media (min-width: 992px) {
    #gc-card-list, #miqaat-card-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem 1.15rem; padding: 1rem 1rem 1.2rem; }
    #gc-card-list .gc-card, #miqaat-card-list .miq-card { margin-bottom: 0; display: flex; flex-direction: column; height: 100%; }
    #gc-card-list .gc-history-btn-wrapper, #miqaat-card-list .miq-history-btn-wrapper { margin-top: auto; }
  }
  @media (min-width: 768px) and (max-width: 991.98px) {
    #gc-card-list, #miqaat-card-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: .9rem 1rem; padding: .75rem .75rem 1rem; }
    #gc-card-list .gc-card, #miqaat-card-list .miq-card { margin-bottom: 0; }
  }

  /* Striped table row background customization */
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: var(--surface-2) !important;
  }
  .table-striped tbody tr:nth-of-type(even) {
    background-color: var(--surface) !important;
  }

  /* ── Detail sections cards inside modals ── */
  .detail-section.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: none;
    overflow: hidden;
  }
  .detail-section.card .card-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 12px 20px;
    display: flex;
    align-items: center;
  }
  .detail-section.card .card-header h5.card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-2);
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .detail-section.card .card-header h5.card-title i {
    width: 26px; height: 26px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.75rem; background: var(--gold-muted); color: var(--gold);
  }
</style>
  <div class="row g-3 align-items-stretch mb-4">
    <!-- Card 1: Thaali Takhmeen -->
    <div class="col-12 col-md-4 d-flex">
      <div class="dash-card w-100 h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-cutlery"></i> Thaali Takhmeen</span>
          <?php if ($fmb_overall_due > 0): ?>
            <span class="badge-pill badge-danger">Pending</span>
          <?php else: ?>
            <span class="badge-pill badge-success">Clear</span>
          <?php endif; ?>
        </div>
        <div class="dash-card-body">
          <div class="stat-tile mb-3">
            <div class="tile-label">Total Due</div>
            <div class="tile-value red amount-big">₹<?php echo format_inr_no_decimals($fmb_overall_due); ?></div>
          </div>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Amount</div>
              <div class="tile-value blue">₹<?php echo format_inr_no_decimals($fmb_overall_amount); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Paid</div>
              <div class="tile-value green">₹<?php echo format_inr_no_decimals($fmb_overall_paid); ?></div>
            </div>
          </div>
          <div class="progress mb-2" aria-label="Takhmeen Paid Progress" style="height:6px; background:var(--border); border-radius:4px; overflow:hidden;">
            <div class="progress-bar" style="width:<?php echo number_format($takh_pct, 2); ?>%; background:linear-gradient(90deg, var(--blue), var(--gold-light)); height:100%;"></div>
          </div>
          <div style="font-size:0.65rem; color:var(--text-3); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;" class="mb-3">
            <span>Years: <?php echo $fmb_takhmeen_count; ?></span> | <span><?php echo number_format($takh_pct, 1); ?>% Paid</span>
          </div>
          <div class="mt-auto text-end">
            <button type="button" class="btn-view view-details-btn" data-target="#sec-takhmeen"><i class="fa fa-arrow-right"></i> View Details</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Miqaat Niyaz Invoices -->
    <div class="col-12 col-md-4 d-flex">
      <div class="dash-card w-100 h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-calendar"></i> Miqaat Niyaz Invoices</span>
          <?php if ($miqaat_total_due > 0): ?>
            <span class="badge-pill badge-danger">Pending</span>
          <?php else: ?>
            <span class="badge-pill badge-success">Clear</span>
          <?php endif; ?>
        </div>
        <div class="dash-card-body">
          <div class="stat-tile mb-3">
            <div class="tile-label">Total Due</div>
            <div class="tile-value red amount-big">₹<?php echo format_inr_no_decimals($miqaat_total_due); ?></div>
          </div>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Amount</div>
              <div class="tile-value blue">₹<?php echo format_inr_no_decimals($miqaat_total_amount); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Paid</div>
              <div class="tile-value green">₹<?php echo format_inr_no_decimals($miqaat_total_paid); ?></div>
            </div>
          </div>
          <div class="progress mb-2" aria-label="Miqaat Paid Progress" style="height:6px; background:var(--border); border-radius:4px; overflow:hidden;">
            <div class="progress-bar" style="width:<?php echo number_format($miq_pct, 2); ?>%; background:linear-gradient(90deg, var(--gold), var(--gold-light)); height:100%;"></div>
          </div>
          <div style="font-size:0.65rem; color:var(--text-3); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;" class="mb-3">
            <span>Invoices: <?php echo $miqaat_invoice_count; ?></span> | <span><?php echo number_format($miq_pct, 1); ?>% Paid</span>
          </div>
          <div class="mt-auto text-end">
            <a href="<?php echo base_url('accounts/miqaat_invoices'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: FMB Extra Contributions -->
    <div class="col-12 col-md-4 d-flex">
      <div class="dash-card w-100 h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-gift"></i> Extra Contributions</span>
          <?php if ($gc_total_due > 0): ?>
            <span class="badge-pill badge-danger">Pending</span>
          <?php else: ?>
            <span class="badge-pill badge-success">Clear</span>
          <?php endif; ?>
        </div>
        <div class="dash-card-body">
          <div class="stat-tile mb-3">
            <div class="tile-label">Total Due</div>
            <div class="tile-value red amount-big">₹<?php echo format_inr_no_decimals($gc_total_due); ?></div>
          </div>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Amount</div>
              <div class="tile-value blue">₹<?php echo format_inr_no_decimals($gc_total_amount); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Paid</div>
              <div class="tile-value green">₹<?php echo format_inr_no_decimals($gc_total_paid); ?></div>
            </div>
          </div>
          <div class="progress mb-2" aria-label="General Contributions Paid Progress" style="height:6px; background:var(--border); border-radius:4px; overflow:hidden;">
            <div class="progress-bar" style="width:<?php echo number_format($gc_pct, 2); ?>%; background:linear-gradient(90deg, var(--green), var(--gold-light)); height:100%;"></div>
          </div>
          <div style="font-size:0.65rem; color:var(--text-3); text-transform:uppercase; font-weight:600; letter-spacing:0.5px;" class="mb-3">
            <span>Invoices: <?php echo $gc_invoice_count; ?></span> | <span><?php echo number_format($gc_pct, 1); ?>% Paid</span>
          </div>
          <div class="mt-auto text-end">
            <button type="button" class="btn-view view-details-btn" data-target="#sec-gc"><i class="fa fa-arrow-right"></i> View Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div id="details-sections" class="mb-5" style="display:none !important;">
    <!-- TAKHMEEN SECTION -->
    <div id="sec-takhmeen" class="detail-section card shadow-sm mb-4 d-none">
      <div class="card-header text-center">
        <h5 class="mb-0 card-title"><i class="fa fa-cutlery"></i> FMB Takhmeen (All Years)</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead>
              <tr>
                <th>Year</th>
                <!-- <th class="text-end">Thaali Days</th> -->
                <th class="text-end">Thaali Days</th>
                <th class="text-end">Amount (₹)</th>
                <th class="text-end">Paid (₹)</th>
                <th class="text-end">Due (₹)</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Allocate overall payments to yearly takhmeen when per-year paid values are missing.
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
                  // Prepare year list sorted by starting Hijri year (oldest-first)
                  $years_sorted = $rows_input;
                  usort($years_sorted, function($a, $b){
                    $ay = isset($a['year']) ? preg_replace('/[^0-9\-]/','', $a['year']) : '';
                    $by = isset($b['year']) ? preg_replace('/[^0-9\-]/','', $b['year']) : '';
                    $aBase = (int)preg_replace('/^(\d{4}).*$/', '$1', $ay);
                    $bBase = (int)preg_replace('/^(\d{4}).*$/', '$1', $by);
                    return $aBase <=> $bBase;
                  });
                  $remaining = $overall_paid_sum;
                  foreach ($years_sorted as $yrRow) {
                    $yearLabel = $yrRow['year'] ?? '';
                    $amt = (float)($yrRow['total_amount'] ?? 0);
                    $pay = min(max($remaining, 0), $amt);
                    $allocated[$yearLabel] = $pay;
                    $remaining -= $pay;
                    if ($remaining <= 0) { break; }
                  }
                }
                foreach ($rows_input as $row) {
                  $rowYear = $row['year'] ?? '';
                  $basePaid = (float) ($row['total_paid'] ?? 0);
                  $paidVal = isset($allocated[$rowYear]) ? (float)$allocated[$rowYear] : $basePaid;
                  $amtVal  = (float) ($row['total_amount'] ?? 0);
                  $daysVal = $row['thaali_days'] ?? null;
                  $assignedDaysVal = isset($row['assigned_thaali_days']) ? (int)$row['assigned_thaali_days'] : 0;
                  $dueVal  = max(0, $amtVal - $paidVal);
                  $highlight = ($currentYearLabel && strpos($rowYear, (string)$currentYearLabel) !== false) ? 'table-warning fw-bold' : '';
                  echo '<tr class="' . $highlight . '">';
                  echo '<td>' . htmlspecialchars($rowYear) . '</td>';
                  // echo '<td class="text-end">' . (($daysVal === null || $daysVal === '') ? '-' : (int)$daysVal) . '</td>';
                  echo '<td class="text-end"><a href="#" class="view-assigned-thaali-days" data-year="' . htmlspecialchars($rowYear, ENT_QUOTES) . '">' . $assignedDaysVal . '</a></td>';
                  echo '<td class="text-end">₹' . format_inr_no_decimals($amtVal) . '</td>';
                  echo '<td class="text-end text-success">₹' . format_inr_no_decimals($paidVal) . '</td>';
                  echo '<td class="text-end text-danger">₹' . format_inr_no_decimals($dueVal) . '</td>';
                  echo '</tr>';
                }
              } else { ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">Takhmeen not found.</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="border-top mt-0">
          <div class="p-3 pb-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Overall Payment History</h6>
            <small class="text-muted">(Payments allocated oldest-year first for dues)</small>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0">
              <thead>
                <tr>
                  <th>Date</th>
                  <th class="text-end">Amount (₹)</th>
                  <th>Method</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($fmb_takhmeen_details['all_payments'])): ?>
                  <?php foreach ($fmb_takhmeen_details['all_payments'] as $pay): ?>
                    <tr>
                      <td><?php echo $pay['payment_date'] ? date('d-M-Y', strtotime($pay['payment_date'])) : '-'; ?></td>
                      <td class="text-end text-success"><?php echo format_inr_no_decimals((float)$pay['amount']); ?></td>
                      <td><?php echo htmlspecialchars($pay['payment_method'] ?? '-'); ?></td>
                      <td><?php echo htmlspecialchars($pay['remarks'] ?? '-'); ?></td>
                      <td><button class="view-invoice btn btn-sm btn-outline-primary" data-payment-id="<?php echo (int)$pay['id']; ?>" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted">No payments found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- GENERAL CONTRIBUTIONS SECTION (Cards Only) -->
    <div id="sec-gc" class="detail-section card shadow-sm d-none">
      <div class="card-header text-center">
        <h5 class="mb-0 card-title"><i class="fa fa-gift"></i> FMB General Contributions</h5>
      </div>
      <div class="card-body p-0">
        <div id="gc-card-list" class="p-2">
          <?php if (!empty($fmb_takhmeen_details['general_contributions'])): ?>
            <?php foreach ($fmb_takhmeen_details['general_contributions'] as $idx => $gc): ?>
              <?php
              $amount      = (float)$gc['amount'];
              $paid        = isset($gc['amount_paid']) ? (float)$gc['amount_paid'] : 0.0;
              $dueRaw      = isset($gc['total_due']) ? (float)$gc['total_due'] : max($amount - $paid, 0);
              $statusFlag  = (int)$gc['payment_status'];
              $due         = $dueRaw < 0.00001 ? 0 : $dueRaw;
              $badgeClass  = 'unpaid';
              $badgeText   = 'Unpaid';
              if ($paid > 0 && $due > 0) {
                $badgeClass = 'partial';
                $badgeText = 'Partial';
              }
              if ($statusFlag === 1 || $due === 0) {
                $badgeClass = 'paid';
                $badgeText = 'Paid';
              }
              ?>
              <div class="gc-card" data-gc-id="<?php echo (int)$gc['id']; ?>">
                <div class="gc-card-header">
                  <strong>#<?php echo $idx + 1; ?> • <?php echo htmlspecialchars($gc['contri_type']); ?></strong>
                  <span class="gc-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                </div>
                <div class="gc-meta">
                  <span>Year: <strong><?php echo htmlspecialchars($gc['contri_year']); ?></strong></span>
                  <span>Type: <strong><?php echo htmlspecialchars($gc['fmb_type']); ?></strong></span>
                  <span>Date: <strong><?php echo $gc['created_at'] ? date('d-M-Y', strtotime($gc['created_at'])) : '-'; ?></strong></span>
                </div>
                <div class="gc-amounts">
                  <div class="amt">
                    <div class="lbl">Amount</div>
                    <div class="val text-primary">₹<?php echo format_inr_no_decimals($amount); ?></div>
                  </div>
                  <div class="amt">
                    <div class="lbl">Paid</div>
                    <div class="val text-success">₹<?php echo format_inr_no_decimals($paid); ?></div>
                  </div>
                  <div class="amt">
                    <div class="lbl">Due</div>
                    <div class="val text-danger">₹<?php echo format_inr_no_decimals($due); ?></div>
                  </div>
                </div>
                <div class="gc-inline-divider"></div>
                <div class="gc-actions">
                  <?php if (!empty($gc['description'])): ?>
                    <button class="btn btn-outline-primary btn-sm view-description" data-description="<?php echo htmlspecialchars($gc['description']); ?>" data-toggle="modal" data-target="#description-modal" title="View Description"><i class="fa-solid fa-eye"></i></button>
                  <?php else: ?>
                    <span class="gc-desc-empty">No description</span>
                  <?php endif; ?>
                </div>
                <div class="gc-history-btn-wrapper">
                  <button class="btn btn-outline-secondary btn-sm view-gc-payments" data-fmbgc-id="<?php echo (int)$gc['id']; ?>" data-amount="<?php echo number_format($amount, 2, '.', ''); ?>" data-paid="<?php echo number_format($paid, 2, '.', ''); ?>" data-due="<?php echo number_format($due, 2, '.', ''); ?>" title="View Payment History">
                    <i class="fa-regular fa-money-bill-1 me-1"></i> Payment History
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center text-muted small py-3">No general contributions found.</div>
          <?php endif; ?>
        </div>
      </div>

      
    </div><!-- /#details-sections -->
  </div>

  <!-- DESCRIPTION MODAL -->
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle me-2"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modal-view-description" class="text-dark"></p>
        </div>
      </div>
    </div>
  </div>
  <!-- GC PAYMENTS HISTORY MODAL -->
  <div class="modal fade" id="gc-payments-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-center"><i class="fa fa-file-text-o me-2"></i> General Contribution Payments</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0 position-relative">
          <div class="gcph-summary-wrapper">
            <div class="d-flex justify-content-between flex-wrap align-items-start">
              <div class="gcph-summary-grid">
                <div class="item">
                  <span class="lbl">Invoice ID</span>
                  <span class="val" id="gcph-invoice-id">-</span>
                </div>
                <div class="item">
                  <span class="lbl">Year</span>
                  <span class="val" id="gcph-year">-</span>
                </div>
                <div class="item">
                  <span class="lbl">Type</span>
                  <span class="val" id="gcph-type">-</span>
                </div>
                <div class="item">
                  <span class="lbl">Amount</span>
                  <span class="val text-primary">₹<span id="gcph-amount">0.00</span></span>
                </div>
                <div class="item">
                  <span class="lbl">Received</span>
                  <span class="val text-success">₹<span id="gcph-received">0.00</span></span>
                </div>
                <div class="item">
                  <span class="lbl">Balance</span>
                  <span class="val text-danger">₹<span id="gcph-balance">0.00</span></span>
                </div>
                <div class="item">
                  <span class="lbl">Status</span>
                  <span class="val"><span id="gcph-status-badge" class="gcph-status-badge gcph-status-unpaid">UNPAID</span></span>
                </div>
                <div class="item">
                  <span class="lbl">Paid %</span>
                  <span class="val" id="gcph-paid-pct">0%</span>
                </div>
              </div>
            </div>
            <div class="gcph-progress-wrap">
              <div class="progress gcph-progress" aria-label="Paid Progress">
                <div class="progress-bar" id="gcph-progress-bar" style="width:0%"></div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" id="gc-payments-table">
              <thead>
                <tr>
                  <th class="sticky-head">#</th>
                  <th class="sticky-head">Date</th>
                  <th class="sticky-head text-end">Amount (₹)</th>
                  <th class="sticky-head">Method</th>
                  <th class="sticky-head">Remarks</th>
                  <th class="sticky-head text-end">Receipt</th>
                  <th class="sticky-head text-end">Cumulative (₹)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="7" class="text-center text-muted">Select a contribution to view payments.</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="loading-overlay d-none" id="gcph-loading"><div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem"><span class="sr-only">Loading...</span></div></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MIQAAT INVOICE PAYMENT HISTORY MODAL (Redesigned) -->
  <div class="modal fade" id="miqaat-history-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-history me-2"></i> Miqaat Invoice Payments</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0 position-relative">
          <div class="miqaat-summary-wrapper">
            <div class="miqaat-summary-grid">
              <div class="item"><span class="lbl">Invoice ID</span><span class="val" id="mq-invoice-id">-</span></div>
              <div class="item"><span class="lbl">Miqaat</span><span class="val" id="mq-miqaat-name">-</span></div>
              <div class="item"><span class="lbl">Type</span><span class="val" id="mq-miqaat-type">-</span></div>
              <div class="item"><span class="lbl">Amount</span><span class="val text-primary">₹<span id="mq-amount">0.00</span></span></div>
              <div class="item"><span class="lbl">Received</span><span class="val text-success">₹<span id="mq-received">0.00</span></span></div>
              <div class="item"><span class="lbl">Balance</span><span class="val text-danger">₹<span id="mq-balance">0.00</span></span></div>
              <div class="item"><span class="lbl">Status</span><span class="val"><span id="mq-status-badge" class="miqaat-status-badge miqaat-status-unpaid">UNPAID</span></span></div>
              <div class="item"><span class="lbl">Paid %</span><span class="val" id="mq-paid-pct">0%</span></div>
            </div>
            <div class="miqaat-progress-wrap">
              <div class="progress miqaat-progress" aria-label="Paid Progress"><div class="progress-bar" id="mq-progress-bar" style="width:0%"></div></div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" id="miqaat-history-table">
              <thead>
                <tr>
                  <th class="sticky-head">#</th>
                  <th class="sticky-head">Payment ID</th>
                  <th class="sticky-head text-end">Amount (₹)</th>
                  <th class="sticky-head">Date</th>
                  <th class="sticky-head">Method</th>
                  <th class="sticky-head">Remarks</th>
                  <th class="sticky-head text-end">Receipt</th>
                  <th class="sticky-head text-end">Cumulative (₹)</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="8" class="text-center text-muted">Select an invoice.</td></tr>
              </tbody>
            </table>
          </div>
          <div class="loading-overlay d-none" id="miqaat-loading"><div class="spinner-border text-purple" style="color:#6f42c1;width:2.5rem;height:2.5rem" role="status"><span class="sr-only">Loading...</span></div></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- UNIVERSAL DETAILS MODAL (for viewing section content) -->
  <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="details-modal-title">Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="details-modal-body">
          <div class="text-center text-muted py-5">Loading...</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Assigned Thaali Dates Modal -->
  <div class="modal fade" id="assigned-thaali-days-container" tabindex="-1" aria-labelledby="assigned-thaali-days-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assigned-thaali-days-label">Assigned Thaali Dates</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="mb-2"><strong>Member Name:</strong> <span id="assigned-user-name"><?php echo htmlspecialchars($member_name ?? '-', ENT_QUOTES); ?></span></p>
          <p class="mb-3"><strong>FY:</strong> <span id="assigned-fy">-</span></p>
          <div id="assigned-dates-loading" class="text-secondary">Loading...</div>
          <div id="assigned-dates-empty" class="text-secondary d-none">No dates assigned.</div>
          <ul id="assigned-dates-list" class="pl-3 mb-0"></ul>
        </div>
      </div>
    </div>
  </div>
</div> <!-- /details-sections -->
</div> <!-- /container -->
<script>
  // Client-side Indian currency formatter for dynamic AJAX-loaded monetary values
  function formatINRClient(num) {
    const n = parseFloat(num);
    if (isNaN(n)) return '0';
    let intPart = Math.round(n).toString();
    if (intPart.length > 3) {
      const last3 = intPart.slice(-3);
      const rest = intPart.slice(0, -3);
      intPart = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + last3;
    }
    return intPart; // no decimals per requirement
  }
  // View Details buttons now open the section content inside a modal instead of inline reveal
  (function() {
    function buildSectionModal(sectionEl) {
      const modalTitleEl = document.getElementById('details-modal-title');
      const bodyEl = document.getElementById('details-modal-body');
      if (!modalTitleEl || !bodyEl) return;
      // Find a heading inside the section for modal title
      let title = 'Details';
      const foundHeader = sectionEl.querySelector('.card-header h5, .card-header h4, h5, h4');
      if (foundHeader) {
        title = foundHeader.textContent.trim();
      }
      modalTitleEl.textContent = title;
      // Clone section to avoid moving it
      const clone = sectionEl.cloneNode(true);
      clone.classList.remove('d-none');
      clone.removeAttribute('id');
      // Avoid nested margin stacking inside modal
      clone.classList.add('mb-0');
      // Optional: remove buttons that would re-open same modal (none currently inside tables)
      bodyEl.innerHTML = '';
      // Keep only inner card-body & header for cleaner presentation (strip outer shadow duplication?)
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
        // Show details modal (can coexist with other modals)
        $('#details-modal').modal('show');
      });
    });
    // Dynamic stacking for multiple Bootstrap modals
    let modalZBase = 1060;
    $(document).on('show.bs.modal', '.modal', function() {
      const $this = $(this);
      const $openModals = $('.modal.show').not($this);
      // Dim previous visible modals
      $openModals.addClass('dimmed-underlay');
      const newZ = modalZBase + ($openModals.length * 30); // spacing
      $this.css('z-index', newZ).removeClass('dimmed-underlay');
      // Collapse to single highest backdrop
      setTimeout(() => {
        const $backs = $('.modal-backdrop');
        if ($backs.length > 1) {
          $backs.not(':last').remove();
          $backs.last().css('z-index', newZ - 10);
        } else {
          $backs.last().css('z-index', newZ - 10);
        }
      }, 50);
    });
    $(document).on('hidden.bs.modal', '.modal', function() {
      const $visible = $('.modal.show');
      $visible.removeClass('dimmed-underlay');
      const $details = $('#details-modal');
      // Do not auto reopen details modal; if it is closed, clear persistence
      if (!$details.hasClass('show')) {
        $details.data('keep-open', false);
      }
      // Re-layer visible modals
      let base = 1060;
      let i = 0;
      $('.modal.show').each(function() {
        $(this).css('z-index', base + (i * 30));
        i++;
      });
      // Ensure a backdrop exists if at least one modal is open
      if ($('.modal.show').length && $('.modal-backdrop').length === 0) {
        $('<div class="modal-backdrop fade show"></div>').appendTo(document.body).css('z-index', base - 10);
      } else if (!$('.modal.show').length) {
        // Remove any leftover backdrop when no modals remain
        $('.modal-backdrop').remove();
      }
    });
    // Mark details-modal to persist
    let detailsExplicitlyClosed = false;
    $('#details-modal').on('shown.bs.modal', function() {
      if (!detailsExplicitlyClosed) {
        $(this).data('keep-open', true);
      }
    });
    $('#details-modal').on('hide.bs.modal', function() {
      // User is closing it; do not auto re-open
      detailsExplicitlyClosed = true;
      $(this).data('keep-open', false);
    });
    // When a child modal opens while details is visible, allow persistence again
    $(document).on('show.bs.modal', '#gc-payments-modal, #miqaat-history-modal, #description-modal', function() {
      const $details = $('#details-modal');
      if ($details.hasClass('show')) {
        detailsExplicitlyClosed = false;
        $details.data('keep-open', true);
      }
    });
  })();
  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 1,
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

  // View assigned thaali dates (family-wise) for a given FY in a popup modal.
  (function() {
    const cache = {};

    function pad2(n) {
      return String(n).padStart(2, '0');
    }

    function fmtGregDateDMY(d) {
      try {
        const dt = new Date(String(d) + 'T00:00:00');
        if (isNaN(dt.getTime())) return String(d);
        return pad2(dt.getDate()) + '-' + pad2(dt.getMonth() + 1) + '-' + dt.getFullYear();
      } catch (e) {
        return String(d);
      }
    }

    function renderDatesList(dates) {
      const $list = $('#assigned-dates-list');
      $list.empty();
      if (!dates || !dates.length) {
        $('#assigned-dates-empty').removeClass('d-none');
        return;
      }
      $('#assigned-dates-empty').addClass('d-none');
      dates.forEach(function(item) {
        const greg = item && item.greg_date ? fmtGregDateDMY(item.greg_date) : '-';
        const $li = $('<li></li>').text(greg);
        $list.append($li);
      });
    }

    $(document).on('click', '.view-assigned-thaali-days', function(e) {
      e.preventDefault();
      const year = String($(this).data('year') || '').trim();
      if (!year) return;

      $('#assigned-fy').text(year);
      $('#assigned-dates-loading').removeClass('d-none').text('Loading...');
      $('#assigned-dates-empty').addClass('d-none');
      $('#assigned-dates-list').empty();
      $('#assigned-thaali-days-container').modal('show');

      if (cache[year]) {
        $('#assigned-dates-loading').addClass('d-none');
        renderDatesList(cache[year]);
        return;
      }

      $.ajax({
        url: '<?php echo base_url('accounts/getfmbassignedthaalidates'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { year: year },
        success: function(res) {
          $('#assigned-dates-loading').addClass('d-none');
          if (!res || res.success === false) {
            const msg = (res && res.message) ? String(res.message) : 'Failed to load assigned dates.';
            $('#assigned-dates-empty').removeClass('d-none').text(msg);
            return;
          }
          const dates = (res && res.dates) ? res.dates : [];
          cache[year] = dates;
          renderDatesList(dates);
        },
        error: function() {
          $('#assigned-dates-loading').addClass('d-none');
          $('#assigned-dates-empty').removeClass('d-none').text('Failed to load assigned dates.');
        }
      });
    });
  })();

  $(".view-description").on("click", function(e) {
    e.preventDefault();
    if ($(this).data("description")) {
      $("#modal-view-description").text($(this).data("description"));
    } else {
      $("#modal-view-description").text("No description found!");
    }
  });

  // Load General Contribution payment history
  $(document).on('click', '.view-gc-payments', function(e) {
    e.preventDefault();
    const invoiceId = $(this).data('fmbgc-id');
    if (!invoiceId) {
      return;
    }
    // Reset loading state and summary placeholders
    $('#gcph-loading').removeClass('d-none');
    $('#gc-payments-table tbody').html('<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>');
    $('#gcph-invoice-id').text(invoiceId);
    $('#gcph-year, #gcph-type').text('-');
    $('#gcph-amount, #gcph-received, #gcph-balance').text('0.00');
    $('#gcph-status-badge').removeClass('gcph-status-paid gcph-status-partial gcph-status-unpaid').addClass('gcph-status-unpaid').text('UNPAID');
    $('#gcph-paid-pct').text('0%');
    $('#gcph-progress-bar').css('width','0%');
    // Fire AJAX
    $.ajax({
      url: '<?php echo base_url('accounts/gc_payment_history'); ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        fmbgc_id: invoiceId
      },
      success: function(res) {
        if (!res || !res.success) {
          $('#gc-payments-table tbody').html('<tr><td colspan="7" class="text-center text-danger">' + (res && res.message ? res.message : 'Failed to load payments') + '</td></tr>');
          $('#gc-payments-modal').modal('show');
          return;
        }
        const inv = res.invoice || {};
        const totalAmt = parseFloat(inv.amount || 0) || 0;
        const totalReceived = parseFloat(res.total_received || 0) || 0;
        const balance = parseFloat(res.balance_due || (totalAmt - totalReceived)) || 0;
        $('#gcph-year').text(inv.contri_year || '-');
        $('#gcph-type').text(inv.contri_type || '-');
        $('#gcph-amount').text(formatINRClient(totalAmt));
        $('#gcph-received').text(formatINRClient(totalReceived));
        $('#gcph-balance').text(formatINRClient(balance));
        // Determine status
        let statusClass = 'gcph-status-unpaid';
        let statusText = 'UNPAID';
        if (totalReceived > 0 && balance > 0) { statusClass = 'gcph-status-partial'; statusText = 'PARTIAL'; }
        if (balance <= 0 && totalAmt > 0) { statusClass = 'gcph-status-paid'; statusText = 'PAID'; }
        $('#gcph-status-badge').removeClass('gcph-status-paid gcph-status-partial gcph-status-unpaid').addClass(statusClass).text(statusText);
        const pct = totalAmt > 0 ? (totalReceived / totalAmt) * 100 : 0;
        $('#gcph-paid-pct').text(pct.toFixed(1) + '%');
        $('#gcph-progress-bar').css('width', pct.toFixed(2) + '%');
        const pays = res.payments || [];
        if (pays.length === 0) {
          $('#gc-payments-table tbody').html('<tr><td colspan="7" class="text-center text-muted">No payments recorded.</td></tr>');
        } else {
          let rows = '';
          let cumulative = 0;
          function fmtDate(d) {
            if (!d) return '-';
            var dt = new Date(d);
            if (isNaN(dt.getTime())) {
              var parts = (d + '').replace('T', ' ').split(/[ :\-]/);
              if (parts.length >= 3) {
                dt = new Date(parts[0], parseInt(parts[1], 10) - 1, parts[2]);
              }
            }
            if (isNaN(dt.getTime())) return '-';
            var day = ('0' + dt.getDate()).slice(-2);
            var mths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return day + '-' + mths[dt.getMonth()] + '-' + dt.getFullYear();
          }
          function methodIcon(method){
            if(!method) return '';
            const m = method.toLowerCase();
            if(m.includes('upi')) return '<i class="fa-solid fa-mobile-screen-button method-icon" title="UPI"></i>';
            if(m.includes('cash')) return '<i class="fa-solid fa-coins method-icon" title="Cash"></i>';
            if(m.includes('card')) return '<i class="fa-solid fa-credit-card method-icon" title="Card"></i>';
            if(m.includes('cheque') || m.includes('dd')) return '<i class="fa-regular fa-file-lines method-icon" title="Cheque"></i>';
            if(m.includes('bank')) return '<i class="fa-solid fa-building-columns method-icon" title="Bank"></i>';
            return '';
          }
          pays.forEach(function(p, i) {
            const amt = parseFloat(p.amount || 0) || 0;
            cumulative += amt;
            const pid = p.id || p.payment_id || '';
            const remarksSafe = (p.remarks ? $('<div/>').text(p.remarks).html() : '-');
            const remarksDisplay = remarksSafe.length > 60 ? remarksSafe.substring(0, 57) + '…' : remarksSafe;
            const methodDisp = (p.payment_method || '-');
            rows += '<tr>' +
              '<td data-label="#">' + (i + 1) + '</td>' +
              '<td data-label="Date">' + fmtDate(p.payment_date) + '</td>' +
              '<td data-label="Amount" class="text-end text-success">' + formatINRClient(amt) + '</td>' +
              '<td data-label="Method">' + methodIcon(methodDisp) + methodDisp + '</td>' +
              '<td data-label="Remarks" title="' + remarksSafe + '">' + remarksDisplay + '</td>' +
              '<td data-label="Receipt" class="text-end"><button class="btn btn-outline-primary gc-view-receipt btn-shrink" data-payment-id="' + pid + '" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>' +
              '<td data-label="Cumulative" class="text-end fw-semibold">' + formatINRClient(cumulative) + '</td>' +
              '</tr>';
          });
          $('#gc-payments-table tbody').html(rows);
        }
        $('#gc-payments-modal').modal('show');
      },
      error: function() {
        $('#gc-payments-table tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
        $('#gc-payments-modal').modal('show');
      },
      complete: function(){
        $('#gcph-loading').addClass('d-none');
      }
    });
  });

  // View receipt for a specific GC payment (PDF in new tab)
  $(document).on('click', '.gc-view-receipt', function(e) {
    e.preventDefault();
    const pid = $(this).data('payment-id');
    if (!pid) {
      return;
    }
    // Re-use existing admin PDF generator (parameter 'for' = 1 as in other receipt calls)
    $.ajax({
      url: '<?php echo base_url('common/generate_pdf'); ?>',
      type: 'POST',
      data: {
        id: pid,
        for: 3
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(blob) {
        var pdfBlob = new Blob([blob], {
          type: 'application/pdf'
        });
        var pdfUrl = URL.createObjectURL(pdfBlob);
        window.open(pdfUrl, '_blank');
      },
      error: function() {
        alert('Failed to load receipt PDF');
      }
    });
  });

  // Miqaat invoice payment history fetch & modal (Redesigned)
  $(document).on('click', '.view-miqaat-history', function(e) {
    e.preventDefault();
    const invoiceId = $(this).data('invoice-id');
    if (!invoiceId) return;
    $('#miqaat-history-modal').modal('show');
    $('#miqaat-loading').removeClass('d-none');
    $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>');
    // Reset summary
    $('#mq-invoice-id').text(invoiceId);
    $('#mq-miqaat-name, #mq-miqaat-type').text('-');
    $('#mq-amount, #mq-received, #mq-balance').text('0.00');
    $('#mq-status-badge').removeClass('miqaat-status-paid miqaat-status-partial miqaat-status-unpaid').addClass('miqaat-status-unpaid').text('UNPAID');
    $('#mq-paid-pct').text('0%');
    $('#mq-progress-bar').css('width','0%');
    $.ajax({
      url: '<?php echo base_url('accounts/miqaat_invoice_history'); ?>',
      type: 'POST',
      dataType: 'json',
      data: { invoice_id: invoiceId },
      success: function(res){
        if(!res || !res.success){
          $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-danger">' + (res && res.message ? res.message : 'Failed to load history') + '</td></tr>');
          return;
        }
        const inv = res.invoice || {};
        const totalAmt = parseFloat(inv.amount || 0) || 0;
        const totalReceived = parseFloat(inv.paid_amount || res.total_received || 0) || 0;
        const balance = parseFloat(inv.due_amount || (totalAmt - totalReceived)) || 0;
        $('#mq-miqaat-name').text(inv.miqaat_name || '-');
        $('#mq-miqaat-type').text(inv.miqaat_type || '-');
        $('#mq-amount').text(formatINRClient(totalAmt));
        $('#mq-received').text(formatINRClient(totalReceived));
        $('#mq-balance').text(formatINRClient(balance));
        let statusClass = 'miqaat-status-unpaid';
        let statusText = 'UNPAID';
        if(totalReceived > 0 && balance > 0){ statusClass = 'miqaat-status-partial'; statusText='PARTIAL'; }
        if(balance <= 0 && totalAmt > 0){ statusClass = 'miqaat-status-paid'; statusText='PAID'; }
        $('#mq-status-badge').removeClass('miqaat-status-paid miqaat-status-partial miqaat-status-unpaid').addClass(statusClass).text(statusText);
        const pct = totalAmt > 0 ? (totalReceived / totalAmt) * 100 : 0;
        $('#mq-paid-pct').text(pct.toFixed(1) + '%');
        $('#mq-progress-bar').css('width', pct.toFixed(2) + '%');
        const pays = res.payments || [];
        if(pays.length === 0){
          $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-muted">No payments recorded.</td></tr>');
          return;
        }
        function fmtDate(d){
          if(!d) return '-';
          var dt = new Date(d);
          if(isNaN(dt.getTime())){
            var parts = (d + '').replace('T',' ').split(/[ :\-]/);
            if(parts.length >= 3){ dt = new Date(parts[0], parseInt(parts[1],10)-1, parts[2]); }
          }
          if(isNaN(dt.getTime())) return '-';
          var day=('0'+dt.getDate()).slice(-2);
          var mths=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return day+'-'+mths[dt.getMonth()]+'-'+dt.getFullYear();
        }
        function methodIcon(method){
          if(!method) return '';
          const m = method.toLowerCase();
          if(m.includes('upi')) return '<i class="fa-solid fa-mobile-screen-button method-icon" title="UPI"></i>';
          if(m.includes('cash')) return '<i class="fa-solid fa-coins method-icon" title="Cash"></i>';
          if(m.includes('card')) return '<i class="fa-solid fa-credit-card method-icon" title="Card"></i>';
          if(m.includes('cheque') || m.includes('dd')) return '<i class="fa-regular fa-file-lines method-icon" title="Cheque"></i>';
          if(m.includes('bank')) return '<i class="fa-solid fa-building-columns method-icon" title="Bank"></i>';
          return '';
        }
        let rows='';
        let cumulative=0;
        pays.forEach(function(p,i){
          const amt = parseFloat(p.amount || 0) || 0;
          cumulative += amt;
          const pid = p.payment_id || p.id || '';
          const remarksSafe = (p.remarks ? $('<div/>').text(p.remarks).html() : '-');
          const remarksDisplay = remarksSafe.length > 60 ? remarksSafe.substring(0,57)+'…' : remarksSafe;
          const methodDisp = (p.payment_method || '-');
          rows += '<tr>'+
            '<td data-label="#">'+ (i+1) +'</td>'+
            '<td data-label="Payment ID">'+ pid +'</td>'+
            '<td data-label="Amount" class="text-end text-success">'+ formatINRClient(amt) +'</td>'+
            '<td data-label="Date">'+ fmtDate(p.payment_date) +'</td>'+
            '<td data-label="Method">'+ methodIcon(methodDisp) + methodDisp +'</td>'+
            '<td data-label="Remarks" title="'+ remarksSafe +'">'+ remarksDisplay +'</td>'+
            '<td data-label="Receipt" class="text-end"><button class="btn btn-outline-primary miqaat-view-receipt btn-shrink" data-payment-id="'+ pid +'" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>'+
            '<td data-label="Cumulative" class="text-end fw-semibold">'+ formatINRClient(cumulative) +'</td>'+
          '</tr>';
            '<td data-label="Date">'+ fmtDate(p.payment_date) +'</td>'+
            '<td data-label="Method">'+ methodIcon(methodDisp) + methodDisp +'</td>'+
            '<td data-label="Remarks" title="'+ remarksSafe +'">'+ remarksDisplay +'</td>'+
            '<td data-label="Receipt" class="text-end"><button class="btn btn-outline-primary miqaat-view-receipt btn-shrink" data-payment-id="'+ pid +'" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>'+
            '<td data-label="Cumulative" class="text-end fw-semibold">'+ formatINRClient(cumulative) +'</td>'+
          '</tr>';
            '<td data-label="Date">'+ fmtDate(p.payment_date) +'</td>'+
            '<td data-label="Method">'+ methodIcon(methodDisp) + methodDisp +'</td>'+
            '<td data-label="Remarks" title="'+ remarksSafe +'">'+ remarksDisplay +'</td>'+
            '<td data-label="Receipt" class="text-end"><button class="btn btn-outline-primary miqaat-view-receipt btn-shrink" data-payment-id="'+ pid +'" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>'+
            '<td data-label="Cumulative" class="text-end fw-semibold">'+ cumulative.toFixed(2) +'</td>'+
          '</tr>';
        });
        $('#miqaat-history-table tbody').html(rows);
      },
      error: function(){
        $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-danger">Server error.</td></tr>');
      },
      complete: function(){
        $('#miqaat-loading').addClass('d-none');
      }
    });
  });

  // Miqaat receipt viewer
  $(document).on('click', '.miqaat-view-receipt', function(e) {
    e.preventDefault();
    const pid = $(this).data('payment-id');
    if (!pid) return;
    const btn = $(this);
    btn.prop('disabled', true).addClass('disabled');
    $.ajax({
      url: '<?php echo base_url('common/generate_pdf'); ?>',
      type: 'POST',
      data: {
        id: pid,
        for: 2
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(blob) {
        if (!(blob instanceof Blob)) {
          alert('Unexpected response');
          return;
        }
        var pdfBlob = new Blob([blob], {
          type: 'application/pdf'
        });
        var pdfUrl = URL.createObjectURL(pdfBlob);
        window.open(pdfUrl, '_blank');
        setTimeout(() => URL.revokeObjectURL(pdfUrl), 60000);
      },
      error: function() {
        alert('Failed to load receipt PDF');
      },
      complete: function() {
        btn.prop('disabled', false).removeClass('disabled');
      }
    });
  });
  // (Optional) Persist section visibility (future enhancement)
</script>