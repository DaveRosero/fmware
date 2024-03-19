<?php
    require_once 'model/admin/admin.php';

    class Stocks extends Admin {
        public function getProducts () {
            $conn = $this->getConnection();
            $query = 'SELECT id, name FROM product 
                    WHERE NOT EXISTS 
                        (SELECT 1 FROM stock WHERE stock.product_id = product.id)';
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

        public function getStocks () {
            $conn = $this->getConnection();

            $query = 'SELECT stock.product_id,
                            stock.qty,
                            stock.critical_level,
                            product.image,
                            product.name,
                            brand.name,
                            category.name
                    FROM stock
                    INNER JOIN product ON product.id = stock.product_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $qty, $critical_level, $image, $name, $brand, $category);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td>'.$name.'</td>
                                <td>'.$brand.'</td>
                                <td>'.$category.'</td>
                                <td>'.$qty.'</td>
                                <td>'.$critical_level.'%</td>
                                <td>
                                    <button 
                                        type="button" 
                                        class="btn btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#restock"
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function addStock () {
            $conn = $this->getConnection();
            
            $id = $_POST['product_id'];
            $critical_level = $_POST['critical_level'];

            $query = 'INSERT INTO stock (product_id, critical_level)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $id, $critical_level);
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
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>