<?php // Assumes format_inr_no_decimals() helper is available ?>

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
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Header ── */
  .page-header-wrap { position: relative; display: flex; align-items: center; justify-content: center; min-height: 44px; margin-bottom: 6px; }
  .btn-back-nav { position: absolute; left: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.5rem; font-weight: 600; letter-spacing: -.3px; margin: 0; text-align: center; }
  .page-sub { font-size: 0.72rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 4px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 24px; }

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

  .stat-tile .tile-label { font-size: 0.65rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); margin-bottom: 8px; }
  .stat-tile .tile-value { font-size: 1.6rem; font-weight: 800; letter-spacing: -1px; font-variant-numeric: tabular-nums; line-height: 1; }
  .tv-blue  { color: var(--blue); }
  .tv-green { color: var(--green); }
  .tv-red   { color: var(--red); }

  /* ── Section card ── */
  .section-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    overflow: hidden; margin-bottom: 20px;
  }
  .section-card::before { content: ''; display: block; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .section-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 20px 11px; background: var(--surface-2); border-bottom: 1px solid var(--border);
  }
  .section-card-title { font-size: 0.78rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-2); display: flex; align-items: center; gap: 8px; margin: 0; }
  .section-card-title .fa { width: 26px; height: 26px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; background: var(--gold-muted); color: var(--gold); }

  /* ── Table ── */
  .themed-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
  .themed-table thead th { font-size: 0.63rem; font-weight: 700; letter-spacing: .55px; text-transform: uppercase; color: var(--text-3); padding: 11px 16px; border-bottom: 1.5px solid var(--border); background: var(--surface-2); white-space: nowrap; }
  .themed-table tbody td { padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--text-2); vertical-align: middle; }
  .themed-table tbody tr:last-child td { border-bottom: none; }
  .themed-table tbody tr:nth-of-type(odd)  { background: var(--surface-2); }
  .themed-table tbody tr:nth-of-type(even) { background: var(--surface); }
  .themed-table tbody tr:hover { background: var(--gold-muted) !important; }

  .t-num   { color: var(--text-3); font-size: 0.72rem; font-weight: 700; }
  .t-title { font-weight: 600; color: var(--text-1); }
  .t-amt   { font-weight: 700; font-variant-numeric: tabular-nums; text-align: right; }
  .t-blue  { color: var(--blue); }
  .t-green { color: var(--green); }
  .t-red   { color: var(--red); }

  /* empty */
  .t-empty { text-align: center; padding: 32px; font-size: 0.83rem; color: var(--text-3); }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    .stat-tile .tile-value { font-size: 1.3rem; }
  }
</style>

<?php
  $tot_assigned = isset($corpus_details['tot_assigned']) ? (float)$corpus_details['tot_assigned'] : 0;
  $tot_paid     = isset($corpus_details['tot_paid'])     ? (float)$corpus_details['tot_paid']     : 0;
  $tot_due      = isset($corpus_details['tot_due'])      ? (float)$corpus_details['tot_due']      : 0;
?>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Corpus Funds</h1>
  </div>
  <p class="page-sub">Fund assignments, payments &amp; outstanding dues</p>
  <hr class="section-divider">

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

  <!-- ── Per-fund table ── -->
  <div class="section-card">
    <div class="section-card-header">
      <h5 class="section-card-title"><i class="fa fa-list"></i> Per Fund Breakdown</h5>
    </div>
    <div class="table-responsive">
      <table class="themed-table">
        <thead>
          <tr>
            <th style="width:56px;">#</th>
            <th>Fund</th>
            <th class="text-right">Assigned</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Outstanding</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $rows = $corpus_details['rows'] ?? [];
          if (!empty($rows)):
            $i = 1;
            foreach ($rows as $r): ?>
            <tr>
              <td><span class="t-num"><?php echo $i++; ?></span></td>
              <td><span class="t-title"><?php echo htmlspecialchars($r['title'] ?? ''); ?></span></td>
              <td class="text-right"><span class="t-amt t-blue">₹<?php echo format_inr_no_decimals((float)($r['amount_assigned'] ?? 0)); ?></span></td>
              <td class="text-right"><span class="t-amt t-green">₹<?php echo format_inr_no_decimals((float)($r['amount_paid'] ?? 0)); ?></span></td>
              <td class="text-right"><span class="t-amt t-red">₹<?php echo format_inr_no_decimals((float)($r['amount_due'] ?? 0)); ?></span></td>
            </tr>
            <?php endforeach;
          else: ?>
            <tr><td colspan="5" class="t-empty">No corpus fund records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>