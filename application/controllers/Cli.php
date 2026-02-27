<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Run from CLI: php index.php cli migrate_expense_sources
    public function migrate_expense_sources()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }

        $path = APPPATH . 'sql/create_expense_tables.sql';
        if (!file_exists($path)) {
            echo "SQL file not found: $path\n";
            return;
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            echo "Failed to read SQL file.\n";
            return;
        }

        // Simple split by semicolon; supports our small set of CREATE TABLE statements
        $parts = array_filter(array_map('trim', explode(';', $sql)));
        $count = 0;
        foreach ($parts as $part) {
            if ($part === '') continue;

            // Remove full-line SQL comments (e.g. "-- comment") that may precede statements
            $lines = preg_split("/\r\n|\n|\r/", $part);
            $lines = array_filter($lines, function ($line) {
                $t = ltrim($line);
                return $t !== '' && strpos($t, '--') !== 0;
            });
            $stmt = trim(implode("\n", $lines));
            if ($stmt === '') continue;
            try {
                $this->db->query($stmt);
                $count++;
            } catch (Exception $e) {
                echo "Error executing statement: " . $e->getMessage() . "\n";
            }
        }

        echo "Executed $count SQL statement(s). Migration finished.\n";
    }

    // CLI helper to print expense_sources count
    public function expense_count()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }
        $this->load->model('ExpenseSourceM');
        $rows = $this->ExpenseSourceM->get_all();
        $count = is_array($rows) ? count($rows) : 0;
        echo "expense_sources count: $count\n";
    }

    // CLI helper to insert one expense source and print returned id
    public function expense_insert_test()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }
        $name = 'cli_test_' . date('Ymd_His');
        $status = 'Active';
        $this->load->model('ExpenseSourceM');
        $id = $this->ExpenseSourceM->create(['name' => $name, 'status' => $status]);
        $err = $this->db->error();
        echo "create() returned: " . var_export($id, true) . "\n";
        echo "db_error: " . json_encode($err) . "\n";
        echo "last_query: " . $this->db->last_query() . "\n";
    }

    // Run from CLI: php index.php cli db_check
    // Prints actual connected DB/host/port and checks key table existence.
    public function db_check()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }

        $env = defined('ENVIRONMENT') ? ENVIRONMENT : 'unknown';
        echo "ENVIRONMENT: {$env}\n";
        echo "DB_HOST (env): " . (string)getenv('DB_HOST') . "\n";
        echo "DB_PORT (env): " . (string)getenv('DB_PORT') . "\n";
        echo "DB_NAME (env): " . (string)getenv('DB_NAME') . "\n";
        echo "DB_USER (env): " . (string)getenv('DB_USER') . "\n";

        $info = $this->db->query("SELECT DATABASE() AS db, @@hostname AS host, @@port AS port")->row_array();
        if (is_array($info)) {
            echo "Connected DB: " . ($info['db'] ?? '') . "\n";
            echo "MySQL host:   " . ($info['host'] ?? '') . "\n";
            echo "MySQL port:   " . ($info['port'] ?? '') . "\n";
        }

        $table = 'fmb_per_day_thaali_cost';
        $exists = $this->db->table_exists($table);
        echo "Table '{$table}' exists: " . ($exists ? 'YES' : 'NO') . "\n";
        if (!$exists) {
            $err = $this->db->error();
            echo "db_error: " . json_encode($err) . "\n";
        }
    }

        // Run from CLI: php index.php cli migrations_status
        public function migrations_status()
        {
            if (!is_cli()) {
                echo "This command can only be run from the CLI.\n";
                return;
            }

            $env = defined('ENVIRONMENT') ? ENVIRONMENT : 'unknown';
            echo "ENVIRONMENT: {$env}\n";
            $info = $this->db->query("SELECT DATABASE() AS db, @@hostname AS host, @@port AS port")->row_array();
            if (is_array($info)) {
                echo "Connected DB: " . ($info['db'] ?? '') . "\n";
                echo "MySQL port:   " . ($info['port'] ?? '') . "\n";
            }

            if (!$this->db->table_exists('migrations')) {
                echo "Table 'migrations' exists: NO\n";
                return;
            }

            $row = $this->db->query("SELECT version FROM migrations LIMIT 1")->row_array();
            $version = is_array($row) ? ($row['version'] ?? null) : null;
            echo "migrations.version: " . var_export($version, true) . "\n";

            $table = 'fmb_per_day_thaali_cost';
            echo "Table '{$table}' exists: " . ($this->db->table_exists($table) ? 'YES' : 'NO') . "\n";
        }

        // Run from CLI: php index.php cli migrations_set_version 32
        public function migrations_set_version($version = null)
        {
            if (!is_cli()) {
                echo "This command can only be run from the CLI.\n";
                return;
            }

            if ($version === null || !is_numeric($version)) {
                echo "Usage: php index.php cli migrations_set_version <number>\n";
                return;
            }
            $version = (int)$version;

            if (!$this->db->table_exists('migrations')) {
                echo "Table 'migrations' does not exist; cannot set version.\n";
                return;
            }

            $this->db->query("UPDATE migrations SET version = ?", array($version));
            $err = $this->db->error();
            if (!empty($err['code'])) {
                echo "db_error: " . json_encode($err) . "\n";
                return;
            }
            echo "Updated migrations.version to {$version}\n";
        }
}
