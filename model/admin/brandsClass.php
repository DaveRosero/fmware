<?php
    require_once 'model/admin/admin.php';

    class Brands extends Admin {
        public function newBrand() {
            $conn = $this->getConnection();
            $name = $_POST['brand_name'];
            $active = 1;
            $query = 'INSERT INTO brand
                        (name, active)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $name, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /fmware/brands');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getBrands () {
            $conn = $this->getConnection();

            $query = 'SELECT name, active FROM brand';
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