<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --green-border:#bbf7d0;
    --red:         #b91c1c;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #1d4ed8;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #b45309;
    --amber-bg:    #fffbeb;
    --purple:      #6d28d9;
    --purple-bg:   #f5f3ff;
    --radius-sm:   8px;
    --radius:      14px;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:      0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:   0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap {
    position: relative;
    display: flex; align-items: center; justify-content: center;
    min-height: 44px; margin-bottom: 4px;
  }
  .btn-back-nav {
    position: absolute; left: 0;
    width: 38px; height: 38px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface); color: var(--text-2); font-size: 14px;
    text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s;
  }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }

  .page-heading {
    font-family: 'Literata', Georgia, serif;
    color: var(--gold); font-size: 1.5rem; font-weight: 600;
    letter-spacing: -.3px; margin: 0; text-align: center;
  }
  .page-sub {
    font-size: 0.72rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: var(--text-3);
    text-align: center; margin-top: 4px;
  }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 18px 0 22px; }

  /* ── Toolbar ── */
  .toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
  .search-wrap { position: relative; flex: 1; }
  .search-icon-left {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--gold); font-size: 13px; pointer-events: none;
  }
  .search-input {
    width: 100%; padding: 10px 14px 10px 38px;
    border: 1.5px solid var(--border); border-radius: 50px;
    background: var(--surface);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.875rem; font-weight: 500; color: var(--text-1);
    box-shadow: var(--shadow-sm); outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  .search-input::placeholder { color: var(--text-3); font-weight: 400; }
  .search-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,134,11,0.12); }

  /* result count chip */
  .result-chip {
    font-size: 0.7rem; font-weight: 700; color: var(--text-3);
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: 40px; padding: 4px 12px;
    white-space: nowrap; display: none;
  }
  .result-chip.visible { display: inline-block; }

  /* ── Table card ── */
  .table-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden; margin-bottom: 40px;
  }
  .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

  /* ── Table ── */
  .raza-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
  .raza-table thead tr { background: var(--surface-2); border-bottom: 1.5px solid var(--border); }
  .raza-table thead th {
    padding: 12px 16px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: .6px;
    text-transform: uppercase; color: var(--text-3);
    white-space: nowrap; border: none;
  }
  .raza-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
  .raza-table tbody tr:last-child { border-bottom: none; }
  .raza-table tbody tr:hover { background: #fdfbf5; }
  .raza-table td { padding: 14px 16px; vertical-align: middle; border: none; color: var(--text-2); }

  /* date */
  .td-date .date-main { font-size: 0.82rem; font-weight: 600; color: var(--text-1); }
  .td-date .date-sub  { font-size: 0.7rem; color: var(--text-3); margin-top: 2px; }

  /* raza type + name */
  .td-raza { font-weight: 600; color: var(--text-1); font-size: 0.83rem; }
  .td-name { font-size: 0.82rem; color: var(--text-2); }

  /* ── Status badge ── */
  .status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 40px;
    font-size: 0.68rem; font-weight: 700; letter-spacing: .3px; white-space: nowrap;
  }
  .s-pending        { background: var(--blue-bg);   color: var(--blue);   border: 1px solid var(--blue-border); }
  .s-recommended    { background: var(--amber-bg);  color: var(--amber);  border: 1px solid #fde68a; }
  .s-approved       { background: var(--green-bg);  color: var(--green);  border: 1px solid var(--green-border); }
  .s-rejected       { background: var(--red-bg);    color: var(--red);    border: 1px solid var(--red-border); }
  .s-not-recommended{ background: var(--purple-bg); color: var(--purple); border: 1px solid #ddd6fe; }

  /* ── Approval pipeline ── */
  .approval-pipeline { display: flex; flex-direction: column; gap: 5px; margin-top: 8px; }
  .pipeline-step { display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 600; color: var(--text-3); }
  .step-dot {
    width: 18px; height: 18px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 9px; flex-shrink: 0;
  }
  .step-pending  { background: #fef9c3; color: #854d0e; border: 1.5px solid #fde68a; }
  .step-done     { background: var(--green-bg); color: var(--green); border: 1.5px solid var(--green-border); }
  .step-rejected { background: var(--red-bg); color: var(--red); border: 1.5px solid var(--red-border); }

  /* ── Chat button ── */
  .btn-chat {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: var(--radius-sm);
    background: var(--blue-bg); border: 1.5px solid var(--blue-border);
    color: var(--blue);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.75rem; font-weight: 700;
    text-decoration: none; transition: all .15s; white-space: nowrap;
  }
  .btn-chat:hover { background: var(--blue); color: #fff; border-color: var(--blue); text-decoration: none; }
  .chat-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 18px; height: 18px; background: var(--blue); color: #fff;
    border-radius: 50%; font-size: 0.6rem; font-weight: 800;
  }
  .btn-chat:hover .chat-badge { background: rgba(255,255,255,0.3); }

  /* ── Action buttons ── */
  .action-wrap { display: flex; gap: 6px; align-items: center; }
  .btn-action {
    width: 32px; height: 32px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface); font-size: 12px; cursor: pointer; transition: all .15s;
  }
  .btn-edit   { color: var(--blue); }
  .btn-edit:hover   { background: var(--blue-bg); border-color: var(--blue-border); }
  .btn-delete { color: var(--red); }
  .btn-delete:hover { background: var(--red-bg); border-color: var(--red-border); }

  /* ── Empty state ── */
  .empty-state { padding: 56px 24px; text-align: center; }
  .empty-state .fa { font-size: 2.5rem; color: var(--border); margin-bottom: 12px; display: block; }
  .empty-state p { font-size: 0.88rem; color: var(--text-3); font-weight: 500; margin: 0; }

  /* ── Mobile card view ── */
  @media (max-width: 640px) {
    .raza-table thead { display: none; }
    .raza-table tbody tr {
      display: block; background: var(--surface);
      margin: 0 0 1px; border-bottom: 1px solid var(--border) !important;
    }
    .raza-table td {
      display: flex; justify-content: space-between; align-items: flex-start;
      gap: 12px; padding: 10px 16px;
      border-bottom: 1px solid var(--border); font-size: 0.82rem;
    }
    .raza-table td:last-child { border-bottom: none; }
    .raza-table td::before {
      content: attr(data-label);
      font-size: 0.65rem; font-weight: 700; letter-spacing: .5px;
      text-transform: uppercase; color: var(--text-3);
      flex-shrink: 0; min-width: 80px; padding-top: 2px;
    }
    .page-heading { font-size: 1.2rem; }
    .toolbar { flex-wrap: wrap; }
    .result-chip { width: 100%; text-align: center; }
  }
</style>

<div class="margintopcontainer">
  <div class="container pt-5">

    <!-- ── Header ── -->
    <div class="page-header-wrap">
      <a href="<?php echo base_url('accounts/home'); ?>" class="btn-back-nav">
        <i class="fa fa-arrow-left"></i>
      </a>
      <h1 class="page-heading">My Raza Submissions</h1>
    </div>
    <p class="page-sub">All submitted applications</p>

    <hr class="section-divider">

    <!-- ── Toolbar ── -->
    <div class="toolbar">
      <div class="search-wrap">
        <i class="fa fa-search search-icon-left"></i>
        <input type="search" class="search-input" id="razaSearchInput" placeholder="Search requests…" autocomplete="off">
      </div>
      <span class="result-chip" id="resultChip"></span>
    </div>

    <!-- ── Table card ── -->
    <div class="table-card">
      <div class="table-responsive">
        <table class="raza-table" id="razaTable">
          <thead>
            <tr>
              <th>Created</th>
              <th>Raza For</th>
              <th>Name</th>
              <th>Status</th>
              <th>Chat</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($raza)): ?>
            <tr>
              <td colspan="6" style="padding:0; border:none;">
                <div class="empty-state">
                  <i class="fa fa-inbox"></i>
                  <p>No Raza submissions found</p>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($raza as $r): ?>
            <tr>
              <td data-label="Created" class="td-date">
                <div class="date-main"><?= date('d M Y', strtotime($r['time-stamp'])) ?></div>
                <div class="date-sub"><?= date('D @ g:i a', strtotime($r['time-stamp'])) ?></div>
              </td>

              <td data-label="Raza For" class="td-raza"><?= htmlspecialchars($r['razaType']) ?></td>

              <td data-label="Name" class="td-name"><?= htmlspecialchars($r['user_name']) ?></td>

              <td data-label="Status">
                <?php
                  $statusMap = [
                    0 => ['s-pending',         'Pending'],
                    1 => ['s-recommended',     'Recommended'],
                    2 => ['s-approved',        'Approved'],
                    3 => ['s-rejected',        'Rejected'],
                    4 => ['s-not-recommended', 'Not Recommended'],
                  ];
                  [$cls, $lbl] = $statusMap[$r['status']] ?? ['s-pending', 'Pending'];
                ?>
                <span class="status-badge <?= $cls ?>"><?= $lbl ?></span>
                <div class="approval-pipeline">
                  <?php
                    $cs = (int)$r['coordinator-status'];
                    $cDot  = $cs === 1 ? 'step-done' : ($cs === 2 ? 'step-rejected' : 'step-pending');
                    $cIcon = $cs === 1 ? 'fa-check' : ($cs === 2 ? 'fa-times' : 'fa-clock-o');
                  ?>
                  <div class="pipeline-step">
                    <span class="step-dot <?= $cDot ?>"><i class="fa <?= $cIcon ?>"></i></span>
                    Jamat
                  </div>
                  <?php
                    $js = (int)$r['Janab-status'];
                    $jDot  = $js === 1 ? 'step-done' : ($js === 2 ? 'step-rejected' : 'step-pending');
                    $jIcon = $js === 1 ? 'fa-check' : ($js === 2 ? 'fa-times' : 'fa-clock-o');
                  ?>
                  <div class="pipeline-step">
                    <span class="step-dot <?= $jDot ?>"><i class="fa <?= $jIcon ?>"></i></span>
                    Amil Saheb
                  </div>
                </div>
              </td>

              <td data-label="Chat">
                <a href="<?= base_url('Accounts/chat/') . $r['id'] ?>" class="btn-chat">
                  <i class="fa fa-comments"></i> Chat
                  <?php if (!empty($r['chat_count']) && $r['chat_count'] > 0): ?>
                    <span class="chat-badge"><?= $r['chat_count'] ?></span>
                  <?php endif; ?>
                </a>
              </td>

              <td data-label="Action">
                <?php if ($r['coordinator-status'] == 0): ?>
                <div class="action-wrap">
                  <button class="btn-action btn-edit"
                    title="<?= $r['razaType_id'] == 2 ? 'View' : 'Edit' ?>"
                    onclick="redirectto('accounts/edit_raza/<?= $r['id'] ?>')">
                    <i class="fa <?= $r['razaType_id'] == 2 ? 'fa-eye' : 'fa-pencil' ?>"></i>
                  </button>
                  <button class="btn-action btn-delete"
                    title="Delete"
                    onclick="redirecttodelete('accounts/DeleteRaza/<?= $r['id'] ?>')">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
                <?php else: ?>
                  <span style="font-size:0.7rem; color:var(--text-3);">—</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script>
  function redirecttodelete(location) {
    if (confirm('Do you want to delete this Raza?')) {
      window.location.href = '<?php echo base_url() ?>' + location;
    }
  }
  function redirectto(location) {
    window.location.href = '<?php echo base_url() ?>' + location;
  }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    var allRows;

    document.getElementById('razaSearchInput').addEventListener('keyup', function () {
      var filter = this.value.toLowerCase().trim();
      var table  = document.getElementById('razaTable');
      var rows   = table.getElementsByTagName('tr');
      var visible = 0;

      for (var i = 1; i < rows.length; i++) {
        var tds   = rows[i].getElementsByTagName('td');
        var found = false;
        for (var j = 0; j < tds.length; j++) {
          if (tds[j]) {
            var txt = tds[j].textContent || tds[j].innerText;
            if (txt.toLowerCase().indexOf(filter) > -1) { found = true; break; }
          }
        }
        rows[i].style.display = found ? '' : 'none';
        if (found) visible++;
      }

      var chip = document.getElementById('resultChip');
      if (filter) {
        chip.textContent = visible + ' result' + (visible !== 1 ? 's' : '');
        chip.classList.add('visible');
      } else {
        chip.classList.remove('visible');
      }
    });
  });
</script>