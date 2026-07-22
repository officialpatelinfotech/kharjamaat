<?php
// Calculate totals for summary card
$total_amount = 0;
$total_paid = 0;
$invoice_count = 0;
$paid_count = 0;
$partial_count = 0;
$unpaid_count = 0;

$ashara_invoices = [];
$shehrullah_invoices = [];
$ladies_invoices = [];
$general_invoices = [];

if (!empty($miqaat_invoices)) {
  foreach ($miqaat_invoices as $inv) {
    $invoice_count++;
    $amt = (float)($inv['amount'] ?? 0);
    $paid = (float)($inv['paid_amount'] ?? 0);
    $due = max(0, $amt - $paid);
    
    $total_amount += $amt;
    $total_paid += min($paid, $amt);
    
    if ($due <= 0.00001) {
      $paid_count++;
    } elseif ($paid > 0) {
      $partial_count++;
    } else {
      $unpaid_count++;
    }

    // Classify into sections
    $type = strtolower($inv['miqaat_type'] ?? '');
    $name = strtolower($inv['miqaat_name'] ?? '');
    
    if (strpos($type, 'ashara') !== false || strpos($name, 'ashara') !== false) {
      $ashara_invoices[] = $inv;
    } elseif (strpos($type, 'shehrullah') !== false || strpos($name, 'shehrullah') !== false) {
      $shehrullah_invoices[] = $inv;
    } elseif (strpos($type, 'ladies') !== false || strpos($name, 'ladies') !== false) {
      $ladies_invoices[] = $inv;
    } else {
      $general_invoices[] = $inv;
    }
  }
}
$total_due = max(0, $total_amount - $total_paid);
$pct_paid = $total_amount > 0 ? ($total_paid / $total_amount) * 100 : 0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

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
    --purple:      #6f42c1;
    --purple-bg:   #f3ebff;
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

  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.6rem; font-weight: 600; letter-spacing: -.3px; }
  
  /* Summary Card Glassmorphism */
  .summary-card {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    color: #fff;
    border-radius: var(--radius-lg);
    border: none;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
  }
  .summary-card::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  .summary-card-body {
    position: relative;
    z-index: 2;
    padding: 24px;
  }
  .summary-title {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.85);
  }
  .summary-value {
    font-size: 1.8rem;
    font-weight: 800;
  }
  .summary-sub {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.75);
  }

  /* Type Wise Section Cards */
  .type-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 24px;
    transition: box-shadow .2s;
  }
  .type-card:hover { box-shadow: var(--shadow-lg); }

  .type-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
  }
  .type-card-header .card-title {
    font-size: 0.85rem; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: var(--text-2);
    display: flex; align-items: center; gap: 10px;
    margin: 0;
  }
  .type-card-header .card-title i {
    width: 28px; height: 28px; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.8rem;
  }

  .theme-ashara i { background: #ffebeb; color: #cc0000; }
  .theme-shehrullah i { background: #eff6ff; color: #1d4ed8; }
  .theme-general i { background: #fbf0db; color: #b45309; }
  .theme-ladies i { background: #fdf2f8; color: #db2777; }

  .type-card-body { padding: 20px; }

  /* Invoice Card grid */
  .miq-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
  }
  .miq-card {
    background: #fff;
    border: 1px solid #e3e1eb;
    border-radius: 14px;
    padding: 14px;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform .15s ease, box-shadow .15s ease;
  }
  .miq-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
  }
  .miq-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 6px;
    gap: 8px;
  }
  .miq-card-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-1);
    margin: 0;
    line-height: 1.3;
  }
  .miq-badge {
    font-size: 0.58rem;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .miq-badge.paid { background: var(--green-bg); color: var(--green); border: 1px solid #bbf7d0; }
  .miq-badge.partial { background: var(--amber-bg); color: var(--amber); border: 1px solid #fde68a; }
  .miq-badge.unpaid { background: var(--red-bg); color: var(--red); border: 1px solid #fca5a5; }
  
  .miq-meta {
    font-size: 0.65rem;
    color: var(--text-3);
    display: flex;
    flex-wrap: wrap;
    gap: 4px 12px;
    margin-bottom: 10px;
  }
  .miq-meta span strong {
    color: var(--text-2);
  }
  
  .miq-amounts {
    display: flex;
    gap: 12px;
    background: var(--surface-2);
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 10px;
  }
  .miq-amounts .amt {
    flex: 1;
    min-width: 0;
  }
  .miq-amounts .lbl {
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: .3px;
    color: var(--text-3);
    font-weight: 600;
    margin-bottom: 2px;
  }
  .miq-amounts .val {
    font-size: 0.82rem;
    font-weight: 700;
  }
  .text-due { color: var(--red); }
  
  .miq-desc {
    font-size: 0.65rem;
    color: var(--text-3);
    line-height: 1.3;
    margin-bottom: 12px;
  }

  .miq-history-btn-wrapper {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
  }
  .pay-now-btn {
    transition: all 0.15s;
  }
  .pay-now-btn:hover {
    background-color: var(--gold-light) !important;
    border-color: var(--gold-light) !important;
  }
  .miq-history-btn-wrapper .btn {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text-2);
    transition: all 0.15s;
  }
  .miq-history-btn-wrapper .btn:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
  }

  /* Empty state */
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    text-align: center;
    color: var(--text-3);
  }
  .empty-state i {
    font-size: 1.8rem;
    margin-bottom: 10px;
    opacity: 0.5;
  }
  .empty-state p {
    margin: 0;
    font-size: 0.78rem;
  }

  /* Modals */
  .modal-content {
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-lg);
  }
  .modal-header {
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
    padding: 16px 20px;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold);
    font-weight: 600;
  }
  .modal-body {
    padding: 20px;
  }
  .modal-footer {
    border-top: 1px solid var(--border);
    padding: 12px 20px;
  }

  .miqaat-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
    background: var(--surface-2);
    padding: 14px;
    border-radius: 10px;
  }
  .miqaat-summary-grid .item {
    display: flex;
    flex-direction: column;
  }
  .miqaat-summary-grid .lbl {
    font-size: 0.58rem;
    text-transform: uppercase;
    color: var(--text-3);
    font-weight: 600;
  }
  .miqaat-summary-grid .val {
    font-size: 0.85rem;
    font-weight: 700;
    margin-top: 2px;
  }

  /* Progress bar inside modal */
  .miqaat-progress-wrap {
    margin-bottom: 16px;
  }
  .miqaat-progress {
    height: 6px;
    border-radius: 4px;
    background: #e2e8f0;
    overflow: hidden;
  }
  .miqaat-progress .progress-bar {
    background: linear-gradient(90deg, #6f42c1, #a855f7);
  }

  .miqaat-status-badge {
    font-size: 0.58rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
  }
  .miqaat-status-paid { background: var(--green-bg); color: var(--green); }
  .miqaat-status-partial { background: var(--amber-bg); color: var(--amber); }
  .miqaat-status-unpaid { background: var(--red-bg); color: var(--red); }

  .loading-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
  }

  .dimmed-underlay {
    opacity: 0.4;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
  </div>

  <h1 class="page-heading mb-3 text-center">Miqaat Niyaz Invoices</h1>
  
  <!-- Overall Summary Card -->
  <div class="card summary-card mb-4 border-0">
    <div class="summary-card-body">
      <div class="row align-items-center">
        <div class="col-md-3 text-center text-md-left mb-3 mb-md-0">
          <div class="summary-title">Total Invoices</div>
          <div class="summary-value"><?php echo $invoice_count; ?></div>
          <div class="summary-sub"><?php echo $paid_count; ?> Paid | <?php echo $partial_count; ?> Partial</div>
        </div>
        <div class="col-md-6 text-center text-md-left mb-3 mb-md-0">
          <div class="row">
            <div class="col-4">
              <div class="summary-title">Total Niyaz</div>
              <div class="summary-value font-weight-bold" style="font-size:1.4rem;">₹<?php echo format_inr_no_decimals($total_amount); ?></div>
            </div>
            <div class="col-4">
              <div class="summary-title">Total Paid</div>
              <div class="summary-value text-white-50" style="font-size:1.4rem;">₹<?php echo format_inr_no_decimals($total_paid); ?></div>
            </div>
            <div class="col-4">
              <div class="summary-title">Outstanding</div>
              <div class="summary-value text-warning" style="font-size:1.4rem;">₹<?php echo format_inr_no_decimals($total_due); ?></div>
            </div>
          </div>
          <div class="progress mt-3" style="height: 6px; background: rgba(255,255,255,0.22); border-radius: 4px; overflow: hidden;">
            <div class="progress-bar bg-white" style="width: <?php echo number_format($pct_paid, 2); ?>%"></div>
          </div>
        </div>
        <div class="col-md-3 text-center text-md-right">
          <div class="summary-title">Overall Progress</div>
          <div class="summary-value text-warning"><?php echo number_format($pct_paid, 1); ?>%</div>
          <div class="summary-sub">Paid till date</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ASHARA SECTION -->
  <div class="type-card theme-ashara">
    <div class="type-card-header">
      <h3 class="card-title"><i class="fa-solid fa-fire-burner"></i> Ashara Niyaz Invoices</h3>
      <span class="badge badge-pill <?php echo count($ashara_invoices) > 0 ? 'badge-danger' : 'badge-success'; ?>">
        <?php echo count($ashara_invoices); ?> Invoice<?php echo count($ashara_invoices) === 1 ? '' : 's'; ?>
      </span>
    </div>
    <div class="type-card-body">
      <?php if (!empty($ashara_invoices)): ?>
        <div class="miq-card-grid">
          <?php foreach ($ashara_invoices as $inv): ?>
            <?php renderMiqaatInvoiceCard($inv); ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fa-solid fa-calendar-minus"></i>
          <p>No Ashara Niyaz invoices found.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- SHEHRULLAH SECTION -->
  <div class="type-card theme-shehrullah">
    <div class="type-card-header">
      <h3 class="card-title"><i class="fa-solid fa-moon"></i> Shehrullah Niyaz Invoices</h3>
      <span class="badge badge-pill <?php echo count($shehrullah_invoices) > 0 ? 'badge-danger' : 'badge-success'; ?>">
        <?php echo count($shehrullah_invoices); ?> Invoice<?php echo count($shehrullah_invoices) === 1 ? '' : 's'; ?>
      </span>
    </div>
    <div class="type-card-body">
      <?php if (!empty($shehrullah_invoices)): ?>
        <div class="miq-card-grid">
          <?php foreach ($shehrullah_invoices as $inv): ?>
            <?php renderMiqaatInvoiceCard($inv); ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fa-solid fa-calendar-minus"></i>
          <p>No Shehrullah Niyaz invoices found.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- GENERAL SECTION -->
  <div class="type-card theme-general">
    <div class="type-card-header">
      <h3 class="card-title"><i class="fa-solid fa-utensils"></i> General Niyaz Invoices</h3>
      <span class="badge badge-pill <?php echo count($general_invoices) > 0 ? 'badge-danger' : 'badge-success'; ?>">
        <?php echo count($general_invoices); ?> Invoice<?php echo count($general_invoices) === 1 ? '' : 's'; ?>
      </span>
    </div>
    <div class="type-card-body">
      <?php if (!empty($general_invoices)): ?>
        <div class="miq-card-grid">
          <?php foreach ($general_invoices as $inv): ?>
            <?php renderMiqaatInvoiceCard($inv); ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fa-solid fa-calendar-minus"></i>
          <p>No General Niyaz invoices found.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- LADIES SECTION -->
  <div class="type-card theme-ladies">
    <div class="type-card-header">
      <h3 class="card-title"><i class="fa-solid fa-person-dress"></i> Ladies Niyaz Invoices</h3>
      <span class="badge badge-pill <?php echo count($ladies_invoices) > 0 ? 'badge-danger' : 'badge-success'; ?>">
        <?php echo count($ladies_invoices); ?> Invoice<?php echo count($ladies_invoices) === 1 ? '' : 's'; ?>
      </span>
    </div>
    <div class="type-card-body">
      <?php if (!empty($ladies_invoices)): ?>
        <div class="miq-card-grid">
          <?php foreach ($ladies_invoices as $inv): ?>
            <?php renderMiqaatInvoiceCard($inv); ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fa-solid fa-calendar-minus"></i>
          <p>No Ladies Niyaz invoices found.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- MIQAAT INVOICE PAYMENT HISTORY MODAL -->
<div class="modal fade" id="miqaat-history-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left me-2"></i> Miqaat Invoice Payments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body p-0 position-relative">
        <div class="p-3">
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
                <th class="pl-3">#</th>
                <th>Payment ID</th>
                <th class="text-end">Amount (₹)</th>
                <th>Date</th>
                <th>Method</th>
                <th>Remarks</th>
                <th class="text-end">Receipt</th>
                <th class="text-end pr-3">Cumulative (₹)</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="8" class="text-center text-muted py-4">Select an invoice to load details.</td></tr>
            </tbody>
          </table>
        </div>
        <div class="loading-overlay d-none" id="miqaat-loading">
          <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
/**
 * Helper to render invoice cards
 */
function renderMiqaatInvoiceCard($inv) {
  $amt  = (float)($inv['amount'] ?? 0);
  $paid = (float)($inv['paid_amount'] ?? 0);
  $due  = max(0, $amt - $paid);
  
  $badgeClass = 'unpaid';
  $badgeText  = 'Unpaid';
  if ($paid > 0 && $due > 0) { 
    $badgeClass = 'partial'; 
    $badgeText = 'Partial'; 
  }
  if ($due <= 0.00001 && $amt > 0) { 
    $badgeClass = 'paid'; 
    $badgeText = 'Paid'; 
    $due = 0; 
  }

  $miqDesc = isset($inv['description']) ? trim((string)$inv['description']) : '';
  $miqType = isset($inv['miqaat_type']) ? trim((string)$inv['miqaat_type']) : '';
  ?>
  <div class="miq-card" data-miqaat-id="<?php echo (int)$inv['id']; ?>">
    <div class="miq-card-header">
      <h4 class="miq-card-title"><?php echo htmlspecialchars($inv['miqaat_name'] ?? ''); ?></h4>
      <span class="miq-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
    </div>
    
    <div class="miq-meta">
      <span>Invoice ID: <strong>#<?php echo (int)$inv['id']; ?></strong></span>
      <span>Date: <strong><?php echo !empty($inv['invoice_date']) ? date('d-M-Y', strtotime($inv['invoice_date'])) : '-'; ?></strong></span>
    </div>
    
    <div class="miq-amounts">
      <div class="amt">
        <div class="lbl">Amount</div>
        <div class="val text-primary">₹<?php echo format_inr_no_decimals($amt); ?></div>
      </div>
      <div class="amt">
        <div class="lbl">Paid</div>
        <div class="val text-success">₹<?php echo format_inr_no_decimals(min($paid, $amt)); ?></div>
      </div>
      <div class="amt">
        <div class="lbl">Due</div>
        <div class="val text-due">₹<?php echo format_inr_no_decimals($due); ?></div>
      </div>
    </div>
    
    <?php if ($miqDesc !== ''): ?>
      <div class="miq-desc"><?php echo htmlspecialchars($miqDesc); ?></div>
    <?php endif; ?>
    
    <div class="miq-history-btn-wrapper">
      <?php if ($due > 0): ?>
        <button class="btn btn-primary btn-sm pay-now-trigger" 
                data-invoice-id="<?php echo (int)$inv['id']; ?>"
                data-miqaat-name="<?php echo htmlspecialchars($inv['miqaat_name'] ?? ''); ?>"
                data-due-text="<?php echo format_inr_no_decimals($due); ?>"
                style="background-color: var(--gold); border-color: var(--gold); color: #fff; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; padding: 5px 12px; border-radius: 6px;">
          <i class="fa-solid fa-credit-card mr-1"></i> Pay Now
        </button>
      <?php else: ?>
        <div></div>
      <?php endif; ?>
      <button class="btn btn-outline-secondary btn-sm view-miqaat-history" data-invoice-id="<?php echo (int)$inv['id']; ?>" title="View Payment History">
        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Payment History
      </button>
    </div>
  </div>
  <?php
}
?>

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

  $(document).ready(function() {
    // Dynamic stacking for multiple Bootstrap modals
    let modalZBase = 1060;
    $(document).on('show.bs.modal', '.modal', function() {
      const $this = $(this);
      const $openModals = $('.modal.show').not($this);
      $openModals.addClass('dimmed-underlay');
      const newZ = modalZBase + ($openModals.length * 30);
      $this.css('z-index', newZ).removeClass('dimmed-underlay');
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
      let base = 1060;
      let i = 0;
      $('.modal.show').each(function() {
        $(this).css('z-index', base + (i * 30));
        i++;
      });
      if ($('.modal.show').length && $('.modal-backdrop').length === 0) {
        $('<div class="modal-backdrop fade show"></div>').appendTo(document.body).css('z-index', base - 10);
      } else if (!$('.modal.show').length) {
        $('.modal-backdrop').remove();
      }
    });

    // Miqaat invoice payment history fetch & modal
    $(document).on('click', '.view-miqaat-history', function(e) {
      e.preventDefault();
      const invoiceId = $(this).data('invoice-id');
      if (!invoiceId) return;
      $('#miqaat-history-modal').modal('show');
      $('#miqaat-loading').removeClass('d-none');
      $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>');
      
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
            $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-danger py-4">' + (res && res.message ? res.message : 'Failed to load history') + '</td></tr>');
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
          if(totalReceived > 0 && balance > 0){ 
            statusClass = 'miqaat-status-partial'; 
            statusText = 'PARTIAL'; 
          }
          if(balance <= 0 && totalAmt > 0){ 
            statusClass = 'miqaat-status-paid'; 
            statusText = 'PAID'; 
          }
          
          $('#mq-status-badge').removeClass('miqaat-status-paid miqaat-status-partial miqaat-status-unpaid').addClass(statusClass).text(statusText);
          const pct = totalAmt > 0 ? (totalReceived / totalAmt) * 100 : 0;
          $('#mq-paid-pct').text(pct.toFixed(1) + '%');
          $('#mq-progress-bar').css('width', pct.toFixed(2) + '%');
          
          const pays = res.payments || [];
          if(pays.length === 0){
            $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-muted py-4">No payments recorded.</td></tr>');
            return;
          }
          
          function fmtDate(d){
            if(!d) return '-';
            var dt = new Date(d);
            if(isNaN(dt.getTime())){
              var parts = (d + '').replace('T',' ').split(/[ :\-]/);
              if(parts.length >= 3){ 
                dt = new Date(parts[0], parseInt(parts[1],10)-1, parts[2]); 
              }
            }
            if(isNaN(dt.getTime())) return '-';
            var day=('0'+dt.getDate()).slice(-2);
            var mths=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return day+'-'+mths[dt.getMonth()]+'-'+dt.getFullYear();
          }

          function methodIcon(method){
            if(!method) return '';
            const m = method.toLowerCase();
            if(m.includes('upi')) return '<i class="fa-solid fa-mobile-screen-button mr-1" title="UPI"></i> ';
            if(m.includes('cash')) return '<i class="fa-solid fa-coins mr-1" title="Cash"></i> ';
            if(m.includes('card')) return '<i class="fa-solid fa-credit-card mr-1" title="Card"></i> ';
            if(m.includes('cheque') || m.includes('dd')) return '<i class="fa-regular fa-file-lines mr-1" title="Cheque"></i> ';
            if(m.includes('bank')) return '<i class="fa-solid fa-building-columns mr-1" title="Bank"></i> ';
            return '';
          }

          let rows = '';
          let cumulative = 0;
          pays.forEach(function(p, i){
            const amt = parseFloat(p.amount || 0) || 0;
            cumulative += amt;
            const pid = p.payment_id || p.id || '';
            const remarksSafe = (p.remarks ? $('<div/>').text(p.remarks).html() : '-');
            const remarksDisplay = remarksSafe.length > 50 ? remarksSafe.substring(0, 47) + '…' : remarksSafe;
            const methodDisp = (p.payment_method || '-');
            
            rows += '<tr>'+
              '<td class="pl-3">'+ (i+1) +'</td>'+
              '<td>'+ pid +'</td>'+
              '<td class="text-end text-success">'+ formatINRClient(amt) +'</td>'+
              '<td>'+ fmtDate(p.payment_date) +'</td>'+
              '<td>'+ methodIcon(methodDisp) + methodDisp +'</td>'+
              '<td title="'+ remarksSafe +'">'+ remarksDisplay +'</td>'+
              '<td class="text-end"><button class="btn btn-outline-primary miqaat-view-receipt btn-shrink" data-payment-id="'+ pid +'" title="View Receipt"><i class="fa-solid fa-file-pdf"></i></button></td>'+
              '<td class="text-end pr-3 fw-semibold">'+ formatINRClient(cumulative) +'</td>'+
            '</tr>';
          });
          $('#miqaat-history-table tbody').html(rows);
        },
        error: function(){
          $('#miqaat-history-table tbody').html('<tr><td colspan="8" class="text-center text-danger py-4">Server error.</td></tr>');
        },
        complete: function(){
          $('#mq-loading, #miqaat-loading').addClass('d-none');
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
        data: { id: pid, for: 2 },
        xhrFields: { responseType: 'blob' },
        success: function(blob) {
          if (!(blob instanceof Blob)) {
            alert('Unexpected response');
            return;
          }
          var pdfBlob = new Blob([blob], { type: 'application/pdf' });
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

    // Pay Now trigger logic
    $(document).on('click', '.pay-now-trigger', function(e) {
      e.preventDefault();
      const invoiceId = $(this).data('invoice-id');
      const miqaatName = $(this).data('miqaat-name');
      const dueText = $(this).data('due-text');
      
      $('#pay-modal-invoice-id').text('#' + invoiceId);
      $('#pay-modal-invoice-id-input').val(invoiceId);
      $('#pay-modal-miqaat-name').text(miqaatName);
      $('#pay-modal-amount-text').text(dueText);
      
      $('#miqaatPayModal').modal('show');
    });
  });
</script>

<!-- CONFIRMATION PAYMENT MODAL -->
<div class="modal fade" id="miqaatPayModal" tabindex="-1" aria-labelledby="miqaatPayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="miqaatPayModalLabel">Pay Miqaat Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form method="post" action="<?php echo base_url('payment/ccavenue_miqaat'); ?>">
        <div class="modal-body">
          <div class="mb-3">
            <p>You are paying the outstanding amount for the following invoice:</p>
            <table class="table table-sm table-bordered">
              <tr>
                <th>Invoice ID</th>
                <td id="pay-modal-invoice-id">-</td>
              </tr>
              <tr>
                <th>Miqaat Name</th>
                <td id="pay-modal-miqaat-name">-</td>
              </tr>
              <tr>
                <th>Outstanding Amount</th>
                <td class="font-weight-bold text-danger">₹<span id="pay-modal-amount-text">0.00</span></td>
              </tr>
            </table>
          </div>
          <input type="hidden" name="invoice_id" id="pay-modal-invoice-id-input" value="" />
          <div class="form-text text-muted">You will be redirected to the secure CCAvenue payment gateway to complete your transaction.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" style="background-color: var(--gold); border-color: var(--gold);">Proceed to Pay</button>
        </div>
      </form>
    </div>
  </div>
</div>
