<?php
$date       = isset($date)       ? $date       : date('Y-m-d');
$start_date = isset($start_date) ? $start_date : '';
$end_date   = isset($end_date)   ? $end_date   : '';

if (empty($start_date)) {
  if (!empty($_GET['start_date']))  { $start_date = $_GET['start_date']; }
  elseif (!empty($_GET['start']))   { $start_date = $_GET['start']; }
  if (!empty($start_date))          { $date = ''; }
}
if (empty($end_date)) {
  if (!empty($_GET['end_date']))    { $end_date = $_GET['end_date']; }
  elseif (!empty($_GET['end']))     { $end_date = $_GET['end']; }
  if (!empty($end_date))            { $date = ''; }
}

$breakdown = isset($breakdown) ? $breakdown : [];
$totals    = isset($totals)    ? $totals    : ['families'=>0,'signed_up'=>0,'not_signed'=>0,'percent'=>0];
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════
   THAALI SIGNUPS BREAKDOWN — gold design system
   All class names unchanged, CSS-only improvements
═══════════════════════════════════════════════════ */
#tsbApp{
  font-family:'Plus Jakarta Sans',sans-serif;
  color:#1a1610;
  background:#faf7f0;
  min-height:calc(100vh - 57px);
  padding:16px;
}
#tsbApp *{box-sizing:border-box}

/* ── Topbar ── */
#tsbApp .tsb-topbar{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:18px;flex-wrap:wrap;gap:8px;
}
#tsbApp .tsb-back{
  display:inline-flex;align-items:center;gap:5px;
  padding:6px 13px;border-radius:8px;
  border:1.5px solid #e8e0cc;background:#fff;color:#5a5244;
  font-size:.75rem;font-weight:700;text-decoration:none;
  transition:all .15s;flex-shrink:0;
  box-shadow:0 1px 2px rgba(0,0,0,.04);
}
#tsbApp .tsb-back:hover{
  background:#f5e9c0;border-color:#b8860b;color:#b8860b;text-decoration:none;
}
#tsbApp .tsb-heading{
  font-size:.98rem;font-weight:800;color:#b8860b;
  text-align:center;flex:1;letter-spacing:.3px;
}

/* ══════════════════════════════════════════
   HIJRI MONTH NAVIGATOR
   Pill card — arrows physically beside text
   Full-bleed gold gradient on hover
══════════════════════════════════════════ */
#tsbApp .tsb-hijri-nav{
  display:flex;justify-content:center;
  margin-bottom:16px;
}
#tsbApp .tsb-nav-pill{
  display:inline-flex;align-items:stretch;
  background:#fff;
  border:1.5px solid #e8e0cc;
  border-radius:50px;
  box-shadow:0 2px 10px rgba(0,0,0,.07),0 1px 3px rgba(0,0,0,.04);
  overflow:hidden;
  /* responds to content on desktop, edge-to-edge on narrow mobile */
  max-width:360px;
  width:100%;
}
#tsbApp .tsb-chev{
  display:inline-flex;align-items:center;justify-content:center;
  width:48px;flex-shrink:0;
  border:none;background:transparent;
  color:#b8a88a;font-size:.82rem;
  cursor:pointer;text-decoration:none;
  transition:background .15s,color .15s;
  -webkit-tap-highlight-color:transparent;
}
#tsbApp .tsb-chev.left {border-right:1px solid #f0ece0}
#tsbApp .tsb-chev.right{border-left :1px solid #f0ece0}
#tsbApp .tsb-chev:hover{
  background:linear-gradient(135deg,#f5e9c0,#fdf3d0);
  color:#b8860b;text-decoration:none;
}
#tsbApp .tsb-chev.dis{
  opacity:.28;pointer-events:none;cursor:default;
}
#tsbApp .tsb-nav-centre{
  flex:1;padding:11px 16px;text-align:center;
  /* subtle gold shimmer behind month name */
  background:linear-gradient(180deg,#fff 60%,#fdf8ee 100%);
}
#tsbApp .tsb-hijri-title{
  display:block;font-size:1rem;font-weight:800;
  color:#b8860b;line-height:1.2;white-space:nowrap;
  letter-spacing:.2px;
}
#tsbApp .tsb-hijri-year{
  display:block;font-size:.67rem;font-weight:600;
  color:#9c8f7a;margin-top:3px;letter-spacing:.3px;
}

