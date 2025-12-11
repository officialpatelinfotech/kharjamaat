<style>
  .hidden {
    display: none;
  }
  body {
    background: #fff;
  }
  .theme-green {
    background: rgb(0, 106, 63) !important;
    color: #fff !important;
    border-color: rgb(0, 106, 63) !important;
  }
  .theme-green-outline {
    background: #fff !important;
    color: rgb(0, 106, 63) !important;
    border: 2px solid rgb(0, 106, 63) !important;
  }
  .theme-green-outline:hover, .theme-green:hover {
    background: rgb(0, 106, 63) !important;
    color: #fff !important;
    border-color: rgb(0, 106, 63) !important;
  }
  .table thead.thead-dark th {
    background: rgb(0, 106, 63);
    color: #fff;
    border-color: rgb(0, 106, 63);
  }
  .btn-info {
    background: rgb(0, 106, 63) !important;
    border-color: rgb(0, 106, 63) !important;
    color: #fff !important;
  }
  .btn-success {
    background: rgb(0, 106, 63) !important;
    border-color: rgb(0, 106, 63) !important;
    color: #fff !important;
  }
  .btn-primary {
    background: rgb(0, 106, 63) !important;
    border-color: rgb(0, 106, 63) !important;
    color: #fff !important;
  }
  .border {
    border-color: rgb(0, 106, 63) !important;
  }
  .rounded {
    border-radius: 10px !important;
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="row mb-4 p-0">
    <div class="col-12 p-0">
      <a href="<?php echo base_url("admin/managefmbsettings"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h4 class="heading text-center mb-4">FMB Extra Contribution Master</h4>
  <div class="row mb-4">
    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" id="filter-form" class="row">
        <select name="filter_status" id="filter-status" class="form-control col-6">
          <option value="">Select Status</option>
          <option value="1" <?php echo isset($filter_status) ? ($filter_status === "1" ? "selected" : "") : ""; ?>>Active</option>
          <option value="0" <?php echo isset($filter_status) ? ($filter_status === "0" ? "selected" : "") : ""; ?>>Inactive</option>
        </select>
        <a href="<?php echo base_url("admin/fmbgeneralcontributionmaster"); ?>" class="col-2">
          <button class="btn btn-outline-secondary"><i class="fa-solid fa-times"></i></button>
        </a>
      </form>
    </div>
    <div class="col-12 col-md-6 p-0 text-right">
      <button id="add-contri-type" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Contribution Type</button>
    </div>
    <div id="add-contri-type-form" class="col-12 border rounded pt-3 mt-3 hidden">
      <form method="POST" action="<?php echo base_url("admin/addfmbcontritype"); ?>" class="row col-12">
        <select class="form-control col-12 col-md-4 mr-3" name="fmb_type" id="fmb-type" required>
          <option value="">Select FMB Type</option>
          <option value="Thaali">Thaali</option>
          <option value="Niyaz">Niyaz</option>
        </select>
        <input type="text" class="form-control col-12 col-md-4 mr-3" name="contri_for" placeholder="Enter contribution type name" required>
        <button type="submit" class="btn btn-success">
          Submit
        </button>
        <button id="hide-add-contri-type-form" class="btn btn-secondary ml-3">
          <i class="fa-solid fa-times"></i>
        </button>
      </form>
    </div>
  </div>
  <div class="row mb-4 p-0 responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Contribution Type</th>
          <th>FMB Type</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($all_fmbgc)): ?>
          <?php foreach ($all_fmbgc as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td>
                <p id="contri-for-<?php echo $value["id"]; ?>"><?php echo $value["name"]; ?></p>
                <input name="edit_contri_for" value="<?php echo $value["name"]; ?>" id="edit-contri-for-<?php echo $value["id"]; ?>" class="hidden form-control">
              </td>
              <td>
                <p id="fmb-type-<?php echo $value["id"]; ?>"><?php echo $value["fmb_type"]; ?></p>
                <select name="edit_fmb_type" id="edit-fmb-type-<?php echo $value["id"]; ?>" class="hidden form-control">
                  <option value="">Select FMB Type</option>
                  <option value="Thaali" <?php echo $value["fmb_type"] === "Thaali" ? "selected" : ""; ?>>Thaali</option>
                  <option value="Niyaz" <?php echo $value["fmb_type"] === "Niyaz" ? "selected" : ""; ?>>Niyaz</option>
                </select>
              </td>
              <td>
                <p id="status-<?php echo $value["id"]; ?>"><?php echo $value["status"] ? "Active" : "Inactive"; ?></p>
                <select name="edit_status" id="edit-status-<?php echo $value["id"]; ?>" class="hidden form-control">
                  <option value="">Select Status</option>
                  <option value="1" <?php echo $value["status"] === "1" ? "selected" : ""; ?>>Active</option>
                  <option value="0" <?php echo $value["status"] === "0" ? "selected" : ""; ?>>Inactive</option>
                </select>
              </td>
              <td>
                <button class="edit-fmbgc-btn btn btn-sm btn-secondary" id="edit-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>"><i class="fa-solid fa-pencil"></i></button>
                <button class="save-fmbgc-btn btn btn-sm btn-success hidden" id="save-fmbgc-btn-<?php echo $value["id"]; ?>" data-fmbgc-id="<?php echo $value["id"]; ?>">Save</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script>
  $(document).ready(function() {
    $("#filter-status").on("change", function() {
      $("#filter-form").submit();
    });
    
    $("#add-contri-type").on("click", function() {
      $("#add-contri-type-form").show();
    });
    $("#hide-add-contri-type-form").on("click", function(e) {
      e.preventDefault();
      $("#add-contri-type-form").hide();
    });

    $(".edit-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $("#contri-for-" + $id).addClass("hidden");
      $("#fmb-type-" + $id).addClass("hidden");
      $("#status-" + $id).addClass("hidden");
      $("#edit-contri-for-" + $id).removeClass("hidden");
      $("#edit-fmb-type-" + $id).removeClass("hidden");
      $("#edit-status-" + $id).removeClass("hidden");
      $(this).addClass("hidden");
      $("#save-fmbgc-btn-" + $id).removeClass("hidden");
    });

    $(".save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $id = $(this).data("fmbgc-id");
      $contriFor = $("#edit-contri-for-" + $id).val();
      $fmbType = $("#edit-fmb-type-" + $id).val();
      $status = $("#edit-status-" + $id).val();

      $.ajax({
        url: "<?php echo base_url("admin/updatefmbgc"); ?>",
        type: "POST",
        data: {
          "id": $id,
          "name": $contriFor,
          "fmb_type": $fmbType,
          "status": $status,
        },
        success: function(res) {
          $res = JSON.parse(res);
          if ($res.success) {
            alert("General Contribution Updated Successfully!");
            location.reload();
          }
        }
      })
    });
  });
</script>