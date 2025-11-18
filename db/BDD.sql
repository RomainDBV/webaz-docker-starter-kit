-- Activer PostGIS
CREATE EXTENSION IF NOT EXISTS postgis;

-- Suppression des anciennes tables si elles existent
DROP TABLE IF EXISTS inventaire;
DROP TABLE IF EXISTS scores;
DROP TABLE IF EXISTS objets;
DROP TABLE IF EXISTS joueurs;

-- ==================================================
-- TABLE JOUEURS
-- ==================================================
CREATE TABLE joueurs (
    id_joueur SERIAL PRIMARY KEY,
    pseudo VARCHAR(50) UNIQUE NOT NULL,
    date_creation TIMESTAMP DEFAULT NOW()
);

-- ==================================================
-- TABLE SCORES
-- ==================================================
CREATE TABLE scores (
    id_score SERIAL PRIMARY KEY,
    id_joueur INT REFERENCES joueurs(id_joueur) ON DELETE CASCADE,
    temps_total INT,         -- en secondes
    objets_trouves INT,
    score_total INT,
    date_partie TIMESTAMP DEFAULT NOW()
);

-- ==================================================
-- TABLE OBJETS
-- ==================================================
CREATE TABLE objets (
    id_objet SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    type_objet VARCHAR(30) CHECK (type_objet IN ('recuperable','code','bloque_objet','bloque_code')),
    code_deverrouille VARCHAR(20),
    id_objet_precedent INT REFERENCES objets(id_objet),
    indice TEXT,
    trouve BOOLEAN DEFAULT FALSE,
    visible BOOLEAN DEFAULT TRUE,
    niveau_zoom_min INT DEFAULT 10,
    icone VARCHAR(200),
    geom geometry(Point, 4326) -- WGS84
);

-- ==================================================
-- TABLE INVENTAIRE
-- ==================================================
CREATE TABLE inventaire (
    id_inventaire SERIAL PRIMARY KEY,
    id_joueur INT REFERENCES joueurs(id_joueur) ON DELETE CASCADE,
    id_objet INT REFERENCES objets(id_objet) ON DELETE CASCADE,
    date_obtention TIMESTAMP DEFAULT NOW(),
    UNIQUE(id_joueur, id_objet)
);



-- ==================================================
-- Insertion des 5 objets pour l'escape game
-- ==================================================

-- OBJET 1 : Singe vietnamien - Senlis
INSERT INTO objets (nom, description, type_objet, geom, icone, indice)
VALUES (
    'Singe vietnamien',
    'Objet 1 à Senlis.',
    'recuperable',
    ST_SetSRID(ST_MakePoint(2.5845, 49.2066), 4326),
    'img/singe_vietnamien.png',
    'Indice vers Objet 2 à Agen'
);

-- OBJET 2 : Singe normand - Agen
INSERT INTO objets (nom, description, type_objet, geom, icone, indice)
VALUES (
    'Singe normand',
    'Objet 2 à Agen.',
    'code',
    ST_SetSRID(ST_MakePoint(0.6174, 44.2028), 4326),
    'img/singe_normand.png',
    'Indice vers Objet 3 à Rumilly'
);

-- OBJET 3 : Singe portugais - Rumilly
INSERT INTO objets (nom, description, type_objet, code_deverrouille, geom, icone, indice)
VALUES (
    'Singe portugais',
    'Objet 3 à Rumilly.',
    'bloque_code',
    '1824',  -- ce code peut être généré dynamiquement côté JS
    ST_SetSRID(ST_MakePoint(5.9430, 45.8672), 4326),
    'img/singe_portugais.png',
    'Indice vers Objet 4 à Fontenay-le-Fleury'
);

-- OBJET 4 : Singe ukrainien - Fontenay-le-Fleury
INSERT INTO objets (nom, description, type_objet, id_objet_precedent, geom, icone, indice)
VALUES (
    'Singe ukrainien',
    'Objet 4 à Fontenay-le-Fleury.',
    'bloque_objet',
    3,
    ST_SetSRID(ST_MakePoint(2.0460, 48.8112), 4326),
    'img/singe_ukrainien.png',
    'Indice vers Objet 5 au Zoo d’Amiens'
);

-- OBJET 5 : Objet final - Zoo d’Amiens
INSERT INTO objets (nom, description, type_objet, id_objet_precedent, geom, icone, indice)
VALUES (
    'Objet final',
    'Objet 5 au Zoo d’Amiens. Vérifie possession de tous les objets précédents.',
    'recuperable',
    4,
    ST_SetSRID(ST_MakePoint(2.2770, 49.9004), 4326),
    'img/objet_final.png',
    'Victoire ! Vous avez tous les objets.'
);

-- Vérification rapide
SELECT id_objet, nom, type_objet, trouve, id_objet_precedent, ST_AsText(geom) AS coordonnees FROM objets;
