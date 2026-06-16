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
    --amber:       #b45309;
    --amber-bg:    #fffbeb;
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

  /* ── Action Button styling ── */
  #anjApp .btn-action {
    font-family: inherit;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 0.82rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
  }
  #anjApp .btn-action-primary {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 100%);
    color: #fff !important;
    border: none;
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.2);
  }
  #anjApp .btn-action-primary:hover {
    background: linear-gradient(135deg, #8c600c 0%, #d49c1a 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(184, 134, 11, 0.35);
    text-decoration: none;
  }
  #anjApp .btn-action-primary:active {
    transform: translateY(0);
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

  /* ── Premium status pills ── */
  #anjApp .badge-premium {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 20px;
  }
  #anjApp .badge-premium.paid {
    background: var(--green-bg);
    color: var(--green);
    border: 1px solid rgba(26,102,69,0.18);
  }
  #anjApp .badge-premium.partial {
    background: var(--amber-bg);
    color: var(--amber);
    border: 1px solid rgba(180,83,9,0.18);
  }
  #anjApp .badge-premium.unpaid {
    background: var(--red-bg);
    color: var(--red);
    border: 1px solid rgba(185,28,28,0.18);
  }

  /* ── Action buttons grid ── */
  #anjApp .gc-action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px;
  }
  #anjApp .gc-action-grid .btn {
    width: 100%;
    margin-bottom: 0 !important;
    font-family: inherit;
    font-weight: 700;
    font-size: 0.72rem;
    padding: 6px 10px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    transition: all 0.15s;
  }
  @media (max-width:359.98px){
    #anjApp .gc-action-grid { grid-template-columns: 1fr; }
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

  /* Sorting styles */
  #anjApp #gc-invoice-table thead th.gc-sort {
    cursor: pointer;
    position: relative;
    user-select: none;
  }
  #anjApp #gc-invoice-table thead th.gc-sort .sort-indicator {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 11px;
    opacity: 0.45;
  }
  #anjApp #gc-invoice-table thead th.gc-sort.sorted-asc .sort-indicator::after {
    content: '▲';
  }
  #anjApp #gc-invoice-table thead th.gc-sort.sorted-desc .sort-indicator::after {
    content: '▼';
  }
  @media (hover:hover){
    #anjApp #gc-invoice-table thead th.gc-sort:hover {
      background: #221e16;
    }
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

  /* Autocomplete suggestions dropdown */
  #anjApp .autocomplete-dropdown {
    position: absolute;
    z-index: 1050;
    left: 0;
    right: 0;
    top: 100%;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    max-height: 250px;
    overflow-y: auto;
  }
  #anjApp .autocomplete-item {
    display: block;
    width: 100%;
    padding: 10px 14px;
    cursor: pointer;
    background: var(--surface);
    border-bottom: 1px solid var(--border-light);
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--text-1);
    text-align: left;
    border-left: none;
    border-right: none;
  }
  #anjApp .autocomplete-item:hover {
    background: var(--gold-muted);
    color: var(--gold);
    text-decoration: none;
  }
  #anjApp .autocomplete-item:last-child {
    border-bottom: none;
  }
</style>

