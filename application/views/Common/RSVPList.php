<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
  --purple:       #6d28d9;
  --purple-bg:    #f5f3ff;
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --shadow:       0 4px 16px rgba(0,0,0,.07),0 1px 4px rgba(0,0,0,.04);
  --shadow-lg:    0 8px 32px rgba(0,0,0,.10),0 2px 8px rgba(0,0,0,.05);
  --radius:       16px;
  --radius-sm:    10px;
}

#rsvpApp,#rsvpApp *,#rsvpApp *::before,#rsvpApp *::after{box-sizing:border-box;}
#rsvpApp{
  font-family:'Plus Jakarta Sans',sans-serif;
  color:var(--text-1);background:var(--bg);
  min-height:100vh;padding-top:57px;
}

/* ── Full-width wrapper ── */
#rsvpApp .rsvp-wrap{padding:12px 14px 48px;}
@media(min-width:1400px){#rsvpApp .rsvp-wrap{padding:14px 20px 48px;}}

/* ── Compact header strip ── */
#rsvpApp .rsvp-header{
  background:linear-gradient(135deg,#78520a 0%,#b8860b 50%,#c9a227 100%);
  border-radius:14px;padding:11px 18px;margin-bottom:16px;
  display:flex;align-items:center;justify-content:space-between;gap:12px;
  position:relative;overflow:hidden;flex-wrap:wrap;
}
#rsvpApp .rsvp-header::before{
  content:'';position:absolute;inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='30'/%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events:none;
}
#rsvpApp .rsvp-header::after{
  content:'';position:absolute;right:-30px;top:-30px;
  width:140px;height:140px;
  background:radial-gradient(circle,rgba(255,255,255,.12) 0%,transparent 70%);
  pointer-events:none;
}
#rsvpApp .hdr-left{display:flex;align-items:center;gap:10px;position:relative;z-index:1;}
#rsvpApp .hdr-back{
  display:inline-flex;align-items:center;justify-content:center;
  width:32px;height:32px;border-radius:8px;
  background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);
  color:#fff;text-decoration:none;transition:background .15s;flex-shrink:0;
}
#rsvpApp .hdr-back:hover{background:rgba(255,255,255,.28);color:#fff;text-decoration:none;}
#rsvpApp .hdr-icon{
  width:32px;height:32px;border-radius:8px;
  background:rgba(255,255,255,.2);
  display:flex;align-items:center;justify-content:center;
  font-size:.9rem;color:#fff;flex-shrink:0;
}
#rsvpApp .hdr-text{}
#rsvpApp .hdr-eyebrow{font-size:.62rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.6);line-height:1;}
#rsvpApp .hdr-title{font-family:'Literata',Georgia,serif;font-size:1.1rem;font-weight:600;color:#fff;line-height:1.2;margin:0;}
#rsvpApp .hdr-right{display:flex;align-items:center;gap:8px;position:relative;z-index:1;flex-shrink:0;}
#rsvpApp .hdr-badge{
  background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);
  border-radius:9px;padding:5px 12px;display:flex;align-items:center;gap:6px;
  font-size:.75rem;font-weight:700;color:#fff;white-space:nowrap;
}
#rsvpApp .hdr-badge .badge-val{font-size:1rem;font-weight:800;}

/* ── Filter card ── */
#rsvpApp .filter-card{
  background:var(--surface);border:1.5px solid var(--border);
  border-radius:var(--radius);padding:14px 16px;margin-bottom:16px;
  box-shadow:var(--shadow-sm);
}
#rsvpApp .filter-inner{display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
#rsvpApp .fi{display:flex;flex-direction:column;gap:4px;flex:1 1 130px;min-width:0;}
#rsvpApp .fi label{font-size:.67rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.3px;}
#rsvpApp .fi select,
#rsvpApp .fi input[type="date"],
#rsvpApp .fi input[type="text"]{
  height:36px;padding:0 10px;
  border:1.5px solid var(--border);border-radius:8px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;
  color:var(--text-1);background:var(--surface-2);outline:none;
  transition:border-color .15s,box-shadow .15s;width:100%;
}
#rsvpApp .fi select:focus,
#rsvpApp .fi input:focus{
  border-color:var(--gold);box-shadow:0 0 0 3px rgba(184,134,11,.12);background:var(--surface);
}
#rsvpApp .fi.search-fi{flex:1 1 200px;}
#rsvpApp .search-wrap{position:relative;}
#rsvpApp .search-wrap input{padding-right:32px;}
#rsvpApp .search-clear{
  position:absolute;right:8px;top:50%;transform:translateY(-50%);
  background:none;border:none;color:var(--text-3);cursor:pointer;font-size:1rem;line-height:1;padding:0;
}
#rsvpApp .search-clear:hover{color:var(--red);}
#rsvpApp .filter-actions{display:flex;gap:8px;align-items:flex-end;flex-shrink:0;}
#rsvpApp .btn-apply{
  display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;
  border-radius:8px;border:none;
  background:linear-gradient(135deg,#b8860b,#c9a227);
  color:#fff;font-size:.82rem;font-weight:800;cursor:pointer;
  box-shadow:0 2px 8px rgba(184,134,11,.25);white-space:nowrap;
}
#rsvpApp .btn-reset{
  display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 14px;
  border-radius:8px;border:1.5px solid var(--border);
  background:var(--surface);color:var(--text-2);font-size:.82rem;font-weight:700;cursor:pointer;
  transition:all .15s;white-space:nowrap;
}
#rsvpApp .btn-reset:hover{border-color:var(--gold);background:var(--gold-muted);color:var(--gold);}

