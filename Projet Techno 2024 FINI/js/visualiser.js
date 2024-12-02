// Récupérer le canevas et son contexte
const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");
//pour pouvoir afficher l'image
const bankName = document.getElementById("imgdir").innerText;
const imgName = document.getElementById("imgname").innerText;
//Pour récupérer les infos des étiquettes liés a l'images
const nbEtiq = document.getElementById("nbEtiq").innerText;
const infos = document.getElementById("infos").innerText;

let infosSplit = [];
let infosSplit2D = [];

// Stocker les coordonnées des points du polygone
let points = [];

// Charger l'image
const img = new Image();
img.src = "images/"+bankName+"/"+imgName; // Remplacez par le chemin de votre image

// Une fois l'image chargée, la dessiner dans le canevas
img.onload = () => { 

    resizeImage();

    // Écouter le redimensionnement de la fenêtre
    window.addEventListener('resize', resizeImage);
 
    //nbEtiqu = nombre d'étiquette par image
    console.log(nbEtiq);

    //on divise la chaine de caractère avec toute les des etiquettes
    infosSplit = infos.split(":/?");
    infosSplit.splice(nbEtiq);

    //on sépare les infos de chaque etiquettes
    for(i = 0; i < nbEtiq; i++){
        infosSplit2D[i] = []
        infosSplit2D[i][0] = infosSplit[i].split("/;!")
    }


    //on récupére la chaines de caractère des points et on les sépares pour avoir les coordonnées
    for(i = 0; i < nbEtiq; i++){
        data = infosSplit2D[i][0][2];
        pointsTab = data.split(":");

        points = pointsTab.map(coord => {
            [x, y] = coord.split(",").map(Number); // Convertir les coordonnées en nombres
            return { x, y }; // Créer un objet pour chaque point
        });

        console.log(points);

        
        // Dessiner le polygone et les points
        drawPolygon(points);
        drawPoint(points);

    }

};

// Fonction pour dessiner les points
function drawPoint(points) {

    // Dessiner le point rouge aux coordonnées (x, y)
    ctx.fillStyle = 'red';
    points.forEach(point => {
        ctx.beginPath();
        ctx.arc(point.x, point.y, 5, 0, Math.PI * 2); // Dessiner un cercle pour chaque point
        ctx.fill();
    });
}

// Fonction pour dessiner le polygone
function drawPolygon(points) {
    if (points.length < 2) return;

    // Début du chemin
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y); // Aller au premier point

    // Tracer les lignes entre les points
    for (let i = 1; i < points.length; i++) {
        ctx.lineTo(points[i].x, points[i].y);
    }

    // Relier le dernier point au premier
    ctx.closePath();

    // Style et remplissage
    ctx.strokeStyle = "blue"; // Couleur du contour
    ctx.lineWidth = 2;       // Épaisseur du contour
    ctx.fillStyle = "rgba(0, 150, 255, 0.3)"; // Couleur de remplissage (transparente)

    ctx.fill();  // Remplir le polygone
    ctx.stroke(); // Dessiner le contour
}

function resizeImage() {
        //setup de la taille de l'image et du canvas
        const maxWidth = document.body.clientWidth;

        const imgWidthOG = img.width;
        const imgHeightOG = img.height;
        const scaleFactor = maxWidth / imgWidthOG;

        // Calculer la hauteur proportionnelle
        let newWidth = imgWidthOG;
        let newHeight = imgHeightOG;

        if (imgWidthOG > maxWidth) {
            newWidth = maxWidth;
            newHeight = imgHeightOG * scaleFactor;
        }else if(imgWidthOG < maxWidth){
            // Calculer la hauteur proportionnelle si l'image est plus petite que que la largeur du body de large
            newWidth = maxWidth;
            newHeight = imgHeightOG * scaleFactor;
        }

        // Ajuster les dimensions du canevas
        canvas.width = newWidth;
        canvas.height = newHeight;

        // Dessiner l'image
        ctx.drawImage(img, 0, 0,newWidth, newHeight);

}