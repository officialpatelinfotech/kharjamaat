<style>
  .hidden {
    display: hidden;
  }
  /* Action buttons grid */
  .gc-action-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:6px; }
  .gc-action-grid .btn { width:100%; margin-bottom:0 !important; }
  /* Maintain 2x2 even on small screens; optionally stack below 360px */
  @media (max-width:359.98px){ .gc-action-grid { grid-template-columns:1fr; } }
  /* Sorting styles */
  #gc-invoice-table thead th.gc-sort { cursor:pointer; position:relative; user-select:none; }
  #gc-invoice-table thead th.gc-sort .sort-indicator { position:absolute; right:6px; top:50%; transform:translateY(-50%); font-size:11px; opacity:0.45; }
  #gc-invoice-table thead th.gc-sort.sorted-asc .sort-indicator::after { content:'▲'; }
  #gc-invoice-table thead th.gc-sort.sorted-desc .sort-indicator::after { content:'▼'; }
  /* Keep dark header; subtle hover without turning white */
  @media (hover:hover){ #gc-invoice-table thead th.gc-sort:hover { background:#2f3438; } }
  /* Filters styling */
  #gc-filters label { font-weight:600; }
  #gc-filters input, #gc-filters select { font-size:13px; }
</style>

<div class="margintopcontainer mx-5 pt-5">
  <div class="row p-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo $type == 1 ? base_url("anjuman/fmbthaali") : base_url("anjuman/fmbniyaz"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-12 col-md-6 my-3 text-right">
      <button class="btn btn-primary" data-toggle="modal" data-target="#generateInvoiceModal">
        <i class="fa-solid fa-file-invoice-dollar me-2"></i> Generate New Invoice
      </button>
    </div>
  </div>

  <h4 class="heading text-center mb-4">FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> Extra Contribution Invoices</h4>

  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white text-center">
    </div>
    <div class="card-body p-0 table-responsive">
      <div id="gc-filters" class="p-3 border-bottom bg-light">
        <div class="form-row">
          <div class="col-md-4 mb-2">
            <label for="gc-filter-name" class="small text-muted mb-1">Member Name</label>
            <input type="text" id="gc-filter-name" class="form-control form-control-sm" placeholder="Search name...">
          </div>
          <div class="col-md-4 mb-2">
            <label for="gc-filter-ctype" class="small text-muted mb-1">Contribution Type</label>
            <select id="gc-filter-ctype" class="form-control form-control-sm">
              <option value="">All Types</option>
              <?php if(isset($contri_type_gc)): foreach($contri_type_gc as $ct): ?>
                <option value="<?php echo htmlspecialchars(strtolower($ct['name']), ENT_QUOTES); ?>"><?php echo htmlspecialchars($ct['name']); ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>
          <div class="col-md-4 mb-2 d-flex align-items-end">
            <button type="button" id="gc-filter-clear" class="btn btn-outline-secondary btn-sm w-100"><i class="fa fa-times mr-1"></i> Clear Filters</button>
          </div>
        </div>
      </div>
      <table class="table table-striped align-middle" id="gc-invoice-table">
        <thead class="thead-dark">
          <tr>
            <th class="no-sort" style="width:40px;">#</th>
            <th class="gc-sort" data-sort-type="date">Invoice Date <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="number">ITS ID <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="text">Member Name <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="text">Contribution Type <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="number">Amount (₹) <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="number">Received (₹) <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="number">Balance (₹) <span class="sort-indicator"></span></th>
            <th class="gc-sort" data-sort-type="text">Status <span class="sort-indicator"></span></th>
            <th class="no-sort" style="min-width:220px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($all_user_fmbgc)): ?>
            <?php foreach ($all_user_fmbgc as $key => $row): ?>
              <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo date("d-m-Y", strtotime($row["created_at"])); ?></td>
                <td><?php echo $row["ITS_ID"]; ?></td>
                <td><?php echo $row["Full_Name"]; ?></td>
                <td><?php echo $row["contri_type"]; ?></td>
                <td><span class="gc-amount" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["amount"],0); ?></span></td>
                <td><span class="gc-received" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["total_received"],0); ?></span></td>
                <td><span class="gc-balance" data-fmbgc-id="<?php echo $row['id'];?>">₹<?php echo number_format($row["balance_due"],0); ?></span></td>
                <td><span class="gc-status" data-fmbgc-id="<?php echo $row['id'];?>">
                  <?php if ($row['balance_due'] <= 0): ?>
                    <span class="badge badge-success">Paid</span>
                  <?php elseif ($row['total_received'] > 0): ?>
                    <span class="badge badge-warning">Partial</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Unpaid</span>
                  <?php endif; ?>
                </span></td>
                <td>
                  <div class="gc-action-grid">
                    <button class="update-payment-btn btn btn-sm btn-success" data-fmbgc-id="<?php echo $row["id"]; ?>" data-user-id="<?php echo $row["user_id"]; ?>" data-member-name="<?php echo htmlspecialchars($row["Full_Name"], ENT_QUOTES); ?>" data-amount="<?php echo $row["amount"]; ?>" data-total-received="<?php echo $row['total_received']; ?>" data-balance-due="<?php echo $row['balance_due']; ?>" data-toggle="modal" data-target="#update-payment-modal">Receive Payment</button>
                    <button class="btn btn-sm btn-info view-history-btn" data-fmbgc-id="<?php echo $row['id']; ?>" data-member-name="<?php echo htmlspecialchars($row['Full_Name'], ENT_QUOTES); ?>" data-toggle="modal" data-target="#payment-history-modal">
                      <i class="fa-solid fa-clock-rotate-left"></i> Payment History
                    </button>
                    <button class="view-description btn btn-sm btn-outline-primary" data-description="<?php echo $row["description"]; ?>" data-toggle="modal" data-target="#description-modal">
                      <i class="fa-solid fa-eye"></i> View Description
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-invoice-btn" data-invoice-id="<?php echo $row['id']; ?>" data-member-name="<?php echo htmlspecialchars($row['Full_Name'], ENT_QUOTES); ?>" data-total-received="<?php echo $row['total_received']; ?>">
                      <i class="fa-solid fa-trash"></i> Delete Invoice
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="generateInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Generate New Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="save-fmbgc-form" method="POST" action="<?php echo base_url("anjuman/addfmbgc"); ?>">
            <div class="mb-3">
              <label for="contri-year">Contribution Year</label>
              <select name="contri_year" id="contri-year" class="form-control" required>
                <option value="">Select Contribution Year</option>
                <option value="1446-47">1446-47</option>
                <option value="1447-48">1447-48</option>
                <option value="1448-49">1448-49</option>
                <option value="1449-50">1449-50</option>
                <option value="1450-51">1450-51</option>
                <option value="1451-52">1451-52</option>
                <option value="1452-53">1452-53</option>
                <option value="1453-54">1453-54</option>
                <option value="1454-55">1454-55</option>
                <option value="1455-56">1455-56</option>
                <option value="1456-57">1456-57</option>
                <option value="1457-58">1457-58</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="member-autocomplete">Member Name</label>
              <input type="text" id="member-autocomplete" class="form-control" placeholder="Type member name..." autocomplete="off" required>
              <input type="hidden" name="user_id" id="user-id" required>
              <div id="member-autocomplete-list" class="list-group" style="position:absolute; z-index:1000;"></div>
            </div>
            <div class="mb-3">
              <label for="contri-type">Contribution Type</label>
              <input type="hidden" name="fmb_type" value="<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>" required>
              <select name="contri_type" id="contri-type" class="form-control" required>
                <option value="">Select Type</option>
                <?php if (isset($contri_type_gc)): ?>
                  <?php foreach ($contri_type_gc as $key => $value): ?>
                    <option value="<?php echo $value["name"]; ?>"><?php echo $value["name"]; ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount (₹)</label>
              <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" min="1" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control" rows="2" placeholder="Optional"></textarea>
            </div>
            <button type="submit" id="save-fmbgc-btn" class="btn btn-success w-100">
              <i class="fa-solid fa-circle-check me-2"></i> Generate Invoice
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="update-payment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Receive Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="update-fmbgc-payment-form" method="POST" action="<?php echo base_url("anjuman/updatefmbgcpayment"); ?>">
            <div class="mb-2">
              <p class="mb-1"><strong>Member:</strong> <span id="up-member-name"></span></p>
              <p class="mb-2"><strong>Invoice Amount:</strong> ₹<span id="invoice-amount"></span></p>
              <p class="mb-2"><strong>Previously Received:</strong> ₹<span id="already-received">0</span></p>
              <p class="mb-2"><strong>Balance Due:</strong> ₹<span id="due-amount"></span></p>
              <input type="hidden" name="user_id" id="up-user-id" value="">
              <input type="hidden" name="fmbgc_id" id="up-fmbgc-id" value="">
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="payment-method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment-method" class="form-control" required>
                  <option value="">Select Method</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="payment-date" class="form-label">Payment Date</label>
                <input type="date" name="payment_date" id="payment-date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="payment-amount" class="form-label">Amount Received Now (₹)</label>
              <input type="number" name="payment_received_amount" id="payment-amount" class="form-control" placeholder="Enter amount" min="1" required>
              <small class="text-muted" id="payment-amount-hint"></small>
            </div>
            <div class="mb-3">
              <label for="payment-remarks" class="form-label">Remarks</label>
              <textarea name="payment_remarks" id="payment-remarks" rows="2" class="form-control" placeholder="Optional notes"></textarea>
            </div>
            <button type="submit" id="update-fmbgc-payment-btn" class="btn btn-success w-100">
              <i class="fa-solid fa-circle-check me-2"></i> Save Payment
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="description-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fa-solid fa-plus-circle me-2"></i> Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="modal-view-description" class="text-dark"></p>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="payment-history-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title"><i class="fa-solid fa-clock-rotate-left me-2"></i> Payment History - <span id="history-member-name"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="history-meta" class="mb-2 small text-muted"></div>
          <div class="table-responsive">
            <table class="table table-sm table-bordered" id="history-table">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Payment ID</th>
                  <th>Date</th>
                  <th>Method</th>
                  <th class="text-right">Amount (₹)</th>
                  <th>Remarks</th>
                  <th>Received At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr class="font-weight-bold">
                  <td colspan="3" class="text-right">Total Received:</td>
                  <td class="text-right" id="history-total-received">0.00</td>
                  <td colspan="2"></td>
                </tr>
                <tr class="font-weight-bold">
                  <td colspan="3" class="text-right">Balance Due:</td>
                  <td class="text-right" id="history-balance-due">0.00</td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div id="history-empty" class="alert alert-info d-none">No payments recorded yet.</div>
          <div id="history-error" class="alert alert-danger d-none"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    // Autocomplete for member name
    $("#member-autocomplete").on("input", function() {
      const query = $(this).val();
      if (!query || query.length < 2) {
        $("#member-autocomplete-list").empty().hide();
        $("#user-id").val("");
        return;
      }
      $.ajax({
        url: "<?php echo base_url('anjuman/searchmembers'); ?>",
        type: "POST",
        data: {
          query: query
        },
        dataType: "json",
        success: function(res) {
          if (res.success && Array.isArray(res.members) && res.members.length > 0) {
            let listHtml = '';
            res.members.forEach(function(member) {
              listHtml += `<button type="button" class="list-group-item list-group-item-action member-suggestion" data-id="${member.ITS_ID}" data-name="${member.Full_Name}">${member.Full_Name} (${member.ITS_ID})</button>`;
            });
            $("#member-autocomplete-list").html(listHtml).show();
          } else {
            $("#member-autocomplete-list").html('<div class="list-group-item">No members found</div>').show();
          }
        },
        error: function() {
          $("#member-autocomplete-list").html('<div class="list-group-item text-danger">Error searching members</div>').show();
        }
      });
    });

    // Delete payment
    $(document).on('click', '.delete-payment-btn', function() {
      if (!confirm('Are you sure you want to delete this payment?')) return;
      const pid = $(this).data('payment-id');
      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_delete_payment'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { payment_id: pid },
        success: function(res) {
          if (!res.success) {
            alert(res.message || 'Delete failed');
            return;
          }
          const invIdMatch = $('#history-meta').text().match(/Invoice ID: (\d+)/);
          if (invIdMatch) {
            const invoiceId = invIdMatch[1];
            // Refresh modal history
            $('.view-history-btn[data-fmbgc-id="' + invoiceId + '"]').trigger('click');
            if (res.summary) {
              const s = res.summary;
              const fmt = function(v){ return '₹' + Math.round(parseFloat(v)||0); };
              $('.gc-received[data-fmbgc-id="' + invoiceId + '"]').text(fmt(s.total_received));
              $('.gc-balance[data-fmbgc-id="' + invoiceId + '"]').text(fmt(s.balance_due));
              // Update button data attributes so further payments use new values
              const btn = $('.update-payment-btn[data-fmbgc-id="' + invoiceId + '"]');
              btn.attr('data-total-received', s.total_received);
              btn.attr('data-balance-due', s.balance_due);
              // Update status badge
              let badgeHtml = '<span class="badge badge-secondary">Unpaid</span>';
              if (s.balance_due <= 0) badgeHtml = '<span class="badge badge-success">Paid</span>';
              else if (s.total_received > 0) badgeHtml = '<span class="badge badge-warning">Partial</span>';
              $('.gc-status[data-fmbgc-id="' + invoiceId + '"]').html(badgeHtml);
            }
          }
        },
        error: function() { alert('Server error'); }
      });
    });

    // Select member from autocomplete
    $(document).on("click", ".member-suggestion", function() {
      const itsId = $(this).data("id");
      const name = $(this).data("name");
      $("#user-id").val(itsId);
      $("#member-autocomplete").val(name);
      $("#member-autocomplete-list").empty().hide();
      $("#member-name").text("Member Name: " + name + " (" + itsId + ")").show();
    });

    // Hide suggestions on outside click
    $(document).on("click", function(e) {
      if (!$(e.target).closest('#member-autocomplete, #member-autocomplete-list').length) {
        $("#member-autocomplete-list").empty().hide();
      }
    });

    $("#save-fmbgc-btn").on("click", function(e) {
      e.preventDefault();
      $contriYear = $("#contri-year").val();
      $userId = $("#user-id").val();
      $contriType = $("#contri-type").val();
      $amount = $("#amount").val();

      if ($userId.length < 1) {
        alert("Please select a member.");
        return;
      }

      if (!$amount || parseFloat($amount) === 0) {
        alert("Amount can't be zero.");
        return;
      }

      $.ajax({
        url: "<?php echo base_url("anjuman/validatefmbgc"); ?>",
        type: "POST",
        data: {
          "contri_year": $contriYear,
          "user_id": $userId,
          "fmb_type": "<?php echo $type == 1 ? "Thaali" : "Niyaz"; ?>",
          "contri_type": $contriType,
        },
        dataType: "json",
        success: function(res) {
          if (res.success) {
            $result = confirm(
              "FMB <?php echo $type == 1 ? "Thaali" : "Niyaz"; ?> General Contribution found for type " + $contriType + " for member. Are you sure you want to move forward?"
            );
            if ($result) {
              $("#save-fmbgc-form").off("submit").submit();
            }
          } else {
            $("#save-fmbgc-form").off("submit").submit();
          }
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
    });

    // $(".update-payment-btn").on("click", function(e) {
    //   e.preventDefault();
    //   $userId = $(this).data("user-id");
    //   $fmbgcId = $(this).data("fmbgc-id");
    //   $memberName = $(this).data("member-name");
    //   $amount = $(this).data("amount");
    //   $("#up-user-id").val($userId);
    //   $("#up-fmbgc-id").val($fmbgcId);
    //   $("#up-member-name").text($memberName);
    //   $("#due-amount").text($amount);
    //   $("#payment-amount").attr("max", $amount);
    // });

    // Receive payment button click
    $(document).on("click", ".update-payment-btn", function() {
      const userId = $(this).data("user-id");
      const fmbgcId = $(this).data("fmbgc-id");
      const memberName = $(this).data("member-name");
      const invoiceAmount = parseFloat($(this).data("amount")) || 0;
      const alreadyReceived = parseFloat($(this).data("total-received")) || 0;
      const dueAttr = $(this).data("balance-due");
      let due = (typeof dueAttr !== 'undefined') ? parseFloat(dueAttr) : (invoiceAmount - alreadyReceived);
      if (due < 0) due = 0;

      $("#up-user-id").val(userId);
      $("#up-fmbgc-id").val(fmbgcId);
      $("#up-member-name").text(memberName);
      $("#invoice-amount").text(Math.round(invoiceAmount));
      $("#already-received").text(Math.round(alreadyReceived));
      $("#due-amount").text(Math.round(due));
      $("#payment-amount").attr("max", due).val(due > 0 ? due : '');
      if (due <= 0) {
        $("#payment-amount-hint").text("Invoice already settled.").addClass("text-danger");
        $("#update-fmbgc-payment-btn").prop("disabled", true);
      } else {
        $("#payment-amount-hint").text("Maximum receivable now: ₹" + Math.round(due)).removeClass("text-danger");
        $("#update-fmbgc-payment-btn").prop("disabled", false);
      }
    });

    // Basic client validation to disallow overpayment
    $("#payment-amount").on("input", function() {
      const max = parseFloat($(this).attr("max"));
      let val = parseFloat($(this).val());
      if (max && val > max) {
        $(this).val(max);
        val = max;
      }
    });

    // Final guard on submit
    $("#update-fmbgc-payment-form").on("submit", function(e) {
      const max = parseFloat($("#payment-amount").attr("max"));
      const val = parseFloat($("#payment-amount").val());
      if (isNaN(val) || val <= 0) {
        alert("Enter a valid payment amount.");
        e.preventDefault();
        return;
      }
      if (!isNaN(max) && val > max + 0.0001) {
        alert("Payment exceeds remaining balance.");
        e.preventDefault();
        return;
      }
    });

    // View payment history
    $(document).on('click', '.view-history-btn', function() {
      const fmbgcId = $(this).data('fmbgc-id');
      const memberName = $(this).data('member-name');
      $('#history-member-name').text(memberName);
      // Reset UI
      $('#history-table tbody').empty();
      $('#history-total-received').text('₹0');
      $('#history-balance-due').text('₹0');
      $('#history-empty').addClass('d-none');
      $('#history-error').addClass('d-none').text('');
      $('#history-meta').text('Loading...');

      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_payment_history'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { fmbgc_id: fmbgcId },
        success: function(res) {
          if (!res.success) {
            $('#history-error').removeClass('d-none').text(res.message || 'Failed to load history');
            $('#history-meta').text('');
            return;
          }
            const inv = res.invoice;
            $('#history-meta').html(
              'Invoice ID: ' + inv.id + ' | Created: ' + (inv.created_at || '') +
              ' | Type: ' + inv.fmb_type + ' | Contribution: ' + inv.contri_type +
              ' | Amount: ₹' + Math.round(inv.amount)
            );
            if (!res.payments || res.payments.length === 0) {
              $('#history-empty').removeClass('d-none');
            } else {
              let rows = '';
              
              res.payments.forEach(function(p, idx) {
                const pid = (p.payment_id && parseInt(p.payment_id) > 0) ? p.payment_id : (p.id || '');
                rows += '<tr>' +
                  '<td>' + (idx + 1) + '</td>' +
                  '<td>' + pid + '</td>' +
                  '<td>' + (p.payment_date || '') + '</td>' +
                  '<td>' + (p.payment_method || '') + '</td>' +
                  '<td class="text-right">₹' + Math.round(p.amount) + '</td>' +
                  '<td>' + (p.remarks ? $('<div>').text(p.remarks).html() : '') + '</td>' +
                  '<td>' + (p.created_at || '') + '</td>' +
                  '<td class="text-nowrap">' +
                    '<button class="btn btn-sm btn-outline-secondary gc-view-receipt mr-2" title="View Receipt" data-payment-id="' + pid + '"><i class="fa-solid fa-file-pdf"></i></button>' +
                    '<button class="btn btn-sm btn-outline-danger delete-payment-btn" data-payment-id="' + pid + '"><i class="fa-solid fa-trash"></i></button>' +
                  '</td>' +
                '</tr>';
              });
              $('#history-table tbody').html(rows);
            }
            $('#history-total-received').text('₹' + Math.round(res.total_received));
            $('#history-balance-due').text('₹' + Math.round(res.balance_due));
        },
        error: function() {
          $('#history-error').removeClass('d-none').text('Server error while loading history');
          $('#history-meta').text('');
        }
      });
    });

    $(".view-description").on("click", function(e) {
      e.preventDefault();
      if ($(this).data("description")) {
        $("#modal-view-description").text($(this).data("description"));
      } else {
        $("#modal-view-description").text("No description found!");
      }
    });

    // Delete entire invoice
    $(document).on('click', '.delete-invoice-btn', function(){
      const invoiceId = $(this).data('invoice-id');
      const memberName = $(this).data('member-name');
      const received = parseFloat($(this).data('total-received'))||0;
      if(!invoiceId) return;
      let msg = 'Delete this invoice for '+memberName+'?';
      if(received>0){
        msg = 'This invoice has ₹'+Math.round(received)+' received. Deleting will remove all related payments. Continue?';
      }
      if(!confirm(msg)) return;
      const $btn = $(this);
      $btn.prop('disabled', true);
      $.ajax({
        url: '<?php echo base_url('anjuman/fmbgc_delete_invoice'); ?>',
        type: 'POST',
        dataType: 'json',
        data: { invoice_id: invoiceId },
        success: function(res){
          if(!res.success){ alert(res.message||'Delete failed'); return; }
          // Remove row visually
          $btn.closest('tr').fadeOut(200, function(){ $(this).remove(); renumberGcTable(); });
        },
        error: function(){ alert('Server error'); },
        complete: function(){ $btn.prop('disabled', false); }
      });
    });

    function renumberGcTable(){
      $('#gc-invoice-table tbody tr').each(function(i){ $(this).find('td:first').text(i+1); });
    }

    // ---------- Sorting Logic for GC Invoice Table ----------
    (function(){
      const table = document.getElementById('gc-invoice-table');
      if(!table) return;
      const tbody = table.querySelector('tbody');
      const headers = table.querySelectorAll('thead th.gc-sort');
      let lastIndex = -1, lastDir = 'asc';

      function parseValue(cell, type){
        if(!cell) return '';
        let txt = cell.getAttribute('data-sort-value');
        txt = (txt!==null?txt:cell.textContent).trim();
        switch(type){
          case 'number':
            txt = txt.replace(/[^0-9.-]/g,'');
            return txt.length?parseFloat(txt):0;
          case 'date':
            // Expect d-m-Y
            const parts = txt.split('-');
            if(parts.length===3){
              const d = parseInt(parts[0],10); const m = parseInt(parts[1],10)-1; const y = parseInt(parts[2],10);
              return new Date(y,m,d).getTime();
            }
            const t = Date.parse(txt); return isNaN(t)?0:t;
          default:
            return txt.toLowerCase();
        }
      }

      function clearIndicators(except){
        headers.forEach((h,i)=>{ if(i!==except){ h.classList.remove('sorted-asc','sorted-desc'); } });
      }

      headers.forEach((header, hIndex)=>{
        header.addEventListener('click', ()=>{
          const type = header.getAttribute('data-sort-type') || 'text';
          const rows = Array.from(tbody.querySelectorAll('tr'));
          const dir = (lastIndex===hIndex && lastDir==='asc')?'desc':'asc';
          lastIndex = hIndex; lastDir = dir;
          rows.sort((a,b)=>{
            // +1 offset because first column (#) is non-sort and part of children
            const aCell = a.children[hIndex+0]; // after redesign indexes match header order because we included # with no-sort
            const bCell = b.children[hIndex+0];
            const aVal = parseValue(aCell, type);
            const bVal = parseValue(bCell, type);
            if(aVal < bVal) return dir==='asc'?-1:1;
            if(aVal > bVal) return dir==='asc'?1:-1;
            return 0;
          });
          // Rebuild
          const frag = document.createDocumentFragment(); rows.forEach(r=>frag.appendChild(r));
          tbody.innerHTML = ''; tbody.appendChild(frag);
          renumberGcTable();
          clearIndicators(hIndex);
          header.classList.remove('sorted-asc','sorted-desc');
          header.classList.add(dir==='asc'?'sorted-asc':'sorted-desc');
        });
      });
    })();
    // ---------- End Sorting Logic ----------

    // ---------- Filter Logic for GC Invoice Table ----------
    function applyGcFilters(){
      const nameVal = $('#gc-filter-name').val().trim().toLowerCase();
      const typeVal = $('#gc-filter-ctype').val().trim(); // already lowercased in option values
      $('#gc-invoice-table tbody tr').each(function(){
        const $tr = $(this);
        const member = $tr.children('td').eq(3).text().trim().toLowerCase();
        const ctype = $tr.children('td').eq(4).text().trim().toLowerCase();
        let show = true;
        if(nameVal && member.indexOf(nameVal) === -1) show = false;
        if(typeVal && ctype !== typeVal) show = false;
        $tr.toggle(show);
      });
      // Renumber visible rows only
      let i=1; $('#gc-invoice-table tbody tr:visible').each(function(){ $(this).children('td').eq(0).text(i++); });
    }
    $('#gc-filter-name').on('input', applyGcFilters);
    $('#gc-filter-ctype').on('change', applyGcFilters);
    $('#gc-filter-clear').on('click', function(){
      $('#gc-filter-name').val('');
      $('#gc-filter-ctype').val('');
      $('#gc-invoice-table tbody tr').show();
      renumberGcTable();
    });
    // ---------- End Filter Logic ----------
  });

    // View Receipt (GC payment) via AJAX -> open PDF blob
    $(document).on('click', '.gc-view-receipt', function() {
      const pid = $(this).data('payment-id');
      if (!pid) return;
      const $btn = $(this);
      $btn.prop('disabled', true).addClass('disabled');
      $.ajax({
        url: '<?php echo base_url('common/generate_pdf'); ?>',
        method: 'POST',
        data: { id: pid, for: 3 },
        xhrFields: { responseType: 'blob' },
        success: function(blob, status, xhr) {
          const ct = (xhr.getResponseHeader('Content-Type') || '').toLowerCase();
          if (!ct.includes('pdf')) {
            // Try read as text for error
            const reader = new FileReader();
            reader.onload = function() { alert('Unexpected response: ' + String(reader.result).substring(0,200)); };
            reader.readAsText(blob);
            return;
          }
          const url = URL.createObjectURL(blob);
          window.open(url, '_blank');
          setTimeout(() => URL.revokeObjectURL(url), 60000);
        },
        error: function(xhr) {
          alert('Failed to generate receipt (status ' + xhr.status + ').');
        },
        complete: function() {
          $btn.prop('disabled', false).removeClass('disabled');
        }
      });
    });
</script>