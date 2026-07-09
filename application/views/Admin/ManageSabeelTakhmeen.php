<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  .sabeel-options-page {
    font-family: 'Outfit', sans-serif;
    color: #1e293b;
    padding-top: 4rem;
    padding-bottom: 4rem;
  }

  .sabeel-options-page .back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    transition: all 0.25s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
  }

  .sabeel-options-page .back-btn:hover {
    color: #0f172a;
    background: #f8fafc;
    border-color: #cbd5e1;
    transform: translateX(-3px);
  }

  .sabeel-options-page .page-header {
    margin-top: 2rem;
    margin-bottom: 3.5rem;
    text-align: center;
  }

  .sabeel-options-page .page-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
    margin-bottom: 0.5rem;
  }

  .sabeel-options-page .page-subtitle {
    color: #64748b;
    font-size: 1.05rem;
    font-weight: 400;
  }

  .sabeel-options-page .option-card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 20px;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01);
    overflow: hidden;
    position: relative;
    height: 100%;
  }

  .sabeel-options-page .option-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    transition: all 0.3s ease;
  }

  .sabeel-options-page .card-primary::before {
    background: linear-gradient(90deg, #3b82f6, #60a5fa);
  }

  .sabeel-options-page .card-success::before {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .sabeel-options-page .option-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.02);
    border-color: rgba(203, 213, 225, 0.5);
  }

  .sabeel-options-page .icon-container {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
  }

  .sabeel-options-page .card-primary .icon-container {
    background: rgba(59, 130, 246, 0.08);
    color: #2563eb;
  }

  .sabeel-options-page .card-success .icon-container {
    background: rgba(16, 185, 129, 0.08);
    color: #059669;
  }

  .sabeel-options-page .option-card:hover .icon-container {
    transform: scale(1.1) rotate(3deg);
  }

  .sabeel-options-page .card-title {
    font-size: 1.35rem;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 0.85rem;
  }

  .sabeel-options-page .card-desc {
    color: #475569;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
  }

  .sabeel-options-page .action-link {
    display: inline-flex;
    align-items: center;
    font-size: 0.95rem;
    font-weight: 600;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    transition: all 0.25s ease;
    text-decoration: none !important;
  }

  .sabeel-options-page .card-primary .action-link {
    background: #f0f6ff;
    color: #2563eb;
  }

  .sabeel-options-page .card-primary .action-link:hover {
    background: #2563eb;
    color: #ffffff;
  }

  .sabeel-options-page .card-success .action-link {
    background: #ecfdf5;
    color: #059669;
  }

  .sabeel-options-page .card-success .action-link:hover {
    background: #059669;
    color: #ffffff;
  }

  .sabeel-options-page .action-link i {
    transition: transform 0.25s ease;
  }

  .sabeel-options-page .action-link:hover i {
    transform: translateX(4px);
  }
</style>

<div class="container sabeel-options-page">
  <div class="p-0">
    <a href="<?php echo base_url("admin"); ?>" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
  </div>
  
  <div class="page-header">
    <h1 class="page-title">Sabeel Takhmeen Options</h1>
    <p class="page-subtitle">Configure, adjust, and review Sabeel allocations and grades</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5 mb-4 d-flex">
      <div class="card option-card card-primary w-100">
        <div class="card-body d-flex flex-column p-4">
          <div>
            <div class="icon-container">
              <i class="fa-solid fa-layer-group"></i>
            </div>
            <h3 class="card-title">Manage Sabeel Takhmeen</h3>
            <p class="card-desc">View, assign, and update Establishment, Residential, and Mutawatteneen Sabeel Takhmeen allocations for all members.</p>
          </div>
          <div class="mt-auto">
            <a href="<?php echo base_url('admin/sabeeltakhmeendashboard'); ?>" class="action-link stretched-link">
              Open Sabeel Takhmeen <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-5 mb-4 d-flex">
      <div class="card option-card card-success w-100">
        <div class="card-body d-flex flex-column p-4">
          <div>
            <div class="icon-container">
              <i class="fa-solid fa-ranking-star"></i>
            </div>
            <h3 class="card-title">Sabeel Grade Master</h3>
            <p class="card-desc">Configure and adjust yearly grade slabs, base monthly rates, and yearly amounts for all Sabeel categories.</p>
          </div>
          <div class="mt-auto">
            <a href="<?php echo base_url('admin/sabeelgrade'); ?>" class="action-link stretched-link">
              Manage Grades <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>