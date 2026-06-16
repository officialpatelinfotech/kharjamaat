<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --gold: #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --bg: #faf7f0;
    --surface: #fff;
    --surface-2: #f7f4ec;
    --border: #e8e0cc;
    --text-1: #1a1610;
    --text-2: #5a5244;
    --text-3: #9c8f7a;
    --green: #1a6645;
    --green-bg: #eaf4ee;
    --green-border: rgba(26,102,69,.25);
    --red: #b91c1c;
    --red-bg: #fef2f2;
    --red-border: rgba(185,28,28,.2);
    --blue: #1d4ed8;
    --blue-bg: #eff6ff;
    --blue-border: rgba(29,78,216,.2);
    --amber: #b45309;
    --amber-bg: #fffbeb;
    --amber-border: rgba(180,83,9,.2);
    --sh: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --sh2: 0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
  }

  body {
    background-color: var(--bg) !important;
  }

  /* Improved pill counter for Fala ni Niyaz button */
  .fala-ni-niyaz-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    margin: 0;
    margin-left: 2px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    border-radius: 999px;
    background-color: #ffffff;
    border: 1px solid rgba(13, 110, 253, 0.18);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    vertical-align: middle;
    user-select: none;
  }

  /* Ensure good contrast when on primary button */
  .btn-primary .fala-ni-niyaz-count {
    background-color: #ffffff;
  }

  /* Slight hover emphasis with parent button */
  #fala-ni-niyaz-invoices:hover .fala-ni-niyaz-count {
    border-color: rgba(13, 110, 253, 0.35);
    box-shadow: 0 2px 6px rgba(13, 110, 253, 0.12);
  }

  /* Sortable table headers */
  th.km-sortable {
    cursor: pointer;
    position: relative;
    user-select: none;
    white-space: nowrap;
  }

  th.km-sortable .sort-indicator {
    font-size: 11px;
    margin-left: 4px;
    opacity: 0.8;
  }

  /* ── Banner ── */
  .miqaat-banner {
    background: linear-gradient(135deg, #78520a 0%, var(--gold) 60%, #c9a227 100%);
    padding: 16px 20px;
    border-radius: 12px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--sh);
  }
  .miqaat-banner::after {
    content: '';
    position: absolute;
    right: -60px;
    top: -60px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .miqaat-banner-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
  }
  .miqaat-banner-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .miqaat-back {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.3);
    background: rgba(255,255,255,.15);
    color: #fff !important;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none !important;
    transition: background .15s;
    backdrop-filter: blur(4px);
  }
  .miqaat-back:hover {
    background: rgba(255,255,255,.25);
  }
  .miqaat-banner-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
  }
  .miqaat-banner-sub {
    font-size: 12px;
    color: rgba(255,255,255,.75);
    font-weight: 500;
    margin-top: 2px;
  }
  .miqaat-banner-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  /* ── Filters Card ── */
  .miqaat-filters-card {
    background: var(--surface);
    border: 1px solid var(--border) !important;
    border-radius: 12px;
    box-shadow: var(--sh);
    padding: 16px;
    margin-bottom: 20px;
  }
  .miqaat-filters-card label {
    font-weight: 700;
    font-size: 11px;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }
  .miqaat-filters-card input,
  .miqaat-filters-card select {
    height: 34px !important;
    border-radius: 8px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface-2) !important;
    font-size: 13px !important;
    color: var(--text-1) !important;
    transition: border-color 0.15s, background-color 0.15s;
  }
  .miqaat-filters-card input:focus,
  .miqaat-filters-card select:focus {
    border-color: var(--gold) !important;
    background: var(--surface) !important;
    box-shadow: none !important;
  }

  /* ── Table Card ── */
  .miqaat-table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--sh);
    overflow: hidden;
    margin-bottom: 30px;
  }
  .miqaat-table-responsive {
    max-height: 60vh;
    overflow-y: auto;
  }
  .miqaat-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
  }
  .miqaat-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: var(--text-1) !important;
    color: #fff !important;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border: none;
    border-bottom: 2px solid var(--border);
  }
  .miqaat-table tbody tr {
    transition: background-color 0.15s;
  }
  .miqaat-table tbody tr:nth-of-type(even) {
    background-color: var(--surface-2);
  }
  .miqaat-table tbody tr:hover {
    background-color: rgba(184, 134, 11, 0.05);
  }
  .miqaat-table tbody td {
    padding: 12px 16px;
    font-size: 13px;
    color: var(--text-2);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .miqaat-table tbody td b {
    color: var(--text-1);
  }

  /* ── Stats grid ── */
  .miqaat-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .miqaat-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    box-shadow: var(--sh);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .miqaat-stat-title {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-2);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .miqaat-stat-value {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-1);
  }

  /* Autocomplete for extra contribution modal */
  #extra-member-autocomplete-list .autocomplete-item {
    display: block;
    width: 100%;
    padding: 10px 14px;
    cursor: pointer;
    background: #fff;
    border: none;
    border-bottom: 1px solid #f0ece0;
    font-size: 0.85rem;
    font-weight: 600;
    color: #1a1610;
    text-align: left;
  }
  #extra-member-autocomplete-list .autocomplete-item:hover {
    background: #f5e9c0;
    color: #b8860b;
    text-decoration: none;
  }
  #extra-member-autocomplete-list .autocomplete-item:last-child {
    border-bottom: none;
  }

  /* Modals Premium Styling */
  .modal-content-premium {
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    background: var(--surface);
    overflow: hidden;
  }
  .modal-header-premium {
    background: linear-gradient(135deg, #78520a 0%, var(--gold) 50%, #c9a227 100%);
    color: #fff;
    border: none;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .modal-header-premium .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-weight: 600;
    font-size: 1.15rem;
    margin: 0;
  }
  .modal-header-premium .close {
    color: #fff;
    opacity: 0.85;
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    margin: 0;
  }
  .modal-header-premium .close:hover {
    opacity: 1;
  }
  .modal-body-premium {
    padding: 24px;
  }
  .form-group-premium label {
    font-weight: 700;
    font-size: 0.75rem;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
  }
  .form-control-premium {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.85rem;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    width: 100%;
    font-family: inherit;
  }
  .form-control-premium:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
    background: var(--surface);
  }

  /* Premium buttons */
  .btn-action {
    font-family: inherit;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 10px 16px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.2px;
    cursor: pointer;
    border: none;
  }
  .btn-action-primary {
    background: linear-gradient(135deg, var(--gold) 0%, #8f6808 100%);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.15);
  }
  .btn-action-primary:hover {
    color: #fff !important;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.3);
    transform: translateY(-2px);
    text-decoration: none;
  }
  .btn-action-secondary {
    background: var(--surface);
    color: var(--gold) !important;
    border: 1.5px solid var(--gold) !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
  }
  .btn-action-secondary:hover {
    background: var(--gold-muted);
    color: var(--gold) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.12);
    text-decoration: none;
  }

  /* Autocomplete suggestions dropdown container */
  .autocomplete-dropdown {
    position: absolute;
    z-index: 1050;
    left: 0;
    right: 0;
    top: 100%;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    max-height: 250px;
    overflow-y: auto;
  }

  /* Banner Right Layout */
  .miqaat-banner-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  /* Responsive Banner Stack for Mobile */
  @media (max-width: 768px) {
    .miqaat-banner-inner {
      flex-direction: column;
      align-items: stretch !important;
      gap: 16px;
    }
    .miqaat-banner-right {
      width: 100%;
      flex-direction: column;
      align-items: stretch !important;
      gap: 8px;
      margin-top: 8px;
    }
    .miqaat-banner-right a,
    .miqaat-banner-right button {
      width: 100%;
      justify-content: center;
      text-align: center;
      padding: 10px 16px !important;
    }
  }

  /* Premium Miqaat Details Box */
  .miqaat-details-card {
    background-color: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02);
  }
  .miqaat-details-group {
    display: flex;
    flex-wrap: wrap;
    gap: 16px 12px;
  }
  .miqaat-detail-item {
    flex: 1 1 calc(50% - 12px);
    min-width: 140px;
  }
  .miqaat-detail-item.full-width {
    flex: 1 1 100%;
  }
  .miqaat-detail-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-3);
    letter-spacing: 0.75px;
    margin-bottom: 2px;
  }
  .miqaat-detail-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-1);
    line-height: 1.4;
  }
</style>

