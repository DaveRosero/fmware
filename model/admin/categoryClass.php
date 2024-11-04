<?php
require_once 'model/admin/admin.php';
require_once 'model/admin/logsClass.php';

class Category extends Admin
{
    public function isCategoryExist($category)
    {
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

    public function newCategory()
    {
        $logs = new Logs();

        $category = ucwords($_POST['category_name']);
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

                $action_log = 'Added new category ' . $category;
                $date_log = date('F j, Y g:i A');
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                $json = array('redirect' => '/category');
                echo json_encode($json);
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function editCategory()
    {
        $logs = new Logs();

        $id = $_POST['category_id'];
        $category = ucwords($_POST['category_name']);
        $old_category = $this->getCategoryName($id);

        $query = "UPDATE category SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $category, $id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();

                $action_log = 'Update ' . $old_category . ' to ' . $category;
                $date_log = date('F j, Y g:i A');
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                $json = array('redirect' => '/category');
                echo json_encode($json);
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCategory()
    {
        $query = 'SELECT
                        category.id, 
                        category.name, 
                        category.date,
                        category.active,
                        COALESCE(product_counts.product_count, 0) AS product_count
                    FROM category
                    LEFT JOIN (
                        SELECT category_id, COUNT(*) AS product_count
                        FROM product
                        WHERE active = 1
                        GROUP BY category_id
                    ) AS product_counts ON category.id = product_counts.category_id';
        $stmt = $this->conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->bind_result($id, $name, $date, $active, $product_count);
                while ($stmt->fetch()) {
                    if ($active == 1) {
                        $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-category-id="' . $id . '" data-category-status="' . $active . '" checked>
                                        </div>';
                    } else {
                        $status = '<div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input status" type="checkbox" id="toggleSwitch" data-category-id="' . $id . '" data-category-status="' . $active . '">
                                        </div>';
                    }

                    echo '<tr>
                                <td class="text-center">' . $status . '</td>
                                <td class="text-center">' . ucwords($name) . '</td>
                                <td class="text-center">' . $product_count . '</td>
                                <td class="text-center">' . date('F j, Y', strtotime($date)) . '</td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button" 
                                        data-category-id="' . $id . '" 
                                        data-category-name="' . $name . '"
                                    >
                                        Edit
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

    public function disableCategory()
    {
        $logs = new Logs();

        $id = $_POST['id'];
        $status = $_POST['status'];
        $category = $this->getCategoryName($id);

        if ($status == 1) {
            $active = 0;
            $action_log = 'Disable category ' . $category;
        } else {
            $active = 1;
            $action_log = 'Enable category ' . $category;
        }

        $query = 'UPDATE category SET active = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $active, $id);
        if ($stmt) {
            if ($stmt->execute()) {
                $stmt->close();

                $date_log = date('F j, Y g:i A');
                $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

                $json['redirect'] = '/category';
                echo json_encode($json);
            } else {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
        } else {
            die("Error in preparing statement: " . $this->conn->error);
        }
    }

    public function getCategoryName($id)
    {
        $query = 'SELECT name FROM category WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($category);
        $stmt->fetch();
        $stmt->close();

        return $category;
    }
}
