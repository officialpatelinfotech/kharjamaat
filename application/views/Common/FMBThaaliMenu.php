<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>

<style>
/* ═══════════════════════════════════════════════════
   FMB THAALI MENU — gold design system
   Scoped to #fmtApp
═══════════════════════════════════════════════════ */
#fmtApp{font-family:'Plus Jakarta Sans',sans-serif;color:#1a1610;background:#faf7f0;min-height:calc(100vh - 57px);padding:14px 16px}
#fmtApp *{box-sizing:border-box}

/* ── Topbar ── */
#fmtApp .fmt-top{display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap}
#fmtApp .fmt-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s;flex-shrink:0}
#fmtApp .fmt-back:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#fmtApp .fmt-heading{font-size:.98rem;font-weight:800;color:#b8860b;flex:1;text-align:center;letter-spacing:.3px}
#fmtApp .fmt-actions{display:flex;align-items:center;gap:7px;flex-shrink:0}
#fmtApp .fmt-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 13px;border-radius:8px;font-size:.74rem;font-weight:700;text-decoration:none;border:1.5px solid;cursor:pointer;transition:all .15s;white-space:nowrap;font-family:'Plus Jakarta Sans',sans-serif}
#fmtApp .fmt-btn-gold{background:#b8860b;border-color:#b8860b;color:#fff}
#fmtApp .fmt-btn-gold:hover{opacity:.87;color:#fff;text-decoration:none}
#fmtApp .fmt-btn-outline{background:#fff;border-color:#e8e0cc;color:#5a5244}
#fmtApp .fmt-btn-outline:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}

/* ── Filter card ── */
#fmtApp .fmt-fc{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;margin-bottom:12px}
#fmtApp .fmt-fc-head{background:linear-gradient(135deg,#78520a,#b8860b);padding:9px 14px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px}
#fmtApp .fmt-fc-title{font-size:.78rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:6px}
#fmtApp .fmt-fc-hint{font-size:.66rem;color:rgba(255,255,255,.7);font-style:italic}
#fmtApp .fmt-fc-body{padding:11px 14px}
#fmtApp .fmt-frow{display:flex;align-items:flex-end;gap:7px;flex-wrap:wrap}
#fmtApp .fmt-fg{display:flex;flex-direction:column;gap:3px;flex:1;min-width:130px}
#fmtApp .fmt-lbl{font-size:.61rem;font-weight:800;color:#5a5244;text-transform:uppercase;letter-spacing:.5px}
#fmtApp .fmt-sel,#fmtApp .fmt-inp{height:32px;padding:0 9px;border:1.5px solid #e8e0cc;border-radius:7px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:#1a1610;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
#fmtApp .fmt-sel:focus,#fmtApp .fmt-inp:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
#fmtApp .fmt-clr{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 11px;border-radius:7px;background:#f7f4ec;border:1.5px solid #e8e0cc;color:#5a5244;font-size:.74rem;font-weight:700;cursor:pointer;text-decoration:none;white-space:nowrap;transition:all .15s}
#fmtApp .fmt-clr:hover{background:#fef2f2;border-color:#b91c1c;color:#b91c1c;text-decoration:none}

/* ── Stats row ── */
#fmtApp .fmt-stats{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
#fmtApp .fmt-stat{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #e8e0cc;border-radius:10px;padding:8px 14px;box-shadow:0 1px 3px rgba(0,0,0,.05)}
#fmtApp .fmt-stat .fs-ico{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0}
#fmtApp .fmt-stat .fs-ico.gold{background:#f5e9c0;color:#b8860b}
#fmtApp .fmt-stat .fs-ico.green{background:#eaf4ee;color:#1a6645}
#fmtApp .fmt-stat .fs-ico.blue{background:#eff6ff;color:#1d4ed8}
#fmtApp .fmt-stat .fs-body{}
#fmtApp .fmt-stat .fs-val{font-size:.9rem;font-weight:800;color:#1a1610;line-height:1}
#fmtApp .fmt-stat .fs-lbl{font-size:.6rem;font-weight:700;color:#9c8f7a;text-transform:uppercase;letter-spacing:.4px;margin-top:2px}

