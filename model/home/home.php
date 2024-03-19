<?php
    require_once 'model/database/database.php';

    class Home{
        private $conn;
        public function __construct () {
            $this->conn = database();
        }

        public function isConnectionActive() {
            return mysqli_ping($this->conn);
        }

        public function getConnection () {
            return $this->conn;
        }
    }
?>