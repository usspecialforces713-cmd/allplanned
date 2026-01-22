<?php
session_start();
require 'database.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['id'])) {
    $task_id = (int) $_POST['id'];
    $user_id = (int) $_SESSION['user_id'];

    // Suppression sécurisée (PostgreSQL + PDO)
    $stmt = $pdo->prepare("
        DELETE FROM tasks
        WHERE id = :task_id
          AND user_id = :user_id
    ");

    $stmt->execute([
        ':task_id' => $task_id,
        ':user_id' => $user_id
    ]);

    // Si aucune ligne supprimée → tâche inexistante ou non autorisée
    if ($stmt->rowCount() > 0) {
        header("Location: planning.php");
        exit();
    } else {
        echo "❌ Erreur : tâche introuvable ou non autorisée.";
    }

} else {
    echo "⚠️ Aucune tâche sélectionnée.";
}
