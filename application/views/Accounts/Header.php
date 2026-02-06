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
    margin-top: 80px;
  }

  /* Table sort indicators */
  th .ts-sort-controls {
    display: inline-flex;
    flex-direction: column;
    line-height: 8px;
    vertical-align: middle;
  }

  th .ts-sort-controls i {
    font-size: 12px;
    color: #999;
    cursor: pointer;
  }

  th.ts-asc .ts-sort-controls .ts-up,
  th.ts-desc .ts-sort-controls .ts-down {
    color: #000;
  }

  th .ts-sort-controls i.active {
    color: #000;
  }
</style>
<?php
// Determine the best display name for the header. Prefer explicit 'Full_Name' when available.
$display_name = '';
if (!empty($_SESSION['user_data']['Full_Name'])) {
  $display_name = $_SESSION['user_data']['Full_Name'];
} elseif (isset($member_name) && trim((string)$member_name) !== '') {
  $display_name = $member_name;
} elseif (!empty($_SESSION['user_data']['First_Name']) || !empty($_SESSION['user_data']['Surname'])) {
  $display_name = trim((!empty($_SESSION['user_data']['First_Name']) ? $_SESSION['user_data']['First_Name'] : '') . ' ' . (!empty($_SESSION['user_data']['Surname']) ? $_SESSION['user_data']['Surname'] : ''));
} elseif (isset($user_name) && trim((string)$user_name) !== '') {
  $display_name = $user_name;
}
// ITS ID to display next to member name
$its_id = isset($_SESSION['user_data']['ITS_ID']) ? $_SESSION['user_data']['ITS_ID'] : '';
?>
<div>
  <nav class="fixed-top navbar navbar-expand-lg navbar-light main-navbar">
    <div class="navbar-brand">
      <a href="<?php echo base_url("/") ?>">
        <img src="<?php echo base_url('assets/main_logo.png') ?>" class="logo">
      </a>
      <?php
      $navigation_link = base_url("/accounts/home/");
      if ($_SESSION['user']["username"] === 'admin') {
        $navigation_link = base_url("/admin/");
      } else if ($_SESSION['user']["username"] === 'jamaat') {
        $navigation_link = base_url("/anjuman/");
      } else if ($_SESSION['user']["username"] === 'amilsaheb') {
        $navigation_link = base_url("/amilsaheb/");
      } else {
        $navigation_link = base_url("/MasoolMusaid/");
      }
      ?>
      <a href="<?php echo $navigation_link ?>" class="user-welcome font-lvl-3-xs d-none d-md-inline-block">
        <?php echo htmlspecialchars(!empty($display_name) ? $display_name : 'Member Name'); ?><?php echo !empty($its_id) ? ', ' . htmlspecialchars($its_id) : ''; ?>
        <small class="text-muted"><?php echo isset($sector) ? '(' . htmlspecialchars($sector) . ')' : ""; ?></small>
      </a>
    </div>
    <button type="button" data-toggle="collapse" data-target="#sj-navbar-collapse" aria-controls="sj-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Mobile-only member name shown below logo and hamburger -->
    <div class="w-100 d-md-none text-center mt-3 mobile-member-name">
      <?php if (!empty($display_name)): ?>
        <div style="font-weight:700; color:#ad7e05; font-size:14px; text-transform:uppercase;"><?php echo htmlspecialchars($display_name); ?><?php echo !empty($its_id) ? ' (' . htmlspecialchars($its_id) . ')' : ''; ?></div>
        <?php if (isset($sector) && $sector !== ''): ?>
          <small class="text-muted"><?php echo htmlspecialchars($sector); ?></small>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <div id="sj-navbar-collapse" class="collapse navbar-collapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a href="<?php echo $navigation_link ?>" class="nav-link"><i
              class="fa fa-home px-1"></i>Home</a></li>
      </ul>
      <ul class="navbar-nav navbar-right">
        <li class="nav-item dropdown"><a href="#" role="button" data-toggle="dropdown"
            class="nav-link dropdown-toggle" aria-expanded="false"><i
              class="fa fa-user px-1"></i>Account</a>
          <div class="dropdown-menu">
            <a href="<?php echo base_url("/accounts/profile/") ?>" class="dropdown-item"><i
                class="fa fa-user px-1"></i>My Profile</a>
            <!-- <a href="<?php echo base_url('/accounts/changepassword/') ?>" class="dropdown-item"><i class="fa fa-lock px-1"></i>Change Password</a> -->
          </div>
        </li>
        <li class="nav-item"><a href="<?php echo base_url('/accounts/logout/') ?>" class="nav-link"><i
              class="fa fa-sign-out-alt px-1"></i>Log Out</a></li>
      </ul>
    </div>
  </nav>
