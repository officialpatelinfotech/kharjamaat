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
}
