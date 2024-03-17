<?php
    require_once 'model/admin/admin.php';

    class Attributes extends Admin {
        public function newAttribute () {
            $conn = $this->getConnection();
            $attr_name = $_POST['attr_name'];
            $active = 1;

            $query = 'INSERT INTO attribute
                        (name, active)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $attr_name, $active);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /fmware/attributes');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function newVariant () {
            $conn = $this->getConnection();
            $attr_value = $_POST['attr_value'];
            $parent_attr = $_POST['attr_id'];

            $query = 'INSERT INTO attribute_data
                        (name, parent_attribute)
                    VALUES (?,?)';
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $attr_value, $parent_attr);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /fmware/attributes');
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }

        public function getAttributes () {
            $conn = $this->getConnection();
            $query = 'SELECT id, name
                    FROM attribute';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name);
                    while ($stmt->fetch()) {
                        echo '<option value="'.$id.'">'.$name.'</option>';
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

        public function getVariant () {
            $conn = $this->getConnection();

            $query = 'SELECT
                        attribute.id, attribute.name,
                        attribute_data.name, attribute_data.parent_attribute
                    FROM attribute
                    INNER JOIN attribute_data ON attribute_data.parent_attribute = attribute.id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $var_name, $parent_attr);
                    while ($stmt->fetch()) {
                        echo '<tr>
                                <td>'.$name.'</td>
                                <td>'.$var_name.'</td>
                                <td></td>
                                <td></td>
                            </tr>';
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