<?php
    require_once 'model/admin/admin.php';

    class UserList extends Admin {
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

        public function getStaffList () {
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
                        if ($group_name == 'user') {
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

        public function getStaff ($email) {
            $query = 'SELECT id, firstname, lastname, email, password, phone, sex
                    FROM user
                    WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param('s', $email);
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $fname, $lname, $newEmail, $password, $phone, $sex);
                    $stmt->fetch();
                    $stmt->close();

                    return [
                        'id' => $id,
                        'fname' => $fname,
                        'lname' => $lname,
                        'email' => $newEmail,
                        'password' => $password,
                        'phone' => $phone,
                        'sex' => $sex
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

        public function addStaff ($id, $group_id) {
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

        public function newStaff () {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $sex = $_POST['sex'];
            $group = $_POST['group'];

            $query = 'INSERT INTO user
                        (firstname, lastname, email, password, phone, sex)
                    VALUES (?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssssi', $fname, $lname, $email, $password, $phone, $sex);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $staff = $this->getStaff($email);
                    if ($this->addStaff($staff['id'], $group)) {
                        return '/staff';
                    } else {
                        die("Error in executing statement: " . $stmt->error);
                        $stmt->close();
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