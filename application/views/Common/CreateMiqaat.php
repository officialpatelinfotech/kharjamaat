<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-hijri@3.0.0/moment-hijri.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
  .ui-autocomplete {
    position: relative;
  }

  .autocomplete-display {
    position: relative;
  }

  #miqaat-form-container {
    width: 50%;
    margin: 0 auto;
  }

  @media (max-width: 768px) {
    #miqaat-form-container {
      width: 100%;
      padding: 0 1rem;
    }
  }

  /* Hijri calendar styles */
  #hijri-calendar .hijri-day.active {
    background: #0d6efd;
    color: #fff;
  }

  #hijri-calendar .hijri-day {
    width: 34px;
    padding: 4px 0;
  }

  #hijri-calendar .hijri-week-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
  }

  #hijri-calendar .hijri-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 4px;
  }

  #hijri-calendar .hijri-head {
    margin-bottom: 2px;
  }

  #hijri-calendar .hijri-cell {
    min-height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #hijri-calendar .hijri-cell.empty {
    background: transparent;
  }

  #hijri-calendar .hijri-head-cell {
    font-size: 0.75rem;
    text-transform: uppercase;
  }

  .hijri-cal-grid {
    min-height: 60px;
  }

  .hijri-grid {
    grid-template-columns: repeat(auto-fill, minmax(38px, 1fr));
    gap: 4px;
  }

  .hijri-week-grid {
    display: flex;
    flex-direction: column;
  }

  .hijri-row {
    display: flex;
    width: 100%;
  }

  .hijri-cell {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    position: relative;
  }

  .hijri-head {
    background-color: #f8f9fa;
    font-weight: bold;
  }

  .hijri-head-cell {
    position: relative;
  }

  .empty {
    background-color: #f1f3f5;
  }
