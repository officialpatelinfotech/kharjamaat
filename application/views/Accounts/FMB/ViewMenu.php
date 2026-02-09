<div class="container margintopcontainer pt-5">
  <div class="d-flex justify-content-center align-items-center mb-3">
    <h4 class="heading text-center mb-0">FMB Menu</h4>
  </div>
  <?php $schedule = isset($month_schedule) ? $month_schedule : (isset($menus) ? $menus : []); $today = date('Y-m-d'); ?>
  <div class="card mb-3 shadow-sm">
    <div class="card-body py-2">
      <form id="schedule-filters" class="row g-2 align-items-end">
        <div class="col-sm-3">
          <label class="small mb-1">Type</label>
          <select class="form-control form-control-sm" id="filter-type">
            <option value="">All</option>
            <option value="menu">Menu</option>
            <option value="holiday">Holiday</option>
            <option value="miqaat">Miqaat</option>
          </select>
        </div>
        <div class="col-sm-4">
          <label class="small mb-1">Search Items / Name</label>
          <input type="text" id="filter-text" class="form-control form-control-sm" placeholder="Search..." />
        </div>
        <div class="col-sm-3">
          <label class="small mb-1">Date</label>
          <input type="date" id="filter-date" class="form-control form-control-sm" />
        </div>
        <div class="col-sm-2 d-flex gap-2 mt-2">
          <button type="button" id="filter-clear" class="btn btn-outline-secondary btn-sm w-100">Reset</button>
        </div>
      </form>
    </div>
  </div>
  <div class="d-flex justify-content-center align-items-center mb-3 hijri-switcher">
    <a class="btn btn-sm btn-outline-secondary prev-switch mr-2" href="<?php echo base_url('accounts/viewmenu') . '?hijri=' . htmlspecialchars($prev_hijri ?? '', ENT_QUOTES); ?>" title="Previous Hijri Month" aria-label="Previous month"><i class="fa fa-chevron-left"></i></a>
    <h6 class="text-center text-info mb-0 hijri-label"><?php echo htmlspecialchars(($hijri_month_name ?? '') . ' ' . ($hijri_year ?? ''), ENT_QUOTES); ?></h6>
    <a class="btn btn-sm btn-outline-secondary next-switch ml-2" href="<?php echo base_url('accounts/viewmenu') . '?hijri=' . htmlspecialchars($next_hijri ?? '', ENT_QUOTES); ?>" title="Next Hijri Month" aria-label="Next month"><i class="fa fa-chevron-right"></i></a>
  </div>
  
  <div id="cards-wrapper" class="row g-5" style="max-height:70vh; overflow:auto;">
    <?php if(!empty($schedule)): ?>
      <?php foreach($schedule as $i => $row):
        $greg = $row['date'];
        $isToday = ($greg === $today);
        $hijriDisplay = $row['hijri_date'] ?? (($row['hijri_day'] ?? '') ? ($row['hijri_day'].' (Hijri)') : '');
        $menuId = isset($row['menu']['menu_id']) ? (int)$row['menu']['menu_id'] : 0;
        $menuItems = isset($row['menu']['items']) && is_array($row['menu']['items']) ? $row['menu']['items'] : [];
        $hasMenu = ($menuId > 0);
        $assignedTo = isset($row['menu']['assigned_to']) ? (string)$row['menu']['assigned_to'] : '';
        $miqaats = isset($row['miqaats']) && is_array($row['miqaats']) ? $row['miqaats'] : [];
        $hasMiqaat = !empty($miqaats);
        $isHoliday = !empty($row['is_holiday']);
        $typeTokens = [];
        if($hasMenu) $typeTokens[]='menu';
        if($isHoliday) $typeTokens[]='holiday';
        if($hasMiqaat) $typeTokens[]='miqaat';
        if(empty($typeTokens)) $typeTokens[]='empty';
        $dataTypes = implode(' ', $typeTokens);
        $searchParts = [];
        if($hasMenu) $searchParts[] = implode(' ', $menuItems);
        if($hasMiqaat){ foreach($miqaats as $mq){ if(!empty($mq['name'])) $searchParts[] = $mq['name']; } }
        if($isHoliday) $searchParts[] = 'holiday';
        $searchText = strtolower(implode(' ', $searchParts));
      ?>
        <div class="col-12 col-md-6 col-lg-3" data-types="<?php echo htmlspecialchars($dataTypes); ?>" data-date="<?php echo htmlspecialchars($greg); ?>" data-text="<?php echo htmlspecialchars($searchText); ?>" id="card-<?php echo htmlspecialchars($greg); ?>">
          <div class="schedule-card card h-100 <?php echo $isToday ? 'today-card' : ''; ?>">
            <div class="card-header py-2">
              <div class="d-flex w-100 justify-content-between align-items-start">
                <div class="date-block">
                  <div class="greg-date d-flex align-items-center gap-2">
                    <?php echo date('d M Y', strtotime($greg)); ?>
                  </div>
                  <div class="hijri-date"><?php echo htmlspecialchars($hijriDisplay); ?></div>
                </div>
                <div class="text-end d-flex flex-column align-items-end gap-1 type-chip-stack">
                  <?php if($hasMenu): ?><span class="type-chip chip-success"><i class="fa fa-cutlery"></i> Menu</span><?php endif; ?>
                  <?php if($isHoliday): ?><span class="type-chip chip-warning"><i class="fa fa-sun-o"></i> Holiday</span><?php endif; ?>
                  <?php if($hasMiqaat): ?><span class="type-chip chip-info"><i class="fa fa-star-o"></i> Miqaat</span><?php endif; ?>
                  <?php if(!$hasMenu && !$isHoliday && !$hasMiqaat): ?><span class="type-chip chip-secondary"><i class="fa fa-circle"></i> Empty</span><?php endif; ?>
                  <?php if($isToday): ?><span class="today-pill mt-1"><i class="fa fa-bolt"></i> Today</span><?php endif; ?>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="weekday mb-2"><i class="fa fa-calendar"></i> <?php echo date('l', strtotime($greg)); ?></div>
              <?php if($hasMenu): ?>
                <div class="section-heading small text-uppercase fw-bold mb-1 text-success">Menu Items</div>
                <?php if(!empty($menuItems)): ?>
                  <div class="items-flow mb-2">
                    <?php foreach($menuItems as $it): ?>
                      <span class="food-chip"><?php echo htmlspecialchars($it); ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="text-muted fst-italic mb-2">Menu will be posted soon</div>
                <?php endif; ?>

                <div class="section-heading small text-uppercase fw-bold mb-1 text-muted">Assigned To</div>
                <div class="assigned-to mb-2">
                  <?php echo htmlspecialchars(!empty($assignedTo) ? $assignedTo : '-', ENT_QUOTES); ?>
                </div>
              <?php endif; ?>
              <?php if($hasMiqaat): ?>
                <div class="section-heading small text-uppercase fw-bold mb-1 text-primary">Miqaats</div>
                <ul class="list-unstyled mb-2 miqaat-list">
                  <?php foreach($miqaats as $mq): ?>
                    <li class="miqaat-text"><i class="fa fa-star-o"></i> <?php echo htmlspecialchars($mq['name']); ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <?php if($isHoliday): ?>
                <div class="holiday-text mb-1"><i class="fa fa-sun-o"></i> Holiday</div>
              <?php endif; ?>
              <?php if(!$hasMenu && !$hasMiqaat && !$isHoliday): ?>
                <div class="text-muted fst-italic">No menu / miqaat / holiday info</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted">No menu / miqaat records for Hijri month.<br/>
        <?php if(isset($menus_debug)): ?>
          <small class="d-block mt-2">Debug: Hijri=<?php echo htmlspecialchars($menus_debug['selected_hijri'] ?? '', ENT_QUOTES); ?> (Year=<?php echo htmlspecialchars($menus_debug['year'] ?? '', ENT_QUOTES); ?>, Month=<?php echo htmlspecialchars($menus_debug['month'] ?? '', ENT_QUOTES); ?>)</small>
          <small class="text-secondary">Ensure entries exist in tables: hijri_calendar (dates) & menu (date range).</small>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="mt-2 small text-muted" id="row-count"></div>
