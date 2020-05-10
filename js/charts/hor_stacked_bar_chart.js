/**
 * Created by Vito on 20/04/2020.
 */
function hor_st_bar_chart(svg_id,csv){
    var screenWidth = $( document  ).width();
    var keys = ["models","views","likes","comments"];

    var svg = d3.select("#"+svg_id),
        margin = {top: 35, left: 130, bottom: 0, right: 15},
        width = (screenWidth-330),
        height = 600;

    var y = d3.scaleBand()
        .range([margin.top, height - margin.bottom])
        .padding(0.1)
        .paddingOuter(0.2)
        .paddingInner(0.2)

    var x = d3.scaleLinear()
        .range([margin.left, width - margin.right])

    var yAxis = svg.append("g")
        .attr("transform", `translate(${margin.left},0)`)
        .attr("class", "y-axis")

    var xAxis = svg.append("g")
        .attr("transform", `translate(0,${margin.top})`)
        .attr("class", "x-axis")

    var z = d3.scaleOrdinal()
        .range(["steelblue", "darkorange", "lightblue","#FF4821"])
        .domain(keys);


        var data = csv
        var speed = 0;

        data.forEach(function(d) {
            d.total = d3.sum(keys, k => +d[k])
            return d
        })

        x.domain([0, d3.max(data, d => d.total)]).nice();

        svg.selectAll(".x-axis").transition().duration(speed)
            .call(d3.axisTop(x).ticks(null, "s"))


        y.domain(data.map(d => d.Category));

        svg.selectAll(".y-axis").transition().duration(speed)
            .call(d3.axisLeft(y).tickSizeOuter(0))

        var group = svg.selectAll("g.layer")
            .data(d3.stack().keys(keys)(data), d => d.key)

        group.exit().remove()

        group.enter().insert("g", ".y-axis").append("g")
            .classed("layer", true)
            .attr("fill", d => z(d.key));

        var bars = svg.selectAll("g.layer").selectAll("rect")
            .data(d => d, e => e.data.Category);

        bars.exit().remove()

        bars.enter().append("rect")
            .attr("height", y.bandwidth())
            .merge(bars)
            .transition().duration(speed)
            .style("cursor","pointer")
            .attr("y", d => y(d.data.Category))
            .attr("x", d => x(d[0]))
            .attr("onclick", d => `alert('#Models: ${d.data.models}% - Views: ${d.data.views}% - Likes: ${d.data.likes}% - Commensts: ${d.data.comments}%')`)
            .attr("width", d => x(d[1]) - x(d[0]))

        var posY = 130;
        var colors = ["steelblue", "darkorange", "lightblue","#FF4821"]
        for(var k=0; k<keys.length; k++){
            svg.append("circle").attr("cx",width-100).attr("cy",posY).attr("r", 6).style("fill", colors[k])
            svg.append("text").attr("x", width-90).attr("y", posY).text("#"+keys[k]).style("font-size", "15px").attr("alignment-baseline","middle")
            posY += 30
        }

}