<style>
  .clickable-row:hover {
    background-color: #eeeeee !important;
    cursor: pointer;
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
  <h2 class="heading text-center pt-5 mb-4">Delivery Dashboard</h2>
  <div class="row mb-4 mx-4 mt-md-0 pt-2 pb-2">
    <div class="container col-12 col-md-2">
      <a href="<?php echo base_url("Umoor") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="container col-12 col-md-10 mt-3 mt-md-0 text-right">
      <a href="<?php echo base_url("Umoor/substitutedeliveryperson"); ?>" id="sub-dp-btn" class="btn btn-warning ml-auto mr-3 mb-3 mb-md-0">Substitute Delivery Person</a>
      <a href="<?php echo base_url("Umoor/permanentassignment"); ?>" class="btn btn-primary ml-auto mr-3 mb-3 mb-md-0">Permanent Delivery Person</a>
      <a href="<?php echo base_url("Umoor/managedeliveryperson"); ?>" class="btn btn-secondary ml-auto">Manage Delivery Person</a>
    </div>
  </div>
  <div class="container">
    <form method="POST" action="<?php echo base_url("Umoor/substitutedeliveryperson"); ?>">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>Hijri Date</th>
            <th>Eng Date</th>
            <th>Day</th>
            <th>Thali Signup Count</th>
            <th>Thali Not Signup Count</th>
            <th>Count of Delivery Person</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($pending_days_in_year as $key => $value) {
          ?>
            <tr class="clickable-row" data-href="<?php echo base_url("Umoor/signupforaday/" . $value["greg_date"]); ?>">
              <td class="substitute-date-td" onclick="document.getElementById('substitute-date-<?php echo $value['id']; ?>').click()"><input type="checkbox" id="substitute-date-<?php echo $value["id"]; ?>" class="substitute-date" name="substitute-date[]" value="<?php echo $value["greg_date"]; ?>"></td>
              <td><?php echo $value["hijri_date"]; ?></td>
              <td><?php echo date("d-m-Y", strtotime($value["greg_date"])); ?></td>
              <td><?php echo date("l", strtotime($value["greg_date"])); ?></td>
              <td><?php echo $value["signup_count"]; ?></td>
              <td><?php echo $value["not_signup_count"]; ?></td>
              <td><?php echo $value["delivery_person_count"]; ?></td>
              <td><button class="view-details btn btn-sm btn-primary">View</button></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      <button id="submit-sub-btn" class="btn btn-success hidden">Submit</button>
    </form>
  </div>
</div>
<script>
  $(document).ready(function($) {
    $(".clickable-row").click(function() {
      window.location = $(this).data("href");
    });

    $(".substitute-date-td, .substitute-date").click(function(e) {
      e.stopPropagation();
    });

    function updateCheckedCount() {
      const checkedCount = $('input[type="checkbox"]:checked').length;
      if (checkedCount) {
        $("#submit-sub-btn").removeClass("hidden");
      } else {
        $("#submit-sub-btn").addClass("hidden");
      }
      return checkedCount;
    }

    // Listen for changes on all checkboxes
    $('input[type="checkbox"]').on('change', updateCheckedCount);

    $("#submit-sub-btn").on("click", function(e) {
      if (updateCheckedCount() < 1) {
        alert("Kindly select the date for substitution.")
        e.preventDefault();
      }
    });

    $("#sub-dp-btn").on("click", function(e) {
      e.preventDefault();
      if (updateCheckedCount() < 1) {
        alert("Kindly select the date for substitution.")
      } else {
        $("#submit-sub-btn").click();
      }
    });

    $(".view-details").on("click", function(e) {
      e.preventDefault();
    });
  });
</script>