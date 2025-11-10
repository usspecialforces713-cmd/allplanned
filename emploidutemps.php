<?php
session_start();
$username = $_SESSION['username'] ?? 'Invité';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Emploi du temps</title>
  <link rel="stylesheet" href="planning.css">
  <style>
    nav {
      background: linear-gradient(90deg, #1e3a8a, #2563eb);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      margin-bottom: 20px;
    }
    nav .links a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-weight: 600;
      transition: opacity 0.3s;
    }
    nav .links a:hover {
      opacity: 0.7;
    }
    .username {
      font-size: 0.9rem;
      opacity: 0.9;
    }
  </style>
</head>
<body>

<nav>
  <div class="links">
    <a href="index.php">🏠 Accueil</a>
    <a href="planning.php">🗓️ Planning</a>
    <a href="emploidutemps.php">📅 Emploi du temps</a>
    <a href="profile.php">👤 Profil</a>
  </div>
  <div class="username">
    Connecté en tant que <strong><?= htmlspecialchars($username) ?></strong>
  </div>
</nav>

<?php

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Emploi du Temps</title>
<style>
  :root {
    --blue: #1e3a8a;
    --yellow: #facc15;
    --light: #f8fafc;
    --dark: #1e293b;
  }

  body {
    margin: 0;
    font-family: "Poppins", "Segoe UI", Arial, sans-serif;
    background: linear-gradient(135deg, var(--blue), var(--yellow));
    color: var(--dark);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  .container {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    max-width: 900px;
    width: 100%;
    animation: fadeIn 0.5s ease;
  }

  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(20px);}
    to {opacity: 1; transform: translateY(0);}
  }

  h1 {
    text-align: center;
    color: var(--blue);
    margin-bottom: 25px;
  }

  form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
  }

  select, input[type="time"], input[type="text"] {
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
  }

  button {
    background: var(--blue);
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
  }

  button:hover {
    background: #2f56cc;
    transform: translateY(-1px);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
  }

  th, td {
    padding: 10px;
    border: 2px solid #e2e8f0;
  }

  th {
    background: var(--blue);
    color: white;
  }

  td {
    background: var(--light);
  }

  .logout {
    text-align: center;
    margin-top: 20px;
  }

  .logout a {
    text-decoration: none;
    color: var(--dark);
    font-weight: 600;
  }

  .logout a:hover {
    text-decoration: underline;
  }

</style>
</head>
<body>
  <div class="container">
    <h1>📅 Emploi du Temps de <?= htmlspecialchars($username) ?></h1>

    <form id="scheduleForm">
      <select id="day" required>
        <option value="">Jour</option>
        <option>Lundi</option>
        <option>Mardi</option>
        <option>Mercredi</option>
        <option>Jeudi</option>
        <option>Vendredi</option>
        <option>Samedi</option>
      </select>
      <input type="time" id="startTime" required>
      <input type="time" id="endTime" required>
      <input type="text" id="activity" placeholder="Activité (ex : Maths, sport...)" required>
      <button type="submit">Ajouter</button>
    </form>

    <table id="scheduleTable">
      <thead>
        <tr>
          <th>Jour</th>
          <th>Heure début</th>
          <th>Heure fin</th>
          <th>Activité</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <div class="logout">
      <a href="planning.php">⬅ Retour au planning</a> | 
      <a href="logout.php">Déconnexion</a>
    </div>
  </div>

<script>
const form = document.getElementById('scheduleForm');
const tableBody = document.querySelector('#scheduleTable tbody');

form.addEventListener('submit', function(e) {
  e.preventDefault();

  const day = document.getElementById('day').value;
  const start = document.getElementById('startTime').value;
  const end = document.getElementById('endTime').value;
  const activity = document.getElementById('activity').value;

  if (!day || !start || !end || !activity) return;

  const row = document.createElement('tr');
  row.innerHTML = `
    <td>${day}</td>
    <td>${start}</td>
    <td>${end}</td>
    <td>${activity}</td>
    <td><button class="deleteBtn" style="background:#dc2626;color:white;border:none;padding:6px 10px;border-radius:6px;">Supprimer</button></td>
  `;

  tableBody.appendChild(row);
  form.reset();
});

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('deleteBtn')) {
    e.target.closest('tr').remove();
  }
});
</script>
</body>
</html>
