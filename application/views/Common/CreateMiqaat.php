<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap"
  rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
  :root {
    --gold: #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --gold-border: rgba(230, 200, 74, .35);
    --bg: #faf7f0;
    --surface: #ffffff;
    --surface-2: #f7f4ec;
    --border: #e8e0cc;
    --border-light: #f0ece0;
    --text-1: #1a1610;
    --text-2: #5a5244;
    --text-3: #9c8f7a;
    --green: #1a6645;
    --green-bg: #eaf4ee;
    --red: #b91c1c;
    --red-bg: #fef2f2;
    --blue: #1d4ed8;
    --blue-bg: #eff6ff;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
    --shadow: 0 4px 16px rgba(0, 0, 0, .07), 0 1px 4px rgba(0, 0, 0, .04);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, .10), 0 2px 8px rgba(0, 0, 0, .05);
    --radius: 16px;
    --radius-sm: 10px;
  }

  #cmApp,
  #cmApp *,
  #cmApp *::before,
  #cmApp *::after {
    box-sizing: border-box;
  }

  #cmApp {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
    background: var(--bg);
    min-height: 100vh;
    padding-top: 57px;
  }

  /* ── Compact page header (horizontal strip, less height) ── */
  #cmApp .cm-header {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: 14px;
    padding: 12px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: relative;
    overflow: hidden;
  }

  #cmApp .cm-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }

  #cmApp .cm-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 1;
  }

  #cmApp .cm-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: rgba(255, 255, 255, .15);
    border: 1px solid rgba(255, 255, 255, .3);
    color: #fff;
    text-decoration: none;
    transition: background .15s;
    flex-shrink: 0;
  }

  #cmApp .cm-back-btn:hover {
    background: rgba(255, 255, 255, .28);
    color: #fff;
    text-decoration: none;
  }

  #cmApp .cm-header-title {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  #cmApp .cm-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, .2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    color: #fff;
    flex-shrink: 0;
  }

  #cmApp .cm-title-text {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.2;
    margin: 0;
  }

  #cmApp .cm-subtitle {
    font-size: .72rem;
    color: rgba(255, 255, 255, .7);
    margin-top: 1px;
  }

  #cmApp .cm-header-badge {
    position: relative;
    z-index: 1;
    background: rgba(255, 255, 255, .15);
    border: 1px solid rgba(255, 255, 255, .25);
    border-radius: 9px;
    padding: 5px 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .75rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
  }

  /* ── Flash messages ── */
  #cmApp .flash {
    padding: 11px 16px;
    border-radius: 10px;
    margin-bottom: 14px;
    font-size: .85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  #cmApp .flash.success {
    background: var(--green-bg);
    color: var(--green);
    border: 1px solid #86efac;
  }

  #cmApp .flash.error {
    background: var(--red-bg);
    color: var(--red);
    border: 1px solid #fca5a5;
  }

  #cmApp .flash.warning {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
  }

  /* ── Form card ── */
  #cmApp .form-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  /* ── Section header inside card ── */
  #cmApp .sec-hd {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 12px 20px;
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border-light);
  }

  #cmApp .sec-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--gold-muted);
    color: var(--gold);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    flex-shrink: 0;
  }

  #cmApp .sec-title {
    font-size: .82rem;
    font-weight: 800;
    color: var(--text-2);
    text-transform: uppercase;
    letter-spacing: .5px;
  }

  /* ── Field rows (same as ViewMember style: label col + input col) ── */
  #cmApp .field-list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  #cmApp .field-row {
    display: flex;
    align-items: stretch;
    border-bottom: 1px solid var(--border-light);
  }

  #cmApp .field-row:last-child {
    border-bottom: none;
  }

  #cmApp .field-row:focus-within {
    background: rgba(184, 134, 11, .03);
  }

  #cmApp .fl-key {
    flex: 0 0 36%;
    max-width: 36%;
    padding: 11px 16px;
    font-size: .72rem;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .3px;
    background: var(--surface-2);
    border-right: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 6px;
    line-height: 1.35;
  }

  #cmApp .fl-key .req {
    color: var(--red);
    font-size: .9rem;
  }

  #cmApp .fl-val {
    flex: 1;
    padding: 7px 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 4px;
  }

  /* ── Inputs ── */
  #cmApp .fl-val input[type="text"],
  #cmApp .fl-val input[type="email"],
  #cmApp .fl-val select,
  #cmApp .fl-val textarea {
    width: 100%;
    height: 36px;
    padding: 0 11px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .84rem;
    color: var(--text-1);
    background: var(--surface-2);
    outline: none;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }

  #cmApp .fl-val input[type="text"]:focus,
  #cmApp .fl-val input[type="email"]:focus,
  #cmApp .fl-val select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184, 134, 11, .12);
    background: var(--surface);
  }

  #cmApp .fl-val select {
    cursor: pointer;
  }

  #cmApp .fl-val .hint {
    font-size: .7rem;
    color: var(--text-3);
    margin-top: 3px;
  }

  /* ── Hijri date display ── */
  #cmApp .hijri-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: .76rem;
    font-weight: 700;
    background: var(--gold-muted);
    color: var(--gold);
    border: 1px solid var(--gold-border);
    margin-top: 4px;
  }

  /* ── Hijri calendar section ── */
  #cmApp .hijri-wrapper {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--surface);
  }

  #cmApp .hijri-cal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--gold-muted);
    border-bottom: 1px solid var(--gold-border);
  }

  #cmApp .hijri-cal-title {
    font-size: .84rem;
    font-weight: 800;
    color: var(--gold);
  }

  #cmApp .hijri-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    cursor: pointer;
    font-size: .8rem;
    transition: all .15s;
  }

  #cmApp .hijri-nav-btn:hover {
    border-color: var(--gold);
    background: var(--gold-muted);
    color: var(--gold);
  }

  #cmApp .hijri-year-sel {
    height: 30px;
    padding: 0 8px;
    border: 1px solid var(--border);
    border-radius: 7px;
    font-size: .8rem;
    background: var(--surface);
    color: var(--text-1);
    outline: none;
  }

  #cmApp .hijri-year-sel:focus {
    border-color: var(--gold);
  }

  #cmApp .hijri-cal-body {
    padding: 10px 12px;
  }

  #cmApp .hijri-week-grid {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  #cmApp .hijri-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
  }

  #cmApp .hijri-head-cell {
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--text-3);
    text-align: center;
    padding: 4px 0;
    letter-spacing: .3px;
  }

  #cmApp .hijri-cell {
    min-height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #cmApp .hijri-cell.empty {
    background: transparent;
  }

  #cmApp .hijri-day {
    width: 100%;
    height: 34px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    color: var(--text-1);
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #cmApp .hijri-day:hover {
    border-color: var(--gold);
    background: var(--gold-muted);
    color: var(--gold);
  }

  #cmApp .hijri-day.active {
    background: linear-gradient(135deg, #b8860b, #c9a227);
    color: #fff;
    border-color: var(--gold);
    box-shadow: 0 2px 8px rgba(184, 134, 11, .3);
  }

  #cmApp .hijri-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    transition: all .15s;
    margin-bottom: 10px;
  }

  #cmApp .hijri-toggle-btn:hover {
    border-color: var(--gold);
    background: var(--gold-muted);
    color: var(--gold);
  }

  #cmApp .hijri-cal-hint {
    font-size: .72rem;
    color: var(--text-3);
    padding: 8px 14px;
    border-top: 1px solid var(--border-light);
  }

  /* ── Assignment type pills ── */
  #cmApp .assign-pill-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 4px 0;
  }

  #cmApp .assign-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 700;
    border: 1.5px solid var(--border);
    background: var(--surface-2);
    color: var(--text-2);
    cursor: pointer;
    transition: all .15s;
  }

  #cmApp .assign-pill:hover {
    border-color: var(--gold);
    background: var(--gold-muted);
    color: var(--gold);
  }

  #cmApp .assign-pill.active {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
  }

  /* ── Autocomplete chips ── */
  #cmApp .chips-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 6px;
  }

  #cmApp .chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .74rem;
    font-weight: 700;
    background: var(--blue-bg);
    color: var(--blue);
    border: 1px solid #93c5fd;
  }

  #cmApp .chip.green {
    background: var(--green-bg);
    color: var(--green);
    border-color: #86efac;
  }

  #cmApp .chip.gold {
    background: var(--gold-muted);
    color: var(--gold);
    border-color: var(--gold-border);
  }

  #cmApp .chip-remove {
    cursor: pointer;
    font-size: .9rem;
    line-height: 1;
    color: inherit;
    background: none;
    border: none;
    padding: 0;
    display: inline-flex;
    align-items: center;
  }

  #cmApp .chip-remove:hover {
    opacity: .7;
  }

  /* ── Current assignments block ── */
  #cmApp .assign-block {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    margin-top: 6px;
  }

  #cmApp .assign-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-light);
    font-size: .83rem;
  }

  #cmApp .assign-item:last-child {
    border-bottom: none;
  }

  #cmApp .assign-item-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
  }

  #cmApp .assign-item-icon.ind {
    background: var(--blue-bg);
    color: var(--blue);
  }

  #cmApp .assign-item-icon.grp {
    background: var(--green-bg);
    color: var(--green);
  }

  #cmApp .assign-item-icon.fnn {
    background: var(--gold-muted);
    color: var(--gold);
  }

  #cmApp .assign-item-name {
    font-weight: 700;
    color: var(--text-1);
  }

  #cmApp .assign-item-sub {
    font-size: .74rem;
    color: var(--text-3);
  }

  #cmApp .edit-assign-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: .76rem;
    font-weight: 700;
    cursor: pointer;
    border: 1.5px solid #93c5fd;
    background: var(--blue-bg);
    color: var(--blue);
    transition: background .15s;
  }

  #cmApp .edit-assign-btn:hover {
    background: #dbeafe;
  }

  /* ── Fala ni Niyaz info ── */
  #cmApp .fnn-info {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    border-radius: var(--radius-sm);
    background: var(--gold-muted);
    border: 1px solid var(--gold-border);
    font-size: .82rem;
    color: var(--text-2);
  }

  #cmApp .fnn-info i {
    color: var(--gold);
    margin-top: 2px;
    flex-shrink: 0;
  }

  /* ── Footer (sticky save) ── */
  #cmApp .form-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px;
    background: var(--surface-2);
    border-top: 1.5px solid var(--border-light);
  }

  #cmApp .btn-save {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 24px;
    border-radius: 10px;
    background: linear-gradient(135deg, #b8860b, #c9a227);
    border: none;
    color: #fff;
    font-size: .88rem;
    font-weight: 800;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    box-shadow: 0 3px 12px rgba(184, 134, 11, .35);
  }

  #cmApp .btn-save:hover {
    opacity: .9;
    transform: translateY(-1px);
  }

  /* ── Responsive ── */
  @media(max-width:600px) {
    #cmApp .fl-key {
      flex: 0 0 40%;
      max-width: 40%;
      font-size: .67rem;
      padding: 9px 11px;
    }

    #cmApp .fl-val {
      padding: 7px 10px;
    }

    #cmApp .fl-val input,
    #cmApp .fl-val select {
      font-size: .8rem;
    }

    #cmApp .cm-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }

    #cmApp .cm-header-badge {
      display: none;
    }
  }

  /* ── Flatpickr overrides ── */
  .flatpickr-input {
    background: var(--surface-2) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: 8px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: .84rem !important;
    color: var(--text-1) !important;
    height: 36px !important;
    padding: 0 11px !important;
  }

  .flatpickr-input:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184, 134, 11, .12) !important;
  }

  .flatpickr-calendar {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    box-shadow: var(--shadow-lg) !important;
  }

  .flatpickr-day.selected,
  .flatpickr-day.selected:hover {
    background: var(--gold) !important;
    border-color: var(--gold) !important;
  }

  .flatpickr-day:hover {
    background: var(--gold-muted) !important;
  }

  .flatpickr-months .flatpickr-month {
    background: var(--gold-muted) !important;
    color: var(--gold) !important;
  }

  .flatpickr-current-month .flatpickr-monthDropdown-months,
  .flatpickr-current-month input.cur-year {
    color: var(--gold) !important;
    font-weight: 700 !important;
  }

  .flatpickr-weekday {
    color: var(--text-3) !important;
    font-weight: 700 !important;
  }

  /* ── jQuery UI autocomplete overrides ── */
  .ui-autocomplete {
    background: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: 12px !important;
    box-shadow: var(--shadow-lg) !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: .84rem !important;
    padding: 4px 0 !important;
  }

  .ui-menu-item-wrapper {
    padding: 9px 14px !important;
    color: var(--text-1) !important;
    transition: background .1s !important;
  }

  .ui-state-active,
  .ui-menu-item-wrapper:hover {
    background: var(--gold-muted) !important;
    color: var(--gold) !important;
    border: none !important;
  }
