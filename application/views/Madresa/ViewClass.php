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
									$stClass = (strtolower($st) === 'active') ? 'badge badge-info' : 'badge badge-secondary';
								?>
								<span class="<?php echo $stClass; ?>"><?php echo htmlspecialchars($st !== '' ? $st : '-'); ?></span>
							</div>
						</div>
						<div class="col-12 totals-row">
							<div class="row g-3">
									<div class="col-md-4 text-start">
									<div class="money-label">Total To Be Collected</div>
									<div class="money-value money-total" id="header-total-collect">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_to_collect'] ?? 0)); ?></div>
								</div>
									<div class="col-md-4 text-start text-md-center">
									<div class="money-label">Total Paid</div>
									<div class="money-value money-paid" id="header-total-paid">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_paid'] ?? 0)); ?></div>
								</div>
									<div class="col-md-4 text-start text-md-end">
									<div class="money-label">Total Due</div>
									<div class="money-value money-due" id="header-total-due">₹<?php echo htmlspecialchars($fmtMoney($financials['amount_due'] ?? 0)); ?></div>
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
						<div class="row g-2 mb-3 align-items-end">
							<div class="col-md-3">
								<label for="filterItsId" class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #6c757d;">Filter by ITS ID</label>
								<input type="text" id="filterItsId" class="form-control form-control-sm" placeholder="e.g. 309...">
							</div>
							<div class="col-md-4">
								<label for="filterName" class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #6c757d;">Filter by Name</label>
								<input type="text" id="filterName" class="form-control form-control-sm" placeholder="Enter student name...">
							</div>
							<div class="col-md-5 text-md-end text-start mt-2 mt-md-0">
								<button type="button" class="btn btn-sm btn-outline-secondary" id="clearFilters">Clear Filters</button>
							</div>
						</div>
						
						<div class="table-scroll">
							<table id="madresa-class-students-table" class="table table-striped mb-0">
								<thead>
									<tr>
										<th style="width:140px">ITS ID</th>
										<th>Name</th>
										<th class="text-end" style="width:160px">Fees</th>
										<th class="text-end" style="width:140px">Paid</th>
										<th class="text-end" style="width:140px">Due</th>
										<th style="width:260px" data-sortable="false">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($students as $s) { ?>
										<?php $sid = (int)($s['ITS_ID'] ?? 0); ?>
										<tr data-its-id="<?php echo $sid; ?>">
											<td><?php echo $sid; ?></td>
											<td><?php echo htmlspecialchars((string)($s['Full_Name'] ?? '')); ?></td>
											<td class="text-end"><span class="amt amt-total">₹<?php echo htmlspecialchars($fmtMoney($s['amount_to_collect'] ?? 0)); ?></span></td>
											<td class="text-end"><span class="amt amt-paid js-row-paid">₹<?php echo htmlspecialchars($fmtMoney($s['amount_paid'] ?? 0)); ?></span></td>
											<td class="text-end"><span class="amt amt-due js-row-due">₹<?php echo htmlspecialchars($fmtMoney($s['amount_due'] ?? 0)); ?></span></td>
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
													<button
														type="button"
														class="btn btn-outline-secondary btn-sm btn-payment-history"
														data-toggle="modal"
														data-target="#historyModal"
														data-bs-toggle="modal"
														data-bs-target="#historyModal"
														data-class-id="<?php echo (int)($class['id'] ?? 0); ?>"
														data-its-id="<?php echo $sid; ?>"
														data-student-name="<?php echo htmlspecialchars((string)($s['Full_Name'] ?? ''), ENT_QUOTES); ?>"
													>Payment History</button>
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
							<option value="Cheque">Cheque</option>
							<option value="NEFT">NEFT</option>
						</select>
					</div>

					<div class="form-group">
						<label for="rpmDate" class="form-label">Payment Date</label>
						<input type="date" name="paid_on" id="rpmDate" class="form-control" required>
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

<!-- Payment History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content" style="border-radius:12px; border:1px solid #e6eaf2;">
			<div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e6eaf2;">
				<h5 class="modal-title">Payment History</h5>
				<button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="border:0; background:transparent; font-size:1.5rem; line-height:1;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<div class="info-label">Student Name</div>
					<div class="info-value" id="history_student_name">-</div>
				</div>
				<div class="table-scroll" style="max-height: 400px;">
					<table class="table table-sm table-striped mb-0">
						<thead>
							<tr>
								<th>Date</th>
								<th>Method</th>
								<th class="text-end">Amount</th>
								<th>Description</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="history_table_body">
							<!-- Loaded via AJAX -->
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer" style="border-top:1px solid #e6eaf2;">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
			</div>
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
			makeTableSortable(document.getElementById('madresa-class-students-table'));
			
			// Filters Logic
			var filterItsId = document.getElementById('filterItsId');
			var filterName = document.getElementById('filterName');
			var clearBtn = document.getElementById('clearFilters');
			var table = document.getElementById('madresa-class-students-table');
			var tbody = table ? table.querySelector('tbody') : null;
			var rows = tbody ? tbody.querySelectorAll('tr') : [];

			function applyFilters() {
				var itsVal = (filterItsId.value || '').trim().toLowerCase();
				var nameVal = (filterName.value || '').trim().toLowerCase();

				rows.forEach(function (row) {
					var itsCell = row.cells[0]; // First column is ITS ID
					var nameCell = row.cells[1]; // Second column is Name

					if (!itsCell || !nameCell) return;

					var itsText = itsCell.textContent.toLowerCase();
					var nameText = nameCell.textContent.toLowerCase();

					var itsMatch = itsVal === '' || itsText.indexOf(itsVal) > -1;
					var nameMatch = nameVal === '' || nameText.indexOf(nameVal) > -1;

					if (itsMatch && nameMatch) {
						row.style.display = '';
					} else {
						row.style.display = 'none';
					}
				});
			}

			if (filterItsId) filterItsId.addEventListener('input', applyFilters);
			if (filterName) filterName.addEventListener('input', applyFilters);

			if (clearBtn) {
				clearBtn.addEventListener('click', function () {
					if (filterItsId) filterItsId.value = '';
					if (filterName) filterName.value = '';
					applyFilters();
				});
			}
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
				$('#receivePaymentModal').modal('show');
			}
		}

		// AJAX Submission for Receive Payment
		$(document).on('submit', '#receivePaymentForm', function (e) {
			e.preventDefault();
			var form = $(this);
			var submitBtn = form.find('button[type="submit"]');
			
			// Get IDs from the button that opened the modal (stored in current data or just parse from action)
			// Efficient way: store in modal state
			var classId = $('#receivePaymentModal').data('class-id');
			var itsId = $('#receivePaymentModal').data('its-id');

			submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			$.ajax({
				url: '<?php echo base_url($madresa_base . "/ajax-receive-payment"); ?>',
				type: 'POST',
				data: {
					class_id: classId,
					students_its_id: itsId,
					amount: $('#rpmAmount').val(),
					payment_mode: $('#rpmMode').val(),
					paid_on: $('#rpmDate').val(),
					notes: $('#rpmNotes').val()
				},
				dataType: 'json',
				success: function (res) {
					submitBtn.prop('disabled', false).text('Submit');
					if (res.success) {
						alert(res.message || 'Payment received successfully');
						$('#receivePaymentModal').modal('hide');

						// Update Row
						if (res.student) {
							var row = $('tr[data-its-id="' + itsId + '"]');
							if (row.length) {
								row.find('.js-row-paid').text('₹' + formatINR(res.student.amount_paid));
								row.find('.js-row-due').text('₹' + formatINR(res.student.amount_due));
								
								// Also update the data attributes on the "Receive Payment" button in that row
								var rpBtn = row.find('.btn-receive-payment');
								if (rpBtn.length) {
									rpBtn.data('paid', parseFloat(res.student.amount_paid));
									rpBtn.data('due', parseFloat(res.student.amount_due));
								}
							}
						}

						// Update Header Totals
						if (res.class_financials) {
							$('#header-total-collect').text('₹' + formatINR(res.class_financials.amount_to_collect));
							$('#header-total-paid').text('₹' + formatINR(res.class_financials.amount_paid));
							$('#header-total-due').text('₹' + formatINR(res.class_financials.amount_due));
						}
					} else {
						alert(res.error || 'Failed to process payment');
					}
				},
				error: function () {
					submitBtn.prop('disabled', false).text('Submit');
					alert('Connection error. Please try again.');
				}
			});
		});

		$(document).on('click', '.btn-receive-payment', function (e) {
			e.preventDefault();
			var btn = $(this);
			var classId = btn.data('class-id');
			var itsId = btn.data('its-id');
			var studentName = btn.data('student-name');
			var due = btn.data('due');

			// Store IDs in modal for the AJAX form
			$('#receivePaymentModal').data('class-id', classId).data('its-id', itsId);

			var form = $('#receivePaymentForm');
			var amount = $('#rpmAmount');
			var notes = $('#rpmNotes');
			var studentLine = $('#rpmStudentLine');

			if (!form.length || !amount.length) return;

			// We don't strictly need this now but keeping for safety if someone refreshes and it redirects
			form.attr('action', '<?php echo base_url($madresa_base . "/classes/receive-payment/"); ?>' + classId + '/' + itsId);

			var dueNum = parseFloat(due);
			if (isNaN(dueNum) || dueNum <= 0) dueNum = 0;
			amount.val((dueNum > 0) ? dueNum.toFixed(2).replace(/\.00$/, '') : '');
			amount.attr('max', (dueNum > 0) ? dueNum : '');
			if (notes.length) notes.val('');
			
			var today = new Date();
			var yyyy = today.getFullYear();
			var mm = String(today.getMonth() + 1).padStart(2, '0');
			var dd = String(today.getDate()).padStart(2, '0');
			$('#rpmDate').val(yyyy + '-' + mm + '-' + dd);

			if (studentLine.length) {
				studentLine.text(studentName || ('ITS ' + itsId));
			}

			$('#receivePaymentModal').modal('show');
		});

		$(document).on('click', '.btn-payment-history', function (e) {
			e.preventDefault();
			var btn = $(this);
			var classId = btn.data('class-id');
			var itsId = btn.data('its-id');
			var name = btn.data('student-name');

			$('#history_student_name').text(name || '-');
			var tbody = $('#history_table_body');
			tbody.html('<tr><td colspan="5" class="text-center font-italic text-muted py-4"><i class="fa fa-spinner fa-spin mr-2"></i> Loading history...</td></tr>');
			
			$('#historyModal').modal('show');

			$.ajax({
				url: '<?php echo base_url($madresa_base . "/get-payment-history"); ?>',
				type: 'POST',
				data: { class_id: classId, students_its_id: itsId },
				dataType: 'json',
				success: function(response) {
					var html = '';
					if (response && response.length > 0) {
						response.forEach(function(item) {
							var dt = item.paid_on ? item.paid_on : item.created_at;
							var dateStr = '-';
							if(dt) {
								var parts = dt.split(' ')[0].split('-');
								if(parts.length === 3) dateStr = parts[2] + '-' + parts[1] + '-' + parts[0];
							}
							
							var receiptUrl = '<?php echo base_url($madresa_base . "/classes/payment-receipt/"); ?>' + item.m_class_id + '?payment_id=' + item.id;

							html += '<tr>' +
								'<td class="text-nowrap">' + dateStr + '</td>' +
								'<td>' + (item.payment_mode || '-') + '</td>' +
								'<td class="text-end fw-bold">₹' + formatINR(item.amount) + '</td>' +
								'<td class="small text-muted">' + (item.notes || '-') + '</td>' +
								'<td class="text-center"><a href="' + receiptUrl + '" class="btn btn-sm btn-outline-primary" target="_blank" title="View Receipt"><i class="fas fa-file-invoice"></i> Receipt</a></td>' +
								'</tr>';
						});
					} else {
						html = '<tr><td colspan="5" class="text-center text-muted py-4">No payment history found.</td></tr>';
					}
					tbody.html(html);
				},
				error: function() {
					tbody.html('<tr><td colspan="5" class="text-center text-danger py-4">Error loading history.</td></tr>');
				}
			});
		});
	})();
</script>