<div class="margintopcontainer mx-2 mx-md-5 pt-3" style="font-family:'Plus Jakarta Sans',sans-serif;">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger rounded-3">
      <?php echo $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success rounded-3">
      <?php echo $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning rounded-3">
      <?php echo $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>

  <!-- Premium Banner -->
  <div class="miqaat-banner">
    <div class="miqaat-banner-inner">
      <div class="miqaat-banner-left">
        <a href="<?php echo base_url("anjuman/fmbniyaz") ?>" class="miqaat-back">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
          <div class="miqaat-banner-title">
            <i class="fa-solid fa-file-invoice-dollar" style="margin-right:6px"></i>
            <?php echo isset($miqaat_type) ? htmlspecialchars($miqaat_type) : ""; ?> Miqaat Pending Invoices
            <span id="generate-title-hijri-year" style="font-size:0.9em; font-weight:normal; opacity:0.85; margin-left:4px;"></span>
          </div>
          <div class="miqaat-banner-sub">Generate Niyaz invoices for members and groups</div>
        </div>
      </div>
      <div class="miqaat-banner-right">
        <button id="create-extra-contribution-btn" type="button" class="btn btn-light font-weight-bold d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createExtraContributionModal" data-toggle="modal" data-target="#createExtraContributionModal" style="border-radius: 8px; padding: 6px 16px; gap: 6px;">
          <i class="fa-solid fa-plus-circle"></i> Create Extra Contribution
        </button>
        <button id="fala-ni-niyaz-invoices" class="btn btn-light font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 6px 16px; gap: 6px;">
          <i class="fa-solid fa-bolt text-warning"></i> <?php echo isset($miqaat_type) ? htmlspecialchars($miqaat_type) : ""; ?> Niyaz Fala Takhmeen
          <span class="badge badge-primary" style="border-radius: 10px; font-size: 12px; padding: 4px 8px;"><?php echo isset($miqaats["Fala_ni_Niyaz"]) ? count($miqaats["Fala_ni_Niyaz"]) : 0; ?></span>
        </button>
        <a href="<?php 
          $mtype_num = 1;
          if (isset($miqaat_type)) {
            if ($miqaat_type === 'Shehrullah') $mtype_num = 1;
            elseif ($miqaat_type === 'Ashara') $mtype_num = 2;
            elseif ($miqaat_type === 'General') $mtype_num = 3;
            elseif ($miqaat_type === 'Ladies') $mtype_num = 4;
          }
          echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=" . $mtype_num); 
        ?>" class="btn btn-light font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 6px 16px; gap: 6px;">
            <i class="fa-solid fa-credit-card"></i> Update Invoice
          </a>
      </div>
    </div>
  </div>

  <?php
  $pending = (isset($miqaats["miqaats"]) && is_array($miqaats["miqaats"])) ? $miqaats["miqaats"] : [];
  $gen_sectors = [];
  $gen_sub_sectors = [];
  $gen_years = [];
  if (!empty($pending)) {
    foreach ($pending as $row) {
      $assignType = isset($row['assign_type']) ? strtolower(trim((string)$row['assign_type'])) : '';
      $sector = '';
      $subSector = '';
      if ($assignType === 'group') {
        $sector = isset($row['leader_Sector']) ? (string)$row['leader_Sector'] : '';
        $subSector = isset($row['leader_Sub_Sector']) ? (string)$row['leader_Sub_Sector'] : '';
      } else {
        $sector = isset($row['Sector']) ? (string)$row['Sector'] : '';
        $subSector = isset($row['Sub_Sector']) ? (string)$row['Sub_Sector'] : '';
      }
      if ($sector !== '' && !in_array($sector, $gen_sectors, true)) $gen_sectors[] = $sector;
      if ($subSector !== '' && !in_array($subSector, $gen_sub_sectors, true)) $gen_sub_sectors[] = $subSector;

      $hy = '';
      if (!empty($row['hijri_date'])) {
        $parts = preg_split('/\s+/', trim((string)$row['hijri_date']));
        $hy = $parts ? (string)end($parts) : '';
      }
      if ($hy !== '' && !in_array($hy, $gen_years, true)) $gen_years[] = $hy;
    }
    sort($gen_sectors);
    sort($gen_sub_sectors);
    rsort($gen_years);
  }

  // Prefer full Hijri years list from hijri_calendar (provided by controller)
  if (isset($hijri_years) && is_array($hijri_years) && !empty($hijri_years)) {
    $gen_years = array_values(array_unique(array_filter(array_map(function ($y) {
      $y = trim((string)$y);
      return $y !== '' ? $y : null;
    }, $hijri_years))));
    rsort($gen_years);
  }

  $default_gen_year = '';
  if (isset($current_hijri_year) && $current_hijri_year !== '') {
    $default_gen_year = (string)$current_hijri_year;
  } elseif (!empty($gen_years)) {
    $default_gen_year = $gen_years[0];
  }
  ?>

  <?php if (!empty($pending)) : ?>
    <div id="miqaat-generate-filters" class="miqaat-filters-card">
      <div class="form-row">
        <div class="col-md-3 mb-2">
          <label for="pf-name">Name or ITS</label>
          <input type="text" id="pf-name" class="form-control" placeholder="Search name or ITS...">
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-sector">Sector</label>
          <select id="pf-sector" class="form-control">
            <option value="">All Sectors</option>
            <?php if (!empty($gen_sectors)) : foreach ($gen_sectors as $s) : if ($s === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($s), ENT_QUOTES); ?>"><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label for="pf-subsector">Sub Sector</label>
          <select id="pf-subsector" class="form-control">
            <option value="">All Sub Sectors</option>
            <?php if (!empty($gen_sub_sectors)) : foreach ($gen_sub_sectors as $ss) : if ($ss === '') continue; ?>
                <option value="<?php echo htmlspecialchars(strtolower($ss), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label for="pf-year">Hijri Year</label>
          <select id="pf-year" class="form-control" data-default-year="<?php echo htmlspecialchars($default_gen_year, ENT_QUOTES); ?>">
            <option value="">All Years</option>
            <?php if (!empty($gen_years)) : foreach ($gen_years as $y) : ?>
                <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($default_gen_year === $y ? 'selected' : ''); ?>><?php echo htmlspecialchars($y); ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-1 mb-2 d-flex align-items-end">
          <button type="button" id="pf-clear" class="btn btn-outline-secondary w-100 d-inline-flex align-items-center justify-content-center" style="height:34px; border-radius:8px; gap: 6px;"><i class="fa-solid fa-filter-circle-xmark"></i> Clear</button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (empty($pending)): ?>
    <div class="card border-0 shadow-sm rounded-3 p-5 text-center mb-4" style="background: var(--surface); border: 1px solid var(--border) !important;">
      <div class="py-4">
        <i class="fa-solid fa-circle-check fa-3x mb-3" style="color: var(--green);"></i>
        <h4 style="font-family: 'Literata', serif; color: var(--text-1); font-weight: 600;">No invoice pending to create</h4>
        <p class="text-muted mb-0">All pending invoices have been successfully generated.</p>
      </div>
    </div>
  <?php else: ?>
  <!-- Stats Grid for already generated / extra contribution totals -->
  <div class="miqaat-stats-grid">
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Total Invoice Amount (Generated)</div>
      <div class="miqaat-stat-value text-primary" id="miqaat-total-amount">₹0</div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Individual Niyaz (Generated)</div>
      <div class="miqaat-stat-value text-success" id="miqaat-individual-total">₹0</div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Fala ni Niyaz (Generated)</div>
      <div class="miqaat-stat-value text-info" id="miqaat-fala-total">₹0</div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Extra Contribution</div>
      <div class="miqaat-stat-value text-warning" id="miqaat-extra-total">₹0</div>
    </div>
    <div class="miqaat-stat-card">
      <div class="miqaat-stat-title">Pending Invoices Shown</div>
      <div class="miqaat-stat-value text-dark" id="miqaat-invoices-count">0</div>
    </div>
  </div>

  <div class="miqaat-table-card">
    <div class="miqaat-table-responsive">
      <table class="miqaat-table" id="miqaat-generate-table">
        <thead>
          <tr>
            <th class="km-sortable" data-sort-key="index" data-sort-type="number" style="width: 50px;"># <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="date" data-sort-type="date" style="width: 140px;">Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="hijri" data-sort-type="string" style="width: 160px;">Hijri Date <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatId" data-sort-type="number" style="width: 110px;">Miqaat ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="razaId" data-sort-type="number" style="width: 110px;">Raza ID <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="miqaatName" data-sort-type="string">Miqaat Name <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="assignedTo" data-sort-type="string" style="width: 150px;">Assigned To <span class="sort-indicator"></span></th>
            <th class="km-sortable" data-sort-key="details" data-sort-type="string">Details <span class="sort-indicator"></span></th>
            <th style="width: 160px;">Actions</th>
          </tr>
        </thead>
          <tbody>
            <?php if (!isset($miqaats["miqaats"]) || empty($miqaats["miqaats"])): ?>
              <tr>
                <td colspan="9" class="text-center">No Pending Invoices Found</td>
              </tr>
            <?php else: ?>
              <tr id="miqaat-generate-no-matches" style="display:none;">
                <td colspan="9" class="text-center text-muted">No pending invoices found for this year.</td>
              </tr>
              <?php foreach ($miqaats["miqaats"] as $i => $m): ?>
                <?php
                $assignType = isset($m['assign_type']) ? strtolower(trim((string)$m['assign_type'])) : '';
                $rowIts = '';
                $rowName = '';
                $rowSector = '';
                $rowSubSector = '';

                if ($assignType === 'group') {
                  $rowIts = isset($m['leader_ITS_ID']) ? (string)$m['leader_ITS_ID'] : '';
                  $rowName = isset($m['leader_Full_Name']) ? (string)$m['leader_Full_Name'] : '';
                  $rowSector = isset($m['leader_Sector']) ? (string)$m['leader_Sector'] : '';
                  $rowSubSector = isset($m['leader_Sub_Sector']) ? (string)$m['leader_Sub_Sector'] : '';
                } else {
                  $rowIts = isset($m['ITS_ID']) ? (string)$m['ITS_ID'] : '';
                  $rowName = isset($m['Full_Name']) ? (string)$m['Full_Name'] : '';
                  $rowSector = isset($m['Sector']) ? (string)$m['Sector'] : '';
                  $rowSubSector = isset($m['Sub_Sector']) ? (string)$m['Sub_Sector'] : '';
                }

                $rowYear = '';
                if (!empty($m['hijri_date'])) {
                  $p = preg_split('/\s+/', trim((string)$m['hijri_date']));
                  $rowYear = $p ? (string)end($p) : '';
                }

                $assignedToLabel = isset($m['assigned_to']) ? (string)$m['assigned_to'] : '';
                $assignedToKey = strtolower(trim($assignedToLabel));
                $assignedToDisplay = $assignedToLabel;
                $individualCountForSort = 0;
                if ($assignedToKey === 'individual') {
                  $cnt = isset($m['individual_count']) ? (int)$m['individual_count'] : 0;
                  $assignedToDisplay = $assignedToLabel . ' (' . $cnt . ')';
                  $individualCountForSort = $cnt;
                }
                ?>
                <tr class="miqaat-generate-row"
                  data-name="<?php echo htmlspecialchars(strtolower($rowName), ENT_QUOTES); ?>"
                  data-its="<?php echo htmlspecialchars(strtolower($rowIts), ENT_QUOTES); ?>"
                  data-sector="<?php echo htmlspecialchars(strtolower($rowSector), ENT_QUOTES); ?>"
                  data-subsector="<?php echo htmlspecialchars(strtolower($rowSubSector), ENT_QUOTES); ?>"
                  data-year="<?php echo htmlspecialchars($rowYear, ENT_QUOTES); ?>"
                  data-greg-date="<?php echo htmlspecialchars((string)($m['miqaat_date'] ?? ''), ENT_QUOTES); ?>"
                  data-hijri-date="<?php echo htmlspecialchars((string)($m['hijri_date'] ?? ''), ENT_QUOTES); ?>"
                  data-miqaat-id="<?php echo htmlspecialchars((string)($m['miqaat_id'] ?? ''), ENT_QUOTES); ?>"
                  data-raza-id="<?php echo htmlspecialchars((string)($m['raza_id'] ?? ''), ENT_QUOTES); ?>"
                  data-miqaat-name="<?php echo htmlspecialchars((string)($m['miqaat_name'] ?? ''), ENT_QUOTES); ?>"
                  data-assigned-to="<?php echo htmlspecialchars(strtolower($assignedToDisplay), ENT_QUOTES); ?>"
                  data-individual-count="<?php echo htmlspecialchars((string)$individualCountForSort, ENT_QUOTES); ?>">
                  <td><b><?php echo $i + 1 ?></b></td>
                  <td><?php echo date("d F Y", strtotime($m['miqaat_date'])) ?></td>
                  <td><?php echo htmlspecialchars($m['hijri_date']) ?></td>
                  <td><?php echo "M#" . htmlspecialchars($m['miqaat_id']) ?></td>
                  <td>
                    <?php if (!empty($m['raza_id'])): ?>
                      <?php echo "R#" . htmlspecialchars($m['raza_id']) ?>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td><b><?php echo htmlspecialchars($m['miqaat_name']) ?></b></td>
                  <td>
                    <b>
                      <?php
                      echo htmlspecialchars($assignedToDisplay);
                      ?>
                    </b>
                  </td>
                  <td>
                    <?php if (strtolower($m['assign_type']) === 'individual'): ?>
                      <?php echo htmlspecialchars($m['Full_Name']) ?> (<?php echo htmlspecialchars($m['ITS_ID']) ?>)
                    <?php elseif (strtolower($m['assign_type']) === 'group'): ?>
                      <b>Group:</b> <?php echo htmlspecialchars($m['group_name']) ?><br>
                      <?php if (!empty($m['leader_ITS_ID'])): ?>
                        <br><b>Leader:</b> <?php echo htmlspecialchars($m['leader_Full_Name']) ?> (<?php echo htmlspecialchars($m['leader_ITS_ID']) ?>)
                      <?php endif; ?>
                      <?php if (!empty($m['ITS_ID'])): ?>
                        <!-- <br><b>Co-leader:</b> <?php echo htmlspecialchars($m['Full_Name']) ?> (<?php echo htmlspecialchars($m['ITS_ID']) ?>) -->
                      <?php endif; ?>
                    <?php elseif (strtolower($m['assign_type']) === 'fala ni niyaz'): ?>
                      <b>Assigned to Fala ni Niyaz</b>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-primary generate-invoice-btn d-inline-flex align-items-center" style="gap: 5px;"
                      data-miqaat_index="<?php echo htmlspecialchars($m['miqaat_index']) ?>"
                      data-miqaat_id="<?php echo htmlspecialchars($m['miqaat_id']) ?>"
                      data-raza_index="<?php echo htmlspecialchars((string)($m['raza_index'] ?? ''), ENT_QUOTES) ?>"
                      data-raza_id="<?php echo htmlspecialchars((string)($m['raza_id'] ?? ''), ENT_QUOTES) ?>"
                      data-miqaat_name="<?php echo htmlspecialchars($m['miqaat_name']) ?>"
                      data-miqaat_date="<?php echo date("d", strtotime($m['miqaat_date'])) . " " . date("F", strtotime($m['miqaat_date'])) . " " . date("Y", strtotime($m['miqaat_date'])) ?>"
                      data-miqaat_type="<?php echo htmlspecialchars($m['miqaat_type']) ?>"
                      data-hijri_date="<?php echo htmlspecialchars($m['hijri_date']) ?>"
                      data-hijri_year="<?php $hijriParts = explode(' ', htmlspecialchars($m['hijri_date']));
                                        echo end($hijriParts); ?>"
                      data-sector="<?php echo htmlspecialchars($rowSector, ENT_QUOTES); ?>"
                      data-subsector="<?php echo htmlspecialchars($rowSubSector, ENT_QUOTES); ?>"
                      data-assigned_to="<?php echo htmlspecialchars($m['assigned_to']) ?>"
                      data-individual_count="<?php echo htmlspecialchars((string)($m['individual_count'] ?? ''), ENT_QUOTES); ?>"
                      data-member_id="<?php
                                      if (strtolower($m['assigned_to']) === 'group') {
                                        echo isset($m['leader_ITS_ID']) ? htmlspecialchars($m['leader_ITS_ID']) : '';
                                      } else {
                                        echo isset($m['ITS_ID']) ? htmlspecialchars($m['ITS_ID']) : '';
                                      }
                                      ?>"
                      data-details="<?php
                                    if (strtolower($m['assign_type']) === 'individual') {
                                      echo htmlspecialchars($m['Full_Name']) . ' (' . htmlspecialchars($m['ITS_ID']) . ')';
                                    } elseif (strtolower($m['assign_type']) === 'group') {
                                      echo 'Group: ' . htmlspecialchars($m['group_name']);
                                      if (!empty($m['leader_ITS_ID'])) {
                                        echo ' | Leader: ' . htmlspecialchars($m['leader_Full_Name']) . ' (' . htmlspecialchars($m['leader_ITS_ID']) . ')';
                                      }
                                    } elseif (strtolower($m['assign_type']) === 'fala ni niyaz') {
                                      echo 'Assigned to Fala ni Niyaz';
                                    } else {
                                      echo '-';
                                    }
                                    ?>"><i class="fa-solid fa-file-circle-plus"></i> Generate Invoice</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <!-- Single reusable Invoice Modal (placed outside loops) -->
  <div class="modal fade" id="generateInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="generateInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title" id="generateInvoiceModalLabel"><i class="fa-solid fa-file-invoice-dollar mr-2"></i> Generate Miqaat Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="generateInvoiceForm" method="post" action="<?php echo base_url('anjuman/create_miqaat_invoice') ?>">
          <div class="modal-body modal-body-premium">
            <input type="hidden" name="miqaat_id" id="modal_miqaat_index">
            <input type="hidden" name="raza_id" id="modal_raza_index">
            <input type="hidden" name="miqaat_type" id="input_miqaat_type">
            <input type="hidden" name="year" id="input_hijri_year">
            <input type="hidden" name="assigned_to" id="input_assigned_to">
            <input type="hidden" name="member_id" id="input_member_id">
            <input type="hidden" name="details" id="input_details">
            
            <div class="form-group form-group-premium mb-3">
              <label>Miqaat Details</label>
              <div id="modal_miqaat_details"></div>
            </div>

            <div class="form-group form-group-premium mb-3">
              <label for="modal-amount">Amount (₹)</label>
              <input type="number" id="modal-amount" class="form-control-premium" name="amount" placeholder="Enter amount" required min="1">
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="modal-description">Description</label>
              <textarea id="modal-description" class="form-control-premium" name="description" rows="2" placeholder="Optional remarks..."></textarea>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="modal-invoice-date">Invoice Date</label>
              <input type="date" id="modal-invoice-date" class="form-control-premium" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-2);">
            <button type="button" class="btn-action btn-action-secondary d-inline-flex align-items-center justify-content-center" data-dismiss="modal" data-bs-dismiss="modal" style="padding: 8px 16px; font-size: 0.82rem; min-width: 80px; gap: 5px;"><i class="fa-solid fa-xmark"></i> Cancel</button>
            <button type="submit" id="create-miqaat-invoice-btn" class="btn-action btn-action-primary d-inline-flex align-items-center justify-content-center" style="padding: 8px 20px; font-size: 0.82rem; gap: 5px;"><i class="fa-solid fa-check"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Fala ni Niyaz Modal -->
  <div class="modal fade" id="falaNiyazModal" tabindex="-1" aria-labelledby="falaNiyazModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title" id="falaNiyazModalLabel"><i class="fa-solid fa-bolt mr-2"></i> <?php echo isset($miqaat_type) ? $miqaat_type : ""; ?> Niyaz Takhmeen</h5>
          <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <div id="falaNiyazTableWrapper" class="table-responsive"></div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-2);">
          <button type="button" class="btn-action btn-action-secondary d-inline-flex align-items-center justify-content-center" data-dismiss="modal" data-bs-dismiss="modal" style="padding: 8px 16px; font-size: 0.82rem; min-width: 80px; gap: 5px;"><i class="fa-solid fa-xmark"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Create Extra Contribution Modal -->
  <div class="modal fade" id="createExtraContributionModal" tabindex="-1" role="dialog" aria-labelledby="createExtraContributionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title" id="createExtraContributionModalLabel"><i class="fa-solid fa-circle-plus mr-2"></i> Create Niyaz Extra Contribution</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="create-extra-contribution-form">
            <div class="form-group form-group-premium position-relative mb-3">
              <label for="extra-member-autocomplete">Name or ITS</label>
              <input type="text" id="extra-member-autocomplete" class="form-control-premium" placeholder="Type name or ITS..." autocomplete="off" required />
              <input type="hidden" name="user_id" id="extra-user-id" required />
              <div id="extra-member-autocomplete-list" class="autocomplete-dropdown" style="display:none;"></div>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="extra-contri-year">Contribution Year</label>
              <select name="contri_year" id="extra-contri-year" class="form-control-premium" required>
                <option value="">Select Contribution Year</option>
                <?php if (!empty($composite_hijri_years)): ?>
                  <?php foreach ($composite_hijri_years as $y): ?>
                    <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>"><?php echo htmlspecialchars($y); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="extra-contri-type">Contribution Type</label>
              <select name="contri_type" id="extra-contri-type" class="form-control-premium" required>
                <option value="">Select Type</option>
                <?php if (!empty($contri_type_gc)): ?>
                  <?php foreach ($contri_type_gc as $value): ?>
                    <option value="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>"><?php echo htmlspecialchars($value["name"]); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label>Miqaat Type</label>
              <input type="text" class="form-control-premium" value="<?php echo htmlspecialchars($miqaat_type, ENT_QUOTES); ?>" readonly style="background: var(--surface-2); cursor: not-allowed; opacity: 0.8;" />
              <input type="hidden" name="miqaat_type" value="<?php echo htmlspecialchars($miqaat_type, ENT_QUOTES); ?>" />
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="extra-amount">Amount (₹)</label>
              <input type="number" name="amount" id="extra-amount" class="form-control-premium" placeholder="Enter amount" min="1" required />
            </div>
            <div class="form-group form-group-premium mb-4">
              <label for="extra-description">Description</label>
              <textarea name="description" id="extra-description" class="form-control-premium" rows="2" placeholder="Optional remarks..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background: var(--surface-2);">
          <button type="button" class="btn-action btn-action-secondary d-inline-flex align-items-center justify-content-center" data-dismiss="modal" style="padding: 8px 16px; font-size: 0.82rem; min-width: 80px; gap: 5px;"><i class="fa-solid fa-xmark"></i> Close</button>
          <button type="button" id="create-extra-contribution-submit" class="btn-action btn-action-primary d-inline-flex align-items-center justify-content-center" onclick="jQuery('#create-extra-contribution-form').submit();" style="padding: 8px 20px; font-size: 0.82rem; gap: 5px;"><i class="fa-solid fa-plus-circle"></i> Create Invoice</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const genModalEl = document.getElementById('generateInvoiceModal');
    const genFormEl = document.getElementById('generateInvoiceForm');
    let modal = null;
    if (genModalEl) {
      modal = new bootstrap.Modal(genModalEl);
    }
    document.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (genFormEl) genFormEl.reset();
        const setVal = (id, val) => {
          const el = document.getElementById(id);
          if (el) el.value = val;
        };
        const setHtml = (id, html) => {
          const el = document.getElementById(id);
          if (el) el.innerHTML = html;
        };
        setHtml('modal_miqaat_details', '');
        setVal('modal_miqaat_index', '');
        setVal('modal_raza_index', '');
        setVal('input_miqaat_type', '');
        setVal('input_hijri_year', '');
        setVal('input_assigned_to', '');
        setVal('input_member_id', '');
        setVal('input_details', '');

        setVal('modal_miqaat_index', btn.getAttribute('data-miqaat_index'));
        setVal('modal_raza_index', btn.getAttribute('data-raza_index'));
        setVal('input_miqaat_type', btn.getAttribute('data-miqaat_type'));
        setVal('input_hijri_year', btn.getAttribute('data-hijri_year'));
        setVal('input_assigned_to', btn.getAttribute('data-assigned_to'));
        setVal('input_member_id', btn.getAttribute('data-member_id'));
        setVal('input_details', btn.getAttribute('data-details'));

        const razaDisplay = (btn.getAttribute('data-raza_id') || '').trim();
        const assignedRaw = (btn.getAttribute('data-assigned_to') || '').trim();
        const cnt = parseInt((btn.getAttribute('data-individual_count') || '0').toString(), 10) || 0;
        const assignedDisplay = (assignedRaw.toLowerCase() === 'individual' && cnt > 0)
          ? (assignedRaw + ' (' + cnt + ')')
          : assignedRaw;
        const detailsHtml = `
          <div class="miqaat-details-card">
            <div class="miqaat-details-group">
              <div class="miqaat-detail-item">
                <div class="miqaat-detail-label">Miqaat ID</div>
                <div class="miqaat-detail-value">M#${escapeHtml(btn.getAttribute('data-miqaat_id'))}</div>
              </div>
              <div class="miqaat-detail-item">
                <div class="miqaat-detail-label">Raza ID</div>
                <div class="miqaat-detail-value">${razaDisplay ? 'R#' + escapeHtml(razaDisplay) : '-'}</div>
              </div>
              <div class="miqaat-detail-item full-width">
                <div class="miqaat-detail-label">Miqaat Name</div>
                <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-miqaat_name'))}</div>
              </div>
              <div class="miqaat-detail-item">
                <div class="miqaat-detail-label">Date</div>
                <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-miqaat_date'))}</div>
              </div>
              <div class="miqaat-detail-item">
                <div class="miqaat-detail-label">Hijri Date</div>
                <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-hijri_date'))}</div>
              </div>
              <div class="miqaat-detail-item">
                <div class="miqaat-detail-label">Assigned To</div>
                <div class="miqaat-detail-value">${escapeHtml(assignedDisplay)}</div>
              </div>
              <div class="miqaat-detail-item full-width">
                <div class="miqaat-detail-label">Assignment Details</div>
                <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-details'))}</div>
              </div>
            </div>
          </div>
        `;
        setHtml('modal_miqaat_details', detailsHtml);
        if (modal) modal.show();
      });
    });

    if (genModalEl) {
      $('#generateInvoiceModal').on('hidden.bs.modal', function() {
        if (genFormEl) genFormEl.reset();
        $('#modal_miqaat_details').html('');
        $('#modal_miqaat_index').val('');
        $('#modal_raza_index').val('');

        const idsToClear = [
          'input_miqaat_type',
          'input_hijri_year',
          'input_assigned_to',
          'input_member_id',
          'input_details'
        ];

        idsToClear.forEach(function(id) {
          $('#' + id).val('');
        });
      });
    }
  });

  $(".alert").delay(5000).slideUp(300);
