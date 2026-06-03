<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
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
  --radius:       16px;
  --radius-sm:    10px;
}
#rdApp,#rdApp *,#rdApp *::before,#rdApp *::after{box-sizing:border-box;}
#rdApp{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--bg);min-height:100vh;padding-top:57px;}
#rdApp .rd-wrap{padding:12px 14px 48px;}
@media(min-width:1400px){#rdApp .rd-wrap{padding:14px 20px 48px;}}

/* ── Compact header ── */
#rdApp .rd-header{
  background:linear-gradient(135deg,#78520a 0%,#b8860b 50%,#c9a227 100%);
  border-radius:14px;padding:11px 18px;margin-bottom:16px;
  display:flex;align-items:center;justify-content:space-between;gap:12px;
  position:relative;overflow:hidden;flex-wrap:wrap;
}
#rdApp .rd-header::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;pointer-events:none;}
#rdApp .rd-header::after{content:'';position:absolute;right:-30px;top:-30px;width:140px;height:140px;background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);pointer-events:none;}
#rdApp .hdr-left{display:flex;align-items:center;gap:10px;position:relative;z-index:1;min-width:0;flex:1;}
#rdApp .hdr-back{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;text-decoration:none;transition:background .15s;flex-shrink:0;}
#rdApp .hdr-back:hover{background:rgba(255,255,255,.28);color:#fff;text-decoration:none;}
#rdApp .hdr-icon{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:.9rem;color:#fff;flex-shrink:0;}
#rdApp .hdr-eyebrow{font-size:.62rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.6);line-height:1;}
#rdApp .hdr-title{font-family:'Literata',Georgia,serif;font-size:1.05rem;font-weight:600;color:#fff;line-height:1.2;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:380px;}
#rdApp .hdr-right{display:flex;align-items:center;gap:8px;position:relative;z-index:1;flex-shrink:0;flex-wrap:wrap;}
#rdApp .hdr-badge{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:9px;padding:5px 12px;display:flex;align-items:center;gap:6px;font-size:.74rem;font-weight:700;color:#fff;white-space:nowrap;}
#rdApp .hdr-badge .bv{font-size:.95rem;font-weight:800;}

/* ── Miqaat info card ── */
#rdApp .miqaat-info-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);margin-bottom:16px;overflow:hidden;}
#rdApp .mic-header{padding:12px 18px;background:var(--gold-muted);border-bottom:1.5px solid var(--gold-border);display:flex;align-items:center;gap:8px;}
#rdApp .mic-icon{width:28px;height:28px;border-radius:7px;background:rgba(184,134,11,.15);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:.78rem;}
#rdApp .mic-title{font-size:.82rem;font-weight:800;color:var(--gold);text-transform:uppercase;letter-spacing:.5px;}
#rdApp .mic-body{display:flex;flex-wrap:wrap;gap:0;}
#rdApp .mic-cell{flex:1 1 150px;padding:12px 18px;border-right:1px solid var(--border-light);}
#rdApp .mic-cell:last-child{border-right:none;}
#rdApp .mic-cell-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:var(--text-3);margin-bottom:4px;}
#rdApp .mic-cell-val{font-size:.9rem;font-weight:700;color:var(--text-1);}

/* ── Overall stats strip ── */
#rdApp .stats-strip{display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;margin-bottom:16px;}
#rdApp .stat-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius-sm);padding:12px 14px;box-shadow:var(--shadow-sm);text-align:center;transition:transform .15s,box-shadow .15s;}
#rdApp .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow);}
#rdApp .stat-card .sv{font-size:1.5rem;font-weight:800;line-height:1;display:block;}
#rdApp .stat-card .sl{font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--text-3);margin-top:4px;display:block;}
#rdApp .stat-card.c-green .sv{color:var(--green);}
#rdApp .stat-card.c-red   .sv{color:var(--red);}
#rdApp .stat-card.c-gold  .sv{color:var(--gold);}
#rdApp .stat-card.c-blue  .sv{color:var(--blue);}
#rdApp .stat-bar-wrap{background:var(--border);border-radius:4px;height:5px;margin-top:6px;overflow:hidden;}
#rdApp .stat-bar{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--green),#34d399);transition:width .5s;}

/* ══════════════════════════════════════════
   SECTOR STATS SECTION
══════════════════════════════════════════ */
#rdApp .sector-section{margin-bottom:16px;}
#rdApp .sector-section-hd{
  display:flex;align-items:center;gap:8px;
  padding:11px 16px;background:var(--surface);
  border:1.5px solid var(--border);border-radius:var(--radius) var(--radius) 0 0;
  border-bottom:1.5px solid var(--border-light);
}
#rdApp .sector-section-hd .ss-icon{width:26px;height:26px;border-radius:7px;background:var(--gold-muted);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:.74rem;}
#rdApp .sector-section-hd .ss-title{font-size:.82rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;flex:1;}
#rdApp .sector-section-hd .ss-hint{font-size:.7rem;color:var(--text-3);font-weight:600;}
#rdApp .sector-section-body{
  background:var(--surface);border:1.5px solid var(--border);border-top:none;
  border-radius:0 0 var(--radius) var(--radius);padding:14px;
}

