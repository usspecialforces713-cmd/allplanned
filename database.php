<?php
$host = getenv("hostname");
$port = getenv("Port");
$db   = getenv("");
$user = getenv("DB_USER");
$pass = getenv("DB_PASSWORD");

// Connexion
$conn = new mysqli(
    hostname: 'localhost',
    username: 'root',
    password: '',
    database: 'planning_db'
);
if ($conn->connect_error) {

// Vérifie la connexion
if ($conn->connect_error) {
  die("Erreur de connexion : " . $conn->connect_error);
}}
?>

