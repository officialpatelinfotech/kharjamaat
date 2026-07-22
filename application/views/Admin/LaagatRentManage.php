<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #10b981;
    --green-bg:    #ecfdf5;
    --green-border:#bbf7d0;
    --red:         #ef4444;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #3b82f6;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #f59e0b;
    --amber-bg:    #fffbeb;
    --amber-border:#fde68a;
    --radius-sm:   8px;
    --radius:      12px;
    --radius-lg:   16px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.05);
    --shadow:      0 4px 16px rgba(184,134,11,0.06);
    --shadow-lg:   0 10px 30px rgba(184,134,11,0.12);
  }

  .page-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    color: var(--text-1);
  }

  /* Page Header / Title */
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
  }
  .btn-back-elegant {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    box-shadow: var(--shadow-sm) !important;
    transition: all .2s !important;
    text-decoration: none !important;
    padding: 0 !important;
  }
  .btn-back-elegant:hover {
    background: var(--gold-muted) !important;
    border-color: var(--gold) !important;
    color: var(--gold-deep) !important;
    transform: translateX(-3px) !important;
  }
  .page-title {
    font-family: 'Literata', Georgia, serif !important;
    color: var(--gold-deep) !important;
    font-size: 1.8rem !important;
    font-weight: 600 !important;
    margin: 0 !important;
    letter-spacing: -.5px !important;
  }
  .btn-create-new {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep)) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 700 !important;
    padding: 10px 20px !important;
    border-radius: var(--radius-sm) !important;
    box-shadow: 0 2px 8px rgba(184,134,11,0.2) !important;
    transition: all .2s !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px;
    font-size: 0.85rem !important;
    height: 42px !important;
  }
  .btn-create-new:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(184,134,11,0.3) !important;
    color: #fff !important;
    text-decoration: none !important;
  }

  /* Card wrapper */
  .records-card {
    background: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow) !important;
    margin-bottom: 30px !important;
    overflow: hidden;
  }
  .records-card .card-body {
    padding: 24px !important;
  }
  .records-card h5 {
    font-family: 'Literata', Georgia, serif !important;
    font-size: 1.25rem !important;
    color: var(--gold-deep) !important;
    font-weight: 600 !important;
    margin-bottom: 20px !important;
  }

  /* Filters */
  .filters-container {
    background: var(--surface-2) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    padding: 20px !important;
    margin-bottom: 24px !important;
  }
  .filters-container label {
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    letter-spacing: .5px !important;
    text-transform: uppercase !important;
    color: var(--text-2) !important;
    margin-bottom: 6px !important;
  }
  .filters-container .form-control, .filters-container .custom-select {
    height: 42px !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    font-size: 0.88rem !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 600 !important;
    color: var(--text-2) !important;
  }
  .filters-container .form-control:focus, .filters-container .custom-select:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12) !important;
    outline: none !important;
  }
  .filters-container select.custom-select {
    padding-right: 32px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    background-position: right 14px center !important;
    background-repeat: no-repeat !important;
    appearance: none !important;
    -webkit-appearance: none !important;
  }
  .btn-apply-filters {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep)) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 700 !important;
    height: 42px !important;
    border-radius: var(--radius-sm) !important;
    transition: all .2s !important;
    padding: 0 20px !important;
    font-size: 0.85rem !important;
  }
  .btn-apply-filters:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06) !important;
    color: #fff !important;
  }
  .btn-reset-filters {
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    font-weight: 700 !important;
    height: 42px !important;
    border-radius: var(--radius-sm) !important;
    transition: all .2s !important;
    padding: 0 20px !important;
    font-size: 0.85rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
  .btn-reset-filters:hover {
    background: var(--surface-2) !important;
    color: var(--text-1) !important;
    text-decoration: none !important;
  }

  /* Table styling */
  .lr-table-scroll {
    max-height: 520px;
    overflow-y: auto;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
  }
  .premium-table {
    margin-bottom: 0 !important;
  }
  .premium-table thead th {
    background: var(--surface-2) !important;
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2) !important;
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 14px 18px !important;
    vertical-align: middle !important;
  }
  .premium-table tbody td {
    padding: 14px 18px !important;
    border-bottom: 1px solid var(--border) !important;
    color: var(--text-2) !important;
    font-size: 0.85rem !important;
    vertical-align: middle !important;
  }
  .premium-table tbody tr:last-child td {
    border-bottom: none !important;
  }
  .premium-table tbody tr:hover {
    background: #fdfbf5 !important;
  }
  #lr_manage_table thead th[data-sort]:not([data-sort="none"]) {
    cursor: pointer;
    user-select: none;
  }

  /* Action buttons */
  .btn-action-edit-lr {
    padding: 6px 12px !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    border-radius: 6px !important;
    border: 1.5px solid var(--blue-border) !important;
    background: var(--blue-bg) !important;
    color: var(--blue) !important;
    transition: all .2s !important;
    text-decoration: none !important;
    display: inline-block !important;
  }
  .btn-action-edit-lr:hover {
    background: var(--blue) !important;
    color: #fff !important;
    border-color: var(--blue) !important;
  }
  .btn-action-view-lr {
    padding: 6px 12px !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    border-radius: 6px !important;
    border: 1.5px solid var(--amber-border) !important;
    background: var(--amber-bg) !important;
    color: var(--amber) !important;
    transition: all .2s !important;
    display: inline-block !important;
  }
  .btn-action-view-lr:hover {
    background: var(--amber) !important;
    color: #fff !important;
    border-color: var(--amber) !important;
  }
  .btn-action-toggle-lr {
    padding: 6px 12px !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    border-radius: 6px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    transition: all .2s !important;
    display: inline-block !important;
  }
  .btn-action-toggle-lr:hover {
    background: var(--surface-2) !important;
    color: var(--text-1) !important;
  }
  .btn-action-delete-lr {
    padding: 6px 12px !important;
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    border-radius: 6px !important;
    border: 1.5px solid var(--red-border) !important;
    background: var(--red-bg) !important;
    color: var(--red) !important;
    transition: all .2s !important;
    display: inline-block !important;
  }
  .btn-action-delete-lr:hover {
    background: var(--red) !important;
    color: #fff !important;
    border-color: var(--red) !important;
  }

  /* Status Badges */
  .badge-success {
    background-color: var(--green-bg) !important;
    color: var(--green) !important;
    border: 1px solid var(--green-border) !important;
    padding: 4px 8px !important;
    font-size: 0.72rem !important;
    border-radius: 4px !important;
  }
  .badge-secondary {
    background-color: #f1f5f9 !important;
    color: #64748b !important;
    border: 1px solid #cbd5e1 !important;
    padding: 4px 8px !important;
    font-size: 0.72rem !important;
    border-radius: 4px !important;
  }

  /* Modal Styling */
  .modal-content {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important;
    overflow: hidden;
  }
  .modal-header {
    background: var(--surface-2) !important;
    border-bottom: 1px solid var(--border) !important;
    padding: 18px 24px !important;
  }
  .modal-title {
    font-family: 'Literata', Georgia, serif !important;
    color: var(--gold-deep) !important;
    font-size: 1.25rem !important;
    font-weight: 600 !important;
    margin: 0 !important;
  }
  .modal-header .close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    cursor: pointer;
    color: var(--text-2);
    transition: all .15s;
    padding: 0;
    opacity: 1;
    border: none;
  }
  .modal-header .close:hover {
    background: var(--red-bg);
    border-color: var(--red-border);
    color: var(--red);
  }
  .modal-body {
    padding: 24px !important;
  }
  .modal-footer {
    background: var(--surface-2) !important;
    border-top: 1px solid var(--border) !important;
    padding: 14px 24px !important;
  }
  .modal .table th {
    background: var(--surface-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-2);
    border: none;
  }
  .modal .table td {
    font-size: 0.88rem;
    color: var(--text-2);
    border-top: 1px solid var(--border);
  }
