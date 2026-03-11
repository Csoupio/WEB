<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
$user = "root";
$password = "root";
$type = $_GET['type'] ?? ''; 

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'client') {
        $stmt = $pdo->prepare("INSERT INTO clients (Nom, email) VALUES (:nom, :email)");        
        $stmt->execute([
            ':nom' =>  $_POST['nom_client'],
            ':email' => $_POST['email_client']
        ]);
    }  elseif ($type === 'projets') {
    $stmt = $pdo->prepare("INSERT INTO projets (Nom, ClientsID, Description) VALUES (:nom, :client_id, :desc)");
    $stmt->execute([
        ':nom'       => $_POST['nom_projet'],
        ':client_id' => $_POST['id_client'],
        ':desc'      => $_POST['desc'] ?? ''   // le champ "desc" envoyé par le formulaire
    ]);
    } elseif($type === 'users') {
        $stmt = $pdo->prepare("INSERT INTO users (Nom, email, role, password) VALUES (:nom, :email, :role, :password)");
        $stmt->execute([
            ':nom' => $_POST["nom"],
            ':email' => $_POST["email"],
            ':role' => $_POST["role"],
            ':password' => $_POST["password"]
        ]);
    }
    header('Location: admin.php?success=1');
}
