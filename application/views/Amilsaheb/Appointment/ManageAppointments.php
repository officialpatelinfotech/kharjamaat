<style>
  /* Styles for 'Not Attended' status */
  .status-not-attended {
    color: #d9534f;
    font-weight: bold;
  }

  /* Styles for 'Attended' status */
  .status-attended {
    color: #5cb85c;
    font-weight: bold;
  }

  /* Stats cards */
  .stats-grid {
    display: flex;
    gap: 1rem;
    align-items: stretch;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 1rem;
  }

  .stat-card {
    display: flex;
    align-items: center;
    padding: 1rem 1.2rem;
    border-radius: 8px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    min-width: 220px;
    color: #fff;
  }

  .stat-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-right: 1rem;
    opacity: 0.95;
  }

  .stat-body {
    flex: 1;
  }

  .stat-label {
    font-size: 1.05rem;
    font-weight: 600;
    opacity: 0.95;
  }

  .stat-value {
    font-size: 2.25rem;
    font-weight: 700;
    line-height: 1;
    margin-top: 0.15rem;
  }

  .stat-blue {
    background-color: #2a88c9;
  }

  .stat-green {
    background-color: #27ae60;
  }

  .stat-red {
    background-color: #c0392b;
  }

  @media (max-width: 576px) {
    .stat-card {
      min-width: 100%;
      padding: 0.9rem;
    }

    .stat-icon {
      width: 54px;
      height: 54px;
      font-size: 1.1rem;
    }

    .stat-value {
      font-size: 1.75rem;
    }
  }

  /* Responsive table -> stacked cards on small screens */
  @media (max-width: 767.98px) {
    table.responsive-table thead {
      display: none;
    }

    table.responsive-table,
    table.responsive-table tbody,
    table.responsive-table tr,
    table.responsive-table td {
      display: block;
      width: 100%;
    }

    table.responsive-table tr {
      margin-bottom: 1rem;
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: 6px;
      padding: 0.75rem;
      background: #fff;
    }

    table.responsive-table td {
      padding: 0.35rem 0.5rem;
      text-align: left;
      border: none;
      position: relative;
    }

    table.responsive-table td[data-label]::before {
      content: attr(data-label) ": ";
      font-weight: 600;
      display: inline-block;
      width: 36%;
      color: #333;
    }

    .card-body p.h2 {
      font-size: 1.5rem;
    }
  }
</style>
<div class="container mt-5 pt-5">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("amilsaheb"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h1 class="text-center heading mb-4">Today's Appointment</h1>
  <hr>
  <div class="stats-grid">
    <div class="stat-card stat-blue">
      <div class="stat-icon" style="background: rgba(255,255,255,0.12);">
        <i class="fa fa-list" aria-hidden="true"></i>
      </div>
      <div class="stat-body">
        <div class="stat-label">Total</div>
        <div class="stat-value"><?php echo $total; ?></div>
      </div>
    </div>

    <div class="stat-card stat-green">
      <div class="stat-icon" style="background: rgba(255,255,255,0.12);">
        <i class="fa fa-check" aria-hidden="true"></i>
      </div>
      <div class="stat-body">
        <div class="stat-label">Attended</div>
        <div class="stat-value"><?php echo $attended; ?></div>
      </div>
    </div>

    <div class="stat-card stat-red">
      <div class="stat-icon" style="background: rgba(255,255,255,0.12);">
        <i class="fa fa-clock-o" aria-hidden="true"></i>
      </div>
      <div class="stat-body">
        <div class="stat-label">Pending</div>
        <div class="stat-value"><?php echo $pending; ?></div>
      </div>
    </div>
  </div>
  <!--<div class="row text-center">-->
  <!--    <div class="col">-->
  <!--        <a href="<?php echo base_url('amilsaheb/all_appointment') ?>" style="color: black;text-decoration:underline">All Appointment's</a>-->
  <!--    </div>-->
  <!--</div>-->
  <div class="row mt-4">
    <div class="table-responsive">
      <table class="table text-center table-bordered table-striped responsive-table">
        <thead>
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Date</th>
            <th scope="col">ITS</th>
            <th scope="col">Name</th>
            <th scope="col">Mobile</th>
            <th scope="col">Time</th>
            <th scope="col">Purpose</th>
            <th scope="col">Details</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($appointment_list as $key => $value) { ?>
            <tr>
              <td data-label="S.No."><?php echo $key + 1 ?></td>
              <td data-label="Date"><?php echo date('D, d M ', strtotime($value['date'])) ?></td>
              <td data-label="ITS"><?php echo $value['its'] ?></td>
              <td data-label="Name"><?php echo $value['name'] ?></td>
              <td data-label="Mobile"><?php echo isset($value['mobile']) ? $value['mobile'] : '' ?></td>
              <td data-label="Time"><?php echo $value['time'] ?></td>
              <td data-label="Purpose"><?= isset($value['purpose']) ? htmlspecialchars($value['purpose']) : '' ?></td>
              <td data-label="Details"><?= isset($value['other_details']) ? nl2br(htmlspecialchars($value['other_details'])) : '' ?></td>
              <td data-label="Status" class="<?= ($value['status'] == 0) ? 'status-not-attended' : 'status-attended'; ?>">
                <?= ($value['status'] == 0) ? 'Pending' : 'Attended'; ?></td>
              <td class="text-center">
                <?php if ($value['status'] == 1) { ?>
                  <a href="<?php echo base_url('amilsaheb/update_appointment_list/') . $value['id'] ?>" data-toggle="tooltip" data-placement="top" title="Mark as Pending">
                    <svg fill="#68d241" width="25px" height="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                      <g id="SVGRepo_iconCarrier">
                        <path d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z"></path>
                      </g>
                    </svg>
                  </a>
                <?php } else { ?>

                  <a href="<?php echo base_url('amilsaheb/update_appointment_list/') . $value['id'] ?>" data-toggle="tooltip" data-placement="top" title="Mark as Attended">
                    <svg fill="#383937" width="25px" height="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                      <g id="SVGRepo_iconCarrier">
                        <path d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z"></path>
                      </g>
                    </svg>
                  </a>
                <?php } ?>
              </td>
            </tr>
          <?php }
          ?>

        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
  $(function() {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>