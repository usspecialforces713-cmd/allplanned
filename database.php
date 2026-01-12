<?php
$host = getenv("PGHOST");
$port = getenv("PGPORT");
$db   = getenv("PGDATABASE");
$user = getenv("PGUSER");
$pass = getenv("PGPASSWORD");

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
