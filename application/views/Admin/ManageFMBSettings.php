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
  <h4 class="heading text-center mb-4">FMB Settings</h4>
  <div class="fmb-card-container">
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-calendar-days"></i></span>
      <div class="fmb-card-title">Manage Miqaat</div>
      <a href="<?php echo base_url('common/managemiqaat?from=admin'); ?>" class="fmb-card-link">Go to Miqaat</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-utensils"></i></span>
      <div class="fmb-card-title">Manage Thaali Menu</div>
      <a href="<?php echo base_url('common/fmbthaalimenu?from=admin'); ?>" class="fmb-card-link">Go to Thaali Menu</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
      <div class="fmb-card-title">Manage Takhmeen</div>
      <a href="<?php echo base_url('admin/managefmbtakhmeen'); ?>" class="fmb-card-link">Go to Takhmeen</a>
    </div>
    <div class="fmb-card">
      <span class="fmb-card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
      <div class="fmb-card-title">Manage General Contribution</div>
      <a href="<?php echo base_url('admin/fmbgeneralcontributionmaster'); ?>" class="fmb-card-link">Go to General Contribution</a>
    </div>
  </div>
</div>
<div class="modal fade" id="add-takhmeen-container" tabindex="-1" aria-labelledby="add-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-takhmeen-container-label">Add FMB Takhmeen Amount</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo base_url("admin/addfmbtakhmeenamount"); ?>">
          <input type="hidden" name="user_id" id="user-id">
          <p><b>Mumineen Name: <span id="user-name">Member Name</span></b></p>
          <label for="takhmeen-year" class="form-label">Takhmeen Year</label>
          <select name="fmb_takhmeen_year" id="takhmeen-year" class="form-control" required>
            <option value="">-----</option>
            <option value="1446-09|1447-09">1446-09 / 1447-09</option>
            <option value="1447-09|1448-09">1447-09 / 1448-09</option>
            <option value="1448-09|1449-09">1448-09 / 1449-09</option>
            <option value="1449-09|1450-09">1449-09 / 1450-09</option>
            <option value="1450-09|1451-09">1450-09 / 1451-09</option>
            <option value="1451-09|1452-09">1451-09 / 1452-09</option>
            <option value="1452-09|1453-09">1452-09 / 1453-09</option>
            <option value="1453-09|1454-09">1453-09 / 1454-09</option>
            <option value="1454-09|1455-09">1454-09 / 1455-09</option>
            <option value="1455-09|1456-09">1455-09 / 1456-09</option>
            <option value="1456-09|1457-09">1456-09 / 1457-09</option>
            <option value="1457-09|1458-09">1457-09 / 1458-09</option>
          </select>
          <br>
          <label for="takhmeen-amount" class="form-label">Takhmeen Amount</label>
          <input type="number" id="takhmeen-amount" name="fmb_takhmeen_amount" class="form-control" value="" placeholder="Enter Takhmeen Amount" min="1" required>
          <br>
          <button type="submit" id="add-takhmeen-btn" class="btn btn-primary text-right">Add Takhmeen</button>
          <p id="validate-takhmeen" class="text-secondary pt-3 m-0"></p>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="update-takhmeen-modal" tabindex="-1" role="dialog" aria-labelledby="updateTakhmeenLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateTakhmeenLabel">Update FMB Takhmeen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="update-takhmeen-form" method="post" action="<?php echo base_url('Admin/updatefmbtakhmeen/1') ?>">
        <div class="modal-body">
          <input type="hidden" name="takhmeen_id" id="edit-takhmeen-id">
          <input type="hidden" name="user_id" id="edit-user-id">
          <div class="form-group">
            <label>Member</label>
            <p id="edit-user-name" class="font-weight-bold mb-0"></p>
          </div>
          <div class="form-group">
            <label>Financial Year</label>
            <input type="text" name="year" id="edit-takhmeen-year" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="edit-takhmeen-amount">Takhmeen Amount</label>
            <input type="number" name="fmb_takhmeen_amount" id="edit-takhmeen-amount" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Takhmeen</button>
        </div>
      </form>
    </div>
  </div>
</div>