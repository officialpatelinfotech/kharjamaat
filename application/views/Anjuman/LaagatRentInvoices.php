<div class="container-fluid px-md-5 margintopcontainer pt-5">
    <?php 
        $total_inv_amt = 0;
        if (!empty($invoices)) {
            foreach($invoices as $inv) {
                $total_inv_amt += (float)$inv['master_amount'];
            }
        }
    ?>
    <div class="d-flex align-items-center mb-4">
        <a href="<?= base_url('anjuman/laagat_rent'); ?>" class="btn btn-outline-secondary btn-sm" title="Back to Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
        <h4 class="mb-0 mx-auto">Update Laagat & Rent Invoices</h4>
        <div style="width: 40px;"></div>
    </div>

    <div class="row mb-3">
        <div class="col-12 text-center">
            <div class="d-inline-flex gap-4 p-2 px-4 bg-light rounded-pill shadow-sm border">
                <div class="text-nowrap"><span class="text-muted small">Total Amount:</span> <span class="fw-bold text-primary">₹<?= format_inr($total_inv_amt, 0) ?></span></div>
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

    <!-- Filters -->
    <div class="card shadow-sm mb-4 mt-4">
        <div class="card-body">
            <form method="GET" action="<?= base_url('anjuman/laagat_rent_invoices'); ?>" class="row g-2 align-items-end">
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
                    <a href="<?= base_url('anjuman/laagat_rent_invoices'); ?>" class="btn btn-light btn-sm flex-fill">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="invoicesTable">
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
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($invoices)): ?>
                            <?php $sr = 1; foreach($invoices as $inv): ?>
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
                                    <td class="text-end fw-bold">₹<?= format_inr($inv['master_amount'], 0) ?></td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary mr-2" title="Edit Invoice" 
                                                    onclick="editInvoice(<?= htmlspecialchars(json_encode($inv)) ?>)">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <form action="<?= base_url('anjuman/laagat_rent_invoice_delete'); ?>" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Invoice">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No invoices found matching current filters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('anjuman/laagat_rent_invoice_save'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_invoice_id">
                    <div class="mb-3">
                        <label class="form-label">Member</label>
                        <p class="form-control-plaintext fw-bold" id="edit_member_name"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="amount" id="edit_invoice_amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editInvoice(data) {
    $('#edit_invoice_id').val(data.id);
    $('#edit_member_name').text(data.Full_Name + ' (' + data.ITS_ID + ')');
    $('#edit_invoice_amount').val(data.amount);
    $('#editInvoiceModal').modal('show');
}
</script>