/* ── Cards grid ── */
#rsvpApp .cards-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:14px;
}
@media(min-width:1200px){#rsvpApp .cards-grid{grid-template-columns:repeat(4,1fr);}}
@media(max-width:600px){#rsvpApp .cards-grid{grid-template-columns:1fr;}}

/* ── Miqaat card ── */
#rsvpApp .mq-card{
  background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);
  box-shadow:var(--shadow-sm);display:flex;flex-direction:column;
  overflow:hidden;transition:transform .15s,box-shadow .15s,border-color .15s;
  position:relative;
}
#rsvpApp .mq-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-lg);border-color:var(--gold);}
#rsvpApp .mq-card::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--gold),var(--gold-light));
  transform:scaleX(0);transform-origin:left;transition:transform .2s;
}
#rsvpApp .mq-card:hover::after{transform:scaleX(1);}

/* Card top bar with type colour */
#rsvpApp .mq-card-bar{height:4px;width:100%;flex-shrink:0;}
#rsvpApp .mq-card-bar.general   {background:linear-gradient(90deg,#1a6645,#34d399);}
#rsvpApp .mq-card-bar.ashara    {background:linear-gradient(90deg,#be123c,#fb7185);}
#rsvpApp .mq-card-bar.ladies    {background:linear-gradient(90deg,#9d174d,#f472b6);}
#rsvpApp .mq-card-bar.shehrullah{background:linear-gradient(90deg,#0369a1,#38bdf8);}
#rsvpApp .mq-card-bar.default   {background:linear-gradient(90deg,var(--gold),var(--gold-light));}

#rsvpApp .mq-card-body{padding:14px 16px;flex:1;display:flex;flex-direction:column;gap:8px;}

/* Card header row */
#rsvpApp .mq-card-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px;}
#rsvpApp .mq-name{font-family:'Literata',Georgia,serif;font-size:.97rem;font-weight:600;color:var(--text-1);line-height:1.3;flex:1;}
#rsvpApp .mq-type-tag{
  display:inline-flex;align-items:center;
  padding:3px 9px;border-radius:20px;font-size:.65rem;font-weight:700;border:1px solid transparent;
  white-space:nowrap;flex-shrink:0;
}
#rsvpApp .mq-type-tag.general   {background:var(--green-bg);color:var(--green);border-color:#86efac;}
#rsvpApp .mq-type-tag.ashara    {background:#fff1f2;color:#be123c;border-color:#fda4af;}
#rsvpApp .mq-type-tag.ladies    {background:#fdf2f8;color:#9d174d;border-color:#fbcfe8;}
#rsvpApp .mq-type-tag.shehrullah{background:var(--blue-bg);color:#0369a1;border-color:#7dd3fc;}
#rsvpApp .mq-type-tag.default   {background:var(--gold-muted);color:var(--gold);border-color:var(--gold-border);}

/* Info rows */
#rsvpApp .mq-info{display:flex;flex-direction:column;gap:5px;}
#rsvpApp .info-row{display:flex;align-items:center;gap:8px;font-size:.78rem;}
#rsvpApp .info-row .info-icon{width:22px;height:22px;border-radius:6px;background:var(--gold-muted);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:.65rem;flex-shrink:0;}
#rsvpApp .info-row .info-label{color:var(--text-3);font-weight:600;min-width:70px;flex-shrink:0;}
#rsvpApp .info-row .info-val{color:var(--text-1);font-weight:700;}

