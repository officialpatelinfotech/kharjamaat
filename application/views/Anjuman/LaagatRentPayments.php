<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
    --green:       #10b981;
    --green-bg:    #ecfdf5;
    --green-border:#bbf7d0;
    --red:         #ef4444;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #3b82f6;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #f59e0b;
    --amber-bg:    #fffbeb;
    --amber-border:#fde68a;
    --radius-sm:   8px;
    --radius:      12px;
    --radius-lg:   16px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.05);
    --shadow:      0 4px 16px rgba(184,134,11,0.06);
    --shadow-lg:   0 10px 30px rgba(184,134,11,0.12);
  }

  /* Scoped styles */
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

  /* Summary Grid */
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }
  .summary-tile {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
  }
  .summary-tile::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 4px;
  }
  .tile-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-3);
    margin-bottom: 4px;
  }
  .tile-value {
    font-size: 1.4rem;
    font-weight: 800;
    line-height: 1.2;
    font-variant-numeric: tabular-nums;
  }
  
  .tile-jamaat::before { background: var(--green); }
  .tile-jamaat .tile-value { color: var(--green); }
  .tile-sarkaar::before { background: var(--blue); }
  .tile-sarkaar .tile-value { color: var(--blue); }
  .tile-total::before { background: var(--gold); }
  .tile-total .tile-value { color: var(--gold-deep); }
  .tile-paid::before { background: #10b981; }
  .tile-paid .tile-value { color: #10b981; }
  .tile-due::before { background: var(--red); }
  .tile-due .tile-value { color: var(--red); }

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
  .premium-table tbody tr:last-child td {
    border-bottom: none;
  }
  .premium-table tbody tr:hover {
    background: #fdfbf5;
  }

  .type-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    display: inline-block;
  }
  .badge-rent {
    background: var(--blue-bg);
    color: var(--blue);
  }
  .badge-laagat {
    background: var(--amber-bg);
    color: var(--amber);
  }

  /* Table Actions */
  .btn-receive {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: #fff;
    font-weight: 700;
    font-size: 0.78rem;
    padding: 8px 14px;
    border-radius: 8px;
    transition: all 0.2s;
    box-shadow: 0 2px 6px rgba(16,185,129,0.15);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }
  .btn-receive:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(16,185,129,0.25);
    text-decoration: none;
  }
  .btn-receive-disabled {
    background: #e2e8f0;
    border: none;
    color: #94a3b8;
    font-weight: 700;
    font-size: 0.78rem;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: not-allowed;
    width: 100%;
    display: inline-block;
  }
  .btn-history-view {
    background: #ffffff;
    border: 1.5px solid var(--border);
    color: var(--text-2);
    font-weight: 700;
    font-size: 0.78rem;
    padding: 8px 14px;
    border-radius: 8px;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }
  .btn-history-view:hover {
    background: var(--surface-2);
    color: var(--text-1);
    border-color: var(--gold);
    text-decoration: none;
  }

  /* Modals */
  .modal-content {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .modal-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 18px 24px;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold-deep);
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
  }
  .modal-header .close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
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
  }
  .modal-header .close:hover {
    background: var(--red-bg);
    border-color: var(--red-border);
    color: var(--red);
  }
  .modal-body {
    padding: 24px;
  }
  .modal-footer {
    background: var(--surface-2);
    border-top: 1px solid var(--border);
    padding: 14px 24px;
  }
  
  .modal .form-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 6px;
    display: block;
  }
  .modal .form-control {
    height: 42px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.92rem;
    font-weight: 600;
  }
  .modal .form-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12);
  }
  .modal .input-group-text {
    border: 1.5px solid var(--border);
    border-right: none;
    background: var(--surface-2);
    font-weight: 600;
    color: var(--text-2);
  }
  
  /* Details List in modal */
  .modal-detail-row {
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
  }
  .modal-detail-row:last-child {
    border-bottom: none;
  }
  
  /* Payments summary box */
  .payment-summary-box {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 20px;
  }
  
  /* History Modal Buttons */
  .btn-pdf-view {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1.5px solid var(--blue-border);
    background: var(--blue-bg);
    color: var(--blue);
    transition: all 0.2s;
    padding: 0;
  }
  .btn-pdf-view:hover {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
  }

  .btn-pdf-delete {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1.5px solid var(--red-border);
    background: var(--red-bg);
    color: var(--red);
    transition: all 0.2s;
    padding: 0;
  }
  .btn-pdf-delete:hover {
    background: var(--red);
    color: #fff;
    border-color: var(--red);
  }

  .btn-modal-cancel {
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-weight: 700;
    font-size: 0.85rem;
    transition: all .15s;
  }
  .btn-modal-cancel:hover {
    background: var(--surface-2);
  }
  .btn-modal-save {
    padding: 10px 22px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
    transition: all .15s;
    box-shadow: 0 2px 8px rgba(184,134,11,0.2);
  }
  .btn-modal-save:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
  }
  
  .history-table th {
    background: var(--surface-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-2);
  }
  .history-table td {
    font-size: 0.85rem;
    color: var(--text-2);
  }
