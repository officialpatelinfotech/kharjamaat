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
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
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
    font-size: 1.6rem;
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

  /* Badges & Pills */
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

  /* Table Buttons */
  .btn-action-edit {
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
  }
  .btn-action-edit:hover {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
  }
  
  .btn-action-delete {
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
    outline: none;
  }
  .btn-action-delete:hover {
    background: var(--red);
    color: #fff;
    border-color: var(--red);
  }

  .btn-action-print {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1.5px solid var(--green-border);
    background: var(--green-bg);
    color: var(--green);
    transition: all 0.2s;
  }
  .btn-action-print:hover {
    background: var(--green);
    color: #fff;
    border-color: var(--green);
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
    height: 48px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.95rem;
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
</style>

<div class="container-fluid px-md-5 margintopcontainer pt-5 pb-5 page-wrap">
    <?php 
        $total_jmt_amt = 0;
        $total_sar_amt = 0;
        $total_inv_amt = 0;
        $total_returned_amt = 0;
        if (!empty($invoices)) {
            foreach($invoices as $inv) {
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
                if ($isDepositOnly && (int)($inv['is_returned'] ?? 0) === 1) {
                    $total_returned_amt += $tAmt;
                }
            }
        }
    ?>
    <?php 
        $is_laagat = isset($module_type) && $module_type === 'laagat';
        $is_rent = isset($module_type) && $module_type === 'rent';
        $is_deposit = isset($module_type) && $module_type === 'deposit';
        $back_url = $is_laagat ? base_url('anjuman/laagat') : ($is_rent || $is_deposit ? base_url('anjuman/rent') : base_url('anjuman/laagat_rent'));
        $title = $is_laagat ? 'Update Laagat Invoices' : ($is_rent ? 'Update Rent Invoices' : ($is_deposit ? 'Update Deposit Invoices' : 'Update Laagat & Rent Invoices'));
    ?>
    
    <!-- Header -->
    <div class="page-header">
        <a href="<?= $back_url; ?>" class="btn-back-nav" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="page-title"><?= $title ?></h1>
        <div style="width: 42px;"></div>
    </div>

    <!-- Summary Metrics -->
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
        <?php if ($is_rent && isset($rent_bifurcation)): ?>
            <?php
                $ladies_total = (float)($rent_bifurcation['Ladies'] ?? 0);
                $jamaat_total = $total_inv_amt - $ladies_total;
            ?>
            <div class="summary-tile tile-jamaat">
                <span class="tile-label">Jamaat Share</span>
                <span class="tile-value">₹<?= format_inr($jamaat_total, 0) ?></span>
            </div>
            <div class="summary-tile tile-sarkaar">
                <span class="tile-label">Ladies Share</span>
                <span class="tile-value">₹<?= format_inr($ladies_total, 0) ?></span>
            </div>
        <?php endif; ?>
        <div class="summary-tile tile-total">
            <span class="tile-label">Total Amount</span>
            <span class="tile-value">₹<?= format_inr($total_inv_amt, 0) ?></span>
        </div>
        <?php if ($is_deposit): ?>
            <div class="summary-tile tile-sarkaar">
                <span class="tile-label">Total Returned</span>
                <span class="tile-value" style="color: var(--blue);">₹<?= format_inr($total_returned_amt, 0) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Alerts -->
    <?php if($this->session->flashdata('laagat_flash_success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('laagat_flash_success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('laagat_flash_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('laagat_flash_error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Filters Card -->
    <div class="filter-card">
        <div class="card-body">
            <?php
                $form_action = base_url('anjuman/laagat_rent_invoices');
                if (isset($module_type)) {
                    $form_action .= '?charge_type=' . $module_type;
                }
            ?>
            <form method="GET" action="<?= $form_action; ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Hijri Year</label>
                    <select name="year" class="form-control">
                        <option value="">All Years</option>
                        <?php foreach($hijri_years as $y): ?>
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
                    <button type="submit" class="btn btn-filter flex-fill"><i class="fa-solid fa-magnifying-glass me-1"></i> Filter</button>
                    <a href="<?= $form_action; ?>" class="btn btn-clear flex-fill d-inline-flex align-items-center justify-content-center">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table Card -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table premium-table table-hover" id="invoicesTable">
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
                            <th class="text-end">Total Amount</th>
                        <?php endif; ?>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($invoices)): ?>
                        <?php $sr = 1; foreach($invoices as $inv): ?>
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
                                <td class="text-center text-nowrap">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <?php if ($is_deposit && (int)($inv['is_returned'] ?? 0) === 1): ?>
                                            <span class="badge bg-success text-white py-1 px-2 fw-bold mr-2" style="font-size: 0.72rem;">Returned</span>
                                        <?php endif; ?>
                                        <?php if ($inv['charge_type'] === 'rent'): ?>
                                            <a href="<?= base_url('common/generate_pdf?id=' . $inv['id'] . '&for=9') ?>" target="_blank" class="btn-action-print mr-2" title="Print Resource Bill">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn-action-edit mr-2" title="Edit Invoice" 
                                                onclick="editInvoice(<?= htmlspecialchars(json_encode($inv)) ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="<?= base_url('anjuman/laagat_rent_invoice_delete'); ?>" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                            <button type="submit" class="btn-action-delete" title="Delete Invoice">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php
                            $colspan = 8;
                            if (!isset($module_type)) $colspan++;
                            if (!$is_rent && !$is_deposit) $colspan += 2;
                        ?>
                        <tr>
                            <td colspan="<?= $colspan ?>" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-2x mb-3 opacity-25"></i><br>
                                No invoices found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="<?= base_url('anjuman/laagat_rent_invoice_save'); ?>" method="POST" class="w-100">
            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 10px 30px rgba(0,0,0,0.2)">
                <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background:linear-gradient(135deg, #78520a 0%, #b8860b 100%);color:#ffffff;border-bottom:none">
                    <h5 class="modal-title font-weight-bold m-0" id="edit_modal_title" style="font-size:1.1rem;color:#ffffff !important">
                        <i class="fa-solid fa-file-pen me-2"></i>Update Invoice & Rent Details
                    </h5>
                    <button type="button" class="btn-close-modal" data-dismiss="modal" aria-label="Close" style="background:none!important;border:none!important;color:#ffffff!important;font-size:1.6rem!important;line-height:1!important;opacity:0.9;cursor:pointer;outline:none!important;box-shadow:none!important;margin:0;padding:0">&times;</button>
                </div>
                <div class="modal-body text-left p-4" style="background:#faf7f0">
                    <input type="hidden" name="id" id="edit_invoice_id">

                    <!-- Member & Invoice Info Header -->
                    <div class="card p-3 mb-3 border-0 shadow-sm" style="background:#ffffff;border-radius:12px">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <label class="text-uppercase small font-weight-bold text-muted mb-1">Member Name & ITS</label>
                                <div class="fw-bold text-dark h6 mb-0" id="edit_member_name"></div>
                            </div>
                            <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                <label class="text-uppercase small font-weight-bold text-muted mb-1">Invoice Date</label>
                                <input type="date" name="created_at" id="edit_created_at" class="form-control form-control-sm font-weight-bold" style="border-radius:8px">
                            </div>
                        </div>
                    </div>

                    <!-- Amounts Breakdown Section -->
                    <div class="card p-3 mb-3 border-0 shadow-sm" style="background:#ffffff;border-radius:12px">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3" id="edit_section_title" style="font-size:0.85rem">
                            <i class="fa-solid fa-coins me-2 text-warning"></i>Invoice Amount & Breakdown
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-4" id="edit_jamaat_section">
                                <label class="form-label font-weight-bold text-success small mb-1">Jamaat Amount (₹)</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-white">₹</span></div>
                                    <input type="number" step="0.01" name="jamaat_amount" id="edit_jamaat_amount" class="form-control font-weight-bold text-success">
                                </div>
                            </div>
                            <div class="col-md-4" id="edit_sarkaar_section">
                                <label class="form-label font-weight-bold text-info small mb-1">Ladies / Sarkaar Amount (₹)</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-white">₹</span></div>
                                    <input type="number" step="0.01" name="sarkaar_amount" id="edit_sarkaar_amount" class="form-control font-weight-bold text-info">
                                </div>
                            </div>
                            <div class="col-md-4" id="edit_deposit_section">
                                <label class="form-label font-weight-bold text-warning small mb-1">Deposit Amount (₹)</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text bg-white">₹</span></div>
                                    <input type="number" step="0.01" name="deposit_amount" id="edit_deposit_amount" class="form-control font-weight-bold text-warning">
                                </div>
                            </div>
                        </div>

                        <div class="mt-3" id="edit_total_amount_section">
                            <label class="form-label font-weight-bold text-primary small mb-1">Total Invoice Amount (₹)</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text bg-primary text-white font-weight-bold">₹</span></div>
                                <input type="number" step="0.01" name="amount" id="edit_invoice_amount" class="form-control font-weight-bold text-primary" style="font-size:1.15rem;background-color:#fefce8">
                            </div>
                        </div>

                        <div class="mt-3" id="edit_thaal_count_section" style="display:none">
                            <label class="form-label font-weight-bold text-dark small mb-1">
                                Approximate Thaal Count <span class="text-muted font-italic">(Updates rent & deposit based on module rates)</span>
                            </label>
                            <input type="number" min="0" name="approximate_thaal_count" id="edit_approximate_thaal_count" class="form-control form-control-sm" style="border-radius:8px;max-width:180px">
                        </div>
                    </div>

                    <!-- Rent Items Breakdown & Quantities Section -->
                    <div id="edit_rent_items_wrapper" class="card p-3 border-0 shadow-sm" style="background:#ffffff;border-radius:12px;display:none">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3" style="font-size:0.85rem">
                            <i class="fa-solid fa-boxes-stacked me-2 text-warning"></i>Rent Items & Quantities Breakdown
                        </h6>
                        <div class="table-responsive" style="max-height:260px;overflow-y:auto;border:1px solid #e8e0cc;border-radius:10px">
                            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem">
                                <thead style="background:#f7f4ec">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Provider</th>
                                        <th class="text-right" style="width:110px">Rate (₹)</th>
                                        <th class="text-center" style="width:100px">Qty</th>
                                        <th class="text-right" style="width:120px">Total (₹)</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_rent_items_tbody">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="background:#f7f4ec;border-top:1px solid #e8e0cc">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal" style="border-radius:8px">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm px-4 font-weight-bold" id="edit_save_btn" style="background:linear-gradient(135deg, #b8860b 0%, #966c07 100%);color:#fff;border:none;border-radius:8px">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Invoice & Rent Items
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var baseJamaatAmount = 0;
var baseLadiesAmount = 0;

function editInvoice(data) {
    $('#edit_invoice_id').val(data.id);
    $('#edit_member_name').text(data.Full_Name + ' (' + data.ITS_ID + ')');
    
    if (data.created_at) {
        var d = new Date(data.created_at);
        if (!isNaN(d.getTime())) {
            var yyyy = d.getFullYear();
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            var dd = String(d.getDate()).padStart(2, '0');
            $('#edit_created_at').val(yyyy + '-' + mm + '-' + dd);
        }
    }

    var jAmt = parseFloat(data.jamaat_amount) || 0;
    var sAmt = parseFloat(data.sarkaar_amount) || 0;
    var depAmt = parseFloat(data.deposit_amount) || 0;
    var tAmt = parseFloat(data.master_amount || data.amount) || 0;

    if (jAmt === 0 && sAmt === 0 && tAmt > 0) {
        jAmt = tAmt;
    }

    baseJamaatAmount = jAmt;
    baseLadiesAmount = sAmt;

    $('#edit_jamaat_amount').val(jAmt.toFixed(2));
    $('#edit_sarkaar_amount').val(sAmt.toFixed(2));
    $('#edit_deposit_amount').val(depAmt.toFixed(2));
    $('#edit_invoice_amount').val(tAmt.toFixed(2));

    $('#edit_rent_items_wrapper').hide();
    $('#edit_rent_items_tbody').empty();
    $('#edit_thaal_count_section').hide();

    var pageIsDeposit = <?= $is_deposit ? 'true' : 'false' ?>;
    var isDepositOnly = pageIsDeposit || (data.charge_type === 'deposit') || (data.charge_type === 'rent' && tAmt <= 0.0001 && depAmt > 0);

    if (isDepositOnly) {
        $('#edit_modal_title').html('<i class="fa-solid fa-file-pen me-2"></i>Update Deposit Invoice');
        $('#edit_section_title').html('<i class="fa-solid fa-coins me-2 text-warning"></i>Deposit Amount Details');
        $('#edit_jamaat_section').hide();
        $('#edit_sarkaar_section').hide();
        $('#edit_total_amount_section').hide();
        $('#edit_thaal_count_section').hide();
        $('#edit_rent_items_wrapper').hide();
        $('#edit_deposit_section').removeClass('col-md-4').addClass('col-md-12').show();
        $('#edit_save_btn').html('<i class="fa-solid fa-floppy-disk me-1"></i> Save Deposit Amount');
    } else {
        $('#edit_modal_title').html('<i class="fa-solid fa-file-pen me-2"></i>Update Invoice & Rent Details');
        $('#edit_section_title').html('<i class="fa-solid fa-coins me-2 text-warning"></i>Invoice Amount & Breakdown');
        $('#edit_jamaat_section').show();
        $('#edit_sarkaar_section').show();
        $('#edit_deposit_section').removeClass('col-md-12').addClass('col-md-4').show();
        $('#edit_total_amount_section').show();
        $('#edit_save_btn').html('<i class="fa-solid fa-floppy-disk me-1"></i> Save Invoice & Rent Items');
    }

    // Fetch full invoice details via AJAX
    $.ajax({
        url: '<?= base_url("anjuman/get_invoice_full_details_ajax"); ?>',
        type: 'GET',
        data: { id: data.id },
        dataType: 'json',
        success: function(res) {
            if (res && res.success) {
                if (isDepositOnly) {
                    if (res.breakdown && res.breakdown.deposit_amount !== undefined) {
                        $('#edit_deposit_amount').val(parseFloat(res.breakdown.deposit_amount).toFixed(2));
                    }
                    $('#edit_jamaat_section').hide();
                    $('#edit_sarkaar_section').hide();
                    $('#edit_total_amount_section').hide();
                    $('#edit_thaal_count_section').hide();
                    $('#edit_rent_items_wrapper').hide();
                } else {
                    if (res.raza) {
                        $('#edit_thaal_count_section').show();
                        $('#edit_approximate_thaal_count').val(res.raza.thaal_count || 0);
                    }

                    if (res.breakdown) {
                        baseJamaatAmount = parseFloat(res.breakdown.base_rent_amount) || 0;
                        $('#edit_jamaat_amount').val(parseFloat(res.breakdown.jamaat_amount).toFixed(2));
                        $('#edit_sarkaar_amount').val(parseFloat(res.breakdown.sarkaar_amount).toFixed(2));
                        $('#edit_deposit_amount').val(parseFloat(res.breakdown.deposit_amount).toFixed(2));
                        $('#edit_invoice_amount').val(parseFloat(res.breakdown.amount).toFixed(2));
                    }

                    if (res.rent_items && res.rent_items.length > 0) {
                        $('#edit_rent_items_wrapper').show();
                        var tbody = $('#edit_rent_items_tbody');
                        tbody.empty();

                        res.rent_items.forEach(function(item) {
                            var rate = (item.rent_sabeel > 0) ? item.rent_sabeel : item.rent_non_sabeel;
                            var qty = parseInt(item.quantity) || 0;
                            var rowTotal = rate * qty;

                            var providerBadge = '<span class="badge bg-secondary text-white">' + item.service_provided_by + '</span>';
                            if (item.service_provided_by === 'Jamaat') {
                                providerBadge = '<span class="badge bg-success text-white">Jamaat</span>';
                            } else if (item.service_provided_by === 'Ladies') {
                                providerBadge = '<span class="badge bg-info text-white">Ladies</span>';
                            } else if (item.service_provided_by === 'Extras') {
                                providerBadge = '<span class="badge bg-warning text-dark">Extras</span>';
                            }

                            var tr = $('<tr>');
                            tr.html(
                                '<td class="align-middle fw-bold">' + item.item_name + '</td>' +
                                '<td class="align-middle">' + providerBadge + '</td>' +
                                '<td class="align-middle text-right"><input type="number" step="0.01" class="form-control form-control-sm text-right item-rate-input" data-item-id="' + item.id + '" data-provider="' + item.service_provided_by + '" value="' + rate.toFixed(2) + '" style="width:90px;display:inline-block"></td>' +
                                '<td class="align-middle text-center"><input type="number" min="0" class="form-control form-control-sm text-center item-qty-input" name="item_qty[' + item.id + ']" data-item-id="' + item.id + '" value="' + qty + '" style="width:70px;display:inline-block"></td>' +
                                '<td class="align-middle text-right fw-bold text-dark item-total-cell" id="item_total_' + item.id + '">₹' + rowTotal.toFixed(2) + '</td>'
                            );
                            tbody.append(tr);
                        });

                        // Add live listener for rate and quantity changes
                        $('.item-qty-input, .item-rate-input').off('input change').on('input change', function() {
                            recalculateRentTotals();
                        });
                    }
                }
            }
        }
    });

    $('#editInvoiceModal').modal('show');
}

function recalculateRentTotals() {
    var itemsJamaat = 0;
    var itemsLadies = 0;

    $('#edit_rent_items_tbody tr').each(function() {
        var rateInput = $(this).find('.item-rate-input');
        var qtyInput = $(this).find('.item-qty-input');
        var itemId = qtyInput.data('item-id');
        var provider = rateInput.data('provider');

        var rate = parseFloat(rateInput.val()) || 0;
        var qty = parseInt(qtyInput.val()) || 0;
        var itemTotal = rate * qty;

        $('#item_total_' + itemId).text('₹' + itemTotal.toFixed(2));

        if (provider === 'Ladies') {
            itemsLadies += itemTotal;
        } else {
            itemsJamaat += itemTotal;
        }
    });

    var calcJamaat = baseJamaatAmount + itemsJamaat;
    var calcLadies = itemsLadies;
    var totalInvoice = calcJamaat + calcLadies;

    $('#edit_jamaat_amount').val(calcJamaat.toFixed(2));
    $('#edit_sarkaar_amount').val(calcLadies.toFixed(2));
    $('#edit_invoice_amount').val(totalInvoice.toFixed(2));
}

document.addEventListener('DOMContentLoaded', function() {
    var jmtInput = document.getElementById('edit_jamaat_amount');
    var sarInput = document.getElementById('edit_sarkaar_amount');
    var totalInput = document.getElementById('edit_invoice_amount');

    function calculateTotalFromInputs() {
        var jVal = parseFloat(jmtInput.value) || 0;
        var sVal = parseFloat(sarInput.value) || 0;
        totalInput.value = (jVal + sVal).toFixed(2);

        var itemsJamaat = 0;
        var itemsLadies = 0;
        $('#edit_rent_items_tbody tr').each(function() {
            var rate = parseFloat($(this).find('.item-rate-input').val()) || 0;
            var qty = parseInt($(this).find('.item-qty-input').val()) || 0;
            var provider = $(this).find('.item-rate-input').data('provider');
            if (provider === 'Ladies') {
                itemsLadies += rate * qty;
            } else {
                itemsJamaat += rate * qty;
            }
        });
        baseJamaatAmount = Math.max(0, jVal - itemsJamaat);
        baseLadiesAmount = Math.max(0, sVal - itemsLadies);
    }

    if (jmtInput && sarInput && totalInput) {
        jmtInput.addEventListener('input', calculateTotalFromInputs);
        sarInput.addEventListener('input', calculateTotalFromInputs);
    }

    // Dynamic Thaal Count recalculation handler
    $('#edit_approximate_thaal_count').on('input change', function() {
        var invoiceId = $('#edit_invoice_id').val();
        var thaalCount = parseInt($(this).val()) || 0;

        var itemQtyMap = {};
        $('#edit_rent_items_tbody tr').each(function() {
            var qtyInput = $(this).find('.item-qty-input');
            var itemId = qtyInput.data('item-id');
            itemQtyMap[itemId] = parseInt(qtyInput.val()) || 0;
        });

        $.ajax({
            url: '<?= base_url("anjuman/recalculate_invoice_amounts_ajax"); ?>',
            type: 'POST',
            data: {
                invoice_id: invoiceId,
                thaal_count: thaalCount,
                item_qty: itemQtyMap
            },
            dataType: 'json',
            success: function(res) {
                if (res && res.success) {
                    baseJamaatAmount = parseFloat(res.base_rent_amount) || 0;
                    $('#edit_jamaat_amount').val(parseFloat(res.jamaat_amount).toFixed(2));
                    $('#edit_sarkaar_amount').val(parseFloat(res.sarkaar_amount).toFixed(2));
                    $('#edit_deposit_amount').val(parseFloat(res.deposit_amount).toFixed(2));
                    $('#edit_invoice_amount').val(parseFloat(res.amount).toFixed(2));
                }
            }
        });
    });
});
</script>
