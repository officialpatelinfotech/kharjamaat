<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }
  /**
   * CLI-only quick test for ExprezBot template sending.
   * Usage:
    *   php index.php whatsapp send_test_template 919309950513 rsvp_open_member en "[\"Name\",\"Miqaat\",\"Miqaat ID\",\"DD-MM-YYYY\",\"miqaat_numeric_id_for_button\"]"
   */
  public function send_test_template($phone = '', $template = '', $lang = 'en', $bodyJson = '[]')
  {
    if (!is_cli()) {
      echo "This endpoint may only be run from CLI." . PHP_EOL;
      return;
    }

    $this->load->library('Whatsapp_bot_lib');

    // Accept either raw JSON or URL-safe base64-encoded JSON to avoid CI URI filtering issues.
    $raw = (string)$bodyJson;
    $vars = [];
    // Heuristic: if looks like base64 (alphanumeric plus -_ and optional =), attempt decode
    if (preg_match('#^[A-Za-z0-9-_]+={0,2}$#', $raw)) {
      // Convert URL-safe base64 to standard base64
      $b64 = strtr($raw, '-_', '+/');
      // Add padding if missing
      $mod = strlen($b64) % 4;
      if ($mod > 0) $b64 .= str_repeat('=', 4 - $mod);
      $decoded = @base64_decode($b64, true);
      if ($decoded !== false) {
        $decodedArr = json_decode($decoded, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedArr)) {
          $vars = $decodedArr;
        }
      }
    }
    // Fallback: raw JSON string
    if (empty($vars)) {
      $try = json_decode($raw, true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
        $vars = $try;
      }
    }

    $result = $this->whatsapp_bot_lib->send_template($phone, $template, $vars, $lang);
    echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
  }
}
