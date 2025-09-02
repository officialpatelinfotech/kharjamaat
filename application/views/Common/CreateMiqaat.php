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
</style>
<div class="container margintopcontainer pt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?php echo base_url(isset($from) ? $from . "?from=$active_controller" : "common/managemiqaat"); ?>" class="btn btn-outline-secondary">
      <i class="fa-solid fa-arrow-left"></i>
    </a>
  </div>

  <div class="mb-4">
    <h3 class="heading text-center mb-4"><?php echo isset($edit_mode) && $edit_mode ? 'Edit Miqaat' : 'Create Miqaat'; ?></h3>
    <div class="col-12 border rounded">
      <form method="POST" action="<?php echo base_url(isset($edit_mode) && $edit_mode ? 'common/update_miqaat' : 'common/add_miqaat'); ?>">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo isset($edit_mode) && $edit_mode ? 'Edit Miqaat' : 'Add New Miqaat'; ?></h5>
        </div>
        <div class="modal-body">
          <?php if (isset($edit_mode) && $edit_mode): ?>
            <div class="form-group mb-3">
              <label for="miqaat-status">Status:</label>
              <select name="status" id="miqaat-status" class="form-control" required>
                <option value="0" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['status']) && $miqaat['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                <option value="1" <?php echo (isset($edit_mode) && $edit_mode && isset($miqaat['status']) && $miqaat['status'] == 1) ? 'selected' : ''; ?>>Active</option>
              </select>
            </div>
          <?php endif; ?>
          <div class="form-group mb-3">
            <label for="date">Date:</label>
            <input type="text" id="date" name="date" class="form-control mb-3" required value="<?php echo isset($edit_mode) && $edit_mode && isset($miqaat['date']) ? date('d-m-Y', strtotime($miqaat['date'])) : (isset($date) ? date('d-m-Y', strtotime($date)) : ''); ?>" placeholder="Please select a date">
            <p id="hijri-date-display" class="form-text text-muted"></p>
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
                          <br><strong>Leader:</strong> <?php echo htmlspecialchars($assignment['group_leader_name'], ENT_QUOTES); ?>
                        <?php endif; ?>
                        <br><strong>Members:</strong>
                        <?php if (!empty($assignment['members'])): ?>
                          <ul>
                            <?php foreach ($assignment['members'] as $member): ?>
                              <li><?php echo htmlspecialchars($member['name'] ?? $member['first_name'], ENT_QUOTES); ?> (ID: <?php echo $member['id']; ?>)</li>
                            <?php endforeach; ?>
                          </ul>
                        <?php else: ?>
                          <em>No members assigned</em>
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
            <select name="assign_to" id="assign-to" class="form-control"
              <?php echo isset($edit_mode) && $edit_mode ? '' : 'required'; ?>>
              <option value="">-- Select Assign To --</option>
              <option value="Individual">Individual</option>
              <option value="Group">Group</option>
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
              <label for="group-name">Group Name:</label>
              <input type="text" name="group_name" id="group-name" class="form-control" placeholder="Enter Group Name" value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                                                                                                                                foreach ($miqaat['assignments'] as $assignment) {
                                                                                                                                  if ($assignment['assign_type'] === 'Group') echo htmlspecialchars($assignment['group_name'], ENT_QUOTES);
                                                                                                                                }
                                                                                                                              } ?>">
            </div>

            <div class="form-group mb-3">
              <label for="group-leader">Select Group Leader:</label>
              <input type="text" id="group-leader" class="form-control" placeholder="Search & select leader" value="<?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])) {
                                                                                                                      foreach ($miqaat['assignments'] as $assignment) {
                                                                                                                        if ($assignment['assign_type'] === 'Group') echo htmlspecialchars($assignment['group_leader_name'], ENT_QUOTES);
                                                                                                                      }
                                                                                                                    } ?>">
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
              <label for="group-members">Assign Members:</label>
              <input type="text" id="group-members" class="form-control" placeholder="Search & add members">
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
              <small class="text-muted">Multiple members can be assigned</small>
              <div id="selected-group-members" class="mt-2">
                <?php if (isset($edit_mode) && $edit_mode && isset($miqaat['assignments'])): ?>
                  <?php foreach ($miqaat['assignments'] as $assignment): ?>
                    <?php if ($assignment['assign_type'] === 'Group' && !empty($assignment['members'])): ?>
                      <?php foreach ($assignment['members'] as $member): ?>
                        <span class="badge bg-info text-dark me-2 mr-2">
                          <?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>
                          <a href="#" class="text-dark ms-1 remove-group-member" data-id="<?php echo $member['id']; ?>">×</a>
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
          <button type="submit" class="btn btn-success"><?php echo isset($edit_mode) && $edit_mode ? 'Update Miqaat' : 'Save Miqaat'; ?></button>
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
      });
    }

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

    $("#date").on('change', function(e) {
      $.ajax({
        url: '<?php echo base_url("Common/verify_miqaat_date") ?>',
        method: 'POST',
        data: {
          miqaat_date: $(this).val()
        },
        success: function(response) {
          const data = JSON.parse(response);
          if (data.status === 'exists') {
            alert(`Miqaat already exists for ${$("#miqaat_date").val()}. Please choose a different date.`);
            $("#miqaat_date").val('');
          } else {
            const hijriDate = data.hijri_date;
            $('#hijri-date-display').html(`Hijri Date: ${hijriDate}`);
          }
        }
      });
    });
    const assignSelect = document.getElementById("assign-to");
    const individualContainer = document.getElementById("individual-container");
    const groupContainer = document.getElementById("group-container");
    const falaContainer = document.getElementById("fala-ni-niyaz-container");

    assignSelect.addEventListener("change", function() {
      individualContainer.classList.add("d-none");
      groupContainer.classList.add("d-none");
      falaContainer.classList.add("d-none");

      if (this.value === "Individual") {
        individualContainer.classList.remove("d-none");
      } else if (this.value === "Group") {
        groupContainer.classList.remove("d-none");
      } else if (this.value === "Fala_ni_Niyaz") {
        falaContainer.classList.remove("d-none");
      }
    });

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

          if (!ids.includes(ui.item.id.toString())) {
            ids.push(ui.item.id);
            hiddenInput.value = ids.join(",");

            let chip = document.createElement("span");
            chip.className = "badge bg-primary text-white me-2 mr-2";
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
    setupAutocomplete("group-members", ".acod-3", "selected-group-members", "group-member-ids", true);

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
</script>