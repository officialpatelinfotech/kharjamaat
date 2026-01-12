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
                        <button type="submit" id="raza-submit-btn" class="btn btn-success w100percent-xs mbm-xs">
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

  <!-- Dues confirmation modal -->
  <div id="dues-modal" class="modal" tabindex="-1" role="dialog" style="display:none; position:fixed; left:50%; top:10%; transform:translateX(-50%); z-index:1050;">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pending Financial Dues</h5>
          <button type="button" class="close" aria-label="Close" onclick="hideDuesModal()">&times;</button>
        </div>
        <div class="modal-body">
          <div id="dues-content">
            Loading dues...
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="hideDuesModal()">Cancel</button>
          <button type="button" id="dues-confirm" class="btn btn-primary">Proceed & Submit</button>
        </div>
      </div>
    </div>
  </div>
  <div id="dues-backdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1040;"></div>
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

<script>
  // Helper to format INR without decimals (simple)
  function formatINR(n) {
    n = Math.round(Number(n) || 0);
    return '₹' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  function coloredAmount(n) {
    var amt = formatINR(n);
    if (Number(n) > 0) return '<span style="color:red">' + amt + '</span>';
    return amt;
  }

  function showDuesModal(html) {
    document.getElementById('dues-content').innerHTML = html;
    document.getElementById('dues-modal').style.display = 'block';
    var bd = document.getElementById('dues-backdrop'); if (bd) bd.style.display = 'block';
  }
  function hideDuesModal() {
    document.getElementById('dues-modal').style.display = 'none';
    var bd = document.getElementById('dues-backdrop'); if (bd) bd.style.display = 'none';
  }

  document.getElementById('raza-form').addEventListener('submit', function (e) {
    // Intercept submit, fetch dues and show confirmation modal
    e.preventDefault();
    var submitBtn = document.getElementById('raza-submit-btn');
    submitBtn.disabled = true;
    fetch('<?= base_url('accounts/get_member_dues') ?>', { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        submitBtn.disabled = false;
        if (!data || !data.success) {
          // proceed if unable to fetch dues
          document.getElementById('raza-form').submit();
          return;
        }
        var d = data.dues;
        var html = '<table class="table table-sm">'
          + '<tr><th>Category</th><th class="text-right">Due</th></tr>'
          + '<tr><td>FMB Takhmeen</td><td class="text-right">' + coloredAmount(d.fmb_due) + '</td></tr>'
          + '<tr><td>Sabeel Takhmeen</td><td class="text-right">' + coloredAmount(d.sabeel_due) + '</td></tr>'
          + '<tr><td>General Contributions</td><td class="text-right">' + coloredAmount(d.gc_due) + '</td></tr>'
          + '<tr><td>Miqaat Invoices</td><td class="text-right">' + coloredAmount(d.miqaat_due) + '</td></tr>'
          + '<tr><td>Corpus Fund</td><td class="text-right">' + coloredAmount(d.corpus_due) + '</td></tr>'
          + '<tr><td>Wajebaat</td><td class="text-right">' + coloredAmount(d.wajebaat_due || 0) + '</td></tr>'
          + '<tr><td>Qardan Hasana</td><td class="text-right">' + coloredAmount(d.qardan_hasana_due || 0) + '</td></tr>'
          + '<tr><th>Total</th><th class="text-right">' + coloredAmount(d.total_due) + '</th></tr>'
          + '</table>';
        if (d.total_due <= 0) {
          html = '<div class="alert alert-success">No pending dues. You may proceed to submit.</div>' + html;
        } else {
          html = '<div class="alert alert-warning">You have pending dues. Please review before submitting.</div>' + html;
        }
        // append invoice rows if provided
        if (data.miqaat_invoices && Array.isArray(data.miqaat_invoices) && data.miqaat_invoices.length > 0) {
          var invHtml = '<hr><h6>Miqaat / Member Invoices</h6>'
            + '<table class="table table-sm table-bordered"><thead><tr><th>Assigned to</th><th>Invoice</th><th class="text-right">Amount</th><th class="text-right">Paid</th><th class="text-right">Due</th></tr></thead><tbody>';
          data.miqaat_invoices.forEach(function(inv) {
            var owner = inv.owner_name || inv.user_id || '';
            var miqName = inv.miqaat_name || ('#'+inv.miqaat_id);
            invHtml += '<tr>'
              + '<td>' + owner + '</td>'
              + '<td>' + miqName + '</td>'
              + '<td class="text-right">' + formatINR(inv.amount || 0) + '</td>'
              + '<td class="text-right">' + formatINR(inv.paid_amount || 0) + '</td>'
              + '<td class="text-right">' + coloredAmount(inv.due_amount || 0) + '</td>'
              + '</tr>';
          });
          invHtml += '</tbody></table>';
          html += invHtml;
        }
        showDuesModal(html);
      })
      .catch(function (err) {
        submitBtn.disabled = false;
        // On error, proceed to submit
        document.getElementById('raza-form').submit();
      });
  });

  // when user confirms in modal, send dues emails then submit the form
  document.getElementById('dues-confirm').addEventListener('click', function () {
    var submitForm = function() { hideDuesModal(); document.getElementById('raza-form').submit(); };
    fetch('<?= base_url('accounts/send_dues_email') ?>', { method: 'POST', credentials: 'same-origin' })
      .then(function(r){ return r.json(); })
      .then(function(resp){ submitForm(); })
      .catch(function(){ submitForm(); });
  });
</script>

<div id="toast-message" class="toast-message">
  done
</div>