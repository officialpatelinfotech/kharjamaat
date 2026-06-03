<?php /* View Single Member Details — Golden Theme */ ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
#vmApp .hdr-btn.primary {
  background: rgba(255,255,255,.92); color: var(--gold);
  border-color: rgba(255,255,255,.6);
}
#vmApp .hdr-btn.primary:hover { background: #fff; }

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

/* ── Masonry columns ── */
#vmApp .masonry-grid {
  column-count: 2; column-gap: 16px;
}
@media (max-width: 768px) {
  #vmApp .masonry-grid { column-count: 1; }
}

/* ── Sidebar sticky ── */
#vmApp .fin-sidebar {
  position: sticky; top: 72px;
}
</style>

<?php
$role = isset($_SESSION['user']['role']) ? (int)$_SESSION['user']['role'] : 0;
$back_url = 'javascript:history.back();';
$view_member_base = 'admin/viewmember/';
if ($role === 1)      { $back_url = base_url('admin'); }
elseif ($role === 2)  { $back_url = base_url('amilsaheb'); $view_member_base = 'amilsaheb/viewmember/'; }
elseif ($role === 3)  { $back_url = base_url('anjuman'); }
elseif ($role === 16) { $back_url = base_url('MasoolMusaid'); $view_member_base = 'MasoolMusaid/viewmember/'; }
elseif ($role >= 4 && $role <= 15) { $back_url = base_url('Umoor'); }
?>

<div id="vmApp">
<div class="container pt-4 pb-5" style="max-width:1200px;">

<?php if(empty($member)): ?>
  <div style="background:var(--red-bg);border:1px solid #fca5a5;border-radius:12px;padding:18px 22px;color:var(--red);font-weight:700;margin-top:20px;">
    <i class="fa fa-exclamation-triangle" style="margin-right:8px;"></i>No member data available.
  </div>
