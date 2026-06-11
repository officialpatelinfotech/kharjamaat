<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
#miqaatApp{font-family:'Plus Jakarta Sans',sans-serif;color:#1a1610;background:#faf7f0;min-height:calc(100vh - 57px);padding:14px 16px}
#miqaatApp *{box-sizing:border-box}

/* ── Topbar ── */
#miqaatApp .miqaat-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px}
#miqaatApp .miqaat-back{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;font-size:.75rem;font-weight:700;text-decoration:none;transition:all .15s}
#miqaatApp .miqaat-back:hover{background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none}
#miqaatApp .miqaat-heading{font-size:.98rem;font-weight:800;color:#b8860b;text-align:center;flex:1;letter-spacing:.3px}

/* ── Filter card ── */
#miqaatApp .miqaat-fc{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);margin-bottom:12px;overflow:hidden}
#miqaatApp .miqaat-fc-head{background:linear-gradient(135deg,#78520a,#b8860b);padding:9px 14px;display:flex;align-items:center;justify-content:space-between}
#miqaatApp .miqaat-fc-title{font-size:.8rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:6px}
#miqaatApp .miqaat-fc-hint{font-size:.68rem;color:rgba(255,255,255,.65);font-weight:500}
#miqaatApp .miqaat-fc-body{padding:11px 14px}
#miqaatApp .miqaat-frow{display:flex;align-items:flex-end;gap:7px;flex-wrap:wrap}
#miqaatApp .miqaat-fg{display:flex;flex-direction:column;gap:3px;flex:1;min-width:130px}
#miqaatApp .miqaat-lbl{font-size:.62rem;font-weight:700;color:#5a5244;text-transform:uppercase;letter-spacing:.4px}
#miqaatApp .miqaat-sel,#miqaatApp .miqaat-inp{height:32px;padding:0 9px;border:1.5px solid #e8e0cc;border-radius:7px;background:#f7f4ec;font-family:'Plus Jakarta Sans',sans-serif;font-size:.76rem;color:#1a1610;outline:none;transition:border-color .15s,box-shadow .15s;width:100%}
#miqaatApp .miqaat-sel:focus,#miqaatApp .miqaat-inp:focus{border-color:#b8860b;background:#fff;box-shadow:0 0 0 3px rgba(184,134,11,.1)}
#miqaatApp .miqaat-search-wrap{position:relative;flex:1;min-width:160px}
#miqaatApp .miqaat-search-wrap .s-ico{position:absolute;left:10px;top:calc(50% + 5px);transform:translateY(-50%);color:#9c8f7a;font-size:.78rem;pointer-events:none;display:flex;align-items:center;justify-content:center}
#miqaatApp .miqaat-search-wrap .miqaat-inp{padding-left:30px;padding-right:30px}
#miqaatApp .miqaat-search-wrap .s-clr{position:absolute;right:10px;top:calc(50% + 5px);transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9c8f7a;font-size:.72rem;padding:0;line-height:1;display:none}
#miqaatApp .miqaat-search-wrap .s-clr.vis{display:flex;align-items:center;justify-content:center}
#miqaatApp .miqaat-search-wrap .s-clr:hover{color:#b91c1c}

#miqaatApp .miqaat-btn-filter{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 12px;border-radius:7px;background:#b8860b;border:1.5px solid #b8860b;color:#fff;font-size:.74rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;transition:all .15s}
#miqaatApp .miqaat-btn-filter:hover{background:#78520a;border-color:#78520a;color:#fff;text-decoration:none}
#miqaatApp .miqaat-clear-btn{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 12px;border-radius:7px;background:#f7f4ec;border:1.5px solid #e8e0cc;color:#5a5244;font-size:.74rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;white-space:nowrap;transition:all .15s}
#miqaatApp .miqaat-clear-btn:hover{background:#fef2f2;border-color:#b91c1c;color:#b91c1c;text-decoration:none}
#miqaatApp .miqaat-btn-print{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 12px;border-radius:7px;background:#fff;border:1.5px solid #e8e0cc;color:#5a5244;font-size:.74rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;transition:all .15s}
#miqaatApp .miqaat-btn-print:hover{background:#f7f4ec;border-color:#b8860b;color:#b8860b}
#miqaatApp .miqaat-btn-add{display:inline-flex;align-items:center;gap:4px;height:32px;padding:0 12px;border-radius:7px;background:#10b981;border:1.5px solid #10b981;color:#fff;font-size:.74rem;font-weight:700;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;text-decoration:none;transition:all .15s}
#miqaatApp .miqaat-btn-add:hover{background:#065f46;border-color:#065f46;color:#fff;text-decoration:none}

/* ── Active filters bar ── */
#miqaatApp .miqaat-chips{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;min-height:0}
#miqaatApp .miqaat-chip{display:inline-flex;align-items:center;gap:4px;background:#f5e9c0;color:#b8860b;border:1px solid rgba(184,134,11,.3);border-radius:20px;padding:3px 9px;font-size:.67rem;font-weight:700}

