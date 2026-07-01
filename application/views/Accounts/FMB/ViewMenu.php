<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2: #5a5244;  --text-3: #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg:   #fef2f2; --red-border:   #fecaca;
    --blue:       #1d4ed8; --blue-bg:  #eff6ff; --blue-border:  #bfdbfe;
    --amber:      #b45309; --amber-bg: #fffbeb; --amber-border: #fde68a;
    --teal:       #0e7490; --teal-bg:  #ecfeff; --teal-border:  #a5f3fc;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub     { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 16px 0 20px; }

  /* ── Filter bar ── */
  .filter-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    padding: 16px 20px; margin-bottom: 18px;
  }
  .filter-label { font-size: 0.63rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; display: block; }
  .filter-control {
    width: 100%; height: 40px; padding: 0 12px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem; font-weight: 500; color: var(--text-1);
    transition: border-color .2s, box-shadow .2s; outline: none; box-sizing: border-box;
  }
  .filter-control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,11,.12); }
  select.filter-control {
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; padding-right: 34px;
  }
  input[type="date"].filter-control { padding: 0 12px; }
  .btn-reset {
    width: 100%; height: 40px; border-radius: var(--radius-sm);
    border: 1.5px solid var(--border); background: var(--surface-2);
    color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all .15s;
  }
  .btn-reset:hover { border-color: var(--gold); color: var(--gold-deep); background: var(--gold-muted); }

  /* ── Month nav ── */
  .month-nav { display: flex; align-items: center; justify-content: space-between; background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 13px 20px; margin-bottom: 22px; }
  .month-nav-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface-2); color: var(--text-2); font-size: 13px; text-decoration: none; transition: all .15s; flex-shrink: 0; }
  .month-nav-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .month-nav-label  { font-family: 'Literata', Georgia, serif; font-size: 1.05rem; font-weight: 600; color: var(--text-1); }
  .month-nav-sublbl { font-size: 0.62rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); display: block; margin-top: 1px; }

  /* ── Cards grid ── */
  .menu-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; max-height: 72vh; overflow-y: auto; padding-right: 4px; }
  .menu-grid::-webkit-scrollbar { width: 4px; }
  .menu-grid::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
  @media (max-width: 1100px) { .menu-grid { grid-template-columns: repeat(3, 1fr); } }
  @media (max-width: 768px)  { .menu-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 520px)  { .menu-grid { grid-template-columns: 1fr; } }

  /* ── Schedule card ── */
  .schedule-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    display: flex; flex-direction: column; overflow: hidden;
    transition: box-shadow .2s, transform .2s, border-color .2s;
    position: relative;
  }
  .schedule-card:hover { box-shadow: var(--shadow); transform: translateY(-3px); border-color: rgba(184,134,11,.3); }
  .schedule-card::before { content: ''; display: block; height: 3px; }
  .sc-menu::before    { background: linear-gradient(90deg, var(--green),  #4cc790); }
  .sc-holiday::before { background: linear-gradient(90deg, var(--amber),  var(--gold-light)); }
  .sc-miqaat::before  { background: linear-gradient(90deg, var(--blue),   #60a5fa); }
  .sc-empty::before   { background: linear-gradient(90deg, var(--border), #e5e7eb); }
  .sc-today::before   { background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold)) !important; height: 4px; }
  .sc-today {
    box-shadow: 0 0 0 2px var(--blue), 0 6px 20px rgba(29,78,216,0.15) !important;
    border-color: var(--blue) !important;
    background: var(--surface) !important;
    z-index: 2;
    position: relative;
  }
  .sc-today:hover { transform: translateY(-3px); }
  .sc-today::before   { background: linear-gradient(90deg, var(--blue), #60a5fa) !important; height: 4px; }
  .sc-today .sc-header { background: var(--blue-bg) !important; border-bottom-color: var(--blue-border) !important; }
  .sc-today .sc-greg  { color: var(--blue); }

  /* card header */
  .sc-header { padding: 10px 14px 9px; background: var(--surface-2); border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
  .sc-greg  { font-size: 0.88rem; font-weight: 700; color: var(--text-1); line-height: 1.2; }
  .sc-hijri { font-size: 0.65rem; font-weight: 700; color: var(--gold-deep); margin-top: 2px; display: block; }
  .sc-chips { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; flex-shrink: 0; }

  /* type chips */
  .type-chip { display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 40px; font-size: 0.58rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; white-space: nowrap; }
  .chip-menu    { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
  .chip-holiday { background: var(--amber-bg); color: var(--amber); border: 1px solid var(--amber-border); }
  .chip-miqaat  { background: var(--blue-bg);  color: var(--blue);  border: 1px solid var(--blue-border); }
  .chip-empty   { background: var(--surface-2); color: var(--text-3); border: 1px solid var(--border); }
  .chip-today   { background: var(--blue); color: #fff; border: 1px solid var(--blue); animation: pulseToday 2s ease-in-out infinite; font-size: 0.62rem; }
  @keyframes pulseToday { 0%,100% { box-shadow: 0 0 0 0 rgba(29,78,216,.5); } 70% { box-shadow: 0 0 0 5px rgba(29,78,216,0); } }

  /* card body */
  .sc-body { padding: 10px 14px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
  .sc-weekday { font-size: 0.62rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 4px; }

  .sc-section-label { font-size: 0.58rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); margin-bottom: 4px; margin-top: 6px; }
  .sc-section-label:first-child { margin-top: 0; }

  /* food chips */
  .food-chips-wrap { display: flex; flex-wrap: wrap; gap: 5px; }
  .food-chip { display: inline-flex; align-items: center; gap: 3px; background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); padding: 3px 9px; border-radius: 40px; font-size: 0.65rem; font-weight: 600; }
  .food-chip .fa { font-size: 9px; opacity: .7; }
  .food-soon { font-size: 0.73rem; color: var(--text-3); font-style: italic; }

  /* assigned to */
  .sc-assigned { font-size: 0.78rem; color: var(--text-2); font-weight: 500; }

  /* miqaat list */
  .miqaat-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 3px; }
  .miqaat-list li { font-size: 0.72rem; color: var(--blue); font-weight: 600; display: flex; align-items: center; gap: 4px; }

  /* holiday */
  .holiday-row { display: inline-flex; align-items: center; gap: 5px; font-size: 0.75rem; font-weight: 700; color: var(--amber); background: var(--amber-bg); border: 1px solid var(--amber-border); border-radius: 40px; padding: 3px 10px; }

  /* empty state */
  .sc-empty-text { font-size: 0.75rem; color: var(--text-3); font-style: italic; }

  /* result count */
  .result-count { font-size: 0.7rem; font-weight: 700; color: var(--text-3); margin-top: 12px; }
  .result-count span { background: var(--gold-muted); color: var(--gold-deep); padding: 2px 9px; border-radius: 40px; font-weight: 800; font-size: 0.65rem; }

  /* empty global state */
  .empty-state { padding: 56px 24px; text-align: center; }
  .empty-state .fa { font-size: 2.5rem; color: var(--border); display: block; margin-bottom: 12px; }
  .empty-state p { font-size: 0.88rem; color: var(--text-3); font-weight: 500; margin: 0; }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .filter-card { padding: 12px 14px; }
    .sc-greg { font-size: 0.95rem; }
    .food-chip { font-size: 0.72rem; }
  }
</style>

<?php
  $schedule = isset($month_schedule) ? $month_schedule : (isset($menus) ? $menus : []);
  $today    = date('Y-m-d');
?>

<div class="container margintopcontainer pt-3 pb-4">

  <!-- ── Header ── -->
  <h1 class="page-heading pt-4">FMB Menu</h1>
  <p class="page-sub">Daily thaali menu &amp; miqaat schedule</p>
  <hr class="section-divider">

  <!-- ── Filters ── -->
  <div class="filter-card">
    <div class="row g-2 align-items-end">
      <div class="col-6 col-sm-3">
        <label class="filter-label">Type</label>
        <select class="filter-control" id="filter-type">
          <option value="">All</option>
          <option value="menu">Menu</option>
          <option value="holiday">Holiday</option>
          <option value="miqaat">Miqaat</option>
        </select>
      </div>
      <div class="col-12 col-sm-4">
        <label class="filter-label">Search Items / Name</label>
        <input type="text" id="filter-text" class="filter-control" placeholder="Search…" />
      </div>
      <div class="col-6 col-sm-3">
        <label class="filter-label">Date</label>
        <input type="date" id="filter-date" class="filter-control" />
      </div>
      <div class="col-6 col-sm-2">
        <button type="button" id="filter-clear" class="btn-reset">Reset</button>
      </div>
    </div>
  </div>

  <!-- ── Month nav ── -->
  <div class="month-nav">
    <a class="month-nav-btn"
       href="<?php echo base_url('accounts/viewmenu') . '?hijri=' . htmlspecialchars($prev_hijri ?? '', ENT_QUOTES); ?>"
       aria-label="Previous month"><i class="fa fa-chevron-left"></i></a>
    <div class="text-center">
      <span class="month-nav-label"><?php echo htmlspecialchars(($hijri_month_name ?? '') . ' ' . ($hijri_year ?? ''), ENT_QUOTES); ?></span>
      <span class="month-nav-sublbl">Hijri Month</span>
    </div>
    <a class="month-nav-btn"
       href="<?php echo base_url('accounts/viewmenu') . '?hijri=' . htmlspecialchars($next_hijri ?? '', ENT_QUOTES); ?>"
       aria-label="Next month"><i class="fa fa-chevron-right"></i></a>
  </div>

  <!-- ── Cards ── -->
  <div class="menu-grid" id="cards-wrapper">
    <?php if (!empty($schedule)):
      foreach ($schedule as $row):
        $greg        = $row['date'];
        $isToday     = ($greg === $today);
        $hijriDisplay= $row['hijri_date'] ?? (($row['hijri_day'] ?? '') ? $row['hijri_day'] . ' (Hijri)' : '');
        $menuId      = isset($row['menu']['menu_id']) ? (int)$row['menu']['menu_id'] : 0;
        $menuItems   = isset($row['menu']['items']) && is_array($row['menu']['items']) ? $row['menu']['items'] : [];
        $hasMenu     = ($menuId > 0);
        $assignedTo  = isset($row['menu']['assigned_to']) ? (string)$row['menu']['assigned_to'] : '';
        $miqaats     = isset($row['miqaats']) && is_array($row['miqaats']) ? $row['miqaats'] : [];
        $hasMiqaat   = !empty($miqaats);
        $isHoliday   = !empty($row['is_holiday']);
        $typeTokens  = [];
        if ($hasMenu)    $typeTokens[] = 'menu';
        if ($isHoliday)  $typeTokens[] = 'holiday';
        if ($hasMiqaat)  $typeTokens[] = 'miqaat';
        if (empty($typeTokens)) $typeTokens[] = 'empty';
        $dataTypes   = implode(' ', $typeTokens);
        $searchParts = [];
        if ($hasMenu) $searchParts[] = implode(' ', $menuItems);
        if ($hasMiqaat) { foreach ($miqaats as $mq) { if (!empty($mq['name'])) $searchParts[] = $mq['name']; } }
        if ($isHoliday) $searchParts[] = 'holiday';
        $searchText  = strtolower(implode(' ', $searchParts));
        // card modifier class
        $scMod = $hasMenu ? 'sc-menu' : ($isHoliday ? 'sc-holiday' : ($hasMiqaat ? 'sc-miqaat' : 'sc-empty'));
    ?>
    <div class="schedule-card <?= $scMod ?> <?= $isToday ? 'sc-today' : '' ?>"
         data-types="<?= htmlspecialchars($dataTypes) ?>"
         data-date="<?= htmlspecialchars($greg) ?>"
         data-text="<?= htmlspecialchars($searchText) ?>"
         id="card-<?= htmlspecialchars($greg) ?>">

      <!-- header -->
      <div class="sc-header">
        <div>
          <div class="sc-greg"><?= date('d M Y', strtotime($greg)) ?></div>
          <span class="sc-hijri"><?= htmlspecialchars($hijriDisplay) ?></span>
        </div>
        <div class="sc-chips">
          <?php if ($isToday):  ?><span class="type-chip chip-today"><i class="fa fa-bolt"></i> Today</span><?php endif; ?>
          <?php if ($hasMenu):  ?><span class="type-chip chip-menu"><i class="fa fa-cutlery"></i> Menu</span><?php endif; ?>
          <?php if ($isHoliday):?><span class="type-chip chip-holiday"><i class="fa fa-sun-o"></i> Holiday</span><?php endif; ?>
          <?php if ($hasMiqaat):?><span class="type-chip chip-miqaat"><i class="fa fa-star-o"></i> Miqaat</span><?php endif; ?>
          <?php if (!$hasMenu && !$isHoliday && !$hasMiqaat): ?><span class="type-chip chip-empty"><i class="fa fa-minus"></i> Empty</span><?php endif; ?>
        </div>
      </div>

      <!-- body -->
      <div class="sc-body">
        <div class="sc-weekday"><i class="fa fa-calendar" style="margin-right:4px;"></i><?= date('l', strtotime($greg)) ?></div>

        <?php if ($hasMenu): ?>
          <div class="sc-section-label">Menu Items</div>
          <?php if (!empty($menuItems)): ?>
            <div class="food-chips-wrap">
              <?php foreach ($menuItems as $it): ?>
                <span class="food-chip"><i class="fa fa-chevron-right"></i><?= htmlspecialchars($it) ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="food-soon">Menu will be posted soon</div>
          <?php endif; ?>
          <div class="sc-section-label">Assigned To</div>
          <div class="sc-assigned"><?= htmlspecialchars(!empty($assignedTo) ? $assignedTo : '—', ENT_QUOTES) ?></div>
        <?php endif; ?>

        <?php if ($hasMiqaat): ?>
          <div class="sc-section-label">Miqaats</div>
          <ul class="miqaat-list">
            <?php foreach ($miqaats as $mq): ?>
              <li><i class="fa fa-star-o"></i><?= htmlspecialchars($mq['name']) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <?php if ($isHoliday): ?>
          <div class="sc-section-label">Status</div>
          <span class="holiday-row"><i class="fa fa-sun-o"></i> Holiday</span>
        <?php endif; ?>

        <?php if (!$hasMenu && !$hasMiqaat && !$isHoliday): ?>
          <div class="sc-empty-text">No menu / miqaat info</div>
        <?php endif; ?>
      </div>

    </div>
    <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state" style="grid-column:1/-1;">
        <i class="fa fa-calendar-times-o"></i>
        <p>No menu / miqaat records for this Hijri month.</p>
        <?php if (isset($menus_debug)): ?>
          <small style="color:var(--text-3);font-size:.72rem;">Hijri=<?= htmlspecialchars($menus_debug['selected_hijri'] ?? '') ?></small>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="result-count" id="row-count"></div>
</div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
(function () {
  const typeSel    = document.getElementById('filter-type');
  const textInput  = document.getElementById('filter-text');
  const dateInput  = document.getElementById('filter-date');
  const clearBtn   = document.getElementById('filter-clear');
  const wrapper    = document.getElementById('cards-wrapper');
  const countEl    = document.getElementById('row-count');

  function norm(v) { return (v || '').toString().toLowerCase(); }

  function applyFilters() {
    const ft = typeSel.value, txt = norm(textInput.value), d = dateInput.value;
    let visible = 0;
    wrapper.querySelectorAll('[data-types]').forEach(card => {
      let show = true;
      const types = (card.getAttribute('data-types') || '').split(/\s+/);
      if (ft && types.indexOf(ft) === -1) show = false;
      if (show && txt) { const dataText = card.getAttribute('data-text'); if (!dataText || dataText.indexOf(txt) === -1) show = false; }
      if (show && d && card.getAttribute('data-date') !== d) show = false;
      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    countEl.innerHTML = 'Showing <span>' + visible + '</span> card' + (visible !== 1 ? 's' : '');
  }

  [typeSel, textInput, dateInput].forEach(el => el.addEventListener('input', applyFilters));

  clearBtn.addEventListener('click', () => {
    typeSel.value = ''; textInput.value = ''; dateInput.value = '';
    applyFilters(); scrollToday();
  });

  function scrollToday() {
    const todayCard = document.querySelector('.sc-today');
    if (todayCard) { setTimeout(() => todayCard.scrollIntoView({ behavior: 'smooth', block: 'center' }), 120); }
  }

  applyFilters();
  scrollToday();
})();
</script>