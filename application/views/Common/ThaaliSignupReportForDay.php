<?php
// Duplicate of SignUpForDay with heading adjusted to 'Thaali Signup Report for'
// Expect variables: $signupdata, $date, $filter_data, $all_dp
?>
<div class="container margintopcontainer pt-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 no-print datatable-toolbar">
      <div class="btn-group mb-2">
        <a href="<?php echo base_url("common/thaali_signups_breakdown") ?>" class="btn btn-outline-secondary" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
        <button type="button" id="print-table" class="btn btn-outline-primary" title="Print Table"><i class="fa-solid fa-print"></i> <span class="d-none d-sm-inline">Print</span></button>
      </div>
      <div class="text-end mb-2">
        <h4 class="heading m-0">Thaali Signup Report</h4>
        <div class="text-primary small fw-semibold">Thaali Signup Report for <?php echo date("d-m-Y", strtotime($date)); ?></div>
      </div>
    </div>
  </div>

  <?php
    // Build sector/sub-sector option lists from provided `all_users` if available
    $all_users = isset($all_users) ? $all_users : [];
    $sectors = [];
    $sub_by_sector = [];
    foreach ($all_users as $u) {
      $s = isset($u['Sector']) ? trim($u['Sector']) : '';
      $ss = isset($u['Sub_Sector']) ? trim($u['Sub_Sector']) : '';
      if ($s !== '') {
        $sectors[$s] = true;
        if (!isset($sub_by_sector[$s])) $sub_by_sector[$s] = [];
        if ($ss !== '') $sub_by_sector[$s][$ss] = true;
      }
    }
    $sector_list = array_keys($sectors);
    sort($sector_list, SORT_NATURAL | SORT_FLAG_CASE);
  ?>
  <div class="col-12 mb-4 no-print">
    <form method="POST" action="<?php echo base_url("common/thaali_signup_report/" . $date); ?>" class="p-3 border rounded shadow-sm bg-white filter-card">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label for="sector" class="form-label mb-1">Sector</label>
          <select name="sector" id="sector" class="filter-form form-control form-select">
            <option value="">All</option>
            <?php foreach ($sector_list as $s): ?>
              <option value="<?php echo htmlspecialchars($s); ?>" <?php echo isset($filter_data['sector']) && $filter_data['sector'] === $s ? 'selected' : ''; ?>><?php echo htmlspecialchars($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="sub_sector" class="form-label mb-1">Sub-Sector</label>
          <select name="sub_sector" id="sub_sector" class="filter-form form-control form-select">
            <option value="">All</option>
            <?php
              $selected_sector = isset($filter_data['sector']) ? $filter_data['sector'] : '';
              $sub_list = [];
              if ($selected_sector && isset($sub_by_sector[$selected_sector])) {
                $sub_list = array_keys($sub_by_sector[$selected_sector]);
              } else {
                // flatten all sub sectors
                $all_subs = [];
                foreach ($sub_by_sector as $arr) { foreach ($arr as $k => $_) $all_subs[$k] = true; }
                $sub_list = array_keys($all_subs);
              }
              sort($sub_list, SORT_NATURAL | SORT_FLAG_CASE);
            ?>
            <?php foreach ($sub_list as $ss): ?>
              <option value="<?php echo htmlspecialchars($ss); ?>" <?php echo isset($filter_data['sub_sector']) && $filter_data['sub_sector'] === $ss ? 'selected' : ''; ?>><?php echo htmlspecialchars($ss); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="thali-taken" class="form-label mb-1">Thali Taken?</label>
          <select name="thali_taken" id="thali-taken" class="filter-form form-control form-select">
            <option value="-1">All</option>
            <option value="1" <?php echo isset($filter_data["thali_taken"]) ? ($filter_data["thali_taken"] == 1 ? "selected" : "") : ""; ?>>Yes</option>
            <option value="0" <?php echo isset($filter_data["thali_taken"]) ? ($filter_data["thali_taken"] == 0 ? "selected" : "") : ""; ?>>No</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <a href="<?php echo base_url("common/thaali_signup_report/" . $date); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-times"></i> Reset</a>
        </div>
      </div>
    </form>
  </div>

  <?php
  $families_total = !empty($signupdata) ? count($signupdata) : 0;
  $families_taking = 0;
  if (!empty($signupdata)) {
    foreach ($signupdata as $r) {
      if (isset($r['want_thali']) && (int)$r['want_thali'] === 1) $families_taking++;
    }
  }
  ?>

  <div class="container">
    <div class="card shadow-sm mb-4">
      <div class="card-body py-2 d-flex flex-wrap justify-content-between align-items-center small gap-2">
        <div><strong>Total Families:</strong> <?php echo $families_total; ?></div>
        <div><strong>Taking Thali:</strong> <?php echo $families_taking; ?></div>
        <div><strong>Not Taking / No Signup:</strong> <?php echo $families_total - $families_taking; ?></div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="table-responsive position-relative modern-table-wrapper">
      <table class="table table-hover align-middle table-bordered mb-0 modern-table table-sm" id="signup-table">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th>HOF ITS</th>
            <th>HOF Name</th>
            <th>Sector</th>
            <th>Sub-Sector</th>
            <th>Signup By (Member)</th>
            <th class="text-center">Thali?</th>
            <th class="text-center">Thali Size</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($signupdata)): ?>
            <?php foreach ($signupdata as $idx => $row): ?>
              <?php
              $taking = !empty($row['want_thali']) && (int)$row['want_thali'] === 1;
              $rowClass = $taking ? 'taking' : 'not-taking';
              ?>
              <tr class="<?php echo $rowClass; ?>">
                <td class="text-center fw-semibold"><?php echo $idx + 1; ?></td>
                <td class="font-monospace"><?php echo htmlspecialchars($row['hof_its_id']); ?></td>
                <td><?php echo htmlspecialchars($row['hof_name']); ?></td>
                <td><?php echo !empty($row['sector']) ? htmlspecialchars($row['sector']) : '<span class="text-muted">—</span>'; ?></td>
                <td><?php echo !empty($row['sub_sector']) ? htmlspecialchars($row['sub_sector']) : '<span class="text-muted">—</span>'; ?></td>
                <td><?php echo !empty($row['signup_user_id']) ? htmlspecialchars($row['signup_member_name']) : '<span class="text-muted">—</span>'; ?></td>
                <td class="text-center">
                  <?php if (isset($row['want_thali'])): ?>
                    <span class="badge rounded-pill <?php echo $taking ? 'bg-success' : 'bg-danger'; ?>"><?php echo $taking ? 'Yes' : 'No'; ?></span>
                  <?php else: ?>
                    <span class="badge rounded-pill bg-secondary">NA</span>
                  <?php endif; ?>
                </td>
                <td class="text-center"><?php echo !empty($row['thali_size']) ? htmlspecialchars($row['thali_size']) : ''; ?></td>
                
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No data found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="small text-muted mt-2">Green rows (Yes) indicate families taking thali. Red rows indicate not taking / no signup.</div>
</div>

