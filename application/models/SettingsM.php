<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class SettingsM extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  private function ensure_table()
  {
    // This repo currently pins CI migrations to an older version.
    // To keep admin preferences working without forcing a full migration run,
    // create the settings table lazily if it doesn't exist.
    if ($this->db->table_exists('app_settings')) return;

    $this->db->query(
      "CREATE TABLE IF NOT EXISTS `app_settings` (
        `key` VARCHAR(64) NOT NULL,
        `value` TEXT NULL,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`key`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    );
    $this->db->query(
      "INSERT IGNORE INTO `app_settings` (`key`, `value`) VALUES ('jamaat_name', 'Khar Jamaat')"
    );

    // Optional footer/contact defaults
    $this->db->query(
      "INSERT IGNORE INTO `app_settings` (`key`, `value`) VALUES
        ('address_line', ''),
        ('city_state', ''),
        ('pincode', ''),
        ('support_email', '')"
    );
  }

  public function get($key, $default = null)
  {
    $this->ensure_table();
    $key = trim((string)$key);
    if ($key === '') return $default;

    $row = $this->db
      ->select('value')
      ->from('app_settings')
      ->where('`key`', $key)
      ->limit(1)
      ->get()
      ->row_array();

    if (!$row) return $default;
    return $row['value'];
  }

  public function set($key, $value)
  {
    $this->ensure_table();
    $key = trim((string)$key);
    if ($key === '') return false;

    $data = [
      'key' => $key,
      'value' => is_string($value) ? $value : json_encode($value)
    ];

    // Upsert
    $exists = $this->db
      ->select('`key`')
      ->from('app_settings')
      ->where('`key`', $key)
      ->limit(1)
      ->get()
      ->row_array();

    if ($exists) {
      return $this->db->where('`key`', $key)->update('app_settings', ['value' => $data['value']]);
    }
    return $this->db->insert('app_settings', $data);
  }
}
