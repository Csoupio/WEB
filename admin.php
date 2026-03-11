<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dd($a) {
    echo("<pre><code>");
    var_dump($a);
    echo("</code></pre>");
    die();
}

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

// ✅ Message de succès après redirection
$success = isset($_GET['success']) && $_GET['success'] == 1;

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();

$sqlClients = "SELECT * FROM clients";
$stmtClients = $pdo->query($sqlClients);
$clients = $stmtClients->fetchAll();

$sqlProjects = "SELECT projets.*, clients.Nom AS ClientsNom 
                FROM projets 
                JOIN clients ON projets.ClientsID = clients.ID";
$stmtProjects = $pdo->query($sqlProjects);
$projets = $stmtProjects->fetchAll();

$sqlTickets = "SELECT ticket.*, projets.Nom AS projetNom 
                FROM ticket 
                JOIN projets ON ticket.IDProjet = projets.ID";
$stmtTickets = $pdo->query($sqlTickets);
$tickets = $stmtTickets->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ✅ CORRECTION : titre de page pertinent -->
    <title>Espace administrateur</title>
    <link rel="stylesheet" href="ticket-template.css">
   
</head>
<body>
    <header>
        <div class="bandeau">
            <div class="start">
                <a href="index.html">
                    <img src="./assets/logo.png" class="Logo" alt="Logo">
                </a>
            </div>
            <div class="middle">
                Espace administrateur
            </div>
            <div class="end">
                <button id="loginmenu" class="login" type="button">Votre Compte</button>
                <div id="dropdownMenu" class="dropdown-content">
                    <!-- ✅ CORRECTION : liens vers les fichiers .php, pas .html -->
                    <a href="projets-list.php" class="menu-item">Mes projets</a>
                    <a href="clients.php" class="menu-item">Dashboard</a>
                    <a href="index.html" class="menu-item">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    

    <div class="corp">

        <!-- ===== GESTION UTILISATEURS ===== -->
        <div class="tuile" id="users">
            <h3>Gérer les utilisateurs</h3>
            <form class="admin-form" action="Add.php?type=users" method="POST" id="formAdminUser">
                <label>Nom</label>
                <input class="textzone" type="text" placeholder="Prénom Nom" id="adminUserPrenom" name="nom">
                <div id="adminUserPrenomError" class="error-text titanic">Veuillez renseigner le nom de l'utilisateur.</div>

                <label>Email</label>
                <input class="textzone" type="email" placeholder="utilisateur@exemple.com" id="adminUserEmail" name="email">
                <div id="adminUserEmailError" class="error-text titanic">Veuillez renseigner un email valide.</div>

                <!-- ✅ AJOUT : champ mot de passe requis par Add.php -->
                <label>Mot de passe</label>
                <input class="textzone" type="password" placeholder="Mot de passe" id="adminUserPassword" name="password">
                <div id="adminUserPasswordError" class="error-text titanic">Veuillez renseigner un mot de passe.</div>

                <label>Rôle</label>
                <!-- ✅ CORRECTION : ajout des value sur les options -->
                <select id="adminUserRole" name="role" class="textzone">
                    <option value="Client">Client</option>
                    <option value="Collaborateur">Collaborateur</option>
                    <option value="Administrateur">Administrateur</option>
                </select>

                <div class="admin-actions" style="margin-top:10px;">
                    <button class="btn-add-comment" type="submit">Créer</button>
                    <!-- ✅ CORRECTION : suppression de l'espace dans type="button " -->
                    <button class="btn-danger" type="button">Supprimer</button>
                </div>
            </form>
            <hr>
            <h4>Liste utilisateurs</h4>
            <div class="table-wrapper">
                <table class="Tickets-table">
                    <thead class="Tickets-head">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody id="adminUserTable">
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u["ID"]) ?></td>
                                <td><?= htmlspecialchars($u["Nom"]) ?></td>
                                <td><?= htmlspecialchars($u["email"]) ?></td>
                                <td><?= htmlspecialchars($u["role"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== GESTION CLIENTS ===== -->
        <div class="tuile" id="clients">
            <h3>Gérer les clients</h3>
            <form class="admin-form" action="Add.php?type=client" method="POST" id="formAdminClient">
                <label>Nom du client</label>
                <input class="textzone" type="text" placeholder="Nom client" name="nom_client" id="adminClientName">
                <div id="adminClientNameError" class="error-text titanic">Veuillez renseigner le nom du client.</div>

                <label>Contact</label>
                <input class="textzone" type="email" placeholder="contact@client.com" name="email_client" id="adminClientEmail">
                <div id="adminClientEmailError" class="error-text titanic">Veuillez renseigner un email valide.</div>

                <input type="hidden" name="client_id" id="adminClientId">
                <div class="admin-actions" style="margin-top:10px;">
                    <button class="btn-add-comment" type="submit">Ajouter</button>
                    <button class="btn-c" type="button" id="cancelEditClient">Annuler</button>
                </div>
            </form>
            <hr>
            <h4>Liste clients</h4>
            <div class="table-wrapper">
                <table class="Tickets-table">
                    <thead class="Tickets-head">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody id="adminClientTable">
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client["ID"]) ?></td>
                                <td><?= htmlspecialchars($client["Nom"]) ?></td>
                                <td><?= htmlspecialchars($client["email"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== GESTION PROJETS ===== -->
        <div class="tuile" id="projects">
            <h3>Créer / modifier projet</h3>
            <form class="admin-form" action="Add.php?type=projets" method="POST" id="formAdminProject">
                <label>Nom du projet</label>
                <input class="textzone" type="text" placeholder="Nom du projet" name="nom_projet" id="adminProjectName">
                <div id="adminProjectNameError" class="error-text titanic">Veuillez renseigner le nom du projet.</div>

                <label>Client</label>
                <select id="ClientSelect" name="id_client" class="textzone">
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['ID']) ?>">
                            <?= htmlspecialchars($client['Nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Description</label>
                <textarea class="new-comment" rows="3" name="desc" placeholder="Courte description" id="adminProjectDescription"></textarea>
                <div id="adminProjectDescriptionError" class="error-text titanic">Veuillez renseigner une description.</div>

                <div class="admin-actions" style="margin-top:10px;">
                    <button class="btn-add-comment" type="submit">Créer</button>
                    <button class="btn-c" type="button">Annuler</button>
                </div>
            </form>
            <hr>
            <h4>Liste des projets</h4>
            <div class="table-wrapper">
                <table class="Tickets-table">
                    <thead class="Tickets-head">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Client</th>
                        </tr>
                    </thead>
                    <tbody id="adminProjectTable">
                        <?php foreach ($projets as $projet): ?>
                            <tr>
                                <td><?= htmlspecialchars($projet["ID"]) ?></td>
                                <td><?= htmlspecialchars($projet["Nom"]) ?></td>
                                <td><?= htmlspecialchars($projet["ClientsNom"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== TICKETS / FORCER STATUTS ===== -->
        <div class="tuile" id="tickets">
            <h3>Tickets &amp; forcer statuts</h3>
            <div class="table-wrapper">
                <table class="Tickets-table">
                <thead class="Tickets-head">
                    <tr>
                        <th>ID</th>
                        <th>Projet</th>
                        <th>Nom</th>
                        <th>Statut actuel</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <!-- ✅ CORRECTION : </tr> placé DANS la boucle foreach, pas en dehors -->
                        <tr>
                            <td><?= htmlspecialchars($ticket["ID"]) ?></td>
                            <td><?= htmlspecialchars($ticket["projetNom"]) ?></td>
                            <td><?= htmlspecialchars($ticket["Nom"]) ?></td>
                            <td><?= htmlspecialchars($ticket["Status"]) ?></td>
                            <td class="force-status">
                                <!-- ✅ CORRECTION : formulaire POST avec l'ID du ticket pour que "Forcer" fonctionne -->
                                <form action="update.php?type=status" method="POST" style="display:flex; gap:6px; align-items:center;">
                                    <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket["ID"]) ?>">
                                    <select name="new_status" class="textzone" style="height:36px; width:auto;">
                                        <option value="Ouvert">Ouvert</option>
                                        <option value="En cours">En cours</option>
                                        <option value="Terminé">Terminé</option>
                                        <option value="Bloqué">Bloqué</option>
                                    </select>
                                    <button class="btn-add-comment" type="submit">Forcer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

    </div>
    <script src="script.js"></script>
</body>
</html>