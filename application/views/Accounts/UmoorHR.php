<?php
// Accounts/UmoorHR.php — Member Portal View for 12 Umoor HR matching Member Dashboard Design
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,opsz,wght@0,6..72,400;0,6..72,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
:root {
  --gold:        #b8860b;
  --gold-light:  #e6c84a;
  --gold-muted:  #f5e9c0;
  --bg:          #faf7f0;
  --surface:     #ffffff;
  --surface-2:   #f7f4ec;
  --border:      #e8e0cc;
  --border-light:#f0ece0;
  --text-1:      #1a1610;
  --text-2:      #5a5244;
  --text-3:      #9c8f7a;
  --radius:      14px;
  --radius-lg:   20px;
  --shadow-sm:   0 1px 3px rgba(0,0,0,0.06);
  --shadow:      0 4px 16px rgba(0,0,0,0.07);
}

.umoor-container {
  max-width: 1140px; margin: 30px auto 60px; padding: 0 15px; font-family: 'Plus Jakarta Sans', sans-serif;
}

.umoor-header-banner {
  background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
  border-radius: var(--radius-lg); padding: 24px 30px; color: #fff; margin-bottom: 24px;
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
  box-shadow: var(--shadow);
}

.umoor-title { font-family: 'Literata', serif; font-size: 1.6rem; margin: 0; font-weight: 600; color: #fff; }
.umoor-sub { font-size: 0.85rem; color: rgba(255,255,255,0.85); margin-top: 4px; }

.year-select-box {
  background: rgba(255,255,255,0.22); border: 1px solid rgba(255,255,255,0.35);
  border-radius: 10px; padding: 7px 14px; color: #fff; font-weight: 800; font-size: 0.85rem;
  outline: none; cursor: pointer;
}
.year-select-box option { color: #000; }

.section-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: var(--shadow-sm);
}

.my-assignment-card {
  background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
  border: 1px solid #fde68a;
  border-radius: 14px;
  padding: 18px 20px;
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: var(--shadow-sm);
  transition: transform 0.15s, box-shadow 0.15s;
}
.my-assignment-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }

.role-badge {
  background: var(--gold);
  color: #fff;
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 800;
  display: inline-block;
}

.umoor-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 16px;
}

.u-card {
  background: var(--surface-2);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
  transition: transform 0.15s, box-shadow 0.15s;
}
.u-card:hover { transform: translateY(-2px); background: #fff; box-shadow: var(--shadow); }
.u-num { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--gold); }
.u-name { font-size: 0.95rem; font-weight: 800; color: var(--text-1); margin: 2px 0 10px 0; }
</style>

