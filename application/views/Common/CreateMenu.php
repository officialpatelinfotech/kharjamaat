<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
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

  #cmApp {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
    background: var(--bg);
    min-height: 100vh;
  }

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

  #cmApp .form-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

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

  #cmApp .fl-val input[type="text"],
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
    width: fit-content;
  }

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

  #cmApp .hijri-day.thaali-day {
    background: var(--green-bg);
    color: var(--green);
    border-color: var(--green);
    font-weight: 700;
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

  #cmApp .btn-premium {
    font-weight: 700;
    font-size: 0.8rem;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
  }

  #cmApp .btn-premium-outline {
    background: transparent;
    border: 1.5px solid #e8e0cc;
    color: #5a5244;
  }

  #cmApp .btn-premium-outline:hover {
    background: #f5e9c0;
    border-color: #b8860b;
    color: #b8860b;
  }
</style>

<div id="cmApp">
  <div class="container pt-5 mt-5 pb-5">
    
    <!-- Flash messages -->
    <?php if ($this->session->flashdata('error')): ?>
      <div class="flash error"><i class="fa-solid fa-triangle-exclamation"></i><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
      <div class="flash success"><i class="fa-solid fa-circle-check"></i><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <!-- ── Compact Header ── -->
    <div class="cm-header">
      <div class="cm-header-left">
        <a href="<?php echo base_url(isset($from) ? "common/fmbthaalimenu?from=" . $from : ""); ?>" class="cm-back-btn"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="cm-header-title">
          <div class="cm-icon-wrap">
            <i class="fa-solid <?php echo ($edit_mode) ? 'fa-pen-to-square' : 'fa-calendar-plus'; ?>"></i>
          </div>
          <div>
            <div class="cm-title-text"><?php echo ($edit_mode) ? 'Edit Menu' : 'Create Menu'; ?></div>
            <div class="cm-subtitle">
              <?php echo ($edit_mode) ? 'Update menu details &amp; assignments' : 'Add a new thaali menu to the schedule'; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="<?php echo base_url("common/add_menu_item?from=createmenu"); ?>" class="btn btn-sm btn-light" style="font-weight:700; border-radius:8px;"><i class="fa fa-edit"></i> Edit Items</a>
        <div class="cm-header-badge">
          <i class="fa-regular fa-calendar"></i>
          <?php echo ($edit_mode) ? 'Edit Mode' : 'New Entry'; ?>
        </div>
      </div>
    </div>

    <!-- ── Form card ── -->
    <div class="form-card">
      <form method="post" action="<?php echo base_url("common/add_menu") ?>">
        <input type="hidden" name="return_hijri_month" value="<?php echo isset($return_hijri_month) ? htmlspecialchars((string) $return_hijri_month, ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="return_assigned_filter" value="<?php echo isset($return_assigned_filter) ? htmlspecialchars((string) $return_assigned_filter, ENT_QUOTES) : ''; ?>">
        
        <?php if ($edit_mode): ?>
          <input type="hidden" name="edit_mode" value="true">
          <input type="hidden" name="menu_id" value="<?php echo $menu["id"]; ?>">
          <input type="hidden" name="hirji_date" value="<?php echo isset($hirji_date) ? $hirji_date : ""; ?>">
        <?php endif ?>

        <!-- ── Date & Calendar ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-calendar-day"></i></span>
          <span class="sec-title">Date &amp; Schedule</span>
        </div>
        <ul class="field-list">
          <li class="field-row">
            <div class="fl-key">Gregorian Date <span class="req">*</span></div>
            <div class="fl-val">
              <input type="hidden" name="menu_date" value="<?php echo isset($date) ? $date : ""; ?>">
              <input type="text" id="menu_date" name="menu_date" required value="<?php
                                                                                                            echo isset($menu["date"]) ? date("d-m-Y", strtotime($menu["date"])) : "";
                                                                                                            echo isset($date) ? $date : "";
                                                                                                            ?>" placeholder="Please select a date">
              
              <!-- Mark as Thaali Day Toggle Section -->
              <div id="thaali-day-toggle-container" class="mt-2" style="display: none; align-items: center; gap: 12px;">
                <button type="button" id="btn-toggle-thaali-day" class="btn btn-premium btn-premium-outline btn-sm">
                  <i class="fa fa-tag"></i> Mark as Thaali Day
                </button>
                <span id="thaali-day-status-label" class="badge badge-success" style="padding: 6px 12px; font-size: 0.8rem; display: none;">
                  <i class="fa fa-check-circle"></i> Thaali Day Marked
                </span>
              </div>
            </div>
          </li>
          <li class="field-row">
            <div class="fl-key">Hijri Date &amp; Calendar</div>
            <div class="fl-val" style="padding-top:10px;padding-bottom:10px;">
              <div class="hijri-pill mb-2">
                <i class="fa fa-moon"></i><span id="hijri-date-display">Hijri Date: </span>
              </div>
              
              <div id="hijri-selector-wrapper" class="mt-2">
                <div class="hijri-wrapper">
                  <div class="hijri-cal-header">
                    <button type="button" id="hijri-prev" class="hijri-nav-btn"><i class="fa fa-chevron-left"></i></button>
                    <div style="display:flex;align-items:center;gap:8px;">
                      <span id="hijri-current" class="hijri-cal-title"></span>
                      <select id="hijri-year-select" class="hijri-year-sel" aria-label="Hijri Year" style="min-width:90px"></select>
                    </div>
                    <button type="button" id="hijri-next" class="hijri-nav-btn"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  <div class="hijri-cal-body">
                    <div id="hijri-calendar"></div>
                  </div>
                  <div class="hijri-cal-hint">
                    <i class="fa fa-info-circle" style="margin-right:5px;"></i>Click a Hijri day to auto-fill the Gregorian date above.
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>

        <!-- ── Member Assignment ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-user-tag"></i></span>
          <span class="sec-title">Member Assignment</span>
        </div>
        <ul class="field-list">
          <li class="field-row">
            <div class="fl-key">Assign Member to this Day</div>
            <div class="fl-val" style="position:relative; overflow:visible;">
              <input type="hidden" id="assigned-user-id" name="assigned_user_id" value="<?php echo isset($assigned_user_id) ? htmlspecialchars((string)$assigned_user_id, ENT_QUOTES) : ''; ?>">
              <input type="text" id="assigned-member" placeholder="Enter member name or ITS ID" autocomplete="off"
                     value="<?php echo isset($assigned_user_name) && isset($assigned_user_id) && $assigned_user_name ? htmlspecialchars((string)$assigned_user_name . ' (' . (string)$assigned_user_id . ')', ENT_QUOTES) : ''; ?>">
              <small id="assigned-member-help" class="hint"></small>
              <ul id="assigned-member-results" class="list-group mt-2" style="max-height: 220px; overflow: auto; width: 100%; position: absolute; z-index: 999; top: 100%; left:0;"></ul>
            </div>
          </li>
        </ul>

        <!-- ── Menu Items ── -->
        <div class="sec-hd">
          <span class="sec-icon"><i class="fa fa-utensils"></i></span>
          <span class="sec-title">Menu Items</span>
        </div>
        <ul class="field-list">
          <li class="field-row">
            <div class="fl-key">Search Items</div>
            <div class="fl-val">
              <input type="text" id="search-input" placeholder="Search items...">
              <ul id="search-results" class="list-group mt-2" style="max-height: 200px; overflow: auto;"></ul>
            </div>
          </li>
          <li class="field-row">
            <div class="fl-key">Selected Items</div>
            <div class="fl-val" style="padding-top:10px; padding-bottom:10px;">
              <ul id="selected-items" class="list-group"></ul>
            </div>
          </li>
        </ul>

        <input type="hidden" name="menu_name">
        <input type="hidden" name="selected_item_ids" id="selected-item-ids">

        <!-- ── Form Footer ── -->
        <div style="padding: 20px; background: var(--surface-2); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
          <a href="<?php echo base_url(isset($from) ? "common/fmbthaalimenu?from=" . $from : ""); ?>" class="btn btn-secondary" style="border-radius: 8px; font-weight:600; padding: 8px 20px;">Cancel</a>
          <button type="submit" id="submit-btn" class="btn btn-success" style="border-radius: 8px; font-weight: 700; background: var(--green); border-color: var(--green); padding: 8px 20px;">
            <?php echo $edit_mode ? 'Save Edits' : 'Create Menu'; ?>
          </button>
        </div>
      </form>
    </div>

    <!-- Miqaat conflict confirmation modal -->
    <div class="modal fade" id="miqaatConflictModal" tabindex="-1" role="dialog" aria-labelledby="miqaatConflictLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="miqaatConflictLabel">Miqaat Exists on Selected Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="miqaat-conflict-text">There is a miqaat on this date. Do you still want to create a menu?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="miqaat-cancel">No</button>
            <button type="button" class="btn btn-primary" id="miqaat-confirm">Yes, Create Menu</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
    $(document).ready(function() {
    let selectedItems = [];
      let submitting = false;
      let lastCheckedISO = '';
      let lastCheckResult = null;

    <?php if ($edit_mode) : ?>
      const itemsFromDB = <?php echo $edit_mode ? json_encode($mapped_menu_items["items"]) : ''; ?>;
      itemsFromDB.forEach(item => {
        const id = Number(item.id);
        const name = item.name;
        selectedItems.push(id);
        $('#selected-items').append(`
        <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
          ${name}
          <button class="btn btn-sm btn-danger remove-item" data-id="${id}">Remove</button>
        </li>
      `);
      });
    <?php endif ?>

    <?php if (isset($menu_dates)) :
      $menu_dates_arr = [];
      foreach ($menu_dates as $menu_date) {
        $menu_dates_arr[] = date("d-m-Y", strtotime($menu_date["date"]));
      }
    ?>
      $menu_dates = <?php echo json_encode($menu_dates_arr); ?>;
    <?php endif; ?>

    flatpickr("#menu_date", {
      dateFormat: "d-m-Y",
      disable: $menu_dates,
    });


    $('#search-input').on('input', function() {
      const query = $(this).val();

      if (query.length < 2) {
        $('#search-results').empty();
        return;
      }

      $.ajax({
        url: '<?php echo base_url("common/search_items") ?>',
        method: 'POST',
        data: {
          keyword: query
        },
        success: function(response) {
          const items = JSON.parse(response);
          $('#search-results').empty();

          const filteredItems = items.filter(item => !selectedItems.includes(Number(item.id)));
          filteredItems.forEach(item => {
            $('#search-results').append(`
              <li class="list-group-item d-flex justify-content-between align-items-center add-item-row" data-id="${item.id}" data-name="${item.name}" role="button" tabindex="0">
                ${item.name}
                <button class="btn btn-sm btn-success add-item" data-id="${item.id}" data-name="${item.name}">Add</button>
              </li>
            `);
          });
        }
      });
    });

    function addItemToSelection(id, name, sourceElement) {
      id = Number(id);

      if (!selectedItems.includes(id)) {
        selectedItems.push(id);
        $('#selected-items').append(`
          <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
            ${name}
            <button class="btn btn-sm btn-danger remove-item" data-id="${id}">Remove</button>
          </li>
        `);
        updateHiddenField();
        if (sourceElement) {
          $(sourceElement).closest('li').remove();
        }
        $("#search-input").val('').focus();
        $('#search-results').empty();
      }
    }

    // Add to selected
    $('#search-results').on('click', '.add-item', function(e) {
      e.preventDefault();
      e.stopPropagation();
      addItemToSelection($(this).data('id'), $(this).data('name'), this);
    });

    $('#search-results').on('click', '.add-item-row', function(e) {
      if ($(e.target).closest('.add-item').length) {
        return;
      }
      addItemToSelection($(this).data('id'), $(this).data('name'), this);
    });

    $('#search-results').on('keydown', '.add-item-row', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        addItemToSelection($(this).data('id'), $(this).data('name'), this);
      }
    });

    // Remove from selected
    $('#selected-items').on('click', '.remove-item', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      selectedItems = selectedItems.filter(itemId => itemId !== id);
      $(`#item-${id}`).remove();
      updateHiddenField();
    });

    function updateHiddenField() {
      $('#selected-item-ids').val(JSON.stringify(selectedItems));
    }

    function toISO(str) {
      if (!str) return '';
      const ymd = /^(\d{4})-(\d{2})-(\d{2})$/;
      const dmy = /^(\d{2})-(\d{2})-(\d{4})$/;
      if (ymd.test(str)) return str;
      const m = str.match(dmy);
      return m ? `${m[3]}-${m[2]}-${m[1]}` : '';
    }

    // Assign member autocomplete
    function clearAssignResults() {
      $('#assigned-member-results').empty();
    }

    function setAssignHelp(text, isError) {
      var el = $('#assigned-member-help');
      el.text(text || '');
      el.removeClass('text-danger text-muted');
      el.addClass(isError ? 'text-danger' : 'text-muted');
    }

    $('#assigned-member').on('input', function () {
      const query = $(this).val();
      $('#assigned-user-id').val('');
      setAssignHelp('', false);

      if (!query || query.length < 2) {
        clearAssignResults();
        return;
      }

      const iso = toISO($('#menu_date').val());

      $.ajax({
        url: '<?php echo base_url("common/search_assign_members"); ?>',
        method: 'POST',
        data: { keyword: query, menu_date: iso },
        success: function (resp) {
          let json;
          try { json = (typeof resp === 'string') ? JSON.parse(resp) : resp; } catch(e) { json = null; }
          clearAssignResults();

          if (!json || json.success === false) {
            setAssignHelp(json && json.message ? json.message : 'Unable to fetch members.', true);
            return;
          }

          const items = json.items || [];
          if (!items.length) {
            return;
          }

          items.forEach(function (item) {
            const pending = Number(item.pending_days || 0);
            const disabled = pending <= 0;
            const label = `${item.name} (${item.its_id})`;
            const sub = `Pending Thaali Days: ${pending}`;
            const li = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
            li.toggleClass('disabled', disabled);
            li.css('cursor', disabled ? 'not-allowed' : 'pointer');
            li.attr('data-id', item.its_id);
            li.attr('data-name', item.name);
            li.attr('data-pending', pending);
            li.html(`<span>${label}<br><small class="text-muted">${sub}</small></span>`);
            if (disabled) {
              li.append('<span class="badge badge-secondary">0</span>');
            } else {
              li.append('<span class="badge badge-success">' + pending + '</span>');
            }
            $('#assigned-member-results').append(li);
          });
        }
      });
    });

    $('#assigned-member-results').on('click', 'li', function () {
      if ($(this).hasClass('disabled')) {
        setAssignHelp('No pending thaali days available for this member.', true);
        return;
      }
      const id = $(this).data('id');
      const name = $(this).data('name');
      const pending = $(this).data('pending');
      $('#assigned-user-id').val(id);
      $('#assigned-member').val(`${name} (${id})`);
      setAssignHelp('Pending Thaali Days: ' + pending, false);
      clearAssignResults();
    });

    // Hide results when clicking outside
    $(document).on('click', function (e) {
      const $t = $(e.target);
      if (!$t.closest('#assigned-member').length && !$t.closest('#assigned-member-results').length) {
        clearAssignResults();
      }
    });

    async function checkMiqaat(iso) {
      try {
        const fd = new FormData();
        fd.append('date', iso);
        const res = await fetch('<?php echo base_url("common/check_miqaat_for_date"); ?>', { method: 'POST', body: fd });
        const json = await res.json();
        return json || { has_miqaat: false };
      } catch(e) {
        return { has_miqaat: false };
      }
    }

    $("#submit-btn").on('click', async function(e) {
      e.preventDefault();
      if (selectedItems.length === 0) {
        alert("Please select at least one item for the menu.");
        return;
      }
      if (submitting) return;
      const raw = $("#menu_date").val();
      const iso = toISO(raw);
      if (!iso) { submitting = true; $("form").submit(); return; }

      // Re-check miqaat for safety
      let result = lastCheckResult;
      if (!result || lastCheckedISO !== iso) {
        result = await checkMiqaat(iso);
        lastCheckedISO = iso;
        lastCheckResult = result;
      }

      if (result.has_miqaat) {
        const miq = result.miqaat || {};
        const text = `There is a miqaat on ${miq.date || raw}${miq.name ? `: ${miq.name}` : ''}${miq.type ? ` (${miq.type})` : ''}. Do you still want to create a menu?`;
        $("#miqaat-conflict-text").text(text);
        $('#miqaatConflictModal').modal('show');
        $('#miqaat-confirm').off('click').on('click', function() {
          $('#miqaatConflictModal').modal('hide');
          submitting = true;
          $("form")[0].submit();
        });
        $('#miqaat-cancel').off('click').on('click', function() {
          $('#miqaatConflictModal').modal('hide');
        });
        return;
      }

      submitting = true;
      $("form")[0].submit();
    });

    $("#menu_date").on('change', async function(e) {
      $.ajax({
        url: '<?php echo base_url("common/verify_menu_date") ?>',
        method: 'POST',
        data: {
          menu_date: $(this).val()
        },
        success: function(response) {
          const data = JSON.parse(response);
          if (data.status === 'exists') {
            alert(`Menu already exists for ${$("#menu_date").val()}. Please choose a different date.`);
            $("#menu_date").val('');
          } else {
            const hijriDate = data.hijri_date;
            $('#hijri-date-display').html(`Hijri Date: ${hijriDate}`);
          }
        }
      });

      // Cache miqaat check on date change (submit will re-check)
      const iso = toISO($(this).val());
      updateThaaliDayToggle($(this).val());
      if (iso) {
        lastCheckedISO = iso;
        lastCheckResult = await checkMiqaat(iso);
      } else {
        lastCheckedISO = '';
        lastCheckResult = null;
      }
    });

    let selectedDateIso = '';
    let isCurrentDateThaali = false;

    function updateThaaliDayToggle(dateVal) {
      const iso = toISO(dateVal);
      if (!iso) {
        $('#thaali-day-toggle-container').hide();
        selectedDateIso = '';
        isCurrentDateThaali = false;
        return;
      }

      $.ajax({
        url: '<?php echo base_url("common/check_is_thaali_day_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        data: { date: iso },
        success: function(res) {
          const isThaali = res && res.is_thaali_day;
          selectedDateIso = iso;
          isCurrentDateThaali = isThaali;

          $('#thaali-day-toggle-container').css('display', 'flex');
          if (isThaali) {
            $('#thaali-day-status-label').show();
            $('#btn-toggle-thaali-day')
              .html('<i class="fa fa-times-circle"></i> Remove Thaali Day Mark')
              .removeClass('btn-premium-outline')
              .addClass('btn-danger')
              .css('border', 'none');
          } else {
            $('#thaali-day-status-label').hide();
            $('#btn-toggle-thaali-day')
              .html('<i class="fa fa-tag"></i> Mark as Thaali Day')
              .removeClass('btn-danger')
              .addClass('btn-premium-outline')
              .css('border', '1.5px solid #e8e0cc');
          }
        }
      });
    }

    $('#btn-toggle-thaali-day').on('click', function() {
      if (!selectedDateIso) return;
      const nextStatus = isCurrentDateThaali ? 0 : 1;

      $.ajax({
        url: '<?php echo base_url("common/toggle_thaali_day_ajax"); ?>',
        type: 'POST',
        data: {
          date: selectedDateIso,
          status: nextStatus
        },
        dataType: 'json',
        success: function(res) {
          if (res && res.success) {
            alert(res.message);
            isCurrentDateThaali = !isCurrentDateThaali;
            
            // Toggle UI
            if (isCurrentDateThaali) {
              $('#thaali-day-status-label').show();
              $('#btn-toggle-thaali-day')
                .html('<i class="fa fa-times-circle"></i> Remove Thaali Day Mark')
                .removeClass('btn-premium-outline')
                .addClass('btn-danger')
                .css('border', 'none');

              // Update calendar day button color to green
              const btn = document.querySelector(`[data-greg="${selectedDateIso}"]`);
              if (btn) {
                btn.className = 'hijri-day thaali-day';
              }
            } else {
              $('#thaali-day-status-label').hide();
              $('#btn-toggle-thaali-day')
                .html('<i class="fa fa-tag"></i> Mark as Thaali Day')
                .removeClass('btn-danger')
                .addClass('btn-premium-outline')
                .css('border', '1.5px solid #e8e0cc');

              // Revert calendar day button color to default
              const btn = document.querySelector(`[data-greg="${selectedDateIso}"]`);
              if (btn) {
                btn.className = 'hijri-day';
              }
            }
          } else {
            alert(res.message || 'Operation failed.');
          }
        },
        error: function() {
          alert('Server error occurred.');
        }
      });
    });

    // Initial load check
    updateThaaliDayToggle($('#menu_date').val());

    /* ── Flash auto-dismiss ── */
    document.querySelectorAll('#cmApp .flash').forEach(function (el) {
      setTimeout(function () { el.style.transition = 'opacity .5s'; el.style.opacity = '0'; setTimeout(function () { el.remove(); }, 500); }, 4000);
    });
  });
