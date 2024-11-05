<?php
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
        }

        $json = array(
            'thead' => $content['thead'],
            'tbody' => $content['tbody']
        );
        echo json_encode($json);
        return;
    }

    public function orders($start_date, $end_date)
    {
        $query = 'SELECT o.id, o.order_ref, o.firstname, o.lastname, o.phone, o.date, o.gross, o.discount, 
                            t.name, p.name, o.paid, o.status
                    FROM orders o
                    INNER JOIN transaction_type t ON t.id = o.transaction_type_id
                    INNER JOIN payment_type p ON p.id = o.payment_type_id
                    WHERE DATE(o.date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $order_ref, $fname, $lname, $phone, $date, $total, $discount, $transaction, $payment, $paid, $status);
        $tbody = '';
        while ($stmt->fetch()) {
            $tbody .= '<tr>
                                <td class="text-center">' . strtoupper($order_ref) . '</td>
                                <td class="text-center">' . ucfirst($fname) . ' ' . ucfirst($lname) . '</td>
                                <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                                <td class="text-center">₱' . number_format($total, 2) . '</td>
                                <td class="text-center">' . ucwords($transaction) . '</td>
                                <td class="text-center">' . strtoupper($payment) . '</td>
                                <td class="text-center">' . strtoupper($paid) . '</td>
                                <td class="text-center">' . strtoupper($status) . '</td>
                            </tr>';
        }
        $stmt->close();
        $thead = '<th class="text-center">Order Ref</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Transaction</th>
                    <th class="text-center">Payment</th>
                    <th class="text-center">Paid</th>
                    <th class="text-center">Status</th>';
        $signature = '<tr>
                        <td colspan="8" class="text-center" style="padding-top: 50px;">
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

    public function refunds($start_date, $end_date)
    {
        $query = "SELECT 
                SUBSTRING(description, 
                        LOCATE('Transaction ', description) + LENGTH('Transaction '), 
                        LOCATE(',', description) - LOCATE('Transaction ', description) - LENGTH('Transaction ')) AS transaction_reference,
                CASE 
                    WHEN description LIKE 'Updated refund for Transaction%' 
                        AND description LIKE '%New Total Amount: ₱%' 
                        THEN 
                        CAST(SUBSTRING_INDEX(SUBSTRING(description, 
                                    LOCATE('New Total Amount: ₱', description) + LENGTH('New Total Amount: ₱')), 
                                    ' ', 1) AS DECIMAL(10, 2))
                    WHEN description LIKE 'Created new refund for Transaction%' 
                        AND description LIKE '%Amount: ₱%' 
                        THEN 
                        CAST(
                            SUBSTRING(
                                description, 
                                LOCATE('₱', description) + 1, 
                                LOCATE('.', description, LOCATE('₱', description)) - LOCATE('₱', description)
                            ) AS DECIMAL(10, 2)
                        )
                END AS refund_amount,
                description
            FROM logs
            WHERE (description LIKE 'Updated refund for Transaction%' 
                OR description LIKE 'Created new refund for Transaction%')
                AND description LIKE '%POS_%'
                AND DATE(STR_TO_DATE(date, '%M %d, %Y %h:%i %p')) BETWEEN ? AND ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($pos_ref, $amount, $description);
        $refunds = [];
        while ($stmt->fetch()) {
            // Default to 0 if no amount is retrieved
            if ($amount === null) {
                $amount = 0;
            }

            // Check if it's an updated refund and override if exists, otherwise add new entry
            if (strpos($description, 'Updated refund for Transaction') !== false) {
                $refunds[$pos_ref] = $amount; // Set or update with the updated amount
            } elseif (!isset($refunds[$pos_ref])) {
                $refunds[$pos_ref] = $amount; // Set initial amount for created refund
            }
        }
        $stmt->close();

        // Prepare the table rows and calculate the total
        $tbody = '';
        $total = 0;
        foreach ($refunds as $pos_ref => $amount) {
            $tbody .= '<tr>
                        <td class="text-center">' . strtoupper($pos_ref) . '</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">-₱' . number_format($amount, 2) . '</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>';
            $total += $amount;
        }

        return [
            'tbody' => $tbody,
            'total' => $total
        ];
    }

    public function sales($start_date, $end_date)
    {
        $refunds = $this->refunds($start_date, $end_date);
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
                        <td class="text-center">₱' . number_format($subtotal, 2) . '</td>
                        <td class="text-center">₱' . number_format($total, 2) . '</td>
                        <td class="text-center">₱' . number_format($discount, 2) . '</td>
                        <td class="text-center">' . strtoupper($transaction) . '</td>
                        <td class="text-center">' . strtoupper($payment) . '</td>
                    </tr>';
            $total_sales += $total;
            $total_discounts += $discount;
        }
        $stmt->close();

        // Calculate net sales
        $net_sales = $total_sales - $total_discounts - $total_refunds;

        // Summary rows for total sales, discounts, refunds, and net sales
        $tbody .= '<tr>
                    <td colspan="4" class="text-end font-weight-bold">Total Sales:</td>
                    <td class="text-center">₱' . number_format($total_sales, 2) . '</td>
                </tr>';
        $tbody .= '<tr>
                    <td colspan="4" class="text-end font-weight-bold">Total Discounts:</td>
                    <td class="text-center text-danger">-₱' . number_format($total_discounts, 2) . '</td>
                </tr>';
        $tbody .= '<tr>
                    <td colspan="4" class="text-end font-weight-bold">Total Refunds:</td>
                    <td class="text-center text-danger">-₱' . number_format($total_refunds, 2) . '</td>
                </tr>';
        $tbody .= '<tr>
                    <td colspan="4" class="text-end font-weight-bold">Net Sales:</td>
                    <td class="text-center font-weight-bold">₱' . number_format($net_sales, 2) . '</td>
                </tr>';

        $thead = '<th class="text-center">POS Ref</th>
              <th class="text-center">Customer</th>
              <th class="text-center">Date</th>
              <th class="text-center">Subtotal</th>
              <th class="text-center">Total</th>
              <th class="text-center">Discount</th>
              <th class="text-center">Transaction</th>
              <th class="text-center">Payment</th>';

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
}
