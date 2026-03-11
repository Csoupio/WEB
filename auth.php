<?php
// auth.php — Authentification avec sessions et password_verify
session_start();

$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']    ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if (!$login || !$pass) {
        header('Location: login.html?error=Champs+manquants');
        exit;
    }

    // Chercher l'utilisateur par email uniquement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $login]);
    $user = $stmt->fetch();

    // Vérifier le mot de passe (compatible hash ET texte brut pour la migration)
    $ok = false;
    if ($user) {
        if (password_verify($pass, $user['password'])) {
            // Mot de passe hashé (nouveaux comptes)
            $ok = true;
        } elseif ($user['password'] === $pass) {
            // Mot de passe en clair (anciens comptes) — on rehash au passage
            $ok = true;
            $newHash = password_hash($pass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = :p WHERE ID = :id")
                ->execute([':p' => $newHash, ':id' => $user['ID']]);
        }
    }

    if ($ok) {
        // Stocker les infos de session
        $_SESSION['user_id']   = $user['ID'];
        $_SESSION['user_nom']  = $user['Nom'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email']= $user['email'];

        // Redirection selon le rôle
        switch ($user['role']) {
            case 'Administrateur': header('Location: admin.php');   break;
            case 'Collaborateur':  header('Location: admin.php');   break;
            case 'Client':
            default:               header('Location: clients.php'); break;
        }
        exit;
    } else {
        header('Location: login.html?error=Identifiants+incorrects');
        exit;
    }
}