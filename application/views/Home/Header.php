<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/8.3.2/introjs.css" integrity="sha512-YAfU5JMSgKNxNvnntWpFlrL0EQcFyMTLuKJ1VLDyJQ5qB/2h0mYyfCWuAUDbozKeK0wA5iMpQkgMkPwqlYhF4g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/8.3.2/intro.min.js" integrity="sha512-5ykRz1AkSpATwLO5fFqJb7Y5kprfzu/q9PbRVSOspLcn2Tm7m6vZIAyoUA4a5GFNRIlxM43kHXmYEphfz3eOWA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

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
</style>
<div>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fef7e6">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="<?php echo base_url("/") ?>">
                    <img src="<?php echo base_url('assets/main_logo.png') ?>" class="logo" />
                </a>
            </div>
            <button class="navbar-toggler ms-auto hamburger" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon btn-sm"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav me-auto mb-lg-0">
                    <!-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo base_url() ?>">
                            <i class="fa fa-home px-1"></i>Home
                        </a>
                    </li> -->
                </ul>
                <ul class="navbar-nav navbar-right">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('accounts/register') ?>">
                            <i class="fa fa-user-plus px-1"></i>Register
                        </a>
                    </li>
                </ul>
                <a href="<?php echo base_url('accounts') ?>">
                    <button type="button" class="btn btn-warning">
                        <i class="fa fa-user px-1"></i>Login
                    </button>
                </a>
            </div>
        </div>
    </nav>
</div>