</style>

<div class="container margintopcontainer pt-5 pb-5 page-wrap">
  <?php
    $is_laagat = isset($module_type) && $module_type === 'laagat';
    $back_url = $is_laagat ? base_url('admin/laagat') : base_url('admin/rent');
    $create_url = $is_laagat ? base_url('admin/laagat/create') : base_url('admin/rent/create');
    $manage_url = $is_laagat ? site_url('admin/laagat/manage') : site_url('admin/rent/manage');
    $title = $is_laagat ? 'Manage Laagat' : 'Manage Rent';
  ?>

  <!-- Header -->
  <div class="page-header">
    <a href="<?php echo $back_url; ?>" class="btn-back-elegant" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $title; ?></h1>
    <a href="<?php echo $create_url; ?>" class="btn-create-new" aria-label="Create New">
      <i class="fa-solid fa-plus"></i> Create New
    </a>
  </div>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($flash_success); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($flash_error); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>

  <div class="card records-card">
    <div class="card-body">
      <h5>Records</h5>

      <form method="get" action="<?php echo $manage_url; ?>" class="mb-4">
        <?php
          $filters = isset($filters) && is_array($filters) ? $filters : [];
          $fTitle = isset($filters['title']) ? (string)$filters['title'] : '';
          $fYear = isset($filters['hijri_year']) ? (string)$filters['hijri_year'] : '';
          $fCharge = isset($filters['charge_type']) ? (string)$filters['charge_type'] : '';
          $fRaza = isset($filters['raza_category']) ? (string)$filters['raza_category'] : '';
        ?>

        <div class="filters-container">
          <div class="form-row">
            <div class="form-group col-12 <?php echo isset($module_type) ? 'col-md-6' : 'col-md-4'; ?>">
              <label for="flt_title">Title</label>
              <input type="text" class="form-control" id="flt_title" name="title" value="<?php echo htmlspecialchars($fTitle); ?>" placeholder="Enter Title" />
            </div>

            <div class="form-group col-12 <?php echo isset($module_type) ? 'col-md-6' : 'col-md-4'; ?>">
              <label for="flt_year">Hijri Year</label>
              <select class="custom-select" id="flt_year" name="hijri_year">
                <option value="">All</option>
                <?php if (!empty($hijri_year_options) && is_array($hijri_year_options)) : ?>
                  <?php foreach ($hijri_year_options as $yr) : ?>
                    <?php $selected = ($fYear !== '' && (string)$fYear === (string)$yr) ? 'selected' : ''; ?>
                    <option value="<?php echo htmlspecialchars((string)$yr); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars((string)$yr); ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <?php if (!isset($module_type)) : ?>
            <div class="form-group col-12 col-md-4">
              <label for="flt_charge">Charge Type</label>
              <select class="custom-select" id="flt_charge" name="charge_type">
                <option value="">All</option>
                <option value="laagat" <?php echo ($fCharge === 'laagat') ? 'selected' : ''; ?>>Laagat</option>
                <option value="rent" <?php echo ($fCharge === 'rent') ? 'selected' : ''; ?>>Rent</option>
              </select>
            </div>
            <?php endif; ?>
          </div>

          <div class="form-row align-items-end">
            <div class="form-group col-12 col-md-8 mb-0">
              <label for="flt_raza">Applicable Raza Categories</label>
              <input type="text" class="form-control" id="flt_raza" name="raza_category" value="<?php echo htmlspecialchars($fRaza); ?>" placeholder="Enter Applicable Raza Category" />
            </div>

            <div class="form-group col-12 col-md-4 mb-0 d-flex justify-content-md-end mt-3 mt-md-0" style="gap: 8px;">
              <a href="<?php echo $manage_url; ?>" class="btn btn-reset-filters">Reset</a>
              <button type="submit" class="btn btn-apply-filters">Apply Filters</button>
            </div>
          </div>
        </div>
      </form>

      <div class="table-responsive lr-table-scroll">
        <table class="table premium-table table-hover align-middle" id="lr_manage_table">
          <thead>
            <tr>
              <th style="width: 80px;" data-sort="number">Sr.No</th>
              <th data-sort="string">Title</th>
              <th data-sort="string">Hijri Year</th>
              <?php if (!isset($module_type)) : ?>
                <th data-sort="string">Charge Type</th>
              <?php endif; ?>
              <?php if (!$is_laagat) : ?>
                <th data-sort="string">Venue</th>
              <?php endif; ?>
              <th data-sort="string">Applicable Raza Categories</th>
              <th data-sort="string">Status</th>
              <th style="width: 280px;" data-sort="none">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rows) && is_array($rows)) : ?>
              <?php $srno = 1; ?>
              <?php foreach ($rows as $r) : ?>
                <tr>
                  <td><?php echo (int)$srno++; ?></td>
                  <td class="font-weight-bold text-dark">
                    <?php echo htmlspecialchars((string)($r['title'] ?? '')); ?>
                  </td>
                  <td><?php echo htmlspecialchars((string)($r['hijri_year'] ?? '')); ?></td>
                  <?php if (!isset($module_type)) : ?>
                    <td><?php echo htmlspecialchars(ucfirst(strtolower((string)($r['charge_type'] ?? '')))); ?></td>
                  <?php endif; ?>
                  <?php if (!$is_laagat) : ?>
                    <td><?php echo (isset($r['venue']) && $r['venue'] !== '') ? htmlspecialchars((string)$r['venue']) : 'All'; ?></td>
                  <?php endif; ?>
                  <td><?php echo htmlspecialchars((string)($r['raza_type_name'] ?? '')); ?></td>
                  <td>
                    <?php if (!empty($r['is_active'])) : ?>
                      <span class="badge badge-success">Active</span>
                    <?php else : ?>
                      <span class="badge badge-secondary">Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                      <a class="btn-action-edit-lr btn-sm" href="<?php echo site_url('admin/' . strtolower($r['charge_type']) . '/create') . '?edit=' . (int)$r['id']; ?>">Edit</a>
                      <button type="button" class="btn-action-view-lr btn-sm view-grades-btn" data-id="<?php echo (int)$r['id']; ?>" data-year="<?php echo htmlspecialchars((string)($r['hijri_year'] ?? '')); ?>" data-title="<?php echo htmlspecialchars((string)($r['title'] ?? '')); ?>">View</button>
   
                      <form method="post" action="<?php echo site_url('admin/laagat_toggle'); ?>" class="m-0" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
                        <?php $isActive = !empty($r['is_active']); ?>
                        <button type="submit" class="btn-action-toggle-lr btn-sm">
                          <?php echo $isActive ? 'Deactivate' : 'Activate'; ?>
                        </button>
                      </form>
   
                      <?php $hasInvoices = !empty($r['has_invoices']); ?>
                      <form method="post" action="<?php echo site_url('admin/laagat_delete'); ?>" class="m-0" style="display:inline-block;" 
                            onsubmit="<?php echo $hasInvoices ? "alert('This " . ucfirst(strtolower($r['charge_type'])) . " cannot be deleted because invoices exist for this record.'); return false;" : "return confirm('Delete this record?');"; ?>">
                        <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
                        <button type="submit" class="btn-action-delete-lr btn-sm">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>

              <?php endforeach; ?>
            <?php else : ?>
              <?php
                $colspan = 8;
                if (isset($module_type)) {
                  $colspan = $is_laagat ? 6 : 7;
                }
              ?>
              <tr><td colspan="<?php echo $colspan; ?>" class="text-center text-muted py-5">No records found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    var table = document.getElementById('lr_manage_table');
    if (!table) return;

    var thead = table.querySelector('thead');
    var tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    var sortState = { index: -1, dir: 'asc' };

    function getCellText(row, idx) {
      var cell = row.cells && row.cells[idx];
      if (!cell) return '';
      return (cell.textContent || '').trim();
    }

    function toNumber(text) {
      if (!text || text === '-') return NaN;
      var cleaned = text.replace(/[^0-9.\-]/g, '');
      var num = parseFloat(cleaned);
      return isNaN(num) ? NaN : num;
    }

    function compare(a, b, type) {
      if (type === 'number') {
        var na = toNumber(a);
        var nb = toNumber(b);
        if (isNaN(na) && isNaN(nb)) return 0;
        if (isNaN(na)) return 1;
        if (isNaN(nb)) return -1;
        return na < nb ? -1 : (na > nb ? 1 : 0);
      }
      a = (a || '').toLowerCase();
      b = (b || '').toLowerCase();
      if (a < b) return -1;
      if (a > b) return 1;
      return 0;
    }

    function renumberSrNo() {
      var rows = tbody.querySelectorAll('tr');
      var sr = 1;
      for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].cells;
        if (!cells || !cells.length) continue;
        if (cells.length === 1) continue;
        cells[0].textContent = String(sr++);
      }
    }

    thead.addEventListener('click', function(e) {
      var th = e.target && e.target.closest ? e.target.closest('th') : null;
      if (!th || !thead.contains(th)) return;

      var type = th.getAttribute('data-sort') || 'string';
      if (type === 'none') return;

      var headers = thead.querySelectorAll('th');
      var idx = Array.prototype.indexOf.call(headers, th);
      if (idx < 0) return;

      var dir = 'asc';
      if (sortState.index === idx) {
        dir = sortState.dir === 'asc' ? 'desc' : 'asc';
      }
      sortState.index = idx;
      sortState.dir = dir;

      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      if (rows.length === 1 && rows[0].querySelector('td[colspan]')) return;

      rows.sort(function(r1, r2) {
        var t1 = getCellText(r1, idx);
        var t2 = getCellText(r2, idx);
        var c = compare(t1, t2, type);
        return dir === 'asc' ? c : -c;
      });

      for (var i = 0; i < rows.length; i++) {
        tbody.appendChild(rows[i]);
      }
      renumberSrNo();
    });
  })();


