<?php
    require_once 'model/user/user.php';

    class Address extends User {
        public function hasAddress ($id) {
            $query = 'SELECT COUNT(*) FROM address WHERE user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();

                    if ($count == 0) {
                        return [
                            'no_address' => 'Your account is not linked to any address yet.'
                        ];
                    } else {
                        return [
                            'has_address' => 'User has address.'
                        ];
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function newAddress () {
            $query = 'INSERT INTO address
                        (house_no, street, brgy, municipality, description, user_id)
                    VALUES (?,?,?,?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssssi', $_POST['house_no'], $_POST['street'], $_POST['brgy'], $_POST['municipality'], $_POST['description'], $_POST['user_id']);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'redirect' => '/fmware/cart'
                    ];
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getAddress ($id) {
            $query = 'SELECT house_no, street, brgy, municipality, description
                    FROM address
                    WHERE user_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($house_no, $street, $brgy, $municipality, $description);
                    $stmt->fetch();
                    $stmt->close();
                    return [
                        'house' => $house_no,
                        'street' => $street,
                        'brgy' => $brgy,
                        'municipality' => $municipality,
                        'description' => $description
                    ];
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