/* RSVP stats row */
#rsvpApp .rsvp-stat-row{
  display:flex;align-items:center;gap:8px;
  background:var(--surface-2);border:1px solid var(--border-light);
  border-radius:var(--radius-sm);padding:8px 12px;margin-top:2px;
}
#rsvpApp .rsvp-count{font-size:1.15rem;font-weight:800;line-height:1;}
#rsvpApp .rsvp-count.has{color:var(--green);}
#rsvpApp .rsvp-count.none{color:var(--red);}
#rsvpApp .rsvp-label{font-size:.7rem;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.3px;}
#rsvpApp .rsvp-bar-wrap{flex:1;background:var(--border);border-radius:4px;height:5px;overflow:hidden;min-width:40px;}
#rsvpApp .rsvp-bar{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--green),#34d399);transition:width .4s;}
#rsvpApp .rsvp-pct{font-size:.7rem;font-weight:700;color:var(--text-3);white-space:nowrap;}

/* Card footer */
#rsvpApp .mq-card-foot{
  padding:10px 16px;border-top:1px solid var(--border-light);
  display:flex;align-items:center;justify-content:flex-end;
  background:var(--surface-2);
}
#rsvpApp .btn-view{
  display:inline-flex;align-items:center;gap:6px;
  padding:7px 16px;border-radius:8px;font-size:.78rem;font-weight:800;
  background:linear-gradient(135deg,#b8860b,#c9a227);color:#fff;
  text-decoration:none;border:none;cursor:pointer;
  box-shadow:0 2px 8px rgba(184,134,11,.25);
  transition:opacity .15s,transform .1s;
}
#rsvpApp .btn-view:hover{opacity:.9;transform:translateY(-1px);color:#fff;text-decoration:none;}

/* ── Empty state ── */
#rsvpApp .empty-state{
  padding:60px 20px;text-align:center;color:var(--text-3);
  background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);
}
#rsvpApp .empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;color:var(--gold-light);}
#rsvpApp .empty-state p{font-size:.95rem;margin:0;}

/* ── Visible count pill ── */
#rsvpApp .visible-count{
  font-size:.75rem;font-weight:700;color:var(--text-3);margin-bottom:12px;
  display:flex;align-items:center;gap:6px;
}
#rsvpApp .visible-count span{
  background:var(--gold-muted);color:var(--gold);
  padding:2px 9px;border-radius:12px;font-weight:800;
}

/* ── Responsive ── */
@media(max-width:768px){
  #rsvpApp .filter-inner{flex-direction:column;}
  #rsvpApp .fi{flex:none;width:100%;}
  #rsvpApp .filter-actions{width:100%;justify-content:flex-end;}
  #rsvpApp .sum-card{flex:1 1 80px;padding:8px 10px;}
  #rsvpApp .sum-val{font-size:1.1rem;}
}
@media(max-width:480px){
  #rsvpApp .rsvp-header{flex-direction:column;align-items:flex-start;gap:6px;}
  #rsvpApp .hdr-badge{display:none;}
  #rsvpApp .mq-card-body{padding:12px 13px;}
}
</style>

<?php
/* ── Derive counts ── */
$totalMembersText = '';
if     (isset($total_members))                          $totalMembersText = (int)$total_members;
elseif (isset($members_count))                          $totalMembersText = (int)$members_count;
elseif (isset($member_count))                           $totalMembersText = (int)$member_count;
elseif (isset($members) && is_array($members))          $totalMembersText = count($members);
elseif (isset($all_members) && is_array($all_members))  $totalMembersText = count($all_members);

/* Sort ascending by date */
if (!empty($miqaats)) {
  usort($miqaats, function($a,$b){
    return (int)strtotime($a['miqaat_date']??'0') <=> (int)strtotime($b['miqaat_date']??'0');
  });
}

/* ── Summary aggregates ── */
$totalMiqaats  = !empty($miqaats) ? count($miqaats) : 0;
$totalRsvps    = 0;
if (!empty($rsvp_by_miqaat)) foreach($rsvp_by_miqaat as $cnt) $totalRsvps += (int)$cnt;
?>

