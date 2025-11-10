<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require 'database.php';
$user_id = $_SESSION['user_id'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
  $fileTmp = $_FILES['photo']['tmp_name'];
  $fileName = basename($_FILES['photo']['name']);
  $ext = pathinfo($fileName, PATHINFO_EXTENSION);
  $allowed = ['jpg','jpeg','png','gif'];

  if (in_array(strtolower($ext), $allowed)) {
    $newName = uniqid('photo_', true) . '.' . $ext;
    move_uploaded_file($fileTmp, "uploads/$newName");

    $sql = "UPDATE users SET photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newName, $user_id);
    $stmt->execute();
  }
}

header("Location: profile.php");
exit();
?>
