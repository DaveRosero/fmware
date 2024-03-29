<?php
    require_once 'model/user/user.php';

    class Checkout extends User {
        public function generateRef () {
            $bytes = random_bytes(10);
            $hex = bin2hex($bytes);
            $prefix = 'FMO_';
            return $prefix.strtoupper($hex);
        }
        public function placeOrder () {
            $query = 'INSERT INTO orders
                        (order_ref, firstname, lastname, phone, gross, delivery_fee, vat, discount, net, 
                        transaction_type_id, payment_type_id, address_id, user_id, paid, status)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $order_ref = $this->generateRef();
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $phone = $_POST['phone'];
            $gross = $_POST[''];
        }
    }
?>