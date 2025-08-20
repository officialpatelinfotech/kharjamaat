<style>
  .hidden {
    display: none;
  }

  .menu-date {
    font-size: 12px;
  }
</style>

<div class="container margintopcontainer">
  <h1 class="heading text-center pt-5 mb-4">Faiz ul Mawaid al Burhaniyah</h1>
  <div class="text-left">
    <a href="<?php echo base_url("accounts/home") ?>" class="btn btn-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
  <div class="row">
    <div class="col-12 col-md-12 col-xl-12">
      <div class="dashboard-card rounded">
        <div class="card-header d-flex flex-column justify-content-between align-items-center">
          <p class="mb-0 font-weight-bold text-break">
            Faiz thali barkat for week Feedback
            <?php
            $today = date('Y-m-d');
            echo date('Y-m-d', strtotime('monday this week', strtotime($today))) . " to <lbr>" . date('Y-m-d', strtotime('saturday this week', strtotime($today)));

            if (isset($menu) && !empty($menu)) {
              $valid_feedback_days = array_filter($menu, function ($item) {
                return $item['want_thali'] == 1;
              });
              $filtered = array_filter($menu, function ($item) {
                return $item['status'] == 1;
              });
              if (count($valid_feedback_days) == 0) {
                echo " <span class='text-secondary'> - No sign up found</span>";
              } else if (count($filtered) == 0) {
                echo " <span class='text-danger'> - Feedback not given</span>";
              } else if (count($filtered) < count($valid_feedback_days)) {
                echo " <span class='text-primary'> - Partial feedback given</span>";
              } else {
                echo " <span class='text-success'> - Feedback given</span>";
              }
            }
            ?>
          </p>
          <a href="<?php echo base_url("accounts/ViewMenu"); ?>" target="_blank">View Menu <i class="fa-solid fa-external-link mt-2"></i></a>
        </div>
        <div class="card-body m-0 p-0">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Menu</th>
                <th>Thali Taken?</th>
                <th>Thali Size</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($menu as $key => $item) :
              ?>
                <tr>
                  <td>
                    <span class="menu-items"><?php echo $item['item_names']; ?></span>
                    <br>
                    <p class="menu-date text-primary m-0 mt-2">(<?php echo $item['hijri_date']; ?>)</p>
                  </td>
                  <td>
                    <?php
                    echo $item['want_thali'] == 1 ? 'Yes' : 'No';
                    ?>
                  </td>
                  <td>
                    <?php echo $item['thali_size'] ? $item['thali_size'] : ''; ?>
                  </td>
                  <td>
                    <button class="give-feedback-btn btn btn-<?php echo (int)$item["status"] ? "success" : "primary"; ?> btn-sm <?php echo $item['want_thali'] == 1 ? '' : 'hidden' ?>" data-fwsid="<?php echo $item["fwsid"]; ?>"><?php echo (int)$item["status"] ? "Given" : "Give"; ?></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Feedback Modal -->
    <div class="modal fade" id="feedback-form-container" data-keyboard="false" tabindex="-1" aria-labelledby="feedback-form-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="feedback-form-title">Feedback form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="javascript:void(0)" id="feedback-form" data-form-id="0">
              <label for="delivery-time">Delivery Time:</label>
              <select name="delivery_time" id="delivery-time" class="form-control">
                <option value="" disabled>-----</option>
                <option value="On Time">On Time</option>
                <option value="Too Early">Too Early</option>
                <option value="Too Late">Too Late</option>
              </select>
              <br>
              <label for="thali-quality">Quality:</label>
              <select name="quality" id="thali-quality" class="form-control">
                <option value="" disabled>-----</option>
                <option value="Excellent">Excellent</option>
                <option value="Very Good">Very Good</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
              </select>
              <br>
              <label for="thali-freshness">Freshness:</label>
              <select name="freshness" id="thali-freshness" class="form-control">
                <option value="" disabled>-----</option>
                <option value="Fresh">Fresh</option>
                <option value="Acceptable">Acceptable</option>
                <option value="Slightly Stale">Slightly Stale</option>
                <option value="Not Fresh">Not Fresh</option>
              </select>
              <br>
              <label for="thali-quantity">Quantity:</label>
              <select name="quantity" id="thali-quantity" class="form-control">
                <option value="" disabled>-----</option>
                <option value="Much More Than Needed">Much More Than Needed</option>
                <option value="Slightly More Than Needed">Slightly More Than Needed</option>
                <option value="Just Right">Just Right</option>
                <option value="Slightly Less Than Needed">Slightly Less Than Needed</option>
                <option value="Too Little">Too Little</option>
              </select>
              <br>
              <label for="feedback-remark">Feedback Remark:</label>
              <input type="text" class="form-control" name="feedback_remark" id="feedback-remark" placeholder="Enter your feedback">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" type="button" id="submit-feedback" class="btn btn-success">Submit Feedback</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="response-container" data-keyboard="false" tabindex="-1" aria-labelledby="response-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="response-title">Response</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="feedback-update-response"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('select[name^="want-thali"]').change(function() {
      var selectedValue = $(this).val();
      var thaliSizeSelect = $(this).closest('tr').find('.thali-size-select');

      if (selectedValue == '1') {
        thaliSizeSelect.show();
      } else {
        thaliSizeSelect.hide();
        thaliSizeSelect.val('');
      }
    });

    $('select[name^="want-thali"]').each(function() {
      $(this).trigger('change');
    });

    $(".give-feedback-btn").on("click", function(e) {
      e.preventDefault();
      $fwsid = $(this).data("fwsid");
      $.ajax({
        url: "<?php echo base_url("accounts/getFMBFeedbackData") ?>",
        type: "POST",
        data: {
          fwsid: $fwsid,
        },
        success: function(res) {
          $feedback_data = JSON.parse(res)[0];
          $("#feedback-form").data("form-id", $fwsid);
          $('#feedback-form-container').modal('show');
          $("#delivery-time").val($feedback_data["delivery_time"]);
          $("#thali-quality").val($feedback_data["quality"]);
          $("#thali-freshness").val($feedback_data["freshness"]);
          $("#thali-quantity").val($feedback_data["quantity"]);
          $("#feedback-remark").val($feedback_data["feedback_remark"]);
        }
      });
    });

    $("#submit-feedback").on("click", function() {
      $feedbackId = $("#feedback-form").data("form-id");
      $deliveryTime = $("#delivery-time").val();
      $thaliQuality = $("#thali-quality").val();
      $thaliFreshness = $("#thali-freshness").val();
      $thaliQuantity = $("#thali-quantity").val();
      $feedbackRemark = $("#feedback-remark").val();

      $.ajax({
        url: '<?php echo base_url("accounts/UpdateFMBFeedback"); ?>',
        method: "POST",
        data: {
          "feedback_id": $feedbackId,
          "delivery_time": $deliveryTime,
          "quality": $thaliQuality,
          "freshness": $thaliFreshness,
          "quantity": $thaliQuantity,
          "feedback_remark": $feedbackRemark,
        },
        success: function(res) {
          if (res) {
            $("#feedback-update-response").html(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Feedback Saved Successfully!</strong>
            </div>`);
            $('#response-container').modal('show');
            $('#feedback-form-container').modal('hide');
            setTimeout(() => {
              location.reload();
            }, 1000);
          }
        },
        error: function(xhr, status, error) {
          if (xhr) {
            $("#feedback-update-response").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Some problem occured while saving feedback. Please try again.</strong>
            </div>`);
            $('#response-container').modal('show');
          }
        }
      });
    });
  });
</script>