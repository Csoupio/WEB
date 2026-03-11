<?php
// register.php — Création de compte utilisateur
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

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom   = trim($_POST['Prénom']   ?? '');
    $nom      = trim($_POST['Nom']      ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // --- Validation ---
    if (!$prenom) $errors[] = "Le prénom est requis.";
    if (!$nom)    $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (strlen($password) < 6) $errors[] = "Le mot de passe doit faire au moins 6 caractères.";

    // Vérifier que l'email n'existe pas déjà
    if (empty($errors)) {
        $check = $pdo->prepare("SELECT ID FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }

    // --- Insertion ---
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (Nom, email, role, password)
            VALUES (:nom, :email, 'Client', :password)
        ");
        $stmt->execute([
            ':nom'      => $prenom . ' ' . $nom,
            ':email'    => $email,
            ':password' => $hash
        ]);
        // Redirection vers login avec message de succès
        header('Location: login.html?registered=1');
        exit;
    }
}

// Si erreurs, on redirige avec les messages encodés
$msg = urlencode(implode('|', $errors));
header("Location: new-account.html?errors=$msg");
exit;