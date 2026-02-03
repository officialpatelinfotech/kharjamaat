<?php
$users = isset($users) ? $users : [];
$selectedYear = $selected_hijri_year ?? '';
$years = isset($hijri_years) ? $hijri_years : [];
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo site_url('common/fmbtakhmeen'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="container mt-3">
    <h4 class="heading text-center mb-3">FMB Users<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h4>

    <form method="get" action="<?php echo site_url('common/fmb_users'); ?>" class="row g-2 align-items-center mb-3" id="yearFilterForm">
      <div class="col-12 col-sm-auto">
        <div class="d-flex align-items-center">
          <label for="hijri_year" class="form-label me-2 mr-2 mb-0" style="white-space: nowrap;">Hijri Financial Year</label>
          <select name="hijri_year" id="hijri_year" class="form-control form-select" style="min-width: 160px;" onchange="document.getElementById('yearFilterForm').submit();">
            <option value="">All</option>
            <?php foreach ($years as $y):
              $start = (int)$y;
              $end_short = sprintf('%02d', ($start + 1) % 100);
            ?>
              <option value="<?php echo htmlspecialchars($y); ?>" <?php echo ($selectedYear == $y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($start . '-' . $end_short); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-12 col-sm-auto">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" id="amount_zero" name="amount_zero" <?php echo !empty($amount_zero) ? 'checked' : ''; ?> onchange="document.getElementById('yearFilterForm').submit();">
          <label class="form-check-label" for="amount_zero">
            Only zero amount
          </label>
        </div>
      </div>
      <div class="col-12 col-sm-auto">
        <div class="d-flex align-items-center">
          <label for="name" class="form-label me-2 mr-2 mb-0" style="white-space: nowrap;">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Search name or ITS" value="<?php echo htmlspecialchars($name_filter ?? ''); ?>" style="min-width: 200px;" />
        </div>
      </div>
      <div class="col-12 col-sm-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
    </form>

    <?php if ($selectedYear && empty($users)): ?>
      <div class="alert alert-warning" role="alert">No takhmeen done for this year.</div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th data-no-sort>#</th>
            <th data-type="number">ITS ID</th>
            <th data-type="string">Name</th>
            <th data-type="number">Total Takhmeen</th>
            <th data-type="number">Due</th>
            <th data-no-sort>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          foreach ($users as $u):
            $name = trim((string)($u['full_name'] ?? ''));
            $uid = (string)($u['user_id'] ?? '');
            // Skip entries where a proper name is not present (avoid showing ITS_ID as name)
            if ($name === '' || $name === $uid) {
              continue;
            }
          ?>
            <tr>
              <td><?= $i++; ?></td>
              <td data-sort-value="<?= htmlspecialchars($u['user_id'] ?? '') ?>"><?= htmlspecialchars($u['user_id'] ?? '') ?></td>
              <td><?= htmlspecialchars($u['full_name'] ?? '') ?></td>
              <td data-sort-value="<?= (float)($u['total_takhmeen'] ?? 0) ?>">₹<?= number_format((float)($u['total_takhmeen'] ?? 0)) ?></td>
              <td data-sort-value="<?= (float)($u['outstanding'] ?? 0) ?>">₹<?= number_format((float)($u['outstanding'] ?? 0)) ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('common/fmb_user_details/' . urlencode($u['user_id'] ?? '')) . ($selectedYear ? ('?hijri_year=' . urlencode($selectedYear)) : ''); ?>">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var table = document.querySelector('.table.table-bordered.table-striped');
          if (!table) return;
          var thead = table.querySelector('thead');
          var tbody = table.querySelector('tbody');
          var headers = thead.querySelectorAll('th');

          headers.forEach(function(th, idx) {
            if (th.hasAttribute('data-no-sort')) return;
            th.classList.add('sortable');
            th.setAttribute('role', 'button');
            th.setAttribute('tabindex', '0');
            var indicator = document.createElement('span');
            indicator.className = 'sort-indicator ml-2';
            th.appendChild(indicator);
            th.addEventListener('click', function() { sortTableByColumn(idx, th); });
            th.addEventListener('keydown', function(e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); sortTableByColumn(idx, th); } });
          });

          function getCellText(row, index) {
            var cell = row.children[index];
            if (!cell) return '';
            return cell.getAttribute('data-sort-value') || cell.textContent.trim();
          }

          function parseValue(val, type) {
            if (type === 'number') {
              if (val === null || val === undefined || val === '') return 0;
              var num = ('' + val).replace(/[^0-9.\-]+/g, '');
              var f = parseFloat(num);
              return isNaN(f) ? 0 : f;
            }
            if (type === 'date') {
              var d = Date.parse(val);
              return isNaN(d) ? 0 : d;
            }
            return ('' + val).toLowerCase();
          }

          function sortTableByColumn(index, th) {
            var type = th.getAttribute('data-type') || 'string';
            var current = th.getAttribute('data-sort-order') || '';
            var newOrder = current === 'asc' ? 'desc' : 'asc';
            headers.forEach(function(h) { h.removeAttribute('data-sort-order'); var si = h.querySelector('.sort-indicator'); if (si) si.textContent = ''; });
            th.setAttribute('data-sort-order', newOrder);
            var si = th.querySelector('.sort-indicator'); if (si) si.textContent = newOrder === 'asc' ? '▲' : '▼';

            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
            rows.sort(function(a, b) {
              var va = parseValue(getCellText(a, index), type);
              var vb = parseValue(getCellText(b, index), type);
              if (va < vb) return newOrder === 'asc' ? -1 : 1;
              if (va > vb) return newOrder === 'asc' ? 1 : -1;
              return 0;
            });
            rows.forEach(function(r) { tbody.appendChild(r); });
          }
        });
      </script>
    </div>
  </div>
</div>