<div id="rsvpApp">
<div class="rsvp-wrap">

  <!-- ── Compact Header ── -->
  <div class="rsvp-header">
    <div class="hdr-left">
      <a href="<?php echo $active_controller ? $active_controller : base_url(); ?>" class="hdr-back">
        <i class="fas fa-arrow-left"></i>
      </a>
      <div class="hdr-icon"><i class="fa-solid fa-calendar-check"></i></div>
      <div class="hdr-text">
        <div class="hdr-eyebrow">Reports</div>
        <h1 class="hdr-title">RSVP Report</h1>
      </div>
    </div>
    <div class="hdr-right">
      <?php if($totalMembersText !== ''): ?>
      <div class="hdr-badge">
        <i class="fa fa-users"></i>
        <span class="badge-val"><?php echo $totalMembersText; ?></span>
        <span style="font-size:.67rem;opacity:.8;">Members</span>
      </div>
      <?php endif; ?>
      <div class="hdr-badge">
        <i class="fa fa-calendar-alt"></i>
        <span class="badge-val" id="hdr-total-miqaats"><?php echo $totalMiqaats; ?></span>
        <span style="font-size:.67rem;opacity:.8;">Miqaats</span>
      </div>
    </div>
  </div>

  <!-- ── Filter card ── -->
  <div class="filter-card">
    <div class="filter-inner">
      <div class="fi">
        <label for="filter-range">Quick Range</label>
        <select id="filter-range">
          <option value="">All time</option>
          <option value="7">Last 7 days</option>
          <option value="30">Last 30 days</option>
          <option value="90">Last 90 days</option>
          <option value="365">Last 1 year</option>
        </select>
      </div>
      <div class="fi">
        <label for="filter-from">From</label>
        <input type="date" id="filter-from">
      </div>
      <div class="fi">
        <label for="filter-to">To</label>
        <input type="date" id="filter-to">
      </div>
      <div class="fi search-fi">
        <label for="filter-name">Search Miqaat</label>
        <div class="search-wrap">
          <input type="text" id="filter-name" placeholder="Type miqaat name…">
          <button id="filter-clear-name" class="search-clear" type="button" title="Clear search">×</button>
        </div>
      </div>
      <div class="filter-actions">
        <button id="filter-apply" class="btn-apply"><i class="fa fa-filter"></i> Apply</button>
        <button id="filter-reset" class="btn-reset"><i class="fa fa-rotate-right"></i> Reset</button>
      </div>
    </div>
  </div>


  <!-- ── Visible count ── -->
  <div class="visible-count">
    Showing <span id="visible-count-val"><?php echo $totalMiqaats; ?></span> miqaats
  </div>

  <!-- ── Cards grid ── -->
  <?php if(!empty($miqaats)): ?>
  <div class="cards-grid" id="miqaat-cards">
    <?php foreach($miqaats as $miqaat):
      $mId    = $miqaat['miqaat_id'] ?? '';
      $mName  = $miqaat['miqaat_name'] ?? '';
      $mType  = strtolower(trim(!empty($miqaat['miqaat_type']) ? $miqaat['miqaat_type'] : ($miqaat['type'] ?? '')));
      $mDate  = $miqaat['miqaat_date'] ?? '';
      $rsvpCt = isset($rsvp_by_miqaat[$mId]) ? (int)$rsvp_by_miqaat[$mId] : 0;
      $pct    = ($totalMembersText > 0) ? min(100, round($rsvpCt / $totalMembersText * 100)) : 0;
      $typeClass = in_array($mType,['general','ashara','ladies','shehrullah']) ? $mType : 'default';

      /* Hijri date */
      $hijriStr = '-';
      if (!empty($miqaat['hijri_date']) && !empty($miqaat['hijri_month_name'])) {
        $hp = explode('-', $miqaat['hijri_date']);
        $hijriStr = $hp[0].' '.$miqaat['hijri_month_name'].' '.(isset($hp[2])?$hp[2]:'');
      }
    ?>
    <div class="mq-card"
         data-date="<?php echo htmlspecialchars($mDate); ?>"
         data-name="<?php echo htmlspecialchars(strtolower($mName)); ?>">
      <div class="mq-card-bar <?php echo $typeClass; ?>"></div>
      <div class="mq-card-body">
        <!-- Header -->
        <div class="mq-card-head">
          <div class="mq-name"><?php echo htmlspecialchars($mName); ?></div>
          <?php if($mType): ?>
          <span class="mq-type-tag <?php echo $typeClass; ?>"><?php echo ucfirst($mType); ?></span>
          <?php endif; ?>
        </div>

        <!-- Info rows -->
        <div class="mq-info">
          <div class="info-row">
            <span class="info-icon"><i class="fa fa-calendar"></i></span>
            <span class="info-label">Eng Date</span>
            <span class="info-val"><?php echo !empty($mDate) ? date('d M Y', strtotime($mDate)) : '—'; ?></span>
          </div>
          <div class="info-row">
            <span class="info-icon"><i class="fa fa-moon"></i></span>
            <span class="info-label">Hijri</span>
            <span class="info-val"><?php echo htmlspecialchars($hijriStr); ?></span>
          </div>
          <?php if($totalMembersText !== ''): ?>
          <div class="info-row">
            <span class="info-icon"><i class="fa fa-users"></i></span>
            <span class="info-label">Members</span>
            <span class="info-val"><?php echo $totalMembersText; ?></span>
          </div>
          <?php endif; ?>
        </div>

        <!-- RSVP stat row -->
        <div class="rsvp-stat-row">
          <div>
            <div class="rsvp-count <?php echo $rsvpCt > 0 ? 'has' : 'none'; ?>"><?php echo $rsvpCt; ?></div>
            <div class="rsvp-label">RSVPs</div>
          </div>
          <?php if($totalMembersText > 0): ?>
          <div class="rsvp-bar-wrap">
            <div class="rsvp-bar" style="width:<?php echo $pct; ?>%;"></div>
          </div>
          <div class="rsvp-pct"><?php echo $pct; ?>%</div>
          <?php else: ?>
          <div style="flex:1;"></div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Card footer -->
      <div class="mq-card-foot">
        <a href="<?php echo base_url('common/rsvp_details/'.$mId); ?>" class="btn-view">
          View Details <i class="fa fa-arrow-right"></i>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div class="empty-state">
    <i class="fa-regular fa-calendar-xmark"></i>
    <p>No miqaats found.</p>
  </div>
  <?php endif; ?>

