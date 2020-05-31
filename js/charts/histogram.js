/**
 * Created by Vito on 24/05/2020.
 */

function plotHistogram(svg_id,dataT,min,max,nBin,yLabel,xLabel){

    var screenWidth = $( document  ).width();

    // set the dimensions and margins of the graph
    var margin = {top: 20, right: 20, bottom: 30, left: 70},
        width = (screenWidth-300) - margin.left - margin.right,
        height = 600 - margin.top - margin.bottom ;

// append the svg object to the body of the page
    var svg = d3.select("#"+svg_id)
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

// get the data

        var data = dataT
        // X axis: scale and draw:
        var x = d3.scaleLinear()
            .domain([min, max])     // can use this instead of 1000 to have the max of data: d3.max(data, function(d) { return +d.value })
            .range([0, width]);
        svg.append("g")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x));

        // Y axis: initialization
        var y = d3.scaleLinear()
            .range([height, 0]);
        var yAxis = svg.append("g")


        var size = (max-min)/nBin;
        var BINS = [];
        var end = min;
        while(end<max){
            BINS.push(end+size);
            end += size;
        }

        // set the parameters for the histogram
        var histogram = d3.histogram()
            .value(function(d) { return d.value; })   // I need to give the vector of value
            .domain(x.domain())  // then the domain of the graphic
            .thresholds(BINS); // then the numbers of bins

        // And apply this function to data to get the bins
        var bins = histogram(data);

        // Y axis: update now that we know the domain
        y.domain([0, d3.max(bins, function(d) { return d.length; })]);   // d3.hist has to be called before the Y axis obviously
        yAxis
            .transition()
            .duration(0)
            .call(d3.axisLeft(y));

        // Join the rect with the bins data
        var u = svg.selectAll("rect")
            .data(bins)

        // Manage the existing bars and eventually the new ones:
        u
            .enter()
            .append("rect") // Add a new rect for each new elements
            .merge(u) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(0)
            .attr("x", 1)
            .attr("transform", function(d) { return "translate(" + x(d.x0) + "," + y(d.length) + ")"; })
            .attr("width", function(d) { return x(d.x1) - x(d.x0) -1 ; })
            .attr("height", function(d) { return height - y(d.length); })
            .style("fill", "#69b3a2")


        // If less bar in the new histogram, I delete the ones not in use anymore
        u
            .exit()
            .remove()

        svg.append("text")
            .attr('class', 'axis-label')
            .attr("text-anchor", "end")
            .attr("y", -40)
            .attr("transform", "rotate(-90)")
            .style('font-weight',' bold')
            .text(yLabel);
        svg.append("text")
            .attr('class', 'axis-label')
            .attr("text-anchor", "end")
            .attr("x", width)
            .attr("y", height + 35)
            .style('font-weight',' bold')
            .text(xLabel);


}
