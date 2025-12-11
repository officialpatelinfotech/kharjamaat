<?php // Sabeel Users list - simple table
$hijri_years = isset($hijri_years) ? $hijri_years : [];
$selected = isset($selected_hijri_year) ? $selected_hijri_year : '';
?>
<div class="container margintopcontainer pt-5">
  <div class="col-3">
    <a href="<?php echo base_url("common/sabeeltakhmeen"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="container">
    <h4 class="heading text-center mb-3">Sabeel Users</h4>
    <form method="get" class="form-inline mb-3" action="<?php echo site_url('common/sabeel_users'); ?>">
      <label class="mr-2">Hijri Financial Year</label>
      <select name="hijri_year" class="form-control mr-2" onchange="this.form.submit()">
        <option value="">All</option>
        <?php foreach ($hijri_years as $y):
          $isRange = is_string($y) && preg_match('/^\d{4}-\d{2}$/', $y);
          if ($isRange) {
            $optVal = (string)$y;
            $optLabel = (string)$y;
          } else {
            $start = (int)$y;
            $end_short = sprintf('%02d', ((int)$y + 1) % 100);
            $optVal = $start . '-' . $end_short;
            $optLabel = $optVal;
          }
        ?>
          <option value="<?php echo htmlspecialchars($optVal); ?>" <?php echo ($selected == $optVal) ? 'selected' : ''; ?>><?php echo htmlspecialchars($optLabel); ?></option>
        <?php endforeach; ?>
      </select>
      <div class="form-check form-check-inline ml-2">
        <input class="form-check-input" type="checkbox" name="amount_zero" id="amount_zero" value="1" <?php echo !empty($amount_zero) ? 'checked' : ''; ?> onchange="this.form.submit()">
        <label class="form-check-label" for="amount_zero">Only zero amount</label>
      </div>
      <label class="mr-2">Name</label>
      <input type="text" name="name" class="form-control mr-2" placeholder="Search name" value="<?php echo htmlspecialchars($name_filter ?? ''); ?>" />
      <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <?php if ($selected && empty($users)): ?>
      <div class="alert alert-warning" role="alert">No takhmeen done for this year.</div>
    <?php endif; ?>
    <table class="table table-bordered table-sm">
      <thead>
        <tr>
          <th data-no-sort>#</th>
          <th data-type="number">User ID</th>
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
            <td><?php echo $i++; ?></td>
            <td><?php echo $u['user_id']; ?></td>
            <td><?php echo $u['full_name']; ?></td>
            <td data-sort-value="<?php echo (float)$u['total_takhmeen']; ?>">₹ <?php echo number_format((float)$u['total_takhmeen'], 0); ?></td>
            <td data-sort-value="<?php echo (float)($u['outstanding'] ?? 0); ?>" class="text-danger">₹ <?php echo number_format((float)($u['outstanding'] ?? 0), 0); ?></td>
            <td><a href="<?php echo site_url('Common/sabeel_user_details/' . $u['user_id']) . ($selected ? ('?hijri_year=' . urlencode($selected)) : ''); ?>" class="btn btn-sm btn-primary">Details</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var table = document.querySelector('.table.table-bordered.table-sm');
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
          th.addEventListener('click', function() {
            sortTableByColumn(idx, th);
          });
          th.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              sortTableByColumn(idx, th);
            }
          });
        });

        function getCellText(row, index) {
          var cell = row.children[index];
          if (!cell) return '';
          return cell.getAttribute('data-sort-value') || cell.textContent.trim();
        }

        function parseValue(val, type) {
          if (type === 'number') {
            if (val === null || val === undefined || val === '') return 0;
            // Remove any currency symbols and commas
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
          // clear other indicators
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