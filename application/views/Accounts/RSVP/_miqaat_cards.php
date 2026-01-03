<?php if (!empty($miqaats) && is_array($miqaats)): ?>
  <div class="row justify-content-center" id="miqaat-cards">
    <?php foreach ($miqaats as $miqaat): ?>
      <div class="col-12 col-md-6 col-lg-4 mb-5 d-flex miqaat-item"
           data-name="<?php echo htmlspecialchars($miqaat['name'], ENT_QUOTES); ?>"
           data-type="<?php echo htmlspecialchars($miqaat['type'], ENT_QUOTES); ?>"
           data-date="<?php echo date('d M Y', strtotime($miqaat['date'])); ?>"
           data-hijri="<?php echo htmlspecialchars($miqaat['hijri_date'], ENT_QUOTES); ?>"
           data-status="<?php echo htmlspecialchars($miqaat['status'], ENT_QUOTES); ?>">
        <div class="card shadow-sm border flex-fill d-flex flex-column justify-content-between" style="min-height:220px; position:relative;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-2"><?php echo htmlspecialchars($miqaat['name']); ?></h5>
            <div class="mb-1"><span class="fw-bold">Type:</span> <?php echo htmlspecialchars($miqaat['type']); ?></div>
            <div class="mb-1"><span class="fw-bold">Date:</span> <?php echo date('d M Y', strtotime($miqaat['date'])); ?></div>
            <div class="mb-1"><span class="fw-bold">Hijri Date:</span> <?php echo $miqaat['hijri_date']; ?></div>
            <div class="mb-1"><span class="fw-bold">Miqaat Status:</span> <?php echo $miqaat['status'] == '1' ? '<span class="text-success">Active</span>' : '<span class="text-danger">Inactive</span>'; ?></div>
            <div class="mb-5">
              <span class="fw-bold">RSVP Status:</span> <?php echo in_array($miqaat["id"], (isset($rsvp_overview[$miqaat["id"]]) && $rsvp_overview[$miqaat["id"]]) ? array_column($rsvp_overview[$miqaat["id"]], 'miqaat_id') : []) ? '<span class="badge badge-success">Confirmed</span>' : '<span class="badge badge-danger">Pending</span>'; ?>
            </div>
            <?php if (isset($miqaat["raza_status"])): ?>
              <?php if ($miqaat["raza_status"] == 1): ?>
                <div class="d-flex justify-content-end align-items-end flex-grow-1" style="position:absolute; bottom:16px; right:16px; left:16px;">
                  <a href="<?php echo base_url('accounts/general_rsvp/' . $miqaat['id']); ?>" class="btn btn-sm btn-primary ms-auto"><?php echo (in_array($miqaat["id"], ((isset($rsvp_overview[$miqaat["id"]]) && $rsvp_overview[$miqaat["id"]]) ? array_column($rsvp_overview[$miqaat["id"]], 'miqaat_id') : [])) ? 'Update RSVP' : 'RSVP'); ?></a>
                </div>
              <?php else: ?>
                <div class="d-flex justify-content-end align-items-end flex-grow-1" style="position:absolute; bottom:16px; right:16px; left:16px;">
                  <button class="btn btn-sm btn-secondary ms-auto" disabled>RSVP</button>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-info text-center">No upcoming events to RSVP.</div>
<?php endif; ?>
