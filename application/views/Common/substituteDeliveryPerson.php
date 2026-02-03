<style>
  .form-legend {
    font-size: 16px;
  }

  #submit-sub-btn {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 20px;
    animation: pulse 1.2s infinite ease-in-out;
  }

  #submit-sub-btn:hover {
    animation-play-state: paused;
  }

  @keyframes pulse {
    0% {
      transform: translateX(-50%) scale(1);
    }

    50% {
      transform: translateX(-50%) scale(0.9);
    }

    100% {
      transform: translateX(-50%) scale(1);
    }
  }

  .hidden {
    display: none;
  }
  /* Controlled spacing for filters */
  #sub-filters { margin-top: 12px; margin-bottom: 16px; }
  @media (min-width: 768px){
    #sub-filters { margin-top: 16px; margin-bottom: 20px; }
  }

  /* Mobile responsiveness enhancements */
  @media (max-width: 767.98px) {
    #sub-filters .row > [class^="col-"] {
      margin-bottom: 10px;
    }
    #sub-filters .form-control,
    #sub-filters select.form-control {
      font-size: 14px;
      padding: 6px 8px;
    }
    #sub-filters label {
      font-size: 13px;
      margin-bottom: 2px;
    }
    #submit-sub-btn {
      left: 0 !important;
      right: 0 !important;
      width: 100%;
      transform: none !important;
      bottom: 0;
      border-radius: 0;
      padding: 14px 10px;
      z-index: 999;
    }
    .table thead th, .table tbody td {
      white-space: nowrap;
    }
    #substitute-table-wrapper {
      -webkit-overflow-scrolling: touch;
    }
    body.has-mobile-sticky-footer {
      padding-bottom: 70px; /* space for sticky submit button */
    }
  }

  @media (max-width: 400px) {
    #submit-sub-btn {
      font-size: 14px;
    }
  }
