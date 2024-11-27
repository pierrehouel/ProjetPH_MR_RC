// Récupérer le canevas et son contexte
const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");

// Stocker les coordonnées des points du polygone
let points = [];

// Charger l'image
const img = new Image();
img.src = "../images/mi/vac1.jpg"; // Remplacez par le chemin de votre image

// Une fois l'image chargée, la dessiner dans le canevas
img.onload = () => {
    // Dessiner l'image
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    // Écouter les clics sur le canevas
canvas.addEventListener("click", (event) => {
    // Récupérer les coordonnées du clic
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;

    // Ajouter les coordonnées au tableau
    points.push({ x, y });


    // Dessiner par-dessus l'image (exemple)

    // Dessiner le point
    drawPoint(x, y);

    // Si plus de 1 point, dessiner une ligne
    if (points.length > 1) {
        drawLine(points[points.length - 2], points[points.length - 1]);
    }
});



// Écouter un double-clic pour fermer le polygone
canvas.addEventListener("dblclick", () => {
    let taille = points.length
    if (points.length > 2) {
        // Dessiner la ligne de fermeture
        drawLine(points[points.length - 1], points[0]);
        // Remplir le polygone (facultatif)
        fillPolygon();
        // Réinitialiser les points
        console.log(points.length -1);
        console.log(points[0]);
        console.log(points[taille-1]);
        points = [];
    }

});

// Fonction pour dessiner un point
function drawPoint(x, y) {
    ctx.fillStyle = "red";
    ctx.beginPath();
    ctx.arc(x, y, 5, 0, Math.PI * 2); // Petit cercle pour représenter le point
    ctx.fill();
}

// Fonction pour dessiner une ligne entre deux points
function drawLine(point1, point2) {
    ctx.strokeStyle = "blue";
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(point1.x, point1.y);
    ctx.lineTo(point2.x, point2.y);
    ctx.stroke();
}

// Fonction pour remplir le polygone (facultatif)
function fillPolygon() {
    ctx.fillStyle = "rgba(0, 0, 255, 0.3)"; // Couleur bleue transparente
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);
    for (let i = 1; i < points.length; i++) {
        ctx.lineTo(points[i].x, points[i].y);
    }
    ctx.closePath();
    ctx.fill();
}



};
