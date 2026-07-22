<?php
$is_laagat = isset($module_type) && $module_type === 'laagat';
$back_url = $is_laagat ? site_url('admin/laagat/manage') : site_url('admin/rent/manage');
$page_title_prefix = !empty($form['id']) ? 'Edit' : 'Create';
$page_title = $page_title_prefix . ($is_laagat ? ' Laagat Form' : ' Rent Form');
?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --gold-deep:   #8a6408;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #10b981;
    --green-bg:    #ecfdf5;
    --green-border:#bbf7d0;
    --red:         #ef4444;
    --red-bg:      #fef2f2;
    --red-border:  #fecaca;
    --blue:        #3b82f6;
    --blue-bg:     #eff6ff;
    --blue-border: #bfdbfe;
    --amber:       #f59e0b;
    --amber-bg:    #fffbeb;
    --amber-border:#fde68a;
    --radius-sm:   8px;
    --radius:      12px;
    --radius-lg:   16px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.05);
    --shadow:      0 4px 16px rgba(184,134,11,0.06);
    --shadow-lg:   0 10px 30px rgba(184,134,11,0.12);
  }

  .page-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    color: var(--text-1);
  }

  /* Page Header / Back Button */
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
  }
  .btn-back-elegant {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px !important;
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    box-shadow: var(--shadow-sm) !important;
    transition: all .2s !important;
    text-decoration: none !important;
    padding: 0 !important;
  }
  .btn-back-elegant:hover {
    background: var(--gold-muted) !important;
    border-color: var(--gold) !important;
    color: var(--gold-deep) !important;
    transform: translateX(-3px) !important;
  }
  .page-title {
    font-family: 'Literata', Georgia, serif !important;
    color: var(--gold-deep) !important;
    font-size: 1.8rem !important;
    font-weight: 600 !important;
    margin: 0 !important;
    letter-spacing: -.5px !important;
  }

  /* Form Card Wrapper */
  .form-card {
    background: var(--surface) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow) !important;
    margin-bottom: 30px !important;
  }
  .form-card .card-body {
    padding: 30px !important;
  }

  /* Inputs & Labels */
  .form-label {
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    letter-spacing: .5px !important;
    text-transform: uppercase !important;
    color: var(--text-2) !important;
    margin-bottom: 6px !important;
    display: block !important;
  }
  .form-control, .custom-select {
    height: 44px !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    font-size: 0.92rem !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 600 !important;
    color: var(--text-2) !important;
  }
  .form-control:focus, .custom-select:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184,134,11,0.12) !important;
    outline: none !important;
  }
  select.custom-select {
    padding-right: 32px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    background-position: right 14px center !important;
    background-repeat: no-repeat !important;
    appearance: none !important;
    -webkit-appearance: none !important;
  }

  /* Sub Section Headers */
  .section-subtitle {
    font-family: 'Literata', Georgia, serif !important;
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: var(--gold-deep) !important;
    margin-bottom: 16px !important;
  }

  /* Sabeel / Non-Sabeel Card Box */
  .holder-card {
    background: var(--surface-2) !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    padding: 24px !important;
    height: 100%;
  }

  /* Raza badges */
  .lr-raza-badge {
    background-color: var(--gold-muted) !important;
    color: var(--gold-deep) !important;
    border: 1.5px solid var(--gold) !important;
    border-radius: 8px !important;
    padding: 6px 12px !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    margin-right: 8px !important;
    margin-bottom: 8px !important;
  }
  .lr-raza-remove {
    background: transparent !important;
    border: none !important;
    color: var(--gold-deep) !important;
    font-size: 1.1rem !important;
    opacity: 1 !important;
    line-height: 1 !important;
    cursor: pointer !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  .lr-raza-remove:hover {
    color: var(--red) !important;
  }

  /* Table styling */
  .premium-table {
    margin-bottom: 0 !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    overflow: hidden;
  }
  .premium-table thead th {
    background: var(--surface-2) !important;
    border-bottom: 1.5px solid var(--border) !important;
    color: var(--text-2) !important;
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 12px 16px !important;
    vertical-align: middle !important;
  }
  .premium-table tbody td {
    padding: 12px 16px !important;
    border-bottom: 1px solid var(--border) !important;
    color: var(--text-2) !important;
    font-size: 0.85rem !important;
    vertical-align: middle !important;
  }
  .premium-table tbody tr:last-child td {
    border-bottom: none !important;
  }
  
  .grade-input-wrapper {
    max-width: 140px;
  }
  .grade-input-wrapper .form-control {
    height: 38px !important;
    text-align: right;
    font-weight: 600;
  }

  /* Save / Cancel buttons */
  .btn-save-lr {
    background: linear-gradient(135deg, var(--gold), var(--gold-deep)) !important;
    border: none !important;
    color: #fff !important;
    font-weight: 700 !important;
    padding: 10px 28px !important;
    border-radius: var(--radius-sm) !important;
    box-shadow: 0 2px 8px rgba(184,134,11,0.2) !important;
    transition: all .2s !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 0.88rem !important;
    height: 44px !important;
  }
  .btn-save-lr:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06) !important;
    color: #fff !important;
  }
  .btn-cancel-lr {
    border: 1.5px solid var(--border) !important;
    background: var(--surface) !important;
    color: var(--text-2) !important;
    font-weight: 700 !important;
    padding: 10px 28px !important;
    border-radius: var(--radius-sm) !important;
    transition: all .2s !important;
    font-size: 0.88rem !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    height: 44px !important;
  }
  .btn-cancel-lr:hover {
    background: var(--surface-2) !important;
    color: var(--text-1) !important;
    text-decoration: none !important;
  }
