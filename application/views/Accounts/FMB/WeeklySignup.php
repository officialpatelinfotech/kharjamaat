<style>
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
            Faiz thali barkat for week
            <?php
            $today = date('Y-m-d');
            echo date('Y-m-d', strtotime('monday next week', strtotime($today))) . " to <lbr>" . date('Y-m-d', strtotime('saturday next week', strtotime($today)));

            $dayNumber = date('N');

            if (isset($signup_data) && !empty($signup_data)) {
              $filtered = array_filter($signup_data, function ($item) {
                return $item['want_thali'] == '1';
              });
              if ($dayNumber > 5) {
                echo " <span class='text-secondary'> - Sign-up closed for next week!</span>";
              } else {
                if (count($filtered) == 0) {
                  echo " <span class='text-danger'> - You have not signed up for this week</span>";
                } else if (count($filtered) < 6) {
                  echo " <span class='text-primary'> - You have partially signed up</span>";
                } else {
                  echo " <span class='text-success'> - You have signed up</span>";
                }
              }
            } else {
              echo " <span class='text-primary'> - Sign-up Now</span>";
            }
            ?>
          </p>
          <a href="<?php echo base_url("accounts/ViewMenu"); ?>" target="_blank">View Menu <i class="fa-solid fa-external-link mt-2"></i></a>
        </div>
        <div class="card-body m-0 p-0">
          <form method="post" action="<?php echo base_url("accounts/SaveFMBSignUp") ?>">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Menu</th>
                  <th>Want Thali?</th>
                  <th>Thali Size</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($menu as $key => $item) : ?>
                  <tr>
                    <td>
                      <span class="menu-items"><?php echo implode(", ", $item['items']); ?></span>
                      <br>
                      <p class="menu-date text-primary m-0 mt-2">(<?php echo $item['hijri_date']; ?>)</p>
                      <input type="hidden" name="date[]" value="<?php echo $item['date']; ?>">
                    </td>
                    <td>
                      <select name="want-thali[]" id="want-thali-<?php echo $item['id']; ?>" class="form-control">
                        <option value="0" <?php echo isset($signup_data[$key]['want_thali']) && ($signup_data[$key]['want_thali'] == 0) ? 'selected' : ''; ?>>No</option>
                        <option value="1" <?php echo isset($signup_data[$key]['want_thali']) && ($signup_data[$key]['want_thali'] == 1) ? 'selected' : ''; ?>>Yes</option>
                      </select>
                    </td>
                    <td>
                      <select name="thali_size[]" id="thali-size-<?php echo $item['id']; ?>" class="form-control thali-size-select">
                        <option value="">------</option>
                        <option value="Big" <?php echo isset($signup_data[$key]['thali_size']) && ($signup_data[$key]['thali_size'] == "Big") ? 'selected' : ''; ?>>Big</option>
                        <option value="Medium" <?php echo isset($signup_data[$key]['thali_size']) && ($signup_data[$key]['thali_size'] == "Medium") ? 'selected' : ''; ?>>Medium</option>
                        <option value="China" <?php echo isset($signup_data[$key]['thali_size']) && ($signup_data[$key]['thali_size'] == "China") ? 'selected' : ''; ?>>China</option>
                      </select>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="text-center">
              <button type="submit" id="submit-btn" class="btn btn-success">Save Sign Up</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    const today = new Date();

    const dayOfWeekNumber = today.getDay();

    if (dayOfWeekNumber > 5 || dayOfWeekNumber == 0) {
      $("#submit-btn").prop("disabled", true);
    } else {
      $("#submit-btn").prop("disabled", false);
    }



    $('select').each(function() {
      if (dayOfWeekNumber > 5 || dayOfWeekNumber == 0) {
        $(this).prop('disabled', true);
      } else {
        $(this).prop('disabled', false);
      }
    })

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

    $("form").submit(function(event) {
      let allValid = true;

      $('select[name^="want-thali"]').each(function() {
        if ($(this).val() == '1') {
          let thaliSizeSelect = $(this).closest('tr').find('.thali-size-select');
          if (thaliSizeSelect.val() === '') {
            allValid = false;
            alert("Please select a thali size for all 'Yes' thali selections.");
            return false;
          }
        }
      });

      if (!allValid) {
        event.preventDefault(); // Prevent form submission
      }
    });
  });
</script>