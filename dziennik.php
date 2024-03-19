<?php
$servername = "localhost";
$username = "root"; // Twoja nazwa użytkownika MySQL
$password = ""; // Twoje hasło MySQL
$dbname = "dziennik";

// Tworzenie połączenia
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
