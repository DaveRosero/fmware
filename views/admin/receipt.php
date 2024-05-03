<?php
  include_once 'session.php';
  require_once 'model/admin/admin.php';
  require_once 'model/admin/orderClass.php';

  $admin = new Admin();
  $order = new Order();

  $admin->isAdmin();
  $receipt = $order->printReceipt($order_ref);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                width: 58mm;
                margin: 0; /* Remove default margin */
                padding: 0; /* Remove default padding */
            }
            .receipt {
                padding: 10px;
                border: none; /* Remove border */
                box-shadow: none; /* Remove box shadow */
                background-color: #fff;
            }
            .header, .footer {
                text-align: center;
                font-weight: bold;
                font-size: 12px;
                margin-bottom: 10px;
            }
            .info {
                margin-bottom: 5px;
                font-weight: bold;
            }
            .items {
                margin-top: 10px;
                font-weight: bold;
            }
            .item {
                margin-bottom: 5px;
                font-weight: bold;
            }
            /* Prevent page breaks within receipt content */
            .receipt, .receipt * {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="receipt">
                <!-- Header with Company Info -->
                <div class="header">
                    <p>F.M. Odulio's Enterprise & Gen. Merchandise</p>
                    <p>McArthur Hwy, Poblacion II, Marilao, Bulacan</p>
                    <p>Phone: (0906) 471 9681</p>
                </div>
                <!-- Order Details -->
                <div class="info">Order Date:  <?php echo $receipt['date']; ?></div>
                <div class="info">Order ID: <?php echo $receipt['order_ref']; ?></div>
                <div class="info">Customer: <?php echo $receipt['customer']; ?></div>
                
                <div class="items">
                    <?php $order->getOrderItems($order_ref); ?>
                </div>
                <!-- Footer with Receipt Expiration Date -->
                <div class="footer">
                    <p>Thank you for your purchase !</p>
                    <p>This will serve as an official receipt valid until <?php echo date('F j, Y', strtotime('+7 days')); ?></p>
                    <p>Visit us at fmware.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once 'vendor/jQuery/bundle.php';
?>
<!-- Bootstrap JS (Optional for dropdowns, modals, etc.) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript to trigger print -->
<script>
    // JavaScript function to trigger print
        window.print();
    
    // Optionally trigger print automatically when page loads (for testing)
    // window.onload = function() {
    //     printReceipt();
    // };
</script>

</body>
</html>
