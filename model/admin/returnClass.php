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
            $content .= '<tr>
                            <td class="text-center">' . $pos_ref . '</td>
                            <td class="text-center">₱' . number_format($value, 2) . '</td>
                            <td class="text-center">' . date('F j, Y H:i A', strtotime($date)) . '</td>
                            <td class="text-center">' . $type . '</td>
                            <td class="text-center">' . $reason . '</td>
                            <td class="text-center"><button class="btn btn-sm btn-primary view-refund" data-refund-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="bi bi-eye-fill"></i></button></td>
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
            $content .= '<tr>
                            <td class="text-center">' . $pos_ref . '</td>
                            <td class="text-center">₱' . number_format($value, 2) . '</td>
                            <td class="text-center">' . date('F j, Y H:i A', strtotime($date)) . '</td>
                            <td class="text-center">' . $type . '</td>
                            <td class="text-center">' . $reason . '</td>
                            <td class="text-center"><button class="btn btn-sm btn-primary view-replacement" data-replacement-id="' . $id . '" data-bs-toggle="modal" data-bs-target="#viewModal"><i class="bi bi-eye-fill"></i></button></td>
                        </tr>';
        }
        $stmt->close();
        return $content;
    }

    public function viewRefund($refund_id)
    {
        $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.refund_qty, ri.item_condition, r.pos_ref
                FROM refund_items ri
                INNER JOIN product p ON p.id = ri.product_id
                INNER JOIN variant v ON v.id = p.variant_id
                INNER JOIN unit u ON u.id = p.unit_id
                INNER JOIN refunds r ON r.id = ri.refund_id
                WHERE ri.refund_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $refund_id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($image, $name, $variant, $unit, $unit_value, $refund_qty, $item_condition, $pos_ref);
        $content = '';
        while ($stmt->fetch()) {
            $content .= '<tr>
                            <td class="text-center"><img src="/asset/images/products/' . $image . '" alt="" srcset="" style="width: 50px;"></td>
                            <td class="text-center">' . $name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                            <td class="text-center">' . $refund_qty . '</td>
                            <td class="text-center">' . $item_condition . '</td>
                        </tr>';
        }

        $json = array(
            'content' => $content,
            'title' => $pos_ref
        );
        echo json_encode($json);
        return;
    }

    public function viewReplacement($replacement_id)
    {
        $query = 'SELECT p.image, p.name, v.name, u.name, p.unit_value, ri.replace_qty, ri.item_condition, r.pos_ref
                FROM replacement_items ri
                INNER JOIN product p ON p.id = ri.product_id
                INNER JOIN variant v ON v.id = p.variant_id
                INNER JOIN unit u ON u.id = p.unit_id
                INNER JOIN replacements r ON r.id = ri.replacement_id
                WHERE ri.replacement_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $replacement_id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($image, $name, $variant, $unit, $unit_value, $replacement_qty, $item_condition, $pos_ref);
        $content = '';
        while ($stmt->fetch()) {
            $content .= '<tr>
                            <td class="text-center"><img src="/asset/images/products/' . $image . '" alt="" srcset="" style="width: 50px;"></td>
                            <td class="text-center">' . $name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                            <td class="text-center">' . $replacement_qty . '</td>
                            <td class="text-center">' . $item_condition . '</td>
                        </tr>';
        }

        $json = array(
            'content' => $content,
            'title' => $pos_ref
        );
        echo json_encode($json);
        return;
    }
}
