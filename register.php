<?php
session_start();
require 'database.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $reason   = $_POST['reason'] ?? '';
    $photoName = null;

    // Validation
    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $msg = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirm) {
        $msg = "Les mots de passe ne correspondent pas.";
    } else {

        // Vérifier si l'utilisateur existe déjà
       $check = $pdo->prepare(
    "SELECT id FROM public.users WHERE username = :username"
);

        $check->execute([
            ':username' => $username
        ]);

        if ($check->rowCount() > 0) {
            $msg = "Nom d’utilisateur déjà pris.";
        } else {

            // Upload photo (facultatif)
            if (!empty($_FILES['photo']['name'])) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $photoName = uniqid('photo_', true) . '.' . $ext;

                if (!is_dir("uploads")) {
                    mkdir("uploads", 0777, true);
                }

                move_uploaded_file(
                    $_FILES['photo']['tmp_name'],
                    "uploads/$photoName"
                );
            }

            // Hash mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion
            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password, reason, photo)
                 VALUES (:username, :email, :password, :reason, :photo)"
            );

            $stmt->execute([
                ':username' => $username,
                ':email'    => $email,
                ':password' => $hashedPassword,
                ':reason'   => $reason,
                ':photo'    => $photoName
            ]);

            $msg = "✅ Compte créé avec succès ! <a href='login.php'>Se connecter</a>";
        }
    }
}
?>
    <!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Créer un compte — Planning App</title>
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #1e3a8a, #facc15);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      width: 90%;
      max-width: 400px;
    }
    h2 { text-align: center; color: #1e3a8a; }
    label { display: block; margin-top: 12px; font-weight: 600; }
    input, select {
      width: 100%;
      padding: 8px;
      margin-top: 4px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      margin-top: 18px;
      width: 100%;
      padding: 10px;
      background: #1e3a8a;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    button:hover { background: #2f56cc; }
    .msg { text-align: center; margin-top: 10px; color: #d00; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Créer un compte pour DER_COMMANDERS APP
      <img src="logodeentre.png" width="100"></img>
    </h2>
    <?php if ($msg): ?><p class="msg"><?= $msg ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <label>Nom d’utilisateur :</label>
      <input type="text" name="username" required>

      <label>Email :</label>
      <input type="email" name="email" required>

      <label>Mot de passe :</label>
      <input type="password" name="password" required>

      <label>Confirmer le mot de passe :</label>
      <input type="password" name="confirm" required>

      <label>Pourquoi utilisez-vous l’application ?</label>
      <select name="reason" required>
        <option value="">-- Sélectionnez une raison --</option>
        <option value="Organisation personnelle">Organisation personnelle</option>
        <option value="Suivi scolaire">Suivi scolaire</option>
        <option value="Gestion du travail">Gestion du travail</option>
        <option value="Autre">Autre</option>
      </select>

      <label>Photo de profil :</label>
      <input type="file" name="photo" accept="image/*">

      <button type="submit">Créer mon compte</button>
    </form>
  </div>
</body>
</html>



