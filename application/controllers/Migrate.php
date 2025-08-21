<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function index() {
        if (!is_cli()) {
            show_error('This can only be run from the CLI');
        }

        $this->load->library('migration');

        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrations applied successfully!" . PHP_EOL;
        }
    }
}
