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
<div class="container margintopcontainer pt-5">
  <div class="mb-4 p-0 pt-5">
    <a href="<?php echo base_url('accounts'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="mb-3 heading text-center d-flex justify-content-center align-items-center gap-3 flex-wrap">
    <span>FMB Details Overview</span>
  </h4>
  <!-- Toggle buttons relocated inside cards as View Details -->
  <style>
    .overview-card-upscaled .metric-box {
      position: relative;
      overflow: hidden;
      background: linear-gradient(145deg, #ffffff, #f4f6f9);
      border: 1px solid #e1e5ec;
      border-radius: 18px;
      padding: 22px 20px;
      min-height: 170px;
      width: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: box-shadow .25s, transform .18s, border-color .3s;
      height: 100%;
    }

    .overview-card-upscaled .metric-box:before {
      content: "";
      position: absolute;
      top: -35%;
      right: -35%;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle at center, rgba(0, 123, 255, .15), transparent 70%);
      transform: rotate(25deg);
    }

    .overview-card-upscaled .metric-box:hover {
      box-shadow: 0 8px 26px -6px rgba(0, 0, 0, .15);
      transform: translateY(-4px);
    }

    .overview-card-upscaled h6 {
      font-size: .8rem;
      letter-spacing: .65px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .overview-card-upscaled .sub-metrics span {
      display: inline-block;
      margin: 4px 8px 0 0;
      font-size: .62rem;
      text-transform: uppercase;
      color: #6c757d;
      letter-spacing: .55px;
      background: #eef1f5;
      padding: 3px 6px;
      border-radius: 4px;
      font-weight: 500;
    }

    .overview-card-upscaled .accent-bar {
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 6px;
      background: linear-gradient(180deg, var(--accent-from), var(--accent-to));
    }

    .overview-card-upscaled .icon-wrap {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      margin-right: 10px;
      color: #fff;
      box-shadow: 0 3px 8px -2px rgba(0, 0, 0, .25);
    }

    .overview-card-upscaled .progress {
      height: 6px;
      margin-top: 6px;
      background: #dfe3e8;
      border-radius: 4px;
      overflow: hidden;
    }

    .overview-card-upscaled .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--accent-from), var(--accent-to));
    }

    .metric-head {
      display: flex;
      align-items: center;
    }

    .metric-inline {
      display: flex;
      flex-wrap: wrap;
      gap: 24px;
      margin-top: 4px;
    }

    .metric-inline>div {
      min-width: 90px;
    }

    .metric-inline .label {
      font-size: .65rem;
      font-weight: 600;
      letter-spacing: .5px;
      color: #6c757d;
      text-transform: uppercase;
    }

    .metric-inline .val {
      font-size: 1.05rem;
      font-weight: 600;
    }

    .theme-takhmeen {
      --accent-from: #0069d9;
      --accent-to: #48a8ff;
    }

    .theme-gc {
      --accent-from: #198754;
      --accent-to: #4cc790;
    }

    .theme-miqaat {
      --accent-from: #6f42c1;
      --accent-to: #b08cff;
    }

    .theme-takhmeen .icon-wrap {
      background: linear-gradient(135deg, #007bff, #3296ff);
    }

    .theme-gc .icon-wrap {
      background: linear-gradient(135deg, #1aa160, #28c76f);
    }

    .theme-miqaat .icon-wrap {
      background: linear-gradient(135deg, #7b4dd6, #b38dff);
    }

    @media (max-width:991.98px) {
      .overview-card-upscaled .metric-box {
        min-height: unset;
      }

      .metric-inline {
        gap: 16px;
      }
    }

    @media (max-width:575.98px) {
      .metric-inline>div {
        min-width: 70px;
      }

      .overview-card-upscaled .metric-box {
        padding: 18px 16px;
        width: 100%;
      }
    }

    /* Modal stacking fixes */
    /* Base z-index for stacking; will be incremented dynamically */
    #details-modal.modal {
      z-index: 1060;
    }

    .modal-backdrop {
      z-index: 1050;
    }

    .modal.dimmed-underlay {
      filter: blur(2px) brightness(.9);
    }

    .modal.dimmed-underlay .modal-content {
      pointer-events: none;
    }

    /* ================= GC Payments Modal Mobile Optimization ================= */
    @media (max-width:575.98px) {
      #gc-payments-modal .modal-dialog {
        margin: .6rem auto;
        max-width: 100%;
      }

      #gc-payments-modal .table thead {
        display: none;
      }

      #gc-payments-modal .table tbody tr {
        display: block;
        background: #fff;
        margin: 0 0 .75rem;
        border: 1px solid #e2e6ea;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
        padding: .5rem .65rem;
      }

      #gc-payments-modal .table tbody tr:last-child {
        margin-bottom: 0;
      }

      #gc-payments-modal .table tbody td {
        display: flex;
        padding: .35rem .25rem;
        border: none !important;
        font-size: .76rem;
        line-height: 1.1rem;
      }

      #gc-payments-modal .table tbody td[data-label] {
        justify-content: space-between;
        gap: 8px;
      }

      #gc-payments-modal .table tbody td[data-label]::before {
        content: attr(data-label);
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-size: .63rem;
      }

      #gc-payments-modal .table tbody td:last-child {
        justify-content: flex-end;
      }

      #gc-payments-modal .table tbody td .btn {
        padding: .3rem .45rem;
        font-size: .65rem;
      }

      #gc-payments-modal .table-sm {
        font-size: inherit;
      }

      #gc-payments-modal .modal-header,
      #gc-payments-modal .modal-footer {
        padding: .5rem .75rem;
      }

      #gc-payments-modal .border-bottom.small.bg-light {
        font-size: .68rem;
      }

      #gc-payments-modal .border-bottom.small.bg-light .mr-2 {
        margin-right: .75rem !important;
      }

      /* Meta info wrap */
      #gc-payments-modal .border-bottom.small.bg-light>.d-flex {
        gap: .4rem !important;
      }
    }

    /* ================= Revamped GC Payments Modal ================= */
    #gc-payments-modal .gcph-summary-wrapper {
      padding: 1rem 1rem .85rem;
      background: linear-gradient(135deg, #f8fafc, #eef3f7);
      border-bottom: 1px solid #dfe5ea;
    }
    #gc-payments-modal .gcph-summary-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 1.25rem 2.5rem;
      font-size: .78rem;
      line-height: 1.05rem;
    }
    #gc-payments-modal .gcph-summary-grid .item .lbl {
      text-transform: uppercase;
      font-size: .6rem;
      letter-spacing: .5px;
      color: #6c7b88;
      font-weight: 600;
      display:block;
    }
    #gc-payments-modal .gcph-summary-grid .item .val {
      font-weight: 600;
      font-size: .85rem;
    }
    #gc-payments-modal .gcph-status-badge {
      display: inline-block;
      padding: .25rem .55rem;
      border-radius: 30px;
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .5px;
      text-transform: uppercase;
    }
    #gc-payments-modal .gcph-status-paid { background: #1aa160; color:#fff; }
    #gc-payments-modal .gcph-status-partial { background: #ffc107; color:#212529; }
    #gc-payments-modal .gcph-status-unpaid { background: #dc3545; color:#fff; }
    #gc-payments-modal .gcph-progress-wrap { margin-top:.6rem; }
    #gc-payments-modal .progress.gcph-progress { height:8px; background:#d8dee3; }
    #gc-payments-modal .progress.gcph-progress .progress-bar { background:linear-gradient(90deg,#198754,#48c98a); }
    #gc-payments-modal .table thead th.sticky-head { position: sticky; top:0; background:#1f2d3a; color:#fff; z-index:2; }
    #gc-payments-modal .table tbody tr:hover { background:#f4f9fc; }
    #gc-payments-modal .method-icon { margin-right:4px; opacity:.85; }
    #gc-payments-modal .loading-overlay { position:absolute; inset:0; background:rgba(255,255,255,.75); display:flex; align-items:center; justify-content:center; z-index:15; }
    #gc-payments-modal .loading-overlay.d-none { display:none !important; }
  #gc-payments-modal .btn.gc-view-receipt.btn-shrink { padding:.25rem .45rem; font-size:.55rem; line-height:1; }
  #gc-payments-modal .btn.gc-view-receipt.btn-shrink i { font-size:.7rem; }
    @media (max-width:575.98px){
      #gc-payments-modal .gcph-summary-grid { gap:.9rem 1.25rem; }
      #gc-payments-modal .gcph-summary-wrapper { padding:.75rem .75rem .65rem; }
    }

    /* ================= Revamped Miqaat Payments Modal ================= */
    #miqaat-history-modal .miqaat-summary-wrapper {
      padding: 1rem 1rem .85rem;
      background: linear-gradient(135deg, #f9f8fc, #f0eef9);
      border-bottom: 1px solid #ddd7ef;
    }
    #miqaat-history-modal .miqaat-summary-grid {
      display:flex;
      flex-wrap:wrap;
      gap:1.25rem 2.5rem;
      font-size:.78rem;
      line-height:1.05rem;
    }
    #miqaat-history-modal .miqaat-summary-grid .item .lbl {
      text-transform:uppercase;
      font-size:.6rem;
      letter-spacing:.5px;
      color:#6c5d92;
      font-weight:600;
      display:block;
    }
    #miqaat-history-modal .miqaat-summary-grid .item .val {
      font-weight:600;
      font-size:.85rem;
    }
    #miqaat-history-modal .miqaat-status-badge {
      display:inline-block;
      padding:.25rem .55rem;
      border-radius:30px;
      font-size:.62rem;
      font-weight:600;
      letter-spacing:.5px;
      text-transform:uppercase;
    }
    #miqaat-history-modal .miqaat-status-paid { background:#6f42c1; color:#fff; }
    #miqaat-history-modal .miqaat-status-partial { background:#ffc107; color:#212529; }
    #miqaat-history-modal .miqaat-status-unpaid { background:#dc3545; color:#fff; }
    #miqaat-history-modal .miqaat-progress-wrap { margin-top:.6rem; }
    #miqaat-history-modal .progress.miqaat-progress { height:8px; background:#d8d2ef; }
    #miqaat-history-modal .progress.miqaat-progress .progress-bar { background:linear-gradient(90deg,#7b4dd6,#b38dff); }
    #miqaat-history-modal .table thead th.sticky-head { position:sticky; top:0; background:#3d2f52; color:#fff; z-index:2; }
    #miqaat-history-modal .table tbody tr:hover { background:#f8f5fc; }
    #miqaat-history-modal .method-icon { margin-right:4px; opacity:.85; }
    #miqaat-history-modal .loading-overlay { position:absolute; inset:0; background:rgba(255,255,255,.75); display:flex; align-items:center; justify-content:center; z-index:15; }
    #miqaat-history-modal .loading-overlay.d-none { display:none !important; }
    #miqaat-history-modal .btn.miqaat-view-receipt.btn-shrink { padding:.25rem .45rem; font-size:.55rem; line-height:1; }
    #miqaat-history-modal .btn.miqaat-view-receipt.btn-shrink i { font-size:.7rem; }
    @media (max-width:575.98px){
      #miqaat-history-modal .miqaat-summary-grid { gap:.9rem 1.25rem; }
      #miqaat-history-modal .miqaat-summary-wrapper { padding:.75rem .75rem .65rem; }
      #miqaat-history-modal .table thead { display:none; }
      #miqaat-history-modal .table tbody tr { display:block; background:#fff; margin:0 0 .75rem; border:1px solid #e2e6ea; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,.05); padding:.5rem .65rem; }
      #miqaat-history-modal .table tbody tr:last-child { margin-bottom:0; }
      #miqaat-history-modal .table tbody td { display:flex; padding:.35rem .25rem; border:none !important; font-size:.76rem; line-height:1.1rem; }
      #miqaat-history-modal .table tbody td[data-label] { justify-content:space-between; gap:8px; }
      #miqaat-history-modal .table tbody td[data-label]::before { content:attr(data-label); font-weight:600; color:#4d3f63; text-transform:uppercase; letter-spacing:.5px; font-size:.63rem; }
      #miqaat-history-modal .table tbody td:last-child { justify-content:flex-end; }
      #miqaat-history-modal .table tbody td .btn { padding:.3rem .45rem; font-size:.65rem; }
    }

    /* General Contributions card list (table removed) */
    #gc-card-list {
      display: block;
      /* Desktop grid layout will be applied via media queries */
    }

    .gc-card {
      background: #fff;
      border: 1px solid #e3e6eb;
      border-radius: 14px;
      padding: .85rem .9rem;
      margin-bottom: .9rem;
      position: relative;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
    }

    /* Desktop 3-column grid (>=992px) */
    @media (min-width: 992px) {
      #gc-card-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem 1.15rem;
        padding: 1rem 1rem 1.2rem;
      }
      #gc-card-list .gc-card {
        margin-bottom: 0; /* grid gap handles spacing */
        display: flex;
        flex-direction: column;
        height: 100%;
      }
      #gc-card-list .gc-actions {
        flex-wrap: wrap;
      }
      #gc-card-list .gc-history-btn-wrapper {
        margin-top: auto; /* push to bottom */
      }
    }

    /* Medium screens (>=768px <992px) fallback to 2 columns */
    @media (min-width: 768px) and (max-width: 991.98px) {
      #gc-card-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .9rem 1rem;
        padding: .75rem .75rem 1rem;
      }
      #gc-card-list .gc-card {
        margin-bottom: 0;
      }
    }

    .gc-card:last-child {
      margin-bottom: 0;
    }

    .gc-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: .5rem;
      gap: .5rem;
    }

    .gc-badge {
      font-size: .62rem;
      padding: .25rem .45rem;
      border-radius: 4px;
      font-weight: 600;
      letter-spacing: .5px;
      text-transform: uppercase;
    }

    .gc-badge.paid {
      background: #28a745;
      color: #fff;
    }

    .gc-badge.partial {
      background: #ffc107;
      color: #212529;
    }

    .gc-badge.unpaid {
      background: #dc3545;
      color: #fff;
    }

    .gc-meta {
      font-size: .64rem;
      color: #59626b;
      display: flex;
      flex-wrap: wrap;
      gap: .55rem .9rem;
      margin-bottom: .4rem;
    }

    .gc-meta span {
      white-space: nowrap;
    }

    .gc-amounts {
      display: flex;
      gap: 1.25rem;
      flex-wrap: wrap;
      margin-bottom: .45rem;
    }

    .gc-amounts .amt {
      flex: 1 1 90px;
    }

    .gc-amounts .lbl {
      font-size: .58rem;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: #6c757d;
      font-weight: 600;
    }

    .gc-amounts .val {
      font-size: .9rem;
      font-weight: 600;
    }

    .gc-inline-divider {
      height: 1px;
      background: linear-gradient(90deg, #dee2e6, rgba(0, 0, 0, 0));
      margin: .4rem 0 .55rem;
    }

    .gc-actions {
      display: flex;
      gap: .5rem;
      margin-top: .3rem;
    }

    .gc-actions .btn {
      padding: .4rem .6rem;
      font-size: .7rem;
    }

    .gc-history-btn-wrapper {
      margin-top: .55rem;
      display: flex;
      justify-content: flex-end;
    }

    .gc-history-btn-wrapper .btn {
      font-size: .7rem;
      padding: .45rem .9rem;
    }

    @media (max-width:575.98px) {
      .gc-history-btn-wrapper {
        justify-content: stretch;
      }
      .gc-history-btn-wrapper .btn {
        width: 100%;
        padding: .5rem .75rem;
      }
    }

    .gc-desc-empty {
      font-size: .6rem;
      color: #999;
    }

    /* Miqaat card layout (mirrors GC) */
    #miqaat-card-list { display:block; }
    .miq-card { background:#fff; border:1px solid #e3e1eb; border-radius:14px; padding:.85rem .9rem; margin-bottom:.9rem; position:relative; box-shadow:0 2px 4px rgba(0,0,0,.05); display:flex; flex-direction:column; }
    .miq-card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem; gap:.5rem; }
    .miq-badge { font-size:.62rem; padding:.25rem .45rem; border-radius:4px; font-weight:600; letter-spacing:.5px; text-transform:uppercase; }
    .miq-badge.paid { background:#6f42c1; color:#fff; }
    .miq-badge.partial { background:#ffc107; color:#212529; }
    .miq-badge.unpaid { background:#dc3545; color:#fff; }
    .miq-meta { font-size:.64rem; color:#5a5661; display:flex; flex-wrap:wrap; gap:.55rem .9rem; margin-bottom:.4rem; }
    .miq-meta span { white-space:nowrap; }
    .miq-amounts { display:flex; gap:1.25rem; flex-wrap:wrap; margin-bottom:.45rem; }
    .miq-amounts .amt { flex:1 1 90px; }
    .miq-amounts .lbl { font-size:.58rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d; font-weight:600; }
    .miq-amounts .val { font-size:.9rem; font-weight:600; }
    .miq-inline-divider { height:1px; background:linear-gradient(90deg,#dee2e6,rgba(0,0,0,0)); margin:.4rem 0 .55rem; }
    .miq-actions { display:flex; gap:.5rem; margin-top:.3rem; }
    .miq-actions .btn { padding:.4rem .6rem; font-size:.7rem; }
    .miq-history-btn-wrapper { margin-top:.55rem; display:flex; justify-content:flex-end; }
    .miq-history-btn-wrapper .btn { font-size:.7rem; padding:.45rem .9rem; }
    @media (max-width:575.98px){
      .miq-history-btn-wrapper { justify-content:stretch; }
      .miq-history-btn-wrapper .btn { width:100%; padding:.5rem .75rem; }
      .miq-amounts { gap:.75rem; }
      .miq-amounts .amt { flex:1 1 70px; }
      .miq-amounts .val { font-size:.85rem; }
    }
    /* Desktop grid */
    @media (min-width: 992px){
      #miqaat-card-list { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem 1.15rem; padding:1rem 1rem 1.2rem; }
      #miqaat-card-list .miq-card { margin-bottom:0; }
      #miqaat-card-list .miq-history-btn-wrapper { margin-top:auto; }
    }
    /* Medium 2-col */
    @media (min-width:768px) and (max-width:991.98px){
      #miqaat-card-list { display:grid; grid-template-columns:repeat(2,1fr); gap:.9rem 1rem; padding:.75rem .75rem 1rem; }
      #miqaat-card-list .miq-card { margin-bottom:0; }
    }

    @media (max-width:575.98px) {
      .gc-amounts {
        gap: .75rem;
      }

      .gc-amounts .amt {
        flex: 1 1 70px;
      }

      .gc-amounts .val {
        font-size: .85rem;
      }
    }
  </style>
  <div class="card shadow-sm mb-4 border-0 overview-card overview-card-upscaled">
    <div class="card-body pt-0 pt-md-2">
      <div class="row g-3 align-items-stretch">
        <div class="col-12 col-md-4 d-flex mb-3 mb-md-0">
          <div class="metric-box theme-takhmeen">
            <span class="accent-bar"></span>
            <div class="metric-head mb-2">
              <div class="icon-wrap"><i class="fa-solid fa-layer-group"></i></div>
              <div>
                <h6 class="mb-1">THAALI TAKHMEEN</h6>
                <div class="metric-inline">
                  <div>
                    <div class="label">Amount</div>
                    <div class="val text-primary">₹<?php echo format_inr_no_decimals($fmb_overall_amount); ?></div>
                  </div>
                  <div>
                    <div class="label">Due</div>
                    <div class="val text-danger">₹<?php echo format_inr_no_decimals($fmb_overall_due); ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="progress" aria-label="Takhmeen Paid Progress">
              <div class="progress-bar" style="width:<?php echo number_format($takh_pct, 2); ?>%"></div>
            </div>
            <div class="sub-metrics mt-2"><span>Years: <?php echo $fmb_takhmeen_count; ?></span><span><?php echo number_format($takh_pct, 1); ?>% Paid</span></div>
            <div class="mt-3 text-end">
              <button type="button" class="btn btn-sm btn-outline-primary view-details-btn" data-target="#sec-takhmeen"><i class="fa fa-eye me-1"></i> View Details</button>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 d-flex mb-3 mb-md-0">
          <div class="metric-box theme-miqaat">
            <span class="accent-bar"></span>
            <div class="metric-head mb-2">
              <div class="icon-wrap"><i class="fa-solid fa-calendar-day"></i></div>
              <div>
                <h6 class="mb-1">MIQAAT NIYAZ INVOICES</h6>
                <div class="metric-inline">
                  <div>
                    <div class="label">Amount</div>
                    <div class="val text-primary">₹<?php echo format_inr_no_decimals($miqaat_total_amount); ?></div>
                  </div>
                  <div>
                    <div class="label">Paid</div>
                    <div class="val text-success">₹<?php echo format_inr_no_decimals($miqaat_total_paid); ?></div>
                  </div>
                  <div>
                    <div class="label">Due</div>
                    <div class="val text-danger">₹<?php echo format_inr_no_decimals($miqaat_total_due); ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="progress" aria-label="Miqaat Paid Progress">
              <div class="progress-bar" style="width:<?php echo number_format($miq_pct, 2); ?>%"></div>
            </div>
            <div class="sub-metrics mt-2"><span>Invoices: <?php echo $miqaat_invoice_count; ?></span><span>Paid: <?php echo $miqaat_paid_count; ?></span><span>Partial: <?php echo $miqaat_partial; ?></span><span>Unpaid: <?php echo $miqaat_unpaid; ?></span><span><?php echo number_format($miq_pct, 1); ?>% Paid</span></div>
            <div class="mt-3 text-end">
              <button type="button" class="btn btn-sm btn-outline-purple view-details-btn" data-target="#sec-miqaat" style="--bs-btn-color:#6f42c1;border-color:#6f42c1;color:#6f42c1"><i class="fa fa-eye me-1"></i> View Details</button>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 d-flex mb-3 mb-md-0">
          <div class="metric-box theme-gc">
            <span class="accent-bar"></span>
            <div class="metric-head mb-2">
              <div class="icon-wrap"><i class="fa-solid fa-hand-holding-dollar"></i></div>
              <div>
                <h6 class="mb-1">FMB EXTRA CONTRIBUTIONS</h6>
                <div class="metric-inline">
                  <div>
                    <div class="label">Amount</div>
                    <div class="val text-primary">₹<?php echo format_inr_no_decimals($gc_total_amount); ?></div>
                  </div>
                  <div>
                    <div class="label">Paid</div>
                    <div class="val text-success">₹<?php echo format_inr_no_decimals($gc_total_paid); ?></div>
                  </div>
                  <div>
                    <div class="label">Due</div>
                    <div class="val text-danger">₹<?php echo format_inr_no_decimals($gc_total_due); ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="progress" aria-label="General Contributions Paid Progress">
              <div class="progress-bar" style="width:<?php echo number_format($gc_pct, 2); ?>%"></div>
            </div>
            <div class="sub-metrics mt-2"><span>Invoices: <?php echo $gc_invoice_count; ?></span><span>Paid: <?php echo $gc_fully_paid; ?></span><span>Partial: <?php echo $gc_partial; ?></span><span>Unpaid: <?php echo $gc_unpaid; ?></span><span><?php echo number_format($gc_pct, 1); ?>% Paid</span></div>
            <div class="mt-3 text-end">
              <button type="button" class="btn btn-sm btn-outline-success view-details-btn" data-target="#sec-gc"><i class="fa fa-eye me-1"></i> View Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div id="details-sections" class="mb-5" style="display:none !important;">
    <!-- TAKHMEEN SECTION -->
    <div id="sec-takhmeen" class="detail-section card shadow-sm mb-4 d-none">
      <div class="card-header text-center bg-light">
        <h5 class="mb-0">FMB Takhmeen (All Years)</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead class="thead-dark">
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
              <thead class="thead-dark">
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
        <h5 class="mb-0">FMB General Contributions</h5>
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

      <!-- MIQAAT SECTION (Cards) -->
      <div id="sec-miqaat" class="detail-section card shadow-sm mt-4 d-none">
        <div class="card-header text-center bg-light">
          <h5 class="mb-0">Miqaat Niyaz Invoices</h5>
        </div>
        <div class="card-body p-0">
          <div id="miqaat-card-list" class="p-2">
            <?php if (!empty($miqaat_invoices)): ?>
              <?php foreach ($miqaat_invoices as $i => $inv): ?>
                <?php
                  $amt  = (float)($inv['amount'] ?? 0);
                  $paid = (float)($inv['paid_amount'] ?? 0);
                  $due  = max(0, $amt - $paid);
                  $badgeClass = 'unpaid';
                  $badgeText  = 'Unpaid';
                  if ($paid > 0 && $due > 0) { $badgeClass='partial'; $badgeText='Partial'; }
                  if ($due <= 0.00001 && $amt > 0) { $badgeClass='paid'; $badgeText='Paid'; $due = 0; }
                ?>
                <div class="miq-card" data-miqaat-id="<?php echo (int)$inv['id']; ?>">
                  <div class="miq-card-header">
                    <strong>#<?php echo $i+1; ?> • <?php echo htmlspecialchars($inv['miqaat_name'] ?? ''); ?></strong>
                    <span class="miq-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
                  </div>
                  <div class="miq-meta">
                    <span>Type: <strong><?php echo htmlspecialchars($inv['miqaat_type'] ?? ''); ?></strong></span>
                    <span>Date: <strong><?php echo !empty($inv['invoice_date']) ? date('d-M-Y', strtotime($inv['invoice_date'])) : '-'; ?></strong></span>
                    <span>Invoice ID: <strong><?php echo (int)$inv['id']; ?></strong></span>
                  </div>
                  <div class="miq-amounts">
                    <div class="amt"><div class="lbl">Amount</div><div class="val text-primary">₹<?php echo format_inr_no_decimals($amt); ?></div></div>
                    <div class="amt"><div class="lbl">Paid</div><div class="val text-success">₹<?php echo format_inr_no_decimals(min($paid,$amt)); ?></div></div>
                    <div class="amt"><div class="lbl">Due</div><div class="val text-danger">₹<?php echo format_inr_no_decimals($due); ?></div></div>
                  </div>
                  <div class="miq-inline-divider"></div>
                    <?php 
                      $miqDesc = isset($inv['description']) ? trim((string)$inv['description']) : ''; 
                      $miqType = isset($inv['miqaat_type']) ? trim((string)($inv['miqaat_type'])) : ''; 
                    ?>
                    <div class="miq-actions">
                      <?php if ($miqType !== '' || $miqDesc !== ''): ?>
                        <span class="text-muted small" title="<?php echo htmlspecialchars(($miqType ? ('Type: ' . $miqType . ($miqDesc !== '' ? ' — ' : '')) : '') . $miqDesc); ?>">
                          <?php if ($miqType !== ''): ?><strong><?php echo htmlspecialchars($miqType); ?></strong><?php endif; ?>
                          <?php if ($miqType !== '' && $miqDesc !== ''): ?> • <?php endif; ?>
                          <?php echo $miqDesc !== '' ? htmlspecialchars($miqDesc) : ''; ?>
                        </span>
                      <?php else: ?>
                        <span class="gc-desc-empty">No description</span>
                      <?php endif; ?>
                    </div>
                  <div class="miq-history-btn-wrapper">
                    <button class="btn btn-outline-secondary btn-sm view-miqaat-history" data-invoice-id="<?php echo (int)$inv['id']; ?>" title="View Payment History"><i class="fa-solid fa-clock-rotate-left me-1"></i> Payment History</button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="text-center text-muted small py-3">No miqaat invoices found.</div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Payments merged into Takhmeen section above -->
    </div><!-- /#details-sections -->
  </div>

  <!-- DESCRIPTION MODAL -->
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Description</h5>
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
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-center"><i class="fa-solid fa-receipt me-2"></i> General Contribution Payments</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
              <thead class="thead-dark">
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
        <div class="modal-header bg-purple text-white" style="background:#6f42c1;">
          <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left me-2"></i> Miqaat Invoice Payments</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
              <thead class="thead-dark">
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
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="details-modal-title">Details</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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