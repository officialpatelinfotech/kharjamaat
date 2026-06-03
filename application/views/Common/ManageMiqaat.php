<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
/* ── Golden Theme ── */
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
  --purple:       #6d28d9;
  --purple-bg:    #f5f3ff;
  --teal:         #0f766e;
  --teal-bg:      #f0fdfa;
  --pink:         #9d174d;
  --pink-bg:      #fdf2f8;
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --shadow:       0 4px 16px rgba(0,0,0,.07),0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:    0 8px 32px rgba(0,0,0,.10),0 2px 8px rgba(0,0,0,.05);
  --radius:       16px;
  --radius-sm:    10px;
}

/* ── Scoped reset ── */
#mqApp,#mqApp *,#mqApp *::before,#mqApp *::after{box-sizing:border-box;}
#mqApp{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--bg);min-height:100vh;padding:57px 0 40px;}

/* ── Full-width wrapper (12px side padding only) ── */
#mqApp .mq-wrap{padding:0 12px;}
@media(min-width:1400px){#mqApp .mq-wrap{padding:0 20px;}}

/* ── Flash alerts ── */
#mqApp .flash{padding:12px 16px;border-radius:10px;margin-bottom:14px;font-size:.86rem;font-weight:600;display:flex;align-items:center;gap:8px;}
#mqApp .flash.success{background:var(--green-bg);color:var(--green);border:1px solid #86efac;}
#mqApp .flash.error{background:var(--red-bg);color:var(--red);border:1px solid #fca5a5;}

/* ── Page header banner ── */
#mqApp .mq-header{
  background:linear-gradient(135deg,#78520a 0%,#b8860b 50%,#c9a227 100%);
  border-radius:20px;padding:18px 22px;margin-bottom:18px;
  position:relative;overflow:hidden;
  display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;
}
#mqApp .mq-header::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;pointer-events:none;}
#mqApp .mq-header::after{content:'';position:absolute;right:-40px;top:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);pointer-events:none;}
#mqApp .mq-eyebrow{font-size:.65rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:3px;position:relative;z-index:1;}
#mqApp .mq-title{font-family:'Literata',Georgia,serif;font-size:1.35rem;font-weight:600;color:#fff;line-height:1.2;margin:0;position:relative;z-index:1;}
#mqApp .mq-title small{font-size:.85rem;font-weight:400;color:rgba(255,255,255,.7);display:block;margin-top:2px;}
#mqApp .mq-header-actions{display:flex;align-items:center;gap:8px;position:relative;z-index:1;flex-wrap:wrap;}
#mqApp .hdr-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 15px;border-radius:9px;font-size:.8rem;font-weight:700;text-decoration:none;transition:background .15s;white-space:nowrap;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.15);color:#fff;cursor:pointer;}
#mqApp .hdr-btn:hover{background:rgba(255,255,255,.28);color:#fff;text-decoration:none;}
#mqApp .hdr-btn.primary{background:rgba(255,255,255,.92);color:var(--gold);border-color:rgba(255,255,255,.6);}
#mqApp .hdr-btn.primary:hover{background:#fff;}

/* ── Filter card ── */
#mqApp .filter-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);padding:14px 18px;margin-bottom:16px;box-shadow:var(--shadow-sm);}
#mqApp .filter-row{display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
#mqApp .filter-field{display:flex;flex-direction:column;gap:4px;flex:1 1 140px;min-width:0;}
#mqApp .filter-field label{font-size:.68rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.3px;}
#mqApp .filter-field select,
#mqApp .filter-field input{
  height:38px;padding:0 11px;
  border:1.5px solid var(--border);border-radius:9px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;
  color:var(--text-1);background:var(--surface-2);outline:none;
  transition:border-color .15s,box-shadow .15s;width:100%;
}
#mqApp .filter-field select:focus,
#mqApp .filter-field input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);}
#mqApp .filter-actions{display:flex;gap:8px;align-items:flex-end;flex-shrink:0;}
#mqApp .btn-filter{display:inline-flex;align-items:center;gap:6px;height:38px;padding:0 16px;border-radius:9px;border:none;background:linear-gradient(135deg,#b8860b,#c9a227);color:#fff;font-size:.82rem;font-weight:800;cursor:pointer;box-shadow:0 2px 8px rgba(184,134,11,.28);white-space:nowrap;}
#mqApp .btn-clear{display:inline-flex;align-items:center;gap:6px;height:38px;padding:0 14px;border-radius:9px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.82rem;font-weight:700;text-decoration:none;transition:all .15s;white-space:nowrap;}
#mqApp .btn-clear:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);text-decoration:none;}
#mqApp .btn-print{display:inline-flex;align-items:center;gap:6px;height:38px;padding:0 14px;border-radius:9px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.82rem;font-weight:700;cursor:pointer;transition:all .15s;white-space:nowrap;}
#mqApp .btn-print:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);}
#mqApp .btn-add{display:inline-flex;align-items:center;gap:6px;height:38px;padding:0 16px;border-radius:9px;background:linear-gradient(135deg,#b8860b,#c9a227);color:#fff;font-size:.82rem;font-weight:800;text-decoration:none;box-shadow:0 2px 8px rgba(184,134,11,.28);white-space:nowrap;transition:opacity .15s;}
#mqApp .btn-add:hover{opacity:.9;color:#fff;text-decoration:none;}

