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
  <style>
    /* Pending dues modal: make content scrollable within viewport */
    #dues-modal {
      position: fixed;
      inset: 0;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      z-index: 1050;
    }

    #dues-modal .modal-dialog {
      margin: 0;
      width: 100%;
      max-width: 820px;
      max-height: 90vh;
    }

    #dues-modal .modal-content {
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    #dues-modal .modal-body {
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      flex: 1 1 auto;
    }
  </style>

  <div id="dues-modal" tabindex="-1" role="dialog">
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

  function isLaagatRentAmountField(fieldLabel) {
    fieldLabel = (fieldLabel || '').toString().toLowerCase();
    // Supports: "Laagat /Rent Amount", "Laagat Amount", "Rent Amount"
    return /(?:laagat\s*(?:\/\s*rent)?|rent)\s*amount/.test(fieldLabel);
  }

  function formatInrCurrency(amount) {
    var n = Number(amount);
    if (!isFinite(n)) n = 0;
    var hasPaise = Math.abs(n - Math.round(n)) > 1e-9;
    var opts = {
      minimumFractionDigits: hasPaise ? 2 : 0,
      maximumFractionDigits: hasPaise ? 2 : 0
    };
    try {
      return '₹' + n.toLocaleString('en-IN', opts);
    } catch (e) {
      // Fallback (non-en-IN environments)
      return '₹' + String(n);
    }
  }

  function closestFormGroup(el) {
    var cur = el;
    while (cur && cur !== document) {
      if (cur.classList && (cur.classList.contains('form-group') || cur.classList.contains('mb-3'))) return cur;
      cur = cur.parentNode;
    }
    return null;
  }

  function renderLaagatRentInfo(groupEl, rawFieldName, chargeType, title, amount, jamaatAmount, sarkaarAmount, depositAmount, items = [], itemQty = {}) {
    if (!groupEl) return;
    rawFieldName = (rawFieldName || '').toString();
    var displayLabel = (chargeType === 'laagat') ? 'Laagat Details' : 'Rent & Deposit Details';
    var bgClass = 'bg-white';
    var toneClass = 'text-dark';
    var n = Number(amount);
    if (!isFinite(n)) n = 0;

    var existingBox = document.getElementById('lr-rent-box');
    if (existingBox && existingBox.getAttribute('data-charge-type') === chargeType && existingBox.getAttribute('data-title') === String(title || '')) {
      var totalRentVals = existingBox.querySelectorAll('.lr-total-rent-value');
      totalRentVals.forEach(function(el) {
        el.textContent = formatInrCurrency(n);
      });
      var depVal = existingBox.querySelector('.lr-deposit-value');
      if (depVal) {
        depVal.textContent = formatInrCurrency(Number(depositAmount));
      }
      return;
    }

    // Clear current controls so it doesn't look like a disabled input.
    while (groupEl.firstChild) groupEl.removeChild(groupEl.firstChild);

    var box = document.createElement('div');
    box.id = 'lr-rent-box';
    box.setAttribute('data-base-rent', String(n));
    box.setAttribute('data-charge-type', chargeType);
    box.setAttribute('data-title', String(title || ''));
    box.className = 'pt-2 pb-3 px-3 rounded border ' + bgClass;
    groupEl.appendChild(box);

    var label = document.createElement('label');
    label.className = 'col-form-label ' + toneClass;
    label.textContent = displayLabel;
    box.appendChild(label);

    if (title) {
      var row = document.createElement('div');
      row.className = 'd-flex justify-content-between align-items-baseline mt-1';

      var t = document.createElement('div');
      t.className = 'h5 mb-0 ' + toneClass;
      t.textContent = String(title);
      row.appendChild(t);

      var v = document.createElement('div');
      v.className = 'h5 mb-0 text-success lr-total-rent-value';
      v.textContent = formatInrCurrency(n);
      row.appendChild(v);

      box.appendChild(row);
    } else {
      var v = document.createElement('div');
      v.className = 'h5 mb-0 w-100 text-end text-success mt-1 lr-total-rent-value';
      v.textContent = formatInrCurrency(n);
      box.appendChild(v);
    }

    if (chargeType === 'rent') {
      // Show deposit amount row
      var dep = Number(depositAmount);
      if (!isFinite(dep)) dep = 0;
      if (dep > 0) {
        var depDiv = document.createElement('div');
        depDiv.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded border text-muted small';
        var depLabel = document.createElement('div');
        depLabel.innerHTML = '<strong>Deposit:</strong>';
        depDiv.appendChild(depLabel);
        var depVal = document.createElement('div');
        depVal.className = 'fw-bold text-primary lr-deposit-value';
        depVal.textContent = formatInrCurrency(dep);
        depDiv.appendChild(depVal);
        box.appendChild(depDiv);
      }

      if (Array.isArray(items) && items.length > 0) {
        var itemsSection = document.createElement('div');
        itemsSection.className = 'mt-3 pt-3 border-top';
        
        var toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn btn-outline-secondary btn-sm w-100 d-flex justify-content-between align-items-center mb-2';
        toggleBtn.style.fontWeight = 'bold';
        toggleBtn.style.fontSize = '0.78rem';
        toggleBtn.style.textTransform = 'uppercase';
        toggleBtn.style.letterSpacing = '0.5px';
        toggleBtn.style.padding = '8px 12px';
        toggleBtn.style.borderRadius = '8px';
        toggleBtn.style.border = '1.5px solid var(--border)';
        toggleBtn.style.background = '#fff';
        toggleBtn.style.color = 'var(--text-2)';
        toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-down"></i>';
        itemsSection.appendChild(toggleBtn);
        
        var itemsList = document.createElement('div');
        itemsList.className = 'd-flex flex-column';
        itemsList.style.gap = '10px';
        itemsList.style.display = 'none';
        itemsSection.appendChild(itemsList);

        var hasPreselected = false;
        items.forEach(function(item) {
          if (itemQty[item.id] && Number(itemQty[item.id]) > 0) {
            hasPreselected = true;
          }
        });
        if (hasPreselected) {
          itemsList.style.display = 'flex';
          toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-up"></i>';
        }

        toggleBtn.addEventListener('click', function() {
          if (itemsList.style.display === 'none') {
            itemsList.style.display = 'flex';
            toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-up"></i>';
          } else {
            itemsList.style.display = 'none';
            toggleBtn.innerHTML = '<span><i class="fa fa-list mr-1"></i> Rent Items (Optional)</span> <i class="fa fa-chevron-down"></i>';
          }
        });
        
        items.forEach(function(item) {
          var itemRow = document.createElement('div');
          itemRow.className = 'd-flex align-items-center justify-content-between p-2 bg-light rounded border';
          
          var detailsDiv = document.createElement('div');
          var nameDiv = document.createElement('div');
          nameDiv.className = 'font-weight-bold text-dark';
          nameDiv.style.fontSize = '0.88rem';
          nameDiv.textContent = item.item_name;
          detailsDiv.appendChild(nameDiv);
          
          var priceDiv = document.createElement('div');
          priceDiv.className = 'text-muted small';
          priceDiv.textContent = formatInrCurrency(item.rent_sabeel) + ' / Piece';
          detailsDiv.appendChild(priceDiv);
          
          itemRow.appendChild(detailsDiv);
          
          var inputDiv = document.createElement('div');
          inputDiv.style.width = '80px';
          
          var qtyInput = document.createElement('input');
          qtyInput.type = 'number';
          qtyInput.name = 'item_qty[' + item.id + ']';
          qtyInput.className = 'form-control text-center py-1 px-2 rent-item-qty';
          qtyInput.setAttribute('data-price', String(item.rent_sabeel));
          qtyInput.min = '0';
          qtyInput.value = itemQty[item.id] || '0';
          qtyInput.style.height = 'auto';
          
          qtyInput.addEventListener('change', function() {
            if (Number(qtyInput.value) < 0) qtyInput.value = '0';
            updateLaagatRentCard();
          });
          qtyInput.addEventListener('input', function() {
            if (Number(qtyInput.value) < 0) qtyInput.value = '0';
            updateLaagatRentCard();
          });
          
          inputDiv.appendChild(qtyInput);
          itemRow.appendChild(inputDiv);
          itemsList.appendChild(itemRow);
        });
        
        box.appendChild(itemsSection);
      }
    } else {
      // Add bifurcation if present
      var jAmt = Number(jamaatAmount);
      var sAmt = Number(sarkaarAmount);
      if (!isFinite(jAmt)) jAmt = 0;
      if (!isFinite(sAmt)) sAmt = 0;

      // Default jAmt fallback to n if both are 0 but total amount > 0
      if (jAmt === 0 && sAmt === 0 && n > 0) {
        jAmt = n;
      }

      var splitDiv = document.createElement('div');
      splitDiv.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded border text-muted small';

      var jmtPart = document.createElement('div');
      jmtPart.innerHTML = '<strong>Jmt. Laagat:</strong> ' + formatInrCurrency(jAmt);
      splitDiv.appendChild(jmtPart);

      var sarPart = document.createElement('div');
      sarPart.innerHTML = '<strong>Sar. Laagat:</strong> ' + formatInrCurrency(sAmt);
      splitDiv.appendChild(sarPart);

      box.appendChild(splitDiv);
    }

    // Add invoice creation text
    var invoiceNote = document.createElement('div');
    invoiceNote.className = 'alert alert-warning mt-2 mb-0 small py-2';
    if (chargeType === 'laagat') {
      invoiceNote.innerHTML = '<i class="fa fa-info-circle mr-1"></i> <strong>Note:</strong> An invoice will be created for the member for Laagat.';
    } else {
      invoiceNote.innerHTML = '<i class="fa fa-info-circle mr-1"></i> <strong>Note:</strong> An invoice will be created for the member for Rent & Deposit.';
    }
    box.appendChild(invoiceNote);

    if (rawFieldName) {
      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = rawFieldName;
      hidden.value = String(n);
      box.appendChild(hidden);
    }
  }

  function normalizeFieldName(label) {
    // keep consistent with this view's name generation ( /? -> '-')
    return (label || '').toString().toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
  }

  function fetchLaagatRentAmount(razaTypeId, venueOptionId = '', thaalCount = 1, itemQty = {}) {
    let url = '<?= base_url('accounts/get_laagat_rent_amount') ?>' + '?raza_type_id=' + encodeURIComponent(razaTypeId);
    if (venueOptionId) {
      url += '&venue_option_id=' + encodeURIComponent(venueOptionId);
    }
    if (thaalCount) {
      url += '&thaal_count=' + encodeURIComponent(thaalCount);
    }
    for (let id in itemQty) {
      url += '&item_qty[' + encodeURIComponent(id) + ']=' + encodeURIComponent(itemQty[id]);
    }
    return fetch(url, {
        credentials: 'same-origin'
      })
      .then(function(r) {
        return r.json();
      });
  }

  console.log(razas)

  function updateLaagatRentCard() {
    let selectedRazaType = document.getElementById('raza-type').value;
    if (!selectedRazaType) return;

    let venueEl = document.querySelector('[name="venue"]');
    let venueOptionId = venueEl ? venueEl.value : '';

    let thaalCountInput = document.querySelector('[name="approximate-thaal-count"]') || 
                          document.querySelector('[name="approximate_thaal_count"]') ||
                          document.querySelector('[name="approximate-items-count"]') ||
                          document.querySelector('[name="approximate_items_count"]') ||
                          document.querySelector('[name="number-of-items"]') ||
                          document.querySelector('[name="number_of_items"]') ||
                          document.querySelector('[name="approximate-thaal-items-count"]') ||
                          document.querySelector('[name="approximate_thaal_items_count"]') ||
                          document.querySelector('[name="approximate-number-of-items"]') ||
                          document.querySelector('[name="approximate_number_of_items"]') ||
                          document.querySelector('[name="items-count"]') ||
                          document.querySelector('[name="items_count"]');
    let thaalCount = thaalCountInput ? (thaalCountInput.value || 1) : 1;

    let itemQty = {};
    document.querySelectorAll('.rent-item-qty').forEach(function(input) {
      let match = input.name.match(/item_qty\[(\d+)\]/);
      if (match) {
        itemQty[match[1]] = input.value || '0';
      }
    });

    fetchLaagatRentAmount(selectedRazaType, venueOptionId, thaalCount, itemQty)
      .then(function(resp) {
        // Try to find laagatRentInput in container
        let laagatRentInput = null;
        let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
        if (selectedRaza) {
          selectedRaza.fields.forEach(field => {
            if (isLaagatRentAmountField(field.name)) {
              let fieldName1 = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
              let fieldName2 = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
              laagatRentInput = document.querySelector(`[name="${fieldName1}"]`) || document.querySelector(`[name="${fieldName2}"]`);
            }
          });
        }

        let grp;
        let rawName;
        if (laagatRentInput) {
          grp = closestFormGroup(laagatRentInput) || laagatRentInput.parentNode;
          rawName = laagatRentInput.getAttribute('name') || normalizeFieldName('Laagat /Rent Amount');
        } else {
          grp = document.getElementById('laagat-rent-card-group');
          if (!grp) {
            grp = document.createElement('div');
            grp.className = 'form-group';
            grp.id = 'laagat-rent-card-group';
          }
          rawName = normalizeFieldName('Laagat /Rent Amount');
        }

        if (!resp || !resp.success || resp.amount === null || resp.amount === undefined) {
          if (grp) {
            grp.style.display = 'none';
            while (grp.firstChild) grp.removeChild(grp.firstChild);
          }
          if (laagatRentInput) {
            laagatRentInput.value = '0';
          }
          return;
        }

        if (grp) {
          grp.style.display = '';
          if (!laagatRentInput) {
            let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
            if (dynamicFieldsContainer && !dynamicFieldsContainer.contains(grp)) {
              dynamicFieldsContainer.appendChild(grp);
            }
          }
        }

        renderLaagatRentInfo(grp, rawName, resp.charge_type, resp.title || '', resp.amount, resp.jamaat_amount, resp.sarkaar_amount, resp.deposit_amount || 0, resp.items || [], itemQty);
      })
      .catch(function(err) {
        console.error('Error fetching rent amount:', err);
      });
  }

  function updateFormFields() {
    let selectedRazaType = document.getElementById('raza-type').value;
    let selectedRaza = razas.find(raza => raza.id == selectedRazaType);
    let lowerelmentid = document.getElementById('lowerbutton')
    lowerelmentid.style.display = 'block'
    if (selectedRaza) {
      let dynamicFieldsContainer = document.getElementById('dynamic-fields-container');
      dynamicFieldsContainer.innerHTML = '';

      let laagatRentInput = null;

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

        if (isLaagatRentAmountField(field.name) && inputElement) {
          laagatRentInput = inputElement;
        }

        fieldContainer.appendChild(label);
        fieldContainer.appendChild(inputElement);
        dynamicFieldsContainer.appendChild(fieldContainer);
      });

      if (laagatRentInput && selectedRazaType) {
        laagatRentInput.readOnly = true;
      }

      updateLaagatRentCard();
    }
  }

  document.addEventListener('change', function(e) {
    if (e.target && e.target.name === 'venue') {
      updateLaagatRentCard();
    }
  });

  document.addEventListener('input', function(e) {
    if (e.target && (
      e.target.name === 'approximate-thaal-count' || e.target.name === 'approximate_thaal_count' ||
      e.target.name === 'approximate-items-count' || e.target.name === 'approximate_items_count' ||
      e.target.name === 'number-of-items' || e.target.name === 'number_of_items' ||
      e.target.name === 'approximate-thaal-items-count' || e.target.name === 'approximate_thaal_items_count' ||
      e.target.name === 'approximate-number-of-items' || e.target.name === 'approximate_number_of_items' ||
      e.target.name === 'items-count' || e.target.name === 'items_count'
    )) {
      updateLaagatRentCard();
    }
  });
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
    document.getElementById('dues-modal').style.display = 'flex';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
  function hideDuesModal() {
    document.getElementById('dues-modal').style.display = 'none';
    var bd = document.getElementById('dues-backdrop');
    if (bd) bd.style.display = 'none';
    document.body.style.overflow = '';
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
          + '<tr><td>Ekram Fund</td><td class="text-right">' + coloredAmount(d.ekram_due || 0) + '</td></tr>'
          + '<tr><td>Wajebaat</td><td class="text-right">' + coloredAmount(d.wajebaat_due || 0) + '</td></tr>'
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