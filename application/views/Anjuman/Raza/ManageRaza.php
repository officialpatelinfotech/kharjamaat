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
</style>
<div id="toast-message" class="toast-message">
    Successfull
</div>
<div class="margintopcontainer">
    <div class="container pt-5">
        <p class="h4 text-center mt-5">Raza Type</p>
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
                        data-placement="bottom" title="Add New Raza Type" onclick="addnewrazatype();">New
                        Raza</a>
                </form>
            </div>
        </div>
        <div class="table-responsive mt-5 mb-5">
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead id="table">
                        <tr>
                            <th class="sno">S.No.</th>
                            <th class="created">Created</th>
                            <th class="raza">Raza For</th>
                            <th class="approval_status">Status</th>
                            <th class="action">Action</th>
                        </tr>
                        <?php
                        foreach ($raza_type as $key => $r) { ?>
                            <tr>
                                <td>
                                    <?php echo $key + 1 ?>
                                </td>
                                <td>
                                    <?php echo date('D, d M @ g:i a', strtotime($r['timestamp'])) ?>
                                </td>
                                <td>
                                    <?php echo $r['name'] ?>
                                </td>
                                <td>
                                    <?php if ($r['active'] == 1) {
                                        echo 'Active';
                                    } else {
                                        echo 'InActive';
                                    } ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/manage_edit_raza/') . $r['id'] ?>">
                                        <button type="button" data-toggle="tooltip" data-placement="bottom"
                                            title="Modify Raza Fields" class="btn btn-sm btn-primary remove-form-row">
                                            <i class="fa-solid fa-pen-to-square"></i></button>
                                    </a>
                                    <a href="<?php echo base_url('admin/manage_delete_raza/') . $r['id'] ?>">
                                        <button type="button" data-toggle="tooltip" data-placement="bottom"
                                            title="Delete Raza Type" class="btn btn-sm btn-danger remove-form-row">
                                            <i class="fa-solid fa-circle-xmark"></i></button>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function addnewrazatype() {
        let table = document.getElementById('table')
        let tr = document.createElement('tr');
        tr.innerHTML = `<td></td><td></td><td><input type="text" id="razaname" class="form-control"></td><td></td><td><button type="button" class="btn btn-sm btn-success remove-form-row" onclick="submitForm()">Submit</button></td>`
        table.appendChild(tr);
    }
    function submitForm() {
        let formData = new FormData();
        let razaname = document.getElementById('razaname').value
        formData.append('raza-name', razaname);

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
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
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