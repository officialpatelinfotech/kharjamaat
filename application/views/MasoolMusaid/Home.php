<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
/* ── Scoped to #mmApp to avoid navbar bleed ── */
:root {
  --gold:        #b8860b;
  --gold-light:  #e6c84a;
  --gold-muted:  #f5e9c0;
  --bg:          #faf7f0;
  --surface:     #ffffff;
  --surface-2:   #f7f4ec;
  --border:      #e8e0cc;
  --border-light:#f0ece0;
  --text-1:      #1a1610;
  --text-2:      #5a5244;
  --text-3:      #9c8f7a;
  --green:       #1a6645;
  --green-bg:    #eaf4ee;
  --red:         #b91c1c;
  --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow:      0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:   0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
}

#mmApp, #mmApp *, #mmApp *::before, #mmApp *::after { box-sizing: border-box; }
#mmApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: var(--text-1);
  background: var(--bg);
  min-height: 100vh;
  padding-top: 57px;
}
#mmApp a { color: inherit; }

/* ── Header banner ── */
#mmApp .mm-header {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: 22px; padding: 22px 28px; margin-bottom: 24px;
  position: relative; overflow: hidden;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
#mmApp .mm-header::before {
  content: ''; position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}
#mmApp .mm-header::after {
  content: ''; position: absolute; right: -50px; top: -50px;
  width: 200px; height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
  pointer-events: none;
}
#mmApp .mm-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 4px; position: relative; z-index: 1; }
#mmApp .mm-title { font-family: 'Literata', Georgia, serif; font-size: 1.5rem; font-weight: 600; color: #fff; line-height: 1.15; margin: 0; position: relative; z-index: 1; }
#mmApp .mm-title span { color: rgba(255,255,255,.72); font-size: .95rem; font-weight: 500; display: block; margin-top: 2px; }
#mmApp .mm-badge {
  position: relative; z-index: 1; flex-shrink: 0;
  background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
  border-radius: 12px; padding: 8px 14px; backdrop-filter: blur(6px); text-align: center;
}
#mmApp .mm-badge-icon { font-size: 1.4rem; color: rgba(255,255,255,.85); display: block; }
#mmApp .mm-badge-lbl { font-size: .6rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .5px; text-transform: uppercase; margin-top: 2px; display: block; }

/* ── Incharge banner ── */
#mmApp .incharge-banner {
  background: var(--gold-muted); border: 1px solid #e6c84a55;
  border-radius: 14px; padding: 14px 20px; margin-bottom: 20px;
  box-shadow: 0 4px 14px rgba(184,134,11,.1);
}
#mmApp .incharge-banner .ib-title {
  text-align: center; color: var(--gold); font-weight: 800; font-size: .75rem;
  text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;
}
#mmApp .incharge-banner .ib-names {
  display: flex; flex-wrap: wrap; justify-content: center; align-items: center;
  gap: 8px; font-size: .92rem; font-weight: 600; color: var(--text-1);
}
#mmApp .incharge-banner .ib-sep { color: var(--border); }

/* ── Surface card ── */
#mmApp .surf {
  background: var(--surface); border-radius: 16px; border: 1px solid var(--border);
  box-shadow: var(--shadow-sm); padding: 18px 20px; margin-bottom: 18px;
  position: relative; overflow: hidden; transition: box-shadow .2s;
}
#mmApp .surf:hover { box-shadow: var(--shadow); }
#mmApp .surf.compact { padding: 14px 16px; }

/* ── Section header ── */
#mmApp .sec-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding-bottom: 10px; margin-bottom: 14px;
  border-bottom: 1.5px solid var(--border-light);
}
#mmApp .sec-hd-title {
  font-size: .9rem; font-weight: 800; color: var(--text-2);
  display: flex; align-items: center; gap: 8px; margin: 0;
}
#mmApp .sec-hd-title .hd-icon {
  width: 26px; height: 26px; border-radius: 7px;
  background: var(--gold-muted); color: var(--gold);
  display: inline-flex; align-items: center; justify-content: center; font-size: .72rem;
}

/* ── Hijri switcher ── */
#mmApp .hijri-switcher { display: flex; justify-content: center; align-items: center; margin: 4px 0 14px; }
#mmApp .chev-box {
  width: 38px; height: 38px; border: 1px solid var(--border); border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  background: var(--surface); color: var(--text-2); font-size: 1rem;
  transition: background .15s, color .15s;
}
#mmApp .chev-box:hover { background: var(--gold-muted); color: var(--gold); }
#mmApp .hijri-nav-btn.disabled { pointer-events: none; opacity: .4; }
#mmApp #hijri-current-title { margin: 0 18px; color: var(--gold) !important; font-weight: 700; font-size: 1rem; }

/* ── Mini stat cards ── */
#mmApp .mini-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: 10px; padding: 14px 10px; text-align: center;
  transition: box-shadow .14s, transform .14s;
}
#mmApp .mini-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-1px); }
#mmApp .stats-value {
  font-size: 1.5rem; font-weight: 800; color: var(--text-1);
  display: block; line-height: 1; margin-bottom: 4px;
}
#mmApp .stats-label {
  font-size: .68rem; font-weight: 700; letter-spacing: .4px;
  color: var(--text-3); text-transform: uppercase; display: block;
}

/* ── Action buttons (replaces colored Bootstrap cards) ── */
#mmApp .action-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}
@media (min-width: 576px) { #mmApp .action-grid { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 992px) { #mmApp .action-grid { grid-template-columns: repeat(5, 1fr); } }

#mmApp .action-btn {
  position: relative; display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 10px;
  padding: 20px 12px; border-radius: 14px; text-decoration: none;
  overflow: hidden; min-height: 110px;
  transition: transform .15s, box-shadow .15s;
  box-shadow: var(--shadow);
}
#mmApp .action-btn::after {
  content: ''; position: absolute; inset: 0;
  background: rgba(255,255,255,0); transition: background .2s;
}
#mmApp .action-btn:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); text-decoration: none; }
#mmApp .action-btn:hover::after { background: rgba(255,255,255,.1); }
#mmApp .action-btn .ab-icon {
  width: 44px; height: 44px; border-radius: 12px;
  background: rgba(255,255,255,.22); backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  box-shadow: inset 0 0 0 1px rgba(255,255,255,.3);
  font-size: 1.3rem; color: #fff; flex-shrink: 0;
}
#mmApp .action-btn .ab-label {
  font-size: .72rem; font-weight: 700; letter-spacing: .4px;
  text-transform: uppercase; color: #fff; text-align: center; line-height: 1.3;
}

/* Scoped overview, member search, cards styles adapted for mmApp */
#mmApp .section-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 0 10px; margin-bottom: 14px;
  border-bottom: 1.5px solid var(--border-light);
}
#mmApp .section-hd-title {
  font-size: .88rem; font-weight: 800; color: var(--text-2);
  display: flex; align-items: center; gap: 8px; margin: 0;
}
#mmApp .section-hd-title .hd-icon {
  width: 26px; height: 26px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: .72rem; flex-shrink: 0;
  background: var(--gold-muted); color: var(--gold);
}
#mmApp .toggle-btn {
  width: 26px; height: 26px; border-radius: 50%;
  border: 1.5px solid var(--border); background: var(--surface-2);
  color: var(--text-3); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: .68rem; transition: all .2s; flex-shrink: 0;
}
#mmApp .btn-outline-secondary { border-color: var(--border); color: var(--text-2); font-size: .78rem; }
#mmApp .btn-outline-secondary:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); }
#mmApp .btn-outline-primary { border-color: var(--blue); color: var(--blue); font-size: .78rem; }
#mmApp .btn-sm { font-size: .76rem; }

#mmApp .surf {
  background: var(--surface);
  border-radius: 20px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  padding: 20px 22px;
  margin-bottom: 18px;
  position: relative; overflow: hidden;
  transition: box-shadow .2s;
}
#mmApp .surf:hover { box-shadow: var(--shadow); }
#mmApp .surf.compact { padding: 16px 18px; }

