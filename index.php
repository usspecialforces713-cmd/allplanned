<?php
// index.php — page d'accueil / hub
session_start();

// Pour tester sans login :
# $_SESSION['user_id'] = 1;
# $_SESSION['username'] = 'moimeme';

$isLogged = !empty($_SESSION['user_id']);
$username = $_SESSION['username'] ?? null;
$isAdmin = ($username === 'moimeme');
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Welocome to ALLplanned the app that simplifies your organisation</title>
  <style>
    :root {
      --blue: #1e3a8a;
      --yellow: #facc15;
      --light: #f8fafc;
      --dark: #1e293b;
    }

    * {
      box-sizing: border-box;
      font-family: "Poppins", "Segoe UI", Arial, sans-serif;
    }

    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--blue), var(--yellow));
      color: var(--dark);
      padding: 20px;
      transition: background 0.5s ease;
    }

    .card {
      background: white;
      width: 100%;
      max-width: 720px;
      padding: 30px;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      animation: fadeIn 0.7s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h1 {
      margin: 0 0 8px;
      font-size: 1.9rem;
      color: var(--blue);
    }

    p.lead {
      margin: 0 0 20px;
      color: #555;
    }

    .grid {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 20px;
    }

    .btn {
      flex: 1;
      min-width: 200px;
      display: inline-block;
      text-align: center;
      padding: 12px 20px;
      border-radius: 10px;
      text-decoration: none;
      color: white;
      background: var(--blue);
      font-weight: 600;
      transition: all 0.25s ease;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .btn:hover {
      background: #2f56cc;
      transform: translateY(-2px);
    }

    .btn.yellow { background: var(--yellow); color: #111; }
    .btn.yellow:hover { background: #fde047; }

    .btn.gray { background: #64748b; }
    .btn.gray:hover { background: #475569; }

    footer {
      margin-top: 30px;
      text-align: center;
      color: #666;
      font-size: 0.9rem;
    }

    @media (max-width: 540px) {
      .grid { flex-direction: column; }
    }
  </style>
</head>
<body>
  <div class="card">
    <header style="display:flex;align-items:center;justify-content:space-between">
      <div>
        <h1>ALLplanned</h1>
        <p class="lead">Ton espace personnel pour gérer les tâches et le planning.</p>
      </div>
      <div style="text-align:right">
        <?php if ($isLogged): ?>
          <div>Connecté en tant que <strong><?=htmlspecialchars($username)?></strong></div>
          <div style="margin-top:8px"><a href="logout.php" class="btn gray">Déconnexion</a></div>
        <?php else: ?>
          <a href="login.php" class="btn">Se connecter</a>
        <?php endif; ?>
      </div>
    </header>

    <main>
      <div class="grid">
        <?php if ($isLogged): ?>
          <a class="btn" href="planning.php">🗓️ Voir mon planning</a>
          <a class="btn yellow" href="profile.php">👤 Mon profil</a>
          <a class="btn gray" href="emploidutemps.php">📅 Emploi du temps</a>
        <?php else: ?>
          <a class="btn" href="login.php">🔐 Connexion</a>
          <a class="btn yellow" href="register.php">🆕 Créer un compte</a>
        <?php endif; ?>

        <?php if ($isAdmin): ?>
          <a class="btn gray" href="create_user.php">👨‍💻 Créer un utilisateur</a>
        <?php endif; ?>
      </div>

      <hr style="margin:30px 0;">

      <section>
        <h3>🎧 Importer et jouer une musique</h3>
        <input type="file" id="musicInput" accept="audio/*">
        <audio id="musicPlayer" controls style="display:none; margin-top:10px;"></audio>
      </section>
    </main>

    <footer>
      <p>© <?=date('Y')?> — Planning App</p>
    </footer>
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
</body>
</html>

