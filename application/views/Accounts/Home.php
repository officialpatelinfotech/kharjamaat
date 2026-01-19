<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  body {
    background-color: #fff7e6;
  }

  .icon {
    font-size: 40pt;
    margin: 10px 0;
    color: #ffffff;
  }

  .title {
    color: white;
    font-size: 0.8rem;
  }

  .heading {
    color: #ad7e05;
    font-family: 'Amita', cursive;
  }

  .row a {
    text-decoration: none;
    color: inherit;
  }

  .action-btn {
    height: 68px;
    /* increased from 50px */
    color: black;
    border-radius: 5px;
  }

  .action-btn:hover {
    text-decoration: none;
    transform: translate(0, -2px);
    transition: all 0.2s ease-in-out;
  }

  .action-btn:hover {
    opacity: 0.8;
  }

  .abi {
    font-size: 12pt;
  }

  .action-btn-title {
    font-size: 0.8rem;
  }

  /* New dashboard design */

  .dashboard-card {
    background: #ffffff;
    padding: 15px;
    border-radius: 5px;
    margin-top: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .card-header {
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-body {
    font-size: 14px;
    height: 150px;
    overflow-y: auto;
    padding: 5px;
  }

  .creation_datetime {
    font-size: 10pt;
  }

  @media screen and (max-width: 767px) {
    .rsvp-card {
      height: 120px;
    }

    .rsvp-stat-number {
      font-size: 1.6rem !important;
      font-weight: 700;
    }

    .rsvp-items {
      font-size: 0.6rem !important;
      font-weight: 600;
    }
  }

  /* New dashboard design  */
  /* Modernized Assigned Miqaats stat button */
  .action-stat {
    position: relative;
    flex-direction: column !important;
    gap: 2px;
    border-radius: 10px;
    overflow: hidden;
  }

  .action-stat .stat-icon-wrap {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.18);
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
    transition: transform .25s ease;
  }

  .action-stat .fa {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
  }

  .action-stat:hover .stat-icon-wrap {
    transform: translateY(-3px);
  }

  .action-stat .count-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.65));
    color: #222;
    padding: 2px 7px 3px;
    font-size: 0.6rem;
    font-weight: 700;
    line-height: 1;
    letter-spacing: .5px;
    border-radius: 40px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(0, 0, 0, 0.08);
    text-transform: uppercase;
  }

  .action-stat .stat-empty {
    font-weight: 500;
  }

  @media (max-width: 575px) {
    .action-stat .stat-icon-wrap {
      width: 38px;
      height: 38px;
    }
  }

  /* Corpus card typography tweaks */
  .mini-card .stats-value {
    font-size: 1.1rem;
    font-weight: 700;
  }

  .mini-card .stats-label {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: .3px;
  }

  .dashboard-card .card-header span {
    font-size: 1rem;
  }

  /* Compact height for small stat cards in Sabeel/FMB sections */
  .mini-stat-card .card-body {
    height: 95px;
    overflow-y: hidden;
  }

  /* Ensure dues cards don't show internal scrollbars */
  .dashboard-card.dues .card-body {
    height: auto;
    overflow: visible;
    padding-top: 10px;
    padding-bottom: 10px;
  }

  /* Tighten spacing inside dues containers */
  .dashboard-card.dues .mini-stat-card .card-body {
    padding-top: .5rem;
    padding-bottom: .5rem;
  }

  .dashboard-card.dues h6,
  .dashboard-card.dues .h4,
  .dashboard-card.dues .h5 {
    margin-bottom: .25rem;
  }

  /* Disable scrollbars explicitly where needed */
  .dashboard-card .card-body.no-scroll {
    height: auto !important;
    overflow: hidden !important;
  }
