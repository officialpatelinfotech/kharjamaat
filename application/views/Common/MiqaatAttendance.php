<div class="container margintopcontainer pt-5">
  <div class="mb-4 mb-md-0">
    <a href="<?php echo $active_controller ? $active_controller : base_url(); ?>" class="btn btn-outline-secondary inline-block text-blue-600 hover:underline">
      <i class="fas fa-arrow-left"></i>
    </a>
  </div>
  <h3 class="heading text-center mb-4">Miqaat <span class="text-primary">Attendance</span></h3>
  <div class="row justify-content-center mb-4">
    <div class="col-12 col-md-8">
      <input id="miqaat-filter" type="search" class="form-control" placeholder="Search miqaat (name or date)...">
    </div>
  </div>
  <div class="row">
    <?php if (!empty($miqaats)) : ?>
      <?php
        // Ensure all miqaats are shown; sort by date descending (latest first)
        usort($miqaats, function($a, $b) {
          $da = isset($a['miqaat_date']) ? $a['miqaat_date'] : (isset($a['date']) ? $a['date'] : '0');
          $db = isset($b['miqaat_date']) ? $b['miqaat_date'] : (isset($b['date']) ? $b['date'] : '0');
          $ta = (int)strtotime($da);
          $tb = (int)strtotime($db);
          return $tb <=> $ta;
        });
      ?>
      <?php foreach ($miqaats as $miqaat) : ?>
        <div class="col-md-4 col-sm-6 mb-4 miqaat-card" data-name="<?php echo htmlspecialchars(strtolower($miqaat['miqaat_name'] ?? $miqaat['name'] ?? ''), ENT_QUOTES); ?>" data-date="<?php echo htmlspecialchars($miqaat['miqaat_date'] ?? $miqaat['date'] ?? '', ENT_QUOTES); ?>">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">
                <?php echo htmlspecialchars($miqaat['miqaat_name']); ?>
                <?php if (!empty($miqaat['miqaat_type']) || !empty($miqaat['type'])): ?>
                  <small class="text-muted ms-2"><?php echo htmlspecialchars(!empty($miqaat['miqaat_type']) ? $miqaat['miqaat_type'] : $miqaat['type']); ?></small>
                <?php endif; ?>
              </h5>
              <p class="card-text mb-2">
                <strong>Date:</strong> <?php echo date('d M Y', strtotime($miqaat['miqaat_date'])); ?>
              </p>
              <p class="card-text mb-2">
                <strong>Hijri Date:</strong>
                <?php
                if (!empty($miqaat['hijri_date']) && !empty($miqaat['hijri_month_name'])) {
                  $hijri_parts = explode('-', $miqaat['hijri_date']);
                  echo $hijri_parts[0] . ' ' . $miqaat['hijri_month_name'] . ' ' . (isset($hijri_parts[2]) ? $hijri_parts[2] : '');
                } else {
                  echo '-';
                }
                ?>
              </p>
              <p class="card-text mb-0">
                <strong>Attendees:</strong> <?php echo (isset($attendance_by_miqaat[$miqaat['miqaat_id']]) && $attendance_by_miqaat[$miqaat['miqaat_id']] > 0) ? "<span class='badge badge-success'>" . $attendance_by_miqaat[$miqaat['miqaat_id']] . "</span>" : "<span class='badge badge-danger'>0</span>"; ?>
              </p>
              <div class="mt-3">
                <a href="<?php echo base_url('common/miqaat_attendance_details/' . $miqaat['miqaat_id']); ?>" class="btn btn-primary btn-sm">View Details</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <div class="col-12">
        <div class="alert alert-info">No miqaats found.</div>
      </div>
    <?php endif; ?>
  </div>
</div>
<script>
  (function(){
    function parseDate(s){ if(!s) return null; var t=new Date(s); return isNaN(t.getTime())?null:t; }
    function applyFilter(){
      var q = (document.getElementById('miqaat-filter').value||'').toLowerCase().trim();
      document.querySelectorAll('.miqaat-card').forEach(function(card){
        var name = (card.getAttribute('data-name')||'').toLowerCase();
        var date = (card.getAttribute('data-date')||'').toLowerCase();
        var show = true;
        if(q){ if(name.indexOf(q)===-1 && date.indexOf(q)===-1) show=false; }
        card.style.display = show ? '' : 'none';
      });
    }
    var el = document.getElementById('miqaat-filter');
    if(el){ el.addEventListener('input', applyFilter); }
  })();
</script>
</div>