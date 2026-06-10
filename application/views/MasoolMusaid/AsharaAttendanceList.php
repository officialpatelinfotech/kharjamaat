<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#b8860b;--gold-light:#e6c84a;--gold-muted:#f5e9c0;
  --bg:#faf7f0;--surface:#fff;--surface-2:#f7f4ec;
  --border:#e8e0cc;--border-light:#f0ece0;
  --text-1:#1a1610;--text-2:#5a5244;--text-3:#9c8f7a;
  --green:#1a6645;--green-bg:#eaf4ee;--green-border:rgba(26,102,69,.25);
  --red:#b91c1c;--red-bg:#fef2f2;--red-border:rgba(185,28,28,.2);
  --blue:#1d4ed8;--blue-bg:#eff6ff;--blue-border:rgba(29,78,216,.2);
  --amber:#b45309;--amber-bg:#fffbeb;--amber-border:rgba(180,83,9,.2);
  --teal:#0891b2;--teal-bg:#ecfeff;
  --purple:#5b21b6;--purple-bg:#f5f3ff;
  --slate:#334155;--slate-bg:#f1f5f9;
  --sh:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --sh2:0 4px 16px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
  --sh3:0 8px 32px rgba(0,0,0,.12),0 2px 8px rgba(0,0,0,.05);
}
html{background:var(--bg)}
#ashApp{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text-1);min-height:100vh;margin-top: 60px;}
#ashApp *{box-sizing:border-box}

/* ── Page header banner ── */
#ashApp .ash-banner{background:linear-gradient(135deg,#78520a 0%,var(--gold) 60%,#c9a227 100%);padding:14px 20px;position:relative;overflow:hidden}
#ashApp .ash-banner::after{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);pointer-events:none}
#ashApp .ash-banner-inner{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;position:relative;z-index:1}
#ashApp .ash-banner-left{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
#ashApp .ash-back{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.15);color:#fff;font-size:.74rem;font-weight:700;text-decoration:none;transition:background .15s;backdrop-filter:blur(4px)}
#ashApp .ash-back:hover{background:rgba(255,255,255,.25);text-decoration:none;color:#fff}
#ashApp .ash-banner-title{font-size:.95rem;font-weight:800;color:#fff;line-height:1.2}
#ashApp .ash-banner-sub{font-size:.7rem;color:rgba(255,255,255,.75);font-weight:500;margin-top:2px}
#ashApp .ash-banner-right{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
#ashApp .ash-count-badge{background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);border-radius:20px;padding:4px 12px;color:#fff;font-size:.72rem;font-weight:700;backdrop-filter:blur(4px);display:flex;align-items:center;gap:5px}
#ashApp .ash-year-sel{height:30px;padding:0 8px;border-radius:7px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.15);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-size:.73rem;font-weight:600;outline:none;backdrop-filter:blur(4px);cursor:pointer}
#ashApp .ash-year-sel option{background:#78520a;color:#fff}
#ashApp .ash-bulk-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.15);color:#fff;font-size:.73rem;font-weight:700;cursor:pointer;transition:background .15s;backdrop-filter:blur(4px);font-family:'Plus Jakarta Sans',sans-serif}
#ashApp .ash-bulk-btn:hover{background:rgba(255,255,255,.25)}

/* ── Filter bar ── */
#ashApp .ash-filters{background:var(--surface);border-bottom:1px solid var(--border);padding:10px 16px;position:sticky;top:57px;z-index:50;box-shadow:var(--sh)}
#ashApp .ash-frow{display:flex;align-items:center;gap:7px;flex-wrap:wrap}
#ashApp .ash-fsel,#ashApp .ash-finput{height:30px;padding:0 9px;border:1.5px solid var(--border);border-radius:7px;background:var(--surface-2);font-family:'Plus Jakarta Sans',sans-serif;font-size:.73rem;color:var(--text-1);outline:none;transition:border-color .15s}
#ashApp .ash-fsel:focus,#ashApp .ash-finput:focus{border-color:var(--gold);background:var(--surface)}
#ashApp .ash-srchwrap{position:relative;flex:1;min-width:160px}
#ashApp .ash-srchwrap i{position:absolute;left:8px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:.72rem;pointer-events:none}
#ashApp .ash-finput{padding:0 8px 0 26px;width:100%}
#ashApp .ash-export-btn{display:inline-flex;align-items:center;gap:5px;padding:0 12px;height:30px;border-radius:7px;background:var(--green-bg);border:1.5px solid var(--green-border);color:var(--green);font-size:.72rem;font-weight:700;cursor:pointer;margin-left:auto;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif}
#ashApp .ash-export-btn:hover{background:#d1fae5}

