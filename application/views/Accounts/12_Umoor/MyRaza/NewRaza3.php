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
                    <form id="raza-form" class="main-form" action="<?= base_url("accounts/submit_raza") ?>"
                        method="post">

                        <?php if ($value === 'Private-Event' || $value === 'Public-Event'): ?>
                            <div class="form-group">
                                <label class="col-form-label requiredField">My Sabil Dues Are Paid<span
                                        class="asteriskField">*</span></label>
                                <div class="">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="sabil" value="0"
                                                required="required"> No
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="sabil" value="1"
                                                required="required"> Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label requiredField">My FMB Dues Are Paid<span
                                        class="asteriskField">*</span></label>
                                <div class="">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="fmb" value="0"
                                                required="required"> No
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="fmb" value="1"
                                                required="required"> Yes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                            <!--    <label class="col-form-label requiredField">I Have Contributed in FMB Smart Kitchen Tameer<span class="asteriskField">*</span></label>-->
                            <!--    <div class="">-->
                            <!--        <div class="form-check">-->
                            <!--            <label class="form-check-label">-->
                            <!--                <input type="radio" class="form-check-input" name="fmbtameer" value="0" required="required"> No-->
                            <!--            </label>-->
                            <!--        </div>-->
                            <!--        <div class="form-check">-->
                            <!--            <label class="form-check-label">-->
                            <!--                <input type="radio" class="form-check-input" name="fmbtameer" value="1" required="required"> Yes-->
                            <!--            </label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
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

                        <div id="lowerbutton" class="lowerbutton">
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

        selectedRaza.fields.forEach(field => {
            let fieldName = field.name.toLowerCase().replace(/\s/g, '-').replace(/[()\/?]/g, '_');
            let fieldValue = data[fieldName] || '';

            let group = document.createElement('div');
            group.className = 'form-group';

            let label = document.createElement('label');
            label.className = 'col-form-label' + (field.required ? ' requiredField' : '');
            label.setAttribute('for', 'id_' + fieldName);
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

    <?php if (!empty($razaId)): ?>
        window.addEventListener('DOMContentLoaded', function () {
            document.getElementById('raza-type').value = "<?= $razaId ?>";
            window.razaPreFillData = <?= json_encode($razaData ?? []) ?>;
            updateFormFields();
        });
    <?php endif; ?>
</script>

<div id="toast-message" class="toast-message">done</div>