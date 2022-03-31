<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banking_system_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Failed! " . $conn->connect_error);
}