</div><!-- /rsvp-wrap -->
</div><!-- /#rsvpApp -->

<script>
(function(){
  var allCards = Array.prototype.slice.call(document.querySelectorAll('#miqaat-cards .mq-card'));
  var visibleCountEl = document.getElementById('visible-count-val');
  var sumMiqaatsEl   = document.getElementById('sum-miqaats');
  var sumRsvpsEl     = document.getElementById('sum-rsvps');

  function parseDate(s){ if(!s)return null; var t=new Date(s); return isNaN(t.getTime())?null:t; }

  function toISO(d){
    var y=d.getFullYear(), m=('0'+(d.getMonth()+1)).slice(-2), day=('0'+d.getDate()).slice(-2);
    return y+'-'+m+'-'+day;
  }

  function applyFilters(){
    var from  = parseDate(document.getElementById('filter-from').value);
    var to    = parseDate(document.getElementById('filter-to').value);
    var name  = (document.getElementById('filter-name').value||'').toLowerCase().trim();
    var vis=0, rsvpSum=0;
    allCards.forEach(function(card){
      var cdate = parseDate(card.getAttribute('data-date'));
      var cname = (card.getAttribute('data-name')||'').toLowerCase();
      var show  = true;
      if(from && cdate && cdate < from) show=false;
      if(to   && cdate && cdate > to)   show=false;
      if(name && cname.indexOf(name)===-1) show=false;
      card.style.display = show?'':'none';
      if(show){
        vis++;
        var rc = card.querySelector('.rsvp-count');
        if(rc) rsvpSum += parseInt(rc.textContent||'0',10);
      }
    });
    if(visibleCountEl) visibleCountEl.textContent = vis;
    if(sumMiqaatsEl)   sumMiqaatsEl.textContent   = vis;
    if(sumRsvpsEl)     sumRsvpsEl.textContent      = rsvpSum;
    // update avg
    var avgEl = document.getElementById('sum-avg');
    if(avgEl) avgEl.textContent = vis>0 ? (rsvpSum/vis).toFixed(1) : '0';
  }

  document.getElementById('filter-apply').addEventListener('click',function(e){e.preventDefault();applyFilters();});

  document.getElementById('filter-reset').addEventListener('click',function(e){
    e.preventDefault();
    document.getElementById('filter-from').value='';
    document.getElementById('filter-to').value='';
    document.getElementById('filter-name').value='';
    document.getElementById('filter-range').value='';
    applyFilters();
  });

  document.getElementById('filter-range').addEventListener('change',function(){
    var v=parseInt(this.value||0,10); if(!v)return;
    var to=new Date(), from=new Date(); from.setDate(to.getDate()-v+1);
    document.getElementById('filter-from').value=toISO(from);
    document.getElementById('filter-to').value=toISO(to);
    applyFilters();
  });

  document.getElementById('filter-clear-name').addEventListener('click',function(){
    document.getElementById('filter-name').value='';
    document.getElementById('filter-name').focus();
    applyFilters();
  });

  /* Live search on keyup */
  document.getElementById('filter-name').addEventListener('input', applyFilters);

  /* Animate RSVP bars in on load */
  setTimeout(function(){
    document.querySelectorAll('.rsvp-bar').forEach(function(bar){
      var w = bar.style.width; bar.style.width='0'; 
      setTimeout(function(){ bar.style.width=w; }, 50);
    });
  }, 100);
})();
</script>