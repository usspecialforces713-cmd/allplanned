<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'database.php'; // $pdo
$user_id = $_SESSION['user_id'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $fileTmp  = $_FILES['photo']['tmp_name'];
    $fileName = basename($_FILES['photo']['name']);
    $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed)) {
        $newName = uniqid('photo_', true) . '.' . $ext;
        move_uploaded_file($fileTmp, "uploads/$newName");

        $sql = "UPDATE users SET photo = :photo WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':photo' => $newName,
            ':id'    => $user_id
        ]);
    }
}

header("Location: profile.php");
exit();
?>
s
