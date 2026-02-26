<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	body { padding-top: 70px; }
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
</style>

<div class="container mt-4">
	<?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa'; ?>
	<div class="row">
		<div class="col-12">
			<div class="p-0 mb-4">
				<div class="d-flex justify-content-between align-items-center">
					<a href="<?php echo base_url($madresa_base . '/classes/view/' . (int)$class['id']); ?>" class="btn btn-outline-secondary btn-sm btn-back" aria-label="Back" title="Back"><span class="back-arrow">&larr;</span></a>
					<div class="d-flex">
						<a href="<?php echo base_url($madresa_base . '/classes/view/' . (int)$class['id']); ?>" class="btn btn-outline-secondary btn-sm">View</a>
					</div>
				</div>
			</div>

			<h3 class="text-center mb-4">Edit Class</h3>

			<?php if (!empty($error)) { ?>
				<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
			<?php } ?>
			<?php if (!empty($message)) { ?>
				<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
			<?php } ?>

			<div class="card shadow-sm">
				<div class="card-body">
					<form method="post" action="<?php echo base_url($madresa_base . '/classes/update/' . (int)$class['id']); ?>">
						<div class="form-group">
							<label>Class</label>
							<input type="text" name="class_name" class="form-control" required maxlength="255" value="<?php echo htmlspecialchars($class['class_name']); ?>" />
						</div>

						<div class="form-group">
							<label>Hijri Year</label>
							<select name="hijri_year" class="form-control" required>
								<option value="">Select Hijri year</option>
								<?php if (!empty($hijri_years)) { ?>
									<?php foreach ($hijri_years as $y) { ?>
										<option value="<?php echo (int)$y; ?>" <?php echo (!empty($class['hijri_year']) && (int)$class['hijri_year'] === (int)$y) ? 'selected' : ''; ?>>
											<?php echo (int)$y; ?>
										</option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>

						<?php $selected_status = !empty($class['status']) ? (string)$class['status'] : 'Active'; ?>
						<?php $madresa_view_prefix = !empty($madresa_view_prefix) ? (string)$madresa_view_prefix : 'Admin/Madresa'; ?>
						<?php $this->load->view($madresa_view_prefix . '/_status_select'); ?>

						<div class="form-group">
							<label>Fees</label>
							<input type="number"
										 name="fees"
										 class="form-control"
										 min="0"
										 step="0.01"
										 value="<?php echo htmlspecialchars((string)($class['fees'] ?? '')); ?>"
										 placeholder="Enter fees" />
						</div>

						<div class="form-group">
							<label>Assign Students</label>
							<div class="position-relative">
								<input type="text" class="form-control" id="studentSearch" placeholder="Type student name or ITS..." autocomplete="off" />
								<div id="studentSuggestions" class="list-group" style="display:none;position:absolute;left:0;right:0;z-index:1000;max-height:260px;overflow:auto;"></div>
							</div>
							<div id="studentIdsContainer">
								<?php if (!empty($assigned_ids)) { ?>
									<?php foreach ($assigned_ids as $aid) { ?>
										<input type="hidden" name="student_ids[]" value="<?php echo (int)$aid; ?>" />
									<?php } ?>
								<?php } ?>
							</div>

							<div id="studentDetails" class="mt-3" style="display:none;"></div>
						</div>

						<button type="submit" class="btn btn-primary">Save Changes</button>
						<a href="<?php echo base_url($madresa_base . '/classes'); ?>" class="btn btn-outline-secondary">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function () {
		var search = document.getElementById('studentSearch');
		var suggestions = document.getElementById('studentSuggestions');
		var idsBox = document.getElementById('studentIdsContainer');
		var details = document.getElementById('studentDetails');
		var detailsUrl = "<?php echo base_url($madresa_base . '/student-details'); ?>";

		var studentsData = <?php echo json_encode(!empty($students) ? $students : []); ?>;
		if (!search || !suggestions || !idsBox) return;

		function escapeHtml(str) {
			return String(str == null ? '' : str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		// Index by id for fast lookup
		var byId = {};
		for (var i = 0; i < studentsData.length; i++) {
			var r = studentsData[i] || {};
			if (r.ITS_ID != null) byId[String(r.ITS_ID)] = r;
		}

		var selectedIds = {}; // map of id -> true
		var enrolledIds = {}; // map of id -> true (already enrolled this year)
		var sortKey = 'Full_Name';
		var sortDir = 'asc';

		var enrolledStudentsUrl = "<?php echo base_url($madresa_base . '/ajax-get-enrolled-students'); ?>";
		var hijriYearSelect = document.querySelector('select[name="hijri_year"]');
		var currentClassId = "<?php echo (int)($class['id'] ?? 0); ?>";

		function loadEnrolledStudents() {
			if (!hijriYearSelect) return;
			var year = hijriYearSelect.value;
			enrolledIds = {}; // Reset
			if (!year) return;

			var xhr = new XMLHttpRequest();
			var url = enrolledStudentsUrl + '?hijri_year=' + encodeURIComponent(year);
			if (currentClassId > 0) {
				url += '&exclude_class_id=' + encodeURIComponent(currentClassId);
			}
			
			xhr.open('GET', url, true);
			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					try {
						var res = JSON.parse(xhr.responseText);
						if (res.success && res.enrolled_ids) {
							for (var i = 0; i < res.enrolled_ids.length; i++) {
								enrolledIds[String(res.enrolled_ids[i])] = true;
							}
						}
					} catch (e) {
						console.error("Failed to parse enrolled students", e);
					}
				}
			};
			xhr.send();
		}

		if (hijriYearSelect) {
			hijriYearSelect.addEventListener('change', function() {
				loadEnrolledStudents();
				search.value = ''; // clear search when year changes
				hideSuggestions();
			});
			loadEnrolledStudents(); // Load initially
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
			for (var i = 0; i < ids.length; i++) {
				var id = String(ids[i]);
				if (id) selectedIds[id] = true;
			}
		}

		function isAlreadySelected(id) {
			id = String(id);
			if (selectedIds[id]) return true;
			var inputs = idsBox.querySelectorAll('input[name="student_ids[]"]');
			for (var i = 0; i < inputs.length; i++) {
				if (String(inputs[i].value) === id) {
					selectedIds[id] = true;
					return true;
				}
			}
			return false;
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
				// Skip if already selected in this class OR already enrolled in another class for this year
				if (isAlreadySelected(id) || enrolledIds[id]) continue;
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

			var list = students.slice(0);
			list.sort(function (a, b) {
				var av = (a && a[sortKey] != null) ? a[sortKey] : '';
				var bv = (b && b[sortKey] != null) ? b[sortKey] : '';

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

			var limit = 30;
			var idsForFetch = ids.slice(0, limit);
			var url = detailsUrl + '?ids=' + encodeURIComponent(idsForFetch.join(','));

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

		syncSelectedMapFromInputs();
		fetchDetails();
	})();
</script>
