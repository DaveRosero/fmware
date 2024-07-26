<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';

    class UserList extends Admin {
        public function getUserList () {
            $query = 'SELECT 
                        user.id, user.firstname, user.lastname, user.email, user.phone,
                        user_group.user_id, user_group.group_id, groups.name, user.date, user.active,
                        (SELECT MAX(logs.date)
                        FROM logs
                        WHERE logs.user_id = user.id) AS last_login
                    FROM user
                    INNER JOIN user_group ON user_group.user_id = user.id
                    INNER JOIN groups ON user_group.group_id = groups.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result(
                        $id, $fname, $lname, $email, $phone,
                        $user_id, $group_id, $group_name, $date, $active, $last_login
                    );

                    while ($stmt->fetch()) {
                        if ($group_name !== 'user') {
                            continue;
                        }

                        if ($active == 1) {
                            $switch_btn = '<div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                            checked
                                        >
                                    </div>';
                        } else {
                            $switch_btn = '<div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                        >
                                    </div>';
                        }

                        $name = $fname . ' ' . $lname;
                        echo '<tr>
                                <td class="text-center">'.$switch_btn.'</td>
                                <td class="text-center">'.$name.'</td>
                                <td class="text-center">'.$email.'</td>
                                <td class="text-center">'.$phone.'</td>
                                <td class="text-center">'.$date.'</td>
                                <td class="text-center">'.$last_login.'</td>
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

        public function updateUserStatus ($user_id, $status) {
            $query = 'UPDATE user SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $status, $user_id);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->close();

            $json = array(
                'redirect' => '/users',
                'status' => $status
            );
            echo json_encode($json);
            return;
        }
    }
?>