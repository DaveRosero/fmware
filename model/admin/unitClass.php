<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

    class Unit extends Admin {
        public function isUnitExist ($unit) {
            $query = 'SELECT COUNT(*) FROM unit WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $unit);
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

        public function newUnit() {
            $logs = new Logs();

            $unit = ucwords($_POST['unit_name']);
            if ($this->isUnitExist($unit)) {
                $json = array('unit_feedback' => 'Unit already exist.');
                echo json_encode($json);
                return;
            }
            $active = 1;
            $query = 'INSERT INTO unit
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $unit, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Added new unit '.$unit;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json = array('redirect' => '/unit');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getUnits () {
            $query = 'SELECT unit.id, 
                        unit.name, 
                        unit.date,
                        unit.active,
                        COALESCE(product_counts.product_count, 0) AS product_count
                    FROM unit
                    LEFT JOIN (
                        SELECT unit_id, COUNT(*) AS product_count
                        FROM product
                        WHERE active = 1
                        GROUP BY unit_id
                    ) AS product_counts ON unit.id = product_counts.unit_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $date, $active, $product_count);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-unit-id="'.$id.'" data-unit-status="'.$active.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-unit-id="'.$id.'" data-unit-status="'.$active.'">
                                        </div>';
                        }

                        echo '<tr>
                                <td>'.$status.'</td>
                                <td>'.$name.'</td>
                                <td>'.$product_count.'</td>
                                <td>'.date('F j, Y', strtotime($date)).'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-unit-id="'.$id.'" 
                                        data-unit-name="'.$name.'"
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

        public function editUnit () {
            $logs = new Logs();

            $id = $_POST['unit_id'];
            $unit = ucwords($_POST['unit_name']);
            $old_unit = $this->getUnitName($id);

            if ($this->isUnitExist($unit)) {
                $json = array('edit_feedback' => 'Unit already exist.');
                echo json_encode($json);
                return;
            }
            
            $query = "UPDATE unit SET name = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $unit, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    $action_log = 'Update '.$old_unit.' to '.$unit;
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json = array('redirect' => '/unit');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function disableUnit () {
            $logs = new Logs();

            $id = $_POST['id'];
            $status = $_POST['status'];
            $unit = $this->getUnitName($id);

            if ($status == 1) {
                $active = 0;
                $action_log = 'Disable unit '.$unit;
            } else {
                $active = 1;
                $action_log = 'Enable unit '.$unit;
            }
            
            $query = 'UPDATE unit SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();

                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    $json['redirect'] = '/unit';
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
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
    }
?>