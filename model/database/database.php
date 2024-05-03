<?php

    function database() {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $database = "fmware-db";

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