</script>

<!-- View Grades Modal -->
<div class="modal fade" id="lrViewGradesModal" tabindex="-1" role="dialog" aria-labelledby="lrViewGradesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <div>
          <h5 class="modal-title font-weight-bold" id="lrViewGradesModalLabel">Grade Amounts</h5>
          <p class="text-muted mb-0 mt-1 small" id="lrViewGradesModalSubtitle" style="font-size: 0.85rem; font-weight: 500;"></p>
        </div>
        <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div id="lrModalSpinner" class="text-center py-5">
          <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="text-muted mt-3 small" style="font-weight: 500;">Loading configurations...</div>
        </div>
        <div id="lrModalError" class="alert alert-danger m-3" role="alert" style="display: none; border-radius: 8px;">
          Failed to load grade amount assignments. Please try again.
        </div>
        <div id="lrModalContent" style="display: none;">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.8px; font-weight: 700; color: #6c757d;">
                  <th class="pl-4 py-3">Residential Grade</th>
                  <th class="text-right py-3">Jmt. Laagat</th>
                  <th class="text-right py-3">Sar. Laagat</th>
                  <th class="text-right pr-4 py-3">Total Amount</th>
                </tr>
              </thead>
              <tbody id="lrGradesTableBody" style="font-size: 0.95rem;">
                <!-- Dynamically populated -->
              </tbody>
            </table>
          </div>
        </div>
        <div id="lrModalContentFlat" style="display: none;" class="py-4 px-4">
          <div class="row text-center">
            <div class="col-4">
              <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Jmt. Laagat</div>
              <div class="font-weight-bold text-success mt-2" id="lrFlatJmtValue" style="font-size: 1.5rem;">₹0.00</div>
            </div>
            <div class="col-4">
              <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Sar. Laagat</div>
              <div class="font-weight-bold text-info mt-2" id="lrFlatSarValue" style="font-size: 1.5rem;">₹0.00</div>
            </div>
            <div class="col-4">
              <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Total Amount</div>
              <div class="font-weight-bold text-dark mt-2" id="lrFlatAmountValue" style="font-size: 1.5rem;">₹0.00</div>
            </div>
          </div>
        </div>
        <div id="lrModalContentFlatRent" style="display: none;" class="py-4 px-4 text-center">
          <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Amount</div>
          <div class="font-weight-bold text-primary mt-2" id="lrFlatRentAmountValue" style="font-size: 2.2rem;">₹0.00</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px; font-weight: 500; padding: 0.4rem 1.2rem;">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