/* ══════════════════════════════════════════
   TABLE CARD
══════════════════════════════════════════ */
#tsbApp .tsb-tcard{
  background:#fff;
  border:1px solid #e8e0cc;
  border-radius:14px;
  box-shadow:0 2px 8px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  overflow:hidden;
}

/* Scroll wrapper — horizontal only, page scrolls vertically */
#tsbApp .tsb-tscroll{
  overflow-x:auto;
  overflow-y:visible;
}
#tsbApp .tsb-tscroll::-webkit-scrollbar{height:4px}
#tsbApp .tsb-tscroll::-webkit-scrollbar-track{background:transparent}
#tsbApp .tsb-tscroll::-webkit-scrollbar-thumb{
  background:#e8e0cc;border-radius:10px;
}

#tsbApp table.tsb-tbl{
  width:100%;border-collapse:collapse;
  font-size:.78rem;min-width:500px;
}

#tsbApp table.tsb-tbl thead th{
  background:linear-gradient(to bottom,#f7f4ec,#ede8da);
  padding:10px 13px;
  font-size:.59rem;font-weight:800;
  text-transform:uppercase;letter-spacing:.75px;color:#9c8f7a;
  border-bottom:2px solid #e8e0cc;
  white-space:nowrap;
  text-align:left;
}
#tsbApp table.tsb-tbl thead th:not(:first-child){text-align:right}

/* Body rows */
#tsbApp table.tsb-tbl tbody tr{
  border-bottom:1px solid #f0ece0;
  cursor:pointer;
  transition:background .1s;
}
#tsbApp table.tsb-tbl tbody tr:hover td{background:#fdf9ef!important}
#tsbApp table.tsb-tbl td{
  padding:10px 13px;vertical-align:middle;color:#1a1610;
}
#tsbApp table.tsb-tbl td:not(:first-child){
  text-align:right;font-variant-numeric:tabular-nums;
}

/* Alternating subtle stripe on even rows */
#tsbApp table.tsb-tbl tbody tr:nth-child(even) td{
  background:#faf7f2;
}
#tsbApp table.tsb-tbl tbody tr:nth-child(even):hover td{background:#fdf9ef!important}

/* ── TODAY row — warm honey, no border ── */
#tsbApp table.tsb-tbl tbody tr.tsb-today td{
  background:#e8ddab!important;
}

#tsbApp table.tsb-tbl tbody tr.tsb-today:hover td{
  background:#dfd29a!important;
}

#tsbApp .tsb-today-badge{
  display:inline-block;
  background:#8a6a12;
  color:#fff;
  font-size:.52rem;
  font-weight:800;
  padding:2px 7px;
  border-radius:20px;
  letter-spacing:.4px;
  text-transform:uppercase;
  margin-left:6px;
  vertical-align:middle;
  box-shadow:0 1px 4px rgba(138,106,18,.22)
}

/* ── Date cell ── */
#tsbApp .tsb-date-main{
  font-size:.8rem;font-weight:700;color:#1a1610;
  display:flex;align-items:center;flex-wrap:wrap;gap:2px;
}
#tsbApp .tsb-date-hijri{
  font-size:.67rem;color:#9c8f7a;margin-top:3px;font-weight:500;
}

/* ── Value cells ── */
#tsbApp .tsb-val-ok {color:#1a6645;font-weight:700}
#tsbApp .tsb-val-bad{color:#b91c1c;font-weight:600}
#tsbApp .tsb-val-sep{color:#d1d5db;font-size:.68rem;margin:0 2px}

/* Total column — slightly larger, bold */
#tsbApp .tsb-total-ok {font-weight:800;font-size:.84rem;color:#1a6645}
#tsbApp .tsb-total-bad{font-weight:800;font-size:.84rem;color:#b91c1c}
#tsbApp .tsb-pct{
  display:block;font-size:.62rem;color:#9c8f7a;
  margin-top:3px;font-weight:600;
}

