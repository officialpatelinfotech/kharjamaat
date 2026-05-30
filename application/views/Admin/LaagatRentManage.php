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
              <th data-sort="string">Venue</th>
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
                  <td><?php echo htmlspecialchars((string)($r['title'] ?? '')); ?></td>
                  <td><?php echo htmlspecialchars((string)($r['hijri_year'] ?? '')); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst(strtolower((string)($r['charge_type'] ?? '')))); ?></td>
                  <td><?php echo (isset($r['venue']) && $r['venue'] !== '') ? htmlspecialchars((string)$r['venue']) : '-'; ?></td>
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
                    <button type="button" class="btn btn-sm btn-outline-info view-grades-btn" data-id="<?php echo (int)$r['id']; ?>" data-year="<?php echo htmlspecialchars((string)($r['hijri_year'] ?? '')); ?>" data-title="<?php echo htmlspecialchars((string)($r['title'] ?? '')); ?>">View</button>
 
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

<!-- View Grades Modal -->
<div class="modal fade" id="lrViewGradesModal" tabindex="-1" role="dialog" aria-labelledby="lrViewGradesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header bg-light border-bottom-0 pt-4 px-4 pb-2">
        <div>
          <h5 class="modal-title font-weight-bold text-dark" id="lrViewGradesModalLabel" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.3px;">Grade Amounts</h5>
          <p class="text-muted mb-0 mt-1 small" id="lrViewGradesModalSubtitle" style="font-size: 0.85rem; font-weight: 500;"></p>
        </div>
        <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close" style="outline: none; font-size: 1.5rem; margin-top: -10px; border: none; background: transparent;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div id="lrModalSpinner" class="text-center py-5">
          <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;">
            <span class="sr-only">Loading...</span>
          </div>
          <div class="text-muted mt-3 small" style="font-weight: 500;">Loading grade amount configurations...</div>
        </div>
        <div id="lrModalError" class="alert alert-danger m-3" role="alert" style="display: none; border-radius: 8px;">
          Failed to load grade amount assignments. Please try again.
        </div>
        <div id="lrModalContent" style="display: none;">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr class="bg-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.8px; font-weight: 700; color: #6c757d;">
                  <th class="pl-4 border-top-0 border-bottom-0 py-3">Residential Grade</th>
                  <th class="text-right border-top-0 border-bottom-0 py-3">Jmt. Laagat</th>
                  <th class="text-right border-top-0 border-bottom-0 py-3">Sar. Laagat</th>
                  <th class="text-right pr-4 border-top-0 border-bottom-0 py-3">Total Amount</th>
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
              <div class="font-weight-bold text-success mt-2" id="lrFlatJmtValue" style="font-size: 1.5rem; font-family: 'Outfit', 'Inter', sans-serif;">₹0.00</div>
            </div>
            <div class="col-4">
              <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Sar. Laagat</div>
              <div class="font-weight-bold text-info mt-2" id="lrFlatSarValue" style="font-size: 1.5rem; font-family: 'Outfit', 'Inter', sans-serif;">₹0.00</div>
            </div>
            <div class="col-4">
              <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Total Amount</div>
              <div class="font-weight-bold text-dark mt-2" id="lrFlatAmountValue" style="font-size: 1.5rem; font-family: 'Outfit', 'Inter', sans-serif;">₹0.00</div>
            </div>
          </div>
        </div>
        <div id="lrModalContentFlatRent" style="display: none;" class="py-4 px-4 text-center">
          <div class="text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1.2px;">Amount</div>
          <div class="font-weight-bold text-primary mt-2" id="lrFlatRentAmountValue" style="font-size: 2.2rem; font-family: 'Outfit', 'Inter', sans-serif;">₹0.00</div>
        </div>
      </div>
      <div class="modal-footer border-top-0 pb-4 px-4 pt-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" style="border-radius: 6px; font-weight: 500; padding: 0.4rem 1.2rem;">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
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
      
      // Backdrop element
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

  // Bind close buttons for fallback
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

    // Reset modal state
    spinnerEl.style.display = 'block';
    errorEl.style.display = 'none';
    contentEl.style.display = 'none';
    document.getElementById('lrModalContentFlat').style.display = 'none';
    document.getElementById('lrModalContentFlatRent').style.display = 'none';
    tbodyEl.innerHTML = '';

    showModal();

    // Fetch grades
    var url = '<?php echo site_url("admin/laagat_get_grade_amounts"); ?>?year=' + encodeURIComponent(year) + '&laagat_rent_id=' + encodeURIComponent(id);
    
    var handleResponse = function(data) {
      if (data && data.success && Array.isArray(data.grades)) {
        spinnerEl.style.display = 'none';
        var masterAmount = (data.master_amount !== null && data.master_amount !== undefined) ? parseFloat(data.master_amount) : 0;
        
        if (data.charge_type === 'rent') {
          // Change modal title dynamically
          document.getElementById('lrViewGradesModalLabel').textContent = 'Rent Details';
          // Show Rent/Deposit Details
          var flatRentEl = document.getElementById('lrModalContentFlatRent');
          var rentSabeel = (data.rent_sabeel !== null && data.rent_sabeel !== undefined) ? parseFloat(data.rent_sabeel) : 0;
          var rentNonSabeel = (data.rent_non_sabeel !== null && data.rent_non_sabeel !== undefined) ? parseFloat(data.rent_non_sabeel) : 0;
          var depositSabeel = (data.deposit_sabeel !== null && data.deposit_sabeel !== undefined) ? parseFloat(data.deposit_sabeel) : 0;
          var depositNonSabeel = (data.deposit_non_sabeel !== null && data.deposit_non_sabeel !== undefined) ? parseFloat(data.deposit_non_sabeel) : 0;

          var html = '<div class="row text-center mb-3">' +
                       '<div class="col-6 border-right">' +
                         '<div class="text-primary font-weight-bold mb-2" style="font-size: 1.1rem;">Khar Sabeel Holders</div>' +
                         '<div class="mb-1"><span class="text-muted small">Rent:</span> <span class="font-weight-bold text-dark">₹' + rentSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                         '<div><span class="text-muted small">Deposit:</span> <span class="font-weight-bold text-dark">₹' + depositSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                       '</div>' +
                       '<div class="col-6">' +
                         '<div class="text-secondary font-weight-bold mb-2" style="font-size: 1.1rem;">Non Khar Sabeel Holders</div>' +
                         '<div class="mb-1"><span class="text-muted small">Rent:</span> <span class="font-weight-bold text-dark">₹' + rentNonSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                         '<div><span class="text-muted small">Deposit:</span> <span class="font-weight-bold text-dark">₹' + depositNonSabeel.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span></div>' +
                       '</div>' +
                     '</div>';
          flatRentEl.innerHTML = html;
          flatRentEl.style.display = 'block';
        } else {
          // Change modal title dynamically
          document.getElementById('lrViewGradesModalLabel').textContent = 'Grade Amounts';
          
          var hasSavedGrades = data.grades && data.grades.length > 0 && data.grades.some(function(g) {
            return g.saved_amount !== null && g.saved_amount !== undefined;
          });
          
          if (hasSavedGrades) {
            // Show Grade Table
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
            // Fallback: Show Flat Amount
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

