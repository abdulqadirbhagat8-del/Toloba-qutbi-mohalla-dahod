<<?php

$servername = "sql303.infinityfree.com";
$username = "if0_42602763";
$password = "ERdLb3xU2a5vL";
$database = "if0_42602763_toloba_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>