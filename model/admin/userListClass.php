<?php
    require_once 'model/admin/admin.php';

    class User extends Admin {
        public function getUserList () {
            $query = 'SELECT 
                        user.id, user.firstname, user.lastname, user.email, user.phone, user.sex,
                        user_group.user_id, user_group.group_id, groups.name
                    FROM user
                    INNER JOIN user_group ON user_group.user_id = user.id
                    INNER JOIN groups ON user_group.group_id = groups.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result(
                        $id, $fname, $lname, $email, $phone, $sex,
                        $user_id, $group_id, $group_name
                    );

                    while ($stmt->fetch()) {
                        if ($group_name !== 'user') {
                            continue;
                        }
                        $name = $fname . ' ' . $lname;
                        if ($sex == 0){
                            $sex_value = 'Male';
                        } else {
                            $sex_value = 'Female';
                        }
                        echo '<tr>
                                <td>'.$name.'</td>
                                <td>'.$email.'</td>
                                <td>'.$phone.'</td>
                                <td>'.$sex_value.'</td>
                                <td>'.$group_name.'</td>
                                <td>
                                    <a class="text-success mx-2" href="#"><i class="fa-solid fa-pen-to-square"></i><a/>
                                    <a class="text-danger" href="#"><i class="fa-solid fa-trash"></i></a>
                                </td>
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