<?php else: ?>
<?php
  $humanize = function($key) {
    $k = str_replace(['_id','_'], [' ID',' '], $key);
    $k = preg_replace('/\s+/', ' ', trim($k));
    return ucwords($k);
  };
  $getInitials = function($name) {
    if(!$name) return '?';
    $parts = preg_split('/\s+/', trim($name));
    if(count($parts)===1) return strtoupper(substr($parts[0],0,1));
    return strtoupper(substr($parts[0],0,1).substr($parts[count($parts)-1],0,1));
  };

  $hof_id = !empty($member['HOF_ID']) ? $member['HOF_ID'] : $member['ITS_ID'];
  $family_members    = $family_members    ?? [];
  $family_financials = $family_financials ?? [];

  usort($family_members, function($a,$b){
    $aH=(($a['HOF_FM_TYPE']??'')==='HOF')?0:1;
    $bH=(($b['HOF_FM_TYPE']??'')==='HOF')?0:1;
    return $aH-$bH;
  });

  $is_admin_or_amilsaheb = in_array($role,[1,2,3]);
  $show_deeni_status = in_array($role,[1,2,3]);

  $matchLabels = [
    'its_sabeel_both_khar' => ['ITS & Sabeel both in Khar','success'],
    'its_khar_sabeel_out'  => ['ITS in Khar, Sabeel outside','warning'],
    'sabeel_khar_its_out'  => ['Sabeel in Khar, ITS outside','info'],
    'both_not_khar'        => ['Both not in Khar','secondary'],
  ];
  $actClasses = ['active'=>'success','inactive'=>'danger','temporary'=>'warning'];
  $its_match = $member['its_sabeel_match'] ?? '';
  $actStatus = $member['activity_status']  ?? '';
  $matchLbl  = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][0] : 'Not calculated';
  $matchCls  = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][1] : 'secondary';
  $actCls    = $actClasses[$actStatus] ?? 'secondary';

  $isF = strtolower($member['Gender'] ?? '') === 'female';
  $memberName = trim($member['Full_Name'] ?? '');
  $initials = $getInitials($memberName);

  $groups = [
    'My Details'               => ['ITS_ID','Full_Name','Full_Name_Arabic','Category','Idara','Mobile','Email','WhatsApp_No','WhatsApp','Registered_Family_Mobile'],
    'HOF Details'              => ['HOF_ID','HOF_FM_TYPE','Family_ID','TanzeemFile_No'],
    'Personal Details'         => ['First_Prefix','Prefix_Year','First_Name','Father_Prefix','Father_Name','Father_Surname','Father_ITS_ID','Surname','Husband_Name','Husband_Prefix','Husband_ITS_ID','Mother_ITS_ID','Spouse_ITS_ID','Gender','Age','Marital_Status','Blood_Group','Date_of_Nikah','Date_Of_Nikah','Date_Of_Nikah_Hijri','Misaq','Warakatul_Tarkhis'],
    'Sector & Location'        => ['Sector','Sub_Sector','Sector_Incharge_Name','Sector_Incharge_Female_Name','Sub_Sector_Incharge_Name','Sub_Sector_Incharge_Female_Name'],
    'Residence Address'        => ['Housing','Type_of_House','Address','Building','Street','Area','City','State','Pincode'],
    'Origin & Community'       => ['Organisation','Vatan','Nationality','Jamaat','Jamiaat'],
    'Education & Skills'       => ['Qualification','Languages','Hunars'],
    'Work Details'             => ['Occupation','Sub_Occupation','Sub_Occupation2'],
    'Religious Milestones'     => ['Ashara_Mubaraka','Karbala_Ziyarat','Raudat_Tahera_Ziyarat','Qadambosi_Sharaf','Quran_Sanad'],
    'Verification & Scan'      => ['Data_Verifcation_Status','Data_Verification_Date','Photo_Verifcation_Status','Photo_Verification_Date','Last_Scanned_Event','Last_Scanned_Place','Inactive_Status'],
    'Sharaf Details'           => ['Title'],
  ];

  $group_icons = [
    'My Details'           => 'fa-id-card',
    'HOF Details'          => 'fa-home',
    'Personal Details'     => 'fa-user',
    'Sector & Location'    => 'fa-map-marker',
    'Residence Address'    => 'fa-building',
    'Origin & Community'   => 'fa-globe',
    'Education & Skills'   => 'fa-graduation-cap',
    'Work Details'         => 'fa-briefcase',
    'Religious Milestones' => 'fa-star',
    'Verification & Scan'  => 'fa-shield',
    'Sharaf Details'       => 'fa-certificate',
  ];

  $rendered_keys = [];
  $member_keys_map = [];
  foreach(array_keys($member) as $k) $member_keys_map[strtolower($k)] = $k;

  // Build financial panel HTML
  ob_start();
  $fin = $family_financials ?? [];
  $sabeel_map   = $fin['sabeel']   ?? [];
  $fmb_map      = $fin['fmb']      ?? [];
  $wajebaat_map = $fin['wajebaat'] ?? [];
  $thaali_map   = $fin['thaali']   ?? [];
  $husain_map   = $fin['husain']   ?? [];
  $corpus_rows  = $fin['corpus']   ?? [];

  $tot_sabeel=0; $sabeel_year='';
  $tot_fmb=0; $fmb_year='';
  $tot_waj_paid=0; $tot_waj_due=0;
  $thaali_active=false; $husain_active=false; $husain_tot=0;

  foreach($family_members as $fm){
    $fmId=$fm['ITS_ID'];
    if(isset($sabeel_map[$fmId])){ $tot_sabeel+=$sabeel_map[$fmId]['total_sabeel']; if(!$sabeel_year)$sabeel_year=$sabeel_map[$fmId]['year']; }
    if(isset($fmb_map[$fmId])){ $tot_fmb+=$fmb_map[$fmId]['fmb_amount']; if(!$fmb_year)$fmb_year=$fmb_map[$fmId]['fmb_year']; }
    if(isset($wajebaat_map[$fmId])){ $tot_waj_paid+=$wajebaat_map[$fmId]['amount']; $tot_waj_due+=$wajebaat_map[$fmId]['due']; }
    if(isset($thaali_map[$fmId])&&$thaali_map[$fmId]['want_thali']==1) $thaali_active=true;
    if(isset($husain_map[$fmId])){ $husain_active=true; $husain_tot+=$husain_map[$fmId]['amount']; }
  }

  $sabeel_link=''; $fmb_link=''; $waj_link=''; $husain_link=''; $corpus_link='';
  if($role===1){
    $sabeel_link=base_url("admin/sabeeltakhmeendashboard?its_id={$hof_id}");
    $fmb_link   =base_url("admin/managefmbtakhmeen?its_id={$hof_id}");
    $waj_link   =base_url("admin/wajebaat?its_id={$hof_id}");
    $husain_link=base_url("admin/qardanhasana/husain?its={$hof_id}");
    $corpus_link=base_url("admin/corpusfunds_hofs?its_id={$hof_id}");
  } elseif($role===3){
    $sabeel_link=base_url("anjuman/sabeeltakhmeendashboard?its_id={$hof_id}");
    $fmb_link   =base_url("anjuman/fmbthaalitakhmeen?its={$hof_id}");
    $waj_link   =base_url("anjuman/wajebaat?its_id={$hof_id}");
    $husain_link=base_url("anjuman/qardanhasana/husain?its={$hof_id}");
    $corpus_link=base_url("anjuman/corpusfunds_receive?its_id={$hof_id}");
  }

  $fin_rows = [
    ['label'=>'Sabeel '.($sabeel_year?"($sabeel_year)":''),'link'=>$sabeel_link,'amounts'=>[['assigned','₹'.number_format($tot_sabeel,0)]]],
    ['label'=>'FMB Takhmeen '.($fmb_year?"($fmb_year)":''),'link'=>$fmb_link,'amounts'=>[['assigned','₹'.number_format($tot_fmb,0)]]],
    ['label'=>'Wajebaat','link'=>$waj_link,'amounts'=>[['paid','₹'.number_format($tot_waj_paid,0).' paid'],($tot_waj_due>0?['due','₹'.number_format($tot_waj_due,0).' due']:null)]],
    ['label'=>'Thaali Taking','link'=>$fmb_link,'badge'=>$thaali_active?'yes':'no','badge_text'=>$thaali_active?'Yes':'No'],
    ['label'=>'Husain Scheme','link'=>$husain_link,'badge'=>$husain_active?'yes':'no','badge_text'=>$husain_active?'Yes (₹'.number_format($husain_tot,0).')':'No'],
  ];
