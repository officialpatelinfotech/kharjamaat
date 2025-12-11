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

  /* Horizontal + vertical scroll with sticky header */
  .table-h-scroll {
    overflow-x: auto;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    max-height: 70vh;
    position: relative;
  }

  .table-h-scroll table {
    min-width: 1060px;
    /* adjust as needed */
    width: 100%;
    /* border-collapse: separate; */
    /* helps sticky headers in some browsers */
  }

  .table-h-scroll thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #343a40;
    /* ensure same as .thead-dark */
    color: #fff;
  }

  /* Optional: keep first column (checkbox) visible when horizontal scrolling */
  .table-h-scroll tbody td:first-child,
  .table-h-scroll thead th:first-child {
    position: sticky;
    left: 0;
    z-index: 6;
    background: #ffffff;
  }

  .table-h-scroll thead th:first-child {
    background: #343a40;
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="col-12 mb-3 mb-md-0">
    <a href="<?php echo isset($from) ? base_url($from) : base_url("anjuman/fmbthaali"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-4">Delivery Dashboard</h4>
  <div class="col-12 text-right mb-4">
    <div class="border rounded shadow-sm p-2">
      <a href="<?php echo base_url("common/currentsubstitutions"); ?>" class="btn btn-dark mr-3 mb-3 mb-md-0">Current Substitutions</a>
      <a href="<?php echo base_url("common/substitutedeliveryperson"); ?>" id="sub-dp-btn" class="btn btn-warning mr-3 mb-3 mb-md-0">Substitute Delivery Person</a>
      <a href="<?php echo base_url("common/permanentassignment"); ?>" class="btn btn-primary mr-3 mb-3 mb-md-0">Permanent Delivery Person</a>
      <a href="<?php echo base_url("common/managedeliveryperson"); ?>" class="btn btn-secondary">Manage Delivery Person</a>
    </div>
  </div>
  <div class="container">
    <form method="POST" action="<?php echo base_url("common/substitutedeliveryperson"); ?>">
      <div class="card rounded">
        <div class="card-header"></div>
        <div class="card-body m-0 p-0 table-h-scroll">
          <table class="table table-striped table-bordered">
            <thead class="thead-dark">
              <tr>
                <th></th>
                <th>Day</th>
                <th>Eng Date</th>
                <th>Hijri Date</th>
                <th>Thaali Signup Count</th>
                <th>Thaali Not Signup Count</th>
                <th>Count of Delivery Person</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($pending_days_in_year as $key => $value) {
              ?>
                <tr class="clickable-row" data-href="<?php echo base_url("common/signupforaday/" . $value["greg_date"]); ?>">
                  <td class="substitute-date-td" onclick="document.getElementById('substitute-date-<?php echo $value['id']; ?>').click()"><input type="checkbox" id="substitute-date-<?php echo $value["id"]; ?>" class="substitute-date" name="substitute-date[]" value="<?php echo $value["greg_date"]; ?>"></td>
                  <td><?php echo date("l", strtotime($value["greg_date"])); ?></td>
                  <td><?php echo date("d-m-Y", strtotime($value["greg_date"])); ?></td>
                  <td><?php echo $value["hijri_date"]; ?></td>
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
        </div>
      </div>
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