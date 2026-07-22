<?php /* Member Profile View (My Profile) — Redesigned to match ViewMember.php */ ?>
<?php
  $member = $user_data ?? ($_SESSION['user_data'] ?? []);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
  integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
  crossorigin="anonymous" referrerpolicy="no-referrer">

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
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:       0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:    0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
  --radius:       16px;
  --radius-sm:    10px;
}

#vmApp, #vmApp *, #vmApp *::before, #vmApp *::after { box-sizing: border-box; }
#vmApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
  padding-top: 57px;
}

/* ── Page Header ── */
#vmApp .vm-header {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px;
  padding: 20px 26px;
  margin-bottom: 22px;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
}
#vmApp .vm-header::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#vmApp .vm-header::after {
  content: '';
  position: absolute; right: -40px; top: -40px;
  width: 200px; height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
#vmApp .vm-eyebrow {
  font-size: .67rem; font-weight: 700; letter-spacing: 1.4px;
  text-transform: uppercase; color: rgba(255,255,255,.6);
  margin-bottom: 3px; position: relative; z-index: 1;
}
#vmApp .vm-title {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.4rem; font-weight: 600; color: #fff;
  line-height: 1.2; margin: 0; position: relative; z-index: 1;
}
#vmApp .vm-title small {
  font-size: .85rem; font-weight: 400;
  color: rgba(255,255,255,.7); display: block; margin-top: 2px;
}
#vmApp .vm-header-actions {
  display: flex; align-items: center; gap: 10px;
  position: relative; z-index: 1; flex-shrink: 0; flex-wrap: wrap;
}
#vmApp .hdr-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 9px;
  font-size: .8rem; font-weight: 700;
  text-decoration: none; transition: background .15s; white-space: nowrap;
  border: 1px solid rgba(255,255,255,.3);
  background: rgba(255,255,255,.15); color: #fff;
}
#vmApp .hdr-btn:hover { background: rgba(255,255,255,.28); color: #fff; text-decoration: none; }

/* ── Member hero card ── */
#vmApp .member-hero {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  margin-bottom: 18px;
  display: flex; align-items: center; gap: 18px;
  box-shadow: var(--shadow-sm);
  position: relative; overflow: hidden;
}
#vmApp .member-hero::before {
  content: '';
  position: absolute; left: 0; top: 0; bottom: 0;
  width: 4px;
  background: linear-gradient(180deg, var(--gold), var(--gold-light));
}
#vmApp .hero-avatar {
  width: 60px; height: 60px; border-radius: 50%;
  background: linear-gradient(135deg, var(--gold), #c9a227);
  color: #fff; display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 1.3rem; flex-shrink: 0;
  box-shadow: 0 3px 12px rgba(184,134,11,.3);
}
#vmApp .hero-avatar.female {
  background: linear-gradient(135deg, #b45309, #f59e0b);
}
#vmApp .hero-name {
  font-family: 'Literata', Georgia, serif;
  font-size: 1.2rem; font-weight: 600; color: var(--text-1);
  margin: 0 0 4px;
}
#vmApp .hero-meta {
  display: flex; flex-wrap: wrap; gap: 10px;
  font-size: .76rem; color: var(--text-3);
}
#vmApp .hero-meta span {
  display: inline-flex; align-items: center; gap: 4px;
}
#vmApp .hero-meta i { color: var(--gold); }

/* ── Status Pills ── */
#vmApp .status-strip {
  display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px;
}
#vmApp .spill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 13px; border-radius: 40px;
  font-size: .76rem; font-weight: 700;
  border: 1.5px solid transparent;
}
#vmApp .spill.success  { background: var(--green-bg); color: var(--green); border-color: #86efac; }
#vmApp .spill.danger   { background: var(--red-bg);   color: var(--red);   border-color: #fca5a5; }
#vmApp .spill.warning  { background: #fffbeb; color: #92400e; border-color: #fcd34d; }
#vmApp .spill.info     { background: var(--blue-bg);  color: var(--blue);  border-color: #93c5fd; }
#vmApp .spill.secondary{ background: var(--surface-2);color: var(--text-2);border-color: var(--border); }