/* ── Pulse animation for today on load ── */
#tsbApp .tsb-pulse{animation:tsbPulse 2.2s ease 1}
@keyframes tsbPulse{
  0%  {background:#fff5c2!important}
  55% {background:#fffbea!important}
  100%{background:#fffbea!important}
}

/* ── Empty state ── */
#tsbApp .tsb-empty{
  text-align:center;padding:48px 20px;
  color:#9c8f7a;font-size:.82rem;
}
#tsbApp .tsb-empty i{
  font-size:2rem;display:block;
  margin-bottom:10px;color:#e8e0cc;
}

/* ── Footer ── */
#tsbApp .tsb-tfoot{
  display:flex;align-items:center;justify-content:space-between;
  padding:9px 14px;
  border-top:1px solid #f0ece0;
  background:#f7f4ec;
  font-size:.68rem;color:#9c8f7a;
  flex-wrap:wrap;gap:6px;
}
#tsbApp .tsb-cnt{
  background:#f5e9c0;color:#b8860b;
  border-radius:20px;padding:2px 10px;
  font-size:.64rem;font-weight:800;
}

/* ── Responsive ── */
@media(max-width:576px){
  #tsbApp{padding:10px}
  #tsbApp .tsb-nav-pill{max-width:100%}
  #tsbApp .tsb-nav-centre{padding:9px 10px}
  #tsbApp .tsb-hijri-title{font-size:.88rem}
  #tsbApp .tsb-chev{width:40px}
  #tsbApp table.tsb-tbl{min-width:420px}
  #tsbApp table.tsb-tbl thead th{top:57px}
}
</style>

<?php
/* ── Hijri navigator ── */
$this->load->model('HijriCalendar');
$sel_hijri_year  = isset($_GET['hijri_year'])  ? $_GET['hijri_year']  : null;
$sel_hijri_month = isset($_GET['hijri_month']) ? (int)$_GET['hijri_month'] : null;
if (!$sel_hijri_year || !$sel_hijri_month) {
  $ref_date = '';
  if (!empty($start_date))  $ref_date = $start_date;
  elseif (!empty($date))    $ref_date = $date;
  else                      $ref_date = date('Y-m-d');
  $parts = $this->HijriCalendar->get_hijri_parts_by_greg_date($ref_date);
  if ($parts && is_array($parts)) {
    $sel_hijri_year  = $parts['hijri_year'];
    $sel_hijri_month = (int)$parts['hijri_month'];
  }
}
$years = $this->HijriCalendar->get_distinct_hijri_years();
$monthList = [];
foreach ($years as $y) {
  $ms = $this->HijriCalendar->get_hijri_months_for_year($y);
  if (!is_array($ms)) continue;
  foreach ($ms as $m) $monthList[] = ['year'=>$y,'id'=>(int)$m['id'],'name'=>$m['name']??''];
}
$currentIndex = null;
foreach ($monthList as $i => $mn) {
  if ($mn['year']==$sel_hijri_year && (int)$mn['id']===(int)$sel_hijri_month) { $currentIndex=$i; break; }
}
$prev = ($currentIndex!==null && $currentIndex>0)                   ? $monthList[$currentIndex-1] : null;
$next = ($currentIndex!==null && $currentIndex<count($monthList)-1) ? $monthList[$currentIndex+1] : null;
$basePath = site_url('common/thaali_signups_breakdown');
$preserve = [];
if (!empty($from))           $preserve['from']       = $from;
if (!empty($start_date))     $preserve['start_date'] = $start_date;
if (!empty($end_date))       $preserve['end_date']   = $end_date;
if (!empty($date))           $preserve['date']        = $date;
if (!empty($_GET['sector'])) $preserve['sector']     = $_GET['sector'];
$currentMonthName = ($currentIndex!==null && isset($monthList[$currentIndex]['name'])) ? $monthList[$currentIndex]['name'] : '';

