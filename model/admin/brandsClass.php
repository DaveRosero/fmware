<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

    class Brands extends Admin {
        public function isBrandExist ($brand) {
            $query = 'SELECT COUNT(*) FROM brand WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $brand);
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

        public function newBrand() {
            $logs = new Logs();

            $brand = strtoupper($_POST['brand_name']);
            if ($this->isBrandExist($brand)) {
                $json = array('brand_feedback' => 'Brand already exist.');
                echo json_encode($json);
                return;
            }
            $active = 1;
            $query = 'INSERT INTO brand
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $brand, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();

                    $action_log = 'Added new brand '.$brand;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json = array('redirect' => '/brands');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getBrands () {
            $query = 'SELECT brand.id, 
                        brand.name, 
                        brand.date,
                        brand.active,
                        COALESCE(product_counts.product_count, 0) AS product_count
                    FROM brand
                    LEFT JOIN (
                        SELECT brand_id, COUNT(*) AS product_count
                        FROM product
                        WHERE active = 1
                        GROUP BY brand_id
                    ) AS product_counts ON brand.id = product_counts.brand_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $date, $active, $product_count);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-brand-id="'.$id.'" data-brand-status="'.$active.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-brand-id="'.$id.'" data-brand-status="'.$active.'">
                                        </div>';
                        }

                        echo '<tr>
                                <td class="text-center">'.$status.'</td>
                                <td class="text-center">'.strtoupper($name).'</td>
                                <td class="text-center">'.$product_count.'</td>
                                <td class="text-center">'.date('F j, Y', strtotime($date)).'</td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-brand-id="'.$id.'" 
                                        data-brand-name="'.$name.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
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

        public function disableBrand () {
            $logs = new Logs();

            $id = $_POST['id'];
            $status = $_POST['status'];
            $brand = $this->getBrandName($id);

            if ($status == 1) {
                $active = 0;
                $action_log = 'Disable brand '.$brand;
            } else {
                $active = 1;
                $action_log = 'Enable brand '.$brand;
            }
            $query = 'UPDATE brand SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json['redirect'] = '/brands';
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function editBrand () {
            $logs = new Logs();

            $id = $_POST['brand_id'];
            $brand = strtoupper($_POST['brand_name']);
            $old_brand = $this->getBrandName($id);
            if ($this->isBrandExist($brand)) {
                $json = array('edit_feedback' => 'Brand already exist.');
                echo json_encode($json);
                return;
            }
            $query = "UPDATE brand SET name = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $brand, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Update '.$old_brand.' to '.$brand;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json = array('redirect' => '/brands');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
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
    }
?>