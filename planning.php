<?php
session_start();
require 'database.php';

// Sécurité
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Invité';
$user_id  = (int) $_SESSION['user_id'];

// Récupération des tâches
$stmt = $pdo->prepare("
    SELECT id, title, date, status
    FROM tasks
    WHERE user_id = :uid
    ORDER BY id DESC
");
$stmt->execute(['uid' => $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mon planning</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="planning.css">

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef2ff;
    margin: 0;
    padding: 0;
}

nav {
    background: #1e3a8a;
    color: white;
    padding: 15px;
}

.container {
    max-width: 900px;
    margin: 30px auto;
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

h1 {
    margin-top: 0;
}

form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

input[type="text"],
input[type="date"] {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

.task {
    background: #f8fafc;
    border-left: 6px solid #1e3a8a;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.task.completed {
    background: #fef9c3;
    border-left-color: #facc15;
    text-decoration: line-through;
    opacity: 0.8;
}

.actions {
    display: flex;
    gap: 6px;
}

.actions form {
    margin: 0;
}

.music-box {
    margin: 25px 0;
    padding: 15px;
    border-radius: 12px;
    background: #f1f5f9;
}
</style>
</head>

<body>

<nav>
  Connecté : <strong><?= htmlspecialchars($username) ?></strong>
</nav>

<main class="container">

<h1>Mon planning</h1>

<form action="save_task.php" method="POST">
  <input type="text" name="title" placeholder="Nouvelle tâche..." required>
  <input type="date" name="date">
  <button type="submit">Ajouter</button>
</form>

<div class="music-box">
  <h3>🎵 Importer une musique</h3>
  <input type="file" id="musicInput" accept="audio/*">
  <audio id="musicPlayer" controls></audio>
</div>

<div class="task-list">
<?php if ($tasks): ?>
    <?php foreach ($tasks as $task): ?>
        <div class="task <?= $task['status'] === 'done' ? 'completed' : '' ?>">
            <div>
                <strong><?= htmlspecialchars($task['date']) ?></strong> —
                <?= htmlspecialchars($task['title']) ?>
            </div>

            <div class="actions">
                <?php if ($task['status'] !== 'done'): ?>
                    <form action="mark_done.php" method="POST">
                        <input type="hidden" name="id" value="<?= $task['id'] ?>">
                        <button type="submit">✔</button>
                    </form>
                <?php endif; ?>

                <form action="delete_task.php" method="POST">
                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                    <button type="submit">🗑</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucune tâche pour le moment.</p>
<?php endif; ?>
</div>

</main>

<script>
const input = document.getElementById("musicInput");
const player = document.getElementById("musicPlayer");

input.addEventListener("change", e => {
    const file = e.target.files[0];
    if (!file) return;
    player.src = URL.createObjectURL(file);
    player.play();
});
</script>

</body>
</html>

