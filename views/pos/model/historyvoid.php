<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if POST variables are set
$post_keys = ['pos_ref'];
foreach ($post_keys as $key) {
    if (!isset($_POST[$key])) {
        die("Error: Missing POST variable $key");
    }
}

$pos_ref = $_POST['pos_ref'];

$query = "UPDATE pos SET status = 'Void' WHERE pos_ref=?";

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('s', $pos_ref);

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