/* Sector cards grid */
#rdApp .sector-cards-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
  gap:12px;
}
@media(max-width:600px){#rdApp .sector-cards-grid{grid-template-columns:1fr;}}

/* Individual sector card */
#rdApp .sector-card{
  background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius-sm);
  overflow:hidden;box-shadow:var(--shadow-sm);
  cursor:pointer;transition:border-color .15s,box-shadow .15s,transform .12s;
  position:relative;
}
#rdApp .sector-card::before{
  content:'';position:absolute;left:0;top:0;bottom:0;width:4px;
  background:linear-gradient(180deg,var(--gold),var(--gold-light));
  transition:width .15s;
}
#rdApp .sector-card:hover{border-color:var(--gold);box-shadow:var(--shadow);transform:translateY(-2px);}
#rdApp .sector-card:hover::before{width:5px;}
#rdApp .sector-card.active{border-color:var(--gold);background:var(--gold-muted);box-shadow:var(--shadow);}
#rdApp .sector-card.active::before{background:linear-gradient(180deg,var(--gold),#78520a);width:5px;}

/* Sector card header */
#rdApp .sc-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 14px 8px 18px;
}
#rdApp .sc-name{font-size:.88rem;font-weight:800;color:var(--text-1);}
#rdApp .sector-card.active .sc-name{color:var(--gold);}
#rdApp .sc-totals{display:flex;gap:8px;align-items:center;}
#rdApp .sc-total-pill{
  display:inline-flex;align-items:center;gap:4px;
  padding:3px 9px;border-radius:20px;font-size:.7rem;font-weight:700;
}
#rdApp .sc-total-pill.yes{background:var(--green-bg);color:var(--green);border:1px solid #86efac;}
#rdApp .sc-total-pill.no {background:var(--red-bg);  color:var(--red);  border:1px solid #fca5a5;}
#rdApp .sc-total-pill.total{background:var(--gold-muted);color:var(--gold);border:1px solid var(--gold-border);}

/* Sector progress bar */
#rdApp .sc-progress{padding:0 14px 0 18px;margin-bottom:6px;}
#rdApp .sc-progress-track{background:var(--border);border-radius:4px;height:5px;overflow:hidden;}
#rdApp .sc-progress-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--green),#34d399);transition:width .5s;}
#rdApp .sc-pct{font-size:.68rem;font-weight:700;color:var(--text-3);margin-top:3px;}

