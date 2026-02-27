<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container margintopcontainer pt-5">
  <a href="<?php echo site_url('anjuman/laagat_rent'); ?>" class="btn btn-outline-secondary mb-3" aria-label="Back to Manage Laagat/Rent"><i class="fa-solid fa-arrow-left"></i></a>
  <h4 class="heading text-center mb-4"><?php echo !empty($form['id']) ? 'Edit Laagat/Rent Form' : 'Create Laagat/Rent Form'; ?></h4>

  <?php if (!empty($flash_success)) : ?>
    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($flash_success); ?></div>
  <?php endif; ?>
  <?php if (!empty($flash_error)) : ?>
    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($flash_error); ?></div>
    <script>
      (function() {
        var msg = <?php echo json_encode((string)$flash_error); ?>;
        if (msg) {
          window.setTimeout(function() {
            alert(msg);
          }, 0);
        }
      })();
    </script>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">

      <form method="post" action="<?php echo site_url('anjuman/laagat_rent_save'); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($form['id']) ? (string)$form['id'] : ''); ?>" />
        <div class="mb-3">
          <label class="form-label" for="lr_title">Title:</label>
          <input
            type="text"
            class="form-control"
            id="lr_title"
            name="title"
            value="<?php echo htmlspecialchars(isset($form['title']) ? (string)$form['title'] : ''); ?>"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="lr_hijri_year">Hijri Year</label>
          <select class="custom-select" id="lr_hijri_year" name="hijri_year" required>
            <option value="">Select Hijri Year</option>
            <?php if (!empty($hijri_year_options) && is_array($hijri_year_options)) : ?>
              <?php
                $sorted_hijri_year_options = $hijri_year_options;
                usort($sorted_hijri_year_options, function ($a, $b) {
                  $aStr = (string)$a;
                  $bStr = (string)$b;
                  $aNum = 0;
                  $bNum = 0;
                  if (preg_match('/^\s*(\d+)/', $aStr, $m)) {
                    $aNum = (int)$m[1];
                  }
                  if (preg_match('/^\s*(\d+)/', $bStr, $m)) {
                    $bNum = (int)$m[1];
                  }
                  if ($aNum === $bNum) {
                    return strcmp($bStr, $aStr);
                  }
                  return ($aNum < $bNum) ? 1 : -1;
                });
              ?>
              <?php foreach ($sorted_hijri_year_options as $yr) : ?>
                <?php $selected = (isset($form['hijri_year']) && (string)$form['hijri_year'] === (string)$yr) ? 'selected' : ''; ?>
                <option value="<?php echo htmlspecialchars((string)$yr); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars((string)$yr); ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="lr_charge_type">Charge Type</label>
          <select class="custom-select" id="lr_charge_type" name="charge_type" required>
            <option value="">Select Charge Type</option>
            <?php $ct = isset($form['charge_type']) ? (string)$form['charge_type'] : ''; ?>
            <option value="laagat" <?php echo ($ct === 'laagat') ? 'selected' : ''; ?>>Laagat (Non-Event Raza - Umoor Raza)</option>
            <option value="rent" <?php echo ($ct === 'rent') ? 'selected' : ''; ?>>Rent (Kaaraj Raza - Private Event Raza)</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="lr_amount">Amount:</label>
            <input
            type="number"
            min="0"
            class="form-control"
            id="lr_amount"
            name="amount"
            value="<?php echo htmlspecialchars(isset($form['amount']) ? (string)$form['amount'] : ''); ?>"
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="lr_raza_type_name">Applicable Raza Categories</label>
          <?php
            $existingRazaTypes = [];
            if (isset($form['raza_types']) && is_array($form['raza_types'])) {
              $existingRazaTypes = $form['raza_types'];
            } else if (!empty($form['raza_type_id']) && !empty($form['raza_type_name'])) {
              $existingRazaTypes = [[
                'id' => (int)$form['raza_type_id'],
                'name' => (string)$form['raza_type_name'],
              ]];
            }
          ?>

          <input
            type="text"
            class="form-control"
            id="lr_raza_type_name"
            list="lr_raza_type_list"
            autocomplete="off"
            value=""
            placeholder="Start typing..."
          />
          <datalist id="lr_raza_type_list"></datalist>

          <div id="lr_raza_selected" class="mt-2 d-flex flex-wrap"></div>

          <div id="lr_raza_hidden_inputs"></div>

          <?php if (!empty($existingRazaTypes)) : ?>
            <script>
              window.__lr_existingRazaTypes = <?php echo json_encode(array_values($existingRazaTypes)); ?>;
            </script>
          <?php endif; ?>
          
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <?php if (!empty($form['id'])) : ?>
          <a class="btn btn-outline-secondary ml-2" href="<?php echo site_url('anjuman/laagat_rent'); ?>">Cancel</a>
        <?php endif; ?>
      </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    var form = document.querySelector('form[action="<?php echo site_url('anjuman/laagat_rent_save'); ?>"]');
    var typeSelect = document.getElementById('lr_charge_type');
    var hijriYearSelect = document.getElementById('lr_hijri_year');
    var inputName = document.getElementById('lr_raza_type_name');
    var dataList = document.getElementById('lr_raza_type_list');
    var selectedWrap = document.getElementById('lr_raza_selected');
    var hiddenInputsWrap = document.getElementById('lr_raza_hidden_inputs');

    var dupCheckUrl = '<?php echo site_url('anjuman/laagat_rent_check_duplicate'); ?>';

    if (!form || !typeSelect || !hijriYearSelect || !inputName || !dataList || !selectedWrap || !hiddenInputsWrap) return;

    var nameToId = {};
    var currentFetch = null;
    var selectedIds = {};

    function postFormForDupCheck() {
      if (!dupCheckUrl) return Promise.resolve({ success: true, exists: false });

      var hy = (hijriYearSelect.value || '').trim();
      var ct = (typeSelect.value || '').trim();
      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      if (!hy || !ct || !inputs || inputs.length === 0) {
        return Promise.resolve({ success: true, exists: false });
      }

      var fd = new FormData(form);

      if (window.$ && $.ajax) {
        return new Promise(function(resolve) {
          $.ajax({
            url: dupCheckUrl,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) { resolve(resp || { success: true, exists: false }); },
            error: function() { resolve({ success: true, exists: false }); }
          });
        });
      }

      if (window.fetch) {
        return window.fetch(dupCheckUrl, {
          method: 'POST',
          body: fd,
          credentials: 'same-origin'
        })
          .then(function(r) { return r.json(); })
          .catch(function() { return { success: true, exists: false }; });
      }

      return Promise.resolve({ success: true, exists: false });
    }

    form.addEventListener('submit', function(e) {
      if (form.__lrBypassSubmit) return;
      e.preventDefault();

      var saveBtn = form.querySelector('button[type="submit"]');
      if (saveBtn) {
        saveBtn.disabled = true;
      }

      postFormForDupCheck().then(function(resp) {
        var exists = resp && resp.exists;
        if (exists) {
          var msg = (resp && resp.message) ? String(resp.message) : "Raza already exist. You can't create same Raza.";
          try { alert(msg); } catch (ex) {}
          if (saveBtn) saveBtn.disabled = false;
          return;
        }

        form.__lrBypassSubmit = true;
        form.submit();
      });
    });

    function initSelectedFromDom() {
      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      for (var i = 0; i < inputs.length; i++) {
        var v = parseInt(inputs[i].value, 10);
        if (v > 0) selectedIds[v] = true;
      }
    }

    function clearOptions() {
      nameToId = {};
      while (dataList.firstChild) dataList.removeChild(dataList.firstChild);
    }

    function clearSelected() {
      selectedIds = {};
      while (selectedWrap.firstChild) selectedWrap.removeChild(selectedWrap.firstChild);
      while (hiddenInputsWrap.firstChild) hiddenInputsWrap.removeChild(hiddenInputsWrap.firstChild);
    }

    function addSelected(id, name) {
      id = parseInt(id, 10);
      if (!id || id <= 0) return;
      if (selectedIds[id]) return;
      selectedIds[id] = true;

      var badge = document.createElement('span');
      badge.className = 'badge badge-primary mr-2 mb-2 lr-raza-badge';
      badge.setAttribute('data-id', String(id));
      badge.appendChild(document.createTextNode(name));

      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'close text-white lr-raza-remove';
      btn.setAttribute('aria-label', 'Remove');
      btn.setAttribute('data-remove-id', String(id));

      var x = document.createElement('span');
      x.setAttribute('aria-hidden', 'true');
      x.innerHTML = '&times;';
      btn.appendChild(x);
      badge.appendChild(btn);
      selectedWrap.appendChild(badge);

      var hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'raza_type_ids[]';
      hidden.value = String(id);
      hiddenInputsWrap.appendChild(hidden);
    }

    function removeSelected(id) {
      id = parseInt(id, 10);
      if (!id || id <= 0) return;
      if (!selectedIds[id]) return;
      delete selectedIds[id];

      var badge = selectedWrap.querySelector('span[data-id="' + id + '"]');
      if (badge && badge.parentNode) badge.parentNode.removeChild(badge);

      var inputs = hiddenInputsWrap.querySelectorAll('input[name="raza_type_ids[]"]');
      for (var i = inputs.length - 1; i >= 0; i--) {
        if (parseInt(inputs[i].value, 10) === id) {
          inputs[i].parentNode.removeChild(inputs[i]);
        }
      }
    }

    function renderOptions(items) {
      clearOptions();
      if (!items || !items.length) return;
      for (var i = 0; i < items.length; i++) {
        var it = items[i];
        if (!it || !it.name) continue;
        nameToId[it.name] = it.id;
        var opt = document.createElement('option');
        opt.value = it.name;
        dataList.appendChild(opt);
      }
    }

    function fetchOptions(term) {
      var chargeType = typeSelect.value || '';
      if (!chargeType) {
        clearOptions();
        inputName.value = '';
        return;
      }
      var url = '<?php echo site_url('anjuman/laagat_rent_raza_categories'); ?>' + '?charge_type=' + encodeURIComponent(chargeType) + '&term=' + encodeURIComponent(term || '');
      if (currentFetch && currentFetch.abort) {
        try { currentFetch.abort(); } catch (e) {}
      }
      if (window.$ && $.getJSON) {
        currentFetch = $.getJSON(url, function(resp) {
          if (resp && resp.success) {
            renderOptions(resp.items || []);
          }
        });
      } else if (window.fetch) {
        window.fetch(url, { credentials: 'same-origin' })
          .then(function(r) { return r.json(); })
          .then(function(resp) { if (resp && resp.success) renderOptions(resp.items || []); });
      }
    }

    typeSelect.addEventListener('change', function() {
      inputName.value = '';
      clearSelected();
      fetchOptions('');
    });

    inputName.addEventListener('input', function() {
      var term = inputName.value || '';
      fetchOptions(term);
    });

    inputName.addEventListener('change', function() {
      var name = (inputName.value || '').trim();
      if (name && nameToId[name]) {
        addSelected(nameToId[name], name);
        inputName.value = '';
        clearOptions();
      }
    });

    selectedWrap.addEventListener('click', function(e) {
      var t = e.target;
      if (!t) return;
      var btn = t.closest ? t.closest('[data-remove-id]') : null;
      if (!btn) {
        while (t && t !== selectedWrap) {
          if (t.getAttribute && t.getAttribute('data-remove-id')) { btn = t; break; }
          t = t.parentNode;
        }
      }
      if (btn && btn.getAttribute) {
        var rid = btn.getAttribute('data-remove-id');
        removeSelected(rid);
      }
    });

    if (window.__lr_existingRazaTypes && Array.isArray(window.__lr_existingRazaTypes)) {
      for (var i = 0; i < window.__lr_existingRazaTypes.length; i++) {
        var it = window.__lr_existingRazaTypes[i];
        if (it && it.id && it.name) {
          addSelected(it.id, it.name);
        }
      }
    } else {
      initSelectedFromDom();
    }
    fetchOptions('');
  })();
</script>

<style>
  #lr_raza_selected .badge { font-weight: 600; }
  #lr_raza_selected .lr-raza-badge {
    display: inline-flex;
    align-items: center;
    padding: .45rem .6rem;
    font-size: 0.95rem;
    line-height: 1.1;
    border-radius: .25rem;
  }
  #lr_raza_selected .lr-raza-remove {
    float: none;
    opacity: 0.9;
    text-shadow: none;
    margin-left: .5rem;
    padding: 0;
    line-height: 1;
    font-size: 1.2rem;
  }
  #lr_raza_selected .lr-raza-remove:hover { opacity: 1; }
</style>
