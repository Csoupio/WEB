<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
// Connexion BDD (identique à clients.php)
$dsn = "mysql:host=localhost:3306;dbname=tickets;charset=utf8mb4";
$pdo = new PDO($dsn, "root", "root");

// 1. On récupère l'ID passé dans l'URL
$idProjet = $_GET['id'];

// 2. On récupère les infos du projet et du client
$sqlProjet = "SELECT projets.*, clients.Nom AS clientNom 
              FROM projets 
              JOIN clients ON projets.ClientsID = clients.ID 
              WHERE projets.ID = ?";
$stmt = $pdo->prepare($sqlProjet);
$stmt->execute([$idProjet]);
$projet = $stmt->fetch();

// 3. On récupère tous les tickets de CE projet
$sqlTickets = "SELECT * FROM ticket WHERE IDProjet = ?";
$stmtT = $pdo->prepare($sqlTickets);
$stmtT->execute([$idProjet]);
$ticketsDuProjet = $stmtT->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="ticket-template.css">
</head>
<body>
    <body>
    <header>
        <div class="bandeau">
            <div class='start'><a href="index.html">
                <img src="./assets/logo.png" class="Logo"></a>
                
            </div>
            <div class ="middle">
                Détails du projet
            </div>
            <div class='end'>
                
                <button id="loginmenu" class="login" type="button" > Votre Compte</button>
                
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="projets-list.php" class="menu-item">Mes projets</a> 
                    <a href="clients.php" class="menu-item">Dashbord</a>
                    <a href="index.html" class="menu-item">Se déconnecter</a>
                </div>
                <script src="script.js"></script>
                
                
            </div>

        </div>
    </header>


    <div class="corp">
        <div class="left-panel">
            <div class="ticket-details tuile">
                <div class="titre">
                    <p><?php echo htmlspecialchars($projet['Nom']); ?> (Client : <?php echo htmlspecialchars($projet['clientNom']); ?>)</p>
                </div>
                <div class="desc">
                    <p><?php echo htmlspecialchars($projet['Description']); ?></p>
                </div>
            </div>

            <div class="chat tuile">
                <h2>Tickets liés au projet</h2>
                <table class="Tickets-table">
                    <thead class="Tickets-head">
                        <tr>
                            <td>ID</td><td>Nom du ticket</td><td>Status</td><td>Priorité</td>
                        </tr>
                    </thead>   
                    <?php foreach($ticketsDuProjet as $t): ?>
                        <tr class="Tickets-ligne"  onclick="window.location.href = 'ticket-template.php?id=<?php echo$ticket['ID']; ?>'" style="cursor:pointer;" >
                            <td><?php echo $t['ID']; ?></td>
                            <td><?php echo $t['Nom']; ?></td>
                            <td><?php echo $t['Status']; ?></td>
                            <td><?php echo $t['Priorité']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="contrat tuile">
                <p class="titre">Heures facturables totales :</p>
                <p class="en-cours"><?php echo $projet['Heures_Facturables']; ?>h</p>
                
                <p class="titre">Taux horaire :</p>
                <p class="priority-low">20€ / heure</p> </div>

                <p class="titre">Nombre d'heures restantes :</p>
                <p>35h</p>
            </div>
            
                


            

            <div class="collaborateur tuile">
                <p class="titre">collaborateurs ajouté au projet :</p>
                <div class="avatar-stack" aria-label="Liste des collaborateurs">
                        <div class="avatar" title="Alice">A</div>
                        <div class="avatar" title="Bob">B</div>
                        <div class="avatar" title="Charlie">C</div>
                        <div class="avatar" title="Dana">D</div>
                </div>
                
            </div>
            <button type="button" class="btn-staff tuile" onclick="window.location.href = 'ticket-creation.php'">Ajouter un ticket</button>
        </div>






    </div>
</body>
</html>