/* ── Table ── */
#ashApp .ash-tcard{background:var(--surface);border-top:1px solid var(--border)}
#ashApp .ash-tscroll{overflow-x:auto;max-height:70vh;overflow-y:auto}
#ashApp .ash-tscroll::-webkit-scrollbar{width:5px;height:5px}
#ashApp .ash-tscroll::-webkit-scrollbar-track{background:transparent}
#ashApp .ash-tscroll::-webkit-scrollbar-thumb{background:var(--border);border-radius:10px}
table.ash{width:100%;border-collapse:collapse;font-size:.76rem;min-width:820px}
table.ash thead th{background:var(--surface-2);padding:8px 10px;font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:var(--text-3);border-bottom:2px solid var(--border);white-space:nowrap;position:sticky;top:0;z-index:5;text-align:left}
table.ash thead th.c{text-align:center}
table.ash tbody tr{border-bottom:1px solid var(--border-light);transition:background .1s;cursor:pointer}
table.ash tbody tr:hover{background:#fdf9ef}
table.ash td{padding:7px 10px;vertical-align:middle;color:var(--text-1)}
table.ash td.c{text-align:center;padding:5px 3px}

/* ── Attendance day buttons ── */
#ashApp .ab{width:26px;height:26px;border-radius:6px;border:none;font-size:.65rem;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:transform .1s,opacity .15s;line-height:1}
#ashApp .ab:hover{transform:scale(1.12);opacity:.85}
#ashApp .ab-maula{background:var(--green);color:#fff}
#ashApp .ab-ontime{background:var(--green);color:#fff}
#ashApp .ab-late{background:#f59e0b;color:#fff}
#ashApp .ab-other{background:#f59e0b;color:#fff}
#ashApp .ab-absent{background:var(--red);color:#fff}
#ashApp .ab-town{background:#334155;color:#fff}
#ashApp .ab-outcaste{background:#7c3aed;color:#fff}
#ashApp .ab-unmarked{background:var(--surface-2);color:var(--text-3);border:1px solid var(--border)}

/* ── Footer ── */
#ashApp .ash-footer{padding:10px 16px;border-top:1px solid var(--border);background:var(--surface-2);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px}
#ashApp .ash-footer-time{font-size:.68rem;color:var(--text-3)}
#ashApp .ash-result-count{font-size:.72rem;color:var(--text-2);font-weight:700}
#ashApp .ash-result-count span{color:var(--gold);font-weight:800}

/* ── Modals ── */
#ashApp .ash-ov{position:fixed;inset:0;background:rgba(26,22,16,.48);z-index:1000;display:none;align-items:center;justify-content:center;padding:14px}
#ashApp .ash-ov.open{display:flex}
#ashApp .ash-modal{background:var(--surface);border:1px solid var(--border);border-radius:18px;width:100%;box-shadow:var(--sh3);max-height:90vh;display:flex;flex-direction:column}
#ashApp .ash-mhd{display:flex;align-items:center;justify-content:space-between;padding:13px 16px;border-bottom:1px solid var(--border-light);flex-shrink:0;background:var(--surface-2);border-radius:18px 18px 0 0}
#ashApp .ash-mtit{font-weight:800;font-size:.88rem;color:var(--text-1);display:flex;align-items:center;gap:6px}
#ashApp .ash-mclose{width:28px;height:28px;border-radius:7px;border:none;background:var(--border);color:var(--text-2);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;transition:all .14s}
#ashApp .ash-mclose:hover{background:var(--gold-muted);color:var(--gold)}
#ashApp .ash-mbody{overflow-y:auto;flex:1;padding:14px 16px}
#ashApp .ash-mft{padding:11px 16px;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;gap:7px;flex-shrink:0}
#ashApp .ash-lbl{display:block;font-size:.67rem;font-weight:800;color:var(--text-2);margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
#ashApp .ash-inp,#ashApp .ash-sel,#ashApp .ash-ta{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:8px 10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;color:var(--text-1);background:var(--surface-2);outline:none;transition:border-color .15s;margin-bottom:12px}
#ashApp .ash-inp:focus,#ashApp .ash-sel:focus,#ashApp .ash-ta:focus{border-color:var(--gold);background:var(--surface)}
#ashApp .ash-sel{height:36px;padding:0 10px}
#ashApp .ash-ta{resize:vertical;min-height:70px}
#ashApp .btn-c{padding:7px 15px;border-radius:8px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .14s}
#ashApp .btn-c:hover{background:var(--surface-2)}
#ashApp .btn-ok{padding:7px 15px;border-radius:8px;border:none;background:var(--gold);color:#fff;font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:opacity .14s}
#ashApp .btn-ok:hover{opacity:.88}
#ashApp .btn-green{padding:7px 15px;border-radius:8px;border:none;background:var(--green);color:#fff;font-weight:700;font-size:.75rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:opacity .14s}

/* ── Toast ── */
#ashApp .ash-toast{position:fixed;top:14px;right:14px;padding:9px 16px;border-radius:10px;font-size:.78rem;font-weight:700;z-index:9999;display:none;box-shadow:var(--sh2);animation:ashTin .25s}
#ashApp .ash-toast.ok{background:var(--green);color:#fff}
#ashApp .ash-toast.er{background:var(--red);color:#fff}
@keyframes ashTin{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}

/* ── Export modal checkboxes ── */
#ashApp .exp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:6px;margin-top:8px}
#ashApp .exp-item{display:flex;align-items:center;gap:5px;padding:5px 9px;border-radius:7px;background:var(--surface-2);border:1.5px solid var(--border);font-size:.73rem;font-weight:600;cursor:pointer;transition:border-color .15s}
#ashApp .exp-item:has(input:checked){background:var(--gold-muted);border-color:rgba(184,134,11,.4);color:var(--gold)}
#ashApp .exp-item input{accent-color:var(--gold);cursor:pointer}

/* ── Responsive ── */
@media(max-width:640px){
  #ashApp .ash-banner{padding:10px 12px}
  #ashApp .ash-filters{padding:8px 12px;top:57px}
  table.ash{min-width:680px}
}
</style>

<?php
/* ── Status config ── */
if (!function_exists('ash_status_class')) {
  function ash_status_class($stat) {
    return match(trim($stat)) {
      'Attended with Maula'      => 'ab-maula',
      'Attended in Khar on Time' => 'ab-ontime',
      'Attended in Khar Late'    => 'ab-late',
      'Attended in Other Jamaat' => 'ab-other',
      'Not attended anywhere'    => 'ab-absent',
      'Not in Town'              => 'ab-town',
      'Married Outcaste'         => 'ab-outcaste',
      default                    => 'ab-unmarked',
    };
  }
}
if (!function_exists('ash_btn')) {
  function ash_btn($u, $day) {
    $key     = $day === 'Ashura' ? 'Ashura'         : "Day$day";
    $ckey    = $day === 'Ashura' ? 'CommentAshura'  : "Comment$day";
    $stat    = $u[$key]  ?? 'Not Marked';
    $comment = htmlspecialchars($u[$ckey] ?? '', ENT_QUOTES);
    $label   = $day === 'Ashura' ? 'A' : "D$day";
    $cls     = ash_status_class($stat);
    return "<button class='ab $cls' data-its='{$u['ITS_ID']}' data-day='$day' data-status='".htmlspecialchars($stat,ENT_QUOTES)."' data-comment='$comment' title='".htmlspecialchars($stat,ENT_QUOTES).($comment?": $comment":'')."'>$label</button>";
  }
}
?>
<?php
$role = isset($_SESSION['user']['role']) ? (int)$_SESSION['user']['role'] : 0;
$can_edit = ($role >= 2 && $role <= 16) || in_array($user_name ?? '', ['amilsaheb','jamaat']);
$jp         = htmlspecialchars(jamaat_place() ?? 'Khar', ENT_QUOTES);

$view_member_base = 'admin/viewmember/';
if ($this->session->userdata('role') === 'amilsaheb' || (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2)) {
  $view_member_base = 'amilsaheb/viewmember/';
} else if ($this->session->userdata('role') === 'MasoolMusaid' || (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 16)) {
  $view_member_base = 'MasoolMusaid/viewmember/';
}

// Generate the Dashboard URL back from List view
$dash_query = [];
if ($this->input->get('sector')) {
  $dash_query['sector'] = $this->input->get('sector');
}
if ($this->input->get('subsector')) {
  $dash_query['subsector'] = $this->input->get('subsector');
}
if (isset($selected_year)) {
  $dash_query['year'] = $selected_year;
}
$dash_url = base_url(str_replace('ashara_attendance_list', 'ashara_attendance', $this->uri->uri_string()) . ($dash_query ? '?' . http_build_query($dash_query) : ''));
?>

<div id="ashApp">
  <div class="ash-toast" id="ashToast"></div>

  <!-- ── Banner ── -->
  <div class="ash-banner">
    <div class="ash-banner-inner">
      <div class="ash-banner-left">
        <a href="<?php echo $dash_url ?>" class="ash-back">
          <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
        <div>
          <div class="ash-banner-title">
            <i class="fa-solid fa-table-list" style="margin-right:6px"></i>
            Attendance List: <?php echo htmlspecialchars($sel_sector.($sel_sub?" – $sel_sub":''), ENT_QUOTES) ?>
          </div>
          <div class="ash-banner-sub">Detailed Ashara Attendance Sheet</div>
        </div>
      </div>
      <div class="ash-banner-right">
        <?php if (!empty($year_options) && is_array($year_options)): ?>
        <select id="yearSelect" class="ash-year-sel">
          <?php foreach ($year_options as $y): ?>
          <option value="<?php echo (int)$y ?>" <?php echo (isset($selected_year)&&(int)$selected_year===(int)$y)?'selected':'' ?>><?php echo (int)$y ?>H</option>
          <?php endforeach ?>
        </select>
        <?php endif ?>
        <div class="ash-count-badge" id="dashCountBadge">
          <i class="fa-solid fa-users fa-xs"></i> <?php echo count($users) ?> Mumineen
        </div>
        <?php if ($can_edit): ?>
        <button class="ash-bulk-btn" onclick="ashOM('modBulk')">
          <i class="fa-solid fa-bolt fa-xs"></i> Bulk Update
        </button>
        <?php endif ?>
      </div>
    </div>
  </div>

  <!-- ── Filter bar ── -->
  <div class="ash-filters">
    <div class="ash-frow">
      <div class="ash-srchwrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" class="ash-finput" id="ashSearch" placeholder="Search name, ITS, sector…" oninput="ashFilter()">
      </div>
      <select class="ash-fsel" id="ashSector" onchange="ashFilter()">
        <option value="">All Sectors</option>
        <?php foreach (array_unique(array_filter(array_column($users,'Sector'))) as $sec): ?>
        <option value="<?php echo htmlspecialchars($sec,ENT_QUOTES) ?>" <?php echo ($sel_sector === $sec) ? 'selected' : '' ?>><?php echo htmlspecialchars($sec,ENT_QUOTES) ?></option>
        <?php endforeach ?>
      </select>
      <select class="ash-fsel" id="ashSub" onchange="ashFilter()">
        <option value="">All Sub-Sectors</option>
        <?php foreach (array_unique(array_filter(array_column($users,'Sub_Sector'))) as $sub): ?>
        <option value="<?php echo htmlspecialchars($sub,ENT_QUOTES) ?>" <?php echo ($sel_sub === $sub) ? 'selected' : '' ?>><?php echo htmlspecialchars($sub,ENT_QUOTES) ?></option>
        <?php endforeach ?>
      </select>
      <select class="ash-fsel" id="ashCommonStatus" onchange="ashFilter()">
        <option value="">All Days Common Status</option>
        <option value="Attended with Maula">Attended with Maula</option>
        <option value="Attended in <?php echo $jp ?> on Time">Attended in <?php echo $jp ?> on Time</option>
        <option value="Attended in <?php echo $jp ?> Late">Attended in <?php echo $jp ?> Late</option>
        <option value="Attended in Other Jamaat">Attended in Other Jamaat</option>
        <option value="Not attended anywhere">Not attended anywhere</option>
        <option value="Not Marked">Not Marked</option>
      </select>
      <select class="ash-fsel" id="ashMemberStatus" onchange="ashFilter()">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
        <option value="All">All Member Statuses</option>
      </select>
      <button class="ash-export-btn" onclick="ashOM('modExport')">
        <i class="fa-solid fa-download fa-xs"></i> Export
      </button>
    </div>
  </div>

  <!-- ── Table ── -->
  <div class="ash-tcard">
    <div class="ash-tscroll">
      <table class="ash" id="ashTable">
        <thead>
          <tr>
            <th>HOF ID</th>
            <th>ITS</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Sector</th>
            <th>Sub-Sector</th>
            <?php foreach ($days as $d): ?>
            <th class="c">D<?php echo $d ?></th>
            <?php endforeach ?>
            <th class="c" style="color:var(--red)">A</th>
          </tr>
        </thead>
        <tbody id="ashTbody">
          <?php foreach ($users as $u): ?>
          <tr data-name="<?php echo htmlspecialchars(strtolower($u['Full_Name']??''),ENT_QUOTES) ?>"
              data-its="<?php echo htmlspecialchars($u['ITS_ID']??'',ENT_QUOTES) ?>"
              data-hof="<?php echo htmlspecialchars($u['HOF_ID']??'',ENT_QUOTES) ?>"
              data-sector="<?php echo htmlspecialchars($u['Sector']??'',ENT_QUOTES) ?>"
              data-sub="<?php echo htmlspecialchars($u['Sub_Sector']??'',ENT_QUOTES) ?>"
              data-inactive="<?php echo htmlspecialchars($u['Inactive_Status']??'',ENT_QUOTES) ?>"
              data-activity="<?php echo htmlspecialchars($u['activity_status']??'',ENT_QUOTES) ?>">
            <td style="font-weight:700;font-size:.72rem;color:var(--text-2)"><?php echo htmlspecialchars($u['HOF_ID']??'—',ENT_QUOTES) ?></td>
            <td style="font-size:.72rem;color:var(--text-2)"><?php echo htmlspecialchars($u['ITS_ID']??'—',ENT_QUOTES) ?></td>
            <td style="font-weight:600;min-width:130px"><?php echo htmlspecialchars($u['Full_Name']??'—',ENT_QUOTES) ?></td>
            <td style="font-size:.72rem;color:var(--text-2);white-space:nowrap"><?php echo htmlspecialchars($u['Mobile']??'—',ENT_QUOTES) ?></td>
            <td style="font-size:.74rem"><?php echo htmlspecialchars($u['Sector']??'—',ENT_QUOTES) ?></td>
            <td style="font-size:.72rem;color:var(--text-2)"><?php echo htmlspecialchars($u['Sub_Sector']??'—',ENT_QUOTES) ?></td>
            <?php foreach ($days as $d): ?>
            <td class="c"><?php echo ash_btn($u, $d) ?></td>
            <?php endforeach ?>
            <td class="c"><?php echo ash_btn($u, 'Ashura') ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Footer ── -->
  <div class="ash-footer">
    <span class="ash-footer-time">Updated: <?php echo date('M j, Y H:i') ?></span>
    <span class="ash-result-count" id="ashResultCount"><span><?php echo count($users) ?></span> members shown</span>
  </div>

  <!-- ═══════════ MODALS ═══════════ -->

  <!-- Edit attendance -->
  <div class="ash-ov" id="modEdit">
    <div class="ash-modal" style="max-width:420px">
      <div class="ash-mhd">
        <span class="ash-mtit"><i class="fa-solid fa-pen-to-square" style="color:var(--gold)"></i> Update Attendance</span>
        <button class="ash-mclose" onclick="ashCM('modEdit')">&#x2715;</button>
      </div>
      <div class="ash-mbody">
        <div id="editMeta" style="font-size:.75rem;color:var(--text-2);margin-bottom:12px;padding:8px 10px;background:var(--surface-2);border-radius:8px;border:1px solid var(--border-light)"></div>
        <label class="ash-lbl">Status</label>
        <select class="ash-sel" id="editStatus">
          <option value="Not Marked">Select</option>
          <option>Attended with Maula</option>
          <option>Attended in <?php echo $jp ?> on Time</option>
          <option>Attended in <?php echo $jp ?> Late</option>
          <option>Attended in Other Jamaat</option>
          <option>Not attended anywhere</option>
        </select>
        <label class="ash-lbl">Comment</label>
        <textarea class="ash-ta" id="editComment" placeholder="Add notes…"></textarea>
        <input type="hidden" id="editIts">
        <input type="hidden" id="editDay">
      </div>
      <div class="ash-mft">
        <button class="btn-c" onclick="ashCM('modEdit')">Cancel</button>
        <button class="btn-ok" id="editSaveBtn" onclick="ashSaveAttendance()">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- Bulk update -->
  <?php if ($can_edit): ?>
  <div class="ash-ov" id="modBulk">
    <div class="ash-modal" style="max-width:520px">
      <div class="ash-mhd">
        <span class="ash-mtit"><i class="fa-solid fa-bolt" style="color:var(--amber)"></i> Bulk Attendance Update</span>
        <button class="ash-mclose" onclick="ashCM('modBulk')">&#x2715;</button>
      </div>
      <div class="ash-mbody">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
          <div>
            <label class="ash-lbl">Select Day</label>
            <select class="ash-sel" id="bulkDay">
              <?php foreach (range(2,9) as $d): ?>
              <option value="<?php echo $d ?>">Day <?php echo $d ?></option>
              <?php endforeach ?>
              <option value="Ashura">Ashura</option>
            </select>
          </div>
          <div>
            <label class="ash-lbl">Select Status</label>
            <select class="ash-sel" id="bulkStatus">
              <option value="">Select Status</option>
              <option>Attended with Maula</option>
              <option>Attended in <?php echo $jp ?> on Time</option>
              <option>Attended in <?php echo $jp ?> Late</option>
              <option>Attended in Other Jamaat</option>
              <option>Not attended anywhere</option>
            </select>
          </div>
        </div>
        <label class="ash-lbl">ITS Numbers <span style="font-weight:400;text-transform:none;color:var(--text-3)">(comma or newline separated)</span></label>
        <textarea class="ash-ta" id="bulkITS" rows="5" placeholder="20312345, 20345678&#10;or each on a new line" style="min-height:100px"></textarea>
      </div>
      <div class="ash-mft">
        <button class="btn-c" onclick="ashCM('modBulk')">Cancel</button>
        <button class="btn-ok" onclick="ashBulkUpdate()"><i class="fa-solid fa-sync-alt fa-xs"></i> Update</button>
      </div>
    </div>
  </div>
  <?php endif ?>

  <!-- Export -->
  <div class="ash-ov" id="modExport">
    <div class="ash-modal" style="max-width:440px">
      <div class="ash-mhd">
        <span class="ash-mtit"><i class="fa-solid fa-file-export" style="color:var(--green)"></i> Export Data</span>
        <button class="ash-mclose" onclick="ashCM('modExport')">&#x2715;</button>
      </div>
      <div class="ash-mbody">
        <label class="ash-lbl">Select Days</label>
        <div style="margin-bottom:8px">
          <label class="exp-item" style="display:inline-flex">
            <input type="checkbox" id="selAllDays" checked onchange="toggleAllDays(this)"> Select All
          </label>
        </div>
        <div class="exp-grid" id="expGrid">
          <?php foreach ($days as $d): ?>
          <label class="exp-item">
            <input type="checkbox" class="day-cb" value="Day<?php echo $d ?>" checked> Day <?php echo $d ?>
          </label>
          <?php endforeach ?>
          <label class="exp-item">
            <input type="checkbox" class="day-cb" value="Ashura" checked> Ashura
          </label>
        </div>
      </div>
      <div class="ash-mft">
        <button class="btn-c" onclick="ashCM('modExport')">Cancel</button>
        <button class="btn-green" onclick="ashExport()"><i class="fa-solid fa-download fa-xs"></i> Export CSV</button>
      </div>
    </div>
  </div>

</div><!-- /#ashApp -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
/* ── Data ── */
const ASH_USERS = <?php echo json_encode($users, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;
const ASH_DAYS  = <?php echo json_encode($days) ?>;
const BASE_URL  = '<?php echo base_url() ?>';
const VIEW_MEMBER_BASE_URL = '<?php echo base_url($view_member_base) ?>';
const SELECTED_YEAR = '<?php echo (int)($selected_year ?? 1447) ?>';

/* ── Modal helpers ── */
function ashOM(id){document.getElementById(id).classList.add('open')}
function ashCM(id){document.getElementById(id).classList.remove('open')}
document.addEventListener('click',e=>{if(e.target.classList.contains('ash-ov'))e.target.classList.remove('open')});
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.querySelectorAll('#ashApp .ash-ov.open').forEach(m=>m.classList.remove('open'))});

