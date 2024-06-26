<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if POST variables are set
$post_keys = ['user_id', 'pos_ref', 'delivery-fee-value', 'fname', 'lname', 'contact', 'subtotal', 'total', 'discount', 'cash', 'changes', 'deliverer', 'payment_type', 'address'];
foreach ($post_keys as $key) {
    if (!isset($_POST[$key])) {
        die("Error: Missing POST variable $key");
    }
}

$user_id = $_POST['user_id'];
$pos_ref = $_POST['pos_ref'];
$delivery_fee = floatval($_POST['delivery-fee-value']);
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$contact = $_POST['contact'];
$subtotal = floatval($_POST['subtotal']);
$total = floatval($_POST['total']);
$discount = floatval($_POST['discount']);
$cash = floatval($_POST['cash']);
$change = floatval($_POST['changes']);
$deliverer_name = $_POST['deliverer_name'];
$transaction_type_id = 1;
$payment_type_id = intval($_POST['payment_type']);
$address = $_POST['address'];
$status = ($payment_type_id == 2) ? 'paid' : 'unpaid';
if (isset($_POST['delivery-fee-value'])) {
    $delivery_fee_value = floatval($_POST['delivery-fee-value']); 
} else {
    $delivery_fee_value = null; 
}

$query = 'INSERT INTO pos
                    (pos_ref, firstname, lastname, subtotal, total, discount, cash, changes, delivery_fee,
                    contact_no, address, deliverer_name, transaction_type_id, payment_type_id, status, user_id)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('sssddddddissiisi', $pos_ref, $fname, $lname, $subtotal, $total, $discount, $cash, $change, $delivery_fee,
                      $contact, $address, $deliverer_name, $transaction_type_id, $payment_type_id, $status, $user_id);

    if ($stmt->execute()) {
        $stmt->close();

        $query = 'INSERT INTO order_items (order_ref, product_id, qty)
                  SELECT ?, product_id, qty
                  FROM cart
                  WHERE user_id = ?
                  AND active = 1';
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('si', $pos_ref, $user_id);

            if ($stmt->execute()) {
                $stmt->close();
                echo "Transaction successful.";
            } else {
                die("Error in executing statement: " . $stmt->error);
            }
        } else {
            die("Error in preparing statement: " . $mysqli->error);
        }
    } else {
        die("Error in executing statement: " . $stmt->error);
    }
} else {
    die("Error in preparing statement: " . $mysqli->error);
}
?>