/* Sub-sector list inside card */
#rdApp .sc-subsectors{
  padding:0 14px 10px 18px;
  display:flex;flex-direction:column;gap:4px;
}
#rdApp .ss-row{
  display:flex;align-items:center;gap:8px;
  padding:5px 8px;border-radius:7px;
  cursor:pointer;transition:background .12s;
  border:1px solid transparent;
}
#rdApp .ss-row:hover{background:var(--surface-2);border-color:var(--border-light);}
#rdApp .ss-row.active{background:var(--gold-muted);border-color:var(--gold-border);}
#rdApp .ss-dot{width:8px;height:8px;border-radius:50%;background:var(--border);flex-shrink:0;}
#rdApp .ss-dot.has-rsvp{background:var(--green);}
#rdApp .ss-label{font-size:.78rem;font-weight:700;color:var(--text-2);flex:1;}
#rdApp .ss-row.active .ss-label{color:var(--gold);}
#rdApp .ss-counts{display:flex;gap:5px;align-items:center;}
#rdApp .ss-yes{font-size:.72rem;font-weight:800;color:var(--green);}
#rdApp .ss-slash{font-size:.68rem;color:var(--text-3);}
#rdApp .ss-tot{font-size:.72rem;font-weight:600;color:var(--text-3);}
#rdApp .ss-bar-wrap{width:50px;background:var(--border);border-radius:3px;height:4px;overflow:hidden;flex-shrink:0;}
#rdApp .ss-bar{height:100%;border-radius:3px;background:linear-gradient(90deg,var(--green),#34d399);}

/* Active filter indicator */
#rdApp .active-filter-strip{
  display:none;align-items:center;gap:8px;
  padding:8px 14px;background:var(--gold-muted);
  border:1.5px solid var(--gold-border);border-radius:var(--radius-sm);
  margin-bottom:12px;font-size:.8rem;font-weight:700;color:var(--gold);
}
#rdApp .active-filter-strip.visible{display:flex;}
#rdApp .afs-clear{
  margin-left:auto;display:inline-flex;align-items:center;gap:5px;
  padding:4px 12px;border-radius:8px;border:1.5px solid var(--gold-border);
  background:var(--surface);color:var(--gold);font-size:.74rem;font-weight:700;
  cursor:pointer;transition:background .12s;
}
#rdApp .afs-clear:hover{background:var(--surface-2);}

/* ── Table toolbar ── */
#rdApp .table-toolbar{
  background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);
  padding:12px 16px;margin-bottom:14px;box-shadow:var(--shadow-sm);
  display:flex;flex-wrap:wrap;gap:10px;align-items:center;
}
#rdApp .tb-search-wrap{position:relative;flex:1 1 200px;}
#rdApp .tb-search-wrap .fa-search{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:.78rem;pointer-events:none;}
#rdApp #tb-search{width:100%;height:36px;padding:0 32px 0 32px;border:1.5px solid var(--border);border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;color:var(--text-1);background:var(--surface-2);outline:none;transition:border-color .15s,box-shadow .15s;}
#rdApp #tb-search:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);}
#rdApp .tb-clear{position:absolute;right:9px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-3);cursor:pointer;font-size:1rem;line-height:1;padding:0;}
#rdApp .tb-clear:hover{color:var(--red);}
#rdApp .tb-select{height:36px;padding:0 10px;border:1.5px solid var(--border);border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;color:var(--text-1);background:var(--surface-2);outline:none;transition:border-color .15s;flex:1 1 120px;min-width:110px;}
#rdApp .tb-select:focus{border-color:var(--gold);}
#rdApp .tb-count{font-size:.76rem;font-weight:700;color:var(--text-3);white-space:nowrap;padding:0 4px;}
#rdApp .tb-count span{background:var(--gold-muted);color:var(--gold);padding:2px 9px;border-radius:12px;font-weight:800;}