#mmApp .ov-card {
  background: var(--surface);
  border: 1.5px solid var(--border);
  border-radius: 14px;
  padding: 12px 14px;
  display: flex; align-items: center; gap: 12px;
  height: 100%;
  transition: border-color .2s, box-shadow .2s, transform .2s;
  position: relative; overflow: hidden;
}
#mmApp .ov-card:hover { border-color: var(--gold); box-shadow: var(--shadow); transform: translateY(-2px); }
#mmApp .ov-card::after {
  content: ''; position: absolute; bottom: 0; left: 0; right: 0;
  height: 2px; background: var(--gold);
  transform: scaleX(0); transition: transform .2s; transform-origin: left;
}
#mmApp .ov-card:hover::after { transform: scaleX(1); }
#mmApp .ov-card.green  { border-color: #86efac; } #mmApp .ov-card.green::after  { background: var(--green); }
#mmApp .ov-card.red    { border-color: #fca5a5; } #mmApp .ov-card.red::after    { background: var(--red); }
#mmApp .ov-icon {
  width: 36px; height: 36px; border-radius: 9px;
  display: inline-flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: .95rem;
}
#mmApp .ov-body { display: flex; flex-direction: column; min-width: 0; }
#mmApp .ov-label { font-size: .7rem; color: var(--text-3); font-weight: 600; letter-spacing: .2px; line-height: 1.3; margin: 0; }
#mmApp .ov-value {
  font-size: 1.2rem; font-weight: 800;
  color: var(--text-1); line-height: 1.1; margin: 2px 0 0;
  word-break: break-all; overflow-wrap: anywhere;
}

#mmApp .mini-card {
  background: var(--surface-2); border: 1px solid var(--border);
  border-radius: 8px; padding: 12px 10px; text-align: center;
  transition: box-shadow .15s, transform .15s;
}
#mmApp .mini-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-1px); }
#mmApp .mini-val {
  font-size: 1.15rem; font-weight: 800; color: var(--text-1);
  display: block; line-height: 1;
  word-break: break-all; overflow-wrap: anywhere;
  font-size: clamp(.8rem, 2.5vw, 1.15rem);
}
#mmApp .mini-val.green { color: var(--green); }
#mmApp .mini-val.red   { color: var(--red); }
#mmApp .mini-lbl {
  font-size: .65rem; font-weight: 700; letter-spacing: .4px;
  color: var(--text-3); text-transform: uppercase;
  margin-top: 5px; display: block;
}

#member-search-block { overflow: visible !important; }
#mmApp .msw-wrap { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
#mmApp .msw-heading {
  font-size: .78rem; font-weight: 800; color: var(--text-2);
  text-transform: uppercase; letter-spacing: .5px;
}
#mmApp .msw-sub { font-size: .7rem; color: var(--text-3); }
#mmApp .msw-right { flex: 1 1 auto; display: flex; gap: 10px; align-items: center; justify-content: flex-end; }
#mmApp .msw-ig { position: relative; flex: 1 1 240px; max-width: 460px; }
#mmApp .msw-ig .ico { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-3); font-size: .82rem; pointer-events: none; }
#mmApp #mswInput {
  width: 100%; height: 38px; padding: 0 36px 0 34px;
  border: 1.5px solid var(--border); border-radius: 8px;
  font-family: 'Plus Jakarta Sans', sans-serif; font-size: .86rem;
  color: var(--text-1); background: var(--surface-2); outline: none;
  transition: border-color .15s, box-shadow .15s;
}
#mmApp #mswInput:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,11,.12); background: var(--surface); }
#mmApp .msw-spinner { position: absolute; right: 34px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(0,0,0,.08); border-top-color: var(--gold); animation: mspin .7s linear infinite; display: none; }
#mmApp .msw-spinner.active { display: block; }
@keyframes mspin { to { transform: translateY(-50%) rotate(360deg); } }
#mmApp .msw-clear { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); background: var(--border); border: none; border-radius: 4px; width: 20px; height: 20px; display: none; align-items: center; justify-content: center; cursor: pointer; color: var(--text-3); font-size: .68rem; line-height: 1; }
#mmApp .msw-clear.visible { display: flex; }
#mmApp #mswDropdown { position: absolute; top: calc(100% + 5px); left: 0; right: 0; background: var(--surface); border: 1.5px solid var(--border); border-radius: 14px; box-shadow: var(--shadow-lg); z-index: 1050; display: none; max-height: 280px; overflow-y: auto; }
#mmApp #mswDropdown.open { display: block; }
#mmApp .msw-item { display: flex; align-items: center; gap: 11px; padding: 10px 13px; cursor: pointer; transition: background .12s; border-bottom: 1px solid var(--border-light); }
#mmApp .msw-item:last-child { border-bottom: none; }
#mmApp .msw-item:hover { background: var(--gold-muted); }
#mmApp .msw-av { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, var(--gold), #c9a227); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .85rem; flex-shrink: 0; }
#mmApp .msw-av.f { background: linear-gradient(135deg, #b45309, #f59e0b); }
#mmApp .msw-name { font-weight: 700; color: var(--text-1); font-size: .85rem; }
#mmApp .msw-meta { font-size: .7rem; color: var(--text-3); margin-top: 2px; }
#mmApp .msw-its { margin-left: auto; background: var(--gold-muted); color: var(--gold); font-size: .66rem; font-weight: 800; padding: 2px 7px; border-radius: 40px; white-space: nowrap; flex-shrink: 0; }
#mmApp .msw-empty { padding: 16px; text-align: center; color: var(--text-3); font-size: .85rem; }
#mmApp .sc-incharge-toggle {
  font-size: .73rem; color: var(--text-3); cursor: pointer;
  display: flex; align-items: center; justify-content: space-between;
  padding: 6px 0 0; border-top: 1px solid var(--border-light); margin-top: 8px;
  background: none; border-left: none; border-right: none; border-bottom: none;
  width: 100%; text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;
}
#mmApp .sc-incharge-toggle:hover { color: var(--gold); }
#mmApp .tk-hint { font-size: .73rem; color: var(--text-3); text-align: center; margin: 6px 0 0; cursor: pointer; }
#mmApp .container-year { position: absolute; right: 20px; top: 20px; font-size: .73rem; font-weight: 700; color: var(--text-3); }
@media (min-width: 768px) { #mmApp .col-md-5th { flex: 0 0 20%; max-width: 20%; } }
@media (max-width: 991px) {
  #mmApp .ov-value { font-size: clamp(.85rem, 3.5vw, 1.2rem); }
  #mmApp .mini-val { font-size: clamp(.72rem, 2.8vw, 1.1rem); }
}
@media (max-width: 400px) {
  #mmApp .ov-value, #mmApp .mini-val { font-size: .75rem; letter-spacing: -.3px; }
}

/* ── RSVP miqaat block loading state ── */
#mmApp #miqaatLoading {
  display: none; position: absolute; left: 0; right: 0; top: 0; bottom: 0;
  align-items: center; justify-content: center;
  background: rgba(255,255,255,.6); border-radius: 20px; z-index: 5;
}
#mmApp #miqaatLoading.active { display: flex; }
@keyframes miqaat-spin { to { transform: rotate(360deg); } }
#mmApp .miqaat-spinner {
  width: 32px; height: 32px; border-radius: 50%;
  border: 3px solid rgba(0,0,0,.08); border-top-color: var(--gold);
  animation: miqaat-spin 1s linear infinite;
}
</style>

<?php
  if (!function_exists('format_inr')) {
    function format_inr($num) {
      $num = (int)round((float)$num); $n = strval($num); $len = strlen($n);
      if ($len <= 3) return $n;
      $last3 = substr($n,-3); $rest = substr($n,0,$len-3); $parts=[];
      while(strlen($rest)>2){$parts[]=substr($rest,-2);$rest=substr($rest,0,strlen($rest)-2);}
      if($rest!=='')$parts[]=$rest;
      return implode(',',array_reverse($parts)).','.$last3;
    }
  }
?>

