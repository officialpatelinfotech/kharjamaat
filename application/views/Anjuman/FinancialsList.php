<div class="margintopcontainer mt-5 pt-5 p-2 p-md-5">
  <?php
  if (!function_exists('format_inr')) {
    function format_inr($num)
    {
      $num = (int) round((float) $num);
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
  if (!function_exists('get_last10')) {
    function get_last10($raw)
    {
      if (empty($raw)) return '';
      $s = preg_replace('/\D+/', '', (string)$raw);
      if ($s === '') return '';
      if (strlen($s) <= 10) return $s;
      return substr($s, -10);
    }
  }
  ?>
  <div class="my-2">
    <a href="<?= base_url('anjuman'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="d-flex align-items-center justify-content-center mb-2">
    <h4 class="text-center section-title mb-0">Individuals Financials - HOFs</h4>
  </div>
  <div class="card mt-3">
    <div class="card-body p-2">
      <form method="get" action="<?= base_url('anjuman/financials'); ?>" class="form-inline mb-2">
        <div class="input-group mb-2 mb-md-0" style="max-width:500px;">
          <input type="text" name="q" value="<?= isset($pagination['q']) ? htmlspecialchars($pagination['q']) : ''; ?>" class="form-control form-control-sm" placeholder="Search ITS or name">
          <div class="input-group-append">
            <button class="btn btn-sm btn-outline-secondary" type="submit">Search</button>
          </div>
        </div>
        <input type="hidden" name="per_page" value="<?= isset($pagination['per_page']) ? (int)$pagination['per_page'] : 50; ?>">
        <a href="<?= base_url('anjuman/financials'); ?>" class="btn btn-secondary ml-0 ml-md-2"><i class="fa-solid fa-times"></i></a>
      </form>
      <div class="table-responsive">
        <table class="table table-sm table-striped table-fixed">
          <thead>
            <tr>
              <th>S.no.</th>
              <th>ITS</th>
              <th>Name</th>
              <th>Mobile</th>
              <th>All Due</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($hofs) && is_array($hofs)): ?>
              <?php
              $startIndex = 0;
              if (isset($pagination) && is_array($pagination) && isset($pagination['page']) && isset($pagination['per_page'])) {
                $startIndex = ((int)$pagination['page'] - 1) * (int)$pagination['per_page'];
              }
              ?>
              <?php foreach ($hofs as $idx => $h): ?>
                <?php $detailsUrl = base_url('anjuman/member_financials') . '?its_id=' . rawurlencode($h['ITS_ID']); ?>
                <tr data-href="<?= $detailsUrl; ?>" class="clickable-row" tabindex="0">
                  <td><?= $startIndex + $idx + 1; ?></td>
                  <td><?= htmlspecialchars($h['ITS_ID']); ?></td>
                  <td><?= htmlspecialchars($h['Full_Name']); ?></td>
                  <td><?= htmlspecialchars(get_last10($h['Mobile'])); ?></td>
                  <td class="<?= ((float)$h['all_due'] > 0) ? 'text-danger' : ''; ?>"><?= '&#8377;' . format_inr_no_decimals($h['all_due']); ?></td>
                  <td>
                    <a href="<?= $detailsUrl; ?>" class="btn btn-sm btn-primary">View details</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted">No HOFs found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php if (isset($pagination) && is_array($pagination)): ?>
    <?php
    $base = base_url('anjuman/financials');
    $qparam = isset($pagination['q']) && $pagination['q'] !== '' ? '&q=' . rawurlencode($pagination['q']) : '';
    $perparam = '&per_page=' . (int)$pagination['per_page'];
    $cur = (int)$pagination['page'];
    $total = (int)$pagination['total'];
    $tp = (int)$pagination['total_pages'];
    ?>
    <div class="d-flex justify-content-between align-items-center mt-2">
      <div class="text-muted">Showing <?= $total ? (($cur - 1) * $pagination['per_page'] + 1) : 0; ?> to <?= min($total, $cur * $pagination['per_page']); ?> of <?= $total; ?></div>
      <div>
        <nav aria-label="Page navigation">
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $cur <= 1 ? 'disabled' : ''; ?>"><a class="page-link" href="<?= $base . '?page=' . max(1, $cur - 1) . $qparam . $perparam; ?>">&laquo; Prev</a></li>
            <li class="page-item disabled"><span class="page-link">Page <?= $cur; ?> / <?= $tp; ?></span></li>
            <li class="page-item <?= $cur >= $tp ? 'disabled' : ''; ?>"><a class="page-link" href="<?= $base . '?page=' . min($tp, $cur + 1) . $qparam . $perparam; ?>">Next &raquo;</a></li>
          </ul>
        </nav>
      </div>
    </div>
  <?php endif; ?>
</div>

<style>
  /* make clickable rows obvious */
  tr.clickable-row {
    cursor: pointer;
  }

  tr.clickable-row:focus {
    outline: 2px solid rgba(13, 110, 253, .25);
  }

  /* Fluid table layout: allow columns to size naturally and flow with viewport */
  .table-fixed {
    table-layout: auto;
    /* allow browser to size columns based on content */
    width: 100%;
  }

  .table-fixed th,
  .table-fixed td {
    /* Let columns size to their content (single-line) and grow as needed.
       Do not force a tiny max-width — allow horizontal scrolling instead. */
    overflow: visible;
    text-overflow: clip;
    white-space: nowrap;
    /* keep values on a single line */
    vertical-align: middle;
  }

  /* Allow header labels to wrap so the first row shows full text on narrow screens */
  .table-fixed th {
    white-space: normal;
    font-weight: 600;
  }

  /* Optional sensible min-widths so important columns remain readable
     before the user scrolls horizontally. Tweak as needed. */
  .table-fixed th:nth-child(1),
  .table-fixed td:nth-child(1) {
    min-width: 48px;
  }

  .table-fixed th:nth-child(2),
  .table-fixed td:nth-child(2) {
    min-width: 110px;
  }

  .table-fixed th:nth-child(3),
  .table-fixed td:nth-child(3) {
    min-width: 220px;
  }

  .table-fixed th:nth-child(4),
  .table-fixed td:nth-child(4) {
    min-width: 120px;
  }

  .table-fixed th:nth-child(5),
  .table-fixed td:nth-child(5) {
    min-width: 120px;
  }

  .table-fixed th:nth-child(6),
  .table-fixed td:nth-child(6) {
    min-width: 110px;
  }

  /* Ensure horizontal scrolling is available for narrow viewports */
  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    /* limit vertical height so the table body can scroll while header stays fixed */
    max-height: 70vh;
  }

  /* Make the table header sticky so the first row remains visible while scrolling */
  .table-fixed thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #ffffff;
    /* match card background */
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
  }

  /* Small-screen tweaks: keep action buttons visible and let main columns flow */
  @media (max-width: 576px) {

    .table-fixed th,
    .table-fixed td {
      padding: .35rem .5rem;
    }

    .table-fixed td .btn {
      white-space: nowrap;
    }
  }
