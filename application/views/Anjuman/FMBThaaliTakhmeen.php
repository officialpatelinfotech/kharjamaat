<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">

<style>
  .hidden {
    display: none;
  }
  
  /* ═══════════════════════════════════════════════════
     GOLD THEME — scoped to #anjApp
  ═══════════════════════════════════════════════════ */
  #anjApp {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1a1610;
    background: #faf7f0;
    min-height: 100vh;
    padding-bottom: 60px;
  }
  #anjApp {
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
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
  }

  /* ── Header ── */
  #anjApp .anj-header {
    margin-bottom: 24px;
  }
  #anjApp .anj-header-inner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 20px;
    padding: 20px 24px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: var(--shadow-sm);
  }
  #anjApp .anj-header-inner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }
  #anjApp .anj-title-group {
    position: relative;
    z-index: 1;
  }
  #anjApp .anj-eyebrow {
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: rgba(255,255,255,.65);
    margin-bottom: 4px;
  }
  #anjApp .anj-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    margin: 0;
  }

  /* ── Back button ── */
  #anjApp .btn-back {
    border-color: var(--border);
    color: var(--text-2);
    font-weight: 700;
    font-size: 0.8rem;
    padding: 8px 16px;
    border-radius: 10px;
    background: var(--surface);
    transition: all 0.15s;
  }
  #anjApp .btn-back:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
  }

  /* ── Content Card wrapper ── */
  #anjApp .content-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow 0.2s;
  }
  #anjApp .content-card:hover {
    box-shadow: var(--shadow);
  }

  /* ── Filter Bar ── */
  #anjApp .filter-bar {
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border);
    padding: 18px;
  }
  #anjApp .filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 6px;
    display: block;
  }
  #anjApp .filter-control {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 8px 12px;
    font-family: inherit;
    font-size: 0.82rem;
    color: var(--text-1);
    background: var(--surface);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    width: 100%;
    height: 38px;
  }
  #anjApp .filter-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184,134,11,.1);
  }

  /* ── Cost Header Panel ── */
  #anjApp .cost-header {
    background: var(--gold-muted);
    border-bottom: 1.5px solid var(--border);
    padding: 12px 18px;
    font-size: 0.85rem;
    color: var(--text-1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  /* ── Table scroll container ── */
  #anjApp .table-scroll-fixed {
    max-height: calc(100vh - 340px);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* ── Premium table styling ── */
  #anjApp .gc-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
  }
  #anjApp .gc-table th {
    background: var(--text-1);
    color: #fff;
    font-weight: 700;
    font-size: 0.76rem;
    padding: 14px 12px;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  #anjApp .gc-table td {
    padding: 12px;
    font-size: 0.82rem;
    color: var(--text-2);
    vertical-align: middle;
    border-bottom: 1px solid var(--border-light);
    background: var(--surface);
  }
  #anjApp .gc-table tr:hover td {
    background: var(--surface-2);
  }
  #anjApp .gc-table tr:last-child td {
    border-bottom: none;
  }

  #anjApp .btn-table-primary {
    background: var(--gold-muted);
    color: var(--gold);
    border: 1px solid rgba(184,134,11,0.25);
  }
  #anjApp .btn-table-primary:hover {
    background: #edd98a;
    color: var(--gold);
    box-shadow: var(--shadow-sm);
  }
  #anjApp .btn-table-success {
    background: var(--green-bg);
    color: var(--green);
    border: 1px solid rgba(26,102,69,0.2);
  }
  #anjApp .btn-table-success:hover {
    background: #cbead7;
    color: var(--green);
    box-shadow: var(--shadow-sm);
  }
  #anjApp .btn-table-info {
    background: var(--blue-bg);
    color: var(--blue);
    border: 1px solid rgba(29,78,216,0.15);
  }
  #anjApp .btn-table-info:hover {
    background: #cbe3fe;
    color: var(--blue);
    box-shadow: var(--shadow-sm);
  }
  #anjApp .btn-table-danger {
    background: var(--red-bg);
    color: var(--red);
    border: 1px solid rgba(185,28,28,0.15);
  }
  #anjApp .btn-table-danger:hover {
    background: #fcd5d5;
    color: var(--red);
    box-shadow: var(--shadow-sm);
  }

  /* Table sorting styles */
  #anjApp th.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
  }
  #anjApp th.sortable .sort-indicator {
    font-size: 10px;
    margin-left: 4px;
    opacity: 0.8;
  }

  /* Modals Premium Styling */
  .modal-content-premium {
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-lg);
    background: var(--surface);
    overflow: hidden;
  }
  .modal-header-premium {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
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
  
  tr.clickable-row { cursor: pointer; }
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container-fluid px-md-5 pb-4">
    <!-- Session Messages -->
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;">
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo base_url("anjuman/fmbthaali") ?>" class="btn btn-back">
        <i class="fa-solid fa-arrow-left mr-1"></i> Back
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">FMB Thaali Takhmeen</h1>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="content-card">
      <!-- Filters Panel -->
      <div id="takhmeen-filters" class="filter-bar">
        <div class="form-row">
          <div class="col-12 col-md-3 mb-2">
            <span class="filter-label">Member Name or ITS ID</span>
            <input type="text" id="filter-member" class="filter-control" placeholder="Search name or ITS ID...">
          </div>
          <div class="col-12 col-md-2 mb-2">
            <span class="filter-label">Hijri Year</span>
            <form method="post" action="<?php echo base_url('anjuman/fmbthaalitakhmeen'); ?>" class="m-0">
              <select name="fmb_year" id="fmb-year" class="filter-control" onchange="this.form.submit()">
                <?php if (!empty($hijri_years)):
                  foreach ($hijri_years as $y): ?>
                    <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($selected_year === $y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
                <?php endforeach;
                endif; ?>
              </select>
            </form>
          </div>
          <?php
          $unique_sectors = [];
          $unique_sub_sectors = [];
          if (isset($all_user_fmb_takhmeen)) {
            foreach ($all_user_fmb_takhmeen as $u) {
              if (!empty($u['Sector'])) $unique_sectors[$u['Sector']] = true;
              if (!empty($u['Sub_Sector'])) $unique_sub_sectors[$u['Sub_Sector']] = true;
            }
          }
          ?>
          <div class="col-12 col-md-2 mb-2">
            <span class="filter-label">Sector</span>
            <select id="filter-sector" class="filter-control">
              <option value="">All Sectors</option>
              <?php foreach (array_keys($unique_sectors) as $sec): ?>
                <option value="<?php echo htmlspecialchars($sec, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sec); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-2 mb-2">
            <span class="filter-label">Sub-Sector</span>
            <select id="filter-sub-sector" class="filter-control">
              <option value="">All Sub-Sectors</option>
              <?php foreach (array_keys($unique_sub_sectors) as $sub): ?>
                <option value="<?php echo htmlspecialchars($sub, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sub); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-3 mb-2 d-flex align-items-end justify-content-md-end gap-2" id="takhmeen-toolbar">
            <button type="button" id="apply-filters-btn" class="btn btn-action btn-action-primary" style="height:38px; border-radius:8px; width:100%;"><i class="fa fa-filter mr-1"></i> Apply</button>
            <button type="button" id="clear-filters-btn" class="btn btn-back" style="height:38px; border-radius:8px; width:100%;"><i class="fa fa-times mr-1"></i> Clear</button>
            <button type="button" id="reset-sort-btn" class="btn btn-back" style="height:38px; border-radius:8px; width:100%;"><i class="fa fa-rotate-left mr-1"></i> Refresh</button>
          </div>
        </div>
      </div>

      <!-- Per Day Thaali Cost Header -->
      <div class="cost-header">
        <strong>Per Day Thaali Cost</strong>
        <small class="text-secondary">(FY - <?php echo htmlspecialchars($selected_year ?? '', ENT_QUOTES); ?>)</small>
        <strong>:</strong>
        <?php if (isset($per_day_thaali_cost_amount) && $per_day_thaali_cost_amount !== null && (float)$per_day_thaali_cost_amount > 0): ?>
          <span class="takhmeen-amount text-primary font-weight-bold" data-raw="<?php echo (float)$per_day_thaali_cost_amount; ?>"><?php echo (float)$per_day_thaali_cost_amount; ?></span>
        <?php else: ?>
          <span class="text-secondary">Not set</span>
        <?php endif; ?>
      </div>

      <!-- Main Table -->
      <div class="table-responsive table-scroll-fixed">
        <table id="takhmeen-table" class="gc-table table table-hover align-middle">
          <thead>
            <tr>
              <th style="width:40px;">#</th>
              <th>ITS ID</th>
              <th>Name</th>
              <th>Sector</th>
              <th>Sub-Sector</th>
              <th>Thaali Days</th>
              <th>Selected Year Takhmeen</th>
              <th>Total Due</th>
              <th style="min-width:200px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($all_user_fmb_takhmeen)) {
              foreach ($all_user_fmb_takhmeen as $key => $user) {
            ?>
                <tr class="clickable-row" data-its-id="<?php echo htmlspecialchars((string)($user['ITS_ID'] ?? ''), ENT_QUOTES); ?>">
                  <td class="row-index" data-sort-value="<?php echo $key + 1; ?>"><?php echo $key + 1; ?></td>
                  <td data-sort-value="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>"><?php echo $user["ITS_ID"]; ?></td>
                  <td data-sort-value="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>" style="font-weight:700; color:var(--text-1);"><?php echo $user["Full_Name"]; ?></td>
                  <td data-sort-value="<?php echo htmlspecialchars($user['Sector'], ENT_QUOTES); ?>"><?php echo $user["Sector"]; ?></td>
                  <td data-sort-value="<?php echo htmlspecialchars($user['Sub_Sector'], ENT_QUOTES); ?>"><?php echo $user["Sub_Sector"]; ?></td>
                  <?php
                  $assignedDays = isset($user['assigned_thaali_days']) ? $user['assigned_thaali_days'] : null;
                  ?>
                  <td data-sort-value="<?php echo $assignedDays !== null ? (int)$assignedDays : ''; ?>">
                    <?php if ($assignedDays !== null): ?>
                      <a href="#" class="view-assigned-thaali-days font-weight-bold" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>" data-year="<?php echo htmlspecialchars($selected_year ?? '', ENT_QUOTES); ?>">
                        <?php echo (int)$assignedDays; ?>
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td data-sort-value="<?php echo (int)($user['selected_total_takhmeen'] ?? 0); ?>">
                    <?php if (!empty($user['selected_total_takhmeen'])): ?>
                      <span class="takhmeen-amount text-primary font-weight-bold" data-raw="<?php echo (int)$user['selected_total_takhmeen']; ?>"><?php echo $user['selected_total_takhmeen']; ?></span>
                      <small class="text-secondary d-block pt-1">(FY - <?php echo htmlspecialchars($user['selected_takhmeen_year'] ?? '', ENT_QUOTES); ?>)</small>
                    <?php else: ?>
                      <span class="text-muted small">Takhmeen Not Found</span>
                    <?php endif; ?>
                  </td>
                  <td class="takhmeen-amount font-weight-bold <?php echo $user["overall_due"] > 0 ? 'text-danger' : ''; ?>" data-sort-value="<?php echo (int)$user['overall_due']; ?>" data-raw="<?php echo (int)$user['overall_due']; ?>"><?php echo $user["overall_due"]; ?></td>
                  <td>
                    <div class="d-flex flex-column gap-1">
                      <?php if (count($user["all_takhmeen"]) > 0): ?>
                        <button class="view-due btn btn-table-info" data-toggle="modal" data-target="#due-overview-modal" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?>" data-all-takhmeen='<?php echo htmlspecialchars(json_encode($user["all_takhmeen"]), ENT_QUOTES, 'UTF-8'); ?>'>
                          Takhmeen Details
                        </button>
                      <?php endif; ?>
                      <?php if ($user["overall_due"] > 0): ?>
                        <button id="pay-takhmeen-btn" class="pay-takhmeen-btn btn btn-table-success" data-toggle="modal" data-target="#pay-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?>" data-overall-due="<?php echo $user["overall_due"]; ?>">
                          Receive Payment
                        </button>
                      <?php endif; ?>
                      <?php if (count($user["all_takhmeen"])): ?>
                        <button id="payment-history" class="payment-history btn btn-table-primary" onclick="openPaymentHistoryModal('<?php echo $user['ITS_ID']; ?>', '<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>')">
                          Payment History
                        </button>
                      <?php endif; ?>
                    </div>
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

  <!-- Assigned Thaali Dates Modal -->
  <div class="modal fade" id="assigned-thaali-days-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title"><i class="fa-solid fa-calendar mr-2"></i> Thaali Dates</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <div class="mb-3 p-3" style="background:var(--surface-2); border-radius:12px; border:1px solid var(--border); font-size:0.86rem;">
            <p class="mb-1"><b>Member Name:</b> <span id="assigned-thaali-days-member" style="font-weight:700; color:var(--text-1);"></span></p>
            <p class="mb-0"><b>Financial Year:</b> <span id="assigned-thaali-days-year" style="font-weight:700; color:var(--text-1);"></span></p>
          </div>
          <div id="assigned-thaali-days-loading" class="text-center text-secondary py-3" style="display:none;"><i class="fa fa-spinner fa-spin mr-1"></i> Loading...</div>
          <div id="assigned-thaali-days-empty" class="alert alert-info text-center d-none">No assigned dates.</div>
          <ul id="assigned-thaali-days-list" class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;"></ul>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 12px 24px;">
          <button type="button" class="btn btn-back" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Takhmeen Details Modal -->
  <div class="modal fade" id="due-overview-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title"><i class="fa-solid fa-file-lines mr-2"></i> Takhmeen Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <p class="mb-3"><b>Member Name:</b> <span id="member-name" style="font-weight:700; color:var(--text-1);"></span></p>
          <div class="table-responsive">
            <table class="table table-hover table-bordered text-center" style="font-size:0.82rem;">
              <thead class="thead-light">
                <tr>
                  <th>Year</th>
                  <th>Takhmeen Amount</th>
                  <th>Paid</th>
                  <th>Due</th>
                  <th>Thaali Days</th>
                  <th>Remark</th>
                </tr>
              </thead>
              <tbody id="due-details"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 12px 24px;">
          <button type="button" class="btn btn-back" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Receive Takhmeen Payment Modal -->
  <div class="modal fade" id="pay-takhmeen-container" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium" style="background: linear-gradient(135deg, #1b6b47 0%, #1a6645 100%);">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle mr-2"></i> Receive Takhmeen Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="updatePaymentForm" method="post" action="<?= base_url('anjuman/update_fmb_payment'); ?>">
            <input type="hidden" name="user_id" id="modal_user_id">
            <div class="p-3 mb-3" style="background:var(--surface-2); border-radius:12px; border:1px solid var(--border); font-size:0.86rem;">
              <strong>Member Name:</strong> <span id="modal_user_name" style="font-weight:700; color:var(--text-1);"></span>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="payment-method">Payment Method</label>
              <select name="payment_method" id="payment-method" class="form-control-premium" required>
                <option value="">Select Method</option>
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
                <option value="NEFT">NEFT</option>
              </select>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="modal-amount">Payment Amount (₹)</label>
              <input type="number" class="form-control-premium" name="amount" id="modal-amount" placeholder="Enter payment amount" required min="1">
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="modal-payment-date">Payment Date</label>
              <input type="date" class="form-control-premium" name="payment_date" id="modal-payment-date" required>
            </div>
            <div class="form-group form-group-premium mb-4">
              <label for="modal_payment_remark">Payment Remark</label>
              <input type="text" class="form-control-premium" name="remarks" id="modal_payment_remark" placeholder="Optional notes">
            </div>
            <button type="submit" class="btn btn-action w-100" style="background:var(--green); border-color:var(--green); color:#fff; padding: 12px; border-radius: 12px; font-size: 0.85rem;">
              <i class="fa-solid fa-circle-check mr-1"></i> Save Payment
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment History Modal -->
  <div class="modal fade" id="payment-history-container" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium" style="background: linear-gradient(135deg, #185075 0%, #174b6e 100%);">
          <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left mr-2"></i> Payment History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <div class="p-3 mb-3" style="background:var(--surface-2); border-radius:12px; border:1px solid var(--border); font-size:0.86rem;">
            <b>Member Name:</b> <span id="history-user-name" style="font-weight:700; color:var(--text-1);"></span>
          </div>
          <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered text-center" style="font-size:0.82rem;">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Payment Date</th>
                  <th>Method</th>
                  <th>Amount</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="payment-history-rows"></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid var(--border-light); padding: 12px 24px;">
          <button type="button" class="btn btn-back" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // === Hijri label helper (Gregorian + Hijri) ===
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

  function formatIsoToDmy(iso) {
    if (typeof iso === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(iso)) {
      const parts = iso.split('-');
      return parts[2] + '-' + parts[1] + '-' + parts[0];
    }
    return String(iso || '');
  }
  
  // Modal z-index stacking fix
  $(document).on('show.bs.modal', '.modal', function() {
    const $openModals = $('.modal.show');
    const baseZ = 1040;
    const z = baseZ + ($openModals.length * 10) + 10;
    $(this).css('z-index', z);
    setTimeout(function() {
      const $backdrops = $('.modal-backdrop').not('.modal-stack');
      $backdrops.css('z-index', z - 1).addClass('modal-stack');
    }, 0);
  });

  $(document).on('hidden.bs.modal', '.modal', function() {
    if ($('.modal.show').length) {
      $('body').addClass('modal-open');
    }
  });

  // Currency formatting
  $(function() {
    $(".takhmeen-amount").each(function() {
      var $el = $(this);
      var raw = $el.data("raw");
      if (raw === undefined || raw === null || raw === "") {
        var txt = $el.text().replace(/[^\d.-]/g, '').trim();
        raw = txt ? parseFloat(txt) : 0;
      }
      var formatted = new Intl.NumberFormat("en-IN", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(raw);
      $el.html("&#8377;" + formatted);
    });
  });

  $(document).ready(function() {
    $(".view-due").on("click", function() {
      const userId = $(this).data("user-id");
      const userName = $(this).data("user-name");
      const allTakhmeen = $(this).data("all-takhmeen");

      $("#member-name").text(userName);
      $("#due-details").empty();
      allTakhmeen.forEach(function(takhmeen) {
        const remarkSafe = (takhmeen.remark || '').toString()
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');

        const fmt = new Intl.NumberFormat('en-IN', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
        const amtTotal = `₹${fmt.format(parseFloat(takhmeen.total_amount||0))}`;
        const amtPaid = `₹${fmt.format(parseFloat(takhmeen.total_paid||0))}`;
        const amtDue = `₹${fmt.format(parseFloat(takhmeen.due||0))}`;
        const assignedDays = parseInt(takhmeen.assigned_thaali_days || 0, 10) || 0;
        const fy = (takhmeen.year || '').toString();
        const assignedHtml = `<a href="#" class="view-assigned-thaali-days font-weight-bold" data-user-id="${(userId||'').toString()}" data-user-name="${(userName||'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}" data-year="${fy.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}">${assignedDays}</a>`;

        $("#due-details").append(`
        <tr>
          <td>${takhmeen.year}</td>
          <td class="text-primary font-weight-bold">${amtTotal}</td>
          <td class="text-success font-weight-bold">${amtPaid}</td>
          <td class="${(takhmeen.due||0) > 0 ? 'text-danger font-weight-bold' : ''}">${amtDue}</td>
          <td>${assignedHtml}</td>
          <td>${remarkSafe || '-'}</td>
        </tr>
      `);
      });

      $("#due-overview-modal").modal("show");
    });
  });

  $('#apply-filters-btn').on('click', function(e) {
    e.preventDefault();
    var base = '<?php echo site_url('anjuman/fmbthaalitakhmeen'); ?>';
    var its = ($('#filter-its').val() || '').toString().trim();
    var sector = ($('#filter-sector').val() || '').toString().trim();
    var sub = ($('#filter-sub-sector').val() || '').toString().trim();
    var fmbYear = ($('#fmb-year').val() || '').toString().trim();
    var memberOnlyVal = ($('#filter-member').val() || '').toString().trim();
    if (memberOnlyVal && !its && !sector && !sub) {
      if (typeof window.applyFmbLocalFilters === 'function') {
        window.applyFmbLocalFilters();
        return;
      }
    }
    var params = {};
    if (fmbYear) params.fmb_year = fmbYear;
    if (its) params.its = its;
    if (sector) params.sector = sector;
    if (sub) params.sub_sector = sub;
    var qs = Object.keys(params).length ? ('?' + $.param(params)) : '';
    window.location.href = base + qs;
  });

  $('#clear-filters-btn').on('click', function(e) {
    e.preventDefault();
    var base = '<?php echo site_url('anjuman/fmbthaalitakhmeen'); ?>';
    var fmbYear = ($('#fmb-year').val() || '').toString().trim();
    var qs = fmbYear ? ('?fmb_year=' + encodeURIComponent(fmbYear)) : '';
    window.location.href = base + qs;
  });

  $(document).on("click", ".pay-takhmeen-btn", function() {
    let userId = $(this).data("user-id");
    let userName = $(this).data("user-name");
    let today = new Date().toISOString().split("T")[0];

    $("#modal_user_id").val(userId);
    $("#modal_user_name").text(userName);
    $("#modal-payment-date").val(today);
    $("#modal-amount").attr("max", $(this).data("overall-due"));

    $("#payment-method").val("");
    $("#modal-amount").val("");
    $("#remarks").val("");

    $("#pay-takhmeen-container").modal("show");
  });

  function openPaymentHistoryModal(user_id, name) {
    $('#history-user-name').text(name);
    $('#payment-history-rows').html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin mr-1"></i> Loading...</td></tr>');

    $.ajax({
      url: "<?php echo base_url('anjuman/getPaymentHistory/1'); ?>",
      type: "POST",
      data: {
        user_id: user_id
      },
      dataType: "json",
      success: function(response) {
        let rows = "";
        if (response.length > 0) {
          response.forEach((payment, index) => {
            rows += `
            <tr>
              <td>${index + 1}</td>
              <td>${payment.payment_date ? (function(d){d=new Date(payment.payment_date);return (('0'+d.getDate()).slice(-2)+'-'+('0'+(d.getMonth()+1)).slice(-2)+'-'+d.getFullYear());})() : ''}</td>
              <td>${payment.payment_method}</td>
              <td class="font-weight-bold">&#8377;${new Intl.NumberFormat("en-IN").format(payment.amount)}</td>
              <td>${payment.remarks ?? ''}</td>
              <td>
                <button class="view-invoice btn btn-sm btn-outline-secondary" data-payment-id="${payment.id}"><i class="fa-solid fa-file-pdf"></i></button>
                <button class="delete-payment btn btn-sm btn-outline-danger ml-2" data-payment-id="${payment.id}"><i class="fa-solid fa-trash"></i></button>
              </td>
            </tr>
          `;
          });
        } else {
          rows = `<tr><td colspan="6" class="text-center text-muted">No payment history found</td></tr>`;
        }
        $('#payment-history-rows').html(rows);
        
        // Delete payment handler
        $(document).off("click", ".delete-payment").on("click", ".delete-payment", function(e) {
          e.preventDefault();
          const paymentId = $(this).data("payment-id");
          if (!confirm("Are you sure you want to delete this payment?")) return;
          $.ajax({
            url: "<?php echo base_url('anjuman/delete_takhmeen_payment'); ?>",
            type: "POST",
            data: {
              payment_id: paymentId,
              for: 1
            },
            success: function(res) {
              let parsed;
              try {
                parsed = JSON.parse(res);
              } catch (e) {
                parsed = { success: false };
              }
              if (parsed.success) {
                alert("Payment deleted successfully!");
                $("button[data-payment-id='" + paymentId + "']").closest("tr").remove();
                window.location.reload();
              } else {
                alert("Delete failed: " + (parsed.message || "Unknown error"));
              }
            },
            error: function() {
              alert("Failed to delete payment");
            }
          });
        });
      },
      error: function() {
        $('#payment-history-rows').html('<tr><td colspan="6" class="text-center text-danger">Error loading history</td></tr>');
      }
    });

    $('#payment-history-container').modal('show');
  }

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 1,
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(response) {
        var blob = new Blob([response], {
          type: "application/pdf"
        });
        var url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
      },
      error: function() {
        alert("Failed to generate receipt PDF");
      }
    });
  });

  $(".alert").hide(3000);

  $(document).on('click', '.view-assigned-thaali-days', function(e) {
    e.preventDefault();
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    const year = $(this).data('year');

    $('#assigned-thaali-days-member').text(userName || '');
    $('#assigned-thaali-days-year').text(year || '');
    $('#assigned-thaali-days-list').empty();
    $('#assigned-thaali-days-empty').addClass('d-none');
    $('#assigned-thaali-days-loading').show();

    $('#assigned-thaali-days-modal').modal('show');

    $.ajax({
      url: "<?php echo base_url('anjuman/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      dataType: 'json',
      data: {
        user_id: userId,
        year: year
      },
      success: function(res) {
        $('#assigned-thaali-days-loading').hide();
        const dates = (res && res.success && Array.isArray(res.dates)) ? res.dates : [];
        if (!dates.length) {
          $('#assigned-thaali-days-empty').removeClass('d-none');
          return;
        }
        dates.forEach(function(d) {
          const iso = String(d || '').trim();
          const greg = formatIsoToDmy(iso);
          const $li = $('<li class="list-group-item d-flex justify-content-between align-items-center">').text(greg);
          $('#assigned-thaali-days-list').append($li);
          getHijriLabelForGregIso(iso).then(function(lbl) {
            if (lbl) {
              $li.html('<span>' + greg + '</span><span class="badge badge-warning" style="font-weight:700; background:var(--gold-muted); color:var(--gold); border-radius:12px; font-size:0.72rem; padding:4px 8px;">' + lbl + '</span>');
            }
          });
        });
      },
      error: function() {
        $('#assigned-thaali-days-loading').hide();
        $('#assigned-thaali-days-empty').text('Failed to load assigned dates.').removeClass('d-none');
      }
    });
  });

  // Table sorting & filtering logic
  (function() {
    const table = document.getElementById('takhmeen-table');
    if (!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    Array.from(tbody.querySelectorAll('tr')).forEach((tr, i) => {
      tr.dataset.originalIndex = i;
    });

    thead.querySelectorAll('th').forEach((th, idx) => {
      const label = th.textContent.trim();
      if (idx === 0 || label === 'Action') return;
      th.classList.add('sortable');
      th.innerHTML = '<span class="sort-label">' + label + '</span><span class="sort-indicator" aria-hidden="true"></span>';
      th.setAttribute('role', 'button');
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => toggleSort(idx, th));
    });

    function getCellValue(tr, index) {
      const cells = tr.querySelectorAll('td');
      if (!cells[index]) return '';
      let raw = cells[index].getAttribute('data-sort-value');
      if (raw === null) {
        raw = cells[index].textContent;
      }
      raw = raw.replace(/₹|,/g, '').replace(/\s+/g, ' ').trim();
      return raw;
    }

    function inferType(val) {
      if (/^[-+]?\d+(\.\d+)?$/.test(val)) return 'number';
      return 'text';
    }

    function normalize(val) {
      const t = inferType(val);
      if (t === 'number') return parseFloat(val) || 0;
      return val.toLowerCase();
    }

    function toggleSort(idx, th) {
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      thead.querySelectorAll('th.sortable').forEach(h => {
        h.dataset.sortDir = '';
        const ind = h.querySelector('.sort-indicator');
        if (ind) ind.textContent = '';
      });
      th.dataset.sortDir = newDir;
      const ind = th.querySelector('.sort-indicator');
      if (ind) ind.textContent = newDir === 'asc' ? '▲' : '▼';
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.sort((a, b) => {
        const va = normalize(getCellValue(a, idx));
        const vb = normalize(getCellValue(b, idx));
        if (va < vb) return newDir === 'asc' ? -1 : 1;
        if (va > vb) return newDir === 'asc' ? 1 : -1;
        return 0;
      });
      rows.forEach(r => tbody.appendChild(r));
      renumberVisible();
    }

    const resetBtn = document.getElementById('reset-sort-btn');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => parseInt(a.dataset.originalIndex, 10) - parseInt(b.dataset.originalIndex, 10));
        rows.forEach(r => tbody.appendChild(r));
        thead.querySelectorAll('th.sortable').forEach(h => {
          h.dataset.sortDir = '';
          const ind = h.querySelector('.sort-indicator');
          if (ind) ind.textContent = '';
        });
        applyFilters();
      });
    }

    const memberInput = document.getElementById('filter-member');
    const sectorSelect = document.getElementById('filter-sector');
    const subSectorSelect = document.getElementById('filter-sub-sector');

    function applyFilters() {
      const nameVal = (memberInput.value || '').toLowerCase().trim();
      const sectorVal = sectorSelect.value;
      const subVal = subSectorSelect.value;
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.forEach(r => {
        const cells = r.querySelectorAll('td');
        if (cells.length < 5) {
          r.style.display = '';
          return;
        }
        const itsCell = cells[1].textContent.toLowerCase();
        const nameCell = cells[2].textContent.toLowerCase();
        const sectorCell = cells[3].textContent.trim();
        const subCell = cells[4].textContent.trim();
        let show = true;
        if (nameVal && !(nameCell.includes(nameVal) || itsCell.includes(nameVal))) show = false;
        if (sectorVal && sectorCell !== sectorVal) show = false;
        if (subVal && subCell !== subVal) show = false;
        r.style.display = show ? '' : 'none';
      });
      renumberVisible();
    }

    function renumberVisible() {
      let counter = 1;
      Array.from(tbody.querySelectorAll('tr')).forEach(r => {
        if (r.style.display === 'none') return;
        const idxCell = r.querySelector('.row-index');
        if (idxCell) idxCell.textContent = counter++;
      });
    }

    [memberInput, sectorSelect, subSectorSelect].forEach(el => {
      if (el) el.addEventListener('keyup', e => {
        if (e.key === 'Enter') applyFilters();
      });
      if (el) el.addEventListener('change', applyFilters);
    });
    window.applyFmbLocalFilters = applyFilters;
    renumberVisible();
  })();

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
</script>