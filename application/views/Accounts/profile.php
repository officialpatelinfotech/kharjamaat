<?php /* Member Profile View (My Profile) */ ?>
<?php
  // The controller may pass different variable names; keep the view resilient.
  $member = $user_data ?? ($_SESSION['user_data'] ?? []);
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
  body {
    background: #f5f7fb;
    font-family: Arial, sans-serif;
    background-attachment: fixed;
  }

  .vm-wrapper { max-width: 1300px; margin: 0 auto; }

  .vm-header-wrap {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin: 20px 0;
    border-bottom: 1px dashed #eee;
    padding-bottom: 15px;
  }

  .vm-page-title {
    font-size: 1.6rem;
    color: #4a82a6;
    font-weight: 300;
    margin: 0;
  }

  .vm-page-title small { font-size: 0.9rem; color: #888; }

  .profile-summary {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .profile-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff;
    font-size: 1.4rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .profile-name { font-size: 1.25rem; font-weight: 600; color: #222; }
  .profile-sub { color: #777; margin-top: 4px; font-size: 0.95rem; }

  .panel-group {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
    break-inside: avoid;
    display: inline-block;
    width: 100%;
    overflow: hidden;
  }

  .panel-heading {
    background-color: #fcfcfc;
    border-bottom: 1px solid #ddd;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .panel-title {
    margin: 0;
    font-size: 1.15rem;
    color: #6398b9;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .panel-body { padding: 15px; }

  .detail-table { width: 100%; border-collapse: collapse; margin: 0; }
  .detail-table th, .detail-table td {
    border: 1px solid #e0e0e0;
    padding: 10px 15px;
    font-size: 0.88rem;
  }
  .detail-table th {
    background-color: #f9f9f9;
    color: #555;
    width: 35%;
    font-weight: 400;
    text-align: left;
  }
  .detail-table td { color: #333; width: 65%; }
  .detail-table td.empty { color: #aaa; }

  .masonry-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    align-items: start;
  }
  @media (min-width: 992px) {
    .masonry-grid { grid-template-columns: 1fr; }
  }

  .two-col-layout { display: block; }
  @media (min-width: 992px) {
    .two-col-layout { display: grid; grid-template-columns: 1fr 360px; gap: 24px; }
  }

  @media (max-width: 768px) {
    .two-col-layout { grid-template-columns: 1fr; }
  }

  /* Family list */
  .family-members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 8px 20px;
  }

  .family-member-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-bottom: 1px solid #f0f0f0;
    border-radius: 6px;
  }

  a.family-member-row {
    text-decoration: none !important;
    color: inherit;
    transition: background-color 0.2s, transform 0.1s;
  }

  a.family-member-row:hover {
    background-color: #f8fafc !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.04);
  }

  .family-member-row:last-child { border-bottom: none; }

  .fm-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    flex-shrink: 0;
  }

  .fm-name { font-weight: 500; font-size: 0.9rem; color: #222; line-height: 1.3; }
  .fm-its { font-size: 0.78rem; color: #888; }

  /* Optional "member status panel" spacing to match screenshot */
  .member-status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px 20px;
  }

  .badge-soft {
    font-size: 0.78rem;
    padding: 4px 10px;
    border-radius: 999px;
  }

  .sticky-right { position: sticky; top: 20px; }
  .edit-hint { font-size: 0.72rem; color: #888; margin-top: 6px; }

  /* Keep your edit-section behavior */
  .edit-section { display: none; }

  .btn-xs { padding: 2px 8px; font-size: 0.75rem; }

  /* Remove the old custom masonry layout; use panels instead */
</style>

<div class="container margintopcontainer pt-4 mb-5 vm-wrapper">

  <!-- HEADER -->
  <div class="vm-header-wrap pt-5 mt-5">
    <div>
      <h2 class="vm-page-title">
        My Profile
        <small>» Dashboard</small>
      </h2>
    </div>

    <div>
      <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary btn-sm">
        Back
      </a>
    </div>
  </div>

  <!-- PROFILE SUMMARY -->
  <div class="profile-summary">
    <div class="profile-avatar">
      <?php echo strtoupper(substr($member['Full_Name'], 0, 1)); ?>
    </div>

    <div>
      <div class="profile-name">
        <?php echo htmlspecialchars($member['Full_Name']); ?>
      </div>

      <div class="profile-sub">
        ITS ID:
        <?php echo htmlspecialchars($member['ITS_ID']); ?>
      </div>
    </div>
  </div>

  <!-- MEMBER STATUS PANEL (optional, only if fields exist) -->
  <?php
    if (!class_exists('MemberStatusM')) {
      CI_Controller::get_instance()->load->model('MemberStatusM');
    }
    $health_options      = MemberStatusM::health_status_options();
    $residential_options = MemberStatusM::residential_status_options();

    $stripActiveInactive = function($str) {
      return trim(preg_replace('/\s*\((Active|Inactive)\)\s*$/i', '', $str));
    };

    $itsMatch = $member['its_sabeel_match'] ?? '';
    $actStatus = $member['activity_status'] ?? '';
    $healthStatus = $member['health_status'] ?? '';
    $residentialStatus = $member['residential_status'] ?? '';

    $matchLabels = [
      'its_sabeel_both_khar' => ['ITS & Sabeel both in Khar', 'success'],
      'its_khar_sabeel_out'  => ['ITS in Khar, Sabeel not in Khar', 'warning'],
      'sabeel_khar_its_out'  => ['Sabeel in Khar, ITS not in Khar', 'info'],
      'both_not_khar'        => ['Sabeel & ITS both not in Khar', 'secondary'],
    ];
    $actClasses = ['active' => 'success', 'inactive' => 'danger', 'temporary' => 'warning'];

    $matchLbl = isset($matchLabels[$itsMatch]) ? $matchLabels[$itsMatch][0] : '';
    $matchCls = isset($matchLabels[$itsMatch]) ? $matchLabels[$itsMatch][1] : 'secondary';
    $actCls = isset($actClasses[$actStatus]) ? $actClasses[$actStatus] : 'secondary';
  ?>
  <?php if (!empty($itsMatch) || !empty($actStatus) || !empty($healthStatus) || !empty($residentialStatus)): ?>
    <div class="panel-group" style="break-inside:avoid; border-top: 3px solid #4f46e5;">
      <div class="panel-heading" style="background-color: #f5f7ff;">
        <h3 class="panel-title">
          <i class="fa fa-shield" style="color:#4f46e5;"></i> &nbsp;Member Status
        </h3>
        <div class="d-flex flex-wrap gap-2">
          <?php if (!empty($matchLbl)): ?>
            <span class="badge badge-<?php echo $matchCls; ?> badge-soft">
              <i class="fa fa-link"></i> <?php echo htmlspecialchars($matchLbl); ?>
            </span>
          <?php endif; ?>
          <?php if (!empty($actStatus)): ?>
            <span class="ml-md-2 mt-1 mt-md-0 badge badge-<?php echo $actCls; ?> badge-soft">
              <i class="fa fa-circle"></i> <?php echo ucfirst(htmlspecialchars($actStatus)); ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="panel-body" style="padding:12px 15px;">
        <div class="member-status-grid">
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">
              ITS–Sabeel Match
              <span style="font-size:0.65rem;background:#e2e8f0;padding:1px 5px;border-radius:10px;color:#555;">Auto</span>
            </div>
            <div style="font-weight:600;color:#222;">
              <?php echo !empty($matchLbl) ? htmlspecialchars($matchLbl) : '—'; ?>
            </div>
          </div>

          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">
              Member Status
              <span style="font-size:0.65rem;background:#e2e8f0;padding:1px 5px;border-radius:10px;color:#555;">Auto</span>
            </div>
            <div style="font-weight:600;" class="text-<?php echo $actCls; ?>">
              <?php echo !empty($actStatus) ? ucfirst(htmlspecialchars($actStatus)) : '—'; ?>
            </div>
          </div>

          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">
              Health Status
              <span style="font-size:0.65rem;background:#fef3c7;padding:1px 5px;border-radius:10px;color:#92400e;">Manual</span>
            </div>
            <div style="font-weight:600;color:#222;">
              <?php
                $healthLabel = !empty($healthStatus) ? ($health_options[$healthStatus] ?? $healthStatus) : '—';
                echo htmlspecialchars($stripActiveInactive($healthLabel));
              ?>
            </div>
          </div>

          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">
              Residential Status
              <span style="font-size:0.65rem;background:#fef3c7;padding:1px 5px;border-radius:10px;color:#92400e;">Manual</span>
            </div>
            <div style="font-weight:600;color:#222;">
              <?php
                $resLabel = !empty($residentialStatus) ? ($residential_options[$residentialStatus] ?? $residentialStatus) : '—';
                echo htmlspecialchars($stripActiveInactive($resLabel));
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- FAMILY MEMBERS -->
  <?php if (!empty($family_members)): ?>
    <div class="panel-group">
      <div class="panel-heading">
        <h3 class="panel-title">
          <i class="fa fa-angle-double-right" style="color:#6398b9;"></i>
          Family Members
        </h3>
        <span class="badge badge-secondary" style="font-size:0.75rem;">
          <?php echo count($family_members); ?> members
        </span>
      </div>

      <div class="panel-body" style="padding: 10px 15px;">
        <div class="family-members-grid">
          <?php foreach ($family_members as $fm): ?>
            <?php
              $name = $fm['Full_Name'] ?? '';
              $initial = $name ? strtoupper(substr($name, 0, 1)) : '?';
            ?>
            <div class="family-member-row">
              <div class="fm-avatar">
                <?php echo htmlspecialchars($initial); ?>
              </div>

              <div style="flex:1; min-width:0;">
                <div class="fm-name" style="word-break: break-word;">
                  <?php echo htmlspecialchars($name); ?>
                </div>
                <div class="fm-its">
                  ITS: <?php echo htmlspecialchars($fm['ITS_ID'] ?? ''); ?><?php echo !empty($fm['Age']) ? ' &bull; Age: ' . htmlspecialchars($fm['Age']) : ''; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- MAIN DETAILS GRID -->
  <div class="details-grid">
    <?php
      $groups = [
        'Personal Details' => [
          'ITS_ID', 'Full_Name', 'Full_Name_Arabic', 'Vatan', 'Mobile', 
          'Registered_Family_Mobile', 'Email', 'Age', 'Gender', 'Misaq', 
          'Marital_Status', 'Blood_Group'
        ],
        'Residential Address' => [
          'Address', 'City', 'Pincode'
        ],
        'Jamaat Details' => [
          'Jamaat', 'Jamiaat', 'Sector', 'Sub_Sector'
        ],
      ];

      function humanize($key) {
        return ucwords(str_replace('_', ' ', $key));
      }

      // Helper to render a panel
      $renderPanel = function($group_name, $fields, $member) {
    ?>
      <div class="panel-group">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-angle-double-right"></i> <?php echo htmlspecialchars($group_name); ?></h3>
        </div>
        <div class="panel-body">
          <table class="detail-table">
            <tbody>
              <?php foreach ($fields as $field): ?>
                <?php $value = $member[$field] ?? ''; ?>
                <tr>
                  <th><?php echo htmlspecialchars(humanize($field)); ?></th>
                  <td>
                    <?php if ($field === 'Mobile'): ?>
                      <span id="mobile-display"><?php echo htmlspecialchars($value); ?></span>
                      <button class="btn btn-outline-primary btn-xs ml-2" id="edit-mobile">Edit</button>
                      <div class="edit-section" id="mobile-edit">
                        <input type="text" id="mobile-input" class="form-control" value="<?php echo htmlspecialchars($value); ?>">
                        <div class="mt-2">
                          <button class="btn btn-success btn-sm" id="save-mobile">Save</button>
                          <button class="btn btn-secondary btn-sm" id="cancel-mobile">Cancel</button>
                        </div>
                      </div>
                    <?php elseif ($field === 'Email'): ?>
                      <span id="email-display"><?php echo htmlspecialchars($value); ?></span>
                      <button class="btn btn-outline-primary btn-xs ml-2" id="edit-email">Edit</button>
                      <div class="edit-section" id="email-edit">
                        <input type="email" id="email-input" class="form-control" value="<?php echo htmlspecialchars($value); ?>">
                        <div class="mt-2">
                          <button class="btn btn-success btn-sm" id="save-email">Save</button>
                          <button class="btn btn-secondary btn-sm" id="cancel-email">Cancel</button>
                        </div>
                      </div>
                    <?php elseif ($field === 'Registered_Family_Mobile'): ?>
                      <span id="family-mobile-display"><?php echo htmlspecialchars($value); ?></span>
                      <button class="btn btn-outline-primary btn-xs ml-2" id="edit-family-mobile">Edit</button>
                      <div class="edit-section" id="family-mobile-edit">
                        <input type="text" id="family-mobile-input" class="form-control" value="<?php echo htmlspecialchars($value); ?>">
                        <div class="mt-2">
                          <button class="btn btn-success btn-sm" id="save-family-mobile">Save</button>
                          <button class="btn btn-secondary btn-sm" id="cancel-family-mobile">Cancel</button>
                        </div>
                      </div>
                    <?php else: ?>
                      <?php if ($value === ''): ?>
                        <span class="empty">-</span>
                      <?php else: ?>
                        <?php echo nl2br(htmlspecialchars((string)$value)); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php }; ?>

    <style>
      .details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        align-items: start;
      }
      @media (min-width: 992px) {
        .details-grid {
          grid-template-columns: 1.2fr 1fr;
        }
      }
    </style>

    <div class="details-left">
      <?php $renderPanel('Personal Details', $groups['Personal Details'], $member); ?>
    </div>

    <div class="details-right">
      <?php $renderPanel('Residential Address', $groups['Residential Address'], $member); ?>
      <?php $renderPanel('Jamaat Details', $groups['Jamaat Details'], $member); ?>
    </div>
  </div>

</div>

<script>
(function() {

  function saveField(payload, callback) {
    var form = new FormData();
    for (var key in payload) {
      form.append(key, payload[key]);
    }

    fetch('<?php echo base_url("accounts/update_profile_contact"); ?>', {
      method: 'POST',
      credentials: 'same-origin',
      body: form
    })
    .then(function(res) { return res.json(); })
    .then(function(json) {
      if (json && json.success) callback(true);
      else {
        alert((json && json.error) ? json.error : 'Save failed');
        callback(false);
      }
    })
    .catch(function() {
      alert('Save failed');
      callback(false);
    });
  }

  // MOBILE
  document.getElementById('edit-mobile')?.addEventListener('click', function() {
    document.getElementById('mobile-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-mobile')?.addEventListener('click', function() {
    document.getElementById('mobile-edit').style.display = 'none';
    document.getElementById('edit-mobile').style.display = 'inline-block';
  });

  document.getElementById('save-mobile')?.addEventListener('click', function() {
    var value = document.getElementById('mobile-input').value.trim();
    saveField({ mobile: value }, function(success) {
      if (!success) return;
      document.getElementById('mobile-display').textContent = value;
      document.getElementById('mobile-edit').style.display = 'none';
      document.getElementById('edit-mobile').style.display = 'inline-block';
    });
  });

  // EMAIL
  document.getElementById('edit-email')?.addEventListener('click', function() {
    document.getElementById('email-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-email')?.addEventListener('click', function() {
    document.getElementById('email-edit').style.display = 'none';
    document.getElementById('edit-email').style.display = 'inline-block';
  });

  document.getElementById('save-email')?.addEventListener('click', function() {
    var value = document.getElementById('email-input').value.trim();
    saveField({ email: value }, function(success) {
      if (!success) return;
      document.getElementById('email-display').textContent = value;
      document.getElementById('email-edit').style.display = 'none';
      document.getElementById('edit-email').style.display = 'inline-block';
    });
  });

  // REGISTERED FAMILY MOBILE
  document.getElementById('edit-family-mobile')?.addEventListener('click', function() {
    document.getElementById('family-mobile-edit').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('cancel-family-mobile')?.addEventListener('click', function() {
    document.getElementById('family-mobile-edit').style.display = 'none';
    document.getElementById('edit-family-mobile').style.display = 'inline-block';
  });

  document.getElementById('save-family-mobile')?.addEventListener('click', function() {
    var value = document.getElementById('family-mobile-input').value.trim();
    saveField({ registered_family_mobile: value }, function(success) {
      if (!success) return;
      document.getElementById('family-mobile-display').textContent = value;
      document.getElementById('family-mobile-edit').style.display = 'none';
      document.getElementById('edit-family-mobile').style.display = 'inline-block';
    });
  });

})();
</script>
