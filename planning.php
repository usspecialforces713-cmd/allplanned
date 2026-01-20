<?php
session_start();
require 'database.php';

// Sécurité : utilisateur non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Invité';
$user_id  = (int) $_SESSION['user_id'];

// Récupérer les tâches
$stmt = $conn->prepare("SELECT id, title, date, status 
     FROM tasks 
     WHERE user_id = ? 
     ORDER BY id DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon planning</title>
<link rel="stylesheet" href="planning.css">
</head>
<body>
  <style>
    :root {
  --blue: #1e3a8a;
  --yellow: #facc15;
  --light: #f8fafc;
  --dark: #1e293b;
  --gray: #e5e7eb;
}

/* ====== RESET ====== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", "Segoe UI", Arial, sans-serif;
}

/* ====== BODY ====== */
body {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 15px;
  background: linear-gradient(135deg, var(--blue), var(--yellow));
}

/* ====== CONTAINER ====== */
.container {
  background: #fff;
  width: 100%;
  max-width: 900px;
  border-radius: 18px;
  padding: 30px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ====== HEADER ====== */
h1 {
  font-size: 2rem;
  color: var(--blue);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

h1 span {
  font-size: 1rem;
  font-weight: 400;
  color: #555;
}

/* ====== FORM ====== */
form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 25px;
}

input[type="text"] {
  flex: 1;
  min-width: 240px;
  padding: 10px 14px;
  border: 2px solid var(--gray);
  border-radius: 10px;
  font-size: 1rem;
  transition: 0.3s;
}

input[type="text"]:focus {
  border-color: var(--blue);
  outline: none;
  box-shadow: 0 0 5px rgba(30,58,138,0.3);
}

button {
  padding: 10px 20px;
  border: none;
  background: var(--blue);
  color: white;
  font-weight: 600;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.25s ease;
}

button:hover {
  background: #2f56cc;
  transform: translateY(-2px);
}

/* ====== TASK LIST ====== */
.task-list {
  margin-top: 30px;
}

.task {
  background: var(--light);
  border-left: 6px solid var(--blue);
  padding: 12px 16px;
  border-radius: 10px;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.25s;
}

.task:hover {
  transform: scale(1.02);
}

.task.completed {
  border-left-color: var(--yellow);
  background: #fef9c3;
  text-decoration: line-through;
  color: #888;
}

/* ====== ACTION BUTTONS ====== */
.actions button {
  background: var(--yellow);
  color: #111;
  border: none;
  margin-left: 8px;
  padding: 6px 10px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.25s;
}

.actions button:hover {
  background: #fde047;
}

/* ====== LOGOUT ====== */
.logout {
  text-align: right;
  margin-top: 20px;
}

.logout a {
  color: var(--dark);
  text-decoration: none;
  font-weight: 600;
}

.logout a:hover {
  text-decoration: underline;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 600px) {
  h1 { font-size: 1.5rem; }
  form { flex-direction: column; }
  button, input[type="text"] { width: 100%; }
  .task { flex-direction: column; align-items: flex-start; }
}

  </style>

<nav>
  <div class="links">
    <a href="index.php">🏠 Accueil</a>
    <a href="planning.php">🗓️ Planning</a>
    <a href="emploidutemps.php">📅 Emploi du temps</a>
    <a href="profile.php">👤 Profil</a>
  </div>
  <div class="username">
    Connecté en tant que <strong><?= htmlspecialchars($username) ?></strong>
  </div>
</nav>
<div style="margin-top:30px;">
  <h3>🎧 Importer et jouer une musique</h3>
  <input type="file" id="musicInput" accept="audio/*">
  <audio id="musicPlayer" controls style="display:none;"></audio>
</div>

