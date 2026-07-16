<div class="container margintopcontainer pt-5" id="fmbTakhmeenApp">
  <?php
    $sum_total_takhmeen = 0;
    $takhmeen_done_count = 0;
    $takhmeen_not_done_count = 0;
    if (isset($all_user_fmb_takhmeen) && is_array($all_user_fmb_takhmeen)) {
      foreach ($all_user_fmb_takhmeen as $user) {
        if (isset($user['current_year_takhmeen'])) {
          $rawAmt = (string)($user['current_year_takhmeen']['amount'] ?? '');
          $amt = (float)preg_replace('/[^0-9.]/', '', $rawAmt);
          $sum_total_takhmeen += $amt;
          $takhmeen_done_count++;
        } else {
          $takhmeen_not_done_count++;
        }
      }
    }
  ?>
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
      --blue:        #1d4ed8;
      --sh:          0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
      --sh2:         0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
    }

    #fmbTakhmeenApp {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background-color: #faf7f0 !important;
    }

    /* Elegant Header Banner */
    .anj-header {
      margin-bottom: 24px;
    }
    .anj-header-inner {
      background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
      border-radius: 18px;
      padding: 24px 30px;
      position: relative;
      overflow: hidden;
      box-shadow: var(--sh);
      color: #fff;
    }
    .anj-eyebrow {
      font-size: .67rem;
      font-weight: 700;
      letter-spacing: 1.4px;
      text-transform: uppercase;
      color: rgba(255,255,255,.65);
      margin-bottom: 4px;
    }
    .anj-title {
      font-family: 'Literata', Georgia, serif;
      font-size: 1.7rem;
      font-weight: 600;
      color: #fff;
      margin: 0;
    }

    /* Filter Card */
    .miqaat-filter-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: var(--sh);
      padding: 16px;
      margin-bottom: 20px;
    }
    .form-group-premium {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .form-group-premium label {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--text-2);
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 0;
    }
    .form-control-premium {
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 0.85rem;
      color: var(--text-1);
      background: var(--surface-2);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      width: 100%;
      height: 38px;
      font-family: inherit;
    }
    .form-control-premium:focus {
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(184,134,11,.1);
      background: var(--surface);
    }

    /* Table Card */
    .miqaat-table-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: var(--sh);
      overflow: hidden;
      margin-bottom: 30px;
    }
    .miqaat-table-responsive {
      max-height: 70vh;
      overflow-y: auto;
      overflow-x: auto;
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

    /* Premium Buttons */
    .btn-premium {
      font-weight: 700;
      font-size: 0.82rem;
      padding: 8px 16px;
      border-radius: 10px;
      transition: all 0.2s;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
    }
    .btn-premium-primary {
      background: var(--gold);
      color: #fff;
    }
    .btn-premium-primary:hover {
      background: #8f6808;
      color: #fff;
      text-decoration: none;
    }
    .btn-premium-secondary {
      background: #fff;
      border: 1.5px solid var(--border);
      color: var(--text-2);
    }
    .btn-premium-secondary:hover {
      background: var(--gold-muted);
      border-color: var(--gold);
      color: var(--gold);
      text-decoration: none;
    }

    .btn-premium-action {
      border-radius: 8px;
      padding: 6px 10px;
      font-size: 0.78rem;
      border: none;
      color: #fff;
      transition: opacity 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 4px;
    }
    .btn-premium-action:hover {
      opacity: 0.85;
      color: #fff;
      text-decoration: none;
    }
    .btn-premium-action-add { background-color: var(--green) !important; }
    .btn-premium-action-view { background-color: var(--blue) !important; }
    .btn-premium-action-edit { background-color: var(--gold) !important; }
    .btn-premium-action-del  { background-color: #dc2626 !important; }

    /* Modals */
    .modal-content-premium {
      border-radius: 16px !important;
      border: 1px solid var(--border) !important;
      box-shadow: var(--sh2) !important;
      background: var(--surface) !important;
      overflow: hidden;
    }
    .modal-header-premium {
      background: linear-gradient(135deg, #78520a, var(--gold)) !important;
      color: #fff !important;
      border-bottom: none !important;
      padding: 16px 20px !important;
    }
    .modal-header-premium .close {
      color: #fff !important;
      opacity: 0.8 !important;
      text-shadow: none !important;
    }
    .modal-header-premium .close:hover {
      opacity: 1 !important;
    }
    .modal-body-premium {
      padding: 24px !important;
    }

    /* Table sorting styles */
    th.sortable {
      cursor: pointer;
      user-select: none;
      position: relative;
    }
    th.sortable .sort-indicator {
      font-size: 10px;
      margin-left: 4px;
      opacity: 0.8;
    }

    /* Hijri calendar (same UX as Create Miqaat) */
    #thaali-hijri-calendar .hijri-day.active { background: var(--gold); color: #fff; border-radius: 6px; }
    #thaali-hijri-calendar .hijri-day { width: 34px; padding: 4px 0; cursor: pointer; transition: background 0.15s; }
    #thaali-hijri-calendar .hijri-day:hover:not(.active) { background: var(--gold-muted); border-radius: 6px; }
    #thaali-hijri-calendar .hijri-week-grid { display: flex; flex-direction: column; width: 100%; }
    #thaali-hijri-calendar .hijri-row { display: grid; grid-template-columns: repeat(7, 1fr); margin-bottom: 4px; }
    #thaali-hijri-calendar .hijri-head { margin-bottom: 2px; }
    #thaali-hijri-calendar .hijri-cell { min-height: 38px; display: flex; align-items: center; justify-content: center; }
    #thaali-hijri-calendar .hijri-cell.empty { background: transparent; }
    #thaali-hijri-calendar .hijri-head-cell { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-2); }
    
    tr.clickable-row { cursor: pointer; }
  </style>

  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger" style="border-radius: 10px;">
      <?= $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success" style="border-radius: 10px;">
      <?= $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning" style="border-radius: 10px;">
      <?= $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>

  <!-- Actions Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="<?php echo base_url("admin"); ?>" class="btn-premium btn-premium-secondary" title="Back to Dashboard">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div class="d-flex align-items-center" style="gap:10px;">
      <a href="<?php echo base_url("admin/manageperdaythaalicost"); ?>" class="btn-premium btn-premium-primary">
        <i class="fa-solid fa-calculator mr-1"></i> Daily Thaali Cost
      </a>
      <button type="button" id="open-add-thaali-type" class="btn-premium btn-premium-primary" style="background: linear-gradient(135deg,#1a6645,#2a8a5c); border:none;">
        <i class="fa-solid fa-plus mr-1"></i> Add New Thaali
      </button>
    </div>
  </div>

  <?php
    // Use global filter meta provided by controller to avoid collapsing options after filtering
    $sectors_list = isset($filter_meta['sectors']) && is_array($filter_meta['sectors']) ? $filter_meta['sectors'] : [];
    $sub_sectors_list = isset($filter_meta['sub_sectors']) && is_array($filter_meta['sub_sectors']) ? $filter_meta['sub_sectors'] : [];
    if (!function_exists('selopt')) { function selopt($cur, $val){ return ((string)($cur ?? '') === (string)$val) ? 'selected' : ''; } }
  ?>

  <!-- Filters Row -->
  <div class="miqaat-filter-card">
    <form method="POST" action="<?php echo base_url("admin/filterfmbtakhmeen"); ?>" id="filter-form" class="form-row align-items-end">
      <div class="col-12 col-md-3 mb-2 mb-md-0">
        <div class="form-group-premium">
          <label>Name or ITS</label>
          <input type="text" name="member_name" id="member-name" class="apply-filter form-control-premium" placeholder="Filter by Name or ITS" value="<?php echo isset($member_name) ? $member_name : ""; ?>">
        </div>
      </div>
      <div class="col-12 col-md-3 mb-2 mb-md-0">
        <div class="form-group-premium">
          <label>Sector</label>
          <select name="sector" id="sector" class="apply-filter form-control-premium">
            <option value="">All Sectors</option>
            <?php foreach($sectors_list as $s): ?>
              <option value="<?php echo htmlspecialchars($s); ?>" <?php echo selopt($sector ?? '', $s); ?>><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-3 mb-2 mb-md-0">
        <div class="form-group-premium">
          <label>Sub Sector</label>
          <select name="sub_sector" id="sub-sector" class="apply-filter form-control-premium">
            <option value="">All Sub Sectors</option>
            <?php foreach($sub_sectors_list as $ss): ?>
              <option value="<?php echo htmlspecialchars($ss); ?>" <?php echo selopt($sub_sector ?? '', $ss); ?>><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-12 col-md-2 mb-2 mb-md-0">
        <div class="form-group-premium">
          <label>Year</label>
          <select name="filter_year" id="filter-year" class="apply-filter form-control-premium">
            <option value="">Select Year</option>
            <?php
              $CI =& get_instance();
              $CI->load->model('HijriCalendar');
              $yearRanges = $CI->HijriCalendar->get_distinct_composite_years();
              foreach ($yearRanges as $yr):
            ?>
              <option value="<?php echo $yr; ?>" <?php echo (isset($year) && $year == $yr) ? "selected" : ""; ?>><?php echo $yr; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-auto">
        <button type="button" id="clear-filters" class="btn btn-outline-secondary" title="Clear Filters" style="height: 38px; border-radius: 10px;">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
    </form>
  </div>

  <?php
  if (isset($all_user_fmb_takhmeen) && !empty($all_user_fmb_takhmeen)):
    $hijri_year = isset($all_user_fmb_takhmeen[0]["hijri_year"]) ? $all_user_fmb_takhmeen[0]["hijri_year"] : '';
  else:
    // Fallback to selected filter year if takhmeen list is empty
    $hijri_year = isset($year) ? $year : '';
  endif;
  ?>

  <!-- Header Panel -->
  <div class="anj-header text-center mb-4">
    <div class="anj-header-inner flex-column justify-content-center py-4">
      <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
      <h1 class="anj-title">FMB Thaali Takhmeen for <span style="color: #fffbdf;"><?php echo $hijri_year; ?></span></h1>
    </div>
  </div>

  <!-- Stats Cards Row -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
      <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:18px 20px; box-shadow:var(--sh); text-align:center; display:flex; flex-direction:column; gap:4px; height:100%; justify-content:center;">
        <span style="font-size:0.68rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.8px;">Takhmeen Done Members</span>
        <span style="font-size:1.6rem; font-weight:800; color:#1a6645;"><i class="fa-solid fa-circle-check mr-1" style="font-size:1.15rem;"></i> <?php echo number_format($takhmeen_done_count); ?></span>
      </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
      <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:18px 20px; box-shadow:var(--sh); text-align:center; display:flex; flex-direction:column; gap:4px; height:100%; justify-content:center;">
        <span style="font-size:0.68rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.8px;">Takhmeen Not Done Members</span>
        <span style="font-size:1.6rem; font-weight:800; color:#b91c1c;"><i class="fa-solid fa-circle-xmark mr-1" style="font-size:1.15rem;"></i> <?php echo number_format($takhmeen_not_done_count); ?></span>
      </div>
    </div>
    <div class="col-md-4">
      <div style="background:#fff; border:1px solid var(--border); border-radius:12px; padding:18px 20px; box-shadow:var(--sh); text-align:center; display:flex; flex-direction:column; gap:4px; height:100%; justify-content:center;">
        <span style="font-size:0.68rem; font-weight:700; color:var(--text-3); text-transform:uppercase; letter-spacing:0.8px;">Amount of Takhmeen Done</span>
        <span style="font-size:1.6rem; font-weight:800; color:var(--gold);">₹<?php echo number_format($sum_total_takhmeen, 0); ?></span>
      </div>
    </div>
  </div>

  <!-- ════════════════════════════════════════════════════════
       Thaali Types – Collapsible Section
       ════════════════════════════════════════════════════════ -->
  <?php $thaali_types = $thaali_types ?? []; ?>
  <div id="thaali-types-card" style="background:#fff;border:1px solid var(--border);border-radius:12px;box-shadow:var(--sh);margin-bottom:20px;overflow:hidden;">
    <!-- Section Header -->
    <div id="thaali-types-toggle" style="display:flex;align-items:center;justify-content:space-between;padding:14px 18px;cursor:pointer;user-select:none;border-bottom:1px solid var(--border);">
      <div style="display:flex;align-items:center;gap:10px;">
        <i class="fa-solid fa-layer-group" style="color:var(--gold);font-size:.9rem;"></i>
        <span style="font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-2);">Thaali Types</span>
        <span id="thaali-types-count-badge" style="background:var(--gold-muted);color:var(--gold);font-size:.7rem;font-weight:700;padding:1px 9px;border-radius:20px;"><?php echo count($thaali_types); ?></span>
      </div>
      <i id="thaali-types-chevron" class="fa-solid fa-chevron-up" style="color:var(--text-3);font-size:.8rem;transition:transform .25s;transform:rotate(180deg);"></i>
    </div>
    <!-- Collapsible Body -->
    <div id="thaali-types-body" style="display:none;transition:all .25s;overflow:hidden;">
      <div style="padding:16px 18px;">
        <?php if (empty($thaali_types)): ?>
          <p class="text-muted" style="font-size:.85rem;">No thaali types found. Click "Add New Thaali" to create one.</p>
        <?php else: ?>
        <table class="miqaat-table" id="thaali-types-table" style="margin:0;">
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>Thaali Type</th>
              <th>Description</th>
              <th>Amount (₹)</th>
              <th>Status</th>
              <th style="width:100px;">Actions</th>
            </tr>
          </thead>
          <tbody id="thaali-types-tbody">
          <?php foreach ($thaali_types as $i => $tt): ?>
            <tr id="thaali-type-row-<?php echo (int)$tt['id']; ?>"
                data-id="<?php echo (int)$tt['id']; ?>"
                data-name="<?php echo htmlspecialchars($tt['name'],ENT_QUOTES); ?>"
                data-description="<?php echo htmlspecialchars($tt['description'] ?? '',ENT_QUOTES); ?>"
                data-amount="<?php echo htmlspecialchars((string)($tt['amount'] ?? 0),ENT_QUOTES); ?>"
                data-status="<?php echo htmlspecialchars($tt['status'],ENT_QUOTES); ?>">
              <td><?php echo $i + 1; ?></td>
              <td style="font-weight:600;"><?php echo htmlspecialchars($tt['name']); ?></td>
              <td style="color:var(--text-2);font-size:.82rem;"><?php echo htmlspecialchars($tt['description'] ?? '—'); ?></td>
              <td style="font-weight:600;color:var(--green);"><?php echo (float)($tt['amount'] ?? 0) > 0 ? '₹'.number_format((float)$tt['amount'],0) : '—'; ?></td>
              <td>
                <span style="background:<?php echo $tt['status']==='Active'?'#d1fae5':'#f3f4f6'; ?>;color:<?php echo $tt['status']==='Active'?'#065f46':'#6b7280'; ?>;font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:20px;"><?php echo htmlspecialchars($tt['status']); ?></span>
              </td>
              <td>
                <div style="display:flex;gap:6px;align-items:center;">
                  <button class="edit-thaali-type-btn btn-premium-action btn-premium-action-edit" data-id="<?php echo (int)$tt['id']; ?>" title="Edit">
                    <i class="fa-solid fa-pencil"></i>
                  </button>
                  <button class="delete-thaali-type-btn btn-premium-action btn-premium-action-del" data-id="<?php echo (int)$tt['id']; ?>" title="Delete">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ════════ Add / Edit Thaali Type — Bootstrap Modal ════════ -->
  <div class="modal fade" id="thaaliTypeModal" tabindex="-1" role="dialog" aria-labelledby="thaaliTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title" id="thaaliTypeModalLabel"><i class="fa-solid fa-layer-group mr-2"></i> <span id="thaali-modal-title-text">Add New Thaali Type</span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="thaali-type-form">
            <input type="hidden" id="tt-edit-id" name="id" value="">
            <div class="form-group form-group-premium mb-3">
              <label for="tt-name">Thaali Type Name <span style="color:#e53e3e;">*</span></label>
              <input type="text" id="tt-name" name="name" class="form-control-premium" placeholder="Enter thaali type name" required autocomplete="off">
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="tt-description">Description (Optional)</label>
              <input type="text" id="tt-description" name="description" class="form-control-premium" placeholder="Enter description" autocomplete="off">
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="tt-amount">Amount (₹)</label>
              <input type="number" id="tt-amount" name="amount" class="form-control-premium" placeholder="Enter amount (optional)" min="0" step="0.01" value="0">
            </div>
            <div class="form-group form-group-premium mb-4">
              <label for="tt-status">Status <span style="color:#e53e3e;">*</span></label>
              <select id="tt-status" name="status" class="form-control-premium">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
            <div id="tt-form-error" style="display:none;background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5;border-radius:8px;padding:8px 12px;font-size:.82rem;margin-bottom:12px;"></div>
          </form>
        </div>
        <div class="modal-footer" style="padding:16px 20px;gap:10px;">
          <button type="button" class="btn-premium btn-premium-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="tt-save" class="btn-premium btn-premium-primary" style="background:linear-gradient(135deg,#78520a,#b8860b);">Save</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Table Card -->
  <div class="miqaat-table-card">
    <div class="miqaat-table-responsive">
      <table class="miqaat-table">
      <thead>
        <tr>
          <th data-no-sort>#</th>
          <th>ITS ID</th>
          <th>Name</th>
          <th>Sector</th>
          <th>Sub-Sector</th>
          <th>Takhmeen</th>
          <th>Thaali Day</th>
          <th>Assigned Thaali Days</th>
          <th data-no-sort>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($all_user_fmb_takhmeen)) {
          foreach ($all_user_fmb_takhmeen as $key => $user) {
        ?>
            <tr class="clickable-row" data-its-id="<?php echo htmlspecialchars((string)($user["ITS_ID"] ?? ''), ENT_QUOTES); ?>">
              <td><?php echo $key + 1; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["ITS_ID"]), ENT_QUOTES); ?>"><?php echo htmlspecialchars($user["ITS_ID"]); ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Full_Name"]), ENT_QUOTES); ?>"><?php echo $user["Full_Name"]; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Sector"]), ENT_QUOTES); ?>"><?php echo $user["Sector"]; ?></td>
              <td data-sort-value="<?php echo htmlspecialchars(strtolower($user["Sub_Sector"]), ENT_QUOTES); ?>"><?php echo $user["Sub_Sector"]; ?></td>
              <td data-sort-value="<?php echo isset($user["current_year_takhmeen"]) ? (float)$user["current_year_takhmeen"]["amount"] : 0; ?>">
                <?php if (isset($user["current_year_takhmeen"])):
                ?>
                  <p class="takhmeen-amount m-0 p-0"><?php echo $user["current_year_takhmeen"]["amount"]; ?></p>
                  <p class="financial-year pt-2 m-0">
                    <small class="text-secondary">(FY - <?php echo $user["current_year_takhmeen"]["year"]; ?>)</small>
                  </p>
                <?php
                else: ?>
                  Takhmeen Not Found
                <?php
                endif; ?>
              </td>
              <td>
                <?php
                  $per_day = isset($per_day_thaali_cost_amount) && is_numeric($per_day_thaali_cost_amount) ? (float)$per_day_thaali_cost_amount : 0;
                  if (isset($user['current_year_takhmeen']) && $per_day > 0) {
                    $rawAmt = (string)($user['current_year_takhmeen']['amount'] ?? '');
                    $amt = (float)preg_replace('/[^0-9.]/', '', $rawAmt);
                    $days = (int)floor($amt / $per_day);
                    echo htmlspecialchars((string)$days);
                  } else {
                    echo '-';
                  }
                ?>
              </td>
              <td data-sort-value="<?php echo isset($user['assigned_thaali_days']) ? (int)$user['assigned_thaali_days'] : 0; ?>">
                <?php $assignedCnt = isset($user['assigned_thaali_days']) ? (int)$user['assigned_thaali_days'] : 0; ?>
                <a href="#" class="view-assigned-thaali-days" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>" data-year="<?php echo htmlspecialchars($hijri_year, ENT_QUOTES); ?>">
                  <?php echo $assignedCnt; ?>
                </a>
              </td>
              <td>
                <?php
                  $allFamilyInactive = (($user['activity_status'] ?? '') === 'inactive' && ($user['active_family_count'] ?? 0) === 0);
                  $inactiveReason = $allFamilyInactive ? 'All family members are inactive' : '';
                ?>
                <button id="add-takhmeen" class="add-takhmeen btn-premium-action btn-premium-action-add mb-2" <?php echo !$allFamilyInactive ? 'data-toggle="modal" data-target="#add-takhmeen-container"' : ''; ?> data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?>" data-inactive="<?php echo $allFamilyInactive ? 'true' : 'false'; ?>" data-inactive-reason="<?php echo htmlspecialchars($inactiveReason); ?>" <?php echo $allFamilyInactive ? 'style="opacity:0.5; cursor:not-allowed;" title="All family members are inactive"' : ''; ?>><i class="fa-solid fa-plus"></i></button>

                <button id="view-takhmeen" class="view-takhmeen btn-premium-action btn-premium-action-view" data-toggle="modal" data-target="#view-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?>" data-takhmeens="<?php echo htmlspecialchars(json_encode($user["takhmeens"]), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-solid fa-eye"></i></button>

                <button id="edit-takhmeen" class="edit-takhmeen btn-premium-action btn-premium-action-edit" data-toggle="modal" data-target="#edit-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?>" data-takhmeens="<?php echo htmlspecialchars(json_encode($user["takhmeens"]), ENT_QUOTES, 'UTF-8'); ?>"><i class="fa-solid fa-pencil"></i></button>
              </td>
            </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</div>

