<?php
// logout.php — Détruire la session et rediriger vers l'accueil
session_start();
session_destroy();
header('Location: index.html');
exit;