
<?php

// database server type, location, database name
$host = "localhost";
$user = "root";
$password = "";
$database = "shopping_list_app";

$conn = new mysqli($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: ");
}

// Copied from the Stock example, changed username and database name
