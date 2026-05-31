<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#b8860b;--gold-muted:#f5e9c0;
  --bg:#faf7f0;--surface:#fff;--surface-2:#f7f4ec;
  --border:#e8e0cc;--border-light:#f0ece0;
  --text-1:#1a1610;--text-2:#5a5244;--text-3:#9c8f7a;
  --green:#1a6645;--green-bg:#eaf4ee;
  --red:#b91c1c;--red-bg:#fef2f2;
  --blue:#1d4ed8;--blue-bg:#eff6ff;
  --amber:#b45309;--amber-bg:#fffbeb;
  --sh:0 1px 4px rgba(0,0,0,.07);
  --sh2:0 4px 18px rgba(0,0,0,.10);
  --sh3:0 8px 32px rgba(0,0,0,.14);
}
#rzApp{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);padding:16px;min-height:100vh}
#rzApp *{box-sizing:border-box}

/* ── Header ── */
#rzApp .rz-hdr{
    position:relative;
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:14px;
    flex-wrap:wrap;
    margin-top:80px;
}

#rzApp .rz-back{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 13px;
    border-radius:8px;
    border:1.5px solid var(--border);
    background:var(--surface);
    color:var(--text-2);
    font-size:.75rem;
    font-weight:700;
    text-decoration:none;
    transition:all .15s;
    z-index:2;
}

#rzApp .rz-back:hover{
    background:var(--gold-muted);
    border-color:var(--gold);
    color:var(--gold);
    text-decoration:none;
}

#rzApp .rz-breadcrumb{
    display:flex;
    align-items:center;
    gap:5px;
    font-size:.72rem;
    color:var(--text-3);
    flex:1;
}

#rzApp .rz-breadcrumb a{
    color:var(--text-3);
    text-decoration:none;
    font-weight:600;
}

#rzApp .rz-breadcrumb a:hover{
    color:var(--gold);
}

#rzApp .rz-breadcrumb .sep{
    color:var(--border);
    font-size:.8rem;
}

#rzApp .rz-breadcrumb .cur{
    color:var(--gold);
    font-weight:700;
}

#rzApp .rz-pagetitle{
    position:absolute;
    left:50%;
    transform:translateX(-50%);
    font-size:.95rem;
    font-weight:800;
    color:var(--gold);
    text-transform:uppercase;
    letter-spacing:.5px;
    text-align:center;
    white-space:nowrap;
    pointer-events:none;
}

/* ── Toolbar ── */
#rzApp .rz-bar{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:9px 12px;margin-bottom:12px;box-shadow:var(--sh)}
#rzApp .rz-bar-row{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
#rzApp .rz-sel{height:30px;padding:0 8px;background:var(--surface-2);border:1.5px solid var(--border);border-radius:7px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.73rem;font-weight:600;color:var(--text-2);outline:none;cursor:pointer;transition:border-color .15s}
#rzApp .rz-sel:hover,#rzApp .rz-sel:focus{border-color:var(--gold)}
#rzApp .rz-srchwrap{position:relative;flex:1;min-width:140px}
#rzApp .rz-srchwrap i{position:absolute;left:8px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:.72rem;pointer-events:none}
#rzApp .rz-srchwrap input{width:100%;height:30px;padding:0 30px 0 26px;border:1.5px solid var(--border);border-radius:7px;background:var(--surface-2);font-family:'Plus Jakarta Sans',sans-serif;font-size:.73rem;color:var(--text-1);outline:none;transition:border-color .15s}
#rzApp .rz-srchwrap input:focus{border-color:var(--gold);background:var(--surface)}
#rzApp .rz-srchwrap .clr{position:absolute;right:7px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:.7rem;cursor:pointer;display:none;background:none;border:none;padding:0;line-height:1}
#rzApp .rz-srchwrap input:not(:placeholder-shown)~.clr{display:block}
#rzApp .rz-refreshbtn{display:inline-flex;align-items:center;gap:5px;height:30px;padding:0 12px;border-radius:7px;background:var(--gold-muted);border:1px solid rgba(184,134,11,.3);color:var(--gold);font-size:.73rem;font-weight:700;cursor:pointer;transition:background .15s;white-space:nowrap}
#rzApp .rz-refreshbtn:hover{background:#edd98a}
#rzApp .rz-bar-sep{width:1px;height:20px;background:var(--border);flex-shrink:0}

/* ── Active filter badge ── */
#rzApp .rz-active-filters{display:flex;gap:5px;flex-wrap:wrap;margin-top:7px;display:none}
#rzApp .rz-active-filters.show{display:flex}
#rzApp .rz-fchip{display:inline-flex;align-items:center;gap:4px;background:var(--gold-muted);color:var(--gold);border:1px solid rgba(184,134,11,.3);border-radius:20px;padding:2px 8px;font-size:.65rem;font-weight:700;cursor:pointer}
#rzApp .rz-fchip:hover{background:#edd98a}

