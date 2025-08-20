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
    height: 50px;
    color: black;
    border-radius: 5px;
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
    margin-top: 20px;
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
    font-style: italic;
  }

  .status {
    font-weight: 400 !important;
    font-size: 10.5pt;
  }

  .hirji-date {
    font-weight: 700;
    font-family: "Amita", cursive;
  }

  .rsvp-btn {
    background-color: #3498db;
    color: white !important;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
  }

  .sign-up-closed {
    font-size: 9pt;
    color: #666;
    font-weight: bold;
  }

  .sign-up-open {
    font-size: 9pt;
    font-weight: bold;
  }

  .overall-due:hover {
    text-decoration: underline !important;
    color: blue !important;
  }

  /* New dashboard design  */
</style>
<div class="container margintopcontainer">
  <h1 class="text-center heading pt-5 mb-3">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
  <p class="hirji-date text-center mb-4"><b><?php echo $hijri_date ?></b></p>
  <hr>
  <div class="row justify-content-center">
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/Umoor') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-edit fa-2x mr-2"></i>
        <span class="action-btn-title">New Raza</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/Calendar') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-calendar fa-2x mr-2"></i>
        <span class="action-btn-title">View Calendar</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/FMBWeeklySignUp') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-plate-wheat fa-2x mr-2"></i>
        <span class="action-btn-title">FMB Weekly Signup</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/MyRazaRequest') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-hands-holding fa-2x mr-2"></i>
        <span class="action-btn-title">My Applications</span>
      </a>
    </div>
    <div class="col-6 mt-3 col-md-3 col-xl-2">
      <a href="<?php echo base_url('accounts/profile') ?>" class="action-btn d-flex justify-content-center align-items-center text-center py-3 text-decoration-none">
        <i class="abi fa-solid fa-clipboard-user fa-2x mr-2"></i>
        <span class="action-btn-title">My Profile</span>
      </a>
    </div>
  </div>
  <div class="d-flex justify-content-center">
    <!-- New dashboard design -->
    <div class="dashboard-container m-3">
      <div class="row">
        <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
          <div class="dashboard-card mx-1">
            <div class="card-header">
              <span>My Raza Request</span>
              <a href="<?php echo base_url("accounts/Umoor") ?>" class="text-primary">+ Create a New Raza</a>
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
          <div class="dashboard-card mx-1">
            <div class="card-header">RSVP
              <a href="<?php echo base_url("accounts/miqaat"); ?>" class="text-primary">Event RSVP</a>
            </div>
            <div class="card-body">
              <ul class="list-group">
                <li class="list-group-item hide-override-xs">
                  <div class="row">
                    <h5 class="mt0 mb0 col-6 col-sm-9">Miqaat</h5>
                    <h5 class="mt0 mb0 col-6 col-sm-3">RSVP</h5>
                  </div>
                </li>
                <?php foreach ($rsvp_list as $rv) { ?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-sm-12 col-md-9">
                        <h5 class="mt0"><a href="<?php echo base_url('accounts/Rsvp/') . $rv['id'] ?>"
                            class="fontblack">
                            <?php echo $rv['name'] ?>
                          </a></h5>
                        <div class="fontcertgreen fontbold">
                          <?php echo date('D, d M', strtotime($rv['date'])) ?> @
                          <?php echo $rv['time'] ?>
                        </div>
                        <div class="font-italic font-sml-1 fontgray">
                          <?php echo $rv['hijri_date'] ?>
                        </div>
                        <div class="text-info fontbold mtop-5">Kindly RSVP by
                          <?php echo date('D, d M', strtotime($rv['expired'])) ?>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-3 rsvp-col">
                        <div class="font-lvl-3 dinblock-xs">
                          <div class="mbm">
                            <?php if ($rv['attend'] == null) {
                              echo 'No RSVP Received';
                            } else {
                              echo '<strong style="color:#799840;">Done</strong>';
                            } ?>
                          </div>
                          <hr class="hide dblock-xs">
                          <a href="<?php echo base_url('accounts/Rsvp/') . $rv['id'] ?>"
                            class="btn btn-default btn-sm rsvp-btn">
                            RSVP</a>
                        </div>
                      </div>
                    </div>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>


        <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
          <div class="dashboard-card mx-1">
            <div class="card-header">
              FMB Weekly Signup
              <a href="<?php echo base_url("accounts/ViewMenu"); ?>" target="_blank" class="text-primary">View Menu <i class="fa-solid fa-external-link"></i></a>
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
                      <?php
                      $today = date('Y-m-d');
                      echo date('d-m-Y', strtotime('monday next week', strtotime($today))) . " - " . date('d-m-Y', strtotime('saturday next week', strtotime($today)));

                      $dayNumber = date('N');
                      echo $dayNumber > 5 ? "<div class='sign-up-closed mt-2'>Sign-up closed!</div>" : "<div class='sign-up-open text-success mt-2'>Sign-up Open!</div>";
                      ?>
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
                          $signup_status_text = "Not Signed Up";
                        } else if (count($filtered) < 6) {
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
                      <a href="<?php echo base_url("accounts/FMBWeeklySignUp"); ?>" class="btn btn-sm btn-<?php echo $signup_status_class; ?> text-white">
                        <?php echo $signup_status_text;
                        echo ($signup_day_count > 0 && $signup_day_count < 6) ? " (" . $signup_day_count . " / 6)" : ""; ?>
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
                      <?php
                      $today = date('Y-m-d');
                      echo date('d-m-Y', strtotime('monday this week', strtotime($today))) . " - " . date('d-m-Y', strtotime('saturday this week', strtotime($today)));
                      ?>
                    </td>
                    <td>
                      <?php
                      $valid_feedback_day = 0;
                      $feedback_day_count = 0;
                      $feedback_status_class = '';
                      $feedback_status_text = '';
                      if (isset($feedback_data) && !empty($feedback_data)) {
                        $valid_feedback_day = count(array_filter($feedback_data, function ($item) {
                          return $item['want_thali'] == 1;
                        }));
                        $filtered = array_filter($feedback_data, function ($item) {
                          return (int)$item['status'] == 1;
                        });
                        if (count($filtered) == 0) {
                          $feedback_status_class = "primary";
                          $feedback_status_text = "Not Given";
                        } else if (count($filtered) < $valid_feedback_day) {
                          $feedback_day_count = count($filtered);
                          $feedback_status_class = "primary";
                          $feedback_status_text = "Partially Given";
                        } else {
                          $feedback_status_class = "success";
                          $feedback_status_text = "Given";
                        }
                      } else {
                        $feedback_status_class = "secondary";
                        $feedback_status_text = "No sign ups found";
                      }
                      ?>
                      <a href="<?php echo base_url("accounts/FMBFeedback"); ?>" class="btn btn-sm btn-<?php echo $feedback_status_class; ?> text-white">
                        <?php echo $feedback_status_text;
                        echo ($feedback_day_count > 0 && $feedback_day_count < 6) ? " (" . $feedback_day_count . " / " . $valid_feedback_day . ")" : ""; ?>
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
              <h5 class="mb-0">
                <i class="bi bi-cash-coin me-2 text-success"></i> FMB Dues
              </h5>
              <?php if (isset($fmb_takhmeen_details["total_due"]) && $fmb_takhmeen_details["total_due"] > 0): ?>
                <a href="<?php echo base_url("accounts/viewfmbtakhmeen"); ?>" class="overall-due text-danger"><span class="badge bg-danger text-white">Pending</span></a>
              <?php endif; ?>
            </div>
            <div class="card-body p-3 row">
              <?php if (isset($fmb_takhmeen_details["total_due"]) && $fmb_takhmeen_details["total_due"] > 0): ?>
                <div class="row col-12 col-md-12 align-items-center">
                  <div class="col-12 col-md-8">
                    <h5 class="fw-bold mb-0">Overall Due: <a href="<?php echo base_url("accounts/viewfmbtakhmeen"); ?>" class="overall-due text-danger"><span>&#8377;<?php echo isset($fmb_takhmeen_details["total_due"]) ? $fmb_takhmeen_details["total_due"] : ""; ?></span></a></h5>
                  </div>
                  <div class="col-12 col-md-4 text-right">
                    <a href="<?php echo base_url("accounts/viewfmbtakhmeen"); ?>" class="btn btn-sm btn-primary text-white">View Details</a>
                  </div>
                </div>
                <div class="col-12 col-md-12">
                  <small class="text-muted">
                    Paid: &#8377;<?php echo $fmb_takhmeen_details["total_paid"]; ?> <br> Remaining: &#8377;<?php echo $fmb_takhmeen_details["total_due"]; ?></small>
                </div>
              <?php else: ?>
                <h6 class="m-1">No dues</h6>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
          <div class="dashboard-card mx-1">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bi bi-cash-coin me-2 text-success"></i> Sabeel Dues
              </h5>
              <?php if (isset($sabeel_takhmeen_details["total_due"]) && $sabeel_takhmeen_details["total_due"] > 0): ?>
                <a href="<?php echo base_url("accounts/viewsabeeltakhmeen"); ?>" class="overall-due text-danger"><span class="badge bg-danger text-white">Pending</span></a>
              <?php endif; ?>
            </div>
            <div class="card-body p-3 row">
              <?php if (isset($sabeel_takhmeen_details["total_due"]) && $sabeel_takhmeen_details["total_due"] > 0): ?>
                <div class="row col-12 col-md-12 align-items-center">
                  <div class="col-12 col-md-8">
                    <h5 class="fw-bold mb-0">Overall Due: <a href="<?php echo base_url("accounts/viewsabeeltakhmeen"); ?>" class="overall-due text-danger"><span>&#8377;<?php echo isset($sabeel_takhmeen_details["total_due"]) ? $sabeel_takhmeen_details["total_due"] : ""; ?></span></a></h5>
                  </div>
                  <div class="col-12 col-md-4 text-right">
                    <a href="<?php echo base_url("accounts/viewsabeeltakhmeen"); ?>" class="btn btn-sm btn-primary text-white">View Details</a>
                  </div>
                </div>
                <div class="row col-12 col-md-12 mt-3">
                  <div class="col-6">
                    <small class="text-muted">
                      <b>Establishment Sabeel:</b>
                      <br>
                      Paid: &#8377;<?php echo $sabeel_takhmeen_details["establishment_paid"]; ?> <br> Remaining: &#8377;<?php echo $sabeel_takhmeen_details["establishment_due"]; ?></small>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">
                      <b>Residential Sabeel:</b>
                      <br>
                      Paid: &#8377;<?php echo $sabeel_takhmeen_details["residential_paid"]; ?> <br> Remaining: &#8377;<?php echo $sabeel_takhmeen_details["residential_due"]; ?></small>
                  </div>
                </div>
              <?php else: ?>
                <h6 class="m-1">No dues</h6>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="p-0 p-md-2 col-12 col-md-6 col-xl-6">
          <div class="dashboard-card mx-1">
            <div class="card-header">My Appointments</div>
            <div class="card-body">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- New dashboard design -->
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
    ["rgb(39, 174, 96)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
    ["rgb(243, 156, 18)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(0, 106, 63)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(255, 247, 230)", "rgb(0, 0, 0)"],
    ["rgb(135, 0, 0)", "rgb(255, 255, 255)"],
    ["rgb(211, 84, 0)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
    ["rgb(243, 156, 18)", "rgb(255, 255, 255)"],
    ["rgb(135, 0, 0)", "rgb(255, 255, 255)"],
    ["rgb(211, 84, 0)", "rgb(255, 255, 255)"],
    ["rgb(0, 106, 63)", "rgb(255, 255, 255)"],
    ["rgb(192, 57, 43)", "rgb(255, 255, 255)"],
    ["rgb(39, 174, 96)", "rgb(255, 255, 255)"],
    ["rgb(41, 128, 185)", "rgb(255, 255, 255)"],
    ["rgb(142, 68, 173)", "rgb(255, 255, 255)"],
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