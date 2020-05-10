/**
 * Created by Vito on 20/03/2020.
 */
function multi_scatter_plot(svg_id,data,xlable,ylable,max_x_val=null,min_x_val=null,scaleX){
    var screenWidth = $( document  ).width();


    if(max_x_val!=null){
        max_x_val = parseInt(max_x_val)
        data = data.filter(function (el) {
            return el[0]<=max_x_val;
        })
    }
    if(min_x_val!=null){
        max_x_val = parseInt(min_x_val)
        data = data.filter(function (el) {
            return el[0]>=min_x_val;
        })
    }


    // set the dimensions and margins of the graph
    var margin = {top: 10, right: 30, bottom: 30, left: 70},
        width = (screenWidth-330) - margin.left - margin.right,
        height = 600 - margin.top - margin.bottom;


    // append the svg object to the body of the page
    var svg = d3.select("#"+svg_id)
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    //Read the data
    // Add X axis
    var maxX = 0;
    var maxY = 0;
    var minX = null;
    for(var k=0; k<data.length; k++){
        var temp = data[k];
        if(parseInt(temp[0])>maxX)
            maxX=temp[0];
        if(parseInt(temp[1])>maxY)
            maxY=temp[1];

        if(minX==null || parseInt(temp[0])<minX)
            minX=temp[0];

    }

    var x;
    if(scaleX=="linear"){
        x = d3.scaleLinear()
            .domain([minX, maxX])
            .range([ 0, width ]);
    }else {
        x = d3.scaleLog()
            .domain([minX, maxX])
            .range([0, width]);
    }

    svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x));

    // Add Y axis
    var y = d3.scaleLinear()
        .domain([0, maxY])
        .range([ height, 0]);
    svg.append("g")
        .call(d3.axisLeft(y));

    const g = svg.append('g')
        .attr('transform', `translate(${margin.left},${margin.top})`);
    const xAxisG = g.append('g')
        .attr('transform', `translate(0, ${height})`);
    const yAxisG = g.append('g');

    yAxisG.append('text')
        .attr('class', 'axis-label')
        .attr('x', -height / 2)
        .attr('y', -120)
        .attr('transform', `rotate(-90)`)
        .style('text-anchor', 'middle')
        .style('font-weight',' bold')
        .text(ylable);

    xAxisG.append('text')
        .attr('class', 'axis-label')
        .attr('x', (width / 2)-50)
        .attr('y', 20)
        .style('font-weight',' bold')
        .text(xlable);

    // Add dots
    svg.append('g')
        .selectAll("dot")
        .data(data)
        .enter()
        .append("circle")
        .on("mouseover", handleMouseOver)
        .on("mouseout", handleMouseOut)
        .style("cursor","pointer")
        .attr("cx", function (d) { return x(d[0]); } )
        .attr("cy", function (d) { return y(d[1]); } )
        .attr("r", 2.3)
        .attr("fill", function (d) { return d[2]; })
        .attr("onclick", function (d) {return `shomodelInfo('${d[3]}')`})
        //.attr("onclick", function(d) { return `confirm("${xlable}=${d[4]}, ${ylable}=${d[5]} Press 'OK' to show the model")==true ? window.open ('${d[3]}','_blank ',false) : null; `; })


    function handleMouseOver(d, i) {  // Add interactivity
        d3.select(this)
            .attr("r", 4);

        var xPos = d[0]
        var yPos = d[1]

        svg.append("text")
            .attr("id", function() { return "t" + xPos + "-" + yPos + "-" + i })
            .attr("x", function() { return x(xPos) - 30; })
            .attr("y", function() { return y(yPos) - 15; })
            .text(xPos+","+yPos)
    }


    function handleMouseOut(d, i) {
        d3.select(this)
            .attr("r", 2.3);

        var xPos = d[0]
        var yPos = d[1]
        d3.select("#t" + xPos + "-" + yPos + "-" + i).remove();

    }

}