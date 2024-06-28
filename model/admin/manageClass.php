<?php
    include_once 'session.php';
    require_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

    class Manage extends Admin {
        public function addExpenses ($description, $amount, $user_id) {
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
            
            $action_log = 'Added expense '.$description.' amount ₱'.number_format($amount, 2);
            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

            $json = array(
                'redirect' => '/manage-business'
            );
            echo json_encode($json);
            return;
        }

        public function showExpenses () {
            $query = 'SELECT description, amount, date FROM expenses ORDER BY date ASC';
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
                                <td>'.$date.'</td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function showDeliveryFee () {
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
                                ? '<div class="form-check form-switch">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-df-id='.$id.'
                                            checked
                                        >
                                    </div>'
                                : '<div class="form-check form-switch">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-df-id='.$id.'
                                        >
                                    </div>';

                $content .= '<tr>
                                <td>'.$status.'</td>
                                <td>'.ucfirst($municipality).'</td>
                                <td>₱'.number_format($delivery_fee, 2).'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#df-modal"
                                        data-df-id='.$id.'
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </td>
                            </tr>';
            }

            $stmt->close();
            echo $content;
            return;
        }

        public function getDeliveryFee ($id) {
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

        public function updateDeliveryFee ($df, $municipality) {
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
            
            $action_log = 'Update Delivery Fee of '.ucfirst($municipality).' from ₱'.number_format($old_df, 2).' to ₱'.number_format($df, 2);
            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);
            
            $json = array(
                'redirect' => '/manage-business'
            );
            echo json_encode($json);
            return;
        }

        public function updateDeliveryFeeStatus ($active, $id) {
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

        public function getOldFee ($municipality) {
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
    }
?>