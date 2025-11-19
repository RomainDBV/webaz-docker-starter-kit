<?php

declare(strict_types=1);

session_start();

require_once 'flight/Flight.php';
require_once 'config.php';

// Stocke la connexion dans Flight
Flight::set('connexion', $link);

// Route accueil
Flight::route('/', function() {
    Flight::render('accueil');
});

// API : tous les objets visibles
Flight::route('GET /api/objets', function() {
    $db = Flight::get('connexion');
    $sql = "SELECT id_objet, nom, type_objet, code_deverrouille, id_objet_precedent, indice,
                   trouve, visible, niveau_zoom_min, icone, indice,
                   ST_X(geom) AS lon, ST_Y(geom) AS lat
            FROM objets
            WHERE visible = true";

    $result = pg_query($db, $sql);
    $rows = pg_fetch_all($result);
    Flight::json($rows);
});

// API : objet précis
Flight::route('GET /api/objets/@id', function($id) {
    $db = Flight::get('connexion');
    $sql = "SELECT id_objet, nom, type_objet, code_deverrouille, id_objet_precedent, indice,
                   trouve, visible, niveau_zoom_min, icone, indice,
                   ST_X(geom) AS lon, ST_Y(geom) AS lat
            FROM objets
            WHERE id_objet = $1";

    $result = pg_query_params($db, $sql, [$id]);
    $row = pg_fetch_assoc($result);
    Flight::json($row);
});



Flight::route('POST /carte', function() {
    $db = Flight::get('connexion'); 
    $pseudo = $_POST['pseudo'];

    // Validation du pseudo : seules les lettres (a-zA-Z), chiffres (0-9), underscore (_) et tiret (-) sont autorisés
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $pseudo)) {
        Flight::render('accueil', ['error' => 'Pseudo invalide : seules les lettres, chiffres, _ et - sont autorisés']);
        return;
    }

    // Vérifie si le joueur existe déjà dans la base
    $sql = "SELECT id_joueur FROM joueurs WHERE pseudo = $1";
    $result = pg_query_params($db, $sql, [$pseudo]);
    $row = pg_fetch_assoc($result);

    if ($row) {
        // Si le joueur existe, on récupère son ID
        $id_joueur = $row['id_joueur'];
    } else {
        // Sinon, on insère le nouveau joueur dans la base et on récupère direct son ID
        $sql = "INSERT INTO joueurs (pseudo) VALUES ($1) RETURNING id_joueur";
        $result = pg_query_params($db, $sql, [$pseudo]);
        $row = pg_fetch_assoc($result);
        $id_joueur = $row['id_joueur'];
    }

    $_SESSION['id_joueur'] = $id_joueur;
    $_SESSION['pseudo'] = $pseudo;

    Flight::render('carte');
});


Flight::start();

