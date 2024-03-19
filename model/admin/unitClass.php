<?php
    require_once 'model/admin/admin.php';

    class Unit extends Admin {
        public function isUnitExist ($unit) {
            $conn = $this->getConnection();
            $query = 'SELECT COUNT(*) FROM unit WHERE name = ?';
            $stmt = $conn->prepare($query);
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function newUnit() {
            $conn = $this->getConnection();
            $unit = $_POST['unit_name'];
            if ($this->isUnitExist($unit)) {
                $json = array('unit_feedback' => 'Unit already exist.');
                echo json_encode($json);
                return;
            }
            $active = 1;
            $query = 'INSERT INTO unit
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sii', $unit, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/unit');
                    echo json_encode($json);
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

            $query = 'SELECT
                        unit.id, 
                        unit.name, 
                        unit.date,
                        unit.active,
                        user.firstname,
                        user.lastname 
                    FROM unit
                    INNER JOIN user ON unit.user_id = user.id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $date, $active, $fname, $lname);
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
                        $initial = substr($lname, 0, 1);
                        $author = $fname.' '.$initial.'.';
                        echo '<tr>
                                <td>'.$status.'</td>
                                <td>'.$name.'</td>
                                <td>'.$author.'</td>
                                <td>'.date('d F Y | h:i A', strtotime($date)).'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-unit-id="'.$id.'" 
                                        data-unit-name="'.$name.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger delete" 
                                        type="button" 
                                        data-unit-id="'.$id.'" 
                                        data-unit-name="'.$name.'"
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
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function editUnit () {
            $conn = $this->getConnection();
            $id = $_POST['unit_id'];
            $unit = $_POST['unit_name'];
            if ($this->isUnitExist($unit)) {
                $json = array('edit_feedback' => 'Unit already exist.');
                echo json_encode($json);
                return;
            }
            $query = "UPDATE unit SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $unit, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/unit');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function disableUnit () {
            $conn = $this->getConnection();
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($status == 1) {
                $active = 0;
            } else {
                $active = 1;
            }
            $query = 'UPDATE unit SET active = ? WHERE id = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json['redirect'] = '/fmware/unit';
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