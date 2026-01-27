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
	.amt { white-space: nowrap; font-variant-numeric: tabular-nums; font-weight: 600; }
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
	.table-scroll {
		width: 100%;
		overflow: auto;
		max-height: 65vh;
		-webkit-overflow-scrolling: touch;
		-ms-overflow-style: none; /* IE/Edge legacy */
		scrollbar-width: none; /* Firefox */
	}
	.table-scroll::-webkit-scrollbar { width: 0; height: 0; }
	th.sortable {
		cursor: pointer;
		user-select: none;
	}
	th.sortable::after {
		content: '';
		display: inline-block;
		margin-left: 6px;
		border-left: 4px solid transparent;
		border-right: 4px solid transparent;
		border-top: 6px solid rgba(0,0,0,0.25);
		vertical-align: middle;
	}
	th.sortable.sort-asc::after {
		border-top: 0;
		border-bottom: 6px solid rgba(0,0,0,0.55);
	}
	th.sortable.sort-desc::after {
		border-top: 6px solid rgba(0,0,0,0.55);
	}
	@media (max-width: 576px) {
		.page-title-bar { padding: 0 90px; }
		.page-title { font-size: 1.45rem; }
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
		$message = $this->input->get('message');
		$error = $this->input->get('error');
		$students_its_id = !empty($students_its_id) ? (int)$students_its_id : 0;
	?>

	<div class="page-title-bar">
		<div class="page-title-left">
			<a href="<?php echo base_url($madresa_base . '/classes/view/' . (int)($class['id'] ?? 0)); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
		</div>
		<h3 class="page-title">Payment History</h3>
	</div>

	<?php if (!empty($message)) { ?>
		<div class="alert alert-success"><?php echo htmlspecialchars((string)$message); ?></div>
	<?php } ?>
	<?php if (!empty($error)) { ?>
		<div class="alert alert-danger"><?php echo htmlspecialchars((string)$error); ?></div>
	<?php } ?>

	<div class="card shadow-sm mb-3">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-2"><b>Class:</b> <?php echo htmlspecialchars((string)($class['class_name'] ?? '')); ?></div>
				<div class="col-md-3 mb-2"><b>Hijri Year:</b> <?php echo !empty($class['hijri_year']) ? (int)$class['hijri_year'] : ''; ?></div>
				<div class="col-md-3 mb-2"><b>Status:</b>
					<?php
						$st = trim((string)($class['status'] ?? ''));
						$stLower = strtolower($st);
						$stClass = ($stLower === 'active') ? 'status-pill status-active' : 'status-pill status-inactive';
					?>
					<span class="<?php echo $stClass; ?>"><?php echo htmlspecialchars($st !== '' ? $st : '-'); ?></span>
				</div>
				<div class="col-12"><b>Fees:</b> ₹<?php echo htmlspecialchars($fmtMoney($class['fees'] ?? 0)); ?></div>
				<?php if ($students_its_id > 0) { ?>
					<div class="col-12 mt-2">
						<span class="badge bg-secondary">Filtered: ITS <?php echo $students_its_id; ?></span>
						<a class="ms-2" href="<?php echo base_url($madresa_base . '/classes/payment-history/' . (int)($class['id'] ?? 0)); ?>">Clear</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="card shadow-sm mb-3">
		<div class="card-header bg-white"><b>Summary</b></div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered mb-0">
					<thead>
						<tr>
							<th class="text-end">To Be Collected</th>
							<th class="text-end">Paid</th>
							<th class="text-end">Due</th>
							<th style="width:220px">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-end"><span class="amt amt-total">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_to_collect'] ?? 0)); ?></span></td>
							<td class="text-end"><span class="amt amt-paid">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_collected'] ?? 0)); ?></span></td>
							<td class="text-end"><span class="amt amt-due">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_due'] ?? 0)); ?></span></td>
							<td>
								<div class="action-buttons">
									<a class="btn btn-outline-primary btn-sm" href="<?php echo base_url($madresa_base . '/classes/payment-receipt/' . (int)($class['id'] ?? 0)); ?>">Payment Receipt</a>
									<a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url($madresa_base . '/classes/payment-history/' . (int)($class['id'] ?? 0) . ($students_its_id > 0 ? ('?students_its_id=' . $students_its_id) : '')); ?>">Payment History</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card shadow-sm">
		<div class="card-header bg-white"><b>Payments</b></div>
		<div class="card-body">
			<?php if (empty($payments)) { ?>
				<p class="text-muted mb-0">No payments found.</p>
			<?php } else { ?>
				<div class="table-scroll">
					<table id="madresa-payments-table" class="table table-striped mb-0">
						<thead>
							<tr>
								<th style="width:160px">Paid On</th>
								<th>Student</th>
								<th>Mode</th>
								<th>Reference</th>
								<th class="text-end" style="width:140px">Amount</th>
								<th style="width:160px" data-sortable="false">Receipt</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($payments as $p) { ?>
								<tr>
									<td><?php echo !empty($p['paid_on']) ? htmlspecialchars((string)$p['paid_on']) : (!empty($p['created_at']) ? htmlspecialchars((string)$p['created_at']) : ''); ?></td>
									<td>
										<?php
											$name = !empty($p['student_name']) ? (string)$p['student_name'] : '';
											$its = !empty($p['students_its_id']) ? (string)$p['students_its_id'] : '';
											$label = trim($name) !== '' ? $name : ($its !== '' ? ('ITS ' . $its) : '-');
											echo htmlspecialchars($label);
										?>
									</td>
									<td><?php echo htmlspecialchars((string)($p['payment_mode'] ?? '')); ?></td>
									<td><?php echo htmlspecialchars((string)($p['reference'] ?? '')); ?></td>
									<td class="text-end"><span class="amt">₹<?php echo htmlspecialchars($fmtMoney($p['amount'] ?? 0)); ?></span></td>
									<td>
										<a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url($madresa_base . '/classes/payment-receipt/' . (int)($class['id'] ?? 0) . '?payment_id=' . (int)($p['id'] ?? 0)); ?>">View Receipt</a>
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

<script>
	(function () {
		function parseNumber(text) {
			if (text === null || typeof text === 'undefined') return NaN;
			var s = String(text).replace(/\s+/g, ' ').trim();
			s = s.replace(/[^0-9.\-]/g, '');
			if (s === '' || s === '-' || s === '.' || s === '-.') return NaN;
			var n = parseFloat(s);
			return isNaN(n) ? NaN : n;
		}

		function parseDate(text) {
			if (!text) return NaN;
			var s = String(text).trim();
			if (!/^\d{4}-\d{2}-\d{2}/.test(s)) return NaN;
			var t = Date.parse(s.replace(' ', 'T'));
			return isNaN(t) ? NaN : t;
		}

		function getCellValue(row, colIndex) {
			var cell = row.children && row.children[colIndex] ? row.children[colIndex] : null;
			if (!cell) return '';
			return (cell.getAttribute('data-sort') || cell.textContent || '').trim();
		}

		function makeTableSortable(table) {
			if (!table) return;
			var thead = table.tHead;
			if (!thead || !thead.rows || !thead.rows[0]) return;
			var headerRow = thead.rows[0];
			var headers = Array.prototype.slice.call(headerRow.cells);

			headers.forEach(function (th, idx) {
				var sortable = th.getAttribute('data-sortable');
				if (sortable === 'false') return;
				th.classList.add('sortable');
				th.addEventListener('click', function () {
					var dir = th.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
					headers.forEach(function (h) {
						h.classList.remove('sort-asc', 'sort-desc');
						h.removeAttribute('data-sort-dir');
					});
					th.setAttribute('data-sort-dir', dir);
					th.classList.add(dir === 'asc' ? 'sort-asc' : 'sort-desc');

					var tbody = table.tBodies && table.tBodies[0] ? table.tBodies[0] : null;
					if (!tbody) return;
					var rows = Array.prototype.slice.call(tbody.rows);
					if (!rows.length) return;

					var decorated = rows.map(function (r, i) {
						var raw = getCellValue(r, idx);
						var dt = parseDate(raw);
						var num = parseNumber(raw);
						return { row: r, index: i, raw: raw, date: dt, num: num };
					});

					var allDate = decorated.every(function (d) { return !isNaN(d.date); });
					var allNum = decorated.every(function (d) { return !isNaN(d.num); });

					decorated.sort(function (a, b) {
						var cmp = 0;
						if (allDate) {
							cmp = a.date - b.date;
						} else if (allNum) {
							cmp = a.num - b.num;
						} else {
							cmp = a.raw.localeCompare(b.raw, undefined, { numeric: true, sensitivity: 'base' });
						}
						if (cmp === 0) cmp = a.index - b.index;
						return dir === 'asc' ? cmp : -cmp;
					});

					decorated.forEach(function (d) { tbody.appendChild(d.row); });
				});
			});
		}

		document.addEventListener('DOMContentLoaded', function () {
			makeTableSortable(document.getElementById('madresa-payments-table'));
		});
	})();
</script>
