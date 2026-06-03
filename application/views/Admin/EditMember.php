<?php /* Edit Member — Inline edit matching ViewMember layout */ ?>
<?php
if (!function_exists('norm_date_input')) {
  function norm_date_input($val) {
    if (empty($val)) return '';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) return $val;
    $t = strtotime($val);
    return $t ? date('Y-m-d', $t) : '';
  }
}
$redirect = $this->input->get('redirect');
$role = $_SESSION['user']['role'] ?? null;
$default_redirect = ($role == 3) ? base_url('anjuman/mumineendirectory') : base_url('admin/managemembers');
if (empty($redirect)) {
  $redirect = $default_redirect;
} else {
  if (strpos($redirect,'http://')===0||strpos($redirect,'https://')===0) {
    if (strpos($redirect,base_url())!==0) $redirect=$default_redirect;
  } elseif (strpos($redirect,'//')===0) {
    $redirect=$default_redirect;
  } else {
    if (strpos($redirect,'/')===0) {
      $bp=rtrim(parse_url(base_url(),PHP_URL_PATH),'/');
      if (!empty($bp)&&strpos($redirect,$bp)!==0) $redirect=$default_redirect;
    } else {
      $redirect=base_url($redirect);
    }
  }
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
#emApp,#emApp *,#emApp *::before,#emApp *::after{box-sizing:border-box;}
#emApp{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-1);background:var(--bg);min-height:100vh;padding-top:57px;}

/* ── Page header ── */
#emApp .em-header{
  background:linear-gradient(135deg,#78520a 0%,#b8860b 50%,#c9a227 100%);
  border-radius:22px;padding:20px 26px;margin-bottom:22px;
  position:relative;overflow:hidden;
  display:flex;align-items:center;justify-content:space-between;gap:14px;
}
#emApp .em-header::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;pointer-events:none;}
#emApp .em-header::after{content:'';position:absolute;right:-40px;top:-40px;width:200px;height:200px;background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);pointer-events:none;}
#emApp .em-eyebrow{font-size:.67rem;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.6);margin-bottom:3px;position:relative;z-index:1;}
#emApp .em-title{font-family:'Literata',Georgia,serif;font-size:1.4rem;font-weight:600;color:#fff;line-height:1.2;margin:0;position:relative;z-index:1;}
#emApp .em-title small{font-size:.85rem;font-weight:400;color:rgba(255,255,255,.7);display:block;margin-top:2px;}
#emApp .em-header-actions{display:flex;align-items:center;gap:10px;position:relative;z-index:1;flex-shrink:0;flex-wrap:wrap;}
#emApp .hdr-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:.8rem;font-weight:700;text-decoration:none;transition:background .15s;white-space:nowrap;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.15);color:#fff;cursor:pointer;}
#emApp .hdr-btn:hover{background:rgba(255,255,255,.28);color:#fff;text-decoration:none;}

/* ── Member hero ── */
#emApp .member-hero{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);padding:20px 22px;margin-bottom:18px;display:flex;align-items:center;gap:18px;box-shadow:var(--shadow-sm);position:relative;overflow:hidden;}
#emApp .member-hero::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,var(--gold),var(--gold-light));}
#emApp .hero-avatar{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#c9a227);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.3rem;flex-shrink:0;box-shadow:0 3px 12px rgba(184,134,11,.3);}
#emApp .hero-avatar.female{background:linear-gradient(135deg,#b45309,#f59e0b);}
#emApp .hero-name{font-family:'Literata',Georgia,serif;font-size:1.2rem;font-weight:600;color:var(--text-1);margin:0 0 4px;}
#emApp .hero-meta{display:flex;flex-wrap:wrap;gap:10px;font-size:.76rem;color:var(--text-3);}
#emApp .hero-meta span{display:inline-flex;align-items:center;gap:4px;}
#emApp .hero-meta i{color:var(--gold);}

/* ── Editing indicator banner ── */
#emApp .edit-banner{background:var(--gold-muted);border:1.5px solid var(--gold-border);border-radius:12px;padding:10px 16px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:.82rem;font-weight:600;color:var(--gold);}
#emApp .edit-banner i{font-size:1rem;}

/* ── Status pills ── */
#emApp .status-strip{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;}
#emApp .spill{display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:40px;font-size:.76rem;font-weight:700;border:1.5px solid transparent;}
#emApp .spill.success{background:var(--green-bg);color:var(--green);border-color:#86efac;}
#emApp .spill.danger{background:var(--red-bg);color:var(--red);border-color:#fca5a5;}
#emApp .spill.warning{background:#fffbeb;color:#92400e;border-color:#fcd34d;}
#emApp .spill.info{background:var(--blue-bg);color:var(--blue);border-color:#93c5fd;}
#emApp .spill.secondary{background:var(--surface-2);color:var(--text-2);border-color:var(--border);}

/* ── Masonry grid (identical to ViewMember) ── */
#emApp .masonry-grid{column-count:2;column-gap:16px;}
@media(max-width:768px){#emApp .masonry-grid{column-count:1;}}

/* ── Panel cards (identical to ViewMember) ── */
#emApp .panel{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);margin-bottom:16px;box-shadow:var(--shadow-sm);overflow:hidden;break-inside:avoid;display:inline-block;width:100%;transition:box-shadow .2s;}
#emApp .panel:hover{box-shadow:var(--shadow);}
#emApp .panel-hd{display:flex;align-items:center;justify-content:space-between;padding:13px 18px;background:var(--surface-2);border-bottom:1.5px solid var(--border-light);cursor:pointer;user-select:none;transition:background .15s;}
#emApp .panel-hd:hover{background:var(--gold-muted);}
#emApp .panel-hd.open{background:var(--gold-muted);border-bottom-color:var(--gold-border);}
#emApp .ph-left{display:flex;align-items:center;gap:9px;}
#emApp .ph-icon{width:28px;height:28px;border-radius:7px;background:var(--gold-muted);color:var(--gold);display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0;}
#emApp .panel-hd.open .ph-icon{background:rgba(184,134,11,.18);}
#emApp .ph-title{font-size:.82rem;font-weight:800;color:var(--text-2);text-transform:uppercase;letter-spacing:.5px;}
#emApp .ph-badge{font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:20px;background:var(--border-light);color:var(--text-3);}
#emApp .ph-chevron{width:24px;height:24px;border-radius:6px;background:var(--surface);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:.68rem;color:var(--text-3);transition:transform .2s;}
#emApp .panel-hd.open .ph-chevron{transform:rotate(180deg);}

/* ── Edit rows — same structure as ViewMember detail-row but with inputs ── */
#emApp .edit-list{list-style:none;margin:0;padding:0;}
#emApp .edit-row{display:flex;align-items:stretch;border-bottom:1px solid var(--border-light);min-height:44px;transition:background .12s;}
#emApp .edit-row:last-child{border-bottom:none;}
#emApp .edit-row:focus-within{background:rgba(184,134,11,.03);}

/* Key column — identical to ViewMember .dr-key */
#emApp .er-key{
  flex:0 0 42%;max-width:42%;
  padding:10px 14px;
  font-size:.72rem;font-weight:700;color:var(--text-3);
  text-transform:uppercase;letter-spacing:.3px;
  background:var(--surface-2);
  border-right:1px solid var(--border-light);
  display:flex;align-items:center;gap:5px;
  word-break:break-word;line-height:1.3;
}
#emApp .er-key .req{color:var(--red);font-size:.9rem;line-height:1;}
#emApp .er-key .auto-lbl{font-size:.58rem;font-weight:700;padding:1px 5px;border-radius:8px;background:var(--gold-muted);color:var(--gold);white-space:nowrap;flex-shrink:0;}

/* Value column — same position as ViewMember .dr-val but contains an input */
#emApp .er-val{flex:1;display:flex;align-items:center;padding:5px 10px;}

/* Inputs/selects styled to look like plain text until focused */
#emApp .er-val input,
#emApp .er-val select {
  width:100%;
  border:1.5px solid transparent;
  border-radius:7px;
  padding:6px 10px;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-size:.84rem;
  color:var(--text-1);
  background:transparent;
  outline:none;
  transition:border-color .15s,background .15s,box-shadow .15s;
  height:34px;
}
/* On hover show subtle border */
#emApp .edit-row:hover .er-val input,
#emApp .edit-row:hover .er-val select{
  border-color:var(--border);
  background:var(--surface);
}
/* On focus — gold highlight, feels like "now editing" */
#emApp .er-val input:focus,
#emApp .er-val select:focus{
  border-color:var(--gold);
  background:var(--surface);
  box-shadow:0 0 0 3px rgba(184,134,11,.12);
}
/* Readonly/auto fields — look like plain text, not editable */
#emApp .er-val input[readonly]{
  color:var(--text-3);cursor:default;
  background:transparent;border-color:transparent !important;
  box-shadow:none !important;
}
#emApp .er-val select{cursor:pointer;}

