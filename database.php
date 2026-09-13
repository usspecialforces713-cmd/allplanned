<?php
require_once __DIR__ . '/config/env.php';

// Require all necessary database configuration
requireEnv('PG_HOST', 'PG_PORT', 'PG_DATABASE', 'PG_USER', 'PG_PASSWORD');

$host = env("PG_HOST");
$port = env("PG_PORT");
$db   = env("PG_DATABASE");
$user = env("PG_USER");
$pass = env("PG_PASSWORD");

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
?>
