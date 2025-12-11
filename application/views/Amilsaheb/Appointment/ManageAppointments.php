<style>
    /* Styles for 'Not Attended' status */
    .status-not-attended {
        color: #d9534f;
        font-weight: bold;
    }

    /* Styles for 'Attended' status */
    .status-attended {
        color: #5cb85c;
        font-weight: bold;
    }
</style>
<div class="container mt-5 pt-5">
    <div class="row mb-4 p-0">
        <div class="col-12 col-md-6">
            <a href="<?php echo base_url("amilsaheb"); ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
    </div>
    <h1 class="text-center heading mb-4">Today's Appointment</h1>
    <hr>
    <div class="row gx-3 d-flex justify-content-center">
        <div class="col-6 col-xl-3 mb-3">
            <div class="card w-100" style="background-color: rgb(41, 128, 185);">
                <div class="card-body text-center">
                    <p class="h5" style="color: white;">Total</p>
                    <p class="h2" style="color: white;"><?php echo $total; ?></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3 mb-3">
            <div class="card w-100" style="background-color: rgb(39, 174, 96);">
                <div class="card-body text-center">
                    <p class="h5" style="color: white;">Attended</p>
                    <p class="h2" style="color: white;"><?php echo $attended; ?></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3 mb-3">
            <div class="card w-100" style="background-color: rgb(192, 57, 43);">
                <div class="card-body text-center">
                    <p class="h5" style="color: white;">Pending</p>
                    <p class="h2" style="color: white;"><?php echo $pending; ?></p>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="row text-center">-->
    <!--    <div class="col">-->
    <!--        <a href="<?php echo base_url('amilsaheb/all_appointment') ?>" style="color: black;text-decoration:underline">All Appointment's</a>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="row mt-4">
        <table class="table text-center table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Date</th>
                    <th scope="col">ITS</th>
                    <th scope="col">Name</th>
                    <th scope="col">Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($appointment_list as $key => $value) { ?>
                    <tr>
                        <th scope="row"><?php echo $key + 1 ?></th>
                        <td><?php echo date('D, d M ', strtotime($value['date'])) ?></td>
                        <td><?php echo $value['its'] ?></td>
                        <td><?php echo $value['name'] ?></td>
                        <td><?php echo $value['time'] ?></td>
                        <td class="<?= ($value['status'] == 0) ? 'status-not-attended' : 'status-attended'; ?>">
                            <?= ($value['status'] == 0) ? 'Pending' : 'Attended'; ?></td>
                        <td class="text-center">
                            <?php if ($value['status'] == 1) { ?>
                                <a href="<?php echo base_url('amilsaheb/update_appointment_list/') . $value['id'] ?>" data-toggle="tooltip" data-placement="top" title="Mark as Pending">
                                    <svg fill="#68d241" width="25px" height="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z"></path>
                                        </g>
                                    </svg>
                                </a>
                            <?php } else { ?>

                                <a href="<?php echo base_url('amilsaheb/update_appointment_list/') . $value['id'] ?>" data-toggle="tooltip" data-placement="top" title="Mark as Attended">
                                    <svg fill="#383937" width="25px" height="25px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M19.965 8.521C19.988 8.347 20 8.173 20 8c0-2.379-2.143-4.288-4.521-3.965C14.786 2.802 13.466 2 12 2s-2.786.802-3.479 2.035C6.138 3.712 4 5.621 4 8c0 .173.012.347.035.521C2.802 9.215 2 10.535 2 12s.802 2.785 2.035 3.479A3.976 3.976 0 0 0 4 16c0 2.379 2.138 4.283 4.521 3.965C9.214 21.198 10.534 22 12 22s2.786-.802 3.479-2.035C17.857 20.283 20 18.379 20 16c0-.173-.012-.347-.035-.521C21.198 14.785 22 13.465 22 12s-.802-2.785-2.035-3.479zm-9.01 7.895-3.667-3.714 1.424-1.404 2.257 2.286 4.327-4.294 1.408 1.42-5.749 5.706z"></path>
                                        </g>
                                    </svg>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
                ?>

            </tbody>
        </table>
    </div>
</div>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>