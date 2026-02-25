<?php $this->load->view('Accounts/Header'); ?>

<div class="container margintopcontainer pt-5">
    <div class="row align-items-center mb-4 pt-5">
        <div class="col-12 col-md-3 text-center text-md-left mb-3 mb-md-0">
            <a href="<?= base_url('accounts/home') ?>" class="btn btn-outline-secondary btn-sm" style="padding: 0.25rem 0.6rem; border-radius: 8px;">
                <i class="fa fa-arrow-left" style="font-size: 1.1rem;"></i>
            </a>
        </div>
        <div class="col-12 col-md-6 text-center">
            <h2 class="heading mb-0">Laagat & Rent Invoices</h2>
        </div>
        <div class="col-md-3 d-none d-md-block"></div>
    </div>

    <?php if (empty($invoices)): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-info shadow-sm border-0">
                    <i class="fa fa-info-circle mr-2"></i> No Laagat or Rent invoices found for your family.
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <?php foreach ($invoices as $inv): ?>
                <?php 
                    $due = (float)$inv['master_amount'] - (float)$inv['paid_amount'];
                    $status_class = $due <= 0 ? 'success' : 'danger';
                    $is_paid = $due <= 0;
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-title mb-0 text-primary"><?= htmlspecialchars($inv['title']) ?></h5>
                                    <div class="small text-muted"><?= htmlspecialchars($inv['raza_type_name'] ?: '') ?></div>
                                    <?php if ($is_paid): ?>
                                        <span class="badge badge-success px-2 py-1 mt-1 rounded-pill">Paid</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <div class="small text-muted">Invoice ID: #<?= $inv['id'] ?></div>
                                  
                                    <div class="font-weight-bold"> Raza ID : R#<?= htmlspecialchars($inv['generated_raza_id'] ?: 'N/A') ?></div>
                                </div>
                            </div>
                            
                            <div class="row border-top pt-3">
                                <div class="col-4">
                                    <div class="small text-muted">Total Amount</div>
                                    <div class="font-weight-bold">₹<?= format_inr((float)$inv['master_amount'], 0) ?></div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="small text-muted">Paid</div>
                                    <div class="font-weight-bold text-success">₹<?= format_inr((float)$inv['paid_amount'], 0) ?></div>
                                </div>
                                <div class="col-4 text-right">
                                    <div class="small text-muted">Outstanding</div>
                                    <div class="font-weight-bold text-<?= $status_class ?>">₹<?= format_inr($due, 0) ?></div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-4" 
                                        onclick="showHistory(<?= $inv['id'] ?>, '<?= htmlspecialchars($inv['title']) ?>')">
                                    <i class="fa fa-history mr-1"></i> Payment History
                                </button>
                            </div>

                            <div class="mt-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><?= htmlspecialchars($inv['hijri_year']) ?>H</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold">Payment History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="small text-muted">Raza Type</div>
                    <div class="h6 mb-0 font-weight-bold text-primary" id="history_title"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm border-0">
                        <thead>
                            <tr class="text-muted small">
                                <th class="border-0">Date</th>
                                <th class="border-0">Method</th>
                                <th class="border-0 text-right">Amount</th>
                                <th class="border-0 text-center">Receipt</th>
                            </tr>
                        </thead>
                        <tbody id="history_table_body">
                            <!-- Content loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light btn-block rounded-pill" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showHistory(invoiceId, title) {
    $('#history_title').text(title);
    $('#history_table_body').html('<tr><td colspan="4" class="text-center py-4"><i class="fa fa-spinner fa-spin mr-2"></i> Loading history...</td></tr>');
    $('#historyModal').modal('show');

    $.ajax({
        url: '<?= base_url("accounts/get_laagat_payment_history"); ?>',
        type: 'POST',
        data: { invoice_id: invoiceId },
        dataType: 'json',
        success: function(response) {
            let html = '';
            if (response && response.length > 0) {
                response.forEach(function(item) {
                    let d = new Date(item.payment_date);
                    let dateStr = d.toLocaleDateString('en-GB').replace(/\//g, '-');
                    let receiptUrl = '<?= base_url("accounts/laagat_receipt/"); ?>' + item.id;

                    html += '<tr>' +
                            '<td class="align-middle">' + dateStr + '</td>' +
                            '<td class="align-middle"><span class="badge badge-light px-2 py-1">' + (item.payment_method || 'Cash') + '</span></td>' +
                            '<td class="align-middle text-right font-weight-bold text-success">₹' + parseFloat(item.amount).toLocaleString('en-IN', {minimumFractionDigits: 0}) + '</td>' +
                            '<td class="align-middle text-center">' +
                            '  <a href="' + receiptUrl + '" class="btn btn-outline-primary btn-xs rounded-pill" target="_blank">' +
                            '    <i class="fa fa-file-invoice"></i>' +
                            '  </a>' +
                            '</td>' +
                            '</tr>';
                });
            } else {
                html = '<tr><td colspan="4" class="text-center py-4 text-muted small">No payment records found for this invoice.</td></tr>';
            }
            $('#history_table_body').html(html);
        },
        error: function() {
            $('#history_table_body').html('<tr><td colspan="3" class="text-center py-4 text-danger small">Failed to load history. Please try again.</td></tr>');
        }
    });
}
</script>