/* Status grid inside Member Status panel */
#emApp .status-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:14px;padding:16px 18px;border-bottom:1px solid var(--border-light);}
#emApp .sg-item{}
#emApp .sg-label{font-size:.7rem;color:var(--text-3);font-weight:700;text-transform:uppercase;letter-spacing:.3px;margin-bottom:5px;display:flex;align-items:center;gap:5px;}
#emApp .sg-badge{display:inline-block;font-size:.6rem;font-weight:700;padding:1px 7px;border-radius:12px;}
#emApp .sg-badge.auto{background:var(--gold-muted);color:var(--gold);}
#emApp .sg-badge.manual{background:var(--orange-bg);color:var(--orange);}
#emApp .sg-val{font-size:.9rem;font-weight:700;color:var(--text-1);}
#emApp .sg-val.green{color:var(--green);}
#emApp .sg-val.red{color:var(--red);}

/* Section note */
#emApp .section-note{display:flex;align-items:flex-start;gap:8px;padding:10px 16px;font-size:.78rem;color:var(--text-2);border-bottom:1px solid var(--border-light);}
#emApp .section-note.gold{background:var(--gold-muted);}
#emApp .section-note i{margin-top:1px;flex-shrink:0;}

/* HOF autocomplete */
#emApp .autocomplete-wrap{position:relative;width:100%;}
#emApp #hof_autocomplete_list{position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--surface);border:1.5px solid var(--border);border-radius:12px;box-shadow:var(--shadow-lg);z-index:1050;display:none;max-height:240px;overflow-y:auto;}
#emApp #hof_autocomplete_list.open{display:block;}
#emApp .hof-item{display:flex;align-items:center;gap:10px;padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border-light);transition:background .12s;}
#emApp .hof-item:last-child{border-bottom:none;}
#emApp .hof-item:hover{background:var(--gold-muted);}
#emApp .hof-av{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#c9a227);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;}
#emApp .hof-name{font-weight:700;font-size:.84rem;color:var(--text-1);}
#emApp .hof-its{font-size:.7rem;color:var(--text-3);}
#emApp .hof-empty{padding:14px;text-align:center;color:var(--text-3);font-size:.84rem;}

/* Responsive */
@media(max-width:480px){
  #emApp .er-key{flex:0 0 38%;max-width:38%;font-size:.67rem;padding:8px 9px;}
  #emApp .er-val{padding:4px 6px;}
  #emApp .er-val input,#emApp .er-val select{font-size:.8rem;padding:5px 8px;height:32px;}
}

/* ── Sticky save bar ── */
#emApp .sticky-save{position:fixed;left:0;right:0;bottom:0;z-index:1060;padding:10px 16px;background:rgba(255,255,255,.96);backdrop-filter:blur(8px);border-top:1.5px solid var(--border);box-shadow:0 -4px 20px rgba(0,0,0,.06);}
#emApp .sticky-save .inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:flex-end;gap:10px;flex-wrap:wrap;}
#emApp .btn-save{display:inline-flex;align-items:center;gap:7px;padding:9px 22px;border-radius:10px;background:linear-gradient(135deg,#b8860b,#c9a227);border:none;color:#fff;font-size:.86rem;font-weight:800;cursor:pointer;transition:opacity .15s,transform .1s;box-shadow:0 3px 12px rgba(184,134,11,.35);}
#emApp .btn-save:hover{opacity:.9;transform:translateY(-1px);}
#emApp .btn-cancel{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;border:1.5px solid var(--border);background:var(--surface);color:var(--text-2);font-size:.84rem;font-weight:700;text-decoration:none;transition:border-color .15s,background .15s;}
#emApp .btn-cancel:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);text-decoration:none;}
#emApp .btn-danger-outline{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;border:1.5px solid #fca5a5;background:var(--red-bg);color:var(--red);font-size:.84rem;font-weight:700;cursor:pointer;transition:background .15s;}
#emApp .btn-danger-outline:hover{background:#fee2e2;}
#emApp .save-status{font-size:.8rem;font-weight:700;padding:6px 12px;border-radius:8px;display:none;}
#emApp .save-status.success{display:inline-block;background:var(--green-bg);color:var(--green);}
#emApp .save-status.error{display:inline-block;background:var(--red-bg);color:var(--red);}
</style>

