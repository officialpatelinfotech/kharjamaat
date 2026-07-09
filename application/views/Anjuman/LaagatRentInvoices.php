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
        if (!empty($invoices)) {
            foreach($invoices as $inv) {
                $tAmt = (float)$inv['master_amount'];
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
            }
        }
    ?>
    <?php 
        $is_laagat = isset($module_type) && $module_type === 'laagat';
        $is_rent = isset($module_type) && $module_type === 'rent';
        $back_url = $is_laagat ? base_url('anjuman/laagat') : ($is_rent ? base_url('anjuman/rent') : base_url('anjuman/laagat_rent'));
        $title = $is_laagat ? 'Update Laagat Invoices' : ($is_rent ? 'Update Rent Invoices' : 'Update Laagat & Rent Invoices');
    ?>
    
    <!-- Header -->
    <div class="page-header">
        <a href="<?= $back_url; ?>" class="btn-back-nav" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
        <h1 class="page-title"><?= $title ?></h1>
        <div style="width: 42px;"></div>
    </div>

    <!-- Summary Metrics -->
    <div class="summary-grid">
        <?php if (!$is_rent): ?>
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
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        <?php foreach($hijri_years as $y): ?>
                            <option value="<?= $y ?>" <?= ($filters['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ITS ID / Member</label>
                    <input type="text" name="its_id" class="form-control" placeholder="ITS ID" value="<?= $filters['its_id'] ?>" onchange="this.form.submit()">
                </div>
                <?php if (isset($module_type)): ?>
                    <input type="hidden" name="charge_type" value="<?= htmlspecialchars($module_type) ?>" />
                <?php else: ?>
                    <div class="col-md-3">
                        <label class="form-label">Type</label>
                        <select name="charge_type" class="form-control" onchange="this.form.submit()">
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
                        <?php if (!$is_rent): ?>
                            <th class="text-end">Jmt. Amount</th>
                            <th class="text-end">Sar. Amount</th>
                        <?php endif; ?>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Deposit</th>
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
                                <?php if (!$is_rent): ?>
                                    <td class="text-end text-success fw-bold"><?= $isRent ? '-' : '₹' . format_inr($jAmt, 0) ?></td>
                                    <td class="text-end text-info fw-bold"><?= $isRent ? '-' : '₹' . format_inr($sAmt, 0) ?></td>
                                <?php endif; ?>
                                <td class="text-end text-primary fw-bold">₹<?= format_inr($tAmt, 0) ?></td>
                                <td class="text-end text-warning fw-bold"><?= $isRent ? '₹' . format_inr((float)$inv['deposit_amount'], 0) : '-' ?></td>
                                <td class="text-center text-nowrap">
                                    <div class="d-flex justify-content-center align-items-center">
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
                            $colspan = 12;
                            if (isset($module_type)) $colspan--;
                            if ($is_rent) $colspan -= 2;
                        ?>
                        <tr>
                            <td colspan="<?= $colspan ?>" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-2x mb-3 opacity-25"></i><br>
                                No invoices found matching current filters.
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
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('anjuman/laagat_rent_invoice_save'); ?>" method="POST" class="w-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <input type="hidden" name="id" id="edit_invoice_id">
                    <div class="mb-3">
                        <label class="form-label">Member</label>
                        <p class="form-control-plaintext fw-bold text-dark mb-0" id="edit_member_name" style="font-size: 1.05rem;"></p>
                    </div>
                    <div id="edit_split_amounts_section">
                        <div class="mb-3">
                            <label class="form-label text-success">Jamaat Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                <input type="number" step="0.01" name="jamaat_amount" id="edit_jamaat_amount" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-info">Sarkaar Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                <input type="number" step="0.01" name="sarkaar_amount" id="edit_sarkaar_amount" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-primary" id="edit_amount_label">Total Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                            <input type="number" step="0.01" name="amount" id="edit_invoice_amount" class="form-control" readonly style="background-color: #f7f4ec;">
                        </div>
                    </div>
                    <div class="mb-3" id="edit_deposit_amount_section" style="display: none;">
                        <label class="form-label text-warning">Deposit Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                            <input type="number" step="0.01" name="deposit_amount" id="edit_deposit_amount" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-modal-save">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editInvoice(data) {
    $('#edit_invoice_id').val(data.id);
    $('#edit_member_name').text(data.Full_Name + ' (' + data.ITS_ID + ')');
    
    var jAmt = parseFloat(data.jamaat_amount) || 0;
    var sAmt = parseFloat(data.sarkaar_amount) || 0;
    var tAmt = parseFloat(data.amount) || 0;
    
    if (jAmt === 0 && sAmt === 0 && tAmt > 0) {
        jAmt = tAmt;
    }
    
    if (data.charge_type === 'rent') {
        $('#edit_split_amounts_section').hide();
        $('#edit_deposit_amount_section').show();
        $('#edit_deposit_amount').val(parseFloat(data.deposit_amount || 0).toFixed(2));
        $('#edit_amount_label').text('Amount');
        $('#edit_invoice_amount')
            .val(tAmt.toFixed(2))
            .prop('readonly', false)
            .css('background-color', '');
    } else {
        $('#edit_split_amounts_section').show();
        $('#edit_deposit_amount_section').hide();
        $('#edit_amount_label').text('Total Amount');
        $('#edit_jamaat_amount').val(jAmt.toFixed(2));
        $('#edit_sarkaar_amount').val(sAmt.toFixed(2));
        $('#edit_invoice_amount')
            .val((jAmt + sAmt).toFixed(2))
            .prop('readonly', true)
            .css('background-color', '#f7f4ec');
    }
    
    $('#editInvoiceModal').data('charge_type', data.charge_type);
    $('#editInvoiceModal').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    var jmtInput = document.getElementById('edit_jamaat_amount');
    var sarInput = document.getElementById('edit_sarkaar_amount');
    var totalInput = document.getElementById('edit_invoice_amount');

    function calculateTotal() {
        var chargeType = $('#editInvoiceModal').data('charge_type');
        if (chargeType === 'rent') {
            return;
        }
        var jVal = parseFloat(jmtInput.value) || 0;
        var sVal = parseFloat(sarInput.value) || 0;
        totalInput.value = (jVal + sVal).toFixed(2);
    }

    if (jmtInput && sarInput && totalInput) {
        jmtInput.addEventListener('input', calculateTotal);
        sarInput.addEventListener('input', calculateTotal);
    }
});
</script>
