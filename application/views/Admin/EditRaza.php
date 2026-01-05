<style>
    .query-form {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border: 1px solid lightgrey;
        padding: 2rem;
        width: 400px;
        display: none;
        z-index: 12;
        border-radius: 5px;
        height:calc(100vh - 5rem);
        margin-top:2rem;
        overflow-y:auto;

        @media screen and (max-width:500px) {
            max-width: 350px;
            @media screen and (max-width: 374px) {
                max-width: 250px;
            }
        }
    }

    #product-overlay {
        display: none;
        top: 0;
        position: fixed;
        height: 100vh;
        width: 100vw;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10;
    }

    .toast-message {
        position: fixed;
        top: 10;
        right: 0;
        background-color: #4CAF50;
        color: #fff;
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

    .submit {
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;

        @media screen and (max-width:768px) {
            flex-direction: column-reverse;
            gap: 2rem;
        }
    }

    .addbtn {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;

        @media screen and (max-width:768px) {
            flex-direction: column-reverse;
            gap: 2rem;
        }
    }

    .fields_btn {
        cursor: pointer;
        color: #ad7e05;
    }

    .fields_btn:hover {
        text-decoration: underline;
    }

    .option-table {
        margin-top: 2rem;
        margin-bottom: 2rem;
        box-sizing: none;
    }

    .table-container .drag-handle {
        cursor: grab;
        color: #6c757d;
        user-select: none;
        font-size: 18px;
        line-height: 1;
    }
    .table-container tr.dragging {
        opacity: .6;
    }
</style>
<div class="margintopcontainer">
    <div class="container pt-5">
        <div class="d-flex justify-content-start mt-5">
            <a href="<?php echo base_url('admin/razalist'); ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <p class="h1 text-center mt-5">Raza Fields</p>
        <p class="h4 text-center mt-5" style="color:brown;">
            <?php echo $raza['name'] ?>
        </p>
        <p class="h4 text-center" style="color:#896952;">
            (<?php echo $raza['umoor'] ?>)
        </p>
        <div class="addbtn">
            <button class="btn btn-success w100percent-xs mbm-xs" onclick="AddField();">Add</button>
        </div>
        <div class="table-responsive mt-5 mb-5">
            <div class="table-container mb-5">
                <table class="table table-bordered text-center mb-5">
                    <thead>
                        <tr>
                            <th style="width:44px"></th>
                            <th class="sno">Name</th>
                            <th class="created">Type</th>
                            <th class="raza">Required</th>
                            <th class="date">Options</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <tbody id="fields_tbody">
                        <?php $i = 0;
                        foreach ($raza['fields']['fields'] as $key => $r) { ?>
                            <tr draggable="true" data-index="<?php echo $i ?>" data-fieldname="<?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?>">
                                <td><span class="drag-handle" title="Drag to reorder">☰</span></td>
                                <td>
                                    <?php echo $r['name'] ?>
                                </td>
                                <td>
                                    <?php echo $r['type'] ?>
                                </td>
                                <td>
                                    <?php if ($r['required'] == true) {
                                        echo 'True';
                                    } else {
                                        echo 'False';
                                    } ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['options'])) { ?>
                                        <a class="fields_btn" data-toggle="tooltip" data-placement="bottom" title="View Options" onclick="handlviewoptionsFromRow(this);">Options</a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-form-row"
                                    data-toggle="tooltip" data-placement="bottom" title="Remove Field"
                                        onclick="remove('<?php echo $r['name'] ?>');">
                                        <i class="fa-solid fa-circle-xmark"></i></button>
                                </td>
                            </tr>
                            <?php $i++;
                        } ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Toast message element -->
<div id="toast-message" class="toast-message">Saved Successfully</div>

<!-- Modal form to edit options -->
<div id="product-overlay"></div>
<div id="fields" class="query-form">
    <p class="h3 text-center">Options</p>
    <div class="addbtn">
        <button class="btn btn-success w100percent-xs mbm-xs" onclick="Addoption();">Add</button>
    </div>
    <form id="options-form">
        <input type="hidden" name="raza-id" id="raza-id">
        <input type="hidden" name="option-id" id="option-id">
        <table id="options-table" class="table table-bordered option-table"></table>
    </form>
    <div class="submit">
        <button class="btn btn-danger w100percent-xs mbm-xs" onclick="cancelview();">Cancel</button>
        <button class="btn btn-warning w100percent-xs mbm-xs" onclick="removeOption();">Remove</button>
        <button type="button" class="btn btn-primary w100percent-xs mbm-xs" onclick="Confirm();">Confirm</button>
    </div>
</div>

