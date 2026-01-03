<?php /* Expanded Edit Member View with Full Field Set - grouped 3-column layout + verification automation */ ?>
<?php
if (!function_exists('norm_date_input')) {
  function norm_date_input($val)
  {
    if (empty($val)) return '';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) return $val; // already ISO
    $t = strtotime($val);
    return $t ? date('Y-m-d', $t) : '';
  }
}
?>
<div class="container margintopcontainer pt-5 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Member</h4>
    <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
  </div>
  <?php if (!empty($member['member_type'])): ?>
    <div class="mb-3">
      <span class="badge bg-info text-dark">Member Type: <?php echo htmlspecialchars($member['member_type']); ?></span>
    </div>
  <?php endif; ?>
  <?php if (!empty($member)): ?>
    <form id="editMemberForm" class="card shadow-sm border-0" method="post" action="<?php echo base_url('admin/updatemember'); ?>">
      <div class="card-body">
        <style>
          .group-header {
            cursor: pointer;
            user-select: none;
          }

          .group-body.collapsed {
            display: none;
          }

          .group-section+.group-section {
            margin-top: 1rem;
          }

          .toggle-indicator {
            font-weight: bold;
            width: 1rem;
            text-align: center;
          }
        </style>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <button type="button" id="expandAllGroups" class="btn btn-sm btn-outline-primary">Expand All</button>
          <button type="button" id="collapseAllGroups" class="ml-2 btn btn-sm btn-outline-secondary">Collapse All</button>
        </div>

        <!-- Identity & Contact -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-identity">
            <span class="small fw-semibold text-uppercase">Identity & Contact</span><span class="toggle-indicator">−</span>
          </div>
          <div id="group-identity" class="group-body border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">ITS ID</label>
                <input type="text" class="form-control form-control-sm" value="<?php echo htmlspecialchars($member['ITS_ID']); ?>" readonly name="its_id">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Full Name</label>
                <input type="text" class="form-control form-control-sm" name="Full_Name" value="<?php echo htmlspecialchars($member['Full_Name']); ?>" required>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Full Name Arabic</label>
                <input type="text" class="form-control form-control-sm" name="Full_Name_Arabic" value="<?php echo htmlspecialchars($member['Full_Name_Arabic'] ?? ''); ?>" placeholder="Arabic script name">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">First Name</label>
                <input type="text" class="form-control form-control-sm" name="First_Name" placeholder="Given name" value="<?php echo htmlspecialchars($member['First_Name'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Surname</label>
                <input type="text" class="form-control form-control-sm" name="Surname" placeholder="Family name" value="<?php echo htmlspecialchars($member['Surname'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">First Prefix</label>
                <input type="text" class="form-control form-control-sm" name="First_Prefix" value="<?php echo htmlspecialchars($member['First_Prefix'] ?? ''); ?>" placeholder="e.g. Shk / Shz">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Prefix Year</label>
                <input type="text" class="form-control form-control-sm" name="Prefix_Year" value="<?php echo htmlspecialchars($member['Prefix_Year'] ?? ''); ?>" placeholder="Year granted">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Father Prefix</label>
                <input type="text" class="form-control form-control-sm" name="Father_Prefix" value="<?php echo htmlspecialchars($member['Father_Prefix'] ?? ''); ?>" placeholder="Father prefix">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Father Name</label>
                <input type="text" class="form-control form-control-sm" name="Father_Name" value="<?php echo htmlspecialchars($member['Father_Name'] ?? ''); ?>" placeholder="Father first name">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Father Surname</label>
                <input type="text" class="form-control form-control-sm" name="Father_Surname" value="<?php echo htmlspecialchars($member['Father_Surname'] ?? ''); ?>" placeholder="Father surname">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Husband Prefix</label>
                <input type="text" class="form-control form-control-sm" name="Husband_Prefix" value="<?php echo htmlspecialchars($member['Husband_Prefix'] ?? ''); ?>" placeholder="Husband prefix">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Husband Name</label>
                <input type="text" class="form-control form-control-sm" name="Husband_Name" value="<?php echo htmlspecialchars($member['Husband_Name'] ?? ''); ?>" placeholder="Husband name">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Gender</label>
                <?php $gSel = $member['Gender'] ?? ''; ?>
                <select class="form-control form-select" name="Gender">
                  <option value="">--</option>
                  <option value="Male" <?php echo $gSel === 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo $gSel === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Age</label>
                <input type="number" class="form-control form-control-sm" name="Age" placeholder="Years" value="<?php echo htmlspecialchars($member['Age'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Mobile</label>
                <input type="text" class="form-control form-control-sm" name="Mobile" placeholder="Primary mobile" value="<?php echo htmlspecialchars($member['Mobile'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Email</label>
                <input type="email" class="form-control form-control-sm" name="Email" placeholder="name@example.com" value="<?php echo htmlspecialchars($member['Email'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">WhatsApp No</label>
                <input type="text" class="form-control form-control-sm" name="WhatsApp_No" placeholder="WhatsApp number" value="<?php echo htmlspecialchars($member['WhatsApp_No'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Registered Family Mobile</label>
                <input type="text" class="form-control form-control-sm" name="Registered_Family_Mobile" placeholder="Shared family mobile" value="<?php echo htmlspecialchars($member['Registered_Family_Mobile'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Member Type</label>
                <?php $mt = $member['Member_Type'] ?? ''; ?>
                <select name="Member_Type" id="editMemberTypeSelect" class="form-control form-select form-select-sm">
                  <option value="">-- Select Member Type --</option>
                  <option value="Resident Mumineen" <?php echo $mt == 'Resident Mumineen' ? 'selected' : ''; ?>>Resident Mumineen – Living in Khar, ITS in Khar, regular Sabeel payer</option>
                  <option value="External Sabeel Payers" <?php echo $mt == 'External Sabeel Payers' ? 'selected' : ''; ?>>External Sabeel Payers – ITS not in Khar, but resident & regular Sabeel payer</option>
                  <option value="Moved-Out Mumineen" <?php echo $mt == 'Moved-Out Mumineen' ? 'selected' : ''; ?>>Moved-Out Mumineen – ITS in Khar but no longer residing</option>
                  <option value="Non-Sabeel Residents" <?php echo $mt == 'Non-Sabeel Residents' ? 'selected' : ''; ?>>Non-Sabeel Residents – Living in Khar, ITS not in Khar, not a Sabeel payer</option>
                  <option value="Temporary Mumineen/Visitors" <?php echo $mt == 'Temporary Mumineen/Visitors' ? 'selected' : ''; ?>>Temporary Mumineen/Visitors – Temporary presence for events</option>
                </select>
                <div id="editMemberTypeError" class="text-danger small mt-1" style="display:none;"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Family & Relationships -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-family">
            <span class="small fw-semibold text-uppercase">Family & Relationships</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-family" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1 d-block">Type</label>
                <select name="hof_type" id="hofTypeSelect" class="form-control form-select">
                  <option value="HOF" <?php echo ($member['HOF_FM_TYPE'] === 'HOF') ? 'selected' : ''; ?>>Head of Family</option>
                  <option value="FM" <?php echo ($member['HOF_FM_TYPE'] !== 'HOF') ? 'selected' : ''; ?>>Family Member</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2" id="hofSelectWrapper" style="<?php echo ($member['HOF_FM_TYPE'] === 'HOF') ? 'display:none;' : ''; ?>">
                <label class="form-label small mb-1">Select HOF (Family members)</label>
                <select name="HOF_ID" class="form-control form-select">
                  <option value="">-- Choose family member as HOF --</option>
                  <?php foreach ($hof_list as $h):
                    $sel = (isset($member['HOF_ID']) && $member['HOF_ID'] == $h['ITS_ID']) ? 'selected' : '';
                    $note = (isset($h['HOF_FM_TYPE']) && $h['HOF_FM_TYPE'] === 'HOF') ? ' (HOF)' : '';
                  ?>
                    <option value="<?php echo htmlspecialchars($h['ITS_ID']); ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($h['Full_Name']) . ' (' . $h['ITS_ID'] . ')' . $note; ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="small text-muted mt-1">Listing family members only. If none found, a full HOF list is shown.</div>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">HOF FM Type</label>
                <input type="text" class="form-control form-control-sm" name="HOF_FM_TYPE" value="<?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?>" readonly>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Father ITS ID</label>
                <input type="text" class="form-control form-control-sm" name="Father_ITS_ID" placeholder="Father ITS" value="<?php echo htmlspecialchars($member['Father_ITS_ID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Mother ITS ID</label>
                <input type="text" class="form-control form-control-sm" name="Mother_ITS_ID" placeholder="Mother ITS" value="<?php echo htmlspecialchars($member['Mother_ITS_ID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Spouse ITS ID</label>
                <input type="text" class="form-control form-control-sm" name="Spouse_ITS_ID" placeholder="Spouse ITS" value="<?php echo htmlspecialchars($member['Spouse_ITS_ID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Family ID</label>
                <input type="text" class="form-control form-control-sm" name="Family_ID" placeholder="Internal family ref" value="<?php echo htmlspecialchars($member['Family_ID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Tanzeem File No</label>
                <input type="text" class="form-control form-control-sm" name="TanzeemFile_No" placeholder="Tanzeem file no" value="<?php echo htmlspecialchars($member['TanzeemFile_No'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Marital & Personal Status -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-marital">
            <span class="small fw-semibold text-uppercase">Marital & Personal Status</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-marital" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-3 col-12">
                <label class="form-label small mb-1">Misaq</label>
                <input type="text" class="form-control form-control-sm" name="Misaq" placeholder="Yes / Year" value="<?php echo htmlspecialchars($member['Misaq'] ?? ''); ?>">
              </div>
              <div class="col-md-3 col-12">
                <label class="form-label small mb-1">Marital Status</label>
                <?php $maritalVal = $member['Marital_Status'] ?? '';
                $maritalOptions = ["Single", "Married", "Engaged", "Separated", "Divorced", "Widowed"]; ?>
                <select name="Marital_Status" class="form-control form-select form-select-sm">
                  <option value="">-- Select --</option>
                  <?php foreach ($maritalOptions as $opt): ?>
                    <option value="<?php echo $opt; ?>" <?php echo ($opt === $maritalVal) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                  <?php endforeach; ?>
                  <?php if ($maritalVal && !in_array($maritalVal, $maritalOptions)): ?>
                    <option value="<?php echo htmlspecialchars($maritalVal); ?>" selected><?php echo htmlspecialchars($maritalVal); ?> (Legacy)</option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-md-3 col-12">
                <label class="form-label small mb-1">Blood Group</label>
                <?php $bloodVal = $member['Blood_Group'] ?? '';
                $bloodOptions = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-", "Unknown"]; ?>
                <select name="Blood_Group" class="form-control form-select form-select-sm">
                  <option value="">-- Select --</option>
                  <?php foreach ($bloodOptions as $opt): ?>
                    <option value="<?php echo $opt; ?>" <?php echo ($opt === $bloodVal) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                  <?php endforeach; ?>
                  <?php if ($bloodVal && !in_array($bloodVal, $bloodOptions)): ?>
                    <option value="<?php echo htmlspecialchars($bloodVal); ?>" selected><?php echo htmlspecialchars($bloodVal); ?> (Legacy)</option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-md-3 col-12">
                <label class="form-label small mb-1">Warakatul Tarkhis</label>
                <input type="text" class="form-control form-control-sm" name="Warakatul_Tarkhis" placeholder="Number / Year" value="<?php echo htmlspecialchars($member['Warakatul_Tarkhis'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Nikah & Religious Dates -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-nikah">
            <span class="small fw-semibold text-uppercase">Nikah & Religious Dates</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-nikah" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Date Of Nikah</label>
                <input type="date" class="form-control form-control-sm" name="Date_Of_Nikah" value="<?php echo norm_date_input($member['Date_Of_Nikah'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Date Of Nikah Hijri</label>
                <input type="text" class="form-control form-control-sm" name="Date_Of_Nikah_Hijri" placeholder="" value="<?php echo htmlspecialchars($member['Date_Of_Nikah_Hijri'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Origin & Community -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-origin">
            <span class="small fw-semibold text-uppercase">Origin & Community</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-origin" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Organisation</label>
                <input type="text" class="form-control form-control-sm" name="Organisation" placeholder="Employer / Institute" value="<?php echo htmlspecialchars($member['Organisation'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Organisation CSV</label>
                <input type="text" class="form-control form-control-sm" name="Organisation_CSV" placeholder="Import ref" value="<?php echo htmlspecialchars($member['Organisation_CSV'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Vatan</label>
                <input type="text" class="form-control form-control-sm" name="Vatan" placeholder="Native place" value="<?php echo htmlspecialchars($member['Vatan'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Nationality</label>
                <input type="text" class="form-control form-control-sm" name="Nationality" placeholder="Country" value="<?php echo htmlspecialchars($member['Nationality'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Jamaat</label>
                <input type="text" class="form-control form-control-sm" name="Jamaat" placeholder="Home Jamaat" value="<?php echo htmlspecialchars($member['Jamaat'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Jamiaat</label>
                <input type="text" class="form-control form-control-sm" name="Jamiaat" placeholder="Jamia (if any)" value="<?php echo htmlspecialchars($member['Jamiaat'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Education & Skills -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-education">
            <span class="small fw-semibold text-uppercase">Education & Skills</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-education" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Qualification</label>
                <input type="text" class="form-control form-control-sm" name="Qualification" placeholder="Highest degree" value="<?php echo htmlspecialchars($member['Qualification'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Languages</label>
                <input type="text" class="form-control form-control-sm" name="Languages" placeholder="Comma separated" value="<?php echo htmlspecialchars($member['Languages'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Hunars</label>
                <input type="text" class="form-control form-control-sm" name="Hunars" placeholder="Skills / Talents" value="<?php echo htmlspecialchars($member['Hunars'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Occupation -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-occupation">
            <span class="small fw-semibold text-uppercase">Occupation</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-occupation" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Occupation</label>
                <input type="text" class="form-control form-control-sm" name="Occupation" placeholder="Primary occupation" value="<?php echo htmlspecialchars($member['Occupation'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Occupation</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Occupation" placeholder="Secondary" value="<?php echo htmlspecialchars($member['Sub_Occupation'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Occupation2</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Occupation2" placeholder="Additional" value="<?php echo htmlspecialchars($member['Sub_Occupation2'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Religious Milestones & Ziyarat -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-religious">
            <span class="small fw-semibold text-uppercase">Religious Milestones & Ziyarat</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-religious" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Quran Sanad</label>
                <input type="text" class="form-control form-control-sm" name="Quran_Sanad" placeholder="Yes / Year" value="<?php echo htmlspecialchars($member['Quran_Sanad'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Qadambosi Sharaf</label>
                <input type="text" class="form-control form-control-sm" name="Qadambosi_Sharaf" placeholder="Yes / Year" value="<?php echo htmlspecialchars($member['Qadambosi_Sharaf'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Raudat Tahera Ziyarat</label>
                <input type="text" class="form-control form-control-sm" name="Raudat_Tahera_Ziyarat" placeholder="Yes / Year" value="<?php echo htmlspecialchars($member['Raudat_Tahera_Ziyarat'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Karbala Ziyarat</label>
                <input type="text" class="form-control form-control-sm" name="Karbala_Ziyarat" placeholder="Yes / Year" value="<?php echo htmlspecialchars($member['Karbala_Ziyarat'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Ashara Mubaraka</label>
                <input type="text" class="form-control form-control-sm" name="Ashara_Mubaraka" placeholder="Yes / City / Year" value="<?php echo htmlspecialchars($member['Ashara_Mubaraka'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Housing & Address -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-housing">
            <span class="small fw-semibold text-uppercase">Housing & Address</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-housing" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Housing</label>
                <input type="text" class="form-control form-control-sm" name="Housing" placeholder="Building / Society" value="<?php echo htmlspecialchars($member['Housing'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Type of House</label>
                <input type="text" class="form-control form-control-sm" name="Type_of_House" placeholder="Owned / Rented" value="<?php echo htmlspecialchars($member['Type_of_House'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Address</label>
                <input type="text" class="form-control form-control-sm" name="Address" placeholder="Flat / Street" value="<?php echo htmlspecialchars($member['Address'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Building</label>
                <input type="text" class="form-control form-control-sm" name="Building" value="<?php echo htmlspecialchars($member['Building'] ?? ''); ?>" placeholder="Building name">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Street</label>
                <input type="text" class="form-control form-control-sm" name="Street" value="<?php echo htmlspecialchars($member['Street'] ?? ''); ?>" placeholder="Street">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Area</label>
                <input type="text" class="form-control form-control-sm" name="Area" value="<?php echo htmlspecialchars($member['Area'] ?? ''); ?>" placeholder="Area">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">State</label>
                <input type="text" class="form-control form-control-sm" name="State" placeholder="State" value="<?php echo htmlspecialchars($member['State'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">City</label>
                <input type="text" class="form-control form-control-sm" name="City" placeholder="City" value="<?php echo htmlspecialchars($member['City'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Pincode</label>
                <input type="text" class="form-control form-control-sm" name="Pincode" placeholder="Postal code" value="<?php echo htmlspecialchars($member['Pincode'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Sector Hierarchy -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-sector">
            <span class="small fw-semibold text-uppercase">Sector Hierarchy</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-sector" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sector</label>
                <?php $currentSector = $member['Sector'] ?? ''; ?>
                <select class="form-control form-select form-select-sm" name="Sector" id="sectorSelectEdit">
                  <option value="">-- Select Sector --</option>
                  <?php if (!empty($sector_list)) foreach ($sector_list as $sec): ?>
                    <option value="<?php echo htmlspecialchars($sec); ?>" <?php echo ($sec === $currentSector) ? 'selected' : ''; ?>><?php echo htmlspecialchars($sec); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Sector</label>
                <?php $currentSub = $member['Sub_Sector'] ?? ''; ?>
                <select class="form-control form-select form-select-sm" name="Sub_Sector" id="subSectorSelectEdit" disabled>
                  <option value="">-- Select Sub Sector --</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sector Incharge ITSID</label>
                <input type="text" class="form-control form-control-sm" name="Sector_Incharge_ITSID" value="<?php echo htmlspecialchars($member['Sector_Incharge_ITSID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sector Incharge Name</label>
                <input type="text" class="form-control form-control-sm" name="Sector_Incharge_Name" value="<?php echo htmlspecialchars($member['Sector_Incharge_Name'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sector Incharge Female ITSID</label>
                <input type="text" class="form-control form-control-sm" name="Sector_Incharge_Female_ITSID" value="<?php echo htmlspecialchars($member['Sector_Incharge_Female_ITSID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sector Incharge Female Name</label>
                <input type="text" class="form-control form-control-sm" name="Sector_Incharge_Female_Name" value="<?php echo htmlspecialchars($member['Sector_Incharge_Female_Name'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Sector Incharge ITSID</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_ITSID" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_ITSID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Sector Incharge Name</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Name" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Name'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Sector Incharge Female ITSID</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Female_ITSID" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Female_ITSID'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Sub Sector Incharge Female Name</label>
                <input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Female_Name" value="<?php echo htmlspecialchars($member['Sub_Sector_Incharge_Female_Name'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Verification & Scan -->
        <div class="group-section">
          <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-verification">
            <span class="small fw-semibold text-uppercase">Verification & Scan</span><span class="toggle-indicator">+</span>
          </div>
          <div id="group-verification" class="group-body collapsed border-start border-end border-bottom p-3">
            <div class="row g-3">
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Data Verification Status</label>
                <?php $dvs = $member['Data_Verifcation_Status'] ?? ''; ?>
                <select class="form-control form-select ver-status" data-date-target="dataVerificationDate" name="Data_Verifcation_Status">
                  <option value="">--</option>
                  <option value="Verified" <?php echo $dvs === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                  <option value="Pending" <?php echo $dvs === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                  <option value="Not Verified" <?php echo $dvs === 'Not Verified' ? 'selected' : ''; ?>>Not Verified</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Photo Verification Status</label>
                <?php $pvs = $member['Photo_Verifcation_Status'] ?? ''; ?>
                <select class="form-control form-select ver-status" data-date-target="photoVerificationDate" name="Photo_Verifcation_Status">
                  <option value="">--</option>
                  <option value="Verified" <?php echo $pvs === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                  <option value="Pending" <?php echo $pvs === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                  <option value="Not Verified" <?php echo $pvs === 'Not Verified' ? 'selected' : ''; ?>>Not Verified</option>
                </select>
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Data Verification Date</label>
                <input id="dataVerificationDate" type="date" class="form-control form-control-sm" name="Data_Verification_Date" value="<?php echo norm_date_input($member['Data_Verification_Date'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Photo Verification Date</label>
                <input id="photoVerificationDate" type="date" class="form-control form-control-sm" name="Photo_Verification_Date" value="<?php echo norm_date_input($member['Photo_Verification_Date'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Last Scanned Event</label>
                <input type="text" class="form-control form-control-sm" name="Last_Scanned_Event" value="<?php echo htmlspecialchars($member['Last_Scanned_Event'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Last Scanned Place</label>
                <input type="text" class="form-control form-control-sm" name="Last_Scanned_Place" value="<?php echo htmlspecialchars($member['Last_Scanned_Place'] ?? ''); ?>">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Title</label>
                <input type="text" class="form-control form-control-sm" name="Title" value="<?php echo htmlspecialchars($member['Title'] ?? ''); ?>" placeholder="Title">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Category</label>
                <input type="text" class="form-control form-control-sm" name="Category" value="<?php echo htmlspecialchars($member['Category'] ?? ''); ?>" placeholder="Category">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Idara</label>
                <input type="text" class="form-control form-control-sm" name="Idara" value="<?php echo htmlspecialchars($member['Idara'] ?? ''); ?>" placeholder="Idara">
              </div>
              <div class="col-md-4 col-12 mb-2">
                <label class="form-label small mb-1">Inactive Status</label>
                <?php $inactiveVal = $member['Inactive_Status'] ?? '';
                $inactiveOptions = ["Deceased", "Shifted Jamaat", "Travel / Outstation", "Duplicate Record", "Blocked / Suspended", "Other"]; ?>
                <select name="Inactive_Status" id="inactiveStatusEdit" class="form-control form-select form-select-sm">
                  <option value="" <?php echo $inactiveVal === '' ? 'selected' : ''; ?>>Active</option>
                  <?php foreach ($inactiveOptions as $opt): ?>
                    <option value="<?php echo $opt; ?>" <?php echo ($opt === $inactiveVal) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                  <?php endforeach; ?>
                  <?php if ($inactiveVal && !in_array($inactiveVal, $inactiveOptions)): ?>
                    <option value="<?php echo htmlspecialchars($inactiveVal); ?>" selected><?php echo htmlspecialchars($inactiveVal); ?> (Legacy)</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-sticky-spacer" style="height:90px"></div>
        <div class="form-sticky-bar">
          <div class="inner d-flex justify-content-center align-items-center gap-2">
            <button type="submit" class="btn btn-primary btn-sm mr-2">Save Changes</button>
            <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
            <span id="editMemberStatus" class="small ms-2"></span>
          </div>
        </div>
        <style>
          .form-sticky-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1050;
            padding: 8px 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(3px);
            border-top: 1px solid #dee2e6;
          }

          .form-sticky-bar .inner {
            max-width: 880px;
            margin: 0 auto;
            padding: 4px 12px;
          }

          @media (max-width: 600px) {
            .form-sticky-bar .inner {
              max-width: 100%;
            }
          }
        </style>
      </div>
    </form>
    <script>
      (function() {
        var typeSel = document.getElementById('hofTypeSelect');
        var hofWrap = document.getElementById('hofSelectWrapper');
        if (typeSel) {
          typeSel.addEventListener('change', function() {
            if (this.value === 'HOF') {
              hofWrap && (hofWrap.style.display = 'none');
            } else {
              hofWrap && (hofWrap.style.display = 'block');
            }
          });
        }

        function today() {
          var d = new Date();
          return d.toISOString().slice(0, 10);
        }
        document.querySelectorAll('.ver-status').forEach(function(sel) {
          sel.addEventListener('change', function() {
            var targetId = this.getAttribute('data-date-target');
            var dateInput = document.getElementById(targetId);
            if (!dateInput) return;
            if (this.value === 'Verified') {
              if (!dateInput.value) dateInput.value = today();
            } else if (this.value === '' || this.value === 'Not Verified') {
              dateInput.value = '';
            }
          });
        });
        // Group toggle logic
        var headers = document.querySelectorAll('.group-header');
        headers.forEach(function(h) {
          h.addEventListener('click', function(e) {
            // ignore if clicking a nested interactive control (none expected here)
            var targetId = h.getAttribute('data-group-target');
            var body = document.getElementById(targetId);
            if (!body) return;
            var indicator = h.querySelector('.toggle-indicator');
            var collapsed = body.classList.toggle('collapsed');
            if (indicator) {
              indicator.textContent = collapsed ? '+' : '−';
            }
          });
        });
        var expandAllBtn = document.getElementById('expandAllGroups');
        var collapseAllBtn = document.getElementById('collapseAllGroups');

        function setAll(expand) {
          document.querySelectorAll('.group-body').forEach(function(b) {
            var header = document.querySelector('.group-header[data-group-target="' + b.id + '"]');
            var indicator = header ? header.querySelector('.toggle-indicator') : null;
            if (expand) {
              b.classList.remove('collapsed');
              if (indicator) indicator.textContent = '−';
            } else {
              b.classList.add('collapsed');
              if (indicator) indicator.textContent = '+';
            }
          });
        }
        if (expandAllBtn) {
          expandAllBtn.addEventListener('click', function() {
            setAll(true);
          });
        }
        if (collapseAllBtn) {
          collapseAllBtn.addEventListener('click', function() {
            setAll(false);
          });
        }
        var form = document.getElementById('editMemberForm');
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          var fd = new FormData(form);
          // Leave Inactive_Status empty (controller maps empty to NULL)
          var hofType = fd.get('hof_type');
          if (hofType === 'HOF') {
            fd.set('HOF_FM_TYPE', 'HOF');
            fd.set('HOF_ID', fd.get('its_id'));
          } else {
            fd.set('HOF_FM_TYPE', 'FM');
            if (!fd.get('HOF_ID')) {
              alert('Please select a HOF for this family member');
              return;
            }
          }
          var mtSel = document.getElementById('editMemberTypeSelect');
          var mtErr = document.getElementById('editMemberTypeError');
          if (mtErr) mtErr.style.display = 'none';
          console.log('Submitting edit member_type:', mtSel ? mtSel.value : undefined);
          fetch(form.action, {
              method: 'POST',
              body: fd
            })
            .then(r => r.json())
            .then(json => {
              var statusEl = document.getElementById('editMemberStatus');
              if (json.status === 'success') {
                statusEl.textContent = 'Saved';
                statusEl.className = 'small text-success';
                setTimeout(() => {
                  window.location.href = '<?php echo base_url('admin/managemembers'); ?>';
                }, 600);
              } else {
                statusEl.textContent = json.message || 'Update failed';
                statusEl.className = 'small text-danger';
                if (json.field === 'member_type' && mtErr) {
                  mtErr.textContent = 'Invalid member type. Allowed: ' + (json.allowed_values ? json.allowed_values.join(', ') : '');
                  mtErr.style.display = 'block';
                }
              }
            })
            .catch(() => {
              var statusEl = document.getElementById('editMemberStatus');
              statusEl.textContent = 'Network error';
              statusEl.className = 'small text-danger';
            });
        });

        // Sector/Sub-Sector dependent dropdown (Edit)
        var sectorMapEdit = <?php echo json_encode($sector_map ?? []); ?>;
        var sectorSelectEdit = document.getElementById('sectorSelectEdit');
        var subSectorSelectEdit = document.getElementById('subSectorSelectEdit');
        var preSector = '<?php echo addslashes($member['Sector'] ?? ''); ?>';
        var preSub = '<?php echo addslashes($member['Sub_Sector'] ?? ''); ?>';

        function populateSubEdit(sec) {
          subSectorSelectEdit.innerHTML = '<option value="">-- Select Sub Sector --</option>';
          subSectorSelectEdit.disabled = true;
          if (sec && sectorMapEdit[sec] && sectorMapEdit[sec].length) {
            var frag = document.createDocumentFragment();
            sectorMapEdit[sec].forEach(function(ss) {
              var opt = document.createElement('option');
              opt.value = ss;
              opt.textContent = ss;
              if (ss === preSub) opt.selected = true;
              frag.appendChild(opt);
            });
            subSectorSelectEdit.appendChild(frag);
            subSectorSelectEdit.disabled = false;
          }
        }
        if (sectorSelectEdit) {
          sectorSelectEdit.addEventListener('change', function() {
            preSub = '';
            populateSubEdit(this.value);
          });
          // initial population
          if (preSector) {
            populateSubEdit(preSector);
          }
        }
      })();
    </script>
  <?php else: ?>
    <div class="alert alert-warning">Member not found.</div>
  <?php endif; ?>
</div>