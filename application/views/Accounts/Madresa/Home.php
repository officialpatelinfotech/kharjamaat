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
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Total Fees (To Collect)</div>
					<div class="money money-total">₹<?php echo htmlspecialchars($fmtMoney($totals['total_fees'] ?? 0)); ?></div>
				</div>
				<div class="col-12 col-md-6">
					<div class="text-muted" style="font-size:0.85rem; font-weight:700; letter-spacing:.02em;">Total Dues</div>
					<div class="money money-due">₹<?php echo htmlspecialchars($fmtMoney($totals['total_dues'] ?? 0)); ?></div>
				</div>
			</div>
			<div class="text-muted" style="font-size:0.85rem;">Fetched from Madresa Classes (Admin-created class fees &amp; dues) for Hijri year <?php echo (int)$selectedHy; ?>.</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white"><b>Madresa Classes</b></div>
		<div class="card-body">
			<?php if (empty($classes)) { ?>
				<p class="text-muted mb-0">No Madresa classes found for Hijri year <?php echo (int)$selectedHy; ?>.</p>
			<?php } else { ?>
				<div class="table-scroll">
					<table class="table table-striped table-sm mb-0 madresa-table">
						<thead>
							<tr>
								<th style="width:70px">#</th>
								<th>Class</th>
								<th style="width:110px">Hijri Year</th>
								<th class="text-right" style="width:110px">Students</th>
								<th class="text-right" style="width:120px">Fees</th>
								<th class="text-right" style="width:150px">Total To Collect</th>
								<th class="text-right" style="width:130px">Collected</th>
								<th class="text-right" style="width:130px">Due</th>
								<th style="width:110px">Status</th>
								<th style="width:170px">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; foreach ($classes as $c) { ?>
								<tr>
									<td><?php echo (int)$i++; ?></td>
									<td><?php echo htmlspecialchars((string)($c['class_name'] ?? '')); ?></td>
									<td><?php echo htmlspecialchars((string)($c['hijri_year'] ?? '')); ?></td>
									<td class="text-right"><?php echo htmlspecialchars((string)($c['student_count'] ?? '0')); ?></td>
									<td class="text-right">₹<?php echo htmlspecialchars($fmtMoney($c['fees'] ?? 0)); ?></td>
									<td class="text-right">₹<?php echo htmlspecialchars($fmtMoney($c['amount_to_collect'] ?? 0)); ?></td>
									<td class="text-right" style="color:#198754; font-weight:700;">₹<?php echo htmlspecialchars($fmtMoney($c['amount_collected'] ?? 0)); ?></td>
									<td class="text-right" style="color:#dc3545; font-weight:700;">₹<?php echo htmlspecialchars($fmtMoney($c['amount_due'] ?? 0)); ?></td>
									<td><?php echo htmlspecialchars((string)($c['status'] ?? '')); ?></td>
									<td>
										<a class="btn btn-sm btn-outline-primary" href="<?php echo base_url('accounts/madresa/payment-history/' . (int)($c['id'] ?? 0)); ?>">Payment History</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
