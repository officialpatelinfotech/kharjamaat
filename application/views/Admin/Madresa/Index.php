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
	<?php $back_path = ($madresa_base === 'admin/madresa') ? 'admin' : $madresa_base; ?>
	<div class="row">
		<div class="col-12">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<div class="d-flex align-items-center">
					<a href="<?php echo base_url($back_path); ?>" class="btn btn-outline-secondary btn-sm btn-back mr-3" aria-label="Back" title="Back">
						<span class="back-arrow">&larr;</span>
					</a>
					<h3 class="mb-0">Madresa Module</h3>
				</div>
			</div>
		</div>

		<div class="col-md-6 mt-3">
			<a href="<?php echo base_url($madresa_base . '/classes/new'); ?>" class="text-decoration-none">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="card-title mb-2">Create Classes</h5>
						<p class="card-text text-muted mb-0">Add a new class for Madresa.</p>
					</div>
				</div>
			</a>
		</div>

		<div class="col-md-6 mt-3">
			<a href="<?php echo base_url($madresa_base . '/classes'); ?>" class="text-decoration-none">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="card-title mb-2">Manage Classes</h5>
						<p class="card-text text-muted mb-0">View and manage existing classes.</p>
					</div>
				</div>
			</a>
		</div>
	</div>
</div>