/* ── Panel cards ── */
#vmApp .panel {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 16px;
  box-shadow: var(--shadow-sm);
  overflow: hidden;
  break-inside: avoid;
  display: inline-block;
  width: 100%;
  transition: box-shadow .2s;
}
#vmApp .panel:hover { box-shadow: var(--shadow); }
#vmApp .panel-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 18px;
  background: var(--surface-2);
  border-bottom: 1.5px solid var(--border-light);
  cursor: pointer; user-select: none;
  transition: background .15s;
}
#vmApp .panel-hd:hover { background: var(--gold-muted); }
#vmApp .panel-hd.open  { background: var(--gold-muted); border-bottom-color: var(--gold-border); }
#vmApp .ph-left { display: flex; align-items: center; gap: 9px; }
#vmApp .ph-icon {
  width: 28px; height: 28px; border-radius: 7px;
  background: var(--gold-muted); color: var(--gold);
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .78rem; flex-shrink: 0;
}
#vmApp .panel-hd.open .ph-icon { background: rgba(184,134,11,.18); }
#vmApp .ph-title {
  font-size: .82rem; font-weight: 800; color: var(--text-2);
  text-transform: uppercase; letter-spacing: .5px;
}
#vmApp .ph-badge {
  font-size: .65rem; font-weight: 700; padding: 2px 8px;
  border-radius: 20px; background: var(--border-light); color: var(--text-3);
}
#vmApp .ph-chevron {
  width: 24px; height: 24px; border-radius: 6px;
  background: var(--surface); border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: .68rem; color: var(--text-3);
  transition: transform .2s;
}
#vmApp .panel-hd.open .ph-chevron { transform: rotate(180deg); }
#vmApp .panel-bd {
  padding: 0;
}

/* ── Detail rows ── */
#vmApp .detail-list { list-style: none; margin: 0; padding: 0; }
#vmApp .detail-row {
  display: flex; align-items: baseline; gap: 0;
  border-bottom: 1px solid var(--border-light);
  min-height: 38px;
}
#vmApp .detail-row:last-child { border-bottom: none; }
#vmApp .dr-key {
  flex: 0 0 42%; max-width: 42%;
  padding: 10px 14px;
  font-size: .76rem; font-weight: 700; color: var(--text-3);
  text-transform: uppercase; letter-spacing: .3px;
  background: var(--surface-2);
  border-right: 1px solid var(--border-light);
  word-break: break-word;
}
#vmApp .dr-val {
  flex: 1; padding: 10px 14px;
  font-size: .84rem; color: var(--text-1); word-break: break-word;
}
#vmApp .dr-val.empty { color: var(--text-3); font-style: italic; }
@media (max-width: 480px) {
  #vmApp .dr-key { flex: 0 0 38%; max-width: 38%; font-size: .7rem; padding: 8px 10px; }
  #vmApp .dr-val { font-size: .8rem; padding: 8px 10px; }
}

/* ── Status grid inside panel ── */
#vmApp .status-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 14px; padding: 16px 18px;
}
#vmApp .sg-item {}
#vmApp .sg-label {
  font-size: .7rem; color: var(--text-3); font-weight: 700;
  text-transform: uppercase; letter-spacing: .3px;
  margin-bottom: 4px; display: flex; align-items: center; gap: 5px;
}
#vmApp .sg-badge {
  display: inline-block; font-size: .6rem; font-weight: 700;
  padding: 1px 7px; border-radius: 12px;
}
#vmApp .sg-badge.auto     { background: var(--gold-muted); color: var(--gold); }
#vmApp .sg-badge.manual   { background: var(--orange-bg);  color: var(--orange); }
#vmApp .sg-badge.sensitive{ background: var(--red-bg);     color: var(--red); }
#vmApp .sg-val {
  font-size: .9rem; font-weight: 700; color: var(--text-1);
}
#vmApp .sg-val.green  { color: var(--green); }
#vmApp .sg-val.red    { color: var(--red); }
#vmApp .sg-val.muted  { color: var(--text-3); font-weight: 400; }

