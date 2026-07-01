<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link
  href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap"
  rel="stylesheet">

<style>
  :root {
    --gold: #b8860b;
    --gold-light: #e6c84a;
    --gold-muted: #f5e9c0;
    --gold-deep: #8a6408;
    --bg: #faf7f0;
    --surface: #ffffff;
    --surface-2: #f7f4ec;
    --border: #e8e0cc;
    --text-1: #1a1610;
    --text-2: #5a5244;
    --text-3: #9c8f7a;
    --green: #1a6645;
    --green-bg: #eaf4ee;
    --green-border: #bbf7d0;
    --red: #b91c1c;
    --red-bg: #fef2f2;
    --red-border: #fecaca;
    --blue: #1d4ed8;
    --blue-bg: #eff6ff;
    --blue-border: #bfdbfe;
    --amber: #b45309;
    --amber-bg: #fffbeb;
    --amber-border: #fde68a;
    --radius-sm: 8px;
    --radius: 14px;
    --radius-lg: 20px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    --shadow: 0 4px 16px rgba(0, 0, 0, 0.07), 0 1px 4px rgba(0, 0, 0, 0.04);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.10), 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-1);
  }

  .raza-form-page {
    min-height: 80vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 40px 16px 60px;
  }

  .raza-form-card {
    width: 100%;
    max-width: 520px;
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
  }

  .raza-form-card::before {
    content: '';
    display: block;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
  }

  .raza-card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--surface);
  }

  .raza-card-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--gold);
    letter-spacing: -.2px;
    line-height: 1.3;
    margin: 0;
  }

  .raza-card-body {
    padding: 24px;
    background: var(--bg);
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label,
  .col-form-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: .5px;
    color: var(--text-3);
    text-transform: uppercase;
    margin-bottom: 7px;
    display: block;
  }

  .requiredField .asteriskField,
  .asteriskField {
    color: var(--red);
    margin-left: 2px;
    font-size: 0.85em;
  }

  .form-control {
    width: 100%;
    padding: 11px 16px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .form-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.13), 0 1px 3px rgba(0, 0, 0, 0.05);
    background: #fffef9;
  }

  .form-control:hover:not(:focus) {
    border-color: #cfc4ac;
  }

  .form-control::placeholder {
    color: var(--text-3);
    font-weight: 400;
  }

  .form-control:disabled,
  .form-control[disabled] {
    background: var(--surface-2);
    color: var(--text-2);
    cursor: not-allowed;
    opacity: 1;
  }

  /* native select: arrow on right, text never clips */
  select.form-control {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding: 11px 40px 11px 16px;
    line-height: 1.4;
    height: 44px;
    overflow: visible;
    white-space: normal;
    text-overflow: clip;
    cursor: pointer;
  }

  select.form-control:disabled {
    background-image: none;
    padding-right: 16px;
    cursor: not-allowed;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 96px;
    white-space: normal;
    overflow: auto;
  }

  /* ── Select2 override ── */
  .select2-container {
    width: 100% !important;
  }

  .select2-container .select2-selection--single {
    height: 44px !important;
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    background: var(--surface) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    transition: border-color .2s, box-shadow .2s !important;
  }

  .select2-container--disabled .select2-selection--single {
    background: var(--surface-2) !important;
    cursor: not-allowed !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 16px !important;
    padding-right: 40px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    color: var(--text-1) !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: var(--text-3) !important;
    font-weight: 400 !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
    right: 10px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: var(--gold) transparent transparent transparent !important;
    border-width: 5px 4px 0 4px !important;
  }

  .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent var(--gold) transparent !important;
    border-width: 0 4px 5px 4px !important;
  }

  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.13) !important;
    background: #fffef9 !important;
  }

  .select2-dropdown {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    box-shadow: var(--shadow-lg) !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    overflow: hidden !important;
  }

  .select2-results__option {
    padding: 10px 16px !important;
    font-size: 0.88rem !important;
    font-weight: 500 !important;
    color: var(--text-2) !important;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--gold-muted) !important;
    color: var(--gold-deep) !important;
  }

  .select2-container--default .select2-results__option[aria-selected=true] {
    background: var(--surface-2) !important;
    color: var(--text-1) !important;
    font-weight: 600 !important;
  }

  .select2-search--dropdown .select2-search__field {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius-sm) !important;
    padding: 8px 12px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 0.85rem !important;
    outline: none !important;
  }

  .select2-search--dropdown .select2-search__field:focus {
    border-color: var(--gold) !important;
    box-shadow: 0 0 0 2px rgba(184, 134, 11, 0.1) !important;
  }

  /* ── Dynamic content cards (miqaat details) ── */
  #dynamic-fields-container .card {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    background: var(--surface-2) !important;
    box-shadow: none !important;
    margin-top: 10px;
  }

  #dynamic-fields-container .card .card-body {
    padding: 16px 18px;
  }

  #dynamic-fields-container .card .card-body p {
    font-size: 0.85rem;
    margin-bottom: 8px;
    color: var(--text-2);
  }

  #dynamic-fields-container .card .card-body p strong {
    color: var(--text-1);
  }

  /* alert overrides */
  .alert {
    border-radius: var(--radius-sm);
    font-size: 0.83rem;
    padding: 10px 14px;
    border-width: 1.5px;
  }

  .alert-info {
    background: var(--blue-bg);
    color: var(--blue);
    border-color: var(--blue-border);
  }

  .alert-success {
    background: var(--green-bg);
    color: var(--green);
    border-color: var(--green-border);
  }

  .alert-warning {
    background: var(--amber-bg);
    color: var(--amber);
    border-color: var(--amber-border);
  }

  /* ── Lower buttons area ── */
  .lowerbutton {
    display: none;
  }

  .form-section-divider {
    border: none;
    border-top: 1px dashed var(--border);
    margin: 22px 0 18px;
  }

  /* confirmation checkbox */
  .form-check {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    margin-bottom: 18px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
  }

  .form-check:has(input:checked) {
    border-color: var(--green);
    background: var(--green-bg);
  }

  .form-check-input {
    position: static !important;
    width: 17px;
    height: 17px;
    accent-color: var(--green);
    flex-shrink: 0;
    cursor: pointer;
    margin: 0 !important;
    float: none !important;
  }

  .form-check-label {
    position: static !important;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-2);
    cursor: pointer;
    margin: 0 !important;
    text-transform: none;
    letter-spacing: 0;
    float: none !important;
  }

  /* action buttons row */
  .row.px-3 {
    display: flex;
    gap: 10px;
    margin: 0;
  }

  .btn-danger {
    flex: 0 0 auto;
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: .3px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all .15s;
    cursor: pointer;
  }

  .btn-danger:hover {
    background: var(--red-bg);
    border-color: var(--red);
    color: var(--red);
    text-decoration: none;
  }

  .btn-success {
    flex: 1;
    padding: 11px 20px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: .3px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .18s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.3);
  }

  .btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
    box-shadow: 0 4px 14px rgba(184, 134, 11, 0.4);
    transform: translateY(-1px);
  }

  .btn-success:disabled {
    opacity: .55;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  /* ── Toast ── */
  .toast-message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: var(--radius-sm);
    z-index: 9999;
    display: none;
    font-size: 0.85rem;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow-lg);
    color: var(--text-1);
    animation: slideIn 0.5s, slideOut 0.5s 2s;
  }

  .confirm-check-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    margin-bottom: 18px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
  }

  .confirm-check-wrap:has(input:checked) {
    border-color: var(--green);
    background: var(--green-bg);
  }

  .confirm-check-wrap input[type="checkbox"] {
    width: 17px;
    height: 17px;
    accent-color: var(--green);
    flex-shrink: 0;
    cursor: pointer;
  }

  .confirm-check-wrap label {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-2);
    cursor: pointer;
    margin: 0;
    text-transform: none;
    letter-spacing: 0;
  }

  /* action buttons row */
  .form-actions {
    display: flex;
    gap: 10px;
  }

  .btn-cancel-raza {
    flex: 0 0 auto;
    padding: 10px 20px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: .3px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all .15s;
    cursor: pointer;
  }

  .btn-cancel-raza:hover {
    background: var(--red-bg);
    border-color: var(--red);
    color: var(--red);
    text-decoration: none;
  }

  .btn-submit-raza {
    flex: 1;
    padding: 11px 20px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem;
    font-weight: 700;
    letter-spacing: .3px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .18s;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.3);
  }

  .btn-submit-raza:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
    box-shadow: 0 4px 14px rgba(184, 134, 11, 0.4);
    transform: translateY(-1px);
  }

  .btn-submit-raza:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
  }

  @keyframes slideIn {
    from {
      right: -100%;
    }

    to {
      right: 20px;
    }
  }

  @keyframes slideOut {
    from {
      right: 20px;
    }

    to {
      right: -100%;
    }
  }

  @media (max-width: 575px) {
    .raza-form-page {
      padding: 20px 12px 40px;
    }

    .raza-card-header {
      padding: 14px 16px 12px;
    }

    .raza-card-body {
      padding: 16px;
      background: var(--bg);
    }

    .row.px-3 {
      flex-direction: column;
    }

    .btn-danger {
      text-align: center;
      justify-content: center;
    }
  }

  @media only screen and (max-width: 767px) {
    .w100percent-xs {
      width: 100% !important;
    }
  }

  .secondaryform {
    display: none;
  }