/* ── Table card ── */
#rzApp .rz-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh);overflow:hidden;margin-bottom:14px}
#rzApp .rz-scroll{overflow-x:auto}
#rzApp table{width:100%;border-collapse:collapse;min-width:820px}
#rzApp thead tr{background:linear-gradient(to bottom,var(--surface-2),#f0ebe0);border-bottom:2px solid var(--border)}
#rzApp th{padding:9px 11px;font-size:.61rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-3);white-space:nowrap;text-align:left;user-select:none}
#rzApp th.c,#rzApp td.c{text-align:center}
#rzApp th.sortable{cursor:pointer}
#rzApp th.sortable:hover{color:var(--gold)}
#rzApp th .sa{margin-left:3px;opacity:.3;font-size:.58rem}
#rzApp th.asc .sa::after{content:'▲';opacity:1;color:var(--gold)}
#rzApp th.desc .sa::after{content:'▼';opacity:1;color:var(--gold)}
#rzApp th:not(.asc):not(.desc) .sa::after{content:'▲▼'}
#rzApp tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s}
#rzApp tbody tr:last-child{border-bottom:none}
#rzApp tbody tr:hover{background:#fdf9ef}
#rzApp td{padding:9px 11px;font-size:.79rem;color:var(--text-1);vertical-align:middle;line-height:1.4}
#rzApp td .sub{font-size:.68rem;color:var(--text-3);display:block;margin-top:2px}
#rzApp .rz-norows{text-align:center;padding:36px 16px;color:var(--text-3);font-size:.8rem}
#rzApp .rz-norows i{font-size:1.8rem;display:block;margin-bottom:8px;color:var(--border)}

/* ── Cells ── */
#rzApp .snob{width:24px;height:24px;border-radius:50%;background:var(--gold-muted);color:var(--gold);font-weight:800;font-size:.65rem;display:inline-flex;align-items:center;justify-content:center}
#rzApp .nm{font-weight:700;font-size:.82rem}
#rzApp .spill{display:inline-flex;align-items:center;padding:3px 9px;border-radius:20px;font-size:.64rem;font-weight:800;white-space:nowrap;margin-bottom:4px}
#rzApp .s0{background:var(--amber-bg);color:var(--amber)}
#rzApp .s1{background:var(--blue-bg);color:var(--blue)}
#rzApp .s2{background:var(--green-bg);color:var(--green)}
#rzApp .s3{background:var(--red-bg);color:var(--red)}
#rzApp .s4{background:#f0f0ff;color:#4338ca}
#rzApp .aprow{display:flex;align-items:center;gap:4px;font-size:.66rem;color:var(--text-2);margin-top:2px}
#rzApp .adot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
#rzApp .a0{background:#f59e0b}
#rzApp .a1{background:var(--green)}
#rzApp .a2{background:var(--red)}
#rzApp .chat-btn{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;background:var(--blue-bg);color:var(--blue);border:1px solid rgba(29,78,216,.18);font-size:.68rem;font-weight:700;text-decoration:none;transition:background .14s;white-space:nowrap}
#rzApp .chat-btn:hover{background:#dbeafe;text-decoration:none}
#rzApp .ccnt{background:var(--gold);color:#fff;border-radius:50%;width:16px;height:16px;display:inline-flex;align-items:center;justify-content:center;font-size:.55rem;font-weight:800}
#rzApp .actg{display:flex;align-items:center;gap:4px;justify-content:center;flex-wrap:wrap}
#rzApp .aico{width:28px;height:28px;border-radius:7px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;font-size:.75rem;transition:all .14s;outline:none}
#rzApp .aico.ap:hover{background:var(--blue-bg);border-color:var(--blue);color:var(--blue)}
#rzApp .aico.rj:hover{background:var(--red-bg);border-color:var(--red);color:var(--red)}
#rzApp .aico.dl:hover{background:var(--amber-bg);border-color:var(--amber);color:var(--amber)}
#rzApp .aico.vw{background:var(--gold-muted);border-color:rgba(184,134,11,.35);color:var(--gold);font-size:.67rem;font-weight:800;width:auto;padding:0 10px}
#rzApp .aico.vw:hover{background:#edd98a;border-color:var(--gold)}

/* ── Footer ── */
#rzApp .rz-foot{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid var(--border-light);font-size:.67rem;color:var(--text-3);flex-wrap:wrap;gap:6px;background:var(--surface-2)}
#rzApp .rz-cnt{background:var(--gold-muted);color:var(--gold);border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:800}

