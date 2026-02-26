<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	body { background-color: #fff7e6; }
	.page-container { max-width: 1400px; margin-left: auto; margin-right: auto; }
	.page-title-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
		margin-bottom: 14px;
	}
	.page-title {
		flex: 1 1 auto;
		text-align: center;
		font-size: 2rem;
		font-weight: 700;
		margin: 0;
		color: #1b3a57;
	}
	.btn-back {
		width: 40px;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0;
		border-radius: 4px;
		font-size: 18px;
		line-height: 1;
		font-weight: 700;
	}
	.btn-back .back-arrow {
		font-weight: 900;
		-webkit-text-stroke: 0.35px currentColor;
		text-shadow: 0.35px 0 0 currentColor, -0.35px 0 0 currentColor;
		display: inline-block;
	}
	.card { border: 1px solid #e6eaf2; border-radius: 12px; }
	.card-header { background: #f8fafc !important; border-bottom: 1px solid #e6eaf2; }
	.money { font-variant-numeric: tabular-nums; font-weight: 700; white-space: nowrap; }
	.money-total { color: #0d6efd; }
	.money-paid { color: #198754; }
	.money-due { color: #dc3545; }
	.table-scroll {
		width: 100%;
		overflow: auto;
		max-height: 65vh;
		-webkit-overflow-scrolling: touch;
		-ms-overflow-style: none;
		scrollbar-width: none;
	}
	.madresa-table th, .madresa-table td { white-space: nowrap; }
	.table-scroll::-webkit-scrollbar { width: 0; height: 0; }
	@media (max-width: 768px) {
		.page-title { font-size: 1.6rem; }
		.page-container { padding-left: 12px; padding-right: 12px; }
	}
</style>

<div class="container page-container margintopcontainer pt-5 pb-4">
	<?php
		$fmtMoney = function ($n) {
			$n = ($n === '' || $n === null) ? 0 : (float)$n;
			$formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
			return preg_replace('/\.00$/', '', (string)$formatted);
		};

		$totals = !empty($madresa_totals) && is_array($madresa_totals) ? $madresa_totals : [];
		$classes = !empty($madresa_classes) && is_array($madresa_classes) ? $madresa_classes : [];
		$selectedHy = !empty($selected_hijri_year) ? (int)$selected_hijri_year : 0;
	?>

	<div class="page-title-bar">
		<a href="<?php echo base_url('accounts/home'); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
		<h3 class="page-title">Madresa Fees &amp; Dues</h3>
		<div style="width:40px;height:34px;" aria-hidden="true"></div>
	</div>

	<div class="card shadow-sm mb-3">
		<div class="card-header bg-white"><b>Totals</b></div>
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-md-6 mb-3 mb-md-0">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Total Fees</div>
					<div class="money money-total">₹<?php echo htmlspecialchars($fmtMoney($totals['total_fees'] ?? 0)); ?></div>
				</div>
				<div class="col-12 col-md-6">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Total Dues</div>
					<div class="money money-due">₹<?php echo htmlspecialchars($fmtMoney($totals['total_dues'] ?? 0)); ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white"><b>Classes</b></div>
		<div class="card-body">
			<?php if (empty($classes)) { ?>
				<p class="text-muted mb-0">No Madresa classes found for Hijri year <?php echo (int)$selectedHy; ?>.</p>
			<?php } else { ?>
				<div class="table-scroll">
					<table class="table table-striped table-sm mb-0 madresa-table">
						<thead>
							<tr>
								<th style="width:60px">Sr.No</th>
								<th>Class</th>
								<th>Hijri Year</th>
								<th class="text-right" style="width:120px">Fees</th>
								<th class="text-right" style="width:130px">Paid</th>
								<th class="text-right" style="width:130px">Due</th>
								<th style="width:110px; padding-left: 20px;">Status</th>
								<th style="width:170px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; foreach ($classes as $c) { ?>
								<tr>
									<td><?php echo (int)$i++; ?></td>
									<td><?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?></td>
									<td><?php echo htmlspecialchars((string)($c['hijri_year'] ?? '')); ?></td>
									<td class="text-right">₹<?php echo htmlspecialchars($fmtMoney($c['fees'] ?? 0)); ?></td>
									<td class="text-right" style="color:#198754; font-weight:700;">₹<?php echo htmlspecialchars($fmtMoney($c['amount_paid'] ?? 0)); ?></td>
									<td class="text-right" style="color:#dc3545; font-weight:700;">₹<?php echo htmlspecialchars($fmtMoney($c['amount_due'] ?? 0)); ?></td>
									<td style="padding-left: 20px;">
										<?php $statusVal = (string)($c['status'] ?? 'Active'); ?>
										<span class="badge text-white" style="background-color: <?php echo strtolower($statusVal) === 'active' ? '#17a2b8' : '#6c757d'; ?>; padding: 0.4em 0.6em; font-size: 0.85rem; border-radius: 4px;">
											<?php echo htmlspecialchars($statusVal); ?>
										</span>
									</td>
									<td>
										<button class="btn btn-sm btn-outline-primary btn-history" 
											data-class-id="<?php echo (int)($c['class_id'] ?? 0); ?>" 
											data-student-its-id="<?php echo htmlspecialchars((string)($c['students_its_id'] ?? '')); ?>"
											data-student-name="<?php echo htmlspecialchars((string)($c['student_name'] ?? '')); ?>"
											data-class-name="<?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?>">
											Payment History
										</button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

<!-- Payment History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="historyModalLabel">Payment History</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<div id="modal-student-info" class="fw-bold"></div>
					<div id="modal-class-info" class="text-muted small"></div>
				</div>
				<div class="table-responsive">
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Date</th>
								<th>Method</th>
								<th>Description</th>
								<th class="text-right">Amount</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="history-table-body">
							<!-- Populated via AJAX -->
						</tbody>
					</table>
				</div>
				<div id="history-loading" class="text-center py-3" style="display:none;">
					<div class="spinner-border spinner-border-sm text-primary" role="status"></div>
					<span class="ms-2">Loading...</span>
				</div>
				<div id="history-empty" class="text-center py-3 text-muted" style="display:none;">
					No payment history found.
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const historyTableBody = document.getElementById('history-table-body');
	const historyLoading = document.getElementById('history-loading');
	const historyEmpty = document.getElementById('history-empty');
	const modalStudentInfo = document.getElementById('modal-student-info');
	const modalClassInfo = document.getElementById('modal-class-info');

	function formatINR(amount) {
		const n = parseFloat(amount) || 0;
		const formatted = new Intl.NumberFormat('en-IN', {
			minimumFractionDigits: 0,
			maximumFractionDigits: 2
		}).format(n);
		return formatted;
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	document.querySelectorAll('.btn-history').forEach(btn => {
		btn.addEventListener('click', function() {
			const classId = this.getAttribute('data-class-id');
			const studentItsId = this.getAttribute('data-student-its-id');
			const studentName = this.getAttribute('data-student-name');
			const className = this.getAttribute('data-class-name');

			modalStudentInfo.textContent = studentName + ' (' + studentItsId + ')';
			modalClassInfo.textContent = 'Class: ' + className;

			historyTableBody.innerHTML = '';
			historyLoading.style.display = 'block';
			historyEmpty.style.display = 'none';

			$('#historyModal').modal('show');

			fetch('<?php echo base_url('accounts/ajax-madresa-payment-history'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'class_id=' + classId + '&student_its_id=' + studentItsId
			})
			.then(response => response.json())
			.then(res => {
				historyLoading.style.display = 'none';
				if (res.success && res.payments && res.payments.length > 0) {
					let html = '';
					res.payments.forEach(p => {
						let dateStr = p.paid_on || p.created_at || '';
						if (dateStr && dateStr !== '0000-00-00' && dateStr !== '0000-00-00 00:00:00') {
							try {
								const d = new Date(dateStr.replace(/-/g, '/')); // Use / for better cross-browser compatibility
								if (!isNaN(d.getTime())) {
									const day = String(d.getDate()).padStart(2, '0');
									const month = String(d.getMonth() + 1).padStart(2, '0');
									const year = d.getFullYear();
									dateStr = `${day}-${month}-${year}`;
								}
							} catch(e) { /* fallback to raw */ }
						} else {
							dateStr = '-';
						}
						
						const method = p.payment_mode ? escapeHtml(p.payment_mode) : '-';
						const desc = p.notes ? escapeHtml(p.notes) : '-';
						const receiptUrl = '<?php echo base_url('madresa/classes/payment-receipt/'); ?>' + p.m_class_id + '?payment_id=' + p.id;
						
						html += `<tr>
							<td>${dateStr}</td>
							<td>${method}</td>
							<td>${desc}</td>
							<td class="text-right fw-bold text-success">₹${formatINR(p.amount)}</td>
							<td class="text-center">
								<a href="${receiptUrl}" class="btn btn-sm btn-outline-primary" target="_blank" title="View Receipt">
									<i class="fas fa-file-invoice"></i> Receipt
								</a>
							</td>
						</tr>`;
					});
					historyTableBody.innerHTML = html;
				} else {
					historyEmpty.style.display = 'block';
				}
			})
			.catch(err => {
				console.error('Error fetching history:', err);
				historyLoading.style.display = 'none';
				historyEmpty.textContent = 'Error loading history.';
				historyEmpty.style.display = 'block';
			});
		});
	});
});
</script>
			<?php } ?>
		</div>
	</div>
</div>
