<?php
    require_once 'config/load_env.php';
    function database() {
        loadEnv('.env');

        $hostname = getenv('DB_HOST');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $database = getenv('DB_NAME');

        // Create a new MySQLi object
        $mysqli = new mysqli($hostname, $username, $password, $database);

        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Set the character set to utf8 (optional, adjust as needed)
        $mysqli->set_charset("utf8");

        // Return the MySQLi object
        return $mysqli;
    }
?>
