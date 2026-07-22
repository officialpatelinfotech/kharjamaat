<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
#tsApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: #1a1610;
  background: #faf7f0;
  min-height: 100vh;
  padding-bottom: 60px;
}
#tsApp {
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
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
}

/* Header Panel */
#tsApp .anj-header {
  margin-bottom: 24px;
}
#tsApp .anj-header-inner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 16px;
  padding: 20px 24px;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}
#tsApp .anj-header-inner::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#tsApp .anj-eyebrow {
  font-size: .65rem;
  font-weight: 700;
  letter-spacing: 1.4px;
  text-transform: uppercase;
  color: rgba(255,255,255,.65);
  margin-bottom: 4px;
}
#tsApp .anj-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.55rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.15;
  margin: 0;
}

/* Premium Button style */
#tsApp .btn-premium {
  font-weight: 700;
  font-size: 0.78rem;
  padding: 8px 16px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.2s;
  text-decoration: none;
}
#tsApp .btn-premium-gold {
  background: var(--gold);
  color: #fff;
  border: 1px solid var(--gold);
}
#tsApp .btn-premium-gold:hover {
  background: #8f6808;
  border-color: #8f6808;
  color: #fff;
}
#tsApp .btn-premium-outline {
  border: 1.5px solid var(--border);
  color: var(--text-2);
  background: var(--surface);
}
#tsApp .btn-premium-outline:hover {
  background: var(--gold-muted);
  color: #78520a;
  border-color: var(--gold);
}

/* Premium Dashboard Card */
#tsApp .ts-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px 18px;
  box-shadow: var(--shadow-sm);
  margin-bottom: 20px;
}
#tsApp .ts-card-title {
  font-size: .8rem;
  font-weight: 700;
  letter-spacing: 0.8px;
  text-transform: uppercase;
  color: var(--text-2);
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Month Navigation Slider */
#tsApp .month-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 8px 12px;
  margin-bottom: 16px;
}
#tsApp .month-nav-btn {
  background: none;
  border: none;
  color: var(--gold);
  font-size: 0.95rem;
  cursor: pointer;
  padding: 4px 8px;
  transition: opacity 0.15s;
}
#tsApp .month-nav-btn:hover {
  opacity: 0.75;
}
#tsApp .month-nav-title {
  font-family: 'Literata', Georgia, serif;
  font-weight: 600;
  font-size: 1rem;
  color: var(--text-1);
}

/* Stats summary items */
#tsApp .stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}
@media (max-width: 768px) {
  #tsApp .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
#tsApp .stats-tile {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 12px 14px;
  text-align: center;
  box-shadow: var(--shadow-sm);
}
#tsApp .stats-val {
  font-size: 1.45rem;
  font-weight: 800;
  line-height: 1.1;
  color: var(--text-1);
}
#tsApp .stats-lbl {
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 0.8px;
  text-transform: uppercase;
  color: var(--text-3);
  margin-top: 4px;
}

/* Progress indicator bar */
#tsApp .progress-indicator-container {
  height: 8px;
  background: var(--surface-2);
  border-radius: 4px;
  overflow: hidden;
  margin-top: 6px;
}
#tsApp .progress-indicator-bar {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease;
}

/* Day selector timeline slider */
#tsApp .timeline-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}
#tsApp .timeline-container {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 4px 2px;
  width: 100%;
}
#tsApp .timeline-container::-webkit-scrollbar {
  height: 6px;
}
#tsApp .timeline-container::-webkit-scrollbar-track {
  background: var(--surface-2);
}
#tsApp .timeline-container::-webkit-scrollbar-thumb {
  background: var(--border);
  border-radius: 3px;
}
#tsApp .timeline-item {
  flex: 0 0 115px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 8px 10px;
  text-align: center;
  cursor: pointer;
  user-select: none;
  transition: all 0.2s;
}
#tsApp .timeline-item:hover {
  border-color: var(--gold);
}
#tsApp .timeline-item.active {
  background: var(--gold-muted);
  border-color: var(--gold);
  box-shadow: 0 2px 6px rgba(184,134,11,0.15);
}
#tsApp .timeline-date-lbl {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--text-1);
}
#tsApp .timeline-hijri-lbl {
  font-size: 0.58rem;
  color: var(--text-3);
}
#tsApp .timeline-ratio-lbl {
  font-size: 0.7rem;
  font-weight: 800;
  margin-top: 4px;
}
#tsApp .timeline-ratio-lbl.signed {
  color: var(--green);
}
#tsApp .timeline-ratio-lbl.pending {
  color: var(--red);
}
#tsApp .timeline-nav-btn {
  background: var(--surface);
  border: 1px solid var(--border);
  color: var(--text-2);
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  position: absolute;
  z-index: 2;
  box-shadow: var(--shadow-sm);
  transition: all 0.15s;
}
#tsApp .timeline-nav-btn:hover {
  background: var(--gold-muted);
  color: var(--gold);
  border-color: var(--gold);
}
#tsApp .timeline-nav-prev { left: -14px; }
#tsApp .timeline-nav-next { right: -14px; }

