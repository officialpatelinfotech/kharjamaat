<head>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #61A0B1;
      margin: 0;
      padding: 0;
      text-align: center;
      background-image: url('<?php echo base_url('assets/background.png'); ?>');
      background-attachment: fixed;
    }

    .profile-container {
      max-width: 1100px;
      margin: 0px auto;
    }

    .profile-card {
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: left;
      padding: 20px;
    }

    h2 {
      color: goldenrod;
      border-bottom: 2px solid goldenrod;
      padding-bottom: 10px;
    }

    .detail-box {
      margin-bottom: 10px;
      border-radius: 8px;
      padding: 10px;
      border: 2px solid goldenrod;
    }

    .user-details {
      background-color: #ffff;
    }

    .family-details {
      background-color: #ffff;
    }

    .family-members {
      background-color: #ffff;
    }

    .incharge-details {
      background-color: #ffff;
    }

    @media (max-width: 768px) {
      .profile-card {
        width: 80%;
        margin: 20px auto;
      }
    }

    strong {
      color: #AD7E05;
    }
  </style>
</head>

<body>
  <div class="d-flex align-items-center container margintopcontainer pt-5">
    <a href="<?php echo base_url('accounts/home'); ?>" class="mt-4 btn btn-secondary me-2"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  <div class="profile-container mt-3 mb-5">
    <div class="row">
      <!-- User Profile Card (Left) -->
      <div class="col-md-6">
        <div class="profile-card user-details">
          <h2>PERSONAL DETAILS</h2>
          <div class="detail-box">
            <strong>ITS ID:</strong> <?php echo $user_data['ITS_ID']; ?>
          </div>
          <div class="detail-box">
            <strong>Full Name:</strong> <?php echo $user_data['Full_Name']; ?>
          </div>
          <div class="detail-box">
            <strong>Full Name (Arabic):</strong> <?php echo $user_data['Full_Name_Arabic']; ?>
          </div>
          <div class="detail-box">
            <strong>Vatan:</strong> <?php echo $user_data['Vatan']; ?>
          </div>
          <div class="detail-box" id="mobile-row">
            <strong>Mobile:</strong>
            <span id="mobile-display"><?php echo htmlspecialchars($user_data['Mobile'] ?? ''); ?></span>
            <button class="btn btn-sm btn-outline-primary ml-2" id="edit-mobile">Edit</button>
            <div id="mobile-edit" style="display:none; margin-top:8px;">
              <input type="text" id="mobile-input" class="form-control" value="<?php echo htmlspecialchars($user_data['Mobile'] ?? ''); ?>" placeholder="Enter mobile">
              <div style="margin-top:6px;">
                <button class="btn btn-sm btn-success" id="save-mobile">Save</button>
                <button class="btn btn-sm btn-secondary" id="cancel-mobile">Cancel</button>
              </div>
            </div>
          </div>
          <div class="detail-box" id="family-mobile-row">
            <strong>Registered Family Mobile:</strong>
            <span id="family-mobile-display"><?php echo htmlspecialchars($user_data['Registered_Family_Mobile'] ?? ''); ?></span>
            <button class="btn btn-sm btn-outline-primary ml-2" id="edit-family-mobile">Edit</button>
            <div id="family-mobile-edit" style="display:none; margin-top:8px;">
              <input type="text" id="family-mobile-input" class="form-control" value="<?php echo htmlspecialchars($user_data['Registered_Family_Mobile'] ?? ''); ?>" placeholder="Enter family mobile">
              <div style="margin-top:6px;">
                <button class="btn btn-sm btn-success" id="save-family-mobile">Save</button>
                <button class="btn btn-sm btn-secondary" id="cancel-family-mobile">Cancel</button>
              </div>
            </div>
          </div>

          <div class="detail-box" id="email-row">
            <strong>Email:</strong>
            <span id="email-display"><?php echo htmlspecialchars($user_data['Email'] ?? ''); ?></span>
            <button class="btn btn-sm btn-outline-primary ml-2" id="edit-email">Edit</button>
            <div id="email-edit" style="display:none; margin-top:8px;">
              <input type="email" id="email-input" class="form-control" value="<?php echo htmlspecialchars($user_data['Email'] ?? ''); ?>" placeholder="Enter email">
              <div style="margin-top:6px;">
                <button class="btn btn-sm btn-success" id="save-email">Save</button>
                <button class="btn btn-sm btn-secondary" id="cancel-email">Cancel</button>
              </div>
            </div>
          </div>
          <div class="detail-box">
            <strong>Age:</strong> <?php echo $user_data['Age']; ?>
          </div>
          <div class="detail-box">
            <strong>Gender:</strong> <?php echo $user_data['Gender']; ?>
          </div>
          <div class="detail-box">
            <strong>Misaq:</strong> <?php echo $user_data['Misaq']; ?>
          </div>
          <div class="detail-box">
            <strong>Marital Status:</strong> <?php echo $user_data['Marital_Status']; ?>
          </div>
          <div class="detail-box">
            <strong>Blood Group:</strong> <?php echo $user_data['Blood_Group']; ?>
          </div>
          <div class="detail-box">
            <strong>Organisation(s):</strong> <?php echo $user_data['Organisation']; ?>
          </div>
          <div class="detail-box">
            <strong>TanzeemFile No:</strong> <?php echo $user_data['TanzeemFile_No']; ?>
          </div>
        </div>
        <div class="profile-card user-details">
          <h2>RESIDENTIAL ADDRESS</h2>
          <div class="detail-box">
            <strong>Address:</strong> <?php echo $user_data['Address']; ?>
          </div>
          <div class="detail-box">
            <strong>City:</strong> <?php echo $user_data['City']; ?>
          </div>
          <div class="detail-box">
            <strong>Pincode:</strong> <?php echo $user_data['Pincode']; ?>
          </div>
        </div>
        <div class="profile-card user-details">
          <h2>JAMAAT DETAILS</h2>
          <div class="detail-box">
            <strong>Jamaat:</strong> <?php echo $user_data['Jamaat']; ?>
          </div>
          <div class="detail-box">
            <strong>Jamiaat:</strong> <?php echo $user_data['Jamiaat']; ?>
          </div>
        </div>
      </div>

      <!-- Other Details Card (Right) -->
      <div class="col-md-6">
        <!-- Family Details Card -->
        <div class="profile-card family-details">
          <h2>PARENTS</h2>
          <div class="detail-box">
            <strong>Father's ITS ID:</strong> <?php echo $father_data['ITS_ID']; ?>
          </div>
          <div class="detail-box">
            <strong>Father's Name:</strong> <?php echo $father_data['Full_Name']; ?>
          </div>
          <div class="detail-box">
            <strong>Mother's ITS ID:</strong> <?php echo $mother_data['ITS_ID']; ?>
          </div>
          <div class="detail-box">
            <strong>Mother's Name:</strong> <?php echo $mother_data['Full_Name']; ?>
          </div>
        </div>

        <div class="profile-card family-details">
          <h2>HEAD OF FAMILY</h2>
          <div class="detail-box">
            <strong>HOF ITS ID:</strong> <?php echo $hof_data['ITS_ID']; ?>
          </div>
          <div class="detail-box">
            <strong>HOF Name:</strong> <?php echo $hof_data['Full_Name']; ?>
          </div>
        </div>

        <!-- Family Members Card -->
        <div class="profile-card family-members">
          <h2>FAMILY MEMBERS</h2>
          <?php foreach ($family_members as $member): ?>
            <div class="detail-box">
              <strong>ITS ID:</strong> <?php echo '<span style="border-right: 2px solid goldenrod; padding-right: 5px">' . $member['ITS_ID'] . '</span>'; ?> <strong>Full Name:</strong> <?php echo $member['Full_Name']; ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Incharge Details Card -->
        <div class="profile-card incharge-details">
          <h2>MASOOL / MUSAID</h2>
          <div class="detail-box">
            <strong>Sector:</strong> <?php echo $user_data['Sector']; ?>
          </div>
          <div class="detail-box">
            <strong>Sub Sector:</strong> <?php echo $user_data['Sub_Sector']; ?>
          </div>
          <div class="detail-box">
            <strong>Sector Incharge (Masool):</strong> <?php echo $incharge_data['Sector_Incharge_Name']; ?>
          </div>
          <div class="detail-box">
            <strong>Sector Incharge Female (Masool):</strong> <?php echo $incharge_data['Sector_Incharge_Female_Name']; ?>
          </div>
          <div class="detail-box">
            <strong>Sub Sector Incharge (Musaid):</strong> <?php echo $incharge_data['Sub_Sector_Incharge_Name']; ?>
          </div>
          <div class="detail-box">
            <strong>Sub Sector Incharge Female (Musaid):</strong> <?php echo $incharge_data['Sub_Sector_Incharge_Female_Name']; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  // Show only the user-table fields that are NOT already displayed above.
  $shown_user_keys = [
    // Personal Details
    'ITS_ID',
    'Full_Name',
    'Full_Name_Arabic',
    'Vatan',
    'Mobile',
    'Registered_Family_Mobile',
    'Email',
    'Age',
    'Gender',
    'Misaq',
    'Marital_Status',
    'Blood_Group',
    'Organisation',
    'TanzeemFile_No',
    // Address
    'Address',
    'City',
    'Pincode',
    // Jamaat
    'Jamaat',
    'Jamiaat',
    // Incharge section fields shown from user_data
    'Sector',
    'Sub_Sector',
  ];
  $other_fields = [];
  if (!empty($user_data) && is_array($user_data)) {
    foreach ($user_data as $k => $v) {
      if (in_array($k, $shown_user_keys, true)) continue;
      if (strtolower((string)$k) === 'id') continue;
      $other_fields[$k] = $v;
    }
  }
  ?>

  <?php if (!empty($other_fields)): ?>
    <div class="profile-container mt-3 mb-5">
      <div class="row">
        <div class="col-12">
          <div class="profile-card user-details">
            <h2>OTHER DETAILS</h2>
            <?php foreach ($other_fields as $key => $value): ?>
              <?php
              $label = ucwords(str_replace('_', ' ', (string)$key));
              if ($value === null || $value === '') {
                $display = '---';
              } elseif (is_scalar($value)) {
                $display = (string)$value;
              } else {
                $display = json_encode($value);
              }
              ?>
              <div class="detail-box">
                <strong><?php echo htmlspecialchars($label); ?>:</strong>
                <?php echo nl2br(htmlspecialchars($display)); ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Inline edit handlers for Mobile, Email, Registered Family Mobile
    (function() {
      function show(el) {
        el.style.display = 'block';
      }

      function hide(el) {
        el.style.display = 'none';
      }

      // Mobile
      var editMobileBtn = document.getElementById('edit-mobile');
      var mobileEdit = document.getElementById('mobile-edit');
      var mobileDisplay = document.getElementById('mobile-display');
      var mobileInput = document.getElementById('mobile-input');
      var saveMobile = document.getElementById('save-mobile');
      var cancelMobile = document.getElementById('cancel-mobile');
      editMobileBtn && editMobileBtn.addEventListener('click', function() {
        show(mobileEdit);
        editMobileBtn.style.display = 'none';
      });
      cancelMobile && cancelMobile.addEventListener('click', function() {
        hide(mobileEdit);
        editMobileBtn.style.display = 'inline-block';
      });
      saveMobile && saveMobile.addEventListener('click', function() {
        var v = mobileInput.value.trim();
        saveField({
          mobile: v
        }, function(success) {
          if (success) {
            mobileDisplay.textContent = v;
            hide(mobileEdit);
            editMobileBtn.style.display = 'inline-block';
          }
        });
      });

      // Email
      var editEmailBtn = document.getElementById('edit-email');
      var emailEdit = document.getElementById('email-edit');
      var emailDisplay = document.getElementById('email-display');
      var emailInput = document.getElementById('email-input');
      var saveEmail = document.getElementById('save-email');
      var cancelEmail = document.getElementById('cancel-email');
      editEmailBtn && editEmailBtn.addEventListener('click', function() {
        show(emailEdit);
        editEmailBtn.style.display = 'none';
      });
      cancelEmail && cancelEmail.addEventListener('click', function() {
        hide(emailEdit);
        editEmailBtn.style.display = 'inline-block';
      });
      saveEmail && saveEmail.addEventListener('click', function() {
        var v = emailInput.value.trim();
        saveField({
          email: v
        }, function(success) {
          if (success) {
            emailDisplay.textContent = v;
            hide(emailEdit);
            editEmailBtn.style.display = 'inline-block';
          }
        });
      });

      // Family mobile
      var editFamilyBtn = document.getElementById('edit-family-mobile');
      var familyEdit = document.getElementById('family-mobile-edit');
      var familyDisplay = document.getElementById('family-mobile-display');
      var familyInput = document.getElementById('family-mobile-input');
      var saveFamily = document.getElementById('save-family-mobile');
      var cancelFamily = document.getElementById('cancel-family-mobile');
      editFamilyBtn && editFamilyBtn.addEventListener('click', function() {
        show(familyEdit);
        editFamilyBtn.style.display = 'none';
      });
      cancelFamily && cancelFamily.addEventListener('click', function() {
        hide(familyEdit);
        editFamilyBtn.style.display = 'inline-block';
      });
      saveFamily && saveFamily.addEventListener('click', function() {
        var v = familyInput.value.trim();
        saveField({
          registered_family_mobile: v
        }, function(success) {
          if (success) {
            familyDisplay.textContent = v;
            hide(familyEdit);
            editFamilyBtn.style.display = 'inline-block';
          }
        });
      });

      function saveField(payload, cb) {
        var form = new FormData();
        for (var k in payload) form.append(k, payload[k]);
        fetch('<?php echo base_url("accounts/update_profile_contact"); ?>', {
          method: 'POST',
          credentials: 'same-origin',
          body: form
        }).then(function(r) {
          return r.json();
        }).then(function(json) {
          if (json && json.success) {
            if (cb) cb(true);
          } else {
            alert('Save failed: ' + (json && json.error ? json.error : 'Unknown'));
            if (cb) cb(false);
          }
        }).catch(function(e) {
          console.error(e);
          alert('Save failed');
          if (cb) cb(false);
        });
      }
    })();
  </script>
</body>

</html>