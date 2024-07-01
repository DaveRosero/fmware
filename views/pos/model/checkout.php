<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if POST variables are set
$post_keys = ['user_id', 'pos_ref', 'delivery-fee-value', 'fname', 'lname', 'contact', 'subtotal', 'total', 'discount', 'cash', 'changes', 'deliverer_name', 'payment_type', 'address'];
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
if ($_POST['transaction_type']){
    $transaction_type_id = 3;
} else{
    $transaction_type_id = 2;
}

if ($_POST['payment_type']){
    $payment_type_id = 2;
    $status = 'paid';
} else {
    $payment_type_id = 3;
    $status = 'paid';
}
$payment_type_id = intval($_POST['payment_type']);
$address = $_POST['address'];
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
    $stmt->bind_param('sssddddddsssiisi', $pos_ref, $fname, $lname, $subtotal, $total, $discount, $cash, $change, $delivery_fee,
                      $contact, $address, $deliverer_name, $transaction_type_id, $payment_type_id, $status, $user_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Insert items into pos_items table
        $query = 'INSERT INTO pos_items (pos_ref, product_id, qty)
                  SELECT ?, product_id, qty
                  FROM pos_cart
                  WHERE user_id = ?';
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('si', $pos_ref, $user_id);
            if ($stmt->execute()) {
                $stmt->close();

                // Select items from order_items table
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

                        // Update stock in stock table
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
