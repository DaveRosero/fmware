<?php
include_once 'session.php';
require_once 'model/admin/admin.php';
require_once 'model/admin/logsClass.php';
require_once 'vendor/PHPMailer/src/PHPMailer.php';
require_once 'vendor/PHPMailer/src/SMTP.php';
require_once 'vendor/PHPMailer/src/Exception.php';
require_once 'config/load_env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Manage extends Admin
{
    public function addExpenses($description, $amount, $user_id)
    {
        $logs = new Logs();

        $description = ucfirst($description);
        $query = 'INSERT INTO expenses
                        (description, amount, date, user_id)
                    VALUES (?,?,?,?)';
        $stmt = $this->conn->prepare($query);
        $date = date('F j, Y');
        $stmt->bind_param('sdsi', $description, $amount, $date, $user_id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $action_log = 'Added expense ' . $description . ' amount ₱' . number_format($amount, 2);
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array(
            'redirect' => '/manage-business'
        );
        echo json_encode($json);
        return;
    }

    public function showExpenses()
    {
        $query = 'SELECT id, description, amount, date FROM expenses ORDER BY date ASC';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $description, $amount, $date);
        $content = '';
        while ($stmt->fetch()) {
            $content .= '<tr>
                                <td class="text-center">' . ucfirst($description) . '</td>
                                <td class="text-center">₱' . number_format($amount, 2) . '</td>
                                <td class="text-center">' . $date . '</td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-sm btn-danger delete" 
                                        type="button"
                                        data-expense-id=' . $id . '
                                    >
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>';
        }
        $stmt->close();
        echo $content;
        return;
    }

    public function showDeliveryFee()
    {
        $query = 'SELECT id, municipality, delivery_fee, active FROM delivery_fee';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $municipality, $delivery_fee, $active);
        $content = '';
        while ($stmt->fetch()) {
            $status = $active == 1
                ? '<div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-df-id=' . $id . '
                                            checked
                                        >
                                    </div>'
                : '<div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-df-id=' . $id . '
                                        >
                                    </div>';

            $content .= '<tr>
                                <td class="text-center">' . $status . '</td>
                                <td class="text-center">' . ucfirst($municipality) . '</td>
                                <td class="text-center">₱' . number_format($delivery_fee, 2) . '</td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#df-modal"
                                        data-df-id=' . $id . '
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>';
        }

        $stmt->close();
        echo $content;
        return;
    }

    public function getDeliveryFee($id)
    {
        $query = 'SELECT municipality, delivery_fee FROM delivery_fee WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($municipality, $delivery_fee);
        $stmt->fetch();
        $stmt->close();
        $json = array(
            'municipal' => $municipality,
            'df' => $delivery_fee
        );
        echo json_encode($json);
    }

    public function updateDeliveryFee($df, $municipality)
    {
        $logs = new Logs();

        $old_df = $this->getOldFee($municipality);
        $query = 'UPDATE delivery_fee SET delivery_fee = ? WHERE municipality = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ds', $df, $municipality);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $action_log = 'Update Delivery Fee of ' . ucfirst($municipality) . ' from ₱' . number_format($old_df, 2) . ' to ₱' . number_format($df, 2);
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array(
            'redirect' => '/manage-business'
        );
        echo json_encode($json);
        return;
    }

    public function updateDeliveryFeeStatus($active, $id)
    {
        $query = 'UPDATE delivery_fee SET active = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $active, $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        $json = array(
            'redirect' => '/manage-business'
        );
        echo json_encode($json);
        return;
    }

    public function getOldFee($municipality)
    {
        $query = 'SELECT delivery_fee FROM delivery_fee WHERE municipality = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $municipality);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($df);
        $stmt->fetch();
        $stmt->close();
        return $df;
    }

    public function delExpense($id)
    {
        $logs = new Logs();

        $expense = $this->getExpenses($id);

        $query = 'DELETE FROM expenses WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $action_log = 'Deleted expense ' . $expense['description'] . ' amount ₱' . number_format($expense['amount'], 2);
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array(
            'redirect' => '/manage-business'
        );
        echo json_encode($json);
    }

    public function getExpenses($id)
    {
        $query = 'SELECT description, amount FROM expenses WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($description, $amount);
        $stmt->fetch();
        $stmt->close();
        return [
            'description' => $description,
            'amount' => $amount
        ];
    }

    public function getPIN()
    {
        $query = 'SELECT value FROM company WHERE category = "PIN"';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($current);
        $stmt->fetch();
        $stmt->close();
        return $current;
    }

    public function changePIN($old, $new)
    {
        $current = $this->getPIN();

        if ($current != $old) {
            $json = array(
                'invalid' => 'invalid'
            );
            echo json_encode($json);
            return;
        }

        $query = 'UPDATE company SET value = ? WHERE category = "PIN"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $new);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        $json = array(
            'success' => 'success'
        );
        echo json_encode($json);
        return;
    }

    public function resetPIN($email)
    {
        loadEnv('.env');
        $smtp_password = getenv('SMTP_PASSWORD');

        $mail = new PHPMailer(true);
        $pin = mt_rand(1000, 9999);

        $query = 'UPDATE company SET value = ? WHERE category = "PIN"';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $pin);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

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
        $mail->Subject = 'FMWare Account Verification';

        // Email body with the confirmation link
        $mail->Body = '<h1>Your PIN has been reset.</h1>
                           <p>This is now your current PIN:</p>
                           <h4>' . $pin . '</h4>
                           <p>We highly suggest that you change your PIN.</p>';

        // Send the email
        try {
            $mail->send();
            $json = array(
                'success' => 'success'
            );
            echo json_encode($json);
            return true; // Email sent successfully
        } catch (Exception $e) {
            return false; // Failed to send email
        }
    }

    public function getBarcodes()
    {
        $query = 'SELECT p.name, p.barcode, v.name, b.name, u.name, p.unit_value 
                FROM product p
                INNER JOIN variant v ON v.id = p.variant_id
                INNER JOIN brand b ON b.id = p.brand_id
                INNER JOIN unit u ON u.id = p.unit_id';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($name, $barcode, $variant, $brand, $unit, $unit_value);
        $content = '<div class="container-fluid">';
        $content .= '<div class="row">';
        while ($stmt->fetch()) {
            $content .= '<div class="col-md-4 text-center barcode-container mb-4">
                        <div class="barcode-name">' . htmlspecialchars($name . ' (' . $variant . ') ' . $unit_value . ' ' . strtoupper($unit)) . '</div>
                        <svg
                            class="barcode"
                            jsbarcode-format="code39"
                            jsbarcode-value="' . htmlspecialchars($barcode) . '"
                            jsbarcode-text-margin="0"
                            jsbarcode-fontoptions="bold"
                        >
                        </svg>
                    </div>';
        }
        $content .= '</div></div>';
        $stmt->close();

        echo $content;
    }
}