/* ── Toast ── */
function ashToast(msg,type='ok'){const t=document.getElementById('ashToast');t.textContent=msg;t.className='ash-toast '+type;t.style.display='block';clearTimeout(t._t);t._t=setTimeout(()=>t.style.display='none',2600)}

/* ── Year switcher ── */
const yearSel=document.getElementById('yearSelect');
if(yearSel)yearSel.addEventListener('change',function(){const u=new URL(window.location.href);u.searchParams.set('year',this.value);window.location.href=u.toString()});

/* ── Status → CSS class ── */
const STATUS_CLS={
  'Attended with Maula':'ab-maula',
  'Attended in Khar on Time':'ab-ontime','Attended in <?php echo $jp ?> on Time':'ab-ontime',
  'Attended in Khar Late':'ab-late','Attended in <?php echo $jp ?> Late':'ab-late',
  'Attended in Other Jamaat':'ab-other',
  'Not attended anywhere':'ab-absent',
  'Not in Town':'ab-town',
  'Married Outcaste':'ab-outcaste',
};
const STATUS_TEXT_CLS={
  'Attended with Maula':'sc-maula',
  'Attended in Khar on Time':'sc-ontime','Attended in <?php echo $jp ?> on Time':'sc-ontime',
  'Attended in Khar Late':'sc-late','Attended in <?php echo $jp ?> Late':'sc-late',
  'Attended in Other Jamaat':'sc-other',
  'Not attended anywhere':'sc-absent',
  'Not in Town':'sc-town',
  'Married Outcaste':'sc-out',
};
function abCls(s){return STATUS_CLS[s]||'ab-unmarked'}
function stCls(s){return STATUS_TEXT_CLS[s]||'sc-none'}
function esc(s){return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}

