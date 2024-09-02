<?php
require_once 'model/database/database.php';

class Admin
{
    protected $conn;
    public function __construct()
    {
        $this->conn = database();
    }

    public function isAdmin()
    {
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

                if ($group_name !== 'admin') {
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

    public function isDelivery()
    {
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

                if ($group_name == 'delivery') {
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

    public function getTotalProducts()
    {
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

    public function getTotalSuppliers()
    {
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

    public function getTotalUsers()
    {
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

    public function getDailyOrderNet()
    {
        $query = 'SELECT net FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid"';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($net);
                $sales = 0;
                while ($stmt->fetch()) {
                    $sales += $net;
                }
                echo '₱' . number_format($sales) . '.00';
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getDailyOrderGross()
    {
        $query = 'SELECT gross FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 1';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($gross);
                $sales = 0;
                while ($stmt->fetch()) {
                    $sales += $gross;
                }
                echo '₱' . number_format($sales) . '.00';
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getDailyPOSNet()
    {
        $query = 'SELECT net FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 2';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($net);
                $sales = 0;
                while ($stmt->fetch()) {
                    $sales += $net;
                }
                echo '₱' . number_format($sales) . '.00';
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getDailyPOSGross()
    {
        $query = 'SELECT gross FROM orders WHERE DATE(date) = CURDATE() AND paid = "paid" AND transaction_type_id = 2';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($gross);
                $sales = 0;
                while ($stmt->fetch()) {
                    $sales += $gross;
                }
                echo '₱' . number_format($sales) . '.00';
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getPendingPO()
    {
        $query = 'SELECT COUNT(*) FROM purchase_order WHERE status = 1';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="dropdown-divider"></div> 
                        <a href="/purchase-orders" class="dropdown-item">
                            <i class="bi bi-clock-fill me-2"></i>
                            Pending Purchase Orders
                            <span class="float-end text-secondary fs-7">' . $count . '</span> </a>
                        </a>';
        }

        return;
    }

    public function getLowStocks()
    {
        $query = 'SELECT COUNT(*) FROM stock WHERE qty <= critical_level';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="dropdown-divider"></div> 
                        <a href="/manage-products" class="dropdown-item">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Low on Stock
                            <span class="float-end text-secondary fs-7">' . $count . '</span> </a>
                        </a>';
        }
    }

    public function getPendingOrders()
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE status = "pending"';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="dropdown-divider"></div> 
                        <a href="/manage-orders" class="dropdown-item">
                            <i class="bi bi-hourglass-split me-2"></i>
                            Pending Online Orders
                            <span class="float-end text-secondary fs-7">' . $count . '</span> </a>
                        </a>';
        }
    }

    public function getToPayOrders()
    {
        $query = 'SELECT COUNT(*) FROM orders WHERE status = "to pay"';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<div class="dropdown-divider"></div> 
                        <a href="/manage-orders" class="dropdown-item">
                            <i class="bi bi-credit-card-fill me-2"></i>
                            To Pay Online Orders
                            <span class="float-end text-secondary fs-7">' . $count . '</span> </a>
                        </a>';
        }
    }
}
