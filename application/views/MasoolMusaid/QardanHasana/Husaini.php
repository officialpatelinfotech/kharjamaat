<?php
// application/views/MasoolMusaid/QardanHasana/Husaini.php
$members   = $members   ?? [];
$summary   = $summary   ?? ['total' => 0, 'given' => 0, 'not_given' => 0];
$sector    = $sector    ?? '';
$subsector = $subsector ?? '';
$scope_label  = trim($sector . ' ' . $subsector);
$total        = (int)$summary['total'];
$given        = (int)$summary['given'];
$not_given    = (int)$summary['not_given'];
$pct_given    = $total > 0 ? round($given     / $total * 100) : 0;
$pct_not      = $total > 0 ? round($not_given / $total * 100) : 0;
?>
<style>
:root {
  --gold:         #b8860b;
  --gold-muted:   #f5e9c0;
  --green:        #1b6e38;
  --green-bg:     #edfbf1;
  --green-row:    #f0fdf4;
  --green-border: #16a34a;
  --red:          #b91c1c;
  --red-bg:       #fff0f0;
  --red-row:      #fff5f5;
  --red-border:   #dc2626;
  --amber:        #92400e;
  --amber-bg:     #fffbeb;
  --surface:      #ffffff;
  --bg:           #faf7f0;
  --border:       #e8e0cc;
  --text-1:       #1a1610;
  --text-2:       #5a5244;
  --text-3:       #9c8f7a;
  --shadow-sm:    0 1px 3px rgba(0,0,0,.06);
  --shadow:       0 4px 16px rgba(0,0,0,.08);
  --radius:       12px;
}
body { background: var(--bg); }

/* ── Header ── */
.qh-header {
  background: linear-gradient(135deg,#78520a 0%,#b8860b 55%,#c9a227 100%);
  border-radius: var(--radius); padding: 1.2rem 1.5rem;
  color:#fff; margin-bottom:1.4rem;
  box-shadow:0 4px 20px rgba(120,82,10,.35);
  border:1px solid rgba(255,255,255,.12);
  position:relative; overflow:hidden;
}
.qh-header::before {
  content:''; position:absolute; inset:0;
  background:linear-gradient(160deg,rgba(255,255,255,.10) 0%,transparent 55%);
  pointer-events:none;
}
.qh-header h2 { font-size:1.2rem; font-weight:800; margin:0; letter-spacing:-.2px; text-shadow:0 1px 3px rgba(0,0,0,.2); }
.qh-header p  { margin:3px 0 0; opacity:.88; font-size:.82rem; }

.qh-back-btn {
  display:inline-flex; align-items:center; gap:6px;
  padding:5px 13px; border-radius:8px;
  margin-right: 10px;;
  background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.28);
  color:#fff; font-size:.77rem; font-weight:700; text-decoration:none;
  transition:background .15s; white-space:nowrap; flex-shrink:0;
}
.qh-back-btn:hover { background:rgba(255,255,255,.27); color:#fff; text-decoration:none; }

.header-stats { font-size:.81rem; opacity:.93; text-align:right; line-height:1.85; flex-shrink:0; }
.header-stats .stat-val { font-size:1.05rem; font-weight:800; }

/* ── Summary cards ── */
.summary-grid {
  display:grid; grid-template-columns:repeat(3,1fr);
  gap:14px; margin-bottom:1rem;
}
.summary-card {
  background:var(--surface); border:1.5px solid var(--border);
  border-radius:var(--radius); padding:15px; box-shadow:var(--shadow-sm);
  cursor:pointer; transition:transform .15s,box-shadow .15s;
  position:relative; overflow:hidden;
}
.summary-card:hover { transform:translateY(-2px); box-shadow:var(--shadow); }
.summary-card::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:3px;
  background:var(--card-accent,var(--gold)); transform:scaleX(0);
  transition:transform .2s; transform-origin:left;
}
.summary-card:hover::after, .summary-card.active-filter::after { transform:scaleX(1); }
.summary-card.active-filter { border-color:var(--card-accent,var(--gold))!important; background:var(--card-bg,var(--gold-muted))!important; }
.summary-card h6 { font-size:.67rem; font-weight:700; letter-spacing:.5px; text-transform:uppercase; color:var(--text-3); margin-bottom:6px; }
.summary-card .val { font-size:2rem; font-weight:800; color:var(--text-1); line-height:1; }
.summary-card .sub { font-size:.72rem; color:var(--text-3); margin-top:3px; }
.card-total   { --card-accent:var(--gold);  --card-bg:var(--gold-muted); }
.card-given   { --card-accent:var(--green); --card-bg:var(--green-bg); }
.card-notgiven{ --card-accent:var(--red);   --card-bg:var(--red-bg); }