/* ── Modals ── */
#rzApp .rz-ov{position:fixed;inset:0;background:rgba(26,22,16,.48);z-index:2000;display:none;align-items:center;justify-content:center;padding:14px}
#rzApp .rz-ov.open{display:flex}
#rzApp .rz-modal{background:var(--surface);border:1px solid var(--border);border-radius:18px;width:100%;max-width:480px;box-shadow:var(--sh3);max-height:90vh;display:flex;flex-direction:column}
#rzApp .rz-mhd{display:flex;align-items:center;justify-content:space-between;padding:13px 16px;border-bottom:1px solid var(--border-light);flex-shrink:0;background:var(--surface-2);border-radius:18px 18px 0 0}
#rzApp .rz-mtit{font-weight:800;font-size:.88rem;color:var(--text-1);display:flex;align-items:center;gap:7px}
#rzApp .rz-mclose{width:28px;height:28px;border-radius:7px;border:none;background:var(--border);color:var(--text-2);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;transition:all .14s}
#rzApp .rz-mclose:hover{background:var(--gold-muted);color:var(--gold)}
#rzApp .rz-mbody{overflow-y:auto;flex:1;padding:14px 16px}
#rzApp .rz-mft{padding:12px 16px;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;gap:7px;flex-shrink:0}
#rzApp .drow{display:flex;align-items:flex-start;padding:7px 10px;border-radius:8px;background:var(--surface-2);gap:10px;margin-bottom:5px;border:1px solid var(--border-light)}
#rzApp .dk{font-size:.65rem;font-weight:800;color:var(--text-3);text-transform:uppercase;letter-spacing:.5px;min-width:90px;flex-shrink:0;padding-top:1px}
#rzApp .dv{font-size:.8rem;color:var(--text-1);font-weight:600;word-break:break-word;flex:1}
#rzApp .rz-lbl{display:block;font-size:.67rem;font-weight:800;color:var(--text-2);margin:12px 0 5px;text-transform:uppercase;letter-spacing:.5px}
#rzApp .rz-ta{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:8px 10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.79rem;color:var(--text-1);resize:vertical;min-height:80px;outline:none;background:var(--surface-2);transition:border-color .15s}
#rzApp .rz-ta:focus{border-color:var(--gold);background:var(--surface)}
#rzApp .rz-ta.err{border-color:var(--red);background:var(--red-bg)}
#rzApp .btn-c{padding:7px 15px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .14s}
#rzApp .btn-c:hover{background:var(--surface-2)}
#rzApp .btn-ok{padding:7px 15px;border-radius:8px;border:none;background:var(--gold);color:#fff;font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:opacity .14s}
#rzApp .btn-ok:hover{opacity:.88}
#rzApp .btn-rj{padding:7px 15px;border-radius:8px;border:none;background:var(--red);color:#fff;font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:opacity .14s}
#rzApp .btn-rj:hover{opacity:.88}
#rzApp .fin-warn{background:var(--amber-bg);border:1px solid rgba(180,83,9,.22);border-radius:8px;padding:9px 11px;color:var(--amber);font-size:.74rem;font-weight:600;margin-bottom:12px;display:flex;gap:7px}
#rzApp .fin-tbl{width:100%;border-collapse:collapse;font-size:.75rem;margin-bottom:10px}
#rzApp .fin-tbl th,#rzApp .fin-tbl td{padding:6px 8px;border-bottom:1px solid var(--border-light);text-align:left}
#rzApp .fin-tbl th{font-weight:800;color:var(--text-2);font-size:.62rem;text-transform:uppercase;letter-spacing:.4px;background:var(--surface-2)}
#rzApp .fin-tbl tr:last-child td,#rzApp .fin-tbl tr:last-child th{border-bottom:none;font-weight:800}
#rzApp .fok{color:var(--text-3)!important}
#rzApp .fbad{color:var(--red)!important}

/* ── Toast ── */
#rzApp .rz-toast{position:fixed;top:14px;right:14px;padding:9px 16px;border-radius:10px;font-size:.78rem;font-weight:700;z-index:9999;display:none;box-shadow:var(--sh2);animation:rzTin .25s}
#rzApp .rz-toast.ok{background:var(--green);color:#fff}
#rzApp .rz-toast.er{background:var(--red);color:#fff}
@keyframes rzTin{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}

@media(max-width:640px){
  #rzApp{padding:10px}
  #rzApp .rz-pagetitle{font-size:.82rem;letter-spacing:.3px}
  #rzApp .rz-bar-row{gap:5px}
  #rzApp .rz-sel{font-size:.7rem;height:28px}
  #rzApp .rz-breadcrumb{font-size:.67rem}
}
</style>

<?php
/* ─── Helper: safe hijri month string ─────────────────────── */
if (!function_exists('rz_month_str')) {
  function rz_month_str($ci, $month_id) {
    $raw = $ci->HijriCalendar->hijri_month_name($month_id);
    if (is_array($raw)) return (string)($raw['hijri_month'] ?? $raw['name'] ?? reset($raw) ?? '');
    return (string)$raw;
  }
}

/* ─── Helper: safe FNN check ──────────────────────────────── */
if (!function_exists('rz_is_fnn')) {
  function rz_is_fnn($r) {
    $chk = function($s){ $s=strtolower((string)$s); return strpos($s,'fnn')!==false||(strpos($s,'fala')!==false&&(strpos($s,'niyaz')!==false||strpos($s,'niaz')!==false)); };
    if (!empty($r['miqaat_details'])) {
      $miq=json_decode($r['miqaat_details'],true);
      if (is_array($miq)) {
        foreach(['assigned_to','assign_type','name','type'] as $f){ if($chk($miq[$f]??''))return true; }
        if(!empty($miq['assignments'])&&is_array($miq['assignments'])){ foreach($miq['assignments'] as $a){ if($chk($a['assign_type']??$a['type']??''))return true; } }
      }
    }
    return $chk($r['razaType']??'');
  }
}

$ci = &get_instance();
$ci->load->model('HijriCalendar');

/* Build hijri year list + map */
$hijri_years=[]; $hijri_map=[];
foreach ($raza as $r) {
  $d='';
  if(!empty($r['miqaat_details'])){ $md=json_decode($r['miqaat_details'],true); if(is_array($md)&&!empty($md['date']))$d=substr($md['date'],0,10); }
  if(empty($d)&&!empty($r['razadata'])){ $rd=json_decode($r['razadata'],true); if(is_array($rd)&&!empty($rd['date']))$d=substr($rd['date'],0,10); }
  $hyear='';
  if(!empty($d)){ $p=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d); if(!empty($p['hijri_year'])){ $hyear=$p['hijri_year']; $hijri_years[$hyear]=$hyear; } }
  $hijri_map[$r['id']]=$hyear;
}
krsort($hijri_years);

/* Umoor list */
$umoors=[];
foreach($razatype as $rt){ if(!empty($rt['umoor']))$umoors[$rt['umoor']]=$rt['umoor']; }

/* Hijri months for JS */
$hijri_months_raw=$ci->HijriCalendar->get_hijri_month();
$hijri_months_js=[];
foreach($hijri_months_raw as $m) $hijri_months_js[$m['id']]=$m['hijri_month'];

/* Page type flags */
$is_umoor  = (!empty($umoor)&&$umoor==='12 Umoor Raza Applications');
$is_kaaraj = (!$is_umoor&&!empty($umoor)&&stripos($umoor,'Kaaraj')!==false);

