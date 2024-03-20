<?php
    require_once 'model/home/home.php';

    class Cart extends Home {
        public function getProductPrice ($id) {
            $conn = $this->getConnection();

            $query = 'SELECT unit_price FROM price_list WHERE product_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($unit_price);
                    $stmt->fetch();
                    $stmt->close();
                    return $unit_price;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function isCartExist ($user_id, $product_id) {
            $conn = $this->getConnection();

            $query = 'SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $user_id, $product_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();

                    if ($count > 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function cartCount ($user_id) {
            $conn = $this->getConnection();

            $query = 'SELECT COUNT(*) FROM cart WHERE user_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    return $count;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function addCart () {
            $conn = $this->getConnection();

            $id = $_POST['id'];
            $subtotal = $this->getProductPrice($id);
            $qty = 1;
            if ($this->isCartExist($_SESSION['user_id'], $id)) {
                $json = array('cart_feedback' => 'Product already in cart');
                echo json_encode($json);
                return;
            }
            $query = 'INSERT INTO cart
                        (user_id, product_id, qty, subtotal)
                    VALUES (?,?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iiii', $_SESSION['user_id'], $id, $qty, $subtotal);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $cart_count = $this->cartCount($_SESSION['user_id']);
                    $json = array('cart_count' => $cart_count);
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>