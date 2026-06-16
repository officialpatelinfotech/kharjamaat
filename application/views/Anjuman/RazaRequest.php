<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* ── Golden Theme Variables ── */
:root {
  --gold:         #b8860b;
  --gold-light:   #e6c84a;
  --gold-muted:   #f5e9c0;
  --gold-border:  rgba(230,200,74,.35);
  --bg:           #faf7f0;
  --surface:      #ffffff;
  --surface-2:    #f7f4ec;
  --border:       #e8e0cc;
  --border-light: #f0ece0;
  --text-1:       #1a1610;
  --text-2:       #5a5244;
  --text-3:       #9c8f7a;
  --green:        #1a6645;
  --green-bg:     #eaf4ee;
  --red:          #b91c1c;
  --red-bg:       #fef2f2;
  --blue:         #1d4ed8;
  --blue-bg:      #eff6ff;
  --orange:       #b45309;
  --orange-bg:    #fff7ed;
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --shadow:       0 4px 16px rgba(0,0,0,.07),0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:    0 8px 32px rgba(0,0,0,.10),0 2px 8px rgba(0,0,0,.05);
  --radius:       14px;
  --radius-sm:    10px;
}
*,*::before,*::after{box-sizing:border-box;}

/* ── Full-width wrapper ── */
#rzApp{
  font-family:'Plus Jakarta Sans',sans-serif;
  color:var(--text-1);
  background:var(--bg);
  min-height:100vh;
  padding-top:57px;
}
#rzApp .rz-wrap{
  width:100%;
  padding:14px 16px 40px;
}

/* ── Compact Page Header ── */
#rzApp .rz-header{
  background:linear-gradient(135deg,#78520a 0%,#b8860b 50%,#c9a227 100%);
  border-radius:14px;
  padding:10px 16px;
  margin-bottom:14px;
  position:relative;
  overflow:hidden;
  display:grid;
  grid-template-columns:auto 1fr auto;
  align-items:center;
  gap:12px;
  min-height:0;
}
#rzApp .rz-header::before{
  content:'';position:absolute;inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events:none;
}
#rzApp .rz-header::after{
  content:'';position:absolute;right:-30px;top:-30px;
  width:140px;height:140px;
  background:radial-gradient(circle,rgba(255,255,255,.10) 0%,transparent 70%);
  pointer-events:none;
}

/* Left: back button */
#rzApp .hdr-back{
  display:inline-flex;align-items:center;gap:6px;
  padding:6px 14px;border-radius:8px;
  font-size:.8rem;font-weight:700;
  text-decoration:none;
  border:1px solid rgba(255,255,255,.3);
  background:rgba(255,255,255,.15);
  color:#fff;
  cursor:pointer;
  white-space:nowrap;
  position:relative;z-index:1;
  transition:background .15s;
}
#rzApp .hdr-back:hover{background:rgba(255,255,255,.28);color:#fff;text-decoration:none;}

/* Center: umoor title */
#rzApp .hdr-center{
  text-align:center;
  position:relative;z-index:1;
}
#rzApp .hdr-umoor{
  font-family:'Literata',Georgia,serif;
  font-size:1rem;
  font-weight:600;
  color:#fff;
  margin:0;
  line-height:1.3;
}

/* Right: count badge */
#rzApp .hdr-badge{
  position:relative;z-index:1;
  flex-shrink:0;
  background:rgba(255,255,255,.15);
  border:1px solid rgba(255,255,255,.25);
  border-radius:10px;
  padding:5px 12px;
  text-align:center;
  white-space:nowrap;
}
#rzApp .hdr-badge-val{
  font-size:1.15rem;font-weight:800;color:#fff;
  display:block;line-height:1;
}
#rzApp .hdr-badge-lbl{
  font-size:.6rem;font-weight:700;
  color:rgba(255,255,255,.65);
  letter-spacing:.5px;text-transform:uppercase;
  display:block;margin-top:1px;
}

/* ── Filter bar ── */
#rzApp .filter-bar{
  background:var(--surface);
  border:1.5px solid var(--border);
  border-radius:var(--radius);
  padding:12px 16px;
  margin-bottom:14px;
  box-shadow:var(--shadow-sm);
}
#rzApp .filter-bar-inner{display:flex;flex-wrap:wrap;gap:8px;align-items:center;}
#rzApp .filter-select{
  height:36px;padding:0 11px;
  border:1.5px solid var(--border);border-radius:8px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;color:var(--text-1);
  background:var(--surface-2);outline:none;cursor:pointer;
  transition:border-color .15s,box-shadow .15s;
  min-width:130px;flex:1 1 120px;
}
#rzApp .filter-select:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);}
#rzApp .search-wrap{position:relative;flex:1 1 200px;}
#rzApp .search-wrap .fa-search{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:.8rem;pointer-events:none;}
#rzApp #razaSearchInput{
  width:100%;height:36px;padding:0 11px 0 32px;
  border:1.5px solid var(--border);border-radius:8px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;
  color:var(--text-1);background:var(--surface-2);outline:none;
  transition:border-color .15s,box-shadow .15s;
}
#rzApp #razaSearchInput:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);}
#rzApp .refresh-btn{
  display:inline-flex;align-items:center;gap:6px;
  height:36px;padding:0 14px;border-radius:8px;
  border:1.5px solid var(--border);background:var(--surface);
  color:var(--text-2);font-size:.8rem;font-weight:700;
  cursor:pointer;transition:all .15s;white-space:nowrap;
}
#rzApp .refresh-btn:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);}

/* ── Table card ── */
#rzApp .table-card{
  background:var(--surface);
  border:1.5px solid var(--border);
  border-radius:var(--radius);
  box-shadow:var(--shadow-sm);
  overflow:hidden;
}
#rzApp .table-card .tc-header{
  padding:10px 16px;
  background:var(--surface-2);
  border-bottom:1.5px solid var(--border-light);
  display:flex;align-items:center;gap:8px;
}
#rzApp .tc-icon{
  width:26px;height:26px;border-radius:6px;
  background:var(--gold-muted);color:var(--gold);
  display:inline-flex;align-items:center;justify-content:center;
  font-size:.76rem;flex-shrink:0;
}
#rzApp .tc-title{font-size:.78rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;}
#rzApp .table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}

/* ── Table ── */
#rzApp table{width:100%;border-collapse:collapse;font-size:.83rem;}
#rzApp thead tr{background:var(--gold-muted);}
#rzApp thead th{
  padding:10px 12px;font-size:.7rem;font-weight:800;
  color:var(--text-2);text-transform:uppercase;letter-spacing:.4px;
  white-space:nowrap;border-bottom:2px solid var(--gold-border);
  text-align:left;cursor:pointer;user-select:none;
}
#rzApp thead th:hover{color:var(--gold);}
#rzApp thead th .sort-icon{margin-left:4px;opacity:.5;font-size:.62rem;}
#rzApp thead th:hover .sort-icon{opacity:1;}
#rzApp tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s;}
#rzApp tbody tr:last-child{border-bottom:none;}
#rzApp tbody tr:hover{background:rgba(184,134,11,.04);}
#rzApp td{padding:10px 12px;vertical-align:middle;color:var(--text-1);}
#rzApp td.sno{color:var(--text-3);font-size:.76rem;font-weight:700;width:36px;}

