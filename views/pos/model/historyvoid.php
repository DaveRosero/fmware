<?php
session_start();

require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

$user_id = $_SESSION['user_id'];
$mysqli = database();
$logs = new Logs();

// Check if POST variables are set
$post_keys = ['pos_ref'];
foreach ($post_keys as $key) {
    if (!isset($_POST[$key])) {
        die("Error: Missing POST variable $key");
    }
}

$pos_ref = $_POST['pos_ref'];
$enteredPin = $_POST['category'];


if (!preg_match('/^\d{4}$/', $enteredPin)) {
    die("Error: Invalid PIN format.");
}


$query = "SELECT value FROM company WHERE category = 'PIN'";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($storedPin);
    $stmt->fetch();
    $stmt->close();

    
    if ($enteredPin != $storedPin) {
        $json = array('invalid' => 'invalid');
        echo json_encode($json);
        return;
    }
} else {
    die("Error in preparing statement: " . $mysqli->error);
}



$query = "UPDATE pos SET status = 'void' WHERE pos_ref=?";

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('s', $pos_ref);

    if ($stmt->execute()) {
        $stmt->close();

        $action_log = 'Transaction ' . $pos_ref . ' has been Voided ';
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $user_id, $date_log);

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
                    $query = 'UPDATE stock SET qty = qty + ? WHERE product_id = ?';
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
$json = array('success' => 'success');
echo json_encode($json);
return;
?>