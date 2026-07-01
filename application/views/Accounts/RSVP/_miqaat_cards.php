<?php
// Accounts/RSVP/_miqaat_cards.php
// Rendered inline on page load AND returned as HTML via rsvp_search AJAX.
// Must include its own styles so it works standalone in both contexts.
?>
<style>
  /* ── Miqaat cards (scoped) ── */
  .miqaat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  @media (max-width: 991px) { .miqaat-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 600px)  { .miqaat-grid { grid-template-columns: 1fr; gap: 12px; } }

  .miq-card {
    background: var(--surface, #fff);
    border: 1.5px solid var(--border, #e8e0cc);
    border-radius: var(--radius-lg, 20px);
    box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,.06));
    display: flex; flex-direction: column;
    overflow: hidden;
    transition: box-shadow .2s, border-color .2s;
  }
  .miq-card:hover { box-shadow: var(--shadow, 0 4px 16px rgba(0,0,0,.07)); border-color: rgba(184,134,11,.3); }
  .miq-card::before { content: ''; display: block; height: 3px; }
  .miq-card.active-card::before   { background: linear-gradient(90deg, var(--green, #1a6645), #4cc790); }
  .miq-card.inactive-card::before { background: linear-gradient(90deg, var(--text-3, #9c8f7a), #c4b49a); }

  .miq-card-header { padding: 14px 18px 12px; background: var(--surface-2, #f7f4ec); border-bottom: 1px solid var(--border, #e8e0cc); display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
  .miq-name { font-size: 0.92rem; font-weight: 700; color: var(--text-1, #1a1610); margin: 0; line-height: 1.3; }
  .miq-type-tag { font-size: 0.62rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3, #9c8f7a); margin-top: 3px; }

  /* status pills */
  .status-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 40px; font-size: 0.63rem; font-weight: 700; letter-spacing: .3px; white-space: nowrap; flex-shrink: 0; }
  .pill-active   { background: #eaf4ee; color: #1a6645; border: 1px solid #bbf7d0; }
  .pill-inactive { background: #f7f4ec; color: #9c8f7a; border: 1px solid #e8e0cc; }
  .pill-confirmed{ background: #eaf4ee; color: #1a6645; border: 1px solid #bbf7d0; }
  .pill-pending  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

  .miq-card-body { padding: 14px 18px; flex: 1; display: flex; flex-direction: column; gap: 7px; }
  .miq-info-row { display: flex; align-items: baseline; gap: 8px; font-size: 0.8rem; }
  .miq-info-label { font-size: 0.63rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3, #9c8f7a); min-width: 68px; flex-shrink: 0; }
  .miq-info-value { font-weight: 500; color: var(--text-2, #5a5244); }

  .miq-card-footer { padding: 12px 18px 16px; border-top: 1px solid var(--border, #e8e0cc); display: flex; justify-content: flex-end; }

  .btn-rsvp {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 20px; border-radius: var(--radius-sm, 8px); border: none;
    background: linear-gradient(135deg, #b8860b, #8a6408);
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 700; text-decoration: none;
    box-shadow: 0 2px 6px rgba(184,134,11,.25); transition: all .18s;
  }
  .btn-rsvp:hover { background: linear-gradient(135deg, #8a6408, #6b4d06); transform: translateY(-1px); box-shadow: 0 4px 10px rgba(184,134,11,.35); color: #fff; text-decoration: none; }
  .btn-rsvp-update { background: linear-gradient(135deg, #1a6645, #134d34); box-shadow: 0 2px 6px rgba(26,102,69,.25); }
  .btn-rsvp-update:hover { background: linear-gradient(135deg, #134d34, #0d3524); box-shadow: 0 4px 10px rgba(26,102,69,.35); }
  .btn-rsvp-disabled { display: inline-flex; align-items: center; gap: 6px; padding: 8px 20px; border-radius: var(--radius-sm, 8px); border: 1.5px solid var(--border, #e8e0cc); background: var(--surface-2, #f7f4ec); color: var(--text-3, #9c8f7a); font-size: 0.78rem; font-weight: 700; cursor: not-allowed; }

  .empty-state-miq { grid-column: 1 / -1; padding: 56px 24px; text-align: center; background: var(--surface, #fff); border: 1.5px solid var(--border, #e8e0cc); border-radius: var(--radius-lg, 20px); }
  .empty-state-miq .fa { font-size: 2.5rem; color: var(--border, #e8e0cc); display: block; margin-bottom: 12px; }
  .empty-state-miq p { font-size: 0.88rem; color: var(--text-3, #9c8f7a); font-weight: 500; margin: 0; }
</style>

<?php if (!empty($miqaats) && is_array($miqaats)): ?>
<div class="miqaat-grid" id="miqaat-cards">
  <?php foreach ($miqaats as $miqaat):
    $isActive  = $miqaat['status'] == '1';
    $hasRsvp   = isset($rsvp_overview[$miqaat['id']]) && !empty($rsvp_overview[$miqaat['id']]);
    $rsvpIds   = $hasRsvp ? array_column($rsvp_overview[$miqaat['id']], 'miqaat_id') : [];
    $confirmed = in_array($miqaat['id'], $rsvpIds);
    $canRsvp   = isset($miqaat['raza_status']) && $miqaat['raza_status'] == 1;
  ?>
  <div class="miq-card <?= $isActive ? 'active-card' : 'inactive-card' ?>
       miqaat-item"
       data-name="<?= htmlspecialchars($miqaat['name'],    ENT_QUOTES) ?>"
       data-type="<?= htmlspecialchars($miqaat['type'],    ENT_QUOTES) ?>"
       data-date="<?= date('d M Y', strtotime($miqaat['date'])) ?>"
       data-hijri="<?= htmlspecialchars($miqaat['hijri_date'], ENT_QUOTES) ?>"
       data-status="<?= htmlspecialchars($miqaat['status'], ENT_QUOTES) ?>">

    <!-- header -->
    <div class="miq-card-header">
      <div>
        <h5 class="miq-name"><?= htmlspecialchars($miqaat['name']) ?></h5>
        <div class="miq-type-tag"><?= htmlspecialchars($miqaat['type']) ?></div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
        <span class="status-pill <?= $isActive ? 'pill-active' : 'pill-inactive' ?>">
          <i class="fa <?= $isActive ? 'fa-circle' : 'fa-minus-circle' ?>" style="font-size:7px;"></i>
          <?= $isActive ? 'Active' : 'Inactive' ?>
        </span>
        <span class="status-pill <?= $confirmed ? 'pill-confirmed' : 'pill-pending' ?>">
          <i class="fa <?= $confirmed ? 'fa-check' : 'fa-clock-o' ?>"></i>
          <?= $confirmed ? 'Confirmed' : 'Pending' ?>
        </span>
      </div>
    </div>

    <!-- body -->
    <div class="miq-card-body">
      <div class="miq-info-row">
        <span class="miq-info-label">Date</span>
        <span class="miq-info-value"><?= date('d M Y', strtotime($miqaat['date'])) ?></span>
      </div>
      <div class="miq-info-row">
        <span class="miq-info-label">Hijri</span>
        <span class="miq-info-value"><?= htmlspecialchars($miqaat['hijri_date']) ?></span>
      </div>
    </div>

    <!-- footer -->
    <div class="miq-card-footer">
      <?php if ($canRsvp): ?>
        <a href="<?= base_url('accounts/general_rsvp/' . $miqaat['id']) ?>"
           class="btn-rsvp <?= $confirmed ? 'btn-rsvp-update' : '' ?>">
          <i class="fa <?= $confirmed ? 'fa-pencil' : 'fa-check' ?>"></i>
          <?= $confirmed ? 'Update RSVP' : 'RSVP Now' ?>
        </a>
      <?php else: ?>
        <span class="btn-rsvp-disabled"><i class="fa fa-lock"></i> RSVP</span>
      <?php endif; ?>
    </div>

  </div>
  <?php endforeach; ?>
</div>

<?php else: ?>
<div class="miqaat-grid" id="miqaat-cards">
  <div class="empty-state-miq">
    <i class="fa fa-calendar-times-o"></i>
    <p>No upcoming events to RSVP.</p>
  </div>
</div>
<?php endif; ?>