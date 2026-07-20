<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
#umoorTeamsApp {
  font-family: 'Plus Jakarta Sans', sans-serif;
  padding: 20px;
  background: #faf7f0;
  min-height: 100vh;
}
.ut-card {
  background: #ffffff;
  border: 1px solid #e8e0cc;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  margin-bottom: 20px;
}
.ut-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
}
.ut-title {
  font-size: 1.25rem;
  font-weight: 800;
  color: #b8860b;
  margin: 0;
}
.ut-badge {
  background: #f5e9c0;
  color: #8a6408;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.74rem;
  font-weight: 800;
}
table.ut-tbl {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.8rem;
}
table.ut-tbl th {
  background: #f7f4ec;
  padding: 10px;
  text-align: left;
  font-size: 0.68rem;
  text-transform: uppercase;
  color: #5a5244;
  border-bottom: 2px solid #e8e0cc;
}
table.ut-tbl td {
  padding: 10px;
  border-bottom: 1px solid #f0ece0;
  vertical-align: middle;
}
</style>

<div id="umoorTeamsApp" class="margintopcontainer">
  
  <div class="ut-card">
    <div class="ut-header">
      <div>
        <h2 class="ut-title"><i class="fa fa-users"></i> <?php echo htmlspecialchars($umoor_list[$umoor_id] ?? 'Umoor Teams'); ?> – HR & Team Hierarchy</h2>
        <span style="font-size:0.78rem;color:#5a5244">Teams, Team Leads, and Team Members for Hijri Year <?php echo htmlspecialchars($active_year); ?></span>
      </div>
      <span class="ut-badge"><?php echo count($sub_committees); ?> Teams Registered</span>
    </div>

    <h4 style="font-size:0.9rem;font-weight:800;color:#1a1610;margin-top:20px;margin-bottom:10px">Sub-Committees / Teams</h4>
    <div style="overflow-x:auto">
      <table class="ut-tbl">
        <thead>
          <tr>
            <th>#</th>
            <th>Team Name</th>
            <th>Team Lead</th>
            <th>Lead ITS</th>
            <th>Active Members</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($sub_committees)): ?>
            <?php foreach ($sub_committees as $k => $sc): ?>
              <tr>
                <td><strong><?php echo $k + 1; ?></strong></td>
                <td><strong style="color:#1a1610"><?php echo htmlspecialchars($sc['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($sc['team_lead_name'] ?? 'Not Assigned'); ?></td>
                <td><span class="ut-badge"><?php echo htmlspecialchars($sc['team_lead_its'] ?? '—'); ?></span></td>
                <td><span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:10px;font-weight:700;font-size:0.72rem"><?php echo (int)($sc['members_count'] ?? 0); ?> Members</span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align:center;padding:20px;color:#9c8f7a">No teams/sub-committees assigned under this Umoor yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <h4 style="font-size:0.9rem;font-weight:800;color:#1a1610;margin-top:24px;margin-bottom:10px">All Assigned Members & Roles</h4>
    <div style="overflow-x:auto">
      <table class="ut-tbl">
        <thead>
          <tr>
            <th>Member Name</th>
            <th>ITS Number</th>
            <th>Role</th>
            <th>Sub-Committee</th>
            <th>Assigned Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($assigned_members)): ?>
            <?php foreach ($assigned_members as $m): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($m['member_name'] ?? $m['user_its']); ?></strong></td>
                <td><?php echo htmlspecialchars($m['user_its']); ?></td>
                <td><span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:10px;font-weight:700;font-size:0.72rem"><?php echo htmlspecialchars($m['role']); ?></span></td>
                <td><?php echo htmlspecialchars($m['sub_committee_id'] ?? 'Umoor Coordinator'); ?></td>
                <td><?php echo htmlspecialchars($m['assigned_at'] ?? $m['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align:center;padding:20px;color:#9c8f7a">No member role assignments found for this year.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

</div>
