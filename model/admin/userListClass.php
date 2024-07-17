<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';

    class UserList extends Admin {
        public function getUserList () {
            $query = 'SELECT 
                        user.id, user.firstname, user.lastname, user.email, user.phone,
                        user_group.user_id, user_group.group_id, groups.name, user.date, user.active
                    FROM user
                    INNER JOIN user_group ON user_group.user_id = user.id
                    INNER JOIN groups ON user_group.group_id = groups.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result(
                        $id, $fname, $lname, $email, $phone,
                        $user_id, $group_id, $group_name, $date, $active
                    );

                    while ($stmt->fetch()) {
                        if ($group_name !== 'user') {
                            continue;
                        }

                        if ($active == 1) {
                            $switch_btn = '<div class="form-check form-switch">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                            checked
                                        >
                                    </div>';
                        } else {
                            $switch_btn = '<div class="form-check form-switch">
                                        <input class="form-check-input status" 
                                            type="checkbox" 
                                            id="toggleSwitch"
                                            data-user-id='.$user_id.'
                                        >
                                    </div>';
                        }

                        $name = $fname . ' ' . $lname;
                        echo '<tr>
                                <td>'.$switch_btn.'</td>
                                <td>'.$name.'</td>
                                <td>'.$email.'</td>
                                <td>'.$phone.'</td>
                                <td>'.$date.'</td>
                                <td></td>
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
    }
?>