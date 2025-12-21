<?php
$date = isset($date) ? $date : date('Y-m-d');
$start_date = isset($start_date) ? $start_date : '';
$end_date = isset($end_date) ? $end_date : '';

// Support `start`/`end` query params (e.g., from the dashboard link). Prefer explicit
// variables passed from controller but fall back to GET if present.
// Accept either `start_date`/`end_date` (preferred) or legacy `start`/`end` query params.
if (empty($start_date)) {
  if (!empty($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
  } elseif (!empty($_GET['start'])) {
    $start_date = $_GET['start'];
  }
  if (!empty($start_date)) {
    $date = '';
  }
}
if (empty($end_date)) {
  if (!empty($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
  } elseif (!empty($_GET['end'])) {
    $end_date = $_GET['end'];
  }
  if (!empty($end_date)) {
    $date = '';
  }
}

// Controller now provides defaults; no view-side defaulting
$breakdown = isset($breakdown) ? $breakdown : [];
$totals = isset($totals) ? $totals : ['families' => 0, 'signed_up' => 0, 'not_signed' => 0, 'percent' => 0];
?>
<style>
  .sticky-header thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fff;
  }

  .progress-xxs {
    height: 6px;
  }

  .card.hoverable:hover {
    transform: translateY(-1px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08);
    transition: all .15s ease;
  }

  /* vertical scroll within table container */
  .table-scroll-y {
    max-height: 65vh;
    overflow-y: auto;
  }

  /* subtle highlight for auto-scrolled row */
  .auto-focus-row {
    animation: rowPulse 2.5s ease 1;
  }

  @keyframes rowPulse {
    0% {
      background-color: #fffceb;
    }

    40% {
      background-color: #fff5c2;
    }

    100% {
      background-color: transparent;
    }
  }
</style>
<div class="container margintopcontainer pt-4">
  <div class="d-flex align-items-center mb-3">
    <a href="<?php echo $active_controller; ?>" class="btn btn-outline-secondary me-2"><i
        class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="text-center heading mb-4">Thaali Signups Breakdown</h4>

  <form method="get" action="<?php echo site_url('common/thaali_signups_breakdown'); ?>"
    class="row g-2 align-items-end mb-3" id="tsbForm">
    <?php if (!empty($from)): ?>
      <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
    <?php endif; ?>
    <div class="col-auto">
      <label for="date" class="form-label mb-0">Date</label>
      <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>"
        oninput="document.getElementById('start_date').value='';document.getElementById('end_date').value='';">
    </div>
    <div class="col-auto">
      <label for="start_date" class="form-label mb-0">Start date</label>
      <input type="date" class="form-control" id="start_date" name="start_date"
        value="<?php echo htmlspecialchars($start_date); ?>" oninput="document.getElementById('date').value='';">
    </div>
    <div class="col-auto">
      <label for="end_date" class="form-label mb-0">End date</label>
      <input type="date" class="form-control" id="end_date" name="end_date"
        value="<?php echo htmlspecialchars($end_date); ?>" oninput="document.getElementById('date').value='';">
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Apply</button>
    </div>
    <div class="col-auto">
      <?php
      $clearHref = site_url('common/thaali_signups_breakdown');
      if (!empty($from)) {
        $clearHref .= '?from=' . urlencode($from);
      }
      ?>
      <a href="<?= $clearHref ?>" class="btn btn-outline-secondary">Clear</a>
    </div>
  </form>

  <?php
  // Build a centered summary of the active filter (date or range)
  $hasRange = (!empty($start_date) && !empty($end_date));
  $hasSingle = (!$hasRange && !empty($date));
  $summaryMain = '';
  $summarySub = '';
  if ($hasRange) {
    $sf = date('D, d M Y', strtotime($start_date));
    $ef = date('D, d M Y', strtotime($end_date));
    $summaryMain = $sf . ' — ' . $ef;
    // Try to show Hijri equivalents for start/end if available
    $hStart = isset($hijri_by_date[$start_date]) ? $hijri_by_date[$start_date] : null;
    $hEnd = isset($hijri_by_date[$end_date]) ? $hijri_by_date[$end_date] : null;
    if ($hStart && $hEnd) {
      $hs = $hStart['hijri_day'] . ' ' . ($hStart['hijri_month_name'] ?? $hStart['hijri_month']) . ' ' . $hStart['hijri_year'];
      $he = $hEnd['hijri_day'] . ' ' . ($hEnd['hijri_month_name'] ?? $hEnd['hijri_month']) . ' ' . $hEnd['hijri_year'];
      $summarySub = $hs . ' — ' . $he;
    }
  } elseif ($hasSingle) {
    $summaryMain = date('D, d M Y', strtotime($date));
    $hSingle = isset($hijri_by_date[$date]) ? $hijri_by_date[$date] : null;
    if ($hSingle) {
      $summarySub = $hSingle['hijri_day'] . ' ' . ($hSingle['hijri_month_name'] ?? $hSingle['hijri_month']) . ' ' . $hSingle['hijri_year'];
    }
  }
  ?>
  <?php
  // Hijri month switcher for this breakdown page (preserve filters where possible)
  $this->load->model('HijriCalendar');
  // Prefer explicit GET hijri params if provided
  $sel_hijri_year = isset($_GET['hijri_year']) ? $_GET['hijri_year'] : null;
  $sel_hijri_month = isset($_GET['hijri_month']) ? (int) $_GET['hijri_month'] : null;
  if (!$sel_hijri_year || !$sel_hijri_month) {
    // derive from start_date, end_date or single date if available
    $ref_date = '';
    if (!empty($start_date))
      $ref_date = $start_date;
    elseif (!empty($date))
      $ref_date = $date;
    else
      $ref_date = date('Y-m-d');
    $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($ref_date);
    if ($parts && is_array($parts)) {
      $sel_hijri_year = $parts['hijri_year'];
      $sel_hijri_month = (int) $parts['hijri_month'];
    }
  }
  $years = $this->HijriCalendar->get_distinct_hijri_years();
  $monthList = [];
  foreach ($years as $y) {
    $ms = $this->HijriCalendar->get_hijri_months_for_year($y);
    if (!is_array($ms))
      continue;
    foreach ($ms as $m) {
      $monthList[] = ['year' => $y, 'id' => (int) $m['id'], 'name' => (isset($m['name']) ? $m['name'] : '')];
    }
  }
  $currentIndex = null;
  foreach ($monthList as $i => $mn) {
    if ($mn['year'] == $sel_hijri_year && (int) $mn['id'] === (int) $sel_hijri_month) {
      $currentIndex = $i;
      break;
    }
  }
  $prev = null;
  $next = null;
  if ($currentIndex !== null) {
    if ($currentIndex > 0)
      $prev = $monthList[$currentIndex - 1];
    if ($currentIndex < count($monthList) - 1)
      $next = $monthList[$currentIndex + 1];
  }
  // Build base url preserving filters
  $basePath = site_url('common/thaali_signups_breakdown');
  $preserve = [];
  if (!empty($from))
    $preserve['from'] = $from;
  if (!empty($start_date))
    $preserve['start_date'] = $start_date;
  if (!empty($end_date))
    $preserve['end_date'] = $end_date;
  if (!empty($date))
    $preserve['date'] = $date;

  // ✅ FIX: preserve sector during hijri navigation
  if (!empty($_GET['sector']))
    $preserve['sector'] = $_GET['sector'];

  ?>
  <div class="d-flex justify-content-center align-items-center mb-3 hijri-switcher" aria-label="Hijri month navigation">
    <a href="<?= $prev ? ($basePath . '?' . http_build_query(array_merge($preserve, ['hijri_year' => $prev['year'], 'hijri_month' => $prev['id']]))) : '#' ?>"
      class="hijri-nav-btn <?= $prev ? '' : 'disabled' ?> me-3" aria-label="Previous Hijri month">
      <div class="chev-pill chev-box"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
    </a>

    <div class="hijri-title text-center" id="tsb-hijri-title" role="heading" aria-level="3">
      <?= htmlspecialchars(($sel_hijri_month ? (isset($monthList[$currentIndex]['name']) ? $monthList[$currentIndex]['name'] : '') : '') . ' ' . ($sel_hijri_year ? $sel_hijri_year : '')); ?>
    </div>

    <a href="<?= $next ? ($basePath . '?' . http_build_query(array_merge($preserve, ['hijri_year' => $next['year'], 'hijri_month' => $next['id']]))) : '#' ?>"
      class="hijri-nav-btn <?= $next ? '' : 'disabled' ?> ms-3" aria-label="Next Hijri month">
      <div class="chev-pill chev-box"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
    </a>
  </div>

  <style>
    .hijri-switcher .chev-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 8px 12px;
      border-radius: 999px;
      border: 1px solid #e6eef0;
      background: #fff;
      color: #0b5563;
      cursor: pointer;
      transition: all .12s ease;
      box-shadow: none;
      min-width: 44px;
      min-height: 44px;
    }

    .hijri-switcher .chev-pill i {
      font-size: 1rem;
    }

    .hijri-switcher .chev-pill:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .hijri-switcher .hijri-title {
      color: #0ea5a4;
      font-weight: 700;
      font-size: 1.05rem;
      padding: 0 8px;
    }

    .hijri-switcher .hijri-nav-btn.disabled .chev-pill {
      opacity: .45;
      pointer-events: none;
    }

    @media (max-width:576px) {
      .hijri-switcher .chev-pill {
        padding: 6px 8px;
        min-width: 38px;
        min-height: 38px;
      }

      .hijri-switcher .hijri-title {
        font-size: 0.95rem;
      }
    }
  </style>

  <script>
    (function () {
      const dateEl = document.getElementById('date');
      const startEl = document.getElementById('start_date');
      const endEl = document.getElementById('end_date');
      if (!startEl || !endEl) return;

      function syncMinMax() {
        if (startEl.value) {
          endEl.min = startEl.value;
        } else {
          endEl.removeAttribute('min');
        }
        if (endEl.value) {
          startEl.max = endEl.value;
        } else {
          startEl.removeAttribute('max');
        }
      }

      startEl.addEventListener('change', syncMinMax);
      endEl.addEventListener('change', syncMinMax);
      // Also when date is typed, it clears range (handled inline); ensure constraints reset
      dateEl && dateEl.addEventListener('input', () => { startEl.value = ''; endEl.value = ''; syncMinMax(); });
      syncMinMax();
    })();
  </script>

  <?php
  // Build a unified rows list: each entry contains date, sector, signed, total
  // 🔑 Decide view mode explicitly (VERY IMPORTANT)
  $isSubSectorMode = !empty($_GET['sector']);

  $rows = [];
  if (!empty($start_date) || !empty($end_date)) {
    if (!empty($daily_breakdowns)) {
      foreach ($daily_breakdowns as $day) {
        $d = $day['date'] ?? '';
        $dBreakdown = isset($day['breakdown']) ? $day['breakdown'] : [];
        // foreach ($dBreakdown as $r) {
        //   $sec = isset($r['sector']) ? trim($r['sector']) : '';
        //   if ($sec === '') $sec = 'Unassigned';
        //   $signed = (int)($r['signed_up'] ?? 0);
        //   $total = (int)($r['total_families'] ?? 0);
        //   $rows[] = ['date' => $d, 'sector' => $sec, 'signed' => $signed, 'total' => $total];
        // }
        foreach ($dBreakdown as $r) {

          // ✅ CORRECT MODE HANDLING
          if ($isSubSectorMode) {
            // Sector incharge → show A / B / C
            $sec = isset($r['sub_sector']) ? trim($r['sub_sector']) : '';
          } else {
            // Admin / Jamaat → show Burhani / Saifee / etc
            $sec = isset($r['sector']) ? trim($r['sector']) : '';
          }

          if ($sec === '')
            $sec = 'Unassigned';

          $signed = (int) ($r['signed_up'] ?? 0);
          $total = (int) ($r['total_families'] ?? 0);

          $rows[] = [
            'date' => $d,
            'sector' => $sec,
            'signed' => $signed,
            'total' => $total
          ];
        }


      }
    }
  } else {
    // Single date: use controller-provided $breakdown which includes totals
    $d = $date;
    foreach ($breakdown as $r) {

      // ✅ CORRECT MODE HANDLING
      if ($isSubSectorMode) {
        $sec = isset($r['sub_sector']) ? trim($r['sub_sector']) : '';
      } else {
        $sec = isset($r['sector']) ? trim($r['sector']) : '';
      }

      if ($sec === '')
        $sec = 'Unassigned';

      $signed = (int) ($r['signed_up'] ?? 0);
      $total = (int) ($r['total_families'] ?? 0);

      $rows[] = [
        'date' => $d,
        'sector' => $sec,
        'signed' => $signed,
        'total' => $total
      ];
    }

  }

  // Sort rows by date asc then sector asc
  usort($rows, function ($a, $b) {
    $da = strtotime($a['date'] ?? '');
    $db = strtotime($b['date'] ?? '');
    if ($da === $db)
      return strcmp($a['sector'] ?? '', $b['sector'] ?? '');
    return $da <=> $db;
  });
  ?>

  <?php
  // Pivot rows to one row per date, columns per sector
  $dateSectorCounts = [];
  $sectors = [];
  foreach ($rows as $r) {
    $d = $r['date'] ?? '';
    $s = $r['sector'] ?? '';
    $v_signed = (int) ($r['signed'] ?? 0);
    $v_total = (int) ($r['total'] ?? 0);
    if ($d === '')
      continue;
    if (!isset($dateSectorCounts[$d]))
      $dateSectorCounts[$d] = [];
    if (!isset($dateSectorCounts[$d][$s]))
      $dateSectorCounts[$d][$s] = ['signed' => 0, 'total' => 0];
    $dateSectorCounts[$d][$s]['signed'] += $v_signed;
    // totals may come from different sub-sectors; sum them so sector shows combined total
    $dateSectorCounts[$d][$s]['total'] += $v_total;
    $sectors[$s] = true;
  }
  $sectors = array_keys($sectors);
  sort($sectors, SORT_NATURAL | SORT_FLAG_CASE);
  $dates = array_keys($dateSectorCounts);
  usort($dates, function ($a, $b) {
    return strtotime($a) <=> strtotime($b);
  });
  ?>
  <div class="card shadow-sm" id="thaali-breakdown-block">
    <div class="table-responsive table-scroll-y">
      <table class="table table-striped table-hover sticky-header mb-0">
        <thead>
          <tr>
            <th style="min-width: 220px;">Date (Hijri)</th>
            <?php if (!empty($sectors)):
              foreach ($sectors as $sec): ?>
                <th class="text-end"><?= htmlspecialchars($sec) ?></th>
              <?php endforeach; endif; ?>
            <th class="text-end">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($dates)): ?>
            <tr>
              <td colspan="<?= max(2, count($sectors) + 2) ?>" class="text-center text-muted py-4">No data available.</td>
            </tr>
          <?php else:
            foreach ($dates as $d): ?>
              <?php
              $rowCounts = $dateSectorCounts[$d];
              $sum_signed = 0;
              $sum_total = 0;
              $hijriParts = isset($hijri_by_date[$d]) ? $hijri_by_date[$d] : null;
              $hijriDisp = '';
              if ($hijriParts) {
                $hijriDisp = $hijriParts['hijri_day'] . ' ' . ($hijriParts['hijri_month_name'] ?? $hijriParts['hijri_month']) . ' ' . $hijriParts['hijri_year'];
              }
              ?>
              <tr class="table-row-click" data-date="<?= htmlspecialchars($d) ?>" style="cursor: pointer;">
                <td>
                  <?= htmlspecialchars(date('D, d M Y', strtotime($d))) ?>
                  <?php if (!empty($hijriDisp)): ?>
                    <div class="small text-muted">(<?= htmlspecialchars($hijriDisp) ?>)</div>
                  <?php endif; ?>
                </td>
                <?php foreach ($sectors as $sec):
                  $cell = isset($rowCounts[$sec]) ? $rowCounts[$sec] : ['signed' => 0, 'total' => 0];
                  $signed = (int) ($cell['signed'] ?? 0);
                  $total = (int) ($cell['total'] ?? 0);
                  $not_signed = max($total - $signed, 0);
                  $sum_signed += $signed;
                  $sum_total += $total;
                  ?>
                  <td class="text-end fw-semibold">
                    <span class="text-success"><?= number_format($signed) ?></span> -
                    <span class="text-danger"><?= number_format($not_signed) ?></span>
                  </td>
                <?php endforeach; ?>
                <td class="text-end fw-bold"><?php
                if ($sum_total > 0) {
                  $sum_not = max($sum_total - $sum_signed, 0);
                  echo '<span class="text-success">' . number_format($sum_signed) . '</span>' . ' - ' . '<span class="text-danger">' . number_format($sum_not) . '</span>';
                } else {
                  echo '-';
                }
                ?></td>
              </tr>
            <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
  // Hijri month AJAX navigation: fetch fragment and replace table + title
  (function () {
    function buildAjaxUrl(href) {
      try {
        var u = new URL(href, window.location.origin);
        u.searchParams.set('ajax', '1');
        return u.toString();
      } catch (e) {
        // Fallback: append param safely
        return href + (href.indexOf('?') === -1 ? '?' : '&') + 'ajax=1';
      }
    }

    function setSpinner(on) {
      document.querySelectorAll('.hijri-nav-btn .chev-box').forEach(function (b) {
        if (on) {
          b.dataset.orig = b.innerHTML;
          b.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        } else {
          if (b.dataset.orig) { b.innerHTML = b.dataset.orig; delete b.dataset.orig; }
        }
      });
    }

    document.addEventListener('click', function (e) {
      var a = e.target.closest && e.target.closest('.hijri-nav-btn');
      if (!a) return;
      // allow normal behavior for disabled
      if (a.classList.contains('disabled')) { e.preventDefault(); return; }
      var href = a.getAttribute('href') || '#';
      if (href === '#') { e.preventDefault(); return; }
      e.preventDefault();
      var url = buildAjaxUrl(href);
      setSpinner(true);
      fetch(url, { credentials: 'same-origin' }).then(function (r) { return r.text(); }).then(function (text) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(text, 'text/html');
        var newBlock = doc.getElementById('thaali-breakdown-block');
        var newTitle = doc.getElementById('tsb-hijri-title');
        if (newBlock) {
          var cur = document.getElementById('thaali-breakdown-block');
          if (cur) cur.parentNode.replaceChild(newBlock, cur);
        } else {
          // Fallback: try replacing the table wrapper or the table itself
          var newTableWrap = doc.querySelector('.table-responsive.table-scroll-y');
          var curTableWrap = document.querySelector('.table-responsive.table-scroll-y');
          if (newTableWrap && curTableWrap && curTableWrap.parentNode) {
            curTableWrap.parentNode.replaceChild(newTableWrap, curTableWrap);
          } else {
            // final fallback: replace table element
            var newTable = doc.querySelector('table.table');
            var curTable = document.querySelector('table.table');
            if (newTable && curTable && curTable.parentNode) {
              curTable.parentNode.replaceChild(newTable, curTable);
            }
          }
        }
        if (newTitle) {
          var curT = document.getElementById('tsb-hijri-title');
          if (curT) curT.innerHTML = newTitle.innerHTML;
        }
        // update nav buttons: copy dataset/class/href from returned doc
        var navBtns = doc.querySelectorAll('.hijri-nav-btn');
        var curNav = document.querySelectorAll('.hijri-nav-btn');
        if (navBtns && navBtns.length && curNav && curNav.length) {
          for (var i = 0; i < Math.min(navBtns.length, curNav.length); i++) {
            var src = navBtns[i]; var dst = curNav[i];
            dst.className = src.className;
            // copy href and data-* attributes
            dst.setAttribute('href', src.getAttribute('href') || '#');
            Array.prototype.slice.call(src.attributes).forEach(function (attr) {
              if (attr.name.indexOf('data-') === 0) dst.setAttribute(attr.name, attr.value);
            });
            // copy inner HTML (chevron content)
            dst.innerHTML = src.innerHTML;
          }
        }
        // push state with clicked href (clean, without ajax param)
        try {
          var pushUrl = href;
          history.pushState({}, '', pushUrl);
        } catch (e) { }
      }).catch(function (err) {
        console.error('Failed to load month fragment', err);
      }).finally(function () { setSpinner(false); });
    });

    // support back/forward
    window.addEventListener('popstate', function (ev) {
      // simply reload to ensure state matches (safe fallback)
      window.location.reload();
    });
  })();
  (function () {
    const rows = document.querySelectorAll('tr.table-row-click[data-date]');

    if (!rows.length) return;

    const base = '<?= site_url('common/thaali_signup_report') ?>';
    const fromQ = '<?= !empty($from) ? ('?from=' . urlencode($from)) : '' ?>';


    function goto(url, evt) {
      console.log('[TSB] Navigating to:', url);
      if (evt && (evt.metaKey || evt.ctrlKey)) {
        window.open(url, '_blank');
      } else {
        window.location.href = url;
      }
    }

    rows.forEach(tr => {
      const d = tr.getAttribute('data-date');

      if (!d) {
        return;
      }

      const url = base + '/' + encodeURIComponent(d) + fromQ;

      tr.title = 'View details for ' + d;

      tr.addEventListener('click', (e) => {
        if (e.target && e.target.tagName === 'A') {
          return;
        }
        goto(url, e);
      });

      tr.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          goto(url, e);
        }
      });

      tr.tabIndex = 0; // make row focusable for keyboard activation
    });
  })();

  // Auto-scroll to today's date within the table (if present)
  (function () {
    const today = '<?= date('Y-m-d'); ?>';
    const target = document.querySelector('tbody tr.table-row-click[data-date="' + today + '"]');
    if (!target) return;

    const container = target.closest('.table-responsive');
    // Add a gentle highlight to the target row
    target.classList.add('auto-focus-row');
    // Ensure highlight is removed (no class clutter) after a few seconds
    setTimeout(() => target.classList.remove('auto-focus-row'), 3000);

    // Try to scroll the table container so the row is centered
    try {
      if (container && container.scrollHeight > container.clientHeight) {
        const cRect = container.getBoundingClientRect();
        const tRect = target.getBoundingClientRect();
        // Amount the row is from top of container
        const delta = (tRect.top - cRect.top) - (container.clientHeight / 2 - tRect.height / 2);
        container.scrollBy({ top: delta, left: 0, behavior: 'smooth' });
      } else {
        // Fallback: scroll the page
        target.scrollIntoView({ block: 'center', behavior: 'smooth' });
      }
    } catch (e) {
      // Fallback if any error
      target.scrollIntoView({ block: 'center', behavior: 'smooth' });
    }
  })();
</script>