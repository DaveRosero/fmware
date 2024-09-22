<?php
require_once 'model/user/user.php';
require_once 'model/user/registerClass.php';
require_once 'vendor/PHPMailer/src/PHPMailer.php';
require_once 'vendor/PHPMailer/src/SMTP.php';
require_once 'vendor/PHPMailer/src/Exception.php';
require_once 'model/admin/logsClass.php';
require_once 'config/load_env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Login extends User
{
    public function verifyCaptcha($captcha)
    {
        $secret = "6LfXNBMlAAAAACUbJ5PhZSx59hZpJecKV-DYx9-P";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = $captcha;
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$ip";
        $fire = file_get_contents($url);
        $data = json_decode($fire);

        return $data->success;
    }

    public function setCodeNull($email)
    {
        $query = 'UPDATE user SET code = NULL WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                return;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getAttempts($email)
    {
        $query = 'SELECT attempts FROM user WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($attempts);
        $stmt->fetch();
        $stmt->close();

        return $attempts;
    }

    public function minusAttempt($email)
    {
        $attempts = $this->getAttempts($email);
        if ($attempts === NULL) {
            $newAttempts = 5;
        } else {
            $newAttempts = $attempts - 1;
        }

        $query = 'UPDATE user SET attempts = ? WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('is', $newAttempts, $email);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        return;
    }

    public function setNullAttempt($email)
    {
        $query = 'UPDATE user SET attempts = NULL WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        return;
    }

    public function lockAccount($email)
    {
        $active = 0;
        $query = 'UPDATE user SET active = ? WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('is', $active, $email);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        return;
    }

    public function activateAccount($email)
    {
        $active = 1;
        $query = 'UPDATE user SET active = ? WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('is', $active, $email);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        return;
    }

    public function login()
    {
        $json = array();
        $logs = new Logs();

        $query = 'SELECT id, email, password, active, code, attempts
                    FROM user
                    WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $_POST['email']);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $email, $hash, $active, $code, $attempts);
                $stmt->fetch();
                $stmt->close();

                if ($attempts == 0 && $attempts !== NULL) {
                    $this->lockAccount($email);
                    $json['login_feedback'] = 'Your account has been disabled indefinitely due to security reason. Please change your password using forgot password.';
                    echo json_encode($json);
                    return;
                }

                if (password_verify($_POST['password'], $hash)) {
                    $group_name = $this->getUserGroup($id);

                    if ($active == 0 && $code == NULL) {
                        $json['login_feedback'] = 'Your account has been disabled.';
                        echo json_encode($json);
                        return;
                    }

                    if ($active == 0 && $code !== NULL) {
                        $json['login_feedback'] = 'Your account is not yet verified. Please click the link sent to your email address to verify.';
                        echo json_encode($json);
                        return;
                    }

                    if ($active == 1 && $code !== NULL) {
                        $this->setCodeNull($email);
                    }

                    $_SESSION['email'] = $email;
                    $_SESSION['user_id'] = $id;
                    $_SESSION['group'] = $group_name;
                    $this->setNullAttempt($email);

                    switch ($group_name) {
                        case 'user':
                            $json['redirect'] = '/';
                            $action_log = 'Customer Login';
                            break;
                        case 'delivery':
                            $json['redirect'] = '/rider-order';
                            $action_log = 'Login to delivery';
                            break;
                        case 'admin':
                            $json['redirect'] = '/dashboard';
                            $action_log = 'Login to dashboard';
                            break;
                        default:
                            $json['redirect'] = '/pos';
                            $action_log = 'Login to point of sale';
                            break;
                    }

                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    echo json_encode($json);
                    return;
                } else {
                    $this->minusAttempt($email);
                    $attempts = $this->getAttempts($email);
                    $json['login_feedback'] = 'Invalid Email or Password. Remaining attempts: ' . $attempts;
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

    public function userExist($email)
    {
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
                } else {
                    return false;
                }
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function updateCode($email, $code)
    {
        $query = 'UPDATE user SET code = ? WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $code, $email);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                return;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function sendResetLink($email)
    {
        loadEnv('.env');
        $smtp_password = getenv('SMTP_PASSWORD');

        $code = bin2hex(random_bytes(50));
        $this->updateCode($email, $code);
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.hostinger.com'; // Specify SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'no-reply@fmware.shop'; // SMTP username
        $mail->Password = $smtp_password; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@fmware.shop', 'FMWare');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'FMWare Reset Password';

        // Email body with the confirmation link
        $mail->Body = '<h3>To reset your password for your FMWare account, click the link below.</h3>
                           <p><a href="fmware.shop/reset-password/' . $code . '/' . $email . '">Reset Password</a></p>
                           <p>If you did not requested this action, you can ignore this email.</p>';

        // Send the email
        try {
            $mail->send();
            return true; // Email sent successfully
        } catch (Exception $e) {
            return false; // Failed to send email
        }
    }

    public function forgotPassword($email)
    {
        if (!$this->userExist($email)) {
            $json['forgot_feedback'] = 'This email is not yet registered to an account.';
            echo json_encode($json);
            return;
        }

        if ($this->sendResetLink($email)) {
            $json['forgot_success'] = 'A verification email has been sent. Please check your inbox.';
            echo json_encode($json);
            return;
        }
    }

    public function verifyCode($url_email, $url_code)
    {
        $query = 'SELECT email, code FROM user WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $url_email);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($user_email, $user_code);
                $stmt->fetch();
                $stmt->close();

                if ($user_email === $url_email && $user_code === $url_code) {
                    return true;
                } else {
                    return false;
                }
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function resetPassword($email, $password, $confirm)
    {
        $register = new Register();

        if (!$register->checkPasswordLength($password)) {
            $json['reset_feedback'] = 'Password must be 8 characters';
            echo json_encode($json);
            return;
        }

        if (!$register->isAlphanumeric($password)) {
            $json['reset_feedback'] = 'Password must consist of letters and numbers';
            echo json_encode($json);
            return;
        }

        if ($password !== $confirm) {
            $json['reset_feedback'] = 'Password does not match.';
            echo json_encode($json);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = 'UPDATE user SET password = ? WHERE email = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $hashedPassword, $email);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();
                $this->setCodeNull($email);
                $this->setNullAttempt($email);
                $this->activateAccount($email);
                $json['redirect'] = '/login';
                echo json_encode($json);
                return;
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }
}
