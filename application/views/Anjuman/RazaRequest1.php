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
    .margintopcontainer{
        margin-inline: 10px;
    }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>





<div id="toast-message" class="toast-message">
    Successfull
</div>
<div class="margintopcontainer">
    <div class="pt-4">
        <p class="h4 text-center mt-5" style="color:goldenrod; text-transform: uppercase;"><?php echo $umoor ?></p>
        <div class="table-responsive mt-5 mb-5">
            <div class="table-container">
                <table id="fulltable" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th class="sno">Sno.</th>
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
                                    <?php formatEventDate($r) ?>
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
                                    <button type="button" class="btn btn-sm btn-warning remove-form-row" onclick="reject_raza(<?php echo $r['id'] ?>);"><i class="fa fa-circle-xmark"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger remove-form-row" onclick="redirectto(<?php echo 'anjuman/DeleteRaza/' . $r['id'] ?>);"><i class="fa fa-trash"></i></button>
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
            <button type="submit" class="btn btn-success w100percent-xs mbm-xs">Recommend</button>
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
            <button type="submit" class="btn btn-danger w100percent-xs mbm-xs">Not Recommend</button>
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
                url: "<?php echo base_url('admin/approveRaza'); ?>",
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
                url: "<?php echo base_url('admin/rejectRaza'); ?>",
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
            window.location.href = '<?php echo base_url() ?>' + 'anjuman/DeleteRaza/' + id;
        }
    }
</script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<?php
function formatEventDate($raza) {
    $data = json_decode($raza['razadata'], true);
    $rf = json_decode($raza['razafields'], true);
    $razafields = $rf['fields'];
    
    if (!empty($data['date'])) {
        $dateString = $data['date'];
        
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        $formattedDate = $date->format('D, d M');


        if (isset($data['time'])) {
            foreach ($razafields as $field) {
                $name = str_replace([' ', '(', ')', '/', '?'], ['-', '_', '_', '-', '-'], strtolower($field['name']));
                if ($name === 'time') {
                    $value = $field['options'][$data['time']];
                    echo $formattedDate . "<br/><span style='color:grey'>" . $value['name'] . "</span>";
                }
            }
        } else {
            echo $formattedDate;
        }
    } else {
        echo "";
    }
}

?>

<script>
    function refresh(){
        window.location.reload();
    }
    $(document).ready(function () {
        $('#fulltable').DataTable({
            "rowCallback": function(row, data, index) {
                $('td:eq(0)', row).html(index + 1);
            }
        });
    });
</script>