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

// Charger les projets pour le select
$projets = $pdo->query("SELECT ID, Nom FROM projets")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de ticket</title>
    <link rel="stylesheet" href="ticket-template.css">
</head>
<body>
    <header>
        <div class="bandeau">
            <div class='start'>
                <a href="index.html"><img src="./assets/logo.png" class="Logo"></a>
            </div>
            <div class="middle">Création de ticket</div>
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
        <div class="card">
            <h3 class="titre">Créer un ticket</h3>
            <form class="form-creation" action="add-ticket.php" method="POST" id="ticketForm">

                <div class="form-row">
                    <label for="title">Titre</label>
                    <input id="title" type="text" name="title" class="textzone">
                    <div id="title_error" class="error-text titanic">Veuillez renseigner un titre.</div>
                </div>

                <div class="form-row">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="textzone" style="height:110px; resize:vertical;"></textarea>
                    <div id="description_error" class="error-text titanic">La description ne peut pas être vide.</div>
                </div>

                <div class="form-row">
                    <label for="project">Projet</label>
                    <select id="project" name="project" class="textzone">
                        <option value="">Sélectionner un projet</option>
                        <?php foreach ($projets as $projet): ?>
                            <option value="<?= $projet['ID'] ?>"><?= htmlspecialchars($projet['Nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="project_error" class="error-text titanic">Veuillez sélectionner un projet.</div>
                </div>

                <div class="form-row">
                    <label for="priority">Priorité</label>
                    <select id="priority" name="priority" class="textzone">
                        <option value="">Sélectionner la priorité</option>
                        <option value="Basse">Basse</option>
                        <option value="Moyenne">Moyenne</option>
                        <option value="Haute">Haute</option>
                    </select>
                    <div id="priority_error" class="error-text titanic">Veuillez sélectionner une priorité.</div>
                </div>

                <div class="form-row">
                    <label for="type">Type de ticket</label>
                    <select id="type" name="type" class="textzone">
                        <option value="">Sélectionner le type</option>
                        <option value="Inclus">Inclus</option>
                        <option value="Facturables">Facturables</option>
                    </select>
                    <div id="type_error" class="error-text titanic">Veuillez sélectionner un type de ticket.</div>
                </div>

                <div class="form-row">
                    <label for="estimated_time">Temps estimé (heures)</label>
                    <input id="estimated_time" type="number" name="estimated_time" class="textzone" min="0">
                    <div id="estimated_time_error" class="error-text titanic">Veuillez indiquer un temps estimé supérieur à 0.</div>
                </div>

                <button id="submitTicket" type="submit" class="btn-staff">Ajouter le ticket</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>