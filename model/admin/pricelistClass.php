<?php
    require_once 'model/admin/admin.php';

    class PriceList extends Admin {
        public function getProducts () {
            $query = 'SELECT product.id, product.name, product.unit_value, unit.name, variant.name FROM product 
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    WHERE NOT EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id)';
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

        public function newPrice () {
            $id = $_POST['product_id'];
            $base_price = $_POST['base_price'];
            $unit_price = $_POST['unit_price'];

            $query = 'INSERT INTO price_list
                        (product_id, base_price, unit_price)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iii', $id, $base_price, $unit_price);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/price-list');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getPrices () {
            $query = 'SELECT price_list.id,
                            price_list.base_price,
                            price_list.unit_price,
                            product.id,
                            product.name,
                            product.image,
                            product.unit_value,
                            unit.name,
                            variant.name,
                            brand.name,
                            category.name
                    FROM price_list
                    INNER JOIN product ON product.id = price_list.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($price_id, $base_price, $unit_price, $product_id, $name, $image, $unit_value, $unit, $variant, $brand, $category);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td class="fw-semibold">'.$name.'</td>
                                <td class="fw-semibold">'.$variant.'</td>
                                <td class="fw-semibold">'.$brand.'</td>
                                <td class="fw-semibold">'.$category.'</td>
                                <td class="fw-semibold">₱'.number_format($base_price).'.00</td>
                                <td class="fw-semibold">₱'.number_format($unit_price).'.00</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-product-id="'.$product_id.'" 
                                        data-product-name="'.$name.'"
                                        data-price-id="'.$price_id.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
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
    }
?>