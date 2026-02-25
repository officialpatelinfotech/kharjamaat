<div class="container-fluid px-md-5 margintopcontainer pt-5">
    <?php 
        $total_inv_amt = 0;
        $total_paid_amt = 0;
        $total_due_amt = 0;
        if (!empty($invoices)) {
            foreach($invoices as $inv) {
                $total_inv_amt += (float)$inv['master_amount'];
                $total_paid_amt += (float)$inv['paid_amount'];
                $total_due_amt += ((float)$inv['master_amount'] - (float)$inv['paid_amount']);
            }
        }
    ?>
    <div class="d-flex align-items-center mb-4">
        <?php 
            $back_url = base_url('anjuman/laagat_rent');
            $heading = "Receive Laagat & Rent Payment";
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 2) {
                $back_url = base_url('amilsaheb');
                $heading = "Laagat & Rent Invoices";
            }
        ?>
        <a href="<?= $back_url ?>" class="btn btn-outline-secondary btn-sm" title="Back to Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
        <h4 class="mb-0 mx-auto"><?= $heading ?></h4>
        <div style="width: 40px;"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12 text-center">
            <div class="d-inline-flex align-items-center p-2 px-4 bg-light rounded-pill shadow-sm border">
                <div class="text-nowrap mx-3"><span class="text-muted small">Total Amount:</span> <span class="fw-bold text-primary">₹<?= format_inr($total_inv_amt, 0) ?></span></div>
                <div class="text-muted">|</div>
                <div class="text-nowrap mx-3"><span class="text-muted small">Total Paid:</span> <span class="fw-bold text-success">₹<?= format_inr($total_paid_amt, 0) ?></span></div>
                <div class="text-muted">|</div>
                <div class="text-nowrap mx-3"><span class="text-muted small">Total Due:</span> <span class="fw-bold text-danger">₹<?= format_inr($total_due_amt, 0) ?></span></div>
            </div>
        </div>
    </div>

    <?php if($this->session->flashdata('laagat_flash_success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('laagat_flash_success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('laagat_flash_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('laagat_flash_error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Search / Filter -->
    <div class="card shadow-sm mb-4 mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('anjuman/laagat_rent_payments'); ?>" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Hijri Year</label>
                    <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        <?php foreach($hijri_years as $y): ?>
                            <option value="<?= $y ?>" <?= ($filters['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">ITS ID / Member</label>
                    <input type="text" name="its_id" class="form-control form-control-sm" placeholder="ITS ID" value="<?= $filters['its_id'] ?>" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Type</label>
                    <select name="charge_type" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="laagat" <?= (($filters['charge_type'] ?? '') == 'laagat') ? 'selected' : '' ?>>Laagat</option>
                        <option value="rent" <?= (($filters['charge_type'] ?? '') == 'rent') ? 'selected' : '' ?>>Rent</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill"><i class="fa-solid fa-magnifying-glass me-1"></i> Filter</button>
                    <a href="<?= base_url('anjuman/laagat_rent_payments'); ?>" class="btn btn-light btn-sm flex-fill">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sr. No.</th>
                            <th>Invoice Date</th>
                            <th>ITS ID</th>
                            <th>Member Name</th>
                            <th>Charge Type</th>
                            <th>Raza Id</th>
                            <th>Raza</th>
                            <th class="text-end">Invoice Amount</th>
                            <th class="text-end">Paid Amount</th>
                            <th class="text-end">Due Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($invoices)): ?>
                            <?php $sr = 1; foreach($invoices as $inv): ?>
                                <?php 
                                    $balance = (float)$inv['master_amount'] - (float)$inv['paid_amount'];
                                ?>
                                <tr>
                                    <td><?= $sr++ ?></td>
                                    <td class="text-nowrap"><?= date('d-m-Y', strtotime($inv['created_at'])) ?></td>
                                    <td><?= $inv['ITS_ID'] ?></td>
                                    <td class="fw-bold"><?= $inv['Full_Name'] ?></td>
                                    <td>
                                        <span class="badge <?= ($inv['charge_type'] == 'rent') ? 'bg-info' : 'bg-primary' ?> text-capitalize">
                                            <?= $inv['charge_type'] ?>
                                        </span>
                                    </td>
                                    <td>R#<?= $inv['generated_raza_id'] ?></td>
                                    <td><?= $inv['title'] ?></td>
                                    <td class="text-end">₹<?= format_inr($inv['master_amount'], 0) ?></td>
                                    <td class="text-end text-success">₹<?= format_inr($inv['paid_amount'], 0) ?></td>
                                    <td class="text-end fw-bold <?= ($balance > 0) ? 'text-danger' : 'text-muted' ?>">
                                        ₹<?= format_inr($balance, 0) ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center p-2">
                                            <?php if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] != 2): ?>
                                                <?php if($balance > 0): ?>
                                                    <button type="button" class="btn btn-success btn-sm w-100 mb-2" title="Receive Payment" data-toggle="modal" data-target="#paymentModal<?= $inv['id'] ?>">
                                                        Receive Payment
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-outline-secondary btn-sm w-100 mb-2" disabled>Paid</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <button type="button" class="btn btn-outline-info btn-sm w-100" title="View History" onclick="showHistory(<?= $inv['id'] ?>, '<?= htmlspecialchars($inv['Full_Name']) ?>')">Payment History</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-magnifying-glass fa-2x mb-3 opacity-25"></i><br>
                                    Search for a member ITS ID to record payments
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-muted mb-0">Member Name</label>
                    <div class="fw-bold text-dark" id="history_member_name"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Method</th>
                                <th class="text-end">Amount</th>
                                <th>Remarks</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="history_table_body">
                            <!-- Content loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modals outside the table -->
<?php if(!empty($invoices)): ?>
    <?php foreach($invoices as $inv): ?>
        <?php 
            $balance = (float)$inv['master_amount'] - (float)$inv['paid_amount'];
            if($balance > 0): 
        ?>
            <div class="modal fade" id="paymentModal<?= $inv['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= base_url('anjuman/laagat_rent_save_payment'); ?>" method="POST">
                        <input type="hidden" name="invoice_id" value="<?= $inv['id'] ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Receive Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-left small">
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">ITS ID</label>
                                        <div class="fw-bold text-dark"><?= $inv['ITS_ID'] ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">Member Name</label>
                                        <div class="fw-bold text-dark"><?= $inv['Full_Name'] ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">Charge Type</label>
                                        <div class="text-dark text-capitalize"><?= $inv['charge_type'] ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">Raza Id</label>
                                        <div class="text-dark">R#<?= $inv['generated_raza_id'] ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">Raza</label>
                                        <div class="text-dark"><?= $inv['title'] ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-muted mb-0">Invoice Amount</label>
                                        <div class="fw-bold text-dark">₹<?= format_inr($inv['master_amount'], 0) ?></div>
                                    </div>
                                </div>

                                <div class="alert alert-light border-0 bg-light py-2 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Paid Amount:</span>
                                        <span class="text-success fw-bold">₹<?= format_inr($inv['paid_amount'], 0) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <span class="text-muted">Due Balance:</span>
                                        <span class="text-danger fw-bold">₹<?= format_inr($balance, 0) ?></span>
                                    </div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Payment Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white">₹</span>
                                        </div>
                                        <input type="number" name="amount" class="form-control fw-bold border-primary" required max="<?= $balance ?>" value="<?= $balance ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label small">Payment Date</label>
                                        <input type="date" name="payment_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Method</label>
                                        <select name="payment_method" class="form-control form-control-sm">
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="NEFT">NEFT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small">Remarks</label>
                                    <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Optional notes..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Payment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function showHistory(invoiceId, name) {
    $('#history_member_name').text(name);
    $('#history_table_body').html('<tr><td colspan="4" class="text-center font-italic">Loading history...</td></tr>');
    $('#historyModal').modal('show');

    $.ajax({
        url: '<?= base_url("anjuman/get_laagat_payment_history"); ?>',
        type: 'POST',
        data: { invoice_id: invoiceId },
        dataType: 'json',
        success: function(response) {
            let html = '';
            if (response && response.length > 0) {
                response.forEach(function(item) {
                    // Quick date format DD-MM-YYYY
                    let d = new Date(item.payment_date);
                    let day = ('0' + d.getDate()).slice(-2);
                    let month = ('0' + (d.getMonth() + 1)).slice(-2);
                    let year = d.getFullYear();
                    let dateStr = day + '-' + month + '-' + year;

                    html += '<tr>' +
                            '<td>' + dateStr + '</td>' +
                            '<td>' + (item.payment_method || '-') + '</td>' +
                            '<td class="text-end fw-bold">₹' + parseFloat(item.amount).toLocaleString('en-IN', {minimumFractionDigits: 0}) + '</td>' +
                            '<td>' + (item.remarks || '-') + '</td>' +
                            '<td class="text-center d-flex justify-content-center">' +
                            '<a href="<?= base_url("anjuman/laagat_receipt/"); ?>' + item.id + '" class="btn btn-outline-primary btn-xs mr-2" target="_blank" title="Receipt">' +
                            '<i class="fa-solid fa-file-invoice"></i>' +
                            '</a>' +
                            <?php if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] != 2): ?>
                            '<button type="button" class="btn btn-outline-danger btn-xs" onclick="deleteHistoryPayment(' + item.id + ', ' + invoiceId + ')">' +
                            '<i class="fa-solid fa-trash"></i>' +
                            '</button>' +
                            <?php endif; ?>
                            '</td>' +
                            '</tr>';
                });
            } else {
                html = '<tr><td colspan="5" class="text-center text-muted">No payments recorded for this invoice yet.</td></tr>';
            }
            $('#history_table_body').html(html);
        },
        error: function() {
            $('#history_table_body').html('<tr><td colspan="5" class="text-center text-danger">Error fetching history. Please try again.</td></tr>');
        }
    });
}

function deleteHistoryPayment(paymentId, invoiceId) {
    if (confirm('Are you sure you want to delete this payment record?')) {
        $.ajax({
            url: '<?= base_url("anjuman/laagat_rent_delete_payment"); ?>',
            type: 'POST',
            data: { payment_id: paymentId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Refresh the history table
                    let memberName = $('#history_member_name').text();
                    showHistory(invoiceId, memberName);
                    // Reload page to update background table amounts
                    window.location.reload();
                } else {
                    alert(response.message || 'Error deleting payment.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    }
}
</script>

<style>
.btn-xs {
    padding: 2px 8px;
    font-size: 0.75rem;
    line-height: 1.5;
    border-radius: 4px;
}
</style>
