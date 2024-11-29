<?php

use function PHPSTORM_META\map;

include_once 'session.php';
require_once 'model/admin/admin.php';

class Reports extends Admin
{
    public function createReport($module, $start_date, $end_date)
    {
        if ($module === 'orders') {
            $content = $this->orders($start_date, $end_date);
        } else if ($module === 'pos') {
            $content = $this->sales($start_date, $end_date);
        } else if ($module === 'products') {
            $content = $this->products($start_date, $end_date);
        }

        $json = array(
            'thead' => $content['thead'],
            'tbody' => $content['tbody']
        );
        echo json_encode($json);
        return;
    }

    public function order_refunds($start_date, $end_date)
    {
        $query = 'SELECT r.pos_ref, 
                        r.total_refund_value,
                        r.refund_date,
                        o.firstname,
                        o.lastname,
                        o.status
                FROM refunds r
                INNER JOIN orders o ON o.order_ref = r.pos_ref
                WHERE DATE(refund_date) BETWEEN ? AND ?
                AND r.pos_ref LIKE "FMO%"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($ref, $amount, $date, $firstname, $lastname, $status);
        $tbody = '';
        $total = 0;
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                <td class="text-center">' . strtoupper($ref) . '</td>
                <td class="text-center">' . $firstname . ' ' . $lastname . '</td>
                <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                <td class="text-center">' . strtoupper($status) . '</td>
                <td class="text-center">REFUND</td>
                <td class="text-center">N/A</td>
                <td class="text-end">₱0.00</td>
                <td class="text-end text-danger">-₱' . number_format($amount, 2) . '</td>
                <td class="text-end">₱0.00</td>
            </tr>';
            $total += $amount;
        }
        $stmt->close();

