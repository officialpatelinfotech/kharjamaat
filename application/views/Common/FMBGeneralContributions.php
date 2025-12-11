<?php
$rows = isset($rows) ? $rows : [];
$years = isset($contri_years) ? $contri_years : [];
$selectedYear = isset($selected_contri_year) ? $selected_contri_year : '';
$filters = isset($filters) ? $filters : [];
$contriTypes = isset($contri_types) ? $contri_types : [];
$paymentsByGc = isset($payments_by_gc) ? $payments_by_gc : [];
$paymentRowsByGc = isset($payments_rows_by_gc) ? $payments_rows_by_gc : [];
$typeCounts = isset($contri_type_counts) ? $contri_type_counts : [];
// Compute quick stats across current rows
$totalCount = is_array($rows) ? count($rows) : 0;
$totalAmount = 0.0;
$totalPaidAll = 0.0;
$totalDueAll = 0.0;
if (!empty($rows)) {
  foreach ($rows as $rr) {
    $amt = (float)($rr['amount'] ?? 0);
    $pid = $rr['id'] ?? null;
    $paid = $pid && isset($paymentsByGc[$pid]) ? (float)($paymentsByGc[$pid]['total_paid'] ?? 0) : 0.0;
    $due = max($amt - $paid, 0);
    $totalAmount += $amt;
    $totalPaidAll += $paid;
    $totalDueAll += $due;
  }
}
?>
<style>
  /* Sticky table header */
  .sticky-header thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fff;
  }

  /* Extra small progress bar */
  .progress-xxs {
    height: 6px;
  }

  /* Subtle card hover */
  .card.hoverable:hover {
    transform: translateY(-1px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
    transition: all .15s ease;
  }

  .chip {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
  }

  .chip .close {
    text-decoration: none;
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="row align-items-center mb-3">
    <div class="col-auto">
      <a href="<?php echo base_url($active_controller); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col text-center">
      <h4 class="heading mb-0">FMB General Contributions<?= $selectedYear ? ' - Hijri ' . htmlspecialchars($selectedYear) : '' ?></h4>
    </div>
    <div class="col-auto"></div>
  </div>

  <!-- Quick stats -->
  <div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm hoverable h-100">
        <div class="card-body py-2">
          <div class="text-muted small">Contributions</div>
          <div class="fs-5 fw-semibold"><?= number_format((int)$totalCount) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm hoverable h-100">
        <div class="card-body py-2">
          <div class="text-muted small">Total Amount</div>
          <div class="fs-5 fw-semibold">₹<?= number_format($totalAmount, 2) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm hoverable h-100">
        <div class="card-body py-2">
          <div class="text-muted small">Total Paid</div>
          <div class="fs-5 fw-semibold text-success">₹<?= number_format($totalPaidAll, 2) ?></div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm hoverable h-100">
        <div class="card-body py-2">
          <div class="text-muted small">Total Due</div>
          <div class="fs-5 fw-semibold text-warning">₹<?= number_format($totalDueAll, 2) ?></div>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($typeCounts)): ?>
    <div class="row g-2 mb-3">
      <?php foreach ($typeCounts as $tc):
        $t = $tc['contri_type'] ?? '';
        $cnt = (int)($tc['count'] ?? 0);
        $sum = (float)($tc['total_amount'] ?? 0);
        // Build link preserving filters but setting contri_type
        $qs = [
          'contri_year' => $filters['contri_year'] ?? $selectedYear,
          'fmb_type' => $filters['fmb_type'] ?? '',
          'payment_status' => $filters['payment_status'] ?? '',
          'user_id' => $filters['user_id'] ?? '',
          'contri_type' => $t,
        ];
        $href = site_url('common/fmb_general_contributions') . '?' . http_build_query(array_filter($qs, function ($v) {
          return $v !== null;
        }));
      ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <a href="<?= $href ?>" class="text-decoration-none">
            <div class="card shadow-sm h-100">
              <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-dark" title="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?: 'Unknown' ?></div>
                  <span class="badge bg-primary rounded-pill"><?= $cnt ?></span>
                </div>
                <div class="small text-muted mt-1">₹<?= number_format($sum, 2) ?> • <?= $cnt ?></div>
                <div class="text-end mt-1"><span class="text-primary small">View details →</span></div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="get" action="<?php echo site_url('common/fmb_general_contributions'); ?>" class="row g-2 align-items-center mb-2" id="gcFilterForm">
    <?php if (!empty($from)): ?>
      <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
    <?php endif; ?>
    <div class="col-12 col-sm-auto">
      <label for="contri_year" class="form-label me-2 mr-2 mb-0">Hijri Year</label>
      <select name="contri_year" id="contri_year" class="form-control form-select" onchange="document.getElementById('gcFilterForm').submit();">
        <?php foreach ($years as $y): ?>
          <option value="<?php echo htmlspecialchars($y); ?>" <?php echo ($selectedYear == $y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-sm-auto">
      <label for="fmb_type" class="form-label me-2 mr-2 mb-0">FMB Type</label>
      <input type="text" class="form-control" name="fmb_type" id="fmb_type" value="<?php echo htmlspecialchars($filters['fmb_type'] ?? ''); ?>" placeholder="Thaali, Niyaz...">
    </div>
    <div class="col-12 col-sm-auto">
      <label for="contri_type" class="form-label me-2 mr-2 mb-0">Contri Type</label>
      <select class="form-control form-select" name="contri_type" id="contri_type" onchange="document.getElementById('gcFilterForm').submit();">
        <option value="">All</option>
        <?php foreach ($contriTypes as $ct): ?>
          <option value="<?php echo htmlspecialchars($ct); ?>" <?php echo (($filters['contri_type'] ?? '') === $ct) ? 'selected' : ''; ?>><?php echo htmlspecialchars($ct); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-sm-auto">
      <label for="payment_status" class="form-label me-2 mr-2 mb-0">Payment Status</label>
      <select class="form-control form-select" name="payment_status" id="payment_status" onchange="document.getElementById('gcFilterForm').submit();">
        <option value="">All</option>
        <option value="0" <?php echo (isset($filters['payment_status']) && $filters['payment_status'] === '0') ? 'selected' : ''; ?>>Pending</option>
        <option value="1" <?php echo (isset($filters['payment_status']) && $filters['payment_status'] == '1') ? 'selected' : ''; ?>>Paid</option>
      </select>
    </div>
    <div class="col-12 col-sm-auto">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
  </form>

  <!-- Active filter chips -->
  <?php
  $chips = [];
  if (!empty($filters['fmb_type'])) $chips[] = ['label' => 'FMB Type: ' . $filters['fmb_type'], 'param' => 'fmb_type'];
  if (!empty($filters['contri_type'])) $chips[] = ['label' => 'Type: ' . $filters['contri_type'], 'param' => 'contri_type'];
  if (isset($filters['payment_status']) && $filters['payment_status'] !== '' && $filters['payment_status'] !== null) {
    $chips[] = ['label' => 'Status: ' . (($filters['payment_status'] === '1') ? 'Paid' : 'Pending'), 'param' => 'payment_status'];
  }
  if (!empty($filters['user_id'])) $chips[] = ['label' => 'ITS ID: ' . $filters['user_id'], 'param' => 'user_id'];
  $baseFilters = $filters; // Preserve for links
  ?>
  <?php if (!empty($chips)): ?>
    <div class="mb-3">
      <?php foreach ($chips as $chip):
        $q = $baseFilters;
        unset($q[$chip['param']]);
        $href = site_url('common/fmb_general_contributions') . '?' . http_build_query(array_filter($q, function ($v) {
          return $v !== null && $v !== '';
        }));
      ?>
        <a href="<?= $href ?>" class="badge rounded-pill bg-light text-dark border me-1 chip">
          <?= htmlspecialchars($chip['label']) ?> <span class="close">&times;</span>
        </a>
      <?php endforeach; ?>
      <?php
      // Clear all except year
      $clearAll = ['contri_year' => $filters['contri_year'] ?? $selectedYear];
      $clearHref = site_url('common/fmb_general_contributions') . '?' . http_build_query($clearAll);
      ?>
      <a href="<?= $clearHref ?>" class="badge rounded-pill bg-danger text-light ms-1">Clear all</a>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-hover sticky-header mb-0">
        <thead>
          <tr>
            <th data-type="number">ID</th>
            <th data-type="date">Date</th>
            <th data-type="number">ITS ID</th>
            <th data-type="string">Name</th>
            <th data-type="number">Hijri Year</th>
            <th data-type="string">FMB Type</th>
            <th data-type="string">Contribution Type</th>
            <th data-type="number">Amount</th>
            <th data-no-sort>Payments</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="9" class="text-center py-4 text-muted">No contributions found for the selected filters.</td>
            </tr>
          <?php endif; ?>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td data-sort-value="<?= (int)($r['id'] ?? 0) ?>"><?= (int)($r['id'] ?? 0) ?></td>
              <td data-sort-value="<?= htmlspecialchars($r['created_at'] ?? '') ?>"><?= htmlspecialchars($r['created_at'] ?? '') ?></td>
              <td data-sort-value="<?= htmlspecialchars($r['user_id'] ?? '') ?>"><?= htmlspecialchars($r['user_id'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['user_name'] ?? '') ?></td>
              <td data-sort-value="<?= htmlspecialchars($r['contri_year'] ?? '') ?>"><?= htmlspecialchars($r['contri_year'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['fmb_type'] ?? '') ?></td>
              <td><?= htmlspecialchars($r['contri_type'] ?? '') ?></td>
              <td data-sort-value="<?= (float)($r['amount'] ?? 0) ?>">₹<?= number_format((float)($r['amount'] ?? 0), 2) ?></td>
              <td>
                <?php
                $pid = $r['id'] ?? null;
                $pSum = ($pid && isset($paymentsByGc[$pid])) ? $paymentsByGc[$pid] : ['total_paid' => 0, 'payment_count' => 0];
                $totalPaid = (float)($pSum['total_paid'] ?? 0);
                $paymentCount = (int)($pSum['payment_count'] ?? 0);
                $due = max(((float)($r['amount'] ?? 0)) - $totalPaid, 0);
                $percent = ((float)($r['amount'] ?? 0)) > 0 ? round(($totalPaid / (float)$r['amount']) * 100) : 0;
                ?>
                <div class="d-flex flex-wrap align-items-center">
                  <span class="badge bg-success rounded-pill me-1 mb-1 small">Paid: ₹<?= number_format($totalPaid, 2) ?></span>
                  <span class="badge bg-secondary rounded-pill me-1 mb-1 small">Cnt: <?= $paymentCount ?></span>
                  <span class="badge bg-warning text-dark rounded-pill me-1 mb-1 small">Due: ₹<?= number_format($due, 2) ?></span>
                </div>
                <div class="progress progress-xxs mt-1" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar bg-success" style="width: <?= $percent ?>%"></div>
                </div>
                <?php if (!empty($paymentRowsByGc[$pid])): ?>
                  <details class="mt-1 gc-payments-details">
                    <summary>View payments</summary>
                    <div class="mt-2">
                      <table class="table table-sm table-bordered">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Remarks</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($paymentRowsByGc[$pid] as $idx => $pr): ?>
                            <tr>
                              <td><?= $idx + 1 ?></td>
                              <td><?= htmlspecialchars($pr['payment_date'] ?? '') ?></td>
                              <td>₹<?= number_format((float)($pr['amount'] ?? 0), 2) ?></td>
                              <td><?= htmlspecialchars($pr['payment_method'] ?? '') ?></td>
                              <td><?= htmlspecialchars($pr['remarks'] ?? '') ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </details>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer py-2 d-flex gap-2 align-items-center">
      <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleAllPayments">Expand/Collapse payments</button>
    </div>
  </div>

  <script>
    // Table sorter for FMB General Contributions table
    (function() {
      document.addEventListener('DOMContentLoaded', function() {
        var table = document.querySelector('.table.table-striped.table-hover.sticky-header');
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
          headers.forEach(function(h) {
            h.removeAttribute('data-sort-order');
            var si = h.querySelector('.sort-indicator');
            if (si) si.textContent = '';
          });
          th.setAttribute('data-sort-order', newOrder);
          var si = th.querySelector('.sort-indicator');
          if (si) si.textContent = newOrder === 'asc' ? '▲' : '▼';

          var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
          rows.sort(function(a, b) {
            var va = parseValue(getCellText(a, index), type);
            var vb = parseValue(getCellText(b, index), type);
            if (va < vb) return newOrder === 'asc' ? -1 : 1;
            if (va > vb) return newOrder === 'asc' ? 1 : -1;
            return 0;
          });
          rows.forEach(function(r) {
            tbody.appendChild(r);
          });
        }
      });
    })();

    (function() {
      const btn = document.getElementById('toggleAllPayments');
      if (!btn) return;
      let expanded = false;
      btn.addEventListener('click', function() {
        const details = document.querySelectorAll('details.gc-payments-details');
        details.forEach(d => {
          d.open = !expanded;
        });
        expanded = !expanded;
        btn.textContent = expanded ? 'Collapse payments' : 'Expand/Collapse payments';
      });
    })();
  </script>
</div>