<div class="modal fade" id="assigned-thaali-days-container" tabindex="-1" aria-labelledby="assigned-thaali-days-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title" id="assigned-thaali-days-label"><i class="fa-solid fa-calendar mr-2"></i> Assigned Thaali Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-premium">
        <p class="mb-2"><strong>Member Name:</strong> <span id="assigned-user-name">-</span></p>
        <p class="mb-3"><strong>FY:</strong> <span id="assigned-fy">-</span></p>
        <div id="assigned-dates-loading" class="text-secondary">Loading...</div>
        <div id="assigned-dates-empty" class="text-secondary d-none">No dates assigned.</div>
        <ul id="assigned-dates-list" class="pl-3 mb-0"></ul>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="add-takhmeen-container" tabindex="-1" aria-labelledby="add-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title" id="add-takhmeen-container-label"><i class="fa-solid fa-circle-plus mr-2"></i> Add FMB Takhmeen Amount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-premium">
        <form method="POST" action="<?php echo base_url("admin/addfmbtakhmeenamount"); ?>">
          <input type="hidden" name="user_id" id="user-id" />
          <p class="mb-2"><strong>Member Name:</strong> <span id="user-name">-</span></p>
          <div class="form-group mb-3">
            <label for="takhmeen-year" style="font-size: 0.75rem; font-weight: 700; color: var(--text-2); text-transform: uppercase;">Year</label>
            <select name="fmb_takhmeen_year" id="takhmeen-year" class="form-control-premium" required>
              <?php foreach ($yearRanges as $yr): ?>
                <option value="<?php echo $yr; ?>" <?php echo selopt($hijri_year ?? '', $yr); ?>><?php echo $yr; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="takhmeen-amount" class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--text-2); text-transform: uppercase;">Takhmeen Amount (₹)</label>
            <input type="number" id="takhmeen-amount" name="fmb_takhmeen_amount" class="form-control-premium" placeholder="Enter Takhmeen Amount" min="1" required>
          </div>
          <div class="form-group mb-3">
            <label for="thaali-date" class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--text-2); text-transform: uppercase;">Thaali days</label>
            <div class="input-group mb-2">
              <input type="date" id="thaali-date" class="form-control-premium" min="<?php echo !empty($hijri_calendar_min_greg) ? htmlspecialchars($hijri_calendar_min_greg, ENT_QUOTES) : '2000-01-01'; ?>" max="<?php echo !empty($hijri_calendar_max_greg) ? htmlspecialchars($hijri_calendar_max_greg, ENT_QUOTES) : '2100-12-31'; ?>" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" />
              <div class="input-group-append">
                <button type="button" id="add-thaali-date-btn" class="btn btn-premium btn-premium-primary" style="border-top-left-radius: 0; border-bottom-left-radius: 0; height: 38px;">Add</button>
              </div>
            </div>
            <p id="thaali-date-both-display" class="form-text text-muted mb-2 small">Selected: -</p>

            <div class="border rounded p-3 bg-light" id="thaali-hijri-selector-wrapper" style="border-radius: 12px !important; border-color: var(--border) !important;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="font-weight-bold m-0" style="font-size: 0.75rem; color: var(--text-2);">Select Hijri Date</label>
                <div>
                  <select id="thaali-hijri-year-select" class="form-control-premium form-select form-select-sm d-inline-block w-auto" aria-label="Hijri Year" style="min-width:90px; height: 30px; padding: 2px 8px; font-size: 0.75rem;"></select>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <button type="button" id="thaali-hijri-prev" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">«</button>
                <span id="thaali-hijri-current" class="mx-2 font-weight-bold small text-secondary"></span>
                <button type="button" id="thaali-hijri-next" class="btn btn-sm btn-outline-secondary" style="border-radius: 6px;">»</button>
              </div>
              <div id="thaali-hijri-calendar" class="hijri-cal-grid mb-2"></div>
              <small id="thaali-hijri-help" class="text-muted d-block small">Click a Hijri day to auto-fill the Gregorian date above.</small>
            </div>

            <small id="thaali-error" class="text-danger d-none">Please select date</small>
            <div id="thaali-dates-list" class="mt-2 d-flex flex-wrap gap-1"></div>
            <input type="hidden" name="thaali_dates" id="thaali-dates-hidden" />
          </div>
          <div class="d-flex align-items-center justify-content-between mt-4">
            <button type="submit" id="add-takhmeen-btn" class="btn-premium btn-premium-primary">Add Takhmeen</button>
            <p id="validate-takhmeen" class="text-secondary m-0 small"></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view-takhmeen-container" tabindex="-1" aria-labelledby="view-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title" id="view-takhmeen-container-label"><i class="fa-solid fa-clock-rotate-left mr-2"></i> View FMB Takhmeen History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-premium">
        <p class="mb-3"><b>Member Name: </b><span id="view-user-name" class="text-secondary">Member Name</span></p>
        <div class="miqaat-table-card">
          <div class="table-responsive" style="overflow-x:auto;">
            <table class="miqaat-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Takhmeen Year</th>
                  <th>Amount</th>
                  <th>Thaali Days</th>
                  <th>Assigned Thaali Days</th>
                  <th>Update Remark</th>
                </tr>
              </thead>
              <tbody id="takhmeen-history-body">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-takhmeen-container" tabindex="-1" aria-labelledby="edit-takhmeen-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content modal-content-premium">
      <div class="modal-header modal-header-premium">
        <h5 class="modal-title" id="edit-takhmeen-label"><i class="fa-solid fa-pen-to-square mr-2"></i> Edit FMB Takhmeen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-premium">
        <p class="mb-3"><b>Member Name: </b><span id="edit-user-name" class="text-secondary">Member Name</span></p>
        <div class="miqaat-table-card">
          <div class="card-header bg-light border-bottom py-2">
            <h6 class="m-0 text-center font-weight-bold text-secondary">Takhmeen History</h6>
          </div>
          <div class="table-responsive" style="overflow-x:auto;">
            <table class="miqaat-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Takhmeen Year</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="edit-takhmeen-body">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const DEFAULT_FMB_FY = "<?php echo htmlspecialchars((string)($hijri_year ?? ''), ENT_QUOTES); ?>";
  const hijriGregMin = <?php echo json_encode(isset($hijri_calendar_min_greg) ? $hijri_calendar_min_greg : null); ?>;
  const hijriGregMax = <?php echo json_encode(isset($hijri_calendar_max_greg) ? $hijri_calendar_max_greg : null); ?>;

  function formatInrAmount(val) {
    const n = parseFloat(String(val ?? '').replace(/[^0-9.]/g, ''));
    if (isNaN(n)) return '0';
    // Amounts are stored as whole rupees; format as integer without rounding to significant digits.
    return new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(Math.round(n));
  }

  function isIsoInRange(isoDate){
    const d = String(isoDate || '');
    if (!d.match(/^\d{4}-\d{2}-\d{2}$/)) return false;
    if (hijriGregMin && d < String(hijriGregMin)) return false;
    if (hijriGregMax && d > String(hijriGregMax)) return false;
    return true;
  }

  function hijriRangeMsg(){
    if (hijriGregMin && hijriGregMax) return 'Please select a date between ' + formatThaaliDateDisplay(hijriGregMin) + ' and ' + formatThaaliDateDisplay(hijriGregMax) + '.';
    return 'Please select a valid date.';
  }

  function resetAddTakhmeenModal() {
    // Reset to Add mode defaults
    $("#add-takhmeen-container-label").text("Add FMB Takhmeen Amount");
    $("#add-takhmeen-btn").show();
    $("#takhmeen-amount").prop("disabled", false).val("");
    $("#validate-takhmeen").html("");
    $("#thaali-dates-list").empty();
    $("#thaali-dates-hidden").val("");
    $("#thaali-date").val("");
    $("#thaali-date-both-display").text('Selected: -');
    $("#thaali-date").prop('disabled', false);
    $("#add-thaali-date-btn").prop('disabled', false);
    $("#thaali-error").addClass("d-none").text("Please select date");
    $("#takhmeen-year").prop("disabled", false);

    // Default year in popup should match the current FY shown on page
    if (DEFAULT_FMB_FY) {
      $('#takhmeen-year').val(DEFAULT_FMB_FY);
    }
  }

  // === Hijri label helper for date chips (Gregorian + Hijri) ===
  const __hijriPartsCache = {};
  function hijriLabelFromParts(parts) {
    if (!parts) return '';
    const d = String(parts.hijri_day || '').trim();
    const m = String(parts.hijri_month_name || parts.hijri_month || '').trim();
    const y = String(parts.hijri_year || '').trim();
    return [d, m, y].filter(Boolean).join(' ');
  }
  function getHijriLabelForGregIso(gregIso) {
    const iso = String(gregIso || '').trim();
    if (!iso) return Promise.resolve('');
    if (__hijriPartsCache[iso]) return Promise.resolve(__hijriPartsCache[iso]);
    return fetch('<?php echo base_url('common/get_hijri_parts'); ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: new URLSearchParams({ greg_date: iso })
    })
      .then(r => r.json())
      .then(resp => {
        if (resp && resp.status === 'success' && resp.parts) {
          const lbl = hijriLabelFromParts(resp.parts);
          __hijriPartsCache[iso] = lbl;
          return lbl;
        }
        __hijriPartsCache[iso] = '';
        return '';
      })
      .catch(() => '');
  }

  function setChipTextWithHijri($chip, gregIso) {
    const iso = String(gregIso || '').trim();
    const g = iso ? formatThaaliDateDisplay(iso) : '';

    // If chip has a nested label, update only that
    const $label = ($chip && $chip.find) ? $chip.find('.thaali-date-label').first() : null;
    const $target = ($label && $label.length) ? $label : $chip;

    // First paint with greg, then enhance with hijri
    $target.text(g || '-');
    if (!iso) return;
    getHijriLabelForGregIso(iso).then(lbl => {
      if (lbl) {
        $target.text(g + ' | ' + lbl);
      } else {
        $target.text(g);
      }
    });
  }

  // Hijri calendar selector for thaali-date (linked to Gregorian date)
  (function initThaaliHijriSelector(){
    const calContainer = document.getElementById('thaali-hijri-calendar');
    const currentLbl = document.getElementById('thaali-hijri-current');
    const prevBtn = document.getElementById('thaali-hijri-prev');
    const nextBtn = document.getElementById('thaali-hijri-next');
    const yearSelect = document.getElementById('thaali-hijri-year-select');
    const wrapper = document.getElementById('thaali-hijri-selector-wrapper');
    const help = document.getElementById('thaali-hijri-help');
    const gregInput = document.getElementById('thaali-date');
    const bothDisplay = document.getElementById('thaali-date-both-display');
    if (!calContainer || !gregInput || !bothDisplay) return;

    let monthsCache = {}; // {year: [{id,name}]}
    let daysCache = {}; // { 'year-month': [ {day,hijri_date,greg_date} ] }
    let years = [];
    let currentYear = null;
    let currentMonth = 1;
    let pendingSelectGreg = null; // iso Y-m-d to highlight

    function isoTodayLocal() {
      const d = new Date();
      const yyyy = d.getFullYear();
      const mm = String(d.getMonth() + 1).padStart(2, '0');
      const dd = String(d.getDate()).padStart(2, '0');
      return `${yyyy}-${mm}-${dd}`;
    }

    function fetchJSON(url) {
      return fetch(url).then(r => r.json());
    }

    function safeText(v){
      return (v === null || typeof v === 'undefined') ? '' : String(v);
    }

    function monthName(year, month) {
      const ms = monthsCache[year] || [];
      const f = ms.find(m => parseInt(m.id) === parseInt(month));
      return f ? f.name : ('Month ' + month);
    }

    function setBothDisplay(gregIso, hijriLabel){
      const gIso = safeText(gregIso).trim();
      const g = gIso ? (typeof formatThaaliDateDisplay === 'function' ? formatThaaliDateDisplay(gIso) : gIso) : '';
      const h = safeText(hijriLabel).trim();
      if (g && h) {
        bothDisplay.textContent = 'Selected: ' + g + ' | ' + h;
      } else if (g) {
        bothDisplay.textContent = 'Selected: ' + g;
      } else {
        bothDisplay.textContent = 'Selected: -';
      }
    }

    function fyStartYearFromString(fy) {
      const s = String(fy || '').trim();
      if (!s) return null;
      const p = s.split('-');
      const y = parseInt(p[0], 10);
      return Number.isFinite(y) ? String(y) : null;
    }

    function ensureYearOptionExists(y) {
      const yy = String(y || '').trim();
      if (!yy || !yearSelect) return;
      const exists = [...yearSelect.options].some(o => String(o.value) === yy);
      if (exists) return;

      // Add missing year and keep options sorted desc (same as API)
      const opt = document.createElement('option');
      opt.value = yy;
      opt.textContent = yy;
      yearSelect.appendChild(opt);

      const vals = [...yearSelect.options].map(o => String(o.value));
      vals.sort((a,b) => parseInt(b,10) - parseInt(a,10));
      yearSelect.innerHTML = vals.map(v => `<option value="${v}">${v}</option>`).join('');

      // Keep internal years array in sync for navigation
      if (!years.includes(yy)) {
        years.push(yy);
        years.sort((a,b) => parseInt(b,10) - parseInt(a,10));
      }
    }

    function syncCalendarToTodayMonth() {
      const todayIso = isoTodayLocal();
      pendingSelectGreg = todayIso;
      return fetch('<?php echo base_url('common/get_hijri_parts'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams({ greg_date: todayIso })
      })
        .then(r => r.json())
        .then(resp => {
          if (resp && resp.status === 'success' && resp.parts) {
            const parts = resp.parts;
            ensureYearOptionExists(parts.hijri_year);
            currentYear = String(parts.hijri_year);
            currentMonth = parseInt(parts.hijri_month, 10);
            if (yearSelect) yearSelect.value = currentYear;
          }
        })
        .catch(() => {});
    }

    function loadYears() {
      return fetchJSON('<?php echo base_url('common/get_hijri_years'); ?>').then(d => {
        if (d && d.status === 'success') {
          years = d.years || [];
          // Default Hijri year should align with FY in popup (start year)
          if (!currentYear) {
            const fyStart = fyStartYearFromString(DEFAULT_FMB_FY);
            currentYear = (fyStart && years.includes(fyStart)) ? fyStart : (years[0] || null);
          }
          if (yearSelect && years.length) {
            yearSelect.innerHTML = years.map(y => `<option value="${y}">${y}</option>`).join('');
            yearSelect.value = currentYear;
          }
        }
      });
    }

    function loadMonths(year) {
      if (!year) return Promise.resolve([]);
      if (monthsCache[year]) return Promise.resolve(monthsCache[year]);
      return fetchJSON('<?php echo base_url('common/get_hijri_months'); ?>?year=' + encodeURIComponent(year)).then(d => {
        if (d && d.status === 'success') monthsCache[year] = d.months || [];
        return monthsCache[year] || [];
      });
    }

    function loadDays(year, month) {
      const k = year + '-' + month;
      if (daysCache[k]) return Promise.resolve(daysCache[k]);
      return fetchJSON('<?php echo base_url('common/get_hijri_days'); ?>?year=' + encodeURIComponent(year) + '&month=' + encodeURIComponent(month)).then(d => {
        if (d && d.status === 'success') daysCache[k] = d.days || [];
        return daysCache[k] || [];
      });
    }

    function highlightGreg(iso) {
      if (!iso) return;
      const btn = calContainer.querySelector('[data-greg="' + iso + '"]');
      if (btn) {
        [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
        btn.classList.add('active');
      }
    }

    function applyAutoSelect() {
      if (!pendingSelectGreg) return;
      highlightGreg(pendingSelectGreg);
      pendingSelectGreg = null;
    }

    function gregWeekday(iso) {
      const d = new Date(String(iso).replace(/-/g, '/'));
      return d.getDay();
    }

    function render() {
      if (!currentYear) {
        calContainer.innerHTML = '<div class="text-muted small">No Hijri calendar data.</div>';
        return;
      }
      loadMonths(currentYear)
        .then(() => loadDays(currentYear, currentMonth))
        .then(days => {
          if (currentLbl) currentLbl.textContent = monthName(currentYear, currentMonth) + ' ' + currentYear;
          calContainer.innerHTML = '';
          if (!days || !days.length) {
            calContainer.innerHTML = '<div class="text-muted small">No days.</div>';
            return;
          }

          const table = document.createElement('div');
          table.className = 'hijri-week-grid';
          const headers = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
          const headRow = document.createElement('div');
          headRow.className = 'hijri-row hijri-head';
          headers.forEach(h => {
            const hd = document.createElement('div');
            hd.className = 'hijri-cell hijri-head-cell fw-semibold text-center';
            hd.textContent = h;
            headRow.appendChild(hd);
          });
          table.appendChild(headRow);

          let weekRow = document.createElement('div');
          weekRow.className = 'hijri-row';
          let cellsInRow = 0;
          const firstWeekday = gregWeekday(days[0].greg_date);
          for (let i = 0; i < firstWeekday; i++) {
            const empty = document.createElement('div');
            empty.className = 'hijri-cell empty';
            weekRow.appendChild(empty);
            cellsInRow++;
          }

          days.forEach(d => {
            if (cellsInRow === 7) {
              table.appendChild(weekRow);
              weekRow = document.createElement('div');
              weekRow.className = 'hijri-row';
              cellsInRow = 0;
            }
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-primary hijri-day';
            btn.textContent = d.day;
            btn.dataset.greg = d.greg_date;
            btn.dataset.hijri = d.hijri_date;
            btn.addEventListener('click', () => {
              const iso = d.greg_date;
              if (!isIsoInRange(iso)) {
                alert(hijriRangeMsg());
                return;
              }
              gregInput.value = iso;
              gregInput.dispatchEvent(new Event('change', { bubbles: true }));

              const hp = String(d.hijri_date || '').split('-');
              if (hp.length === 3) {
                const label = hp[0] + ' ' + monthName(currentYear, currentMonth) + ' ' + hp[2];
                setBothDisplay(iso, label);
              } else {
                setBothDisplay(iso, '');
              }
              [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
              btn.classList.add('active');
            });
            const cell = document.createElement('div');
            cell.className = 'hijri-cell text-center';
            cell.appendChild(btn);
            weekRow.appendChild(cell);
            cellsInRow++;
          });

          if (cellsInRow > 0 && cellsInRow < 7) {
            for (let i = cellsInRow; i < 7; i++) {
              const empty = document.createElement('div');
              empty.className = 'hijri-cell empty';
              weekRow.appendChild(empty);
            }
          }
          table.appendChild(weekRow);
          calContainer.appendChild(table);
          applyAutoSelect();
        });
    }

    // Add a show/hide toggle button (same as Create Miqaat)
    (function ensureToggle() {
      if (!wrapper || document.getElementById('toggle-thaali-hijri-cal')) return;
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.id = 'toggle-thaali-hijri-cal';
      btn.className = 'btn btn-sm btn-outline-primary mb-2';
      btn.setAttribute('aria-expanded', 'false');
      btn.textContent = 'Show Hijri Calendar';
      // Insert after header row
      if (wrapper.children.length > 0) {
        wrapper.insertBefore(btn, wrapper.children[1] || null);
      } else {
        wrapper.insertBefore(btn, wrapper.firstChild);
      }
      calContainer.style.display = 'none';
      if (help) help.style.display = 'none';
      btn.addEventListener('click', () => {
        const hidden = calContainer.style.display === 'none';
        if (hidden) {
          calContainer.style.display = '';
          if (help) help.style.display = '';
          btn.textContent = 'Hide Hijri Calendar';
          btn.setAttribute('aria-expanded', 'true');
        } else {
          calContainer.style.display = 'none';
          if (help) help.style.display = 'none';
          btn.textContent = 'Show Hijri Calendar';
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    })();

    function navigate(delta) {
      currentMonth += delta;
      if (currentMonth < 1) {
        currentMonth = 12;
        const idx = years.indexOf(currentYear);
        if (idx > 0) currentYear = years[idx - 1];
      } else if (currentMonth > 12) {
        currentMonth = 1;
        const idx = years.indexOf(currentYear);
        if (idx < years.length - 1) currentYear = years[idx + 1];
      }
      if (yearSelect) yearSelect.value = currentYear;
      if (!monthsCache[currentYear]) {
        loadMonths(currentYear).then(() => render());
      } else {
        render();
      }
    }

    function syncCalendarToGregorian(iso) {
      const gregIso = String(iso || '').trim();
      if (!gregIso) {
        setBothDisplay('', '');
        return;
      }
      pendingSelectGreg = gregIso;

      // Fetch hijri parts for display and jumping calendar
      fetch('<?php echo base_url('common/get_hijri_parts'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams({ greg_date: gregIso })
      }).then(r => r.json()).then(resp => {
        if (resp && resp.status === 'success' && resp.parts) {
          const parts = resp.parts;
          const hLabel = (parts.hijri_day ? String(parts.hijri_day) : '')
            + ' ' + (parts.hijri_month_name ? String(parts.hijri_month_name) : (parts.hijri_month ? String(parts.hijri_month) : ''))
            + ' ' + (parts.hijri_year ? String(parts.hijri_year) : '');
          setBothDisplay(gregIso, hLabel.trim());
          ensureYearOptionExists(parts.hijri_year);
          currentYear = String(parts.hijri_year);
          currentMonth = parseInt(parts.hijri_month);
          if (yearSelect) yearSelect.value = currentYear;
        } else {
          setBothDisplay(gregIso, 'Hijri Date: Not found');
        }
        render();
      }).catch(() => {
        setBothDisplay(gregIso, 'Hijri Date: Error fetching');
        render();
      });
    }

    // Boot
    loadYears()
      .then(() => loadMonths(currentYear))
      .then(() => {
        if (gregInput.value) {
          syncCalendarToGregorian(gregInput.value);
        } else {
          // Default view: show current (today's) Hijri month/year
          syncCalendarToTodayMonth().then(() => render());
        }
      });

    // Event wiring
    gregInput.addEventListener('change', function(){
      syncCalendarToGregorian(this.value);
    });

    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', function(){ navigate(-1); });
      nextBtn.addEventListener('click', function(){ navigate(1); });
    }
    if (yearSelect) {
      yearSelect.addEventListener('change', function(){
        const newYear = this.value;
        if (newYear && newYear !== currentYear) {
          currentYear = newYear;
          loadMonths(currentYear).then(ms => {
            const exists = (ms || []).some(m => parseInt(m.id) === parseInt(currentMonth));
            if (!exists) currentMonth = (ms && ms.length) ? parseInt(ms[0].id) : 1;
            render();
          });
        }
      });
    }
  })();

  function openAddTakhmeenAsEdit(userId, userName, year) {
    resetAddTakhmeenModal();
    $("#add-takhmeen-container-label").text("Edit FMB Takhmeen Amount");
    $("#user-id").val(userId);
    $("#user-name").html(userName);

    if (year) {
      $("#takhmeen-year").val(year);
    }
    // Trigger validation/update UI for selected year
    $("#takhmeen-year").trigger("change");
    // Show already selected/assigned thaali dates for this FY
    if (userId && year) {
      loadAssignedThaaliDatesIntoForm(userId, year);
    }
    // When editing a specific year via history, prevent changing year accidentally
    if (year) {
      $("#takhmeen-year").prop("disabled", true);
    }

    $("#add-takhmeen-container").modal("show");
  }

  $(".add-takhmeen").on("click", function(e) {
    if ($(this).attr("data-inactive") === "true") {
      e.preventDefault();
      e.stopPropagation();
      alert("This member is inactive. Reason: " + ($(this).attr("data-inactive-reason") || "Inactive"));
      return false;
    }
    resetAddTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $("#user-id").val($userId);
    $("#user-name").html($userName);
  });

  // Ensure modal resets when closed so next open is clean.
  $(document).on('hidden.bs.modal', '#add-takhmeen-container', function(){
    resetAddTakhmeenModal();
  });

  $("#takhmeen-year").on("change", function(e) {
    $userId = $("#user-id").val();
    $takhmeen_year = $(this).val();

    // If there are already assigned thaali dates for this FY, show them in the list
    if ($userId && $takhmeen_year) {
      loadAssignedThaaliDatesIntoForm($userId, $takhmeen_year);
    }

    $.ajax({
      url: "<?php echo base_url("admin/validatefmbtakhmeen"); ?>",
      type: "POST",
      data: {
        "user_id": $userId,
        "year": $takhmeen_year
      },
      success: function(res) {
        if (res) {
          $res = JSON.parse(res);
          if ($res.success) {
            $("#add-takhmeen-btn").hide();
            $("#takhmeen-amount").prop("disabled", true);
            $("#validate-takhmeen").html(`
              <div class="alert alert-info">
                <p>Takhmeen already exists for this year.</p>
                <label for="edit-takhmeen-amount" class="form-label">Update Amount</label>
                <input type="number" id="edit-takhmeen-amount" 
                  class="form-control mb-2" 
                  value="${$res.user_takhmeen.total_amount}" min="1">
                <label for=\"edit-takhmeen-remark\" class=\"form-label\">Edit Remark</label>
                <textarea id=\"edit-takhmeen-remark\" class=\"form-control mb-2\" rows=\"2\" placeholder=\"Enter reason for update\" required></textarea>
                <button type="button" id="save-takhmeen-btn" class="btn btn-primary">Save</button>
              </div>
            `);
            $(document).off("click", "#save-takhmeen-btn").on("click", "#save-takhmeen-btn", function() {
              $newAmount = $("#edit-takhmeen-amount").val();
              let remark = $("#edit-takhmeen-remark").val();
              remark = String(remark || '').trim();
              if (!remark) {
                alert('Please enter edit remark before saving.');
                return;
              }
              $.ajax({
                url: "<?php echo base_url("admin/updatefmbtakhmeen/0"); ?>",
                type: "POST",
                data: {
                  "user_id": $userId,
                  "year": $takhmeen_year,
                  "fmb_takhmeen_amount": $newAmount,
                  "edit_remark": remark
                },
                success: function(updateRes) {
                  if (updateRes) {
                    let uRes = JSON.parse(updateRes);
                    if (uRes.success) {
                      alert("Takhmeen updated successfully!");
                      location.reload();
                    } else {
                      alert("Update failed: " + uRes.message);
                    }
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  console.error('Update AJAX Error:', textStatus, errorThrown, jqXHR);
                }
              });
            });
          }
        } else {
          $("#add-takhmeen-btn").show();
          $("#validate-takhmeen").html("");
          $("#takhmeen-amount").prop("disabled", false);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown, jqXHR);
      }
    });
  });

  function renderThaaliDatesListReadOnly(dates) {
    const $list = $('#thaali-dates-list');
    $list.empty();
    const safeDates = Array.isArray(dates) ? dates : [];
    if (!safeDates.length) {
      // Show explicit empty state so user knows we tried loading
      $list.append($('<div>').addClass('text-secondary').text('No dates selected.'));
      $('#thaali-dates-hidden').val('[]');
      return;
    }
    $('#thaali-dates-hidden').val(JSON.stringify(safeDates));
    safeDates.forEach(function(d){
      const row = $('<div>')
        .addClass('thaali-date-row d-flex align-items-center mb-1')
        .css('gap','0.5rem');

      const dateText = $('<span>')
        .addClass('badge badge-info badge-pill d-inline-flex align-items-center')
        .css('gap', '0.35rem');
      const label = $('<span>')
        .addClass('thaali-date-label')
        .text(formatThaaliDateDisplay(d));
      const remove = $('<a>')
        .attr('href', '#')
        .addClass('remove-assigned-thaali-date text-white')
        .attr('data-date', d)
        .html('&times;')
        .css({ 'line-height': '1', 'font-weight': 'bold', 'font-size': '1.2em', 'text-decoration': 'none' });
      dateText.append(label, remove);
      setChipTextWithHijri(dateText, d);

      row.append(dateText);
      $list.append(row);
    });
  }

  $(document).on('click', '.remove-assigned-thaali-date', function(e){
    e.preventDefault();
    const dateVal = String($(this).data('date') || '').trim();
    const userId = String($('#user-id').val() || '').trim();
    const year = String($('#takhmeen-year').val() || '').trim();
    if (!dateVal || !userId || !year) return;

    if (!confirm('Remove thaali date ' + formatThaaliDateDisplay(dateVal) + ' ?')) {
      return;
    }

    const $link = $(this);
    $link.css('pointer-events','none');
    $.ajax({
      url: "<?php echo base_url('admin/removefmbassignedthaalidate'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year, date: dateVal },
      success: function(res){
        let payload = res;
        try { if (typeof res === 'string') payload = JSON.parse(res); } catch (e) { payload = { success: false, message: 'Invalid response' }; }
        if (!payload || payload.success === false) {
          alert((payload && payload.message) ? payload.message : 'Failed to remove date');
          $link.css('pointer-events','');
          return;
        }

        // Update hidden list
        let existing = [];
        try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
        existing = Array.isArray(existing) ? existing : [];
        const idx = existing.indexOf(dateVal);
        if (idx !== -1) existing.splice(idx, 1);
        $('#thaali-dates-hidden').val(JSON.stringify(existing));

        // Remove row
        $link.closest('.thaali-date-row').remove();
        if (!existing.length) {
          $('#thaali-dates-list').empty().append($('<div>').addClass('text-secondary').text('No dates selected.'));
        }
      },
      error: function(){
        alert('Failed to remove date');
        $link.css('pointer-events','');
      }
    });
  });

  function loadAssignedThaaliDatesIntoForm(userId, year) {
    // This is for displaying existing assigned dates (read-only) in the Add/Edit modal.
    const $list = $('#thaali-dates-list');
    $list.empty().append($('<div>').addClass('text-secondary').text('Loading...'));
    $.ajax({
      url: "<?php echo base_url('admin/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year },
      success: function(res){
        let payload = res;
        try {
          if (typeof res === 'string') payload = JSON.parse(res);
        } catch (e) {
          payload = { success: false, dates: [], message: 'Invalid response' };
        }

        if (!payload || payload.success === false) {
          const msg = payload && payload.message ? String(payload.message) : 'No dates selected.';
          $list.empty().append($('<div>').addClass('text-secondary').text(msg));
          $('#thaali-dates-hidden').val('[]');
          return;
        }

        const dates = payload && payload.dates ? payload.dates : [];
        renderThaaliDatesListReadOnly(dates);
      },
      error: function(){
        // Show empty state on failure
        $list.empty().append($('<div>').addClass('text-secondary').text('No dates selected.'));
        $('#thaali-dates-hidden').val('[]');
      }
    });
  }

  $(".view-takhmeen").on("click", function(e) {
    resetViewTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $takhmeens = $(this).data("takhmeens");
    const perDayCostByYear = <?php echo json_encode(isset($per_day_thaali_cost_by_year) && is_array($per_day_thaali_cost_by_year) ? $per_day_thaali_cost_by_year : new stdClass()); ?>;

    $("#view-user-name").html($userName);

    if ($takhmeens && $takhmeens.length > 0) {
      let historyHtml = '';
      $takhmeens.forEach((takhmeen, index) => {
        const assignedCnt = (takhmeen && typeof takhmeen.assigned_thaali_days !== 'undefined') ? Number(takhmeen.assigned_thaali_days) : 0;
        let thaaliDaysDisplay = '-';
        const yearKey = takhmeen && typeof takhmeen.year !== 'undefined' ? String(takhmeen.year) : '';
        const perDayCost = (yearKey && perDayCostByYear && typeof perDayCostByYear[yearKey] !== 'undefined') ? Number(perDayCostByYear[yearKey]) : 0;
        if (perDayCost && Number(perDayCost) > 0 && takhmeen && typeof takhmeen.amount !== 'undefined') {
          const amtNum = parseFloat(String(takhmeen.amount).replace(/[^0-9.]/g, ''));
          if (!isNaN(amtNum)) {
            thaaliDaysDisplay = Math.floor(amtNum / Number(perDayCost));
          }
        }
        historyHtml += `
          <tr>
            <td>${index + 1}</td>
            <td>${takhmeen.year}</td>
            <td>&#8377;${formatInrAmount(takhmeen.amount)}</td>
            <td>${thaaliDaysDisplay}</td>
            <td>
              <a href="#" class="view-assigned-thaali-days" data-user-id="${$userId}" data-user-name="${$userName}" data-year="${takhmeen.year}">${assignedCnt}</a>
            </td>
            <td>${takhmeen.remark ? $('<div/>').text(takhmeen.remark).html() : '-'}</td>
          </tr>
        `;
      });
      $("#takhmeen-history-body").html(historyHtml);
    } else {
      $("#takhmeen-history-body").html('<tr><td colspan="6" class="text-center">No Takhmeen history found.</td></tr>');
    }
  });

  $(".edit-takhmeen").on("click", function(e) {
    resetViewTakhmeenModal();
    $userId = $(this).data("user-id");
    $userName = $(this).data("user-name");
    $takhmeens = $(this).data("takhmeens");

    $("#edit-user-name").html($userName);

    if ($takhmeens && $takhmeens.length > 0) {
      let historyHtml = '';
      $takhmeens.forEach((takhmeen, index) => {
        historyHtml += `
        <tr>
          <td>${index + 1}</td>
          <td>${takhmeen.year}</td>
          <td>&#8377;${formatInrAmount(takhmeen.amount)}</td>
          <td>
            <button class="btn btn-sm btn-primary edit-single-takhmeen" 
              data-user-id="${$userId}" 
              data-year="${takhmeen.year}" 
              data-amount="${takhmeen.amount}">
              <i class="fa-solid fa-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-single-takhmeen" 
              data-user-id="${$userId}" 
              data-year="${takhmeen.year}">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
      });

      $("#edit-takhmeen-body").html(historyHtml);
    } else {
      $("#edit-takhmeen-body").html('<tr><td colspan="4" class="text-center">No Takhmeen history found.</td></tr>');
    }
  });

  // ===== EDIT BUTTON FLOW =====
  $(document).on("click", ".edit-single-takhmeen", function(e) {
    e.preventDefault();

    // Open popup like the Add Takhmeen modal, prefilled for this year.
    const $btn = $(this);
    const userId = $btn.data('user-id');
    const year = $btn.data('year');
    const userName = String($('#edit-user-name').text() || '').trim();

    // Close history modal first to avoid stacked modal/backdrop issues.
    // Bind handler before hiding to avoid missing the event.
    $('#edit-takhmeen-container').off('hidden.bs.modal.__openEdit').on('hidden.bs.modal.__openEdit', function(){
      $(this).off('hidden.bs.modal.__openEdit');
      openAddTakhmeenAsEdit(userId, userName, year);
    });
    $('#edit-takhmeen-container').modal('hide');
  });

  $(document).on("click", ".delete-single-takhmeen", function(e) {
    e.preventDefault();
    let $btn = $(this);
    let $row = $btn.closest("tr");
    let userId = $btn.data("user-id");
    let year = $btn.data("year");

    if (!confirm(`Are you sure you want to delete takhmeen for year ${year}?`)) {
      return;
    }

    $.ajax({
      url: "<?php echo base_url('admin/deletefmbtakhmeen'); ?>",
      type: "POST",
      data: {
        user_id: userId,
        year: year
      },
      success: function(res) {
        let parsed = JSON.parse(res);
        if (parsed.success) {
          alert("Takhmeen deleted successfully!");
          $row.remove();
          window.location.reload();
        } else {
          alert("Delete failed: " + parsed.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown);
      }
    });
  });

  function resetViewTakhmeenModal() {
    $("#edit-user-name").html("Member Name");
    $("#edit-takhmeen-body").html("");

    $("#view-user-name").html("Member Name");
    $("#takhmeen-history-body").html("");
  }

  $(document).ready(
    function() {
      $takhmeenAmount = $(".takhmeen-amount");
      for (const index in $takhmeenAmount) {
        $indexTakhmeenAmount = $($takhmeenAmount[Number(index)]);
        $takhmeenAmountText = $indexTakhmeenAmount.html();
        $indianFormatTA = "&#8377;" + formatInrAmount($takhmeenAmountText);
        $indexTakhmeenAmount.html($indianFormatTA);
      };
    }()
  );

  $(".apply-filter").on("change", function() {
    $("#filter-form").submit();
  });

  // Clear filters: reset inputs/selects and submit
  $(document).on('click', '#clear-filters', function(){
    const form = document.getElementById('filter-form');
    if(!form) return;
    // Reset known fields
    const nameInput = form.querySelector('#member-name');
    const sectorSel = form.querySelector('#sector');
    const subSectorSel = form.querySelector('#sub-sector');
    const yearSel = form.querySelector('#filter-year');
    if(nameInput) nameInput.value = '';
    if(sectorSel) sectorSel.value = '';
    if(subSectorSel) subSectorSel.value = '';
    if(yearSel) yearSel.value = '';
    form.submit();
  });

  // Auto-submit form on input text/select, with a small debounce for text
  let typingTimer;
  const doneTypingInterval = 500;
  $('#filter-form input[type="text"]').on('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
      $(this).closest('form').submit();
    }, doneTypingInterval);
  });
  $('#filter-form input[type="text"]').on('keydown', function() {
    clearTimeout(typingTimer);
  });
  $('#filter-form select').on('change', function() {
    $(this).closest('form').submit();
  });

  $(".alert").delay(3000).fadeOut(500);

  // Client-side sortable headers for the takhmeen table (table-only change)
  (function(){
    const table = document.querySelector('.miqaat-table-card .miqaat-table');
    if(!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if(!thead || !tbody) return;

    thead.querySelectorAll('th').forEach((th, idx) => {
      if(th.hasAttribute('data-no-sort')) return;
      th.classList.add('sortable');
      const original = th.innerHTML.trim();
      th.innerHTML = '<span class="sort-label">'+original+'</span><span class="sort-indicator" aria-hidden="true"></span>';
      th.setAttribute('role','button'); th.setAttribute('tabindex','0');
      th.addEventListener('click', () => toggleSort(idx, th));
      th.addEventListener('keydown', e => { if(['Enter',' '].includes(e.key)){ e.preventDefault(); toggleSort(idx, th); }});
    });

    function getCellValue(tr, index){
      const cells = tr.querySelectorAll('td');
      if(!cells[index]) return '';
      return cells[index].getAttribute('data-sort-value') || cells[index].textContent.trim();
    }
    function inferType(val){
      if(/^\d+(?:\.\d+)?$/.test(val)) return 'number';
      if(/^\d{4}-\d{2}-\d{2}$/.test(val)) return 'date';
      return 'text';
    }
    function norm(val){
      const t = inferType(val);
      if(t==='number') return parseFloat(val);
      if(t==='date') return new Date(val).getTime();
      return String(val).toLowerCase();
    }
    function toggleSort(idx, th){
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      thead.querySelectorAll('th.sortable').forEach(h => { h.dataset.sortDir=''; const ind=h.querySelector('.sort-indicator'); if(ind) ind.textContent=''; });
      th.dataset.sortDir = newDir; const ind=th.querySelector('.sort-indicator'); if(ind) ind.textContent = newDir==='asc' ? '▲' : '▼';

      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.sort((a,b) => {
        const va = norm(getCellValue(a, idx));
        const vb = norm(getCellValue(b, idx));
        if(va < vb) return newDir==='asc' ? -1 : 1;
        if(va > vb) return newDir==='asc' ? 1 : -1;
        return 0;
      });

      rows.forEach(r => tbody.appendChild(r));
      // Renumber first column
      let i=1; tbody.querySelectorAll('tr').forEach(r => { const td = r.querySelector('td'); if(td) td.textContent = i++; });
    }
  })();
  // === Thaali Date add/remove handlers ===
  // Thaali Dates are optional for adding takhmeen. Keep the field-level validation only when
  // clicking "Add" to append a date.
  $(document).on('submit', '#add-takhmeen-container form', function() {
    $('#thaali-error').addClass('d-none');
  });
  function formatThaaliDateDisplay(isoDate) {
    // Expects YYYY-MM-DD from <input type="date">. Return DD-MM-YYYY.
    const m = String(isoDate || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!m) return isoDate;
    return `${m[3]}-${m[2]}-${m[1]}`;
  }

  function addThaaliDate(dateVal) {
    if (!dateVal) {
      $('#thaali-error').removeClass('d-none').text('Please select date');
      return;
    }
    if (!isIsoInRange(dateVal)) {
      alert(hijriRangeMsg());
      return;
    }
    $('#thaali-error').addClass('d-none');

    let existing = [];
    try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
    if (existing.indexOf(dateVal) !== -1) {
      alert('Date already added');
      $('#thaali-date').val('');
      $('#thaali-date-both-display').text('Selected: -');
      return;
    }
    existing.push(dateVal);
    $('#thaali-dates-hidden').val(JSON.stringify(existing));

    // Each selected date renders on its own line (ek ke niche ek)
    const row = $('<div>').addClass('thaali-date-row d-flex align-items-center mb-1').css('gap','0.5rem');
    const dateText = $('<span>')
      .addClass('badge badge-info badge-pill d-inline-flex align-items-center')
      .css('gap', '0.35rem');
    const label = $('<span>')
      .addClass('thaali-date-label')
      .text(formatThaaliDateDisplay(dateVal));
    const remove = $('<a>')
      .attr('href', '#')
      .addClass('remove-thaali-date text-white')
      .attr('data-date', dateVal)
      .html('&times;')
      .css({ 'line-height': '1', 'font-weight': 'bold', 'font-size': '1.2em', 'text-decoration': 'none' });
    dateText.append(label, remove);
    setChipTextWithHijri(dateText, dateVal);
    row.append(dateText);
    $('#thaali-dates-list').append(row);
    $('#thaali-date').val('');
    $('#thaali-date-both-display').text('Selected: -');
  }

  function isExistingEditMode(){
    return $('#validate-takhmeen #save-takhmeen-btn').length > 0;
  }

  function addAssignedThaaliDateAjax(dateVal){
    if (!dateVal) {
      $('#thaali-error').removeClass('d-none').text('Please select date');
      return;
    }
    if (!isIsoInRange(dateVal)) {
      alert(hijriRangeMsg());
      return;
    }
    $('#thaali-error').addClass('d-none');

    const userId = String($('#user-id').val() || '').trim();
    const year = String($('#takhmeen-year').val() || '').trim();
    if (!userId || !year) {
      alert('Missing user/year');
      return;
    }

    $.ajax({
      url: "<?php echo base_url('admin/addfmbassignedthaalidate'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year, date: dateVal },
      success: function(res){
        let payload = res;
        try { if (typeof res === 'string') payload = JSON.parse(res); } catch (e) { payload = { success: false, message: 'Invalid response' }; }
        if (!payload || payload.success === false) {
          alert((payload && payload.message) ? payload.message : 'Failed to add date');
          return;
        }
        // Refresh the fetched list
        loadAssignedThaaliDatesIntoForm(userId, year);
        $('#thaali-date').val('');
      },
      error: function(){
        alert('Failed to add date');
      }
    });
  }

  $(document).on('click', '#add-thaali-date-btn', function(e) {
    e.preventDefault();
    const v = $('#thaali-date').val();
    if (isExistingEditMode()) {
      addAssignedThaaliDateAjax(v);
    } else {
      addThaaliDate(v);
    }
  });

  // Auto-add when a date is selected from the picker
  $(document).on('change', '#thaali-date', function() {
    const v = $(this).val();
    if (isExistingEditMode()) {
      addAssignedThaaliDateAjax(v);
    } else {
      addThaaliDate(v);
    }
  });

  $(document).on('click', '.remove-thaali-date', function(e) {
    e.preventDefault();
    const d = $(this).data('date');
    let existing = [];
    try { existing = JSON.parse($('#thaali-dates-hidden').val() || '[]'); } catch(err) { existing = []; }
    const idx = existing.indexOf(d);
    if (idx !== -1) existing.splice(idx, 1);
    $('#thaali-dates-hidden').val(JSON.stringify(existing));
    $(this).closest('.thaali-date-row').remove();
  });

  // === Assigned Thaali days popup ===
  function formatIsoToDmy(isoDate){
    const m = String(isoDate || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if(!m) return isoDate;
    return `${m[3]}-${m[2]}-${m[1]}`;
  }

  function openAssignedThaaliDaysModal(userId, userName, year){
    $('#assigned-user-name').text(userName || '-');
    $('#assigned-fy').text(year || '-');
    $('#assigned-dates-list').empty();
    $('#assigned-dates-empty').addClass('d-none').text('No dates assigned.');
    $('#assigned-dates-loading').removeClass('d-none');

    // Show assigned modal (supports being opened over another modal)
    var $assigned = $('#assigned-thaali-days-container');

    // Ensure modal is under body to avoid z-index/overflow quirks
    if ($assigned.parent()[0] !== document.body) {
      $assigned.appendTo(document.body);
    }

    // Compute next z-index above any currently-visible modal
    var maxZ = 1040;
    $('.modal.show').each(function(){
      var z = parseInt($(this).css('z-index'), 10);
      if (!isNaN(z)) maxZ = Math.max(maxZ, z);
    });
    $assigned.css('z-index', maxZ + 10);

    $assigned.off('shown.bs.modal.fmbstack').on('shown.bs.modal.fmbstack', function(){
      // Raise the newest backdrop just under the assigned modal
      var $backdrop = $('.modal-backdrop').not('.modal-stack').last();
      $backdrop.css('z-index', maxZ + 5).addClass('modal-stack');
      // Keep body locked when multiple modals are open
      $('body').addClass('modal-open');
    });
    $assigned.off('hidden.bs.modal.fmbstack').on('hidden.bs.modal.fmbstack', function(){
      $(this).css('z-index', '');
      // If another modal is still open, keep modal-open
      if ($('.modal.show').length) {
        $('body').addClass('modal-open');
      }
    });

    $assigned.modal('show');

    $.ajax({
      url: "<?php echo base_url('admin/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      data: { user_id: userId, year: year },
      success: function(res){
        let payload = res;
        try {
          if (typeof res === 'string') payload = JSON.parse(res);
        } catch (err) {
          payload = { success: false, dates: [], message: 'Invalid response' };
        }

        $('#assigned-dates-loading').addClass('d-none');
        const dates = payload && payload.dates ? payload.dates : [];
        if (!dates.length) {
          $('#assigned-dates-empty').removeClass('d-none');
          return;
        }
        dates.forEach(function(d){
          const $li = $('<li>').text(formatIsoToDmy(d));
          $('#assigned-dates-list').append($li);
          // Enhance with hijri label
          getHijriLabelForGregIso(d).then(function(lbl){
            if (lbl) {
              $li.text(formatIsoToDmy(d) + ' | ' + lbl);
            }
          });
        });
      },
      error: function(){
        $('#assigned-dates-loading').addClass('d-none');
        $('#assigned-dates-empty').removeClass('d-none').text('Failed to load dates.');
      }
    });
  }

  $(document).on('click', '.view-assigned-thaali-days', function(e){
    e.preventDefault();
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    const year = $(this).data('year');

    openAssignedThaaliDaysModal(userId, userName, year);
  });

  $(document).ready(function() {
    $(document).on('click', 'tr.clickable-row', function(e) {
      if ($(e.target).is('button, a, input, select, option, i') || $(e.target).closest('button, a, input, select, option').length) {
        return;
      }
      var itsId = $(this).data('its-id');
      if (itsId) {
        window.location.href = '<?php echo base_url("admin/viewmember/"); ?>' + itsId;
      }
    });
  });

  /* ═══════════════════════════════════════════════════════════
   * Thaali Types – Collapsible + CRUD
   * ═══════════════════════════════════════════════════════════ */
  (function($) {
    var BASE = '<?php echo base_url(); ?>';

    /* ── Collapse / Expand ── */
    var _collapsed = true;
    $('#thaali-types-toggle').on('click', function() {
      _collapsed = !_collapsed;
      if (_collapsed) {
        $('#thaali-types-body').slideUp(200);
        $('#thaali-types-chevron').css('transform','rotate(180deg)');
      } else {
        $('#thaali-types-body').slideDown(200);
        $('#thaali-types-chevron').css('transform','rotate(0deg)');
      }
    });

    /* ── Panel Open / Close (Bootstrap Modal) ── */
    function openModal(mode, rowData) {
      $('#tt-edit-id').val('');
      $('#tt-name').val('');
      $('#tt-description').val('');
      $('#tt-amount').val('0');
      $('#tt-status').val('Active');
      $('#tt-form-error').hide().text('');
      if (mode === 'edit' && rowData) {
        $('#thaali-modal-title-text').text('Edit Thaali Type');
        $('#tt-edit-id').val(rowData.id);
        $('#tt-name').val(rowData.name);
        $('#tt-description').val(rowData.description);
        $('#tt-amount').val(rowData.amount || '0');
        $('#tt-status').val(rowData.status);
      } else {
        $('#thaali-modal-title-text').text('Add New Thaali Type');
      }
      $('#thaaliTypeModal').modal('show');
    }

    function closeModal() {
      $('#thaaliTypeModal').modal('hide');
    }

    $('#open-add-thaali-type').on('click', function() { openModal('add'); });
    $('#tt-save').on('click', function() { $('#thaali-type-form').submit(); });

    /* ── Edit button ── */
    $(document).on('click', '.edit-thaali-type-btn', function(e) {
      e.stopPropagation();
      var row = $(this).closest('tr');
      openModal('edit', {
        id:          row.data('id'),
        name:        row.data('name'),
        description: row.data('description'),
        amount:      row.data('amount'),
        status:      row.data('status')
      });
    });

    /* ── Delete button ── */
    $(document).on('click', '.delete-thaali-type-btn', function(e) {
      e.stopPropagation();
      var id   = $(this).data('id');
      var row  = $('#thaali-type-row-' + id);
      var name = row.data('name');
      if (!confirm('Delete thaali type "' + name + '"? This cannot be undone.')) return;
      $.post(BASE + 'admin/delete_thaali_type', { id: id }, function(res) {
        if (res.success) {
          row.fadeOut(250, function() {
            $(this).remove();
            reIndexRows();
            updateCountBadge();
          });
        } else {
          alert(res.message || 'Could not delete thaali type.');
        }
      }, 'json').fail(function() { alert('Server error. Please try again.'); });
    });

    /* ── Form submit (Add / Edit) ── */
    $('#thaali-type-form').on('submit', function(e) {
      e.preventDefault();
      var id     = $.trim($('#tt-edit-id').val());
      var name   = $.trim($('#tt-name').val());
      var desc   = $.trim($('#tt-description').val());
      var amount = $.trim($('#tt-amount').val()) || '0';
      var status = $('#tt-status').val();

      if (!name) {
        showPanelError('Thaali Type Name is required.'); return;
      }

      var url      = id ? BASE + 'admin/edit_thaali_type' : BASE + 'admin/add_thaali_type';
      var postData = { name: name, description: desc, amount: amount, status: status };
      if (id) postData.id = id;

      $('#tt-save').prop('disabled', true).text('Saving…');
      $.post(url, postData, function(res) {
        $('#tt-save').prop('disabled', false).text('Save');
        if (res.success) {
          closeModal();
          var formattedAmount = Number(amount) > 0 ? '₹' + new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2 }).format(Number(amount)) : '—';
          if (id) {
            /* Update existing row in place */
            var row = $('#thaali-type-row-' + id);
            row.data('name', name).data('description', desc).data('amount', amount).data('status', status)
               .attr('data-name', name).attr('data-description', desc).attr('data-amount', amount).attr('data-status', status);
            row.find('td:nth-child(2)').text(name);
            row.find('td:nth-child(3)').text(desc || '—');
            row.find('td:nth-child(4)').text(formattedAmount);
            var badge = row.find('td:nth-child(5) span');
            badge.text(status).css({
              background: status === 'Active' ? '#d1fae5' : '#f3f4f6',
              color:      status === 'Active' ? '#065f46' : '#6b7280'
            });
          } else {
            /* Append new row */
            var newId  = res.id;
            var rowNum = $('#thaali-types-tbody tr').length + 1;
            var delBtn = '<button class="delete-thaali-type-btn btn-premium-action btn-premium-action-del" data-id="' + newId + '" title="Delete"><i class="fa-solid fa-trash"></i></button>';
            var html = '<tr id="thaali-type-row-' + newId + '" data-id="' + newId + '" data-name="' + $('<div>').text(name).html() + '" data-description="' + $('<div>').text(desc).html() + '" data-amount="' + amount + '" data-status="' + status + '">'
              + '<td>' + rowNum + '</td>'
              + '<td style="font-weight:600;">' + $('<div>').text(name).html() + '</td>'
              + '<td style="color:var(--text-2);font-size:.82rem;">' + ($('<div>').text(desc || '—').html()) + '</td>'
              + '<td style="font-weight:600;color:var(--green);">' + formattedAmount + '</td>'
              + '<td><span style="background:' + (status==='Active'?'#d1fae5':'#f3f4f6') + ';color:' + (status==='Active'?'#065f46':'#6b7280') + ';font-size:.7rem;font-weight:700;padding:2px 10px;border-radius:20px;">' + status + '</span></td>'
              + '<td><div style="display:flex;gap:6px;align-items:center;">'
              + '<button class="edit-thaali-type-btn btn-premium-action btn-premium-action-edit" data-id="' + newId + '" title="Edit"><i class="fa-solid fa-pencil"></i></button>'
              + delBtn
              + '</div></td>'
              + '</tr>';
            $('#thaali-types-tbody').append(html);
            updateCountBadge();
            /* If table was hidden (no rows) show it */
            if ($('#thaali-types-table').length === 0) { location.reload(); }
          }
        } else {
          showPanelError(res.message || 'An error occurred.');
        }
      }, 'json').fail(function() {
        $('#tt-save').prop('disabled', false).text('Save');
        showPanelError('Server error. Please try again.');
      });
    });

    function showPanelError(msg) {
      $('#tt-form-error').text(msg).show();
    }

    function reIndexRows() {
      $('#thaali-types-tbody tr').each(function(i) {
        $(this).find('td:first').text(i + 1);
      });
    }

    function updateCountBadge() {
      var n = $('#thaali-types-tbody tr').length;
      $('#thaali-types-count-badge').text(n);
    }

  })(jQuery);
</script>