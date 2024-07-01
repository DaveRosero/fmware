<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';

    class PO extends Admin {
        public function showPO () {
            $query = 'SELECT p.po_ref, s.name, p.total, p.date
                    FROM purchase_order p
                    INNER JOIN supplier s ON s.id = p.supplier_id';
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($po_ref, $supplier, $total, $date);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<tr>
                                <td>'.$po_ref.'</td>
                                <td>'.$supplier.'</td>
                                <td>₱'.number_format($total, 2).'</td>
                                <td>'.$date.'</td>
                                <td>
                                    <a
                                        class="btn btn-sm btn-secondary view me-1" 
                                        type="button"
                                        href="/purchase-orders/'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-eye fs-1"></i>
                                    </a>
                                </td>
                            </tr>';
            }
            $stmt->close();

            echo $content;
            return;
        }

        public function supplierOptions () {
            $query = 'SELECT id, name FROM supplier WHERE active = 1';
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $supplier);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<option value="'.$id.'">'.$supplier.'</option>';
            }
            $stmt->close();

            echo $content;
            return;
        }

        public function redirect ($id) {
            $query = 'SELECT name FROM supplier WHERE id = ?';
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

            $json = array(
                'redirect' => '/create-po/'.$supplier
            );

            echo json_encode($json);
        }

        public function getSupplierInfo ($supplier) {
            $query = 'SELECT id, name, email, contact_person, phone, address FROM supplier WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $supplier);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $name, $email, $contact_person, $phone, $address);
            $stmt->fetch();
            $stmt->close();
            return [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'contact_person' => $contact_person,
                'phone' => $phone,
                'address' => $address
            ];
        }
    }
?>