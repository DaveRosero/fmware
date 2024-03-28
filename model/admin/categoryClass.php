<?php
    require_once 'model/admin/admin.php';

    class Category extends Admin {
        public function isCategoryExist ($category) {
            $query = 'SELECT COUNT(*) FROM category WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $category);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();

                    if ($count > 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newCategory () {
            $category = $_POST['category_name'];
            if ($this->isCategoryExist($category)) {
                $json = array('category_feedback' => 'Category already exist.');
                echo json_encode($json);
                return;
            }
            $active = 1;
            $query = 'INSERT INTO category
                        (name, user_id, active)
                    VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sii', $category, $_SESSION['user_id'], $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/category');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function editCategory () {
            $id = $_POST['category_id'];
            $category = $_POST['category_name'];
            $query = "UPDATE category SET name = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('si', $category, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json = array('redirect' => '/fmware/category');
                    echo json_encode($json);
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCategory () {
            $query = 'SELECT
                        category.id, 
                        category.name, 
                        category.date,
                        category.active,
                        user.firstname,
                        user.lastname 
                    FROM category
                    INNER JOIN user ON category.user_id = user.id';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $date, $active, $fname, $lname);
                    while ($stmt->fetch()) {
                        if ($active == 1) {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-category-id="'.$id.'" data-category-status="'.$active.'" checked>
                                        </div>';
                        } else {
                            $status = '<div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-category-id="'.$id.'" data-category-status="'.$active.'">
                                        </div>';
                        }
                        $initial = substr($lname, 0, 1);
                        $author = $fname.' '.$initial.'.';
                        echo '<tr>
                                <td>'.$status.'</td>
                                <td>'.$name.'</td>
                                <td>'.$author.'</td>
                                <td>'.date('d F Y | h:i A', strtotime($date)).'</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-category-id="'.$id.'" 
                                        data-category-name="'.$name.'"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger delete" 
                                        type="button" 
                                        data-category-id="'.$id.'" 
                                        data-category-name="'.$name.'"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>                                   
                                </td>
                            </tr>';
                    }
                    $stmt->close();
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function disableCategory () {
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($status == 1) {
                $active = 0;
            } else {
                $active = 1;
            }
            $query = 'UPDATE category SET active = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $active, $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    $json['redirect'] = '/fmware/category';
                    echo json_encode($json);
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