/* ── Family members ── */
#vmApp .fm-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 6px; padding: 14px 16px;
}
#vmApp .fm-card {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: var(--radius-sm);
  border: 1.5px solid var(--border);
  text-decoration: none; color: inherit;
  transition: border-color .15s, box-shadow .15s, transform .12s;
  background: var(--surface);
}
#vmApp .fm-card:hover {
  border-color: var(--gold); box-shadow: var(--shadow-sm);
  transform: translateY(-1px); text-decoration: none; color: inherit;
}
#vmApp .fm-card.current { border-color: var(--gold); background: var(--gold-muted); }
#vmApp .fm-av {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, var(--gold), #c9a227);
  color: #fff; display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: .85rem; flex-shrink: 0;
}
#vmApp .fm-av.female { background: linear-gradient(135deg, #b45309, #f59e0b); }
#vmApp .fm-name { font-weight: 700; font-size: .84rem; color: var(--text-1); }
#vmApp .fm-its  { font-size: .7rem; color: var(--text-3); margin-top: 1px; }
#vmApp .fm-hof  {
  font-size: .62rem; font-weight: 700; padding: 1px 7px;
  border-radius: 12px; background: var(--blue-bg); color: var(--blue);
  margin-left: 5px; flex-shrink: 0;
}
#vmApp .fm-status-active {
  font-size: .62rem; font-weight: 700; padding: 1px 7px;
  border-radius: 12px; background: var(--green-bg); color: var(--green);
  margin-left: 5px; flex-shrink: 0;
}
#vmApp .fm-status-inactive {
  font-size: .62rem; font-weight: 700; padding: 1px 7px;
  border-radius: 12px; background: var(--red-bg); color: var(--red);
  margin-left: 5px; flex-shrink: 0;
}
#vmApp .fm-status-temporary {
  font-size: .62rem; font-weight: 700; padding: 1px 7px;
  border-radius: 12px; background: var(--orange-bg); color: var(--orange);
  margin-left: 5px; flex-shrink: 0;
}

/* ── Financial sidebar ── */
#vmApp .fin-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 11px 18px;
  border-bottom: 1px solid var(--border-light);
  gap: 10px;
  transition: background .12s;
}
#vmApp .fin-row:last-child { border-bottom: none; }
#vmApp a.fin-row-link { text-decoration: none; color: inherit; display: block; }
#vmApp a.fin-row-link:hover .fin-row { background: var(--gold-muted); }
#vmApp .fin-label { font-size: .8rem; font-weight: 600; color: var(--text-2); }
#vmApp .fin-amounts { display: flex; flex-wrap: wrap; gap: 6px; justify-content: flex-end; }
#vmApp .fin-amt { font-size: .8rem; font-weight: 700; }
#vmApp .fin-amt.assigned { color: var(--blue); }
#vmApp .fin-amt.paid     { color: var(--green); }
#vmApp .fin-amt.due      { color: var(--red); }
#vmApp .fin-badge { font-size: .75rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
#vmApp .fin-badge.yes { background: var(--green-bg); color: var(--green); }
#vmApp .fin-badge.no  { background: var(--red-bg);   color: var(--red); }
#vmApp .corpus-hd {
  padding: 10px 18px 6px;
  font-size: .74rem; font-weight: 800; color: var(--text-3);
  text-transform: uppercase; letter-spacing: .5px;
  border-top: 2px dashed var(--border);
  margin-top: 4px;
  display: flex; align-items: center; gap: 7px;
}

/* ── Main Layout grid ── */
#vmApp .details-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  align-items: start;
  margin-top: 4px;
}
@media (min-width: 992px) {
  #vmApp .details-grid {
    grid-template-columns: 1.2fr 1fr;
  }
}
</style>

