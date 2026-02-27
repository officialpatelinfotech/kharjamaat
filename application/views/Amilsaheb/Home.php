<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .dashboard-header {
    margin-bottom: 30px;
  }

  .dashboard-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 5px;
    background: linear-gradient(90deg, #111827, #4f46e5, #06b6d4);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
  }

  .dashboard-subtitle {
    color: #666;
    font-size: 1rem;
  }

  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .chart-container.grouped-block {
    padding-bottom: 0;
  }

  .chart-title {
    font-size: 1.35rem;
    font-weight: 600;
    margin-bottom: 16px;
    color: #333;
  }

  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
  }

  /* Finance sector cards (FMB/Sabeel) */
  .hijri-year-badge {
    font-size: 1.25rem;
    font-weight: 600;
    padding: 6px 10px;
    border: 1px solid #e9edf5;
  }

  .fmb-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 12px 14px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .fmb-card .fmb-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .fmb-card .fmb-name {
    font-weight: 700;
    color: #222;
  }

  .fmb-card .fmb-amounts {
    display: flex;
    gap: 12px;
    font-size: .9rem;
    color: #555;
  }

  .fmb-card .fmb-amounts .val {
    font-weight: 700;
    color: #111;
  }

  /* Softer chip styling for amounts */
  .amount-chip {
    background: #f8fafc;
    border: 1px solid #e9edf5;
    border-radius: 8px;
    padding: 4px 8px;
    transition: background-color .2s ease, border-color .2s ease, color .2s ease;
  }

  .amount-chip:hover {
    background: #f1f5f9;
    border-color: #dbe3ef;
  }

  /* Hover tooltip for truncated amounts (desktop/hover devices) */
  @media (hover: hover) {
    .has-tooltip {
      position: relative;
    }

    .has-tooltip::after {
      content: attr(data-full);
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: 110%;
      background: #111;
      color: #fff;
      padding: 6px 8px;
      border-radius: 6px;
      font-size: .8rem;
      white-space: nowrap;
      box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
      opacity: 0;
      pointer-events: none;
      transition: opacity .15s ease;
      z-index: 20;
    }

    .has-tooltip::before {
      content: '';
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      bottom: 100%;
      border: 6px solid transparent;
      border-top-color: #111;
      opacity: 0;
      transition: opacity .15s ease;
      z-index: 19;
    }

    .has-tooltip:hover::after,
    .has-tooltip:hover::before {
      opacity: 1;
    }
  }

  .progress-slim {
    height: 8px;
    border-radius: 6px;
    overflow: hidden;
    background: #f1f3f7;
  }

  .progress-slim .bar {
    height: 100%;
    background: linear-gradient(90deg, #36A2EB 0%, #4BC0C0 100%);
  }

  /* Clickable sector cards */
  .fmb-card.sector-click {
    cursor: pointer;
  }

  /* Lightweight modal for sector details */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.35);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
  }

  .modal-overlay.show {
    display: flex;
  }

  .modal-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 14px;
    width: 92vw;
    max-width: 560px;
    padding: 18px;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.15);
  }

  .modal-card .modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }

  .modal-card .modal-title {
    font-weight: 700;
    font-size: 1.15rem;
    margin: 0;
  }

  .modal-close {
    border: none;
    background: #f5f5f7;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .modal-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    margin-top: 10px;
  }

  .pill {
    background: #f6f8fb;
    border: 1px solid #e9edf5;
    border-radius: 10px;
    padding: 10px 12px;
    text-align: center;
  }

  .pill .label {
    display: block;
    font-size: .85rem;
    color: #6b7280;
  }

  .pill .value {
    display: block;
    font-weight: 700;
    color: #111;
    font-size: 1rem;
  }

  /* Overview mini-cards */
  .overview-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    height: 100%;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .overview-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #f5f7ff;
    color: #5a67d8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .overview-icon i {
    font-size: 1.1rem;
  }

  .overview-body {
    display: flex;
    flex-direction: column;
  }

  .overview-title {
    font-size: .85rem;
    color: #666;
    margin: 0;
  }

  .overview-value {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 2px 0 0;
    line-height: 1;
  }

  /* Calendar overview cards */
  .calendar-block .stat-card {
    background: #f6f8fb;
    border: 1px solid #e9edf5;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .calendar-block .stat-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
    line-height: 1.2;
  }

  .calendar-block .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 8px;
    color: #111827;
    line-height: 1.1;
  }

  .calendar-block a {
    text-decoration: none;
    color: inherit;
  }

  /* Takhmeen toggle blocks */
  .takhmeen-summary {
    cursor: pointer;
  }

  .takhmeen-hint {
    font-size: .85rem;
    color: #6b7280;
    margin: 6px 0 0;
  }

  .takhmeen-details .hide-details {
    font-size: .85rem;
  }

  .calendar-block .stat-card:hover {
    border-color: #dbe3ef;
  }

  /* Quick Actions */
  .quick-actions .qa-tile {
    background: #ffffff;
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 16px 14px;
    height: 100%;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .quick-actions .qa-tile:hover {
    transform: translateY(-2px);
    border-color: #ddd;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
  }

  /* Subtle hover-lift on common cards */
  .overview-card:hover,
  .fmb-card:hover,
  .mini-card:hover,
  .calendar-block .stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
    border-color: #ddd;
  }

  .quick-actions .qa-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #eef2ff 0%, #f5f7ff 100%);
    color: #5a67d8;
    flex-shrink: 0;
  }

  .quick-actions .qa-icon i {
    font-size: 1.4rem;
  }

  .quick-actions .qa-label {
    font-weight: 600;
    color: #333;
  }

  .quick-actions .qa-sub {
    font-size: .8rem;
    color: #777;
  }

  /* Sector-wise */
  .sector-block .chart-title {
    font-size: 1.25rem;
    margin-bottom: 12px;
  }

  /* Flatten the shadow of the Sector-wise container */
  .sector-block {
    box-shadow: none !important;
    margin-bottom: 6px !important;
    /* further reduce gap below sector list */
  }

  /* Flatten the shadow of the Member Types container */
  .member-types-block {
    box-shadow: none !important;
    margin-top: 0 !important;
    /* remove extra gap above section */
    padding-top: 8px;
    /* tighten top padding */
  }

  /* Tighten heading spacing inside Member Types */
  .member-types-block .section-title {
    margin-top: 0;
    margin-bottom: 10px;
    /* slightly tighter heading spacing */
  }

  /* Mini cards (used for Raza Summary) */
  .mini-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    text-align: center;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .mini-card .stats-value {
    font-size: 1.25rem;
    margin-bottom: 2px;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .mini-card .stats-label {
    font-size: .8rem;
    letter-spacing: .6px;
    color: #777;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  @media (max-width: 767.98px) {
    .chart-container {
      padding: 20px;
      margin-bottom: 16px;
    }

    .dashboard-title {
      font-size: 1.6rem;
    }

    .dashboard-subtitle {
      font-size: .95rem;
    }

    .fmb-card {
      padding: 10px 12px;
    }

    /* Prevent overflow in sector amount row */
    .fmb-card .fmb-head {
      flex-wrap: wrap;
      align-items: flex-start;
      gap: 6px;
    }

    .fmb-card .fmb-name {
      flex: 1 1 auto;
      min-width: 160px;
    }

    .fmb-card .fmb-amounts {
      gap: 8px 10px;
      font-size: .8rem;
      width: 100%;
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .fmb-card .fmb-amounts span {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .chart-container.grouped-block {
      padding: 16px;
    }

    .chart-container .row.g-2>[class*='col-'] {
      margin-bottom: 8px;
    }

    /* So big totals in small chips don't overflow */
    .overview-card {
      padding: 10px;
    }

    .overview-card .overview-value {
      font-size: 1rem;
      line-height: 1.2;
      overflow-wrap: anywhere;
      word-break: break-word;
    }

    .calendar-block .stat-card {
      padding: 14px;
      border-radius: 10px;
    }

    .calendar-block .stat-title {
      font-size: 1.1rem;
    }

    .calendar-block .stat-value {
      font-size: 1.35rem;
    }
  }

  /* Mobile toolbar for sidebar toggle */
  .mobile-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 10px 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  }

  .mobile-menu-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    outline: none;
    background: linear-gradient(135deg, #eef2ff 0%, #f5f7ff 100%);
    color: #5a67d8;
    padding: 8px 12px;
    border-radius: 9999px;
    font-weight: 600;
  }

  .mobile-menu-btn .menu-icon {
    width: 28px;
    height: 28px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    box-shadow: inset 0 0 0 1px #e7e9fb;
  }

  .mobile-title {
    font-weight: 600;
    color: #333;
    font-size: .95rem;
  }

  /* Left Sidebar Menu */
  .sidebar-menu {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 16px;
    position: sticky;
    top: 80px;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  }

  .sidebar-menu .menu-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
  }

  .sidebar-menu .menu-search {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f7f7fb;
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 12px;
    padding: 10px 12px;
    margin-bottom: 10px;
  }

  .sidebar-menu .menu-search i {
    color: #9aa0a6;
    font-size: 14px;
    flex: 0 0 auto;
  }

  .sidebar-menu .menu-search input {
    border: 0;
    outline: 0;
    background: transparent;
    width: 100%;
    font-size: 0.95rem;
    color: #111827;
  }

  .sidebar-menu .menu-search input::placeholder {
    color: #9aa0a6;
  }

  .sidebar-menu .menu-search .menu-search-clear {
    border: 0;
    background: #ffffff;
    color: #6b7280;
    width: 26px;
    height: 26px;
    border-radius: 8px;
    display: none;
    align-items: center;
    justify-content: center;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
  }

  .sidebar-menu .menu-search .menu-search-clear:hover {
    background: #f3f4f6;
  }

  .sidebar-menu .menu-section {
    font-size: .85rem;
    color: #777;
    margin: 16px 8px 6px;
    text-transform: uppercase;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 8px;
    border-radius: 10px;
  }

  .sidebar-menu .menu-section:hover {
    background: #f7f7fb;
  }

  .sidebar-menu .menu-section::after {
    content: '▾';
    font-size: .75rem;
    color: #9aa0a6;
    transform: rotate(0deg);
    transition: transform .15s ease;
  }

  .sidebar-menu .menu-section.is-collapsed::after {
    transform: rotate(-90deg);
  }

  .sidebar-menu .menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .sidebar-menu .menu-list li+li {
    margin-top: 6px;
  }

  .sidebar-menu .menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #333;
    text-decoration: none;
    transition: background .15s ease, transform .15s ease;
  }

  .sidebar-menu .menu-item:hover {
    background: #f7f7fb;
    transform: translateX(2px);
    text-decoration: none;
  }

  .sidebar-menu .menu-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #eef2ff 0%, #f5f7ff 100%);
    color: #5a67d8;
  }

  .sidebar-menu .menu-label {
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Mobile sidebar behavior */
  @media (max-width: 767.98px) {
    .col-sidebar {
      height: 0;
      margin: 0 !important;
    }

    .sidebar-menu {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 82%;
      max-width: 320px;
      border-radius: 0 14px 14px 0;
      transform: translateX(-100%);
      transition: transform .2s ease;
      z-index: 1050;
      overflow-y: auto;
    }

    .sidebar-menu.open {
      transform: translateX(0);
    }

    .sidebar-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.35);
      z-index: 1040;
      display: none;
    }

    .sidebar-overlay.show {
      display: block;
    }
  }

  /* Beautification enhancements */
  .dashboard-title {
    font-weight: 800;
    letter-spacing: -0.02em;
  }

  @media (min-width: 992px) {
    .dashboard-title {
      font-size: 2.1rem;
    }
  }

  .dashboard-subtitle {
    color: #6b7280;
    font-weight: 600;
  }

  .mini-card {
    border: 1px solid #eef2f7;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    transition: transform .12s ease, box-shadow .12s ease;
  }

  .mini-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  }

  .mini-card .stats-value {
    font-weight: 800;
    letter-spacing: -0.01em;
  }

  .mini-card .stats-label {
    font-weight: 600;
    color: #6b7280;
  }

  .sidebar-menu .menu-item .menu-label {
    font-size: .95rem;
  }

  .chart-container.grouped-block {
    margin-top: 8px;
  }

  .chart-container.compact {
    padding-top: 8px;
  }

  .section-title {
    margin-bottom: 12px;
  }

  #familiesSignedUpCard {
    border-left: none;
    background-image: linear-gradient(180deg, rgba(22, 163, 74, 0.06), rgba(255, 255, 255, 0));
  }

  #familiesSignedUpCard .stats-value {
    color: #16a34a;
  }

  #noThaaliCard {
    border-left: none;
    background-image: linear-gradient(180deg, rgba(220, 38, 38, 0.06), rgba(255, 255, 255, 0));
  }

  #noThaaliCard .stats-value {
    color: #dc2626;
  }

  /* Finance summary colors: Paid green, Due red; Total default */
  .takhmeen-summary .overview-value.text-success {
    color: #16a34a !important;
  }

  .takhmeen-summary .overview-value.text-danger {
    color: #dc2626 !important;
  }

  .takhmeen-summary .overview-value.text-primary {
    color: #111 !important;
  }

  /* Corpus funds colors: Received green, Pending red; Total default */
  .corpus-summary .stats-value.text-success {
    color: #16a34a !important;
  }

  .corpus-summary .stats-value.text-danger {
    color: #dc2626 !important;
  }

  .corpus-summary .stats-value:not(.text-success):not(.text-danger) {
    color: #111 !important;
  }

  /* Within sector cards: ensure Total amount chips are black */
  .fmb-amounts .val.text-primary {
    color: #111 !important;
  }
