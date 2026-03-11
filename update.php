<?php

$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
$dbUser = "root";
$dbPassword = "root";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['action'] ?? '';

    // Mise à jour d'un client (formulaire admin)
    if ($action === '' || $action === 'update_client') {
        $sql = "UPDATE clients SET Nom = :nom_client, email = :email_client WHERE ID = :client_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':client_id'    => $_POST['client_id'],
            ':nom_client'   => $_POST['nom_client'],
            ':email_client' => $_POST['email_client']
        ]);
        header("Location: admin.php?success=1");
        exit;
    }

    // Forcer le statut d'un ticket (appel fetch depuis admin.php)
    if ($action === 'force_status') {
        $statuts_autorises = ['Ouvert', 'En cours', 'Terminé', 'Bloqué'];

        if (
            isset($_POST['ticket_id'], $_POST['new_status']) &&
            in_array($_POST['new_status'], $statuts_autorises, true)
        ) {
            $sql = "UPDATE ticket SET Status = :status WHERE ID = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':status' => $_POST['new_status'],
                ':id'     => (int) $_POST['ticket_id']
            ]);
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Paramètres invalides']);
        }
        exit;
    }
}