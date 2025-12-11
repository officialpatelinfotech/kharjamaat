<style>
	#rsvp-container {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 8px;
		padding: 20px;
		max-width: 600px;
		margin: 0 auto;
		box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
	}
</style>
<div class="container margintopcontainer pt-5">
	<?php if ($this->session->flashdata('error')): ?>
		<div class="alert alert-danger">
			<?= $this->session->flashdata('error'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success">
			<?= $this->session->flashdata('success'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('warning')): ?>
		<div class="alert alert-warning">
			<?= $this->session->flashdata('warning'); ?>
		</div>
	<?php endif; ?>

	<div class="mb-4 mb-md-0">
		<a href="<?php echo base_url('accounts/rsvp_list') ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
			<i class="fas fa-arrow-left"></i>
		</a>
	</div>

	<h3 class="heading text-center">RSVP for <span class="text-primary"><?php echo htmlspecialchars($miqaat['name']); ?></span></h3>
	<h6 class="text-center text-muted mb-5">Miqaat Date: <?php echo date("d F Y", strtotime($miqaat['date'])); ?></h6>
	<div id="rsvp-container">
		<h5 class="text-xl font-semibold mb-4">Select Family Members for RSVP</h5>
		<form method="post" action="<?php echo base_url('accounts/submit_general_rsvp'); ?>">
			<input type="hidden" name="miqaat_id" value="<?php echo htmlspecialchars($miqaat['id']); ?>" />
			<ul class="list-group mb-4">
				<?php if (!empty($family)) {
					foreach ($family as $member) { ?>
						<li class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 rounded border">
							<label class="mb-0" for="rsvp_members_<?php echo htmlspecialchars($member['ITS_ID']); ?>">
								<?php echo htmlspecialchars($member['First_Name']) . " " . htmlspecialchars($member['Surname']); ?>
								<span class="text-muted small">(ID: <?php echo htmlspecialchars($member['ITS_ID']); ?>)</span>
							</label>
							<input type="checkbox" name="rsvp_members[]" id="rsvp_members_<?php echo htmlspecialchars($member['ITS_ID']); ?>" <?php echo in_array($member['ITS_ID'], $rsvp_miqaat_ids) ? 'checked' : ''; ?> value="<?php echo htmlspecialchars($member['ITS_ID']); ?>" />
						</li>
					<?php }
				} else { ?>
					<li class="list-group-item">No family members found.</li>
				<?php } ?>
			</ul>

			<!-- Guest RSVP option -->
			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" value="1" id="guest_rsvp_chk" name="guest_rsvp" <?php echo (!empty($guest_rsvp) ? 'checked' : ''); ?> />
				<label class="form-check-label" for="guest_rsvp_chk">
					Include Guests
				</label>
			</div>

			<div id="guestCounts" style="<?php echo (!empty($guest_rsvp) ? '' : 'display:none;'); ?> margin-bottom:12px;">
				<div class="row g-2">
					<div class="col-12 col-md-4">
						<label for="guest_gents" class="form-label small">Gents</label>
						<input type="number" min="0" step="1" class="form-control form-control-sm" id="guest_gents" name="guest_gents" value="<?php echo isset($guest_rsvp['gents']) ? (int)$guest_rsvp['gents'] : 0; ?>" />
					</div>
					<div class="col-12 col-md-4">
						<label for="guest_ladies" class="form-label small">Ladies</label>
						<input type="number" min="0" step="1" class="form-control form-control-sm" id="guest_ladies" name="guest_ladies" value="<?php echo isset($guest_rsvp['ladies']) ? (int)$guest_rsvp['ladies'] : 0; ?>" />
					</div>
					<div class="col-12 col-md-4">
						<label for="guest_children" class="form-label small">Children</label>
						<input type="number" min="0" step="1" class="form-control form-control-sm" id="guest_children" name="guest_children" value="<?php echo isset($guest_rsvp['children']) ? (int)$guest_rsvp['children'] : 0; ?>" />
					</div>
				</div>
			</div>

			<button type="submit" class="btn btn-sm btn-primary">Submit RSVP</button>
		</form>
	</div>
</div>
<script>
	(function() {
		// Fade alerts if jQuery is available, otherwise simple timeout hide
		if (window.jQuery) {
			$(".alert").delay(3000).fadeOut(500);
		} else {
			setTimeout(function() {
				var alerts = document.querySelectorAll('.alert');
				alerts.forEach(function(a) {
					a.style.transition = 'opacity 0.5s';
					a.style.opacity = '0';
					setTimeout(function() {
						if (a.parentNode) a.parentNode.removeChild(a);
					}, 500);
				});
			}, 3000);
		}

		// Guest counts toggle: works with or without jQuery
		function byId(id) {
			return document.getElementById(id);
		}
		var chk = byId('guest_rsvp_chk');
		var counts = byId('guestCounts');
		var gents = byId('guest_gents');
		var ladies = byId('guest_ladies');
		var children = byId('guest_children');

		function setRequired(el, req) {
			if (!el) return;
			if (req) {
				el.setAttribute('required', 'required');
				el.removeAttribute('disabled');
			} else {
				el.removeAttribute('required');
				el.setAttribute('disabled', 'disabled');
			}
		}

		function updateVisibility() {
			if (!chk || !counts) return;
			var checked = false;
			try {
				checked = chk.checked;
			} catch (e) {
				checked = false;
			}
			if (checked) {
				counts.style.display = '';
				setRequired(gents, true);
				setRequired(ladies, true);
				setRequired(children, true);
			} else {
				counts.style.display = 'none';
				setRequired(gents, false);
				setRequired(ladies, false);
				setRequired(children, false);
			}
		}

		// Attach event
		if (chk) {
			chk.addEventListener('change', updateVisibility);
		}
		// init
		updateVisibility();

		// Form submit guard (vanilla)
		var form = document.querySelector('#rsvp-container form') || document.querySelector('form');
		if (form) {
			form.addEventListener('submit', function(e) {
				if (chk && chk.checked) {
					function toInt(v) {
						var n = parseInt(String(v || '0').trim(), 10);
						return isNaN(n) ? NaN : n;
					}
					var a = toInt(gents && gents.value);
					var b = toInt(ladies && ladies.value);
					var c = toInt(children && children.value);
					if (isNaN(a) || isNaN(b) || isNaN(c) || a < 0 || b < 0 || c < 0) {
						e.preventDefault();
						alert('Please enter valid non-negative integers for guest counts.');
						return false;
					}
				}
			});
		}
	})();
</script>