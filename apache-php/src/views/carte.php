<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géo'scape Game </title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/carte.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

</head>
<body>
    <div id="app">

        <div class="game-container">

            <!-- Zone inventaire -->
            <div id="inventaire">
                <h2>Inventaire</h2>
                <ul id="liste-inventaire">
                    <li v-for="obj in inventaire" :key="obj.id">
                    <img src="img/singe.jpg" :alt="obj.nom" class="icone-inventaire">   
                    </li>
                </ul>
            </div>

            <!-- Zone carte -->
            <div id="map-container">
                <div class="map-header">
                    <label>
                        <input type="checkbox" v-model="showHeatmap" @change="cocherHeatmap">
                        Tricher (afficher la Heatmap)
                    </label>
                </div>

                <div id="map"></div>
            </div>

        </div>

         <!-- Modal -->
        <div v-if="modalVisible" class="modal-overlay" @click="fermerModal">
            <div class="modal-content" @click.stop>
                <h3>{{ modalTitre }}</h3> 
                <p>{{ modalMessage }}</p>
                <button @click="fermerModal">OK</button>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="assets/carte.js"></script>
</body>
</html>
