<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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

$rows = isset($wajebaat_rows) && is_array($wajebaat_rows) ? $wajebaat_rows : [];
?>

<style>
  .page-title {
    line-height: 1.6;
    margin-bottom: 1.5rem;
  }

  .details-table-container {
    max-height: 520px;
    overflow: auto;
  }
  .details-table th,
  .details-table td {
    padding-top: 0.9rem;
    padding-bottom: 0.9rem;
  }
  .details-table thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 3;
    box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
  }
  .details-table thead th[data-sort]:not([data-sort="none"]) {
    cursor: pointer;
    user-select: none;
  }
  .details-table thead th .sort-indicator {
    font-size: 0.85em;
  }
</style>

<div class="container-fluid margintopcontainer pt-5">
  <div class="d-flex align-items-center">
    <a href="<?= base_url('amilsaheb'); ?>" class="btn btn-sm btn-outline-secondary mr-2"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <h4 class="text-center page-title">Wajebaat Details</h4>

  <?php
  $sumAssigned = 0.0;
  $sumPaid = 0.0;
  $sumOutstanding = 0.0;
  foreach ($rows as $r) {
    $assigned = (float)($r['amount'] ?? 0);
    $due = (float)($r['due'] ?? 0);
    $sumAssigned += $assigned;
    $sumOutstanding += $due;
    $sumPaid += max(0, $assigned - $due);
  }
  ?>

  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="row text-center">
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value">₹<?= format_inr($sumAssigned); ?></div>
            <div class="stats-label">Total Amount</div>
          </div>
        </div>
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value text-success">₹<?= format_inr($sumPaid); ?></div>
            <div class="stats-label">Total Paid</div>
          </div>
        </div>
        <div class="col-6 col-md-4 mb-2">
          <div class="mini-card" style="margin-bottom:4px;">
            <div class="stats-value text-danger">₹<?= format_inr($sumOutstanding); ?></div>
            <div class="stats-label">Outstanding</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="form-row align-items-end">
        <div class="col-md-4">
          <label class="small mb-1">Name / ITS</label>
          <input type="text" id="filterText" class="form-control form-control-sm" placeholder="Search by name or ITS">
        </div>
      </div>
      <div class="mt-2 d-flex">
        <button id="btnClearFilters" class="btn btn-sm btn-outline-secondary">Clear</button>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="details-table-container">
        <table class="table table-striped table-bordered details-table" id="wajebaatTable">
          <thead>
            <tr>
              <th style="width:80px;" data-sort="number">Sr. No</th>
              <th style="width:120px;" data-sort="number">ITS</th>
              <th data-sort="string">Name</th>
              <th class="text-right" data-sort="number">Amount</th>
              <th class="text-right" data-sort="number">Paid</th>
              <th class="text-right" data-sort="number">Outstanding</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($rows)) { ?>
              <tr>
                <td colspan="6" class="text-center text-muted">No wajebaat records found.</td>
              </tr>
            <?php } else {
              $i = 1;
              foreach ($rows as $r) {
                $its = (string)($r['ITS_ID'] ?? '');
                $name = (string)($r['Full_Name'] ?? '');
                $assigned = (float)($r['amount'] ?? 0);
                $due = (float)($r['due'] ?? 0);
                $paid = max(0, $assigned - $due);
                $itsSort = is_numeric($its) ? (int)$its : 0;
            ?>
              <tr data-its="<?= htmlspecialchars($its); ?>" data-name="<?= htmlspecialchars(strtolower($name)); ?>">
                <td data-raw="<?= (int)$i; ?>"><?= $i++; ?></td>
                <td data-raw="<?= (int)$itsSort; ?>"><?= htmlspecialchars($its); ?></td>
                <td><?= htmlspecialchars($name !== '' ? $name : '—'); ?></td>
                <td class="text-right" data-raw="<?= htmlspecialchars($assigned); ?>">₹<?= format_inr($assigned); ?></td>
                <td class="text-right text-success" data-raw="<?= htmlspecialchars($paid); ?>">₹<?= format_inr($paid); ?></td>
                <td class="text-right text-danger" data-raw="<?= htmlspecialchars($due); ?>">₹<?= format_inr($due); ?></td>
              </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    var input = document.getElementById('filterText');
    var btnClear = document.getElementById('btnClearFilters');
    var table = document.getElementById('wajebaatTable');
    if (!input || !btnClear || !table) return;

    function applyFilter() {
      var q = (input.value || '').trim().toLowerCase();
      var rows = table.querySelectorAll('tbody tr');
      rows.forEach(function(tr) {
        // Keep the "no records" row visible
        if (tr.children.length === 1) return;
        var name = (tr.getAttribute('data-name') || '');
        var its = (tr.getAttribute('data-its') || '').toLowerCase();
        var show = (q === '') || name.indexOf(q) !== -1 || its.indexOf(q) !== -1;
        tr.style.display = show ? '' : 'none';
      });
    }

    input.addEventListener('input', applyFilter);
    btnClear.addEventListener('click', function() {
      input.value = '';
      applyFilter();
    });
  })();
</script>

<script>
  (function() {
    var table = document.getElementById('wajebaatTable');
    if (!table) return;
    var thead = table.querySelector('thead');
    var tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    var headers = Array.prototype.slice.call(thead.querySelectorAll('th'));
    var dirState = {}; // idx -> 'asc'|'desc'

    function getCellSortValue(cell, type) {
      if (!cell) return type === 'number' ? 0 : '';
      var raw = cell.getAttribute('data-raw');
      if (raw !== null && String(raw).trim() !== '') {
        if (type === 'number') return parseFloat(raw) || 0;
        return String(raw).toLowerCase();
      }
      var txt = (cell.textContent || '').trim();
      if (type === 'number') {
        return parseFloat(txt.replace(/[₹,\s]/g, '')) || 0;
      }
      return txt.toLowerCase();
    }

    function setIndicator(activeIdx, dir) {
      headers.forEach(function(h, i) {
        var label = h.getAttribute('data-label');
        if (!label) {
          label = (h.textContent || '').trim();
          h.setAttribute('data-label', label);
        }
        h.innerHTML = label;
        if (i === activeIdx) {
          var indicator = document.createElement('span');
          indicator.className = 'sort-indicator';
          indicator.textContent = dir === 'asc' ? ' ▲' : ' ▼';
          h.appendChild(indicator);
        }
      });
    }

    headers.forEach(function(th, idx) {
      var type = th.getAttribute('data-sort');
      if (!type || type === 'none') return;
      th.addEventListener('click', function() {
        var current = dirState[idx] === 'asc' ? 'desc' : 'asc';
        dirState[idx] = current;
        setIndicator(idx, current);
        var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
        rows.sort(function(a, b) {
          var av = getCellSortValue(a.children[idx], type);
          var bv = getCellSortValue(b.children[idx], type);
          var cmp = 0;
          if (type === 'number') {
            cmp = av === bv ? 0 : (av < bv ? -1 : 1);
          } else {
            cmp = String(av).localeCompare(String(bv), undefined, {
              sensitivity: 'base'
            });
          }
          return current === 'asc' ? cmp : -cmp;
        });
        rows.forEach(function(r) {
          tbody.appendChild(r);
        });
      });
    });
  })();
</script>
