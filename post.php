<?php require_once('comment.php'); ?>

<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "blog";

    // Connexion à la base de données
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Configuration de la connexion PDO pour afficher les erreurs
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connexion réussie";
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }

    // Vérification de l'existence de l'id dans l'URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        // Si l'id n'est pas présent dans l'URL, on redirige vers la page d'accueil
        header("Location: index.php");
        exit();
    }

    // Requête SQL pour récupérer le post, le nom de l'auteur et la catégorie
    $sql = "SELECT post.*, author.FirstName, author.LastName, category.Name as CategoryName FROM post 
        JOIN author ON post.Author_Id = author.Id 
        JOIN category ON post.Category_Id = category.Id 
        WHERE post.Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Requête SQL pour récupérer les commentaires
    $sql = "SELECT * FROM comment WHERE Post_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $result['Title']; ?></title>
    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <!-- Barre de navigation -->
    <div id="topelem">
        <a href="./">
            <img id="biglogo" src="img/textlogo.png">
        </a>

        <nav>
            <a href="./admin.php">
                <img id="adminlogo" src="img/administrator.png">
            </a>
        </nav>
    </div>

    <!-- Contenu -->
    <div id="articlelist">
        <div id="article">
            <a href="#"><?php echo $result['Title']; ?></a>
            <p class="infos"><?php echo $result['FirstName'] . " " . $result['LastName']; ?> | <?php echo $result['CategoryName']; ?> | <?php echo $result['CreationTimestamp']; ?></p>
            <p><?php echo $result['Contents']; ?></p>
        </div>
    </div>

    <!-- Post des commentaires -->
    <h3 id="spacer">~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</h3>
    <div id="post-comment">
        <h2>Laissez un commentaire</h2>
        <form action="comment.php" method="post">
            <label for="nickname">Pseudo:</label><br>
            <input type="text" name="nickname" id="nickname"><br><br>
            <label for="comment">Commentaire:</label><br>
            <textarea name="comment" id="comment" cols="30" rows="10"></textarea><br><br>
            <input type="hidden" name="post_id" value="<?php echo $id; ?>">
            <input type="submit" value="Poster">
        </form>
    </div>

    <!-- Affichage des commentaires -->
    <h3 id="spacer">~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</h3>
    <div id="comments">
        <h2>Commentaires</h2>
        <?php if (count($comments) == 0): ?>
            <p>Il n'y a pas de commentaires pour l'instant.</p>
        <?php else: ?>
            <?php foreach($comments as $comment): ?>
                <div id="comment">
                    <p class="nickname"><?php echo $comment['NickName']; ?> | <?php echo $comment['CreationTimestamp']; ?></p>
                    <p class="content"><?php echo $comment['Contents']; ?></p>
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
