<?php
    require_once 'model/admin/admin.php';
    require_once 'model/admin/logsClass.php';

    class Staff extends Admin {
        public function getStaffList () {
            $query = 'SELECT 
                        user.id, user.firstname, user.lastname, user.email, user.phone, user.active,
                        user_group.user_id, user_group.group_id, groups.name, user.date
                    FROM user
                    INNER JOIN user_group ON user_group.user_id = user.id
                    INNER JOIN groups ON user_group.group_id = groups.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result(
                        $id, $fname, $lname, $email, $phone, $active,
                        $user_id, $group_id, $group_name, $date
                    );

                    while ($stmt->fetch()) {
                        if ($group_name === 'user' || $group_name === 'admin') {
                            continue;
                        }

                        if ($active == 1) {
                            $status = '<div class="form-check form-switch d-flex justfiy-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                            checked
                                        >
                                    </div>';
                        } else {
                            $status = '<div class="form-check form-switch justify-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                        >
                                    </div>';
                        }

                        $name = $fname . ' ' . $lname;
                        echo '<tr>
                                <td class="text-center">'.$status.'</td>
                                <td class="text-center">'.$name.'</td>
                                <td class="text-center">'.$email.'</td>
                                <td class="text-center">'.$phone.'</td>
                                <td class="text-center">'.$date.'</td>
                                <td class="text-center">'.ucfirst($group_name).'</td>
                            </tr>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getStaff ($email) {
            $query = 'SELECT id, firstname, lastname, email, password, phone, date
                    FROM user
                    WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('s', $email);
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $fname, $lname, $newEmail, $password, $phone, $date);
                    $stmt->fetch();
                    $stmt->close();

                    return [
                        'id' => $id,
                        'fname' => $fname,
                        'lname' => $lname,
                        'email' => $newEmail,
                        'password' => $password,
                        'phone' => $phone,
                        'date' => $date
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

        public function getStaffbyId ($id) {
            $query = 'SELECT id, firstname, lastname, email, password, phone
                    FROM user
                    WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $fname, $lname, $newEmail, $password, $phone);
                    $stmt->fetch();
                    $stmt->close();

                    return [
                        'id' => $id,
                        'fname' => $fname,
                        'lname' => $lname,
                        'email' => $newEmail,
                        'password' => $password,
                        'phone' => $phone
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

        public function addStaffGroup ($id, $group) {
            $query = 'INSERT INTO user_group
                        (user_id, group_id)
                    VALUES (?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $id, $group);
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

        public function getPositions () {
            $query = 'SELECT id, name FROM groups';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) {
                        if ($name === 'admin' || $name === 'user') {
                            continue;
                        }
                        echo '<option value="'.$id.'">'.ucfirst($name).'</option>';
                    }
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

        public function accountExist ($email) {
            $query = 'SELECT COUNT(*) FROM user WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $email);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();

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

        public function isAlphanumeric ($password) {
            return preg_match('/[a-zA-Z]/', $password) && preg_match('/[0-9]/', $password);
        }

        public function checkPasswordLength ($password) {
            return strlen($password) >= 8;
        }

        public function regStaff () {
            $logs = new Logs();

            $fname = ucfirst($_POST['fname']);
            $lname = ucfirst($_POST['lname']);
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            $phone = $_POST['phone'];
            $group = $_POST['group'];
            $active = 1;

            if ($this->accountExist($email)){ 
                return 'Account with this email already exist';
            }

            if (!$this->checkPasswordLength($password)) {
                return 'Password must be 8 characters long';
            }

            if (!$this->isAlphanumeric($password)) {
                return 'Password must consist of letters and numbers';
            }

            if ($password !== $confirm) {
                return 'Password does not match';
            }

            if (substr($phone, 0, 2) !== '09' && strlen($phone) !== 11) {
                return 'Mobile number must be 09XXXXXXXXX';
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $date = date('F j, Y');

            $query = 'INSERT INTO user
                        (firstname, lastname, email, password, phone, date, active)
                    VALUES (?,?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ssssssi', $fname, $lname, $email, $hashedPassword, $phone, $date, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $staff = $this->getStaff($email);
                    $this->addStaffGroup($staff['id'], $group);
                    
                    $description_log = 'Added staff '.ucfirst($staff['fname']).' '.ucfirst($staff['lname']);
                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($description_log, $_SESSION['user_id'], $date_log);

                    return '/staff';
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function updateStaff ($active, $id) {
            $logs = new Logs();

            $query = 'UPDATE user SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $staff = $this->getStaffbyId($id);

                    if ($active == 1) {
                        $action_log = 'Enable staff account '.$staff['fname'].' '.$staff['lname'];
                    } else {
                        $action_log = 'Disable staff account '.$staff['fname'].' '.$staff['lname'];
                    }

                    $date_log = date('F j, Y g:i A');
                    $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                    return '/staff';
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