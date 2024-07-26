<?php
    require_once 'model/admin/admin.php';

    class Logs extends Admin {
        public function showLogs () {
            $query = 'SELECT logs.description, user.firstname, user.lastname, logs.date
                    FROM logs
                    INNER JOIN user ON user.id = logs.user_id
                    ORDER BY logs.id DESC';
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
                                <td class="text-center">'.ucfirst($description).'</td>
                                <td class="text-center">'.ucfirst($fname).' '.ucfirst($lname).'</td>
                                <td class="text-center">'.$date.'</td>
                            </tr>';
            }
            $stmt->close();
            echo $content;
            return;
        }

        public function newLog ($description, $id, $date) {
            $query = 'INSERT INTO logs (description, user_id, date) VALUES (?,?,?)';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sis', $description, $id, $date);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }
            
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }
            
            $stmt->close();
            return;
        }
    }
?>