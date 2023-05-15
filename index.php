<?php
require_once('config.php');

// Requête SQL pour récupérer les posts avec le nom de l'auteur et de la catégorie, triés par date de création décroissante
$sql = "SELECT post.*, author.FirstName, author.LastName, category.Name AS CategoryName FROM post
        JOIN author ON post.Author_Id = author.Id
        JOIN category ON post.Category_Id = category.Id
        ORDER BY CreationTimestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/accueil.css">
</head>
<body>
    <!-- Barre de navigation -->
    <div id="topelem">
        <a href="#">
            <img id="biglogo" src="img/textlogo.png">
        </a>

        <nav>
            <a href="admin.php">
                <img id="adminlogo" src="img/administrator.png">
            </a>
        </nav>
    </div>

    <!-- Contenu -->
    <div id="articlelist">
        <?php if (count($result) == 0): ?>
            <h3>Il n'y a pas de posts pour l'instant !</h3>
            <h3>Lorsqu'il y en aura, ils apparaîtront ici.</h3>
        <?php else: ?>
            <?php foreach ($result as $row): ?>
            <div id="article">
                <a href=<?php echo 'post.php?id='.$row['Id']; ?>><?php echo $row['Title']; ?></a>
                <p class="infos"><?php echo $row['FirstName'] . " " . $row['LastName']; ?> | <?php echo $row['CategoryName']; ?> | <?php echo $row['CreationTimestamp']; ?></p>
                <p><?php echo substr($row['Contents'], 0, 100).'...'; ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Bas de la page -->
    <div id="bottomelem">
        <p>Bloggy, un projet réalisé à l'ESGI par Joshua Deschietere</p>
    </div>
</body>
</html>