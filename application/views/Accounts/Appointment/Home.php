<?php
$ci = get_instance();
$ci->load->model('HijriCalendar');

// Null-safe fallback — $user_appointments may not be passed on all routes
if (!isset($user_appointments)) $user_appointments = [];

$ist = new DateTimeZone('Asia/Kolkata');
$today_greg  = (new DateTime('now', $ist))->format('Y-m-d');
$today_hijri = $ci->HijriCalendar->get_hijri_parts_by_greg_date($today_greg);

$hijri_month = isset($_GET['hijri_month']) ? intval($_GET['hijri_month']) : (int)($today_hijri['hijri_month'] ?? 0);
$hijri_year  = isset($_GET['hijri_year'])  ? intval($_GET['hijri_year'])  : (int)($today_hijri['hijri_year']  ?? 0);
if (empty($hijri_month) || empty($hijri_year)) { $hijri_month = 1; $hijri_year = 1440; }

function generateCalendar($hijri_month, $hijri_year, $user_appointments = []) {
  $ci = get_instance();
  $ci->load->model('HijriCalendar');
  $hijri_month_padded = str_pad((string)(int)$hijri_month, 2, '0', STR_PAD_LEFT);
  $days = $ci->HijriCalendar->get_hijri_days_for_month_year($hijri_month_padded, (string)(int)$hijri_year);
  $month_row = $ci->HijriCalendar->hijri_month_name((int)$hijri_month);
  $hijri_month_name = $month_row ? $month_row['hijri_month'] : $hijri_month_padded;
  $tz = new DateTimeZone('Asia/Kolkata');
  $today = new DateTime('now', $tz);
  ob_start();
?>
<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <div class="text-center" style="flex:1;">
      <h1 class="page-heading">Appointment Calendar</h1>
      <p class="page-sub">Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
    <div style="width:38px;flex-shrink:0;"></div>
  </div>
  <hr class="section-divider">

  <!-- ── Month nav ── -->
  <div class="month-nav">
    <button type="button" onclick="changeMonth(-1)" class="month-nav-btn" aria-label="Previous month">
      <i class="fa fa-chevron-left"></i>
    </button>
    <div class="text-center" style="flex:1;">
      <span class="month-nav-label"
        id="month-year"
        data-hijri-month="<?php echo (int)$hijri_month; ?>"
        data-hijri-year="<?php echo (int)$hijri_year; ?>">
        <?php echo htmlspecialchars($hijri_month_name . ' ' . $hijri_year, ENT_QUOTES); ?>
      </span>
      <span class="month-nav-sub">Hijri Month</span>
    </div>
    <button type="button" onclick="changeMonth(1)" class="month-nav-btn" aria-label="Next month">
      <i class="fa fa-chevron-right"></i>
    </button>
  </div>

  <!-- ── Calendar table ── -->
  <div class="cal-card">
    <div class="table-responsive">
      <table class="cal-table">
        <thead>
          <tr>
            <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
              <th><?= $d ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          echo '<tr>';
          if (empty($days)) {
            echo '<td colspan="7" style="text-align:center;padding:28px;color:var(--text-3);font-size:.83rem;">Hijri calendar data not available for this month.</td>';
          } else {
            $firstGreg   = new DateTime($days[0]['greg_date']);
            $startWeekday = (int)$firstGreg->format('w');
            for ($i = 0; $i < $startWeekday; $i++) echo '<td class="cal-empty"></td>';

            foreach ($days as $d) {
              $greg    = $d['greg_date'];
              $isToday = ($greg === $today->format('Y-m-d'));
              $isPast  = ($greg < $today->format('Y-m-d'));
              $gregDt  = new DateTime($greg);
              $hijriDay = ltrim((string)($d['day'] ?? ''), '0');
              if ($hijriDay === '') $hijriDay = (string)($d['day'] ?? '');

              if ($isPast) {
                // Past date — no link, greyed out
                echo '<td class="cal-cell cal-past">'
                  . '<div class="cal-link-disabled">'
                  . '<span class="cal-hijri">' . htmlspecialchars($hijriDay, ENT_QUOTES) . '</span>'
                  . '<span class="cal-greg">'  . htmlspecialchars($gregDt->format('j M'), ENT_QUOTES) . '</span>'
                  . '</div>'
                  . '</td>';
              } else {
                echo '<td class="cal-cell' . ($isToday ? ' cal-today' : '') . '">';
                echo '<a href="' . site_url('accounts/time_slots?date=' . $greg) . '" class="cal-link">'
                  . '<span class="cal-hijri">' . htmlspecialchars($hijriDay, ENT_QUOTES) . '</span>'
                  . '<span class="cal-greg">'  . htmlspecialchars($gregDt->format('j M'), ENT_QUOTES) . '</span>'
                  . '</a>';
                echo '</td>';
              }

              if ((int)$gregDt->format('w') === 6) echo '</tr><tr>';
            }

            $lastGreg    = new DateTime(end($days)['greg_date']);
            $endWeekday  = (int)$lastGreg->format('w');
            if ($endWeekday !== 6) {
              for ($i = $endWeekday + 1; $i < 7; $i++) echo '<td class="cal-empty"></td>';
            }
          }
          echo '</tr>';
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Appointments table ── -->
  <div class="section-card" style="margin-top:28px;">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-calendar-check-o"></i> Your Appointments</h5>
    </div>
    <div class="table-responsive">
      <table class="themed-table">
        <thead>
          <tr>
            <th>Purpose</th>
            <th>Details</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (is_array($user_appointments)) {
            usort($user_appointments, fn($a,$b) => strtotime($a->date) - strtotime($b->date));
          } else { $user_appointments = []; }

          if (empty($user_appointments)): ?>
            <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--text-3);font-size:.83rem;">No appointments found.</td></tr>
          <?php else:
            foreach ($user_appointments as $appt):
              $attended = (int)$appt->status === 1;
          ?>
          <tr>
            <td style="font-weight:600;color:var(--text-1);"><?= isset($appt->purpose) ? htmlspecialchars($appt->purpose) : '' ?></td>
            <td style="font-size:.8rem;color:var(--text-2);"><?= isset($appt->other_details) ? nl2br(htmlspecialchars($appt->other_details)) : '' ?></td>
            <td style="white-space:nowrap;font-weight:600;"><?= date('D, d M Y', strtotime($appt->date)) ?></td>
            <td style="white-space:nowrap;"><?= htmlspecialchars($appt->time) ?></td>
            <td>
              <span class="status-pill <?= $attended ? 'pill-attended' : 'pill-pending' ?>">
                <i class="fa <?= $attended ? 'fa-check' : 'fa-clock-o' ?>"></i>
                <?= $attended ? 'Attended' : 'Not Attended' ?>
              </span>
            </td>
            <td class="text-center">
              <?php if (!$attended): ?>
                <a href="<?= site_url('accounts/delete_appointment/' . $appt->id) ?>"
                   class="btn-del"
                   onclick="return confirm('Are you sure you want to delete this appointment?')">
                  <i class="fa fa-trash"></i> Delete
                </a>
              <?php else: ?>
                <span title="Appointment Attended">
                  <svg fill="var(--green)" width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z"/>
                  </svg>
                </span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php
  return ob_get_clean();
}
?>

