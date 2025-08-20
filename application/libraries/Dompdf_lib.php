<?php

use Dompdf\Dompdf;

class Dompdf_lib
{
  public function load()
  {
    require_once APPPATH . '../vendor/autoload.php';
    return new Dompdf();
  }
}