/* ── Open edit modal (from table button) ── */
document.addEventListener('click',e=>{
  const btn=e.target.closest('.ab');
  if(!btn)return;
  const {its,day,status,comment}=btn.dataset;
  document.getElementById('editIts').value=its;
  document.getElementById('editDay').value=day;
  document.getElementById('editStatus').value=status;
  document.getElementById('editComment').value=comment||'';
  document.getElementById('editMeta').innerHTML=`<strong>${esc(its)}</strong> &mdash; ${day==='Ashura'?'Ashura':'Day '+day}`;
  ashOM('modEdit');
});

/* ── Save attendance ── */
function ashSaveAttendance(){
  const btn=document.getElementById('editSaveBtn');
  const its=document.getElementById('editIts').value;
  const day=document.getElementById('editDay').value;
  const status=document.getElementById('editStatus').value;
  const comment=document.getElementById('editComment').value;
  const year=yearSel?yearSel.value:SELECTED_YEAR;
  btn.disabled=true;btn.textContent='Saving…';
  fetch(BASE_URL+'MasoolMusaid/update_attendance',{
    method:'POST',credentials:'same-origin',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:new URLSearchParams({its,day,status,comment,...(year?{year}:{})})
  }).then(r=>r.ok?r.text():Promise.reject(r))
  .then(()=>{
    /* Update button in main table if exists */
    const b=document.querySelector(`.ab[data-its="${CSS.escape(its)}"][data-day="${CSS.escape(day)}"]`);
    if(b){
      b.className='ab '+abCls(status);
      b.dataset.status=status;b.dataset.comment=comment;
      b.title=status+(comment?': '+comment:'');
    }
    ashCM('modEdit');
    ashToast('Attendance updated','ok');
  }).catch(()=>ashToast('Update failed — please try again','er'))
  .finally(()=>{btn.disabled=false;btn.textContent='Save Changes'});
}