/* ── Table card ── */
#rdApp .table-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;}
#rdApp .table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
#rdApp .table-inner{max-height:560px;overflow:auto;}
#rdApp table{width:100%;border-collapse:collapse;font-size:.83rem;}
#rdApp thead th{position:sticky;top:0;z-index:5;padding:10px 13px;font-size:.7rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.4px;background:var(--gold-muted);border-bottom:2px solid var(--gold-border);white-space:nowrap;}
#rdApp tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s;}
#rdApp tbody tr:last-child{border-bottom:none;}
#rdApp tbody tr:hover{background:rgba(184,134,11,.04);}
#rdApp tbody tr.row-yes{background:rgba(26,102,69,.05);}
#rdApp tbody tr.row-yes:hover{background:rgba(26,102,69,.1);}
#rdApp td{padding:10px 13px;vertical-align:middle;color:var(--text-1);}
#rdApp td.td-sno{color:var(--text-3);font-size:.75rem;font-weight:700;width:38px;}
#rdApp .member-av{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#c9a227);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:.72rem;flex-shrink:0;}
#rdApp .member-av.f{background:linear-gradient(135deg,#b45309,#f59e0b);}
#rdApp .member-cell{display:flex;align-items:center;gap:9px;}
#rdApp .member-name{font-weight:700;font-size:.84rem;color:var(--text-1);}
#rdApp .member-its{font-size:.7rem;color:var(--text-3);}
#rdApp .rsvp-yes{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--green-bg);color:var(--green);border:1px solid #86efac;}
#rdApp .rsvp-no {display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--red-bg); color:var(--red);  border:1px solid #fca5a5;}
#rdApp .sector-tag{display:inline-block;padding:2px 8px;border-radius:20px;font-size:.68rem;font-weight:700;background:var(--surface-2);color:var(--text-2);border:1px solid var(--border-light);}
#rdApp .comment-text{font-size:.78rem;color:var(--text-2);font-style:italic;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
#rdApp .no-comment{color:var(--text-3);font-size:.75rem;}

/* ── Empty state ── */
#rdApp .empty-state{padding:48px 20px;text-align:center;color:var(--text-3);}
#rdApp .empty-state i{font-size:2rem;margin-bottom:10px;display:block;color:var(--gold-light);}
#rdApp .empty-state p{font-size:.9rem;margin:0;}

/* ── Responsive ── */
@media(max-width:768px){
  #rdApp .stats-strip{grid-template-columns:repeat(2,1fr);}
  #rdApp .mic-cell{flex:1 1 100%;border-right:none;}
  #rdApp thead th{font-size:.65rem;padding:8px 9px;}
  #rdApp td{padding:8px 9px;font-size:.78rem;}
  #rdApp .table-toolbar{flex-direction:column;}
  #rdApp .tb-search-wrap,#rdApp .tb-select{width:100%;flex:none;}
  #rdApp .sector-cards-grid{grid-template-columns:1fr 1fr;}
}
@media(max-width:480px){
  #rdApp .rd-header{flex-direction:column;align-items:flex-start;gap:6px;}
  #rdApp .hdr-right{flex-wrap:wrap;}
  #rdApp .hdr-title{max-width:100%;white-space:normal;}
  #rdApp .stats-strip{grid-template-columns:repeat(2,1fr);}
  #rdApp .sector-cards-grid{grid-template-columns:1fr;}
}
</style>

<?php
/* ── Derive rows ── */
$rows       = [];
$list_label = 'HOF-level';
if (!empty($rsvp_member_list) && is_array($rsvp_member_list)) {
  $rows = $rsvp_member_list; $list_label = 'Member-level';
} elseif (!empty($rsvp) && is_array($rsvp)) {
  $rows = $rsvp;
}
if (!empty($rows)) {
  $rows = array_values(array_filter($rows, function($m){
    $sec  = trim((string)($m['sector']??$m['Sector']??''));
    $sub  = trim((string)($m['sub_sector']??$m['Sub_Sector']??$m['SubSector']??''));
    $iVal = null;
    foreach(['Inactive_Status','inactive_status','InactiveStatus'] as $k){ if(array_key_exists($k,$m)){ $iVal=$m[$k]; break; } }
    return $sec!==''&&$sub!==''&&$iVal===null;
  }));
}

$yes_count  = 0; $no_count = 0;
foreach($rows as $m){ if(isset($m['rsvp_status'])&&$m['rsvp_status']==1) $yes_count++; else $no_count++; }
$total_rows = count($rows);
$pct        = $total_rows > 0 ? round($yes_count/$total_rows*100) : 0;