</style>

<div class="container-fluid margintopcontainer mt-5 pt-5">
  <!-- Header -->
  <div class="dashboard-header text-center">
    <h1 class="dashboard-title">Amilsaheb Dashboard</h1>
    <p class="dashboard-subtitle">
      <?php
      $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : date('Y');
      echo $hijri_year . 'H / ' . date('Y');
      ?>
    </p>
  </div>

  <div class="row">
    <!-- Left Sidebar Menu -->
    <div class="col-md-4 col-lg-3 mb-4 col-sidebar">
      <div class="sidebar-menu">
        <div class="menu-title">Quick Menu</div>
        <div class="menu-search" role="search">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input id="quickMenuSearch" type="text" placeholder="Search menu..." aria-label="Search quick menu" autocomplete="off" />
          <button type="button" id="quickMenuClear" class="menu-search-clear" aria-label="Clear search">&times;</button>
        </div>
        <div class="menu-section">Raza</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=1') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Miqaat Raza Request</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=2') ?>"><span class="menu-icon"><i class="fa fa-handshake-o"></i></span><span class="menu-label">Kaaraj Raza Request</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/UmoorRazaRequest') ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">12 Umoor Raza Request</span></a></li>
        </ul>

        <div class="menu-section">Finance</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('common/sabeeltakhmeen?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-hand-holding-heart"></i></span><span class="menu-label">Sabeel Takhmeen</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/fmbtakhmeen?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-hand-holding-heart"></i></span><span class="menu-label">FMB Thaali Takhmeen</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/fmb_general_contributions?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-inr"></i></span><span class="menu-label">FMB General Contributions</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/qardanhasana'); ?>"><span class="menu-icon"><i class="fa-solid fa-handshake"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
          <li><a class="menu-item" href="<?= base_url('amilsaheb/corpusfunds_details'); ?>"><span class="menu-icon"><i class="fa-solid fa-donate"></i></span><span class="menu-label">Corpus Funds</span></a></li>
          <li><a class="menu-item" href="<?= base_url('anjuman/laagat_rent_payments'); ?>"><span class="menu-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span><span class="menu-label">Laagat/Rent</span></a></li>
          <li><a class="menu-item" href="<?= base_url('amilsaheb/ekramfunds_details'); ?>"><span class="menu-icon"><i class="fa-solid fa-donate"></i></span><span class="menu-label">Ekram Funds</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/wajebaat_details'); ?>"><span class="menu-icon"><i class="fa-solid fa-coins"></i></span><span class="menu-label">Wajebaat</span></a></li>
          <li><a class="menu-item" href="<?= base_url('amilsaheb/expense'); ?>"><span class="menu-icon"><i class="fa-solid fa-receipt"></i></span><span class="menu-label">Expense Module</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('madresa'); ?>"><span class="menu-icon"><i class="fa fa-graduation-cap"></i></span><span class="menu-label">Madresa</span></a></li>
        </ul>

        <div class="menu-section">Reports</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('common/miqaatattendance?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Miqaat Attendance Report</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/thaali_signups_breakdown?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-bar-chart"></i></span><span class="menu-label">FMB Thaali Signups</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/rsvp_list?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">RSVP Report</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/mumineendirectory'); ?>"><span class="menu-icon"><i class="fa fa-address-book"></i></span><span class="menu-label">Mumineen Directory</span></a></li>
        </ul>

        <div class="menu-section">Appointments</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/manage_appointment') ?>"><span class="menu-icon"><i class="fa fa-list"></i></span><span class="menu-label">Appointments</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('amilsaheb/slots_calendar') ?>"><span class="menu-icon"><i class="fa fa-clock-o"></i></span><span class="menu-label">Manage Time Slots</span></a></li>
        </ul>

        <div class="menu-section">Activity</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('Amilsaheb/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat <?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446H';
                                                                                                                                                                                              echo $hijri_year . 'H'; ?></span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('Amilsaheb/ashara_attendance') ?>"><span class="menu-icon"><i class="fa fa-user"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/fmbcalendar?from=amilsaheb'); ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">FMB Calendar</span></a></li>
        </ul>
      </div>
    </div>

    <!-- Right Content -->
    <div class="col-md-8 col-lg-9">
      <!-- Mobile toggle button -->
      <div class="mb-3 d-md-none">
        <div class="mobile-toolbar">
          <button id="sidebarToggle" class="mobile-menu-btn" aria-label="Open menu">
            <span class="menu-icon"><i class="fa fa-bars"></i></span>
            <span class="menu-text">Menu</span>
          </button>
          <span class="mobile-title">Quick Menu</span>
        </div>
      </div>

      <!-- Corpus Funds Summary (similar to Anjuman) -->
      <?php
      if (!function_exists('format_inr')) {
        function format_inr($num)
        {
          $num = (int)round((float)$num);
          $n = strval($num);
          $len = strlen($n);
          if ($len <= 3) return $n;
          $last3 = substr($n, -3);
          $rest = substr($n, 0, $len - 3);
          $parts = [];
          while (strlen($rest) > 2) {
            $parts[] = substr($rest, -2);
            $rest = substr($rest, 0, strlen($rest) - 2);
          }
          if ($rest !== '') $parts[] = $rest;
          $parts = array_reverse($parts);
          return implode(',', $parts) . ',' . $last3;
        }
      }
      $fundsCount = 0;
      $sumAmount = 0;
      $sumAssigned = 0;
      $sumOutstanding = 0;
      if (isset($corpus_funds) && is_array($corpus_funds)) {
        $fundsCount = count($corpus_funds);
        foreach ($corpus_funds as $f) {
          $amt = (float)($f['amount'] ?? 0);
          $ass = isset($f['assigned_total']) ? (float)$f['assigned_total'] : 0.0;
          if ($ass === 0.0 && isset($f['assignments']) && is_array($f['assignments'])) {
            foreach ($f['assignments'] as $a) {
              $ass += (float)($a['amount_assigned'] ?? ($a['amount'] ?? 0));
            }
          }
          $out = isset($f['outstanding']) ? (float)$f['outstanding'] : max(0, $amt - $ass);
          $sumAmount += $amt;
          $sumAssigned += $ass;
          $sumOutstanding += $out;
        }
      }
      $sumAmount = (int)round($sumAmount);
      $sumAssigned = (int)round($sumAssigned);
      $sumOutstanding = (int)round($sumOutstanding);
      $sumPaid = max(0, (int)$sumAssigned - (int)$sumOutstanding);
      ?>


      <div class="modal fade" id="corpusFundsModal" tabindex="-1" role="dialog" aria-labelledby="corpusFundsLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header py-2">
              <h6 class="modal-title" id="corpusFundsLabel">Corpus Funds</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="d-flex justify-content-between align-items-center py-1">
                <span>Total Per Family</span>
                <strong id="corpusAmtTotal">₹0</strong>
              </div>
              <div class="d-flex justify-content-between align-items-center py-1">
                <span>Total Assigned</span>
                <strong id="corpusAmtAssigned" class="text-success">₹0</strong>
              </div>
              <div class="d-flex justify-content-between align-items-center py-1">
                <span>Outstanding</span>
                <strong id="corpusAmtOutstanding" class="text-danger">₹0</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Grouped: Overview + Sector-wise -->
      <div class="chart-container grouped-block">
        <h4 class="section-title text-center mt-4 mt-md-0">Jamaat Overview</h4>
        <div class="row">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=all'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-users"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Total Members</span>
                  <span class="overview-value"><?= isset($member_type_counts['resident']) ? (int)$member_type_counts['resident'] : (isset($stats['HOF']) && isset($stats['FM']) ? ((int)$stats['HOF'] + (int)$stats['FM']) : count($users)) ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=hof_fm_type&value=HOF'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user"></i></div>
                <div class="overview-body">
                  <span class="overview-title">HOF</span>
                  <span class="overview-value"><?= $stats['HOF'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=hof_fm_type&value=FM'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user-plus"></i></div>
                <div class="overview-body">
                  <span class="overview-title">FM</span>
                  <span class="overview-value"><?= $stats['FM'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=gender&value=male'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-male"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Males</span>
                  <span class="overview-value"><?= $stats['Mardo'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=gender&value=female'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-female"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Females</span>
                  <span class="overview-value"><?= $stats['Bairo'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=0&max=4'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-child"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Age 0-4</span>
                  <span class="overview-value"><?= $stats['Age_0_4'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=5&max=15'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-child"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Age 5-15</span>
                  <span class="overview-value"><?= $stats['Age_5_15'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=65'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user-circle"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Seniors (65+)</span>
                  <span class="overview-value"><?= $stats['Buzurgo'] ?></span>
                </div>
              </div>
            </a>
          </div>
        </div>
        <!-- Sector-wise Members -->
        <div class="row">
          <div class="col-12">
            <div class="chart-container sector-block" style="margin-top: 20px; padding-top: 10px; padding: 0px;">
              <h4 class="section-title text-center">Sector-wise Members</h4>
              <?php
              $sectorRows = isset($stats['Sectors']) ? $stats['Sectors'] : [];
              if (!empty($sectorRows)) {
                $sectorRows = array_values(array_filter($sectorRows, function ($row) {
                  $name = isset($row['Sector']) ? trim($row['Sector']) : '';
                  return $name !== '' && strtolower($name) !== 'unassigned';
                }));
                usort($sectorRows, function ($a, $b) {
                  return intval($b['total'] ?? 0) <=> intval($a['total'] ?? 0);
                });
              }
              ?>
              <?php if (!empty($sectorRows)) { ?>
                <div class="row">
                  <?php foreach ($sectorRows as $row): ?>
                    <div class="col-12 col-md-3 mb-3">
                      <a href="<?= base_url('amilsaheb/mumineendirectory?filter=sector&value=' . rawurlencode($row['Sector'] ?: '')); ?>" style="text-decoration:none;color:inherit;display:block;">
                        <div class="overview-card">
                          <div class="overview-icon"><i class="fa fa-map-marker"></i></div>
                          <div class="overview-body">
                            <span class="overview-title"><?= htmlspecialchars($row['Sector'] ?: 'Unassigned'); ?></span>
                            <?php
                            $hof = intval($row['hof_count'] ?? $row['HOF'] ?? $row['hof'] ?? 0);
                            $fm = intval($row['fm_count'] ?? $row['FM'] ?? $row['fm'] ?? null);
                            $total = intval($row['total'] ?? 0);
                            if ($fm === null) {
                              $fm = max(0, $total - $hof);
                            }
                            ?>
                            <div style="display:flex;align-items:baseline;gap:16px;flex-wrap:wrap;">
                              <span class="overview-value" style="font-size:1.15rem;">HOF: <?= $hof; ?></span>
                              <span class="overview-value" style="font-size:1.15rem;font-weight:600;">FM: <?= $fm; ?></span>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php } else { ?>
                <div class="text-center text-muted">No assigned sectors found.</div>
              <?php } ?>
            </div>
          </div>
        </div>

        <!-- Member Types (moved below Sector-wise Members) -->
        <div class="chart-container member-types-block" style="padding-top: 0px; padding: 0px;">
          <h4 class="section-title text-center">Members By Types</h4>
          <?php $mt = isset($member_type_counts) ? $member_type_counts : ['resident' => 0, 'external' => 0, 'moved_out' => 0, 'non_sabeel' => 0, 'temporary' => 0, 'total' => 0]; ?>
          <div class="row">
            <div class="col-12 col-md-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=member_type&value=' . rawurlencode('Resident Mumineen')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fa fa-home"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Resident Mumineen</span>
                    <span class="overview-value"><?= (int)$mt['resident']; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-md-4 col-lg-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=member_type&value=' . rawurlencode('External Sabeel Payers')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:#ecfeff; color:#0891b2;"><i class="fa fa-external-link"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">External Sabeel Payers</span>
                    <span class="overview-value"><?= (int)$mt['external']; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-md-4 col-lg-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=member_type&value=' . rawurlencode('Moved-Out Mumineen')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa fa-sign-out"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Moved-Out Mumineen</span>
                    <span class="overview-value"><?= (int)$mt['moved_out']; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-md-4 col-lg-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=member_type&value=' . rawurlencode('Non-Sabeel Residents')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:#fff1f2; color:#dc2626;"><i class="fa fa-ban"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Non-Sabeel Residents</span>
                    <span class="overview-value"><?= (int)$mt['non_sabeel']; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-md-4 col-lg-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter=member_type&value=' . rawurlencode('Temporary Mumineen/Visitors')); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa fa-clock-o"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Temporary/Visitors</span>
                    <span class="overview-value"><?= (int)$mt['temporary']; ?></span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Marital Status Distribution -->
        <div class="chart-container member-types-block" style="padding-top: 0px; padding: 0px;">
          <h4 class="section-title text-center">Marital Status</h4>
          <?php $ms = isset($marital_status_counts) ? $marital_status_counts : []; ?>
          <div class="row">
            <?php if (empty($ms)) { ?>
              <div class="col-12 text-center text-muted">No data available</div>
              <?php } else {
              foreach ($ms as $label => $count) {
                $safeLabel = htmlspecialchars($label);
                $lbl_l = strtolower(trim($label));
                $iconClass = 'fa fa-info-circle';
                $iconBg = '#f5f5f7';
                $iconColor = '#6b7280';
                if (strpos($lbl_l, 'single') !== false) {
                  $iconClass = 'fa fa-user';
                  $iconBg = '#eef2ff';
                  $iconColor = '#4f46e5';
                } elseif (strpos($lbl_l, 'married') !== false) {
                  $iconClass = 'fa fa-user';
                  $iconBg = '#fff0f6';
                  $iconColor = '#db2777';
                } elseif (strpos($lbl_l, 'engag') !== false) {
                  $iconClass = 'fa fa-star';
                  $iconBg = '#fff7ed';
                  $iconColor = '#ea580c';
                } elseif (strpos($lbl_l, 'divorc') !== false) {
                  $iconClass = 'fa fa-user';
                  $iconBg = '#fff1f2';
                  $iconColor = '#dc2626';
                } elseif (strpos($lbl_l, 'widow') !== false) {
                  $iconClass = 'fa fa-user-secret';
                  $iconBg = '#ecfeff';
                  $iconColor = '#0891b2';
                }
              ?>
                <div class="col-6 col-md-3 mb-3">
                  <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&marital_status=' . rawurlencode($label)); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="overview-card">
                      <div class="overview-icon" style="background:<?php echo $iconBg; ?>; color:<?php echo $iconColor; ?>;"><i class="<?php echo $iconClass; ?>"></i></div>
                      <div class="overview-body">
                        <span class="overview-title"><?php echo $safeLabel; ?></span>
                        <span class="overview-value"><?php echo (int)$count; ?></span>
                      </div>
                    </div>
                  </a>
                </div>
            <?php }
            } ?>
          </div>
        </div>

        <?php
        // Always show this section; if no signups, display a friendly empty state
        $sw = isset($dashboard_data['this_week_sector_signup_avg']) ? $dashboard_data['this_week_sector_signup_avg'] : null;
        $sw_start = isset($sw['start']) ? $sw['start'] : date('Y-m-d', strtotime('monday this week'));
        $sw_end = isset($sw['end']) ? $sw['end'] : date('Y-m-d', strtotime('sunday this week'));
        $sw_sectors = (isset($sw['sectors']) && is_array($sw['sectors'])) ? $sw['sectors'] : [];

        // Build complete sector list from overall stats to include zero-count sectors
        $allSectorNames = [];
        if (isset($stats) && isset($stats['Sectors']) && is_array($stats['Sectors'])) {
          foreach ($stats['Sectors'] as $sr) {
            $nm = isset($sr['Sector']) ? trim($sr['Sector']) : '';
            if ($nm !== '' && strtolower($nm) !== 'unassigned') {
              $allSectorNames[] = $nm;
            }
          }
          $allSectorNames = array_values(array_unique($allSectorNames));
        }

        // Map provided weekly sector rows by name (case-insensitive key)
        $byWeekly = [];
        foreach ($sw_sectors as $r) {
          $nm = isset($r['sector']) ? trim($r['sector']) : '';
          if ($nm !== '') {
            $byWeekly[strtolower($nm)] = $r;
          }
        }

        // Compose display rows: ensure every known sector appears; fill zeros if missing
        $displayWeekly = [];
        if (!empty($allSectorNames)) {
          foreach ($allSectorNames as $secName) {
            $key = strtolower($secName);
            if (isset($byWeekly[$key])) {
              // Use the computed data
              $row = $byWeekly[$key];
              // Normalize keys
              $displayWeekly[] = [
                'sector' => $secName,
                'avg' => (float)($row['avg'] ?? 0),
                'total' => (int)($row['total'] ?? 0),
              ];
            } else {
              // No signups recorded: show zeros
              $displayWeekly[] = [
                'sector' => $secName,
                'avg' => 0,
                'total' => 0,
              ];
            }
          }
        } else {
          // Fallback: if we don't have the master sector list, show whatever the API returned
          $displayWeekly = [];
          foreach ($sw_sectors as $row) {
            $displayWeekly[] = [
              'sector' => isset($row['sector']) ? $row['sector'] : '—',
              'avg' => (float)($row['avg'] ?? 0),
              'total' => (int)($row['total'] ?? 0),
            ];
          }
        }
        ?>
        <?php
        // Determine current hijri parts (controller may provide $selected_hijri_parts)
        if (isset($selected_hijri_parts) && is_array($selected_hijri_parts) && !empty($selected_hijri_parts)) {
          $hijri_today = $selected_hijri_parts;
        } else {
          $hijri_today = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
        }
        $current_hijri_month = isset($hijri_today['hijri_month']) ? (int)$hijri_today['hijri_month'] : 1;
        $current_hijri_year = isset($hijri_today['hijri_year']) ? (int)$hijri_today['hijri_year'] : (int)date('Y');
        $month_names_raw = $this->HijriCalendar->get_hijri_month();
        $month_names = [];
        foreach ($month_names_raw as $mn) {
          $id = isset($mn['id']) ? (int)$mn['id'] : null;
          $name = isset($mn['hijri_month']) ? $mn['hijri_month'] : '';
          if ($id) $month_names[$id] = $name;
        }
        ?>
        <div class="chart-container" id="thaali-month-block" data-hijri-month="<?= $current_hijri_month ?>" data-hijri-year="<?= $current_hijri_year ?>">
          <div class="d-flex justify-content-center align-items-center">
            <div>
              <h4 class="section-title">Thaali Signup for Current Month</h4>
            </div>
          </div>
          <div class="d-flex justify-content-end align-items-center">
            <a href="<?= base_url('common/thaali_signups_breakdown?from=amilsaheb'); ?>" class="btn btn-sm btn-primary my-2">View details</a>
          </div>

          <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
            <a href="#" class="hijri-nav-btn prev" aria-label="Previous month" style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;">
              <span style="font-size:18px;line-height:1">&#x2039;</span>
            </a>
            <span id="hijri-current-title" class="mx-3" style="font-weight:600;color:#0ea5a5;font-size:20px;"><?= htmlspecialchars($month_names[$current_hijri_month] ?? 'Hijri Month') . ' ' . htmlspecialchars($current_hijri_year); ?></span>
            <a href="#" class="hijri-nav-btn next" aria-label="Next month" style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;">
              <span style="font-size:18px;line-height:1">&#x203A;</span>
            </a>
          </div>

          <?php
          // Reuse existing variables to populate initial counts for the current month
          $noThaali = isset($dashboard_data['no_thaali_families_month']) ? $dashboard_data['no_thaali_families_month'] : (isset($dashboard_data['no_thaali_families']) ? $dashboard_data['no_thaali_families'] : []);
          $familiesSignedUp = isset($dashboard_data['this_month_families_signed_up']) ? (int)$dashboard_data['this_month_families_signed_up'] : max(0, (isset($stats['HOF']) ? (int)$stats['HOF'] : 0) - (int)count($noThaali));
          ?>

          <div class="row text-center mb-2">
            <div class="col-12 col-md-6 mb-2">
              <div class="mini-card" id="familiesSignedUpMonthCard">
                <div class="stats-value"><?= (int)$familiesSignedUp; ?></div>
                <div class="stats-label">SIGN UP THIS MONTH</div>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-2">
              <div class="mini-card" id="noThaaliMonthCard">
                <div class="stats-value"><?= (int)count($noThaali); ?></div>
                <div class="stats-label">NO SIGN UP THIS MONTH</div>
              </div>
            </div>
          </div>
        </div>

        <?php
        // Inject RSVP container (if controller provided miqaat RSVP data)
        $miq_rsvp = isset($dashboard_data['miqaat_rsvp']) ? $dashboard_data['miqaat_rsvp'] : null;
        $upcoming = isset($dashboard_data['upcoming_miqaats']) ? $dashboard_data['upcoming_miqaats'] : [];
        $initial_index = 0;
        $initial_id = isset($miq_rsvp['next_miqaat']['id']) ? $miq_rsvp['next_miqaat']['id'] : (isset($upcoming[0]['id']) ? $upcoming[0]['id'] : '');
        foreach ($upcoming as $k => $m) {
          if (isset($m['id']) && $m['id'] == $initial_id) {
            $initial_index = $k;
            break;
          }
        }
        ?>

        <div class="chart-container" id="miqaat-rsvp-block" data-initial-index="<?= $initial_index; ?>">
          <div class="d-flex align-items-center justify-content-between" style="gap:12px;">
            <h4 class="section-title text-center m-0" style="flex:1;">RSVP for Next Miqaat</h4>
          </div>
          <div style="min-width:140px;text-align:right;">
            <a href="#" class="btn btn-sm btn-primary text-white my-2" id="miqaat-view-details">View details</a>
          </div>

          <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
            <a href="#" class="miqaat-nav-btn prev" aria-label="Previous miqaat" style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;margin-right:12px;">
              <span style="font-size:18px;line-height:1">&#x2039;</span>
            </a>
            <span id="miqaat-current-title" class="mx-3" style="font-weight:600;color:#0ea5a5;font-size:18px;"></span>
            <a href="#" class="miqaat-nav-btn next" aria-label="Next miqaat" style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;margin-left:12px;">
              <span style="font-size:18px;line-height:1">&#x203A;</span>
            </a>
          </div>

          <div id="miqaat-mobile-wrapper">
            <div id="miqaat-mobile-left">
              <div class="row text-center mb-2">
                <div class="col-12 col-md-4 mb-2">
                  <a href="#" id="miqaatWillAttendCard" class="open-miqaat-modal" data-type="rsvp" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card">
                      <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                      <div class="stats-value" id="willAttendCount"><?= isset($miq_rsvp['combined_summary']['total']) ? (int)$miq_rsvp['combined_summary']['total'] : 0; ?></div>
                      <div class="small text-muted" id="willAttendGuest"><?= isset($miq_rsvp['guest_summary']['total']) && (int)$miq_rsvp['guest_summary']['total'] > 0 ? ('+' . (int)$miq_rsvp['guest_summary']['total'] . ' guests') : ''; ?></div>
                      <div class="stats-label">Will attend</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2">
                  <a href="#" id="miqaatWillNotAttendCard" class="open-miqaat-modal" data-type="no" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card">
                      <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                      <div class="stats-value" id="willNotAttendCount"><?= isset($miq_rsvp['will_not_attend']) ? (int)$miq_rsvp['will_not_attend'] : 0; ?></div>
                      <div class="stats-label">Will not attend</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2">
                  <a href="#" id="miqaatNotSubmittedCard" class="open-miqaat-modal" data-type="not_submitted" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card">
                      <div class="small text-muted" style="visibility:hidden;">&nbsp;</div>
                      <div class="stats-value" id="rsvpNotSubmittedCount"><?= isset($miq_rsvp['rsvp_not_submitted']) ? (int)$miq_rsvp['rsvp_not_submitted'] : 0; ?></div>
                      <div class="stats-label">RSVP not submitted</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div id="miqaat-mobile-right">
              <div class="row text-center mb-3" id="miqaatGuestBreakdown">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestGentsCard" class="open-miqaat-modal" data-type="gents" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Gents</div>
                      <div class="stats-value" id="guestGentsCount"><?= isset($miq_rsvp['combined_summary']['gents']) ? (int)$miq_rsvp['combined_summary']['gents'] : 0; ?></div>
                      <div class="small text-muted" id="guestGentsBreakdown">Members: <?= isset($miq_rsvp['member_summary']['gents']) ? (int)$miq_rsvp['member_summary']['gents'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['gents']) ? (int)$miq_rsvp['guest_summary']['gents'] : 0; ?></div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestLadiesCard" class="open-miqaat-modal" data-type="ladies" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Ladies</div>
                      <div class="stats-value" id="guestLadiesCount"><?= isset($miq_rsvp['combined_summary']['ladies']) ? (int)$miq_rsvp['combined_summary']['ladies'] : 0; ?></div>
                      <div class="small text-muted" id="guestLadiesBreakdown">Members: <?= isset($miq_rsvp['member_summary']['ladies']) ? (int)$miq_rsvp['member_summary']['ladies'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['ladies']) ? (int)$miq_rsvp['guest_summary']['ladies'] : 0; ?></div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestChildrenCard" class="open-miqaat-modal" data-type="children" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Children</div>
                      <div class="stats-value" id="guestChildrenCount"><?= isset($miq_rsvp['combined_summary']['children']) ? (int)$miq_rsvp['combined_summary']['children'] : 0; ?></div>
                      <div class="small text-muted" id="guestChildrenBreakdown">Members: <?= isset($miq_rsvp['member_summary']['children']) ? (int)$miq_rsvp['member_summary']['children'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['children']) ? (int)$miq_rsvp['guest_summary']['children'] : 0; ?></div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <style>
            @media (max-width:767.98px) {
              #miqaat-mobile-wrapper {
                display: flex;
                gap: 8px;
                align-items: flex-start;
              }

              #miqaat-mobile-left,
              #miqaat-mobile-right {
                width: 50%;
              }

              #miqaat-mobile-left .row,
              #miqaat-mobile-right .row {
                margin-bottom: 0;
                display: flex;
                flex-direction: column;
                gap: 8px;
              }

              #miqaat-mobile-left .row>[class*="col-"],
              #miqaat-mobile-right .row>[class*="col-"] {
                display: flex;
                align-items: stretch;
              }

              #miqaat-mobile-left .row>[class*="col-"]>a,
              #miqaat-mobile-right .row>[class*="col-"]>a {
                display: flex;
                flex: 1;
                align-items: stretch;
                width: 100%;
              }

              #miqaat-mobile-wrapper .mini-card {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                flex: 1 1 auto;
                height: 150px;
                padding: 18px 16px;
                box-sizing: border-box;
                overflow: visible;
              }

              #miqaat-mobile-wrapper .mini-card .stats-value {
                margin-bottom: 8px;
                font-size: 1.6rem;
                font-weight: 700;
                line-height: 1.1;
                display: block;
                white-space: nowrap;
                overflow: visible !important;
                text-overflow: clip;
                max-width: none;
                padding: 0 6px;
              }

              #miqaat-mobile-wrapper .mini-card .stats-label {
                white-space: normal;
                overflow: visible;
                text-overflow: clip;
                overflow-wrap: break-word;
                word-break: break-word;
                font-size: 0.85rem;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.8px;
                margin-top: 8px;
              }

              #miqaat-mobile-wrapper .mini-card .small.text-muted {
                display: block;
                color: #6b7280;
                font-size: 0.85rem;
                margin-top: 6px;
              }
            }
          </style>

          <div id="miqaatMessage" class="miqaat-message" style="display:none; margin-top:10px; text-align:center; color:#333; font-size:0.95rem;">&nbsp;</div>
          <div id="miqaatLoading" class="miqaat-loading-overlay" style="display:none;">
            <div class="miqaat-spinner" aria-hidden="true"></div>
          </div>


        </div>

        <!-- HOF List Modal -->
        <div class="modal fade" id="hofListModal" tabindex="-1" role="dialog" aria-labelledby="hofListLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="hofListLabel">HOFs Signed Up This Month</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <style>
                  /* Make table body scrollable and keep header sticky */
                  #hofListContainer .table-responsive {
                    max-height: 60vh;
                    overflow: auto;
                  }

                  #hofListTable thead th {
                    position: sticky;
                    top: 0;
                    background: #fff;
                    z-index: 2;
                  }

                  #hofListInnerHeader {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;
                  }
                </style>
                <div id="hofListSpinner" style="display:none;text-align:center;padding:24px;">Loading…</div>
                <div id="hofListInnerHeader" style="display:none;">
                  <!-- <h5 id="hofListInnerTitle" class="mb-0">HOFs Signed Up This Month</h5> -->
                  <small id="hofListCount" class="mr-auto text-muted">Count: 0</small>
                </div>
                <div id="miqaatPopupMeta" style="display:none;margin-bottom:8px;"></div>
                <div id="hofListContainer" style="display:none;">
                  <div class="table-responsive">
                    <table class="table table-sm table-striped" id="hofListTable">
                      <thead>
                        <tr>
                          <th>HOF ID</th>
                          <th>Name</th>
                          <th>Sector</th>
                          <th>Sub Sector</th>
                          <th id="hofMobileHeader" class="mobile-col">Mobile</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <style>
          /* Hide Mobile column when HOF modal is in miqaat mode */
          #hofListModal[data-mode="miqaat"] .mobile-col {
            display: none;
          }
        </style>
        <script>
          (function() {
            var baseUrl = '<?= rtrim(base_url('anjuman'), '/'); ?>';
            var monthNames = <?= json_encode($month_names); ?>;

            function updateCardsFor(month, year) {
              var url = baseUrl + '?format=json&hijri_year=' + encodeURIComponent(year) + '&hijri_month=' + encodeURIComponent(month);
              fetch(url, {
                  credentials: 'same-origin'
                })
                .then(function(res) {
                  return res.json();
                })
                .then(function(payload) {
                  if (!payload || !payload.success) return;
                  var m = payload.monthly_stats || {};
                  var signed = parseInt(m.families_signed_up || 0, 10);
                  var noList = m.no_thaali_list || [];
                  var noCount = Array.isArray(noList) ? noList.length : 0;
                  var signedEl = document.querySelector('#familiesSignedUpMonthCard .stats-value');
                  var noEl = document.querySelector('#noThaaliMonthCard .stats-value');
                  if (signedEl) signedEl.textContent = signed;
                  if (noEl) noEl.textContent = noCount;
                  var title = document.getElementById('hijri-current-title');
                  if (title) title.textContent = (monthNames[month] || 'Hijri Month') + ' ' + year;
                  // update container attributes
                  var container = document.getElementById('thaali-month-block');
                  if (container) {
                    container.setAttribute('data-hijri-month', month);
                    container.setAttribute('data-hijri-year', year);
                  }
                }).catch(function(err) {
                  console.error('Failed to fetch monthly stats', err);
                });
            }

            function deltaMonth(currentMonth, currentYear, delta) {
              var m = parseInt(currentMonth, 10);
              var y = parseInt(currentYear, 10);
              m += delta;
              if (m < 1) {
                m = 12;
                y -= 1;
              }
              if (m > 12) {
                m = 1;
                y += 1;
              }
              return {
                month: m,
                year: y
              };
            }

            document.addEventListener('click', function(e) {
              var t = e.target.closest && e.target.closest('.hijri-nav-btn');
              if (!t) return;
              e.preventDefault();
              var container = document.getElementById('thaali-month-block');
              if (!container) return;
              var curMonth = parseInt(container.getAttribute('data-hijri-month') || '1', 10);
              var curYear = parseInt(container.getAttribute('data-hijri-year') || (new Date()).getFullYear(), 10);
              var delta = t.classList.contains('prev') ? -1 : 1;
              var next = deltaMonth(curMonth, curYear, delta);
              updateCardsFor(next.month, next.year);
            });
            // Open modal for signed / not-signed lists
            function _normalizePhone(raw) {
              var DEFAULT_CC = '91'; // default country code (no +)
              if (!raw) return '';
              var s = String(raw).trim();
              // strip common separators
              s = s.replace(/[\s\-\.\(\)]/g, '');
              // if starts with +, keep digits
              if (s.charAt(0) === '+') {
                var d = s.slice(1).replace(/\D/g, '');
                return '+' + d;
              }
              // if starts with 00 international prefix
              if (s.indexOf('00') === 0) {
                var d2 = s.slice(2).replace(/\D/g, '');
                return '+' + d2;
              }
              // remove non-digits
              var digits = s.replace(/\D/g, '');
              if (!digits) return '';
              // common local formats: leading 0 removed, then prepend default CC
              if (digits.length === 10) {
                return '+' + DEFAULT_CC + digits;
              }
              if (digits.length === 11 && digits.charAt(0) === '0') {
                return '+' + DEFAULT_CC + digits.slice(1);
              }
              // If already includes country code without +, prefix +
              if (digits.length >= 11 && digits.length <= 15) {
                return '+' + digits;
              }
              // fallback: return digits
              return digits;
            }

            function renderHofList(rows) {
              var tbody = document.querySelector('#hofListTable tbody');
              tbody.innerHTML = '';
              // Determine whether to show mobile column based on modal mode
              try {
                var modalEl = document.getElementById('hofListModal');
                var mode = modalEl ? (modalEl.getAttribute('data-mode') || '') : '';
                var showMobile = (mode !== 'miqaat');
                var mobileHeader = document.getElementById('hofMobileHeader');
                if (mobileHeader) mobileHeader.style.display = showMobile ? '' : 'none';
              } catch (e) {
                console.warn('Failed to toggle mobile header', e);
              }
              if (!rows || !rows.length) {
                var tr = document.createElement('tr');
                var td = document.createElement('td');
                var colSpanVal = showMobile ? 5 : 4;
                td.colSpan = colSpanVal;
                td.className = 'text-center text-muted';
                td.textContent = 'No records found.';
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
              }
              // sort rows by Sector then Sub_Sector (case-insensitive)
              try {
                rows.sort(function(a, b) {
                  var sa = (a.Sector || a.sector || '') + '';
                  var sb = (b.Sector || b.sector || '') + '';
                  sa = sa.toLowerCase();
                  sb = sb.toLowerCase();
                  if (sa < sb) return -1;
                  if (sa > sb) return 1;
                  var ssa = (a.Sub_Sector || a.sub_sector || a.SubSector || '') + '';
                  var ssb = (b.Sub_Sector || b.sub_sector || b.SubSector || '') + '';
                  ssa = ssa.toLowerCase();
                  ssb = ssb.toLowerCase();
                  if (ssa < ssb) return -1;
                  if (ssa > ssb) return 1;
                  var na = (a.Full_Name || a.full_name || a.name || '') + '';
                  var nb = (b.Full_Name || b.full_name || b.name || '') + '';
                  na = na.toLowerCase();
                  nb = nb.toLowerCase();
                  if (na < nb) return -1;
                  if (na > nb) return 1;
                  return 0;
                });
              } catch (e) {
                // if sorting fails, continue without blocking rendering
                console.warn('HOF list sort failed', e);
              }

              rows.forEach(function(r) {
                var tr = document.createElement('tr');
                var its = r.ITS_ID || r.its_id || r.ITS || '';
                var name = r.Full_Name || r.full_name || r.name || '';
                var sector = r.Sector || r.sector || '';
                var sub = r.Sub_Sector || r.sub_sector || r.SubSector || '';
                var mobile = r.RFM_Mobile || r.rfm_mobile || r.RFM || r.mobile || r.Mobile || '';

                var tdIts = document.createElement('td');
                tdIts.textContent = its;
                tr.appendChild(tdIts);

                var tdName = document.createElement('td');
                tdName.textContent = name;
                tr.appendChild(tdName);

                var tdSector = document.createElement('td');
                tdSector.textContent = sector;
                tr.appendChild(tdSector);

                var tdSub = document.createElement('td');
                tdSub.textContent = sub;
                tr.appendChild(tdSub);

                // append mobile column only when visible for this modal mode
                try {
                  var modalEl2 = document.getElementById('hofListModal');
                  var mode2 = modalEl2 ? (modalEl2.getAttribute('data-mode') || '') : '';
                  var showMobile2 = (mode2 !== 'miqaat');
                  if (showMobile2) {
                    var tdMobile = document.createElement('td');
                    tdMobile.classList.add('mobile-col');
                    if (mobile) {
                      var telVal = _normalizePhone(mobile);
                      var a = document.createElement('a');
                      a.href = telVal ? ('tel:' + telVal) : ('tel:' + mobile);
                      a.textContent = mobile;
                      a.style.textDecoration = 'none';
                      tdMobile.appendChild(a);
                    } else {
                      tdMobile.textContent = '';
                    }
                    tr.appendChild(tdMobile);
                  }
                } catch (e) {
                  console.warn('Failed to append mobile cell', e);
                }

                tbody.appendChild(tr);
              });
            }

            function showHofModal(type) {
              var container = document.getElementById('thaali-month-block');
              if (!container) return;
              var curMonth = parseInt(container.getAttribute('data-hijri-month') || '1', 10);
              var curYear = parseInt(container.getAttribute('data-hijri-year') || (new Date()).getFullYear(), 10);
              var url = baseUrl + '?format=json&hijri_year=' + encodeURIComponent(curYear) + '&hijri_month=' + encodeURIComponent(curMonth);

              // set titles and show inner header immediately
              var labelEl = document.getElementById('hofListLabel');
              var innerTitleEl = document.getElementById('hofListInnerTitle');
              var innerHeader = document.getElementById('hofListInnerHeader');
              var countEl = document.getElementById('hofListCount');
              var titleText = (type === 'signed') ? 'HOFs Signed Up This Month' : 'HOFs Not Signed Up This Month';
              if (labelEl) labelEl.textContent = titleText;
              if (innerTitleEl) innerTitleEl.textContent = titleText;
              if (countEl) countEl.textContent = 'Count: 0';
              if (innerHeader) innerHeader.style.display = '';

              // Hide miqaat-specific meta when this modal is opened for Thaali signup
              var miqMeta = document.getElementById('miqaatPopupMeta');
              if (miqMeta) {
                miqMeta.style.display = 'none';
                miqMeta.innerHTML = '';
              }

              // mark modal mode for Thaali (so mobile column remains visible)
              try {
                var modalEl = document.getElementById('hofListModal');
                if (modalEl) modalEl.setAttribute('data-mode', 'thaali');
              } catch (e) {}
              // show spinner
              var spinner = document.getElementById('hofListSpinner');
              var cont = document.getElementById('hofListContainer');
              if (spinner) spinner.style.display = '';
              if (cont) cont.style.display = 'none';
              fetch(url, {
                  credentials: 'same-origin'
                })
                .then(function(res) {
                  return res.json();
                })
                .then(function(payload) {
                  if (!payload || !payload.success) {
                    renderHofList([]);
                    if (countEl) countEl.textContent = 'Count: 0';
                    return;
                  }
                  var m = payload.monthly_stats || {};
                  var rows = (type === 'signed') ? (m.signed_hof_list || []) : (m.no_thaali_list || []);
                  renderHofList(rows);
                  if (countEl) countEl.textContent = 'Count: ' + (Array.isArray(rows) ? rows.length : 0);
                }).catch(function(err) {
                  console.error('Failed to fetch HOF list', err);
                  renderHofList([]);
                  if (countEl) countEl.textContent = 'Count: 0';
                }).finally(function() {
                  if (spinner) spinner.style.display = 'none';
                  if (cont) cont.style.display = '';
                  // show bootstrap modal if available
                  if (window.jQuery && typeof window.jQuery('#hofListModal').modal === 'function') {
                    window.jQuery('#hofListModal').modal('show');
                  } else {
                    var modal = document.getElementById('hofListModal');
                    if (modal) modal.style.display = 'block';
                  }
                });
            }

            // Attach click handlers to cards
            var signedCard = document.getElementById('familiesSignedUpMonthCard');
            var noCard = document.getElementById('noThaaliMonthCard');
            if (signedCard) {
              signedCard.style.cursor = 'pointer';
              signedCard.addEventListener('click', function() {
                showHofModal('signed');
              });
            }
            if (noCard) {
              noCard.style.cursor = 'pointer';
              noCard.addEventListener('click', function() {
                showHofModal('not-signed');
              });
            }
          })();
        </script>



        <?php if (!empty($year_daytype_stats)) { ?>
          <div class="chart-container calendar-block">
            <h4 class="section-title">FMB Calendar Overview (Hijri <?= htmlspecialchars($year_daytype_stats['hijri_year']); ?>)</h4>
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <a href="<?= base_url('common/fmbcalendar?from=amilsaheb'); ?>">
                  <div class="stat-card">
                    <div class="stat-title">Miqaat Days</div>
                    <div class="stat-value"><?= (int)$year_daytype_stats['miqaat_days']; ?></div>
                  </div>
                </a>
              </div>
              <div class="col-12 col-md-4">
                <a href="<?= base_url('common/fmbcalendar?from=amilsaheb'); ?>">
                  <div class="stat-card">
                    <div class="stat-title">Thaali Days</div>
                    <div class="stat-value"><?= (int)$year_daytype_stats['thaali_days']; ?></div>
                  </div>
                </a>
              </div>
              <div class="col-12 col-md-4">
                <a href="<?= base_url('common/fmbcalendar?from=amilsaheb'); ?>">
                  <div class="stat-card">
                    <div class="stat-title">Holidays</div>
                    <div class="stat-value"><?= (int)$year_daytype_stats['holiday_days']; ?></div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        <?php } ?>

        <!-- Finance by Sector (separated containers) -->
        <div class="row mb-2">
          <div class="col-12">
            <div class="chart-container" data-toggle="takhmeen">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="chart-title mb-0">FMB Takhmeen</h5>
                <?php
                $fmbSectorRows = isset($dashboard_data['fmb_takhmeen_sector']) ? $dashboard_data['fmb_takhmeen_sector'] : [];
                $fmbYear = isset($dashboard_data['fmb_takhmeen_year']) ? $dashboard_data['fmb_takhmeen_year'] : null;
                ?>
                <span class="badge badge-light">Hijri <?php echo $fmbYear ? htmlspecialchars($fmbYear) : ''; ?></span>
              </div>
              <?php
              // Build full sector list from overall stats
              $allSectors = [];
              if (isset($stats) && isset($stats['Sectors']) && is_array($stats['Sectors'])) {
                foreach ($stats['Sectors'] as $sr) {
                  $nm = isset($sr['Sector']) ? trim($sr['Sector']) : '';
                  if ($nm !== '') $allSectors[] = $nm;
                }
              }
              $allSectors = array_values(array_unique($allSectors));
              // Map takhmeen rows by sector
              $bySector = [];
              foreach ($fmbSectorRows as $r) {
                $nm = isset($r['sector']) ? trim($r['sector']) : '';
                if ($nm !== '' && strtolower($nm) !== 'unassigned') {
                  $bySector[$nm] = $r;
                }
              }
              // Compose display rows ensuring every sector appears
              $displayFmb = [];
              foreach ($allSectors as $secName) {
                if (isset($bySector[$secName])) {
                  $row = $bySector[$secName];
                  $row['no_takhmeen'] = false;
                  $displayFmb[] = $row;
                } else {
                  $displayFmb[] = [
                    'sector' => $secName,
                    'total_takhmeen' => 0,
                    'total_paid' => 0,
                    'outstanding' => 0,
                    'members' => 0,
                    'no_takhmeen' => true,
                  ];
                }
              }
              if (!empty($displayFmb)) {
              ?>
                <?php
                $fmbTotal = 0;
                $fmbPaid = 0;
                $fmbDue = 0;
                foreach ($displayFmb as $rSum) {
                  $t = (float)($rSum['total_takhmeen'] ?? 0);
                  $p = (float)($rSum['total_paid'] ?? 0);
                  $fmbTotal += $t;
                  $fmbPaid += $p;
                  $fmbDue += max(0, $t - $p);
                }
                ?>
                <div class="takhmeen-summary" role="button" tabindex="0" aria-expanded="false" aria-controls="fmb-details">
                  <div class="row mb-2">
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Total</span>
                          <span class="overview-value text-primary">₹<?= format_inr_no_decimals($fmbTotal); ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Paid</span>
                          <span class="overview-value text-success">₹<?= format_inr_no_decimals($fmbPaid); ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Due</span>
                          <span class="overview-value text-danger">₹<?= format_inr_no_decimals($fmbDue); ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <p class="takhmeen-hint text-center mb-0">Click to view sector-wise details</p>
                </div>
                <div id="fmb-details" class="takhmeen-details" hidden>
                  <div class="d-flex justify-content-end mb-2">
                    <a href="#" class="hide-details">Hide details</a>
                  </div>
                  <div class="row g-2">
                    <?php foreach ($displayFmb as $row):
                      $sector = isset($row['sector']) ? trim($row['sector']) : 'Unassigned';
                      $total = (float)($row['total_takhmeen'] ?? 0);
                      $paid = (float)($row['total_paid'] ?? 0);
                      $due = (float)($row['outstanding'] ?? max(0, $total - $paid));
                      $pct = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                    ?>
                      <div class="col-12 mb-2">
                        <a href="<?= base_url('common/fmbtakhmeen?from=amilsaheb'); ?>" class="text-decoration-none d-block">
                          <div class="fmb-card sector-click"
                            data-type="FMB"
                            data-sector="<?= htmlspecialchars($sector); ?>"
                            data-members="<?= intval($row['members'] ?? 0); ?>"
                            data-total="₹<?= format_inr_no_decimals($total); ?>"
                            data-paid="₹<?= format_inr_no_decimals($paid); ?>"
                            data-due="₹<?= format_inr_no_decimals($due); ?>">
                            <div class="fmb-head">
                              <div class="fmb-name"><i class="fa fa-map-marker text-primary me-2"></i><?= htmlspecialchars($sector); ?>
                                <small class="text-muted ms-2">(<?= intval($row['members'] ?? 0); ?>)</small>
                                <?php if (!empty($row['no_takhmeen'])): ?>
                                  <small class="text-muted ms-2">No takhmeen done</small>
                                <?php endif; ?>
                              </div>
                              <div class="fmb-amounts">
                                <span class="amount-chip has-tooltip" data-full="Total ₹<?= format_inr_no_decimals($total); ?>" title="Total ₹<?= format_inr_no_decimals($total); ?>">Total <span class="val text-primary">₹<?= format_inr_no_decimals($total); ?></span></span>
                                <span class="amount-chip has-tooltip" data-full="Paid ₹<?= format_inr_no_decimals($paid); ?>" title="Paid ₹<?= format_inr_no_decimals($paid); ?>">Paid <span class="val text-success">₹<?= format_inr_no_decimals($paid); ?></span></span>
                                <span class="amount-chip has-tooltip" data-full="Due ₹<?= format_inr_no_decimals($due); ?>" title="Due ₹<?= format_inr_no_decimals($due); ?>">Due <span class="val text-danger">₹<?= format_inr_no_decimals($due); ?></span></span>
                              </div>
                            </div>
                            <div class="progress-slim">
                              <div class="bar" style="width: <?= $pct; ?>%"></div>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php } // end if displayFmb 
              ?>
            </div>
          </div>
          <div class="col-12">
            <div class="chart-container" data-toggle="takhmeen">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="chart-title mb-0">Sabeel Takhmeen</h5>
                <?php
                $sabSectorRows = isset($dashboard_data['sabeel_takhmeen_sector']) ? $dashboard_data['sabeel_takhmeen_sector'] : [];
                $sabYear = isset($dashboard_data['sabeel_takhmeen_year']) ? $dashboard_data['sabeel_takhmeen_year'] : null;
                ?>
                <span class="badge badge-light">Hijri <?php echo $sabYear ? htmlspecialchars($sabYear) : ''; ?></span>
              </div>
              <?php
              // Build full sector list from overall stats
              $allSectors2 = [];
              if (isset($stats) && isset($stats['Sectors']) && is_array($stats['Sectors'])) {
                foreach ($stats['Sectors'] as $sr) {
                  $nm = isset($sr['Sector']) ? trim($sr['Sector']) : '';
                  if ($nm !== '') $allSectors2[] = $nm;
                }
              }
              $allSectors2 = array_values(array_unique($allSectors2));
              // Map takhmeen rows by sector
              $bySector2 = [];
              foreach ($sabSectorRows as $r) {
                $nm = isset($r['sector']) ? trim($r['sector']) : '';
                if ($nm !== '' && strtolower($nm) !== 'unassigned') {
                  $bySector2[$nm] = $r;
                }
              }
              $displaySab = [];
              foreach ($allSectors2 as $secName) {
                if (isset($bySector2[$secName])) {
                  $row = $bySector2[$secName];
                  $row['no_takhmeen'] = false;
                  $displaySab[] = $row;
                } else {
                  $displaySab[] = [
                    'sector' => $secName,
                    'total_takhmeen' => 0,
                    'total_paid' => 0,
                    'outstanding' => 0,
                    'members' => 0,
                    'no_takhmeen' => true,
                  ];
                }
              }
              if (!empty($displaySab)) {
              ?>
                <?php
                $sabTotal = 0;
                $sabPaid = 0;
                $sabDue = 0;
                foreach ($displaySab as $rSum) {
                  $t = (float)($rSum['total_takhmeen'] ?? 0);
                  $p = (float)($rSum['total_paid'] ?? 0);
                  $sabTotal += $t;
                  $sabPaid += $p;
                  $sabDue += max(0, $t - $p);
                }
                ?>
                <div class="takhmeen-summary" role="button" tabindex="0" aria-expanded="false" aria-controls="sab-details">
                  <div class="row mb-2">
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Total</span>
                          <span class="overview-value text-primary">₹<?= format_inr_no_decimals($sabTotal); ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Paid</span>
                          <span class="overview-value text-success">₹<?= format_inr_no_decimals($sabPaid); ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="overview-card">
                        <div class="overview-body">
                          <span class="overview-title">Due</span>
                          <span class="overview-value text-danger">₹<?= format_inr_no_decimals($sabDue); ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <p class="takhmeen-hint text-center mb-0">Click to view sector-wise details</p>
                </div>
                <div id="sab-details" class="takhmeen-details" hidden>
                  <div class="d-flex justify-content-end mb-2">
                    <a href="#" class="hide-details">Hide details</a>
                  </div>
                  <div class="row g-2">
                    <?php foreach ($displaySab as $row):
                      $sector = isset($row['sector']) ? trim($row['sector']) : 'Unassigned';
                      $total = (float)($row['total_takhmeen'] ?? 0);
                      $paid = (float)($row['total_paid'] ?? 0);
                      $due = (float)($row['outstanding'] ?? max(0, $total - $paid));
                      $pct = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                    ?>
                      <div class="col-12 mb-2">
                        <a href="<?= base_url('common/sabeeltakhmeen?from=amilsaheb'); ?>" class="text-decoration-none d-block">
                          <div class="fmb-card sector-click"
                            data-type="Sabeel"
                            data-sector="<?= htmlspecialchars($sector); ?>"
                            data-members="<?= intval($row['members'] ?? 0); ?>"
                            data-total="₹<?= format_inr_no_decimals($total); ?>"
                            data-paid="₹<?= format_inr_no_decimals($paid); ?>"
                            data-due="₹<?= format_inr_no_decimals($due); ?>">
                            <div class="fmb-head">
                              <div class="fmb-name"><i class="fa fa-map-marker text-success me-2"></i><?= htmlspecialchars($sector); ?>
                                <small class="text-muted ms-2">(<?= intval($row['members'] ?? 0); ?>)</small>
                                <?php if (!empty($row['no_takhmeen'])): ?>
                                  <small class="text-muted ms-2">No takhmeen done</small>
                                <?php endif; ?>
                              </div>
                              <div class="fmb-amounts">
                                <span class="amount-chip has-tooltip" data-full="Total ₹<?= format_inr_no_decimals($total); ?>" title="Total ₹<?= format_inr_no_decimals($total); ?>">Total <span class="val text-primary">₹<?= format_inr_no_decimals($total); ?></span></span>
                                <span class="amount-chip has-tooltip" data-full="Paid ₹<?= format_inr_no_decimals($paid); ?>" title="Paid ₹<?= format_inr_no_decimals($paid); ?>">Paid <span class="val text-success">₹<?= format_inr_no_decimals($paid); ?></span></span>
                                <span class="amount-chip has-tooltip" data-full="Due ₹<?= format_inr_no_decimals($due); ?>" title="Due ₹<?= format_inr_no_decimals($due); ?>">Due <span class="val text-danger">₹<?= format_inr_no_decimals($due); ?></span></span>
                              </div>
                            </div>
                            <div class="progress-slim">
                              <div class="bar" style="width: <?= $pct; ?>%; background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);"></div>
                            </div>
                          </div>
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php } // end if displaySab 
              ?>
            </div>
          </div>
        </div>


        <!-- Raza Summary -->
        <div class="row mt-2">
          <div class="col-12 mb-4">
            <style>
              /* Ensure corpus amounts show without forced decimal suffix */
              .corpus-summary .stats-value::after {
                content: none !important;
              }

              /* Wajebaat cards: show full values and no forced decimals */
              .wq-summary-card .stats-value::after {
                content: none !important;
              }

              .wq-summary-card .stats-value {
                white-space: normal;
                overflow: visible;
                text-overflow: unset;
                word-break: break-word;
              }
            </style>
            <a href="<?= base_url('amilsaheb/corpusfunds_details'); ?>" class="text-decoration-none d-block">
              <div class="chart-container compact h-100 corpus-summary clickable"
                data-total="<?= format_inr_no_decimals($sumAmount); ?>"
                data-assigned="<?= format_inr_no_decimals($sumAssigned); ?>"
                data-outstanding="<?= format_inr_no_decimals($sumOutstanding); ?>">
                <div class="d-flex align-items-center mb-2">
                  <h5 class="chart-title m-0">Corpus Funds</h5>
                </div>
                <div class="row text-center g-2">
                  <div class="col-12 col-md-4">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value amount-clickable" title="Click to view">₹<?= format_inr_no_decimals($sumAssigned); ?></div>
                      <div class="stats-label text-dark">Total Takhmeen</div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-success amount-clickable" title="Click to view">₹<?= format_inr_no_decimals($sumPaid); ?></div>
                      <div class="stats-label">Total Received</div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-danger amount-clickable" title="Click to view">₹<?= format_inr_no_decimals($sumOutstanding); ?></div>
                      <div class="stats-label">Total Pending</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <small class="text-muted">Funds: <?= (int)$fundsCount; ?></small>
                  </div>
                  <div class="col-12 mt-2">
                    <span class="btn btn-sm btn-outline-secondary">View All</span>
                  </div>
                </div>
              </div>
            </a>
          </div>

          <?php
          // Ekram Funds summary (mirror Corpus Funds layout)
          $ekramFundsCount = 0;
          $ekram_sumAmount = 0;
          $ekram_sumAssigned = 0;
          $ekram_sumPaid = 0;
          $ekram_sumOutstanding = 0;
          if (isset($ekram_funds) && is_array($ekram_funds)) {
            $ekramFundsCount = count($ekram_funds);
            foreach ($ekram_funds as $f) {
              $amt = isset($f['amount']) ? (float)$f['amount'] : 0;
              $assigned = isset($f['assigned_total']) ? (float)$f['assigned_total'] : 0;
              $paid = isset($f['paid_total']) ? (float)$f['paid_total'] : 0;
              $out = isset($f['outstanding']) ? (float)$f['outstanding'] : 0;
              $ekram_sumAmount += $amt;
              $ekram_sumAssigned += $assigned;
              $ekram_sumPaid += $paid;
              $ekram_sumOutstanding += $out;
            }
          }
          $ekram_sumAmount = (int)round($ekram_sumAmount);
          $ekram_sumAssigned = (int)round($ekram_sumAssigned);
          $ekram_sumPaid = (int)round($ekram_sumPaid);
          $ekram_sumOutstanding = (int)round($ekram_sumOutstanding);
          ?>

          <div class="col-12">
            <div id="ekram-funds-block" class="chart-container grouped-block mt-3 pb-3">
              <h4 class="chart-title">Ekram Funds</h4>
              <div class="row text-center">
                <div class="col-md-4 mb-3">
                  <div class="mini-card">
                    <div class="stats-value">₹<?php echo format_inr($ekram_sumAssigned); ?></div>
                    <div class="stats-label">Total assigned</div>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="mini-card">
                    <div class="stats-value text-success">₹<?php echo format_inr($ekram_sumPaid); ?></div>
                    <div class="stats-label">Total Received</div>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <div class="mini-card">
                    <div class="stats-value text-danger">₹<?php echo format_inr($ekram_sumOutstanding); ?></div>
                    <div class="stats-label">Total Pending</div>
                  </div>
                </div>
              </div>
              <div class="text-center mt-2">
                <small>Funds: <?php echo $ekramFundsCount; ?></small>
                <div style="margin-top:8px; margin-bottom:12px;"><a class="btn btn-sm btn-outline-secondary" href="<?php echo base_url('amilsaheb/ekramfunds_details'); ?>">View All</a></div>
              </div>
              <style>
                /* Ensure Ekram card uses available width and matches appearance of other summary cards */
                #ekram-funds-block {
                  width: 100%;
                  max-width: 100%;
                }

                #ekram-funds-block .mini-card {
                  min-width: 140px;
                }
              </style>
            </div>
          </div>

          <?php
          $wa = isset($dashboard_data['wajebaat_summary']) && is_array($dashboard_data['wajebaat_summary'])
            ? $dashboard_data['wajebaat_summary']
            : ['count' => 0, 'total' => 0, 'received' => 0, 'due' => 0];
          ?>
          <div class="col-12 mb-4">
            <div class="row g-2">
              <div class="col-12 mb-3">
                <a href="<?= base_url('amilsaheb/wajebaat_details'); ?>" class="text-decoration-none d-block">
                  <div class="chart-container compact h-100 clickable wq-summary-card">
                    <div class="d-flex align-items-center mb-2">
                      <h5 class="chart-title m-0">Wajebaat</h5>
                    </div>
                    <div class="row text-center g-2">
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-primary">₹<?= format_inr_no_decimals((int)($wa['total'] ?? 0)); ?></div>
                          <div class="stats-label">Total</div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-success">₹<?= format_inr_no_decimals((int)($wa['received'] ?? 0)); ?></div>
                          <div class="stats-label">Received</div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-danger">₹<?= format_inr_no_decimals((int)($wa['due'] ?? 0)); ?></div>
                          <div class="stats-label">Pending</div>
                        </div>
                      </div>
                      <div class="col-12 mt-2">
                        <span class="btn btn-sm btn-outline-secondary">View All</span>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>

          <?php
          $qh_tot = isset($qh_all_schemes_totals) && is_array($qh_all_schemes_totals)
            ? $qh_all_schemes_totals
            : ['mohammedi' => 0, 'taher' => 0, 'husain' => 0, 'total' => 0];
          ?>
          <div class="col-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="chart-title m-0">Qardan Hasana Schemes</h5>
                <a href="<?= base_url('amilsaheb/qardanhasana'); ?>" class="btn btn-sm btn-outline-secondary">View</a>
              </div>
              <div class="row text-center g-2">
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('amilsaheb/qardanhasana/mohammedi'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['mohammedi'] ?? 0), 0); ?></div>
                      <div class="stats-label">Mohammedi</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('amilsaheb/qardanhasana/taher'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['taher'] ?? 0), 0); ?></div>
                      <div class="stats-label">Taher</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('amilsaheb/qardanhasana/husain'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['husain'] ?? 0), 0); ?></div>
                      <div class="stats-label">Husain</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('amilsaheb/qardanhasana'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-success">₹<?= format_inr((float)($qh_tot['total'] ?? 0), 0); ?></div>
                      <div class="stats-label">Total</div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <?php
          $dashboard_expenses = isset($dashboard_expenses) && is_array($dashboard_expenses) ? $dashboard_expenses : [];
          $dashboard_expense_total = isset($dashboard_expense_total) ? (float)$dashboard_expense_total : 0.0;
          $dashboard_expense_hijri_year = isset($dashboard_expense_hijri_year) ? (int)$dashboard_expense_hijri_year : null;
          ?>
          <div class="col-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="chart-title m-0">Expenses</h5>
              </div>

              <div class="text-center py-3">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-4">
                    <div class="mini-card">
                      <div class="stats-value">
                        ₹<?= number_format($dashboard_expense_total, 0); ?>
                      </div>
                      <div class="stats-label">
                        Expense<?= $dashboard_expense_hijri_year ? ' for ' . $dashboard_expense_hijri_year : ''; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-center mb-2">
                <a href="<?= base_url('amilsaheb/expense'); ?>" class="btn btn-sm btn-outline-secondary">View All</a>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="chart-container">
              <h5 class="chart-title">Raza Summary</h5>
              <?php $rz = isset($dashboard_data['raza_summary']) ? $dashboard_data['raza_summary'] : ['pending' => 0, 'approved' => 0, 'rejected' => 0]; ?>
              <div class="row text-center">
                <div class="col-12 col-md-4">
                  <div class="mini-card">
                    <div class="stats-value"><?php echo (int)$rz['pending']; ?></div>
                    <div class="stats-label">Pending</div>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="mini-card">
                    <div class="stats-value"><?php echo (int)$rz['approved']; ?></div>
                    <div class="stats-label">Approved</div>
                  </div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="mini-card">
                    <div class="stats-value"><?php echo (int)$rz['rejected']; ?></div>
                    <div class="stats-label">Rejected</div>
                  </div>
                </div>
              </div>
              <div class="mt-3 text-center">
                <a class="btn btn-sm btn-outline-primary" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=1'); ?>">View Miqaat Raza</a>
                <a class="btn btn-sm btn-outline-primary mt-2 mt-md-0" href="<?php echo base_url('amilsaheb/EventRazaRequest?event_type=2'); ?>">View Kaaraj Raza</a>
                <a class="btn btn-sm btn-outline-secondary mt-2 mt-md-0" href="<?php echo base_url('amilsaheb/UmoorRazaRequest'); ?>">View Umoor Raza</a>
              </div>
            </div>
          </div>
        </div>

        <script>
          // Disable browser back button
          history.pushState(null, null, location.href);
          window.onpopstate = function() {
            history.pushState(null, null, location.href);
          };

          // Ensure DOM is fully parsed before querying elements defined later
          document.addEventListener('DOMContentLoaded', function() {
            // Color accents on cards for a more modern, colorful look
            (function() {
              const accents = [{
                  bg: '#eef2ff',
                  fg: '#4f46e5'
                }, // indigo
                {
                  bg: '#ecfeff',
                  fg: '#0891b2'
                }, // cyan
                {
                  bg: '#fff7ed',
                  fg: '#ea580c'
                }, // orange
                {
                  bg: '#f5f3ff',
                  fg: '#7c3aed'
                }, // violet
                {
                  bg: '#f0fdf4',
                  fg: '#16a34a'
                }, // green
                {
                  bg: '#fff1f2',
                  fg: '#dc2626'
                }, // red
                {
                  bg: '#eff6ff',
                  fg: '#2563eb'
                }, // blue
                {
                  bg: '#fef3c7',
                  fg: '#d97706'
                }, // amber
              ];

              function hexToRgb(hex) {
                const m = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                return m ? {
                  r: parseInt(m[1], 16),
                  g: parseInt(m[2], 16),
                  b: parseInt(m[3], 16)
                } : null;
              }

              // Overview mini-cards: icon background (remove left border accent)
              document.querySelectorAll('.overview-card').forEach((card, i) => {
                const c = accents[i % accents.length];
                try {
                  // Remove left border accent per request
                  // card.style.borderLeft = 'none';
                  const icon = card.querySelector('.overview-icon');
                  if (icon) {
                    icon.style.background = c.bg;
                    icon.style.color = c.fg;
                  }
                } catch (e) {
                  /* no-op */
                }
              });

              // Finance and weekly cards: soft tint and accented numbers (remove left border accent)
              document.querySelectorAll('.fmb-card').forEach((card, i) => {
                const c = accents[i % accents.length];
                // Remove left border accent per request
                card.style.borderLeft = 'none';
                const rgb = hexToRgb(c.fg);
                if (rgb) {
                  const tint = `linear-gradient(180deg, rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.06), rgba(255,255,255,0))`;
                  card.style.backgroundImage = tint;
                }
                card.querySelectorAll('.fmb-amounts .val').forEach(v => v.style.color = c.fg);
                const nameIcon = card.querySelector('.fmb-name i');
                if (nameIcon) nameIcon.style.color = c.fg;
              });

              // Calendar tiles: top border accent
              document.querySelectorAll('.calendar-block .stat-card').forEach((card, i) => {
                const c = accents[i % accents.length];
                // card.style.borderTop = '3px solid ' + c.fg;
              });
            })();

            // Disable corpus funds modal popups; navigation handled via anchor links
            (function() {
              /* no-op: popups removed per requirements */
            })();

            // Sidebar toggle for mobile
            (function() {
              var toggleBtn = document.getElementById('sidebarToggle');
              var sidebar = document.querySelector('.sidebar-menu');
              var overlay = document.getElementById('sidebarOverlay');
              if (toggleBtn && sidebar && overlay) {
                function openSidebar() {
                  sidebar.classList.add('open');
                  overlay.classList.add('show');
                }

                function closeSidebar() {
                  sidebar.classList.remove('open');
                  overlay.classList.remove('show');
                }
                toggleBtn.addEventListener('click', function() {
                  sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
                });
                overlay.addEventListener('click', closeSidebar);
                sidebar.addEventListener('click', function(e) {
                  var a = e.target.closest('a.menu-item');
                  if (a) closeSidebar();
                });
              }
            })();

            // Collapsible Quick Menu sections (Raza/Finance/Reports/Appointments/Activity)
            (function() {
              var sidebarMenu = document.querySelector('.sidebar-menu');
              if (!sidebarMenu) return;

              var searchInput = document.getElementById('quickMenuSearch');

              var sections = Array.prototype.slice.call(sidebarMenu.querySelectorAll('.menu-section'));
              if (!sections.length) return;

              function safeGet(key) {
                try {
                  return window.localStorage ? localStorage.getItem(key) : null;
                } catch (e) {
                  return null;
                }
              }
              function safeSet(key, val) {
                try {
                  if (window.localStorage) localStorage.setItem(key, val);
                } catch (e) {}
              }

              var prefix = 'quickmenu:' + (window.location && window.location.pathname ? window.location.pathname : 'page');

              sections.forEach(function(sec, idx) {
                var list = sec.nextElementSibling;
                if (!list || !list.classList || !list.classList.contains('menu-list')) return;

                sec.setAttribute('role', 'button');
                sec.setAttribute('tabindex', '0');

                if (!list.id) {
                  list.id = 'qm-' + idx + '-' + Math.random().toString(36).slice(2, 8);
                }
                sec.setAttribute('aria-controls', list.id);

                var label = (sec.textContent || '').trim().toLowerCase();
                var key = prefix + '|' + label;
                var stored = safeGet(key);
                // Default: collapsed (unless user previously expanded it)
                var collapsed = stored === null ? true : (stored === '1');

                function applyState() {
                  list.style.display = collapsed ? 'none' : '';
                  sec.classList.toggle('is-collapsed', collapsed);
                  sec.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                  safeSet(key, collapsed ? '1' : '0');
                }

                function toggle() {
                  collapsed = !collapsed;
                  applyState();
                }

                sec.addEventListener('click', function(e) {
                  if (window.getSelection && String(window.getSelection()).length) return;
                  // While searching, keep sections expanded so matches are visible.
                  if (searchInput && (searchInput.value || '').trim().length) return;
                  toggle();
                });
                sec.addEventListener('keydown', function(e) {
                  if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (searchInput && (searchInput.value || '').trim().length) return;
                    toggle();
                  }
                });

                applyState();
              });
            })();

            // Quick Menu search filter
            (function() {
              var sidebarMenu = document.querySelector('.sidebar-menu');
              var input = document.getElementById('quickMenuSearch');
              var clearBtn = document.getElementById('quickMenuClear');
              if (!sidebarMenu || !input) return;

              var sections = Array.prototype.slice.call(sidebarMenu.querySelectorAll('.menu-section'));
              var lists = Array.prototype.slice.call(sidebarMenu.querySelectorAll('.menu-list'));

              function normalize(s) {
                return (s || '').toLowerCase().replace(/\s+/g, ' ').trim();
              }

              function apply() {
                var q = normalize(input.value);
                if (clearBtn) clearBtn.style.display = q ? 'inline-flex' : 'none';

                lists.forEach(function(list) {
                  if (!q) {
                    list.dataset.prevDisplay = (list.style.display || '');
                  }

                  var items = Array.prototype.slice.call(list.querySelectorAll('li'));
                  var visibleCount = 0;
                  items.forEach(function(li) {
                    var labelEl = li.querySelector('.menu-label');
                    var text = normalize(labelEl ? labelEl.textContent : li.textContent);
                    var match = !q || (text.indexOf(q) !== -1);
                    li.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                  });

                  list.dataset.searchVisibleCount = String(visibleCount);

                  if (q) {
                    list.style.display = visibleCount > 0 ? '' : 'none';
                  } else {
                    list.style.display = list.dataset.prevDisplay || '';
                  }
                });

                sections.forEach(function(sec) {
                  var list = sec.nextElementSibling;
                  if (!list || !list.classList || !list.classList.contains('menu-list')) return;

                  if (q) {
                    var vc = parseInt(list.dataset.searchVisibleCount || '0', 10);
                    sec.style.display = vc > 0 ? '' : 'none';
                    sec.classList.remove('is-collapsed');
                    sec.setAttribute('aria-expanded', vc > 0 ? 'true' : 'false');
                  } else {
                    sec.style.display = '';
                  }
                });
              }

              input.addEventListener('input', apply);
              if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                  input.value = '';
                  input.focus();
                  apply();
                });
              }

              apply();
            })();

            // Disable sector details modal; cards now navigate via links
            (function() {
              /* no-op: modal removed per requirements */
            })();

            // Toggle takhmeen containers (summary -> sector details)
            (function() {
              document.querySelectorAll('.chart-container[data-toggle="takhmeen"]').forEach(function(container) {
                var summary = container.querySelector('.takhmeen-summary');
                var details = container.querySelector('.takhmeen-details');
                if (!summary || !details) return;

                function openDetails() {
                  details.hidden = false;
                  summary.setAttribute('aria-expanded', 'true');
                  summary.style.display = 'none';
                }

                function closeDetails() {
                  details.hidden = true;
                  summary.setAttribute('aria-expanded', 'false');
                  summary.style.display = '';
                }

                summary.addEventListener('click', openDetails);
                summary.addEventListener('keydown', function(e) {
                  if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openDetails();
                  }
                });

                var hide = details.querySelector('.hide-details');
                if (hide) hide.addEventListener('click', function(e) {
                  e.preventDefault();
                  closeDetails();
                });
              });
            })();
          });
        </script>
      </div> <!-- /Right Content -->
    </div> <!-- /row -->
    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <!-- Sector Details Modal -->
    <div id="sectorModal" class="modal-overlay" aria-hidden="true" role="dialog" aria-labelledby="sectorModalTitle">
      <div class="modal-card">
        <div class="modal-head">
          <h5 id="sectorModalTitle" class="modal-title">Sector</h5>
          <button id="sectorModalClose" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div id="sectorModalMeta" class="text-muted" style="margin-bottom:8px;">&nbsp;</div>
        <div class="modal-grid">
          <div class="pill"><span class="label">Total</span><span id="sectorModalTotal" class="value">₹0</span></div>
          <div class="pill"><span class="label">Paid</span><span id="sectorModalPaid" class="value">₹0</span></div>
          <div class="pill"><span class="label">Due</span><span id="sectorModalDue" class="value">₹0</span></div>
        </div>
      </div>
    </div>
  </div>
  <script>
    (function() {
      var upcoming = <?= json_encode(array_values($upcoming)); ?> || [];
      var container = document.getElementById('miqaat-rsvp-block');
      var index = parseInt(container ? container.getAttribute('data-initial-index') || '0' : '0', 10) || 0;
      var titleEl = document.getElementById('miqaat-current-title');
      var rsvpCountEl = document.getElementById('miqaatRsvpCount');
      var notCountEl = document.getElementById('miqaatNotRsvpCount');
      var rsvpCard = document.getElementById('miqaatRsvpCard');
      var notCard = document.getElementById('miqaatNotRsvpCard');
      var viewDetails = document.getElementById('miqaat-view-details');

      // Update guest breakdown counts when miqaat JSON is loaded
      function updateGuestCountsFromPayload(m) {
        try {
          var gs = (m && m.guest_summary) ? m.guest_summary : {
            gents: 0,
            ladies: 0,
            children: 0,
            total: 0
          };
          var ms = (m && m.member_summary) ? m.member_summary : {
            gents: 0,
            ladies: 0,
            children: 0,
            total: 0
          };
          var cs = (m && m.combined_summary) ? m.combined_summary : {
            gents: (ms.gents || 0) + (gs.gents || 0),
            ladies: (ms.ladies || 0) + (gs.ladies || 0),
            children: (ms.children || 0) + (gs.children || 0),
            total: (ms.total || 0) + (gs.total || 0)
          };
          var gentsEl = document.getElementById('guestGentsCount');
          var ladiesEl = document.getElementById('guestLadiesCount');
          var childrenEl = document.getElementById('guestChildrenCount');
          var totalEl = document.getElementById('guestTotalCount');
          var gentsBreak = document.getElementById('guestGentsBreakdown');
          var ladiesBreak = document.getElementById('guestLadiesBreakdown');
          var childrenBreak = document.getElementById('guestChildrenBreakdown');
          var totalBreak = document.getElementById('guestTotalBreakdown');
          if (gentsEl) gentsEl.textContent = (cs.gents || 0);
          if (ladiesEl) ladiesEl.textContent = (cs.ladies || 0);
          if (childrenEl) childrenEl.textContent = (cs.children || 0);
          if (totalEl) totalEl.textContent = (cs.total || 0);
          if (gentsBreak) gentsBreak.textContent = 'Members: ' + (ms.gents || 0) + ' | Guests: ' + (gs.gents || 0);
          if (ladiesBreak) ladiesBreak.textContent = 'Members: ' + (ms.ladies || 0) + ' | Guests: ' + (gs.ladies || 0);
          if (childrenBreak) childrenBreak.textContent = 'Members: ' + (ms.children || 0) + ' | Guests: ' + (gs.children || 0);
          if (totalBreak) totalBreak.textContent = 'Members: ' + (ms.total || 0) + ' | Guests: ' + (gs.total || 0);
          try {
            var waGuestEl = document.getElementById('willAttendGuest');
            if (waGuestEl) waGuestEl.textContent = (gs.total > 0 ? ('+' + (gs.total || 0) + ' guests') : '');
          } catch (e) {}
        } catch (e) {
          console.warn('Failed to update guest counts', e);
        }
      }

      function renderFor(i) {
        if (!upcoming || !upcoming.length) {
          if (titleEl) titleEl.textContent = 'No upcoming miqaat';
          return;
        }
        if (i < 0) i = 0;
        if (i >= upcoming.length) i = upcoming.length - 1;
        index = i;
        var mi = upcoming[index];
        var miqId = mi.id || mi.miqaat_id || '';
        var miqName = mi.name || ('Miqaat ' + miqId);
        var dateLabel = mi.hijri_label || mi.date || '';
        if (titleEl) {
          function _escapeHtml(s) {
            return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
          }
          var nameHtml = '<div class="miqaat-name">' + _escapeHtml(miqName) + '</div>';
          var dateHtml = dateLabel ? ('<div class="miqaat-date text-center" style="font-size: 14px;">' + _escapeHtml(dateLabel) + '</div>') : '';
          titleEl.innerHTML = nameHtml + dateHtml;
        }
        if (rsvpCard) rsvpCard.setAttribute('data-miqaat-id', miqId);
        if (notCard) notCard.setAttribute('data-miqaat-id', miqId);
        if (viewDetails) viewDetails.href = '<?= base_url("common/rsvp_list?from=amilsaheb"); ?>' + '?miqaat_id=' + encodeURIComponent(miqId);

        var apiBase = '<?= rtrim(base_url('anjuman'), '/'); ?>';
        var url = apiBase + '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(miqId);
        fetch(url, {
          credentials: 'same-origin'
        }).then(function(resp) {
          return resp.json();
        }).then(function(data) {
          if (!data || !data.miqaat_rsvp) return;
          var m = data.miqaat_rsvp || {};
          try {
            if (typeof updateGuestCountsFromPayload === 'function') updateGuestCountsFromPayload(m);
          } catch (e) {}
          try {
            var waEl = document.getElementById('willAttendCount');
            var wnaEl = document.getElementById('willNotAttendCount');
            var nsEl = document.getElementById('rsvpNotSubmittedCount');
            if (waEl) waEl.textContent = '0';
            if (wnaEl) wnaEl.textContent = '0';
            if (nsEl) nsEl.textContent = '0';
          } catch (e) {}

          try {
            var countsUrl = '<?= base_url('common/miqaat_rsvp_user_counts'); ?>?miqaat_id=' + encodeURIComponent(miqId);
            fetch(countsUrl, {
              credentials: 'same-origin'
            }).then(function(r) {
              return r.json();
            }).then(function(cdata) {
              if (cdata && cdata.success) {
                var wa = cdata.will_attend || 0;
                var wna = cdata.will_not_attend || 0;
                var ns = cdata.rsvp_not_submitted || 0;
                var waEl2 = document.getElementById('willAttendCount');
                var wnaEl2 = document.getElementById('willNotAttendCount');
                var nsEl2 = document.getElementById('rsvpNotSubmittedCount');
                var memberTotal = (m && m.member_summary && m.member_summary.total) ? m.member_summary.total : 0;
                var guestTotal = (m && m.guest_summary && m.guest_summary.total) ? m.guest_summary.total : 0;
                var combined = (memberTotal + guestTotal) || wa;
                if (waEl2) waEl2.textContent = combined;
                try {
                  var waGuestEl = document.getElementById('willAttendGuest');
                  if (waGuestEl) waGuestEl.textContent = (guestTotal > 0 ? ('+' + guestTotal + ' guests') : '');
                } catch (e) {}
                if (wnaEl2) wnaEl2.textContent = wna;
                if (nsEl2) nsEl2.textContent = ns;
              }
            }).catch(function(err) {
              console.warn('Failed to fetch per-user RSVP counts', err);
            });
          } catch (e) {
            console.warn('Counts fetch failed', e);
          }
        }).catch(function(err) {
          console.error('Failed to fetch miqaat rsvp data', err);
        });
      }

      document.addEventListener('click', function(e) {
        var t = e.target.closest && e.target.closest('.miqaat-nav-btn');
        if (!t) return;
        e.preventDefault();
        if (t.classList.contains('prev')) {
          if (index > 0) {
            renderFor(index - 1);
            return;
          }
          var first = (upcoming && upcoming.length) ? upcoming[0] : null;
          var beforeDate = first ? (first.date || '') : '';
          if (!beforeDate) return;
          var apiBase = '<?= rtrim(base_url('anjuman'), '/'); ?>';
          var url = apiBase + '?format=json&miqaat_prev=1&before_date=' + encodeURIComponent(beforeDate);
          var loadingEl = document.getElementById('miqaatLoading');
          var msgEl = document.getElementById('miqaatMessage');
          if (loadingEl) loadingEl.style.display = 'flex';
          t.style.pointerEvents = 'none';
          fetch(url, {
            credentials: 'same-origin'
          }).then(function(resp) {
            return resp.json();
          }).then(function(data) {
            if (loadingEl) loadingEl.style.display = 'none';
            t.style.pointerEvents = '';
            if (!data || !data.success || !data.miqaat) {
              if (msgEl) {
                msgEl.textContent = 'No earlier miqaat found';
                msgEl.style.display = 'block';
                clearTimeout(msgEl._t);
                msgEl._t = setTimeout(function() {
                  msgEl.style.display = 'none';
                }, 3000);
              }
              return;
            }
            upcoming.unshift(data.miqaat);
            renderFor(0);
          }).catch(function(err) {
            if (loadingEl) loadingEl.style.display = 'none';
            t.style.pointerEvents = '';
            console.error('Failed to fetch previous miqaat', err);
            if (msgEl) {
              msgEl.textContent = 'Failed to fetch earlier miqaat';
              msgEl.style.display = 'block';
              clearTimeout(msgEl._t);
              msgEl._t = setTimeout(function() {
                msgEl.style.display = 'none';
              }, 3000);
            }
          });
        } else {
          renderFor(index + 1);
        }
      });

      // initial render
      renderFor(index);

      // Click handler for opening miqaat RSVP modal: fetch miqaat JSON and populate HOF modal
      document.addEventListener('click', function(e) {
        var a = e.target.closest && e.target.closest('.open-miqaat-modal');
        if (!a) return;
        e.preventDefault();
        var dtype = a.getAttribute('data-type') || 'rsvp';
        var mid = a.getAttribute('data-miqaat-id') || (upcoming && upcoming[index] && (upcoming[index].id || upcoming[index].miqaat_id)) || '';
        if (!mid) return;

        function _escapeHtml(s) {
          return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // set modal title and show inner header
        var labelEl = document.getElementById('hofListLabel');
        var innerHeader = document.getElementById('hofListInnerHeader');
        var countEl = document.getElementById('hofListCount');
        if (labelEl) labelEl.textContent = (dtype === 'rsvp' ? "RSVP'd Members" : (dtype === 'no' ? 'Members Will not attend' : (dtype === 'not_submitted' ? 'Members Not Submitted' : (dtype === 'gents' ? 'Gents' : (dtype === 'ladies' ? 'Ladies' : (dtype === 'children' ? 'Children' : 'Members'))))));
        if (innerHeader) innerHeader.style.display = '';
        if (countEl) countEl.textContent = 'Count: 0';

        var spinner = document.getElementById('hofListSpinner');
        var container = document.getElementById('hofListContainer');
        if (spinner) spinner.style.display = '';
        if (container) container.style.display = 'none';

        // mark modal mode for Miqaat (hide mobile column)
        try {
          var modalEl = document.getElementById('hofListModal');
          if (modalEl) modalEl.setAttribute('data-mode', 'miqaat');
        } catch (e) {}
        // fetch miqaat JSON from Anjuman endpoint
        var apiBase = '<?= rtrim(base_url('anjuman'), '/'); ?>';
        var url = apiBase + '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(mid);
        fetch(url, {
          credentials: 'same-origin'
        }).then(function(resp) {
          return resp.json();
        }).then(function(data) {
          if (spinner) spinner.style.display = 'none';
          if (!data || !data.miqaat_rsvp) {
            if (container) {
              // render empty state inside table body
              var tbody = document.querySelector('#hofListTable tbody');
              try {
                var modalElTmp = document.getElementById('hofListModal');
                var modeTmp = modalElTmp ? (modalElTmp.getAttribute('data-mode') || '') : '';
                var showMobileTmp = (modeTmp !== 'miqaat');
                var colspanTmp = showMobileTmp ? 5 : 4;
                if (tbody) tbody.innerHTML = '<tr><td colspan="' + colspanTmp + '" class="text-center text-muted">No records found.</td></tr>';
              } catch (e) {
                if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No records found.</td></tr>';
              }
              container.style.display = '';
            }
            if (countEl) countEl.textContent = 'Count: 0';
            return;
          }
          var m = data.miqaat_rsvp;
          try {
            if (typeof updateGuestCountsFromPayload === 'function') updateGuestCountsFromPayload(m);
          } catch (e) {}

          // populate miqaat meta (name, date, counts badges)
          try {
            var miObj = (upcoming || []).find(function(x) {
              return String(x.id || x.miqaat_id || '') === String(mid);
            }) || {};
            var miName = miObj && (miObj.name || miObj.miqaat_name) ? (miObj.name || miObj.miqaat_name) : '';
            var miDateLabel = miObj && (miObj.hijri_label || miObj.date) ? (miObj.hijri_label || miObj.date) : '';
            var metaEl = document.getElementById('miqaatPopupMeta');
            if (metaEl) {
              metaEl.innerHTML = '<div style="font-weight:600;">' + _escapeHtml(miName || 'Miqaat') + '</div>' + (miDateLabel ? ('<div class="text-muted">' + _escapeHtml(miDateLabel) + '</div>') : '') + '<div style="margin-top:8px;">' + '<span class="badge badge-success" id="popupWillAttend" style="margin-right:8px;">Will attend: 0</span>' + '<span class="badge badge-danger" id="popupWillNotAttend" style="margin-right:8px;">Will not attend: 0</span>' + '<span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span>' + '</div>';
              metaEl.style.display = '';
            }
            // fetch per-user classification counts and update popup badges
            try {
              var countsUrl = '<?= base_url('common/miqaat_rsvp_user_counts'); ?>?miqaat_id=' + encodeURIComponent(mid);
              fetch(countsUrl, {
                credentials: 'same-origin'
              }).then(function(r) {
                return r.json();
              }).then(function(cdata) {
                if (cdata && cdata.success) {
                  var pw = document.getElementById('popupWillAttend');
                  var pwn = document.getElementById('popupWillNotAttend');
                  var pns = document.getElementById('popupNotSubmitted');
                  if (pw) pw.textContent = 'Will attend: ' + (cdata.will_attend || 0);
                  if (pwn) pwn.textContent = 'Will not attend: ' + (cdata.will_not_attend || 0);
                  if (pns) pns.textContent = 'Not submitted: ' + (cdata.rsvp_not_submitted || 0);
                }
              }).catch(function(err) {
                console.warn('Failed to fetch popup counts', err);
              });
            } catch (e) {
              console.warn('Counts fetch failed', e);
            }
          } catch (e) {
            console.warn('Failed to prepare miqaat meta', e);
          }

          var rows = [];
          if (dtype === 'rsvp') {
            rows = (m.rsvp_member_list && m.rsvp_member_list.length) ? m.rsvp_member_list : (m.rsvp_list || []);
          } else if (dtype === 'no') {
            rows = (m.not_rsvp_member_list && m.not_rsvp_member_list.length) ? m.not_rsvp_member_list : (m.not_rsvp_list || []);
          } else if (dtype === 'not_submitted') {
            rows = (m.not_submitted_member_list && m.not_submitted_member_list.length) ? m.not_submitted_member_list : [];
          } else if (dtype === 'gents') {
            rows = (m.rsvp_male_member_list && m.rsvp_male_member_list.length) ? m.rsvp_male_member_list : (m.rsvp_member_list || []);
          } else if (dtype === 'ladies') {
            rows = (m.rsvp_female_member_list && m.rsvp_female_member_list.length) ? m.rsvp_female_member_list : (m.rsvp_member_list || []);
          } else if (dtype === 'children') {
            rows = (m.rsvp_children_member_list && m.rsvp_children_member_list.length) ? m.rsvp_children_member_list : [];
          }

          // render using existing renderHofList helper
          try {
            renderHofList(rows);
          } catch (e) {
            // fallback: build simple tbody
            var tbody = document.querySelector('#hofListTable tbody');
            if (tbody) {
              tbody.innerHTML = '';
              try {
                var modalElTmp2 = document.getElementById('hofListModal');
                var modeTmp2 = modalElTmp2 ? (modalElTmp2.getAttribute('data-mode') || '') : '';
                var showMobileTmp2 = (modeTmp2 !== 'miqaat');
                if (!rows || !rows.length) {
                  var colspanTmp2 = showMobileTmp2 ? 5 : 4;
                  tbody.innerHTML = '<tr><td colspan="' + colspanTmp2 + '" class="text-center text-muted">No records found.</td></tr>';
                } else {
                  rows.forEach(function(r) {
                    var tr = document.createElement('tr');
                    var its = r.ITS_ID || r.its_id || r.ITS || '';
                    var name = r.Full_Name || r.full_name || r.name || '';
                    var sector = r.Sector || r.sector || '';
                    var sub = r.Sub_Sector || r.sub_sector || r.SubSector || '';
                    var html = '<td>' + _escapeHtml(its) + '</td><td>' + _escapeHtml(name) + '</td><td>' + _escapeHtml(sector) + '</td><td>' + _escapeHtml(sub) + '</td>';
                    if (showMobileTmp2) html += '<td class="mobile-col"></td>';
                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                  });
                }
              } catch (e) {
                if (!rows || !rows.length) {
                  tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No records found.</td></tr>';
                } else {
                  rows.forEach(function(r) {
                    var tr = document.createElement('tr');
                    var its = r.ITS_ID || r.its_id || r.ITS || '';
                    var name = r.Full_Name || r.full_name || r.name || '';
                    var sector = r.Sector || r.sector || '';
                    var sub = r.Sub_Sector || r.sub_sector || r.SubSector || '';
                    tr.innerHTML = '<td>' + _escapeHtml(its) + '</td><td>' + _escapeHtml(name) + '</td><td>' + _escapeHtml(sector) + '</td><td>' + _escapeHtml(sub) + '</td><td></td>';
                    tbody.appendChild(tr);
                  });
                }
              }
            }
          }
          if (container) container.style.display = '';
          if (countEl) countEl.textContent = 'Count: ' + (Array.isArray(rows) ? rows.length : 0);
          try {
            if (window.jQuery && typeof window.jQuery('#hofListModal').modal === 'function') {
              window.jQuery('#hofListModal').modal('show');
            } else {
              var modal = document.getElementById('hofListModal');
              if (modal) modal.style.display = 'block';
            }
          } catch (e) {}
        }).catch(function(err) {
          if (spinner) spinner.style.display = 'none';
          console.error('Failed to fetch miqaat rsvp for modal', err);
          if (container) container.style.display = '';
          if (countEl) countEl.textContent = 'Count: 0';
        });
      });

    })();
  </script>