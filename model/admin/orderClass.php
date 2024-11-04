<?php
require_once 'model/admin/admin.php';

class Order extends Admin
{
    public function paidFormat($paid)
    {
        if ($paid === 'unpaid') {
            $format = '<span class="badge bg-danger text-wrap">' . strtoupper($paid) . '</span>';
        }

        if ($paid === 'paid') {
            $format = '<span class="badge bg-success text-wrap">' . strtoupper($paid) . '</span>';
        }

        return $format;
    }

    public function paidButton($paid, $payment, $status)
    {
        if ($payment == 1) {
            return '<img src="/asset/images/payments/cod.png" alt="COD" style="width: 250px;">';
        }

        if ($payment == 4 && $status === 'claimed') {
            return '<p>The order has been claimed by the customer.</p>';
        }

        if ($payment == 4) {
            return '<p>To be picked up by the customer.</p>';
        }

        if ($status === 'to pay') {
            return '<p>Awaiting for buyer to upload proof of payment...</p>';
        }

        if ($paid === 'unpaid' && $status === 'pending') {
            $button = '<div class="col col-auto">  
                            <button class="btn btn-lg btn-danger" id="deny-btn">
                                <span><i class="bi bi-x-circle-fill"></i></span> 
                                Deny
                            </button>
                        </div>
                        <div class="col col-auto">
                            <i class="fas fa-arrow-right fa-2x"></i>
                        </div>
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-success" id="paid-btn"><i class="bi bi-check-circle-fill"></i> Approve</button>
                        </div>';
        }

        if ($paid === 'unpaid' && $status === 'cancelled') {
            return '<p>Proof of Payment denied.</p>';
        }

        if ($paid === 'paid') {
            $button = '<p class="text-center fw-bold fs-4">The order has been paid.</p>
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-success" disabled><i class="bi bi-check-circle-fill"></i> Paid</button>
                        </div>';
        }

        return $button;
    }

