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

.mumineen-container {
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--bg);
  color:var(--text-1);
  max-width:1500px;
  margin:15px auto;
  padding:18px;
}
.mumineen-container *{box-sizing:border-box}

.md-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;}
.md-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s}
.md-back:hover{background:var(--gold-muted);border-color:var(--gold);color:var(--gold);text-decoration:none}
.md-export{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:8px;background:var(--green-bg);border:1.5px solid rgba(26,102,69,.25);color:var(--green);font-size:.75rem;font-weight:700;cursor:pointer;transition:all .15s}
.md-export:hover{background:#d1fae5}

.fc{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh);margin-bottom:12px;overflow:hidden}
.fc-head{background:linear-gradient(135deg,#78520a,var(--gold));padding:10px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px}
.fc-head-left{display:flex;align-items:center;gap:8px;font-weight:800;font-size:.8rem;color:#fff;letter-spacing:.4px}
.fc-head-right{display:flex;align-items:center;gap:6px}
.fc-count{background:rgba(255,255,255,.2);color:#fff;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:800}
.fc-btn{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:7px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.12);color:#fff;font-size:.72rem;font-weight:700;cursor:pointer;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif}
.fc-btn:hover{background:rgba(255,255,255,.22)}
.fc-body{padding:14px;}

.fsec{display:flex;align-items:center;gap:6px;font-size:.62rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);margin:12px 0 8px}
.fsec::after{content:'';flex:1;height:1px;background:var(--border-light)}
.fsec:first-child{margin-top:0}
.fsec i{font-size:.72rem}

.frow{display:grid;gap:8px;margin-bottom:4px;align-items:end;}
.frow-6{grid-template-columns:2fr 1fr 1fr 1fr 1.1fr 1fr}
.frow-4{grid-template-columns:repeat(4,1fr)}
.frow-3{grid-template-columns:repeat(3,1fr)}
.flabel{display:block;font-size:.67rem;font-weight:700;color:var(--text-2);margin-bottom:4px;letter-spacing:.2px}
.finput,.fselect{width:100%;height:32px;padding:0 9px;border:1.5px solid var(--border);border-radius:7px;background:var(--surface-2);font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:var(--text-1);outline:none;transition:border-color .15s,background .15s}
.finput:focus,.fselect:focus{border-color:var(--gold);background:var(--surface);box-shadow:0 0 0 3px rgba(184,134,11,.1)}
.age-row{display:flex;gap:5px}
.age-row .finput{flex:1}

.md-chips{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px}
.chip{display:inline-flex;align-items:center;gap:4px;background:var(--gold-muted);color:var(--gold);border:1px solid rgba(184,134,11,.3);border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700}
.chip-x{cursor:pointer;opacity:.6;font-size:.8rem;line-height:1}
.chip-x:hover{opacity:1}
.chip-clear{display:inline-flex;align-items:center;gap:3px;background:var(--red-bg);color:var(--red);border:1px solid rgba(185,28,28,.2);border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700;cursor:pointer}
.chip-clear:hover{background:#fee2e2}
.chip-excl{display:inline-flex;align-items:center;gap:4px;background:#fff0f0;color:var(--red);border:1px solid rgba(185,28,28,.35);border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700}
/* Exclude checkbox dropdown */
.excl-wrap{position:relative;width:100%}
.excl-trigger{display:flex;align-items:center;justify-content:space-between;width:100%;height:32px;padding:0 10px;border:1.5px solid rgba(185,28,28,.4);border-radius:7px;background:#fff8f8;font-family:inherit;font-size:.76rem;color:var(--text-1);cursor:pointer;transition:border-color .15s;user-select:none}
.excl-trigger:hover{border-color:var(--red)}
.excl-trigger .excl-count{display:inline-flex;align-items:center;justify-content:center;background:var(--red);color:#fff;border-radius:20px;min-width:18px;height:18px;padding:0 5px;font-size:.6rem;font-weight:800;margin-left:6px}
.excl-trigger .excl-arrow{font-size:.65rem;color:var(--red);transition:transform .2s}
.excl-trigger.open .excl-arrow{transform:rotate(180deg)}
.excl-panel{display:none;position:fixed;background:#fff;border:1.5px solid rgba(185,28,28,.35);border-radius:9px;box-shadow:0 6px 20px rgba(0,0,0,.15);z-index:9999;max-height:280px;overflow-y:auto;padding:6px 0;min-width:260px}
.excl-panel.open{display:block}
.excl-grp-hd{padding:5px 12px 3px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:var(--red);opacity:.75;background:#fff8f8;border-top:1px solid rgba(185,28,28,.12);margin-top:2px}
.excl-grp-hd:first-child{border-top:none;margin-top:0}
.excl-item{display:flex;align-items:center;gap:8px;padding:5px 12px;cursor:pointer;transition:background .1s;font-size:.76rem;color:var(--text-1)}
.excl-item:hover{background:#fff0f0}
.excl-item input[type=checkbox]{accent-color:var(--red);width:14px;height:14px;cursor:pointer;flex-shrink:0}

.sector-summary-card {
  background:var(--surface); border:1px solid var(--border); border-radius:14px;
  box-shadow:var(--sh); margin-bottom:12px; overflow:hidden;
}
.sector-summary-bar {
  background:linear-gradient(135deg,#78520a,var(--gold));
  color:#fff; padding:10px 14px;
  display:flex; align-items:center; justify-content:space-between; gap:10px;
}
.sector-summary-bar-title { display:flex; align-items:center; gap:8px; font-weight:800; font-size:.8rem; color:#fff; letter-spacing:.4px; }
.sector-summary-body { padding:14px; }

.total-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:linear-gradient(135deg,var(--gold-light),var(--gold));
  color:#fff; border-radius:8px; padding:6px 12px;
  font-size:.8rem; font-weight:700; margin-bottom:10px;
  box-shadow:0 2px 6px rgba(184,134,11,.3);
}
.total-pill .total-num { font-size:1.1rem; font-weight:800; }

.sector-grid {
  display:grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap:8px;
}
.sec-card {
  border:1.5px solid var(--border); border-radius:8px; padding:10px 12px;
  background:var(--surface-2); cursor:pointer; transition:all .18s;
  position:relative; overflow:hidden;
}
.sec-card::before {
  content:''; position:absolute; top:0; left:0; right:0; height:3px;
  background:linear-gradient(90deg,var(--gold-light),var(--gold));
  border-radius:8px 8px 0 0; opacity:0; transition:opacity .18s;
}
.sec-card:hover { border-color:var(--gold); background:var(--gold-muted); transform:translateY(-1px); box-shadow:var(--sh2); }
.sec-card:hover::before { opacity:1; }
.sec-card.active-sector { border-color:var(--gold); background:var(--gold-muted); box-shadow:var(--sh2); }
.sec-card.active-sector::before { opacity:1; }
.sec-card-name { font-size:.78rem; font-weight:800; color:var(--text-1); margin-bottom:6px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sec-card-stats { display:flex; gap:5px; flex-wrap:wrap; }
.sec-stat { display:inline-flex; align-items:center; gap:3px; font-size:.65rem; font-weight:700; padding:2px 6px; border-radius:4px; }
.sec-stat-total { background:var(--gold-muted); color:var(--gold); }
.sec-stat-hof   { background:var(--green-bg); color:var(--green); }
.sec-stat-fm    { background:var(--border-light); color:var(--text-2); }

.subsec-row { margin-top:10px; display:flex; flex-wrap:wrap; gap:6px; }
.subsec-chip {
  display:inline-flex; align-items:center; gap:4px;
  background:var(--surface-2); color:var(--text-2); border:1.5px solid var(--border);
  border-radius:40px; padding:3px 10px; font-size:.68rem; font-weight:700;
  cursor:pointer; transition:all .15s;
}
.subsec-chip:hover { background:var(--gold-muted); border-color:var(--gold); color:var(--gold); }
.subsec-chip.active-subsector { background:var(--gold); color:#fff; border-color:var(--gold); }
.subsec-chip .subsec-count { background:rgba(255,255,255,.25); border-radius:20px; padding:0 5px; margin-left:2px; }
.subsec-chip:not(.active-subsector) .subsec-count { background:var(--border); color:var(--text-3); }

.dash-title {
  background:var(--gold-muted);
  border-left:4px solid var(--gold);
  border-radius:0 8px 8px 0;
  padding:9px 14px;
  margin-bottom:10px;
  display:none;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
.dash-title h5 { margin:0; color:var(--gold); font-weight:800; font-size:.84rem; }
.dash-title-clear {
  padding:4px 10px;
  border-radius:7px;
  border:1px solid rgba(184,134,11,.3);
  background:var(--surface);
  color:var(--gold);
  font-size:.72rem;
  font-weight:700;
  cursor:pointer;
  transition:background .15s;
}
.dash-title-clear:hover {
  background:var(--gold-muted);
}

.results-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; flex-wrap:wrap; gap:6px }
.results-count { font-size:.76rem; color:var(--text-2); font-weight:700; }
.results-count span { color:var(--gold); font-weight:800; }

.table-wrap {
  background:var(--surface);
  border:1px solid var(--border);
  border-radius:14px;
  box-shadow:var(--sh);
  overflow-x:auto;
  max-height:70vh;
  overflow-y:auto;
}
table.dir { width:100%; border-collapse:collapse; font-size:.78rem; min-width:980px; }
table.dir thead th {
  background:linear-gradient(to bottom,var(--surface-2),#f0ebe0); padding:9px 11px; font-size:.6rem; font-weight:800;
  text-transform:uppercase; letter-spacing:.7px; color:var(--text-3);
  border-bottom:2px solid var(--border); white-space:nowrap;
  position:sticky; top:0; z-index:1; user-select:none; text-align:left;
}
th.sortable { cursor:pointer; transition:color .14s; }
th.sortable:hover { color:var(--gold); }
th.sortable .si { margin-left:3px; opacity:.3; font-size:.57rem; }
th.sortable.asc .si::after { content:'▲'; opacity:1; color:var(--gold); }
th.sortable.desc .si::after { content:'▼'; opacity:1; color:var(--gold); }
th.sortable:not(.asc):not(.desc) .si::after { content:'⇅'; }

.mumineen-container:not(.umoor-view) table.dir tbody tr { border-bottom:1px solid var(--border-light); transition:background .1s; cursor: pointer; }
.mumineen-container.umoor-view table.dir tbody tr { border-bottom:1px solid var(--border-light); transition:background .1s; }
table.dir tbody tr:hover { background:#fdf9ef; }
table.dir td { padding:8px 11px; vertical-align:middle; color:var(--text-1); }
tr.hof-row td { background:#fffbeb; font-weight:700; border-top:1.5px solid rgba(184,134,11,.25); }
tr.hof-row td:first-child { border-left:3px solid var(--gold); }
tr.family-sep td { padding:0; height:4px; background:var(--surface-2); border:none; }
.empty-row td { text-align:center; padding:36px; color:var(--text-3); font-size:.84rem; }

.pill-hof { display:inline-block; background:var(--gold); color:#fff; font-size:.52rem; font-weight:800; padding:1px 6px; border-radius:20px; margin-left:5px; vertical-align:middle; }
.pill-fm  { display:inline-block; background:var(--surface-2); color:var(--text-2); border:1px solid var(--border); font-size:.52rem; font-weight:700; padding:1px 6px; border-radius:20px; margin-left:5px; vertical-align:middle; }
.bid { display:inline-block; background:#0c447c; color:#fff; font-size:.65rem; font-weight:700; padding:2px 7px; border-radius:5px; letter-spacing:.2px; }
.bact   { display:inline-block; background:var(--green-bg); color:var(--green); border:1px solid rgba(26,102,69,.2); font-size:.63rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.binact { display:inline-block; background:var(--red-bg); color:var(--red); border:1px solid rgba(185,28,28,.2); font-size:.63rem; font-weight:700; padding:2px 8px; border-radius:20px; }
.btemp     { display:inline-block; background:var(--amber-bg); color:var(--amber); border:1px solid rgba(180,83,9,.2); font-size:.63rem; font-weight:700; padding:2px 8px; border-radius:20px; }

.act-btn { display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; border-radius:6px; border:none; cursor:pointer; font-size:.72rem; text-decoration:none; transition:all .14s; }
.act-btn:hover { opacity:.8; text-decoration:none; }
.act-view { background:var(--blue-bg); color:var(--blue); }
.act-edit { background:var(--amber-bg); color:var(--amber); }

@media(max-width:992px){.frow-6,.frow-4,.frow-3{grid-template-columns:repeat(3,1fr)}}
@media(max-width:768px){.frow-6,.frow-4,.frow-3{grid-template-columns:repeat(2,1fr)}.sector-grid{grid-template-columns:repeat(auto-fill,minmax(140px,1fr));}}
@media(max-width:576px){.frow-6,.frow-4,.frow-3{grid-template-columns:1fr}.mumineen-container{padding:10px}.sector-grid{grid-template-columns:1fr 1fr;}}
</style>

<?php
  if (!class_exists('MemberStatusM')) {
    CI_Controller::get_instance()->load->model('MemberStatusM');
  }
  $is_umoor = isset($is_umoor) ? $is_umoor : (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] >= 4 && $_SESSION['user']['role'] <= 15);
  $view_base = 'admin/viewmember/';
  $back_fallback = $is_umoor ? 'umoor' : 'amilsaheb';
  if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2) {
    $view_base = 'amilsaheb/viewmember/';
    $back_fallback = 'amilsaheb';
  } else if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 16) {
    $view_base = 'MasoolMusaid/viewmember/';
    $back_fallback = 'MasoolMusaid';
  } else if ($is_umoor) {
    $view_base = 'Umoor/viewmember/';
    $back_fallback = 'umoor';
  }
  $can_edit = !$is_umoor && isset($_SESSION['user']['role']) && in_array($_SESSION['user']['role'], [1, 3]);
  $deeni_options = MemberStatusM::deeni_status_options();
  $health_options = MemberStatusM::health_status_options();
  $residential_options = MemberStatusM::residential_status_options();
?>

<div class="mumineen-container pt-5 <?= $is_umoor ? 'umoor-view' : '' ?>">

  <!-- Top bar -->
  <div class="md-topbar pt-2">
    <a href="<?php echo isset($back_url) ? $back_url : base_url($back_fallback); ?>" class="md-back">
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
        <button id="btnReset" class="fc-btn" type="button"><i class="fa fa-refresh"></i> Reset</button>
        <button id="btnToggle" class="fc-btn" type="button"><i class="fa fa-chevron-down"></i> Show Filters</button>
      </div>
    </div>

    <div id="filterBody" class="d-none">
      <form id="filtersForm" onsubmit="return false;" class="fc-body">

        <!-- Section 1: Search & Location -->
        <div class="fsec"><i class="fa fa-search" style="color:var(--blue);"></i> Search &amp; Location</div>
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
        <div class="fsec" id="secLabel2"><i class="fa fa-user" style="color:var(--purple);"></i> Member Details</div>
        <div class="frow frow-3" id="secRow2">
          <div>
            <label class="flabel">Active Inactive Status</label>
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
            <label class="flabel">HOF / FM</label>
            <select id="fHOFType" class="fselect">
              <option value="">All</option>
              <option value="HOF">HOF Only</option>
              <option value="FM">FM Only</option>
            </select>
          </div>
        </div>

        <!-- Exclude Status Filters -->
        <div class="fsec" id="secLabel4" style="margin-top:20px"><i class="fa fa-ban" style="color:var(--red)"></i> <span style="color:var(--red)">Exclude by Status</span></div>
        <div id="secRow4">
          <div class="excl-wrap">
            <div id="exclTrigger" class="excl-trigger" role="button" tabindex="0">
              <span id="exclTriggerText"><span style="color:var(--text-3);font-style:italic">Select statuses to exclude&hellip;</span></span>
              <span class="excl-arrow"><i class="fa fa-chevron-down"></i></span>
            </div>
            <div id="exclPanel" class="excl-panel">
              <div class="excl-grp-hd"><i class="fa fa-heartbeat"></i>&nbsp; Health Status</div>
              <?php foreach ($health_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <label class="excl-item"><input type="checkbox" class="excl-cb" value="health|<?php echo htmlspecialchars($k); ?>"> <?php echo htmlspecialchars($v); ?></label>
                <?php endif; ?>
              <?php endforeach; ?>
              <div class="excl-grp-hd"><i class="fa fa-star-o"></i>&nbsp; Deeni Status</div>
              <?php foreach ($deeni_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <label class="excl-item"><input type="checkbox" class="excl-cb" value="deeni|<?php echo htmlspecialchars($k); ?>"> <?php echo htmlspecialchars($v); ?></label>
                <?php endif; ?>
              <?php endforeach; ?>
              <div class="excl-grp-hd"><i class="fa fa-home"></i>&nbsp; Residential Status</div>
              <?php foreach ($residential_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <label class="excl-item"><input type="checkbox" class="excl-cb" value="resi|<?php echo htmlspecialchars($k); ?>"> <?php echo htmlspecialchars($v); ?></label>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Section 3: Status Filters -->
        <div class="fsec" id="secLabel3"><i class="fa fa-heartbeat" style="color:var(--red);"></i> Status Filters</div>
        <div class="frow frow-4" id="secRow3">
          <div>
            <label class="flabel">Health Status</label>
            <select id="fHealth" class="fselect">
              <option value="">All</option>
              <?php foreach ($health_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <option value="<?php echo htmlspecialchars($k); ?>"><?php echo htmlspecialchars($v); ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="flabel">Deeni Status</label>
            <select id="fDeeni" class="fselect">
              <option value="">All</option>
              <?php foreach ($deeni_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <option value="<?php echo htmlspecialchars($k); ?>"><?php echo htmlspecialchars($v); ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="flabel">Residential Status</label>
            <select id="fResidential" class="fselect">
              <option value="">All</option>
              <?php foreach ($residential_options as $k => $v): ?>
                <?php if ($k !== ''): ?>
                  <option value="<?php echo htmlspecialchars($k); ?>"><?php echo htmlspecialchars($v); ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="flabel">ITS-Sabeel Match</label>
            <select id="fItsMatch" class="fselect">
              <option value="">All</option>
              <option value="its_sabeel_both_khar"><?= htmlspecialchars(MemberStatusM::match_status_label('its_sabeel_both_khar')) ?></option>
              <option value="its_khar_sabeel_out"><?= htmlspecialchars(MemberStatusM::match_status_label('its_khar_sabeel_out')) ?></option>
              <option value="sabeel_khar_its_out"><?= htmlspecialchars(MemberStatusM::match_status_label('sabeel_khar_its_out')) ?></option>
              <option value="both_not_khar"><?= htmlspecialchars(MemberStatusM::match_status_label('both_not_khar')) ?></option>
            </select>
          </div>
        </div>
    </div>
  </div>

  <!-- ── Sector Summary Cards ──────────────────────────────────────── -->
  <div class="sector-summary-card">
    <div class="sector-summary-bar">
      <div class="sector-summary-bar-title">
        <i class="fa fa-map-marker"></i> SECTOR SUMMARY
        <span id="sectorToggleHint" style="font-size:.68rem;opacity:.7;font-weight:500;">Click a sector to filter</span>
      </div>
      <button id="btnSectorToggle" class="fc-btn" type="button"><i class="fa fa-chevron-down"></i> Show</button>
    </div>
    <div id="sectorSummaryBody" class="d-none">
      <div class="sector-summary-body">
        <div id="totalPill" class="total-pill">
          <i class="fa fa-users"></i>
          <span>Total Members:</span>
          <span class="total-num" id="totalCount">0</span>
        </div>
        <div class="sector-grid" id="sectorGrid"></div>
        <div class="subsec-row" id="subsecRow" style="display:none!important;"></div>
      </div>
    </div>
  </div>

  <!-- Chips -->
  <div id="chipRow"></div>

  <!-- Dashboard title -->
  <div id="dashTitle" style="display:none;" class="dash-title">
    <span class="md-banner-text" id="dashTitleText"></span>
    <button class="dash-title-clear" onclick="resetAll()">&#x2715; Clear Filter</button>
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
          <th class="sortable" data-col="Full_Name">Name <span class="si"></span></th>
          <th class="sortable" data-col="ITS_ID">ITS ID <span class="si"></span></th>
          <th class="sortable" data-col="Age">Age <span class="si"></span></th>
          <th class="sortable" data-col="Gender">Gender <span class="si"></span></th>
          <th class="sortable" data-col="Sector">Sector /</br> Sub Sector <span class="si"></span></th>
          <th>Mobile</th>
          <th class="sortable" data-col="_status">Status <span class="si"></span></th>
          <th class="sortable" data-col="its_sabeel_match">ITS Match <span class="si"></span></th>
          <th class="sortable" data-col="deeni_status">Deeni <span class="si"></span></th>
          <th class="sortable" data-col="health_status">Health <span class="si"></span></th>
          <th class="sortable" data-col="residential_status">Residential <span class="si"></span></th>
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
const IS_UMOOR = <?= $is_umoor ? 'true' : 'false' ?>;
const INACTIVE_DEENI_KEYS = <?= json_encode(MemberStatusM::get_inactive_trigger_keys('deeni')) ?>;
const INACTIVE_HEALTH_KEYS = <?= json_encode(MemberStatusM::get_inactive_trigger_keys('health')) ?>;
const INACTIVE_RESIDENTIAL_KEYS = <?= json_encode(MemberStatusM::get_inactive_trigger_keys('residential')) ?>;
const JAMAAT_PLACE = <?= json_encode(jamaat_place()) ?>;
const ITS_MATCH_LABELS = <?= json_encode([
  'its_sabeel_both_khar' => MemberStatusM::match_status_label('its_sabeel_both_khar'),
  'its_khar_sabeel_out'  => MemberStatusM::match_status_label('its_khar_sabeel_out'),
  'sabeel_khar_its_out'  => MemberStatusM::match_status_label('sabeel_khar_its_out'),
  'both_not_khar'        => MemberStatusM::match_status_label('both_not_khar'),
]) ?>;

const HEALTH_MAP = {};
document.querySelectorAll('#fHealth option').forEach(opt => { if (opt.value) HEALTH_MAP[opt.value] = opt.textContent.trim(); });

const DEENI_MAP = {};
document.querySelectorAll('#fDeeni option').forEach(opt => { if (opt.value) DEENI_MAP[opt.value] = opt.textContent.trim(); });

const RESIDENTIAL_MAP = {};
document.querySelectorAll('#fResidential option').forEach(opt => { if (opt.value) RESIDENTIAL_MAP[opt.value] = opt.textContent.trim(); });

let filtered  = [...ALL_DATA];
let sortCol   = 'Full_Name', sortDir = 'asc';

// Sector card click state (separate from the filter-form selects)
let activeSectorCard  = null; // sector name string or null
let activeSubSecCard  = null; // sub-sector name string or null

// ITS → name map for HOF resolution
const itsMap = {};
ALL_DATA.forEach(u => { const k = String(u.ITS_ID || u.ITS || ''); if (k) itsMap[k] = u.Full_Name || ''; });

// ── Boot ──────────────────────────────────────────────────────────────────────
fillSelects();
readURLAndApply();

// Mark Full_Name header as default sort indicator
document.querySelectorAll('th.sortable').forEach(th => {
  if (th.dataset.col === 'Full_Name') th.classList.add('asc');
  else th.classList.remove('asc', 'desc');
});

// ── Fill selects ──────────────────────────────────────────────────────────────
function fillSelects() {
  const sectors = new Set(), subs = new Set(), hofs = new Map(), marital = new Set();
  ALL_DATA.forEach(u => {
    if (u.Sector)     sectors.add(u.Sector);
    if (u.Sub_Sector) subs.add(u.Sub_Sector);
    const ms = (u.Marital_Status || '').trim();
    if (ms) marital.add(ms.charAt(0).toUpperCase() + ms.slice(1).toLowerCase());
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
  const maritalP  = p.get('marital_status') || p.get('marital') || p.get('ms') || '';

  const isDash = !!(
    (legFilter && legFilter !== 'all') || statusP || itsMatchP || madresaP || minP || maxP || maritalP
  );

  setv('fName',   p.get('name')   || '');
  setv('fSector', p.get('sector') || '');
  if (statusP)   setv('fStatus',   cap(statusP));
  if (itsMatchP) setv('fItsMatch', itsMatchP);
  if (minP) document.getElementById('fAgeMin').value = minP;
  if (maxP) document.getElementById('fAgeMax').value = maxP;
  if (maritalP) setv('fMarital', cap(maritalP));

  const form = document.getElementById('filtersForm');
  if (legFilter === 'gender')      form.dataset.gender    = legValue;
  if (legFilter === 'hof_fm_type') form.dataset.hofFmType = legValue;
  if (madresaP)                    form.dataset.madresa   = madresaP;

  if (legFilter === 'health_status')      setv('fHealth',      legValue);
  if (legFilter === 'deeni_status')       setv('fDeeni',       legValue);
  if (legFilter === 'residential_status') {
    setv('fResidential', legValue);
    if (legValue === 'Madresa in ' + JAMAAT_PLACE) {
      sortCol = 'Age';
      sortDir = 'asc';
      setTimeout(() => {
        document.querySelectorAll('th.sortable').forEach(th => {
          if (th.dataset.col === 'Age') {
            th.classList.add('asc');
          } else {
            th.classList.remove('asc', 'desc');
          }
        });
      }, 50);
    }
  }
  if (legFilter === 'sector')             setv('fSector',      legValue);

  if (!['','all','age_range','sector','gender','hof_fm_type','health_status','deeni_status','residential_status','its_sabeel_match'].includes(legFilter) && legValue) {
    form.dataset.legacyField = legFilter;
    form.dataset.legacyValue = legValue;
  }

  if (isDash) {
    document.getElementById('secLabel2').style.display = 'none';
    document.getElementById('secRow2').style.display   = 'none';
    document.getElementById('secLabel3').style.display = 'none';
    document.getElementById('secRow3').style.display   = 'none';
    document.getElementById('maritalCol').style.display    = 'none';
    document.getElementById('dashInlineSlot').style.display = '';

    const dashMap = {
      'status':'fStatus', 'activity_status':'fStatus',
      'health_status':'fHealth', 'deeni_status':'fDeeni',
      'residential_status':'fResidential', 'gender':'fGender',
      'hof_fm_type':'fHOFType', 'its_sabeel_match':'fItsMatch',
      'marital_status':'fMarital', 'marital':'fMarital', 'ms':'fMarital'
    };
    const injectId = statusP ? 'fStatus' : itsMatchP ? 'fItsMatch' : maritalP ? 'fMarital' : (dashMap[legFilter] || null);

    if (injectId) {
      const orig = document.getElementById(injectId);
      if (orig) {
        const parentDiv = orig.closest('div');
        if (parentDiv) {
          const clone    = parentDiv.cloneNode(true);
          const cloneSel = clone.querySelector('select');
          if (cloneSel) {
            orig.id      = injectId + '_hidden';
            cloneSel.id  = injectId;
            const val    = statusP ? cap(statusP) : itsMatchP ? itsMatchP : maritalP ? cap(maritalP) : legValue;
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
  const maritalP = p.get('marital_status') || p.get('marital') || p.get('ms') || '';
  const map = Object.assign({'active':'Active Members','inactive':'Inactive Members'}, ITS_MATCH_LABELS);
  const md = p.get('madresa_deprived');
  let t = (mn === '5' && mx === '15' ? (md === '1' ? 'Deeni Taalim Not Taking (Age 5-15)' : (md === '0' ? 'Deeni Taalim Taking (Age 5-15)' : 'Deeni Taalim Eligible (Age 5-15)')) : '')
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

  if (!t && maritalP) {
    if (maritalP.toLowerCase() === 'single' && mn === '21' && mx === '40') {
      t = 'Single (21-40) Members';
    } else {
      t = cap(maritalP) + ' Members';
    }
  }

  if (t) {
    document.getElementById('dashTitleText').textContent = t;
    document.getElementById('dashTitle').style.display = '';
  }
}

// ── Core filter ───────────────────────────────────────────────────────────────
function run() {
  const name    = (document.getElementById('fName').value || '').toLowerCase().trim();
  const sector  = activeSectorCard || document.getElementById('fSector').value;
  const sub     = activeSubSecCard  || document.getElementById('fSubSector').value;
  const marital = document.getElementById('fMarital').value;
  const hof     = document.getElementById('fHOF').value;
  const ageMin  = document.getElementById('fAgeMin').value !== '' ? parseInt(document.getElementById('fAgeMin').value) : null;
  const ageMax  = document.getElementById('fAgeMax').value !== '' ? parseInt(document.getElementById('fAgeMax').value) : null;

  const status   = gv('fStatus');
  const gender   = gv('fGender').toLowerCase();
  const hofType  = gv('fHOFType').toUpperCase();
  const health   = gv('fHealth');
  const deeni    = gv('fDeeni');
  const resi     = gv('fResidential');
  const itsMatch = gv('fItsMatch');
  // Exclude checkboxes
  const exclAll  = Array.from(document.querySelectorAll('.excl-cb:checked')).map(cb=>cb.value);
  const exclHealth = exclAll.filter(v=>v.startsWith('health|')).map(v=>v.slice(7));
  const exclDeeni  = exclAll.filter(v=>v.startsWith('deeni|')).map(v=>v.slice(6));
  const exclResi   = exclAll.filter(v=>v.startsWith('resi|')).map(v=>v.slice(5));

  const form       = document.getElementById('filtersForm');
  const dsGender   = (form.dataset.gender    || '').toLowerCase();
  const dsHofType  = (form.dataset.hofFmType || '').toUpperCase();
  const dsMadresa  = form.dataset.madresa    || '';
  const dsLegField = (form.dataset.legacyField || '').toLowerCase();
  const dsLegValue =  form.dataset.legacyValue || '';

  filtered = ALL_DATA.filter(u => {
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

    if (status) {
      const inact = (u.Inactive_Status || u.inactive_status || '').trim();
      const act   = (u.activity_status || '').toLowerCase();
      const isAct = !inact && (!act || act === 'active');
      if (status === 'Active'   && !isAct) return false;
      if (status === 'Inactive' &&  isAct) return false;
    }

    const wg = gender || dsGender;
    if (wg && (u.Gender || '').toLowerCase() !== wg) return false;

    const wh = hofType || dsHofType;
    if (wh && (u.HOF_FM_TYPE || '').toUpperCase() !== wh) return false;

    if (health && (u.health_status      || '').trim() !== health)  return false;
    if (deeni  && (u.deeni_status       || '').trim() !== deeni)   return false;
    if (resi   && (u.residential_status || '').trim() !== resi)    return false;
    if (itsMatch && (u.its_sabeel_match || '')         !== itsMatch) return false;
    // Exclude filters
    if (exclHealth.length && exclHealth.includes((u.health_status      || '').trim())) return false;
    if (exclDeeni.length  && exclDeeni.includes((u.deeni_status        || '').trim())) return false;
    if (exclResi.length   && exclResi.includes((u.residential_status   || '').trim())) return false;
    if (dsMadresa && String(u.madresa_deprived ?? '') !== dsMadresa) return false;

    if (dsLegField && dsLegValue) {
      const k = Object.keys(u).find(x => x.toLowerCase() === dsLegField);
      if (!k || (u[k] || '').toString().trim().toLowerCase() !== dsLegValue.toLowerCase()) return false;
    }

    return true;
  });

  if (sortCol) applySortToFiltered();

  renderTable();
  renderChips();
  renderSectorCards();

  const n = filtered.length;
  document.getElementById('countBadge').textContent  = n + ' result' + (n !== 1 ? 's' : '');
  document.getElementById('resultsCount').innerHTML = '<span>' + n + '</span> member' + (n !== 1 ? 's' : '') + ' found';
}

// ── Sector Summary Cards ──────────────────────────────────────────────────────
function renderSectorCards() {
  // Build stats from `filtered` (but ignore activeSectorCard/activeSubSecCard
  // so the "full" sector breakdown is always visible even when one is clicked)
  const baseData = buildBaseFiltered();

  const sectorStats = {};
  baseData.forEach(u => {
    const s = u.Sector || 'Unknown';
    if (!sectorStats[s]) sectorStats[s] = { total:0, hof:0, fm:0, subs:{} };
    sectorStats[s].total++;
    if ((u.HOF_FM_TYPE||'').toUpperCase() === 'HOF') sectorStats[s].hof++;
    else sectorStats[s].fm++;
    const ss = u.Sub_Sector || '';
    if (ss) {
      if (!sectorStats[s].subs[ss]) sectorStats[s].subs[ss] = 0;
      sectorStats[s].subs[ss]++;
    }
  });

  document.getElementById('totalCount').textContent = baseData.length;

  const grid = document.getElementById('sectorGrid');
  grid.innerHTML = '';

  Object.entries(sectorStats)
    .sort((a,b) => a[0].localeCompare(b[0]))
    .forEach(([sector, stats]) => {
      const div = document.createElement('div');
      div.className = 'sec-card' + (activeSectorCard === sector ? ' active-sector' : '');
      div.innerHTML =
        `<div class="sec-card-name" title="${esc(sector)}">${esc(sector)}</div>` +
        `<div class="sec-card-stats">` +
          `<span class="sec-stat sec-stat-total"><i class="fa fa-users" style="font-size:.6rem;"></i> ${stats.total}</span>` +
          `<span class="sec-stat sec-stat-hof">HOF ${stats.hof}</span>` +
          `<span class="sec-stat sec-stat-fm">FM ${stats.fm}</span>` +
        `</div>`;
      div.addEventListener('click', () => toggleSectorCard(sector));
      grid.appendChild(div);
    });

  // Sub-sector chips (only when a sector is active)
  renderSubSecChips(sectorStats);
}

function renderSubSecChips(sectorStats) {
  const row = document.getElementById('subsecRow');
  row.innerHTML = '';

  if (!activeSectorCard || !sectorStats[activeSectorCard]) {
    row.style.setProperty('display', 'none', 'important');
    return;
  }

  const subs = sectorStats[activeSectorCard].subs;
  if (!Object.keys(subs).length) {
    row.style.setProperty('display', 'none', 'important');
    return;
  }

  row.style.removeProperty('display');

  // "All sub-sectors" chip
  const allChip = document.createElement('span');
  allChip.className = 'subsec-chip' + (!activeSubSecCard ? ' active-subsector' : '');
  allChip.innerHTML = `All <span class="subsec-count">${sectorStats[activeSectorCard].total}</span>`;
  allChip.addEventListener('click', () => { activeSubSecCard = null; run(); });
  row.appendChild(allChip);

  Object.entries(subs).sort((a,b) => a[0].localeCompare(b[0])).forEach(([ss, cnt]) => {
    const chip = document.createElement('span');
    chip.className = 'subsec-chip' + (activeSubSecCard === ss ? ' active-subsector' : '');
    chip.innerHTML = `${esc(ss)} <span class="subsec-count">${cnt}</span>`;
    chip.addEventListener('click', () => { activeSubSecCard = ss; run(); });
    row.appendChild(chip);
  });
}

// Build filtered data WITHOUT the sector-card override (used for sector card counts)
function buildBaseFiltered() {
  const name    = (document.getElementById('fName').value || '').toLowerCase().trim();
  const formSec = document.getElementById('fSector').value;
  const formSub = document.getElementById('fSubSector').value;
  const marital = document.getElementById('fMarital').value;
  const hof     = document.getElementById('fHOF').value;
  const ageMin  = document.getElementById('fAgeMin').value !== '' ? parseInt(document.getElementById('fAgeMin').value) : null;
  const ageMax  = document.getElementById('fAgeMax').value !== '' ? parseInt(document.getElementById('fAgeMax').value) : null;
  const status   = gv('fStatus');
  const gender   = gv('fGender').toLowerCase();
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

  return ALL_DATA.filter(u => {
    if (name) { const nm=(u.Full_Name||'').toLowerCase(),its=String(u.ITS_ID||u.ITS||'').toLowerCase(),mob=(u.Mobile||'').toLowerCase(); if(!nm.includes(name)&&!its.includes(name)&&!mob.includes(name)) return false; }
    if (formSec && u.Sector     !== formSec) return false;
    if (formSub && u.Sub_Sector !== formSub) return false;
    if (marital && (u.Marital_Status||'').trim().toLowerCase() !== marital.toLowerCase()) return false;
    if (hof     && String(u.HOF_ID||u.HOF||'') !== hof) return false;
    if (ageMin !== null && (parseInt(u.Age)||0) < ageMin) return false;
    if (ageMax !== null && (parseInt(u.Age)||0) > ageMax) return false;
    if (status) { const inact=(u.Inactive_Status||u.inactive_status||'').trim(),act=(u.activity_status||'').toLowerCase(),isAct=!inact&&(!act||act==='active'); if(status==='Active'&&!isAct) return false; if(status==='Inactive'&&isAct) return false; }
    const wg=gender||dsGender; if(wg&&(u.Gender||'').toLowerCase()!==wg) return false;
    const wh=hofType||dsHofType; if(wh&&(u.HOF_FM_TYPE||'').toUpperCase()!==wh) return false;
    if(health&&(u.health_status||'').trim()!==health) return false;
    if(deeni&&(u.deeni_status||'').trim()!==deeni) return false;
    if(resi&&(u.residential_status||'').trim()!==resi) return false;
    if(itsMatch&&(u.its_sabeel_match||'')!==itsMatch) return false;
    if(dsMadresa&&String(u.madresa_deprived??'')!==dsMadresa) return false;
    if(dsLegField&&dsLegValue){const k=Object.keys(u).find(x=>x.toLowerCase()===dsLegField);if(!k||(u[k]||'').toString().trim().toLowerCase()!==dsLegValue.toLowerCase()) return false;}
    return true;
  });
}

function toggleSectorCard(sector) {
  if (activeSectorCard === sector) {
    activeSectorCard = null;
    activeSubSecCard  = null;
  } else {
    activeSectorCard = sector;
    activeSubSecCard  = null;
  }
  run();
}

// ── Sort helper ───────────────────────────────────────────────────────────────
function applySortToFiltered() {
  filtered.sort((a, b) => {
    let va, vb;
    if (sortCol === '_status') {
      const st = u => { const inact=(u.Inactive_Status||u.inactive_status||'').trim(),act=(u.activity_status||'').toLowerCase(); return (!inact&&(!act||act==='active'))?'Active':'Inactive'; };
      va = st(a); vb = st(b);
    } else if (sortCol === 'Age') {
      return sortDir === 'asc' ? (parseInt(a.Age)||0)-(parseInt(b.Age)||0) : (parseInt(b.Age)||0)-(parseInt(a.Age)||0);
    } else {
      va = (a[sortCol]||'').toString().toLowerCase();
      vb = (b[sortCol]||'').toString().toLowerCase();
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
    tbody.innerHTML = `<tr class="empty-row"><td colspan="13"><i class="fa fa-search"></i> No members found.</td></tr>`;
    return;
  }

  const redirectParam = encodeURIComponent(window.location.pathname + window.location.search);

  function rowHTML(u, rowNum) {
    const isHOF = (u.HOF_FM_TYPE || '').toUpperCase() === 'HOF';
    const act   = (u.activity_status || '').toLowerCase();
    const inact = (u.Inactive_Status || u.inactive_status || '').trim();
    const isAct = !inact && (!act || act === 'active');
    const badge = isAct ? '<span class="bact">Active</span>'
      : act === 'temporary' ? '<span class="btemp">Temp</span>'
      : '<span class="binact">Inactive</span>';

    const isHealthRed = INACTIVE_HEALTH_KEYS.includes((u.health_status||'').trim());
    const isDeeniRed = INACTIVE_DEENI_KEYS.includes((u.deeni_status||'').trim());
    const isResRed = INACTIVE_RESIDENTIAL_KEYS.includes((u.residential_status||'').trim());
    
    const healthStyle = isHealthRed ? 'color: var(--red); font-weight: bold;' : '';
    const deeniStyle = isDeeniRed ? 'color: var(--red); font-weight: bold;' : '';
    const resStyle = isResRed ? 'color: var(--red); font-weight: bold;' : '';

    let html = `<td style="color:var(--text-3);font-size:.72rem;font-weight:600">${rowNum}</td>` +
      `<td><span style="font-weight:${isHOF?800:500}">${esc(u.Full_Name||'')}${isHOF?'<span class="pill-hof">HOF</span>':'<span class="pill-fm">FM</span>'}</span></td>` +
      `<td><span class="bid">${esc(String(u.ITS_ID||u.ITS||''))}</span></td>` +
      `<td>${esc(u.Age||'—')}</td>` +
      `<td style="text-transform:capitalize;font-size:.75rem">${esc(u.Gender||'—')}</td>` +
      `<td><div style="font-size:.76rem;font-weight:600">${esc(u.Sector||'—')}</div><div style="font-size:.68rem;color:var(--text-3)">${esc(u.Sub_Sector||'')}</div></td>` +
      `<td style="font-size:.75rem;color:var(--text-2)">${esc(u.Mobile||'—')}</td>` +
      `<td>${badge}</td>` +
      `<td style="font-size:.72rem;color:var(--text-2)">${esc(ITS_MATCH_LABELS[u.its_sabeel_match] || u.its_sabeel_match || '—')}</td>` +
      `<td style="font-size:.72rem;color:var(--text-2);${deeniStyle}">${esc(DEENI_MAP[(u.deeni_status||'').trim()] || u.deeni_status || '—')}</td>` +
      `<td style="font-size:.72rem;color:var(--text-2);${healthStyle}">${esc(HEALTH_MAP[(u.health_status||'').trim()] || u.health_status || '—')}</td>` +
      `<td style="font-size:.72rem;color:var(--text-2);${resStyle}">${esc(RESIDENTIAL_MAP[(u.residential_status||'').trim()] || u.residential_status || '—')}</td>`;

    html += `<td>` +
      `<a href="${VIEW_URL}${u.ITS_ID}" class="act-btn act-view" title="View"><i class="fa fa-eye"></i></a>` +
      (CAN_EDIT ? `<a href="${EDIT_URL}${u.ITS_ID}?redirect=${redirectParam}" class="act-btn act-edit" style="margin-left:4px" title="Edit"><i class="fa fa-pencil"></i></a>` : '') +
    `</td>`;
    return html;
  }

  const groups = {}, order = [];
  filtered.forEach(u => {
    const hid = (u.HOF_ID || u.HOF || u.ITS_ID || '').toString();
    if (!groups[hid]) {
      groups[hid] = {
        hid,
        hname: itsMap[hid] || u.HOF_Name || hid,
        members: [],
        hofUser: null
      };
      order.push(hid);
    }
    if ((u.HOF_FM_TYPE || '').toUpperCase() === 'HOF') {
      groups[hid].hofUser = u;
    }
    groups[hid].members.push(u);
  });

  // Sort members within each group: HOF first, then FMs sorted by Age ascending
  Object.keys(groups).forEach(hid => {
    groups[hid].members.sort((a, b) => {
      const aIsH = (a.HOF_FM_TYPE || '').toUpperCase() === 'HOF';
      const bIsH = (b.HOF_FM_TYPE || '').toUpperCase() === 'HOF';
      if (aIsH && !bIsH) return -1;
      if (!aIsH && bIsH) return 1;
      const ageA = parseInt(a.Age) || 0;
      const ageB = parseInt(b.Age) || 0;
      return ageA - ageB;
    });
  });

  const seen = new Set();
  let sg = order.filter(k => { if (seen.has(k)) return false; seen.add(k); return true; }).map(k => groups[k]);

  if (sortCol) {
    const getSortVal = (grp) => {
      const refUser = grp.hofUser || grp.members[0];
      if (!refUser) return '';
      if (sortCol === '_status') {
        const inact = (refUser.Inactive_Status || refUser.inactive_status || '').trim();
        const act   = (refUser.activity_status || '').toLowerCase();
        return (!inact && (!act || act === 'active')) ? 'Active' : 'Inactive';
      }
      if (sortCol === 'Age') {
        return parseInt(refUser.Age) || 0;
      }
      return (refUser[sortCol] || '').toString().toLowerCase();
    };
    sg.sort((a, b) => {
      // Families with HOF come before families without HOF
      const aHas = !!a.hofUser, bHas = !!b.hofUser;
      if (aHas && !bHas) return -1;
      if (!aHas && bHas) return 1;
      const va = getSortVal(a);
      const vb = getSortVal(b);
      if (sortCol === 'Age') {
        return sortDir === 'asc' ? va - vb : vb - va;
      }
      if (typeof va === 'string' && typeof vb === 'string') {
        return sortDir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
      }
      return 0;
    });
  } else {
    sg.sort((a, b) => {
      // Families with HOF come before orphan FM-only groups
      const aHas = !!a.hofUser, bHas = !!b.hofUser;
      if (aHas && !bHas) return -1;
      if (!aHas && bHas) return 1;
      const ageA = parseInt((a.hofUser && a.hofUser.Age) || (a.members[0] && a.members[0].Age)) || 0;
      const ageB = parseInt((b.hofUser && b.hofUser.Age) || (b.members[0] && b.members[0].Age)) || 0;
      return ageA - ageB;
    });
  }

  let idx = 1;
  sg.forEach((grp, gi) => {
    if (gi > 0) {
      const sep = tbody.insertRow();
      sep.className = 'family-sep';
      sep.innerHTML = `<td colspan="13"></td>`;
    }
    grp.members.forEach(u => {
      const tr = tbody.insertRow();
      tr.dataset.its = u.ITS_ID;
      if ((u.HOF_FM_TYPE || '').toUpperCase() === 'HOF') tr.className = 'hof-row';
      tr.innerHTML = rowHTML(u, idx++);
    });
  });
}

// ── Chips ─────────────────────────────────────────────────────────────────────
const EXCL_LABELS = {'health':'Health','deeni':'Deeni','resi':'Residential'};
function getExclChecked(){return Array.from(document.querySelectorAll('.excl-cb:checked'));}
function updateExclTrigger(){
  const checked=getExclChecked();
  const trigger=document.getElementById('exclTrigger');
  const textEl=document.getElementById('exclTriggerText');
  if(!trigger||!textEl)return;
  if(!checked.length){
    textEl.innerHTML='<span style="color:var(--text-3);font-style:italic">Select statuses to exclude&hellip;</span>';
    trigger.querySelectorAll('.excl-count').forEach(el=>el.remove());
  } else {
    textEl.innerHTML=`<span style="color:var(--red);font-weight:700">${checked.length} status${checked.length>1?'es':''} excluded</span>`;
    let badge=trigger.querySelector('.excl-count');
    if(!badge){badge=document.createElement('span');badge.className='excl-count';trigger.insertBefore(badge,trigger.querySelector('.excl-arrow'));}
    badge.textContent=checked.length;
  }
}
function renderChips() {
  const defs = [
    ['fName','Name'],['fSector','Sector'],['fSubSector','Sub Sector'],
    ['fMarital','Marital'],['fAgeMin','Age ≥'],['fAgeMax','Age ≤'],['fHOF','HOF'],
    ['fStatus','Status'],['fGender','Gender'],
    ['fHOFType','HOF/FM'],['fHealth','Health'],['fDeeni','Deeni'],
    ['fResidential','Residential'],['fItsMatch','ITS Match'],
  ];
  const row = document.getElementById('chipRow');
  row.innerHTML = '';
  let any = false;

  // Sector card chip
  if (activeSectorCard) {
    any = true;
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.innerHTML = `<b>Sector:</b>&nbsp;${esc(activeSectorCard)} <span class="chip-x" data-type="sector">&times;</span>`;
    row.appendChild(chip);
  }
  if (activeSubSecCard) {
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.innerHTML = `<b>Sub Sector:</b>&nbsp;${esc(activeSubSecCard)} <span class="chip-x" data-type="subsector">&times;</span>`;
    row.appendChild(chip);
  }

  defs.forEach(([id, label]) => {
    const el = document.getElementById(id);
    if (!el || !el.value) return;
    any = true;
    const display = el.tagName === 'SELECT' ? (el.options[el.selectedIndex]?.text || el.value) : el.value;
    const chip = document.createElement('span');
    chip.className = 'chip';
    chip.innerHTML = `<b>${esc(label)}:</b>&nbsp;${esc(display)} <span class="chip-x" data-id="${id}">&times;</span>`;
    row.appendChild(chip);
  });

  // Exclude chips
  getExclChecked().forEach(cb=>{
    const [type]=cb.value.split('|');
    const label=EXCL_LABELS[type]||type;
    const txt=cb.closest('label')?.textContent.trim()||cb.value;
    any=true;
    const chip=document.createElement('span');chip.className='chip-excl';
    chip.innerHTML=`<i class="fa fa-ban" style="font-size:.6rem"></i>&nbsp;<b>Excl.${esc(label)}:</b>&nbsp;${esc(txt)}&nbsp;<span class="chip-x" data-excl-val="${esc(cb.value)}" style="color:var(--red)">&times;</span>`;
    row.appendChild(chip);
  });

  if (any || activeSectorCard) {
    const cl = document.createElement('span');
    cl.className = 'chip-clear';
    cl.innerHTML = '&times; Clear all';
    cl.onclick = resetAll;
    row.appendChild(cl);
  }

  row.querySelectorAll('.chip-x').forEach(x => {
    x.addEventListener('click', () => {
      if (x.dataset.exclVal) {
        const cb=document.querySelector(`.excl-cb[value="${x.dataset.exclVal}"]`);
        if(cb){cb.checked=false;updateExclTrigger();run();} return;
      }
      if (x.dataset.type === 'sector')    { activeSectorCard = null; activeSubSecCard = null; run(); return; }
      if (x.dataset.type === 'subsector') { activeSubSecCard = null; run(); return; }
      const el = document.getElementById(x.dataset.id);
      if (el) { el.value = ''; run(); }
    });
  });
}

// ── Reset ─────────────────────────────────────────────────────────────────────
function resetAll() {
  document.getElementById('filtersForm').reset();
  document.querySelectorAll('.excl-cb').forEach(cb=>cb.checked=false);
  updateExclTrigger();
  const form = document.getElementById('filtersForm');
  ['gender','hofFmType','madresa','legacyField','legacyValue'].forEach(k => delete form.dataset[k]);

  document.querySelectorAll('[id$="_hidden"]').forEach(el => { el.id = el.id.replace('_hidden', ''); });

  ['secLabel2','secRow2','secLabel3','secRow3','secLabel4','secRow4','maritalCol'].forEach(id => {
    const e=document.getElementById(id);if(e)e.style.display = '';
  });
  const slot = document.getElementById('dashInlineSlot');
  slot.style.display = 'none';
  slot.innerHTML = '';

  document.getElementById('dashTitle').style.display = 'none';
  document.getElementById('chipRow').innerHTML = '';

  sortCol = 'Full_Name'; sortDir = 'asc';
  document.querySelectorAll('th.sortable').forEach(th => {
    if (th.dataset.col === 'Full_Name') th.classList.add('asc');
    else th.classList.remove('asc', 'desc');
  });

  activeSectorCard = null;
  activeSubSecCard  = null;

  history.replaceState(null, '', window.location.pathname);
  filtered = [...ALL_DATA];
  renderTable();
  renderSectorCards();
  const n = filtered.length;
  document.getElementById('resultsCount').innerHTML = '<span>' + n + '</span> members';
  document.getElementById('countBadge').textContent   = '';
}

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
  if (!filtered.length) { alert('No data to export.'); return; }
  const preferred = ['ITS_ID','Full_Name','Age','Gender','Sector','Sub_Sector','Mobile','Email',
    'Marital_Status','HOF_FM_TYPE','activity_status','health_status',
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
['fSector','fSubSector','fMarital','fHOF','fStatus','fGender',
 'fHOFType','fHealth','fDeeni','fResidential','fItsMatch'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('change', run);
});
['fName','fAgeMin','fAgeMax'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('input', run);
});
// Exclude checkboxes
document.querySelectorAll('.excl-cb').forEach(cb=>cb.addEventListener('change',()=>{updateExclTrigger();run();}));
// Exclude dropdown toggle
(()=>{
  const trigger=document.getElementById('exclTrigger');
  const panel=document.getElementById('exclPanel');
  if(!trigger||!panel)return;
  document.body.appendChild(panel);
  function positionPanel(){
    const r=trigger.getBoundingClientRect();
    panel.style.top=(r.bottom+4)+'px';
    panel.style.left=r.left+'px';
    panel.style.width=r.width+'px';
  }
  function openPanel(){positionPanel();panel.classList.add('open');trigger.classList.add('open');}
  function closePanel(){panel.classList.remove('open');trigger.classList.remove('open');}
  trigger.addEventListener('click',e=>{e.stopPropagation();panel.classList.contains('open')?closePanel():openPanel();});
  trigger.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();trigger.click();}});
  document.addEventListener('click',e=>{if(!trigger.contains(e.target)&&!panel.contains(e.target))closePanel();});
  window.addEventListener('scroll',()=>{if(panel.classList.contains('open'))positionPanel();},{passive:true});
  window.addEventListener('resize',()=>{if(panel.classList.contains('open'))positionPanel();},{passive:true});
})();

document.getElementById('btnReset').addEventListener('click', resetAll);

document.getElementById('btnToggle').addEventListener('click', function() {
  const body     = document.getElementById('filterBody');
  const willHide = !body.classList.contains('d-none');
  body.classList.toggle('d-none');
  this.innerHTML = willHide
    ? '<i class="fa fa-chevron-down"></i> Show'
    : '<i class="fa fa-chevron-up"></i> Hide';
});

// Sector summary toggle
document.getElementById('btnSectorToggle').addEventListener('click', function() {
  const body     = document.getElementById('sectorSummaryBody');
  const willHide = !body.classList.contains('d-none');
  body.classList.toggle('d-none');
  this.innerHTML = willHide
    ? '<i class="fa fa-chevron-down"></i> Show'
    : '<i class="fa fa-chevron-up"></i> Hide';
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

// Clickable rows for Mumineen directory table
document.querySelector('table.dir tbody').addEventListener('click', e => {
  const tr = e.target.closest('tr');
  if (!tr || tr.classList.contains('family-sep') || tr.classList.contains('empty-row')) return;
  if (e.target.closest('button, a, input, select, option, label') || e.target.classList.contains('act-btn')) {
    return;
  }
  const its = tr.dataset.its;
  if (its) {
    window.location.href = VIEW_URL + its;
  }
});
</script>