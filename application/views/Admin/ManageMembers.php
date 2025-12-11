<?php /* Manage Members View */ ?>
<style>
  .mm-header { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
  .mm-add-btn { background:#0d6efd; color:#fff !important; border:none; padding:.5rem .9rem; border-radius:.35rem; font-weight:500; display:inline-flex; align-items:center; gap:.4rem; text-decoration:none; }
  .mm-add-btn:hover { background:#0b5ed7; color:#fff; }
  .mm-edit-btn { background:#ffc107; border:none; color:#212529 !important; padding:.35rem .65rem; border-radius:.25rem; font-size:.7rem; font-weight:600; text-decoration:none; }
  .mm-edit-btn:hover { background:#ffca2c; color:#212529; }
  .filter-badge-group .badge { font-size:.65rem; font-weight:500; }
  .filter-badge-group .btn-close { font-size:.5rem; line-height:1; margin-left:.35rem; opacity:.75; }
  .filter-badge-group .btn-close:hover { opacity:1; }
  .filter-form small.text-muted { font-size:.65rem; }
  /* Consistent compact action buttons */
  .mm-actions { display:flex; justify-content:center; align-items:center; gap:6px; }
  .mm-actions .btn { width:32px; height:32px; padding:0; display:inline-flex; justify-content:center; align-items:center; border-radius:6px; }
  .mm-actions .mm-edit-btn { width:32px; height:32px; padding:0; display:inline-flex; justify-content:center; align-items:center; }
  .mm-actions i { font-size:14px; }
  @media (max-width: 576px){ .mm-actions { gap:4px; } }
</style>
<div class="container margintopcontainer pt-5 mb-5">
  <div class="mm-header mb-3 w-100">
    <div class="d-flex align-items-center gap-2">
      <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
      <div class="btn-group mr-2 mt-2" role="group" aria-label="Data actions">
        <a href="<?php echo base_url('admin/exportmembers'); ?>" class="btn btn-sm btn-outline-success" title="Download current member dataset"><i class="fa fa-download me-1"></i> Export Members</a>
        <a href="<?php echo base_url('admin/importlatest'); ?>" class="btn btn-sm btn-outline-primary" title="Import latest external data source"><i class="fa fa-upload me-1"></i> Import Latest Data</a>
      </div>
      <!-- <form method="post" action="<?php echo base_url('admin/reset_all_member_passwords'); ?>" class="mt-4 mr-2" onsubmit="return confirm('Are you absolutely sure? This will reset ALL active non-admin users\' passwords to default (ITS ID).');">
        <input type="hidden" name="confirm" value="1" />
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset all member passwords">
          <i class="fa fa-key me-1"></i> Reset All Passwords
        </button>
      </form> -->
      <a href="<?php echo base_url('admin/addmember'); ?>" class="mt-2 mm-add-btn"><i class="fa fa-user-plus"></i> Add Member</a>
    </div>
  </div>
  <?php if(function_exists('get_instance')){ $CI = get_instance(); }
  if(isset($CI) && isset($CI->session)){
    $succ = $CI->session->flashdata('success');
    $err  = $CI->session->flashdata('error');
    if($succ){ echo '<div class="alert alert-success py-2">'.htmlspecialchars($succ).'</div>'; }
    if($err){ echo '<div class="alert alert-danger py-2">'.htmlspecialchars($err).'</div>'; }
  } ?>
<?php 
  $af = $applied_filters ?? []; 
  $meta = $filter_meta ?? ['sectors'=>[], 'sub_sectors'=>[], 'hofs'=>[], 'statuses'=>['active','inactive']];
  if(!function_exists('sel')){ function sel($a,$b){ return ($a!==null && $a!=='' && (string)$a === (string)$b) ? 'selected' : ''; } }
?>
  <?php
    // Build active chips
    $chipMap = [
      'name' => 'Name',
      'sector' => 'Sector',
      'sub_sector' => 'Sub Sector',
      'status' => 'Status',
      'hof' => 'HOF'
    ];
    $activeChips = [];
    foreach($chipMap as $k=>$label){
      if(isset($af[$k]) && $af[$k] !== '' && $af[$k] !== null){
        $activeChips[$k] = $label . ': ' . htmlspecialchars($af[$k]);
      }
    }
    $activeCount = count($activeChips);
  ?>
  <div class="card mb-3 border-0 shadow-sm" id="filterCard">
    <div class="card-header bg-dark text-white d-flex flex-wrap align-items-center justify-content-between py-2" style="cursor:pointer;" id="filterHeader">
      <div class="d-flex align-items-center gap-2">
        <strong class="text-uppercase small">Filters</strong>
        <?php if($activeCount): ?><span class="badge bg-primary rounded-pill"><?php echo $activeCount; ?></span><?php endif; ?>
      </div>
      <button class="text-white btn btn-sm btn-link text-decoration-none p-0" type="button" id="filterToggleBtn">
        <i class="fa fa-sliders mr-1"></i> <span><?php echo $activeCount ? 'Hide' : 'Show'; ?></span>
      </button>
    </div>
    <?php if($activeCount): ?>
      <div class="px-3 pt-2 pb-1 filter-badge-group">
        <?php foreach($activeChips as $key=>$text): ?>
          <?php 
            $params = $_GET; unset($params[$key]);
            $base = base_url('admin/managemembers');
            $qs = http_build_query(array_filter($params, function($v){ return $v!=='' && $v!==null; }));
            $removeUrl = $qs ? $base.'?'.$qs : $base;
          ?>
          <span class="badge text-bg-light border">
            <i class="fa fa-filter me-1"></i><?php echo $text; ?>
            <button type="button" class="btn btn-close ms-1" style="float:none;" onclick="window.location.href='<?php echo $removeUrl; ?>'" aria-label="Remove"><i class="fa-solid fa-times"></i></button>
          </span>
        <?php endforeach; ?>
        <a href="<?php echo base_url('admin/managemembers'); ?>" class="badge text-bg-danger text-decoration-none">Clear All</a>
      </div>
    <?php endif; ?>
  <div id="filterBody" style="<?php echo $activeCount ? '' : 'display:none;'; ?>">
      <div class="card-body pt-3 pb-2 filter-form" style="border-top:1px solid #f1f1f1;">
        <form method="get" class="row g-3 align-items-end">
          <div class="col-lg-3 col-md-4 col-sm-6">
            <label class="form-label small mb-1">Member Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($af['name'] ?? ''); ?>" class="form-control" placeholder="e.g. Burhanuddin">
          </div>
          <div class="col-lg-2 col-md-3 col-sm-6">
            <label class="form-label small mb-1">Sector</label>
            <select name="sector" class="form-control form-select form-select-sm">
              <option value="">All</option>
              <?php foreach($meta['sectors'] as $s): ?>
                <option value="<?php echo htmlspecialchars($s); ?>" <?php echo sel($af['sector'] ?? '', $s); ?>><?php echo htmlspecialchars($s); ?></option>
              <?php endforeach; ?>
            </select>
            <?php if(empty($meta['sectors'])): ?><small class="text-muted">No sectors</small><?php endif; ?>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-6">
            <label class="form-label small mb-1">Sub Sector</label>
            <select name="sub_sector" class="form-control form-select form-select-sm">
              <option value="">All</option>
              <?php foreach($meta['sub_sectors'] as $ss): ?>
                <option value="<?php echo htmlspecialchars($ss); ?>" <?php echo sel($af['sub_sector'] ?? '', $ss); ?>><?php echo htmlspecialchars($ss); ?></option>
              <?php endforeach; ?>
            </select>
            <?php if(empty($meta['sub_sectors'])): ?><small class="text-muted">No sub sectors</small><?php endif; ?>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-6">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-control form-select form-select-sm">
              <option value="">All (Default)</option>
              <?php foreach($meta['statuses'] as $st): ?>
                <option value="<?php echo $st; ?>" <?php echo sel($af['status'] ?? '', $st); ?>><?php echo ucfirst($st); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <label class="form-label small mb-1">HOF</label>
            <select name="hof" class="form-control form-select form-select-sm">
              <option value="">All HOFs</option>
              <?php foreach($meta['hofs'] as $h): ?>
                <option value="<?php echo htmlspecialchars($h['value']); ?>" <?php echo sel($af['hof'] ?? '', $h['value']); ?>><?php echo htmlspecialchars($h['Full_Name']) . ' (' . $h['value'] . ')'; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check me-1"></i>Apply</button>
            <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-sm btn-outline-secondary ml-2"><i class="fa fa-undo me-1"></i>Reset</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    (function($){
      var btn = $('#filterToggleBtn');
      var body = $('#filterBody');
      if(btn.length && body.length){
        btn.on('click', function(e){
          e.preventDefault();
          body.slideToggle(160, function(){
            var visible = body.is(':visible');
            btn.find('span').text(visible ? 'Hide' : 'Show');
          });
        });
        // Also allow clicking the header area (excluding direct form interactions)
        $('#filterHeader').on('click', function(e){
          if($(e.target).closest('form,select,input,button').length) return; // ignore if interacting with form
          btn.trigger('click');
        });
      }
    })(jQuery);
  </script>

  <?php if (!empty($members)): ?>
    <?php
      // echo "<pre>" . print_r($members, true) . "</pre>";
      // Group members by HOF (HOF_ID). If HOF_ID missing, ITS_ID acts as own group.
      $groups = [];
      foreach ($members as $row) {
        $hofId = $row['HOF_ID'] ?? $row['ITS_ID'];
        if (!isset($groups[$hofId])) { $groups[$hofId] = []; }
        $groups[$hofId][] = $row;
      }
      // Helper to detect HOF record in group (HOF_FM_TYPE == 'HOF')
      $getHofRecord = function($arr){
        foreach($arr as $r){ if(($r['HOF_FM_TYPE'] ?? '') === 'HOF') return $r; }
        return $arr[0];
      };
    ?>
    <div class="table-responsive border rounded shadow-sm">
      <table class="table table-sm table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Full Name</th>
            <th>Sector</th>
            <th>Sub Sector</th>
            <th style="width:110px">Relation</th>
            
            <th class="text-center" style="width:120px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $rowIndex = 1; foreach($groups as $hofId => $rows): $hofRec = $getHofRecord($rows); ?>
          <tr class="table-primary" style="--bs-table-bg:#e7f1ff;">
            <td colspan="6" class="py-2">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>HOF:</strong> <?php echo htmlspecialchars($hofRec['Full_Name'] ?? ''); ?>
                  <span class="text-white badge bg-primary ms-2">ITS: <?php echo htmlspecialchars($hofRec['ITS_ID'] ?? ''); ?></span>
                  <?php if(!empty($hofRec['Sector'])): ?>
                    <span class="text-white badge bg-info text-dark ms-1">Sector: <?php echo htmlspecialchars($hofRec['Sector']); ?></span>
                  <?php endif; ?>
                  <?php if(!empty($hofRec['Sub_Sector'])): ?>
                    <span class="text-white badge bg-secondary ms-1">Sub: <?php echo htmlspecialchars($hofRec['Sub_Sector']); ?></span>
                  <?php endif; ?>
                  <?php if(!empty($hofRec['member_type'])): ?>
                    <span class="badge bg-warning text-dark ms-1"><?php echo htmlspecialchars($hofRec['member_type']); ?></span>
                  <?php endif; ?>
                </div>
                <div>
                  <a href="<?php echo base_url('admin/editmember/') . ($hofRec['ITS_ID'] ?? ''); ?>" class="btn btn-sm btn-outline-primary">Edit HOF</a>
                </div>
              </div>
            </td>
          </tr>
          <?php foreach($rows as $r): ?>
            <?php $isHof = (($r['HOF_FM_TYPE'] ?? '') === 'HOF'); ?>
            <tr>
              <td><?php echo $rowIndex++; ?></td>
              <td><?php echo htmlspecialchars($r['Full_Name'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($r['Sector'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($r['Sub_Sector'] ?? ''); ?></td>
              <td>
                <?php if($isHof): ?>
                  <span class="text-white badge bg-primary">HOF</span>
                <?php else: ?>
                  <span class="badge bg-light text-dark">Member</span>
                <?php endif; ?>
              </td>
              
              <td class="text-center">
                <div class="mm-actions">
                  <button type="button" class="btn btn-outline-secondary btn-view-member" data-its="<?php echo htmlspecialchars($r['ITS_ID'] ?? ''); ?>" title="View"><i class="fa fa-eye"></i></button>
                  <a href="<?php echo base_url('admin/editmember/') . ($r['ITS_ID'] ?? ''); ?>" class="mm-edit-btn" title="Edit"><i class="fa fa-pencil"></i></a>
                  <form method="post" action="<?php echo base_url('admin/reset_member_password'); ?>" onsubmit="return confirm('Reset password to MD5 of ITS ID for this member?');" class="mt-3">
                    <input type="hidden" name="its_id" value="<?php echo htmlspecialchars($r['ITS_ID'] ?? ''); ?>">
                    <button type="submit" class="btn btn-outline-danger" title="Reset Password"><i class="fa fa-key"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No members found.</div>
  <?php endif; ?>
</div>
<!-- Member Details Modal -->
<div class="modal fade" id="memberDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title">Member Details</h6>
        <button type="button" class="btn btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="memberDetailsLoading" class="p-3 small text-muted">Loading...</div>
        <div class="table-responsive" id="memberDetailsTableWrapper" style="display:none;">
          <table class="table table-sm table-striped mb-0">
            <tbody id="memberDetailsTableBody"></tbody>
          </table>
        </div>
        <div id="memberDetailsError" class="p-3 text-danger small" style="display:none;"></div>
      </div>
      <div class="modal-footer py-2">
        <a id="memberEditLink" href="#" class="btn btn-primary btn-sm"><i class="fa fa-pencil me-1"> </i>Edit</a>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    const modalEl = document.getElementById('memberDetailsModal');
    if(!modalEl) return;
    // Bootstrap 5 modal init (assumes bootstrap bundle loaded globally)
    let bsModal = null;
    function ensureModal(){ if(!bsModal){ bsModal = new bootstrap.Modal(modalEl); } return bsModal; }
    function humanize(key){
      return key
        .replace(/_/g,' ') 
        .replace(/\bid\b/gi,'ID')
        .replace(/\s+/g,' ') 
        .trim()
        .replace(/\b([a-z])/g, c => c.toUpperCase());
    }
    function clearState(){
      document.getElementById('memberDetailsLoading').style.display='block';
      document.getElementById('memberDetailsTableWrapper').style.display='none';
      document.getElementById('memberDetailsError').style.display='none';
      document.getElementById('memberDetailsTableBody').innerHTML='';
    }
    function populate(member){
      const tbody = document.getElementById('memberDetailsTableBody');
      // Sort keys alphabetically for consistency
      const keys = Object.keys(member).sort();
      const frag = document.createDocumentFragment();
      keys.forEach(k => {
        const tr = document.createElement('tr');
        const th = document.createElement('th');
        th.className='text-muted small text-uppercase';
        th.style.width='220px';
        th.textContent = humanize(k);
        const td = document.createElement('td');
        td.className='small';
        const val = member[k];
        if(val===null || val===''){
          td.innerHTML = '<span class="text-muted">—</span>';
        } else {
          td.textContent = val;
        }
        tr.appendChild(th); tr.appendChild(td); frag.appendChild(tr);
      });
      tbody.appendChild(frag);
      document.getElementById('memberDetailsLoading').style.display='none';
      document.getElementById('memberDetailsTableWrapper').style.display='block';
    }
    function showError(msg){
      document.getElementById('memberDetailsLoading').style.display='none';
      const err = document.getElementById('memberDetailsError');
      err.textContent = msg || 'Failed to load member.';
      err.style.display='block';
    }
    document.querySelectorAll('.btn-view-member').forEach(btn => {
      btn.addEventListener('click', function(){
        const its = this.getAttribute('data-its');
        if(!its) return;
        clearState();
        ensureModal().show();
        fetch('<?php echo base_url('admin/memberjson/'); ?>'+ encodeURIComponent(its))
          .then(r => r.json())
          .then(json => {
            if(json.status==='success'){ 
              populate(json.member);
              const editLink = document.getElementById('memberEditLink');
              editLink.href = '<?php echo base_url('admin/editmember/'); ?>'+ encodeURIComponent(its);
            } else { showError(json.message || 'Error'); }
          })
          .catch(()=> showError('Network error'));
      });
    });
  })();
</script>