<?php
if (isset($_GET['partial']) && $_GET['partial'] == '1') {
  echo generateCalendar($hijri_month, $hijri_year, $user_appointments);
  return;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2: #5a5244; --text-3: #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg: #fef2f2;  --red-border: #fecaca;
    --blue:       #1d4ed8; --blue-bg: #eff6ff;  --blue-border: #bfdbfe;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Header ── */
  .page-header-wrap { display: flex; align-items: center; min-height: 44px; margin-bottom: 4px; }
  .btn-back-nav { flex-shrink: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.45rem; font-weight: 600; letter-spacing: -.3px; margin: 0; }
  .page-sub { font-size: 0.7rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); margin: 2px 0 0; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 14px 0 22px; }

  /* ── Month nav ── */
  .month-nav { display: flex; align-items: center; justify-content: space-between; background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 13px 20px; margin-bottom: 18px; }
  .month-nav-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface-2); color: var(--text-2); font-size: 13px; cursor: pointer; transition: all .15s; }
  .month-nav-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
  .month-nav-btn:active { transform: translateY(1px); }
  .month-nav-label { font-family: 'Literata', Georgia, serif; font-size: 1.1rem; font-weight: 600; color: var(--text-1); display: block; }
  .month-nav-sub   { font-size: 0.62rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); display: block; margin-top: 1px; }

  /* ── Calendar card ── */
  .cal-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; }
  .cal-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

  /* ── Calendar table ── */
  .cal-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
  .cal-table thead th { padding: 10px 4px; font-size: 0.65rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); background: var(--surface-2); border-bottom: 1.5px solid var(--border); text-align: center; }
  .cal-table tbody td { border: 1px solid var(--border); padding: 2px; vertical-align: top; min-height: 60px; }
  .cal-empty { background: var(--surface-2) !important; }

  /* day cell */
  .cal-cell { background: var(--surface); transition: background .12s; }
  .cal-cell:hover { background: var(--gold-muted); }

  /* today highlight */
  .cal-today { background: var(--blue-bg) !important; border: 1.5px solid var(--blue) !important; }
  .cal-today .cal-link { color: var(--blue); }
  .cal-today .cal-hijri { color: var(--blue); }

  /* date link */
  .cal-link { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 8px 4px; min-height: 56px; text-decoration: none; color: var(--text-2); border-radius: 6px; transition: color .12s; gap: 3px; }
  .cal-link:hover { color: var(--gold-deep); text-decoration: none; }
  .cal-hijri { font-size: 1rem; font-weight: 800; color: var(--text-1); line-height: 1; }
  .cal-greg  { font-size: 0.62rem; font-weight: 600; color: var(--text-3); margin-top: 2px; }
  .cal-today .cal-greg { color: var(--blue); }

  /* past date cell */
  .cal-past { background: var(--surface-2) !important; cursor: not-allowed; }
  .cal-past .cal-link-disabled { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 8px 4px; min-height: 56px; gap: 3px; cursor: not-allowed; }
  .cal-past .cal-hijri { color: var(--text-3) !important; font-weight: 600; }
  .cal-past .cal-greg  { color: var(--border) !important; }
  @media (max-width: 480px) {
    .cal-link { min-height: 44px; padding: 5px 2px; }
    .cal-hijri { font-size: 0.88rem; }
    .cal-greg  { font-size: 0.55rem; }
    .cal-past .cal-link-disabled { min-height: 44px; padding: 5px 2px; }
  }

  /* ── Section card (appointments) ── */
  .section-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; }
  .section-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .section-card-header { display: flex; align-items: center; padding: 13px 20px 11px; background: var(--surface-2); border-bottom: 1px solid var(--border); }
  .section-card-title { font-size: 0.78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-2); display: flex; align-items: center; gap: 8px; margin: 0; }
  .section-card-title .fa { width: 26px; height: 26px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; background: var(--gold-muted); color: var(--gold); }

  /* ── Appointments table ── */
  .themed-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
  .themed-table thead th { font-size: 0.63rem; font-weight: 700; letter-spacing: .55px; text-transform: uppercase; color: var(--text-3); padding: 10px 14px; border-bottom: 1.5px solid var(--border); background: var(--surface-2); white-space: nowrap; }
  .themed-table tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
  .themed-table tbody tr:last-child td { border-bottom: none; }
  .themed-table tbody tr:nth-of-type(odd)  { background: var(--surface-2); }
  .themed-table tbody tr:nth-of-type(even) { background: var(--surface); }
  .themed-table tbody tr:hover { background: var(--gold-muted) !important; }

  /* status pills */
  .status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 40px; font-size: 0.65rem; font-weight: 700; letter-spacing: .3px; }
  .pill-attended { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .pill-pending  { background: var(--red-bg);   color: var(--red);   border: 1px solid var(--red-border); }

  /* delete button */
  .btn-del { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: var(--radius-sm); border: 1.5px solid var(--red-border); background: var(--red-bg); color: var(--red); font-size: 0.72rem; font-weight: 700; text-decoration: none; transition: all .15s; }
  .btn-del:hover { background: var(--red); color: #fff; text-decoration: none; }

  @media (max-width: 575px) { .page-heading { font-size: 1.2rem; } }
</style>

<div id="calendar-content">
  <?php echo generateCalendar($hijri_month, $hijri_year, $user_appointments); ?>
</div>

<!-- ══ JS IDENTICAL TO ORIGINAL ══ -->
<script>
function changeMonth(offset) {
  const el = document.getElementById('month-year');
  if (!el) return;
  let month = parseInt(el.dataset.hijriMonth || '0', 10);
  let year  = parseInt(el.dataset.hijriYear  || '0', 10);
  if (!month || !year) return;

  month += offset;
  if (month < 1)  { month = 12; year -= 1; }
  else if (month > 12) { month = 1; year += 1; }

  fetch(`?hijri_month=${month}&hijri_year=${year}&partial=1`)
    .then(r => r.text())
    .then(html => { document.getElementById('calendar-content').innerHTML = html; })
    .catch(err => console.error('Error fetching calendar data:', err));
}
</script>