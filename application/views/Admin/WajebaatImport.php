<div class="container margintopcontainer">
  <div class="row align-items-center pt-3 mb-2">
    <div class="col-3 text-left">
      <a href="<?php echo base_url('admin/wajebaat'); ?>" class="btn btn-sm btn-outline-secondary" title="Back" aria-label="Back"><i class="fa fa-arrow-left"></i></a>
    </div>
    <div class="col-6 text-center">
      <h2 class="m-0">Import Latest Wajebaat Data</h2>
    </div>
    <div class="col-3 text-right">
      <a href="<?php echo base_url('admin/wajebaat_export'); ?>" class="btn btn-sm btn-success">&#x2B07; Export Current</a>
    </div>
  </div>
  <hr>

  <?php if (!empty($message)) : ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
  <?php endif; ?>
  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <div class="card p-3 mb-3">
    <div class="alert alert-info">Provide a CSV file. Required columns: <strong>ITS_ID, amount, due</strong>. Header row is optional; non-empty cells update/insert data.</div>

    <form method="post" enctype="multipart/form-data" id="wajebaat-import-form">
      <div id="dropzone" class="border rounded p-4 text-center" style="border-style: dashed!important; background:#f8f9fa;">
        <h4 class="mb-1">Select or Drop CSV Here</h4>
        <div class="text-muted">Click to browse or drag a .csv file (UTF-8 recommended)</div>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" style="display:none;" />
        <div id="drop-filename" class="mt-3"></div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary" id="import-btn">&#x2B07; Import</button>
        <button type="button" class="btn btn-outline-danger" id="clear-btn">Clear</button>
        <a href="<?php echo base_url('admin/wajebaat_template'); ?>" class="btn btn-outline-secondary">Download 5-Row Template</a>
      </div>
    </form>

    <div class="mt-3">
      <a class="d-block" data-toggle="collapse" href="#optionalCols" role="button" aria-expanded="false" aria-controls="optionalCols">Show Common Optional Columns</a>
      <div class="collapse mt-2" id="optionalCols">
        <div class="small text-muted">Optional columns may include additional metadata; only ITS_ID, amount and due are required for import.</div>
      </div>
    </div>

    <p class="text-muted mt-3">Note: Large files (&gt;5MB) or thousands of rows may require chunked processing or a queued background job.</p>
  </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function(){
  var dropzone = document.getElementById('dropzone');
  var fileInput = document.getElementById('csv_file');
  var filenameEl = document.getElementById('drop-filename');
  var clearBtn = document.getElementById('clear-btn');

  function setFileDisplay(file){
    if(!file) { filenameEl.innerText = '' ; return; }
    filenameEl.innerHTML = '<strong>' + file.name + '</strong> (' + Math.round(file.size/1024) + ' KB)';
  }

  dropzone.addEventListener('click', function(){ fileInput.click(); });

  fileInput.addEventListener('change', function(e){
    var f = e.target.files[0];
    setFileDisplay(f);
  });

  dropzone.addEventListener('dragover', function(e){ e.preventDefault(); dropzone.classList.add('bg-white'); });
  dropzone.addEventListener('dragleave', function(e){ e.preventDefault(); dropzone.classList.remove('bg-white'); });
  dropzone.addEventListener('drop', function(e){
    e.preventDefault(); dropzone.classList.remove('bg-white');
    var f = e.dataTransfer.files[0];
    if(f){ fileInput.files = e.dataTransfer.files; setFileDisplay(f); }
  });

  clearBtn.addEventListener('click', function(){ fileInput.value = ''; setFileDisplay(null); });
});
</script>
