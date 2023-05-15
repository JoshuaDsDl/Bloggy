<?php
require_once('config.php');

// Vérifier si un post a été sélectionné pour la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    // Récupérer l'identifiant du post à supprimer
    $post_id = $_POST['post_id'];

    // Supprimer le post de la base de données
    $stmt = $conn->prepare("DELETE FROM post WHERE Id = :post_id");
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();

    // Rediriger vers la page d'accueil
    header('Location: index.php');
    exit();
}

// Récupérer la liste des posts pour afficher dans le formulaire
$stmt = $conn->query("SELECT * FROM post");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Formulaire de suppression d'un post -->
<head>
    <link rel="stylesheet" href="css/admin.css">
</head>
<div id="content">
    <h2>Supprimer un post</h2>
    <form method="post">
        <div>
            <label for="post_id">Post :</label>
            <select id="post_id" name="post_id">
                <?php foreach($posts as $post): ?>
                    <option value="<?php echo $post['Id']; ?>"><?php echo $post['Title']; ?></option>
                <?php endforeach; ?>
            </select>
        </div><br>
        <div>
            <input type="submit" value="Supprimer">
        </div>
    </form>
</div>
