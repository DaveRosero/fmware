<?php
    require_once 'model/database/database.php';

    class Home{
        protected $conn;
        public function __construct () {
            $this->conn = database();
        }

        public function redirectUser($group) {
            switch ($group) {
                case 'user':
                    header('Location: /');
                    break;
                case 'admin':
                    header('Location: /dashboard');
                    break;
                case 'delivery':
                    header('Location: /rider-order');
                    break;
                default:
                    header('Location: /pos');
                    break;

            }
        }
    }
?>