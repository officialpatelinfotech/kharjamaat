<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2:  #5a5244;  --text-3:  #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg:  #fef2f2;  --red-border: #fecaca;
    --blue:       #1d4ed8; --blue-bg: #eff6ff;  --blue-border: #bfdbfe;
    --amber:      #b45309; --amber-bg: #fffbeb; --amber-border: #fde68a;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); padding-bottom: 90px; }

  /* ── Page header ── */
  .page-header-wrap { position: relative; display: flex; align-items: center; justify-content: center; min-height: 44px; margin-bottom: 6px; }
  .btn-back-nav { position: absolute; left: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 22px; }

  /* ── Month navigator ── */
  .month-nav { display: flex; align-items: center; justify-content: space-between; background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 14px 20px; margin-bottom: 16px; }
  .month-nav-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface-2); color: var(--text-2); font-size: 13px; text-decoration: none; transition: all .15s; flex-shrink: 0; }
  .month-nav-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .month-nav-center { flex: 1; text-align: center; padding: 0 12px; }
  .month-nav-label { font-family: 'Literata', Georgia, serif; font-size: 1.08rem; font-weight: 600; color: var(--text-1); display: block; }
  .month-nav-sub   { font-size: 0.63rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); display: block; margin-top: 1px; }

  /* view menu link */
  .view-menu-link { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--gold); text-decoration: none; padding: 4px 12px; border-radius: 40px; border: 1px solid rgba(184,134,11,.3); background: var(--gold-muted); transition: all .15s; margin-bottom: 20px; }
  .view-menu-link:hover { background: var(--gold); color: #fff; border-color: var(--gold); text-decoration: none; }

  /* ── Day cards grid ── */
  .day-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 24px; }
  @media (max-width: 900px) { .day-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 560px) { .day-grid { grid-template-columns: 1fr; gap: 10px; } }

  /* ── Day card ── */
  .day-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    display: flex; flex-direction: column; overflow: hidden;
    transition: box-shadow .18s, border-color .18s;
  }
  .day-card:hover { box-shadow: var(--shadow); border-color: rgba(184,134,11,.3); }
  .day-card::before { content: ''; display: block; height: 3px; background: var(--border); transition: background .2s; }
  .day-card.has-menu::before    { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .day-card.no-menu-day::before { background: linear-gradient(90deg, var(--text-3), #c4b49a); }
  .day-card.wants-thali::before { background: linear-gradient(90deg, var(--green), #4cc790); }

  /* card header band */
  .day-card-top { padding: 10px 14px 9px; background: var(--surface-2); border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; }
  .day-greg { font-size: 0.92rem; font-weight: 700; color: var(--text-1); line-height: 1.2; }
  .day-hijri { font-size: 0.68rem; font-weight: 700; color: var(--gold-deep); margin-top: 2px; display: block; }
  .day-weekday { font-size: 0.7rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); text-align: right; padding-top: 2px; }

  /* card body */
  .day-card-body { padding: 10px 14px; flex: 1; }
  .menu-label { font-size: 0.6rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 3px; }
  .menu-items-text { font-size: 0.8rem; font-weight: 500; color: var(--text-2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .no-thali-label { font-size: 0.75rem; font-weight: 700; color: var(--red); }
  .assigned-label { font-size: 0.6rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-top: 8px; margin-bottom: 2px; }
  .assigned-value { font-size: 0.78rem; color: var(--text-2); font-weight: 500; }

  /* card footer – selects */
  .day-card-footer { padding: 10px 14px 12px; border-top: 1px solid var(--border); background: var(--bg); display: flex; gap: 10px; }
  .day-field { flex: 1; }
  .day-field-label { font-size: 0.6rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); margin-bottom: 5px; display: block; }

  /* themed selects */
  .day-select {
    width: 100%; height: 40px; padding: 0 32px 0 10px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 10px center;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem; font-weight: 500; color: var(--text-1);
    appearance: none; -webkit-appearance: none; box-sizing: border-box;
    transition: border-color .2s, box-shadow .2s;
  }
  .day-select:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }
  .day-select.want-yes { border-color: var(--green); background-color: var(--green-bg); color: var(--green); font-weight: 700; }
  .day-select.want-no  { color: var(--text-3); }

  /* ── Floating save button ── */
  .floating-save {
    position: fixed; right: 16px; bottom: 16px; z-index: 1200;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: var(--radius-sm); border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem; font-weight: 700;
    box-shadow: 0 4px 16px rgba(184,134,11,.4); transition: all .18s; cursor: pointer;
  }
  .floating-save:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); box-shadow: 0 6px 20px rgba(184,134,11,.5); transform: translateY(-2px); }
  @media (max-width: 575px) { .floating-save { left: 12px; right: 12px; bottom: 12px; justify-content: center; } }
</style>

<div class="container margintopcontainer pt-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">FMB Thaali Signup</h1>
  </div>
  <p class="page-sub">Choose your thaali for each day</p>
  <hr class="section-divider">

  <?php
    $total_days   = (isset($signup_days)  && is_array($signup_days))  ? count($signup_days)  : 0;
    $signed_count = 0;
    if (isset($signup_data) && is_array($signup_data)) {
      $signed_count = count(array_filter($signup_data, fn($it) => isset($it['want_thali']) && (string)$it['want_thali'] === '1'));
    }
  ?>

  <!-- ── Month navigator ── -->
  <div class="month-nav">
    <a class="month-nav-btn"
       href="<?php echo base_url('accounts/fmbweeklysignup') . '?hijri=' . htmlspecialchars($prev_hijri ?? '', ENT_QUOTES); ?>"
       aria-label="Previous month"><i class="fa fa-chevron-left"></i></a>
    <div class="month-nav-center">
      <span class="month-nav-label"><?php echo htmlspecialchars(($hijri_month_name ?? '') . ' ' . ($hijri_year ?? ''), ENT_QUOTES); ?></span>
      <span class="month-nav-sub">Hijri Month</span>
    </div>
    <a class="month-nav-btn"
       href="<?php echo base_url('accounts/fmbweeklysignup') . '?hijri=' . htmlspecialchars($next_hijri ?? '', ENT_QUOTES); ?>"
       aria-label="Next month"><i class="fa fa-chevron-right"></i></a>
  </div>

  <!-- View menu -->
  <div class="text-center mb-4">
    <a href="<?php echo base_url('accounts/viewmenu') . '?hijri=' . htmlspecialchars($selected_hijri ?? ($hijri_year . '-' . $hijri_month), ENT_QUOTES); ?>"
       target="_blank" class="view-menu-link">
      <i class="fa fa-list"></i> View Menu
    </a>
  </div>

  <!-- ── Day cards ── -->
  <form method="post" action="<?php echo base_url('accounts/savefmbsignup') ?>" id="signup-form">
    <div class="day-grid">
      <?php foreach ($all_days as $day):
        $hasMenu    = !empty($day['menu_items']);
        $wantsThali = ($day['want_thali'] !== null && (string)$day['want_thali'] === '1');
        $cardClass  = $wantsThali ? 'wants-thali' : ($hasMenu ? 'has-menu' : 'no-menu-day');
      ?>
      <div class="day-card <?= $cardClass ?>">

        <!-- top band -->
        <div class="day-card-top">
          <div>
            <div class="day-greg"><?= date('d M Y', strtotime(htmlspecialchars($day['greg_date'], ENT_QUOTES))) ?></div>
            <span class="day-hijri"><?= htmlspecialchars($day['hijri_date'], ENT_QUOTES) ?></span>
          </div>
          <div class="day-weekday"><?= htmlspecialchars($day['weekday'], ENT_QUOTES) ?></div>
          <input type="hidden" name="date[]" value="<?= $day['greg_date'] ?>">
        </div>

        <!-- menu info -->
        <div class="day-card-body">
          <?php if ($hasMenu): ?>
            <div class="menu-label">Menu</div>
            <div class="menu-items-text" title="<?= htmlspecialchars(implode(', ', $day['menu_items']), ENT_QUOTES) ?>">
              <?= htmlspecialchars(implode(', ', $day['menu_items']), ENT_QUOTES) ?>
            </div>
          <?php else: ?>
            <div class="no-thali-label"><i class="fa fa-ban" style="margin-right:4px;"></i>No Thaali Day</div>
          <?php endif; ?>

          <?php if (!empty($day['menu_id'])): ?>
            <div class="assigned-label">Assigned To</div>
            <div class="assigned-value"><?= htmlspecialchars(!empty($day['assigned_to']) ? $day['assigned_to'] : '-', ENT_QUOTES) ?></div>
          <?php endif; ?>
        </div>

        <!-- selects -->
        <div class="day-card-footer">
          <div class="day-field">
            <label class="day-field-label">Want Thali?</label>
            <select name="want-thali[]"
              class="day-select thali-want-select <?= $wantsThali ? 'want-yes' : 'want-no' ?>">
              <option value="0" <?= ($day['want_thali'] !== null && (string)$day['want_thali'] === '0') ? 'selected' : '' ?>>No</option>
              <option value="1" <?= $wantsThali ? 'selected' : '' ?>>Yes</option>
            </select>
          </div>
          <div class="day-field thali-size-field" <?= !$wantsThali ? 'style="display:none"' : '' ?>>
            <label class="day-field-label">Size</label>
            <select name="thali_size[]" class="day-select thali-size-select">
              <option value="">--</option>
              <?php foreach (['Big','Double Big','Triple Big','Medium','Double Medium','China','Double China'] as $sz): ?>
                <option value="<?= $sz ?>" <?= ($day['thali_size'] === $sz) ? 'selected' : '' ?>><?= $sz ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

      </div><!-- /.day-card -->
      <?php endforeach; ?>
    </div><!-- /.day-grid -->

    <!-- hidden real submit (triggered by floating button) -->
    <button type="submit" id="submit-btn" style="display:none !important;"></button>
  </form>

</div><!-- /.container -->

<!-- ── Floating Save ── -->
<button type="button" id="floating-save-btn" class="floating-save">
  <i class="fa fa-check"></i> Save Sign Up
</button>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
  // Floating button submits the form
  (function () {
    var floatBtn = document.getElementById('floating-save-btn');
    var form     = document.getElementById('signup-form');
    if (floatBtn && form) {
      floatBtn.addEventListener('click', function (e) { e.preventDefault(); form.submit(); });
    }
  })();

  $(document).ready(function () {
    // Colour select on change + show/hide size field
    function updateWantSelect($sel) {
      var val = $sel.val();
      $sel.removeClass('want-yes want-no').addClass(val === '1' ? 'want-yes' : 'want-no');
      var $card = $sel.closest('.day-card');
      $card.removeClass('wants-thali has-menu no-menu-day');
      if (val === '1') {
        $card.addClass('wants-thali');
        $card.find('.thali-size-field').show();
      } else {
        // restore original class
        var hasMenu = $card.find('.menu-items-text').length > 0;
        $card.addClass(hasMenu ? 'has-menu' : 'no-menu-day');
        $card.find('.thali-size-field').hide();
        $card.find('.thali-size-select').val('');
      }
    }

    $('.thali-want-select').on('change', function () { updateWantSelect($(this)); });
    // init (size field visibility already set via PHP inline style, just update colours)
    $('.thali-want-select').each(function () {
      var val = $(this).val();
      $(this).removeClass('want-yes want-no').addClass(val === '1' ? 'want-yes' : 'want-no');
    });

    // Form validation
    $("form").submit(function (e) {
      var allValid = true;
      $('.thali-want-select').each(function () {
        if ($(this).val() === '1') {
          var sz = $(this).closest('.day-card-footer').find('.thali-size-select').val();
          if (!sz) {
            allValid = false;
            alert("Please select a thali size for all 'Yes' thali selections.");
            return false;
          }
        }
      });
      if (!allValid) e.preventDefault();
    });
  });
</script>