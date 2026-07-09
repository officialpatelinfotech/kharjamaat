<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$is_laagat = isset($module_type) && $module_type === 'laagat';
$is_rent = isset($module_type) && $module_type === 'rent';
$title = $is_laagat ? 'Laagat Module' : ($is_rent ? 'Rent Module' : 'Laagat & Rent Module');
$back_url = base_url("anjuman");
$invoices_url = base_url("anjuman/laagat_rent_invoices" . ($is_laagat ? "?charge_type=laagat" : ($is_rent ? "?charge_type=rent" : "")));
$payments_url = base_url("anjuman/laagat_rent_payments" . ($is_laagat ? "?charge_type=laagat" : ($is_rent ? "?charge_type=rent" : "")));
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<style>
  .module-dashboard {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1a1610;
    max-width: 900px;
    margin: 0 auto;
  }
  .page-header {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    margin-bottom: 40px;
  }
  .btn-back {
    position: absolute;
    left: 0;
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1.5px solid #e8e0cc;
    background: #ffffff;
    color: #5a5244;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all .2s;
  }
  .btn-back:hover {
    background: #f5e9c0;
    border-color: #b8860b;
    color: #b8860b;
    text-decoration: none;
    transform: translateX(-3px);
  }
  .page-title {
    font-family: 'Literata', Georgia, serif;
    color: #b8860b;
    font-size: 2rem;
    font-weight: 600;
    margin: 0;
    letter-spacing: -.5px;
  }
  
  .card-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
  }
  @media (max-width: 768px) {
    .card-container {
      grid-template-columns: 1fr;
      gap: 20px;
    }
  }

  .premium-card {
    background: #ffffff;
    border: 1.5px solid #e8e0cc;
    border-radius: 20px;
    padding: 40px 24px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.05);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }
  .premium-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    transition: all 0.3s;
  }
  .premium-card.blue-theme::before {
    background: linear-gradient(90deg, #1d4ed8, #60a5fa);
  }
  .premium-card.green-theme::before {
    background: linear-gradient(90deg, #10b981, #34d399);
  }

  .premium-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 36px rgba(184, 134, 11, 0.14);
    border-color: rgba(184, 134, 11, 0.35);
  }

  .icon-wrapper {
    width: 96px;
    height: 96px;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    transition: all 0.3s ease;
  }
  .premium-card:hover .icon-wrapper {
    transform: scale(1.1) rotate(4deg);
  }
  .blue-theme .icon-wrapper {
    background: #eff6ff;
    color: #1d4ed8;
  }
  .green-theme .icon-wrapper {
    background: #ecfdf5;
    color: #10b981;
  }

  .card-h {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a1610;
    margin-bottom: 12px;
    letter-spacing: -.3px;
  }
  .card-d {
    font-size: 0.9rem;
    color: #5a5244;
    line-height: 1.6;
    margin-bottom: 32px;
    flex-grow: 1;
  }

  .btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
  }
  .blue-theme .btn-action {
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
  }
  .blue-theme .btn-action:hover {
    background: linear-gradient(135deg, #1e40af, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(29, 78, 216, 0.25);
    color: #ffffff;
    text-decoration: none;
  }
  .green-theme .btn-action {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
  }
  .green-theme .btn-action:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25);
    color: #ffffff;
    text-decoration: none;
  }
</style>

<div class="container margintopcontainer pt-5 pb-5">
  <div class="module-dashboard">
    
    <!-- Header -->
    <div class="page-header">
      <a href="<?php echo $back_url; ?>" class="btn-back" title="Back"><i class="fa-solid fa-arrow-left"></i></a>
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <!-- Cards Grid -->
    <div class="card-container">
      
      <!-- Card 1: Invoices -->
      <div class="premium-card blue-theme">
        <div class="icon-wrapper">
          <i class="fa-solid fa-file-invoice fa-3x"></i>
        </div>
        <div>
          <h2 class="card-h">Update <?php echo $is_laagat ? 'Laagat' : ($is_rent ? 'Rent' : 'Laagat & Rent'); ?> Invoices</h2>
          <p class="card-d">Manage all generated invoices, update individual amounts, and oversee general configurations.</p>
        </div>
        <a href="<?php echo $invoices_url; ?>" class="btn-action">
          <i class="fa-solid fa-list-check"></i> Update Invoices
        </a>
      </div>

      <!-- Card 2: Payments -->
      <div class="premium-card green-theme">
        <div class="icon-wrapper">
          <i class="fa-solid fa-money-bill-transfer fa-3x"></i>
        </div>
        <div>
          <h2 class="card-h">Receive <?php echo $is_laagat ? 'Laagat' : ($is_rent ? 'Rent' : 'Laagat & Rent'); ?> Payment</h2>
          <p class="card-d">Record new payments received against invoices, inspect receipts, and browse history.</p>
        </div>
        <a href="<?php echo $payments_url; ?>" class="btn-action">
          <i class="fa-solid fa-cash-register"></i> Receive Payment
        </a>
      </div>

    </div>

  </div>
</div>