/* ── Stats grid ── */
#mqApp .stats-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
  gap:10px;
  margin-bottom:18px;
}
#mqApp .stat-card{
  border-radius:var(--radius-sm);padding:13px 14px;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:4px;text-align:center;border:1.5px solid transparent;
  transition:transform .15s,box-shadow .15s;
}
#mqApp .stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow);}
#mqApp .stat-card .sc-val{font-size:1.6rem;font-weight:800;line-height:1;color:var(--text-1);}
#mqApp .stat-card .sc-lbl{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--text-2);margin-top:2px;}
#mqApp .stat-card.c-gold   {background:var(--gold-muted);     border-color:var(--gold-border);}
#mqApp .stat-card.c-gold .sc-val{color:var(--gold);}
#mqApp .stat-card.c-blue   {background:var(--blue-bg);        border-color:#bfdbfe;}
#mqApp .stat-card.c-blue .sc-val{color:var(--blue);}
#mqApp .stat-card.c-teal   {background:var(--teal-bg);        border-color:#99f6e4;}
#mqApp .stat-card.c-teal .sc-val{color:var(--teal);}
#mqApp .stat-card.c-green  {background:var(--green-bg);       border-color:#86efac;}
#mqApp .stat-card.c-green .sc-val{color:var(--green);}
#mqApp .stat-card.c-orange {background:var(--orange-bg);      border-color:#fcd34d;}
#mqApp .stat-card.c-orange .sc-val{color:var(--orange);}
#mqApp .stat-card.c-purple {background:var(--purple-bg);      border-color:#c4b5fd;}
#mqApp .stat-card.c-purple .sc-val{color:var(--purple);}
#mqApp .stat-card.c-pink   {background:var(--pink-bg);        border-color:#fbcfe8;}
#mqApp .stat-card.c-pink .sc-val{color:var(--pink);}
#mqApp .stat-card.c-red    {background:var(--red-bg);         border-color:#fca5a5;}
#mqApp .stat-card.c-red .sc-val{color:var(--red);}
#mqApp .stat-card.c-amber  {background:#fffbeb;               border-color:#fcd34d;}
#mqApp .stat-card.c-amber .sc-val{color:#92400e;}
#mqApp .stat-card.c-rose   {background:#fff1f2;               border-color:#fda4af;}
#mqApp .stat-card.c-rose .sc-val{color:#be123c;}

/* ── Table card ── */
#mqApp .table-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;}
#mqApp .table-card-hd{display:flex;align-items:center;justify-content:space-between;padding:12px 18px;background:var(--surface-2);border-bottom:1.5px solid var(--border-light);}
#mqApp .table-card-hd-left{display:flex;align-items:center;gap:8px;}
#mqApp .tc-icon{width:28px;height:28px;border-radius:7px;background:var(--gold-muted);color:var(--gold);display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0;}
#mqApp .tc-title{font-size:.82rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;}
#mqApp .tc-count{font-size:.7rem;font-weight:700;padding:2px 9px;border-radius:20px;background:var(--gold-muted);color:var(--gold);}

/* ── Table scroll ── */
#mqApp .table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
#mqApp .table-inner-scroll{max-height:62vh;overflow:auto;}

/* ── Table ── */
#mqApp table{width:100%;border-collapse:collapse;font-size:.83rem;}
#mqApp thead th{
  position:sticky;top:0;z-index:5;
  padding:10px 13px;font-size:.7rem;font-weight:800;
  color:var(--text-2);text-transform:uppercase;letter-spacing:.4px;
  background:var(--gold-muted);border-bottom:2px solid var(--gold-border);
  white-space:nowrap;cursor:pointer;user-select:none;
}
#mqApp thead th:hover{color:var(--gold);}
#mqApp thead th .sort-indicator{margin-left:4px;font-size:.65rem;opacity:.5;}
#mqApp thead th:hover .sort-indicator{opacity:1;}
#mqApp tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s;}
#mqApp tbody tr:last-child{border-bottom:none;}
#mqApp tbody tr:hover{background:rgba(184,134,11,.04);}
#mqApp tbody tr.sunday-row{background:rgba(185,28,28,.04);}
#mqApp tbody tr.sunday-row:hover{background:rgba(185,28,28,.08);}
#mqApp td{padding:10px 13px;vertical-align:middle;}
#mqApp td.sno{color:var(--text-3);font-size:.75rem;font-weight:700;width:38px;white-space:nowrap;}

/* ── Month header row ── */
#mqApp tr.month-header td{
  background:var(--gold-muted);
  font-size:.74rem;font-weight:800;color:var(--gold);
  text-transform:uppercase;letter-spacing:.6px;
  padding:8px 14px;
  border-top:2px solid var(--gold-border);
  border-bottom:1px solid var(--gold-border);
}

/* ── Date cells ── */
#mqApp .eng-date{font-weight:700;font-size:.83rem;color:var(--text-1);white-space:nowrap;}
#mqApp .hijri-date{font-size:.72rem;color:var(--text-3);margin-top:2px;white-space:nowrap;}
#mqApp .day-badge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:.68rem;font-weight:700;background:var(--surface-2);color:var(--text-2);border:1px solid var(--border-light);}
#mqApp .day-badge.sunday{background:var(--red-bg);color:var(--red);border-color:#fca5a5;}