</style>
<?php
// `format_inr()` is provided by the autoloaded `inr_helper`.
?>
<div class="container margintopcontainer pt-5">
  <h1 class="text-center heading pt-5 mb-3">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
  <p class="hirji-date text-center mb-4"><b><?php echo $hijri_date ?></b></p>
  <hr>
  <div class="row justify-content-center">
    <!-- <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/Umoor') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-edit fa-2x mr-2"></i>
        <span class="action-btn-title">New Raza</span>
      </a>
    </div> -->
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/assigned_miqaats') ?>" aria-label="Miqaat Public Event Raza: <?php echo $assigned_miqaats_count; ?>" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-calendar-check-o"></i>
          <?php if (isset($assigned_miqaats_count) && (int)$assigned_miqaats_count > 0): ?>
            <span class="count-badge"><?php echo $assigned_miqaats_count; ?></span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Miqaat Public Event Raza</span>
        <?php if (!isset($assigned_miqaats_count) || (int)$assigned_miqaats_count === 0): ?>
          <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">None yet</span>
        <?php endif; ?>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?= base_url('Umoor12/MyRazaRequest?value=Private-Event') ?>" aria-label="Kaaraj Private Event Raza" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-calendar-plus"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Kaaraj Private Event Raza</span>
        <!-- <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Create</span> -->
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/Umoor') ?>" aria-label="Other Non Event Raza" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-edit"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Other Non Event Raza</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Create</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/MyRazaRequest') ?>" aria-label="My Applications" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-files-o"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Submitted Applications</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/viewfmbtakhmeen') ?>" aria-label="FMB Due" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-cutlery"></i>
          <?php if (isset($fmb_takhmeen_details['total_due']) && (int)$fmb_takhmeen_details['total_due'] > 0): ?>
            <span class="count-badge">Due</span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">FMB Due</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Overview</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/viewsabeeltakhmeen') ?>" aria-label="Sabeel Due" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-money"></i>
          <?php if (isset($sabeel_takhmeen_details["total_due"]) && (int)$sabeel_takhmeen_details["total_due"] > 0): ?>
            <span class="count-badge">Due</span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Sabeel Due</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">View</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/rsvp_list') ?>" aria-label="Event RSVP" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-calendar"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Miqaat & RSVP</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Events</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/fmbweeklysignup') ?>" aria-label="FMB Thaali Signup" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-spoon"></i>
          <?php
          $weekly_signup_days = 0;
          if (isset($signup_data) && is_array($signup_data)) {
            $weekly_signup_days = count(array_filter($signup_data, function ($d) {
              return isset($d['want_thali']) && (string)$d['want_thali'] === '1';
            }));
          }
          if (isset($signup_days) && is_array($signup_days)) {
            $signup_days = count($signup_days);
          } else {
            $signup_days = 0;
          }
          if ($weekly_signup_days > 0): ?>
            <span class="count-badge"><?php echo $weekly_signup_days . "/" . $signup_days; ?></span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Thaali Signup</span>
        <?php if ($weekly_signup_days === 0): ?>
          <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Start now</span>
        <?php else: ?>
          <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Days chosen</span>
        <?php endif; ?>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/viewmenu') ?>" aria-label="Thaali Menu" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-list"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Thaali Menu</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">FMB</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/FMBFeedback') ?>" aria-label="FMB Feedback" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-comments"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Thaali Feedback</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">Give Feedback</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/corpusfunds') ?>" aria-label="Corpus Funds" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-donate"></i>
          <?php
          $cf_due_badge = 0;
          if (isset($corpus_summary) && is_array($corpus_summary)) {
            $cf_due_badge = (float)($corpus_summary['outstanding'] ?? 0);
          }
          if ($cf_due_badge > 0): ?>
            <span class="count-badge">Due</span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Corpus Funds</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">View</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/wajebaat') ?>" aria-label="Wajebaat" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-coins"></i>
          <?php if (isset($wajebaat['due']) && (float)$wajebaat['due'] > 0): ?>
            <span class="count-badge">Due</span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Wajebaat</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/qardan_hasana') ?>" aria-label="Qardan Hasana" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-handshake-o"></i>
          <?php if (isset($qardan_hasana['due']) && (float)$qardan_hasana['due'] > 0): ?>
            <span class="count-badge">Due</span>
          <?php endif; ?>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Qardan Hasana</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/appointment') ?>" aria-label="Appointments" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-calendar"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">Appointments</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">View</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/profile') ?>" aria-label="My Profile" class="action-btn action-stat d-flex justify-content-center align-items-center text-center py-4 text-decoration-none">
        <div class="stat-icon-wrap mb-1">
          <i class="fa fa-user"></i>
        </div>
        <span class="action-btn-title d-block" style="font-weight:600; font-size:0.65rem; letter-spacing:.5px; text-transform:uppercase;">My Profile</span>
        <span class="stat-empty text-white-50" style="font-size:0.55rem; letter-spacing:.5px;">View</span>
      </a>
    </div>
    <!-- New action buttons requested -->
  </div>
  <div class="justify-content-center">
    <div class="row">
      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1">
          <div class="card-header">
            <span>Pending Raza Request</span>
            <!-- <a href="<?php echo base_url("accounts/Umoor") ?>" class="text-primary">+ Submit a New Raza</a> -->
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th class="raza">Raza For</th>
                  <th class="approval_status">Status</th>
                  <th class="action">Action</th>
                </tr>
                <?php
                foreach ($raza as $key => $r) { ?>
                  <tr>
                    <td>
                      <?php echo $r['razaType'] ?>
                      <p class="creation_datetime mt-2 m-0"><?php echo date('D, d M', strtotime($r['time-stamp'])) ?> <br><?php echo date('@ g:i a', strtotime($r['time-stamp'])) ?></p>
                    </td>
                    <td class="status">
                      <?php if ($r['status'] == 0) {
                        echo '<div><strong style="color: darkblue;">Pending</strong></div>';
                      } elseif ($r['status'] == 1) {
                        echo '<div><strong style="color: blue;">Recommended</strong></div>';
                      } elseif ($r['status'] == 2) {
                        echo '<div><strong style="color: limegreen;">Approved</strong></div>';
                      } elseif ($r['status'] == 3) {
                        echo '<div><strong style="color: red;">Rejected</strong></div>';
                      } elseif ($r['status'] == 4) {
                        echo '<div><strong style="color: blue;">Not Recommended</strong></div>';
                      } ?>
                    </td>
                    <td class="text-center">
                      <a href="<?php echo base_url("accounts/MyRazaRequest") ?>" title="View all my applications"><i class="vaa text-primary fa-solid fa-eye"></i></a>
                    </td>
                  </tr>
                <?php } ?>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card">
          <div class="card-header">RSVP
            <a href="<?php echo base_url("accounts/rsvp_list"); ?>" class="text-primary">Event RSVP</a>
          </div>
          <div class="card-body">
            <?php
            $approved_miqaats_count = 0;
            if (isset($miqaats) && is_array($miqaats)) {
              $today = strtotime('today');
              $approved_miqaats = array_filter($miqaats, function ($m) use ($today) {
                $status = null;
                $dateStr = null;
                if (is_array($m)) {
                  $status = $m['Janab-status'] ?? $m['janab_status'] ?? $m['status'] ?? null;
                  $dateStr = $m['date'] ?? $m['miqaat_date'] ?? $m['event_date'] ?? $m['start_date'] ?? null;
                } elseif (is_object($m)) {
                  $status = isset($m->{'Janab-status'}) ? $m->{'Janab-status'} : ($m->janab_status ?? $m->status ?? null);
                  $dateStr = $m->date ?? $m->miqaat_date ?? $m->event_date ?? $m->start_date ?? null;
                }
                $isApproved = false;
                if (is_string($status)) $isApproved = trim($status) === '1';
                elseif (is_numeric($status)) $isApproved = ((int)$status === 1);
                elseif (is_bool($status)) $isApproved = ($status === true);

                $isUpcoming = false;
                if (!empty($dateStr)) {
                  $d = strtotime($dateStr);
                  if ($d !== false) {
                    $isUpcoming = ($d >= $today);
                  }
                }
                return $isApproved && $isUpcoming;
              });
              $approved_miqaats_count = count($approved_miqaats);
            }
            $submitted_rsvps_count = (isset($rsvp_overview) && is_array($rsvp_overview)) ? count($rsvp_overview) : 0;
            $pending_rsvps_count = max(0, $approved_miqaats_count - $submitted_rsvps_count);
            ?>
            <div class="row text-center m-0 m-md-0">
              <div class="rsvp-card col-4 col-md-4 mb-3 mb-md-0 d-flex">
                <div class="card shadow-sm border-0 flex-fill d-flex flex-column justify-content-between" style="min-height:120px;">
                  <div class="card-body d-flex flex-column justify-content-between p-3">
                    <div class="rsvp-items text-dark" style="font-size: 0.9rem;">Approved Miqaats to RSVP</div>
                    <div class="rsvp-stat-number text-primary mt-auto" style="font-size:2.2rem; font-weight:700; line-height:1;"><a href="<?php echo base_url("accounts/rsvp_list"); ?>"><?php echo $approved_miqaats_count; ?></a></div>
                  </div>
                </div>
              </div>
              <div class="rsvp-card col-4 col-md-4 mb-3 mb-md-0 d-flex">
                <div class="card shadow-sm border-0 flex-fill d-flex flex-column justify-content-between" style="min-height:120px;">
                  <div class="card-body d-flex flex-column justify-content-between p-3">
                    <div class="rsvp-items text-dark" style="font-size: 0.9rem;">Pending RSVPs</div>
                    <div class="rsvp-stat-number text-warning mt-auto" style="font-size:2.2rem; font-weight:700; line-height:1;"><a href="<?php echo base_url("accounts/rsvp_list"); ?>"><?php echo $pending_rsvps_count; ?></a></div>
                  </div>
                </div>
              </div>
              <div class="rsvp-card col-4 col-md-4 d-flex">
                <div class="card shadow-sm border-0 flex-fill d-flex flex-column justify-content-between" style="min-height:120px;">
                  <div class="card-body d-flex flex-column justify-content-between p-3">
                    <div class="rsvp-items text-dark" style="font-size: 0.9rem;">Submitted RSVPs</div>
                    <div class="rsvp-stat-number text-success mt-auto" style="font-size:2.2rem; font-weight:700; line-height:1;"><a href="<?php echo base_url("accounts/rsvp_list"); ?>"><?php echo $submitted_rsvps_count; ?></a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1">
          <div class="card-header">
            FMB Thaali Signup
            <a href="<?php echo base_url("accounts/viewmenu"); ?>" target="_blank" class="text-primary">View Menu <i class="fa-solid fa-external-link"></i></a>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Dates</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?php echo isset($current_hijri_month_label) ? $current_hijri_month_label : ''; ?>
                    <div class="mt-2">
                      <small>Today's Thaali:
                        <?php if (isset($fmb_today_status)): ?>
                          <?php
                          $badgeText = isset($fmb_today_status['badge_text']) ? strtolower($fmb_today_status['badge_text']) : '';
                          ?>
                          <!-- Do not show a 'closed' badge on this dashboard; only show menu items if present -->
                          <?php if (!empty($fmb_today_status['menu_items'])): ?>
                            <span class="text-muted ml-1" style="font-size: 0.8rem;">(<?php echo htmlspecialchars(implode(', ', $fmb_today_status['menu_items'])); ?>)</span>
                          <?php endif; ?>
                        <?php endif; ?>
                      </small>
                    </div>
                  </td>
                  <td>
                    <?php
                    $signup_day_count = 0;
                    $signup_status_class = '';
                    $signup_status_text = '';
                    if (isset($signup_data) && !empty($signup_data)) {
                      $filtered = array_filter($signup_data, function ($item) {
                        return $item['want_thali'] == '1';
                      });
                      if (count($filtered) == 0) {
                        $signup_status_class = "primary";
                        $signup_status_text = "Sign-up Now";
                      } else if (count($filtered) < $signup_days) {
                        $signup_day_count = count($filtered);
                        $signup_status_class = "primary";
                        $signup_status_text = "Partially Signed Up";
                      } else {
                        $signup_status_class = "success";
                        $signup_status_text = "Signed up";
                      }
                    } else {
                      $signup_status_class = "primary";
                      $signup_status_text = "Sign-up Now";
                    }
                    ?>
                    <a href="<?php echo base_url("accounts/fmbweeklysignup"); ?>" class="btn btn-sm btn-<?php echo $signup_status_class; ?> text-white">
                      <?php echo $signup_status_text;
                      echo ($signup_day_count > 0 && $signup_day_count < $signup_days) ? " (" . $signup_day_count . " / " . $signup_days . ")" : ""; ?>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1">
          <div class="card-header">
            FMB Feedback
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Dates</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <?php echo isset($month_feedback_range) ? htmlspecialchars($month_feedback_range) : ''; ?>
                  </td>
                  <td>
                    <?php
                    $mf_class = isset($month_feedback_status_class) ? $month_feedback_status_class : 'secondary';
                    $mf_text  = isset($month_feedback_status_text) ? $month_feedback_status_text : 'No Sign Ups';
                    $mf_signed = isset($month_feedback_signed) ? (int)$month_feedback_signed : 0;
                    $mf_given  = isset($month_feedback_given) ? (int)$month_feedback_given : 0;
                    $show_counts = ($mf_signed > 0 && $mf_given > 0 && $mf_given < $mf_signed && $mf_text === 'Partially Given');
                    ?>
                    <a href="<?php echo base_url("accounts/FMBFeedback"); ?>" class="btn btn-sm btn-<?php echo $mf_class; ?> text-white">
                      <?php echo $mf_text; ?>
                      <?php if ($show_counts): ?> (<?php echo $mf_given; ?> / <?php echo $mf_signed; ?>)<?php endif; ?>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1 dues">
          <div class="card-header">
            <span>FMB Dues</span>
            <?php if (isset($fmb_takhmeen_details["total_due"]) && (float)$fmb_takhmeen_details["total_due"] > 0): ?>
              <a href="<?php echo base_url("accounts/viewfmbtakhmeen"); ?>" class="overall-due text-danger"><span class="badge bg-danger text-white">Pending</span></a>
            <?php endif; ?>
          </div>
          <div class="card-body p-3">
            <?php if (isset($fmb_takhmeen_details["total_due"]) && (float)$fmb_takhmeen_details["total_due"] > 0): ?>
              <div class="row g-2">
                <div class="col-12 col-md-12">
                  <a href="<?php echo base_url('accounts/viewfmbtakhmeen'); ?>" class="text-decoration-none d-block">
                    <div class="card shadow-sm text-center h-100 mini-stat-card">
                      <div class="card-body py-1 d-flex align-items-center justify-content-center flex-column">
                        <h6 class="text-muted mb-2">Total Years Due</h6>
                        <div class="h4 text-danger mb-0">&#8377;<?php echo format_inr_no_decimals($fmb_takhmeen_details['total_due'] ?? 0); ?></div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="text-left mt-3">
                <a href="<?php echo base_url("accounts/viewfmbtakhmeen"); ?>" class="btn btn-primary text-white" style="font-size:0.8rem; padding:.25rem .6rem; width:auto; min-width:140px; display:inline-block;">View Details</a>
              </div>
            <?php else: ?>
              <h6 class="m-1">No dues</h6>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1 dues">
          <div class="card-header">
            <span>Sabeel Dues</span>
            <?php if (isset($sabeel_takhmeen_details["total_due"]) && $sabeel_takhmeen_details["total_due"] > 0): ?>
              <a href="<?php echo base_url("accounts/viewsabeeltakhmeen"); ?>" class="overall-due text-danger"><span class="badge bg-danger text-white">Pending</span></a>
            <?php endif; ?>
          </div>
          <div class="card-body p-3">
            <?php
            // Use values computed in AccountM::get_member_total_sabeel_due
            $hasDue = isset($sabeel_takhmeen_details["total_due"]) && (float)$sabeel_takhmeen_details["total_due"] > 0;
            $currentCompositeYear = isset($sabeel_takhmeen_details['current_year']) ? (string)$sabeel_takhmeen_details['current_year'] : '';
            $cy_total = (float)($sabeel_takhmeen_details['current_year_total'] ?? 0);
            $cy_paid  = (float)($sabeel_takhmeen_details['current_year_paid'] ?? 0);
            $cy_due   = (float)($sabeel_takhmeen_details['current_year_due'] ?? max(0, $cy_total - $cy_paid));
            ?>
            <div class="row g-2">
              <div class="col-12 col-md-12">
                <a href="<?php echo base_url('accounts/viewsabeeltakhmeen'); ?>" class="text-decoration-none d-block">
                  <div class="card shadow-sm text-center h-100 mini-stat-card">
                    <div class="card-body py-1 d-flex align-items-center justify-content-center flex-column">
                      <h6 class="text-muted mb-2">Total Year Due</h6>
                      <div class="h4 text-danger mb-0">&#8377;<?php echo format_inr_no_decimals($sabeel_takhmeen_details['total_due'] ?? 0); ?></div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <?php if (!empty($currentCompositeYear)): ?>
              <div class="mt-2"><small class="text-muted">Current Year: <?php echo htmlspecialchars($currentCompositeYear); ?></small></div>
            <?php endif; ?>
            <div class="row g-2 mt-1">
              <div class="col-6 col-md-4">
                <a href="<?php echo base_url('accounts/viewsabeeltakhmeen'); ?>" class="text-decoration-none d-block">
                  <div class="card shadow-sm text-center h-100 mini-stat-card">
                    <div class="card-body py-1 d-flex align-items-center justify-content-center flex-column">
                      <h6 class="text-muted mb-2">Takhmeen</h6>
                      <div class="h5 text-primary mb-0">&#8377;<?php echo format_inr_no_decimals($cy_total); ?></div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="col-6 col-md-4">
                <a href="<?php echo base_url('accounts/viewsabeeltakhmeen'); ?>" class="text-decoration-none d-block">
                  <div class="card shadow-sm text-center h-100 mini-stat-card">
                    <div class="card-body py-1 d-flex align-items-center justify-content-center flex-column">
                      <h6 class="text-muted mb-2">Paid</h6>
                      <div class="h5 text-success mb-0">&#8377;<?php echo format_inr_no_decimals($cy_paid); ?></div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="col-6 col-md-4 mt-3 mt-md-0">
                <a href="<?php echo base_url('accounts/viewsabeeltakhmeen'); ?>" class="text-decoration-none d-block">
                  <div class="card shadow-sm text-center h-100 mini-stat-card">
                    <div class="card-body py-1 d-flex align-items-center justify-content-center flex-column">
                      <h6 class="text-muted mb-2">Pending</h6>
                      <div class="h5 text-danger mb-0">&#8377;<?php echo format_inr_no_decimals($cy_due); ?></div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="text-left mt-3">
              <a href="<?php echo base_url("accounts/viewsabeeltakhmeen"); ?>" class="btn btn-primary text-white" style="font-size:0.8rem; padding:.25rem .6rem; width:auto; min-width:140px; display:inline-block;">View Details</a>
            </div>
          </div>
        </div>
      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
        <div class="dashboard-card mx-1">
          <div class="card-header">
            <span>Corpus Funds</span>
          </div>
          <?php
          $cf_total   = isset($corpus_summary['total_per_family']) ? (float)$corpus_summary['total_per_family'] : 0;
          $cf_assgn   = isset($corpus_summary['assigned_total']) ? (float)$corpus_summary['assigned_total'] : 0;
          $cf_out     = isset($corpus_summary['outstanding']) ? (float)$corpus_summary['outstanding'] : 0;
          $fundsCnt   = isset($corpus_summary['funds_count']) ? (int)$corpus_summary['funds_count'] : 0;
          $fmt = function ($n) {
            return format_inr_no_decimals($n);
          };
          ?>
          <div class="card-body no-scroll" style="height:auto;">
            <div class="row mb-2 text-center">
              <div class="col-4 mb-2">
                <div class="mini-card">
                  <div class="stats-value text-success">₹<?php echo $fmt($cf_assgn); ?></div>
                  <div class="stats-label">Total Assigned</div>
                </div>
              </div>
              <div class="col-4 mb-2">
                <div class="mini-card">
                  <div class="stats-value text-danger">₹<?php echo $fmt($cf_out); ?></div>
                  <div class="stats-label">Outstanding</div>
                </div>
              </div>
            </div>
            <p class="text-center text-muted mb-2" style="font-size:0.8rem;">Funds: <?php echo $fundsCnt; ?></p>
            <div class="text-center"><a href="<?php echo base_url('accounts/corpusfunds'); ?>" class="btn btn-sm btn-outline-secondary">View Details</a></div>
          </div>
        </div>
      </div>


      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
            <div class="dashboard-card mx-1">
              <div class="card-header">
                <span>Wajebaat</span>
              </div>
              <?php
              $w = isset($wajebaat) ? $wajebaat : null;
              $wa_fmt = function ($n) { return format_inr_no_decimals($n ?? 0); };
              ?>
              <div class="card-body no-scroll" style="height:auto;">
                <div class="row mb-2 text-center">
                  <div class="col-6 mb-2">
                    <div class="mini-card">
                      <div class="stats-value text-success">₹<?php echo $wa_fmt($w['amount'] ?? 0); ?></div>
                      <div class="stats-label">Total Assigned</div>
                    </div>
                  </div>
                  <div class="col-6 mb-2">
                    <div class="mini-card">
                      <div class="stats-value text-danger">₹<?php echo $wa_fmt($w['due'] ?? 0); ?></div>
                      <div class="stats-label">Outstanding</div>
                    </div>
                  </div>
                </div>
                <p class="text-center text-muted mb-2" style="font-size:0.8rem;">Last updated: <?php
                    $last = '';
                    if (!empty($w['updated_at'])) $last = $w['updated_at'];
                    elseif (!empty($w['created_at'])) $last = $w['created_at'];
                    echo $last ? date('d-m-Y', strtotime($last)) : 'Not available';
                  ?></p>
                <div class="text-center"><a href="<?php echo base_url('accounts/wajebaat'); ?>" class="btn btn-sm btn-outline-secondary">View Details</a></div>
              </div>
            </div>

      </div>

      <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
            <div class="dashboard-card mx-1">
              <div class="card-header">
                <span>Qardan Hasana</span>
              </div>
              <?php
              $qh = isset($qardan_hasana) ? $qardan_hasana : null;
              $qh_fmt = function ($n) { return format_inr_no_decimals($n ?? 0); };
              ?>
              <div class="card-body no-scroll" style="height:auto;">
                <div class="row mb-2 text-center">
                  <div class="col-6 mb-2">
                    <div class="mini-card">
                      <div class="stats-value text-success">₹<?php echo $qh_fmt($qh['amount'] ?? 0); ?></div>
                      <div class="stats-label">Total Assigned</div>
                    </div>
                  </div>
                  <div class="col-6 mb-2">
                    <div class="mini-card">
                      <div class="stats-value text-danger">₹<?php echo $qh_fmt($qh['due'] ?? 0); ?></div>
                      <div class="stats-label">Outstanding</div>
                    </div>
                  </div>
                </div>
                <p class="text-center text-muted mb-2" style="font-size:0.8rem;">Last updated: <?php
                    $last = '';
                    if (!empty($qh['updated_at'])) $last = $qh['updated_at'];
                    elseif (!empty($qh['created_at'])) $last = $qh['created_at'];
                    echo $last ? date('d-m-Y', strtotime($last)) : 'Not available';
                  ?></p>
                <div class="text-center"><a href="<?php echo base_url('accounts/qardan_hasana'); ?>" class="btn btn-sm btn-outline-secondary">View Details</a></div>
              </div>
            </div>

      </div>
    </div>
  </div>
  <div class="continer d-flex justify-content-center">
    <div class="row mt-4">
      <!-- <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('accounts/Umoor') ?>">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">12 Umoor & Event Raza</div>
            <i class="fa-solid icon fa-clipboard-list"></i>
          </div>
        </div>
      </a>
      <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('accounts/MyRazaRequest') ?>">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">My Applications</div>
            <i class="fa-solid icon fa-hands-holding"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('accounts/miqaat') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">Event RSVP</div>
            <i class="fa-solid icon fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <div class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">FMB Menu & Feedback</div>
            <i class="fa-solid icon fa-comments"></i>
          </div>
        </div>
      </div>
      <a href="<?php echo base_url('accounts/profile') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">My Profile</div>
            <i class="fa-solid icon fa-clipboard-user"></i>
          </div>
        </div>
      </a>
      <?php
      if ($user_name == $hof_data) {
        echo '<a href="' . base_url('accounts/appointment') . '" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Amil Saheb\'s Appointment</div>
                        <i class="fa-solid icon fa-calendar-days"></i>
                    </div>
                </div>
            </a>';
      }
      ?> -->

      <!-- <div class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body">
            <div class="title">Finance</div>
            <i class="fa-solid icon fa-money-check-alt"></i>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</div>
<script>
  const colors = [
    ["rgb(0, 106, 63)", "rgb(255, 255, 255)"],
    ["rgb(39, 174, 96)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
    ["rgb(243, 156, 18)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
    ["rgb(243, 156, 18)", "rgb(255, 255, 255)"],
    ["rgb(211, 84, 0)", "rgb(255, 255, 255)"],
    ["rgb(0, 106, 63)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(39, 174, 96)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
    ["rgb(135, 0, 0)", "rgb(255, 255, 255)"],
    ["rgb(211, 84, 0)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(135, 0, 0)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(255, 247, 230)", "rgb(0, 0, 0)"],
    ["rgb(243, 156, 18)", "rgb(255, 255, 255)"],
    ["rgb(135, 0, 0)", "rgb(255, 255, 255)"],
    ["rgb(211, 84, 0)", "rgb(255, 255, 255)"],
    ["rgb(0, 106, 63)", "rgb(255, 255, 255)"],
    ["rgb(39, 174, 96)", "rgb(255, 255, 255)"],
  ]
  $(document).ready(function() {
    $(".action-btn").each(function(i, el) {
      this.style.backgroundColor = colors[i][0];
      this.style.color = colors[i][1];
    });
  })
</script>