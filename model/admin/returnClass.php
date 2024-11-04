<?php
require_once 'session.php';
require_once 'model/admin/admin.php';

class Returns extends Admin
{
    public function getReturns()
    {
        $refund = $this->getRefunds();
        $replacements = $this->getReplacements();

        echo $refund . $replacements;
    }

    public function getRefunds()
    {
        $query = 'SELECT id, pos_ref, total_refund_value, refund_date, reason
                    FROM refunds';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $pos_ref, $value, $date, $reason);
        $content = '';
        $type = 'Refund';
        while ($stmt->fetch()) {
            if (str_contains($pos_ref, 'POS')) {
                $data_type = 'pos';
            } else {
                $data_type = 'fmo';
            }
            $content .= '<tr>
                            <td class="text-center">' . $pos_ref . '</td>
                            <td class="text-center">₱' . number_format($value, 2) . '</td>
                            <td class="text-center">' . date('F j, Y H:i A', strtotime($date)) . '</td>
                            <td class="text-center">' . $type . '</td>
                            <td class="text-center">' . $reason . '</td>
                            <td class="text-center"><button class="btn btn-sm btn-primary view-refund" data-refund-id="' . $id . '" data-type="' . $data_type . '" data-bs-toggle="modal" data-bs-target="#viewModal">View</button></td>
                        </tr>';
        }
        $stmt->close();
        return $content;
    }

    public function getReplacements()
    {
        $query = 'SELECT id, pos_ref, total_replace_value, replace_date, reason
                    FROM replacements';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $pos_ref, $value, $date, $reason);
        $content = '';
        $type = 'Replacement';
        while ($stmt->fetch()) {
            if (str_contains($pos_ref, 'POS')) {
                $data_type = 'pos';
            } else {
                $data_type = 'fmo';
            }
            $content .= '<tr>
                            <td class="text-center">' . $pos_ref . '</td>
                            <td class="text-center">₱' . number_format($value, 2) . '</td>
                            <td class="text-center">' . date('F j, Y H:i A', strtotime($date)) . '</td>
                            <td class="text-center">' . $type . '</td>
                            <td class="text-center">' . $reason . '</td>
                            <td class="text-center"><button class="btn btn-sm btn-primary view-replacement" data-replacement-id="' . $id . '" data-type="' . $data_type . '" data-bs-toggle="modal" data-bs-target="#viewModal">View</button></td>
                        </tr>';
        }
        $stmt->close();
        return $content;
    }

    public function viewRefund($refund_id, $type)
    {
        if ($type == 'pos') {
            $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.refund_qty, ri.item_condition, r.pos_ref, user.firstname, CONCAT(pos.firstname, " ", pos.lastname) AS customer
                    FROM refund_items ri
                    INNER JOIN product p ON p.id = ri.product_id
                    INNER JOIN variant v ON v.id = p.variant_id
                    INNER JOIN unit u ON u.id = p.unit_id
                    INNER JOIN refunds r ON r.id = ri.refund_id
                    LEFT JOIN logs l ON l.description LIKE CONCAT("%refund%", "%", r.pos_ref, "%")
                    INNER JOIN user ON user.id = l.user_id
                    LEFT JOIN pos ON pos.pos_ref = r.pos_ref
                    WHERE ri.refund_id = ?';
        } else {
            $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.refund_qty, ri.item_condition, r.pos_ref, user.firstname, CONCAT(orders.firstname, " ", orders.lastname) AS customer
                    FROM refund_items ri
                    INNER JOIN product p ON p.id = ri.product_id
                    INNER JOIN variant v ON v.id = p.variant_id
                    INNER JOIN unit u ON u.id = p.unit_id
                    INNER JOIN refunds r ON r.id = ri.refund_id
                    LEFT JOIN logs l ON l.description LIKE CONCAT("%refund%", "%", r.pos_ref, "%")
                    INNER JOIN user ON user.id = l.user_id
                    INNER JOIN orders ON orders.order_ref = r.pos_ref
                    WHERE ri.refund_id = ?';
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $refund_id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($image, $name, $variant, $unit, $unit_value, $refund_qty, $item_condition, $pos_ref, $staff, $customer);
        $content = '';
        while ($stmt->fetch()) {
            $content .= '<tr>
                            <td class="text-center"><img src="/asset/images/products/' . $image . '" alt="" srcset="" style="width: 50px;"></td>
                            <td class="text-center">' . $name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                            <td class="text-center">' . $refund_qty . '</td>
                            <td class="text-center">' . $item_condition . '</td>
                        </tr>';
        }
        $stmt->close();

        $json = array(
            'content' => $content,
            'title' => $pos_ref,
            'staff' => 'Processed by: ' . ucwords($staff),
            'customer' => 'Customer: ' . $customer
        );
        echo json_encode($json);
        return;
    }

    public function viewReplacement($replacement_id, $type)
    {
        if ($type == 'pos') {
            $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.replace_qty, ri.item_condition, r.pos_ref, user.firstname, CONCAT(pos.firstname, " ", pos.lastname) AS customer
                    FROM replacement_items ri
                    INNER JOIN product p ON p.id = ri.product_id
                    INNER JOIN variant v ON v.id = p.variant_id
                    INNER JOIN unit u ON u.id = p.unit_id
                    INNER JOIN replacements r ON r.id = ri.replacement_id
                    LEFT JOIN logs l ON l.description LIKE CONCAT("%replacement%", "%", r.pos_ref, "%")
                    INNER JOIN user ON user.id = l.user_id
                    LEFT JOIN pos ON pos.pos_ref = r.pos_ref
                    WHERE ri.replacement_id = ?';
        } else {
            $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.replace_qty, ri.item_condition, r.pos_ref, user.firstname, CONCAT(orders.firstname, " ", orders.lastname) AS customer
                    FROM replacement_items ri
                    INNER JOIN product p ON p.id = ri.product_id
                    INNER JOIN variant v ON v.id = p.variant_id
                    INNER JOIN unit u ON u.id = p.unit_id
                    INNER JOIN replacements r ON r.id = ri.replacement_id
                    LEFT JOIN logs l ON l.description LIKE CONCAT("%replacement%", "%", r.pos_ref, "%")
                    INNER JOIN user ON user.id = l.user_id
                    INNER JOIN orders ON orders.order_ref = r.pos_ref
                    WHERE ri.replacement_id = ?';
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $replacement_id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($image, $name, $variant, $unit, $unit_value, $replacement_qty, $item_condition, $pos_ref, $staff, $customer);
        $content = '';
        while ($stmt->fetch()) {
            $content .= '<tr>
                            <td class="text-center"><img src="/asset/images/products/' . $image . '" alt="" srcset="" style="width: 50px;"></td>
                            <td class="text-center">' . $name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                            <td class="text-center">' . $replacement_qty . '</td>
                            <td class="text-center">' . $item_condition . '</td>
                        </tr>';
        }
        $stmt->close();

        $json = array(
            'content' => $content,
            'title' => $pos_ref,
            'staff' => 'Processed by: ' . ucwords($staff),
            'customer' => 'Customer: ' . $customer,
            'test' => $replacement_id . $type
        );
        echo json_encode($json);
        return;
    }
}
