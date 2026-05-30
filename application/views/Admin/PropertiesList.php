<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .properties-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 230, 234, 0.8);
        border-radius: 12px;
        box-shadow: 0 8px 32px 0 rgba(173, 126, 5, 0.05);
    }
    
    .table-container {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .btn-gold {
        background-color: #ad7e05;
        color: white;
        border: none;
    }
    
    .btn-gold:hover {
        background-color: #8c6503;
        color: white;
    }
    
    .toast-message {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #28a745;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 9999;
        display: none;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s, slideOut 0.3s 2.7s;
    }

    @keyframes slideIn {
        from { transform: translateX(120%); }
        to { transform: translateX(0); }
    }

    @keyframes slideOut {
        from { transform: translateX(0); }
        to { transform: translateX(120%); }
    }

    .sort-icons {
        display: inline-block;
        margin-left: 5px;
        cursor: pointer;
        vertical-align: middle;
    }

    .sort-icons i {
        display: block;
        font-size: 10px;
        margin: 0;
        line-height: 1;
        color: #333;
    }

    th {
        white-space: nowrap;
        background-color: #fef7e6;
        color: #ad7e05 !important;
        font-weight: 600;
    }
</style>

<div id="toast-message" class="toast-message">Successfully Saved</div>

<div class="margintopcontainer pt-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="<?php echo site_url('admin/laagat'); ?>" class="btn btn-outline-secondary" aria-label="Back to Laagat / Rent Menu">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h4 class="heading mb-0 text-center flex-grow-1">Properties Management</h4>
            <div style="width: 42px;"></div> <!-- placeholder to balance back button -->
        </div>

        <div class="card properties-card p-4">
            <div class="row align-items-center mb-4 g-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search properties..." aria-label="Search" id="propertySearchInput">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-left-0">
                                <i class="fa fa-search text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-md-right">
                    <button class="btn btn-gold px-4" onclick="addNewPropertyRow();">
                        <i class="fa fa-plus-circle mr-2"></i>Add Property
                    </button>
                </div>
            </div>

            <div class="table-responsive table-container">
                <table class="table table-bordered text-center mb-0" id="propertiesTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">S.No.
                                <span class="sort-icons" onclick="sortTable(0)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th>Property Name
                                <span class="sort-icons" onclick="sortTable(1)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th style="width: 200px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($properties)): ?>
                            <tr id="no-properties-row">
                                <td colspan="3" class="text-muted py-4">No properties added yet. Click "Add Property" to begin.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($properties as $key => $p): ?>
                                <tr id="row_<?php echo $p['id']; ?>">
                                    <td class="sno align-middle"><?php echo $key + 1; ?></td>
                                    <td class="align-middle text-left px-4">
                                        <span id="propertyName_<?php echo $p['id']; ?>" class="font-weight-500"><?php echo htmlspecialchars($p['name']); ?></span>
                                        <input type="text" id="editPropertyName_<?php echo $p['id']; ?>" class="form-control" style="display: none;" value="<?php echo htmlspecialchars($p['name']); ?>">
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="button" id="editBtn_<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary mr-2 px-3" onclick="editRow(<?php echo $p['id']; ?>);" title="Edit Name">
                                                <i class="fa-solid fa-pencil-alt mr-1"></i> Edit
                                            </button>
                                            <button type="button" id="submitBtn_<?php echo $p['id']; ?>" class="btn btn-sm btn-success mr-2 px-3" style="display: none;" onclick="submitRow(<?php echo $p['id']; ?>);">
                                                Save
                                            </button>
                                            <a href="<?php echo site_url('admin/delete_property/' . $p['id']); ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Are you sure you want to delete this property? This cannot be undone.');" title="Delete Property">
                                                <i class="fa-solid fa-trash-can mr-1"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function showToast(msg, isError = false) {
        const toast = document.getElementById('toast-message');
        if (!toast) return;
        toast.textContent = msg;
        toast.style.backgroundColor = isError ? '#dc3545' : '#28a745';
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    <?php if (!empty($flash_success)): ?>
        showToast(<?php echo json_encode($flash_success); ?>);
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        showToast(<?php echo json_encode($flash_error); ?>, true);
    <?php endif; ?>

    let addingRowActive = false;

    function addNewPropertyRow() {
        if (addingRowActive) return;
        addingRowActive = true;

        const noProp = document.getElementById('no-properties-row');
        if (noProp) noProp.style.display = 'none';

        const tbody = document.querySelector('#propertiesTable tbody');
        const tr = document.createElement('tr');
        tr.id = 'temp_new_row';
        tr.innerHTML = `
            <td class="align-middle"></td>
            <td class="align-middle text-left px-4">
                <input type="text" id="new_property_name" class="form-control" placeholder="Enter property name..." required>
            </td>
            <td class="align-middle">
                <div class="d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-sm btn-success mr-2 px-3" onclick="submitNewProperty();">Submit</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="cancelNewPropertyRow();">Cancel</button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
        document.getElementById('new_property_name').focus();
    }

    function cancelNewPropertyRow() {
        const tempRow = document.getElementById('temp_new_row');
        if (tempRow) tempRow.parentNode.removeChild(tempRow);
        addingRowActive = false;

        const tbody = document.querySelector('#propertiesTable tbody');
        if (tbody.querySelectorAll('tr').length === 0) {
            const noProp = document.getElementById('no-properties-row');
            if (noProp) noProp.style.display = '';
        }
    }

    function submitNewProperty() {
        const nameInput = document.getElementById('new_property_name');
        const name = (nameInput.value || '').trim();
        if (!name) {
            alert('Property name is required.');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);

        fetch('<?php echo site_url('admin/add_property'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                showToast('Property added successfully');
                window.location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(() => {
            alert('Failed to submit request.');
        });
    }

    function editRow(rowId) {
        const span = document.getElementById('propertyName_' + rowId);
        const input = document.getElementById('editPropertyName_' + rowId);
        const editBtn = document.getElementById('editBtn_' + rowId);
        const submitBtn = document.getElementById('submitBtn_' + rowId);

        if (input.style.display === 'none') {
            span.style.display = 'none';
            input.style.display = 'block';
            editBtn.innerHTML = '<i class="fa-solid fa-xmark mr-1"></i> Cancel';
            editBtn.classList.remove('btn-outline-primary');
            editBtn.classList.add('btn-outline-secondary');
            submitBtn.style.display = 'inline-block';
            input.focus();
        } else {
            span.style.display = 'inline';
            input.style.display = 'none';
            input.value = span.textContent;
            editBtn.innerHTML = '<i class="fa-solid fa-pencil-alt mr-1"></i> Edit';
            editBtn.classList.remove('btn-outline-secondary');
            editBtn.classList.add('btn-outline-primary');
            submitBtn.style.display = 'none';
        }
    }

    function submitRow(rowId) {
        const input = document.getElementById('editPropertyName_' + rowId);
        const newName = (input.value || '').trim();
        if (!newName) {
            alert('Property name is required.');
            return;
        }

        const formData = new FormData();
        formData.append('rowId', rowId);
        formData.append('propertyName', newName);

        fetch('<?php echo site_url('admin/update_property_name'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                showToast('Property updated successfully');
                document.getElementById('propertyName_' + rowId).textContent = newName;
                editRow(rowId);
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(() => {
            alert('Failed to save update.');
        });
    }

    // Search filter
    document.getElementById('propertySearchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#propertiesTable tbody tr:not(#temp_new_row):not(#no-properties-row)');
        
        rows.forEach(row => {
            const nameCell = row.querySelector('td:nth-child(2)');
            if (nameCell) {
                const text = nameCell.textContent || nameCell.innerText;
                row.style.display = text.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
            }
        });
    });

    // Sorting functionality
    function sortTable(columnIndex) {
        const table = document.querySelector("#propertiesTable tbody");
        const rowsArray = Array.from(table.querySelectorAll("tr:not(#temp_new_row):not(#no-properties-row)"));
        if (rowsArray.length === 0) return;

        const isAscending = this.isAsc = !this.isAsc;

        const sortedRows = rowsArray.sort((a, b) => {
            const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();
            const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();

            if (columnIndex === 0) { // numbers
                return (parseInt(cellA) - parseInt(cellB)) * (isAscending ? 1 : -1);
            } else {
                return cellA.localeCompare(cellB) * (isAscending ? 1 : -1);
            }
        });

        sortedRows.forEach(row => table.appendChild(row));
    }
</script>
