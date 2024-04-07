<?php
    require_once 'model/user/user.php';

    class Transaction extends User {
        public function getOrderCount ($user_id) {
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

        public function getToPayCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "to pay"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $to_pay = '('.$count.')';
                    return $to_pay;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getPendingCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "pending"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $pending = '('.$count.')';
                    return $pending;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getToReceiveCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "delivering"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $to_receive = '('.$count.')';
                    return $to_receive;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getDeliveredCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "delivered"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $delivered = '('.$count.')';
                    return $delivered;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCompletedCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "completed"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $completed = '('.$count.')';
                    return $completed;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCancelledCount ($user_id) {
            $query = 'SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = "cancelled"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    $cancelled = '('.$count.')';
                    return $cancelled;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function displayButtons ($status, $user_id) {
            $buttons = '';
            $to_pay = $this->getToPayCount($user_id);
            $pending = $this->getPendingCount($user_id);
            $to_receive = $this->getToReceiveCount($user_id);
            $delivered = $this->getDeliveredCount($user_id);
            $completed = $this->getCompletedCount($user_id);
            $cancelled = $this->getCancelledCount($user_id);

            if ($status === 'to-pay') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/to-pay">To Pay '.$to_pay.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/to-pay">To Pay '.$to_pay.'</a>
                            </div>';
            }

            if ($status === 'pending') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/pending">Pending '.$pending.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/pending">Pending '.$pending.'</a>
                            </div>';
            }

            if ($status === 'to-receive') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/to-receive">To Receive '.$to_receive.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/to-receive">To Receive '.$to_receive.'</a>
                            </div>';
            }

            if ($status === 'delivered') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/delivered">Delivered '.$delivered.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/delivered">Delivered '.$delivered.'</a>
                            </div>';
            }

            if ($status === 'completed') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/completed">Completed '.$completed.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/completed">Completed '.$completed.'</a>
                            </div>';
            }

            if ($status === 'cancelled') {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-primary" href="/fmware/my-purchases/cancelled">Cancelled '.$cancelled.'</a>
                            </div>';
            } else {
                $buttons .= '<div class="col-12 col-md-auto text-center">
                                <a class="text-decoration-none badge bg-secondary" href="/fmware/my-purchases/cancelled">Cancelled '.$cancelled.'</a>
                            </div>';
            }

            echo $buttons;
        }

        public function getPendingOrder ($user_id) {
            $query = 'SELECT id, order_ref, gross, date
                    FROM orders
                    WHERE user_id = ?
                    AND status = "pending"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $order_ref, $gross, $date);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td class="text-primary fw-bold">'.$order_ref.'</td>
                                <td class="fw-semibold">₱'.number_format($gross).'.00</td>
                                <td>'.date('d F Y h:i', strtotime($date)).'</td>
                                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
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

        public function getToReceiveOrder ($user_id) {
            $query = 'SELECT id, order_ref, gross, date
                    FROM orders
                    WHERE user_id = ?
                    AND status = "delivering"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $order_ref, $gross, $date);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td class="text-primary fw-bold">'.$order_ref.'</td>
                                <td class="fw-semibold">₱'.number_format($gross).'.00</td>
                                <td>'.date('d F Y h:i', strtotime($date)).'</td>
                                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
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

        public function getDeliveredOrder ($user_id) {
            $query = 'SELECT id, order_ref, gross, date
                    FROM orders
                    WHERE user_id = ?
                    AND status = "delivered"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $order_ref, $gross, $date);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td class="text-primary fw-bold">'.$order_ref.'</td>
                                <td class="fw-semibold">₱'.number_format($gross).'.00</td>
                                <td>'.date('d F Y h:i', strtotime($date)).'</td>
                                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
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

        public function getCompletedOrder ($user_id) {
            $query = 'SELECT id, order_ref, gross, date
                    FROM orders
                    WHERE user_id = ?
                    AND status = "completed"';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $order_ref, $gross, $date);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td class="text-primary fw-bold">'.$order_ref.'</td>
                                <td class="fw-semibold">₱'.number_format($gross).'.00</td>
                                <td>'.date('d F Y h:i', strtotime($date)).'</td>
                                <td><button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></button></td>
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

        public function displayHistory ($status, $user_id) {
            if ($status === 'pending') {
                $this->getPendingOrder($user_id);
            }
            
            if ($status === 'to-receive') {
                $this->getToReceiveOrder($user_id);
            }

            if ($status === 'delivered') {
                $this->getDeliveredOrder($user_id);
            }

            if ($status) {
                
            }
        }
    }
?>