<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
  .hidden {
    display: none;
  }

  /* Hijri calendar styles (aligned with createmiqaat) */
  #hijri-calendar .hijri-day.active {
    background: #0d6efd;
    color: #fff;
  }

  #hijri-calendar .hijri-day {
    width: 34px;
    padding: 4px 0;
  }

  #hijri-calendar .hijri-week-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  #hijri-calendar .hijri-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 4px;
  }

  #hijri-calendar .hijri-head {
    margin-bottom: 2px;
  }

  #hijri-calendar .hijri-cell {
    min-height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
  }

  #hijri-calendar .hijri-cell.empty {
    background: transparent;
  }

  #hijri-calendar .hijri-head-cell {
    font-size: 0.75rem;
    text-transform: uppercase;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="mb-3 mb-md-0">
    <a href="<?php echo base_url(isset($from) ? "common/fmbthaalimenu?from=" . $from : ""); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="">
    <h3 class="heading text-center">Create Menu</h3>
  </div>
  <div class="mt-3 mt-md-0 text-right">
    <a href="<?php echo base_url("common/add_menu_item?from=createmenu"); ?>" class="btn btn-primary">Edit Items</a>
  </div>

  <form method="post" action="<?php echo base_url("common/add_menu") ?>" class="mt-4">
    <div class="form-group">
      <h4 class="mb-2">Date</h4>
      <div class="d-flex flex-column gap-2">
        <input type="hidden" name="menu_date" value="<?php echo isset($date) ? $date : ""; ?>">
        <?php if ($edit_mode): ?>
          <input type="hidden" name="edit_mode" value="true">
          <input type="hidden" name="menu_id" value="<?php echo $menu["id"]; ?>">
          <input type="hidden" name="hirji_date" value="<?php echo isset($hirji_date) ? $hirji_date : ""; ?>">
        <?php endif ?>
        <input type="text" id="menu_date" name="menu_date" class="form-control mb-2" required value="<?php
                                                                                                      echo isset($menu["date"]) ? date("d-m-Y", strtotime($menu["date"])) : "";
                                                                                                      echo isset($date) ? $date : "";
                                                                                                      ?>" placeholder="Please select a date">
        <p id="hijri-date-display" class="form-text text-muted mb-2">Hijri Date: </p>
        <div class="form-group mb-3 border rounded p-2 bg-light" id="hijri-selector-wrapper">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="fw-bold m-0">Select Hijri Date</label>
            <div>
              <select id="hijri-year-select" class="form-control form-select form-select-sm d-inline-block w-auto me-2" aria-label="Hijri Year" style="min-width:90px"></select>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-2">
            <button type="button" id="hijri-prev" class="btn btn-sm btn-outline-secondary">«</button>
            <span id="hijri-current" class="mx-2 fw-semibold small"></span>
            <button type="button" id="hijri-next" class="btn btn-sm btn-outline-secondary">»</button>
          </div>
          <div id="hijri-calendar" class="hijri-cal-grid mb-2"></div>
          <small class="text-muted d-block">Click a Hijri day to auto-fill the Gregorian date above.</small>
        </div>
      </div>
    </div>

    <div class="form-group">
      <h4 class="mb-2">Assign Member to this Day</h4>
      <input type="hidden" id="assigned-user-id" name="assigned_user_id" value="<?php echo isset($assigned_user_id) ? htmlspecialchars((string)$assigned_user_id, ENT_QUOTES) : ''; ?>">
      <input type="text" id="assigned-member" class="form-control" placeholder="Enter member name or ITS ID" autocomplete="off"
             value="<?php echo isset($assigned_user_name) && isset($assigned_user_id) && $assigned_user_name ? htmlspecialchars((string)$assigned_user_name . ' (' . (string)$assigned_user_id . ')', ENT_QUOTES) : ''; ?>">
      <small id="assigned-member-help" class="form-text text-muted mt-1"></small>
      <ul id="assigned-member-results" class="list-group mt-2" style="max-height: 220px; overflow: auto;"></ul>
    </div>

    <h4>Select Items to Add to Menu</h4>
    <div class="form-group">
      <input type="text" id="search-input" class="form-control" placeholder="Search items...">
    </div>

    <ul id="search-results" class="list-group mb-3"></ul>

    <h5>Selected Items</h5>
    <ul id="selected-items" class="list-group my-2"></ul>

    <input type="hidden" name="menu_name">
    <input type="hidden" name="selected_item_ids" id="selected-item-ids">

    <div class="form-group">
      <input type="submit" id="submit-btn" class="btn btn-success mt-3" value="<?php echo $edit_mode ? "Save Edits" : 'Create Menu'; ?>" />
    </div>
  </form>

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
              <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item.name}
                <button class="btn btn-sm btn-success add-item" data-id="${item.id}" data-name="${item.name}">Add</button>
              </li>
            `);
          });
        }
      });
    });

    // Add to selected
    $('#search-results').on('click', '.add-item', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      const name = $(this).data('name');

      if (!selectedItems.includes(id)) {
        selectedItems.push(id);
        $('#selected-items').append(`
          <li class="list-group-item d-flex justify-content-between align-items-center" id="item-${id}">
            ${name}
            <button class="btn btn-sm btn-danger remove-item" data-id="${id}">Remove</button>
          </li>
        `);
        updateHiddenField();
        $(this).closest('li').remove();
        $("#search-input").val('').focus();
        $('#search-results').empty();
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

      const assignedId = ($('#assigned-user-id').val() || '').toString().trim();
      if (!assignedId) {
        alert("You can't create the menu. You have to assign the member first.");
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
          const assignedId2 = ($('#assigned-user-id').val() || '').toString().trim();
          if (!assignedId2) {
            alert("You can't create the menu. You have to assign the member first.");
            return;
          }
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
      if (iso) {
        lastCheckedISO = iso;
        lastCheckResult = await checkMiqaat(iso);
      } else {
        lastCheckedISO = '';
        lastCheckResult = null;
      }
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
          btn.className = 'btn btn-sm btn-outline-primary hijri-day';
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
        btn.className = 'btn btn-sm btn-outline-primary mb-2';
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