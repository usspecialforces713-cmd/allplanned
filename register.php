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
            "SELECT id FROM users WHERE username = :username"
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
