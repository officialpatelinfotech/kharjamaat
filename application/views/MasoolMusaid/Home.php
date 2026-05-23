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

/* ── Responsive tweaks ── */
@media (max-width: 576px) {
  #mmApp .mm-title { font-size: 1.2rem; }
  #mmApp .mm-header { padding: 16px 18px; }
  #mmApp .stats-value { font-size: 1.3rem; }
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
      <div class="mm-badge">
        <span class="mm-badge-icon"><i class="fa fa-users"></i></span>
        <span class="mm-badge-lbl">Dashboard</span>
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
</script>