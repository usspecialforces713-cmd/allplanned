<?php
session_start();
require 'database.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if (isset($_POST['id'])) {
  $task_id = intval($_POST['id']);
  $user_id = $_SESSION['user_id'];

  // On efface seulement la tâche appartenant à cet utilisateur
  $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $task_id, $user_id);

  if ($stmt->execute()) {
    header("Location: planning.php"); // Reviens au planning
    exit();
  } else {
    echo "❌ Erreur lors de la suppression de la tâche.";
  }
} else {
  echo "⚠️ Aucune tâche sélectionnée.";
}
