<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
  .hijri-label  { font-size: 0.85rem; color: var(--text-3); font-weight: 500; letter-spacing: .5px; }

  /* ── Action buttons ── */
  .action-btn {
    position: relative; display: flex; flex-direction: row;
    align-items: center; justify-content: flex-start;
    gap: 10px; padding: 10px 12px; border-radius: 10px;
    text-decoration: none; overflow: hidden; height: 52px;
    transition: transform .15s ease, box-shadow .15s ease;
    box-shadow: var(--shadow);
  }
  .action-btn::after {
    content: ''; position: absolute; inset: 0;
    background: rgba(255,255,255,0); transition: background .2s;
  }
  .action-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); text-decoration: none; }
  .action-btn:hover::after { background: rgba(255,255,255,0.08); }
  .action-btn .stat-icon-wrap {
    position: relative; flex-shrink: 0;
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.22); backdrop-filter: blur(4px);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.3);
  }
  .action-btn .stat-icon-wrap .fa { font-size: 0.85rem; text-shadow: 0 1px 3px rgba(0,0,0,0.3); }
  .action-btn .count-badge {
    position: absolute; top: -5px; right: -8px;
    background: #fff; color: #222; padding: 1px 5px;
    font-size: 0.52rem; font-weight: 700; border-radius: 40px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2); border: 1px solid rgba(0,0,0,0.06);
    letter-spacing: .3px; text-transform: uppercase;
  }
  .action-btn-text { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
  .action-btn-title { font-size: 0.6rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #fff; line-height: 1.25; }
  .action-btn .stat-empty { font-size: 0.52rem; color: rgba(255,255,255,0.65); letter-spacing: .3px; }

  @media (max-width: 575px) {
    .action-btn { height: 48px; padding: 8px 10px; gap: 8px; }
    .action-btn .stat-icon-wrap { width: 28px; height: 28px; }
    .action-btn-title { font-size: 0.55rem; }
  }

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
  }
  .dash-card-header .card-title .fa {
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
  .badge-link    { background: var(--blue-bg);  color: var(--blue);  border: 1px solid #bfdbfe; text-decoration: none; }
  .badge-link:hover { opacity: .8; }

  .dash-card-body { padding: 18px 20px; flex: 1; }

  /* ── Stat tiles ── */
  .stat-tile {
    background: var(--surface-2); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 14px 12px;
    text-align: center; transition: background .15s;
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
  }
  .btn-view:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

  /* ── No dues state ── */
  .no-dues { display: flex; align-items: center; gap: 10px; padding: 10px 0; }
  .no-dues .fa { font-size: 1.3rem; color: var(--green); }
  .no-dues span { font-size: 0.85rem; color: var(--text-3); font-weight: 500; }

  /* ── RSVP cards ── */
  .rsvp-tile {
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 16px 10px; text-align: center;
    flex: 1; display: flex; flex-direction: column;
    justify-content: space-between; gap: 6px;
  }
  .rsvp-tile .rsvp-label { font-size: 0.65rem; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: .5px; }
  .rsvp-tile .rsvp-num  { font-size: 2rem; font-weight: 800; line-height: 1; text-decoration: none; }

  /* ── Raza table ── */
  .dash-table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
  .dash-table th { font-size: 0.65rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); padding: 0 0 10px; border-bottom: 1px solid var(--border); }
  .dash-table td { padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--text-1); vertical-align: middle; }
  .dash-table tr:last-child td { border-bottom: none; }
  .dash-table .date-sub { font-size: 0.68rem; color: var(--text-3); margin-top: 3px; }
  .status-badge { display: inline-block; padding: 3px 9px; border-radius: 40px; font-size: 0.65rem; font-weight: 700; letter-spacing: .3px; }
  .s-pending  { background: #eff6ff; color: #1d4ed8; }
  .s-approved { background: #eaf4ee; color: #1a6645; }
  .s-rejected { background: #fef2f2; color: #b91c1c; }
  .s-recommended   { background: #fffbeb; color: #b45309; }
  .s-not-recommended { background: #f5f3ff; color: #6d28d9; }

  /* ── FMB Signup table ── */
  .signup-table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
  .signup-table td { padding: 10px 0; }
  .signup-table td:first-child { color: var(--text-2); font-weight: 500; }

  /* ── Info row (small text line) ── */
  .info-row { font-size: 0.72rem; color: var(--text-3); margin-top: 12px; text-align: center; }

  /* ── Separator ── */
  .section-sep { border: none; border-top: 1px solid var(--border); margin: 4px 0 14px; }

  /* ── Breakdown row (Sabeel) ── */
  .breakdown-row { display: flex; gap: 10px; margin-top: 12px; }
  .breakdown-row .stat-tile { flex: 1; }

  /* ── Responsive ── */
  @media (max-width: 767px) {
    .amount-big { font-size: 1.3rem; }
    .rsvp-tile .rsvp-num { font-size: 1.6rem; }
  }
</style>

<div class="container margintopcontainer pt-5">
  <h1 class="text-center page-heading mb-1">Welcome to Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></h1>
  <p class="hijri-label text-center mb-4"><?php echo $hijri_date ?></p>
  <hr style="border-color:var(--border);">

  <!-- ── Quick Action Buttons ── -->
  <div class="row justify-content-center">
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/assigned_miqaats') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-calendar-check-o"></i>
          <?php if (isset($assigned_miqaats_count) && (int)$assigned_miqaats_count > 0): ?>
            <span class="count-badge"><?php echo $assigned_miqaats_count; ?></span>
          <?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Miqaat Public Event Raza</span>
          <?php if (!isset($assigned_miqaats_count) || (int)$assigned_miqaats_count === 0): ?>
            <span class="stat-empty">None yet</span>
          <?php endif; ?>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?= base_url('Umoor12/MyRazaRequest?value=Private-Event') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-calendar-plus"></i></div>
        <div class="action-btn-text"><span class="action-btn-title">Kaaraj Private Event Raza</span></div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/Umoor') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-edit"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Other Non Event Raza</span>
          <span class="stat-empty">Create</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/MyRazaRequest') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-files-o"></i></div>
        <div class="action-btn-text"><span class="action-btn-title">Submitted Applications</span></div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/viewfmbtakhmeen') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-cutlery"></i>
          <?php if (!empty($fmb_due_badge)): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">FMB Due</span>
          <span class="stat-empty">Overview</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/viewsabeeltakhmeen') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-money"></i>
          <?php if (isset($sabeel_takhmeen_details["total_due"]) && (int)$sabeel_takhmeen_details["total_due"] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Sabeel Due</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/laagat_rent') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-calculator"></i>
          <?php if (isset($laagat_summary['total_due']) && (float)$laagat_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Laagat &amp; Rent</span>
          <span class="stat-empty">Invoices</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/qardanhasana') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-handshake-o"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Qardan Hasana</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/madresa') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-graduation-cap"></i>
          <?php if (isset($madresa_summary['total_due']) && (float)$madresa_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Madresa</span>
          <span class="stat-empty">Fees &amp; Dues</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/rsvp_list') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-calendar"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Miqaat &amp; RSVP</span>
          <span class="stat-empty">Events</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/fmbweeklysignup') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-spoon"></i>
          <?php
          $weekly_signup_days = 0;
          if (isset($signup_data) && is_array($signup_data)) {
            $weekly_signup_days = count(array_filter($signup_data, fn($d) => isset($d['want_thali']) && (string)$d['want_thali'] === '1'));
          }
          $signup_days = isset($signup_days) && is_array($signup_days) ? count($signup_days) : 0;
          if ($weekly_signup_days > 0): ?>
            <span class="count-badge"><?php echo $weekly_signup_days . "/" . $signup_days; ?></span>
          <?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Thaali Signup</span>
          <span class="stat-empty"><?php echo $weekly_signup_days === 0 ? 'Start now' : 'Days chosen'; ?></span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/viewmenu') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-list"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Thaali Menu</span>
          <span class="stat-empty">FMB</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/FMBFeedback') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-comments"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Thaali Feedback</span>
          <span class="stat-empty">Give Feedback</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/corpusfunds') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-donate"></i>
          <?php $cf_due_badge = (float)($corpus_summary['outstanding'] ?? 0); if ($cf_due_badge > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Corpus Funds</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/ekramfunds') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-gift"></i>
          <?php $ef_due_badge = (float)($ekram_summary['outstanding'] ?? 0); if ($ef_due_badge > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text">
          <span class="action-btn-title">Ekram Funds</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/wajebaat') ?>" class="action-btn">
        <div class="stat-icon-wrap">
          <i class="fa fa-coins"></i>
          <?php if (isset($wajebaat['due']) && (float)$wajebaat['due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
        </div>
        <div class="action-btn-text"><span class="action-btn-title">Wajebaat</span></div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/appointment') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-calendar"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">Appointments</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
    <div class="col-6 mt-2 col-md-4 col-xl-3">
      <a href="<?php echo base_url('accounts/profile') ?>" class="action-btn">
        <div class="stat-icon-wrap"><i class="fa fa-user"></i></div>
        <div class="action-btn-text">
          <span class="action-btn-title">My Profile</span>
          <span class="stat-empty">View</span>
        </div>
      </a>
    </div>
  </div>

  <!-- ── Dashboard Cards Grid ── -->
  <div class="row mt-4">

    <!-- FMB Dues -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-cutlery"></i> FMB Dues</span>
          <?php if (isset($fmb_takhmeen_details["total_due"]) && (float)$fmb_takhmeen_details["total_due"] > 0): ?>
            <span class="badge-pill badge-danger">Pending</span>
          <?php else: ?>
            <span class="badge-pill badge-success">Clear</span>
          <?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php if (isset($fmb_takhmeen_details["total_due"]) && (float)$fmb_takhmeen_details["total_due"] > 0): ?>
            <div class="stat-tile mb-3">
              <div class="tile-label">Total Due</div>
              <div class="tile-value red amount-big">&#8377;<?php echo format_inr_no_decimals($fmb_takhmeen_details['total_due'] ?? 0); ?></div>
            </div>
            <a href="<?php echo base_url('accounts/viewfmbtakhmeen'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
          <?php else: ?>
            <div class="no-dues"><i class="fa fa-check-circle"></i><span>No FMB dues pending</span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Sabeel Dues -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-money"></i> Sabeel Dues</span>
          <?php if (isset($sabeel_takhmeen_details["total_due"]) && $sabeel_takhmeen_details["total_due"] > 0): ?>
            <span class="badge-pill badge-danger">Pending</span>
          <?php else: ?>
            <span class="badge-pill badge-success">Clear</span>
          <?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php
          $cy_total = (float)($sabeel_takhmeen_details['current_year_total'] ?? 0);
          $cy_paid  = (float)($sabeel_takhmeen_details['current_year_paid'] ?? 0);
          $cy_due   = (float)($sabeel_takhmeen_details['current_year_due'] ?? max(0, $cy_total - $cy_paid));
          $currentCompositeYear = isset($sabeel_takhmeen_details['current_year']) ? (string)$sabeel_takhmeen_details['current_year'] : '';
          ?>
          <div class="stat-tile mb-3">
            <div class="tile-label">Total Due <?php echo $currentCompositeYear ? '— ' . htmlspecialchars($currentCompositeYear) : ''; ?></div>
            <div class="tile-value red amount-big">&#8377;<?php echo format_inr_no_decimals($sabeel_takhmeen_details['total_due'] ?? 0); ?></div>
          </div>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Takhmeen</div>
              <div class="tile-value blue">&#8377;<?php echo format_inr_no_decimals($cy_total); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Paid</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($cy_paid); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Pending</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($cy_due); ?></div>
            </div>
          </div>
          <a href="<?php echo base_url('accounts/viewsabeeltakhmeen'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
        </div>
      </div>
    </div>

    <!-- Pending Raza -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-files-o"></i> Pending Raza Requests</span>
        </div>
        <div class="dash-card-body" style="overflow-y:auto; max-height:260px;">
          <?php if (!empty($raza)): ?>
          <table class="dash-table">
            <thead>
              <tr>
                <th>Raza For</th>
                <th>Status</th>
                <th style="text-align:center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($raza as $r): ?>
              <tr>
                <td>
                  <?php echo htmlspecialchars($r['razaType']); ?>
                  <div class="date-sub"><?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])); ?></div>
                </td>
                <td>
                  <?php
                  $sMap = [0=>'s-pending|Pending', 1=>'s-recommended|Recommended', 2=>'s-approved|Approved', 3=>'s-rejected|Rejected', 4=>'s-not-recommended|Not Recommended'];
                  [$cls, $lbl] = explode('|', $sMap[$r['status']] ?? 's-pending|Pending');
                  echo '<span class="status-badge '.$cls.'">'.$lbl.'</span>';
                  ?>
                </td>
                <td style="text-align:center;">
                  <a href="<?php echo base_url('accounts/MyRazaRequest'); ?>" title="View"><i class="fa fa-eye" style="color:var(--blue);"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php else: ?>
            <div class="no-dues"><i class="fa fa-check-circle"></i><span>No pending applications</span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- RSVP -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-calendar"></i> RSVP</span>
          <a href="<?php echo base_url('accounts/rsvp_list'); ?>" class="badge-pill badge-link">All Events &rarr;</a>
        </div>
        <div class="dash-card-body">
          <?php
          $approved_miqaats_count = 0;
          if (isset($miqaats) && is_array($miqaats)) {
            $today = strtotime('today');
            $approved_miqaats = array_filter($miqaats, function($m) use ($today) {
              $status = is_array($m) ? ($m['Janab-status'] ?? $m['janab_status'] ?? $m['status'] ?? null) : ($m->{'Janab-status'} ?? $m->janab_status ?? $m->status ?? null);
              $dateStr = is_array($m) ? ($m['date'] ?? $m['miqaat_date'] ?? $m['event_date'] ?? $m['start_date'] ?? null) : ($m->date ?? $m->miqaat_date ?? $m->event_date ?? $m->start_date ?? null);
              $isApproved = is_string($status) ? trim($status)==='1' : (is_numeric($status) ? (int)$status===1 : ($status===true));
              $isUpcoming = !empty($dateStr) && ($d = strtotime($dateStr)) !== false && $d >= $today;
              return $isApproved && $isUpcoming;
            });
            $approved_miqaats_count = count($approved_miqaats);
          }
          $submitted_rsvps_count = (isset($rsvp_overview) && is_array($rsvp_overview)) ? count($rsvp_overview) : 0;
          $pending_rsvps_count = max(0, $approved_miqaats_count - $submitted_rsvps_count);
          ?>
          <div class="d-flex gap-3" style="gap:12px;">
            <a href="<?php echo base_url('accounts/rsvp_list'); ?>" class="rsvp-tile" style="text-decoration:none;">
              <div class="rsvp-label">Approved Miqaats</div>
              <div class="rsvp-num" style="color:var(--blue);"><?php echo $approved_miqaats_count; ?></div>
            </a>
            <a href="<?php echo base_url('accounts/rsvp_list'); ?>" class="rsvp-tile" style="text-decoration:none;">
              <div class="rsvp-label">Pending RSVPs</div>
              <div class="rsvp-num" style="color:var(--amber);"><?php echo $pending_rsvps_count; ?></div>
            </a>
            <a href="<?php echo base_url('accounts/rsvp_list'); ?>" class="rsvp-tile" style="text-decoration:none;">
              <div class="rsvp-label">Submitted</div>
              <div class="rsvp-num" style="color:var(--green);"><?php echo $submitted_rsvps_count; ?></div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- FMB Thaali Signup -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-spoon"></i> FMB Thaali Signup</span>
          <a href="<?php echo base_url('accounts/viewmenu'); ?>" target="_blank" class="badge-pill badge-link">Menu &rarr;</a>
        </div>
        <div class="dash-card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
            <div>
              <div style="font-size:0.8rem; font-weight:600; color:var(--text-2);"><?php echo isset($current_hijri_month_label) ? $current_hijri_month_label : ''; ?></div>
              <?php if (!empty($fmb_today_status['menu_items'])): ?>
                <div style="font-size:0.72rem; color:var(--text-3); margin-top:4px;">Today: <?php echo htmlspecialchars(implode(', ', $fmb_today_status['menu_items'])); ?></div>
              <?php endif; ?>
            </div>
            <div>
              <?php
              $signup_day_count = 0; $signup_status_class = 'primary'; $signup_status_text = 'Sign-up Now';
              if (isset($signup_data) && !empty($signup_data)) {
                $filtered = array_filter($signup_data, fn($item) => $item['want_thali'] == '1');
                if (count($filtered) == 0) { $signup_status_class = 'primary'; $signup_status_text = 'Sign-up Now'; }
                elseif (count($filtered) < $signup_days) { $signup_day_count = count($filtered); $signup_status_class = 'warning'; $signup_status_text = 'Partially Signed Up'; }
                else { $signup_status_class = 'success'; $signup_status_text = 'Signed Up'; }
              }
              $btnColors = ['primary'=>'var(--blue)', 'warning'=>'var(--amber)', 'success'=>'var(--green)'];
              $col = $btnColors[$signup_status_class] ?? 'var(--blue)';
              ?>
              <a href="<?php echo base_url('accounts/fmbweeklysignup'); ?>" class="btn-view" style="border-color:<?php echo $col; ?>; color:<?php echo $col; ?>;">
                <?php echo $signup_status_text; ?>
                <?php if ($signup_day_count > 0 && $signup_day_count < $signup_days): ?> (<?php echo $signup_day_count; ?>/<?php echo $signup_days; ?>)<?php endif; ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FMB Feedback -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-comments"></i> FMB Feedback</span>
        </div>
        <div class="dash-card-body">
          <?php
          $mf_class = isset($month_feedback_status_class) ? $month_feedback_status_class : 'secondary';
          $mf_text  = isset($month_feedback_status_text) ? $month_feedback_status_text : 'No Sign Ups';
          $mf_signed = (int)($month_feedback_signed ?? 0);
          $mf_given  = (int)($month_feedback_given ?? 0);
          $show_counts = $mf_signed > 0 && $mf_given > 0 && $mf_given < $mf_signed && $mf_text === 'Partially Given';
          $fbColors = ['success'=>'var(--green)', 'warning'=>'var(--amber)', 'primary'=>'var(--blue)', 'secondary'=>'var(--text-3)'];
          $fbc = $fbColors[$mf_class] ?? 'var(--text-3)';
          ?>
          <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
            <div style="font-size:0.8rem; color:var(--text-2); font-weight:500;"><?php echo isset($month_feedback_range) ? htmlspecialchars($month_feedback_range) : ''; ?></div>
            <a href="<?php echo base_url('accounts/FMBFeedback'); ?>" class="btn-view" style="border-color:<?php echo $fbc; ?>; color:<?php echo $fbc; ?>;">
              <?php echo $mf_text; ?><?php if ($show_counts): ?> (<?php echo $mf_given; ?>/<?php echo $mf_signed; ?>)<?php endif; ?>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Corpus Funds -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-donate"></i> Corpus Funds</span>
          <?php $cf_out = (float)($corpus_summary['outstanding'] ?? 0); if ($cf_out > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php $cf_assgn = (float)($corpus_summary['assigned_total'] ?? 0); $fundsCnt = (int)($corpus_summary['funds_count'] ?? 0); ?>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total Assigned</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($cf_assgn); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Outstanding</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($cf_out); ?></div>
            </div>
          </div>
          <div class="info-row">Funds: <?php echo $fundsCnt; ?></div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/corpusfunds'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

    <!-- Ekram Funds -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-gift"></i> Ekram Funds</span>
          <?php $ef_out = (float)($ekram_summary['outstanding'] ?? 0); if ($ef_out > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php $ef_assgn = (float)($ekram_summary['assigned_total'] ?? 0); $ef_count = (int)($ekram_summary['funds_count'] ?? 0); ?>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total Assigned</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($ef_assgn); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Outstanding</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($ef_out); ?></div>
            </div>
          </div>
          <div class="info-row">Funds: <?php echo $ef_count; ?></div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/ekramfunds'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

    <!-- Wajebaat -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-coins"></i> Wajebaat</span>
          <?php if (isset($wajebaat['due']) && (float)$wajebaat['due'] > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php $w = $wajebaat ?? null; ?>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total Assigned</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($w['amount'] ?? 0); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Outstanding</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($w['due'] ?? 0); ?></div>
            </div>
          </div>
          <?php $last = !empty($w['updated_at']) ? $w['updated_at'] : ($w['created_at'] ?? ''); ?>
          <div class="info-row">Last updated: <?php echo $last ? date('d M Y', strtotime($last)) : 'Not available'; ?></div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/wajebaat'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

    <!-- Qardan Hasana -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-handshake-o"></i> Qardan Hasana</span>
          <?php $qh_due = (float)($qardan_summary['due'] ?? 0); if ($qh_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <?php $qh = $qardan_summary ?? []; ?>
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total</div>
              <div class="tile-value gold">&#8377;<?php echo format_inr_no_decimals($qh['total'] ?? 0); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Due</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($qh['due'] ?? 0); ?></div>
            </div>
          </div>
          <div class="info-row">Taher: &#8377;<?php echo format_inr_no_decimals($qh['taher'] ?? 0); ?> &nbsp;|&nbsp; Husain: &#8377;<?php echo format_inr_no_decimals($qh['husain'] ?? 0); ?></div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/qardanhasana'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

    <!-- Laagat & Rent -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-calculator"></i> Laagat &amp; Rent</span>
          <?php $lr_due = (float)($laagat_summary['total_due'] ?? 0); if ($lr_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($laagat_summary['total_amount'] ?? 0); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Due</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($laagat_summary['total_due'] ?? 0); ?></div>
            </div>
          </div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/laagat_rent'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

    <!-- Madresa -->
    <div class="col-12 col-md-6 p-2">
      <div class="dash-card h-100">
        <div class="dash-card-header">
          <span class="card-title"><i class="fa fa-graduation-cap"></i> Madresa</span>
          <?php $md_due = (float)($madresa_summary['total_due'] ?? 0); if ($md_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
        </div>
        <div class="dash-card-body">
          <div class="breakdown-row mb-3">
            <div class="stat-tile">
              <div class="tile-label">Total Fees</div>
              <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($madresa_summary['total_fees'] ?? 0); ?></div>
            </div>
            <div class="stat-tile">
              <div class="tile-label">Due</div>
              <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($madresa_summary['total_due'] ?? 0); ?></div>
            </div>
          </div>
          <div class="text-center mt-3"><a href="<?php echo base_url('accounts/madresa'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
        </div>
      </div>
    </div>

  </div><!-- /.row cards -->
</div><!-- /.container -->

<script>
const colors = [
  ["#006a3f","#fff"],["#27ae60","#fff"],["#8e44ad","#fff"],["#f39c12","#fff"],
  ["#c0392b","#fff"],["#2980b9","#fff"],["#8e44ad","#fff"],["#f39c12","#fff"],
  ["#d35400","#fff"],["#006a3f","#fff"],["#2980b9","#fff"],["#27ae60","#fff"],
  ["#8e44ad","#fff"],["#870000","#fff"],["#d35400","#fff"],["#2980b9","#fff"],
  ["#c0392b","#fff"],["#870000","#fff"],["#c0392b","#fff"],["#f5e9c0","#000"],
  ["#f39c12","#fff"],["#870000","#fff"],["#d35400","#fff"],["#006a3f","#fff"],
  ["#27ae60","#fff"],
];
$(document).ready(function () {
  $(".action-btn").each(function (i) {
    if (colors[i]) {
      this.style.backgroundColor = colors[i][0];
      this.style.color = colors[i][1];
    }
  });
});
</script>