/* Filter grid and inputs */
#tsApp .filter-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr) auto;
  gap: 10px;
  align-items: end;
  margin-bottom: 18px;
}
@media (max-width: 991.98px) {
  #tsApp .filter-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
#tsApp .form-control-premium {
  background: var(--surface);
  border: 1px solid var(--border);
  color: var(--text-1);
  font-size: 0.8rem;
  font-weight: 600;
  height: 38px;
  border-radius: 8px;
  padding: 6px 12px;
  width: 100%;
}
#tsApp .form-control-premium:focus {
  border-color: var(--gold);
  outline: none;
}
#tsApp .filter-label {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--text-2);
  margin-bottom: 4px;
  display: block;
}

/* Bulk Action Panel */
#tsApp .bulk-action-panel {
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 12px 16px;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}
#tsApp .bulk-action-controls {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

/* Interactive Families Table */
#tsApp .table-premium {
  width: 100%;
  margin-bottom: 0;
  border-collapse: collapse;
}
#tsApp .table-premium th {
  background: var(--surface-2);
  border-bottom: 2px solid var(--border);
  color: var(--text-3);
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 12px 14px;
  text-align: left;
}
#tsApp .table-premium td {
  padding: 12px 14px;
  vertical-align: middle;
  border-bottom: 1px solid var(--border-light, #f0ece0);
  font-size: 0.8rem;
  color: var(--text-2);
}
#tsApp .table-premium tr:hover td {
  background: #fdfcf7;
}

/* Badges */
#tsApp .status-badge {
  font-size: 0.65rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}
#tsApp .status-badge-signed {
  background: var(--green-bg);
  color: var(--green);
}
#tsApp .status-badge-pending {
  background: var(--red-bg);
  color: var(--red);
}

/* Pagination container */
#tsApp .pagination-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 14px;
}
#tsApp .pagination-info {
  font-size: 0.72rem;
  color: var(--text-3);
  font-weight: 600;
}
#tsApp .pagination-controls {
  display: flex;
  gap: 4px;
}
#tsApp .pagination-btn {
  background: var(--surface);
  border: 1px solid var(--border);
  color: var(--text-2);
  font-size: 0.75rem;
  font-weight: 700;
  height: 28px;
  padding: 0 10px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  user-select: none;
  transition: all 0.15s;
}
#tsApp .pagination-btn:hover {
  background: var(--gold-muted);
  color: var(--gold);
  border-color: var(--gold);
}
#tsApp .pagination-btn.active {
  background: var(--gold);
  color: #fff;
  border-color: var(--gold);
}
#tsApp .pagination-btn.disabled {
  opacity: 0.5;
  pointer-events: none;
}
</style>

