<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
#fmbApp{font-family:'Plus Jakarta Sans',sans-serif;color:#1a1610;background:#faf7f0;min-height:calc(100vh - 57px);padding:14px 16px}
#fmbApp *{box-sizing:border-box}

/* ── Topbar ── */
#fmbApp .fmb-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px}
#fmbApp .fmb-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s}
#fmbApp .fmb-back:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#fmbApp .fmb-heading{font-size:.98rem;font-weight:800;color:#b8860b;text-align:center;flex:1;letter-spacing:.3px}

/* ── Filter card ── */
#fmbApp .fmb-fc{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);margin-bottom:12px;overflow:hidden}
#fmbApp .fmb-fc-head{background:linear-gradient(135deg,#78520a,#b8860b);padding:9px 14px;display:flex;align-items:center;justify-content:space-between}
#fmbApp .fmb-fc-title{font-size:.8rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:6px}
#fmbApp .fmb-fc-hint{font-size:.68rem;color:rgba(255,255,255,.65);font-weight:500}
#fmbApp .fmb-fc-body{padding:11px 14px}
#fmbApp .fmb-frow{display:flex;align-items:flex-end;gap:7px;flex-wrap:wrap}
#fmbApp .fmb-fg{display:flex;flex-direction:column;gap:3px;flex:1;min-width:130px}
#fmbApp .fmb-lbl{font-size:.62rem;font-weight:700;color:#5a5244;text-transform:uppercase;letter-spacing:.4px}
#fmbApp .fmb-sel,#fmbApp .fmb-inp{height:32px;padding:0 9px;border:1.5px solid #e8e0cc;border-radius:7px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:#1a1610;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
#fmbApp .fmb-sel:focus,#fmbApp .fmb-inp:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
#fmbApp .fmb-search-wrap{
    position:relative;
    flex:1;
    min-width:160px
}

#fmbApp .fmb-search-wrap .s-ico{
    position:absolute;
    left:12px;
    top:calc(50% + 11px);
    transform:translateY(-50%);
    color:#9c8f7a;
    font-size:.78rem;
    pointer-events:none;
    display:flex;
    align-items:center;
    justify-content:center
}

#fmbApp .fmb-search-wrap .fmb-inp{
    padding-left:34px;
    padding-right:34px
}

#fmbApp .fmb-search-wrap .s-clr{
    position:absolute;
    right:10px;
    top:calc(50% + 11px);
    transform:translateY(-50%);
    background:none;
    border:none;
    cursor:pointer;
    color:#9c8f7a;
    font-size:.72rem;
    padding:0;
    line-height:1;
    display:none
}

#fmbApp .fmb-search-wrap .s-clr.vis{
    display:flex;
    align-items:center;
    justify-content:center
}

#fmbApp .fmb-search-wrap .s-clr:hover{
    color:#b91c1c
}
#fmbApp .fmb-clear-btn{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 12px;border-radius:7px;background:#f7f4ec;border:1.5px solid #e8e0cc;color:#5a5244;font-size:.74rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;white-space:nowrap;transition:all .15s}
#fmbApp .fmb-clear-btn:hover{background:#fef2f2;border-color:#b91c1c;color:#b91c1c;text-decoration:none}

/* ── Active filters bar ── */
#fmbApp .fmb-chips{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;min-height:0}
#fmbApp .fmb-chip{display:inline-flex;align-items:center;gap:4px;background:#f5e9c0;color:#b8860b;border:1px solid rgba(184,134,11,.3);border-radius:20px;padding:3px 9px;font-size:.67rem;font-weight:700}
#fmbApp .fmb-chip .cx{cursor:pointer;opacity:.6;font-size:.8rem;line-height:1;margin-left:2px;background:none;border:none;color:inherit;padding:0}
#fmbApp .fmb-chip .cx:hover{opacity:1}