</style>
<div class="container margintopcontainer pt-5">
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
      <?php echo $this->session->flashdata('error'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
      <?php echo $this->session->flashdata('success'); ?>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('warning')): ?>
    <div class="alert alert-warning">
      <?php echo $this->session->flashdata('warning'); ?>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?php echo base_url("common/managemiqaat"); ?>" class="btn btn-outline-secondary">
      <i class="fa-solid fa-arrow-left"></i>
    </a>
  </div>

  <div class="mb-4">
    <h4 class="heading text-center mb-4"><?php echo isset($edit_mode) && $edit_mode ? 'Edit Miqaat' : 'Create Miqaat'; ?></h4>
    <div id="miqaat-form-container" class="col-12 border rounded">
      <form method="POST" action="<?php echo base_url(isset($edit_mode) && $edit_mode ? 'common/update_miqaat' : 'common/add_miqaat'); ?>">
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="date">Date:</label>
            <input type="text" id="date" name="date" class="form-control mb-3" required value="<?php echo isset($edit_mode) && $edit_mode && isset($miqaat['date']) ? date('d-m-Y', strtotime($miqaat['date'])) : (isset($date) ? date('d-m-Y', strtotime($date)) : ''); ?>" placeholder="Please select a date">
            <p id="hijri-date-display" class="form-text text-muted">Hijri Date: <?php echo isset($hijri_date) ? $hijri_date : ''; ?></p>
          </div>

          <div class="form-group mb-3 border rounded p-2 bg-light" id="hijri-selector-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="fw-bold m-0">Select Hijri Date</label>
              <div>
                <select id="hijri-year-select" class="form-control form-select form-select-sm d-inline-block w-auto me-2" aria-label="Hijri Year" style="min-width:90px"></select>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <button type="button" id="hijri-prev" class="btn btn-sm btn-outline-secondary">«</button>
              <span id="hijri-current" class="mx-2 fw-semibold small"></span>
              <button type="button" id="hijri-next" class="btn btn-sm btn-outline-secondary">»</button>
            </div>
            <div id="hijri-calendar" class="hijri-cal-grid mb-2"></div>
            <small class="text-muted d-block">Click a Hijri day to auto-fill the Gregorian date above.</small>
          </div>

          <div class="form-group mb-3">
            <label for="miqaat-name">Miqaat Name:</label>
            <input type="text" class="form-control" name="name" id="miqaat-name" required value="<?php echo isset($edit_mode) && $edit_mode && isset($miqaat['name']) ? htmlspecialchars($miqaat['name'], ENT_QUOTES) : ''; ?>">
          </div>

          <div class="form-group mb-3">
            <label for="miqaat-type">Miqaat Type:</label>
            <select name="miqaat_type" id="miqaat-type" class="form-control" required>
              <option value="">-- Select Miqaat Type --</option>
              <option value="General" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['type']) && $miqaat['type'] == 'General') ? 'selected' : ''; ?>>General</option>
              <option value="Ashara" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['type']) && $miqaat['type'] == 'Ashara') ? 'selected' : ''; ?>>Ashara</option>
              <option value="Ladies" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['type']) && $miqaat['type'] == 'Ladies') ? 'selected' : ''; ?>>Ladies</option>
              <option value="Shehrullah" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['type']) && $miqaat['type'] == 'Shehrullah') ? 'selected' : ''; ?>>Shehrullah</option>
            </select>
          </div>

          <?php if (isset($edit_mode) && $edit_mode): ?>
            <?php if (isset($miqaat['assignments']) && !empty($miqaat['assignments'])): ?>
              <div class="mb-3">
                <h6>Current Assignments:</h6>
                <ul class="list-group">
                  <?php foreach ($miqaat['assignments'] as $assignment): ?>
                    <?php if ($assignment['assign_type'] === 'Individual'): ?>
                      <li class="list-group-item">
                        <strong>Individual:</strong> <?php echo htmlspecialchars($assignment['member_name'] ?? '', ENT_QUOTES); ?> (ID: <?php echo $assignment['member_id'] ?? ''; ?>)
                      </li>
                    <?php elseif ($assignment['assign_type'] === 'Group'): ?>
                      <li class="list-group-item">
                        <strong>Group:</strong> <?php echo htmlspecialchars($assignment['group_name'] ?? '', ENT_QUOTES); ?>
                        <?php if (!empty($assignment['group_leader_name'])): ?>
                          <br><strong>Leader:</strong> <?php echo htmlspecialchars($assignment['group_leader_name'], ENT_QUOTES); ?> (ID: <?php echo htmlspecialchars($assignment['group_leader_id'], ENT_QUOTES); ?>)
                        <?php endif; ?>
                        <br><strong>Co-leader:</strong>
                        <?php if (!empty($assignment['members'])):
                          $group_co_leader = $assignment['members'][0];
                        ?>
                          <?php echo htmlspecialchars($group_co_leader['name'] ?? $group_co_leader['first_name'], ENT_QUOTES); ?> (ID: <?php echo $group_co_leader['id']; ?>)
                        <?php else: ?>
                          <em>No co-leader assigned</em>
                        <?php endif; ?>
                      </li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php elseif (isset($miqaat['assigned_to']) && $miqaat['assigned_to'] === 'Fala ni Niyaz'): ?>
              <div class="mb-3">
                <h6>Current Assignments:</h6>
                <span class="badge bg-success text-white">Fala ni Niyaz</span>
              </div>
            <?php endif; ?>
            <button type="button" id="edit-assignments-btn" class="btn btn-sm btn-outline-primary mb-3">Edit Assignment</button>
          <?php endif; ?>
          <div id="assign-to-container" class="form-group mb-3"
            <?php echo isset($edit_mode) && $edit_mode ? 'style="display:none;"' : ''; ?>>
            <label for="assign-to">Assign To:</label>
            <select name="assign_to" id="assign-to" class="form-control">
              <option value="">-- Select Assign To --</option>
              <option value="Individual">Individual</option>
              <option value="Group">Sanstha / Group</option>
              <option value="Fala ni Niyaz">Fala ni Niyaz</option>
            </select>
          </div>

          <div id="individual-container" class="d-none">
            <div class="form-group mb-3">
              <label for="individuals">Select Individuals:</label>
              <input type="text" id="individuals" class="form-control" placeholder="Search & select individuals">
              <div class="autocomplete-display acod-1"></div>
              <input type="hidden" name="individual_ids" id="individual-ids"
                value="<?php
                        if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                          $ids = [];
                          foreach ($miqaat['assignments'] as $assignment) {
                            if ($assignment['assign_type'] === 'Individual') {
                              $ids[] = $assignment['member_id'];
                            }
                          }
                          echo implode(',', $ids);
                        }
                        ?>">
              <small class="text-muted">Start typing name to search</small>
              <div id="selected-individuals" class="mt-2">
                <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])): ?>
                  <?php foreach ($miqaat['assignments'] as $assignment): ?>
                    <?php if ($assignment['assign_type'] === 'Individual'): ?>
                      <span class="badge bg-primary text-white me-2 mr-2">
                        <?php echo isset($assignment['member_name']) ? htmlspecialchars($assignment['member_name'], ENT_QUOTES) : ''; ?>
                        <a href="#" class="text-white ms-1 remove-individual" data-id="<?php echo $assignment['member_id']; ?>">×</a>
                      </span>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div id="group-container" class="d-none">
            <div class="form-group mb-3">
              <label for="group-name">Sanstha / Group Name:</label>
              <input type="text" name="group_name" id="group-name" class="form-control" placeholder="Enter Sanstha / Group Name" value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                                                                                                                                          foreach ($miqaat['assignments'] as $assignment) {
                                                                                                                                            if ($assignment['assign_type'] === 'Group') echo htmlspecialchars($assignment['group_name'], ENT_QUOTES);
                                                                                                                                          }
                                                                                                                                        } ?>">
            </div>

            <div class="form-group mb-3">
              <label for="group-leader">Select Sanstha / Group Leader:</label>
              <input type="text" id="group-leader" class="form-control" placeholder="Search & select leader">
              <div class="autocomplete-display acod-2"></div>
              <input type="hidden" name="group_leader_id" id="group-leader-id" value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                                                                                        foreach ($miqaat['assignments'] as $assignment) {
                                                                                          if ($assignment['assign_type'] === 'Group') echo $assignment['group_leader_id'];
                                                                                        }
                                                                                      } ?>">
              <div id="selected-group-leader" class="mt-2">
                <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])): ?>
                  <?php foreach ($miqaat['assignments'] as $assignment): ?>
                    <?php if ($assignment['assign_type'] === 'Group' && !empty($assignment['group_leader_name'])): ?>
                      <span class="badge bg-success text-white me-2 mr-2">
                        <?php echo htmlspecialchars($assignment['group_leader_name'], ENT_QUOTES); ?>
                        <a href="#" class="text-white ms-1 remove-group-leader" data-id="<?php echo $assignment['group_leader_id']; ?>">×</a>
                      </span>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="group-members">Select Sanstha / Group Co-leader:</label>
              <input type="text" id="group-members" class="form-control" placeholder="Search & add co-leader">
              <div class="autocomplete-display acod-3"></div>
              <input type="hidden" name="group_member_ids" id="group-member-ids" value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                                                                                          foreach ($miqaat['assignments'] as $assignment) {
                                                                                            if ($assignment['assign_type'] === 'Group' && !empty($assignment['members'])) {
                                                                                              $ids = array_map(function ($m) {
                                                                                                return $m['id'];
                                                                                              }, $assignment['members']);
                                                                                              echo implode(',', $ids);
                                                                                            }
                                                                                          }
                                                                                        } ?>">
              <small class="text-muted">Co-leader can be assigned</small>
              <div id="selected-group-members" class="mt-2">
                <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])): ?>
                  <?php foreach ($miqaat['assignments'] as $assignment): ?>
                    <?php if ($assignment['assign_type'] === 'Group' && !empty($assignment['members'])): ?>
                      <?php foreach ($assignment['members'] as $member): ?>
                        <span class="badge bg-info text-white me-2 mr-2">
                          <?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>
                          <a href="#" class="text-white ms-1 remove-group-member" data-id="<?php echo $member['id']; ?>">×</a>
                        </span>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div id="fala-ni-niyaz-container" class="d-none">
            <p class="text-success">This Miqaat will be automatically assigned to **Umoor FMB** role members. A Raza will also be created automatically on save.</p>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" id="save-miqaat-btn" class="btn btn-success"><?php echo isset($edit_mode) && $edit_mode ? 'Update Miqaat' : 'Save Miqaat'; ?></button>
        </div>
        <?php if (isset($edit_mode) && $edit_mode): ?>
          <input type="hidden" name="miqaat_id" value="<?php echo isset($miqaat_id) ? $miqaat_id : (isset($miqaat['id']) ? $miqaat['id'] : ''); ?>">
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Show assign-to input when edit button is clicked
    var editBtn = document.getElementById('edit-assignments-btn');
    var assignToContainer = document.getElementById('assign-to-container');
    if (editBtn && assignToContainer) {
      editBtn.addEventListener('click', function() {
        assignToContainer.style.display = 'block';

        const assignToSelect = document.getElementById("assign-to");
        assignToSelect.value = "<?php echo isset($miqaat['assignments'][0]['assign_type']) ? $miqaat['assignments'][0]['assign_type'] : ''; ?>";

        const event = new Event('change', {
          bubbles: true
        });
        assignToSelect.dispatchEvent(event);
      });
    }

    // Make group leader required if Group is selected
    // var assignToSelect = document.getElementById('assign-to');
    // var groupLeaderInput = document.getElementById('group-leader');
    // var groupContainer = document.getElementById('group-container');

    // --- Date picker and hijri logic from CreateMenu.php ---
    let miqaatDates = [];
    // If you have a PHP array of miqaat dates, convert to JS array here
    <?php if (isset($miqaat_dates)) :
      $miqaat_dates_arr = [];
      foreach ($miqaat_dates as $miqaat_date) {
        $miqaat_dates_arr[] = date("d-m-Y", strtotime($miqaat_date["date"]));
      }
    ?>
      miqaatDates = <?php echo json_encode($miqaat_dates_arr); ?>;
    <?php endif; ?>

    flatpickr("#date", {
      dateFormat: "d-m-Y",
      disable: miqaatDates,
    });

    const assignSelect = document.getElementById("assign-to");
    const individualContainer = document.getElementById("individual-container");
    const groupContainer = document.getElementById("group-container");
    const falaContainer = document.getElementById("fala-ni-niyaz-container");

    assignSelect.addEventListener("change", function() {
      individualContainer.classList.add("d-none");
      groupContainer.classList.add("d-none");
      falaContainer.classList.add("d-none");

      var groupLeaderInput = document.getElementById('group-leader');
      var individualInput = document.getElementById('individuals');
      var individualIdsInput = document.getElementById('individual-ids');

      if (this.value === "Individual") {
        individualContainer.classList.remove("d-none");
        if (groupLeaderInput) groupLeaderInput.removeAttribute('required');
      } else if (this.value === "Group") {
        groupContainer.classList.remove("d-none");
        if (individualInput) individualInput.removeAttribute('required');
      } else if (this.value === "Fala ni Niyaz" || this.value === "Fala_ni_Niyaz") {
        falaContainer.classList.remove("d-none");
        if (groupLeaderInput) groupLeaderInput.removeAttribute('required');
        if (individualInput) individualInput.removeAttribute('required');
      } else {
        if (groupLeaderInput) groupLeaderInput.removeAttribute('required');
        if (individualInput) individualInput.removeAttribute('required');
      }
    });

    // Also update required attribute when individuals are selected/deselected
    if (document.getElementById('individual-ids')) {
      document.getElementById('individual-ids').addEventListener('change', function() {
        var individualInput = document.getElementById('individuals');
        var idsArr = this.value ? this.value.split(',').filter(Boolean) : [];
        if (idsArr.length > 0) {
          if (individualInput) individualInput.removeAttribute('required');
        } else {
          if (individualInput && assignSelect.value === "Individual") individualInput.setAttribute('required', 'required');
        }
      });
    }

    function setupAutocomplete(inputId, appendContainer, selectedContainer, hiddenInputId, multiple = false) {

      const input = document.getElementById(inputId);
      const hiddenInput = document.getElementById(hiddenInputId);

      $(input).autocomplete({
        appendTo: appendContainer,
        source: function(request, response) {
          $.ajax({
            url: "<?php echo base_url('common/search_users'); ?>",
            dataType: "json",
            data: {
              term: request.term
            },
            success: function(data) {
              response($.map(data, function(item) {
                return {
                  label: item.name,
                  value: item.name,
                  id: item.id
                };
              }));
            }
          });
        },
        select: function(event, ui) {
          let ids = hiddenInput.value ? hiddenInput.value.split(",") : [];

          if (ids.length > 0 && !multiple) {
            alert("Only one selection is allowed! To replace, unselect the current one.");
            input.value = "";
            return false;
          }
          if (!ids.includes(ui.item.id.toString())) {
            ids.push(ui.item.id);
            hiddenInput.value = ids.join(",");

            let chip = document.createElement("span");
            chip.className = "badge bg-primary text-white me-2 mr-2 mb-2";
            chip.innerHTML = ui.item.label +
              ' <a href="#" class="text-white ms-1 remove-individual" data-id="' + ui.item.id + '">×</a>';
            document.getElementById(selectedContainer).appendChild(chip);
          }

          input.value = "";
          return false;
        }
      });

      $(document).on("click", ".remove-individual", function(e) {
        e.preventDefault();
        let id = $(this).data("id").toString();

        let ids = hiddenInput.value ? hiddenInput.value.split(",") : [];
        ids = ids.filter(i => i !== id);
        hiddenInput.value = ids.join(",");

        $(this).parent().remove();
      });

      // Remove group leader
      $(document).on("click", ".remove-group-leader", function(e) {
        e.preventDefault();
        // Clear group leader input and hidden field
        $('#group-leader').val("");
        $('#group-leader-id').val("");
        $(this).parent().remove();
      });

      // Remove group member
      $(document).on("click", ".remove-group-member", function(e) {
        e.preventDefault();
        let id = $(this).data("id").toString();
        let ids = $('#group-member-ids').val() ? $('#group-member-ids').val().split(",") : [];
        ids = ids.filter(i => i !== id);
        $('#group-member-ids').val(ids.join(","));
        $(this).parent().remove();
      });
    }

    setupAutocomplete("individuals", ".acod-1", "selected-individuals", "individual-ids", true);
    setupAutocomplete("group-leader", ".acod-2", "selected-group-leader", "group-leader-id", false);
    setupAutocomplete("group-members", ".acod-3", "selected-group-members", "group-member-ids", false);

    // Edit modal logic
    const editAssignSelect = document.getElementById("edit-assign-to");
    const editIndividualContainer = document.getElementById("edit-individual-container");
    const editGroupContainer = document.getElementById("edit-group-container");
    const editFalaContainer = document.getElementById("edit-fala-ni-niyaz-container");

    if (editAssignSelect) {
      editAssignSelect.addEventListener("change", function() {
        editIndividualContainer.classList.add("d-none");
        editGroupContainer.classList.add("d-none");
        editFalaContainer.classList.add("d-none");
        if (this.value === "Individual") {
          editIndividualContainer.classList.remove("d-none");
        } else if (this.value === "Group") {
          editGroupContainer.classList.remove("d-none");
        } else if (this.value === "Fala ni Niyaz") {
          editFalaContainer.classList.remove("d-none");
        }
      });
    }

    setupAutocomplete("edit-individuals", ".acod-4", "edit-selected-individuals", "edit-individual-ids", true);
    setupAutocomplete("edit-group-leader", ".acod-5", "edit-selected-group-leader", "edit-group-leader-id", false);
    setupAutocomplete("edit-group-members", ".acod-6", "edit-selected-group-members", "edit-group-member-ids", true);

    $(document).on("click", ".edit-miqaat-btn", function(e) {
      e.preventDefault();
      // Clear previous selections
      $("#edit-selected-individuals").empty();
      $("#edit-selected-group-leader").empty();
      $("#edit-selected-group-members").empty();

      // Fill modal fields
      $("#edit-miqaat-id").val($(this).data("id"));
      $("#edit-miqaat-name").val($(this).data("name"));
      $("#edit-miqaat-type").val($(this).data("type"));
      let assignType = $(this).data("assign");
      $("#edit-assign-to").val(assignType);
      editAssignSelect.dispatchEvent(new Event('change'));

      // Hide all containers first
      editIndividualContainer.classList.add("d-none");
      editGroupContainer.classList.add("d-none");
      editFalaContainer.classList.add("d-none");

      // Individuals
      if (assignType === "Individual") {
        editIndividualContainer.classList.remove("d-none");
        let individualIds = $(this).data("individualids") || "";
        $("#edit-individual-ids").val(individualIds);
        let names = [];
        try {
          let assignments = JSON.parse($(this).data("assigned"));
          assignments.forEach(function(a) {
            if (a.assign_type === "Individual") {
              names.push({
                id: a.member_id,
                name: a.member_name
              });
            }
          });
        } catch (err) {}
        // Show all selected individuals as chips
        names.forEach(function(n) {
          let chip = document.createElement("span");
          chip.className = "badge bg-primary text-white me-2 mr-2";
          chip.innerHTML = n.name + ' <a href="#" class="text-white ms-1 remove-individual" data-id="' + n.id + '">×</a>';
          document.getElementById("edit-selected-individuals").appendChild(chip);
        });
      }

      // Group
      if (assignType === "Group") {
        editGroupContainer.classList.remove("d-none");
        $("#edit-group-name").val($(this).data("groupname"));
        $("#edit-group-leader-id").val($(this).data("groupleaderid"));
        let leaderName = $(this).data("groupleadername");
        if (leaderName) {
          let chip = document.createElement("span");
          chip.className = "badge bg-primary text-white me-2 mr-2";
          chip.innerHTML = leaderName + ' <a href="#" class="text-white ms-1 remove-individual" data-id="' + $(this).data("groupleaderid") + '">×</a>';
          document.getElementById("edit-selected-group-leader").appendChild(chip);
        }
        let groupMemberIds = $(this).data("groupmemberids") || "";
        $("#edit-group-member-ids").val(groupMemberIds);
        let memberNames = [];
        try {
          let assignments = JSON.parse($(this).data("assigned"));
          assignments.forEach(function(a) {
            if (a.assign_type === "Group" && a.members) {
              a.members.forEach(function(m) {
                memberNames.push({
                  id: m.id,
                  name: m.name
                });
              });
            }
          });
        } catch (err) {}
        // Show all selected group members as chips
        memberNames.forEach(function(n) {
          let chip = document.createElement("span");
          chip.className = "badge bg-primary text-white me-2 mr-2";
          chip.innerHTML = n.name + ' <a href="#" class="text-white ms-1 remove-individual" data-id="' + n.id + '">×</a>';
          document.getElementById("edit-selected-group-members").appendChild(chip);
        });
      }

      // Fala ni Niyaz
      if (assignType === "Fala ni Niyaz" || assignType === "Fala_ni_Niyaz") {
        editFalaContainer.classList.remove("d-none");
        // Optionally, you can show a summary message or details here if needed
      }

      // Show modal
      $("#editMiqaatModal").modal("show");
    });
  });

  $(document).ready(function() {
    $('#date').on('change', function() {
      var gregDate = $(this).val();
      // Convert to Y-m-d format if needed
      var parts = gregDate.split('-');
      if (parts.length === 3) {
        // If input is d-m-Y, convert to Y-m-d
        gregDate = parts[2] + '-' + parts[1] + '-' + parts[0];
      }
      $.ajax({
        url: '<?php echo base_url("common/get_hijri_date_ajax"); ?>',
        method: 'POST',
        data: {
          greg_date: gregDate
        },
        success: function(response) {
          const data = JSON.parse(response);
          if (data.status === 'success') {
            const hijriDate = data.hijri_date;
            $('#hijri-date-display').html(`Hijri Date: ${hijriDate}`);
          } else {
            $('#hijri-date-display').html(''); // Clear if error
          }
        }
      });
    });
  });

  $(document).ready(function() {
    $("#save-miqaat-btn").on("click", function() {
      if ($("#assign-to").val() === "Individual") {
        if ($("#individual-ids").val().trim() === "") {
          alert("Please select at least one individual.");
          return false;
        }
      }
      if ($("#assign-to").val() === "Group") {
        if ($("#group-leader-id").val().trim() === "") {
          alert("Please select a group leader.");
          return false;
        }
      }
    });
  });

  $(".alert").delay(5000).slideUp(300);
