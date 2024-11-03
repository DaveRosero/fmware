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

    public function sales($start_date, $end_date)
    {
        $query = 'SELECT pos.pos_ref, pos.firstname, pos.lastname, pos.date, pos.subtotal, pos.total, 
                        pos.discount, t.name, p.name
                    FROM pos
                    INNER JOIN transaction_type t ON t.id = pos.transaction_type_id
                    INNER JOIN payment_type p ON p.id = pos.payment_type_id
                    WHERE STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p") BETWEEN 
                      STR_TO_DATE(?, "%Y-%m-%d") AND 
                      STR_TO_DATE(?, "%Y-%m-%d")';
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
        $tbody = '';
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
        }
        $stmt->close();
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
