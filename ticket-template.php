<?php
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

// Récupérer l'ID du ticket depuis l'URL
$idTicket = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$idTicket) {
    die("Ticket introuvable.");
}

// Charger le ticket avec le nom du projet
$stmt = $pdo->prepare("
    SELECT ticket.*, projets.Nom AS projetNom
    FROM ticket
    JOIN projets ON ticket.IDProjet = projets.ID
    WHERE ticket.ID = ?
");
$stmt->execute([$idTicket]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die("Ticket introuvable.");
}

// Classe CSS selon la priorité
$priorityClass = match(strtolower($ticket['Priorité'] ?? '')) {
    'haute'   => 'priority-high',
    'moyenne' => 'priority-medium',
    'basse'   => 'priority-low',
    default   => ''
};

// Classe CSS selon le statut
$statusClass = match(strtolower($ticket['Status'] ?? '')) {
    'en cours'  => 'en-cours',
    'terminé'   => 'priority-low',
    'bloqué'    => 'priority-high',
    default     => ''
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ticket['Nom']) ?></title>
    <link rel="stylesheet" href="ticket-template.css">
</head>
<body>
    <header>
        <div class="bandeau">
            <div class='start'>
                <a href="index.html"><img src="./assets/logo.png" class="Logo"></a>
            </div>
            <div class="middle">Détails du ticket</div>
            <div class='end'>
                <button id="loginmenu" class="login" type="button">Votre Compte</button>
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="projets-list.php" class="menu-item">Mes projets</a>
                    <a href="clients.php" class="menu-item">Dashboard</a>
                    <a href="index.html" class="menu-item">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    <div class="corp">
        <!-- Colonne gauche -->
        <div class="left-panel">
            <div class="ticket-details tuile">
                <p class="titre"><?= htmlspecialchars($ticket['Nom']) ?></p>
                <p style="color:#666; font-size:13px; margin-bottom:8px;">
                    Projet : <strong><?= htmlspecialchars($ticket['projetNom']) ?></strong>
                </p>
                <div class="desc">
                    <p><?= nl2br(htmlspecialchars($ticket['Descritpion'] ?? '')) ?></p>
                </div>
            </div>

            <div class="chat tuile">
                <h3>Commentaires</h3>
                <div class="chat-log">
                    <p style="color:#aaa; font-size:13px;">Aucun commentaire pour l'instant.</p>
                </div>
                <textarea class="new-comment" placeholder="Ajouter un commentaire..." rows="4"></textarea>
                <button type="button" class="btn-add-comment">Ajouter</button>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="right-panel">
            <div class="status tuile">
                <p class="titre">Statut :</p>
                <p class="<?= $statusClass ?>"><?= htmlspecialchars($ticket['Status']) ?></p>

                <p class="titre">Priorité :</p>
                <p class="<?= $priorityClass ?>"><?= htmlspecialchars($ticket['Priorité'] ?? '-') ?></p>

                <p class="titre">Type de ticket :</p>
                <p><?= htmlspecialchars($ticket['Type'] ?? '-') ?></p>
            </div>

            <div class="temps tuile">
                <p class="titre">Temps estimé :</p>
                <p class="temp-estimee"><?= htmlspecialchars($ticket['Temps_Estime'] ?? '0') ?>h</p>
                <p class="titre">Temps réel passé :</p>
                <progress max="<?= (int)($ticket['Temps_Estime'] ?? 0) ?>" value="0"></progress>
            </div>

            <div class="collaborateur tuile">
                <p class="titre">Collaborateurs :</p>
                <div class="avatar-stack">
                    <div class="avatar" title="?">?</div>
                </div>
            </div>

            <a href="projet-template.php?id=<?= (int)$ticket['IDProjet'] ?>">
                <button type="button" class="btn-staff tuile">← Retour au projet</button>
            </a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>