        return [
            'tbody' => $tbody,
            'total' => $total
        ];
    }

    public function orders($start_date, $end_date)
    {
        $refunds = $this->order_refunds($start_date, $end_date);
        $tbody = $refunds['tbody'];
        $total_refunds = $refunds['total'];

        $query = 'SELECT o.order_ref, o.firstname, o.lastname, o.date, o.gross, o.discount, 
                     t.name, p.name, o.status
              FROM orders o
              INNER JOIN transaction_type t ON t.id = o.transaction_type_id
              INNER JOIN payment_type p ON p.id = o.payment_type_id
              WHERE DATE(o.date) BETWEEN ? AND ?
              AND paid = "paid"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($order_ref, $fname, $lname, $date, $gross, $discount, $transaction, $payment, $status);

        $total_sales = 0;
        $total_discounts = 0;
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                    <td class="text-center">' . strtoupper($order_ref) . '</td>
                    <td class="text-center">' . ucfirst($fname) . ' ' . ucfirst($lname) . '</td>
                    <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                    <td class="text-center">' . strtoupper($transaction) . '</td>
                    <td class="text-center">' . strtoupper($payment) . '</td>
                    <td class="text-center">' . strtoupper($status) . '</td>
                    <td class="text-end">₱' . number_format($discount, 2) . '</td>
                    <td class="text-end">₱' . number_format($gross, 2) . '</td>
                    <td class="text-end">₱' . number_format($gross - $discount, 2) . '</td>
                </tr>';
            $total_sales += $gross;
            $total_discounts += $discount;
        }
        $stmt->close();

        // Calculate net sales
        $net_sales = $total_sales - $total_discounts - $total_refunds;

        // Summary rows for total sales, discounts, refunds, and net sales
        $tbody .= '<tr>
                <td colspan="8" class="text-end font-weight-bold">Total Sales:</td>
                <td class="text-end">₱' . number_format($total_sales, 2) . '</td>
            </tr>';
        $tbody .= '<tr>
                <td colspan="8" class="text-end font-weight-bold">Total Discounts:</td>
                <td class="text-end text-danger">-₱' . number_format($total_discounts, 2) . '</td>
            </tr>';
        $tbody .= '<tr>
                <td colspan="8" class="text-end font-weight-bold">Total Refunds:</td>
                <td class="text-end text-danger">-₱' . number_format($total_refunds, 2) . '</td>
            </tr>';
        // Determine class based on net sales value
        $net_sales_class = $net_sales < 0 ? 'text-danger' : 'text-success';

        $tbody .= '<tr>
                <td colspan="8" class="text-end font-weight-bold">Net Sales:</td>
                <td class="text-end font-weight-bold ' . $net_sales_class . '">₱' . number_format($net_sales, 2) . '</td>
            </tr>';

        // Define table header with the updated column layout
        $thead = '<th class="text-center">Order Ref</th>
              <th class="text-center">Customer</th>
              <th class="text-center">Date</th>
              <th class="text-center">Transaction</th>
              <th class="text-center">Payment</th>
              <th class="text-center">Status</th>
              <th class="text-end">Discount</th>
              <th class="text-end">Subtotal</th>
              <th class="text-end">Total</th>';

        // Manager signature row
        $signature = '<tr>
                    <td colspan="9" class="text-center" style="padding-top: 50px;">
                        <p class="mb-0">__________________________</p>
                        <p class="mb-0 mt-0"><strong>Angelica Odulio</strong></p>
                        <p class="mt-0"><strong>Manager</strong></p>
                    </td>
                  </tr>';

        return [
            'thead' => $thead,
            'tbody' => $tbody . $signature
        ];
    }

    public function sales_refunds($start_date, $end_date)
    {
        $query = 'SELECT r.pos_ref, 
                        r.total_refund_value,
                        r.refund_date,
                        p.firstname,
                        p.lastname
                FROM refunds r
                INNER JOIN pos p ON p.pos_ref = r.pos_ref
                WHERE DATE(refund_date) BETWEEN ? AND ?
                AND r.pos_ref LIKE "POS%"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($ref, $amount, $date, $firstname, $lastname);
        $tbody = '';
        $total = 0;
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                <td class="text-center">' . strtoupper($ref) . '</td>
                <td class="text-center">' . $firstname . ' ' . $lastname . '</td>
                <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                <td class="text-center">REFUND</td>
                <td class="text-center">N/A</td>
                <td class="text-end">₱0.00</td>
                <td class="text-end">₱0.00</td>
                <td class="text-end text-danger">-₱' . number_format($amount, 2) . '</td>
            </tr>';
            $total += $amount;
        }
        $stmt->close();

        return [
            'tbody' => $tbody,
            'total' => $total
        ];
    }

    public function sales($start_date, $end_date)
    {
        $refunds = $this->sales_refunds($start_date, $end_date);
        $tbody = $refunds['tbody'];
        $total_refunds = $refunds['total'];

        $query = 'SELECT pos.pos_ref, pos.firstname, pos.lastname, pos.date, pos.subtotal, pos.total, 
                pos.discount, t.name, p.name
            FROM pos
            INNER JOIN transaction_type t ON t.id = pos.transaction_type_id
            INNER JOIN payment_type p ON p.id = pos.payment_type_id
            WHERE DATE(STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p")) BETWEEN ? AND ?';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($pos_ref, $fname, $lname, $date, $subtotal, $total, $discount, $transaction, $payment);

        $total_sales = 0;
        $total_discounts = 0;
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                    <td class="text-center">' . strtoupper($pos_ref) . '</td>
                    <td class="text-center">' . ucfirst($fname) . ' ' . ucfirst($lname) . '</td>
                    <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                    <td class="text-center">' . strtoupper($transaction) . '</td>
                    <td class="text-center">' . strtoupper($payment) . '</td>
                    <td class="text-end">₱' . number_format($discount, 2) . '</td>
                    <td class="text-end">₱' . number_format($subtotal, 2) . '</td>
                    <td class="text-end">₱' . number_format($total, 2) . '</td>
                </tr>';
            $total_sales += $total;
            $total_discounts += $discount;
        }
        $stmt->close();

        // Calculate net sales
        $net_sales = $total_sales - $total_discounts - $total_refunds;

        // Summary rows for total sales, discounts, refunds, and net sales
        $tbody .= '<tr>
                <td colspan="7" class="text-end font-weight-bold">Total Sales:</td>
                <td class="text-end">₱' . number_format($total_sales, 2) . '</td>
            </tr>';
        $tbody .= '<tr>
                <td colspan="7" class="text-end font-weight-bold">Total Discounts:</td>
                <td class="text-end text-danger">-₱' . number_format($total_discounts, 2) . '</td>
            </tr>';
        $tbody .= '<tr>
                <td colspan="7" class="text-end font-weight-bold">Total Refunds:</td>
                <td class="text-end text-danger">-₱' . number_format($total_refunds, 2) . '</td>
            </tr>';
        // Determine class based on net sales value
        $net_sales_class = $net_sales < 0 ? 'text-danger' : 'text-success';

        $tbody .= '<tr>
                    <td colspan="7" class="text-end font-weight-bold">Net Sales:</td>
                    <td class="text-end font-weight-bold ' . $net_sales_class . '">₱' . number_format($net_sales, 2) . '</td>
                </tr>';

        // Update column headers
        $thead = '<th class="text-center">POS Ref</th>
              <th class="text-center">Customer</th>
              <th class="text-center">Date</th>
              <th class="text-center">Transaction</th>
              <th class="text-center">Payment</th>
              <th class="text-end">Discount</th>
              <th class="text-end">Subtotal</th>
              <th class="text-end">Total</th>';

        $signature = '<tr>
                <td colspan="8" class="text-center" style="padding-top: 50px;">
                    <p class="mb-0">__________________________</p>
                    <p class="mb-0 mt-0"><strong>Angelica Odulio</strong></p>
                    <p class="mt-0"><strong>Manager</strong></p>
                </td>
              </tr>';

        return [
            'tbody' => $tbody . $signature,
            'thead' => $thead
        ];
    }

    public function products($start_date, $end_date)
    {
        $order = $this->getOrderProducts($start_date, $end_date);
        $pos = $this->getPosProducts($start_date, $end_date);

        $signature = '<tr>
                <td colspan="8" class="text-center" style="padding-top: 50px;">
                    <p class="mb-0">__________________________</p>
                    <p class="mb-0 mt-0"><strong>Angelica Odulio</strong></p>
                    <p class="mt-0"><strong>Manager</strong></p>
                </td>
              </tr>';

        return [
            'thead' => $order['thead'],
            'tbody' => $order['tbody'] . $pos['tbody'] . $signature
        ];
    }

    public function getOrderProducts($start_date, $end_date)
    {
        $query = 'SELECT o.order_ref, oi.product_id, SUM(oi.qty) AS total_qty, p.name, u.name, v.name, p.unit_value
                FROM orders o
                INNER JOIN order_items oi ON oi.order_ref = o.order_ref
                INNER JOIN product p ON p.id = oi.product_id
                INNER JOIN unit u ON u.id = p.unit_id
                INNER JOIN variant v ON v.id = p.variant_id
                WHERE DATE(o.date) BETWEEN ? AND ?
                GROUP BY oi.product_id
                ORDER BY total_qty DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($order_ref, $product_id, $qty, $product_name, $unit, $variant, $unit_value);
        $tbody = '';
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                    <td class="text-center">' . $product_name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                    <td class="text-center">Online Order</td>
                    <td class="text-center">' . $qty . '</td>
                </tr>';
        }
        $stmt->close();

        $thead = '<th class="text-center">Product</th>
              <th class="text-center">Module</th>
              <th class="text-center">Quantity Sold</th>';

        return [
            'thead' => $thead,
            'tbody' => $tbody
        ];
    }

    public function getPosProducts($start_date, $end_date)
    {
        $query = 'SELECT pos.pos_ref, pi.product_id, SUM(pi.qty) AS total_qty, p.name, u.name, v.name, p.unit_value
                FROM pos
                INNER JOIN pos_items pi ON pi.pos_ref = pos.pos_ref
                INNER JOIN product p ON p.id = pi.product_id
                INNER JOIN unit u ON u.id = p.unit_id
                INNER JOIN variant v ON v.id = p.variant_id
                WHERE DATE(STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p")) BETWEEN ? AND ?
                GROUP BY pi.product_id
                ORDER BY total_qty DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($pos_ref, $product_id, $qty, $product_name, $unit, $variant, $unit_value);
        $tbody = '';
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                    <td class="text-center">' . $product_name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit) . '</td>
                    <td class="text-center">Point of Sale</td>
                    <td class="text-center">' . $qty . '</td>
                </tr>';
        }
        $stmt->close();

        $thead = '<th class="text-center">Product</th>
              <th class="text-center">Module</th>
              <th class="text-center">Quantity Sold</th>';

        return [
            'thead' => $thead,
            'tbody' => $tbody
        ];
    }
}
