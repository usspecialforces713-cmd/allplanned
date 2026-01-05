<?php
session_start();

$isLogged  = !empty($_SESSION['user_id']);
$username = $_SESSION['username'] ?? null;
$isAdmin  = ($username === 'moimeme');
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ALLplanned – Organise ta vie</title>

<style>
/* ===== INTRO CINÉMA ===== */
#intro-screen {
  position: fixed;
  inset: 0;
  background: #ffffff;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.intro-step {
  position: absolute;
  text-align: center;
  opacity: 0;
  transform: scale(0.9);
  transition: opacity 1s ease, transform 1s ease;
}

.intro-step.active {
  opacity: 1;
  transform: scale(1);
}

.intro-image {
  width: 220px;
  margin-bottom: 20px;
}

.intro-quote {
  font-size: 1.4rem;
  font-weight: 600;
  color: #1e293b;
  max-width: 700px;
  margin: auto;
}

.intro-author {
  display: block;
  margin-top: 10px;
  font-size: 1rem;
  color: #64748b;
}

.fade-out {
  opacity: 0;
  pointer-events: none;
  transition: opacity 1.2s ease;
}

/* ===== APP ===== */
:root {
  --blue: #1e3a8a;
  --yellow: #facc15;
  --dark: #1e293b;
}

* {
  box-sizing: border-box;
  font-family: "Poppins", "Segoe UI", Arial, sans-serif;
}

body {
  margin: 0;
  min-height: 100vh;
  background: linear-gradient(135deg, var(--blue), var(--yellow));
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.card {
  background: #fff;
  width: 100%;
  max-width: 720px;
  padding: 30px;
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

h1 {
  margin: 0 0 6px;
  color: var(--blue);
}

.lead {
  margin: 0 0 20px;
  color: #555;
}

.grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.btn {
  flex: 1;
  min-width: 200px;
  padding: 12px;
  text-align: center;
  border-radius: 10px;
  text-decoration: none;
  color: white;
  background: var(--blue);
  font-weight: 600;
}

.btn.yellow { background: var(--yellow); color: #111; }
.btn.gray { background: #64748b; }

footer {
  margin-top: 25px;
  text-align: center;
  color: #666;
  font-size: .9rem;
}
</style>
</head>

<body>

<!-- 🎬 INTRO -->
<div id="intro-screen">
  <div class="intro-step active" id="step1">
    <img src="logo.png" class="intro-image">
    <p class="intro-quote">“Failing to plan is planning to fail.”</p>
    <span class="intro-author">— Benjamin Franklin</span>
  </div>

  <div class="intro-step" id="step2">
    <img src="logodeentre.png" class="intro-image">
    <p class="intro-quote">
      “Give me six hours to chop down a tree and I will spend the first four sharpening the axe.”
    </p>
    <span class="intro-author">— Abraham Lincoln</span>
  </div>
</div>

<!-- 🧠 APP -->
<div class="card">

<header style="display:flex;justify-content:space-between;align-items:center">
  <div>
    <h1>ALLplanned</h1>
    <p class="lead">Ton espace personnel pour gérer ton planning.</p>
  </div>
  <div>
    <?php if ($isLogged): ?>
      <div>Connecté : <strong><?=htmlspecialchars($username)?></strong></div>
      <a href="logout.php" class="btn gray">Déconnexion</a>
    <?php else: ?>
      <a href="login.php" class="btn">Connexion</a>
    <?php endif; ?>
  </div>
</header>

<div class="grid" style="margin-top:20px">
<?php if ($isLogged): ?>
  <a class="btn" href="planning.php">🗓️ Planning</a>
  <a class="btn yellow" href="profile.php">👤 Profil</a>
  <a class="btn gray" href="emploidutemps.php">📅 Emploi du temps</a>
<?php else: ?>
  <a class="btn" href="login.php">🔐 Connexion</a>
  <a class="btn yellow" href="register.php">🆕 Inscription</a>
<?php endif; ?>

<?php if ($isAdmin): ?>
  <a class="btn gray" href="create_user.php">👨‍💻 Admin</a>
<?php endif; ?>
</div>

<hr style="margin:25px 0">

<section>
  <h3>🎧 Musique</h3>
  <input type="file" id="musicInput" accept="audio/*">
  <audio id="musicPlayer" controls style="display:none;margin-top:10px"></audio>
</section>

<footer>
  © <?=date('Y')?> — ALLplanned
</footer>

</div>

<!-- 🧠 JS UNIQUE -->
<script>
/* 🎵 Musique */
const input = document.getElementById("musicInput");
const player = document.getElementById("musicPlayer");

if (input && player) {
  input.addEventListener("change", () => {
    const file = input.files[0];
    if (file) {
      player.src = URL.createObjectURL(file);
      player.style.display = "block";
      player.play();
    }
  });
}

/* 🎬 Intro */
window.addEventListener("load", () => {
  const step1 = document.getElementById("step1");
  const step2 = document.getElementById("step2");
  const intro = document.getElementById("intro-screen");

  setTimeout(() => {
    step1.classList.remove("active");
    step2.classList.add("active");
  }, 3000);

  setTimeout(() => {
    intro.classList.add("fade-out");
    setTimeout(() => intro.remove(), 1200);
  }, 6000);
});
</script>

</body>
</html>