</style>

<div id="cmApp">
  <div class="container-fluid pt-3 pb-5" style="max-width:780px;">

    <!-- Flash messages -->
    <?php if ($this->session->flashdata('error')): ?>
      <div class="flash error"><i class="fa-solid fa-triangle-exclamation"></i><?= $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
      <div class="flash success"><i class="fa-solid fa-circle-check"></i><?= $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('warning')): ?>
      <div class="flash warning"><i
          class="fa-solid fa-triangle-exclamation"></i><?= $this->session->flashdata('warning'); ?></div>
    <?php endif; ?>

    <!-- ── Compact Header ── -->
    <div class="cm-header">
      <div class="cm-header-left">
        <a href="<?php echo base_url('common/managemiqaat'); ?>" class="cm-back-btn"><i
            class="fa-solid fa-arrow-left"></i></a>
        <div class="cm-header-title">
          <div class="cm-icon-wrap">
            <i class="fa-solid <?php echo (isset($edit_mode) && $edit_mode) ? 'fa-pen-to-square' : 'fa-calendar-plus'; ?>"></i>
          </div>
          <div>
            <div class="cm-title-text"><?php echo (isset($edit_mode) && $edit_mode) ? 'Edit Miqaat' : 'Create Miqaat'; ?></div>
            <div class="cm-subtitle">
              <?php echo (isset($edit_mode) && $edit_mode) ? 'Update miqaat details &amp; assignments' : 'Add a new miqaat to the schedule'; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="cm-header-badge">
        <i class="fa-regular fa-calendar"></i>
        <?php echo (isset($edit_mode) && $edit_mode) ? 'Edit Mode' : 'New Entry'; ?>
      </div>
    </div>

    <!-- ── Form card ── -->
    <div class="form-card">

      <form method="POST"
        action="<?php echo base_url(isset($edit_mode) && $edit_mode ? 'common/update_miqaat' : 'common/add_miqaat'); ?>">
        <?php if (isset($edit_mode) && $edit_mode): ?>
          <input type="hidden" name="miqaat_id"
            value="<?php echo isset($miqaat_id) ? $miqaat_id : (isset($miqaat['id']) ? $miqaat['id'] : ''); ?>">
        <?php endif; ?>

        <!-- ── Date & Hijri ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-calendar-day"></i></span>
          <span class="sec-title">Date</span>
        </div>
        <ul class="field-list">
          <li class="field-row">
            <div class="fl-key">Gregorian Date <span class="req">*</span></div>
            <div class="fl-val">
              <input type="text" id="date" name="date" required
                value="<?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['date'])) ? date('d-m-Y', strtotime($miqaat['date'])) : (isset($date) ? date('d-m-Y', strtotime($date)) : ''); ?>"
                placeholder="DD-MM-YYYY">
              <div id="hijri-date-display" class="hijri-pill" style="display:none;">
                <i class="fa fa-moon"></i><span id="hijri-text"><?php echo isset($hijri_date) ? $hijri_date : ''; ?></span>
              </div>
            </div>
          </li>
          <li class="field-row">
            <div class="fl-key">Hijri Calendar</div>
            <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
              <button type="button" id="toggle-hijri-cal" class="hijri-toggle-btn">
                <i class="fa fa-moon"></i> Show Hijri Calendar
              </button>
              <div id="hijri-selector-wrapper">
                <div class="hijri-wrapper">
                  <div class="hijri-cal-header">
                    <button type="button" id="hijri-prev" class="hijri-nav-btn"><i
                        class="fa fa-chevron-left"></i></button>
                    <div style="display:flex;align-items:center;gap:8px;">
                      <span id="hijri-current" class="hijri-cal-title"></span>
                      <select id="hijri-year-select" class="hijri-year-sel" aria-label="Hijri Year"></select>
                    </div>
                    <button type="button" id="hijri-next" class="hijri-nav-btn"><i
                        class="fa fa-chevron-right"></i></button>
                  </div>
                  <div class="hijri-cal-body">
                    <div id="hijri-calendar"></div>
                  </div>
                  <div class="hijri-cal-hint"><i class="fa fa-info-circle" style="margin-right:5px;"></i>Click a day to
                    auto-fill the Gregorian date above.</div>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <!-- ── Miqaat Details ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-info-circle"></i></span>
          <span class="sec-title">Miqaat Details</span>
        </div>
        <ul class="field-list">
          <li class="field-row">
            <div class="fl-key">Miqaat Name <span class="req">*</span></div>
            <div class="fl-val">
              <input type="text" name="name" id="miqaat-name" required
                value="<?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['name'])) ? htmlspecialchars($miqaat['name'], ENT_QUOTES) : ''; ?>"
                placeholder="e.g. Shab-e-Juma, Eid Milad…">
            </div>
          </li>
          <li class="field-row">
            <div class="fl-key">Miqaat Type <span class="req">*</span></div>
            <div class="fl-val">
              <select name="miqaat_type" id="miqaat-type" required>
                <option value="">-- Select Type --</option>
                <?php foreach (['General', 'Ashara', 'Ladies', 'Shehrullah'] as $t): ?>
                  <option value="<?php echo $t; ?>" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['type']) && $miqaat['type'] == $t) ? 'selected' : ''; ?>>
                    <?php echo $t; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </li>
        </ul>

        <!-- ── Assignment ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-users"></i></span>
          <span class="sec-title">Assignment</span>
        </div>

        <!-- Current assignments (edit mode) -->
        <?php if (isset($edit_mode) && $edit_mode && !empty($miqaat['assignments'])): ?>
          <div style="padding:14px 20px;border-bottom:1px solid var(--border-light);">
            <div
              style="font-size:.72rem;font-weight:800;color:var(--text-3);text-transform:uppercase;letter-spacing:.4px;margin-bottom:10px;">
              Current Assignments</div>
            <div class="assign-block">
              <?php foreach ($miqaat['assignments'] as $asgn): ?>
                <?php if ($asgn['assign_type'] === 'Individual'): ?>
                  <div class="assign-item">
                    <div class="assign-item-icon ind"><i class="fa fa-user"></i></div>
                    <div>
                      <div class="assign-item-name"><?php echo htmlspecialchars($asgn['member_name'] ?? '', ENT_QUOTES); ?></div>
                      <div class="assign-item-sub">Individual &bull; ITS: <?php echo $asgn['member_id'] ?? ''; ?></div>
                    </div>
                  </div>
                <?php elseif ($asgn['assign_type'] === 'Group'): ?>
                  <div class="assign-item">
                    <div class="assign-item-icon grp"><i class="fa fa-users"></i></div>
                    <div style="flex:1;">
                      <div class="assign-item-name"><?php echo htmlspecialchars($asgn['group_name'] ?? '', ENT_QUOTES); ?></div>
                      <div class="assign-item-sub">Group &bull; Leader:
                        <?php echo htmlspecialchars($asgn['group_leader_name'] ?? '', ENT_QUOTES); ?>
                        (<?php echo $asgn['group_leader_id'] ?? ''; ?>)</div>
                      <?php if (!empty($asgn['members'])):
                        $col = $asgn['members'][0]; ?>
                        <div class="assign-item-sub">Co-leader:
                          <?php echo htmlspecialchars($col['name'] ?? $col['first_name'] ?? '', ENT_QUOTES); ?>
                          (<?php echo $col['id'] ?? ''; ?>)</div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
            <button type="button" id="edit-assignments-btn" class="edit-assign-btn" style="margin-top:10px;">
              <i class="fa fa-pencil"></i> Edit Assignment
            </button>
          </div>
        <?php elseif (isset($edit_mode) && $edit_mode && isset($miqaat['assigned_to']) && $miqaat['assigned_to'] === 'Fala ni Niyaz'): ?>
          <div style="padding:14px 20px;border-bottom:1px solid var(--border-light);">
            <div class="assign-block">
              <div class="assign-item">
                <div class="assign-item-icon fnn"><i class="fa fa-star"></i></div>
                <div>
                  <div class="assign-item-name">Fala ni Niyaz</div>
                  <div class="assign-item-sub">Auto-assigned to Umoor FMB members</div>
                </div>
              </div>
            </div>
            <button type="button" id="edit-assignments-btn" class="edit-assign-btn" style="margin-top:10px;">
              <i class="fa fa-pencil"></i> Edit Assignment
            </button>
          </div>
        <?php endif; ?>

        <!-- Assign-to selector -->
        <ul class="field-list" id="assign-to-container" <?php echo (isset($edit_mode) && $edit_mode) ? 'style="display:none;"' : ''; ?>>
          <li class="field-row">
            <div class="fl-key">Assign To</div>
            <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
              <div class="assign-pill-row" id="assign-pills">
                <button type="button" class="assign-pill" data-val="Individual"><i class="fa fa-user"></i>
                  Individual</button>
                <button type="button" class="assign-pill" data-val="Group"><i class="fa fa-users"></i> Sanstha /
                  Group</button>
                <button type="button" class="assign-pill" data-val="Fala ni Niyaz"><i class="fa fa-star"></i> Fala ni
                  Niyaz</button>
              </div>
              <select name="assign_to" id="assign-to" style="display:none;">
                <option value="">--</option>
                <option value="Individual">Individual</option>
                <option value="Group">Sanstha / Group</option>
                <option value="Fala ni Niyaz">Fala ni Niyaz</option>
              </select>
            </div>
          </li>
        </ul>

        <!-- Individual search -->
        <div id="individual-container" style="display:none;">
          <ul class="field-list">
            <li class="field-row">
              <div class="fl-key">Search Individuals</div>
              <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
                <input type="text" id="individuals" placeholder="Type name to search…">
                <div class="autocomplete-display acod-1" style="position:relative;"></div>
                <input type="hidden" name="individual_ids" id="individual-ids"
                  value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    $ids = [];
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Individual')
                        $ids[] = $a['member_id'];
                    }
                    echo implode(',', $ids);
                  } ?>">
                <div class="chips-wrap" id="selected-individuals">
                  <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Individual'): ?>
                        <span class="chip"><?php echo htmlspecialchars($a['member_name'] ?? '', ENT_QUOTES); ?><button
                            class="chip-remove remove-individual" data-id="<?php echo $a['member_id']; ?>">×</button></span>
                      <?php endif;
                    }
                  } ?>
                </div>
                <div class="hint" style="margin-top:5px;">Max 3 individuals</div>
              </div>
            </li>
          </ul>
        </div>

        <!-- Group search -->
        <div id="group-container" style="display:none;">
          <ul class="field-list">
            <li class="field-row">
              <div class="fl-key">Group Name</div>
              <div class="fl-val">
                <input type="text" name="group_name" id="group-name" placeholder="Enter Sanstha / Group name"
                  value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Group')
                        echo htmlspecialchars($a['group_name'], ENT_QUOTES);
                    }
                  } ?>">
              </div>
            </li>
            <li class="field-row">
              <div class="fl-key">Group Leader</div>
              <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
                <input type="text" id="group-leader" placeholder="Search leader…">
                <div class="autocomplete-display acod-2" style="position:relative;"></div>
                <input type="hidden" name="group_leader_id" id="group-leader-id"
                  value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Group')
                        echo $a['group_leader_id'];
                    }
                  } ?>">
                <div class="chips-wrap" id="selected-group-leader">
                  <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Group' && !empty($a['group_leader_name'])): ?>
                        <span class="chip green"><?php echo htmlspecialchars($a['group_leader_name'], ENT_QUOTES); ?><button
                            class="chip-remove remove-group-leader"
                            data-id="<?php echo $a['group_leader_id']; ?>">×</button></span>
                      <?php endif;
                    }
                  } ?>
                </div>
              </div>
            </li>
            <li class="field-row">
              <div class="fl-key">Co-leader</div>
              <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
                <input type="text" id="group-members" placeholder="Search co-leader…">
                <div class="autocomplete-display acod-3" style="position:relative;"></div>
                <input type="hidden" name="group_member_ids" id="group-member-ids"
                  value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Group' && !empty($a['members'])) {
                        $ids = array_map(function ($m) {
                          return $m['id']; }, $a['members']);
                        echo implode(',', $ids);
                      }
                    }
                  } ?>">
                <div class="chips-wrap" id="selected-group-members">
                  <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                    foreach ($miqaat['assignments'] as $a) {
                      if ($a['assign_type'] === 'Group' && !empty($a['members'])) {
                        foreach ($a['members'] as $mem): ?>
                          <span class="chip gold"><?php echo htmlspecialchars($mem['name'], ENT_QUOTES); ?><button
                              class="chip-remove remove-group-member" data-id="<?php echo $mem['id']; ?>">×</button></span>
                        <?php endforeach;
                      }
                    }
                  } ?>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- Fala ni Niyaz info -->
        <div id="fala-ni-niyaz-container" style="display:none;">
          <div style="padding:14px 20px;">
            <div class="fnn-info">
              <i class="fa fa-star-of-life"></i>
              <span>This Miqaat will be automatically assigned to <strong>Umoor FMB</strong> role members. A Raza
                request will be created automatically on save.</span>
            </div>
          </div>
        </div>

        <!-- ── Footer ── -->
        <div class="form-footer">
          <a href="<?php echo base_url('common/managemiqaat'); ?>"
            style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:9px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.84rem;font-weight:700;text-decoration:none;transition:all .15s;"
            onmouseover="this.style.borderColor='var(--gold)';this.style.background='var(--gold-muted)';this.style.color='var(--gold)';"
            onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface)';this.style.color='var(--text-2)';">
            Cancel
          </a>
          <button type="submit" id="save-miqaat-btn" class="btn-save">
            <i class="fa-solid <?php echo (isset($edit_mode) && $edit_mode) ? 'fa-floppy-disk' : 'fa-circle-plus'; ?>"></i>
            <?php echo (isset($edit_mode) && $edit_mode) ? 'Update Miqaat' : 'Save Miqaat'; ?>
          </button>
        </div>
      </form>
    </div>

  </div><!-- /container -->