</style>

<div class="margintopcontainer">
  <div class="raza-form-page">
    <div class="raza-form-card">

      <!-- ── Card header ── -->
      <div class="raza-card-header">
        <h1 class="raza-card-title form-title">Update Raza Request</h1>
      </div>

      <!-- ── Card body ── -->
      <div class="raza-card-body">
        <form id="raza-form" class="main-form" action="<?php echo base_url('accounts/updateraza/') . $raza['id'] ?>"
          method="post">

          <!-- Render the 'raza-type' dropdown -->
          <div class="form-group">
            <label for="id_raza-raza" class="col-form-label requiredField">
              Raza for<span class="asteriskField">*</span>
            </label>
            <select name="raza-type" class="select2widget form-control" required id="raza-type" disabled
              data-allow-clear="false" data-minimum-input-length="0" tabindex="-1" aria-hidden="true">
              <?php
              foreach ($razatype as $rt) {
                $selected = ($rt['id'] == $raza['razaType']) ? 'selected' : '';
                echo '<option value="' . $rt['id'] . '" ' . $selected . '>' . $rt['name'] . '</option>';
              }
              ?>
            </select>
          </div>

          <!-- Render dynamic fields based on selected 'raza-type' -->
          <div id="dynamic-fields-container"></div>

          <div id="lowerbutton" class="lowerbutton">
    <div class="form-section-divider"></div>

    <div class="confirm-check-wrap hide-for-miqaat">
        <input type="checkbox" id="exampleCheck1" required>
        <label for="exampleCheck1">
            All details are correct and I confirm this update
        </label>
    </div>

    <div class="form-actions">
        <a href="<?= base_url('accounts/MyRazaRequest') ?>"
           class="btn-cancel-raza cancel-miqaat-back">
            <i class="fa fa-times"></i> Cancel
        </a>

        <button type="submit"
                id="raza-update-submit-btn"
                class="btn-submit-raza hide-for-miqaat"
                disabled>
            <i class="fa fa-paper-plane"></i> Update Raza
        </button>
    </div>
