<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap"
  rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
  integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
  crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.css">

<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"
  integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.js"></script>
<script src="https://kit.fontawesome.com/e50fe14bb8.js" crossorigin="anonymous"></script>
<title><?php echo htmlspecialchars(jamaat_name(), ENT_QUOTES, 'UTF-8'); ?></title>
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
      <a href="<?php echo isset($from) ? base_url("/?from=" . explode("/", $from)[0]) : ""; ?>" style="text-decoration: none;">
        <img src="<?php echo base_url('assets/main_logo.png') ?>" class="logo">
      </a>
    </div>

    <button type="button" data-toggle="collapse" data-target="#sj-navbar-collapse" class="navbar-toggler">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="sj-navbar-collapse" class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a href="<?php echo isset($from) ? base_url(explode("/", $from)[0]) : ""; ?>" class="nav-link">
            <i class="fa fa-home px-1"></i>Home
          </a>
        </li>

        <!-- <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-life-ring px-1"></i>Help
          </a>
          <div class="dropdown-menu">
            <a href="<?php echo $active_controller . "/request-help"; ?>" class="dropdown-item">Help Desk</a>
          </div>
        </li> -->
      </ul>

      <ul class="navbar-nav navbar-right">
        <li class="nav-item mr-2" id="km-export-excel-wrap" style="display:none;">
          <button type="button" id="km-export-excel-btn" class="btn btn-outline-secondary btn-sm" title="Export current table to Excel (CSV)">
            <i class="fa fa-file-excel-o px-1"></i>Export Excel
          </button>
        </li>
        <li class="nav-item">
          <span class="nav-link user-welcome font-lvl-3-xs">
            <?php echo $user_name ?>
          </span>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url('/accounts/logout/') ?>" class="nav-link">
            <i class="fa fa-sign-out-alt px-1"></i>Log Out
          </a>
        </li>
      </ul>
    </div>
  </nav>
</div>
<script>
  // Inject global footer for Common (admin/ops) pages
  (function() {
    if (document.getElementById('global-site-footer')) return;
    var jamaatName = <?php echo json_encode(jamaat_name()); ?>;
    var footerHtml = '\n<footer id="global-site-footer" class="site-footer" style="background:#222;color:#eee;font-size:14px;margin-top:40px;padding:32px 0 16px;">' +
      '<style>.site-footer a{color:#ffc107;text-decoration:none;} .site-footer a:hover{text-decoration:underline;color:#ffda55;} .site-footer h5{font-size:16px;font-weight:600;color:#fff;} .site-footer .footer-bottom{border-top:1px solid #444;margin-top:24px;padding-top:12px;font-size:12px;color:#bbb;} @media (max-width:767px){.site-footer{padding:24px 0;} .site-footer h5{margin-top:24px;} }</style>' +
      '<div class="container">' +
      '<div class="row">' +
      '<div class="col-md-4 col-sm-6"><h5>About</h5><p>' + jamaatName + ' operational dashboard.</p></div>' +
      '<div class="col-md-2 col-sm-6"><h5>Legal</h5><ul class="list-unstyled mb-0">' +
      '<li><a href="<?php echo base_url('terms'); ?>">Terms & Conditions</a></li>' +
      '<li><a href="<?php echo base_url('privacy'); ?>">Privacy Policy</a></li>' +
      '<li><a href="<?php echo base_url('refund'); ?>">Refund & Cancellation</a></li>' +
      '</ul></div>' +
      '<div class="col-md-3 col-sm-6"><h5>Contact</h5><p class="mb-1"><strong>Email:</strong> <a href="mailto:support@kharjamaat.in">support@kharjamaat.in</a></p><p class="mb-1"><strong>Phone:</strong> <a href="tel:+919000000000">+91-90000-00000</a></p><p class="mb-0"><strong>Hours:</strong> Mon-Sat 10:00–18:00 IST</p></div>' +
      '<div class="col-md-3 col-sm-6"><h5>Status</h5><p class="mb-2">Last Updated: <?php echo date('d M Y'); ?></p><p class="small mb-2">Internal use only.</p></div>' +
      '</div>' +
      '<div class="footer-bottom text-center"><span>&copy; <?php echo date('Y'); ?> ' + jamaatName + '. All rights reserved.</span></div>' +
      '</div></footer>';
    var div = document.createElement('div');
    div.innerHTML = footerHtml;
    document.addEventListener('DOMContentLoaded', function() {
      document.body.appendChild(div.firstChild);
    });
  })();
</script>
<script src="<?php echo base_url('assets/js/table-export.js'); ?>?v=1"></script>