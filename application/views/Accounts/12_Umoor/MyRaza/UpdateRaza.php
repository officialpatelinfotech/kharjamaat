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
      <div class="card pull-center bg-light sj-card ">
        <div class="card-header text-center form-title">Update Raza Request</div>
        <div class="card-body">
          <div class="card-text">
            <form id="raza-form" class="main-form" action="<?php echo base_url('accounts/updateraza/') . $raza['id'] ?>" method="post">
              <!-- Render the 'raza-type' dropdown -->
              <div class="form-group">
                <label for="id_raza-raza" class="col-form-label requiredField">
                  Raza for<span class="asteriskField">*</span>
                </label>
                <select name="raza-type" class="select2widget form-control" required id="raza-type"
                  disabled data-allow-clear="false"
                  data-minimum-input-length="0" tabindex="-1" aria-hidden="true">
                  <?php
                  foreach ($razatype as $rt) {
                    $selected = ($rt['id'] == $raza['razaType']) ? 'selected' : '';
                    echo '<option value="' . $rt['id'] . '" ' . $selected . '>' . $rt['name'] . '</option>';
                  }
                  ?>
                </select>
              </div>

              <!-- Render dynamic fields based on selected 'raza-type' -->
              <div id="dynamic-fields-container">

              </div>

              <div id="lowerbutton" class="lowerbutton">
                <div class="form-group form-check hide-for-miqaat">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                  <label class="form-check-label" for="exampleCheck1">All Details Correct?*</label>
                </div>

                <div class="row px-3 gy-1">
                  <div class=" "><a href="<?php echo base_url("umoor12/MyRazaRequest?value=$value") ?>"
                      class="btn btn-danger w100percent-xs cancel-miqaat-back">Cancel</a></div>
                  <div class="ml-auto hide-for-miqaat">
                    <button type="submit" class="btn btn-success w100percent-xs mbm-xs">
                      Submit
                    </button>
                  </div>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let razas = [];
  <?php foreach ($razatype as $r) { ?>
    razas.push(<?php echo $r['fields'] ?>)
  <?php } ?>
  let raza_data_field = <?php echo $raza['razadata'] ?>;
  updateFormFields()

  function updateFormFields() {
    let selectedRazaType = document.getElementById('raza-type').value;
    let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
    let lowerelmentid = document.getElementById('lowerbutton')
    lowerelmentid.style.display = 'block'
    if (selectedRazaType == 2) {
      $(".hide-for-miqaat").hide();
      $(".cancel-miqaat-back").text(`Back`);
      $(".form-title").text("View Raza Details");
      // Show raza details joined with miqaat table
      let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
      dynamicFieldsContainer.innerHTML = '';
      <?php if (isset($raza_miqaat) && is_array($raza_miqaat)): ?>
        let miqaat = <?php echo json_encode($raza_miqaat); ?>;
        let html = '<div class="card mt-2 mb-2"><div class="card-body">';
        html += `<p><strong>Miqaat Name:</strong> ${miqaat.name || ''}</p>`;
        html += `<p><strong>Miqaat Type:</strong> ${miqaat.type || ''}</p>`;
        html += `<p><strong>Miqaat Date:</strong> ${miqaat.date ? new Date(miqaat.date).toLocaleDateString('en-GB') : ''}</p>`;
        html += '</div></div>';
        dynamicFieldsContainer.innerHTML = html;
      <?php else: ?>
        dynamicFieldsContainer.innerHTML = '<div class="alert alert-info">No miqaat data found for this raza.</div>';
      <?php endif; ?>
    } else {
      if (selectedRaza) {
        let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
        dynamicFieldsContainer.innerHTML = '';
        selectedRaza.fields.forEach(field => {
          let fieldContainer = document.createElement('div');
          fieldContainer.classList.add('form-group');

          let label = document.createElement('label');
          label.setAttribute('for', `id_raza-${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_')}`);
          label.classList.add('col-form-label', 'requiredField');
          if (field.required) {
            label.innerHTML = `${field.name}<span class="asteriskField">*</span>`;
          } else {
            label.innerHTML = `${field.name}`;
          }

          let inputElement;

          switch (field.type) {
            case 'date':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'date');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('dateinput', 'form-control');
              inputElement.required = field.required;
              break;
            case 'select':
              inputElement = document.createElement('select');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('select', 'form-control');
              inputElement.required = field.required;

              // Add options for select
              field.options.forEach(option => {
                let optionElement = document.createElement('option');
                optionElement.value = option.id;
                optionElement.text = option.name;
                inputElement.appendChild(optionElement);
              });
              break;
            case 'text':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'text');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;
            case 'number':
              inputElement = document.createElement('input');
              inputElement.setAttribute('type', 'number');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;
            case 'textarea':
              inputElement = document.createElement('textarea');
              inputElement.setAttribute('name', `${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-')}`);
              inputElement.classList.add('form-control');
              inputElement.required = field.required;
              break;

            default:
              break;
          }
          inputElement.value = raza_data_field[`${field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_')}`]
          fieldContainer.appendChild(label);
          fieldContainer.appendChild(inputElement);
          dynamicFieldsContainer.appendChild(fieldContainer);
          console.log(inputElement)
        });
      }
    }
  }
</script>

<div id="toast-message" class="toast-message">
  done
</div>