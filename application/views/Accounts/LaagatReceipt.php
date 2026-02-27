<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - <?= htmlspecialchars($payment['title']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body { background: #f5f7fb; padding-top: 50px; }
        .receipt-card { max-width: 600px; margin: 0 auto; border-radius: 15px; border: none; }
        .receipt-header { background: #fff; border-bottom: 2px dashed #eee; padding: 30px; border-radius: 15px 15px 0 0; text-align: center; }
        .receipt-body { padding: 30px; }
        .receipt-footer { padding: 20px; text-align: center; color: #888; font-size: 0.9rem; }
        .kv { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .k { color: #888; }
        .v { font-weight: 600; text-align: right; }
        .amount-big { font-size: 2rem; font-weight: 700; color: #28a745; margin-top: 10px; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; background: #e8f5e9; color: #2e7d32; font-weight: 600; font-size: 0.8rem; margin-bottom: 20px; }
        @media print {
            body { padding-top: 0; background: #fff; }
            .receipt-card { box-shadow: none; margin: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container mb-5 mt-5">
    <div class="row no-print mb-4 justify-content-center">
        <div class="col-md-6 d-flex justify-content-between">
            <?php 
                $back_link = base_url('accounts/laagatrent');
                if (isset($_SESSION['user']['role']) && ($_SESSION['user']['role'] == 3 || $_SESSION['user']['role'] == 2)) {
                    $back_link = base_url('anjuman/laagat_rent_payments');
                }
            ?>
            <a href="<?= $back_link ?>" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-print mr-2"></i> Print Receipt
            </button>
        </div>
    </div>

    <div class="card receipt-card shadow-sm">
        <div class="receipt-header">
            <div class="status-badge">PAYMENT SUCCESSFUL</div>
            <h4 class="mb-0">Payment Receipt</h4>
            <div class="amount-big">₹<?= format_inr($payment['amount'], 0) ?></div>
        </div>
        <div class="receipt-body">
            <div class="kv">
                <span class="k">Receipt No</span>
                <span class="v">REC-LR-<?= str_pad($payment['id'], 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="kv">
                <span class="k">Date</span>
                <span class="v"><?= date('d-m-Y', strtotime($payment['payment_date'])) ?></span>
            </div>
            <hr>
            <div class="kv">
                <span class="k">Account Name</span>
                <span class="v"><?= htmlspecialchars($payment['title']) ?></span>
            </div>
            <div class="kv">
                <span class="k">Raza Id</span>
                <span class="v">R#<?= htmlspecialchars($payment['generated_raza_id'] ?: 'N/A') ?></span>
            </div>
            <div class="kv">
                <span class="k">Charge Type</span>
                <span class="v text-capitalize"><?= htmlspecialchars($payment['charge_type']) ?></span>
            </div>
            <div class="kv">
                <span class="k">Hijri Year</span>
                <span class="v"><?= htmlspecialchars($payment['hijri_year']) ?>H</span>
            </div>
            <hr>
            <div class="kv">
                <span class="k">Member Name</span>
                <span class="v"><?= htmlspecialchars($payment['Full_Name']) ?></span>
            </div>
            <div class="kv">
                <span class="k">ITS ID</span>
                <span class="v"><?= htmlspecialchars($payment['ITS_ID']) ?></span>
            </div>
            <hr>
            <div class="kv">
                <span class="k">Payment Method</span>
                <span class="v badge badge-light"><?= htmlspecialchars($payment['payment_method'] ?: 'Cash') ?></span>
            </div>
            <?php if (!empty($payment['reference_no'])): ?>
            <div class="kv">
                <span class="k">Reference No</span>
                <span class="v"><?= htmlspecialchars($payment['reference_no']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="receipt-footer">
            <p class="mb-0">Thank you for your payment.</p>
            <p class="small mt-1">Computer generated receipt.</p>
        </div>
    </div>
</div>

</body>
</html>
