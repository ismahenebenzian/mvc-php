<?php

// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'root');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// On récupère les 5 derniers billets
$req = $bdd->query(
    "SELECT id, titre, contenu,
            DATE_FORMAT(date_creation, '%d/%m/%Y à %Hh%imin%ss') AS date_creation_fr
     FROM billets
     ORDER BY date_creation DESC
     LIMIT 0, 5"
);

// On construit $posts
$posts = [];
while ($row = $req->fetch()) {
    $post = [
        'title' => $row['titre'],
        'french_creation_date' => $row['date_creation_fr'],
        'content' => $row['contenu'],
    ];
    $posts[] = $post;
}

// On appelle l'affichage
require('templates/homepage.php');