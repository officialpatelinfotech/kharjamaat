<?php $this->load->view('Accounts/Header'); ?>

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
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 26px; }

  /* ── Empty state ── */
  .empty-state {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    padding: 56px 24px; text-align: center;
  }
  .empty-state .fa { font-size: 2.5rem; color: var(--border); display: block; margin-bottom: 14px; }
  .empty-state p { font-size: 0.88rem; color: var(--text-3); font-weight: 500; margin: 0; }

  /* ── Invoice card ── */
  .inv-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    overflow: hidden; display: flex; flex-direction: column;
    transition: box-shadow .2s, border-color .2s;
    height: 100%;
  }
  .inv-card:hover { box-shadow: var(--shadow); border-color: rgba(184,134,11,0.3); }
  .inv-card::before { content: ''; display: block; height: 3px; }
  .inv-card.is-paid::before   { background: linear-gradient(90deg, var(--green), #4cc790); }
  .inv-card.is-due::before    { background: linear-gradient(90deg, var(--red),   #f87171); }

  /* card header */
  .inv-card-header {
    padding: 16px 20px 14px; background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
  }
  .inv-title { font-size: 0.95rem; font-weight: 700; color: var(--text-1); margin: 0 0 3px; line-height: 1.3; }
  .inv-raza-type { font-size: 0.7rem; font-weight: 600; color: var(--text-3); letter-spacing: .2px; }

  .inv-meta-right { text-align: right; flex-shrink: 0; }
  .inv-id { font-size: 0.65rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); }
  .inv-raza-id { font-size: 0.72rem; font-weight: 700; color: var(--text-2); margin-top: 2px; }

  /* status badges */
  .status-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 40px;
    font-size: 0.63rem; font-weight: 700; letter-spacing: .3px;
    margin-top: 5px; white-space: nowrap;
  }
  .pill-paid      { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .pill-approved  { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .pill-rejected  { background: var(--red-bg);   color: var(--red);   border: 1px solid var(--red-border); }
  .pill-pending   { background: var(--amber-bg); color: var(--amber); border: 1px solid var(--amber-border); }
  .pill-returned  { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

  /* card body */
  .inv-card-body { padding: 16px 20px; flex: 1; }

  /* amount strip */
  .amt-strip {
    display: flex; gap: 0;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    overflow: hidden; margin-bottom: 14px;
  }
  .amt-cell {
    flex: 1; padding: 10px 12px; text-align: center;
    border-right: 1px solid var(--border);
  }
  .amt-cell:last-child { border-right: none; }
  .amt-label { font-size: 0.6rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 4px; }
  .amt-value { font-size: 0.92rem; font-weight: 800; font-variant-numeric: tabular-nums; }
  .amt-total   { color: var(--blue); }
  .amt-paid    { color: var(--green); }
  .amt-due-pos { color: var(--red); }
  .amt-due-zero{ color: var(--green); }

  /* breakdown strip */
  .breakdown-strip {
    display: flex; gap: 10px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-sm); padding: 9px 12px;
    margin-bottom: 14px; font-size: 0.75rem;
  }
  .breakdown-item { flex: 1; }
  .breakdown-item .bl { font-size: 0.6rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); margin-bottom: 2px; }
  .breakdown-item .bv { font-weight: 700; }
  .bv-green { color: var(--green); }
  .bv-blue  { color: var(--blue); }
  .bv-amber { color: var(--amber); }

  /* hijri year chip */
  .hijri-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: .3px;
    color: var(--text-3); background: var(--surface-2);
    border: 1px solid var(--border); border-radius: 40px; padding: 3px 10px;
  }

  /* card footer */
  .inv-card-footer {
    padding: 12px 20px 16px; border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
    flex-wrap: wrap;
  }

  /* action buttons */
  .btn-pay {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 2px 6px rgba(184,134,11,0.25); transition: all .18s;
  }
  .btn-pay:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(184,134,11,0.35); }

  .btn-history {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-history:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold-deep); }

  /* ── Shared modal styles ── */
  .modal-content {
    border: 1.5px solid var(--border) !important; border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important; overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .modal-header {
    background: var(--surface-2); border-bottom: 1px solid var(--border);
    padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif; font-size: 1.1rem;
    font-weight: 600; color: var(--gold); margin: 0;
  }
  .modal-header .close {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; cursor: pointer; color: var(--text-2);
    transition: all .15s; padding: 0; opacity: 1; text-shadow: none;
  }
  .modal-header .close:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }
  .modal-body   { background: var(--bg); padding: 20px 24px; }
  .modal-footer { background: var(--surface-2); border-top: 1px solid var(--border); padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px; }

  /* ── Modal form controls (standard spec) ── */
  .modal-form-label {
    font-size: 0.7rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; display: block;
  }
  .modal-form-control {
    width: 100%; height: 52px; padding: 0 16px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 15px; font-weight: 500; color: var(--text-1); line-height: 50px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: border-color .2s, box-shadow .2s;
    appearance: none; -webkit-appearance: none; box-sizing: border-box;
  }
  .modal-form-control:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(184,134,11,0.12); }
  select.modal-form-control {
    padding-right: 42px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
  }
  .form-help { font-size: 0.7rem; color: var(--text-3); margin-top: 6px; }
  .form-group-modal { margin-bottom: 16px; }

  /* invoice title display */
  .inv-title-display {
    height: 52px; padding: 0 16px; display: flex; align-items: center;
    background: var(--surface-2); border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); font-size: 0.88rem;
    font-weight: 700; color: var(--gold-deep); box-sizing: border-box;
  }

  /* modal buttons */
  .btn-modal-cancel {
    padding: 9px 20px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-modal-cancel:hover { background: var(--surface-2); }
  .btn-modal-proceed {
    padding: 9px 22px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s;
    box-shadow: 0 2px 8px rgba(184,134,11,0.25);
    display: inline-flex; align-items: center; gap: 7px;
  }
  .btn-modal-proceed:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); }
  .btn-modal-proceed::before { content: '\f023'; font-family: FontAwesome; font-size: 12px; }

  /* history table */
  .history-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
  .history-table thead th {
    font-size: 0.63rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
    color: var(--text-3); padding: 9px 14px; border-bottom: 1.5px solid var(--border);
    background: var(--surface-2); white-space: nowrap;
  }
  .history-table tbody td { padding: 11px 14px; border-bottom: 1px solid var(--border); color: var(--text-2); vertical-align: middle; }
  .history-table tbody tr:last-child td { border-bottom: none; }
  .history-table tbody tr:hover { background: #fdfbf5; }

  .method-pill {
    display: inline-block; padding: 2px 9px; border-radius: 40px;
    font-size: 0.65rem; font-weight: 700;
    background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border);
  }
  .btn-receipt {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--blue-border); background: var(--blue-bg);
    color: var(--blue); font-size: 12px; cursor: pointer; transition: all .15s;
  }
  .btn-receipt:hover { background: var(--blue); color: #fff; border-color: var(--blue); }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .inv-card-footer { flex-direction: column; align-items: stretch; }
    .btn-pay, .btn-history { justify-content: center; }
    .modal-header, .modal-body, .modal-footer { padding: 14px 16px !important; }
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?= base_url('accounts/home') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <?php
      $is_laagat = isset($module_type) && $module_type === 'laagat';
      $is_deposit = isset($module_type) && $module_type === 'deposit';
      $title = $is_laagat ? 'Laagat Invoices' : ($is_deposit ? 'Rent Deposits' : 'Rent Invoices');
      $sub_title = $is_laagat ? 'Your laagat invoices' : ($is_deposit ? 'Your rent deposit invoices' : 'Your rent invoices');
    ?>
    <h1 class="page-heading"><?= $title ?></h1>
  </div>
  <p class="page-sub"><?= $sub_title ?></p>
  <hr class="section-divider">

  <?php if (empty($invoices)): ?>
    <div class="empty-state">
      <i class="fa fa-file-text-o"></i>
      <p>No <?= $is_laagat ? 'Laagat' : ($is_deposit ? 'Rent Deposit' : 'Rent') ?> invoices found for your family.</p>
    </div>

  <?php else: ?>
    <div class="row">
      <?php foreach ($invoices as $inv):
        $isDepositOnly = ($inv['charge_type'] === 'rent' && (float)$inv['master_amount'] <= 0.0001 && (float)$inv['deposit_amount'] > 0);
        $tAmt        = $isDepositOnly ? (float)$inv['deposit_amount'] : (float)$inv['master_amount'];
        $due         = $tAmt - (float)$inv['paid_amount'];
        $is_paid     = $due <= 0;
        $cardClass   = $is_paid ? 'is-paid' : 'is-due';
        $janab       = $inv['janab_status'];
        $jAmt        = (float)$inv['jamaat_amount'];
        $sAmt        = (float)$inv['sarkaar_amount'];
        if ($isDepositOnly) {
          $jAmt = 0.00;
          $sAmt = 0.00;
        } elseif ($jAmt == 0.00 && $sAmt == 0.00 && $tAmt > 0.00) {
          $jAmt = $tAmt;
        }
      ?>
      <div class="col-12 col-md-6 mb-4 d-flex">
        <div class="inv-card <?= $cardClass ?> w-100">

          <!-- card header -->
          <div class="inv-card-header">
            <div>
              <h5 class="inv-title"><?= htmlspecialchars($inv['title']) . ($isDepositOnly ? ' (Deposit)' : '') ?></h5>
              <?php if ($inv['raza_type_name']): ?>
                <div class="inv-raza-type"><?= htmlspecialchars($inv['raza_type_name']) ?></div>
              <?php endif; ?>
              <div style="margin-top:6px; display:flex; flex-wrap:wrap; gap:5px;">
                <?php if ((int)($inv['is_returned'] ?? 0) === 1): ?>
                  <span class="status-pill pill-returned"><i class="fa fa-reply"></i> Returned</span>
                <?php elseif ($is_paid): ?>
                  <span class="status-pill pill-paid"><i class="fa fa-check"></i> Paid</span>
                <?php endif; ?>
                <?php if ($janab === null): ?>
                <?php elseif ((int)$janab === 1): ?>
                  <span class="status-pill pill-approved"><i class="fa fa-check"></i> Raza Approved</span>
                <?php elseif ((int)$janab === 2): ?>
                  <span class="status-pill pill-rejected"><i class="fa fa-times"></i> Raza Rejected</span>
                <?php else: ?>
                  <span class="status-pill pill-pending"><i class="fa fa-clock-o"></i> Raza Pending</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="inv-meta-right">
              <div class="inv-id">Invoice #<?= $inv['id'] ?></div>
              <div class="inv-raza-id">R#<?= htmlspecialchars($inv['generated_raza_id'] ?: 'N/A') ?></div>
            </div>
          </div>

          <!-- card body -->
          <div class="inv-card-body">

            <!-- amount strip -->
            <div class="amt-strip">
              <div class="amt-cell">
                <div class="amt-label">Total</div>
                <div class="amt-value amt-total">₹<?= format_inr($tAmt, 0) ?></div>
              </div>
              <div class="amt-cell">
                <div class="amt-label">Paid</div>
                <div class="amt-value amt-paid">₹<?= format_inr((float)$inv['paid_amount'], 0) ?></div>
              </div>
              <div class="amt-cell">
                <div class="amt-label">Outstanding</div>
                <div class="amt-value <?= $is_paid ? 'amt-due-zero' : 'amt-due-pos' ?>">₹<?= format_inr($due, 0) ?></div>
              </div>
            </div>

            <!-- breakdown: laagat split OR deposit -->
            <?php if ($inv['charge_type'] !== 'rent'): ?>
            <div class="breakdown-strip">
              <div class="breakdown-item">
                <div class="bl">Jamaat Laagat</div>
                <div class="bv bv-green">₹<?= format_inr($jAmt, 0) ?></div>
              </div>
              <div class="breakdown-item" style="text-align:right;">
                <div class="bl">Sarkaar Laagat</div>
                <div class="bv bv-blue">₹<?= format_inr($sAmt, 0) ?></div>
              </div>
            </div>
            <?php elseif (!empty($inv['deposit_amount']) && (float)$inv['deposit_amount'] > 0): ?>
            <div class="breakdown-strip">
              <div class="breakdown-item">
                <div class="bl">Deposit Amount</div>
                <div class="bv bv-amber">₹<?= format_inr((float)$inv['deposit_amount'], 0) ?></div>
              </div>
            </div>
            <?php endif; ?>

            <!-- hijri year -->
            <?php if (!empty($inv['hijri_year'])): ?>
            <span class="hijri-chip"><i class="fa fa-moon-o"></i> <?= htmlspecialchars($inv['hijri_year']) ?>H</span>
            <?php endif; ?>

          </div><!-- /.inv-card-body -->

          <!-- footer actions -->
          <div class="inv-card-footer">
            <span></span><!-- spacer -->
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <?php if ($inv['charge_type'] === 'rent' && !$is_deposit): ?>
                <button type="button" class="btn-history"
                  onclick="showRentItems(<?= $inv['id'] ?>, '<?= htmlspecialchars($inv['title']) ?>')">
                  <i class="fa fa-list"></i> View Items
                </button>
                <a href="<?= base_url('common/generate_pdf?id=' . $inv['id'] . '&for=9') ?>" target="_blank" class="btn-history" style="text-decoration:none;">
                  <i class="fa fa-print"></i> Print Bill
                </a>
              <?php endif; ?>
              <?php if ((int)$janab === 1 && $due > 0 && (int)($inv['is_returned'] ?? 0) === 0): ?>
                <button type="button" class="btn-pay"
                  onclick="payInvoice(<?= $inv['id'] ?>, <?= $due ?>, '<?= htmlspecialchars($inv['title']) ?>', '<?= htmlspecialchars($inv['charge_type']) ?>', '<?= htmlspecialchars($inv['ITS_ID']) ?>')">
                  <i class="fa fa-credit-card"></i> Pay Now
                </button>
              <?php endif; ?>
              <button type="button" class="btn-history"
                onclick="showHistory(<?= $inv['id'] ?>, '<?= htmlspecialchars($inv['title']) ?>')">
                <i class="fa fa-history"></i> History
              </button>
            </div>
          </div>

        </div><!-- /.inv-card -->
      </div><!-- /.col -->
      <?php endforeach; ?>
    </div><!-- /.row -->
  <?php endif; ?>

</div><!-- /.container -->

<!-- ── History Modal ── -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-history" style="margin-right:8px;color:var(--gold);font-size:.9rem;"></i>Payment History</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" style="padding:0 !important;">
        <div style="padding:14px 20px 10px; background:var(--surface-2); border-bottom:1px solid var(--border);">
          <div style="font-size:.65rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:var(--text-3);margin-bottom:3px;">Invoice</div>
          <div style="font-size:.9rem;font-weight:700;color:var(--gold-deep);" id="history_title"></div>
        </div>
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th class="text-center">Receipt</th>
              </tr>
            </thead>
            <tbody id="history_table_body">
              <tr><td colspan="4" style="text-align:center;padding:28px;color:var(--text-3);">Loading…</td></tr>
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

<!-- ── Rent Items Modal ── -->
<div class="modal fade" id="rentItemsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-list" style="margin-right:8px;color:var(--gold);font-size:.9rem;"></i>Rent Items</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" style="padding:0 !important;">
        <div style="padding:14px 20px 10px; background:var(--surface-2); border-bottom:1px solid var(--border);">
          <div style="font-size:.65rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:var(--text-3);margin-bottom:3px;">Invoice</div>
          <div style="font-size:.9rem;font-weight:700;color:var(--gold-deep);" id="rent_items_title"></div>
        </div>
        <div class="table-responsive">
          <table class="history-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Item Name</th>
                <th class="text-right">Cost / Piece</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total Cost</th>
              </tr>
            </thead>
            <tbody id="rent_items_table_body">
              <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--text-3);">Loading…</td></tr>
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

