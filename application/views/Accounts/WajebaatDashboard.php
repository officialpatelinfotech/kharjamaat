<?php
// Dashboard page that shows the wajebaat card prominently and additional details
?>
<div class="container margintopcontainer pt-5">
  <div class="d-flex align-items-center mb-3">
    <a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-arrow-left"></i></a>
    <h4 class="m-0">My Wajebaat Dashboard</h4>
  </div>

  <div class="row mt-4">
    <div class="col-md-6">
      <?php $this->load->view('Accounts/_wajebaat_card', ['wajebaat' => $wajebaat, 'corpus_details' => $corpus_details ?? []]); ?>
    </div>
    <div class="col-md-6">
      <div class="dashboard-card">
        <div class="card-header"><span>Wajebaat Details</span></div>
        <div class="card-body">
          <?php if (!empty($wajebaat)) : ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($wajebaat['Full_Name'] ?? ($_SESSION['user_data']['First_Name'] . ' ' . $_SESSION['user_data']['Surname'])); ?></p>
            <p><strong>ITS ID:</strong> <?php echo htmlspecialchars($wajebaat['ITS_ID'] ?? ''); ?></p>
            <p><strong>Amount:</strong> ₹ <?php echo htmlspecialchars(format_inr($wajebaat['amount'] ?? 0, 2)); ?></p>
            <p><strong>Due:</strong> <span class="text-danger">₹ <?php echo htmlspecialchars(format_inr($wajebaat['due'] ?? 0, 2)); ?></span></p>
            <p><strong>Last updated:</strong> <?php
                $last = '';
                if (!empty($wajebaat['updated_at'])) $last = $wajebaat['updated_at'];
                elseif (!empty($wajebaat['created_at'])) $last = $wajebaat['created_at'];
                echo $last ? date('d-m-Y H:i', strtotime($last)) : 'Not available';
              ?></p>
          <?php else: ?>
            <div class="text-muted">No wajebaat record found.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
// Wajebaat dashboard removed — kept as placeholder to avoid broken include paths.
// If you want this file fully deleted, remove or rename it from the filesystem.
?>