/* ── Filter ── */
function ashFilter(){
  const q=(document.getElementById('ashSearch').value||'').toLowerCase().trim();
  const sec=(document.getElementById('ashSector').value||'').toLowerCase();
  const sub=(document.getElementById('ashSub').value||'').toLowerCase();
  const commonStatus = document.getElementById('ashCommonStatus').value;
  const memStatusSel = document.getElementById('ashMemberStatus');
  const memStatus = memStatusSel ? memStatusSel.value : 'Active';
  
  let vis=0;
  document.querySelectorAll('#ashTbody tr').forEach(tr=>{
    let show=true;
    if(q&&!( tr.dataset.name.includes(q)||tr.dataset.its.includes(q)||tr.dataset.hof.includes(q)||tr.dataset.sector.toLowerCase().includes(q)))show=false;
    if(sec&&tr.dataset.sector.toLowerCase()!==sec)show=false;
    if(sub&&tr.dataset.sub.toLowerCase()!==sub)show=false;
    
    if (memStatus !== 'All' && show) {
      const inact = (tr.dataset.inactive || '').trim();
      const act = (tr.dataset.activity || '').toLowerCase().trim();
      const isAct = !inact && (!act || act === 'active');
      if (memStatus === 'Active' && !isAct) show = false;
      if (memStatus === 'Inactive' && isAct) show = false;
    }
    
    if(commonStatus && show){
      const btns=tr.querySelectorAll('.ab');
      const targetStatus = commonStatus.toLowerCase();
      for(const b of btns){
        const bStatus = (b.dataset.status||'').toLowerCase().trim();
        if(targetStatus === 'not marked'){
          if(bStatus !== 'not marked' && bStatus !== ''){
            show=false;
            break;
          }
        } else {
          if(bStatus !== targetStatus){
            show=false;
            break;
          }
        }
      }
    }
    
    tr.style.display=show?'':'none';
    if(show)vis++;
  });
  const rc=document.getElementById('ashResultCount');
  if(rc)rc.innerHTML=`<span>${vis}</span> members shown`;
  const cb=document.getElementById('dashCountBadge');
  if(cb)cb.innerHTML=`<i class="fa-solid fa-users fa-xs"></i> ${vis} Mumineen`;
}

