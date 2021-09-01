<?php

$servername = "localhost";
$username = "admin";
$password = "Ditch1234!";
$dbname = "internalweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>