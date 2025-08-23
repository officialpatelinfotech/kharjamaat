<!-- Custom CSS -->
<style>
  .custom-card {
    border: none;
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
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

  <h2 class="mb-4 text-center">FMB Module</h2>
  <div class="row mb-4 p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman") ?>" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
  </div>
  <div class="container mt-4 m-0">
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-bowl-food fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">FMB Thaali</h4>
            <p class="card-text">Manage Takhmeen, General Contributions, and Payments for Thaali.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/fmbthaali"); ?>" class="btn btn-primary">
                <i class="fa-solid fa-table-list me-2"></i> Takhmeen
              </a>
              <a href="<?php echo base_url("anjuman/fmbgeneralcontribution/1"); ?>" class="btn btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> General Contribution
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- FMB Niyaz Card -->
      <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-hand-holding-heart fa-2x text-danger"></i>
            </div>
            <h4 class="card-title">FMB Niyaz</h4>
            <p class="card-text">Manage Niyaz contributions and invoices.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/fmbgeneralcontribution/2"); ?>" class="btn btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Generate Invoice
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>