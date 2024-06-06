<?php
    require_once 'session.php';
    require_once 'model/admin/admin.php';

    class Manage extends Admin {
        public function addExpenses ($description, $amount, $user_id) {
            $query = 'INSERT INTO expenses
                        (description, amount, date, user_id)
                    VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $timestamp = time();
            $stmt->bind_param('siii', $description, $amount, $timestamp, $user_id);

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

        public function showExpenses () {
            $query = 'SELECT description, amount, date FROM expenses';
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($description, $amount, $date);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<tr>
                                <td>'.ucfirst($description).'</td>
                                <td>₱'.number_format($amount, 2).'</td>
                                <td>'.date('F j, Y', $date).'</td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }
    }
?>