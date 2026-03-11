<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, "root", "root", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("ERREUR CONNEXION BDD : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès direct interdit.");
}

$nom         = trim($_POST['title']             ?? '');
$description = trim($_POST['description']       ?? '');
$idProjet    = (int)($_POST['project']          ?? 0);
$priorite    = trim($_POST['priority']          ?? '');
$type        = trim($_POST['type']              ?? '');
$temps       = (float)($_POST['estimated_time'] ?? 0);

if (!$nom || !$idProjet) {
    die("Données manquantes : titre='$nom', projet='$idProjet'");
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO ticket (Nom, IDProjet, Status, Priorité, Type, Descritpion, Temps_estimé)
        VALUES (:nom, :idProjet, 'Ouvert', :priorite, :type, :description, :temps)
    ");

    $stmt->bindValue(':nom',         $nom);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':idProjet',    $idProjet, PDO::PARAM_INT);
    $stmt->bindValue(':priorite',    $priorite);
    $stmt->bindValue(':type',        $type);
    $stmt->bindValue(':temps',       $temps);
    $stmt->execute();

    $newId = $pdo->lastInsertId();

    header('Location: ticket-template.php?id=' . $newId);
    exit;

} catch (PDOException $e) {
    die("ERREUR SQL : " . $e->getMessage() . "<br><br>Vérifie les noms de colonnes dans ta table 'ticket' via phpMyAdmin > Structure.");
}