<?php
include('database.php'); // $pdo = new PDO(...)
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title   = $_POST['title'];
    $date    = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (user_id, title, date) 
            VALUES (:user_id, :title, :date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':title'   => $title,
        ':date'    => $date
    ]);

    header("Location: planning.php");
    exit();
}
?>
