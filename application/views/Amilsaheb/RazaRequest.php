<style>
    /*td {*/
    /*    min-width: 100px;*/
    /*}*/
    .sno {
        width: 40px;
    }

    .created {
        min-width: 130px;
    }

    .raza {
        min-width: 130px;
    }

    .eventdate {
        min-width: 130px;
    }

    .name {
        min-width: 130px;
    }

    .remark {
        min-width: 130px;
    }

    .approval_status {
        min-width: 130px;
    }

    .action {
        min-width: 100px;
    }

    .action_btn {
        display: flex;
        flex-direction: row;
        gap: 1rem;

        @media screen and (max-width:768px) {
            flex-direction: column;
            gap: 1rem;
            flex-grow: 1;
        }
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

    .status {
        min-width: 230px;
    }

    .select {
        background: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 18px;
        color: #495057;
        padding-inline: 9px;

        @media screen and (max-width:576px) {
            font-size: 20px;
            width: 100%;
        }
    }

    .options {
        background-color: white;
    }

    .table-responsive {
        transform: rotateX(180deg);
    }

    .table-container {
        transform: rotateX(180deg);
    }
    .chat-button {
        display: flex;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        align-items: center;
        justify-content: center;
        margin: auto;
    }

    /* Hover effect */
    .chat-button:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }

    .chat-count {
        display: inline-block;
        width: 25px;
        height: 25px;
        background-color: grey;
        color: white;
        border-radius: 50%;
        /* Make it circular */
        text-align: center;
        line-height: 25px;
        font-size: 14px;
        font-weight: bold;
        margin-left: 5px;
        /* Adjust as needed */
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        /* Add shadow for depth */
    }
    
    .dashboard-container {
        display: flex;
        justify-content: space-between;
        margin: 20px auto;
    }

    .dashboard-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: calc(25% - 20px);
    }

    .dashboard-icon {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 15px;
        display: block;
        text-align: center;
    }

    .card-title {
        font-size: 1.2rem;
        margin-bottom: 10px;
        display: inline-flex;
    }

    .card-text {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
    }

    .btn {
        /*background-color: #007bff;*/
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .event-list {
        max-height: 50px;
        /* Height to display only two events initially */
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .event-list-content {
        padding: 0;
        margin: 0;
    }

    .event-list:hover {
        max-height: 200px;
        /* Height to display more events on hover */
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

    .action-buttons {
        display: inline-block;
        text-align: center;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        /* Keep buttons spaced apart */
    }

    .button-group button {
        margin-right: 5px;
        /* Add some space between buttons */
    }

    .view-link {
        margin-top: 10px;
        /* Adds space between buttons and the "View" link */
        text-align: center;
    }
</style>
<div id="toast-message" class="toast-message">
    Successfull
</div>
<div class="margintopcontainer">
    <div class="ml-1 mr-1 pt-5">
        <p class="h4 text-center mt-5" style="color:goldenrod; text-transform: uppercase;"><?php echo $umoor ?></p>
        <div class="container">
            <div class="dashboard-container">
                <div class="dashboard-card" style="background-color: #ffe066;">
                    <i class="fas fa-clock dashboard-icon"></i>
                    <h2 class="card-title">Pending Tasks</h2>
                    <h2 style="display: inline-flex; padding-left: 15px;"><?php echo $janab_status_0_count ?></h2>
                </div>

                <div class="dashboard-card" style="background-color: #66cdaa;">
                    <i class="fas fa-check-circle dashboard-icon"></i>
                    <h2 class="card-title">Completed Tasks</h2>
                    <h2 style="display: inline-flex; padding-left: 15px;"><?php echo $janab_status_1_count ?></h2>
                </div>

                <div class="dashboard-card" style="background-color: #afeeee;">
                    <i class="fas fa-sync dashboard-icon"></i>
                    <h2 class="card-title">Processing Tasks</h2>
                    <h2 style="display: inline-flex; padding-left: 15px;"><?php echo $coordinator_status_0_count ?></h2>
                </div>

                <!-- <div class="dashboard-card" style="background-color: #e6e6fa;">
                    <i class="far fa-calendar-alt dashboard-icon"></i>
                    <h2 class="card-title">Upcoming Events</h2>
                    <div class="event-list" onmouseover="showAllEvents()">
                        <ul class="event-list-content">
                            <li>Event 1 - <span id="event1-date"></span></li>
                            <li>Event 2 - <span id="event2-date"></span></li>
                            <li>Event 3 - <span id="event3-date"></span></li>
                            <li>Event 4 - <span id="event4-date"></span></li>
                        </ul>
                    </div>
                </div> -->

                <!-- <script>
                    // Generate random dates for the dummy data
                    function generateRandomDate() {
                        const startDate = new Date();
                        const endDate = new Date();
                        endDate.setDate(endDate.getDate() + 30); // Events within the next 30 days
                        return new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
                    }

                    // Format date as "Month Day, Year"
                    function formatDate(date) {
                        const options = {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        };
                        return date.toLocaleDateString('en-US', options);
                    }

                    // Update event dates with dummy data
                    document.getElementById('event1-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event2-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event3-date').textContent = formatDate(generateRandomDate());
                    document.getElementById('event4-date').textContent = formatDate(generateRandomDate());
                </script> -->



            </div>
            <div class="row mt-5">
                <form class="form-inline my-2 my-lg-0 w-100">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="razaSearchInput">
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
                    <option class="options" value="2">Event Date (New>Old)</option>
                    <option class="options" value="3">Event Date (Old>New)</option>
                    <option class="options" value="4">Create Date (New>Old)</option>
                    <option class="options" value="5">Create Date (Old>New)</option>
                    <option class="options" value="6">Clear</option>
                </select>
            </div>
        </div>
        <div class="table-responsive mt-5 mb-5">
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="sno">S.No.
                                <span class="sort-icons" onclick="sortTable(0)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="sno">Sabil
                                <span class="sort-icons" onclick="sortTable(1)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="sno">FMB Thaali
                                <span class="sort-icons" onclick="sortTable(2)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <!--<th class="sno">FMB Kitchen-->
                            <!--    <span class="sort-icons" onclick="sortTable(3)">-->
                            <!--        <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>-->
                            <!--    </span>-->
                            <!--</th>-->
                            <th class="created">Created
                                <span class="sort-icons" onclick="sortTable(4)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="raza">Raza For
                                <span class="sort-icons" onclick="sortTable(5)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="eventdate">Event Date
                                <span class="sort-icons" onclick="sortTable(6)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="name">Name
                                <span class="sort-icons" onclick="sortTable(7)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="remark">Remark
                                <span class="sort-icons" onclick="sortTable(8)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="approval_status">Status
                                <span class="sort-icons" onclick="sortTable(9)">
                                    <i class="fas fa-sort-up"></i><i class="fas fa-sort-down"></i>
                                </span>
                            </th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>

                    <tbody id="datatable">
                        <?php
                        foreach ($raza as $key => $r) { ?>
                            <tr>
                                <td>
                                    <?php echo $key + 1 ?>
                                </td>
                                <td>
                                    <?php echo date('D, d M @ g:i a', strtotime($r['time-stamp'])) ?>
                                </td>
                                <td>
                                    <?php echo $r['razaType'] ?>
                                </td>
                                <td>
                                    <?php $temp = json_decode($r['razadata'], true);
                                    if (!empty($temp['date'])) {
                                        echo date('D, d M ', strtotime($temp['date']));
                                    } ?>
                                </td>
                                <td>
                                    <?php echo $r['user_name'] ?>
                                </td>
                                <td>
                                    <?php echo $r['remark'] ?>
                                </td>
                                <td class="status">
                                    <div class="text-left">
                                        <ul>
                                            <?php if ($r['status'] == 0) {
                                                echo '<div><strong style="color: orange;">Pending</strong></div>';
                                            } elseif ($r['status'] == 1) {
                                                echo '<div><strong style="color: blue;">Recommended</strong></div>';
                                            } elseif ($r['status'] == 2) {
                                                echo '<div><strong style="color: limegreen;">Approved</strong></div>';
                                            } elseif ($r['status'] == 3) {
                                                echo '<div><strong style="color: red;">Rejected</strong></div>';
                                            } elseif ($r['status'] == 4) {
                                                echo '<div><strong style="color: blue;">Not Recommended</strong></div>';
                                            } ?>
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
                                                } ?>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-check"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="redirectto(<?php echo 'amilsaheb/DeleteRaza/' . $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
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
            <textarea class="form-control" name="remark" id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
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
            <textarea class="form-control" name="remark" required id="remark" rows="5" style="max-width:100%; height:100%"></textarea>
        </div>
        <div class="submit">
            <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Cancel</button>
            <button type="submit" class="btn btn-danger w100percent-xs mbm-xs">Reject</button>
        </div>
    </form>
</div>
<div id="show-form" class="query-form">
    <table id="details-table-show" class="table"></table>
    <form class="reject" id="show">
        <div class="submit">
            <button class="btn btn-primary w100percent-xs mbm-xs" onclick="clearForm();">Close</button>
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

    function show_raza(id) {
        document.getElementById("show-form").style.display = "block";
        document.getElementById("product-overlay").style.display = "block";
        let raza = razas.find(e => e.id == id)
        otherdetails(raza, 'show')
    }

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
        document.getElementById("show-form").style.display = "none";
        document.getElementById("product-overlay").style.display = "none";
        event.preventDefault();
    }

    function otherdetails(raza, action) {
        var table = document.getElementById(`details-table-${action}`);
        table.innerHTML = "";
        var tablehead = document.createElement('thead')
        tablehead.innerHTML = `<thead><tr><th scope="col" colspan="2" class="text-center">Raza Details</th></tr></thead>`
        table.appendChild(tablehead)
        var tablebody = document.createElement('tbody');
        let tbodydata = "";
        let razadata = JSON.parse(raza.razadata)
        let razafields = JSON.parse(raza.razafields)
        let k = 0;
        for (let key in razadata) {
            if (razafields.fields[k].type == 'select') {
                let options = razafields.fields[k].options
                let value = options[razadata[key]]
                tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${value.name}</td></tr>`
            } else {
                tbodydata += `<tr><th scope="row">${razafields.fields[k].name}</th><td>${razadata[key]}</td></tr>`
            }
            k++;
        }
        tablebody.innerHTML = tbodydata
        table.appendChild(tablebody)
    }
    $(document).ready(function() {
        $('#approve').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('amilsaheb/approveRaza'); ?>",
                data: formData,
                success: function(response) {
                    showSuccessMessage();
                    clearForm();
                    refresh();
                },
                error: function(error) {
                    console.error('query submission failed');
                }
            });
        });

        function showSuccessMessage() {
            var toastMessage = $('#toast-message');
            toastMessage.show();
            setTimeout(function() {
                toastMessage.hide();
            }, 2000);
        }
    });
    $(document).ready(function() {
        $('#reject').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('amilsaheb/rejectRaza'); ?>",
                data: formData,
                success: function(response) {
                    showSuccessMessage();
                    clearForm();
                    refresh();
                },
                error: function(error) {
                    console.error('query submission failed');
                }
            });
        });

        function showSuccessMessage() {
            var toastMessage = $('#toast-message');
            toastMessage.show();
            setTimeout(function() {
                toastMessage.hide();
            }, 2000);
        }
    });

    function redirectto(location) {
        window.location.href = '<?php echo base_url() ?>' + location;
    }

    function deleteRaza(id) {
        let check = confirm("Do You Want to Delete This Raza");
        if (check) {
            window.location.href = '<?php echo base_url() ?>' + 'amilsaheb/DeleteRaza/' + id;
        }
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function refresh() {
        window.location.reload()
    }
</script>
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
    let tem = document.getElementById("sort");
    tem.value = 2;
    updateTable();

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
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.status == 2;
                    });
                    break;
                case "recommended":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.status == 1;
                    });
                    break;
                case "pending":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.status == 0;
                    });
                    break;
                case "rejected":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.status == 3;
                    });
                    break;
                case "notrecommended":
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.status == 4;
                    });
                    break;
                case "clear":
                    refresh();
                    break;

                default:
                    filteredAndSortedRazas = filteredAndSortedRazas.filter(function(raza) {
                        return raza.razaType === filter;
                    });
                    break;
            }

        }

        if (sort !== "") {
            filteredAndSortedRazas.sort(function(a, b) {
                // Implement your sorting logic here
                switch (parseInt(sort)) {
                    case 0:
                        return a.user_name.localeCompare(b.user_name);
                    case 1:
                        return b.user_name.localeCompare(a.user_name);
                    case 4:
                        // Implement sorting by date (New > Old)
                        return new Date(b['time-stamp']) - new Date(a['time-stamp']);
                    case 5:
                        // Implement sorting by date (Old > New)
                        return new Date(a['time-stamp']) - new Date(b['time-stamp']);
                    case 2:
                        // Implement sorting by event date (New > Old)
                        return new Date(getEventDate(b.razadata)) - new Date(getEventDate(a.razadata));
                    case 3:
                        // Implement sorting by event date (Old > New)
                        return new Date(getEventDate(a.razadata)) - new Date(getEventDate(b.razadata));
                    case 6:
                        refresh();
                    default:
                        return 0;
                }
            });
        }

        // Populate the table with the filtered and sorted data
        for (var i = 0; i < filteredAndSortedRazas.length; i++) {
            var raza = filteredAndSortedRazas[i];
            var chatCount = raza.chat_count && raza.chat_count > 0 ? raza.chat_count : '';
            var chatCountHTML = chatCount ? `<div class="chat-count">${chatCount}</div>` : '';
            var chatURL = `<?= base_url('Accounts/chat/') ?>${raza.id}`;
            var row = document.createElement("tr");
            row.innerHTML = `
                <td>${i + 1}</td>
                <td>${formatSabil(raza)}</td>
                <td>${formatFmb(raza)}</td>
                <td>${formatDate(raza['time-stamp'])}</td>
                <td>${formateRazaType(raza)}</td>
                <td>${formatEventDate(raza)}</td>
                <td>${raza['user_name']}</td>
                <td>
                    <a href="${chatURL}" class="chat-button">
                        Chat${chatCountHTML}
                    </a>
                </td>
                <td>${getStatusHTML(raza)}</td>
                <td><span class="action_btn">${getActionHTML(raza)}</span></td>
            `;

            tbody.appendChild(row);
        }
    }

    function formatSabil(raza) {
        if (raza['sabil'] != '') {
            if (raza['sabil']) {
                return 'yes';
            } else {
                return 'no';
            }
        } else {
            return ""
        }
    }

    function formatFmbTameer(raza) {
        if (raza['fmbtameer'] != '') {
            if (raza['fmbtameer']) {
                return 'yes';
            } else {
                return 'no';
            }
        } else {
            return ""
        }
    }

    function formatFmb(raza) {
        if (raza['fmb'] != '') {
            if (raza['fmb']) {
                return 'yes';
            } else {
                return 'no';
            }
        } else {
            return ""
        }
    }

   function formateRazaType(raza) {
        let data = JSON.parse(raza.razadata);
        let rf = JSON.parse(raza.razafields);
        let razafields = rf.fields;

        // Logic for 'raza-purpose' can be included if needed
        // if (data['raza-purpose']) {
        //     let k = razafields.find(e => {
        //         let name = e.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
        //         return name === 'raza-purpose';
        //     });
        //     let value = k.options[data['raza-purpose']];
        //     return `${raza.razaType}<br/> <span style='color:grey'>(${value.name})</span>`;
        // } else {
        //     return `${raza.razaType} (${raza.umoor || ''})`; // Add raza.umoor with razaType
        // }

        // Return razaType and umoor
        return `${raza.umoor ? raza.umoor + '\n' : ''}(${raza.razaType || ''})`;
    }

    function formatDate(dateString) {
        // Implement date formatting logic if needed
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    function formatEventDate(raza) {
        let data = JSON.parse(raza.razadata)
        let rf = JSON.parse(raza.razafields)
        let razafields = rf.fields
        let dateString = data.date

        if (dateString) {

            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            if (data['time']) {
                let k = razafields.find(e => {
                    let name = e.name.toLowerCase().replace(/\s/g, '-').replace(/[()]/g, '_').replace(/[\/?]/g, '-');
                    return name === 'time';
                });
                let value = k.options[data['time']]
                return `${new Date(dateString).toLocaleDateString('en-US', options)}<br/> <span style='color:grey'>${value.name}</span>`;
            } else {
                return new Date(dateString).toLocaleDateString('en-US', options);
            }

        } else {
            return ""
        }
    }

    function getStatusHTML(raza) {
        let statusHTML = '<div class="text-left">' + '<ul>';

        if (raza['status'] == 0) {
            statusHTML += '<li><div><strong style="color: orange;">Pending</strong></div></li>';
        } else if (raza['status'] == 1) {
            statusHTML += '<li><div><strong style="color: blue;">Recommended</strong></div></li>';
        } else if (raza['status'] == 2) {
            statusHTML += '<li><div><strong style="color: limegreen;">Approved</strong></div></li>';
        } else if (raza['status'] == 3) {
            statusHTML += '<li><div><strong style="color: red;">Rejected</strong></div></li>';
        } else if (raza['status'] == 4) {
            statusHTML += '<li><div><strong style="color: blue;">Not Recommended</strong></div></li>';
        }

        statusHTML += '<li>';
        if (raza['coordinator-status'] == 0) {
            statusHTML += '<div>Jamat <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
        } else if (raza['coordinator-status'] == 1) {
            statusHTML += '<div>Jamat <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
        } else if (raza['coordinator-status'] == 2) {
            statusHTML += '<div>Jamat <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
        }
        statusHTML += '</li>';

        statusHTML += '<li>';
        if (raza['Janab-status'] == 0) {
            statusHTML += '<div>Amil Saheb <i class="fa-solid fa-clock" style="color: #fff700;"></i></div>';
        } else if (raza['Janab-status'] == 1) {
            statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-check" style="color: limegreen;"></i></div>';
        } else if (raza['Janab-status'] == 2) {
            statusHTML += '<div>Amil Saheb <i class="fa-solid fa-circle-xmark" style="color: red;"></i></div>';
        }
        statusHTML += '</li></ul></div>';

        return statusHTML;
    }

    function getActionHTML(raza) {
        // Implement your action HTML generation logic
        // You can use the same logic you have in your PHP code

        let actionHTML = '';
        if (raza['Janab-status'] == 0) {
            actionHTML = `
        <div class="action-buttons">
            <div class="button-group">
                <button type="button" class="btn btn-sm btn-primary remove-form-row" onclick="approve_raza(${raza['id']});">
                    <i class="fa fa-circle-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="reject_raza(${raza['id']});">
                    <i class="fa fa-circle-xmark"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="deleteRaza(${raza['id']});">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <div class="view-link btn btn-sm btn-primary remove-form-row">
                <a onclick="show_raza(${raza['id']});">
                    <span style=" cursor:pointer;">View</span>
                </a>
            </div>
        </div>
    `;
        } else {
            actionHTML = `
        <div class="view-link btn btn-sm btn-primary remove-form-row">
            <a onclick="show_raza(${raza['id']});">
                <span style=" cursor:pointer;">View</span>
            </a>
        </div>
    `;
        }

        return actionHTML;
    }

    function getEventDate(razadata) {
        // Implement logic to extract event date from razadata
        let data = JSON.parse(razadata);
        return data.date ? new Date(data.date) : null;
    }
</script>
<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.querySelector("table");
        switching = true;
        dir = "asc";  // Set the sorting direction to ascending

        // Make a loop that will continue until no switching has been done
        while (switching) {
            switching = false;
            rows = table.rows;

            // Loop through all table rows (except the first, which contains table headers)
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;

                // Get the two elements you want to compare, one from current row and one from the next
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                // Check if the two rows should switch place, based on the direction, asc or desc
                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                // If a switch has been marked, make the switch and mark that a switch has been done
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1
                switchcount++;
            } else {
                // If no switching has been done AND the direction is "asc", set the direction to "desc" and run the loop again
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

</script>
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });

</script>