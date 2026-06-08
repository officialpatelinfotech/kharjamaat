<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$qh_prefix     = isset($qh_prefix) && trim((string)$qh_prefix) !== '' ? trim((string)$qh_prefix) : 'admin';
$qh_scheme_base = $qh_prefix . '/qardanhasana';
$can_manage    = isset($can_manage) ? (bool)$can_manage : true;
$can_import    = isset($can_import) ? (bool)$can_import : $can_manage;
$is_member_view = ($qh_prefix === 'accounts');

$scheme_colors = [
  'mohammedi' => ['grad' => 'linear-gradient(135deg,#1a7850 0%,#0d5c38 100%)', 'icon' => 'fa fa-hand-o-up'],
  'taher'     => ['grad' => 'linear-gradient(135deg,#2563eb 0%,#1e3a8a 100%)', 'icon' => 'fa fa-money'],
  'husain'    => ['grad' => 'linear-gradient(135deg,#c97c1a 0%,#7c2d12 100%)', 'icon' => 'fa fa-handshake-o'],
];
$sc_key   = isset($scheme_key) ? (string)$scheme_key : '';
$sc_color = isset($scheme_colors[$sc_key]) ? $scheme_colors[$sc_key] : ['grad'=>'linear-gradient(135deg,#b8860b 0%,#78520a 100%)','icon'=>'fa fa-leaf'];
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ═══════════════════════════════════════
   QARDAN HASANA SCHEME — DETAIL PAGE
   Matches Umoor dashboard gold theme
   ═══════════════════════════════════════ */
:root {
  --gold:       #b8860b;
  --gold-light: #e6c84a;
  --gold-muted: #f5e9c0;
  --bg:         #faf7f0;
  --surface:    #ffffff;
  --surface-2:  #f7f4ec;
  --border:     #e8e0cc;
  --border-l:   #f0ece0;
  --text-1:     #1a1610;
  --text-2:     #5a5244;
  --text-3:     #9c8f7a;
  --green:      #1a6645;
  --green-bg:   #eaf4ee;
  --red:        #b91c1c;
  --red-bg:     #fef2f2;
  --blue:       #1d4ed8;
  --blue-bg:    #eff6ff;
  --sh:         0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --sh-lg:      0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#qhSchApp, #qhSchApp *, #qhSchApp *::before, #qhSchApp *::after { box-sizing: border-box; }
#qhSchApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
  padding: 22px 22px 60px;
}

