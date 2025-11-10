<?php
include('database.php');
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $date = $_POST['date'];
  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, date) VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $user_id, $title, $date);
  $stmt->execute();
  $stmt->close();

  header("Location: planning.php");
  exit();
}
?>