/* ── Table card ── */
#fmtApp .fmt-tcard{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden}
#fmtApp .fmt-thead{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid #f0ece0;background:#f7f4ec;flex-wrap:wrap;gap:7px}
#fmtApp .fmt-thead-title{font-size:.78rem;font-weight:800;color:#5a5244;display:flex;align-items:center;gap:6px}
#fmtApp .fmt-thead-title i{color:#b8860b}
#fmtApp .fmt-print-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.72rem;font-weight:700;cursor:pointer;transition:all .15s;font-family:'Plus Jakarta Sans',sans-serif}
#fmtApp .fmt-print-btn:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b}

#fmtApp .fmt-tscroll{overflow-x:auto;overflow-y:visible}
#fmtApp .fmt-tscroll::-webkit-scrollbar{height:4px}
#fmtApp .fmt-tscroll::-webkit-scrollbar-thumb{background:#e8e0cc;border-radius:10px}

#fmtApp table.fmt-tbl{width:100%;border-collapse:collapse;font-size:.78rem;min-width:700px}
#fmtApp table.fmt-tbl thead th{background:linear-gradient(to bottom,#f7f4ec,#ede8da);padding:9px 12px;font-size:.59rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:#9c8f7a;border-bottom:2px solid #e8e0cc;white-space:nowrap;text-align:left;user-select:none}
#fmtApp table.fmt-tbl thead th.sortable{cursor:pointer}
#fmtApp table.fmt-tbl thead th.sortable:hover{color:#b8860b}
#fmtApp table.fmt-tbl thead th .si{margin-left:3px;opacity:.3;font-size:.56rem}
#fmtApp table.fmt-tbl thead th.asc .si::after{content:'▲';opacity:1;color:#b8860b}
#fmtApp table.fmt-tbl thead th.desc .si::after{content:'▼';opacity:1;color:#b8860b}
#fmtApp table.fmt-tbl thead th:not(.asc):not(.desc) .si::after{content:'⇅'}

#fmtApp table.fmt-tbl tbody tr{border-bottom:1px solid #f0ece0;transition:background .1s}
#fmtApp table.fmt-tbl tbody tr:hover td{background:#fdf9ef!important}
#fmtApp table.fmt-tbl td{padding:9px 12px;vertical-align:middle;color:#1a1610}

/* Month header row */
#fmtApp tr.month-hdr td{background:linear-gradient(90deg,#f5e9c0,#fdf5d6)!important;font-weight:800;font-size:.75rem;color:#b8860b;border-top:2px solid rgba(184,134,11,.22);border-bottom:1px solid rgba(184,134,11,.15);padding:7px 12px}

/* Sunday row */
#fmtApp tr.row-sun td{background:#fff2f2}
#fmtApp tr.row-sun:hover td{background:#ffe4e4!important}

/* No-menu row */
#fmtApp tr.row-empty td{background:#f9f9f7;color:#9c8f7a;font-style:italic}
#fmtApp tr.row-empty:hover td{background:#f2f2f0!important}

/* S.No badge */
#fmtApp .fmt-sno{width:24px;height:24px;border-radius:50%;background:#f5e9c0;color:#b8860b;font-weight:800;font-size:.62rem;display:inline-flex;align-items:center;justify-content:center}

/* Menu item pill */
#fmtApp .menu-pill{display:inline-block;background:#eaf4ee;color:#1a6645;border:1px solid rgba(26,102,69,.18);border-radius:12px;padding:1px 7px;font-size:.65rem;font-weight:600;margin:1px 2px 1px 0;white-space:nowrap}

