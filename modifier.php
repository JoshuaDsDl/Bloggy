<?php
require_once('config.php');
$post = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si un post est sélectionné
    if (isset($_POST['post_id'])) {
        $postID = $_POST['post_id'];

        // Récupérer les détails du post
        $stmt = $conn->prepare("SELECT * FROM post WHERE Id = :post_id");
        $stmt->bindParam(':post_id', $postID);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le post existe
        if ($post) {
            // Récupérer les catégories
            $stmt = $conn->query("SELECT * FROM category");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les auteurs
            $stmt = $conn->query("SELECT * FROM author");
            $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si le formulaire est soumis pour la modification du post
            if (isset($_POST['submit'])) {
                $newTitle = isset($_POST['title']) ? $_POST['title'] : '';
                $newContent = isset($_POST['content']) ? $_POST['content'] : '';
                $newCategory = isset($_POST['category']) ? $_POST['category'] : '';
                $newAuthor = isset($_POST['author']) ? $_POST['author'] : '';

                // Mettre à jour le post dans la base de données
                $stmt = $conn->prepare("UPDATE post SET Title = :title, Contents = :content, Category_Id = :category_id, Author_Id = :author_id WHERE Id = :post_id");
                $stmt->bindParam(':title', $newTitle);
                $stmt->bindParam(':content', $newContent);
                $stmt->bindParam(':category_id', $newCategory);
                $stmt->bindParam(':author_id', $newAuthor);
                $stmt->bindParam(':post_id', $postID);
                $stmt->execute();

                // Rediriger vers la page d'administration
                header("Location: index.php");
                exit();
            }
        } else {
            // Rediriger si le post n'existe pas
            header("Location: admin.php?action=modifier");
            exit();
        }
    }
}

// Récupérer tous les posts
$stmt = $conn->query("SELECT * FROM post");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Formulaire de modification d'un post -->
<head>
    <link rel="stylesheet" href="css/admin.css">
</head>
<div id="content">
    <h2>Modifier un post</h2>
    <form method="post" action="admin.php?action=modifier">
        <input type="hidden" name="post_id" value="<?php echo $postID; ?>">

        <?php if (!$post): ?>
            <div>
                <label for="post">Post à modifier :</label><br>
                <select id="post" name="post_id">
                <option value="">Sélectionner un post</option>
                    <?php foreach ($posts as $postItem) : ?>
                        <option value="<?php echo $postItem['Id']; ?>"><?php echo $postItem['Title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div><br>
            <div>
                <input type="submit" value="Sélectionner">
            </div>
        <?php endif; ?>

        <?php if ($post): ?>
            <div>
                <label for="title">Titre du post :</label><br>
                <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : $post['Title']; ?>">
            </div><br>
            <div>
                <label for="content">Contenu :</label><br>
                <textarea id="content" name="content"><?php echo isset($_POST['content']) ? $_POST['content'] : $post['Contents']; ?></textarea>
            </div><br>
            <div>
        <label for="category">Catégorie :</label><br>
        <select id="category" name="category">
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['Id']; ?>" <?php echo $category['Id'] == $post['Category_Id'] ? 'selected' : ''; ?>><?php echo $category['Name']; ?></option>
            <?php endforeach; ?>
        </select>
        </div><br>
        <div>
            <label for="author">Auteur :</label><br>
            <select id="author" name="author">
                <?php foreach ($authors as $author) : ?>
                    <option value="<?php echo $author['Id']; ?>" <?php echo $author['Id'] == $post['Author_Id'] ? 'selected' : ''; ?>><?php echo $author['FirstName'] . ' ' . $author['LastName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div><br>
        <div id="submitbtns">
            <a href="./admin.php?action=modifier"><input type="button" id="leftbtn" value="Retour"></a>
            <input type="submit" name="submit" id="rightbtn" value="Modifier">
        </div>
        <?php endif; ?>

    </form>
</div>
