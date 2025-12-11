<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CCAvenue Checkout</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h4 class="mb-3">CCAvenue Test Checkout</h4>
  <form method="post" action="<?php echo htmlspecialchars($action, ENT_QUOTES); ?>" class="card p-3">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Merchant ID</label>
        <input type="text" class="form-control" name="merchant_id" placeholder="Merchant ID" required>
      </div>
      <div class="form-group col-md-6">
        <label>Order ID</label>
        <input type="text" class="form-control" name="order_id" value="ORD<?php echo time(); ?>" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Amount</label>
        <input type="text" class="form-control" name="amount" value="1.00" required>
      </div>
      <div class="form-group col-md-4">
        <label>Currency</label>
        <input type="text" class="form-control" name="currency" value="INR" required>
      </div>
      <div class="form-group col-md-4">
        <label>Language</label>
        <input type="text" class="form-control" name="language" value="EN" required>
      </div>
    </div>

    <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($callback, ENT_QUOTES); ?>">
    <input type="hidden" name="cancel_url" value="<?php echo htmlspecialchars($callback, ENT_QUOTES); ?>">

    <h6 class="mt-3">Billing</h6>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>Name</label>
        <input type="text" class="form-control" name="billing_name" value="Test User">
      </div>
      <div class="form-group col-md-6">
        <label>Email</label>
        <input type="email" class="form-control" name="billing_email" value="test@example.com">
      </div>
    </div>
    <div class="form-group">
      <label>Address</label>
      <input type="text" class="form-control" name="billing_address" value="123 Street">
    </div>
    <div class="form-row">
      <div class="form-group col-md-3">
        <label>City</label>
        <input type="text" class="form-control" name="billing_city" value="Mumbai">
      </div>
      <div class="form-group col-md-3">
        <label>State</label>
        <input type="text" class="form-control" name="billing_state" value="MH">
      </div>
      <div class="form-group col-md-3">
        <label>Zip</label>
        <input type="text" class="form-control" name="billing_zip" value="400001">
      </div>
      <div class="form-group col-md-3">
        <label>Country</label>
        <input type="text" class="form-control" name="billing_country" value="India">
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Pay with CCAvenue</button>
  </form>
</div>
</body>
</html>
