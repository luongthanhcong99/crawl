<?php 
$host = "localhost";
$username = "root";
$password = "";
$dbname = "toeic";
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Can't connect to database: " . mysqli_connect_error());
}