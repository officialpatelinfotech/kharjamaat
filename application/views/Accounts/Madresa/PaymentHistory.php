<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	body { background-color: #fff7e6; }
	.page-container { max-width: 1200px; }
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

		$c = !empty($madresa_class) && is_array($madresa_class) ? $madresa_class : [];
		$f = !empty($madresa_financials) && is_array($madresa_financials) ? $madresa_financials : [];
		$payments = !empty($madresa_payments) && is_array($madresa_payments) ? $madresa_payments : [];

		$toCollect = (float)($f['amount_to_collect'] ?? 0);
		$paid = (float)($f['amount_collected'] ?? ($madresa_total_paid ?? 0));
		$due = (float)($f['amount_due'] ?? max($toCollect - $paid, 0));
		$studentCount = (int)($f['student_count'] ?? 0);
	?>

	<div class="page-title-bar">
		<a href="<?php echo base_url('accounts/madresa'); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
		<h3 class="page-title">Madresa Payment History</h3>
		<div style="width:40px;height:34px;" aria-hidden="true"></div>
	</div>

	<div class="card shadow-sm mb-3">
		<div class="card-header bg-white"><b>Class Summary</b></div>
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-md-4 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Class</div>
					<div style="font-weight:700;"><?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?></div>
				</div>
				<div class="col-12 col-md-2 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Hijri Year</div>
					<div style="font-weight:700;"><?php echo htmlspecialchars((string)($c['hijri_year'] ?? '')); ?></div>
				</div>
				<div class="col-12 col-md-2 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Students</div>
					<div style="font-weight:700;"><?php echo (int)$studentCount; ?></div>
				</div>
				<div class="col-12 col-md-4 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Status</div>
					<div style="font-weight:700;"><?php echo htmlspecialchars((string)($c['status'] ?? '')); ?></div>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col-12 col-md-4 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Total To Collect</div>
					<div class="money money-total">₹<?php echo htmlspecialchars($fmtMoney($toCollect)); ?></div>
				</div>
				<div class="col-12 col-md-4 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Collected</div>
					<div class="money money-paid">₹<?php echo htmlspecialchars($fmtMoney($paid)); ?></div>
				</div>
				<div class="col-12 col-md-4 mb-2">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Due</div>
					<div class="money money-due">₹<?php echo htmlspecialchars($fmtMoney($due)); ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white"><b>Payments</b></div>
		<div class="card-body">
			<?php if (empty($payments)) { ?>
				<p class="text-muted mb-0">No payments found for this class.</p>
			<?php } else { ?>
				<div class="table-scroll">
					<table class="table table-striped table-sm mb-0">
						<thead>
							<tr>
								<th style="width:70px">#</th>
								<th style="width:120px">Paid On</th>
								<th>Student</th>
								<th style="width:110px">ITS</th>
								<th class="text-right" style="width:130px">Amount</th>
								<th style="width:130px">Mode</th>
								<th style="width:160px">Reference</th>
								<th>Notes</th>
								<th style="width:140px">Created By</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; foreach ($payments as $p) {
								$paidOn = '';
								if (!empty($p['paid_on'])) {
									$paidOn = date('d-m-Y', strtotime((string)$p['paid_on']));
								} elseif (!empty($p['created_at'])) {
									$paidOn = date('d-m-Y', strtotime((string)$p['created_at']));
								}
							?>
							<tr>
								<td><?php echo (int)$i++; ?></td>
								<td><?php echo htmlspecialchars($paidOn); ?></td>
								<td><?php echo htmlspecialchars((string)($p['student_name'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string)($p['students_its_id'] ?? '')); ?></td>
								<td class="text-right" style="font-weight:700;">₹<?php echo htmlspecialchars($fmtMoney($p['amount'] ?? 0)); ?></td>
								<td><?php echo htmlspecialchars((string)($p['payment_mode'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string)($p['reference'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string)($p['notes'] ?? '')); ?></td>
								<td><?php echo htmlspecialchars((string)($p['created_by'] ?? '')); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
