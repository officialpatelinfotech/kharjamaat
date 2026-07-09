<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap');

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
    --radius-sm:   8px;
    --radius:      12px;
    --radius-lg:   16px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.05);
    --shadow:      0 4px 16px rgba(184,134,11,0.06);
    --shadow-lg:   0 10px 30px rgba(184,134,11,0.12);
  }

  .margintopcontainer {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    color: var(--text-1);
  }

  /* Page title & Back Button */
  .page-title-elegant {
    font-family: 'Literata', Georgia, serif !important;
    color: var(--gold-deep) !important;
    font-size: 1.8rem !important;
    font-weight: 600 !important;
    letter-spacing: -.5px !important;
    margin: 0 !important;
  }
  .btn-back-elegant {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    box-shadow: var(--shadow-sm) !important;
    transition: all .2s !important;
    text-decoration: none !important;
    padding: 0 !important;
  }
  .btn-back-elegant:hover {
    background: var(--gold-muted) !important;
    border-color: var(--gold) !important;
    color: var(--gold-deep) !important;
    transform: translateX(-3px) !important;
  }

  /* Dashboard Metric Cards */
  .better-dashboard {
    display: flex;
    justify-content: center;
    gap: 24px;
    margin: 30px auto;
    flex-wrap: wrap;
  }
  .better-card {
    background: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: 18px !important;
    box-shadow: var(--shadow) !important;
    padding: 20px 24px !important;
    min-width: 240px !important;
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    transition: all 0.25s ease !important;
    cursor: pointer !important;
  }
  .better-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: var(--shadow-lg) !important;
    border-color: var(--gold-light) !important;
  }
  .better-card .better-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1.5rem !important;
    box-shadow: none !important;
    margin-bottom: 0 !important;
  }
  .better-card.pending .better-icon {
    background: #fffbeb !important;
    color: #d97706 !important;
    border: 1px solid #fde68a !important;
  }
  .better-card.completed .better-icon {
    background: #ecfdf5 !important;
    color: #059669 !important;
    border: 1px solid #bbf7d0 !important;
  }
  .better-card.processing .better-icon {
    background: #eff6ff !important;
    color: #2563eb !important;
    border: 1px solid #bfdbfe !important;
  }
  .better-card .better-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .better-label {
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    color: var(--text-3) !important;
  }
  .better-value {
    font-size: 1.8rem !important;
    font-weight: 800 !important;
    color: var(--text-1) !important;
    line-height: 1.2 !important;
  }

  /* Filters */
  .filters-wrapper {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: var(--shadow);
  }
  .select {
    background-color: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    font-size: 0.85rem !important;
    color: var(--text-2) !important;
    height: 42px !important;
    padding: 0 32px 0 12px !important;
    transition: all .2s !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 600 !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 12px center !important;
  }
  .select:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12) !important;
    outline: none !important;
  }
  .options {
    background-color: var(--surface);
  }

  /* Refresh Button */
  .btn-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 0.85rem !important;
    height: 42px !important;
    padding: 0 24px !important;
    border-radius: var(--radius-sm) !important;
    box-shadow: 0 2px 8px rgba(16,185,129,0.15) !important;
    transition: all .2s !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
  .btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(16,185,129,0.25) !important;
  }

  /* Table styling */
  .table-responsive {
    transform: rotateX(180deg);
  }
  .table-container {
    transform: rotateX(180deg);
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
  }
  .table {
    margin-bottom: 0 !important;
  }
  .table thead th {
    background: var(--surface-2);
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 18px;
    vertical-align: middle;
  }
  .table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f0ece0;
    color: var(--text-2);
    font-size: 0.85rem;
    vertical-align: middle;
  }
  .table tbody tr:last-child td {
    border-bottom: none;
  }
  .table tbody tr:hover {
    background: #fdfbf5;
  }

  /* Chat button */
  .chat-button {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    color: #fff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px;
    box-shadow: 0 2px 6px rgba(59,130,246,0.15) !important;
    transition: all 0.2s !important;
    text-decoration: none !important;
    margin: 0 auto;
  }
  .chat-button:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 10px rgba(59,130,246,0.25) !important;
    color: #fff !important;
    text-decoration: none !important;
  }
  .chat-count {
    background: #ef4444 !important;
    color: #fff !important;
    font-size: 0.7rem !important;
    font-weight: 800 !important;
    width: 18px !important;
    height: 18px !important;
    line-height: 18px !important;
    border-radius: 50% !important;
    display: inline-block !important;
    text-align: center !important;
  }

  /* View link and Actions button */
  .btn-primary.remove-form-row, .view-link .btn-primary {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep)) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(184,134,11,0.15) !important;
    transition: all 0.2s !important;
  }
  .btn-primary.remove-form-row:hover, .view-link .btn-primary:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 10px rgba(184,134,11,0.25) !important;
    color: #fff !important;
    text-decoration: none !important;
  }
  .btn-danger.remove-form-row {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(239,68,68,0.15) !important;
    transition: all 0.2s !important;
  }
  .btn-danger.remove-form-row:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 10px rgba(239,68,68,0.25) !important;
  }
  .btn-warning.remove-form-row {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(245,158,11,0.15) !important;
    transition: all 0.2s !important;
  }
  .btn-warning.remove-form-row:hover {
    background: linear-gradient(135deg, #d97706, #b45309) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 10px rgba(245,158,11,0.25) !important;
  }

  /* Modals */
  .query-form {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: var(--surface);
    border: 1.5px solid var(--border);
    padding: 24px;
    width: 450px;
    max-height: calc(100vh - 120px);
    overflow-y: auto;
    display: none;
    z-index: 1050;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
  }
  .query-form label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--text-3);
    margin-bottom: 6px;
  }
  .query-form .form-control {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
  }

  /* Sort icons & Datatable styling */
  .sort-icons {
    display: inline-block;
    margin-left: 6px;
    cursor: pointer;
    vertical-align: middle;
    opacity: 0.6;
  }
  .sort-icons:hover {
    opacity: 1;
  }
  .sort-icons i {
    display: block;
    font-size: 9px;
    line-height: 1;
    color: var(--text-2);
  }
  
  .dataTables_wrapper .dataTables_length {
    margin-bottom: 16px;
    color: var(--text-2) !important;
    font-weight: 600;
  }
  .dataTables_wrapper .dataTables_length select {
    background-color: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    font-size: 0.85rem !important;
    padding: 6px 24px 6px 10px !important;
    height: 38px !important;
    font-weight: 600 !important;
    color: var(--text-2) !important;
    outline: none !important;
  }
  .dataTables_wrapper .dataTables_filter {
    margin-bottom: 16px;
    color: var(--text-2) !important;
    font-weight: 600;
  }
  .dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    padding: 6px 12px !important;
    font-size: 0.88rem !important;
    height: 38px !important;
    margin-left: 8px !important;
    transition: all .2s !important;
    color: var(--text-1) !important;
  }
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12) !important;
    outline: none !important;
  }
  .dataTables_wrapper .dataTables_info {
    color: var(--text-3) !important;
    font-size: 0.85rem;
    font-weight: 500;
    margin-top: 14px;
  }
  .dataTables_wrapper .dataTables_paginate {
    margin-top: 14px;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    border-radius: var(--radius-sm) !important;
    padding: 6px 12px !important;
    margin: 0 2px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep)) !important;
    color: #fff !important;
    border-color: var(--gold-deep) !important;
  }

  /* Status Indicators */
  .status div {
    font-size: 0.88rem;
    font-weight: 700;
    margin-bottom: 4px;
  }
  .status ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .status li div {
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
</style>
<div id="toast-message" class="toast-message">
  Successfull
</div>
<div class="container-fluid px-md-5 margintopcontainer pt-5 pb-5">
  <!-- Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="<?php echo base_url("amilsaheb"); ?>" class="btn-back-elegant" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 class="page-title-elegant"><?php echo $umoor ?></h1>
    <div style="width: 42px;"></div>
  </div>

  <div class="ml-1 mr-1">
    <div class="container-fluid">
      <div class="dashboard-container better-dashboard">
        <div class="better-card pending">
          <div class="better-icon"><i class="fas fa-clock"></i></div>
          <div class="better-content">
            <div class="better-label">Pending Tasks</div>
            <div class="better-value"><?php echo $janab_status_0_count ?></div>
          </div>
        </div>
        <div class="better-card completed">
          <div class="better-icon"><i class="fas fa-check-circle"></i></div>
          <div class="better-content">
            <div class="better-label">Completed Tasks</div>
            <div class="better-value"><?php echo $janab_status_1_count ?></div>
          </div>
        </div>
        <div class="better-card processing">
          <div class="better-icon"><i class="fas fa-sync"></i></div>
          <div class="better-content">
            <div class="better-label">Processing Tasks</div>
            <div class="better-value"><?php echo $coordinator_status_0_count ?></div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="d-flex justify-content-end mb-3 mt-4">
      <button type="button" class="btn btn-success" onclick="refresh();"><i class="fa-solid fa-arrows-rotate mr-2"></i> Refresh</button>
    </div>
    
    <div class="filters-wrapper d-flex flex-wrap align-items-center justify-content-start" style="gap: 16px;">
      <select onchange="updateTable();" name="filter" class="select" id="filter">
        <option value="" selected disabled>Filter</option>
        <option class="options" value="pending">Pending</option>
        <option class="options" value="approved">Approved</option>
        <option class="options" value="recommended">Recommended</option>
        <option class="options" value="notrecommended">Not Recommended</option>
        <option class="options" value="rejected">Rejected</option>
        <option class="options" value="clear">Clear</option>
      </select>
      <?php if (empty($umoor) || $umoor !== '12 Umoor Raza Applications'): ?>
        <?php if (empty($umoor) || (stripos($umoor, 'Kaaraj') === false && $umoor !== '12 Umoor Raza Applications')): ?>
          <select onchange="updateTable();" name="miqaat_filter" class="select" id="miqaat_filter">
            <option value="" selected>All Miqaat Types</option>
            <option class="options" value="Shehrullah">Shehrullah</option>
            <option class="options" value="Ashara">Ashara</option>
            <option class="options" value="General">General</option>
            <option class="options" value="Ladies">Ladies</option>
          </select>
        <?php elseif ($umoor === '12 Umoor Raza Applications'): ?>
          <?php
          // Build distinct umoor categories from $razatype
          $umoors = [];
          foreach ($razatype as $rt) {
            if (!empty($rt['umoor'])) $umoors[$rt['umoor']] = $rt['umoor'];
          }
          ?>
          <select onchange="updateTable();" name="umoor_filter" class="select" id="umoor_filter">
            <option value="" selected>All Umoor Types</option>
            <?php foreach ($umoors as $u): ?>
              <option value="<?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <!-- Kaaraj view: miqaat filter intentionally hidden -->
        <?php endif; ?>
      <?php else: ?>
        <?php
        // Build distinct umoor categories from $razatype
        $umoors = [];
        foreach ($razatype as $rt) {
          if (!empty($rt['umoor'])) $umoors[$rt['umoor']] = $rt['umoor'];
        }
        ?>
        <select onchange="updateTable();" name="umoor_filter" class="select" id="umoor_filter">
          <option value="" selected>All Umoor Types</option>
          <?php foreach ($umoors as $u): ?>
            <option value="<?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($u, ENT_QUOTES, 'UTF-8'); ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>

      <?php
      // Build a list of Hijri event years from $raza (miqaat_details or razadata)
      $ci = &get_instance();
      $ci->load->model('HijriCalendar');
      $hijri_years = [];
      foreach ($raza as $r) {
        $d = '';
        if (!empty($r['miqaat_details'])) {
          $md = json_decode($r['miqaat_details'], true);
          if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
        }
        if (empty($d) && !empty($r['razadata'])) {
          $rd = json_decode($r['razadata'], true);
          if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
        }
        if (!empty($d)) {
          $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
          if (!empty($parts) && !empty($parts['hijri_year'])) {
            $hijri_years[$parts['hijri_year']] = $parts['hijri_year'];
          }
        }
      }
      krsort($hijri_years);
      ?>

      <select onchange="updateTable();" name="year_filter" class="select" id="year_filter">
        <option value="" selected>All Hijri Years</option>
        <?php foreach ($hijri_years as $y): ?>
          <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
        <?php endforeach; ?>
      </select>

      <select onchange="updateTable();" name="sort" class="select" id="sort">
        <option value="" selected disabled>Sort</option>
        <option class="options" value="0">Name(A-Z)</option>
        <option class="options" value="1">Name(Z-A)</option>
        <option class="options" value="2">Event Date (New>Old)</option>
        <option class="options" value="3">Event Date (Old>New)</option>
        <option class="options" value="4">Create Date (New>Old)</option>
        <option class="options" value="5">Create Date (Old>New)</option>
        <option class="options" value="6">Clear</option>
      </select>
    </div>
  </div>
  <div class="table-responsive mt-4 mb-5">
    <div class="table-container">
      <!-- <?php echo "<pre>";
            print_r($raza);
            echo "</pre>"; ?> -->
      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th class="sno">S.No.
              <span class="sort-icons" onclick="sortTable(0)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
            <th class="name">Name
              <span class="sort-icons" onclick="sortTable(1)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
            <th class="raza">Raza For
              <span class="sort-icons" onclick="sortTable(2)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
            <th class="eventdate">Event Date
              <span class="sort-icons" onclick="sortTable(3)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
            <th class="chat">Chat</th>
            <th class="approval_status">Status
              <span class="sort-icons" onclick="sortTable(5)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
            <th class="action">Action</th>
            <th class="created">Created
              <span class="sort-icons" onclick="sortTable(8)">
                <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
              </span>
            </th>
          </tr>
        </thead>
        <tbody id="datatable">
          <?php
          foreach ($raza as $key => $r) {
            // compute hijri year for this row
            $hijri_year_attr = '';
            $d = '';
            if (!empty($r['miqaat_details'])) {
              $md = json_decode($r['miqaat_details'], true);
              if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
            }
            if (empty($d) && !empty($r['razadata'])) {
              $rd = json_decode($r['razadata'], true);
              if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
            }
            if (!empty($d)) {
              $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
              if (!empty($parts) && !empty($parts['hijri_year'])) $hijri_year_attr = $parts['hijri_year'];
            }
          ?>
            <tr data-hijri-year="<?php echo $hijri_year_attr; ?>">
              <td>
                <?php echo $key + 1 ?>
              </td>
              <td>
                <?php
                // If this request is a Fala ni Niyaz (FNN), show that label instead of the user's name
                $is_fnn = false;
                if (!empty($r['miqaat_details'])) {
                  $miq = json_decode($r['miqaat_details'], true);
                  if (is_array($miq)) {
                    $miq_type = strtolower($miq['type'] ?? '');
                    $miq_name = strtolower($miq['name'] ?? '');
                    if (strpos($miq_type, 'fnn') !== false || (strpos($miq_type, 'fala') !== false && (strpos($miq_type, 'niyaz') !== false || strpos($miq_type, 'niaz') !== false))) {
                      $is_fnn = true;
                    }
                    if (!$is_fnn && (strpos($miq_name, 'fnn') !== false || (strpos($miq_name, 'fala') !== false && (strpos($miq_name, 'niyaz') !== false || strpos($miq_name, 'niaz') !== false)))) {
                      $is_fnn = true;
                    }
                    // check top-level assign_type
                    if (!$is_fnn && !empty($miq['assign_type'])) {
                      $atop = strtolower($miq['assign_type']);
                      if (strpos($atop, 'fnn') !== false || (strpos($atop, 'fala') !== false && (strpos($atop, 'niyaz') !== false || strpos($atop, 'niaz') !== false))) {
                        $is_fnn = true;
                      }
                    }
                    // check assignments entries
                    if (!$is_fnn && !empty($miq['assignments']) && is_array($miq['assignments'])) {
                      foreach ($miq['assignments'] as $as) {
                        $asz = strtolower((string)($as['assign_type'] ?? $as['type'] ?? ''));
                        if (strpos($asz, 'fnn') !== false || (strpos($asz, 'fala') !== false && (strpos($asz, 'niyaz') !== false || strpos($asz, 'niaz') !== false))) {
                          $is_fnn = true;
                          break;
                        }
                      }
                    }
                  }
                }
                if (!$is_fnn && isset($r['razaType'])) {
                  $rt = strtolower($r['razaType']);
                  if (strpos($rt, 'fnn') !== false || (strpos($rt, 'fala') !== false && (strpos($rt, 'niyaz') !== false || strpos($rt, 'niaz') !== false))) {
                    $is_fnn = true;
                  }
                }

                if ($is_fnn) {
                  echo 'Fala ni Niyaz';
                } else {
                  echo htmlspecialchars($r['user_name'], ENT_QUOTES, 'UTF-8');
                }
                ?>
              </td>
              <td>
                <?php echo $r['razaType'] ?>
              </td>
              <td>
                <?php $temp = json_decode($r['razadata'], true);
                if (!empty($temp['date'])) {
                  echo date('D, d M ', strtotime($temp['date']));
                } ?>
              </td>
              <td>
                <?php $chat_count = !empty($r['chat_count']) ? $r['chat_count'] : ''; ?>
                <a href="<?php echo base_url('Accounts/chat/') . $r['id'] . '/amilsaheb'; ?>" class="chat-button">
                  Chat<?php echo $chat_count ? '<div class="chat-count">' . $chat_count . '</div>' : ''; ?>
                </a>
              </td>
              <td class="status">
                <div class="text-left">
                  <ul>
                    <?php if ($r['status'] == 0) {
                      echo '<div><strong style="color: orange;">Pending</strong></div>';
                    } elseif ($r['status'] == 1) {
                      echo '<div><strong style="color: blue;">Recommended</strong></div>';
                    } elseif ($r['status'] == 2) {
                      echo '<div><strong style="color: limegreen;">Approved</strong></div>';
                    } elseif ($r['status'] == 3) {
                      echo '<div><strong style="color: red;">Rejected</strong></div>';
                    } elseif ($r['status'] == 4) {
                      echo '<div><strong style="color: blue;">Not Recommended</strong></div>';
                    } ?>
                    <li>
                      <?php if ($r['coordinator-status'] == 0) {
                        echo '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                      } elseif ($r['coordinator-status'] == 1) {
                        echo '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                      } elseif ($r['coordinator-status'] == 2) {
                        echo '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                      } ?>
                    </li>
                    <li>
                      <?php if ($r['Janab-status'] == 0) {
                        echo '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                      } elseif ($r['Janab-status'] == 1) {
                        echo '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                      } elseif ($r['Janab-status'] == 2) {
                        echo '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                      } ?>
                    </li>

                  </ul>
                </div>
              </td>
              <td>
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-check"></i></button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
                <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="deleteRaza(<?php echo $r['id'] ?>);"><i class="fa fa-trash"></i></button>
              </td>
              <td>
                <?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])) ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
        <tfoot></tfoot>
      </table>
    </div>
  </div>
</div>
</div>
<div id="product-overlay"></div>
<div id="approve-form" class="query-form">
  <table id="details-table-approve" class="table"></table>
  <form class="approve" id="approve">
    <div class="form-group">
      <label for="remark" class="form-label">Remark (optional)</label>
      <textarea class="form-control" name="remark" id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button class="btn btn-danger w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Approve</button>
    </div>
  </form>
</div>
<div id="reject-form" class="query-form">
  <table id="details-table-reject" class="table"></table>
  <form class="reject" id="reject">
    <div class="form-group">
      <label for="remark" class="form-label">Remark *</label>
      <textarea class="form-control" name="remark" required id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
    </div>
    <div class="submit">
      <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
      <button type="submit" class="btn btn-danger w100percent-xs mbm-xs">Reject</button>
    </div>
  </form>
</div>
<div id="show-form" class="query-form">
  <table id="details-table-show" class="table"></table>
  <form class="reject" id="show">
    <div class="submit">
      <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Close</button>
    </div>
  </form>
</div>
<script>
  // Build razas as a proper JSON array to prevent parsing issues
  // Build razas array with hijri_parts for each entry
  let razas = [
    <?php
    foreach ($raza as $r) {
      $d = '';
      if (!empty($r['miqaat_details'])) {
        $md = json_decode($r['miqaat_details'], true);
        if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
      }
      if (empty($d) && !empty($r['razadata'])) {
        $rd = json_decode($r['razadata'], true);
        if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
      }
      $hijri_parts = null;
      if (!empty($d)) {
        $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
        if (!empty($parts['hijri_year']) && !empty($parts['hijri_month']) && !empty($parts['hijri_day'])) {
          $hijri_parts = [
            'year' => $parts['hijri_year'],
            'month' => $parts['hijri_month'],
            'day' => $parts['hijri_day']
          ];
        }
      }
      $r['hijri_parts'] = $hijri_parts;
      echo json_encode($r, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ",\n";
    }
    ?>
  ];
  let hijriMap = <?php
                  // build map of raza id => hijri year for JS
                  $hmap = [];
                  foreach ($raza as $r) {
                    $d = '';
                    if (!empty($r['miqaat_details'])) {
                      $md = json_decode($r['miqaat_details'], true);
                      if (is_array($md) && !empty($md['date'])) $d = substr($md['date'], 0, 10);
                    }
                    if (empty($d) && !empty($r['razadata'])) {
                      $rd = json_decode($r['razadata'], true);
                      if (is_array($rd) && !empty($rd['date'])) $d = substr($rd['date'], 0, 10);
                    }
                    $hyear = '';
                    if (!empty($d)) {
                      $parts = $ci->HijriCalendar->get_hijri_parts_by_greg_date($d);
                      if (!empty($parts) && !empty($parts['hijri_year'])) $hyear = $parts['hijri_year'];
                    }
                    $hmap[$r['id']] = $hyear;
                  }
                  echo json_encode($hmap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                  ?>;

  // Expose Hijri month names for JS rendering of hijri date
  <?php
    $ci = isset($ci) ? $ci : get_instance();
    $ci->load->model('HijriCalendar');
    $hijri_months = $ci->HijriCalendar->get_hijri_month();
    $hijri_months_js = [];
    foreach ($hijri_months as $m) {
      $hijri_months_js[$m['id']] = $m['hijri_month'];
    }
  ?>
  window.hijriMonths = <?php echo json_encode($hijri_months_js, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

  function show_raza(id) {
    document.getElementById("show-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'show')
  }

  function approve_raza(id) {
    document.getElementById("approve-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("approve").appendChild(newInput);
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'approve')
  }

  function reject_raza(id) {
    document.getElementById("reject-form").style.display = "block";
    document.getElementById("product-overlay").style.display = "block";
    const newInput = document.createElement("div");
    newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
    document.getElementById("reject").appendChild(newInput);
    let raza = razas.find(e => e.id == id)
    otherdetails(raza, 'reject')
  }

  function clearForm() {
    $('#approve')[0].reset();
    $('#reject')[0].reset();
    document.getElementById("approve-form").style.display = "none";
    document.getElementById("reject-form").style.display = "none";
    document.getElementById("show-form").style.display = "none";
    document.getElementById("product-overlay").style.display = "none";
    event.preventDefault();
  }

  function otherdetails(raza, action) {
    var table = document.getElementById(`details-table-${action}`);
    table.innerHTML = "";
    var tablehead = document.createElement('thead')
    tablehead.innerHTML = `<thead><tr><th scope="col" colspan="2" class="text-center">Raza Details</th></tr></thead>`
    table.appendChild(tablehead)
    var tablebody = document.createElement('tbody');
    let tbodydata = "";
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let razadata = parseMaybe(raza.razadata);
    let razafields = parseMaybe(raza.razafields);
    let raza_type_id = parseInt(raza.razaType_id || raza.raza_type_id);

    if (raza_type_id === 2 && raza.miqaat_details) {
      let miqaat_info = parseMaybe(raza.miqaat_details) || {};
      tbodydata += `<tr><th scope=\"row\">Miqaat Name</th><td>${miqaat_info.name}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Miqaat Type</th><td>${miqaat_info.type}</td></tr>`;
      // Format miqaat_info.date as dd-mm-yyyy
      let miqaatDateStr = '';
      if (miqaat_info.date) {
        let d = new Date(miqaat_info.date);
        let day = String(d.getDate()).padStart(2, '0');
        let month = String(d.getMonth() + 1).padStart(2, '0');
        let year = d.getFullYear();
        miqaatDateStr = `${day}-${month}-${year}`;
      }
      tbodydata += `<tr><th scope=\"row\">Miqaat Date</th><td>${miqaatDateStr}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Assigned To</th><td>${miqaat_info.assigned_to}</td></tr>`;
      tbodydata += `<tr><th scope=\"row\">Status</th><td>${miqaat_info.status == 1 ? 'Active' : 'Inactive'}</td></tr>`;
      // If group assignment, show group leader and all members' names
      if (miqaat_info.assign_type === 'Group' && Array.isArray(miqaat_info.assignments) && miqaat_info.assignments.length > 0) {
        tbodydata += `<tr><th colspan=\"2\" class=\"text-center\">Group Leader</th></tr>`;
        tbodydata += `<tr><th scope=\"row\">Leader Name</th><td>${miqaat_info.group_leader_name ? miqaat_info.group_leader_name : ''} ${miqaat_info.group_leader_surname ? miqaat_info.group_leader_surname : ''}</td></tr>`;
        tbodydata += `<tr><th colspan=\"2\" class=\"text-center\">Group Members</th></tr>`;
        miqaat_info.assignments.forEach(function(member, idx) {
          tbodydata += `<tr><th scope=\"row\">Member ${idx + 1}</th><td>${member.member_first_name ? member.member_first_name : ''} ${member.member_surname ? member.member_surname : ''}</td></tr>`;
        });
      }
    } else {
      let k = 0;
      for (let key in (razadata || {})) {
        if (razafields && razafields.fields && razafields.fields[k] && razafields.fields[k].type == 'select') {
          let options = razafields.fields[k].options || [];
          let value = options.find(opt => String(opt.id) === String(razadata[key]));
          let displayVal = value ? value.name : razadata[key];
          tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${displayVal}</td></tr>`
        } else {
          const fname = (razafields && razafields.fields && razafields.fields[k]) ? razafields.fields[k].name : key;
          tbodydata += `<tr><th scope="row">${fname}</th><td>${razadata ? razadata[key] : ''}</td></tr>`
        }
        k++;
      }
    }
    tablebody.innerHTML = tbodydata
    table.appendChild(tablebody)
  }
  $(document).ready(function() {
    $('#approve').submit(function(event) {
      event.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('amilsaheb/approveRaza'); ?>",
        data: formData,
        success: function(response) {
          showSuccessMessage();
          clearForm();
          refresh();
        },
        error: function(error) {
          console.error('query submission failed');
        }
      });
    });

    function showSuccessMessage() {
      var toastMessage = $('#toast-message');
      toastMessage.show();
      setTimeout(function() {
        toastMessage.hide();
      }, 2000);
    }
  });
  $(document).ready(function() {
    $('#reject').submit(function(event) {
      event.preventDefault();

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: "<?php echo base_url('amilsaheb/rejectRaza'); ?>",
        data: formData,
        success: function(response) {
          showSuccessMessage();
          clearForm();
          refresh();
        },
        error: function(error) {
          console.error('query submission failed');
        }
      });
    });

    function showSuccessMessage() {
      var toastMessage = $('#toast-message');
      toastMessage.show();
      setTimeout(function() {
        toastMessage.hide();
      }, 2000);
    }
  });

  function redirectto(location) {
    window.location.href = '<?php echo base_url() ?>' + location;
  }

  function deleteRaza(id) {
    let check = confirm("Do You Want to Delete This Raza?");
    if (check) {
      window.location.href = '<?php echo base_url() ?>' + 'amilsaheb/DeleteRaza/' + id + '?umoor=<?php echo urlencode($umoor ?? ""); ?>&event_type=<?php echo $event_type ?? ""; ?>';
    }
  }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
  function refresh() {
    window.location.reload()
  }
</script>
<script>
  $(document).ready(function() {
    // Function to handle the search functionality
    function performSearch() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("razaSearchInput");
      filter = input.value.toLowerCase();
      table = document.getElementsByTagName("table")[0]; // Assuming it's the first table on the page
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows and hide those that don't match the search query
      for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        var found = false;
        for (var j = 0; j < td.length; j++) {
          if (td[j]) {
            txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
              found = true;
              break;
            }
          }
        }
        if (found) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }

    // Attach the performSearch function to the input field's keyup event
    document.getElementById("razaSearchInput").addEventListener("keyup", performSearch);
  });
</script>
<script>
  let tem = document.getElementById("sort");
  tem.value = 2;
  updateTable();

  function updateTable() {
    var filterValue = document.getElementById("filter").value;
    var sortValue = document.getElementById("sort").value;
    var miqaatFilterElem = document.getElementById("miqaat_filter");
    var miqaatFilter = miqaatFilterElem ? miqaatFilterElem.value : "";
    var umoorFilterElem = document.getElementById("umoor_filter");
    var umoorFilter = umoorFilterElem ? umoorFilterElem.value : "";
    var yearFilterElem = document.getElementById("year_filter");
    var yearFilter = yearFilterElem ? yearFilterElem.value : "";
    var hijriMonthElem = document.getElementById("hijri_month_filter");
    var hijriMonth = hijriMonthElem ? hijriMonthElem.value : "";
    var hijriDayElem = document.getElementById("hijri_day_filter");
    var hijriDay = hijriDayElem ? hijriDayElem.value : "";
    updateTableContent(filterValue, sortValue, miqaatFilter, yearFilter, umoorFilter, hijriMonth, hijriDay);
  }

  function updateTableContent(filter, sort, miqaatFilter, yearFilter, umoorFilter, hijriMonth, hijriDay) {
    var tbody = document.getElementById('datatable');
    tbody.innerHTML = "";

    // Filter and sort your razas array based on the selected options
    var filteredAndSortedRazas = razas;

    // helper to safely parse JSON-ish fields
    function parseMaybe(val) {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    }

    // Apply miqaat type filter if selected
    if (miqaatFilter && miqaatFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var m = parseMaybe(raza.miqaat_details) || {};
        var miqaatType = (m.type || raza.razaType || '').toString();
        return miqaatType.toLowerCase() === miqaatFilter.toLowerCase();
      });
    }


    // Apply Hijri year filter if selected (uses server-provided hijriMap)
    if (yearFilter && yearFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var hy = (hijriMap && hijriMap[raza.id]) ? hijriMap[raza.id].toString() : '';
        return hy === yearFilter;
      });
    }

    // Hijri month/day filter: parse hijri_parts from raza
    if ((hijriMonth && hijriMonth !== "") || (hijriDay && hijriDay !== "")) {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var hijri = (raza.hijri_parts || null);
        if (!hijri) return true;
        if (hijriMonth && hijriMonth !== "" && parseInt(hijri.month) !== parseInt(hijriMonth)) return false;
        if (hijriDay && hijriDay !== "" && parseInt(hijri.day) !== parseInt(hijriDay)) return false;
        return true;
      });
    }

    // Apply Umoor filter (for 12 Umoor view) if selected
    if (umoorFilter && umoorFilter !== "") {
      filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
        var u = (raza.umoor || '').toString();
        return u.toLowerCase() === umoorFilter.toLowerCase();
      });
    }

    if (filter !== "") {
      switch (filter) {
        case "approved":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 2;
          });
          break;
        case "recommended":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 1;
          });
          break;
        case "pending":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 0;
          });
          break;
        case "rejected":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 3;
          });
          break;
        case "notrecommended":
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.status == 4;
          });
          break;
        case "clear":
          refresh();
          break;

        default:
          filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
            return raza.razaType === filter;
          });
          break;
      }

    }

    if (sort !== "") {
      filteredAndSortedRazas.sort(function(a, b) {
        // Implement your sorting logic here
        switch (parseInt(sort)) {
          case 0:
            return a.user_name.localeCompare(b.user_name);
          case 1:
            return b.user_name.localeCompare(a.user_name);
          case 4:
            // Implement sorting by date (New > Old)
            return new Date(b['time-stamp']) - new Date(a['time-stamp']);
          case 5:
            // Implement sorting by date (Old > New)
            return new Date(a['time-stamp']) - new Date(b['time-stamp']);
          case 2:
            // Implement sorting by event date (New > Old)
            return new Date(getEventDate(b)) - new Date(getEventDate(a));
          case 3:
            // Implement sorting by event date (Old > New)
            return new Date(getEventDate(a)) - new Date(getEventDate(b));
          case 7:
            // Sort by miqaat date (New > Old)
            return new Date(getMiqaatDate(b)) - new Date(getMiqaatDate(a));
          case 8:
            // Sort by miqaat date (Old > New)
            return new Date(getMiqaatDate(a)) - new Date(getMiqaatDate(b));
          case 6:
            refresh();
          default:
            return 0;
        }
      });
    }

    // Populate the table with the filtered and sorted data
    for (var i = 0; i < filteredAndSortedRazas.length; i++) {
      var raza = filteredAndSortedRazas[i];
      var chatCount = raza.chat_count && raza.chat_count > 0 ? raza.chat_count : '';
      var chatCountHTML = chatCount ? `<div class="chat-count">${chatCount}</div>` : '';
      var chatURL = `<?php echo base_url('Accounts/chat/') ?>${raza.id}<?php echo "/amilsaheb"; ?>`;
      var row = document.createElement("tr");
      // Determine display name: if this is a Fala ni Niyaz (FNN) show label instead of user name
      var nameDisplay = raza['user_name'] || '';
      try {
        var _m = (function(v) {
          if (!v) return null;
          if (typeof v === 'object') return v;
          try {
            return JSON.parse(v);
          } catch (e) {
            return null;
          }
        })(raza.miqaat_details) || {};
        var miqAssignedTo = (_m.assigned_to || '').toString().toLowerCase();
        var miqType = (_m.type || '').toString().toLowerCase();
        var miqName = (_m.name || '').toString().toLowerCase();
        var miqAssignType = (_m.assign_type || _m.assignType || '').toString().toLowerCase();
        var rt = (raza.razaType || '').toString().toLowerCase();
        var isFnn = false;
        var checkFnn = function(s) {
          if (!s) return false;
          if (s.indexOf('fnn') !== -1) return true;
          if (s.indexOf('fala') !== -1 && (s.indexOf('niyaz') !== -1 || s.indexOf('niaz') !== -1)) return true;
          return false;
        };
        if (checkFnn(miqAssignedTo) || checkFnn(miqType) || checkFnn(miqName) || checkFnn(miqAssignType) || checkFnn(rt)) {
          isFnn = true;
        }
        if (!isFnn && Array.isArray(_m.assignments)) {
          for (var ai = 0; ai < _m.assignments.length; ai++) {
            var as = _m.assignments[ai] || {};
            var ast = (as.assign_type || as.type || '').toString().toLowerCase();
            if (checkFnn(ast)) {
              isFnn = true;
              break;
            }
          }
        }
        if (isFnn) nameDisplay = 'Fala ni Niyaz';
      } catch (e) {
        // ignore
      }

      row.innerHTML = `
                <td>${i + 1}</td>
                <td>${nameDisplay}</td>
                <td>${formateRazaType(raza)}</td>
                <td>${formatEventDate(raza)}</td>
                <td><a href="${chatURL}" class="chat-button">Chat${chatCountHTML}</a></td>
                <td>${getStatusHTML(raza)}</td>
                <td><span class="action_btn">${getActionHTML(raza)}</span></td>
                <td>${formatDate(raza['time-stamp'])}</td>
            `;

      tbody.appendChild(row);
    }
  }

  // Sabil and FMB related columns removed; helper functions were removed accordingly.

  function formateRazaType(raza) {
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    // If linked to a miqaat, show Name with small Type · Date
    if (raza.miqaat_id && raza.miqaat_details) {
      const m = parseMaybe(raza.miqaat_details) || {};
      const date = m.date ? new Date(m.date).toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      }) : '';
      const name = m.name || '';
      const type = m.type || '';
      const assignedTo = m.assigned_to || '';
      const parts = [];
      if (type) parts.push(type);
      if (assignedTo) parts.push(assignedTo);
      const meta = parts.join(' · ');
      return `${name}<br><small style="color:#6c757d;">${meta}</small>`;
    }

    let data = parseMaybe(raza.razadata) || {};
    let rf = parseMaybe(raza.razafields) || {};
    let razafields = rf.fields || [];

    // Default: show umoor and razaType
    return `${raza.umoor ? raza.umoor + '\n' : ''}(${raza.razaType || ''})`;
  }

  function formatDate(dateString) {
    // Implement date formatting logic if needed
    const options = {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      hour12: true
    };
    return new Date(dateString).toLocaleDateString('en-US', options);
  }

  function formatEventDate(raza) {
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let data = parseMaybe(raza.razadata) || {};
    let rf = parseMaybe(raza.razafields) || {};
    let razafields = rf.fields || [];
    let gregDate = '';
    let gregDateStr = '';
    const options = {
      weekday: 'short',
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    };
    if (raza.miqaat_id && raza.miqaat_details) {
      let miqaat_info = parseMaybe(raza.miqaat_details) || {};
      gregDate = miqaat_info.date;
      if (gregDate) {
        gregDateStr = new Date(gregDate).toLocaleDateString('en-US', options);
      }
    } else {
      gregDate = data.date;
      if (!gregDate) {
        if (Array.isArray(razafields)) {
            for (let i = 0; i < razafields.length; i++) {
                let f = razafields[i];
                if (f.type === 'date' && f.name) {
                    let key1 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                    let key2 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                    if (data[key1]) { gregDate = data[key1]; break; }
                    if (data[key2]) { gregDate = data[key2]; break; }
                }
            }
        } else if (razafields) {
            for (let k in razafields) {
                let f = razafields[k];
                if (f.type === 'date' && f.name) {
                    let key1 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                    let key2 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                    if (data[key1]) { gregDate = data[key1]; break; }
                    if (data[key2]) { gregDate = data[key2]; break; }
                }
            }
        }
      }
      if (gregDate) {
        gregDateStr = new Date(gregDate).toLocaleDateString('en-US', options);
      }
    }
    // Hijri date from hijri_parts (provided by server)
    let hijriStr = '';
    if (raza.hijri_parts && raza.hijri_parts.year && raza.hijri_parts.month && raza.hijri_parts.day) {
      // Use hijri_months array if available, else fallback to number
      let hijriMonthName = '';
      if (window.hijriMonths && window.hijriMonths[raza.hijri_parts.month]) {
        hijriMonthName = window.hijriMonths[raza.hijri_parts.month];
      } else {
        // fallback: just show month number
        hijriMonthName = 'Month ' + raza.hijri_parts.month;
      }
      hijriStr = `<br><small class=\"text-muted\">(${raza.hijri_parts.day} ${hijriMonthName} ${raza.hijri_parts.year}H)</small>`;
    }
    if (gregDateStr) {
      return gregDateStr + hijriStr;
    } else {
      return '';
    }
  }

  function getStatusHTML(raza) {
    let statusHTML = '<div class="text-left">';

    if (raza['status'] == 0) {
      statusHTML += '<div><strong style="color: orange;">Pending</strong></div>';
    } else if (raza['status'] == 1) {
      statusHTML += '<div><strong style="color: blue;">Recommended</strong></div>';
    } else if (raza['status'] == 2) {
      statusHTML += '<div><strong style="color: limegreen;">Approved</strong></div>';
    } else if (raza['status'] == 3) {
      statusHTML += '<div><strong style="color: red;">Rejected</strong></div>';
    } else if (raza['status'] == 4) {
      statusHTML += '<div><strong style="color: blue;">Not Recommended</strong></div>';
    }

    statusHTML += '<ul><li>';
    if (raza['coordinator-status'] == 0) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
    } else if (raza['coordinator-status'] == 1) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
    } else if (raza['coordinator-status'] == 2) {
      statusHTML += '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
    }
    statusHTML += '</li>';

    statusHTML += '<li>';
    if (raza['Janab-status'] == 0) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
    } else if (raza['Janab-status'] == 1) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
    } else if (raza['Janab-status'] == 2) {
      statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
    }
    statusHTML += '</li></ul></div>';

    return statusHTML;
  }

  function getActionHTML(raza) {
    // Implement your action HTML generation logic
    // You can use the same logic you have in your PHP code

    let actionHTML = '';
    if (raza['Janab-status'] == 0) {
      actionHTML = `
        <div class="action-buttons">
            <div class="button-group">
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(${raza['id']});">
                    <i class="fa fa-circle-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(${raza['id']});">
                    <i class="fa fa-circle-xmark"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="deleteRaza(${raza['id']});">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div class="view-link remove-form-row">
              <a class="btn btn-sm btn-primary" onclick="show_raza(${raza['id']});">
                <span style=" cursor:pointer;">View</span>
              </a>
            </div>
        </div>
    `;
    } else {
      actionHTML = `
        <div class="view-link remove-form-row">
          <a class="btn btn-sm btn-primary" onclick="show_raza(${raza['id']});">
            <span style=" cursor:pointer;">View</span>
          </a>
        </div>
    `;
    }

    return actionHTML;
  }

  function getEventDate(raza) {
    const parseMaybe = (val) => {
      if (!val) return null;
      if (typeof val === 'object') return val;
      try {
        return JSON.parse(val);
      } catch (e) {
        return null;
      }
    };
    let data = parseMaybe(raza.razadata) || {};
    let m = parseMaybe(raza.miqaat_details) || {};
    if (m.date) return new Date(m.date);
    if (data.date) return new Date(data.date);
    
    let rf = parseMaybe(raza.razafields) || {};
    let fields = rf.fields || rf;
    if (Array.isArray(fields)) {
        for (let i = 0; i < fields.length; i++) {
            let f = fields[i];
            if (f.type === 'date' && f.name) {
                let key1 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                let key2 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                if (data[key1]) return new Date(data[key1]);
                if (data[key2]) return new Date(data[key2]);
            }
        }
    } else if (fields) {
        for (let k in fields) {
            let f = fields[k];
            if (f.type === 'date' && f.name) {
                let key1 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
                let key2 = f.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                if (data[key1]) return new Date(data[key1]);
                if (data[key2]) return new Date(data[key2]);
            }
        }
    }
    return null;
  }
</script>
<script>
  function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.querySelector("table");
    switching = true;
    dir = "asc"; // Set the sorting direction to ascending

    // Make a loop that will continue until no switching has been done
    while (switching) {
      switching = false;
      rows = table.rows;

      // Loop through all table rows (except the first, which contains table headers)
      for (i = 1; i < (rows.length - 1); i++) {
        shouldSwitch = false;

        // Get the two elements you want to compare, one from current row and one from the next
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];

        // Check if the two rows should switch place, based on the direction, asc or desc
        if (dir === "asc") {
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop
            shouldSwitch = true;
            break;
          }
        } else if (dir === "desc") {
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            // If so, mark as a switch and break the loop
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        // If a switch has been marked, make the switch and mark that a switch has been done
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        // Each time a switch is done, increase this count by 1
        switchcount++;
      } else {
        // If no switching has been done AND the direction is "asc", set the direction to "desc" and run the loop again
        if (switchcount === 0 && dir === "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
</script>
<script>
  $(document).ready(function() {
    if ($.fn && $.fn.DataTable) {
      $('.table').DataTable();
    }
  });
</script>