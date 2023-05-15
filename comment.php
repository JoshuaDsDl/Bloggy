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

    // Vérification de la soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupération des données du formulaire
        $nickname = $_POST['nickname'];
        $comment = $_POST['comment'];
        $post_id = $_POST['post_id'];
        $date = date('Y-m-d H:i:s');

        // Requête SQL pour insérer le commentaire dans la base de données
        $sql = "INSERT INTO comment (NickName, Contents, Post_Id, CreationTimestamp) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nickname, $comment, $post_id, $date]);

        // Redirection vers la page du post
        header("Location: post.php?id=$post_id");
        exit();
    }
?>