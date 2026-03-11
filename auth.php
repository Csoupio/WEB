<?php
// auth.php
$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
$user = "root";
$password = "root";

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $pass = $_POST['password'];

    // On cherche l'utilisateur avec l'email ET le mot de passe exact
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :pass");
    $stmt->execute([':email' => $login, ':pass' => $pass]);
    $userFound = $stmt->fetch();

    if ($userFound) {
        // Ça match ! On redirige vers l'admin
        header('Location: admin.php');
    } else {
        // Raté, on renvoie au login avec un message
        header('Location: login.html?error=1');
    }
}