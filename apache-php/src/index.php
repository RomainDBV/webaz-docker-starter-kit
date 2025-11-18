<?php

declare(strict_types=1);

require_once 'flight/Flight.php';
require_once 'config.php';

Flight::route('/', function() {
    Flight::render('accueil');
});

// API : tous les objets visibles
Flight::route('GET /api/objets', function() use ($link) {
    $sql = "SELECT id_objet, nom, type_objet, code_deverrouille, id_objet_precedent, indice,
                   trouve, visible, niveau_zoom_min, icone,
                   ST_X(geom) AS lon, ST_Y(geom) AS lat
            FROM objets
            WHERE visible = true";

    $result = pg_query($link, $sql);
    $rows = pg_fetch_all($result);

    Flight::json($rows);
});

// API : objet précis
Flight::route('GET /api/objets/@id', function($id) use ($link) {
    $sql = "SELECT id_objet, nom, type_objet, code_deverrouille, id_objet_precedent, indice,
                   trouve, visible, niveau_zoom_min, icone,
                   ST_X(geom) AS lon, ST_Y(geom) AS lat
            FROM objets
            WHERE id_objet = $id";

    $result = pg_query($link, $sql);
    $row = pg_fetch_assoc($result);

    Flight::json($row);
});


Flight::start();

 