</style>

<script>
  (function() {
    try {
      var rows = document.querySelectorAll('tr.clickable-row');
      rows.forEach(function(r) {
        r.addEventListener('click', function(e) {
          // don't navigate when clicking links or buttons inside the row
          if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;
          var href = r.getAttribute('data-href');
          if (href) window.location.href = href;
        });
        r.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            var href = r.getAttribute('data-href');
            if (href) window.location.href = href;
          }
        });
      });
    } catch (err) {
      console && console.warn && console.warn('Clickable rows init failed', err);
    }
  })();
</script>
</div>

<script>
  // Make the financials table sortable by clicking headers (except Actions)
  (function() {
    try {
      const table = document.querySelector('.table.table-fixed');
      if (!table) return;
      const thead = table.querySelector('thead');
      const tbody = table.querySelector('tbody');
      if (!thead || !tbody) return;

      function getCellValue(tr, idx) {
        const td = tr.children[idx];
        if (!td) return '';
        return td.getAttribute('data-sort-value') || td.textContent.trim();
      }

      function inferType(val) {
        if (val === null || val === undefined) return 'text';
        // numeric with currency symbols
        if (/^[\s\u20B9,\d.\-]+$/.test(val.replace(/[^0-9\-.,]/g, '')) ) return 'number';
        if (!isNaN(Date.parse(val))) return 'date';
        if (!isNaN(parseFloat(val)) && isFinite(val)) return 'number';
        return 'text';
      }

      function norm(val) {
        const t = inferType(val);
        if (t === 'date') return new Date(val).getTime();
        if (t === 'number') {
          const n = ('' + val).replace(/[\u20B9,\s]/g, '');
          const f = parseFloat(n);
          return isNaN(f) ? 0 : f;
        }
        return ('' + val).toLowerCase();
      }

      // Enhance headers
      Array.from(thead.querySelectorAll('th')).forEach((th, idx) => {
        const txt = th.textContent.trim().toLowerCase();
        if (txt === 'actions') return; // skip actions
        th.classList.add('sortable');
        const original = th.innerHTML.trim();
        th.innerHTML = '<span class="sort-label">' + original + '</span><span class="sort-indicator" aria-hidden="true"></span>';
        th.style.cursor = 'pointer';
        th.addEventListener('click', () => toggleSort(idx, th));
      });

      function toggleSort(idx, th) {
        const dir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
        // clear others
        thead.querySelectorAll('th.sortable').forEach(h => { if (h !== th) { h.dataset.sortDir = ''; const ind = h.querySelector('.sort-indicator'); if (ind) ind.textContent = ''; } });
        th.dataset.sortDir = dir;
        const ind = th.querySelector('.sort-indicator'); if (ind) ind.textContent = dir === 'asc' ? '▲' : '▼';

        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a,b) => {
          const va = norm(getCellValue(a, idx));
          const vb = norm(getCellValue(b, idx));
          if (va < vb) return dir === 'asc' ? -1 : 1;
          if (va > vb) return dir === 'asc' ? 1 : -1;
          return 0;
        });

        // rebuild tbody
        tbody.innerHTML = '';
        rows.forEach(r => tbody.appendChild(r));
        renumber();
      }

      function renumber() {
        const rows = tbody.querySelectorAll('tr');
        let i = 1;
        rows.forEach(r => {
          const first = r.querySelector('td');
          if (first) first.textContent = i++;
        });
      }
    } catch (e) {
      console && console.warn && console.warn('Financials table sorting failed', e);
    }
  })();
</script>