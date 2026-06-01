<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#b8860b;--gold-light:#e6c84a;--gold-muted:#f5e9c0;
  --bg:#faf7f0;--surface:#fff;--surface-2:#f7f4ec;
  --border:#e8e0cc;--border-light:#f0ece0;
  --text-1:#1a1610;--text-2:#5a5244;--text-3:#9c8f7a;
  --green:#1a6645;--green-bg:#eaf4ee;
  --red:#b91c1c;--red-bg:#fef2f2;
  --blue:#1d4ed8;--blue-bg:#eff6ff;
  --amber:#b45309;--amber-bg:#fffbeb;
  --purple:#5b21b6;--purple-bg:#f5f3ff;
  --sh:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --sh2:0 4px 16px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
}

/* ── Page wrapper ── */
.md-wrap{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);max-width:1300px;margin:15 auto;padding:18px}
.md-wrap *{box-sizing:border-box}

/* ── Top bar ── */
.md-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;}
.md-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s}
.md-back:hover{background:var(--gold-muted);border-color:var(--gold);color:var(--gold);text-decoration:none}
.md-export{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:var(--green-bg);border:1.5px solid rgba(26,102,69,.25);color:var(--green);font-size:.75rem;font-weight:700;cursor:pointer;transition:all .15s}
.md-export:hover{background:#d1fae5}

/* ── Filter card ── */
.fc{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh);margin-bottom:12px;overflow:hidden}
.fc-head{background:linear-gradient(135deg,#78520a,var(--gold));padding:10px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px}
.fc-head-left{display:flex;align-items:center;gap:8px;font-weight:800;font-size:.8rem;color:#fff;letter-spacing:.4px}
.fc-head-right{display:flex;align-items:center;gap:6px}
.fc-count{background:rgba(255,255,255,.2);color:#fff;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:800}
.fc-btn{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:7px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.12);color:#fff;font-size:.72rem;font-weight:700;cursor:pointer;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif}
.fc-btn:hover{background:rgba(255,255,255,.22)}
.fc-body{padding:14px;display:none}
.fc-body.open{display:block}

/* ── Filter section label ── */
.fsec{display:flex;align-items:center;gap:6px;font-size:.62rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin:12px 0 8px}
.fsec::after{content:'';flex:1;height:1px;background:var(--border-light)}
.fsec:first-child{margin-top:0}
.fsec i{font-size:.72rem}

/* ── Filter grid ── */
.fgrid{display:grid;gap:8px;margin-bottom:4px}
.fg-6{grid-template-columns:2fr 1fr 1fr 1fr 1.1fr 1fr}
.fg-4{grid-template-columns:repeat(4,1fr)}
.flabel{display:block;font-size:.67rem;font-weight:700;color:var(--text-2);margin-bottom:4px;letter-spacing:.2px}
.finput,.fselect{width:100%;height:32px;padding:0 9px;border:1.5px solid var(--border);border-radius:7px;background:var(--surface-2);font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:var(--text-1);outline:none;transition:border-color .15s,background .15s}
.finput:focus,.fselect:focus{border-color:var(--gold);background:var(--surface);box-shadow:0 0 0 3px rgba(184,134,11,.1)}
.age-row{display:flex;gap:5px}
.age-row .finput{flex:1}

/* ── Chips ── */
.md-chips{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px}
.chip{display:inline-flex;align-items:center;gap:4px;background:var(--gold-muted);color:var(--gold);border:1px solid rgba(184,134,11,.3);border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700}
.chip-x{cursor:pointer;opacity:.6;font-size:.8rem;line-height:1}
.chip-x:hover{opacity:1}
.chip-clear{display:inline-flex;align-items:center;gap:3px;background:var(--red-bg);color:var(--red);border:1px solid rgba(185,28,28,.2);border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700;cursor:pointer}
.chip-clear:hover{background:#fee2e2}

/* ── Dashboard banner ── */
.md-banner{background:var(--gold-muted);border-left:4px solid var(--gold);border-radius:0 8px 8px 0;padding:9px 14px;margin-bottom:10px;display:none;align-items:center;justify-content:space-between;gap:10px}
.md-banner.show{display:flex}
.md-banner-text{font-weight:800;font-size:.84rem;color:var(--gold)}
.md-banner-clear{padding:4px 10px;border-radius:7px;border:1px solid rgba(184,134,11,.3);background:var(--surface);color:var(--gold);font-size:.72rem;font-weight:700;cursor:pointer;transition:background .15s}
.md-banner-clear:hover{background:var(--gold-muted)}

/* ── Results bar ── */
.md-results{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:6px}
.md-results-count{font-size:.76rem;color:var(--text-2);font-weight:700}
.md-results-count span{color:var(--gold);font-weight:800}

/* ── Table card ── */
.md-tcard{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh);overflow:hidden}
.md-tscroll{overflow-x:auto}
table.dir{width:100%;border-collapse:collapse;font-size:.78rem;min-width:980px}
table.dir thead th{background:linear-gradient(to bottom,var(--surface-2),#f0ebe0);padding:9px 11px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:var(--text-3);border-bottom:2px solid var(--border);white-space:nowrap;user-select:none;text-align:left;position:sticky;top:0;z-index:1}
table.dir th.sortable{cursor:pointer;transition:color .14s}
table.dir th.sortable:hover{color:var(--gold)}
table.dir th .si{margin-left:3px;opacity:.3;font-size:.57rem}
table.dir th.asc .si::after{content:'▲';opacity:1;color:var(--gold)}
table.dir th.desc .si::after{content:'▼';opacity:1;color:var(--gold)}
table.dir th:not(.asc):not(.desc) .si::after{content:'⇅'}
table.dir tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s}
table.dir tbody tr:hover{background:#fdf9ef}
table.dir td{padding:8px 11px;vertical-align:middle;color:var(--text-1)}
table.dir tr.hof-row td{background:#fffbeb;font-weight:700;border-top:1.5px solid rgba(184,134,11,.25)}
table.dir tr.hof-row td:first-child{border-left:3px solid var(--gold)}
table.dir tr.family-sep td{padding:0;height:4px;background:var(--surface-2);border:none}
table.dir .empty-row td{text-align:center;padding:36px;color:var(--text-3);font-size:.84rem}

/* ── Inline badges ── */
.pill-hof{display:inline-block;background:var(--gold);color:#fff;font-size:.52rem;font-weight:800;padding:1px 6px;border-radius:20px;margin-left:5px;vertical-align:middle}
.pill-fm{display:inline-block;background:var(--surface-2);color:var(--text-2);border:1px solid var(--border);font-size:.52rem;font-weight:700;padding:1px 6px;border-radius:20px;margin-left:5px;vertical-align:middle}
.bid{display:inline-block;background:#0c447c;color:#fff;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:5px;letter-spacing:.2px}
.bact{display:inline-block;background:var(--green-bg);color:var(--green);border:1px solid rgba(26,102,69,.2);font-size:.63rem;font-weight:700;padding:2px 8px;border-radius:20px}
.binact{display:inline-block;background:var(--red-bg);color:var(--red);border:1px solid rgba(185,28,28,.2);font-size:.63rem;font-weight:700;padding:2px 8px;border-radius:20px}
.btemp{display:inline-block;background:var(--amber-bg);color:var(--amber);border:1px solid rgba(180,83,9,.2);font-size:.63rem;font-weight:700;padding:2px 8px;border-radius:20px}

/* ── Action buttons ── */
.act-btn{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;border:none;cursor:pointer;font-size:.72rem;text-decoration:none;transition:all .14s}
.act-btn:hover{opacity:.8;text-decoration:none}
.act-view{background:var(--blue-bg);color:var(--blue)}
.act-edit{background:var(--amber-bg);color:var(--amber)}

/* ── Responsive ── */
@media(max-width:992px){.fg-6,.fg-4{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){.fg-6,.fg-4{grid-template-columns:repeat(2,1fr)}}
@media(max-width:576px){.fg-6,.fg-4{grid-template-columns:1fr}.md-wrap{padding:10px}}
</style>

<?php
$view_base = 'admin/viewmember/';
if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2) {
  $view_base = 'amilsaheb/viewmember/';
}
$can_edit = isset($_SESSION['user']['role']) && in_array($_SESSION['user']['role'], [1, 3]);
$back_url_val = isset($back_url) ? $back_url : base_url('amilsaheb');
?>

<div class="md-wrap pt-5">

  <!-- Top bar -->
  <div class="md-topbar pt-2">
    <a href="<?php echo $back_url_val ?>" class="md-back">
      <i class="fa fa-arrow-left"></i> Back
    </a>
    <button class="md-export" onclick="exportCSV()">
      <i class="fa fa-file-excel-o"></i> Export Excel
    </button>
  </div>

  <!-- Filter card -->
  <div class="fc">
    <div class="fc-head">
      <div class="fc-head-left">
        <i class="fa fa-sliders"></i> Filters
        <span class="fc-count" id="countBadge"></span>
      </div>
      <div class="fc-head-right">
        <button class="fc-btn" id="btnReset"><i class="fa fa-refresh"></i> Reset</button>
        <button class="fc-btn" id="btnToggle"><i class="fa fa-chevron-down"></i> Show</button>
      </div>
    </div>

    <div id="filterBody" class="fc-body">
      <form id="filtersForm" onsubmit="return false;">

        <!-- Search & Location -->
        <div class="fsec"><i class="fa fa-search" style="color:var(--blue)"></i> Search &amp; Location</div>
        <div class="fgrid fg-6" id="baseFilterRow">
          <div>
            <label class="flabel">Name or ITS</label>
            <input type="text" id="fName" class="finput" placeholder="Name / ITS ID…">
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
          <div id="maritalCol">
            <label class="flabel">Marital Status</label>
            <select id="fMarital" class="fselect"><option value="">All</option></select>
          </div>
          <div id="dashInlineSlot" style="display:none"></div>
        </div>

        <!-- Member Details -->
        <div class="fsec" id="secLabel2"><i class="fa fa-user" style="color:var(--purple)"></i> Member Details</div>
        <div class="fgrid fg-4" id="secRow2">
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

        <!-- Status Filters -->
        <div class="fsec" id="secLabel3"><i class="fa fa-heartbeat" style="color:var(--red)"></i> Status Filters</div>
        <div class="fgrid fg-4" id="secRow3">
          <div>
            <label class="flabel">Health Status</label>
            <select id="fHealth" class="fselect">
              <option value="">All</option>
              <option value="Healthy">Fit &amp; Healthy</option>
              <option value="Medically Unfit">Handicapped / Medically Unfit</option>
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
              <option value="Misaq Not Given">Misaq Not Given</option>
              <option value="Mustajeeb">Mustajeeb</option>
              <option value="No Ashara / LQ">No Ashara / LQ (3 yrs)</option>
              <option value="No Vajebaat / Sabeel">No Vajebaat / Sabeel (3 yrs)</option>
              <option value="Zero Days Scanned in Ashara Mubaraka">Zero Days Scanned in Ashara</option>
            </select>
          </div>
          <div>
            <label class="flabel">Residential Status</label>
            <select id="fResidential" class="fselect">
              <option value="">All</option>
              <option value="Residing in Khar">Residing in Khar</option>
              <option value="Madresa in Khar">Madresa in Khar</option>
              <option value="FMB Thaali in Khar">FMB Thaali in Khar</option>
              <option value="Moved for Job">Moved for Job</option>
              <option value="Moved for Studies">Moved for Studies</option>
              <option value="Moved after Marriage">Moved after Marriage</option>
              <option value="Permanently Migrated">Permanently Migrated</option>
              <option value="Unknown or Not Traceable">Unknown / Not Traceable</option>
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

  <!-- Active filter chips -->
  <div class="md-chips" id="chipRow"></div>

  <!-- Dashboard banner -->
  <div class="md-banner" id="dashTitle">
    <span class="md-banner-text" id="dashTitleText"></span>
    <button class="md-banner-clear" onclick="resetAll()">&#x2715; Clear Filter</button>
  </div>

  <!-- Results bar -->
  <div class="md-results">
    <span class="md-results-count" id="resultsCount"></span>
  </div>

  <!-- Table -->
  <div class="md-tcard">
    <div class="md-tscroll">
      <table class="dir">
        <thead>
          <tr>
            <th style="width:38px">#</th>
            <th class="sortable" data-col="Full_Name">Name <span class="si"></span></th>
            <th class="sortable" data-col="ITS_ID">ITS ID <span class="si"></span></th>
            <th class="sortable" data-col="Age">Age <span class="si"></span></th>
            <th class="sortable" data-col="Gender">Gender <span class="si"></span></th>
            <th class="sortable" data-col="Sector">Sector / Sub Sector <span class="si"></span></th>
            <th>Mobile</th>
            <th class="sortable" data-col="_status">Status <span class="si"></span></th>
            <th class="sortable" data-col="health_status">Health <span class="si"></span></th>
            <th class="sortable" data-col="deeni_status">Deeni <span class="si"></span></th>
            <th class="sortable" data-col="residential_status">Residential <span class="si"></span></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
  </div>

</div><!-- /.md-wrap -->

<script>
const ALL_DATA = <?= json_encode(isset($all_users) ? $all_users : $users) ?>;
const VIEW_URL = '<?= base_url($view_base) ?>';
const EDIT_URL = '<?= base_url('admin/editmember/') ?>';
const CAN_EDIT = <?= $can_edit ? 'true' : 'false' ?>;

let filtered = [...ALL_DATA];
let sortCol = null, sortDir = 'asc';

const itsMap = {};
ALL_DATA.forEach(u => { const k = String(u.ITS_ID||u.ITS||''); if(k) itsMap[k] = u.Full_Name||''; });

/* ── Boot ── */
fillSelects();
readURLAndApply();

/* ── Fill selects ── */
function fillSelects(){
  const sectors=new Set(),subs=new Set(),hofs=new Map(),marital=new Set(),mTypes=new Set();
  ALL_DATA.forEach(u=>{
    if(u.Sector) sectors.add(u.Sector);
    if(u.Sub_Sector) subs.add(u.Sub_Sector);
    const ms=(u.Marital_Status||'').trim();
    if(ms) marital.add(ms.charAt(0).toUpperCase()+ms.slice(1).toLowerCase());
    if(u.Member_Type) mTypes.add(u.Member_Type);
    const hid=(u.HOF_ID||u.HOF||'').toString();
    if(hid) hofs.set(hid,itsMap[hid]||u.HOF_Name||hid);
  });
  const fill=(id,items)=>{
    const el=document.getElementById(id);if(!el)return;
    items.forEach(([v,l])=>{const o=document.createElement('option');o.value=v;o.textContent=l;el.appendChild(o)});
  };
  fill('fSector',Array.from(sectors).sort().map(v=>[v,v]));
  fill('fSubSector',Array.from(subs).sort().map(v=>[v,v]));
  fill('fMemberType',Array.from(mTypes).sort().map(v=>[v,v]));
  fill('fHOF',Array.from(hofs.entries()).sort((a,b)=>(a[1]||'').localeCompare(b[1]||'')));
  const pref=['Single','Married','Engaged','Separated','Divorced','Widowed'];
  const rem=new Set(marital);
  const mEl=document.getElementById('fMarital');
  pref.forEach(v=>{if(rem.has(v)){const o=document.createElement('option');o.value=v;o.textContent=v;mEl.appendChild(o);rem.delete(v)}});
  Array.from(rem).sort().forEach(v=>{const o=document.createElement('option');o.value=v;o.textContent=v;mEl.appendChild(o)});
  document.getElementById('fSector').addEventListener('change',function(){
    const sel=this.value;const subEl=document.getElementById('fSubSector');
    subEl.querySelectorAll('option:not([value=""])').forEach(n=>n.remove());
    const subSet=new Set();
    ALL_DATA.forEach(u=>{if((!sel||u.Sector===sel)&&u.Sub_Sector)subSet.add(u.Sub_Sector)});
    Array.from(subSet).sort().forEach(v=>{const o=document.createElement('option');o.value=v;o.textContent=v;subEl.appendChild(o)});
  });
}

/* ── URL read ── */
function readURLAndApply(){
  const p=new URLSearchParams(window.location.search);
  const legFilter=(p.get('filter')||'').toLowerCase();
  const legValue=p.get('value')||'';
  const statusP=p.get('status')||'';
  const itsMatchP=p.get('its_sabeel_match')||'';
  const minP=p.get('min')||p.get('age_min')||'';
  const maxP=p.get('max')||p.get('age_max')||'';
  const madresaP=p.get('madresa_deprived')||'';
  const isDash=!!(( legFilter&&legFilter!=='all')||statusP||itsMatchP||madresaP||minP||maxP);
  setv('fName',p.get('name')||'');
  setv('fSector',p.get('sector')||'');
  if(statusP) setv('fStatus',cap(statusP));
  if(itsMatchP) setv('fItsMatch',itsMatchP);
  if(minP) document.getElementById('fAgeMin').value=minP;
  if(maxP) document.getElementById('fAgeMax').value=maxP;
  const form=document.getElementById('filtersForm');
  if(legFilter==='gender') form.dataset.gender=legValue;
  if(legFilter==='hof_fm_type') form.dataset.hofFmType=legValue;
  if(madresaP) form.dataset.madresa=madresaP;
  if(legFilter==='health_status') setv('fHealth',legValue);
  if(legFilter==='deeni_status') setv('fDeeni',legValue);
  if(legFilter==='residential_status') setv('fResidential',legValue);
  if(legFilter==='sector') setv('fSector',legValue);
  if(!['','all','age_range','sector','gender','hof_fm_type','health_status','deeni_status','residential_status','its_sabeel_match'].includes(legFilter)&&legValue){
    form.dataset.legacyField=legFilter;form.dataset.legacyValue=legValue;
  }
  if(isDash){
    document.getElementById('secLabel2').style.display='none';
    document.getElementById('secRow2').style.display='none';
    document.getElementById('secLabel3').style.display='none';
    document.getElementById('secRow3').style.display='none';
    document.getElementById('maritalCol').style.display='none';
    document.getElementById('dashInlineSlot').style.display='';
    const dashMap={'status':'fStatus','activity_status':'fStatus','health_status':'fHealth','deeni_status':'fDeeni','residential_status':'fResidential','gender':'fGender','hof_fm_type':'fHOFType','its_sabeel_match':'fItsMatch'};
    const injectId=statusP?'fStatus':itsMatchP?'fItsMatch':(dashMap[legFilter]||null);
    if(injectId){
      const orig=document.getElementById(injectId);
      if(orig){
        const parentDiv=orig.closest('div');
        if(parentDiv){
          const clone=parentDiv.cloneNode(true);
          const cloneSel=clone.querySelector('select');
          if(cloneSel){
            orig.id=injectId+'_hidden';
            cloneSel.id=injectId;
            const val=statusP?cap(statusP):itsMatchP?itsMatchP:legValue;
            cloneSel.value=val;
            cloneSel.addEventListener('change',run);
          }
          document.getElementById('dashInlineSlot').appendChild(clone);
        }
      }
    }
    setDashTitle(p);
  }
  run();
}

function setDashTitle(p){
  const lf=(p.get('filter')||'').toLowerCase(),lv=p.get('value')||'',st=p.get('status')||'',im=p.get('its_sabeel_match')||'',mn=p.get('min')||'',mx=p.get('max')||'',md=p.get('madresa_deprived');
  const map={'active':'Active Members','inactive':'Inactive Members','its_sabeel_both_khar':'ITS & Sabeel both in Khar','its_khar_sabeel_out':'ITS in Khar, Sabeel Outside','sabeel_khar_its_out':'Sabeel in Khar, ITS Outside','both_not_khar':'Both not in Khar'};
  const t=(mn==='5'&&mx==='15'?(md==='1'?'Deeni Taalim Not Taking (5-15)':md==='0'?'Deeni Taalim Taking (5-15)':'Deeni Taalim Eligible (5-15)'):'')||map[st.toLowerCase()]||map[im]||(lf==='all'?'All Members':'')
    ||(lf==='hof_fm_type'&&lv.toUpperCase()==='HOF'?'HOF Members':lf==='hof_fm_type'&&lv.toUpperCase()==='FM'?'Family Members':'')
    ||(lf==='gender'&&lv.toLowerCase()==='male'?'Gents':lf==='gender'&&lv.toLowerCase()==='female'?'Ladies':'')
    ||(lf==='age_range'?({mn:'0',mx:'4'}=={mn,mx}?'Age 0-4':mn==='5'&&mx==='15'?'Age 5-15':mn==='16'&&mx==='25'?'Age 16-25':mn==='26'&&mx==='65'?'Age 26-65':mn==='66'?'Above 65':''):'')
    ||(lf==='health_status'&&lv?'Health: '+lv:'')||(lf==='deeni_status'&&lv?'Deeni: '+lv:'')||(lf==='residential_status'&&lv?'Residential: '+lv:'')
    ||(lf&&lv?lf.replace(/_/g,' ')+': '+lv:'');
  if(t){document.getElementById('dashTitleText').textContent=t;document.getElementById('dashTitle').classList.add('show')}
}

/* ── Core filter ── */
function run(){
  const name=(document.getElementById('fName').value||'').toLowerCase().trim();
  const sector=document.getElementById('fSector').value;
  const sub=document.getElementById('fSubSector').value;
  const marital=document.getElementById('fMarital').value;
  const hof=document.getElementById('fHOF').value;
  const ageMin=document.getElementById('fAgeMin').value!==''?parseInt(document.getElementById('fAgeMin').value):null;
  const ageMax=document.getElementById('fAgeMax').value!==''?parseInt(document.getElementById('fAgeMax').value):null;
  const status=gv('fStatus'),gender=gv('fGender').toLowerCase(),mType=gv('fMemberType'),hofType=gv('fHOFType').toUpperCase();
  const health=gv('fHealth'),deeni=gv('fDeeni'),resi=gv('fResidential'),itsMatch=gv('fItsMatch');
  const form=document.getElementById('filtersForm');
  const dsGender=(form.dataset.gender||'').toLowerCase(),dsHofType=(form.dataset.hofFmType||'').toUpperCase();
  const dsMadresa=form.dataset.madresa||'',dsLegField=(form.dataset.legacyField||'').toLowerCase(),dsLegValue=form.dataset.legacyValue||'';

  filtered=ALL_DATA.filter(u=>{
    if(name){const nm=(u.Full_Name||'').toLowerCase(),its=String(u.ITS_ID||u.ITS||'').toLowerCase(),mob=(u.Mobile||'').toLowerCase();if(!nm.includes(name)&&!its.includes(name)&&!mob.includes(name))return false}
    if(sector&&u.Sector!==sector)return false;
    if(sub&&u.Sub_Sector!==sub)return false;
    if(marital&&(u.Marital_Status||'').trim().toLowerCase()!==marital.toLowerCase())return false;
    if(hof&&String(u.HOF_ID||u.HOF||'')!==hof)return false;
    if(ageMin!==null&&(parseInt(u.Age)||0)<ageMin)return false;
    if(ageMax!==null&&(parseInt(u.Age)||0)>ageMax)return false;
    if(status){const inact=(u.Inactive_Status||u.inactive_status||'').trim(),act=(u.activity_status||'').toLowerCase(),isAct=!inact&&(!act||act==='active');if(status==='Active'&&!isAct)return false;if(status==='Inactive'&&isAct)return false}
    const wg=gender||dsGender;if(wg&&(u.Gender||'').toLowerCase()!==wg)return false;
    if(mType&&u.Member_Type!==mType)return false;
    const wh=hofType||dsHofType;if(wh&&(u.HOF_FM_TYPE||'').toUpperCase()!==wh)return false;
    if(health&&(u.health_status||'').trim()!==health)return false;
    if(deeni&&(u.deeni_status||'').trim()!==deeni)return false;
    if(resi&&(u.residential_status||'').trim()!==resi)return false;
    if(itsMatch&&(u.its_sabeel_match||'')!==itsMatch)return false;
    if(dsMadresa&&String(u.madresa_deprived??'')!==dsMadresa)return false;
    if(dsLegField&&dsLegValue){const k=Object.keys(u).find(x=>x.toLowerCase()===dsLegField);if(!k||(u[k]||'').toString().trim().toLowerCase()!==dsLegValue.toLowerCase())return false}
    return true;
  });

  if(sortCol) applySortToFiltered();
  renderTable();
  renderChips();
  const n=filtered.length;
  document.getElementById('countBadge').textContent=n+' result'+(n!==1?'s':'');
  document.getElementById('resultsCount').innerHTML='<span>'+n+'</span> member'+(n!==1?'s':'')+' found';
}

/* ── Sort ── */
function applySortToFiltered(){
  filtered.sort((a,b)=>{
    if(sortCol==='_status'){const st=u=>{const inact=(u.Inactive_Status||u.inactive_status||'').trim(),act=(u.activity_status||'').toLowerCase();return(!inact&&(!act||act==='active'))?'Active':'Inactive'};const va=st(a),vb=st(b);return sortDir==='asc'?va.localeCompare(vb):vb.localeCompare(va)}
    if(sortCol==='Age')return sortDir==='asc'?(parseInt(a.Age)||0)-(parseInt(b.Age)||0):(parseInt(b.Age)||0)-(parseInt(a.Age)||0);
    const va=(a[sortCol]||'').toString().toLowerCase(),vb=(b[sortCol]||'').toString().toLowerCase();
    return sortDir==='asc'?va.localeCompare(vb):vb.localeCompare(va);
  });
}

/* ── Render table ── */
function renderTable(){
  const tbody=document.getElementById('tbody');
  tbody.innerHTML='';
  if(!filtered.length){tbody.innerHTML='<tr class="empty-row"><td colspan="12"><i class="fa fa-search"></i> No members found.</td></tr>';return}
  const rp=encodeURIComponent(window.location.pathname+window.location.search);
  function rowHTML(u,n){
    const isHOF=(u.HOF_FM_TYPE||'').toUpperCase()==='HOF';
    const act=(u.activity_status||'').toLowerCase(),inact=(u.Inactive_Status||u.inactive_status||'').trim();
    const isAct=!inact&&(!act||act==='active');
    const badge=isAct?'<span class="bact">Active</span>':act==='temporary'?'<span class="btemp">Temp</span>':'<span class="binact">Inactive</span>';
    return`<td style="color:var(--text-3);font-size:.72rem;font-weight:600">${n}</td>`+
      `<td><span style="font-weight:${isHOF?800:500}">${esc(u.Full_Name||'')}${isHOF?'<span class="pill-hof">HOF</span>':'<span class="pill-fm">FM</span>'}</span></td>`+
      `<td><span class="bid">${esc(String(u.ITS_ID||u.ITS||''))}</span></td>`+
      `<td>${esc(u.Age||'—')}</td>`+
      `<td style="text-transform:capitalize;font-size:.75rem">${esc(u.Gender||'—')}</td>`+
      `<td><div style="font-size:.76rem;font-weight:600">${esc(u.Sector||'—')}</div><div style="font-size:.68rem;color:var(--text-3)">${esc(u.Sub_Sector||'')}</div></td>`+
      `<td style="font-size:.75rem;color:var(--text-2)">${esc(u.Mobile||'—')}</td>`+
      `<td>${badge}</td>`+
      `<td style="font-size:.72rem;color:var(--text-2)">${esc(u.health_status||'—')}</td>`+
      `<td style="font-size:.72rem;color:var(--text-2)">${esc(u.deeni_status||'—')}</td>`+
      `<td style="font-size:.72rem;color:var(--text-2)">${esc(u.residential_status||'—')}</td>`+
      `<td><a href="${VIEW_URL}${u.ITS_ID}" class="act-btn act-view" title="View"><i class="fa fa-eye"></i></a>`+
      (CAN_EDIT?`<a href="${EDIT_URL}${u.ITS_ID}?redirect=${rp}" class="act-btn act-edit" style="margin-left:4px" title="Edit"><i class="fa fa-pencil"></i></a>`:'')+`</td>`;
  }
  if(sortCol){filtered.forEach((u,i)=>{const tr=tbody.insertRow();if((u.HOF_FM_TYPE||'').toUpperCase()==='HOF')tr.className='hof-row';tr.innerHTML=rowHTML(u,i+1)});return}
  const groups={},order=[];
  filtered.forEach(u=>{const hid=(u.HOF_ID||u.HOF||u.ITS_ID||'').toString();if(!groups[hid]){groups[hid]={hid,hname:itsMap[hid]||u.HOF_Name||hid,members:[]};order.push(hid)}groups[hid].members.push(u)});
  const seen=new Set();
  const sg=order.filter(k=>{if(seen.has(k))return false;seen.add(k);return true}).map(k=>groups[k]).sort((a,b)=>(a.hname||'').localeCompare(b.hname||''));
  let idx=1;
  sg.forEach((grp,gi)=>{
    if(gi>0){const sep=tbody.insertRow();sep.className='family-sep';sep.innerHTML='<td colspan="12"></td>'}
    grp.members.forEach(u=>{const tr=tbody.insertRow();if((u.HOF_FM_TYPE||'').toUpperCase()==='HOF')tr.className='hof-row';tr.innerHTML=rowHTML(u,idx++)});
  });
}

/* ── Chips ── */
function renderChips(){
  const ITS_L={its_sabeel_both_khar:'ITS & Sabeel in Khar',its_khar_sabeel_out:'ITS in Khar',sabeel_khar_its_out:'Sabeel in Khar',both_not_khar:'Both Not in Khar'};
  const defs=[['fName','Name'],['fSector','Sector'],['fSubSector','Sub Sector'],['fMarital','Marital'],['fAgeMin','Age ≥'],['fAgeMax','Age ≤'],['fHOF','HOF'],['fStatus','Status'],['fGender','Gender'],['fMemberType','Member Type'],['fHOFType','HOF/FM'],['fHealth','Health'],['fDeeni','Deeni'],['fResidential','Residential'],['fItsMatch','ITS Match']];
  const row=document.getElementById('chipRow');
  row.innerHTML='';let any=false;
  defs.forEach(([id,label])=>{
    const el=document.getElementById(id);if(!el||!el.value)return;any=true;
    const display=id==='fHOF'?(el.options[el.selectedIndex]?.text||el.value):id==='fItsMatch'?(ITS_L[el.value]||el.value):el.value;
    const chip=document.createElement('span');chip.className='chip';
    chip.innerHTML=`<b>${esc(label)}:</b>&nbsp;${esc(display)}&nbsp;<span class="chip-x" data-id="${id}">&times;</span>`;
    row.appendChild(chip);
  });
  if(any){const cl=document.createElement('span');cl.className='chip-clear';cl.innerHTML='&times; Clear all';cl.onclick=resetAll;row.appendChild(cl)}
  row.querySelectorAll('.chip-x').forEach(x=>{x.addEventListener('click',()=>{const el=document.getElementById(x.dataset.id);if(el){el.value='';run()}})});
}

/* ── Reset ── */
function resetAll(){
  document.getElementById('filtersForm').reset();
  const form=document.getElementById('filtersForm');
  ['gender','hofFmType','madresa','legacyField','legacyValue'].forEach(k=>delete form.dataset[k]);
  document.querySelectorAll('[id$="_hidden"]').forEach(el=>{el.id=el.id.replace('_hidden','')});
  ['secLabel2','secRow2','secLabel3','secRow3','maritalCol'].forEach(id=>document.getElementById(id).style.display='');
  const slot=document.getElementById('dashInlineSlot');slot.style.display='none';slot.innerHTML='';
  document.getElementById('dashTitle').classList.remove('show');
  document.getElementById('chipRow').innerHTML='';
  sortCol=null;sortDir='asc';
  document.querySelectorAll('th.sortable').forEach(th=>th.classList.remove('asc','desc'));
  history.replaceState(null,'',window.location.pathname);
  filtered=[...ALL_DATA];renderTable();
  document.getElementById('resultsCount').innerHTML='<span>'+filtered.length+'</span> members';
  document.getElementById('countBadge').textContent='';
}

/* ── Export CSV ── */
function exportCSV(){
  if(!filtered.length){alert('No data to export.');return}
  const pref=['ITS_ID','Full_Name','Age','Gender','Sector','Sub_Sector','Mobile','Email','Marital_Status','HOF_FM_TYPE','Member_Type','activity_status','health_status','deeni_status','residential_status','its_sabeel_match','Qualification','Occupation','Address','Vatan'];
  const extra=new Set();filtered.forEach(r=>Object.keys(r).forEach(k=>{if(!pref.includes(k))extra.add(k)}));
  const headers=[...pref,...Array.from(extra)];
  let csv=headers.map(h=>'"'+h+'"').join(',')+'\n';
  filtered.forEach(row=>{csv+=headers.map(h=>'"'+(row[h]??'').toString().replace(/"/g,'""')+'"').join(',')+'\n'});
  const link=document.createElement('a');link.href=URL.createObjectURL(new Blob(['\uFEFF'+csv],{type:'text/csv;charset=utf-8;'}));
  link.download='mumineen_'+new Date().toISOString().slice(0,10)+'.csv';
  document.body.appendChild(link);link.click();document.body.removeChild(link);
}

/* ── Utils ── */
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
function gv(id){const el=document.getElementById(id);return el?el.value:''}
function setv(id,val){const el=document.getElementById(id);if(el&&val!=null)el.value=val}
function cap(s){return s?s.charAt(0).toUpperCase()+s.slice(1).toLowerCase():''}

/* ── Events ── */
['fSector','fSubSector','fMarital','fHOF','fStatus','fGender','fMemberType','fHOFType','fHealth','fDeeni','fResidential','fItsMatch'].forEach(id=>{const el=document.getElementById(id);if(el)el.addEventListener('change',run)});
['fName','fAgeMin','fAgeMax'].forEach(id=>{const el=document.getElementById(id);if(el)el.addEventListener('input',run)});
document.getElementById('btnReset').addEventListener('click',resetAll);
document.getElementById('btnToggle').addEventListener('click',function(){
  const body=document.getElementById('filterBody');
  const willHide=body.classList.contains('open');
  body.classList.toggle('open');
  this.innerHTML=willHide?'<i class="fa fa-chevron-down"></i> Show':'<i class="fa fa-chevron-up"></i> Hide';
});
document.querySelectorAll('th.sortable').forEach(th=>{
  th.addEventListener('click',function(){
    const col=this.dataset.col;
    sortDir=(sortCol===col&&sortDir==='asc')?'desc':'asc';
    sortCol=col;
    document.querySelectorAll('th.sortable').forEach(t=>t.classList.remove('asc','desc'));
    this.classList.add(sortDir);
    applySortToFiltered();renderTable();
  });
});
</script>