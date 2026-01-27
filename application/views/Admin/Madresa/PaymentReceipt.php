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
	.page-title-right {
		position: absolute;
		right: 0;
		top: 50%;
		transform: translateY(-50%);
		display: flex;
		gap: 8px;
	}
	.page-title {
		margin: 0;
		font-size: 1.75rem;
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
	.kv { display: flex; gap: 8px; margin-bottom: 6px; }
	.k { min-width: 140px; font-weight: 700; }
	.v { flex: 1 1 auto; }
	.amt { white-space: nowrap; font-variant-numeric: tabular-nums; font-weight: 700; }
	.card { border: 1px solid #e6eaf2; border-radius: 12px; }
	hr { border-top: 1px solid #e6eaf2; }
	@media print {
		.page-title-right, .page-title-left { display: none !important; }
		body { padding-top: 0; }
	}
	@media (max-width: 576px) {
		.page-title-bar { padding: 0 90px; }
		.page-title { font-size: 1.45rem; }
	}
</style>

<div class="container-fluid mt-4 page-container">
	<?php
		$madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa';
		$fmtMoney = function ($n) {
			$n = ($n === '' || $n === null) ? 0 : (float)$n;
			$formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
			return preg_replace('/\.00$/', '', (string)$formatted);
		};
	?>

	<div class="page-title-bar">
		<div class="page-title-left">
			<a href="<?php echo base_url($madresa_base . '/classes/payment-history/' . (int)($class['id'] ?? 0)); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
		</div>
		<h3 class="page-title">Payment Receipt</h3>
		<div class="page-title-right">
			<button class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
		</div>
	</div>

	<?php if (empty($payment)) { ?>
		<div class="alert alert-warning">No payment found for this class.</div>
	<?php } else { ?>
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="kv"><div class="k">Receipt No</div><div class="v">MAD-<?php echo (int)($payment['id'] ?? 0); ?></div></div>
				<div class="kv"><div class="k">Date</div><div class="v"><?php echo htmlspecialchars((string)($payment['paid_on'] ?? ($payment['created_at'] ?? ''))); ?></div></div>
				<hr>
				<div class="kv"><div class="k">Class</div><div class="v"><?php echo htmlspecialchars((string)($class['class_name'] ?? '')); ?></div></div>
				<div class="kv"><div class="k">Hijri Year</div><div class="v"><?php echo !empty($class['hijri_year']) ? (int)$class['hijri_year'] : ''; ?></div></div>
				<div class="kv"><div class="k">Student</div><div class="v"><?php
						$name = !empty($payment['student_name']) ? (string)$payment['student_name'] : '';
						$its = !empty($payment['students_its_id']) ? (string)$payment['students_its_id'] : '';
						$label = trim($name) !== '' ? $name : ($its !== '' ? ('ITS ' . $its) : '-');
						echo htmlspecialchars($label);
					?></div></div>
				<hr>
				<div class="kv"><div class="k">Amount Paid</div><div class="v"><span class="amt">₹<?php echo htmlspecialchars($fmtMoney($payment['amount'] ?? 0)); ?></span></div></div>
				<div class="kv"><div class="k">Payment Mode</div><div class="v"><?php echo htmlspecialchars((string)($payment['payment_mode'] ?? '')); ?></div></div>
				<div class="kv"><div class="k">Reference</div><div class="v"><?php echo htmlspecialchars((string)($payment['reference'] ?? '')); ?></div></div>
				<?php if (!empty($payment['notes'])) { ?>
					<div class="kv"><div class="k">Notes</div><div class="v"><?php echo nl2br(htmlspecialchars((string)$payment['notes'])); ?></div></div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
