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
                                        href="/create-po/'.$supplier.'/'.$po_ref.'"
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

            $po_ref = $this->poRef();
            $total = null;
            $date = date('F j, Y');
            $this->createPO($po_ref, $id, $_SESSION['user_id'], $total, $date);

            $json = array(
                'redirect' => '/create-po/'.$supplier.'/'.$po_ref
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

        public function createPO ($po_ref, $supplier_id, $user_id, $total, $date) {
            $query = 'INSERT INTO purchase_order
                        (po_ref, supplier_id, user_id, total, date)
                    VALUES (?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('siids', $po_ref, $supplier_id, $user_id, $total, $date);

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

        public function poRef () {
            $bytes = random_bytes(10);
            $hex = bin2hex($bytes);
            $prefix = 'FMPO_';
            return $prefix.strtoupper($hex);
        }

        public function getPOInfo ($po_ref) {
            $query = 'SELECT p.id, p.po_ref, s.name, p.total, p.user_id, p.date
                    FROM purchase_order p
                    INNER JOIN supplier s ON s.id = p.supplier_id
                    WHERE p.po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $po_ref, $supplier, $total, $user_id, $date);
            $stmt->fetch();
            $stmt->close();
            return [
                'id' => $id,
                'po_ref' => $po_ref,
                'supplier' => $supplier,
                'total' => $total,
                'user_id' => $user_id,
                'date' => $date
            ];
        }
        
        public function getSupplierProducts ($id) {
            $query = 'SELECT product.id, product.name, unit.name, product.unit_value, variant.name
                    FROM product
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    WHERE product.supplier_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($product_id, $name, $unit, $unit_value, $variant);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<option value="'.$product_id.'">'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</option>';
            }
            $stmt->close();
            echo $content;
            return;
        }
    }
?>