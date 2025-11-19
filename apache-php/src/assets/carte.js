Vue.createApp({
    data() {
        return {
            showHeatmap: false,
            map: null,
            heatmapLayer: null,
        }
    },
    methods: {
        cocherHeatmap() {
            if (this.showHeatmap) {
                this.heatmapLayer.addTo(this.map);
            } else {
                this.map.removeLayer(this.heatmapLayer);
            }
        }
    },
    mounted() {
        // Initialisation de la carte
        this.map = L.map('map').setView([48.85, 2.35], 12);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        // Layer Heatmap
        this.heatmapLayer = L.tileLayer.wms('http://localhost:8080/geoserver/escapegame/wms', {
            layers: 'escapegame:objets',
            styles: 'heatmap',
            format: 'image/png',
            transparent: true,
            version: '1.1.0',
            attribution: '© GeoServer'
        });
    }
}).mount('#app');



