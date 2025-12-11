<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .icon {
    font-size: 40pt;
    margin: 10px 0;
    color: #ffffff;
  }

  .title {
    color: white;
  }

  .heading {
    color: #ad7e05;
    font-family: 'Amita', cursive;
  }

  .card {
    height: 153px;
  }

  .card:hover {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
  }

  .row a {
    text-decoration: none;
    color: inherit;
  }

  /* Match Jamaat/Amilsaheb layout */
  .chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .chart-container.compact {
    padding: 18px;
  }

  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
  }

  .mini-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    text-align: center;
  }

  .mini-card .stats-value {
    font-size: 1.2rem;
    font-weight: 700;
  }

  .mini-card .stats-label {
    font-size: .8rem;
    color: #777;
    letter-spacing: .6px;
  }

  .fmb-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 12px 14px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .fmb-card .fmb-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .fmb-card .fmb-name {
    font-weight: 700;
    color: #222;
  }

  .fmb-card .fmb-amounts {
    display: flex;
    gap: 12px;
    font-size: .9rem;
    color: #555;
  }

  .fmb-card .fmb-amounts .val {
    font-weight: 700;
    color: #111;
  }
</style>
<div class="container margintopcontainer">
  <h1 class="text-center heading pt-5 mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
  <hr>
  <?php
  // Weekly Thaali Signups section (sector/sub-sector scoped)
  $weekly = isset($weekly_signup_avg) ? $weekly_signup_avg : null;
  $weekStart = $weekly['start'] ?? date('Y-m-d', strtotime('monday this week'));
  $weekEnd = $weekly['end'] ?? date('Y-m-d', strtotime('sunday this week'));
  $items = isset($weekly['items']) ? $weekly['items'] : [];
  $scope = isset($weekly['scope']) ? $weekly['scope'] : ['sector' => '', 'sub_sector' => ''];
  ?>

  <div class="chart-container compact">
    <h4 class="section-title text-center">This Week Thaali Signup Average (Sub-sector-wise)</h4>
    <p class="text-center text-muted" style="margin-top:-10px; margin-bottom:14px;">
      <?= htmlspecialchars($weekStart); ?> to <?= htmlspecialchars($weekEnd); ?>
    </p>
    <?php
    $sumWeekTotal = 0;
    foreach ($items as $it) {
      $sumWeekTotal += (int)($it['total'] ?? 0);
    }
    $days = (int)($weekly['days'] ?? 7);
    if ($days <= 0) {
      $days = 7;
    }
    $overallAvgPerDay = $days > 0 ? round($sumWeekTotal / $days, 2) : 0;
    ?>
    <div class="row text-center mb-2">
      <div class="col-4 col-md-3 mb-2">
        <div class="mini-card">
          <div class="stats-value"><?= (int)$sumWeekTotal; ?></div>
          <div class="stats-label">Total (Week)</div>
        </div>
      </div>
      <div class="col-4 col-md-3 mb-2">
        <div class="mini-card">
          <div class="stats-value"><?= number_format((float)$overallAvgPerDay, 2); ?></div>
          <div class="stats-label">Avg/Day (Overall)</div>
        </div>
      </div>
      <div class="col-4 col-md-3 mb-2">
        <div class="mini-card">
          <div class="stats-value"><?= count($items); ?></div>
          <div class="stats-label">Sub-sectors</div>
        </div>
      </div>
    </div>

    <?php if (!empty($items)): ?>
      <div class="row g-2">
        <?php foreach ($items as $item): ?>
          <?php
          $sector = isset($item['sector']) ? trim($item['sector']) : '';
          $sub = isset($item['sub_sector']) ? trim($item['sub_sector']) : '';
          $total = (int)($item['total'] ?? 0);
          $avg = (float)($item['avg'] ?? 0);
          $params = [
            'from' => 'masoolmusaid',
            'start_date' => $weekStart,
            'end_date' => $weekEnd,
            'sector' => $sector,
          ];
          if ($sub !== '') {
            $params['sub_sector'] = $sub;
          }
          $href = base_url('common/thaali_signups_breakdown') . '?' . http_build_query($params);
          ?>
          <div class="col-12 col-md-6 col-lg-4 mt-2">
            <a href="<?= $href; ?>" style="text-decoration:none;color:inherit;display:block;">
              <div class="fmb-card">
                <div class="fmb-head">
                  <div class="fmb-name">
                    <i class="fa fa-map-marker text-primary me-2"></i>
                    <?= htmlspecialchars($sector); ?><?= $sub !== '' ? ' – ' . htmlspecialchars($sub) : ''; ?>
                  </div>
                </div>
                <div class="fmb-amounts">
                  <span>Avg/Day <span class="val"><?= number_format($avg, 2); ?></span></span>
                  <span>Total <span class="val"><?= $total; ?></span></span>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-muted">No thaali signups recorded this week.</div>
    <?php endif; ?>
  </div>
  <div class="continer d-flex justify-content-center">
    <div class="row container">
      <a href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Mumineen Directory</div>
            <i class="fa-solid icon fa-clipboard-user"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/asharaohbat') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Ashara Ohbat 1446</div>
            <i class="fa-solid icon fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/ashara_attendance') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Ashara Attendance</div>
            <i class="fa-solid icon fa-user-check"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/rsvp_list') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Miqaat RSVP</div>
            <i class="fa-solid icon fa-users"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('MasoolMusaid/miqaat_attendance') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card dashboard-card text-center">
          <div class="card-body">
            <div class="title">Miqaat Attendance</div>
            <i class="fa-solid icon fa-calendar-check"></i>
          </div>
        </div>
      </a>
    </div>
  </div>

</div>
<script>
  const colors = ["rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
    "rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(0, 106, 63)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
    "rgb(41, 128, 185)",
  ]
  $(document).ready(function() {
    $(".dashboard-card").each(function(i) {
      this.style.backgroundColor = colors[i % colors.length];
    });
  })
</script>
<script>
  // Disable browser back button
  history.pushState(null, null, location.href);
  window.onpopstate = function() {
    history.pushState(null, null, location.href);
  };
</script>