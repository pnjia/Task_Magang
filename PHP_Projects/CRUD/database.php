<?php

$servername = "localhost";
$username = "root";
$password = "Root-phpmyadmin01";
$dbname = "db_tes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Gagal terkoneksi: " . $conn->connect_error);
}


?>