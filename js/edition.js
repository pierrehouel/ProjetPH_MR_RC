document.addEventListener('DOMContentLoaded', function () {
    const image = document.getElementById('dynamic-image');

    // Vérifier si l'image existe avant d'ajouter un événement
    if (image) {
        image.addEventListener('click', function (event) {
            //On récupérer les coordonnée x et y sur l'image
            const img  = image.getBoundingClientRect();
            const x = Math.round(event.clientX - img.left);
            const y = Math.round(event.clientY - img.top);

            //Affiche les coordonnée dans une alert
            alert("Coordonnée sur l'image:\n X: " + x + "px\n Y: " + y +"px");
        });
    }
});