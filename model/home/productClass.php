<?php
    require_once 'model/home/home.php';

    class Product extends Home {
        public function getProductIDs ($name) {
            $query = 'SELECT id FROM product WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $name);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id);
                    $product_array = array();
                    while ($stmt->fetch()) {
                        $product_array[] = $id;
                    }

                    return $product_array;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getPriceRange ($product_ids) {
            $prices = array();
            $query = 'SELECT unit_price FROM price_list WHERE product_id = ?';

            foreach ($product_ids as $product_id) {
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i', $product_id);
                if ($stmt) {
                    if ($stmt->execute()) {
                        $stmt->bind_result($unit_price);
                        $stmt->fetch();
                        $stmt->close();
                        $prices[] = $unit_price;
                    } else {
                        die("Error in executing statement: " . $stmt->error);
                        $stmt->close();
                    }
                } else {
                    die("Error in preparing statement: " . $this->conn->error);
                }
            }
            $min_price = min($prices);
            $max_price = max($prices);
            if ($min_price == $max_price) {
                $price_format = '₱'.number_format($min_price).'.00';
            } else {
                $price_format = '₱'.number_format($min_price).'.00 - ₱'.number_format($max_price).'.00';
            }

            return $price_format;
        }

        public function getProducts () {
            $query = 'SELECT product.id, product.name, product.image, stock.qty FROM product
                    INNER JOIN stock ON stock.product_id = product.id
                    WHERE EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id) 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $stock);
                    while ($stmt->fetch()) {
                        echo '<div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <a href="/view-product/product/'.$id.'">
                                        <img src="/asset/images/products/'.$image.'" class="card-img-top product-image" alt="'.$name.'">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title text-center fw-bold">'.$name.'</h5>
                                    </div>
                                </div>
                            </div>';
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

        public function getProductInfo ($product_id) {
            $query = 'SELECT id,
                            name,
                            code,
                            supplier_id,
                            barcode,
                            image,
                            description,
                            brand_id,
                            category_id,
                            unit_id,
                            unit_value,
                            variant_id,
                            expiration_date,
                            user_id,
                            date,
                            active
                    FROM product
                    WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $product_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $code, $supplier_id, $barcode, $image, 
                                        $description, $brand_id, $category_id, $unit_id, $unit_value, 
                                        $variant_id, $expiration_date, $user_id, $date, $active);
                    $stmt->fetch();
                    $stmt->close();
                    return [
                        'id' => $id,
                        'name' => $name,
                        'code' => $code,
                        'supplier_id' => $supplier_id,
                        'barcode' => $barcode,
                        'image' => $image,
                        'description' => $description,
                        'brand_id' => $brand_id,
                        'category_id' => $category_id,
                        'unit_id' => $unit_id,
                        'unit_value' => $unit_value,
                        'variant_id' => $variant_id,
                        'expiration' => $expiration_date,
                        'user_id' => $user_id,
                        'date' => $date,
                        'active' => $active
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getPrice ($product_name) {
            $product_ids = $this->getProductIDs($product_name);
            $price = $this->getPriceRange($product_ids);
            echo $price;
        }

        public function getBrand ($brand_id) {
            $query = 'SELECT name FROM brand WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $brand_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($brand);
                    $stmt->fetch();
                    $stmt->close();
                    echo $brand;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCategory ($category_id) {
            $query = 'SELECT name FROM category WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $category_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($category);
                    $stmt->fetch();
                    $stmt->close();
                    echo $category;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getVariants ($product_name) {
            $query = 'SELECT product.id,
                            product.name,
                            product.image,
                            product.unit_value,
                            unit.name,
                            variant.name,
                            stock.qty,
                            price_list.unit_price
                    FROM product
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN stock ON stock.product_id = product.id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    WHERE product.name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $product_name);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $unit_value, $unit, $variant, $stock, $price);
                    while ($stmt->fetch()) {
                        if ($stock == 0) {
                            $button = '<input type="radio" name="product_id" value="'.$id.'" disabled />';
                        } else {
                            $button = '<input type="radio" name="product_id" value="'.$id.'" required />';
                        }
                        echo '<div class="col-md-6 mb-2">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        '.$button.'
                                        <img class="me-2" width="60px" src="/asset/images/products/'.$image.'" alt="GCash" />
                                        <div class="mt-2">
                                            <p class="mb-0">'.$variant.'</p>
                                            <p class="mb-0">('.$unit_value.' '.$unit.')</p>
                                            <p class="mb-0 text-muted">Stock: '.$stock.'</p>
                                            <p class="mb-0">₱'.number_format($price).'.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
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