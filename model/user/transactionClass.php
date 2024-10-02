<?php
require_once 'model/user/user.php';

class Transaction extends User
{
    public function getOrderCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                echo $count;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getToPayCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "to pay"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $to_pay = '(' . $count . ')';
                return $to_pay;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getPendingCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "pending"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $pending = '(' . $count . ')';
                return $pending;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getToReceiveCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "delivering"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $to_receive = '(' . $count . ')';
                return $to_receive;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getDeliveredCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "delivered"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $delivered = '(' . $count . ')';
                return $delivered;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCompletedCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "completed"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $completed = '(' . $count . ')';
                return $completed;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCancelledCount($user_id)
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "cancelled"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                $cancelled = '(' . $count . ')';
                return $cancelled;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getToPayOrder($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "to pay"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getPendingOrder($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "pending"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getToReceiveOrder($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "delivering"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getDeliveredOrder($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "delivered"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCompletedOrder($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "completed"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCancelledOrders($user_id)
    {
        $query = 'SELECT orders.id,
                            orders.order_ref,
                            orders.gross,
                            orders.date,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.name ORDER BY order_items.id), ",", 1) AS first_product_name,
                            SUBSTRING_INDEX(GROUP_CONCAT(product.image ORDER BY order_items.id), ",", 1) AS first_product_image,
                            GROUP_CONCAT(product.name SEPARATOR ", ") AS additional_products
                    FROM orders
                    INNER JOIN order_items ON order_items.order_ref = orders.order_ref
                    INNER JOIN product ON product.id = order_items.product_id 
                    WHERE orders.user_id = ?
                    AND orders.status = "cancelled"
                    GROUP BY orders.order_ref';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $order_ref, $gross, $date, $first_product_name, $first_product_image, $additional_products);

                while ($stmt->fetch()) {
                    echo '<tr>
                                <td class="text-primary fw-bold">' . $order_ref . '</td>
                                <td class="fw-semibold">₱' . number_format($gross) . '.00</td>
                                <td>' . date('d F Y h:i', strtotime($date)) . '</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <img src="/asset/images/products/' . $first_product_image . '" class="img-fluid" style="width: 50px; margin-right: 10px;">
                                        <span>' . $first_product_name;

                    if ($additional_products) {
                        // Count the number of commas to determine the number of additional products
                        $additional_product_count = substr_count($additional_products, ',');

                        // Display the number of additional products if there are any
                        if ($additional_product_count > 0) {
                            echo ' + ' . $additional_product_count . ' more';
                        }
                    }

                    echo '          </span>
                                    </div>
                                </td>
                                <td><button class="btn btn-sm btn-primary viewOrder" data-order-ref="' . $order_ref . '"><i class="fas fa-eye"></i></button></td>
                            </tr>';
                }
                $stmt->close();
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function displayTitle($status)
    {
        if ($status === 'to-pay') {
            return 'To Pay';
        }

        if ($status === 'pending') {
            return 'Pending';
        }

        if ($status === 'to-receive') {
            return 'To Receive';
        }

        if ($status === 'delivered') {
            return 'Delivered';
        }

        if ($status === 'completed') {
            return 'Completed';
        }

        if ($status === 'cancelled') {
            return 'Cancelled';
        }
    }

    public function displayHistory($status, $user_id)
    {
        if ($status === 'pending') {
            $this->getPendingOrder($user_id);
        }

        if ($status === 'to-receive') {
            $this->getToReceiveOrder($user_id);
        }

        if ($status === 'delivered') {
            $this->getDeliveredOrder($user_id);
        }

        if ($status === 'completed') {
            $this->getCompletedOrder($user_id);
        }

        if ($status === 'to-pay') {
            $this->getToPayOrder($user_id);
        }

        if ($status === 'cancelled') {
            $this->getCancelledOrders($user_id);
        }
    }
}
