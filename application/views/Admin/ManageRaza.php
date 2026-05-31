<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    /* Styling System - Warm and Elegant Gold/Beige Palette */
    body {
        background-color: #faf8f5;
    }

    .razalist-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 230, 234, 0.8);
        border-radius: 12px;
        box-shadow: 0 8px 32px 0 rgba(173, 126, 5, 0.05);
    }
    
    .table-container {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e6ea;
    }
    
    .btn-gold {
        background-color: #ad7e05;
        color: white;
        border: none;
        font-weight: 500;
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

    /* Custom Badges */
    .status-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .table td {
        vertical-align: middle !important;
    }

    /* Action Buttons */
    .btn-action-group .btn {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
    }
</style>

<div id="toast-message" class="toast-message">Successfully Saved</div>

<div class="margintopcontainer pt-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="<?php echo base_url("admin"); ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h4 class="heading mb-0 text-center flex-grow-1">Raza Forms Manager</h4>
            <div style="width: 42px;"></div> <!-- placeholder to balance back button -->
        </div>

        <div class="card razalist-card p-4">
            <div class="row align-items-center mb-4 g-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search Raza forms..." aria-label="Search" id="razaSearchInput">
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-left-0">
                                <i class="fa fa-search text-muted"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-md-right">
                    <button class="btn btn-gold px-4" onclick="addnewrazatype();">
                        <i class="fa fa-plus-circle mr-2"></i>Create New Form
                    </button>
                </div>
            </div>

            <div class="table-responsive table-container">
                <table class="table table-bordered text-center mb-0" id="razaTable">
                    <thead>
                        <tr>
                            <th class="sno" style="width: 80px;">S.No.
                                <span class="sort-icons" onclick="sortTable(0)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="created">Created
                                <span class="sort-icons" onclick="sortTable(1)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="raza">Raza For
                                <span class="sort-icons" onclick="sortTable(2)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="umoor">Umoor
                                <span class="sort-icons" onclick="sortTable(3)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="approval_status" style="width: 120px;">Status
                                <span class="sort-icons" onclick="sortTable(4)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="action" style="width: 280px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($raza_type as $key => $r) { ?>
                            <tr id="row_<?php echo $r['id']; ?>">
                                <td class="align-middle"><?php echo $key + 1 ?></td>
                                <td class="align-middle text-muted small"><?php echo date('D, d M y @ g:i a', strtotime($r['timestamp'])) ?></td>
                                <td class="align-middle font-weight-500">
                                    <span id="razaName_<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['name']); ?></span>
                                    <input type="text" id="editRazaName_<?php echo $r['id']; ?>" class="form-control"
                                        style="display: none;" value="<?php echo htmlspecialchars($r['name']); ?>">
                                </td>
                                <td class="align-middle">
                                    <span id="umoor_<?php echo $r['id']; ?>" class="text-secondary small font-weight-500"><?php echo htmlspecialchars($r['umoor']); ?></span>
                                    <select id="editUmoor_<?php echo $r['id']; ?>" class="form-control form-control-sm"
                                        style="display: none;">
                                        <option value="Private-Event" <?php echo ($r['umoor'] === 'Private-Event') ? 'selected' : ''; ?>>Private Event</option>
                                        <option value="Public-Event" <?php echo ($r['umoor'] === 'Public-Event') ? 'selected' : ''; ?>>Public Event</option>
                                        <option value="UmoorDeeniyah" <?php echo ($r['umoor'] === 'UmoorDeeniyah') ? 'selected' : ''; ?>>Umoor Deeniyah</option>
                                        <option value="UmoorTalimiyah" <?php echo ($r['umoor'] === 'UmoorTalimiyah') ? 'selected' : ''; ?>>Umoor Talimiyah</option>
                                        <option value="UmoorMarafiqBurhaniyah" <?php echo ($r['umoor'] === 'UmoorMarafiqBurhaniyah') ? 'selected' : ''; ?>>Umoor Marafiq Burhaniyah</option>
                                        <option value="UmoorMaaliyah" <?php echo ($r['umoor'] === 'UmoorMaaliyah') ? 'selected' : ''; ?>>Umoor Maaliyah</option>
                                        <option value="UmoorMawaridBashariyah" <?php echo ($r['umoor'] === 'UmoorMawaridBashariyah') ? 'selected' : ''; ?>>Umoor Mawarid Bashariyah</option>
                                        <option value="UmoorDakheliya" <?php echo ($r['umoor'] === 'UmoorDakheliya') ? 'selected' : ''; ?>>Umoor Dakheliya</option>
                                        <option value="UmoorKharejiyah" <?php echo ($r['umoor'] === 'UmoorKharejiyah') ? 'selected' : ''; ?>>Umoor Kharejiyah</option>
                                        <option value="UmoorIqtesadiyah" <?php echo ($r['umoor'] === 'UmoorIqtesadiyah') ? 'selected' : ''; ?>>Umoor Iqtesadiyah</option>
                                        <option value="UmoorFMB" <?php echo ($r['umoor'] === 'UmoorFMB') ? 'selected' : ''; ?>>Umoor FMB</option>
                                        <option value="UmoorAl-Qaza" <?php echo ($r['umoor'] === 'UmoorAl-Qaza') ? 'selected' : ''; ?>>Umoor Al-Qaza</option>
                                        <option value="UmoorAl-Amlaak" <?php echo ($r['umoor'] === 'UmoorAl-Amlaak') ? 'selected' : ''; ?>>Umoor Al-Amlaak</option>
                                        <option value="UmoorAl-Sehhat" <?php echo ($r['umoor'] === 'UmoorAl-Sehhat') ? 'selected' : ''; ?>>Umoor Al-Sehhat</option>
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <span id="status_<?php echo $r['id']; ?>" class="status-badge <?php echo ($r['active'] == 1) ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo ($r['active'] == 1) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center align-items-center btn-action-group" id="actionBtns_<?php echo $r['id']; ?>">
                                        <a href="<?php echo base_url('admin/manage_edit_raza/') . $r['id'] ?>" class="btn btn-sm btn-outline-primary mr-1" title="Modify Fields">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Fields
                                        </a>
                                        <button type="button" id="editBtn_<?php echo $r['id']; ?>" class="btn btn-sm btn-outline-secondary mr-1" onclick="editRow(<?php echo $r['id']; ?>);" title="Edit Name/Umoor">
                                            <i class="fa-solid fa-pencil-alt mr-1"></i> Edit
                                        </button>
                                        <button type="button" id="statusBtn_<?php echo $r['id']; ?>" 
                                            class="btn btn-sm <?php echo ($r['active'] == 1) ? 'btn-outline-danger' : 'btn-outline-success'; ?> mr-1"
                                            onclick="toggleStatus(<?php echo $r['id']; ?>);" title="<?php echo ($r['active'] == 1) ? 'Disable' : 'Enable'; ?> Raza Type">
                                            <i class="fa-solid <?php echo ($r['active'] == 1) ? 'fa-circle-xmark' : 'fa-circle-check'; ?> mr-1"></i>
                                            <?php echo ($r['active'] == 1) ? 'Disable' : 'Enable'; ?>
                                        </button>
                                        <button type="button" id="submitBtn_<?php echo $r['id']; ?>"
                                            class="btn btn-sm btn-success" style="display: none;"
                                            onclick="submitRow(<?php echo $r['id']; ?>);">
                                            Save
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
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

	let addingRowActive = false;

	function addnewrazatype() {
		if (addingRowActive) return;
		addingRowActive = true;

		let tbody = document.querySelector('#razaTable tbody');
		let tr = document.createElement('tr');
		tr.id = 'temp_add_row';
		tr.innerHTML = `
            <td class="align-middle"></td>
            <td class="align-middle"></td>
            <td class="align-middle"><input type="text" id="razaname" class="form-control form-control-sm" placeholder="Raza form name..." required></td>
            <td class="align-middle">
                <select id="umoor" class="form-control form-control-sm">
                    <option value="Private-Event">Private Event</option>
                    <option value="Public-Event">Public Event</option>
                    <option value="UmoorDeeniyah">Umoor Deeniyah</option>
                    <option value="UmoorTalimiyah">Umoor Talimiyah</option>
                    <option value="UmoorMarafiqBurhaniyah">Umoor Marafiq Burhaniyah</option>
                    <option value="UmoorMaaliyah">Umoor Maaliyah</option>
                    <option value="UmoorMawaridBashariyah">Umoor Mawarid Bashariyah</option>
                    <option value="UmoorDakheliya">Umoor Dakheliya</option>
                    <option value="UmoorKharejiyah">Umoor Kharejiyah</option>
                    <option value="UmoorIqtesadiyah">Umoor Iqtesadiyah</option>
                    <option value="UmoorFMB">Umoor FMB</option>
                    <option value="UmoorAl-Qaza">Umoor Al-Qaza</option>
                    <option value="UmoorAl-Amlaak">Umoor Al-Amlaak</option>
                    <option value="UmoorAl-Sehhat">Umoor Al-Sehhat</option>
                </select>
            </td>
            <td class="align-middle"></td>
            <td class="align-middle">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-success mr-2 px-3" onclick="submitForm()">Submit</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="cancelAddRow()">Cancel</button>
                </div>
            </td>
        `;
		tbody.appendChild(tr);
		document.getElementById('razaname').focus();
	}

	function cancelAddRow() {
		let tr = document.getElementById('temp_add_row');
		if (tr) tr.parentNode.removeChild(tr);
		addingRowActive = false;
	}

	function submitForm() {
		let razaname = document.getElementById('razaname').value.trim();
		let umoor = document.getElementById('umoor').value;
		if (!razaname) {
			alert('Raza name is required.');
			return;
		}

		let formData = new FormData();
		formData.append('raza-name', razaname);
		formData.append('umoor', umoor);

		fetch('<?php echo base_url('admin/addRaza') ?>', {
				method: 'POST',
				body: formData,
			})
			.then(response => {
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}
				return response.json();
			})
			.then(data => {
				if (data && data.status && data.id) {
					showToast('Form created successfully');
					setTimeout(() => {
						window.location.href = '<?php echo base_url("admin/manage_edit_raza/"); ?>' + data.id;
					}, 300);
				} else {
					alert('Failed to submit form.');
				}
			})
			.catch(error => {
				console.error('Form submission failed:', error);
				alert('Failed to submit form.');
			});
	}

	function editRow(rowId) {
		const razaNameSpan = document.getElementById('razaName_' + rowId);
		const umoorSpan = document.getElementById('umoor_' + rowId);
		const razaNameInput = document.getElementById('editRazaName_' + rowId);
		const umoorSelect = document.getElementById('editUmoor_' + rowId);
		const submitBtn = document.getElementById('submitBtn_' + rowId);
		const actionContainer = document.getElementById('actionBtns_' + rowId);
		const editBtn = document.getElementById('editBtn_' + rowId);
		const otherBtns = actionContainer.querySelectorAll('button:not(#submitBtn_' + rowId + '), a');

		if (razaNameInput.style.display === 'none') {
			// Show inputs (Start Editing)
			razaNameSpan.style.display = 'none';
			umoorSpan.style.display = 'none';
			razaNameInput.style.display = 'block';
			umoorSelect.style.display = 'block';
			submitBtn.style.display = 'inline-block';
			
			editBtn.innerHTML = '<i class="fa-solid fa-xmark mr-1"></i> Cancel';
			editBtn.className = 'btn btn-sm btn-outline-secondary mr-1';
			
			otherBtns.forEach(btn => {
				if (btn.id !== 'editBtn_' + rowId) btn.style.display = 'none';
			});
		} else {
			// Hide inputs (Revert)
			razaNameSpan.style.display = 'inline';
			umoorSpan.style.display = 'inline';
			razaNameInput.style.display = 'none';
			umoorSelect.style.display = 'none';
			submitBtn.style.display = 'none';
			
			editBtn.innerHTML = '<i class="fa-solid fa-pencil-alt mr-1"></i> Edit';
			editBtn.className = 'btn btn-sm btn-outline-secondary mr-1';
			
			otherBtns.forEach(btn => btn.style.display = 'inline-block');
		}
	}

	function submitRow(rowId) {
		var newRazaName = document.getElementById('editRazaName_' + rowId).value.trim();
		var newUmoor = document.getElementById('editUmoor_' + rowId).value;
		if (!newRazaName) {
			alert('Raza name is required.');
			return;
		}

		var formData = new FormData();
		formData.append('razaName', newRazaName);
		formData.append('umoor', newUmoor);
		formData.append('rowId', rowId);

		fetch('<?php echo base_url('admin/update_raza_details/') ?>' + rowId, {
				method: 'POST',
				body: formData,
			})
			.then(response => response.json())
			.then(data => {
				showToast('Raza details updated successfully');
				document.getElementById('razaName_' + rowId).textContent = newRazaName;
				document.getElementById('umoor_' + rowId).textContent = newUmoor;
				editRow(rowId); // Revert UI
			})
			.catch(error => {
				console.error('Data update failed:', error);
				alert('Failed to update data.');
			});
	}

	function toggleStatus(rowId) {
		fetch('<?php echo base_url('admin/toggle_raza_status/') ?>' + rowId, {
				method: 'GET'
			})
			.then(response => response.json())
			.then(data => {
				if (data.status) {
					showToast('Status updated successfully');
					const statusTd = document.getElementById('status_' + rowId);
					const statusBtn = document.getElementById('statusBtn_' + rowId);
					const isActive = data.new_active == 1;

					statusTd.textContent = isActive ? 'Active' : 'Inactive';
					statusTd.className = 'status-badge ' + (isActive ? 'status-active' : 'status-inactive');
					
					statusBtn.className = 'btn btn-sm ' + (isActive ? 'btn-outline-danger' : 'btn-outline-success') + ' mr-1';
					statusBtn.title = (isActive ? 'Disable' : 'Enable') + ' Raza Type';
					statusBtn.innerHTML = '<i class="fa-solid ' + (isActive ? 'fa-circle-xmark' : 'fa-circle-check') + ' mr-1"></i>' + (isActive ? 'Disable' : 'Enable');
				}
			})
			.catch(error => {
				console.error('Status toggle failed:', error);
			});
	}

    // Search filter
    document.getElementById('razaSearchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#razaTable tbody tr:not(#temp_add_row)');
        
        rows.forEach(row => {
            let found = false;
            row.querySelectorAll('td').forEach(cell => {
                const text = cell.textContent || cell.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                }
            });
            row.style.display = found ? '' : 'none';
        });
    });

    // Sorting functionality
    function sortTable(columnIndex) {
        const table = document.querySelector("#razaTable tbody");
        const rowsArray = Array.from(table.querySelectorAll("tr:not(#temp_add_row)"));
        if (rowsArray.length === 0) return;

        const isAscending = this.isAsc = !this.isAsc;

        const sortedRows = rowsArray.sort((a, b) => {
            const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();
            const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();

            if (columnIndex === 0) { // numbers
                return (parseInt(cellA) - parseInt(cellB)) * (isAscending ? 1 : -1);
            } else if (columnIndex === 1) { // dates
                return (new Date(cellA) - new Date(cellB)) * (isAscending ? 1 : -1);
            } else {
                return cellA.localeCompare(cellB) * (isAscending ? 1 : -1);
            }
        });

        sortedRows.forEach(row => table.appendChild(row));
    }
</script>