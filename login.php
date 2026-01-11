<?php
session_start();
require 'database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare(
            "SELECT id, username, password FROM users WHERE username = :username"
        );
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: planning.php");
            exit;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
<style>

body {
  font-family: Arial, sans-serif;
  background: #f3f4f6;
  margin: 0;
  padding: 40px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.container {
  width: 900px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.oauth-box {
  width: 45%;
}

.oauth-btn {
  display:flex;
  align-items:center;
  gap:10px;
  padding:12px 14px;
  margin-bottom:10px;
  border-radius:8px;
  text-decoration:none;
  font-size:15px;
  border:1px solid #ccc;
  color:#111;
  background:white;
}

.oauth-btn.github { background:#24292e; color:white; }
.oauth-btn.microsoft { background:#2F2F7A; color:white; }

.login-box {
  width: 45%;
  background:white;
  padding:30px;
  border-radius:15px;
  box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
#loader {
    position: fixed;
    inset: 0;
    background: white;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

#loader-box {
    text-align: center;
    font-family: Arial;
    font-size: 18px;
}

#spinner {
    width: 40px;
    height: 40px;
    border: 5px solid #ddd;
    border-top-color: #007bff;
    border-radius: 50%;
    margin: 10px auto;
    animation: spin 1s linear infinite;
}

.fast #spinner { animation-duration: 0.5s; }
.medium #spinner { animation-duration: 1.2s; }
.slow #spinner { animation-duration: 2s; }

@keyframes spin {
    to { transform: rotate(360deg); }
}


input {
  width:100%;
  padding:12px;
  margin-bottom:15px;
  border-radius:8px;
  border:1px solid #ccc;
}

button {
  width:100%;
  padding:12px;
  border:none;
  border-radius:8px;
  background:#0d6efd;
  color:white;
  cursor:pointer;
}
button:hover {
  background:#0b5ed7;
}

.error {
  color:red;
  margin-top:10px;
}
</style>

   <div class="login-box">
    <h2>Connexion</h2>

    <form method="POST">
      <input type="text" name="username" placeholder="Nom d'utilisateur" required>
      <input type="password" name="password" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <a href="register.php">Créer un compte</a>
  </div>

</body>
</html>