/* Action buttons */
#fmtApp .act-btn{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;border:1.5px solid;font-size:.74rem;text-decoration:none;cursor:pointer;transition:all .14s;background:transparent;font-family:'Plus Jakarta Sans',sans-serif}
#fmtApp .act-edit{border-color:#e8e0cc;color:#5a5244}
#fmtApp .act-edit:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#fmtApp .act-del{border-color:#fecaca;color:#b91c1c;background:transparent}
#fmtApp .act-del:hover{background:#fef2f2;border-color:#b91c1c}
#fmtApp .act-add{border-color:#e8e0cc;color:#1a6645}
#fmtApp .act-add:hover{background:#eaf4ee;border-color:#1a6645;text-decoration:none}
#fmtApp .act-sep{width:1px;height:16px;background:#e8e0cc;display:inline-block;margin:0 3px;vertical-align:middle}

/* Thaali Day badge */
#fmtApp .td-yes{display:inline-flex;align-items:center;gap:4px;background:#d1fae5;color:#065f46;border:1px solid rgba(6,95,70,.2);border-radius:10px;padding:2px 8px;font-size:.65rem;font-weight:700;white-space:nowrap}
#fmtApp .td-no{display:inline-flex;align-items:center;gap:4px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;padding:2px 8px;font-size:.65rem;font-weight:700;white-space:nowrap}

/* Toggle Thaali Day button */
#fmtApp .act-thaali-on{border-color:#bbf7d0;color:#065f46;background:#ecfdf5}
#fmtApp .act-thaali-on:hover{background:#d1fae5;border-color:#059669}
#fmtApp .act-thaali-off{border-color:#e5e7eb;color:#9ca3af;background:#f9fafb}
#fmtApp .act-thaali-off:hover{background:#fef2f2;border-color:#f87171;color:#b91c1c}

/* Footer */
#fmtApp .fmt-foot{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid #f0ece0;background:#f7f4ec;font-size:.68rem;color:#9c8f7a;flex-wrap:wrap;gap:6px}
#fmtApp .fmt-cnt{background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.64rem;font-weight:800}

/* Print overrides */
@media print{
  #fmtApp .fmt-act-col{display:none!important}
  #fmtApp .fmt-print-btn,#fmtApp .fmt-top,#fmtApp .fmt-fc,#fmtApp .fmt-stats,#fmtApp .fmt-thead{display:none!important}
  #fmtApp .fmt-tcard{border:none;box-shadow:none;border-radius:0}
  #fmtApp .fmt-tscroll{overflow:visible}
  body,#fmtApp{padding:0!important;margin:0!important;background:#fff!important}
}

/* Responsive */
@media(max-width:640px){
  #fmtApp{padding:10px}
  #fmtApp .fmt-frow{flex-direction:column}
  #fmtApp .fmt-fg{min-width:100%}
  #fmtApp .fmt-actions{width:100%;justify-content:flex-end}
  #fmtApp .fmt-stats{gap:6px}
  #fmtApp table.fmt-tbl{min-width:560px}
}
</style>

<?php
$rowCount = !empty($menu) ? count($menu) : 0;
$from_val = htmlspecialchars((string)($from ?? ''), ENT_QUOTES);
$hijri_month_id_val = isset($hijri_month_id) && $hijri_month_id !== '' ? htmlspecialchars((string)$hijri_month_id, ENT_QUOTES) : '';
$assigned_filter_val = isset($assigned_filter) ? htmlspecialchars((string)$assigned_filter, ENT_QUOTES) : '';
$back_href = isset($from) ? base_url($from) : base_url('anjuman/fmbthaali');

/* Build add/edit links with param persistence */
$param_tail = '?from='.urlencode((string)($from??''))
  .($hijri_month_id_val!==''?'&hijri_month='.urlencode($hijri_month_id_val):'')
  .(!empty($assigned_filter_val)?'&assigned_filter='.urlencode($assigned_filter_val):'');
?>

