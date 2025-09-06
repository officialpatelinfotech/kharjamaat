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
	<h6 class="text-center text-muted mb-5">Date: <?php echo date("d F Y", strtotime($miqaat['date'])); ?></h6>
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
			<button type="submit" class="btn btn-sm btn-primary">Submit RSVP</button>
		</form>
	</div>
</div>
<script>
	$(".alert").delay(3000).fadeOut(500);
</script>