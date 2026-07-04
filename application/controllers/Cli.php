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

    public function verify_laagat_rent_grade()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }

        $this->load->model('LaagatRentM');

        // Let's find a user who has a residential grade in sabeel_takhmeen
        $takhmeen = $this->db->select('t.*, g.grade, g.year as grade_year')
            ->from('sabeel_takhmeen t')
            ->join('sabeel_takhmeen_grade g', 'g.id = t.residential_grade')
            ->where('t.residential_grade >', 0)
            ->limit(1)
            ->get()
            ->row_array();

        if (!$takhmeen) {
            echo "Error: No sabeel_takhmeen record with residential_grade found.\n";
            return;
        }

        $userId = (int)$takhmeen['user_id'];
        $hijriYear = $takhmeen['year'];
        $gradeId = (int)$takhmeen['residential_grade'];

        echo "Found test user: ID={$userId}, Year={$hijriYear}, Grade ID={$gradeId}, Grade Name={$takhmeen['grade']}\n";

        // Let's create a temporary Rent configuration
        $rentPayload = [
            'title' => 'Temp Rent Test',
            'hijri_year' => $hijriYear,
            'charge_type' => 'rent',
            'amount' => 500.00,
            'raza_type_id' => 1,
            'grade_amounts' => [
                $gradeId => 1000.00
            ],
            'grade_jamaat_amounts' => [
                $gradeId => 800.00
            ],
            'grade_sarkaar_amounts' => [
                $gradeId => 200.00
            ]
        ];

        echo "Creating Rent config with grade amounts...\n";
        $rentRes = $this->LaagatRentM->create($rentPayload);
        if (!$rentRes['success']) {
            echo "Failed to create Rent config: " . ($rentRes['error'] ?? '') . "\n";
            return;
        }
        $rentId = $rentRes['id'];
        echo "Rent config created with ID={$rentId}\n";

        // Check if grade amounts were actually saved in database
        $savedRentGrades = $this->db->where('laagat_rent_id', $rentId)->get('laagat_rent_grade_amounts')->result_array();
        echo "Saved grade amounts count for Rent config: " . count($savedRentGrades) . "\n";

        // Query amount and breakdown for Rent
        $rentAmt = $this->LaagatRentM->get_amount_for_user($rentId, $userId);
        $rentBreakdown = $this->LaagatRentM->get_amounts_breakdown_for_user($rentId, $userId);
        echo "Rent lookup amount: {$rentAmt} (Expected: 500.00)\n";
        echo "Rent lookup breakdown: " . json_encode($rentBreakdown) . "\n";

        // Let's create a temporary Laagat configuration
        $laagatPayload = [
            'title' => 'Temp Laagat Test',
            'hijri_year' => $hijriYear,
            'charge_type' => 'laagat',
            'amount' => 500.00,
            'raza_type_id' => 1,
            'grade_amounts' => [
                $gradeId => 1000.00
            ],
            'grade_jamaat_amounts' => [
                $gradeId => 800.00
            ],
            'grade_sarkaar_amounts' => [
                $gradeId => 200.00
            ]
        ];

        echo "\nCreating Laagat config with grade amounts...\n";
        $laagatRes = $this->LaagatRentM->create($laagatPayload);
        if (!$laagatRes['success']) {
            echo "Failed to create Laagat config: " . ($laagatRes['error'] ?? '') . "\n";
            // cleanup rent
            $this->db->delete('laagat_rent', ['id' => $rentId]);
            return;
        }
        $laagatId = $laagatRes['id'];
        echo "Laagat config created with ID={$laagatId}\n";

        // Check if grade amounts were actually saved in database
        $savedLaagatGrades = $this->db->where('laagat_rent_id', $laagatId)->get('laagat_rent_grade_amounts')->result_array();
        echo "Saved grade amounts count for Laagat config: " . count($savedLaagatGrades) . "\n";

        // Query amount and breakdown for Laagat
        $laagatAmt = $this->LaagatRentM->get_amount_for_user($laagatId, $userId);
        $laagatBreakdown = $this->LaagatRentM->get_amounts_breakdown_for_user($laagatId, $userId);
        echo "Laagat lookup amount: {$laagatAmt} (Expected: 1000.00)\n";
        echo "Laagat lookup breakdown: " . json_encode($laagatBreakdown) . "\n";

        // Cleanup
        echo "\nCleaning up test records...\n";
        $this->db->delete('laagat_rent', ['id' => $rentId]);
        $this->db->delete('laagat_rent_raza_type_map', ['laagat_rent_id' => $rentId]);
        $this->db->delete('laagat_rent', ['id' => $laagatId]);
        $this->db->delete('laagat_rent_raza_type_map', ['laagat_rent_id' => $laagatId]);
        $this->db->delete('laagat_rent_grade_amounts', ['laagat_rent_id' => $laagatId]);
        echo "Cleanup complete!\n";

        // Assertions for output verification in the test runner
        $passed = true;
        if (count($savedRentGrades) !== 0) {
            echo "ASSERTION FAILED: Saved grade amounts count for Rent is not 0!\n";
            $passed = false;
        }
        if ((float)$rentAmt !== 500.00) {
            echo "ASSERTION FAILED: Rent lookup amount is not 500.00!\n";
            $passed = false;
        }
        if ((float)$rentBreakdown['jamaat_amount'] !== 500.00 || (float)$rentBreakdown['sarkaar_amount'] !== 0.00 || (float)$rentBreakdown['amount'] !== 500.00) {
            echo "ASSERTION FAILED: Rent lookup breakdown is incorrect!\n";
            $passed = false;
        }
        if (count($savedLaagatGrades) !== 1) {
            echo "ASSERTION FAILED: Saved grade amounts count for Laagat is not 1!\n";
            $passed = false;
        }
        if ((float)$laagatAmt !== 1000.00) {
            echo "ASSERTION FAILED: Laagat lookup amount is not 1000.00!\n";
            $passed = false;
        }
        if ((float)$laagatBreakdown['jamaat_amount'] !== 800.00 || (float)$laagatBreakdown['sarkaar_amount'] !== 200.00 || (float)$laagatBreakdown['amount'] !== 1000.00) {
            echo "ASSERTION FAILED: Laagat lookup breakdown is incorrect!\n";
            $passed = false;
        }

        if ($passed) {
            echo "\nALL TESTS PASSED SUCCESSFULLY!\n";
        } else {
            echo "\nSOME TESTS FAILED!\n";
        }
    }

    public function verify_expense_item_flow()
    {
        if (!is_cli()) {
            echo "This command can only be run from the CLI.\n";
            return;
        }

        echo "Starting expense item flow verification...\n";

        $this->load->model('ExpenseM');
        $this->load->model('ExpenseSourceM');
        $this->load->model('ExpenseItemM');

        // 1. Create a test Source of Funds (SOF)
        $sourceName = "Test SOF " . uniqid();
        $sourceId = $this->ExpenseSourceM->create([
            'name' => $sourceName,
            'status' => 'Active'
        ]);
        echo "Created test source: ID={$sourceId}, Name='{$sourceName}'\n";

        // 2. Create a test Expense Item
        $sectorName = "Test Sector";
        $sectorCode = "SEC01";
        $subSectorName = "Test Sub Sector";
        $subSectorCode = "SUB01";
        $itemName = "Test Item " . uniqid();
        $itemCode = "IT01";

        $itemId = $this->ExpenseItemM->create([
            'sector_name' => $sectorName,
            'sector_code' => $sectorCode,
            'sub_sector_name' => $subSectorName,
            'sub_sector_code' => $subSectorCode,
            'item_name' => $itemName,
            'item_code' => $itemCode,
            'status' => 'Active'
        ]);
        echo "Created test item: ID={$itemId}, Name='{$itemName}'\n";

        // 3. Create a test Expense
        $expenseDate = date('Y-m-d');
        $amount = 1250.75;
        $hijriYear = 1445;
        $notes = "CLI test expense notes";

        $expenseId = $this->ExpenseM->create([
            'expense_date' => $expenseDate,
            'item_id' => $itemId,
            'amount' => $amount,
            'source_id' => $sourceId,
            'hijri_year' => $hijriYear,
            'notes' => $notes
        ]);
        echo "Created test expense: ID={$expenseId}\n";

        // 4. Fetch list and verify
        $list = $this->ExpenseM->get_list(['item' => $itemName]);
        $found = null;
        foreach ($list as $row) {
            if ((int)$row['id'] === (int)$expenseId) {
                $found = $row;
                break;
            }
        }

        $passed = true;
        if (!$found) {
            echo "ASSERTION FAILED: Created expense not found in list filtered by item name!\n";
            $passed = false;
        } else {
            echo "Successfully retrieved created expense from list.\n";
            if ($found['sector_name'] !== $sectorName) {
                echo "ASSERTION FAILED: sector_name mismatch: expected '{$sectorName}', got '{$found['sector_name']}'\n";
                $passed = false;
            }
            if ($found['sub_sector_name'] !== $subSectorName) {
                echo "ASSERTION FAILED: sub_sector_name mismatch: expected '{$subSectorName}', got '{$found['sub_sector_name']}'\n";
                $passed = false;
            }
            if ($found['item_name'] !== $itemName) {
                echo "ASSERTION FAILED: item_name mismatch: expected '{$itemName}', got '{$found['item_name']}'\n";
                $passed = false;
            }
            if ((float)$found['amount'] !== $amount) {
                echo "ASSERTION FAILED: amount mismatch: expected {$amount}, got {$found['amount']}\n";
                $passed = false;
            }
            if ($found['source_name'] !== $sourceName) {
                echo "ASSERTION FAILED: source_name mismatch: expected '{$sourceName}', got '{$found['source_name']}'\n";
                $passed = false;
            }
        }

        // 5. Update and verify
        $newAmount = 2500.00;
        $updateOk = $this->ExpenseM->update($expenseId, ['amount' => $newAmount]);
        if (!$updateOk) {
            echo "ASSERTION FAILED: Expense update failed!\n";
            $passed = false;
        } else {
            $updatedExpense = $this->ExpenseM->get($expenseId);
            if ((float)$updatedExpense['amount'] !== $newAmount) {
                echo "ASSERTION FAILED: Updated amount mismatch: expected {$newAmount}, got {$updatedExpense['amount']}\n";
                $passed = false;
            } else {
                echo "Successfully updated expense amount.\n";
            }
        }

        // 6. Cleanup
        echo "Cleaning up test records...\n";
        $this->ExpenseM->delete($expenseId);
        $this->ExpenseItemM->delete_with_expenses($itemId);
        $this->ExpenseSourceM->delete_with_expenses($sourceId);
        echo "Cleanup complete!\n";

        if ($passed) {
            echo "\nEXPENSE ITEM FLOW VERIFICATION PASSED SUCCESSFULLY!\n";
        } else {
            echo "\nEXPENSE ITEM FLOW VERIFICATION FAILED!\n";
        }
    }
}