/* ── Miqaat name & type ── */
#mqApp .mq-name{font-weight:700;font-size:.85rem;color:var(--text-1);}
#mqApp .mq-type-tag{display:inline-block;margin-top:3px;padding:2px 8px;border-radius:20px;font-size:.67rem;font-weight:700;border:1px solid transparent;}
#mqApp .mq-type-tag.ashara   {background:var(--rose-bg,#fff1f2);color:#be123c;border-color:#fda4af;}
#mqApp .mq-type-tag.shehrullah{background:#e0f2fe;color:#0369a1;border-color:#7dd3fc;}
#mqApp .mq-type-tag.general  {background:var(--green-bg);color:var(--green);border-color:#86efac;}
#mqApp .mq-type-tag.ladies   {background:var(--pink-bg);color:var(--pink);border-color:#fbcfe8;}
#mqApp .mq-type-tag.default  {background:var(--surface-2);color:var(--text-3);border-color:var(--border);}

/* ── Assignment cell ── */
#mqApp .assign-group-name{font-weight:800;font-size:.82rem;color:var(--text-1);display:flex;align-items:center;gap:5px;margin-bottom:3px;}
#mqApp .assign-group-name i{color:var(--gold);}
#mqApp .assign-label{font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--text-3);letter-spacing:.3px;margin:5px 0 2px;}
#mqApp .assign-person{font-size:.78rem;color:var(--text-2);display:flex;align-items:center;gap:5px;}
#mqApp .assign-person .its{font-size:.68rem;color:var(--text-3);background:var(--surface-2);padding:1px 6px;border-radius:8px;border:1px solid var(--border-light);}
#mqApp .assign-pending{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:var(--orange-bg);color:var(--orange);border:1px solid #fcd34d;}
#mqApp .assign-direct{font-weight:700;font-size:.84rem;color:var(--text-1);}

/* ── Status badges ── */
#mqApp .status-badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700;border:1px solid transparent;}
#mqApp .status-badge.active     {background:var(--green-bg);color:var(--green);border-color:#86efac;}
#mqApp .status-badge.inactive   {background:var(--orange-bg);color:var(--orange);border-color:#fcd34d;}
#mqApp .status-badge.completed  {background:var(--blue-bg);color:var(--blue);border-color:#93c5fd;}
#mqApp .status-badge.partial    {background:var(--purple-bg);color:var(--purple);border-color:#c4b5fd;}
#mqApp .status-badge.pending-status{background:var(--surface-2);color:var(--text-3);border-color:var(--border);}

/* ── Action buttons ── */
#mqApp .act-row{display:grid;grid-template-columns:1fr 1fr;gap:5px;}
#mqApp .act-btn{display:inline-flex;align-items:center;justify-content:center;width:100%;height:30px;border-radius:7px;border:1.5px solid;font-size:.78rem;cursor:pointer;background:transparent;transition:background .12s;text-decoration:none;}
#mqApp .act-btn:disabled{opacity:.4;cursor:not-allowed;}
#mqApp .act-btn.activate{border-color:#86efac;color:var(--green);}
#mqApp .act-btn.activate:hover:not(:disabled){background:var(--green-bg);}
#mqApp .act-btn.cancel  {border-color:#fcd34d;color:var(--orange);}
#mqApp .act-btn.cancel:hover:not(:disabled){background:var(--orange-bg);}
#mqApp .act-btn.edit    {border-color:#93c5fd;color:var(--blue);}
#mqApp .act-btn.edit:hover{background:var(--blue-bg);}
#mqApp .act-btn.del     {border-color:#fca5a5;color:var(--red);}
#mqApp .act-btn.del:hover:not(:disabled){background:var(--red-bg);}

/* ── Empty state ── */
#mqApp .empty-state{padding:48px 20px;text-align:center;color:var(--text-3);}
#mqApp .empty-state i{font-size:2rem;margin-bottom:10px;display:block;}
#mqApp .empty-state p{font-size:.9rem;margin:0 0 12px;}

/* ── Responsive ── */
@media(max-width:768px){
  #mqApp .stats-grid{grid-template-columns:repeat(2,1fr);}
  #mqApp .filter-row{flex-direction:column;}
  #mqApp .filter-field{flex:none;width:100%;}
  #mqApp .filter-actions{width:100%;flex-wrap:wrap;}
  #mqApp .mq-header{flex-direction:column;align-items:flex-start;}
  #mqApp thead th{font-size:.65rem;padding:8px 9px;}
  #mqApp td{padding:8px 9px;font-size:.78rem;}
}
@media(max-width:480px){
  #mqApp .stats-grid{grid-template-columns:repeat(2,1fr);}
  #mqApp .stat-card .sc-val{font-size:1.3rem;}
}

/* ── Print ── */
@media print{
  body *{visibility:hidden!important;}
  .miqaat-print-only,.miqaat-print-only *{visibility:visible!important;}
  .miqaat-print-only{position:absolute;top:0;left:0;width:100%;}
  .miqaat-screen-only{display:none!important;}
  .miqaat-print-only{display:block!important;}
  .miqaat-print-only thead{display:table-header-group;}
  .miqaat-print-only table{width:100%!important;}
  .miqaat-print-only *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
}
.miqaat-print-only{display:none;}
</style>

