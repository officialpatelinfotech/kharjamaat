<div class="container margintopcontainer">
  <style>
    /* Make the wajebaat table vertically scrollable and keep header fixed */
    .wajebaat-table-container {
      max-height: 420px;
      overflow: auto;
    }
    .wajebaat-table thead th {
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 3;
      box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
    }
    .wajebaat-table thead th[data-sort]:not([data-sort="none"]) {
      cursor: pointer;
      user-select: none;
    }
    .wajebaat-table thead th .sort-indicator {
      font-size: 0.85em;
    }
  </style>
  <div class="row align-items-center pt-3 mb-2">
    <div class="col-3 text-left">
      <a href="<?php echo base_url('admin'); ?>" class="btn btn-sm btn-outline-secondary" title="Back" aria-label="Back"><i class="fa fa-arrow-left"></i></a>
    </div>
    <div class="col-6 text-center">
      <h2 class="m-0">Wajebaat</h2>
    </div>
    <div class="col-3 text-right">
      <a href="<?php echo base_url('admin/wajebaat_import'); ?>" id="import-data" class="btn btn-sm btn-primary">Import Data</a>
    </div>
  </div>
  <hr>

  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="form-row align-items-end">
        <div class="col-md-6 mb-2">
          <label class="small mb-1">Name / ITS</label>
          <input type="text" id="waFilterText" class="form-control form-control-sm" placeholder="Search by name or ITS">
        </div>
      </div>

      <button type="button" class="btn btn-sm btn-outline-secondary" id="waClearFilters">Clear</button>
      <span class="small text-muted ml-2" id="waFilterSummary" aria-live="polite"></span>
    </div>
  </div>

  <div class="card p-3">
    <div class="wajebaat-table-container">
      <table class="table table-striped table-bordered wajebaat-table">
        <thead>
          <tr>
            <th data-sort="number">ID</th>
            <th data-sort="number">ITS ID</th>
            <th data-sort="string">Name</th>
            <th data-sort="number">Amount</th>
            <th data-sort="number">Due</th>
            <th data-sort="date">Created At</th>
            <th data-sort="none">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($wajebaat) && is_array($wajebaat)) : ?>
            <?php foreach ($wajebaat as $row) : ?>
              <?php
                $itsAttr = isset($row['ITS_ID']) ? (string)$row['ITS_ID'] : '';
                $nameAttr = isset($row['Full_Name']) ? (string)$row['Full_Name'] : '';
              ?>
              <tr data-its="<?php echo htmlspecialchars($itsAttr); ?>" data-name="<?php echo htmlspecialchars(strtolower($nameAttr)); ?>">
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['ITS_ID']); ?></td>
                <td><?php echo htmlspecialchars($row['Full_Name'] ?? ''); ?></td>
                <td data-raw="<?php echo htmlspecialchars((float)$row['amount']); ?>">₹<?php echo htmlspecialchars(format_inr($row['amount'], 2)); ?></td>
                <td class="text-danger" data-raw="<?php echo htmlspecialchars((float)$row['due']); ?>">₹<?php echo htmlspecialchars(format_inr($row['due'], 2)); ?></td>
                <?php
                  $createdRaw = isset($row['created_at']) ? (string)$row['created_at'] : '';
                  $createdTs = $createdRaw !== '' ? strtotime($createdRaw) : false;
                  $createdDisplay = ($createdTs !== false && $createdTs > 0) ? date('d-m-Y H:i:s', $createdTs) : $createdRaw;
                  $createdSort = ($createdTs !== false && $createdTs > 0) ? (int)$createdTs : 0;
                ?>
                <td data-raw="<?php echo htmlspecialchars($createdSort); ?>"><?php echo htmlspecialchars($createdDisplay); ?></td>
                <td>
                  <button type="button" class="btn btn-sm btn-outline-primary view-details"
                    data-name="<?php echo htmlspecialchars(isset($row['Full_Name']) ? $row['Full_Name'] : ''); ?>"
                    data-its="<?php echo htmlspecialchars($row['ITS_ID']); ?>"
                    data-amount="<?php echo htmlspecialchars((float)$row['amount']); ?>"
                    data-amount-formatted="<?php echo htmlspecialchars('₹'.format_inr($row['amount'],2)); ?>"
                    data-due="<?php echo htmlspecialchars((float)$row['due']); ?>"
                    data-due-formatted="<?php echo htmlspecialchars('₹'.format_inr($row['due'],2)); ?>"
                    data-updated="<?php echo htmlspecialchars(!empty($row['updated_at']) ? $row['updated_at'] : $row['created_at']); ?>"
                  >View Details</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="7" class="text-center">No records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function(){
    // Client-side sorting for all columns
    var table = document.querySelector('.wajebaat-table');
    if(!table) return;
    var thead = table.querySelector('thead');
    var tbody = table.querySelector('tbody');
    if(!thead || !tbody) return;

    var headers = Array.prototype.slice.call(thead.querySelectorAll('th'));
    var dirState = {}; // idx -> 'asc'|'desc'

    function getCellSortValue(cell, type){
      if(!cell) return type === 'number' || type === 'date' ? 0 : '';
      var raw = cell.getAttribute('data-raw');
      if(raw !== null && String(raw).trim() !== ''){
        if(type === 'number' || type === 'date') return parseFloat(raw) || 0;
        return String(raw).toLowerCase();
      }
      var txt = (cell.textContent || '').trim();
      if(type === 'number'){
        return parseFloat(txt.replace(/[₹,\s]/g, '')) || 0;
      }
      if(type === 'date'){
        var m = txt.match(/^(\d{2})-(\d{2})-(\d{4})(?:\s+(\d{2}):(\d{2}):(\d{2}))?$/);
        if(m){
          var iso = m[3] + '-' + m[2] + '-' + m[1] + 'T' + (m[4] || '00') + ':' + (m[5] || '00') + ':' + (m[6] || '00');
          var t = Date.parse(iso);
          return isNaN(t) ? 0 : t;
        }
        var t2 = Date.parse(txt.replace(' ', 'T'));
        return isNaN(t2) ? 0 : t2;
      }
      return txt.toLowerCase();
    }

    function setIndicator(activeIdx, dir){
      headers.forEach(function(h, i){
        var label = h.getAttribute('data-label');
        if(!label){
          label = (h.textContent || '').trim();
          h.setAttribute('data-label', label);
        }
        h.innerHTML = label;
        if(i === activeIdx){
          var indicator = document.createElement('span');
          indicator.className = 'sort-indicator';
          indicator.textContent = dir === 'asc' ? ' ▲' : ' ▼';
          h.appendChild(indicator);
        }
      });
    }

    headers.forEach(function(th, idx){
      var type = th.getAttribute('data-sort');
      if(!type || type === 'none') return;
      th.addEventListener('click', function(){
        var current = dirState[idx] === 'asc' ? 'desc' : 'asc';
        dirState[idx] = current;
        setIndicator(idx, current);
        var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
        rows.sort(function(a, b){
          var av = getCellSortValue(a.children[idx], type);
          var bv = getCellSortValue(b.children[idx], type);
          var cmp = 0;
          if(type === 'number' || type === 'date'){
            cmp = av === bv ? 0 : (av < bv ? -1 : 1);
          } else {
            cmp = String(av).localeCompare(String(bv), undefined, { sensitivity: 'base' });
          }
          return current === 'asc' ? cmp : -cmp;
        });
        rows.forEach(function(r){ tbody.appendChild(r); });
      });
    });
  });
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function(){
    var table = document.querySelector('.wajebaat-table');
    if(!table) return;
    var tbody = table.querySelector('tbody');
    if(!tbody) return;

    var textInput = document.getElementById('waFilterText');
    var clearBtn  = document.getElementById('waClearFilters');
    var summaryEl = document.getElementById('waFilterSummary');
    if(!textInput || !clearBtn || !summaryEl) return;

    function applyFilters(){
      var q = (textInput.value || '').trim().toLowerCase();
      var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
      var total = 0;
      var shown = 0;

      rows.forEach(function(tr){
        if(tr.children.length <= 1){
          return;
        }
        total++;

        var name = (tr.getAttribute('data-name') || '');
        var its  = (tr.getAttribute('data-its') || '').toLowerCase();
        var ok = true;
        if(q){
          ok = (name.indexOf(q) !== -1) || (its.indexOf(q) !== -1);
        }

        tr.style.display = ok ? '' : 'none';
        if(ok) shown++;
      });

      summaryEl.textContent = total ? ('Showing ' + shown + ' of ' + total) : '';
    }

    textInput.addEventListener('input', applyFilters);

    clearBtn.addEventListener('click', function(){
      textInput.value = '';
      applyFilters();
    });

    applyFilters();
  });
  </script>

