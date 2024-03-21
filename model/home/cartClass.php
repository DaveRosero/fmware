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

        public function getCart ($id) {
            $conn = $this->getConnection();

            $query = 'SELECT cart.id,
                            cart.qty,
                            cart.subtotal,
                            product.image,
                            product.name,
                            price_list.unit_price
                    FROM cart
                    INNER JOIN product ON product.id = cart.product_id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    WHERE cart.user_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) { 
                    $stmt->bind_result($id, $qty, $subtotal, $image, $name, $price);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 100px"></td>
                                <td>'.$name.'</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <button 
                                            class="btn btn-sm btn-danger subQty"
                                            data-cart-id="'.$id.'"
                                        >-</button>
                                        <input type="number" 
                                            class="form-control form-control-sm text-center qty" 
                                            value="'.$qty.'" style="width: 50px;" readonly
                                        >
                                        <button 
                                            class="btn btn-sm btn-primary addQty"
                                            data-cart-id="'.$id.'"
                                        >+</button>
                                    </div>
                                </td>
                                <td>₱'.number_format($price).'.00</td>
                                <td class="subtotal fw-semibold">₱'.number_format($subtotal).'.00</td>
                            </tr>';
                    }
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getCartTotal ($id) {
            $conn = $this->getConnection();

            $query = 'SELECT subtotal FROM cart WHERE user_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($subtotal);
                    $grandtotal = 0;
                    while ($stmt->fetch()){
                        $grandtotal += $subtotal;
                    }
                    echo '₱'.number_format($grandtotal).'.00';
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getNewSubtotal ($id) {
            $conn = $this->getConnection();

            $query = 'SELECT cart.qty,
                            price_list.unit_price
                    FROM cart
                    INNER JOIN price_list ON price_list.product_id = cart.product_id
                    WHERE cart.id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($qty, $price);
                    $stmt->fetch();
                    $subtotal = $qty * $price;
                    return [
                        'qty' => $qty,
                        'subtotal' => $subtotal
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function updateSubtotal ($id, $subtotal) {
            $conn = $this->getConnection();

            $query = 'UPDATE cart SET subtotal = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $subtotal, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function addQty () {
            $conn = $this->getConnection();

            $id = $_POST['id'];
            $qty = 1;

            $query = 'UPDATE cart SET qty = qty + ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $qty, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $new_cart = $this->getNewSubtotal($id);
                    $this->updateSubtotal($id, $new_cart['subtotal']);
                    $subtotal = number_format($new_cart['subtotal']);
                    $subtotal = '₱'.$subtotal.'.00';
                    $json = array(
                        'qty' => $new_cart['qty'],
                        'subtotal' => $subtotal
                    );
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function subQty () {
            $conn = $this->getConnection();

            $id = $_POST['id'];
            $qty = 1;

            $query = 'UPDATE cart SET qty = qty - ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $qty, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $new_cart = $this->getNewSubtotal($id);
                    $this->updateSubtotal($id, $new_cart['subtotal']);
                    $subtotal = number_format($new_cart['subtotal']);
                    $subtotal = '₱'.$subtotal.'.00';
                    $json = array(
                        'qty' => $new_cart['qty'],
                        'subtotal' => $subtotal
                    );
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function resetCart ($id) {
            $conn = $this->getConnection();

            $query = 'DELETE FROM cart WHERE user_id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $redirect = '/fmware/cart';
                    echo $redirect;
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