/* ── Build rows ──────────────────────────────────────────────────────────────
   Priority: $daily_breakdowns (full month or range, multiple days)
   Fallback:  $breakdown (single-day legacy, controller passes this for one date)
   The bug was: default page load (no date params) hit the else branch which
   only reads $breakdown → just 1 day. Now we always try $daily_breakdowns first.
── */
$isSubSectorMode = !empty($_GET['sector']);
$rows = [];

if (!empty($daily_breakdowns)) {
  /* Controller sent multi-day data — use it regardless of URL params */
  foreach ($daily_breakdowns as $day) {
    $d = $day['date'] ?? '';
    foreach ($day['breakdown'] ?? [] as $r) {
      $sec = $isSubSectorMode ? trim($r['sub_sector']??'') : trim($r['sector']??'');
      if ($sec==='') $sec='Unassigned';
      $rows[] = ['date'=>$d,'sector'=>$sec,'signed'=>(int)($r['signed_up']??0),'total'=>(int)($r['total_families']??0)];
    }
  }
} else {
  /* Fallback: single-day $breakdown (legacy or single-date controller path) */
  $fallback_date = !empty($date) ? $date : (!empty($start_date) ? $start_date : date('Y-m-d'));
  foreach ($breakdown as $r) {
    $d   = $r['date'] ?? $r['greg_date'] ?? $fallback_date;
    $sec = $isSubSectorMode ? trim($r['sub_sector']??'') : trim($r['sector']??'');
    if ($sec==='') $sec='Unassigned';
    $rows[] = ['date'=>$d,'sector'=>$sec,'signed'=>(int)($r['signed_up']??0),'total'=>(int)($r['total_families']??0)];
  }
}
usort($rows, fn($a,$b) => (strtotime($a['date']??'') <=> strtotime($b['date']??'')) ?: strcmp($a['sector']??'',$b['sector']??''));

/* ── Pivot ── */
$dateSectorCounts = []; $sectors = [];
foreach ($rows as $r) {
  $d=$r['date']??''; $s=$r['sector']??'';
  if ($d==='') continue;
  if (!isset($dateSectorCounts[$d]))      $dateSectorCounts[$d]=[];
  if (!isset($dateSectorCounts[$d][$s])) $dateSectorCounts[$d][$s]=['signed'=>0,'total'=>0];
  $dateSectorCounts[$d][$s]['signed'] += $r['signed'];
  $dateSectorCounts[$d][$s]['total']  += $r['total'];
  $sectors[$s]=true;
}
$sectors = array_keys($sectors); sort($sectors,SORT_NATURAL|SORT_FLAG_CASE);
$dates   = array_keys($dateSectorCounts);
usort($dates, fn($a,$b) => strtotime($a)<=>strtotime($b));
$colCount = count($sectors)+2;
$today    = date('Y-m-d');
?>

