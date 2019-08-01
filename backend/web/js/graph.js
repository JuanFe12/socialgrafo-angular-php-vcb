/*
var graph = {
    nodes: [
        {"name": "Lillian",     "sex": "F",},
        {"name": "Gordon",      "sex": "M",},
        {"name": "Sylvester",   "sex": "M",},
        {"name": "Mary",        "sex": "F",},
        {"name": "Helen",       "sex": "F",},
        {"name": "Jamie",       "sex": "M",},
        {"name": "Jessie",      "sex": "F",},
        {"name": "Ashton",      "sex": "M",},
        {"name": "Duncan",      "sex": "M",},
        {"name": "Evette",      "sex": "F",},
        {"name": "Mauer",       "sex": "M",},
        {"name": "Fray",        "sex": "F",},
        {"name": "Duke",        "sex": "M",},
        {"name": "Baron",       "sex": "M",},
        {"name": "Infante",     "sex": "M",},
        {"name": "Percy",       "sex": "M",},
        {"name": "Cynthia",     "sex": "F",}
    ],

    links: [
        {"source": "Sylvester", "target": "Gordon",     "type":"A"},
        {"source": "Sylvester", "target": "Lillian",    "type":"A"},
        {"source": "Sylvester", "target": "Mary",       "type":"A"},
        {"source": "Sylvester", "target": "Jamie",      "type":"A"},
        {"source": "Sylvester", "target": "Jessie",     "type":"A"},
        {"source": "Sylvester", "target": "Helen",      "type":"A"},
        {"source": "Helen",     "target": "Gordon",     "type":"A"},
        {"source": "Mary",      "target": "Lillian",    "type":"A"},
        {"source": "Ashton",    "target": "Mary",       "type":"A"},
        {"source": "Duncan",    "target": "Jamie",      "type":"A"},
        {"source": "Gordon",    "target": "Jessie",     "type":"A"},
        {"source": "Sylvester", "target": "Fray",       "type":"E"},
        {"source": "Fray",      "target": "Mauer",      "type":"A"},
        {"source": "Fray",      "target": "Cynthia",    "type":"A"},
        {"source": "Fray",      "target": "Percy",      "type":"A"},
        {"source": "Percy",     "target": "Cynthia",    "type":"A"},
        {"source": "Infante",   "target": "Duke",       "type":"A"},
        {"source": "Duke",      "target": "Gordon",     "type":"A"},
        {"source": "Duke",      "target": "Sylvester",  "type":"A"},
        {"source": "Baron",     "target": "Duke",       "type":"A"},
        {"source": "Baron",     "target": "Sylvester",  "type":"E"},
        {"source": "Evette",    "target": "Sylvester",  "type":"E"},
        {"source": "Cynthia",   "target": "Sylvester",  "type":"E"},
        {"source": "Cynthia",   "target": "Jamie",      "type":"E"},
        {"source": "Mauer",     "target": "Jessie",     "type":"E"}
    ]
}
/** */

var canvas = d3.select("#network"),
    ctx = canvas.node().getContext('2d'),
    width = canvas.attr('width'),
    height = canvas.attr('height'),
    r = 5,
    x = d3.scaleOrdinal().range([20, width-20])
    color = d3.scaleOrdinal(d3.schemeCategory20);
    simulation = d3.forceSimulation()
                    .force('x', d3.forceX(width/2)) //Fuerza de ubicación en X
                    // .force('x', d3.forceX(function(d){
                    //     return x(d.sex);
                    // })) //Fuerza de ubicación en X
                    .force('y', d3.forceY(height/2)) //Fuerza de ubicación en Y
                    .force('collide', d3.forceCollide(r*5)) //área de cada circulo fuera de él que no toca otro
                    .force('charge', d3.forceManyBody().strength(-100)) //fuerza de separación, a más alto, más cerca
                    .force('link', d3.forceLink().id(function(d){ return d.name }));

