<?php
require_once 'session.php';
require_once 'model/admin/admin.php';
require_once 'model/admin/logsClass.php';

class Supplier extends Admin
{
    public function addSupplier($supplier, $email, $contact, $phone, $address)
    {
        $logs = new Logs();

        $active = 1;
        $date = date('F j, Y');
        $supplier = strtoupper($supplier);
        $query = 'INSERT INTO supplier
                        (name, email, phone, contact_person, address, date, active)
                    VALUES (?,?,?,?,?,?,?)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssssssi', $supplier, $email, $phone, $contact, $address, $date, $active);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $action_log = 'Added new supplier ' . $supplier;
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array('redirect' => '/manage-suppliers');
        echo json_encode($json);
        return;
    }

    public function showSupplier()
    {
        $query = 'SELECT id, name, email, contact_person, phone, address, date, active FROM supplier';
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $supplier, $email, $contact, $phone, $address, $date, $active);
        $content = '';
        while ($stmt->fetch()) {
            if ($active == 1) {
                $status = '<div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input status" 
                                    type="checkbox" 
                                    id="toggleSwitch"
                                    data-supplier-id=' . $id . '
                                    checked
                                >
                            </div>';
            } else {
                $status = '<div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input status" 
                                    type="checkbox" 
                                    id="toggleSwitch"
                                    data-supplier-id=' . $id . '
                                >
                            </div>';
            }

            $content .= '<tr>
                                <td class="text-center">' . $status . '</td>
                                <td class="text-center">' . $supplier . '</td>
                                <td class="text-center">' . $email . '</td>
                                <td class="text-center">' . ucfirst($contact) . '</td>
                                <td class="text-center">' . $phone . '</td>
                                <td class="text-center">' . $address . '</td>
                                <td class="text-center">' . $date . '</td>
                                <td class="text-center">
                                    <button 
                                        class="btn btn-sm btn-success edit" 
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSupplier"
                                        data-supplier-id=' . $id . '
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>';
        }
        $stmt->close();
        echo $content;
        return;
    }

    public function getSupplier($id)
    {
        $query = 'SELECT id, name, email, contact_person, phone, address FROM supplier WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $supplier, $email, $contact, $phone, $address);
        $stmt->fetch();
        $stmt->close();
        $json = array(
            'id' => $id,
            'supplier' => $supplier,
            'email' => $email,
            'contact' => $contact,
            'phone' => $phone,
            'address' => $address
        );
        echo json_encode($json);
        return;
    }

    public function editSupplier($supplier, $email, $contact, $phone, $address, $id)
    {
        $logs = new Logs();

        $query = 'UPDATE supplier SET name = ?, email = ?, contact_person = ?, phone = ?, address = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $supplier = strtoupper($supplier);
        $stmt->bind_param('sssssi', $supplier, $email, $contact, $phone, $address, $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();

        $action_log = 'Update info of supplier ' . strtoupper($supplier);
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array(
            'redirect' => '/manage-suppliers'
        );
        echo json_encode($json);
        return;
    }

    public function updateSupplierStatus($active, $id)
    {
        $logs = new Logs();

        $query = 'UPDATE supplier SET active = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $active, $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->close();
        $supplier = $this->getSupplierInfo($id);

        if ($active == 1) {
            $action_log = 'Enable supplier ' . strtoupper($supplier['supplier']);
        } else {
            $action_log = 'Disable supplier ' . strtoupper($supplier['supplier']);
        }

        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

        $json = array(
            'redirect' => '/manage-suppliers'
        );
        echo json_encode($json);
        return;
    }

    public function getSupplierInfo($id)
    {
        $query = 'SELECT id, name, email, contact_person, phone, address FROM supplier WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($id, $supplier, $email, $contact, $phone, $address);
        $stmt->fetch();
        $stmt->close();

        return [
            'id' => $id,
            'supplier' => $supplier,
            'email' => $email,
            'contact' => $contact,
            'phone' => $phone,
            'address' => $address
        ];
    }
}
