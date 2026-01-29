<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	body { padding-top: 70px; background: #f5f7fb; }
	.page-container { max-width: 1200px; }
	.page-title-bar {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 44px;
		margin-bottom: 1rem;
		padding: 0 120px;
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
	@media (max-width: 576px) {
		.page-title-bar { padding: 0 90px; }
		.page-title { font-size: 1.45rem; }
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
	.amt { white-space: nowrap; font-variant-numeric: tabular-nums; font-weight: 700; }
	.amt-total { color: #6c757d; }
	.amt-paid { color: #198754; }
	.amt-due { color: #dc3545; }
	.card { border: 1px solid #e6eaf2; border-radius: 12px; }
	.card-header { background: #f8fafc !important; border-bottom: 1px solid #e6eaf2; }
	.status-pill {
		display: inline-block;
		padding: 2px 10px;
		border-radius: 999px;
		font-size: 0.9rem;
		font-weight: 700;
	}
	.status-active { background: rgba(25,135,84,0.12); color: #198754; }
	.status-inactive { background: rgba(220,53,69,0.12); color: #dc3545; }
	.action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
	.action-buttons .btn { white-space: nowrap; }
	.info-card { background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%); }
	.info-card .card-body { padding: 18px 22px; }
	.info-label { color: #6c757d; font-size: 0.78rem; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
	.info-value { color: #1b3a57; font-weight: 500; font-size: 1.05rem; }
	.info-fee { color: #0d6efd; font-size: 1.15rem; font-weight: 500; }
	.totals-row { margin-top: 12px; padding-top: 12px; border-top: 1px dashed #e6eaf2; }
	.money-label { color: #6c757d; font-size: 0.78rem; font-weight: 800; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
	.money-value { font-variant-numeric: tabular-nums; font-weight: 700; font-size: 1.05rem; white-space: nowrap; }
	.money-total { color: #0d6efd; }
	.money-paid { color: #198754; }
	.money-due { color: #dc3545; }

	/* Scrollable students table and sortable headers */
	.table-scroll { -webkit-overflow-scrolling: touch; }
	.assigned-students-table thead th { cursor: pointer; }
	.assigned-students-table thead th:after { content: '\25B4\25BE'; font-size:0.7rem; color:#c3c9d6; margin-left:6px; }
</style>

<div class="container-fluid mt-4 page-container">
	<?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa'; ?>
	<?php
		$fmtMoney = function ($n) {
			$n = ($n === '' || $n === null) ? 0 : (float)$n;
			$formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
			return preg_replace('/\.00$/', '', (string)$formatted);
		};
	?>
	<div class="row">
		<div class="col-12">
			<div class="page-title-bar">
				<div class="page-title-left">
					<a href="<?php echo !empty($is_jamaat) ? base_url('madresa') : base_url($madresa_base . '/classes'); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
				</div>
				<h3 class="page-title">Class Details</h3>
				<div class="page-title-right">
					<?php if (empty($is_jamaat)) { ?>
						<a href="<?php echo base_url($madresa_base . '/classes/edit/' . (int)$class['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
					<?php } ?>
				</div>
			</div>

			<div class="card shadow-sm mb-3 info-card">
				<div class="card-body">
					<div class="row g-3 align-items-start">
						<div class="col-md-4 text-start">
							<div class="info-label">Class</div>
							<div class="info-value"><?php echo htmlspecialchars((string)($class['class_name'] ?? '')); ?></div>
						</div>
						<div class="col-md-4 text-start text-md-center">
							<div class="info-label">Hijri Year</div>
							<div class="info-value"><?php echo !empty($class['hijri_year']) ? (int)$class['hijri_year'] : '-'; ?></div>
						</div>
						<div class="col-md-4 text-start text-md-end">
							<div class="info-label">Status</div>
							<div class="info-value">
								<?php
									$st = trim((string)($class['status'] ?? ''));
									$stLower = strtolower($st);
									$stClass = ($stLower === 'active') ? 'status-pill status-active' : 'status-pill status-inactive';
								?>
								<span class="<?php echo $stClass; ?>"><?php echo htmlspecialchars($st !== '' ? $st : '-'); ?></span>
							</div>
						</div>
						<div class="col-12 totals-row">
							<div class="row g-3">
								<div class="col-md-4">
									<div class="money-label">Total To Be Collected</div>
									<div class="money-value money-total">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_to_collect'] ?? 0)); ?></div>
								</div>
								<div class="col-md-4 text-center">
									<div class="money-label">Total Paid</div>
									<div class="money-value money-paid">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_collected'] ?? 0)); ?></div>
								</div>
								<div class="col-md-4">
									<div class="money-label">Total Due</div>
									<div class="money-value money-due">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_due'] ?? 0)); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card shadow-sm">
				<div class="card-header bg-white"><b>Assigned Students</b></div>
				<div class="card-body">
					<?php if (empty($students)) { ?>
						<p class="text-muted mb-0">No students assigned.</p>
							<?php } else { ?>
								<div class="table-scroll" style="max-height:320px; overflow:auto;">
									<table class="table table-striped mb-0 assigned-students-table">
										<thead>
											<tr>
												<th style="width:140px" data-type="number">ITS ID</th>
												<th data-type="string">Name</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($students as $s) { ?>
												<tr>
													<td><?php echo (int)$s['ITS_ID']; ?></td>
													<td><?php echo htmlspecialchars((string)$s['Full_Name']); ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function() {
		// Simple client-side sorting for Assigned Students table
		var $table = $('.assigned-students-table');
		if (!$table.length) return;
		var $tbody = $table.find('tbody');
		$table.find('th').each(function(col) {
			var $th = $(this);
			$th.on('click', function() {
				var type = $th.data('type') || 'string';
				var asc = !$th.data('asc');
				$th.data('asc', asc);
				$table.find('th').not($th).removeData('asc');
				var rows = $tbody.find('tr').get();
				rows.sort(function(a, b) {
					var A = $(a).children().eq(col).text().trim();
					var B = $(b).children().eq(col).text().trim();
					if (type === 'number') {
						var an = parseFloat(A.replace(/[^0-9.-]/g, '')) || 0;
						var bn = parseFloat(B.replace(/[^0-9.-]/g, '')) || 0;
						return asc ? an - bn : bn - an;
					}
					A = A.toLowerCase(); B = B.toLowerCase();
					if (A < B) return asc ? -1 : 1;
					if (A > B) return asc ? 1 : -1;
					return 0;
				});
				$.each(rows, function(i, row) { $tbody.append(row); });
			});
		});
	})();
</script>