<div id="fmtApp" class="margintopcontainer">

  <!-- ① TOP BAR -->
  <div class="fmt-top">
    <a href="<?php echo $back_href?>" class="fmt-back"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="fmt-heading"><i class="fa fa-cutlery" style="margin-right:6px;opacity:.7"></i>FMB Thaali Menu</div>
    <?php if (!in_array((int)$_SESSION['user']['role'], [9, 12], true)): ?>
    <div class="fmt-actions">
      <a href="<?php echo base_url('common/add_menu_item?from='.$from_val)?>" class="fmt-btn fmt-btn-outline">
        <i class="fa fa-pencil"></i> Edit Items
      </a>
      <a href="<?php echo base_url('common/createmenu'.$param_tail)?>" class="fmt-btn fmt-btn-gold">
        <i class="fa fa-plus"></i> Add Menu
      </a>
    </div>
    <?php endif; ?>
  </div>

  <!-- ② FILTER CARD -->
  <div class="fmt-fc">
    <div class="fmt-fc-head">
      <div class="fmt-fc-title"><i class="fa fa-sliders"></i> Filters</div>
      <div class="fmt-fc-hint">Month changes apply automatically</div>
    </div>
    <div class="fmt-fc-body">
      <form method="get" action="<?php echo base_url('common/fmbthaalimenu')?>" id="filter-form">
        <input type="hidden" name="from" value="<?php echo $from_val?>">
        <div class="fmt-frow">

          <div class="fmt-fg" style="max-width:200px">
            <label class="fmt-lbl">Month / Year</label>
            <select name="hijri_month" id="hijri-month" class="fmt-sel">
              <option value="">Select Month / Year</option>
              <option value="-3" <?php echo($hijri_month_id_val=='-3')?'selected':''?>>Last Year</option>
              <option value="-1" <?php echo($hijri_month_id_val=='-1')?'selected':''?>>Current Year</option>
              <?php if(isset($hijri_months)): foreach($hijri_months as $hm): ?>
              <option value="<?php echo htmlspecialchars($hm['id'],ENT_QUOTES)?>"
                <?php echo(isset($hijri_month_id)&&$hm['id']==$hijri_month_id)?'selected':''?>>
                <?php echo htmlspecialchars($hm['hijri_month'],ENT_QUOTES)?>
              </option>
              <?php endforeach; endif ?>
              <option value="-2" <?php echo($hijri_month_id_val=='-2')?'selected':''?>>Next Year</option>
            </select>
          </div>

          <div class="fmt-fg">
            <label class="fmt-lbl">Search Name / ITS</label>
            <input type="text" id="filter-assigned" name="assigned_filter" class="fmt-inp"
              placeholder="Filter by name or ITS ID…"
              autocomplete="off"
              value="<?php echo $assigned_filter_val?>">
          </div>

          <div style="display:flex;align-items:flex-end;gap:6px">
            <a href="<?php echo base_url('common/fmbthaalimenu?from='.$from_val)?>" class="fmt-clr">
              <i class="fa fa-times"></i> Clear
            </a>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- ③ STATS ROW -->
  <?php if(isset($assigned_members_count)||isset($assigned_days_count)||isset($total_thaali_days_count)||isset($per_day_thaali_cost_amount)):?>
  <div class="fmt-stats">
    <?php if(isset($assigned_members_count)):?>
    <div class="fmt-stat">
      <div class="fs-ico gold"><i class="fa fa-users"></i></div>
      <div class="fs-body">
        <div class="fs-val"><?php echo(int)$assigned_members_count?></div>
        <div class="fs-lbl">Assigned Members</div>
      </div>
    </div>
    <?php endif?>
    <?php if(isset($assigned_days_count)&&isset($total_thaali_days_count)):?>
    <div class="fmt-stat">
      <div class="fs-ico green"><i class="fa fa-calendar-check-o"></i></div>
      <div class="fs-body">
        <div class="fs-val"><?php echo(int)$assigned_days_count?> / <?php echo(int)$total_thaali_days_count?></div>
        <div class="fs-lbl">Assigned Days</div>
      </div>
    </div>
    <?php endif?>
    <?php if(isset($per_day_thaali_cost_amount)&&$per_day_thaali_cost_amount!==null&&(float)$per_day_thaali_cost_amount>0):?>
    <div class="fmt-stat">
      <div class="fs-ico blue"><i class="fa fa-rupee"></i></div>
      <div class="fs-body">
        <div class="fs-val">₹<?php echo number_format((float)$per_day_thaali_cost_amount,0,'.',',')?></div>
        <div class="fs-lbl">Per Day Cost<?php if(!empty($per_day_thaali_cost_fy)):?> &mdash; FY <?php echo htmlspecialchars($per_day_thaali_cost_fy,ENT_QUOTES)?><?php endif?></div>
      </div>
    </div>
    <?php endif?>
  </div>
  <?php endif?>

  <!-- ④ TABLE CARD -->
  <div class="fmt-tcard">
    <div class="fmt-thead">
      <div class="fmt-thead-title">
        <i class="fa fa-table"></i>
        Menu List
      </div>
      <button type="button" id="fmt-print-btn" class="fmt-print-btn">
        <i class="fa fa-print"></i> Print
      </button>
    </div>

    <div class="fmt-tscroll">
      <table class="fmt-tbl" id="fmt-table">
        <thead>
          <tr>
            <th style="width:38px">#</th>
            <th class="sortable" data-col="eng" style="min-width:95px">Eng Date <span class="si"></span></th>
            <th class="sortable" data-col="hijri" style="min-width:170px">Hijri Date <span class="si"></span></th>
            <th class="sortable" data-col="day" style="min-width:80px">Day <span class="si"></span></th>
            <th style="min-width:180px">Menu</th>
            <th style="min-width:150px">Assigned To</th>
            <th style="min-width:95px;text-align:center">Thaali</th>
            <?php if (!in_array((int)$_SESSION['user']['role'], [9, 12], true)): ?>
            <th class="fmt-act-col" style="width:76px">Actions</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody id="fmt-tbody">
          <?php if(!empty($menu)): ?>
          <?php foreach($menu as $key => $item):
            /* Month header */
            $curMonthPart = explode(' ',$item['hijri_date']??'',2);
            $curMonthStr  = $curMonthPart[1] ?? '';
            $prevMonthStr = '';
            if($key>0){ $pp=explode(' ',$menu[$key-1]['hijri_date']??'',2); $prevMonthStr=$pp[1]??''; }
            if($key===0||$curMonthStr!==$prevMonthStr):
          ?>
          <tr class="month-hdr" data-hijri-month-name="<?php echo htmlspecialchars($curMonthStr,ENT_QUOTES)?>">
            <td colspan="<?= in_array((int)$_SESSION['user']['role'], [9, 12], true) ? '7' : '8' ?>">
              <i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i>
              Hijri Month: <strong><?php echo htmlspecialchars($curMonthStr,ENT_QUOTES)?></strong>
            </td>
          </tr>
          <?php endif?>
          <?php
            $dayName = isset($item['date']) ? date('l',strtotime($item['date'])) : '';
            $isSun   = ($dayName==='Sunday');
            $isEmpty = empty($item['items']);
            $rowCls  = $isSun ? 'row-sun' : ($isEmpty ? 'row-empty' : '');
            /* Menu items as pills */
            $menuHtml = '';
            if(!$isEmpty){
              foreach($item['items'] as $mi) $menuHtml.='<span class="menu-pill">'.htmlspecialchars($mi,ENT_QUOTES).'</span>';
            } else {
              $menuHtml='<span style="color:#d1d5db;font-size:.72rem">—</span>';
            }
          ?>
          <tr class="<?php echo htmlspecialchars($rowCls,ENT_QUOTES)?>"
              data-eng-date="<?php echo htmlspecialchars($item['date']??'',ENT_QUOTES)?>"
              data-hijri-date="<?php echo htmlspecialchars($item['hijri_date']??'',ENT_QUOTES)?>"
              data-assigned-its="<?php echo htmlspecialchars($item['assigned_to_its']??'',ENT_QUOTES)?>">
            <td><span class="fmt-sno"><?php echo $key+1?></span></td>
            <td data-sort-value="<?php echo htmlspecialchars($item['date']??'',ENT_QUOTES)?>" style="font-size:.76rem;font-weight:600;white-space:nowrap">
              <?php echo isset($item['date'])?date('d M Y',strtotime($item['date'])):''?>
            </td>
            <td style="font-size:.76rem;white-space:nowrap">
              <?php echo htmlspecialchars($item['hijri_date']??'',ENT_QUOTES)?>
            </td>
            <td style="white-space:nowrap;font-weight:<?php echo $isSun?700:500?>;color:<?php echo $isSun?'#b91c1c':'inherit'?>">
              <?php echo htmlspecialchars($dayName,ENT_QUOTES)?>
            </td>
            <td style="max-width:260px;word-break:break-word;white-space:normal"><?php echo $menuHtml?></td>
            <td style="font-size:.76rem">
              <?php echo!empty($item['assigned_to'])?htmlspecialchars($item['assigned_to'],ENT_QUOTES):''?>
            </td>
            <?php
              $isThaali   = !empty($item['is_thaali_day']);
              $menuDate   = htmlspecialchars($item['date']??'', ENT_QUOTES);
            ?>
            <td style="text-align:center;white-space:nowrap">
              <?php if($isThaali): ?>
                <span class="td-yes"><i class="fa fa-check"></i> Yes</span>
              <?php else: ?>
                <span class="td-no"><i class="fa fa-times"></i> No</span>
              <?php endif; ?>
              <?php if (!in_array((int)$_SESSION['user']['role'], [9, 12], true)): ?>
              <br>
              <button type="button"
                class="act-btn fmt-thaali-toggle <?php echo $isThaali ? 'act-thaali-on' : 'act-thaali-off' ?>"
                data-date="<?php echo $menuDate ?>"
                data-state="<?php echo $isThaali ? '1' : '0' ?>"
                title="<?php echo $isThaali ? 'Mark as Non-Thaali Day' : 'Mark as Thaali Day' ?>"
                style="margin-top:4px;font-size:.65rem;height:22px;width:auto;padding:0 7px">
                <i class="fa <?php echo $isThaali ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                <?php echo $isThaali ? 'Unmark' : 'Mark' ?>
              </button>
              <?php endif; ?>
            </td>
            <?php if (!in_array((int)$_SESSION['user']['role'], [9, 12], true)): ?>
            <td class="fmt-act-col" style="white-space:nowrap">
              <?php if(!$isEmpty): ?>
              <a href="<?php echo base_url('common/edit_menu/'.$item['id'].'?from='.urlencode((string)($from??'')).(isset($hijri_month_id)&&$hijri_month_id!==''?'&hijri_month='.urlencode((string)$hijri_month_id):'').(!empty($assigned_filter)?'&assigned_filter='.urlencode((string)$assigned_filter):''))?>"
                 class="act-btn act-edit" title="Edit"><i class="fa fa-pencil"></i></a>
              <span class="act-sep"></span>
              <form method="POST" action="<?php echo base_url('common/delete_menu')?>" style="display:inline" onsubmit="return confirm('Delete this menu?')">
                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($item['id'],ENT_QUOTES)?>">
                <button type="submit" class="act-btn act-del" title="Delete"><i class="fa fa-trash"></i></button>
              </form>
              <?php else: ?>
              <a href="<?php echo base_url('common/createmenu?date='.urlencode((string)($item['date']??'')).'&from='.urlencode((string)($from??'')).(isset($hijri_month_id)&&$hijri_month_id!==''?'&hijri_month='.urlencode((string)$hijri_month_id):'').(!empty($assigned_filter)?'&assigned_filter='.urlencode((string)$assigned_filter):''))?>"
                 class="act-btn act-add" title="Add menu"><i class="fa fa-plus"></i></a>
              <?php endif?>
            </td>
            <?php endif; ?>
          </tr>
          <?php endforeach?>
          <?php else:?>
          <tr>
            <td colspan="<?= in_array((int)$_SESSION['user']['role'], [9, 12], true) ? '7' : '8' ?>" style="text-align:center;padding:40px;color:#9c8f7a;font-size:.82rem">
              <i class="fa fa-cutlery" style="font-size:1.8rem;display:block;margin-bottom:8px;color:#e8e0cc"></i>
              No menu items found.
            </td>
          </tr>
          <?php endif?>
        </tbody>
      </table>
    </div>

    <div class="fmt-foot">
      <span class="fmt-cnt" id="fmt-cnt"><?php echo $rowCount?> row<?php echo $rowCount!==1?'s':''?></span>
      <span style="font-size:.66rem;color:#9c8f7a">
        <span style="background:#fff2f2;color:#b91c1c;padding:1px 6px;border-radius:4px;font-size:.63rem;font-weight:700">Sunday</span>
        &nbsp;highlighted &nbsp;·&nbsp;
        <span style="background:#eaf4ee;color:#1a6645;padding:1px 6px;border-radius:4px;font-size:.63rem;font-weight:700">Green pills</span>
        &nbsp;= menu items
      </span>
    </div>
  </div>

