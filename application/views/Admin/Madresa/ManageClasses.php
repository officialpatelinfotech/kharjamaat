<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Manage Classes</title>

	<!-- Bootstrap & FontAwesome (remove if already loaded globally) -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>


		/* FIX: space for fixed admin header */
		body {
			padding-top: 70px; /* adjust if your header is taller */
		}

		/* Header layout */
		.page-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			flex-wrap: wrap;
			gap: 10px;
			margin-bottom: 10px;
		}

		.page-header-left {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.page-header-right {
			display: flex;
			align-items: center;
			gap: 8px;
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

		/* Uniform height */
		.form-control-sm,
		.btn-sm {
			height: 34px;
			box-sizing: border-box;
		}

		.page-header-right form {
			margin: 0;
		}

		/* Card styling */
		.card {
			border-radius: 8px;
		}

		.card-body {
			padding: 1.5rem;
		}

		/* Table */
		.table th {
			background: #f8f9fa;
			font-weight: 600;
			white-space: nowrap;
		}

		.table td {
			vertical-align: middle;
		}

		.table .btn {
			margin-right: 4px;
		}

		.action-btn {
			width: 34px;
			height: 34px;
			padding: 0;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 4px;
			font-size: 14px;
		}

		/* Empty state */
		.empty-state {
			text-align: center;
			padding: 40px 10px;
		}

		/* Center card on large screens */
		@media (min-width: 1200px) {
			.card {
				max-width: 1100px;
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

<div class="container mt-3">
	<?php $madresa_base = !empty($madresa_base) ? (string)$madresa_base : 'admin/madresa'; ?>
	<div class="row">
		<div class="col-12">

			<!-- PAGE HEADER -->
			<div class="p-0 mb-4">
				<div class="d-flex justify-content-between align-items-center">
					<a href="<?php echo base_url($madresa_base); ?>"
						 class="btn btn-outline-secondary btn-sm btn-back"
						 title="Back">
						<span class="back-arrow">&larr;</span>
					</a>
					
					<div class="page-header-right">
						<?php $is_jamaat = !empty($_SESSION['user']['username']) && $_SESSION['user']['username'] === 'jamaat'; ?>
						<?php $is_admin = (!empty($_SESSION['user']['role']) && (int)$_SESSION['user']['role'] === 1) || (!empty($_SESSION['user']['username']) && $_SESSION['user']['username'] === 'admin'); ?>


						<?php if (empty($is_jamaat)) { ?>
							<a href="<?php echo base_url($madresa_base . '/classes/new'); ?>"
								 class="btn btn-primary btn-sm">
								Create Class
							</a>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="text-center mb-4">
				<h3 class="mb-1">Manage Classes</h3>
			</div>

			<div class="card shadow-sm mb-4">
				<div class="card-body py-3">
					<form method="get" action="<?php echo base_url($madresa_base . '/classes'); ?>" class="row g-2 align-items-end">
						<div class="col-md-3">
							<label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #6c757d; margin-bottom: 2px;">Hijri Year</label>
							<select name="year" class="form-control form-control-sm">
								<?php if (!empty($hijri_years)) { ?>
									<?php foreach ($hijri_years as $y) { ?>
										<option value="<?php echo (int)$y; ?>"
											<?php echo (!empty($selected_hijri_year) && (int)$selected_hijri_year === (int)$y) ? 'selected' : ''; ?>>
											<?php echo (int)$y; ?>
										</option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-3">
							<label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #6c757d; margin-bottom: 2px;">Class</label>
							<input type="text" name="class" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)($selected_class_query ?? '')); ?>" placeholder="Search class...">
						</div>
						<div class="col-md-4">
							<label class="form-label" style="font-size: 0.85rem; font-weight: 600; color: #6c757d; margin-bottom: 2px;">Student Name</label>
							<input type="text" name="student_name" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)($selected_student_query ?? '')); ?>" placeholder="Search student...">
						</div>
						<div class="col-md-2 mt-3 mt-md-0 d-flex align-items-end">
							<button type="submit" class="btn btn-primary btn-sm w-50 mr-1" style="margin-right: 4px;">Filter</button>
							<a href="<?php echo base_url($madresa_base . '/classes'); ?>" class="btn btn-outline-secondary btn-sm w-50 text-center" style="margin-left: 4px;">Clear</a>
						</div>
					</form>
				</div>
			</div>

			<!-- SUCCESS MESSAGE -->
			<?php $message = $this->input->get('message'); ?>
			<?php if (!empty($message)) { ?>
				<div class="alert alert-success">
					<?php echo htmlspecialchars($message); ?>
				</div>
			<?php } ?>

			<!-- ERROR MESSAGE -->
			<?php $error = $this->input->get('error'); ?>
			<?php if (!empty($error)) { ?>
				<div class="alert alert-danger">
					<?php echo htmlspecialchars($error); ?>
				</div>
			<?php } ?>

			<!-- CARD -->
			<div class="card shadow-sm">
				<div class="card-body">

					<?php if (empty($classes)) { ?>

						<!-- EMPTY STATE -->
						<div class="empty-state">
							<p class="text-muted mb-3">
								No classes found for the selected Hijri year.
							</p>
							<a href="<?php echo base_url($madresa_base . '/classes/new'); ?>"
								 class="btn btn-primary btn-sm px-4">
								Create your first class
							</a>
						</div>

					<?php } else { ?>

						<!-- TABLE -->
						<div class="table-responsive">
							<table class="table table-striped table-hover mb-0">
								<thead>
									<tr>
										<th>Sr No</th>
										<th>Class</th>
										<th>Hijri Year</th>
										<th>Students</th>
										<th>Fees</th>
										<th>Status</th>
										<th>Created</th>
										<th class="text-center">Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php $sr = 1; ?>
									<?php foreach ($classes as $row) { ?>
										<tr>
											<td><?php echo (int)$sr++; ?></td>
											<td><?php echo htmlspecialchars($row['class_name']); ?></td>
											<td>
												<?php if (!empty($row['hijri_year'])) { ?>
													<?php echo (int)$row['hijri_year']; ?>
												<?php } else { ?>
													<span class="badge badge-warning">Unassigned</span>
												<?php } ?>
											</td>
											<td><?php echo (int)($row['student_count'] ?? 0); ?></td>
											<td>
												<?php 
													$fees = (float)($row['fees'] ?? 0);
													echo '₹' . ($fees == (int)$fees ? number_format($fees, 0) : number_format($fees, 2));
												?>
											</td>
											<td class="text-center">
												<?php
													$st = trim((string)($row['status'] ?? ''));
													$stClass = (strtolower($st) === 'active') ? 'badge badge-info' : 'badge badge-secondary';
												?>
												<span class="<?php echo $stClass; ?>"><?php echo htmlspecialchars($st !== '' ? $st : '-'); ?></span>
											</td>
											<td>
												<?php
													$createdAt = $row['created_at'] ?? '';
													$ts = $createdAt ? strtotime($createdAt) : false;
													echo $ts ? date('d-m-Y', $ts) : htmlspecialchars((string)$createdAt);
												?>
											</td>
											<td class="text-center">
												<a href="<?php echo base_url($madresa_base . '/classes/view/' . (int)$row['id']); ?>"
													 class="btn btn-outline-secondary btn-sm action-btn"
													 title="View" aria-label="View">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												<?php if (empty($is_jamaat)) { ?>
													<a href="<?php echo base_url($madresa_base . '/classes/edit/' . (int)$row['id']); ?>"
														 class="btn btn-primary btn-sm action-btn"
														 title="Edit" aria-label="Edit">
														<i class="fa fa-pencil" aria-hidden="true"></i>
													</a>
												<?php } ?>

												<?php if (!empty($is_admin)) { ?>
													<form method="post"
																action="<?php echo base_url($madresa_base . '/classes/delete/' . (int)$row['id']); ?>"
																class="d-inline"
																onsubmit="return confirm('Are you sure you want to delete this class? This will remove all assigned students from it.');">
														<input type="hidden" name="year" value="<?php echo (int)$selected_hijri_year; ?>">
														<button type="submit" class="btn btn-outline-danger btn-sm action-btn" title="Delete" aria-label="Delete">
															<i class="fa fa-trash" aria-hidden="true"></i>
														</button>
													</form>
												<?php } ?>
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

</body>
</html>
