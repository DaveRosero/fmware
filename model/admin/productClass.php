<?php
    include_once 'session.php';
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

        public function insertPrice ($id, $base_price, $selling_price) {
            $query = 'INSERT INTO price_list 
                        (product_id, base_price, unit_price)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('idd', $id, $base_price, $selling_price);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            return;
        }

        public function insertStock ($id, $qty, $critical_level, $stockable) {
            $query = 'INSERT INTO stock
                        (product_id, qty, critical_level, stockable)
                    VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('iiii', $id, $qty, $critical_level, $stockable);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            return;
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
            $default = 'default-product.png';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadFile = $uploadDir . basename($_FILES['image']['name']);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $image = basename($uploadFile);
                } else {
                    $image = $default;
                }
            } else {
                $image = $default;
            }

            $name = $_POST['name'];
            $code = $_POST['code'];
            $supplier_id = $_POST['supplier'];
            $unit_value = $_POST['unit_value'];
            $expiration_date = $_POST['expiration_date'];
            $barcode = $_POST['barcode'];
            $description = $_POST['description'];
            $active = 1;
            $currentDate = date('F j, Y');

            $query = 'INSERT INTO product
                        (name, code, supplier_id, barcode, image, description, brand_id, category_id, unit_id, unit_value, variant_id, expiration_date, user_id, date, active)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssiiisssisi', $name, $code, $supplier_id, $barcode, $image, $description, $brand_id, $category_id, $unit_id, $unit_value, $variant_id, $expiration_date, $_SESSION['user_id'], $currentDate, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/manage-products');
                    $last_id = $this->conn->insert_id;
                    $this->insertPrice($last_id, $_POST['base_price'], $_POST['selling_price']);
                    $this->insertStock($last_id, $_POST['initial_stock'], $_POST['critical_level'], $_POST['stockable']);
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function stockLegend ($qty, $critical_level) {
            if ($qty == 0) {
                return '<span class="badge bg-dark fw-semibold text-wrap"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >'.$qty.'</span>';
            }

            if ($qty <= $critical_level) {
                return '<span class="badge bg-danger fw-semibold text-wrap"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >'.$qty.'</span>';
            }

            if ($qty > $critical_level) {
                return '<span class="badge bg-success fw-semibold text-wrap"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >'.$qty.'</span>';
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
                        variant.name,
                        price_list.base_price,
                        price_list.unit_price,
                        stock.qty,
                        stock.critical_level 
                    FROM product
                    INNER JOIN user ON user.id = product.user_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN category ON category.id = product.category_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    INNER JOIN stock ON stock.product_id = product.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $unit_value, $active, $fname, $lname, $brand, $category, $unit, $variant, $base_price, $selling_price, $stock, $critical_level);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'">
                                        </div>';
                        }

                        $legend = $this->stockLegend($stock, $critical_level);

                        echo '<tr>
                                <td class="text-start">'.$status.'</td>
                                <td class="text-start"><img src="/asset/images/products/'.$image.'" alt="" srcset="" style="width: 50px;"></td>
                                <td class="text-start">'.$name.' ('.$variant.')</td>
                                <td class="text-start">'.$brand.'</td>
                                <td class="text-start">'.$category.'</td>
                                <td class="text-center">'.$unit_value.' '.strtoupper($unit).'</td>
                                <td class="text-start">₱'.number_format($base_price, 2).'</td>
                                <td class="text-start">₱'.number_format($selling_price, 2).'</td>
                                <td class="text-start">'.$legend.'</td>
                                <td class="text-start">
                                    <button 
                                        class="btn btn-sm btn-secondary view" 
                                        type="button" 
                                        data-product-id="'.$id.'"
                                    >
                                        <i class="fa-solid fa-eye fs-1"></i>
                                    </button>                             
                                    <button 
                                        class="btn btn-sm btn-primary edit" 
                                        type="button" 
                                        data-product-id="'.$id.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square fs-1"></i>
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

        public function disableProduct ($active, $id) {
            $query = 'UPDATE product SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'redirect' => '/manage-products'
            );
            echo json_encode($json);
            return;
        }

        public function supplierOptions () {
            $query = 'SELECT id, name, active FROM supplier WHERE active = 1';
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $supplier, $active);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<option value="'.$id.'">'.$supplier.'</option>';
            }

            $stmt->close();
            echo $content;
            return;
        }
    }
?>