</style>
<div class="margintopcontainer mx-2 pt-5">
  <div class="col-12">
    <div class="col-12 col-md-6 p-0 float-left mb-4 mb-md-0">
      <a href="<?php echo base_url("common/deliverydashboard") ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="col-12 col-md-6 ml-auto p-0 float-left text-right mb-2 mb-md-0">
      <a href="<?php echo base_url("common/managedeliveryperson"); ?>" class="btn btn-secondary">Manage Delivery Person</a>
    </div>
  </div>
  <h4 class="heading text-center mb-2">Substitute Delivery Person</h4>
  <?php
    $unique_sectors = [];
    $unique_sub_sectors = [];
    if(isset($all_users)){
      foreach($all_users as $u){
        if(!empty($u['Sector'])) $unique_sectors[$u['Sector']] = true;
        if(!empty($u['Sub_Sector'])) $unique_sub_sectors[$u['Sub_Sector']] = true;
      }
    }
  ?>
  <div class="row col-12" id="sub-filters">
    <div class="col-12 col-md-4 mb-2">
      <input type="text" id="filter-member" class="form-control" placeholder="Filter name or ITS" autocomplete="off" />
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="filter-sector" class="form-control">
        <option value="">All Sectors</option>
        <?php foreach(array_keys($unique_sectors) as $sec): ?>
          <option value="<?php echo htmlspecialchars($sec, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sec); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="filter-sub-sector" class="form-control">
        <option value="">All Sub-Sectors</option>
        <?php foreach(array_keys($unique_sub_sectors) as $sub): ?>
          <option value="<?php echo htmlspecialchars($sub, ENT_QUOTES); ?>"><?php echo htmlspecialchars($sub); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-2 d-flex align-items-stretch" style="gap:6px;">
      <button type="button" id="apply-filters-btn" class="btn btn-primary btn-block"><i class="fa fa-filter"></i> Apply</button>
      <button type="button" id="clear-filters-btn" class="btn btn-outline-secondary btn-block"><i class="fa fa-times"></i> Clear</button>
    </div>
  </div>
  <div class="col-12">
    <form method="POST" action="<?php echo base_url("common/assigndeliveryperson"); ?>" id="assign-delivery-person">
      <legend class="form-legend text-center pt-2 pb-2 text-primary">From <?php echo $substitute_date[0] . " to " . $substitute_date[count($substitute_date) - 1] ?></legend>
      <input type="hidden" name="start_date" value="<?php echo $substitute_date[0]; ?>">
      <input type="hidden" name="end_date" value="<?php echo $substitute_date[count($substitute_date) - 1]; ?>">
      <div id="substitute-table-wrapper" class="table-responsive">
        <table id="substitute-table" class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th>Sr. No.</th>
            <th>Member Name</th>
            <th>Sector</th>
            <th>Sub-sector</th>
            <th>Reg. Delivery Person</th>
            <th>Sub. Delivery Person</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($all_users)) {
            foreach ($all_users as $key => $user) {
          ?>
              <tr>
                <td class="row-index" data-original-index="<?php echo $key; ?>"><?php echo $key + 1; ?></td>
                <td><?php echo htmlspecialchars($user["Full_Name"], ENT_QUOTES); ?> <span class="text-muted">(<?php echo htmlspecialchars($user['ITS_ID'] ?? '', ENT_QUOTES); ?>)</span></td>
                <td><?php echo $user["Sector"]; ?></td>
                <td><?php echo $user["Sub_Sector"]; ?></td>
                <td>
                  <?php echo $user["delivery_person_name"]; ?>
                </td>
                <td>
                  <select class="form-control sub-deliver-person" name="sub-data[]">
                    <option value="">-----</option>
                    <?php
                    foreach ($all_dp as $dp) {
                      if ($dp['id'] == $user["dp_id"]) continue;
                    ?>
                      <option value="<?php echo $user['ITS_ID'] . '|' . $dp['id']; ?>"><?php echo $dp["name"]; ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </td>
              </tr>
          <?php
            }
          }
          ?>
        </tbody>
        </table>
      </div>
      <button id="submit-sub-btn" class="btn btn-success hidden">Submit</button>
    </form>
  </div>
</div>
<script>
  // Add class to body for mobile bottom padding
  (function() {
    function adjustBodyPadding() {
      if (window.innerWidth < 768) {
        document.body.classList.add('has-mobile-sticky-footer');
      } else {
        document.body.classList.remove('has-mobile-sticky-footer');
      }
    }
    adjustBodyPadding();
    window.addEventListener('resize', adjustBodyPadding);
  })();
  function updateSelectCount() {
    const selectedCount = $('select').filter(function() {
      return $(this).val() !== ''; // Only count non-empty selections
    }).length;

    if (selectedCount) {
      $("#submit-sub-btn").removeClass("hidden");
    } else {
      $("#submit-sub-btn").addClass("hidden");
    }

    return selectedCount;
  }

  // Listen for changes on all selects
  $('select').on('change', updateSelectCount);

  $("#submit-sub-btn").on("click", function(e) {
    if (updateSelectCount() < 1) {
      alert("No substitutions were made.")
      e.preventDefault();
    } else {
      $result = confirm("Are sure you want to move ahead with the substitution?");
      if (!$result) {
        e.preventDefault();
      }
    }
  });

  // Filtering logic for substitute table
  (function(){
    const tbody = document.querySelector('#substitute-table tbody');
    if(!tbody) return;
    const memberInput = document.getElementById('filter-member');
    const sectorSelect = document.getElementById('filter-sector');
    const subSectorSelect = document.getElementById('filter-sub-sector');
    const applyBtn = document.getElementById('apply-filters-btn');
    const clearBtn = document.getElementById('clear-filters-btn');

    function applyFilters(){
      const nameVal = (memberInput.value||'').toLowerCase().trim();
      const sectorVal = sectorSelect.value;
      const subVal = subSectorSelect.value;
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.forEach(r => {
        const cells = r.querySelectorAll('td');
        if(cells.length < 6){ r.style.display=''; return; }
        const nameCell = cells[1].textContent.toLowerCase();
        const sectorCell = cells[2].textContent.trim();
        const subCell = cells[3].textContent.trim();
        let show = true;
        if(nameVal && !nameCell.includes(nameVal)) show = false;
        if(sectorVal && sectorCell !== sectorVal) show = false;
        if(subVal && subCell !== subVal) show = false;
        r.style.display = show ? '' : 'none';
      });
      renumberVisible();
    }
    function renumberVisible(){
      let i=1; Array.from(tbody.querySelectorAll('tr')).forEach(r=>{ if(r.style.display==='none') return; const idxCell=r.querySelector('.row-index'); if(idxCell) idxCell.textContent=i++; });
    }
    if(applyBtn) applyFilters();
    if(applyBtn) applyBtn.addEventListener('click', applyFilters);
    [memberInput, sectorSelect, subSectorSelect].forEach(el=>{ if(!el) return; el.addEventListener('keyup', e=>{ if(e.key==='Enter') applyFilters(); }); el.addEventListener('change', applyFilters); });
    if(clearBtn) clearBtn.addEventListener('click', ()=>{ memberInput.value=''; sectorSelect.value=''; subSectorSelect.value=''; applyFilters(); });
  })();
</script>