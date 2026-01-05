<style>
  .mumineen-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 1.5rem;
  }

  .top-actions {
    display: flex;
    justify-content: flex-end;
    gap: .5rem;
    margin-bottom: 1rem
  }

  .filters-bar {
    background: #343a40;
    color: #fff;
    padding: .5rem .75rem;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .filters-card { background: #fff; border: 1px solid #e9ecef; border-radius: 6px; box-shadow: 0 1px 4px rgba(16,24,40,0.03); margin-bottom: 1rem; overflow: hidden }
  .filters-form { padding: 1rem; }
  .filters-form .form-label { font-size: .9rem; margin-bottom: .35rem; }
  .filters-form .form-control, .filters-form .form-select { min-height: 42px; height: 44px; padding: .5rem .75rem; }
  .filters-form .form-control::placeholder { color: #9aa0a6 }

  /* Filter grid: one row on wide screens, wraps cleanly into a 2nd row on smaller screens */
  .filters-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr 1.6fr;
    gap: .75rem;
    align-items: end;
    margin: 0;
  }
  .filters-grid > div { min-width: 0; }
  .filters-grid .form-label { display: block; }
  .filters-grid .form-control,
  .filters-grid .form-select { width: 100%; }
  .filters-actions {
    display: flex;
    gap: .5rem;
    margin-top: .75rem;
  }
  @media (max-width: 992px) {
    .filters-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  /* Mobile layout */
  @media (max-width: 576px) {
    .mumineen-container { padding: 1rem; }
    .filters-form { padding: .75rem; }
    .filters-grid { grid-template-columns: 1fr; gap: .65rem; }
    .filters-actions { flex-direction: column; gap: .5rem; }
    .filters-actions .btn { width: 100%; }

    .filters-bar { gap: .5rem; }
    #toggleFiltersBtn { padding: .25rem .5rem; width: auto; }

    .hof-header { flex-direction: column; align-items: flex-start; gap: .25rem; }
    .hof-left { width: 100%; }

    .member-row { flex-wrap: wrap; gap: .5rem; }
    .member-index { width: auto; }
    .member-name { flex: 1 1 100%; min-width: 0; }
    .actions { width: auto; margin-left: auto; }
  }

  .hof-header {
    background: #cfe8ff;
    padding: .5rem .75rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: .6rem
  }

  .hof-left {
    font-weight: 700;
    color: #023e8a
  }

  .hof-badges {
    margin-left: .5rem
  }

  .member-row {
    background: #fff;
    padding: .7rem;
    border: 1px solid #e6eef8;
    border-left: 6px solid #6fb0ff;
    display: flex;
    align-items: center;
    gap: 1rem
  }

  .member-index {
    width: 40px;
    color: #666
  }

  .member-name {
    flex: 1
  }

  .its-badge {
    background: #1c7bd3;
    color: #fff;
    padding: .15rem .45rem;
    border-radius: 6px;
    font-size: .8rem;
    margin-left: .5rem
  }

  .its-small {
    color: #9aa7b2;
    font-size: .85rem
  }

  .age,
  .relation {
    width: 80px;
    text-align: center
  }

  .actions {
    width: 140px;
    text-align: right;
    display: flex;
    gap: .4rem;
    justify-content: flex-end
  }

  .action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0
  }

  .action-view {
    background: #f8f9fa;
    color: #333
  }

  .action-edit {
    background: #ffd966;
    color: #333
  }

  .action-pass {
    background: #ff6b6b;
    color: #fff
  }

  .edit-hof {
    background: #ffffff;
    border: 1px solid #9fb9db;
    color: #0b66a3;
    padding: .25rem .6rem;
    border-radius: .25rem
  }

  .member-card {
    margin-bottom: .6rem
  }

  .search-row {
    display: flex;
    gap: .5rem;
    margin-bottom: 0.8rem
  }

  /* legacy responsive tweaks no longer needed with CSS grid */

  /* Modal scrollability fixes */
  .modal-dialog { max-width: 960px; }
  .modal-body { max-height: 70vh; overflow-y: auto; }
  .modal-content { overflow: hidden; }
  pre { max-width: 100%; white-space: pre-wrap; word-wrap: break-word; }
  textarea.form-control { resize: vertical; }

  @media (max-width:760px) {
    .top-actions {
      flex-direction: column;
      align-items: flex-end
    }

    .age,
    .relation {
      display: none
    }

    .its-small {
      display: none
    }
    .modal-body { max-height: 60vh; }
  }
</style>

<div class="mumineen-container pt-5">
  <div class="row mb-2 align-items-center pt-5">
    <div class="col-6">
      <a href="<?php echo isset($back_url) ? $back_url : base_url('amilsaheb'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-6">
      <div class="top-actions"></div>
    </div>
  </div>

  <div class="filters-card">
    <div class="filters-bar d-flex justify-content-between align-items-center">
      <div class="fw-bold">FILTERS</div>
      <button id="toggleFiltersBtn" class="btn btn-sm btn-outline-light" type="button" aria-expanded="true"><i class="fa fa-sliders"></i> Hide</button>
    </div>

    <form id="filtersForm" class="filters-form" onsubmit="return false;">
      <div class="filters-grid">
        <div>
          <label class="form-label">Member Name</label>
          <input type="text" id="filterName" class="form-control" placeholder="e.g. Burhanuddin">
          <div class="filters-actions">
            <button id="applyFiltersBtn" class="btn btn-primary btn-sm">✔ Apply</button>
            <button id="resetFiltersBtn" class="btn btn-outline-secondary btn-sm">↺ Reset</button>
          </div>
        </div>
        <div>
          <label class="form-label">Sector</label>
          <select id="filterSector" class="form-select">
            <option value="">All</option>
          </select>
        </div>
        <div>
          <label class="form-label">Sub Sector</label>
          <select id="filterSubSector" class="form-select">
            <option value="">All</option>
          </select>
        </div>
        <div>
          <label class="form-label">Status</label>
          <select id="filterStatus" class="form-select">
            <option value="">All (Default)</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label class="form-label">HOF</label>
          <select id="filterHOF" class="form-select">
            <option value="">All HOFs</option>
          </select>
        </div>
      </div>
    </form>
  </div>

  <!-- <div class="search-row">
    <input type="text" id="searchInput" placeholder="Search..." oninput="performSearch()" class="form-control">
    <div id="activeFilter" style="display:none" class="align-self-center">
      <span id="activeFilterText" class="badge bg-secondary"></span>
      <button id="clearFilterLink" type="button" class="btn btn-sm btn-outline-secondary ms-2">Clear</button>
    </div>
  </div> -->

  <div id="membersList"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div id="userDetailsForm">
        <div class="modal-header">
          <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="userDetailsFields" class="row row-cols-1 row-cols-md-2 g-3"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const originalData = <?= json_encode($users) ?>;
  // full user list provided by controller (falls back to originalData when not available)
  const originalAllData = <?= json_encode(isset($all_users) ? $all_users : $users) ?>;
  let currentData = [...originalData];

  // populate selects first so initial filters can set select values (use full dataset)
  populateFilterOptions();
  applyInitialFilterFromQuery();
  renderList(currentData);

  function performSearch() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const filtered = currentData.filter(user => Object.values(user).some(v => v && v.toString().toLowerCase().includes(keyword)));
    renderList(filtered);
  }

  function groupByHOF(users) {
    const groups = {};
    // build ITS -> name map from full dataset (so HOF_ID referencing ITS can be resolved)
    const itsMap = {};
    (originalAllData || originalData).forEach(m => {
      const its = String(m.ITS_ID || m.ITS || '');
      if (its) itsMap[its] = m.Full_Name || m.FullName || m.Name || '';
    });

    users.forEach(u => {
      const hofId = (u.HOF_ID || u.HOF || u.hof_id || '').toString();
      let hofName = (u.HOF_Name || u.hof_name || '').toString();
      // if hofName not present, try resolving from ITS map
      if (!hofName && hofId && itsMap[hofId]) hofName = itsMap[hofId];
      if (!hofName) hofName = (u.HOF || '') || 'Unknown HOF';

      const key = hofId || '__NO_HOF__';
      if (!groups[key]) groups[key] = {
        hofId: hofId,
        hofName: hofName,
        members: []
      };
      groups[key].members.push(u);
    });

    // convert to sorted array by hofName
    const arr = Object.values(groups).sort((a, b) => (a.hofName || '').toString().localeCompare((b.hofName || '').toString()));
    return arr;
  }

  function renderList(users) {
    const container = document.getElementById('membersList');
    container.innerHTML = '';
    const groups = groupByHOF(users);
    let idx = 1;
    groups.forEach(group => {
      const hofId = group.hofId || '';
      const hofName = group.hofName || 'Unknown HOF';
      const members = group.members;
      const hofDiv = document.createElement('div');
      hofDiv.className = 'member-card';

      // HOF header
      const header = document.createElement('div');
      header.className = 'hof-header';
      header.innerHTML = `<div class="hof-left">HOF: ${escapeHtml(hofName)} <span class="its-small">${hofId?('ID: '+escapeHtml(hofId)):''}</span> <span class="hof-badges"><span class="badge bg-light text-dark">Members: ${members.length}</span></span></div>`;
      hofDiv.appendChild(header);

      // members
      members.forEach(m => {
        const row = document.createElement('div');
        row.className = 'member-row';

        const index = document.createElement('div');
        index.className = 'member-index';
        index.textContent = idx++;
        const nameWrap = document.createElement('div');
        nameWrap.className = 'member-name';
        const name = document.createElement('div');
        name.innerHTML = `<strong>${escapeHtml(m.Full_Name||'')}</strong> <span class="its-badge">ITS: ${escapeHtml(m.ITS_ID||m.ITS||'')}</span>`;
        const sub = document.createElement('div');
        sub.className = 'its-small';
        sub.textContent = m.Sector ? m.Sector : '';
        nameWrap.appendChild(name);
        nameWrap.appendChild(sub);

        const age = document.createElement('div');
        age.className = 'age';
        age.textContent = m.Age || '';
        const relation = document.createElement('div');
        relation.className = 'relation';
        relation.innerHTML = `<span class="badge bg-light text-dark">${escapeHtml(m.Relation || m.RelationShip || 'Member')}</span>`;

        const actions = document.createElement('div');
        actions.className = 'actions';
        const btnView = document.createElement('button');
        btnView.className = 'action-btn action-view';
        btnView.innerHTML = '<i class="fa fa-eye"></i>';
        btnView.title = 'View';
        btnView.onclick = () => openModal(m);
        actions.appendChild(btnView);

        row.appendChild(index);
        row.appendChild(nameWrap);
        row.appendChild(age);
        row.appendChild(relation);
        row.appendChild(actions);
        hofDiv.appendChild(row);
      });

      container.appendChild(hofDiv);
    });
  }

  function openModal(user) {
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    const container = document.getElementById('userDetailsFields');
    container.innerHTML = '';

    // Render all keys from user object in a readable way
    Object.keys(user).forEach(key => {
        // hide internal id field from modal
        if (String(key).toLowerCase() === 'id') return;
        let val = user[key];
      if (val === null || val === undefined) val = '';

      const col = document.createElement('div');
      col.className = 'col';
      const label = document.createElement('label');
      label.className = 'form-label';
      label.textContent = key.replace(/_/g, ' ');

      // If value is object/array, pretty-print JSON
      if (typeof val === 'object') {
        const pre = document.createElement('pre');
        pre.style.whiteSpace = 'pre-wrap';
        pre.style.background = '#f8f9fa';
        pre.style.padding = '8px';
        pre.style.borderRadius = '4px';
        pre.textContent = JSON.stringify(val, null, 2);
        col.appendChild(label);
        col.appendChild(pre);
      } else {
        const s = String(val);
        if (s.length > 120 || s.indexOf('\n') !== -1) {
          const ta = document.createElement('textarea');
          ta.className = 'form-control';
          ta.rows = 4;
          ta.value = s;
          ta.readOnly = true;
          col.appendChild(label);
          col.appendChild(ta);
        } else {
          const input = document.createElement('input');
          input.className = 'form-control';
          input.value = s;
          input.readOnly = true;
          col.appendChild(label);
          col.appendChild(input);
        }
      }

      container.appendChild(col);
    });

    modal.show();
  }

  function escapeHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function applyInitialFilterFromQuery() {
    const params = new URLSearchParams(window.location.search);

    // Support legacy / filter=value pattern
    const legacyFilter = (params.get('filter') || '').toLowerCase();
    const legacyValue = params.get('value');

    // Also support direct GET params: name, sector, sub_sector, status, hof, min, max
    const name = params.get('name') || params.get('filterName') || '';
    const sector = params.get('sector') || params.get('filterSector') || '';
    const sub = params.get('sub_sector') || params.get('sub') || params.get('filterSubSector') || '';
    const status = params.get('status') || params.get('filterStatus') || '';
    const hof = params.get('hof') || params.get('filterHOF') || '';
    const min = params.get('min');
    const max = params.get('max');

    // If legacy filter provided, handle those cases first
    if (legacyFilter) {
      switch (legacyFilter) {
        case 'all':
          currentData = [...originalData];
          break;
        case 'sector':
          // set select if option exists
          if (legacyValue) { const sel = document.getElementById('filterSector'); if (sel) sel.value = legacyValue; }
          break;
        case 'member_type':
          if (legacyValue) { const sel = document.getElementById('filterStatus'); if (sel) sel.value = legacyValue; }
          break;
            case 'gender':
              // store gender on form dataset instead of abusing name field
              if (legacyValue) { const f = document.getElementById('filtersForm'); if (f) f.dataset.gender = legacyValue; }
              break;
            case 'hof_fm_type':
              // legacy filter that specifies whether record is HOF or FM; store on form dataset and show chip
              if (legacyValue) {
                const f = document.getElementById('filtersForm'); if (f) f.dataset.hofFmType = legacyValue;
                const chip = document.getElementById('activeFilter'); const chipText = document.getElementById('activeFilterText');
                if (chip && chipText) { chipText.textContent = 'Type: ' + legacyValue; chip.style.display = 'inline-block'; }
              }
              break;
        case 'age_range':
          if (min !== null || max !== null) { /* handled below */ }
          break;
        default:
          break;
      }
    }

    // If any direct params exist, set form fields
    if (name) { const el = document.getElementById('filterName'); if (el) el.value = name; }
    if (sector) { const el = document.getElementById('filterSector'); if (el) el.value = sector; }
    if (sub) { const el = document.getElementById('filterSubSector'); if (el) el.value = sub; }
    if (status) { const el = document.getElementById('filterStatus'); if (el) el.value = status; }
    if (hof) { const el = document.getElementById('filterHOF'); if (el) el.value = hof; }
    // direct gender param support (store on form dataset)
    const directGender = params.get('gender'); if (directGender) { const f = document.getElementById('filtersForm'); if (f) f.dataset.gender = directGender; }

    // If min/max specified, we will apply an age_range style filter by storing them on the form element dataset
    if (min !== null || max !== null) {
      const f = document.getElementById('filtersForm'); if (f) { f.dataset.min = min || ''; f.dataset.max = max || ''; }
    }

    // Apply filters now to reflect any GET-provided values
    applyFilters();
  }

  // Populate filter selects from data
  function populateFilterOptions() {
    const sectors = new Set();
    const subSectors = new Set();
    const hofs = new Map();
    const statuses = new Set();
    // ITS -> name map to resolve HOF names (use full dataset)
    const itsMap = {};
    (originalAllData || originalData).forEach(u => {
      if (u.Sector) sectors.add(u.Sector);
      if (u.Sub_Sector) subSectors.add(u.Sub_Sector);
      const its = String(u.ITS_ID || u.ITS || '');
      if (its) itsMap[its] = u.Full_Name || u.FullName || u.Name || '';
      const hofId = u.HOF_ID || u.HOF || u.hof_id || '';
      const hofName = u.HOF_Name || u.hof_name || '';
      // prefer explicit HOF name, else try resolving from ITS map, else fallback to Full_Name
      let resolved = hofName || (hofId && itsMap[hofId]) || '';
      if (!resolved && u.Full_Name && hofId && (u.ITS_ID == hofId || u.ITS == hofId)) resolved = u.Full_Name;
      if (hofId) hofs.set(hofId, resolved);
      if (u.Status) statuses.add(u.Status);
    });

    const sectorEl = document.getElementById('filterSector');
    const subEl = document.getElementById('filterSubSector');
    const hofEl = document.getElementById('filterHOF');

    // clear existing (keep default option)
    sectorEl.querySelectorAll('option:not([value=""])').forEach(n => n.remove());
    subEl.querySelectorAll('option:not([value=""])').forEach(n => n.remove());
    hofEl.querySelectorAll('option:not([value=""])').forEach(n => n.remove());

    Array.from(sectors).sort().forEach(s => {
      const o = document.createElement('option');
      o.value = s;
      o.textContent = s;
      sectorEl.appendChild(o);
    });
    Array.from(subSectors).sort().forEach(s => {
      const o = document.createElement('option');
      o.value = s;
      o.textContent = s;
      subEl.appendChild(o);
    });
    Array.from(hofs.entries()).forEach(([id, name]) => {
      const o = document.createElement('option');
      o.value = id;
      o.textContent = name || id;
      hofEl.appendChild(o);
    });

    // when sector changes, filter sub-sector options to matching ones
    sectorEl.addEventListener('change', () => {
      const selected = sectorEl.value;
      const subSet = new Set();
      if (!selected) {
        // if sector cleared, show all sub sectors (use full dataset)
        (originalAllData || originalData).forEach(u => { if (u.Sub_Sector) subSet.add(u.Sub_Sector); });
      } else {
        (originalAllData || originalData).forEach(u => { if ((u.Sector || '') === selected && u.Sub_Sector) subSet.add(u.Sub_Sector); });
      }
      // repopulate subEl
      subEl.querySelectorAll('option:not([value=""])').forEach(n => n.remove());
      Array.from(subSet).sort().forEach(s => { const o = document.createElement('option'); o.value = s; o.textContent = s; subEl.appendChild(o); });
    });
  }

  function applyFilters(e) {
    if (e) e.preventDefault();
    const name = (document.getElementById('filterName').value || '').toLowerCase();
    const sector = document.getElementById('filterSector').value;
    const sub = document.getElementById('filterSubSector').value;
    const status = document.getElementById('filterStatus').value;
    const hof = document.getElementById('filterHOF').value;

    // dataset-based filters (legacy/direct)
    const formEl = document.getElementById('filtersForm');
    const hofFmType = formEl && formEl.dataset && formEl.dataset.hofFmType ? formEl.dataset.hofFmType.toString().toUpperCase() : '';
    const dsGender = formEl && formEl.dataset && formEl.dataset.gender ? formEl.dataset.gender.toString().toLowerCase() : '';
    const dsMin = formEl && formEl.dataset && formEl.dataset.min !== undefined && formEl.dataset.min !== '' ? parseInt(formEl.dataset.min, 10) : null;
    const dsMax = formEl && formEl.dataset && formEl.dataset.max !== undefined && formEl.dataset.max !== '' ? parseInt(formEl.dataset.max, 10) : null;

    const hasAnyFilter = !!(name || sector || sub || status || hof || hofFmType || dsGender || (dsMin !== null) || (dsMax !== null));

    // if no filters set, show all (prefer full dataset when available)
    if (!hasAnyFilter) {
      currentData = (originalAllData && originalAllData.length) ? [...originalAllData] : [...originalData];
      renderList(currentData);
      const chip = document.getElementById('activeFilter'); if (chip) chip.style.display = 'none';
      return;
    }

    const source = (originalAllData && originalAllData.length) ? originalAllData : originalData;
    const filtered = source.filter(u => {
      const preds = [];
      if (name) {
        const nm = (u.Full_Name || u.FullName || '').toString().toLowerCase();
        const its = (u.ITS_ID || u.ITS || '').toString().toLowerCase();
        const mob = (u.Mobile || '').toString().toLowerCase();
        preds.push(nm.includes(name) || its.includes(name) || mob.includes(name));
      }
      if (sector) preds.push((u.Sector || '').toString() === sector);
      if (sub) preds.push((u.Sub_Sector || '').toString() === sub);
      if (status) { const uStatus = (u.Status || u.Member_Status || '').toString(); preds.push(uStatus === status); }
      if (hof) { const hofId = (u.HOF_ID || u.HOF || u.hof_id || '').toString(); preds.push(hofId === hof); }
      if (hofFmType) {
        const t = ((u.HOF_FM_TYPE || u.hof_fm_type || u.HOF_FM || '')).toString().toUpperCase();
        preds.push(t === hofFmType);
      }
      if (dsGender) {
        const ug = ((u.Gender || u.gender || '')).toString().toLowerCase();
        preds.push(ug === dsGender);
      }
      // age range (min/max) stored on dataset
      if (dsMin !== null || dsMax !== null) {
        const ageVal = parseInt((u.Age || '').toString(), 10);
        if (!Number.isNaN(ageVal)) {
          if (dsMin !== null && dsMax !== null) preds.push(ageVal >= dsMin && ageVal <= dsMax);
          else if (dsMin !== null) preds.push(ageVal >= dsMin);
          else if (dsMax !== null) preds.push(ageVal <= dsMax);
        } else {
          preds.push(false);
        }
      }
      // OR behaviour: include if any predicate is true
      return preds.length > 0 && preds.some(Boolean);
    });

    // show active filter chip (optional; markup may be removed)
    const chip = document.getElementById('activeFilter');
    const chipText = document.getElementById('activeFilterText');
    let labelParts = [];
    if (name) labelParts.push('Name: ' + name);
    if (sector) labelParts.push('Sector: ' + sector);
    if (sub) labelParts.push('Sub: ' + sub);
    if (status) labelParts.push('Status: ' + status);
    if (hof) {
      const hofSelect = document.getElementById('filterHOF');
      labelParts.push('HOF: ' + (hofSelect.options[hofSelect.selectedIndex].text || hof));
    }
    if (hofFmType) labelParts.push('Type: ' + hofFmType);
    if (dsGender) labelParts.push('Gender: ' + dsGender);
    if (dsMin !== null || dsMax !== null) labelParts.push('Age: ' + (dsMin !== null ? dsMin : '') + '-' + (dsMax !== null ? dsMax : ''));
    if (chip && chipText) {
      if (labelParts.length) {
        chipText.textContent = labelParts.join(' | ');
        chip.style.display = 'inline-block';
      } else {
        chip.style.display = 'none';
      }
    }

    currentData = filtered;
    renderList(filtered);
  }

  function resetFilters(e) {
    if (e) e.preventDefault();
    const f = document.getElementById('filtersForm');
    f.reset();
    // clear dataset-based filters too
    if (f && f.dataset) {
      delete f.dataset.gender;
      delete f.dataset.hofFmType;
      delete f.dataset.min;
      delete f.dataset.max;
    }
    currentData = (originalAllData && originalAllData.length) ? [...originalAllData] : [...originalData];
    renderList(currentData);
    const chip = document.getElementById('activeFilter');
    if (chip) chip.style.display = 'none';
  }

  document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
  document.getElementById('resetFiltersBtn').addEventListener('click', resetFilters);

  // Apply filters immediately when any select changes
  ['filterSector','filterSubSector','filterStatus','filterHOF'].forEach(id=>{
    const el = document.getElementById(id);
    if(el) el.addEventListener('change', applyFilters);
  });

  const clearFilterEl = document.getElementById('clearFilterLink');
  if (clearFilterEl) {
    clearFilterEl.addEventListener('click', function(e) {
      e.preventDefault();
      history.replaceState(null, '', window.location.pathname);
      currentData = [...originalData];
      const si = document.getElementById('searchInput'); if (si) si.value = '';
      renderList(currentData);
      const af = document.getElementById('activeFilter'); if (af) af.style.display = 'none';
    });
  }

  // Toggle filters visibility
  (function(){
    const btn = document.getElementById('toggleFiltersBtn');
    if(!btn) return;
    const filtersBar = document.querySelector('.filters-bar');
    const filtersForm = document.getElementById('filtersForm');
    const searchRow = document.querySelector('.search-row');
    btn.addEventListener('click', function(){
      const hidden = filtersForm.classList.toggle('d-none');
      if(filtersBar) filtersBar.classList.toggle('d-none', hidden);
      if(searchRow) searchRow.classList.toggle('d-none', hidden);
      btn.innerHTML = hidden ? '<i class="fa fa-sliders"></i> Show' : '<i class="fa fa-sliders"></i> Hide';
      btn.setAttribute('aria-expanded', (!hidden).toString());
    });
  })();
</script>