</div><!-- /#fmtApp -->

<script>
var BASE = '<?php echo base_url() ?>';

/* ── Thaali Day toggle ── */
(function(){
  document.addEventListener('click', function(e){
    var btn = e.target.closest('.fmt-thaali-toggle');
    if (!btn) return;
    var date  = btn.getAttribute('data-date');
    var state = parseInt(btn.getAttribute('data-state'), 10); // 1=currently thaali, 0=not
    var newState = state ? 0 : 1; // flip
    btn.disabled = true;
    btn.style.opacity = '.5';
    $.ajax({
      url: BASE + 'common/toggle_thaali_day_ajax',
      type: 'POST',
      data: { date: date, status: newState },
      dataType: 'json',
      success: function(res) {
        if (res && res.success) {
          // Update badge in same <td>
          var td   = btn.closest('td');
          var badge = td.querySelector('.td-yes, .td-no');
          if (newState === 1) {
            // Now a thaali day
            if (badge) { badge.className = 'td-yes'; badge.innerHTML = '<i class="fa fa-check"></i> Yes'; }
            btn.className = btn.className.replace('act-thaali-off','act-thaali-on');
            btn.setAttribute('data-state','1');
            btn.title = 'Mark as Non-Thaali Day';
            btn.innerHTML = '<i class="fa fa-toggle-on"></i> Unmark';
          } else {
            // No longer a thaali day
            if (badge) { badge.className = 'td-no'; badge.innerHTML = '<i class="fa fa-times"></i> No'; }
            btn.className = btn.className.replace('act-thaali-on','act-thaali-off');
            btn.setAttribute('data-state','0');
            btn.title = 'Mark as Thaali Day';
            btn.innerHTML = '<i class="fa fa-toggle-off"></i> Mark';
          }
        } else {
          alert((res && res.message) ? res.message : 'Failed to update. Please try again.');
        }
      },
      error: function() { alert('Server error. Please try again.'); },
      complete: function() { btn.disabled = false; btn.style.opacity = ''; }
    });
  });
})();

