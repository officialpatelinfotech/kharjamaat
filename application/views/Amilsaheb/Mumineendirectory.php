<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.mumineen-container { max-width:1200px; margin:0 auto; padding:1.5rem; }
.top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }

/* Filter card */
.filters-bar {
  background:linear-gradient(135deg,#1e293b,#334155);
  color:#fff; padding:.6rem 1rem; border-radius:6px 6px 0 0;
  display:flex; align-items:center; justify-content:space-between;
}
.filters-bar-title { display:flex; align-items:center; gap:.5rem; font-weight:700; font-size:.82rem; letter-spacing:.5px; }
.filters-card { background:#fff; border:1px solid #dee2e6; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,.07); margin-bottom:1rem; overflow:hidden; }
.filters-body { padding:1rem; }

/* Filter section label */
.filter-section-label {
  font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.8px;
  color:#94a3b8; margin-bottom:.5rem; margin-top:.25rem;
  display:flex; align-items:center; gap:.4rem;
}
.filter-section-label::after { content:''; flex:1; height:1px; background:#e2e8f0; }

.frow { display:grid; gap:.6rem; align-items:end; margin-bottom:.6rem; }
.frow-6 { grid-template-columns: 2fr 1fr 1fr 1fr 1.1fr 1fr; }
.frow-4 { grid-template-columns: repeat(4,1fr); }

.flabel { font-size:.72rem; font-weight:700; color:#64748b; margin-bottom:.2rem; display:block; letter-spacing:.2px; }
.finput, .fselect {
  font-size:.82rem; height:36px; padding:.3rem .6rem;
  border:1.5px solid #e2e8f0; border-radius:6px; background:#f8fafc;
  color:#1e293b; outline:none; width:100%; transition:border-color .15s,box-shadow .15s;
}
.finput:focus, .fselect:focus { border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.12); background:#fff; }
.age-row { display:flex; gap:.4rem; }
.age-row .finput { flex:1; }

/* Chips */
#chipRow { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:.75rem; }
.chip { display:inline-flex; align-items:center; gap:4px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:40px; padding:3px 10px; font-size:.72rem; font-weight:700; }
.chip-x { cursor:pointer; margin-left:2px; opacity:.6; } .chip-x:hover { opacity:1; }
.chip-clear { display:inline-flex; align-items:center; background:#fff1f2; color:#be123c; border:1px solid #fecdd3; border-radius:40px; padding:3px 10px; font-size:.72rem; font-weight:700; cursor:pointer; }
.chip-clear:hover { background:#ffe4e6; }

/* Dashboard title */
.dash-title { border-left:4px solid #2563eb; background:linear-gradient(90deg,#eff6ff,#fff); padding:.65rem 1rem; border-radius:4px; margin-bottom:.75rem; display:flex; align-items:center; justify-content:space-between; }
.dash-title h5 { margin:0; color:#1d4ed8; font-weight:700; font-size:.9rem; }

/* Table */
.table-wrap { overflow-x:auto; border-radius:8px; border:1px solid #e2e8f0; background:#fff; box-shadow:0 1px 6px rgba(0,0,0,.06); }
table.dir { width:100%; border-collapse:collapse; font-size:.82rem; }
table.dir thead th {
  background:#f8fafc; padding:10px 12px; font-size:.67rem; font-weight:800;
  text-transform:uppercase; letter-spacing:.5px; color:#64748b;
  border-bottom:2px solid #e2e8f0; white-space:nowrap;
  position:sticky; top:0; z-index:1; user-select:none;
}
th.sortable { cursor:pointer; }
th.sortable:hover { background:#f1f5f9; color:#1e293b; }
th.sortable .sort-icon { margin-left:4px; font-size:.6rem; }
th.sortable.asc  .sort-icon::after { content:'▲'; }
th.sortable.desc .sort-icon::after { content:'▼'; }
th.sortable:not(.asc):not(.desc) .sort-icon::after { content:'⇅'; opacity:.4; }

table.dir tbody tr { border-bottom:1px solid #f1f5f9; transition:background .1s; }
table.dir tbody tr:hover { background:#f8fbff; }
table.dir td { padding:8px 12px; vertical-align:middle; }
tr.hof-row td { background:#eff6ff; font-weight:700; border-top:2px solid #bfdbfe; }
tr.hof-row td:first-child { border-left:3px solid #2563eb; }
tr.family-sep td { padding:0; height:5px; background:#f8fafc; border:none; }

.pill-hof { display:inline-block; background:#2563eb; color:#fff; font-size:.55rem; font-weight:800; padding:1px 6px; border-radius:40px; margin-left:4px; vertical-align:middle; }
.pill-fm  { display:inline-block; background:#e0e7ff; color:#3730a3; font-size:.55rem; font-weight:700; padding:1px 6px; border-radius:40px; margin-left:4px; vertical-align:middle; }
.badge-its { display:inline-block; background:#0369a1; color:#fff; font-size:.68rem; font-weight:700; padding:2px 7px; border-radius:4px; }
.badge-active   { display:inline-block; background:#dcfce7; color:#15803d; border:1px solid #86efac; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:40px; }
.badge-inactive { display:inline-block; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:40px; }
.badge-temp     { display:inline-block; background:#fef3c7; color:#92400e; border:1px solid #fcd34d; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:40px; }

.act-btn { display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; border:none; cursor:pointer; font-size:.78rem; text-decoration:none; transition:opacity .15s; }
.act-btn:hover { opacity:.75; }
.act-view { background:#dbeafe; color:#1d4ed8; }
.act-edit { background:#fef9c3; color:#92400e; }

.empty-row td { text-align:center; padding:3rem; color:#94a3b8; font-size:.9rem; }
.results-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem; }
.results-count { font-size:.8rem; color:#64748b; font-weight:600; }

@media(max-width:992px) { .frow-6,.frow-4 { grid-template-columns:1fr 1fr 1fr; } }
@media(max-width:768px)  { .frow-6,.frow-4 { grid-template-columns:1fr 1fr; } }
@media(max-width:576px)  { .frow-6,.frow-4 { grid-template-columns:1fr; } .mumineen-container { padding:.75rem; } }
</style>

<?php
  $view_base = 'admin/viewmember/';
  if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2) {
    $view_base = 'amilsaheb/viewmember/';
  }
  $can_edit = isset($_SESSION['user']['role']) && in_array($_SESSION['user']['role'], [1, 3]);
?>

<div class="mumineen-container pt-5">

  <!-- Top bar -->
  <div class="top-bar pt-3">
    <a href="<?php echo isset($back_url) ? $back_url : base_url('amilsaheb'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
    <button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="fa fa-file-excel-o"></i> Export Excel</button>
  </div>

  <!-- Filter card -->
  <div class="filters-card">
    <div class="filters-bar">
      <div class="filters-bar-title">
        <i class="fa fa-sliders"></i> FILTERS
        <span id="countBadge" style="margin-left:10px;font-size:.72rem;background:rgba(255,255,255,.15);padding:3px 10px;border-radius:20px;font-weight:600;"></span>
      </div>
      <div style="display:flex;align-items:center;gap:.5rem;">
        <button id="btnReset" class="btn btn-sm btn-outline-light" type="button"><i class="fa fa-refresh"></i> Reset</button>
        <button id="btnToggle" class="btn btn-sm btn-outline-light" type="button"><i class="fa fa-chevron-down"></i> Show Filters</button>
      </div>
    </div>

    <div id="filterBody" class="d-none">
      <form id="filtersForm" onsubmit="return false;" class="filters-body">

        <!-- Section 1: Search & Location -->
        <div class="filter-section-label"><i class="fa fa-search" style="color:#3b82f6;"></i> Search &amp; Location</div>
        <div class="frow frow-6" id="baseFilterRow">
          <div>
            <label class="flabel">Name or ITS</label>
            <input type="text" id="fName" class="finput" placeholder="Burhanuddin / 12345678">
          </div>
          <div>
            <label class="flabel">Sector</label>
            <select id="fSector" class="fselect"><option value="">All</option></select>
          </div>
          <div>
            <label class="flabel">Sub Sector</label>
            <select id="fSubSector" class="fselect"><option value="">All</option></select>
          </div>
          <div>
            <label class="flabel">HOF</label>
            <select id="fHOF" class="fselect"><option value="">All HOFs</option></select>
          </div>
          <div>
            <label class="flabel">Age Range</label>
            <div class="age-row">
              <input type="number" id="fAgeMin" class="finput" placeholder="Min" min="0">
              <input type="number" id="fAgeMax" class="finput" placeholder="Max" min="0">
            </div>
          </div>
          <!-- Marital: hidden in dashboard mode -->
          <div id="maritalCol">
            <label class="flabel">Marital Status</label>
            <select id="fMarital" class="fselect"><option value="">All</option></select>
          </div>
          <!-- Dashboard injected filter appears here (hidden in normal mode) -->
          <div id="dashInlineSlot" style="display:none;"></div>
        </div>

        <!-- Section 2: Member Details -->
        <div class="filter-section-label" id="secLabel2"><i class="fa fa-user" style="color:#8b5cf6;"></i> Member Details</div>
        <div class="frow frow-4" id="secRow2">
          <div>
            <label class="flabel">Member Status</label>
            <select id="fStatus" class="fselect">
              <option value="">All</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div>
            <label class="flabel">Gender</label>
            <select id="fGender" class="fselect">
              <option value="">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div>
            <label class="flabel">Member Type</label>
            <select id="fMemberType" class="fselect"><option value="">All</option></select>
          </div>
          <div>
            <label class="flabel">HOF / FM</label>
            <select id="fHOFType" class="fselect">
              <option value="">All</option>
              <option value="HOF">HOF Only</option>
              <option value="FM">FM Only</option>
            </select>
          </div>
        </div>

        <!-- Section 3: Status Filters -->
        <div class="filter-section-label" id="secLabel3"><i class="fa fa-heartbeat" style="color:#ef4444;"></i> Status Filters</div>
        <div class="frow frow-4" id="secRow3">
          <div>
            <label class="flabel">Health Status</label>
            <select id="fHealth" class="fselect">
              <option value="">All</option>
              <option value="Healthy">Fit &amp; Healthy</option>
              <option value="Medically Unfit">Handicapped Medically Unfit</option>
              <option value="Hospitalised">Major Disease Patient</option>
              <option value="Lazimul Firash">Lazimul Firash / Bedridden</option>
              <option value="Wafaat">Wafaat</option>
            </select>
          </div>
          <div>
            <label class="flabel">Deeni Status</label>
            <select id="fDeeni" class="fselect">
              <option value="">All</option>
              <option value="Normal">Normal</option>
              <option value="Deen Badli Lidu che">Deen Badli Lidu che</option>
              <option value="Married Outside">Married Outside</option>
              <option value="Misaq Not Given">Not given Misaq to Syedna Mufaddal Saifuddin AQA tus after Takht Nashini</option>
              <option value="Mustajeeb">Mustajeeb</option>
              <option value="No Ashara / LQ">No Ashara / LQ attended for past 3 years</option>
              <option value="No Vajebaat / Sabeel">Not paid Sila Fitra / Vajeebaat / Sabeel for last 3 years</option>
              <option value="Zero Days Scanned in Ashara Mubaraka">Zero Days Scanned in Ashara Mubaraka</option>
            </select>
          </div>
          <div>
            <label class="flabel">Residential Status</label>
            <select id="fResidential" class="fselect">
              <option value="">All</option>
              <option value="Residing in Local Jamaat">Residing in Local Jamaat</option>
              <option value="Moved for Job">Moved for Job</option>
              <option value="Moved for Studies">Moved for Studies</option>
              <option value="Moved after Marriage">Permanently moved after Marriage</option>
              <option value="Permanently Migrated">Permanently Migrated</option>
              <option value="Unknown or Not Traceable">Unknown or Not Traceable</option>
            </select>
          </div>
          <div>
            <label class="flabel">ITS-Sabeel Match</label>
            <select id="fItsMatch" class="fselect">
              <option value="">All</option>
              <option value="its_sabeel_both_khar">ITS &amp; Sabeel both in Khar</option>
              <option value="its_khar_sabeel_out">ITS in Khar, Sabeel out</option>
              <option value="sabeel_khar_its_out">Sabeel in Khar, ITS out</option>
              <option value="both_not_khar">Both not in Khar</option>
            </select>
          </div>
        </div>

      </form>
    </div>
  </div>

  <!-- Chips -->
  <div id="chipRow"></div>

  <!-- Dashboard title -->
  <div id="dashTitle" style="display:none;" class="dash-title mb-3">
    <h5 id="dashTitleText"></h5>
    <button class="btn btn-sm btn-outline-secondary" onclick="resetAll()">Clear Filter</button>
  </div>

  <!-- Results bar -->
  <div class="results-bar mb-2">
    <span class="results-count" id="resultsCount"></span>
  </div>

  <!-- Table -->
  <div class="table-wrap">
    <table class="dir">
      <thead>
        <tr>
          <th>#</th>
          <th class="sortable" data-col="Full_Name">Name <span class="sort-icon"></span></th>
          <th class="sortable" data-col="ITS_ID">ITS ID <span class="sort-icon"></span></th>
          <th class="sortable" data-col="Age">Age <span class="sort-icon"></span></th>
          <th class="sortable" data-col="Gender">Gender <span class="sort-icon"></span></th>
          <th class="sortable" data-col="Sector">Sector / Sub Sector <span class="sort-icon"></span></th>
          <th>Mobile</th>
          <th class="sortable" data-col="_status">Status <span class="sort-icon"></span></th>
          <th class="sortable" data-col="health_status">Health <span class="sort-icon"></span></th>
          <th class="sortable" data-col="deeni_status">Deeni <span class="sort-icon"></span></th>
          <th class="sortable" data-col="residential_status">Residential <span class="sort-icon"></span></th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>

</div>

<script>
// ── Data ──────────────────────────────────────────────────────────────────────
const ALL_DATA = <?= json_encode(isset($all_users) ? $all_users : $users) ?>;
const VIEW_URL = '<?= base_url($view_base) ?>';
const EDIT_URL = '<?= base_url('admin/editmember/') ?>';
const CAN_EDIT = <?= $can_edit ? 'true' : 'false' ?>;

let filtered = [...ALL_DATA];
let sortCol  = null, sortDir = 'asc';

// ITS → name map for HOF resolution
const itsMap = {};
ALL_DATA.forEach(u => { const k = String(u.ITS_ID || u.ITS || ''); if (k) itsMap[k] = u.Full_Name || ''; });

// ── Boot ──────────────────────────────────────────────────────────────────────
fillSelects();
readURLAndApply();

// ── Fill selects ──────────────────────────────────────────────────────────────
function fillSelects() {
  const sectors = new Set(), subs = new Set(), hofs = new Map(), marital = new Set(), mTypes = new Set();
  ALL_DATA.forEach(u => {
    if (u.Sector)     sectors.add(u.Sector);
    if (u.Sub_Sector) subs.add(u.Sub_Sector);
    const ms = (u.Marital_Status || '').trim();
    if (ms) marital.add(ms.charAt(0).toUpperCase() + ms.slice(1).toLowerCase());
    if (u.Member_Type) mTypes.add(u.Member_Type);
    const hid = (u.HOF_ID || u.HOF || '').toString();
    if (hid) hofs.set(hid, itsMap[hid] || u.HOF_Name || hid);
  });

  const fill = (id, items) => {
    const el = document.getElementById(id);
    if (!el) return;
    items.forEach(([v, l]) => { const o = document.createElement('option'); o.value = v; o.textContent = l; el.appendChild(o); });
  };
  fill('fSector',     Array.from(sectors).sort().map(v => [v,v]));
  fill('fSubSector',  Array.from(subs).sort().map(v => [v,v]));
  fill('fMemberType', Array.from(mTypes).sort().map(v => [v,v]));
  fill('fHOF',        Array.from(hofs.entries()).sort((a,b) => (a[1]||'').localeCompare(b[1]||'')));

  const pref = ['Single','Married','Engaged','Separated','Divorced','Widowed'];
  const rem  = new Set(marital);
  const mEl  = document.getElementById('fMarital');
  pref.forEach(v => { if (rem.has(v)) { const o = document.createElement('option'); o.value = v; o.textContent = v; mEl.appendChild(o); rem.delete(v); } });
  Array.from(rem).sort().forEach(v => { const o = document.createElement('option'); o.value = v; o.textContent = v; mEl.appendChild(o); });

  // Sub-sector cascades with sector
  document.getElementById('fSector').addEventListener('change', function() {
    const sel = this.value;
    const subEl = document.getElementById('fSubSector');
    subEl.querySelectorAll('option:not([value=""])').forEach(n => n.remove());
    const subSet = new Set();
    ALL_DATA.forEach(u => { if ((!sel || u.Sector === sel) && u.Sub_Sector) subSet.add(u.Sub_Sector); });
    Array.from(subSet).sort().forEach(v => { const o = document.createElement('option'); o.value = v; o.textContent = v; subEl.appendChild(o); });
  });
}

// ── Read URL & apply dashboard mode ──────────────────────────────────────────
function readURLAndApply() {
  const p         = new URLSearchParams(window.location.search);
  const legFilter = (p.get('filter') || '').toLowerCase();
  const legValue  = p.get('value')  || '';
  const statusP   = p.get('status') || '';
  const itsMatchP = p.get('its_sabeel_match') || '';
  const minP      = p.get('min') || p.get('age_min') || '';
  const maxP      = p.get('max') || p.get('age_max') || '';
  const madresaP  = p.get('madresa_deprived') || '';

  const isDash = !!(
    (legFilter && legFilter !== 'all') || statusP || itsMatchP || madresaP || minP || maxP
  );

  // Set base field values
  setv('fName',   p.get('name')   || '');
  setv('fSector', p.get('sector') || '');
  if (statusP)   setv('fStatus',   cap(statusP));
  if (itsMatchP) setv('fItsMatch', itsMatchP);
  if (minP) document.getElementById('fAgeMin').value = minP;
  if (maxP) document.getElementById('fAgeMax').value = maxP;

  // Store dataset filters
  const form = document.getElementById('filtersForm');
  if (legFilter === 'gender')      form.dataset.gender    = legValue;
  if (legFilter === 'hof_fm_type') form.dataset.hofFmType = legValue;
  if (madresaP)                    form.dataset.madresa   = madresaP;

  if (legFilter === 'health_status')      setv('fHealth',      legValue);
  if (legFilter === 'deeni_status')       setv('fDeeni',       legValue);
  if (legFilter === 'residential_status') setv('fResidential', legValue);
  if (legFilter === 'sector')             setv('fSector',      legValue);

  if (!['','all','age_range','sector','gender','hof_fm_type','health_status','deeni_status','residential_status','its_sabeel_match'].includes(legFilter) && legValue) {
    form.dataset.legacyField = legFilter;
    form.dataset.legacyValue = legValue;
  }

  // ── Dashboard mode ────────────────────────────────────────────────────────
  if (isDash) {
    // Hide sections 2 and 3
    document.getElementById('secLabel2').style.display = 'none';
    document.getElementById('secRow2').style.display   = 'none';
    document.getElementById('secLabel3').style.display = 'none';
    document.getElementById('secRow3').style.display   = 'none';

    // Hide marital, show inline slot
    document.getElementById('maritalCol').style.display    = 'none';
    document.getElementById('dashInlineSlot').style.display = '';

    // Which select to inject
    const dashMap = {
      'status':'fStatus', 'activity_status':'fStatus',
      'health_status':'fHealth', 'deeni_status':'fDeeni',
      'residential_status':'fResidential', 'gender':'fGender',
      'hof_fm_type':'fHOFType', 'its_sabeel_match':'fItsMatch',
    };
    const injectId = statusP ? 'fStatus' : itsMatchP ? 'fItsMatch' : (dashMap[legFilter] || null);

    if (injectId) {
      const orig = document.getElementById(injectId);
      if (orig) {
        const parentDiv = orig.closest('div');
        if (parentDiv) {
          const clone    = parentDiv.cloneNode(true);
          const cloneSel = clone.querySelector('select');
          if (cloneSel) {
            // BUG FIX: rename original ID so getElementById always finds the visible clone
            orig.id      = injectId + '_hidden';
            cloneSel.id  = injectId;
            const val    = statusP ? cap(statusP) : itsMatchP ? itsMatchP : legValue;
            cloneSel.value = val;
            cloneSel.addEventListener('change', run);
          }
          document.getElementById('dashInlineSlot').appendChild(clone);
        }
      }
    }

    setDashTitle(p);
  }

  run();
}

function setDashTitle(p) {
  const lf = (p.get('filter') || '').toLowerCase();
  const lv = p.get('value')  || '';
  const st = p.get('status') || '';
  const im = p.get('its_sabeel_match') || '';
  const mn = p.get('min') || '', mx = p.get('max') || '';
  const map = {
    'active':'Active Members','inactive':'Inactive Members',
    'its_sabeel_both_khar':'ITS & Sabeel both in Khar',
    'its_khar_sabeel_out':'ITS in Khar, Sabeel Outside',
    'sabeel_khar_its_out':'Sabeel in Khar, ITS Outside',
    'both_not_khar':'Both not in Khar',
  };
  const md = p.get('madresa_deprived');
  const t = (mn === '5' && mx === '15' ? (md === '1' ? 'Deeni Taalim Not Taking (Age 5-15)' : (md === '0' ? 'Deeni Taalim Taking (Age 5-15)' : 'Deeni Taalim Eligible (Age 5-15)')) : '')
    || map[st.toLowerCase()] || map[im]
    || (lf==='all'?'All Members':'')
    || (lf==='hof_fm_type'&&lv.toUpperCase()==='HOF'?'HOF Members':'')
    || (lf==='hof_fm_type'&&lv.toUpperCase()==='FM'?'Family Members':'')
    || (lf==='gender'&&lv.toLowerCase()==='male'?'Gents':'')
    || (lf==='gender'&&lv.toLowerCase()==='female'?'Ladies':'')
    || (lf==='age_range'&&mn==='0' &&mx==='4' ?'Age 0-4 yrs':'')
    || (lf==='age_range'&&mn==='5' &&mx==='15'?'Age 5-15 yrs':'')
    || (lf==='age_range'&&mn==='16'&&mx==='25'?'Age 16-25 yrs':'')
    || (lf==='age_range'&&mn==='26'&&mx==='65'?'Age 26-65 yrs':'')
    || (lf==='age_range'&&mn==='66'?'Above 65 yrs':'')
    || (lf==='health_status'     &&lv?'Health: '+lv:'')
    || (lf==='deeni_status'      &&lv?'Deeni: '+lv:'')
    || (lf==='residential_status'&&lv?'Residential: '+lv:'')
    || (lf&&lv?(lf==='leavestatus'?'Ohbat Status':lf.replace(/_/g,' '))+': '+lv:'');
  if (t) {
    document.getElementById('dashTitleText').textContent = t;
    document.getElementById('dashTitle').style.display = '';
  }
}

// ── Core filter ───────────────────────────────────────────────────────────────
function run() {
  const name    = (document.getElementById('fName').value || '').toLowerCase().trim();
  const sector  = document.getElementById('fSector').value;
  const sub     = document.getElementById('fSubSector').value;
  const marital = document.getElementById('fMarital').value;
  const hof     = document.getElementById('fHOF').value;
  const ageMin  = document.getElementById('fAgeMin').value !== '' ? parseInt(document.getElementById('fAgeMin').value) : null;
  const ageMax  = document.getElementById('fAgeMax').value !== '' ? parseInt(document.getElementById('fAgeMax').value) : null;

  // These may live in secRow2/3 (normal) OR in dashInlineSlot (dashboard)
  // getElementById finds whichever has the real ID (original renamed to _hidden in dash mode)
  const status   = gv('fStatus');
  const gender   = gv('fGender').toLowerCase();
  const mType    = gv('fMemberType');
  const hofType  = gv('fHOFType').toUpperCase();
  const health   = gv('fHealth');
  const deeni    = gv('fDeeni');
  const resi     = gv('fResidential');
  const itsMatch = gv('fItsMatch');

  const form       = document.getElementById('filtersForm');
  const dsGender   = (form.dataset.gender    || '').toLowerCase();
  const dsHofType  = (form.dataset.hofFmType || '').toUpperCase();
  const dsMadresa  = form.dataset.madresa    || '';
  const dsLegField = (form.dataset.legacyField || '').toLowerCase();
  const dsLegValue =  form.dataset.legacyValue || '';

  filtered = ALL_DATA.filter(u => {
    // Name / ITS / Mobile search
    if (name) {
      const nm  = (u.Full_Name || '').toLowerCase();
      const its = String(u.ITS_ID || u.ITS || '').toLowerCase();
      const mob = (u.Mobile || '').toLowerCase();
      if (!nm.includes(name) && !its.includes(name) && !mob.includes(name)) return false;
    }
    if (sector  && u.Sector     !== sector)  return false;
    if (sub     && u.Sub_Sector !== sub)      return false;
    if (marital && (u.Marital_Status || '').trim().toLowerCase() !== marital.toLowerCase()) return false;
    if (hof     && String(u.HOF_ID || u.HOF || '') !== hof) return false;
    if (ageMin !== null && (parseInt(u.Age) || 0) < ageMin) return false;
    if (ageMax !== null && (parseInt(u.Age) || 0) > ageMax) return false;

    // Active / Inactive
    if (status) {
      const inact = (u.Inactive_Status || u.inactive_status || '').trim();
      const act   = (u.activity_status || '').toLowerCase();
      const isAct = !inact && (!act || act === 'active');
      if (status === 'Active'   && !isAct) return false;
      if (status === 'Inactive' &&  isAct) return false;
    }

    // Gender (UI select OR dataset from URL)
    const wg = gender || dsGender;
    if (wg && (u.Gender || '').toLowerCase() !== wg) return false;

    if (mType && u.Member_Type !== mType) return false;

    // HOF/FM type (UI select OR dataset from URL)
    const wh = hofType || dsHofType;
    if (wh && (u.HOF_FM_TYPE || '').toUpperCase() !== wh) return false;

    if (health && (u.health_status      || '').trim() !== health)  return false;
    if (deeni  && (u.deeni_status       || '').trim() !== deeni)   return false;
    if (resi   && (u.residential_status || '').trim() !== resi)    return false;
    if (itsMatch && (u.its_sabeel_match || '')         !== itsMatch) return false;

    if (dsMadresa && String(u.madresa_deprived ?? '') !== dsMadresa) return false;

    // Generic legacy field=value (e.g. Qualification, Occupation)
    if (dsLegField && dsLegValue) {
      const k = Object.keys(u).find(x => x.toLowerCase() === dsLegField);
      if (!k || (u[k] || '').toString().trim().toLowerCase() !== dsLegValue.toLowerCase()) return false;
    }

    return true;
  });

  // Re-apply sort if active
  if (sortCol) applySortToFiltered();

  renderTable();
  renderChips();

  const n = filtered.length;
  document.getElementById('countBadge').textContent  = n + ' result' + (n !== 1 ? 's' : '');
  document.getElementById('resultsCount').textContent = n + ' member' + (n !== 1 ? 's' : '') + ' found';
}

// ── Sort helper ───────────────────────────────────────────────────────────────
function applySortToFiltered() {
  filtered.sort((a, b) => {
    let va, vb;
    if (sortCol === '_status') {
      const st = u => {
        const inact = (u.Inactive_Status || u.inactive_status || '').trim();
        const act   = (u.activity_status || '').toLowerCase();
        return (!inact && (!act || act === 'active')) ? 'Active' : 'Inactive';
      };
      va = st(a); vb = st(b);
    } else if (sortCol === 'Age') {
      return sortDir === 'asc'
        ? (parseInt(a.Age) || 0) - (parseInt(b.Age) || 0)
        : (parseInt(b.Age) || 0) - (parseInt(a.Age) || 0);
    } else {
      va = (a[sortCol] || '').toString().toLowerCase();
      vb = (b[sortCol] || '').toString().toLowerCase();
    }
    if (va < vb) return sortDir === 'asc' ? -1 :  1;
    if (va > vb) return sortDir === 'asc' ?  1 : -1;
    return 0;
  });
}

// ── Render table ──────────────────────────────────────────────────────────────
function renderTable() {
  const tbody = document.getElementById('tbody');
  tbody.innerHTML = '';

  if (!filtered.length) {
    tbody.innerHTML = '<tr class="empty-row"><td colspan="12"><i class="fa fa-search"></i> No members found.</td></tr>';
    return;
  }

  const redirectParam = encodeURIComponent(window.location.pathname + window.location.search);

  // Helper to build a row's innerHTML
  function rowHTML(u, rowNum) {
    const isHOF = (u.HOF_FM_TYPE || '').toUpperCase() === 'HOF';
    const act   = (u.activity_status || '').toLowerCase();
    const inact = (u.Inactive_Status || u.inactive_status || '').trim();
    const isAct = !inact && (!act || act === 'active');
    const badge = isAct ? '<span class="badge-active">Active</span>'
      : act === 'temporary' ? '<span class="badge-temp">Temporary</span>'
      : '<span class="badge-inactive">Inactive</span>';

    return `<td style="color:#9ca3af;font-weight:600;font-size:.78rem;">${rowNum}</td>` +
      `<td><div style="font-weight:${isHOF?700:500};">${esc(u.Full_Name||'')}` +
        (isHOF ? '<span class="pill-hof">HOF</span>' : '<span class="pill-fm">FM</span>') +
      `</div></td>` +
      `<td><span class="badge-its">${esc(String(u.ITS_ID||u.ITS||''))}</span></td>` +
      `<td>${esc(u.Age||'—')}</td>` +
      `<td style="text-transform:capitalize;">${esc(u.Gender||'—')}</td>` +
      `<td><div style="font-size:.78rem;">${esc(u.Sector||'—')}</div><div style="font-size:.7rem;color:#9ca3af;">${esc(u.Sub_Sector||'')}</div></td>` +
      `<td style="font-size:.78rem;">${esc(u.Mobile||'—')}</td>` +
      `<td>${badge}</td>` +
      `<td style="font-size:.75rem;">${esc(u.health_status||'—')}</td>` +
      `<td style="font-size:.75rem;">${esc(u.deeni_status||'—')}</td>` +
      `<td style="font-size:.75rem;">${esc(u.residential_status||'—')}</td>` +
      `<td>` +
        `<a href="${VIEW_URL}${u.ITS_ID}" class="act-btn act-view" title="View"><i class="fa fa-eye"></i></a>` +
        (CAN_EDIT ? `<a href="${EDIT_URL}${u.ITS_ID}?redirect=${redirectParam}" class="act-btn act-edit ms-1" title="Edit"><i class="fa fa-pencil"></i></a>` : '') +
      `</td>`;
  }

  // Sorted mode: flat list (no HOF grouping, as order would conflict)
  if (sortCol) {
    filtered.forEach((u, i) => {
      const tr = tbody.insertRow();
      if ((u.HOF_FM_TYPE || '').toUpperCase() === 'HOF') tr.className = 'hof-row';
      tr.innerHTML = rowHTML(u, i + 1);
    });
    return;
  }

  // Default mode: grouped by HOF family
  const groups = {}, order = [];
  filtered.forEach(u => {
    const hid = (u.HOF_ID || u.HOF || u.ITS_ID || '').toString();
    if (!groups[hid]) { groups[hid] = { hid, hname: itsMap[hid] || u.HOF_Name || hid, members:[] }; order.push(hid); }
    groups[hid].members.push(u);
  });
  const seen = new Set();
  const sortedGroups = order
    .filter(k => { if (seen.has(k)) return false; seen.add(k); return true; })
    .map(k => groups[k])
    .sort((a, b) => (a.hname||'').localeCompare(b.hname||''));

  let idx = 1;
  sortedGroups.forEach((grp, gi) => {
    if (gi > 0) {
      const sep = tbody.insertRow();
      sep.className = 'family-sep';
      sep.innerHTML = '<td colspan="12"></td>';
    }
    grp.members.forEach(u => {
      const tr = tbody.insertRow();
      if ((u.HOF_FM_TYPE || '').toUpperCase() === 'HOF') tr.className = 'hof-row';
      tr.innerHTML = rowHTML(u, idx++);
    });
  });
}

// ── Chips ─────────────────────────────────────────────────────────────────────
function renderChips() {
  const ITS_L = {
    its_sabeel_both_khar:'ITS & Sabeel in Khar', its_khar_sabeel_out:'ITS in Khar',
    sabeel_khar_its_out:'Sabeel in Khar', both_not_khar:'Both Not in Khar',
  };
  const defs = [
    ['fName','Name'],['fSector','Sector'],['fSubSector','Sub Sector'],
    ['fMarital','Marital'],['fAgeMin','Age ≥'],['fAgeMax','Age ≤'],['fHOF','HOF'],
    ['fStatus','Status'],['fGender','Gender'],['fMemberType','Member Type'],
    ['fHOFType','HOF/FM'],['fHealth','Health'],['fDeeni','Deeni'],
    ['fResidential','Residential'],['fItsMatch','ITS Match'],
  ];
  const row = document.getElementById('chipRow');
  row.innerHTML = '';
  let any = false;

  defs.forEach(([id, label]) => {
    const el = document.getElementById(id);
    if (!el || !el.value) return;
    any = true;
    const display = id === 'fHOF'      ? (el.options[el.selectedIndex]?.text || el.value)
                  : id === 'fItsMatch' ? (ITS_L[el.value] || el.value)
                  : el.value;
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.innerHTML = `<b>${esc(label)}:</b>&nbsp;${esc(display)} <span class="chip-x" data-id="${id}">&times;</span>`;
    row.appendChild(chip);
  });

  if (any) {
    const cl = document.createElement('span');
    cl.className = 'chip-clear';
    cl.innerHTML = '&times; Clear all';
    cl.onclick = resetAll;
    row.appendChild(cl);
  }

  // BUG FIX: re-attach chip-x listeners after every render
  row.querySelectorAll('.chip-x').forEach(x => {
    x.addEventListener('click', () => {
      const el = document.getElementById(x.dataset.id);
      if (el) { el.value = ''; run(); }
    });
  });
}

// ── Reset ─────────────────────────────────────────────────────────────────────
function resetAll() {
  document.getElementById('filtersForm').reset();
  const form = document.getElementById('filtersForm');
  ['gender','hofFmType','madresa','legacyField','legacyValue'].forEach(k => delete form.dataset[k]);

  // BUG FIX: restore renamed _hidden IDs back to originals
  document.querySelectorAll('[id$="_hidden"]').forEach(el => { el.id = el.id.replace('_hidden', ''); });

  // Restore hidden sections
  ['secLabel2','secRow2','secLabel3','secRow3','maritalCol'].forEach(id => {
    document.getElementById(id).style.display = '';
  });
  const slot = document.getElementById('dashInlineSlot');
  slot.style.display = 'none';
  slot.innerHTML = '';

  document.getElementById('dashTitle').style.display = 'none';
  document.getElementById('chipRow').innerHTML = '';

  sortCol = null; sortDir = 'asc';
  document.querySelectorAll('th.sortable').forEach(th => th.classList.remove('asc','desc'));

  history.replaceState(null, '', window.location.pathname);
  filtered = [...ALL_DATA];
  renderTable();
  const n = filtered.length;
  document.getElementById('resultsCount').textContent = n + ' members';
  document.getElementById('countBadge').textContent   = '';
}

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
  if (!filtered.length) { alert('No data to export.'); return; }
  const preferred = ['ITS_ID','Full_Name','Age','Gender','Sector','Sub_Sector','Mobile','Email',
    'Marital_Status','HOF_FM_TYPE','Member_Type','activity_status','health_status',
    'deeni_status','residential_status','its_sabeel_match','Qualification','Occupation','Address','Vatan'];
  const extra = new Set();
  filtered.forEach(r => Object.keys(r).forEach(k => { if (!preferred.includes(k)) extra.add(k); }));
  const headers = [...preferred, ...Array.from(extra)];
  let csv = headers.map(h => '"' + h + '"').join(',') + '\n';
  filtered.forEach(row => {
    csv += headers.map(h => '"' + (row[h] ?? '').toString().replace(/"/g, '""') + '"').join(',') + '\n';
  });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(new Blob(['\uFEFF' + csv], {type:'text/csv;charset=utf-8;'}));
  link.download = 'mumineen_' + new Date().toISOString().slice(0,10) + '.csv';
  document.body.appendChild(link); link.click(); document.body.removeChild(link);
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function gv(id) { const el = document.getElementById(id); return el ? el.value : ''; }
function setv(id, val) { const el = document.getElementById(id); if (el && val !== null && val !== undefined) el.value = val; }
function cap(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1).toLowerCase() : ''; }

// ── Event listeners ───────────────────────────────────────────────────────────
['fSector','fSubSector','fMarital','fHOF','fStatus','fGender','fMemberType',
 'fHOFType','fHealth','fDeeni','fResidential','fItsMatch'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('change', run);
});
['fName','fAgeMin','fAgeMax'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('input', run);
});

document.getElementById('btnReset').addEventListener('click', resetAll);

document.getElementById('btnToggle').addEventListener('click', function() {
  const body     = document.getElementById('filterBody');
  const willHide = !body.classList.contains('d-none');
  body.classList.toggle('d-none');
  this.innerHTML = willHide
    ? '<i class="fa fa-chevron-down"></i> Show Filters'
    : '<i class="fa fa-chevron-up"></i> Hide Filters';
});

// ── Column sorting ────────────────────────────────────────────────────────────
document.querySelectorAll('th.sortable').forEach(th => {
  th.addEventListener('click', function() {
    const col = this.dataset.col;
    sortDir = (sortCol === col && sortDir === 'asc') ? 'desc' : 'asc';
    sortCol = col;
    document.querySelectorAll('th.sortable').forEach(t => t.classList.remove('asc','desc'));
    this.classList.add(sortDir);
    applySortToFiltered();
    renderTable();
  });
});
</script>