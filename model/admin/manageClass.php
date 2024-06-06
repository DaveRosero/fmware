<?php
    require_once 'session.php';
    require_once 'model/admin/admin.php';

    class Manage extends Admin {
        public function addExpenses ($description, $amount, $user_id) {
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
                                <td>'.$date.'</td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function getEmployeeIds () {
            $ids = array();
            $query = 'SELECT user_id FROM user_group WHERE group_id = 3 OR group_id = 4';
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($user_id);
            while ($stmt->fetch()) {
                $ids[] = $user_id;
            }
            $stmt->close();
            return $ids;
        }

        public function isPaid ($id) {
            $query = 'SELECT COUNT(*) FROM expenses WHERE user_id = ? AND date = ?';
            $stmt = $this->conn->prepare($query);
            $date = date('F j, Y');
            $stmt->bind_param('is', $id, $date);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function showEmployees () {
            $ids = $this->getEmployeeIds();
            $content = '';
            foreach ($ids as $id) {
                if ($this->isPaid($id)) {
                    $status = '<span class="badge bg-success text-wrap">PAID</span>';
                } else {
                    $status = '<span class="badge bg-danger text-wrap">UNPAID</span>';
                }
                $query = 'SELECT user.firstname,
                                user.lastname,
                                groups.name,
                                daily_wage.amount
                        FROM user
                        INNER JOIN user_group ON user_group.user_id = user.id
                        INNER JOIN groups ON groups.id = user_group.group_id
                        INNER JOIN daily_wage ON daily_wage.user_id = user.id
                        WHERE user.id = ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i', $id);

                if (!$stmt) {
                    die("Error in preparing statement: " . $this->conn->error);
                }
                
                if (!$stmt->execute()) {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }

                $stmt->bind_result($fname, $lname, $group, $wage);
                while ($stmt->fetch()) {
                    $content .= '<tr>
                                    <td>'.ucfirst($fname).' '.ucfirst(substr($lname, 0, 1)).'.</td>
                                    <td>'.ucfirst($group).'</td>
                                    <td>₱'.number_format($wage, 2).'</td>
                                    <td>'.$status.'</td>
                                </tr>';
                }
                $stmt->close();
            }
            echo $content;
            return;
        }

        public function showEmployeesModal () {
            $ids = $this->getEmployeeIds();
            $content = '';
            foreach ($ids as $id) {
                if ($this->isPaid($id)) {
                    continue;
                }
                $query = 'SELECT user.firstname,
                                user.lastname,
                                groups.name,
                                daily_wage.amount
                        FROM user
                        INNER JOIN user_group ON user_group.user_id = user.id
                        INNER JOIN groups ON groups.id = user_group.group_id
                        INNER JOIN daily_wage ON daily_wage.user_id = user.id
                        WHERE user.id = ?';
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i', $id);

                if (!$stmt) {
                    die("Error in preparing statement: " . $this->conn->error);
                }
                
                if (!$stmt->execute()) {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }

                $stmt->bind_result($fname, $lname, $group, $wage);
                while ($stmt->fetch()) {
                    $content .= '<div class="row mb-3">
                                    <div class="text-start">
                                        <input type="checkbox" class="form-check-input" id="description" name="staff[]" value="'.$id.'">
                                        <label for="description" class="fw-semibold fs-6 mx-2">
                                        '.$fname.' '.substr($lname, 0, 1).'. - '.ucfirst($group).' - ₱'.number_format($wage, 2).'
                                        </label>
                                    </div>
                                </div>';
                }
                $stmt->close();
            }
            echo $content;
            return;
        }

        public function getWage ($id) {
            $query = 'SELECT daily_wage.amount,
                            user.firstname,
                            user.lastname 
                    FROM daily_wage
                    INNER JOIN user ON user.id = daily_wage.user_id 
                    WHERE daily_wage.user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->bind_result($wage, $fname, $lname);
            $stmt->fetch();
            $stmt->close();
            $name = ucfirst($fname).' '.ucfirst(substr($lname, 0, 1)).'.';
            return [
                'wage' => $wage,
                'name' => $name
            ];
        }

        public function addWageToExpense ($id, $wage, $name) {
            $query = 'INSERT INTO expenses
                        (description, amount, date, user_id)
                    VALUES (?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $description = 'Daily Wage - '.$name;
            $date = date('F j, Y');
            $stmt->bind_param('sdsi', $description, $wage, $date, $id);

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

        public function payEmployees ($ids) {
            foreach ($ids as $id) {
                $staff = $this->getWage($id);
                $this->addWageToExpense($id, $staff['wage'], $staff['name']);
            }
            
            $json = array(
                'redirect' => '/manage-business'
            );
            echo json_encode($json);
            return;
        }
    }
?>