<div id="tsApp" class="margintopcontainer pt-5">
  <div class="container pb-4">
    <!-- Back Button -->
    <div class="mb-4">
      <a href="<?php echo htmlspecialchars($active_controller); ?>" class="btn-premium btn-premium-outline">
        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
      </a>
    </div>

    <!-- Header Panel -->
    <div class="anj-header">
      <div class="anj-header-inner">
        <div class="anj-title-group">
          <p class="anj-eyebrow">Fizalat Mawamil al-Burhaniyah</p>
          <h1 class="anj-title">Thaali Sign-up Dashboard</h1>
        </div>
      </div>
    </div>

    <!-- Month Navigation Bar -->
    <div class="month-nav">
      <button type="button" class="month-nav-btn" onclick="switchMonth('<?php echo $prev_hijri; ?>')">
        <i class="fa fa-chevron-left"></i> Prev Month
      </button>
      <div class="text-center">
        <div class="month-nav-title"><?php echo htmlspecialchars($hijri_month_name) . ' ' . htmlspecialchars($hijri_year) . 'H'; ?></div>
        <?php if (!empty($greg_month_range)): ?>
          <small class="text-muted" style="font-size: 0.72rem; font-weight: 600;"><?php echo htmlspecialchars($greg_month_range); ?></small>
        <?php endif; ?>
      </div>
      <button type="button" class="month-nav-btn" onclick="switchMonth('<?php echo $next_hijri; ?>')">
        Next Month <i class="fa fa-chevron-right"></i>
      </button>
    </div>

    <!-- Overall summary cards (for active date) -->
    <div class="stats-grid">
      <div class="stats-tile">
        <div class="stats-val" id="stat-total-families">0</div>
        <div class="stats-lbl">Total Families</div>
      </div>
      <div class="stats-tile">
        <div class="stats-val" id="stat-signed-up">0</div>
        <div class="stats-lbl">Signed Up</div>
      </div>
      <div class="stats-tile">
        <div class="stats-val" id="stat-pending">0</div>
        <div class="stats-lbl">Pending / Not Signed</div>
      </div>
      <div class="stats-tile" style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
        <div class="stats-val" style="font-size: 1.25rem;"><span id="stat-progress-pct">0</span>%</div>
        <div class="stats-lbl">Progress</div>
        <div class="progress-indicator-container">
          <div class="progress-indicator-bar" id="stat-progress-bar" style="width: 0%; background: linear-gradient(90deg, var(--green), var(--gold-light));"></div>
        </div>
      </div>
    </div>

    <!-- Day-wise breakdown timeline slider -->
    <div class="ts-card">
      <div class="ts-card-title">
        <i class="fa-solid fa-chart-simple text-primary"></i> 2. Day-wise Signup Breakdown for <?php echo htmlspecialchars($hijri_month_name) . ' ' . htmlspecialchars($hijri_year) . 'H'; ?>
      </div>
      
      <div class="timeline-wrapper">
        <button type="button" class="timeline-nav-btn timeline-nav-prev" onclick="scrollTimeline(-250)">
          <i class="fa fa-chevron-left"></i>
        </button>
        
        <div class="timeline-container" id="timeline-slider">
          <?php if (!empty($menus)): ?>
            <?php foreach ($menus as $m): ?>
              <?php
                $is_active = ($m['date'] === $active_date);
                $pct_signed = $total_hofs > 0 ? ($m['signed_up_count'] / $total_hofs) * 100 : 0;
              ?>
              <div class="timeline-item <?php echo $is_active ? 'active' : ''; ?>" data-date="<?php echo htmlspecialchars($m['date'], ENT_QUOTES); ?>" onclick="selectTimelineDate(this)">
                <div class="timeline-date-lbl"><?php echo date('D, d M Y', strtotime($m['date'])); ?></div>
                <div class="timeline-hijri-lbl"><?php echo htmlspecialchars($m['hijri_label']); ?></div>
                <div class="timeline-ratio-lbl <?php echo $m['signed_up_count'] > 0 ? 'signed' : 'pending'; ?>" data-date-counts="<?php echo htmlspecialchars($m['date'], ENT_QUOTES); ?>">
                  <?php echo $m['signed_up_count'] . ' / ' . $total_hofs; ?>
                  <span style="font-size:0.6rem; font-weight:normal; display:block;"><?php echo round($pct_signed); ?>% signed</span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-4 text-muted w-100" style="font-size:0.83rem;">
              <i class="fa fa-calendar-xmark fa-2x mb-2 d-block"></i> No menu dates found for this month.
            </div>
          <?php endif; ?>
        </div>
        
        <button type="button" class="timeline-nav-btn timeline-nav-next" onclick="scrollTimeline(250)">
          <i class="fa fa-chevron-right"></i>
        </button>
      </div>
      
      <div style="font-size: 0.72rem; font-weight: bold; color: var(--text-3); text-align: center;">
        <span class="mr-3"><i class="fa fa-circle" style="color:var(--green);"></i> Green = Signed Up</span>
        <span><i class="fa fa-circle" style="color:var(--red);"></i> Red = Not Signed Up</span>
      </div>
    </div>

    <!-- Filter Tools -->
    <div class="ts-card">
      <div class="ts-card-title">
        <i class="fa-solid fa-filter text-primary"></i> 3. Filters
      </div>

      <div class="filter-grid">
        <div>
          <span class="filter-label">Sector</span>
          <select id="filter-sector" class="form-control-premium" <?php echo $is_sector_incharge ? 'disabled' : ''; ?>>
            <option value="">All Sectors</option>
            <?php foreach ($sectors_list as $s): ?>
              <option value="<?php echo htmlspecialchars($s, ENT_QUOTES); ?>" <?php echo $forced_sector === $s ? 'selected' : ''; ?>><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <span class="filter-label">Sub-Sector</span>
          <select id="filter-sub-sector" class="form-control-premium">
            <option value="">All Sub-Sectors</option>
            <?php foreach ($sub_sectors_list as $ss): ?>
              <option value="<?php echo htmlspecialchars($ss, ENT_QUOTES); ?>"><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <span class="filter-label">Signup Status</span>
          <select id="filter-status" class="form-control-premium">
            <option value="all">All</option>
            <option value="signed">Signed Up</option>
            <option value="pending">Not Signed Up</option>
          </select>
        </div>
        <div>
          <span class="filter-label">Search</span>
          <input type="text" id="search-q" class="form-control-premium" placeholder="Search HOF name or ITS ID..." autocomplete="off">
        </div>
        <div>
          <button type="button" class="btn btn-outline-secondary" style="height:38px; border-radius:8px;" onclick="resetFilters()">
            <i class="fa-solid fa-sync"></i> Reset
          </button>
        </div>
      </div>
    </div>

    <!-- Bulk Action -->
    <div class="ts-card">
      <div class="ts-card-title">
        <i class="fa-solid fa-layer-group text-primary"></i> 4. Bulk Action
      </div>
      
      <div class="bulk-action-panel" style="margin-bottom: 0;">
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-2); display:flex; align-items:center; gap:12px;">
          <button type="button" class="btn btn-sm btn-outline-primary" style="font-weight:700; font-size:0.75rem;" onclick="selectAllFilteredHOFs()">Select All</button>
          <button type="button" class="btn btn-sm btn-outline-secondary" style="font-weight:700; font-size:0.75rem;" onclick="deselectAllHOFs()">Deselect All</button>
          <span style="color:var(--text-3); font-weight:normal;">|</span>
          <span><span id="bulk-selection-count">0</span> families selected</span>
        </div>
        <div class="bulk-action-controls">
          <select id="bulk-action" class="form-control-premium" style="width:180px;">
            <option value="1">Sign-up (Want to Sign-up)</option>
            <option value="2">Remove Sign-up</option>
          </select>
          <select id="bulk-scope" class="form-control-premium" style="width:230px;">
            <option value="date">Selected Date Only</option>
            <option value="month">Selected Hijri Month (<?php echo htmlspecialchars($hijri_month_name); ?>)</option>
          </select>
          <div id="bulk-thali-size-group" style="display:inline-block;">
            <select id="bulk-thali-size" class="form-control-premium" style="width:150px;">
              <option value="Medium">Medium</option>
              <option value="Big">Big</option>
              <option value="Double Big">Double Big</option>
              <option value="Triple Big">Triple Big</option>
              <option value="Double Medium">Double Medium</option>
              <option value="China">China</option>
              <option value="Double China">Double China</option>
              <option value="Small">Small</option>
              <option value="Large">Large</option>
            </select>
          </div>
          <button type="button" class="btn-premium btn-premium-gold" onclick="performBulkAction()">
            <i class="fa-solid fa-circle-check"></i> Perform Action
          </button>
        </div>
      </div>
    </div>

    <!-- Active day Families Table -->
    <div class="ts-card p-0" style="overflow:hidden;">
      <div style="padding:16px 18px; border-bottom:1.5px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
        <span style="font-family: 'Literata', Georgia, serif; font-size:1rem; font-weight:600; color:var(--gold);">
          5. Families List for: <span id="list-date-label-greg"><?php echo !empty($active_date) ? date('D, d M Y', strtotime($active_date)) : 'No Date Selected'; ?></span>
          <span style="font-size:0.75rem; color:var(--text-3); font-family:'Plus Jakarta Sans',sans-serif; font-weight:normal;" id="list-date-label-hijri"><?php echo !empty($active_date_hijri_label) ? '(' . htmlspecialchars($active_date_hijri_label) . ')' : ''; ?></span>
        </span>
        <div style="font-size:0.75rem; font-weight:700; color:var(--text-3);" id="list-aggregate-stats">
          Total: <span id="tbl-stat-total">0</span> | Signed Up: <span id="tbl-stat-signed" class="text-success">0</span> | Not Signed: <span id="tbl-stat-pending" class="text-danger">0</span>
        </div>
      </div>

      <div style="overflow-x:auto;">
        <table class="table-premium" id="families-table">
          <thead>
            <tr>
              <th style="width: 40px;"><input type="checkbox" id="tbl-select-page-checkbox"></th>
              <th style="width: 100px;">ITS No.</th>
              <th>HOF Name</th>
              <th>Sector</th>
              <th>Sub-Sector</th>
              <th>Signup Status</th>
              <th style="width: 120px; text-align: center;">Action</th>
            </tr>
          </thead>
          <tbody id="families-tbody">
            <!-- Loaded via AJAX -->
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div style="padding:12px 18px; border-top:1.5px solid var(--border);">
        <div class="pagination-container">
          <div class="pagination-info" id="pagination-info">Showing 0 to 0 of 0 entries</div>
          <div class="pagination-controls" id="pagination-controls">
            <!-- Populated dynamically -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bulk Progress Modal -->
