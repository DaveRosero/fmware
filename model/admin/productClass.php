<?php
    require_once 'model/admin/admin.php';

    class Products extends Admin {
        public function getCategory () {
            $query = 'SELECT id, name, active FROM category';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getBrands() {
            $query = 'SELECT id, name, active FROM brand';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getUnits () {
            $query = 'SELECT id, name, active FROM unit';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function isProductExist ($product) {
            $query = 'SELECT COUNT(*) FROM product WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $product);
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

        public function newProduct () {
            if ($this->isProductExist($_POST['name'])) {
                $json = array('product_feedback' => 'Product already exist.');
                echo json_encode($json);
                return;
            }

            $uploadDir = 'asset/images/products/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);

            // Product Image
            $image = basename($uploadFile);

            $name = $_POST['name'];
            $code = $_POST['code'];
            $supplier_code = $_POST['supplier_code'];
            $brand = $_POST['brand'];
            $category = $_POST['category'];
            $unit = $_POST['unit'];
            $unit_value = $_POST['unit_value'];
            $expiration_date = $_POST['expiration_date'];
            $barcode = $_POST['barcode'];
            $description = $_POST['description'];
            $active = 1;

            $query = 'INSERT INTO product
                        (name, code, supplier_code, barcode, image, description, brand_id, category_id, unit_id, unit_value, expiration_date, user_id, active)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssiiiisii', $name, $code, $supplier_code, $barcode, $image, $description, $brand, $category, $unit, $unit_value, $expiration_date, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/products');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getProducts () {
            $query = 'SELECT
                        product.id,
                        product.name,
                        product.image,
                        product.unit_value,
                        product.active,
                        user.firstname,
                        user.lastname,
                        brand.name,
                        category.name,
                        unit.name 
                    FROM product
                    INNER JOIN user ON user.id = product.user_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id
                    INNER JOIN unit ON unit.id = product.unit_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $unit_value, $active, $fname, $lname, $brand, $category, $unit);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'" data-product-status="'.$active.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'" data-product-status="'.$active.'">
                                        </div>';
                        }
                        $initial = substr($lname, 0, 1);
                        $author = $fname.' '.$initial.'.';
                        echo '<tr>
                                <td>'.$status.'</td>
                                <td><img src="asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td>'.$name.'</td>
                                <td>'.$brand.'</td>
                                <td>'.$category.'</td>
                                <td>'.$unit_value.' '.$unit.'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-primary view" 
                                        type="button" 
                                        data-product-id="'.$id.'" 
                                        data-product-name="'.$name.'"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>                                
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-product-id="'.$id.'" 
                                        data-product-name="'.$name.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger delete" 
                                        type="button" 
                                        data-product-id="'.$id.'" 
                                        data-product-name="'.$name.'"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>                                   
                                </td>
                            </tr>';
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
    }
?>