<div id="anjApp" class="margintopcontainer pt-5">
  <div class="container-fluid px-md-5 pb-4">
    <!-- Header Controls Row -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <a href="<?php echo $type == 1 ? base_url("anjuman/fmbthaali") : base_url("anjuman/fmbniyaz"); ?>" class="btn btn-back">
          <i class="fa-solid fa-arrow-left mr-1"></i> Back
        </a>
      </div>
      <div>
        <button class="btn btn-action btn-action-primary" style="padding: 10px 20px; font-weight: 700; border-radius: 12px; font-size: 0.82rem;" data-toggle="modal" data-target="#generateInvoiceModal">
          <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Generate New Invoice
        </button>
      </div>
    </div>

    <!-- Title Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> Extra Contribution Invoices</h1>
        </div>
      </div>
    </div>

    <!-- Main Content Card -->
    <div class="content-card">
      <!-- Filter Bar -->
      <div id="gc-filters" class="filter-bar">
        <div class="form-row">
          <div class="<?php echo $type == 1 ? 'col-md-4' : 'col-md-3'; ?> mb-2">
            <span class="filter-label">Name or ITS</span>
            <input type="text" id="gc-filter-name" class="filter-control" placeholder="Search name or ITS...">
          </div>
          <div class="<?php echo $type == 1 ? 'col-md-4' : 'col-md-3'; ?> mb-2">
            <span class="filter-label">Contribution Type</span>
            <select id="gc-filter-ctype" class="filter-control">
              <option value="">All Types</option>
              <?php if(isset($contri_type_gc)): foreach($contri_type_gc as $ct): ?>
                <option value="<?php echo htmlspecialchars(strtolower($ct['name']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ct['name']); ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>
          <?php if ($type != 1): ?>
          <div class="col-md-3 mb-2">
            <span class="filter-label">Miqaat Type</span>
            <select id="gc-filter-miqaat-type" class="filter-control">
              <option value="">All Miqaats</option>
              <option value="shehrullah">Shehrullah</option>
              <option value="ashara">Ashara</option>
              <option value="general">General</option>
              <option value="ladies">Ladies</option>
            </select>
          </div>
          <?php endif; ?>
          <div class="<?php echo $type == 1 ? 'col-md-4' : 'col-md-3'; ?> mb-2 d-flex align-items-end">
            <button type="button" id="gc-filter-clear" class="btn btn-back w-100" style="height:38px; border-radius:8px;"><i class="fa fa-times mr-1"></i> Clear Filters</button>
          </div>
        </div>
      </div>

      <!-- Table Container -->
      <div class="table-responsive">
        <table class="gc-table table table-hover align-middle" id="gc-invoice-table">
          <thead>
            <tr>
              <th class="no-sort" style="width:40px;">#</th>
              <th class="gc-sort" data-sort-type="date">Invoice Date <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="number">ITS ID <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="text">Member Name <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="text">Contribution Type <span class="sort-indicator"></span></th>
              <?php if ($type != 1): ?>
              <th class="gc-sort" data-sort-type="text">Miqaat Type <span class="sort-indicator"></span></th>
              <?php endif; ?>
              <th class="gc-sort" data-sort-type="number">Amount (₹) <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="number">Received (₹) <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="number">Balance (₹) <span class="sort-indicator"></span></th>
              <th class="gc-sort" data-sort-type="text">Status <span class="sort-indicator"></span></th>
              <th class="no-sort" style="min-width:220px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($all_user_fmbgc)): ?>
              <?php foreach ($all_user_fmbgc as $key => $row): ?>
                <tr>
                  <td><?php echo $key + 1; ?></td>
                  <td><?php echo date("d-m-Y", strtotime($row["created_at"])); ?></td>
                  <td><?php echo $row["ITS_ID"]; ?></td>
                  <td style="font-weight:700; color:var(--text-1);"><?php echo $row["Full_Name"]; ?></td>
                  <td class="gc-contri-type-td" data-fmbgc-id="<?php echo $row['id']; ?>"><?php echo $row["contri_type"]; ?></td>
                  <?php if ($type != 1): ?>
                  <td class="gc-miqaat-type-td" data-fmbgc-id="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row["miqaat_type"] ?? ''); ?></td>
                  <?php endif; ?>
                  <td style="font-weight:700;"><span class="gc-amount" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["amount"],0); ?></span></td>
                  <td><span class="gc-received" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["total_received"],0); ?></span></td>
                  <td style="font-weight:700; color:var(--red);"><span class="gc-balance" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["balance_due"],0); ?></span></td>
                  <td><span class="gc-status" data-fmbgc-id="<?php echo $row['id'];?>">
                    <?php if ($row['balance_due'] <= 0): ?>
                      <span class="badge-premium paid">Paid</span>
                    <?php elseif ($row['total_received'] > 0): ?>
                      <span class="badge-premium partial">Partial</span>
                    <?php else: ?>
                      <span class="badge-premium unpaid">Unpaid</span>
                    <?php endif; ?>
                  </span></td>
                  <td>
                    <div class="gc-action-grid">
                      <button class="update-payment-btn btn btn-table-success" data-fmbgc-id="<?php echo $row["id"]; ?>" data-user-id="<?php echo $row["user_id"]; ?>" data-member-name="<?php echo htmlspecialchars($row["Full_Name"], ENT_QUOTES); ?>" data-amount="<?php echo $row["amount"]; ?>" data-total-received="<?php echo $row['total_received']; ?>" data-balance-due="<?php echo $row['balance_due']; ?>" data-toggle="modal" data-target="#update-payment-modal">
                        <i class="fa-solid fa-wallet"></i> Pay
                      </button>
                      <button class="edit-invoice-btn btn btn-table-primary" data-fmbgc-id="<?php echo $row["id"]; ?>" data-contri-year="<?php echo htmlspecialchars($row["contri_year"], ENT_QUOTES); ?>" data-contri-type="<?php echo htmlspecialchars($row["contri_type"], ENT_QUOTES); ?>" data-miqaat-type="<?php echo htmlspecialchars($row["miqaat_type"] ?? '', ENT_QUOTES); ?>" data-amount="<?php echo $row["amount"]; ?>" data-description="<?php echo htmlspecialchars($row["description"], ENT_QUOTES); ?>" data-member-name="<?php echo htmlspecialchars($row["Full_Name"], ENT_QUOTES); ?>" data-its="<?php echo $row["ITS_ID"]; ?>">
                        <i class="fa-solid fa-pen"></i> Edit
                      </button>
                      <button class="btn btn-table-info view-history-btn" data-fmbgc-id="<?php echo $row['id']; ?>" data-member-name="<?php echo htmlspecialchars($row['Full_Name'], ENT_QUOTES); ?>" data-toggle="modal" data-target="#payment-history-modal">
                        <i class="fa-solid fa-clock-rotate-left"></i> History
                      </button>
                      <button class="view-description btn btn-table-info" data-description="<?php echo $row["description"]; ?>" data-toggle="modal" data-target="#description-modal">
                        <i class="fa-solid fa-eye"></i> Desc
                      </button>
                      <button class="btn btn-table-danger delete-invoice-btn" data-invoice-id="<?php echo $row['id']; ?>" data-member-name="<?php echo htmlspecialchars($row['Full_Name'], ENT_QUOTES); ?>" data-total-received="<?php echo $row['total_received']; ?>" style="grid-column: span 2;">
                        <i class="fa-solid fa-trash"></i> Delete Invoice
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Generate Invoice Modal -->
  <div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle mr-2"></i> Generate New Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="save-fmbgc-form" method="POST" action="<?php echo base_url("anjuman/addfmbgc"); ?>">
            <div class="form-group form-group-premium mb-3">
              <label for="contri-year">Contribution Year</label>
              <select name="contri_year" id="contri-year" class="form-control-premium" required>
                <option value="">Select Contribution Year</option>
                <?php if (!empty($hijri_years)): ?>
                  <?php foreach ($hijri_years as $y): ?>
                    <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>"><?php echo htmlspecialchars($y); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group form-group-premium mb-3 position-relative">
              <label for="member-autocomplete">Name or ITS</label>
              <input type="text" id="member-autocomplete" class="form-control-premium" placeholder="Type name or ITS..." autocomplete="off" required>
              <input type="hidden" name="user_id" id="user-id" required>
              <div id="member-autocomplete-list" class="autocomplete-dropdown" style="display:none;"></div>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="contri-type">Contribution Type</label>
              <input type="hidden" name="fmb_type" value="<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>" required>
              <select name="contri_type" id="contri-type" class="form-control-premium" required>
                <option value="">Select Type</option>
                <?php if (isset($contri_type_gc)): ?>
                  <?php foreach ($contri_type_gc as $key => $value): ?>
                    <option value="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>" data-miqaat-type="<?php echo htmlspecialchars($value["miqaat_type"] ?? '', ENT_QUOTES); ?>"><?php echo htmlspecialchars($value["name"]); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <?php if ($type != 1): ?>
            <div class="form-group form-group-premium mb-3">
              <label for="miqaat-type">Miqaat Type</label>
              <select name="miqaat_type" id="miqaat-type" class="form-control-premium" required>
                <option value="">Select Miqaat Type</option>
                <option value="Shehrullah">Shehrullah</option>
                <option value="Ashara">Ashara</option>
                <option value="General">General</option>
                <option value="Ladies">Ladies</option>
              </select>
            </div>
            <?php endif; ?>
            <div class="form-group form-group-premium mb-3">
              <label for="amount">Amount (₹)</label>
              <input type="number" name="amount" id="amount" class="form-control-premium" placeholder="Enter amount" min="1" required>
            </div>
            <div class="form-group form-group-premium mb-4">
              <label for="description">Description</label>
              <textarea name="description" id="description" class="form-control-premium" rows="2" placeholder="Optional remarks..."></textarea>
            </div>
            <button type="submit" id="save-fmbgc-btn" class="btn btn-action btn-action-primary w-100" style="padding: 12px; border-radius: 12px; font-size: 0.85rem;">
              <i class="fa-solid fa-circle-check mr-1"></i> Generate Invoice
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Invoice Modal -->
  <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium">
          <h5 class="modal-title"><i class="fa-solid fa-pencil mr-2"></i> Update Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="edit-fmbgc-form">
            <input type="hidden" name="invoice_id" id="edit-invoice-id" value="">
            
            <div class="mb-3" style="font-size:0.86rem; color:var(--text-2);">
              <p class="mb-1"><strong>Member:</strong> <span id="edit-member-name" style="font-weight:700; color:var(--text-1);"></span></p>
              <p class="mb-1"><strong>ITS ID:</strong> <span id="edit-member-its" style="font-weight:700; color:var(--text-1);"></span></p>
            </div>
            <hr style="border-color:var(--border-light);">

            <div class="form-group form-group-premium mb-3">
              <label for="edit-contri-year">Contribution Year</label>
              <select name="contri_year" id="edit-contri-year" class="form-control-premium" required>
                <option value="">Select Contribution Year</option>
                <?php if (!empty($hijri_years)): ?>
                  <?php foreach ($hijri_years as $y): ?>
                    <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>"><?php echo htmlspecialchars($y); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            
            <div class="form-group form-group-premium mb-3">
              <label for="edit-contri-type">Contribution Type</label>
              <select name="contri_type" id="edit-contri-type" class="form-control-premium" required>
                <option value="">Select Type</option>
                <?php if (isset($contri_type_gc)): ?>
                  <?php foreach ($contri_type_gc as $key => $value): ?>
                    <option value="<?php echo htmlspecialchars($value["name"], ENT_QUOTES); ?>" data-miqaat-type="<?php echo htmlspecialchars($value["miqaat_type"] ?? '', ENT_QUOTES); ?>"><?php echo htmlspecialchars($value["name"]); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <?php if ($type != 1): ?>
            <div class="form-group form-group-premium mb-3">
              <label for="edit-miqaat-type">Miqaat Type</label>
              <select name="miqaat_type" id="edit-miqaat-type" class="form-control-premium" required>
                <option value="">Select Miqaat Type</option>
                <option value="Shehrullah">Shehrullah</option>
                <option value="Ashara">Ashara</option>
                <option value="General">General</option>
                <option value="Ladies">Ladies</option>
              </select>
            </div>
            <?php endif; ?>
            
            <div class="form-group form-group-premium mb-3">
              <label for="edit-amount">Amount (₹)</label>
              <input type="number" name="amount" id="edit-amount" class="form-control-premium" placeholder="Enter amount" min="1" required>
            </div>
            
            <div class="form-group form-group-premium mb-4">
              <label for="edit-description">Description</label>
              <textarea name="description" id="edit-description" class="form-control-premium" rows="2" placeholder="Optional remarks..."></textarea>
            </div>
            
            <button type="submit" id="edit-fmbgc-save-btn" class="btn btn-action btn-action-primary w-100" style="padding: 12px; border-radius: 12px; font-size: 0.85rem;">
              <i class="fa-solid fa-circle-check mr-1"></i> Save Changes
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Receive Payment Modal -->
  <div class="modal fade" id="update-payment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium" style="background: linear-gradient(135deg, #1b6b47 0%, #1a6645 100%);">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle mr-2"></i> Receive Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <form id="update-fmbgc-payment-form" method="POST" action="<?php echo base_url("anjuman/updatefmbgcpayment"); ?>">
            <div class="p-3 mb-3" style="background:var(--surface-2); border-radius:12px; font-size:0.86rem; border:1px solid var(--border);">
              <p class="mb-2"><strong>Member:</strong> <span id="up-member-name" style="font-weight:700; color:var(--text-1);"></span></p>
              <div class="d-flex justify-content-between flex-wrap gap-2 pt-2 border-top" style="border-color:var(--border);">
                <div>Invoice Amount: <span style="font-weight:700; color:var(--text-1);">₹<span id="invoice-amount"></span></span></div>
                <div>Paid: <span style="font-weight:700; color:var(--green);">₹<span id="already-received">0</span></span></div>
                <div>Due: <span style="font-weight:700; color:var(--red);">₹<span id="due-amount"></span></span></div>
              </div>
              <input type="hidden" name="user_id" id="up-user-id" value="">
              <input type="hidden" name="fmbgc_id" id="up-fmbgc-id" value="">
            </div>
            <div class="row">
              <div class="col-md-6 form-group form-group-premium mb-3">
                <label for="payment-method">Payment Method</label>
                <select name="payment_method" id="payment-method" class="form-control-premium" required onchange="toggleFmbPaymentFields(this.value)">
                  <option value="">Select Method</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                  <option value="NEFT">NEFT</option>
                </select>
              </div>
              <div class="col-md-6 form-group form-group-premium mb-3">
                <label for="payment-date">Payment Date</label>
                <input type="date" name="payment_date" id="payment-date" class="form-control-premium" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
            <div class="row" id="extra-fmb-payment-fields" style="display: none;">
              <div class="col-md-6 form-group form-group-premium mb-3">
                <label for="reference_no">Cheque No. / NEFT Ref No.</label>
                <input type="text" name="reference_no" id="reference_no" class="form-control-premium" placeholder="Ref number">
              </div>
              <div class="col-md-6 form-group form-group-premium mb-3">
                <label for="bank_name">Drawn on Bank</label>
                <input type="text" name="bank_name" id="bank_name" class="form-control-premium" placeholder="Bank name">
              </div>
            </div>
            <div class="form-group form-group-premium mb-3">
              <label for="payment-amount">Amount Received Now (₹)</label>
              <input type="number" name="payment_received_amount" id="payment-amount" class="form-control-premium" placeholder="Enter amount" min="1" required>
              <small class="form-text text-muted" id="payment-amount-hint"></small>
            </div>
            <div class="form-group form-group-premium mb-4">
              <label for="payment-remarks">Remarks</label>
              <textarea name="payment_remarks" id="payment-remarks" rows="2" class="form-control-premium" placeholder="Optional notes..."></textarea>
            </div>
            <button type="submit" id="update-fmbgc-payment-btn" class="btn btn-action w-100" style="background:var(--green); border-color:var(--green); color:#fff; padding: 12px; border-radius: 12px; font-size: 0.85rem;">
              <i class="fa-solid fa-circle-check mr-1"></i> Save Payment
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Description Modal -->
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium" style="background: linear-gradient(135deg, #1b6b47 0%, #1a6645 100%);">
          <h5 class="modal-title"><i class="fa-solid fa-circle-info mr-2"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <p id="modal-view-description" class="text-dark m-0" style="font-size:0.9rem; line-height:1.6;"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment History Modal -->
  <div class="modal fade" id="payment-history-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modal-content-premium">
        <div class="modal-header modal-header-premium" style="background: linear-gradient(135deg, #185075 0%, #174b6e 100%);">
          <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left mr-2"></i> Payment History - <span id="history-member-name"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body-premium">
          <div id="history-meta" class="mb-3 p-3 text-dark font-weight-bold" style="background:var(--surface-2); border-radius:12px; border:1px solid var(--border); font-size:0.82rem;"></div>
          <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered text-center" id="history-table" style="font-size:0.8rem;">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Payment ID</th>
                  <th>Date</th>
                  <th>Method</th>
                  <th class="text-right">Amount (₹)</th>
                  <th>Remarks</th>
                  <th>Received At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr class="font-weight-bold" style="color:var(--green);">
                  <td colspan="4" class="text-right">Total Received:</td>
                  <td class="text-right" id="history-total-received">₹0</td>
                  <td colspan="3"></td>
                </tr>
                <tr class="font-weight-bold" style="color:var(--red);">
                  <td colspan="4" class="text-right">Balance Due:</td>
                  <td class="text-right" id="history-balance-due">₹0</td>
                  <td colspan="3"></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div id="history-empty" class="alert alert-info d-none m-0">No payments recorded yet.</div>
          <div id="history-error" class="alert alert-danger d-none m-0"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Auto-fill Miqaat Type based on selected Contribution Type in generate/edit modals
    $("#contri-type").on("change", function() {
      const selectedMiqaatType = $(this).find("option:selected").data("miqaat-type");
      if (selectedMiqaatType) {
        $("#miqaat-type").val(selectedMiqaatType);
      } else {
        $("#miqaat-type").val("");
      }
    });

    $("#edit-contri-type").on("change", function() {
      const selectedMiqaatType = $(this).find("option:selected").data("miqaat-type");
      if (selectedMiqaatType) {
        $("#edit-miqaat-type").val(selectedMiqaatType);
      } else {
        $("#edit-miqaat-type").val("");
      }
    });

    // Update Invoice button click
    $(document).on("click", ".edit-invoice-btn", function() {
      const fmbgcId = $(this).data("fmbgc-id");
      const contriYear = $(this).data("contri-year");
      const contriType = $(this).data("contri-type");
      const miqaatType = $(this).data("miqaat-type");
      const amount = $(this).data("amount");
      const description = $(this).data("description");
      const memberName = $(this).data("member-name");
      const its = $(this).data("its");

      $("#edit-invoice-id").val(fmbgcId);
      $("#edit-member-name").text(memberName);
      $("#edit-member-its").text(its);
      $("#edit-contri-year").val(contriYear);
      $("#edit-contri-type").val(contriType);
      $("#edit-miqaat-type").val(miqaatType);
      $("#edit-amount").val(amount);
      $("#edit-description").val(description);

      $("#editInvoiceModal").modal("show");
    });

    // Save edited invoice
    $("#edit-fmbgc-form").on("submit", function(e) {
      e.preventDefault();
      const $btn = $("#edit-fmbgc-save-btn");
      $btn.prop("disabled", true);

      const fmbgcId = $("#edit-invoice-id").val();
      const amount = $("#edit-amount").val();
      const contriYear = $("#edit-contri-year").val();
      const contriType = $("#edit-contri-type").val();
      const miqaatEl = $("#edit-miqaat-type");
      const miqaatType = miqaatEl.length ? miqaatEl.val() : null;
      const description = $("#edit-description").val();

      $.ajax({
        url: "<?php echo base_url('anjuman/updatefmbgcinvoice'); ?>",
        type: "POST",
        dataType: "json",
        data: {
          invoice_id: fmbgcId,
          amount: amount,
          contri_year: contriYear,
          contri_type: contriType,
          miqaat_type: miqaatType,
          description: description
        },
        success: function(res) {
          if (!res.success) {
            alert(res.message || "Update failed");
            return;
          }
          // Refresh row details in the main table
          const $tr = $('button.edit-invoice-btn[data-fmbgc-id="' + fmbgcId + '"]').closest('tr');
          if ($tr.length) {
            $('.gc-contri-type-td[data-fmbgc-id="' + fmbgcId + '"]').text(contriType);
            $('.gc-miqaat-type-td[data-fmbgc-id="' + fmbgcId + '"]').text(miqaatType);
            
            const fmt = function(v){ return '₹' + Math.round(parseFloat(v)||0).toLocaleString('en-IN'); };
            $('.gc-amount[data-fmbgc-id="' + fmbgcId + '"]').text(fmt(res.summary.amount));
            $('.gc-received[data-fmbgc-id="' + fmbgcId + '"]').text(fmt(res.summary.total_received));
            $('.gc-balance[data-fmbgc-id="' + fmbgcId + '"]').text(fmt(res.summary.balance_due));

            let badgeHtml = '<span class="badge-premium unpaid">Unpaid</span>';
            if (res.summary.balance_due <= 0) {
              badgeHtml = '<span class="badge-premium paid">Paid</span>';
            } else if (res.summary.total_received > 0) {
              badgeHtml = '<span class="badge-premium partial">Partial</span>';
            }
            $('.gc-status[data-fmbgc-id="' + fmbgcId + '"]').html(badgeHtml);

            // Update attributes on buttons in this row
            const $editBtn = $tr.find('.edit-invoice-btn');
            $editBtn.attr('data-contri-year', contriYear);
            $editBtn.attr('data-contri-type', contriType);
            $editBtn.attr('data-miqaat-type', miqaatType);
            $editBtn.attr('data-amount', res.summary.amount);
            $editBtn.attr('data-description', description);

            const $payBtn = $tr.find('.update-payment-btn');
            $payBtn.attr('data-amount', res.summary.amount);
            $payBtn.attr('data-total-received', res.summary.total_received);
            $payBtn.attr('data-balance-due', res.summary.balance_due);

            const $viewDescBtn = $tr.find('.view-description');
            $viewDescBtn.attr('data-description', description);
          }
          $("#editInvoiceModal").modal("hide");
        },
        error: function() {
          alert("Server error");
        },
        complete: function() {
          $btn.prop("disabled", false);
        }
      });
    });

    window.toggleFmbPaymentFields = function(method) {
      if (method === 'Cheque' || method === 'NEFT') {
        $('#extra-fmb-payment-fields').show();
      } else {
        $('#extra-fmb-payment-fields').hide();
        $('#reference_no').val('');
        $('#bank_name').val('');
      }
    };
    
    // Autocomplete for member name
    $("#member-autocomplete").on("input", function() {
      const query = $(this).val();
      if (!query || query.length < 2) {
        $("#member-autocomplete-list").empty().hide();
        $("#user-id").val("");
        return;
      }
      $.ajax({
        url: "<?php echo base_url('anjuman/searchmembers'); ?>",
        type: "POST",
        data: {
          query: query
        },
        dataType: "json",
        success: function(res) {
          if (res.success && Array.isArray(res.members) && res.members.length > 0) {
            let listHtml = '';
            res.members.forEach(function(member) {
              listHtml += `<button type="button" class="autocomplete-item member-suggestion" data-id="${member.ITS_ID}" data-name="${member.Full_Name}">${member.Full_Name} (${member.ITS_ID})</button>`;
            });
            $("#member-autocomplete-list").html(listHtml).show();
          } else {
            $("#member-autocomplete-list").html('<div class="p-3 text-muted text-center" style="font-size:0.85rem;">No members found</div>').show();
          }
        },
        error: function() {
          $("#member-autocomplete-list").html('<div class="p-3 text-danger text-center" style="font-size:0.85rem;">Error searching members</div>').show();
        }
      });
    });

    // Delete payment
    $(document).on('click', '.delete-payment-btn', function() {
      if (!confirm('Are you sure you want to delete this payment?')) return;
      const pid = $(this).data('payment-id');
      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_delete_payment'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { payment_id: pid },
        success: function(res) {
          if (!res.success) {
            alert(res.message || 'Delete failed');
            return;
          }
          const invIdMatch = $('#history-meta').text().match(/Invoice ID: (\d+)/);
          if (invIdMatch) {
            const invoiceId = invIdMatch[1];
            // Refresh modal history
            $('.view-history-btn[data-fmbgc-id="' + invoiceId + '"]').trigger('click');
            if (res.summary) {
              const s = res.summary;
              const fmt = function(v){ return '₹' + Math.round(parseFloat(v)||0); };
              $('.gc-received[data-fmbgc-id="' + invoiceId + '"]').text(fmt(s.total_received));
              $('.gc-balance[data-fmbgc-id="' + invoiceId + '"]').text(fmt(s.balance_due));
              // Update button data attributes so further payments use new values
              const btn = $('.update-payment-btn[data-fmbgc-id="' + invoiceId + '"]');
              btn.attr('data-total-received', s.total_received);
              btn.attr('data-balance-due', s.balance_due);
              // Update status badge
              let badgeHtml = '<span class="badge-premium unpaid">Unpaid</span>';
              if (s.balance_due <= 0) badgeHtml = '<span class="badge-premium paid">Paid</span>';
              else if (s.total_received > 0) badgeHtml = '<span class="badge-premium partial">Partial</span>';
              $('.gc-status[data-fmbgc-id="' + invoiceId + '"]').html(badgeHtml);
            }
          }
        },
        error: function() { alert('Server error'); }
      });
    });

    // Select member from autocomplete
    $(document).on("click", ".member-suggestion", function() {
      const itsId = $(this).data("id");
      const name = $(this).data("name");
      $("#user-id").val(itsId);
      $("#member-autocomplete").val(name);
      $("#member-autocomplete-list").empty().hide();
    });

    // Hide suggestions on outside click
    $(document).on("click", function(e) {
      if (!$(e.target).closest('#member-autocomplete, #member-autocomplete-list').length) {
        $("#member-autocomplete-list").empty().hide();
      }
    });

    $("#save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      
      // Native validation check
      if (!$("#save-fmbgc-form")[0].reportValidity()) {
        return;
      }
      
      $contriYear = $("#contri-year").val();
      $userId = $("#user-id").val();
      $contriType = $("#contri-type").val();
      $amount = $("#amount").val();

      if ($userId.length < 1) {
        alert("Please select a member.");
        return;
      }

      if (!$amount || parseFloat($amount) === 0) {
        alert("Amount can't be zero.");
        return;
      }

      $.ajax({
        url: "<?php echo base_url("anjuman/validatefmbgc"); ?>",
        type: "POST",
        data: {
          "contri_year": $contriYear,
          "user_id": $userId,
          "fmb_type": "<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>",
          "contri_type": $contriType,
        },
        dataType: "json",
        success: function(res) {
          if (res.success) {
            $result = confirm(
              "FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> General Contribution found for type " + $contriType + " for member. Are you sure you want to move forward?"
            );
            if ($result) {
              $("#save-fmbgc-form").off("submit").submit();
            }
          } else {
            $("#save-fmbgc-form").off("submit").submit();
          }
        },
        error: function(xhr, status, error) {
          console.log(error);
          // Fallback: submit form anyway if validation check fails so user is not blocked!
          $("#save-fmbgc-form").off("submit").submit();
        }
      });
    });

    // Receive payment button click
    $(document).on("click", ".update-payment-btn", function() {
      const userId = $(this).data("user-id");
      const fmbgcId = $(this).data("fmbgc-id");
      const memberName = $(this).data("member-name");
      const invoiceAmount = parseFloat($(this).data("amount")) || 0;
      const alreadyReceived = parseFloat($(this).data("total-received")) || 0;
      const dueAttr = $(this).data("balance-due");
      let due = (typeof dueAttr !== 'undefined') ? parseFloat(dueAttr) : (invoiceAmount - alreadyReceived);
      if (due < 0) due = 0;

      $("#up-user-id").val(userId);
      $("#up-fmbgc-id").val(fmbgcId);
      $("#up-member-name").text(memberName);
      $("#invoice-amount").text(Math.round(invoiceAmount));
      $("#already-received").text(Math.round(alreadyReceived));
      $("#due-amount").text(Math.round(due));
      $("#payment-amount").attr("max", due).val(due > 0 ? due : '');
      $("#payment-method").val('');
      $("#reference_no").val('');
      $("#bank_name").val('');
      toggleFmbPaymentFields('');
      if (due <= 0) {
        $("#payment-amount-hint").text("Invoice already settled.").addClass("text-danger");
        $("#update-fmbgc-payment-btn").prop("disabled", true);
      } else {
        $("#payment-amount-hint").text("Maximum receivable now: ₹" + Math.round(due)).removeClass("text-danger");
        $("#update-fmbgc-payment-btn").prop("disabled", false);
      }
    });

    // Basic client validation to disallow overpayment
    $("#payment-amount").on("input", function() {
      const max = parseFloat($(this).attr("max"));
      let val = parseFloat($(this).val());
      if (max && val > max) {
        $(this).val(max);
        val = max;
      }
    });

    // Final guard on submit
    $("#update-fmbgc-payment-form").on("submit", function(e) {
      const max = parseFloat($("#payment-amount").attr("max"));
      const val = parseFloat($("#payment-amount").val());
      if (isNaN(val) || val <= 0) {
        alert("Enter a valid payment amount.");
        e.preventDefault();
        return;
      }
      if (!isNaN(max) && val > max + 0.0001) {
        alert("Payment exceeds remaining balance.");
        e.preventDefault();
        return;
      }
    });

    // View payment history
    $(document).on('click', '.view-history-btn', function() {
      const fmbgcId = $(this).data('fmbgc-id');
      const memberName = $(this).data('member-name');
      $('#history-member-name').text(memberName);
      // Reset UI
      $('#history-table tbody').empty();
      $('#history-total-received').text('₹0');
      $('#history-balance-due').text('₹0');
      $('#history-empty').addClass('d-none');
      $('#history-error').addClass('d-none').text('');
      $('#history-meta').text('Loading...');

      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_payment_history'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { fmbgc_id: fmbgcId },
        success: function(res) {
          if (!res.success) {
            $('#history-error').removeClass('d-none').text(res.message || 'Failed to load history');
            $('#history-meta').text('');
            return;
          }
            const inv = res.invoice;
            $('#history-meta').html(
              'Invoice ID: ' + inv.id + ' | Created: ' + (inv.created_at || '') +
              ' | Type: ' + inv.fmb_type + ' | Contribution: ' + inv.contri_type +
              (inv.miqaat_type ? ' | Miqaat: ' + inv.miqaat_type : '') +
              ' | Amount: ₹' + Math.round(inv.amount)
            );
            if (!res.payments || res.payments.length === 0) {
              $('#history-empty').removeClass('d-none');
            } else {
              let rows = '';
              
              res.payments.forEach(function(p, idx) {
                const pid = (p.payment_id && parseInt(p.payment_id) > 0) ? p.payment_id : (p.id || '');
                rows += '<tr>' +
                  '<td>' + (idx + 1) + '</td>' +
                  '<td>' + pid + '</td>' +
                  '<td>' + (p.payment_date || '') + '</td>' +
                  '<td>' + (p.payment_method || '') + '</td>' +
                  '<td class="text-right">₹' + Math.round(p.amount) + '</td>' +
                  '<td>' + (p.remarks ? $('<div>').text(p.remarks).html() : '') + '</td>' +
                  '<td>' + (p.created_at || '') + '</td>' +
                  '<td class="text-nowrap">' +
                    '<button class="btn btn-sm btn-outline-secondary gc-view-receipt mr-2" title="View Receipt" data-payment-id="' + pid + '"><i class="fa-solid fa-file-pdf"></i></button>' +
                    '<button class="btn btn-sm btn-outline-danger delete-payment-btn" data-payment-id="' + pid + '"><i class="fa-solid fa-trash"></i></button>' +
                  '</td>' +
                '</tr>';
              });
              $('#history-table tbody').html(rows);
            }
            $('#history-total-received').text('₹' + Math.round(res.total_received));
            $('#history-balance-due').text('₹' + Math.round(res.balance_due));
        },
        error: function() {
          $('#history-error').removeClass('d-none').text('Server error while loading history');
          $('#history-meta').text('');
        }
      });
    });

    $(".view-description").on("click", function(e) {
      e.preventDefault();
      if ($(this).data("description")) {
        $("#modal-view-description").text($(this).data("description"));
      } else {
        $("#modal-view-description").text("No description found!");
      }
    });

    // Delete entire invoice
    $(document).on('click', '.delete-invoice-btn', function(){
      const invoiceId = $(this).data('invoice-id');
      const memberName = $(this).data('member-name');
      const received = parseFloat($(this).data('total-received'))||0;
      if(!invoiceId) return;
      let msg = 'Delete this invoice for '+memberName+'?';
      if(received>0){
        msg = 'This invoice has ₹'+Math.round(received)+' received. Deleting will remove all related payments. Continue?';
      }
      if(!confirm(msg)) return;
      const $btn = $(this);
      $btn.prop('disabled', true);
      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_delete_invoice'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { invoice_id: invoiceId },
        success: function(res){
          if(!res.success){ alert(res.message||'Delete failed'); return; }
          // Remove row visually
          $btn.closest('tr').fadeOut(200, function(){ $(this).remove(); renumberGcTable(); });
        },
        error: function(){ alert('Server error'); },
        complete: function(){ $btn.prop('disabled', false); }
      });
    });

    function renumberGcTable(){
      $('#gc-invoice-table tbody tr').each(function(i){ $(this).find('td:first').text(i+1); });
    }

    // ---------- Sorting Logic for GC Invoice Table ----------
    (function(){
      const table = document.getElementById('gc-invoice-table');
      if(!table) return;
      const tbody = table.querySelector('tbody');
      const headers = table.querySelectorAll('thead th.gc-sort');
      let lastIndex = -1, lastDir = 'asc';

      function parseValue(cell, type){
        if(!cell) return '';
        let txt = cell.getAttribute('data-sort-value');
        txt = (txt!==null?txt:cell.textContent).trim();
        switch(type){
          case 'number':
            txt = txt.replace(/[^0-9.-]/g,'');
            return txt.length?parseFloat(txt):0;
          case 'date':
            // Expect d-m-Y
            const parts = txt.split('-');
            if(parts.length===3){
              const d = parseInt(parts[0],10); const m = parseInt(parts[1],10)-1; const y = parseInt(parts[2],10);
              return new Date(y,m,d).getTime();
            }
            const t = Date.parse(txt); return isNaN(t)?0:t;
          default:
            return txt.toLowerCase();
        }
      }

      function clearIndicators(except){
        headers.forEach((h,i)=>{ if(i!==except){ h.classList.remove('sorted-asc','sorted-desc'); } });
      }

      headers.forEach((header, hIndex)=>{
        header.addEventListener('click', ()=>{
          const type = header.getAttribute('data-sort-type') || 'text';
          const rows = Array.from(tbody.querySelectorAll('tr'));
          const dir = (lastIndex===hIndex && lastDir==='asc')?'desc':'asc';
          lastIndex = hIndex; lastDir = dir;
          rows.sort((a,b)=>{
            const cellIndex = header.cellIndex;
            const aCell = a.children[cellIndex];
            const bCell = b.children[cellIndex];
            const aVal = parseValue(aCell, type);
            const bVal = parseValue(bCell, type);
            if(aVal < bVal) return dir==='asc'?-1:1;
            if(aVal > bVal) return dir==='asc'?1:-1;
            return 0;
          });
          // Rebuild
          const frag = document.createDocumentFragment(); rows.forEach(r=>frag.appendChild(r));
          tbody.innerHTML = ''; tbody.appendChild(frag);
          renumberGcTable();
          clearIndicators(hIndex);
          header.classList.remove('sorted-asc','sorted-desc');
          header.classList.add(dir==='asc'?'sorted-asc':'sorted-desc');
        });
      });
    })();
    // ---------- End Sorting Logic ----------

    // ---------- Filter Logic for GC Invoice Table ----------
    function applyGcFilters(){
      const nameVal = $('#gc-filter-name').val().trim().toLowerCase();
      const typeVal = $('#gc-filter-ctype').val().trim(); // already lowercased in option values
      const miqaatEl = $('#gc-filter-miqaat-type');
      const miqaatVal = miqaatEl.length ? (miqaatEl.val() || '').trim().toLowerCase() : '';
      const isThaali = <?php echo $type == 1 ? 'true' : 'false'; ?>;
      
      $('#gc-invoice-table tbody tr').each(function(){
        const $tr = $(this);
        const its = $tr.children('td').eq(2).text().trim().toLowerCase();
        const member = $tr.children('td').eq(3).text().trim().toLowerCase();
        const ctype = $tr.children('td').eq(4).text().trim().toLowerCase();
        const miqaat = isThaali ? '' : $tr.children('td').eq(5).text().trim().toLowerCase();
        let show = true;
        if(nameVal && (member.indexOf(nameVal) === -1) && (its.indexOf(nameVal) === -1)) show = false;
        if(typeVal && ctype !== typeVal) show = false;
        if(!isThaali && miqaatVal && miqaat !== miqaatVal) show = false;
        $tr.toggle(show);
      });
      // Renumber visible rows only
      let i=1; $('#gc-invoice-table tbody tr:visible').each(function(){ $(this).children('td').eq(0).text(i++); });
    }
    $('#gc-filter-name').on('input', applyGcFilters);
    $('#gc-filter-ctype').on('change', applyGcFilters);
    if ($('#gc-filter-miqaat-type').length) {
      $('#gc-filter-miqaat-type').on('change', applyGcFilters);
    }
    $('#gc-filter-clear').on('click', function(){
      $('#gc-filter-name').val('');
      $('#gc-filter-ctype').val('');
      if ($('#gc-filter-miqaat-type').length) {
        $('#gc-filter-miqaat-type').val('');
      }
      $('#gc-invoice-table tbody tr').show();
      renumberGcTable();
    });
    // ---------- End Filter Logic ----------
  });

  // View Receipt (GC payment) via AJAX -> open PDF blob
  $(document).on('click', '.gc-view-receipt', function() {
    const pid = $(this).data('payment-id');
    if (!pid) return;
    const $btn = $(this);
    $btn.prop('disabled', true).addClass('disabled');
    $.ajax({
      url: '<?php echo base_url('common/generate_pdf'); ?>',
      method: 'POST',
      data: { id: pid, for: 3 },
      xhrFields: { responseType: 'blob' },
      success: function(blob, status, xhr) {
        const ct = (xhr.getResponseHeader('Content-Type') || '').toLowerCase();
        if (!ct.includes('pdf')) {
          // Try read as text for error
          const reader = new FileReader();
          reader.onload = function() { alert('Unexpected response: ' + String(reader.result).substring(0,200)); };
          reader.readAsText(blob);
          return;
        }
        const url = URL.createObjectURL(blob);
        window.open(url, '_blank');
        setTimeout(() => URL.revokeObjectURL(url), 60000);
      },
      error: function(xhr) {
        alert('Failed to generate receipt (status ' + xhr.status + ').');
      },
      complete: function() {
        $btn.prop('disabled', false).removeClass('disabled');
      }
    });
  });
</script>