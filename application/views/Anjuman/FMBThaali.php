<!-- Custom CSS -->
<style>
  .custom-card.fmb-thaali-blue {
    background: #e6f7ff;
    /* soft blue */
    border: none;
    border-radius: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .custom-card.fmb-thaali-green {
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
  <h4 class="text-center mb-3">FMB Thaali</h4>
  <div class="container mt-4 m-0">
    <div class="row">

      <div class="col-md-4 mb-4">
        <div class="card custom-card fmb-thaali-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-bowl-food fa-2x text-primary"></i>
            </div>
            <h5 class="card-title">Receive Thaali Takhmeen Payment</h5>
            <p class="card-text">Receive Thaali Takhmeen Payments</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/fmbthaalitakhmeen"); ?>" class="btn btn-sm btn-primary text-white">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Receive Payment
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="col-md-4 mb-4">
        <div class="card custom-card fmb-thaali-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-primary"></i>
            </div>
            <h5 class="card-title">FMB Thaali Signup</h5>
            <p class="card-text">View Signup Details</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("common/fmbsignup?from=anjuman/fmbthaali"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Go to Signup Details
              </a>
            </div>
          </div>
        </div>
      </div> -->

      <div class="col-md-4 mb-4">
        <div class="card custom-card fmb-thaali-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-primary"></i>
            </div>
            <h5 class="card-title">FMB Thaali Delivery</h5>
            <p class="card-text">Manage Thaali Delivery Person & Substitutions</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("common/deliverydashboard?from=anjuman/fmbthaali"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Go to Delivery Dashboard
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card custom-card fmb-thaali-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-money-bill-wheat fa-2x text-primary"></i>
            </div>
            <h5 class="card-title">Manage FMB Menu</h5>
            <p class="card-text">Manage FMB Thaali Menu Items</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("common/fmbthaalimenu?from=anjuman/fmbthaali"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Go to FMB Menu
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card custom-card fmb-thaali-blue shadow-sm h-100">
          <div class="card-body text-center">
            <div class="icon-box mb-3">
              <i class="fa-solid fa-bowl-food fa-2x text-primary"></i>
            </div>
            <h5 class="card-title">FMB Extra Thaali Contribution</h5>
            <p class="card-text">Generate Extra Thaali invoices & receive payments</p>

            <div class="d-flex justify-content-center gap-2 flex-wrap">
              <a href="<?php echo base_url("anjuman/fmbgeneralcontribution/1"); ?>" class="btn btn-sm btn-primary mt-2 mt-md-0 ml-md-2">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Receive Extra Thaali Payment
              </a>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>