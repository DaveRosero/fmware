<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'INSERT INTO pos
                    (pos_ref, firsname, lastname, subtotal, total, discount, cash, changes, delivery_fee,
                    contact_no, address, deliverer_name, transaction_type_id, payment_type_id, status, user_id)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

$user_id = $_POST['user_id'];
$pos_ref = $_POST['pos_ref'];
$delivery_fee = intval($_POST['delivery-fee-value']);
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$contact = $_POST['contact'];
$subtotal = $_POST['subtotal']; 
$total = $_POST['total'];
$discount = $_POST['discount'];
$cash = $_POST['cash'];
$change = $_POST['changes'];
$deliverername = $_POST['deliever_name'];
$transaction_type_id = 1;
$payment_type_id = $_POST['payment_type'];
$address = $_POST['address_id'];
if ($payment_type_id == 2) {
    $status = 'paid';
}
$stmt = $this->mysqli->prepare($query);
$stmt->bind_param('sssddddddissiisi', $order_ref, $fname, $lname, $subtotal, $total, $discount, $cash, $change, $delivery_fee,
                  $contact, $address, $deliverername, $transaction_type_id, $payment_type_id, $status, $user_id);
if ($stmt) {
    if ($stmt->execute()) {
        $stmt->close();
        $this->orderItems($order_ref, $user_id);
    } else {
        die("Error in executing statement: " . $stmt->error);
        $stmt->close();
    }
} else {
    die("Error in preparing statement: " . $this->mysqli->error);
}

$query = 'INSERT INTO order_items (order_ref, product_id, qty)
        SELECT ?, product_id, qty
        FROM cart
        WHERE user_id = ?
        AND active = 1';
$stmt = $this->mysqli->prepare($query);
$stmt->bind_param('si', $order_ref, $user_id);
if ($stmt) {
    if ($stmt->execute()) {
        $stmt->close();
    } else {
        die("Error in executing statement: " . $stmt->error);
        $stmt->close();
    }
} else {
    die("Error in preparing statement: " . $this->mysqli->error);
}

