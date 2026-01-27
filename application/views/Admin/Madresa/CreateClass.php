<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Create Class</title>

	<!-- Bootstrap & FontAwesome (remove if already globally loaded) -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>
		/* FIX: space for fixed admin header */
		body {
			padding-top: 70px; /* adjust to your navbar height */
		}

		/* Card styling */
		.card {
			border-radius: 8px;
		}

		.card-header {
			border-bottom: 1px solid #eee;
		}

		.card-body {
			padding: 1.5rem;
		}

		/* Header layout */
		.page-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 10px;
		}

		.page-header-left {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		/* Back button */
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

		/* Make the left-arrow glyph look bold across fonts */
		.btn-back .back-arrow {
			font-weight: 900;
			-webkit-text-stroke: 0.35px currentColor;
			text-shadow: 0.35px 0 0 currentColor, -0.35px 0 0 currentColor;
			display: inline-block;
		}

		/* Uniform controls */
		.btn-sm {
			height: 34px;
		}

		/* Student select improvements */
		#studentSelect {
			font-size: 0.9rem;
		}

		/* Center card on large screens */
		@media (min-width: 1200px) {
			.card {
				max-width: 900px;
				margin: auto;
			}
		}

		@media (max-width: 768px) {
			.page-header {
				flex-direction: column;
				align-items: flex-start;
			}
		}
	</style>
</head>

<body>

<?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa'; ?>

