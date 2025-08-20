<style>
  .form-legend {
    font-size: 18px;
  }

  #submit-sub-btn {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 20px;
    animation: pulse 1.2s infinite ease-in-out;
  }

  #submit-sub-btn:hover {
    animation-play-state: paused;
  }

  @keyframes pulse {
    0% {
      transform: translateX(-50%) scale(1);
    }

    50% {
      transform: translateX(-50%) scale(0.9);
    }

    100% {
      transform: translateX(-50%) scale(1);
    }
  }

  .hidden {
    display: none;
  }
</style>
<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-2">Substitute Delivery Person</h2>
  <div class="row col-12 col-md-12 mb-3">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("Umoor/deliverydashboard") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-12 col-md-6 text-right">
      <a href="<?php echo base_url("Umoor/managedeliveryperson"); ?>" class="btn btn-secondary">Manage Delivery Person</a>
    </div>
  </div>
  <form method="POST" action="<?php echo base_url("Umoor/assigndeliveryperson"); ?>" id="assign-delivery-person">
    <legend class="form-legend text-center pt-2 pb-2 text-primary">From <?php echo $substitute_date[0] . " to " . $substitute_date[count($substitute_date) - 1] ?></legend>
    <input type="hidden" name="start_date" value="<?php echo $substitute_date[0]; ?>">
    <input type="hidden" name="end_date" value="<?php echo $substitute_date[count($substitute_date) - 1]; ?>">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Sr. No.</th>
          <th>Mumineen Name</th>
          <th>Sector</th>
          <th>Sub-sector</th>
          <th>Reg. Delivery Person</th>
          <th>Sub. Delivery Person</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($all_users)) {
          foreach ($all_users as $key => $user) {
        ?>
            <tr>
              <td>
                <?php echo $key + 1; ?>
              </td>
              <td><?php echo $user["First_Name"] . " " . $user["Surname"]; ?></td>
              <td><?php echo $user["Sector"]; ?></td>
              <td><?php echo $user["Sub_Sector"]; ?></td>
              <td>
                <?php echo $user["delivery_person_name"]; ?>
              </td>
              <td>
                <select class="form-control sub-deliver-person" name="sub-data[]">
                  <option value="">-----</option>
                  <?php
                  foreach ($all_dp as $dp) {
                    if ($dp['id'] == $user["dp_id"]) continue;
                  ?>
                    <option value="<?php echo $user['ITS_ID'] . '|' . $dp['id']; ?>"><?php echo $dp["name"]; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </td>
            </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
    <button id="submit-sub-btn" class="btn btn-success hidden">Submit</button>
  </form>
</div>
<script>
  function updateSelectCount() {
    const selectedCount = $('select').filter(function() {
      return $(this).val() !== ''; // Only count non-empty selections
    }).length;

    if (selectedCount) {
      $("#submit-sub-btn").removeClass("hidden");
    } else {
      $("#submit-sub-btn").addClass("hidden");
    }

    return selectedCount;
  }

  // Listen for changes on all selects
  $('select').on('change', updateSelectCount);

  $("#submit-sub-btn").on("click", function(e) {
    if (updateSelectCount() < 1) {
      alert("No substitutions were made.")
      e.preventDefault();
    } else {
      $result = confirm("Are sure you want to move ahead with the substitution?");
      if (!$result) {
        e.preventDefault();
      }
    }
  });
</script>