<div id="tsbApp" class="margintopcontainer">

  <!-- Topbar -->
  <div class="tsb-topbar">
    <a href="<?php echo $active_controller?>" class="tsb-back"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="tsb-heading"><i class="fa fa-cutlery" style="margin-right:6px;opacity:.7"></i>Thaali Signups Breakdown</div>
    <div style="width:80px;flex-shrink:0"></div>
  </div>

  <!-- Hijri month navigator — compact pill with arrows right beside text -->
  <div class="tsb-hijri-nav">
    <div class="tsb-nav-pill">

      <a href="<?php echo $prev ? ($basePath.'?'.http_build_query(array_merge($preserve,['hijri_year'=>$prev['year'],'hijri_month'=>$prev['id']]))) : '#'?>"
         class="tsb-chev left <?php echo $prev?'':'dis'?>" id="tsb-prev" aria-label="Previous Hijri month">
        <i class="fa fa-chevron-left"></i>
      </a>

      <div class="tsb-nav-centre" id="tsb-nav-centre">
        <span class="tsb-hijri-title" id="tsb-hijri-title"><?php echo htmlspecialchars($currentMonthName,ENT_QUOTES)?></span>
        <span class="tsb-hijri-year"><?php echo htmlspecialchars((string)($sel_hijri_year??''),ENT_QUOTES)?>H</span>
      </div>

      <a href="<?php echo $next ? ($basePath.'?'.http_build_query(array_merge($preserve,['hijri_year'=>$next['year'],'hijri_month'=>$next['id']]))) : '#'?>"
         class="tsb-chev right <?php echo $next?'':'dis'?>" id="tsb-next" aria-label="Next Hijri month">
        <i class="fa fa-chevron-right"></i>
      </a>

    </div>
  </div>

  <!-- Table card -->
  <div class="tsb-tcard" id="thaali-breakdown-block">
    <div class="tsb-tscroll">
      <table class="tsb-tbl" id="tsbTable">
        <thead>
          <tr>
            <th style="min-width:190px">Date (Hijri)</th>
            <?php foreach ($sectors as $sec): ?>
            <th><?php echo htmlspecialchars($sec,ENT_QUOTES)?></th>
            <?php endforeach ?>
            <th style="min-width:120px">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($dates)): ?>
          <tr><td colspan="<?php echo $colCount?>" class="tsb-empty">
            <i class="fa fa-cutlery"></i>
            <p>No data available for this period.</p>
          </td></tr>
          <?php else: ?>
          <?php foreach ($dates as $d):
            $rowCounts  = $dateSectorCounts[$d] ?? [];
            $sum_signed = 0; $sum_total = 0;
            $hijriParts = $hijri_by_date[$d] ?? null;
            $hijriDisp  = '';
            if ($hijriParts) {
              $hijriDisp = $hijriParts['hijri_day'].' '.($hijriParts['hijri_month_name']??$hijriParts['hijri_month']).' '.$hijriParts['hijri_year'].'H';
            }
            $isToday = ($d === $today);
          ?>
          <tr class="tsb-row-click<?php echo $isToday?' tsb-today':''?>"
              data-date="<?php echo htmlspecialchars($d,ENT_QUOTES)?>"
              tabindex="0"
              title="View detail for <?php echo htmlspecialchars($d,ENT_QUOTES)?>">
            <td>
              <div class="tsb-date-main">
                <?php echo date('D, d M Y',strtotime($d))?>
                <?php if ($isToday): ?><span class="tsb-today-badge">Today</span><?php endif ?>
              </div>
              <?php if ($hijriDisp): ?><div class="tsb-date-hijri"><?php echo htmlspecialchars($hijriDisp,ENT_QUOTES)?></div><?php endif ?>
            </td>
            <?php foreach ($sectors as $sec):
              $cell   = $rowCounts[$sec] ?? ['signed'=>0,'total'=>0];
              $signed = (int)($cell['signed']??0);
              $total  = (int)($cell['total']??0);
              $notsig = max($total-$signed,0);
              $sum_signed += $signed;
              $sum_total  += $total;
            ?>
            <td>
              <span class="tsb-val-ok"><?php echo number_format($signed)?></span>
              <span class="tsb-val-sep">/</span>
              <span class="tsb-val-bad"><?php echo number_format($notsig)?></span>
            </td>
            <?php endforeach ?>
            <td>
              <?php if ($sum_total > 0):
                $sum_not = max($sum_total-$sum_signed,0);
                $pct     = round($sum_signed/$sum_total*100);
              ?>
              <span class="tsb-total-ok"><?php echo number_format($sum_signed)?></span>
              <span class="tsb-val-sep">/</span>
              <span class="tsb-total-bad"><?php echo number_format($sum_not)?></span>
              <span class="tsb-pct"><?php echo $pct?>% signed</span>
              <?php else: ?><span style="color:#d1d5db">—</span><?php endif ?>
            </td>
          </tr>
          <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
    <div class="tsb-tfoot">
      <span class="tsb-cnt"><?php echo count($dates)?> row<?php echo count($dates)!==1?'s':''?></span>
      <span style="font-size:.66rem;color:#9c8f7a">
        <span style="color:#1a6645;font-weight:700">Green</span> = Signed up &nbsp;/&nbsp;
        <span style="color:#b91c1c;font-weight:700">Red</span> = Not signed up
      </span>
    </div>
  </div>

</div><!-- /#tsbApp -->

