<?php
    require_once 'model/admin/admin.php';

    class Group extends Admin{
        public function getGroups(){
            $conn = $this->getConnection();

            $query = 'SELECT name, permission, active
                    FROM groups';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if($stmt->execute()){
                    $stmt->bind_result($name, $perms, $active);
                    while ($stmt->fetch()) {
                        if ($active == 0) {
                            $status = 'INACTIVE';
                        }else{
                            $status = 'ACTIVE';
                        }

                        echo '<tr>
                                <td>'.$name.'</td>
                                <td style="max-width: 200px;">'.$perms.'</td>
                                <td>'.$status.'</td>
                                <td>
                                    <a class="text-success mx-2" href="#"><i class="fa-solid fa-pen-to-square"></i><a/>
                                    <a class="text-danger" href="#"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>';
                    }
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function createGroup() {
            $conn = $this->getConnection();

            $group_name = $_POST['group_name'];
            $permsArray = $_POST['permissions'];
            $perms = implode(', ', $permsArray);
            $active = 1;
            $query = 'INSERT INTO groups
                        (name, permission, active)
                    VALUES (?,?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssi', $group_name, $perms, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    header('Location: /fmware/groups');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function displayGroups() {
            $conn = $this->getConnection();

            $query = 'SELECT id, name FROM groups';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) {
                        echo '<option value="'.$id.'">'.$name.'</option>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>