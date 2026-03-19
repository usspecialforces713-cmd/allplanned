<?php
$host = getenv("PG_HOST");
$port = getenv("PG_PORT");
$db   = getenv("PG_DATABASE");
$user = getenv("PG_USER");
$pass = getenv("PG_PASSWORD");

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

