<?php
// bulk_create.php — upload CSV username,password (sans header)
require_once 'database.php'; // $pdo
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv'])) {
    $f = $_FILES['csv']['tmp_name'];

    if (($handle = fopen($f, 'r')) !== false) {
        $created = 0;
        $skipped = 0;

        // Préparer les requêtes une seule fois (plus rapide)
        $checkStmt = $pdo->prepare(
            "SELECT id FROM users WHERE username = :username"
        );
        $insertStmt = $pdo->prepare(
            "INSERT INTO users (username, password) VALUES (:username, :password)"
        );

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $username = trim($data[0] ?? '');
            $plain    = $data[1] ?? '';

            if ($username === '' || $plain === '') {
                continue;
            }

            // Existe ?
            $checkStmt->execute([
                ':username' => $username
            ]);

            if ($checkStmt->fetch()) {
                $skipped++;
                continue;
            }

            $hash = password_hash($plain, PASSWORD_DEFAULT);

            if ($insertStmt->execute([
                ':username' => $username,
                ':password' => $hash
            ])) {
                $created++;
            }
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