    public function paidStatus($order_ref)
    {
        $query = 'SELECT paid, payment_type_id, status FROM orders WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($paid, $payment, $status);
                $stmt->fetch();
                $stmt->close();
                $button = $this->paidButton($paid, $payment, $status);
                echo $button;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function updatePaidStatus($order_ref, $paid)
    {
        $query = 'UPDATE orders SET paid = ? WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $paid, $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function statusFormat($status)
    {
        $status = strtolower($status);
        if ($status === 'to pay') {
            $format = '<span class="badge bg-info text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'pending') {
            $format = '<span class="badge bg-warning text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'delivering') {
            $format = '<span class="badge bg-info text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'delivered') {
            $format = '<span class="badge bg-success text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'prepared') {
            $format = '<span class="badge bg-light border text-dark text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'partially refunded' || $status === 'fully refunded' || $status === 'partially replaced' || $status === 'fully replaced') {
            $format = '<span class="badge bg-secondary text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'cancelled') {
            $format = '<span class="badge bg-danger text-wrap">' . strtoupper($status) . '</span>';
        }

        if ($status === 'claimed') {
            $format = '<span class="badge bg-success text-wrap">' . strtoupper($status) . '</span>';
        }

        return $format ?? null;
    }

    public function statusButton($status, $paid, $payment)
    {
        if ($payment == 4 && $status === 'claimed') {
            return '<p>The order has been claimed by the customer.</p>';
        }

        if ($payment == 4) {
            return '<p>To be picked up by the customer.</p>';
        }

        if ($status === 'to pay') {
            return '<p>Awaiting approval of the payment...</p>';
        }

        if ($status === 'cancelled') {
            return '<p>Order is cancelled.</p>';
        }

        if ($payment == 2 && $paid === 'unpaid') {
            return '<p>Awaiting approval of the payment...</p>';
        }

        if ($status === 'pending') {
            $button = '<div class="col col-auto">
                            <button class="btn btn-lg btn-warning" disabled><i class="bi bi-hourglass-split"></i> Pending</button>
                        </div>
                        <div class="col col-auto">
                            <i class="bi bi-arrow-right fs-4"></i>
                        </div>
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-primary" id="delivering-btn"><i class="bi bi-truck"></i> Delivering</button>
                        </div>';
        }

        if ($status === 'delivering') {
            $button = '<div class="col col-auto">
                            <button class="btn btn-lg btn-primary" disabled><i class="bi bi-truck"></i> Delivering</button>
                        </div>
                        <div class="col col-auto">
                            <i class="bi bi-arrow-right fs-4"></i>
                        </div>
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-success" id="delivered-btn" disabled><i class="bi bi-check-circle-fill"></i> Delivered</button>
                        </div>
                        ';
        }

        if ($status === 'delivered') {
            $button = '<p class="text-center fw-bold fs-4">The order has been delivered.</p>
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-success" disabled><i class="bi bi-check-circle-fill"></i> Delivered</button>
                        </div>';
        }

        return $button;
    }

    public function orderStatus($order_ref)
    {
        $query = 'SELECT status, paid, payment_type_id FROM orders WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($status, $paid, $payment);
                $stmt->fetch();
                $stmt->close();
                $button = $this->statusButton($status, $paid, $payment);
                return [
                    'html' => $button,
                    'status' => $status,
                    'paid' => $paid,
                    'payment' => $payment
                ];
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function updateOrderStatus($order_ref, $status)
    {
        $query = 'UPDATE orders SET status = ? WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $status, $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getOrders()
    {
        $query = 'SELECT orders.id, 
                            orders.order_ref,
                            orders.firstname,
                            orders.lastname,
                            orders.date,
                            orders.gross,
                            orders.paid,
                            orders.status,
                            transaction_type.name,
                            payment_type.name
                    FROM orders
                    INNER JOIN transaction_type ON transaction_type.id = orders.transaction_type_id
                    INNER JOIN payment_type ON payment_type.id = orders.payment_type_id
                    ORDER BY orders.date DESC';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $ref, $fname, $lname, $date, $gross, $paid, $status, $transaction, $payment);
                $content = '';
                while ($stmt->fetch()) {
                    $initial = substr($lname, 0, 1);
                    $customer = $fname . ' ' . $initial . '.';
                    $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                    $dateFormat = $dateObj->format('F j, Y');

                    $content .= '<tr>
                                <td class="text-center"><strong>' . $ref . '</strong></td>
                                <td class="text-center text-primary"><strong>' . $customer . '</strong></td>
                                <td class="text-center">' . $dateFormat . '</td>
                                <td class="text-center">' . $transaction . '</td>
                                <td class="text-center">' . $payment . '</td>
                                <td class="text-center">₱' . number_format($gross, 2) . '</td>
                                <td class="text-center">' . $this->paidFormat($paid) . '</td>
                                <td class="text-center">' . $this->statusFormat($status) . '</td>
                                <td class="text-center">
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-primary viewOrder"
                                            data-order-ref="' . $ref . '"
                                            data-customer-name="' . $customer . '"
                                            data-paid="' . $paid . '"
                                            data-status="' . $status . '"
                                        >
                                            View
                                        </button>
                                        <!-- <button class="btn btn-sm btn-info print-receipt ms-1"
                                            data-order-ref="' . $ref . '"
                                            data-customer-name="' . $customer . '"
                                        >
                                            <i class="bi bi-printer-fill"></i>
                                        </button> -->
                                    </div>
                                </td>
                            </tr>';
                }
                $stmt->close();

                return $content;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function printReceipt($order_ref)
    {
        $query = 'SELECT orders.order_ref,
                            orders.date,
                            orders.firstname,
                            orders.lastname
                    FROM orders
                    WHERE orders.order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($order_ref, $date, $fname, $lname);
                $stmt->fetch();
                $stmt->close();
                $initial = substr($lname, 0, 1);
                $customer = $fname . ' ' . $initial . '.';
                $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                $dateFormat = $dateObj->format('d F Y h:i A');

                return [
                    'order_ref' => $order_ref,
                    'date' => $dateFormat,
                    'customer' => $customer,
                ];
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getOrderItems($order_ref)
    {
        $query = 'SELECT order_items.qty,
                            product.name,
                            product.unit_value,
                            price_list.unit_price,
                            orders.delivery_fee,
                            orders.gross,
                            variant.name,
                            unit.name
                    FROM order_items
                    INNER JOIN product ON product.id = order_items.product_id
                    INNER JOIN price_list ON price_list.product_id = order_items.product_id
                    INNER JOIN orders ON orders.order_ref = order_items.order_ref
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    WHERE order_items.order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($qty, $name, $unit_value, $price, $delivery_fee, $gross, $variant, $unit);
                $content = '';
                while ($stmt->fetch()) {
                    $price *= $qty;
                    $content .= '<div class="item">
                                        <span>' . $qty . ' x ' . $name . ' (' . $variant . ') (' . $unit_value . ' ' . $unit . ')</span>
                                        <span class="float-end">₱' . number_format($price) . '.00</span>
                                    </div>';
                }
                $stmt->close();
                $content .= '<hr>
                                <div class="item">
                                    <span>Delivery Fee</span>
                                    <span class="float-end">₱' . number_format($delivery_fee) . '.00</span>
                                </div>
                                <div class="item">
                                    <span>Total:</span>
                                    <span class="float-end">₱' . number_format($gross) . '.00</span>
                                </div>';
                echo $content;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCode($order_ref)
    {
        $query = 'SELECT code FROM orders WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($code);
                $stmt->fetch();
                $stmt->close();
                return $code;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    // public function receiptQrCode ($order_ref) {
    //     $code = $this->getCode($order_ref);
    //     // $api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=fmware-store.000webhostapp.com/confirm-order/'.$order_ref;
    //     $api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=192.168.1.16/confirm-order/'.$code.'/'.$order_ref;
    //     $filename = $order_ref . '.jpg';
    //     $image = file_get_contents($api);
    //     $savePath = 'asset/images/payments/receipts/qr_code/' . $filename;

    //     if (file_exists($savePath)) {
    //         $qrcode = '<img src="/'.$savePath.'" alt="">';
    //         return $qrcode;
    //     }

    //     if ($image !== false) {
    //         $saveResult = file_put_contents($savePath, $image);
    //         if ($saveResult !== false) {
    //             $qrcode = '<img src="/'.$savePath.'" alt="">';
    //             return $qrcode;
    //         } else {
    //             echo "Error: Failed to save the image to $savePath";
    //         }
    //     }else {
    //         echo "Error: Failed to fetch image data from $api";
    //     }
    // }

    public function generateQrCode($order_ref)
    {
        $code = $this->getCode($order_ref);
        $url = 'https://fmware.shop/confirm-order/' . $code . '/' . $order_ref;
        return $url;
    }

    public function confirmOrder($order_ref)
    {
        $query = 'UPDATE orders SET code = NULL WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                $status = 'delivered';
                $paid = 'paid';
                $this->updateOrderStatus($order_ref, $status);
                $this->updatePaidStatus($order_ref, $paid);
                return true;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function uploadPayment()
    {
        $targetDir = 'asset/images/payments/proof/gcash/';
        $uniqueFilename = uniqid() . '_' . time() . '_' . mt_rand(1000, 9999) . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $uniqueFilename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        $image = $uniqueFilename;
        $order_ref = $_POST['order_ref'];
        $query = 'INSERT INTO proof_of_transaction
                        (order_ref, proof_of_payment)
                    VALUES (?,?)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $order_ref, $image);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                $status = 'pending';
                $this->updateOrderStatus($order_ref, $status);
                return [
                    'redirect' => '/my-purchases/pending'
                ];
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function checkOrder($order_ref, $urlCode)
    {
        $query = 'SELECT status, code FROM orders WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($status, $code);
                $stmt->fetch();
                $stmt->close();

                if ($status === 'delivered' || $status === 'pending' || $status === 'to pay' || $urlCode !== $code) {
                    return true;
                }

                return false;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getProofCOD($order_ref)
    {
        $query = 'SELECT  proof_of_payment, proof_of_delivery FROM proof_of_transaction WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($payment, $delivery);
                $stmt->fetch();
                $stmt->close();
                if ($payment === NULL) {
                    return '<img src="/asset/images/payments/proof/cod/' . $delivery . '" alt="" srcset="" style="width: 300px; height: 500px;">';
                }
                return '<img src="/asset/images/payments/proof/gcash/' . $payment . '" alt="" srcset="" style="width: 300px; height: 500px;">';
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getOrderDetails($order_ref)
    {
        $query = 'SELECT order_items.product_id,
                            order_items.qty,
                            product.name,
                            product.image,
                            product.unit_value,
                            unit.name,
                            variant.name,
                            price_list.unit_price,
                            orders.payment_type_id
                    FROM order_items
                    INNER JOIN product ON product.id = order_items.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN price_list on price_list.product_id = product.id
                    INNER JOIN orders ON orders.order_ref = order_items.order_ref
                    WHERE order_items.order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $order_ref);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $qty, $name, $image, $unit_value, $unit, $variant, $price, $payment);
                $order_details = '';
                while ($stmt->fetch()) {
                    $subtotal = $qty * $price;
                    $order_details .= '<div class="row g-3">
                                            <div class="col-auto">
                                                <img src="/asset/images/products/' . $image . '" alt="" class="img-fluid" style="max-width: 100px;">
                                            </div>
                                            <div class="col">
                                                <p class="mb-1">' . $name . ' (' . $variant . ') (' . $unit_value . ' ' . strtoupper($unit) . ')</p>
                                                <p class="mb-0">x' . $qty . '</p>
                                            </div>
                                            <div class="col-auto">
                                                <p class="mb-0">₱' . number_format($subtotal) . '.00</p>
                                            </div>
                                        </div>';
                }
                $stmt->close();
                if ($payment == 2) {
                    $proof = '<div class="row mt-4 mb-4">
                                    <label for="imageInput" class="form-label">Upload Proof of Payment <span class="text-danger">*</span>
                                    <input type="file" class="form-control" id="imageInput" name="image" accept="image/*" required>
                                    <input type="hidden" name="order_ref" id="order_ref" value="' . $order_ref . '">
                                    <button type="submit" class="btn btn-primary mt-2">Upload Proof of Payment</button>
                                </div>';
                    return $order_details . $proof;
                }
                return $order_details;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getPrintOrders()
    {
        $query = 'SELECT orders.id, 
                            orders.order_ref,
                            orders.firstname,
                            orders.lastname,
                            orders.date,
                            orders.gross,
                            orders.paid,
                            orders.status,
                            transaction_type.name,
                            payment_type.name
                    FROM orders
                    INNER JOIN transaction_type ON transaction_type.id = orders.transaction_type_id
                    INNER JOIN payment_type ON payment_type.id = orders.payment_type_id
                    ORDER BY orders.date DESC';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $ref, $fname, $lname, $date, $gross, $paid, $status, $transaction, $payment);
                $content = '';
                while ($stmt->fetch()) {
                    $initial = substr($lname, 0, 1);
                    $customer = $fname . ' ' . $initial . '.';
                    $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                    $dateFormat = $dateObj->format('F j, Y');

                    $content .= '<tr>
                                <td><strong>' . $ref . '</strong></td>
                                <td class="text-primary"><strong>' . $customer . '</strong></td>
                                <td>' . $dateFormat . '</td>
                                <td>' . $transaction . '</td>
                                <td>' . $payment . '</td>
                                <td>₱' . number_format($gross) . '.00</td>
                                <td>' . strtoupper($paid) . '</td>
                                <td>' . strtoupper($status) . '</td>
                            </tr>';
                }
                $stmt->close();

                return $content;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function denyOrder($order_ref)
    {
        $status = 'cancelled';
        $query = 'UPDATE orders SET status = ? WHERE order_ref = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $status, $order_ref);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $json = array(
            'redirect' => '/manage-orders'
        );
        echo json_encode($json);
    }
}
