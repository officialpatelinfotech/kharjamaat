<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2: #5a5244; --text-3: #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg:   #fef2f2; --red-border:   #fecaca;
    --blue:       #1d4ed8; --blue-bg:  #eff6ff; --blue-border:  #bfdbfe;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap { position: relative; display: flex; align-items: center; justify-content: center; min-height: 44px; margin-bottom: 6px; }
  .btn-back-nav { position: absolute; left: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 24px; }

  /* ── Year selector ── */
  .year-selector-wrap {
    display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px;
  }
  .year-selector-label { font-size: 0.7rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); }
  .year-select {
    height: 40px; padding: 0 36px 0 14px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 12px center;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem; font-weight: 600; color: var(--text-1);
    box-shadow: var(--shadow-sm);
    appearance: none; -webkit-appearance: none; cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
  }
  .year-select:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }

  /* ── Summary tiles ── */
  .summary-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
  @media (max-width: 480px) { .summary-strip { grid-template-columns: 1fr; } }

  .stat-tile {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    padding: 18px 16px; text-align: center;
    position: relative; overflow: hidden; transition: box-shadow .2s;
  }
  .stat-tile:hover { box-shadow: var(--shadow); }
  .stat-tile::before { content: ''; display: block; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
  .tile-assigned::before { background: linear-gradient(90deg, var(--blue),  #60a5fa); }
  .tile-paid::before     { background: linear-gradient(90deg, var(--green), #4cc790); }
  .tile-due::before      { background: linear-gradient(90deg, var(--red),   #f87171); }

  .tile-label { font-size: 0.65rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); margin-bottom: 8px; }
  .tile-value { font-size: 1.6rem; font-weight: 800; letter-spacing: -1px; font-variant-numeric: tabular-nums; line-height: 1; }
  .tv-blue  { color: var(--blue); }
  .tv-green { color: var(--green); }
  .tv-red   { color: var(--red); }

  /* ── Last updated chip ── */
  .updated-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: .3px;
    color: var(--text-3); background: var(--surface-2);
    border: 1px solid var(--border); border-radius: 40px; padding: 4px 12px;
    margin-top: 6px;
  }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .tile-value { font-size: 1.3rem; }
  }
</style>

<?php
  $tot_assigned = isset($wajebaat['amount']) ? (float)$wajebaat['amount'] : 0;
  $tot_due      = isset($wajebaat['due'])    ? (float)$wajebaat['due']    : null;
  $tot_paid     = null;

  foreach (['paid','amount_paid','paid_amount','total_paid'] as $k) {
    if (isset($wajebaat[$k]) && $wajebaat[$k] !== '' && $wajebaat[$k] !== null) {
      $tot_paid = (float)$wajebaat[$k]; break;
    }
  }
  if ($tot_paid === null) $tot_paid = max(0, $tot_assigned - (float)($tot_due ?? 0));
  if ($tot_due  === null) $tot_due  = max(0, $tot_assigned - $tot_paid);

  $lastUpdatedTs = null;
  if (!empty($wajebaat) && is_array($wajebaat)) {
    foreach (['updated_at','updated_on','last_updated','modified_at','modified_on','created_at','created_on','created_date','date','created'] as $k) {
      if (!empty($wajebaat[$k])) { $ts = strtotime((string)$wajebaat[$k]); if ($ts) { $lastUpdatedTs = $ts; break; } }
    }
  }
  $lastUpdatedText = $lastUpdatedTs ? date('d M Y, g:i a', $lastUpdatedTs) : '—';
?>

<div class="container margintopcontainer pt-5 pb-5" style="max-width:900px;">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Wajebaat</h1>
  </div>
  <p class="page-sub">Annual wajebaat dues &amp; payment summary</p>
  <hr class="section-divider">

  <!-- ── Year selector ── -->
  <div class="year-selector-wrap">
    <span class="year-selector-label"><i class="fa fa-calendar" style="margin-right:5px;color:var(--gold);"></i>Hijri Year</span>
    <select id="yearSelect" class="year-select" onchange="location.href='?year='+this.value;">
      <?php foreach ($available_years as $y): ?>
        <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?> H</option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- ── Summary tiles ── -->
  <div class="summary-strip">
    <div class="stat-tile tile-assigned">
      <div class="tile-label">Total Assigned</div>
      <div class="tile-value tv-blue">₹<?php echo format_inr_no_decimals($tot_assigned); ?></div>
    </div>
    <div class="stat-tile tile-paid">
      <div class="tile-label">Total Paid</div>
      <div class="tile-value tv-green">₹<?php echo format_inr_no_decimals($tot_paid); ?></div>
    </div>
    <div class="stat-tile tile-due">
      <div class="tile-label">Outstanding</div>
      <div class="tile-value tv-red">₹<?php echo format_inr_no_decimals($tot_due); ?></div>
    </div>
  </div>

  <!-- ── Last updated ── -->
  <div class="text-center">
    <span class="updated-chip">
      <i class="fa fa-clock-o"></i> Last Updated: <?php echo htmlspecialchars($lastUpdatedText); ?>
    </span>
  </div>

</div>