?>
<div class="panel" style="margin-bottom:0;">
  <div class="panel-hd open" data-fin-toggle>
    <div class="ph-left">
      <span class="ph-icon"><i class="fa fa-rupee-sign"></i></span>
      <span class="ph-title">Family Financials</span>
    </div>
    <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
  </div>
  <div class="panel-bd" id="fin-body">
    <?php foreach($fin_rows as $fr): if(!$fr) continue; ?>
      <?php if($fr['link']): ?><a href="<?php echo $fr['link']; ?>" class="fin-row-link"><?php endif; ?>
      <div class="fin-row">
        <span class="fin-label"><?php echo htmlspecialchars($fr['label']); ?></span>
        <?php if(!empty($fr['badge'])): ?>
          <span class="fin-badge <?php echo $fr['badge']; ?>"><?php echo htmlspecialchars($fr['badge_text']); ?></span>
        <?php else: ?>
          <div class="fin-amounts">
            <?php foreach(($fr['amounts']??[]) as $a): if(!$a) continue; ?>
              <span class="fin-amt <?php echo $a[0]; ?>"><?php echo $a[1]; ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if($fr['link']): ?></a><?php endif; ?>
    <?php endforeach; ?>

    <?php if(!empty($corpus_rows)): ?>
      <div class="corpus-hd"><i class="fa fa-university"></i> Corpus Funds</div>
      <?php foreach($corpus_rows as $cf): $due=max(0,$cf['amount_assigned']-$cf['paid']); ?>
        <?php if($corpus_link): ?><a href="<?php echo $corpus_link; ?>" class="fin-row-link"><?php endif; ?>
        <div class="fin-row" style="padding-left:28px;">
          <span class="fin-label"><?php echo htmlspecialchars($cf['title']); ?></span>
          <div class="fin-amounts">
            <span class="fin-amt assigned">₹<?php echo number_format($cf['amount_assigned'],0); ?></span>
            <span class="fin-amt paid">₹<?php echo number_format($cf['paid'],0); ?> paid</span>
            <span class="fin-amt due">₹<?php echo number_format($due,0); ?> due</span>
          </div>
        </div>
        <?php if($corpus_link): ?></a><?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<?php $financial_panel_html = ob_get_clean(); ?>

