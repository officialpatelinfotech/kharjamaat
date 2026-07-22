<?php
// Calculate totals for summary card
$total_amount = 0;
$total_paid = 0;
$invoice_count = 0;
$paid_count = 0;
$partial_count = 0;
$unpaid_count = 0;

if (!empty($general_contributions)) {
  foreach ($general_contributions as $gc) {
    $invoice_count++;
    $amt = (float)($gc['amount'] ?? 0);
    $paid = (float)($gc['amount_paid'] ?? 0);
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

  .summary-title-top { font-size: .67rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 2px; }
  .summary-title-main { font-family: 'Literata', Georgia, serif; font-size: 1.5rem; font-weight: 600; color: #fff; line-height: 1.15; }
  
  .badge-premium-pill { font-size: .65rem; font-weight: 700; border-radius: 40px; padding: 4px 10px; display: inline-flex; align-items: center; justify-content: center; border: 1.5px solid transparent; text-transform: uppercase; letter-spacing: .5px; }
  .badge-premium-pill.pending { background: rgba(239, 68, 68, 0.25); color: #fecaca; border-color: rgba(239, 68, 68, 0.35); }
  .badge-premium-pill.clear { background: rgba(34, 197, 94, 0.25); color: #bbf7d0; border-color: rgba(34, 197, 94, 0.35); }

  .summary-tile { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); border-radius: var(--radius); padding: 12px 14px; text-align: center; }
  .summary-tile .val { font-size: 1.35rem; font-weight: 800; color: #fff; line-height: 1.1; }
  .summary-tile .lbl { font-size: .56rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .8px; text-transform: uppercase; margin-top: 4px; }

  /* Premium Cards */
  .dash-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
    box-shadow: var(--shadow-sm); transition: box-shadow 0.2s, border-color 0.2s;
  }
  .dash-card:hover { box-shadow: var(--shadow); border-color: rgba(184, 134, 11, 0.25); }

  .dash-card-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 20px; border-bottom: 1.5px dashed var(--border);
  }
  .card-title { font-family: 'Literata', Georgia, serif; font-size: 0.95rem; font-weight: 600; color: var(--gold); display: flex; align-items: center; gap: 8px; }
  .card-title i { font-size: 0.9rem; }

  .badge-pill { font-size: .62rem; font-weight: 800; border-radius: 40px; padding: 4px 10px; text-transform: uppercase; letter-spacing: .4px; }
  .badge-pill.badge-danger { background: var(--red-bg); color: var(--red); border: 1.5px solid rgba(185,28,28,0.12); }
  .badge-pill.badge-success { background: var(--green-bg); color: var(--green); border: 1.5px solid rgba(26,102,69,0.12); }
  .badge-pill.badge-warning { background: var(--amber-bg); color: var(--amber); border: 1.5px solid rgba(180,83,9,0.12); }

  .dash-card-body { padding: 18px 20px; display: flex; flex-direction: column; }
  
  .stat-tile { background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 10px 12px; }
  .stat-tile .tile-label { font-size: .58rem; text-transform: uppercase; letter-spacing: .8px; color: var(--text-3); font-weight: 700; margin-bottom: 4px; }
  .stat-tile .tile-value { font-size: 0.9rem; font-weight: 700; color: var(--text-1); }
  .stat-tile .tile-value.red { color: var(--red); }
  .stat-tile .tile-value.green { color: var(--green); }
  .stat-tile .tile-value.blue { color: var(--blue); }
  .stat-tile .tile-value.amount-big { font-size: 1.35rem; font-weight: 800; }

  .breakdown-row { display: flex; gap: 8px; }
  .breakdown-row .stat-tile { flex: 1; }

  .info-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 12px; font-size: .72rem; color: var(--text-3); font-weight: 500; }
  .info-meta span strong { color: var(--text-2); }

  .btn-gold-outline {
    color: var(--gold); border: 1.5px solid var(--border); background: var(--surface);
    font-weight: 600; border-radius: 8px; transition: all 0.2s; font-size: 0.8rem; padding: 6px 14px;
    display: inline-flex; align-items: center; gap: 6px;
  }
  .btn-gold-outline:hover { background: var(--gold-muted); color: #78520a; border-color: var(--gold); text-decoration: none; }

  .btn-view {
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.72rem; font-weight: 700;
    color: var(--text-2); border: 1.5px solid var(--border); background: var(--surface);
    border-radius: 8px; padding: 5px 12px; transition: all 0.15s; cursor: pointer; text-transform: uppercase; letter-spacing: .5px;
    display: inline-flex; align-items: center; justify-content: center; gap: 4px;
  }
  .btn-view:hover { color: var(--gold); border-color: var(--gold); background: var(--gold-muted); text-decoration: none; }

  .progress-wrap { height: 6px; background: var(--border); border-radius: 4px; overflow: hidden; margin: 10px 0 6px; }
  .progress-bar { height: 100%; border-radius: 4px; }

  .progress-meta { font-size: 0.65rem; color: var(--text-3); font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 14px; }

  /* Modal Details */
  .modal-content { border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow-lg); }
  .modal-header { border-bottom: 1.5px dashed var(--border); }
  .modal-title { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.05rem; font-weight: 600; }
  .modal-footer { border-top: 1px solid var(--border); }
  
  .table thead th {
    font-size: .56rem; text-transform: uppercase; letter-spacing: .8px; font-weight: 700;
    color: var(--text-3); border-bottom: 1.5px solid var(--border); background: var(--surface-2); padding: 8px 12px;
  }
  .table tbody td { font-size: .78rem; color: var(--text-2); padding: 10px 12px; vertical-align: middle; border-bottom: 1px solid var(--border); }

  /* Mobile responsiveness */
  @media (max-width: 575.98px) {
    .breakdown-row { flex-direction: column; gap: 6px; }
    .breakdown-row .stat-tile { flex: none; width: 100%; }
    #gc-payments-modal .modal-dialog { margin: .5rem auto; max-width: 100%; }
    #gc-payments-modal .table thead { display: none; }
    #gc-payments-modal .table tbody tr {
      display: block; background: var(--surface); margin: 0 0 .75rem;
      border: 1.5px solid var(--border); border-radius: var(--radius-sm);
      box-shadow: var(--shadow-sm); padding: .5rem .65rem;
    }
    #gc-payments-modal .table tbody td {
      display: flex; padding: .35rem .25rem; border: none !important;
      font-size: .76rem; justify-content: space-between; gap: 8px;
    }
    #gc-payments-modal .table tbody td[data-label]::before {
      content: attr(data-label); font-weight: 700; color: var(--text-3);
      text-transform: uppercase; letter-spacing: .5px; font-size: .62rem;
    }
  }
