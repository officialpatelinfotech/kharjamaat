<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin/ekramfunds'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Ekram Funds"><i class="fa-solid fa-arrow-left"></i></a>
  <h3 class="heading text-center mb-4">Create Corpus Fund</h3>
  <div class="card">
    <div class="card-body">
      <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="post" action="<?php echo site_url('admin/ekramfunds/create'); ?>">
        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required value="<?php echo isset($old['title'])?htmlspecialchars($old['title']):''; ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Amount (assigned per HOF) *</label>
          <input type="number" step="0.01" name="amount" class="form-control" required value="<?php echo isset($old['amount'])?htmlspecialchars($old['amount']):''; ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" rows="6" class="form-control"><?php echo isset($old['description'])?htmlspecialchars($old['description']):''; ?></textarea>
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Create & Assign to All HOFs</button>
        </div>
      </form>
    </div>
  </div>
</div>
