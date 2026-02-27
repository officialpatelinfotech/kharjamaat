<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container margintopcontainer pt-5">
  <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Admin Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4">Laagat / Rent Module</h4>

  <div class="row g-3 align-items-stretch lr-cards-row justify-content-center">
    <div class="col-12 col-md-4 d-flex">
      <a href="<?php echo site_url('admin/laagat/create'); ?>" class="text-decoration-none w-100">
        <div class="card shadow-sm h-100 lr-card lr-card-primary">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-plus-circle fa-3x text-primary"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Create Laagat/Rent Form</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50">Create a new Laagat/Rent form for the selected Hijri year and applicable Raza category.</p>
            <div class="text-center">
              <span class="btn btn-sm btn-primary px-3">Go to Form &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-4 d-flex">
      <a href="<?php echo site_url('admin/laagat/manage'); ?>" class="text-decoration-none w-100">
        <div class="card shadow-sm h-100 lr-card lr-card-secondary">
          <div class="card-body d-flex flex-column">
            <div class="mb-3 text-center">
              <i class="fa fa-database fa-3x text-secondary"></i>
            </div>
            <h5 class="fw-semibold text-dark mb-2 text-center">Manage Laagat/Rent</h5>
            <p class="flex-grow-1 small mb-3 text-dark-50">Browse created forms, edit details, and activate/deactivate a year.</p>
            <div class="text-center">
              <span class="btn btn-sm btn-outline-secondary px-3">View Records &raquo;</span>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<style>
  .lr-card { border: none; position: relative; overflow: hidden; }
  .lr-card:before { content:''; position:absolute; inset:0; opacity:0.08; }
  .lr-card-primary { background:#fff9e6; }    /* soft warm */
  .lr-card-secondary { background:#e8f4ff; }  /* soft blue */
  .lr-card { box-shadow: 0 1px 3px rgba(0,0,0,.08); border:1px solid #e2e6ea; }
  .lr-cards-row .card:hover { box-shadow:0 4px 14px rgba(0,0,0,.18); }
  .lr-card h5, .lr-card p { color:#212529; }
  .lr-card .btn { font-weight:500; }
  .lr-card h5 { letter-spacing:.5px; }
  .lr-card p { color:#333; }
  .lr-cards-row .card { transition: transform .15s ease, box-shadow .15s ease; }
  .lr-cards-row .card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
  @media (max-width: 767.98px){ .lr-card { min-height: 220px; } }
</style>
