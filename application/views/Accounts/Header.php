<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap"
  rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Intro CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/8.3.2/introjs.css" integrity="sha512-YAfU5JMSgKNxNvnntWpFlrL0EQcFyMTLuKJ1VLDyJQ5qB/2h0mYyfCWuAUDbozKeK0wA5iMpQkgMkPwqlYhF4g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
  integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
  crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Intro JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/8.3.2/intro.min.js" integrity="sha512-5ykRz1AkSpATwLO5fFqJb7Y5kprfzu/q9PbRVSOspLcn2Tm7m6vZIAyoUA4a5GFNRIlxM43kHXmYEphfz3eOWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"
  integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://kit.fontawesome.com/e50fe14bb8.js" crossorigin="anonymous"></script>
<title>Khar Jamaat</title>
<link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/header_logo.png'); ?>">
<style>
  .navbar-brand .logo {
    max-height: 30px;
  }

  @media (max-width: 768px) {

    .btn,
    .btn-group {
      width: 100%;
    }
  }

  .user-welcome {
    font-weight: 600;
    margin-left: 10px;
    font-size: 14px;
    text-transform: uppercase;
    margin-left: 20px;
  }

  .user-welcome,
  .user-welcome:hover,
  .navbar-brand {
    color: #ad7e05 !important;
  }

  nav {
    border-top: 1px solid silver;
    border-bottom: 1px solid silver;
    background-color: #fef7e6;
  }

  .margintopcontainer {
    margin-top: 57px;
  }
</style>
<div>
  <nav class="fixed-top navbar navbar-expand-lg navbar-light main-navbar">
    <div class="navbar-brand">
      <a href="<?php echo base_url("/") ?>">
        <img src="<?php echo base_url('assets/main_logo.png') ?>" class="logo">
      </a>
      <a href="<?php echo base_url("/accounts/home/") ?>" class="user-welcome font-lvl-3-xs">
        <?php echo isset($member_name) ? $member_name : "Member Name"; ?>
        <small class="text-muted">(<?php echo isset($sector) ? $sector : "Sector"; ?>)</small>
      </a>
    </div>
    <button type="button"
      data-toggle="collapse" data-target="#sj-navbar-collapse" aria-controls="sj-navbar-collapse"
      aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span
        class="navbar-toggler-icon"></span></button>
    <div id="sj-navbar-collapse" class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="<?php echo base_url("/accounts/home/") ?>" class="nav-link"><i
              class="fa fa-home px-1"></i>Home</a></li>
        <!--<li class="nav-item dropdown"><a href="#" role="button" data-toggle="dropdown"-->
        <!--        class="nav-link dropdown-toggle" aria-expanded="false"><i-->
        <!--            class="fa fa-life-ring px-1"></i>Help</a>-->
        <!--    <div class="dropdown-menu"><a href="<?php echo base_url("accounts/request-help") ?>"-->
        <!--            class="dropdown-item">Help Desk</a></div>-->
        <!--</li>-->
      </ul>
      <ul class="navbar-nav navbar-right">
        <!--<li class="nav-item dropdown"><a href="#" role="button" data-toggle="dropdown"-->
        <!--        class="nav-link dropdown-toggle" aria-expanded="false"><i-->
        <!--            class="fa fa-file-alt px-1"></i>Jamaat Forms</a>-->
        <!--    <div class="dropdown-menu"><a href="<?php echo base_url('uploads/jamaat-form/menu-form.pdf') ?>"-->
        <!--            target="_blank" class="dropdown-item" downolad="menu-form.pdf">Menu Form for Jaman Karnaar</a></div>-->
        <!--</li>-->
        <li class="nav-item dropdown"><a href="#" role="button" data-toggle="dropdown"
            class="nav-link dropdown-toggle" aria-expanded="false"><i
              class="fa fa-user px-1"></i>Account</a>
          <div class="dropdown-menu">
            <a href="<?php echo base_url("/accounts/profile/") ?>" class="dropdown-item"><i
                class="fa fa-user px-1"></i>My Profile</a>
            <a href="<?php echo base_url('/accounts/changepassword/') ?>" class="dropdown-item"><i
                class="fa fa-lock px-1"></i>Change Password</a>
          </div>
        </li>
        <li class="nav-item"><a href="<?php echo base_url('/accounts/logout/') ?>" class="nav-link"><i
              class="fa fa-sign-out-alt px-1"></i>Log Out</a></li>
      </ul>
    </div>
  </nav>
</div>