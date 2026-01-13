<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Admin Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Expense Module</h4>

  <div class="row g-3 align-items-stretch justify-content-center">
    <div class="col-12 col-md-6 d-flex">
      <a href="<?php echo site_url('admin/expense/source-of-funds'); ?>" class="text-decoration-none w-100">
        <div class="card shadow-sm h-100 expense-card">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-bank fa-3x text-primary"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Source of Funds</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50 text-center">Manage and review expense funding sources.</p>
            <div class="text-center">
              <span class="btn btn-sm btn-primary px-3">Open &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<style>
  .expense-card { background:#fff; border:1px solid #e2e6ea; }
  .expense-card { transition: transform .15s ease, box-shadow .15s ease; }
  .expense-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
</style>
