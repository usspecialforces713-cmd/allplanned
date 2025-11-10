<?php
session_start(); // Démarre la session pour pouvoir la détruire
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session complètement

// Redirige vers la page de connexion
header("Location: login.php");
exit();
?>