(function(){
  var tbody=document.getElementById('fmt-tbody');
  if(!tbody)return;

  /* ── Column sort (keeps month headers with their rows) ── */
  var colMap={eng:1,hijri:2,day:3};
  var state={col:null,dir:'asc'};
  document.querySelectorAll('#fmtApp table.fmt-tbl thead th.sortable').forEach(function(th){
    th.addEventListener('click',function(){
      var col=th.dataset.col;
      state.dir=(state.col===col&&state.dir==='asc')?'desc':'asc';
      state.col=col;
      document.querySelectorAll('#fmtApp table.fmt-tbl thead th.sortable').forEach(function(h){h.classList.remove('asc','desc')});
      th.classList.add(state.dir);
      sortRows(colMap[col],state.dir);
    });
  });
  function getCellVal(tr,idx){var c=tr.querySelectorAll('td');return c[idx]?(c[idx].getAttribute('data-sort-value')||c[idx].textContent.trim()):'';}
  function norm(v){if(/^\d{4}-\d{2}-\d{2}$/.test(v))return new Date(v).getTime();if(!isNaN(parseFloat(v))&&isFinite(v))return parseFloat(v);return v.toLowerCase()}
  function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
  function sortRows(idx,dir){
    var all=Array.from(tbody.querySelectorAll('tr'));
    var hdrs=all.filter(function(r){return r.classList.contains('month-hdr')});
    var data=all.filter(function(r){return!r.classList.contains('month-hdr')});
    data.sort(function(a,b){var va=norm(getCellVal(a,idx)),vb=norm(getCellVal(b,idx));return va<vb?(dir==='asc'?-1:1):va>vb?(dir==='asc'?1:-1):0});
    hdrs.forEach(function(r){r.remove()});
    tbody.innerHTML='';
    var seen=new Set();
    data.forEach(function(r){
      var hd=r.getAttribute('data-hijri-date')||'';
      var parts=hd.split(' ');parts.shift();var mName=parts.join(' ');
      if(mName&&!seen.has(mName)){
        seen.add(mName);
        var hdr=document.createElement('tr');hdr.className='month-hdr';
        hdr.setAttribute('data-hijri-month-name',mName);
        var td=document.createElement('td');td.colSpan=8;
        td.innerHTML='<i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i>Hijri Month: <strong>'+esc(mName)+'</strong>';
        hdr.appendChild(td);tbody.appendChild(hdr);
      }
      tbody.appendChild(r);
    });
    renumber();
  }
  function renumber(){var i=1;tbody.querySelectorAll('.fmt-sno').forEach(function(el){el.textContent=i++})}

  /* ── Assigned To client-side filter ── */
  var assignedFilter=document.getElementById('filter-assigned');
  function applyFilter(){
    var q=(assignedFilter?assignedFilter.value:'').toString().toLowerCase().trim();
    var rows=Array.from(tbody.querySelectorAll('tr'));
    rows.forEach(function(r){
      if(r.classList.contains('month-hdr'))return;
      if(!q){r.style.display='';return}
      var tds=r.querySelectorAll('td');
      var name=(tds[5]?tds[5].textContent:'').toLowerCase();
      var its=(r.getAttribute('data-assigned-its')||'').toLowerCase();
      r.style.display=(name.includes(q)||its.includes(q))?'':'none';
    });
    /* hide month headers with no visible rows below */
    var curHdr=null,anyVis=false;
    rows.forEach(function(r){
      if(r.classList.contains('month-hdr')){
        if(curHdr)curHdr.style.display=anyVis?'':'none';
        curHdr=r;anyVis=false;return;
      }
      if(r.style.display!=='none')anyVis=true;
    });
    if(curHdr)curHdr.style.display=anyVis?'':'none';
    /* update count */
    var vis=rows.filter(function(r){return!r.classList.contains('month-hdr')&&r.style.display!=='none'}).length;
    var cnt=document.getElementById('fmt-cnt');
    if(cnt)cnt.textContent=vis+' row'+(vis!==1?'s':'');
  }
  if(assignedFilter)assignedFilter.addEventListener('input',applyFilter);
  applyFilter();

  /* ── Print ── */
  var printBtn=document.getElementById('fmt-print-btn');
  if(printBtn)printBtn.addEventListener('click',function(){window.print()});

})();

/* ── Form auto-submit on month change ── */
(function(){
  var sel=document.getElementById('hijri-month');
  if(sel)sel.addEventListener('change',function(){document.getElementById('filter-form').submit()});
  var inp=document.getElementById('filter-assigned');
  if(inp)inp.addEventListener('keydown',function(e){if(e.key==='Enter'){e.preventDefault();document.getElementById('filter-form').submit()}});
})();
</script>