<div id="mqApp">
<div class="mq-wrap pt-3 pb-4">

  <!-- Flash messages -->
  <?php if($this->session->flashdata('error')): ?>
    <div class="flash error"><i class="fa-solid fa-triangle-exclamation"></i><?= $this->session->flashdata('error'); ?></div>
  <?php endif; ?>
  <?php if($this->session->flashdata('success')): ?>
    <div class="flash success"><i class="fa-solid fa-circle-check"></i><?= $this->session->flashdata('success'); ?></div>
  <?php endif; ?>

  <!-- ── Header ── -->
  <div class="mq-header">
    <div>
      <p class="mq-eyebrow">Miqaat Management</p>
      <h1 class="mq-title">
        Manage Miqaat
        <small>Hijri Year <?php echo isset($hijri_year) ? htmlspecialchars($hijri_year) : ''; ?></small>
      </h1>
    </div>
    <div class="mq-header-actions">
      <a href="<?php echo isset($from) ? base_url($from) : base_url('anjuman/fmbthaali'); ?>" class="hdr-btn">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
      <button id="print-table-btn" class="hdr-btn"><i class="fa fa-print"></i> Print</button>
      <a href="<?php echo base_url('common/createmiqaat?date='.date('Y-m-d')); ?>" class="hdr-btn primary">
        <i class="fa-solid fa-plus"></i> Add Miqaat
      </a>
    </div>
  </div>

  <!-- ── Filter card ── -->
  <div class="filter-card">
    <form method="post" action="<?php echo base_url('common/managemiqaat'); ?>" id="filter-form">
      <div class="filter-row">
        <div class="filter-field">
          <label>Month / Year</label>
          <select name="hijri_month" id="hijri-month">
            <option value="">All Months</option>
            <option value="-3" <?php echo (isset($hijri_month_id)&&$hijri_month_id==-3)?'selected':''; ?>>Last Year</option>
            <option value="-1" <?php echo (isset($hijri_month_id)&&$hijri_month_id==-1)?'selected':''; ?>>Current Year</option>
            <?php if(isset($hijri_months)) foreach($hijri_months as $v): ?>
              <option value="<?php echo $v['id']; ?>" <?php echo (isset($hijri_month_id)&&$v['id']==$hijri_month_id)?'selected':''; ?>><?php echo $v['hijri_month']; ?></option>
            <?php endforeach; ?>
            <option value="-2" <?php echo (isset($hijri_month_id)&&$hijri_month_id==-2)?'selected':''; ?>>Next Year</option>
          </select>
        </div>
        <div class="filter-field">
          <label>Member / Leader</label>
          <input type="text" name="member_name_filter" id="member-name-filter" placeholder="Name or ITS ID" value="<?php echo isset($member_name_filter)?htmlspecialchars($member_name_filter,ENT_QUOTES):''; ?>">
        </div>
        <div class="filter-field">
          <label>Assignment</label>
          <select name="assignment_filter" id="assignment-filter">
            <option value="" <?php echo empty($assignment_filter)?'selected':''; ?>>All</option>
            <option value="unassigned" <?php echo (isset($assignment_filter)&&$assignment_filter==='unassigned')?'selected':''; ?>>Unassigned Only</option>
            <option value="assigned" <?php echo (isset($assignment_filter)&&$assignment_filter==='assigned')?'selected':''; ?>>Assigned Only</option>
          </select>
        </div>
        <div class="filter-field">
          <label>Miqaat Type</label>
          <select name="miqaat_type" id="miqaat-type">
            <option value="">All Types</option>
            <?php if(!empty($miqaat_types)) foreach($miqaat_types as $type): ?>
              <option value="<?php echo htmlspecialchars($type,ENT_QUOTES); ?>" <?php echo (isset($miqaat_type)&&$miqaat_type===$type)?'selected':''; ?>><?php echo htmlspecialchars($type); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-actions">
          <button type="submit" class="btn-filter"><i class="fa fa-filter"></i> Apply</button>
          <a href="<?php echo base_url('common/managemiqaat'); ?>" class="btn-clear"><i class="fa fa-times"></i> Clear</a>
        </div>
      </div>
    </form>
  </div>

  <!-- ── Stats grid ── -->
  <?php
  $sum_total_miqaats=isset($summary_miqaat_days)?(int)$summary_miqaat_days:0;
  $sum_individual=isset($summary_individual)?(int)$summary_individual:0;
  $sum_group=isset($summary_group)?(int)$summary_group:0;
  $sum_fnn=isset($summary_fnn)?(int)$summary_fnn:0;
  $sum_ashara=isset($summary_ashara)?(int)$summary_ashara:0;
  $sum_shehrullah=isset($summary_shehrullah)?(int)$summary_shehrullah:0;
  $sum_general=isset($summary_general)?(int)$summary_general:0;
  $sum_ladies=isset($summary_ladies)?(int)$summary_ladies:0;

  if($sum_total_miqaats===0||$sum_individual===0){
    $sum_total_miqaats=$sum_individual=$sum_group=$sum_fnn=$sum_ashara=$sum_shehrullah=$sum_general=$sum_ladies=0;
    if(!empty($miqaats)&&is_array($miqaats)){
      foreach($miqaats as $d){
        if(empty($d['miqaats']))continue;
        foreach($d['miqaats'] as $m){
          $sum_total_miqaats++;
          $hasInd=$hasGrp=false;
          if(!empty($m['assignments'])){foreach($m['assignments'] as $as){$at=strtolower($as['assign_type']??'');if($at==='individual')$hasInd=true;if($at==='group')$hasGrp=true;}}
          if($hasInd)$sum_individual++;if($hasGrp)$sum_group++;
          $hay=strtolower(trim(($m['type']??'').' '.($m['name']??'').' '.($m['assigned_to']??'')));
          $is_fnn=(strpos($hay,'fnn')!==false)||((strpos($hay,'fala')!==false)&&(strpos($hay,'niyaz')!==false||strpos($hay,'niaz')!==false));
          if($is_fnn)$sum_fnn++;
          $tl=strtolower(trim($m['type']??''));
          if($tl==='ashara')$sum_ashara++;if($tl==='shehrullah')$sum_shehrullah++;if($tl==='general')$sum_general++;if($tl==='ladies')$sum_ladies++;
        }
      }
    }
  }

  // Individual contributors (HOF-based)
  $sum_individual_contributors=isset($calendar_summary['individual_contributors'])?(int)$calendar_summary['individual_contributors']:0;
  if($sum_individual_contributors===0){
    $uniq_member_ids=[];
    if(!empty($miqaats)&&is_array($miqaats)){foreach($miqaats as $d){if(empty($d['miqaats']))continue;foreach($d['miqaats'] as $m){if(empty($m['assignments']))continue;foreach($m['assignments'] as $as){$at=strtolower($as['assign_type']??'');if($at==='individual'){$mid=trim((string)($as['member_id']??''));if($mid!=='')$uniq_member_ids[$mid]=true;}}}}}
    if(!empty($uniq_member_ids)){
      try{
        $ci=get_instance();$ids=array_keys($uniq_member_ids);$chunks=array_chunk($ids,200);$hof_ids=[];
        foreach($chunks as $c){$escaped=array_map(function($v)use($ci){return $ci->db->escape($v);},$c);$in=implode(',',$escaped);$sql="SELECT DISTINCT hof.ITS_ID AS hof_id FROM `user` u JOIN `user` hof ON hof.ITS_ID=(CASE WHEN u.HOF_FM_TYPE='HOF' THEN u.ITS_ID ELSE u.HOF_ID END) WHERE u.ITS_ID IN(".$in.") AND hof.HOF_FM_TYPE='HOF' AND hof.Inactive_Status IS NULL AND hof.Sector IS NOT NULL AND hof.Sub_Sector IS NOT NULL";$res=$ci->db->query($sql)->result_array();foreach($res as $r){$hid=trim((string)($r['hof_id']??''));if($hid!=='')$hof_ids[$hid]=true;}}
        $sum_individual_contributors=count($hof_ids);
      }catch(Exception $e){$sum_individual_contributors=count($uniq_member_ids);}
    }
  }
  $total_members=isset($calendar_summary['total_members'])?(int)$calendar_summary['total_members']:(isset($summary_total_members)?(int)$summary_total_members:0);
  if(empty($total_members)){try{$ci=get_instance();$q=$ci->db->query("SELECT COUNT(*) AS cnt FROM `user` WHERE HOF_FM_TYPE='HOF' AND Inactive_Status IS NULL");$row=$q->row();$total_members=$row?(int)$row->cnt:0;}catch(Exception $e){$total_members=0;}}
  $non_contributors=max(0,$total_members-$sum_individual_contributors);
  ?>

  <div class="stats-grid">
    <div class="stat-card c-gold"><span class="sc-val"><?php echo isset($calendar_summary['total_miqaat_days'])?(int)$calendar_summary['total_miqaat_days']:(int)$sum_total_miqaats; ?></span><span class="sc-lbl">Total Miqaat</span></div>
    <div class="stat-card c-rose"><span class="sc-val"><?php echo isset($calendar_summary['ashara'])?(int)$calendar_summary['ashara']:(int)$sum_ashara; ?></span><span class="sc-lbl">Ashara</span></div>
    <div class="stat-card c-blue"><span class="sc-val"><?php echo isset($calendar_summary['shehrullah'])?(int)$calendar_summary['shehrullah']:(int)$sum_shehrullah; ?></span><span class="sc-lbl">Shehrullah</span></div>
    <div class="stat-card c-green"><span class="sc-val"><?php echo isset($calendar_summary['general'])?(int)$calendar_summary['general']:(int)$sum_general; ?></span><span class="sc-lbl">General</span></div>
    <div class="stat-card c-pink"><span class="sc-val"><?php echo isset($calendar_summary['ladies'])?(int)$calendar_summary['ladies']:(int)$sum_ladies; ?></span><span class="sc-lbl">Ladies</span></div>
    <div class="stat-card c-purple"><span class="sc-val"><?php echo isset($calendar_summary['individual'])?(int)$calendar_summary['individual']:(int)$sum_individual; ?></span><span class="sc-lbl">Individual Niyaaz</span></div>
    <div class="stat-card c-teal"><span class="sc-val"><?php echo isset($calendar_summary['group'])?(int)$calendar_summary['group']:(int)$sum_group; ?></span><span class="sc-lbl">Group Niyaaz</span></div>
    <div class="stat-card c-amber"><span class="sc-val"><?php echo isset($calendar_summary['fnn'])?(int)$calendar_summary['fnn']:(int)$sum_fnn; ?></span><span class="sc-lbl">Fala ni Niyaaz</span></div>
    <div class="stat-card c-green"><span class="sc-val"><?php echo (int)$sum_individual_contributors; ?></span><span class="sc-lbl">Contributors</span></div>
    <div class="stat-card c-red"><span class="sc-val"><?php echo (int)$non_contributors; ?></span><span class="sc-lbl">Fala Contributors</span></div>
  </div>

  <!-- ── Main Table ── -->
  <?php
  $monthWiseMiqaats=[];
  $totalVisibleRows=0;
  if(!empty($miqaats)){
    $filter_a=isset($assignment_filter)?$assignment_filter:'';
    $filteredMiqaats=[];
    if($filter_a==='unassigned'||$filter_a==='assigned'){
      foreach($miqaats as $d){
        if(empty($d['miqaats']))continue;
        $dc=$d;$dc['miqaats']=[];
        foreach($d['miqaats'] as $m){$hasA=!empty($m['assignments']);if($filter_a==='unassigned'&&!$hasA)$dc['miqaats'][]=$m;elseif($filter_a==='assigned'&&$hasA)$dc['miqaats'][]=$m;}
        if(!empty($dc['miqaats']))$filteredMiqaats[]=$dc;
      }
    } else {
      $filteredMiqaats=array_values(array_filter($miqaats,function($d){return!empty($d['miqaats']);}));
    }
    foreach($filteredMiqaats as $day){
      $hijriMonth='';
      if(!empty($day['hijri_date_with_month'])){$parts=explode(' ',$day['hijri_date_with_month'],2);$hijriMonth=isset($parts[1])?$parts[1]:'';}
      if($hijriMonth){if(!isset($monthWiseMiqaats[$hijriMonth]))$monthWiseMiqaats[$hijriMonth]=[];$monthWiseMiqaats[$hijriMonth][]=$day;}
      foreach($day['miqaats']??[] as $_)$totalVisibleRows++;
    }
  }
  ?>

  <div class="table-card miqaat-list-container">
    <div class="table-card-hd">
      <div class="table-card-hd-left">
        <span class="tc-icon"><i class="fa fa-calendar-alt"></i></span>
        <span class="tc-title">Miqaat Schedule</span>
        <span class="tc-count" id="mq-row-count"><?php echo $totalVisibleRows; ?> entries</span>
      </div>
    </div>

    <!-- On-screen table -->
    <div class="miqaat-screen-only table-scroll">
      <div class="table-inner-scroll">
        <table>
          <thead>
            <tr>
              <th data-no-sort class="sno">#</th>
              <th>Eng Date</th>
              <th>Hijri Date</th>
              <th>Day</th>
              <th>Name</th>
              <th>Type</th>
              <th>Assigned To</th>
              <th>Status</th>
              <th data-no-sort>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($monthWiseMiqaats)): ?>
              <?php $sno=1; ?>
              <?php foreach($monthWiseMiqaats as $monthName=>$days): ?>
                <tr class="month-header" data-hijri-month-name="<?php echo htmlspecialchars($monthName,ENT_QUOTES); ?>">
                  <td colspan="9"><i class="fa fa-moon" style="margin-right:6px;"></i><?php echo htmlspecialchars($monthName); ?></td>
                </tr>
                <?php foreach($days as $day): ?>
                  <?php $dayName=isset($day['date'])?date('l',strtotime($day['date'])):''; $isSun=($dayName==='Sunday'); ?>
                  <?php foreach($day['miqaats'] as $miqaat): ?>
                    <?php $typeL=strtolower(trim($miqaat['type']??'')); ?>
                    <tr class="<?php echo $isSun?'sunday-row':''; ?>" data-hijri-month-name="<?php echo htmlspecialchars($monthName,ENT_QUOTES); ?>" data-eng-date="<?php echo htmlspecialchars($day['date'],ENT_QUOTES); ?>">
                      <td class="sno"><?php echo $sno++; ?></td>
                      <td data-sort-value="<?php echo htmlspecialchars($day['date'],ENT_QUOTES); ?>">
                        <div class="eng-date"><?php echo date('d M Y',strtotime($day['date'])); ?></div>
                      </td>
                      <td>
                        <div class="hijri-date"><?php echo htmlspecialchars($day['hijri_date_with_month']); ?></div>
                      </td>
                      <td data-sort-value="<?php echo strtolower($dayName); ?>">
                        <span class="day-badge <?php echo $isSun?'sunday':''; ?>"><?php echo $dayName; ?></span>
                      </td>
                      <td data-sort-value="<?php echo htmlspecialchars(strtolower($miqaat['name']??''),ENT_QUOTES); ?>">
                        <div class="mq-name"><?php echo htmlspecialchars($miqaat['name']??''); ?></div>
                      </td>
                      <td data-sort-value="<?php echo $typeL; ?>">
                        <?php $typeClass=in_array($typeL,['ashara','shehrullah','general','ladies'])?$typeL:'default'; ?>
                        <span class="mq-type-tag <?php echo $typeClass; ?>"><?php echo htmlspecialchars($miqaat['type']??''); ?></span>
                      </td>
                      <td>
                        <?php if(!empty($miqaat['assignments'])): ?>
                          <?php
                          $grpA=[];$indA=[];
                          foreach($miqaat['assignments'] as $as){
                            $at=strtolower($as['assign_type']??'');
                            if($at==='group')$grpA[]=$as; elseif($at==='individual')$indA[]=$as;
                          }
                          ?>
                          <?php foreach($grpA as $as): ?>
                            <div class="assign-group-name"><i class="fa fa-users"></i><?php echo htmlspecialchars($as['group_name']??''); ?></div>
                            <div class="assign-label">Leader</div>
                            <div class="assign-person"><?php echo htmlspecialchars($as['group_leader_name']??''); ?> <span class="its"><?php echo htmlspecialchars($as['group_leader_id']??''); ?></span></div>
                            <?php if(!empty($as['members'])): ?>
                              <div class="assign-label">Co-leader</div>
                              <?php foreach($as['members'] as $mem): ?>
                                <div class="assign-person"><?php echo htmlspecialchars($mem['name']??''); ?> <span class="its"><?php echo htmlspecialchars($mem['id']??''); ?></span></div>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          <?php endforeach; ?>
                          <?php if(!empty($indA)): ?>
                            <div class="assign-label">Individual</div>
                            <?php foreach($indA as $as): ?>
                              <div class="assign-person"><?php echo htmlspecialchars($as['member_name']??'',ENT_QUOTES); ?> <span class="its"><?php echo htmlspecialchars($as['member_id']??'',ENT_QUOTES); ?></span></div>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php $aTo=trim($miqaat['assigned_to']??''); ?>
                          <?php if($aTo===''||strtolower($aTo)==='n/a'): ?>
                            <span class="assign-pending"><i class="fa fa-clock"></i> Assignment Pending</span>
                          <?php else: ?>
                            <span class="assign-direct"><?php echo htmlspecialchars($aTo,ENT_QUOTES); ?></span>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php
                        if(isset($miqaat['invoice_status'])&&$miqaat['invoice_status']==='Generated'){echo '<span class="status-badge completed"><i class="fa fa-check-circle"></i> Completed</span>';}
                        elseif(isset($miqaat['invoice_status'])&&$miqaat['invoice_status']==='Partially Generated'){echo '<span class="status-badge partial"><i class="fa fa-adjust"></i> Partial Invoice</span>';}
                        elseif(isset($miqaat['status'])){
                          if($miqaat['status']==1)echo '<span class="status-badge active"><i class="fa fa-circle-check"></i> Active</span>';
                          elseif($miqaat['status']==2)echo '<span class="status-badge inactive"><i class="fa fa-ban"></i> Inactive</span>';
                          else echo '<span class="status-badge pending-status"><i class="fa fa-minus-circle"></i> Inactive</span>';
                        }else echo '<span class="status-badge pending-status">N/A</span>';
                        ?>
                      </td>
                      <td>
                        <div class="act-row">
                          <!-- Activate -->
                          <form method="POST" action="<?php echo base_url('common/activate_miqaat'); ?>" style="margin:0;">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="act-btn activate" title="Activate"
                              <?php $noA=empty($miqaat['assignments']); echo((isset($miqaat['status'])&&$miqaat['status']==1)||$noA?'disabled title="Assign first"':''); ?>>
                              <i class="fa fa-check"></i>
                            </button>
                          </form>
                          <!-- Cancel -->
                          <form method="POST" action="<?php echo base_url('common/cancel_miqaat'); ?>" style="margin:0;" onsubmit="return confirm('Make this Miqaat inactive?');">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="act-btn cancel" title="Deactivate"
                              <?php echo(isset($miqaat['status'])&&$miqaat['status']==2)?'disabled':''; ?>>
                              <i class="fa fa-ban"></i>
                            </button>
                          </form>
                          <!-- Edit -->
                          <a href="<?php echo base_url('common/edit_miqaat?id='.$miqaat['id']); ?>" class="act-btn edit" title="Edit">
                            <i class="fa fa-edit"></i>
                          </a>
                          <!-- Delete -->
                          <form method="POST" action="<?php echo base_url('common/delete_miqaat'); ?>" style="margin:0;" onsubmit="return confirm('Delete this Miqaat?');">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="act-btn del" title="Delete"><i class="fa fa-trash"></i></button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9">
                  <div class="empty-state">
                    <i class="fa fa-calendar-times"></i>
                    <p><?php
                      $nm='No Miqaats found';
                      if(!empty($member_name_filter))$nm.=' matching "'.htmlspecialchars($member_name_filter,ENT_QUOTES).'"';
                      if(!empty($assignment_filter))$nm.=' ('.$assignment_filter.')';
                      if(isset($miqaat_type)&&$miqaat_type!=='')$nm.=' of type '.htmlspecialchars($miqaat_type,ENT_QUOTES);
                      echo $nm.'.';
                    ?></p>
                    <a href="<?php echo base_url('common/createmiqaat?date='.date('Y-m-d')); ?>" class="btn-add" style="margin:0 auto;display:inline-flex;"><i class="fa fa-plus"></i> Create Miqaat</a>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Print version -->
    <div class="miqaat-print-only">
      <?php if(!empty($monthWiseMiqaats)): ?>
        <?php $printSno=1;$isFirst=true; ?>
        <?php foreach($monthWiseMiqaats as $monthName=>$days): ?>
          <div style="<?php echo $isFirst?'':'page-break-before:always;'; ?>">
            <div style="font-weight:bold;text-align:center;margin:8px 0 5px;font-size:1.1rem;"><?php echo htmlspecialchars($monthName,ENT_QUOTES); ?></div>
            <table style="width:100%;border-collapse:collapse;font-size:.82rem;">
              <thead><tr style="background:#1f2933;color:#fff;">
                <th style="padding:8px;">#</th><th style="padding:8px;">Eng Date</th><th style="padding:8px;">Hijri Date</th>
                <th style="padding:8px;">Day</th><th style="padding:8px;">Name</th><th style="padding:8px;">Type</th><th style="padding:8px;">Assigned To</th>
              </tr></thead>
              <tbody>
                <?php foreach($days as $day): ?>
                  <?php $dn=isset($day['date'])?date('l',strtotime($day['date'])):'';$rs=$dn==='Sunday'?' style="background:#ffe5e5"':''; ?>
                  <?php foreach($day['miqaats'] as $mq): ?>
                    <tr<?php echo $rs;?>>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo $printSno++; ?></td>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo date('d M Y',strtotime($day['date'])); ?></td>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo htmlspecialchars($day['hijri_date_with_month']); ?></td>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo $dn; ?></td>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo htmlspecialchars($mq['name']??''); ?></td>
                      <td style="padding:6px;border:1px solid #ddd;"><?php echo htmlspecialchars($mq['type']??''); ?></td>
                      <td style="padding:6px;border:1px solid #ddd;">
                        <?php if(!empty($mq['assignments'])){$grpP=[];$indP=[];foreach($mq['assignments'] as $a){$at=strtolower($a['assign_type']??'');if($at==='group')$grpP[]=$a;elseif($at==='individual')$indP[]=$a;}foreach($grpP as $a){echo'<strong>Group: '.htmlspecialchars($a['group_name']??'').'</strong><br>Leader: '.htmlspecialchars($a['group_leader_name']??'').' ('.htmlspecialchars($a['group_leader_id']??'').')<br>';}if(!empty($indP)){echo'Individual:<br>';foreach($indP as $a)echo htmlspecialchars($a['member_name']??'',ENT_QUOTES).' ('.htmlspecialchars($a['member_id']??'',ENT_QUOTES).')<br>';}}else{$aTo=trim($mq['assigned_to']??'');echo($aTo===''||strtolower($aTo)==='n/a')?'Pending':htmlspecialchars($aTo,ENT_QUOTES);} ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php $isFirst=false; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