</script>

<script>
  const generatedInvoices = <?php echo json_encode($generated_invoices ?? []); ?>;
  const extraContributions = <?php echo json_encode($extra_contributions ?? []); ?>;
  // Pending invoices table filters (client-side)
  (function() {
    function toLower(s) {
      return (s || '').toString().trim().toLowerCase();
    }

    function applyGenerateFilters() {
      const nameQ = toLower(document.getElementById('pf-name')?.value);
      const sectorV = toLower(document.getElementById('pf-sector')?.value);
      const subSectorV = toLower(document.getElementById('pf-subsector')?.value);
      const yearV = (document.getElementById('pf-year')?.value || '').toString().trim();

      const titleYearEl = document.getElementById('generate-title-hijri-year');
      if (titleYearEl) {
        titleYearEl.textContent = yearV ? (' (Hijri ' + yearV + ')') : '';
      }

      const rows = Array.from(document.querySelectorAll('tr.miqaat-generate-row'));
      let visible = 0;
      rows.forEach(function(tr) {
        const rName = toLower(tr.getAttribute('data-name'));
        const rIts = toLower(tr.getAttribute('data-its'));
        const rSector = toLower(tr.getAttribute('data-sector'));
        const rSub = toLower(tr.getAttribute('data-subsector'));
        const rYear = (tr.getAttribute('data-year') || '').toString().trim();

        let ok = true;
        if (nameQ) {
          ok = (rName.indexOf(nameQ) !== -1) || (rIts.indexOf(nameQ) !== -1);
        }
        if (ok && sectorV) ok = rSector === sectorV;
        if (ok && subSectorV) ok = rSub === subSectorV;
        if (ok && yearV) ok = rYear === yearV;

        tr.style.display = ok ? '' : 'none';
        if (ok) visible++;
      });

      const noMatchRow = document.getElementById('miqaat-generate-no-matches');
      if (noMatchRow) {
        noMatchRow.style.display = (rows.length && visible === 0) ? '' : 'none';
      }

      renumberVisibleRows();

      // Local helper for INR grouping (no decimals)
      function formatRupees(n) {
        if (n === undefined || n === null) n = 0;
        let num = parseFloat(n);
        if (isNaN(num)) num = 0;
        num = Math.round(num);
        const neg = num < 0;
        if (neg) num = Math.abs(num);
        let s = String(num);
        if (s.length > 3) {
          const last3 = s.slice(-3);
          let rest = s.slice(0, -3);
          rest = rest.replace(/\B(?=(?:\d{2})+(?!\d))/g, ',');
          s = rest + ',' + last3;
        }
        return (neg ? '-' : '') + '₹' + s;
      }

      let individualTotal = 0;
      let falaTotal = 0;

      generatedInvoices.forEach(function(inv) {
        const iName = toLower(inv.Full_Name || '');
        const iIts = toLower(inv.ITS_ID || '');
        const iSector = toLower(inv.Sector || '');
        const iSub = toLower(inv.Sub_Sector || '');
        const iYear = (inv.invoice_year || '').toString().trim();

        let show = true;
        if (nameQ && iName.indexOf(nameQ) === -1 && iIts.indexOf(nameQ) === -1) show = false;
        if (sectorV && iSector !== sectorV) show = false;
        if (subSectorV && iSub !== subSectorV) show = false;
        if (yearV && iYear !== yearV) show = false;

        if (show) {
          const amt = parseFloat(inv.amount || '0') || 0;
          const assigned = toLower(inv.assigned_to || '');
          const desc = toLower(inv.description || '');
          if (assigned === 'fala ni niyaz' || desc.indexOf('fala ni niyaz') !== -1) {
            falaTotal += amt;
          } else {
            individualTotal += amt;
          }
        }
      });

      let extraTotal = 0;
      extraContributions.forEach(function(c) {
        const cName = toLower(c.Full_Name || '');
        const cIts = toLower(c.ITS_ID || '');
        const cSector = toLower(c.Sector || '');
        const cSub = toLower(c.Sub_Sector || '');
        const cYear = (c.contri_year || '').toString().trim().toLowerCase();

        let show = true;
        if (nameQ && cName.indexOf(nameQ) === -1 && cIts.indexOf(nameQ) === -1) show = false;
        if (sectorV && cSector !== sectorV) show = false;
        if (subSectorV && cSub !== subSectorV) show = false;
        if (yearV && !cYear.includes(yearV)) show = false;

        if (show) {
          extraTotal += parseFloat(c.amount || '0') || 0;
        }
      });

      const totalEl = document.getElementById('miqaat-total-amount');
      if (totalEl) totalEl.textContent = formatRupees(individualTotal + falaTotal + extraTotal);

      const indEl = document.getElementById('miqaat-individual-total');
      if (indEl) indEl.textContent = formatRupees(individualTotal);

      const falaEl = document.getElementById('miqaat-fala-total');
      if (falaEl) falaEl.textContent = formatRupees(falaTotal);

      const extraEl = document.getElementById('miqaat-extra-total');
      if (extraEl) extraEl.textContent = formatRupees(extraTotal);

      const cntEl = document.getElementById('miqaat-invoices-count');
      if (cntEl) cntEl.textContent = String(visible);
    }

    function renumberVisibleRows() {
      const rows = Array.from(document.querySelectorAll('tr.miqaat-generate-row'));
      let n = 0;
      rows.forEach(function(tr) {
        if (tr.style.display === 'none') return;
        n++;
        const cell = tr.querySelector('td');
        if (cell) cell.innerHTML = '<b>' + n + '</b>';
      });
    }

    function parseNumber(val) {
      const s = (val || '').toString();
      const m = s.match(/\d+(?:\.\d+)?/);
      return m ? parseFloat(m[0]) : NaN;
    }

    function getSortValue(tr, key, type) {
      if (!tr) return '';
      if (key === 'index') {
        // Use current DOM order index; will be stable after sort
        const firstCellText = tr.querySelector('td') ? tr.querySelector('td').textContent : '';
        return parseNumber(firstCellText);
      }

      if (key === 'date') {
        const iso = tr.getAttribute('data-greg-date') || '';
        const t = Date.parse(iso.replace(/-/g, '/'));
        return isNaN(t) ? 0 : t;
      }

      if (key === 'hijri') return (tr.getAttribute('data-hijri-date') || '').toString().toLowerCase();
      if (key === 'miqaatId') return parseNumber(tr.getAttribute('data-miqaat-id'));
      if (key === 'razaId') return parseNumber(tr.getAttribute('data-raza-id'));
      if (key === 'miqaatName') return (tr.getAttribute('data-miqaat-name') || '').toString().toLowerCase();
      if (key === 'assignedTo') {
        const raw = (tr.getAttribute('data-assigned-to') || '').toString().trim().toLowerCase();
        let label = raw;
        if (!label) {
          const cells = tr.querySelectorAll('td');
          // Assigned To column index in generate table: 6
          const cell = cells && cells.length >= 7 ? cells[6] : null;
          label = (cell ? cell.textContent : '').toString().trim().toLowerCase();
        }

        const type = (label.split('(')[0] || '').trim();
        const typeKey = type || label;

        if (typeKey === 'individual') {
          let cnt = parseInt((tr.getAttribute('data-individual-count') || '').toString(), 10);
          if (isNaN(cnt)) {
            const m = label.match(/\((\d+)\)/);
            cnt = m ? parseInt(m[1], 10) : 0;
          }
          const padded = ('000000' + String(isNaN(cnt) ? 0 : cnt)).slice(-6);
          return 'individual|' + padded;
        }

        return typeKey + '|';
      }
      if (key === 'details') {
        // Details is column 7 (0-based: 0..8 with actions). Keep robust by selecting 8th cell.
        const cells = tr.querySelectorAll('td');
        const detailsCell = cells && cells.length >= 8 ? cells[7] : null;
        return (detailsCell ? detailsCell.textContent : '').toString().trim().toLowerCase();
      }
      return '';
    }

    function updateSortIndicators(activeTh, dir) {
      document.querySelectorAll('#miqaat-generate-table thead th.km-sortable').forEach(function(th) {
        const ind = th.querySelector('.sort-indicator');
        if (th === activeTh) {
          th.setAttribute('data-sort-dir', dir);
          if (ind) ind.textContent = (dir === 'asc') ? '▲' : '▼';
        } else {
          th.removeAttribute('data-sort-dir');
          if (ind) ind.textContent = '↕';
        }
      });
    }

    function sortGenerateTable(key, type, dir) {
      const table = document.getElementById('miqaat-generate-table');
      if (!table) return;
      const tbody = table.querySelector('tbody');
      if (!tbody) return;

      const noMatchRow = document.getElementById('miqaat-generate-no-matches');
      const rows = Array.from(tbody.querySelectorAll('tr.miqaat-generate-row'));

      rows.sort(function(a, b) {
        const va = getSortValue(a, key, type);
        const vb = getSortValue(b, key, type);

        let cmp = 0;
        if (type === 'number' || type === 'date') {
          const na = (typeof va === 'number' && !isNaN(va)) ? va : -Infinity;
          const nb = (typeof vb === 'number' && !isNaN(vb)) ? vb : -Infinity;
          cmp = na === nb ? 0 : (na < nb ? -1 : 1);
        } else {
          cmp = String(va).localeCompare(String(vb));
        }

        return (dir === 'asc') ? cmp : -cmp;
      });

      // Re-append rows (keep no-match row at top)
      if (noMatchRow && noMatchRow.parentElement === tbody) {
        tbody.appendChild(noMatchRow);
      }
      rows.forEach(function(r) {
        tbody.appendChild(r);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const filtersEl = document.getElementById('miqaat-generate-filters');
      if (!filtersEl) return;

      // Sorting
      const table = document.getElementById('miqaat-generate-table');
      if (table) {
        // Show neutral arrows by default
        table.querySelectorAll('thead th.km-sortable .sort-indicator').forEach(function(ind) {
          if (!ind.textContent || ind.textContent.trim() === '') {
            ind.textContent = '↕';
          }
        });

        const headers = table.querySelectorAll('thead th.km-sortable');
        headers.forEach(function(th) {
          th.addEventListener('click', function() {
            const key = th.getAttribute('data-sort-key') || '';
            const type = th.getAttribute('data-sort-type') || 'string';
            const current = th.getAttribute('data-sort-dir');
            const dir = (current === 'asc') ? 'desc' : 'asc';

            updateSortIndicators(th, dir);
            sortGenerateTable(key, type, dir);
            applyGenerateFilters();
          });
        });
      }

      ['pf-name', 'pf-sector', 'pf-subsector', 'pf-year'].forEach(function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', applyGenerateFilters);
        el.addEventListener('change', applyGenerateFilters);
      });

      const clearBtn = document.getElementById('pf-clear');
      if (clearBtn) {
        clearBtn.addEventListener('click', function() {
          const nameEl = document.getElementById('pf-name');
          const sectorEl = document.getElementById('pf-sector');
          const subEl = document.getElementById('pf-subsector');
          const yearEl = document.getElementById('pf-year');
          if (nameEl) nameEl.value = '';
          if (sectorEl) sectorEl.value = '';
          if (subEl) subEl.value = '';
          if (yearEl) yearEl.value = '';
          applyGenerateFilters();
        });
      }

      applyGenerateFilters();
    });
  })();