/* ── Stats grid ── */
#miqaatApp .miqaat-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-bottom:12px}
@media(min-width:576px){#miqaatApp .miqaat-stats{grid-template-columns:repeat(3,1fr)}}
@media(min-width:992px){#miqaatApp .miqaat-stats{grid-template-columns:repeat(5,1fr)}}
#miqaatApp .miqaat-scard{border-radius:12px;padding:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:5px;border:1.5px solid;box-shadow:0 1px 3px rgba(0,0,0,.06);min-height:80px}
#miqaatApp .sc-lbl{font-size:.63rem;font-weight:700;line-height:1.3}
#miqaatApp .sc-val{font-size:1.35rem;font-weight:800;line-height:1}
#miqaatApp .sc-ico{font-size:.88rem;margin-bottom:1px}

#miqaatApp .sc-general{background:#dbeafe;border-color:#3b82f6;color:#1d4ed8}
#miqaatApp .sc-ashara{background:#fee2e2;border-color:#ef4444;color:#b91c1c}
#miqaatApp .sc-shehrullah{background:#e0f2fe;border-color:#0284c7;color:#0369a1}
#miqaatApp .sc-ladies{background:#fce7f3;border-color:#ec4899;color:#9d174d}
#miqaatApp .sc-individual{background:#ede9fe;border-color:#7c3aed;color:#5b21b6}
#miqaatApp .sc-group{background:#d1fae5;border-color:#10b981;color:#065f46}
#miqaatApp .sc-fnn{background:#fde68a;border-color:#f59e0b;color:#92400e}
#miqaatApp .sc-contributors{background:#d1fae5;border-color:#10b981;color:#065f46}
#miqaatApp .sc-noncontrib{background:#ffe4e6;border-color:#f43f5e;color:#be123c}

/* ── Table card ── */
#miqaatApp .miqaat-tcard{background:#fff;border:1px solid #e8e0cc;border-radius:13px;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden}
#miqaatApp .miqaat-legend{display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:8px 14px;border-bottom:1px solid #f0ece0;background:#faf7f0}
#miqaatApp .leg-lbl{font-size:.6rem;font-weight:800;color:#9c8f7a;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
#miqaatApp .leg-item{display:inline-flex;align-items:center;gap:4px;font-size:.64rem;font-weight:600;color:#5a5244;white-space:nowrap}
#miqaatApp .leg-dot{width:9px;height:9px;border-radius:2px;flex-shrink:0;border:1px solid rgba(0,0,0,.12)}
#miqaatApp .miqaat-row-count{margin-left:auto;background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.64rem;font-weight:800}

/* Table */
#miqaatApp .miqaat-tscroll{overflow-x:auto;overflow-y:visible}
#miqaatApp .miqaat-tscroll::-webkit-scrollbar{height:4px}
#miqaatApp .miqaat-tscroll::-webkit-scrollbar-thumb{background:#e8e0cc;border-radius:10px}
#miqaatApp table.miqaat-tbl{width:100%;border-collapse:collapse;font-size:.78rem;min-width:1000px}
#miqaatApp table.miqaat-tbl thead th{background:linear-gradient(to bottom,#f7f4ec,#ede8da);padding:9px 11px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:#9c8f7a;border-bottom:2px solid #e8e0cc;white-space:nowrap;text-align:left;z-index:10;user-select:none}
#miqaatApp table.miqaat-tbl thead th.sortable{cursor:pointer}
#miqaatApp table.miqaat-tbl thead th.sortable:hover{color:#b8860b}
#miqaatApp table.miqaat-tbl thead th .sort-indicator{margin-left:3px;font-size:.56rem}
#miqaatApp table.miqaat-tbl tbody tr{border-bottom:1px solid #f0ece0}
#miqaatApp table.miqaat-tbl tbody tr:not(.month-hdr):not(.no-results-row):hover td{filter:brightness(.96)}
#miqaatApp table.miqaat-tbl td{padding:8px 11px;vertical-align:middle;color:#1a1610}

/* Row colors by type */
#miqaatApp tr.row-holiday td{background:#f9f9f7;color:#9c8f7a;font-style:italic}
#miqaatApp tr.row-sunday td{background:#fff2f2!important}
#miqaatApp tr.row-ashara td{background:#fffbf0}
#miqaatApp tr.row-shehrullah td{background:#e6f7ff}
#miqaatApp tr.row-ladies td{background:#fff5f8}
#miqaatApp tr.row-general td{background:#f8faff}
#miqaatApp tr.row-miqaat td{background:#fffdf4}

/* Left accent border */
#miqaatApp tr.row-holiday td:first-child{border-left:3px solid #d1d5db}
#miqaatApp tr.row-sunday td:first-child{border-left:3px solid #ef4444}
#miqaatApp tr.row-ashara td:first-child{border-left:3px solid #f59e0b}
#miqaatApp tr.row-shehrullah td:first-child{border-left:3px solid #0284c7}
#miqaatApp tr.row-ladies td:first-child{border-left:3px solid #ec4899}
#miqaatApp tr.row-general td:first-child{border-left:3px solid #3b82f6}
#miqaatApp tr.row-miqaat td:first-child{border-left:3px solid #b8860b}

/* Month header */
#miqaatApp tr.month-hdr td{background:linear-gradient(90deg,#f5e9c0,#fdf5d6)!important;font-weight:800;font-size:.76rem;color:#b8860b;border-top:2px solid rgba(184,134,11,.22);border-bottom:1px solid rgba(184,134,11,.15);padding:7px 11px;white-space:nowrap;filter:none!important;cursor:pointer;user-select:none;}
#miqaatApp tr.month-hdr td:hover { filter: brightness(0.98); }
#miqaatApp tr.month-hdr .toggle-icon { float: right; margin-right: 10px; font-size: 0.8rem; color: #5a5244; }

/* Cells */
#miqaatApp .miqaat-sno{width:26px;height:26px;border-radius:50%;background:#f5e9c0;color:#b8860b;font-weight:800;font-size:.63rem;display:inline-flex;align-items:center;justify-content:center}
#miqaatApp .tbadge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:.61rem;font-weight:800;white-space:nowrap}
#miqaatApp .tb-ashara{background:#fffbeb;color:#b45309;border:1px solid rgba(180,83,9,.25)}
#miqaatApp .tb-shehrullah{background:#e0f2fe;color:#0369a1;border:1px solid rgba(3,105,161,.2)}
#miqaatApp .tb-ladies{background:#fce7f3;color:#9d174d;border:1px solid rgba(157,23,77,.2)}
#miqaatApp .tb-general{background:#eff6ff;color:#1d4ed8;border:1px solid rgba(29,78,216,.2)}
#miqaatApp .tb-miqaat{background:#f5e9c0;color:#b8860b;border:1px solid rgba(184,134,11,.3)}

#miqaatApp .assign-link{color:#1d4ed8;font-weight:600;font-size:.77rem;text-decoration:none;display:inline-flex;align-items:center;gap:3px}
#miqaatApp .assign-link:hover{color:#b8860b;text-decoration:underline}

#miqaatApp .st-badge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:.65rem;font-weight:800;white-space:nowrap;text-align:center}
#miqaatApp .st-active{background:#d1fae5;color:#065f46;border:1px solid rgba(6,95,70,.2)}
#miqaatApp .st-inactive{background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb}
#miqaatApp .st-completed{background:#eff6ff;color:#1d4ed8;border:1px solid rgba(29,78,216,.2)}
#miqaatApp .st-warning{background:#fffbeb;color:#b45309;border:1px solid rgba(180,83,9,.25)}

#miqaatApp .btn-action{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;border:none;cursor:pointer;font-size:.78rem;transition:opacity .15s}
#miqaatApp .btn-action:hover{opacity:.8}
#miqaatApp .btn-act-success{background:#10b981;color:#fff}
#miqaatApp .btn-act-warning{background:#f59e0b;color:#fff}
#miqaatApp .btn-act-primary{background:#3b82f6;color:#fff;display:inline-flex;align-items:center;justify-content:center}
#miqaatApp .btn-act-danger{background:#ef4444;color:#fff}

#miqaatApp .miqaat-tfoot{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;border-top:1px solid #f0ece0;background:#f7f4ec;font-size:.68rem;color:#9c8f7a;flex-wrap:wrap;gap:6px}

#miqaatApp .miqaat-no-results{text-align:center;padding:40px 20px;color:#9c8f7a}
#miqaatApp .miqaat-no-results i{font-size:1.8rem;display:block;margin-bottom:8px;color:#e8e0cc}
#miqaatApp .miqaat-no-results p{font-size:.82rem;margin:0}

@media(max-width:576px){
  #miqaatApp{padding:10px}
  #miqaatApp .miqaat-frow{flex-direction:column;align-items:stretch}
  #miqaatApp .miqaat-fg{min-width:100%}
  #miqaatApp table.miqaat-tbl{min-width:760px}
}

/* Print-optimized styles: show only the miqaat list when printing */
@media print {
  body * { visibility: hidden !important; }
  .miqaat-list-container, .miqaat-list-container * { visibility: visible !important; }
  .miqaat-list-container { position: absolute; top: 0; left: 0; width: 100%; }
  .miqaat-tcard { box-shadow: none !important; border: none !important; }
  .miqaat-tscroll { overflow: visible !important; }
  table.miqaat-tbl thead th { position: static !important; background: #1f2933 !important; color: #ffffff !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
  .miqaat-list-container table th:nth-child(8),
  .miqaat-list-container table td:nth-child(8),
  .miqaat-list-container table th:nth-child(9),
  .miqaat-list-container table td:nth-child(9) {
    display: none !important;
  }
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
.adet-row{padding:10px 12px;border-radius:9px;background:#f7f4ec;border:1px solid #e8e0cc;margin-bottom:8px;text-align:left}
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
/* ── Helpers ── */
function miqaat_row_class($type, $is_sunday) {
  if ($is_sunday) return 'row-sunday';
  $t = strtolower(trim($type));
  if (str_contains($t, 'ashara')) return 'row-ashara';
  if (str_contains($t, 'shehrullah')) return 'row-shehrullah';
  if (str_contains($t, 'ladies')) return 'row-ladies';
  if ($t === 'general') return 'row-general';
  return 'row-miqaat';
}

function miqaat_badge($type) {
  $t = strtolower(trim($type));
  $cls = match(true) {
    str_contains($t, 'ashara') => 'tb-ashara',
    str_contains($t, 'shehrullah') => 'tb-shehrullah',
    str_contains($t, 'ladies') => 'tb-ladies',
    $t === 'general' => 'tb-general',
    default => 'tb-miqaat'
  };
  return "<span class='tbadge $cls'>" . htmlspecialchars($type ?: '—', ENT_QUOTES) . "</span>";
}
?>

<div id="miqaatApp" class="margintopcontainer">

  <!-- Alerts -->
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger" style="margin-bottom: 12px; border-radius: 8px;">
      <?= $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success" style="margin-bottom: 12px; border-radius: 8px;">
      <?= $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <!-- Topbar -->
  <div class="miqaat-topbar">
    <a href="<?php echo isset($from) ? base_url($from) : base_url("anjuman/fmbthaali"); ?>" class="miqaat-back"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="miqaat-heading"><i class="fa fa-calendar" style="margin-right:6px;opacity:.7"></i>Manage Miqaat For Year &mdash; <?php echo isset($hijri_year) ? $hijri_year : ""; ?></div>
    <div style="width:70px"></div>
  </div>

  <!-- Filter card -->
  <div class="miqaat-fc">
    <div class="miqaat-fc-head">
      <div class="miqaat-fc-title"><i class="fa fa-sliders"></i> Filters</div>
      <div class="miqaat-fc-hint">Filters apply automatically on change</div>
    </div>
    <div class="miqaat-fc-body">
      <form method="post" action="<?php echo base_url('common/managemiqaat'); ?>" id="filter-form">
        <input type="hidden" name="from" value="<?php echo isset($from) ? htmlspecialchars($from, ENT_QUOTES) : ''; ?>" />
        <div class="miqaat-frow">

          <!-- Hijri Year -->
          <div class="miqaat-fg" style="max-width:180px">
            <label class="miqaat-lbl">Hijri Year</label>
            <select name="hijri_year" id="hijri-year" class="miqaat-sel">
              <option value="">Select Hijri Year</option>
              <?php if (isset($hijri_years)) foreach ($hijri_years as $yr): ?>
                <option value="<?php echo htmlspecialchars($yr, ENT_QUOTES); ?>" <?php echo isset($hijri_year) && $yr == $hijri_year ? 'selected' : ''; ?>><?php echo htmlspecialchars($yr, ENT_QUOTES); ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Miqaat Type -->
          <div class="miqaat-fg" style="max-width:160px">
            <label class="miqaat-lbl">Miqaat Type</label>
            <select id="miqaat-type" name="miqaat_type" class="miqaat-sel">
              <option value="">All Types</option>
              <?php if (!empty($miqaat_types)) foreach ($miqaat_types as $type): ?>
                <option value="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>" <?php echo (isset($miqaat_type) && $miqaat_type === $type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type, ENT_QUOTES); ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <!-- Assignment -->
          <div class="miqaat-fg" style="max-width:180px">
            <label class="miqaat-lbl">Assignment Status</label>
            <select id="assignment-filter" name="assignment_filter" class="miqaat-sel">
              <option value="" <?php echo empty($assignment_filter) ? 'selected' : ''; ?>>Assigned / Unassigned</option>
              <option value="unassigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'unassigned') ? 'selected' : ''; ?>>Unassigned Only</option>
              <option value="assigned" <?php echo (isset($assignment_filter) && $assignment_filter === 'assigned') ? 'selected' : ''; ?>>Assigned Only</option>
            </select>
          </div>

          <!-- Search -->
          <div class="miqaat-fg miqaat-search-wrap">
            <label class="miqaat-lbl">Search Member / Group / ITS</label>
            <i class="fa fa-search s-ico"></i>
            <input type="text" class="miqaat-inp" name="member_name_filter" id="member-name-filter"
              placeholder="Type name, group or ITS ID…"
              value="<?php echo isset($member_name_filter) ? htmlspecialchars($member_name_filter, ENT_QUOTES) : ''; ?>">
            <button type="button" class="s-clr" id="miqaatSearchClr" title="Clear search">&#x2715;</button>
          </div>

          <!-- Actions -->
          <div class="miqaat-fg" style="flex:0 0 auto;min-width:auto;align-items:flex-end">
            <label class="miqaat-lbl" style="visibility:hidden">actions</label>
            <div style="display:flex;gap:7px;">
              <button type="submit" class="miqaat-btn-filter" id="apply-filters-btn"><i class="fa fa-filter"></i> Filter</button>
              <a href="<?php echo base_url('common/managemiqaat?from=' . (isset($from) ? urlencode($from) : '')); ?>" class="miqaat-clear-btn"><i class="fa fa-times"></i> Clear All</a>
              <button type="button" id="print-table-btn" class="miqaat-btn-print" title="Print table"><i class="fa fa-print"></i> Print</button>
              <a href="<?php echo base_url('common/createmiqaat?date=' . date('Y-m-d') . (!empty($from) ? '&from=' . urlencode($from) : '')); ?>" class="miqaat-btn-add"><i class="fa fa-plus"></i> Add Miqaat</a>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>

  <!-- PHP Counts & Calculations -->
  <?php
  // Derive counts from visible dataset when summary vars are not provided
  $sum_total_miqaats = isset($summary_miqaat_days) ? (int)$summary_miqaat_days : 0;
  $sum_sundays = isset($summary_sundays) ? (int)$summary_sundays : 0;
  $sum_individual = isset($summary_individual) ? (int)$summary_individual : 0;
  $sum_group = isset($summary_group) ? (int)$summary_group : 0;
  $sum_fnn = isset($summary_fnn) ? (int)$summary_fnn : 0;
  $sum_ashara = isset($summary_ashara) ? (int)$summary_ashara : 0;
  $sum_shehrullah = isset($summary_shehrullah) ? (int)$summary_shehrullah : 0;
  $sum_general = isset($summary_general) ? (int)$summary_general : 0;
  $sum_ladies = isset($summary_ladies) ? (int)$summary_ladies : 0;

  $monthWiseMiqaats = [];
  $filteredMiqaats = [];

  if (!empty($miqaats)) {
    $filter = isset($assignment_filter) ? $assignment_filter : '';
    if ($filter === 'unassigned' || $filter === 'assigned') {
      foreach ($miqaats as $d) {
        if (empty($d['miqaats'])) continue;
        $dayCopy = $d;
        $dayCopy['miqaats'] = [];
        foreach ($d['miqaats'] as $m) {
          $hasAssignments = !empty($m['assignments']);
          if ($filter === 'unassigned' && !$hasAssignments) {
            $dayCopy['miqaats'][] = $m;
          } elseif ($filter === 'assigned' && $hasAssignments) {
            $dayCopy['miqaats'][] = $m;
          }
        }
        if (!empty($dayCopy['miqaats'])) {
          $filteredMiqaats[] = $dayCopy;
        }
      }
    } else {
      $filteredMiqaats = array_values(array_filter($miqaats, function ($d) {
        return !empty($d['miqaats']);
      }));
    }

    foreach ($filteredMiqaats as $day) {
      $hijriMonth = '';
      if (!empty($day['hijri_date_with_month'])) {
        $parts = explode(' ', $day['hijri_date_with_month'], 2);
        $hijriMonth = isset($parts[1]) ? $parts[1] : '';
      }
      if ($hijriMonth) {
        if (!isset($monthWiseMiqaats[$hijriMonth])) {
          $monthWiseMiqaats[$hijriMonth] = [];
        }
        $monthWiseMiqaats[$hijriMonth][] = $day;
      }
    }
  }

  // Recalculate summary counts for visible rows
  $sum_total_miqaats = 0;
  $sum_individual = 0;
  $sum_group = 0;
  $sum_fnn = 0;
  $sum_ashara = 0;
  $sum_shehrullah = 0;
  $sum_general = 0;
  $sum_ladies = 0;
  $uniq_member_ids = [];

  foreach ($filteredMiqaats as $d) {
    if (empty($d['miqaats'])) continue;
    foreach ($d['miqaats'] as $m) {
      $sum_total_miqaats++;
      $type_l = strtolower(isset($m['type']) ? $m['type'] : '');
      if ($type_l === 'ashara') $sum_ashara++;
      if ($type_l === 'shehrullah') $sum_shehrullah++;
      if ($type_l === 'general') $sum_general++;
      if ($type_l === 'ladies') $sum_ladies++;

      $hasInd = false;
      $hasGrp = false;
      if (!empty($m['assignments'])) {
        foreach ($m['assignments'] as $as) {
          $at = strtolower(isset($as['assign_type']) ? $as['assign_type'] : '');
          if ($at === 'individual') {
            $hasInd = true;
            $mid = trim((string)($as['member_id'] ?? ''));
            if ($mid !== '') $uniq_member_ids[$mid] = true;
          }
          if ($at === 'group') $hasGrp = true;
        }
      }
      if ($hasInd) $sum_individual++;
      if ($hasGrp) $sum_group++;

      $hay_parts = [
        (string)($m['type'] ?? ''),
        (string)($m['name'] ?? ''),
        (string)($m['assigned_to'] ?? '')
      ];
      if (!empty($m['assignments'])) {
        foreach ($m['assignments'] as $as_chk) {
          $hay_parts[] = (string)($as_chk['assign_type'] ?? '');
        }
      }
      $hay = strtolower(trim(implode(' ', $hay_parts)));
      $basic = preg_replace('/\s+/', ' ', $hay);
      $letters = preg_replace('/[^a-z]/', '', $basic);
      $letters = preg_replace(['/.{0}a{2,}/', '/.{0}i{2,}/', '/.{0}y{2,}/'], ['a', 'i', 'y'], $letters);
      $is_fnn = (strpos($basic, 'fnn') !== false)
        || ((strpos($basic, 'fala') !== false) && (strpos($basic, 'niyaz') !== false || strpos($basic, 'niaz') !== false))
        || ((strpos($letters, 'fala') !== false) && (strpos($letters, 'niyaz') !== false || strpos($letters, 'niaz') !== false));
      if ($is_fnn) $sum_fnn++;
    }
  }

  // Individual Contributors map to HOFs
  $sum_individual_contributors = 0;
  if (!empty($uniq_member_ids)) {
    try {
      $ci = get_instance();
      $ids = array_keys($uniq_member_ids);
      $chunks = array_chunk($ids, 200);
      $hof_ids = [];
      foreach ($chunks as $c) {
        $escaped = array_map(function($v) use ($ci) { return $ci->db->escape($v); }, $c);
        $in = implode(',', $escaped);
        $sql = "SELECT DISTINCT hof.ITS_ID AS hof_id FROM `user` u JOIN `user` hof ON hof.ITS_ID = (CASE WHEN u.HOF_FM_TYPE = 'HOF' THEN u.ITS_ID ELSE u.HOF_ID END) WHERE u.ITS_ID IN (" . $in . ") AND hof.HOF_FM_TYPE = 'HOF' AND hof.Inactive_Status IS NULL AND hof.Sector IS NOT NULL AND hof.Sub_Sector IS NOT NULL";
        $res = $ci->db->query($sql)->result_array();
        foreach ($res as $r) {
          $hid = isset($r['hof_id']) ? trim((string)$r['hof_id']) : '';
          if ($hid !== '') $hof_ids[$hid] = true;
        }
      }
      $sum_individual_contributors = count($hof_ids);
    } catch (Exception $e) {
      $sum_individual_contributors = count($uniq_member_ids);
    }
  }

  // Total HOF Members count
  $total_members = 0;
  try {
    $ci = get_instance();
    $q = $ci->db->query("SELECT COUNT(*) AS cnt FROM `user` WHERE HOF_FM_TYPE = 'HOF' AND Inactive_Status IS NULL");
    $row = $q->row();
    $total_members = $row ? (int)$row->cnt : 0;
  } catch (Exception $e) {
    $total_members = 0;
  }
  $non_contributors = max(0, $total_members - $sum_individual_contributors);
  ?>

  <!-- Stats cards -->
  <div class="miqaat-stats">
    <div class="miqaat-scard sc-general">
      <div class="sc-ico"><i class="fa fa-calendar"></i></div>
      <div class="sc-val"><?php echo $sum_total_miqaats; ?></div>
      <div class="sc-lbl">Total Miqaat</div>
    </div>
    <div class="miqaat-scard sc-ashara">
      <div class="sc-ico"><i class="fa fa-calendar-check-o"></i></div>
      <div class="sc-val"><?php echo $sum_ashara; ?></div>
      <div class="sc-lbl">Ashara Miqaat</div>
    </div>
    <div class="miqaat-scard sc-shehrullah">
      <div class="sc-ico"><i class="fa fa-moon-o"></i></div>
      <div class="sc-val"><?php echo $sum_shehrullah; ?></div>
      <div class="sc-lbl">Shehrullah Miqaat</div>
    </div>
    <div class="miqaat-scard sc-general">
      <div class="sc-ico"><i class="fa fa-info-circle"></i></div>
      <div class="sc-val"><?php echo $sum_general; ?></div>
      <div class="sc-lbl">General Miqaat</div>
    </div>
    <div class="miqaat-scard sc-ladies">
      <div class="sc-ico"><i class="fa fa-female"></i></div>
      <div class="sc-val"><?php echo $sum_ladies; ?></div>
      <div class="sc-lbl">Ladies Miqaat</div>
    </div>
    <div class="miqaat-scard sc-individual">
      <div class="sc-ico"><i class="fa fa-user"></i></div>
      <div class="sc-val"><?php echo $sum_individual; ?></div>
      <div class="sc-lbl">Individual Niyaaz</div>
    </div>
    <div class="miqaat-scard sc-group">
      <div class="sc-ico"><i class="fa fa-users"></i></div>
      <div class="sc-val"><?php echo $sum_group; ?></div>
      <div class="sc-lbl">Group Niyaaz</div>
    </div>
    <div class="miqaat-scard sc-fnn">
      <div class="sc-ico"><i class="fa fa-star"></i></div>
      <div class="sc-val"><?php echo $sum_fnn; ?></div>
      <div class="sc-lbl">Fala ni Niyaaz</div>
    </div>
    <div class="miqaat-scard sc-contributors">
      <div class="sc-ico"><i class="fa fa-user-circle"></i></div>
      <div class="sc-val"><?php echo $sum_individual_contributors; ?></div>
      <div class="sc-lbl">Individual Contributors</div>
    </div>
    <div class="miqaat-scard sc-noncontrib">
      <div class="sc-ico"><i class="fa fa-times-circle-o"></i></div>
      <div class="sc-val"><?php echo $non_contributors; ?></div>
      <div class="sc-lbl">Fala Contributors</div>
    </div>
  </div>

  <!-- Table card -->
  <div class="miqaat-tcard">
    <div class="miqaat-legend">
      <span class="leg-lbl">Row Colors:</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fffdf4;border-color:#b8860b"></span>Miqaat</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fffbf0;border-color:#f59e0b"></span>Ashara</span>
      <span class="leg-item"><span class="leg-dot" style="background:#e6f7ff;border-color:#0284c7"></span>Shehrullah</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fff5f8;border-color:#ec4899"></span>Ladies</span>
      <span class="leg-item"><span class="leg-dot" style="background:#f8faff;border-color:#3b82f6"></span>General</span>
      <span class="leg-item"><span class="leg-dot" style="background:#fff2f2;border-color:#ef4444"></span>Sunday</span>
      <span class="miqaat-row-count" id="miqaatRowCount">— rows</span>
    </div>

    <div class="miqaat-tscroll">
      <table class="miqaat-tbl" id="miqaatTable">
        <thead>
          <tr>
            <th style="width:40px">#</th>
            <th class="sortable" data-col="eng" style="min-width:95px">Eng Date <span class="sort-indicator"></span></th>
            <th class="sortable" data-col="hijri" style="min-width:210px">Hijri Date <span class="sort-indicator"></span></th>
            <th class="sortable" data-col="day" style="min-width:80px">Day <span class="sort-indicator"></span></th>
            <th style="min-width:180px">Name</th>
            <th style="min-width:100px">Type</th>
            <th style="min-width:180px">Assigned To</th>
            <th style="min-width:100px">Status</th>
            <th style="width:130px;text-align:center;">Actions</th>
          </tr>
        </thead>
        <tbody id="miqaatTbody">
          <?php if (!empty($monthWiseMiqaats)): ?>
            <?php $sno = 1; ?>
            <?php foreach ($monthWiseMiqaats as $monthName => $days): ?>
              <tr class="month-hdr" data-hijri-month-name="<?php echo htmlspecialchars($monthName, ENT_QUOTES); ?>">
                <td colspan="9">
                  <i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i>
                  <strong>Hijri Month: <?php echo htmlspecialchars($monthName, ENT_QUOTES); ?></strong>
                  <span class="toggle-icon"><i class="fa fa-chevron-down"></i></span>
                </td>
              </tr>
              <?php foreach ($days as $day): ?>
                <?php
                $dayName = isset($day['date']) ? date('l', strtotime($day['date'])) : '';
                $is_sunday = ($dayName === 'Sunday');
                ?>
                <?php if (!empty($day['miqaats'])): ?>
                  <?php foreach ($day['miqaats'] as $miqaat): ?>
                    <?php
                    $row_cls = miqaat_row_class($miqaat['type'] ?? '', $is_sunday);
                    $day_cls = $is_sunday ? 'day-sun' : (strtolower($dayName) === 'friday' ? 'day-fri' : '');
                    $ato = isset($miqaat['assigned_to']) ? trim($miqaat['assigned_to']) : '';
                    if ($ato === '') $ato = 'Assignment Pending';
                    ?>
                    <tr class="<?php echo htmlspecialchars($row_cls, ENT_QUOTES); ?>" 
                        data-hijri-month-name="<?php echo htmlspecialchars($monthName, ENT_QUOTES); ?>" 
                        data-eng-date="<?php echo htmlspecialchars($day['date'], ENT_QUOTES); ?>">
                      <td><span class="miqaat-sno"><?php echo $sno++; ?></span></td>
                      <td data-sort-value="<?php echo htmlspecialchars($day['date'], ENT_QUOTES); ?>" style="font-weight:600;white-space:nowrap;"><?php echo date('d M Y', strtotime($day['date'])); ?></td>
                      <td style="white-space:nowrap;"><?php echo htmlspecialchars($day['hijri_date_with_month'] ?? '', ENT_QUOTES); ?></td>
                      <td class="<?php echo $day_cls; ?>" style="white-space:nowrap;font-weight:<?php echo $is_sunday ? '700' : '500'; ?>;"><?php echo $dayName; ?></td>
                      <td style="word-break:break-word;white-space:normal;font-weight:600;"><?php echo htmlspecialchars($miqaat['name'] ?? '', ENT_QUOTES); ?></td>
                      <td><?php echo miqaat_badge($miqaat['type'] ?? ''); ?></td>
                      <td style="word-break:break-word;white-space:normal;">
                        <?php if (!empty($miqaat['assignments'])): ?>
                          <?php $ajson = htmlspecialchars(json_encode($miqaat['assignments']), ENT_NOQUOTES, 'UTF-8'); ?>
                          <a href="#" class="assign-link show-adet" data-assignments='<?php echo str_replace("'", "&#39;", $ajson); ?>'><i class="fa fa-users" style="font-size:.62rem;margin-right:3px;opacity:.7"></i><?php echo htmlspecialchars($ato, ENT_QUOTES); ?></a>
                        <?php else: ?>
                          <?php if ($ato === 'Assignment Pending'): ?>
                            <span class="st-badge st-inactive">Assignment Pending</span>
                          <?php else: ?>
                            <span style="font-weight:600;"><?php echo htmlspecialchars($ato, ENT_QUOTES); ?></span>
                          <?php endif; ?>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php
                        if (isset($miqaat['invoice_status']) && $miqaat['invoice_status'] == 'Generated') {
                          echo '<span class="st-badge st-completed">Completed</span>';
                        } elseif (isset($miqaat['invoice_status']) && $miqaat['invoice_status'] == 'Partially Generated') {
                          echo '<span class="st-badge st-warning">Completed:<br>Partially Invoiced</span>';
                        } else {
                          if (isset($miqaat['status'])) {
                            if ($miqaat['status'] == 1) {
                              echo '<span class="st-badge st-active">Active</span>';
                            } else {
                              echo '<span class="st-badge st-inactive">Inactive</span>';
                            }
                          } else {
                            echo '<span class="st-badge st-inactive">N/A</span>';
                          }
                        }
                        ?>
                      </td>
                      <td>
                        <div style="display:flex;gap:4px;justify-content:center;">
                          <!-- Activate Button -->
                          <form method="POST" action="<?php echo base_url('common/activate_miqaat'); ?>" style="display:inline;">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="btn-action btn-act-success" <?php
                                                                                  $noAssignments = empty($miqaat['assignments']);
                                                                                  echo (isset($miqaat['status']) && $miqaat['status'] == 1) || $noAssignments ? 'disabled title="Assign first to activate" style="opacity:0.4;cursor:not-allowed;"' : '';
                                                                                  ?>>
                              <i class="fa fa-check"></i>
                            </button>
                          </form>
                          <!-- Cancel Button -->
                          <form method="POST" action="<?php echo base_url('common/cancel_miqaat'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to make this Miqaat inactive?');">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="btn-action btn-act-warning" <?php echo (isset($miqaat['status']) && $miqaat['status'] == 2) ? 'disabled style="opacity:0.4;cursor:not-allowed;"' : ''; ?>>
                              <i class="fa fa-ban"></i>
                            </button>
                          </form>
                          <!-- Edit Button -->
                          <a href="<?php echo base_url('common/edit_miqaat?id=' . $miqaat['id'] . (!empty($from) ? '&from=' . urlencode($from) : '')); ?>" class="btn-action btn-act-primary">
                            <i class="fa fa-edit"></i>
                          </a>
                          <!-- Delete Button -->
                          <form method="POST" action="<?php echo base_url('common/delete_miqaat'); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this Miqaat?');">
                            <input type="hidden" name="miqaat_id" value="<?php echo $miqaat['id']; ?>">
                            <button type="submit" class="btn-action btn-act-danger">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9">
                <div class="miqaat-no-results">
                  <i class="fa fa-search"></i>
                  <p>No Miqaats found matching the selected filters.</p>
                  <div style="margin-top:12px;">
                    <a href="<?php echo base_url('common/createmiqaat?date=' . date('Y-m-d') . (!empty($from) ? '&from=' . urlencode($from) : '')); ?>" class="miqaat-btn-add"><i class="fa fa-plus"></i> Create Miqaat</a>
                    <a href="<?php echo base_url('common/managemiqaat?from=' . (isset($from) ? urlencode($from) : '')); ?>" class="miqaat-clear-btn" style="margin-left:8px;">Clear Filters</a>
                  </div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="miqaat-tfoot">
      <span id="miqaatCnt" style="background:#f5e9c0;color:#b8860b;border-radius:20px;padding:2px 9px;font-size:.65rem;font-weight:800">— rows</span>
      <span>Manage Miqaat For Year &mdash; <?php echo isset($hijri_year) ? htmlspecialchars($hijri_year, ENT_QUOTES) : ""; ?></span>
    </div>
  </div>

</div><!-- /#miqaatApp -->

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

/* ── Auto-submit dropdowns ── */
document.getElementById('hijri-year').addEventListener('change',function(){document.getElementById('filter-form').submit()});
document.getElementById('miqaat-type').addEventListener('change',function(){document.getElementById('filter-form').submit()});
document.getElementById('assignment-filter').addEventListener('change',function(){document.getElementById('filter-form').submit()});

/* ── Search Clear and Submit on Enter ── */
var searchInput=document.getElementById('member-name-filter');
var searchClr=document.getElementById('miqaatSearchClr');
function updateSearchClr(){searchClr.classList.toggle('vis',searchInput.value.length>0)}
searchInput.addEventListener('input',updateSearchClr);
searchInput.addEventListener('keydown',function(e){
  if(e.key==='Enter'){e.preventDefault();document.getElementById('filter-form').submit()}
});
searchClr.addEventListener('click',function(){searchInput.value='';updateSearchClr();document.getElementById('filter-form').submit()});
updateSearchClr();

$(".alert").delay(3000).fadeOut(500);

/* ── Print handler ── */
var printBtn = document.getElementById('print-table-btn');
if (printBtn) {
  printBtn.addEventListener('click', function() {
    setTimeout(function() { window.print(); }, 50);
  });
}

/* ── Column sort keeping month headers grouped ── */
(function(){
  var tbody=document.getElementById('miqaatTbody');
  if(!tbody) return;
  var state={col:null,dir:'asc'};
  var colIdx={eng:1,hijri:2,day:3};
  document.querySelectorAll('#miqaatTable thead th.sortable').forEach(function(th){
    th.addEventListener('click',function(){
      var col=th.dataset.col;
      state.dir=(state.col===col&&state.dir==='asc')?'desc':'asc';
      state.col=col;
      document.querySelectorAll('#miqaatTable thead th.sortable').forEach(function(h){
        var ind = h.querySelector('.sort-indicator');
        if (ind) ind.textContent = '';
      });
      var ind = th.querySelector('.sort-indicator');
      if (ind) ind.textContent = state.dir === 'asc' ? '▲' : '▼';
      sortRows(colIdx[col],state.dir);
    });
  });
  function getCellVal(tr,idx){var c=tr.querySelectorAll('td');return c[idx]?(c[idx].getAttribute('data-sort-value')||c[idx].textContent.trim()):'';}
  function norm(v){if(/^\d{4}-\d{2}-\d{2}$/.test(v))return new Date(v).getTime();if(!isNaN(parseFloat(v))&&isFinite(v))return parseFloat(v);return v.toLowerCase()}
  function sortRows(idx,dir){
    var all=Array.from(tbody.querySelectorAll('tr'));
    var mhdrs=all.filter(function(r){return r.classList.contains('month-hdr')});
    var data=all.filter(function(r){return!r.classList.contains('month-hdr') && !r.classList.contains('no-results-row')});
    data.sort(function(a,b){
      var va=norm(getCellVal(a,idx)),vb=norm(getCellVal(b,idx));
      return va<vb?(dir==='asc'?-1:1):va>vb?(dir==='asc'?1:-1):0;
    });
    mhdrs.forEach(function(r){r.remove()});
    tbody.innerHTML='';
    var seen=new Set();
    data.forEach(function(r){
      var mn=r.getAttribute('data-hijri-month-name')||'';
      if(!seen.has(mn)){
        seen.add(mn);
        var hdr=document.createElement('tr');hdr.className='month-hdr';
        hdr.setAttribute('data-hijri-month-name',mn);
        var td=document.createElement('td');td.colSpan=9;
        td.innerHTML='<i class="fa fa-calendar-o" style="margin-right:6px;opacity:.65"></i><strong>Hijri Month: '+esc(mn)+'</strong><span class="toggle-icon"><i class="fa fa-chevron-down"></i></span>';
        hdr.appendChild(td);
        tbody.appendChild(hdr);
      }
      tbody.appendChild(r);
    });
    renumber();
  }
  function renumber(){
    var i=1;
    document.querySelectorAll('#miqaatTbody tr:not(.month-hdr)').forEach(function(tr){
      var s=tr.querySelector('.miqaat-sno');if(s)s.textContent=i++;
    });
  }
})();

function updateCount(){
  var n=document.querySelectorAll('#miqaatTbody tr:not(.month-hdr):not(.no-results-row)').length;
  var txt=n+' row'+(n!==1?'s':'');
  var a=document.getElementById('miqaatCnt'),b=document.getElementById('miqaatRowCount');
  if(a)a.textContent=txt;if(b)b.textContent=txt;
}
updateCount();

/* Collapsible month headers */
function toggleMonth(header, forceCollapse) {
  var isCollapsed;
  if (typeof forceCollapse !== 'undefined') {
    isCollapsed = forceCollapse;
    header.classList.toggle('collapsed', isCollapsed);
  } else {
    isCollapsed = header.classList.toggle('collapsed');
  }
  
  var chevron = header.querySelector('.toggle-icon i');
  if (chevron) {
    if (isCollapsed) {
      chevron.className = 'fa fa-chevron-right';
    } else {
      chevron.className = 'fa fa-chevron-down';
    }
  }
  
  var next = header.nextElementSibling;
  while (next && !next.classList.contains('month-hdr')) {
    if (isCollapsed) {
      next.style.display = 'none';
    } else {
      next.style.display = '';
    }
    next = next.nextElementSibling;
  }
}

document.getElementById('miqaatTbody').addEventListener('click', function(e) {
  var header = e.target.closest('.month-hdr');
  if (header) toggleMonth(header);
});

/* Collapse all months by default on load */
document.querySelectorAll('#miqaatTbody tr.month-hdr').forEach(function(header) {
  toggleMonth(header, true);
});
</script>