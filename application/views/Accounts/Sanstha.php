<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:        #b8860b;
    --gold-light:  #e6c84a;
    --gold-muted:  #f5e9c0;
    --bg:          #faf7f0;
    --surface:     #ffffff;
    --surface-2:   #f7f4ec;
    --border:      #e8e0cc;
    --text-1:      #1a1610;
    --text-2:      #5a5244;
    --text-3:      #9c8f7a;
    --green:       #1a6645;
    --green-bg:    #eaf4ee;
    --radius-lg:   20px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,0.06);
    --shadow:      0 4px 16px rgba(0,0,0,0.07);
  }

  .sanstha-container {
    max-width: 1140px; margin: 30px auto 60px; padding: 0 15px; font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .sanstha-header-banner {
    background: linear-gradient(135deg, #78520a 0%, #b8860b 50%, #c9a227 100%);
    border-radius: var(--radius-lg); padding: 24px 30px; color: #fff; margin-bottom: 24px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
    box-shadow: var(--shadow);
  }

  .sanstha-title { font-family: 'Literata', serif; font-size: 1.6rem; margin: 0; font-weight: 600; }
  .sanstha-sub { font-size: 0.85rem; color: rgba(255,255,255,0.8); margin-top: 4px; }

  .year-select-box {
    background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px; padding: 6px 12px; color: #fff; font-weight: 800; font-size: 0.85rem;
    outline: none; cursor: pointer;
  }
  .year-select-box option { color: #000; }

  .sanstha-card {
    background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
    padding: 20px; margin-bottom: 16px; box-shadow: var(--shadow-sm); transition: transform 0.15s, box-shadow 0.15s;
  }
  .sanstha-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }

  .sanstha-badge {
    font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 20px;
    background: var(--green-bg); color: var(--green); border: 1px solid #bbf7d0; display: inline-block;
  }

  .nav-tabs-custom { border-bottom: 2px solid var(--border); margin-bottom: 20px; }
  .nav-tabs-custom .nav-link {
    font-weight: 700; color: var(--text-2); border: none; padding: 10px 20px; font-size: 0.9rem;
  }
  .nav-tabs-custom .nav-link.active {
    color: var(--gold); border-bottom: 3px solid var(--gold); background: transparent;
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="sanstha-container">
    
    <!-- Top Header Banner -->
    <div class="sanstha-header-banner">
      <div>
        <h1 class="sanstha-title"><i class="fa fa-building-o"></i> Sanstha Memberships</h1>
        <div class="sanstha-sub">View your Sanstha assignments and active Jamaat Sanstha directory.</div>
      </div>
      <div>
        <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;margin-right:6px"><i class="fa fa-calendar"></i> Hijri Year:</span>
        <select class="year-select-box" onchange="location.href='<?php echo base_url('accounts/sanstha?year='); ?>' + this.value">
          <option value="1446" <?php echo ($year === '1446') ? 'selected' : ''; ?>>1446 Hijri</option>
          <option value="1447" <?php echo ($year === '1447') ? 'selected' : ''; ?>>1447 Hijri</option>
          <option value="1448" <?php echo ($year === '1448') ? 'selected' : ''; ?>>1448 Hijri</option>
          <option value="1449" <?php echo ($year === '1449') ? 'selected' : ''; ?>>1449 Hijri</option>
          <option value="1450" <?php echo ($year === '1450') ? 'selected' : ''; ?>>1450 Hijri</option>
        </select>
      </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-custom" id="sansthaTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="my-sanstha-tab" data-toggle="tab" href="#my-sanstha" role="tab"><i class="fa fa-user-check mr-1"></i> My Sanstha Memberships (<?php echo count($member_sansthas); ?>)</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="all-sanstha-tab" data-toggle="tab" href="#all-sanstha" role="tab"><i class="fa fa-list-alt mr-1"></i> Jamaat Sanstha Directory (<?php echo count($all_sansthas); ?>)</a>
      </li>
    </ul>

    <div class="tab-content" id="sansthaTabsContent">
      
      <!-- TAB 1: MY SANSTHA MEMBERSHIPS -->
      <div class="tab-pane fade show active" id="my-sanstha" role="tabpanel">
        <?php if(!empty($member_sansthas)): ?>
          <div class="row">
            <?php foreach($member_sansthas as $s): ?>
              <div class="col-md-6 mb-3">
                <div class="sanstha-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 style="font-size:1.05rem;font-weight:800;color:var(--text-1);margin:0">
                      <i class="fa fa-building text-warning mr-1"></i> <?php echo htmlspecialchars($s['name']); ?>
                    </h4>
                    <span class="sanstha-badge"><i class="fa fa-check-circle"></i> Active Member (<?php echo htmlspecialchars($s['year'] ?? $year); ?>H)</span>
                  </div>
                  <?php if(!empty($s['description'])): ?>
                    <p style="font-size:0.82rem;color:var(--text-2);margin:4px 0 10px 0"><?php echo htmlspecialchars($s['description']); ?></p>
                  <?php endif; ?>
                  <div style="font-size:0.75rem;color:var(--text-3)">
                    <i class="fa fa-calendar-check-o"></i> Assigned on <?php echo date('d M Y', strtotime($s['assigned_at'])); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="card p-5 text-center" style="border-radius:16px;border:1px solid var(--border);background:#fff">
            <i class="fa fa-building-o fa-3x text-muted mb-3" style="opacity:0.4"></i>
            <h5 style="font-weight:700;color:var(--text-2)">No Sanstha Membership Assigned</h5>
            <p class="text-muted small mb-0">You are not currently assigned to any Sanstha for <?php echo htmlspecialchars($year); ?> Hijri.</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- TAB 2: JAMAAT SANSTHA DIRECTORY -->
      <div class="tab-pane fade" id="all-sanstha" role="tabpanel">
        <?php if(!empty($all_sansthas)): ?>
          <div class="row">
            <?php foreach($all_sansthas as $as): ?>
              <div class="col-md-6 mb-3">
                <div class="sanstha-card">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 style="font-size:1.05rem;font-weight:800;color:var(--text-1);margin:0">
                      <i class="fa fa-building-o text-warning mr-1"></i> <?php echo htmlspecialchars($as['name']); ?>
                    </h4>
                    <span class="badge badge-warning" style="font-size:0.75rem;padding:4px 10px;border-radius:20px">
                      <?php echo (int)($as['members_count'] ?? 0); ?> Members (<?php echo htmlspecialchars($year); ?>H)
                    </span>
                  </div>
                  <?php if(!empty($as['description'])): ?>
                    <p style="font-size:0.82rem;color:var(--text-2);margin:4px 0 0 0"><?php echo htmlspecialchars($as['description']); ?></p>
                  <?php else: ?>
                    <p style="font-size:0.78rem;color:var(--text-3);margin:4px 0 0 0;font-style:italic">No description available.</p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="card p-4 text-center" style="border-radius:16px;border:1px solid var(--border);background:#fff">
            <p class="text-muted mb-0">No active Sansthas found in Jamaat directory.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>
</div>