</style>

<div class="container-fluid px-md-5 margintopcontainer pt-5 pb-5 page-wrap">
  <?php
  $total_jmt_amt = 0;
  $total_sar_amt = 0;
  $total_inv_amt = 0;
  $total_paid_amt = 0;
  $total_due_amt = 0;
  if (!empty($invoices)) {
    foreach ($invoices as $inv) {
      $isDepositOnly = ($inv['charge_type'] === 'rent' && (float)$inv['master_amount'] <= 0.0001 && (float)$inv['deposit_amount'] > 0);
      $tAmt = $isDepositOnly ? (float)$inv['deposit_amount'] : (float)$inv['master_amount'];
      if ($inv['charge_type'] !== 'rent') {
        $jAmt = (float)$inv['jamaat_amount'];
        $sAmt = (float)$inv['sarkaar_amount'];
        if ($jAmt == 0.00 && $sAmt == 0.00 && $tAmt > 0.00) {
          $jAmt = $tAmt;
        }
        $total_jmt_amt += $jAmt;
        $total_sar_amt += $sAmt;
      }
      $total_inv_amt += $tAmt;
      $total_paid_amt += (float)$inv['paid_amount'];
      $total_due_amt += ($tAmt - (float)$inv['paid_amount']);
    }
  }
  ?>
  
  <?php
  $is_laagat = isset($module_type) && $module_type === 'laagat';
  $is_rent = isset($module_type) && $module_type === 'rent';
  $is_deposit = isset($module_type) && $module_type === 'deposit';
  $back_url = $is_laagat ? base_url('anjuman/laagat') : ($is_rent || $is_deposit ? base_url('anjuman/rent') : base_url('anjuman/laagat_rent'));
  $heading = $is_laagat ? "Receive Laagat Payment" : ($is_rent ? "Receive Rent Payment" : ($is_deposit ? "Receive Deposit Payment" : "Receive Laagat & Rent Payment"));
  if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2) {
    $back_url = base_url('amilsaheb');
    $heading = $is_laagat ? "Laagat Invoices" : ($is_rent ? "Rent Invoices" : ($is_deposit ? "Deposit Invoices" : "Laagat & Rent Invoices"));
  }
  ?>

  <!-- Header -->
  <div class="page-header">
    <a href="<?= $back_url ?>" class="btn-back-nav" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 class="page-title"><?= $heading ?></h1>
    <div style="width: 42px;"></div>
  </div>

  <!-- Metrics Grid -->
  <div class="summary-grid">
    <?php if (!$is_rent && !$is_deposit): ?>
      <div class="summary-tile tile-jamaat">
        <span class="tile-label">Total Jamaat</span>
        <span class="tile-value">₹<?= format_inr($total_jmt_amt, 0) ?></span>
      </div>
      <div class="summary-tile tile-sarkaar">
        <span class="tile-label">Total Sarkaar</span>
        <span class="tile-value">₹<?= format_inr($total_sar_amt, 0) ?></span>
      </div>
    <?php endif; ?>
    <div class="summary-tile tile-total">
      <span class="tile-label">Total Amount</span>
      <span class="tile-value">₹<?= format_inr($total_inv_amt, 0) ?></span>
    </div>
    <div class="summary-tile tile-paid">
      <span class="tile-label">Total Paid</span>
      <span class="tile-value">₹<?= format_inr($total_paid_amt, 0) ?></span>
    </div>
    <div class="summary-tile tile-due">
      <span class="tile-label">Total Due</span>
      <span class="tile-value">₹<?= format_inr($total_due_amt, 0) ?></span>
    </div>
  </div>

  <!-- Alerts -->
  <?php if ($this->session->flashdata('laagat_flash_success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('laagat_flash_success') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('laagat_flash_error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('laagat_flash_error') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <!-- Filters Card -->
  <?php
    $form_action = base_url('anjuman/laagat_rent_payments');
    if (isset($module_type)) {
      $form_action .= '?charge_type=' . $module_type;
    }
  ?>
  <div class="filter-card">
    <div class="card-body">
      <form method="GET" action="<?= $form_action; ?>" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Hijri Year</label>
          <select name="year" class="form-control">
            <option value="">All Years</option>
            <?php foreach ($hijri_years as $y): ?>
              <option value="<?= $y ?>" <?= ($filters['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">ITS ID / Member</label>
          <input type="text" name="its_id" class="form-control" placeholder="ITS ID" value="<?= $filters['its_id'] ?>">
        </div>
        <?php if (isset($module_type)): ?>
          <input type="hidden" name="charge_type" value="<?= htmlspecialchars($module_type) ?>" />
        <?php else: ?>
          <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="charge_type" class="form-control">
              <option value="">All Types</option>
              <option value="laagat" <?= (($filters['charge_type'] ?? '') == 'laagat') ? 'selected' : '' ?>>Laagat</option>
              <option value="rent" <?= (($filters['charge_type'] ?? '') == 'rent') ? 'selected' : '' ?>>Rent</option>
            </select>
          </div>
        <?php endif; ?>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-filter flex-fill mr-2"><i class="fa-solid fa-magnifying-glass me-1"></i> Filter</button>
          <a href="<?= $form_action; ?>" class="btn btn-clear flex-fill d-inline-flex align-items-center justify-content-center">Clear</a>
        </div>
      </form>
    </div>
  </div>

   <!-- Invoices Table Card -->
  <div class="table-card">
    <div class="table-responsive">
      <table class="table premium-table table-hover">
        <thead>
          <tr>
            <th>Sr. No.</th>
            <th>Invoice Date</th>
            <th>ITS ID</th>
            <th>Member Name</th>
            <?php if (!isset($module_type)): ?>
              <th>Charge Type</th>
            <?php endif; ?>
            <th>Raza Id</th>
            <th>Raza</th>
            <?php if ($is_deposit): ?>
              <th class="text-end">Deposit Amount</th>
            <?php else: ?>
              <?php if (!$is_rent): ?>
                <th class="text-end">Jmt. Amount</th>
                <th class="text-end">Sar. Amount</th>
              <?php endif; ?>
              <th class="text-end">Rent Amount</th>
            <?php endif; ?>
            <th class="text-end">Paid Amount</th>
            <th class="text-end">Due Amount</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($invoices)): ?>
            <?php $sr = 1;
            foreach ($invoices as $inv): ?>
              <?php
              $balance = $is_deposit ? ((float)$inv['deposit_amount'] - (float)$inv['paid_amount']) : ((float)$inv['master_amount'] - (float)$inv['paid_amount']);
              ?>
              <tr>
                <td><?= $sr++ ?></td>
                <td class="text-nowrap"><?= date('d-m-Y', strtotime($inv['created_at'])) ?></td>
                <td><?= $inv['ITS_ID'] ?></td>
                <td class="fw-bold text-dark"><?= $inv['Full_Name'] ?></td>
                <?php if (!isset($module_type)): ?>
                <td>
                  <span class="type-badge <?= ($inv['charge_type'] == 'rent') ? 'badge-rent' : 'badge-laagat' ?>">
                    <?= $inv['charge_type'] ?>
                  </span>
                </td>
                <?php endif; ?>
                <td class="text-nowrap">R#<?= $inv['generated_raza_id'] ?></td>
                <td><?= htmlspecialchars($inv['title']) ?></td>
                <?php
                $jAmt = (float)$inv['jamaat_amount'];
                $sAmt = (float)$inv['sarkaar_amount'];
                $tAmt = (float)$inv['master_amount'];
                if ($jAmt == 0.00 && $sAmt == 0.00 && $tAmt > 0.00) {
                  $jAmt = $tAmt;
                }
                $isRent = ($inv['charge_type'] === 'rent');
                ?>
                <?php if ($is_deposit): ?>
                  <td class="text-end text-warning fw-bold">₹<?= format_inr((float)$inv['deposit_amount'], 0) ?></td>
                <?php else: ?>
                  <?php if (!$is_rent): ?>
                    <td class="text-end text-success fw-bold"><?= $isRent ? '-' : '₹' . format_inr($jAmt, 0) ?></td>
                    <td class="text-end text-info fw-bold"><?= $isRent ? '-' : '₹' . format_inr($sAmt, 0) ?></td>
                  <?php endif; ?>
                  <td class="text-end text-primary fw-bold">₹<?= format_inr($tAmt, 0) ?></td>
                <?php endif; ?>
                <td class="text-end text-success fw-bold">₹<?= format_inr($inv['paid_amount'], 0) ?></td>
                <td class="text-end fw-bold <?= ($balance > 0) ? 'text-danger' : 'text-muted' ?>">
                  ₹<?= format_inr($balance, 0) ?>
                </td>
                <td class="text-center text-nowrap">
                  <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                    <?php if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] != 2): ?>
                      <?php if ($balance > 0): ?>
                        <button type="button" class="btn-receive btn-sm" title="Receive Payment" data-toggle="modal" data-target="#paymentModal<?= $inv['id'] ?>">
                          <i class="fa-solid fa-indian-rupee-sign"></i> Receive
                        </button>
                      <?php else: ?>
                        <span class="btn-receive-disabled btn-sm text-center">Paid</span>
                      <?php endif; ?>
                    <?php endif; ?>

                    <button type="button" class="btn-history-view btn-sm" title="View History" onclick="showHistory(<?= $inv['id'] ?>, '<?= htmlspecialchars($inv['Full_Name']) ?>')">
                      <i class="fa-solid fa-history"></i> History
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <?php
              $colspan = 9;
              if (!isset($module_type)) $colspan++;
              if (!$is_rent && !$is_deposit) $colspan += 2;
            ?>
            <tr>
              <td colspan="<?= $colspan ?>" class="text-center py-5 text-muted">
                <i class="fa-solid fa-magnifying-glass fa-2x mb-3 opacity-25"></i><br>
                No invoices found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-history text-warning mr-2"></i>Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-4 d-flex justify-content-between align-items-center bg-light p-3 rounded border">
          <div>
            <span class="text-muted small text-uppercase fw-bold d-block">Member Name</span>
            <span class="fw-bold text-dark h5 mb-0" id="history_member_name"></span>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table history-table table-bordered mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Method</th>
                <th class="text-end">Amount</th>
                <th>Remarks</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="history_table_body">
              <!-- Content loaded via AJAX -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modals outside the table -->
<?php if (!empty($invoices)): ?>
  <?php foreach ($invoices as $inv): ?>
    <?php
    $balance = $is_deposit ? ((float)$inv['deposit_amount'] - (float)$inv['paid_amount']) : ((float)$inv['master_amount'] - (float)$inv['paid_amount']);
    if ($balance > 0):
    ?>
      <div class="modal fade" id="paymentModal<?= $inv['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <form action="<?= base_url('anjuman/laagat_rent_save_payment'); ?>" method="POST" class="w-100">
            <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-cash-register text-success mr-2"></i>Receive <?= $is_deposit ? 'Deposit' : 'Payment' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-left">
                
                <!-- Details Grid -->
                <div class="mb-4 p-3 rounded bg-light border">
                  <div class="row g-2">
                    <div class="col-6 mb-2">
                      <span class="text-muted small d-block">ITS ID</span>
                      <span class="fw-bold text-dark"><?= $inv['ITS_ID'] ?></span>
                    </div>
                    <div class="col-6 mb-2">
                      <span class="text-muted small d-block">Member Name</span>
                      <span class="fw-bold text-dark"><?= $inv['Full_Name'] ?></span>
                    </div>
                    <div class="col-6 mb-2">
                      <span class="text-muted small d-block">Charge Type</span>
                      <span class="type-badge <?= ($inv['charge_type'] == 'rent') ? ($is_deposit ? 'badge-rent text-warning bg-warning-light border-warning' : 'badge-rent') : 'badge-laagat' ?>">
                        <?= $inv['charge_type'] . ($is_deposit ? ' (Deposit)' : '') ?>
                      </span>
                    </div>
                    <div class="col-6 mb-2">
                      <span class="text-muted small d-block">Raza Details</span>
                      <span class="text-dark">R#<?= $inv['generated_raza_id'] ?> — <?= htmlspecialchars($inv['title']) ?></span>
                    </div>
                    
                    <?php if ($is_deposit): ?>
                      <div class="col-12 mt-1">
                        <span class="text-muted small d-block">Deposit Amount</span>
                        <span class="fw-bold text-warning">₹<?= format_inr((float)$inv['deposit_amount'], 0) ?></span>
                      </div>
                    <?php elseif ($is_rent): ?>
                      <div class="col-12 mt-1">
                        <span class="text-muted small d-block">Invoice Amount</span>
                        <span class="fw-bold text-dark">₹<?= format_inr($inv['master_amount'], 0) ?></span>
                      </div>
                    <?php else: ?>
                      <?php
                      $jAmt = (float)$inv['jamaat_amount'];
                      $sAmt = (float)$inv['sarkaar_amount'];
                      $tAmt = (float)$inv['master_amount'];
                      if ($jAmt == 0.00 && $sAmt == 0.00 && $tAmt > 0.00) {
                        $jAmt = $tAmt;
                      }
                      ?>
                      <div class="col-4 mt-1">
                        <span class="text-muted small d-block">Jmt. Amount</span>
                        <span class="fw-bold text-success">₹<?= format_inr($jAmt, 0) ?></span>
                      </div>
                      <div class="col-4 mt-1">
                        <span class="text-muted small d-block">Sar. Amount</span>
                        <span class="fw-bold text-info">₹<?= format_inr($sAmt, 0) ?></span>
                      </div>
                      <div class="col-4 mt-1">
                        <span class="text-muted small d-block">Total Amount</span>
                        <span class="fw-bold text-primary">₹<?= format_inr($tAmt, 0) ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Dues Summary Box -->
                <div class="payment-summary-box d-flex justify-content-between align-items-center">
                  <div>
                    <span class="text-muted small d-block">Paid Amount</span>
                    <span class="text-success fw-bold h5 mb-0">₹<?= format_inr($inv['paid_amount'], 0) ?></span>
                  </div>
                  <div class="text-right">
                    <span class="text-muted small d-block">Due Balance</span>
                    <span class="text-danger fw-bold h5 mb-0">₹<?= format_inr($balance, 0) ?></span>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">Payment Amount</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₹</span>
                    </div>
                    <input type="number" name="amount" class="form-control form-control-lg fw-bold text-success" required max="<?= $balance ?>" value="<?= $balance ?>">
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-6">
                    <div class="mb-3">
                      <label class="form-label">Payment Date</label>
                      <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="mb-3">
                      <label class="form-label">Method</label>
                      <select name="payment_method" class="form-control">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="NEFT">NEFT</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="mb-0">
                  <label class="form-label">Remarks</label>
                  <textarea name="remarks" class="form-control text-left" rows="2" placeholder="Optional notes..."></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-modal-save">Save Payment</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>

<script>
  function showHistory(invoiceId, name) {
    $('#history_member_name').text(name);
    $('#history_table_body').html('<tr><td colspan="5" class="text-center font-italic text-muted py-4"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Loading history...</td></tr>');
    $('#historyModal').modal('show');

    $.ajax({
      url: '<?= base_url("anjuman/get_laagat_payment_history"); ?>',
      type: 'POST',
      data: {
        invoice_id: invoiceId
      },
      dataType: 'json',
      success: function(response) {
        let html = '';
        if (response && response.length > 0) {
          response.forEach(function(item) {
            let d = new Date(item.payment_date);
            let day = ('0' + d.getDate()).slice(-2);
            let month = ('0' + (d.getMonth() + 1)).slice(-2);
            let year = d.getFullYear();
            let dateStr = day + '-' + month + '-' + year;

            html += '<tr>' +
              '<td class="font-weight-bold">' + dateStr + '</td>' +
              '<td><span class="badge badge-light border" style="padding: 4px 8px;">' + (item.payment_method || '-') + '</span></td>' +
              '<td class="text-end fw-bold text-success">₹' + parseFloat(item.amount).toLocaleString('en-IN', {
                minimumFractionDigits: 0
              }) + '</td>' +
              '<td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + (item.remarks || '-') + '</td>' +
              '<td class="text-center">' +
              '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">' +
              '<button type="button" class="btn-pdf-view view-invoice" data-payment-id="' + item.id + '" title="PDF Receipt">' +
              '<i class="fa-solid fa-file-pdf"></i>' +
              '</button>' +
              <?php if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] != 2): ?> 
                '<button type="button" class="btn-pdf-delete" title="Delete Payment" onclick="deleteHistoryPayment(' + item.id + ', ' + invoiceId + ')">' +
                '<i class="fa-solid fa-trash"></i>' +
                '</button>' +
              <?php endif; ?> 
              '</div>' +
              '</td>' +
              '</tr>';
          });
        } else {
          html = '<tr><td colspan="5" class="text-center text-muted py-4">No payments recorded for this invoice yet.</td></tr>';
        }
        $('#history_table_body').html(html);
      },
      error: function() {
        $('#history_table_body').html('<tr><td colspan="5" class="text-center text-danger py-4">Error fetching history. Please try again.</td></tr>');
      }
    });
  }

  function deleteHistoryPayment(paymentId, invoiceId) {
    if (confirm('Are you sure you want to delete this payment record?')) {
      $.ajax({
        url: '<?= base_url("anjuman/laagat_rent_delete_payment"); ?>',
        type: 'POST',
        data: {
          payment_id: paymentId
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            let memberName = $('#history_member_name').text();
            showHistory(invoiceId, memberName);
            window.location.reload();
          } else {
            alert(response.message || 'Error deleting payment.');
          }
        },
        error: function() {
          alert('An error occurred. Please try again.');
        }
      });
    }
  }

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 8,
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
</script>