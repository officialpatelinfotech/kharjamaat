<?php
// Traditional month calendar grid (weeks as rows, weekdays as columns)
// Defaults: current month, week starts on Sunday. Clicking a weekday header toggles that column.
// Clicking a day cell selects the week row (highlights the entire week).

$now = new DateTime();
$year = (int)$now->format('Y');
$month = (int)$now->format('n');

// Allow optional override via $year and $month variables passed to the view
if (isset($view_year) && is_numeric($view_year)) $year = (int)$view_year;
if (isset($view_month) && is_numeric($view_month)) $month = (int)$view_month;

$firstOfMonth = new DateTime(sprintf('%04d-%02d-01', $year, $month));
$startWeekDay = (int)$firstOfMonth->format('w'); // 0 = Sunday
$daysInMonth = (int)$firstOfMonth->format('t');
// If controller provided hijri_days (mapping of hijri day -> greg_date), build weeks
$weeks = [];
if (isset($hijri_days) && is_array($hijri_days) && count($hijri_days) > 0) {
  // build map greg_date => hijri_day
  $gregMap = [];
  foreach ($hijri_days as $hd) {
    $gregMap[$hd['greg_date']] = (int)$hd['day'];
  }
  // iterate from first greg date to last greg date
  $startDate = new DateTime($hijri_days[0]['greg_date']);
  $endDate = new DateTime(end($hijri_days)['greg_date']);
  $period = new DatePeriod($startDate, new DateInterval('P1D'), (clone $endDate)->modify('+1 day'));
  $week = array_fill(0, 7, null);
  foreach ($period as $dt) {
    $w = (int)$dt->format('w'); // 0-6
    $dStr = $dt->format('Y-m-d');
    if (isset($gregMap[$dStr])) {
      $week[$w] = ['greg' => $dStr, 'hijri' => $gregMap[$dStr]];
    } else {
      $week[$w] = null;
    }
    // if saturday (end of week) push and reset
    if ($w === 6) {
      $weeks[] = $week;
      $week = array_fill(0, 7, null);
    }
  }
  // push remaining week if any non-null
  $has = false;
  foreach ($week as $c) {
    if ($c !== null) {
      $has = true;
      break;
    }
  }
  if ($has) $weeks[] = $week;
} else {
  // Build weeks array (each week is array of 7 entries; empty slots are null) using Gregorian month
  $week = array_fill(0, 7, null);
  $day = 1;
  // Fill leading blanks
  for ($i = 0; $i < $startWeekDay; $i++) $week[$i] = null;
  $weekday = $startWeekDay;
  while ($day <= $daysInMonth) {
    $week[$weekday] = $day;
    $day++;
    $weekday++;
    if ($weekday === 7) {
      $weeks[] = $week;
      $week = array_fill(0, 7, null);
      $weekday = 0;
    }
  }
  // push trailing week
  if ($weekday !== 0) $weeks[] = $week;
}