<script>
    let raza = <?php echo json_encode($raza); ?>;
    let current_option_index;

    function handlviewoptionsFromRow(el) {
        const tr = el.closest('tr');
        if (!tr) return;
        const idx = Number(tr.dataset.index || 0);
        handlviewoptions(idx);
    }

    function handlviewoptions(index) {
        current_option_index = index;
        document.getElementById("fields").style.display = "block";
        document.getElementById("product-overlay").style.display = "block";

        const table = document.createElement('tbody');
        table.id = "opt-table";
        let tabledata = "";

        raza.fields.fields[Number(index)].options.forEach(element => {
            tabledata += `<tr class="form-group"><td><input type="text" class="form-control" name="option_values[]" value="${element.name}"></td></tr>`;
        });

        table.innerHTML = tabledata;
        const t = document.getElementById('options-table');
        t.innerHTML = "";
        t.appendChild(table);
    }

    function cancelview() {
        document.getElementById("fields").style.display = "none";
        document.getElementById("product-overlay").style.display = "none";
    }

    function Confirm() {
        document.getElementById('raza-id').value = raza.id;
        document.getElementById('option-id').value = current_option_index;

        // Submit using native JS event
        document.getElementById("options-form").dispatchEvent(new Event('submit'));
    }

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("options-form");

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            fetch("<?php echo base_url('admin/modifyrazaoption'); ?>", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    showSuccessMessage();
                    window.location.reload()
                    cancelview();
                } else {
                    alert("Error: " + data.error);
                }
            })
            .catch(error => {
                alert("Request failed");
                console.error(error);
            });
        });

        function showSuccessMessage() {
            const toastMessage = document.getElementById("toast-message");
            toastMessage.style.display = "block";
            setTimeout(() => {
                toastMessage.style.display = "none";
            }, 2000);
        }
    });

    function Addoption() {
        const optiontable = document.getElementById('opt-table');
        const tr = document.createElement('tr');
        tr.className = "form-group";
        tr.innerHTML = `<td><input type="text" class="form-control" name="option_values[]"></td>`;
        optiontable.appendChild(tr);
    }

    function removeOption() {
        const optiontable = document.getElementById('opt-table');
        const lastElement = optiontable.lastChild;
        if (lastElement) {
            optiontable.removeChild(lastElement);
        }
    }
</script>


<script>
    function AddField() {
        let dtable = document.getElementById('fields_tbody');
        let tr = document.createElement('tr');
        tr.innerHTML = `
        <td></td>
        <td><input type="text" class="form-control" name="field-name" id="field-name"></td>
        <td>
            <select class="form-control" name="field-type" id="field-type">
                <option value="0">Date</option>
                <option value="1">Text</option>
                <option value="2">Number</option>
                <option value="3">Textarea</option>
                <option value="4">Select</option>
            </select>
        </td>
        <td>
            <select class="form-control" name="field-required" id="field-required">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </td>
        <td></td>
        <td><button type="button" class="btn btn-sm btn-success remove-form-row" onclick="submitForm()">Submit</button></td>
    `;
        dtable.appendChild(tr)
    }
    function submitForm() {
        let fieldname = document.getElementById('field-name').value;
        let fieldtype = document.getElementById('field-type').value;
        let fieldrequired = document.getElementById('field-required').value;

        let formData = new FormData();
        formData.append('fieldname', fieldname);
        formData.append('fieldtype', fieldtype);
        formData.append('fieldrequired', fieldrequired);

        fetch('<?php echo base_url('admin/addField/') . $raza['id'] ?>', {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                window.location.reload()
                return response.json();

            })
            .then(data => {
                // Handle success response
                console.log('Form submitted successfully:', data);
            })
            .catch(error => {
                // Handle error
                console.error('Form submission failed:', error);
            });
    }
    function remove(name){
        let formData = new FormData();
        formData.append('fieldname', name);

        fetch('<?php echo base_url('admin/removeField/') . $raza['id'] ?>', {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                window.location.reload()
                return response.json();

            })
            .then(data => {
                // Handle success response
                console.log('Form submitted successfully:', data);
            })
            .catch(error => {
                // Handle error
                console.error('Form submission failed:', error);
            });
    }

</script>

<script>
    // --- Drag & drop sorting for fields table ---
    (function () {
        const tbody = document.getElementById('fields_tbody');
        if (!tbody) return;

        let draggingRow = null;

        function updateRowIndexesAndRaza() {
            const rows = Array.from(tbody.querySelectorAll('tr[data-fieldname]'));
            // update indexes in DOM
            rows.forEach((r, i) => { r.dataset.index = String(i); });

            // update raza.fields.fields order in memory to keep option indexes aligned
            if (raza && raza.fields && raza.fields.fields && Array.isArray(raza.fields.fields)) {
                const map = {};
                raza.fields.fields.forEach(f => { if (f && f.name) map[f.name] = f; });
                const reordered = [];
                rows.forEach(r => {
                    const nm = r.dataset.fieldname;
                    if (nm && map[nm]) reordered.push(map[nm]);
                });
                // keep any missing at end
                raza.fields.fields.forEach(f => { if (f && f.name && !reordered.find(x => x.name === f.name)) reordered.push(f); });
                raza.fields.fields = reordered;
            }
        }

        function showToast(msg) {
            const el = document.getElementById('toast-message');
            if (!el) return;
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => { el.style.display = 'none'; }, 2000);
        }

        function saveOrder() {
            const rows = Array.from(tbody.querySelectorAll('tr[data-fieldname]'));
            const order = rows.map(r => r.dataset.fieldname);
            const formData = new FormData();
            formData.append('order', JSON.stringify(order));

            fetch('<?php echo base_url('admin/reorderRazaFields/') . $raza['id']; ?>', {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data && data.status) {
                        showToast('Order Saved');
                    } else {
                        alert('Failed to save order');
                    }
                })
                .catch(() => alert('Failed to save order'));
        }

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('tr[draggable="true"]:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        tbody.addEventListener('dragstart', (e) => {
            const tr = e.target.closest('tr[draggable="true"]');
            if (!tr) return;
            draggingRow = tr;
            tr.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        tbody.addEventListener('dragend', () => {
            if (draggingRow) draggingRow.classList.remove('dragging');
            draggingRow = null;
        });

        tbody.addEventListener('dragover', (e) => {
            e.preventDefault();
            const afterElement = getDragAfterElement(tbody, e.clientY);
            if (!draggingRow) return;
            if (afterElement == null) {
                tbody.appendChild(draggingRow);
            } else {
                tbody.insertBefore(draggingRow, afterElement);
            }
        });

        tbody.addEventListener('drop', (e) => {
            e.preventDefault();
            updateRowIndexesAndRaza();
            saveOrder();
        });

        // initial index alignment
        updateRowIndexesAndRaza();
    })();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>