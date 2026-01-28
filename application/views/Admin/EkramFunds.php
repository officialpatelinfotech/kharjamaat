<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Admin Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Ekram Funds</h4>
  <div class="row g-3 align-items-stretch ekram-cards-row">
    <div class="col-12 col-md-4 d-flex">
      <a href="<?php echo site_url('admin/ekramfunds/new'); ?>" id="ekram-new-fund-link" class="text-decoration-none w-100" data-target="<?php echo site_url('admin/ekramfunds/new'); ?>">
        <div class="card shadow-sm h-100 ekram-card ekram-card-primary" style="cursor:pointer;">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-plus-circle fa-3x text-primary"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Assign New Fund</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50">Define a new ekram fund and auto-assign its amount to all HOFs.</p>
            <div class="text-center">
              <span id="ekram-new-fund-btn" class="btn btn-sm btn-primary px-3">Go to Form &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-4 d-flex">
      <a href="<?php echo site_url('admin/ekramfunds_list'); ?>" class="text-decoration-none w-100">
        <div class="card shadow-sm h-100 ekram-card ekram-card-secondary">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-database fa-3x text-secondary"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Created Funds</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50">Browse existing ekram funds and review their amounts.</p>
            <div class="text-center">
              <span class="btn btn-sm btn-outline-secondary px-3">View Funds &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-12 col-md-4 d-flex">
      <a href="<?php echo site_url('admin/ekramfunds_hofs'); ?>" class="text-decoration-none w-100">
        <div class="card shadow-sm h-100 ekram-card ekram-card-info">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-users fa-3x text-info"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Assigned Funds</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50">View the funds which have been assigned.</p>
            <div class="text-center">
              <span class="btn btn-sm btn-outline-info px-3">View List &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  </div>

</div>
<style>
  .ekram-card { border: none; position: relative; overflow: hidden; }
  .ekram-card:before { content:''; position:absolute; inset:0; opacity:0.08; }
  /* Plain accessible color theme (no gradients) */
  .ekram-card-primary { background:#fff9e6; }    /* soft warm */
  .ekram-card-secondary { background:#e8f4ff; }  /* soft blue */
  .ekram-card-info { background:#e9f8ef; }       /* soft green */
  .ekram-card { box-shadow: 0 1px 3px rgba(0,0,0,.08); border:1px solid #e2e6ea; }
  .ekram-cards-row .card:hover { box-shadow:0 4px 14px rgba(0,0,0,.18); }
  .ekram-card h5, .ekram-card p { color:#212529; }
  .ekram-card .btn { font-weight:500; }
  .ekram-card h5 { letter-spacing:.5px; }
  .ekram-card p { color:#333; }
  .ekram-cards-row .card { transition: transform .15s ease, box-shadow .15s ease; }
  .ekram-cards-row .card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
  @media (max-width: 767.98px){ .ekram-card { min-height: 220px; } }
</style>
