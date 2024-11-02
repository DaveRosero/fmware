<?php
include_once 'session.php';
require_once 'model/admin/logsClass.php';


$mysqli = database();


$logs = new Logs();

// Check if POST variables are set
$post_keys = ['user_id', 'pos_ref', 'delivery-fee-value', 'fname', 'lname', 'contact', 'subtotal', 'total', 'discount', 'cash', 'changes', 'payment_type', 'address'];
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
$date = date('F j, Y g:i A');

if ($_POST['transaction_type']) {
    $transaction_type_id = 3;
    $status = 'pending';
    // Determine the payment status based on payment_type_id
    if ($_POST['payment_type'] == '1') {
        $paid = 'unpaid'; // If payment_type_id is 1 and transaction is pending, mark as unpaid
    } else {
        $paid = 'paid';   // For other payment types, mark as paid
    }
} else {
    $transaction_type_id = 2;
    $status = 'paid';
    $paid = 'paid';
}

if ($_POST['payment_type'] === '1') {
    $payment_type_id = 1;
} elseif ($_POST['payment_type'] === '2') {
    $payment_type_id = 2;
} else {
    $payment_type_id = 3;
}
$payment_type_id = intval($_POST['payment_type']);
$address = $_POST['address'];
if (isset($_POST['delivery-fee-value'])) {
    $delivery_fee_value = floatval($_POST['delivery-fee-value']);
} else {
    $delivery_fee_value = null;
}

$query = 'INSERT INTO pos
                    (pos_ref, firstname, lastname, date, subtotal, total, discount, cash, changes, delivery_fee,
                    contact_no, address, transaction_type_id, payment_type_id, status, user_id, paid)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param(
        'ssssddddddssiisis',
        $pos_ref, //string
        $fname,   //string
        $lname,   //string
        $date,    //string
        $subtotal, //double
        $total,    //double
        $discount, //double
        $cash,     //double
        $change,   //double
        $delivery_fee, //double
        $contact,  //string
        $address,  //string
        $transaction_type_id, //int
        $payment_type_id,  //int
        $status,  //string
        $user_id, //int
        $paid,    //string
    );

    if ($stmt->execute()) {
        $stmt->close();

        $action_log = 'Transaction ' . $pos_ref . ' Done ';
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $user_id, $date_log);

        $query = 'INSERT INTO pos_items (pos_ref, product_id, qty, total)
                  SELECT ?, product_id, qty, qty * price
                  FROM pos_cart
                  WHERE user_id = ?';
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('si', $pos_ref, $user_id);
            if ($stmt->execute()) {
                $stmt->close();


                $query = 'SELECT product_id, qty FROM pos_items WHERE pos_ref = ?';
                $stmt = $mysqli->prepare($query);
                if ($stmt) {
                    $stmt->bind_param('s', $pos_ref);
                    if ($stmt->execute()) {
                        $stmt->bind_result($product_id, $qty);
                        $products = array();
                        while ($stmt->fetch()) {
                            $products[] = array(
                                'id' => $product_id,
                                'qty' => $qty
                            );
                        }
                        $stmt->close();

                        // Update stock 
                        foreach ($products as $product) {
                            $query = 'UPDATE stock SET qty = qty - ? WHERE product_id = ?';
                            $stmt = $mysqli->prepare($query);
                            if ($stmt) {
                                $stmt->bind_param('ii', $product['qty'], $product['id']);
                                if ($stmt->execute()) {
                                    $stmt->close();
                                } else {
                                    die("Error in executing statement: " . $stmt->error);
                                }
                            } else {
                                die("Error in preparing statement: " . $mysqli->error);
                            }
                        }

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
    } else {
        die("Error in executing statement: " . $stmt->error);
    }
} else {
    die("Error in preparing statement: " . $mysqli->error);
}
?>