<script>
/* ── Row click → detail page ── */
function initTsbRows(){
  var base='<?php echo site_url('common/thaali_signup_report')?>';
  var fromQ='<?php echo !empty($from)?('?from='.urlencode($from)):''?>';
  document.querySelectorAll('#tsbApp .tsb-row-click[data-date]').forEach(function(tr){
    tr.replaceWith(tr.cloneNode(true));
  });
  document.querySelectorAll('#tsbApp .tsb-row-click[data-date]').forEach(function(tr){
    var d=tr.getAttribute('data-date');if(!d)return;
    var url=base+'/'+encodeURIComponent(d)+fromQ;
    tr.title='View detail for '+d;
    tr.addEventListener('click',function(e){if(e.target&&e.target.tagName==='A')return;e.metaKey||e.ctrlKey?window.open(url,'_blank'):window.location.href=url});
    tr.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' ')window.location.href=url});
  });
}
initTsbRows();

/* ── Auto-scroll + pulse today ── */
(function(){
  var today='<?php echo $today?>';
  var target=document.querySelector('#tsbApp .tsb-row-click[data-date="'+today+'"]');
  if(!target)return;
  target.classList.add('tsb-pulse');
  setTimeout(function(){target.classList.remove('tsb-pulse')},2500);
  var container=target.closest('.tsb-tscroll');
  if(container&&container.scrollHeight>container.clientHeight){
    var cRect=container.getBoundingClientRect(),tRect=target.getBoundingClientRect();
    container.scrollBy({top:(tRect.top-cRect.top)-(container.clientHeight/2-tRect.height/2),behavior:'smooth'});
  } else { target.scrollIntoView({block:'center',behavior:'smooth'}); }
})();

/* ── Hijri AJAX navigation ── */
(function(){
  function buildUrl(href){
    try{var u=new URL(href,window.location.origin);u.searchParams.set('ajax','1');return u.toString()}
    catch(e){return href+(href.indexOf('?')===-1?'?':'&')+'ajax=1'}
  }
  function setSpinner(on){
    document.querySelectorAll('#tsbApp .tsb-chev').forEach(function(b){
      if(on){b.dataset.orig=b.innerHTML;b.innerHTML='<i class="fa fa-spinner fa-spin"></i>'}
      else if(b.dataset.orig){b.innerHTML=b.dataset.orig;delete b.dataset.orig}
    });
  }
  document.addEventListener('click',function(e){
    var a=e.target.closest&&e.target.closest('.tsb-chev');
    if(!a)return;
    if(a.classList.contains('dis')){e.preventDefault();return}
    var href=a.getAttribute('href')||'#';
    if(href==='#'){e.preventDefault();return}
    e.preventDefault();
    setSpinner(true);
    fetch(buildUrl(href),{credentials:'same-origin'}).then(function(r){return r.text()}).then(function(text){
      var parser=new DOMParser();var doc=parser.parseFromString(text,'text/html');
      /* Replace table block */
      var newBlock=doc.getElementById('thaali-breakdown-block');
      if(newBlock){var cur=document.getElementById('thaali-breakdown-block');if(cur)cur.parentNode.replaceChild(newBlock,cur)}
      /* Replace nav centre (month name + year) */
      var newCentre=doc.getElementById('tsb-nav-centre');
      if(newCentre){var curCentre=document.getElementById('tsb-nav-centre');if(curCentre)curCentre.innerHTML=newCentre.innerHTML}
      /* Update nav arrow classes + hrefs */
      var navBtns=doc.querySelectorAll('.tsb-chev');
      var curNav=document.querySelectorAll('#tsbApp .tsb-chev');
      for(var i=0;i<Math.min(navBtns.length,curNav.length);i++){
        curNav[i].className=navBtns[i].className;
        curNav[i].setAttribute('href',navBtns[i].getAttribute('href')||'#');
        curNav[i].innerHTML=navBtns[i].innerHTML;
      }
      if(typeof initTsbRows==='function')initTsbRows();
      try{history.pushState({},'',href)}catch(e){}
    }).catch(function(err){console.error('TSB AJAX nav failed',err)}).finally(function(){setSpinner(false)});
  });
  window.addEventListener('popstate',function(){window.location.reload()});
})();
</script>