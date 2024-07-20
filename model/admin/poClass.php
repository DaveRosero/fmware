<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

    class PO extends Admin {
        public function showPO () {
            $query = 'SELECT p.po_ref, s.name, p.total, p.date, p.status, p.date_received
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

            $stmt->bind_result($po_ref, $supplier, $total, $date, $status, $received);
            $content = '';
            while ($stmt->fetch()) {
                if ($status == 3) {
                    continue;
                }

                switch ($status) {
                    case 0:
                        $po_status = '<span class="badge bg-primary text-wrap">DRAFT</span>';
                        $button = '<a
                                        class="btn btn-sm btn-secondary view me-1" 
                                        type="button"
                                        href="/create-po/'.$supplier.'/'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square fs-1"></i>
                                    </a>';
                        break;
                    case 1:
                        $po_status = '<span class="badge bg-warning text-wrap">PENDING</span>';
                        $button = '<a
                                        class="btn btn-sm btn-secondary" 
                                        type="button"
                                        href="/view-po/'.$supplier.'/'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-eye fs-1"></i>
                                    </a>
                                    <a
                                        class="btn btn-sm btn-success" 
                                        type="button"
                                        href="/receive-po/'.$supplier.'/'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-right-to-bracket fs-1"></i>
                                    </a>';
                        break;
                    case 2:
                        $po_status = '<span class="badge bg-success text-wrap">COMPLETED</span>';
                        $button = '<a
                                        class="btn btn-sm btn-secondary" 
                                        type="button"
                                        href="/view-po/'.$supplier.'/'.$po_ref.'"
                                    >
                                        <i class="fa-solid fa-eye fs-1"></i>
                                    </a>';
                        break;
                    default:
                        $po_status = 'Invalid PO Status';
                        break;
                }
                $content .= '<tr>
                                <td>'.$po_ref.'</td>
                                <td>'.$supplier.'</td>
                                <td>'.$date.'</td>
                                <td>'.$received.'</td>
                                <td>'.$po_status.'</td>
                                <td>'.$button.'</td>
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
            $logs = new Logs();
            
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
            
            $action_log = 'New Purchase Order #'.$po_ref;
            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

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
            $query = 'SELECT p.id, p.po_ref, s.name, p.total, p.user_id, p.date, p.status, p.remarks, p.shipping, p.others
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

            $stmt->bind_result($id, $po_ref, $supplier, $total, $user_id, $date, $status, $remarks, $shipping, $others);
            $stmt->fetch();
            $stmt->close();

            $received_total = $this->getPOReceivedTotal($po_ref);

            return [
                'id' => $id,
                'po_ref' => $po_ref,
                'supplier' => $supplier,
                'total' => $total,
                'user_id' => $user_id,
                'date' => $date,
                'status' => $status,
                'remarks' => $remarks,
                'shipping' => $shipping,
                'others' => $others,
                'received_total' => $received_total
            ];
        }
        
        public function getSupplierProducts ($id) {
            $query = 'SELECT product.id, product.name, unit.name, product.unit_value, variant.name, stock.qty, stock.critical_level, stock.stockable
                    FROM product
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN stock ON stock.product_id = product.id
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

            $stmt->bind_result($product_id, $name, $unit, $unit_value, $variant, $stock, $critical_level, $stockable);
            $content = '';
            while ($stmt->fetch()) {
                if ($stockable == 0) {
                    $content .= '<option value="'.$product_id.'">'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</option>';
                    continue;
                }

                if ($stock < $critical_level + 5) {
                    $content .= '<option value="'.$product_id.'">'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</option>';
                }
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
            $query = 'SELECT poi.id, product.id, product.name, unit.name, product.unit_value, variant.name, poi.qty, poi.price, poi.unit, stock.qty, stock.critical_level
                    FROM purchase_order_items poi
                    INNER JOIN product ON product.id = poi.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN stock ON stock.product_id = poi.product_id
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

            $stmt->bind_result($id, $product_id, $name, $unit, $unit_value, $variant, $qty, $price, $po_unit, $stock, $critical_level);
            $content = '';
            while ($stmt->fetch()) {
                $total = $qty * $price;
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
                                <td>
                                    <input class="form-control poi-qty" type="number" name="qty" min="1" value="'.$qty.'" data-product-id="'.$product_id.'" data-po-ref="'.$po_ref.'">
                                    <p class="fs-2 fst-italic text-muted text-center mb-0 mt-0">Recommended Min. Qty: '.(($critical_level - $stock) + 5).'</p>
                                    <p class="fs-2 fst-italic text-muted text-center" mb-0 mt-0">Stock: '.$stock.' Crtical Level: '.$critical_level.'</p>
                                </td>
                                <td><input class="form-control poi-unit" type="text" name="unit" value="'.$po_unit.'" data-product-id="'.$product_id.'" data-po-ref="'.$po_ref.'"></td>
                                <td>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">₱</span>
                                        <input class="form-control poi-price" type="number" name="price" step="any" min="0" value="'.$price.'" data-product-id="'.$product_id.'" data-po-ref="'.$po_ref.'">
                                    </div>
                                </td>
                                <td id="poi-total">₱'.number_format($total, 2).'</td>
                            </tr>';
            }
            $stmt->close();
            
            $grand_total = $this->getPOTotal($po_ref);
            $json = array(
                'content' => $content,
                'grand_total' => $grand_total
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

        public function updateQty ($po_ref, $product_id, $qty) {
            $query = 'UPDATE purchase_order_items SET qty = ? WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('isi', $qty, $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $total = $this->getItemTotal($po_ref, $product_id);
            $grand_total = $this->getPOTotal($po_ref);
            $json = array(
                'total' => $total,
                'grand_total' => $grand_total
            );
            echo json_encode($json);
            return;
        }

        public function updatePrice ($po_ref, $product_id, $price) {
            $query = 'UPDATE purchase_order_items SET price = ? WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('dsi', $price, $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $total = $this->getItemTotal($po_ref, $product_id);
            $order_total = $this->getPOTotal($po_ref);
            $json = array(
                'total' => $total,
                'order_total' => $order_total
            );
            echo json_encode($json);
            return;
        }

        public function getItemTotal ($po_ref, $product_id) {
            $query = 'SELECT qty, price FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($qty, $price);
            $stmt->fetch();
            $stmt->close();

            $total = $qty * $price;
            return number_format($total, 2);
        }

        public function getPOTotal ($po_ref) {
            $query = 'SELECT price, qty FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->bind_result($qty, $price);
            $grand_total = 0;
            while ($stmt->fetch()) {
                $grand_total += $qty * $price;
            }
            $stmt->close();

            $this->updatePOTotal($grand_total, $po_ref);
            return number_format($grand_total, 2);
        }

        public function updatePOTotal ($total, $po_ref) {
            $query = 'UPDATE purchase_order SET total = ? WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ds', $total, $po_ref);
            
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

        public function updateUnit ($po_ref, $product_id, $unit) {
            $query = 'UPDATE purchase_order_items SET unit = ? WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssi', $unit, $po_ref, $product_id);

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

        public function updateRemarks ($po_ref, $remarks) {
            $query = 'UPDATE purchase_order SET remarks = ? WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $remarks, $po_ref);

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
        
        public function savePO ($po_ref) {
            $query = 'UPDATE purchase_order SET status = 1 WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'redirect' => '/purchase-orders'
            );
            echo json_encode($json);
            return;
        }

        public function getPendingPOItems ($po_ref) {
            $query = 'SELECT poi.id, product.id, product.name, unit.name, product.unit_value, variant.name, poi.qty, poi.price, poi.unit, poi.received, po.shipping, po.others, poi.actual_price
                    FROM purchase_order_items poi
                    INNER JOIN product ON product.id = poi.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN purchase_order po ON po.po_ref = poi.po_ref
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

            $stmt->bind_result($id, $product_id, $name, $unit, $unit_value, $variant, $qty, $price, $po_unit, $received, $shipping, $others, $actual_price);
            $content = '';
            $count = 1;
            while ($stmt->fetch()) {
                $total = $qty * $price;
                $amount = $received * $actual_price;
                $content .= '<tr>
                                <td>'.$count.'</td>
                                <td>'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</td>
                                <td>'.$qty.'</td>
                                <td>'.$po_unit.'</td>
                                <td>₱'.number_format($price, 2).'</td>
                                <td id="poi-total">₱'.number_format($total, 2).'</td>
                                <td>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">₱</span>
                                        <input class="form-control poi-price" type="number" name="price" step="any" min="0" value="'.$actual_price.'" data-product-id="'.$product_id.'" data-po-ref="'.$po_ref.'">
                                    </div>
                                </td>
                                <td><input class="form-control poi-received" type="number" name="received" value="'.$received.'" data-product-id="'.$product_id.'" data-po-ref="'.$po_ref.'"></td>
                                <td>₱'.number_format($amount, 2).'</td>
                            </tr>';
                $count += 1;
            }

            $stmt->close();
            $order_total = $this->getPOTotal($po_ref);
            $received_total = $this->getPOReceivedTotal($po_ref);
            $grand_total = str_replace(',', '', $received_total) + $shipping + $others;
            $content .= '<tr>
                            <td class="text-end fw-semibold" colspan="5">Order Total: </td>
                            <td id="order_total">₱'.$order_total.'</td>
                            <td class="text-end fw-semibold" colspan="2">Received Total: </td>
                            <td id="received_total">₱'.$received_total.'</td>
                        </tr>';
            $json = array(
                'content' => $content,
                'order_total' => $order_total,
                'received_total' => $received_total,
                'grand_total' => number_format($grand_total, 2)
            );
            echo json_encode($json);
            return;
        }

        public function updateReceived ($po_ref, $product_id, $received) {
            if (!$this->checkReceived($po_ref, $product_id, $received)) {
                $json = array(
                    'invalid_received' => 'invalid_received'
                );
                echo json_encode($json);
                return;
            }

            $query = 'UPDATE purchase_order_items SET received = ? WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('isi', $received, $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->close();
            $amount = $this->getPOAmount($po_ref, $product_id);
            $received_total = $this->getPOReceivedTotal($po_ref);
            $shipping = $this->getShipping($po_ref);
            $others = $this->getOthers($po_ref);
            $grand_total = str_replace(',', '', $received_total) + $shipping + $others;
            $json = array(
                'amount' => $amount,
                'received_total' => $received_total,
                'grand_total' => number_format($grand_total, 2)
            );
            echo json_encode($json);
            return;
        }

        public function getPOAmount ($po_ref, $product_id) {
            $query = 'SELECT received, actual_price FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($received, $price);
            $stmt->fetch();
            $stmt->close();

            $amount = $received * $price;
            return number_format($amount, 2);
        }

        public function checkReceived ($po_ref, $product_id, $received) {
            $query = 'SELECT qty FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($qty);
            $stmt->fetch();
            $stmt->close();

            if ($received > $qty) {
                return false;
            } else {
                return true;
            }
        }

        public function getPOReceivedTotal ($po_ref) {
            $query = 'SELECT actual_price, received FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($price, $received);
            $received_total = 0;
            while ($stmt->fetch()) {
                $received_total += $price * $received;
            }

            $stmt->close();
            return number_format($received_total, 2);
        }

        public function viewPO ($po_ref) {
            $query = 'SELECT poi.id, product.id, product.name, unit.name, product.unit_value, variant.name, poi.qty, poi.price, poi.unit, poi.received
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

            $stmt->bind_result($id, $product_id, $name, $unit, $unit_value, $variant, $qty, $price, $po_unit, $received);
            $content = '';
            $count = 1;
            while ($stmt->fetch()) {
                $total = $price * $qty;

                if ($total == 0) {
                    $total = '-';
                } else {
                    $total = '₱'.number_format($total, 2);
                }

                if ($price == 0) {
                    $price = '-';
                } else {
                    $price = '₱'.number_format($price, 2);
                }

                $content .= '<tr>
                                <td>'.$count.'</td>
                                <td>'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</td>
                                <td>'.$qty.'</td>
                                <td>'.$po_unit.'</td>
                                <td>'.$price.'</td>
                                <td>'.$total.'</td>
                            </tr>';
                $count += 1;
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function viewPOTotal ($po_ref) {
            $query = 'SELECT price, qty FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->bind_result($qty, $price);
            $grand_total = 0;
            while ($stmt->fetch()) {
                $grand_total += $qty * $price;
            }
            $stmt->close();

            if ($grand_total == 0) {
                $grand_total = '-';
            } else {
                $grand_total = '₱'.number_format($grand_total, 2);
            }

            echo $grand_total;
            return;
        }

        public function updateShipping ($po_ref, $shipping) {
            $query = 'UPDATE purchase_order SET shipping = ? WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ds', $shipping, $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $received_total = $this->getPOReceivedTotal($po_ref);
            $received_total = str_replace(',', '', $received_total);
            $others = $this->getOthers($po_ref);
            $grand_total = $received_total + $shipping + $others;

            $json = array(
                'grand_total' => number_format($grand_total, 2)
            );
            echo json_encode($json);
            return;
        }

        public function getShipping ($po_ref) {
            $query = 'SELECT shipping FROM purchase_order WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($shipping);
            $stmt->fetch();
            $stmt->close();
            return $shipping;
        }

        public function getOthers ($po_ref) {
            $query = 'SELECT others FROM purchase_order WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($others);
            $stmt->fetch();
            $stmt->close();
            return $others;
        }

        public function updateOthers ($po_ref, $others) {
            $query = 'UPDATE purchase_order SET others = ? WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ds', $others, $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $received_total = $this->getPOReceivedTotal($po_ref);
            $received_total = str_replace(',', '', $received_total);
            $shipping = $this->getShipping($po_ref);
            $grand_total = $received_total + $shipping + $others;

            $json = array(
                'grand_total' => number_format($grand_total, 2)
            );
            echo json_encode($json);
            return;
        }

        public function completePO ($po_ref) {
            $logs = new Logs();
            $current_date = date('F j, Y');
            
            $query = 'UPDATE purchase_order SET status = 2, date_received = ? WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $current_date, $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $json = array(
                'redirect' => '/purchase-orders'
            );

            $products = $this->getReceivedArray($po_ref);
            foreach ($products as $product) {
                $this->addStock($product['qty'], $product['id']);
            }
            
            $action_log = 'Verified Purchase Order #'.$po_ref;
            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

            echo json_encode($json);
            return;
        }

        public function viewCompletePO ($po_ref) {
            $query = 'SELECT poi.id, product.id, product.name, unit.name, product.unit_value, variant.name, poi.qty, poi.price, poi.unit, poi.received, po.shipping, po.others, poi.actual_price
                    FROM purchase_order_items poi
                    INNER JOIN product ON product.id = poi.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN purchase_order po ON po.po_ref = poi.po_ref
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

            $stmt->bind_result($id, $product_id, $name, $unit, $unit_value, $variant, $qty, $price, $po_unit, $received, $shipping, $others, $actual_price);
            $content = '';
            $count = 1;
            while ($stmt->fetch()) {
                $total = $qty * $price;
                $amount = $received * $actual_price;
                $content .= '<tr>
                                <td class="text-center">'.$count.'</td>
                                <td class="text-start">'.$name.' ('.$variant.') '.$unit_value.' '.$unit.'</td>
                                <td class="text-center">'.$qty.'</td>
                                <td class="text-center">'.$po_unit.'</td>
                                <td class="text-center">₱'.number_format($price, 2).'</td>
                                <td class="text-center" id="poi-total">₱'.number_format($total, 2).'</td>
                                <td>₱'.number_format($actual_price, 2).'</td>
                                <td class="text-center">'.$received.'</td>
                                <td class="text-center">₱'.number_format($amount, 2).'</td>
                            </tr>';
                $count += 1;
            }

            $stmt->close();
            $order_total = $this->getPOTotal($po_ref);
            $received_total = $this->getPOReceivedTotal($po_ref);
            $content .= '<tr>
                            <td class="text-end fw-semibold" colspan="5">Order Total: </td>
                            <td class="text-center" id="order_total">₱'.$order_total.'</td>
                            <td class="text-end fw-semibold" colspan="2">Received Total: </td>
                            <td class="text-center" id="received_total">₱'.$received_total.'</td>
                        </tr>';
            echo $content;
            return;
        }

        public function addStock ($qty, $id) {

            $query = 'UPDATE stock SET qty = qty + ? WHERE product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $qty, $id);

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

        public function getReceivedArray ($po_ref) {
            $query = 'SELECT product_id, received FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($product_id, $received);
            $products = array();
            while ($stmt->fetch()) {
                $products [] = array(
                    'qty' => $received,
                    'id' => $product_id
                );
            }
            $stmt->close();
            return $products;
        }

        public function deletePO ($po_ref) {
            $logs = new Logs();
            
            $po_info = $this->getPOInfo($po_ref);
            if ($po_info['status'] == 0) {
                $status = 'DRAFT';
            } else if ($po_info['status'] == 1) {
                $status = 'PENDING';
            } else if ($po_info['status'] == 2) {
                $status = 'COMPLETED';
            }

            if ($po_info['status'] !== 2) {
                $this->deletePOItems($po_ref);
                $query = 'DELETE FROM purchase_order WHERE po_ref = ?';
                $action_log = 'Deleted Purhcase Order #'.$po_ref.' ('.$status.')';
            } else {
                $query = 'UPDATE purchase_order SET status = 3 WHERE po_ref = ?';
                $action_log = 'Archived Purhcase Order #'.$po_ref.' ('.$status.')';
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();

            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

            $json = array(
                'redirect' => '/purchase-orders'
            );
            echo json_encode($json);
            return;
        }

        public function deletePOItems ($po_ref) {
            $query = 'DELETE FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

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

        public function updateActualPrice ($po_ref, $product_id, $price) {
            $query = 'UPDATE purchase_order_items SET actual_price = ? WHERE product_id = ? AND po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('dis', $price, $product_id, $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();
            $total = $this->getActualTotal($po_ref, $product_id);
            $amount = $this->getPOAmount($po_ref, $product_id);
            $received_total = $this->getPOReceivedTotal($po_ref);
            $shipping = $this->getShipping($po_ref);
            $others = $this->getOthers($po_ref);
            $grand_total = str_replace(',', '', $received_total) + $shipping + $others;
            $order_total = $this->getActualPOTotal($po_ref);
            $json = array(
                'total' => $total,
                'grand_total' => number_format($grand_total, 2),
                'amount' => $amount,
                'received_total' => $received_total,
                'order_total' => $order_total
            );
            echo json_encode($json);
            return;
        }

        public function getActualTotal ($po_ref, $product_id) {
            $query = 'SELECT qty, actual_price FROM purchase_order_items WHERE po_ref = ? AND product_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $po_ref, $product_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($qty, $price);
            $stmt->fetch();
            $stmt->close();

            $total = $qty * $price;
            return number_format($total, 2);
        }

        public function getActualPOTotal ($po_ref) {
            $query = 'SELECT actual_price, qty FROM purchase_order_items WHERE po_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $po_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->bind_result($qty, $price);
            $grand_total = 0;
            while ($stmt->fetch()) {
                $grand_total += $qty * $price;
            }
            $stmt->close();

            $this->updatePOTotal($grand_total, $po_ref);
            return number_format($grand_total, 2);
        }
    }
?>