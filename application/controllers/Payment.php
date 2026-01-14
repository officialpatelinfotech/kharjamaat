<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function pay()
  {
    $amount = $this->input->post('amount');

    $redirectUrl = base_url("payment/response");

    $merchantTransactionId = "M23OQY1FW5QOO" . time();
    $response = $this->phonepe_pg->initiatePayment($merchantTransactionId, $amount * 100, $redirectUrl, "Payment for Order: " . $merchantTransactionId);

    if (isset($response['error'])) {
      echo "Error initiating payment: " . $response['error'];
    } else {
      echo "Payment initiated. Please complete the payment on PhonePe.";
    }
  }

  public function response()
  {
    $post = $this->input->post();
    log_message('info', 'PhonePe Response: ' . json_encode($post));

    if (isset($post['merchantTransactionId'])) {
      $orderId = $post['merchantTransactionId'];
      $status  = $post['code'];

      if ($status === "PAYMENT_SUCCESS") {
        echo "✅ Payment Successful for Order: " . $orderId;
        // update DB
      } else {
        echo "❌ Payment Failed for Order: " . $orderId;
        // update DB
      }
    } else {
      echo "Invalid Response";
    }
  }

  /**
   * Initiate payment via CCAvenue using the local payment library (non-PhonePe).
   * This builds the merchant payload, encrypts it using Crypto.php and auto-posts
   * to CCAvenue's initiateTransaction endpoint.
   */
  public function ccavenue_takhmeen()
  {
    $amount = $this->input->post('amount');
    $order_id = $this->input->post('order_id') ?: ('TAKHMEEN-' . date('YmdHis'));

    if (empty($amount) || $amount <= 0) {
      show_error('Invalid amount for payment');
      return;
    }

    // CCAvenue credentials (kept inline as in the integration kit)
    $working_key = '3192DCC09548EAC34B7492AD528DEABB';
    // $access_code = 'ATPL85MK89BJ83LPJB';
    $access_code = 'AVPL85MK89BJ83LPJB';
    $merchant_id = '4411587';

    // Determine the return host to send to CCAvenue. Prefer the current Host
    // unless it's a development host (localhost/127.0.0.1), in which case
    // fall back to the production-like hostname `kharjamaat.in` so gateway
    // sees a valid merchant domain during testing on local machine.
    $currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $isLocalhost = preg_match('/^(localhost|127\.0\.0\.1|::1)$/i', $currentHost);
    if (!$isLocalhost && !empty($currentHost)) {
      $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
      $baseReturn = $scheme . '://' . $currentHost;
    } else {
      // Explicit mocked host for local testing
      $baseReturn = 'http://kharjamaat.in';
    }

    // Include optional merchant params (ITS ID and takhmeen year) so response can be reconciled
    $its_id = $this->input->post('its_id');
    $takhmeen_year = $this->input->post('takhmeen_year');

    // Build merchant data string expected by the CCAVENUE kit
    $params = [
      'merchant_id' => $merchant_id,
      'order_id' => $order_id,
      'amount' => number_format((float)$amount, 2, '.', ''),
      'currency' => 'INR',
      'redirect_url' => rtrim($baseReturn, '/') . '/payment/ccavenue_response',
      'cancel_url' => rtrim($baseReturn, '/') . '/payment/ccavenue_response',
      'language' => 'EN'
    ];
    if (!empty($its_id)) {
      $params['merchant_param1'] = $its_id;
    }
    if (!empty($takhmeen_year)) {
      $params['merchant_param2'] = $takhmeen_year;
    }
    // Preserve current CI session id so the callback doesn't overwrite login
    // (CCAvenue POST back is cross-site; SameSite=Lax cookies won't be sent).
    $sid = session_id();
    if (!empty($sid)) {
      $params['merchant_param5'] = $sid;
    }

    $merchant_data = '';
    foreach ($params as $k => $v) {
      $merchant_data .= $k . '=' . $v . '&';
    }
    // Trim trailing ampersand to avoid malformed payloads (CCAvenue is strict)
    $merchant_data = rtrim($merchant_data, '&');

    // Load Crypto functions from the payment library and encrypt
    include_once(APPPATH . 'libraries/payment/Crypto.php');
    $encrypted_data = encrypt($merchant_data, $working_key);

    // Log minimal outgoing payment info for debugging (do NOT log keys)
    log_message('info', 'CCAvenue initiate: order_id=' . $order_id . ' amount=' . $params['amount'] . ' merchant_id=' . $merchant_id . ' redirect=' . $params['redirect_url']);

    // Render a small page that auto-submits to CCAvenue
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Redirecting to Payment</title></head><body>';
    // echo '<form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">';
    echo '<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">';
    echo '<input type="hidden" name="encRequest" value="' . htmlspecialchars($encrypted_data) . '">';
    echo '<input type="hidden" name="access_code" value="' . htmlspecialchars($access_code) . '">';
    echo '</form>';
    echo '<script>document.redirect.submit();</script></body></html>';
  }

  /**
   * Initiate Sabeel takhmeen payment via CCAvenue.
   * Captures ITS ID + payment type (establishment/residential) via merchant params.
   */
  public function ccavenue_sabeel()
  {
    $amount = $this->input->post('amount');
    $order_id = $this->input->post('order_id') ?: ('SABEEL-TAKHMEEN-' . date('YmdHis'));
    $its_id = $this->input->post('its_id');
    $payment_type = strtolower(trim((string) $this->input->post('payment_type')));

    if (empty($amount) || $amount <= 0) {
      show_error('Invalid amount for payment');
      return;
    }
    if ($payment_type !== 'establishment' && $payment_type !== 'residential') {
      show_error('Invalid payment type');
      return;
    }
    if (empty($its_id)) {
      show_error('Missing ITS ID');
      return;
    }

    // CCAvenue credentials (kept inline as in the integration kit)
    $working_key = '3192DCC09548EAC34B7492AD528DEABB';
    $access_code = 'AVPL85MK89BJ83LPJB';
    $merchant_id = '4411587';

    // Determine return host (same logic as takhmeen)
    $currentHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $isLocalhost = preg_match('/^(localhost|127\.0\.0\.1|::1)$/i', $currentHost);
    if (!$isLocalhost && !empty($currentHost)) {
      $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
      $baseReturn = $scheme . '://' . $currentHost;
    } else {
      $baseReturn = 'http://kharjamaat.in';
    }

    $params = [
      'merchant_id' => $merchant_id,
      'order_id' => $order_id,
      'amount' => number_format((float) $amount, 2, '.', ''),
      'currency' => 'INR',
      'redirect_url' => rtrim($baseReturn, '/') . '/payment/ccavenue_response',
      'cancel_url' => rtrim($baseReturn, '/') . '/payment/ccavenue_response',
      'language' => 'EN',
      // Merchant params for reconciliation
      'merchant_param1' => (string) $its_id,
      'merchant_param3' => $payment_type,
      'merchant_param4' => 'SABEEL'
    ];
    // Preserve current CI session id so the callback doesn't overwrite login
    $sid = session_id();
    if (!empty($sid)) {
      $params['merchant_param5'] = $sid;
    }

    $merchant_data = '';
    foreach ($params as $k => $v) {
      $merchant_data .= $k . '=' . $v . '&';
    }
    $merchant_data = rtrim($merchant_data, '&');

    include_once(APPPATH . 'libraries/payment/Crypto.php');
    $encrypted_data = encrypt($merchant_data, $working_key);

    log_message('info', 'CCAvenue initiate (Sabeel): order_id=' . $order_id . ' amount=' . $params['amount'] . ' merchant_id=' . $merchant_id . ' redirect=' . $params['redirect_url'] . ' type=' . $payment_type);

    echo '<!doctype html><html><head><meta charset="utf-8"><title>Redirecting to Payment</title></head><body>';
    echo '<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">';
    echo '<input type="hidden" name="encRequest" value="' . htmlspecialchars($encrypted_data) . '">';
    echo '<input type="hidden" name="access_code" value="' . htmlspecialchars($access_code) . '">';
    echo '</form>';
    echo '<script>document.redirect.submit();</script></body></html>';
  }

  /**
   * Handle CCAvenue response (decrypt using Crypto.php and show parsed data).
   */
  public function ccavenue_response()
  {
    include_once(APPPATH . 'libraries/payment/Crypto.php');
    $workingKey = '3192DCC09548EAC34B7492AD528DEABB';

    $encResponse = $this->input->post('encResp');
    if (empty($encResponse)) {
      echo 'No response received from gateway.';
      return;
    }

    $rcvdString = decrypt($encResponse, $workingKey);
    $decryptValues = explode('&', $rcvdString);
    $data = [];
    foreach ($decryptValues as $pair) {
      $parts = explode('=', $pair, 2);
      if (count($parts) === 2) $data[trim($parts[0])] = trim($parts[1]);
    }

    $order_status = isset($data['order_status']) ? $data['order_status'] : '';

    // --- Session continuity fix ---
    // CCAvenue posts back cross-site, so CI session cookie might not be included
    // (SameSite=Lax). When that happens, a new session id can be generated and
    // the Set-Cookie header overwrites the logged-in session.
    // We pass the original session id via merchant_param5 and restore it here.
    $origSid = (string)($data['merchant_param5'] ?? '');
    if ($origSid !== '' && preg_match('/^[a-zA-Z0-9,-]{16,128}$/', $origSid)) {
      $cookieName = config_item('sess_cookie_name') ?: session_name();
      $cookiePath = config_item('cookie_path') ?: '/';
      $cookieDomain = (string)(config_item('cookie_domain') ?: '');
      $cookieSecure = (bool)(config_item('cookie_secure') ?: FALSE);
      $cookieSameSite = (string)(config_item('cookie_samesite') ?: 'Lax');
      $sessExpiration = (int)(config_item('sess_expiration') ?? 0);
      $cookieExpires = ($sessExpiration > 0) ? (time() + $sessExpiration) : 0;
      // Ensure browser keeps the original session after redirect
      if (is_php('7.3')) {
        setcookie(
          $cookieName,
          $origSid,
          [
            'expires' => $cookieExpires,
            'path' => $cookiePath,
            'domain' => $cookieDomain,
            'secure' => $cookieSecure,
            'httponly' => TRUE,
            'samesite' => $cookieSameSite
          ]
        );
      } else {
        $header = 'Set-Cookie: ' . $cookieName . '=' . $origSid;
        if ($cookieExpires > 0) {
          $header .= '; Expires=' . gmdate('D, d M Y H:i:s', $cookieExpires) . ' GMT';
          $header .= '; Max-Age=' . $sessExpiration;
        }
        $header .= '; Path=' . $cookiePath;
        $header .= ($cookieDomain !== '' ? '; Domain=' . $cookieDomain : '');
        $header .= ($cookieSecure ? '; Secure' : '') . '; HttpOnly; SameSite=' . $cookieSameSite;
        header($header);
      }
      $_COOKIE[$cookieName] = $origSid;
    }

    // Persist gateway response to a payments_log table (configurable)
    $this->load->config('payment');
    $log_table = $this->config->item('payments_log_table') ?: 'payments_log';

    $log_row = [
      'gateway' => 'CCAvenue',
      'order_id' => $data['order_id'] ?? '',
      'amount' => $data['amount'] ?? '',
      'status' => $order_status,
      'tracking_id' => $data['tracking_id'] ?? '',
      'bank_ref_no' => $data['bank_ref_no'] ?? '',
      'merchant_param1' => $data['merchant_param1'] ?? '',
      'merchant_param2' => $data['merchant_param2'] ?? '',
      'raw' => $rcvdString,
      'created_at' => date('Y-m-d H:i:s')
    ];

    if ($this->db->table_exists($log_table)) {
      $this->db->insert($log_table, $log_row);
      $log_id = $this->db->insert_id();
    } else {
      $log_id = null;
    }

    // Reconcile into takhmeen payments tables on success
    $its_id = $data['merchant_param1'] ?? '';
    $purpose = strtoupper(trim((string) ($data['merchant_param4'] ?? '')));
    $sabeel_type = strtolower(trim((string) ($data['merchant_param3'] ?? '')));
    $amount_val = (isset($data['amount']) && is_numeric($data['amount'])) ? (float) $data['amount'] : null;

    // Parse gateway trans_date if available (format: d/m/Y H:i:s)
    $payment_dt = date('Y-m-d H:i:s');
    if (!empty($data['trans_date'])) {
      $dt = DateTime::createFromFormat('d/m/Y H:i:s', (string) $data['trans_date']);
      if ($dt instanceof DateTime) {
        $payment_dt = $dt->format('Y-m-d H:i:s');
      }
    }

    if ($order_status === 'Success' && !empty($its_id) && $amount_val !== null) {
      $isSabeel = ($purpose === 'SABEEL') || (isset($data['order_id']) && strpos((string) $data['order_id'], 'SABEEL-') === 0);

      // Sabeel reconciliation
      if ($isSabeel && ($sabeel_type === 'establishment' || $sabeel_type === 'residential') && $this->db->table_exists('sabeel_takhmeen_payments')) {
        $remarks = 'Order:' . ($data['order_id'] ?? '')
          . ' Tracking:' . ($data['tracking_id'] ?? '')
          . ' Status:' . $order_status;

        // Basic idempotency: skip if we already have a row with this order in remarks
        $already = false;
        $oid = (string) ($data['order_id'] ?? '');
        if ($oid !== '') {
          $q = $this->db->query(
            "SELECT id FROM sabeel_takhmeen_payments WHERE user_id = ? AND remarks LIKE ? LIMIT 1",
            [$its_id, '%' . $oid . '%']
          );
          $already = ($q && $q->num_rows() > 0);
        }

        if (!$already) {
          $this->load->model('AnjumanM');
          $ok = $this->AnjumanM->update_sabeel_payment([
            'user_id' => $its_id,
            'payment_method' => 'Online',
            'type' => $sabeel_type,
            'amount' => $amount_val,
            'payment_date' => $payment_dt,
            'remarks' => $remarks,
          ]);
          if (!empty($log_id)) {
            $this->db->where('id', $log_id)->update($log_table, ['reconciled' => $ok ? 1 : 0]);
          }
        }
      }

      // FMB reconciliation (existing behavior)
      if (!$isSabeel && $this->db->table_exists('fmb_takhmeen_payments')) {
        $paymentData = [
          'user_id' => $its_id,
          'payment_method' => 'CCAvenue',
          'amount' => $amount_val,
          'payment_date' => $payment_dt,
          'remarks' => 'Order:' . ($data['order_id'] ?? '') . ' Status:' . $order_status
        ];

        if (file_exists(APPPATH . 'models/AnjumanM.php')) {
          $this->load->model('AnjumanM');
          $ok = $this->AnjumanM->update_fmb_payment($paymentData);
        } else {
          $this->db->insert('fmb_takhmeen_payments', $paymentData);
          $ok = $this->db->affected_rows() > 0;
        }

        if (!empty($log_id)) {
          $this->db->where('id', $log_id)->update($log_table, ['reconciled' => $ok ? 1 : 0]);
        }
      }
    }

    // After processing, redirect back to the appropriate UI page.
    // Show a brief status page, then redirect (better UX than instant redirect).
    $oid = (string)($data['order_id'] ?? '');
    $statusFlag = strtolower((string)$order_status);
    $query = http_build_query([
      'cc_status' => $statusFlag,
      'order_id' => $oid,
    ]);

    $redirectUrl = base_url('accounts/viewfmbtakhmeen');

    // Decide destination based on purpose/order id
    if ((isset($purpose) && strtoupper($purpose) === 'SABEEL') || (strpos($oid, 'SABEEL-') === 0)) {
      $redirectUrl = base_url('accounts/ViewSabeelTakhmeen');
    } elseif (strpos($oid, 'ADMIN-TAKHMEEN-') === 0) {
      $redirectUrl = base_url('common/takhmeen_pay');
    } else {
      $redirectUrl = base_url('accounts/viewfmbtakhmeen');
    }

    $redirectUrl = $redirectUrl . ($query ? ('?' . $query) : '');

    $isSuccess = (strtolower((string)$order_status) === 'success');
    $viewData = [
      'title' => $isSuccess ? 'Payment Successful' : 'Payment Not Completed',
      'message' => $isSuccess ? 'Your payment was successful.' : 'Your payment was not completed. Please try again if needed.',
      'redirect_url' => $redirectUrl,
      'seconds' => 3,
    ];

    $this->load->view('Payment/CCAvenueResult', $viewData);
    return;
  }
}
