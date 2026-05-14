<?php
// Small fallback in case the helper isn't loaded in this context
if (!function_exists('format_inr')) {
	function format_inr($num, $dec = 0)
	{
		return number_format((float)$num, (int)$dec, '.', ',');
	}
}

$filters = isset($filters) && is_array($filters) ? $filters : ['from' => '', 'to' => ''];
$payments = isset($payments) && is_array($payments) ? $payments : [];
?>

<div class="container-fluid px-md-5 margintopcontainer pt-5">
	<div class="d-flex align-items-center mb-4">
		<a href="<?= base_url('anjuman') ?>" class="btn btn-outline-secondary btn-sm" title="Back to Dashboard"><i class="fa-solid fa-arrow-left"></i></a>
		<h4 class="mb-0 mx-auto">Payments Report (Date Range)</h4>
		<div style="width: 40px;"></div>
	</div>

	<?php if (!empty($error)): ?>
		<div class="alert alert-danger" role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php endif; ?>

	<div class="card shadow-sm mb-4">
		<div class="card-body">
			<form method="get" action="<?= base_url('anjuman/payments_report') ?>" class="form-row align-items-end">
				<div class="form-group col-md-4">
					<label for="from">From</label>
					<input type="date" class="form-control" id="from" name="from" value="<?= htmlspecialchars((string)($filters['from'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
				</div>
				<div class="form-group col-md-4">
					<label for="to">To</label>
					<input type="date" class="form-control" id="to" name="to" value="<?= htmlspecialchars((string)($filters['to'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
				</div>
				<div class="form-group col-md-4">
					<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search mr-1"></i>View</button>
				</div>
			</form>

			<div class="row mt-3">
				<div class="col-12 text-center">
					<div class="d-inline-flex align-items-center p-2 px-4 bg-light rounded-pill shadow-sm border">
						<div class="text-nowrap mx-3"><span class="text-muted small">Count:</span> <span class="fw-bold"><?= (int)($total_count ?? 0) ?></span></div>
						<div class="text-muted">|</div>
						<div class="text-nowrap mx-3"><span class="text-muted small">Total Amount:</span> <span class="fw-bold text-success">₹<?= format_inr((float)($total_amount ?? 0), 0) ?></span></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-sm mb-0">
					<thead class="thead-light">
						<tr>
							<th style="min-width:120px;">Date</th>
							<th style="min-width:160px;">Source</th>
							<th style="min-width:110px;">ITS</th>
							<th style="min-width:220px;">Name</th>
							<th style="min-width:110px;" class="text-right">Amount</th>
							<th style="min-width:140px;">Method</th>
							<th style="min-width:220px;">Notes</th>
							<th style="min-width:140px;">Ref</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($payments)): ?>
							<tr>
								<td colspan="8" class="text-center text-muted">No payments found for selected date range.</td>
							</tr>
						<?php else: ?>
							<?php foreach ($payments as $row): ?>
								<?php
									$rawDt = (string)($row['payment_date'] ?? '');
									$dtLabel = $rawDt;
									$ts = strtotime($rawDt);
									if ($ts) {
										$dtLabel = date('d-m-Y', $ts);
									}
								?>
								<tr>
									<td><?= htmlspecialchars($dtLabel, ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string)($row['source'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string)($row['its_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string)($row['full_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
									<td class="text-right">₹<?= format_inr((float)($row['amount'] ?? 0), 0) ?></td>
									<td><?= htmlspecialchars((string)($row['payment_method'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string)($row['notes'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string)($row['reference'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