</script>
<script>
  // Hijri calendar selector logic (mirrors createmiqaat) for CreateMenu
  (function() {
    const calContainer = document.getElementById('hijri-calendar');
    const currentLbl = document.getElementById('hijri-current');
    const prevBtn = document.getElementById('hijri-prev');
    const nextBtn = document.getElementById('hijri-next');
    const yearSelect = document.getElementById('hijri-year-select');
    const gregInput = document.getElementById('menu_date');
    const hijriDisplay = document.getElementById('hijri-date-display');
    if (!calContainer || !gregInput) return;

    let monthsCache = {}; // {year: [{id,name}]}
    let daysCache = {}; // { 'year-month': [ {day,hijri_date,greg_date} ] }
    let years = [];
    let currentYear = null;
    let currentMonth = 1;
    let pendingSelectGreg = null; // store greg date to auto-select when calendar data ready

    function fetchJSON(url) {
      return fetch(url).then(r => r.json());
    }

    function monthName(year, month) {
      const ms = monthsCache[year] || [];
      const f = ms.find(m => parseInt(m.id) === parseInt(month));
      return f ? f.name : ('Month ' + month);
    }

    function loadYears() {
      return fetchJSON('<?php echo base_url('common/get_hijri_years'); ?>').then(d => {
        if (d.status === 'success') {
          years = d.years;
          if (!currentYear) currentYear = years[0];
          if (yearSelect) {
            yearSelect.innerHTML = years.map(y => `<option value="${y}">${y}</option>`).join('');
            yearSelect.value = currentYear;
          }
        }
      });
    }

    function loadMonths(year) {
      if (monthsCache[year]) return Promise.resolve(monthsCache[year]);
      return fetchJSON('<?php echo base_url('common/get_hijri_months'); ?>?year=' + year).then(d => {
        if (d.status === 'success') monthsCache[year] = d.months;
        return monthsCache[year] || [];
      });
    }

    function loadDays(year, month) {
      const k = year + '-' + month;
      if (daysCache[k]) return Promise.resolve(daysCache[k]);
      return fetchJSON('<?php echo base_url('common/get_hijri_days'); ?>?year=' + year + '&month=' + month).then(d => {
        if (d.status === 'success') daysCache[k] = d.days;
        return daysCache[k] || [];
      });
    }

    function highlightGreg(iso) {
      if (!iso) return;
      const btn = calContainer.querySelector('[data-greg="' + iso + '"]');
      if (btn) {
        [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
        btn.classList.add('active');
      }
    }

    function applyAutoSelect() {
      if (!pendingSelectGreg) return;
      highlightGreg(pendingSelectGreg);
      // After highlighting, also update the visible Hijri Date display if possible
      const active = calContainer.querySelector('.hijri-day.active');
      if (active && active.dataset.hijri) {
        const hp = active.dataset.hijri.split('-');
        if (hp.length === 3) {
          hijriDisplay.textContent = 'Hijri Date: ' + hp[0] + ' ' + monthName(currentYear, currentMonth) + ' ' + hp[2];
        }
      }
      pendingSelectGreg = null;
    }

    function gregWeekday(iso) { // iso = Y-m-d
      const d = new Date(iso.replace(/-/g, '/')); // Safari friendly
      return d.getDay(); // 0=Sun
    }

    function render() {
      // Ensure months for current year are loaded before rendering (so monthName resolves correctly)
      loadMonths(currentYear).then(() => loadDays(currentYear, currentMonth)).then(days => {
        currentLbl.textContent = monthName(currentYear, currentMonth) + ' ' + currentYear;
        const monthYearHeading = document.getElementById('hijri-month-year');
        // if(monthYearHeading){ monthYearHeading.textContent = currentLbl.textContent; }
        calContainer.innerHTML = '';
        if (!days.length) {
          calContainer.innerHTML = '<div class="text-muted small">No days.</div>';
          return;
        }

        // Build 7-col structure: headers + weeks
        const table = document.createElement('div');
        table.className = 'hijri-week-grid';

        const headers = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const headRow = document.createElement('div');
        headRow.className = 'hijri-row hijri-head';
        headers.forEach(h => {
          const hd = document.createElement('div');
          hd.className = 'hijri-cell hijri-head-cell fw-semibold text-center';
          hd.textContent = h;
          headRow.appendChild(hd);
        });
        table.appendChild(headRow);

        // Map each day to weekday
        let weekRow = document.createElement('div');
        weekRow.className = 'hijri-row';
        let cellsInRow = 0;
        // Determine offset for first day
        const firstWeekday = gregWeekday(days[0].greg_date); // 0..6
        for (let i = 0; i < firstWeekday; i++) {
          const empty = document.createElement('div');
          empty.className = 'hijri-cell empty';
          weekRow.appendChild(empty);
          cellsInRow++;
        }

        days.forEach(d => {
          if (cellsInRow === 7) {
            table.appendChild(weekRow);
            weekRow = document.createElement('div');
            weekRow.className = 'hijri-row';
            cellsInRow = 0;
          }
          const btn = document.createElement('button');
          btn.type = 'button';
          const isThaaliDay = (d.day_type === 'Thaali' || d.day_type === 'Both');
          if (isThaaliDay) {
            btn.className = 'hijri-day thaali-day';
          } else {
            btn.className = 'hijri-day';
          }
          btn.textContent = d.day;
          btn.dataset.greg = d.greg_date;
          btn.dataset.hijri = d.hijri_date;
          btn.addEventListener('click', () => {
            const gp = d.greg_date.split('-');
            if (gp.length === 3) {
              gregInput.value = gp[2] + '-' + gp[1] + '-' + gp[0];
              gregInput.dispatchEvent(new Event('change', {
                bubbles: true
              }));
            }
            const hp = d.hijri_date.split('-');
            if (hp.length === 3) {
              hijriDisplay.textContent = 'Hijri Date: ' + hp[0] + ' ' + monthName(currentYear, currentMonth) + ' ' + hp[2];
            }
            [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
            btn.classList.add('active');
            const selectedHeading = document.getElementById('hijri-selected-date');
            // if(selectedHeading){ selectedHeading.textContent = 'Selected Date: '+ d.day + ' ' + monthName(currentYear,currentMonth) + ' ' + currentYear; }
          });
          const cell = document.createElement('div');
          cell.className = 'hijri-cell text-center';
          cell.appendChild(btn);
          weekRow.appendChild(cell);
          cellsInRow++;
        });

        // Trailing empties
        if (cellsInRow > 0 && cellsInRow < 7) {
          for (let i = cellsInRow; i < 7; i++) {
            const empty = document.createElement('div');
            empty.className = 'hijri-cell empty';
            weekRow.appendChild(empty);
          }
        }
        table.appendChild(weekRow);
        calContainer.appendChild(table);
        applyAutoSelect();
        // After auto-select ensure selected heading reflects active day if any (e.g., on sync)
        const selectedHeading = document.getElementById('hijri-selected-date');
        if (selectedHeading) {
          const active = calContainer.querySelector('.hijri-day.active');
          // if(active){ selectedHeading.textContent = 'Selected Date: ' + active.textContent.trim() + ' ' + monthName(currentYear,currentMonth) + ' ' + currentYear; }
        }
      });
    }
    // Insert toggle button (once) just above calendar wrapper
    (function ensureToggle() {
      const wrapper = document.getElementById('hijri-selector-wrapper');
      const cal = document.getElementById('hijri-calendar');
      if (wrapper && !document.getElementById('toggle-hijri-cal')) {
        try {
          localStorage.removeItem('hijriCalHidden');
        } catch (e) {}
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'toggle-hijri-cal';
        btn.className = 'hijri-toggle-btn';
        btn.setAttribute('aria-expanded', 'false');
        btn.textContent = 'Show Hijri Calendar';
        if (wrapper.children.length > 0) {
          wrapper.insertBefore(btn, wrapper.children[1]);
        } else {
          wrapper.insertBefore(btn, wrapper.firstChild);
        }
        const note = wrapper.querySelector('small.text-muted');
        cal.style.display = 'none';
        if (note) note.style.display = 'none';
        btn.addEventListener('click', () => {
          const hidden = cal.style.display === 'none';
          if (hidden) {
            cal.style.display = '';
            if (note) note.style.display = '';
            btn.textContent = 'Hide Hijri Calendar';
            btn.setAttribute('aria-expanded', 'true');
          } else {
            cal.style.display = 'none';
            if (note) note.style.display = 'none';
            btn.textContent = 'Show Hijri Calendar';
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      }
    })();

    function navigate(delta) {
      currentMonth += delta;
      if (currentMonth < 1) {
        currentMonth = 12;
        const idx = years.indexOf(currentYear);
        if (idx > 0) {
          currentYear = years[idx - 1];
        }
      } else if (currentMonth > 12) {
        currentMonth = 1;
        const idx = years.indexOf(currentYear);
        if (idx < years.length - 1) {
          currentYear = years[idx + 1];
        }
      }
      if (yearSelect) yearSelect.value = currentYear;
      if (!monthsCache[currentYear]) {
        loadMonths(currentYear).then(() => render());
      } else {
        render();
      }
    }

    function syncCalendarToGregorian(dmy) {
      if (!dmy) return;
      const p = dmy.split('-');
      if (p.length !== 3) return;
      const iso = p[2] + '-' + p[1] + '-' + p[0];
      pendingSelectGreg = iso; // target to auto-select
      // First try cache
      for (const key in daysCache) {
        const days = daysCache[key];
        if (days.some(d => d.greg_date === iso)) {
          const partsKey = key.split('-');
          currentYear = parseInt(partsKey[0]);
          currentMonth = parseInt(partsKey[1]);
          if (yearSelect) yearSelect.value = currentYear;
          render();
          return;
        }
      }
      fetch('<?php echo base_url('common/get_hijri_parts'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: new URLSearchParams({
          greg_date: iso
        })
      }).then(r => r.json()).then(resp => {
        if (resp.status === 'success' && resp.parts) {
          currentYear = resp.parts.hijri_year;
          currentMonth = parseInt(resp.parts.hijri_month);
          if (yearSelect) yearSelect.value = currentYear;
        }
        render();
      }).catch(() => {
        render();
      });
    }

    // Initial boot
    loadYears().then(() => loadMonths(currentYear)).then(ms => {
      if (ms && ms.length && !currentMonth) currentMonth = parseInt(ms[0].id) || 1;
    }).then(() => {
      if (gregInput.value) {
        syncCalendarToGregorian(gregInput.value);
      } else {
        render();
      }
    });

    gregInput.addEventListener('change', function() {
      syncCalendarToGregorian(this.value);
    });
    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', function() {
        navigate(-1);
      });
      nextBtn.addEventListener('click', function() {
        navigate(1);
      });
    }
    if (yearSelect) {
      yearSelect.addEventListener('change', function() {
        const newYear = this.value;
        if (newYear && newYear !== currentYear) {
          currentYear = newYear;
          loadMonths(currentYear).then(ms => {
            const exists = (ms || []).some(m => parseInt(m.id) === parseInt(currentMonth));
            if (!exists) {
              currentMonth = ms && ms.length ? parseInt(ms[0].id) : 1;
            }
            render();
          });
        }
      });
    }
  })();
</script>

<script>
  (function addHijriHeadings() {
    const wrapper = document.getElementById('hijri-selector-wrapper');
    if (wrapper && !document.getElementById('hijri-month-year')) {
      const heading = document.createElement('div');
      heading.id = 'hijri-month-year';
      heading.className = 'mb-1 fw-semibold text-primary small';
      wrapper.insertBefore(heading, document.getElementById('toggle-hijri-cal') ? document.getElementById('toggle-hijri-cal').nextSibling : wrapper.children[1]);
      const sel = document.createElement('div');
      sel.id = 'hijri-selected-date';
      sel.className = 'mb-2 text-secondary small';
      wrapper.insertBefore(sel, heading.nextSibling);
      const currentLbl = document.getElementById('hijri-current');
      if (currentLbl && currentLbl.textContent.trim()) heading.textContent = currentLbl.textContent.trim();
    }
  })();
</script>