<style>
  .modern-table-wrapper {
    max-height: 70vh;
    overflow: auto;
    border: 1px solid #e3e6ea;
    border-radius: .5rem;
    background: #fff;
  }

  .modern-table thead th {
    position: sticky;
    top: 0;
    background: #f1f3f5;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    z-index: 5;
  }

  .modern-table tbody tr.taking {
    --row-accent: #d1f4e0;
    background: linear-gradient(90deg, var(--row-accent) 0%, rgba(255, 255, 255, 0) 70%);
  }

  .modern-table tbody tr.not-taking {
    --row-accent: #f8d7da33;
  }

  .modern-table tbody tr.not-taking:hover {
    background: #fff5f5;
  }

  .modern-table tbody tr.taking:hover {
    filter: brightness(0.98);
  }

  .modern-table tbody td {
    vertical-align: middle;
    font-size: .82rem;
  }

  .modern-table td,
  .modern-table th {
    white-space: nowrap;
  }

  .filter-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  }

  .datatable-toolbar {
    gap: .75rem;
  }

  #print-table {
    backdrop-filter: blur(2px);
  }

  @media (max-width: 992px) {
    .modern-table-wrapper {
      max-height: 60vh;
    }

    .modern-table td {
      font-size: .75rem;
    }
  }

  @media print {
    body {
      background: #fff !important;
    }

    .no-print,
    .no-print * {
      display: none !important;
    }

    .modern-table-wrapper {
      max-height: none;
      overflow: visible;
      border: none;
    }

    .modern-table thead th {
      background: #e9ecef !important;
      -webkit-print-color-adjust: exact;
    }

    .modern-table tbody tr.taking {
      background: #e6f9ef !important;
      -webkit-print-color-adjust: exact;
    }

    .modern-table tbody tr.not-taking {
      background: #fff !important;
    }

    .small.text-muted {
      color: #555 !important;
    }
  }
</style>

<script>
  document.querySelectorAll('.filter-form').forEach(el => {
    el.addEventListener('change', function() {
      this.form.submit();
    });
  });
  document.getElementById('print-table')?.addEventListener('click', () => {
    window.print();
  });
</script>
