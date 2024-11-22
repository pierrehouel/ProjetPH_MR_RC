function drawPolygon(points, motion=null){
    for(i of points)
        drawDot(i[0], i[1]);
}

//pour dessiner un polygone avec une liste de point 

function drawContour (points, motion=null){
    contexte.lineWidth=2;
    context.fillStyle="rgb(0 255 0 / 50%)";
    context.strokeStyle="rgb(0 0 0 / 50%)";
    context.beginPath();
    context.moveTo(points[0][0], points[0][1]);
    if (motion !=null)
    context.lineTo(motion[0], motion[1]);
    context.closePath();
    context.fill();
    context.stroke();
}

// dessiner un point 

function drawDot (x, y){
    context.fillStyle = "rgb(255 0 0 / 50%)";
    context.strokeStyle= "rab(0 0 0 / 50%)";
}