/* Detect from param for breadcrumb */
$from = $this->input->get('from') ?: 'anjuman';
$from_label = 'Anjuman Dashboard';
$from_url   = base_url('anjuman');
if (strpos($from,'amil')!==false){ $from_label='Amil Dashboard'; $from_url=base_url('amil'); }
if (strpos($from,'admin')!==false){ $from_label='Admin Dashboard'; $from_url=base_url('admin'); }
?>

<div id="rzApp">
<div class="rz-toast" id="rzToast"></div>

<!-- ── Header ── -->
<div class="rz-hdr">
  <a href="<?php echo $from_url ?>" class="rz-back">
    <i class="fa-solid fa-arrow-left"></i> Back
  </a>
  <div class="rz-breadcrumb">
    <a href="<?php echo $from_url ?>"><?php echo $from_label ?></a>
    <span class="sep">›</span>
    <span class="cur"><?php echo htmlspecialchars($umoor ?: 'Raza Applications') ?></span>
  </div>
  <div class="rz-pagetitle"><?php echo htmlspecialchars($umoor ?: 'Raza Applications') ?></div>
</div>

<!-- ── Toolbar ── -->
<div class="rz-bar">
  <div class="rz-bar-row">

    <div class="rz-srchwrap">
      <i class="fa fa-search"></i>
      <input type="text" id="rzSearch" placeholder="Search name, event…" oninput="rzFilter()" autocomplete="off">
      <button class="clr" onclick="document.getElementById('rzSearch').value='';rzFilter()" tabindex="-1">&#x2715;</button>
    </div>

    <div class="rz-bar-sep"></div>

    <select class="rz-sel" id="rzStatus" onchange="rzFilter()">
      <option value="">All Status</option>
      <option value="0">Pending</option>
      <option value="1">Recommended</option>
      <option value="2">Approved</option>
      <option value="3">Rejected</option>
      <option value="4">Not Recommended</option>
    </select>

    <?php if (!$is_umoor && !$is_kaaraj): ?>
    <select class="rz-sel" id="rzType" onchange="rzFilter()">
      <option value="">All Types</option>
      <option value="Shehrullah">Shehrullah</option>
      <option value="Ashara">Ashara</option>
      <option value="General">General</option>
      <option value="Ladies">Ladies</option>
    </select>
    <?php elseif($is_umoor): ?>
    <select class="rz-sel" id="rzUmoor" onchange="rzFilter()">
      <option value="">All Umoor</option>
      <?php foreach($umoors as $u): ?>
      <option value="<?php echo htmlspecialchars($u,ENT_QUOTES) ?>"><?php echo htmlspecialchars($u,ENT_QUOTES) ?></option>
      <?php endforeach ?>
    </select>
    <?php endif ?>

    <select class="rz-sel" id="rzYear" onchange="rzFilter()">
      <option value="">All Years</option>
      <?php foreach($hijri_years as $y): ?>
      <option value="<?php echo $y ?>"><?php echo $y ?>H</option>
      <?php endforeach ?>
    </select>

    <div class="rz-bar-sep"></div>

    <select class="rz-sel" id="rzSort" onchange="rzFilter()">
      <option value="2">Event Date ↓</option>
      <option value="3">Event Date ↑</option>
      <option value="0">Name A→Z</option>
      <option value="1">Name Z→A</option>
      <option value="4">Created ↓</option>
      <option value="5">Created ↑</option>
    </select>

    <button class="rz-refreshbtn" onclick="location.reload()">
      <i class="fa fa-refresh"></i> Refresh
    </button>
  </div>

  <!-- Active filter chips -->
  <div class="rz-active-filters" id="rzChips"></div>
</div>

<!-- ── Table ── -->
<div class="rz-card">
  <div class="rz-scroll">
    <table id="rzTable">
      <thead>
        <tr>
          <th class="c" style="width:40px">#</th>
          <th class="sortable" data-col="name">Name <span class="sa"></span></th>
          <th>Raza For</th>
          <th class="sortable" data-col="evdate">Event Date <span class="sa"></span></th>
          <th class="c">Chat</th>
          <th>Status</th>
          <th class="c" style="min-width:130px">Actions</th>
          <th class="sortable" data-col="ts">Created <span class="sa"></span></th>
        </tr>
      </thead>
      <tbody id="rzTbody">
<?php
$spmap  = [0=>['s0','Pending'],1=>['s1','Recommended'],2=>['s2','Approved'],3=>['s3','Rejected'],4=>['s4','Not Recommended']];
$admap  = [0=>'a0',1=>'a1',2=>'a2'];
$almap  = [0=>'Pending',1=>'Done',2=>'Rejected'];