/* ── Name cell ── */
#rzApp .rz-name{font-weight:700;font-size:.84rem;color:var(--text-1);}
#rzApp .rz-type{font-size:.77rem;color:var(--text-2);font-weight:600;margin-top:1px;}
#rzApp .rz-type-meta{font-size:.7rem;color:var(--text-3);margin-top:1px;}

/* ── Date cell ── */
#rzApp .rz-date{font-size:.81rem;font-weight:600;color:var(--text-1);}
#rzApp .rz-hijri{font-size:.7rem;color:var(--text-3);margin-top:2px;}

/* ── Status badges ── */
#rzApp .status-main{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:20px;font-size:.7rem;font-weight:700;border:1.5px solid transparent;white-space:nowrap;margin-bottom:4px;}
#rzApp .status-main.pending  {background:#fffbeb;color:#92400e;border-color:#fcd34d;}
#rzApp .status-main.recommended{background:var(--blue-bg);color:var(--blue);border-color:#93c5fd;}
#rzApp .status-main.approved {background:var(--green-bg);color:var(--green);border-color:#86efac;}
#rzApp .status-main.rejected {background:var(--red-bg);color:var(--red);border-color:#fca5a5;}
#rzApp .status-main.notrecommended{background:#f5f3ff;color:#6d28d9;border-color:#c4b5fd;}
#rzApp .status-sub{display:flex;flex-direction:column;gap:2px;margin-top:3px;}
#rzApp .sub-row{display:inline-flex;align-items:center;gap:4px;font-size:.7rem;color:var(--text-2);}

/* ── Chat button ── */
#rzApp .chat-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;background:var(--blue-bg);color:var(--blue);border:1.5px solid #93c5fd;font-size:.76rem;font-weight:700;text-decoration:none;transition:background .15s;white-space:nowrap;}
#rzApp .chat-btn:hover{background:#dbeafe;color:var(--blue);text-decoration:none;}
#rzApp .chat-badge{display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;background:var(--blue);color:#fff;border-radius:50%;font-size:.62rem;font-weight:800;}

/* ── Action buttons ── */
#rzApp .action-wrap{display:flex;flex-direction:column;gap:5px;align-items:flex-start;}
#rzApp .btn-group-row{display:flex;gap:5px;}
#rzApp .act-btn{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;border:1.5px solid;cursor:pointer;font-size:.78rem;transition:all .15s;background:transparent;}
#rzApp .act-btn.approve{border-color:#86efac;color:var(--green);}
#rzApp .act-btn.approve:hover{background:var(--green-bg);}
#rzApp .act-btn.reject{border-color:#fca5a5;color:var(--red);}
#rzApp .act-btn.reject:hover{background:var(--red-bg);}
#rzApp .act-btn.del{border-color:#fcd34d;color:#92400e;}
#rzApp .act-btn.del:hover{background:#fffbeb;}
#rzApp .act-btn.view{border-color:#93c5fd;color:var(--blue);width:auto;padding:0 10px;font-size:.73rem;font-weight:700;}
#rzApp .act-btn.view:hover{background:var(--blue-bg);}

/* ── Created date ── */
#rzApp .rz-created{font-size:.76rem;color:var(--text-2);}

/* ── Empty state ── */
#rzApp .empty-state{padding:44px 20px;text-align:center;color:var(--text-3);}
#rzApp .empty-state i{font-size:2rem;margin-bottom:10px;display:block;}
#rzApp .empty-state p{font-size:.9rem;margin:0;}

/* ── Toast ── */
#rzApp .toast-msg{position:fixed;top:66px;right:16px;background:linear-gradient(135deg,#1a6645,#2d9d68);color:#fff;padding:11px 18px;border-radius:11px;font-size:.83rem;font-weight:700;z-index:9999;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:8px;}

/* ── Modal overlay ── */
#rzApp .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;backdrop-filter:blur(2px);}
#rzApp .modal-overlay.active{display:flex;align-items:center;justify-content:center;padding:16px;}

/* ── Modal card ── */
#rzApp .modal-card{background:var(--surface);border-radius:var(--radius);box-shadow:var(--shadow-lg);width:100%;max-width:500px;max-height:calc(100vh - 100px);overflow-y:auto;border:1.5px solid var(--border);}
#rzApp .modal-hd{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:var(--gold-muted);border-bottom:1.5px solid var(--gold-border);}
#rzApp .modal-title{font-family:'Literata',Georgia,serif;font-size:1rem;font-weight:600;color:var(--text-1);margin:0;}
#rzApp .modal-close{width:26px;height:26px;border-radius:6px;border:1px solid var(--border);background:var(--surface);color:var(--text-3);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .15s;}
#rzApp .modal-close:hover{border-color:var(--gold);color:var(--gold);background:var(--gold-muted);}
#rzApp .modal-body{padding:18px;}
#rzApp .modal-ft{padding:12px 18px;border-top:1.5px solid var(--border-light);display:flex;justify-content:flex-end;gap:9px;flex-wrap:wrap;}

/* ── Detail table inside modal ── */
#rzApp .detail-list{list-style:none;margin:0 0 14px;padding:0;border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;}
#rzApp .detail-row{display:flex;border-bottom:1px solid var(--border-light);}
#rzApp .detail-row:last-child{border-bottom:none;}
#rzApp .dr-k{flex:0 0 40%;max-width:40%;padding:8px 12px;font-size:.7rem;font-weight:700;color:var(--text-3);text-transform:uppercase;background:var(--surface-2);border-right:1px solid var(--border-light);}
#rzApp .dr-v{flex:1;padding:8px 12px;font-size:.81rem;color:var(--text-1);}

/* ── Form fields inside modal ── */
#rzApp .form-group{margin-bottom:12px;}
#rzApp .form-group label{display:block;font-size:.73rem;font-weight:700;color:var(--text-2);text-transform:uppercase;letter-spacing:.3px;margin-bottom:5px;}
#rzApp .form-group textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.83rem;color:var(--text-1);background:var(--surface-2);outline:none;resize:vertical;min-height:90px;transition:border-color .15s,box-shadow .15s;}
#rzApp .form-group textarea:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);}

/* ── Modal buttons ── */
#rzApp .btn-gold{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;background:linear-gradient(135deg,#b8860b,#c9a227);border:none;color:#fff;font-size:.82rem;font-weight:800;cursor:pointer;box-shadow:0 3px 10px rgba(184,134,11,.3);transition:opacity .15s;}
#rzApp .btn-gold:hover{opacity:.9;}
#rzApp .btn-outline-secondary{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.82rem;font-weight:700;cursor:pointer;transition:all .15s;}
#rzApp .btn-outline-secondary:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);}
#rzApp .btn-danger{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;border:1.5px solid #fca5a5;background:var(--red-bg);color:var(--red);font-size:.82rem;font-weight:700;cursor:pointer;transition:background .15s;}
#rzApp .btn-danger:hover{background:#fee2e2;}

