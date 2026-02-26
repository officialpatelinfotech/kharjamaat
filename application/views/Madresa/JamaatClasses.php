<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
	body { padding-top: 70px; }
	.page-container { padding-left: 24px; padding-right: 24px; }
	.page-title-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 12px;
	}
	.page-title {
		flex: 1 1 auto;
		text-align: center;
		font-size: 2rem;
		font-weight: 700;
		margin: 0;
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
	.filters-bar {
		margin-top: 18px;
		margin-bottom: 14px;
	}
	.filters-bar form {
		margin: 0;
	}
	.form-control-sm, .btn-sm { height: 34px; box-sizing: border-box; }
	.table { font-size: 1rem; }
	.madresa-table { width: 100%; min-width: 1450px; }
	@media (min-width: 992px) {
		.madresa-table { table-layout: fixed; }
		.col-sr { width: 70px; }
		.col-class { width: 240px; }
		.col-year { width: 110px; }
		.col-students { width: 100px; }
		.col-fees { width: 120px; }
		.col-total { width: 160px; }
		.col-collected { width: 140px; }
		.col-due { width: 140px; }
		.col-status { width: 110px; }
		.col-actions { width: 170px; }
	}
	.td-class { white-space: normal; word-break: break-word; }
	.table th { background: #f8f9fa; font-weight: 600; white-space: nowrap; padding: 14px 16px; }
	.table td { vertical-align: middle; padding: 14px 16px; }
	.td-center, .th-center { text-align: center; }
	.td-right, .th-right { text-align: right; }
	.td-actions { text-align: center; }
	.amt { white-space: nowrap; font-weight: 600; }
	.amt, .text-right { font-variant-numeric: tabular-nums; }
	.amt-total { color: #6c757d; }
	.amt-collected { color: #198754; }
	.amt-due { color: #dc3545; }
	.btn-view {
		width: auto;
		height: 34px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 0 8px;
		white-space: nowrap;
		font-size: 0.875rem;
	}
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
	.empty-state { text-align: center; padding: 40px 10px; }
	.card { width: 100%; }
	@media (max-width: 768px) {
		.page-container { padding-left: 12px; padding-right: 12px; }
		.page-title { font-size: 1.6rem; }
		.filters-bar .d-flex { flex-direction: column; align-items: stretch; width: 100%; }
		.filters-bar select { width: 100% !important; min-width: 0 !important; }
		.filters-bar .mr-2 { margin-right: 0 !important; margin-bottom: 10px; }
	}
</style>

<div class="container-fluid mt-3 page-container">
	<?php
		$madresa_base = 'madresa';
		$selectedYear = !empty($selected_hijri_year) ? (int)$selected_hijri_year : (!empty($current_hijri_year) ? (int)$current_hijri_year : 0);
		$selectedClassQuery = !empty($selected_class_query) ? (string)$selected_class_query : '';
		$hijriYears = !empty($hijri_years) && is_array($hijri_years) ? $hijri_years : [];

		$fmt = function ($n) {
			$n = ($n === '' || $n === null) ? 0 : (float)$n;
			$formatted = function_exists('format_inr') ? format_inr($n, 2) : number_format($n, 2);
			// Remove trailing .00 only (keep other decimals like .50)
			return preg_replace('/\\.00$/', '', (string)$formatted);
		};
	?>

	<div class="row">
		<div class="col-12">
			<div class="page-title-bar">
				<a href="<?php echo base_url('anjuman'); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back">
					<span class="back-arrow">&larr;</span>
				</a>
				<h2 class="page-title">Madresa Classes</h2>
				<div style="width:40px"></div>
			</div>

			<div class="filters-bar">
				<form method="get" action="<?php echo base_url($madresa_base); ?>">
					<div class="d-flex align-items-center justify-content-center">
						<select name="year" class="form-control form-control-sm mr-2" style="width:140px" onchange="this.form.submit()">
							<?php foreach ($hijriYears as $y) { ?>
								<option value="<?php echo (int)$y; ?>" <?php echo ($selectedYear === (int)$y) ? 'selected' : ''; ?>>
									<?php echo (int)$y; ?>
								</option>
							<?php } ?>
						</select>

						<input
							type="text"
							name="class"
							value="<?php echo htmlspecialchars($selectedClassQuery); ?>"
							placeholder="Search class"
							class="form-control form-control-sm mr-2"
							style="width:200px"
							onkeydown="if(event.key==='Enter'){event.preventDefault();this.form.submit();}"
							onchange="this.form.submit()"
						/>

						<input
							type="text"
							name="student_name"
							value="<?php echo htmlspecialchars($selectedStudentQuery ?? ''); ?>"
							placeholder="Search student name"
							class="form-control form-control-sm"
							style="width:200px"
							onkeydown="if(event.key==='Enter'){event.preventDefault();this.form.submit();}"
							onchange="this.form.submit()"
						/>
					</div>
				</form>
			</div>



			<?php $message = $this->input->get('message'); ?>
			<?php if (!empty($message)) { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars((string)$message); ?></div>
			<?php } ?>

			<?php $error = $this->input->get('error'); ?>
			<?php if (!empty($error)) { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars((string)$error); ?></div>
			<?php } ?>

			<div class="card shadow-sm">
				<div class="card-body">
					<?php if (empty($classes)) { ?>
						<div class="empty-state">
							<p class="text-muted mb-0">No classes found for the selected filters.</p>
						</div>
					<?php } else { ?>
						<div class="table-scroll">
							<table id="madresa-classes-table" class="table table-striped table-hover mb-0 madresa-table">
								<colgroup>
									<col style="width:70px">
									<col style="width:240px">
									<col style="width:110px">
									<col style="width:100px">
									<col style="width:120px">
									<col style="width:160px">
									<col style="width:140px">
									<col style="width:140px">
									<col style="width:110px">
									<col style="width:170px">
								</colgroup>
								<thead>
									<tr>
										<th class="col-sr th-center">Sr No</th>
										<th class="col-class">Class</th>
										<th class="col-year th-center">Hijri Year</th>
										<th class="col-students th-center">Students</th>
										<th class="col-fees th-right">Fees</th>
										<th class="col-total th-right">Total To Collect</th>
										<th class="col-collected th-right">Collected</th>
										<th class="col-due th-right">Due</th>
										<th class="col-status th-center">Status</th>
										<th class="col-actions th-center" data-sortable="false">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php $sr = 1; ?>
									<?php foreach ($classes as $row) { ?>
										<tr>
											<td class="col-sr td-center"><?php echo (int)$sr++; ?></td>
											<td class="col-class td-class"><?php echo htmlspecialchars((string)($row['class_name'] ?? '')); ?></td>
											<td class="col-year td-center"><?php echo !empty($row['hijri_year']) ? (int)$row['hijri_year'] : ''; ?></td>
											<td class="col-students td-center"><?php echo (int)($row['student_count'] ?? 0); ?></td>
											<td class="col-fees td-right"><span class="amt">₹<?php echo htmlspecialchars($fmt($row['fees'] ?? 0)); ?></span></td>
											<td class="td-right"><span class="amt amt-total">₹<?php echo htmlspecialchars($fmt($row['amount_to_collect'] ?? 0)); ?></span></td>
											<td class="td-right"><span class="amt amt-collected">₹<?php echo htmlspecialchars($fmt($row['amount_collected'] ?? 0)); ?></span></td>
											<td class="td-right"><span class="amt amt-due">₹<?php echo htmlspecialchars($fmt($row['amount_due'] ?? 0)); ?></span></td>
											<td class="col-status td-center">
												<?php
													$st = trim((string)($row['status'] ?? ''));
													$stClass = (strtolower($st) === 'active') ? 'badge badge-info' : 'badge badge-secondary';
												?>
												<span class="<?php echo $stClass; ?>"><?php echo htmlspecialchars($st !== '' ? $st : '-'); ?></span>
											</td>
											<td class="col-actions td-actions">
												<a href="<?php echo base_url($madresa_base . '/classes/view/' . (int)$row['id']); ?>" class="btn btn-outline-secondary btn-sm btn-view" title="View Class Details" aria-label="View Class Details">
													View Class Details
												</a>
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

<script>
	(function () {
		function parseNumber(text) {
			if (text === null || typeof text === 'undefined') return NaN;
			var s = String(text).replace(/\s+/g, ' ').trim();
			// Remove currency symbols and separators.
			s = s.replace(/[^0-9.\-]/g, '');
			if (s === '' || s === '-' || s === '.' || s === '-.') return NaN;
			var n = parseFloat(s);
			return isNaN(n) ? NaN : n;
		}

		function parseDate(text) {
			if (!text) return NaN;
			var s = String(text).trim();
			// Accept YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
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
					// Toggle direction
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
						return {
							row: r,
							index: i,
							raw: raw,
							date: dt,
							num: num
						};
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
			makeTableSortable(document.getElementById('madresa-classes-table'));
		});
	})();
</script>