</script>
<!-- Enhanced Hijri mapping script -->
<script>
  (function() {
    const dateInput = document.getElementById('date');
    const hijriEl = document.getElementById('hijri-date-display');
    if (!dateInput || !hijriEl) return;

    let lastRequested = null;
    let fpInstance = null;

    function toISO(dmy) {
      if (!dmy) return '';
      const parts = dmy.split('-');
      if (parts.length !== 3) return '';
      // Expecting d-m-Y -> Y-m-d
      return parts[2] + "-" + parts[1] + "-" + parts[0];
    }

    function setStatus(text, klass) {
      hijriEl.textContent = text;
      if (klass) {
        hijriEl.classList.remove('text-danger', 'text-muted');
        hijriEl.classList.add(klass);
      }
    }

    function fetchHijriIfNeeded(dmy) {
      if (!dmy) {
        setStatus('Hijri Date:', 'text-muted');
        return;
      }
      const iso = toISO(dmy);
      if (!iso) {
        setStatus('Hijri Date:', 'text-muted');
        return;
      }
      if (iso === lastRequested) return; // prevent duplicate
      lastRequested = iso;
      setStatus('Hijri Date: Loading...', 'text-muted');
      fetch('<?php echo base_url("common/get_hijri_date_ajax"); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: new URLSearchParams({
          greg_date: iso
        })
      }).then(r => r.json()).then(data => {
        if (data && data.status === 'success' && data.hijri_date) {
          hijriEl.textContent = 'Hijri Date: ' + data.hijri_date;
          hijriEl.classList.remove('text-danger');
          hijriEl.classList.add('text-muted');
        } else {
          setStatus('Hijri Date: Not found', 'text-danger');
        }
      }).catch(() => {
        setStatus('Hijri Date: Error fetching', 'text-danger');
      });
    }

    // Initialize / extend flatpickr if not already initialised by earlier script
    if (typeof flatpickr !== 'undefined') {
      // Reconfigure with onChange to ensure callback
      fpInstance = flatpickr('#date', {
        dateFormat: 'd-m-Y',
        disable: (window.miqaatDates || []),
        onChange: function(selectedDates, dateStr) {
          fetchHijriIfNeeded(dateStr);
        }
      });
    }

    // Initial fetch if value pre-filled
    if (dateInput.value) {
      fetchHijriIfNeeded(dateInput.value);
    }

    // Fallback listener if user types manually
    dateInput.addEventListener('change', function() {
      fetchHijriIfNeeded(this.value);
    });
  })();