<!-- ── Page Header ── -->
<div class="vm-header">
  <div>
    <p class="vm-eyebrow">Member Profile</p>
    <h1 class="vm-title">
      View Member Details
      <small><?php echo htmlspecialchars($memberName); ?> &mdash; ITS <?php echo htmlspecialchars($member['ITS_ID'] ?? ''); ?></small>
    </h1>
  </div>
  <div class="vm-header-actions">
    <a href="<?php echo $back_url; ?>" class="hdr-btn">
      <i class="fa fa-arrow-left"></i> Back
    </a>
    <?php if(!empty($member['ITS_ID']) && in_array($role,[1,2,3])): ?>
      <?php $edit_base=($role===2)?'amilsaheb/editmember/':'admin/editmember/'; $rq='?redirect='.urlencode($_SERVER['REQUEST_URI']); ?>
      <a href="<?php echo base_url($edit_base).$member['ITS_ID'].$rq; ?>" class="hdr-btn primary">
        <i class="fa fa-pencil"></i> Edit
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- ── Member Hero ── -->
<div class="member-hero">
  <div class="hero-avatar <?php echo $isF?'female':''; ?>"><?php echo htmlspecialchars($initials); ?></div>
  <div style="flex:1; min-width:0;">
    <h2 class="hero-name"><?php echo htmlspecialchars($memberName ?: '—'); ?></h2>
    <div class="hero-meta">
      <?php if(!empty($member['ITS_ID'])): ?>
        <span><i class="fa fa-id-badge"></i> ITS: <?php echo htmlspecialchars($member['ITS_ID']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['Gender'])): ?>
        <span><i class="fa fa-<?php echo $isF?'female':'male'; ?>"></i> <?php echo htmlspecialchars($member['Gender']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['Age'])): ?>
        <span><i class="fa fa-birthday-cake"></i> Age <?php echo htmlspecialchars($member['Age']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['Mobile'])): ?>
        <span><i class="fa fa-phone"></i> <?php echo htmlspecialchars($member['Mobile']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['Sector'])): ?>
        <span><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($member['Sector']); ?><?php echo !empty($member['Sub_Sector'])?' — '.htmlspecialchars($member['Sub_Sector']):''; ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ── Status Pills ── -->
<div class="status-strip">
  <?php if($its_match): ?>
    <span class="spill <?php echo $matchCls; ?>"><i class="fa fa-link"></i> <?php echo htmlspecialchars($matchLbl); ?></span>
  <?php endif; ?>
  <?php if($actStatus): ?>
    <span class="spill <?php echo $actCls; ?>"><i class="fa fa-circle"></i> <?php echo ucfirst(htmlspecialchars($actStatus)); ?></span>
  <?php endif; ?>
  <?php if(!empty($member['HOF_FM_TYPE'])): ?>
    <span class="spill info"><i class="fa fa-home"></i> <?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?></span>
  <?php endif; ?>
</div>

<!-- ── Status Panel (full width) ── -->
<div class="panel">
  <div class="panel-hd open" data-panel-target="grp-status">
    <div class="ph-left">
      <span class="ph-icon"><i class="fa fa-toggle-on"></i></span>
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
      <?php if($show_deeni_status): ?>
      <div class="sg-item">
        <div class="sg-label">Deeni Status <span class="sg-badge sensitive">Sensitive</span></div>
        <div class="sg-val <?php echo empty($member['deeni_status'])?'muted':''; ?>"><?php echo htmlspecialchars($member['deeni_status'] ?? '—'); ?></div>
      </div>
      <?php endif; ?>
      <div class="sg-item">
        <div class="sg-label">Health Status <span class="sg-badge manual">Manual</span></div>
        <div class="sg-val <?php echo empty($member['health_status'])?'muted':''; ?>"><?php echo htmlspecialchars($member['health_status'] ?? '—'); ?></div>
      </div>
      <div class="sg-item">
        <div class="sg-label">Residential Status <span class="sg-badge manual">Manual</span></div>
        <div class="sg-val <?php echo empty($member['residential_status'])?'muted':''; ?>"><?php echo htmlspecialchars($member['residential_status'] ?? '—'); ?></div>
      </div>
    </div>
  </div>
</div>

<!-- ── Family Members (full width) ── -->
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
      <a href="<?php echo base_url($view_member_base).$fm['ITS_ID']; ?>" class="fm-card <?php echo $fmView?'current':''; ?>">
        <div class="fm-av <?php echo $fmF?'female':''; ?>"><?php echo htmlspecialchars($fmInit); ?></div>
        <div style="flex:1;min-width:0;">
          <div class="fm-name">
            <?php echo htmlspecialchars($fmName); ?>
            <?php if($fmHof): ?><span class="fm-hof">HOF</span><?php endif; ?>
          </div>
          <div class="fm-its">ITS: <?php echo htmlspecialchars($fm['ITS_ID']??''); ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── Main Grid ── -->
