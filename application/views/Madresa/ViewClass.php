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
	.modal-title { color: #1b3a57; font-weight: 700; }
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
	.pay-hint { color: #6c757d; font-size: 0.9rem; }
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
</style>

<div class="container-fluid mt-4 page-container">
	<?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'madresa'; ?>
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
									<div class="col-md-4 text-start">
									<div class="money-label">Total To Be Collected</div>
									<div class="money-value money-total">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_to_collect'] ?? 0)); ?></div>
								</div>
									<div class="col-md-4 text-start text-md-center">
									<div class="money-label">Total Paid</div>
									<div class="money-value money-paid">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_collected'] ?? 0)); ?></div>
								</div>
									<div class="col-md-4 text-start text-md-end">
									<div class="money-label">Total Due</div>
									<div class="money-value money-due">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_due'] ?? 0)); ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card shadow-sm">
				<div class="card-header bg-white"><b>Students in this class</b></div>
				<div class="card-body">
					<?php if (empty($students)) { ?>
						<p class="text-muted mb-0">No students assigned.</p>
					<?php } else { ?>
						<div class="table-scroll">
							<table id="madresa-class-students-table" class="table table-striped mb-0">
								<thead>
									<tr>
										<th style="width:140px">ITS ID</th>
										<th>Name</th>
										<th class="text-end" style="width:160px">To Be Collected</th>
										<th class="text-end" style="width:140px">Paid</th>
										<th class="text-end" style="width:140px">Due</th>
										<th style="width:260px" data-sortable="false">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $s) { ?>
										<tr>
											<?php $sid = (int)($s['ITS_ID'] ?? 0); ?>
											<td><?php echo $sid; ?></td>
											<td><?php echo htmlspecialchars((string)($s['Full_Name'] ?? '')); ?></td>
											<td class="text-end"><span class="amt amt-total">₹<?php echo htmlspecialchars($fmtMoney($s['amount_to_collect'] ?? 0)); ?></span></td>
											<td class="text-end"><span class="amt amt-paid">₹<?php echo htmlspecialchars($fmtMoney($s['amount_paid'] ?? 0)); ?></span></td>
											<td class="text-end"><span class="amt amt-due">₹<?php echo htmlspecialchars($fmtMoney($s['amount_due'] ?? 0)); ?></span></td>
											<td>
												<div class="action-buttons">
													<?php if (!empty($can_receive_payment)) { ?>
														<button
															type="button"
															class="btn btn-success btn-sm btn-receive-payment"
															data-toggle="modal"
															data-target="#receivePaymentModal"
															data-bs-toggle="modal"
															data-bs-target="#receivePaymentModal"
															data-class-id="<?php echo (int)($class['id'] ?? 0); ?>"
															data-its-id="<?php echo $sid; ?>"
															data-student-name="<?php echo htmlspecialchars((string)($s['Full_Name'] ?? ''), ENT_QUOTES); ?>"
															data-to-collect="<?php echo (float)($s['amount_to_collect'] ?? 0); ?>"
															data-paid="<?php echo (float)($s['amount_paid'] ?? 0); ?>"
															data-due="<?php echo (float)($s['amount_due'] ?? 0); ?>"
														>Receive Payment</button>
													<?php } ?>
													<a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url($madresa_base . '/classes/payment-history/' . (int)($class['id'] ?? 0) . '?students_its_id=' . $sid); ?>">Payment History</a>
												</div>
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
	</div>
</div>

							<?php if (!empty($can_receive_payment)) { ?>
<!-- Receive Payment Modal (Jamaat only) -->
<div class="modal fade" id="receivePaymentModal" tabindex="-1" role="dialog" aria-labelledby="receivePaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="border-radius:12px; border:1px solid #e6eaf2;">
			<div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e6eaf2;">
				<h5 class="modal-title" id="receivePaymentModalLabel">Receive Payment</h5>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="border:0; background:transparent; font-size:1.5rem; line-height:1;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="receivePaymentForm" action="#">
				<div class="modal-body">
					<div class="form-group">
						<label class="form-label">Name: <span id="rpmStudentLine" class="ml-2" style="font-weight:600;"></span></label>
					</div>

					<div class="form-group">
						<label for="rpmAmount" class="form-label">Payment Amount (₹)</label>
						<input type="number" step="0.01" min="0.01" name="amount" id="rpmAmount" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="rpmMode" class="form-label">Payment Method</label>
						<select name="payment_mode" id="rpmMode" class="form-control">
							<option value="Cash">Cash</option>
							<option value="Check">Check</option>
						</select>
					</div>

					<div class="form-group mb-0">
						<label for="rpmNotes" class="form-label">Description</label>
						<textarea name="notes" id="rpmNotes" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<div class="modal-footer" style="border-top:1px solid #e6eaf2;">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
							<?php } ?>

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
			makeTableSortable(document.getElementById('madresa-class-students-table'));
		});
	})();
</script>

<script>
	(function () {
		function formatINR(n) {
			n = (n === '' || n === null || typeof n === 'undefined') ? 0 : parseFloat(n);
			if (isNaN(n)) n = 0;
			try {
				// Use Intl if available; fallback to simple formatting.
				return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(n);
			} catch (e) {
				return String(n.toFixed(2)).replace(/\.00$/, '');
			}
		}

		function showModal() {
			var el = document.getElementById('receivePaymentModal');
			if (!el) return;
			if (window.bootstrap && window.bootstrap.Modal) {
				var m = window.bootstrap.Modal.getOrCreateInstance(el);
				m.show();
				return;
			}
			if (window.jQuery && window.jQuery.fn && window.jQuery.fn.modal) {
				window.jQuery(el).modal('show');
				return;
			}
		}

		document.addEventListener('click', function (e) {
			var btn = e.target.closest ? e.target.closest('.btn-receive-payment') : null;
			if (!btn) return;
			e.preventDefault();

			var classId = btn.getAttribute('data-class-id') || '0';
			var itsId = btn.getAttribute('data-its-id') || '0';
			var studentName = btn.getAttribute('data-student-name') || '';
			var due = btn.getAttribute('data-due') || '0';

			var form = document.getElementById('receivePaymentForm');
			var amount = document.getElementById('rpmAmount');
			var notes = document.getElementById('rpmNotes');
			var studentLine = document.getElementById('rpmStudentLine');
			// (Totals line removed)
			if (!form || !amount) return;

			form.action = '<?php echo base_url($madresa_base . '/classes/receive-payment/'); ?>' + String(parseInt(classId, 10)) + '/' + String(parseInt(itsId, 10));

			var dueNum = parseFloat(due);
			if (isNaN(dueNum) || dueNum <= 0) dueNum = 0;
			amount.value = (dueNum > 0) ? dueNum.toFixed(2).replace(/\.00$/, '') : '';
			amount.max = (dueNum > 0) ? String(dueNum) : '';
			if (notes) notes.value = '';

			if (studentLine) {
				studentLine.textContent = (studentName ? studentName : ('ITS ' + itsId));
			}
			// Totals line removed per UI requirement.

			showModal();
		});
	})();
</script>
