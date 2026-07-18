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
    --border-light:#f0ece0;
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

  /* ── Layout Wrappers Scoped to Mumin App ── */
  #muminApp, #muminApp *, #muminApp *::before, #muminApp *::after { box-sizing: border-box; }
  #muminApp { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); background: var(--bg); min-height: 100vh; }
  #muminApp a { color: inherit; }

  #muminApp .mumin-root { display: flex; min-height: 100vh; padding-top: 60px; }

  #muminApp .mumin-sidebar {
    width: 280px; flex-shrink: 0;
    position: sticky; top: 60px; height: calc(100vh - 60px);
    overflow-y: auto; background: var(--surface);
    border-right: 1px solid var(--border);
    padding: 20px 14px 40px;
    scrollbar-width: thin; scrollbar-color: var(--border) transparent;
    z-index: 100;
  }

  #muminApp .mumin-content { flex: 1; min-width: 0; padding: 24px 24px 60px; }

  /* ── Sidebar Nav ── */
  #muminApp .menu-title { font-weight: 800; font-size: .84rem; color: var(--text-2); margin-bottom: 12px; padding: 0 4px; display: flex; align-items: center; gap: 8px; }
  #muminApp .menu-title .sb-ico { width: 26px; height: 26px; border-radius: 7px; background: var(--gold-muted); color: var(--gold); display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; }

  #muminApp .sb-search { display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1.5px solid var(--border); border-radius: 8px; padding: 7px 10px; margin-bottom: 16px; transition: border-color .15s; }
  #muminApp .sb-search:focus-within { border-color: var(--gold); }
  #muminApp .sb-search i { color: var(--text-3); font-size: .8rem; }
  #muminApp .sb-search input { border: none; background: transparent; outline: none; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .81rem; color: var(--text-1); width: 100%; }
  #muminApp .sb-search input::placeholder { color: var(--text-3); }
  #muminApp .sb-search-clear { border: none; background: none; cursor: pointer; color: var(--text-3); font-size: .78rem; padding: 0; display: none; }
  #muminApp .sb-search-clear.visible { display: inline-block; }

  #muminApp .menu-section {
    font-size: .62rem; font-weight: 800; letter-spacing: 1.1px; text-transform: uppercase;
    color: var(--text-3); padding: 14px 6px 6px;
    display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-light); margin-top: 8px;
  }
  #muminApp .menu-section:first-of-type { border-top: none; margin-top: 0; }

  #muminApp .menu-list { list-style: none; margin: 0; padding: 0; }
  #muminApp .menu-list li + li { margin-top: 2px; }

  #muminApp .menu-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 8px;
    color: var(--text-2); font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: background .14s, color .14s;
    position: relative;
  }
  #muminApp .menu-item:hover { background: var(--gold-muted); color: var(--gold); text-decoration: none; }

  #muminApp .menu-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--surface-2); color: var(--text-3); font-size: .78rem; flex-shrink: 0;
    transition: background .14s, color .14s;
  }
  #muminApp .menu-item:hover .menu-icon { background: var(--gold-muted); color: var(--gold); }
  #muminApp .menu-label { flex: 1; white-space: normal; word-break: break-word; }

  #muminApp .menu-item .count-badge {
    background: var(--red); color: #fff; padding: 1px 5px;
    font-size: 0.55rem; font-weight: 800; border-radius: 40px;
    margin-left: 6px; border: 1px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  /* ── Mobile toolbar ── */
  #muminApp .mob-bar {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; padding: 9px 13px; margin-bottom: 14px; box-shadow: var(--shadow-sm);
  }
  #muminApp .mob-btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 6px 13px;
    border-radius: 40px; background: var(--gold-muted); color: var(--gold);
    font-weight: 700; font-size: .82rem; border: 1px solid rgba(184,134,11,.25);
    cursor: pointer; outline: none;
  }
  #muminApp .mob-btn .mic { width: 24px; height: 24px; border-radius: 6px; background: var(--surface); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: .78rem; }

  #muminApp .sidebar-overlay { position: fixed; inset: 0; background: rgba(26,22,16,.4); z-index: 1040; display: none; }
  #muminApp .sidebar-overlay.show { display: block; }
  #muminApp .sidebar-close-btn {
    position: absolute; top: 10px; right: 10px; width: 28px; height: 28px;
    border: none; border-radius: 7px; background: var(--gold-muted); color: var(--gold);
    font-size: .95rem; line-height: 1; display: none; align-items: center; justify-content: center; cursor: pointer;
  }

  /* ── Dashboard Banner Header ── */
  #muminApp .anj-header { margin-bottom: 22px; }
  #muminApp .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 22px; padding: 22px 26px;
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
  }
  #muminApp .anj-header-inner::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  #muminApp .anj-header-inner::after {
    content: ''; position: absolute; right: -50px; top: -50px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
    pointer-events: none;
  }
  #muminApp .anj-title-group { position: relative; z-index: 1; }
  #muminApp .anj-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 4px; }
  #muminApp .anj-title { font-family: 'Literata', Georgia, serif; font-size: 1.6rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0; }
  #muminApp .anj-title span { color: rgba(255,255,255,.72); font-size: 0.9rem; font-weight: 500; font-family: 'Plus Jakarta Sans', sans-serif; }
  #muminApp .anj-badge {
    position: relative; z-index: 1;
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    border-radius: 14px; padding: 10px 16px; backdrop-filter: blur(6px);
    text-align: center; flex-shrink: 0;
  }
  #muminApp .anj-badge-val { font-size: 1.3rem; font-weight: 800; color: #fff; line-height: 1; display: block; }
  #muminApp .anj-badge-lbl { font-size: .64rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 3px; display: block; }

  /* ── Dashboard Cards ── */
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

  /* ── Stat Tiles ── */
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

  /* ── Amount Display ── */
  .amount-big { font-size: 1.6rem; font-weight: 800; letter-spacing: -1px; }

  /* ── View Details Btn ── */
  .btn-view {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; padding: 7px 16px;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    color: var(--text-2); background: var(--surface); text-decoration: none;
    transition: all .15s;
  }
  .btn-view:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

  /* ── No Dues State ── */
  .no-dues { display: flex; align-items: center; gap: 10px; padding: 10px 0; }
  .no-dues .fa { font-size: 1.3rem; color: var(--green); }
  .no-dues span { font-size: 0.85rem; color: var(--text-3); font-weight: 500; }

  /* ── RSVP Cards ── */
  .rsvp-tile {
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 16px 10px; text-align: center;
    flex: 1; display: flex; flex-direction: column;
    justify-content: space-between; gap: 6px;
  }
  .rsvp-tile .rsvp-label { font-size: 0.65rem; font-weight: 700; color: var(--text-3); text-transform: uppercase; letter-spacing: .5px; }
  .rsvp-tile .rsvp-num  { font-size: 2rem; font-weight: 800; line-height: 1; text-decoration: none; }

  /* ── Raza Table ── */
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

  /* ── FMB Signup Table ── */
  .signup-table { width: 100%; font-size: 0.8rem; border-collapse: collapse; }
  .signup-table td { padding: 10px 0; }
  .signup-table td:first-child { color: var(--text-2); font-weight: 500; }

  /* ── Info Row (Small text line) ── */
  .info-row { font-size: 0.72rem; color: var(--text-3); margin-top: 12px; text-align: center; }

  /* ── Separator ── */
  .section-sep { border: none; border-top: 1px solid var(--border); margin: 4px 0 14px; }

  /* ── Breakdown Row (Sabeel) ── */
  .breakdown-row { display: flex; gap: 10px; margin-top: 12px; }
  .breakdown-row .stat-tile { flex: 1; }

  /* ── Mobile responsiveness ── */
  @media (max-width: 991px) {
    #muminApp .mumin-root { padding-top: 110px; }
    #muminApp .mumin-sidebar {
      position: fixed; top: 0; left: 0; height: 100vh;
      transform: translateX(-100%); transition: transform .25s;
      z-index: 1050; width: 290px; padding-top: 16px;
    }
    #muminApp .mumin-sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
    #muminApp .mumin-content { padding: 12px 11px 48px; }
    #muminApp .sidebar-close-btn { display: inline-flex; }
  }
  @media (min-width: 992px) { #muminApp .mob-bar { display: none !important; } }

  @media (max-width: 767px) {
    .amount-big { font-size: 1.3rem; }
    .rsvp-tile .rsvp-num { font-size: 1.6rem; }
  }
</style>

<div id="muminApp">
  <div class="mumin-root">

    <!-- ══ SIDEBAR ══ -->
    <aside class="mumin-sidebar" id="muminSidebar">
      <button class="sidebar-close-btn" id="sidebarCloseBtn">&times;</button>
      <div class="sidebar-menu">
        <div class="menu-title">
          <span class="sb-ico">
            <i class="fa fa-tachometer"></i>
          </span>Quick Menu
        </div>
        <div class="sb-search">
          <i class="fa fa-search"></i>
          <input id="quickMenuSearch" type="text" placeholder="Search menu..." autocomplete="off">
          <button type="button" id="quickMenuClear" class="sb-search-clear">&times;</button>
        </div>

        <div class="menu-section">Raza</div>
        <ul class="menu-list">
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/assigned_miqaats') ?>">
              <span class="menu-icon" style="background:#eaf4ee; color:#1a6645;"><i class="fa fa-calendar-check-o"></i></span>
              <span class="menu-label">Miqaat Public Event Raza</span>
              <?php if (isset($assigned_miqaats_count) && (int)$assigned_miqaats_count > 0): ?>
                <span class="count-badge"><?php echo $assigned_miqaats_count; ?></span>
              <?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?= base_url('Umoor12/MyRazaRequest?value=Private-Event') ?>">
              <span class="menu-icon" style="background:#eaf4ee; color:#1a6645;"><i class="fa fa-calendar-plus"></i></span>
              <span class="menu-label">Kaaraj Private Event Raza & Hall Booking</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/Umoor') ?>">
              <span class="menu-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa fa-edit"></i></span>
              <span class="menu-label">Other Non Event Raza</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/MyRazaRequest') ?>">
              <span class="menu-icon" style="background:#fffbeb; color:#b45309;"><i class="fa fa-files-o"></i></span>
              <span class="menu-label">Submitted Applications</span>
            </a>
          </li>
        </ul>

        <div class="menu-section">FMB / Food</div>
        <ul class="menu-list">
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/viewfmbtakhmeen') ?>">
              <span class="menu-icon" style="background:#fef2f2; color:#b91c1c;"><i class="fa fa-cutlery"></i></span>
              <span class="menu-label">FMB Takhmeen</span>
              <?php if (!empty($fmb_due_badge)): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/fmb_contributions') ?>">
              <span class="menu-icon" style="background:#fef2f2; color:#d97706;"><i class="fa fa-gift"></i></span>
              <span class="menu-label">FMB Contributions</span>
              <?php if (!empty($fmb_extra_due_badge)): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/fmbweeklysignup') ?>">
              <span class="menu-icon" style="background:#eff6ff; color:#1d4ed8;"><i class="fa fa-spoon"></i></span>
              <span class="menu-label">Thaali Signup</span>
              <?php
              $weekly_signup_days = 0;
              if (isset($signup_data) && is_array($signup_data)) {
                $weekly_signup_days = count(array_filter($signup_data, fn($d) => isset($d['want_thali']) && (string)$d['want_thali'] === '1'));
              }
              $signup_days = isset($signup_days) && is_array($signup_days) ? count($signup_days) : 0;
              if ($weekly_signup_days > 0): ?>
                <span class="count-badge"><?php echo $weekly_signup_days . "/" . $signup_days; ?></span>
              <?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/viewmenu') ?>">
              <span class="menu-icon" style="background:#eaf4ee; color:#1a6645;"><i class="fa fa-list"></i></span>
              <span class="menu-label">Thaali Menu</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/FMBFeedback') ?>">
              <span class="menu-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa fa-comments"></i></span>
              <span class="menu-label">Thaali Feedback</span>
            </a>
          </li>
        </ul>

        <div class="menu-section">Finance &amp; Funds</div>
        <ul class="menu-list">
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/viewsabeeltakhmeen') ?>">
              <span class="menu-icon" style="background:#eff6ff; color:#1d4ed8;"><i class="fa fa-money"></i></span>
              <span class="menu-label">Sabeel Due</span>
              <?php if (isset($sabeel_takhmeen_details["total_due"]) && (int)$sabeel_takhmeen_details["total_due"] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/miqaat_invoices') ?>">
              <span class="menu-icon" style="background:#fffbeb; color:#d97706;"><i class="fa fa-calendar"></i></span>
              <span class="menu-label">Miqaat Niyaz Invoices</span>
              <?php if (isset($miqaat_invoice_due_badge) && $miqaat_invoice_due_badge): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/laagat') ?>">
              <span class="menu-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa fa-calculator"></i></span>
              <span class="menu-label">Laagat Invoices</span>
              <?php if (isset($laagat_summary['total_due']) && (float)$laagat_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/rent') ?>">
              <span class="menu-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa fa-home"></i></span>
              <span class="menu-label">Rent Invoices</span>
              <?php if (isset($rent_summary['total_due']) && (float)$rent_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/rent_deposit') ?>">
              <span class="menu-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa fa-shield"></i></span>
              <span class="menu-label">Rent Deposits</span>
              <?php if (isset($rent_deposit_summary['total_due']) && (float)$rent_deposit_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/qardanhasana') ?>">
              <span class="menu-icon" style="background:#fffbeb; color:#b45309;"><i class="fa fa-handshake-o"></i></span>
              <span class="menu-label">Qardan Hasana</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/wajebaat') ?>">
              <span class="menu-icon" style="background:#eff6ff; color:#1d4ed8;"><i class="fa fa-credit-card"></i></span>
              <span class="menu-label">Wajebaat</span>
              <?php if (isset($wajebaat['due']) && (float)$wajebaat['due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/corpusfunds') ?>">
              <span class="menu-icon" style="background:#fef2f2; color:#b91c1c;"><i class="fa fa-university"></i></span>
              <span class="menu-label">Corpus Funds</span>
              <?php $cf_due_badge = (float)($corpus_summary['outstanding'] ?? 0); if ($cf_due_badge > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/ekramfunds') ?>">
              <span class="menu-icon" style="background:#fffbeb; color:#b45309;"><i class="fa fa-gift"></i></span>
              <span class="menu-label">Ekram Funds</span>
              <?php $ef_due_badge = (float)($ekram_summary['outstanding'] ?? 0); if ($ef_due_badge > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
        </ul>

        <div class="menu-section">General</div>
        <ul class="menu-list">
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/madresa') ?>">
              <span class="menu-icon" style="background:#fffbeb; color:#b45309;"><i class="fa fa-graduation-cap"></i></span>
              <span class="menu-label">Madresa</span>
              <?php if (isset($madresa_summary['total_due']) && (float)$madresa_summary['total_due'] > 0): ?><span class="count-badge">Due</span><?php endif; ?>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/rsvp_list') ?>">
              <span class="menu-icon" style="background:#eaf4ee; color:#1a6645;"><i class="fa fa-calendar"></i></span>
              <span class="menu-label">Miqaat &amp; RSVP</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/appointment') ?>">
              <span class="menu-icon" style="background:#fef2f2; color:#b91c1c;"><i class="fa fa-calendar-o"></i></span>
              <span class="menu-label">Appointments</span>
            </a>
          </li>
          <li>
            <a class="menu-item" href="<?php echo base_url('accounts/profile') ?>">
              <span class="menu-icon" style="background:#fef2f2; color:#b91c1c;"><i class="fa fa-user"></i></span>
              <span class="menu-label">My Profile</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ══ CONTENT ══ -->
    <main class="mumin-content mt-4">
      
      <!-- Mobile toolbar -->
      <div class="mob-bar">
        <button id="sidebarToggle" class="mob-btn">
          <span class="mic"><i class="fa fa-bars"></i></span> Menu
        </button>
        <span style="font-size:.82rem;font-weight:700;color:var(--text-2);">Member Dashboard</span>
      </div>

      <!-- ── Dashboard Header Banner Card ── -->
      <div class="anj-header mb-4">
        <div class="anj-header-inner">
          <div class="anj-title-group">
            <p class="anj-eyebrow">Welcome to Anjuman-e-Saifee <?= htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?></p>
            <h1 class="anj-title">
              <?= htmlspecialchars($member_name ?? 'Mumin', ENT_QUOTES, 'UTF-8'); ?>
              <br><span><?= htmlspecialchars($hijri_date, ENT_QUOTES, 'UTF-8'); ?></span>
            </h1>
          </div>
          <div class="anj-badge">
            <span class="anj-badge-val"><?= htmlspecialchars($user_name ?? $_SESSION['user_data']['ITS_ID'] ?? $_SESSION['user']['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="anj-badge-lbl">ITS ID</span>
          </div>
        </div>
      </div>

      <!-- ── Dashboard Cards Grid ── -->
      <div class="row mt-4">

        <!-- FMB Takhmeen -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-cutlery"></i> FMB Takhmeen</span>
              <?php if (isset($fmb_takhmeen_details["total_due"]) && (float)$fmb_takhmeen_details["total_due"] > 0): ?>
                <span class="badge-pill badge-danger">Pending</span>
              <?php else: ?>
                <span class="badge-pill badge-success">Clear</span>
              <?php endif; ?>
            </div>
            <div class="dash-card-body">
              <?php
              $f_total = (float)($fmb_takhmeen_details['current_year_total'] ?? 0);
              $f_paid  = (float)($fmb_takhmeen_details['current_year_paid'] ?? 0);
              $f_due   = (float)($fmb_takhmeen_details['current_year_due'] ?? max(0.0, $f_total - $f_paid));
              $currentCompositeYear = isset($fmb_takhmeen_details['current_year']) ? (string)$fmb_takhmeen_details['current_year'] : '';
              ?>
              <div class="stat-tile mb-3">
                <div class="tile-label">Total Due <?php echo $currentCompositeYear ? '— ' . htmlspecialchars($currentCompositeYear) : ''; ?></div>
                <div class="tile-value red amount-big">&#8377;<?php echo format_inr_no_decimals($fmb_takhmeen_details['total_due'] ?? 0); ?></div>
              </div>
              <div class="breakdown-row mb-3">
                <div class="stat-tile">
                  <div class="tile-label">Takhmeen</div>
                  <div class="tile-value blue">&#8377;<?php echo format_inr_no_decimals($f_total); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Paid</div>
                  <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($f_paid); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Pending</div>
                  <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($f_due); ?></div>
                </div>
              </div>
              <a href="<?php echo base_url('accounts/viewfmbtakhmeen'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
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

        <!-- Miqaat Niyaz Invoices -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-calendar"></i> Miqaat Niyaz Invoices</span>
              <?php if (isset($miqaat_invoice_total_due) && (float)$miqaat_invoice_total_due > 0): ?>
                <span class="badge-pill badge-danger">Pending</span>
              <?php else: ?>
                <span class="badge-pill badge-success">Clear</span>
              <?php endif; ?>
            </div>
            <div class="dash-card-body">
              <?php
              $miq_total = (float)(isset($miqaat_invoice_total_amount) ? $miqaat_invoice_total_amount : 0);
              $miq_due   = (float)(isset($miqaat_invoice_total_due) ? $miqaat_invoice_total_due : 0);
              $miq_paid  = max(0.0, $miq_total - $miq_due);
              ?>
              <div class="stat-tile mb-3">
                <div class="tile-label">Total Due</div>
                <div class="tile-value red amount-big">&#8377;<?php echo format_inr_no_decimals($miq_due); ?></div>
              </div>
              <div class="breakdown-row mb-3">
                <div class="stat-tile">
                  <div class="tile-label">Invoiced</div>
                  <div class="tile-value blue">&#8377;<?php echo format_inr_no_decimals($miq_total); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Paid</div>
                  <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($miq_paid); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Pending</div>
                  <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($miq_due); ?></div>
                </div>
              </div>
              <a href="<?php echo base_url('accounts/miqaat_invoices'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
            </div>
          </div>
        </div>

        <!-- Extra Contributions -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-gift"></i> Extra Contributions</span>
              <?php if (isset($fmb_extra_due) && (float)$fmb_extra_due > 0): ?>
                <span class="badge-pill badge-danger">Pending</span>
              <?php else: ?>
                <span class="badge-pill badge-success">Clear</span>
              <?php endif; ?>
            </div>
            <div class="dash-card-body">
              <?php
              $gc_total = (float)(isset($fmb_extra_amount) ? $fmb_extra_amount : 0);
              $gc_due   = (float)(isset($fmb_extra_due) ? $fmb_extra_due : 0);
              $gc_paid  = max(0.0, $gc_total - $gc_due);
              ?>
              <div class="stat-tile mb-3">
                <div class="tile-label">Total Due</div>
                <div class="tile-value red amount-big">&#8377;<?php echo format_inr_no_decimals($gc_due); ?></div>
              </div>
              <div class="breakdown-row mb-3">
                <div class="stat-tile">
                  <div class="tile-label">Invoiced</div>
                  <div class="tile-value blue">&#8377;<?php echo format_inr_no_decimals($gc_total); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Paid</div>
                  <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($gc_paid); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Pending</div>
                  <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($gc_due); ?></div>
                </div>
              </div>
              <a href="<?php echo base_url('accounts/fmb_contributions'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a>
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

        <!-- Laagat -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-calculator"></i> Laagat Invoices</span>
              <?php $laagat_due = (float)($laagat_summary['total_due'] ?? 0); if ($laagat_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
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
              <div class="text-center mt-3"><a href="<?php echo base_url('accounts/laagat'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
            </div>
          </div>
        </div>

        <!-- Rent -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-home"></i> Rent Invoices</span>
              <?php $rent_due = (float)($rent_summary['total_due'] ?? 0); if ($rent_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
            </div>
            <div class="dash-card-body">
              <div class="breakdown-row mb-3">
                <div class="stat-tile">
                  <div class="tile-label">Total</div>
                  <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($rent_summary['total_amount'] ?? 0); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Due</div>
                  <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($rent_summary['total_due'] ?? 0); ?></div>
                </div>
              </div>
              <div class="text-center mt-3"><a href="<?php echo base_url('accounts/rent'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
            </div>
          </div>
        </div>

        <!-- Rent Deposits -->
        <div class="col-12 col-md-6 p-2">
          <div class="dash-card h-100">
            <div class="dash-card-header">
              <span class="card-title"><i class="fa fa-shield"></i> Rent Deposits</span>
              <?php $deposit_due = (float)($rent_deposit_summary['total_due'] ?? 0); if ($deposit_due > 0): ?><span class="badge-pill badge-danger">Pending</span><?php else: ?><span class="badge-pill badge-success">Clear</span><?php endif; ?>
            </div>
            <div class="dash-card-body">
              <div class="breakdown-row mb-3">
                <div class="stat-tile">
                  <div class="tile-label">Total</div>
                  <div class="tile-value green">&#8377;<?php echo format_inr_no_decimals($rent_deposit_summary['total_amount'] ?? 0); ?></div>
                </div>
                <div class="stat-tile">
                  <div class="tile-label">Due</div>
                  <div class="tile-value red">&#8377;<?php echo format_inr_no_decimals($rent_deposit_summary['total_due'] ?? 0); ?></div>
                </div>
              </div>
              <div class="text-center mt-3"><a href="<?php echo base_url('accounts/rent_deposit'); ?>" class="btn-view"><i class="fa fa-arrow-right"></i> View Details</a></div>
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
    </main>

  </div>
</div>

<script>
$(document).ready(function () {
  // instant menu search
  $('#quickMenuSearch').on('input', function() {
    var q = $(this).val().toLowerCase().trim();
    if (q) {
      $('#quickMenuClear').addClass('visible');
    } else {
      $('#quickMenuClear').removeClass('visible');
    }
    $('#muminSidebar .menu-item').each(function() {
      var text = $(this).find('.menu-label').text().toLowerCase();
      if (text.indexOf(q) > -1) {
        $(this).closest('li').show();
      } else {
        $(this).closest('li').hide();
      }
    });
  });

  // clear search input
  $('#quickMenuClear').on('click', function() {
    $('#quickMenuSearch').val('').trigger('input');
  });

  // mobile sidebar toggles
  $('#sidebarToggle').on('click', function() {
    $('#muminSidebar').addClass('open');
    $('#sidebarOverlay').addClass('show');
  });
  $('#sidebarCloseBtn, #sidebarOverlay').on('click', function() {
    $('#muminSidebar').removeClass('open');
    $('#sidebarOverlay').removeClass('show');
  });
});
</script>