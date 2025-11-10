<?php
$servername = "localhost";
$username = "root";
$password = ""; // mot de passe MySQL (souvent vide sous XAMPP)
$dbname = "planning_db";

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
