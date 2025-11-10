
<?php
session_start();
$username = $_SESSION['username'] ?? 'Invité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Emploi du temps</title>
  <link rel="stylesheet" href="planning.css">
  <style>
    nav {
      background: linear-gradient(90deg, #1e3a8a, #2563eb);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      margin-bottom: 20px;
    }
    nav .links a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-weight: 600;
      transition: opacity 0.3s;
    }
    nav .links a:hover {
      opacity: 0.7;
    }
    .username {
      font-size: 0.9rem;
      opacity: 0.9;
    }
  </style>
</head>
<body>
  

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
  <audio id="musicPlayer" controls style="display:none; margin-top:10px;"></audio>
</div>

<script>
const input = document.getElementById('musicInput');
const player = document.getElementById('musicPlayer');

input.addEventListener('change', () => {
  const file = input.files[0];
  if (file) {
    const url = URL.createObjectURL(file);
    player.src = url;
    player.style.display = 'block';
    player.play();
  }
});
</script>

<?php

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];
$user_id = (int) $_SESSION['user_id'];

require 'database.php'; // ou 'db.php' selon ton organisation

// Récupérer les tâches pour l'utilisateur
$stmt = $conn->prepare("SELECT id, title, date, status FROM tasks WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<img src="assets/images/logo.png" alt="Allplaned Logo" style="height:80px;">
<title>Mon planning</title>
 <!-- si tu as séparé le CSS -->
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
</head>
<body>
  <div class="container">
    <div class="header-bar">
      
      <a href="logout.php" class="logout-btn">🚪 Déconnexion</a>
    </div>

    <h1>Mon planning</h1>

    <form action="save_task.php" method="POST">
      <input type="text" name="title" placeholder="Ajouter une nouvelle tâche..." required>
      <input type="date" name="date">
      <button type="submit">Ajouter</button>
    </form>

    <div class="task-list">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $status = $row['status'] ?? 'pending'; // sécurité si colonne manquante
            $cls = ($status === 'done' || $status === 'completed' || $status === '1') ? 'task completed' : 'task';
            $title = htmlspecialchars($row['title'] ?? $row['task'] ?? '(sans titre)');
            $date  = !empty($row['date']) ? htmlspecialchars($row['date']) : '';
          ?>
          <div class="<?= $cls ?>" data-id="<?= (int)$row['id'] ?>">
            <div>
              <strong><?= $date ?></strong> <?= $title ?>
            </div>
            <div class="actions">
              <?php if ($status !== 'done' && $status !== 'completed' && $status !== '1'): ?>
                <form action="mark_done.php" method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                  <button type="submit">Terminer ✅</button>
                </form>
              <?php endif; ?>
              <form action="delete_task.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                <button type="submit">🗑 Supprimer</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Aucune tâche pour le moment 🌻</p>
      <?php endif; ?>
    </div>

  </div>
</body>
</html>
