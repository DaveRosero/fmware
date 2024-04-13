<?php
    require_once 'model/home/home.php';

    class Cart extends Home {
        public function getProductPrice ($id) {
            $query = 'SELECT unit_price FROM price_list WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function isCartExist ($user_id, $product_id) {
            $query = 'SELECT COUNT(*) FROM cart WHERE user_id = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function cartCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM cart WHERE user_id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function addCart () {
            $id = $_POST['product_id'];
            $subtotal = $this->getProductPrice($id);
            $qty = 1;
            $active = 0;
            if ($this->isCartExist($_SESSION['user_id'], $id)) {
                $json = array('product_exist' => 'Product already in cart');
                echo json_encode($json);
                return;
            }
            $query = 'INSERT INTO cart
                        (user_id, product_id, qty, subtotal, active)
                    VALUES (?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iiiii', $_SESSION['user_id'], $id, $qty, $subtotal, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $cart_count = $this->cartCount($_SESSION['user_id']);
                    $json = array(
                        'cart_count' => $cart_count,
                        'product_added' => 'Product added to cart.'
                    );
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCart ($id) {
            $query = 'SELECT cart.id,
                            cart.user_id,
                            cart.qty,
                            cart.product_id,
                            cart.subtotal,
                            cart.active,
                            product.image,
                            product.name,
                            product.unit_value,
                            product.description,
                            price_list.unit_price,
                            unit.name
                    FROM cart
                    INNER JOIN product ON product.id = cart.product_id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    INNER JOIN unit ON unit.id = product.unit_id
                    WHERE cart.user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) { 
                    $stmt->bind_result($cart_id, $user_id, $qty, $product_id, $subtotal, $active, $image, $name, $unit_value, $description, $price, $unit);
                    while ($stmt->fetch()) {
                        if ($active == 0) {
                            $checkbox = '<input class="form-check-input cart-checkbox" type="checkbox" name="selected_products[]" value="'.$product_id.'" data-user-id="'.$user_id.'">';
                        } else {
                            $checkbox = '<input class="form-check-input cart-checkbox" type="checkbox" name="selected_products[]" value="'.$product_id.'" data-user-id="'.$user_id.'" checked>';
                        }
                        echo '<!-- Single item -->
                        <div class="row">
                            <div class="col-lg-1 col-md-2 mb-4 mb-lg-0 mt-5">
                                <!-- Checkbox -->
                                <div class="form-check">
                                    '.$checkbox.'
                                </div>
                                <!-- Checkbox -->
                            </div>
                        
                            <div class="col-lg-2 col-md-3 mb-4 mb-lg-0">
                                <!-- Image -->
                                <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                                    <img src="asset/images/products/'.$image.'" class="w-100" alt="product" />
                                    <a href="#!">
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                                    </a>
                                </div>
                                <!-- Image -->
                            </div>
                        
                            <div class="col-lg-4 col-md-5 mb-4 mb-lg-0">
                                <!-- Data -->
                                <p><strong>'.$name.'</strong></p>
                                <p>Unit: '.$unit_value.' '.$unit.' </p>
                                <p>Description: '. (!empty($description) ? $description : 'N/A') .'</p>
                                <button type="button" class="btn btn-danger btn-sm me-1 mb-2" data-mdb-toggle="tooltip"
                                    title="Remove item">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm mb-2" data-mdb-toggle="tooltip"
                                    title="View Product">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <!-- Data -->
                            </div>
                        
                            <div class="col-lg-3 col-md-2 mb-4 mb-lg-0">
                                <!-- Quantity -->
                                <div class="d-flex mb-4" style="max-width: 300px">
                                    <button class="btn btn-primary px-3 me-2 subQty"
                                        data-product-id="'.$product_id.'"
                                        data-cart-id="'.$cart_id.'">
                                        <i class="fas fa-minus"></i>
                                    </button>
                        
                                    <div class="form-outline">
                                        <input min="0" name="quantity" value="'.$qty.'" type="number" 
                                            class="form-control qty_'.$product_id.'" />
                                        <label class="form-label">Quantity</label>
                                    </div>
                        
                                    <button class="btn btn-primary px-3 ms-2 addQty"
                                        data-product-id="'.$product_id.'"
                                        data-cart-id="'.$cart_id.'">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <!-- Quantity -->
                        
                                <!-- Price -->
                                <p class="text-start text-md-center">
                                    <strong class="subtotal_'.$product_id.'">₱'.number_format($subtotal).'.00</strong>
                                </p>
                                <!-- Price -->
                            </div>
                        </div>
                        <!-- Single item -->
                        ';
                    }
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCartTotal ($id, $delivery_fee) {
            $query = 'SELECT subtotal FROM cart WHERE user_id = ? AND active = 1';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($subtotal);
                    $product = 0;
                    while ($stmt->fetch()){
                        $product += $subtotal;
                    }
                    $stmt->close();
                    $checkout = $product + $delivery_fee;
                    return [
                        'product_total' => '₱'.number_format($product).'.00',
                        'checkout_total' => '₱'.number_format($checkout).'.00'
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getNewSubtotal ($id) {
            $query = 'SELECT cart.qty,
                            price_list.unit_price
                    FROM cart
                    INNER JOIN price_list ON price_list.product_id = cart.product_id
                    WHERE cart.id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function updateSubtotal ($id, $subtotal) {
            $query = 'UPDATE cart SET subtotal = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $subtotal, $id);
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

        public function addQty () {
            $id = $_POST['id'];
            $qty = 1;

            $query = 'UPDATE cart SET qty = qty + ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function subQty () {
            $id = $_POST['id'];
            $qty = 1;

            $query = 'UPDATE cart SET qty = qty - ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function checkProduct ($user_id, $product_id) {
            $active = 1;
            $query = 'UPDATE cart SET active = ? WHERE user_id = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iii', $active, $user_id, $product_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'checked' => 'Product is selected'
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function uncheckProduct ($user_id, $product_id) {
            $active = 0;
            $query = 'UPDATE cart SET active = ? WHERE user_id = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iii', $active, $user_id, $product_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'unchecked' => 'Unchecked Product'
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getDeliveryFee ($brgy) {
            $query = 'SELECT delivery_fee FROM delivery_fee WHERE brgys LIKE ?';
            $stmt = $this->conn->prepare($query);
            $param = '%'.$brgy.'%';
            $stmt->bind_param('s', $param);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($delivery_fee);
                    $stmt->fetch();
                    $stmt->close();
                    return [
                        'delivery_fee' => number_format($delivery_fee),
                        'delivery_value' => $delivery_fee
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