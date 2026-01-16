<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'database.php'; // ici, $pdo est un objet PDO connecté à PostgreSQL
$user_id = $_SESSION['user_id'];

// Récupérer les infos utilisateur
$sql = "SELECT username, email, reason, photo FROM Public.users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mon Profil</title>
<link rel="stylesheet" href="planning.css">
<style>
.profile {
  max-width: 600px;
  margin: 40px auto;
  background: white;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.profile h2 {
  text-align: center;
  color: #1e3a8a;
}
.profile img {
  display: block;
  margin: 20px auto;
  width: 130px;
  height: 130px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #1e3a8a;
}
.profile p {
  font-size: 1rem;
  margin: 8px 0;
}
form input[type="file"] {
  margin-top: 10px;
}
button {
  margin-top: 15px;
  padding: 10px 20px;
  background: #1e3a8a;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
button:hover {
  background: #2f56cc;
}
.back {
  display: block;
  text-align: center;
  margin-top: 25px;
  color: #1e3a8a;
  text-decoration: none;
}
</style>
</head>
<body>
  <div class="profile">
    <h2>Mon Profil</h2>
    <img src="<?= $user['photo'] ? 'uploads/'.$user['photo'] : 'uploads/default.png' ?>" alt="Photo de profil">

    <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Raison d'utilisation :</strong> <?= htmlspecialchars($user['reason'] ?? 'Non précisé') ?></p>

    <form action="upload_photo.php" method="POST" enctype="multipart/form-data">
      <label>Changer la photo de profil :</label>
      <input type="file" name="photo" accept="image/*" required>
      <button type="submit">Mettre à jour</button>
    </form>

    <a href="index.php" class="back">⬅ Retour à l’accueil</a>
  </div>
</body>
</html>


