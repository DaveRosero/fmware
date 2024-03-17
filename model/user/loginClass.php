<?php
    require_once 'model/user/user.php';

    class Login extends User {
        public function verifyCaptcha ($captcha) {
            $secret = "6LfXNBMlAAAAACUbJ5PhZSx59hZpJecKV-DYx9-P";
            $ip = $_SERVER['REMOTE_ADDR'];
            $response = $captcha;
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$ip";
            $fire = file_get_contents($url);
            $data = json_decode($fire);
    
            return $data->success;
        }

        public function login () {
            $conn = $this->getConnection();
            $query = 'SELECT id, email, password
                    FROM user
                    WHERE email = ?';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $_POST['email']);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $email, $password);
                    $stmt->fetch();
                    $stmt->close();

                    if ($email === $_POST['email'] && $password === $_POST['password']) {
                        $group_name = $this->getUserGroup($id);
                        $_SESSION['user_id'] = $id;
                        if ($group_name == 'user'){
                            header('Location: /fmware');
                        } else {
                            header('Location: /fmware/dashboard');
                        }
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