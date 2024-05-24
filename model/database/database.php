<?php

    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception('.env file not found.');
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // skip comments
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

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
