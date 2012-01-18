(function () {

    Renderer = function (canvas) {
        canvas = $(canvas).get(0)
        var ctx = canvas.getContext("2d")
        var particleSystem = null

        var palette = {
            "Africa":"#D68300",
            "Asia":"#4D7A00",
            "Europe":"#6D87CF",
            "North America":"#D4E200",
            "Oceania":"#4F2170",
            "South America":"#CD2900"
        }

        var that = {
            init:function (system) {
                particleSystem = system
                particleSystem.screen({padding:[100, 60, 60, 60], // leave some space at the bottom for the param sliders
                    step:.02}) // have the ‘camera’ zoom somewhat slowly as the graph unfolds
                $(window).resize(that.resize)
                that.resize()

                that.initMouseHandling()
            },
            redraw:function () {
                if (particleSystem === null) return

                ctx.clearRect(0, 0, canvas.width, canvas.height)
                ctx.strokeStyle = "#d3d3d3"
                ctx.lineWidth = 1
                ctx.beginPath()
                particleSystem.eachEdge(function (edge, pt1, pt2) {
                    // edge: {source:Node, target:Node, length:#, data:{}}
                    // pt1:  {x:#, y:#}  source position in screen coords
                    // pt2:  {x:#, y:#}  target position in screen coords

                    var weight = null // Math.max(1,edge.data.border/100)
                    var color = null // edge.data.color
                    if (!color || ("" + color).match(/^[ \t]*$/)) color = null

                    if (color !== undefined || weight !== undefined) {
                        ctx.save()
                        ctx.beginPath()

                        if (!isNaN(weight)) ctx.lineWidth = weight

                        if (edge.source.data.region == edge.target.data.region) {
                            ctx.strokeStyle = "#B9B9B9"
                        }

                        // if (color) ctx.strokeStyle = color
                        ctx.fillStyle = null

                        ctx.moveTo(pt1.x, pt1.y)
                        ctx.lineTo(pt2.x, pt2.y)
                        ctx.stroke()
                        ctx.restore()
                    } else {
                        // draw a line from pt1 to pt2
                        ctx.moveTo(pt1.x, pt1.y)
                        ctx.lineTo(pt2.x, pt2.y)
                    }
                })
                ctx.stroke()

                particleSystem.eachNode(function (node, pt) {
                    // node: {mass:#, p:{x,y}, name:"", data:{}}
                    // pt:   {x:#, y:#}  node position in screen coords


                    // determine the box size and round off the coords if we'll be
                    // drawing a text label (awful alignment jitter otherwise...)
                    var w = ctx.measureText(node.data.label || "").width + 6
                    var label = node.data.label
                    if (!(label || "").match(/^[ \t]*$/)) {
                        pt.x = Math.floor(pt.x)
                        pt.y = Math.floor(pt.y)
                    } else {
                        label = null
                    }

                    // clear any edges below the text label
                    // ctx.fillStyle = 'rgba(255,255,255,.6)'
                    // ctx.fillRect(pt.x-w/2, pt.y-7, w,14)


                    ctx.clearRect(pt.x - w / 2, pt.y - 7, w, 14)


                    // draw the text
                    if (label) {
                        ctx.font = "bold 11px Arial"
                        ctx.textAlign = "center"

                        // if (node.data.region) ctx.fillStyle = palette[node.data.region]
                        // else ctx.fillStyle = "#888888"
                        ctx.fillStyle = node.data.color

                        // ctx.fillText(label||"", pt.x, pt.y+4)
                        ctx.fillText(label || "", pt.x, pt.y + 4)
                    }
                })
            },

            resize:function () {
                var w = $(window).width(),
                    h = $(window).height();
                canvas.width = 800;
                canvas.height = 600 // resize the canvas element to fill the screen
                particleSystem.screenSize(800, 600) // inform the system so it can map coords for us
                that.redraw()
            },

            initMouseHandling:function () {
                // no-nonsense drag and drop (thanks springy.js)
                selected = null;
                nearest = null;
                var dragged = null;
                var oldmass = 1

                $(canvas).mousedown(function (e) {
                    var pos = $(this).offset();
                    var p = {x:e.pageX - pos.left, y:e.pageY - pos.top}
                    selected = nearest = dragged = particleSystem.nearest(p);

                    if (selected.node !== null) {
                        // dragged.node.tempMass = 10000
                        dragged.node.fixed = true
                    }
                    return false
                });

                $(canvas).mousemove(function (e) {
                    var old_nearest = nearest && nearest.node._id
                    var pos = $(this).offset();
                    var s = {x:e.pageX - pos.left, y:e.pageY - pos.top};

                    nearest = particleSystem.nearest(s);
                    if (!nearest) return

                    if (dragged !== null && dragged.node !== null) {
                        var p = particleSystem.fromScreen(s)
                        dragged.node.p = {x:p.x, y:p.y}
                        // dragged.tempMass = 10000
                    }

                    return false
                });

                $(window).bind('mouseup', function (e) {
                    if (dragged === null || dragged.node === undefined) return
                    dragged.node.fixed = false
                    dragged.node.tempMass = 100
                    dragged = null;
                    selected = null
                    return false
                });

            },

        }

        return that
    }

    /*var Maps = function(elt){

     }

     return that.init()
     }*/


    $(document).ready(function () {
        var sys = arbor.ParticleSystem(4000, 500, 0.5, 55)
        sys.renderer = Renderer("#viewport") // our newly created renderer will have its .init() method called shortly by sys...
        $.post("graphjson/", function (data) {
            sys.graft(jQuery.parseJSON(data))
            //sys.parameters({stiffness:400})
        });
        //var mcp = Maps("#maps")

    })


})()