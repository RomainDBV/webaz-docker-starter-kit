<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géo'scape Game </title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/carte.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

</head>
<body>

    <div id="app">

        <label>
            <input type="checkbox" v-model="showHeatmap" @change="cocherHeatmap">
            Afficher la heatmap
        </label>

    </div>

    <div id="map"></div>

    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="assets/carte.js"></script>
</body>
</html>
