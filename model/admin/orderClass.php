<?php
    require_once 'model/admin/admin.php';

    class Order extends Admin {
        public function paidFormat ($paid) {
            if ($paid === 'unpaid') {
                $format = '<span class="badge bg-danger text-wrap">'.strtoupper($paid).'</span>';
            }

            if ($paid === 'paid') {
                $format = '<span class="badge bg-success text-wrap">'.strtoupper($paid).'</span>';
            }

            return $format;
        }

        public function statusFormat ($status) {
            if ($status === 'pending') {
                $format = '<span class="badge bg-warning text-wrap">'.strtoupper($status).'</span>';
            }

            if ($status === 'delivering') {
                $format = '<span class="badge bg-secondary text-wrap">'.strtoupper($status).'</span>';
            }

            if ($status === 'delivered') {
                $format = '<span class="badge bg-success text-wrap">'.strtoupper($status).'</span>';
            }

            return $format;
        }
        
        public function getOrders () {
            $query = 'SELECT orders.id, 
                            orders.order_ref,
                            orders.firstname,
                            orders.lastname,
                            orders.date,
                            orders.paid,
                            orders.status,
                            transaction_type.name,
                            payment_type.name
                    FROM orders
                    INNER JOIN transaction_type ON transaction_type.id = orders.transaction_type_id
                    INNER JOIN payment_type ON payment_type.id = orders.payment_type_id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $ref, $fname, $lname, $date, $paid, $status, $transaction, $payment);
                    while ($stmt->fetch()) {
                        $initial = substr($lname, 0, 1);
                        $customer = $fname.' '.$initial.'.';
                        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                        $dateFormat = $dateObj->format('d F Y');

                        echo '<tr>
                                <td>
                                    <button class="btn btn-sm btn-primary viewOrder"
                                        data-order-ref="'.$ref.'"
                                        data-customer-name="'.$customer.'"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td><strong>'.$ref.'</strong></td>
                                <td>'.$dateFormat.'</td>
                                <td>'.$transaction.'</td>
                                <td>'.$payment.'</td>
                                <td>'.$this->paidFormat($paid).'</td>
                                <td>'.$this->statusFormat($status).'</td>
                            </tr>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }
    }
?>