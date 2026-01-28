<div class="container margintopcontainer pt-5">
  <a href="<?php echo site_url('admin/ekramfunds'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Ekram Funds"><i class="fa fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Create Ekram Fund</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-success p-2"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger p-2"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php $old = isset($old) ? $old : []; ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" action="<?php echo site_url('admin/ekramfunds_create'); ?>">
        <div class="form-group mb-3">
          <label class="small">Hijri Year *</label>
          <select id="hijri_year_select" name="hijri_year" class="form-control form-control-sm" required>
            <option value="">Select Year</option>
            <?php for ($y = 1442; $y <= 1457; $y++): ?>
              <option value="<?php echo $y; ?>" <?php echo (isset($old['hijri_year']) && (int)$old['hijri_year'] === $y) ? 'selected' : ''; ?>><?php echo $y; ?></option>
            <?php endfor; ?>
          </select>
          <div id="hijri-year-alert" class="mt-2" style="display:none;"></div>
        </div>
        <div class="form-group mb-3">
          <label class="small">Amount (assigned per HOF) *</label>
          <input type="number" step="0.01" name="amount" class="form-control form-control-sm" required value="<?php echo htmlspecialchars($old['amount'] ?? ''); ?>">
        </div>
        <div class="form-group mb-3">
          <label class="small">Description</label>
          <textarea name="description" rows="4" class="form-control form-control-sm"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>
        <button id="ekram-create-submit" type="submit" class="btn btn-primary btn-sm">Create & Assign to All HOFs</button>
      </form>
    </div>
  </div>
</div>
<script>
  (function(){
    var select = document.getElementById('hijri_year_select');
    var alertDiv = document.getElementById('hijri-year-alert');
    var submitBtn = document.getElementById('ekram-create-submit');
    if (!select || !alertDiv || !submitBtn) return;
    function showAlert(msg, type) {
      alertDiv.style.display = 'block';
      alertDiv.className = 'alert alert-' + (type || 'warning') + ' p-2';
      alertDiv.textContent = msg;
    }
    function clearAlert(){ alertDiv.style.display = 'none'; alertDiv.className=''; alertDiv.textContent = ''; }
    var pending = null;
    function checkYear(year, cb) {
      if (!year) { cb(null); return; }
      // debounce multiple rapid changes
      if (pending) clearTimeout(pending);
      pending = setTimeout(function(){
        fetch('<?php echo site_url('admin/ekramfunds_check_duplicate'); ?>?hijri_year=' + encodeURIComponent(year), { credentials: 'same-origin' })
          .then(function(resp){ return resp.json(); })
          .then(function(json){ cb(json); })
          .catch(function(){ cb(null); });
      }, 250);
    }
    // on change, check and disable submit if exists
    select.addEventListener('change', function(){
      var val = select.value;
      if (!val) { clearAlert(); submitBtn.disabled = false; return; }
      checkYear(val, function(json){
        if (!json || json.success !== true) { clearAlert(); submitBtn.disabled = false; return; }
        if (json.exists === true) {
          showAlert('A fund already exists for Hijri year ' + (json.hijri_year || val) + '. You should not assign for the same year.', 'danger');
          submitBtn.disabled = true;
        } else {
          clearAlert(); submitBtn.disabled = false;
        }
      });
    });
    // final check on submit to prevent race condition
    var form = select && select.form;
    if (form) {
      form.addEventListener('submit', function(e){
        var val = select.value;
        if (!val) return; // let HTML required handle
        e.preventDefault();
        checkYear(val, function(json){
          if (json && json.success === true && json.exists === true) {
            showAlert('A fund already exists for Hijri year ' + (json.hijri_year || val) + '. You should not assign for the same year.', 'danger');
            submitBtn.disabled = true;
            return;
          }
          // proceed
          form.submit();
        });
      });
    }
  })();
</script>