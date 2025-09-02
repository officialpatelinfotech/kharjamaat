<style>
	td {
		min-width: 130px;
	}

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

	.fields_btn {
		cursor: pointer;
		color: #ad7e05;
	}

	.fields_btn:hover {
		text-decoration: underline;
	}

	/* Style the sort icons */
	.sort-icons {
		display: inline-block;
		margin-left: 5px;
		cursor: pointer;
		vertical-align: middle;
	}

	.sort-icons i {
		display: block;
		font-size: 10px;
		/* Adjust the size of the icons */
		margin: 0;
		line-height: 1;
		color: #333;
	}

	.sort-icons i:first-child {
		margin-bottom: 2px;
	}

	/* Align the column text with the icons */
	th {
		white-space: nowrap;
	}
</style>
<div id="toast-message" class="toast-message">
	Successfull
</div>
<div class="margintopcontainer">
	<div class="container pt-5">
		<p class="h4 text-center mt-5">All Raza Form</p>
		<div class="container">
			<div class="row mt-5">
				<form class="form-inline my-2 my-lg-0 w-100">
					<div class="input-group">
						<input class="form-control" type="search" placeholder="Search" aria-label="Search"
							id="razaSearchInput">
						<div class="input-group-append">
							<span class="input-group-text">
								<i class="fa fa-search"></i>
							</span>
						</div>
					</div>
					<a class="form-control btn btn-success my-2 my-lg-0 ml-auto" data-toggle="tooltip"
						data-placement="bottom" title="Add New Raza Type" onclick="addnewrazatype();">Create New Form</a>
				</form>
			</div>
		</div>
		<div class="table-responsive mt-5 mb-5">
			<div class="table-container">
				<table class="table table-bordered text-center">
					<thead id="table">
						<tr>
							<th class="sno">S.No.
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
							<th class="approval_status">Status
								<span class="sort-icons" onclick="sortTable(4)">
									<i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
								</span>
							</th>
							<th class="action">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($raza_type as $key => $r) { ?>
							<tr>
								<td><?php echo $key + 1 ?></td>
								<td><?php echo date('D, d M @ g:i a', strtotime($r['timestamp'])) ?></td>
								<td>
									<span id="razaName_<?php echo $r['id']; ?>"><?php echo $r['name']; ?></span>
									<input type="text" id="editRazaName_<?php echo $r['id']; ?>" class="form-control"
										style="display: none;" value="<?php echo $r['name']; ?>">
								</td>
								<td>
									<span id="umoor_<?php echo $r['id']; ?>"><?php echo $r['umoor']; ?></span>
									<select id="editUmoor_<?php echo $r['id']; ?>" class="form-control"
										style="display: none;">
										<option value="Private-Event" <?php echo ($r['umoor'] === 'Private-Event') ? 'selected' : ''; ?>>Private Event</option>
										<option value="Public-Event" <?php echo ($r['umoor'] === 'Public-Event') ? 'selected' : ''; ?>>Public Event</option>
										<option value="UmoorDeeniyah" <?php echo ($r['umoor'] === 'UmoorDeeniyah') ? 'selected' : ''; ?>>Umoor Deeniyah</option>
										<option value="UmoorDeeniyah" <?php echo ($r['umoor'] === 'UmoorDeeniyah') ? 'selected' : ''; ?>>Umoor Deeniyah</option>
										<option value="UmoorTalimiyah" <?php echo ($r['umoor'] === 'UmoorTalimiyah') ? 'selected' : ''; ?>>Umoor Talimiyah
										</option>
										<option value="UmoorMarafiqBurhaniyah" <?php echo ($r['umoor'] === 'UmoorMarafiqBurhaniyah') ? 'selected' : ''; ?>>Umoor
											Marafiq Burhaniyah</option>
										<option value="UmoorMaaliyah" <?php echo ($r['umoor'] === 'UmoorMaaliyah') ? 'selected' : ''; ?>>Umoor Maaliyah</option>
										<option value="UmoorMawaridBashariyah" <?php echo ($r['umoor'] === 'UmoorMawaridBashariyah') ? 'selected' : ''; ?>>Umoor
											Mawarid Bashariyah</option>
										<option value="UmoorDakheliya" <?php echo ($r['umoor'] === 'UmoorDakheliya') ? 'selected' : ''; ?>>Umoor Dakheliya
										</option>
										<option value="UmoorKharejiyah" <?php echo ($r['umoor'] === 'UmoorKharejiyah') ? 'selected' : ''; ?>>Umoor Kharejiyah
										</option>
										<option value="UmoorIqtesadiyah" <?php echo ($r['umoor'] === 'UmoorIqtesadiyah') ? 'selected' : ''; ?>>Umoor Iqtesadiyah
										</option>
										<option value="UmoorFMB" <?php echo ($r['umoor'] === 'UmoorFMB') ? 'selected' : ''; ?>>Umoor FMB</option>
										<option value="UmoorAl-Qaza" <?php echo ($r['umoor'] === 'UmoorAl-Qaza') ? 'selected' : ''; ?>>Umoor Al-Qaza</option>
										<option value="UmoorAl-Amlaak" <?php echo ($r['umoor'] === 'UmoorAl-Amlaak') ? 'selected' : ''; ?>>Umoor Al-Amlaak
										</option>
										<option value="UmoorAl-Sehhat" <?php echo ($r['umoor'] === 'UmoorAl-Sehhat') ? 'selected' : ''; ?>>Umoor Al-Sehhat
										</option>
									</select>
								</td>
								<td><?php echo ($r['active'] == 1) ? 'Active' : 'Inactive'; ?></td>
								<td>
									<a href="<?php echo base_url('admin/manage_edit_raza/') . $r['id'] ?>">
										<button type="button" data-toggle="tooltip" data-placement="bottom"
											title="Modify Raza Fields" class="btn btn-sm btn-primary remove-form-row">
											<i class="fa-solid fa-pen-to-square"></i></button>
									</a>
									<a href="javascript:void(0);" onclick="editRow(<?php echo $r['id']; ?>);">
										<button type="button" data-toggle="tooltip" data-placement="bottom"
											title="Edit Raza Name And Umoor" class="btn btn-sm btn-secondary">
											<i class="fa-solid fa-pencil-alt"></i>
										</button>
									</a>
									<a href="<?php echo base_url('admin/manage_delete_raza/') . $r['id'] ?>">
										<button type="button" data-toggle="tooltip" data-placement="bottom"
											title="Delete Raza Type" class="btn btn-sm btn-danger remove-form-row">
											<i class="fa-solid fa-circle-xmark"></i></button>
									</a>
									<button type="button" id="submitBtn_<?php echo $r['id']; ?>"
										class="btn btn-sm btn-success" style="display: none;"
										onclick="submitRow(<?php echo $r['id']; ?>);">
										Submit
									</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot></tfoot>
				</table>


			</div>
		</div>
	</div>
</div>


<script>
	function addnewrazatype() {
		let table = document.getElementById('table');
		let tr = document.createElement('tr');
		tr.innerHTML = `
            <td></td>
            <td></td>
            <td><input type="text" id="razaname" class="form-control"></td>
            <td>
                <select id="umoor" class="form-control">
                    <option value="Private-Event">Private Event</option>
                    <option value="Public-Event">Public Event</option>
                    <option value="UmoorDeeniyah">Umoor Deeniyah</option>
                    <option value="UmoorTalimiyah">Umoor Talimiyah</option>
                    <option value="UmoorMarafiq Burhaniyah">Umoor Marafiq Burhaniyah</option>
                    <option value="UmoorMaaliyah">Umoor Maaliyah</option>
                    <option value="UmoorMawarid Bashariyah">Umoor Mawarid Bashariyah</option>
                    <option value="UmoorDakheliya">Umoor Dakheliya</option>
                    <option value="UmoorKharejiyah">Umoor Kharejiyah</option>
                    <option value="UmoorIqtesadiyah">Umoor Iqtesadiyah</option>
                    <option value="UmoorFMB">Umoor FMB</option>
                    <option value="UmoorAl-Qaza">Umoor Al-Qaza</option>
                    <option value="UmoorAl-Amlaak">Umoor Al-Amlaak</option>
                    <option value="UmoorAl-Sehhat">Umoor Al-Sehhat</option>
                </select>
            </td>
            <td></td>
            <td>
                <button type="button" class="btn btn-sm btn-success remove-form-row" onclick="submitForm()">Submit</button>
            </td>
        `;
		table.appendChild(tr);
	}

	function submitForm() {
		let formData = new FormData();
		let razaname = document.getElementById('razaname').value
		let umoor = document.getElementById('umoor').value
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

	function editRow(rowId) {
		var allButtons = document.querySelectorAll('#row_' + rowId + ' .btn');
		for (var i = 0; i < allButtons.length; i++) {
			allButtons[i].style.display = 'none';
		}
		document.getElementById('submitBtn_' + rowId).style.display = 'block';

		document.getElementById('razaName_' + rowId).style.display = 'none';
		document.getElementById('umoor_' + rowId).style.display = 'none';
		document.getElementById('editRazaName_' + rowId).style.display = 'block';
		document.getElementById('editUmoor_' + rowId).style.display = 'block';
	}

	function submitRow(rowId) {
		var newRazaName = document.getElementById('editRazaName_' + rowId).value;
		var newUmoor = document.getElementById('editUmoor_' + rowId).value;

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
				// Handle success response
				console.log('Data updated successfully:', data);
			})
			.catch(error => {
				// Handle error
				console.error('Data update failed:', error);
			});

		document.getElementById('razaName_' + rowId).textContent = newRazaName;
		document.getElementById('umoor_' + rowId).textContent = newUmoor;

		document.getElementById('razaName_' + rowId).style.display = 'inline';
		document.getElementById('umoor_' + rowId).style.display = 'inline';
		document.getElementById('editRazaName_' + rowId).style.display = 'none';
		document.getElementById('editUmoor_' + rowId).style.display = 'none';

		var allButtons = document.querySelectorAll('#row_' + rowId + ' .btn');
		for (var i = 0; i < allButtons.length; i++) {
			allButtons[i].style.display = 'block';
		}
		document.getElementById('submitBtn_' + rowId).style.display = 'none';
	}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
	$(document).ready(function() {
		// Function to handle the search functionality
		function performSearch() {
			var input, filter, table, tr, td, i, txtValue;
			input = document.getElementById("razaSearchInput");
			filter = input.value.toLowerCase();
			table = document.getElementsByTagName("table")[0]; // Assuming it's the first table on the page
			tr = table.getElementsByTagName("tr");

			// Loop through all table rows and hide those that don't match the search query
			for (i = 1; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td");
				var found = false;
				for (var j = 0; j < td.length; j++) {
					if (td[j]) {
						txtValue = td[j].textContent || td[j].innerText;
						if (txtValue.toLowerCase().indexOf(filter) > -1) {
							found = true;
							break;
						}
					}
				}
				if (found) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}

		// Attach the performSearch function to the input field's keyup event
		document.getElementById("razaSearchInput").addEventListener("keyup", performSearch);
	});
</script>
<script>
	function sortTable(columnIndex) {
		const table = document.querySelector(".table tbody");
		const rowsArray = Array.from(table.querySelectorAll("tr"));

		const sortedRows = rowsArray.sort((a, b) => {
			const cellA = a.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();
			const cellB = b.querySelectorAll("td")[columnIndex].innerText.trim().toLowerCase();

			if (columnIndex === 1) { // Sort created column (dates)
				return new Date(cellA) - new Date(cellB);
			} else if (columnIndex === 4) { // Sort status column
				return (cellA === "active") ? -1 : 1;
			} else {
				return cellA.localeCompare(cellB);
			}
		});

		// Append the sorted rows back to the table
		sortedRows.forEach(row => table.appendChild(row));
	}
</script>