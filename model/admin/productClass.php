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

        public function getVariants () {
            $query = 'SELECT id, name, active FROM variant';
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

        public function newBrand ($brand) {
            $query = 'INSERT INTO brand
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $brand, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getBrandId ($brand) {
            $query = 'SELECT id FROM brand WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $brand);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($brand_id);
                    $stmt->fetch();
                    $stmt->close();
                    return $brand_id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newCategory ($category) {
            $query = 'INSERT INTO category
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $category, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCategoryId ($category) {
            $query = 'SELECT id FROM category WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $category);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($category_id);
                    $stmt->fetch();
                    $stmt->close();
                    return $category_id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newUnit ($unit) {
            $query = 'INSERT INTO unit
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $unit, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getUnitId ($unit) {
            $query = 'SELECT id FROM unit WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $unit);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($unit_id);
                    $stmt->fetch();
                    $stmt->close();
                    return $unit_id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newVariant ($variant) {
            $query = 'INSERT INTO variant
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $variant, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getVariantId ($variant) {
            $query = 'SELECT id FROM variant WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $variant);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($variant_id);
                    $stmt->fetch();
                    $stmt->close();
                    return $variant_id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newProduct () {
            // if ($this->isProductExist($_POST['name'])) {
            //     $json = array('exist' => 'Product already exist.');
            //     echo json_encode($json);
            //     return;
            // }

            if (is_numeric($_POST['brand']) && is_int($_POST['brand'] + 0)) {
                $brand_id = $_POST['brand'];
            } else {
                if ($this->newBrand($_POST['brand'])) {
                    $brand_id = $this->getBrandId($_POST['brand']);
                }
            }

            if (is_numeric($_POST['category']) && is_int($_POST['category'] + 0)) {
                $category_id = $_POST['category'];
            } else {
                if ($this->newCategory($_POST['category'])) {
                    $category_id = $this->getCategoryId($_POST['category']);
                }
            }

            if (is_numeric($_POST['unit']) && is_int($_POST['unit'] + 0)) {
                $unit_id = $_POST['unit'];
            } else {
                if ($this->newUnit($_POST['unit'])) {
                    $unit_id = $this->getUnitId($_POST['unit']);
                }
            }

            if (is_numeric($_POST['variant']) && is_int($_POST['variant'] + 0)) {
                $variant_id = $_POST['variant'];
            } else {
                if ($this->newVariant($_POST['variant'])) {
                    $variant_id = $this->getVariantId($_POST['variant']);
                }
            }

            $uploadDir = '/asset/images/products/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile);

            // Product Image
            $image = basename($uploadFile);
            $name = $_POST['name'];
            $code = $_POST['code'];
            $supplier_code = $_POST['supplier_code'];
            $unit_value = $_POST['unit_value'];
            $expiration_date = $_POST['expiration_date'];
            $barcode = $_POST['barcode'];
            $description = $_POST['description'];
            $active = 1;

            $query = 'INSERT INTO product
                        (name, code, supplier_code, barcode, image, description, brand_id, category_id, unit_id, unit_value, variant_id, expiration_date, user_id, active)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssiiiissii', $name, $code, $supplier_code, $barcode, $image, $description, $brand_id, $category_id, $unit_id, $unit_value, $variant_id, $expiration_date, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/products');
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
                        unit.name,
                        variant.name 
                    FROM product
                    INNER JOIN user ON user.id = product.user_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $unit_value, $active, $fname, $lname, $brand, $category, $unit, $variant);
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
                                <td><img src="/asset/images/products/'.$image.'" alt="" srcset="" style="width: 70px;"></td>
                                <td>'.$name.'</td>
                                <td>'.$variant.'</td>
                                <td>'.$brand.'</td>
                                <td>'.$category.'</td>
                                <td>'.$unit_value.' '.strtoupper($unit).'</td>
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