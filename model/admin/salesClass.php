<?php
    require_once 'session.php';
    require_once 'model/admin/admin.php';

    class Sales extends Admin {
        public function showSales () {
            $query = 'SELECT pos.pos_ref, pos.firstname, pos.lastname, pos.date, pos.subtotal, pos.total, 
                        pos.discount, t.name, p.name
                    FROM pos
                    INNER JOIN transaction_type t ON t.id = pos.transaction_type_id
                    INNER JOIN payment_type p ON p.id = pos.payment_type_id';
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($pos_ref, $fname, $lname, $date, $subtotal, $total, $discount, $transaction, $payment);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<tr>
                                <td class="text-center fw-semibold">'.strtoupper($pos_ref).'</td>
                                <td class="text-center text-primary fw-semibold">'.ucfirst($fname).' '.ucfirst($lname).'</td>
                                <td class="text-center">'.date('F j, Y', strtotime($date)).'</td>
                                <td class="text-center">₱'.number_format($subtotal, 2).'</td>
                                <td class="text-center">₱'.number_format($total, 2).'</td>
                                <td class="text-center">₱'.number_format($discount, 2).'</td>
                                <td class="text-center">'.strtoupper($transaction).'</td>
                                <td class="text-center">'.strtoupper($payment).'</td>
                                <td>
                                    <button class="btn btn-sm btn-primary viewPOS" 
                                        data-pos-ref="'.$pos_ref.'"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function viewSales ($pos_ref) {
            $query = 'SELECT p.name, p.image, u.name, p.unit_value, v.name, pi.qty, pi.total, user.firstname
                    FROM pos_items pi
                    INNER JOIN product p ON p.id = pi.product_id
                    INNER JOIN unit u ON u.id = p.unit_id
                    INNER JOIN variant v ON v.id = p.variant_id
                    INNER JOIN pos ON pos.pos_ref = pi.pos_ref
                    INNER JOIN user ON user.id = pos.user_id
                    WHERE pi.pos_ref = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $pos_ref);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($name, $image, $unit, $unit_value, $variant, $qty, $total, $staff);
            $content = '';
            while($stmt->fetch()) {
                $selling_price = $total / $qty;
                $content .= '<tr>
                                <td class="text-center"><img src="/asset/images/products/'.$image.'" alt="" srcset="" style="width: 50px;"></td>
                                <td class="text-center">'.$name.' ('.$variant.') '.$unit_value.' '.strtoupper($unit).'</td>
                                <td class="text-center">₱'.number_format($selling_price, 2).'</td>
                                <td class="text-center">'.$qty.'</td>
                                <td class="text-center">₱'.number_format($total, 2).'</td>
                            </tr>';
            }
            $stmt->close();

            $json = array(
                'content' => $content,
                'pos_ref' => $pos_ref,
                'staff' => 'Prepared by: '.ucwords($staff)
            );
            echo json_encode($json);
            return;
        }
    }
?>