<div id="mmApp">
  <div class="container pt-4 pb-5">

    <!-- ── Header banner ── -->
    <div class="mm-header">
      <div>
        <p class="mm-eyebrow">Masool &amp; Musaid</p>
        <h1 class="mm-title">
          Anjuman-e-Saifee
          <span><?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></span>
        </h1>
      </div>
      <div class="mm-badge" style="border-radius:14px; padding:10px 16px;">
        <span class="badge-val" style="font-size:1.5rem; font-weight:800; color:#fff; line-height:1; display:block;"><?= (int)(($stats['active_inactive']['active'] ?? 0) + ($stats['active_inactive']['inactive'] ?? 0)) ?></span>
        <span class="mm-badge-lbl" style="font-size:.65rem; font-weight:700; color:rgba(255,255,255,.65); letter-spacing:.5px; text-transform:uppercase; margin-top:3px; display:block;">Total Members</span>
      </div>
    </div>

    <!-- ── Incharge banner (role 16 only) ── -->
    <?php
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 16) {
        $ci =& get_instance();
        $username_raw = strtoupper(trim($_SESSION['user']['username']));
        $incharge_male = ''; $incharge_female = ''; $title = '';
        $sec = $username_raw; $sub = '';

        if (in_array(substr($username_raw, -1), ['A','B','C'])) {
            $sec_candidate = ucfirst(strtolower(substr($username_raw, 0, -1)));
            $ci->db->where('Sector', $sec_candidate);
            $count = $ci->db->count_all_results('user');
            if ($count > 0) { $sec = $sec_candidate; $sub = substr($username_raw, -1); }
        }
        $sec = ucfirst(strtolower($sec));

        if ($sub !== '') {
            $ci->db->select('Sub_Sector_Incharge_Name, Sub_Sector_Incharge_Female_Name');
            $ci->db->from('user');
            $ci->db->where('Sector', $sec);
            $ci->db->where('Sub_Sector', $sub);
            $ci->db->group_start();
            $ci->db->where("Sub_Sector_Incharge_Name != '' AND Sub_Sector_Incharge_Name IS NOT NULL");
            $ci->db->or_where("Sub_Sector_Incharge_Female_Name != '' AND Sub_Sector_Incharge_Female_Name IS NOT NULL");
            $ci->db->group_end();
            $ci->db->limit(1);
            $row = $ci->db->get()->row_array();
            if ($row) {
                $incharge_male   = trim($row['Sub_Sector_Incharge_Name'] ?? '');
                $incharge_female = trim($row['Sub_Sector_Incharge_Female_Name'] ?? '');
                $title           = htmlspecialchars($sec . ' ' . $sub) . ' Sub-Sector Incharges';
            }
        } else {
            $ci->db->select('Sector_Incharge_Name, Sector_Incharge_Female_Name');
            $ci->db->from('user');
            $ci->db->where('Sector', $sec);
            $ci->db->group_start();
            $ci->db->where("Sector_Incharge_Name != '' AND Sector_Incharge_Name IS NOT NULL");
            $ci->db->or_where("Sector_Incharge_Female_Name != '' AND Sector_Incharge_Female_Name IS NOT NULL");
            $ci->db->group_end();
            $ci->db->limit(1);
            $row = $ci->db->get()->row_array();
            if ($row) {
                $incharge_male   = trim($row['Sector_Incharge_Name'] ?? '');
                $incharge_female = trim($row['Sector_Incharge_Female_Name'] ?? '');
                $title           = htmlspecialchars($sec) . ' Sector Incharges';
            }
        }

        if ($incharge_male !== '' || $incharge_female !== ''): ?>
        <div class="incharge-banner">
          <div class="ib-title"><i class="fa fa-users" style="margin-right:6px;"></i><?= $title ?></div>
          <div class="ib-names">
            <?php if ($incharge_male !== ''): ?>
              <span><i class="fa fa-male" style="color:#1d4ed8;margin-right:5px;"></i><?= htmlspecialchars($incharge_male) ?></span>
            <?php endif; ?>
            <?php if ($incharge_male !== '' && $incharge_female !== ''): ?>
              <span class="ib-sep d-none d-md-inline">|</span>
            <?php endif; ?>
            <?php if ($incharge_female !== ''): ?>
              <span><i class="fa fa-female" style="color:#b91c1c;margin-right:5px;"></i><?= htmlspecialchars($incharge_female) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php endif;
    } ?>

    <!-- Member Search -->
    <div class="surf mb-3" id="member-search-block" style="overflow:visible;">
      <div class="msw-wrap">
        <div class="d-none d-md-flex" style="flex-direction:column;gap:2px;">
          <span class="msw-heading"><i class="fa fa-search" style="margin-right:4px;"></i>Member Search</span>
          <span class="msw-sub">Search by name or ITS ID</span>
        </div>
        <div class="msw-right">
          <div class="msw-ig">
            <i class="fa fa-search ico"></i>
            <input type="text" id="mswInput" placeholder="Type name or ITS ID…" autocomplete="off">
            <div class="msw-spinner" id="mswSpinner"></div>
            <button class="msw-clear" id="mswClear">&times;</button>
            <div id="mswDropdown" role="listbox"></div>
          </div>
          <a href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap;border-radius:8px;font-size:.78rem;font-weight:700;border-color:var(--border);color:var(--text-2);">
            <i class="fa fa-users"></i> All Members
          </a>
        </div>
      </div>
    </div>

    <!-- ── Thaali Signup card ── -->
    <div class="surf compact">
      <div class="sec-hd">
        <h4 class="sec-hd-title">
          <span class="hd-icon"><i class="fa fa-cutlery"></i></span>
          Thaali Signup for Current Month
        </h4>
        <a id="thaali-details-btn" href="#" class="btn btn-sm btn-primary text-white" style="font-size:.76rem;font-weight:700;border-radius:8px;">
          View details
        </a>
      </div>

      <?php
      $this->load->model('HijriCalendar');
      $hijri_today = $selected_hijri_parts ?? $this->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
      $current_hijri_year  = (int)($hijri_today['hijri_year']  ?? 0);
      $current_hijri_month = (int)($hijri_today['hijri_month'] ?? 0);
      $monthRow = $this->db->where('id', $current_hijri_month)->get('hijri_month')->row_array();
      $current_hijri_month_name = $monthRow['hijri_month'] ?? '';

      $prev_month = $current_hijri_month - 1; $prev_year = $current_hijri_year;
      $next_month = $current_hijri_month + 1; $next_year = $current_hijri_year;
      if ($prev_month < 1)  { $prev_month = 12; $prev_year--; }
      if ($next_month > 12) { $next_month = 1;  $next_year++; }

      $hijri_days  = $this->HijriCalendar->get_hijri_days_for_month_year($current_hijri_month, $current_hijri_year);
      $month_start = $hijri_days[0]['greg_date']                       ?? date('Y-m-01');
      $month_end   = $hijri_days[count($hijri_days) - 1]['greg_date']  ?? date('Y-m-t');
      ?>

      <script>
      (function(){
        var btn=document.getElementById('thaali-details-btn');
        if(!btn||!window.USER_NAME)return;
        var params=new URLSearchParams({from:'masoolmusaid',start_date:'<?= $month_start ?>',end_date:'<?= $month_end ?>',sector:window.USER_NAME});
        btn.href='<?= base_url('common/thaali_signups_breakdown') ?>?'+params.toString();
      })();
      </script>

      <!-- Hijri switcher -->
      <div class="hijri-switcher">
        <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $prev_year ?>" data-hijri-month="<?= $prev_month ?>">
          <div class="chev-box"><i class="fa fa-chevron-left"></i></div>
        </a>
        <div id="hijri-current-title"><?= htmlspecialchars($current_hijri_month_name . ' ' . $current_hijri_year) ?></div>
        <a href="#" class="hijri-nav-btn" data-hijri-year="<?= $next_year ?>" data-hijri-month="<?= $next_month ?>">
          <div class="chev-box"><i class="fa fa-chevron-right"></i></div>
        </a>
      </div>

      <!-- Loader -->
      <div id="monthLoader" class="text-center my-2" style="display:none;color:var(--text-3);font-size:.85rem;">
        <i class="fa fa-spinner fa-spin"></i> Loading month…
      </div>

      <!-- Month stats -->
      <div id="thaali-month-block" class="row text-center">
        <div class="col-6 mb-2">
          <a href="#" class="open-hof-modal" data-modal-type="signed" data-hijri-year="<?= $current_hijri_year ?>" data-hijri-month="<?= $current_hijri_month ?>" style="text-decoration:none;color:inherit;display:block;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($month_stats['families_signed_up'] ?? 0) ?></div>
              <div class="stats-label">Signed up this month</div>
            </div>
          </a>
        </div>
        <div class="col-6 mb-2">
          <a href="#" class="open-hof-modal" data-modal-type="no" data-hijri-year="<?= $current_hijri_year ?>" data-hijri-month="<?= $current_hijri_month ?>" style="text-decoration:none;color:inherit;display:block;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($month_stats['no_thaali_count'] ?? 0) ?></div>
              <div class="stats-label">No signup this month</div>
            </div>
          </a>
        </div>
      </div>

      <!-- HOF List Modal -->
      <div class="modal fade" id="hofListModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header py-2">
              <h6 class="modal-title" id="hofListLabel"></h6>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div id="miqaatPopupMeta" style="display:none;margin-bottom:14px;background:var(--surface-2);border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.82rem;"></div>
              <div id="hofListLoading" class="text-center py-3" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
              <div id="hofListContainer" style="max-height:60vh;overflow:auto;"></div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /surf -->

    <!-- ── Quick Action Buttons ── -->
    <div class="surf">
      <div class="sec-hd">
        <h4 class="sec-hd-title"><span class="hd-icon"><i class="fa fa-th-large"></i></span> Quick Actions</h4>
      </div>
      <div class="action-grid">
        <?php
        $username_raw = $_SESSION['user']['username'] ?? '';
        $is_sub_sector = false;
        if (preg_match('/^(Burhani|Mohammedi|Saifee|Taheri|Najmi)([A-Z])$/i', $username_raw)) {
            $is_sub_sector = true;
        }
        $directory_label = $is_sub_sector ? 'Mumineen in your Sub Mohalla' : 'Mumineen in your Mohalla';

        $actions = [
          ['url' => base_url('MasoolMusaid/mumineendirectory'), 'icon' => 'fa fa-users',           'label' => $directory_label],
          ['url' => base_url('MasoolMusaid/asharaohbat'),       'icon' => 'fa fa-calendar',         'label' => 'Ashara Ohbat'],
          ['url' => base_url('MasoolMusaid/ashara_attendance'), 'icon' => 'fa fa-user-check',       'label' => 'Ashara Attendance'],
          ['url' => base_url('MasoolMusaid/rsvp_list'),         'icon' => 'fa fa-calendar-check-o', 'label' => 'Miqaat RSVP'],
          ['url' => base_url('MasoolMusaid/miqaat_attendance'), 'icon' => 'fa fa-check-square-o',   'label' => 'Miqaat Attendance'],
        ];
        $btnColors = [
          ['#8e44ad','#fff'], ['#d97706','#fff'], ['#870000','#fff'],
          ['#d35400','#fff'], ['#006a3f','#fff'],
        ];
        foreach ($actions as $i => $act):
          $bg = $btnColors[$i % count($btnColors)][0];
        ?>
        <a href="<?= $act['url'] ?>" class="action-btn" style="background:<?= $bg ?>;">
          <div class="ab-icon"><i class="<?= $act['icon'] ?>"></i></div>
          <div class="ab-label"><?= $act['label'] ?></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ── Overview ── -->
    <div class="surf">
      <h2 style="font-size:1rem;font-weight:800;color:var(--text-1);margin:0 0 18px;text-align:center;letter-spacing:-.2px;">Mohalla Overview</h2>

      <!-- Member Status -->
      <div class="section-hd">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-toggle-on"></i></span> Member Status</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseMMPMemberActivity" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPMemberActivity">
        <div class="row mb-3">
          <div class="col-6">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active') ?>">
              <div class="ov-card green">
                <div class="ov-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-check-circle"></i></div>
                <div class="ov-body"><span class="ov-label">Active Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <div class="col-6">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=inactive') ?>">
              <div class="ov-card red">
                <div class="ov-icon" style="background:#fef2f2;color:#b91c1c;"><i class="fa fa-times-circle"></i></div>
                <div class="ov-body"><span class="ov-label">Inactive Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['inactive'] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- ITS Sabeel Match -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-exchange"></i></span> ITS Sabeel Match</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseMMPItsSabeelMatch" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPItsSabeelMatch">
        <div class="row mb-3">
          <?php
          $statCards = [
            ['its_sabeel_both_khar',  'ITS & Sabeel in '.jamaat_place(), 'fa-home',         '#f5e9c0','#b8860b'],
            ['sabeel_khar_its_out',   'Sabeel in Khar, ITS out',         'fa-external-link','#ecfeff','#0891b2'],
            ['its_khar_sabeel_out',   'ITS in Khar, Sabeel out',         'fa-sign-out',     '#fff7ed','#b45309'],
            ['both_not_khar',         'Both not in Khar',                'fa-ban',           '#fef2f2','#b91c1c'],
          ];
          foreach ($statCards as [$key, $label, $icon, $bg, $color]): ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?its_sabeel_match='.$key) ?>">
              <div class="ov-card">
                <div class="ov-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div>
                <div class="ov-body"><span class="ov-label"><?= $label ?></span><span class="ov-value"><?= (int)($stats['active_inactive'][$key] ?? 0) ?></span></div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- General Overall Stats -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-info-circle"></i></span> General Overall Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseMMPGeneralOverallStats" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPGeneralOverallStats">
        <div class="row mb-3">
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-users"></i></div><div class="ov-body"><span class="ov-label">Total Members</span><span class="ov-value"><?= (int)($stats['active_inactive']['active'] ?? 0) ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=hof_fm_type&value=HOF') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-user"></i></div><div class="ov-body"><span class="ov-label">HOF</span><span class="ov-value"><?= $stats['HOF'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=hof_fm_type&value=FM') ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-user-plus"></i></div><div class="ov-body"><span class="ov-label">FM</span><span class="ov-value"><?= $stats['FM'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=gender&value=male') ?>"><div class="ov-card"><div class="ov-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-male"></i></div><div class="ov-body"><span class="ov-label">Males</span><span class="ov-value"><?= $stats['Mardo'] ?></span></div></div></a></div>
          <div class="col-6 col-md-5th mb-3"><a href="<?= base_url('MasoolMusaid/mumineendirectory?status=active&filter=gender&value=female') ?>"><div class="ov-card"><div class="ov-icon" style="background:#fdf2f8;color:#9d174d;"><i class="fa fa-female"></i></div><div class="ov-body"><span class="ov-label">Females</span><span class="ov-value"><?= $stats['Bairo'] ?></span></div></div></a></div>
          <?php
          $ageGroups=[['0','4','Age 0-4',$stats['Age_0_4']],['5','15','Age 5-15',$stats['Age_5_15']],['16','25','Age 16-25',$stats['Age_16_25']],['26','65','Age 26-65',$stats['Age_26_65']],['66','','Above 65',$stats['Buzurgo']]];
          foreach($ageGroups as[$mn,$mx,$lbl,$val]):$url=$mx?base_url("MasoolMusaid/mumineendirectory?status=active&filter=age_range&min=$mn&max=$mx"):base_url("MasoolMusaid/mumineendirectory?status=active&filter=age_range&min=$mn");?>
          <div class="col-6 col-md-5th mb-3"><a href="<?= $url ?>"><div class="ov-card"><div class="ov-icon" style="background:var(--green-bg);color:var(--green);"><i class="fa fa-child"></i></div><div class="ov-body"><span class="ov-label"><?= $lbl ?></span><span class="ov-value"><?= $val ?></span></div></div></a></div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php
      $status_counts = isset($stats['status_counts']) ? $stats['status_counts'] : [];
      $statusGroups = [
        'health_status'      => ['label'=>'Health Status',      'icon'=>'fa-heartbeat',      'bg'=>'#fef2f2','color'=>'#b91c1c','key'=>'health',      'id'=>'collapseMMPHealth'],
        'deeni_status'       => ['label'=>'Deeni Status',       'icon'=>'fa-star',           'bg'=>'#f5f3ff','color'=>'#7c3aed','key'=>'deeni',       'id'=>'collapseMMPDeeni'],
        'residential_status' => ['label'=>'Residential Status', 'icon'=>'fa-building',       'bg'=>'#eff6ff','color'=>'#1d4ed8','key'=>'residential', 'id'=>'collapseMMPResidential'],
        'Qualification'      => ['label'=>'Dunyavi Education',  'icon'=>'fa-graduation-cap', 'bg'=>'#eaf4ee','color'=>'#1a6645','key'=>'education',   'id'=>'collapseMMPEducation'],
      ];
      $CI =& get_instance();
      $CI->load->model('MemberStatusM');
      $deeni_map = MemberStatusM::deeni_status_options();
      $health_map = MemberStatusM::health_status_options();
      $res_map = MemberStatusM::residential_status_options();

      foreach ($statusGroups as $filterKey => $g):
        if (empty($status_counts[$g['key']])) continue; ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></span><?= $g['label'] ?></h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#<?= $g['id'] ?>" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="<?= $g['id'] ?>">
        <div class="row mb-3">
          <?php foreach ($status_counts[$g['key']] as $lbl => $cnt): 
            if ($lbl==='None'||$lbl==='') continue; 
            $display_lbl = $lbl;
            if ($g['key'] === 'deeni' && isset($deeni_map[$lbl])) {
              $display_lbl = $deeni_map[$lbl];
            } elseif ($g['key'] === 'health' && isset($health_map[$lbl])) {
              $display_lbl = $health_map[$lbl];
            } elseif ($g['key'] === 'residential' && isset($res_map[$lbl])) {
              $display_lbl = $res_map[$lbl];
            }
            $display_lbl = preg_replace('/\s*\((Active|Inactive)\)$/i', '', $display_lbl);
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter='.$filterKey.'&value='.rawurlencode($lbl)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $g['bg'] ?>;color:<?= $g['color'] ?>;"><i class="fa <?= $g['icon'] ?>"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($display_lbl) ?></span><span class="ov-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <!-- Deeni Taalim -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-graduation-cap"></i></span> Deeni Taalim Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseMMPEduTracking" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPEduTracking">
        <div class="row mb-3">
          <?php
          $deeniCards=[
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15'),              '#eff6ff','#1d4ed8','fa-child',     'Total Eligible (5-15)',   $stats['deeni_eligible']??0],
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=0'),'#eaf4ee','#1a6645','fa-book','Deeni Taalim Taking',    $stats['deeni_taking']??0],
            [base_url('MasoolMusaid/mumineendirectory?status=Active&min=5&max=15&madresa_deprived=1'),'#fff7ed','#b45309','fa-book','Not Taking Deeni Taalim',$stats['madresa_deprived']??0],
          ];
          foreach($deeniCards as[$url,$bg,$color,$icon,$lbl,$val]):?>
          <div class="col-12 col-md-4 mb-3">
            <a href="<?= $url ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $bg ?>;color:<?= $color ?>;"><i class="fa <?= $icon ?>"></i></div><div class="ov-body"><span class="ov-label"><?= $lbl ?></span><span class="ov-value"><?= (int)$val ?> <small style="font-size:.7rem;color:var(--text-3);font-weight:600;">Farzando</small></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Occupation -->
      <?php if (!empty($status_counts['occupation'])): ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-briefcase"></i></span> All Occupation</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseMMPOccupation" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseMMPOccupation">
        <div class="row mb-3">
          <?php foreach ($status_counts['occupation'] as $lbl => $cnt): if ($lbl==='None'||$lbl==='') continue; ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter=Occupation&value='.rawurlencode($lbl)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:var(--surface-2);color:var(--text-2);"><i class="fa fa-briefcase"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($lbl) ?></span><span class="ov-value"><?= $cnt ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Sub-sectors -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></span> Sub-sectors</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupSubSectors" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupSubSectors">
        <div class="row mb-3">
          <?php
          $subSectorRows = isset($stats['SubSectors']) ? $stats['SubSectors'] : [];
          if (!empty($subSectorRows)) {
            $subSectorRows = array_values(array_filter($subSectorRows, fn($r) => trim($r['SubSector']??'')!=='' && strtolower($r['SubSector']??'')!=='unassigned'));
            usort($subSectorRows, fn($a,$b) => intval($b['total']??0) <=> intval($a['total']??0));
            foreach ($subSectorRows as $idx => $row):
              $hof = intval($row['hof_count']??$row['HOF']??0);
              $fm  = intval($row['fm_count'] ??$row['FM'] ??0);
          ?>
          <div class="col-12 col-md-3 mb-3">
            <div class="ov-card" style="display:flex;flex-direction:column;align-items:stretch;height:100%;">
              <a href="<?= base_url('MasoolMusaid/mumineendirectory?filter=sub_sector&value='.rawurlencode($row['SubSector']??'')) ?>" style="display:flex;align-items:center;gap:10px;flex:1;">
                <div class="ov-icon" style="background:#eaf4ee;color:#1a6645;"><i class="fa fa-map-marker"></i></div>
                <div class="ov-body" style="width:100%;">
                  <span class="ov-label"><?= htmlspecialchars(($row['Sector']??'') . ' ' . ($row['SubSector']??'')) ?></span>
                  <span class="ov-value" style="font-size:.95rem;">HOF <?= $hof ?> &nbsp;·&nbsp; FM <?= $fm ?></span>
                </div>
              </a>
            </div>
          </div>
          <?php endforeach; } ?>
        </div>
      </div>

      <!-- Marital Stats -->
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon" style="background:#fff0f6;color:#9d174d;"><i class="fa fa-heart"></i></span> Marital Stats</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupMarital" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupMarital">
        <div class="row mb-3">
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=Active&marital_status=Single&min=21&max=40') ?>">
              <div class="ov-card"><div class="ov-icon" style="background:var(--gold-muted);color:var(--gold);"><i class="fa fa-heart"></i></div><div class="ov-body"><span class="ov-label">Single (21-40)</span><span class="ov-value"><?= isset($stats['singles_21_40'])?(int)$stats['singles_21_40']:0 ?></span></div></div>
            </a>
          </div>
          <?php
          $ms = isset($marital_status_counts)?$marital_status_counts:[];
          foreach($ms as $label=>$count):
            $ll=strtolower(trim($label));
            if($ll==='unknown'||$ll===''||$ll==='single') continue;
            $iconBg='#f5f5f7';$iconColor='#6b7280';$icon='fa-info-circle';
            if(str_contains($ll,'married'))  {$icon='fa-user';$iconBg='#fff0f6';$iconColor='#9d174d';}
            elseif(str_contains($ll,'engag'))  {$icon='fa-star';$iconBg='#fffbeb';$iconColor='#b45309';}
            elseif(str_contains($ll,'divorc')) {$icon='fa-user';$iconBg='#fef2f2';$iconColor='#b91c1c';}
            elseif(str_contains($ll,'widow'))  {$icon='fa-user-secret';$iconBg='#ecfeff';$iconColor='#0891b2';}
          ?>
          <div class="col-6 col-md-3 mb-3">
            <a href="<?= base_url('MasoolMusaid/mumineendirectory?status=Active&marital_status='.rawurlencode($label)) ?>">
              <div class="ov-card"><div class="ov-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;"><i class="fa <?= $icon ?>"></i></div><div class="ov-body"><span class="ov-label"><?= htmlspecialchars($label) ?></span><span class="ov-value"><?= (int)$count ?></span></div></div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Weekly Signup Performance -->
      <?php
      $sw_items = isset($weekly_signup_avg['items']) ? $weekly_signup_avg['items'] : [];
      if (!empty($sw_items)): ?>
      <div class="section-hd mt-1">
        <h3 class="section-hd-title"><span class="hd-icon"><i class="fa fa-line-chart"></i></span> Weekly Signup Performance</h3>
        <button class="toggle-btn" data-toggle="collapse" data-target="#collapseGroupWeekly" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseGroupWeekly">
        <div class="row mb-2">
          <?php foreach ($sw_items as $sr): ?>
          <div class="col-6 col-md-3 mb-3">
            <div class="mini-card">
              <span class="mini-val"><?= round((float)($sr['avg']??0), 1) ?> <small style="font-size:.65rem;color:var(--text-3);">/ day</small></span>
              <span class="mini-lbl"><?= htmlspecialchars(($sr['sector']??'') . ' ' . ($sr['sub_sector']??'')) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div><!-- /surf Overview -->

    <!-- ── RSVP for Next Miqaat ── -->
    <div class="surf" id="miqaat-rsvp-block" data-initial-index="<?= isset($initial_index) ? $initial_index : 0 ?>" style="position:relative;">
      <div class="sec-hd">
        <h4 class="sec-hd-title">
          <span class="hd-icon" style="background:#f5e9c0;color:#b8860b;"><i class="fa fa-calendar-check-o"></i></span>
          RSVP for Next Miqaat
        </h4>
        <div class="d-flex align-items-center">
          <a href="<?= base_url('MasoolMusaid/rsvp_list') ?>" id="miqaat-view-details" class="btn btn-sm btn-primary text-white mr-2" style="white-space:nowrap;border-radius:8px;font-size:.76rem;font-weight:700;">View details</a>
          <button class="toggle-btn" type="button" data-toggle="collapse" data-target="#collapseRsvpMM" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
        </div>
      </div>
      <div class="collapse show" id="collapseRsvpMM">
        <div class="d-flex align-items-center justify-content-between mt-2 mb-1" style="gap:10px;">
          <div class="d-flex align-items-center justify-content-center" style="gap:10px;width:100%;">
            <button type="button" class="miqaat-nav-btn prev"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;">
              &lsaquo;
            </button>
            <div id="miqaat-current-title" style="font-weight:700;color:var(--gold);font-size:.95rem;text-align:center;min-width:160px;"></div>
            <button type="button" class="miqaat-nav-btn next"
              style="width:34px;height:34px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text-2);font-size:1.2rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;">
              &rsaquo;
            </button>
          </div>
        </div>

        <div class="row text-center mb-2 mt-2">
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillAttendCard" class="open-miqaat-modal" data-type="rsvp" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="willAttendCount">0</div>
                <div class="small text-muted" id="willAttendGuest" style="min-height:16px;font-size:.72rem;"></div>
                <div class="stats-label">Will attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatWillNotAttendCard" class="open-miqaat-modal" data-type="no" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="willNotAttendCount">0</div>
                <div class="stats-label">Will not attend</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatNotSubmittedCard" class="open-miqaat-modal" data-type="not_submitted" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="stats-value" id="rsvpNotSubmittedCount">0</div>
                <div class="stats-label">RSVP not submitted</div>
              </div>
            </a>
          </div>
        </div>

        <div class="row text-center mb-3" id="miqaatGuestBreakdown">
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestGentsCard" class="open-miqaat-modal" data-type="gents" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Gents</div>
                <div class="stats-value" id="guestGentsCount">0</div>
                <div class="small text-muted" id="guestGentsBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestLadiesCard" class="open-miqaat-modal" data-type="ladies" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Ladies</div>
                <div class="stats-value" id="guestLadiesCount">0</div>
                <div class="small text-muted" id="guestLadiesBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
          <div class="col-12 col-md-4 mb-2">
            <a href="#" id="miqaatGuestChildrenCard" class="open-miqaat-modal" data-type="children" data-miqaat-id="" style="text-decoration:none;color:inherit;display:block;">
              <div class="mini-card">
                <div class="small text-muted" style="font-size:.72rem;">Children</div>
                <div class="stats-value" id="guestChildrenCount">0</div>
                <div class="small text-muted" id="guestChildrenBreakdown" style="font-size:.7rem;">Members: 0 | Guests: 0</div>
              </div>
            </a>
          </div>
        </div>

        <div id="miqaatMessage" style="display:none;margin-top:6px;text-align:center;color:var(--text-2);font-size:.85rem;"></div>
        <div id="miqaatLoading"><div class="miqaat-spinner"></div></div>
      </div>
    </div>

    <!-- ── FMB Calendar Overview ── -->
    <?php if (!empty($year_daytype_stats)): ?>
    <div class="surf">
      <div class="sec-hd">
        <h4 class="sec-hd-title">
          <span class="hd-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="fa fa-calendar"></i></span>
          FMB Calendar Overview (Hijri <?= htmlspecialchars($year_daytype_stats['hijri_year'] ?? '') ?>)
        </h4>
        <button class="toggle-btn" type="button" data-toggle="collapse" data-target="#collapseCalendarMM" aria-expanded="true"><i class="fa fa-chevron-down"></i></button>
      </div>
      <div class="collapse show" id="collapseCalendarMM">
        <div class="row">
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['miqaat_days'] ?? 0) ?></div>
              <div class="stats-label">Miqaat Days</div>
            </div>
          </a>
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['thaali_days'] ?? 0) ?></div>
              <div class="stats-label">Thaali Days</div>
            </div>
          </a>
          <a href="<?= base_url('common/fmbcalendar?from=masoolmusaid') ?>" class="col-12 col-md-4 mb-2" style="text-decoration:none;">
            <div class="mini-card">
              <div class="stats-value"><?= (int)($year_daytype_stats['holiday_days'] ?? 0) ?></div>
              <div class="stats-label">Holidays</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- /container -->
