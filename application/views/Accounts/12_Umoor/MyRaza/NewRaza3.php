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

  .raza-card-header-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
  }

  .btn-back-raza {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-size: 13px;
    text-decoration: none;
    flex-shrink: 0;
    transition: all .15s;
    box-shadow: var(--shadow-sm);
  }

  .btn-back-raza:hover {
    background: var(--gold-muted);
    border-color: var(--gold);
    color: var(--gold);
    text-decoration: none;
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

  .raza-value-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: .2px;
    color: var(--gold-deep);
    background: var(--gold-muted);
    border: 1px solid rgba(184, 134, 11, 0.3);
    border-radius: 40px;
    padding: 4px 12px;
  }

  .raza-value-badge .fa {
    color: var(--gold);
    font-size: 10px;
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

  textarea.form-control {
    resize: vertical;
    min-height: 96px;
    white-space: normal;
    overflow: auto;
  }

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

  .form-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 20px 0;
  }

  .dynamic-info-card {
    background: var(--surface-2);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    margin-top: 6px;
  }

  .dynamic-info-card p {
    margin: 0 0 8px;
    font-size: 0.85rem;
    color: var(--text-2);
  }

  .dynamic-info-card p:last-child {
    margin-bottom: 0;
  }

  .dynamic-info-card strong {
    color: var(--text-1);
    font-weight: 700;
  }

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

  #dynamic-fields-container .table {
    font-size: 0.82rem;
    border-color: var(--border);
  }

  #dynamic-fields-container .table th {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: .4px;
    text-transform: uppercase;
    color: var(--text-3);
    border-color: var(--border);
    background: var(--surface-2);
  }

  #dynamic-fields-container .table td {
    border-color: var(--border);
    color: var(--text-2);
  }

  .badge.bg-primary {
    background: var(--blue-bg) !important;
    color: var(--blue) !important;
    border: 1px solid var(--blue-border);
    font-weight: 700;
    font-size: 0.68rem;
    padding: 3px 9px;
    border-radius: 40px;
  }

  .badge.bg-success {
    background: var(--green-bg) !important;
    color: var(--green) !important;
    border: 1px solid var(--green-border);
    font-weight: 700;
    font-size: 0.68rem;
    padding: 3px 9px;
    border-radius: 40px;
  }

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

  #dynamic-fields-container .rounded.border {
    border: 1.5px solid var(--border) !important;
    border-radius: var(--radius) !important;
    background: var(--surface-2) !important;
  }

  #dynamic-fields-container .bg-light {
    background: var(--surface-2) !important;
  }

  .lowerbutton {
    display: none;
  }

  .form-section-divider {
    border: none;
    border-top: 1px dashed var(--border);
    margin: 22px 0 18px;
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
    position: static !important;
    width: 17px;
    height: 17px;
    accent-color: var(--green);
    flex-shrink: 0;
    cursor: pointer;
    margin: 0 !important;
    float: none !important;
  }

  .confirm-check-wrap label {
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

  #dues-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    z-index: 1050;
  }

  #dues-modal .modal-dialog {
    margin: 0;
    width: 100%;
    max-width: 820px;
    max-height: 90vh;
  }

  #dues-modal .modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
  }

  #dues-modal .modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border);
    background: var(--surface-2);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  #dues-modal .modal-title {
    font-family: 'Literata', Georgia, serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gold);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
  }

  #dues-modal .modal-title::before {
    content: '\f19c';
    font-family: FontAwesome;
    font-style: normal;
    font-size: 0.9rem;
  }

  #dues-modal .close {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    color: var(--text-2);
    transition: all .15s;
    padding: 0;
    line-height: 1;
  }

  #dues-modal .close:hover {
    background: var(--red-bg);
    border-color: var(--red);
    color: var(--red);
  }

  #dues-modal .modal-body {
    overflow-y: auto;
    flex: 1 1 auto;
    padding: 20px 24px;
    background: var(--surface);
  }

  #dues-modal .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface-2);
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  #dues-content .table {
    font-size: 0.83rem;
    border-color: var(--border);
    width: 100%;
  }

  #dues-content .table th {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: .4px;
    text-transform: uppercase;
    color: var(--text-3);
    border-color: var(--border);
    background: var(--surface-2);
    padding: 8px 12px;
  }

  #dues-content .table td {
    border-color: var(--border);
    color: var(--text-2);
    padding: 9px 12px;
    vertical-align: middle;
  }

  #dues-content .table tr:last-child th,
  #dues-content .table tr:last-child td {
    background: var(--surface-2);
    font-weight: 700;
    color: var(--text-1);
  }

  #dues-content .text-right {
    text-align: right;
  }

  .btn-modal-cancel {
    padding: 9px 20px;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border);
    background: var(--surface);
    color: var(--text-2);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
  }

  .btn-modal-cancel:hover {
    background: var(--surface-2);
    border-color: var(--text-3);
  }

  .btn-modal-proceed {
    padding: 9px 22px;
    border-radius: var(--radius-sm);
    border: none;
    background: linear-gradient(135deg, var(--gold), var(--gold-deep));
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    box-shadow: 0 2px 8px rgba(184, 134, 11, 0.25);
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .btn-modal-proceed:hover {
    background: linear-gradient(135deg, var(--gold-deep), #6b4d06);
  }

  .btn-modal-proceed::before {
    content: '\f00c';
    font-family: FontAwesome;
    font-size: 12px;
  }

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

    .form-actions {
      flex-direction: column;
    }

    .btn-cancel-raza {
      text-align: center;
      justify-content: center;
    }

    #dues-modal .modal-header,
    #dues-modal .modal-body,
    #dues-modal .modal-footer {
      padding: 14px 16px;
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

      <div class="raza-card-header">
        <div class="raza-card-header-top">
          <a href="<?= base_url("umoor12/MyRazaRequest?value=$value") ?>" class="btn-back-raza">
            <i class="fa fa-arrow-left"></i>
          </a>
          <h1 class="raza-card-title">New Raza Request</h1>
        </div>
        <?php
        $value_display = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);
        $value_display = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1 $2', $value_display);
        ?>
        <div>
          <span class="raza-value-badge">
            <i class="fa fa-tag"></i>
            <?= htmlspecialchars($value_display) ?>
          </span>
        </div>
      </div>

      <div class="raza-card-body">
        <form id="raza-form" class="main-form" action="<?= base_url("accounts/submit_raza") ?>" method="post">

          <div class="form-group">
            <label for="raza-type" class="col-form-label requiredField">
              Raza for <span class="asteriskField">*</span>
            </label>
            <select name="raza-type" class="select2widget form-control" required id="raza-type"
              onchange="updateFormFields()">
              <option value="">— Select a category —</option>
              <?php foreach ($razatype as $raza) {
                $selected = (!empty($razaId) && $raza['id'] == $razaId) ? 'selected' : '';
                echo '<option value="' . $raza['id'] . '" ' . $selected . '>' . $raza['name'] . '</option>';
              } ?>
            </select>
          </div>

          <div id="dynamic-fields-container"></div>

          <div id="lowerbutton" class="lowerbutton">
            <div class="form-section-divider"></div>

            <div class="confirm-check-wrap">
              <input type="checkbox" id="exampleCheck1" required>
              <label for="exampleCheck1">All details are correct and I confirm this submission</label>
            </div>

            <div class="form-actions">
              <a href="<?= base_url("umoor12/MyRazaRequest?value=$value") ?>" class="btn-cancel-raza">
                <i class="fa fa-times"></i> Cancel
              </a>
              <button type="submit" id="raza-submit-btn" class="btn-submit-raza" disabled>
                <i class="fa fa-paper-plane"></i> Submit Raza
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div id="dues-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pending Financial Dues</h5>
        <button type="button" class="close" aria-label="Close" onclick="hideDuesModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="dues-content">
          <div style="text-align:center; padding:24px 0; color:var(--text-3);">
            <i class="fa fa-spinner fa-spin" style="font-size:1.5rem; margin-bottom:8px; display:block;"></i>
            Loading dues…
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" onclick="hideDuesModal()">Cancel</button>
        <button type="button" id="dues-confirm" class="btn-modal-proceed">Proceed &amp; Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="dues-backdrop"
  style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1040; backdrop-filter:blur(2px);">
</div>

<div id="toast-message" class="toast-message">done</div>

<!-- ══════════════════════════════════════════════════════
     JAVASCRIPT — SAME AS ORIGINAL, PLUS ONE NEW LISTENER
     GATING THE SUBMIT BUTTON ON THE CONFIRMATION CHECKBOX
     ══════════════════════════════════════════════════════ -->
<script>
  let razas = [];
  <?php foreach ($razatype as $raza) {
    echo "razas.push(" . $raza['fields'] . ");";
  } ?>

  function isLaagatRentAmountField(fieldLabel) {
    fieldLabel = (fieldLabel || '').toString().toLowerCase();
    return /(?:laagat\s*(?:\/\s*rent)?|rent)\s*amount/.test(fieldLabel);
  }

  function formatInrCurrency(amount) {
    var n = Number(amount);
    if (!isFinite(n)) n = 0;
    var hasPaise = Math.abs(n - Math.round(n)) > 1e-9;
    var opts = { minimumFractionDigits: hasPaise ? 2 : 0, maximumFractionDigits: hasPaise ? 2 : 0 };
    try { return '₹' + n.toLocaleString('en-IN', opts); }
    catch (e) { return '₹' + String(n); }
  }

  function closestFormGroup(el) {
    var cur = el;
    while (cur && cur !== document) {
      if (cur.classList && (cur.classList.contains('form-group') || cur.classList.contains('mb-3'))) return cur;
      cur = cur.parentNode;
    }
    return null;
  }

  function renderLaagatRentInfo(groupEl, rawFieldName, chargeType, title, amount, jamaatAmount, sarkaarAmount, depositAmount, items = [], itemQty = {}) {
    if (!groupEl) return;
    rawFieldName = (rawFieldName || '').toString();
    var displayLabel = (chargeType === 'laagat') ? 'Laagat Details' : 'Rent & Deposit Details';
    var bgClass = 'bg-white';
    var toneClass = 'text-dark';
    var n = Number(amount);
    if (!isFinite(n)) n = 0;

    var existingBox = document.getElementById('lr-rent-box');
    if (existingBox && existingBox.getAttribute('data-charge-type') === chargeType && existingBox.getAttribute('data-title') === String(title || '')) {
      var totalRentVals = existingBox.querySelectorAll('.lr-total-rent-value');
      totalRentVals.forEach(function(el) {
        el.textContent = formatInrCurrency(n);
      });
      var depVal = existingBox.querySelector('.lr-deposit-value');
      if (depVal) {
        depVal.textContent = formatInrCurrency(Number(depositAmount));
      }
      return;
    }

    while (groupEl.firstChild) groupEl.removeChild(groupEl.firstChild);

    var box = document.createElement('div');
    box.id = 'lr-rent-box';
    box.setAttribute('data-base-rent', String(n));
    box.setAttribute('data-charge-type', chargeType);
    box.setAttribute('data-title', String(title || ''));
    box.className = 'pt-2 pb-3 px-3 rounded border ' + bgClass;
    groupEl.appendChild(box);

    var label = document.createElement('label');
    label.className = 'col-form-label ' + toneClass;
    label.textContent = displayLabel;
    box.appendChild(label);

    if (title) {
      var row = document.createElement('div');
      row.className = 'd-flex justify-content-between align-items-baseline mt-1';
      var t = document.createElement('div');
      t.className = 'h5 mb-0 ' + toneClass;
      t.textContent = String(title);
      row.appendChild(t);
      var v = document.createElement('div');
      v.className = 'h5 mb-0 text-success lr-total-rent-value';
      v.textContent = formatInrCurrency(n);
      row.appendChild(v);
      box.appendChild(row);
    } else {
      var v = document.createElement('div');
      v.className = 'h5 mb-0 w-100 text-end text-success mt-1 lr-total-rent-value';
      v.textContent = formatInrCurrency(n);
      box.appendChild(v);
    }

    if (chargeType === 'rent') {
      var dep = Number(depositAmount);
      if (!isFinite(dep)) dep = 0;
      if (dep > 0) {
        var depDiv = document.createElement('div');
        depDiv.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded border text-muted small';
        var depLabel = document.createElement('div');
        depLabel.innerHTML = '<strong>Deposit:</strong>';
        depDiv.appendChild(depLabel);
        var depVal = document.createElement('div');
        depVal.className = 'fw-bold text-primary lr-deposit-value';
        depVal.textContent = formatInrCurrency(dep);
        depDiv.appendChild(depVal);
        box.appendChild(depDiv);
      }

      if (Array.isArray(items) && items.length > 0) {
        var itemsSection = document.createElement('div');
        itemsSection.className = 'mt-3 pt-3 border-top';
        
        var toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn btn-outline-secondary btn-sm w-100 d-flex justify-content-between align-items-center mb-2';
        toggleBtn.style.fontWeight = 'bold';
        toggleBtn.style.fontSize = '0.78rem';
        toggleBtn.style.textTransform = 'uppercase';
        toggleBtn.style.letterSpacing = '0.5px';
        toggleBtn.style.padding = '8px 12px';
        toggleBtn.style.borderRadius = '8px';
        toggleBtn.style.border = '1.5px solid var(--border)';
        toggleBtn.style.background = '#fff';
        toggleBtn.style.color = 'var(--text-2)';
        toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-down"></i>';
        itemsSection.appendChild(toggleBtn);
        
        var itemsList = document.createElement('div');
        itemsList.className = 'd-flex flex-column';
        itemsList.style.gap = '10px';
        itemsList.style.display = 'none';
        itemsSection.appendChild(itemsList);

        var hasPreselected = false;
        items.forEach(function(item) {
          if (itemQty[item.id] && Number(itemQty[item.id]) > 0) {
            hasPreselected = true;
          }
        });
        if (hasPreselected) {
          itemsList.style.display = 'flex';
          toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-up"></i>';
        }

        toggleBtn.addEventListener('click', function() {
          if (itemsList.style.display === 'none') {
            itemsList.style.display = 'flex';
            toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-up"></i>';
          } else {
            itemsList.style.display = 'none';
            toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-down"></i>';
          }
        });
        
        items.forEach(function(item) {
          var itemRow = document.createElement('div');
          itemRow.className = 'd-flex align-items-center justify-content-between p-2 bg-light rounded border';
          
          var detailsDiv = document.createElement('div');
          var nameDiv = document.createElement('div');
          nameDiv.className = 'font-weight-bold text-dark';
          nameDiv.style.fontSize = '0.88rem';
          nameDiv.textContent = item.item_name;
          detailsDiv.appendChild(nameDiv);
          
          var priceDiv = document.createElement('div');
          priceDiv.className = 'text-muted small';
          priceDiv.textContent = formatInrCurrency(item.rent_sabeel) + ' / Piece';
          detailsDiv.appendChild(priceDiv);
          
          itemRow.appendChild(detailsDiv);
          
          var inputDiv = document.createElement('div');
          inputDiv.style.width = '80px';
          
          var qtyInput = document.createElement('input');
          qtyInput.type = 'number';
          qtyInput.name = 'item_qty[' + item.id + ']';
          qtyInput.className = 'form-control text-center py-1 px-2 rent-item-qty';
          qtyInput.setAttribute('data-price', String(item.rent_sabeel));
          qtyInput.min = '0';
          qtyInput.value = itemQty[item.id] || '0';
          qtyInput.style.height = 'auto';
          
          qtyInput.addEventListener('change', function() {
            if (Number(qtyInput.value) < 0) qtyInput.value = '0';
            updateLaagatRentCard();
          });
          qtyInput.addEventListener('input', function() {
            if (Number(qtyInput.value) < 0) qtyInput.value = '0';
            updateLaagatRentCard();
          });
          
          inputDiv.appendChild(qtyInput);
          itemRow.appendChild(inputDiv);
          itemsList.appendChild(itemRow);
        });
        
        box.appendChild(itemsSection);
      }
    } else {
      var jAmt = Number(jamaatAmount);
      var sAmt = Number(sarkaarAmount);
      if (!isFinite(jAmt)) jAmt = 0;
      if (!isFinite(sAmt)) sAmt = 0;
      if (jAmt === 0 && sAmt === 0 && n > 0) { jAmt = n; }
      var splitDiv = document.createElement('div');
      splitDiv.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded border text-muted small';
      var jmtPart = document.createElement('div');
      jmtPart.innerHTML = '<strong>Jmt. Laagat:</strong> ' + formatInrCurrency(jAmt);
      splitDiv.appendChild(jmtPart);
      var sarPart = document.createElement('div');
      sarPart.innerHTML = '<strong>Sar. Laagat:</strong> ' + formatInrCurrency(sAmt);
      splitDiv.appendChild(sarPart);
      box.appendChild(splitDiv);
    }

    var invoiceNote = document.createElement('div');
    invoiceNote.className = 'alert alert-warning mt-2 mb-0 small py-2';
    if (chargeType === 'laagat') {
      invoiceNote.innerHTML = '<i class="fa fa-info-circle mr-1"></i> <strong>Note:</strong> An invoice will be created for the member for Laagat.';
    } else {
      invoiceNote.innerHTML = '<i class="fa fa-info-circle mr-1"></i> <strong>Note:</strong> An invoice will be created for the member for Rent & Deposit.';
    }
    box.appendChild(invoiceNote);

    if (rawFieldName) {
      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = rawFieldName;
      hidden.value = String(n);
      box.appendChild(hidden);
    }
  }

  function normalizeFieldName(label) {
    return (label || '').toString().toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
  }

  function fetchLaagatRentAmount(razaTypeId, venueOptionId = '', thaalCount = 1, itemQty = {}) {
    let url = '<?= base_url('accounts/get_laagat_rent_amount') ?>' + '?raza_type_id=' + encodeURIComponent(razaTypeId);
    if (venueOptionId) { url += '&venue_option_id=' + encodeURIComponent(venueOptionId); }
    if (thaalCount) { url += '&thaal_count=' + encodeURIComponent(thaalCount); }
    for (let id in itemQty) {
      url += '&item_qty[' + encodeURIComponent(id) + ']=' + encodeURIComponent(itemQty[id]);
    }
    return fetch(url, { credentials: 'same-origin' }).then(function (r) { return r.json(); });
  }

  function updateLaagatRentCard() {
    let selectedRazaType = document.getElementById('raza-type').value;
    if (!selectedRazaType) return;
    let venueEl = document.querySelector('[name="venue"]');
    let venueOptionId = venueEl ? venueEl.value : '';
    let thaalCountInput = document.querySelector('[name="approximate-thaal-count"]') || 
                          document.querySelector('[name="approximate_thaal_count"]') ||
                          document.querySelector('[name="approximate-items-count"]') ||
                          document.querySelector('[name="approximate_items_count"]') ||
                          document.querySelector('[name="number-of-items"]') ||
                          document.querySelector('[name="number_of_items"]') ||
                          document.querySelector('[name="approximate-thaal-items-count"]') ||
                          document.querySelector('[name="approximate_thaal_items_count"]') ||
                          document.querySelector('[name="approximate-number-of-items"]') ||
                          document.querySelector('[name="approximate_number_of_items"]') ||
                          document.querySelector('[name="items-count"]') ||
                          document.querySelector('[name="items_count"]');
    let thaalCount = thaalCountInput ? (thaalCountInput.value || 1) : 1;

    let itemQty = {};
    document.querySelectorAll('.rent-item-qty').forEach(function(input) {
      let match = input.name.match(/item_qty\[(\d+)\]/);
      if (match) {
        itemQty[match[1]] = input.value || '0';
      }
    });

    fetchLaagatRentAmount(selectedRazaType, venueOptionId, thaalCount, itemQty)
      .then(function (resp) {
        let laagatRentInput = null;
        let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
        if (selectedRaza) {
          selectedRaza.fields.forEach(field => {
            if (isLaagatRentAmountField(field.name)) {
              let fieldName1 = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
              let fieldName2 = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
              laagatRentInput = document.querySelector(`[name="${fieldName1}"]`) || document.querySelector(`[name="${fieldName2}"]`);
            }
          });
        }
        let grp, rawName;
        if (laagatRentInput) {
          grp = closestFormGroup(laagatRentInput) || laagatRentInput.parentNode;
          rawName = laagatRentInput.getAttribute('name') || normalizeFieldName('Laagat /Rent Amount');
        } else {
          grp = document.getElementById('laagat-rent-card-group');
          if (!grp) { grp = document.createElement('div'); grp.className = 'form-group'; grp.id = 'laagat-rent-card-group'; }
          rawName = normalizeFieldName('Laagat /Rent Amount');
        }
        if (!resp || !resp.success || resp.amount === null || resp.amount === undefined) {
          if (grp) { grp.style.display = 'none'; while (grp.firstChild) grp.removeChild(grp.firstChild); }
          if (laagatRentInput) { laagatRentInput.value = '0'; }
          return;
        }
        if (grp) {
          grp.style.display = '';
          if (!laagatRentInput) {
            let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
            if (dynamicFieldsContainer && !dynamicFieldsContainer.contains(grp)) { dynamicFieldsContainer.appendChild(grp); }
          }
        }
        renderLaagatRentInfo(grp, rawName, resp.charge_type, resp.title || '', resp.amount, resp.jamaat_amount, resp.sarkaar_amount, resp.deposit_amount || 0, resp.items || [], itemQty);
      })
      .catch(function (err) { console.error('Error fetching rent amount:', err); });
  }

  function updateFormFields(existingData = null) {
    let selectedRazaType = document.getElementById('raza-type').value;
    let selectedRaza = razas.find(r => r.id == selectedRazaType);
    document.getElementById('lowerbutton').style.display = 'block';
    if (!selectedRaza) return;
    let data = existingData || window.razaPreFillData || {};
    let container = document.getElementById('dynamic-fields-container');
    container.innerHTML = '';

    if (selectedRazaType == 2) {
      $.ajax({
        url: "<?php echo base_url("umoor12/get_miqaat_by_its_id"); ?>",
        dataType: 'json',
        success: function (res) {
          let container = document.getElementById('dynamic-fields-container');
          if (Array.isArray(res) && res.length > 0) {
            let selectHtml = '<div class="form-group">';
            selectHtml += '<label for="miqaat-select" class="col-form-label requiredField">Miqaats Assigned To You<span class="asteriskField">*</span></label>';
            selectHtml += '<select id="miqaat-select" name="miqaat_id" class="form-control"><option value="">---------</option>';
            res.forEach((miqaat, idx) => {
              selectHtml += `<option value="${miqaat.miqaat_id}" ${Number(miqaat.miqaat_status) == 1 ? "" : "disabled"} data-miqaat-option="${idx}">${miqaat.miqaat_name}</option>`;
            });
            selectHtml += '</select></div><div id="miqaat-details"></div>';
            container.innerHTML = selectHtml;

            function showMiqaatDetails(index) {
              let miqaat = res[index];
              let detailsHtml = '<div class="card mt-2"><div class="card-body">';
              detailsHtml += `<p><strong>Name:</strong> ${miqaat.miqaat_name}</p>`;
              detailsHtml += `<p><strong>Type:</strong> ${miqaat.miqaat_type}</p>`;
              let formattedDate = '';
              if (miqaat.miqaat_date) {
                let d = new Date(miqaat.miqaat_date);
                if (!isNaN(d.getTime())) {
                  let day = String(d.getDate()).padStart(2, '0');
                  let month = String(d.getMonth() + 1).padStart(2, '0');
                  let year = d.getFullYear();
                  formattedDate = `${day}-${month}-${year}`;
                } else {
                  let parts = miqaat.miqaat_date.split('-');
                  formattedDate = parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : miqaat.miqaat_date;
                }
              }
              detailsHtml += `<p><strong>Date:</strong> ${formattedDate}</p>`;
              if (miqaat.assign_type === 'Group') {
                detailsHtml += `<p><strong>Assigned To:</strong> <span class="badge bg-primary text-white">Group</span></p>`;
                if (miqaat.group_name) detailsHtml += `<p><strong>Group Name:</strong> ${miqaat.group_name}</p>`;
                if (miqaat.group_leader_name) detailsHtml += `<p><strong>Group Leader:</strong> ${miqaat.group_leader_name} (${miqaat.group_leader_id})</p>`;
                if (miqaat.member_names.length > 0) {
                  detailsHtml += `<p><strong>Group Members:</strong></p>`;
                  detailsHtml += `<table class='table table-sm table-bordered mt-2'><thead><tr><th>Name</th><th>ITS ID</th></tr></thead><tbody>`;
                  miqaat.member_names.split(",").forEach(function (name, idx) {
                    let mid = (miqaat.member_ids && miqaat.member_ids.split(",")[idx]) ? miqaat.member_ids.split(",")[idx] : '';
                    detailsHtml += `<tr><td>${name}</td><td>${mid}</td></tr>`;
                  });
                  detailsHtml += `</tbody></table>`;
                }
              } else if (miqaat.assign_type === 'Individual') {
                detailsHtml += `<p><strong>Assigned To:</strong> <span class="badge bg-success">Individual</span></p>`;
                if (Array.isArray(miqaat.member_names) && miqaat.member_names.length > 0) {
                  let name = miqaat.member_names[0];
                  let mid = (miqaat.member_ids && miqaat.member_ids[0]) ? miqaat.member_ids[0] : '';
                  detailsHtml += `<p><strong>Member:</strong> ${name} (${mid})</p>`;
                }
              }
              detailsHtml += '</div></div>';
              detailsHtml += `<div id="niyaz-alert-container-${miqaat.miqaat_id}" class="mt-2"></div>`;
              document.getElementById('miqaat-details').innerHTML = detailsHtml;
              fetch(`<?= base_url('accounts/get_niyaz_amount_for_miqaat') ?>?miqaat_id=${miqaat.miqaat_id}`)
                .then(r => r.json())
                .then(data => {
                  if (data.success && data.amount > 0) {
                    let alertHtml = `<div class="alert alert-info py-2 mb-0"><strong>Note:</strong> An invoice of <strong>₹${data.amount}</strong> for <strong>${data.description}</strong> will be automatically generated upon submission.</div>`;
                    document.getElementById(`niyaz-alert-container-${miqaat.miqaat_id}`).innerHTML = alertHtml;
                  }
                }).catch(err => console.error("Failed to fetch Niyaz amount", err));
            }

            document.getElementById('miqaat-select').addEventListener('change', function () {
              let selectedOption = this.options[this.selectedIndex];
              let miqaatIndex = selectedOption.getAttribute('data-miqaat-option');
              showMiqaatDetails(miqaatIndex);
            });
          } else {
            container.innerHTML = '<div class="alert alert-info">No miqaat data found.</div>';
          }
        }
      });
    } else {
      let laagatRentInput = null;
      selectedRaza.fields.forEach(field => {
        let fieldName = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
        let fieldValue = data[fieldName] || '';
        let group = document.createElement('div');
        group.className = 'form-group';
        let label = document.createElement('label');
        label.className = 'col-form-label' + (field.required ? ' requiredField' : '');
        label.setAttribute('for', fieldName);
        label.innerHTML = field.name + (field.required ? '<span class="asteriskField">*</span>' : '');
        group.appendChild(label);
        let input;
        switch (field.type) {
          case 'text': case 'date': case 'number':
            input = document.createElement('input');
            input.type = field.type; input.name = fieldName;
            input.className = 'form-control'; input.value = fieldValue;
            if (field.required) input.required = true;
            break;
          case 'select':
            input = document.createElement('select');
            input.name = fieldName; input.id = fieldName;
            input.className = 'select2widget form-control';
            if (field.required) input.required = true;
            field.options.forEach(opt => {
              let o = document.createElement('option');
              o.value = opt.id; o.text = opt.name;
              if (opt.id == fieldValue) o.selected = true;
              input.appendChild(o);
            });
            break;
          case 'textarea':
            input = document.createElement('textarea');
            input.name = fieldName; input.className = 'form-control';
            input.value = fieldValue;
            if (field.required) input.required = true;
            break;
        }
        group.appendChild(input);
        container.appendChild(group);
        if (isLaagatRentAmountField(field.name)) { laagatRentInput = input; }
      });
      if (laagatRentInput && selectedRazaType) { laagatRentInput.readOnly = true; }
      updateLaagatRentCard();

      $('#dynamic-fields-container select.select2widget').select2({
        width: '100%',
        dropdownAutoWidth: false
      });
    }
  }

  document.addEventListener('change', function (e) {
    if (e.target && e.target.name === 'venue') { updateLaagatRentCard(); }
  });

  document.addEventListener('input', function (e) {
    if (e.target && (
      e.target.name === 'approximate-thaal-count' || e.target.name === 'approximate_thaal_count' ||
      e.target.name === 'approximate-items-count' || e.target.name === 'approximate_items_count' ||
      e.target.name === 'number-of-items' || e.target.name === 'number_of_items' ||
      e.target.name === 'approximate-thaal-items-count' || e.target.name === 'approximate_thaal_items_count' ||
      e.target.name === 'approximate-number-of-items' || e.target.name === 'approximate_number_of_items' ||
      e.target.name === 'items-count' || e.target.name === 'items_count'
    )) {
      updateLaagatRentCard();
    }
  });

  // Submit is only enabled once the confirmation checkbox is checked
  document.getElementById('exampleCheck1').addEventListener('change', function () {
    let submitBtn = document.getElementById('raza-submit-btn');
    if (submitBtn) submitBtn.disabled = !this.checked;
  });

  <?php if (!empty($razaId)): ?>
    window.addEventListener('DOMContentLoaded', function () {
      document.getElementById('raza-type').value = "<?= $razaId ?>";
      window.razaPreFillData = <?= json_encode($razaData ?? []) ?>;
      updateFormFields();
    });
  <?php endif; ?>
</script>

<script>
  function formatINR(n) {
    n = Math.round(Number(n) || 0);
    return '₹' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function coloredAmount(n) {
    var amt = formatINR(n);
    if (Number(n) > 0) return '<span style="color:var(--red); font-weight:700;">' + amt + '</span>';
    return '<span style="color:var(--green); font-weight:700;">' + amt + '</span>';
  }

  function showDuesModal(html) {
    document.getElementById('dues-content').innerHTML = html;
    document.getElementById('dues-modal').style.display = 'flex';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  function hideDuesModal() {
    document.getElementById('dues-modal').style.display = 'none';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'none';
    document.body.style.overflow = '';
  }

  document.getElementById('raza-form').addEventListener('submit', function (e) {
    e.preventDefault();
    var submitBtn = document.getElementById('raza-submit-btn');
    if (submitBtn) submitBtn.disabled = true;
    let miqaatIdParam = '';
    let miqaatSelect = document.getElementById('miqaat-select');
    if (miqaatSelect && miqaatSelect.value) {
      miqaatIdParam = '?miqaat_id=' + encodeURIComponent(miqaatSelect.value);
    }
    fetch('<?= base_url('accounts/get_member_dues') ?>' + miqaatIdParam, { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (submitBtn) submitBtn.disabled = false;
        if (!data || !data.success) { document.getElementById('raza-form').submit(); return; }
        var d = data.dues;
        var html = '<table class="table table-sm">' +
          '<tr><th>Category</th><th class="text-right">Due</th></tr>' +
          '<tr><td>FMB Takhmeen</td><td class="text-right">' + coloredAmount(d.fmb_due) + '</td></tr>' +
          '<tr><td>Sabeel Takhmeen</td><td class="text-right">' + coloredAmount(d.sabeel_due) + '</td></tr>' +
          '<tr><td>General Contributions</td><td class="text-right">' + coloredAmount(d.gc_due) + '</td></tr>' +
          '<tr><td>Miqaat Invoices</td><td class="text-right">' + coloredAmount(d.miqaat_due) + '</td></tr>' +
          '<tr><td>Corpus Fund</td><td class="text-right">' + coloredAmount(d.corpus_due) + '</td></tr>' +
          '<tr><td>Ekram Fund</td><td class="text-right">' + coloredAmount(d.ekram_due || 0) + '</td></tr>' +
          '<tr><td>Wajebaat</td><td class="text-right">' + coloredAmount(d.wajebaat_due || 0) + '</td></tr>' +
          '<tr><th>Total</th><th class="text-right">' + coloredAmount(d.total_due) + '</th></tr>' +
          '</table>';
        if (d.total_due <= 0) {
          html = '<div class="alert alert-success"><i class="fa fa-check-circle" style="margin-right:6px;"></i>No pending dues. You may proceed to submit.</div>' + html;
        } else {
          html = '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle" style="margin-right:6px;"></i>You have pending dues. Please review before submitting.</div>' + html;
        }
        if (data.expected_niyaz && data.expected_niyaz.amount > 0) {
          html += '<div class="alert alert-info mt-3 py-2 mb-0"><strong>Note:</strong> An invoice of <strong>₹' + data.expected_niyaz.amount + '</strong> for <strong>' + data.expected_niyaz.description + '</strong> will be automatically generated upon submission.</div>';
        }
        if (data.miqaat_invoices && Array.isArray(data.miqaat_invoices) && data.miqaat_invoices.length > 0) {
          var invHtml = '<hr style="border-color:var(--border);"><h6 style="font-size:0.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:var(--text-3);">Miqaat / Member Invoices</h6>' +
            '<table class="table table-sm table-bordered"><thead><tr><th>Assigned to</th><th>Invoice</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead><tbody>';
          data.miqaat_invoices.forEach(function (inv) {
            var owner = inv.owner_name || inv.user_id || '';
            var miqName = inv.miqaat_name || ('#' + inv.miqaat_id);
            invHtml += '<tr><td>' + owner + '</td><td>' + miqName + '</td><td class="text-right">' + formatINR(inv.amount || 0) + '</td><td class="text-right">' + formatINR(inv.paid_amount || 0) + '</td><td class="text-right">' + coloredAmount(inv.due_amount || 0) + '</td></tr>';
          });
          invHtml += '</tbody></table>';
          html += invHtml;
        }
        showDuesModal(html);
      })
      .catch(function (err) {
        if (submitBtn) submitBtn.disabled = false;
        document.getElementById('raza-form').submit();
      });
  });

  document.getElementById('dues-confirm').addEventListener('click', function () {
    var submitForm = function () { hideDuesModal(); document.getElementById('raza-form').submit(); };
    fetch('<?= base_url('accounts/send_dues_email') ?>', { method: 'POST', credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (resp) { submitForm(); })
      .catch(function () { submitForm(); });
  });
</script>