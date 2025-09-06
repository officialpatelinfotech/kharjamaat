<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .icon {
        font-size: 40pt;
        margin: 10px 0;
        color: #ffffff;
    }

    .title {
        color: white;
    }

    .heading {
        color: #ad7e05;
        font-family: 'Amita', cursive;
    }

    .card {
        height: 153px;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
    }

    .row a {
        text-decoration: none;
        color: inherit;
    }
</style>
<div class="container margintopcontainer">
    <h1 class="text-center heading pt-5 mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
    <hr>
    <div class="continer d-flex justify-content-center">
        <div class="row container mt-5">
            <a href="<?php echo base_url('MasoolMusaid/mumineendirectory') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Mumineen Directory</div>
                        <i class="fa-solid icon fa-clipboard-user"></i>
                    </div>
                </div>
            </a>
            <a href="<?php echo base_url('MasoolMusaid/asharaohbat') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Ashara Ohbat 1446</div>
                        <i class="fa-solid icon fa-calendar-days"></i>
                    </div>
                </div>
            </a>
            <a href="<?php echo base_url('MasoolMusaid/ashara_attendance') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Ashara Attendance</div>
                        <i class="fa-solid icon fa-user-check"></i>
                    </div>
                </div>
            </a>
            <a href="<?php echo base_url('MasoolMusaid/rsvp_list') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Miqaat RSVP</div>
                        <i class="fa-solid icon fa-users"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<script>
    const colors = ["rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",
        "rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",
        "rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",]
    $(document).ready(function () {
        $(".card").each(function (i, el) {
            this.style.backgroundColor = colors[i];
        });
    })
</script>
<script>
    // Disable browser back button
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.pushState(null, null, location.href);
    };
</script>