</div>

        </form>
      </div><!-- /.raza-card-body -->
    </div><!-- /.raza-form-card -->
  </div><!-- /.raza-form-page -->
</div>

<div id="toast-message" class="toast-message">
  done
</div>

<!-- ══════════════════════════════════════════════════════
     JAVASCRIPT — logic unchanged, class names harmonized
     with the new theme (select2widget added to dynamic
     selects so they pick up the styled Select2 dropdown),
     plus a listener gating the submit button on the
     confirmation checkbox
     ══════════════════════════════════════════════════════ -->
<script>
  let razas = [];
  <?php foreach ($razatype as $r) { ?>
    razas.push(<?php echo $r['fields'] ?>)
  <?php } ?>

  let raza_data_field = <?php echo $raza['razadata'] ?>;

  updateFormFields();

  // Submit is only enabled once the confirmation checkbox is checked
  document.getElementById('exampleCheck1').addEventListener('change', function () {
    let submitBtn = document.getElementById('raza-update-submit-btn');
    if (submitBtn) submitBtn.disabled = !this.checked;
  });

  function updateFormFields() {
    let selectedRazaType = document.getElementById('raza-type').value;

    let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
    let lowerelmentid = document.getElementById('lowerbutton')
    lowerelmentid.style.display = 'block';
    if (selectedRazaType == 2) {
      $(".form-title").text("View Raza Details");
      $(".hide-for-miqaat").hide();
      $(".cancel-miqaat-back").html('<i class="fa fa-arrow-left"></i> Back');
      // Show raza details joined with miqaat table
      let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
      dynamicFieldsContainer.innerHTML = '';
      <?php if (isset($raza_miqaat) && is_array($raza_miqaat)): ?>
        let miqaat = <?php echo json_encode($raza_miqaat); ?>;
        let html = '<div class="card mt-2 mb-3"><div class="card-body">';
        html += `<p><strong>Miqaat Name:</strong> ${miqaat.name || ''}</p>`;
        html += `<p><strong>Miqaat Type:</strong> ${miqaat.type || ''}</p>`;
        html += `<p><strong>Miqaat Date:</strong> ${miqaat.date ? new Date(miqaat.date).toLocaleDateString('en-GB') : ''}</p>`;
        html += '</div></div>';
        dynamicFieldsContainer.innerHTML = html;
      <?php else: ?>
        dynamicFieldsContainer.innerHTML = '<div class="alert alert-info">No miqaat data found for this raza.</div>';
      <?php endif; ?>
    } else {
      if (selectedRaza) {
        let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
        dynamicFieldsContainer.innerHTML = '';
        selectedRaza.fields.forEach(field => {
          let fieldContainer = document.createElement('div');
          fieldContainer.classList.add('form-group');

          let label = document.createElement('label');
          label.setAttribute('for', `id_raza-${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_')}`);
          label.classList.add('col-form-label', 'requiredField');
          if (field.required) {
            label.innerHTML = `${field.name}<span class="asteriskField">*</span>`;
          } else {
            label.innerHTML = `${field.name}`;
          }

          let inputElement;

          switch (field.type) {
            case 'date':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'date');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('dateinput', 'form-control');
              inputElement.required = field.required;
              break;
            case 'select':
              inputElement = document.createElement('select');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('select2widget', 'form-control');
              inputElement.required = field.required;

              // Add options for select
              field.options.forEach(option => {
                let optionElement = document.createElement('option');
                optionElement.value = option.id;
                optionElement.text = option.name;
                inputElement.appendChild(optionElement);
              });
              break;
            case 'text':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'text');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;
            case 'number':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'number');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;
            case 'textarea':
              inputElement = document.createElement('textarea');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;

            default:
              break;
          }
          inputElement.value = raza_data_field[`${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_')}`]
          fieldContainer.appendChild(label);
          fieldContainer.appendChild(inputElement);
          dynamicFieldsContainer.appendChild(fieldContainer);
        });

        // Initialize Select2 on any newly-created dynamic selects
        // so they use the same themed dropdown as the New Raza page
        if ($.fn.select2) {
          $('#dynamic-fields-container select.select2widget').select2({
            width: '100%',
            dropdownAutoWidth: false
          });
        }
      }
    }
  }
</script>