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
            $status = 0;
            $this->createPO($po_ref, $id, $_SESSION['user_id'], $total, $date, $status);

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

        public function createPO ($po_ref, $supplier_id, $user_id, $total, $date, $status) {
            $query = 'INSERT INTO purchase_order
                        (po_ref, supplier_id, user_id, total, date, status)
                    VALUES (?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('siidsi', $po_ref, $supplier_id, $user_id, $total, $date, $status);

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

        public function addPOItem ($po_ref, $product_id) {
            if ($this->isAdded($po_ref, $product_id)) {
                $json = array(
                    'failed' => 'failed'
                );
                echo json_encode($json);
                return;
            }
            
            $query = 'INSERT INTO purchase_order_items
                        (po_ref, product_id, qty)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $qty = 1;
            $stmt->bind_param('sii', $po_ref, $product_id, $qty);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'success' => 'success'
            );
            echo json_encode($json);
            return;
        }

        public function isAdded ($po_ref, $product_id) {
            $query = 'SELECT COUNT(*) FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                return true;
            } else { 
                return false;
            }
        }

        public function getPOItem ($po_ref) {
            $query = 'SELECT poi.id, product.id, product.name, unit.name, product.unit_value, variant.name, poi.qty
                    FROM purchase_order_items poi
                    INNER JOIN product ON product.id = poi.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    WHERE poi.po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($id, $product_id, $name, $unit, $unit_value, $variant, $qty);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<tr>
                                <td>
                                    <button
                                        class="btn btn-sm btn-danger del me-1" 
                                        type="button"
                                        data-product-id="'.$product_id.'"
                                        data-po-ref="'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-xmark fa-solid fa-lg"></i>
                                    </button></td>
                                <td>'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</td>
                                <td class="w-25"><input class="form-control" type="number" name="qty" value="'.$qty.'"></td>
                                <td class="w-25"><input class="form-control" type="number" name="price"></td>
                                <td class="w-25"><input class="form-control" type="text" name="unit"></td>
                            </tr>';
            }
            $stmt->close();

            $json = array(
                'content' => $content
            );
            echo json_encode($json);
            return;
        }

        public function delPOItem ($po_ref, $product_id) {
            $query = 'DELETE FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'success' => 'success'
            );
            echo json_encode($json);
            return;
        }
    }
?>