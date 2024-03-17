<?php
    require_once 'model/user/user.php';

    class Register extends User {
        public function newUser ($id) {
            $conn = $this->getConnection();
            $group_id = 2;

            $query = 'INSERT INTO user_group
                        (user_id, group_id)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $id, $group_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }           
        }

        public function register () {
            $conn = $this->getConnection();

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $sex = $_POST['sex'];
            
            $query = 'INSERT INTO user
                        (firstname, lastname, email, password, phone, sex)
                    VALUES (?,?,?,?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssi', $fname, $lname, $email, $password, $phone, $sex);
            if ($stmt) {
                if ($stmt->execute()) {
                    $user = $this->getUser($email);
                    if ($this->newUser($user['id'])) {
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: /fmware');
                    } else {
                        die("Error in executing statement: " . $stmt->error);
                        $stmt->close();
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>