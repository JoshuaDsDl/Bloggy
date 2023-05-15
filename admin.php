<?php
require_once('config.php');

if(isset($_GET['action'])){
    $action = $_GET['action'];
} else {
    $action = "null";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <!-- Barre de navigation -->
    <div id="topelem">
        <a href="./index.php">
            <img id="biglogo" src="img/textlogo.png">
        </a>

        <nav>
            <a href="./admin.php">
                <img id="adminlogo" src="img/administrator.png">
            </a>
        </nav>
    </div>

    <!-- Contenu de la page -->
    <div id="content">
        <?php
            // switch case pour afficher le contenu en fonction de l'action
            switch($action) {
                case "ajouter":
                    include 'ajouter.php';
                    break;
                case "modifier":
                    include 'modifier.php';
                    break;
                case "supprimer":
                    include "supprimer.php";
                    break;
                default:
                    // afficher les boutons
        ?>
                    <a href="?action=ajouter"><button>Ajouter un post</button></a>
                    <a href="?action=modifier"><button>Modifier le titre et/ou contenu d'un post</button></a>
                    <a href="?action=supprimer"><button>Supprimer un post</button></a>
        <?php
                    break;
            }
        ?>
    </div>

    <!-- Bas de la page -->
    <div id="bottomelem">
        <p>Bloggy, un projet réalisé à l'ESGI par Joshua Deschietere</p>
    </div>
</body>
</html>