foreach ($raza as $key => $r):
  /* ── event date ── */
  $greg_date='';
  if(!empty($r['miqaat_id'])&&!empty($r['miqaat_details'])){
    $mi2=json_decode($r['miqaat_details'],true);
    if(!empty($mi2['date']))$greg_date=$mi2['date'];
  } else {
    $tmp=json_decode($r['razadata']??'{}',true);
    if(!empty($tmp['date']))$greg_date=$tmp['date'];
  }
  $event_cell='—'; $hijri_str='';
  if(!empty($greg_date)){
    $event_cell=date('D, d M',strtotime($greg_date));
    $hijriP=$ci->HijriCalendar->get_hijri_parts_by_greg_date(substr($greg_date,0,10));
    if(!empty($hijriP['hijri_day'])){
      $hmn=rz_month_str($ci,$hijriP['hijri_month']);
      $hijri_str=intval($hijriP['hijri_day']).' '.$hmn.' '.intval($hijriP['hijri_year']).'H';
      $event_cell.='<span class="sub">'.$hijri_str.'</span>';
    }
  }

  /* ── raza for cell ── */
  $raza_for='';
  $miq_type_attr='';
  if(!empty($r['miqaat_id'])&&!empty($r['miqaat_details'])){
    $miqd=json_decode($r['miqaat_details'],true);
    if(is_array($miqd)){
      $mn=htmlspecialchars($miqd['name']??'',ENT_QUOTES);
      $mt=htmlspecialchars($miqd['type']??'',ENT_QUOTES);
      $ma=htmlspecialchars($miqd['assigned_to']??'',ENT_QUOTES);
      $miq_type_attr=$miqd['type']??'';
      $metaP=array_filter([$mt,$ma]);
      $raza_for='<span style="font-weight:700">'.$mn.'</span>'.($metaP?'<span class="sub">'.implode(' · ',$metaP).'</span>':'');
    }
  } elseif(!empty($r['umoor'])){
    $raza_for='<span style="font-weight:700">'.htmlspecialchars($r['umoor'],ENT_QUOTES).'</span><span class="sub">'.htmlspecialchars($r['razaType']??'',ENT_QUOTES).'</span>';
  } else {
    $raza_for=htmlspecialchars($r['razaType']??'',ENT_QUOTES);
  }

  /* ── pills/status ── */
  $sp=$spmap[(int)$r['status']]??['s0','Unknown'];
  $cdot=$admap[(int)($r['coordinator-status']??0)]??'a0';
  $jdot=$admap[(int)($r['Janab-status']??0)]??'a0';
  $clbl=$almap[(int)($r['coordinator-status']??0)]??'Pending';
  $jlbl=$almap[(int)($r['Janab-status']??0)]??'Pending';

  /* ── name ── */
  $dname=rz_is_fnn($r)?'Fala ni Niyaz':htmlspecialchars($r['user_name']??'',ENT_QUOTES,'UTF-8');
  $can_act=((int)($r['Janab-status']??0)===0);
  $ccnt=(int)($r['chat_count']??0);
  $hyr=$hijri_map[$r['id']]??'';

  /* search text for JS data-search attr */
  $search_text=strtolower($dname.' '.($r['razaType']??'').' '.($r['umoor']??'').' '.($miq_type_attr));
?>
        <tr
          data-id="<?php echo (int)$r['id'] ?>"
          data-status="<?php echo (int)$r['status'] ?>"
          data-hy="<?php echo htmlspecialchars((string)$hyr,ENT_QUOTES) ?>"
          data-miqtype="<?php echo htmlspecialchars($miq_type_attr,ENT_QUOTES) ?>"
          data-umoor="<?php echo htmlspecialchars($r['umoor']??'',ENT_QUOTES) ?>"
          data-name="<?php echo htmlspecialchars($r['user_name']??'',ENT_QUOTES) ?>"
          data-ts="<?php echo htmlspecialchars($r['time-stamp']??'',ENT_QUOTES) ?>"
          data-evdate="<?php echo htmlspecialchars($greg_date,ENT_QUOTES) ?>"
          data-search="<?php echo htmlspecialchars($search_text,ENT_QUOTES) ?>">

          <td class="c"><span class="snob"><?php echo $key+1 ?></span></td>
          <td><span class="nm"><?php echo $dname ?></span></td>
          <td><?php echo $raza_for ?></td>
          <td><?php echo $event_cell ?></td>
          <td class="c">
            <a href="<?php echo base_url('Accounts/chat/').$r['id'].'/anjuman' ?>" class="chat-btn">
              <i class="fa fa-comment"></i> Chat
              <?php if($ccnt>0): ?><span class="ccnt"><?php echo $ccnt ?></span><?php endif ?>
            </a>
          </td>
          <td>
            <span class="spill <?php echo $sp[0] ?>"><?php echo $sp[1] ?></span>
            <div class="aprow"><span class="adot <?php echo $cdot ?>"></span>Jamat · <?php echo $clbl ?></div>
            <div class="aprow"><span class="adot <?php echo $jdot ?>"></span>Amil Saheb · <?php echo $jlbl ?></div>
          </td>
          <td class="c">
            <div class="actg">
              <?php if($can_act): ?>
              <button class="aico ap" onclick="rzApprove(<?php echo (int)$r['id'] ?>)" title="Recommend"><i class="fa fa-check-circle"></i></button>
              <button class="aico rj" onclick="rzReject(<?php echo (int)$r['id'] ?>)" title="Not Recommend"><i class="fa fa-times-circle"></i></button>
              <button class="aico dl" onclick="rzDelete(<?php echo (int)$r['id'] ?>)" title="Delete"><i class="fa fa-trash"></i></button>
              <?php endif ?>
              <button class="aico vw" onclick="rzView(<?php echo (int)$r['id'] ?>)">View</button>
            </div>
          </td>
          <td><span class="sub" style="font-size:.75rem;color:var(--text-2)"><?php echo date('d M @ g:ia',strtotime($r['time-stamp']??'now')) ?></span></td>
        </tr>
<?php endforeach ?>
      </tbody>
    </table>
  </div>
  <div class="rz-foot">
    <span class="rz-cnt" id="rzCnt"><?php echo count($raza) ?> records</span>
    <span><?php echo htmlspecialchars($umoor?:'Raza Applications') ?> &mdash; <?php echo $from_label ?></span>
  </div>
</div>

<!-- View Modal -->
<div class="rz-ov" id="modView">
  <div class="rz-modal">
    <div class="rz-mhd">
      <span class="rz-mtit"><i class="fa fa-eye" style="color:var(--gold)"></i> Raza Details</span>
      <button class="rz-mclose" onclick="rzCM('modView')">&#x2715;</button>
    </div>
    <div class="rz-mbody" id="modViewBody"></div>
    <div class="rz-mft"><button class="btn-c" onclick="rzCM('modView')">Close</button></div>
  </div>