/* ── Financials modal ── */
#rzApp .fin-modal-card{max-width:540px;}
#rzApp .fin-warning{background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:9px 13px;font-size:.78rem;color:#92400e;margin-bottom:12px;display:flex;align-items:flex-start;gap:7px;}
#rzApp .fin-table{width:100%;border-collapse:collapse;font-size:.8rem;margin-bottom:7px;}
#rzApp .fin-table th,#rzApp .fin-table td{padding:8px 11px;border:1px solid var(--border-light);}
#rzApp .fin-table th{background:var(--surface-2);color:var(--text-2);font-weight:700;font-size:.7rem;text-transform:uppercase;}
#rzApp .fin-table tr:hover td{background:rgba(184,134,11,.03);}
#rzApp .fin-amt-due{color:var(--red);font-weight:700;}
#rzApp .fin-amt-ok{color:var(--text-3);}
#rzApp .fin-total-row th{background:var(--gold-muted)!important;color:var(--gold)!important;}

/* ── Responsive ── */
@media(max-width:768px){
  #rzApp .rz-wrap{padding:10px 10px 30px;}
  #rzApp .filter-bar-inner{flex-direction:column;}
  #rzApp .filter-select,#rzApp .search-wrap,#rzApp #razaSearchInput{width:100%;flex:none;}
  #rzApp td,#rzApp th{padding:7px 9px;}
  #rzApp .modal-card{max-width:100%;}
  #rzApp .hdr-umoor{font-size:.88rem;}
}
@media(max-width:480px){
  #rzApp table{font-size:.76rem;}
  #rzApp td{padding:6px 7px;}
  #rzApp .act-btn{width:27px;height:27px;font-size:.72rem;}
  #rzApp .hdr-back span{display:none;}
}
</style>

<!-- Toast -->
<div id="rzApp">
<div id="rz-toast" class="toast-msg" style="display:none;">
  <i class="fa-solid fa-circle-check"></i> <span id="rz-toast-msg">Done</span>
</div>