graph.nodes.forEach(function(d){
    d.x = Math.random() * width;
    d.y = Math.random() * height;
});

//update();

// d3.json(graph, function(err, graph){
//     if(err) throw err;
function generateGraph(){

    canvas = d3.select("#network"),
    ctx = canvas.node().getContext('2d'),
    width = canvas.attr('width'),
    height = canvas.attr('height'),
    r = 20,
    //x = d3.scaleOrdinal().range([50, width-50])
    color = d3.scaleOrdinal(d3.schemeCategory20);
    simulation = d3.forceSimulation()
                    .force('x', d3.forceX(width/2)) //Fuerza de ubicación en X
                    // .force('x', d3.forceX(function(d){
                    //     return x(d.sex);
                    // })) //Fuerza de ubicación en X
                    .force('y', d3.forceY(height/2)) //Fuerza de ubicación en Y
                    .force('collide', d3.forceCollide(r+10)) //área de cada circulo fuera de él que no toca otro
                    .force('charge', d3.forceManyBody().strength(-300)) //fuerza de separación, a más alto, más cerca
                    .force('link', d3.forceLink().id(function(d){ return d.name }));

    graph.nodes.forEach(function(d){
        d.x = Math.random() * width;
        d.y = Math.random() * height;
    });
    graph.links.strength = -200;

    simulation
        .nodes(graph.nodes)
        .on('tick', update)
        .force('link')
        .links(graph.links);

    canvas.call(
        d3.drag()
            .container(canvas.node())
            .subject(dragsubject)
            .on('start', dragstarted)
            .on('drag', dragged)
            .on('end', dragended)
    );
    update();
}

function update(){

    ctx.clearRect(0,0, width, height);

    ctx.beginPath();
    ctx.globalAlpha = 0.5;
    ctx.strokeStyle = "lightgray";
    ctx.lineWidth = 2;
    graph.links.forEach(drawLinks);
    
    ctx.stroke();
    
    ctx.globalAlpha = 1;
    graph.nodes.forEach(drawNode);
}

function dragsubject(){
    return simulation.find(d3.event.x, d3.event.y);
}

//});


function drawNode(d){
    ctx.beginPath();

    ctx.moveTo(d.x, d.y);
    switch (d.type) {
        case 'DB':      ctx.arc(d.x, d.y, r*2,      0, 2*Math.PI); break;
        case 'table':   ctx.arc(d.x, d.y, r*1.5,      0, 2*Math.PI); break;
        case 'data':    ctx.arc(d.x, d.y, r*.3,     0, 2*Math.PI); break;
        default:        ctx.arc(d.x, d.y, r*1,      0, 2*Math.PI); break;
    }

    ctx.strokeStyle = "#FFF";
    ctx.lineWidth = 6;
    ctx.stroke();
    
    switch (d.type) {
        case 'DB':      ctx.fillStyle = '#4c3896'; break;
        case 'table':   ctx.fillStyle = '#5bc0de'; break;
        case 'data':    ctx.fillStyle = '#777777'; break;
        default:        ctx.fillStyle = '#777777'; break;
    }
    ctx.fill();

    ctx.font = "15px bold";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    if(d.type == 'table' || d.type == 'DB') ctx.fillText(d.name, d.x, d.y+r*3);
    
    //ctx.fillStyle = color(d.sex == 'M' ? 'blue' : 'red');
    
}
function drawLinks(l){
    ctx.moveTo(l.source.x, l.source.y);
    ctx.lineTo(l.target.x, l.target.y);
}

function dragstarted(){
    if(!d3.event.active) simulation.alphaTarget(0.3).restart();
    d3.event.subject.fx = d3.event.subject.x;
    d3.event.subject.fy = d3.event.subject.y;
    console.log(d3.event.subject)
}

function dragged(){
    d3.event.subject.fx = d3.event.x;
    d3.event.subject.fy = d3.event.y;
}

function dragended(){
    d3.event.subject.fx = null;
    d3.event.subject.fy = null;
}