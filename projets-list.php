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
   $sqlProjects = "SELECT projets.*, clients.Nom AS ClientsNom 
                    FROM projets 
                    JOIN clients ON projets.ClientsID = clients.ID";
    $stmtProjects = $pdo->query($sqlProjects);
    $projets = $stmtProjects->fetchAll();

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
    <header>
        <div class="bandeau">
            <div class='start'><a href="index.html">
                <img src="./assets/logo.png" class="Logo"></a>
                
            </div>
            <div class ="middle">
                Vos projets
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
    <section >
        <h2>Liste des projets</h2>
        <table class="Tickets-table">
            <thead class="Tickets-head">
                <tr>
                    <th>Nom du projet</th>
                    <th>Nom du client</th>
                    <th>Heures facturables</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projets as $projet): ?>
                    <tr class="Tickets-ligne" onclick="window.location.href = 'projet-template.php?id=<?php echo $projet['ID']; ?>'" style="cursor:pointer;">
                        <td><?php echo $projet["Nom"]; ?></td>
                        <td><?php echo $projet["ClientsNom"]; ?></td>
                        <td><?php echo $projet["Heures_Facturables"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            
        </table>
    </section>

    
</body>
</html>