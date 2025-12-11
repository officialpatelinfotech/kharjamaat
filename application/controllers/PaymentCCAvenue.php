<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentCCAvenue extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper(['url']);
        $this->load->database();
    }

    // Simple checkout form served via app
    public function checkout() {
        $data = [
            'action' => site_url('payment/ccavenue/initiate'),
            'callback' => site_url('payment/ccavenue/callback'),
        ];
        $this->load->view('Payment/checkout', $data);
    }

    // Initiates payment by encrypting form data and redirecting to CC Avenue
    public function initiate() {
        // Basic guard: require order fields
        $order_id = $this->input->post('order_id');
        $amount = $this->input->post('amount');
        $merchant_id = $this->input->post('merchant_id');
        if (empty($order_id) || empty($amount) || empty($merchant_id)) {
            show_error('Missing required payment fields', 400);
            return;
        }

        // Load keys from environment to avoid hardcoding
        // Read credentials from env or CI config fallback
        $this->load->config('payment');
        $working_key = getenv('CCAVENUE_WORKING_KEY');
        $access_code = getenv('CCAVENUE_ACCESS_CODE');
        if (empty($working_key)) {
            $working_key = $this->config->item('ccavenue_working_key');
        }
        if (empty($access_code)) {
            $access_code = $this->config->item('ccavenue_access_code');
        }
        if (empty($working_key) || empty($access_code)) {
            show_error('CCAvenue credentials not configured', 500);
            return;
        }

        // Build merchant data string from POST (same pattern as kit)
        $merchant_data = '';
        foreach ($this->input->post() as $key => $value) {
            $merchant_data .= $key . '=' . $value . '&';
        }

        // Include Crypto.php from kit
        $crypto_path = APPPATH . 'libraries/Web Integration Non Seamless/PHP_NON_SEAMLESS_KIT/NON_SEAMLESS_KIT/Crypto.php';
        if (!file_exists($crypto_path)) {
            show_error('Crypto library missing', 500);
            return;
        }
        require_once $crypto_path;

        $encrypted_data = encrypt($merchant_data, $working_key);

        // Render auto-post form to CC Avenue
        $action_url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
        $html = '<html><body><form method="post" id="ccav_redirect" action="' . $action_url . '">'
              . '<input type="hidden" name="encRequest" value="' . htmlspecialchars($encrypted_data, ENT_QUOTES) . '">' 
              . '<input type="hidden" name="access_code" value="' . htmlspecialchars($access_code, ENT_QUOTES) . '">' 
              . '</form><script>document.getElementById("ccav_redirect").submit();</script></body></html>';
        $this->output->set_output($html);
    }

    // Callback endpoint to handle CC Avenue response
    public function callback() {
        $this->load->config('payment');
        $working_key = getenv('CCAVENUE_WORKING_KEY');
        if (empty($working_key)) {
            $working_key = $this->config->item('ccavenue_working_key');
        }
        if (empty($working_key)) {
            show_error('CCAvenue working key not configured', 500);
            return;
        }

        $encResponse = $this->input->post('encResp');
        if (empty($encResponse)) {
            show_error('Empty gateway response', 400);
            return;
        }

        // Include Crypto and decrypt
        $crypto_path = APPPATH . 'libraries/Web Integration Non Seamless/PHP_NON_SEAMLESS_KIT/NON_SEAMLESS_KIT/Crypto.php';
        require_once $crypto_path;
        $rcvdString = decrypt($encResponse, $working_key);

        // Parse key=value pairs
        $pairs = explode('&', $rcvdString);
        $data = [];
        foreach ($pairs as $p) {
            $kv = explode('=', $p, 2);
            $data[$kv[0]] = isset($kv[1]) ? $kv[1] : '';
        }

        $order_status = isset($data['order_status']) ? $data['order_status'] : '';
        $order_id = isset($data['order_id']) ? $data['order_id'] : '';
        $amount = isset($data['amount']) ? $data['amount'] : '';

        // Persist minimal audit trail (adjust table/fields per project schema)
        // Example table: miqaat_payment or a generic payments_log
        $log = [
            'gateway' => 'CCAvenue',
            'order_id' => $order_id,
            'amount' => $amount,
            'status' => $order_status,
            'raw' => $rcvdString,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $log_table = $this->config->item('payments_log_table') ?: 'payments_log';
        if ($this->db->table_exists($log_table)) {
            $this->db->insert($log_table, $log);
        }

        // Render a simple result view
        $message = '';
        if ($order_status === 'Success') {
            $message = 'Payment successful.';
        } elseif ($order_status === 'Aborted') {
            $message = 'Payment aborted.';
        } elseif ($order_status === 'Failure') {
            $message = 'Payment failed.';
        } else {
            $message = 'Unknown status.';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => ($order_status === 'Success'), 'status' => $order_status, 'order_id' => $order_id, 'amount' => $amount, 'data' => $data]));
    }
}
