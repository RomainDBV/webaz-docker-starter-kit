// Création de la carte
var map = L.map('map').setView([48.85, 2.35], 12);

// Fond OSM
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var HeatmapLayer = L.tileLayer.wms('http://localhost:8080/geoserver/escapegame/wms', {
    layers: 'escapegame:objets',
    styles: 'heatmap',      
    format: 'image/png',    
    version: '1.1.0',
    attribution: '© GeoServer'
});


// Vue app
Vue.createApp({
    data() {
        return {
            showHeatmap: false
        }
    },
    methods: {
        cocherHeatmap() {
            if(this.showHeatmap) {
                HeatmapLayer.addTo(map);
            } else {
                map.removeLayer(HeatmapLayer);
            }
        }
    }
}).mount('#app');