</div><!-- /#cmApp -->

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const MAX_INDIVIDUAL = 3;

    /* ── Assign pills → hidden select ── */
    var assignHidden = document.getElementById('assign-to');
    var pills = document.querySelectorAll('#assign-pills .assign-pill');
    var indContainer = document.getElementById('individual-container');
    var grpContainer = document.getElementById('group-container');
    var fnnContainer = document.getElementById('fala-ni-niyaz-container');

    function showAssignSection(val) {
      indContainer.style.display = val === 'Individual' ? '' : 'none';
      grpContainer.style.display = val === 'Group' ? '' : 'none';
      fnnContainer.style.display = (val === 'Fala ni Niyaz' || val === 'Fala_ni_Niyaz') ? '' : 'none';
    }

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        pills.forEach(function (p) { p.classList.remove('active'); });
        this.classList.add('active');
        var val = this.getAttribute('data-val');
        assignHidden.value = val;
        showAssignSection(val);
      });
    });

    /* ── Edit assignments toggle ── */
    var editBtn = document.getElementById('edit-assignments-btn');
    var assignContainer = document.getElementById('assign-to-container');
    if (editBtn && assignContainer) {
      editBtn.addEventListener('click', function () {
        assignContainer.style.display = '';
        var curType = "<?php echo isset($miqaat['assignments'][0]['assign_type']) ? $miqaat['assignments'][0]['assign_type'] : ''; ?>";
        if (curType) {
          assignHidden.value = curType;
          pills.forEach(function (p) { p.classList.remove('active'); if (p.getAttribute('data-val') === curType) p.classList.add('active'); });
          showAssignSection(curType);
        }
      });
    }

    /* ── Hijri toggle ── */
    var toggleBtn = document.getElementById('toggle-hijri-cal');
    var hijriWrapper = document.getElementById('hijri-selector-wrapper');

    if (toggleBtn && hijriWrapper) {

      hijriWrapper.style.display = 'none';

      toggleBtn.addEventListener('click', function () {

        const isHidden = window.getComputedStyle(hijriWrapper).display === 'none';

        if (isHidden) {
          hijriWrapper.style.display = 'block';
          toggleBtn.innerHTML =
            '<i class="fa fa-moon"></i> Hide Hijri Calendar';
        } else {
          hijriWrapper.style.display = 'none';
          toggleBtn.innerHTML =
            '<i class="fa fa-moon"></i> Show Hijri Calendar';
        }

      });
    }

    /* ── Flatpickr ── */
    var miqaatDates = [];
    <?php if (isset($miqaat_dates)) {
      $arr = [];
      foreach ($miqaat_dates as $md)
        $arr[] = date('d-m-Y', strtotime($md['date']));
      echo 'miqaatDates=' . json_encode($arr) . ';';
    } ?>

    var fpInstance = flatpickr('#date', {
      dateFormat: 'd-m-Y',
      disable: miqaatDates,
      onChange: function (sel, dateStr) { if (dateStr) fetchHijri(dateStr); }
    });

    if (document.getElementById('date').value) {
      fetchHijri(document.getElementById('date').value);
    }

    /* ── Hijri date fetch ── */
    var lastHijriReq = null;
    function fetchHijri(dmy) {
      var parts = dmy.split('-');
      if (parts.length !== 3) return;
      var iso = parts[2] + '-' + parts[1] + '-' + parts[0];
      if (iso === lastHijriReq) return;
      lastHijriReq = iso;
      fetch('<?php echo base_url('common/get_hijri_date_ajax'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams({ greg_date: iso })
      }).then(r => r.json()).then(function (d) {
        var el = document.getElementById('hijri-date-display');
        var txt = document.getElementById('hijri-text');
        if (d && d.status === 'success' && d.hijri_date) {
          if (txt) txt.textContent = d.hijri_date;
          if (el) el.style.display = '';
        } else {
          if (el) el.style.display = 'none';
        }
      }).catch(function () { });
    }

    /* ── Autocomplete helper ── */
    function setupAutocomplete(inputId, appendSel, selectedId, hiddenId, multiple, maxSel) {
      var input = document.getElementById(inputId);
      var hiddenInput = document.getElementById(hiddenId);
      if (!input || !hiddenInput) return;

      $(input).autocomplete({
        appendTo: appendSel,
        source: function (req, res) {
          $.ajax({ url: '<?php echo base_url('common/search_users'); ?>', dataType: 'json', data: { term: req.term }, success: function (data) { res($.map(data, function (item) { return { label: item.name, value: item.name, id: item.id }; })); } });
        },
        select: function (e, ui) {
          var ids = hiddenInput.value ? hiddenInput.value.split(',').filter(Boolean) : [];
          if (maxSel && ids.length >= maxSel) { alert('Max ' + maxSel + ' selections allowed.'); input.value = ''; return false; }
          if (!multiple && ids.length > 0) { alert('Only one selection allowed. Remove current first.'); input.value = ''; return false; }
          if (!ids.includes(String(ui.item.id))) {
            ids.push(ui.item.id);
            hiddenInput.value = ids.join(',');
            var chip = document.createElement('span');
            var cls = (hiddenId === 'individual-ids') ? 'chip' : (hiddenId === 'group-leader-id' ? 'chip green' : 'chip gold');
            chip.className = cls;
            var rmCls = (hiddenId === 'individual-ids') ? 'remove-individual' : (hiddenId === 'group-leader-id' ? 'remove-group-leader' : 'remove-group-member');
            chip.innerHTML = ui.item.label + '<button class="chip-remove ' + rmCls + '" data-id="' + ui.item.id + '">×</button>';
            document.getElementById(selectedId).appendChild(chip);
          }
          input.value = ''; return false;
        }
      });
    }

    setupAutocomplete('individuals', '.acod-1', 'selected-individuals', 'individual-ids', true, MAX_INDIVIDUAL);
    setupAutocomplete('group-leader', '.acod-2', 'selected-group-leader', 'group-leader-id', false, 1);
    setupAutocomplete('group-members', '.acod-3', 'selected-group-members', 'group-member-ids', false, 1);

    /* Chip removal */
    $(document).on('click', '.remove-individual', function (e) {
      e.preventDefault();
      var id = String($(this).data('id'));
      var ids = ($('#individual-ids').val() || '').split(',').filter(Boolean).filter(function (i) { return i !== id; });
      $('#individual-ids').val(ids.join(','));
      $(this).parent().remove();
    });
    $(document).on('click', '.remove-group-leader', function (e) {
      e.preventDefault(); $('#group-leader').val(''); $('#group-leader-id').val(''); $(this).parent().remove();
    });
    $(document).on('click', '.remove-group-member', function (e) {
      e.preventDefault();
      var id = String($(this).data('id'));
      var ids = ($('#group-member-ids').val() || '').split(',').filter(Boolean).filter(function (i) { return i !== id; });
      $('#group-member-ids').val(ids.join(','));
      $(this).parent().remove();
    });

    /* ── Save validation ── */
    document.getElementById('save-miqaat-btn').addEventListener('click', function (e) {
      var av = assignHidden.value;
      if (av === 'Individual') {
        if (!($('#individual-ids').val() || '').trim()) { alert('Please select at least one individual.'); e.preventDefault(); return; }
      }
      if (av === 'Group') {
        if (!($('#group-leader-id').val() || '').trim()) { alert('Please select a group leader.'); e.preventDefault(); return; }
      }
    });

    /* ── Flash auto-dismiss ── */
    document.querySelectorAll('#cmApp .flash').forEach(function (el) {
      setTimeout(function () { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(function () { el.remove(); }, 500); }, 4000);
    });
  });