</div>
<script>
  (function(){
    const typeSel = document.getElementById('filter-type');
    const textInput = document.getElementById('filter-text');
    const dateInput = document.getElementById('filter-date');
    const clearBtn = document.getElementById('filter-clear');
    const cardsWrapper = document.getElementById('cards-wrapper');
    const countEl = document.getElementById('row-count');
    function norm(v){ return (v||'').toString().toLowerCase(); }
    function applyFilters(){
      const ft = typeSel.value; const txt = norm(textInput.value); const d = dateInput.value; let visible = 0;
      cardsWrapper.querySelectorAll('[data-types]')?.forEach(card => {
        let show = true;
        const types = (card.getAttribute('data-types')||'').split(/\s+/);
        if (ft && types.indexOf(ft) === -1) show = false;
        if (show && txt){ const dataText = card.getAttribute('data-text'); if(!dataText || dataText.indexOf(txt)===-1) show = false; }
        if (show && d && card.getAttribute('data-date') !== d) show = false;
        card.style.display = show ? '' : 'none'; if(show) visible++;
      });
      countEl.textContent = visible + ' card(s) shown';
    }
    [typeSel, textInput, dateInput].forEach(el => el.addEventListener('input', applyFilters));
    clearBtn.addEventListener('click', () => { typeSel.value=''; textInput.value=''; dateInput.value=''; applyFilters(); scrollToday(); });
    function scrollToday(){
      const todayCard = document.querySelector('.today-card');
      if(todayCard){
        setTimeout(() => { todayCard.scrollIntoView({behavior:'smooth', block:'center'}); }, 120);
      }
    }
    applyFilters();
    scrollToday();
  })();
