Vue.createApp({
    data() {
        return {
            showHeatmap: false,
            map: null,
            heatmapLayer: null,

            objets: [],
            inventaire: [],
            modalVisible: false,
            modalTitre: '',
            modalMessage: ''
        }
    },
    methods: {
        initMap() {
            
            this.map = L.map('map').setView([48.85, 2.35], 12);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            this.heatmapLayer = L.tileLayer.wms('http://localhost:8080/geoserver/escapegame/wms', {
                layers: 'escapegame:objets',
                styles: 'heatmap',
                format: 'image/png',
                transparent: true,
                version: '1.1.0'
            });
        },
        
        genererCode() {
            // Génère un code à 4 chiffres aléatoire
            return Math.floor(1000 + Math.random() * 9000).toString();
        },

        cocherHeatmap() {
            if (this.showHeatmap) {
                this.heatmapLayer.addTo(this.map);
            } else {
                this.map.removeLayer(this.heatmapLayer);
            }
        },

        afficherModal(titre, message) {
            this.modalTitre = titre;
            this.modalMessage = message;
            this.modalVisible = true;
        },

        fermerModal() {
            this.modalVisible = false;
        },

        chargerObjets() {
            fetch('/api/objets')
            .then(r => r.json())
            .then(json => {
                this.objets = json;
                this.placerObjetsSurCarte();

                const objet1 = this.objets.find(o => o.id_objet == 1);
                if (objet1) {
                    this.afficherModal(objet1.indice_precedent, objet1.indice_propre);
                }
        
            });
        },


        placerObjetsSurCarte() {
            // On stocke tous les markers dans un tableau pour pouvoir les ajouter/enlever facilement
            this.markers = [];

            this.objets.forEach(obj => {
                let icone = L.icon({
                    iconUrl: obj.icone,
                    iconSize: [50, 50]
                });

                let marker = L.marker([obj.lat, obj.lon], { icon: icone });
                marker.objet = obj; // Stocke l'objet pour référence

                marker.on('click', () => this.gererClicObjet(marker));

                this.markers.push(marker);
            });

            // Fonction pour mettre à jour l'affichage des markers selon le zoom
            const updateMarkers = () => {
                const zoomActuel = this.map.getZoom();

                this.markers.forEach(marker => {
                    const obj = marker.objet;
                    if (marker.recupere) return;
                    if (zoomActuel >= obj.niveau_zoom_min) {
                        if (!this.map.hasLayer(marker)) marker.addTo(this.map);
                    } else {
                        if (this.map.hasLayer(marker)) this.map.removeLayer(marker);
                    }
                });
            };

            // Mise à jour initiale
            updateMarkers();

            // Écouteur sur le zoom pour mise à jour dynamique
            this.map.on('zoomend', updateMarkers);
        },

        gererClicObjet(marker) {
            const obj = marker.objet;

            if (obj.type_objet === 'recuperable') {
                // Déplace dans l'inventaire si pas déjà présent
                if (!this.inventaire.includes(obj)) {
                    this.inventaire.push(obj);
                }
                // Masque l'objet sur la carte
                this.map.removeLayer(marker);
                // Marque le marker comme récupéré
                marker.recupere = true;
                // Affiche l'indice suivant
                this.afficherModal(obj.description, obj.indice_suivant);   
            }

            else if (obj.type_objet === 'code') {
                // Génère le code si ce n'est pas déjà fait
                if (!obj.code_genere) {
                    obj.code_genere = this.genererCode();
                }

                if (!this.inventaire.includes(obj)) {
                    this.inventaire.push(obj);
                }

                this.map.removeLayer(marker);
                marker.recupere = true;

                this.afficherModal(obj.code_genere, obj.indice_suivant);
            }

            else if (obj.type_objet === 'bloque_objet') {
                this.afficherModal(obj.indice_precedent);
            }
            
            else if (obj.type_objet === 'bloque_code') {
                this.afficherModal(obj.indice_precedent);
            }
        },

    },

    mounted() {
        this.initMap();
        this.chargerObjets();
    },

}).mount('#app');