/* ── Build sector → sub_sector stats ── */
$sector_stats = []; // ['SectorName' => ['total'=>n, 'yes'=>n, 'subs'=>['SubName'=>['total'=>n,'yes'=>n]]]]
foreach($rows as $m){
  $sec = trim($m['sector']??$m['Sector']??'');
  $sub = trim($m['sub_sector']??$m['Sub_Sector']??$m['SubSector']??'');
  $isY = isset($m['rsvp_status'])&&$m['rsvp_status']==1;
  if($sec==='') continue;
  if(!isset($sector_stats[$sec])) $sector_stats[$sec]=['total'=>0,'yes'=>0,'subs'=>[]];
  $sector_stats[$sec]['total']++;
  if($isY) $sector_stats[$sec]['yes']++;
  if($sub!==''){
    if(!isset($sector_stats[$sec]['subs'][$sub])) $sector_stats[$sec]['subs'][$sub]=['total'=>0,'yes'=>0];
    $sector_stats[$sec]['subs'][$sub]['total']++;
    if($isY) $sector_stats[$sec]['subs'][$sub]['yes']++;
  }
}
ksort($sector_stats);
foreach($sector_stats as &$sc){ ksort($sc['subs']); }
unset($sc);

/* Miqaat info */
$miqaatName = htmlspecialchars($miqaat['name'] ?? $miqaat['miqaat_name'] ?? '');
$miqaatDate = '';
if (!empty($miqaat['date']))            $miqaatDate = date('d M Y', strtotime($miqaat['date']));
elseif (!empty($miqaat['miqaat_date'])) $miqaatDate = date('d M Y', strtotime($miqaat['miqaat_date']));
$miqaatType = htmlspecialchars($miqaat['type'] ?? $miqaat['miqaat_type'] ?? '');
$hijriStr = '-';
if (!empty($hijri_date)) {
  $hp = explode('-', $hijri_date['hijri_date']);
  $hijriStr = $hp[0].' '.($hijri_date['hijri_month_name']??'').' '.(isset($hp[2])?$hp[2]:'');
}
function rd_initials($n){ if(!$n)return'?'; $p=preg_split('/\s+/',trim($n)); return count($p)===1?strtoupper(substr($p[0],0,1)):strtoupper(substr($p[0],0,1).substr($p[count($p)-1],0,1)); }
?>