<div class="modal fade" id="bulkProgressModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
      <div class="modal-body text-center p-4">
        <h5 class="modal-title mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--text-1);">Processing Bulk Sign-ups</h5>
        <p class="text-muted small mb-4" id="bulk-progress-text">Starting bulk action...</p>
        <div class="progress" style="height: 10px; border-radius: 5px; background-color: #f0f0f0; overflow: hidden; margin-bottom: 10px;">
          <div id="bulk-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 0%; transition: width 0.2s ease;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span class="small font-weight-bold text-dark" id="bulk-progress-percent">0%</span>
      </div>
    </div>
  </div>
</div>

<script>
  var selectedDate = '<?php echo $active_date; ?>';
  var allFamilies = [];
  var filteredFamilies = [];
  var selectedHOFSet = new Set();
  var pageSize = 2000;
  var currentPage = 1;

  jQuery(document).ready(function() {
    // Initial fetch of dashboard table data
    loadDashboardDateData(selectedDate);

    // Toggle bulk size input based on sign-up/remove action
    jQuery('#bulk-action').on('change', function() {
      if (jQuery(this).val() == '1') {
        jQuery('#bulk-thali-size-group').show();
      } else {
        jQuery('#bulk-thali-size-group').hide();
      }
    });

    // Realtime filter triggers
    jQuery('#filter-sector, #filter-sub-sector, #filter-status').on('change', function() {
      applyClientFiltering();
    });
    jQuery('#search-q').on('input', function() {
      applyClientFiltering();
    });

    // Table page-select-all checkbox handler
    jQuery(document).on('change', '#tbl-select-page-checkbox', function() {
      const isChecked = jQuery(this).prop('checked');
      jQuery('.family-check').each(function() {
        const its = jQuery(this).val();
        if (isChecked) {
          jQuery(this).prop('checked', true);
          selectedHOFSet.add(its);
        } else {
          jQuery(this).prop('checked', false);
          selectedHOFSet.delete(its);
        }
      });
      updateBulkSelectionCount();
    });

    jQuery(document).on('change', '.family-check', function() {
      const its = jQuery(this).val();
      if (jQuery(this).prop('checked')) {
        selectedHOFSet.add(its);
      } else {
        selectedHOFSet.delete(its);
      }
      // Sync the header checkbox
      checkPageCheckboxSync();
      updateBulkSelectionCount();
    });
  });

  function scrollTimeline(val) {
    var container = document.getElementById('timeline-slider');
    if (container) {
      container.scrollLeft += val;
    }
  }

  function switchMonth(hijriVal) {
    var fromVal = '<?php echo isset($from) ? urlencode($from) : ""; ?>';
    var url = '<?php echo base_url("common/thaali_signup_dashboard"); ?>?hijri=' + hijriVal;
    if (fromVal) {
      url += '&from=' + fromVal;
    }
    window.location.href = url;
  }

  function selectTimelineDate(elem) {
    jQuery('.timeline-item').removeClass('active');
    jQuery(elem).addClass('active');
    const newDate = jQuery(elem).attr('data-date');
    selectedDate = newDate;
    loadDashboardDateData(newDate);
  }

  function loadDashboardDateData(dateVal) {
    if (!dateVal) {
      jQuery('#families-tbody').html('<tr><td colspan="7" class="text-center py-4 text-muted"><i class="fa fa-info-circle mb-2 d-block"></i> No menu dates available. Please select a month with active menus or add menu dates first.</td></tr>');
      jQuery('#stat-total-families').text('0');
      jQuery('#stat-signed-up').text('0 (0%)');
      jQuery('#stat-pending').text('0 (0%)');
      jQuery('#stat-progress-pct').text('0');
      jQuery('#stat-progress-bar').css('width', '0%');
      jQuery('#tbl-stat-total').text('0');
      jQuery('#tbl-stat-signed').text('0');
      jQuery('#tbl-stat-pending').text('0');
      return;
    }
    jQuery('#families-tbody').html('<tr><td colspan="7" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x text-primary mb-2 d-block"></i> Loading families list...</td></tr>');
    
    const sectorVal = jQuery('#filter-sector').val();
    const subSectorVal = jQuery('#filter-sub-sector').val();
    const statusVal = jQuery('#filter-status').val();

    jQuery.ajax({
      url: '<?php echo base_url("common/get_thaali_signup_dashboard_data_ajax"); ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        date: dateVal,
        sector: sectorVal,
        sub_sector: subSectorVal,
        signup_status: statusVal
      },
      success: function(res) {
        if (!res || !res.success) {
          jQuery('#families-tbody').html('<tr><td colspan="7" class="text-center py-4 text-danger">Failed to load data.</td></tr>');
          return;
        }

        // 1) Update top overall stats cards
        jQuery('#stat-total-families').text(res.stats.total);
        jQuery('#stat-signed-up').text(res.stats.signed_up + ' (' + res.stats.progress_pct + '%)');
        jQuery('#stat-pending').text(res.stats.pending + ' (' + (100 - res.stats.progress_pct) + '%)');
        jQuery('#stat-progress-pct').text(res.stats.progress_pct);
        jQuery('#stat-progress-bar').css('width', res.stats.progress_pct + '%');

        // Update ratios on timeline for this date dynamically
        const ratioText = res.stats.signed_up + ' / ' + res.stats.total;
        const ratioElem = jQuery('[data-date-counts="' + dateVal + '"]');
        if (ratioElem.length) {
          ratioElem.html(ratioText + '<span style="font-size:0.6rem; font-weight:normal; display:block;">' + res.stats.progress_pct + '% signed</span>');
          ratioElem.removeClass('signed pending').addClass(res.stats.signed_up > 0 ? 'signed' : 'pending');
        }

        // 2) Update list labels
        const dt = new Date(dateVal + 'T00:00:00');
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        jQuery('#list-date-label-greg').text(dayNames[dt.getDay()] + ', ' + dt.getDate() + ' ' + monthNames[dt.getMonth()] + ' ' + dt.getFullYear());
        jQuery('#list-date-label-hijri').text('(' + res.hijri_label + ')');

        // Store families list locally for client filtering & pagination
        allFamilies = res.families || [];
        applyClientFiltering();
      },
      error: function() {
        jQuery('#families-tbody').html('<tr><td colspan="7" class="text-center py-4 text-danger">Connection error. Please try again.</td></tr>');
      }
    });
  }

  function applyClientFiltering() {
    const sectorFilter = jQuery('#filter-sector').val();
    const subSectorFilter = jQuery('#filter-sub-sector').val();
    const statusFilter = jQuery('#filter-status').val();
    const q = jQuery('#search-q').val().toLowerCase().trim();

    // Reset checkall state
    jQuery('#bulk-select-main').prop('checked', false);
    jQuery('#bulk-selection-count').text('0');

    filteredFamilies = allFamilies.filter(function(f) {
      // Sector filter
      if (sectorFilter && f.sector !== sectorFilter) return false;
      // Sub-Sector filter
      if (subSectorFilter && f.sub_sector !== subSectorFilter) return false;
      // Status filter
      if (statusFilter === 'signed' && f.want_thali != 1) return false;
      if (statusFilter === 'pending' && f.want_thali == 1) return false;
      // Search query
      if (q) {
        const name = (f.hof_name || '').toLowerCase();
        const its = (f.hof_its_id || '').toLowerCase();
        if (name.indexOf(q) === -1 && its.indexOf(q) === -1) return false;
      }
      return true;
    });

    // Recalculate filtered stats
    let total = 0, signed = 0, pending = 0;
    filteredFamilies.forEach(function(f) {
      total++;
      if (f.want_thali == 1) signed++;
      else pending++;
    });
    jQuery('#tbl-stat-total').text(total);
    jQuery('#tbl-stat-signed').text(signed);
    jQuery('#tbl-stat-pending').text(pending);

    currentPage = 1;
    renderFamiliesTable();
  }

  function renderFamiliesTable() {
    const tbody = jQuery('#families-tbody');
    tbody.empty();

    if (filteredFamilies.length === 0) {
      tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted" style="font-size:0.85rem;"><i class="fa fa-info-circle mb-2"></i> No families found matching the criteria.</td></tr>');
      jQuery('#pagination-info').text('Showing 0 to 0 of 0 entries');
      jQuery('#pagination-controls').empty();
      return;
    }

    const startIdx = (currentPage - 1) * pageSize;
    const endIdx = Math.min(startIdx + pageSize, filteredFamilies.length);
    const paginated = filteredFamilies.slice(startIdx, endIdx);

    paginated.forEach(function(f) {
      const isSigned = (f.want_thali == 1);
      
      let statusBadge = '';
      if (isSigned) {
        statusBadge = '<span class="status-badge status-badge-signed"><i class="fa-solid fa-circle-check"></i> Signed Up (' + (f.thali_size || 'Medium') + ')</span>';
      } else {
        statusBadge = '<span class="status-badge status-badge-pending"><i class="fa-solid fa-circle-xmark"></i> Not Signed Up</span>';
      }

      let actionBtn = '';
      if (isSigned) {
        actionBtn = '<button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="performSingleAction(\'' + f.hof_its_id + '\', 2)" style="font-weight:700;font-size:0.7rem;"><i class="fa fa-times mr-1"></i> Remove</button>';
      } else {
        actionBtn = '<button type="button" class="btn btn-sm btn-outline-success w-100" onclick="performSingleAction(\'' + f.hof_its_id + '\', 1)" style="font-weight:700;font-size:0.7rem;"><i class="fa fa-check mr-1"></i> Sign-up</button>';
      }

      const tr = jQuery('<tr></tr>');
      tr.append('<td><input type="checkbox" class="family-check" value="' + f.hof_its_id + '"></td>');
      tr.append('<td style="font-weight:800; color:var(--text-1);">' + f.hof_its_id + '</td>');
      tr.append('<td style="font-weight:600; color:var(--text-1);">' + f.hof_name + '</td>');
      tr.append('<td>' + (f.sector || '—') + '</td>');
      tr.append('<td>' + (f.sub_sector || '—') + '</td>');
      tr.append('<td>' + statusBadge + '</td>');
      tr.append('<td>' + actionBtn + '</td>');
      tbody.append(tr);
    });

    jQuery('#pagination-info').text('Showing ' + (startIdx + 1) + ' to ' + endIdx + ' of ' + filteredFamilies.length + ' entries');
    renderPaginationControls();
  }

  function renderPaginationControls() {
    const container = jQuery('#pagination-controls');
    container.empty();

    const totalPages = Math.ceil(filteredFamilies.length / pageSize);
    if (totalPages <= 1) return;

    // Prev Button
    const prevBtn = jQuery('<div class="pagination-btn"><i class="fa fa-chevron-left"></i></div>');
    if (currentPage === 1) prevBtn.addClass('disabled');
    prevBtn.on('click', function() {
      if (currentPage > 1) {
        currentPage--;
        renderFamiliesTable();
      }
    });
    container.append(prevBtn);

    // Number Buttons
    for (let i = 1; i <= totalPages; i++) {
      if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
        const pageBtn = jQuery('<div class="pagination-btn"></div>').text(i);
        if (i === currentPage) pageBtn.addClass('active');
        pageBtn.on('click', function() {
          currentPage = i;
          renderFamiliesTable();
        });
        container.append(pageBtn);
      } else if (i === currentPage - 3 || i === currentPage + 3) {
        container.append('<div style="align-self:end; margin:0 4px; color:var(--text-3);">...</div>');
      }
    }

    // Next Button
    const nextBtn = jQuery('<div class="pagination-btn"><i class="fa fa-chevron-right"></i></div>');
    if (currentPage === totalPages) nextBtn.addClass('disabled');
    nextBtn.on('click', function() {
      if (currentPage < totalPages) {
        currentPage++;
        renderFamiliesTable();
      }
    });
    container.append(nextBtn);
  }

  function renderFamiliesTable() {
    const tbody = jQuery('#families-tbody');
    tbody.empty();

    // Reset table header checkbox
    jQuery('#tbl-select-page-checkbox').prop('checked', false);

    if (filteredFamilies.length === 0) {
      tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted" style="font-size:0.85rem;"><i class="fa fa-info-circle mb-2"></i> No families found matching the criteria.</td></tr>');
      jQuery('#pagination-info').text('Showing 0 to 0 of 0 entries');
      jQuery('#pagination-controls').empty();
      return;
    }

    const startIdx = (currentPage - 1) * pageSize;
    const endIdx = Math.min(startIdx + pageSize, filteredFamilies.length);
    const paginated = filteredFamilies.slice(startIdx, endIdx);

    paginated.forEach(function(f) {
      const isSigned = (f.want_thali == 1);
      const isChecked = selectedHOFSet.has(f.hof_its_id);
      
      let statusBadge = '';
      if (isSigned) {
        statusBadge = '<span class="status-badge status-badge-signed"><i class="fa-solid fa-circle-check"></i> Signed Up (' + (f.thali_size || 'Medium') + ')</span>';
      } else {
        statusBadge = '<span class="status-badge status-badge-pending"><i class="fa-solid fa-circle-xmark"></i> Not Signed Up</span>';
      }

      let actionBtn = '';
      if (isSigned) {
        actionBtn = '<button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="performSingleAction(\'' + f.hof_its_id + '\', 2)" style="font-weight:700;font-size:0.7rem;"><i class="fa fa-times mr-1"></i> Remove</button>';
      } else {
        actionBtn = '<button type="button" class="btn btn-sm btn-outline-success w-100" onclick="performSingleAction(\'' + f.hof_its_id + '\', 1)" style="font-weight:700;font-size:0.7rem;"><i class="fa fa-check mr-1"></i> Sign-up</button>';
      }

      const tr = jQuery('<tr></tr>');
      tr.append('<td><input type="checkbox" class="family-check" value="' + f.hof_its_id + '" ' + (isChecked ? 'checked' : '') + '></td>');
      tr.append('<td style="font-weight:800; color:var(--text-1);">' + f.hof_its_id + '</td>');
      tr.append('<td style="font-weight:600; color:var(--text-1);">' + f.hof_name + '</td>');
      tr.append('<td>' + (f.sector || '—') + '</td>');
      tr.append('<td>' + (f.sub_sector || '—') + '</td>');
      tr.append('<td>' + statusBadge + '</td>');
      tr.append('<td>' + actionBtn + '</td>');
      tbody.append(tr);
    });

    checkPageCheckboxSync();

    jQuery('#pagination-info').text('Showing ' + (startIdx + 1) + ' to ' + endIdx + ' of ' + filteredFamilies.length + ' entries');
    renderPaginationControls();
  }

  function checkPageCheckboxSync() {
    let allPageChecked = true;
    let anyCheckboxes = false;
    jQuery('.family-check').each(function() {
      anyCheckboxes = true;
      if (!jQuery(this).prop('checked')) {
        allPageChecked = false;
      }
    });
    jQuery('#tbl-select-page-checkbox').prop('checked', anyCheckboxes && allPageChecked);
  }

  function renderPaginationControls() {
    const container = jQuery('#pagination-controls');
    container.empty();

    const totalPages = Math.ceil(filteredFamilies.length / pageSize);
    if (totalPages <= 1) return;

    // Prev Button
    const prevBtn = jQuery('<div class="pagination-btn"><i class="fa fa-chevron-left"></i></div>');
    if (currentPage === 1) prevBtn.addClass('disabled');
    prevBtn.on('click', function() {
      if (currentPage > 1) {
        currentPage--;
        renderFamiliesTable();
      }
    });
    container.append(prevBtn);

    // Number Buttons
    for (let i = 1; i <= totalPages; i++) {
      if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
        const pageBtn = jQuery('<div class="pagination-btn"></div>').text(i);
        if (i === currentPage) pageBtn.addClass('active');
        pageBtn.on('click', function() {
          currentPage = i;
          renderFamiliesTable();
        });
        container.append(pageBtn);
      } else if (i === currentPage - 3 || i === currentPage + 3) {
        container.append('<div style="align-self:end; margin:0 4px; color:var(--text-3);">...</div>');
      }
    }

    // Next Button
    const nextBtn = jQuery('<div class="pagination-btn"><i class="fa fa-chevron-right"></i></div>');
    if (currentPage === totalPages) nextBtn.addClass('disabled');
    nextBtn.on('click', function() {
      if (currentPage < totalPages) {
        currentPage++;
        renderFamiliesTable();
      }
    });
    container.append(nextBtn);
  }

  function selectAllFilteredHOFs() {
    filteredFamilies.forEach(function(f) {
      selectedHOFSet.add(f.hof_its_id);
    });
    jQuery('.family-check').prop('checked', true);
    jQuery('#tbl-select-page-checkbox').prop('checked', true);
    updateBulkSelectionCount();
  }

  function deselectAllHOFs() {
    selectedHOFSet.clear();
    jQuery('.family-check').prop('checked', false);
    jQuery('#tbl-select-page-checkbox').prop('checked', false);
    updateBulkSelectionCount();
  }

  function updateBulkSelectionCount() {
    jQuery('#bulk-selection-count').text(selectedHOFSet.size);
  }

  function resetFilters() {
    jQuery('#filter-sector').val('');
    jQuery('#filter-sub-sector').val('');
    jQuery('#filter-status').val('all');
    jQuery('#search-q').val('');
    selectedHOFSet.clear();
    applyClientFiltering();
  }

  function performSingleAction(itsVal, actionCode) {
    let size = '';
    if (actionCode === 1) {
      size = prompt('Please enter Thali Size (Medium / Big / Double Big / Triple Big / Double Medium / China / Double China / Small / Large):', 'Medium');
      if (size === null) return;
      size = size.trim();
      var inputLower = size.toLowerCase();
      var sizeMap = {
        'medium': 'Medium',
        'big': 'Big',
        'double big': 'Double Big',
        'triple big': 'Triple Big',
        'double medium': 'Double Medium',
        'china': 'China',
        'double china': 'Double China',
        'small': 'Small',
        'large': 'Large'
      };
      if (!sizeMap[inputLower]) {
        alert('Invalid thali size. Action aborted.');
        return;
      }
      size = sizeMap[inputLower];
    }
    
    jQuery.ajax({
      url: '<?php echo base_url("common/save_bulk_signup_ajax"); ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        its_list: [itsVal],
        dates: [selectedDate],
        action: actionCode,
        thali_size: size
      },
      success: function(res) {
        if (res && res.success) {
          loadDashboardDateData(selectedDate);
        } else {
          alert((res && res.message) ? res.message : 'Action failed.');
        }
      },
      error: function() {
        alert('Server error. Please try again.');
      }
    });
  }

  function performBulkAction() {
    const selectedHOFs = Array.from(selectedHOFSet);

    if (selectedHOFs.length === 0) {
      alert('Please select at least one family.');
      return;
    }

    const actionVal = jQuery('#bulk-action').val();
    const sizeVal = jQuery('#bulk-thali-size').val();
    const scopeVal = jQuery('#bulk-scope').val();

    let targetDates = [];
    if (scopeVal === 'month') {
      jQuery('.timeline-item').each(function() {
        var d = jQuery(this).attr('data-date');
        if (d) {
          targetDates.push(d);
        }
      });
      if (targetDates.length === 0) {
        alert('No active menu dates found in the selected month.');
        return;
      }
    } else {
      targetDates = [selectedDate];
    }

    const scopeLabel = scopeVal === 'month' ? 'the entire Hijri month' : 'the selected date';
    if (!confirm('Perform bulk action for ' + selectedHOFs.length + ' families on ' + scopeLabel + '?')) return;

    // Show progress modal
    jQuery('#bulkProgressModal').modal('show');
    updateProgress(0, selectedHOFs.length, 'Initializing...');

    // Chunk parameters
    const chunkSize = 10;
    let index = 0;
    let totalSaved = 0;

    function processNextChunk() {
      if (index >= selectedHOFs.length) {
        // All done!
        setTimeout(function() {
          jQuery('#bulkProgressModal').modal('hide');
          alert('Bulk action completed successfully. Processed ' + totalSaved + ' records.');
          selectedHOFSet.clear();
          loadDashboardDateData(selectedDate);
        }, 500);
        return;
      }

      const chunk = selectedHOFs.slice(index, index + chunkSize);
      const progressText = 'Processing families ' + (index + 1) + ' to ' + Math.min(index + chunkSize, selectedHOFs.length) + ' of ' + selectedHOFs.length + '...';
      const pct = Math.round((index / selectedHOFs.length) * 100);
      updateProgress(pct, selectedHOFs.length, progressText);

      jQuery.ajax({
        url: '<?php echo base_url("common/save_bulk_signup_ajax"); ?>',
        type: 'POST',
        dataType: 'json',
        data: {
          its_list: chunk,
          dates: targetDates,
          action: actionVal,
          thali_size: sizeVal
        },
        success: function(res) {
          if (res && res.success) {
            totalSaved += (res.message ? parseInt(res.message.replace(/[^0-9]/g, '')) || chunk.length : chunk.length);
            index += chunkSize;
            processNextChunk();
          } else {
            jQuery('#bulkProgressModal').modal('hide');
            alert((res && res.message) ? res.message : 'Bulk action failed during execution.');
          }
        },
        error: function() {
          jQuery('#bulkProgressModal').modal('hide');
          alert('Server error occurred during bulk processing. Some records may have been saved.');
        }
      });
    }

    // Start processing first chunk
    processNextChunk();
  }

  function updateProgress(percent, total, text) {
    jQuery('#bulk-progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
    jQuery('#bulk-progress-percent').text(percent + '%');
    jQuery('#bulk-progress-text').text(text);
  }
</script>
