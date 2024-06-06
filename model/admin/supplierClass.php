<?php
    require_once 'session.php';
    require_once 'model/admin/admin.php';

    class Supplier extends Admin {
        public function adddSupplier ($supplier, $email, $phone, $address) {
            $active = 1;
            $supplier = strtoupper($supplier);
            $query = 'INSERT INTO supplier
                        (name, email, phone, address, active)
                    VALUES (?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssi', $supplier, $email, $phone, $address, $active);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array('redirect' => '/manage-suppliers');
            echo json_encode($json);
            return;
        }

        public function showSupplier () {
            $query = 'SELECT id, name, email, phone, address, active
                    FROM supplier';
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $supplier, $email, $phone, $address, $active);
            $content = '';
            while ($stmt->fetch()) {
                if ($active == 1) {
                    $status = '<div class="form-check form-switch">
                                <input class="form-check-input status" 
                                    type="checkbox" 
                                    id="toggleSwitch"
                                    data-supplier-id='.$id.'
                                    checked
                                >
                            </div>';
                } else {
                    $status = '<div class="form-check form-switch">
                                <input class="form-check-input status" 
                                    type="checkbox" 
                                    id="toggleSwitch"
                                    data-supplier-id='.$id.'
                                >
                            </div>';
                }

                $content .= '<tr>
                                <td>'.$status.'</td>
                                <td>'.$supplier.'</td>
                                <td>'.$email.'</td>
                                <td>'.$phone.'</td>
                                <td>'.$address.'</td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }
    }
?>