/* ── Progress bar ── */
.progress-wrap { margin-bottom:1.2rem; }
.progress-labels { display:flex; justify-content:space-between; font-size:.71rem; color:var(--text-3); margin-bottom:4px; }
.progress-track { height:8px; border-radius:20px; background:#fee2e2; overflow:hidden; }
.progress-fill  { height:100%; border-radius:20px; background:linear-gradient(90deg,#16a34a,#22c55e); transition:width .4s; }

/* ── Filter bar ── */
.filter-bar {
  background:var(--surface); border:1px solid var(--border); border-radius:var(--radius);
  padding:10px 14px; display:flex; flex-wrap:wrap; gap:10px; align-items:center;
  margin-bottom:1rem; box-shadow:var(--shadow-sm);
}
.filter-bar input[type=search] {
  flex:1; min-width:200px; border:1.5px solid var(--border); border-radius:8px;
  padding:7px 11px; font-size:.86rem; outline:none; transition:border-color .15s;
}
.filter-bar input[type=search]:focus { border-color:var(--gold); }

.tab-pills { display:flex; gap:6px; flex-wrap:wrap; }
.tab-pill {
  padding:5px 13px; border-radius:20px; border:1.5px solid var(--border);
  background:var(--surface); color:var(--text-2); font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .15s; white-space:nowrap;
}
.tab-pill:hover { border-color:var(--gold); color:var(--gold); }
.tab-pill.active              { background:var(--gold);  border-color:var(--gold);  color:#fff; }
.tab-pill.pill-given.active   { background:var(--green); border-color:var(--green); color:#fff; }
.tab-pill.pill-notgiven.active{ background:var(--red);   border-color:var(--red);   color:#fff; }
.results-count { font-size:.8rem; color:var(--text-3); margin-left:auto; white-space:nowrap; }

/* ── Legend ── */
.legend-bar { display:flex; flex-wrap:wrap; gap:12px; align-items:center; font-size:.73rem; font-weight:600; margin-bottom:8px; }
.legend-dot { display:inline-flex; align-items:center; gap:5px; color:var(--text-2); }
.legend-dot::before { content:''; width:12px; height:12px; border-radius:3px; flex-shrink:0; }
.legend-dot.dot-given::before    { background:var(--green-row); border:1.5px solid var(--green-border); }
.legend-dot.dot-notgiven::before { background:var(--red-row);   border:1.5px solid var(--red-border);   }

/* ── Table ── */
.qh-table-wrap { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
.qh-table-scroll { overflow-x:auto; max-height:65vh; }
.qh-table { width:100%; border-collapse:collapse; font-size:.85rem; min-width:620px; }
.qh-table thead th {
  background:var(--gold); color:#fff; padding:10px 12px; text-align:left;
  font-size:.72rem; font-weight:700; letter-spacing:.4px; text-transform:uppercase;
  position:sticky; top:0; z-index:2; white-space:nowrap; cursor:pointer; user-select:none;
}
.qh-table thead th:hover { background:#a07709; }
.qh-table thead th .sort-icon { margin-left:4px; opacity:.6; font-size:.67rem; }
.qh-table tbody tr { border-bottom:1px solid var(--border); transition:background .1s; }
.qh-table tbody tr:last-child { border-bottom:none; }
.qh-table td { padding:9px 12px; vertical-align:middle; }

/* Given row — green */
.qh-table tbody tr.row-given {
  background-color:var(--green-row)!important;
  border-left:4px solid var(--green-border)!important;
}
.qh-table tbody tr.row-given:hover { background-color:#dcfce7!important; }
.qh-table tbody tr.row-given td    { color:#14532d; }

/* Not given row — red */
.qh-table tbody tr.row-notgiven {
  background-color:var(--red-row)!important;
  border-left:4px solid var(--red-border)!important;
}
.qh-table tbody tr.row-notgiven:hover { background-color:#fee2e2!important; }
.qh-table tbody tr.row-notgiven td    { color:#7f1d1d; }

/* Badges */
.badge-husaini { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:.74rem; font-weight:700; }
.badge-given    { background:var(--green-bg); color:var(--green); }
.badge-notgiven { background:var(--red-bg);   color:var(--red);   }
.badge-hof { background:#dbeafe; color:#1d4ed8; padding:3px 8px; border-radius:10px; font-size:.69rem; font-weight:700; }
.badge-fm  { background:#ede9fe; color:#5b21b6; padding:3px 8px; border-radius:10px; font-size:.69rem; font-weight:700; }

.empty-state { text-align:center; padding:3rem 1rem; color:var(--text-3); }
.empty-state i { font-size:2.5rem; margin-bottom:.8rem; display:block; }

@media (max-width:576px) {
  .summary-grid { grid-template-columns:repeat(3,1fr); gap:8px; }
  .results-count { width:100%; margin-left:0; }
}
</style>

<div class="container-fluid px-3 px-md-4" style="padding-top:70px;">

  <!-- Header -->
  <div class="qh-header">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
      <div class="d-flex align-items-center gap-3">
        <a href="<?= base_url('MasoolMusaid') ?>" class="qh-back-btn">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
          <h2><i class="fa-solid fa-hand-holding-heart me-2"></i>Husaini Scheme</h2>
          <p>Lifetime participation — <strong><?= htmlspecialchars($scope_label) ?></strong> &nbsp;·&nbsp; Active members only</p>
        </div>
      </div>
      <div class="header-stats">
  <div>Total &nbsp;<span class="stat-val"><?= $summary['total'] ?></span></div>
  <div>
    <span class="given-text"><i class="fa-solid fa-check" style="color:#4ade80;"></i> <?= $summary['given'] ?> Given</span>
    &nbsp;|&nbsp;
    <span class="missed-text"><i class="fa-solid fa-xmark" style="color:#f87171;"></i> <?= $summary['not_given'] ?> Not Given</span>
  </div>
</div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid">
    <div class="summary-card card-total" data-filter="all" onclick="setFilter('all',this)">
      <h6>Total Active</h6>
      <div class="val"><?= $total ?></div>
      <div class="sub"><?= htmlspecialchars($scope_label) ?></div>
    </div>
    <div class="summary-card card-given" data-filter="given" onclick="setFilter('given',this)">
      <h6>Given ✔</h6>
      <div class="val" style="color:var(--green);"><?= $given ?></div>
      <div class="sub"><?= $pct_given ?>% of active</div>
    </div>
    <div class="summary-card card-notgiven" data-filter="notgiven" onclick="setFilter('notgiven',this)">
      <h6>Not Given ✘</h6>
      <div class="val" style="color:var(--red);"><?= $not_given ?></div>
      <div class="sub"><?= $pct_not ?>% of active</div>
    </div>
  </div>

  <!-- Progress bar -->
  <?php if ($total > 0): ?>
  <div class="progress-wrap">
    <div class="progress-labels">
      <span style="color:var(--green);font-weight:700;">✔ Given <?= $pct_given ?>%</span>
      <span style="color:var(--red);font-weight:700;">✘ Not Given <?= $pct_not ?>%</span>
    </div>
    <div class="progress-track">
      <div class="progress-fill" style="width:<?= $pct_given ?>%;"></div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Filter Bar -->
  <div class="filter-bar">
    <input type="search" id="searchInput" placeholder="Search by name or ITS..." oninput="applyFilters()">
    <div class="tab-pills">
      <button class="tab-pill active"        data-tab="all"      onclick="setTab('all',this)">All</button>
      <button class="tab-pill pill-given"    data-tab="given"    onclick="setTab('given',this)">✅ Given</button>
      <button class="tab-pill pill-notgiven" data-tab="notgiven" onclick="setTab('notgiven',this)">❌ Not Given</button>
    </div>
    <span class="results-count" id="resultsCount"></span>
  </div>

  <!-- Legend -->
  <div class="legend-bar">
    <span class="legend-dot dot-given">Given</span>
    <span class="legend-dot dot-notgiven">Not Given</span>
  </div>

  <!-- Table -->
  <div class="qh-table-wrap">
    <div class="qh-table-scroll">
      <table class="qh-table">
        <thead>
          <tr>
            <th onclick="sortTable(0)">#<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(1)">ITS<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(2)">Name<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(3)">Sub-Sector<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(4)">Type<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(5)">Age<span class="sort-icon">↕</span></th>
            <th onclick="sortTable(6)">Husaini Scheme<span class="sort-icon">↕</span></th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>

</div>

<script>
const ALL_MEMBERS = <?= json_encode(array_map(function($m) {
  return [
    'its'       => (string)($m['ITS_ID']     ?? ''),
    'name'      => (string)($m['Full_Name']  ?? ''),
    'sub'       => (string)($m['Sub_Sector'] ?? ''),
    'type'      => (string)($m['HOF_FM_TYPE']?? ''),
    'age'       => (int)   ($m['Age']        ?? 0),
    'has_given' => (bool)  ($m['has_given']  ?? false),
  ];
}, $members), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

let currentTab  = 'all';
let currentSort = { col: -1, dir: 1 };

function getFiltered() {
  const kw = document.getElementById('searchInput').value.toLowerCase().trim();
  return ALL_MEMBERS
    .filter(m => {
      if (currentTab === 'given')    return m.has_given;
      if (currentTab === 'notgiven') return !m.has_given;
      return true;
    })
    .filter(m => !kw || m.its.toLowerCase().includes(kw) || m.name.toLowerCase().includes(kw));
}

function sortTable(col) {
  currentSort.dir = (currentSort.col === col) ? -currentSort.dir : 1;
  currentSort.col = col;
  renderTable();
}

function sortValue(m, col) {
  switch(col) {
    case 1: return m.its;
    case 2: return m.name.toLowerCase();
    case 3: return m.sub.toLowerCase();
    case 4: return m.type;
    case 5: return m.age;
    case 6: return m.has_given ? 0 : 1;
    default: return 0;
  }
}

function renderTable() {
  let rows = getFiltered();
  if (currentSort.col >= 0) {
    rows.sort((a, b) => {
      const av = sortValue(a, currentSort.col), bv = sortValue(b, currentSort.col);
      return typeof av === 'number'
        ? currentSort.dir * (av - bv)
        : currentSort.dir * String(av).localeCompare(String(bv));
    });
  }

  document.getElementById('resultsCount').textContent = rows.length + ' member(s)';
  const tbody = document.getElementById('tableBody');

  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-user-slash"></i>No members match this filter.</div></td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map((m, i) => {
    const rowClass     = m.has_given ? 'row-given' : 'row-notgiven';
    const husainiBadge = m.has_given
      ? `<span class="badge-husaini badge-given"><i class="fa-solid fa-check-circle"></i> Given</span>`
      : `<span class="badge-husaini badge-notgiven"><i class="fa-solid fa-times-circle"></i> Not Given</span>`;
    const typeBadge = m.type === 'HOF'
      ? `<span class="badge-hof">HOF</span>`
      : `<span class="badge-fm">FM</span>`;

    return `<tr class="${rowClass}">
      <td>${i + 1}</td>
      <td style="font-family:monospace;font-size:.8rem;">${escHtml(m.its)}</td>
      <td style="font-weight:600;">${escHtml(m.name)}</td>
      <td>${escHtml(m.sub)}</td>
      <td>${typeBadge}</td>
      <td>${m.age || '—'}</td>
      <td>${husainiBadge}</td>
    </tr>`;
  }).join('');
}

function setTab(tab, btn) {
  currentTab = tab;
  document.querySelectorAll('.tab-pill').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  document.querySelectorAll('.summary-card').forEach(c =>
    c.classList.toggle('active-filter', c.dataset.filter === tab)
  );
  renderTable();
}

function setFilter(tab) {
  if (currentTab === tab) {
    setTab('all', document.querySelector('.tab-pill[data-tab="all"]'));
    return;
  }
  setTab(tab, document.querySelector(`.tab-pill[data-tab="${tab}"]`));
}

function applyFilters() { renderTable(); }

function escHtml(s) {
  return s ? String(s).replace(/[&<>"]/g, c =>
    ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])) : '';
}

document.addEventListener('DOMContentLoaded', renderTable);
</script>