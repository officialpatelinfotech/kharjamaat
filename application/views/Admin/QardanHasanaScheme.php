<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
  $qh_prefix = isset($qh_prefix) && trim((string)$qh_prefix) !== '' ? trim((string)$qh_prefix) : 'admin';
  $qh_scheme_base = $qh_prefix . '/qardanhasana';
  $can_manage = isset($can_manage) ? (bool)$can_manage : true;
  $can_import = isset($can_import) ? (bool)$can_import : $can_manage;

  $is_member_view = ($qh_prefix === 'accounts');
?>

<style>
  .qh-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
  }

  .qh-header-title {
    flex: 1;
    text-align: center;
    margin: 0;
  }

  .qh-header-spacer {
    width: 42px;
    flex: 0 0 42px;
  }

  .qh-import-btn {
    min-width: 120px;
  }

  .qh-filters-card label {
    font-weight: 600;
  }

  @media (max-width: 767.98px) {
    .qh-header-row {
      flex-wrap: wrap;
      justify-content: center;
    }
    .qh-header-title {
      order: 2;
      width: 100%;
      text-align: center;
      margin-top: 8px;
    }
    .qh-header-left {
      order: 1;
      width: 100%;
      display: flex;
      justify-content: space-between;
    }
    .qh-header-spacer {
      display: none;
    }
  }
</style>