/* ── Bulk update ── */
function ashBulkUpdate(){
  const day=document.getElementById('bulkDay').value;
  const status=document.getElementById('bulkStatus').value;
  const raw=document.getElementById('bulkITS').value;
  const year=yearSel?yearSel.value:SELECTED_YEAR;
  const itsList=raw.split(/[\n,]+/).map(i=>i.trim()).filter(i=>i.length>0);
  if(!status){ashToast('Select a status','er');return}
  if(!itsList.length){ashToast('Enter at least one ITS number','er');return}
  if(!confirm(`Update ${itsList.length} records to "${status}" for ${day==='Ashura'?'Ashura':'Day '+day}?`))return;
  fetch(BASE_URL+'MasoolMusaid/bulk_update_attendance',{
    method:'POST',credentials:'same-origin',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({its_list:itsList,day,status,...(year?{year}:{})})
  }).then(r=>r.ok?r.json():Promise.reject(r))
  .then(()=>{ashCM('modBulk');ashToast('Bulk update successful','ok');location.reload()})
  .catch(err => {
    console.error(err);
    if (err instanceof Response) {
      err.text().then(t => {
        console.error("Response text:", t);
        ashToast('Bulk update failed: ' + t.slice(0, 50), 'er');
      });
    } else {
      ashToast('Bulk update failed: ' + err.message, 'er');
    }
  });
}

