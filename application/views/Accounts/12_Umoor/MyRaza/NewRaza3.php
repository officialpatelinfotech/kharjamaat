<style>
  .sj-card {
    width: 400px;
    margin-top: 50px;

    @media screen and (max-width: 768px) {
      width: 98%;
    }
  }

  .pull-center {
    display: table;
    margin-left: auto;
    margin-right: auto;
  }

  @media only screen and (max-width: 767px) {
    .w100percent-xs {
      width: 100% !important;
    }
  }

  .secondaryform {
    display: none;
  }

  .toast-message {
    position: fixed;
    top: 10;
    right: 0;
    padding: 10px 20px;
    border-radius: 4px;
    z-index: 9999;
    display: none;
    font-size: 15px;
    animation: slideIn 0.5s, slideOut 0.5s 2s;

    @media screen and (max-width:400px) {
      width: 100%;
      text-align: center;
    }
  }

  @keyframes slideIn {
    from {
      right: -100%;
    }

    to {
      right: 0;
    }
  }

  @keyframes slideOut {
    from {
      right: 0;
    }

    to {
      right: -100%;
    }
  }

  .lowerbutton {
    display: none;
  }
</style>

<div class="margintopcontainer">
  <div class="container pt-1">
    <div class="fmain">
      <div class="card pull-center bg-light sj-card">
        <div class="card-header text-center" style="text-transform: uppercase; color: goldenrod;">
          New Raza Request For <?= $value; ?>
        </div>
        <div class="card-body">
          <form id="raza-form" class="main-form" action="<?= base_url("accounts/submit_raza") ?>" method="post">
            <?php if ($value === 'Private-Event' || $value === 'Public-Event'): ?>
              <!-- Removed Sabil/FMB dues fields as requested -->
            <?php endif; ?>

            <div class="form-group">
              <label for="raza-type" class="col-form-label requiredField">
                Raza for<span class="asteriskField">*</span>
              </label>
              <select name="raza-type" class="select2widget form-control" required id="raza-type"
                onchange="updateFormFields()">
                <option value="">---------</option>
                <?php foreach ($razatype as $raza) {
                  $selected = (!empty($razaId) && $raza['id'] == $razaId) ? 'selected' : '';
                  echo '<option value="' . $raza['id'] . '" ' . $selected . '>' . $raza['name'] . '</option>';
                } ?>
              </select>
            </div>

            <div id="dynamic-fields-container"></div>

            <div id="lowerbutton" class="lowerbutton mt-3">
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                <label class="form-check-label" for="exampleCheck1">All Details Correct?*</label>
              </div>
              <div class="row px-3 gy-1">
                <div><a href="<?= base_url("umoor12/MyRazaRequest?value=$value") ?>"
                    class="btn btn-danger w100percent-xs">Cancel</a></div>
                <div class="ml-auto">
                  <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let razas = [];
  <?php foreach ($razatype as $raza) {
    echo "razas.push(" . $raza['fields'] . ");";
  } ?>

  function updateFormFields(existingData = null) {
    let selectedRazaType = document.getElementById('raza-type').value;
    let selectedRaza = razas.find(r => r.id == selectedRazaType);

    document.getElementById('lowerbutton').style.display = 'block';
    if (!selectedRaza) return;

    let data = existingData || window.razaPreFillData || {};

    let container = document.getElementById('dynamic-fields-container');
    container.innerHTML = '';

    if (selectedRazaType == 2) {
      // Handle specific case for Raza Type 2
      $.ajax({
        url: "<?php echo base_url("umoor12/get_miqaat_by_its_id"); ?>",
        dataType: 'json',
        success: function(res) {
          let container = document.getElementById('dynamic-fields-container');
          if (Array.isArray(res) && res.length > 0) {
            let selectHtml = '<div class="form-group">';
            selectHtml += '<label for="miqaat-select" class="col-form-label requiredField">Miqaats Assigned To You<span class="asteriskField">*</span></label>';
            selectHtml += '<select id="miqaat-select" name="miqaat_id" class="form-control"><option value="">---------</option>';

            res.forEach((miqaat, idx) => {
              selectHtml += `<option value="${miqaat.miqaat_id}" ${Number(miqaat.miqaat_status) == 1 ? "" : "disabled"} data-miqaat-option="${idx}">${miqaat.miqaat_name}</option>`;
            });

            selectHtml += '</select></div>';
            selectHtml += '<div id="miqaat-details"></div>';
            container.innerHTML = selectHtml;

            // Function to display miqaat details
            function showMiqaatDetails(index) {
              let miqaat = res[index];
              let detailsHtml = '<div class="card mt-2"><div class="card-body">';
              detailsHtml += `<p><strong>Name:</strong> ${miqaat.miqaat_name}</p>`;
              detailsHtml += `<p><strong>Type:</strong> ${miqaat.miqaat_type}</p>`;
              // Format date to dd-mm-yyyy
              let formattedDate = '';
              if (miqaat.miqaat_date) {
                let d = new Date(miqaat.miqaat_date);
                if (!isNaN(d.getTime())) {
                  let day = String(d.getDate()).padStart(2, '0');
                  let month = String(d.getMonth() + 1).padStart(2, '0');
                  let year = d.getFullYear();
                  formattedDate = `${day}-${month}-${year}`;
                } else {
                  // fallback if not a valid date object
                  let parts = miqaat.miqaat_date.split('-');
                  if (parts.length === 3) {
                    formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                  } else {
                    formattedDate = miqaat.miqaat_date;
                  }
                }
              }
              detailsHtml += `<p><strong>Date:</strong> ${formattedDate}</p>`;

              if (miqaat.assign_type === 'Group') {
                detailsHtml += `<p><strong>Assigned To:</strong> <span class="badge bg-primary text-white">Group</span></p>`;
                if (miqaat.group_name) {
                  detailsHtml += `<p><strong>Group Name:</strong> ${miqaat.group_name}</p>`;
                }
                if (miqaat.group_leader_name) {
                  detailsHtml += `<p><strong>Group Leader:</strong> ${miqaat.group_leader_name} (${miqaat.group_leader_id})</p>`;
                }

                if (miqaat.member_names.length > 0) {
                  detailsHtml += `<p><strong>Group Members:</strong></p>`;
                  detailsHtml += `<table class='table table-sm table-bordered mt-2'><thead><tr><th>Name</th><th>ITS ID</th></tr></thead><tbody>`;
                  miqaat.member_names.split(",").forEach(function(name, idx) {
                    let mid = (miqaat.member_ids && miqaat.member_ids.split(",")[idx]) ? miqaat.member_ids.split(",")[idx] : '';
                    detailsHtml += `<tr><td>${name}</td><td>${mid}</td></tr>`;
                  });
                  detailsHtml += `</tbody></table>`;
                }

              } else if (miqaat.assign_type === 'Individual') {
                detailsHtml += `<p><strong>Assigned To:</strong> <span class="badge bg-success">Individual</span></p>`;
                if (Array.isArray(miqaat.member_names) && miqaat.member_names.length > 0) {
                  let name = miqaat.member_names[0];
                  let mid = (miqaat.member_ids && miqaat.member_ids[0]) ? miqaat.member_ids[0] : '';
                  detailsHtml += `<p><strong>Member:</strong> ${name} (${mid})</p>`;
                }
              }

              detailsHtml += '</div></div>';
              document.getElementById('miqaat-details').innerHTML = detailsHtml;
            }

            // On select change
            document.getElementById('miqaat-select').addEventListener('change', function() {
              let selectedOption = this.options[this.selectedIndex];
              let miqaatIndex = selectedOption.getAttribute('data-miqaat-option');
              showMiqaatDetails(miqaatIndex);
            });
          } else {
            container.innerHTML = '<div class="alert alert-info">No miqaat data found.</div>';
          }
        }
      });
    } else {
      selectedRaza.fields.forEach(field => {
        let fieldName = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
        let fieldValue = data[fieldName] || '';

        let group = document.createElement('div');
        group.className = 'form-group';

        let label = document.createElement('label');
        label.className = 'col-form-label' + (field.required ? ' requiredField' : '');
        // label.setAttribute('for', 'id_' + fieldName);
        label.setAttribute('for', fieldName);
        label.innerHTML = field.name + (field.required ? '<span class="asteriskField">*</span>' : '');
        group.appendChild(label);

        let input;
        switch (field.type) {
          case 'text':
          case 'date':
          case 'number':
            input = document.createElement('input');
            input.type = field.type;
            input.name = fieldName;
            input.className = 'form-control';
            input.value = fieldValue;
            if (field.required) input.required = true;
            break;
          case 'select':
            input = document.createElement('select');
            input.name = fieldName;
            input.id = fieldName;
            input.className = 'form-control';
            if (field.required) input.required = true;
            field.options.forEach(opt => {
              let o = document.createElement('option');
              o.value = opt.id;
              o.text = opt.name;
              if (opt.id == fieldValue) o.selected = true;
              input.appendChild(o);
            });
            break;
          case 'textarea':
            input = document.createElement('textarea');
            input.name = fieldName;
            input.className = 'form-control';
            input.value = fieldValue;
            if (field.required) input.required = true;
            break;
        }

        group.appendChild(input);
        container.appendChild(group);
      });
    }
  }

  <?php if (!empty($razaId)): ?>
    window.addEventListener('DOMContentLoaded', function() {
      document.getElementById('raza-type').value = "<?= $razaId ?>";
      window.razaPreFillData = <?= json_encode($razaData ?? []) ?>;
      updateFormFields();
    });
  <?php endif; ?>
</script>

<div id="toast-message" class="toast-message">done</div>