<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp_bot_lib
{
  protected $CI;

  public function __construct()
  {
    $this->CI = &get_instance();
    $this->CI->config->load('whatsapp', true);
  }

  /**
   * Send a template notification via ExprezBot API.
   *
   * @param string $phone Digits-only (e.g. 919309950513)
   * @param string $templateName
   * @param array $bodyVars Template variables (order matters)
   * @param string|null $language
   * @return array {ok, http_code, response_raw, response_json, error}
   */
  public function send_template($phone, $templateName, $bodyVars = [], $language = null)
  {
    $url = (string)$this->CI->config->item('api_url', 'whatsapp');
    $token = (string)$this->CI->config->item('token', 'whatsapp');

    $phone = $this->normalize_phone($phone);
    $templateName = trim((string)$templateName);
    $language = $language !== null ? trim((string)$language) : (string)$this->CI->config->item('default_language', 'whatsapp');

    if ($url === '' || $token === '' || $phone === '' || $templateName === '') {
      return [
        'ok' => false,
        'http_code' => 0,
        'response_raw' => '',
        'response_json' => null,
        'error' => 'Missing configuration or required params (api_url/token/phone/template_name)'
      ];
    }

  $body = [];
  if (is_array($bodyVars)) {
    foreach (array_values($bodyVars) as $v) {
      if (is_array($v) && array_key_exists('text', $v)) {
        $body[] = ['text' => (string)$v['text']];
      } else {
        $body[] = ['text' => (string)$v];
      }
    }
  }

    $payload = [
      'token' => $token,
      'template_name' => $templateName,
      'template_language' => $language !== '' ? $language : 'en',
      'phone' => $phone,
    // ExprezBot expects an array of objects: [{"text": "..."}, ...]
    'body' => $body
    ];

    $json = json_encode($payload);
    if ($json === false) {
      return [
        'ok' => false,
        'http_code' => 0,
        'response_raw' => '',
        'response_json' => null,
        'error' => 'Failed to encode JSON payload'
      ];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $err = $errno ? curl_error($ch) : '';
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $jsonResp = null;
    if (is_string($raw) && $raw !== '') {
      $decoded = json_decode($raw, true);
      if (json_last_error() === JSON_ERROR_NONE) {
        $jsonResp = $decoded;
      }
    }

    $ok = ($errno === 0) && ($http >= 200 && $http < 300);

    return [
      'ok' => $ok,
      'http_code' => $http,
      'response_raw' => is_string($raw) ? $raw : '',
      'response_json' => $jsonResp,
      'error' => $err
    ];
  }

  /**
   * Normalize phone for API: digits only, preferring country code.
   * If 10 digits, assumes India and prefixes 91.
   */
  public function normalize_phone($phone)
  {
    $digits = preg_replace('/[^0-9]/', '', (string)$phone);
    $digits = $digits !== null ? $digits : '';

    // Common India case
    if (strlen($digits) === 10) {
      $digits = '91' . $digits;
    }

    return $digits;
  }
}
