<style>
  .hidden {
    display: none;
  }

  .add-sabeel-grade {
    background-color: #f1f1f1;
  }
</style>
<div class="container margintopcontainer">
  <h2 class="heading text-center pt-5 mb-4">Sabeel Grade</h2>
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("admin/sabeeltakhmeendashboard"); ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-12 col-md-6">
      <form method="POST" action="<?php echo base_url("admin/filtersabeelgrade"); ?>" class="d-flex m-0">
        <select name="sabeel_year" id="sabeel-year" class="form-control" required>
          <option value="">Select Year</option>
          <option value="1446-09|1447-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1446-09|1447-09" ? "selected" : "") : ""; ?>>1446-09 / 1447-09</option>
          <option value="1447-09|1448-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1447-09|1448-09" ? "selected" : "") : ""; ?>>1447-09 / 1448-09</option>
          <option value="1448-09|1449-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1448-09|1449-09" ? "selected" : "") : ""; ?>>1448-09 / 1449-09</option>
          <option value="1449-09|1450-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1449-09|1450-09" ? "selected" : "") : ""; ?>>1449-09 / 1450-09</option>
          <option value="1450-09|1451-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1450-09|1451-09" ? "selected" : "") : ""; ?>>1450-09 / 1451-09</option>
          <option value="1451-09|1452-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1451-09|1452-09" ? "selected" : "") : ""; ?>>1451-09 / 1452-09</option>
          <option value="1452-09|1453-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1452-09|1453-09" ? "selected" : "") : ""; ?>>1452-09 / 1453-09</option>
          <option value="1453-09|1454-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1453-09|1454-09" ? "selected" : "") : ""; ?>>1453-09 / 1454-09</option>
          <option value="1454-09|1455-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1454-09|1455-09" ? "selected" : "") : ""; ?>>1454-09 / 1455-09</option>
          <option value="1455-09|1456-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1455-09|1456-09" ? "selected" : "") : ""; ?>>1455-09 / 1456-09</option>
          <option value="1456-09|1457-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1456-09|1457-09" ? "selected" : "") : ""; ?>>1456-09 / 1457-09</option>
          <option value="1457-09|1458-09" <?php echo isset($sabeel_year) ? ($sabeel_year == "1457-09|1458-09" ? "selected" : "") : ""; ?>>1457-09 / 1458-09</option>
        </select>
        <a href="<?php echo base_url("admin/sabeelgrade"); ?>" class="btn btn-secondary ml-2">Clear</a>
        <button type="submit" class="btn btn-primary ml-2">Submit</button>
      </form>
    </div>
  </div>
  <hr>
  <div class="row col-12">
    <div class="col-12 col-md-6">
      <div class="p-2 border rounded text-right">
        <h4 class="text-center mt-3">Establishment Sabeel Grade</h4>
        <button id="add-e-sabeel-grade-btn" class="add-sabeel-grade-btn btn btn-sm btn-primary" data-index="0">Add Grade</button>
        <div id="add-e-sabeel-grade" class="add-sabeel-grade hidden border mt-3 rounded">
          <form method="post" action="<?php echo base_url('admin/addsabeelgrade/1') ?>" id="add-e-sabeel-form" class="add-sabeel-grade-form">
            <div class="row modal-body">
              <div class="col-12 col-md-12">
                <select name="e_sabeel_year" id="e-sabeel-year" class="sabeel-year form-control" required>
                  <option value="">Select Year</option>
                  <option value="1446-09|1447-09">1446-09 / 1447-09</option>
                  <option value="1447-09|1448-09">1447-09 / 1448-09</option>
                  <option value="1448-09|1449-09">1448-09 / 1449-09</option>
                  <option value="1449-09|1450-09">1449-09 / 1450-09</option>
                  <option value="1450-09|1451-09">1450-09 / 1451-09</option>
                  <option value="1451-09|1452-09">1451-09 / 1452-09</option>
                  <option value="1452-09|1453-09">1452-09 / 1453-09</option>
                  <option value="1453-09|1454-09">1453-09 / 1454-09</option>
                  <option value="1454-09|1455-09">1454-09 / 1455-09</option>
                  <option value="1455-09|1456-09">1455-09 / 1456-09</option>
                  <option value="1456-09|1457-09">1456-09 / 1457-09</option>
                  <option value="1457-09|1458-09">1457-09 / 1458-09</option>
                </select>
              </div>
              <div id="add-e-sabeel-grade-table" class="row col-12 col-md-12 mt-3">
                <div class="col-12 col-md-6">
                  <select name="e_sabeel_grade" id="e-sabeel-grade" class="sabeel-grade form-control" required></select>
                </div>
                <div class="col-12 col-md-6">
                  <input type="number" name="e_sabeel_amount" class="sabeel-amount form-control" placeholder="Enter amount" min="1" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="hide-add-container btn btn-secondary" data-index="0">Cancel</button>
              <button type="submit" class="submit-sabeel-grade-btn btn btn-primary" data-index="0">Submit</button>
            </div>
          </form>
        </div>
        <table class="table table-hover table-striped mt-3">
          <thead>
            <tr>
              <th>Grade</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($sabeel_grades)): ?>
              <?php foreach ($sabeel_grades as $key => $value): ?>
                <?php if ($value["type"] == "Establishment"): ?>
                  <tr>
                    <td><?php echo $value["grade"]; ?></td>
                    <td>
                      <span id="amount-<?php echo $value["id"]; ?>"><?php echo $value["amount"]; ?></span>
                      <input type="number" id="edit-amount-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo $value["amount"]; ?>" min="1">
                    </td>
                    <td>
                      <button id="edit-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" class="edit-btn btn btn-sm btn-secondary">Edit</button>
                      <button id="save-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" class="save-btn btn btn-sm btn-success hidden">Save</button>
                      <?php if (!empty($value["in_use"])): ?>
                        <button class="btn btn-sm btn-danger" title="This item can't be deleted as it is assign to a member." disabled>Delete</button>
                      <?php else: ?>
                        <a href="<?php echo base_url("admin/deletesabeelgrade/" . $value["id"]); ?>"
                          class="delete-sabeel-grade btn btn-sm btn-danger">Delete</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3">No records found!</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="p-2 border rounded text-right">
        <h4 class="text-center mt-3">Residential Sabeel Grade</h4>
        <button id="add-r-sabeel-grade-btn" class="add-sabeel-grade-btn btn btn-sm btn-primary" data-index="1">Add Grade</button>
        <div id="add-r-sabeel-grade" class="add-sabeel-grade hidden border mt-3 rounded">
          <form method="post" action="<?php echo base_url('admin/addsabeelgrade/2') ?>" id="add-r-sabeel-form" class="add-sabeel-grade-form">
            <div class="row modal-body">
              <div class="col-12 col-md-12">
                <select name="r_sabeel_year" id="r-sabeel-year" class="sabeel-year form-control" required>
                  <option value="">Select Year</option>
                  <option value="1446-09|1447-09">1446-09 / 1447-09</option>
                  <option value="1447-09|1448-09">1447-09 / 1448-09</option>
                  <option value="1448-09|1449-09">1448-09 / 1449-09</option>
                  <option value="1449-09|1450-09">1449-09 / 1450-09</option>
                  <option value="1450-09|1451-09">1450-09 / 1451-09</option>
                  <option value="1451-09|1452-09">1451-09 / 1452-09</option>
                  <option value="1452-09|1453-09">1452-09 / 1453-09</option>
                  <option value="1453-09|1454-09">1453-09 / 1454-09</option>
                  <option value="1454-09|1455-09">1454-09 / 1455-09</option>
                  <option value="1455-09|1456-09">1455-09 / 1456-09</option>
                  <option value="1456-09|1457-09">1456-09 / 1457-09</option>
                  <option value="1457-09|1458-09">1457-09 / 1458-09</option>
                </select>
              </div>
              <div id="add-e-sabeel-grade-table" class="row col-12 col-md-12 mt-3">
                <div class="col-12 col-md-6">
                  <select name="r_sabeel_grade" id="r-sabeel-grade" class="sabeel-grade form-control" required></select>
                </div>
                <div class="col-12 col-md-6">
                  <input type="number" name="r_sabeel_amount" class="sabeel-amount form-control" placeholder="Enter amount" min="1" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="hide-add-container btn btn-secondary" data-index="1">Cancel</button>
              <button type="submit" class="submit-sabeel-grade-btn btn btn-primary" data-index="1">Submit</button>
            </div>
          </form>
        </div>
        <table class="table table-hover table-striped mt-3">
          <thead>
            <tr>
              <th>Grade</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($sabeel_grades)): ?>
              <?php foreach ($sabeel_grades as $key => $value): ?>
                <?php if ($value["type"] == "Residential"): ?>
                  <tr>
                    <td><?php echo $value["grade"]; ?></td>
                    <td>
                      <span id="amount-<?php echo $value["id"]; ?>"><?php echo $value["amount"]; ?></span>
                      <input type="number" id="edit-amount-<?php echo $value["id"]; ?>" class="form-control hidden" value="<?php echo $value["amount"]; ?>" min="1">
                    </td>
                    <td>
                      <button id="edit-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" class="edit-btn btn btn-sm btn-secondary">Edit</button>
                      <button id="save-btn-<?php echo $value["id"]; ?>" data-sabeel-grade-id="<?php echo $value["id"]; ?>" data-sabeel-type="<?php echo $value["type"]; ?>" class="save-btn btn btn-sm btn-success hidden">Save</button>
                      <?php if (!empty($value["in_use"])): ?>
                        <button class="btn btn-sm btn-danger" title="This item can't be deleted as it is assign to a member." disabled>Delete</button>
                      <?php else: ?>
                        <a href="<?php echo base_url("admin/deletesabeelgrade/" . $value["id"]); ?>"
                          class="delete-sabeel-grade btn btn-sm btn-danger">Delete</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3">No records found!</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  $(".add-sabeel-grade-btn").on("click", function(e) {
    $grades = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
    $gradeOptions = "";
    $grades.forEach(grade => {
      $gradeOptions += `<option value="${grade}">${grade}</option>`;
    });
    $index = $(this).data("index");
    $(".sabeel-grade").eq($index).html($gradeOptions);
    $(".add-sabeel-grade").eq($index).removeClass("hidden");
  });

  $(".hide-add-container").on("click", function(e) {
    $index = $(this).data("index");
    $(".add-sabeel-grade").eq($index).addClass("hidden");
  });

  $(".edit-btn").on("click", function(e) {
    $(this).addClass("hidden");
    $id = $(this).data("sabeel-grade-id");
    $("#amount-" + $id).addClass("hidden");
    $("#edit-amount-" + $id).removeClass("hidden");
    $("#save-btn-" + $id).removeClass("hidden");
  });

  $(".save-btn").on("click", function(e) {
    $(this).addClass("hidden");
    $id = $(this).data("sabeel-grade-id");
    $type = $(this).data("sabeel-type");
    $("#amount-" + $id).removeClass("hidden");
    $editAmount = $("#edit-amount-" + $id);
    $editAmount.addClass("hidden");
    $editAmountValue = $editAmount.val();
    $("#edit-btn-" + $id).removeClass("hidden");

    $.ajax({
      url: "<?php echo base_url("admin/updatesabeelgrade"); ?>",
      type: "POST",
      data: {
        "id": $id,
        "type": $type,
        "amount": $editAmountValue
      },
      success: function(res) {
        $res = JSON.parse(res);
        if ($res.success) {
          alert("Sabeel Grade Updated Successfully!");
          location.reload();
        }
      }
    })
  });

  $(".delete-sabeel-grade").on("click", function(e) {
    $result = confirm("Are you sure you want to delete this sabeel grade?");
    if (!$result) {
      e.preventDefault();
    }
  });

  $(".submit-sabeel-grade-btn").on("click", function(event) {
    event.preventDefault();
    $index = $(this).data("index");
    $sabeelYear = $(".sabeel-year").eq($index).val();
    $sabeelGrade = $(".sabeel-grade").eq($index).val();
    $sabeelAmount = $(".sabeel-amount").eq($index).val();

    if ($sabeelYear == "" || $sabeelGrade == "" || $sabeelAmount == "") {
      alert("Fields can't be empty");
      return;
    }

    $.ajax({
      url: "<?php echo base_url("admin/validatesabeelgrade"); ?>",
      type: "POST",
      data: {
        "type": $index + 1,
        "year": $sabeelYear,
        "grade": $sabeelGrade,
      },
      success: function(res) {
        if (res) {
          $res = JSON.parse(res);
          if ($res.success) {
            alert("Sabeel grade already exists!");
            return;
          }
        } else {
          $(".add-sabeel-grade-form").eq($index).off("submit").submit();
        }
      }
    })
  });
</script>