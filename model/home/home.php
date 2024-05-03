<?php
    require_once 'model/database/database.php';

    class Home{
        protected $conn;
        public function __construct () {
            $this->conn = database();
        }
    }
?>