/* ── Stats grid — display only, no click ── */
#fmbApp .fmb-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-bottom:12px}
@media(min-width:576px){#fmbApp .fmb-stats{grid-template-columns:repeat(3,1fr)}}
@media(min-width:992px){#fmbApp .fmb-stats{grid-template-columns:repeat(6,1fr)}}
#fmbApp .fmb-scard{border-radius:12px;padding:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:5px;border:1.5px solid;box-shadow:0 1px 3px rgba(0,0,0,.06);min-height:80px}
#fmbApp .sc-lbl{font-size:.63rem;font-weight:700;line-height:1.3}
#fmbApp .sc-val{font-size:1.35rem;font-weight:800;line-height:1}
#fmbApp .sc-ico{font-size:.88rem;margin-bottom:1px}
#fmbApp .sc-general{background:#dbeafe;border-color:#3b82f6;color:#1d4ed8}
#fmbApp .sc-thaali{background:#bae6fd;border-color:#0891b2;color:#0e7490}
#fmbApp .sc-holiday{background:#fed7aa;border-color:#f97316;color:#c2410c}
#fmbApp .sc-individual{background:#ede9fe;border-color:#7c3aed;color:#5b21b6}
#fmbApp .sc-group{background:#d1fae5;border-color:#10b981;color:#065f46}
#fmbApp .sc-fnn{background:#fde68a;border-color:#f59e0b;color:#92400e}

/* ── Table card ── */
#fmbApp .fmb-tcard{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden}
#fmbApp .fmb-legend{display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:8px 14px;border-bottom:1px solid #f0ece0;background:#faf7f0}
#fmbApp .leg-lbl{font-size:.6rem;font-weight:800;color:#9c8f7a;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
#fmbApp .leg-item{display:inline-flex;align-items:center;gap:4px;font-size:.64rem;font-weight:600;color:#5a5244;white-space:nowrap}
#fmbApp .leg-dot{width:9px;height:9px;border-radius:2px;flex-shrink:0;border:1px solid rgba(0,0,0,.12)}
#fmbApp .fmb-row-count{margin-left:auto;background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.64rem;font-weight:800}

/* Table */
#fmbApp .fmb-tscroll{overflow-x:auto;overflow-y:visible}
#fmbApp .fmb-tscroll::-webkit-scrollbar{height:4px}
#fmbApp .fmb-tscroll::-webkit-scrollbar-thumb{background:#e8e0cc;border-radius:10px}
#fmbApp table.fmb-tbl{width:100%;border-collapse:collapse;font-size:.78rem;min-width:900px}
#fmbApp table.fmb-tbl thead th{background:linear-gradient(to bottom,#f7f4ec,#ede8da);padding:9px 11px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:#9c8f7a;border-bottom:2px solid #e8e0cc;white-space:nowrap;text-align:left;z-index:10;user-select:none}
#fmbApp table.fmb-tbl thead th.sortable{cursor:pointer}
#fmbApp table.fmb-tbl thead th.sortable:hover{color:#b8860b}
#fmbApp table.fmb-tbl thead th .si{margin-left:3px;opacity:.3;font-size:.56rem}
#fmbApp table.fmb-tbl thead th.asc .si::after{content:'▲';opacity:1;color:#b8860b}
#fmbApp table.fmb-tbl thead th.desc .si::after{content:'▼';opacity:1;color:#b8860b}
#fmbApp table.fmb-tbl thead th:not(.asc):not(.desc) .si::after{content:'⇅'}
#fmbApp table.fmb-tbl tbody tr{border-bottom:1px solid #f0ece0}
#fmbApp table.fmb-tbl tbody tr:not(.month-hdr):hover td{filter:brightness(.96)}
#fmbApp table.fmb-tbl td{padding:8px 11px;vertical-align:middle;color:#1a1610}

/* Row colors by type */
#fmbApp tr.row-holiday td{background:#f9f9f7;color:#9c8f7a;font-style:italic}
#fmbApp tr.row-sunday td{background:#fff2f2!important}
#fmbApp tr.row-thaali td{background:#f0f9ff}
#fmbApp tr.row-miqaat td{background:#fffdf4}
#fmbApp tr.row-both td{background:#f0fdf5}
#fmbApp tr.row-ladies td{background:#fff5f8}
#fmbApp tr.row-ashara td{background:#fffbf0}
#fmbApp tr.row-general td{background:#f8faff}

/* Left accent border */
#fmbApp tr.row-holiday td:first-child{border-left:3px solid #d1d5db}
#fmbApp tr.row-sunday td:first-child{border-left:3px solid #ef4444}
#fmbApp tr.row-thaali td:first-child{border-left:3px solid #0891b2}
#fmbApp tr.row-miqaat td:first-child{border-left:3px solid #b8860b}
#fmbApp tr.row-both td:first-child{border-left:3px solid #10b981}
#fmbApp tr.row-ladies td:first-child{border-left:3px solid #ec4899}
#fmbApp tr.row-ashara td:first-child{border-left:3px solid #f59e0b}
#fmbApp tr.row-general td:first-child{border-left:3px solid #3b82f6}

/* Month header */
#fmbApp tr.month-hdr td{background:linear-gradient(90deg,#f5e9c0,#fdf5d6)!important;font-weight:800;font-size:.76rem;color:#b8860b;border-top:2px solid rgba(184,134,11,.22);border-bottom:1px solid rgba(184,134,11,.15);padding:7px 11px;white-space:nowrap;filter:none!important}

/* Cells */
#fmbApp .fmb-sno{width:26px;height:26px;border-radius:50%;background:#f5e9c0;color:#b8860b;font-weight:800;font-size:.63rem;display:inline-flex;align-items:center;justify-content:center}
#fmbApp td.col-name,#fmbApp td.col-assigned,#fmbApp td.col-menu{word-break:break-word;white-space:normal}
#fmbApp td.col-name{min-width:130px;max-width:240px}
#fmbApp td.col-assigned{min-width:160px;max-width:280px}
#fmbApp td.col-menu{min-width:160px;max-width:300px}
#fmbApp .fmb-entry{line-height:1.45;padding:1px 0;font-size:.77rem}
#fmbApp .entry-sep{border:none;border-top:1px dashed #e8e0cc;margin:5px 0}
#fmbApp .tbadge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:.61rem;font-weight:800;white-space:nowrap}
#fmbApp .tb-miqaat{background:#f5e9c0;color:#b8860b;border:1px solid rgba(184,134,11,.3)}
#fmbApp .tb-thaali{background:#e0f2fe;color:#0369a1;border:1px solid rgba(3,105,161,.2)}
#fmbApp .tb-both{background:#d1fae5;color:#065f46;border:1px solid rgba(6,95,70,.2)}
#fmbApp .tb-holiday{background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb}
#fmbApp .tb-ladies{background:#fce7f3;color:#9d174d;border:1px solid rgba(157,23,77,.2)}
#fmbApp .tb-ashara{background:#fffbeb;color:#b45309;border:1px solid rgba(180,83,9,.25)}
#fmbApp .tb-general{background:#eff6ff;color:#1d4ed8;border:1px solid rgba(29,78,216,.2)}
#fmbApp .day-sun{color:#b91c1c;font-weight:800}
#fmbApp .day-fri{color:#0369a1;font-weight:700}
#fmbApp .assign-link{color:#1d4ed8;font-weight:600;font-size:.77rem;text-decoration:none;display:inline-flex;align-items:center;gap:3px}
#fmbApp .assign-link:hover{color:#b8860b;text-decoration:underline}
#fmbApp .menu-item{display:inline-block;background:#f0fdf4;color:#065f46;border:1px solid rgba(6,95,70,.18);border-radius:12px;padding:1px 7px;font-size:.65rem;font-weight:600;margin:1px 2px 1px 0;white-space:nowrap}
#fmbApp .holiday-tag{display:inline-flex;align-items:center;gap:4px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700}
#fmbApp .fmb-tfoot{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid #f0ece0;background:#f7f4ec;font-size:.68rem;color:#9c8f7a;flex-wrap:wrap;gap:6px}

/* No-results */
#fmbApp .fmb-no-results{text-align:center;padding:40px 20px;color:#9c8f7a}
#fmbApp .fmb-no-results i{font-size:1.8rem;display:block;margin-bottom:8px;color:#e8e0cc}
#fmbApp .fmb-no-results p{font-size:.82rem;margin:0}

@media(max-width:576px){
  #fmbApp{padding:10px}
  #fmbApp .fmb-frow{flex-direction:column}
  #fmbApp .fmb-fg{min-width:100%}
  #fmbApp table.fmb-tbl{min-width:760px}
  #fmbApp table.fmb-tbl thead th{top:57px}
}
</style>

<!-- Global modal styles -->
<style>
.fmb-ov{position:fixed!important;top:0!important;left:0!important;right:0!important;bottom:0!important;background:rgba(26,22,16,.52)!important;z-index:9999!important;display:none;align-items:center;justify-content:center;padding:16px;font-family:'Plus Jakarta Sans',sans-serif}
.fmb-ov.open{display:flex!important}
.fmb-modal{background:#fff;border:1px solid #e8e0cc;border-radius:18px;width:100%;max-width:500px;box-shadow:0 12px 40px rgba(0,0,0,.18);max-height:90vh;display:flex;flex-direction:column;overflow:hidden}
.fmb-mhd{display:flex;align-items:center;justify-content:space-between;padding:13px 18px;border-bottom:1px solid #f0ece0;flex-shrink:0;background:linear-gradient(to bottom,#f7f4ec,#f0ebe0);border-radius:18px 18px 0 0}
.fmb-mtit{font-weight:800;font-size:.92rem;color:#1a1610;display:flex;align-items:center;gap:8px;font-family:'Plus Jakarta Sans',sans-serif}
.fmb-mclose{width:30px;height:30px;border-radius:8px;border:none;background:#e8e0cc;color:#5a5244;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.95rem;font-weight:700;line-height:1;transition:all .14s;flex-shrink:0}
.fmb-mclose:hover{background:#f5e9c0;color:#b8860b}
.fmb-mbody{overflow-y:auto;flex:1;padding:16px 18px}
.fmb-mft{padding:12px 18px;border-top:1px solid #f0ece0;display:flex;justify-content:flex-end;gap:8px;flex-shrink:0;background:#faf7f0}
.adet-row{padding:10px 12px;border-radius:9px;background:#f7f4ec;border:1px solid #e8e0cc;margin-bottom:8px}
.adet-row .ak{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:#9c8f7a;margin-bottom:4px;display:flex;align-items:center;gap:5px}
.adet-row .av{font-size:.85rem;font-weight:700;color:#1a1610}
.adet-row .asub{font-size:.74rem;color:#5a5244;margin-top:3px}
.fmb-lbl2{display:block;font-size:.67rem;font-weight:800;color:#5a5244;margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px;margin-top:12px}
.fmb-sel2{width:100%;height:38px;padding:0 11px;border:1.5px solid #e8e0cc;border-radius:8px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;color:#1a1610;outline:none;transition:border-color .15s}
.fmb-sel2:focus{border-color:#b8860b;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
.fmb-btn-ok{padding:8px 18px;border-radius:9px;border:none;background:#b8860b;color:#fff;font-weight:700;font-size:.78rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:opacity .15s}
.fmb-btn-ok:hover{opacity:.87}
.fmb-btn-c{padding:8px 16px;border-radius:9px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-weight:700;font-size:.78rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .14s}
.fmb-btn-c:hover{background:#f7f4ec}
</style>

<?php
/* ── Summary counts (always from full $calendar) ── */
$summary_individual=0;$summary_group=0;$summary_fnn=0;
$summary_miqaat_days_excl_ladies=0;$summary_holidays_excl_sundays=0;
$summary_sundays_without_miqaat_thaali=0;$summary_thaali_days=0;
if(!empty($calendar)){
  foreach($calendar as $row){
    $type=$row['miqaat_type']??'';$miqaat_name=$row['miqaat_name']??'';
    $menu_present=!empty($row['menu_items'])&&(!is_array($row['menu_items'])||count($row['menu_items'])>0);
    $isHoliday=empty($type)&&empty($miqaat_name)&&!$menu_present;
    $isSunday=strtolower(date('l',strtotime($row['greg_date']??'now')))==='sunday';
    if($isHoliday&&!$isSunday)$summary_holidays_excl_sundays++;
    if($isSunday&&!(!empty($miqaat_name)||(isset($type)&&in_array(strtolower($type),['miqaat','both']))))$summary_sundays_without_miqaat_thaali++;
    if(strtolower($type)==='thaali')$summary_thaali_days++;
    if(!$isHoliday&&$type!=='Thaali'){
      $asgns=$row['assignments']??[];
      $hay_parts=[(string)$type,(string)$miqaat_name,(string)($row['assigned_to']??'')];
      foreach($asgns as $ac)$hay_parts[]=(string)($ac['assign_type']??'');
      $hay=strtolower(trim(implode(' ',$hay_parts)));
      $basic=preg_replace('/\s+/',' ',$hay);
      $letters=preg_replace(['/a{2,}/','/i{2,}/','/y{2,}/'],['a','i','y'],preg_replace('/[^a-z]/','', $basic));
      $is_fnn=strpos($basic,'fnn')!==false||(strpos($basic,'fala')!==false&&(strpos($basic,'niyaz')!==false||strpos($basic,'niaz')!==false))||(strpos($letters,'fala')!==false&&(strpos($letters,'niyaz')!==false||strpos($letters,'niaz')!==false));
      if($is_fnn)$summary_fnn++;
      if(strtolower($type)!=='ladies')$summary_miqaat_days_excl_ladies++;
      $ri=false;$rg=false;
      foreach($asgns as $as){$t=strtolower($as['assign_type']??'');if($t==='individual')$ri=true;elseif($t==='group')$rg=true;}
      if($ri)$summary_individual++;if($rg)$summary_group++;
    }
  }
}
$summary_sundays_or_utlal=$summary_holidays_excl_sundays+$summary_sundays_without_miqaat_thaali;

/* ── Detect active filters for chip display ── */
$active_filters=[];
if(!empty($hijri_month_id)&&$hijri_month_id!==-1){
  $lbl_map=['-3'=>'Last Year','-2'=>'Next Year'];
  if(isset($lbl_map[$hijri_month_id])){$active_filters['hijri_month']=['label'=>'Period','val'=>$lbl_map[$hijri_month_id]];}
  elseif(is_numeric($hijri_month_id)&&$hijri_month_id>0){
    // find month name
    $mn='Month '.$hijri_month_id;
    if(!empty($hijri_months)){foreach($hijri_months as $hm){if($hm['id']==$hijri_month_id){$mn=$hm['hijri_month'];break;}}}
    $active_filters['hijri_month']=['label'=>'Month','val'=>$mn];
  }
}
if(!empty($member_name_filter))$active_filters['member_name_filter']=['label'=>'Search','val'=>$member_name_filter];
if(!empty($assignment_filter))$active_filters['assignment_filter']=['label'=>'Assignment','val'=>ucfirst($assignment_filter).' Only'];
if(!empty($miqaat_type))$active_filters['miqaat_type']=['label'=>'Type','val'=>$miqaat_type];

/* ── Helpers ── */
function fmb_row_class($type,$is_sunday){
  if($is_sunday)return'row-sunday';
  $t=strtolower(trim($type));
  if(str_contains($t,'ashara'))return'row-ashara';
  if(str_contains($t,'ladies'))return'row-ladies';
  if($t==='thaali')return'row-thaali';
  if($t==='both')return'row-both';
  if($t==='general')return'row-general';
  if(empty($t))return'row-holiday';
  return'row-miqaat';
}
function fmb_badge($type){
  $t=strtolower(trim($type));
  $cls=match(true){$t==='thaali'=>'tb-thaali',$t==='both'=>'tb-both',str_contains($t,'ladies')=>'tb-ladies',str_contains($t,'ashara')=>'tb-ashara',$t==='general'=>'tb-general',empty($t)=>'tb-holiday',default=>'tb-miqaat'};
  return"<span class='tbadge $cls'>".htmlspecialchars($type?:'—',ENT_QUOTES)."</span>";
}
?>

<div id="fmbApp" class="margintopcontainer">

  <!-- Topbar -->
  <div class="fmb-topbar">
    <a href="<?php echo $active_controller?>" class="fmb-back"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="fmb-heading"><i class="fa fa-calendar" style="margin-right:6px;opacity:.7"></i>FMB Calendar &mdash; <?php echo htmlspecialchars($hijri_year,ENT_QUOTES)?>H</div>
    <div style="width:70px"></div>
  </div>

  <!-- Filter card -->
  <div class="fmb-fc">
    <div class="fmb-fc-head">
      <div class="fmb-fc-title"><i class="fa fa-sliders"></i> Filters</div>
      <div class="fmb-fc-hint">Filters apply automatically on change</div>
    </div>
    <div class="fmb-fc-body">
      <form method="post" action="<?php echo base_url('common/fmbcalendar?from='.$from)?>" id="fmbFilterForm">
        <div class="fmb-frow">

          <!-- Month / Year -->
          <div class="fmb-fg" style="max-width:180px">
            <label class="fmb-lbl">Month / Year</label>
            <select name="hijri_month" id="fmb-month" class="fmb-sel">
              <option value="">All (Current Year)</option>
              <option value="-3" <?php echo(($hijri_month_id??0)==-3)?'selected':''?>>Last Year</option>
              <option value="-1" <?php echo(($hijri_month_id??'')==-1||($hijri_month_id??'')==='−1')?'selected':''?>>Current Year</option>
              <?php if(!empty($hijri_months))foreach($hijri_months as $hm):?>
              <option value="<?php echo $hm['id']?>" <?php echo(isset($hijri_month_id)&&$hm['id']==$hijri_month_id)?'selected':''?>><?php echo htmlspecialchars($hm['hijri_month'],ENT_QUOTES)?></option>
              <?php endforeach?>
              <option value="-2" <?php echo(($hijri_month_id??0)==-2)?'selected':''?>>Next Year</option>
            </select>
          </div>

          <!-- Miqaat Type -->
          <div class="fmb-fg" style="max-width:160px">
            <label class="fmb-lbl">Miqaat Type</label>
            <select id="fmb-miqaat-type" name="miqaat_type" class="fmb-sel">
              <option value="">All Types</option>
              <?php if(!empty($miqaat_types))foreach($miqaat_types as $mtype):?>
              <option value="<?php echo htmlspecialchars($mtype,ENT_QUOTES)?>" <?php echo(($miqaat_type??'')===$mtype)?'selected':''?>><?php echo htmlspecialchars($mtype,ENT_QUOTES)?></option>
              <?php endforeach?>
              <option value="Thaali" <?php echo(($miqaat_type??'')==='Thaali')?'selected':''?>>Thaali</option>
            </select>
          </div>

          <!-- Assignment -->
          <div class="fmb-fg" style="max-width:180px">
            <label class="fmb-lbl">Assignment Status</label>
            <select id="fmb-assignment" name="assignment_filter" class="fmb-sel">
              <option value="">All (Assigned + Unassigned)</option>
              <option value="assigned" <?php echo(($assignment_filter??'')==='assigned')?'selected':''?>>Assigned Only</option>
              <option value="unassigned" <?php echo(($assignment_filter??'')==='unassigned')?'selected':''?>>Unassigned Only</option>
            </select>
          </div>

          <!-- Search (member/group/ITS) — submit on Enter -->
          <div class="fmb-fg fmb-search-wrap">
            <label class="fmb-lbl">Search Member / Group / ITS</label>
            <i class="fa fa-search s-ico"></i>
            <input type="text" class="fmb-inp" name="member_name_filter" id="fmb-search"
              placeholder="Type name, group or ITS ID…"
              value="<?php echo htmlspecialchars($member_name_filter??'',ENT_QUOTES)?>">
            <button type="button" class="s-clr" id="fmbSearchClr" title="Clear search">&#x2715;</button>
          </div>

          <!-- Clear all -->
          <div class="fmb-fg" style="flex:0 0 auto;min-width:auto;align-items:flex-end">
            <label class="fmb-lbl" style="visibility:hidden">x</label>
            <a href="<?php echo base_url("common/fmbcalendar?from=$from")?>" class="fmb-clear-btn">
              <i class="fa fa-times"></i> Clear All
            </a>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- Active filter chips -->
  <?php if(!empty($active_filters)): ?>
  <div class="fmb-chips">
    <?php foreach($active_filters as $key=>$af): ?>
    <span class="fmb-chip">
      <strong><?php echo htmlspecialchars($af['label'],ENT_QUOTES)?></strong>: <?php echo htmlspecialchars($af['val'],ENT_QUOTES)?>
    </span>
    <?php endforeach ?>
  </div>
  <?php endif ?>

  <!-- Stats cards — display only -->
  <div class="fmb-stats">
    <div class="fmb-scard sc-general">
      <div class="sc-ico"><i class="fa fa-calendar"></i></div>
      <div class="sc-val"><?php echo(int)$summary_miqaat_days_excl_ladies?></div>
      <div class="sc-lbl">Total Miqaat Days</div>
    </div>
    <div class="fmb-scard sc-thaali">
      <div class="sc-ico"><i class="fa fa-cutlery"></i></div>
      <div class="sc-val"><?php echo(int)$summary_thaali_days?></div>
      <div class="sc-lbl">Total Thaali Days</div>
    </div>
    <div class="fmb-scard sc-holiday">
      <div class="sc-ico"><i class="fa fa-sun-o"></i></div>
      <div class="sc-val"><?php echo $summary_sundays_or_utlal?></div>
      <div class="sc-lbl">Sundays + Utlat</div>
    </div>
    <div class="fmb-scard sc-individual">
      <div class="sc-ico"><i class="fa fa-user"></i></div>
      <div class="sc-val"><?php echo(int)$summary_individual?></div>
      <div class="sc-lbl">Individual Niyaaz</div>
    </div>
    <div class="fmb-scard sc-group">
      <div class="sc-ico"><i class="fa fa-users"></i></div>
      <div class="sc-val"><?php echo(int)$summary_group?></div>
      <div class="sc-lbl">Group Niyaaz</div>
    </div>
    <div class="fmb-scard sc-fnn">
      <div class="sc-ico"><i class="fa fa-star"></i></div>
      <div class="sc-val"><?php echo(int)$summary_fnn?></div>
      <div class="sc-lbl">Fala ni Niyaaz</div>
    </div>
  </div>

  <!-- Table card -->
  <div class="fmb-tcard">
    <div class="fmb-legend">
      <span class="leg-lbl">Row Colors:</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fffdf4;border-color:#b8860b"></span>Miqaat</span>
      <span class="leg-item"><span class="leg-dot" style="background:#f0f9ff;border-color:#0891b2"></span>Thaali</span>
      <span class="leg-item"><span class="leg-dot" style="background:#f0fdf5;border-color:#10b981"></span>Both</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fff5f8;border-color:#ec4899"></span>Ladies</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fffbf0;border-color:#f59e0b"></span>Ashara</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fff2f2;border-color:#ef4444"></span>Sunday</span>
      <span class="leg-item"><span class="leg-dot" style="background:#f9f9f7;border-color:#d1d5db"></span>Holiday</span>
      <span class="fmb-row-count" id="fmbRowCount">— rows</span>
    </div>

    <div class="fmb-tscroll">
      <table class="fmb-tbl" id="fmbTable">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th class="sortable" data-col="eng" style="min-width:95px">Eng Date <span class="si"></span></th>
            <th class="sortable" data-col="hijri" style="min-width:210px">Hijri Date <span class="si"></span></th>
            <th class="sortable" data-col="day" style="min-width:80px;white-space:nowrap">Day <span class="si"></span></th>
            <th style="min-width:90px">Type</th>
            <th style="min-width:140px">Miqaat Name</th>
            <th style="min-width:180px">Assigned To</th>
            <th style="min-width:170px">Thaali Menu</th>
          </tr>
        </thead>
        <tbody id="fmbTbody">
          <?php
          if(isset($calendar)){
            if(empty($calendar)){
              $parts=[];
              if(!empty($miqaat_type))$parts[]='Type: '.htmlspecialchars($miqaat_type);
              if(!empty($member_name_filter))$parts[]='Search: "'.htmlspecialchars($member_name_filter).'"';
              if(!empty($assignment_filter))$parts[]='Assignment: '.ucfirst(htmlspecialchars($assignment_filter)).' only';
              echo'<tr><td colspan="8"><div class="fmb-no-results"><i class="fa fa-search"></i><p>No rows match the selected filters'.($parts?' — '.implode(', ',$parts):'').'.</p></div></td></tr>';
            }

            $grouped=[];
            foreach($calendar as $row){$d=$row['greg_date']??'';$grouped[$d][]=$row;}
            ksort($grouped);
            $sno=1;$last_month='';

            foreach($grouped as $date=>$rows_for_date){
              $first=$rows_for_date[0];
              $eng_sort=$date;
              $eng_date=date('d M Y',strtotime($date));
              $day_name=date('l',strtotime($date));
              $is_sunday=($day_name==='Sunday');
              $hijri_date=$first['hijri_date']??'';
              $hp=explode('-',$hijri_date);
              $h_month=$hp[1]??'';$h_year=$hp[2]??'';
              $month_name=$first['hijri_month_name']??'';
              $h_sort=(count($hp)===3)?sprintf('%04d-%02d-%02d',(int)$hp[2],(int)$hp[1],(int)$hp[0]):'';

              $allEmpty=true;
              foreach($rows_for_date as $rc){if(!empty($rc['miqaat_type'])||!empty($rc['miqaat_name'])||!empty($rc['menu_items'])){$allEmpty=false;break;}}

              if($h_month!==$last_month){
                $last_month=$h_month;
                echo'<tr class="month-hdr" data-hijri-month="'.htmlspecialchars($h_month,ENT_QUOTES).'" data-hijri-month-name="'.htmlspecialchars($month_name,ENT_QUOTES).'" data-hijri-year="'.htmlspecialchars($h_year,ENT_QUOTES).'">';
                echo'<td colspan="8"><i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i><strong>'.htmlspecialchars($month_name,ENT_QUOTES).'</strong>&ensp;&mdash;&ensp;'.htmlspecialchars($h_year,ENT_QUOTES).'H</td></tr>';
              }

              $types_h=$names_h=$asgn_h=$menu_h=[];
              $primary_type='';
              foreach($rows_for_date as $r){
                $type=$r['miqaat_type']??'';if(!$primary_type)$primary_type=$type;
                $mname=$r['miqaat_name']??'';
                $asgns=$r['assignments']??[];
                $menu_raw=$r['menu_items']??[];
                $ato=$r['assigned_to']??'';

                $types_h[]=fmb_badge($type);
                $names_h[]='<div class="fmb-entry">'.htmlspecialchars($mname,ENT_QUOTES).'</div>';

                if(!empty($asgns)){
                  $ajson=htmlspecialchars(json_encode($asgns),ENT_NOQUOTES,'UTF-8');
                  $asgn_h[]='<div class="fmb-entry"><a href="#" class="assign-link show-adet" data-assignments=\''.str_replace("'","&#39;",$ajson).'\'><i class="fa fa-users" style="font-size:.62rem;margin-right:3px;opacity:.7"></i>'.htmlspecialchars($ato,ENT_QUOTES).'</a></div>';
                } else {
                  $asgn_h[]='<div class="fmb-entry" style="color:#9c8f7a;font-size:.74rem">'.htmlspecialchars($ato,ENT_QUOTES).'</div>';
                }

                if(!empty($menu_raw)){
                  $items=is_array($menu_raw)?$menu_raw:array_map('trim',explode(',',$menu_raw));
                  $pills='<div class="fmb-entry" style="line-height:1.9">';
                  foreach($items as $mi)if(trim($mi))$pills.='<span class="menu-item">'.htmlspecialchars(trim($mi),ENT_QUOTES).'</span>';
                  $pills.='</div>';$menu_h[]=$pills;
                } else {
                  $menu_h[]='<div class="fmb-entry" style="color:#d1d5db;font-size:.7rem">—</div>';
                }
              }

              $row_cls=fmb_row_class($primary_type,$is_sunday);
              $day_cls=$is_sunday?'day-sun':(strtolower($day_name)==='friday'?'day-fri':'');
              $sep='<hr class="entry-sep">';

              echo '<tr class="'.htmlspecialchars($row_cls,ENT_QUOTES).'"'
                .' data-hijri-month="'.htmlspecialchars($h_month,ENT_QUOTES).'"'
                .' data-hijri-month-name="'.htmlspecialchars($month_name,ENT_QUOTES).'"'
                .' data-hijri-year="'.htmlspecialchars($h_year,ENT_QUOTES).'"'
                .'>';
              echo '<td><span class="fmb-sno">'.$sno++.'</span></td>';
              echo '<td data-sort-value="'.htmlspecialchars($eng_sort,ENT_QUOTES).'" style="font-size:.76rem;font-weight:600;white-space:nowrap">'.$eng_date.'</td>';
              echo '<td data-sort-value="'.htmlspecialchars($h_sort,ENT_QUOTES).'" style="white-space:nowrap;font-size:.76rem">'.htmlspecialchars($first['hijri_date_with_month']??$hijri_date,ENT_QUOTES).'</td>';
              echo '<td class="'.$day_cls.'" style="white-space:nowrap;font-weight:'.($is_sunday?700:500).'">'.$day_name.'</td>';

              if($allEmpty){
                echo '<td><span class="holiday-tag"><i class="fa fa-moon-o"></i> Holiday</span></td>';
                echo '<td style="color:#d1d5db;font-size:.7rem">—</td>';
                echo '<td style="color:#d1d5db;font-size:.7rem">—</td>';
                echo '<td style="color:#d1d5db;font-size:.7rem">—</td>';
              } else {
                echo '<td>'.implode($sep,$types_h).'</td>';
                echo '<td class="col-name">'.implode($sep,$names_h).'</td>';
                echo '<td class="col-assigned">'.implode($sep,$asgn_h).'</td>';
                echo '<td class="col-menu">'.implode($sep,$menu_h).'</td>';
              }
              echo '</tr>';
            }
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="fmb-tfoot">
      <span id="fmbCnt" style="background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:800">— rows</span>
      <span>FMB Calendar &mdash; <?php echo htmlspecialchars($hijri_year,ENT_QUOTES)?>H</span>
    </div>
  </div>

</div><!-- /#fmbApp -->

<!-- Assignment modal -->
<div class="fmb-ov" id="modAdet">
  <div class="fmb-modal">
    <div class="fmb-mhd">
      <span class="fmb-mtit"><i class="fa fa-users" style="color:#b8860b"></i> Assignment Details</span>
      <button class="fmb-mclose" onclick="fmbCM('modAdet')">&#x2715;</button>
    </div>
    <div class="fmb-mbody" id="adetBody"></div>
    <div class="fmb-mft"><button class="fmb-btn-c" onclick="fmbCM('modAdet')">Close</button></div>
  </div>
</div>

<!-- Manage day modal -->
<div class="fmb-ov" id="modManage">
  <div class="fmb-modal" style="max-width:360px">
    <div class="fmb-mhd">
      <span class="fmb-mtit"><i class="fa fa-calendar" style="color:#b8860b"></i> Manage Day</span>
      <button class="fmb-mclose" onclick="fmbCM('modManage')">&#x2715;</button>
    </div>
    <div class="fmb-mbody">
      <form id="manageDayForm" method="post" action="<?php echo base_url('common/update_day')?>">
        <input type="hidden" name="greg_date" id="greg_date">
        <label class="fmb-lbl2">Day Type</label>
        <select name="day_type" id="day_type" class="fmb-sel2">
          <option value="">-- Select --</option>
          <option value="Holiday">Holiday</option>
          <option value="Thaali">Thaali Only</option>
          <option value="Miqaat">Miqaat Only</option>
          <option value="Both">Thaali + Miqaat</option>
        </select>
      </form>
    </div>
    <div class="fmb-mft">
      <button class="fmb-btn-c" onclick="fmbCM('modManage')">Cancel</button>
      <button class="fmb-btn-ok" onclick="document.getElementById('manageDayForm').submit()">Submit</button>
    </div>
  </div>
</div>

<script>
/* ── Modal helpers ── */
function fmbOM(id){document.getElementById(id).classList.add('open')}
function fmbCM(id){document.getElementById(id).classList.remove('open')}
document.addEventListener('click',function(e){if(e.target.classList.contains('fmb-ov'))e.target.classList.remove('open')});
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('.fmb-ov.open').forEach(function(m){m.classList.remove('open')})});
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}

/* ── Assignment detail modal ── */
document.addEventListener('click',function(e){
  var a=e.target.closest('.show-adet');if(!a)return;e.preventDefault();
  var raw=a.getAttribute('data-assignments');var asgns=[];
  try{asgns=JSON.parse(raw||'[]')}catch(err){asgns=[]}
  var html='';
  if(asgns&&asgns.length){
    asgns.forEach(function(asgn){
      if(asgn.assign_type==='Individual'){
        html+='<div class="adet-row"><div class="ak"><i class="fa fa-user" style="margin-right:4px"></i>Individual</div><div class="av">'+esc(asgn.member_name||'')+'</div><div class="asub"><i class="fa fa-phone" style="margin-right:3px;opacity:.5"></i>'+(asgn.member_mobile||'N/A')+'</div></div>';
      } else if(asgn.assign_type==='Group'){
        html+='<div class="adet-row"><div class="ak"><i class="fa fa-users" style="margin-right:4px"></i>Group / Sanstha</div><div class="av">'+esc(asgn.group_name||'')+'</div>';
        html+='<div class="asub"><strong>Leader:</strong> '+esc(asgn.group_leader_name||'')+' &mdash; '+(asgn.group_leader_mobile||'N/A')+'</div>';
        if(asgn.members&&asgn.members.length)html+='<div class="asub"><strong>Co-leader:</strong> '+esc(asgn.members[0].name||'')+' &mdash; '+(asgn.members[0].mobile||'N/A')+'</div>';
        html+='</div>';
      } else {
        html+='<div class="adet-row"><div class="ak">'+esc(asgn.assign_type||'Assignment')+'</div><div class="av">'+esc(asgn.member_name||asgn.group_name||'—')+'</div></div>';
      }
    });
  } else {html='<p style="color:#9c8f7a;font-size:.82rem;padding:8px 0">No assignment details available.</p>'}
  document.getElementById('adetBody').innerHTML=html;
  fmbOM('modAdet');
});

/* ── Manage day ── */
document.addEventListener('click',function(e){
  var btn=e.target.closest('.manage-day-btn');if(!btn)return;
  document.getElementById('greg_date').value=btn.dataset.date||'';
  document.getElementById('day_type').value='';
  fmbOM('modManage');
});

/* ── Auto-submit on dropdown change ── */
document.getElementById('fmb-month').addEventListener('change',function(){document.getElementById('fmbFilterForm').submit()});
document.getElementById('fmb-miqaat-type').addEventListener('change',function(){document.getElementById('fmbFilterForm').submit()});
document.getElementById('fmb-assignment').addEventListener('change',function(){document.getElementById('fmbFilterForm').submit()});

/* ── Search: submit on Enter key + show/hide clear button ── */
var searchInput=document.getElementById('fmb-search');
var searchClr=document.getElementById('fmbSearchClr');
function updateSearchClr(){searchClr.classList.toggle('vis',searchInput.value.length>0)}
searchInput.addEventListener('input',updateSearchClr);
searchInput.addEventListener('keydown',function(e){
  if(e.key==='Enter'){e.preventDefault();document.getElementById('fmbFilterForm').submit()}
});
searchClr.addEventListener('click',function(){searchInput.value='';updateSearchClr();document.getElementById('fmbFilterForm').submit()});
updateSearchClr();

/* ── Column sort (keeps month headers grouped) ── */
(function(){
  var tbody=document.getElementById('fmbTbody');
  var state={col:null,dir:'asc'};
  var colIdx={eng:1,hijri:2,day:3};
  document.querySelectorAll('#fmbTable thead th.sortable').forEach(function(th){
    th.addEventListener('click',function(){
      var col=th.dataset.col;
      state.dir=(state.col===col&&state.dir==='asc')?'desc':'asc';
      state.col=col;
      document.querySelectorAll('#fmbTable thead th.sortable').forEach(function(h){h.classList.remove('asc','desc')});
      th.classList.add(state.dir);
      sortRows(colIdx[col],state.dir);
    });
  });
  function getCellVal(tr,idx){var c=tr.querySelectorAll('td');return c[idx]?(c[idx].getAttribute('data-sort-value')||c[idx].textContent.trim()):'';}
  function norm(v){if(/^\d{4}-\d{2}-\d{2}$/.test(v))return new Date(v).getTime();if(!isNaN(parseFloat(v))&&isFinite(v))return parseFloat(v);return v.toLowerCase()}
  function sortRows(idx,dir){
    var all=Array.from(tbody.querySelectorAll('tr'));
    var mhdrs=all.filter(function(r){return r.classList.contains('month-hdr')});
    var data=all.filter(function(r){return!r.classList.contains('month-hdr')});
    data.sort(function(a,b){
      var va=norm(getCellVal(a,idx)),vb=norm(getCellVal(b,idx));
      return va<vb?(dir==='asc'?-1:1):va>vb?(dir==='asc'?1:-1):0;
    });
    mhdrs.forEach(function(r){r.remove()});
    tbody.innerHTML='';
    var seen=new Set();
    data.forEach(function(r){
      var m=r.dataset.hijriMonth,y=r.dataset.hijriYear,mn=r.dataset.hijriMonthName||'';
      var key=m+'-'+y;
      if(!seen.has(key)){
        seen.add(key);
        var hdr=document.createElement('tr');hdr.className='month-hdr';
        hdr.setAttribute('data-hijri-month',m);hdr.setAttribute('data-hijri-month-name',mn);hdr.setAttribute('data-hijri-year',y);
        var td=document.createElement('td');td.colSpan=8;
        td.innerHTML='<i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i><strong>'+esc(mn)+'</strong>&ensp;&mdash;&ensp;'+esc(y)+'H';
        hdr.appendChild(td);
        tbody.appendChild(hdr);
      }
      tbody.appendChild(r);
    });
    renumber();
  }
})();

/* ── Row count ── */
function renumber(){
  var i=1;
  document.querySelectorAll('#fmbTbody tr:not(.month-hdr)').forEach(function(tr){
    var s=tr.querySelector('.fmb-sno');if(s)s.textContent=i++;
  });
}
function updateCount(){
  var n=document.querySelectorAll('#fmbTbody tr:not(.month-hdr)').length;
  var txt=n+' row'+(n!==1?'s':'');
  var a=document.getElementById('fmbCnt'),b=document.getElementById('fmbRowCount');
  if(a)a.textContent=txt;if(b)b.textContent=txt;
}
updateCount();
</script>