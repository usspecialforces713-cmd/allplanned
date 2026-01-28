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
/* ===== SIDEBAR ===== */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #1e3a8a, #2563eb);
    color: white;
    padding: 25px 20px;
    box-shadow: 3px 0 12px rgba(0,0,0,0.3);
    transition: transform 0.3s ease;
    z-index: 1000;
}
.sidebar.closed { transform: translateX(-260px); }

.sidebar .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.close-btn {
    background: transparent;
    border: none;
    color: white;
    font-size: 1.6rem;
    cursor: pointer;
    padding: 5px;
}

.hamburger {
    position: fixed;
    top: 15px;
    left: 15px;
    background: #1e3a8a;
    color: white;
    padding: 10px 14px;
    font-size: 1.6rem;
    border-radius: 8px;
    cursor: pointer;
    z-index: 1100;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    margin-left: 260px;
    padding: 25px;
    transition: 0.3s;
}
.main-content.full { margin-left: 20px; }

/* ===== STYLE FORM + TÂCHES ===== */
.container {
  background: #fff;
  max-width: 900px;
  padding: 30px;
  border-radius: 18px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.task {
    background: #f8fafc;
    border-left: 6px solid #1e3a8a;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
}
.task.completed {
    background: #fef9c3;
    border-left-color: #facc15;
    text-decoration: line-through;
}

</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar closed" id="sidebar">
    <div class="top-bar">
        <h2>AllPlaned</h2>
        <button id="closeBtn" class="close-btn">✖</button>
    </div>

    <ul>
        <li><a href="index.php">🏠 Accueil</a></li>
        <li><a href="planning.php">🗓️ Planning</a></li>
        <li><a href="emploidutemps.php">📅 Emploi du temps</a></li>
        <li><a href="profile.php">👤 Profil</a></li>
        <li><a href="logout.php">🔓 Déconnexion</a></li>
    </ul>
</div>

<!-- BOUTON HAMBURGER -->
<div id="hamburger" class="hamburger">☰</div>
</head>



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
                    <form action="delete_task.php" method="POST">
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
    
const sidebar = document.getElementById("sidebar");
const hamburger = document.getElementById("hamburger");
const closeBtn = document.getElementById("closeBtn");
const mainContent = document.querySelector(".main-content");

// Ouvrir la sidebar
hamburger.addEventListener("click", () => {
    sidebar.classList.remove("closed");
    mainContent?.classList.remove("full");
});

// Fermer la sidebar
closeBtn.addEventListener("click", () => {
    sidebar.classList.add("closed");
    mainContent?.classList.add("full");
});
</script>

</body>
</html>