/* ── Export CSV ── */
function toggleAllDays(chk){document.querySelectorAll('.day-cb').forEach(c=>c.checked=chk.checked)}
document.querySelectorAll('.day-cb').forEach(c=>c.addEventListener('change',()=>{document.getElementById('selAllDays').checked=[...document.querySelectorAll('.day-cb')].every(c=>c.checked)}));
function ashExport(){
  const selected=[...document.querySelectorAll('.day-cb:checked')].map(c=>c.value);
  if(!selected.length){ashToast('Select at least one day','er');return}
  const hdrs=['ITS_ID','Full_Name','HOF_ID','Mobile','Sector','Sub_Sector',...selected];
  const rows=ASH_USERS.map(u=>[u.ITS_ID??'',u.Full_Name??'',u.HOF_ID??'',u.Mobile??'',u.Sector??'',u.Sub_Sector??'',...selected.map(d=>u[d]??'Not Marked')]);
  let csv=hdrs.join(',')+'\n'+rows.map(r=>r.map(v=>'"'+String(v).replace(/"/g,'""')+'"').join(',')).join('\n');
  const link=document.createElement('a');link.href=URL.createObjectURL(new Blob(['\uFEFF'+csv],{type:'text/csv;charset=utf-8;'}));
  link.download=`ashara_attendance_${new Date().toISOString().slice(0,10)}.csv`;
  document.body.appendChild(link);link.click();document.body.removeChild(link);
  ashCM('modExport');ashToast('Export started','ok');
}

/* ── Clickable Rows for Member Profile ── */
document.querySelector('#ashTable tbody')?.addEventListener('click', e => {
  const tr = e.target.closest('tr');
  if (!tr) return;
  if (e.target.closest('button, a, input, select, option, label') || e.target.classList.contains('ab')) {
    return;
  }
  const its = tr.dataset.its;
  if (its) {
    window.location.href = VIEW_MEMBER_BASE_URL + its;
  }
});

document.addEventListener('DOMContentLoaded', () => {
  ashFilter();
});
</script>
