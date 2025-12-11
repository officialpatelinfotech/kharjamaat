<div class="container margintopcontainer pt-5">
  <a href="<?php echo site_url('admin/corpusfunds'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Corpus Funds Menu"><i class="fa fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Create Corpus Fund</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-success p-2"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger p-2"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="post" action="<?php echo site_url('admin/corpusfunds_create'); ?>">
        <div class="form-group mb-3">
          <label class="small">Title *</label>
          <input type="text" name="title" class="form-control form-control-sm" required>
        </div>
        <div class="form-group mb-3">
          <label class="small">Amount (assigned per HOF) *</label>
          <input type="number" step="0.01" name="amount" class="form-control form-control-sm" required>
        </div>
        <div class="form-group mb-3">
          <label class="small">Description</label>
          <textarea name="description" rows="4" class="form-control form-control-sm"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Create & Assign to All HOFs</button>
      </form>
    </div>
  </div>
</div>