</div><!-- /mq-wrap -->
</div><!-- /#mqApp -->

<script>
$('#hijri-month,#assignment-filter,#miqaat-type').on('change',function(){$('#filter-form').submit();});
$('.flash').delay(3000).fadeOut(500);

// Print
document.getElementById('print-table-btn').addEventListener('click',function(){setTimeout(function(){window.print();},50);});

// Sortable columns
(function(){
  var table=document.querySelector('#mqApp table');
  if(!table)return;
  var thead=table.querySelector('thead');
  var tbody=table.querySelector('tbody');
  if(!thead||!tbody)return;

  thead.querySelectorAll('th').forEach(function(th,idx){
    if(th.hasAttribute('data-no-sort'))return;
    th.style.cursor='pointer';
    var orig=th.innerHTML.trim();
    th.innerHTML='<span>'+orig+'</span><span class="sort-indicator" style="margin-left:4px;font-size:.62rem;opacity:.5;"></span>';
    th.addEventListener('click',function(){toggleSort(idx,th);});
  });

  function getCellValue(tr,index){
    var cells=tr.querySelectorAll('td');
    if(!cells[index])return'';
    return cells[index].getAttribute('data-sort-value')||cells[index].textContent.trim();
  }
  function norm(v){
    if(/^\d{4}-\d{2}-\d{2}$/.test(v))return new Date(v).getTime();
    if(!isNaN(parseFloat(v))&&isFinite(v))return parseFloat(v);
    return v.toLowerCase();
  }
  function toggleSort(idx,th){
    var newDir=th.dataset.sortDir==='asc'?'desc':'asc';
    thead.querySelectorAll('th').forEach(function(h){h.dataset.sortDir='';var i=h.querySelector('.sort-indicator');if(i)i.textContent='';});
    th.dataset.sortDir=newDir;
    var ind=th.querySelector('.sort-indicator');
    if(ind){ind.textContent=newDir==='asc'?'▲':'▼';ind.style.opacity='1';}
    var allRows=Array.from(tbody.querySelectorAll('tr'));
    var monthHeaders=allRows.filter(function(r){return r.classList.contains('month-header');});
    var dataRows=allRows.filter(function(r){return!r.classList.contains('month-header')&&!r.querySelector('.empty-state');});
    dataRows.sort(function(a,b){
      var va=norm(getCellValue(a,idx)),vb=norm(getCellValue(b,idx));
      if(va<vb)return newDir==='asc'?-1:1;
      if(va>vb)return newDir==='asc'?1:-1;
      return 0;
    });
    tbody.innerHTML='';
    var inserted={};
    dataRows.forEach(function(r){
      var mName=r.getAttribute('data-hijri-month-name')||'';
      if(mName&&!inserted[mName]){
        var hdr=document.createElement('tr');
        hdr.className='month-header';
        hdr.setAttribute('data-hijri-month-name',mName);
        var td=document.createElement('td');td.colSpan=9;
        td.innerHTML='<i class="fa fa-moon" style="margin-right:6px;"></i>'+mName;
        hdr.appendChild(td);tbody.appendChild(hdr);
        inserted[mName]=true;
      }
      tbody.appendChild(r);
    });
    // renumber
    var i=1;tbody.querySelectorAll('td.sno').forEach(function(td){td.textContent=i++;});
  }
})();
</script>