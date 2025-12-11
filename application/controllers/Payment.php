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
    $access_code = 'ATPL85MK89BJ83LPJB';
    // $access_code = 'AVPL85MK89BJ83LPJB';
    $merchant_id = '4411587';

    // Build merchant data string expected by the CCAVENUE kit
    $params = [
      'merchant_id' => $merchant_id,
      'order_id' => $order_id,
      'amount' => number_format((float)$amount, 2, '.', ''),
      'currency' => 'INR',
      'redirect_url' => base_url('payment/ccavenue_response'),
      'cancel_url' => base_url('payment/ccavenue_response'),
      'language' => 'EN'
    ];

    $merchant_data = '';
    foreach ($params as $k => $v) {
      $merchant_data .= $k . '=' . $v . '&';
    }
    // Trim trailing ampersand to avoid malformed payloads (CCAvenue is strict)
    $merchant_data = rtrim($merchant_data, '&');

    // Include optional merchant params (ITS ID and takhmeen year) so response can be reconciled
    $its_id = $this->input->post('its_id');
    $takhmeen_year = $this->input->post('takhmeen_year');
    if (!empty($its_id)) {
      $params['merchant_param1'] = $its_id;
    }
    if (!empty($takhmeen_year)) {
      $params['merchant_param2'] = $takhmeen_year;
    }

    // Load Crypto functions from the payment library and encrypt
    include_once(APPPATH . 'libraries/payment/Crypto.php');
    $encrypted_data = encrypt($merchant_data, $working_key);

    // Log minimal outgoing payment info for debugging (do NOT log keys)
    log_message('info', 'CCAvenue initiate: order_id=' . $order_id . ' amount=' . $params['amount'] . ' merchant_id=' . $merchant_id . ' endpoint=test');

    // Render a small page that auto-submits to CCAvenue
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Redirecting to Payment</title></head><body>';
    echo '<form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">';
    // echo '<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">';
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

    // If merchant_param1 (ITS ID) exists, attempt to reconcile into fmb_takhmeen_payments
    $its_id = $data['merchant_param1'] ?? '';
    $takhmeen_year = $data['merchant_param2'] ?? '';
    if (!empty($its_id) && is_numeric($data['amount']) && $this->db->table_exists('fmb_takhmeen_payments')) {
      // Prepare payment data
      $paymentData = [
        'user_id' => $its_id,
        'payment_method' => 'CCAvenue',
        'amount' => (float)$data['amount'],
        'payment_date' => date('Y-m-d H:i:s'),
        'remarks' => 'Order:' . ($data['order_id'] ?? '') . ' Status:' . $order_status
      ];

      // Use AnjumanM model helper if available
      if (file_exists(APPPATH . 'models/AnjumanM.php')) {
        $this->load->model('AnjumanM');
        $ok = $this->AnjumanM->update_fmb_payment($paymentData);
      } else {
        // Direct insert as fallback
        $this->db->insert('fmb_takhmeen_payments', $paymentData);
        $ok = $this->db->affected_rows() > 0;
      }

      // Optionally update the payments_log row with reconciliation status
      if (!empty($log_id)) {
        $this->db->where('id', $log_id)->update($log_table, ['reconciled' => $ok ? 1 : 0]);
      }
    }

    // Render human-friendly response
    if ($order_status === 'Success') {
      echo "✅ Payment Successful for Order: " . htmlspecialchars($data['order_id'] ?? '') . '<br>';
    } elseif ($order_status === 'Aborted') {
      echo "Payment Aborted for Order: " . htmlspecialchars($data['order_id'] ?? '') . '<br>';
    } elseif ($order_status === 'Failure') {
      echo "Payment Failed for Order: " . htmlspecialchars($data['order_id'] ?? '') . '<br>';
    } else {
      echo "Security Error. Illegal access detected" . '<br>';
    }

    echo '<pre>' . htmlspecialchars(print_r($data, true)) . '</pre>';
  }
}
