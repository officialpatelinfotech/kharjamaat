<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --green-border:#bbf7d0;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:      0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap {
    position: relative; display: flex; align-items: center;
    justify-content: center; min-height: 44px; margin-bottom: 6px;
  }
  .btn-back-nav {
    position: absolute; left: 0; width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface); color: var(--text-2); font-size: 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
  }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 24px; }

  /* ── Month navigator ── */
  .month-nav {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 14px 20px;
    margin-bottom: 24px;
  }
  .month-nav-btn {
    width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface-2); color: var(--text-2); font-size: 13px;
    text-decoration: none; transition: all .15s; flex-shrink: 0;
  }
  .month-nav-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .month-nav-btn.disabled { opacity: .4; pointer-events: none; cursor: not-allowed; }
  .month-nav-label {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.05rem; font-weight: 600;
    color: var(--text-1); text-align: center; flex: 1; padding: 0 12px;
  }
  .month-nav-label small { display: block; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-top: 1px; }

  /* ── Day cards grid ── */
  .feedback-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 40px;
  }
  @media (max-width: 900px) { .feedback-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 560px) { .feedback-grid { grid-template-columns: 1fr; gap: 10px; } }

  /* ── Day card ── */
  .day-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    display: flex; flex-direction: column;
    overflow: hidden;
    transition: box-shadow .18s, border-color .18s;
  }
  .day-card:hover { box-shadow: var(--shadow); border-color: rgba(184,134,11,0.3); }

  /* top accent */
  .day-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); opacity: 0; transition: opacity .18s; }
  .day-card:hover::before { opacity: 1; }
  .day-card.has-feedback::before { opacity: 1; background: linear-gradient(90deg, var(--green), #4cc790); }
  .day-card.no-thaali { opacity: .7; }
  .day-card.no-thaali::before { background: linear-gradient(90deg, var(--text-3), #c4b49a); }

  .day-card-header {
    padding: 12px 14px 10px;
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
  }
  .day-menu-name {
    font-size: 0.87rem; font-weight: 700; color: var(--text-1);
    line-height: 1.3; margin: 0 0 5px;
  }
  .day-menu-name.no-menu { color: var(--text-3); font-style: italic; font-weight: 500; }
  .day-dates { display: flex; flex-direction: column; gap: 1px; }
  .day-hijri { font-size: 0.68rem; font-weight: 700; color: var(--gold-deep); letter-spacing: .2px; }
  .day-greg  { font-size: 0.68rem; color: var(--text-3); font-weight: 500; }

  .day-card-body { padding: 12px 14px; flex: 1; display: flex; flex-direction: column; gap: 8px; }

  /* info row */
  .day-info-row { display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; }
  .day-info-label { font-size: 0.65rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); }
  .day-info-value { font-weight: 600; color: var(--text-1); }

  /* thaali taken badge */
  .badge-taken    { display: inline-block; padding: 2px 9px; border-radius: 40px; font-size: 0.65rem; font-weight: 700; background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .badge-not-taken{ display: inline-block; padding: 2px 9px; border-radius: 40px; font-size: 0.65rem; font-weight: 700; background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }

  .day-card-footer { padding: 10px 14px 14px; }

  /* feedback buttons */
  .btn-give-feedback {
    display: inline-flex; align-items: center; gap: 6px;
    width: 100%; justify-content: center;
    padding: 9px 14px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 2px 6px rgba(184,134,11,0.25);
    transition: all .18s;
  }
  .btn-give-feedback:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); box-shadow: 0 4px 12px rgba(184,134,11,0.35); transform: translateY(-1px); }
  .btn-feedback-given {
    display: inline-flex; align-items: center; gap: 6px;
    width: 100%; justify-content: center;
    padding: 9px 14px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--green-border);
    background: var(--green-bg); color: var(--green);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-feedback-given:hover { background: var(--green); color: #fff; }
  .badge-no-thaali {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 9px 14px; border-radius: var(--radius-sm);
    background: var(--surface-2); border: 1.5px solid var(--border);
    color: var(--text-3); font-size: 0.75rem; font-weight: 600;
  }

  /* ── Feedback Modal ── */
  #feedback-form-container .modal-dialog { max-width: 500px; }
  #feedback-form-container .modal-content {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important;
    overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  #feedback-form-container .modal-header {
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    padding: 18px 24px;
  }
  #feedback-form-container .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.1rem; font-weight: 600; color: var(--gold);
  }
  #feedback-form-container .close { color: var(--text-2); opacity: 1; font-size: 1.1rem; }
  #feedback-form-container .close:hover { color: var(--red); }
  #feedback-form-container .modal-body { background: var(--bg); padding: 20px 24px; }
  #feedback-form-container .modal-footer {
    background: var(--surface-2); border-top: 1px solid var(--border);
    padding: 14px 24px; display: flex; justify-content: space-between;
  }

  /* feedback form fields */
  #feedback-form .form-group { margin-bottom: 16px; }
  #feedback-form label {
    font-size: 0.7rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; display: block;
  }
  #feedback-form .form-control {
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem; font-weight: 500; color: var(--text-1);
    padding: 10px 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: border-color .2s, box-shadow .2s;
    appearance: none; -webkit-appearance: none;
  }
  #feedback-form select.form-control {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 38px;
  }
  #feedback-form .form-control:focus {
    border-color: var(--gold); outline: none;
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12);
  }

  /* modal buttons */
  #submit-feedback {
    padding: 9px 22px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 2px 8px rgba(184,134,11,0.25); transition: all .15s;
    display: inline-flex; align-items: center; gap: 7px;
  }
  #submit-feedback:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); }
  #submit-feedback::before { content: '\f00c'; font-family: FontAwesome; font-size: 12px; }

  .btn-modal-close {
    padding: 9px 20px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-modal-close:hover { background: var(--surface-2); }

  /* ── Empty state ── */
  .empty-state {
    grid-column: 1 / -1; padding: 56px 24px; text-align: center;
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
  }
  .empty-state .fa { font-size: 2.5rem; color: var(--border); display: block; margin-bottom: 12px; }
  .empty-state p { font-size: 0.88rem; color: var(--text-3); font-weight: 500; margin: 0; }

  .hidden { display: none; }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap">
    <a href="<?php echo base_url("accounts/home") ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">FMB Thaali Feedback</h1>
  </div>
  <p class="page-sub">Rate your daily thaali experience</p>
  <hr class="section-divider">

  <?php
  /* ── Hijri month navigation logic (identical to original) ── */
  $CI = &get_instance();
  if (!isset($CI->HijriCalendar)) { $CI->load->model('HijriCalendar'); }
  $hijri_years    = $CI->HijriCalendar->get_distinct_hijri_years();
  $today_hijri    = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
  $default_year   = isset($today_hijri['hijri_year']) ? (int)$today_hijri['hijri_year'] : (!empty($hijri_years) ? (int)$hijri_years[0] : null);
  $sel_year       = isset($_GET['hijri_year']) ? (int)$_GET['hijri_year'] : $default_year;
  $months_this_year = $sel_year ? $CI->HijriCalendar->get_hijri_months_for_year($sel_year) : [];
  $default_month  = isset($today_hijri['hijri_month']) ? (int)$today_hijri['hijri_month'] : (!empty($months_this_year) ? (int)$months_this_year[0]['id'] : null);
  $sel_month      = isset($_GET['hijri_month']) ? (int)$_GET['hijri_month'] : $default_month;

  $current_month_name = '';
  foreach ($months_this_year as $m) {
    if ((int)$m['id'] === (int)$sel_month) { $current_month_name = $m['name']; break; }
  }

  $prev_month = null; $prev_year = $sel_year;
  $next_month = null; $next_year = $sel_year;
  $index = -1;
  foreach ($months_this_year as $i => $m) {
    if ((int)$m['id'] === (int)$sel_month) { $index = $i; break; }
  }
  if ($index > 0) { $prev_month = $months_this_year[$index-1]['id']; $prev_year = $sel_year; }
  elseif ($index === 0) {
    $prev_year = $sel_year - 1;
    $pm = $CI->HijriCalendar->get_hijri_months_for_year($prev_year);
    if (!empty($pm)) $prev_month = end($pm)['id'];
  }
  if ($index >= 0 && $index < count($months_this_year)-1) { $next_month = $months_this_year[$index+1]['id']; $next_year = $sel_year; }
  elseif ($index === count($months_this_year)-1) {
    $next_year = $sel_year + 1;
    $nm = $CI->HijriCalendar->get_hijri_months_for_year($next_year);
    if (!empty($nm)) $next_month = $nm[0]['id'];
  }
  ?>

  <!-- ── Month navigator ── -->
  <div class="month-nav">
    <?php if (!empty($prev_month)): ?>
      <a href="?hijri_year=<?= urlencode($prev_year) ?>&hijri_month=<?= urlencode($prev_month) ?>" class="month-nav-btn" aria-label="Previous month">
        <i class="fa fa-chevron-left"></i>
      </a>
    <?php else: ?>
      <span class="month-nav-btn disabled"><i class="fa fa-chevron-left"></i></span>
    <?php endif; ?>

    <div class="month-nav-label">
      <?= htmlspecialchars($current_month_name) ?> <?= htmlspecialchars($sel_year) ?>
      <small>Hijri Month</small>
    </div>

    <?php if (!empty($next_month)): ?>
      <a href="?hijri_year=<?= urlencode($next_year) ?>&hijri_month=<?= urlencode($next_month) ?>" class="month-nav-btn" aria-label="Next month">
        <i class="fa fa-chevron-right"></i>
      </a>
    <?php else: ?>
      <span class="month-nav-btn disabled"><i class="fa fa-chevron-right"></i></span>
    <?php endif; ?>
  </div>

  <?php
  /* ── All menu/day logic identical to original ── */
  function _normalize_hijri($s) {
    if (!$s) return '';
    preg_match_all('/(\d+)/', $s, $matches);
    if (empty($matches[0]) || count($matches[0]) < 3) return trim($s);
    $parts = $matches[0];
    return (int)$parts[0] . '-' . (int)$parts[1] . '-' . $parts[2];
  }
  $menu_map = [];
  if (!empty($menu) && is_array($menu)) {
    foreach ($menu as $m) {
      if (isset($m['hijri_date'])) {
        $key = _normalize_hijri($m['hijri_date']);
        if (!isset($menu_map[$key])) $menu_map[$key] = [];
        $menu_map[$key][] = $m;
        $raw = trim($m['hijri_date']);
        if ($raw !== $key) {
          if (!isset($menu_map[$raw])) $menu_map[$raw] = [];
          $menu_map[$raw][] = $m;
        }
      }
    }
  }
  $selected_month = null; $selected_year = null;
  if (isset($_GET['hijri_month']) && isset($_GET['hijri_year'])) {
    $selected_month = $_GET['hijri_month']; $selected_year = $_GET['hijri_year'];
  } elseif (!empty($menu_map)) {
    $first_date = array_keys($menu_map)[0];
    $parts = explode('-', $first_date);
    if (count($parts) === 3) { $selected_month = ltrim($parts[1],'0'); $selected_year = $parts[2]; }
  }
  if (empty($selected_month) || empty($selected_year)) {
    $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
    if ($parts) { $selected_month = $parts['hijri_month']; $selected_year = $parts['hijri_year']; }
  }
  $days = [];
  if (!empty($selected_month) && !empty($selected_year)) {
    $days = $CI->HijriCalendar->get_hijri_days_for_month_year($selected_month, $selected_year);
  }
  $fetched_menu = [];
  if ((empty($menu) || !is_array($menu)) && !empty($days)) {
    if (!isset($CI->AccountM)) $CI->load->model('AccountM');
    $first_greg = $days[0]['greg_date'];
    $last_greg  = $days[count($days)-1]['greg_date'];
    $menus_between = $CI->AccountM->get_menus_between($first_greg, $last_greg);
    $greg_menu_map = [];
    foreach ($menus_between as $mm) { $d = $mm['date'] ?? null; if ($d) $greg_menu_map[$d] = $mm; }
    foreach ($days as $d) {
      $greg = $d['greg_date']; $hij = $d['hijri_date'];
      if (isset($greg_menu_map[$greg])) {
        $mrow = $greg_menu_map[$greg];
        $fetched_menu[] = ['fwsid'=>$mrow['id']??null,'item_names'=>!empty($mrow['items'])?implode(', ',$mrow['items']):'','hijri_date'=>$hij,'greg_date'=>$greg,'want_thali'=>1,'thali_size'=>'','status'=>0];
      }
    }
    if (!empty($fetched_menu)) {
      $menu = $fetched_menu;
      $menu_map = [];
      foreach ($menu as $m) {
        if (isset($m['hijri_date'])) {
          $key = _normalize_hijri($m['hijri_date']);
          if (!isset($menu_map[$key])) $menu_map[$key] = [];
          $menu_map[$key][] = $m;
          $raw = trim($m['hijri_date']);
          if ($raw !== $key) { if (!isset($menu_map[$raw])) $menu_map[$raw] = []; $menu_map[$raw][] = $m; }
        }
      }
    }
  }
  ?>

  <!-- ── Day cards grid ── -->
  <div class="feedback-grid">

    <?php
    /* ─ render helper: one card ─ */
    function render_feedback_card($menu_item, $hijri_date, $greg_date, $signup_data) {
      $signed = false; $size = ''; $signupId = null;
      if (!empty($signup_data) && !empty($greg_date)) {
        foreach ($signup_data as $sd) {
          if (isset($sd['signup_date']) && $sd['signup_date'] === $greg_date) {
            if ((string)($sd['want_thali'] ?? '') === '1') $signed = true;
            $size = $sd['thali_size'] ?? '';
            $signupId = $sd['id'] ?? null;
          }
        }
      }
      if (!$signupId && $menu_item && isset($menu_item['signup_id'])) $signupId = $menu_item['signup_id'];
      $feedbackGiven = (int)($menu_item['status'] ?? 0);
      $menuName = $menu_item ? ($menu_item['item_names'] ?? '') : '';
      $cardClass = !$signed ? 'no-thaali' : ($feedbackGiven ? 'has-feedback' : '');
      ?>
      <div class="day-card <?= $cardClass ?>">
        <div class="day-card-header">
          <?php if ($menuName): ?>
            <p class="day-menu-name"><?= htmlspecialchars($menuName) ?></p>
          <?php else: ?>
            <p class="day-menu-name no-menu">No menu</p>
          <?php endif; ?>
          <div class="day-dates">
            <span class="day-hijri"><i class="fa fa-moon-o" style="margin-right:4px;opacity:.7;"></i><?= htmlspecialchars($hijri_date) ?></span>
            <?php if (!empty($greg_date)): ?>
              <span class="day-greg"><?= htmlspecialchars(date('D, d M Y', strtotime($greg_date))) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="day-card-body">
          <div class="day-info-row">
            <span class="day-info-label">Thaali Taken</span>
            <?php if ($signed): ?>
              <span class="badge-taken"><i class="fa fa-check" style="margin-right:3px;"></i>Yes</span>
            <?php else: ?>
              <span class="badge-not-taken">No</span>
            <?php endif; ?>
          </div>
          <?php if ($size): ?>
          <div class="day-info-row">
            <span class="day-info-label">Size</span>
            <span class="day-info-value"><?= htmlspecialchars($size) ?></span>
          </div>
          <?php endif; ?>
        </div>

        <div class="day-card-footer">
          <?php if ($signed && $signupId): ?>
            <?php if ($feedbackGiven): ?>
              <button class="btn-feedback-given give-feedback-btn" data-fwsid="<?= htmlspecialchars($signupId) ?>">
                <i class="fa fa-check-circle"></i> Feedback Given
              </button>
            <?php else: ?>
              <button class="btn-give-feedback give-feedback-btn" data-fwsid="<?= htmlspecialchars($signupId) ?>">
                <i class="fa fa-star"></i> Give Feedback
              </button>
            <?php endif; ?>
          <?php else: ?>
            <div class="badge-no-thaali"><i class="fa fa-minus-circle"></i> Thaali not taken</div>
          <?php endif; ?>
        </div>
      </div>
      <?php
    }

    /* ─ render all cards ─ */
    if (empty($days)) {
      if (!empty($menu) && is_array($menu)) {
        foreach ($menu as $item) {
          render_feedback_card($item, $item['hijri_date'] ?? '', $item['greg_date'] ?? '', $signup_data ?? []);
        }
      } else { ?>
        <div class="empty-state">
          <i class="fa fa-calendar-times-o"></i>
          <p>No menu data available for this month</p>
        </div>
      <?php }
    } else {
      foreach ($days as $d) {
        $hijri_date = $d['hijri_date'];
        $parts_d = explode('-', $hijri_date);
        $keyd = count($parts_d) === 3 ? ((int)$parts_d[0].'-'.(int)$parts_d[1].'-'.$parts_d[2]) : $hijri_date;
        $menu_items_for_day = $menu_map[$keyd] ?? null;
        $menu_item = (is_array($menu_items_for_day) && count($menu_items_for_day) > 0) ? $menu_items_for_day[0] : null;
        render_feedback_card($menu_item, $hijri_date, $d['greg_date'] ?? '', $signup_data ?? []);
      }
    }
    ?>

  </div><!-- /.feedback-grid -->
</div><!-- /.container -->

<!-- ── Feedback Modal ── -->
<div class="modal fade" id="feedback-form-container" data-keyboard="false" tabindex="-1" aria-labelledby="feedback-form-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedback-form-title">
          <i class="fa fa-star" style="margin-right:8px; color:var(--gold);"></i>Thaali Feedback
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="javascript:void(0)" id="feedback-form" data-form-id="0">
          <div class="form-group">
            <label for="delivery-time">Delivery Time</label>
            <select name="delivery_time" id="delivery-time" class="form-control">
              <option value="" disabled selected>— Select —</option>
              <option value="On Time">On Time</option>
              <option value="Too Early">Too Early</option>
              <option value="Too Late">Too Late</option>
            </select>
          </div>
          <div class="form-group">
            <label for="thali-quality">Quality</label>
            <select name="quality" id="thali-quality" class="form-control">
              <option value="" disabled selected>— Select —</option>
              <option value="Excellent">Excellent</option>
              <option value="Very Good">Very Good</option>
              <option value="Good">Good</option>
              <option value="Fair">Fair</option>
              <option value="Poor">Poor</option>
            </select>
          </div>
          <div class="form-group">
            <label for="thali-freshness">Freshness</label>
            <select name="freshness" id="thali-freshness" class="form-control">
              <option value="" disabled selected>— Select —</option>
              <option value="Fresh">Fresh</option>
              <option value="Acceptable">Acceptable</option>
              <option value="Slightly Stale">Slightly Stale</option>
              <option value="Not Fresh">Not Fresh</option>
            </select>
          </div>
          <div class="form-group">
            <label for="thali-quantity">Quantity</label>
            <select name="quantity" id="thali-quantity" class="form-control">
              <option value="" disabled selected>— Select —</option>
              <option value="Much More Than Needed">Much More Than Needed</option>
              <option value="Slightly More Than Needed">Slightly More Than Needed</option>
              <option value="Just Right">Just Right</option>
              <option value="Slightly Less Than Needed">Slightly Less Than Needed</option>
              <option value="Too Little">Too Little</option>
            </select>
          </div>
          <div class="form-group">
            <label for="feedback-remark">Remarks</label>
            <input type="text" class="form-control" name="feedback_remark" id="feedback-remark" placeholder="Any additional comments…">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="submit-feedback">Submit Feedback</button>
        <button type="button" class="btn-modal-close" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- ── ALL JS IDENTICAL TO ORIGINAL ── -->
<script>
  (function($) {
    $(function() {
      $(document).on('click', '.give-feedback-btn', function() {
        var $fwsid = $(this).data('fwsid');
        if (!$fwsid) return;
        $.ajax({
          url: '<?php echo base_url("accounts/getFMBFeedbackData"); ?>',
          method: 'POST', dataType: 'json', data: { fwsid: $fwsid },
          success: function(res) {
            if (!res || !res.id) {
              $("#feedback-form").data("form-id", $fwsid);
              $('#feedback-form-container').modal('show');
              $("#delivery-time").val(''); $("#thali-quality").val('');
              $("#thali-freshness").val(''); $("#thali-quantity").val(''); $("#feedback-remark").val('');
              return;
            }
            $("#feedback-form").data("form-id", res.id);
            $('#feedback-form-container').modal('show');
            $("#delivery-time").val(res.delivery_time);
            $("#thali-quality").val(res.quality);
            $("#thali-freshness").val(res.freshness);
            $("#thali-quantity").val(res.quantity);
            $("#feedback-remark").val(res.feedback_remark);
          }
        });
      });

      $("#submit-feedback").on("click", function() {
        var $feedbackId = $("#feedback-form").data("form-id");
        if (!$feedbackId) return;
        $.ajax({
          url: '<?php echo base_url("accounts/UpdateFMBFeedback"); ?>',
          method: "POST", dataType: 'json',
          data: {
            feedback_id: $feedbackId,
            delivery_time: $("#delivery-time").val(),
            quality: $("#thali-quality").val(),
            freshness: $("#thali-freshness").val(),
            quantity: $("#thali-quantity").val(),
            feedback_remark: $("#feedback-remark").val()
          },
          success: function(res) {
            if (res && res.success) {
              $('#feedback-form-container').modal('hide');
              setTimeout(function() { location.reload(); }, 600);
            }
          }
        });
      });
    });
  })(jQuery);
</script>