<div id="vmApp">
<div class="container pt-4 pb-5" style="max-width:1200px;">

  <!-- PAGE HEADER -->
  <div class="vm-header mt-5">
    <div>
      <div class="vm-eyebrow">My Profile</div>
      <h2 class="vm-title">Karim bhai Mohsinali bhai Bhuriwala <small>» Dashboard</small></h2>
    </div>
    <div class="vm-header-actions">
      <a href="<?php echo base_url('accounts/home'); ?>" class="hdr-btn">
        <i class="fa fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <?php
    if (!class_exists('MemberStatusM')) {
      CI_Controller::get_instance()->load->model('MemberStatusM');
    }
    $deeni_status_options       = MemberStatusM::deeni_status_options();
    $health_status_options      = MemberStatusM::health_status_options();
    $residential_status_options = MemberStatusM::residential_status_options();

    $getInitials = function($name) {
      if(!$name) return '?';
      $parts = preg_split('/\s+/', trim($name));
      if(count($parts)===1) return strtoupper(substr($parts[0],0,1));
      return strtoupper(substr($parts[0],0,1).substr($parts[count($parts)-1],0,1));
    };

    $stripActiveInactive = function($str) {
      return trim(preg_replace('/\s*\((Active|Inactive)\)\s*$/i', '', $str));
    };

    $itsMatch = $member['its_sabeel_match'] ?? '';
    $actStatus = $member['activity_status'] ?? '';
    $healthStatus = $member['health_status'] ?? '';
    $deeniStatus = $member['deeni_status'] ?? '';
    $residentialStatus = $member['residential_status'] ?? '';

    $actClasses = ['active' => 'success', 'inactive' => 'danger', 'temporary' => 'warning'];

    $matchLbl = $itsMatch !== '' ? MemberStatusM::match_status_label($itsMatch) : 'Not calculated';
    $matchCls = $itsMatch !== '' ? MemberStatusM::match_status_badge_class($itsMatch) : 'secondary';
    $actCls = isset($actClasses[$actStatus]) ? $actClasses[$actStatus] : 'secondary';

    $isF = strtolower($member['Gender'] ?? '') === 'female';
    $memberName = trim($member['Full_Name'] ?? '');
    $initials = $getInitials($memberName);
  ?>

  <!-- HERO CARD -->
  <div class="member-hero">
    <div class="hero-avatar <?php echo $isF ? 'female' : ''; ?>"><?php echo htmlspecialchars($initials); ?></div>
    <div style="flex:1; min-width:0;">
      <h3 class="hero-name"><?php echo htmlspecialchars($memberName); ?></h3>
      <div class="hero-meta">
        <span><i class="fa fa-id-card"></i> ITS: <?php echo htmlspecialchars($member['ITS_ID']); ?></span>
        <?php if(!empty($member['Gender'])): ?><span><i class="fa fa-user"></i> <?php echo ucfirst(strtolower($member['Gender'])); ?></span><?php endif; ?>
        <?php if(!empty($member['Age'])): ?><span><i class="fa fa-birthday-cake"></i> Age <?php echo htmlspecialchars($member['Age']); ?></span><?php endif; ?>
        <?php if(!empty($member['Sector'])): ?><span><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($member['Sector']); ?></span><?php endif; ?>
      </div>
    </div>
  </div>

  <!-- STATUS PILLS -->
  <div class="status-strip">
    <span class="spill <?php echo $matchCls; ?>"><i class="fa fa-link"></i> <?php echo htmlspecialchars($matchLbl); ?></span>
    <span class="spill <?php echo $actCls; ?>"><i class="fa fa-circle"></i> <?php echo ucfirst(htmlspecialchars($actStatus)); ?></span>
    <?php if(!empty($member['HOF_FM_TYPE'])): ?><span class="spill info"><i class="fa fa-home"></i> <?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?></span><?php endif; ?>
  </div>

  <!-- MEMBER STATUS PANEL -->
  <div class="panel">
    <div class="panel-hd open" data-panel-target="grp-status">
      <div class="ph-left">
        <span class="ph-icon"><i class="fa fa-info-circle"></i></span>
        <span class="ph-title">Member Status</span>
      </div>
      <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
    </div>
    <div class="panel-bd" id="grp-status">
      <div class="status-grid">
        <div class="sg-item">
          <div class="sg-label">ITS–Sabeel Match <span class="sg-badge auto">Auto</span></div>
          <div class="sg-val"><?php echo htmlspecialchars($matchLbl ?: '—'); ?></div>
        </div>
        <div class="sg-item">
          <div class="sg-label">Member Status <span class="sg-badge auto">Auto</span></div>
          <div class="sg-val <?php echo $actCls==='success'?'green':($actCls==='danger'?'red':''); ?>">
            <?php echo $actStatus ? ucfirst(htmlspecialchars($actStatus)) : '—'; ?>
          </div>
        </div>

        <div class="sg-item">
          <div class="sg-label">Health Status <span class="sg-badge manual">Manual</span></div>
          <div class="sg-val <?php echo MemberStatusM::is_inactive_trigger('health', $healthStatus) ? 'red' : ''; ?>">
            <?php
              $healthLabel = !empty($healthStatus) ? ($health_status_options[$healthStatus] ?? $healthStatus) : '—';
              echo htmlspecialchars($stripActiveInactive($healthLabel));
            ?>
          </div>
        </div>
        <div class="sg-item">
          <div class="sg-label">Residential Status <span class="sg-badge manual">Manual</span></div>
          <div class="sg-val <?php echo MemberStatusM::is_inactive_trigger('residential', $residentialStatus) ? 'red' : ''; ?>">
            <?php
              $resLabel = !empty($residentialStatus) ? ($residential_status_options[$residentialStatus] ?? $residentialStatus) : '—';
              echo htmlspecialchars($stripActiveInactive($resLabel));
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FAMILY MEMBERS -->
  <?php if(!empty($family_members)): ?>
  <div class="panel">
    <div class="panel-hd open" data-panel-target="grp-family">
      <div class="ph-left">
        <span class="ph-icon"><i class="fa fa-users"></i></span>
        <span class="ph-title">Family Members</span>
        <span class="ph-badge"><?php echo count($family_members); ?></span>
      </div>
      <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
    </div>
    <div class="panel-bd" id="grp-family">
      <div class="fm-grid">
        <?php foreach($family_members as $fm):
          $fmF = strtolower($fm['Gender']??'')==='female';
          $fmName = trim($fm['Full_Name'] ?? ($fm['First_Name'].' '.$fm['Surname']));
          $fmInit = $getInitials($fmName);
          $fmHof  = ($fm['HOF_FM_TYPE']??'')==='HOF';
          $fmView = ($fm['ITS_ID']==$member['ITS_ID']);
        ?>
        <div class="fm-card <?php echo $fmView?'current':''; ?>">
          <div class="fm-av <?php echo $fmF?'female':''; ?>"><?php echo htmlspecialchars($fmInit); ?></div>
          <div style="flex:1;min-width:0;">
            <div class="fm-name">
              <?php echo htmlspecialchars($fmName); ?>
              <?php if($fmHof): ?><span class="fm-hof">HOF</span><?php endif; ?>
              <?php
                $fmAct = $fm['activity_status'] ?? 'active';
                $fmActClass = 'fm-status-' . $fmAct;
              ?>
              <span class="<?php echo $fmActClass; ?>"><?php echo ucfirst(htmlspecialchars($fmAct)); ?></span>
            </div>
            <div class="fm-its">ITS: <?php echo htmlspecialchars($fm['ITS_ID']??''); ?><?php echo !empty($fm['Age']) ? ' &bull; Age: ' . htmlspecialchars($fm['Age']) : ''; ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- MAIN DETAILS GRID -->
  <div class="details-grid">
    <?php
      $groups = [
        'Personal Details' => [
          'ITS_ID', 'Full_Name', 'Full_Name_Arabic', 'Vatan', 'Mobile', 
          'Registered_Family_Mobile', 'Email', 'Age', 'Gender', 'Misaq', 
          'Marital_Status', 'Blood_Group'
        ],
        'Residential Address' => [
          'Address', 'City', 'Pincode'
        ],
        'Jamaat Details' => [
          'Jamaat', 'Jamiaat', 'Sector', 'Sub_Sector'
        ],
      ];

      $group_icons = [
        'Personal Details' => 'fa-user',
        'Residential Address' => 'fa-building',
        'Jamaat Details' => 'fa-globe'
      ];

      function humanize($key) {
        return ucwords(str_replace('_', ' ', $key));
      }

      // Helper to render a panel
      $renderPanel = function($group_name, $fields, $member) use ($group_icons) {
        $icon = $group_icons[$group_name] ?? 'fa-angle-right';
        $gId = 'grp-' . preg_replace('/[^a-z0-9]/', '-', strtolower($group_name));
    ?>
      <div class="panel">
        <div class="panel-hd open" data-panel-target="<?php echo $gId; ?>">
          <div class="ph-left">
            <span class="ph-icon"><i class="fa <?php echo $icon; ?>"></i></span>
            <span class="ph-title"><?php echo htmlspecialchars($group_name); ?></span>
          </div>
          <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
        </div>
        <div class="panel-bd" id="<?php echo $gId; ?>">
          <ul class="detail-list">
            <?php foreach ($fields as $field): ?>
              <?php $value = $member[$field] ?? ''; ?>
              <li class="detail-row">
                <div class="dr-key"><?php echo htmlspecialchars(humanize($field)); ?></div>
                <div class="dr-val">
                  <?php if ($field === 'Mobile'): ?>
                    <span id="mobile-display"><?php echo htmlspecialchars($value); ?></span>
                    <button class="btn btn-outline-primary btn-xs ml-2" id="edit-mobile" style="padding: 1px 6px; font-size: 0.7rem;">Edit</button>
                    <div class="edit-section" id="mobile-edit" style="display:none; margin-top: 8px;">
                      <input type="text" id="mobile-input" class="form-control form-control-sm" value="<?php echo htmlspecialchars($value); ?>" style="max-width: 250px;">
                      <div class="mt-2">
                        <button class="btn btn-success btn-sm" id="save-mobile" style="padding: 2px 10px; font-size: 0.78rem;">Save</button>
                        <button class="btn btn-secondary btn-sm" id="cancel-mobile" style="padding: 2px 10px; font-size: 0.78rem;">Cancel</button>
                      </div>
                    </div>
                  <?php elseif ($field === 'Email'): ?>
                    <span id="email-display"><?php echo htmlspecialchars($value); ?></span>
                    <button class="btn btn-outline-primary btn-xs ml-2" id="edit-email" style="padding: 1px 6px; font-size: 0.7rem;">Edit</button>
                    <div class="edit-section" id="email-edit" style="display:none; margin-top: 8px;">
                      <input type="email" id="email-input" class="form-control form-control-sm" value="<?php echo htmlspecialchars($value); ?>" style="max-width: 250px;">
                      <div class="mt-2">
                        <button class="btn btn-success btn-sm" id="save-email" style="padding: 2px 10px; font-size: 0.78rem;">Save</button>
                        <button class="btn btn-secondary btn-sm" id="cancel-email" style="padding: 2px 10px; font-size: 0.78rem;">Cancel</button>
                      </div>
                    </div>
                  <?php elseif ($field === 'Registered_Family_Mobile'): ?>
                    <span id="family-mobile-display"><?php echo htmlspecialchars($value); ?></span>
                    <button class="btn btn-outline-primary btn-xs ml-2" id="edit-family-mobile" style="padding: 1px 6px; font-size: 0.7rem;">Edit</button>
                    <div class="edit-section" id="family-mobile-edit" style="display:none; margin-top: 8px;">
                      <input type="text" id="family-mobile-input" class="form-control form-control-sm" value="<?php echo htmlspecialchars($value); ?>" style="max-width: 250px;">
                      <div class="mt-2">
                        <button class="btn btn-success btn-sm" id="save-family-mobile" style="padding: 2px 10px; font-size: 0.78rem;">Save</button>
                        <button class="btn btn-secondary btn-sm" id="cancel-family-mobile" style="padding: 2px 10px; font-size: 0.78rem;">Cancel</button>
                      </div>
                    </div>
                  <?php else: ?>
                    <?php if ($value === ''): ?>
                      <span class="empty" style="color: var(--text-3); font-style: italic;">—</span>
                    <?php else: ?>
                      <?php echo nl2br(htmlspecialchars((string)$value)); ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php }; ?>

    <div class="details-left">
      <?php $renderPanel('Personal Details', $groups['Personal Details'], $member); ?>
    </div>

    <div class="details-right">
      <?php $renderPanel('Residential Address', $groups['Residential Address'], $member); ?>
      <?php $renderPanel('Jamaat Details', $groups['Jamaat Details'], $member); ?>
    </div>
  </div>

