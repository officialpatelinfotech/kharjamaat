<div class="mx-5 margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $this->session->flashdata('error'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  <div class="row p-0 mb-4 mb-md-0">
    <div class="col-12 col-md-6">
      <a href="<?php echo base_url("anjuman/fmbthaali") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
  </div>
  <h4 class="heading text-center mb-4">FMB Thaali Takhmeen</h4>
  <style>
    /* Scrollable table container with sticky header */
    .table-scroll-fixed {
      max-height: calc(100vh - 320px);
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
    }

    .table-scroll-fixed table {
      width: 100%;
      border-collapse: collapse;
    }

    .table-scroll-fixed thead th {
      position: sticky;
      top: 0;
      z-index: 3;
      background: #f8fafc;
      /* match card header */
    }
  </style>
  <?php
  $unique_sectors = [];
  $unique_sub_sectors = [];
  if (isset($all_user_fmb_takhmeen)) {
    foreach ($all_user_fmb_takhmeen as $u) {
      if (!empty($u['Sector'])) $unique_sectors[$u['Sector']] = true;
      if (!empty($u['Sub_Sector'])) $unique_sub_sectors[$u['Sub_Sector']] = true;
    }
  }
  ?>
  <div class="row mb-3" id="takhmeen-filters">
    <div class="col-12 col-md-2 mb-2">
      <input type="text" id="filter-member" class="form-control form-control-sm" placeholder="Filter name or ITS" autocomplete="off" />
    </div>
    <!-- <div class="col-12 col-md-2 mb-2">
      <input type="text" id="filter-its" class="form-control form-control-sm" placeholder="Filter ITS ID" autocomplete="off" value="<?php echo htmlspecialchars(isset($filter_its) ? $filter_its : $this->input->get('its')); ?>" />
    </div> -->
    <div class="col-12 col-md-1 mb-2">
      <form method="post" action="<?php echo base_url('anjuman/fmbthaalitakhmeen'); ?>" class="m-0">
        <select name="fmb_year" id="fmb-year" class="form-control form-control-sm" onchange="this.form.submit()">
          <?php if (!empty($hijri_years)):
            foreach ($hijri_years as $y): ?>
              <option value="<?php echo htmlspecialchars($y, ENT_QUOTES); ?>" <?php echo ($selected_year === $y) ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
          <?php endforeach;
          endif; ?>
        </select>
      </form>
    </div>
    <div class="col-12 col-md-1 mb-2">
      <select id="filter-sector" class="form-control form-control-sm">
        <option value="">All Sectors</option>
        <?php foreach (array_keys($unique_sectors) as $sec): ?>
          <option value="<?php echo htmlspecialchars($sec, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sec); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-2 mb-2">
      <select id="filter-sub-sector" class="form-control form-control-sm">
        <option value="">All Sub-Sectors</option>
        <?php foreach (array_keys($unique_sub_sectors) as $sub): ?>
          <option value="<?php echo htmlspecialchars($sub, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sub); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-3 d-flex justify-content-md-end align-items-start" id="takhmeen-toolbar" style="gap:8px;">
      <button type="button" id="apply-filters-btn" class="btn btn-sm btn-primary" title="Apply filters"><i class="fa fa-filter"></i> Apply</button>
      <button type="button" id="clear-filters-btn" class="btn btn-sm btn-outline-secondary" title="Clear filters"><i class="fa fa-times"></i> Clear</button>
      <button type="button" id="reset-sort-btn" class="btn btn-sm btn-outline-secondary" title="Reset original order"><i class="fa fa-rotate-left"></i> Refresh Order</button>
    </div>
  </div>
  <div class="card shadow-sm rounded-3 mt-4">
    <div class="card-header bg-light text-center">
      <strong>Per Day Thaali Cost</strong>
      <small class="text-secondary">(FY - <?php echo htmlspecialchars($selected_year ?? '', ENT_QUOTES); ?>)</small>
      :
      <?php if (isset($per_day_thaali_cost_amount) && $per_day_thaali_cost_amount !== null && (float)$per_day_thaali_cost_amount > 0): ?>
        <span class="takhmeen-amount text-primary" data-raw="<?php echo (float)$per_day_thaali_cost_amount; ?>"><?php echo (float)$per_day_thaali_cost_amount; ?></span>
      <?php else: ?>
        <span class="text-secondary">Not set</span>
      <?php endif; ?>
    </div>
    <div class="card-body p-0 table-responsive table-scroll-fixed">
      <table id="takhmeen-table" class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>ITS ID</th>
            <th>Name</th>
            <th>Sector</th>
            <th>Sub-Sector</th>
            <th>Thaali Days</th>
            <th>Selected Year Takhmeen</th>
            <th>Total Paid</th>
            <th>Total Due</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($all_user_fmb_takhmeen)) {
            foreach ($all_user_fmb_takhmeen as $key => $user) {
          ?>
              <tr>
                <td class="row-index" data-sort-value="<?php echo $key + 1; ?>"><?php echo $key + 1; ?></td>
                <td data-sort-value="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>"><?php echo $user["ITS_ID"]; ?></td>
                <td data-sort-value="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>"><?php echo $user["Full_Name"]; ?></td>
                <td data-sort-value="<?php echo htmlspecialchars($user['Sector'], ENT_QUOTES); ?>"><?php echo $user["Sector"]; ?></td>
                <td data-sort-value="<?php echo htmlspecialchars($user['Sub_Sector'], ENT_QUOTES); ?>"><?php echo $user["Sub_Sector"]; ?></td>
                <?php
                $assignedDays = isset($user['assigned_thaali_days']) ? $user['assigned_thaali_days'] : null;
                ?>
                <td data-sort-value="<?php echo $assignedDays !== null ? (int)$assignedDays : ''; ?>">
                  <?php if ($assignedDays !== null): ?>
                    <a href="#" class="view-assigned-thaali-days" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>" data-year="<?php echo htmlspecialchars($selected_year ?? '', ENT_QUOTES); ?>">
                      <?php echo (int)$assignedDays; ?>
                    </a>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td data-sort-value="<?php echo (int)($user['selected_total_takhmeen'] ?? 0); ?>">
                  <?php if (!empty($user['selected_total_takhmeen'])): ?>
                    <p class="takhmeen-amount text-primary m-0 p-0" data-raw="<?php echo (int)$user['selected_total_takhmeen']; ?>"><?php echo $user['selected_total_takhmeen']; ?></p>
                    <p class="financial-year pt-2 m-0">
                      <small class="text-secondary">(FY - <?php echo htmlspecialchars($user['selected_takhmeen_year'] ?? '', ENT_QUOTES); ?>)</small>
                    </p>
                  <?php else: ?>
                    Takhmeen Not Found
                  <?php endif; ?>
                </td>
                <td class="takhmeen-amount text-success" data-sort-value="<?php echo (int)$user['overall_total_paid']; ?>" data-raw="<?php echo (int)$user['overall_total_paid']; ?>"><?php echo $user["overall_total_paid"]; ?></td>
                <td class="takhmeen-amount <?php echo $user["overall_due"] > 0 ? 'text-danger' : ''; ?>" data-sort-value="<?php echo (int)$user['overall_due']; ?>" data-raw="<?php echo (int)$user['overall_due']; ?>"><?php echo $user["overall_due"]; ?></td>
                <td>
                  <?php if (count($user["all_takhmeen"]) > 0): ?>
                    <button class="view-due btn btn-sm btn-info mb-2" data-toggle="modal" data-target="#due-overview-modal" data-user-id="<?php echo htmlspecialchars($user['ITS_ID'], ENT_QUOTES); ?>" data-user-name="<?php echo $user["Full_Name"]; ?>" data-all-takhmeen='<?php echo htmlspecialchars(json_encode($user["all_takhmeen"]), ENT_QUOTES, 'UTF-8'); ?>'>Takhmeen Details</button>
                  <?php endif; ?>
                  <?php if ($user["overall_due"] > 0): ?>
                    <button id="pay-takhmeen-btn" class="pay-takhmeen-btn btn btn-sm btn-success mb-2" data-toggle="modal" data-target="#pay-takhmeen-container" data-user-id="<?php echo $user["ITS_ID"]; ?>" data-user-name="<?php echo $user["Full_Name"]; ?>" data-overall-due="<?php echo $user["overall_due"]; ?>">Receive Payment</button>
                  <?php endif; ?>
                  <?php if (count($user["all_takhmeen"])): ?>
                    <button id="payment-history" class="payment-history mt-2 mt-md-0 mb-2 btn btn-sm btn-outline-primary" onclick="openPaymentHistoryModal('<?php echo $user['ITS_ID']; ?>', '<?php echo htmlspecialchars($user['Full_Name'], ENT_QUOTES); ?>')">Payment History</button>
                  <?php endif; ?>
                </td>
              </tr>
          <?php
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Assigned Thaali Days Modal -->
<div class="modal fade" id="assigned-thaali-days-modal" tabindex="-1" aria-labelledby="assigned-thaali-days-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assigned-thaali-days-title">Assigned Thaali Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-1"><b>Member Name:</b> <span id="assigned-thaali-days-member"></span></p>
        <p class="mb-3"><b>FY:</b> <span id="assigned-thaali-days-year"></span></p>
        <div id="assigned-thaali-days-loading" class="text-center text-secondary" style="display:none;">Loading...</div>
        <div id="assigned-thaali-days-empty" class="text-center text-secondary" style="display:none;">No assigned dates.</div>
        <ul id="assigned-thaali-days-list" class="mb-0"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="due-overview-modal" role="dialog" tabindex="-1" aria-labelledby="due-overview" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="due-overview">Takhmeen Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><b>Member Name:</b> <span id="member-name"></span></p>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Year</th>
              <th>Takhmeen Amount</th>
              <th>Paid</th>
              <th>Due</th>
              <th>Assigned Thaali Days</th>
              <th>Update Remark</th>
            </tr>
          </thead>
          <tbody id="due-details"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pay-takhmeen-container" tabindex="-1" aria-labelledby="pay-takhmeen-container-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pay-takhmeen-container-label">Receive Takhmeen Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updatePaymentForm" method="post" action="<?= base_url('anjuman/update_fmb_payment'); ?>">
          <input type="hidden" name="user_id" id="modal_user_id">
          <div class="form-group">
            <label><strong>Member Name:</strong> <span id="modal_user_name" class="mb-1"></span></label>
          </div>
          <div class="form-group">
            <label for="payment-method">Payment Method:</label>
            <select name="payment_method" id="payment-method" class="form-control" required>
              <option value="">-----</option>
              <option value="Cash">Cash</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
          <div class="form-group">
            <label for="modal-amount">Payment Amount:</label>
            <input type="number" class="form-control" name="amount" id="modal-amount" placeholder="Enter payment amount" required min="1">
          </div>
          <div class="form-group">
            <label for="modal-payment-date">Payment Date:</label>
            <input type="date" class="form-control" name="payment_date" id="modal-payment-date" required>
          </div>
          <div class="form-group">
            <label for="modal_payment_remark">Payment Remark:</label>
            <input type="text" class="form-control" name="remarks" id="modal_payment_remark" placeholder="Enter remarks">
          </div>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="payment-history-container" tabindex="-1" aria-labelledby="payment-history-label" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payment-history-label">Payment History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><b>Member Name:</b> <span id="history-user-name"></span></p>
        <div id="payment-history-table-container">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Payment Date</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="payment-history-rows"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Modal z-index stacking fix (Bootstrap) for pages with multiple modals
  // Ensures newly opened modal + its backdrop appear above existing open modals.
  $(document).on('show.bs.modal', '.modal', function() {
    const $openModals = $('.modal.show');
    const baseZ = 1040;
    const z = baseZ + ($openModals.length * 10) + 10;
    $(this).css('z-index', z);

    // Backdrop is inserted after this event; adjust it on next tick
    setTimeout(function() {
      const $backdrops = $('.modal-backdrop').not('.modal-stack');
      $backdrops.css('z-index', z - 1).addClass('modal-stack');
    }, 0);
  });

  // When a stacked modal closes, keep body scroll lock if others remain open
  $(document).on('hidden.bs.modal', '.modal', function() {
    if ($('.modal.show').length) {
      $('body').addClass('modal-open');
    }
  });

  // Precise currency formatting without rounding significant digits
  $(function() {
    $(".takhmeen-amount").each(function() {
      var $el = $(this);
      var raw = $el.data("raw");
      if (raw === undefined || raw === null || raw === "") {
        var txt = $el.text().replace(/[^\d.-]/g, '').trim();
        raw = txt ? parseFloat(txt) : 0;
      }
      var formatted = new Intl.NumberFormat("en-IN", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(raw);
      $el.html("&#8377;" + formatted);
    });
  });

  $(document).ready(function() {
    $(".view-due").on("click", function() {
      const userId = $(this).data("user-id");
      const userName = $(this).data("user-name");
      const allTakhmeen = $(this).data("all-takhmeen");

      $("#member-name").text(userName);
      $("#due-details").empty();
      allTakhmeen.forEach(function(takhmeen) {
        const remarkSafe = (takhmeen.remark || '').toString()
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');

        const fmt = new Intl.NumberFormat('en-IN', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
        const amtTotal = `₹${fmt.format(parseFloat(takhmeen.total_amount||0))}`;
        const amtPaid = `₹${fmt.format(parseFloat(takhmeen.total_paid||0))}`;
        const amtDue = `₹${fmt.format(parseFloat(takhmeen.due||0))}`;
        const assignedDays = parseInt(takhmeen.assigned_thaali_days || 0, 10) || 0;
        const fy = (takhmeen.year || '').toString();
        const assignedHtml = `<a href="#" class="view-assigned-thaali-days" data-user-id="${(userId||'').toString()}" data-user-name="${(userName||'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}" data-year="${fy.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;')}">${assignedDays}</a>`;

        $("#due-details").append(`
        <tr>
          <td>${takhmeen.year}</td>
          <td class="text-primary">${amtTotal}</td>
          <td class="text-success">${amtPaid}</td>
          <td class="${(takhmeen.due||0) > 0 ? 'text-danger' : ''}">${amtDue}</td>
          <td>${assignedHtml}</td>
          <td>${remarkSafe || '-'}</td>
        </tr>
      `);
      });

      $("#due-overview-modal").modal("show");
    });
  });

  // Apply button now submits filters to server via GET so server-side filtering is used
  // Remove earlier client-side-only behavior and redirect to controller with query params
  $('#apply-filters-btn').on('click', function(e) {
    e.preventDefault();
    var base = '<?php echo site_url('anjuman/fmbthaalitakhmeen'); ?>';
    var its = ($('#filter-its').val() || '').toString().trim();
    var sector = ($('#filter-sector').val() || '').toString().trim();
    var sub = ($('#filter-sub-sector').val() || '').toString().trim();
    var fmbYear = ($('#fmb-year').val() || '').toString().trim();
    var memberOnlyVal = ($('#filter-member').val() || '').toString().trim();
    // If only member name is provided (no ITS/sector/sub-sector), apply client-side filter
    // NOTE: we intentionally ignore fmbYear here so quick name searches don't force a reload
    if (memberOnlyVal && !its && !sector && !sub) {
      if (typeof window.applyFmbLocalFilters === 'function') {
        window.applyFmbLocalFilters();
        return;
      }
      // fallback: continue to server redirect if local filter unavailable
    }
    var params = {};
    if (fmbYear) params.fmb_year = fmbYear;
    if (its) params.its = its;
    if (sector) params.sector = sector;
    if (sub) params.sub_sector = sub;
    var qs = Object.keys(params).length ? ('?' + $.param(params)) : '';
    window.location.href = base + qs;
  });

  // Clear filters: navigate to base (optionally preserve fmb_year selection)
  $('#clear-filters-btn').on('click', function(e) {
    e.preventDefault();
    var base = '<?php echo site_url('anjuman/fmbthaalitakhmeen'); ?>';
    var fmbYear = ($('#fmb-year').val() || '').toString().trim();
    var qs = fmbYear ? ('?fmb_year=' + encodeURIComponent(fmbYear)) : '';
    window.location.href = base + qs;
  });

  $(document).on("click", ".pay-takhmeen-btn", function() {
    let userId = $(this).data("user-id");
    let userName = $(this).data("user-name");
    let today = new Date().toISOString().split("T")[0];

    $("#modal_user_id").val(userId);
    $("#modal_user_name").text(userName);
    $("#modal-payment-date").val(today);
    $("#modal-amount").attr("max", $(this).data("overall-due"));

    $("#payment-method").val("");
    $("#modal-amount").val("");
    $("#remarks").val("");

    $("#pay-takhmeen-container").modal("show");
  });

  function openPaymentHistoryModal(user_id, name) {
    $('#history-user-name').text(name);
    $('#payment-history-rows').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

    $.ajax({
      url: "<?php echo base_url('anjuman/getPaymentHistory/1'); ?>",
      type: "POST",
      data: {
        user_id: user_id
      },
      dataType: "json",
      success: function(response) {
        let rows = "";
        if (response.length > 0) {
          response.forEach((payment, index) => {
            rows += `
            <tr>
              <td>${index + 1}</td>
              <td>${payment.payment_date ? (function(d){d=new Date(payment.payment_date);return (('0'+d.getDate()).slice(-2)+'-'+('0'+(d.getMonth()+1)).slice(-2)+'-'+d.getFullYear());})() : ''}</td>
              <td>${payment.payment_method}</td>
              <td>&#8377;${new Intl.NumberFormat("en-IN").format(payment.amount)}</td>
              <td>${payment.remarks ?? ''}</td>
              <td>
                <button class="view-invoice btn btn-sm btn-primary" data-payment-id="${payment.id}">View Receipt</button>
                <button class="delete-payment btn btn-sm btn-danger ml-2" data-payment-id="${payment.id}"><i class="fa-solid fa-trash"></i></button>
              </td>
            </tr>
          `;
          });
        } else {
          rows = `<tr><td colspan="6" class="text-center">No payment history found</td></tr>`;
        }
        $('#payment-history-rows').html(rows);
        // Delete payment handler
        $(document).on("click", ".delete-payment", function(e) {
          e.preventDefault();
          const paymentId = $(this).data("payment-id");
          if (!confirm("Are you sure you want to delete this payment?")) return;
          $.ajax({
            url: "<?php echo base_url('anjuman/delete_takhmeen_payment'); ?>",
            type: "POST",
            data: {
              payment_id: paymentId,
              for: 1
            },
            success: function(res) {
              let parsed;
              try {
                parsed = JSON.parse(res);
              } catch (e) {
                parsed = {
                  success: false
                };
              }
              if (parsed.success) {
                alert("Payment deleted successfully!");
                $("button[data-payment-id='" + paymentId + "']").closest("tr").remove();
                window.location.reload();
              } else {
                alert("Delete failed: " + (parsed.message || "Unknown error"));
              }
            },
            error: function() {
              alert("Failed to delete payment");
            }
          });
        });
      },
      error: function() {
        $('#payment-history-rows').html('<tr><td colspan="6" class="text-center text-danger">Error loading history</td></tr>');
      }
    });

    $('#payment-history-container').modal('show');
  }

  $(document).on("click", ".view-invoice", function(e) {
    e.preventDefault();
    const paymentId = $(this).data("payment-id");

    $.ajax({
      url: "<?php echo base_url('common/generate_pdf'); ?>",
      type: "POST",
      data: {
        id: paymentId,
        for: 1,
      },
      xhrFields: {
        responseType: 'blob'
      },
      success: function(response) {
        var blob = new Blob([response], {
          type: "application/pdf"
        });
        var url = window.URL.createObjectURL(blob);
        window.open(url, "_blank");
      },
      error: function() {
        alert("Failed to generate receipt PDF");
      }
    });
  });

  $(".alert").hide(3000);

  $(document).on('click', '.view-assigned-thaali-days', function(e) {
    e.preventDefault();
    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    const year = $(this).data('year');

    $('#assigned-thaali-days-member').text(userName || '');
    $('#assigned-thaali-days-year').text(year || '');
    $('#assigned-thaali-days-list').empty();
    $('#assigned-thaali-days-empty').hide();
    $('#assigned-thaali-days-loading').show();

    $('#assigned-thaali-days-modal').modal('show');

    $.ajax({
      url: "<?php echo base_url('anjuman/getfmbassignedthaalidates'); ?>",
      type: 'POST',
      dataType: 'json',
      data: {
        user_id: userId,
        year: year
      },
      success: function(res) {
        $('#assigned-thaali-days-loading').hide();
        const dates = (res && res.success && Array.isArray(res.dates)) ? res.dates : [];
        if (!dates.length) {
          $('#assigned-thaali-days-empty').show();
          return;
        }
        dates.forEach(function(d) {
          // d is expected as YYYY-MM-DD
          let label = d;
          if (typeof d === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(d)) {
            const parts = d.split('-');
            label = parts[2] + '-' + parts[1] + '-' + parts[0];
          }
          $('#assigned-thaali-days-list').append('<li>' + label + '</li>');
        });
      },
      error: function() {
        $('#assigned-thaali-days-loading').hide();
        $('#assigned-thaali-days-empty').text('Failed to load assigned dates.').show();
      }
    });
  });

  // Improved sortable columns for main takhmeen table
  (function() {
    const table = document.getElementById('takhmeen-table');
    if (!table) return;
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    // Capture original order
    Array.from(tbody.querySelectorAll('tr')).forEach((tr, i) => {
      tr.dataset.originalIndex = i;
    });

    thead.querySelectorAll('th').forEach((th, idx) => {
      const label = th.textContent.trim();
      if (idx === 0 || label === 'Action') return; // skip serial and action
      th.classList.add('sortable');
      th.innerHTML = '<span class="sort-label">' + label + '</span><span class="sort-indicator" aria-hidden="true"></span>';
      th.setAttribute('role', 'button');
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => toggleSort(idx, th));
    });

    function getCellValue(tr, index) {
      const cells = tr.querySelectorAll('td');
      if (!cells[index]) return '';
      // Prefer explicit data-sort-value
      let raw = cells[index].getAttribute('data-sort-value');
      if (raw === null) {
        raw = cells[index].textContent;
      }
      raw = raw.replace(/₹|,/g, '').replace(/\s+/g, ' ').trim();
      return raw;
    }

    function inferType(val) {
      if (/^[-+]?\d+(\.\d+)?$/.test(val)) return 'number';
      return 'text';
    }

    function normalize(val) {
      const t = inferType(val);
      if (t === 'number') return parseFloat(val) || 0;
      return val.toLowerCase();
    }

    function toggleSort(idx, th) {
      const newDir = th.dataset.sortDir === 'asc' ? 'desc' : 'asc';
      thead.querySelectorAll('th.sortable').forEach(h => {
        h.dataset.sortDir = '';
        const ind = h.querySelector('.sort-indicator');
        if (ind) ind.textContent = '';
      });
      th.dataset.sortDir = newDir;
      const ind = th.querySelector('.sort-indicator');
      if (ind) ind.textContent = newDir === 'asc' ? '▲' : '▼';
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.sort((a, b) => {
        const va = normalize(getCellValue(a, idx));
        const vb = normalize(getCellValue(b, idx));
        if (va < vb) return newDir === 'asc' ? -1 : 1;
        if (va > vb) return newDir === 'asc' ? 1 : -1;
        return 0;
      });
      rows.forEach(r => tbody.appendChild(r));
      renumberVisible();
    }

    // Reset button logic
    const resetBtn = document.getElementById('reset-sort-btn');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.sort((a, b) => parseInt(a.dataset.originalIndex, 10) - parseInt(b.dataset.originalIndex, 10));
        rows.forEach(r => tbody.appendChild(r));
        // Clear indicators
        thead.querySelectorAll('th.sortable').forEach(h => {
          h.dataset.sortDir = '';
          const ind = h.querySelector('.sort-indicator');
          if (ind) ind.textContent = '';
        });
        applyFilters(); // keep current filters applied after reset
      });
    }

    // Filtering logic
    const memberInput = document.getElementById('filter-member');
    const sectorSelect = document.getElementById('filter-sector');
    const subSectorSelect = document.getElementById('filter-sub-sector');
    const applyBtn = document.getElementById('apply-filters-btn');
    const clearBtn = document.getElementById('clear-filters-btn');

    function applyFilters() {
      const nameVal = (memberInput.value || '').toLowerCase().trim();
      const sectorVal = sectorSelect.value;
      const subVal = subSectorSelect.value;
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.forEach(r => {
        const cells = r.querySelectorAll('td');
        if (cells.length < 5) {
          r.style.display = '';
          return;
        }
        const itsCell = cells[1].textContent.toLowerCase();
        const nameCell = cells[2].textContent.toLowerCase();
        const sectorCell = cells[3].textContent.trim();
        const subCell = cells[4].textContent.trim();
        let show = true;
        if (nameVal && !(nameCell.includes(nameVal) || itsCell.includes(nameVal))) show = false;
        if (sectorVal && sectorCell !== sectorVal) show = false;
        if (subVal && subCell !== subVal) show = false;
        r.style.display = show ? '' : 'none';
      });
      renumberVisible();
    }

    function renumberVisible() {
      let counter = 1;
      Array.from(tbody.querySelectorAll('tr')).forEach(r => {
        if (r.style.display === 'none') return;
        const idxCell = r.querySelector('.row-index');
        if (idxCell) idxCell.textContent = counter++;
      });
    }

    // The apply/clear buttons above perform server navigation (server-side filtering).
    // Keep live filtering on Enter/change for local member name/sector/sub-sector controls.
    [memberInput, sectorSelect, subSectorSelect].forEach(el => {
      if (el) el.addEventListener('keyup', e => {
        if (e.key === 'Enter') applyFilters();
      });
      if (el) el.addEventListener('change', applyFilters);
    });
    // Expose local filter function for use by outer handlers
    window.applyFmbLocalFilters = applyFilters;

    [memberInput, sectorSelect, subSectorSelect].forEach(el => {
      if (el) el.addEventListener('keyup', e => {
        if (e.key === 'Enter') applyFilters();
      });
      if (el) el.addEventListener('change', applyFilters);
    });
    renumberVisible();
  })();
</script>