<?php
  $its_match   = $member['its_sabeel_match'] ?? '';
  $actStatus   = $member['activity_status']  ?? 'active';
  $matchLabels = ['its_sabeel_both_khar'=>['ITS & Sabeel both in Khar','success'],'its_khar_sabeel_out'=>['ITS in Khar, Sabeel outside','warning'],'sabeel_khar_its_out'=>['Sabeel in Khar, ITS outside','info'],'both_not_khar'=>['Both not in Khar','secondary']];
  $matchLabel  = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][0] : 'Not calculated';
  $matchClass  = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][1] : 'secondary';
  $actClass    = ['active'=>'success','inactive'=>'danger','temporary'=>'warning'][$actStatus] ?? 'secondary';
  $isF         = strtolower($member['Gender'] ?? '') === 'female';
  $memberName  = trim($member['Full_Name'] ?? '');
  $getInitials = function($n){ if(!$n)return'?'; $p=preg_split('/\s+/',trim($n)); return count($p)===1?strtoupper(substr($p[0],0,1)):strtoupper(substr($p[0],0,1).substr($p[count($p)-1],0,1)); };
  $initials    = $getInitials($memberName);
  $maritalVal  = $member['Marital_Status'] ?? '';  $maritalOpts = ["Single","Married","Engaged","Separated","Divorced","Widowed"];
  $bloodVal    = $member['Blood_Group'] ?? '';      $bloodOpts   = ["A+","A-","B+","B-","AB+","AB-","O+","O-","Unknown"];
  $inactiveVal = $member['Inactive_Status'] ?? '';  $inactiveOpts= ["Deceased","Shifted Jamaat","Travel / Outstation","Duplicate Record","Blocked / Suspended","Other"];
  $dvs=$member['Data_Verifcation_Status']??''; $pvs=$member['Photo_Verifcation_Status']??'';
  $currentSector=$member['Sector']??'';
?>