</div>

<!-- Approve Modal -->
<div class="rz-ov" id="modApprove">
  <div class="rz-modal">
    <div class="rz-mhd">
      <span class="rz-mtit"><i class="fa fa-check-circle" style="color:var(--blue)"></i> Recommend Raza</span>
      <button class="rz-mclose" onclick="rzCM('modApprove')">&#x2715;</button>
    </div>
    <div class="rz-mbody">
      <div id="modApD"></div>
      <label class="rz-lbl">Remark <span style="color:var(--text-3);font-weight:400;text-transform:none">(optional)</span></label>
      <textarea class="rz-ta" id="appRem" placeholder="Add a remark…"></textarea>
      <input type="hidden" id="appId"><input type="hidden" id="appIts">
    </div>
    <div class="rz-mft">
      <button class="btn-c" onclick="rzCM('modApprove')">Cancel</button>
      <button class="btn-ok" onclick="rzSubmitApprove()">Recommend</button>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="rz-ov" id="modReject">
  <div class="rz-modal">
    <div class="rz-mhd">
      <span class="rz-mtit"><i class="fa fa-times-circle" style="color:var(--red)"></i> Not Recommend</span>
      <button class="rz-mclose" onclick="rzCM('modReject')">&#x2715;</button>
    </div>
    <div class="rz-mbody">
      <div id="modRjD"></div>
      <label class="rz-lbl">Remark <span style="color:var(--red);font-weight:800">*</span></label>
      <textarea class="rz-ta" id="rejRem" placeholder="Reason required…"></textarea>
      <input type="hidden" id="rejId">
    </div>
    <div class="rz-mft">
      <button class="btn-c" onclick="rzCM('modReject')">Cancel</button>
      <button class="btn-rj" onclick="rzSubmitReject()">Not Recommend</button>
    </div>
  </div>
</div>

<!-- Financial Modal -->
<div class="rz-ov" id="modFin">
  <div class="rz-modal" style="max-width:500px">
    <div class="rz-mhd">
      <span class="rz-mtit"><i class="fa fa-exclamation-triangle" style="color:var(--amber)"></i> Pending Dues</span>
      <button class="rz-mclose" onclick="rzCM('modFin')">&#x2715;</button>
    </div>
    <div class="rz-mbody" id="modFinBody"></div>
    <div class="rz-mft">
      <button class="btn-c" onclick="rzCM('modFin')">Cancel</button>
      <button class="btn-ok" id="finProceed">Proceed Anyway</button>
    </div>
  </div>
</div>

</div><!-- /#rzApp -->

<script>
/* ── PHP data ── */
var rzData=<?php
  $out=[];
  foreach($raza as $r){
    $d='';
    if(!empty($r['miqaat_details'])){$md=json_decode($r['miqaat_details'],true);if(is_array($md)&&!empty($md['date']))$d=substr($md['date'],0,10);}
    if(empty($d)&&!empty($r['razadata'])){$rd=json_decode($r['razadata'],true);if(is_array($rd)&&!empty($rd['date']))$d=substr($rd['date'],0,10);}
    $hp=null;
    if(!empty($d)){
      $pts=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
      if(!empty($pts['hijri_year'])){$mn=rz_month_str($ci,$pts['hijri_month']);$hp=['year'=>(int)$pts['hijri_year'],'month'=>(int)$pts['hijri_month'],'day'=>(int)$pts['hijri_day'],'month_name'=>$mn];}
    }
    $r['hijri_parts']=$hp;
    $r['_evdate']=$d;
    $out[]=$r;
  }
  echo json_encode($out,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT);
