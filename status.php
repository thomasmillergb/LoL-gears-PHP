<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 29/12/2014
 * Time: 22:09
 */
?>

<div class="middle" xmlns="http://www.w3.org/1999/html">
<p>Click ME</p>
    <p id="demo"></p>
    <p id="total"></p>
    <ul id='myid' class="test">

        <li id='1' value = '10'><a>10</a></li>
        <li id='2' value = '20'><a>20</a></li>
        <li id='3' value = '30'><a>30</a></li>
        <li id='4' value = '50'><a>50</a></li>
        <li id='5' value = '100'><a>100</a></li>
    </ul>
    <div class="stats">
        <div class="creeptotal">
            <h1> Total Creeps Dead by Match</h1>
            <canvas id="canvas" height="300" width="300" ></canvas>
        </div>
        <div class="creep10">
            <h1> Creeps Dead by 10mins for each Match</h1>
            <canvas id="canvas10" height="300" width="300"></canvas>
        </div>
        <div class="creep20">
            <h1> Creeps Dead by 20mins for each Match</h1>
            <canvas id="canvas20" height="300" width="300"></canvas>
        </div>

        <div class="creep30">
            <h1> Creeps Dead by 30mins for each Match</h1>
            <canvas id="canvas30" height="300" width="300"></canvas>
        </div>
        <div class="creep40">
            <h1> Creeps Dead by end for each Match</h1>
            <canvas id="canvas40" height="300" width="300"></canvas>
        </div><br>
        <div class="creepd10">
            <h1> CreepDiff 10</h1>
            <canvas id="canvasd10" height="300" width="300"></canvas>
        </div>
        <div class="creepd20">
            <h1> CreepDiff 20</h1>
            <canvas id="canvasd20" height="300" width="300"></canvas>
        </div>

        <div class="creepd30">
            <h1> CreepDiff 30</h1>
            <canvas id="canvasd30" height="300" width="300"></canvas>
        </div>
        <div class="creepd40">
            <h1> CreepDiff 40</h1>
            <canvas id="canvasd40" height="300" width="300"></canvas>
        </div>
    </div>



        <script src="/jquery/jquery-2.1.3.min.js"></script>

    <script>
        $(document).ready(function(){
            var el = $("li");

            $(el).click(function(){
                el.each(function(){
                    $(this).removeClass("clicked");
                });
                $(this).addClass("clicked");
            });
        });


        //alert(j_creeps.slice(l-10,l));









        function getLabels(ix){
            var b = [];

            for (var i = ix; i >= 1; i--) {
                b.push(i.toString());
            }

            return b;


        }
        function dataSets(label , data){
            return  {
                labels: label,

                datasets: [
                    {
                        label: "My First dataset",
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "rgba(220,220,220,1)",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",

                        pointHighlightStroke: "rgba(220,220,220,1)",

                        //data
                        data: data

                    },

                ]


            };


        }


        <?php echo "var j_creeps = ". $j_creeps . ";\n" ;?>
        <?php echo "var j_creeps10 = ". $j_creeps10 . ";\n" ;?>
        <?php echo "var j_creeps20 = ". $j_creeps20 . ";\n" ;?>
        <?php echo "var j_creeps30 = ". $j_creeps30 . ";\n" ;?>
        <?php echo "var j_creeps40 = ". $j_creeps40 . ";\n" ;?>

        <?php echo "var j_creepsd10 = ". $j_creepsd10 . ";\n" ;?>
        <?php echo "var j_creepsd20 = ". $j_creepsd20 . ";\n" ;?>
        <?php echo "var j_creepsd30 = ". $j_creepsd30 . ";\n" ;?>
        <?php echo "var j_creepsd40 = ". $j_creepsd40 . ";\n" ;?>
        var l = (j_creeps.length);


        var ctxCreepTotal = document.getElementById("canvas").getContext("2d");
        var ctxCreep10 = document.getElementById("canvas10").getContext("2d");
        var ctxCreep20 = document.getElementById("canvas20").getContext("2d");
        var ctxCreep30 = document.getElementById("canvas30").getContext("2d");
        var ctxCreep40 = document.getElementById("canvas40").getContext("2d");

        var ctxCreepd10 = document.getElementById("canvasd10").getContext("2d");
        var ctxCreepd20 = document.getElementById("canvasd20").getContext("2d");
        var ctxCreepd30 = document.getElementById("canvasd30").getContext("2d");
        var ctxCreepd40 = document.getElementById("canvasd40").getContext("2d");
        updateWindows(10);
        $(document).ready(function () {
            $("ul[id*=myid] li").click(function () {

                updateWindows($(this).attr("value"));

            });
        });
        function scaled(scale, data){

            var chucked = chunk(data, scale/10);
//            document.getElementById("demo").innerHTML = chucked;


        }
        function sum(element, index, array){
            var sum = 0;
            element.forEach( function addNumber(value) { sum += value; });
           // document.getElementById("demo").innerHTML = element ;
  //          document.getElementById("total").innerHTML = sum ;

            return sum;
        }

        function chunk (arr, len) {

            var chunks = [],
                i = 0,
                n = arr.length;

            while (i < n) {
                chunks.push(arr.slice(i, i += len).forEach(sum));
            }
            document.getElementById("demo").innerHTML = chunks;
            return chunks;
        }

        function updateWindows(value){
          //  scaled(value, j_creeps);
            window.creep = redrawLine(value,ctxCreepTotal, window.creep,j_creeps);
            window.creep10 = redrawLine(value,ctxCreep10,window.creep10,j_creeps10);
            window.creep20 = redrawLine(value,ctxCreep20,window.creep20,j_creeps20);
            window.creep30 = redrawLine(value,ctxCreep30,window.creep30,j_creeps30);
            window.creep40 = redrawLine(value,ctxCreep40,window.creep40,j_creeps40);


            window.creepd10 = redrawLine(value,ctxCreepd10, window.creepd10,j_creepsd10);
            window.creepd20 = redrawLine(value,ctxCreepd20, window.creepd20,j_creepsd20);
            window.creepd30 = redrawLine(value,ctxCreepd30, window.creepd30,j_creepsd30);
            window.creepd40 = redrawLine(value,ctxCreepd40, window.creepd40,j_creepsd40);

        }

        function redrawLine(newData,ct,line,data){
            if(newData >49){

                var newlineChartData = dataSets(getLabels(newData) ,(data.slice(l-newData,l)) );
            }
            else
            {
                var newlineChartData = dataSets(getLabels(newData) ,(data.slice(l-newData,l)) );

            }

            if(line)
                line.destroy();
            ct.canvas.width = 300;
            ct.canvas.height = 300;

            //ctxCreepTotal.destroy();
            //var ctxCreepTotal = document.getElementById("canvas").getContext("2d");
            line = new Chart(ct).Line(newlineChartData);
            ct.canvas.width = 300;
            ct.canvas.height = 300;
            return line



        }

        </script>




</div>