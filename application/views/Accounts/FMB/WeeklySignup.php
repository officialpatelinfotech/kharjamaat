<style>
  .menu-date {
    font-size: 12px;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="text-left pt-5">
    <a href="<?php echo base_url("accounts/home") ?>" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <h4 class="heading text-center mb-4">FMB Thaali Signup</h4>
  <div class="row">
    <div class="col-12 col-md-12 col-xl-12">
      <div class="dashboard-card rounded">
        <div class="card-header d-flex flex-wrap align-items-start justify-content-between">
          <?php
            $total_days = (isset($signup_days) && is_array($signup_days)) ? count($signup_days) : 0;
            $signed_count = 0;
            if (isset($signup_data) && is_array($signup_data)) {
              $signed_count = count(array_filter($signup_data, function ($it) { return isset($it['want_thali']) && (string)$it['want_thali'] === '1'; }));
            }
          ?>
          <?php
            // Derive today's status (not monthly aggregate)
            $todayGreg = date('Y-m-d');
            $nowTime = date('H:i:s');
            $cutoff = '18:00:00';
            $todayMenuExists = false;
            $todaySigned = false;
            $todayRowIsPastCutoff = ($nowTime >= $cutoff);
            // Map signup_data by date for quick lookup
            $signupByDate = [];
            if (isset($signup_data) && is_array($signup_data)) {
              foreach ($signup_data as $sd) {
                if (isset($sd['signup_date'])) {
                  $signupByDate[$sd['signup_date']] = $sd;
                }
              }
            }
            if (isset($menu) && is_array($menu)) {
              foreach ($menu as $mrow) {
                if (isset($mrow['date']) && $mrow['date'] === $todayGreg) {
                  $todayMenuExists = true;
                  if (isset($signupByDate[$todayGreg]) && (string)$signupByDate[$todayGreg]['want_thali'] === '1') {
                    $todaySigned = true;
                  }
                  break;
                }
              }
            }
            $statusHtml = ''; 
          ?>
          <div class="header-left d-flex flex-column flex-grow-1">
            <div class="month-switcher-wrapper w-100">
              <div class="month-switcher d-flex align-items-center mb-2 mb-sm-1">
                <a class="nav-arrow prev btn btn-sm btn-outline-secondary mr-2" href="<?php echo base_url('accounts/fmbweeklysignup') . '?hijri=' . htmlspecialchars($prev_hijri ?? '', ENT_QUOTES); ?>" title="Previous month" aria-label="Previous month"><i class="fa-solid fa-chevron-left"></i></a>
                <span class="month-label flex-grow-1 text-center px-2"><?php echo htmlspecialchars(($hijri_month_name ?? '') . ' ' . ($hijri_year ?? ''), ENT_QUOTES); ?></span>
                <a class="nav-arrow next btn btn-sm btn-outline-secondary ml-2" href="<?php echo base_url('accounts/fmbweeklysignup') . '?hijri=' . htmlspecialchars($next_hijri ?? '', ENT_QUOTES); ?>" title="Next month" aria-label="Next month"><i class="fa-solid fa-chevron-right"></i></a>
              </div>
              <div class="month-status text-center mb-2">
                <?php echo $statusHtml; ?>
              </div>
            </div>
            <div class="mt-1">
              <a href="<?php echo base_url("accounts/viewmenu") . '?hijri=' . htmlspecialchars($selected_hijri ?? ($hijri_year.'-'.$hijri_month), ENT_QUOTES); ?>" target="_blank">View Menu <i class="fa-solid fa-external-link"></i></a>
            </div>
          </div>
          <div class="header-actions ml-auto">
            <!-- <button type="button" id="save-top-btn" class="btn btn-success btn-sm ml-2" aria-label="Save Sign Up">Save Sign Up</button> -->
          </div>
        </div>
        <div class="card-body m-0 p-0">
            <form method="post" action="<?php echo base_url("accounts/savefmbsignup") ?>" id="signup-form">
            <div class="signup-cards p-3">
              <div class="row">
                <?php foreach ($all_days as $key => $day) : ?>
                  <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <div class="day-card card h-100">
                      <div class="card-body d-flex flex-column p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <div>
                            <div class="font-weight-bold day-greg"><?php echo date("d-m-Y", strtotime(htmlspecialchars($day['greg_date'], ENT_QUOTES))); ?></div>
                            <small class="text-primary day-hijri"><?php echo htmlspecialchars($day['hijri_date'], ENT_QUOTES); ?></small>
                          </div>
                          <div class="day-week text-right font-weight-bold text-secondary" style="min-width:70px;">
                            <?php echo htmlspecialchars($day['weekday'], ENT_QUOTES); ?>
                          </div>
                          <input type="hidden" name="date[]" value="<?php echo $day['greg_date']; ?>">
                        </div>
                        <div class="menu-list mb-2 flex-grow-1 small">
                          <?php if (!empty($day['menu_items'])): ?>
                            <div class="text-muted small mb-1">Menu</div>
                            <div class="menu-items text-truncate" title="<?php echo htmlspecialchars(implode(', ', $day['menu_items']), ENT_QUOTES); ?>"><?php echo htmlspecialchars(implode(', ', $day['menu_items']), ENT_QUOTES); ?></div>
                          <?php else: ?>
                            <div class="text-danger font-weight-bold">No Thaali Day</div>
                          <?php endif; ?>

                          <?php if (!empty($day['menu_id'])): ?>
                            <div class="text-muted small mt-2">Assigned To</div>
                            <div class="assigned-to">
                              <?php echo htmlspecialchars(!empty($day['assigned_to']) ? $day['assigned_to'] : '-', ENT_QUOTES); ?>
                            </div>
                          <?php endif; ?>
                        </div>
                        <div class="form-row mt-1">
                          <div class="col-6">
                            <label class="small text-muted mb-1">Want Thali?</label>
                            <select name="want-thali[]" class="form-control form-control-sm">
                              <option value="0" <?php echo ($day['want_thali'] !== null && (string)$day['want_thali'] === '0') ? 'selected' : ''; ?>>No</option>
                              <option value="1" <?php echo ($day['want_thali'] !== null && (string)$day['want_thali'] === '1') ? 'selected' : ''; ?>>Yes</option>
                            </select>
                          </div>
                          <div class="col-6">
                            <label class="small text-muted mb-1">Thali Size</label>
                            <select name="thali_size[]" class="form-control form-control-sm thali-size-select">
                              <option value="">--</option>
                              <option value="Big" <?php echo ($day['thali_size'] === 'Big') ? 'selected' : ''; ?>>Big</option>
                              <option value="Double Big" <?php echo ($day['thali_size'] === 'Double Big') ? 'selected' : ''; ?>>Double Big</option>
                              <option value="Triple Big" <?php echo ($day['thali_size'] === 'Triple Big') ? 'selected' : ''; ?>>Triple Big</option>
                              <option value="Medium" <?php echo ($day['thali_size'] === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                              <option value="Double Medium" <?php echo ($day['thali_size'] === 'Double Medium') ? 'selected' : ''; ?>>Double Medium</option>
                              <option value="China" <?php echo ($day['thali_size'] === 'China') ? 'selected' : ''; ?>>China</option>
                              <option value="Double China" <?php echo ($day['thali_size'] === 'Double China') ? 'selected' : ''; ?>>Double China</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="text-center">
                  <button type="submit" id="submit-btn" class="btn btn-success">Save Sign Up</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Header layout adjustments */
  .card .card-header { position: relative; }
  .card .card-header .header-left { min-width: 250px; }
  #save-top-btn { margin-top: 4px; }
  /* Hide bottom submit - we moved the action to top button */
  #submit-btn { display: none !important; }
  /* On small screens stack the button nicely under header content */
  @media (max-width: 576px) {
    .card .card-header { flex-direction: column; align-items: stretch; }
    .header-actions { width: 100%; text-align: right; }
    #save-top-btn { width: auto; }
  }
  /* Floating save button */
  .floating-save {
    position: fixed;
    right: 12px;
    bottom: 14px;
    z-index: 1200;
    box-shadow: 0 6px 10px rgba(0,0,0,0.12);
    padding: 8px 10px;
    border-radius: 6px;
    font-size: 0.95rem;
  }
  @media (max-width: 576px) {
    .floating-save { left: 12px; right: 12px; bottom: 12px; width: auto; display: block; padding: 9px 12px; font-size: 1rem; }
  }

  /* Give the page extra bottom padding so floating button doesn't overlap content */
  .margintopcontainer { padding-bottom: 100px; }
  /* Card-based layout styles */
  .signup-cards .day-card { border: 1px solid #e9ecef; box-shadow: none; }
  /* Toned-down card scale and adjusted label sizes */
  .signup-cards .day-card { border: 1px solid #e9ecef; box-shadow: none; }
  .signup-cards .day-card .card-body { padding: 0.55rem; }
  .signup-cards .day-card .day-greg { font-size: 1.15rem; line-height:1; }
  .signup-cards .day-card .day-hijri { font-size: 0.95rem; display:block; margin-top:3px; }
  .signup-cards .menu-list { min-height: 26px; }
  /* Menu items size increased slightly but smaller than before */
  .signup-cards .menu-items { font-size: 1.15rem; line-height: 1.25; }
  .signup-cards .assigned-to { font-size: 1.1rem; font-weight: 400; line-height: 1.25; }
  .signup-cards .day-week { font-size: 1.05rem; }
  /* Label sizing for dropdown captions */
  .signup-cards label.small { font-size: 0.88rem; }
  @media (max-width: 576px) {
    .signup-cards .day-card { margin-bottom: 6px; }
    .signup-cards .day-card .day-greg { font-size: 16px; }
    .signup-cards .day-card .day-hijri { font-size: 13px; }
    .signup-cards .day-card .menu-list { min-height: 24px; }
    .signup-cards .menu-items { font-size: 1.05rem; }
    .form-control-sm { font-size: 14px; padding: .25rem .4rem; }
    .signup-cards label.small { font-size: 0.82rem; }
    #save-top-btn { width: 100%; }
  }
</style>

<script>
  (function(){
    var topBtn = document.getElementById('save-top-btn');
    var form = document.getElementById('signup-form');
    if (topBtn && form) {
      topBtn.addEventListener('click', function(e){
        e.preventDefault();
        form.submit();
      });
    }
  })();
</script>
<script>
  $(document).ready(function() {
    // Local date (YYYY-MM-DD) without UTC offset issues
    const now = new Date();
    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, '0');
    const dd = String(now.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    const cutoff = new Date(yyyy, now.getMonth(), now.getDate(), 18, 0, 0); // 6 PM local

    // Sign up open for all days: no disabling of past or today after cutoff.

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
<!-- Floating Save Button: submits the signup form -->
<button type="button" id="floating-save-btn" class="btn btn-success floating-save" aria-label="Save Sign Up">Save Sign Up</button>
<script>
  (function(){
    var floatBtn = document.getElementById('floating-save-btn');
    var form = document.getElementById('signup-form');
    if (floatBtn && form) {
      floatBtn.addEventListener('click', function(e){
        e.preventDefault();
        form.submit();
      });
    }
  })();
</script>
<style>
  /* Month switcher expanded layout */
  .month-switcher-wrapper { width: 100%; }
  .month-switcher { width: 100%; justify-content: space-between; }
  .month-switcher .nav-arrow { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; }
  .month-label { font-weight: 600; font-size: 1.05rem; line-height: 1.2; }
  .month-status .badge { font-size: 0.75rem; }
  @media (min-width: 576px) {
    .month-switcher .nav-arrow { width: 46px; height: 46px; }
    .month-label { font-size: 1.15rem; }
    .month-status { text-align: right; }
  }
  @media (max-width: 575.98px) {
    .month-label { white-space: normal; }
  }
</style>