</div>
<script>
  // Inject global footer once per page load (Accounts scope)
  (function() {
    if (document.getElementById('global-site-footer')) return; // avoid duplicates
    var jamaatName = <?php echo json_encode(jamaat_name()); ?>;
    var footerHtml = '\n<footer id="global-site-footer" class="site-footer" style="background:#222;color:#eee;font-size:14px;margin-top:40px;padding:32px 0 16px;">' +
      '<style>.site-footer a{color:#ffc107;text-decoration:none;} .site-footer a:hover{text-decoration:underline;color:#ffda55;} .site-footer h5{font-size:16px;font-weight:600;color:#fff;} .site-footer .footer-bottom{border-top:1px solid #444;margin-top:24px;padding-top:12px;font-size:12px;color:#bbb;} @media (max-width:767px){.site-footer{padding:24px 0;} .site-footer h5{margin-top:24px;} }</style>' +
      '<div class="container">' +
      '<div class="row">' +
      '<div class="col-md-4 col-sm-6"><h5>About</h5><p>' + jamaatName + ' platform for organized community participation.</p></div>' +
      '<div class="col-md-2 col-sm-6"><h5>Legal</h5><ul class="list-unstyled mb-0">' +
      '<li><a href="' + (window.BASE_URL || '<?php echo base_url(); ?>') + 'terms">Terms & Conditions</a></li>' +
      '<li><a href="' + (window.BASE_URL || '<?php echo base_url(); ?>') + 'privacy">Privacy Policy</a></li>' +
      '<li><a href="' + (window.BASE_URL || '<?php echo base_url(); ?>') + 'refund">Refund & Cancellation</a></li>' +
      '</ul></div>' +
      '<div class="col-md-3 col-sm-6"><h5>Contact</h5><p class="mb-1"><strong>Email:</strong> <a href="mailto:support@kharjamaat.in">support@kharjamaat.in</a></p><p class="mb-1"><strong>Phone:</strong> <a href="tel:+919000000000">+91-90000-00000</a></p><p class="mb-0"><strong>Hours:</strong> Mon-Sat 10:00–18:00 IST</p></div>' +
      '<div class="col-md-3 col-sm-6"><h5>Status</h5><p class="mb-2">Last Updated: <?php echo date('d M Y'); ?></p><p class="small mb-2">For payment or account queries contact support before disputes.</p></div>' +
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
<script src="<?php echo base_url('assets/js/table-sort.js'); ?>?v=1"></script>

  <?php if (empty($_COOKIE['km_cookie_consent'])): ?>
    <div id="cookie-consent-banner" class="alert alert-dark mb-0" role="alert" style="position:fixed;left:0;right:0;bottom:0;z-index:1050;border-radius:0;">
      <div class="container d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <div class="me-md-3">
          We use essential cookies to keep this site working. <a href="<?php echo base_url('privacy'); ?>" class="alert-link">Learn more</a>.
        </div>
        <div class="mt-2 mt-md-0">
          <button type="button" id="cookie-consent-accept" class="btn btn-warning btn-sm">Accept</button>
        </div>
      </div>
    </div>
    <script>
      (function() {
        var btn = document.getElementById('cookie-consent-accept');
        var banner = document.getElementById('cookie-consent-banner');
        if (!btn || !banner) return;
        btn.addEventListener('click', function() {
          btn.disabled = true;
          fetch('<?php echo base_url('cookies/accept'); ?>', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          }).then(function() {
            banner.style.display = 'none';
          }).catch(function() {
            btn.disabled = false;
          });
        });
      })();
    </script>
  <?php endif; ?>