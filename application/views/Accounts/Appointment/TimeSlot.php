<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  :root {
    --gold:       #b8860b; --gold-light: #e6c84a; --gold-muted: #f5e9c0; --gold-deep: #8a6408;
    --bg:         #faf7f0; --surface: #ffffff; --surface-2: #f7f4ec; --border: #e8e0cc;
    --text-1:     #1a1610; --text-2: #5a5244; --text-3: #9c8f7a;
    --green:      #1a6645; --green-bg: #eaf4ee; --green-border: #bbf7d0;
    --red:        #b91c1c; --red-bg:   #fef2f2; --red-border:   #fecaca;
    --blue:       #1d4ed8; --blue-bg:  #eff6ff; --blue-border:  #bfdbfe;
    --amber:      #b45309; --amber-bg: #fffbeb; --amber-border: #fde68a;
    --radius-sm:  8px; --radius: 14px; --radius-lg: 20px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow:     0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 8px 32px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.05);
  }

  body { background: var(--bg); font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-1); }

  /* ── Page header ── */
  .page-header-wrap { display: flex; align-items: center; min-height: 44px; margin-bottom: 4px; }
  .btn-back-nav { flex-shrink: 0; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-size: 14px; text-decoration: none; box-shadow: var(--shadow-sm); transition: all .15s; }
  .btn-back-nav:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .page-heading { font-family: 'Literata', Georgia, serif; color: var(--gold); font-size: 1.45rem; font-weight: 600; letter-spacing: -.3px; margin: 0; flex: 1; text-align: center; }
  .page-sub { font-size: 0.7rem; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-3); text-align: center; margin-top: 2px; }
  .section-divider { border: none; border-top: 1px solid var(--border); margin: 14px 0 22px; }

  /* ── Date band with nav ── */
  .date-band {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
    padding: 16px 20px; margin-bottom: 26px;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    position: relative; overflow: hidden;
  }
  .date-band::before { content: ''; display: block; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
  .date-band-center  { text-align: center; flex: 1; }
  .date-band-label   { font-size: 0.62rem; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-3); margin-bottom: 5px; display: block; }
  /* Hijri = PRIMARY (large) */
  .date-band-hijri   { font-family: 'Literata', Georgia, serif; font-size: 1.25rem; font-weight: 700; color: var(--gold); display: block; line-height: 1.2; }
  /* Greg = secondary (small) */
  .date-band-greg    { font-size: 0.78rem; font-weight: 600; color: var(--text-3); margin-top: 4px; display: block; }

  .date-nav-btn {
    flex-shrink: 0; width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface-2); color: var(--text-2); font-size: 13px;
    text-decoration: none; transition: all .15s;
  }
  .date-nav-btn:hover { background: var(--gold-muted); border-color: var(--gold); color: var(--gold); text-decoration: none; }
  .date-nav-disabled {
    flex-shrink: 0; width: 36px; height: 36px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm); border: 1.5px solid var(--border);
    background: var(--surface-2); color: var(--border);
    font-size: 13px; cursor: not-allowed; opacity: .5;
  }

  /* ── Slots grid ── */
  .slots-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 32px; }
  @media (max-width: 768px) { .slots-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 480px) { .slots-grid { grid-template-columns: 1fr; gap: 10px; } }

  /* ── Slot card ── */
  .slot-card {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--radius); box-shadow: var(--shadow-sm);
    padding: 18px 14px; text-align: center;
    text-decoration: none; color: inherit; display: block;
    transition: box-shadow .18s, border-color .18s, transform .18s;
    position: relative; overflow: hidden;
  }
  .slot-card::before { content: ''; display: block; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); opacity: 0; transition: opacity .2s; }
  .slot-card:hover { box-shadow: var(--shadow-lg); border-color: var(--gold); transform: translateY(-3px); text-decoration: none; color: inherit; }
  .slot-card:hover::before { opacity: 1; }

  .slot-time { font-size: 0.95rem; font-weight: 800; color: var(--text-1); margin-bottom: 6px; line-height: 1.2; }
  .slot-avail { display: inline-flex; align-items: center; gap: 5px; font-size: 0.7rem; font-weight: 700; letter-spacing: .3px; background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); border-radius: 40px; padding: 3px 10px; margin-top: 4px; }
  .slot-avail .fa { font-size: 9px; }

  /* ── No slots state ── */
  .empty-slots { background: var(--red-bg); border: 1.5px solid var(--red-border); border-radius: var(--radius); padding: 24px; text-align: center; color: var(--red); font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }

  /* ── Purpose Modal ── */
  #purposeModal .modal-content {
    border: 1.5px solid var(--border) !important; border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-lg) !important; overflow: hidden;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  #purposeModal .modal-header { background: var(--surface-2); border-bottom: 1px solid var(--border); padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
  #purposeModal .modal-title { font-family: 'Literata', Georgia, serif; font-size: 1.1rem; font-weight: 600; color: var(--gold); margin: 0; }
  #purposeModal .close { width: 32px; height: 32px; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; font-size: 15px; cursor: pointer; color: var(--text-2); transition: all .15s; padding: 0; opacity: 1; text-shadow: none; }
  #purposeModal .close:hover { background: var(--red-bg); border-color: var(--red-border); color: var(--red); }
  #purposeModal .modal-body { background: var(--bg); padding: 20px 24px; }
  #purposeModal .modal-footer { background: var(--surface-2); border-top: 1px solid var(--border); padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px; }

  /* modal form labels */
  #purposeModal label { font-size: 0.7rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: var(--text-3); margin-bottom: 6px; display: block; }
  #purposeModal .form-group { margin-bottom: 16px; }

  /* modal form controls — standard 52px spec */
  #purposeModal .form-control {
    width: 100%; height: 52px; padding: 0 16px;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 15px; font-weight: 500; color: var(--text-1); line-height: 50px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: border-color .2s, box-shadow .2s;
    appearance: none; -webkit-appearance: none; box-sizing: border-box;
  }
  #purposeModal select.form-control {
    padding-right: 42px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%23b8860b' stroke-width='1.6' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
  }
  #purposeModal textarea.form-control { height: auto; line-height: 1.5; padding: 12px 16px; resize: vertical; min-height: 80px; }
  #purposeModal .form-control:focus { border-color: var(--gold); outline: none; box-shadow: 0 0 0 3px rgba(184,134,11,.12); }

  /* modal buttons */
  .btn-modal-cancel { padding: 9px 20px; border-radius: var(--radius-sm); border: 1.5px solid var(--border); background: var(--surface); color: var(--text-2); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s; }
  .btn-modal-cancel:hover { background: var(--surface-2); }
  .btn-modal-confirm { padding: 9px 22px; border-radius: var(--radius-sm); border: none; background: linear-gradient(135deg, var(--gold), var(--gold-deep)); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all .15s; box-shadow: 0 2px 8px rgba(184,134,11,.25); display: inline-flex; align-items: center; gap: 7px; }
  .btn-modal-confirm:hover { background: linear-gradient(135deg, var(--gold-deep), #6b4d06); }
  .btn-modal-confirm::before { content: '\f00c'; font-family: FontAwesome; font-size: 12px; }

  @media (max-width: 575px) {
    .page-heading { font-size: 1.2rem; }
    #purposeModal .modal-body, #purposeModal .modal-header, #purposeModal .modal-footer { padding: 14px 16px; }
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">

  <!-- ── Header ── -->
  <div class="page-header-wrap pt-5">
    <a href="<?php echo base_url('accounts/appointment') ?>" class="btn-back-nav"><i class="fa fa-arrow-left"></i></a>
    <h1 class="page-heading">Available Time Slots</h1>
    <div style="width:38px;flex-shrink:0;"></div>
  </div>
  <p class="page-sub">Anjuman-e-Saifee <?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></p>
  <hr class="section-divider">

  <?php
  $ci = get_instance();
  $ci->load->model('HijriCalendar');
  $hparts = $ci->HijriCalendar->get_hijri_parts_by_greg_date(date('Y-m-d', strtotime($date)));
  $hijri_label = '';
  if (!empty($hparts)) {
    $hijri_label = htmlspecialchars($hparts['hijri_day'] . ' ' . $hparts['hijri_month_name'] . ' ' . $hparts['hijri_year'], ENT_QUOTES);
  }
  // Use IST for today comparison
  $ist         = new DateTimeZone('Asia/Kolkata');
  $today_ist   = (new DateTime('now', $ist))->format('Y-m-d');
  $prev_date   = date('Y-m-d', strtotime($date . ' -1 day'));
  $next_date   = date('Y-m-d', strtotime($date . ' +1 day'));
  $prev_is_past = ($prev_date < $today_ist);  // disable prev if it would go before today
  ?>

  <!-- ── Date band with nav ── -->
  <div class="date-band">
    <?php if ($prev_is_past): ?>
      <span class="date-nav-btn date-nav-disabled" title="Cannot go to past dates">
        <i class="fa fa-chevron-left"></i>
      </span>
    <?php else: ?>
      <a href="<?= site_url('accounts/time_slots?date=' . $prev_date) ?>" class="date-nav-btn" title="Previous day">
        <i class="fa fa-chevron-left"></i>
      </a>
    <?php endif; ?>
    <div class="date-band-center">
      <span class="date-band-label">Select Date</span>
      <?php if ($hijri_label !== ''): ?>
        <span class="date-band-hijri"><?php echo $hijri_label; ?> H</span>
      <?php endif; ?>
      <span class="date-band-greg"><?php echo date('D, d M Y', strtotime($date)); ?></span>
    </div>
    <a href="<?= site_url('accounts/time_slots?date=' . $next_date) ?>" class="date-nav-btn" title="Next day">
      <i class="fa fa-chevron-right"></i>
    </a>
  </div>

  <!-- ── Time slots ── -->
  <?php if (is_array($time_slots) && !empty($time_slots['time_slots'])): ?>
    <div class="slots-grid">
      <?php foreach ($time_slots['time_slots'] as $slot):
        $fmt = date('h:i A', strtotime($slot->time)) . ' – ' . date('h:i A', strtotime($slot->time . ' +15 minutes'));
      ?>
        <a href="#" class="slot-card book-link"
           data-slot-id="<?= $slot->slot_id ?>"
           data-time="<?= $slot->time ?>">
          <div class="slot-time"><?= $fmt ?></div>
          <span class="slot-avail">
            <i class="fa fa-check-circle"></i>
            <?= (int)$slot->count ?> Available
          </span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="empty-slots">
      <i class="fa fa-calendar-times-o" style="font-size:1.2rem;"></i>
      No time slots available for this date.
    </div>
  <?php endif; ?>

</div>

<!-- ── Purpose Modal ── -->
<div class="modal fade" id="purposeModal" tabindex="-1" role="dialog" aria-labelledby="purposeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="purposeModalLabel">
          <i class="fa fa-calendar-check-o" style="margin-right:8px;color:var(--gold);font-size:.9rem;"></i>Book Appointment
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="purposeForm">
          <input type="hidden" id="modal_slot_id" name="slot_id" />
          <input type="hidden" id="modal_time"    name="time" />

          <div class="form-group">
            <label for="appointment_purpose">Purpose</label>
            <select class="form-control" id="appointment_purpose" name="purpose" required>
              <option value="">— Select purpose —</option>
              <option>Wajebaat Takhmeen</option>
              <option>Miqaat Raza</option>
              <option>Kaaraj Raza</option>
              <option>Safai Chitthi</option>
              <option>Transfer Out</option>
              <option>Transfer In (New Sabeel Registration)</option>
              <option>Nikah Raza</option>
              <option>Misaaq Raza</option>
              <option>Taweez</option>
              <option>Talaq Qaziyah</option>
              <option>Wirasat Qaziyah</option>
              <option>Araz to Maula tus</option>
              <option>Others</option>
            </select>
          </div>

          <div class="form-group" id="otherTextGroup" style="display:none;">
            <label for="other_text">Please specify <span style="color:var(--red);">*</span></label>
            <input type="text" class="form-control" id="other_text" name="other_text" placeholder="Describe your purpose…" />
          </div>

          <div class="form-group" id="otherDetailsGroup">
            <label for="other_details">Additional Details <span style="color:var(--text-3);font-weight:400;">(optional)</span></label>
            <textarea class="form-control" id="other_details" name="other_details" rows="3" placeholder="Any extra details…"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" data-dismiss="modal">Cancel</button>
        <button type="button" id="purposeConfirmBtn" class="btn-modal-confirm">Confirm Booking</button>
      </div>
    </div>
  </div>
</div>

<!-- ══ ALL JS IDENTICAL TO ORIGINAL ══ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  var bookLinks = document.querySelectorAll('.book-link');

  bookLinks.forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();
      var slotId = this.getAttribute('data-slot-id');
      var time   = this.getAttribute('data-time');

      document.getElementById('modal_slot_id').value = slotId;
      document.getElementById('modal_time').value    = time;
      document.getElementById('appointment_purpose').value = '';
      document.getElementById('other_text').value   = '';
      document.getElementById('other_details').value = '';
      document.getElementById('otherTextGroup').style.display = 'none';

      if (window.jQuery && jQuery.fn.modal) {
        jQuery('#purposeModal').modal('show');
      } else {
        if (confirm('Do you want to book this time slot?')) {
          window.location.href = "<?= site_url('accounts/book_slot') ?>?slot_id=" + slotId + "&time=" + time;
        }
      }
    });
  });

  var purposeSelect = document.getElementById('appointment_purpose');
  var otherGroup    = document.getElementById('otherTextGroup');
  var otherInput    = document.getElementById('other_text');

  purposeSelect.addEventListener('change', function () {
    if (this.value === 'Others') {
      otherGroup.style.display = '';
      otherInput.setAttribute('required', 'required');
    } else {
      otherGroup.style.display = 'none';
      otherInput.removeAttribute('required');
    }
  });

  document.getElementById('purposeConfirmBtn').addEventListener('click', function () {
    var slotId  = encodeURIComponent(document.getElementById('modal_slot_id').value);
    var time    = encodeURIComponent(document.getElementById('modal_time').value);
    var purpose = document.getElementById('appointment_purpose').value;
    var details = document.getElementById('other_details').value.trim();

    if (!purpose) { alert('Please select a purpose.'); return; }

    if (purpose === 'Others') {
      var other = document.getElementById('other_text').value.trim();
      if (!other) { alert('Please specify the purpose for "Others".'); return; }
      purpose = other;
    }

    window.location.href = "<?= site_url('accounts/book_slot') ?>?slot_id=" + slotId
      + "&time=" + time
      + "&purpose=" + encodeURIComponent(purpose)
      + "&details=" + encodeURIComponent(details);
  });
});
</script>