<div class="umoor-container pt-5">

  <!-- Header Banner matching Member Dashboard Design -->
  <div class="umoor-header-banner">
    <div>
      <h1 class="umoor-title"><i class="fa fa-sitemap mr-2"></i>My 12 Umoor HR</h1>
      <div class="umoor-sub">View your active 12 Umoor HR assignments and browse Jamaat 12 Umoor directory.</div>
    </div>
    <div>
      <span style="font-size:0.75rem;font-weight:800;text-transform:uppercase;margin-right:8px;color:#fff"><i class="fa fa-calendar"></i> Hijri Year:</span>
      <select class="year-select-box" onchange="changeYear(this.value)">
        <option value="1446" <?php echo $year == '1446' ? 'selected' : ''; ?>>1446 Hijri</option>
        <option value="1447" <?php echo $year == '1447' ? 'selected' : ''; ?>>1447 Hijri</option>
        <option value="1448" <?php echo $year == '1448' ? 'selected' : ''; ?>>1448 Hijri</option>
        <option value="1449" <?php echo $year == '1449' ? 'selected' : ''; ?>>1449 Hijri</option>
        <option value="1450" <?php echo $year == '1450' ? 'selected' : ''; ?>>1450 Hijri</option>
      </select>
    </div>
  </div>

  <!-- SECTION 1: MY ASSIGNMENTS -->
  <div class="section-card">
    <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-1);margin:0 0 18px 0;display:flex;align-items:center">
      <i class="fa fa-user-circle text-warning mr-2"></i> My Assigned 12 Umoor HR Roles (<?php echo htmlspecialchars($year); ?> Hijri)
    </h3>

    <?php if (!empty($my_assignments)): ?>
      <div class="row">
        <?php foreach ($my_assignments as $assign): ?>
          <div class="col-md-6 mb-3">
            <div class="my-assignment-card">
              <div>
                <span class="role-badge mb-1"><?php echo htmlspecialchars($assign['role']); ?></span>
                <h4 style="font-size:1.05rem;font-weight:800;color:#78350f;margin:8px 0 3px 0"><?php echo htmlspecialchars($assign['umoor_name']); ?></h4>
                <p style="font-size:0.83rem;color:#92400e;margin:0">
                  <?php echo !empty($assign['sub_committee_name']) ? ('<strong>Team:</strong> ' . htmlspecialchars($assign['sub_committee_name'])) : 'Assigned directly to Umoor Level'; ?>
                </p>
              </div>
              <div style="text-align:right">
                <span style="font-size:0.78rem;color:#b45309;font-weight:800"><?php echo htmlspecialchars($assign['year']); ?> Hijri</span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="padding:28px;text-align:center;background:var(--surface-2);border-radius:12px;color:var(--text-3)">
        <i class="fa fa-info-circle fa-2x mb-2" style="color:var(--gold)"></i>
        <p style="font-size:0.9rem;margin:0;font-weight:700;color:var(--text-2)">You are not assigned to any 12 Umoor HR role for <?php echo htmlspecialchars($year); ?> Hijri.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- SECTION 2: JAMAAT 12 UMOOR HIERARCHY DIRECTORY -->
  <div class="section-card">
    <h3 style="font-size:1.05rem;font-weight:800;color:var(--text-1);margin:0 0 18px 0">
      <i class="fa fa-sitemap text-primary mr-2"></i> Jamaat 12 Umoor Hierarchy &amp; Coordinators Directory
    </h3>

    <div class="umoor-grid">
      <?php foreach ($hierarchy as $h): ?>
        <div class="u-card">
          <div class="u-num"><?php echo $h['umoor_id']; ?></div>
          <div class="u-name"><?php echo htmlspecialchars($h['umoor_name']); ?></div>
          <div style="border-top:1px dashed var(--border);padding-top:8px;margin-top:6px;font-size:0.75rem">
            <div style="color:#b8860b;font-weight:700;margin-bottom:4px">
              <i class="fa fa-user"></i> <strong>Male:</strong> <?php echo !empty($h['male_coordinator']) ? htmlspecialchars($h['male_coordinator']['name']) : '<em class="text-muted">Unassigned</em>'; ?>
            </div>
            <div style="color:#059669;font-weight:700">
              <i class="fa fa-user"></i> <strong>Al Aqeeq:</strong> <?php echo !empty($h['female_coordinator']) ? htmlspecialchars($h['female_coordinator']['name']) : '<em class="text-muted">Unassigned</em>'; ?>
            </div>
          </div>
          <?php if (!empty($h['teams'])): ?>
            <div style="margin-top:10px;padding-top:6px;border-top:1px solid var(--border);font-size:0.72rem;color:var(--text-2)">
              <strong>Teams (<?php echo count($h['teams']); ?>):</strong>
              <?php 
                $tNames = array_map(function($t) { return $t['name']; }, $h['teams']);
                echo htmlspecialchars(implode(', ', array_slice($tNames, 0, 3)));
                if (count($tNames) > 3) echo '...';
              ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<script>
function changeYear(yr) {
  window.location.href = '<?php echo base_url("accounts/umoor_hr?year="); ?>' + yr;
}
</script>