// Weekday names (Sunday-first)
$weekdayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
?>
<style>
  .slots-calendar {
    width: 100%;
    overflow: hidden;
  }

  .cal-table-wrapper {
    overflow-x: auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    max-height: calc(100vh - 260px);
    border-radius: 4px;
  }

  .cal-table-wrapper table {
    border-collapse: collapse;
    width: 100%;
    min-width: 720px;
    table-layout: fixed;
  }

  .slots-calendar th,
  .slots-calendar td {
    border: 1px solid #e5e7eb;
    padding: 10px;
    vertical-align: top;
    height: 100px;
  }

  .slots-calendar thead th {
    background: #f8fafc;
    cursor: pointer;
    position: sticky;
    top: 0;
    z-index: 3;
  }

  .slots-calendar td.empty {
    background: #fafafa;
    color: #9ca3af;
  }

  .slots-calendar td.day-cell {
    cursor: pointer;
  }

  .slots-calendar td.col-selected {
    background: #fff7ed;
  }

  .slots-calendar tr.week-selected td {
    background: #ecfeff;
  }

  .slots-calendar th.week-head,
  .slots-calendar td.week-select {
    width: 40px;
    text-align: center;
    vertical-align: middle;
    background: #f8fafc;
  }

  .slots-calendar td.week-select .btn-week {
    font-size: 14px;
    padding: 4px 6px;
    border-radius: 3px;
  }

  .slots-calendar .day-number {
    font-weight: 700;
    margin-bottom: 6px;
    display: block;
    font-size: 18px; /* larger date numbers */
  }
  .slots-calendar .greg-date {
    display: block;
    font-weight: 400;
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
  }

  .slots-calendar .today {
    background: #e0f2fe;
    border-radius: 4px;
    padding: 2px 6px;
  }

  .slots-calendar td.status-available {
    background: #ecfdf5;
  }

  .slots-calendar td.status-booked {
    background: #fff1f2;
  }

  .slots-calendar td.status-none {
    background: #f3f4f6;
    color: #9ca3af;
  }

  .slots-calendar .no-slot {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  .slots-calendar .slot-available {
    font-size: 12px;
    color: #065f46;
    margin-top: 6px;
  }

  .slots-calendar .slot-booked {
    font-size: 12px;
    color: #9f1239;
    margin-top: 2px;
  }

  .slots-calendar .slot-fmb {
    font-size: 12px;
    color: #0f172a;
    background: #fff7ed;
    padding: 2px 6px;
    display: inline-block;
    margin-top: 6px;
    border-radius: 4px;
  }

  /* Responsive tweaks for mobile */
  @media (max-width: 768px) {
    .cal-table-wrapper table {
      min-width: 720px;
    }

    .slots-calendar th,
    .slots-calendar td {
      padding: 6px;
      height: 90px;
    }

    .slots-calendar .day-number {
      font-size: 16px;
    }

    .slots-calendar .day-content {
      font-size: 12px;
    }

    .slots-calendar .slot-available,
    .slots-calendar .slot-booked,
    .slots-calendar .slot-fmb {
      font-size: 11px;
      margin-top: 4px;
    }

    .slots-calendar th.week-head,
    .slots-calendar td.week-select {
      width: 32px;
    }
  }

  @media (max-width: 480px) {
    .cal-table-wrapper table {
      min-width: 680px;
    }

    .slots-calendar th,
    .slots-calendar td {
      padding: 5px;
      height: 80px;
    }

    .slots-calendar .day-number {
      font-size: 14px;
    }

    .slots-calendar .day-content {
      font-size: 11px;
    }

    .slots-calendar .slot-available,
    .slots-calendar .slot-booked,
    .slots-calendar .slot-fmb {
      font-size: 10px;
    }

    .slots-calendar thead th {
      top: 0;
    }
  }
</style>

<div class="container pt-5 mt-5 slots-calendar">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("amilsaheb"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h4 class="heading text-center mb-4">Appointment Calendar</h4>
  <div style="display:flex;justify-content:center;align-items:center;margin-bottom:8px;">
    <div style="display:flex; align-items:center; gap:8px;">
      <button id="cal-prev" class="btn btn-sm btn-outline-secondary">&larr;</button>
      <div style="min-width:260px;display:flex;align-items:center;gap:8px;">
        <select id="cal-hijri-month" class="form-control form-control-sm" style="min-width:160px;padding:4px 8px;">
          <?php if (!empty($hijri_months)) foreach ($hijri_months as $hm): ?>
            <option value="<?php echo $hm['id']; ?>" <?php if ($hm['id'] == ($hijri_month ?? $hijri_month)) echo ' selected'; ?>><?php echo htmlspecialchars($hm['name']); ?></option>
          <?php endforeach; ?>
        </select>
        <select id="cal-hijri-year" class="form-control form-control-sm" style="width:90px;padding:4px 8px;">
          <?php if (!empty($hijri_years)) foreach ($hijri_years as $hy): ?>
            <option value="<?php echo $hy; ?>" <?php if ($hy == ($hijri_year ?? $hijri_year)) echo ' selected'; ?>><?php echo $hy; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button id="cal-next" class="btn btn-sm btn-outline-secondary">&rarr;</button>
    </div>
  </div>
  <div id="cal-root" data-year="<?php echo isset($view_year) ? $view_year : $year; ?>" data-month="<?php echo isset($view_month) ? $view_month : $month; ?>">
    <div class="cal-table-wrapper">
    <table>
      <thead>
        <tr>
          <th class="weekday-head">Week</th>
          <?php foreach ($weekdayNames as $wi => $wname): ?>
            <th class="weekday-head" data-weekday-index="<?php echo $wi; ?>"><?php echo $wname; ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($weeks as $weekIndex => $week): ?>
          <tr class="week-row" data-week-index="<?php echo $weekIndex; ?>">
            <?php
            // left column: week selector cell
            echo '<td class="week-select" data-week-index="' . $weekIndex . '" title="Select Week">';
            echo '<button type="button" class="btn-week" style="border:none;background:transparent;cursor:pointer;">' . ($weekIndex + 1) . '</button>';
            echo '</td>';
            ?>
            <?php for ($wi = 0; $wi < 7; $wi++):
              $d = $week[$wi];
              if ($d === null) {
                echo '<td class="empty" data-week-index="' . $weekIndex . '" data-weekday="' . $wi . '"></td>';
              } else {
                // support hijri-driven cells (array with greg/hijri) or legacy integer day
                $displayDay = '';
                if (is_array($d) && isset($d['greg'])) {
                  $dateStr = $d['greg'];
                  $displayDay = isset($d['hijri']) ? $d['hijri'] : '';
                } else {
                  $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                  $displayDay = $d;
                }
                $isToday = ($dateStr === (new DateTime())->format('Y-m-d'));
                // slot status can be provided as $slot_statuses["YYYY-MM-DD"] = 'available'|'booked'|'none' or an array with counts
                // default to 'none' (no slots) when no entry provided
                $statusClass = 'status-none';
                $statusLabel = 'No slots';
                $s = null;
                if (isset($slot_statuses) && is_array($slot_statuses) && array_key_exists($dateStr, $slot_statuses)) {
                  $s = $slot_statuses[$dateStr];
                  if (is_array($s)) {
                    $total = isset($s['total']) ? (int)$s['total'] : 0;
                    $booked = isset($s['booked']) ? (int)$s['booked'] : 0;
                    $available = isset($s['available']) ? (int)$s['available'] : max(0, $total - $booked);
                    if ($available <= 0 && $booked > 0) {
                      $statusClass = 'status-booked';
                      $statusLabel = $booked . ' booked';
                    } elseif ($available > 0) {
                      $statusClass = 'status-available';
                      $statusLabel = $available . ' available';
                    } else {
                      $statusClass = 'status-none';
                      $statusLabel = 'No slots';
                    }
                  } else {
                    if ($s === 'booked') {
                      $statusClass = 'status-booked';
                      $statusLabel = 'Booked';
                    } elseif ($s === 'available') {
                      $statusClass = 'status-available';
                      $statusLabel = 'Available';
                    } else {
                      $statusClass = 'status-none';
                      $statusLabel = ucfirst($s);
                    }
                  }
                }
                // prepare html for available/booked lines
                $slotHtml = '';
                if (is_array($s)) {
                  $total = isset($s['total']) ? (int)$s['total'] : 0;
                  $booked = isset($s['booked']) ? (int)$s['booked'] : 0;
                  $available = isset($s['available']) ? (int)$s['available'] : max(0, $total - $booked);
                  if ($available > 0) $slotHtml .= '<div class="slot-available">' . $available . ' available</div>';
                  $slotHtml .= '<div class="slot-booked">' . $booked . ' booked</div>';
                  if (isset($s['fmb_day']) && $s['fmb_day'] !== '') {
                    $slotHtml .= '<div class="slot-fmb">FMB: ' . htmlspecialchars($s['fmb_day']) . '</div>';
                  }
                } else {
                  // fallback: single label
                  $slotHtml = '<div class="no-slot">' . htmlspecialchars($statusLabel) . '</div>';
                }
                $gregDisplay = DateTime::createFromFormat('Y-m-d', $dateStr)->format('d-m-Y');
                echo '<td class="day-cell ' . $statusClass . '" data-week-index="' . $weekIndex . '" data-weekday="' . $wi . '" data-date="' . $dateStr . '">';
                echo '<span class="day-number' . ($isToday ? ' today' : '') . '">' . htmlspecialchars($displayDay) . '<br><span class="greg-date">' . $gregDisplay . '</span></span>';
                echo '<div class="day-content" style="font-size:13px;color:#374151">' . $slotHtml . '</div>';
                echo '</td>';
              }
            endfor; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>

<div id="week-action" class="px-3 px-md-0" style="display:none; text-align: center; margin:12px 0;">
  <a id="week-action-link" class="btn btn-sm btn-primary" href="#">Manage Selected Week</a>
  <span id="week-action-range" style="margin-left:8px;color:#374151;font-size:13px;"></span>
</div>

<script>
  (function() {
    var calRoot = document.getElementById('cal-root');
    // inject server-provided slot statuses (date => status or counts)
    var slotStatuses = <?php echo isset($slot_statuses) ? json_encode($slot_statuses) : '{}'; ?>;
    var manageUrlBase = '<?php echo base_url('amilsaheb/manage_slots'); ?>';

    function buildWeeks(year, month) {
      // month: 1-12
      var first = new Date(year, month - 1, 1);
      var startWeekDay = first.getDay(); // 0=Sunday
      var daysInMonth = new Date(year, month, 0).getDate();
      var weeks = [];
      var week = new Array(7).fill(null);
      var weekday = startWeekDay;
      for (var i = 0; i < startWeekDay; i++) week[i] = null;
      var day = 1;
      while (day <= daysInMonth) {
        week[weekday] = day;
        day++;
        weekday++;
        if (weekday === 7) {
          weeks.push(week);
          week = new Array(7).fill(null);
          weekday = 0;
        }
      }
      if (weekday !== 0) weeks.push(week);
      return weeks;
    }

    function renderCalendar(year, month) {
      var weekdayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
      var weeks = buildWeeks(year, month);
      var html = [];
      html.push('<table>');
      html.push('<thead><tr>');
      html.push('<th class="weekday-head">Week</th>');
      for (var wi = 0; wi < 7; wi++) html.push('<th class="weekday-head" data-weekday-index="' + wi + '">' + weekdayNames[wi] + '</th>');
      html.push('</tr></thead>');
      html.push('<tbody>');
      for (var w = 0; w < weeks.length; w++) {
        html.push('<tr class="week-row" data-week-index="' + w + '">');
        html.push('<td class="week-select" data-week-index="' + w + '" title="Select Week"><button type="button" class="btn-week" style="border:none;background:transparent;cursor:pointer;">' + (w + 1) + '</button></td>');
        for (var wi = 0; wi < 7; wi++) {
          var d = weeks[w][wi];
          if (d === null) {
            html.push('<td class="empty" data-week-index="' + w + '" data-weekday="' + wi + '"></td>');
          } else {
            var mm = (month < 10 ? '0' + month : month);
            var dd = (d < 10 ? '0' + d : d);
            var dateStr = year + '-' + mm + '-' + dd;
            var isToday = (dateStr === (new Date()).toISOString().slice(0, 10));
            // determine status from slotStatuses; default to 'No slots' when missing
            var st = slotStatuses && slotStatuses[dateStr] ? slotStatuses[dateStr] : null;
            var statusClass = 'status-none';
            var statusLabel = 'No slots';
            var statusHtml = '';
            if (st) {
              if (typeof st === 'object') {
                var total = parseInt(st.total || 0, 10);
                var booked = parseInt(st.booked || 0, 10);
                var available = (typeof st.available !== 'undefined') ? parseInt(st.available, 10) : Math.max(0, total - booked);
                if (available <= 0 && booked > 0) {
                  statusClass = 'status-booked';
                } else if (available > 0) {
                  statusClass = 'status-available';
                } else {
                  statusClass = 'status-none';
                }
                if (available > 0) statusHtml += '<div class="slot-available">' + available + ' available</div>';
                statusHtml += '<div class="slot-booked">' + booked + ' booked</div>';
                if (st.fmb_day) statusHtml += '<div class="slot-fmb">FMB: ' + (st.fmb_day) + '</div>';
              } else {
                if (st === 'booked') {
                  statusClass = 'status-booked';
                  statusHtml = '<div class="slot-booked">Booked</div>';
                } else if (st === 'available') {
                  statusClass = 'status-available';
                  statusHtml = '<div class="slot-available">Available</div>';
                } else {
                  statusClass = 'status-none';
                  statusHtml = '<div class="slot-available">' + st + '</div>';
                }
              }
            } else {
              statusHtml = '<div class="slot-booked">No slots</div>';
            }
            var dd = (d < 10 ? '0' + d : d);
            var mmStr = (month < 10 ? '0' + month : month);
            var greg = dd + '-' + mmStr + '-' + year;
            html.push('<td class="day-cell ' + statusClass + '" data-week-index="' + w + '" data-weekday="' + wi + '" data-date="' + dateStr + '">');
            html.push('<span class="day-number' + (isToday ? ' today' : '') + '">' + d + '<br><span class="greg-date">' + greg + '</span></span>');
            html.push('<div class="day-content" style="font-size:13px;color:#374151">' + (statusHtml ? statusHtml : '&nbsp;') + '</div>');
            html.push('</td>');
          }
        }
        html.push('</tr>');
      }
      html.push('</tbody></table>');
      // wrap in scrollable wrapper to match server-rendered structure
      calRoot.innerHTML = '<div class="cal-table-wrapper">' + html.join('') + '</div>';
      calRoot.setAttribute('data-year', year);
      calRoot.setAttribute('data-month', month);
      attachHandlers();
    }

    function attachHandlers() {
      // header toggles
      document.querySelectorAll('#cal-root .weekday-head').forEach(function(th) {
        th.addEventListener('click', function() {
          var wi = th.getAttribute('data-weekday-index');
          var cells = document.querySelectorAll('#cal-root td[data-weekday="' + wi + '"]');
          var any = Array.from(cells).some(function(c) {
            return c.classList.contains('col-selected');
          });
          cells.forEach(function(c) {
            if (any) c.classList.remove('col-selected');
            else c.classList.add('col-selected');
          });
        });
      });

      // day click
      document.querySelectorAll('#cal-root td.day-cell').forEach(function(td) {
        td.addEventListener('click', function() {
          var week = td.getAttribute('data-week-index');
          document.querySelectorAll('#cal-root tr.week-selected').forEach(function(r) {
            r.classList.remove('week-selected');
          });
          var tr = document.querySelector('#cal-root tr.week-row[data-week-index="' + week + '"]');
          if (tr) tr.classList.add('week-selected');
          var date = td.getAttribute('data-date');
          if (date) {
            window.location.href = manageUrlBase + '?date=' + encodeURIComponent(date);
          }
        });
      });

      // week-select click (toggle selection and show week action)
      document.querySelectorAll('#cal-root td.week-select').forEach(function(td) {
        td.addEventListener('click', function(e) {
          var week = td.getAttribute('data-week-index');
          var tr = document.querySelector('#cal-root tr.week-row[data-week-index="' + week + '"]');
          var already = tr && tr.classList.contains('week-selected');
          document.querySelectorAll('#cal-root tr.week-selected').forEach(function(r) {
            r.classList.remove('week-selected');
          });
          if (!already && tr) {
            tr.classList.add('week-selected');
            updateWeekAction(week);
          } else {
            hideWeekAction();
          }
        });
      });
    }

    function updateWeekAction(weekIndex) {
      var tr = document.querySelector('#cal-root tr.week-row[data-week-index="' + weekIndex + '"]');
      if (!tr) {
        hideWeekAction();
        return;
      }
      var dates = Array.from(tr.querySelectorAll('td.day-cell')).map(function(td) {
        return td.getAttribute('data-date');
      }).filter(Boolean);
      if (dates.length === 0) {
        hideWeekAction();
        return;
      }
      dates.sort();
      var start = dates[0];
      var end = dates[dates.length - 1];
      var link = document.getElementById('week-action-link');
      link.href = manageUrlBase + '?start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end);
      // display in dd-mm-yyyy
      function fmt(d) {
        var p = d.split('-');
        return p[2] + '-' + p[1] + '-' + p[0];
      }
      document.getElementById('week-action-range').textContent = fmt(start) + ' — ' + fmt(end);
      document.getElementById('week-action').style.display = 'block';
    }

    function hideWeekAction() {
      var wa = document.getElementById('week-action');
      if (!wa) return;
      wa.style.display = 'none';
      var link = document.getElementById('week-action-link');
      if (link) link.href = '#';
      var span = document.getElementById('week-action-range');
      if (span) span.textContent = '';
    }

    // init: read initial year/month from root attributes
    var initYear = parseInt(calRoot.getAttribute('data-year'), 10) || (new Date()).getFullYear();
    var initMonth = parseInt(calRoot.getAttribute('data-month'), 10) || ((new Date()).getMonth() + 1);

    // wire controls (Hijri navigation): reload page with selected hijri_month & hijri_year
    function navigateToHijri(month, year) {
      var base = window.location.href.split('?')[0];
      var q = '?hijri_month=' + encodeURIComponent(month) + '&hijri_year=' + encodeURIComponent(year);
      window.location.href = base + q;
    }

    document.getElementById('cal-prev').addEventListener('click', function() {
      var monthSelect = document.getElementById('cal-hijri-month');
      var yearSelect = document.getElementById('cal-hijri-year');
      var idx = monthSelect.selectedIndex - 1;
      var year = parseInt(yearSelect.value, 10);
      if (idx < 0) {
        year = year - 1;
        yearSelect.value = year;
        idx = monthSelect.options.length - 1;
      }
      var m = monthSelect.options[idx].value;
      navigateToHijri(m, year);
    });
    document.getElementById('cal-next').addEventListener('click', function() {
      var monthSelect = document.getElementById('cal-hijri-month');
      var yearSelect = document.getElementById('cal-hijri-year');
      var idx = monthSelect.selectedIndex + 1;
      var year = parseInt(yearSelect.value, 10);
      if (idx >= monthSelect.options.length) {
        year = year + 1;
        yearSelect.value = year;
        idx = 0;
      }
      var m = monthSelect.options[idx].value;
      navigateToHijri(m, year);
    });
    document.getElementById('cal-hijri-month').addEventListener('change', function() {
      var m = this.value;
      var y = document.getElementById('cal-hijri-year').value;
      navigateToHijri(m, y);
    });
    document.getElementById('cal-hijri-year').addEventListener('change', function() {
      var y = this.value;
      var m = document.getElementById('cal-hijri-month').value;
      navigateToHijri(m, y);
    });

    // initial render uses server table as-is, but ensure handlers attached
    attachHandlers();

  })();
</script>