</style>

<div class="container margintopcontainer pt-5 pb-5 page-wrap">
  
  <!-- Header -->
  <div class="page-header">
    <a href="<?php echo $back_url; ?>" class="btn-back-elegant" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    <div style="width: 42px;"></div>
  </div>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($flash_success); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($flash_error); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <script>
      (function() {
        var msg = <?php echo json_encode((string)$flash_error); ?>;
        if (msg) {
          window.setTimeout(function() {
            alert(msg);
          }, 0);
        }
      })();
    </script>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card form-card">
        <div class="card-body">

      <form method="post" action="<?php echo site_url('admin/laagat_save'); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($form['id']) ? (string)$form['id'] : ''); ?>" />
        <div class="mb-3">
          <label class="form-label" for="lr_title">Title:</label>
          <input
            type="text"
            class="form-control"
            id="lr_title"
            name="title"
            value="<?php echo htmlspecialchars(isset($form['title']) ? (string)$form['title'] : ''); ?>"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="lr_hijri_year">Hijri Year</label>
          <select class="custom-select" id="lr_hijri_year" name="hijri_year" required>
            <option value="">Select Hijri Year</option>
            <?php if (!empty($hijri_year_options) && is_array($hijri_year_options)) : ?>
              <?php
                $sorted_hijri_year_options = $hijri_year_options;
                usort($sorted_hijri_year_options, function ($a, $b) {
                  $aStr = (string)$a;
                  $bStr = (string)$b;
                  $aNum = 0;
                  $bNum = 0;
                  if (preg_match('/^\s*(\d+)/', $aStr, $m)) {
                    $aNum = (int)$m[1];
                  }
                  if (preg_match('/^\s*(\d+)/', $bStr, $m)) {
                    $bNum = (int)$m[1];
                  }
                  if ($aNum === $bNum) {
                    return strcmp($bStr, $aStr);
                  }
                  return ($aNum < $bNum) ? 1 : -1;
                });
              ?>
              <?php foreach ($sorted_hijri_year_options as $yr) : ?>
                <?php $selected = (isset($form['hijri_year']) && (string)$form['hijri_year'] === (string)$yr) ? 'selected' : ''; ?>
                <option value="<?php echo htmlspecialchars((string)$yr); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars((string)$yr); ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <input type="hidden" id="lr_charge_type" name="charge_type" value="<?php echo htmlspecialchars($module_type); ?>" />

        <div class="mb-3" id="lr_venue_section" style="display: none;">
          <label class="form-label" for="lr_venue">Venue Selection</label>
          <select class="custom-select" id="lr_venue" name="venue">
            <option value="">All/Any Venue (Default)</option>
            <?php if (!empty($venue_options) && is_array($venue_options)) : ?>
              <?php foreach ($venue_options as $vo) : ?>
                <?php $selected = (isset($form['venue']) && (string)$form['venue'] === (string)$vo) ? 'selected' : ''; ?>
                <option value="<?php echo htmlspecialchars((string)$vo); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars((string)$vo); ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>


        <div class="mb-3">
          <label class="form-label" for="lr_raza_type_name">Applicable Raza Categories</label>
          <?php
            $existingRazaTypes = [];
            if (isset($form['raza_types']) && is_array($form['raza_types'])) {
              $existingRazaTypes = $form['raza_types'];
            } else if (!empty($form['raza_type_id']) && !empty($form['raza_type_name'])) {
              $existingRazaTypes = [[
                'id' => (int)$form['raza_type_id'],
                'name' => (string)$form['raza_type_name'],
              ]];
            }
          ?>

          <input
            type="text"
            class="form-control"
            id="lr_raza_type_name"
            list="lr_raza_type_list"
            autocomplete="off"
            value=""
            placeholder="Start typing..."
          />
          <datalist id="lr_raza_type_list"></datalist>

          <div id="lr_raza_selected" class="mt-2 d-flex flex-wrap"></div>

          <div id="lr_raza_hidden_inputs"></div>

          <?php if (!empty($existingRazaTypes)) : ?>
            <script>
              window.__lr_existingRazaTypes = <?php echo json_encode(array_values($existingRazaTypes)); ?>;
            </script>
          <?php endif; ?>
          
        </div>

        <div class="mb-3" id="lr_calculation_type_section" style="display: none;">
          <label class="form-label" for="lr_is_per_thaal">Calculation Type</label>
          <select class="custom-select" id="lr_is_per_thaal" name="is_per_thaal">
            <option value="0" <?php echo (isset($form['is_per_thaal']) && (int)$form['is_per_thaal'] === 0) ? 'selected' : ''; ?>>Flat Rate</option>
            <option value="1" <?php echo (isset($form['is_per_thaal']) && (int)$form['is_per_thaal'] === 1) ? 'selected' : ''; ?>>Thaal-range Based</option>
          </select>
        </div>

        <div id="lr_thaal_ranges_section" class="mb-4" style="display: none;">
          <label class="form-label font-weight-bold mb-3">Thaal Ranges &amp; Rates</label>
          <div id="lr_ranges_container" class="mb-3">
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm" id="lr_add_range_btn">
            <i class="fa fa-plus mr-1"></i> Add Thaal Range
          </button>
        </div>

        <div id="lr_items_section" class="mb-4" style="display: none;">
          <label class="form-label font-weight-bold mb-3">Rent Items</label>
          <p class="text-muted small mb-3">Add items available for rent. Set a cost per piece — the total will be calculated based on the number of pieces the member requests. The same rate applies to all members with no deposit.</p>
          <div id="lr_items_container" class="mb-3">
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm" id="lr_add_item_btn">
            <i class="fa fa-plus mr-1"></i> Add Item
          </button>
        </div>

        <?php if (!empty($form['thaal_ranges'])) : ?>
          <script>
            window.__lr_existingThaalRanges = <?php echo json_encode(array_values($form['thaal_ranges'])); ?>;
          </script>
        <?php endif; ?>

        <?php if (!empty($form['items'])) : ?>
          <script>
            window.__lr_existingItems = <?php echo json_encode(array_values($form['items'])); ?>;
          </script>
        <?php endif ?>

        <div class="row mb-4" id="lr_rent_details_section" style="display: none;">
          <div class="col-12 col-md-6 mb-3 mb-md-0">
            <div class="holder-card">
              <h6 class="section-subtitle"><?php echo htmlspecialchars(jamaat_place()); ?> Sabeel Holders</h6>
              <div class="mb-3">
                <label class="form-label" for="lr_rent_sabeel">Rent</label>
                <input
                  type="number"
                  class="form-control"
                  id="lr_rent_sabeel"
                  name="rent_sabeel"
                  min="0"
                  step="0.01"
                  value="<?php echo htmlspecialchars(isset($form['rent_sabeel']) ? (string)$form['rent_sabeel'] : ''); ?>"
                  placeholder="0.00"
                />
              </div>
              <div class="mb-0">
                <label class="form-label" for="lr_deposit_sabeel">Deposit</label>
                <input
                  type="number"
                  class="form-control"
                  id="lr_deposit_sabeel"
                  name="deposit_sabeel"
                  min="0"
                  step="0.01"
                  value="<?php echo htmlspecialchars(isset($form['deposit_sabeel']) ? (string)$form['deposit_sabeel'] : ''); ?>"
                  placeholder="0.00"
                />
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="holder-card">
              <h6 class="section-subtitle text-secondary">Non <?php echo htmlspecialchars(jamaat_place()); ?> Sabeel Holders</h6>
              <div class="mb-3">
                <label class="form-label" for="lr_rent_non_sabeel">Rent</label>
                <input
                  type="number"
                  class="form-control"
                  id="lr_rent_non_sabeel"
                  name="rent_non_sabeel"
                  min="0"
                  step="0.01"
                  value="<?php echo htmlspecialchars(isset($form['rent_non_sabeel']) ? (string)$form['rent_non_sabeel'] : ''); ?>"
                  placeholder="0.00"
                />
              </div>
              <div class="mb-0">
                <label class="form-label" for="lr_deposit_non_sabeel">Deposit</label>
                <input
                  type="number"
                  class="form-control"
                  id="lr_deposit_non_sabeel"
                  name="deposit_non_sabeel"
                  min="0"
                  step="0.01"
                  value="<?php echo htmlspecialchars(isset($form['deposit_non_sabeel']) ? (string)$form['deposit_non_sabeel'] : ''); ?>"
                  placeholder="0.00"
                />
              </div>
            </div>
          </div>
        </div>

        <div id="lr_grade_amounts_section" class="mb-4" style="display: none;">
          <label class="form-label font-weight-bold">Grade-based Amounts (Residential)</label>
          <div class="table-responsive">
            <table class="table premium-table table-hover align-middle">
              <thead>
                <tr>
                  <th scope="col" style="width: 40%;">Grade</th>
                  <th scope="col" class="text-right" style="width: 20%;">Jmt. Laagat</th>
                  <th scope="col" class="text-right" style="width: 20%;">Sar. Laagat</th>
                  <th scope="col" class="text-right" style="width: 20%;">Total Amount</th>
                </tr>
              </thead>
              <tbody id="lr_grade_amounts_tbody">
              </tbody>
            </table>
          </div>
        </div>

        <div class="d-flex align-items-center mt-4" style="gap: 10px;">
          <button type="submit" class="btn-save-lr">Save Form</button>
          <?php if (!empty($form['id'])) : ?>
            <a class="btn-cancel-lr" href="<?php echo $back_url; ?>">Cancel</a>
          <?php endif; ?>
        </div>
      </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var JAMAAT_PLACE = <?php echo json_encode(jamaat_place()); ?>;
  (function() {
    var form = document.querySelector('form[action="<?php echo site_url('admin/laagat_save'); ?>"]');
    var typeSelect = document.getElementById('lr_charge_type');
    var hijriYearSelect = document.getElementById('lr_hijri_year');
    var inputName = document.getElementById('lr_raza_type_name');
    var dataList = document.getElementById('lr_raza_type_list');
    var selectedWrap = document.getElementById('lr_raza_selected');
    var hiddenInputsWrap = document.getElementById('lr_raza_hidden_inputs');

    var dupCheckUrl = '<?php echo site_url('admin/laagat/check-duplicate'); ?>';

    if (!form || !typeSelect || !hijriYearSelect || !inputName || !dataList || !selectedWrap || !hiddenInputsWrap) return;

    var nameToId = {};
    var currentFetch = null;
    var selectedIds = {};
    var laagatRentId = (form.querySelector('input[name="id"]').value || '').trim();
    var gradeAmountsSection = document.getElementById('lr_grade_amounts_section');
    var gradeAmountsTbody = document.getElementById('lr_grade_amounts_tbody');
    var isInitializing = true;
    var lastFetchedYear = '';
    var activeGrades = [];

    function postFormForDupCheck() {
      if (!dupCheckUrl) return Promise.resolve({ success: true, exists: false });

      // If key fields missing, skip check and let server-side validation handle it.
      var hy = (hijriYearSelect.value || '').trim();
      var ct = (typeSelect.value || '').trim();
      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      if (!hy || !ct || !inputs || inputs.length === 0) {
        return Promise.resolve({ success: true, exists: false });
      }

      var fd = new FormData(form);

      // Use jQuery if available, else fetch
      if (window.$ && $.ajax) {
        return new Promise(function(resolve) {
          $.ajax({
            url: dupCheckUrl,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) { resolve(resp || { success: true, exists: false }); },
            error: function() { resolve({ success: true, exists: false }); }
          });
        });
      }

      if (window.fetch) {
        return window.fetch(dupCheckUrl, {
          method: 'POST',
          body: fd,
          credentials: 'same-origin'
        })
          .then(function(r) { return r.json(); })
          .catch(function() { return { success: true, exists: false }; });
      }

      return Promise.resolve({ success: true, exists: false });
    }

    form.addEventListener('submit', function(e) {
      if (form.__lrBypassSubmit) return;
      e.preventDefault();

      var saveBtn = form.querySelector('button[type="submit"]');
      if (saveBtn) {
        saveBtn.disabled = true;
      }

      postFormForDupCheck().then(function(resp) {
        var exists = resp && resp.exists;
        if (exists) {
          var msg = (resp && resp.message) ? String(resp.message) : "Raza already exist. You can't create same Raza.";
          try { alert(msg); } catch (ex) {}
          if (saveBtn) saveBtn.disabled = false;
          return;
        }

        form.__lrBypassSubmit = true;
        form.submit();
      });
    });

    function initSelectedFromDom() {
      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      for (var i = 0; i < inputs.length; i++) {
        var v = parseInt(inputs[i].value, 10);
        if (v > 0) selectedIds[v] = true;
      }
    }

    function clearOptions() {
      nameToId = {};
      while (dataList.firstChild) dataList.removeChild(dataList.firstChild);
    }

    function clearSelected() {
      selectedIds = {};
      while (selectedWrap.firstChild) selectedWrap.removeChild(selectedWrap.firstChild);
      while (hiddenInputsWrap.firstChild) hiddenInputsWrap.removeChild(hiddenInputsWrap.firstChild);
      if (!isInitializing) {
        updateGradeAmounts();
      }
    }

    function addSelected(id, name) {
      id = parseInt(id, 10);
      if (!id || id <= 0) return;
      if (selectedIds[id]) return;
      selectedIds[id] = true;

      var badge = document.createElement('span');
      badge.className = 'badge badge-primary mr-2 mb-2 lr-raza-badge';
      badge.setAttribute('data-id', String(id));
      badge.appendChild(document.createTextNode(name));

      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'close text-white lr-raza-remove';
      btn.setAttribute('aria-label', 'Remove');
      btn.setAttribute('data-remove-id', String(id));

      var x = document.createElement('span');
      x.setAttribute('aria-hidden', 'true');
      x.innerHTML = '&times;';
      btn.appendChild(x);
      badge.appendChild(btn);
      selectedWrap.appendChild(badge);

      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'raza_type_ids[]';
      hidden.value = String(id);
      hiddenInputsWrap.appendChild(hidden);

      if (!isInitializing) {
        updateGradeAmounts();
      }
    }

    function removeSelected(id) {
      id = parseInt(id, 10);
      if (!id || id <= 0) return;
      if (!selectedIds[id]) return;
      delete selectedIds[id];

      var badge = selectedWrap.querySelector('span[data-id="' + id + '"]');
      if (badge && badge.parentNode) badge.parentNode.removeChild(badge);

      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      for (var i = inputs.length - 1; i >= 0; i--) {
        if (parseInt(inputs[i].value, 10) === id) {
          inputs[i].parentNode.removeChild(inputs[i]);
        }
      }

      if (!isInitializing) {
        updateGradeAmounts();
      }
    }

    function renderOptions(items) {
      clearOptions();
      if (!items || !items.length) return;
      for (var i = 0; i < items.length; i++) {
        var it = items[i];
        if (!it || !it.name) continue;
        nameToId[it.name] = it.id;
        var opt = document.createElement('option');
        opt.value = it.name;
        dataList.appendChild(opt);
      }
    }

    function fetchOptions(term) {
      var chargeType = typeSelect.value || '';
      if (!chargeType) {
        clearOptions();
        inputName.value = '';
        return;
      }
      var url = '<?php echo site_url('admin/laagat_raza_categories'); ?>' + '?charge_type=' + encodeURIComponent(chargeType) + '&term=' + encodeURIComponent(term || '');
      if (currentFetch && currentFetch.abort) {
        try { currentFetch.abort(); } catch (e) {}
      }
      // Use jQuery if available, else fetch
      if (window.$ && $.getJSON) {
        currentFetch = $.getJSON(url, function(resp) {
          if (resp && resp.success) {
            renderOptions(resp.items || []);
          }
        });
      } else if (window.fetch) {
        window.fetch(url, { credentials: 'same-origin' })
          .then(function(r) { return r.json(); })
          .then(function(resp) { if (resp && resp.success) renderOptions(resp.items || []); });
      }
    }

    function updateGradeAmounts() {
      var year = (hijriYearSelect.value || '').trim();
      var hasCategories = Object.keys(selectedIds).length > 0;
      var ct = (typeSelect.value || '').trim();

      if (ct === 'rent' || !year || !hasCategories) {
        if (gradeAmountsSection) gradeAmountsSection.style.display = 'none';
        if (gradeAmountsTbody) gradeAmountsTbody.innerHTML = '';
        activeGrades = [];
        lastFetchedYear = '';
        return;
      }

      // If the year is the same, we just need to ensure the section is visible
      if (year === lastFetchedYear) {
        if (gradeAmountsSection) gradeAmountsSection.style.display = 'block';
        return;
      }

      var url = '<?php echo site_url('admin/laagat_get_grade_amounts'); ?>' + '?year=' + encodeURIComponent(year) + '&laagat_rent_id=' + encodeURIComponent(laagatRentId);
      
      var handleResponse = function(resp) {
        if (resp && resp.success) {
          lastFetchedYear = year;
          activeGrades = resp.grades || [];
          renderGradeInputs(activeGrades);
        }
      };

      if (window.$ && $.getJSON) {
        $.getJSON(url, handleResponse);
      } else if (window.fetch) {
        window.fetch(url, { credentials: 'same-origin' })
          .then(function(r) { return r.json(); })
          .then(handleResponse);
      }
    }

    function renderGradeInputs(grades) {
      if (!gradeAmountsTbody) return;
      gradeAmountsTbody.innerHTML = '';

      if (!grades || grades.length === 0) {
        gradeAmountsTbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No Residential Grades found for this Hijri Year.</td></tr>';
        if (gradeAmountsSection) gradeAmountsSection.style.display = 'block';
        return;
      }

      for (var i = 0; i < grades.length; i++) {
        var g = grades[i];
        var tr = document.createElement('tr');

        // Column 1: Grade
        var tdGrade = document.createElement('td');
        tdGrade.className = 'align-middle font-weight-bold';
        tdGrade.appendChild(document.createTextNode('Grade ' + g.grade));
        tr.appendChild(tdGrade);

        // Column 2: Jmt. Laagat Input
        var tdJmt = document.createElement('td');
        var inputJmt = document.createElement('input');
        inputJmt.type = 'number';
        inputJmt.min = '0';
        inputJmt.step = '0.01';
        inputJmt.className = 'form-control lr-grade-jmt-input';
        inputJmt.id = 'lr_grade_jmt_' + g.sabeel_takhmeen_grade_id;
        inputJmt.name = 'grade_jamaat_amounts[' + g.sabeel_takhmeen_grade_id + ']';
        var jVal = (g.saved_jamaat_amount !== null && g.saved_jamaat_amount !== undefined) ? g.saved_jamaat_amount : '';
        // If legacy amount is saved and jamaat amount is empty/null, default it to saved_amount
        if (jVal === '' && g.saved_amount !== null && g.saved_amount !== undefined) {
          jVal = g.saved_amount;
        }
        inputJmt.value = jVal;
        inputJmt.placeholder = '0.00';
        tdJmt.appendChild(inputJmt);
        tr.appendChild(tdJmt);

        // Column 3: Sar. Laagat Input
        var tdSar = document.createElement('td');
        var inputSar = document.createElement('input');
        inputSar.type = 'number';
        inputSar.min = '0';
        inputSar.step = '0.01';
        inputSar.className = 'form-control lr-grade-sar-input';
        inputSar.id = 'lr_grade_sar_' + g.sabeel_takhmeen_grade_id;
        inputSar.name = 'grade_sarkaar_amounts[' + g.sabeel_takhmeen_grade_id + ']';
        var sVal = (g.saved_sarkaar_amount !== null && g.saved_sarkaar_amount !== undefined) ? g.saved_sarkaar_amount : '';
        inputSar.value = sVal;
        inputSar.placeholder = '0.00';
        tdSar.appendChild(inputSar);
        tr.appendChild(tdSar);

        // Column 4: Total Amount Read-only Input
        var tdTotal = document.createElement('td');
        var inputTotal = document.createElement('input');
        inputTotal.type = 'number';
        inputTotal.className = 'form-control lr-grade-total-input';
        inputTotal.id = 'lr_grade_total_' + g.sabeel_takhmeen_grade_id;
        inputTotal.readOnly = true;
        var tVal = (g.saved_amount !== null && g.saved_amount !== undefined) ? g.saved_amount : '';
        inputTotal.value = tVal;
        inputTotal.placeholder = '0.00';
        tdTotal.appendChild(inputTotal);
        tr.appendChild(tdTotal);

        gradeAmountsTbody.appendChild(tr);
      }

      if (gradeAmountsSection) gradeAmountsSection.style.display = 'block';
    }

    if (gradeAmountsTbody) {
      gradeAmountsTbody.addEventListener('input', function(e) {
        var target = e.target;
        if (target && (target.classList.contains('lr-grade-jmt-input') || target.classList.contains('lr-grade-sar-input'))) {
          var tr = target.closest('tr');
          if (tr) {
            var jmtInput = tr.querySelector('.lr-grade-jmt-input');
            var sarInput = tr.querySelector('.lr-grade-sar-input');
            var totalInput = tr.querySelector('.lr-grade-total-input');
            if (jmtInput && sarInput && totalInput) {
              var jVal = parseFloat(jmtInput.value) || 0;
              var sVal = parseFloat(sarInput.value) || 0;
              totalInput.value = (jVal + sVal).toFixed(2);
            }
          }
        }
      });
    }

    hijriYearSelect.addEventListener('change', function() {
      updateGradeAmounts();
    });

    var venueSection = document.getElementById('lr_venue_section');
    var venueSelect = document.getElementById('lr_venue');
    var amountSection = document.getElementById('lr_amount_section');
    var amountInput = document.getElementById('lr_amount');
    var rentDetailsSection = document.getElementById('lr_rent_details_section');
    var rentSabeelInput = document.getElementById('lr_rent_sabeel');
    var rentNonSabeelInput = document.getElementById('lr_rent_non_sabeel');
    var calcTypeSection = document.getElementById('lr_calculation_type_section');
    var calcTypeSelect = document.getElementById('lr_is_per_thaal');
    var rangesSection = document.getElementById('lr_thaal_ranges_section');
    var rangesContainer = document.getElementById('lr_ranges_container');
    var addRangeBtn = document.getElementById('lr_add_range_btn');

    var itemsSection = document.getElementById('lr_items_section');
    var itemsContainer = document.getElementById('lr_items_container');
    var addItemBtn = document.getElementById('lr_add_item_btn');

    function addRangeRow(data = null) {
      var card = document.createElement('div');
      card.className = 'card mb-3 p-3 border rounded shadow-sm position-relative lr-range-card';
      card.style.background = '#faf9f6';

      var minVal = data ? data.thaal_min : '';
      var maxVal = data ? data.thaal_max : '';
      var rentSabeel = data ? data.rent_sabeel : '';
      var depositSabeel = data ? data.deposit_sabeel : '';
      var rentNonSabeel = data ? data.rent_non_sabeel : '';
      var depositNonSabeel = data ? data.deposit_non_sabeel : '';

      card.innerHTML = `
        <button type="button" class="btn btn-sm btn-link text-danger position-absolute btn-remove-range" style="top: 10px; right: 10px;" aria-label="Delete">
          <i class="fa fa-trash-o fa-lg"></i>
        </button>
        
        <div class="form-group mb-3" style="max-width: 320px;">
          <label class="form-label font-weight-bold small text-uppercase text-muted mb-1" style="font-size: 0.74rem; letter-spacing: 0.5px;">Thaal Count Range</label>
          <div class="d-flex align-items-center" style="gap: 8px;">
            <input type="number" class="form-control" name="range_thaal_min[]" value="${minVal}" min="1" required placeholder="Min Thaal" />
            <span class="text-muted font-weight-bold">to</span>
            <input type="number" class="form-control" name="range_thaal_max[]" value="${maxVal}" min="1" required placeholder="Max Thaal" />
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-sm-6 mb-3 mb-sm-0">
            <div class="p-3 rounded border bg-white shadow-xs">
              <div class="font-weight-bold small text-uppercase text-primary mb-2" style="font-size: 0.74rem; letter-spacing: 0.5px;">${JAMAAT_PLACE} Sabeel Holders</div>
              <div class="row mx-n2">
                <div class="col-6 px-2">
                  <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Rent Amount</label>
                  <input type="number" class="form-control" name="range_rent_sabeel[]" value="${rentSabeel}" min="0" step="0.01" required placeholder="0.00" />
                </div>
                <div class="col-6 px-2">
                  <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Deposit Amount</label>
                  <input type="number" class="form-control" name="range_deposit_sabeel[]" value="${depositSabeel}" min="0" step="0.01" required placeholder="0.00" />
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12 col-sm-6">
            <div class="p-3 rounded border bg-white shadow-xs">
              <div class="font-weight-bold small text-uppercase text-secondary mb-2" style="font-size: 0.74rem; letter-spacing: 0.5px;">Non ${JAMAAT_PLACE} Sabeel Holders</div>
              <div class="row mx-n2">
                <div class="col-6 px-2">
                  <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Rent Amount</label>
                  <input type="number" class="form-control" name="range_rent_non_sabeel[]" value="${rentNonSabeel}" min="0" step="0.01" required placeholder="0.00" />
                </div>
                <div class="col-6 px-2">
                  <label class="small text-muted mb-1 d-block" style="font-size: 0.72rem;">Deposit Amount</label>
                  <input type="number" class="form-control" name="range_deposit_non_sabeel[]" value="${depositNonSabeel}" min="0" step="0.01" required placeholder="0.00" />
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      rangesContainer.appendChild(card);
    }

    if (addRangeBtn) {
      addRangeBtn.addEventListener('click', function() {
        addRangeRow();
      });
    }

    if (rangesContainer) {
      rangesContainer.addEventListener('click', function(e) {
        var t = e.target;
        if (!t) return;
        var removeBtn = t.closest('.btn-remove-range');
        if (removeBtn) {
          var card = removeBtn.closest('.lr-range-card');
          if (card && card.parentNode) {
            card.parentNode.removeChild(card);
          }
        }
      });
    }

    if (addItemBtn && itemsContainer) {
      addItemBtn.addEventListener('click', function() {
        addItemCard();
      });
    }

    if (itemsContainer) {
      itemsContainer.addEventListener('click', function(e) {
        var t = e.target;
        if (!t) return;
        var removeBtn = t.closest('.btn-remove-item');
        if (removeBtn) {
          var card = removeBtn.closest('.lr-item-card');
          if (card && card.parentNode) {
            card.parentNode.removeChild(card);
          }
        }
      });
    }

    function addItemCard(data) {
      if (!itemsContainer) return;
      var card = document.createElement('div');
      card.className = 'card mb-3 p-3 border rounded shadow-sm position-relative lr-item-card';
      card.style.background = '#faf9f6';

      var itemName = data ? (data.item_name || '') : '';
      var rentS = data ? (data.rent_sabeel || '') : '';
      var depS = data ? (data.deposit_sabeel || '') : '';
      var rentNS = data ? (data.rent_non_sabeel || '') : '';
      var depNS = data ? (data.deposit_non_sabeel || '') : '';

      card.innerHTML = `
        <button type="button" class="btn btn-sm btn-link text-danger position-absolute btn-remove-item" style="top: 10px; right: 10px;" aria-label="Delete">
          <i class="fa fa-trash-o fa-lg"></i>
        </button>

        <div class="d-flex align-items-end" style="gap: 16px; flex-wrap: wrap;">
          <div class="form-group mb-0" style="min-width: 200px; flex: 2;">
            <label class="form-label font-weight-bold small text-uppercase text-muted mb-1" style="font-size: 0.74rem; letter-spacing: 0.5px;">Item Name</label>
            <input type="text" class="form-control" name="item_name[]" value="${itemName}" required placeholder="e.g. Chair, Table, Mic..." />
          </div>
          <div class="form-group mb-0" style="min-width: 140px; flex: 1;">
            <label class="form-label font-weight-bold small text-uppercase text-muted mb-1" style="font-size: 0.74rem; letter-spacing: 0.5px;">Cost / Piece</label>
            <input type="number" class="form-control" name="item_rent_sabeel[]" value="${rentS}" min="0" step="0.01" required placeholder="0.00" />
          </div>
        </div>
      `;

      itemsContainer.appendChild(card);
    }

    function toggleRentFields() {
      if (typeSelect && typeSelect.value === 'rent') {
        if (venueSection) venueSection.style.display = 'block';
        if (calcTypeSection) calcTypeSection.style.display = 'block';
        // Items section is always visible for rent type (independent of calculation type)
        if (itemsSection) itemsSection.style.display = 'block';

        var calcVal = calcTypeSelect ? calcTypeSelect.value : '0';
        if (calcVal === '1') {
          // Thaal-range Based: show ranges, hide flat rate fields
          if (rangesSection) rangesSection.style.display = 'block';
          if (rentDetailsSection) {
            rentDetailsSection.style.display = 'none';
            var inputs = rentDetailsSection.querySelectorAll('input');
            for (var i = 0; i < inputs.length; i++) inputs[i].removeAttribute('required');
          }
          if (rangesContainer) {
            var rangeInputs = rangesContainer.querySelectorAll('input');
            for (var i = 0; i < rangeInputs.length; i++) rangeInputs[i].setAttribute('required', 'required');
          }
        } else {
          // Flat Rate: show flat rate fields, hide ranges
          if (rangesSection) {
            rangesSection.style.display = 'none';
            if (rangesContainer) {
              var rangeInputs = rangesContainer.querySelectorAll('input');
              for (var i = 0; i < rangeInputs.length; i++) rangeInputs[i].removeAttribute('required');
            }
          }
          if (rentDetailsSection) {
            rentDetailsSection.style.display = 'flex';
            if (rentSabeelInput) rentSabeelInput.setAttribute('required', 'required');
            if (rentNonSabeelInput) rentNonSabeelInput.setAttribute('required', 'required');
          }
        }

        if (amountSection) {
          amountSection.style.display = 'none';
          if (amountInput) { amountInput.removeAttribute('required'); amountInput.value = ''; }
        }
      } else {
        // Not rent — hide all rent-specific sections
        if (venueSection) venueSection.style.display = 'none';
        if (venueSelect) venueSelect.value = '';
        if (calcTypeSection) {
          calcTypeSection.style.display = 'none';
          if (calcTypeSelect) calcTypeSelect.value = '0';
        }
        if (rangesSection) {
          rangesSection.style.display = 'none';
          if (rangesContainer) {
            var rangeInputs = rangesContainer.querySelectorAll('input');
            for (var i = 0; i < rangeInputs.length; i++) rangeInputs[i].removeAttribute('required');
          }
        }
        if (itemsSection) {
          itemsSection.style.display = 'none';
          if (itemsContainer) {
            var itemInputs = itemsContainer.querySelectorAll('input');
            for (var i = 0; i < itemInputs.length; i++) itemInputs[i].removeAttribute('required');
          }
        }
        if (amountSection) {
          amountSection.style.display = 'none';
          if (amountInput) { amountInput.removeAttribute('required'); amountInput.value = ''; }
        }
        if (rentDetailsSection) {
          rentDetailsSection.style.display = 'none';
          var inputs = rentDetailsSection.querySelectorAll('input');
          for (var i = 0; i < inputs.length; i++) { inputs[i].value = ''; inputs[i].removeAttribute('required'); }
        }
      }
    }

    if (calcTypeSelect) {
      calcTypeSelect.addEventListener('change', toggleRentFields);
    }

    typeSelect.addEventListener('change', function() {
      inputName.value = '';
      clearSelected();
      fetchOptions('');
      toggleRentFields();
    });

    inputName.addEventListener('input', function() {
      var term = inputName.value || '';
      fetchOptions(term);
    });

    inputName.addEventListener('change', function() {
      var name = (inputName.value || '').trim();
      if (name && nameToId[name]) {
        addSelected(nameToId[name], name);
        inputName.value = '';
        clearOptions();
      }
    });

    selectedWrap.addEventListener('click', function(e) {
      var t = e.target;
      if (!t) return;
      var btn = t.closest ? t.closest('[data-remove-id]') : null;
      if (!btn) {
        // Fallback for older browsers
        while (t && t !== selectedWrap) {
          if (t.getAttribute && t.getAttribute('data-remove-id')) { btn = t; break; }
          t = t.parentNode;
        }
      }
      if (btn && btn.getAttribute) {
        var rid = btn.getAttribute('data-remove-id');
        removeSelected(rid);
      }
    });

    // Initial load (edit mode)
    if (window.__lr_existingRazaTypes && Array.isArray(window.__lr_existingRazaTypes)) {
      for (var i = 0; i < window.__lr_existingRazaTypes.length; i++) {
        var it = window.__lr_existingRazaTypes[i];
        if (it && it.id && it.name) {
          addSelected(it.id, it.name);
        }
      }
    } else {
      initSelectedFromDom();
    }

    if (window.__lr_existingThaalRanges && Array.isArray(window.__lr_existingThaalRanges)) {
      for (var j = 0; j < window.__lr_existingThaalRanges.length; j++) {
        addRangeRow(window.__lr_existingThaalRanges[j]);
      }
    }

    if (window.__lr_existingItems && Array.isArray(window.__lr_existingItems)) {
      for (var k = 0; k < window.__lr_existingItems.length; k++) {
        addItemCard(window.__lr_existingItems[k]);
      }
    }

    isInitializing = false;
    updateGradeAmounts();
    fetchOptions('');
    toggleRentFields();
  })();
</script>

<style>
  #lr_raza_selected .badge { font-weight: 600; }
  #lr_raza_selected .lr-raza-badge {
    display: inline-flex;
    align-items: center;
    padding: .45rem .6rem;
    font-size: 0.95rem;
    line-height: 1.1;
    border-radius: .25rem;
  }
  #lr_raza_selected .lr-raza-remove {
    float: none;
    opacity: 0.9;
    text-shadow: none;
    margin-left: .5rem;
    padding: 0;
    line-height: 1;
    font-size: 1.2rem;
  }
  #lr_raza_selected .lr-raza-remove:hover { opacity: 1; }
</style>