<div id="emApp">
<div class="container pt-4 pb-5" style="max-width:1300px;">

  <!-- ── Header ── -->
  <div class="em-header">
    <div>
      <p class="em-eyebrow">Member Management</p>
      <h1 class="em-title">Edit Member<small><?php echo htmlspecialchars($memberName); ?> &mdash; ITS <?php echo htmlspecialchars($member['ITS_ID']??''); ?></small></h1>
    </div>
    <div class="em-header-actions">
      <a href="<?php echo htmlspecialchars($redirect); ?>" class="hdr-btn"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
  </div>

  <?php if(!empty($member)): ?>

  <!-- ── Hero ── -->
  <div class="member-hero">
    <div class="hero-avatar <?php echo $isF?'female':''; ?>"><?php echo htmlspecialchars($initials); ?></div>
    <div style="flex:1;min-width:0;">
      <h2 class="hero-name"><?php echo htmlspecialchars($memberName?:'—'); ?></h2>
      <div class="hero-meta">
        <?php if(!empty($member['ITS_ID'])): ?><span><i class="fa fa-id-badge"></i> ITS: <?php echo htmlspecialchars($member['ITS_ID']); ?></span><?php endif; ?>
        <?php if(!empty($member['Gender'])): ?><span><i class="fa fa-<?php echo $isF?'female':'male'; ?>"></i> <?php echo htmlspecialchars($member['Gender']); ?></span><?php endif; ?>
        <?php if(!empty($member['Age'])): ?><span><i class="fa fa-birthday-cake"></i> Age <?php echo htmlspecialchars($member['Age']); ?></span><?php endif; ?>
        <?php if(!empty($member['Sector'])): ?><span><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($member['Sector']); ?><?php echo !empty($member['Sub_Sector'])?' — '.htmlspecialchars($member['Sub_Sector']):''; ?></span><?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ── Editing notice ── -->
  <div class="edit-banner">
    <i class="fa fa-pencil-square-o"></i>
    <span>You are editing this member's details. Click any field to make changes, then save using the button below.</span>
  </div>

  <!-- ── Status pills ── -->
  <div class="status-strip">
    <span class="spill <?php echo $matchClass; ?>"><i class="fa fa-link"></i> <?php echo htmlspecialchars($matchLabel); ?></span>
    <span class="spill <?php echo $actClass; ?>"><i class="fa fa-circle"></i> <?php echo ucfirst($actStatus); ?></span>
    <?php if(!empty($member['HOF_FM_TYPE'])): ?><span class="spill info"><i class="fa fa-home"></i> <?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?></span><?php endif; ?>
  </div>

  <form id="editMemberForm" method="post" action="<?php echo base_url('admin/updatemember'); ?>">

  <!-- ══ MEMBER STATUS (full width, above masonry) ══ -->
  <div class="panel" style="margin-bottom:16px;">
    <div class="panel-hd open" data-pt="grp-mstatus" style="background:var(--blue-bg);border-color:#93c5fd55;">
      <div class="ph-left">
        <span class="ph-icon" style="background:var(--blue-bg);color:var(--blue);"><i class="fa fa-toggle-on"></i></span>
        <span class="ph-title" style="color:var(--blue);">Member Status</span>
      </div>
      <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
    </div>
    <div id="grp-mstatus">
      <div class="section-note gold"><i class="fa fa-info-circle"></i><span>ITS–Sabeel match &amp; Member Status are auto-calculated. Living statuses below drive Member Status automatically.</span></div>
      <div class="status-grid">
        <div class="sg-item">
          <div class="sg-label">ITS–Sabeel Match <span class="sg-badge auto">Auto</span></div>
          <div class="sg-val"><?php echo htmlspecialchars($matchLabel); ?></div>
        </div>
        <div class="sg-item">
          <div class="sg-label">Member Status <span class="sg-badge auto">Auto</span></div>
          <input type="hidden" name="activity_status" id="activityStatusSel" value="<?php echo htmlspecialchars($actStatus); ?>">
          <div class="sg-val <?php echo $actClass==='success'?'green':($actClass==='danger'?'red':''); ?>" id="activityStatusDisplay">
            <?php echo htmlspecialchars($activity_status_options[$actStatus] ?? ucfirst($actStatus)); ?>
          </div>
        </div>
      </div>
      <ul class="edit-list">
        <li class="edit-row">
          <div class="er-key">Deeni Status <span class="auto-lbl">Living</span></div>
          <div class="er-val">
            <select name="deeni_status" id="deeniStatusSel">
              <?php foreach($deeni_status_options as $v=>$l): ?><option value="<?php echo htmlspecialchars($v); ?>" <?php echo (($member['deeni_status']??'')===$v)?'selected':''; ?>><?php echo htmlspecialchars($l); ?></option><?php endforeach; ?>
              <?php if(!empty($member['deeni_status'])&&!array_key_exists($member['deeni_status'],$deeni_status_options)): ?><option value="<?php echo htmlspecialchars($member['deeni_status']); ?>" selected><?php echo htmlspecialchars($member['deeni_status']); ?> (Legacy)</option><?php endif; ?>
            </select>
          </div>
        </li>
        <li class="edit-row">
          <div class="er-key">Health Status <span class="auto-lbl">Living</span></div>
          <div class="er-val">
            <select name="health_status" id="healthStatusSel">
              <?php foreach($health_status_options as $v=>$l): ?><option value="<?php echo htmlspecialchars($v); ?>" <?php echo (($member['health_status']??'')===$v)?'selected':''; ?>><?php echo htmlspecialchars($l); ?></option><?php endforeach; ?>
              <?php if(!empty($member['health_status'])&&!array_key_exists($member['health_status'],$health_status_options)): ?><option value="<?php echo htmlspecialchars($member['health_status']); ?>" selected><?php echo htmlspecialchars($member['health_status']); ?> (Legacy)</option><?php endif; ?>
            </select>
          </div>
        </li>
        <li class="edit-row">
          <div class="er-key">Residential Status <span class="auto-lbl">Living</span></div>
          <div class="er-val">
            <select name="residential_status" id="residentialStatusSel">
              <?php foreach($residential_status_options as $v=>$l): ?><option value="<?php echo htmlspecialchars($v); ?>" <?php echo (($member['residential_status']??'')===$v)?'selected':''; ?>><?php echo htmlspecialchars($l); ?></option><?php endforeach; ?>
              <?php if(!empty($member['residential_status'])&&!array_key_exists($member['residential_status'],$residential_status_options)): ?><option value="<?php echo htmlspecialchars($member['residential_status']); ?>" selected><?php echo htmlspecialchars($member['residential_status']); ?> (Legacy)</option><?php endif; ?>
            </select>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- ══ MASONRY GRID (2 columns, same as ViewMember) ══ -->
  <div class="masonry-grid">

    <!-- ─ Identity & Contact ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-identity">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-id-card"></i></span><span class="ph-title">Identity &amp; Contact</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-identity">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">ITS ID</div><div class="er-val"><input type="text" name="its_id" value="<?php echo htmlspecialchars($member['ITS_ID']); ?>" readonly></div></li>
          <li class="edit-row"><div class="er-key">Full Name <span class="req">*</span></div><div class="er-val"><input type="text" name="Full_Name" value="<?php echo htmlspecialchars($member['Full_Name']); ?>" required></div></li>
          <li class="edit-row"><div class="er-key">Full Name Arabic</div><div class="er-val"><input type="text" name="Full_Name_Arabic" value="<?php echo htmlspecialchars($member['Full_Name_Arabic']??''); ?>" placeholder="Arabic script"></div></li>
          <li class="edit-row"><div class="er-key">First Name</div><div class="er-val"><input type="text" name="First_Name" value="<?php echo htmlspecialchars($member['First_Name']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Surname</div><div class="er-val"><input type="text" name="Surname" value="<?php echo htmlspecialchars($member['Surname']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Gender</div><div class="er-val"><select name="Gender"><option value="">--</option><option value="Male" <?php echo ($member['Gender']??'')==='Male'?'selected':''; ?>>Male</option><option value="Female" <?php echo ($member['Gender']??'')==='Female'?'selected':''; ?>>Female</option></select></div></li>
          <li class="edit-row"><div class="er-key">Age</div><div class="er-val"><input type="number" name="Age" value="<?php echo htmlspecialchars($member['Age']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Mobile</div><div class="er-val"><input type="text" name="Mobile" value="<?php echo htmlspecialchars($member['Mobile']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">WhatsApp No</div><div class="er-val"><input type="text" name="WhatsApp_No" value="<?php echo htmlspecialchars($member['WhatsApp_No']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Email</div><div class="er-val"><input type="email" name="Email" value="<?php echo htmlspecialchars($member['Email']??''); ?>" placeholder="name@example.com"></div></li>
          <li class="edit-row"><div class="er-key">Registered Family Mobile</div><div class="er-val"><input type="text" name="Registered_Family_Mobile" value="<?php echo htmlspecialchars($member['Registered_Family_Mobile']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">First Prefix</div><div class="er-val"><input type="text" name="First_Prefix" value="<?php echo htmlspecialchars($member['First_Prefix']??''); ?>" placeholder="e.g. Shk / Shz"></div></li>
          <li class="edit-row"><div class="er-key">Prefix Year</div><div class="er-val"><input type="text" name="Prefix_Year" value="<?php echo htmlspecialchars($member['Prefix_Year']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Father Prefix</div><div class="er-val"><input type="text" name="Father_Prefix" value="<?php echo htmlspecialchars($member['Father_Prefix']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Father Name</div><div class="er-val"><input type="text" name="Father_Name" value="<?php echo htmlspecialchars($member['Father_Name']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Father Surname</div><div class="er-val"><input type="text" name="Father_Surname" value="<?php echo htmlspecialchars($member['Father_Surname']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Husband Prefix</div><div class="er-val"><input type="text" name="Husband_Prefix" value="<?php echo htmlspecialchars($member['Husband_Prefix']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Husband Name</div><div class="er-val"><input type="text" name="Husband_Name" value="<?php echo htmlspecialchars($member['Husband_Name']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Family & Relationships ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-family">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-home"></i></span><span class="ph-title">Family &amp; Relationships</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-family">
        <ul class="edit-list">
          <li class="edit-row">
            <div class="er-key">Type</div>
            <div class="er-val"><select name="hof_type" id="hofTypeSelect"><option value="HOF" <?php echo ($member['HOF_FM_TYPE']==='HOF')?'selected':''; ?>>Head of Family (HOF)</option><option value="FM" <?php echo ($member['HOF_FM_TYPE']!=='HOF')?'selected':''; ?>>Family Member (FM)</option></select></div>
          </li>
          <li class="edit-row" id="hofSelectWrapper" style="<?php echo ($member['HOF_FM_TYPE']==='HOF')?'display:none;':''; ?>">
            <div class="er-key">Select HOF</div>
            <div class="er-val">
              <div class="autocomplete-wrap">
                <input type="text" id="hof_autocomplete" placeholder="Search by ITS or name…" value="<?php echo !empty($member['HOF_ID'])?htmlspecialchars(!empty($hof_name)?$hof_name.' ('.$member['HOF_ID'].')':$member['HOF_ID']):''; ?>" autocomplete="off">
                <input type="hidden" name="HOF_ID" id="hof_id" value="<?php echo htmlspecialchars($member['HOF_ID']??''); ?>">
                <div id="hof_autocomplete_list"></div>
              </div>
            </div>
          </li>
          <li class="edit-row"><div class="er-key">HOF FM Type</div><div class="er-val"><input type="text" name="HOF_FM_TYPE" value="<?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?>" readonly></div></li>
          <li class="edit-row"><div class="er-key">Father ITS ID</div><div class="er-val"><input type="text" name="Father_ITS_ID" value="<?php echo htmlspecialchars($member['Father_ITS_ID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Mother ITS ID</div><div class="er-val"><input type="text" name="Mother_ITS_ID" value="<?php echo htmlspecialchars($member['Mother_ITS_ID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Spouse ITS ID</div><div class="er-val"><input type="text" name="Spouse_ITS_ID" value="<?php echo htmlspecialchars($member['Spouse_ITS_ID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Family ID</div><div class="er-val"><input type="text" name="Family_ID" value="<?php echo htmlspecialchars($member['Family_ID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Tanzeem File No</div><div class="er-val"><input type="text" name="TanzeemFile_No" value="<?php echo htmlspecialchars($member['TanzeemFile_No']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Sector Hierarchy ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-sector">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-map-marker"></i></span><span class="ph-title">Sector Hierarchy</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-sector">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Sector</div><div class="er-val"><select name="Sector" id="sectorSelectEdit"><option value="">-- Select --</option><?php if(!empty($sector_list)) foreach($sector_list as $sec): ?><option value="<?php echo htmlspecialchars($sec); ?>" <?php echo ($sec===$currentSector)?'selected':''; ?>><?php echo htmlspecialchars($sec); ?></option><?php endforeach; ?></select></div></li>
          <li class="edit-row"><div class="er-key">Sub Sector</div><div class="er-val"><select name="Sub_Sector" id="subSectorSelectEdit" disabled><option value="">-- Select --</option></select></div></li>
          <li class="edit-row"><div class="er-key">Sector Incharge ITS</div><div class="er-val"><input type="text" name="Sector_Incharge_ITSID" value="<?php echo htmlspecialchars($member['Sector_Incharge_ITSID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sector Incharge Name</div><div class="er-val"><input type="text" name="Sector_Incharge_Name" value="<?php echo htmlspecialchars($member['Sector_Incharge_Name']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sector Incharge Female ITS</div><div class="er-val"><input type="text" name="Sector_Incharge_Female_ITSID" value="<?php echo htmlspecialchars($member['Sector_Incharge_Female_ITSID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sector Incharge Female Name</div><div class="er-val"><input type="text" name="Sector_Incharge_Female_Name" value="<?php echo htmlspecialchars($member['Sector_Incharge_Female_Name']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Sector Incharge ITS</div><div class="er-val"><input type="text" name="Sub_Sector_Incharge_ITSID" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_ITSID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Sector Incharge Name</div><div class="er-val"><input type="text" name="Sub_Sector_Incharge_Name" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Name']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Sector Incharge Female ITS</div><div class="er-val"><input type="text" name="Sub_Sector_Incharge_Female_ITSID" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Female_ITSID']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Sector Incharge Female Name</div><div class="er-val"><input type="text" name="Sub_Sector_Incharge_Female_Name" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Female_Name']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Marital & Personal ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-marital">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-heart"></i></span><span class="ph-title">Marital &amp; Personal</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-marital">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Misaq</div><div class="er-val"><input type="text" name="Misaq" value="<?php echo htmlspecialchars($member['Misaq']??''); ?>" placeholder="Yes / Year"></div></li>
          <li class="edit-row"><div class="er-key">Marital Status</div><div class="er-val"><select name="Marital_Status"><option value="">-- Select --</option><?php foreach($maritalOpts as $o): ?><option value="<?php echo $o; ?>" <?php echo ($o===$maritalVal)?'selected':''; ?>><?php echo $o; ?></option><?php endforeach; ?><?php if($maritalVal&&!in_array($maritalVal,$maritalOpts)): ?><option value="<?php echo htmlspecialchars($maritalVal); ?>" selected><?php echo htmlspecialchars($maritalVal); ?> (Legacy)</option><?php endif; ?></select></div></li>
          <li class="edit-row"><div class="er-key">Blood Group</div><div class="er-val"><select name="Blood_Group"><option value="">-- Select --</option><?php foreach($bloodOpts as $o): ?><option value="<?php echo $o; ?>" <?php echo ($o===$bloodVal)?'selected':''; ?>><?php echo $o; ?></option><?php endforeach; ?><?php if($bloodVal&&!in_array($bloodVal,$bloodOpts)): ?><option value="<?php echo htmlspecialchars($bloodVal); ?>" selected><?php echo htmlspecialchars($bloodVal); ?> (Legacy)</option><?php endif; ?></select></div></li>
          <li class="edit-row"><div class="er-key">Warakatul Tarkhis</div><div class="er-val"><input type="text" name="Warakatul_Tarkhis" value="<?php echo htmlspecialchars($member['Warakatul_Tarkhis']??''); ?>" placeholder="Number / Year"></div></li>
          <li class="edit-row"><div class="er-key">Date of Nikah</div><div class="er-val"><input type="date" name="Date_Of_Nikah" value="<?php echo norm_date_input($member['Date_Of_Nikah']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Date of Nikah Hijri</div><div class="er-val"><input type="text" name="Date_Of_Nikah_Hijri" value="<?php echo htmlspecialchars($member['Date_Of_Nikah_Hijri']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Origin & Community ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-origin">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-globe"></i></span><span class="ph-title">Origin &amp; Community</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-origin">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Organisation</div><div class="er-val"><input type="text" name="Organisation" value="<?php echo htmlspecialchars($member['Organisation']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Organisation CSV</div><div class="er-val"><input type="text" name="Organisation_CSV" value="<?php echo htmlspecialchars($member['Organisation_CSV']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Vatan</div><div class="er-val"><input type="text" name="Vatan" value="<?php echo htmlspecialchars($member['Vatan']??''); ?>" placeholder="Native place"></div></li>
          <li class="edit-row"><div class="er-key">Nationality</div><div class="er-val"><input type="text" name="Nationality" value="<?php echo htmlspecialchars($member['Nationality']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Jamaat</div><div class="er-val"><input type="text" name="Jamaat" value="<?php echo htmlspecialchars($member['Jamaat']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Jamiaat</div><div class="er-val"><input type="text" name="Jamiaat" value="<?php echo htmlspecialchars($member['Jamiaat']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Education & Skills ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-education">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-graduation-cap"></i></span><span class="ph-title">Education &amp; Skills</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-education">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Qualification</div><div class="er-val"><input type="text" name="Qualification" value="<?php echo htmlspecialchars($member['Qualification']??''); ?>" placeholder="Highest degree"></div></li>
          <li class="edit-row"><div class="er-key">Languages</div><div class="er-val"><input type="text" name="Languages" value="<?php echo htmlspecialchars($member['Languages']??''); ?>" placeholder="Comma separated"></div></li>
          <li class="edit-row"><div class="er-key">Hunars</div><div class="er-val"><input type="text" name="Hunars" value="<?php echo htmlspecialchars($member['Hunars']??''); ?>" placeholder="Skills / Talents"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Occupation ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-occupation">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-briefcase"></i></span><span class="ph-title">Occupation</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-occupation">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Occupation</div><div class="er-val"><input type="text" name="Occupation" value="<?php echo htmlspecialchars($member['Occupation']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Occupation</div><div class="er-val"><input type="text" name="Sub_Occupation" value="<?php echo htmlspecialchars($member['Sub_Occupation']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Sub Occupation 2</div><div class="er-val"><input type="text" name="Sub_Occupation2" value="<?php echo htmlspecialchars($member['Sub_Occupation2']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Religious Milestones ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-religious">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-star"></i></span><span class="ph-title">Religious Milestones &amp; Ziyarat</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-religious">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Quran Sanad</div><div class="er-val"><input type="text" name="Quran_Sanad" value="<?php echo htmlspecialchars($member['Quran_Sanad']??''); ?>" placeholder="Yes / Year"></div></li>
          <li class="edit-row"><div class="er-key">Qadambosi Sharaf</div><div class="er-val"><input type="text" name="Qadambosi_Sharaf" value="<?php echo htmlspecialchars($member['Qadambosi_Sharaf']??''); ?>" placeholder="Yes / Year"></div></li>
          <li class="edit-row"><div class="er-key">Raudat Tahera Ziyarat</div><div class="er-val"><input type="text" name="Raudat_Tahera_Ziyarat" value="<?php echo htmlspecialchars($member['Raudat_Tahera_Ziyarat']??''); ?>" placeholder="Yes / Year"></div></li>
          <li class="edit-row"><div class="er-key">Karbala Ziyarat</div><div class="er-val"><input type="text" name="Karbala_Ziyarat" value="<?php echo htmlspecialchars($member['Karbala_Ziyarat']??''); ?>" placeholder="Yes / Year"></div></li>
          <li class="edit-row"><div class="er-key">Ashara Mubaraka</div><div class="er-val"><input type="text" name="Ashara_Mubaraka" value="<?php echo htmlspecialchars($member['Ashara_Mubaraka']??''); ?>" placeholder="Yes / City / Year"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Housing & Address ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-housing">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-building"></i></span><span class="ph-title">Housing &amp; Address</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-housing">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Housing</div><div class="er-val"><input type="text" name="Housing" value="<?php echo htmlspecialchars($member['Housing']??''); ?>" placeholder="Building / Society"></div></li>
          <li class="edit-row"><div class="er-key">Type of House</div><div class="er-val"><input type="text" name="Type_of_House" value="<?php echo htmlspecialchars($member['Type_of_House']??''); ?>" placeholder="Owned / Rented"></div></li>
          <li class="edit-row"><div class="er-key">Address</div><div class="er-val"><input type="text" name="Address" value="<?php echo htmlspecialchars($member['Address']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Building</div><div class="er-val"><input type="text" name="Building" value="<?php echo htmlspecialchars($member['Building']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Street</div><div class="er-val"><input type="text" name="Street" value="<?php echo htmlspecialchars($member['Street']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Area</div><div class="er-val"><input type="text" name="Area" value="<?php echo htmlspecialchars($member['Area']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">City</div><div class="er-val"><input type="text" name="City" value="<?php echo htmlspecialchars($member['City']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">State</div><div class="er-val"><input type="text" name="State" value="<?php echo htmlspecialchars($member['State']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Pincode</div><div class="er-val"><input type="text" name="Pincode" value="<?php echo htmlspecialchars($member['Pincode']??''); ?>"></div></li>
        </ul>
      </div>
    </div>

    <!-- ─ Verification & Scan ─ -->
    <div class="panel">
      <div class="panel-hd open" data-pt="grp-verification">
        <div class="ph-left"><span class="ph-icon"><i class="fa fa-shield"></i></span><span class="ph-title">Verification &amp; Scan</span></div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div id="grp-verification">
        <ul class="edit-list">
          <li class="edit-row"><div class="er-key">Data Verification Status</div><div class="er-val"><select class="ver-status" data-date-target="dataVerificationDate" name="Data_Verifcation_Status"><option value="">--</option><option value="Verified" <?php echo $dvs==='Verified'?'selected':''; ?>>Verified</option><option value="Pending" <?php echo $dvs==='Pending'?'selected':''; ?>>Pending</option><option value="Not Verified" <?php echo $dvs==='Not Verified'?'selected':''; ?>>Not Verified</option></select></div></li>
          <li class="edit-row"><div class="er-key">Data Verification Date</div><div class="er-val"><input id="dataVerificationDate" type="date" name="Data_Verification_Date" value="<?php echo norm_date_input($member['Data_Verification_Date']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Photo Verification Status</div><div class="er-val"><select class="ver-status" data-date-target="photoVerificationDate" name="Photo_Verifcation_Status"><option value="">--</option><option value="Verified" <?php echo $pvs==='Verified'?'selected':''; ?>>Verified</option><option value="Pending" <?php echo $pvs==='Pending'?'selected':''; ?>>Pending</option><option value="Not Verified" <?php echo $pvs==='Not Verified'?'selected':''; ?>>Not Verified</option></select></div></li>
          <li class="edit-row"><div class="er-key">Photo Verification Date</div><div class="er-val"><input id="photoVerificationDate" type="date" name="Photo_Verification_Date" value="<?php echo norm_date_input($member['Photo_Verification_Date']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Last Scanned Event</div><div class="er-val"><input type="text" name="Last_Scanned_Event" value="<?php echo htmlspecialchars($member['Last_Scanned_Event']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Last Scanned Place</div><div class="er-val"><input type="text" name="Last_Scanned_Place" value="<?php echo htmlspecialchars($member['Last_Scanned_Place']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Title</div><div class="er-val"><input type="text" name="Title" value="<?php echo htmlspecialchars($member['Title']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Category</div><div class="er-val"><input type="text" name="Category" value="<?php echo htmlspecialchars($member['Category']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Idara</div><div class="er-val"><input type="text" name="Idara" value="<?php echo htmlspecialchars($member['Idara']??''); ?>"></div></li>
          <li class="edit-row"><div class="er-key">Inactive Status</div><div class="er-val"><select name="Inactive_Status" id="inactiveStatusEdit"><option value="" <?php echo $inactiveVal===''?'selected':''; ?>>Active</option><?php foreach($inactiveOpts as $o): ?><option value="<?php echo $o; ?>" <?php echo ($o===$inactiveVal)?'selected':''; ?>><?php echo $o; ?></option><?php endforeach; ?><?php if($inactiveVal&&!in_array($inactiveVal,$inactiveOpts)): ?><option value="<?php echo htmlspecialchars($inactiveVal); ?>" selected><?php echo htmlspecialchars($inactiveVal); ?> (Legacy)</option><?php endif; ?></select></div></li>
        </ul>
      </div>
    </div>

  </div><!-- /masonry-grid -->

  <div style="height:80px;"></div>

  <!-- ── Sticky Save Bar ── -->
  <div class="sticky-save">
    <div class="inner">
      <span class="save-status" id="editMemberStatus"></span>
      <button type="button" class="btn-danger-outline" onclick="if(confirm('Reset this member\'s password to their ITS ID?')){var f=document.createElement('form');f.method='post';f.action='<?php echo base_url('admin/reset_member_password'); ?>';var i=document.createElement('input');i.type='hidden';i.name='its_id';i.value='<?php echo addslashes($member['ITS_ID']??''); ?>';f.appendChild(i);document.body.appendChild(f);f.submit();}"><i class="fa fa-key"></i> Reset Password</button>
      <a href="<?php echo htmlspecialchars($redirect); ?>" class="btn-cancel">Cancel</a>
      <button type="submit" class="btn-save"><i class="fa fa-save"></i> Save Changes</button>
    </div>
  </div>

  </form>

  <script>
  (function(){
    /* Accordion */
    document.querySelectorAll('[data-pt]').forEach(function(hd){
      var body=document.getElementById(hd.getAttribute('data-pt'));
      var chev=hd.querySelector('.ph-chevron');
      if(!body) return;
      var open=hd.classList.contains('open');
      hd.addEventListener('click',function(){
        open=!open;
        body.style.display=open?'':'none';
        hd.classList.toggle('open',open);
        if(chev) chev.style.transform=open?'rotate(180deg)':'';
      });
    });

    /* HOF type */
    var typeSel=document.getElementById('hofTypeSelect');
    var hofWrap=document.getElementById('hofSelectWrapper');
    if(typeSel) typeSel.addEventListener('change',function(){if(hofWrap)hofWrap.style.display=this.value==='HOF'?'none':'';});

    /* Verification date */
    function today(){return new Date().toISOString().slice(0,10);}
    document.querySelectorAll('.ver-status').forEach(function(sel){
      sel.addEventListener('change',function(){
        var di=document.getElementById(this.getAttribute('data-date-target'));
        if(!di)return;
        if(this.value==='Verified'){if(!di.value)di.value=today();}
        else if(this.value===''||this.value==='Not Verified'){di.value='';}
      });
    });

    /* Living status → member status */
    var dS=document.getElementById('deeniStatusSel'),hS=document.getElementById('healthStatusSel'),rS=document.getElementById('residentialStatusSel');
    var aS=document.getElementById('activityStatusSel'),aD=document.getElementById('activityStatusDisplay');
    function upStatus(){
      if(!dS||!hS||!rS||!aS) return;
      var t=[dS.options[dS.selectedIndex]?.text||'',hS.options[hS.selectedIndex]?.text||'',rS.options[rS.selectedIndex]?.text||''];
      var hasI=t.some(function(x){return x.indexOf('(Inactive)')>-1;});
      var hasA=t.some(function(x){return x.indexOf('(Active)')>-1;});
      if(hasI) aS.value='inactive'; else if(hasA) aS.value='active';
      if(aD){var v=aS.value;aD.textContent=v?v.charAt(0).toUpperCase()+v.slice(1):'— None —';}
    }
    if(dS)dS.addEventListener('change',upStatus);
    if(hS)hS.addEventListener('change',upStatus);
    if(rS)rS.addEventListener('change',upStatus);
    upStatus();

    /* Sector / Sub-sector */
    var sectorMapEdit=<?php echo json_encode($sector_map??[]); ?>;
    var inchargesMap=<?php echo json_encode($incharges_map??[]); ?>;
    var sectorSel=document.getElementById('sectorSelectEdit');
    var subSel=document.getElementById('subSectorSelectEdit');
    var preSector='<?php echo addslashes($member['Sector']??''); ?>';
    var preSub='<?php echo addslashes($member['Sub_Sector']??''); ?>';
    function setN(n,v){var e=document.getElementsByName(n)[0];if(e)e.value=v||'';}
    function upIncharges(){
      var sec=sectorSel?sectorSel.value:'',sub=subSel?subSel.value:'';
      var si=(inchargesMap&&inchargesMap.sectors)?inchargesMap.sectors.find(function(s){return s.Sector===sec;}):null;
      setN('Sector_Incharge_ITSID',si&&si.Sector_Incharge_ITSID||'');setN('Sector_Incharge_Name',si&&si.Sector_Incharge_Name||'');
      setN('Sector_Incharge_Female_ITSID',si&&si.Sector_Incharge_Female_ITSID||'');setN('Sector_Incharge_Female_Name',si&&si.Sector_Incharge_Female_Name||'');
      var ssi=(inchargesMap&&inchargesMap.sub_sectors)?inchargesMap.sub_sectors.find(function(s){return s.Sector===sec&&s.Sub_Sector===sub;}):null;
      setN('Sub_Sector_Incharge_ITSID',ssi&&ssi.Sub_Sector_Incharge_ITSID||'');setN('Sub_Sector_Incharge_Name',ssi&&ssi.Sub_Sector_Incharge_Name||'');
      setN('Sub_Sector_Incharge_Female_ITSID',ssi&&ssi.Sub_Sector_Incharge_Female_ITSID||'');setN('Sub_Sector_Incharge_Female_Name',ssi&&ssi.Sub_Sector_Incharge_Female_Name||'');
    }
    function popSub(sec){
      subSel.innerHTML='<option value="">-- Select Sub Sector --</option>';subSel.disabled=true;
      if(sec&&sectorMapEdit[sec]&&sectorMapEdit[sec].length){
        sectorMapEdit[sec].forEach(function(ss){var o=document.createElement('option');o.value=ss;o.textContent=ss;if(ss===preSub)o.selected=true;subSel.appendChild(o);});
        subSel.disabled=false;
      }
    }
    if(sectorSel){sectorSel.addEventListener('change',function(){preSub='';popSub(this.value);upIncharges();});if(preSector)popSub(preSector);}
    if(subSel) subSel.addEventListener('change',upIncharges);

    /* HOF Autocomplete */
    var hofIn=document.getElementById('hof_autocomplete'),hofId=document.getElementById('hof_id'),hofList=document.getElementById('hof_autocomplete_list');
    function esc(s){if(!s)return'';return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    if(hofIn&&hofList){
      var db=null;
      hofIn.addEventListener('input',function(){
        var v=this.value.trim();
        if(!v){hofId.value='';hofList.classList.remove('open');hofList.innerHTML='';return;}
        clearTimeout(db);
        db=setTimeout(function(){
          fetch('<?php echo base_url("admin/search_hofs_autocomplete"); ?>?q='+encodeURIComponent(v))
            .then(function(r){return r.json();})
            .then(function(data){
              hofList.innerHTML='';
              if(data&&data.length){
                data.forEach(function(item){
                  var d=document.createElement('div');d.className='hof-item';
                  var ini=(item.Full_Name||'?').split(' ').map(function(w){return w[0]||'';}).slice(0,2).join('').toUpperCase();
                  d.innerHTML='<div class="hof-av">'+esc(ini)+'</div><div><div class="hof-name">'+esc(item.Full_Name)+'</div><div class="hof-its">ITS: '+esc(item.ITS_ID)+'</div></div>';
                  d.addEventListener('click',function(){
                    hofId.value=item.ITS_ID;hofIn.value=item.Full_Name+' ('+item.ITS_ID+')';
                    hofList.classList.remove('open');hofList.innerHTML='';
                    if(item.Sector&&sectorSel){sectorSel.value=item.Sector;preSub=item.Sub_Sector||'';popSub(item.Sector);}
                    ['Sector_Incharge_ITSID','Sector_Incharge_Name','Sector_Incharge_Female_ITSID','Sector_Incharge_Female_Name','Sub_Sector_Incharge_ITSID','Sub_Sector_Incharge_Name','Sub_Sector_Incharge_Female_ITSID','Sub_Sector_Incharge_Female_Name'].forEach(function(n){setN(n,item[n]||'');});
                  });
                  hofList.appendChild(d);
                });
              } else { hofList.innerHTML='<div class="hof-empty">No members found</div>'; }
              hofList.classList.add('open');
            })
            .catch(function(){hofList.innerHTML='<div class="hof-empty" style="color:var(--red);">Failed to load</div>';hofList.classList.add('open');});
        },300);
      });
      document.addEventListener('click',function(e){if(e.target!==hofIn&&!hofList.contains(e.target))hofList.classList.remove('open');});
    }

    /* AJAX submit */
    var form=document.getElementById('editMemberForm'),stEl=document.getElementById('editMemberStatus');
    form.addEventListener('submit',function(e){
      e.preventDefault();
      var fd=new FormData(form);
      var av=document.getElementById('activityStatusSel')?document.getElementById('activityStatusSel').value:'';
      fd.set('activity_status',av);
      var ht=fd.get('hof_type');
      if(ht==='HOF'){fd.set('HOF_FM_TYPE','HOF');fd.set('HOF_ID',fd.get('its_id'));}
      else{fd.set('HOF_FM_TYPE','FM');}
      fetch(form.action,{method:'POST',body:fd})
        .then(function(r){return r.json();})
        .then(function(j){
          if(j.status==='success'){stEl.textContent='✓ Saved';stEl.className='save-status success';setTimeout(function(){window.location.href='<?php echo addslashes($redirect); ?>';},700);}
          else{stEl.textContent=j.message||'Update failed';stEl.className='save-status error';}
        })
        .catch(function(){stEl.textContent='Network error';stEl.className='save-status error';});
    });
  })();
  </script>

  <?php else: ?>
    <div style="background:var(--red-bg);border:1px solid #fca5a5;border-radius:12px;padding:18px 22px;color:var(--red);font-weight:700;margin-top:20px;">
      <i class="fa fa-exclamation-triangle" style="margin-right:8px;"></i>Member not found.
    </div>
  <?php endif; ?>

</div>
</div>