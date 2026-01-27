<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	body { padding-top: 70px; background: #f5f7fb; }
	.page-container { max-width: 900px; }
	.page-title-bar {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 44px;
		margin-bottom: 1rem;
		padding: 0 140px;
		background: transparent;
		border: 0;
		border-radius: 0;
		box-shadow: none;
	}
	.page-title-left {
		position: absolute;
		left: 0;
		top: 50%;
		transform: translateY(-50%);
	}
	.page-title {
		margin: 0;
		font-size: 1.65rem;
		font-weight: 700;
		text-align: center;
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
	.label { color: #6c757d; font-size: 0.78rem; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
	.value { color: #1b3a57; font-weight: 500; font-size: 1.05rem; }
	.money { font-variant-numeric: tabular-nums; font-weight: 700; white-space: nowrap; }
	.money-total { color: #0d6efd; }
	.money-paid { color: #198754; }
	.money-due { color: #dc3545; }
	@media (max-width: 576px) {
		.page-title-bar { padding: 0 90px; }
		.page-title { font-size: 1.35rem; }
	}
</style>

<div class="container-fluid mt-4 page-container">
	<?php
		$madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'madresa';
		$fmtMoney = function ($n) {
			$n = ($n === '' || $n === null) ? 0 : (float)$n;
			$formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
			return preg_replace('/\.00$/', '', (string)$formatted);
		};
		$message = !empty($message) ? (string)$message : '';
		$error = !empty($error) ? (string)$error : '';
		$classId = (int)($class['id'] ?? 0);
		$itsId = (int)($student['ITS_ID'] ?? 0);
		$defaultPaidOn = date('Y-m-d');
	?>

	<div class="page-title-bar">
		<div class="page-title-left">
			<a href="<?php echo base_url($madresa_base . '/classes/view/' . $classId); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
		</div>
		<h3 class="page-title">Receive Payment</h3>
	</div>

	<?php if ($message !== '') { ?>
		<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
	<?php } ?>
	<?php if ($error !== '') { ?>
		<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
	<?php } ?>

	<div class="card shadow-sm mb-3">
		<div class="card-header bg-white"><b>Student & Class</b></div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-6">
					<div class="label">Student</div>
					<div class="value"><?php echo htmlspecialchars((string)($student['Full_Name'] ?? '')); ?></div>
					<div class="text-muted">ITS <?php echo $itsId; ?></div>
				</div>
				<div class="col-md-6">
					<div class="label">Class</div>
					<div class="value"><?php echo htmlspecialchars((string)($class['class_name'] ?? '')); ?></div>
					<div class="text-muted">Hijri Year <?php echo !empty($class['hijri_year']) ? (int)$class['hijri_year'] : '-'; ?></div>
				</div>
				<div class="col-12">
					<div class="row g-3">
						<div class="col-md-4">
							<div class="label">To Be Collected</div>
							<div class="money money-total">₹<?php echo htmlspecialchars($fmtMoney($student['amount_to_collect'] ?? 0)); ?></div>
						</div>
						<div class="col-md-4">
							<div class="label">Paid</div>
							<div class="money money-paid">₹<?php echo htmlspecialchars($fmtMoney($student['amount_collected'] ?? 0)); ?></div>
						</div>
						<div class="col-md-4">
							<div class="label">Due</div>
							<div class="money money-due">₹<?php echo htmlspecialchars($fmtMoney($student['amount_due'] ?? 0)); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white"><b>Payment Details</b></div>
		<div class="card-body">
			<form method="post" action="<?php echo base_url($madresa_base . '/classes/receive-payment/' . $classId . '/' . $itsId); ?>">
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Amount (₹)</label>
						<input type="number" step="0.01" min="0" name="amount" class="form-control" required>
					</div>
					<div class="col-md-4">
						<label class="form-label">Paid On</label>
						<input type="date" name="paid_on" class="form-control" value="<?php echo htmlspecialchars($defaultPaidOn); ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">Payment Mode</label>
						<input type="text" name="payment_mode" class="form-control" placeholder="Cash / UPI / Bank" maxlength="50">
					</div>
					<div class="col-md-6">
						<label class="form-label">Reference</label>
						<input type="text" name="reference" class="form-control" maxlength="100">
					</div>
					<div class="col-12">
						<label class="form-label">Notes</label>
						<textarea name="notes" class="form-control" rows="3"></textarea>
					</div>
					<div class="col-12 d-flex gap-2 flex-wrap">
						<button type="submit" class="btn btn-success">Save Payment</button>
						<a class="btn btn-outline-secondary" href="<?php echo base_url($madresa_base . '/classes/payment-history/' . $classId . '?students_its_id=' . $itsId); ?>">Payment History</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
