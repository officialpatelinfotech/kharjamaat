<!-- Custom CSS -->
<style>
  .custom-card.laagat-blue {
    background: #e6f7ff;
    /* soft blue */
    border: none;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .custom-card.laagat-green {
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
  <div class="p-0 mb-4">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h4 class="text-center mb-3">Laagat & Rent Module</h4>
  <div class="container mt-4 m-0">
    <div class="row">

      <div class="col-md-6 mb-4">
        <div class="card custom-card laagat-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-file-invoice fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">Update Laagat & Rent invoices</h4>
            <p class="card-text">Manage all generated invoices & update amounts </p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/laagat_rent_invoices"); ?>" class="btn btn-sm btn-primary text-white">
                <i class="fa-solid fa-list me-2"></i> Update invoices
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card laagat-green shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-transfer fa-2x text-success"></i>
            </div>
            <h4 class="card-title">Receive Laagat & Rent Payment</h4>
            <p class="card-text">Receive payments against invoices and view history</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/laagat_rent_payments"); ?>" class="btn btn-sm btn-success text-white">
                <i class="fa-solid fa-cash-register me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>
