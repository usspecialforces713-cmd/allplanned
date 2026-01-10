<?php
$host = getenv("hostname");
$port = getenv("Port");
$db   = getenv("database");
$user = getenv("username");
$pass = getenv("pasword");

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
   
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

