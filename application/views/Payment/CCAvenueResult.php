<?php
// Minimal standalone result page shown after CCAvenue callback.
// Expects: $title, $message, $redirect_url, $seconds
$title = isset($title) ? (string)$title : 'Payment Status';
$message = isset($message) ? (string)$message : '';
$redirect_url = isset($redirect_url) ? (string)$redirect_url : '';
$seconds = isset($seconds) ? (int)$seconds : 3;
if ($seconds < 0) $seconds = 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($title); ?></title>
  <?php if (!empty($redirect_url) && $seconds >= 0): ?>
    <meta http-equiv="refresh" content="<?php echo (int)$seconds; ?>;url=<?php echo htmlspecialchars($redirect_url); ?>">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
    integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <style>
    body { font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; }
  </style>
</head>
<body>
  <div class="bg-light min-vh-100 d-flex align-items-center">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="card shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                  <?php
                    $t = strtolower($title);
                    $is_success = (strpos($t, 'success') !== false);
                    $icon = $is_success ? 'fa-check-circle' : 'fa-exclamation-triangle';
                    $icon_class = $is_success ? 'text-success' : 'text-warning';
                  ?>
                  <i class="fa <?php echo $icon; ?> <?php echo $icon_class; ?>" style="font-size:28px"></i>
                </div>
                <div>
                  <h4 class="mb-1"><?php echo htmlspecialchars($title); ?></h4>
                  <?php if ($message !== ''): ?>
                    <div class="text-muted"><?php echo htmlspecialchars($message); ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <?php if (!empty($redirect_url)): ?>
                <div class="alert alert-secondary mb-3" role="alert">
                  Redirecting in <strong><span id="sec"><?php echo (int)$seconds; ?></span></strong> seconds…
                </div>
                <div class="d-flex flex-column flex-sm-row">
                  <a class="btn btn-primary" href="<?php echo htmlspecialchars($redirect_url); ?>">Continue</a>
                </div>
                <noscript>
                  <div class="text-muted mt-3">JavaScript is disabled. Please click Continue.</div>
                </noscript>
              <?php else: ?>
                <div class="alert alert-warning" role="alert">
                  No redirect URL configured.
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="text-center text-muted small mt-3">You can close this tab after redirect.</div>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($redirect_url) && $seconds > 0): ?>
  <script>
    (function(){
      var el=document.getElementById('sec');
      if(!el) return;
      var s=parseInt(el.textContent||'0',10)||0;
      var t=setInterval(function(){
        s=Math.max(0,s-1);
        el.textContent=String(s);
        if(s<=0) {
          clearInterval(t);
          window.location.href = <?php echo json_encode($redirect_url); ?>;
        }
      },1000);
    })();
  </script>
  <?php endif; ?>
</body>
</html>
