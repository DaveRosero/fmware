<?php
    require_once 'model/database/database.php';

    class User{
        protected $conn;
        public function __construct () {
            $this->conn = database();
        }

        public function isLoggedIn () {
            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                return true;
            } else {
                return false;
            }
        }

        public function getUser ($email) {
            $query = 'SELECT id, firstname, lastname, email, password, phone, sex
                    FROM user
                    WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('s', $email);
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $fname, $lname, $newEmail, $password, $phone, $sex);
                    $stmt->fetch();
                    $stmt->close();

                    return [
                        'id' => $id,
                        'fname' => $fname,
                        'lname' => $lname,
                        'email' => $newEmail,
                        'password' => $password,
                        'phone' => $phone,
                        'sex' => $sex
                    ];
                } else {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $stmt->close();
                    return null;
                }
            } else {
                echo "Error preparing statement: " . $this->conn->error;
                return null;
            }
        }

        public function getUserGroup ($id) {
            $query = 'SELECT groups.name
                    FROM user_group
                    INNER JOIN groups ON user_group.group_id = groups.id
                    WHERE user_group.user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($group_name);
                    $stmt->fetch();
                    $stmt->close();

                    return $group_name;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            }else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }
    }
?>