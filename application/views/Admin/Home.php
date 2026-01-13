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
      <!-- <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('admin/RazaRequest'); ?>">-->
      <!--    <div class="card text-center">-->
      <!--        <div class="card-body d-flex flex-column justify-content-between">-->
      <!--            <div class="title">Raza Request</div>-->
      <!--            <i class="icon fa-solid fa-hands-holding"></i>-->
      <!--        </div>-->
      <!--    </div>-->
      <!--</a> -->
      <!-- <a href="<?php echo base_url('admin/managemiqaat'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Miqaat</div>
            <i class="icon fa-solid fa-calendar-days"></i>
          </div>
        </div>
      </a> -->
      <a href="<?php echo base_url('common/fmbcalendar?from=admin'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">FMB Calendar</div>
            <i class="icon fa-solid fa-calendar-days"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/razalist'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Raza Form</div>
            <i class="icon fa-solid fa-list-check"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managefmbsettings'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage FMB Thaali & Niyaz</div>
            <i class="icon fa-solid fa-plate-wheat"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managesabeeltakhmeen'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Sabeel Takhmeen</div>
            <i class="icon fa-solid fa-hand-holding-heart"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/managemembers'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Manage Members</div>
            <i class="icon fa-solid fa-users-cog"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/corpusfunds'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Corpus Funds</div>
            <i class="icon fa-solid fa-donate"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/wajebaat'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Wajebaat</div>
            <i class="icon fa-solid fa-book"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/qardan_hasana'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Qardan Hasana</div>
            <i class="icon fa-solid fa-handshake"></i>
          </div>
        </div>
      </a>
      <a href="<?php echo base_url('admin/expense'); ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
        <div class="card text-center">
          <div class="card-body d-flex flex-column justify-content-between">
            <div class="title">Expense Module</div>
            <i class="icon fa-solid fa-receipt"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
<script>
  const colors = ["rgb(142, 68, 173)",
    "rgb(243, 156, 18)",
    "rgb(0, 106, 63)",
    "rgb(41, 128, 185)",
    "rgb(135, 0, 0)",
    "rgb(211, 84, 0)",
    "rgb(192, 57, 43)",
    "rgb(39, 174, 96)",
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
    "rgb(41, 128, 185)",
  ]
  $(document).ready(function() {
    $(".card").each(function(i, el) {
      this.style.backgroundColor = colors[i];
    });
  })
</script>