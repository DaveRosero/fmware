<?php
    require_once 'session.php';
    require_once 'model/admin/admin.php';

    class Supplier extends Admin {
        public function adddSupplier ($supplier, $email, $contact, $phone, $address) {
            $active = 1;
            $date = date('F j, Y');
            $supplier = strtoupper($supplier);
            $query = 'INSERT INTO supplier
                        (name, email, phone, contact_person, address, date, active)
                    VALUES (?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssi', $supplier, $email, $phone, $contact, $address, $date, $active);

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
            $query = 'SELECT id, name, email, contact_person, phone, address, date, active FROM supplier';
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $supplier, $email, $contact, $phone, $address, $date, $active);
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
                                <td>'.ucfirst($contact).'</td>
                                <td>'.$phone.'</td>
                                <td>'.$address.'</td>
                                <td>'.$date.'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSupplier"
                                        data-supplier-id='.$id.'
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function getSupplier ($id) {
            $query = 'SELECT id, name, email, contact_person, phone, address FROM supplier WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $supplier, $email, $contact, $phone, $address);
            $stmt->fetch();
            $stmt->close();
            $json = array(
                'id' => $id,
                'supplier' => $supplier,
                'email' => $email,
                'contact' => $contact,
                'phone' => $phone,
                'address' => $address
            );
            echo json_encode($json);
            return;
        }

        public function editSupplier ($supplier, $email, $contact, $phone, $address, $id) {
            $query = 'UPDATE supplier SET name = ?, email = ?, contact_person = ?, phone = ?, address = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $supplier = strtoupper($supplier);
            $stmt->bind_param('sssssi', $supplier, $email, $contact, $phone, $address, $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'redirect' => '/manage-suppliers'
            );
            echo json_encode($json);
            return;
        }

        public function updateSupplierStatus ($active, $id) {
            $query = 'UPDATE supplier SET active = ? WHERE id = ?';
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
                'redirect' => '/manage-suppliers'
            );
            echo json_encode($json);
            return;
        }
    }
?>