<div class="container mt-3">
	<div class="row">
		<div class="col-12">

			<!-- PAGE HEADER (outside card) -->
			<div class="page-header mb-3">
				<div class="page-header-left">
					<a href="<?php echo base_url($madresa_base); ?>"
						 class="btn btn-outline-secondary btn-sm btn-back"
						 title="Back"
						 aria-label="Back">
						<span class="back-arrow">&larr;</span>
					</a>
					<h3 class="mb-0">Create Class</h3>
				</div>

				<a href="<?php echo base_url($madresa_base . '/classes'); ?>"
					 class="btn btn-outline-secondary btn-sm">
					Go to Manage
				</a>
			</div>

			<!-- ALERTS -->
			<?php if (!empty($error)) { ?>
				<div class="alert alert-danger">
					<?php echo htmlspecialchars($error); ?>
				</div>
			<?php } ?>

			<?php if (!empty($message)) { ?>
				<div class="alert alert-success">
					<?php echo htmlspecialchars($message); ?>
				</div>
			<?php } ?>

			<!-- CARD -->
			<div class="card shadow-sm">
				<!-- CARD BODY -->
				<div class="card-body">
					<form method="post" action="<?php echo base_url($madresa_base . '/classes/create'); ?>">

						<!-- CLASS -->
						<div class="form-group">
							<label class="font-weight-semibold">Class</label>
							<input type="text"
										 name="class_name"
										 class="form-control"
										 required
										 maxlength="255">
						</div>

						<!-- HIJRI YEAR -->
						<div class="form-group">
							<label class="font-weight-semibold">Hijri Year</label>
							<select name="hijri_year" class="form-control" required>
								<option value="">Select Hijri year</option>
								<?php if (!empty($hijri_years)) { ?>
									<?php foreach ($hijri_years as $y) { ?>
										<option value="<?php echo (int)$y; ?>"
											<?php echo (!empty($default_hijri_year) && (int)$default_hijri_year === (int)$y) ? 'selected' : ''; ?>>
											<?php echo (int)$y; ?>
										</option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>

						<?php $selected_status = 'Active'; ?>
						<?php $this->load->view('Admin/Madresa/_status_select'); ?>

						<!-- FEES -->
						<div class="form-group">
							<label class="font-weight-semibold">Fees</label>
							<input type="number"
										 name="fees"
										 class="form-control"
										 min="0"
										 step="0.01"
										 placeholder="Enter fees">
						</div>

						<!-- STUDENT ASSIGN -->
						<div class="form-group">
							<label class="font-weight-semibold">Assign Students</label>

							<div class="position-relative">
								<input type="text"
											 class="form-control"
											 id="studentSearch"
											 placeholder="Type student name or ITS..." autocomplete="off">
								<div id="studentSuggestions" class="list-group" style="display:none;position:absolute;left:0;right:0;z-index:1000;max-height:260px;overflow:auto;"></div>
							</div>
							<div id="studentIdsContainer"></div>

							<div id="studentDetails" class="mt-3" style="display:none;"></div>
						</div>

						<!-- SUBMIT -->
						<button type="submit" class="btn btn-primary px-4">
							Create Class
						</button>

					</form>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- STUDENT AUTOCOMPLETE + DETAILS SCRIPT -->
<script>
(function () {
	var search = document.getElementById('studentSearch');
	var suggestions = document.getElementById('studentSuggestions');
	var idsBox = document.getElementById('studentIdsContainer');
	var details = document.getElementById('studentDetails');
	var detailsUrl = "<?php echo base_url($madresa_base . '/student-details'); ?>";

	var studentsData = <?php echo json_encode(!empty($students) ? $students : []); ?>;
	if (!search || !suggestions || !idsBox) return;

	// Index by id for fast lookup
	var byId = {};
	for (var i = 0; i < studentsData.length; i++) {
		var r = studentsData[i] || {};
		if (r.ITS_ID != null) byId[String(r.ITS_ID)] = r;
	}

	var selectedIds = {}; // map of id -> true
	var sortKey = 'Full_Name';
	var sortDir = 'asc';

	function escapeHtml(str) {
		return String(str == null ? '' : str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/\"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function getSelectedIds() {
		var out = [];
		var inputs = idsBox.querySelectorAll('input[name="student_ids[]"]');
		for (var i = 0; i < inputs.length; i++) out.push(inputs[i].value);
		return out;
	}

	function syncSelectedMapFromInputs() {
		selectedIds = {};
		var ids = getSelectedIds();
		for (var i = 0; i < ids.length; i++) selectedIds[String(ids[i])] = true;
	}

	function addStudent(id) {
		id = String(id);
		if (!id || selectedIds[id]) return;
		selectedIds[id] = true;
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'student_ids[]';
		input.value = id;
		idsBox.appendChild(input);
		fetchDetails();
	}

	function removeStudent(id) {
		id = String(id);
		selectedIds[id] = false;
		var inputs = idsBox.querySelectorAll('input[name="student_ids[]"]');
		for (var i = 0; i < inputs.length; i++) {
			if (String(inputs[i].value) === id) {
				inputs[i].parentNode.removeChild(inputs[i]);
				break;
			}
		}
		fetchDetails();
	}

	function hideSuggestions() {
		suggestions.style.display = 'none';
		suggestions.innerHTML = '';
	}

	function showSuggestions(list) {
		if (!list || !list.length) {
			hideSuggestions();
			return;
		}
		var html = '';
		for (var i = 0; i < list.length; i++) {
			var s = list[i] || {};
			var id = String(s.ITS_ID);
			var label = (s.Full_Name || '') + ' (' + id + ')';
			html += '<button type="button" class="list-group-item list-group-item-action" data-id="' + escapeHtml(id) + '">' + escapeHtml(label) + '</button>';
		}
		suggestions.innerHTML = html;
		suggestions.style.display = 'block';
	}

	function searchStudents(q) {
		q = String(q || '').toLowerCase().trim();
		if (!q) {
			hideSuggestions();
			return;
		}
		var out = [];
		for (var i = 0; i < studentsData.length; i++) {
			var s = studentsData[i] || {};
			var id = String(s.ITS_ID || '');
			var name = String(s.Full_Name || '').toLowerCase();
			if (selectedIds[id]) continue;
			if (id.indexOf(q) !== -1 || name.indexOf(q) !== -1) {
				out.push(s);
				if (out.length >= 15) break;
			}
		}
		showSuggestions(out);
	}

	function renderDetails(students, totalSelected) {
		if (!details) return;
		if (!students || !students.length) {
			details.style.display = 'none';
			details.innerHTML = '';
			return;
		}

		// Sort copy
		var list = students.slice(0);
		list.sort(function (a, b) {
			var av = (a && a[sortKey] != null) ? a[sortKey] : '';
			var bv = (b && b[sortKey] != null) ? b[sortKey] : '';

			// numeric for Age
			if (sortKey === 'Age') {
				av = parseInt(av, 10);
				bv = parseInt(bv, 10);
				if (isNaN(av)) av = -1;
				if (isNaN(bv)) bv = -1;
			} else {
				av = String(av).toLowerCase();
				bv = String(bv).toLowerCase();
			}

			if (av < bv) return sortDir === 'asc' ? -1 : 1;
			if (av > bv) return sortDir === 'asc' ? 1 : -1;
			return 0;
		});

		var rows = '';
		for (var i = 0; i < list.length; i++) {
			var s = list[i] || {};
			var its = (s.ITS_ID != null) ? String(s.ITS_ID) : '';
			rows += '<tr>'
				+ '<td>' + escapeHtml(s.Full_Name || '') + '</td>'
				+ '<td style="width:80px;">' + escapeHtml(s.Age != null ? s.Age : '-') + '</td>'
				+ '<td style="width:120px;">' + escapeHtml(s.Sector || '-') + '</td>'
				+ '<td style="width:180px;">' + escapeHtml(s.Sub_Sector || '-') + '</td>'
				+ '<td style="width:110px;"><button type="button" class="btn btn-sm btn-outline-danger js-remove-student" data-id="' + escapeHtml(its) + '">Remove</button></td>'
				+ '</tr>';
		}

		var note = '';
		if (typeof totalSelected === 'number' && totalSelected > students.length) {
			note = '<div class="text-muted small mb-2">Showing first ' + students.length + ' of ' + totalSelected + ' selected students.</div>';
		}

		details.innerHTML =
			'<div class="card">'
			+ '<div class="card-header bg-white py-2"><strong>Selected Student Details</strong></div>'
			+ '<div class="card-body py-2">'
			+ note
			+ '<div class="table-responsive" style="max-height:240px;overflow:auto;">'
			+ '<table class="table table-sm mb-0">'
			+ '<thead><tr>'
			+ '<th><a href="#" class="js-sort" data-key="Full_Name">Name</a></th>'
			+ '<th style="width:80px;"><a href="#" class="js-sort" data-key="Age">Age</a></th>'
			+ '<th style="width:120px;"><a href="#" class="js-sort" data-key="Sector">Sector</a></th>'
			+ '<th style="width:180px;"><a href="#" class="js-sort" data-key="Sub_Sector">Sub Sector</a></th>'
			+ '<th style="width:110px;">Action</th>'
			+ '</tr></thead>'
			+ '<tbody>' + rows + '</tbody>'
			+ '</table>'
			+ '</div>'
			+ '</div>'
			+ '</div>';
		details.style.display = 'block';
	}

	function fetchDetails() {
		var ids = getSelectedIds();
		if (!details) return;
		if (!ids.length) {
			details.style.display = 'none';
			details.innerHTML = '';
			return;
		}

		// Limit displayed details to keep UI fast
		var limit = 30;
		var idsForFetch = ids.slice(0, limit);
		var url = detailsUrl + '?ids=' + encodeURIComponent(idsForFetch.join(','));

		// Fetch via XHR for broad compatibility
		var xhr = new XMLHttpRequest();
		xhr.open('GET', url, true);
		xhr.onreadystatechange = function () {
			if (xhr.readyState !== 4) return;
			if (xhr.status !== 200) {
				renderDetails([], ids.length);
				return;
			}
			try {
				var res = JSON.parse(xhr.responseText || '{}');
				renderDetails(res.students || [], ids.length);
			} catch (e) {
				renderDetails([], ids.length);
			}
		};
		xhr.send(null);
	}

	search.addEventListener('input', function () {
		searchStudents(search.value);
	});

	search.addEventListener('focus', function () {
		searchStudents(search.value);
	});

	document.addEventListener('click', function (e) {
		if (e.target && e.target.getAttribute && e.target.getAttribute('data-id') && e.target.closest('#studentSuggestions')) {
			var id = e.target.getAttribute('data-id');
			addStudent(id);
			search.value = '';
			hideSuggestions();
			return;
		}
		if (e.target && e.target.closest && e.target.closest('.js-remove-student')) {
			var btn = e.target.closest('.js-remove-student');
			removeStudent(btn.getAttribute('data-id'));
			return;
		}
		if (e.target && e.target.closest && e.target.closest('.js-sort')) {
			e.preventDefault();
			var a = e.target.closest('.js-sort');
			var key = a.getAttribute('data-key');
			if (key) {
				if (sortKey === key) {
					sortDir = (sortDir === 'asc') ? 'desc' : 'asc';
				} else {
					sortKey = key;
					sortDir = 'asc';
				}
				fetchDetails();
			}
			return;
		}
		if (e.target !== search && !e.target.closest('#studentSuggestions')) {
			hideSuggestions();
		}
	});

	// Validate at least one student on submit
	var form = search.closest('form');
	if (form) {
		form.addEventListener('submit', function (e) {
			if (getSelectedIds().length === 0) {
				e.preventDefault();
				alert('Please assign at least one student');
				search.focus();
			}
		});
	}

	// Init
	syncSelectedMapFromInputs();
	fetchDetails();
})();
</script>

</body>
</html>
