<?php

function dbConnect()
{
    try {
        $database = new PDO(
            'mysql:host=localhost;dbname=blog;charset=utf8',
            'root',
            'root'   
        );
        return $database;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function getPosts()
{
    $database = dbConnect();

    $statement = $database->query(
        "SELECT id, title, content,
                DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%imin%ss') AS formatted_creation_date
         FROM posts
         ORDER BY creation_date DESC
         LIMIT 0, 5"
    );

    $posts = [];
    while (($row = $statement->fetch())) {
        $post = [
            'title'                   => $row['title'],
            'formatted_creation_date' => $row['formatted_creation_date'],
            'content'                 => $row['content'],
            'identifier'              => $row['id'],   
        ];
        $posts[] = $post;
    }
    return $posts;
}

function getPost($identifier)
{
    $database = dbConnect();

    $statement = $database->prepare(
        "SELECT id, title, content,
                DATE_FORMAT(creation_date, '%d/%m/%Y à %Hh%imin%ss') AS formatted_creation_date
         FROM posts
         WHERE id = ?"
    );
    $statement->execute([$identifier]);
    $row = $statement->fetch();

    if (!$row) {
        die('Erreur : billet introuvable');
    }

    return [
        'title'                   => $row['title'],
        'formatted_creation_date' => $row['formatted_creation_date'],
        'content'                 => $row['content'],
    ];
}

function getComments($identifier)
{
    $database = dbConnect();

    $statement = $database->prepare(
        "SELECT id, author, comment,
                DATE_FORMAT(comment_date, '%d/%m/%Y à %Hh%imin%ss') AS formatted_creation_date
         FROM comments
         WHERE post_id = ?
         ORDER BY comment_date DESC"
    );
    $statement->execute([$identifier]);

    $comments = [];
    while (($row = $statement->fetch())) {
        $comments[] = [
            'author'                  => $row['author'],
            'formatted_creation_date' => $row['formatted_creation_date'],
            'comment'                 => $row['comment'],
        ];
    }
    return $comments;
}