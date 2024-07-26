<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

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
                        echo '<option value="'.$id.'">'.ucwords($name).'</option>';
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
                        echo '<option value="'.$id.'">'.strtoupper($name).'</option>';
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
                        echo '<option value="'.$id.'">'.ucfirst($name).'</option>';
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
                        echo '<option value="'.$id.'">'.ucwords($name).'</option>';
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
            $logs = new Logs();

            $brand = strtoupper($brand);
            $query = 'INSERT INTO brand
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $brand, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Added new brand '.$brand;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

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
            $logs = new Logs();

            $category = ucwords($category);
            $query = 'INSERT INTO category
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $category, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();

                    $action_log = 'Added new category '.$category;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
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
            $logs = new Logs();
            
            $unit = ucwords($unit);
            $query = 'INSERT INTO unit
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $unit, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Added new unit '.$unit;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

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
            $logs = new Logs();
            
            $variant = ucwords($variant);
            $query = 'INSERT INTO variant
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $active = 1;
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $variant, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Added new variant '.$variant;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

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

            $logs = new Logs();

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

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/asset/images/products/';
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

            $name = ucwords($_POST['name']);
            $code = $_POST['code'];
            $supplier_id = $_POST['supplier'];
            $unit_value = $_POST['unit_value'];
            $expiration_date = $_POST['expiration_date'];
            $barcode = $_POST['barcode'];
            $description = $_POST['description'];
            $active = 1;
            $currentDate = date('F j, Y');
            $pickup = $_POST['pickup'];
            $delivery = $_POST['delivery'];

            $query = 'INSERT INTO product
                        (name, code, supplier_id, barcode, image, description, brand_id, category_id, unit_id, unit_value, variant_id, expiration_date, user_id, date, pickup, delivery, active)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssiiisssisiii', $name, $code, $supplier_id, $barcode, $image, $description, $brand_id, $category_id, $unit_id, $unit_value, $variant_id, $expiration_date, $_SESSION['user_id'], $currentDate, $pickup, $delivery, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/manage-products');
                    $last_id = $this->conn->insert_id;
                    $this->insertPrice($last_id, $_POST['base_price'], $_POST['selling_price']);
                    $this->insertStock($last_id, $_POST['initial_stock'], $_POST['critical_level'], $_POST['stockable']);
                    
                    $action_log = 'Added new product '.$name;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                    
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function stockLegend ($qty, $critical_level, $stockable) {
            if ($stockable == 0) {
                return '<span class="badge bg-dark text-light fw-semibold text-wrap cursor-pointer"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >N/A</span>';
            }

            if ($qty == 0) {
                return '<span class="badge bg-dark fw-semibold text-wrap cursor-pointer"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >'.$qty.'</span>';
            }

            if ($qty <= $critical_level) {
                return '<span class="badge bg-danger fw-semibold text-wrap cursor-pointer"
                            data-bs-toggle="tooltip" title="Critical Level: '.$critical_level.'"
                        >'.$qty.'</span>';
            }

            if ($qty > $critical_level) {
                return '<span class="badge bg-success fw-semibold text-wrap cursor-pointer"
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
                        stock.critical_level,
                        stock.stockable 
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
                    $stmt->bind_result($id, $name, $image, $unit_value, $active, $fname, $lname, $brand, $category, $unit, $variant, $base_price, $selling_price, $stock, $critical_level, $stockable);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-product-id="'.$id.'">
                                        </div>';
                        }

                        $legend = $this->stockLegend($stock, $critical_level, $stockable);

                        echo '<tr>
                                <td class="text-center">'.$status.'</td>
                                <td class="text-center"><img src="/asset/images/products/'.$image.'" alt="" srcset="" style="width: 50px;"></td>
                                <td class="text-center">'.$name.' ('.$variant.') '.$unit_value.' '.strtoupper($unit).'</td>
                                <td class="text-center">'.$brand.'</td>
                                <td class="text-center">'.$category.'</td>
                                <td class="text-center">₱'.number_format($base_price, 2).'</td>
                                <td class="text-center">₱'.number_format($selling_price, 2).'</td>
                                <td class="text-center">'.$legend.'</td>
                                <td class="text-center">
                                    <div class="d-flex">
                                        <button 
                                            class="btn btn-sm btn-primary view me-1" 
                                            type="button" 
                                            data-product-id="'.$id.'"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewProduct";
                                        >
                                            <i class="fa-solid fa-eye"></i>
                                        </button>   
                                        <button 
                                            class="btn btn-sm btn-success edit" 
                                            type="button" 
                                            data-product-id="'.$id.'"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProduct"
                                        >
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </div>                              
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
            $logs = new Logs();

            $product = $this->getProductInfo($id);
            
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

            if ($active == 0) {
                $action_log = 'Disable product '.$product['name'];
            } else {
                $action_log = 'Enable product '.$product['name'];
            }

            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            
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

        public function getProductInfo ($id) {
            $query = 'SELECT product.name,
                            product.code,
                            product.supplier_id,
                            product.description,
                            product.expiration_date,
                            product.brand_id,
                            product.unit_value,
                            product.unit_id,
                            product.category_id,
                            product.variant_id,
                            product.barcode,
                            price_list.base_price,
                            price_list.unit_price,
                            stock.qty,
                            stock.critical_level,
                            product.pickup,
                            product.delivery,
                            stock.stockable,
                            product.image
                    FROM product
                    INNER JOIN price_list ON price_list.product_id = product.id
                    INNER JOIN stock ON stock.product_id = product.id
                    WHERE product.id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($name, $code, $supplier, $description, $expiration, $brand, $unit_value, $unit,
                            $category, $variant, $barcode, $base_price, $selling_price, $stock, $critical_level,
                            $pickup, $delivery, $stockable, $image);
            $stmt->fetch();
            $stmt->close();

            $json = array(
                'id' => $id,
                'name' => $name,
                'code' => $code,
                'supplier' => $supplier,
                'description' => $description,
                'expiration' => $expiration,
                'brand' => $brand,
                'unit_value' => $unit_value,
                'unit' => $unit,
                'category' => $category,
                'variant' => $variant,
                'base_price' => $base_price,
                'selling_price' => $selling_price,
                'stock' => $stock,
                'critical_level' => $critical_level,
                'barcode' => $barcode,
                'pickup' => $pickup,
                'delivery' => $delivery,
                'stockable' => $stockable,
                'image' => $image
            );
            return $json;
        }

        public function editProduct ($id) {
            $logs = new Logs();
            $date_log = date('F j, Y g:i A');

            $product = $this->getProductInfo($id);

            if ($_FILES['edit_image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['edit_image']['tmp_name'])) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/asset/images/products/';

                if ($_FILES['edit_image']['name'] === 'default-product.png') {
                    $uploadFile = basename($_FILES['edit_image']['name']);
                } else {
                    $uploadFile = uniqid('image_') . $_FILES['edit_image']['name'];
                }

                $path = $uploadDir . $uploadFile;
                move_uploaded_file($_FILES['edit_image']['tmp_name'], $path);
                $image = $uploadFile;
                $this->editImage($image, $id);

                $action_log = 'Update image of product '.$product['name'];
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            }

            if ($product && $product['name'] !== $_POST['edit_name']) {
                $name = ucwords($_POST['edit_name']);
                $this->editProductName($name, $id);

                $action_log = 'Update product name from '.$product['name'].' to '.$name;
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            }

            if ($product && $product['code'] !== $_POST['edit_code']) {
                $code = strtoupper($_POST['edit_code']);
                $this->editItemCode($code, $id);

                if (empty($code)) {
                    $code = 'NULL';
                }

                if (empty($product['code'])) {
                    $old_code = 'NULL';
                } else {
                    $old_code = $product['code'];
                }

                $action_log = 'Update code of product '.$_POST['edit_name'].' from '.$old_code.' to '.$code;
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            }

            if ($product && $product['supplier'] !== $_POST['edit_supplier']) {
                $old_supplier = $this->getSupplier($product['supplier']);
                $this->editSupllier($_POST['edit_supplier'], $id);
                $new_supplier = $this->getSupplier($_POST['edit_supplier']);

                if ($old_supplier !== $new_supplier) {
                    $action_log = 'Changed supplier of product '.$_POST['edit_name'].' from '.$old_supplier.' to '.$new_supplier;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            if ($product && $product['description'] !== $_POST['edit_description']) {
                $this->editDescription($_POST['edit_description'], $id);

                if (empty($_POST['edit_description'])) {
                    $new_desc = 'NULL';
                }

                if (empty($product['description'])) {
                    $old_desc = 'NULL';
                } else {
                    $old_desc = $product['description'];
                }

                $action_log = 'Update description of product '.$_POST['edit_name'].' from '.$old_desc.' to '.$new_desc;
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            }

            if ($product && $product['expiration'] !== $_POST['edit_expiration_date']) {
                $this->editExpirationDate($_POST['edit_expiration_date'], $id);

                if (empty($_POST['edit_expiration_date'])) {
                    $new_exp = 'NULL';
                } else {
                    $new_exp = $_POST['edit_expiration_date'];
                }

                if (empty($product['expiration'])) {
                    $old_exp = 'NULL';
                } else {
                    $old_exp = $product['expiration'];
                }

                if ($new_exp === 'NULL') {
                    $action_log = 'Update expiration date of product '.$_POST['edit_name'].' to '.$new_exp;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                } else if($old_exp === 'NULL') {
                    $action_log = 'Update expiration date of product '.$_POST['edit_name'].' from '.$old_exp.' to '.date('F j, Y', strtotime($new_exp));
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                } else {
                    $action_log = 'Update expiration date of product '.$_POST['edit_name'].' from '.date('F j, Y', strtotime($old_exp)).' to '.date('F j, Y', strtotime($new_exp));
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            if ($product && $product['brand'] !== $_POST['edit_brand']) {
                $old_brand = $this->getBrandName($product['brand']);
                $this->editBrand($_POST['edit_brand'], $id);
                $new_brand = $this->getBrandName($_POST['edit_brand']);

                if ($old_brand !== $new_brand) {
                    $action_log = 'Update brand of product '.$_POST['edit_name'].' from '.$old_brand.' to '.$new_brand;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            if ($product && $product['unit_value'] !== $_POST['edit_unit_value']) {
                $this->editUnitValue($_POST['edit_unit_value'], $id);

                $action_log = 'Update unit value of product'.$_POST['edit_name'].' from '.$product['unit_value'].' to '.$_POST['edit_unit_value'];
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            }

            if ($product && $product['unit'] !== $_POST['edit_unit']) {
                $old_unit = $this->getUnitName($product['unit']);
                $this->editUnit($_POST['edit_unit'], $id);
                $new_unit = $this->getUnitName($_POST['edit_unit']);

                if ($old_unit !== $new_unit) {
                    $action_log = 'Update unit of product '.$_POST['edit_name'].' from '.$old_unit.' to '.$new_unit;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            if ($product && $product['category'] !== $_POST['edit_category']) {
                $old_category = $this->getCategoryName($product['category']);
                $this->editCategory($_POST['edit_category'], $id);
                $new_category = $this->getCategoryName($_POST['edit_category']);

                if ($old_category !== $new_category) {
                    $action_log = 'Update category of product '.$_POST['edit_name'].' from '.$old_category.' to '.$new_category;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            if ($product && $product['variant'] !== $_POST['edit_variant']) {
                $old_variant = $this->getVariantName($product['variant']);
                $this->editVariant($_POST['edit_variant'], $id);
                $new_variant = $this->getVariantName($_POST['edit_variant']);

                if ($old_variant !== $new_variant) {
                    $action_log = 'Update category of product '.$_POST['edit_name'].' from '.$old_variant.' to '.$new_variant;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['base_price'] !== $_POST['edit_base_price']) {
                $this->editBasePrice($_POST['edit_base_price'], $id);
                $old_bp = '₱'.number_format($product['base_price'], 2);
                $new_bp = '₱'.number_format($_POST['edit_base_price'], 2);

                if ($old_bp !== $new_bp) {
                    $action_log = 'Update base price of product '.$_POST['edit_name'].' from '.$old_bp.' to '.$new_bp;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['selling_price'] !== $_POST['edit_selling_price']) {
                $this->editSellingPrice($_POST['edit_selling_price'], $id);
                $old_sp = '₱'.number_format($product['selling_price'], 2);
                $new_sp = '₱'.number_format($_POST['edit_selling_price'], 2);

                if ($old_sp !== $new_sp) {
                    $action_log = 'Update selling price of product '.$_POST['edit_name'].' from '.$old_sp.' to '.$new_sp;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['stock'] !== $_POST['edit_stock']) {
                $this->editStock($_POST['edit_stock'], $id);
                $old_stock = $product['stock'];
                $new_stock = $_POST['edit_stock'];

                if ($old_stock != $new_stock) {
                    $action_log = 'Update stock of product '.$_POST['edit_name'].' from '.$old_stock.' to '.$new_stock;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['critical_level'] !== $_POST['edit_critical_level']) {
                $this->editCriticalLevel($_POST['edit_critical_level'], $id);
                $old_cl = $product['critical_level'];
                $new_cl = $_POST['edit_critical_level'];

                if ($old_cl != $new_cl) {
                    $action_log = 'Update critical level of product '.$_POST['edit_name'].' from '.$old_cl.' to '.$new_cl;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['stockable'] !== $_POST['edit_stockable']) {
                $this->editStockable($_POST['edit_stockable'], $id);
                $old_stockable = $product['stockable'];
                $new_stockable = $_POST['edit_stockable'];

                if ($old_stockable != $new_stockable) {
                    if ($new_stockable == 0) {
                        $action_log = 'Update product '.$_POST['edit_name'].' as NONSTOCKABLE';
                    } else {
                        $action_log = 'Update product '.$_POST['edit_name'].' as STOCKABLE';
                    }
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['barcode'] !== $_POST['edit_barcode']) {
                $this->editBarcode($_POST['edit_barcode'], $id);
                $old_barcode = $product['barcode'];
                $new_barcode = $_POST['edit_barcode'];

                if ($old_barcode !== $new_barcode) {
                    $action_log = 'Update barcode of product '.$_POST['edit_name'].' from '.$old_barcode.' to '.$new_barcode;
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['pickup'] !== $_POST['edit_pickup']) {
                $this->editPickup($_POST['edit_pickup'], $id);
                $old_pickup = $product['pickup'];
                $new_pickup = $_POST['edit_pickup'];

                if ($old_pickup != $new_pickup) {
                    if ($new_pickup == 0) {
                        $action_log = 'Update product '.$_POST['edit_name'].' as FOR NON-PICKUP';
                    } else {
                        $action_log = 'Update product '.$_POST['edit_name'].' as FOR PICKUP';
                    }
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }
            
            if ($product && $product['delivery'] !== $_POST['edit_delivery']) {
                $this->editDelivery($_POST['edit_delivery'], $id);
                $old_delivery = $product['delivery'];
                $new_delivery = $_POST['edit_delivery'];

                if ($old_delivery != $new_delivery) {
                    if ($new_delivery== 0) {
                        $action_log = 'Update product '.$_POST['edit_name'].' as FOR NON-DELIVERY';
                    } else {
                        $action_log = 'Update product '.$_POST['edit_name'].' as FOR DELIVERY';
                    }
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
                }
            }

            $json = array(
                'redirect' => '/manage-products'
            );

            echo json_encode($json);
            return;
        }

        public function editImage ($image, $id) {
            $query = 'UPDATE product SET image = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $image, $id);

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

        public function editProductName ($name, $id) {
            $query = 'UPDATE product SET name = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $name, $id);

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

        public function editSupllier ($supplier, $id) {
            $query = 'UPDATE product SET supplier_id = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $supplier, $id);

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

        public function editItemCode ($code, $id) {
            $query = 'UPDATE product SET code = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $code, $id);

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

        public function editDescription ($description, $id) {
            $query = 'UPDATE product SET description = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $description, $id);

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
        
        public function editExpirationDate ($expiration, $id) {
            $query = 'UPDATE product SET expiration_date = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $expiration, $id);

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

        public function editBrand ($brand, $id) {
            $query = 'UPDATE product SET brand_id = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $brand, $id);

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

        public function editUnitValue ($unit_value, $id) {
            $query = 'UPDATE product SET unit_value = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $unit_value, $id);

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

        public function editUnit ($unit, $id) {
            $query = 'UPDATE product SET unit_id = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $unit, $id);

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

        public function editCategory ($category, $id) {
            $query = 'UPDATE product SET category_id = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $category, $id);

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

        public function editVariant ($variant, $id) {
            $query = 'UPDATE product SET variant_id = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $variant, $id);

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

        public function editBasePrice ($base_price, $id) {
            $query = 'UPDATE price_list SET base_price = ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('di', $base_price, $id);

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

        public function editSellingPrice ($selling_price, $id) {
            $query = 'UPDATE price_list SET unit_price = ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('di', $selling_price, $id);

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

        public function editStock ($stock, $id) {
            $query = 'UPDATE stock SET qty = ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $stock, $id);

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

        public function editCriticalLevel ($critical_level, $id) {
            $query = 'UPDATE stock SET critical_level = ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $critical_level, $id);

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

        public function editStockable ($stockable, $id) {
            $query = 'UPDATE stock SET stockable = ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $stockable, $id);

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

        public function editBarcode ($barcode, $id) {
            $query = 'UPDATE product SET barcode = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $barcode, $id);

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

        public function editPickup ($pickup, $id) {
            $query = 'UPDATE product SET pickup = ? WHERE id= ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $pickup, $id);

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

        public function editDelivery ($delivery, $id) {
            $query = 'UPDATE product SET delivery = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $delivery, $id);

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

        public function viewProduct ($id) {
            $query = 'SELECT product.image,
                            product.code,
                            product.name,
                            supplier.name,
                            product.description,
                            product.expiration_date,
                            category.name,
                            brand.name,
                            product.unit_value,
                            unit.name,
                            variant.name,
                            price_list.base_price,
                            price_list.unit_price,
                            stock.qty,
                            stock.critical_level,
                            product.barcode,
                            product.pickup,
                            product.delivery,
                            product.id
                    FROM product
                    INNER JOIN supplier ON supplier.id = product.supplier_id
                    INNER JOIN category ON category.id = product.category_id
                    INNER JOIN brand ON brand.id = product.brand_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    INNER JOIN stock ON stock.product_id = product.id
                    WHERE product.id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($image, $code, $name, $supplier, $description, $expiration_date, $category,
                                $brand, $unit_value, $unit, $variant, $base_price, $selling_price, $stock,
                                $critical_level, $barcode, $pickup, $delivery, $product_id);
            $stmt->fetch();
            $stmt->close();

            if ($pickup == 1) {
                $pickup_format = '<i class="fa-solid fa-square-check text-success fs-6"></i>';
            } else {
                $pickup_format = '<i class="fa-solid fa-square-xmark text-danger fs-6"></i>';
            }

            if ($delivery == 1) {
                $delivery_format = '<i class="fa-solid fa-square-check text-success fs-6"></i>';
            } else {
                $delivery_format = '<i class="fa-solid fa-square-xmark text-danger fs-6"></i>';
            }

            $json = array(
                'image' => $image,
                'name' => $name,
                'code' => $code,
                'supplier' => $supplier,
                'description' => $description,
                'expiration_date' => $expiration_date,
                'category' => $category,
                'brand' => strtoupper($brand),
                'unit' => $unit_value . ' ' . strtoupper($unit),
                'variant' => ucwords($variant),
                'base_price' => '₱'.number_format($base_price, 2),
                'selling_price' => '₱'.number_format($selling_price, 2),
                'stock' => $stock,
                'critical_level' => $critical_level,
                'barcode' => $barcode,
                'pickup' => $pickup_format,
                'delivery' => $delivery_format,
                'id' => $product_id
            );

            return $json;
        }

        public function getSupplier ($id) {
            $query= 'SELECT name FROM supplier WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($supplier);
            $stmt->fetch();
            $stmt->close();
            return $supplier;
        }

        public function getBrandName ($id) {
            $query = 'SELECT name FROM brand WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($brand);
            $stmt->fetch();
            $stmt->close();
            return $brand;
        }

        public function getUnitName ($id) {
            $query = 'SELECT name FROM unit WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($unit);
            $stmt->fetch();
            $stmt->close();
            return $unit;
        }

        public function getCategoryName ($id) {
            $query = 'SELECT name FROM category WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($category);
            $stmt->fetch();
            $stmt->close();
            return $category;
        }

        public function getVariantName ($id) {
            $query = 'SELECT name FROM variant WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($variant);
            $stmt->fetch();
            $stmt->close();
            return $variant;
        }
    }
?>