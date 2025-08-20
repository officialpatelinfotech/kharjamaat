<style>
    /*td {*/
    /*    min-width: 130px;*/
    /*}*/
    .sno{
        width:40px;
    }
    .created{
        min-width: 130px;
    }
    .raza{
        min-width: 130px;
    }
    .eventdate{
        min-width: 130px;
    }
    .name{
        min-width: 130px;
    }
    .remark{
        min-width: 130px;
    }
    .approval_status{
        min-width: 130px;
    }
    .action{
        min-width: 100px;
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
    max-height: calc(100vh - 120px);
    overflow-y: auto;
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
    .select {
        background: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 18px;
        color: #495057;
        padding-inline: 9px;

        @media screen and (max-width:576px) {
            font-size: 16px;
            width: 100%;
        }
    }

    .options {
        background-color: white;
    }
</style>
<div id="toast-message" class="toast-message">
    Successfull
</div>
<div class="margintopcontainer">
    <div class="container pt-5">
        <p class="h4 text-center mt-5">Raza Request</p>
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
                    <a class="form-control btn btn-success my-2 my-lg-0 ml-auto" onclick="refresh();">Refresh</a>
                </form>
            </div>
            <div class="row d-flex justify-content-between mt-3">
                <select onchange="updateTable();" name="filter" class="select mb-3" id="filter">
                    <option value="" selected disabled>Filter</option>
                    <?php foreach ($razatype as $key => $value) { ?>
                        <option class="options" value="<?php echo $value['name'] ?>">
                            <?php echo $value['name'] ?>
                        </option>
                    <?php } ?>
                    <option class="options" value="pending">Pending</option>
                    <option class="options" value="approved">Approved</option>
                    <option class="options" value="recommended">Recommended</option>
                    <option class="options" value="notrecommended">Not Recommended</option>
                    <option class="options" value="rejected">Rejected</option>
                    <option class="options" value="clear">Clear</option>
                </select>
                <select onchange="updateTable();" name="sort" class="select mb-3" id="sort">
                    <option value="" selected disabled>Sort</option>
                    <option class="options" value="0">Name(A-Z)</option>
                    <option class="options" value="1">Name(Z-A)</option>
                    <option class="options" value="2">Date (New>Old)</option>
                    <option class="options" value="3">Date (Old>New)</option>
                    <option class="options" value="4">Clear</option>
                </select>
            </div>
        </div>
        <div class="table-responsive mt-5 mb-5">
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="sno">S.No.</th>
                            <th class="created">Created</th>
                            <th class="raza">Raza For</th>
                            <th class="eventdate">Event Date</th>
                            <th class="name">Name</th>
                            <th class="remark">Remark</th>
                            <th class="approval_status">Status</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <tbody id="datatable">
                        <?php
                        foreach ($raza as $key => $r) { ?>
                            <tr>
                                <td class="sno">
                                    <?php echo $key + 1 ?>
                                </td>
                                <td>
                                    <?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])) ?>
                                </td>
                                <td>
                                    <?php echo $r['razaType'] ?>
                                </td>
                                <td>
                                    <?php $temp= json_decode($r['razadata'],true) ;if(!empty($temp['date'])){
                                        echo date('D, d M ', strtotime($temp['date']));
                                    } ?>
                                </td>
                                <td>
                                    <?php echo $r['user_name'] ?>
                                </td>
                                <td>
                                    <?php echo $r['remark'] ?>
                                </td>
                                <td>
                                    <div class="text-left">
                                        <ul>
                                            <li>
                                                <?php if ($r['status'] == 0) {
                                                    echo '<div><strong style="color: darkblue;">Pending</strong></div>';
                                                } elseif ($r['status'] == 1) {
                                                    echo '<div><strong style="color: blue;">Recommended</strong></div>';
                                                } elseif ($r['status'] == 2) {
                                                    echo '<div><strong style="color: limegreen;">Approved</strong></div>';
                                                } elseif ($r['status'] == 3) {
                                                    echo '<div><strong style="color: red;">Rejected</strong></div>';
                                                } elseif ($r['status'] == 4) {
                                                    echo '<div><strong style="color: blue;">Not Recommended</strong></div>';
                                                } ?>
                                            </li>
                                            <li>
                                                <?php if ($r['coordinator-status'] == 0) {
                                                    echo '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                                                } elseif ($r['coordinator-status'] == 1) {
                                                    echo '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                                                } elseif ($r['coordinator-status'] == 2) {
                                                    echo '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                                                } ?>
                                            </li>
                                            <li>
                                                <?php if ($r['Janab-status'] == 0) {
                                                    echo '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
                                                } elseif ($r['Janab-status'] == 1) {
                                                    echo '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
                                                } elseif ($r['Janab-status'] == 2) {
                                                    echo '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
                                                }?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($r['Janab-status'] == 0) { ?>
                                        <button type="button" class="btn btn-sm btn-primary remove-form-row"
                                            onclick="approve_raza(<?php echo $r['id'] ?>);"><i
                                                class="fa fa-circle-check"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger remove-form-row"
                                            onclick="reject_raza(<?php echo $r['id'] ?>);"><i
                                                class="fa fa-circle-xmark"></i></button>
                                    <?php } ?>
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
<div id="product-overlay"></div>
<div id="approve-form" class="query-form">
    <table id="details-table-approve" class="table"></table>
    <form class="approve" id="approve">
        <div class="form-group">
            <label for="remark" class="form-label">Remark (optional)</label>
            <textarea class="form-control" name="remark" id="remark" rows="5"
                style="max-width:100%; height:100%"></textarea>
        </div>
        <div class="submit">
            <button class="btn btn-danger w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
            <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Approve</button>
        </div>
    </form>
</div>
<div id="reject-form" class="query-form">
    <table id="details-table-reject" class="table"></table>
    <form class="reject" id="reject">
        <div class="form-group">
            <label for="remark" class="form-label">Remark *</label>
            <textarea class="form-control" name="remark" required id="remark" rows="5"
                style="max-width:100%; height:100%"></textarea>
        </div>
        <div class="submit">
            <button class="btn btn-danger w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
            <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Confirm</button>
        </div>
    </form>
</div>
<script>
    let razas = [];
    <?php foreach ($raza as $r) { ?>
        var data = {};
        <?php foreach ($r as $key => $ele) { ?>
            data['<?php echo $key ?>'] = '<?php echo $ele ?>';
        <?php } ?>
        razas.push(data);
    <?php } ?>
    function approve_raza(id) {
        document.getElementById("approve-form").style.display = "block";
        document.getElementById("product-overlay").style.display = "block";
        const newInput = document.createElement("div");
        newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
        document.getElementById("approve").appendChild(newInput);
        let raza = razas.find(e => e.id == id)
        otherdetails(raza, 'approve')
    }
    function reject_raza(id) {
        document.getElementById("reject-form").style.display = "block";
        document.getElementById("product-overlay").style.display = "block";
        const newInput = document.createElement("div");
        newInput.innerHTML = `<input type="text" hidden name="raza_id" value=${id} Required=true>`;
        document.getElementById("reject").appendChild(newInput);
        let raza = razas.find(e => e.id == id)
        otherdetails(raza, 'reject')
    }
    function clearForm() {
        $('#approve')[0].reset();
        $('#reject')[0].reset();
        document.getElementById("approve-form").style.display = "none";
        document.getElementById("reject-form").style.display = "none";
        document.getElementById("product-overlay").style.display = "none";
        event.preventDefault();
        refresh();
    }
    function otherdetails(raza, action) {
        var table = document.getElementById(`details-table-${action}`);
        table.innerHTML="";
        var tablehead = document.createElement('thead')
        tablehead.innerHTML = `<thead><tr><th scope="col" colspan="2" class="text-center">Raza Details</th></tr></thead>`
        table.appendChild(tablehead)
        var tablebody = document.createElement('tbody');
        let tbodydata = "";
        raza.razadata = JSON.parse(raza.razadata)
        let razafields = JSON.parse(raza.razafields)
        let k = 0;
        for (let key in raza.razadata) {
            if (razafields.fields[k].type == 'select') {
                console.log(razafields.fields[k])
                let options = razafields.fields[k].options
                let value=options[raza.razadata[key]]
                console.log(value)
                tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${value.name}</td></tr>`
            }else{
                tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${raza.razadata[key]}</td></tr>`
            }
            k++;
        }
        tablebody.innerHTML = tbodydata
        table.appendChild(tablebody)
    }
    $(document).ready(function () {
        $('#approve').submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('admin/approveRaza'); ?>",
                data: formData,
                success: function (response) {
                    showSuccessMessage();
                    clearForm();
                    refresh();
                },
                error: function (error) {
                    console.error('query submission failed');
                }
            });
        });

        function showSuccessMessage() {
            var toastMessage = $('#toast-message');
            toastMessage.show();
            setTimeout(function () {
                toastMessage.hide();
            }, 2000);
        }
    });
    $(document).ready(function () {
        $('#reject').submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('admin/rejectRaza'); ?>",
                data: formData,
                success: function (response) {
                    showSuccessMessage();
                    clearForm();
                    refresh();
                },
                error: function (error) {
                    console.error('query submission failed');
                }
            });
        });

        function showSuccessMessage() {
            var toastMessage = $('#toast-message');
            toastMessage.show();
            setTimeout(function () {
                toastMessage.hide();
            }, 2000);
        }
    });
    function redirectto(location) {
        window.location.href = '<?php echo base_url() ?>' + location;
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function refresh() {
        window.location.reload()
    }
</script>
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
<script>
    function updateTable() {
        var filterValue = document.getElementById("filter").value;
        var sortValue = document.getElementById("sort").value;
        updateTableContent(filterValue, sortValue);
    }

    function updateTableContent(filter, sort) {
        var tbody = document.getElementById('datatable');
        tbody.innerHTML = "";

        // Filter and sort your razas array based on the selected options
        var filteredAndSortedRazas = razas;

        if (filter !== "") {
            switch (filter) {
                case "approved":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.status == 2;
                    });
                    break;
                case "recommended":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.status == 1;
                    });
                    break;
                case "pending":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.status == 0;
                    });
                    break;
                case "rejected":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.status == 3;
                    });
                    break;
                case "notrecommended":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.status == 4;
                    });
                    break;
                case "clear":
                    refresh();
                    break;

                default:
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function (raza) {
                        return raza.razaType === filter;
                    });
                    break;
            }

        }

        if (sort !== "") {
            filteredAndSortedRazas.sort(function (a, b) {
                // Implement your sorting logic here
                switch (parseInt(sort)) {
                    case 0:
                        return a.user_name.localeCompare(b.user_name);
                    case 1:
                        return b.user_name.localeCompare(a.user_name);
                    case 2:
                        // Implement sorting by date (New > Old)
                        return new Date(b['time-stamp']) - new Date(a['time-stamp']);
                    case 3:
                        // Implement sorting by date (Old > New)
                        return new Date(a['time-stamp']) - new Date(b['time-stamp']);
                    case 4:
                        refresh();
                    default:
                        return 0;
                }
            });
        }

        // Populate the table with the filtered and sorted data
        for (var i = 0; i < filteredAndSortedRazas.length; i++) {
            var raza = filteredAndSortedRazas[i];

            var row = document.createElement("tr");
            row.innerHTML = `
                <td>${i + 1}</td>
                <td>${formatDate(raza['time-stamp'])}</td>
                <td>${raza['razaType']}</td>
                <td>${formatEventDate(raza.razadata)}</td>
                <td>${raza['user_name']}</td>
                <td>${raza['remark']}</td>
                <td>${getStatusHTML(raza)}</td>
                <td>${getActionHTML(raza)}</td>
            `;

            tbody.appendChild(row);
        }
    }

    function formatDate(dateString) {
        // Implement date formatting logic if needed
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
    function formatEventDate(razadata) {
        let data=JSON.parse(razadata)
        let dateString=data.date
        if(dateString){
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }else{
            return ""
        }
    }

    function getStatusHTML(raza) {
        // Implement your status HTML generation logic
        // You can use the same logic you have in your PHP code

        let statusHTML = '';
        if (raza['status'] == 0) {
            statusHTML = '<div><strong style="color: darkblue;">Pending</strong></div>';
        } else if (raza['status'] == 1) {
            statusHTML = '<div><strong style="color: blue;">Recommended</strong></div>';
        } else if (raza['status'] == 2) {
            statusHTML = '<div><strong style="color: limegreen;">Approved</strong></div>';
        } else if (raza['status'] == 3) {
            statusHTML = '<div><strong style="color: red;">Rejected</strong></div>';
        } else if (raza['status'] == 4) {
            statusHTML = '<div><strong style="color: blue;">Not Recommended</strong></div>';
        }

        return statusHTML;
    }

    function getActionHTML(raza) {
        // Implement your action HTML generation logic
        // You can use the same logic you have in your PHP code

        let actionHTML = '';
        if (raza['Janab-status'] == 0) {
            actionHTML = `
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(${raza['id']});">
                    <i class="fa fa-circle-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(${raza['id']});">
                    <i class="fa fa-circle-xmark"></i>
                </button>`;
        }

        return actionHTML;
    }
</script>