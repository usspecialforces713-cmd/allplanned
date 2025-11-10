<?php
// bulk_create.php — upload CSV username,password (sans header)
require_once 'database.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
    $f = $_FILES['csv']['tmp_name'];
    if (($handle = fopen($f, 'r')) !== false) {
        $created = 0; $skipped = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $username = trim($data[0] ?? '');
            $plain = $data[1] ?? '';
            if ($username === '' || $plain === '') continue;
            // existe ?
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) { $skipped++; $stmt->close(); continue; }
            $stmt->close();
            $hash = password_hash($plain, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $ins->bind_param("ss", $username, $hash);
            if ($ins->execute()) $created++;
            $ins->close();
        }
        fclose($handle);
        $msg = "Création terminée : $created créé(s), $skipped ignoré(s).";
    } else {
        $msg = "Impossible d'ouvrir le fichier.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Import CSV</title></head>
<body>
  <h2>Importer des comptes (CSV)</h2>
  <?php if ($msg) echo '<p>'.htmlspecialchars($msg).'</p>'; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Fichier CSV (username,password)<br><input type="file" name="csv" accept=".csv" required></label><br><br>
    <button type="submit">Importer</button>
  </form>
  <p>Ex : <code>alex,abcd</code></p>
</body>
</html>
