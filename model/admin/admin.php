<?php
    require_once 'model/database/database.php';

    class Admin{
        protected $conn;
        public function __construct () {
            $this->conn = database();
        }

        public function isAdmin () {
            if (isset($_SESSION['user_id'])) {
                $id = $_SESSION['user_id'];
            } else {
                header('Location: /404');
            }
            

            $query = 'SELECT user_group.group_id, groups.name
                    FROM user_group
                    INNER JOIN groups ON user_group.group_id = groups.id
                    WHERE user_group.user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($group_id, $group_name);
                    $stmt->fetch();
                    $stmt->close();

                    if ($group_name == 'user') {
                        header('Location: /404');
                    } else {
                        return null;
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function isDelivery () {
            if (isset($_SESSION['user_id'])) {
                $id = $_SESSION['user_id'];
            } else {
                header('Location: /404');
            }

            $query = 'SELECT user_group.group_id, groups.name
                    FROM user_group
                    INNER JOIN groups ON user_group.group_id = groups.id
                    WHERE user_group.user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($group_id, $group_name);
                    $stmt->fetch();
                    $stmt->close();

                    if ($group_name == 'Delivery Rider') {
                        return null;
                    } else {
                        header('Location: /404');
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getTotalProducts () {
            $query = 'SELECT COUNT(*) FROM product WHERE active = 1';
            $stmt = $this->conn->prepare($query);
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

        public function getTotalSuppliers () {
            $query = 'SELECT COUNT(*) FROM supplier WHERE active = 1';
            $stmt = $this->conn->prepare($query);
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

        public function getTotalUsers () {
            $query = 'SELECT COUNT(*) FROM user';
            $stmt = $this->conn->prepare($query);
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

        public function getDailyOrderNet () {
            $query = 'SELECT net FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid"';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($net);
                    $sales = 0;
                    while ($stmt->fetch()) {
                        $sales += $net;
                    }
                    echo '₱'.number_format($sales).'.00';
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getDailyOrderGross () {
            $query = 'SELECT gross FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 1';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($gross);
                    $sales = 0;
                    while ($stmt->fetch()) {
                        $sales += $gross;
                    }
                    echo '₱'.number_format($sales).'.00';
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getDailyPOSNet () {
            $query = 'SELECT net FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 2';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($net);
                    $sales = 0;
                    while ($stmt->fetch()) {
                        $sales += $net;
                    }
                    echo '₱'.number_format($sales).'.00';
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getDailyPOSGross () {
            $query = 'SELECT gross FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 2';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($gross);
                    $sales = 0;
                    while ($stmt->fetch()) {
                        $sales += $gross;
                    }
                    echo '₱'.number_format($sales).'.00';
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