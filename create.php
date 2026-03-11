<?php

$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
$user = "root";
$password = "root";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

// on gère le traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "INSERT INTO clients (Nom, email, type) VALUES (:Nom, :email, :type)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":Nom"   => $_POST["Nom"],
        ":email" => $_POST["email"],
        ":type" => $_POST["type"]
    ]);
}


header("location:admin.php");