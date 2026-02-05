<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  /* Spacing tweaks for takhmeen summary and corpus table */
  .takhmeen-summary .row.mb-2 {
    margin-bottom: 10px !important;
  }

  .takhmeen-summary .overview-card {
    margin-bottom: 8px;
  }

  .chart-container.compact {
    padding: 14px 16px;
  }

  .chart-container+.container.margintopcontainer {
    margin-top: 10px;
  }

  /* Corpus table compact padding and no-wrap currency */
  .chart-container table.table.table-sm thead th {
    padding: 6px 10px;
  }

  .chart-container table.table.table-sm tbody td {
    padding: 6px 10px;
    vertical-align: middle;
  }

  .chart-container table.table.table-sm td:nth-child(3),
  .chart-container table.table.table-sm td:nth-child(4),
  .chart-container table.table.table-sm td:nth-child(5) {
    white-space: nowrap;
  }

  /* Reduce bottom gaps between dashboard blocks */
  .chart-container.grouped-block {
    margin-bottom: 12px;
  }

  /* Hide sidebar close button by default (desktop/tablet) */
  .sidebar-close-btn {
    display: none;
  }

  .row.mb-2 {
    margin-bottom: 8px !important;
  }

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

  /* Mobile toolbar and menu button styling (restores original compact Menu button) */
  .mobile-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: #ffffff;
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.04);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  }

  .mobile-menu-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 18px;
    background: #eef2ff;
    /* soft purple background */
    color: #4f46e5;
    /* indigo text */
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1;
    border: 1px solid rgba(79, 70, 229, 0.12);
    /* subtle indigo border */
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  }

  /* small boxed icon on the left */
  .mobile-menu-btn .menu-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #ffffff;
    color: #4f46e5;
    border: 1px solid rgba(79, 70, 229, 0.12);
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
    font-size: 14px;
  }

  .mobile-menu-btn .menu-text {
    display: inline-block;
    vertical-align: middle;
    font-size: 0.95rem;
    color: #4f46e5;
  }

  .mobile-title {
    margin-left: 8px;
    font-weight: 600;
    color: #111827;
    display: inline-block;
    text-align: right;
  }

  /* Miqaat title styles used in the switcher */
  .miqaat-name {
    color: #0ea5a5;
    font-weight: 700;
    font-size: 18px;
    line-height: 1.1;
    text-align: center;
  }

  .miqaat-date {
    color: #0ea5a5;
    font-size: 13px;
    font-weight: 600;
    margin-top: 6px;
    text-align: center;
  }

  /* Loading overlay for miqaat switcher */
  .miqaat-loading-overlay {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 12px;
    z-index: 5;
  }

  .miqaat-spinner {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 4px solid rgba(0, 0, 0, 0.08);
    border-top-color: #3b82f6;
    animation: miqaat-spin 1s linear infinite;
  }

  @keyframes miqaat-spin {
    to {
      transform: rotate(360deg);
    }
  }

  .stats-card {
    border-radius: 12px;
    padding: 18px 18px;
    margin-bottom: 16px;
    background: #ffffff;
    box-shadow: none;
    transition: background-color 0.2s ease;
    border: 1px solid #eee;
  }

  .stats-card:hover {
    background-color: #fafafa;
  }

  .stats-icon {
    font-size: 1.8rem;
    margin-bottom: 10px;
  }

  .stats-value {
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 2px;
    line-height: 1.1;
  }

  /* Emphasize corpus funds amounts */
  .corpus-summary .stats-value {
    font-size: 1.25rem !important;
    font-weight: 700;
  }

  /* Corpus: ensure no forced decimal suffix */
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

  /* Modal corpus amounts */
  #corpusFundsModal .modal-body strong {
    font-size: 1.15rem;
  }

  .corpus-summary .stats-value.amount-clickable {
    cursor: pointer;
  }

  .stats-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }

  .chart-container {
    background: white;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
  }

  /* Top accent stripe removed per request (was .chart-container::before) */

  /* Reduce bottom padding for the grouped outer container */
  .chart-container.grouped-block {
    padding-bottom: 0;
  }

  /* Trim inner spacing at the bottom of the grouped block */
  .chart-container.grouped-block .row.mb-4 {
    margin-bottom: 0 !important;
  }

  .chart-container.grouped-block .sector-block {
    padding-bottom: 6px;
  }

  /* Remove shadow specifically for the Sector-wise container */
  .sector-block {
    box-shadow: none !important;
  }

  /* Member Types container: flat look */
  .member-types-block {
    box-shadow: none !important;
  }

  .chart-title {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
  }

  .progress-container {
    background: white;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .progress-item {
    margin-bottom: 20px;
  }

  .progress-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-weight: 500;
  }

  .progress {
    height: 10px;
    border-radius: 10px;
    overflow: hidden;
  }

  .progress-bar {
    border-radius: 10px;
  }

  .member-table-container {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .mini-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    text-align: center;
    border: 1px solid #eee;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
  }

  .mini-card .stats-value {
    font-size: 1.25rem;
    margin-bottom: 2px;
    display: block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .mini-card .stats-label {
    font-size: 0.8rem;
    letter-spacing: 0.6px;
    color: #777;
    display: block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .list-unstyled li+li {
    margin-top: 10px;
  }

  .table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.95rem;
    color: #333;
    background-color: #f8f9fa;
  }

  .table td {
    font-size: 0.95rem;
  }

  .btn-action {
    margin: 2px;
    padding: 5px 10px;
    font-size: 0.8rem;
    border-radius: 5px;
  }

  /* Minimal color accents for stat cards */
  .stats-card.sabeel {
    border: 1px solid #667eea;
  }

  .stats-card.thaali {
    border: 1px solid #f5576c;
  }

  .stats-card.outstanding {
    border: 1px solid #fcb69f;
  }

  .stats-card.members {
    border: 1px solid #6ac1b8;
  }

  .stats-card.sabeel .stats-icon {
    color: #667eea;
  }

  .stats-card.thaali .stats-icon {
    color: #f5576c;
  }

  .stats-card.outstanding .stats-icon {
    color: #fcb69f;
  }

  .stats-card.members .stats-icon {
    color: #6ac1b8;
  }

  .chart-container canvas {
    max-height: 240px;
  }

  /* Calendar overview stat-cards (match Amilsaheb scheme) */
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

  .calendar-block .stat-card:hover {
    border-color: #dbe3ef;
  }

  /* Smaller FMB Calendar cards */
  .calendar-overview .stats-card {
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
  }

  .calendar-overview .stats-card .text-center h5 {
    font-size: 1.1rem;
    margin-bottom: 6px;
  }

  .calendar-overview .stats-card .stats-value {
    font-size: 1.4rem;
  }

  /* Compact variant for smaller chart blocks */
  .chart-container.compact {
    padding: 18px;
  }

  .chart-container.compact canvas {
    max-height: 180px;
  }

  .weekly-chart-container canvas {
    max-height: 160px;
  }

  .sub-chart-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #555;
    margin-bottom: 6px;
  }

  /* Hijri year label in takhmeen summary cards */
  .hijri-year {
    font-size: 1rem;
  }

  /* Emphasize takhmeen summary numerals */
  .takhmeen-summary .overview-value {
    font-size: 1.35rem;
  }

  /* Weekly signup: larger counts */
  .weekly-summary .mini-card .stats-value {
    font-size: 1.4rem;
    font-weight: 700;
  }

  .weekly-summary .mini-card .stats-label {
    font-size: 0.95rem;
  }

  /* Right-aligned current year inside chart containers */
  .container-year {
    position: absolute;
    right: 18px;
    top: 18px;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.95rem;
  }

  /* FMB sector cards */
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

  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
  }

  /* Subtle colorful underline for section titles */

  .sector-card .name {
    font-size: 1.15rem;
    font-weight: 600;
    color: #333;
    margin: 0;
  }

  .sector-card .num {
    font-weight: 800;
    font-size: 1.75rem;
    color: #111;
    margin-left: 12px;
  }

  .icon {
    font-size: 1.8rem;
    margin: 10px 0;
    color: #ffffff;
  }

  .title {
    color: white;
  }

  .heading {
    color: #ad7e05;
    font-family: 'Amita', cursive;
  }

  .card {
    height: 153px;
  }

  .card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
  }

  .row a {
    text-decoration: none;
    color: inherit;
  }

  /* Jamaat Overview mini-cards */
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

  @media (max-width: 767.98px) {
    .overview-card {
      padding: 10px 12px;
    }

    .overview-icon {
      width: 32px;
      height: 32px;
    }

    .overview-icon i {
      font-size: 1rem;
    }

    .overview-title {
      font-size: .8rem;
    }

    .overview-value {
      font-size: 1.05rem;
    }
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

  .sidebar-menu .menu-section {
    font-size: .85rem;
    color: #777;
    margin: 16px 8px 6px;
    text-transform: uppercase;
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
    .chart-container {
      padding: 20px;
      margin-bottom: 16px;
    }

    .chart-container canvas {
      max-height: 180px;
    }

    .chart-container.compact canvas {
      max-height: 140px;
    }

    .weekly-chart-container canvas {
      max-height: 140px;
    }

    .dashboard-title {
      font-size: 1.6rem;
    }

    .dashboard-subtitle {
      font-size: 0.95rem;
    }

    .stats-icon {
      font-size: 1.6rem;
    }

    .stats-value {
      font-size: 1.1rem;
    }

    .stats-label {
      font-size: 0.85rem;
    }

    .chart-title {
      font-size: 1rem;
    }

    .section-title {
      font-size: 1.1rem;
    }

    .table th,
    .table td {
      font-size: 0.9rem;
    }

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

    /* Centered modal-style on mobile when open */
    .sidebar-menu.open {
      top: 50%;
      left: 50%;
      height: auto;
      max-height: 90vh;
      width: 90%;
      max-width: 360px;
      border-radius: 14px;
      transform: translate(-50%, -50%);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      background: #fff;
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

    .sidebar-close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 32px;
      height: 32px;
      border: none;
      border-radius: 8px;
      background: #eef2ff;
      color: #4f46e5;
      font-size: 20px;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
  }
</style>

<style>
  /* Mobile-only: equalize miqaat mini-card heights (avoid desktop grid conflicts) */
  @media (max-width:767.98px) {
    #miqaat-rsvp-block .row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    /* Ensure each column stretches to match siblings on small screens */
    #miqaat-rsvp-block .row>[class*="col-"] {
      display: flex;
      align-items: stretch;
    }

    /* Make anchors and cards fill available height so .mini-card can stretch */
    #miqaat-rsvp-block .row>[class*="col-"] a,
    #miqaat-rsvp-block .row>[class*="col-"]>a>.mini-card {
      display: flex;
      flex: 1 1 auto;
      align-items: stretch;
    }

    #miqaat-rsvp-block .mini-card {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      min-height: 120px;
    }
  }
</style>

