<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    



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
    $sqlTickets = "SELECT ticket.*, projets.Nom AS projetNom 
                        FROM ticket 
                        JOIN projets ON ticket.IDProjet = projets.ID";
    $stmtTickets = $pdo->query($sqlTickets);
    $tickets = $stmtTickets->fetchAll();

    // 1. Compter les tickets ouverts
    $sqlOuverts = "SELECT COUNT(*) FROM ticket WHERE Status = 'ouvert'";
    $ticketsOuverts = $pdo->query($sqlOuverts)->fetchColumn();
    // 2. Compter les projets actifs
    $sqlProjets = "SELECT COUNT(*) FROM projets"; // Ajuste si tu as une colonne 'Status' dans projets
    $projetsActifs = $pdo->query($sqlProjets)->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="clients.css">
    
</head>
<body>
    <header>
        <div class="bandeau">
            <div class='start'><a href="index.html">
                <img src="./assets/logo.png" class="Logo"></a>
                
            </div>
            <div class ="middle">
                Votre espace clients
            </div>
            <div class='end'>
                
                <button id="loginmenu" class="login" type="button" > Votre Compte</button>
                
                <div id="dropdownMenu" class="dropdown-content">
                    <a href="projets-list.php" class="menu-item">Mes projets</a> 
                    <a href="admin.php" class="menu-item">Espace administrateur</a>    
                    <a href="clients.php" class="menu-item">Dashbord</a>
                    <a href="index.html" class="menu-item">Se déconnecter</a>
                </div>

                
                
                
            </div>

        </div>
    </header>
    

    <div class="corp">
        <div class="dashbord tuile">
            <h1>Votre dashbord</h1>
            <div class="cards">
                <div class="card">
                    <h3 class="card-title">Tickets ouverts</h3>
                    <p class="card-value"><?php echo $ticketsOuverts; ?></p>
                </div>
                <div class="card">
                    <h3 class="card-title">Projets actifs</h3>
                    <p class="card-value"><?php echo $projetsActifs; ?></p>                </div>
                <!-- <div class="card">
                    <h3 class="card-title">Heures facturables</h3>
                    <p class="card-value">128h</p>
                </div> -->
                
            </div>
        </div>

        <div class="tickets tuile">
            <h2>Apperçu de vos Tickets</h2>
        <section id="filtre">
            
            <div id="status" name="status" class ="status">
                <label for="status">Filtrer par statut :</label>
                <button value="" class="filter-btn">Tous</button>
                <button value="ouvert" class="filter-btn">Ouvert</button>
                <button value="en-cours" class="filter-btn">En cours</button>
                <button value="termine" class="filter-btn">Terminé</button>
            </div>

            
            <div id="priority" name="priority" class ="status">
                <label for="priority">Filtrer par priorité :</label>
                <button  class="filter-btn-Statut">Tous</button>
                <button  class="filter-btn-Statut">Haute</button>
                <button  class="filter-btn-Statut">Moyenne</button>
                <button  class="filter-btn-Statut">Basse</button>
            </div>
        </section>
        <table class ="Tickets-table">
            <thead class="Tickets-head">
                <tr>
                <td>ID</td>
                <td >Projets</td>
                <td>Nom du ticket</td>
                <td >Status</td>
                <td >Priorité</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tickets as $ticket): ?>
                    <tr class="Tickets-ligne" onclick="window.location.href = 'ticket-template.php?id=<?php echo$ticket['ID']; ?>'" style="cursor:pointer;" >
                        <td><?php echo $ticket["ID"]; ?></td>
                        <td><?php echo $ticket["projetNom"]; ?></td>
                        <td><?php echo $ticket["Nom"]; ?></td>
                        <td class="status"><?php echo $ticket["Status"]; ?></td>
                        <td class="priority"><?php echo $ticket["Priorité"]; ?></td>
                    </tr>
                <?php endforeach; ?>  
            </tbody>
        </table>

        <script src="script.js"></script>
</body>
</html>