<?php /* View Single Member Details */ ?>
<style>
  .vm-wrapper { max-width:1100px; margin:0 auto; }
  .vm-title { font-size:1.15rem; font-weight:600; }
  .vm-table th { width:240px; background:#f8f9fa; }
  .vm-badge { font-size:.65rem; letter-spacing:.5px; }
  @media (max-width: 768px){
    .vm-table th { width:140px; }
  }
</style>
<div class="container margintopcontainer pt-4 mb-5 vm-wrapper">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
      <span class="vm-title">Member Details</span>
      <?php if(!empty($member['ITS_ID'])): ?>
        <span class="badge bg-primary vm-badge">ITS: <?php echo htmlspecialchars($member['ITS_ID']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['HOF_FM_TYPE'])): ?>
        <span class="badge bg-<?php echo $member['HOF_FM_TYPE']==='HOF'?'success':'info'; ?> vm-badge"><?php echo htmlspecialchars($member['HOF_FM_TYPE']); ?></span>
      <?php endif; ?>
      <?php if(!empty($member['Member_Type'])): ?>
        <span class="badge bg-warning text-dark vm-badge"><?php echo htmlspecialchars($member['Member_Type']); ?></span>
      <?php endif; ?>
    </div>
    <?php if(!empty($member['ITS_ID'])): ?>
      <div class="d-flex gap-2">
        <a href="<?php echo base_url('admin/editmember/').$member['ITS_ID']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil me-1"></i>Edit</a>
      </div>
    <?php endif; ?>
  </div>
  <?php if(empty($member)): ?>
    <div class="alert alert-warning">No member data available.</div>
  <?php else: ?>
    <?php
      // Sort keys to keep consistent output; preserve original if needed
      $displayMember = $member;
      ksort($displayMember);
      // Humanize keys
      $humanize = function($key){
        $k = str_replace(['_id','_'], [' ID',' '], $key);
        $k = preg_replace('/\s+/', ' ', trim($k));
        return ucwords($k);
      };
    ?>
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-striped mb-0 vm-table">
            <tbody>
              <?php foreach($displayMember as $field=>$value): ?>
                <tr>
                  <th class="text-muted small text-uppercase"><?php echo htmlspecialchars($humanize($field)); ?></th>
                  <td class="small">
                    <?php if($value === null || $value === ''): ?>
                      <span class="text-muted">—</span>
                    <?php else: ?>
                      <?php echo nl2br(htmlspecialchars((string)$value)); ?>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
