<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container margintopcontainer pt-5">
  <div class="row mb-3">
    <div class="col-6">
      <a href="<?php echo base_url('admin/laagat'); ?>" class="btn btn-outline-secondary" aria-label="Back to Laagat/Rent Module"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-6 d-flex justify-content-end text-right text-end">
      <a href="<?php echo base_url('admin/laagat/create'); ?>" class="btn btn-primary" aria-label="Create New Laagat/Rent Form">
        <i class="fa-solid fa-plus"></i> Create New
      </a>
    </div>
  </div>

  <h4 class="heading text-center mb-4">Manage Laagat / Rent</h4>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($flash_success); ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($flash_error); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Records</h5>

      <form method="get" action="<?php echo site_url('admin/laagat/manage'); ?>" class="mb-4">
        <?php
          $filters = isset($filters) && is_array($filters) ? $filters : [];
          $fTitle = isset($filters['title']) ? (string)$filters['title'] : '';
          $fYear = isset($filters['hijri_year']) ? (string)$filters['hijri_year'] : '';
          $fCharge = isset($filters['charge_type']) ? (string)$filters['charge_type'] : '';
          $fRaza = isset($filters['raza_category']) ? (string)$filters['raza_category'] : '';
        ?>

        <div class="border rounded p-4">
          <div class="form-row">
            <div class="form-group col-12 col-md-4">
              <label for="flt_title" class="mb-2">Title</label>
              <input type="text" class="form-control" id="flt_title" name="title" value="<?php echo htmlspecialchars($fTitle); ?>" placeholder="Enter Title" />
            </div>

            <div class="form-group col-12 col-md-4">
              <label for="flt_year" class="mb-2">Hijri Year</label>
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

            <div class="form-group col-12 col-md-4">
              <label for="flt_charge" class="mb-2">Charge Type</label>
              <select class="custom-select" id="flt_charge" name="charge_type">
                <option value="">All</option>
                <option value="laagat" <?php echo ($fCharge === 'laagat') ? 'selected' : ''; ?>>Laagat</option>
                <option value="rent" <?php echo ($fCharge === 'rent') ? 'selected' : ''; ?>>Rent</option>
              </select>
            </div>
          </div>

          <div class="form-row align-items-end">
            <div class="form-group col-12 col-md-8 mb-0">
              <label for="flt_raza" class="mb-2">Applicable Raza Categories</label>
              <input type="text" class="form-control" id="flt_raza" name="raza_category" value="<?php echo htmlspecialchars($fRaza); ?>" placeholder="Enter Applicable Raza Category" />
            </div>

            <div class="form-group col-12 col-md-4 mb-0 d-flex justify-content-md-end mt-3 mt-md-0">
              <a href="<?php echo site_url('admin/laagat/manage'); ?>" class="btn btn-outline-secondary mr-2">Reset</a>
              <button type="submit" class="btn btn-success">Apply Filters</button>
            </div>
          </div>
        </div>
      </form>

      <div class="table-responsive lr-table-scroll">
        <table class="table table-bordered align-middle" id="lr_manage_table">
          <thead>
            <tr>
              <th style="width: 80px;" data-sort="number">Sr.No</th>
              <th data-sort="string">Title</th>
              <th data-sort="string">Hijri Year</th>
              <th data-sort="string">Charge Type</th>
              <th data-sort="number">Amount</th>
              <th data-sort="string">Applicable Raza Categories</th>
              <th data-sort="string">Status</th>
              <th style="width: 220px;" data-sort="none">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rows) && is_array($rows)) : ?>
              <?php $srno = 1; ?>
              <?php foreach ($rows as $r) : ?>
                <tr>
                  <td><?php echo (int)$srno++; ?></td>
                  <td><?php echo htmlspecialchars((string)($r['title'] ?? '')); ?></td>
                  <td><?php echo htmlspecialchars((string)($r['hijri_year'] ?? '')); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst(strtolower((string)($r['charge_type'] ?? '')))); ?></td>
                  <td>
                    <?php
                      $amt = $r['amount'] ?? '';
                      echo ($amt !== '' && $amt !== null) ? '₹' . format_inr((float)$amt, 0) : '-';
                    ?>
                  </td>
                  <td><?php echo htmlspecialchars((string)($r['raza_type_name'] ?? '')); ?></td>
                  <td>
                    <?php if (!empty($r['is_active'])) : ?>
                      <span class="badge badge-success">Active</span>
                    <?php else : ?>
                      <span class="badge badge-secondary">Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('admin/laagat/create') . '?edit=' . (int)$r['id']; ?>">Edit</a>

                    <form method="post" action="<?php echo site_url('admin/laagat_toggle'); ?>" style="display:inline-block;">
                      <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
                      <?php $isActive = !empty($r['is_active']); ?>
                      <button type="submit" class="btn btn-sm <?php echo $isActive ? 'btn-outline-warning' : 'btn-outline-success'; ?>">
                        <?php echo $isActive ? 'Deactivate' : 'Activate'; ?>
                      </button>
                    </form>

                    <?php $hasInvoices = !empty($r['has_invoices']); ?>
                    <form method="post" action="<?php echo site_url('admin/laagat_delete'); ?>" style="display:inline-block;" 
                          onsubmit="<?php echo $hasInvoices ? "alert('This Laagat/Rent Cannot deleted beacuase invoice exist for this record .'); return false;" : "return confirm('Delete this record?');"; ?>">
                      <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>" />
                      <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr><td colspan="8" class="text-center text-muted">No records found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .lr-table-scroll {
    max-height: 520px;
    overflow-y: auto;
  }
  #lr_manage_table thead th[data-sort]:not([data-sort="none"]) {
    cursor: pointer;
    user-select: none;
  }
</style>

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
      // Keep digits, dot, minus
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
      // default string
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
        // Skip the empty-state row (colspan)
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
      // If only empty-state row, do nothing
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
