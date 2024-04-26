<?php
    require_once 'model/user/user.php';
    
    class Logout extends User {
        public function logout () {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy(); // Destroy the active session
            }
            header('Location: /login');
            exit; // Ensure script execution stops after redirection
        }
    }
?>
