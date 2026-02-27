<?php /* Add Member View - mirrors EditMember with empty defaults */ ?>
<?php
 if(!function_exists('norm_date_input')){
   function norm_date_input($val){ if(empty($val)) return ''; if(preg_match('/^\d{4}-\d{2}-\d{2}$/',$val)) return $val; $t=strtotime($val); return $t?date('Y-m-d',$t):''; }
 }
 $m = $member ?? [];
 function fval($m,$k){ return isset($m[$k]) ? htmlspecialchars($m[$k]) : ''; }
?>
<div class="container margintopcontainer pt-5 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Add Member</h4>
    <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-sm btn-outline-secondary">Back</a>
  </div>
  <form id="addMemberForm" class="card shadow-sm border-0" method="post" action="<?php echo base_url('admin/savemember'); ?>">
    <div class="card-body">
      <style>
        .group-header{cursor:pointer;user-select:none;}
        .group-body.collapsed{display:none;}
        .group-section + .group-section{margin-top:1rem;}
        .toggle-indicator{font-weight:bold;width:1rem;text-align:center;}
      </style>
      <div class="d-flex flex-wrap gap-2 mb-3">
        <button type="button" id="expandAllGroups" class="btn btn-sm btn-outline-primary">Expand All</button>
        <button type="button" id="collapseAllGroups" class="ml-2 btn btn-sm btn-outline-secondary">Collapse All</button>
      </div>
      <!-- Identity & Contact (expanded) -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-identity"><span class="small fw-semibold text-uppercase">Identity & Contact</span><span class="toggle-indicator">−</span></div>
        <div id="group-identity" class="group-body border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">ITS ID *</label>
              <input type="text" class="form-control form-control-sm" name="ITS_ID" required placeholder="e.g. 12345678">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Full Name *</label>
              <input type="text" class="form-control form-control-sm" name="Full_Name" required placeholder="Full legal name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Full Name Arabic</label>
              <input type="text" class="form-control form-control-sm" name="Full_Name_Arabic" placeholder="Arabic script name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">First Name</label>
              <input type="text" class="form-control form-control-sm" name="First_Name" placeholder="Given name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Surname</label>
              <input type="text" class="form-control form-control-sm" name="Surname" placeholder="Family name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">First Prefix</label>
              <input type="text" class="form-control form-control-sm" name="First_Prefix" placeholder="e.g. Shk / Shz">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Prefix Year</label>
              <input type="text" class="form-control form-control-sm" name="Prefix_Year" placeholder="Year granted">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Father Prefix</label>
              <input type="text" class="form-control form-control-sm" name="Father_Prefix" placeholder="Father prefix">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Father Name</label>
              <input type="text" class="form-control form-control-sm" name="Father_Name" placeholder="Father first name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Father Surname</label>
              <input type="text" class="form-control form-control-sm" name="Father_Surname" placeholder="Father surname">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Husband Prefix</label>
              <input type="text" class="form-control form-control-sm" name="Husband_Prefix" placeholder="Husband prefix">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Husband Name</label>
              <input type="text" class="form-control form-control-sm" name="Husband_Name" placeholder="Husband name">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Gender</label>
              <select class="form-control form-select form-select-sm" name="Gender"><option value="">--</option><option value="Male">Male</option><option value="Female">Female</option></select>
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Age</label>
              <input type="number" class="form-control form-control-sm" name="Age" placeholder="Years">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Mobile</label>
              <input type="text" class="form-control form-control-sm" name="Mobile" placeholder="Primary mobile">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Email</label>
              <input type="email" class="form-control form-control-sm" name="Email" placeholder="name@example.com">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">WhatsApp No</label>
              <input type="text" class="form-control form-control-sm" name="WhatsApp_No" placeholder="WhatsApp number">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Registered Family Mobile</label>
              <input type="text" class="form-control form-control-sm" name="Registered_Family_Mobile" placeholder="Shared family mobile">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Member Type</label>
              <?php $jp = htmlspecialchars(jamaat_place(), ENT_QUOTES, 'UTF-8'); ?>
              <select name="Member_Type" id="memberTypeSelect" class="form-control form-select form-select-sm">
                <option value="">-- Select Member Type --</option>
                <option value="Resident Mumineen">Resident Mumineen – Living in <?php echo $jp; ?>, ITS in <?php echo $jp; ?>, regular Sabeel payer</option>
                <option value="External Sabeel Payers">External Sabeel Payers – ITS not in <?php echo $jp; ?>, but resident & regular Sabeel payer</option>
                <option value="Moved-Out Mumineen">Moved-Out Mumineen – ITS in <?php echo $jp; ?> but no longer residing</option>
                <option value="Non-Sabeel Residents">Non-Sabeel Residents – Living in <?php echo $jp; ?>, ITS not in <?php echo $jp; ?>, not a Sabeel payer</option>
                <option value="Temporary Mumineen/Visitors">Temporary Mumineen/Visitors – Temporary presence for events</option>
              </select>
              <div id="memberTypeError" class="text-danger small mt-1" style="display:none;"></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Family & Relationships (collapsed) -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-family"><span class="small fw-semibold text-uppercase">Family & Relationships</span><span class="toggle-indicator">+</span></div>
        <div id="group-family" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Type</label>
              <select name="hof_type" id="hofTypeSelect" class="form-control form-select form-select-sm">
                <option value="HOF">Head of Family</option>
                <option value="FM">Family Member</option>
              </select>
            </div>
            <div class="col-md-4 col-12 mt-2" id="hofSelectWrapper" style="display:none;">
              <label class="form-label small mb-1">Select HOF</label>
              <select name="HOF_ID" class="form-control form-select form-select-sm">
                <option value="">-- Choose HOF --</option>
                <?php foreach($hof_list as $h): ?>
                  <option value="<?php echo htmlspecialchars($h['ITS_ID']); ?>"><?php echo htmlspecialchars($h['Full_Name']).' ('.$h['ITS_ID'].')'; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Father ITS ID</label>
              <input type="text" class="form-control form-control-sm" name="Father_ITS_ID" placeholder="Father ITS (if known)">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Mother ITS ID</label>
              <input type="text" class="form-control form-control-sm" name="Mother_ITS_ID" placeholder="Mother ITS (if known)">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Spouse ITS ID</label>
              <input type="text" class="form-control form-control-sm" name="Spouse_ITS_ID" placeholder="Spouse ITS (if married)">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Family ID</label>
              <input type="text" class="form-control form-control-sm" name="Family_ID" placeholder="Internal family ref">
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Tanzeem File No</label>
              <input type="text" class="form-control form-control-sm" name="TanzeemFile_No" placeholder="Tanzeem file no">
            </div>
          </div>
        </div>
      </div>
      <!-- Marital & Personal Status -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-marital"><span class="small fw-semibold text-uppercase">Marital & Personal Status</span><span class="toggle-indicator">+</span></div>
        <div id="group-marital" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-3 col-12"><label class="form-label small mb-1">Misaq</label><input type="text" class="form-control form-control-sm" name="Misaq" placeholder="Yes / Year"></div>
            <div class="col-md-3 col-12">
              <label class="form-label small mb-1">Marital Status</label>
              <select name="Marital_Status" class="form-control form-select form-select-sm">
                <option value="">-- Select --</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Engaged">Engaged</option>
                <option value="Separated">Separated</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
              </select>
            </div>
            <div class="col-md-3 col-12">
              <label class="form-label small mb-1">Blood Group</label>
              <select name="Blood_Group" class="form-control form-select form-select-sm">
                <option value="">-- Select --</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="Unknown">Unknown</option>
              </select>
            </div>
            <div class="col-md-3 col-12"><label class="form-label small mb-1">Warakatul Tarkhis</label><input type="text" class="form-control form-control-sm" name="Warakatul_Tarkhis" placeholder="Number / Year"></div>
          </div>
        </div>
      </div>
      <!-- Nikah & Religious Dates -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-nikah"><span class="small fw-semibold text-uppercase">Nikah & Religious Dates</span><span class="toggle-indicator">+</span></div>
        <div id="group-nikah" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Date Of Nikah</label><input type="date" class="form-control form-control-sm" name="Date_Of_Nikah"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Date Of Nikah Hijri</label><input type="text" class="form-control form-control-sm" name="Date_Of_Nikah_Hijri" placeholder=""></div>
          </div>
        </div>
      </div>
      <!-- Origin & Community -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-origin"><span class="small fw-semibold text-uppercase">Origin & Community</span><span class="toggle-indicator">+</span></div>
        <div id="group-origin" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Organisation</label><input type="text" class="form-control form-control-sm" name="Organisation" placeholder="Employer / Institute"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Organisation CSV</label><input type="text" class="form-control form-control-sm" name="Organisation_CSV" placeholder="Import ref (if any)"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Vatan</label><input type="text" class="form-control form-control-sm" name="Vatan" placeholder="Native place"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Nationality</label><input type="text" class="form-control form-control-sm" name="Nationality" placeholder="Country"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Jamaat</label><input type="text" class="form-control form-control-sm" name="Jamaat" placeholder="Home Jamaat"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Jamiaat</label><input type="text" class="form-control form-control-sm" name="Jamiaat" placeholder="Jamia (if any)"></div>
          </div>
        </div>
      </div>
      <!-- Education & Skills -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-education"><span class="small fw-semibold text-uppercase">Education & Skills</span><span class="toggle-indicator">+</span></div>
        <div id="group-education" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Qualification</label><input type="text" class="form-control form-control-sm" name="Qualification" placeholder="Highest degree"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Languages</label><input type="text" class="form-control form-control-sm" name="Languages" placeholder="Comma separated"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Hunars</label><input type="text" class="form-control form-control-sm" name="Hunars" placeholder="Skills / Talents"></div>
          </div>
        </div>
      </div>
      <!-- Occupation -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-occupation"><span class="small fw-semibold text-uppercase">Occupation</span><span class="toggle-indicator">+</span></div>
        <div id="group-occupation" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Occupation</label><input type="text" class="form-control form-control-sm" name="Occupation" placeholder="Primary occupation"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Occupation</label><input type="text" class="form-control form-control-sm" name="Sub_Occupation" placeholder="Secondary"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Occupation2</label><input type="text" class="form-control form-control-sm" name="Sub_Occupation2" placeholder="Additional"></div>
          </div>
        </div>
      </div>
      <!-- Religious Milestones & Ziyarat -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-religious"><span class="small fw-semibold text-uppercase">Religious Milestones & Ziyarat</span><span class="toggle-indicator">+</span></div>
        <div id="group-religious" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Quran Sanad</label><input type="text" class="form-control form-control-sm" name="Quran_Sanad" placeholder="Yes / Year"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Qadambosi Sharaf</label><input type="text" class="form-control form-control-sm" name="Qadambosi_Sharaf" placeholder="Yes / Year"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Raudat Tahera Ziyarat</label><input type="text" class="form-control form-control-sm" name="Raudat_Tahera_Ziyarat" placeholder="Yes / Year"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Karbala Ziyarat</label><input type="text" class="form-control form-control-sm" name="Karbala_Ziyarat" placeholder="Yes / Year"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Ashara Mubaraka</label><input type="text" class="form-control form-control-sm" name="Ashara_Mubaraka" placeholder="Yes / City / Year"></div>
          </div>
        </div>
      </div>
      <!-- Housing & Address -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-housing"><span class="small fw-semibold text-uppercase">Housing & Address</span><span class="toggle-indicator">+</span></div>
        <div id="group-housing" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Housing</label><input type="text" class="form-control form-control-sm" name="Housing" placeholder="Building / Society"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Type of House</label><input type="text" class="form-control form-control-sm" name="Type_of_House" placeholder="Owned / Rented"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Address</label><input type="text" class="form-control form-control-sm" name="Address" placeholder="Flat / Street"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Building</label><input type="text" class="form-control form-control-sm" name="Building" placeholder="Building name"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Street</label><input type="text" class="form-control form-control-sm" name="Street" placeholder="Street"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Area</label><input type="text" class="form-control form-control-sm" name="Area" placeholder="Area"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">State</label><input type="text" class="form-control form-control-sm" name="State" placeholder="State"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">City</label><input type="text" class="form-control form-control-sm" name="City" placeholder="City"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Pincode</label><input type="text" class="form-control form-control-sm" name="Pincode" placeholder="Postal code"></div>
          </div>
        </div>
      </div>
      <!-- Sector Hierarchy -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-sector"><span class="small fw-semibold text-uppercase">Sector Hierarchy</span><span class="toggle-indicator">+</span></div>
        <div id="group-sector" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Sector</label>
              <select class="form-control form-select form-select-sm" name="Sector" id="sectorSelect">
                <option value="">-- Select Sector --</option>
                <?php if(!empty($sector_list)) foreach($sector_list as $sec): ?>
                  <option value="<?php echo htmlspecialchars($sec); ?>"><?php echo htmlspecialchars($sec); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Sub Sector</label>
              <select class="form-control form-select form-select-sm" name="Sub_Sector" id="subSectorSelect" disabled>
                <option value="">-- Select Sub Sector --</option>
              </select>
            </div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sector Incharge ITSID</label><input type="text" class="form-control form-control-sm" name="Sector_Incharge_ITSID"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sector Incharge Name</label><input type="text" class="form-control form-control-sm" name="Sector_Incharge_Name"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sector Incharge Female ITSID</label><input type="text" class="form-control form-control-sm" name="Sector_Incharge_Female_ITSID"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sector Incharge Female Name</label><input type="text" class="form-control form-control-sm" name="Sector_Incharge_Female_Name"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Sector Incharge ITSID</label><input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_ITSID"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Sector Incharge Name</label><input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Name"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Sector Incharge Female ITSID</label><input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Female_ITSID"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Sub Sector Incharge Female Name</label><input type="text" class="form-control form-control-sm" name="Sub_Sector_Incharge_Female_Name"></div>
          </div>
        </div>
      </div>
      <!-- Verification & Scan -->
      <div class="group-section">
        <div class="group-header py-2 px-3 bg-light border rounded d-flex justify-content-between align-items-center" data-group-target="group-verification"><span class="small fw-semibold text-uppercase">Verification & Scan</span><span class="toggle-indicator">+</span></div>
        <div id="group-verification" class="group-body collapsed border-start border-end border-bottom p-3">
          <div class="row g-3">
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Data Verification Status</label><select class="form-control form-select form-select-sm ver-status" data-date-target="dataVerificationDate" name="Data_Verifcation_Status"><option value="">--</option><option value="Verified">Verified</option><option value="Pending">Pending</option><option value="Not Verified">Not Verified</option></select></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Photo Verification Status</label><select class="form-control form-select form-select-sm ver-status" data-date-target="photoVerificationDate" name="Photo_Verifcation_Status"><option value="">--</option><option value="Verified">Verified</option><option value="Pending">Pending</option><option value="Not Verified">Not Verified</option></select></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Data Verification Date</label><input id="dataVerificationDate" type="date" class="form-control form-control-sm" name="Data_Verification_Date"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Photo Verification Date</label><input id="photoVerificationDate" type="date" class="form-control form-control-sm" name="Photo_Verification_Date"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Last Scanned Event</label><input type="text" class="form-control form-control-sm" name="Last_Scanned_Event"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Last Scanned Place</label><input type="text" class="form-control form-control-sm" name="Last_Scanned_Place"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Title</label><input type="text" class="form-control form-control-sm" name="Title" placeholder="Title"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Category</label><input type="text" class="form-control form-control-sm" name="Category" placeholder="Category"></div>
            <div class="col-md-4 col-12 mt-2"><label class="form-label small mb-1">Idara</label><input type="text" class="form-control form-control-sm" name="Idara" placeholder="Idara"></div>
            <div class="col-md-4 col-12 mt-2">
              <label class="form-label small mb-1">Inactive Status</label>
              <select name="Inactive_Status" id="inactiveStatusAdd" class="form-control form-select form-select-sm">
                <option value="">Active</option>
                <option value="Deceased">Deceased</option>
                <option value="Shifted Jamaat">Shifted Jamaat</option>
                <option value="Travel / Outstation">Travel / Outstation</option>
                <option value="Duplicate Record">Duplicate Record</option>
                <option value="Blocked / Suspended">Blocked / Suspended</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-sticky-spacer" style="height:90px"></div>
      <div class="form-sticky-bar">
        <div class="inner d-flex justify-content-center align-items-center gap-2">
          <button type="submit" class="btn btn-success btn-sm mr-2">Submit</button>
          <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-secondary btn-sm">Cancel</a>
          <span id="addMemberStatus" class="small ms-2"></span>
        </div>
      </div>
      <style>
        .form-sticky-bar{position:fixed;left:0;right:0;bottom:0;z-index:1050;padding:8px 0;background:rgba(255,255,255,0.95);backdrop-filter:blur(3px);border-top:1px solid #dee2e6;}
        .form-sticky-bar .inner{max-width:880px;margin:0 auto;padding:4px 12px;}
        @media (max-width: 600px){ .form-sticky-bar .inner{max-width:100%;} }
      </style>
    </div>
  </form>
  <script>
    (function(){
      // Sector/Sub-sector dependent dropdown
      var sectorMap = <?php echo json_encode($sector_map ?? []); ?>;
      var sectorSelect = document.getElementById('sectorSelect');
      var subSectorSelect = document.getElementById('subSectorSelect');
      function resetSub(){
        subSectorSelect.innerHTML = '<option value="">-- Select Sub Sector --</option>';
        subSectorSelect.disabled = true;
      }
      if(sectorSelect){
        sectorSelect.addEventListener('change', function(){
          var val = this.value; resetSub();
          if(val && sectorMap[val] && sectorMap[val].length){
            var frag = document.createDocumentFragment();
            sectorMap[val].forEach(function(ss){
              var opt = document.createElement('option'); opt.value = ss; opt.textContent = ss; frag.appendChild(opt);
            });
            subSectorSelect.appendChild(frag); subSectorSelect.disabled = false;
          }
        });
      }
      function today(){ var d=new Date(); return d.toISOString().slice(0,10);}    
      document.querySelectorAll('.ver-status').forEach(function(sel){ sel.addEventListener('change', function(){ var targetId=this.getAttribute('data-date-target'); var dateInput=document.getElementById(targetId); if(!dateInput)return; if(this.value==='Verified'){ if(!dateInput.value) dateInput.value=today(); } else if(this.value===''|| this.value==='Not Verified'){ dateInput.value=''; } }); });
      var typeSel=document.getElementById('hofTypeSelect'); var hofWrap=document.getElementById('hofSelectWrapper'); if(typeSel){ typeSel.addEventListener('change', function(){ if(this.value==='HOF'){ hofWrap.style.display='none'; } else { hofWrap.style.display='block'; } }); }
      // Toggle groups
      function setAll(expand){ document.querySelectorAll('.group-body').forEach(function(b){ var header=document.querySelector('.group-header[data-group-target="'+b.id+'"]'); var indicator=header?header.querySelector('.toggle-indicator'):null; if(expand){ b.classList.remove('collapsed'); if(indicator) indicator.textContent='−'; } else { b.classList.add('collapsed'); if(indicator) indicator.textContent='+'; } }); }
      document.getElementById('expandAllGroups').addEventListener('click', function(){ setAll(true); });
      document.getElementById('collapseAllGroups').addEventListener('click', function(){ setAll(false); });
      document.querySelectorAll('.group-header').forEach(function(h){ h.addEventListener('click', function(){ var targetId=h.getAttribute('data-group-target'); var body=document.getElementById(targetId); if(!body) return; var indicator=h.querySelector('.toggle-indicator'); var collapsed=body.classList.toggle('collapsed'); if(indicator) indicator.textContent=collapsed?'+':'−'; }); });
      // Form submit
      var form=document.getElementById('addMemberForm');
      form.addEventListener('submit', function(e){ 
        e.preventDefault(); 
        var fd=new FormData(form); 
        var hofType=fd.get('hof_type'); 
        if(hofType==='HOF'){ 
          fd.set('HOF_FM_TYPE','HOF'); fd.set('HOF_ID', fd.get('ITS_ID')); 
        } else { 
          fd.set('HOF_FM_TYPE','FM'); if(!fd.get('HOF_ID')){ alert('Select a HOF'); return; } 
        }
  // Leave Inactive_Status empty (do not force value); controller will treat missing/empty as NULL
        var mtSel = document.getElementById('memberTypeSelect');
        var mtErr = document.getElementById('memberTypeError');
        if(mtSel && mtSel.value && mtErr){ mtErr.style.display='none'; }
        console.log('Submitting member_type:', mtSel?mtSel.value:undefined);
        fetch(form.action,{method:'POST', body:fd}).then(r=>r.json()).then(json=>{ 
          var st=document.getElementById('addMemberStatus'); 
          if(json.status==='success'){ 
            st.textContent='Created'; st.className='small text-success'; 
            setTimeout(()=>{ window.location.href='<?php echo base_url('admin/managemembers'); ?>'; }, 700); 
          } else { 
            st.textContent=json.message||'Error'; st.className='small text-danger';
            if(json.field==='member_type' && mtErr){
              mtErr.textContent='Invalid member type. Allowed: '+ (json.allowed_values?json.allowed_values.join(', '):'');
              mtErr.style.display='block';
            }
          }
        }).catch(()=>{ var st=document.getElementById('addMemberStatus'); st.textContent='Network error'; st.className='small text-danger'; });
      });
    })();
  </script>
</div>