<!-- ── Pay Modal ── -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payModalLabel"><i class="fa fa-credit-card" style="margin-right:8px;color:var(--gold);font-size:.9rem;"></i>Make Payment</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form method="post" action="<?= base_url('payment/ccavenue_laagat_rent'); ?>">
        <div class="modal-body">
          <div class="form-group-modal">
            <label class="modal-form-label">Invoice</label>
            <div class="inv-title-display" id="pay_title"></div>
          </div>
          <div class="form-group-modal">
            <label for="pay_amount" class="modal-form-label">Amount (₹)</label>
            <input type="number" step="0.01" min="0.01"
              id="pay_amount" name="amount"
              class="modal-form-control" placeholder="Enter amount" required />
            <div class="form-help" id="pay_due_help"></div>
          </div>
          <input type="hidden" id="pay_invoice_id"  name="invoice_id" />
          <input type="hidden" id="pay_its_id"      name="its_id" />
          <input type="hidden" id="pay_charge_type" name="charge_type" />
          <input type="hidden" id="pay_order_id"    name="order_id" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-modal-proceed">Proceed to Pay</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
function showHistory(invoiceId, title) {
  $('#history_title').text(title);
  $('#history_table_body').html('<tr><td colspan="4" style="text-align:center;padding:28px;color:var(--text-3);"><i class="fa fa-spinner fa-spin" style="margin-right:6px;"></i> Loading history…</td></tr>');
  $('#historyModal').modal('show');

  $.ajax({
    url: '<?= base_url("accounts/get_laagat_payment_history"); ?>',
    type: 'POST', data: { invoice_id: invoiceId }, dataType: 'json',
    success: function(response) {
      let html = '';
      if (response && response.length > 0) {
        response.forEach(function(item) {
          let d = new Date(item.payment_date);
          let dateStr = d.toLocaleDateString('en-GB').replace(/\//g, '-');
          html += '<tr>' +
            '<td style="font-weight:600;color:var(--text-1);">' + dateStr + '</td>' +
            '<td><span class="method-pill">' + (item.payment_method || 'Cash') + '</span></td>' +
            '<td class="text-right" style="font-weight:700;color:var(--green);">₹' + parseFloat(item.amount).toLocaleString('en-IN', {minimumFractionDigits: 0}) + '</td>' +
            '<td class="text-center"><button type="button" class="btn-receipt view-invoice" data-payment-id="' + item.id + '" title="View Receipt"><i class="fa fa-file-pdf-o"></i></button></td>' +
            '</tr>';
        });
      } else {
        html = '<tr><td colspan="4" style="text-align:center;padding:28px;color:var(--text-3);font-size:.83rem;">No payment records found.</td></tr>';
      }
      $('#history_table_body').html(html);
    },
    error: function() {
      $('#history_table_body').html('<tr><td colspan="4" style="text-align:center;padding:28px;color:var(--red);font-size:.83rem;">Failed to load history.</td></tr>');
    }
  });
}

function showRentItems(invoiceId, title) {
  $('#rent_items_title').text(title);
  $('#rent_items_table_body').html('<tr><td colspan="5" style="text-align:center;padding:28px;color:var(--text-3);"><i class="fa fa-spinner fa-spin" style="margin-right:6px;"></i> Loading rent items…</td></tr>');
  $('#rentItemsModal').modal('show');

  $.ajax({
    url: '<?= base_url("accounts/get_rent_invoice_items"); ?>',
    type: 'POST', data: { invoice_id: invoiceId }, dataType: 'json',
    success: function(response) {
      let html = '';
      if (response && response.success) {
        if (response.items && response.items.length > 0) {
          response.items.forEach(function(item, idx) {
            html += '<tr>' +
              '<td>' + (idx + 1) + '</td>' +
              '<td style="font-weight:600;color:var(--text-1);">' + item.item_name + '</td>' +
              '<td class="text-right" style="font-weight:700;color:var(--green);">₹' + parseFloat(item.rent_sabeel).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>' +
              '<td class="text-center" style="font-weight:700;color:var(--text-2);">' + item.quantity + '</td>' +
              '<td class="text-right" style="font-weight:700;color:var(--text-1);">₹' + parseFloat(item.total_cost).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>' +
              '</tr>';
          });
        } else {
          html = '<tr><td colspan="5" style="text-align:center;padding:28px;color:var(--text-3);font-size:.83rem;">No rent items selected.</td></tr>';
        }
      } else {
        html = '<tr><td colspan="5" style="text-align:center;padding:28px;color:var(--red);font-size:.83rem;">Failed to load rent items.</td></tr>';
      }
      $('#rent_items_table_body').html(html);
    },
    error: function() {
      $('#rent_items_table_body').html('<tr><td colspan="5" style="text-align:center;padding:28px;color:var(--red);font-size:.83rem;">Failed to load rent items.</td></tr>');
    }
  });
}

$(document).on('click', '.view-invoice', function(e) {
  e.preventDefault();
  const paymentId = $(this).data('payment-id');
  if (!paymentId) return;
  $.ajax({
    url: "<?php echo base_url('common/generate_pdf'); ?>",
    type: "POST", data: { id: paymentId, for: 8 },
    xhrFields: { responseType: 'blob' },
    success: function(response) {
      var url = window.URL.createObjectURL(new Blob([response], { type: "application/pdf" }));
      window.open(url, "_blank");
    },
    error: function() { alert('Failed to generate receipt PDF'); }
  });
});

function payInvoice(invoiceId, maxAmount, title, chargeType, itsId) {
  $('#pay_title').text(title);
  $('#pay_invoice_id').val(invoiceId);
  $('#pay_its_id').val(itsId);
  $('#pay_charge_type').val(chargeType);
  $('#pay_amount').val(maxAmount).attr('max', maxAmount);
  $('#pay_due_help').text('Maximum payment allowed: ₹' + parseFloat(maxAmount).toLocaleString('en-IN', {minimumFractionDigits: 0}));
  $('#pay_order_id').val('LAAGAT-RENT-' + Date.now());
  $('#payModal').modal('show');
}

$('#payModal form').on('submit', function(e) {
  var amt    = parseFloat($('#pay_amount').val());
  var maxAmt = parseFloat($('#pay_amount').attr('max'));
  if (amt <= 0) { alert('Please enter a valid amount.'); e.preventDefault(); return false; }
  if (amt > maxAmt) { alert('Amount cannot exceed outstanding due: ₹' + maxAmt); e.preventDefault(); return false; }
});
</script>