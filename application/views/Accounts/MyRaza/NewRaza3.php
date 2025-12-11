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
        <div class="card-header text-center">New Raza Request</div>
        <div class="card-body">
          <div class="card-text">
            <form id="raza-form" class="main-form" action="<?php echo base_url('accounts/submit_raza') ?>"
              method="post">

              <div class="form-group"><label class="col-form-label  requiredField">
                  My Sabil Dues Are Paid<span class="asteriskField">*</span></label>
                <div class="">
                  <div class="form-check"><label
                      class="form-check-label"><input type="radio" class="form-check-input"
                        name="sabil" value="0" required="required">
                      No
                    </label></div>
                  <div class="form-check"><label
                      class="form-check-label"><input type="radio" class="form-check-input"
                        name="sabil" value="1" required="required">
                      Yes
                    </label></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-form-label  requiredField">
                  My FMB Dues Are Paid<span class="asteriskField">*</span>
                </label>
                <div class="">
                  <div class="form-check"><label
                      class="form-check-label"><input type="radio" class="form-check-input"
                        name="fmb" value="0" required="required">
                      No
                    </label></div>
                  <div class="form-check"><label
                      class="form-check-label"><input type="radio" class="form-check-input"
                        name="fmb" value="1" required="required">
                      Yes
                    </label></div>
                </div>
                <!--<div class="form-group"><label class="col-form-label  requiredField">-->
                <!--    I Have Contributed in FMB Smart Kitchen Tameer<span class="asteriskField">*</span></label>-->
                <!--<div class="">-->
                <!--    <div class="form-check"><label-->
                <!--            class="form-check-label"><input type="radio" class="form-check-input"-->
                <!--                name="fmbtameer" value="0" required="required">-->
                <!--            No-->
                <!--        </label></div>-->
                <!--    <div class="form-check"><label-->
                <!--            class="form-check-label"><input type="radio" class="form-check-input"-->
                <!--                name="fmbtameer" value="1" required="required">-->
                <!--            Yes-->
                <!--        </label></div>-->
                <!--</div>-->
                <!--</div>-->

                <div class="form-group">
                  <label for="id_raza-raza" class="col-form-label requiredField">
                    Raza for<span class="asteriskField">*</span>
                  </label>
                  <select name="raza-type" class="select2widget form-control" required id="raza-type"
                    onchange="updateFormFields()" data-allow-clear="false" data-minimum-input-length="0"
                    tabindex="-1" aria-hidden="true">
                    <option value="" selected>---------</option>
                    <?php foreach ($razatype as $raza) {
                      echo '<option value="' . $raza['id'] . '">' . $raza['name'] . '</option>';
                    } ?>
                  </select>
                </div>

                <div id="dynamic-fields-container" class="">
                  <!-- Dynamic fields will be added here -->
                </div>

                <div id="lowerbutton" class="lowerbutton">
                  <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                    <label class="form-check-label" for="exampleCheck1">All Details Correct?*</label>
                  </div>

                  <div class="row px-3 gy-1">
                    <div class=" "><a href="<?php echo base_url('accounts/MyRazaRequest') ?>"
                        class="btn btn-danger w100percent-xs">Cancel</a></div>
                    <div class="ml-auto ">
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
  <?php foreach ($razatype as $raza) { ?>
    razas.push(<?php echo $raza['fields'] ?>)
  <?php } ?>

  console.log(razas)

  function updateFormFields() {
    let selectedRazaType = document.getElementById('raza-type').value;
    let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
    let lowerelmentid = document.getElementById('lowerbutton')
    lowerelmentid.style.display = 'block'
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
            inputElement.setAttribute('min', '<?php echo date('Y-m-d'); ?>');
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

        fieldContainer.appendChild(label);
        fieldContainer.appendChild(inputElement);
        dynamicFieldsContainer.appendChild(fieldContainer);
      });
    }
  }
</script>

<div id="toast-message" class="toast-message">
  done
</div>