</div>

<!-- Detail Modal -->
<div class="modal fade" id="wajebaatDetailModal" tabindex="-1" role="dialog" aria-labelledby="wajebaatDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wajebaatDetailModalLabel">Wajebaat Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <span id="wajebaat-name"></span></p>
        <p><strong>ITS ID:</strong> <span id="wajebaat-its"></span></p>
        <p><strong>Amount:</strong> <span id="wajebaat-amount"></span></p>
        <p><strong>Due:</strong> <span id="wajebaat-due" class="text-danger"></span></p>
        <p><strong>Last updated:</strong> <span id="wajebaat-updated"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  function formatDateDMY(raw){
    if(!raw) return '';
    var s = String(raw).trim();
    if(!s) return '';
    s = s.replace('T', ' ');
    var parts = s.split(' ');
    var date = parts[0];
    var time = parts.slice(1).join(' ').trim();
    var m = date.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if(m){
      var dmy = m[3] + '-' + m[2] + '-' + m[1];
      return time ? (dmy + ' ' + time) : dmy;
    }
    return raw;
  }

  function normalizeRupee(str){
    if(!str) return '';
    return String(str).replace(/^₹\s+/, '₹');
  }

  function showModal(){
    var modalEl = document.getElementById('wajebaatDetailModal');
    if(window.jQuery && typeof jQuery(modalEl).modal === 'function'){
      jQuery(modalEl).modal('show');
    } else if(window.bootstrap && typeof bootstrap.Modal === 'function'){
      new bootstrap.Modal(modalEl).show();
    } else {
      modalEl.style.display = 'block';
      modalEl.classList.add('show');
    }
  }

  document.querySelectorAll('.view-details').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.getElementById('wajebaat-name').innerText = btn.getAttribute('data-name') || '';
      document.getElementById('wajebaat-its').innerText = btn.getAttribute('data-its') || '';
      document.getElementById('wajebaat-amount').innerText = normalizeRupee(btn.getAttribute('data-amount-formatted') || btn.getAttribute('data-amount') || '');
      document.getElementById('wajebaat-due').innerText = normalizeRupee(btn.getAttribute('data-due-formatted') || btn.getAttribute('data-due') || '');
      document.getElementById('wajebaat-updated').innerText = formatDateDMY(btn.getAttribute('data-updated') || '');
      showModal();
    });
  });
});
</script>
