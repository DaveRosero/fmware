<?php
    require_once 'model/home/home.php';
    require_once 'model/home/cartClass.php';

    class Checkout extends Home {
        private $cart;
        public function newCart () {
            $this->cart = new Cart();
            return $this->cart;
        }

        public function generateRef () {
            $bytes = random_bytes(10);
            $hex = bin2hex($bytes);
            $prefix = 'FMO_';
            return $prefix.strtoupper($hex);
        }

        public function getNet ($id) {
            $query = 'SELECT cart.qty,
                            cart.subtotal,
                            price_list.base_price
                    FROM cart
                    INNER JOIN price_list ON price_list.product_id = cart.product_id
                    WHERE cart.user_id = ?
                    AND active = 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($qty, $subtotal, $base_price);
                    $net = 0;
                    while ($stmt->fetch()) {
                        $profit = $subtotal - ($base_price * $qty);
                        $net += $profit;
                    }
                    $stmt->close();
                    return $net;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getGross ($id, $delivery_fee) {
            $query = 'SELECT subtotal FROM cart WHERE user_id = ? AND active = 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($subtotal);
                    $total = 0;
                    while ($stmt->fetch()) {
                        $total += $subtotal;
                    }
                    $stmt->close();
                    $gross = $total + $delivery_fee;
                    return $gross;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function deleteCart ($user_id) {
            $query = 'DELETE FROM cart WHERE user_id = ? AND active = 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function orderItems($order_ref, $user_id) {
            $query = 'INSERT INTO order_items (order_ref, product_id, qty)
                    SELECT ?, product_id, qty
                    FROM cart
                    WHERE user_id = ?
                    AND active = 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $order_ref, $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getOrderProducts ($order_ref) {
            $query = 'SELECT product_id, qty FROM order_items WHERE order_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $order_ref);
            if ($stmt) {
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
                    return $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function updateStock ($products) {
            foreach ($products as $product) {
                $product_id = $product['id'];
                $qty = $product['qty'];

                $query = 'UPDATE stock SET qty = qty - ? WHERE product_id = ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('ii', $qty, $product_id);
                if ($stmt) {
                    if ($stmt->execute()) {
                        $stmt->close();
                    } else {
                        die("Error in executing statement: " . $stmt->error);
                        $stmt->close();
                    }
                } else {
                    die("Error in preparing statement: " . $this->conn->error);
                }
            }
        }

        public function placeOrder () {
            $this->newCart();
            $query = 'INSERT INTO orders
                        (order_ref, firstname, lastname, phone, gross, delivery_fee, net, 
                        transaction_type_id, payment_type_id, address_id, user_id, paid, status)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';

            $user_id = $_POST['user_id'];
            $order_ref = $this->generateRef();
            $delivery_fee = intval($_POST['delivery-fee-value']);
            $net = $this->getNet($user_id);
            $gross = $this->getGross($user_id, $delivery_fee);
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $phone = $_POST['phone'];
            $transaction_type_id = 1;
            $payment_type_id = $_POST['payment_type'];
            $address_id = intval($_POST['address_id']);
            $paid = 'unpaid';
            $status = 'pending';

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssiiiiiiiss', $order_ref, $fname, $lname, $phone, $gross, $delivery_fee, 
                            $net, $transaction_type_id, $payment_type_id, $address_id, $user_id, 
                            $paid, $status);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $this->orderItems($order_ref, $user_id);
                    $this->deleteCart($user_id);
                    $products = $this->getOrderProducts($order_ref);
                    $this->updateStock($products);
                    return [
                        'redirect' => '/my-purchases/pending'
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }
    }
?>