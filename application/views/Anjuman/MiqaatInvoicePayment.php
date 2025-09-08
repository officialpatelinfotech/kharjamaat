<!-- Custom CSS for cards (copied from FMBModule.php) -->
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
  <div class="p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman/fmbmodule") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h2 class="mb-4 text-center">Miqaat <span class="text-primary">Invoice & Payment</span></h2>
  <div class="container mt-4 m-0">
    <div class="row">
      <!-- Invoice Generation Card -->
      <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-file-invoice-dollar fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">Invoice Generation</h4>
            <p class="card-text">Generate invoices for Miqaat events and view invoice history.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url('anjuman/miqaatinvoice'); ?>" class="btn btn-primary">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Generate Invoice
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Manage Payment Card -->
      <div class="col-md-6 mb-4">
        <div class="card custom-card shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-check-dollar fa-2x text-success"></i>
            </div>
            <h4 class="card-title">Manage Payment</h4>
            <p class="card-text">Record and manage payments for Miqaat invoices.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url('anjuman/miqaatpayment'); ?>" class="btn btn-success">
                <i class="fa-solid fa-money-bill-wave me-2"></i> Manage Payment
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>