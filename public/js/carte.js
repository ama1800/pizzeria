/** leaflet map */
// Variables globales
// On initialise la carte et on la centre sur notre adresse
var carte = L.map('map').setView([49.2591321, 2.4766832], 18); // coordonnés js.
// ajouter une icon sur l'adresse
var logo = L.icon({
    iconUrl: 'img/pizza.gif',

    iconSize: [60, 60], // size of the icon

});
window.onload = () => {
    // On charge les "tuiles"
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        minZoom: 1,
        maxZoom: 20,
        name: 'tiles' // permettra de ne pas supprimer cette couche
    }).addTo(carte);

    L.marker(
        [49.2591321, 2.4766832], { icon: logo }
    ).addTo(carte);
    // L.popup()
    //     .setLatLng([49.2591321, 2.4766832])
    //     .setContent("<img style='width :50px; height : 50px' src='img/leaflet.png' alt='logo'>")
    //     .openOn(carte);

    document.getElementById("map").style.width = "600px";
    document.getElementById("map").style.height = "400px";
}
