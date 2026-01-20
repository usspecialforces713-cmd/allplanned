<?php
session_start();
require_once __DIR__ . '/database.php'; // $pdo

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT id, password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    }

    $error = "Identifiants incorrects";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f4f6;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-box {
    width: 420px;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    position: relative;
}

.login-box h2 {
    margin-bottom: 20px;
}

.register-link {
    position: absolute;
    top: 20px;
    right: 25px;
    text-decoration: none;
    color: #0d6efd;
    font-size: 14px;
}

input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #0d6efd;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #0b5ed7;
}

.error {
    color: red;
    margin-bottom: 15px;
}
</style>
</head>

<body>

<div class="login-box">
    <a href="register.php" class="register-link">Créer un compte</a>

   

    

    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>