<div class="container margintopcontainer pt-5">
  <div class="qh-header-row">
    <div class="qh-header-left">
      <a href="<?php echo base_url($qh_scheme_base); ?>" class="btn btn-outline-secondary" aria-label="Back to Qardan Hasana">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
    </div>
    <h4 class="heading qh-header-title"><?php echo isset($scheme_title) ? htmlspecialchars($scheme_title) : 'Scheme'; ?></h4>
    <div class="qh-header-spacer"></div>
  </div>

  <?php if (isset($total_amount)): ?>
    <?php if (!$is_member_view): ?>
      <div class="text-center mb-4">
        <div class="text-muted" style="font-size: 14px;">Total Amount</div>
        <div style="font-size: 28px; font-weight: 700;">₹<?php echo isset($total_amount) ? format_inr($total_amount, 0) : '0'; ?></div>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($scheme_key) && in_array($scheme_key, ['mohammedi','taher','husain'], true)): ?>
    <?php if ($can_import): ?>
      <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 mb-3">
        <?php if (!empty($qh_import_message)): ?>
          <div class="alert alert-success mb-0 flex-grow-1" style="min-width:260px;"><?php echo htmlspecialchars($qh_import_message); ?></div>
        <?php elseif (!empty($qh_import_error)): ?>
          <div class="alert alert-danger mb-0 flex-grow-1" style="min-width:260px;"><?php echo htmlspecialchars($qh_import_error); ?></div>
        <?php endif; ?>
        <button type="button" class="btn btn-primary qh-import-btn" data-toggle="modal" data-target="#qhImportModal">
          <i class="fa-solid fa-file-import"></i> Import
        </button>
      </div>

      <!-- Import CSV Modal -->
      <div class="modal fade" id="qhImportModal" tabindex="-1" role="dialog" aria-labelledby="qhImportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="qhImportModalLabel">Import CSV</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/import'); ?>" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="form-group">
                  <label for="qh-import-file">Upload CSV File</label>
                  <input type="file" class="form-control" name="import_file" id="qh-import-file" accept=".csv,text/csv" required>
                  <small class="form-text text-muted">Only `.csv` files are supported.</small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="qh-upload-submit" disabled>
                  <i class="fa-solid fa-upload"></i> Upload
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!$is_member_view): ?>
    <div class="card shadow-sm mb-4 qh-filters-card">
      <div class="card-body">
        <form method="get" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key); ?>" class="form-row d-flex flex-wrap align-items-end">
          <div class="form-group col-auto mr-2 mb-2" style="min-width:220px;">
            <label for="miqaat_id">Miqaat Name</label>
            <select class="form-control" id="miqaat_id" name="miqaat_id">
              <option value="">All</option>
              <?php
                $selectedMiqaat = isset($filters['miqaat_id']) ? (string)$filters['miqaat_id'] : '';
                if (!empty($miqaats)):
                  foreach ($miqaats as $m):
                    $mid = isset($m['id']) ? (string)$m['id'] : '';
                    $mname = isset($m['name']) ? (string)$m['name'] : '';
                    $mdate = isset($m['date']) ? (string)$m['date'] : '';
                    $label = $mname;
                    if ($mdate !== '') $label .= ' (' . date('d-m-Y', strtotime($mdate)) . ')';
              ?>
                <option value="<?php echo htmlspecialchars($mid); ?>" <?php echo ($selectedMiqaat !== '' && $selectedMiqaat === $mid) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($label); ?>
                </option>
              <?php
                  endforeach;
                endif;
              ?>
            </select>
          </div>

          <?php if (isset($scheme_key) && $scheme_key === 'husain'): ?>
            <div class="form-group col-auto mr-2 mb-2" style="min-width:150px;">
              <label for="deposit_date">Deposit Date</label>
              <input type="date" class="form-control" id="deposit_date" name="deposit_date" value="<?php echo isset($filters['deposit_date']) ? htmlspecialchars((string)$filters['deposit_date']) : ''; ?>" />
            </div>

            <div class="form-group col-auto mr-2 mb-2" style="min-width:150px;">
              <label for="maturity_date">Maturity Date</label>
              <input type="date" class="form-control" id="maturity_date" name="maturity_date" value="<?php echo isset($filters['maturity_date']) ? htmlspecialchars((string)$filters['maturity_date']) : ''; ?>" />
            </div>

            <div class="form-group col-auto mr-2 mb-2" style="min-width:200px;">
              <label for="duration">Duration</label>
              <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g. 6 months / 1 year" value="<?php echo isset($filters['duration']) ? htmlspecialchars((string)$filters['duration']) : ''; ?>" />
            </div>
          <?php else: ?>
            <div class="form-group col-auto mr-2 mb-2" style="min-width:150px;">
              <label for="hijri_date">Hijri Date</label>
              <input type="text" class="form-control" id="hijri_date" name="hijri_date" placeholder="DD-MM-YYYY" value="<?php echo isset($filters['hijri_date']) ? htmlspecialchars((string)$filters['hijri_date']) : ''; ?>" />
            </div>

            <div class="form-group col-auto mr-2 mb-2" style="min-width:150px;">
              <label for="greg_date">English Date</label>
              <input type="date" class="form-control" id="greg_date" name="greg_date" value="<?php echo isset($filters['greg_date']) ? htmlspecialchars((string)$filters['greg_date']) : ''; ?>" />
            </div>
          <?php endif; ?>

          <?php if (isset($scheme_key) && in_array($scheme_key, ['taher','husain'], true)): ?>
            <div class="form-group col-auto mr-2 mb-2" style="min-width:150px;">
              <label for="its">ITS</label>
              <input type="text" class="form-control" id="its" name="its" placeholder="Enter ITS" value="<?php echo isset($filters['its']) ? htmlspecialchars((string)$filters['its']) : ''; ?>" />
            </div>

            <div class="form-group col-auto mr-2 mb-2" style="min-width:200px;">
              <label for="member_name">Member Name</label>
              <input type="text" class="form-control" id="member_name" name="member_name" placeholder="Enter Member Name" value="<?php echo isset($filters['member_name']) ? htmlspecialchars((string)$filters['member_name']) : ''; ?>" />
            </div>
          <?php endif; ?>

          <div class="form-group col-auto mb-2 ml-auto d-flex align-items-end">
            <a href="<?php echo base_url($qh_scheme_base . '/' . $scheme_key); ?>" class="btn btn-outline-secondary mr-2">Reset</a>
            <button type="submit" class="btn btn-success">Apply Filters</button>
          </div>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
          <h6 class="mb-0">Records</h6>
          <div class="text-muted small">
            <?php echo !empty($records) ? (count($records) . ' record(s)') : '0 record(s)'; ?>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <?php if (isset($scheme_key) && $scheme_key === 'taher'): ?>
                  <th style="white-space:nowrap;">Sr. No.</th>
                  <th style="white-space:nowrap;">ITS</th>
                  <th style="white-space:nowrap;">Member Name</th>
                  <th style="white-space:nowrap;">Unit = 215₹</th>
                  <th style="white-space:nowrap;">No of 215₹ Units</th>
                  <th>Miqaat Name</th>
                  <?php if ($can_manage): ?><th style="white-space:nowrap;">Actions</th><?php endif; ?>
                <?php elseif (isset($scheme_key) && $scheme_key === 'husain'): ?>
                  <th style="white-space:nowrap;">Sr. No.</th>
                  <th style="white-space:nowrap;">ITS</th>
                  <th style="white-space:nowrap;">Member Name</th>
                  <th class="text-right" style="white-space:nowrap;">Amount</th>
                  <th style="white-space:nowrap;">Deposit Date</th>
                  <th style="white-space:nowrap;">Maturity Date</th>
                  <th style="white-space:nowrap;">Duration</th>
                  <?php if ($can_manage): ?><th style="white-space:nowrap;">Actions</th><?php endif; ?>
                <?php else: ?>
                  <th style="white-space:nowrap;">Sr. No.</th>
                  <th style="white-space:nowrap;">Uploaded Date</th>
                  <th>Miqaat Name</th>
                  <th style="white-space:nowrap;">Hijri Date</th>
                  <th style="white-space:nowrap;">English Date</th>
                  <th class="text-right" style="white-space:nowrap;">Collection Amount</th>
                  <?php if ($can_manage): ?><th style="white-space:nowrap;">Actions</th><?php endif; ?>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($records)): ?>
                <?php $sr = 0; foreach ($records as $r): $sr++; ?>
                  <tr>
                    <?php if (isset($scheme_key) && $scheme_key === 'taher'): ?>
                      <td style="white-space:nowrap;"><?php echo (int)$sr; ?></td>
                      <td style="white-space:nowrap;"><?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?></td>
                      <td><?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?></td>
                      <td style="white-space:nowrap;">215</td>
                      <td style="white-space:nowrap;" class="text-right"><?php echo isset($r['units']) ? (int)$r['units'] : 0; ?></td>
                      <td><?php echo htmlspecialchars((string)($r['miqaat_name'] ?? '')); ?></td>
                      <?php if ($can_manage): ?>
                      <td style="white-space:nowrap;">
                        <button
                          type="button"
                          class="btn btn-sm btn-primary qh-edit-taher-btn mr-1"
                          data-toggle="modal"
                          data-target="#qhEditTaherModal"
                          data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                          data-its="<?php echo htmlspecialchars((string)($r['ITS'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-units="<?php echo htmlspecialchars((string)($r['units'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-miqaat_name="<?php echo htmlspecialchars((string)($r['miqaat_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        >
                          <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i> Delete
                          </button>
                        </form>
                      </td>
                      <?php endif; ?>
                    <?php elseif (isset($scheme_key) && $scheme_key === 'husain'): ?>
                      <td style="white-space:nowrap;"><?php echo (int)$sr; ?></td>
                      <td style="white-space:nowrap;"><?php echo htmlspecialchars((string)($r['ITS'] ?? '')); ?></td>
                      <td><?php echo htmlspecialchars((string)($r['member_name'] ?? '')); ?></td>
                      <td class="text-right" style="white-space:nowrap;">₹<?php echo isset($r['amount']) ? (function_exists('format_inr') ? format_inr((float)$r['amount'], 0) : number_format((float)$r['amount'], 0)) : '0'; ?></td>
                      <td style="white-space:nowrap;">
                        <?php
                          $dd = isset($r['deposit_date']) ? (string)$r['deposit_date'] : '';
                          echo $dd ? htmlspecialchars(date('d-m-Y', strtotime($dd))) : '';
                        ?>
                      </td>
                      <td style="white-space:nowrap;">
                        <?php
                          $md = isset($r['maturity_date']) ? (string)$r['maturity_date'] : '';
                          echo $md ? htmlspecialchars(date('d-m-Y', strtotime($md))) : '';
                        ?>
                      </td>
                      <td style="white-space:nowrap;"><?php echo htmlspecialchars((string)($r['duration'] ?? '')); ?></td>
                      <?php if ($can_manage): ?>
                      <td style="white-space:nowrap;">
                        <button
                          type="button"
                          class="btn btn-sm btn-primary qh-edit-husain-btn mr-1"
                          data-toggle="modal"
                          data-target="#qhEditHusainModal"
                          data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                          data-its="<?php echo htmlspecialchars((string)($r['ITS'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-amount="<?php echo htmlspecialchars((string)($r['amount'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-deposit_date="<?php echo htmlspecialchars((string)($r['deposit_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-maturity_date="<?php echo htmlspecialchars((string)($r['maturity_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-duration="<?php echo htmlspecialchars((string)($r['duration'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        >
                          <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i> Delete
                          </button>
                        </form>
                      </td>
                      <?php endif; ?>
                    <?php else: ?>
                      <td style="white-space:nowrap;"><?php echo (int)$sr; ?></td>
                      <td style="white-space:nowrap;">
                        <?php
                          $ud = isset($r['uploaded_date']) ? $r['uploaded_date'] : '';
                          echo $ud ? htmlspecialchars(date('d-m-Y', strtotime($ud))) : '';
                        ?>
                      </td>
                      <td><?php echo htmlspecialchars($r['miqaat_name'] ?? ''); ?></td>
                      <td style="white-space:nowrap;">
                        <?php
                          $hd = isset($r['hijri_date']) ? (string)$r['hijri_date'] : '';
                          if ($hd !== '' && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $hd, $m)) {
                            $hd = $m[3] . '-' . $m[2] . '-' . $m[1];
                          }
                          echo htmlspecialchars($hd);
                        ?>
                      </td>
                      <td style="white-space:nowrap;">
                        <?php
                          $ed = isset($r['eng_date']) ? $r['eng_date'] : '';
                          echo $ed ? htmlspecialchars(date('d-m-Y', strtotime($ed))) : '';
                        ?>
                      </td>
                      <td class="text-right" style="white-space:nowrap;">
                        <?php echo isset($r['collection_amount']) ? '₹' . (function_exists('format_inr') ? format_inr((float)$r['collection_amount'], 0) : number_format((float)$r['collection_amount'], 0)) : '0'; ?>
                      </td>
                      <?php if ($can_manage): ?>
                      <td style="white-space:nowrap;">
                        <button
                          type="button"
                          class="btn btn-sm btn-primary qh-edit-btn mr-1"
                          data-toggle="modal"
                          data-target="#qhEditModal"
                          data-id="<?php echo (int)($r['id'] ?? 0); ?>"
                          data-miqaat_name="<?php echo htmlspecialchars((string)($r['miqaat_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-hijri_date="<?php
                            $hdAttr = (string)($r['hijri_date'] ?? '');
                            if ($hdAttr !== '' && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $hdAttr, $m2)) {
                              $hdAttr = $m2[3] . '-' . $m2[2] . '-' . $m2[1];
                            }
                            echo htmlspecialchars($hdAttr, ENT_QUOTES, 'UTF-8');
                          ?>"
                          data-eng_date="<?php echo htmlspecialchars((string)($r['eng_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                          data-collection_amount="<?php echo htmlspecialchars((string)($r['collection_amount'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        >
                          <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        <form method="post" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/delete/' . (int)($r['id'] ?? 0)); ?>" class="d-inline" onsubmit="return confirm('Delete this record?');">
                          <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i> Delete
                          </button>
                        </form>
                      </td>
                      <?php endif; ?>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <?php
                    $baseCols = (isset($scheme_key) && $scheme_key === 'taher') ? 6 : ((isset($scheme_key) && $scheme_key === 'husain') ? 7 : 6);
                    $colspan = $baseCols + ($can_manage ? 1 : 0);
                  ?>
                  <td colspan="<?php echo (int)$colspan; ?>" class="text-center text-muted">No records found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php if ($can_manage): ?>

    <!-- Edit Record Modal (Mohammedi) -->
    <div class="modal fade" id="qhEditModal" tabindex="-1" role="dialog" aria-labelledby="qhEditModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qhEditModalLabel">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" id="qh-edit-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-id" value="" />

              <div class="form-group">
                <label for="qh-edit-miqaat-name">Miqaat Name</label>
                <input type="text" class="form-control" name="miqaat_name" id="qh-edit-miqaat-name" required />
              </div>

              <div class="form-group">
                <label for="qh-edit-hijri-date">Hijri Date</label>
                <input type="text" class="form-control" name="hijri_date" id="qh-edit-hijri-date" placeholder="DD-MM-YYYY" required />
              </div>

              <div class="form-group">
                <label for="qh-edit-eng-date">English Date</label>
                <input type="date" class="form-control" name="eng_date" id="qh-edit-eng-date" required />
              </div>

              <div class="form-group mb-0">
                <label for="qh-edit-amount">Collection Amount</label>
                <input type="number" step="0.01" class="form-control" name="collection_amount" id="qh-edit-amount" required />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Record Modal (Taher) -->
    <div class="modal fade" id="qhEditTaherModal" tabindex="-1" role="dialog" aria-labelledby="qhEditTaherModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qhEditTaherModalLabel">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" id="qh-edit-taher-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-taher-id" value="" />

              <div class="form-group">
                <label for="qh-edit-taher-its">ITS</label>
                <input type="text" class="form-control" name="ITS" id="qh-edit-taher-its" required />
              </div>

              <div class="form-group">
                <label>Unit</label>
                <input type="text" class="form-control" value="215" readonly />
                <input type="hidden" name="unit" id="qh-edit-taher-unit" value="215" />
              </div>

              <div class="form-group">
                <label for="qh-edit-taher-units">No of 215₹ Units</label>
                <input type="number" class="form-control" name="units" id="qh-edit-taher-units" min="0" step="1" required />
              </div>

              <div class="form-group mb-0">
                <label for="qh-edit-taher-miqaat">Miqaat Name</label>
                <input type="text" class="form-control" name="miqaat_name" id="qh-edit-taher-miqaat" required />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Record Modal (Husain) -->
    <div class="modal fade" id="qhEditHusainModal" tabindex="-1" role="dialog" aria-labelledby="qhEditHusainModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qhEditHusainModalLabel">Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" id="qh-edit-husain-form" action="<?php echo base_url($qh_scheme_base . '/' . $scheme_key . '/update/'); ?>">
            <div class="modal-body">
              <input type="hidden" name="id" id="qh-edit-husain-id" value="" />

              <div class="form-group">
                <label for="qh-edit-husain-its">ITS</label>
                <input type="text" class="form-control" name="ITS" id="qh-edit-husain-its" required />
              </div>

              <div class="form-group">
                <label for="qh-edit-husain-amount">Amount</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="qh-edit-husain-amount" required />
              </div>

              <div class="form-group">
                <label for="qh-edit-husain-deposit">Deposit Date</label>
                <input type="date" class="form-control" name="deposit_date" id="qh-edit-husain-deposit" />
              </div>

              <div class="form-group">
                <label for="qh-edit-husain-maturity">Maturity Date</label>
                <input type="date" class="form-control" name="maturity_date" id="qh-edit-husain-maturity" />
              </div>

              <div class="form-group mb-0">
                <label for="qh-edit-husain-duration">Duration (Months/Year)</label>
                <input type="text" class="form-control" name="duration" id="qh-edit-husain-duration" placeholder="e.g. 12 Months / 1 Year" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      (function() {
        var file = document.getElementById('qh-import-file');
        var submitBtn = document.getElementById('qh-upload-submit');
        if (!file || !submitBtn) return;
        var update = function() {
          submitBtn.disabled = !(file.files && file.files.length > 0);
        };
        file.addEventListener('change', update);
        update();
      })();

      (function() {
        var editForm = document.getElementById('qh-edit-form');
        if (!editForm) return;

        var baseAction = editForm.getAttribute('action') || '';
        var setVal = function(id, value) {
          var el = document.getElementById(id);
          if (!el) return;
          el.value = (value === undefined || value === null) ? '' : value;
        };

        var buttons = document.querySelectorAll('.qh-edit-btn');
        if (!buttons || !buttons.length) return;
        buttons.forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id') || '';
            setVal('qh-edit-id', id);
            setVal('qh-edit-miqaat-name', btn.getAttribute('data-miqaat_name') || '');
            setVal('qh-edit-hijri-date', btn.getAttribute('data-hijri_date') || '');
            setVal('qh-edit-eng-date', btn.getAttribute('data-eng_date') || '');
            setVal('qh-edit-amount', btn.getAttribute('data-collection_amount') || '');

            // Ensure action ends with / then append id
            var action = baseAction;
            if (action.slice(-1) !== '/') action += '/';
            editForm.setAttribute('action', action + encodeURIComponent(id));
          });
        });
      })();

      (function() {
        var editForm = document.getElementById('qh-edit-taher-form');
        if (!editForm) return;

        var baseAction = editForm.getAttribute('action') || '';
        var setVal = function(id, value) {
          var el = document.getElementById(id);
          if (!el) return;
          el.value = (value === undefined || value === null) ? '' : value;
        };

        var buttons = document.querySelectorAll('.qh-edit-taher-btn');
        if (!buttons || !buttons.length) return;
        buttons.forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id') || '';
            setVal('qh-edit-taher-id', id);
            setVal('qh-edit-taher-its', btn.getAttribute('data-its') || '');
            setVal('qh-edit-taher-unit', '215');
            setVal('qh-edit-taher-units', btn.getAttribute('data-units') || '0');
            setVal('qh-edit-taher-miqaat', btn.getAttribute('data-miqaat_name') || '');

            var action = baseAction;
            if (action.slice(-1) !== '/') action += '/';
            editForm.setAttribute('action', action + encodeURIComponent(id));
          });
        });
      })();

      (function() {
        var editForm = document.getElementById('qh-edit-husain-form');
        if (!editForm) return;

        var baseAction = editForm.getAttribute('action') || '';
        var setVal = function(id, value) {
          var el = document.getElementById(id);
          if (!el) return;
          el.value = (value === undefined || value === null) ? '' : value;
        };

        var buttons = document.querySelectorAll('.qh-edit-husain-btn');
        if (!buttons || !buttons.length) return;
        buttons.forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id') || '';
            setVal('qh-edit-husain-id', id);
            setVal('qh-edit-husain-its', btn.getAttribute('data-its') || '');
            setVal('qh-edit-husain-amount', btn.getAttribute('data-amount') || '0');
            setVal('qh-edit-husain-deposit', btn.getAttribute('data-deposit_date') || '');
            setVal('qh-edit-husain-maturity', btn.getAttribute('data-maturity_date') || '');
            setVal('qh-edit-husain-duration', btn.getAttribute('data-duration') || '');

            var action = baseAction;
            if (action.slice(-1) !== '/') action += '/';
            editForm.setAttribute('action', action + encodeURIComponent(id));
          });
        });
      })();
    </script>

    <?php endif; ?>
  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-body">
        <p class="mb-0">This scheme page is ready for wiring up (list/add/update) as needed.</p>
      </div>
    </div>
  <?php endif; ?>
</div>
