<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }
  public function send_whatsapp_message()
  {

    $apiUrl = "https://multiwhatsapp.admarksolution.com/api/send-message";
    $apiKey = "3e03ac84-c20a-482e-8884-86378662b2fd";

    // Query parameters

    $params = [
      "number" => "919309950513",
      "messageBody" => "*Greetings from Patel Infotech Services!*
We’re delighted to connect with you and look forward to helping you grow with our innovative web solutions.
      
*Explore our services:* https://patelinfotech.online/services",
    ];
    $urlWithParams = $apiUrl . '?' . http_build_query($params);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlWithParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "api-key: $apiKey"
    ]);

    // Response
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      echo "cURL Error: " . curl_error($ch);
    } else {
      $data = json_decode($response, true);
      echo "<pre>" . print_r($data, true) . "</pre>";
    }
    curl_close($ch);
  }
}