</style>

<div class="container margintopcontainer pt-4 pb-5">
  
  <!-- Back Button -->
  <div class="row mb-3">
    <div class="col-12">
      <a href="<?php echo base_url('accounts'); ?>" class="btn-gold-outline"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <!-- Summary Card -->
  <div class="card summary-card mb-4">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col-12 col-md-5 mb-3 mb-md-0">
          <div class="summary-title-top">Overview</div>
          <h2 class="summary-title-main mb-3">Extra Contributions</h2>
          <span class="badge-premium-pill <?php echo $total_due > 0 ? 'pending' : 'clear'; ?>">
            <?php echo $total_due > 0 ? 'Pending' : 'Clear'; ?>
          </span>
        </div>
        <div class="col-12 col-md-7">
          <div class="row g-2">
            <div class="col-4">
              <div class="summary-tile">
                <div class="val">₹<?php echo format_inr_no_decimals($total_amount); ?></div>
                <div class="lbl">Invoiced</div>
              </div>
            </div>
            <div class="col-4">
              <div class="summary-tile">
                <div class="val">₹<?php echo format_inr_no_decimals($total_paid); ?></div>
                <div class="lbl">Paid</div>
              </div>
            </div>
            <div class="col-4">
              <div class="summary-tile">
                <div class="val">₹<?php echo format_inr_no_decimals($total_due); ?></div>
                <div class="lbl">Due</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  $niyaz_contribs = [];
  $thaali_contribs = [];
  if (!empty($general_contributions)) {
    foreach ($general_contributions as $gc) {
      if (($gc['fmb_type'] ?? '') === 'Niyaz') {
        $niyaz_contribs[] = $gc;
      } else {
        $thaali_contribs[] = $gc;
      }
    }
  }
  ?>

  <!-- Niyaz Contributions Section -->
  <h3 class="page-heading mb-3 text-center" style="font-size: 1.35rem; color: var(--gold); border-bottom: 2.5px solid var(--border); padding-bottom: 8px; margin-top: 25px; font-family: 'Literata', Georgia, serif; font-weight: 600;"><i class="fa-solid fa-hand-holding-heart mr-2" style="font-size: 1.15rem; color: var(--gold);"></i> Niyaz Contributions</h3>
  <div class="row mb-5">
    <?php if (!empty($niyaz_contribs)): ?>
      <?php foreach ($niyaz_contribs as $idx => $gc): ?>
        <?php
        $amount     = (float)$gc['amount'];
        $paid       = isset($gc['amount_paid']) ? (float)$gc['amount_paid'] : 0.0;
        $dueRaw     = isset($gc['total_due']) ? (float)$gc['total_due'] : max($amount - $paid, 0);
        $statusFlag = (int)$gc['payment_status'];
        $due        = $dueRaw < 0.00001 ? 0 : $dueRaw;
        $badgeClass = 'badge-danger'; $badgeText = 'Unpaid';
        if ($paid > 0 && $due > 0) { $badgeClass='badge-warning'; $badgeText='Partial'; }
        if ($statusFlag === 1 || $due === 0) { $badgeClass='badge-success'; $badgeText='Paid'; }
        
        $pct = $amount > 0 ? ($paid / $amount) * 100 : 0;
        ?>
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title">
                <i class="fa fa-gift"></i> #<?php echo $idx+1; ?> &bull; <?php echo htmlspecialchars($gc['contri_type']); ?>
              </span>
              <span class="badge-pill <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
            </div>
            <div class="dash-card-body">
              <div class="info-meta">
                <span>Year: <strong><?php echo htmlspecialchars($gc['contri_year']); ?></strong></span>
                <span>Type: <strong><?php echo htmlspecialchars($gc['fmb_type']); ?></strong></span>
                <span>Date: <strong><?php echo $gc['created_at'] ? date('d-M-Y',strtotime($gc['created_at'])) : '-'; ?></strong></span>
              </div>
              <div class="breakdown-row">
                <div class="stat-tile">
                  <div class="tile-label">Amount</div>
                  <div class="tile-value blue">₹<?php echo format_inr_no_decimals($amount); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Paid</div>
                  <div class="tile-value green">₹<?php echo format_inr_no_decimals($paid); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Due</div>
                  <div class="tile-value red">₹<?php echo format_inr_no_decimals($due); ?></div>
                </div>
              </div>
              <div class="progress-wrap"><div class="progress-bar" style="width:<?php echo number_format($pct,2); ?>%; background:linear-gradient(90deg,var(--green),var(--gold-light));"></div></div>
              <div class="progress-meta"><?php echo number_format($pct,1); ?>% Paid</div>
              
              <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="gap:8px;">
                <div>
                  <?php if (!empty($gc['description'])): ?>
                    <button class="btn btn-outline-primary btn-sm view-description" data-description="<?php echo htmlspecialchars($gc['description']); ?>" data-toggle="modal" data-target="#description-modal" title="View Description"><i class="fa fa-eye"></i> Description</button>
                  <?php else: ?>
                    <span style="font-size:0.75rem; color:var(--text-3); font-style:italic;">No description</span>
                  <?php endif; ?>
                </div>
                <button class="btn-view view-gc-payments"
                  data-fmbgc-id="<?php echo (int)$gc['id']; ?>"
                  data-amount="<?php echo number_format($amount,2,'.',''); ?>"
                  data-paid="<?php echo number_format($paid,2,'.',''); ?>"
                  data-due="<?php echo number_format($due,2,'.',''); ?>"
                  title="Payment History">
                  <i class="fa fa-history"></i> Payments
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-4">
        <div style="color:var(--text-3); font-size:0.9rem; font-weight:600;"><i class="fa fa-info-circle mb-2 text-muted"></i> No Niyaz contributions found.</div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Thaali Contributions Section -->
  <h3 class="page-heading mb-3 text-center" style="font-size: 1.35rem; color: var(--gold); border-bottom: 2.5px solid var(--border); padding-bottom: 8px; margin-top: 25px; font-family: 'Literata', Georgia, serif; font-weight: 600;"><i class="fa-solid fa-bowl-food mr-2" style="font-size: 1.15rem; color: var(--gold);"></i> Thaali Contributions</h3>
  <div class="row">
    <?php if (!empty($thaali_contribs)): ?>
      <?php foreach ($thaali_contribs as $idx => $gc): ?>
        <?php
        $amount     = (float)$gc['amount'];
        $paid       = isset($gc['amount_paid']) ? (float)$gc['amount_paid'] : 0.0;
        $dueRaw     = isset($gc['total_due']) ? (float)$gc['total_due'] : max($amount - $paid, 0);
        $statusFlag = (int)$gc['payment_status'];
        $due        = $dueRaw < 0.00001 ? 0 : $dueRaw;
        $badgeClass = 'badge-danger'; $badgeText = 'Unpaid';
        if ($paid > 0 && $due > 0) { $badgeClass='badge-warning'; $badgeText='Partial'; }
        if ($statusFlag === 1 || $due === 0) { $badgeClass='badge-success'; $badgeText='Paid'; }
        
        $pct = $amount > 0 ? ($paid / $amount) * 100 : 0;
        ?>
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title">
                <i class="fa fa-gift"></i> #<?php echo $idx+1; ?> &bull; <?php echo htmlspecialchars($gc['contri_type']); ?>
              </span>
              <span class="badge-pill <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>
            </div>
            <div class="dash-card-body">
              <div class="info-meta">
                <span>Year: <strong><?php echo htmlspecialchars($gc['contri_year']); ?></strong></span>
                <span>Type: <strong><?php echo htmlspecialchars($gc['fmb_type']); ?></strong></span>
                <span>Date: <strong><?php echo $gc['created_at'] ? date('d-M-Y',strtotime($gc['created_at'])) : '-'; ?></strong></span>
              </div>
              <div class="breakdown-row">
                <div class="stat-tile">
                  <div class="tile-label">Amount</div>
                  <div class="tile-value blue">₹<?php echo format_inr_no_decimals($amount); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Paid</div>
                  <div class="tile-value green">₹<?php echo format_inr_no_decimals($paid); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Due</div>
                  <div class="tile-value red">₹<?php echo format_inr_no_decimals($due); ?></div>
                </div>
              </div>
              <div class="progress-wrap"><div class="progress-bar" style="width:<?php echo number_format($pct,2); ?>%; background:linear-gradient(90deg,var(--green),var(--gold-light));"></div></div>
              <div class="progress-meta"><?php echo number_format($pct,1); ?>% Paid</div>
              
              <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="gap:8px;">
                <div>
                  <?php if (!empty($gc['description'])): ?>
                    <button class="btn btn-outline-primary btn-sm view-description" data-description="<?php echo htmlspecialchars($gc['description']); ?>" data-toggle="modal" data-target="#description-modal" title="View Description"><i class="fa fa-eye"></i> Description</button>
                  <?php else: ?>
                    <span style="font-size:0.75rem; color:var(--text-3); font-style:italic;">No description</span>
                  <?php endif; ?>
                </div>
                <button class="btn-view view-gc-payments"
                  data-fmbgc-id="<?php echo (int)$gc['id']; ?>"
                  data-amount="<?php echo number_format($amount,2,'.',''); ?>"
                  data-paid="<?php echo number_format($paid,2,'.',''); ?>"
                  data-due="<?php echo number_format($due,2,'.',''); ?>"
                  title="Payment History">
                  <i class="fa fa-history"></i> Payments
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-4">
        <div style="color:var(--text-3); font-size:0.9rem; font-weight:600;"><i class="fa fa-info-circle mb-2 text-muted"></i> No Thaali contributions found.</div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Description Modal -->
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle"></i> Contribution Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p id="desc-text" style="font-size:0.88rem; line-height:1.6; color:var(--text-2); white-space:pre-wrap;"></p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

  <!-- General Contribution Payments Modal -->
  <div class="modal fade" id="gc-payments-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-history"></i> Payment History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0">
          <div class="gcph-summary-wrapper" style="background:var(--surface-2); padding:16px 20px; border-bottom:1px solid var(--border);">
            <div style="font-size:0.83rem; font-weight:700; color:var(--text-1); margin-bottom:6px;">Extra Contribution Summary</div>
            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px;">
              <span style="font-size:0.75rem; color:var(--text-3);">Amount: <strong class="text-primary" id="gcph-amt">₹0</strong></span>
              <span style="font-size:0.75rem; color:var(--text-3);">Paid: <strong class="text-success" id="gcph-paid">₹0</strong></span>
              <span style="font-size:0.75rem; color:var(--text-3);">Due: <strong class="text-danger" id="gcph-due">₹0</strong></span>
            </div>
          </div>
          <div style="padding:14px 20px; max-height:280px; overflow-y:auto;">
            <div id="gc-payments-loading" class="text-center" style="font-size:0.83rem; color:var(--text-3);">Loading payments...</div>
            <div id="gc-payments-empty" class="text-center d-none" style="font-size:0.83rem; color:var(--text-3);">No payment records found.</div>
            <table class="table table-striped align-middle mb-0 d-none" id="gc-payments-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Method</th>
                  <th>Receipt</th>
                  <th class="text-right">Amount (₹)</th>
                </tr>
              </thead>
              <tbody id="gc-payments-tbody"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button></div>
      </div>
    </div>
  </div>

