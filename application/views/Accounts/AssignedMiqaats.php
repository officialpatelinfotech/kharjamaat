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
    --purple:      #6d28d9;
    --purple-bg:   #f5f3ff;
    --purple-border:#ddd6fe;
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
    position: relative;
    display: flex; align-items: center; justify-content: center;
    min-height: 44px; margin-bottom: 6px;
  }
  .btn-back-nav {
    position: absolute; left: 0;
    width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface); color: var(--text-2); font-size: 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
  }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold); font-size: 1.5rem; font-weight: 600;
    letter-spacing: -.3px; margin: 0; text-align: center;
  }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 28px; }

  /* ── Flash alerts ── */
  .flash-alert {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 0.83rem; font-weight: 600;
    margin-bottom: 16px; border: 1.5px solid;
  }
  .flash-alert .fa { font-size: 14px; }
  .flash-success { background: var(--green-bg); color: var(--green); border-color: var(--green-border); }
  .flash-danger  { background: var(--red-bg);   color: var(--red);   border-color: var(--red-border); }
  .flash-warning { background: var(--amber-bg); color: var(--amber); border-color: var(--amber-border); }

  /* ── Miqaat cards grid ── */
  .miqaat-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  @media (max-width: 768px) { .miqaat-grid { grid-template-columns: 1fr; } }

  /* ── Individual miqaat card ── */
  .miqaat-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    display: flex; flex-direction: column;
    transition: box-shadow .2s, border-color .2s;
  }
  .miqaat-card:hover { box-shadow: var(--shadow); border-color: rgba(184,134,11,0.35); }

  /* top accent colour bar — changes per status */
  .miqaat-card::before {
    content: ''; display: block; height: 3px;
  }
  .miqaat-card.status-active::before    { background: linear-gradient(90deg, var(--green), #4cc790); }
  .miqaat-card.status-inactive::before  { background: linear-gradient(90deg, var(--text-3), #c4b49a); }
  .miqaat-card.status-cancelled::before { background: linear-gradient(90deg, var(--red), #f87171); }
  .miqaat-card.status-completed::before { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

  /* card header band */
  .miqaat-card-header {
    padding: 16px 20px 14px;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;
  }
  .miqaat-name {
    font-size: 0.95rem; font-weight: 700; color: var(--text-1);
    line-height: 1.35; margin: 0;
  }

  /* status pill */
  .miq-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 40px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: .3px;
    white-space: nowrap; flex-shrink: 0;
  }
  .miq-active    { background: var(--green-bg);  color: var(--green);  border: 1px solid var(--green-border); }
  .miq-inactive  { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }
  .miq-cancelled { background: var(--red-bg);    color: var(--red);    border: 1px solid var(--red-border); }
  .miq-completed { background: var(--gold-muted); color: var(--gold-deep); border: 1px solid rgba(184,134,11,0.3); }

  /* card body */
  .miqaat-card-body { padding: 16px 20px; flex: 1; }

  /* info rows */
  .info-row {
    display: flex; align-items: baseline; gap: 8px;
    font-size: 0.8rem; color: var(--text-2);
    margin-bottom: 8px;
  }
  .info-row:last-child { margin-bottom: 0; }
  .info-label {
    font-size: 0.65rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--text-3);
    min-width: 88px; flex-shrink: 0;
  }
  .info-value { font-weight: 500; }

  /* raza status inline badge */
  .raza-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 9px; border-radius: 40px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: .3px;
  }
  .raza-approved    { background: var(--green-bg);   color: var(--green);  border: 1px solid var(--green-border); }
  .raza-recommended { background: var(--amber-bg);   color: var(--amber);  border: 1px solid var(--amber-border); }
  .raza-submitted   { background: var(--blue-bg);    color: var(--blue);   border: 1px solid var(--blue-border); }
  .raza-none        { background: var(--surface-2);  color: var(--text-3); border: 1px solid var(--border); }

  /* assignments block */
  .assignments-block {
    margin-top: 12px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
  }
  .assignments-title {
    font-size: 0.65rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--text-3);
    margin-bottom: 8px;
  }
  .assignment-item {
    display: flex; flex-direction: column; gap: 2px;
    padding: 7px 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.8rem; color: var(--text-2);
  }
  .assignment-item:last-child { border-bottom: none; padding-bottom: 0; }
  .assignment-item:first-child { padding-top: 0; }
  .assign-name  { font-weight: 600; color: var(--text-1); }
  .assign-meta  { font-size: 0.72rem; color: var(--text-3); }
  .assign-type-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 1px 8px; border-radius: 40px;
    font-size: 0.62rem; font-weight: 700;
    margin-bottom: 4px; width: fit-content;
  }
  .atype-individual { background: var(--blue-bg);   color: var(--blue);  border: 1px solid var(--blue-border); }
  .atype-group      { background: var(--purple-bg); color: var(--purple);border: 1px solid var(--purple-border); }

  /* card footer */
  .miqaat-card-footer {
    padding: 12px 20px 16px;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end;
  }

  /* Submit Raza button */
  .btn-submit-raza {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.8rem; font-weight: 700; letter-spacing: .2px;
    text-decoration: none; cursor: pointer;
    box-shadow: 0 2px 8px rgba(184,134,11,0.28);
    transition: all .18s;
  }
  .btn-submit-raza:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
    box-shadow: 0 4px 14px rgba(184,134,11,0.38);
    transform: translateY(-1px);
    color: #fff; text-decoration: none;
  }
  .btn-submit-raza-disabled {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    color: var(--text-3);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.8rem; font-weight: 700;
    cursor: not-allowed; pointer-events: none;
  }

  /* ── Empty state ── */
  .empty-state-card {
    grid-column: 1 / -1;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 56px 24px;
    text-align: center;
    box-shadow: var(--shadow-sm);
  }
  .empty-state-card .fa { font-size: 2.5rem; color: var(--border); display: block; margin-bottom: 14px; }
  .empty-state-card h5 { font-size: 0.95rem; font-weight: 700; color: var(--text-2); margin-bottom: 6px; }
  .empty-state-card p  { font-size: 0.82rem; color: var(--text-3); margin: 0; }

  /* ── Dues Modal ── */
  #dues-modal {
    position: fixed; inset: 0;
    display: none; align-items: center; justify-content: center;
    padding: 1rem; z-index: 1050;
  }
  #dues-modal .modal-dialog { margin: 0; width: 100%; max-width: 820px; max-height: 90vh; }
  #dues-modal .modal-content {
    max-height: 90vh; display: flex; flex-direction: column;
    border: 1.5px solid var(--border); border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg); overflow: hidden;
  }
  #dues-modal .modal-header {
    padding: 18px 24px; border-bottom: 1px solid var(--border);
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: space-between;
  }
  #dues-modal .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.1rem; font-weight: 600; color: var(--gold); margin: 0;
    display: flex; align-items: center; gap: 8px;
  }
  #dues-modal .modal-title::before { content: '\f19c'; font-family: FontAwesome; font-size: 0.9rem; font-style: normal; }
  #dues-modal .close {
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; cursor: pointer; color: var(--text-2);
    transition: all .15s; padding: 0; line-height: 1;
  }
  #dues-modal .close:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }
  #dues-modal .modal-body {
    overflow-y: auto; flex: 1 1 auto; padding: 20px 24px;
    background: var(--surface);
    -webkit-overflow-scrolling: touch;
  }
  #dues-modal .modal-footer {
    padding: 14px 24px; border-top: 1px solid var(--border);
    background: var(--surface-2);
    display: flex; justify-content: flex-end; gap: 10px;
  }

  /* dues table */
  #dues-content .table { font-size: 0.83rem; border-color: var(--border); width: 100%; }
  #dues-content .table th {
    font-size: 0.68rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase;
    color: var(--text-3); border-color: var(--border); background: var(--surface-2); padding: 8px 12px;
  }
  #dues-content .table td { border-color: var(--border); color: var(--text-2); padding: 9px 12px; }
  #dues-content .table tr:last-child th,
  #dues-content .table tr:last-child td { background: var(--surface-2); font-weight: 700; color: var(--text-1); }
  #dues-content .text-right { text-align: right; }
  #dues-content .alert { border-radius: var(--radius-sm); font-size: 0.83rem; padding: 10px 14px; border-width: 1.5px; margin-bottom: 14px; }
  #dues-content .alert-success { background: var(--green-bg); color: var(--green); border-color: var(--green-border); }
  #dues-content .alert-warning { background: var(--amber-bg); color: var(--amber); border-color: var(--amber-border); }
  #dues-content .alert-info    { background: var(--blue-bg);  color: var(--blue);  border-color: var(--blue-border); }

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
    display: flex; align-items: center; gap: 7px;
  }
  .btn-modal-proceed:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); }
  .btn-modal-proceed::before { content: '\f00c'; font-family: FontAwesome; font-size: 12px; }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Flash messages ── -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="flash-alert flash-danger"><i class="fa fa-times-circle"></i><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('success')): ?>
    <div class="flash-alert flash-success"><i class="fa fa-check-circle"></i><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('warning')): ?>
    <div class="flash-alert flash-warning"><i class="fa fa-exclamation-triangle"></i><?= $this->session->flashdata('warning') ?></div>
  <?php endif; ?>

  <!-- ── Header ── -->
  <div class="page-header-wrap">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn-back-nav">
      <i class="fa fa-arrow-left"></i>
    </a>
    <h1 class="page-heading">Assigned Miqaats</h1>
  </div>
  <p class="page-sub">Your miqaat assignments &amp; raza status</p>

  <hr class="section-divider">

  <!-- ── Grid ── -->
  <div class="miqaat-grid">
    <?php if (!empty($miqaats)):
      foreach ($miqaats as $miqaat):

        /* ── Status class for top bar & badge ── */
        if (isset($miqaat['invoice_status']) && $miqaat['invoice_status']) {
          $cardClass = 'status-completed';
          $badgeClass = 'miq-completed';
          $badgeLabel = 'Completed';
          $badgeIcon  = 'fa-check-circle';
        } elseif ($miqaat['status'] == 1) {
          $cardClass = 'status-active';
          $badgeClass = 'miq-active';
          $badgeLabel = 'Active';
          $badgeIcon  = 'fa-circle';
        } elseif ($miqaat['status'] == 2) {
          $cardClass = 'status-cancelled';
          $badgeClass = 'miq-cancelled';
          $badgeLabel = 'Cancelled';
          $badgeIcon  = 'fa-ban';
        } else {
          $cardClass = 'status-inactive';
          $badgeClass = 'miq-inactive';
          $badgeLabel = 'Inactive';
          $badgeIcon  = 'fa-clock-o';
        }

        /* ── Raza status ── */
        if (!empty($miqaat['raza'])) {
          if (($miqaat['raza']['Janab-status'] ?? 0) == 1) {
            $razaClass = 'raza-approved'; $razaLabel = 'Approved'; $razaIcon = 'fa-check';
          } elseif (($miqaat['raza']['coordinator-status'] ?? 0) == 1) {
            $razaClass = 'raza-recommended'; $razaLabel = 'Recommended'; $razaIcon = 'fa-thumbs-up';
          } else {
            $razaClass = 'raza-submitted'; $razaLabel = 'Submitted'; $razaIcon = 'fa-paper-plane';
          }
        } else {
          $razaClass = 'raza-none'; $razaLabel = 'Not Submitted'; $razaIcon = 'fa-minus';
        }

        /* ── Invoice amount ── */
        $invoice_amt = 0;
        $m_type = $miqaat['type'] ?? 'General';
        $m_year = $miqaat['year'] ?? 'default';
        if (isset($niyaz_amounts[$m_year])) {
          if ($miqaat['assigned_to'] === 'Individual') {
            $invoice_amt = $niyaz_amounts[$m_year][$m_type]['individual'] ?? 0;
          } elseif (in_array($miqaat['assigned_to'], ['Fala ni Niyaz','Fala_ni_Niyaz'])) {
            $invoice_amt = $niyaz_amounts[$m_year][$m_type]['fala'] ?? 0;
          }
        }
    ?>

    <div class="miqaat-card <?= $cardClass ?>">

      <!-- Header band -->
      <div class="miqaat-card-header">
        <h4 class="miqaat-name"><?= htmlspecialchars($miqaat['name']) ?></h4>
        <span class="miq-status-badge <?= $badgeClass ?>">
          <i class="fa <?= $badgeIcon ?>"></i> <?= $badgeLabel ?>
        </span>
      </div>

      <!-- Body -->
      <div class="miqaat-card-body">

        <div class="info-row">
          <span class="info-label">Eng Date</span>
          <span class="info-value"><?= date('d M Y', strtotime($miqaat['date'])) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Hijri Date</span>
          <span class="info-value"><?= htmlspecialchars($miqaat['hijri_date']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Type</span>
          <span class="info-value"><?= htmlspecialchars($miqaat['type']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Assigned To</span>
          <span class="info-value"><?= htmlspecialchars($miqaat['assigned_to']) ?></span>
        </div>
        <div class="info-row">
          <span class="info-label">Raza Status</span>
          <span class="raza-badge <?= $razaClass ?>">
            <i class="fa <?= $razaIcon ?>"></i> <?= $razaLabel ?>
          </span>
        </div>

        <!-- Assignments -->
        <?php if (!empty($miqaat['assignments'])): ?>
        <div class="assignments-block">
          <div class="assignments-title">Assignments</div>
          <?php foreach ($miqaat['assignments'] as $asgn): ?>
            <?php if ($asgn['assign_type'] === 'Individual'): ?>
            <div class="assignment-item">
              <span class="assign-type-badge atype-individual"><i class="fa fa-user"></i> Individual</span>
              <span class="assign-name"><?= htmlspecialchars($asgn['member_name']) ?></span>
              <span class="assign-meta">ID: <?= htmlspecialchars($asgn['member_id']) ?></span>
            </div>
            <?php elseif ($asgn['assign_type'] === 'Group'): ?>
            <div class="assignment-item">
              <span class="assign-type-badge atype-group"><i class="fa fa-users"></i> Group</span>
              <span class="assign-name"><?= htmlspecialchars($asgn['group_name']) ?></span>
              <span class="assign-meta"><strong>Leader:</strong> <?= htmlspecialchars($asgn['group_leader_name']) ?> (<?= htmlspecialchars($asgn['group_leader_id']) ?>)</span>
              <?php if (!empty($asgn['co_leader_name'])): ?>
              <span class="assign-meta"><strong>Co-Leader:</strong> <?= htmlspecialchars($asgn['co_leader_name']) ?> (<?= htmlspecialchars($asgn['co_leader_id']) ?>)</span>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

      </div><!-- /.miqaat-card-body -->

      <!-- Footer -->
      <div class="miqaat-card-footer">
        <?php if ($miqaat['status'] == 1 && empty($miqaat['raza'])): ?>
          <a href="<?= base_url('accounts/submit_miqaat_raza/' . $miqaat['id']) ?>"
             class="btn-submit-raza raza-submit-btn"
             data-invoice-amount="<?= $invoice_amt ?>">
            <i class="fa fa-paper-plane"></i> Submit Raza
          </a>
        <?php else: ?>
          <span class="btn-submit-raza-disabled">
            <i class="fa fa-lock"></i>
            <?= !empty($miqaat['raza']) ? 'Raza Submitted' : 'Unavailable' ?>
          </span>
        <?php endif; ?>
      </div>

    </div><!-- /.miqaat-card -->

    <?php endforeach; ?>
    <?php else: ?>

    <div class="empty-state-card">
      <i class="fa fa-calendar-times-o"></i>
      <h5>No Assigned Miqaats</h5>
      <p>You can apply for Miqaat Raza only after being assigned a Miqaat</p>
    </div>

    <?php endif; ?>
  </div><!-- /.miqaat-grid -->

</div>

<!-- ── Dues Modal ── -->
<div id="dues-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pending Financial Dues</h5>
        <button type="button" class="close" aria-label="Close" onclick="hideDuesModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="dues-content">
          <div style="text-align:center; padding:24px 0; color:var(--text-3);">
            <i class="fa fa-spinner fa-spin" style="font-size:1.5rem; margin-bottom:8px; display:block;"></i>
            Loading dues…
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" onclick="hideDuesModal()">Cancel</button>
        <button type="button" id="dues-confirm" class="btn-modal-proceed">Proceed</button>
      </div>
    </div>
  </div>
</div>
<div id="dues-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1040; backdrop-filter:blur(2px);"></div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
  $(".flash-alert").delay(3000).fadeOut(500);

  function formatINR(n) {
    n = Math.round(Number(n) || 0);
    return '₹' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  function coloredAmount(n) {
    var amt = formatINR(n);
    if (Number(n) > 0) return '<span style="color:var(--red);font-weight:700;">' + amt + '</span>';
    return '<span style="color:var(--green);font-weight:700;">' + amt + '</span>';
  }
  function showDuesModal(html) {
    document.getElementById('dues-content').innerHTML = html;
    document.getElementById('dues-modal').style.display = 'flex';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
  function hideDuesModal() {
    document.getElementById('dues-modal').style.display = 'none';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'none';
    document.body.style.overflow = '';
  }

  $(document).on('click', '.raza-submit-btn', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var invoiceAmt = parseFloat($(this).attr('data-invoice-amount')) || 0;

    showDuesModal('<div style="text-align:center;padding:24px 0;color:var(--text-3);"><i class="fa fa-spinner fa-spin" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>Loading dues…</div>');

    fetch('<?= base_url('accounts/get_member_dues') ?>', { credentials: 'same-origin' })
      .then(function(r) {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(function(data) {
        if (!data || !data.success) {
          showDuesModal('<div class="alert alert-warning">Unable to fetch dues right now. You may still proceed.</div>');
          document.getElementById('dues-confirm').onclick = function() { hideDuesModal(); window.location.href = href; };
          return;
        }
        var d = data.dues;
        var html = '<table class="table table-sm">' +
          '<tr><th>Category</th><th class="text-right">Due</th></tr>' +
          '<tr><td>FMB Takhmeen</td><td class="text-right">'       + coloredAmount(d.fmb_due)          + '</td></tr>' +
          '<tr><td>Sabeel Takhmeen</td><td class="text-right">'    + coloredAmount(d.sabeel_due)        + '</td></tr>' +
          '<tr><td>General Contributions</td><td class="text-right">'+ coloredAmount(d.gc_due)          + '</td></tr>' +
          '<tr><td>Miqaat Invoices</td><td class="text-right">'    + coloredAmount(d.miqaat_due)        + '</td></tr>' +
          '<tr><td>Corpus Fund</td><td class="text-right">'        + coloredAmount(d.corpus_due)        + '</td></tr>' +
          '<tr><td>Ekram Fund</td><td class="text-right">'         + coloredAmount(d.ekram_due || 0)    + '</td></tr>' +
          '<tr><td>Wajebaat</td><td class="text-right">'           + coloredAmount(d.wajebaat_due || 0) + '</td></tr>' +
          '<tr><th>Total</th><th class="text-right">'              + coloredAmount(d.total_due)         + '</th></tr>' +
          '</table>';
        if (d.total_due <= 0) {
          html = '<div class="alert alert-success"><i class="fa fa-check-circle" style="margin-right:6px;"></i>No pending dues. You may proceed.</div>' + html;
        } else {
          html = '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle" style="margin-right:6px;"></i>You have pending dues. Please review before proceeding.</div>' + html;
        }
        if (invoiceAmt > 0) {
          html = '<div class="alert alert-info"><strong>Notice:</strong> An invoice of ' + formatINR(invoiceAmt) + ' will be automatically generated upon submitting this Raza.</div>' + html;
        }
        if (data.miqaat_invoices && Array.isArray(data.miqaat_invoices) && data.miqaat_invoices.length > 0) {
          var invHtml = '<hr style="border-color:var(--border);"><h6 style="font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:var(--text-3);">Miqaat / Member Invoices</h6>' +
            '<table class="table table-sm table-bordered"><thead><tr><th>Assigned to</th><th>Invoice</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead><tbody>';
          data.miqaat_invoices.forEach(function(inv) {
            invHtml += '<tr><td>' + (inv.owner_name || inv.user_id || '') + '</td><td>' + (inv.miqaat_name || '#' + inv.miqaat_id) + '</td><td class="text-right">' + formatINR(inv.amount || 0) + '</td><td class="text-right">' + formatINR(inv.paid_amount || 0) + '</td><td class="text-right">' + coloredAmount(inv.due_amount || 0) + '</td></tr>';
          });
          invHtml += '</tbody></table>';
          html += invHtml;
        }
        showDuesModal(html);
        document.getElementById('dues-confirm').onclick = function() {
          fetch('<?= base_url('accounts/send_dues_email') ?>', { method: 'POST', credentials: 'same-origin' })
            .then(function(r) { return r.json().catch(function() { return {}; }); })
            .then(function() { hideDuesModal(); window.location.href = href; })
            .catch(function() { hideDuesModal(); window.location.href = href; });
        };
      })
      .catch(function() {
        showDuesModal('<div class="alert alert-warning">Unable to fetch dues (network issue). You may still proceed.</div>');
        document.getElementById('dues-confirm').onclick = function() { hideDuesModal(); window.location.href = href; };
      });
  });
</script>