</script>
<script>
  // Allow stacked modals: ensure each new modal/backdrop is layered above the previous
  (function() {
    $(document)
      .off('show.bs.modal.stacked')
      .on('show.bs.modal.stacked', '.modal', function() {
        const $open = $('.modal.show');
        const $this = $(this);
        const idx = $open.length;
        // Set z-index of this modal above existing ones
        $this.css('z-index', 1050 + idx * 20);
        // Adjust the backdrop just below this modal
        setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').first().css('z-index', 1040 + idx * 20).addClass('modal-stack');
        }, 0);
      })
      .off('hidden.bs.modal.stacked')
      .on('hidden.bs.modal.stacked', '.modal', function() {
        // Maintain scroll lock if there are other modals still open
        if ($('.modal.show').length) {
          $('body').addClass('modal-open');
        }
      });
  })();
</script>
<?php
$fala_items = [];
$fala_summary = [];

if (isset($miqaats['Fala_ni_Niyaz']) && is_array($miqaats['Fala_ni_Niyaz'])) {
  if (isset($miqaats["Fala_ni_Niyaz"][0]["year"])) {
    foreach ($miqaats['Fala_ni_Niyaz'] as $group) {
      $year = isset($group['year']) ? $group['year'] : '';
      $count = isset($group['count']) ? (int)$group['count'] : 0;
      $earliest = isset($group['earliest_date']) ? $group['earliest_date'] : '';
      $latest = isset($group['latest_date']) ? $group['latest_date'] : '';
      $fala_summary[] = [
        'year' => $year,
        'count' => $count,
        'earliest_date' => $earliest,
        'latest_date' => $latest,
        'is_generated' => !empty($group['is_generated']) ? true : false,
      ];
      if (!empty($group['miqaats']) && is_array($group['miqaats'])) {
        foreach ($group['miqaats'] as $m) {
          $fala_items[] = [
            'miqaat_index' => isset($m['miqaat_index']) ? $m['miqaat_index'] : '',
            'raza_index'   => isset($m['raza_index']) ? $m['raza_index'] : '',
            'miqaat_id'    => isset($m['miqaat_id']) ? $m['miqaat_id'] : '',
            'raza_id'      => isset($m['raza_id']) ? $m['raza_id'] : '',
            'miqaat_name'  => isset($m['miqaat_name']) ? $m['miqaat_name'] : '',
            'miqaat_date'  => isset($m['miqaat_date']) ? $m['miqaat_date'] : '',
            'miqaat_type'  => isset($m['miqaat_type']) ? $m['miqaat_type'] : '',
            'hijri_date'   => isset($m['hijri_date']) ? $m['hijri_date'] : '',
            'assigned_to'  => isset($m['assigned_to']) ? $m['assigned_to'] : 'Fala ni Niyaz',
            'details'      => 'Assigned to Fala ni Niyaz',
            'member_id'    => ''
          ];
        }
      }
    }
  } else {
    foreach ($miqaats["Fala_ni_Niyaz"] as $m) {
      $assignType = isset($m['assign_type']) ? strtolower($m['assign_type']) : '';
      if ($assignType === 'fala ni niyaz') {
        $fala_items[] = [
          'miqaat_index' => isset($m['miqaat_index']) ? $m['miqaat_index'] : '',
          'raza_index'   => isset($m['raza_index']) ? $m['raza_index'] : '',
          'miqaat_id'    => isset($m['miqaat_id']) ? $m['miqaat_id'] : '',
          'raza_id'      => isset($m['raza_id']) ? $m['raza_id'] : '',
          'miqaat_name'  => isset($m['miqaat_name']) ? $m['miqaat_name'] : '',
          'miqaat_date'  => isset($m['miqaat_date']) ? $m['miqaat_date'] : '',
          'miqaat_type'  => isset($m['miqaat_type']) ? $m['miqaat_type'] : '',
          'hijri_date'   => isset($m['hijri_date']) ? $m['hijri_date'] : '',
          'assigned_to'  => isset($m['assigned_to']) ? $m['assigned_to'] : 'Fala ni Niyaz',
          'details'      => 'Assigned to Fala ni Niyaz',
          'member_id'    => ''
        ];
      }
    }
  }
}
?>
<script>
  const FALA_NI_NIYAZ_ITEMS = <?php echo json_encode($fala_items); ?>;
  const FALA_NI_NIYAZ_SUMMARY = <?php echo json_encode($fala_summary); ?>;
  const IS_SHEHRULLAH = <?php echo json_encode(isset($miqaat_type) && (strtolower($miqaat_type) === 'shehrullah' || strtolower($miqaat_type) === 'ashara')); ?>;
  const CURRENT_MIQAAT_TYPE = <?php echo json_encode(isset($miqaat_type) ? $miqaat_type : ''); ?>;

  function formatLongDate(isoDate) {
    if (!isoDate) return '';
    const dt = new Date(isoDate.replace(/-/g, '/'));
    if (isNaN(dt)) return isoDate;
    const options = {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    };
    return dt.toLocaleDateString(undefined, options);
  }

  function escapeHtml(str) {
    if (str === undefined || str === null) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function buildFalaTable(items) {
    let summaryHtml = '';

    if (IS_SHEHRULLAH) {
      if (Array.isArray(FALA_NI_NIYAZ_SUMMARY) && FALA_NI_NIYAZ_SUMMARY.length) {
        const yearRows = FALA_NI_NIYAZ_SUMMARY.map(g => {
          const start = formatLongDate(g.earliest_date || '');
          const end = formatLongDate(g.latest_date || '');
          const isGenerated = g.is_generated ? true : false;
          let actionButtonHtml = '';
          if (isGenerated) {
            actionButtonHtml = `
              <span class="badge badge-success mr-2 p-2" style="font-size: 13px;">Takhmeen Done</span>
              <button type="button" class="btn btn-sm btn-outline-primary generate-new-active-members-btn"
                data-hijri_year="${escapeHtml(g.year)}"
                data-miqaat_type="${escapeHtml(CURRENT_MIQAAT_TYPE)}"
              ><i class="fa-solid fa-users"></i> Active Members</button>
            `;
          } else {
            actionButtonHtml = `
              <button type="button" class="btn btn-sm btn-primary generate-year-invoice-btn"
                data-hijri_year="${escapeHtml(g.year)}"
                data-count="${escapeHtml(String(g.count))}"
                data-range_start="${escapeHtml(start)}"
                data-range_end="${escapeHtml(end)}"
                data-miqaat_type="${escapeHtml(CURRENT_MIQAAT_TYPE)}"
              ><i class="fa-solid fa-calculator"></i> Do Takhmeen for this Year</button>
            `;
          }

          return `
            <tr>
              <td>${escapeHtml(g.year)}</td>
              <td>
                ${actionButtonHtml}
              </td>
            </tr>
          `;
        }).join('');
        return `
          ${summaryHtml}
          <table class="table table-bordered table-striped">
            <thead class="table-dark">
              <tr>
                <th>Hijri Year</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              ${yearRows}
            </tbody>
          </table>
        `;
      }
      return '<div class="alert alert-info">No Fala ni Niyaz miqaats found.</div>';
    }

    if (!items || !items.length) {
      return '<div class="alert alert-info">No Fala ni Niyaz miqaats found.</div>';
    }
    const rows = items.map((m, i) => {
      const miqaatDateStr = formatLongDate(m.miqaat_date);
      const hijriYear = (m.hijri_date || '').trim().split(' ').pop();
      const details = 'Assigned to Fala ni Niyaz';
      return `
        <tr>
          <td><b>${i + 1}</b></td>
          <td>${escapeHtml(miqaatDateStr)}</td>
          <td>${escapeHtml(m.hijri_date || '')}</td>
          <td>M#${escapeHtml(m.miqaat_id)}</td>
          <td>R#${escapeHtml(m.raza_id)}</td>
          <td><b>${escapeHtml(m.miqaat_name)}</b></td>
          <td>
            <button type="button" class="btn btn-sm btn-primary generate-invoice-btn d-inline-flex align-items-center" style="gap: 5px;"
              data-miqaat_index="${escapeHtml(m.miqaat_index)}"
              data-miqaat_id="${escapeHtml(m.miqaat_id)}"
              data-raza_index="${escapeHtml(m.raza_index)}"
              data-raza_id="${escapeHtml(m.raza_id)}"
              data-miqaat_name="${escapeHtml(m.miqaat_name)}"
              data-miqaat_date="${escapeHtml(miqaatDateStr)}"
              data-miqaat_type="${escapeHtml(m.miqaat_type)}"
              data-hijri_date="${escapeHtml(m.hijri_date)}"
              data-hijri_year="${escapeHtml(hijriYear)}"
              data-assigned_to="${escapeHtml(m.assigned_to || 'Fala ni Niyaz')}"
              data-individual_count="${escapeHtml(String(m.individual_count || ''))}"
              data-member_id=""
              data-details="${escapeHtml(details)}"
            ><i class="fa-solid fa-file-circle-plus"></i> Generate Invoice</button>
          </td>
        </tr>
      `;
    }).join('');

    return `
      ${summaryHtml}
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Hijri Date</th>
            <th>Miqaat ID</th>
            <th>Raza ID</th>
            <th>Miqaat Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          ${rows}
        </tbody>
      </table>
    `;
  }

  document.addEventListener('DOMContentLoaded', function() {
    const falaBtn = document.getElementById('fala-ni-niyaz-invoices');
    if (falaBtn) {

    falaBtn.addEventListener('click', function() {
      const genModalElLocal = document.getElementById('generateInvoiceModal');
      const genFormElLocal = document.getElementById('generateInvoiceForm');
      const wrapper = document.getElementById('falaNiyazTableWrapper');
      if (wrapper) {
        wrapper.innerHTML = buildFalaTable(FALA_NI_NIYAZ_ITEMS);

        wrapper.querySelectorAll('.generate-invoice-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            if (genFormElLocal) genFormElLocal.reset();
            const setVal = (id, val) => {
              const el = document.getElementById(id);
              if (el) el.value = val;
            };
            const setHtml = (id, html) => {
              const el = document.getElementById(id);
              if (el) el.innerHTML = html;
            };
            setHtml('modal_miqaat_details', '');
            setVal('modal_miqaat_index', '');
            setVal('modal_raza_index', '');
            setVal('input_miqaat_type', '');
            setVal('input_hijri_year', '');
            setVal('input_assigned_to', '');
            setVal('input_member_id', '');
            setVal('input_details', '');

            document.getElementById('modal_miqaat_index').value = btn.getAttribute('data-miqaat_index');
            document.getElementById('modal_raza_index').value = btn.getAttribute('data-raza_index');
            document.getElementById('input_miqaat_type').value = btn.getAttribute('data-miqaat_type');
            document.getElementById('input_hijri_year').value = btn.getAttribute('data-hijri_year');
            document.getElementById('input_assigned_to').value = btn.getAttribute('data-assigned_to');
            document.getElementById('input_member_id').value = btn.getAttribute('data-member_id') || '';
            document.getElementById('input_details').value = btn.getAttribute('data-details');

            const assignedRaw = (btn.getAttribute('data-assigned_to') || '').trim();
            const cnt = parseInt((btn.getAttribute('data-individual_count') || '0').toString(), 10) || 0;
            const assignedDisplay = (assignedRaw.toLowerCase() === 'individual' && cnt > 0)
              ? (assignedRaw + ' (' + cnt + ')')
              : assignedRaw;
            const razaId = (btn.getAttribute('data-raza_id') || '').trim();
            const detailsHtml = `
              <div class="miqaat-details-card">
                <div class="miqaat-details-group">
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Miqaat ID</div>
                    <div class="miqaat-detail-value">M#${escapeHtml(btn.getAttribute('data-miqaat_id'))}</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Raza ID</div>
                    <div class="miqaat-detail-value">${razaId ? 'R#' + escapeHtml(razaId) : '-'}</div>
                  </div>
                  <div class="miqaat-detail-item full-width">
                    <div class="miqaat-detail-label">Miqaat Name</div>
                    <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-miqaat_name'))}</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Date</div>
                    <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-miqaat_date'))}</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Hijri Date</div>
                    <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-hijri_date'))}</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Assigned To</div>
                    <div class="miqaat-detail-value">${escapeHtml(assignedDisplay)}</div>
                  </div>
                  <div class="miqaat-detail-item full-width">
                    <div class="miqaat-detail-label">Assignment Details</div>
                    <div class="miqaat-detail-value">${escapeHtml(btn.getAttribute('data-details'))}</div>
                  </div>
                </div>
              </div>
            `;
            setHtml('modal_miqaat_details', detailsHtml);

            // Show Generate modal on top of Fala modal (stacked)
            if (genModalElLocal) {
              const genModal = new bootstrap.Modal(genModalElLocal);
              genModal.show();
            }
          });
        });

        wrapper.querySelectorAll('.generate-year-invoice-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            // Gather details for confirmation
            const year = btn.getAttribute('data-hijri_year') || '';
            const count = btn.getAttribute('data-count') || '';
            const rangeStart = btn.getAttribute('data-range_start') || '';
            const rangeEnd = btn.getAttribute('data-range_end') || '';
            const mtype = btn.getAttribute('data-miqaat_type') || '';

            // Confirm with the user before proceeding
            const rangeText = (rangeStart && rangeEnd) ? `, Range: ${rangeStart} - ${rangeEnd}` : '';

            if (genFormElLocal) genFormElLocal.reset();
            const setVal = (id, val) => {
              const el = document.getElementById(id);
              if (el) el.value = val;
            };
            const setHtml = (id, html) => {
              const el = document.getElementById(id);
              if (el) el.innerHTML = html;
            };

            setVal('modal_miqaat_index', '');
            setVal('modal_raza_index', '');
            setVal('input_member_id', '');

            setVal('input_miqaat_type', mtype);
            setVal('input_hijri_year', year);
            setVal('input_assigned_to', 'Fala ni Niyaz');

            const details = `Yearly ${escapeHtml(mtype)} Fala ni Niyaz — Year: ${escapeHtml(year)} | Miqaat Count: ${escapeHtml(count)} | ${escapeHtml(rangeStart)} - ${escapeHtml(rangeEnd)}`;
            setVal('input_details', details);

            const html = `
              <div class="miqaat-details-card">
                <div class="miqaat-details-group">
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Type</div>
                    <div class="miqaat-detail-value">${escapeHtml(mtype)}</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Assigned To</div>
                    <div class="miqaat-detail-value">Fala ni Niyaz</div>
                  </div>
                  <div class="miqaat-detail-item">
                    <div class="miqaat-detail-label">Year</div>
                    <div class="miqaat-detail-value">${escapeHtml(year)}</div>
                  </div>
                </div>
              </div>
            `;
            setHtml('modal_miqaat_details', html);

            if (genModalElLocal) {
              const genModal = new bootstrap.Modal(genModalElLocal);
              genModal.show();
            }
          });
        });

        wrapper.querySelectorAll('.generate-new-active-members-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            const year = btn.getAttribute('data-hijri_year') || '';
            const mtype = btn.getAttribute('data-miqaat_type') || '';
            
            if (!confirm('Are you sure you want to generate Fala ni Niyaz invoices for newly added active members for ' + mtype + ' (' + year + ')?')) {
              return;
            }

            btn.disabled = true;
            btn.textContent = 'Generating...';

            fetch('<?php echo base_url("anjuman/generate_fala_ni_niyaz_for_new_hofs"); ?>', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `year=${encodeURIComponent(year)}&miqaat_type=${encodeURIComponent(mtype)}`
            })
            .then(async res => {
              const text = await res.text();
              let data;
              try {
                data = JSON.parse(text);
              } catch (e) {
                throw new Error('Response is not valid JSON. Raw response: ' + text.substring(0, 300));
              }
              if (!res.ok) {
                throw new Error(data.error || 'Server error ' + res.status);
              }
              return data;
            })
            .then(data => {
              if (data && data.status) {
                alert(`Successfully generated ${data.created_count} new invoices.`);
                window.location.reload();
              } else {
                alert('Generation failed: ' + (data.error || 'unknown error'));
                btn.disabled = false;
                btn.textContent = 'Active Members';
              }
            })
            .catch(err => {
              alert('An error occurred during generation: ' + err.message);
              btn.disabled = false;
              btn.textContent = 'Active Members';
            });
          });
        });
      }

      const falaEl = document.getElementById('falaNiyazModal');
      if (falaEl) {
        const falaModal = new bootstrap.Modal(falaEl);
        falaModal.show();
      }
      });
    }

    // Autocomplete and form submission logic for Niyaz Extra Contribution modal
    (function() {
      if (typeof jQuery === 'undefined') return;

      // Handle showing the modal for Create Extra Contribution button manually (supports Bootstrap 4 & 5)
      const extraModalEl = document.getElementById('createExtraContributionModal');
      let extraModal = null;
      if (extraModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        extraModal = new bootstrap.Modal(extraModalEl);
      }
      jQuery(document).on('click', '#create-extra-contribution-btn', function() {
        if (extraModal) {
          extraModal.show();
        } else {
          jQuery('#createExtraContributionModal').modal('show');
        }
      });

      jQuery(document).on('input', '#extra-member-autocomplete', function() {
        const query = jQuery(this).val();
        const listContainer = jQuery('#extra-member-autocomplete-list');
        const userIdInput = jQuery('#extra-user-id');
        if (!query || query.length < 2) {
          listContainer.empty().hide();
          userIdInput.val('');
          return;
        }
        jQuery.ajax({
          url: "<?php echo base_url('anjuman/searchmembers'); ?>",
          type: "POST",
          data: { query: query },
          dataType: "json",
          success: function(res) {
            if (res.success && Array.isArray(res.members) && res.members.length > 0) {
              let listHtml = '';
              res.members.forEach(function(member) {
                listHtml += `<button type="button" class="autocomplete-item extra-member-suggestion" data-id="${member.ITS_ID}" data-name="${member.Full_Name}">${member.Full_Name} (${member.ITS_ID})</button>`;
              });
              listContainer.html(listHtml).show();
            } else {
              listContainer.html('<div class="p-3 text-muted text-center" style="font-size:0.85rem;">No members found</div>').show();
            }
          },
          error: function() {
            listContainer.html('<div class="p-3 text-danger text-center" style="font-size:0.85rem;">Error searching members</div>').show();
          }
        });
      });

      // Select member from autocomplete
      jQuery(document).on('click', '.extra-member-suggestion', function() {
        const itsId = jQuery(this).attr('data-id');
        const name = jQuery(this).attr('data-name');
        jQuery('#extra-user-id').val(itsId);
        jQuery('#extra-member-autocomplete').val(name);
        jQuery('#extra-member-autocomplete-list').empty().hide();
      });

      // Hide suggestions on outside click
      jQuery(document).on('click', function(e) {
        if (!jQuery(e.target).closest('#extra-member-autocomplete, #extra-member-autocomplete-list').length) {
          jQuery('#extra-member-autocomplete-list').empty().hide();
        }
      });

      // Reset modal fields when hidden
      $('#createExtraContributionModal').on('hidden.bs.modal', function() {
        jQuery('#create-extra-contribution-form')[0].reset();
        jQuery('#extra-user-id').val('');
        jQuery('#extra-member-autocomplete-list').empty().hide();
      });

      // Form submission
      jQuery(document).on('submit', '#create-extra-contribution-form', function(e) {
        e.preventDefault();
        const btn = jQuery('#create-extra-contribution-submit');
        btn.prop('disabled', true);
        
        const formData = jQuery(this).serialize();
        jQuery.ajax({
          url: "<?php echo base_url('anjuman/addfmbgc_ajax'); ?>",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function(res) {
            if (res && res.success) {
              alert(res.message || 'Invoice created successfully.');
              const itsId = jQuery('#extra-user-id').val();
              const mtype = <?php 
                $mtype_num = 1;
                if (isset($miqaat_type)) {
                  if ($miqaat_type === 'Shehrullah') $mtype_num = 1;
                  elseif ($miqaat_type === 'Ashara') $mtype_num = 2;
                  elseif ($miqaat_type === 'General') $mtype_num = 3;
                  elseif ($miqaat_type === 'Ladies') $mtype_num = 4;
                }
                echo $mtype_num; 
              ?>;
              if (itsId) {
                window.location.href = `<?php echo base_url('anjuman/miqaatinvoicepayment'); ?>?miqaat_type=${mtype}&search=${encodeURIComponent(itsId)}`;
              } else {
                window.location.reload();
              }
            } else {
              alert(res.message || 'Failed to create invoice.');
              btn.prop('disabled', false);
            }
          },
          error: function() {
            alert('An error occurred. Please try again.');
            btn.prop('disabled', false);
          }
        });
      });
    })();
  });
</script>
<script>
  // Ensure user confirms before proceeding with YEAR-LEVEL (ALL families) invoices
  $(document).on('submit', '#generateInvoiceForm', function(e) {
    var mtype = ($('#input_miqaat_type').val() || '').toLowerCase();
    var year = $('#input_hijri_year').val() || '';
    var assignedTo = ($('#input_assigned_to').val() || '').toLowerCase();
    var miqaatIndex = $('#modal_miqaat_index').val() || '';
    var razaIndex = $('#modal_raza_index').val() || '';

    // Year-level flow has no specific miqaat/raza index and targets all families
    var isYearLevel = assignedTo === 'fala ni niyaz' &&
      (mtype === 'shehrullah' || mtype === 'ashara') &&
      miqaatIndex === '' &&
      razaIndex === '';

    if (isYearLevel) {
      var confirmMsg = 'This will create invoices for ALL families for ' + (mtype.charAt(0).toUpperCase() + mtype.slice(1)) + ' (' + year + ').\n\nDo you want to continue?';
      if (!window.confirm(confirmMsg)) {
        e.preventDefault();
        return false;
      }
    }
  });
</script>