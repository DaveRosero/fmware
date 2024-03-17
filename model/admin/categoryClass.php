<?php
    require_once 'model/admin/admin.php';

    class Category extends Admin {
        public function newCategory() {
            $conn = $this->getConnection();
            $name = $_POST['category_name'];
            $active = 1;
            $query = 'INSERT INTO category
                        (name, active)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $name, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /fmware/category');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getCategory () {
            $conn = $this->getConnection();

            $query = 'SELECT name, active FROM category';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($name, $active);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = 'ACTIVE';
                        } else {
                            $status = 'INACTIVE';
                        }
                        echo '<tr>
                                <td>'.$name.'</td>
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
    }
?>