<?php /* Add Member View - redesigned */ ?>
<?php
if (!function_exists('norm_date_input')) {
  function norm_date_input($val)
  {
    if (empty($val))
      return '';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val))
      return $val;
    $t = strtotime($val);
    return $t ? date('Y-m-d', $t) : '';
  }
}
$m = $member ?? [];
function fval($m, $k)
{
  return isset($m[$k]) ? htmlspecialchars($m[$k]) : '';
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">
<style>
  *,
  *::before,
  *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  .am-wrap {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: #111827;
    max-width: 1140px;
    margin: 0 auto;
    padding: 100px 28px 120px;
  }

  /* Page header */
  .am-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 26px;
  }

  .am-page-title {
    font-size: 26px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.4px;
  }

  .am-page-title span {
    color: #4361ee;
  }

  .am-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    text-decoration: none;
    padding: 9px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
    background: #fff;
    transition: all .15s;
  }

  .am-back-btn:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #374151;
  }

  .am-back-btn i {
    font-size: 16px;
  }

  /* Mode tabs */
  .am-mode-switcher {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 18px;
  }

  .am-mode-tab {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 22px;
    cursor: pointer;
    border: 1.5px solid #e5e7eb;
    border-radius: 14px;
    background: #fff;
    text-align: left;
    transition: all .18s;
    position: relative;
    overflow: hidden;
  }

  .am-mode-tab::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: transparent;
    transition: background .18s;
  }

  .am-mode-tab:hover {
    background: #f9fafb;
  }

  /* Active — Temporary (amber) */
  .am-mode-tab.active-temp {
    border-color: #f59e0b;
    background: #fffbeb;
  }

  .am-mode-tab.active-temp::before {
    background: #f59e0b;
  }

  .am-mode-tab.active-temp .am-tab-icon {
    background: #fef3c7;
  }

  .am-mode-tab.active-temp .am-tab-title {
    color: #92400e;
  }

  .am-mode-tab.active-temp .am-tab-sub {
    color: #b45309;
  }

  .am-mode-tab.active-temp .am-tab-check {
    background: #f59e0b;
    color: #fff;
    opacity: 1;
  }

  /* Active — Permanent (blue) */
  .am-mode-tab.active-perm {
    border-color: #4361ee;
    background: #eef2ff;
  }

  .am-mode-tab.active-perm::before {
    background: #4361ee;
  }

  .am-mode-tab.active-perm .am-tab-icon {
    background: #dbeafe;
  }

  .am-mode-tab.active-perm .am-tab-title {
    color: #1e3a8a;
  }

  .am-mode-tab.active-perm .am-tab-sub {
    color: #3b82f6;
  }

  .am-mode-tab.active-perm .am-tab-check {
    background: #4361ee;
    color: #fff;
    opacity: 1;
  }

  .am-tab-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    background: #f3f4f6;
    transition: background .18s;
  }

  .am-tab-title {
    font-size: 15px;
    font-weight: 600;
    color: #9ca3af;
    margin: 0 0 4px;
    transition: color .18s;
  }

  .am-tab-sub {
    font-size: 12.5px;
    color: #d1d5db;
    margin: 0;
    transition: color .18s;
  }

  .am-tab-check {
    margin-left: auto;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
    opacity: 0;
    transition: all .18s;
    background: transparent;
  }

  /* Banner */
  .am-banner {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 13px 18px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 22px;
    line-height: 1.6;
  }

  .am-banner i {
    flex-shrink: 0;
    margin-top: 2px;
    font-size: 17px;
  }

  .am-banner-temp {
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #78350f;
  }

  .am-banner-perm {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e3a8a;
  }

  /* Form card */
  .am-form-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
  }

  /* Controls bar */
  .am-ctrl-bar {
    display: flex;
    gap: 8px;
    padding: 12px 22px;
    border-bottom: 1px solid #f3f4f6;
    background: #fafafa;
  }

  .am-ctrl-btn {
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    padding: 6px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 7px;
    background: #fff;
    cursor: pointer;
    transition: all .15s;
  }

  .am-ctrl-btn:hover {
    background: #f3f4f6;
    color: #374151;
  }

  /* Section groups */
  .am-group {
    border-bottom: 1px solid #f3f4f6;
  }

  .am-group:last-child {
    border-bottom: none;
  }

  .am-group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 22px;
    cursor: pointer;
    user-select: none;
    background: #fff;
    transition: background .15s;
  }

  .am-group-header:hover {
    background: #fafafa;
  }

  .am-group-left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .am-group-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
  }

  .am-group-title {
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .65px;
    color: #4b5563;
  }

  .am-temp-pill {
    font-size: 10.5px;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 20px;
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
    display: none;
    margin-left: 6px;
  }

  body.mode-temporary .am-temp-pill {
    display: inline;
  }

  .am-group-chevron {
    font-size: 16px;
    color: #9ca3af;
    transition: transform .2s;
  }

  .am-group-chevron.open {
    transform: rotate(180deg);
  }

  .am-group-body {
    padding: 22px 22px 24px;
    display: none;
  }

  .am-group-body.expanded {
    display: block;
  }

  /* Icon bg colors */
  .ico-identity {
    background: #eff6ff;
  }

  .ico-address {
    background: #f0fdf4;
  }

  .ico-family {
    background: #faf5ff;
  }

  .ico-marital {
    background: #fff7ed;
  }

  .ico-nikah {
    background: #fef2f2;
  }

  .ico-origin {
    background: #ecfdf5;
  }

  .ico-education {
    background: #f0f9ff;
  }

  .ico-occupation {
    background: #fefce8;
  }

  .ico-religious {
    background: #fdf4ff;
  }

  .ico-sector {
    background: #f0f9ff;
  }

  .ico-verification {
    background: #fff1f2;
  }

  /* Form fields */
  .am-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px 18px;
  }

  .am-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .am-field.wide {
    grid-column: 1/-1;
  }

  .am-label {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    letter-spacing: .1px;
  }

  .am-label .req {
    color: #ef4444;
    margin-left: 2px;
  }

  .am-field input,
  .am-field select {
    font-size: 14px;
    padding: 10px 13px;
    border: 1px solid #e5e7eb;
    border-radius: 9px;
    background: #fff;
    color: #111827;
    width: 100%;
    transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none;
    appearance: none;
  }

  .am-field input:focus,
  .am-field select:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, .1);
  }

  .am-field input::placeholder {
    color: #b8c0cc;
  }

  .am-field select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%239ca3af' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
  }

  .am-field select optgroup {
    font-weight: 700;
    font-size: 11.5px;
    letter-spacing: .3px;
    color: #6b7280;
    background: #f9fafb;
  }

  .am-field select option {
    font-size: 14px;
    color: #111827;
    font-weight: 400;
  }

  .am-err {
    font-size: 12px;
    color: #ef4444;
    margin-top: 3px;
    display: none;
  }

  /* Sticky bar */
  .am-sticky {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.96);
    border-top: 1px solid #e5e7eb;
    padding: 14px 28px;
    backdrop-filter: blur(6px);
  }

  .am-sticky-inner {
    max-width: 1140px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
  }

  .am-status {
    font-size: 14px;
    font-weight: 500;
    margin-right: auto;
  }

  .am-status.ok {
    color: #059669;
  }

  .am-status.err {
    color: #ef4444;
  }

  .am-btn-cancel {
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    background: #fff;
    border: 1px solid #e5e7eb;
    padding: 10px 22px;
    border-radius: 9px;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
  }

  .am-btn-cancel:hover {
    background: #f9fafb;
    border-color: #d1d5db;
  }

  .am-btn-submit {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background: #4361ee;
    border: none;
    padding: 10px 26px;
    border-radius: 9px;
    cursor: pointer;
    letter-spacing: .15px;
    transition: all .15s;
  }

  .am-btn-submit:hover {
    background: #3451d1;
  }

  .am-btn-submit:active {
    transform: scale(.98);
  }

  /* Hide in temp mode */
  body.mode-temporary .hide-in-temp {
    display: none !important;
  }

  @media(max-width:700px) {
    .am-mode-tab {
      padding: 14px 16px;
      gap: 11px;
    }

    .am-tab-icon {
      width: 40px;
      height: 40px;
      font-size: 19px;
    }

    .am-row {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media(max-width:440px) {
    .am-mode-switcher {
      grid-template-columns: 1fr;
    }

    .am-row {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="am-wrap">

  <div class="am-page-header">
    <h1 class="am-page-title">Add Member</h1>
    <a href="<?php echo base_url('admin/managemembers'); ?>" class="am-back-btn">
      <i class="ti ti-arrow-left"></i> Back to Members
    </a>
  </div>

  <!-- Mode switcher -->
  <div class="am-mode-switcher" role="tablist">
    <button class="am-mode-tab" id="tabTemp" role="tab" type="button" data-mode="temporary">
      <div class="am-tab-icon"><i class="ti ti-clock"></i></div>
      <div>
        <p class="am-tab-title">Temporary / Non-Sabeel</p>
        <p class="am-tab-sub">Visitors &middot; Non-Sabeel residents &middot; Quick entry</p>
      </div>
      <div class="am-tab-check"><i class="ti ti-check" style="font-size:12px;"></i></div>
    </button>
    <button class="am-mode-tab" id="tabPerm" role="tab" type="button" data-mode="permanent">
      <div class="am-tab-icon"><i class="ti ti-home"></i></div>
      <div>
        <p class="am-tab-title">Permanent Resident</p>
        <p class="am-tab-sub">Full member record with all sections</p>
      </div>
      <div class="am-tab-check"><i class="ti ti-check" style="font-size:12px;"></i></div>
    </button>
  </div>

  <div class="am-banner am-banner-temp" id="bannerTemp" style="display:none;">
    <i class="ti ti-bolt"></i>
    <div>Quick entry mode — only essential fields are shown. You can complete the full profile later by editing the
      member record.</div>
  </div>
  <div class="am-banner am-banner-perm" id="bannerPerm">
    <i class="ti ti-clipboard-list"></i>
    <div>Full member record — fill in all applicable sections. Sections can be expanded or collapsed individually.</div>
  </div>

  <input type="hidden" name="_entry_mode" id="entryModeInput" value="permanent">

  <form id="addMemberForm" method="post" action="<?php echo base_url('admin/savemember'); ?>">
    <div class="am-form-card">

      <div class="am-ctrl-bar hide-in-temp">
        <button type="button" id="expandAllGroups" class="am-ctrl-btn">+ Expand All</button>
        <button type="button" id="collapseAllGroups" class="am-ctrl-btn">&#8722; Collapse All</button>
      </div>

      <!-- SECTION: Identity & Contact -->
      <div class="am-group">
        <div class="am-group-header" data-target="body-identity">
          <div class="am-group-left">
            <div class="am-group-icon ico-identity"><i class="ti ti-user" style="color:#2563eb;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Identity &amp; Contact</span>
            <span class="am-temp-pill">shown in temp</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron open"></i>
        </div>
        <div class="am-group-body expanded" id="body-identity">
          <div class="am-row">
            <div class="am-field">
              <label class="am-label">ITS ID <span class="req">*</span></label>
              <input type="text" name="ITS_ID" value="<?php echo fval($m, 'ITS_ID'); ?>" required
                placeholder="e.g. 12345678">
            </div>
            <div class="am-field">
              <label class="am-label">Full Name <span class="req">*</span></label>
              <input type="text" name="Full_Name" value="<?php echo fval($m, 'Full_Name'); ?>" required
                placeholder="Full legal name">
            </div>
            <div class="am-field">
              <label class="am-label">Gender</label>
              <select name="Gender">
                <option value="">-- Select --</option>
                <option value="Male" <?php if (fval($m, 'Gender') === 'Male')
                  echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if (fval($m, 'Gender') === 'Female')
                  echo 'selected'; ?>>Female</option>
              </select>
            </div>
            <div class="am-field">
              <label class="am-label">Age</label>
              <input type="number" name="Age" value="<?php echo fval($m, 'Age'); ?>" placeholder="Years">
            </div>
            <div class="am-field">
              <label class="am-label">Mobile</label>
              <input type="text" name="Mobile" value="<?php echo fval($m, 'Mobile'); ?>" placeholder="Primary mobile">
            </div>
            <div class="am-field">
              <label class="am-label">Email</label>
              <input type="email" name="Email" value="<?php echo fval($m, 'Email'); ?>" placeholder="name@example.com">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Full Name (Arabic)</label>
              <input type="text" name="Full_Name_Arabic" value="<?php echo fval($m, 'Full_Name_Arabic'); ?>"
                placeholder="Arabic script">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">First Name</label>
              <input type="text" name="First_Name" value="<?php echo fval($m, 'First_Name'); ?>"
                placeholder="Given name">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Surname</label>
              <input type="text" name="Surname" value="<?php echo fval($m, 'Surname'); ?>" placeholder="Family name">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">First Prefix</label>
              <input type="text" name="First_Prefix" value="<?php echo fval($m, 'First_Prefix'); ?>"
                placeholder="e.g. Shk / Shz">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Prefix Year</label>
              <input type="text" name="Prefix_Year" value="<?php echo fval($m, 'Prefix_Year'); ?>"
                placeholder="Year granted">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Father Prefix</label>
              <input type="text" name="Father_Prefix" value="<?php echo fval($m, 'Father_Prefix'); ?>">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Father Name</label>
              <input type="text" name="Father_Name" value="<?php echo fval($m, 'Father_Name'); ?>"
                placeholder="Father first name">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Father Surname</label>
              <input type="text" name="Father_Surname" value="<?php echo fval($m, 'Father_Surname'); ?>">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Husband Prefix</label>
              <input type="text" name="Husband_Prefix" value="<?php echo fval($m, 'Husband_Prefix'); ?>">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Husband Name</label>
              <input type="text" name="Husband_Name" value="<?php echo fval($m, 'Husband_Name'); ?>">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">WhatsApp No</label>
              <input type="text" name="WhatsApp_No" value="<?php echo fval($m, 'WhatsApp_No'); ?>"
                placeholder="WhatsApp number">
            </div>
            <div class="am-field hide-in-temp">
              <label class="am-label">Registered Family Mobile</label>
              <input type="text" name="Registered_Family_Mobile"
                value="<?php echo fval($m, 'Registered_Family_Mobile'); ?>">
            </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION: Housing & Address -->
      <div class="am-group">
        <div class="am-group-header" data-target="body-address">
          <div class="am-group-left">
            <div class="am-group-icon ico-address"><i class="ti ti-map-pin" style="color:#16a34a;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Housing &amp; Address</span>
            <span class="am-temp-pill">shown in temp</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-address">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Address</label><input type="text" name="Address"
                value="<?php echo fval($m, 'Address'); ?>" placeholder="Flat / Street"></div>
            <div class="am-field"><label class="am-label">Building</label><input type="text" name="Building"
                value="<?php echo fval($m, 'Building'); ?>" placeholder="Building name"></div>
            <div class="am-field"><label class="am-label">Street</label><input type="text" name="Street"
                value="<?php echo fval($m, 'Street'); ?>" placeholder="Street"></div>
            <div class="am-field"><label class="am-label">Area</label><input type="text" name="Area"
                value="<?php echo fval($m, 'Area'); ?>" placeholder="Area / Locality"></div>
            <div class="am-field"><label class="am-label">City</label><input type="text" name="City"
                value="<?php echo fval($m, 'City'); ?>" placeholder="City"></div>
            <div class="am-field"><label class="am-label">State</label><input type="text" name="State"
                value="<?php echo fval($m, 'State'); ?>" placeholder="State"></div>
            <div class="am-field"><label class="am-label">Pincode</label><input type="text" name="Pincode"
                value="<?php echo fval($m, 'Pincode'); ?>" placeholder="Postal code"></div>
            <div class="am-field hide-in-temp"><label class="am-label">Housing / Society</label><input type="text"
                name="Housing" value="<?php echo fval($m, 'Housing'); ?>" placeholder="Society / Complex"></div>
            <div class="am-field hide-in-temp"><label class="am-label">Type of House</label><input type="text"
                name="Type_of_House" value="<?php echo fval($m, 'Type_of_House'); ?>" placeholder="Owned / Rented">
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION: Family & Relationships -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-family">
          <div class="am-group-left">
            <div class="am-group-icon ico-family"><i class="ti ti-users" style="color:#7c3aed;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Family &amp; Relationships</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-family">
          <div class="am-row">
            <div class="am-field">
              <label class="am-label">Type</label>
              <select name="hof_type" id="hofTypeSelect">
                <option value="HOF">Head of Family</option>
                <option value="FM">Family Member</option>
              </select>
            </div>
            <div class="am-field" id="hofSelectWrapper" style="display:none; position: relative;">
              <label class="am-label">Select Head of Family (Autocomplete)</label>
              <input type="text" id="hof_autocomplete" placeholder="Search HOF by ITS or Name..." autocomplete="off">
              <input type="hidden" name="HOF_ID" id="hof_id">
              <div id="hof_autocomplete_list" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1050; max-height: 250px; overflow-y: auto; display: none; top: 100%;"></div>
              <div class="small text-muted mt-1">Type ITS ID or Member Name. Only members with ITS out of Khar, or both ITS and Sabeel out of Khar will be shown.</div>
            </div>
            <div class="am-field"><label class="am-label">Father ITS ID</label><input type="text" name="Father_ITS_ID"
                value="<?php echo fval($m, 'Father_ITS_ID'); ?>" placeholder="Father ITS (if known)"></div>
            <div class="am-field"><label class="am-label">Mother ITS ID</label><input type="text" name="Mother_ITS_ID"
                value="<?php echo fval($m, 'Mother_ITS_ID'); ?>" placeholder="Mother ITS (if known)"></div>
            <div class="am-field"><label class="am-label">Spouse ITS ID</label><input type="text" name="Spouse_ITS_ID"
                value="<?php echo fval($m, 'Spouse_ITS_ID'); ?>" placeholder="Spouse ITS (if married)"></div>
            <div class="am-field"><label class="am-label">Family ID</label><input type="text" name="Family_ID"
                value="<?php echo fval($m, 'Family_ID'); ?>" placeholder="Internal family ref"></div>
            <div class="am-field"><label class="am-label">Tanzeem File No</label><input type="text"
                name="TanzeemFile_No" value="<?php echo fval($m, 'TanzeemFile_No'); ?>" placeholder="Tanzeem file no">
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION: Marital & Personal Status -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-marital">
          <div class="am-group-left">
            <div class="am-group-icon ico-marital"><i class="ti ti-ring" style="color:#ea580c;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Marital &amp; Personal Status</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-marital">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Misaq</label><input type="text" name="Misaq"
                value="<?php echo fval($m, 'Misaq'); ?>" placeholder="Yes / Year"></div>
            <div class="am-field">
              <label class="am-label">Marital Status</label>
              <select name="Marital_Status">
                <option value="">-- Select --</option>
                <?php foreach (['Single', 'Married', 'Engaged', 'Separated', 'Divorced', 'Widowed'] as $ms): ?>
                  <option value="<?php echo $ms; ?>" <?php if (fval($m, 'Marital_Status') === $ms)
                       echo 'selected'; ?>>
                    <?php echo $ms; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="am-field">
              <label class="am-label">Blood Group</label>
              <select name="Blood_Group">
                <option value="">-- Select --</option>
                <?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown'] as $bg): ?>
                  <option value="<?php echo $bg; ?>" <?php if (fval($m, 'Blood_Group') === $bg)
                       echo 'selected'; ?>>
                    <?php echo $bg; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="am-field"><label class="am-label">Warakatul Tarkhis</label><input type="text"
                name="Warakatul_Tarkhis" value="<?php echo fval($m, 'Warakatul_Tarkhis'); ?>"
                placeholder="Number / Year"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Nikah & Religious Dates -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-nikah">
          <div class="am-group-left">
            <div class="am-group-icon ico-nikah"><i class="ti ti-calendar-event"
                style="color:#dc2626;font-size:16px;"></i></div>
            <span class="am-group-title">Nikah &amp; Religious Dates</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-nikah">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Date of Nikah</label><input type="date" name="Date_Of_Nikah"
                value="<?php echo norm_date_input(fval($m, 'Date_Of_Nikah')); ?>"></div>
            <div class="am-field"><label class="am-label">Date of Nikah (Hijri)</label><input type="text"
                name="Date_Of_Nikah_Hijri" value="<?php echo fval($m, 'Date_Of_Nikah_Hijri'); ?>"
                placeholder="Hijri date"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Origin & Community -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-origin">
          <div class="am-group-left">
            <div class="am-group-icon ico-origin"><i class="ti ti-world" style="color:#059669;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Origin &amp; Community</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-origin">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Organisation</label><input type="text" name="Organisation"
                value="<?php echo fval($m, 'Organisation'); ?>" placeholder="Employer / Institute"></div>
            <div class="am-field"><label class="am-label">Organisation CSV</label><input type="text"
                name="Organisation_CSV" value="<?php echo fval($m, 'Organisation_CSV'); ?>"
                placeholder="Import ref (if any)"></div>
            <div class="am-field"><label class="am-label">Vatan</label><input type="text" name="Vatan"
                value="<?php echo fval($m, 'Vatan'); ?>" placeholder="Native place"></div>
            <div class="am-field"><label class="am-label">Nationality</label><input type="text" name="Nationality"
                value="<?php echo fval($m, 'Nationality'); ?>" placeholder="Country"></div>
            <div class="am-field"><label class="am-label">Jamaat</label><input type="text" name="Jamaat"
                value="<?php echo fval($m, 'Jamaat'); ?>" placeholder="Home Jamaat"></div>
            <div class="am-field"><label class="am-label">Jamiaat</label><input type="text" name="Jamiaat"
                value="<?php echo fval($m, 'Jamiaat'); ?>" placeholder="Jamia (if any)"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Education & Skills -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-education">
          <div class="am-group-left">
            <div class="am-group-icon ico-education"><i class="ti ti-school" style="color:#0284c7;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Education &amp; Skills</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-education">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Qualification</label><input type="text" name="Qualification"
                value="<?php echo fval($m, 'Qualification'); ?>" placeholder="Highest degree"></div>
            <div class="am-field"><label class="am-label">Languages</label><input type="text" name="Languages"
                value="<?php echo fval($m, 'Languages'); ?>" placeholder="Comma separated"></div>
            <div class="am-field"><label class="am-label">Hunars</label><input type="text" name="Hunars"
                value="<?php echo fval($m, 'Hunars'); ?>" placeholder="Skills / Talents"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Occupation -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-occupation">
          <div class="am-group-left">
            <div class="am-group-icon ico-occupation"><i class="ti ti-briefcase"
                style="color:#ca8a04;font-size:16px;"></i></div>
            <span class="am-group-title">Occupation</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-occupation">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Occupation</label><input type="text" name="Occupation"
                value="<?php echo fval($m, 'Occupation'); ?>" placeholder="Primary occupation"></div>
            <div class="am-field"><label class="am-label">Sub Occupation</label><input type="text" name="Sub_Occupation"
                value="<?php echo fval($m, 'Sub_Occupation'); ?>" placeholder="Secondary"></div>
            <div class="am-field"><label class="am-label">Sub Occupation 2</label><input type="text"
                name="Sub_Occupation2" value="<?php echo fval($m, 'Sub_Occupation2'); ?>" placeholder="Additional">
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION: Religious Milestones & Ziyarat -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-religious">
          <div class="am-group-left">
            <div class="am-group-icon ico-religious"><i class="ti ti-star" style="color:#9333ea;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Religious Milestones &amp; Ziyarat</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-religious">
          <div class="am-row">
            <div class="am-field"><label class="am-label">Quran Sanad</label><input type="text" name="Quran_Sanad"
                value="<?php echo fval($m, 'Quran_Sanad'); ?>" placeholder="Yes / Year"></div>
            <div class="am-field"><label class="am-label">Qadambosi Sharaf</label><input type="text"
                name="Qadambosi_Sharaf" value="<?php echo fval($m, 'Qadambosi_Sharaf'); ?>" placeholder="Yes / Year">
            </div>
            <div class="am-field"><label class="am-label">Raudat Tahera Ziyarat</label><input type="text"
                name="Raudat_Tahera_Ziyarat" value="<?php echo fval($m, 'Raudat_Tahera_Ziyarat'); ?>"
                placeholder="Yes / Year"></div>
            <div class="am-field"><label class="am-label">Karbala Ziyarat</label><input type="text"
                name="Karbala_Ziyarat" value="<?php echo fval($m, 'Karbala_Ziyarat'); ?>" placeholder="Yes / Year">
            </div>
            <div class="am-field"><label class="am-label">Ashara Mubaraka</label><input type="text"
                name="Ashara_Mubaraka" value="<?php echo fval($m, 'Ashara_Mubaraka'); ?>"
                placeholder="Yes / City / Year"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Sector Hierarchy -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-sector">
          <div class="am-group-left">
            <div class="am-group-icon ico-sector"><i class="ti ti-folder" style="color:#0284c7;font-size:16px;"></i>
            </div>
            <span class="am-group-title">Sector Hierarchy</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-sector">
          <div class="am-row">
            <div class="am-field">
              <label class="am-label">Sector</label>
              <select name="Sector" id="sectorSelect">
                <option value="">-- Select Sector --</option>
                <?php if (!empty($sector_list))
                  foreach ($sector_list as $sec): ?>
                    <option value="<?php echo htmlspecialchars($sec); ?>" <?php if (fval($m, 'Sector') === htmlspecialchars($sec))
                         echo 'selected'; ?>>
                      <?php echo htmlspecialchars($sec); ?>
                    </option>
                  <?php endforeach; ?>
              </select>
            </div>
            <div class="am-field">
              <label class="am-label">Sub Sector</label>
              <select name="Sub_Sector" id="subSectorSelect" disabled>
                <option value="">-- Select Sub Sector --</option>
              </select>
            </div>
            <div class="am-field"><label class="am-label">Sector Incharge ITS ID</label><input type="text"
                name="Sector_Incharge_ITSID" value="<?php echo fval($m, 'Sector_Incharge_ITSID'); ?>"></div>
            <div class="am-field"><label class="am-label">Sector Incharge Name</label><input type="text"
                name="Sector_Incharge_Name" value="<?php echo fval($m, 'Sector_Incharge_Name'); ?>"></div>
            <div class="am-field"><label class="am-label">Sector Incharge Female ITS ID</label><input type="text"
                name="Sector_Incharge_Female_ITSID" value="<?php echo fval($m, 'Sector_Incharge_Female_ITSID'); ?>">
            </div>
            <div class="am-field"><label class="am-label">Sector Incharge Female Name</label><input type="text"
                name="Sector_Incharge_Female_Name" value="<?php echo fval($m, 'Sector_Incharge_Female_Name'); ?>"></div>
            <div class="am-field"><label class="am-label">Sub Sector Incharge ITS ID</label><input type="text"
                name="Sub_Sector_Incharge_ITSID" value="<?php echo fval($m, 'Sub_Sector_Incharge_ITSID'); ?>"></div>
            <div class="am-field"><label class="am-label">Sub Sector Incharge Name</label><input type="text"
                name="Sub_Sector_Incharge_Name" value="<?php echo fval($m, 'Sub_Sector_Incharge_Name'); ?>"></div>
            <div class="am-field"><label class="am-label">Sub Sector Incharge Female ITS ID</label><input type="text"
                name="Sub_Sector_Incharge_Female_ITSID"
                value="<?php echo fval($m, 'Sub_Sector_Incharge_Female_ITSID'); ?>"></div>
            <div class="am-field"><label class="am-label">Sub Sector Incharge Female Name</label><input type="text"
                name="Sub_Sector_Incharge_Female_Name"
                value="<?php echo fval($m, 'Sub_Sector_Incharge_Female_Name'); ?>"></div>
          </div>
        </div>
      </div>

      <!-- SECTION: Verification & Scan -->
      <div class="am-group hide-in-temp">
        <div class="am-group-header" data-target="body-verification">
          <div class="am-group-left">
            <div class="am-group-icon ico-verification"><i class="ti ti-shield-check"
                style="color:#dc2626;font-size:16px;"></i></div>
            <span class="am-group-title">Verification &amp; Scan</span>
          </div>
          <i class="ti ti-chevron-down am-group-chevron"></i>
        </div>
        <div class="am-group-body" id="body-verification">
          <div class="am-row">
            <div class="am-field">
              <label class="am-label">Data Verification Status</label>
              <select class="ver-status" data-date-target="dataVerificationDate" name="Data_Verifcation_Status">
                <option value="">--</option>
                <option <?php if (fval($m, 'Data_Verifcation_Status') === 'Verified')
                  echo 'selected'; ?>>Verified
                </option>
                <option <?php if (fval($m, 'Data_Verifcation_Status') === 'Pending')
                  echo 'selected'; ?>>Pending</option>
                <option <?php if (fval($m, 'Data_Verifcation_Status') === 'Not Verified')
                  echo 'selected'; ?>>Not Verified
                </option>
              </select>
            </div>
            <div class="am-field"><label class="am-label">Data Verification Date</label><input id="dataVerificationDate"
                type="date" name="Data_Verification_Date"
                value="<?php echo norm_date_input(fval($m, 'Data_Verification_Date')); ?>"></div>
            <div class="am-field">
              <label class="am-label">Photo Verification Status</label>
              <select class="ver-status" data-date-target="photoVerificationDate" name="Photo_Verifcation_Status">
                <option value="">--</option>
                <option <?php if (fval($m, 'Photo_Verifcation_Status') === 'Verified')
                  echo 'selected'; ?>>Verified
                </option>
                <option <?php if (fval($m, 'Photo_Verifcation_Status') === 'Pending')
                  echo 'selected'; ?>>Pending</option>
                <option <?php if (fval($m, 'Photo_Verifcation_Status') === 'Not Verified')
                  echo 'selected'; ?>>Not
                  Verified
                </option>
              </select>
            </div>
            <div class="am-field"><label class="am-label">Photo Verification Date</label><input
                id="photoVerificationDate" type="date" name="Photo_Verification_Date"
                value="<?php echo norm_date_input(fval($m, 'Photo_Verification_Date')); ?>"></div>
            <div class="am-field"><label class="am-label">Last Scanned Event</label><input type="text"
                name="Last_Scanned_Event" value="<?php echo fval($m, 'Last_Scanned_Event'); ?>"></div>
            <div class="am-field"><label class="am-label">Last Scanned Place</label><input type="text"
                name="Last_Scanned_Place" value="<?php echo fval($m, 'Last_Scanned_Place'); ?>"></div>
            <div class="am-field"><label class="am-label">Title</label><input type="text" name="Title"
                value="<?php echo fval($m, 'Title'); ?>"></div>
            <div class="am-field"><label class="am-label">Category</label><input type="text" name="Category"
                value="<?php echo fval($m, 'Category'); ?>"></div>
            <div class="am-field"><label class="am-label">Idara</label><input type="text" name="Idara"
                value="<?php echo fval($m, 'Idara'); ?>"></div>
            <div class="am-field">
              <label class="am-label">Inactive Status</label>
              <select name="Inactive_Status">
                <option value="">Active</option>
                <?php foreach (['Deceased', 'Shifted Jamaat', 'Travel / Outstation', 'Duplicate Record', 'Blocked / Suspended', 'Other'] as $is): ?>
                  <option value="<?php echo $is; ?>" <?php if (fval($m, 'Inactive_Status') === $is)
                       echo 'selected'; ?>>
                    <?php echo $is; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /am-form-card -->

    <!-- Sticky submit bar -->
    <div class="am-sticky">
      <div class="am-sticky-inner">
        <span id="addMemberStatus" class="am-status"></span>
        <a href="<?php echo base_url('admin/managemembers'); ?>" class="am-btn-cancel">Cancel</a>
        <button type="submit" class="am-btn-submit">Save Member</button>
      </div>
    </div>

  </form>
</div>

<script>
  (function () {
    var currentMode = 'permanent';
    var tabTemp = document.getElementById('tabTemp');
    var tabPerm = document.getElementById('tabPerm');

    function setMode(mode) {

      currentMode = mode;

      document.getElementById('entryModeInput').value = mode;

      document.body.classList.toggle(
        'mode-temporary',
        mode === 'temporary'
      );

      tabTemp.className =
        'am-mode-tab' +
        (mode === 'temporary' ? ' active-temp' : '');

      tabPerm.className =
        'am-mode-tab' +
        (mode === 'permanent' ? ' active-perm' : '');

      document.getElementById('bannerTemp').style.display =
        mode === 'temporary' ? '' : 'none';

      document.getElementById('bannerPerm').style.display =
        mode === 'permanent' ? '' : 'none';

      if (mode === 'temporary') {

        var ab = document.getElementById('body-address');

        if (ab) {

          ab.classList.add('expanded');

          var ch = ab.previousElementSibling.querySelector('.am-group-chevron');

          if (ch) {
            ch.classList.add('open');
          }
        }
      }
    }

    tabTemp.addEventListener('click', function () { setMode('temporary'); });
    tabPerm.addEventListener('click', function () { setMode('permanent'); });
    setMode('permanent');

    /* Sector / Sub-sector */
    var sectorMap = <?php echo json_encode($sector_map ?? []); ?>;
    var sectorSel = document.getElementById('sectorSelect');
    var subSel = document.getElementById('subSectorSelect');
    if (sectorSel) {
      sectorSel.addEventListener('change', function () {
        subSel.innerHTML = '<option value="">-- Select Sub Sector --</option>';
        subSel.disabled = true;
        if (this.value && sectorMap[this.value]) {
          sectorMap[this.value].forEach(function (ss) {
            var o = document.createElement('option');
            o.value = ss; o.textContent = ss;
            subSel.appendChild(o);
          });
          subSel.disabled = false;
        }
      });
    }

    /* Verification date auto-fill */
    function today() { return new Date().toISOString().slice(0, 10); }
    document.querySelectorAll('.ver-status').forEach(function (s) {
      s.addEventListener('change', function () {
        var d = document.getElementById(this.getAttribute('data-date-target'));
        if (!d) return;
        if (this.value === 'Verified') { if (!d.value) d.value = today(); }
        else if (!this.value || this.value === 'Not Verified') { d.value = ''; }
      });
    });

    /* HOF / FM toggle */
    var hofSel = document.getElementById('hofTypeSelect');
    var hofWrap = document.getElementById('hofSelectWrapper');
    if (hofSel) {
      hofSel.addEventListener('change', function () {
        hofWrap.style.display = this.value === 'FM' ? '' : 'none';
      });
    }

    /* Section accordion */
    document.querySelectorAll('.am-group-header').forEach(function (h) {
      h.addEventListener('click', function () {
        var body = document.getElementById(h.getAttribute('data-target'));
        var chev = h.querySelector('.am-group-chevron');
        if (!body) return;
        var open = body.classList.toggle('expanded');
        if (chev) chev.classList.toggle('open', open);
      });
    });

    /* Expand / Collapse all */
    function setAll(expand) {
      document.querySelectorAll('.am-group').forEach(function (g) {
        if (g.classList.contains('hide-in-temp') && currentMode === 'temporary') return;
        var body = g.querySelector('.am-group-body');
        var chev = g.querySelector('.am-group-chevron');
        if (!body) return;
        body.classList.toggle('expanded', expand);
        if (chev) chev.classList.toggle('open', expand);
      });
    }
    var expBtn = document.getElementById('expandAllGroups');
    var colBtn = document.getElementById('collapseAllGroups');
    if (expBtn) expBtn.addEventListener('click', function () { setAll(true); });
    if (colBtn) colBtn.addEventListener('click', function () { setAll(false); });

    /* Form submit */
    var form = document.getElementById('addMemberForm');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var fd = new FormData(form);
      if (currentMode === 'temporary') {
        fd.set('HOF_FM_TYPE', 'HOF');
        fd.set('HOF_ID', fd.get('ITS_ID'));
      } else {
        var ht = fd.get('hof_type') || 'HOF';
        if (ht === 'HOF') {
          fd.set('HOF_FM_TYPE', 'HOF');
          fd.set('HOF_ID', fd.get('ITS_ID'));
        } else {
          fd.set('HOF_FM_TYPE', 'FM');
        }
      }
      var st = document.getElementById('addMemberStatus');
      st.textContent = 'Saving...';
      st.className = 'am-status';
      fetch(form.action, { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          if (json.status === 'success') {
            st.textContent = '✓ Member saved successfully';
            st.className = 'am-status ok';
            setTimeout(function () { window.location.href = '<?php echo base_url('admin/managemembers'); ?>'; }, 900);
          } else {
            st.textContent = '✗ ' + (json.message || 'Error saving member');
            st.className = 'am-status err';
          }
        })
        .catch(function () {
          var st = document.getElementById('addMemberStatus');
          st.textContent = '✗ Network error';
          st.className = 'am-status err';
        });
    });

    // HOF Autocomplete
    var hofInput = document.getElementById('hof_autocomplete');
    var hofIdInput = document.getElementById('hof_id');
    var hofList = document.getElementById('hof_autocomplete_list');

    if (hofInput && hofList) {
      var debounceTimeout = null;

      hofInput.addEventListener('input', function () {
        var val = this.value.trim();
        
        // If cleared completely, reset hidden input
        if (val === '') {
          hofIdInput.value = '';
          hofList.style.display = 'none';
          hofList.innerHTML = '';
          return;
        }

        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function () {
          fetch('<?php echo base_url("admin/search_hofs_autocomplete"); ?>?q=' + encodeURIComponent(val))
            .then(function (r) { return r.json(); })
            .then(function (data) {
              hofList.innerHTML = '';
              if (data && data.length > 0) {
                data.forEach(function (item) {
                  var btn = document.createElement('button');
                  btn.type = 'button';
                  btn.className = 'list-group-item list-group-item-action py-2 px-3';
                  btn.style.cursor = 'pointer';
                  btn.innerHTML = '<strong>' + escapeHtml(item.Full_Name) + '</strong> (' + escapeHtml(item.ITS_ID) + ')';
                  btn.addEventListener('click', function () {
                    hofIdInput.value = item.ITS_ID;
                    hofInput.value = item.Full_Name + ' (' + item.ITS_ID + ')';
                    hofList.style.display = 'none';
                    hofList.innerHTML = '';
                  });
                  hofList.appendChild(btn);
                });
                hofList.style.display = 'block';
              } else {
                var div = document.createElement('div');
                div.className = 'list-group-item text-muted py-2 px-3';
                div.textContent = 'No members found';
                hofList.appendChild(div);
                hofList.style.display = 'block';
              }
            })
            .catch(function () {
              hofList.innerHTML = '';
              var div = document.createElement('div');
              div.className = 'list-group-item text-danger py-2 px-3';
              div.textContent = 'Failed to load results';
              hofList.appendChild(div);
              hofList.style.display = 'block';
            });
        }, 300);
      });

      // Close autocomplete list if clicked outside
      document.addEventListener('click', function (e) {
        if (e.target !== hofInput && e.target !== hofList && !hofList.contains(e.target)) {
          hofList.style.display = 'none';
        }
      });

      // Helper to escape HTML to prevent XSS in dynamic list
      function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;')
                  .replace(/'/g, '&#039;');
      }
    }

  })();
</script>