/**
 * Created by Vito on 20/03/2020.
 */
var tagBarchart = false;
function horizontal_bar_chart(svg_id,data, minX=null, maxX=null){
    var screenWidth = $( document  ).width();

    // set the dimensions and margins of the graph
    var margin = {top: 20, right: 20, bottom: 30, left: 130},
        width = (screenWidth-300) - margin.left - margin.right,
        height = 600 - margin.top - margin.bottom;


    // set the ranges
    var y = d3.scaleBand()
        .range([height, 0])
        .padding(0.1);

    var x = d3.scaleLinear()
        .range([0, width]);

    // append the svg object to the body of the page
    // append a 'group' element to 'svg'
    // moves the 'group' element to the top left margin
    var svg = d3.select("#"+svg_id)
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    data.forEach(function(d) {
        d.x = +d.x;
    });

    // Scale the range of the data in the domains
    if(minX!=null && maxX!=null) x.domain([minX, maxX])
    else x.domain([0, d3.max(data, function(d){ return d.x; })])
    y.domain(data.map(function(d) { return d.y; }));
    //y.domain([0, d3.max(data, function(d) { return d.sales; })]);

    // append the rectangles for the bar chart
    svg.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .on("mouseover", handleMouseOver)
        .on("mouseout", handleMouseOut)
        .attr("class", "bar")
        //.attr("x", function(d) { return x(d.sales); })
        .attr("width", function(d) {return x(d.x); } )
        .attr("y", function(d) { return y(d.y); })
        .attr("height", y.bandwidth())
        .attr("onclick", function(d) {
            if(tagBarchart)
                return `getTagForEachcat('${d.y}')`;
            else
                return""
                //return `alert('${d.y} : ${d.x}')`;
        })
        //.attr("style", "cursor:pointer;");

    // add the x Axis
    svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x));

    // add the y Axis
    svg.append("g")
        .call(d3.axisLeft(y));

    tagBarchart = false;


    function handleMouseOver(d, i) {  // Add interactivity
        d3.select(this)
            .attr("height", y.bandwidth()+2)

        var xPos = d[0]
        var yPos = d[1]

        svg.append("text")
            .attr("id", function() { return "t" + xPos + "-" + yPos + "-" })
            .attr("x", function() { return x(xPos) - 30; })
            .attr("y", function() { return y(yPos) - 15; })
            .text(d.y+": "+d.x)
    }


    function handleMouseOut(d, i) {
        d3.select(this)
            .attr("height", y.bandwidth());

        var xPos = d[0]
        var yPos = d[1]
        d3.select("#t" + xPos + "-" + yPos + "-").remove();

    }
}

