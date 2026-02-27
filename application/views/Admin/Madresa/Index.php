<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Custom CSS -->
<style>
  .custom-card.madresa-blue {
    background: #e6f7ff;
    /* soft blue */
    border: none;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .custom-card.madresa-green {
    background: #e6ffe6;
    /* soft mint green */
    border: none;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .custom-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
  }

  .icon-box {
    background: #eef2f7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    border-radius: 50%;
  }

  .btn {
    border-radius: 5px;
    transition: all 0.2s ease-in-out;
  }

  .btn:hover {
    transform: scale(1.05);
  }
</style>

<div class="container margintopcontainer pt-5 pb-4 position-relative">
  <?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa'; ?>
  <?php $back_path = ($madresa_base === 'admin/madresa') ? 'admin' : $madresa_base; ?>
  
  <div class="p-0 mb-4">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url($back_path); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
    </div>
  </div>

  <h4 class="text-center mb-3">Madresa Module</h4>

  <div class="container mt-4 m-0">
    <div class="row">

      <div class="col-md-6 mb-4">
        <div class="card custom-card madresa-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-plus fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">Create Classes</h4>
            <p class="card-text">Add a new class for Madresa.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url($madresa_base . '/classes/new'); ?>" class="btn btn-sm btn-primary text-white">
                <i class="fa-solid fa-circle-plus me-2"></i> Go to Create Classes
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card madresa-green shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-list-check fa-2x text-success"></i>
            </div>
            <h4 class="card-title">Manage Classes</h4>
            <p class="card-text">View and manage existing classes.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url($madresa_base . '/classes'); ?>" class="btn btn-sm btn-success text-white">
                <i class="fa-solid fa-list me-2"></i> Go to Manage Classes
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

