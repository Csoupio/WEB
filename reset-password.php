<?php
// reset-password.php — Réinitialisation du mot de passe (version simplifiée sans email)
// Pour une vraie appli, on enverrait un lien par email. Ici on vérifie juste que l'email existe
// et on permet de choisir un nouveau mot de passe directement.
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email       = trim($_POST['login']         ?? '');
$newPassword = trim($_POST['nouveauPswd']   ?? '');
$confirm     = trim($_POST['Confirmerpassword'] ?? '');

$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email invalide.";
}
if (strlen($newPassword) < 6) {
    $errors[] = "Le mot de passe doit faire au moins 6 caractères.";
}
if ($newPassword !== $confirm) {
    $errors[] = "Les mots de passe ne correspondent pas.";
}

if (!empty($errors)) {
    $msg = urlencode(implode('|', $errors));
    header("Location: login.html?reset_error=$msg");
    exit;
}

// Vérifier que l'email existe
$stmt = $pdo->prepare("SELECT ID FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.html?reset_error=" . urlencode("Aucun compte trouvé avec cet email."));
    exit;
}

// Mettre à jour le mot de passe
$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE users SET password = :p WHERE email = :email")
    ->execute([':p' => $hash, ':email' => $email]);

header('Location: login.html?reset=1');
exit;