</div>
</div>

<script>
(function() {
  // Generic panel accordion
  document.querySelectorAll('[data-panel-target]').forEach(function(hd){
    var id   = hd.getAttribute('data-panel-target');
    var body = document.getElementById(id);
    var chev = hd.querySelector('.ph-chevron');
    if(!body) return;
    // Start open if header has .open class
    var isOpen = hd.classList.contains('open');
    if(!isOpen) body.style.display = 'none';
    hd.addEventListener('click', function(){
      isOpen = !isOpen;
      body.style.display = isOpen ? '' : 'none';
      hd.classList.toggle('open', isOpen);
      if(chev) chev.style.transform = isOpen ? 'rotate(180deg)' : '';
    });
  });

  function saveField(payload, callback) {
    var form = new FormData();
    for (var key in payload) {
      form.append(key, payload[key]);
    }

    fetch('<?php echo base_url("accounts/update_profile_contact"); ?>', {
      method: 'POST',
      credentials: 'same-origin',
      body: form
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
      if (json && json.success) callback(true);
      else {
        alert((json && json.error) ? json.error : 'Save failed');
        callback(false);
      }
    })
    .catch(function() {
      alert('Save failed');
      callback(false);
    });
  }

  // MOBILE
  document.getElementById('edit-mobile')?.addEventListener('click', function() {
    document.getElementById('mobile-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-mobile')?.addEventListener('click', function() {
    document.getElementById('mobile-edit').style.display = 'none';
    document.getElementById('edit-mobile').style.display = 'inline-block';
  });

  document.getElementById('save-mobile')?.addEventListener('click', function() {
    var value = document.getElementById('mobile-input').value.trim();
    saveField({ mobile: value }, function(success) {
      if (!success) return;
      document.getElementById('mobile-display').textContent = value;
      document.getElementById('mobile-edit').style.display = 'none';
      document.getElementById('edit-mobile').style.display = 'inline-block';
    });
  });

  // EMAIL
  document.getElementById('edit-email')?.addEventListener('click', function() {
    document.getElementById('email-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-email')?.addEventListener('click', function() {
    document.getElementById('email-edit').style.display = 'none';
    document.getElementById('edit-email').style.display = 'inline-block';
  });

  document.getElementById('save-email')?.addEventListener('click', function() {
    var value = document.getElementById('email-input').value.trim();
    saveField({ email: value }, function(success) {
      if (!success) return;
      document.getElementById('email-display').textContent = value;
      document.getElementById('email-edit').style.display = 'none';
      document.getElementById('edit-email').style.display = 'inline-block';
    });
  });

  // REGISTERED FAMILY MOBILE
  document.getElementById('edit-family-mobile')?.addEventListener('click', function() {
    document.getElementById('family-mobile-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-family-mobile')?.addEventListener('click', function() {
    document.getElementById('family-mobile-edit').style.display = 'none';
    document.getElementById('edit-family-mobile').style.display = 'inline-block';
  });

  document.getElementById('save-family-mobile')?.addEventListener('click', function() {
    var value = document.getElementById('family-mobile-input').value.trim();
    saveField({ registered_family_mobile: value }, function(success) {
      if (!success) return;
      document.getElementById('family-mobile-display').textContent = value;
      document.getElementById('family-mobile-edit').style.display = 'none';
      document.getElementById('edit-family-mobile').style.display = 'inline-block';
    });
  });

})();
</script>