<div class="row" style="margin-top:4px;">
  <!-- Left: detail panels -->
  <div class="<?php echo ($role===16)?'col-12':'col-lg-8 col-md-7'; ?> mb-4">
    <div class="masonry-grid">

    <?php foreach($groups as $groupName => $fields):
      $fieldsToRender = [];
      foreach($fields as $f) {
        $lf = strtolower($f);
        if(isset($member_keys_map[$lf])) $fieldsToRender[] = $member_keys_map[$lf];
      }
      if(empty($fieldsToRender)) continue;
      $icon = $group_icons[$groupName] ?? 'fa-angle-right';
      $gId  = 'grp-'.preg_replace('/[^a-z0-9]/','-',strtolower($groupName));
    ?>
    <div class="panel">
      <div class="panel-hd open" data-panel-target="<?php echo $gId; ?>">
        <div class="ph-left">
          <span class="ph-icon"><i class="fa <?php echo $icon; ?>"></i></span>
          <span class="ph-title"><?php echo htmlspecialchars($groupName); ?></span>
        </div>
        <div class="ph-chevron" style="transform:rotate(180deg);"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div class="panel-bd" id="<?php echo $gId; ?>">
        <ul class="detail-list">
          <?php foreach($fieldsToRender as $field):
            $rendered_keys[] = $field;
            $val = $member[$field];
          ?>
          <li class="detail-row">
            <div class="dr-key"><?php echo htmlspecialchars($humanize($field)); ?></div>
            <div class="dr-val <?php echo ($val===null||$val==='')?'empty':''; ?>">
              <?php echo ($val===null||$val==='') ? '—' : nl2br(htmlspecialchars((string)$val)); ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endforeach; ?>

    <?php
      $other_fields = array_filter(array_keys($member), function($k) use($rendered_keys){ return !in_array($k,$rendered_keys); });
      sort($other_fields);
      if(!empty($other_fields)):
    ?>
    <div class="panel">
      <div class="panel-hd" data-panel-target="grp-other">
        <div class="ph-left">
          <span class="ph-icon"><i class="fa fa-ellipsis-h"></i></span>
          <span class="ph-title">Additional Details</span>
          <span class="ph-badge"><?php echo count($other_fields); ?></span>
        </div>
        <div class="ph-chevron"><i class="fa fa-chevron-down"></i></div>
      </div>
      <div class="panel-bd" id="grp-other" style="display:none;">
        <ul class="detail-list">
          <?php foreach($other_fields as $field): $val=$member[$field]; ?>
          <li class="detail-row">
            <div class="dr-key"><?php echo htmlspecialchars($humanize($field)); ?></div>
            <div class="dr-val <?php echo ($val===null||$val==='')?'empty':''; ?>">
              <?php echo ($val===null||$val==='') ? '—' : nl2br(htmlspecialchars((string)$val)); ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

    </div><!-- /masonry-grid -->
  </div><!-- /col-lg-8 -->

  <!-- Right: Financial sidebar -->
  <?php if($role !== 16): ?>
  <div class="col-lg-4 col-md-5 mb-4 d-none d-md-block">
    <div class="fin-sidebar">
      <?php echo $financial_panel_html; ?>
    </div>
  </div>
  <!-- Mobile financial -->
  <div class="col-12 d-block d-md-none mb-4">
    <?php echo $financial_panel_html; ?>
  </div>
  <?php endif; ?>

</div><!-- /row -->
<?php endif; ?>
</div><!-- /container -->
</div><!-- /#vmApp -->

<script>
(function(){
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

  // Financial panel toggle (uses data-fin-toggle)
  var finHd = document.querySelector('[data-fin-toggle]');
  if(finHd){
    var finBody = document.getElementById('fin-body');
    var finChev = finHd.querySelector('.ph-chevron');
    var finOpen = true;
    finHd.addEventListener('click', function(){
      finOpen = !finOpen;
      if(finBody) finBody.style.display = finOpen ? '' : 'none';
      if(finChev) finChev.style.transform = finOpen ? 'rotate(180deg)' : '';
    });
  }
})();
</script>