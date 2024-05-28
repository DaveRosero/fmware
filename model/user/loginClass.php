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
            $json = array();

            $query = 'SELECT id, email, password, active, code
                    FROM user
                    WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $_POST['email']);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $email, $hash, $active, $code);
                    $stmt->fetch();
                    $stmt->close();

                    if (password_verify($_POST['password'], $hash)) {
                        $group_name = $this->getUserGroup($id);
                        $_SESSION['user_id'] = $id;
                        
                        if ($active == 0 && $code == NULL) {
                            $json['login_feedback'] = 'Your account has been disabled.';
                        } else if ($active == 0) {
                            $json['login_feedback'] = 'Your account is not yet verified. Please click the link sent to your email address to verify.';
                        } else {
                            $_SESSION['email'] = $email;
                            if ($group_name == 'user'){
                                $json['redirect'] = '/';
                            } 
                            
                            if ($group_name == 'Delivery Rider') {
                                $json['redirect'] = '/scan-qr';
                            } 
    
                            if ($group_name == 'admin') {
                                $json['redirect'] = '/dashboard';
                            }

                            if ($group_name == 'cashier') {
                                $json['redirect'] == '/pos';
                            }
                        }

                        echo json_encode($json);
                    } else {
                        $json['login_feedback'] = 'Invalid Email or Password. Please try again.';
                        echo json_encode($json);
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