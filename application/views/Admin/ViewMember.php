<?php /* View Single Member Details */ ?>
<style>
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
  .vm-page-title small {
    font-size: 0.9rem;
    color: #888;
  }
  
  .masonry-grid {
    column-count: 2;
    column-gap: 20px;
  }
  @media (max-width: 768px) {
    .masonry-grid {
      column-count: 1;
    }
  }
  .panel-group {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
    break-inside: avoid;
    display: inline-block;
    width: 100%;
  }
  .panel-heading {
    background-color: #fcfcfc;
    border-bottom: 1px solid #ddd;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .panel-title {
    margin: 0;
    font-size: 1.15rem;
    color: #6398b9;
    font-weight: 400;
    display: flex;
    align-items: center;
  }
  .panel-title i {
    color: #d9534f;
    margin-right: 10px;
    font-size: 0.85em;
  }
  .panel-body {
    padding: 15px;
  }
  .detail-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
  }
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
  .detail-table td {
    color: #333;
    width: 65%;
  }
  .detail-table td.empty {
    color: #aaa;
  }

  /* Family panels */
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
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color: #fff; display: flex; align-items: center;
    justify-content: center; font-size: 0.8rem; font-weight: 700; flex-shrink: 0;
  }
  .fm-avatar.female { background: linear-gradient(135deg,#ec4899,#f43f5e); }
  .fm-name { font-weight: 500; font-size: 0.9rem; color: #222; }
  .fm-its  { font-size: 0.78rem; color: #888; }
  .fm-hof-badge { font-size: 0.65rem; font-weight: 700; padding: 2px 7px; border-radius: 20px; background: #dbeafe; color: #1e40af; }

  /* Financial table */
  .fin-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
  .fin-table th { background: #f0f4f8; color: #4a5568; padding: 10px 14px; border: 1px solid #e2e8f0; text-align: left; white-space: nowrap; }
  .fin-table td { padding: 10px 14px; border: 1px solid #e2e8f0; color: #333; vertical-align: middle; }
  .fin-table tr:hover td { background: #fafbfc; }
  .badge-yes  { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; }
  .badge-no   { background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; }
  .badge-na   { background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 12px; font-size: 0.72rem; }

  /* Corpus summary row */
  .corpus-summary-block { margin-top: 12px; }
  .corpus-row { display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid #f0f0f0; font-size: 0.95rem; }
  .corpus-row:last-child { border-bottom: none; }
  .corpus-title { color: #4a5568; font-weight: 500; }
  .corpus-amounts span { margin-left: 10px; font-size: 0.95rem; }
  .amt-assigned { color: #1e40af; font-weight: 700; }
  .amt-paid     { color: #065f46; font-weight: 700; }
  .amt-due      { color: #991b1b; font-weight: 700; }
</style>

<div class="container margintopcontainer pt-4 mb-5 vm-wrapper">
  
  <div class="vm-header-wrap pt-5">
    <div>
      <h2 class="vm-page-title">View Member Details <small>» Dashboard</small></h2>
    </div>
    <div class="d-flex gap-2">
      <?php
        $role = isset($_SESSION['user']['role']) ? (int)$_SESSION['user']['role'] : 0;
        $back_url = 'javascript:history.back();';
        $view_member_base = 'admin/viewmember/';
        if ($role === 1) {
            $back_url = base_url('admin');
        } elseif ($role === 2) {
            $back_url = base_url('amilsaheb');
            $view_member_base = 'amilsaheb/viewmember/';
        } elseif ($role === 3) {
            $back_url = base_url('anjuman');
        } elseif ($role === 16) {
            $back_url = base_url('MasoolMusaid');
            $view_member_base = 'MasoolMusaid/viewmember/';
        } elseif ($role >= 4 && $role <= 15) {
            $back_url = base_url('Umoor');
        }
      ?>
      <a href="<?php echo $back_url; ?>" class="btn btn-default border btn-sm mr-2">Back</a>
      <?php if(!empty($member['ITS_ID']) && in_array($role, [1, 2, 3])): ?>
        <?php 
          $edit_base = ($role === 2) ? 'amilsaheb/editmember/' : 'admin/editmember/'; 
          $current_uri = $_SERVER['REQUEST_URI'];
          $redirect_query = '?redirect=' . urlencode($current_uri);
        ?>
        <a href="<?php echo base_url($edit_base).$member['ITS_ID'] . $redirect_query; ?>" class="btn btn-primary btn-sm">Edit</a>
      <?php endif; ?>
    </div>
  </div>

  <?php if(empty($member)): ?>
    <div class="alert alert-warning">No member data available.</div>
  <?php else: ?>
    <?php
      $humanize = function($key){
        $k = str_replace(['_id','_'], [' ID',' '], $key);
        $k = preg_replace('/\s+/', ' ', trim($k));
        return ucwords($k);
      };

      $getInitials = function($name){
        if(!$name) return '?';
        $parts = preg_split('/\s+/', trim($name));
        if(count($parts) === 1) return strtoupper(substr($parts[0],0,1));
        return strtoupper(substr($parts[0],0,1) . substr($parts[count($parts)-1],0,1));
      };

      $hof_id = !empty($member['HOF_ID']) ? $member['HOF_ID'] : $member['ITS_ID'];
      $family_members    = $family_members    ?? [];
      $family_financials = $family_financials ?? [];

      // Sort so HOF is always first
      usort($family_members, function($a, $b) {
        $aHof = (($a['HOF_FM_TYPE'] ?? '') === 'HOF') ? 0 : 1;
        $bHof = (($b['HOF_FM_TYPE'] ?? '') === 'HOF') ? 0 : 1;
        return $aHof - $bHof;
      });

      // Role check: only admins (role 1) and amilsaheb (role 2) see sensitive fields
      $is_admin_or_amilsaheb = in_array($role, [1, 2, 3]);
      $show_deeni_status = in_array($role, [1, 2, 3]); // NOT shown to member login (role 5 etc)

      // ITS-Sabeel match label map
      $matchLabels = [
        'its_sabeel_both_khar' => ['ITS & Sabeel both in Khar',      'success'],
        'its_khar_sabeel_out'  => ['ITS in Khar, Sabeel not in Khar', 'warning'],
        'sabeel_khar_its_out'  => ['Sabeel in Khar, ITS not in Khar', 'info'],
        'both_not_khar'        => ['Sabeel & ITS both not in Khar',   'secondary'],
      ];
      $actClasses = ['active' => 'success', 'inactive' => 'danger', 'temporary' => 'warning'];
      $its_match  = $member['its_sabeel_match'] ?? '';
      $actStatus  = $member['activity_status']  ?? '';
      $matchLbl   = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][0] : 'Not calculated';
      $matchCls   = isset($matchLabels[$its_match]) ? $matchLabels[$its_match][1] : 'secondary';
      $actCls     = $actClasses[$actStatus] ?? 'secondary';

      // Define the groups as requested
      $groups = [
        'My Details' => ['ITS_ID', 'Full_Name', 'Full_Name_Arabic', 'Category', 'Idara', 'Mobile', 'Email', 'WhatsApp'],
        'HOF Details' => ['HOF_ID', 'HOF_FM_TYPE', 'Family_ID'],
        'Personal Details' => ['First_Prefix', 'Prefix_Year', 'First_Name', 'Father_Prefix', 'Father_Name', 'Father_Surname', 'Father_ITS_ID', 'Surname', 'Husband_Name', 'Husband_Prefix', 'Gender', 'Age', 'Marital_Status', 'Date_of_Nikah', 'Date_of_Nikah_Hijri'],
        'Residence Details' => ['Housing'],
        'Financial Details' => [],
        'Sharaf Details' => ['Title'],
        'Umoor Diniyah' => ['Ashara_Mubaraka', 'Karbala_Ziyarat', 'Misaq'],
        'Health Details' => ['Blood_Group'],
        'Habits Details' => [],
        'Waraqat-ul Tarkhis' => [],
        'Education Qualification' => ['Languages'],
        'Work Details' => ['Occupation', 'Hunars'],
        'Residence Address Details' => ['Address', 'Area', 'Sector', 'Sub_Sector', 'City', 'Pincode', 'Building'],
      ];

      $rendered_keys = [];
      $member_keys_map = []; 
      foreach(array_keys($member) as $k) {
        $member_keys_map[strtolower($k)] = $k;
      }
    ?>

    <!-- ====== MEMBER STATUS PANEL (full width, prominent) ====== -->
    <div class="panel-group" style="break-inside:avoid; border-top: 3px solid #4f46e5;">
      <div class="panel-heading" style="background: linear-gradient(90deg,#eef2ff,#f5f7ff);">
        <h3 class="panel-title"><i class="fa fa-shield" style="color:#4f46e5;"></i> &nbsp;Member Status</h3>
        <div class="d-flex flex-wrap gap-2">
          <?php if($its_match): ?>
          <span class="badge badge-<?php echo $matchCls; ?>" style="font-size:0.78rem; padding:4px 10px;">
            <i class="fa fa-link"></i> <?php echo htmlspecialchars($matchLbl); ?>
          </span>
          <?php endif; ?>
          <?php if($actStatus): ?>
          <span class="ml-2 badge badge-<?php echo $actCls; ?>" style="font-size:0.78rem; padding:4px 10px;">
            <i class="fa fa-circle"></i> <?php echo ucfirst(htmlspecialchars($actStatus)); ?>
          </span>
          <?php endif; ?>
        </div>
      </div>
      <div class="panel-body" style="padding:12px 15px;">
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:10px 20px;">
          <!-- Auto data fields -->
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">ITS–Sabeel Match <span style="font-size:0.65rem;background:#e2e8f0;padding:1px 5px;border-radius:10px;color:#555;">Auto</span></div>
            <div style="font-weight:600;color:#222;"><?php echo htmlspecialchars($matchLbl ?: '—'); ?></div>
          </div>
          <!-- Manual data fields -->
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">Member Status <span style="font-size:0.65rem;background:#fef3c7;padding:1px 5px;border-radius:10px;color:#92400e;">Auto</span></div>
            <div style="font-weight:600;" class="text-<?php echo $actCls; ?>"><?php echo $actStatus ? ucfirst(htmlspecialchars($actStatus)) : '—'; ?></div>
          </div>
          <?php if($show_deeni_status): ?>
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">Deeni Status <span style="font-size:0.65rem;background:#fee2e2;padding:1px 5px;border-radius:10px;color:#991b1b;">Sensitive</span></div>
            <div style="font-weight:600;color:#222;"><?php echo htmlspecialchars($member['deeni_status'] ?? '—'); ?></div>
          </div>
          <?php endif; ?>
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">Health Status <span style="font-size:0.65rem;background:#fef3c7;padding:1px 5px;border-radius:10px;color:#92400e;">Manual</span></div>
            <div style="font-weight:600;color:#222;"><?php echo htmlspecialchars($member['health_status'] ?? '—'); ?></div>
          </div>
          <div>
            <div style="font-size:0.78rem;color:#888;margin-bottom:3px;">Residential Status <span style="font-size:0.65rem;background:#fef3c7;padding:1px 5px;border-radius:10px;color:#92400e;">Manual</span></div>
            <div style="font-weight:600;color:#222;"><?php echo htmlspecialchars($member['residential_status'] ?? '—'); ?></div>
          </div>
        </div>
      </div>
    </div>


    <!-- ====== FAMILY MEMBERS PANEL (full width, above masonry grid) ====== -->
    <?php if(!empty($family_members)): ?>
    <div class="panel-group" style="break-inside:avoid;">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-angle-double-right"></i> Family Members</h3>
        <span class="badge badge-secondary" style="font-size:0.75rem;"><?php echo count($family_members); ?> members</span>
      </div>
      <div class="panel-body" style="padding: 10px 15px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:4px 20px;">
          <?php foreach($family_members as $fm): ?>
            <?php
              $isF = (strtolower($fm['Gender'] ?? '') === 'female' || strtolower($fm['Gender'] ?? '') === 'f');
              $avCls = $isF ? 'fm-avatar female' : 'fm-avatar';
              $fmName = trim($fm['Full_Name'] ?? ($fm['First_Name'].' '.$fm['Surname']));
              $initials = $getInitials($fmName);
              $isHof = (($fm['HOF_FM_TYPE'] ?? '') === 'HOF');
              $isViewed = ($fm['ITS_ID'] == $member['ITS_ID']);
            ?>
            <a href="<?php echo base_url($view_member_base).$fm['ITS_ID']; ?>" class="family-member-row" style="<?php echo $isViewed ? 'background:#f0f7ff; border: 1px solid #cce5ff;' : ''; ?>">
              <div class="<?php echo $avCls; ?>"><?php echo htmlspecialchars($initials); ?></div>
              <div style="flex:1;min-width:0;">
                <div class="fm-name" style="word-break: break-word;">
                  <?php echo htmlspecialchars($fmName); ?>
                  <?php if($isHof): ?><span class="fm-hof-badge ml-1 d-inline-block">HOF</span><?php endif; ?>
                </div>
                <div class="fm-its">ITS: <?php echo htmlspecialchars($fm['ITS_ID'] ?? ''); ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ====== MAIN GRID & SIDEBAR ====== -->
    <?php
      // Prepare the Financial Panel HTML to reuse for mobile and desktop
      ob_start();
      $fin = $family_financials ?? [];
      $sabeel_map   = $fin['sabeel']   ?? [];
      $fmb_map      = $fin['fmb']      ?? [];
      $wajebaat_map = $fin['wajebaat'] ?? [];
      $thaali_map   = $fin['thaali']   ?? [];
      $husain_map   = $fin['husain']   ?? [];
      $corpus_rows  = $fin['corpus']   ?? [];

      $tot_sabeel = 0; $sabeel_year = '';
      $tot_fmb = 0; $fmb_year = '';
      $tot_waj_paid = 0; $tot_waj_due = 0;
      $thaali_active = false;
      $husain_active = false; $husain_tot = 0;

      foreach($family_members as $fm) {
          $fmId = $fm['ITS_ID'];
          if (isset($sabeel_map[$fmId])) {
              $tot_sabeel += $sabeel_map[$fmId]['total_sabeel'];
              if(!$sabeel_year) $sabeel_year = $sabeel_map[$fmId]['year'];
          }
          if (isset($fmb_map[$fmId])) {
              $tot_fmb += $fmb_map[$fmId]['fmb_amount'];
              if(!$fmb_year) $fmb_year = $fmb_map[$fmId]['fmb_year'];
          }
          if (isset($wajebaat_map[$fmId])) {
              $tot_waj_paid += $wajebaat_map[$fmId]['amount'];
              $tot_waj_due += $wajebaat_map[$fmId]['due'];
          }
          if (isset($thaali_map[$fmId]) && $thaali_map[$fmId]['want_thali'] == 1) $thaali_active = true;
          if (isset($husain_map[$fmId])) {
              $husain_active = true;
              $husain_tot += $husain_map[$fmId]['amount'];
          }
      }
    ?>
        <div class="panel-group" style="position: sticky; top: 20px;">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-rupee-sign"></i> Family Financials (HOF)</h3>
          </div>
          <div class="panel-body p-0">
            <div style="padding: 12px 16px;">
              <!-- Sabeel -->
              <div class="corpus-row">
                <span class="corpus-title">Sabeel Takhmeen <?php echo $sabeel_year ? "($sabeel_year)" : ""; ?></span>
                <span class="corpus-amounts"><span class="amt-assigned">₹<?php echo number_format($tot_sabeel,0); ?></span></span>
              </div>
              <!-- FMB -->
              <div class="corpus-row">
                <span class="corpus-title">FMB Takhmeen <?php echo $fmb_year ? "($fmb_year)" : ""; ?></span>
                <span class="corpus-amounts"><span class="amt-assigned">₹<?php echo number_format($tot_fmb,0); ?></span></span>
              </div>
              <!-- Wajebaat -->
              <div class="corpus-row">
                <span class="corpus-title">Wajebaat</span>
                <span class="corpus-amounts">
                  <span class="amt-paid" title="Paid">₹<?php echo number_format($tot_waj_paid,0); ?> paid</span>
                  <?php if($tot_waj_due > 0): ?>
                    <span class="amt-due" title="Due">₹<?php echo number_format($tot_waj_due,0); ?> due</span>
                  <?php endif; ?>
                </span>
              </div>
              <!-- Thaali -->
              <div class="corpus-row">
                <span class="corpus-title">Thaali Taking</span>
                <span class="corpus-amounts">
                  <?php echo $thaali_active ? '<span class="badge-yes" style="font-size:0.85rem; padding: 4px 10px;">Yes</span>' : '<span class="badge-no" style="font-size:0.85rem; padding: 4px 10px;">No</span>'; ?>
                </span>
              </div>
              <!-- Husain -->
              <div class="corpus-row">
                <span class="corpus-title">Husain Scheme</span>
                <span class="corpus-amounts">
                  <?php echo $husain_active ? '<span class="badge-yes" style="font-size:0.85rem; padding: 4px 10px;">Yes (₹'.number_format($husain_tot,0).')</span>' : '<span class="badge-no" style="font-size:0.85rem; padding: 4px 10px;">No</span>'; ?>
                </span>
              </div>
              <!-- Corpus -->
              <?php if(!empty($corpus_rows)): ?>
              <div style="margin-top: 15px; padding-top: 10px; border-top: 2px dashed #e2e8f0;">
                <div style="font-size:0.95rem;font-weight:600;color:#4a5568;margin-bottom:6px;"><i class="fa fa-bank mr-1"></i> Corpus Funds</div>
                <?php foreach($corpus_rows as $cf): $due = max(0, $cf['amount_assigned'] - $cf['paid']); ?>
                  <div class="corpus-row" style="padding-left: 10px;">
                    <span class="corpus-title"><?php echo htmlspecialchars($cf['title']); ?></span>
                    <span class="corpus-amounts">
                      <span class="amt-assigned">₹<?php echo number_format($cf['amount_assigned'],0); ?></span>
                      <span class="amt-paid">₹<?php echo number_format($cf['paid'],0); ?> paid</span>
                      <span class="amt-due">₹<?php echo number_format($due,0); ?> due</span>
                    </span>
                  </div>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
    <?php $financial_panel_html = ob_get_clean(); ?>

    <div class="row">
      <!-- Left Column (My Details etc) -->
      <div class="col-lg-8 col-md-7 mb-4">
        <div class="masonry-grid">
      <?php foreach($groups as $groupName => $fields): ?>
      <?php 
        $fieldsToRender = [];
        foreach($fields as $f) {
          $lf = strtolower($f);
          if(isset($member_keys_map[$lf])) {
            $fieldsToRender[] = $member_keys_map[$lf];
          }
        }
      ?>
      <?php if(!empty($fieldsToRender)): ?>
        <div class="panel-group">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-angle-double-right"></i> <?php echo $groupName; ?></h3>
            <i class="fa fa-chevron-up text-muted" style="font-size:0.8em;"></i>
          </div>
          <div class="panel-body">
            <table class="detail-table">
              <tbody>
                <?php foreach($fieldsToRender as $field): ?>
                  <?php $rendered_keys[] = $field; $val = $member[$field]; ?>
                  <tr>
                    <th><?php echo htmlspecialchars($humanize($field)); ?>:</th>
                    <?php if($val === null || $val === ''): ?>
                      <td class="empty">-</td>
                    <?php else: ?>
                      <td><?php echo nl2br(htmlspecialchars((string)$val)); ?></td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
      
      <?php if ($groupName === 'My Details'): ?>
        <!-- Mobile Financial Panel -->
        <div class="d-block d-md-none mb-4 mt-2">
           <?php echo $financial_panel_html; ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php 
      // Additional Details for any unmapped fields
      $other_fields = [];
      foreach($member as $key => $val) {
        if(!in_array($key, $rendered_keys)) {
          $other_fields[] = $key;
        }
      }
      if(!empty($other_fields)): 
        sort($other_fields);
    ?>
      <div class="panel-group">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-angle-double-right"></i> Additional Details</h3>
          <i class="fa fa-chevron-up text-muted" style="font-size:0.8em;"></i>
        </div>
        <div class="panel-body">
          <table class="detail-table">
            <tbody>
              <?php foreach($other_fields as $field): $val = $member[$field]; ?>
                <tr>
                  <th><?php echo htmlspecialchars($humanize($field)); ?>:</th>
                  <?php if($val === null || $val === ''): ?>
                    <td class="empty">-</td>
                  <?php else: ?>
                    <td><?php echo nl2br(htmlspecialchars((string)$val)); ?></td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
    </div><!-- /masonry-grid -->

      </div><!-- /col-lg-8 -->

      <!-- Right Sidebar (Financials) -->
      <div class="col-lg-4 col-md-5 mb-4 d-none d-md-block">
        <?php echo $financial_panel_html; ?>
      </div>
      
    </div><!-- /row -->

  <?php endif; ?>
</div>
