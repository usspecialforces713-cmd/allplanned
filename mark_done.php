<?php
session_start();
require 'database.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifie si une tâche a été envoyée
if (isset($_GET['id'])) {
    $task_id = (int) $_GET['id'];
    $user_id = (int) $_SESSION['user_id'];

    // Mise à jour sécurisée (PostgreSQL + PDO)
    $stmt = $pdo->prepare("
        UPDATE tasks
        SET done = TRUE
        WHERE id = :task_id
          AND user_id = :user_id
    ");

    $stmt->execute([
        ':task_id' => $task_id,
        ':user_id' => $user_id
    ]);

    // Si aucune ligne modifiée
    if ($stmt->rowCount() === 0) {
        echo "Erreur : tâche introuvable ou non autorisée.";
        exit();
    }
}

// Retour vers le planning
header("Location: planning.php");
exit();
