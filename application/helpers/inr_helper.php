<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('format_inr')) {
  /**
   * Format a number using Indian digit grouping (INR) with optional decimals.
   * Example: 1234567.89 => 12,34,567.89
   *
   * @param float|int|string $number
   * @param int $decimals
   * @return string
   */
  function format_inr($number, $decimals = 2) {
    $number = round((float)$number, $decimals);
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $parts = explode('.', number_format($number, $decimals, '.', ''));
    $int = $parts[0];
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '' ) {
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',';
    } else {
      $rest = '';
    }
    $formatted = $sign . $rest . $last3;
    if ($decimals > 0) {
      $formatted .= '.' . str_pad($parts[1] ?? '0', $decimals, '0');
    }
    return $formatted;
  }
}

if (!function_exists('format_inr_no_decimals')) {
  /**
   * Format a number using Indian digit grouping (no decimals).
   * Example: 1234567.89 => 12,34,568
   * Rounds to nearest rupee.
   *
   * @param float|int|string $number
   * @return string
   */
  function format_inr_no_decimals($number) {
    $number = round((float)$number); // nearest rupee
    $sign = '';
    if ($number < 0) { $sign = '-'; $number = abs($number); }
    $int = (string)$number;
    $last3 = substr($int, -3);
    $rest = substr($int, 0, -3);
    if ($rest !== false && $rest !== '') {
      $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',';
    } else {
      $rest = '';
    }
    return $sign . $rest . $last3;
  }
}
