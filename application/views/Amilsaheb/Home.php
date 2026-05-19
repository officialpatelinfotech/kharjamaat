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
      margin-bottom: 12px;
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
      font-size: 1rem;
      font-weight: normal;
      color: #666;
      margin-left: 6px;
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
    /* margin-bottom: 20px; */
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
  @media (min-width: 768px) {
    .col-md-5th {
      flex: 0 0 20%;
      max-width: 20%;
    }
  }
  .section-header-standard {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 0.75rem;
    margin-bottom: 1.25rem;
    border-bottom: 2px solid #f1f5f9;
  }
  .section-header-standard .section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #475569;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .section-header-standard .section-title i {
    color: #94a3b8;
    font-size: 0.9rem;
  }
  .collapse-toggle-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    transition: all 0.2s;
  }
  .collapse-toggle-btn:hover {
    background: #f1f5f9;
    color: #475569;
  }
  .collapse-toggle-btn[aria-expanded="true"] i {
    transform: rotate(0deg);
  }
  .collapse-toggle-btn[aria-expanded="false"] i {
    transform: rotate(-90deg);
  }
  .collapse-toggle-btn i {
    transition: transform 0.3s ease;
  }
  .collapse-toggle-btn.collapsed i {
    transform: rotate(-90deg);
  }
  /* Optional: point up when open instead of down if desired, but current down/right logic is fine */
  
  .section-header-standard {
    cursor: pointer;
    user-select: none;
  }
  .section-header-standard:hover .collapse-toggle-btn {
    background: #f1f5f9;
    color: #475569;
  }
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
      <h1 class="dashboard-title">
          Amilsaheb Dashboard
          <span class="dashboard-subtitle">
              <?php
              $hijri_year = isset($year_daytype_stats['hijri_year']) ? $year_daytype_stats['hijri_year'] : '1446H';
              echo '— ' . $hijri_year . 'H / ' . date('Y');
              ?>
          </span>
      </h1>
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
          <li><a class="menu-item" href="<?php echo base_url('Amilsaheb/asharaohbat') ?>"><span class="menu-icon"><i class="fa fa-calendar"></i></span><span class="menu-label">Ashara Ohbat</span></a></li>
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

      <!-- ===== Member Search Widget ===== -->
      <div class="chart-container mb-4 member-search-widget" id="member-search-block" style="padding:18px 20px;">
        <style>
          /* Override parent overflow:hidden so the dropdown can escape */
          #member-search-block {
            overflow: visible !important;
          }
          /* Member Search Widget Styles */
          .msw-label {
            font-size: .85rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 10px;
          }
          .msw-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
          }
          .msw-input-group {
            position: relative;
            flex: 1 1 250px;
            max-width: 500px;
          }
          .msw-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
            font-size: 15px;
            pointer-events: none;
          }
          #mswInput {
            width: 100%;
            padding: 11px 38px 11px 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: .97rem;
            color: #111827;
            background: #fafbff;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
          }
          #mswInput:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
            background: #fff;
          }
          .msw-clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            width: 24px;
            height: 24px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #6b7280;
            font-size: 12px;
            line-height: 1;
          }
          .msw-clear-btn.visible { display: flex; }
          .msw-spinner {
            position: absolute;
            right: 38px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid rgba(0,0,0,.08);
            border-top-color: #6366f1;
            animation: msw-spin .7s linear infinite;
            display: none;
          }
          .msw-spinner.active { display: block; }
          @keyframes msw-spin { to { transform: translateY(-50%) rotate(360deg); } }
          #mswDropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0,0,0,.12);
            z-index: 1050;
            display: none;
            max-height: 300px;
            overflow-y: auto;
          }
          #mswDropdown.open { display: block; }
          .msw-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            cursor: pointer;
            transition: background .15s ease;
            border-bottom: 1px solid #f3f4f6;
          }
          .msw-result-item:last-child { border-bottom: none; }
          .msw-result-item:hover { background: #f5f7ff; }
          .msw-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .9rem;
            flex-shrink: 0;
          }
          .msw-avatar.female { background: linear-gradient(135deg,#ec4899,#f43f5e); }
          .msw-res-name {
            font-weight: 600;
            color: #111827;
            font-size: .92rem;
          }
          .msw-res-meta {
            font-size: .78rem;
            color: #6b7280;
            margin-top: 1px;
          }
          .msw-its-badge {
            margin-left: auto;
            background: #eef2ff;
            color: #6366f1;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
          }
          .msw-no-results {
            padding: 18px 14px;
            color: #6b7280;
            text-align: center;
            font-size: .9rem;
          }

        </style>

        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:15px;">
          <!-- Left side: Label & Subtitle -->
          <div class="d-flex align-items-center" style="gap:10px;">
            <div class="msw-label m-0" style="margin-bottom:0 !important;"><i class="fa fa-search mr-1"></i> Member Search</div>
            <span class="d-none d-md-inline" style="font-size:.78rem;color:#9ca3af;margin-top:2px;">Search by name or ITS ID</span>
          </div>

          <!-- Right side: Search Input & Button -->
          <div class="msw-input-wrap m-0" style="flex: 1 1 auto; justify-content: flex-end;">
            <div class="msw-input-group">
              <i class="fa fa-search msw-icon"></i>
              <input type="text" id="mswInput" placeholder="Type name or ITS ID..." autocomplete="off" aria-label="Search members" />
              <div class="msw-spinner" id="mswSpinner"></div>
              <button class="msw-clear-btn" id="mswClear" aria-label="Clear search" title="Clear">&#x2715;</button>
              <div id="mswDropdown" role="listbox" aria-label="Member search results"></div>
            </div>
            <a href="<?php echo base_url('amilsaheb/mumineendirectory'); ?>" class="btn btn-outline-primary btn-sm" style="white-space:nowrap;border-radius:10px;">
              <i class="fa fa-users mr-1"></i>All Members
            </a>
          </div>
        </div>
      </div>
      <!-- ===== End Member Search Widget ===== -->



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
        <h4 class="section-title text-center mt-4 mt-md-0 mb-4">Jamaat Overview</h4>

        <!-- Member Status (Active / Inactive) -->
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa fa-toggle-on"></i> Member Status</h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseAmilMemberActivity" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseAmilMemberActivity">
          <div class="row px-3">
            <div class="col-6 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?status=active'); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card" style="border: 1px solid #22c55e;">
                  <div class="overview-icon" style="background:#f0fdf4; color:#22c55e;"><i class="fa fa-check-circle"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Active Members</span>
                    <span class="overview-value"><?= (int)($stats['active_inactive']['active'] ?? 0); ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-6 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?status=inactive'); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card" style="border: 1px solid #ef4444;">
                  <div class="overview-icon" style="background:#fef2f2; color:#ef4444;"><i class="fa fa-times-circle"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Inactive Members</span>
                    <span class="overview-value"><?= (int)($stats['active_inactive']['inactive'] ?? 0); ?></span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Group 2: Statuses -->
        <div class="section-header-standard ml-3 mr-3">
          <h5 class="section-title"><i class="fa fa-info-circle"></i> Statistics</h5>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGroupStatuses" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseGroupStatuses">
          <div class="row px-3">
            <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?its_sabeel_match=its_sabeel_both_khar'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fa fa-home"></i></div>
                <div class="overview-body">
                  <span class="overview-title">ITS & Sabeel both in <?= htmlspecialchars(jamaat_place()) ?> <i class="fa fa-info-circle text-muted ml-1" title="Living in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>, ITS in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>, regular Sabeel payer" style="cursor:help; font-size:11px;" data-toggle="tooltip"></i></span>
                  <span class="overview-value"><?= (int)($stats['active_inactive']['its_sabeel_both_khar'] ?? 0); ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?its_sabeel_match=sabeel_khar_its_out'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon" style="background:#ecfeff; color:#0891b2;"><i class="fa fa-external-link"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Sabeel in <?= htmlspecialchars(jamaat_place()) ?>, ITS not in <?= htmlspecialchars(jamaat_place()) ?> <i class="fa fa-info-circle text-muted ml-1" title="ITS not in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>, but a resident and a regular Sabeel payer in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>" style="cursor:help; font-size:11px;" data-toggle="tooltip"></i></span>
                  <span class="overview-value"><?= (int)($stats['active_inactive']['sabeel_khar_its_out'] ?? 0); ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?its_sabeel_match=its_khar_sabeel_out'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa fa-sign-out"></i></div>
                <div class="overview-body">
                  <span class="overview-title">ITS in <?= htmlspecialchars(jamaat_place()) ?>, Sabeel not in <?= htmlspecialchars(jamaat_place()) ?> <i class="fa fa-info-circle text-muted ml-1" title="ITS in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?> but no longer residing in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>" style="cursor:help; font-size:11px;" data-toggle="tooltip"></i></span>
                  <span class="overview-value"><?= (int)($stats['active_inactive']['its_khar_sabeel_out'] ?? 0); ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?its_sabeel_match=both_not_khar'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon" style="background:#fff1f2; color:#dc2626;"><i class="fa fa-ban"></i></div>
                <div class="overview-body">
                  <span class="overview-title">ITS & Sabeel both not in <?= htmlspecialchars(jamaat_place()) ?> <i class="fa fa-info-circle text-muted ml-1" title="A resident but ITS and regular Sabeel payment both not in <?php echo htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>" style="cursor:help; font-size:11px;" data-toggle="tooltip"></i></span>
                  <span class="overview-value"><?= (int)($stats['active_inactive']['both_not_khar'] ?? 0); ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=all'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-users"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Total Members</span>
                  <span class="overview-value"><?= (int)($member_type_counts['total'] ?? 0) ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=hof_fm_type&value=HOF'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user"></i></div>
                <div class="overview-body">
                  <span class="overview-title">HOF (Head of Family)</span>
                  <span class="overview-value"><?= $stats['HOF'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=hof_fm_type&value=FM'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user-plus"></i></div>
                <div class="overview-body">
                  <span class="overview-title">FM (Family Members)</span>
                  <span class="overview-value"><?= $stats['FM'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
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
          <div class="col-6 col-md-5th mb-3">
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
          <div class="col-6 col-md-5th mb-3">
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
          <div class="col-6 col-md-5th mb-3">
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
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=16&max=25'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Age 16-25</span>
                  <span class="overview-value"><?= $stats['Age_16_25'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=26&max=65'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Age 26-65</span>
                  <span class="overview-value"><?= $stats['Age_26_65'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-5th mb-3">
            <a href="<?= base_url('amilsaheb/mumineendirectory?filter=age_range&min=66'); ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="overview-card">
                <div class="overview-icon"><i class="fa fa-user-circle"></i></div>
                <div class="overview-body">
                  <span class="overview-title">Above 65</span>
                  <span class="overview-value"><?= $stats['Buzurgo'] ?></span>
                </div>
              </div>
            </a>
          </div>
          <?php $mt = isset($member_type_counts) ? $member_type_counts : ['resident' => 0, 'external' => 0, 'moved_out' => 0, 'non_sabeel' => 0, 'temporary' => 0, 'total' => 0]; ?>

          
          </div>
        </div>

        

        <?php
        $status_counts = isset($stats['status_counts']) ? $stats['status_counts'] : [];
        $amilGroups1 = [
          'health_status'      => ['label' => 'Health Status',      'icon' => 'fa-heartbeat',       'bg' => '#fff1f2', 'color' => '#e11d48', 'count_key' => 'health',     'id' => 'collapseAmilHealth'],
          'deeni_status'       => ['label' => 'Deeni Status',       'icon' => 'fa-star',             'bg' => '#f5f3ff', 'color' => '#7c3aed', 'count_key' => 'deeni',      'id' => 'collapseAmilDeeni'],
          'residential_status' => ['label' => 'Residential Status', 'icon' => 'fa-building',         'bg' => '#f0f9ff', 'color' => '#0369a1', 'count_key' => 'residential','id' => 'collapseAmilResidential'],
          'residential_status' => ['label' => 'Residential Status', 'icon' => 'fa-building',         'bg' => '#f0f9ff', 'color' => '#0369a1', 'count_key' => 'residential','id' => 'collapseAmilResidential'],
          'Qualification'      => ['label' => 'Dunyavi Education',       'icon' => 'fa-graduation-cap',  'bg' => '#f0fdf4', 'color' => '#16a34a', 'count_key' => 'education',  'id' => 'collapseAmilEducation'],
        ];
        foreach ($amilGroups1 as $filterKey => $g) {
          $countKey = $g['count_key'];
          if (!empty($status_counts[$countKey])) {
        ?>
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="<?= $g['id'] ?>">
          <div class="row px-3">
            <?php foreach ($status_counts[$countKey] as $lbl => $cnt) {
              if ($lbl == 'None' || $lbl == '') continue; ?>
            <div class="col-6 col-md-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter='.$filterKey.'&value=' . rawurlencode($lbl)); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:<?= $g['bg'] ?>; color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div>
                  <div class="overview-body">
                    <span class="overview-title"><?= htmlspecialchars($lbl) ?></span>
                    <span class="overview-value"><?= $cnt ?></span>
                  </div>
                </div>
              </a>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php } } ?>

        <!-- Mehroom Deeni Talim -->
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa fa-graduation-cap"></i> Deeni Taalim Stats</h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseAmilEduTracking" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseAmilEduTracking">
          <div class="row px-3">
            <div class="col-12 col-md-6 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1'); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card" style="border-left: 4px solid #ea580c;">
                  <div class="overview-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa fa-book-reader"></i></div>
                  <div class="overview-body">
                    <span class="overview-title">Mehroom Deeni Taalim (Age 5-15)</span>
                    <span class="overview-value"><?= (int)($stats['madresa_deprived'] ?? 0); ?> Farzando</span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>

        <?php
        $amilGroups2 = [
          'Occupation'         => ['label' => 'Occupation',          'icon' => 'fa-briefcase',        'bg' => '#fafaf9', 'color' => '#57534e', 'count_key' => 'occupation', 'id' => 'collapseAmilOccupation'],
        ];
        foreach ($amilGroups2 as $filterKey => $g) {
          $countKey = $g['count_key'];
          if (!empty($status_counts[$countKey])) {
        ?>
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa <?= $g['icon'] ?>"></i> <?= $g['label'] ?></h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="<?= $g['id'] ?>">
          <div class="row px-3">
            <?php foreach ($status_counts[$countKey] as $lbl => $cnt) {
              if ($lbl == 'None' || $lbl == '') continue; ?>
            <div class="col-6 col-md-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?filter='.$filterKey.'&value=' . rawurlencode($lbl)); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:<?= $g['bg'] ?>; color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div>
                  <div class="overview-body">
                    <span class="overview-title"><?= htmlspecialchars($lbl) ?></span>
                    <span class="overview-value"><?= $cnt ?></span>
                  </div>
                </div>
              </a>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php } } ?>

        <!-- Sector-wise Members Section -->
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa fa-map-marker"></i> Sector-wise Members</h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGroupSectors" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseGroupSectors">
          <div class="row px-3">
          <?php
          $sectorRows = isset($stats['Sectors']) ? $stats['Sectors'] : [];
          if (!empty($sectorRows)) {
            $sectorRows = array_values(array_filter($sectorRows, function ($row) {
              $name = isset($row['Sector']) ? trim($row['Sector']) : '';
              return $name !== '' && strtolower($name) !== 'unassigned';
            }));
            usort($sectorRows, function ($a, $b) { return intval($b['total'] ?? 0) <=> intval($a['total'] ?? 0); });
            foreach ($sectorRows as $idx => $row):
              $hof = intval($row['hof_count'] ?? $row['HOF'] ?? $row['hof'] ?? 0);
              $fm  = intval($row['fm_count']  ?? $row['FM']  ?? $row['fm']  ?? 0);
              $collapseId = 'collapseAmilSectorIncharge' . $idx;
          ?>
            <div class="col-12 col-md-3 mb-3">
              <div class="overview-card sector-card" style="height:100%; display:flex; flex-direction:column;">

                <!-- Top clickable area -->
                <a href="<?= base_url('amilsaheb/mumineendirectory?filter=sector&value=' . rawurlencode($row['Sector'] ?: '')); ?>" style="text-decoration:none;color:inherit;">
                  <div style="display:flex; align-items:center;">
                    <div class="overview-icon"><i class="fa fa-map-marker"></i></div>
                    <div class="overview-body ms-2 ml-3" style="width:100%;">
                      <span class="overview-title"><?= htmlspecialchars($row['Sector'] ?: 'Unassigned'); ?></span>
                      <?php if (!empty($row['Sector_Incharge_Name']) || !empty($row['Sector_Incharge_Female_Name'])): ?>
                      <div style="margin-top:6px; margin-bottom:6px; font-size:0.78rem; color:#555; line-height:1.5;">
                        <?php if (!empty($row['Sector_Incharge_Name'])): ?>
                          <span><i class="fa fa-male text-primary" style="margin-right:3px;"></i><?= htmlspecialchars($row['Sector_Incharge_Name']) ?></span>
                        <?php endif; ?>
                      </div>
                      <?php endif; ?>
                      <div style="display:flex;align-items:baseline;gap:16px;flex-wrap:wrap;">
                        <span class="overview-value" style="font-size:1.15rem;">HOF: <?= $hof; ?></span>
                        <span class="overview-value" style="font-size:1.15rem;font-weight:600;">FM: <?= $fm; ?></span>
                      </div>
                    </div>
                  </div>
                </a>

                <!-- View Incharges toggle -->
                <?php if (!empty($row['Sector_Incharge_Name']) || !empty($row['Sector_Incharge_Female_Name']) || !empty($row['sub_sectors'])): ?>
                <div style="margin-top:10px; border-top:1px solid #eee; padding-top:5px; position:relative; z-index:2;">
                  <button class="btn btn-sm btn-link text-decoration-none w-100 text-start text-muted p-0 d-flex justify-content-between align-items-center amilsaheb-incharge-toggle"
                    type="button" aria-expanded="false" aria-controls="<?= $collapseId ?>"
                    onclick="event.preventDefault(); event.stopPropagation(); var t=$('#<?= $collapseId ?>'); t.collapse('toggle'); var shown=t.hasClass('show'); $(this).attr('aria-expanded', !shown); $(this).find('i.fa').toggleClass('fa-chevron-down fa-chevron-up');">
                    <span><i class="fa fa-info-circle"></i> View Incharges</span>
                    <i class="fa fa-chevron-down small"></i>
                  </button>
                  <div class="collapse mt-2" id="<?= $collapseId ?>">
                    <div style="font-size:0.85rem; color:#555;">
                      <?php if (!empty($row['sub_sectors'])): ?>
                        <strong>Sub Sector Incharges:</strong>
                        <ul style="padding-left:0; margin-bottom:0; list-style:none; margin-top:5px;">
                        <?php foreach ($row['sub_sectors'] as $sub): ?>
                          <li style="margin-bottom:6px; background:#f8f9fa; padding:6px; border-radius:4px;">
                            <strong><?= htmlspecialchars($sub['Sub_Sector']) ?></strong><br>
                            <?php if (!empty($sub['Sub_Sector_Incharge_Name'])): ?>
                              <i class="fa fa-male text-primary me-1"></i> <?= htmlspecialchars($sub['Sub_Sector_Incharge_Name']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($sub['Sub_Sector_Incharge_Female_Name'])): ?>
                              <i class="fa fa-female text-danger me-1"></i> <?= htmlspecialchars($sub['Sub_Sector_Incharge_Female_Name']) ?>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php endif; ?>

                </div>
                </div>
          <?php endforeach; } ?>
          </div>
        </div>

        <!-- Marital Section -->
        <div class="section-header-standard ml-3 mr-3 mt-4">
          <h4 class="section-title"><i class="fa fa-heart"></i> Marital Stats</h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGroupMarital" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseGroupMarital">
          <div class="row px-3">
            <!-- New Singles 21-40 Insight -->
                  <div class="col-12 col-md-3 mb-3">
                    <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&marital_status=Single&min=21&max=40'); ?>" style="text-decoration:none;color:inherit;display:block;">
                      <div class="overview-card">
                        <div class="overview-icon" style="background:#eef2ff; color:#4f46e5;"><i class="fa fa-heart"></i></div>
                        <div class="overview-body">
                          <span class="overview-title">Single</span>
                          <span class="overview-value"><?= isset($stats['singles_21_40']) ? (int)$stats['singles_21_40'] : 0; ?></span>
                        </div>
                      </div>
                    </a>
                  </div>
          <?php
          $ms = isset($marital_status_counts) ? $marital_status_counts : [];
          foreach ($ms as $label => $count) {
            $lbl_l = strtolower(trim($label));
            if ($lbl_l === 'unknown' || $lbl_l === '' || $lbl_l === 'single') continue;
            $iconClass = 'fa fa-info-circle';
            $iconBg = '#f5f5f7';
            $iconColor = '#6b7280';
            if (strpos($lbl_l, 'single') !== false) { $iconClass = 'fa fa-user'; $iconBg = '#eef2ff'; $iconColor = '#4f46e5'; }
            elseif (strpos($lbl_l, 'married') !== false) { $iconClass = 'fa fa-user'; $iconBg = '#fff0f6'; $iconColor = '#db2777'; }
            elseif (strpos($lbl_l, 'engag') !== false) { $iconClass = 'fa fa-star'; $iconBg = '#fff7ed'; $iconColor = '#ea580c'; }
            elseif (strpos($lbl_l, 'divorc') !== false) { $iconClass = 'fa fa-user'; $iconBg = '#fff1f2'; $iconColor = '#dc2626'; }
            elseif (strpos($lbl_l, 'widow') !== false) { $iconClass = 'fa fa-user-secret'; $iconBg = '#ecfeff'; $iconColor = '#0891b2'; }
          ?>
            <div class="col-6 col-md-3 mb-3">
              <a href="<?= base_url('amilsaheb/mumineendirectory?status=Active&marital_status=' . rawurlencode($label)); ?>" style="text-decoration:none;color:inherit;display:block;">
                <div class="overview-card">
                  <div class="overview-icon" style="background:<?= $iconBg ?>; color:<?= $iconColor ?>;"><i class="<?= $iconClass ?>"></i></div>
                  <div class="overview-body">
                    <span class="overview-title"><?= htmlspecialchars($label) ?></span>
                    <span class="overview-value"><?= (int)$count ?></span>
                  </div>
                </div>
              </a>
            </div>
          <?php } ?>
          </div>
        </div>

        <!-- Performance / Weekly Signup Section -->
        <?php
        $sw = isset($dashboard_data['this_week_sector_signup_avg']) ? $dashboard_data['this_week_sector_signup_avg'] : null;
        if ($sw) {
          $sw_sectors = (isset($sw['sectors']) && is_array($sw['sectors'])) ? $sw['sectors'] : [];
          if (!empty($sw_sectors)) {
        ?>
        <div class="section-header-standard px-1 mt-4">
          <h4 class="section-title"><i class="fa fa-line-chart"></i> Weekly Signup Performance</h4>
          <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseGroupWeekly" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
        <div class="collapse show" id="collapseGroupWeekly">
          <div class="row px-3 mb-3">
          <?php foreach ($sw_sectors as $sr): ?>
            <div class="col-6 col-md-3 mb-2">
              <div class="mini-card p-2">
                <span class="stats-label d-block text-truncate"><?= htmlspecialchars($sr['sector'] ?? 'Unassigned') ?></span>
                <span class="stats-value"><?= round((float)($sr['avg_signup'] ?? 0), 1) ?>%</span>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
        <?php } } ?>

      </div><!-- End grouped-block -->

      <!-- Finance Section: Takhmeen Cards -->
      <div class="row">
        <!-- FMB Takhmeen -->
        <div class="col-md-12">
          <div class="chart-container" data-toggle="takhmeen">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-money"></i> FMB Takhmeen</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseFmbTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseFmbTakhmeen">
              <?php
              $fmbData = isset($dashboard_data['fmb_takhmeen_sector']) ? $dashboard_data['fmb_takhmeen_sector'] : [];
              $fYear = isset($dashboard_data['fmb_takhmeen_year']) ? $dashboard_data['fmb_takhmeen_year'] : ($hijri_year ?? null);
              if ($fYear) echo '<div class="container-year mr-5">Hijri '.htmlspecialchars($fYear).'</div>';

              $fTotal = 0; $fPaid = 0; $fDue = 0;
              foreach ($fmbData as $r) {
                $t = (float)($r['total_takhmeen'] ?? 0); $p = (float)($r['total_paid'] ?? 0);
                $fTotal += $t; $fPaid += $p;
              }
              $fDue = max(0, $fTotal - $fPaid);
              ?>
              <div class="takhmeen-summary" role="button" data-toggle="collapse" data-target="#fmb-sector-details">
                <div class="row mb-2">
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Total</span><span class="overview-value">₹<?= format_inr($fTotal) ?></span></div></div></div>
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value text-success">₹<?= format_inr($fPaid) ?></span></div></div></div>
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Due</span><span class="overview-value text-danger">₹<?= format_inr($fDue) ?></span></div></div></div>
                </div>
                <p class="takhmeen-hint text-center">Click to view sector-wise details</p>
              </div>
              <div id="fmb-sector-details" class="collapse mt-3">
                <div class="row g-2">
                  <?php foreach ($fmbData as $row):
                    $t = (float)($row['total_takhmeen'] ?? 0); $p = (float)($row['total_paid'] ?? 0); $d = (float)($row['outstanding'] ?? ($t-$p));
                    $pct = $t > 0 ? min(100, round(($p/$t)*100)) : 0;
                  ?>
                    <div class="col-12 mb-2">
                      <div class="fmb-card">
                        <div class="fmb-head">
                          <div class="fmb-name"><i class="fa fa-map-marker text-primary mr-2"></i><?= htmlspecialchars($row['sector'] ?? 'Unassigned') ?> <small class="text-muted">(<?= (int)($row['members'] ?? 0) ?>)</small></div>
                          <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($t) ?></span></span><span>Paid <span class="val text-success">₹<?= format_inr($p) ?></span></span><span>Due <span class="val text-danger">₹<?= format_inr($d) ?></span></span></div>
                        </div>
                        <div class="progress-slim"><div class="bar" style="width: <?= $pct ?>%"></div></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sabeel Takhmeen -->
        <div class="col-md-12">
          <div class="chart-container" data-toggle="takhmeen">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-credit-card"></i> Sabeel Takhmeen</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseSabTakhmeen" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseSabTakhmeen">
              <?php
              $sabData = isset($dashboard_data['sabeel_takhmeen_sector']) ? $dashboard_data['sabeel_takhmeen_sector'] : [];
              $sYear = isset($dashboard_data['sabeel_takhmeen_year']) ? $dashboard_data['sabeel_takhmeen_year'] : ($hijri_year ?? null);
              if ($sYear) echo '<div class="container-year mr-5">Hijri '.htmlspecialchars($sYear).'</div>';

              $sTotal = 0; $sPaid = 0; $sDue = 0;
              foreach ($sabData as $r) {
                $t = (float)($r['total_takhmeen'] ?? 0); $p = (float)($r['total_paid'] ?? 0);
                $sTotal += $t; $sPaid += $p;
              }
              $sDue = max(0, $sTotal - $sPaid);
              ?>
              <div class="takhmeen-summary" role="button" data-toggle="collapse" data-target="#sab-sector-details">
                <div class="row mb-2">
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Total</span><span class="overview-value">₹<?= format_inr($sTotal) ?></span></div></div></div>
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Paid</span><span class="overview-value text-success">₹<?= format_inr($sPaid) ?></span></div></div></div>
                  <div class="col-12 col-md-4 mb-2"><div class="overview-card"><div class="overview-body"><span class="overview-title">Due</span><span class="overview-value text-danger">₹<?= format_inr($sDue) ?></span></div></div></div>
                </div>
                <p class="takhmeen-hint text-center">Click to view sector-wise details</p>
              </div>
              <div id="sab-sector-details" class="collapse mt-3">
                <div class="row g-2">
                  <?php foreach ($sabData as $row):
                    $t = (float)($row['total_takhmeen'] ?? 0); $p = (float)($row['total_paid'] ?? 0); $d = (float)($row['outstanding'] ?? ($t-$p));
                    $pct = $t > 0 ? min(100, round(($p/$t)*100)) : 0;
                  ?>
                    <div class="col-12 mb-2">
                      <div class="fmb-card">
                        <div class="fmb-head">
                          <div class="fmb-name"><i class="fa fa-map-marker text-success mr-2"></i><?= htmlspecialchars($row['sector'] ?? 'Unassigned') ?> <small class="text-muted">(<?= (int)($row['members'] ?? 0) ?>)</small></div>
                          <div class="fmb-amounts"><span>Total <span class="val">₹<?= format_inr($t) ?></span></span><span>Paid <span class="val text-success">₹<?= format_inr($p) ?></span></span><span>Due <span class="val text-danger">₹<?= format_inr($d) ?></span></span></div>
                        </div>
                        <div class="progress-slim"><div class="bar" style="width: <?= $pct ?>%; background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);"></div></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Financial Modules: Corpus, Wajebaat, QH, etc -->
      <div class="row">
        <!-- Corpus Funds -->
        <?php
        $cTot = 0; $cRec = 0; $cPen = 0; $cCount = 0;
        if (isset($corpus_funds) && is_array($corpus_funds)) {
          $cCount = count($corpus_funds);
          foreach ($corpus_funds as $f) { $cTot += (float)($f['assigned_total'] ?? 0); $cRec += (float)($f['paid_total'] ?? 0); }
        }
        $cPen = max(0, $cTot - $cRec);
        ?>
        <div class="col-md-12 mb-3">
          <div class="chart-container compact">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-university"></i> Corpus Funds</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseCorpus" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseCorpus">
              <div class="row text-center g-2">
                <div class="col-4"><a href="<?= base_url('amilsaheb/corpusfunds_receive') ?>"><div class="mini-card"><span class="stats-value">₹<?= format_inr($cTot) ?></span><span class="stats-label">Total</span></div></a></div>
                <div class="col-4"><a href="<?= base_url('amilsaheb/corpusfunds_receive') ?>"><div class="mini-card"><span class="stats-value text-success">₹<?= format_inr($cRec) ?></span><span class="stats-label">Received</span></div></a></div>
                <div class="col-4"><a href="<?= base_url('amilsaheb/corpusfunds_receive') ?>"><div class="mini-card"><span class="stats-value text-danger">₹<?= format_inr($cPen) ?></span><span class="stats-label">Pending</span></div></a></div>
                <div class="col-12 mt-2"><small class="text-muted">Funds: <?= $cCount ?></small> | <a href="<?= base_url('amilsaheb/corpusfunds_receive') ?>" class="btn btn-xs btn-outline-secondary py-0">View All</a></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Wajebaat -->
        <?php $wa = isset($dashboard_data['wajebaat_summary']) ? $dashboard_data['wajebaat_summary'] : ['total'=>0,'received'=>0,'due'=>0]; ?>
        <div class="col-md-12 mb-3">
          <div class="chart-container compact">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-coins"></i> Wajebaat</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseWajebaat" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseWajebaat">
              <div class="row text-center g-2">
                <div class="col-4"><div class="mini-card"><span class="stats-value">₹<?= format_inr($wa['total']) ?></span><span class="stats-label">Total</span></div></div>
                <div class="col-4"><div class="mini-card"><span class="stats-value text-success">₹<?= format_inr($wa['received']) ?></span><span class="stats-label">Received</span></div></div>
                <div class="col-4"><div class="mini-card"><span class="stats-value text-danger">₹<?= format_inr($wa['due']) ?></span><span class="stats-label">Pending</span></div></div>
                <div class="col-12 mt-2"><a href="<?= base_url('amilsaheb/wajebaat') ?>" class="btn btn-xs btn-outline-secondary py-0">View All</a></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Qardan Hasana -->
        <?php $qh = isset($qh_all_schemes_totals) ? $qh_all_schemes_totals : ['mohammedi'=>0,'taher'=>0,'husain'=>0,'total'=>0]; ?>
        <div class="col-md-12 mb-3">
          <div class="chart-container compact">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-leaf"></i> Qardan Hasana</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseQH" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseQH">
              <div class="row text-center g-2">
                <div class="col-3"><div class="mini-card p-2"><span class="stats-value" style="font-size:1.1rem;">₹<?= format_inr($qh['mohammedi']) ?></span><span class="stats-label">Mohammedi</span></div></div>
                <div class="col-3"><div class="mini-card p-2"><span class="stats-value" style="font-size:1.1rem;">₹<?= format_inr($qh['taher']) ?></span><span class="stats-label">Taher</span></div></div>
                <div class="col-3"><div class="mini-card p-2"><span class="stats-value" style="font-size:1.1rem;">₹<?= format_inr($qh['husain']) ?></span><span class="stats-label">Husain</span></div></div>
                <div class="col-3"><div class="mini-card p-2"><span class="stats-value text-success" style="font-size:1.1rem;">₹<?= format_inr($qh['total']) ?></span><span class="stats-label">Total</span></div></div>
                <div class="col-12 mt-2"><a href="<?= base_url('amilsaheb/qardanhasana') ?>" class="btn btn-xs btn-outline-secondary py-0">View All</a></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Raza Summary -->
        <?php $rz = isset($dashboard_data['raza_summary']) ? $dashboard_data['raza_summary'] : ['pending'=>0,'approved'=>0,'rejected'=>0]; ?>
        <div class="col-md-12 mb-3">
          <div class="chart-container compact">
            <div class="section-header-standard px-1">
              <h4 class="section-title"><i class="fa fa-file-text-o"></i> Raza Summary</h4>
              <button class="collapse-toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRaza" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
            </div>
            <div class="collapse show" id="collapseRaza">
              <div class="row text-center g-2">
                <div class="col-4"><div class="mini-card"><span class="stats-value"><?= (int)$rz['pending'] ?></span><span class="stats-label">Pending</span></div></div>
                <div class="col-4"><div class="mini-card"><span class="stats-value text-success"><?= (int)$rz['approved'] ?></span><span class="stats-label">Approved</span></div></div>
                <div class="col-4"><div class="mini-card"><span class="stats-value text-danger"><?= (int)$rz['rejected'] ?></span><span class="stats-label">Rejected</span></div></div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- End Finance/Modules row -->

    </div><!-- End Right Content -->
  </div><!-- End Main Row -->
</div><!-- End Container -->



<script>
  $(document).ready(function() {
    // Sidebar Toggle Logic
    $('#sidebarToggle').on('click', function() {
      $('.sidebar-menu').addClass('open');
      if (!$('.sidebar-overlay').length) {
        $('body').append('<div class="sidebar-overlay"></div>');
      }
      $('.sidebar-overlay').fadeIn(200).addClass('show');
    });

    $(document).on('click', '#sidebarCloseBtn, .sidebar-overlay', function() {
      $('.sidebar-menu').removeClass('open');
      $('.sidebar-overlay').fadeOut(200, function() { $(this).remove(); });
    });

    // Quick Menu Search
    $('#quickMenuSearch').on('keyup', function() {
      var val = $(this).val().toLowerCase();
      if (val !== '') $('#quickMenuClear').show(); else $('#quickMenuClear').hide();
      $('.sidebar-menu .menu-item').each(function() {
        var txt = $(this).find('.menu-label').text().toLowerCase();
        $(this).parent().toggle(txt.indexOf(val) > -1);
      });
      $('.sidebar-menu .menu-section').each(function() {
        var section = $(this);
        var list = section.next('.menu-list');
        var hasVisible = list.find('li:visible').length > 0;
        section.toggle(hasVisible);
        list.toggle(hasVisible);
      });
    });

    $('#quickMenuClear').on('click', function() {
      $('#quickMenuSearch').val('').trigger('keyup');
    });

    // Member Search Widget Autocomplete
    let searchTimeout;
    const $mswInput = $('#mswInput');
    const $mswDropdown = $('#mswDropdown');
    const $mswSpinner = $('#mswSpinner');
    const $mswClear = $('#mswClear');

    $mswInput.on('input', function() {
      const q = $(this).val().trim();
      if (q.length > 0) $mswClear.addClass('visible'); else { $mswClear.removeClass('visible'); $mswDropdown.removeClass('open').empty(); return; }
      if (q.length < 2) return;

      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        $mswSpinner.addClass('active');
        $.ajax({
          url: '<?= base_url("amilsaheb/search_members_json") ?>',
          data: { query: q },
          dataType: 'json',
          success: function(data) {
            $mswSpinner.removeClass('active');
            $mswDropdown.empty().addClass('open');
            if (data && data.length > 0) {
              data.forEach(item => {
                const avatarClass = (item.gender && item.gender.toLowerCase() === 'female') ? 'msw-avatar female' : 'msw-avatar';
                const initial = (item.full_name || 'M').charAt(0).toUpperCase();
                const html = `
                  <div class="msw-result-item" onclick="window.location.href='<?= base_url("amilsaheb/viewmember/") ?>${item.its_id}'">
                    <div class="${avatarClass}">${initial}</div>
                    <div class="msw-res-content">
                      <div class="msw-res-name">${item.full_name}</div>
                      <div class="msw-res-meta">${item.sector || 'No Sector'} | HOF: ${item.hof_id || '-'}</div>
                    </div>
                    <div class="msw-its-badge">${item.its_id}</div>
                  </div>`;
                $mswDropdown.append(html);
              });
            } else {
              $mswDropdown.append('<div class="msw-no-results">No members found</div>');
            }
          },
          error: function() { $mswSpinner.removeClass('active'); }
        });
      }, 300);
    });

    $mswClear.on('click', function() { $mswInput.val('').trigger('input'); });
    $(document).on('click', function(e) { if (!$(e.target).closest('.member-search-widget').length) $mswDropdown.removeClass('open'); });

    // Section Header Clickable Toggle
    $(document).on('click', '.section-header-standard', function(e) {
      if ($(e.target).closest('button, a').length) return;
      const target = $(this).find('.collapse-toggle-btn').data('target');
      if (target) $(target).collapse('toggle');
    });

    // Sidebar Menu Section Toggle
    $(document).on('click', '.sidebar-menu .menu-section', function() {
      const $sec = $(this);
      const $list = $sec.next('.menu-list');
      $list.slideToggle(200);
      $sec.toggleClass('is-collapsed');
    });
  });
</script>