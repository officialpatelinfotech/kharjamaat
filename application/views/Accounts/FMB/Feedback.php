<style>
  .hidden {
    display: none;
  }

  .menu-date {
    font-size: 12px;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="text-left pt-5">
    <a href="<?php echo base_url("accounts/home") ?>" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-4">FMB Thaali Feedback</h4>
  <div class="row">
    <div class="col-12 col-md-12 col-xl-12">
      <div class="dashboard-card rounded">
        <div class="card-header d-flex flex-column justify-content-between align-items-center">
          <p class="mb-0 font-weight-bold text-break">
          </p>
          <?php
          // Hijri month nav (prev / current name / next) styled like Thaali signup
          $CI = &get_instance();
          if (!isset($CI->HijriCalendar)) {
            $CI->load->model('HijriCalendar');
          }
          $hijri_years = $CI->HijriCalendar->get_distinct_hijri_years();
          // Default to current Hijri month/year when not provided via GET
          $today_hijri = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
          $default_year = isset($today_hijri['hijri_year']) ? (int)$today_hijri['hijri_year'] : (!empty($hijri_years) ? (int)$hijri_years[0] : null);
          $sel_year = isset($_GET['hijri_year']) ? (int)$_GET['hijri_year'] : $default_year;
          $months_this_year = $sel_year ? $CI->HijriCalendar->get_hijri_months_for_year($sel_year) : [];
          $default_month = isset($today_hijri['hijri_month']) ? (int)$today_hijri['hijri_month'] : (!empty($months_this_year) ? (int)$months_this_year[0]['id'] : null);
          $sel_month = isset($_GET['hijri_month']) ? (int)$_GET['hijri_month'] : $default_month;

          // Find current month name
          $current_month_name = '';
          foreach ($months_this_year as $m) {
            if ((int)$m['id'] === (int)$sel_month) {
              $current_month_name = $m['name'];
              break;
            }
          }

          // Compute prev month/year
          $prev_month = null;
          $prev_year = $sel_year;
          $next_month = null;
          $next_year = $sel_year;
          // locate index
          $index = -1;
          foreach ($months_this_year as $i => $m) {
            if ((int)$m['id'] === (int)$sel_month) {
              $index = $i;
              break;
            }
          }
          if ($index > 0) {
            $prev_month = $months_this_year[$index - 1]['id'];
            $prev_year = $sel_year;
          } elseif ($index === 0) {
            // previous year's last month
            $prev_year = $sel_year - 1;
            $pm = $CI->HijriCalendar->get_hijri_months_for_year($prev_year);
            if (!empty($pm)) {
              $prev_month = end($pm)['id'];
            }
          }
          if ($index >= 0 && $index < count($months_this_year) - 1) {
            $next_month = $months_this_year[$index + 1]['id'];
            $next_year = $sel_year;
          } elseif ($index === count($months_this_year) - 1) {
            // next year's first month
            $next_year = $sel_year + 1;
            $nm = $CI->HijriCalendar->get_hijri_months_for_year($next_year);
            if (!empty($nm)) {
              $next_month = $nm[0]['id'];
            }
          }
          ?>
          <div class="d-flex w-100 align-items-center justify-content-between" style="gap:10px;">
            <div class="d-flex align-items-center">
              <?php if (!empty($prev_month)): ?>
                <a href="?hijri_year=<?php echo urlencode($prev_year); ?>&hijri_month=<?php echo urlencode($prev_month); ?>" class="btn btn-outline-secondary" aria-label="Previous month"><i class="fa fa-chevron-left"></i></a>
              <?php else: ?>
                <button class="btn btn-outline-secondary" disabled><i class="fa fa-chevron-left"></i></button>
              <?php endif; ?>
            </div>
            <div class="text-center flex-grow-1">
              <strong><?php echo htmlspecialchars($current_month_name ? $current_month_name : ''); ?> <?php echo htmlspecialchars($sel_year ? $sel_year : ''); ?></strong>
            </div>
            <div class="d-flex align-items-center">
              <?php if (!empty($next_month)): ?>
                <a href="?hijri_year=<?php echo urlencode($next_year); ?>&hijri_month=<?php echo urlencode($next_month); ?>" class="btn btn-outline-secondary" aria-label="Next month"><i class="fa fa-chevron-right"></i></a>
              <?php else: ?>
                <button class="btn btn-outline-secondary" disabled><i class="fa fa-chevron-right"></i></button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="px-3 pb-2">
          <!-- <a href="<?php echo base_url("accounts/ViewMenu"); ?>" target="_blank" class="text-primary"><i class="fa-solid fa-external-link"></i> View Menu</a> -->
        </div>
        <div class="card-body m-0 p-0">
          <?php
          // Helper to normalize hijri date strings into 'D-M-YYYY' (no leading zeros)
          function _normalize_hijri($s)
          {
            if (!$s) return '';
            // Extract numeric parts (day, month, year)
            preg_match_all('/(\d+)/', $s, $matches);
            if (empty($matches[0]) || count($matches[0]) < 3) return trim($s);
            $parts = $matches[0];
            $day = (int)$parts[0];
            $month = (int)$parts[1];
            $year = $parts[2];
            return $day . '-' . $month . '-' . $year;
          }

          // Build a map of menu items keyed by a normalized hijri_date for robust lookup
          // Support multiple menu items per day by storing arrays.
          $menu_map = [];
          if (!empty($menu) && is_array($menu)) {
            foreach ($menu as $m) {
              if (isset($m['hijri_date'])) {
                $key = _normalize_hijri($m['hijri_date']);
                if (!isset($menu_map[$key])) $menu_map[$key] = [];
                $menu_map[$key][] = $m;
                // Also store by original string to maximize matching chances
                $raw = trim($m['hijri_date']);
                if ($raw !== $key) {
                  if (!isset($menu_map[$raw])) $menu_map[$raw] = [];
                  $menu_map[$raw][] = $m;
                }
              }
            }
          }

          // Determine month/year to render days for
          $selected_month = null;
          $selected_year = null;
          // Prefer explicit GET params if provided
          if (isset($_GET['hijri_month']) && isset($_GET['hijri_year'])) {
            $selected_month = $_GET['hijri_month'];
            $selected_year = $_GET['hijri_year'];
          } elseif (!empty($menu_map)) {
            // Derive from first menu item
            $first_date = array_keys($menu_map)[0]; // format d-m-Y
            $parts = explode('-', $first_date);
            if (count($parts) === 3) {
              $selected_month = ltrim($parts[1], '0');
              $selected_year = $parts[2];
            }
          }

          // If still not determined, use today's hijri parts via model
          if (empty($selected_month) || empty($selected_year)) {
            $CI = &get_instance();
            if (!isset($CI->HijriCalendar)) {
              $CI->load->model('HijriCalendar');
            }
            $parts = $CI->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d'));
            if ($parts) {
              $selected_month = $parts['hijri_month'];
              $selected_year = $parts['hijri_year'];
            }
          }

          // Fetch all days for the selected hijri month/year
          $days = [];
          if (!empty($selected_month) && !empty($selected_year)) {
            $CI = &get_instance();
            if (!isset($CI->HijriCalendar)) {
              $CI->load->model('HijriCalendar');
            }
            $days = $CI->HijriCalendar->get_hijri_days_for_month_year($selected_month, $selected_year);
          }

          // If caller did not provide $menu, fetch menus from DB for the Gregorian range covering these days
          $fetched_menu = [];
          if ((empty($menu) || !is_array($menu)) && !empty($days)) {
            $CI = &get_instance();
            if (!isset($CI->AccountM)) {
              $CI->load->model('AccountM');
            }
            $first_greg = $days[0]['greg_date'];
            $last_greg = $days[count($days) - 1]['greg_date'];
            // get_menus_between returns array of ['id','date','items'=>[]]
            $menus_between = $CI->AccountM->get_menus_between($first_greg, $last_greg);
            // Map by greg date for quick lookup
            $greg_menu_map = [];
            foreach ($menus_between as $mm) {
              $d = $mm['date'] ?? null;
              if ($d) {
                $greg_menu_map[$d] = $mm; // mm contains items[]
              }
            }
            // Build fetched_menu entries keyed by hijri_date using the days array
            foreach ($days as $d) {
              $greg = $d['greg_date'];
              $hij = $d['hijri_date'];
              if (isset($greg_menu_map[$greg])) {
                $mrow = $greg_menu_map[$greg];
                $fetched_menu[] = [
                  'fwsid' => $mrow['id'] ?? null,
                  'item_names' => !empty($mrow['items']) ? implode(', ', $mrow['items']) : '',
                  'hijri_date' => $hij,
                  'greg_date' => $greg,
                  'want_thali' => 1, // assume menu available implies thali
                  'thali_size' => '',
                  'status' => 0,
                ];
              }
            }
            // If we found fetched_menu, use it as $menu for downstream logic
            if (!empty($fetched_menu)) {
              $menu = $fetched_menu;
              // Rebuild $menu_map now that we have menu data
              $menu_map = [];
              foreach ($menu as $m) {
                if (isset($m['hijri_date'])) {
                  $key = _normalize_hijri($m['hijri_date']);
                  if (!isset($menu_map[$key])) $menu_map[$key] = [];
                  $menu_map[$key][] = $m;
                  $raw = trim($m['hijri_date']);
                  if ($raw !== $key) {
                    if (!isset($menu_map[$raw])) $menu_map[$raw] = [];
                    $menu_map[$raw][] = $m;
                  }
                }
              }
            }
          }
          ?>

          <div class="card-body m-0 p-3">
            <div class="row g-3">
              <?php
              // If we don't have day list, render cards from provided $menu
              if (empty($days)) {
                if (!empty($menu) && is_array($menu)) {
                  foreach ($menu as $item) :
              ?>
                    <div class="col-12 col-md-6 col-lg-4">
                      <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                          <div class="mb-2">
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($item['item_names'] ?? ''); ?></h5>
                            <p class="menu-date text-primary mb-0">(<?php echo htmlspecialchars($item['hijri_date'] ?? ''); ?>)
                              <?php if (!empty($item['greg_date'])): ?><br><small class="text-muted"><?php echo htmlspecialchars($item['greg_date']); ?></small><?php endif; ?>
                            </p>
                          </div>
                          <div class="mt-auto text-right">
                            <?php
                            $signed = false;
                            if (!empty($signup_data) && !empty($item['greg_date'])) {
                              foreach ($signup_data as $sd) {
                                if (isset($sd['signup_date']) && $sd['signup_date'] === $item['greg_date'] && (string)$sd['want_thali'] === '1') {
                                  $signed = true;
                                  break;
                                }
                              }
                            }
                            ?>
                            <p class="mb-1"><strong>Thaali Taken:</strong> <?php echo $signed ? 'Yes' : 'No'; ?></p>
                            <?php
                            $size = '';
                            if (!empty($signup_data) && !empty($item['greg_date'])) {
                              foreach ($signup_data as $sd) {
                                if (isset($sd['signup_date']) && $sd['signup_date'] === $item['greg_date']) {
                                  $size = isset($sd['thali_size']) ? $sd['thali_size'] : '';
                                  break;
                                }
                              }
                            }
                            ?>
                            <p class="mb-2"><strong>Size:</strong> <?php echo htmlspecialchars($size); ?></p>
                                <?php
                                  // Determine signup id for this card (prefer direct field supplied by controller)
                                  $signupId = isset($item['signup_id']) ? $item['signup_id'] : null;
                                  if (!$signupId && !empty($signup_data) && !empty($item['greg_date'])) {
                                    foreach ($signup_data as $sd) { if ($sd['signup_date'] === $item['greg_date']) { $signupId = $sd['id']; break; } }
                                  }
                                ?>
                                <?php if ($signed && !empty($signupId)): ?>
                                  <button class="give-feedback-btn btn btn-<?php echo (int)($item["status"] ?? 0) ? "success" : "primary"; ?> btn-sm" data-fwsid="<?php echo htmlspecialchars($signupId); ?>"><?php echo (int)($item["status"] ?? 0) ? "Given" : "Give"; ?></button>
                            <?php else: ?>
                              <span class="badge badge-secondary">Thaali not taken</span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php
                  endforeach;
                }
              } else {
                // Render a card per hijri day
                foreach ($days as $d) :
                  $hijri_date = $d['hijri_date'];
                  $parts_d = explode('-', $hijri_date);
                  if (count($parts_d) === 3) {
                    $keyd = (int)$parts_d[0] . '-' . (int)$parts_d[1] . '-' . $parts_d[2];
                  } else {
                    $keyd = $hijri_date;
                  }
                  $menu_items_for_day = isset($menu_map[$keyd]) ? $menu_map[$keyd] : null;
                  $menu_item = is_array($menu_items_for_day) && count($menu_items_for_day) > 0 ? $menu_items_for_day[0] : null;
                  ?>
                  <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card shadow-sm h-100">
                      <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                          <?php if ($menu_item): ?>
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($menu_item['item_names'] ?? ''); ?></h5>
                          <?php else: ?>
                            <h5 class="card-title mb-1 text-muted">No menu</h5>
                          <?php endif; ?>
                          <p class="menu-date text-primary mb-0">(<?php echo htmlspecialchars($hijri_date); ?>)
                            <?php if (!empty($d['greg_date'])): ?><br><small class="text-muted"><?php echo htmlspecialchars($d['greg_date']); ?></small><?php endif; ?>
                          </p>
                        </div>
                        <div class="mt-auto text-right">
                          <?php
                          $signedDay = false;
                          if (!empty($signup_data) && !empty($d['greg_date'])) {
                            foreach ($signup_data as $sd) {
                              if (isset($sd['signup_date']) && $sd['signup_date'] === $d['greg_date'] && (string)$sd['want_thali'] === '1') {
                                $signedDay = true;
                                break;
                              }
                            }
                          }
                          ?>
                          <p class="mb-1"><strong>Thaali Taken:</strong> <?php echo $signedDay ? 'Yes' : 'No'; ?></p>
                          <?php
                          $sizeDay = '';
                          if (!empty($signup_data) && !empty($d['greg_date'])) {
                            foreach ($signup_data as $sd) {
                              if (isset($sd['signup_date']) && $sd['signup_date'] === $d['greg_date']) {
                                $sizeDay = isset($sd['thali_size']) ? $sd['thali_size'] : '';
                                break;
                              }
                            }
                          }
                          ?>
                          <p class="mb-2"><strong>Size:</strong> <?php echo htmlspecialchars($sizeDay); ?></p>
                              <?php
                                $signupIdDay = ($menu_item && isset($menu_item['signup_id'])) ? $menu_item['signup_id'] : null;
                                if (!$signupIdDay && !empty($signup_data) && !empty($d['greg_date'])) {
                                  foreach ($signup_data as $sd) { if ($sd['signup_date'] === $d['greg_date']) { $signupIdDay = $sd['id']; break; } }
                                }
                              ?>
                              <?php if ($signedDay && $signupIdDay): ?>
                                <button class="give-feedback-btn btn btn-<?php echo (int)($menu_item["status"] ?? 0) ? "success" : "primary"; ?> btn-sm" data-fwsid="<?php echo htmlspecialchars($signupIdDay); ?>"><?php echo (int)($menu_item["status"] ?? 0) ? "Given" : "Give"; ?></button>
                          <?php else: ?>
                            <span class="badge badge-secondary">Thaali not taken</span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php
                endforeach;
              }
              ?>
            </div> <!-- .row g-3 -->
          </div> <!-- .card-body m-0 p-3 -->
        </div> <!-- .card-body m-0 p-0 -->
      </div> <!-- .card-body m-0 p-0 wrapper -->
    </div> <!-- .dashboard-card -->
  </div> <!-- .col -->
</div> <!-- .row -->
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
      <div class="modal-footer d-flex justify-content-between">
        <button type="button" id="submit-feedback" class="btn btn-success">Submit Feedback</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div> <!-- .container -->

<script>
  (function($) {
    $(function() {
      // Feedback modal population and submit handlers
      $(document).on('click', '.give-feedback-btn', function() {
        var $fwsid = $(this).data('fwsid');
        if (!$fwsid) return;
        $.ajax({
          url: '<?php echo base_url("accounts/getFMBFeedbackData"); ?>',
          method: 'POST',
          dataType: 'json',
          data: { fwsid: $fwsid },
          success: function(res){
            if (!res || !res.id) {
              // No existing feedback row: initialize form blank
              $("#feedback-form").data("form-id", $fwsid);
              $('#feedback-form-container').modal('show');
              $("#delivery-time").val('');
              $("#thali-quality").val('');
              $("#thali-freshness").val('');
              $("#thali-quantity").val('');
              $("#feedback-remark").val('');
              return;
            }
            var $feedback_data = res;
            $("#feedback-form").data("form-id", $feedback_data.id);
            $('#feedback-form-container').modal('show');
            $("#delivery-time").val($feedback_data.delivery_time);
            $("#thali-quality").val($feedback_data.quality);
            $("#thali-freshness").val($feedback_data.freshness);
            $("#thali-quantity").val($feedback_data.quantity);
            $("#feedback-remark").val($feedback_data.feedback_remark);
          }
        });
      });
      $("#submit-feedback").on("click", function() {
        var $feedbackId = $("#feedback-form").data("form-id");
        if(!$feedbackId){
          $("#feedback-update-response").html('<div class="alert alert-danger" role="alert"><strong>Missing feedback id.</strong></div>');
          $('#response-container').modal('show');
          return;
        }
        var $deliveryTime = $("#delivery-time").val();
        var $thaliQuality = $("#thali-quality").val();
        var $thaliFreshness = $("#thali-freshness").val();
        var $thaliQuantity = $("#thali-quantity").val();
        var $feedbackRemark = $("#feedback-remark").val();

        $.ajax({
          url: '<?php echo base_url("accounts/UpdateFMBFeedback"); ?>',
          method: "POST",
          dataType: 'json',
          data: {
            feedback_id: $feedbackId,
            delivery_time: $deliveryTime,
            quality: $thaliQuality,
            freshness: $thaliFreshness,
            quantity: $thaliQuantity,
            feedback_remark: $feedbackRemark
          },
          success: function(res) {
            if (res && res.success) {
              $("#feedback-update-response").html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Feedback Saved Successfully!</strong></div>');
              $('#response-container').modal('show');
              $('#feedback-form-container').modal('hide');
              setTimeout(function(){ location.reload(); }, 1000);
            } else {
              $("#feedback-update-response").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>No changes saved.</strong></div>');
              $('#response-container').modal('show');
            }
          },
          error: function(xhr, status, error) {
            $("#feedback-update-response").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Some problem occurred while saving feedback. Please try again.</strong></div>');
            $('#response-container').modal('show');
          }
        });
      });
    });
  })(jQuery);
</script>