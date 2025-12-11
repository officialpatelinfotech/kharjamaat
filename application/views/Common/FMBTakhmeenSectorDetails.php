<?php
$year = $selected_hijri_year ?? '';
$sector = $sector ?? '';
$details = isset($details) ? $details : ['rows' => [], 'totals' => ['total'=>0,'paid'=>0,'due'=>0], 'takhmeen_year' => ''];
?>
<?php
// Local INR formatter (Indian grouping): 1,23,45,678
if (!function_exists('format_inr')) {
  function format_inr($number, $decimals = 0) {
    $number = round((float)$number, $decimals);
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $parts = explode('.', number_format($number, $decimals, '.', ''));
    $int = $parts[0];
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '') {
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',';
    } else {
      $rest = '';
    }
    $formatted = $sign . $rest . $last3;
    if ($decimals > 0) {
      $formatted .= '.' . str_pad($parts[1] ?? '0', $decimals, '0');
    }
    return $formatted;
  }
}
?>
<div class="container margintopcontainer pt-5">
  <div class="mb-3">
    <a href="<?php echo site_url('common/fmbtakhmeen?hijri_year=' . urlencode($year)); ?>" class="btn btn-outline-secondary">
      <i class="fa fa-arrow-left"></i>
    </a>
  </div>
  <h4 class="heading text-center mb-2">FMB Sector Details</h4>
  <p class="text-center text-muted mb-4">Sector: <strong><?php echo htmlspecialchars($sector); ?></strong> · Hijri <strong><?php echo htmlspecialchars($year); ?></strong> (<?php echo htmlspecialchars($details['takhmeen_year']); ?>)</p>

  <div class="card p-3 mb-3 text-center">
    <div class="row">
    <div class="col-4">
        <div class="small text-muted">Total</div>
        <div class="h4 mb-0">₹<?php echo format_inr((float)($details['totals']['total'] ?? 0)); ?></div>
      </div>
      <div class="col-4">
        <div class="small text-muted">Paid</div>
        <div class="h4 mb-0 text-success">₹<?php echo format_inr((float)($details['totals']['paid'] ?? 0)); ?></div>
      </div>
      <div class="col-4">
        <div class="small text-muted">Due</div>
        <div class="h4 mb-0 text-danger">₹<?php echo format_inr((float)($details['totals']['due'] ?? 0)); ?></div>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
            <th data-type="string">Member</th>
            <th data-type="number">Total</th>
            <th data-type="number">Paid (FIFO)</th>
            <th data-type="number">Due</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($details['rows'])): ?>
          <?php foreach ($details['rows'] as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r['name']); ?></td>
              <td data-sort-value="<?php echo (float)$r['total']; ?>">₹<?php echo format_inr((float)$r['total']); ?></td>
              <td class="text-success" data-sort-value="<?php echo (float)$r['paid']; ?>">₹<?php echo format_inr((float)$r['paid']); ?></td>
              <td class="text-danger" data-sort-value="<?php echo (float)$r['due']; ?>">₹<?php echo format_inr((float)$r['due']); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center text-muted">No takhmeen data for this sector and year.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    </div>

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