</script>

<script>
  // Hijri calendar selector logic
  (function() {
    const calContainer = document.getElementById('hijri-calendar');
    const currentLbl = document.getElementById('hijri-current');
    const prevBtn = document.getElementById('hijri-prev');
    const nextBtn = document.getElementById('hijri-next');
    const yearSelect = document.getElementById('hijri-year-select');
    const gregInput = document.getElementById('date');
    const hijriDisplay = document.getElementById('hijri-date-display');
    if (!calContainer || !gregInput) return;

    let monthsCache = {}; // {year: [{id,name}]}
    let daysCache = {}; // { 'year-month': [ {day,hijri_date,greg_date} ] }
    let years = [];
    let currentYear = null;
    let currentMonth = 1;
    let pendingSelectGreg = null; // store greg date to auto-select when calendar data ready

    function fetchJSON(url) {
      return fetch(url).then(r => r.json());
    }

    function monthName(year, month) {
      const ms = monthsCache[year] || [];
      const f = ms.find(m => parseInt(m.id) === parseInt(month));
      return f ? f.name : ('Month ' + month);
    }

    function loadYears() {
      return fetchJSON('<?php echo base_url('common/get_hijri_years'); ?>').then(d => {
        if (d.status === 'success') {
          years = d.years;
          if (!currentYear) currentYear = years[0];
          if (yearSelect) {
            yearSelect.innerHTML = years.map(y => `<option value="${y}">${y}</option>`).join('');
            yearSelect.value = currentYear;
          }
        }
      });
    }

    function loadMonths(year) {
      if (monthsCache[year]) return Promise.resolve(monthsCache[year]);
      return fetchJSON('<?php echo base_url('common/get_hijri_months'); ?>?year=' + year).then(d => {
        if (d.status === 'success') monthsCache[year] = d.months;
        return monthsCache[year] || [];
      });
    }

    function loadDays(year, month) {
      const k = year + '-' + month;
      if (daysCache[k]) return Promise.resolve(daysCache[k]);
      return fetchJSON('<?php echo base_url('common/get_hijri_days'); ?>?year=' + year + '&month=' + month).then(d => {
        if (d.status === 'success') daysCache[k] = d.days;
        return daysCache[k] || [];
      });
    }

    function highlightGreg(iso) {
      if (!iso) return;
      const btn = calContainer.querySelector('[data-greg="' + iso + '"]');
      if (btn) {
        [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
        btn.classList.add('active');
      }
    }
    // adjust applyAutoSelect to only highlight; avoid triggering click (which re-fetches unnecessarily)
    function applyAutoSelect() {
      if (!pendingSelectGreg) return;
      highlightGreg(pendingSelectGreg);
      pendingSelectGreg = null;
    }

    function gregWeekday(iso) { // iso = Y-m-d
      const d = new Date(iso.replace(/-/g, '/')); // Safari friendly
      return d.getDay(); // 0=Sun
    }

    function render() {
      // Ensure months for current year are loaded before rendering (so monthName resolves correctly)
      loadMonths(currentYear).then(() => loadDays(currentYear, currentMonth)).then(days => {
        currentLbl.textContent = monthName(currentYear, currentMonth) + ' ' + currentYear;
        const monthYearHeading = document.getElementById('hijri-month-year');
        if (monthYearHeading) {
          // monthYearHeading.textContent = currentLbl.textContent;
        }
        calContainer.innerHTML = '';
        if (!days.length) {
          calContainer.innerHTML = '<div class="text-muted small">No days.</div>';
          return;
        }

        // Build 7-col structure: headers + weeks
        const table = document.createElement('div');
        table.className = 'hijri-week-grid';

        const headers = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const headRow = document.createElement('div');
        headRow.className = 'hijri-row hijri-head';
        headers.forEach(h => {
          const hd = document.createElement('div');
          hd.className = 'hijri-cell hijri-head-cell fw-semibold text-center';
          hd.textContent = h;
          headRow.appendChild(hd);
        });
        table.appendChild(headRow);

        // Map each day to weekday
        // days are in ascending greg_date already
        let weekRow = document.createElement('div');
        weekRow.className = 'hijri-row';
        let cellsInRow = 0;
        // Determine offset for first day
        const firstWeekday = gregWeekday(days[0].greg_date); // 0..6
        for (let i = 0; i < firstWeekday; i++) {
          const empty = document.createElement('div');
          empty.className = 'hijri-cell empty';
          weekRow.appendChild(empty);
          cellsInRow++;
        }

        days.forEach(d => {
          if (cellsInRow === 7) {
            table.appendChild(weekRow);
            weekRow = document.createElement('div');
            weekRow.className = 'hijri-row';
            cellsInRow = 0;
          }
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'btn btn-sm btn-outline-primary hijri-day';
          btn.textContent = d.day;
          btn.dataset.greg = d.greg_date;
          btn.dataset.hijri = d.hijri_date;
          btn.addEventListener('click', () => {
            const gp = d.greg_date.split('-');
            if (gp.length === 3) {
              gregInput.value = gp[2] + '-' + gp[1] + '-' + gp[0];
              gregInput.dispatchEvent(new Event('change', {
                bubbles: true
              }));
            }
            const hp = d.hijri_date.split('-');
            if (hp.length === 3) {
              hijriDisplay.textContent = 'Hijri Date: ' + hp[0] + ' ' + monthName(currentYear, currentMonth) + ' ' + hp[2];
            }
            [...calContainer.querySelectorAll('.hijri-day')].forEach(x => x.classList.remove('active'));
            btn.classList.add('active');
            const selectedHeading = document.getElementById('hijri-selected-date');
            if (selectedHeading) {
              // selectedHeading.textContent = 'Selected Date: ' + d.day + ' ' + monthName(currentYear, currentMonth) + ' ' + currentYear;
            }
          });
          const cell = document.createElement('div');
          cell.className = 'hijri-cell text-center';
          cell.appendChild(btn);
          weekRow.appendChild(cell);
          cellsInRow++;
        });

        // Trailing empties
        if (cellsInRow > 0 && cellsInRow < 7) {
          for (let i = cellsInRow; i < 7; i++) {
            const empty = document.createElement('div');
            empty.className = 'hijri-cell empty';
            weekRow.appendChild(empty);
          }
        }
        table.appendChild(weekRow);
        calContainer.appendChild(table);
        applyAutoSelect();
        // After auto-select ensure selected heading reflects active day if any (e.g., on sync)
        const selectedHeading = document.getElementById('hijri-selected-date');
        if (selectedHeading) {
          const active = calContainer.querySelector('.hijri-day.active');
          if (active) {
            // selectedHeading.textContent = 'Selected Date: ' + active.textContent.trim() + ' ' + monthName(currentYear, currentMonth) + ' ' + currentYear;
          }
        }
      });
    }
    // Insert toggle button (once) just above calendar wrapper
    (function ensureToggle() {
      const wrapper = document.getElementById('hijri-selector-wrapper');
      if (wrapper && !document.getElementById('toggle-hijri-cal')) {
        // Always clear stored preference to force reset each refresh
        try {
          localStorage.removeItem('hijriCalHidden');
        } catch (e) {}
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'toggle-hijri-cal';
        btn.className = 'btn btn-sm btn-outline-primary mb-2';
        btn.setAttribute('aria-expanded', 'false');
        btn.textContent = 'Show Hijri Calendar';
        if (wrapper.children.length > 0) {
          wrapper.insertBefore(btn, wrapper.children[1]);
        } else {
          wrapper.insertBefore(btn, wrapper.firstChild);
        }
        const note = wrapper.querySelector('small.text-muted');
        calContainer.style.display = 'none';
        if (note) note.style.display = 'none';
        btn.addEventListener('click', () => {
          const hidden = calContainer.style.display === 'none';
          if (hidden) {
            calContainer.style.display = '';
            if (note) note.style.display = '';
            btn.textContent = 'Hide Hijri Calendar';
            btn.setAttribute('aria-expanded', 'true');
          } else {
            calContainer.style.display = 'none';
            if (note) note.style.display = 'none';
            btn.textContent = 'Show Hijri Calendar';
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      }
    })();

    function navigate(delta) {
      currentMonth += delta;
      if (currentMonth < 1) {
        currentMonth = 12;
        const idx = years.indexOf(currentYear);
        if (idx > 0) {
          currentYear = years[idx - 1];
        }
      } else if (currentMonth > 12) {
        currentMonth = 1;
        const idx = years.indexOf(currentYear);
        if (idx < years.length - 1) {
          currentYear = years[idx + 1];
        }
      }
      if (yearSelect) yearSelect.value = currentYear;
      // If new year months not cached, ensure they load before render
      if (!monthsCache[currentYear]) {
        loadMonths(currentYear).then(() => render());
      } else {
        render();
      }
    }

    function syncCalendarToGregorian(dmy) {
      if (!dmy) return;
      const p = dmy.split('-');
      if (p.length !== 3) return;
      const iso = p[2] + '-' + p[1] + '-' + p[0];
      pendingSelectGreg = iso; // target to auto-select
      // First try cache
      for (const key in daysCache) {
        const days = daysCache[key];
        if (days.some(d => d.greg_date === iso)) {
          const partsKey = key.split('-');
          currentYear = parseInt(partsKey[0]);
          currentMonth = parseInt(partsKey[1]);
          render();
          return;
        }
      }
      // If not in cache, query backend for hijri parts to jump directly
      fetch('<?php echo base_url('common/get_hijri_parts'); ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: new URLSearchParams({
          greg_date: iso
        })
      }).then(r => r.json()).then(resp => {
        if (resp.status === 'success' && resp.parts) {
          currentYear = resp.parts.hijri_year;
          currentMonth = parseInt(resp.parts.hijri_month);
          if (yearSelect) yearSelect.value = currentYear;
        }
        render();
      }).catch(() => {
        render();
      });
    }

    // Initial boot
    loadYears().then(() => loadMonths(currentYear)).then(ms => {
      if (ms && ms.length && !currentMonth) currentMonth = parseInt(ms[0].id) || 1;
    }).then(() => {
      // If greg date already set, store for auto-select
      if (gregInput.value) {
        syncCalendarToGregorian(gregInput.value);
      } else {
        render();
      }
    });

    // Watch for external Gregorian date changes (flatpickr or manual)
    gregInput.addEventListener('change', function() {
      syncCalendarToGregorian(this.value);
    });
    // Attach navigation button handlers (may have been lost in refactors)
    if (prevBtn && nextBtn) {
      prevBtn.addEventListener('click', function() {
        navigate(-1);
      });
      nextBtn.addEventListener('click', function() {
        navigate(1);
      });
    }
    if (yearSelect) {
      yearSelect.addEventListener('change', function() {
        const newYear = this.value;
        if (newYear && newYear !== currentYear) {
          currentYear = newYear;
          // keep same month if possible, else fallback to 1
          loadMonths(currentYear).then(ms => {
            const exists = (ms || []).some(m => parseInt(m.id) === parseInt(currentMonth));
            if (!exists) {
              currentMonth = ms && ms.length ? parseInt(ms[0].id) : 1;
            }
            render();
          });
        }
      });
    }
  })();
</script>

<script>
  (function addHijriHeadings() {
    const wrapper = document.getElementById('hijri-selector-wrapper');
    if (wrapper && !document.getElementById('hijri-month-year')) {
      const heading = document.createElement('div');
      heading.id = 'hijri-month-year';
      heading.className = 'mb-1 fw-semibold text-primary small';
      wrapper.insertBefore(heading, document.getElementById('toggle-hijri-cal') ? document.getElementById('toggle-hijri-cal').nextSibling : wrapper.children[1]);
      const sel = document.createElement('div');
      sel.id = 'hijri-selected-date';
      sel.className = 'mb-2 text-secondary small';
      wrapper.insertBefore(sel, heading.nextSibling);
    }
  })();
</script>