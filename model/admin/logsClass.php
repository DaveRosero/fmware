<?php
    require_once 'model/admin/admin.php';

    class Logs extends Admin {
        public function showLogs () {
            $query = 'SELECT logs.description, user.firstname, user.lastname, logs.date
                    FROM logs
                    INNER JOIN user ON user.id = logs.user_id';
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($description, $fname, $lname, $date);
            $content = '';
            while ($stmt->fetch()) {
                $content .= '<tr>
                                <td>'.ucfirst($description).'</td>
                                <td>'.ucfirst($fname).' '.ucfirst($lname).'</td>
                                <td>'.$date.'</td>
                            </tr>';
            }
            $stmt->close();
            return;
        }

        public function newStaff () {

        }
    }
?>