/* ── Flash ── */
.qh-flash { border-radius: 10px; padding: 12px 16px; font-size: .82rem; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
.qh-flash-ok  { background: var(--green-bg); color: var(--green); border: 1px solid #b7dfc8; }
.qh-flash-err { background: var(--red-bg);   color: var(--red);   border: 1px solid #fca5a5; }

/* ── Back button ── */
.qh-back-btn {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--surface); border: 1.5px solid var(--border);
  border-radius: 9px; padding: 7px 14px;
  font-size: .80rem; font-weight: 600; color: var(--text-2);
  text-decoration: none; transition: all .15s;
  margin-bottom: 20px;
}
.qh-back-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

/* ── Hero banner ── */
.qh-hero {
  border-radius: 22px; padding: 24px 28px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 20px;
  margin-bottom: 24px;
  box-shadow: var(--sh-lg);
  background: var(--sc-grad, linear-gradient(135deg,#b8860b 0%,#78520a 100%));
}
.qh-hero::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
.qh-hero::after {
  content: ''; position: absolute; right: -50px; top: -50px;
  width: 200px; height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
.qh-hero-left { position: relative; z-index: 1; display: flex; align-items: center; gap: 16px; }
.qh-hero-icon {
  width: 52px; height: 52px; border-radius: 14px;
  background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; color: #fff; flex-shrink: 0;
}
.qh-hero-eyebrow { font-size: .64rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 3px; }
.qh-hero-title { font-family: 'Literata', Georgia, serif; font-size: 1.55rem; font-weight: 600; color: #fff; margin: 0; line-height: 1.2; }

.qh-hero-right { position: relative; z-index: 1; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }

.qh-amount-pill {
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 14px; padding: 12px 18px;
  backdrop-filter: blur(6px); text-align: center; flex-shrink: 0;
}
.qh-amount-pill-val { font-size: 1.45rem; font-weight: 800; color: #fff; display: block; line-height: 1; }
.qh-amount-pill-lbl { font-size: .62rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 4px; display: block; }

.qh-hero-action-btn {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
  border-radius: 9px; padding: 9px 15px;
  font-size: .78rem; font-weight: 600; color: #fff;
  text-decoration: none; cursor: pointer;
  transition: background .15s;
}
.qh-hero-action-btn:hover { background: rgba(255,255,255,.28); color: #fff; text-decoration: none; }
.qh-hero-action-btn.qh-import-btn { background: rgba(255,255,255,.92); color: rgba(0,0,0,.7); border-color: rgba(255,255,255,.5); }
.qh-hero-action-btn.qh-import-btn:hover { background: #fff; }

/* ── Filter card ── */
.qh-filter-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 18px 20px;
  margin-bottom: 20px;
  box-shadow: var(--sh);
}
.qh-filter-row { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
.qh-fg { display: flex; flex-direction: column; gap: 5px; min-width: 170px; flex: 1 1 170px; }
.qh-fg label { font-size: .72rem; font-weight: 700; color: var(--text-2); }
.qh-fg select, .qh-fg input[type="text"], .qh-fg input[type="date"] {
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 7px 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: .81rem; color: var(--text-1);
  background: var(--surface);
  outline: none; transition: border-color .14s;
}
.qh-fg select:focus, .qh-fg input:focus { border-color: var(--gold); }
.qh-filter-actions { display: flex; gap: 8px; align-items: flex-end; margin-left: auto; }

.qh-btn {
  display: inline-flex; align-items: center; gap: 6px;
  border-radius: 8px; padding: 8px 16px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: .78rem; font-weight: 600; cursor: pointer;
  border: 1.5px solid transparent; transition: all .14s; text-decoration: none;
}
.qh-btn-primary { background: var(--gold); border-color: var(--gold); color: #fff; }
.qh-btn-primary:hover { background: #a07309; border-color: #a07309; color: #fff; text-decoration: none; }
.qh-btn-ghost { background: var(--surface); border-color: var(--border); color: var(--text-2); }
.qh-btn-ghost:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
.qh-btn-danger { background: #b91c1c; border-color: #b91c1c; color: #fff; }
.qh-btn-danger:hover { background: #991b1b; color: #fff; }

/* ── Table card ── */
.qh-table-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: var(--sh);
  overflow: hidden;
}
.qh-table-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--border);
  flex-wrap: wrap; gap: 10px;
}
.qh-table-title { font-size: .84rem; font-weight: 800; color: var(--text-1); display: flex; align-items: center; gap: 8px; }
.qh-table-title i { color: var(--gold); }
.qh-record-count { font-size: .73rem; color: var(--text-3); font-weight: 700; background: var(--surface-2); border: 1px solid var(--border-l); border-radius: 20px; padding: 3px 10px; }

.qh-table-wrap { overflow-x: auto; max-height: 65vh; overflow-y: auto; }

table.qh-tbl { width: 100%; border-collapse: collapse; min-width: 700px; }
table.qh-tbl thead th {
  background: linear-gradient(to bottom, var(--surface-2), #f0ebe0);
  padding: 9px 13px; font-size: .6rem; font-weight: 800;
  text-transform: uppercase; letter-spacing: .7px; color: var(--text-3);
  border-bottom: 2px solid var(--border); white-space: nowrap;
  position: sticky; top: 0; z-index: 2; user-select: none; text-align: left;
}
th.sortable { cursor: pointer; transition: color .14s; }
th.sortable:hover { color: var(--gold); }
th.sortable .si { margin-left: 3px; opacity: .35; font-size: .55rem; }
th.sortable .si::after { content: '⇅'; }
th.sortable.asc  .si::after { content: '▲'; opacity: 1; color: var(--gold); }
th.sortable.desc .si::after { content: '▼'; opacity: 1; color: var(--gold); }

table.qh-tbl tbody tr { border-bottom: 1px solid var(--border-l); transition: background .1s; }
table.qh-tbl tbody tr:hover { background: #fdf9ef; }
table.qh-tbl td { padding: 9px 13px; vertical-align: middle; color: var(--text-1); font-size: .80rem; }
.qh-tbl-sr { color: var(--text-3); font-size: .72rem; font-weight: 600; }
.qh-its-badge { font-size: .72rem; font-weight: 700; background: var(--surface-2); border: 1px solid var(--border-l); border-radius: 5px; padding: 2px 7px; font-family: monospace; color: var(--text-2); }
.qh-amount-cell { font-weight: 700; color: var(--green); }
.qh-empty-row td { text-align: center; padding: 40px; color: var(--text-3); font-size: .84rem; }
.qh-empty-row td i { font-size: 1.5rem; display: block; margin-bottom: 8px; opacity: .4; }

/* Action buttons in table */
.qh-act-btn {
  display: inline-flex; align-items: center; gap: 4px;
  border-radius: 6px; padding: 4px 9px;
  font-size: .7rem; font-weight: 600; cursor: pointer;
  border: 1.5px solid transparent; transition: all .12s; text-decoration: none;
}
.qh-act-edit  { background: var(--blue-bg); border-color: #bfdbfe; color: var(--blue); }
.qh-act-edit:hover  { background: var(--blue); color: #fff; border-color: var(--blue); }
.qh-act-del   { background: var(--red-bg); border-color: #fca5a5; color: var(--red); }
.qh-act-del:hover   { background: var(--red); color: #fff; border-color: var(--red); }

/* ── Modal overrides ── */
.qh-modal .modal-content { border-radius: 16px; border: 1px solid var(--border); }
.qh-modal .modal-header { background: var(--surface-2); border-bottom: 1px solid var(--border); border-radius: 16px 16px 0 0; padding: 14px 18px; }
.qh-modal .modal-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .88rem; font-weight: 800; color: var(--text-1); }
.qh-modal .modal-body { padding: 18px; }
.qh-modal .modal-footer { border-top: 1px solid var(--border); padding: 12px 18px; }
.qh-modal .form-group label { font-size: .75rem; font-weight: 700; color: var(--text-2); margin-bottom: 5px; }
.qh-modal .form-control { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .82rem; border: 1.5px solid var(--border); border-radius: 8px; }
.qh-modal .form-control:focus { border-color: var(--gold); box-shadow: 0 0 0 2px rgba(184,134,11,.12); }

@media (max-width: 700px) {
  #qhSchApp { padding: 12px 10px 40px; }
  .qh-hero { flex-direction: column; align-items: flex-start; }
  .qh-hero-right { justify-content: flex-start; }
}
</style>

<div id="qhSchApp">

  <?php if (!empty($qh_import_message)): ?>
    <div class="qh-flash qh-flash-ok"><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($qh_import_message); ?></div>
  <?php elseif (!empty($qh_import_error)): ?>
    <div class="qh-flash qh-flash-err"><i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($qh_import_error); ?></div>
  <?php endif; ?>

  <!-- Back button -->
  <a href="<?php echo base_url($qh_scheme_base); ?>" class="qh-back-btn">
    <i class="fa fa-arrow-left"></i> Back to Qardan Hasana
  </a>

  <!-- Hero banner -->
  <div class="qh-hero" style="--sc-grad: <?php echo $sc_color['grad']; ?>;">
    <div class="qh-hero-left">
      <div class="qh-hero-icon"><i class="<?php echo $sc_color['icon']; ?>"></i></div>
      <div>
        <div class="qh-hero-eyebrow">Qardan Hasana</div>
        <h1 class="qh-hero-title"><?php echo isset($scheme_title) ? htmlspecialchars($scheme_title) : 'Scheme'; ?></h1>
      </div>
    </div>
    <div class="qh-hero-right">
      <?php if (isset($total_amount) && !$is_member_view): ?>
      <div class="qh-amount-pill">
        <span class="qh-amount-pill-val">₹<?php echo isset($total_amount) ? (function_exists('format_inr') ? format_inr($total_amount, 0) : number_format($total_amount, 0)) : '0'; ?></span>
        <span class="qh-amount-pill-lbl">Total Amount</span>
      </div>
      <?php endif; ?>

      <?php if ($can_import): ?>
        <?php if (isset($scheme_key) && in_array($scheme_key, ['mohammedi','taher','husain'], true)): ?>
        <a href="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/template'); ?>" class="qh-hero-action-btn">
          <i class="fa fa-download"></i> CSV Template
        </a>
        <?php endif; ?>
        <button type="button" class="qh-hero-action-btn qh-import-btn" data-toggle="modal" data-target="#qhImportModal">
          <i class="fa fa-upload"></i> Import
        </button>
      <?php endif; ?>
    </div>
  </div>

  <?php if (isset($scheme_key) && in_array($scheme_key, ['mohammedi', 'taher', 'husain'], true)): ?>

    <!-- ── Filter Card ── -->
    <?php if (!$is_member_view): ?>
    <div class="qh-filter-card">
      <form method="get" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key); ?>" class="qh-filter-row">

        <?php if ($sc_key === 'mohammedi'): ?>
          <div class="qh-fg" style="max-width:240px;">
            <label for="miqaat_id">Miqaat Name</label>
            <select id="miqaat_id" name="miqaat_id">
              <option value="">All</option>
              <?php
                $selectedMiqaat = isset($filters['miqaat_id']) ? (string)$filters['miqaat_id'] : '';
                if (!empty($miqaats)):
                  foreach ($miqaats as $m):
                    $mid   = isset($m['id'])   ? (string)$m['id']   : '';
                    $mname = isset($m['name'])  ? (string)$m['name'] : '';
                    $mdate = isset($m['date'])  ? (string)$m['date'] : '';
                    $label = $mname;
                    if ($mdate !== '') $label .= ' (' . date('d-m-Y', strtotime($mdate)) . ')';
              ?>
                <option value="<?php echo htmlspecialchars($mid); ?>" <?php echo ($selectedMiqaat !== '' && $selectedMiqaat === $mid) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($label); ?>
                </option>
              <?php
                  endforeach;
                endif;
              ?>
            </select>
          </div>
          <div class="qh-fg" style="max-width:175px;">
            <label for="hijri_date">Hijri Date</label>
            <input type="text" id="hijri_date" name="hijri_date" placeholder="DD-MM-YYYY" value="<?php echo isset($filters['hijri_date']) ? htmlspecialchars((string)$filters['hijri_date']) : ''; ?>">
          </div>
          <div class="qh-fg" style="max-width:175px;">
            <label for="greg_date">English Date</label>
            <input type="date" id="greg_date" name="greg_date" value="<?php echo isset($filters['greg_date']) ? htmlspecialchars((string)$filters['greg_date']) : ''; ?>">
          </div>
        <?php elseif ($sc_key === 'husain'): ?>
          <div class="qh-fg" style="max-width:175px;">
            <label for="deposit_date">Deposit Date</label>
            <input type="date" id="deposit_date" name="deposit_date" value="<?php echo isset($filters['deposit_date']) ? htmlspecialchars((string)$filters['deposit_date']) : ''; ?>">
          </div>
          <div class="qh-fg" style="max-width:175px;">
            <label for="maturity_date">Maturity Date</label>
            <input type="date" id="maturity_date" name="maturity_date" value="<?php echo isset($filters['maturity_date']) ? htmlspecialchars((string)$filters['maturity_date']) : ''; ?>">
          </div>
          <div class="qh-fg" style="max-width:200px;">
            <label for="duration">Duration</label>
            <input type="text" id="duration" name="duration" placeholder="e.g. 6 months / 1 year" value="<?php echo isset($filters['duration']) ? htmlspecialchars((string)$filters['duration']) : ''; ?>">
          </div>
        <?php endif; ?>

        <?php if (in_array($sc_key, ['taher','husain'], true)): ?>
          <?php
            $combinedSearch = '';
            if (isset($filters['search']) && trim((string)$filters['search']) !== '') {
              $combinedSearch = (string)$filters['search'];
            } elseif (isset($filters['its']) && trim((string)$filters['its']) !== '') {
              $combinedSearch = (string)$filters['its'];
            } elseif (isset($filters['member_name']) && trim((string)$filters['member_name']) !== '') {
              $combinedSearch = (string)$filters['member_name'];
            }
          ?>
          <div class="qh-fg" style="max-width:270px;">
            <label for="search">Member Name / ITS</label>
            <input type="text" id="search" name="search" placeholder="Search by ITS or member name" value="<?php echo htmlspecialchars($combinedSearch); ?>">
          </div>
        <?php endif; ?>

        <div class="qh-filter-actions">
          <a href="<?php echo base_url($qh_scheme_base . '/' . $scheme_key); ?>" class="qh-btn qh-btn-ghost">
            <i class="fa fa-times"></i> Reset
          </a>
          <button type="submit" class="qh-btn qh-btn-primary">
            <i class="fa fa-filter"></i> Apply
          </button>
        </div>
      </form>
    </div>
    <?php endif; ?>

    <!-- ── Table Card ── -->
    <div class="qh-table-card">
      <div class="qh-table-header">
        <div class="qh-table-title"><i class="fa fa-table"></i> Records</div>
        <div class="qh-record-count"><?php echo !empty($records) ? count($records) : 0; ?> record(s)</div>
      </div>
      <div class="qh-table-wrap">
        <table class="qh-tbl" id="qhTable">
          <thead>
            <tr>
              <th class="sortable" data-col="sr">Sr. No. <span class="si"></span></th>
              <?php if ($sc_key === 'mohammedi'): ?>
                <th class="sortable" data-col="uploaded_date">Uploaded Date <span class="si"></span></th>
                <th class="sortable" data-col="miqaat_name">Miqaat Name <span class="si"></span></th>
                <th class="sortable" data-col="hijri_date">Hijri Date <span class="si"></span></th>
                <th class="sortable" data-col="eng_date">English Date <span class="si"></span></th>
                <th class="sortable" data-col="collection_amount" style="text-align:right;">Collection Amount <span class="si"></span></th>
                <?php if ($can_manage): ?><th>Actions</th><?php endif; ?>
              <?php elseif ($sc_key === 'taher'): ?>
                <th class="sortable" data-col="ITS">ITS <span class="si"></span></th>
                <th class="sortable" data-col="member_name">Member Name <span class="si"></span></th>
                <th class="sortable" data-col="unit">Unit (₹) <span class="si"></span></th>
                <th class="sortable" data-col="units" style="text-align:right;">No. of Units <span class="si"></span></th>
                <th class="sortable" data-col="miqaat_name">Miqaat Name <span class="si"></span></th>
                <?php if ($can_manage): ?><th>Actions</th><?php endif; ?>
              <?php else: /* husain */ ?>
                <th class="sortable" data-col="ITS">ITS <span class="si"></span></th>
                <th class="sortable" data-col="member_name">Member Name <span class="si"></span></th>
                <th class="sortable" data-col="amount" style="text-align:right;">Amount <span class="si"></span></th>
                <th class="sortable" data-col="deposit_date">Deposit Date <span class="si"></span></th>
                <th class="sortable" data-col="maturity_date">Maturity Date <span class="si"></span></th>
                <th class="sortable" data-col="duration">Duration <span class="si"></span></th>
                <?php if ($can_manage): ?><th>Actions</th><?php endif; ?>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody id="qhTbody">
            <?php if (!empty($records)): ?>
              <?php $sr = 0; foreach ($records as $r): $sr++; ?>
                <?php
                  $rowIts = isset($r['ITS']) ? htmlspecialchars((string)$r['ITS']) : '';
                  $isClickable = in_array($sc_key, ['taher','husain'], true) && $rowIts;
                ?>
                <tr<?php echo $isClickable ? ' class="qh-clickable" data-its="'.$rowIts.'"' : ''; ?>>
                  <!-- sr -->
                  <td class="qh-tbl-sr" data-val="<?php echo $sr; ?>"><?php echo $sr; ?></td>

                  <?php if ($sc_key === 'mohammedi'): ?>
                    <?php
                      $ud = isset($r['uploaded_date']) ? (string)$r['uploaded_date'] : '';
                      $udFmt = $ud ? date('d-m-Y', strtotime($ud)) : '';
                      $hd = isset($r['hijri_date']) ? (string)$r['hijri_date'] : '';
                      if ($hd !== '' && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $hd, $hm)) {
                        $hd = $hm[3] . '-' . $hm[2] . '-' . $hm[1];
                      }
                      $ed  = isset($r['eng_date']) ? (string)$r['eng_date'] : '';
                      $edFmt = $ed ? date('d-m-Y', strtotime($ed)) : '';
                      $ca  = isset($r['collection_amount']) ? (float)$r['collection_amount'] : 0;
                    ?>
                    <td data-val="<?php echo htmlspecialchars($ud); ?>"><?php echo htmlspecialchars($udFmt); ?></td>
                    <td data-val="<?php echo htmlspecialchars($r['miqaat_name'] ?? ''); ?>"><?php echo htmlspecialchars($r['miqaat_name'] ?? ''); ?></td>
                    <td data-val="<?php echo htmlspecialchars($hd); ?>"><?php echo htmlspecialchars($hd); ?></td>
                    <td data-val="<?php echo htmlspecialchars($ed); ?>"><?php echo htmlspecialchars($edFmt); ?></td>
                    <td class="qh-amount-cell" data-val="<?php echo $ca; ?>" style="text-align:right;">₹<?php echo function_exists('format_inr') ? format_inr($ca, 0) : number_format($ca, 0); ?></td>
                    <?php if ($can_manage): ?>
                    <td>
                      <button type="button" class="qh-act-btn qh-act-edit qh-edit-btn mr-1"
                        data-toggle="modal" data-target="#qhEditModal"
                        data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                        data-miqaat_name="<?php echo htmlspecialchars((string)($r['miqaat_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-hijri_date="<?php
                          $hdAttr = (string)($r['hijri_date'] ?? '');
                          if ($hdAttr !== '' && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $hdAttr, $m2)) {
                            $hdAttr = $m2[3] . '-' . $m2[2] . '-' . $m2[1];
                          }
                          echo htmlspecialchars($hdAttr, ENT_QUOTES, 'UTF-8');
                        ?>"
                        data-eng_date="<?php echo htmlspecialchars((string)($r['eng_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-collection_amount="<?php echo htmlspecialchars((string)($r['collection_amount'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        <i class="fa fa-pencil"></i> Edit
                      </button>
                      <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                        <button type="submit" class="qh-act-btn qh-act-del"><i class="fa fa-trash"></i> Delete</button>
                      </form>
                    </td>
                    <?php endif; ?>

                  <?php elseif ($sc_key === 'taher'): ?>
                    <td data-val="<?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?>"><span class="qh-its-badge"><?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?></span></td>
                    <td data-val="<?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?>"><?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?></td>
                    <td data-val="215">215</td>
                    <td data-val="<?php echo (int)($r['units'] ?? 0); ?>" style="text-align:right; font-weight:600;"><?php echo (int)($r['units'] ?? 0); ?></td>
                    <td data-val="<?php echo htmlspecialchars((string)($r['miqaat_name'] ?? '')); ?>"><?php echo htmlspecialchars((string)($r['miqaat_name'] ?? '')); ?></td>
                    <?php if ($can_manage): ?>
                    <td>
                      <button type="button" class="qh-act-btn qh-act-edit qh-edit-taher-btn"
                        data-toggle="modal" data-target="#qhEditTaherModal"
                        data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                        data-its="<?php echo htmlspecialchars((string)($r['ITS'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-units="<?php echo htmlspecialchars((string)($r['units'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-miqaat_name="<?php echo htmlspecialchars((string)($r['miqaat_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        <i class="fa fa-pencil"></i> Edit
                      </button>
                      <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                        <button type="submit" class="qh-act-btn qh-act-del"><i class="fa fa-trash"></i> Delete</button>
                      </form>
                    </td>
                    <?php endif; ?>

                  <?php else: /* husain */ ?>
                    <?php
                      $dd = isset($r['deposit_date'])  ? (string)$r['deposit_date']  : '';
                      $md = isset($r['maturity_date']) ? (string)$r['maturity_date'] : '';
                      $ddFmt = $dd ? date('d-m-Y', strtotime($dd)) : '';
                      $mdFmt = $md ? date('d-m-Y', strtotime($md)) : '';
                      $amt = (float)($r['amount'] ?? 0);
                    ?>
                    <td data-val="<?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?>"><span class="qh-its-badge"><?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?></span></td>
                    <td data-val="<?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?>"><?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?></td>
                    <td class="qh-amount-cell" data-val="<?php echo $amt; ?>" style="text-align:right;">₹<?php echo function_exists('format_inr') ? format_inr($amt, 0) : number_format($amt, 0); ?></td>
                    <td data-val="<?php echo htmlspecialchars($dd); ?>"><?php echo htmlspecialchars($ddFmt); ?></td>
                    <td data-val="<?php echo htmlspecialchars($md); ?>"><?php echo htmlspecialchars($mdFmt); ?></td>
                    <td data-val="<?php echo htmlspecialchars((string)($r['duration'] ?? '')); ?>"><?php echo htmlspecialchars((string)($r['duration'] ?? '')); ?></td>
                    <?php if ($can_manage): ?>
                    <td>
                      <button type="button" class="qh-act-btn qh-act-edit qh-edit-husain-btn"
                        data-toggle="modal" data-target="#qhEditHusainModal"
                        data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                        data-its="<?php echo htmlspecialchars((string)($r['ITS'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-amount="<?php echo htmlspecialchars((string)($r['amount'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-deposit_date="<?php echo htmlspecialchars((string)($r['deposit_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-maturity_date="<?php echo htmlspecialchars((string)($r['maturity_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        data-duration="<?php echo htmlspecialchars((string)($r['duration'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        <i class="fa fa-pencil"></i> Edit
                      </button>
                      <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                        <button type="submit" class="qh-act-btn qh-act-del"><i class="fa fa-trash"></i> Delete</button>
                      </form>
                    </td>
                    <?php endif; ?>

                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr class="qh-empty-row">
                <td colspan="<?php
                  $baseCols = ($sc_key === 'taher') ? 6 : (($sc_key === 'husain') ? 7 : 7);
                  echo $baseCols + ($can_manage ? 1 : 0);
                ?>">
                  <i class="fa fa-search"></i>
                  No records found for the selected filters.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div><!-- /qh-table-wrap -->
    </div><!-- /qh-table-card -->

    <!-- ══════ MODALS ══════ -->
    <?php if ($can_manage): ?>

    <!-- Import modal -->
    <div class="modal fade qh-modal" id="qhImportModal" tabindex="-1" role="dialog" aria-labelledby="qhImportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qhImportModalLabel"><i class="fa fa-upload"></i> Import CSV</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/import'); ?>" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="form-group">
                <label for="qh-import-file">Upload CSV File</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="import_file" id="qh-import-file" accept=".csv,text/csv" required>
                  <label class="custom-file-label" for="qh-import-file">Choose CSV file</label>
                </div>
                <div class="form-text text-muted mt-2 small">
                  <div>Only <code>.csv</code> files are supported.</div>
                  <?php if ($sc_key === 'mohammedi'): ?>
                    <div class="mt-1">Columns: <strong>Miqaat Name</strong>, <strong>Hijri Date</strong>, <strong>English Date</strong>, <strong>Collection Amount</strong>.</div>
                  <?php elseif ($sc_key === 'taher'): ?>
                    <div class="mt-1">Columns: <strong>ITS</strong>, <strong>Unit = 215₹</strong>, <strong>No of 215₹ Units</strong>, <strong>Miqaat Name</strong>.</div>
                  <?php elseif ($sc_key === 'husain'): ?>
                    <div class="mt-1">Columns: <strong>ITS</strong>, <strong>Amount</strong>, <strong>Deposit Date</strong>, <strong>Maturity Date</strong>, <strong>Duration</strong>.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="qh-btn qh-btn-ghost" data-dismiss="modal">Cancel</button>
              <button type="submit" class="qh-btn qh-btn-primary" id="qh-upload-submit" disabled>
                <i class="fa fa-upload"></i> Upload
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit modal (Mohammedi) -->
    <div class="modal fade qh-modal" id="qhEditModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <form method="post" id="qh-edit-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-id">
              <div class="form-group"><label>Miqaat Name</label><input type="text" class="form-control" name="miqaat_name" id="qh-edit-miqaat-name" required></div>
              <div class="form-group"><label>Hijri Date</label><input type="text" class="form-control" name="hijri_date" id="qh-edit-hijri-date" placeholder="DD-MM-YYYY" required></div>
              <div class="form-group"><label>English Date</label><input type="date" class="form-control" name="eng_date" id="qh-edit-eng-date" required></div>
              <div class="form-group mb-0"><label>Collection Amount</label><input type="number" step="0.01" class="form-control" name="collection_amount" id="qh-edit-amount" required></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="qh-btn qh-btn-ghost" data-dismiss="modal">Cancel</button>
              <button type="submit" class="qh-btn qh-btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit modal (Taher) -->
    <div class="modal fade qh-modal" id="qhEditTaherModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <form method="post" id="qh-edit-taher-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-taher-id">
              <div class="form-group"><label>ITS</label><input type="text" class="form-control" name="ITS" id="qh-edit-taher-its" required></div>
              <div class="form-group"><label>Unit</label><input type="text" class="form-control" value="215" readonly><input type="hidden" name="unit" id="qh-edit-taher-unit" value="215"></div>
              <div class="form-group"><label>No of 215₹ Units</label><input type="number" class="form-control" name="units" id="qh-edit-taher-units" min="0" step="1" required></div>
              <div class="form-group mb-0"><label>Miqaat Name</label><input type="text" class="form-control" name="miqaat_name" id="qh-edit-taher-miqaat" required></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="qh-btn qh-btn-ghost" data-dismiss="modal">Cancel</button>
              <button type="submit" class="qh-btn qh-btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit modal (Husain) -->
    <div class="modal fade qh-modal" id="qhEditHusainModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <form method="post" id="qh-edit-husain-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-husain-id">
              <div class="form-group"><label>ITS</label><input type="text" class="form-control" name="ITS" id="qh-edit-husain-its" required></div>
              <div class="form-group"><label>Amount</label><input type="number" step="0.01" class="form-control" name="amount" id="qh-edit-husain-amount" required></div>
              <div class="form-group"><label>Deposit Date</label><input type="date" class="form-control" name="deposit_date" id="qh-edit-husain-deposit"></div>
              <div class="form-group"><label>Maturity Date</label><input type="date" class="form-control" name="maturity_date" id="qh-edit-husain-maturity"></div>
              <div class="form-group mb-0"><label>Duration</label><input type="text" class="form-control" name="duration" id="qh-edit-husain-duration" placeholder="e.g. 12 Months / 1 Year"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="qh-btn qh-btn-ghost" data-dismiss="modal">Cancel</button>
              <button type="submit" class="qh-btn qh-btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php endif; /* can_manage */ ?>

  <?php else: ?>
    <div class="qh-table-card" style="padding:24px;text-align:center;color:var(--text-3);">This scheme page is ready for wiring up as needed.</div>
  <?php endif; ?>

</div><!-- /#qhSchApp -->

<script>
(function() {
  /* ── File import enable/disable ── */
  var fileInp = document.getElementById('qh-import-file');
  var submitBtn = document.getElementById('qh-upload-submit');
  if (fileInp && submitBtn) {
    var lbl = document.querySelector('label[for="qh-import-file"].custom-file-label');
    function updFile() {
      submitBtn.disabled = !(fileInp.files && fileInp.files.length > 0);
      if (lbl) lbl.textContent = (fileInp.files && fileInp.files.length > 0) ? fileInp.files[0].name : 'Choose CSV file';
    }
    fileInp.addEventListener('change', updFile);
    updFile();
  }

  /* ── Edit modal wiring (Mohammedi) ── */
  var editForm = document.getElementById('qh-edit-form');
  if (editForm) {
    var baseAct = editForm.getAttribute('action') || '';
    var setV = function(id, v) { var el=document.getElementById(id); if(el) el.value = (v==null)?'':v; };
    document.querySelectorAll('.qh-edit-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = btn.getAttribute('data-id') || '';
        setV('qh-edit-id', id);
        setV('qh-edit-miqaat-name', btn.getAttribute('data-miqaat_name') || '');
        setV('qh-edit-hijri-date', btn.getAttribute('data-hijri_date') || '');
        setV('qh-edit-eng-date', btn.getAttribute('data-eng_date') || '');
        setV('qh-edit-amount', btn.getAttribute('data-collection_amount') || '');
        var a = baseAct; if(a.slice(-1)!=='/') a+='/'; editForm.setAttribute('action', a+encodeURIComponent(id));
      });
    });
  }

  /* ── Edit modal wiring (Taher) ── */
  var taherForm = document.getElementById('qh-edit-taher-form');
  if (taherForm) {
    var baseActT = taherForm.getAttribute('action') || '';
    var setVT = function(id, v) { var el=document.getElementById(id); if(el) el.value = (v==null)?'':v; };
    document.querySelectorAll('.qh-edit-taher-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = btn.getAttribute('data-id') || '';
        setVT('qh-edit-taher-id', id);
        setVT('qh-edit-taher-its', btn.getAttribute('data-its') || '');
        setVT('qh-edit-taher-unit', '215');
        setVT('qh-edit-taher-units', btn.getAttribute('data-units') || '0');
        setVT('qh-edit-taher-miqaat', btn.getAttribute('data-miqaat_name') || '');
        var a = baseActT; if(a.slice(-1)!=='/') a+='/'; taherForm.setAttribute('action', a+encodeURIComponent(id));
      });
    });
  }

  /* ── Edit modal wiring (Husain) ── */
  var husainForm = document.getElementById('qh-edit-husain-form');
  if (husainForm) {
    var baseActH = husainForm.getAttribute('action') || '';
    var setVH = function(id, v) { var el=document.getElementById(id); if(el) el.value = (v==null)?'':v; };
    document.querySelectorAll('.qh-edit-husain-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = btn.getAttribute('data-id') || '';
        setVH('qh-edit-husain-id', id);
        setVH('qh-edit-husain-its', btn.getAttribute('data-its') || '');
        setVH('qh-edit-husain-amount', btn.getAttribute('data-amount') || '0');
        setVH('qh-edit-husain-deposit', btn.getAttribute('data-deposit_date') || '');
        setVH('qh-edit-husain-maturity', btn.getAttribute('data-maturity_date') || '');
        setVH('qh-edit-husain-duration', btn.getAttribute('data-duration') || '');
        var a = baseActH; if(a.slice(-1)!=='/') a+='/'; husainForm.setAttribute('action', a+encodeURIComponent(id));
      });
    });
  }

  /* ── Clickable rows → member profile ── */
  var tbody = document.getElementById('qhTbody');
  if (tbody) {
    tbody.addEventListener('click', function(e) {
      if (e.target.closest('button, a, input, select, form')) return;
      var row = e.target.closest('tr.qh-clickable');
      if (row) {
        var its = row.getAttribute('data-its');
        if (its) window.location.href = '<?php echo base_url("admin/viewmember/"); ?>' + its;
      }
    });
  }

  /* ══════════════════════════════════
     TABLE SORTING
     Uses data-val attributes on <td>
     ══════════════════════════════════ */
  var table = document.getElementById('qhTable');
  if (!table) return;

  var sortCol  = null;
  var sortDir  = 'asc';

  table.querySelectorAll('th.sortable').forEach(function(th, thIdx) {
    th.addEventListener('click', function() {
      var col = th.getAttribute('data-col');
      if (sortCol === col) {
        sortDir = (sortDir === 'asc') ? 'desc' : 'asc';
      } else {
        sortCol = col;
        sortDir = 'asc';
      }

      /* update header classes */
      table.querySelectorAll('th.sortable').forEach(function(h) {
        h.classList.remove('asc', 'desc');
      });
      th.classList.add(sortDir);

      /* collect rows */
      var tBody  = table.querySelector('tbody');
      var rows   = Array.from(tBody.querySelectorAll('tr:not(.qh-empty-row)'));
      if (!rows.length) return;

      /* header cell index for this column */
      var headers = Array.from(table.querySelectorAll('thead th'));
      var colIdx  = headers.indexOf(th);
      if (colIdx < 0) return;

      rows.sort(function(a, b) {
        var tdA = a.querySelectorAll('td')[colIdx];
        var tdB = b.querySelectorAll('td')[colIdx];
        if (!tdA || !tdB) return 0;
        var vA = (tdA.getAttribute('data-val') || tdA.textContent || '').trim();
        var vB = (tdB.getAttribute('data-val') || tdB.textContent || '').trim();

        /* numeric sort */
        var nA = parseFloat(vA.replace(/[₹,]/g,''));
        var nB = parseFloat(vB.replace(/[₹,]/g,''));
        if (!isNaN(nA) && !isNaN(nB)) {
          return sortDir === 'asc' ? nA - nB : nB - nA;
        }

        /* date sort (YYYY-MM-DD stored in data-val) */
        var dA = new Date(vA), dB = new Date(vB);
        if (!isNaN(dA) && !isNaN(dB)) {
          return sortDir === 'asc' ? dA - dB : dB - dA;
        }

        /* string sort */
        return sortDir === 'asc'
          ? vA.localeCompare(vB, undefined, {sensitivity:'base'})
          : vB.localeCompare(vA, undefined, {sensitivity:'base'});
      });

      /* re-append sorted rows */
      rows.forEach(function(r) { tBody.appendChild(r); });

      /* re-number the Sr. No. column (always col 0) */
      rows.forEach(function(r, i) {
        var srTd = r.querySelector('td.qh-tbl-sr');
        if (srTd) { srTd.textContent = i + 1; srTd.setAttribute('data-val', i + 1); }
      });
    });
  });
})();
</script>