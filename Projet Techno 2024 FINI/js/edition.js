// Récupérer le canevas et son contexte
const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");
//Récupère le nom de l'image et le dossier ou est sauvegarder l'image
const bankName = document.getElementById("imgdir").innerText;
const imgName = document.getElementById("imgname").innerText;

//Pour les informations de l'etiquette
const coordPts = document.getElementById("coordPts");
const nomEtiq = document.getElementById("nomEtiq");
const descEtiq = document.getElementById("descEtiq");
const imgId = document.getElementById("imgId");
const cataId = document.getElementById("cataId");

//bouton qui ouvre une popup
const buttonEtiquette = document.getElementById("final");

let coordAffiche;
let donnees;

// Stocker les coordonnées des points du polygone
let points = [];

// Charger l'image
const img = new Image();
img.src = "images/"+bankName+"/"+imgName; // Remplacez par le chemin de votre image

// Une fois l'image chargée, la dessiner dans le canevas
img.onload = () => {    

    console.log(bankName);
    console.log(imgName);

    resizeImage()

    // Écouter le redimensionnement de la fenêtre
    window.addEventListener('resize', resizeImage);


    // Écouter les clics sur le canevas
    canvas.addEventListener("click", (event) => {

        // Récupérer les coordonnées du clic
        const rect = canvas.getBoundingClientRect();
        const x = Math.round(event.clientX - rect.left);
        const y = Math.round(event.clientY - rect.top);

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
            // Remplir le polygone
            fillPolygon();
            // Réinitialiser les points
            points.splice(taille-1);
            console.log(points);
            //points = [];
        }

    });
};

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

    // Ajouter un événement au bouton pour ouvrir une nouvelle fenêtre
    buttonEtiquette.addEventListener('click', () => {
        // Créer une fenêtre popup
        const popup = window.open('', '', 'width=400,height=700');

        // Générer le contenu HTML pour la fenêtre
        popup.document.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Formulaire</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 8px;
                        text-align: left;
                    }
                    form {
                        display: flex;
                        flex-direction: column;
                    }
                    input, textarea {
                        margin-bottom: 10px;
                        padding: 8px;
                        font-size: 14px;
                    }
                    button {
                        padding: 10px;
                        font-size: 16px;
                        cursor: pointer;
                    }
                </style>
            </head>
            <body>
                <h2>Coordonnées des Points</h2>
                <table>
                    <thead>
                        <tr>
                            <th>X</th>
                            <th>Y</th>
                        </tr>
                    </thead>
                    <tbody id="pointsTable"></tbody>
                </table>

                <h2>Formulaire</h2>
                <form id="form">
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" required>

                    <label for="description">Description :</label>
                    <textarea id="description" name="description" required></textarea>

                    <button type="submit">Soumettre</button>
                </form>
                
                    <script>
                        // Récupérer les points transmis par le parent
                        const points = ${JSON.stringify(points)};
                        const pointsTable = document.getElementById('pointsTable');

                        // Afficher les coordonnées dans le tableau
                        points.forEach(point => {
                            const row = document.createElement('tr');
                            row.innerHTML = \`<td>\${point.x}</td><td>\${point.y}</td>\`;
                            pointsTable.appendChild(row);
                        });

                        // Gérer la soumission du formulaire
                        document.getElementById('form').addEventListener('submit', (event) => {
                            event.preventDefault();
                            const name = document.getElementById('name').value;
                            const description = document.getElementById('description').value;

                            // Envoyer les données au parent
                            window.opener.postMessage({ name, description }, '*');

                            // Fermer la fenêtre
                            window.close();
                        });
                    </script>
            </body>
            </html>
        `);
    });

    // Réception des données du formulaire
    window.addEventListener('message', (event) => {
        const { name, description } = event.data;

        coordAffiche = points.map(points => `${points.x},${points.y}`).join(':');
       
        coordPts.textContent = coordAffiche;
        nomEtiq.textContent = name;
        descEtiq.textContent = description;

        console.log(coordAffiche);
        console.log(name);
        console.log(description);
        console.log(imgId.innerText);
        console.log(cataId.innerText);

    });
