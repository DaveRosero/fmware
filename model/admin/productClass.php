<?php
    require_once 'model/admin/admin.php';

    class Products extends Admin {
        public function getCategory () {
            $conn = $this->getConnection();

            $query = 'SELECT id, name, active FROM category';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $active);
                    while ($stmt->fetch()) {
                        if ($active != 1) {
                            continue;
                        }
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

        public function getBrands() {
            $conn = $this->getConnection();

            $query = 'SELECT id, name, active FROM brand';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $active);
                    while ($stmt->fetch()) {
                        if ($active != 1) {
                            continue;
                        }
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

        public function getUnits () {
            $conn = $this->getConnection();

            $query = 'SELECT id, name, active FROM unit';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $active);
                    while ($stmt->fetch()) {
                        if ($active != 1) {
                            continue;
                        }
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

        public function getAttributes () {
            $conn = $this->getConnection();

            $query = 'SELECT id, name FROM attribute_data';
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

        public function newProduct () {
            $conn = $this->getConnection();

            $uploadDir = 'asset/images/products/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);

            $name = $_POST['name'];
            $sku = $_POST['sku'];
            if (empty($_POST['upc'])) {
                $upc = random_int(100000000, 999999999);
            } else {
                $upc = $_POST['upc'];
            }            
            $imgPath = $uploadFile;
            $description = $_POST['description'];
            $attribute = $_POST['attribute'];
            $brand = $_POST['brand'];
            $category = $_POST['category'];
            $unit = $_POST['unit'];
            $active = 1;

            $query = 'INSERT INTO product
                        (name, sku, upc, image, description, attribute_data_id, brand_id, category_id, unit_id, active)
                    VALUES (?,?,?,?,?,?,?,?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssiiiii', $name, $sku, $upc, $imgPath, $description, $attribute, $brand, $category, $unit, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /fmware/products');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getBarcode ($upc) {
            $data = $upc;
            $file_name = 'asset/images/products/barcode/'.$data.'_barcode.png';
            if (file_exists($file_name)) {
                return $file_name;
            } else {
                $barcodeType = 'CODE128';
                $apiUrl = "https://barcode.tec-it.com/barcode.ashx?data={$data}&type={$barcodeType}";
                $barcodeImage = file_get_contents($apiUrl);
                file_put_contents($file_name, $barcodeImage);
                return $file_name;
            }
        }

        public function getProducts () {
            $conn = $this->getConnection();

            $query = 'SELECT 
                        product.id, product.name, product.sku, product.upc, product.image, product.description, 
                        product.attribute_data_id, product.brand_id, product.category_id, product.unit_id, 
                        product.date, product.active,
                        attribute_data.name, brand.name, category.name, unit.name
                    FROM product
                    INNER JOIN attribute_data ON attribute_data.id = product.attribute_data_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id
                    INNER JOIN unit ON unit.id = product.unit_id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $sku, $upc, $image, $description, $attribute_data_id, $brand_id, $category_id, $unit_id, $date, $active, $attr_name, $brand_name, $category_name, $unit_name);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = 'ACTIVE';
                        } else {
                            $status = 'INACTIVE';
                        }

                        $barcode = $this->getBarcode($upc);  
                        
                        echo '<tr>
                                <td><img src="'.$image.'" alt="" srcset="" style="width: 50px;"></td>
                                <td>'.$name.'</td>
                                <td><img src="'.$barcode.'" alt="" srcset="" style="width: 100px;"></td>
                                <td>'.$brand_name.'</td>
                                <td>'.$category_name.'</td>
                                <td>'.$unit_name.'</td>
                                <td>'.$status.'</td>
                                <td>
                                    <a class="text-success mx-2" href="#"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a class="text-danger" href="#"><i class="fa-solid fa-trash"></i></a>
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