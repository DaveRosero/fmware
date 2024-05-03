<?php
    require_once 'model/user/user.php';
    require_once 'vendor/PHPMailer/src/PHPMailer.php';
    require_once 'vendor/PHPMailer/src/SMTP.php';
    require_once 'vendor/PHPMailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Register extends User {
        public function newUser ($id) {
            $group_id = 2;

            $query = 'INSERT INTO user_group
                        (user_id, group_id)
                    VALUES (?,?)';
            $stmt = $this->conn->prepare($query);
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
                die("Error in preparing statement: " . $this->conn->error);
            }           
        }

        public function generateLink () {
            $code = bin2hex(random_bytes(50));
            $link = 'fmware.shop/verify-account/'.$code;
            return [
                'code' => $code,
                'link' => $link
            ];
        }
        
        public function sendConfirmationEmail($email, $name, $link) {
            $mail = new PHPMailer(true);
        
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify SMTP server
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = '21shingie@gmail.com'; // SMTP username
            $mail->Password = 'mboi hjvb lbud zkrk'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to
        
            // Recipients
            $mail->setFrom('21shingie@gmail.com', 'FMWare');
            $mail->addAddress($email, $name);
        
            // Email content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'FMWare Account Verification';
        
            // Email body with the confirmation link
            $mail->Body = '<h1>Hello, ' . $name . '</h1>
                           <p>Thank you for signing up with FMWare! Please click the following link to verify your account:</p>
                           <p><a href="' . $link . '">Verify Account</a></p>
                           <p>If you did not sign up for an account with FMWare, you can ignore this email.</p>';
        
            // Send the email
            try {
                $mail->send();
                return true; // Email sent successfully
            } catch (Exception $e) {
                return false; // Failed to send email
            }
        }
        
        public function accountExist ($email) {
            $query = 'SELECT COUNT(*) FROM user WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $email);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    if ($count > 0) {
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

        public function checkPasswordLength ($password) {
            return strlen($password) === 8;
        }

        public function isAlphanumeric ($password) {
            return preg_match('/[a-zA-Z]/', $password) && preg_match('/[0-9]/', $password);
        }

        public function register () {
            if ($this->accountExist($_POST['email'])) {
                $json =  array(
                    'exist' => 'This email is registered to an existing account'
                );
                echo json_encode($json);
                return;
            }

            if (!$this->checkPasswordLength($_POST['password'])) {
                $json = array(
                    'password' => 'Password must be 8 characters'
                );
                echo json_encode($json);
                return;
            }

            if (!$this->isAlphanumeric($_POST['password'])) {
                $json = array(
                    'password' => 'Password must consist of letters and numbers'
                );
                echo json_encode($json);
                return;
            }

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $sex = $_POST['sex'];
            $verify = $this->generateLink();
            $active = 0;

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $query = 'INSERT INTO user
                        (firstname, lastname, email, password, phone, sex, code, active)
                    VALUES (?,?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssssisi', $fname, $lname, $email, $hashedPassword, $phone, $sex, $verify['code'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $name = $fname.' '.$lname;
                    $user = $this->getUser($email);
                    $this->newUser($user['id']);
                    $this->sendConfirmationEmail($email, $name, $verify['link']);
                    $_SESSION['user_id'] = $user['id'];
                    $json = array(
                        'verify' => 'Confirmation Email Sent',
                        'redirect' => '/login'
                    );
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function verifyAccount($id) {
            $query = 'UPDATE user SET code = NULL, active = 1 WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
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

        public function verifyCode ($code, $id) {
            $query = 'SELECT code FROM user WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($user_code);
                    $stmt->fetch();
                    $stmt->close();

                    if ($code === $user_code) {
                        $this->verifyAccount($id);
                        header('Location: /login');
                    } else {
                        header('Location: /404');
                        exit();
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