const JAMAAT_PLACE = <?= json_encode(jamaat_place()) ?>;
document.addEventListener('DOMContentLoaded', function() {
  var modalEl = document.getElementById('lrViewGradesModal');
  if (!modalEl) return;

  var subtitleEl = document.getElementById('lrViewGradesModalSubtitle');
  var spinnerEl = document.getElementById('lrModalSpinner');
  var errorEl = document.getElementById('lrModalError');
  var contentEl = document.getElementById('lrModalContent');
  var tbodyEl = document.getElementById('lrGradesTableBody');

  function showModal() {
    if (window.jQuery && typeof jQuery(modalEl).modal === 'function') {
      jQuery(modalEl).modal('show');
    } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
      new bootstrap.Modal(modalEl).show();
    } else {
      modalEl.style.display = 'block';
      modalEl.classList.add('show');
      document.body.classList.add('modal-open');
      
      var backdrop = document.createElement('div');
      backdrop.className = 'modal-backdrop fade show';
      backdrop.id = 'lr-modal-backdrop';
      backdrop.addEventListener('click', hideModal);
      document.body.appendChild(backdrop);
    }
  }

  function hideModal() {
    if (window.jQuery && typeof jQuery(modalEl).modal === 'function') {
      jQuery(modalEl).modal('hide');
    } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
      var inst = bootstrap.Modal.getInstance(modalEl);
      if (inst) inst.hide();
    } else {
      modalEl.style.display = 'none';
      modalEl.classList.remove('show');
      document.body.classList.remove('modal-open');
      var backdrop = document.getElementById('lr-modal-backdrop');
      if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
      }
    }
  }

  modalEl.querySelectorAll('[data-dismiss="modal"]').forEach(function(btn) {
    btn.addEventListener('click', hideModal);
  });

  document.addEventListener('click', function(e) {
    var btn = e.target && e.target.closest && e.target.closest('.view-grades-btn');
    if (!btn) return;

    var id = btn.getAttribute('data-id');
    var year = btn.getAttribute('data-year');
    var title = btn.getAttribute('data-title');

    subtitleEl.textContent = title + ' (' + year + ')';

    var dialogEl = modalEl.querySelector('.modal-dialog');
    if (dialogEl) {
      dialogEl.classList.remove('modal-lg');
    }

    spinnerEl.style.display = 'block';
    errorEl.style.display = 'none';
    contentEl.style.display = 'none';
    document.getElementById('lrModalContentFlat').style.display = 'none';
    document.getElementById('lrModalContentFlatRent').style.display = 'none';
    tbodyEl.innerHTML = '';
    // Remove any previously appended items section
    var prevItemsSection = document.getElementById('lrModalItemsSection');
    if (prevItemsSection && prevItemsSection.parentNode) {
      prevItemsSection.parentNode.removeChild(prevItemsSection);
    }

    showModal();

    var url = '<?php echo site_url("admin/laagat_get_grade_amounts"); ?>?year=' + encodeURIComponent(year) + '&laagat_rent_id=' + encodeURIComponent(id);
    
    var handleResponse = function(data) {
      if (data && data.success && Array.isArray(data.grades)) {
        spinnerEl.style.display = 'none';
        var masterAmount = (data.master_amount !== null && data.master_amount !== undefined) ? parseFloat(data.master_amount) : 0;
        
        if (data.charge_type === 'rent') {
          document.getElementById('lrViewGradesModalLabel').textContent = 'Rent Details';
          var flatRentEl = document.getElementById('lrModalContentFlatRent');
          
          if (data.is_per_thaal === 1) {
            if (dialogEl) {
              dialogEl.classList.add('modal-lg');
            }
            var html = '<div class="px-4 pb-4">';
            html += '<div class="text-uppercase text-secondary font-weight-bold mb-3 small" style="letter-spacing: 0.5px;">Thaal Ranges & Rates</div>';
            html += '<div class="table-responsive">';
            html += '<table class="table table-bordered table-sm text-center mb-0" style="font-size: 0.85rem;">';
            html += '<thead class="bg-light"><tr>';
            html += '<th>Thaal Range</th>';
            html += '<th>' + JAMAAT_PLACE + ' Rent</th>';
            html += '<th>' + JAMAAT_PLACE + ' Deposit</th>';
            html += '<th>Non-' + JAMAAT_PLACE + ' Rent</th>';
            html += '<th>Non-' + JAMAAT_PLACE + ' Deposit</th>';
            html += '</tr></thead><tbody>';
            
            if (Array.isArray(data.thaal_ranges) && data.thaal_ranges.length > 0) {
              data.thaal_ranges.forEach(function(r) {
                var rMin = parseInt(r.thaal_min, 10);
                var rMax = parseInt(r.thaal_max, 10);
                var rRentS = parseFloat(r.rent_sabeel || 0);
                var rDepS = parseFloat(r.deposit_sabeel || 0);
                var rRentNS = parseFloat(r.rent_non_sabeel || 0);
                var rDepNS = parseFloat(r.deposit_non_sabeel || 0);
                
                html += '<tr>';
                html += '<td><strong>' + rMin + ' to ' + rMax + '</strong></td>';
                html += '<td>₹' + rRentS.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '<td>₹' + rDepS.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '<td>₹' + rRentNS.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '<td>₹' + rDepNS.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '</tr>';
              });
            } else {
              html += '<tr><td colspan="5" class="text-muted">No ranges configured.</td></tr>';
            }
            
            html += '</tbody></table></div></div>';
            flatRentEl.innerHTML = html;
          } else {
            if (dialogEl) {
              dialogEl.classList.remove('modal-lg');
            }
            var rentSabeel = (data.rent_sabeel !== null && data.rent_sabeel !== undefined) ? parseFloat(data.rent_sabeel) : 0;
            var rentNonSabeel = (data.rent_non_sabeel !== null && data.rent_non_sabeel !== undefined) ? parseFloat(data.rent_non_sabeel) : 0;
            var depositSabeel = (data.deposit_sabeel !== null && data.deposit_sabeel !== undefined) ? parseFloat(data.deposit_sabeel) : 0;
            var depositNonSabeel = (data.deposit_non_sabeel !== null && data.deposit_non_sabeel !== undefined) ? parseFloat(data.deposit_non_sabeel) : 0;

            var html = '<div class="row text-center mb-3">' +
                         '<div class="col-6 border-right">' +
                           '<div class="text-primary font-weight-bold mb-2" style="font-size: 1.1rem;">' + JAMAAT_PLACE + ' Sabeel Holders</div>' +
                           '<div class="mb-1"><span class="text-muted small">Rent:</span> <span class="font-weight-bold text-dark">₹' + rentSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                           '<div><span class="text-muted small">Deposit:</span> <span class="font-weight-bold text-dark">₹' + depositSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                         '</div>' +
                         '<div class="col-6">' +
                           '<div class="text-secondary font-weight-bold mb-2" style="font-size: 1.1rem;">Non ' + JAMAAT_PLACE + ' Sabeel Holders</div>' +
                           '<div class="mb-1"><span class="text-muted small">Rent:</span> <span class="font-weight-bold text-dark">₹' + rentNonSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                           '<div><span class="text-muted small">Deposit:</span> <span class="font-weight-bold text-dark">₹' + depositNonSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                         '</div>' +
                       '</div>';
            flatRentEl.innerHTML = html;
          }

          // --- Render Items table (always for rent type) ---
          if (Array.isArray(data.items) && data.items.length > 0) {
            if (dialogEl) dialogEl.classList.add('modal-lg');
            var itemsHtml = '<div class="px-4 pb-4">';
            itemsHtml += '<div class="text-uppercase text-secondary font-weight-bold mb-3 small" style="letter-spacing: 0.5px;">Rent Items</div>';
            itemsHtml += '<div class="table-responsive">';
            itemsHtml += '<table class="table table-bordered table-sm mb-0" style="font-size: 0.85rem;">';
            itemsHtml += '<thead class="bg-light"><tr>';
            itemsHtml += '<th class="py-2 pl-3">#</th>';
            itemsHtml += '<th class="py-2">Item Name</th>';
            itemsHtml += '<th class="py-2">Service Provided By</th>';
            itemsHtml += '<th class="py-2 text-right pr-3">Cost / Piece</th>';
            itemsHtml += '</tr></thead><tbody>';
            data.items.forEach(function(item, idx) {
              var costPerPiece = parseFloat(item.rent_sabeel || 0);
              var serviceProvidedBy = item.service_provided_by || 'Jamaat';
              itemsHtml += '<tr>';
              itemsHtml += '<td class="pl-3 text-muted align-middle">' + (idx + 1) + '</td>';
              itemsHtml += '<td class="font-weight-bold align-middle">' + (item.item_name || '') + '</td>';
              itemsHtml += '<td class="align-middle">' + serviceProvidedBy + '</td>';
              itemsHtml += '<td class="text-right pr-3 font-weight-bold text-success align-middle">₹' + costPerPiece.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
              itemsHtml += '</tr>';
            });
            itemsHtml += '</tbody></table></div></div>';

            // Append items table below existing flatRentEl content
            var itemsSection = document.createElement('div');
            itemsSection.id = 'lrModalItemsSection';
            itemsSection.innerHTML = itemsHtml;
            var flatRentParent = flatRentEl.parentNode;
            if (flatRentParent) {
              flatRentParent.appendChild(itemsSection);
            }
          }

          flatRentEl.style.display = 'block';
        } else {
          document.getElementById('lrViewGradesModalLabel').textContent = 'Grade Amounts';
          
          var hasSavedGrades = data.grades && data.grades.length > 0 && data.grades.some(function(g) {
            return g.saved_amount !== null && g.saved_amount !== undefined;
          });
          
          if (hasSavedGrades) {
            data.grades.forEach(function(g) {
              var tr = document.createElement('tr');
              
              var tdGrade = document.createElement('td');
              tdGrade.className = 'pl-4 align-middle font-weight-bold text-secondary';
              tdGrade.textContent = 'Grade ' + g.grade;
              tr.appendChild(tdGrade);

              var savedJ = (g.saved_jamaat_amount !== null && g.saved_jamaat_amount !== undefined) ? parseFloat(g.saved_jamaat_amount) : null;
              var savedS = (g.saved_sarkaar_amount !== null && g.saved_sarkaar_amount !== undefined) ? parseFloat(g.saved_sarkaar_amount) : null;
              var amountVal = (g.saved_amount !== null && g.saved_amount !== undefined) ? parseFloat(g.saved_amount) : 0;

              if ((savedJ === null || savedJ === 0) && (savedS === null || savedS === 0) && amountVal > 0) {
                savedJ = amountVal;
                savedS = 0;
              } else {
                if (savedJ === null) savedJ = 0;
                if (savedS === null) savedS = 0;
              }

              var tdJmt = document.createElement('td');
              tdJmt.className = 'text-right font-weight-bold text-success align-middle';
              tdJmt.textContent = '₹' + savedJ.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              tr.appendChild(tdJmt);

              var tdSar = document.createElement('td');
              tdSar.className = 'text-right font-weight-bold text-info align-middle';
              tdSar.textContent = '₹' + savedS.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              tr.appendChild(tdSar);

              var tdAmount = document.createElement('td');
              tdAmount.className = 'text-right pr-4 font-weight-bold text-dark align-middle';
              tdAmount.textContent = '₹' + amountVal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              tr.appendChild(tdAmount);

              tbodyEl.appendChild(tr);
            });
            contentEl.style.display = 'block';
          } else {
            var flatEl = document.getElementById('lrModalContentFlat');
            var flatJmtEl = document.getElementById('lrFlatJmtValue');
            var flatSarEl = document.getElementById('lrFlatSarValue');
            var flatValEl = document.getElementById('lrFlatAmountValue');
            flatJmtEl.textContent = '₹' + masterAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            flatSarEl.textContent = '₹' + (0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            flatValEl.textContent = '₹' + masterAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            flatEl.style.display = 'block';
          }
        }
      } else {
        showError('Invalid data payload');
      }
    };

    var showError = function(error) {
      console.error('Error fetching grade amounts:', error);
      spinnerEl.style.display = 'none';
      errorEl.style.display = 'block';
    };

    if (window.$ && $.getJSON) {
      $.getJSON(url, handleResponse).fail(showError);
    } else if (window.fetch) {
      window.fetch(url, { credentials: 'same-origin' })
        .then(function(r) {
          if (!r.ok) throw new Error('Network response was not ok');
          return r.json();
        })
        .then(handleResponse)
        .catch(showError);
    }
  });
});
</script>