<div id="rdApp">
<div class="rd-wrap">

  <!-- ── Compact Header ── -->
  <div class="rd-header">
    <div class="hdr-left">
      <a href="<?php echo base_url('common/rsvp_list?from='.$active_controller); ?>" class="hdr-back"><i class="fas fa-arrow-left"></i></a>
      <div class="hdr-icon"><i class="fa-solid fa-calendar-check"></i></div>
      <div>
        <div class="hdr-eyebrow">RSVP Details</div>
        <h1 class="hdr-title"><?php echo $miqaatName; ?></h1>
      </div>
    </div>
    <div class="hdr-right">
      <div class="hdr-badge"><i class="fa fa-check-circle" style="color:#86efac;"></i><span class="bv"><?php echo $yes_count; ?></span> Yes</div>
      <div class="hdr-badge"><i class="fa fa-users"></i><span class="bv"><?php echo $total_rows; ?></span> Total</div>
    </div>
  </div>

  <!-- ── Miqaat Info ── -->
  <div class="miqaat-info-card">
    <div class="mic-header"><span class="mic-icon"><i class="fa fa-calendar-alt"></i></span><span class="mic-title">Miqaat Information</span></div>
    <div class="mic-body">
      <div class="mic-cell"><div class="mic-cell-lbl">Miqaat Name</div><div class="mic-cell-val"><?php echo $miqaatName; ?></div></div>
      <?php if($miqaatType): ?><div class="mic-cell"><div class="mic-cell-lbl">Type</div><div class="mic-cell-val"><?php echo $miqaatType; ?></div></div><?php endif; ?>
      <div class="mic-cell"><div class="mic-cell-lbl">Gregorian Date</div><div class="mic-cell-val"><?php echo $miqaatDate?:'—'; ?></div></div>
      <div class="mic-cell"><div class="mic-cell-lbl">Hijri Date</div><div class="mic-cell-val"><?php echo htmlspecialchars($hijriStr); ?></div></div>
    </div>
  </div>

  <!-- ── Overall stats strip ── -->
  <div class="stats-strip">
    <div class="stat-card c-gold"><span class="sv"><?php echo $total_rows; ?></span><span class="sl">Total Members</span></div>
    <div class="stat-card c-green">
      <span class="sv"><?php echo $yes_count; ?></span><span class="sl">RSVP Yes</span>
      <div class="stat-bar-wrap"><div class="stat-bar" style="width:<?php echo $pct; ?>%;"></div></div>
    </div>
    <div class="stat-card c-red"><span class="sv"><?php echo $no_count; ?></span><span class="sl">RSVP No</span></div>
    <div class="stat-card c-blue"><span class="sv"><?php echo $pct; ?>%</span><span class="sl">Response Rate</span></div>
  </div>

  <?php if(!empty($sector_stats)): ?>
  <!-- ══════════════════════════════════════
       SECTOR STATS (filter cards)
  ══════════════════════════════════════ -->
  <div class="sector-section">
    <div class="sector-section-hd">
      <span class="ss-icon"><i class="fa fa-map-marker-alt"></i></span>
      <span class="ss-title">Sector-wise RSVP</span>
      <span class="ss-hint">Click a sector or sub-sector to filter the table</span>
    </div>
    <div class="sector-section-body">
      <div class="sector-cards-grid" id="sector-cards-grid">
        <?php foreach($sector_stats as $secName => $sc):
          $secPct = $sc['total'] > 0 ? round($sc['yes']/$sc['total']*100) : 0;
        ?>
        <div class="sector-card"
             data-sector="<?php echo htmlspecialchars(strtolower($secName)); ?>"
             data-subsector=""
             onclick="handleSectorClick(this)">
          <div class="sc-head">
            <span class="sc-name"><i class="fa fa-map-marker" style="margin-right:5px;font-size:.75rem;"></i><?php echo htmlspecialchars($secName); ?></span>
            <div class="sc-totals">
              <span class="sc-total-pill yes"><i class="fa fa-check"></i><?php echo $sc['yes']; ?></span>
              <span class="sc-total-pill total"><?php echo $sc['total']; ?></span>
            </div>
          </div>
          <div class="sc-progress">
            <div class="sc-progress-track">
              <div class="sc-progress-fill" style="width:<?php echo $secPct; ?>%;"></div>
            </div>
            <div class="sc-pct"><?php echo $secPct; ?>% responded</div>
          </div>
          <?php if(!empty($sc['subs'])): ?>
          <div class="sc-subsectors">
            <?php foreach($sc['subs'] as $subName => $sub):
              $subPct = $sub['total'] > 0 ? round($sub['yes']/$sub['total']*100) : 0;
            ?>
            <div class="ss-row"
                 data-sector="<?php echo htmlspecialchars(strtolower($secName)); ?>"
                 data-subsector="<?php echo htmlspecialchars(strtolower($subName)); ?>"
                 onclick="handleSubSectorClick(event, this)">
              <span class="ss-dot <?php echo $sub['yes']>0?'has-rsvp':''; ?>"></span>
              <span class="ss-label"><?php echo htmlspecialchars($subName); ?></span>
              <span class="ss-counts">
                <span class="ss-yes"><?php echo $sub['yes']; ?></span>
                <span class="ss-slash">/</span>
                <span class="ss-tot"><?php echo $sub['total']; ?></span>
              </span>
              <div class="ss-bar-wrap">
                <div class="ss-bar" style="width:<?php echo $subPct; ?>%;"></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Active filter strip -->
  <div class="active-filter-strip" id="active-filter-strip">
    <i class="fa fa-filter"></i>
    <span id="active-filter-label">Filtered by sector</span>
    <button class="afs-clear" onclick="clearSectorFilter()"><i class="fa fa-times"></i> Clear Filter</button>
  </div>
  <?php endif; ?>

  <?php if(!empty($rows)): ?>
  <!-- ── Table Toolbar ── -->
  <div class="table-toolbar">
    <div class="tb-search-wrap">
      <i class="fa fa-search"></i>
      <input type="text" id="tb-search" placeholder="Search by name, ITS, sector…">
      <button class="tb-clear" id="tb-clear-btn" type="button">×</button>
    </div>
    <select id="tb-rsvp-filter" class="tb-select">
      <option value="">All RSVP Status</option>
      <option value="yes">RSVP Yes</option>
      <option value="no">RSVP No / Pending</option>
    </select>
    <div class="tb-count">Showing <span id="visible-count"><?php echo $total_rows; ?></span> rows</div>
  </div>

  <!-- ── Table Card ── -->
  <div class="table-card">
    <div class="table-scroll">
      <div class="table-inner">
        <table id="rsvp-table">
          <thead>
            <tr>
              <th class="td-sno">#</th>
              <th>Member</th>
              <th>Mobile</th>
              <th>Sector</th>
              <th>Sub Sector</th>
              <th>RSVP</th>
              <th>Comment</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($rows as $i=>$member):
              $isYes    = isset($member['rsvp_status'])&&$member['rsvp_status']==1;
              $fullName = $member['full_name']??$member['Full_Name']??$member['name']??'';
              $itsId    = $member['ITS_ID']??$member['hof_id']??$member['ITS']??'';
              $sector   = $member['sector']??$member['Sector']??'';
              $subSec   = $member['sub_sector']??$member['Sub_Sector']??$member['SubSector']??'';
              $comment  = $member['rsvp_comment']??$member['comment']??'';
              $mobileRaw= $member['mobile']??$member['Mobile']??$member['RFM_Mobile']??$member['rfm_mobile']??'';
              $mobile   = !empty($mobileRaw)?substr(preg_replace('/\D/','',$mobileRaw),-10):'';
              $isFemale = strtolower($member['gender']??$member['Gender']??'')==='female';
              $initials = rd_initials($fullName);
            ?>
            <tr class="<?php echo $isYes?'row-yes':''; ?>"
                data-search="<?php echo htmlspecialchars(strtolower($fullName.' '.$itsId.' '.$sector.' '.$subSec)); ?>"
                data-rsvp="<?php echo $isYes?'yes':'no'; ?>"
                data-sector="<?php echo htmlspecialchars(strtolower($sector)); ?>"
                data-subsector="<?php echo htmlspecialchars(strtolower($subSec)); ?>">
              <td class="td-sno"><?php echo $i+1; ?></td>
              <td>
                <div class="member-cell">
                  <div class="member-av <?php echo $isFemale?'f':''; ?>"><?php echo htmlspecialchars($initials); ?></div>
                  <div>
                    <div class="member-name"><?php echo htmlspecialchars($fullName); ?></div>
                    <div class="member-its"><?php echo htmlspecialchars($itsId); ?></div>
                  </div>
                </div>
              </td>
              <td><?php echo $mobile?htmlspecialchars($mobile):'<span style="color:var(--text-3);">—</span>'; ?></td>
              <td><span class="sector-tag"><?php echo htmlspecialchars($sector?:'-'); ?></span></td>
              <td><span class="sector-tag"><?php echo htmlspecialchars($subSec?:'-'); ?></span></td>
              <td><?php if($isYes): ?><span class="rsvp-yes"><i class="fa fa-check-circle"></i> Yes</span><?php else: ?><span class="rsvp-no"><i class="fa fa-times-circle"></i> No</span><?php endif; ?></td>
              <td><?php if(!empty($comment)): ?><span class="comment-text" title="<?php echo htmlspecialchars($comment); ?>"><?php echo htmlspecialchars($comment); ?></span><?php else: ?><span class="no-comment">—</span><?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php else: ?>
  <div class="empty-state"><i class="fa-regular fa-face-frown-open"></i><p>No members found for this miqaat.</p></div>
  <?php endif; ?>

