<?php
    require_once 'model/admin/admin.php';

    class Stocks extends Admin {
        public function getProducts () {
            $query = 'SELECT product.id, product.name, product.unit_value, unit.name, variant.name FROM product
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id 
                    WHERE NOT EXISTS 
                        (SELECT 1 FROM stock WHERE stock.product_id = product.id)';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $unit_value, $unit, $variant);
                    while ($stmt->fetch()) {
                        echo '<option value="'.$id.'">'.$name.' ('.$variant.') ('.$unit_value.' '.strtoupper($unit).')</option>';
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

        public function checkCriticalLevel ($qty, $critical_level, $max_stock) {
            $critical_stock = ($critical_level / 100) * $max_stock;
            if ($qty < $critical_stock) {
                $stock = '<span class="badge bg-danger text-wrap">'.$qty.'</span>';
            } else {
                $stock = '<span class="badge bg-success text-wrap">'.$qty.'</span>';
            }

            return $stock;
        }

        public function getStocks () {
            $query = 'SELECT stock.product_id,
                            stock.qty,
                            stock.critical_level,
                            stock.max_stock,
                            product.image,
                            product.name,
                            product.unit_value,
                            variant.name,
                            unit.name,
                            brand.name,
                            category.name
                    FROM stock
                    INNER JOIN product ON product.id = stock.product_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $qty, $critical_level, $max_stock, $image, $name, $unit_value, $variant, $unit, $brand, $category);
                    while ($stmt->fetch()) {
                        $stock = $this->checkCriticalLevel($qty, $critical_level, $max_stock);
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td class="fw-semibold">'.$name.'</td>
                                <td class="fw-semibold">'.$variant.'</td>
                                <td class="fw-semibold">'.$unit_value.' '.strtoupper($unit).' </td>
                                <td class="fw-semibold">'.$brand.'</td>
                                <td class="fw-semibold">'.$category.'</td>
                                <td class="fw-semibold">'.$stock.'</td>
                                <td class="fw-semibold">'.$critical_level.'%</td>
                                <td class="fw-semibold">'.$max_stock.'</td>
                                <td>
                                    <button 
                                        type="button" 
                                        class="btn btn-primary restock"
                                        data-product-name="'.$name.'"
                                        data-product-id="'.$id.'" 
                                    >
                                        Restock
                                    </button> 
                                </td>
                            </tr>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getRestock () {
            $query = 'SELECT restock.supplier_order_no,
                            restock.qty,
                            restock.date,
                            product.image,
                            product.name,
                            user.firstname,
                            user.lastname 
                    FROM restock
                    INNER JOIN product ON product.id = restock.product_id
                    INNER JOIN user ON user.id = restock.user_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($supplier_order_no, $qty, $date, $image, $name, $fname, $lname);
                    while ($stmt->fetch()) {
                        $initial = substr($lname, 0, 1);
                        $author = $fname.' '.$initial.'.';
                        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                        $dateFormat = $dateObj->format('d F Y');
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td>'.$name.'</td>
                                <td>'.$qty.'</td>
                                <td>'.$supplier_order_no.'</td>
                                <td>'.$author.'</td>
                                <td>'.$dateFormat.'</td>
                            </tr>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function addStock () {
            $id = $_POST['product_id'];
            $initial_stock = $_POST['initial_stock'];
            $critical_level = $_POST['critical_level'];
            $max_stock = $_POST['max_stock'];   

            $query = 'INSERT INTO stock (product_id, qty, critical_level, max_stock)
                    VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iiii', $id, $initial_stock, $critical_level, $max_stock);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/stocks');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function updateQty ($qty, $id) {
            $query = 'UPDATE stock SET qty = qty + ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $qty, $id);
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

        public function restock () {
            $supplier_order_no = $_POST['supplier_order_no'];
            $product_id = $_POST['product_id'];
            $qty = $_POST['qty'];
            $date = $_POST['date'];

            $query = 'INSERT INTO restock
                        (user_id, supplier_order_no, product_id, qty, date)
                    VALUES (?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('isiis', $_SESSION['user_id'], $supplier_order_no, $product_id, $qty, $date);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $this->updateQty($qty, $product_id);
                    $json = array('redirect' => '/fmware/stocks');
                    echo json_encode($json);
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