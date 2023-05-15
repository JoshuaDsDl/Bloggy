<?php
require_once('config.php');

// Récupérer les catégories
$stmt = $conn->query("SELECT * FROM category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les auteurs
$stmt = $conn->query("SELECT * FROM author");
$authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $content = $_POST['content'];

    // Insérer les données dans la base de données
    $stmt = $conn->prepare("INSERT INTO post (Title, Contents, CreationTimestamp, Author_Id, Category_Id) VALUES (:title, :contents, NOW(), :author, :category)");
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':contents', $content);
    $stmt->bindValue(':author', $author);
    $stmt->bindValue(':category', $category);
    $stmt->execute();

    // Rediriger vers l'accueil
    header('Location: index.php');
    exit();
}
?>

<!-- Formulaire d'ajout d'un post -->
<head>
    <link rel="stylesheet" href="css/admin.css">
</head>
<div id="content">
    <h2>Ajouter un post</h2>
    <form method="post">
        <div>
            <label for="title">Titre :</label>
            <input type="text" id="title" name="title" required>
        </div><br>
        <div>
            <label for="category">Catégorie :</label>
            <select id="category" name="category">
                <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div><br>
        <div>
            <label for="author">Auteur :</label><br>
            <select id="author" name="author" required>
                <?php foreach($authors as $author): ?>
                    <option value="<?php echo $author['Id']; ?>"><?php echo $author['FirstName'] . ' ' . $author['LastName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div><br>
        <div>
            <label for="content">Contenu :</label>
            <textarea id="content" name="content" required></textarea>
        </div><br>
        <div>
            <input type="submit" value="Ajouter">
        </div>
    </form>
</div>
