<?php
$databaseHost = getenv('DB_HOST');
$databaseUsername = getenv('DB_USER');
$databasePassword = getenv('DB_PASSWORD');
$databaseName = getenv('DB_NAME');
// // Path to the Unix socket file used for connecting to the MySQL server.
$databaseSocket = getenv('DB_SOCKET');

// Initialize the database client
$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName, null, $databaseSocket);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
