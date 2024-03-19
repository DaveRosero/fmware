<?php
    require_once 'model/admin/admin.php';

    class Brands extends Admin {
        public function isBrandExist ($brand) {
            $conn = $this->getConnection();
            $query = 'SELECT COUNT(*) FROM brand WHERE name = ?';
            $stmt = $conn->prepare($query);
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function newBrand() {
            $conn = $this->getConnection();
            $brand = $_POST['brand_name'];
            if ($this->isBrandExist($brand)) {
                $json = array('brand_feedback' => 'Brand already exist.');
                echo json_encode($json);
                return;
            }
            $active = 1;
            $query = 'INSERT INTO brand
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sii', $brand, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/brands');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getBrands () {
            $conn = $this->getConnection();

            $query = 'SELECT
                        brand.id, 
                        brand.name, 
                        brand.date,
                        brand.active,
                        user.firstname,
                        user.lastname 
                    FROM brand
                    INNER JOIN user ON brand.user_id = user.id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $date, $active, $fname, $lname);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-brand-id="'.$id.'" data-brand-status="'.$active.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-brand-id="'.$id.'" data-brand-status="'.$active.'">
                                        </div>';
                        }
                        $initial = substr($lname, 0, 1);
                        $author = $fname.' '.$initial.'.';
                        echo '<tr>
                                <td>'.$status.'</td>
                                <td>'.$name.'</td>
                                <td>'.$author.'</td>
                                <td>'.date('d F Y | h:i:s A', strtotime($date)).'</td>
                                <td>
                                    <button 
                                        class="btn text-success edit" 
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function disableBrand () {
            $conn = $this->getConnection();
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($status == 1) {
                $active = 0;
            } else {
                $active = 1;
            }
            $query = 'UPDATE brand SET active = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json['redirect'] = '/fmware/brands';
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function editBrand () {
            $conn = $this->getConnection();
            $id = $_POST['brand_id'];
            $brand = $_POST['brand_name'];
            if ($this->isBrandExist($brand)) {
                $json = array('edit_feedback' => 'Brand already exist.');
                echo json_encode($json);
                return;
            }
            $query = "UPDATE brand SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $brand, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/brands');
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