</div>
</div>

<script>
(function(){
  /* ── State ── */
  var activeSector    = '';
  var activeSubSector = '';

  var rows         = Array.prototype.slice.call(document.querySelectorAll('#rsvp-table tbody tr'));
  var searchInput  = document.getElementById('tb-search');
  var clearBtn     = document.getElementById('tb-clear-btn');
  var rsvpFilter   = document.getElementById('tb-rsvp-filter');
  var countEl      = document.getElementById('visible-count');
  var filterStrip  = document.getElementById('active-filter-strip');
  var filterLabel  = document.getElementById('active-filter-label');

  /* ── Master filter function ── */
  function applyFilters(){
    var q       = searchInput ? (searchInput.value||'').toLowerCase().trim() : '';
    var rsvpVal = rsvpFilter  ? rsvpFilter.value : '';
    var vis = 0;
    rows.forEach(function(tr){
      var search  = tr.getAttribute('data-search')||'';
      var rsvp    = tr.getAttribute('data-rsvp')||'';
      var sec     = tr.getAttribute('data-sector')||'';
      var sub     = tr.getAttribute('data-subsector')||'';
      var show    = true;
      if(q && search.indexOf(q)===-1) show=false;
      if(rsvpVal && rsvp!==rsvpVal)   show=false;
      if(activeSector && sec!==activeSector) show=false;
      if(activeSubSector && sub!==activeSubSector) show=false;
      tr.style.display = show?'':'none';
      if(show) vis++;
    });
    /* renumber */
    var n=1;
    rows.forEach(function(tr){ if(tr.style.display!=='none'){ var s=tr.querySelector('.td-sno'); if(s)s.textContent=n++; } });
    if(countEl) countEl.textContent = vis;
    /* filter strip */
    if(filterStrip){
      if(activeSector){
        filterStrip.classList.add('visible');
        var lbl = activeSector.charAt(0).toUpperCase()+activeSector.slice(1);
        if(activeSubSector) lbl += ' › '+activeSubSector.charAt(0).toUpperCase()+activeSubSector.slice(1);
        if(filterLabel) filterLabel.textContent = 'Filtered: '+lbl;
      } else {
        filterStrip.classList.remove('visible');
      }
    }
  }

  /* ── Sector card click ── */
  window.handleSectorClick = function(card){
    var sec = card.getAttribute('data-sector');
    if(activeSector===sec && activeSubSector===''){
      /* deselect */
      activeSector=''; activeSubSector='';
      document.querySelectorAll('.sector-card').forEach(function(c){c.classList.remove('active');});
      document.querySelectorAll('.ss-row').forEach(function(r){r.classList.remove('active');});
    } else {
      activeSector = sec; activeSubSector = '';
      document.querySelectorAll('.sector-card').forEach(function(c){c.classList.toggle('active',c.getAttribute('data-sector')===sec);});
      document.querySelectorAll('.ss-row').forEach(function(r){r.classList.remove('active');});
    }
    applyFilters();
  };

  /* ── Sub-sector row click ── */
  window.handleSubSectorClick = function(e, row){
    e.stopPropagation(); /* prevent sector card click */
    var sec = row.getAttribute('data-sector');
    var sub = row.getAttribute('data-subsector');
    if(activeSector===sec && activeSubSector===sub){
      /* deselect sub, keep sector */
      activeSubSector='';
      document.querySelectorAll('.ss-row').forEach(function(r){r.classList.remove('active');});
    } else {
      activeSector    = sec;
      activeSubSector = sub;
      document.querySelectorAll('.sector-card').forEach(function(c){c.classList.toggle('active',c.getAttribute('data-sector')===sec);});
      document.querySelectorAll('.ss-row').forEach(function(r){r.classList.toggle('active',r.getAttribute('data-sector')===sec&&r.getAttribute('data-subsector')===sub);});
    }
    applyFilters();
  };

  /* ── Clear sector filter ── */
  window.clearSectorFilter = function(){
    activeSector=''; activeSubSector='';
    document.querySelectorAll('.sector-card').forEach(function(c){c.classList.remove('active');});
    document.querySelectorAll('.ss-row').forEach(function(r){r.classList.remove('active');});
    applyFilters();
  };

  /* ── Toolbar events ── */
  if(searchInput) searchInput.addEventListener('input', applyFilters);
  if(clearBtn)    clearBtn.addEventListener('click', function(){ searchInput.value=''; applyFilters(); searchInput.focus(); });
  if(rsvpFilter)  rsvpFilter.addEventListener('change', applyFilters);

  /* ── Animate bars ── */
  setTimeout(function(){
    document.querySelectorAll('#rdApp .stat-bar, #rdApp .sc-progress-fill, #rdApp .ss-bar').forEach(function(b){
      var w=b.style.width; b.style.width='0';
      setTimeout(function(){ b.style.width=w; b.style.transition='width .5s ease'; },60);
    });
  },120);
})();
</script>