<?php /* Import Members View */ ?>
<style>
  .import-layout { max-width: 880px; }
  .drop-zone { border:2px dashed #ced4da; border-radius:8px; padding:28px 22px; text-align:center; transition:.25s; background:#fafafa; }
  .drop-zone.dragover { background:#e9f5ff; border-color:#66afe9; }
  .drop-zone input[type=file] { display:none; }
  .drop-zone .dz-instructions { font-size:.8rem; color:#6c757d; margin-top:.5rem; }
  .sample-pill { font-size:.65rem; }
  .import-meta-list { columns:2; -webkit-columns:2; -moz-columns:2; font-size:.7rem; line-height:1.15rem; }
  @media (max-width:600px){ .import-meta-list{ columns:1; } }
</style>
<div class="container margintopcontainer pt-4 mb-5 import-layout">
  <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
    <h5 class="mb-2 mb-sm-0">Import Latest Members Data</h5>
    <div class="btn-group btn-group-sm">
      <a href="<?php echo base_url('admin/exportmembers'); ?>" class="btn btn-outline-success"><i class="fa fa-download me-1"></i>Export Current</a>
      <a href="<?php echo base_url('admin/managemembers'); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
  </div>
  <div class="alert alert-info py-2 px-3 small mb-3">
    Provide a CSV file. Required columns: <code>ITS_ID</code>, <code>Full_Name</code>. Non-empty cells update existing data, empty cells leave existing values untouched.
  </div>
  <div class="card shadow-sm mb-4">
    <div class="card-body p-3">
      <form id="importForm" method="post" enctype="multipart/form-data" class="">
        <div class="drop-zone mb-3" id="dropZone">
          <input id="csvInput" type="file" name="data_file" accept=".csv,text/csv" required>
          <p class="m-0"><strong>Select or Drop CSV Here</strong></p>
          <div class="dz-instructions">Click to browse or drag a .csv file (UTF-8 recommended)</div>
          <div id="dzFileName" class="small text-primary mt-2" style="display:none;"></div>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
          <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-upload me-1"></i>Import</button>
          <button type="button" id="clearFileBtn" class="btn btn-outline-danger btn-sm mr-2">Clear</button>
          <a href="<?php echo base_url('admin/exportmembers'); ?>?status=active&template=1" class="btn btn-outline-dark btn-sm" title="Download a 5-row sample template">Download 5-Row Template</a>
        </div>
        <details class="mb-2">
          <summary class="small fw-semibold">Show Common Optional Columns</summary>
          <div class="import-meta-list mt-2">
            ITS_ID, Full_Name, Full_Name_Arabic, First_Name, Surname, Member_Type, member_type, Mobile, Email, WhatsApp_No, Gender, Age, Sector, Sub_Sector, Marital_Status, Blood_Group, Address, City, Pincode, Housing, Occupation, Qualification, Languages, Hunars, Misaq, Warakatul_Tarkhis, Date_Of_Nikah, Inactive_Status
          </div>
        </details>
      </form>
    </div>
  </div>
  <?php if(isset($summary) && $summary): ?>
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-light"><strong class="small text-uppercase">Import Summary</strong></div>
      <div class="card-body small">
        <ul class="mb-2">
          <li>Processed: <strong><?php echo (int)$summary['processed']; ?></strong></li>
          <li>Inserted: <strong class="text-success"><?php echo (int)$summary['inserted']; ?></strong></li>
          <li>Updated: <strong class="text-primary"><?php echo (int)$summary['updated']; ?></strong></li>
          <li>Skipped: <strong class="text-warning"><?php echo (int)$summary['skipped']; ?></strong></li>
          <?php if(isset($summary['moved_out'])): ?>
            <li>Marked Moved-Out: <strong class="text-danger"><?php echo (int)$summary['moved_out']; ?></strong></li>
          <?php endif; ?>
        </ul>
        <?php if(!empty($summary['errors'])): ?>
          <div class="alert alert-danger p-2">
            <div class="fw-semibold mb-1">Errors / Warnings (<?php echo count($summary['errors']); ?>)</div>
            <ol class="mb-0 ps-3">
              <?php foreach($summary['errors'] as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
              <?php endforeach; ?>
            </ol>
          </div>
        <?php else: ?>
          <div class="alert alert-success p-2 mb-0">No critical errors encountered.</div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="small text-muted">Note: Large files (&gt;5MB) or thousands of rows may require chunked processing or a queued background job in future optimization.</div>
</div>
<script>
  (function(){
    var dz = document.getElementById('dropZone');
    var input = document.getElementById('csvInput');
    var fileNameEl = document.getElementById('dzFileName');
    var clearBtn = document.getElementById('clearFileBtn');
    function showName(){
      if(input.files && input.files.length){ fileNameEl.textContent = input.files[0].name; fileNameEl.style.display='block'; }
      else { fileNameEl.textContent=''; fileNameEl.style.display='none'; }
    }
    dz.addEventListener('click', function(){ input.click(); });
    input.addEventListener('change', showName);
    ;['dragenter','dragover'].forEach(function(evt){ dz.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dz.classList.add('dragover'); }); });
    ;['dragleave','drop'].forEach(function(evt){ dz.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); dz.classList.remove('dragover'); }); });
    dz.addEventListener('drop', function(e){ if(e.dataTransfer.files && e.dataTransfer.files.length){ input.files = e.dataTransfer.files; showName(); }});
    clearBtn.addEventListener('click', function(){ input.value=''; showName(); });
  })();
</script>
</div>
