<?php
    require_once 'model/user/user.php';

    class Logout extends User {
        public function logout () {
            session_start();
            session_unset();

            header('Location: /fmware/login');
            exit();
        }
    }
?>