<div class="rz-wrap">

  <!-- ── Compact Page Header ── -->
  <div class="rz-header">
    <!-- Left: back -->
    <a href="<?php echo base_url('anjuman'); ?>" class="hdr-back">
      <i class="fa-solid fa-arrow-left"></i> <span>Back</span>
    </a>

    <!-- Center: umoor name -->
    <div class="hdr-center">
      <p class="hdr-umoor"><?php echo htmlspecialchars($umoor ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- Right: count -->
    <div class="hdr-badge">
      <span class="hdr-badge-val" id="rzCount"><?php echo count($raza ?? []); ?></span>
      <span class="hdr-badge-lbl">Requests</span>
    </div>
  </div>

  <!-- ── Filter Bar ── -->
  <div class="filter-bar">
    <div class="filter-bar-inner">
      <div class="search-wrap">
        <i class="fa fa-search"></i>
        <input type="text" id="razaSearchInput" placeholder="Search by name, type, status…">
      </div>

      <select onchange="updateTable();" id="filter" class="filter-select">
        <option value="" selected disabled>Filter by Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="recommended">Recommended</option>
        <option value="notrecommended">Not Recommended</option>
        <option value="rejected">Rejected</option>
        <option value="clear">Clear Filter</option>
      </select>

      <?php if (empty($umoor) || $umoor !== '12 Umoor Raza Applications'): ?>
        <?php if (empty($umoor) || stripos($umoor, 'Kaaraj') === false): ?>
        <select onchange="updateTable();" id="miqaat_filter" class="filter-select">
          <option value="" selected>All Miqaat Types</option>
          <option value="Shehrullah">Shehrullah</option>
          <option value="Ashara">Ashara</option>
          <option value="General">General</option>
          <option value="Ladies">Ladies</option>
        </select>
        <?php endif; ?>
      <?php else: ?>
        <?php $umoors=[]; foreach($razatype as $rt){ if(!empty($rt['umoor'])) $umoors[$rt['umoor']]=$rt['umoor']; } ?>
        <select onchange="updateTable();" id="umoor_filter" class="filter-select">
          <option value="" selected>All Umoor Types</option>
          <?php foreach($umoors as $u): ?>
            <option value="<?php echo htmlspecialchars($u,ENT_QUOTES,'UTF-8'); ?>"><?php echo htmlspecialchars($u,ENT_QUOTES,'UTF-8'); ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>

      <?php
        $ci=&get_instance(); $ci->load->model('HijriCalendar');
        $hijri_years=[]; $hijri_map=[];
        foreach($raza as $r){
          $d='';
          if(!empty($r['miqaat_details'])){$md=json_decode($r['miqaat_details'],true);if(is_array($md)&&!empty($md['date']))$d=substr($md['date'],0,10);}
          if(empty($d)&&!empty($r['razadata'])){$rd=json_decode($r['razadata'],true);if(is_array($rd)&&!empty($rd['date']))$d=substr($rd['date'],0,10);}
          if(!empty($d)){$parts=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d);if(!empty($parts['hijri_year']))$hijri_years[$parts['hijri_year']]=$parts['hijri_year'];}
          $hyear='';
          if(!empty($d)){$parts=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d);if(!empty($parts['hijri_year']))$hyear=$parts['hijri_year'];}
          $hijri_map[$r['id']]=$hyear;
        }
        krsort($hijri_years);
      ?>
      <select onchange="updateTable();" id="year_filter" class="filter-select">
        <option value="" selected>All Hijri Years</option>
        <?php foreach($hijri_years as $y): ?><option value="<?php echo $y; ?>"><?php echo $y; ?></option><?php endforeach; ?>
      </select>

      <select onchange="updateTable();" id="sort" class="filter-select">
        <option value="2" selected>Event Date (New→Old)</option>
        <option value="3">Event Date (Old→New)</option>
        <option value="0">Name (A–Z)</option>
        <option value="1">Name (Z–A)</option>
        <option value="4">Created (New→Old)</option>
        <option value="5">Created (Old→New)</option>
      </select>

      <button class="refresh-btn" onclick="window.location.reload();">
        <i class="fa fa-rotate-right"></i> Refresh
      </button>
    </div>
  </div>

  <!-- ── Table Card ── -->
  <div class="table-card">
    <div class="tc-header">
      <span class="tc-icon"><i class="fa fa-list-alt"></i></span>
      <span class="tc-title">Raza Applications</span>
    </div>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th class="sno" onclick="sortTable(0)">#<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
            <th onclick="sortTable(1)">Name<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
            <th onclick="sortTable(2)">Raza For<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
            <th onclick="sortTable(3)">Event Date<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
            <th>Chat</th>
            <th onclick="sortTable(5)">Status<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
            <th>Action</th>
            <th onclick="sortTable(7)">Created<span class="sort-icon"><i class="fas fa-sort"></i></span></th>
          </tr>
        </thead>
        <tbody id="datatable">
          <?php
          foreach($raza as $key=>$r){
            $hijri_year_attr=''; $d='';
            if(!empty($r['miqaat_details'])){$md=json_decode($r['miqaat_details'],true);if(is_array($md)&&!empty($md['date']))$d=substr($md['date'],0,10);}
            if(empty($d)&&!empty($r['razadata'])){
              $rd=json_decode($r['razadata'],true);
              if(is_array($rd)&&!empty($rd['date'])){
                $d=substr($rd['date'],0,10);
              } else {
                $rf = is_string($r['razafields']) ? json_decode($r['razafields'], true) : $r['razafields'];
                $fields = isset($rf['fields']) ? $rf['fields'] : $rf;
                if (!empty($fields) && is_array($fields)) {
                    foreach ($fields as $f) {
                        if (isset($f['type']) && $f['type'] === 'date' && isset($f['name'])) {
                            $key1 = str_replace(['/', '?'], '_', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                            $key2 = str_replace(['/', '?'], '-', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                            if (!empty($rd[$key1])) {
                                $d = substr($rd[$key1],0,10);
                                break;
                            } else if (!empty($rd[$key2])) {
                                $d = substr($rd[$key2],0,10);
                                break;
                            }
                        }
                    }
                }
              }
            }
            if(!empty($d)){$parts=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d);if(!empty($parts['hijri_year']))$hijri_year_attr=$parts['hijri_year'];}
            $is_fnn=false;
            if(!empty($r['miqaat_id'])&&!empty($r['miqaat_details'])){$miq=json_decode($r['miqaat_details'],true);if(is_array($miq)){$mAss=strtolower($miq['assigned_to']??'');if(strpos($mAss,'fnn')!==false||(strpos($mAss,'fala')!==false&&(strpos($mAss,'niyaz')!==false||strpos($mAss,'niaz')!==false)))$is_fnn=true;if(!$is_fnn&&!empty($miq['assign_type'])){$at=strtolower($miq['assign_type']);if(strpos($at,'fnn')!==false||(strpos($at,'fala')!==false&&(strpos($at,'niyaz')!==false||strpos($at,'niaz')!==false)))$is_fnn=true;}}}
            if(!$is_fnn&&isset($r['razaType'])){$rt=strtolower($r['razaType']);if(strpos($rt,'fnn')!==false||(strpos($rt,'fala')!==false&&(strpos($rt,'niyaz')!==false||strpos($rt,'niaz')!==false)))$is_fnn=true;}
            $displayName=$is_fnn?'Fala ni Niyaz':htmlspecialchars($r['user_name'],ENT_QUOTES,'UTF-8');
            $statusMap=[0=>['pending','Pending'],1=>['recommended','Recommended'],2=>['approved','Approved'],3=>['rejected','Rejected'],4=>['notrecommended','Not Recommended']];
            $sm=$statusMap[$r['status']]??['secondary','Unknown'];
            $coIcons=[0=>'<i class="fa-solid fa-clock" style="color:#ca8a04;"></i>',1=>'<i class="fa-solid fa-circle-check" style="color:#1a6645;"></i>',2=>'<i class="fa-solid fa-circle-xmark" style="color:#b91c1c;"></i>'];
            $coIcon=$coIcons[$r['coordinator-status']]??$coIcons[0];
            $jaIcon=$coIcons[$r['Janab-status']]??$coIcons[0];
          ?>
          <tr data-hijri-year="<?php echo $hijri_year_attr; ?>">
            <td class="sno"><?php echo $key+1; ?></td>
            <td><div class="rz-name"><?php echo $displayName; ?></div></td>
            <td>
              <?php
              if(!empty($r['miqaat_id'])&&!empty($r['miqaat_details'])){
                $miqaat=json_decode($r['miqaat_details'],true);
                if(is_array($miqaat)){
                  $mName=htmlspecialchars($miqaat['name']??'',ENT_QUOTES,'UTF-8');
                  $mType=htmlspecialchars($miqaat['type']??'',ENT_QUOTES,'UTF-8');
                  $mAss=htmlspecialchars($miqaat['assigned_to']??'',ENT_QUOTES,'UTF-8');
                  $metaParts=array_filter([$mType,$mAss]);
                  echo '<div class="rz-type">'.$mName.'</div>';
                  if(!empty($metaParts)) echo '<div class="rz-type-meta">'.implode(' · ',$metaParts).'</div>';
                }
              } else {
                echo '<div class="rz-type">'.htmlspecialchars($r['razaType'],ENT_QUOTES,'UTF-8').'</div>';
              }
              ?>
            </td>
            <td>
              <?php
              $greg_date='';
              if(!empty($r['miqaat_id'])&&!empty($r['miqaat_details'])){$mi=json_decode($r['miqaat_details'],true);if(!empty($mi['date'])){$greg_date=$mi['date'];echo '<div class="rz-date">'.date('D, d M',strtotime($greg_date)).'</div>';}}
              else{
                  $tmp=json_decode($r['razadata'],true);
                  if(!empty($tmp['date'])){
                      $greg_date=$tmp['date'];echo '<div class="rz-date">'.date('D, d M',strtotime($greg_date)).'</div>';
                  } else {
                      $rf = is_string($r['razafields']) ? json_decode($r['razafields'], true) : $r['razafields'];
                      $fields = isset($rf['fields']) ? $rf['fields'] : $rf;
                      if (!empty($fields) && is_array($fields)) {
                          foreach ($fields as $f) {
                              if (isset($f['type']) && $f['type'] === 'date' && isset($f['name'])) {
                                  $key1 = str_replace(['/', '?'], '_', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                                  $key2 = str_replace(['/', '?'], '-', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                                  if (!empty($tmp[$key1])) {
                                      $greg_date = $tmp[$key1];
                                      echo '<div class="rz-date">'.date('D, d M',strtotime($greg_date)).'</div>';
                                      break;
                                  } else if (!empty($tmp[$key2])) {
                                      $greg_date = $tmp[$key2];
                                      echo '<div class="rz-date">'.date('D, d M',strtotime($greg_date)).'</div>';
                                      break;
                                  }
                              }
                          }
                      }
                  }
              }
              if(!empty($greg_date)){$hijri=$ci->HijriCalendar->get_hijri_parts_by_greg_date(substr($greg_date,0,10));if(!empty($hijri['hijri_day'])&&!empty($hijri['hijri_month'])&&!empty($hijri['hijri_year'])){$hn=$ci->HijriCalendar->hijri_month_name($hijri['hijri_month']);echo '<div class="rz-hijri">'.$hijri['hijri_day'].' '.$hn.' '.$hijri['hijri_year'].'H</div>';}}
              ?>
            </td>
            <td>
              <?php $cc=!empty($r['chat_count'])?$r['chat_count']:''; ?>
              <a href="<?php echo base_url('Accounts/chat/').$r['id'].'/anjuman'; ?>" class="chat-btn">
                <i class="fa-regular fa-comment"></i> Chat
                <?php if($cc): ?><span class="chat-badge"><?php echo $cc; ?></span><?php endif; ?>
              </a>
            </td>
            <td>
              <span class="status-main <?php echo $sm[0]; ?>">
                <?php
                  $statusIcons=['pending'=>'fa-clock','recommended'=>'fa-thumbs-up','approved'=>'fa-circle-check','rejected'=>'fa-circle-xmark','notrecommended'=>'fa-thumbs-down'];
                  echo '<i class="fa-solid '.$statusIcons[$sm[0]].'"></i> '.$sm[1];
                ?>
              </span>
              <div class="status-sub">
                <span class="sub-row"><?php echo $coIcon; ?> Jamat</span>
                <span class="sub-row"><?php echo $jaIcon; ?> Amil Saheb</span>
              </div>
            </td>
            <td>
              <div class="action-wrap">
                <?php if($r['Janab-status']==0): ?>
                <div class="btn-group-row">
                  <button class="act-btn approve" title="Recommend" onclick="approve_raza(<?php echo $r['id']; ?>)"><i class="fa-solid fa-circle-check"></i></button>
                  <button class="act-btn reject"  title="Not Recommend" onclick="reject_raza(<?php echo $r['id']; ?>)"><i class="fa-solid fa-circle-xmark"></i></button>
                  <button class="act-btn del"     title="Delete" onclick="deleteRaza(<?php echo $r['id']; ?>)"><i class="fa-solid fa-trash"></i></button>
                </div>
                <?php endif; ?>
                <button class="act-btn view" onclick="show_raza(<?php echo $r['id']; ?>)"><i class="fa-regular fa-eye"></i> View</button>
              </div>
            </td>
            <td><div class="rz-created"><?php echo date('D, d M',strtotime($r['time-stamp'])); ?><br><span style="color:var(--text-3);font-size:.68rem;"><?php echo date('g:i a',strtotime($r['time-stamp'])); ?></span></div></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div id="rz-empty" class="empty-state" style="display:none;">
        <i class="fa fa-inbox"></i>
        <p>No requests match your filters.</p>
      </div>
    </div>
  </div>

</div><!-- /rz-wrap -->

<!-- ── Modal Overlay ── -->
<div id="product-overlay" class="modal-overlay" onclick="clearForm()"></div>

<!-- ── Approve Modal ── -->
<div id="approve-form" class="modal-card" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1010;">
  <div class="modal-hd">
    <h3 class="modal-title"><i class="fa-solid fa-thumbs-up" style="color:var(--green);margin-right:7px;"></i>Recommend Raza</h3>
    <button class="modal-close" onclick="clearForm()"><i class="fa fa-times"></i></button>
  </div>
  <div class="modal-body">
    <table id="details-table-approve" style="width:100%;margin-bottom:12px;"></table>
    <form id="approve">
      <div class="form-group">
        <label>Remark (optional)</label>
        <textarea name="remark" id="remark" rows="4"></textarea>
      </div>
    </form>
  </div>
  <div class="modal-ft">
    <button class="btn-outline-secondary" onclick="clearForm()">Cancel</button>
    <button class="btn-gold" onclick="$('#approve').submit()"><i class="fa-solid fa-check"></i> Recommend</button>
  </div>
</div>

<!-- ── Reject Modal ── -->
<div id="reject-form" class="modal-card" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1010;">
  <div class="modal-hd" style="background:var(--red-bg);border-bottom-color:#fca5a5;">
    <h3 class="modal-title"><i class="fa-solid fa-thumbs-down" style="color:var(--red);margin-right:7px;"></i>Not Recommend</h3>
    <button class="modal-close" onclick="clearForm()"><i class="fa fa-times"></i></button>
  </div>
  <div class="modal-body">
    <table id="details-table-reject" style="width:100%;margin-bottom:12px;"></table>
    <form id="reject">
      <div class="form-group">
        <label>Remark <span style="color:var(--red)">*</span></label>
        <textarea name="remark" required rows="4"></textarea>
      </div>
    </form>
  </div>
  <div class="modal-ft">
    <button class="btn-outline-secondary" onclick="clearForm()">Cancel</button>
    <button class="btn-danger" onclick="$('#reject').submit()"><i class="fa-solid fa-times-circle"></i> Not Recommend</button>
  </div>
</div>

<!-- ── View Modal ── -->
<div id="show-form" class="modal-card" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1010;">
  <div class="modal-hd">
    <h3 class="modal-title"><i class="fa-regular fa-eye" style="margin-right:7px;"></i>Raza Details</h3>
    <button class="modal-close" onclick="clearForm()"><i class="fa fa-times"></i></button>
  </div>
  <div class="modal-body">
    <table id="details-table-show" style="width:100%;"></table>
  </div>
  <!-- <div class="modal-ft">
    <button class="btn-outline-secondary" onclick="clearForm()"><i class="fa fa-times"></i> Close</button>
  </div> -->
</div>

<!-- ── Financials Modal ── -->
<div id="financials-modal" class="modal-card fin-modal-card" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:1020;">
  <div class="modal-hd" style="background:#fffbeb;border-bottom-color:#fcd34d;">
    <h3 class="modal-title"><i class="fa-solid fa-triangle-exclamation" style="color:#92400e;margin-right:7px;"></i>Pending Financial Dues</h3>
    <button class="modal-close" onclick="closeFinancialsModal()"><i class="fa fa-times"></i></button>
  </div>
  <div class="modal-body" id="financials-modal-content"></div>
</div>

</div><!-- /#rzApp -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var razas=[
<?php
foreach($raza as $r){
  $d='';
  if(!empty($r['miqaat_details'])){$md=json_decode($r['miqaat_details'],true);if(is_array($md)&&!empty($md['date']))$d=substr($md['date'],0,10);}
  if(empty($d)&&!empty($r['razadata'])){
      $rd=json_decode($r['razadata'],true);
      if(is_array($rd)&&!empty($rd['date'])){
          $d=substr($rd['date'],0,10);
      } else {
          $rf = is_string($r['razafields']) ? json_decode($r['razafields'], true) : $r['razafields'];
          $fields = isset($rf['fields']) ? $rf['fields'] : $rf;
          if (!empty($fields) && is_array($fields)) {
              foreach ($fields as $f) {
                  if (isset($f['type']) && $f['type'] === 'date' && isset($f['name'])) {
                      $key1 = str_replace(['/', '?'], '_', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                      $key2 = str_replace(['/', '?'], '-', str_replace(['(', ')'], '_', str_replace(' ', '-', strtolower($f['name']))));
                      if (!empty($rd[$key1])) {
                          $d = substr($rd[$key1],0,10);
                          break;
                      } else if (!empty($rd[$key2])) {
                          $d = substr($rd[$key2],0,10);
                          break;
                      }
                  }
              }
          }
      }
  }
  $hijri_parts=null;
  if(!empty($d)){$parts=$ci->HijriCalendar->get_hijri_parts_by_greg_date($d);if(!empty($parts['hijri_year'])&&!empty($parts['hijri_month'])&&!empty($parts['hijri_day']))$hijri_parts=['year'=>$parts['hijri_year'],'month'=>$parts['hijri_month'],'day'=>$parts['hijri_day']];}
  $r['hijri_parts']=$hijri_parts;
  echo json_encode($r,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT).",\n";
}
?>
];
var hijriMap=<?php echo json_encode($hijri_map??[],JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;
<?php
$ci=isset($ci)?$ci:get_instance();$ci->load->model('HijriCalendar');
$hijri_months=$ci->HijriCalendar->get_hijri_month();$hijri_months_js=[];
foreach($hijri_months as $m)$hijri_months_js[$m['id']]=$m['hijri_month'];
?>
window.hijriMonths=<?php echo json_encode($hijri_months_js,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>;

var _initialTbodyHTML=(document.getElementById('datatable')||{innerHTML:''}).innerHTML;
document.getElementById('sort').value=2;
updateTable();

/* ── Search ── */
document.getElementById('razaSearchInput').addEventListener('input',function(){
  var f=this.value.toLowerCase();
  var rows=document.querySelectorAll('#datatable tr');
  var vis=0;
  rows.forEach(function(tr){
    var txt=(tr.textContent||tr.innerText).toLowerCase();
    var show=txt.indexOf(f)>-1;
    tr.style.display=show?'':'none';
    if(show)vis++;
  });
  document.getElementById('rz-empty').style.display=vis?'none':'block';
});

/* ── Show/Approve/Reject ── */
function show_raza(id){
  var raza=razas.find(function(e){return e.id==id;});
  buildDetailTable(raza,'show');
  document.getElementById('show-form').style.display='block';
  document.getElementById('product-overlay').classList.add('active');
}
function approve_raza(id){
  var raza=razas.find(function(e){return e.id==id;});
  $('#approve').find('input[name="raza_id"]').parent().remove();
  $('#approve').find('input[name="its_id"]').parent().remove();
  buildDetailTable(raza,'approve');
  document.getElementById('approve-form').style.display='block';
  document.getElementById('product-overlay').classList.add('active');
  var inp=document.createElement('div');inp.innerHTML='<input type="hidden" name="raza_id" value="'+id+'">';
  document.getElementById('approve').appendChild(inp);
  try{var its=raza.user_id||raza.userId||raza.ITS_ID||raza.its_id||raza.its||null;if(its){var i2=document.createElement('div');i2.innerHTML='<input type="hidden" name="its_id" value="'+its+'">';document.getElementById('approve').appendChild(i2);}}catch(e){}
}
function reject_raza(id){
  var raza=razas.find(function(e){return e.id==id;});
  buildDetailTable(raza,'reject');
  document.getElementById('reject-form').style.display='block';
  document.getElementById('product-overlay').classList.add('active');
  var inp=document.createElement('div');inp.innerHTML='<input type="hidden" name="raza_id" value="'+id+'">';
  document.getElementById('reject').appendChild(inp);
}
function clearForm(){
  ['approve','reject'].forEach(function(id){var f=document.getElementById(id);if(f)f.reset();});
  ['approve-form','reject-form','show-form'].forEach(function(id){var el=document.getElementById(id);if(el)el.style.display='none';});
  document.getElementById('product-overlay').classList.remove('active');
}
function closeFinancialsModal(){
  document.getElementById('financials-modal').style.display='none';
  document.getElementById('financials-modal-content').innerHTML='';
}

/* ── Build detail table ── */
function buildDetailTable(raza,action){
  var table=document.getElementById('details-table-'+action);
  table.innerHTML='';
  var ul=document.createElement('ul');ul.className='detail-list';
  var rows=[];
  var rt_id=parseInt(raza.razaType_id||raza.raza_type_id);
  function parseMaybe(v){if(!v)return null;if(typeof v==='object')return v;try{return JSON.parse(v);}catch(e){return null;}}
  if(rt_id===2&&raza.miqaat_details){
    var mi=parseMaybe(raza.miqaat_details)||{};
    rows.push(['Miqaat Name',mi.name||'']);
    rows.push(['Miqaat Type',mi.type||'']);
    if(mi.date){var d=new Date(mi.date);rows.push(['Miqaat Date',d.getDate().toString().padStart(2,'0')+'-'+(d.getMonth()+1).toString().padStart(2,'0')+'-'+d.getFullYear()]);}
    rows.push(['Assigned To',mi.assigned_to||'']);
    rows.push(['Status',mi.status==1?'Active':'Inactive']);
    if(mi.assign_type==='Group'&&Array.isArray(mi.assignments)&&mi.assignments.length){
      rows.push(['Group Leader',(mi.group_leader_name||'')+' '+(mi.group_leader_surname||'')]);
      mi.assignments.forEach(function(m,i){rows.push(['Member '+(i+1),(m.member_first_name||'')+' '+(m.member_surname||'')]);});
    }
  } else {
    var data=parseMaybe(raza.razadata)||{};
    var rf=parseMaybe(raza.razafields)||{};
    var fields=rf.fields||[];
    var k=0;
    for(var key in data){
      var fname=fields[k]?fields[k].name:key;
      var fval=data[key];
      if(fields[k]&&fields[k].type==='select'){var opt=(fields[k].options||[]).find(function(o){return String(o.id)===String(fval);});fval=opt?opt.name:fval;}
      rows.push([fname,fval]);k++;
    }
  }
  rows.forEach(function(row){
    var li=document.createElement('li');li.className='detail-row';
    li.innerHTML='<div class="dr-k">'+row[0]+'</div><div class="dr-v">'+(row[1]||'<span style="color:var(--text-3)">—</span>')+'</div>';
    ul.appendChild(li);
  });
  table.appendChild(ul);
}

/* ── Financials modal ── */
function formatINR(n){return '₹'+(Math.round(n)).toLocaleString('en-IN');}
function renderFinancialsModal(data,raza_id){
  var c=document.getElementById('financials-modal-content');
  var rows=[
    ['FMB Takhmeen',data.fmb_due],
    ['Sabeel Takhmeen',data.sabeel_due],
    ['General Contributions',data.gc_due],
    ['Miqaat Invoices',data.miqaat_due],
    ['Corpus Fund',data.corpus_due],
    ['Ekram Fund',data.ekram_due||0]
  ];
  var html='<div class="fin-warning"><i class="fa-solid fa-triangle-exclamation"></i><span>This member has pending dues. Please review before proceeding.</span></div>';
  html+='<table class="fin-table"><thead><tr><th>Category</th><th style="text-align:right">Due Amount</th></tr></thead><tbody>';
  rows.forEach(function(r){html+='<tr><td>'+r[0]+'</td><td style="text-align:right" class="'+(r[1]>0?'fin-amt-due':'fin-amt-ok')+'">'+formatINR(r[1])+'</td></tr>';});
  html+='<tr class="fin-total-row"><th>Total Due</th><th style="text-align:right">'+formatINR(data.total_due)+'</th></tr>';
  html+='</tbody></table>';
  if(Array.isArray(data.miqaat_invoices)&&data.miqaat_invoices.length){
    html+='<h6 style="margin:12px 0 8px;font-size:.78rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.4px;">Miqaat / Member Invoices</h6>';
    html+='<div style="max-height:150px;overflow:auto;"><table class="fin-table"><thead><tr><th>Assigned To</th><th>Invoice</th><th style="text-align:right">Amt</th><th style="text-align:right">Paid</th><th style="text-align:right">Due</th></tr></thead><tbody>';
    data.miqaat_invoices.forEach(function(row){html+='<tr><td>'+(row.assigned_to||'')+'</td><td>'+(row.invoice||'')+'</td><td style="text-align:right">'+formatINR(row.amount)+'</td><td style="text-align:right">'+formatINR(row.paid)+'</td><td style="text-align:right" class="fin-amt-due">'+formatINR(row.due)+'</td></tr>';});
    html+='</tbody></table></div>';
  }
  html+='<div style="display:flex;justify-content:flex-end;gap:9px;margin-top:14px;">';
  html+='<button class="btn-outline-secondary" onclick="closeFinancialsModal()">Cancel</button>';
  html+='<button class="btn-gold" onclick="proceedFromFinancials('+raza_id+')"><i class="fa-solid fa-arrow-right"></i> Proceed Anyway</button>';
  html+='</div>';
  c.innerHTML=html;
  document.getElementById('financials-modal').style.display='block';
}
function proceedFromFinancials(raza_id){
  closeFinancialsModal();
  $('#approve').find('input[name="raza_id"]').parent().remove();
  var inp=document.createElement('div');inp.innerHTML='<input type="hidden" name="raza_id" value="'+raza_id+'">';
  document.getElementById('approve').appendChild(inp);
  submitApproveForm($('#approve'));
}

/* ── AJAX submissions ── */
$(document).ready(function(){
  $('#approve').submit(function(e){
    e.preventDefault();
    var its=$(this).find('input[name="its_id"]').val();
    var raza_id=$(this).find('input[name="raza_id"]').val();
    if(!its){submitApproveForm($(this));return;}
    fetch('<?php echo base_url("anjuman/member_financials_json"); ?>?its_id='+encodeURIComponent(its),{credentials:'same-origin'})
      .then(function(r){return r.json();})
      .then(function(data){if(!data||!data.success){submitApproveForm($('#approve'));return;}renderFinancialsModal(data,raza_id);})
      .catch(function(){submitApproveForm($('#approve'));});
  });
  window.submitApproveForm=function($form){
    $.ajax({type:'POST',url:'<?php echo base_url("admin/approveRaza"); ?>',data:$form.serialize(),success:function(){showToast('Raza Recommended successfully!');clearForm();window.location.reload();},error:function(){console.error('failed');}});
  };
  $('#reject').submit(function(e){
    e.preventDefault();
    $.ajax({type:'POST',url:'<?php echo base_url("admin/rejectRaza"); ?>',data:$(this).serialize(),success:function(){showToast('Raza marked as Not Recommended.');clearForm();window.location.reload();},error:function(){console.error('failed');}});
  });
});

function showToast(msg){
  var t=document.getElementById('rz-toast');
  document.getElementById('rz-toast-msg').textContent=msg;
  t.style.display='flex';
  setTimeout(function(){t.style.display='none';},2500);
}
function deleteRaza(id){
  if(confirm('Delete this Raza request?')) window.location.href='<?php echo base_url(); ?>anjuman/DeleteRaza/'+id;
}

/* ── Table update (filter/sort) ── */
var _initialRazaTbodyHTML=document.getElementById('datatable').innerHTML;
function updateTable(){
  var filter=document.getElementById('filter').value;
  var sort=document.getElementById('sort').value;
  var miqaatEl=document.getElementById('miqaat_filter');
  var miqaatF=miqaatEl?miqaatEl.value:'';
  var umoorEl=document.getElementById('umoor_filter');
  var umoorF=umoorEl?umoorEl.value:'';
  var yearEl=document.getElementById('year_filter');
  var yearF=yearEl?yearEl.value:'';
  function parseMaybe(v){if(!v)return null;if(typeof v==='object')return v;try{return JSON.parse(v);}catch(e){return null;}}
  var list=razas.slice();
  if(miqaatF) list=list.filter(function(r){var m=parseMaybe(r.miqaat_details)||{};return (m.type||r.razaType||'').toLowerCase()===miqaatF.toLowerCase();});
  if(yearF) list=list.filter(function(r){return (hijriMap&&hijriMap[r.id]?hijriMap[r.id].toString():'')===yearF;});
  if(umoorF) list=list.filter(function(r){return (r.umoor||'').toLowerCase()===umoorF.toLowerCase();});
  if(filter==='clear'){window.location.reload();return;}
  var statusMap={approved:2,recommended:1,pending:0,rejected:3,notrecommended:4};
  if(filter!==''&&statusMap[filter]!==undefined) list=list.filter(function(r){return r.status==statusMap[filter];});
  function getEvDate(r){
      var m=parseMaybe(r.miqaat_details)||{};
      if(m.date)return new Date(m.date);
      var d=parseMaybe(r.razadata)||{};
      if(d.date)return new Date(d.date);
      var rf=parseMaybe(r.razafields)||{};
      var fields=rf.fields||rf;
      if (Array.isArray(fields)) {
          for (var j=0; j<fields.length; j++) {
              var f = fields[j];
              if (f.type === 'date' && f.name) {
                  var key = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                  if (d[key]) return new Date(d[key]);
              }
          }
      } else if (fields) {
          for (var k in fields) {
              if (fields[k].type === 'date' && fields[k].name) {
                  var key = fields[k].name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                  if (d[key]) return new Date(d[key]);
              }
          }
      }
      return new Date(0);
  }
  list.sort(function(a,b){
    var s=parseInt(sort);
    if(s===0)return a.user_name.localeCompare(b.user_name);
    if(s===1)return b.user_name.localeCompare(a.user_name);
    if(s===2)return getEvDate(b)-getEvDate(a);
    if(s===3)return getEvDate(a)-getEvDate(b);
    if(s===4)return new Date(b['time-stamp'])-new Date(a['time-stamp']);
    if(s===5)return new Date(a['time-stamp'])-new Date(b['time-stamp']);
    return 0;
  });
  var tbody=document.getElementById('datatable');
  if(!list.length){tbody.innerHTML='';document.getElementById('rz-empty').style.display='block';document.getElementById('rzCount').textContent='0';return;}
  document.getElementById('rz-empty').style.display='none';
  document.getElementById('rzCount').textContent=list.length;
  try{
    var html='';
    list.forEach(function(r,i){
      var chatURL='<?php echo base_url("Accounts/chat/"); ?>'+r.id+'/anjuman';
      var cc=r.chat_count&&r.chat_count>0?r.chat_count:'';
      var chatBadge=cc?'<span class="chat-badge">'+cc+'</span>':'';
      html+='<tr>';
      html+='<td class="sno">'+(i+1)+'</td>';
      html+='<td><div class="rz-name">'+(r.user_name||'')+'</div></td>';
      html+='<td>'+formateRazaTypeJS(r)+'</td>';
      html+='<td>'+formatEventDateJS(r)+'</td>';
      html+='<td><a href="'+chatURL+'" class="chat-btn"><i class="fa-regular fa-comment"></i> Chat '+chatBadge+'</a></td>';
      html+='<td>'+getStatusHTML(r)+'</td>';
      html+='<td>'+getActionHTML(r)+'</td>';
      html+='<td><div class="rz-created">'+formatDateShort(r['time-stamp'])+'</div></td>';
      html+='</tr>';
    });
    tbody.innerHTML=html;
  }catch(e){console.error(e);tbody.innerHTML=_initialRazaTbodyHTML;}
}

function formateRazaTypeJS(r){
  function parseMaybe(v){if(!v)return null;if(typeof v==='object')return v;try{return JSON.parse(v);}catch(e){return null;}}
  if(r.miqaat_id&&r.miqaat_details){var m=parseMaybe(r.miqaat_details)||{};var p=[m.type,m.assigned_to].filter(Boolean);return '<div class="rz-type">'+(m.name||'')+'</div>'+(p.length?'<div class="rz-type-meta">'+p.join(' · ')+'</div>':'');}
  return '<div class="rz-type">'+(r.umoor?r.umoor+' · ':'')+(r.razaType||'')+'</div>';
}
function formatEventDateJS(r){
  function parseMaybe(v){if(!v)return null;if(typeof v==='object')return v;try{return JSON.parse(v);}catch(e){return null;}}
  var gd='';
  if(r.miqaat_id&&r.miqaat_details){var m=parseMaybe(r.miqaat_details)||{};gd=m.date||'';}
  else{
      var d=parseMaybe(r.razadata)||{};
      gd=d.date||'';
      if(!gd) {
          var rf=parseMaybe(r.razafields)||{};
          var fields=rf.fields||rf;
          if (Array.isArray(fields)) {
              for (var j=0; j<fields.length; j++) {
                  var f = fields[j];
                  if (f.type === 'date' && f.name) {
                      let key1 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                      let key2 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                      if (d[key1]) { gd = d[key1]; break; }
                      if (d[key2]) { gd = d[key2]; break; }
                  }
              }
          } else if (fields) {
              for (var k in fields) {
                  if (fields[k].type === 'date' && fields[k].name) {
                      let key1 = fields[k].name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                      let key2 = fields[k].name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                      if (d[key1]) { gd = d[key1]; break; }
                      if (d[key2]) { gd = d[key2]; break; }
                  }
              }
          }
      }
  }
  if(!gd)return '';
  var opts={weekday:'short',month:'short',day:'numeric'};
  var ds=new Date(gd).toLocaleDateString('en-US',opts);
  var hijriStr='';
  if(r.hijri_parts&&r.hijri_parts.year){var mn=(window.hijriMonths&&window.hijriMonths[r.hijri_parts.month])||('M'+r.hijri_parts.month);hijriStr='<div class="rz-hijri">'+r.hijri_parts.day+' '+mn+' '+r.hijri_parts.year+'H</div>';}
  return '<div class="rz-date">'+ds+'</div>'+hijriStr;
}
function formatDateShort(s){
  var d=new Date(s);
  return d.toLocaleDateString('en-US',{weekday:'short',day:'numeric',month:'short'})+'<br><span style="color:var(--text-3);font-size:.68rem;">'+d.toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit',hour12:true})+'</span>';
}
function getStatusHTML(r){
  var map={0:['pending','fa-clock','Pending'],1:['recommended','fa-thumbs-up','Recommended'],2:['approved','fa-circle-check','Approved'],3:['rejected','fa-circle-xmark','Rejected'],4:['notrecommended','fa-thumbs-down','Not Recommended']};
  var sm=map[r.status]||['secondary','fa-question','Unknown'];
  var coIcons={0:'<i class="fa-solid fa-clock" style="color:#ca8a04;"></i>',1:'<i class="fa-solid fa-circle-check" style="color:#1a6645;"></i>',2:'<i class="fa-solid fa-circle-xmark" style="color:#b91c1c;"></i>'};
  return '<span class="status-main '+sm[0]+'"><i class="fa-solid '+sm[1]+'"></i> '+sm[2]+'</span>'
    +'<div class="status-sub"><span class="sub-row">'+(coIcons[r['coordinator-status']]||coIcons[0])+' Jamat</span><span class="sub-row">'+(coIcons[r['Janab-status']]||coIcons[0])+' Amil Saheb</span></div>';
}
function getActionHTML(r){
  var btns='';
  if(r['Janab-status']==0){
    btns='<div class="btn-group-row"><button class="act-btn approve" title="Recommend" onclick="approve_raza('+r.id+')"><i class="fa-solid fa-circle-check"></i></button><button class="act-btn reject" title="Not Recommend" onclick="reject_raza('+r.id+')"><i class="fa-solid fa-circle-xmark"></i></button><button class="act-btn del" title="Delete" onclick="deleteRaza('+r.id+')"><i class="fa-solid fa-trash"></i></button></div>';
  }
  return '<div class="action-wrap">'+btns+'<button class="act-btn view" onclick="show_raza('+r.id+')"><i class="fa-regular fa-eye"></i> View</button></div>';
}

/* ── Column sort ── */
function sortTable(n){
  var table=document.querySelector('#rzApp table');
  var switching=true,dir='asc',switchcount=0;
  while(switching){
    switching=false;var rows=table.rows;
    for(var i=1;i<rows.length-1;i++){
      var x=rows[i].getElementsByTagName('TD')[n];
      var y=rows[i+1].getElementsByTagName('TD')[n];
      if(!x||!y)continue;
      var xv=(x.textContent||x.innerText).toLowerCase();
      var yv=(y.textContent||y.innerText).toLowerCase();
      var shouldSwitch=(dir==='asc'&&xv>yv)||(dir==='desc'&&xv<yv);
      if(shouldSwitch){rows[i].parentNode.insertBefore(rows[i+1],rows[i]);switching=true;switchcount++;break;}
    }
    if(!switching&&switchcount===0&&dir==='asc'){dir='desc';switching=true;}
  }
}
</script>