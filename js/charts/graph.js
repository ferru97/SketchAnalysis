/**
 * Created by Vito on 04/05/2020.
 */
function plot_graph(tag_id,data){
    var screenWidth = $( document  ).width();
    // set the dimensions and margins of the graph
    var margin = {top: 10, right: 30, bottom: 30, left: 70},
        width = (screenWidth-330) - margin.left - margin.right,
        height = 600 - margin.top - margin.bottom;

// append the svg object to the body of the page
    var svg = d3.select("#"+tag_id)
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");


    // Initialize the links
    var link = svg
        .selectAll("line")
        .data(data.links)
        .enter()
        .append("line")
        .style("stroke", "#aaa")

    // Initialize the nodes
    var node = svg
        .selectAll("circle")
        .data(data.nodes)
        .enter()
        .append("circle")
        .on("mouseover", handleMouseOver)
        .on("mouseout", handleMouseOut)
        .attr("r", 7.5)
        .style("fill", "#69b3a2")

    // Let's list the force we wanna apply on the network
    var simulation = d3.forceSimulation(data.nodes)                 // Force algorithm is applied to data.nodes
       // .force("link", d3.forceLink().id(function(d, i) { return i; }).distance(100).strength(1))
        .force("link", d3.forceLink()                               // This force provides links between nodes
            .id(function(d) { return d.id; })                     // This provide  the id of a node
            .links(data.links)
            .distance(function(d) { return d.strength; })
            .strength(1)// and this the list of links
        )
        .force("charge", d3.forceManyBody().strength(-400))         // This adds repulsion between nodes. Play with the -400 for the repulsion strength
        .force("center", d3.forceCenter(width / 2, height / 2))     // This force attracts nodes to the center of the svg area
        .on("end", ticked);

    // This function is run at each iteration of the force algorithm, updating the nodes position.
    function ticked() {
        link
            .attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node
            .attr("cx", function (d) { return d.x+6; })
            .attr("cy", function(d) { return d.y-6; });
    }

    function handleMouseOver(d, i) {  // Add interactivity
        d3.select(this)
            .attr("r", 9);

        var xPos = d.x
        var yPos = d.y

        svg.append("text")
            .attr("id", function() { return "t" +d.name+ i })
            .attr("x", function() { return (xPos) - 30; })
            .attr("y", function() { return (yPos) - 15; })
            .text(d.name+"("+d.occur+" times)")
    }


    function handleMouseOut(d, i) {
        d3.select(this)
            .attr("r", 7.5);
        d3.select("#t"+d.name+ i).remove();

    }

}