?>;
var rzHMap=<?php echo json_encode($hijri_map,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;

/* ── Helpers ── */
function esc(s){return String(s==null?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
function fmtINR(n){return'₹'+(Math.round(n)||0).toLocaleString('en-IN')}
function parseMaybe(v){if(!v)return null;if(typeof v==='object')return v;try{return JSON.parse(v)}catch(e){return null}}
function getEvDate(r){if(r._evdate)return new Date(r._evdate);var m=parseMaybe(r.miqaat_details);if(m&&m.date)return new Date(m.date);var rd=parseMaybe(r.razadata);if(rd&&rd.date)return new Date(rd.date);return new Date(0)}

function rzOM(id){document.getElementById(id).classList.add('open')}
function rzCM(id){document.getElementById(id).classList.remove('open')}
document.addEventListener('click',function(e){if(e.target.classList.contains('rz-ov'))e.target.classList.remove('open')});
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('#rzApp .rz-ov.open').forEach(function(m){m.classList.remove('open')})});

function rzToast(msg,type){var t=document.getElementById('rzToast');t.textContent=msg;t.className='rz-toast '+(type||'ok');t.style.display='block';clearTimeout(t._t);t._t=setTimeout(function(){t.style.display='none'},2600)}

/* ── Build detail for modals ── */
function buildDetail(r){
  var rows=[];
  if(r.miqaat_id&&r.miqaat_details){
    var m=parseMaybe(r.miqaat_details)||{};
    rows=[['Miqaat',m.name||'—'],['Type',m.type||'—'],['Assigned To',m.assigned_to||'—'],['Date',m.date?new Date(m.date).toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'}):'—'],['Status',m.status==1?'Active':'Inactive']];
    if(m.assign_type==='Group'&&Array.isArray(m.assignments)&&m.assignments.length){
      rows.push(['Group Leader',(m.group_leader_name||'')+' '+(m.group_leader_surname||'')]);
      m.assignments.forEach(function(mb,i){rows.push(['Member '+(i+1),(mb.member_first_name||'')+' '+(mb.member_surname||'')])});
    }
  } else {
    var rf=parseMaybe(r.razafields)||{};var fields=rf.fields||[];var rd=parseMaybe(r.razadata)||{};var k=0;
    for(var key in rd){var f=fields[k]||{name:key,type:'text'};var val=rd[key];if(f.type==='select'&&f.options&&f.options[val])val=(f.options[val].name||val);rows.push([f.name,val]);k++}
  }
  if(r.hijri_parts&&r.hijri_parts.day)rows.push(['Hijri Date',r.hijri_parts.day+' '+(r.hijri_parts.month_name||'')+' '+r.hijri_parts.year+'H']);
  return rows.map(function(row){return'<div class="drow"><span class="dk">'+esc(row[0])+'</span><span class="dv">'+esc(String(row[1]))+'</span></div>'}).join('');
}

/* ── View ── */
function rzView(id){
  var r=rzData.find(function(x){return x.id==id});
  if(!r)return;
  document.getElementById('modViewBody').innerHTML=buildDetail(r);
  rzOM('modView');
}

/* ── Approve ── */
function rzApprove(id){
  var r=rzData.find(function(x){return x.id==id});
  if(!r)return;
  document.getElementById('modApD').innerHTML=buildDetail(r);
  document.getElementById('appRem').value='';
  document.getElementById('appRem').className='rz-ta';
  document.getElementById('appId').value=id;
  document.getElementById('appIts').value=r.user_id||r.userId||r.ITS_ID||r.its_id||'';
  rzOM('modApprove');
}
function rzSubmitApprove(){
  var id=document.getElementById('appId').value;
  var its=document.getElementById('appIts').value;
  var rem=document.getElementById('appRem').value.trim();
  rzCM('modApprove');
  if(its){
    fetch('<?php echo base_url('anjuman/member_financials_json') ?>?its_id='+encodeURIComponent(its),{credentials:'same-origin'})
      .then(function(res){return res.json()})
      .then(function(data){if(data&&data.success&&data.total_due>0){rzShowFin(data,id,rem)}else{rzDoApprove(id,rem)}})
      .catch(function(){rzDoApprove(id,rem)});
  } else {rzDoApprove(id,rem)}
}
function rzShowFin(data,id,rem){
  var rows=[['FMB Takhmeen',data.fmb_due],['Sabeel Takhmeen',data.sabeel_due],['General Contributions',data.gc_due],['Miqaat Invoices',data.miqaat_due],['Corpus Fund',data.corpus_due],['Ekram Fund',data.ekram_due||0]];
  var html='<div class="fin-warn"><i class="fa fa-exclamation-triangle"></i>&nbsp;Member has pending dues. Review before proceeding.</div>';
  html+='<table class="fin-tbl"><thead><tr><th>Category</th><th style="text-align:right">Due</th></tr></thead><tbody>';
  rows.forEach(function(row){html+='<tr><td>'+esc(row[0])+'</td><td style="text-align:right" class="'+(row[1]>0?'fbad':'fok')+'">'+fmtINR(row[1])+'</td></tr>'});
  html+='<tr><td><strong>Total</strong></td><td style="text-align:right" class="fbad"><strong>'+fmtINR(data.total_due)+'</strong></td></tr></tbody></table>';
  if(Array.isArray(data.miqaat_invoices)&&data.miqaat_invoices.length){
    html+='<p style="font-weight:800;font-size:.74rem;color:var(--text-2);margin:8px 0 5px">Miqaat Invoices</p><div style="max-height:150px;overflow:auto"><table class="fin-tbl"><thead><tr><th>Assigned To</th><th>Invoice</th><th style="text-align:right">Due</th></tr></thead><tbody>';
    data.miqaat_invoices.forEach(function(row){html+='<tr><td>'+esc(row.assigned_to||'')+'</td><td>'+esc(row.invoice||'')+'</td><td style="text-align:right" class="fbad">'+fmtINR(row.due)+'</td></tr>'});
    html+='</tbody></table></div>';
  }
  document.getElementById('modFinBody').innerHTML=html;
  document.getElementById('finProceed').onclick=function(){rzCM('modFin');rzDoApprove(id,rem)};
  rzOM('modFin');
}
function rzDoApprove(id,rem){
  $.ajax({type:'POST',url:'<?php echo base_url('admin/approveRaza') ?>',data:{raza_id:id,remark:rem},
    success:function(){rzToast('Raza recommended successfully','ok');location.reload()},
    error:function(){rzToast('Something went wrong — please try again','er')}
  });
}

/* ── Reject ── */
function rzReject(id){
  var r=rzData.find(function(x){return x.id==id});
  if(!r)return;
  document.getElementById('modRjD').innerHTML=buildDetail(r);
  document.getElementById('rejRem').value='';
  document.getElementById('rejRem').className='rz-ta';
  document.getElementById('rejId').value=id;
  rzOM('modReject');
}
function rzSubmitReject(){
  var rem=document.getElementById('rejRem').value.trim();
  if(!rem){document.getElementById('rejRem').className='rz-ta err';return}
  var id=document.getElementById('rejId').value;
  rzCM('modReject');
  $.ajax({type:'POST',url:'<?php echo base_url('admin/rejectRaza') ?>',data:{raza_id:id,remark:rem},
    success:function(){rzToast('Marked as not recommended','ok');location.reload()},
    error:function(){rzToast('Something went wrong','er')}
  });
}

/* ── Delete ── */
function rzDelete(id){if(!confirm('Delete this raza request?'))return;window.location.href='<?php echo base_url('anjuman/DeleteRaza/') ?>'+id}

/* ══════════════════════════════════════════════════════════
   FILTER ENGINE — reads data-* attrs directly from DOM rows
   No JSON lookup needed → no type mismatch possible
══════════════════════════════════════════════════════════ */
var _sortCol=null, _sortDir=1;

/* Column header sort */
document.querySelectorAll('#rzApp th.sortable').forEach(function(th){
  th.addEventListener('click',function(){
    var col=th.dataset.col;
    if(_sortCol===col){_sortDir*=-1}else{_sortCol=col;_sortDir=1}
    document.querySelectorAll('#rzApp th.sortable').forEach(function(h){h.classList.remove('asc','desc')});
    th.classList.add(_sortDir===1?'asc':'desc');
    rzFilter();
  });
});

function rzFilter(){
  var search   = (document.getElementById('rzSearch').value||'').toLowerCase().trim();
  var status   = document.getElementById('rzStatus').value;           /* '' | '0'|'1'|'2'|'3'|'4' */
  var sortV    = parseInt(document.getElementById('rzSort').value);
  var year     = (document.getElementById('rzYear')||{value:''}).value||'';
  var typeEl   = document.getElementById('rzType');
  var miqType  = typeEl ? typeEl.value.toLowerCase() : '';
  var umoorEl  = document.getElementById('rzUmoor');
  var umoorV   = umoorEl ? umoorEl.value.toLowerCase() : '';

  var rows = Array.prototype.slice.call(document.querySelectorAll('#rzTbody tr[data-id]'));
  var visible = 0;

  /* ── FILTER: hide/show each row based on its data-* attrs ── */
  rows.forEach(function(tr){
    var show = true;

    /* status — compare as strings, both sides */
    if(status !== '' && tr.dataset.status !== status){ show=false; }

    /* hijri year */
    if(show && year && tr.dataset.hy !== year){ show=false; }

    /* miqaat type */
    if(show && miqType && (tr.dataset.miqtype||'').toLowerCase() !== miqType){ show=false; }

    /* umoor */
    if(show && umoorV && (tr.dataset.umoor||'').toLowerCase() !== umoorV){ show=false; }

    /* text search — uses pre-built data-search attr */
    if(show && search){
      var hay = (tr.dataset.search||'') + ' ' + (tr.textContent||'').toLowerCase();
      if(hay.indexOf(search)===-1){ show=false; }
    }

    tr.style.display = show ? '' : 'none';
    if(show) visible++;
  });

  /* ── SORT ── */
  var tbody = document.getElementById('rzTbody');
  var vis   = rows.filter(function(tr){ return tr.style.display !== 'none' });

  vis.sort(function(a,b){
    /* column-header sort takes priority */
    if(_sortCol){
      var d = _sortDir;
      if(_sortCol==='name')  return d*((a.dataset.name||'').localeCompare(b.dataset.name||''));
      if(_sortCol==='evdate')return d*(new Date(a.dataset.evdate||0)-new Date(b.dataset.evdate||0));
      if(_sortCol==='ts')    return d*(new Date(a.dataset.ts||0)-new Date(b.dataset.ts||0));
    }
    /* dropdown sort */
    switch(sortV){
      case 0: return (a.dataset.name||'').localeCompare(b.dataset.name||'');
      case 1: return (b.dataset.name||'').localeCompare(a.dataset.name||'');
      case 2: return new Date(b.dataset.evdate||0)-new Date(a.dataset.evdate||0);
      case 3: return new Date(a.dataset.evdate||0)-new Date(b.dataset.evdate||0);
      case 4: return new Date(b.dataset.ts||0)-new Date(a.dataset.ts||0);
      case 5: return new Date(a.dataset.ts||0)-new Date(b.dataset.ts||0);
      default:return 0;
    }
  });
  vis.forEach(function(tr){ tbody.appendChild(tr) });

  /* ── Renumber ── */
  var n=0;
  vis.forEach(function(tr){ n++; var s=tr.querySelector('.snob'); if(s) s.textContent=n; });

  /* ── Count ── */
  document.getElementById('rzCnt').textContent = visible+' record'+(visible!==1?'s':'');

  /* ── Active filter chips ── */
  var chips=[]; var chipsWrap=document.getElementById('rzChips');
  if(status){var lbl={0:'Pending',1:'Recommended',2:'Approved',3:'Rejected',4:'Not Recommended'}[status]||status;chips.push({label:'Status: '+lbl,clear:function(){document.getElementById('rzStatus').value='';rzFilter()}})}
  if(miqType){chips.push({label:'Type: '+miqType,clear:function(){document.getElementById('rzType').value='';rzFilter()}})}
  if(umoorV){chips.push({label:'Umoor: '+umoorV,clear:function(){document.getElementById('rzUmoor').value='';rzFilter()}})}
  if(year){chips.push({label:'Year: '+year+'H',clear:function(){document.getElementById('rzYear').value='';rzFilter()}})}
  if(search){chips.push({label:'Search: "'+search+'"',clear:function(){document.getElementById('rzSearch').value='';rzFilter()}})}

  chipsWrap.innerHTML='';
  chips.forEach(function(c,i){
    var ch=document.createElement('span');
    ch.className='rz-fchip';
    ch.innerHTML=esc(c.label)+'&nbsp;&#x2715;';
    (function(fn){ch.onclick=fn})(c.clear);
    chipsWrap.appendChild(ch);
  });
  chipsWrap.classList.toggle('show',chips.length>0);
}

/* Run on load — default sort: event date newest first */
document.addEventListener('DOMContentLoaded',function(){ rzFilter(); });
</script>