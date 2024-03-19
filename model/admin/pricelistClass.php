<?php
    require_once 'model/admin/admin.php';

    class PriceList extends Admin {
        public function getProducts () {
            $conn = $this->getConnection();
            $query = 'SELECT id, name FROM product 
                    WHERE NOT EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id)';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) {
                        echo '<option value="'.$id.'">'.$name.'</option>';
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

        public function newPrice () {
            $conn = $this->getConnection();

            $id = $_POST['product_id'];
            $base_price = $_POST['base_price'];
            $unit_price = $_POST['unit_price'];

            $query = 'INSERT INTO price_list
                        (product_id, base_price, unit_price)
                    VALUES (?,?,?)';
            $stmt = $conn->prepare($query);
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getPrices () {
            $conn = $this->getConnection();

            $query = 'SELECT price_list.id,
                            price_list.base_price,
                            price_list.unit_price,
                            product.id,
                            product.name,
                            product.image
                    FROM price_list
                    INNER JOIN product ON product.id = price_list.product_id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($price_id, $base_price, $unit_price, $product_id, $name, $image);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td>'.$name.'</td>
                                <td>₱'.number_format($base_price).'</td>
                                <td>₱'.number_format($unit_price).'</td>
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
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>