</script>
<style>
  :root { --card-radius:12px; --chip-bg:#f1f3f5; --chip-radius:14px; --grad-menu:linear-gradient(135deg,#e0f8ec,#ffffff); --grad-holiday:linear-gradient(135deg,#fff5e0,#ffffff); --grad-miqaat:linear-gradient(135deg,#e8f0ff,#ffffff); }
  #cards-wrapper { padding-right:4px; }
  /* Increase vertical & horizontal gap (works with Bootstrap 5) */
  #cards-wrapper.row { --bs-gutter-x:2rem; --bs-gutter-y:2rem; }
  /* Fallback for older Bootstrap: add extra bottom margin on columns */
  #cards-wrapper > [class*='col-'] { margin-bottom:2rem; }
  .schedule-card { border:1px solid #e5e7eb; border-radius:var(--card-radius); overflow:hidden; position:relative; background:#fff; display:flex; flex-direction:column; }
  .schedule-card.type-menu { background:var(--grad-menu); }
  .schedule-card.type-holiday { background:var(--grad-holiday); }
  .schedule-card.type-miqaat { background:var(--grad-miqaat); }
  .schedule-card .card-header { background:transparent; border-bottom:none; }
  .schedule-card .greg-date { font-weight:600; font-size:.85rem; }
  .schedule-card .hijri-date { font-size:.65rem; opacity:.7; }
  .schedule-card .weekday { font-size:.7rem; text-transform:uppercase; letter-spacing:.5px; color:#666; font-weight:500; }
  .schedule-card:hover { box-shadow:0 4px 18px -4px rgba(0,0,0,.12); transform:translateY(-3px); transition:.25s; }
  .type-chip { display:inline-block; background:#111827; color:#fff; padding:2px 8px; font-size:.55rem; border-radius:999px; font-weight:600; text-transform:uppercase; letter-spacing:.7px; }
  .chip-success { background:#1e7e34; }
  .chip-warning { background:#d48806; }
  .chip-info { background:#0d6efd; }
  .chip-secondary { background:#6c757d; }
  .today-badge { display:none; }
  .items-flow { display:flex; gap:6px; flex-wrap:wrap; }
  .food-chip { background:var(--chip-bg); padding:4px 8px; border-radius:var(--chip-radius); font-size:.62rem; line-height:1.1; font-weight:500; position:relative; }
  .food-chip:before { content:'\f105'; font-family:'Font Awesome 5 Free'; font-weight:900; margin-right:4px; color:#bbb; }
  .type-chip-stack .type-chip { width: max-content; }
  .section-heading { letter-spacing:.5px; font-size:.55rem !important; opacity:.8; }
  .miqaat-list li { font-size:.65rem; }
  .holiday-text, .miqaat-text { font-size:.7rem; font-weight:600; display:flex; align-items:center; gap:4px; }
  .holiday-text i, .miqaat-text i { font-size:.75rem; }
  .assigned-to { font-size: 0.95rem; font-weight: 400; line-height: 1.25; }
  .today-card { box-shadow:0 0 0 2px rgba(13,110,253,.35),0 6px 20px -6px rgba(0,0,0,.25); }
  .today-pill { background:linear-gradient(135deg,#0d6efd,#5aa2ff); color:#fff; font-size:.55rem; padding:2px 8px; border-radius:20px; display:inline-flex; gap:4px; align-items:center; font-weight:600; letter-spacing:.5px; position:relative; }
  .today-pill:before { content:''; position:absolute; inset:0; border-radius:inherit; padding:1px; background:linear-gradient(135deg,#fff,#b3d6ff,#fff); -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
  @keyframes pulseToday { 0% { box-shadow:0 0 0 0 rgba(13,110,253,.55);} 70% { box-shadow:0 0 0 8px rgba(13,110,253,0);} 100% { box-shadow:0 0 0 0 rgba(13,110,253,0);} }
  .today-pill { animation:pulseToday 2.2s ease-in-out infinite; }
  .schedule-card .card-body { flex:1 1 auto; }
  .schedule-card i.fa { opacity:.75; }
  .schedule-card.type-menu .type-chip { background:#198754; }
  .schedule-card.type-holiday .type-chip { background:#e69500; }
  .schedule-card.type-miqaat .type-chip { background:#0d6efd; }
  .schedule-card.type-menu .food-chip { background:#e8f9ef; }
  .schedule-card.type-holiday .food-chip { background:#fff2d9; }
  .schedule-card.type-miqaat .food-chip { background:#e4ecff; }
  #cards-wrapper .schedule-card { transition:box-shadow .25s, transform .25s; }
  @media (max-width:575.98px){ .food-chip { font-size:.55rem; } }
  /* Hijri month switcher styling */
  .hijri-switcher { gap: 0.5rem; }
  .hijri-switcher .prev-switch, .hijri-switcher .next-switch {
    width: 40px; height: 36px; padding: 0; display:flex; align-items:center; justify-content:center;
  }
  .hijri-switcher .hijri-label { margin: 0 6px; font-size: 0.95rem; }
  @media (max-width: 575.98px) {
    .hijri-switcher { width: 100%; padding: 0 12px; box-sizing: border-box; }
    .hijri-switcher .prev-switch, .hijri-switcher .next-switch { width: 36px; height: 34px; }
    .hijri-switcher .hijri-label { font-size: 0.9rem; text-align:center; }
  }
</style>
<style>
  /* Mobile-only: increase Dates, Day and Menu font sizes for readability */
  @media (max-width: 575.98px) {
    .schedule-card .greg-date { font-size: 1.05rem !important; }
    .schedule-card .hijri-date { font-size: 0.95rem !important; }
    .schedule-card .weekday { font-size: 1rem !important; }
    .section-heading { font-size: 0.85rem !important; }
    .food-chip { font-size: 0.95rem !important; padding: 6px 8px !important; }
    .items-flow { gap: 8px; }
  }
</style>