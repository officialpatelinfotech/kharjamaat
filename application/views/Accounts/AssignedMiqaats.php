<?php
// Show only upcoming miqaats in the assigned list
if (isset($miqaats) && is_array($miqaats)) {
  $today = strtotime('today');
  $miqaats = array_values(array_filter($miqaats, function ($m) use ($today) {
    $dateStr = null;
    if (is_array($m)) {
      $dateStr = $m['date'] ?? $m['miqaat_date'] ?? $m['event_date'] ?? $m['start_date'] ?? null;
    } elseif (is_object($m)) {
      $dateStr = $m->date ?? $m->miqaat_date ?? $m->event_date ?? $m->start_date ?? null;
    }
    if (!empty($dateStr)) {
      $d = strtotime($dateStr);
      if ($d !== false) {
        return ($d >= $today);
      }
    }
    return false;
  }));
}
?>
<style>
  .miqaat-status-badge {
    font-size: 0.875rem;
    padding: 5px;
    border-radius: 0.375rem;
    margin: 0;
    margin-left: 0.5rem;
    position: relative;
    top: -4px;
  }
</style>
<div class="container margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
      <?= $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?= $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning">
      <?= $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>

  <div class="mb-4 mb-md-0 pt-5">
    <a href="<?php echo base_url('accounts/home') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <div>
    <h3 class="text-center text-2xl font-semibold mb-4">Assigned Miqaats</h3>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php if (!empty($miqaats)) {
      $sr = 1;
      foreach ($miqaats as $miqaat) { ?>
        <div class="p-4 mt-3 rounded rounded-xl shadow-sm border border-gray-200 bg-white hover:shadow-md transition">
          <h4 class="text-lg font-semibold text-gray-800 mb-3">
            Miqaat Name: <?php echo htmlspecialchars($miqaat['name']); ?>
            <?php
            if (isset($miqaat['invoice_status']) && $miqaat['invoice_status']) {
              echo '<span class="miqaat-status-badge badge badge-success">Completed</span>';
            } else {
              if ($miqaat['status'] == 0) {
                echo '<span class="miqaat-status-badge badge badge-secondary">Inactive</span>';
              } elseif ($miqaat['status'] == 1) {
                echo '<span class="miqaat-status-badge badge badge-success">Active</span>';
              } elseif ($miqaat['status'] == 2) {
                echo '<span class="miqaat-status-badge badge badge-warning">Cancelled</span>';
              }
            }
            ?>
          </h4>
          <p class="text-sm text-gray-500"><strong>Eng Date:</strong> <?php echo date('d M Y', strtotime($miqaat['date'])); ?></p>
          <p class="text-sm text-gray-500"><strong>Hijri Date:</strong> <?php echo htmlspecialchars($miqaat['hijri_date']); ?></p>
          <p class="text-sm text-gray-500"><strong>Miqaat Type:</strong> <?php echo htmlspecialchars($miqaat['type']); ?></p>
          <p class="text-sm text-gray-500"><strong>Assigned To:</strong> <?php echo htmlspecialchars($miqaat['assigned_to']); ?></p>

          <?php if (!empty($miqaat['assignments'])): ?>
            <div class="mt-3">
              <div class="font-semibold text-gray-700 mb-1"><strong>Assignments:</strong></div>
              <ul class="list-disc list-inside text-sm text-gray-600">
                <?php foreach ($miqaat['assignments'] as $assignment): ?>
                  <?php if ($assignment['assign_type'] === 'Individual'): ?>
                    <li>
                      <span class="font-medium"></span>
                      <?php echo htmlspecialchars($assignment['member_name']); ?> (ID: <?php echo htmlspecialchars($assignment['member_id']); ?>)
                    </li>
                  <?php elseif ($assignment['assign_type'] === 'Group'): ?>
                    <li>
                      <span class="font-medium"><strong>Group:</strong></span> <?php echo htmlspecialchars($assignment['group_name']); ?>
                      <br>
                      <strong>Leader:</strong> <?php echo htmlspecialchars($assignment['group_leader_name']); ?> (ID: <?php echo htmlspecialchars($assignment['group_leader_id']); ?>)
                      <?php if (!empty($assignment['co_leader_name'])): ?>
                        <br>
                        <strong>Co-Leader:</strong> <?php echo htmlspecialchars($assignment['co_leader_name']); ?> (ID: <?php echo htmlspecialchars($assignment['co_leader_id']); ?>)
                      <?php endif; ?>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <p class="text-sm text-gray-500"><strong>Raza Status:</strong>
            <?php if (isset($miqaat['raza'])) {
              if ($miqaat["raza"]["Janab-status"] == 1) {
                echo '<span class="badge badge-success">Approved</span>';
              } else if ($miqaat["raza"]["coordinator-status"] == 1) {
                echo '<span class="badge badge-warning">Recommended</span>';
              } else {
                echo '<span class="badge badge-primary">Submitted</span>';
              }
            } else {
              echo '<span class="badge badge-secondary">Not Submitted</span>';
            } ?>
          </p>

          <?php if ($miqaat['status'] == 1 && !isset($miqaat['raza'])): ?>
            <a href="<?php echo base_url('accounts/submit_miqaat_raza/' . $miqaat['id']) ?>" class="raza-submit-btn btn btn-sm btn-success">Submit Raza</a>
          <?php else: ?>
            <a class="btn btn-sm btn-secondary disabled" style="pointer-events: none; opacity: 0.6;">Submit Raza</a>
          <?php endif; ?>
        </div>
      <?php }
    } else { ?>
      <div class="col-span-2 d-flex justify-content-center py-8">
        <div class="alert-info text-center w-100 p-5">
          <h5 class="mb-2">You can apply for Miqaat Raza only if you have been assigned a Miqaat</h5>
          <p class="mb-0 text-muted">You haven't been assigned any Miqaat yet</p>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
<script>
  $(".alert").delay(3000).fadeOut(500);

</script>

<!-- Dues confirmation modal -->
<div id="dues-modal" class="modal" tabindex="-1" role="dialog" style="display:none; position:fixed; left:50%; top:10%; transform:translateX(-50%); z-index:1050;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pending Financial Dues</h5>
        <button type="button" class="close" aria-label="Close" onclick="hideDuesModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="dues-content">Loading dues...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="hideDuesModal()">Cancel</button>
        <button type="button" id="dues-confirm" class="btn btn-primary">Proceed</button>
      </div>
    </div>
  </div>
</div>
<div id="dues-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1040;"></div>

<script>
  // Helper to format INR without decimals
  function formatINR(n) {
    n = Math.round(Number(n) || 0);
    return '₹' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  function coloredAmount(n) {
    var amt = formatINR(n);
    if (Number(n) > 0) return '<span style="color:red">' + amt + '</span>';
    return amt;
  }

  function showDuesModal(html) {
    document.getElementById('dues-content').innerHTML = html;
    document.getElementById('dues-modal').style.display = 'block';
    var bd = document.getElementById('dues-backdrop'); if (bd) bd.style.display = 'block';
  }
  function hideDuesModal() {
    document.getElementById('dues-modal').style.display = 'none';
    var bd = document.getElementById('dues-backdrop'); if (bd) bd.style.display = 'none';
  }

  // Intercept the submit links and show dues modal
  $(document).on('click', '.raza-submit-btn', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    var btn = this;
    // fetch dues
    fetch('<?= base_url('accounts/get_member_dues') ?>', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data || !data.success) {
          // fallback to confirmation if endpoint unavailable
          if (confirm('Unable to fetch dues. Proceed to submit Raza?')) window.location.href = href;
          return;
        }
        var d = data.dues;
        var html = '<table class="table table-sm">'
          + '<tr><th>Category</th><th class="text-right">Due</th></tr>'
          + '<tr><td>FMB Takhmeen</td><td class="text-right">' + coloredAmount(d.fmb_due) + '</td></tr>'
          + '<tr><td>Sabeel Takhmeen</td><td class="text-right">' + coloredAmount(d.sabeel_due) + '</td></tr>'
          + '<tr><td>General Contributions</td><td class="text-right">' + coloredAmount(d.gc_due) + '</td></tr>'
          + '<tr><td>Miqaat Invoices</td><td class="text-right">' + coloredAmount(d.miqaat_due) + '</td></tr>'
          + '<tr><td>Corpus Fund</td><td class="text-right">' + coloredAmount(d.corpus_due) + '</td></tr>'
          + '<tr><td>Wajebaat</td><td class="text-right">' + coloredAmount(d.wajebaat_due || 0) + '</td></tr>'
          + '<tr><td>Qardan Hasana</td><td class="text-right">' + coloredAmount(d.qardan_hasana_due || 0) + '</td></tr>'
          + '<tr><th>Total</th><th class="text-right">' + coloredAmount(d.total_due) + '</th></tr>'
          + '</table>';
        if (d.total_due <= 0) {
          html = '<div class="alert alert-success">No pending dues. You may proceed.</div>' + html;
        } else {
          html = '<div class="alert alert-warning">You have pending dues. Please review before proceeding.</div>' + html;
        }

        // Miqaat invoices
        if (data.miqaat_invoices && Array.isArray(data.miqaat_invoices) && data.miqaat_invoices.length > 0) {
          var invHtml = '<hr><h6>Miqaat / Member Invoices</h6>'
            + '<table class="table table-sm table-bordered"><thead><tr><th>Assigned to</th><th>Invoice</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead><tbody>';
          data.miqaat_invoices.forEach(function(inv) {
            var owner = inv.owner_name || inv.user_id || '';
            var miqName = inv.miqaat_name || ('#'+inv.miqaat_id);
            invHtml += '<tr>'
              + '<td>' + owner + '</td>'
              + '<td>' + miqName + '</td>'
              + '<td class="text-right">' + formatINR(inv.amount || 0) + '</td>'
              + '<td class="text-right">' + formatINR(inv.paid_amount || 0) + '</td>'
              + '<td class="text-right">' + coloredAmount(inv.due_amount || 0) + '</td>'
              + '</tr>';
          });
          invHtml += '</tbody></table>';
          html += invHtml;
        }

        showDuesModal(html);

        // bind proceed button to send email notifications, then go to href
        var proceed = document.getElementById('dues-confirm');
        proceed.onclick = function() {
          // call backend to send dues emails, then navigate
          fetch('<?= base_url('accounts/send_dues_email') ?>', { method: 'POST', credentials: 'same-origin' })
            .then(function(r){ return r.json(); })
            .then(function(resp){
              hideDuesModal();
              window.location.href = href;
            })
            .catch(function(){ hideDuesModal(); window.location.href = href; });
        };
      })
      .catch(function () {
        if (confirm('Unable to fetch dues. Proceed to submit Raza?')) window.location.href = href;
      });
  });
</script>
</script>