</script>

<!-- Hijri calendar logic (unchanged functional core, restyled) -->
<script>
  (function () {
    var calContainer = document.getElementById('hijri-calendar');
    var currentLbl = document.getElementById('hijri-current');
    var prevBtn = document.getElementById('hijri-prev');
    var nextBtn = document.getElementById('hijri-next');
    var yearSelect = document.getElementById('hijri-year-select');
    var gregInput = document.getElementById('date');
    var hijriDisplay = document.getElementById('hijri-date-display');
    var hijriText = document.getElementById('hijri-text');
    if (!calContainer || !gregInput) return;

    var monthsCache = {}, daysCache = {}, years = [], currentYear = null, currentMonth = 1, pendingGreg = null;

    function fetchJSON(url) { return fetch(url).then(function (r) { return r.json(); }); }
    function monthName(year, month) { var ms = monthsCache[year] || []; var f = ms.find(function (m) { return parseInt(m.id) === parseInt(month); }); return f ? f.name : ('Month ' + month); }

    function loadYears() { return fetchJSON('<?php echo base_url('common/get_hijri_years'); ?>').then(function (d) { if (d.status === 'success') { years = d.years; if (!currentYear) currentYear = years[0]; if (yearSelect) { yearSelect.innerHTML = years.map(function (y) { return '<option value="' + y + '">' + y + '</option>'; }).join(''); yearSelect.value = currentYear; } } }); }
    function loadMonths(year) { if (monthsCache[year]) return Promise.resolve(monthsCache[year]); return fetchJSON('<?php echo base_url('common/get_hijri_months'); ?>?year=' + year).then(function (d) { if (d.status === 'success') monthsCache[year] = d.months; return monthsCache[year] || []; }); }
    function loadDays(year, month) { var k = year + '-' + month; if (daysCache[k]) return Promise.resolve(daysCache[k]); return fetchJSON('<?php echo base_url('common/get_hijri_days'); ?>?year=' + year + '&month=' + month).then(function (d) { if (d.status === 'success') daysCache[k] = d.days; return daysCache[k] || []; }); }

    function highlightGreg(iso) { var btn = calContainer.querySelector('[data-greg="' + iso + '"]'); if (btn) { calContainer.querySelectorAll('.hijri-day').forEach(function (x) { x.classList.remove('active'); }); btn.classList.add('active'); } }
    function gregWeekday(iso) { return new Date(iso.replace(/-/g, '/')).getDay(); }

    function render() {
      loadMonths(currentYear).then(function () { return loadDays(currentYear, currentMonth); }).then(function (days) {
        currentLbl.textContent = monthName(currentYear, currentMonth) + ' ' + currentYear;
        calContainer.innerHTML = '';
        if (!days.length) { calContainer.innerHTML = '<div style="padding:10px;color:var(--text-3);font-size:.8rem;">No days available.</div>'; return; }

        var table = document.createElement('div'); table.className = 'hijri-week-grid';
        var headRow = document.createElement('div'); headRow.className = 'hijri-row';
        ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(function (h) { var hd = document.createElement('div'); hd.className = 'hijri-head-cell'; hd.textContent = h; headRow.appendChild(hd); });
        table.appendChild(headRow);

        var weekRow = document.createElement('div'); weekRow.className = 'hijri-row'; var cellsInRow = 0;
        var firstWd = gregWeekday(days[0].greg_date);
        for (var i = 0; i < firstWd; i++) { var emp = document.createElement('div'); emp.className = 'hijri-cell empty'; weekRow.appendChild(emp); cellsInRow++; }

        days.forEach(function (d) {
          if (cellsInRow === 7) { table.appendChild(weekRow); weekRow = document.createElement('div'); weekRow.className = 'hijri-row'; cellsInRow = 0; }
          var btn = document.createElement('button'); btn.type = 'button'; btn.className = 'hijri-day'; btn.textContent = d.day; btn.dataset.greg = d.greg_date; btn.dataset.hijri = d.hijri_date;
          btn.addEventListener('click', function () {
            var gp = d.greg_date.split('-');
            if (gp.length === 3) { gregInput.value = gp[2] + '-' + gp[1] + '-' + gp[0]; gregInput.dispatchEvent(new Event('change', { bubbles: true })); }
            var hp = d.hijri_date.split('-');
            if (hp.length === 3 && hijriText) { hijriText.textContent = hp[0] + ' ' + monthName(currentYear, currentMonth) + ' ' + hp[2]; hijriDisplay.style.display = ''; }
            calContainer.querySelectorAll('.hijri-day').forEach(function (x) { x.classList.remove('active'); }); btn.classList.add('active');
          });
          var cell = document.createElement('div'); cell.className = 'hijri-cell'; cell.appendChild(btn); weekRow.appendChild(cell); cellsInRow++;
        });
        if (cellsInRow > 0 && cellsInRow < 7) { for (var j = cellsInRow; j < 7; j++) { var e2 = document.createElement('div'); e2.className = 'hijri-cell empty'; weekRow.appendChild(e2); } }
        table.appendChild(weekRow); calContainer.appendChild(table);
        if (pendingGreg) { highlightGreg(pendingGreg); pendingGreg = null; }
      });
    }

    function navigate(delta) {
      currentMonth += delta;
      if (currentMonth < 1) { currentMonth = 12; var idx = years.indexOf(currentYear); if (idx > 0) currentYear = years[idx - 1]; }
      else if (currentMonth > 12) { currentMonth = 1; var idx = years.indexOf(currentYear); if (idx < years.length - 1) currentYear = years[idx + 1]; }
      if (yearSelect) yearSelect.value = currentYear;
      loadMonths(currentYear).then(function () { render(); });
    }

    function syncToGreg(dmy) {
      if (!dmy) return; var p = dmy.split('-'); if (p.length !== 3) return;
      var iso = p[2] + '-' + p[1] + '-' + p[0]; pendingGreg = iso;
      for (var key in daysCache) { var days = daysCache[key]; if (days.some(function (d) { return d.greg_date === iso; })) { var pk = key.split('-'); currentYear = parseInt(pk[0]); currentMonth = parseInt(pk[1]); render(); return; } }
      fetch('<?php echo base_url('common/get_hijri_parts'); ?>', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' }, body: new URLSearchParams({ greg_date: iso }) }).then(function (r) { return r.json(); }).then(function (resp) { if (resp.status === 'success' && resp.parts) { currentYear = resp.parts.hijri_year; currentMonth = parseInt(resp.parts.hijri_month); if (yearSelect) yearSelect.value = currentYear; } render(); }).catch(function () { render(); });
    }

    loadYears().then(function () { return loadMonths(currentYear); }).then(function () {
      if (gregInput.value) syncToGreg(gregInput.value); else render();
    });

    gregInput.addEventListener('change', function () { syncToGreg(this.value); });
    if (prevBtn) prevBtn.addEventListener('click', function () { navigate(-1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { navigate(1); });
    if (yearSelect) yearSelect.addEventListener('change', function () { var ny = this.value; if (ny && ny !== currentYear) { currentYear = ny; loadMonths(currentYear).then(function (ms) { var exists = (ms || []).some(function (m) { return parseInt(m.id) === parseInt(currentMonth); }); if (!exists) currentMonth = ms && ms.length ? parseInt(ms[0].id) : 1; render(); }); } });
  })();
</script>