<div class="container-fluid margintopcontainer mt-5 pt-5">
  <!-- Dashboard Header -->
  <div class="dashboard-header text-center">
    <h1 class="dashboard-title">Jamaat Dashboard</h1>
    <p class="dashboard-subtitle">
      <?php
      // Get current Hijri year
      $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446H';
      echo $hijri_year . 'H / ' . date('Y');
      ?>
    </p>
  </div>

  <div class="row">
    <!-- Left Sidebar Menu -->
    <div class="col-md-4 col-lg-3 mb-4 col-sidebar">
      <div class="sidebar-menu">
        <button id="sidebarCloseBtn" class="sidebar-close-btn" aria-label="Close menu">&times;</button>
        <div class="menu-title">Quick Menu</div>
        <div class="menu-section">Raza</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=1') ?>"><span class="menu-icon"><i class="fa-solid fa-hands-holding"></i></span><span class="menu-label">Miqaat Raza Request</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/EventRazaRequest?event_type=2') ?>"><span class="menu-icon"><i class="fa-solid fa-hands-holding"></i></span><span class="menu-label">Kaaraj Raza Request</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/UmoorRazaRequest') ?>"><span class="menu-icon"><i class="fa-solid fa-hands-holding"></i></span><span class="menu-label">12 Umoor Raza Request</span></a></li>
        </ul>
        <div class="menu-section">Activity</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('anjuman/asharaohbat') ?>"><span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span><span class="menu-label">Ashara Ohbat <?php $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446H';
                                                                                                                                                                                                      echo $hijri_year . 'H'; ?></span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/ashara_attendance') ?>"><span class="menu-icon"><i class="fa-solid fa-user-check"></i></span><span class="menu-label">Ashara Attendance</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/fmbcalendar?from=anjuman'); ?>"><span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span><span class="menu-label">FMB Calendar</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/thaali_signups_breakdown?from=anjuman'); ?>"><span class="menu-icon"><i class="fa-solid fa-chart-column"></i></span><span class="menu-label">FMB Thaali Signups</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/fmbthaalimenu?from=anjuman'); ?>"><span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span><span class="menu-label">Add FMB Menu</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/managemiqaat?from=common/managemiqaat'); ?>"><span class="menu-icon"><i class="fa-solid fa-calendar-days"></i></span><span class="menu-label">Create Miqaat</span></a></li>
        <li><a class="menu-item" href="<?php echo base_url('common/rsvp_list?from=anjuman'); ?>"><span class="menu-icon"><i class="fa fa-check-square-o"></i></span><span class="menu-label">RSVP Report</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('common/miqaatattendance?from=anjuman'); ?>"><span class="menu-icon"><i class="fa fa-users"></i></span><span class="menu-label">Miqaat Attendance Report</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('madresa'); ?>"><span class="menu-icon"><i class="fa-solid fa-school"></i></span><span class="menu-label">Madresa Module</span></a></li>
        </ul>
        <div class="menu-section">Finance</div>
        <ul class="menu-list">
          <li><a class="menu-item" href="<?php echo base_url('anjuman/fmbmodule') ?>"><span class="menu-icon"><i class="fa-solid fa-hand-holding-heart"></i></span><span class="menu-label">FMB Module</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/sabeeltakhmeendashboard') ?>"><span class="menu-icon"><i class="fa-solid fa-hand-holding-heart"></i></span><span class="menu-label">Sabeel Module</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/qardanhasana'); ?>"><span class="menu-icon"><i class="fa-solid fa-handshake"></i></span><span class="menu-label">Qardan Hasana</span></a></li>
          <li><a class="menu-item" href="<?= base_url('anjuman/corpusfunds_receive'); ?>"><span class="menu-icon"><i class="fa-solid fa-donate"></i></span><span class="menu-label">Corpus Funds</span></a></li>
            <li><a class="menu-item" href="<?= base_url('anjuman/ekramfunds_receive'); ?>"><span class="menu-icon"><i class="fa-solid fa-hand-holding-heart"></i></span><span class="menu-label">Ekram Fund Module</span></a></li>
          <li><a class="menu-item" href="<?= base_url('anjuman/financials'); ?>"><span class="menu-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span><span class="menu-label">Individual Financial Details</span></a></li>
          <li><a class="menu-item" href="<?= base_url('anjuman/expense'); ?>"><span class="menu-icon"><i class="fa-solid fa-receipt"></i></span><span class="menu-label">Expense Module</span></a></li>
          <li><a class="menu-item" href="<?php echo base_url('anjuman/wajebaat'); ?>"><span class="menu-icon"><i class="fa-solid fa-coins"></i></span><span class="menu-label">Wajebaat</span></a></li>
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

      <!-- Member Types (below Sector-wise Members) -->


      <!-- Grouped: Jamaat Overview + Sector-wise (with outer shadow container) -->
      <div class="chart-container grouped-block">
        <?php
        // Helper to format numbers in Indian currency style (2-3-2 grouping)
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
        ?>
        <h4 class="section-title text-center mt-4 mt-md-0">Jamaat Overview</h4>
        <div class="row">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('anjuman/mumineendirectory?filter=all'); ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter=hof_fm_type&value=HOF'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user"></i></div>
                <div class="overview-body">
                  <span class="overview-title">HOF (Head of Family)</span>
                  <span class="overview-value"><?= $stats['HOF'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('anjuman/mumineendirectory?filter=hof_fm_type&value=FM'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user-plus"></i></div>
                <div class="overview-body">
                  <span class="overview-title">FM (Family Members)</span>
                  <span class="overview-value"><?= $stats['FM'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('anjuman/mumineendirectory?filter=gender&value=male'); ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter=gender&value=female'); ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter=age_range&min=0&max=4'); ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter=age_range&min=5&max=15'); ?>" style="text-decoration:none;color:inherit;display:block;">
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
            <a href="<?= base_url('anjuman/mumineendirectory?filter=age_range&min=65'); ?>" style="text-decoration:none;color:inherit;display:block;">
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

        <!-- Sector-wise Member Count -->
        <div class="row">
          <div class="col-12" style="padding: 0;">
            <div class="chart-container sector-block">
              <h4 class="section-title text-center">Sector-wise Members</h4>
              <?php
              $sectorRows = isset($stats['Sectors']) ? $stats['Sectors'] : [];
              // Keep only assigned sectors (non-empty names)
              if (!empty($sectorRows)) {
                $sectorRows = array_values(array_filter($sectorRows, function ($row) {
                  $name = isset($row['Sector']) ? trim($row['Sector']) : '';
                  return $name !== '' && strtolower($name) !== 'unassigned';
                }));
              }
              // Sort by total members desc for better readability
              if (!empty($sectorRows)) {
                usort($sectorRows, function ($a, $b) {
                  return intval($b['total'] ?? 0) <=> intval($a['total'] ?? 0);
                });
              }
              ?>
              <?php if (!empty($sectorRows)) { ?>
                <div class="row">
                  <?php foreach ($sectorRows as $row): ?>
                    <div class="col-12 col-md-3 mb-3">
                      <a href="<?= base_url('anjuman/mumineendirectory?filter=sector&value=' . rawurlencode($row['Sector'] ?: '')); ?>" style="text-decoration:none;color:inherit;display:block;">
                        <div class="overview-card sector-card">
                          <div class="overview-icon"><i class="fa fa-map-marker"></i></div>
                          <div class="overview-body">
                            <span class="overview-title"><?= htmlspecialchars($row['Sector'] ?: 'Unassigned'); ?></span>
                            <?php
                            // Fetch HOF/FM directly from backend provided counts
                            $hof = intval($row['hof_count'] ?? $row['HOF'] ?? $row['hof'] ?? 0);
                            $fm = intval($row['fm_count'] ?? $row['FM'] ?? $row['fm'] ?? 0);
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

        <div class="row">
          <div class="col-12" style="padding: 0;">
            <div class="chart-container member-types-block">
              <h4 class="section-title text-center">Member Types</h4>
              <?php $mt = isset($member_type_counts) ? $member_type_counts : ['resident' => 0, 'external' => 0, 'moved_out' => 0, 'non_sabeel' => 0, 'temporary' => 0, 'total' => 0]; ?>
              <div class="row">
                <div class="col-12 col-md-4 col-lg-3 mb-3">
                  <a href="<?= base_url('anjuman/mumineendirectory?filter=member_type&value=' . rawurlencode('Resident Mumineen')); ?>" style="text-decoration:none;color:inherit;display:block;">
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
                  <a href="<?= base_url('anjuman/mumineendirectory?filter=member_type&value=' . rawurlencode('External Sabeel Payers')); ?>" style="text-decoration:none;color:inherit;display:block;">
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
                  <a href="<?= base_url('anjuman/mumineendirectory?filter=member_type&value=' . rawurlencode('Moved-Out Mumineen')); ?>" style="text-decoration:none;color:inherit;display:block;">
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
                  <a href="<?= base_url('anjuman/mumineendirectory?filter=member_type&value=' . rawurlencode('Non-Sabeel Residents')); ?>" style="text-decoration:none;color:inherit;display:block;">
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
                  <a href="<?= base_url('anjuman/mumineendirectory?filter=member_type&value=' . rawurlencode('Temporary Mumineen/Visitors')); ?>" style="text-decoration:none;color:inherit;display:block;">
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
          </div>
        </div>
        <!-- Marital Status Distribution -->
        <div class="row">
          <div class="col-12" style="padding: 0;">
            <div class="chart-container member-types-block compact">
              <h4 class="section-title text-center">Marital Status</h4>
              <div class="row">
                <?php $ms = isset($marital_status_counts) ? $marital_status_counts : []; ?>
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
                      <div class="overview-card">
                        <div class="overview-icon" style="background:<?php echo $iconBg; ?>; color:<?php echo $iconColor; ?>;"><i class="<?php echo $iconClass; ?>"></i></div>
                        <div class="overview-body">
                          <span class="overview-title"><?php echo $safeLabel; ?></span>
                          <span class="overview-value"><?php echo (int)$count; ?></span>
                        </div>
                      </div>
                    </div>
                <?php }
                } ?>
              </div>
            </div>
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
        <div class="chart-container compact weekly-summary">
          <div class="d-flex align-items-center justify-content-between" style="gap:12px;">
            <h4 class="section-title text-center m-0" style="flex:1;">Thaali Signup for Current Month</h4>
          </div>
          <div style="min-width:140px;text-align:right;">
            <!-- View details button (computes dates after hijri vars are set below) -->
            <a id="thaali-details-btn" href="#" class="btn btn-sm btn-primary text-white my-3 my-md-0" style="white-space:nowrap;">View details</a>
          </div>
          <?php
          // Show current Hijri month using a chevron-style prev/next switcher
          $this->load->model('HijriCalendar');
          // If controller provided selected parts (via GET params), use that for rendering
          if (isset($selected_hijri_parts) && is_array($selected_hijri_parts) && !empty($selected_hijri_parts)) {
            $hijri_today = $selected_hijri_parts;
          } else {
            $hijri_today = $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
          }
          $current_hijri_year = isset($hijri_today['hijri_year']) ? $hijri_today['hijri_year'] : null;
          $current_hijri_month_id = isset($hijri_today['hijri_month']) ? (int)$hijri_today['hijri_month'] : null;
          $current_hijri_month_name = isset($hijri_today['hijri_month_name']) ? $hijri_today['hijri_month_name'] : '';
          $years = $this->HijriCalendar->get_distinct_hijri_years();

          // Build a flat ordered list of year+month entries so we can compute prev/next across year boundaries
          $monthList = [];
          foreach ($years as $y) {
            $ms = $this->HijriCalendar->get_hijri_months_for_year($y);
            if (!is_array($ms)) continue;
            foreach ($ms as $m) {
              $monthList[] = [
                'year' => $y,
                'id'   => (int)$m['id'],
                'name' => isset($m['name']) ? $m['name'] : '',
              ];
            }
          }

          $currentIndex = null;
          foreach ($monthList as $i => $mn) {
            if ($mn['year'] == $current_hijri_year && (int)$mn['id'] === (int)$current_hijri_month_id) {
              $currentIndex = $i;
              break;
            }
          }

          $prev = null;
          $next = null;
          if ($currentIndex !== null) {
            if ($currentIndex > 0) $prev = $monthList[$currentIndex - 1];
            if ($currentIndex < count($monthList) - 1) $next = $monthList[$currentIndex + 1];
          }

          // Helper to build URL with hijri params (preserves path, replaces hijri params)
          $basePath = current_url();
          function hijri_url($basePath, $y, $m)
          {
            return $basePath . '?hijri_year=' . rawurlencode($y) . '&hijri_month=' . rawurlencode($m);
          }

          // Now that we have $current_hijri_month_id and $current_hijri_year, compute Gregorian range
          $hijri_days_for_button = $this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month_id, $current_hijri_year);
          if (!empty($hijri_days_for_button) && is_array($hijri_days_for_button)) {
            $button_start = isset($hijri_days_for_button[0]['greg_date']) ? $hijri_days_for_button[0]['greg_date'] : date('Y-m-01');
            $button_end = isset($hijri_days_for_button[count($hijri_days_for_button) - 1]['greg_date']) ? $hijri_days_for_button[count($hijri_days_for_button) - 1]['greg_date'] : date('Y-m-t');
          } else {
            $button_start = date('Y-m-01');
            $button_end = date('Y-m-t');
          }
          ?>
          <script>
            (function() {
              var btn = document.getElementById('thaali-details-btn');
              if (btn) {
                btn.setAttribute('href', '<?= base_url('common/thaali_signups_breakdown?from=anjuman&start_date=' . rawurlencode($button_start) . '&end_date=' . rawurlencode($button_end)); ?>');
              }
            })();
          </script>
          <?php
          ?>

          <div class="d-flex justify-content-center align-items-center hijri-switcher" style="margin-top:-6px;margin-bottom:14px;">
            <a href="#" data-hijri-year="<?= $prev ? htmlspecialchars($prev['year']) : '' ?>" data-hijri-month="<?= $prev ? htmlspecialchars($prev['id']) : '' ?>" class="hijri-nav-btn <?= $prev ? '' : 'disabled' ?>" aria-label="Previous Hijri month">
              <div class="chev-box"><i class="fa fa-chevron-left"></i></div>
            </a>

            <div class="hijri-title text-center" id="hijri-current-title" style="margin:0 18px;color:#0ea5a4;font-weight:600;font-size:1.15rem;">
              <?= htmlspecialchars($current_hijri_month_name ? $current_hijri_month_name : '—') . ' ' . (isset($current_hijri_year) ? htmlspecialchars($current_hijri_year) : ''); ?>
            </div>

            <a href="#" data-hijri-year="<?= $next ? htmlspecialchars($next['year']) : '' ?>" data-hijri-month="<?= $next ? htmlspecialchars($next['id']) : '' ?>" class="hijri-nav-btn <?= $next ? '' : 'disabled' ?>" aria-label="Next Hijri month">
              <div class="chev-box"><i class="fa fa-chevron-right"></i></div>
            </a>
          </div>

          <style>
            .hijri-switcher .chev-box {
              width: 44px;
              height: 44px;
              border: 1px solid #cbd5e1;
              border-radius: 6px;
              display: flex;
              align-items: center;
              justify-content: center;
              background: #fff;
              color: #374151;
              font-size: 1.15rem;
            }

            .hijri-switcher .chev-box i {
              margin: 0;
            }

            .hijri-switcher .hijri-nav-btn.disabled {
              pointer-events: none;
              opacity: .45;
            }

            @media (max-width:576px) {
              .hijri-switcher .chev-box {
                width: 38px;
                height: 38px;
                font-size: 1rem;
              }

              .hijri-switcher .hijri-title {
                font-size: 1rem;
              }
            }
          </style>
          <div id="thaali-month-block" class="row">
            <div class="col-12">
              <?php
              // Compute weekly totals and overall avg/day (no meter)
              $sumWeekTotal = 0;
              foreach ($displayWeekly as $rw) {
                $sumWeekTotal += (int)($rw['total'] ?? 0);
              }
              // Use 6-day divisor for average (business rule)
              $availableDays = isset($sw['days']) ? (int)$sw['days'] : 7;
              // Default divisor is 6. If the actual available days is less than 6 (edge case), use that instead.
              $divisor = 6;
              if ($availableDays > 0 && $availableDays < 6) {
                $divisor = $availableDays;
              }
              // Enforce integer-only average (no decimal points)
              $overallAvgPerDay = $divisor > 0 ? (int)round($sumWeekTotal / $divisor) : 0;
              // Families signed up at least once this week = total HOF - families with no thaali
              $noThaali = isset($dashboard_data['no_thaali_families']) ? $dashboard_data['no_thaali_families'] : [];
              $hofTotal = isset($stats['HOF']) ? (int)$stats['HOF'] : 0;
              $familiesSignedUp = max(0, $hofTotal - (int)count($noThaali));
              ?>
              <?php
              // Monthly quick summary: show families signed up this Hijri month and families with no signups this Hijri month.
              // month_start/month_end are computed earlier for the details button; fall back to gregorian month if missing
              if (!isset($month_start) || !isset($month_end)) {
                $hijri_days = $this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month_id, $current_hijri_year);
                if (!empty($hijri_days) && is_array($hijri_days)) {
                  $month_start = isset($hijri_days[0]['greg_date']) ? $hijri_days[0]['greg_date'] : date('Y-m-01');
                  $month_end = isset($hijri_days[count($hijri_days) - 1]['greg_date']) ? $hijri_days[count($hijri_days) - 1]['greg_date'] : date('Y-m-t');
                } else {
                  $month_start = date('Y-m-01');
                  $month_end = date('Y-m-t');
                }
              }
              // Prefer controller-provided month-level metrics if available
              if (isset($dashboard_data['this_month_families_signed_up']) || isset($dashboard_data['no_thaali_families_month'])) {
                $familiesSignedUpMonth = isset($dashboard_data['this_month_families_signed_up']) ? (int)$dashboard_data['this_month_families_signed_up'] : (isset($dashboard_data['this_month_signed_up']) ? (int)$dashboard_data['this_month_signed_up'] : (int)$familiesSignedUp);
                $noThaaliMonthList = isset($dashboard_data['no_thaali_families_month']) ? $dashboard_data['no_thaali_families_month'] : (isset($dashboard_data['no_thaali_families']) ? $dashboard_data['no_thaali_families'] : []);
              } else {
                // Fallback: compute using CommonM helper which maps Hijri month -> Gregorian days and queries signups
                $this->load->model('CommonM');
                $mstats = $this->CommonM->get_monthly_thaali_stats($current_hijri_month_id, $current_hijri_year);
                $familiesSignedUpMonth = isset($mstats['families_signed_up']) ? (int)$mstats['families_signed_up'] : (int)$familiesSignedUp;
                $noThaaliMonthList = isset($mstats['no_thaali_list']) ? $mstats['no_thaali_list'] : (isset($dashboard_data['no_thaali_families']) ? $dashboard_data['no_thaali_families'] : []);
              }
              $noThaaliMonthCount = is_array($noThaaliMonthList) ? count($noThaaliMonthList) : (int)$noThaaliMonthList;
              ?>
              <div class="row text-center mb-2">
                <div class="col-12 col-md-6 mb-2">
                  <a href="#" class="open-hof-modal" data-modal-type="signed" style="text-decoration:none;color:inherit;display:block;"
                    data-hijri-year="<?= htmlspecialchars($current_hijri_year); ?>" data-hijri-month="<?= htmlspecialchars($current_hijri_month_id); ?>"
                    data-start-date="<?= htmlspecialchars($month_start); ?>" data-end-date="<?= htmlspecialchars($month_end); ?>">
                    <div class="mini-card">
                      <div class="stats-value"><?= (int)$familiesSignedUpMonth; ?></div>
                      <div class="stats-label">Sign up this month</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-6 mb-2">
                  <a href="#" class="open-hof-modal" data-modal-type="no" style="text-decoration:none;color:inherit;display:block;"
                    data-hijri-year="<?= htmlspecialchars($current_hijri_year); ?>" data-hijri-month="<?= htmlspecialchars($current_hijri_month_id); ?>"
                    data-start-date="<?= htmlspecialchars($month_start); ?>" data-end-date="<?= htmlspecialchars($month_end); ?>">
                    <div class="mini-card">
                      <div class="stats-value"><?= (int)$noThaaliMonthCount; ?></div>
                      <div class="stats-label">No sign up this month</div>
                    </div>
                  </a>
                </div>
              </div>

              <!-- HOF Lists Modal -->
              <div class="modal fade" id="hofListModal" tabindex="-1" role="dialog" aria-labelledby="hofListLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header py-2">
                      <h6 class="modal-title" id="hofListLabel">HOFs</h6>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div id="miqaatPopupMeta" style="margin-bottom:12px; display:none;">
                        <!-- Filled dynamically: miqaat name/date and counts -->
                        <div class="text-muted">Loading miqaat information...</div>
                      </div>
                      <div id="hofListLoading" class="text-center py-3" style="display:none;">
                        <i class="fa fa-spinner fa-spin"></i> Loading...
                      </div>
                      <div id="hofListContainer" style="max-height:60vh; overflow:auto;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <script>
            (function() {
              // Normalize phone to E.164-like form (default country +91)
              function _normalizePhone(raw) {
                var DEFAULT_CC = '91';
                if (!raw) return '';
                var s = String(raw).trim();
                s = s.replace(/[\s\-\.\(\)]/g, '');
                if (s.charAt(0) === '+') {
                  var d = s.slice(1).replace(/\D/g, '');
                  return '+' + d;
                }
                if (s.indexOf('00') === 0) {
                  var d2 = s.slice(2).replace(/\D/g, '');
                  return '+' + d2;
                }
                var digits = s.replace(/\D/g, '');
                if (!digits) return '';
                if (digits.length === 10) return '+' + DEFAULT_CC + digits;
                if (digits.length === 11 && digits.charAt(0) === '0') return '+' + DEFAULT_CC + digits.slice(1);
                if (digits.length >= 11 && digits.length <= 15) return '+' + digits;
                return digits;
              }

              // Build simple list HTML
              function renderHofList(title, rows) {
                var html = '';
                html += '<div class="d-flex justify-content-between align-items-center mb-2">';
                html += '<strong>' + title + '</strong>';
                html += '<span class="text-muted">Count: ' + (rows ? rows.length : 0) + '</span>';
                html += '</div>';
                if (!rows || rows.length === 0) {
                  html += '<div class="text-muted">No records found.</div>';
                  return html;
                }
                // Filter out rows that have both Sector and Sub_Sector empty
                try {
                  rows = rows.filter(function(r) {
                    var sector = (r && (r.Sector || r.sector) ? (r.Sector || r.sector) : '') + '';
                    var sub = (r && (r.Sub_Sector || r.sub_sector || r.SubSector) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '') + '';
                    sector = sector.trim();
                    sub = sub.trim();
                    return !(sector === '' && sub === '');
                  });
                } catch (e) {
                  console.warn('HOF filter failed', e);
                }
                // sort rows by Sector, then Sub Sector, then Name (case-insensitive)
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
                  console.warn('HOF list sort failed', e);
                }
                html += '<table class="table table-sm table-striped"><thead><tr><th>HOF ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
                rows.forEach(function(r) {
                  var id = (r && (r.ITS_ID || r.hof_id || r.ITS)) ? (r.ITS_ID || r.hof_id || r.ITS) : '';
                  var name = (r && (r.Full_Name || r.name)) ? (r.Full_Name || r.name) : '';
                  var sector = (r && (r.Sector || r.sector)) ? (r.Sector || r.sector) : '';
                  var subSector = (r && (r.Sub_Sector || r.sub_sector || r.SubSector)) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '';
                  var mobile = (r && (r.RFM_Mobile || r.rfm_mobile || r.Mobile || r.mobile)) ? (r.RFM_Mobile || r.rfm_mobile || r.Mobile || r.mobile) : '';
                  var tel = mobile ? _normalizePhone(mobile) : '';
                  html += '<tr><td>' + id + '</td><td>' + name + '</td><td>' + sector + '</td><td>' + subSector + '</td><td>' + (mobile ? (tel ? ('<a href="tel:' + tel + '" style="text-decoration:none; color: blue;">' + mobile + '</a>') : mobile) : '') + '</td></tr>';
                });
                html += '</tbody></table>';
                return html;
              }

              // Open modal and fetch lists for selected Hijri month
              $(document).on('click', '.open-hof-modal', function(e) {
                e.preventDefault();
                var $a = $(this);
                var type = $a.data('modal-type'); // 'signed' or 'no'
                var hijriYear = $a.data('hijri-year');
                var hijriMonth = $a.data('hijri-month');
                $('#hofListLabel').text(type === 'signed' ? 'HOFs Signed Up This Month' : 'HOFs With No Signup This Month');
                $('#hofListContainer').html('');
                $('#hofListLoading').show();
                // Hide miqaat-specific meta when using this modal for HOF lists
                try {
                  $('#miqaatPopupMeta').hide();
                } catch (e) {}
                $('#hofListModal').modal('show');
                // Use a lightweight endpoint via the same controller: return JSON of lists
                var url = buildUrlWithParams(window.location.pathname, hijriYear, hijriMonth);
                // Ensure JSON: add &format=json
                try {
                  var u = new URL(url);
                  u.searchParams.set('format', 'json');
                  url = u.toString();
                } catch (e) {
                  url += '&format=json';
                }
                fetch(url, {
                  credentials: 'same-origin'
                }).then(function(resp) {
                  return resp.json();
                }).then(function(data) {
                  // Expect data.monthly_stats: { signed_hof_list: [], no_thaali_list: [] }
                  var rows = [];
                  if (data && data.monthly_stats) {
                    rows = (type === 'signed') ? (data.monthly_stats.signed_hof_list || []) : (data.monthly_stats.no_thaali_list || []);
                  }
                  $('#hofListLoading').hide();
                  $('#hofListContainer').html(renderHofList("", rows));
                }).catch(function(err) {
                  console.error('Failed to load HOF list', err);
                  $('#hofListLoading').hide();
                  $('#hofListContainer').html('<div class="text-danger">Failed to load data.</div>');
                });
              });

              function buildUrlWithParams(base, y, m) {
                try {
                  var u = new URL(base, window.location.origin);
                  u.searchParams.set('hijri_year', y);
                  u.searchParams.set('hijri_month', m);
                  u.searchParams.set('ajax', '1');
                  return u.toString();
                } catch (e) {
                  return base + '?hijri_year=' + encodeURIComponent(y) + '&hijri_month=' + encodeURIComponent(m) + '&ajax=1';
                }
              }

              function ajaxLoadMonth(year, month, pushState) {
                if (!year || !month) return;
                var base = window.location.pathname;
                var url = buildUrlWithParams(base, year, month);
                document.querySelectorAll('.hijri-switcher .hijri-nav-btn .chev-box').forEach(function(b) {
                  b.dataset.orig = b.innerHTML;
                  b.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                });
                fetch(url, {
                  credentials: 'same-origin'
                }).then(function(resp) {
                  return resp.text();
                }).then(function(htmlText) {
                  var parser = new DOMParser();
                  var doc = parser.parseFromString(htmlText, 'text/html');
                  var newBlock = doc.querySelector('#thaali-month-block');
                  var newTitle = doc.querySelector('#hijri-current-title');
                  if (newBlock) {
                    var cur = document.querySelector('#thaali-month-block');
                    cur.parentNode.replaceChild(newBlock, cur);
                  }
                  if (newTitle) {
                    var curTitle = document.getElementById('hijri-current-title');
                    if (curTitle) curTitle.innerHTML = newTitle.innerHTML;
                  }
                  // Locate nav buttons by index (first = prev, second = next).
                  var navBtns = doc.querySelectorAll('.hijri-switcher .hijri-nav-btn');
                  var newPrev = (navBtns && navBtns.length > 0) ? navBtns[0] : null;
                  var newNext = (navBtns && navBtns.length > 1) ? navBtns[1] : null;
                  var curNavBtns = document.querySelectorAll('.hijri-switcher .hijri-nav-btn');
                  var curPrev = (curNavBtns && curNavBtns.length > 0) ? curNavBtns[0] : null;
                  var curNext = (curNavBtns && curNavBtns.length > 1) ? curNavBtns[1] : null;
                  if (newPrev && curPrev) {
                    curPrev.dataset.hijriYear = newPrev.dataset.hijriYear || '';
                    curPrev.dataset.hijriMonth = newPrev.dataset.hijriMonth || '';
                    curPrev.classList.toggle('disabled', newPrev.classList.contains('disabled'));
                  }
                  if (newNext && curNext) {
                    curNext.dataset.hijriYear = newNext.dataset.hijriYear || '';
                    curNext.dataset.hijriMonth = newNext.dataset.hijriMonth || '';
                    curNext.classList.toggle('disabled', newNext.classList.contains('disabled'));
                  }
                  if (pushState) {
                    var newUrl = window.location.pathname + '?hijri_year=' + encodeURIComponent(year) + '&hijri_month=' + encodeURIComponent(month);
                    history.pushState({
                      hijri_year: year,
                      hijri_month: month
                    }, '', newUrl);
                  }
                }).catch(function(err) {
                  console.error('Failed to load month data', err);
                }).finally(function() {
                  document.querySelectorAll('.hijri-switcher .hijri-nav-btn .chev-box').forEach(function(b) {
                    if (b.dataset.orig) {
                      b.innerHTML = b.dataset.orig;
                      delete b.dataset.orig;
                    }
                  });
                });
              }

              document.querySelectorAll('.hijri-switcher .hijri-nav-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                  e.preventDefault();
                  if (btn.classList.contains('disabled')) return;
                  var y = btn.getAttribute('data-hijri-year');
                  var m = btn.getAttribute('data-hijri-month');
                  ajaxLoadMonth(y, m, true);
                });
              });

              window.addEventListener('popstate', function(ev) {
                if (ev.state && ev.state.hijri_year && ev.state.hijri_month) {
                  ajaxLoadMonth(ev.state.hijri_year, ev.state.hijri_month, false);
                }
              });
            })();
          </script>
        </div>

        <?php
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
        $rsvp_count = isset($miq_rsvp['rsvp_count']) ? (int)$miq_rsvp['rsvp_count'] : 0;
        $not_count = isset($miq_rsvp['not_rsvp_count']) ? (int)$miq_rsvp['not_rsvp_count'] : 0;
        ?>

        <div class="chart-container compact" id="miqaat-rsvp-block" data-initial-index="<?= $initial_index; ?>">
          <div class="d-flex align-items-center justify-content-between" style="gap:12px;">
            <h4 class="section-title text-center m-0" style="flex:1;">RSVP for Next Miqaat</h4>
          </div>
          <div style="min-width:140px;text-align:right;">
            <!-- View details button (computes dates after hijri vars are set below) -->
            <a href="#" class="btn btn-sm btn-primary text-white my-2" id="miqaat-view-details">View details</a>
          </div>


          <div class="d-flex align-items-center justify-content-center mt-3 mb-3">
            <a href="#" class="miqaat-nav-btn prev" aria-label="Previous miqaat" style="display:inline-flex;align-items:center;justify-content:center;height:40px;width:40px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,0.04);color:#374151;text-decoration:none;margin-right:12px;">
              <span style="font-size:18px;line-height:1">&#x2039;</span>
            </a>
            <span id="miqaat-current-title" class="mx-3" style="font-weight:600;color:#0ea5a5;font-size:20px;"></span>
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
                      <div class="stats-value" id="willAttendCount"><?php
                                                                    $combined_total = isset($miq_rsvp['combined_summary']['total']) ? (int)$miq_rsvp['combined_summary']['total'] : (isset($miq_rsvp['rsvp_users_count']) ? (int)$miq_rsvp['rsvp_users_count'] : 0);
                                                                    echo $combined_total;
                                                                    ?></div>
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
              <!-- Guest breakdown: Gents / Ladies / Children / Guests -->
              <div class="row text-center mb-3" id="miqaatGuestBreakdown">
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestGentsCard" class="open-miqaat-modal" data-type="gents" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Gents</div>
                      <div class="stats-value" id="guestGentsCount"><?= isset($miq_rsvp['combined_summary']['gents']) ? (int)$miq_rsvp['combined_summary']['gents'] : (isset($miq_rsvp['guest_summary']['gents']) ? (int)$miq_rsvp['guest_summary']['gents'] : 0); ?></div>
                      <div class="small text-muted" id="guestGentsBreakdown">
                        Members: <?= isset($miq_rsvp['member_summary']['gents']) ? (int)$miq_rsvp['member_summary']['gents'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['gents']) ? (int)$miq_rsvp['guest_summary']['gents'] : 0; ?>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestLadiesCard" class="open-miqaat-modal" data-type="ladies" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Ladies</div>
                      <div class="stats-value" id="guestLadiesCount"><?= isset($miq_rsvp['combined_summary']['ladies']) ? (int)$miq_rsvp['combined_summary']['ladies'] : (isset($miq_rsvp['guest_summary']['ladies']) ? (int)$miq_rsvp['guest_summary']['ladies'] : 0); ?></div>
                      <div class="small text-muted" id="guestLadiesBreakdown">
                        Members: <?= isset($miq_rsvp['member_summary']['ladies']) ? (int)$miq_rsvp['member_summary']['ladies'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['ladies']) ? (int)$miq_rsvp['guest_summary']['ladies'] : 0; ?>
                      </div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4 mb-2 mb-md-0">
                  <a href="#" id="miqaatGuestChildrenCard" class="open-miqaat-modal" data-type="children" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card bg-white">
                      <div class="small text-muted">Children</div>
                      <div class="stats-value" id="guestChildrenCount"><?= isset($miq_rsvp['combined_summary']['children']) ? (int)$miq_rsvp['combined_summary']['children'] : (isset($miq_rsvp['guest_summary']['children']) ? (int)$miq_rsvp['guest_summary']['children'] : 0); ?></div>
                      <div class="small text-muted" id="guestChildrenBreakdown">
                        Members: <?= isset($miq_rsvp['member_summary']['children']) ? (int)$miq_rsvp['member_summary']['children'] : 0; ?> | Guests: <?= isset($miq_rsvp['guest_summary']['children']) ? (int)$miq_rsvp['guest_summary']['children'] : 0; ?>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <style>
            @media (max-width:767.98px) {

              /* Left/Right split for miqaat cards */
              #miqaat-mobile-wrapper {
                display: flex;
                gap: 8px;
                align-items: flex-start;
              }

              #miqaat-mobile-left,
              #miqaat-mobile-right {
                width: 50%;
              }

              /* Make internal rows stack cleanly and ensure their children stretch */
              #miqaat-mobile-left .row,
              #miqaat-mobile-right .row {
                margin-bottom: 0;
                display: flex;
                flex-direction: column;
                gap: 8px;
              }

              /* Ensure each column cell becomes a flex container so cards can stretch */
              #miqaat-mobile-left .row>[class*="col-"],
              #miqaat-mobile-right .row>[class*="col-"] {
                display: flex;
                align-items: stretch;
              }

              /* Make the anchor fill the column so .mini-card can stretch uniformly */
              #miqaat-mobile-left .row>[class*="col-"]>a,
              #miqaat-mobile-right .row>[class*="col-"]>a {
                display: flex;
                flex: 1;
                align-items: stretch;
                width: 100%;
              }

              /* Make the mini-card fill available height and distribute content */
              #miqaat-mobile-wrapper .mini-card {
                display: flex;
                flex-direction: column;
                justify-content: center;
                /* center content vertically for uniform look */
                align-items: center;
                /* center text horizontally */
                text-align: center;
                flex: 1 1 auto;
                height: 150px;
                padding: 18px 16px;
                box-sizing: border-box;
                overflow: visible;
              }

              /* Slight spacing adjustment for value text; prevent clipping */
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

              /* Allow labels to wrap to next line instead of being truncated */
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

              /* Support for demographic small text lines */
              #miqaat-mobile-wrapper .mini-card .small.text-muted {
                display: block;
                color: #6b7280;
                font-size: 0.85rem;
                margin-top: 6px;
              }
            }
          </style>

          <!-- inline message for miqaat actions (hidden by default) -->
          <div id="miqaatMessage" class="miqaat-message" style="display:none; margin-top:10px; text-align:center; color:#333; font-size:0.95rem;">&nbsp;</div>

          <!-- loading overlay (hidden by default) -->
          <div id="miqaatLoading" class="miqaat-loading-overlay" style="display:none;">
            <div class="miqaat-spinner" aria-hidden="true"></div>
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
                // Prefer hijri_label if provided by controller; otherwise show greg date
                var dateLabel = mi.hijri_label || mi.date || '';
                if (titleEl) {
                  // safely escape text to avoid injecting HTML
                  function _escapeHtml(s) {
                    return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                  }
                  var nameHtml = '<div class="miqaat-name">' + _escapeHtml(miqName) + '</div>';
                  var dateHtml = dateLabel ? ('<div class="miqaat-date">' + _escapeHtml(dateLabel) + '</div>') : '';
                  titleEl.innerHTML = nameHtml + dateHtml;
                }
                if (rsvpCard) rsvpCard.setAttribute('data-miqaat-id', miqId);
                if (notCard) notCard.setAttribute('data-miqaat-id', miqId);
                if (viewDetails) viewDetails.href = '<?= base_url("common/rsvp_list?from=amilsaheb"); ?>' + '?miqaat_id=' + encodeURIComponent(miqId);

                var url = window.location.pathname;
                try {
                  var u = new URL(url, window.location.origin);
                  u.searchParams.set('format', 'json');
                  u.searchParams.set('miqaat_rsvp', '1');
                  u.searchParams.set('miqaat_id', miqId);
                  url = u.toString();
                } catch (err) {
                  url += '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(miqId);
                }
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
                  // legacy counts (HOF-level) kept for compatibility
                  if (typeof window !== 'undefined') {
                    try {
                      var waEl = document.getElementById('willAttendCount');
                      var wnaEl = document.getElementById('willNotAttendCount');
                      var nsEl = document.getElementById('rsvpNotSubmittedCount');
                      // Initialize with zeros while user-level counts are fetched
                      if (waEl) waEl.textContent = '0';
                      if (wnaEl) wnaEl.textContent = '0';
                      if (nsEl) nsEl.textContent = '0';
                    } catch (e) {}
                  }
                  // Now fetch per-user classification counts from common endpoint
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
                        // Prefer payload member/guest breakdown if available
                        var memberTotal = (m && m.member_summary && m.member_summary.total) ? m.member_summary.total : 0;
                        var guestTotal = (m && m.guest_summary && m.guest_summary.total) ? m.guest_summary.total : 0;
                        var combined = (memberTotal + guestTotal) || wa;
                        if (waEl2) waEl2.textContent = combined;
                        // show guest subtext
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
                  // we're at the first item; try to fetch the previous miqaat from server
                  var first = (upcoming && upcoming.length) ? upcoming[0] : null;
                  var beforeDate = first ? (first.date || '') : '';
                  if (!beforeDate) return; // nothing to do
                  var url = window.location.pathname;
                  try {
                    var u = new URL(url, window.location.origin);
                    u.searchParams.set('format', 'json');
                    u.searchParams.set('miqaat_prev', '1');
                    u.searchParams.set('before_date', beforeDate);
                    url = u.toString();
                  } catch (err) {
                    url += '?format=json&miqaat_prev=1&before_date=' + encodeURIComponent(beforeDate);
                  }
                  // show loading overlay and disable prev button while loading
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
                      // no previous miqaat found — show a short message to the user
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
                    // prepend to upcoming list and render it
                    upcoming.unshift(data.miqaat);
                    // stay at the newly-prepended item (index 0)
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

              // Click handler for opening miqaat RSVP modal
              document.addEventListener('click', function(e) {
                var a = e.target.closest && e.target.closest('.open-miqaat-modal');
                if (!a) return;
                e.preventDefault();
                var dtype = a.getAttribute('data-type') || 'rsvp';
                var mid = a.getAttribute('data-miqaat-id') || (upcoming && upcoming[index] && (upcoming[index].id || upcoming[index].miqaat_id)) || '';
                if (!mid) return;

                // local helpers
                function _escapeHtml(s) {
                  return String(s === null || s === undefined ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                }

                function _normalizePhone(raw) {
                  var DEFAULT_CC = '91';
                  if (!raw) return '';
                  var s = String(raw).trim();
                  s = s.replace(/[\s\-\.\(\)]/g, '');
                  if (s.charAt(0) === '+') {
                    var d = s.slice(1).replace(/\D/g, '');
                    return '+' + d;
                  }
                  if (s.indexOf('00') === 0) {
                    var d2 = s.slice(2).replace(/\D/g, '');
                    return '+' + d2;
                  }
                  var digits = s.replace(/\D/g, '');
                  if (!digits) return '';
                  if (digits.length === 10) return '+' + DEFAULT_CC + digits;
                  if (digits.length === 11 && digits.charAt(0) === '0') return '+' + DEFAULT_CC + digits.slice(1);
                  if (digits.length >= 11 && digits.length <= 15) return '+' + digits;
                  return digits;
                }

                function renderHofListLocal(title, rows) {
                  var html = '';
                  html += '<div class="d-flex justify-content-between align-items-center mb-2">';
                  html += '<strong>' + _escapeHtml(title) + '</strong>';
                  // Filter out rows that have both Sector and Sub_Sector empty before counting
                  try {
                    rows = (rows || []).filter(function(r) {
                      var sector = (r && (r.Sector || r.sector) ? (r.Sector || r.sector) : '') + '';
                      var sub = (r && (r.Sub_Sector || r.sub_sector || r.SubSector) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '') + '';
                      return !((sector || '').trim() === '' && (sub || '').trim() === '');
                    });
                  } catch (e) {
                    console.warn('HOF filter failed', e);
                  }
                  html += '<span class="text-muted">Count: ' + (rows ? rows.length : 0) + '</span>';
                  html += '</div>';
                  if (!rows || rows.length === 0) {
                    html += '<div class="text-muted">No records found.</div>';
                    return html;
                  }
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
                    console.warn('sort error', e);
                  }
                  // Choose first-column header based on whether this is a member list
                  var firstCol = (title && String(title).toLowerCase().indexOf('member') !== -1) ? 'Member ID' : 'HOF ID';
                  html += '<table class="table table-sm table-striped"><thead><tr><th>' + _escapeHtml(firstCol) + '</th><th>Name</th><th>Sector</th><th>Sub Sector</th></tr></thead><tbody>';
                  rows.forEach(function(r) {
                    var id = (r && (r.ITS_ID || r.hof_id || r.ITS)) ? (r.ITS_ID || r.hof_id || r.ITS) : '';
                    var name = (r && (r.Full_Name || r.name)) ? (r.Full_Name || r.name) : '';
                    var sector = (r && (r.Sector || r.sector)) ? (r.Sector || r.sector) : '';
                    var subSector = (r && (r.Sub_Sector || r.sub_sector || r.SubSector)) ? (r.Sub_Sector || r.sub_sector || r.SubSector) : '';
                    // Mobile column removed per UX change
                    html += '<tr><td>' + _escapeHtml(id) + '</td><td>' + _escapeHtml(name) + '</td><td>' + _escapeHtml(sector) + '</td><td>' + _escapeHtml(subSector) + '</td></tr>';
                  });
                  html += '</tbody></table>';
                  return html;
                }

                // show modal and loading; populate meta block with basic miqaat info
                try {
                  var lbl = "Will not attend for Miqaat";
                  if (dtype === 'rsvp') lbl = "RSVP'd for Miqaat";
                  else if (dtype === 'not_submitted') lbl = "RSVP not submitted for Miqaat";
                  try {
                    $('#hofListLabel').text(lbl);
                  } catch (e) {}
                } catch (e) {}
                var loading = document.getElementById('hofListLoading');
                var containerEl = document.getElementById('hofListContainer');
                var metaEl = document.getElementById('miqaatPopupMeta');
                if (loading) loading.style.display = 'block';
                if (containerEl) containerEl.innerHTML = '';
                // determine a local miqaat object if available
                try {
                  var miObj = (upcoming || []).find(function(x) {
                    return String(x.id || x.miqaat_id || '') === String(mid);
                  }) || (upcoming[index] || {});
                  var miName = miObj && (miObj.name || miObj.miqaat_name) ? (miObj.name || miObj.miqaat_name) : '';
                  var miDateLabel = miObj && (miObj.hijri_label || miObj.date) ? (miObj.hijri_label || miObj.date) : '';
                  if (metaEl) {
                    metaEl.innerHTML = '<div style="font-weight:600;">' + _escapeHtml(miName || 'Miqaat') + '</div>' +
                      (miDateLabel ? ('<div class="text-muted">' + _escapeHtml(miDateLabel) + '</div>') : '') +
                      '<div style="margin-top:8px;">' +
                      '<span class="badge badge-success" id="popupWillAttend" style="margin-right:8px;">Will attend: 0</span>' +
                      '<span class="badge badge-danger" id="popupWillNotAttend" style="margin-right:8px;">Will not attend: 0</span>' +
                      '<span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span>' +
                      '</div>';
                    try {
                      $('#miqaatPopupMeta').show();
                    } catch (e) {}
                  }
                } catch (e) {
                  console.warn('Failed to prepare miqaat meta', e);
                }
                try {
                  $('#hofListModal').modal('show');
                } catch (e) {
                  /* ignore if bootstrap unavailable */ }

                var url = window.location.pathname;
                try {
                  var u = new URL(url, window.location.origin);
                  u.searchParams.set('format', 'json');
                  u.searchParams.set('miqaat_rsvp', '1');
                  u.searchParams.set('miqaat_id', mid);
                  url = u.toString();
                } catch (err) {
                  url += '?format=json&miqaat_rsvp=1&miqaat_id=' + encodeURIComponent(mid);
                }
                fetch(url, {
                  credentials: 'same-origin'
                }).then(function(resp) {
                  return resp.json();
                }).then(function(data) {
                  if (loading) loading.style.display = 'none';
                  if (!data || !data.miqaat_rsvp) {
                    if (containerEl) containerEl.innerHTML = '<div class="text-muted">No data found.</div>';
                    return;
                  }
                  var m = data.miqaat_rsvp;
                  try {
                    if (typeof updateGuestCountsFromPayload === 'function') updateGuestCountsFromPayload(m);
                  } catch (e) {}
                  // Prefer member-level lists when available (show individual users)
                  var rows = [];
                  var titleTxt = '';
                  if (dtype === 'rsvp') {
                    rows = (m.rsvp_member_list && m.rsvp_member_list.length) ? m.rsvp_member_list : (m.rsvp_list || []);
                    titleTxt = "RSVP'd Members";
                  } else if (dtype === 'no') {
                    rows = (m.not_rsvp_member_list && m.not_rsvp_member_list.length) ? m.not_rsvp_member_list : (m.not_rsvp_list || []);
                    titleTxt = "Members Will not attend";
                  } else if (dtype === 'not_submitted') {
                    rows = (m.not_submitted_member_list && m.not_submitted_member_list.length) ? m.not_submitted_member_list : [];
                    titleTxt = "Members Not Submitted";
                  } else if (dtype === 'gents') {
                    rows = (m.rsvp_male_member_list && m.rsvp_male_member_list.length) ? m.rsvp_male_member_list : (m.rsvp_member_list || []);
                    titleTxt = "Gents";
                  } else if (dtype === 'ladies') {
                    rows = (m.rsvp_female_member_list && m.rsvp_female_member_list.length) ? m.rsvp_female_member_list : (m.rsvp_member_list || []);
                    titleTxt = "Ladies";
                  } else if (dtype === 'children') {
                    rows = (m.rsvp_children_member_list && m.rsvp_children_member_list.length) ? m.rsvp_children_member_list : [];
                    titleTxt = "Children";
                  } else {
                    // fallback to HOF-level not-rsvp list
                    rows = (m.not_rsvp_member_list && m.not_rsvp_member_list.length) ? m.not_rsvp_member_list : (m.not_rsvp_list || []);
                    titleTxt = "Members";
                  }
                  if (containerEl) containerEl.innerHTML = renderHofListLocal(titleTxt, rows);

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

                }).catch(function(err) {
                  if (loading) loading.style.display = 'none';
                  if (containerEl) containerEl.innerHTML = '<div class="text-danger">Failed to load list.</div>';
                  console.error('miqaat rsvp fetch failed', err);
                });
              });

              // Update guest breakdown counts when miqaat JSON is loaded elsewhere (renderFor initial fetch)
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
                  // also update willAttend guest short text
                  try {
                    var waGuestEl = document.getElementById('willAttendGuest');
                    if (waGuestEl) waGuestEl.textContent = (gs.total > 0 ? ('+' + (gs.total || 0) + ' guests') : '');
                  } catch (e) {}
                } catch (e) {
                  console.warn('Failed to update guest counts', e);
                }
              }
            })();
          </script>
        </div>

        <?php if (!empty($year_daytype_stats)) { ?>
          <div class="chart-container calendar-overview">
            <h4 class="section-title">FMB Calendar Overview (Hijri <?= htmlspecialchars($year_daytype_stats['hijri_year']); ?>)</h4>
            <div class="row">
              <a href="<?= base_url('common/fmbcalendar?from=anjuman'); ?>" class="col-12 col-md-4">
                <div class="stats-card bg-light">
                  <div class="text-center">
                    <h5>Miqaat Days</h5>
                    <div class="stats-value"><?= (int)$year_daytype_stats['miqaat_days']; ?></div>
                  </div>
                </div>
              </a>
              <a href="<?= base_url('common/fmbcalendar?from=anjuman'); ?>" class="col-12 col-md-4">
                <div class="stats-card bg-light">
                  <div class="text-center">
                    <h5>Thaali Days</h5>
                    <div class="stats-value"><?= (int)$year_daytype_stats['thaali_days']; ?></div>
                  </div>
                </div>
              </a>
              <a href="<?= base_url('common/fmbcalendar?from=anjuman'); ?>" class="col-12 col-md-4">
                <div class="stats-card bg-light">
                  <div class="text-center">
                    <h5>Holidays</h5>
                    <div class="stats-value"><?= (int)$year_daytype_stats['holiday_days']; ?></div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        <?php } ?>
        <!-- Top Charts Row: Mohallah + Grade-wise side by side -->
        <div class="row mb-2">
          <div class="col-md-12">
            <div class="chart-container" data-toggle="takhmeen">
              <h5 class="chart-title">FMB Takhmeen <?php
                                                    $__fmbYearTitle = isset($dashboard_data['fmb_takhmeen_year']) ? $dashboard_data['fmb_takhmeen_year'] : (isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : null);
                                                    ?></h5>
              <?php if ($__fmbYearTitle): ?>
                <div class="container-year">Hijri <?= htmlspecialchars($__fmbYearTitle); ?></div>
              <?php endif; ?>
              <?php
              $fmbSectorRows = isset($dashboard_data['fmb_takhmeen_sector']) ? $dashboard_data['fmb_takhmeen_sector'] : [];
              $fmbYear = isset($dashboard_data['fmb_takhmeen_year']) ? $dashboard_data['fmb_takhmeen_year'] : null;
              // Default to current Hijri year if not provided
              $currentHijriYear = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : null;
              if (!$fmbYear && $currentHijriYear) {
                $fmbYear = $currentHijriYear;
              }
              // If sector rows have mixed years, filter to current hijri year by default
              if (!empty($fmbSectorRows) && $fmbYear && isset($fmbSectorRows[0]) && (is_array($fmbSectorRows[0]) && array_key_exists('year', $fmbSectorRows[0]))) {
                $fmbSectorRows = array_values(array_filter($fmbSectorRows, function ($r) use ($fmbYear) {
                  return (isset($r['year']) ? strval($r['year']) : '') === strval($fmbYear);
                }));
              }
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
                  $fmbPaid  += $p;
                  $fmbDue   += max(0, $t - $p);
                }
                // Enforce integer amounts
                $fmbTotal = (int)round($fmbTotal);
                $fmbPaid  = (int)round($fmbPaid);
                $fmbDue   = (int)round($fmbDue);
                ?>
                <div class="takhmeen-summary" role="button" tabindex="0" aria-expanded="false" aria-controls="anj-fmb-details">
                  <div class="row mb-2">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Total</span><span class="overview-value text-dark">₹<?= format_inr($fmbTotal); ?></span></div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value text-success">₹<?= format_inr($fmbPaid); ?></span></div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Due</span><span class="overview-value text-danger">₹<?= format_inr($fmbDue); ?></span></div>
                      </div>
                    </div>
                  </div>
                  <p class="takhmeen-hint text-center mb-0">Click to view sector-wise details</p>
                </div>
                <div id="anj-fmb-details" class="takhmeen-details" hidden>
                  <div class="d-flex justify-content-end mb-2"><a href="#" class="hide-details">Hide details</a></div>
                  <div class="row g-2">
                    <?php foreach ($displayFmb as $row):
                      $sector = isset($row['sector']) ? trim($row['sector']) : 'Unassigned';
                      $total = (float)($row['total_takhmeen'] ?? 0);
                      $paid  = (float)($row['total_paid'] ?? 0);
                      $due   = (float)($row['outstanding'] ?? max(0, $total - $paid));
                      // Cast to integers for display
                      $total = (int)round($total);
                      $paid  = (int)round($paid);
                      $due   = (int)round($due);
                      $pct = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                    ?>
                      <div class="col-12 mb-2">
                        <div class="fmb-card sector-click"
                          data-type="FMB"
                          data-sector="<?= htmlspecialchars($sector); ?>"
                          data-members="<?= intval($row['members'] ?? 0); ?>"
                          data-total="₹<?= format_inr($total); ?>"
                          data-paid="₹<?= format_inr($paid); ?>"
                          data-due="₹<?= format_inr($due); ?>">
                          <div class="fmb-head">
                            <div class="fmb-name"><i class="fa fa-map-marker text-primary me-2"></i><?= htmlspecialchars($sector); ?>
                              <small class="text-muted ms-2">(<?= intval($row['members'] ?? 0); ?>)</small>
                              <?php if (!empty($row['no_takhmeen'])): ?>
                                <small class="text-muted ms-2">No takhmeen done</small>
                              <?php endif; ?>
                            </div>
                            <div class="fmb-amounts">
                              <span>Total <span class="val text-primary">₹<?= format_inr($total); ?></span></span>
                              <span>Paid <span class="val text-success">₹<?= format_inr($paid); ?></span></span>
                              <span>Due <span class="val text-danger">₹<?= format_inr($due); ?></span></span>
                            </div>
                          </div>
                          <div class="progress-slim">
                            <div class="bar" style="width: <?= $pct; ?>%"></div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php } // end if displayFmb 
              ?>
            </div>
          </div>
          <div class="col-md-12">
            <div class="chart-container" data-toggle="takhmeen">
              <h5 class="chart-title">Sabeel Takhmeen <?php
                                                      $__sabYearTitle = isset($dashboard_data['sabeel_takhmeen_year']) ? $dashboard_data['sabeel_takhmeen_year'] : (isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : null);
                                                      ?></h5>
              <?php if ($__sabYearTitle): ?>
                <div class="container-year">Hijri <?= htmlspecialchars($__sabYearTitle); ?></div>
              <?php endif; ?>
              <?php
              $sabSectorRows = isset($dashboard_data['sabeel_takhmeen_sector']) ? $dashboard_data['sabeel_takhmeen_sector'] : [];
              $sabYear = isset($dashboard_data['sabeel_takhmeen_year']) ? $dashboard_data['sabeel_takhmeen_year'] : null;
              // Default to current Hijri year if not provided
              if (!$sabYear && $currentHijriYear) {
                $sabYear = $currentHijriYear;
              }
              // Filter to current hijri year if mixed data present
              if (!empty($sabSectorRows) && $sabYear && isset($sabSectorRows[0]) && (is_array($sabSectorRows[0]) && array_key_exists('year', $sabSectorRows[0]))) {
                $sabSectorRows = array_values(array_filter($sabSectorRows, function ($r) use ($sabYear) {
                  return (isset($r['year']) ? strval($r['year']) : '') === strval($sabYear);
                }));
              }
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
                  $sabPaid  += $p;
                  $sabDue   += max(0, $t - $p);
                }
                // Enforce integer amounts
                $sabTotal = (int)round($sabTotal);
                $sabPaid  = (int)round($sabPaid);
                $sabDue   = (int)round($sabDue);
                ?>
                <div class="takhmeen-summary" role="button" tabindex="0" aria-expanded="false" aria-controls="anj-sab-details">
                  <div class="row mb-2">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Total</span><span class="overview-value text-dark">₹<?= format_inr($sabTotal); ?></span></div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value text-success">₹<?= format_inr($sabPaid); ?></span></div>
                      </div>
                    </div>
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                      <div class="overview-card">
                        <div class="overview-body"><span class="overview-title">Due</span><span class="overview-value text-danger">₹<?= format_inr($sabDue); ?></span></div>
                      </div>
                    </div>
                  </div>
                  <p class="takhmeen-hint text-center mb-0">Click to view sector-wise details</p>
                </div>
                <div id="anj-sab-details" class="takhmeen-details" hidden>
                  <div class="d-flex justify-content-end mb-2"><a href="#" class="hide-details">Hide details</a></div>
                  <div class="row g-2">

                    <?php foreach ($displaySab as $row):
                      $sector = isset($row['sector']) ? trim($row['sector']) : 'Unassigned';
                      $total = (float)($row['total_takhmeen'] ?? 0);
                      $paid  = (float)($row['total_paid'] ?? 0);
                      $due   = (float)($row['outstanding'] ?? max(0, $total - $paid));
                      // Cast to integers for display
                      $total = (int)round($total);
                      $paid  = (int)round($paid);
                      $due   = (int)round($due);
                      $pct = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                    ?>
                      <div class="col-12 mb-2">
                        <div class="fmb-card sector-click"
                          data-type="Sabeel"
                          data-sector="<?= htmlspecialchars($sector); ?>"
                          data-members="<?= intval($row['members'] ?? 0); ?>"
                          data-total="₹<?= format_inr($total); ?>"
                          data-paid="₹<?= format_inr($paid); ?>"
                          data-due="₹<?= format_inr($due); ?>">
                          <div class="fmb-head">
                            <div class="fmb-name"><i class="fa fa-map-marker text-success me-2"></i><?= htmlspecialchars($sector); ?>
                              <small class="text-muted ms-2">(<?= intval($row['members'] ?? 0); ?>)</small>
                              <?php if (!empty($row['no_takhmeen'])): ?>
                                <small class="text-muted ms-2">No takhmeen done</small>
                              <?php endif; ?>
                            </div>
                            <div class="fmb-amounts">
                              <span>Total <span class="val text-primary">₹<?= format_inr($total); ?></span></span>
                              <span>Paid <span class="val text-success">₹<?= format_inr($paid); ?></span></span>
                              <span>Due <span class="val text-danger">₹<?= format_inr($due); ?></span></span>
                            </div>
                          </div>
                          <div class="progress-slim">
                            <div class="bar" style="width: <?= $pct; ?>%; background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);"></div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php } // end if displaySab 
              ?>
            </div>
          </div>
        </div>

        <!-- Corpus + Raza side-by-side -->
        <?php
        // Corpus funds totals from assignments/payments:
        // Total Takhmeen = sum of assigned_total across funds
        // Total Received = sum of paid_total across funds
        // Total Pending = Total Takhmeen - Total Received
        $fundsCount = 0;
        $sumAmount = 0;
        $sumReceived = 0;
        $sumPending = 0;
        if (isset($corpus_funds) && is_array($corpus_funds)) {
          $fundsCount = count($corpus_funds);
          foreach ($corpus_funds as $f) {
            $assigned = (float)($f['assigned_total'] ?? 0);
            $paid = (float)($f['paid_total'] ?? 0);
            $sumAmount += $assigned;
            $sumReceived += $paid;
          }
        }
        $sumAmount = (int)round($sumAmount);
        $sumReceived = (int)round($sumReceived);
        $sumPending = max(0, (int)round($sumAmount - $sumReceived));
        $rz = isset($dashboard_data['raza_summary']) ? $dashboard_data['raza_summary'] : ['pending' => 0, 'approved' => 0, 'rejected' => 0];
        ?>
        <div class="row mt-2 align-items-stretch mb-4">
          <div class="col-md-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100 corpus-summary clickable"
              data-total="<?= format_inr($sumAmount); ?>"
              data-assigned="<?= format_inr($sumReceived); ?>"
              data-outstanding="<?= format_inr($sumPending); ?>">
              <div class="d-flex align-items-center mb-2">
                <h5 class="chart-title m-0">Corpus Funds</h5>
              </div>
              <div class="row text-center g-2">
                <div class="col-12 col-md-4">
                  <a href="<?= base_url('anjuman/corpusfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value amount-clickable" title="Click to view">₹<?= format_inr($sumAmount); ?></div>
                      <div class="stats-label">Total Takhmeen</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4">
                  <a href="<?= base_url('anjuman/corpusfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-success amount-clickable" title="Click to view">₹<?= format_inr($sumReceived); ?></div>
                      <div class="stats-label">Total Received</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-4">
                  <a href="<?= base_url('anjuman/corpusfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-danger amount-clickable" title="Click to view">₹<?= format_inr($sumPending); ?></div>
                      <div class="stats-label">Total Pending</div>
                    </div>
                  </a>
                </div>
                <div class="col-12">
                  <small class="text-muted">Funds: <?= (int)$fundsCount; ?></small>
                </div>
                <div class="col-12 mt-2">
                  <a href="<?= base_url('anjuman/corpusfunds_receive'); ?>" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
              </div>
            </div>

          </div>

            <!-- Ekram Funds Summary -->
            <?php
            $efundsCount = 0;
            $esumAssigned = 0;
            $esumPaid = 0;
            if (isset($ekram_funds) && is_array($ekram_funds)) {
              $efundsCount = count($ekram_funds);
              foreach ($ekram_funds as $efrow) {
                $esumAssigned += (float)($efrow['assigned_total'] ?? 0);
                $esumPaid += (float)($efrow['paid_total'] ?? 0);
              }
            }
            $esumPending = max(0, (int)round($esumAssigned - $esumPaid));
            ?>
            <div class="col-md-12 mb-3 mb-md-3">
              <div class="chart-container compact h-100 ekram-summary clickable"
                data-total="<?= format_inr($esumAssigned); ?>"
                data-assigned="<?= format_inr($esumPaid); ?>"
                data-outstanding="<?= format_inr($esumPending); ?>">
                <div class="d-flex align-items-center mb-2">
                  <h5 class="chart-title m-0">Ekram Funds</h5>
                </div>
                <div class="row text-center g-2">
                  <div class="col-12 col-md-4">
                    <a href="<?= base_url('anjuman/ekramfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                      <div class="mini-card" style="margin-bottom:8px;">
                        <div class="stats-value amount-clickable">₹<?= format_inr($esumAssigned); ?></div>
                        <div class="stats-label">Total Assigned</div>
                      </div>
                    </a>
                  </div>
                  <div class="col-12 col-md-4">
                    <a href="<?= base_url('anjuman/ekramfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                      <div class="mini-card" style="margin-bottom:8px;">
                        <div class="stats-value text-success amount-clickable">₹<?= format_inr($esumPaid); ?></div>
                        <div class="stats-label">Total Received</div>
                      </div>
                    </a>
                  </div>
                  <div class="col-12 col-md-4">
                    <a href="<?= base_url('anjuman/ekramfunds_receive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                      <div class="mini-card" style="margin-bottom:8px;">
                        <div class="stats-value text-danger amount-clickable">₹<?= format_inr($esumPending); ?></div>
                        <div class="stats-label">Total Pending</div>
                      </div>
                    </a>
                  </div>
                  <div class="col-12">
                    <small class="text-muted">Funds: <?= (int)$efundsCount; ?></small>
                  </div>
                  <div class="col-12 mt-2">
                    <a href="<?= base_url('anjuman/ekramfunds_receive'); ?>" class="btn btn-sm btn-outline-secondary">View All</a>
                  </div>
                </div>
              </div>
            </div>

          <?php
          $wa = isset($dashboard_data['wajebaat_summary']) && is_array($dashboard_data['wajebaat_summary'])
            ? $dashboard_data['wajebaat_summary']
            : ['count' => 0, 'total' => 0, 'received' => 0, 'due' => 0];
          ?>
          <div class="col-md-12 mb-3 mb-md-3">
            <div class="row g-2">
              <div class="col-12 mb-3">
                <a href="<?= base_url('anjuman/wajebaat'); ?>" class="text-decoration-none d-block">
                  <div class="chart-container compact h-100 clickable wq-summary-card">
                    <div class="d-flex align-items-center mb-2">
                      <h5 class="chart-title m-0">Wajebaat</h5>
                    </div>
                    <div class="row text-center g-2">
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-primary">₹<?= format_inr((int)($wa['total'] ?? 0)); ?></div>
                          <div class="stats-label">Total</div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-success">₹<?= format_inr((int)($wa['received'] ?? 0)); ?></div>
                          <div class="stats-label">Received</div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="mini-card" style="margin-bottom:8px;">
                          <div class="stats-value text-danger">₹<?= format_inr((int)($wa['due'] ?? 0)); ?></div>
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
          <div class="col-md-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="chart-title m-0">Qardan Hasana Schemes</h5>
                <a href="<?= base_url('anjuman/qardanhasana'); ?>" class="btn btn-sm btn-outline-secondary">View</a>
              </div>
              <div class="row text-center g-2">
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('anjuman/qardanhasana/mohammedi'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['mohammedi'] ?? 0), 0); ?></div>
                      <div class="stats-label">Mohammedi</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('anjuman/qardanhasana/taher'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['taher'] ?? 0), 0); ?></div>
                      <div class="stats-label">Taher</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('anjuman/qardanhasana/husain'); ?>" style="text-decoration:none;color:inherit;display:block;">
                    <div class="mini-card" style="margin-bottom:8px;">
                      <div class="stats-value text-primary">₹<?= format_inr((float)($qh_tot['husain'] ?? 0), 0); ?></div>
                      <div class="stats-label">Husain</div>
                    </div>
                  </a>
                </div>
                <div class="col-12 col-md-3">
                  <a href="<?= base_url('anjuman/qardanhasana'); ?>" style="text-decoration:none;color:inherit;display:block;">
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

          <?php
          $dashboard_madresa_hijri_year = isset($year_daytype_stats['hijri_year']) ? (int)$year_daytype_stats['hijri_year'] : null;
          ?>
          <div class="col-md-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="chart-title m-0">Madresa</h5>
                <a href="<?= base_url('madresa'); ?>" class="btn btn-sm btn-outline-secondary">View</a>
              </div>
              <div class="text-center py-3">
                <div class="row justify-content-center">
                  <div class="col-12 col-md-4">
                    <div class="mini-card">
                      <div class="stats-value">
                        <?= $dashboard_madresa_hijri_year ? ((int)$dashboard_madresa_hijri_year . 'H') : 'Classes'; ?>
                      </div>
                      <div class="stats-label">
                        Madresa Classes<?= $dashboard_madresa_hijri_year ? ' (Current Year)' : ''; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 mb-3 mb-md-3">
            <div class="chart-container compact h-100">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="chart-title m-0">Expenses</h5>
                <a href="<?= base_url('anjuman/expense'); ?>" class="btn btn-sm btn-outline-secondary">View</a>
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
            </div>
          </div>

          <div class="col-md-12">
            <div class="chart-container h-100">
              <h5 class="chart-title">Raza Summary</h5>
              <div class="row text-center mb-2">
                <div class="col-12 col-md-4 mb-2">
                  <div class="mini-card">
                    <div class="stats-value"><?= (int)$rz['pending']; ?></div>
                    <div class="stats-label">Pending</div>
                  </div>
                </div>
                <div class="col-12 col-md-4 mb-2">
                  <div class="mini-card">
                    <div class="stats-value"><?= (int)$rz['approved']; ?></div>
                    <div class="stats-label">Approved</div>
                  </div>
                </div>
                <div class="col-12 col-md-4 mb-2">
                  <div class="mini-card">
                    <div class="stats-value"><?= (int)$rz['rejected']; ?></div>
                    <div class="stats-label">Rejected</div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('anjuman/EventRazaRequest?event_type=1'); ?>">Miqaat Raza</a>
                <a class="btn btn-sm btn-outline-primary mt-2 mt-md-0" href="<?= base_url('anjuman/EventRazaRequest?event_type=2'); ?>">Kaaraj Raza</a>
                <a class="btn btn-sm btn-outline-secondary mt-2 mt-md-0" href="<?= base_url('anjuman/UmoorRazaRequest'); ?>">Umoor Raza</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Corpus Funds Modal (shows all three amounts) -->
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
                  <span>Total Takhmeen</span>
                  <strong id="corpusAmtTotal">₹0</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1">
                  <span>Total Received</span>
                  <strong id="corpusAmtAssigned" class="text-success">₹0</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-1">
                  <span>Total Pending</span>
                  <strong id="corpusAmtOutstanding" class="text-danger">₹0</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script>
          (function() {
            // Open modal on card click, populate all three amounts from data-attributes
            $(document).on('click', '.corpus-summary', function(e) {
              // Allow buttons/links inside card to work normally
              if ($(e.target).closest('a, button').length) return;
              var $card = $(this);
              var t = $card.data('total');
              var a = $card.data('assigned');
              var o = $card.data('outstanding');
              $('#corpusAmtTotal').text('₹' + t);
              $('#corpusAmtAssigned').text('₹' + a);
              $('#corpusAmtOutstanding').text('₹' + o);
              $('#corpusFundsModal').modal('show');
            });
            // Also support clicking on the individual numbers
            $(document).on('click', '.corpus-summary .stats-value.amount-clickable', function(e) {
              e.preventDefault();
              var $card = $(this).closest('.corpus-summary');
              var t = $card.data('total');
              var a = $card.data('assigned');
              var o = $card.data('outstanding');
              $('#corpusAmtTotal').text('₹' + t);
              $('#corpusAmtAssigned').text('₹' + a);
              $('#corpusAmtOutstanding').text('₹' + o);
              $('#corpusFundsModal').modal('show');
            });
          })();
        </script>
        <!-- Weekly Signups & Upcoming Miqaats Row -->
      </div> <!-- /Right Content -->
    </div> <!-- /row -->
    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>
    <!-- No-thaali modal -->
    <div id="noThaaliModal" class="modal-overlay" aria-hidden="true" role="dialog" aria-labelledby="noThaaliTitle">
      <div class="modal-card">
        <div class="modal-head">
          <h5 id="noThaaliTitle" class="modal-title">Families with No Thaali This Week</h5>
          <button id="noThaaliClose" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div id="noThaaliCount" class="text-muted" style="margin-bottom:8px;"></div>
        <div style="max-height:440px; overflow:auto;">
          <ul id="noThaaliList" class="list-unstyled" style="padding-left:0;"></ul>
        </div>
      </div>
    </div>
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
    // Mohallah chart removed; data is displayed as a table in the view.

    // Grade-wise Doughnut Charts (Establishment & Residential)
    const gradeDataEst = <?php echo json_encode(isset($dashboard_data['grade_breakdown_est']) ? $dashboard_data['grade_breakdown_est'] : [
                            ['grade' => 'Grade A', 'count' => 35],
                            ['grade' => 'Grade B', 'count' => 25],
                            ['grade' => 'Grade C', 'count' => 40]
                          ]); ?>;
    const gradeDataRes = <?php echo json_encode(isset($dashboard_data['grade_breakdown_res']) ? $dashboard_data['grade_breakdown_res'] : [
                            ['grade' => 'Grade A', 'count' => 30],
                            ['grade' => 'Grade B', 'count' => 20],
                            ['grade' => 'Grade C', 'count' => 50]
                          ]); ?>;

    function renderDoughnut(ctxId, data) {
      const el = document.getElementById(ctxId);
      if (!el) return null;
      const ctx = el.getContext('2d');
      // Build labels that include counts e.g., "A — 120"
      const displayLabels = data.map(item => `${item.grade} — ${item.count}`);
      return new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: displayLabels,
          datasets: [{
            data: data.map(item => item.count),
            backgroundColor: ['#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF6384', '#4D5360'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'right',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  // Ensure tooltip shows "Grade: count" once
                  const rawLabel = context.chart.data.labels[context.dataIndex] || '';
                  const gradeOnly = String(rawLabel).split(' — ')[0];
                  const value = context.parsed;
                  return `${gradeOnly}: ${value}`;
                }
              }
            }
          }
        }
      });
    }

    renderDoughnut('gradeChartEst', gradeDataEst);
    renderDoughnut('gradeChartRes', gradeDataRes);

    // Weekly Signups Line Chart
    const weeklyData = <?php echo json_encode(isset($dashboard_data['weekly_signups']) ? $dashboard_data['weekly_signups'] : [
                          ['week' => 'Week 1', 'signups' => 20],
                          ['week' => 'Week 2', 'signups' => 38],
                          ['week' => 'Week 3', 'signups' => 25],
                          ['week' => 'Week 4', 'signups' => 52]
                        ]); ?>;

    (function() {
      var weeklyCanvas = document.getElementById('weeklyChart');
      if (!weeklyCanvas) return; // Skip if the canvas isn't present on this page
      var weeklyCtx = weeklyCanvas.getContext('2d');
      new Chart(weeklyCtx, {
        type: 'line',
        data: {
          labels: weeklyData.map(item => item.week),
          datasets: [{
            label: 'Signups',
            data: weeklyData.map(item => item.signups),
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#36A2EB',
            pointBorderWidth: 0,
            pointRadius: 4
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                display: false
              }
            },
            x: {
              grid: {
                display: false
              }
            }
          }
        }
      });
    })();
    // Mobile sidebar toggle
    (function() {
      var toggleBtn = document.getElementById('sidebarToggle');
      var sidebarMenu = document.querySelector('.sidebar-menu');
      var overlay = document.getElementById('sidebarOverlay');
      if (!toggleBtn || !sidebarMenu || !overlay) return;

      function openSidebar() {
        sidebarMenu.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function closeSidebar() {
        sidebarMenu.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
      }

      toggleBtn.addEventListener('click', function() {
        if (sidebarMenu.classList.contains('open')) {
          closeSidebar();
        } else {
          openSidebar();
        }
      });

      overlay.addEventListener('click', function() {
        closeSidebar();
      });
      var closeBtn = document.getElementById('sidebarCloseBtn');
      if (closeBtn) closeBtn.addEventListener('click', function() {
        closeSidebar();
      });
    })();

    // Apply Amilsaheb-style color accents to cards (overview, fmb, calendar)
    document.addEventListener('DOMContentLoaded', function() {
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

        // Finance cards: soft tint and accented numbers (remove left border accent)
        document.querySelectorAll('.fmb-card').forEach((card, i) => {
          const c = accents[i % accents.length];
          // Remove left border accent per request
          // card.style.borderLeft = 'none';
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
          card.style.borderTop = '3px solid ' + c.fg;
        });
      })();
    });

    // Sector card navigation: go to respective pages
    (function() {
      document.addEventListener('click', function(e) {
        var card = e.target.closest('.sector-click');
        if (!card) return;
        var type = (card.getAttribute('data-type') || 'FMB').toLowerCase();
        if (type === 'fmb') {
          window.location.href = '<?php echo base_url('anjuman/fmbthaalitakhmeen'); ?>';
        } else {
          window.location.href = '<?php echo base_url('anjuman/sabeeltakhmeendashboard'); ?>';
        }
      });
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

    // No-thaali modal & data
    (function() {
      const noThaaliData = <?php echo json_encode(isset($dashboard_data['no_thaali_families']) ? $dashboard_data['no_thaali_families'] : []); ?>;
      var card = document.getElementById('noThaaliCard');
      var modal = document.getElementById('noThaaliModal');
      var closeBtn = document.getElementById('noThaaliClose');
      var list = document.getElementById('noThaaliList');
      var countEl = document.getElementById('noThaaliCount');

      function renderList() {
        if (!list || !countEl) return;
        countEl.textContent = noThaaliData.length + ' families have not signed up for thaali this week.';
        list.innerHTML = '';
        if (noThaaliData.length === 0) {
          list.innerHTML = '<li class="text-muted">No families — everyone has at least one signup.</li>';
          return;
        }
        noThaaliData.forEach(function(item) {
          var li = document.createElement('li');
          var name = item.Full_Name || item.FullName || (item.First_Name ? (item.First_Name + ' ' + (item.Surname || '')) : 'Unknown');
          var sector = item.Sector ? (' <small class="text-muted">(' + item.Sector + ')</small>') : '';
          li.innerHTML = '<div><strong>' + name + '</strong>' + sector + '</div>';
          list.appendChild(li);
        });
      }

      function openModal() {
        if (!modal) return;
        renderList();
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
      }

      function closeModal() {
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
      }

      if (card) card.addEventListener('click', function() {
        openModal();
      });
      if (closeBtn) closeBtn.addEventListener('click', function() {
        closeModal();
      });
      if (modal) modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
      });
    })();
  </script>
  <script>
    // Remove decimal points from currency summary amounts on Anjuman dashboard
    (function() {
      $('.corpus-summary .stats-value, .wq-summary-card .stats-value').each(function() {
        var txt = $(this).text();
        var m = txt.match(/^(₹?)([0-9,]+)(?:\.[0-9]+)?$/);
        if (m) {
          $(this).text((m[1] || '') + m[2]);
        }
      });
    })();
    // General INR normalizer: strip decimals wherever ₹ appears on this page
    (function() {
      function stripDecimals(el) {
        var txt = $(el).text();
        var m = txt && txt.match(/^(₹?)([0-9,]+)(?:\.[0-9]+)?$/);
        if (m) {
          $(el).text((m[1] || '') + m[2]);
        }
      }
      $(function() {
        // Overview values and finance amounts
        $('.overview-value, .fmb-amounts .val, #sectorModalTotal, #sectorModalPaid, #sectorModalDue').each(function() {
          stripDecimals(this);
        });
      });
      // After sector modal opens, normalize its values too
      document.addEventListener('click', function(e) {
        var card = e.target.closest('.sector-click');
        if (card) {
          setTimeout(function() {
            ['#sectorModalTotal', '#sectorModalPaid', '#sectorModalDue'].forEach(function(sel) {
              var el = document.querySelector(sel);
              if (el) stripDecimals(el);
            });
          }, 0);
        }
      });
    })();
  </script>