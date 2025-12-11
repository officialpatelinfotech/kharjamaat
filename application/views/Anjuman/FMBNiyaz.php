<!-- Custom CSS -->
<style>
  .custom-card.fmb-niyaz-blue {
    background: #e6f7ff;
    /* soft blue */
    border: none;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .custom-card.fmb-niyaz-green {
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
      <a href="<?php echo base_url("anjuman/fmbmodule") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h4 class="text-center mb-3">FMB Niyaz</h4>
  <div class="container mt-4 m-0">
    <div class="row">

      <div class="col-md-6 mb-4">
        <div class="card custom-card fmb-niyaz-green shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-success"></i>
            </div>
            <h4 class="card-title">Shehrullah Miqaat Invoice & Payment</h4>
            <p class="card-text">Generate Shehrullah Miqaat invoices & receive payment</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=1"); ?>" class="btn btn-sm btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Create Invoice
              </a>
              <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=1"); ?>" class="btn btn-sm btn-outline-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Update Invoice
              </a>
              <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=1"); ?>" class="btn btn-sm btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card fmb-niyaz-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">Ashara Miqaat Invoice & Payment</h4>
            <p class="card-text">Generate Ashara Miqaat invoices & receive payment</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=2"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Create Invoice
              </a>
              <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=2"); ?>" class="btn btn-sm btn-outline-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Update Invoice
              </a>
              <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=2"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card fmb-niyaz-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-primary"></i>
            </div>
            <h4 class="card-title">General Miqaat Invoice & Payment</h4>
            <p class="card-text">Generate General Miqaat invoices & receive payment</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=3"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Create Invoice
              </a>
              <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=3"); ?>" class="btn btn-sm btn-outline-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Update Invoice
              </a>
              <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=3"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card fmb-niyaz-green shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-success"></i>
            </div>
            <h4 class="card-title">Ladies Miqaat Invoice & Payment</h4>
            <p class="card-text">Generate Ladies Miqaat invoices & receive payment</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/generatemiqaatinvoice?miqaat_type=4"); ?>" class="btn btn-sm btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Create Invoice
              </a>
              <a href="<?php echo base_url("anjuman/updatemiqaatinvoice?miqaat_type=4"); ?>" class="btn btn-sm btn-outline-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Update Invoice
              </a>
              <a href="<?php echo base_url("anjuman/miqaatinvoicepayment?miqaat_type=4"); ?>" class="btn btn-sm btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card custom-card fmb-niyaz-green shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-success"></i>
            </div>
            <h4 class="card-title">FMB Extra Niyaz Contribution</h4>
            <p class="card-text">Generate Extra Niyaz invoices & receive payment</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/fmbgeneralcontribution/2"); ?>" class="btn btn-sm btn-success mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice me-2"></i> Receive Extra Niyaz Payment
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>