</div><!-- /#mmApp -->

<!-- ── All JS preserved exactly ── -->
<script>
(function(){
  // User scope
  const ALLOWED_SECTORS=['BURHANI','MOHAMMEDI','NAJMI','SAIFEE','TAHERI'];
  const ALLOWED_SUBS=['A','B','C'];
  function parseUserScope(userName){if(!userName)return null;var name=userName.trim().toUpperCase();var last=name.slice(-1);var base=name.slice(0,-1);if(ALLOWED_SUBS.includes(last)&&ALLOWED_SECTORS.includes(base))return{sector:base,sub:last};if(ALLOWED_SECTORS.includes(name))return{sector:name,sub:null};return null;}
  const USER_SCOPE=parseUserScope(window.USER_NAME||'');

  // Mobile formatter
  function renderMobile(raw){if(!raw)return '';var digits=String(raw).replace(/\D/g,'');if(digits.startsWith('91')&&digits.length===12)return '<a href="tel:+'+digits+'" style="color:var(--gold);text-decoration:none;">+'+digits+'</a>';return '<span class="copy-mobile" data-mobile="'+raw+'" style="cursor:pointer;color:var(--gold);">'+raw+'</span>';}

  // Copy handler
  document.addEventListener('click',function(e){var el=e.target.closest('.copy-mobile');if(!el)return;var num=el.dataset.mobile;navigator.clipboard.writeText(num).then(function(){var old=el.innerText;el.innerText='Copied ✔';setTimeout(function(){el.innerText=old;},1200);});});

  // Modal
  $(document).on('click','.open-hof-modal',function(e){
    e.preventDefault();
    $('#miqaatPopupMeta').hide();
    var type=$(this).data('modal-type');var y=$(this).data('hijri-year');var m=$(this).data('hijri-month');
    if(!y||!m)return;
    var url=window.location.pathname+'?hijri_year='+y+'&hijri_month='+m+'&format=json';
    $('#hofListContainer').html('');$('#hofListLoading').show();
    $('#hofListLabel').text(type==='signed'?'Families Signed Up This Month':'Families With No Signup This Month');
    $('#hofListModal').modal('show');
    fetch(url,{credentials:'same-origin'}).then(function(r){return r.json();}).then(function(data){
      var rows=type==='signed'?(data?.monthly_stats?.signed_hof_list||[]):(data?.monthly_stats?.no_thaali_list||[]);
      if(USER_SCOPE){rows=rows.filter(function(r){var s=String(r.Sector||'').toUpperCase();var sub=String(r.Sub_Sector||'').toUpperCase();if(s!==USER_SCOPE.sector)return false;if(USER_SCOPE.sub===null)return ALLOWED_SUBS.includes(sub);return sub===USER_SCOPE.sub;});}
      $('#hofListLoading').hide();
      if(!rows.length){$('#hofListContainer').html('<div class="text-muted text-center py-3">No records found</div>');return;}
      var html='<table class="table table-sm table-striped"><thead><tr><th>ITS</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
      rows.forEach(function(r){html+='<tr><td>'+( r.ITS_ID||'')+'</td><td>'+(r.Full_Name||'')+'</td><td>'+(r.Sector||'')+'</td><td>'+(r.Sub_Sector||'')+'</td><td>'+renderMobile(r.Mobile||r.RFM_Mobile)+'</td></tr>';});
      html+='</tbody></table>';$('#hofListContainer').html(html);
    }).catch(function(){$('#hofListLoading').hide();$('#hofListContainer').html('<div class="text-danger">Failed to load data</div>');});
  });
 
   // Hijri switcher
   function buildUrl(base,y,m){try{var u=new URL(base,window.location.origin);u.searchParams.set('hijri_year',y);u.searchParams.set('hijri_month',m);u.searchParams.set('ajax','1');return u.toString();}catch(e){return base+'?hijri_year='+y+'&hijri_month='+m+'&ajax=1';}}
   function loadMonth(year,month,pushState){
     var url=buildUrl(window.location.pathname,year,month);
     document.getElementById('monthLoader').style.display='block';
     fetch(url,{credentials:'same-origin'}).then(function(r){return r.text();}).then(function(html){
       document.getElementById('monthLoader').style.display='none';
       var doc=new DOMParser().parseFromString(html,'text/html');
       var newBlock=doc.querySelector('#thaali-month-block');var curBlock=document.querySelector('#thaali-month-block');if(newBlock&&curBlock)curBlock.replaceWith(newBlock);
       var newTitle=doc.querySelector('#hijri-current-title');var curTitle=document.getElementById('hijri-current-title');if(newTitle&&curTitle)curTitle.innerHTML=newTitle.innerHTML;
       var newBtns=doc.querySelectorAll('.hijri-nav-btn');var curBtns=document.querySelectorAll('.hijri-nav-btn');
       newBtns.forEach(function(b,i){if(!curBtns[i])return;curBtns[i].dataset.hijriYear=b.dataset.hijriYear||'';curBtns[i].dataset.hijriMonth=b.dataset.hijriMonth||'';curBtns[i].classList.toggle('disabled',b.classList.contains('disabled'));});
       if(pushState)history.pushState({year:year,month:month},'','?hijri_year='+year+'&hijri_month='+month);
     }).catch(function(){document.getElementById('monthLoader').style.display='none';});
   }
   document.addEventListener('click',function(e){var btn=e.target.closest('.hijri-nav-btn');if(!btn||btn.classList.contains('disabled'))return;e.preventDefault();loadMonth(btn.dataset.hijriYear,btn.dataset.hijriMonth,true);});
   window.addEventListener('popstate',function(ev){if(ev.state&&ev.state.year&&ev.state.month)loadMonth(ev.state.year,ev.state.month,false);});
 })();
 
 // Disable back button
 history.pushState(null, null, location.href);
 window.onpopstate = function(){ history.pushState(null, null, location.href); };
 
 $(function(){
   /* Section header click-to-collapse */
   $(document).on('click', '#mmApp .section-hd', function (e) {
     if ($(e.target).closest('button, a').length) return;
     const target = $(this).find('.toggle-btn').data('target');
     if (target) $(target).collapse('toggle');
   });
 
   /* Member search */
   let msTimer;
   $('#mswInput').on('input', function () {
     const q = this.value.trim();
     $('#mswClear').toggleClass('visible', q.length > 0);
     if (!q) { $('#mswDropdown').removeClass('open').empty(); return; }
     if (q.length < 2) return;
     clearTimeout(msTimer);
     msTimer = setTimeout(() => {
       $('#mswSpinner').addClass('active');
       $.ajax({
         url: '<?= base_url("MasoolMusaid/search_members_json") ?>',
         data: { query: q }, dataType: 'json',
         success(data) {
           $('#mswSpinner').removeClass('active');
           const $drop = $('#mswDropdown').empty().addClass('open');
           if (data && data.length) {
             data.forEach(item => {
               const init = (item.full_name||'M')[0].toUpperCase();
               const fem  = (item.gender||'').toLowerCase()==='female';
               $drop.append(`<div class="msw-item" onclick="location.href='<?= base_url("MasoolMusaid/viewmember/") ?>${item.its_id}'"><div class="msw-av${fem?' f':''}">${init}</div><div><div class="msw-name">${item.full_name}</div><div class="msw-meta">${item.sector||'No Sector'} | HOF: ${item.hof_id||'—'}</div></div><div class="msw-its">${item.its_id}</div></div>`);
             });
           } else {
             $drop.append('<div class="msw-empty">No members found</div>');
           }
         },
         error() { $('#mswSpinner').removeClass('active'); }
       });
     }, 280);
   });
   $('#mswClear').on('click', () => { $('#mswInput').val('').trigger('input'); });
   $(document).on('click', e => { if (!$(e.target).closest('#member-search-block').length) $('#mswDropdown').removeClass('open'); });
 
   /* ══════════════════════════════════════════════════════════
      RSVP miqaat block
   ══════════════════════════════════════════════════════════ */
   (function(){
     var upcoming = <?= json_encode(array_values($upcoming_miqaats ?? [])) ?> || [];
     var initialRsvp = <?= json_encode($miqaat_rsvp ?? new stdClass()) ?>;
     var index = parseInt('<?= isset($initial_index) ? $initial_index : 0 ?>', 10) || 0;
 
     function _esc(s){ return String(s===null||s===undefined?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
 
     function seedFromPhp(){
       var m = initialRsvp;
       if(!m) return;
       updateGuestCountsFromPayload(m);
       var wna = document.getElementById('willNotAttendCount');
       var ns  = document.getElementById('rsvpNotSubmittedCount');
       if(wna) wna.textContent = (m.will_not_attend||0);
       if(ns)  ns.textContent  = (m.rsvp_not_submitted||0);
     }
 
     function setMiqaatIdOnCards(mid){
       ['miqaatWillAttendCard','miqaatWillNotAttendCard','miqaatNotSubmittedCard',
        'miqaatGuestGentsCard','miqaatGuestLadiesCard','miqaatGuestChildrenCard'].forEach(function(id){
         var el = document.getElementById(id);
         if(el) el.setAttribute('data-miqaat-id', mid||'');
       });
     }
 
     function renderTitle(mi){
       var el = document.getElementById('miqaat-current-title');
       if(!el) return;
       if(!mi){ el.textContent='No miqaat found'; return; }
       var name = mi.name || ('Miqaat '+(mi.id||mi.miqaat_id||''));
       var dateLabel = mi.hijri_label || mi.date || '';
       el.innerHTML = '<div style="font-weight:700;color:var(--gold);font-size:.95rem;">'+_esc(name)+'</div>'
                    + (dateLabel?'<div style="font-size:.78rem;color:var(--text-3);margin-top:2px;">'+_esc(dateLabel)+'</div>':'');
     }
 
     function setViewDetails(mid){
       var btn = document.getElementById('miqaat-view-details');
       if(btn) btn.href = '<?= base_url("MasoolMusaid/rsvp_list") ?>' + (mid ? '?miqaat_id='+encodeURIComponent(mid) : '');
     }
 
     function showLoading(on){
       var el = document.getElementById('miqaatLoading');
       if(el) el.classList.toggle('active', on);
     }
 
     function renderFor(i){
       if(!upcoming||!upcoming.length){ renderTitle(null); return; }
       i = Math.max(0, Math.min(i, upcoming.length-1));
       index = i;
       var mi = upcoming[index];
       var miqId = String(mi.id||mi.miqaat_id||'');
       renderTitle(mi);
       setViewDetails(miqId);
       setMiqaatIdOnCards(miqId);
 
       var prevBtn = document.querySelector('.miqaat-nav-btn.prev');
       var nextBtn = document.querySelector('.miqaat-nav-btn.next');
       if(prevBtn) prevBtn.style.opacity = (index <= 0) ? '.35' : '1';
       if(nextBtn) nextBtn.style.opacity = (index >= upcoming.length-1) ? '.35' : '1';
 
       if(!miqId) return;
       showLoading(true);
 
       var url = window.location.pathname;
       try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_rsvp','1'); u.searchParams.set('miqaat_id',miqId); url=u.toString(); }
       catch(err){ url+='?format=json&miqaat_rsvp=1&miqaat_id='+encodeURIComponent(miqId); }
 
       fetch(url,{credentials:'same-origin'})
         .then(function(r){ return r.json(); })
         .then(function(data){
           showLoading(false);
           if(!data||!data.miqaat_rsvp) return;
           var m = data.miqaat_rsvp;
           updateGuestCountsFromPayload(m);
           var wna = document.getElementById('willNotAttendCount');
           var ns  = document.getElementById('rsvpNotSubmittedCount');
           if(wna) wna.textContent = (m.will_not_attend||0);
           if(ns)  ns.textContent  = (m.rsvp_not_submitted||0);
 
           var cUrl = '<?= base_url('MasoolMusaid/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(miqId);
           fetch(cUrl,{credentials:'same-origin'}).then(function(r){ return r.json(); }).then(function(cdata){
             if(cdata&&cdata.success){
               var ms=(m&&m.member_summary&&m.member_summary.total)?m.member_summary.total:0;
               var gs=(m&&m.guest_summary&&m.guest_summary.total)?m.guest_summary.total:0;
               var combined=(ms+gs)||(cdata.will_attend||0);
               var waEl = document.getElementById('willAttendCount');
               var wgEl = document.getElementById('willAttendGuest');
               if(waEl) waEl.textContent = combined;
               if(wgEl) wgEl.textContent = (gs>0?('+'+gs+' guests'):'');
               var wnaEl = document.getElementById('willNotAttendCount');
               var nsEl  = document.getElementById('rsvpNotSubmittedCount');
               if(wnaEl) wnaEl.textContent = (cdata.will_not_attend||0);
               if(nsEl)  nsEl.textContent  = (cdata.rsvp_not_submitted||0);
             }
           }).catch(function(e){ console.warn('RSVP counts fetch failed',e); });
         })
         .catch(function(e){ showLoading(false); console.error('RSVP fetch failed',e); });
     }
 
     function updateGuestCountsFromPayload(m){
       try{
         var gs = (m&&m.guest_summary)  ? m.guest_summary  : {gents:0,ladies:0,children:0,total:0};
         var ms = (m&&m.member_summary) ? m.member_summary : {gents:0,ladies:0,children:0,total:0};
         var cs = (m&&m.combined_summary)? m.combined_summary : {
           gents:   (ms.gents||0)+(gs.gents||0),
           ladies:  (ms.ladies||0)+(gs.ladies||0),
           children:(ms.children||0)+(gs.children||0),
           total:   (ms.total||0)+(gs.total||0)
         };
         var els = {guestGentsCount:cs.gents, guestLadiesCount:cs.ladies, guestChildrenCount:cs.children};
         Object.keys(els).forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=els[id]||0; });
         var breaks = {
           guestGentsBreakdown:   'Members: '+(ms.gents||0)+' | Guests: '+(gs.gents||0),
           guestLadiesBreakdown:  'Members: '+(ms.ladies||0)+' | Guests: '+(gs.ladies||0),
           guestChildrenBreakdown:'Members: '+(ms.children||0)+' | Guests: '+(gs.children||0)
         };
         Object.keys(breaks).forEach(function(id){ var el=document.getElementById(id); if(el) el.textContent=breaks[id]; });
         var waG = document.getElementById('willAttendGuest');
         if(waG) waG.textContent = (gs.total>0?('+'+gs.total+' guests'):'');
       }catch(e){ console.warn('updateGuestCountsFromPayload failed',e); }
     }
 
     document.addEventListener('click', function(e){
       var t = e.target.closest && e.target.closest('.miqaat-nav-btn');
       if(!t) return;
       e.preventDefault();
       if(t.classList.contains('prev')){
         if(index > 0){ renderFor(index-1); }
         else {
           var first = (upcoming&&upcoming.length) ? upcoming[0] : null;
           var beforeDate = first?(first.date||''):'';
           if(!beforeDate) return;
           var url = window.location.pathname;
           try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_prev','1'); u.searchParams.set('before_date',beforeDate); url=u.toString(); }
           catch(err){ url+='?format=json&miqaat_prev=1&before_date='+encodeURIComponent(beforeDate); }
           showLoading(true); t.style.pointerEvents='none';
           fetch(url,{credentials:'same-origin'}).then(function(r){ return r.json(); })
             .then(function(data){
               showLoading(false); t.style.pointerEvents='';
               if(!data||!data.success||!data.miqaat){
                 var msgEl=document.getElementById('miqaatMessage');
                 if(msgEl){msgEl.textContent='No earlier miqaat found';msgEl.style.display='block';clearTimeout(msgEl._t);msgEl._t=setTimeout(function(){msgEl.style.display='none';},3000);}
                 return;
               }
               upcoming.unshift(data.miqaat); renderFor(0);
             }).catch(function(){ showLoading(false); t.style.pointerEvents=''; });
         }
       } else {
         if(index < upcoming.length-1){ renderFor(index+1); }
       }
     });
 
     document.addEventListener('click', function(e){
       var a = e.target.closest && e.target.closest('.open-miqaat-modal');
       if(!a) return;
       e.preventDefault();
       var dtype = a.getAttribute('data-type')||'rsvp';
       var mid   = a.getAttribute('data-miqaat-id') || (upcoming&&upcoming[index]&&(upcoming[index].id||upcoming[index].miqaat_id||''))||'';
       if(!mid) return;
 
       function _escH(s){ return String(s===null||s===undefined?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
       function renderList(title,rows){
         var html='<div class="d-flex justify-content-between align-items-center mb-2"><strong>'+_escH(title)+'</strong>';
         try{rows=(rows||[]).filter(function(r){var s=((r&&(r.Sector||r.sector))||'')+'';var ss=((r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'')+'';return!((s.trim()===''&&ss.trim()===''));});}catch(e){}
         html+='<span class="text-muted">Count: '+(rows?rows.length:0)+'</span></div>';
         if(!rows||!rows.length)return html+'<div class="text-muted">No records found.</div>';
         try{rows.sort(function(a,b){var sa=((a.Sector||a.sector||'')+'').toLowerCase();var sb=((b.Sector||b.sector||'')+'').toLowerCase();return sa<sb?-1:sa>sb?1:0;});}catch(e){}
         html+='<table class="table table-sm table-striped"><thead><tr><th>ID</th><th>Name</th><th>Sector</th><th>Sub Sector</th><th>Mobile</th></tr></thead><tbody>';
         rows.forEach(function(r){
           var id=(r&&(r.ITS_ID||r.hof_id||r.ITS))||'';
           var name=(r&&(r.Full_Name||r.name))||'';
           var s=(r&&(r.Sector||r.sector))||'';
           var ss=(r&&(r.Sub_Sector||r.sub_sector||r.SubSector))||'';
           var mobile=(r&&(r.mobile||r.Mobile||r.RFM_Mobile))||'';
           html+='<tr><td>'+_escH(id)+'</td><td>'+_escH(name)+'</td><td>'+_escH(s)+'</td><td>'+_escH(ss)+'</td><td>'+renderMobile(mobile)+'</td></tr>';
         });
         return html+'</tbody></table>';
       }
 
       var lblMap={rsvp:"RSVP'd for Miqaat",no:"Will not attend",not_submitted:"RSVP not submitted",gents:"Gents",ladies:"Ladies",children:"Children"};
       $('#hofListLabel').text(lblMap[dtype]||"Members");
       $('#hofListContainer').html('');$('#hofListLoading').show();
 
       var miObj=(upcoming||[]).find(function(x){return String(x.id||x.miqaat_id||'')===String(mid);})||(upcoming[index]||{});
       $('#miqaatPopupMeta').html('<div style="font-weight:700;">'+_escH(miObj.name||'Miqaat')+'</div>'+(miObj.hijri_label||miObj.date?'<div class="text-muted">'+_escH(miObj.hijri_label||miObj.date)+'</div>':'')+'<div style="margin-top:8px;"><span class="badge badge-success mr-2" id="popupWillAttend">Will attend: 0</span><span class="badge badge-danger mr-2" id="popupWillNotAttend">Will not attend: 0</span><span class="badge badge-secondary" id="popupNotSubmitted">Not submitted: 0</span></div>').show();
 
       $('#hofListModal').modal('show');
 
       var url=window.location.pathname;
       try{ var u=new URL(url,window.location.origin); u.searchParams.set('format','json'); u.searchParams.set('miqaat_rsvp','1'); u.searchParams.set('miqaat_id',mid); url=u.toString(); }
       catch(err){ url+='?format=json&miqaat_rsvp=1&miqaat_id='+encodeURIComponent(mid); }
 
       fetch(url,{credentials:'same-origin'}).then(function(r){ return r.json(); })
         .then(function(data){
           $('#hofListLoading').hide();
           if(!data||!data.miqaat_rsvp){$('#hofListContainer').html('<div class="text-muted">No data found.</div>');return;}
           var m=data.miqaat_rsvp;
           var rows=[];var titleTxt='';
           if(dtype==='rsvp'){rows=(m.rsvp_member_list&&m.rsvp_member_list.length)?m.rsvp_member_list:(m.rsvp_list||[]);titleTxt="RSVP'd Members";}
           else if(dtype==='no'){rows=(m.not_rsvp_member_list&&m.not_rsvp_member_list.length)?m.not_rsvp_member_list:(m.not_rsvp_list||[]);titleTxt="Will not attend";}
           else if(dtype==='not_submitted'){rows=(m.not_submitted_member_list&&m.not_submitted_member_list.length)?m.not_submitted_member_list:[];titleTxt="Not Submitted";}
           else if(dtype==='gents'){rows=(m.rsvp_male_member_list&&m.rsvp_male_member_list.length)?m.rsvp_male_member_list:[];titleTxt="Gents";}
           else if(dtype==='ladies'){rows=(m.rsvp_female_member_list&&m.rsvp_female_member_list.length)?m.rsvp_female_member_list:[];titleTxt="Ladies";}
           else if(dtype==='children'){rows=(m.rsvp_children_member_list&&m.rsvp_children_member_list.length)?m.rsvp_children_member_list:[];titleTxt="Children";}
           $('#hofListContainer').html(renderList(titleTxt,rows));
 
           var cUrl='<?= base_url('MasoolMusaid/miqaat_rsvp_user_counts') ?>?miqaat_id='+encodeURIComponent(mid);
           fetch(cUrl,{credentials:'same-origin'}).then(function(r){return r.json();}).then(function(cdata){
             if(cdata&&cdata.success){
               var pw=document.getElementById('popupWillAttend');var pwn=document.getElementById('popupWillNotAttend');var pns=document.getElementById('popupNotSubmitted');
               if(pw)pw.textContent='Will attend: '+(cdata.will_attend||0);
               if(pwn)pwn.textContent='Will not attend: '+(cdata.will_not_attend||0);
               if(pns)pns.textContent='Not submitted: '+(cdata.rsvp_not_submitted||0);
             }
           }).catch(function(e){console.warn('popup counts failed',e);});
         })
         .catch(function(){ $('#hofListLoading').hide(); $('#hofListContainer').html('<div class="text-danger">Failed to load list.</div>'); });
     });
 
     if(document.readyState==='loading'){
       document.addEventListener('DOMContentLoaded',function(){ seedFromPhp(); renderFor(index); });
     } else {
       seedFromPhp(); renderFor(index);
     }
   })();
 });
</script>