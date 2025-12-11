<style>
  body {
    min-height: 100vh;
    /* background: linear-gradient(135deg, #e6fef7 0%, #c6ecd9 100%); */
  }

  .fmb-card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
    margin-top: 3rem;
  }

  .fmb-card {
    flex: 1 1 20%;
    min-width: 250px;
    max-width: 300px;
    background: rgb(0, 106, 63);
    border-radius: 18px;
    box-shadow: 0 6px 32px rgba(0, 106, 63, 0.10);
    padding: 2.5rem 1.5rem 2rem 1.5rem;
    text-align: center;
    transition: box-shadow 0.2s, transform 0.2s, background 0.2s;
    position: relative;
    overflow: hidden;
    color: #fff;
    border: 2px solid rgb(0, 106, 63);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    height: 300px;
  }

  .fmb-card:hover {
    box-shadow: 0 12px 40px rgba(0, 106, 63, 0.18);
    transform: translateY(-4px) scale(1.04);
    background: #005c38;
    border-color: #005c38;
    color: #fff;
  }

  .fmb-card-icon {
    font-size: 2.8rem;
    margin-bottom: 1.1rem;
    color: #fff;
    background: #005c38;
    border-radius: 50%;
    padding: 0.7rem;
    box-shadow: 0 2px 8px rgba(0, 106, 63, 0.08);
    display: inline-block;
  }

  .fmb-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
    color: #fff;
    letter-spacing: 0.5px;
    text-shadow: 0 2px 8px rgba(0, 106, 63, 0.10);
  }

  .fmb-card-link {
    display: inline-block;
    margin-top: auto;
    margin-bottom: 0;
    padding: 0.2rem 0rem;
    font-size: 1rem;
    border-radius: 8px;
    background: #fff;
    color: rgb(0, 106, 63);
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 106, 63, 0.08);
    border: 2px solid rgb(0, 106, 63);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  }

  .fmb-card-link:hover {
    background: #005c38;
    color: #fff;
    box-shadow: 0 4px 16px rgba(0, 106, 63, 0.15);
    border-color: #fff;
    text-decoration-line: none;
  }

  .back-btn {
    display: inline-block;
    padding: 0.5rem 1.5rem;
    font-size: 1rem;
    border-radius: 8px;
    background: #fff;
    color: rgb(0, 106, 63);
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 106, 63, 0.08);
    border: 2px solid rgb(0, 106, 63);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
  }

  .back-btn:hover {
    background: #005c38;
    color: #fff;
    box-shadow: 0 4px 16px rgba(0, 106, 63, 0.15);
    border-color: #fff;
  }
</style>
<div class="container margintopcontainer pt-5">
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url('admin'); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
    </div>
  </div>
  <h4 class="heading text-center mb-4">Manage FMB Thaali & Niyaz</h4>
  <div class="fmb-card-container">
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-calendar-days"></i></span>
      <div class="fmb-card-title">Create & Manage Miqaat</div>
      <a href="<?php echo base_url('common/managemiqaat?from=admin'); ?>" class="fmb-card-link">Go to Miqaat</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-utensils"></i></span>
      <div class="fmb-card-title">Manage Thaali Menu</div>
      <a href="<?php echo base_url('common/fmbthaalimenu?from=admin/managefmbsettings'); ?>" class="fmb-card-link">Go to Thaali Menu</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
      <div class="fmb-card-title">Manage Thaali Takhmeen</div>
      <a href="<?php echo base_url('admin/managefmbtakhmeen'); ?>" class="fmb-card-link">Go to Takhmeen</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
      <div class="fmb-card-title">Manage Extra Contribution Master</div>
      <a href="<?php echo base_url('admin/fmbgeneralcontributionmaster'); ?>" class="fmb-card-link">Go to Master</a>
    </div>
  </div>
</div>