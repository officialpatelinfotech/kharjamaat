<style>
  .menu-list-container {
    width: 100%;
  }

  /* Hide Actions column and print button when printing */
  @media print {

    /* Hide actions column */
    .menu-list-container table th:last-child,
    .menu-list-container table td:last-child {
      display: none !important;
    }

    /* Hide everything except heading and table */
    #print-table-btn,
    .print-controls,
    .create-menu-btn,
    .create-menu-btn form,
    .create-menu-btn #clear-filter,
    .container.mb-3.p-0 {
      display: none !important;
    }

    /* Remove page padding for compact print */
    body,
    .margintopcontainer {
      padding: 0 !important;
      margin: 0 !important;
    }
  }
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="margintopcontainer pt-5 mx-3">
  <div class="mb-3 p-0">
    <a href="<?php echo isset($from) ? base_url($from) : base_url("anjuman/fmbthaali"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="create-menu-btn d-flex">
    <form method="post" action="<?php echo base_url("common/filter_menu"); ?>" id="filter-form" class="d-flex m-0">
      <!-- <div class="form-group mr-3">
          <input type="text" id="daterange" name="daterange" class="form-control" />
          <input type="hidden" id="start_date" name="start_date" value="<?php echo isset($start_date) ? $start_date : ''; ?>">
          <input type="hidden" id="end_date" name="end_date" value="<?php echo isset($end_date) ? $end_date : ''; ?>">
        </div> -->

      <div class="form-group">
        <select name="hijri_month" id="hijri-month" class="form-control">
          <option value="">Select Month / Year</option>
          <option value="-3" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -3 ? "selected" : ""; ?>>Last Year</option>
          <option value="-1" <?php echo (isset($hijri_month_id) ? $hijri_month_id : 0) == -1 ? "selected" : ""; ?>>Current Year</option>
          <?php
          if (isset($hijri_months)) {
            foreach ($hijri_months as $key => $value) {
          ?>
              <option value="<?php echo $value["id"]; ?>" <?php echo isset($value["id"]) ? ($value["id"] == (isset($hijri_month_id) ? $hijri_month_id : 0) ? "selected" : "") : ""; ?>><?php echo $value["hijri_month"]; ?></option>
          <?php
            }
          }
          ?>
        </select>
      </div>

      <div class="form-group mx-3" style="min-width:240px;">
        <input type="text" id="filter-assigned" class="form-control" placeholder="Filter Name or ITS" autocomplete="off" />
      </div>

      <!-- <div class="sort-options mr-3">
          <select id="sort-type" name="sort_type" class="form-control">
            <option value="asc" <?php echo isset($sort_type) ? ($sort_type == 'asc' ? 'selected' : '') : "" ?>>Sort by Date &darr;</i></option>
            <option value="desc" <?php echo isset($sort_type) ? ($sort_type == 'desc' ? 'selected' : '') : "" ?>>Sort by Date &uarr;</option>
          </select>
        </div> -->
      <div class="clear-filter-btn">
        <a href="<?php echo base_url("common/fmbthaalimenu?from=" . $from); ?>" id="clear-filter" class="btn btn-secondary mx-3"><i class="fa fa-times"></i></a>
      </div>
    </form>

    <div class="ml-auto">
      <!-- <a href="<?= base_url('admin/duplicate_last_month_menu'); ?>" class="btn btn-outline-primary" id="duplicate-menu-btn">
          <i class="fa fa-copy"></i> Duplicate Last Month's Menu
        </a> -->

      <a href="<?php echo base_url("common/add_menu_item?from=" . $from); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-pencil"></i> Edit Items</a>
      <a href="<?php echo base_url("common/createmenu"); ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Menu</a>
    </div>
  </div>



  <h4 class="text-center mb-3">FMB Thaali Menu</h4>

  <?php if (isset($assigned_members_count) || isset($assigned_days_count) || isset($total_thaali_days_count)): ?>
    <div class="mb-2 text-center text-secondary">
      <?php if (isset($assigned_members_count)): ?>
        Assigned Members: <strong class="text-dark"><?php echo (int) $assigned_members_count; ?></strong>
      <?php endif; ?>
      <?php if (isset($assigned_days_count) && isset($total_thaali_days_count)): ?>
        <?php if (isset($assigned_members_count)): ?>
          &nbsp;|&nbsp;
        <?php endif; ?>
        Assigned Days: <strong class="text-dark"><?php echo (int) $assigned_days_count; ?></strong> / <strong class="text-dark"><?php echo (int) $total_thaali_days_count; ?></strong>
      <?php endif; ?>

    </div>
  <?php endif; ?>

  <div class="mb-2 d-flex justify-content-center">
    <div class="text-center">
      <strong>Per Day Thaali Cost</strong>
      <?php if (!empty($per_day_thaali_cost_fy)): ?>
        <small class="text-secondary">(FY - <?php echo htmlspecialchars($per_day_thaali_cost_fy, ENT_QUOTES); ?>)</small>
      <?php endif; ?>
      :
      <?php if (isset($per_day_thaali_cost_amount) && $per_day_thaali_cost_amount !== null && (float)$per_day_thaali_cost_amount > 0): ?>
        <span class="text-primary">₹<?php echo number_format((float)$per_day_thaali_cost_amount, 0, '.', ','); ?></span>
      <?php else: ?>
        <span class="text-secondary">Not set</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="mb-3 d-flex justify-content-end print-controls" style="gap:8px;">
    <button type="button" id="print-table-btn" class="btn btn-outline-primary"><i class="fa fa-print"></i> Print Table</button>
  </div>

  <div class="menu-list-container">
    <table class="table table-bordered mt-2">
      <thead>
        <tr>
          <th data-no-sort>#</th>
          <th>Eng Date</th>
          <th>Hijri Date</th>
          <th>Day</th>
          <th>Menu</th>
          <th>Assigned To</th>
          <th data-no-sort>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($menu)) : ?>
          <?php foreach ($menu as $key => $item) :
            if ($key === 0 || (isset($menu[$key - 1]) && explode(" ", $menu[$key - 1]["hijri_date"], 2)[1] != explode(" ", $menu[$key]["hijri_date"], 2)[1])):
          ?>
              <tr class="month-header" data-hijri-month-name="<?php echo htmlspecialchars(explode(" ", $menu[$key]["hijri_date"], 2)[1], ENT_QUOTES); ?>">
                <td colspan="7" class="bg-dark text-white text-center">Hijri Month: <?php echo explode(" ", $menu[$key]["hijri_date"], 2)[1]; ?></td>
              </tr>
            <?php endif;
            $dayName = isset($item['date']) ? date("l", strtotime($item['date'])) : "";
            $rowClass = '';
            if ($dayName === 'Sunday') {
              $rowClass = 'style="background:#ffe5e5"';
            } elseif (count($item["items"]) == 0) {
              $rowClass = 'class="table-secondary"';
            }
            ?>
            <tr <?php echo $rowClass; ?> data-eng-date="<?php echo isset($item['date']) ? htmlspecialchars($item['date'], ENT_QUOTES) : ''; ?>" data-hijri-date="<?php echo isset($item['hijri_date']) ? htmlspecialchars($item['hijri_date'], ENT_QUOTES) : ''; ?>" data-assigned-its="<?php echo !empty($item['assigned_to_its']) ? htmlspecialchars($item['assigned_to_its'], ENT_QUOTES) : ''; ?>">
              <td><?php echo $key + 1; ?></td>
              <td data-sort-value="<?php echo isset($item['date']) ? htmlspecialchars($item['date'], ENT_QUOTES) : ''; ?>">
                <?php echo isset($item['date']) ? date("d M Y", strtotime($item['date'])) : ""; ?>
              </td>
              <td>
                <?php echo isset($item['hijri_date']) ? $item['hijri_date'] : ""; ?>
              </td>
              <td>
                <?php echo $dayName; ?>
              </td>
              <td>
                <?php echo implode(", ",  $item["items"]); ?>
              </td>
              <td>
                <?php echo !empty($item['assigned_to']) ? htmlspecialchars($item['assigned_to']) : ''; ?>
              </td>
              <?php
              if (count($item["items"]) > 0) :
              ?>
                <td>
                  <a href="<?php echo base_url("common/edit_menu/" . $item['id'] . "?from=" . $from); ?>" class="btn btn-sm btn-primary mb-2 mb-md-0"><i class="fa fa-edit"></i></a>
                  <form method="POST" action="<?php echo base_url('common/delete_menu'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                    <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              <?php else : ?>
                <td>
                  <a href="<?php echo base_url("common/createmenu?date=" . $item['date']); ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i>
                  </a>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="7" class="text-center">No menu items found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <script>
      (function() {
        const table = document.querySelector('.menu-list-container table');
        if (!table) return;
        const thead = table.querySelector('thead');
        const tbody = table.querySelector('tbody');
        if (!thead || !tbody) return;

        // Make headers sortable (except those marked data-no-sort)
        thead.querySelectorAll('th').forEach((th, idx) => {
          if (th.hasAttribute('data-no-sort')) return;
          th.classList.add('sortable');
          const original = th.innerHTML.trim();
          th.innerHTML = '<span class="sort-label">' + original + '</span><span class="sort-indicator" aria-hidden="true"></span>';
          th.setAttribute('role', 'button');
          th.setAttribute('tabindex', '0');
          th.addEventListener('click', () => toggleSort(idx, th));
          th.addEventListener('keydown', e => {
            if (['Enter', ' '].includes(e.key)) {
              e.preventDefault();
              toggleSort(idx, th);
            }
          });
        });

        function getCellValue(tr, index) {
          const cells = tr.querySelectorAll('td');
          if (!cells[index]) return '';
          return cells[index].getAttribute('data-sort-value') || cells[index].textContent.trim();
        }

        function inferType(val) {
          if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return 'date';
          if (!isNaN(parseFloat(val)) && isFinite(val)) return 'number';
          return 'text';
        }

        function norm(val) {
          const t = inferType(val);
          if (t === 'date') return new Date(val).getTime();
          if (t === 'number') return parseFloat(val);
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

          const allRows = Array.from(tbody.querySelectorAll('tr'));
          const monthHeaders = allRows.filter(r => r.classList.contains('month-header'));
          const dataRows = allRows.filter(r => !r.classList.contains('month-header'));

          dataRows.sort((a, b) => {
            const va = norm(getCellValue(a, idx));
            const vb = norm(getCellValue(b, idx));
            if (va < vb) return newDir === 'asc' ? -1 : 1;
            if (va > vb) return newDir === 'asc' ? 1 : -1;
            return 0;
          });

          // Rebuild tbody with month headers preceding first row of each month in current order
          tbody.innerHTML = '';
          const inserted = new Set();
          dataRows.forEach(r => {
            const m = r.getAttribute('data-hijri-date');
            let mName = '';
            if (m) {
              const parts = m.split(' ');
              parts.shift();
              mName = parts.join(' ');
            }
            if (mName && !inserted.has(mName)) {
              const hdr = document.createElement('tr');
              hdr.className = 'month-header';
              const td = document.createElement('td');
              td.colSpan = 7;
              td.className = 'bg-dark text-white text-center';
              td.style.fontWeight = 'bold';
              td.textContent = 'Hijri Month: ' + mName;
              hdr.appendChild(td);
              tbody.appendChild(hdr);
              inserted.add(mName);
            }
            tbody.appendChild(r);
          });
        }

        // Assigned To filter: search by Name or ITS (client-side)
        const assignedFilter = document.getElementById('filter-assigned');

        function applyAssignedFilter() {
          const q = (assignedFilter && assignedFilter.value ? assignedFilter.value : '').toString().toLowerCase().trim();
          const rows = Array.from(tbody.querySelectorAll('tr'));
          // First pass: hide/show data rows
          rows.forEach(r => {
            if (r.classList.contains('month-header')) return;
            if (!q) {
              r.style.display = '';
              return;
            }
            const tds = r.querySelectorAll('td');
            const assignedName = (tds[5] ? tds[5].textContent : '').toString().toLowerCase();
            const assignedIts = (r.getAttribute('data-assigned-its') || '').toString().toLowerCase();
            const show = assignedName.includes(q) || assignedIts.includes(q);
            r.style.display = show ? '' : 'none';
          });
          // Second pass: hide month headers with no visible rows under them
          let currentHeader = null;
          let anyVisibleForHeader = false;
          rows.forEach(r => {
            if (r.classList.contains('month-header')) {
              if (currentHeader) {
                currentHeader.style.display = anyVisibleForHeader ? '' : 'none';
              }
              currentHeader = r;
              anyVisibleForHeader = false;
              return;
            }
            if (r.style.display !== 'none') anyVisibleForHeader = true;
          });
          if (currentHeader) {
            currentHeader.style.display = anyVisibleForHeader ? '' : 'none';
          }
        }
        if (assignedFilter) {
          assignedFilter.addEventListener('input', applyAssignedFilter);
        }

        // Eng Date filter (only this column controls filtering)
        const engFilter = document.getElementById('eng-date-filter');
        if (engFilter) {
          engFilter.addEventListener('change', () => {
            const v = engFilter.value; // YYYY-MM-DD
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.forEach(r => {
              if (r.classList.contains('month-header')) return; // keep headers for context
              const eng = r.getAttribute('data-eng-date') || '';
              if (!v || eng === v) {
                r.style.display = '';
              } else {
                r.style.display = 'none';
              }
            });
          });
        }

        // Print table only
        const printTableBtn = document.getElementById('print-table-btn');
        if (printTableBtn) {
          printTableBtn.addEventListener('click', () => {
            // Directly print current page; CSS @media print hides Actions column & button
            window.print();
          });
        }

      })();
    </script>
  </div>
</div>
<script>
  $(function() {
    start = '<?= isset($start_date) ? $start_date : '' ?>';
    end = '<?= isset($end_date) ? $end_date : '' ?>';

    let startDate = start ? moment(start) : moment().startOf('month');
    let endDate = end ? moment(end) : moment().endOf('month');

    $('#daterange').daterangepicker({
      startDate: startDate,
      endDate: endDate,
      locale: {
        format: 'YYYY-MM-DD'
      },
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'This Week': [moment().startOf('week'), moment().endOf('week')],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      }
    }, function(start, end) {
      $('#start_date').val(start.format('YYYY-MM-DD'));
      $('#end_date').val(end.format('YYYY-MM-DD'));
      $('#daterange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    });

    // On page load: Set the visible input field manually
    $('#daterange').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
  });

  $("#search-btn").on("click", function(e) {
    if ($("#hijri-month").val() == "") {
      e.preventDefault();
      alert("Select a Month / Year to filter");
    }
  });

  $('#hijri-month, #sort-type').on('change', function() {
    $('#filter-form').submit();
  });
</script>