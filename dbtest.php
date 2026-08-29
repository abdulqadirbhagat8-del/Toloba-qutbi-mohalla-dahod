<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql303.infinityfree.com";
$username = "if0_42602763";
$password = "ERdLb3xU2a5vL";
$database = "if0_42602763_toloba_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("DATABASE CONNECTION FAILED: " . $conn->connect_error);
}

echo "DATABASE CONNECTION SUCCESSFUL";
?>