</div>

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

  function pad2(n){ return String(n).padStart(2,'0'); }
  function fmtGregDateDMY(d){
    try{ const dt=new Date(String(d)+'T00:00:00'); if(isNaN(dt.getTime())) return String(d); return pad2(dt.getDate())+'-'+pad2(dt.getMonth()+1)+'-'+dt.getFullYear(); }
    catch(e){ return String(d); }
  }

  $(document).on('click', '.view-description', function() {
    const desc = String($(this).data('description') || '').trim();
    $('#desc-text').text(desc);
  });

  $(document).on('click', '.view-gc-payments', function() {
    const fmbgc_id = $(this).data('fmbgc-id');
    const amt = parseFloat($(this).data('amount')) || 0.0;
    const paid = parseFloat($(this).data('paid')) || 0.0;
    const due = parseFloat($(this).data('due')) || 0.0;

    $('#gcph-amt').text('₹' + formatINRClient(amt));
    $('#gcph-paid').text('₹' + formatINRClient(paid));
    $('#gcph-due').text('₹' + formatINRClient(due));

    $('#gc-payments-loading').removeClass('d-none');
    $('#gc-payments-empty').addClass('d-none');
    $('#gc-payments-table').addClass('d-none');
    $('#gc-payments-tbody').empty();
    $('#gc-payments-modal').modal('show');

    $.ajax({
      url: '<?php echo base_url('accounts/gc_payment_history'); ?>',
      type: 'POST',
      dataType: 'json',
      data: { fmbgc_id: fmbgc_id },
      success: function(res) {
        $('#gc-payments-loading').addClass('d-none');
        if (!res || res.success === false) {
          $('#gc-payments-empty').removeClass('d-none').text(res.message || 'Failed to load payments.');
          return;
        }
        const history = res.payments || res.history || [];
        if (!history.length) {
          $('#gc-payments-empty').removeClass('d-none');
          return;
        }
        $('#gc-payments-table').removeClass('d-none');
        history.forEach(function(p) {
          const dateStr = p.payment_date ? fmtGregDateDMY(p.payment_date) : '-';
          const methodStr = p.payment_method ? String(p.payment_method) : '-';
          const idVal = p.payment_id || p.id;
          let receiptCell = '-';
          if (idVal) {
            receiptCell = '<a href="#" class="view-invoice" data-payment-id="' + idVal + '"><i class="fa fa-file-pdf"></i> Receipt</a>';
          }
          const tr = $('<tr></tr>');
          tr.append($('<td></td>').text(dateStr));
          tr.append($('<td></td>').text(methodStr));
          tr.append($('<td></td>').html(receiptCell));
          tr.append($('<td class="text-right text-success font-weight-bold"></td>').text('₹' + formatINRClient(p.amount)));
          $('#gc-payments-tbody').append(tr);
        });
      },
      error: function() {
        $('#gc-payments-loading').addClass('d-none');
        $('#gc-payments-empty').removeClass('d-none').text('Connection error.');
      }
    });
  });

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");
    $.ajax({ url:"<?php echo base_url('common/generate_pdf'); ?>", type:"POST", data:{id:paymentId,for:3}, xhrFields:{responseType:'blob'},
      success:function(response){ var url=window.URL.createObjectURL(new Blob([response],{type:"application/pdf"})); window.open(url,"_blank"); },
      error:function(){ alert("Failed to generate invoice PDF"); }
    });
  });
</script>
