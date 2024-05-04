<?php
    require_once 'model/database/database.php';

    class Home{
        protected $conn;
        public function __construct () {
            $this->conn = database();
        }

        public function redirectUser() {
            if (isset($_SESSION['user_id'])) {
                $id = $_SESSION['user_id'];
            } else {
                return null;
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

                    if ($group_name === 'user') {
                        header('Location: /');
                        return;
                    }

                    if ($group_name === 'admin') {
                        header('Location: /dashboard');
                        return;
                    }

                    if ($group_name === 'cashier') {
                        header('Location: /pos');
                        return;
                    }

                    if ($group_name === 'delivery') {
                        header('Location: /scan-qr');
                        return;
                    }

                    return null;
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