<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>database project</title>

    <!-- Bootstrap: the modified theme takes care of the table-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .chart {

    }

    .main text {
        font: 10px sans-serif;  
    }

    .axis line, .axis path {
        shape-rendering: crispEdges;
        stroke: black;
        fill: none;
    }

    circle {
        fill: steelblue;
    }

    </style>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.v2.js"></script>
  </head>
  <body>
    <div class="col-xs-12" style="height:50px;"></div>
      <div class="container" id="DIV1">
        <input type="text" id="searchInput" onkeyup="myFunction()" placeholder="Search for genes.." title="Type in a gene name">
        <table class="table" id="myTable">
          <tr class="header">
            <th style="width:60%"">Sample_series_id</th>
            <th style="width:60%">Sample_source_name_ch1</th>
            <th style="width:60%">gene_entrezid</th>
          </tr>
          <?php
            require 'connect.inc.php';
            $query = "SELECT Sample_series_id,Sample_source_name_ch1,gene_entrezid FROM newSamples";
            $sample_series_id = $sample_channel = $gene_entrezid = array();
            if ($query_run = $mysqli->query($query)){
              while ($query_row = $query_run->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>";
                  // storing the values of query to pass onto JavaScript later
                  $gene_entrezid[] =  $query_row['gene_entrezid'];
                  echo $query_row["Sample_series_id"];
                  echo "</td>";
                  echo "<td>";
                  $sample_channel[] = $query_row['Sample_source_name_ch1'];
                  echo $query_row["Sample_source_name_ch1"];
                  echo "</td>";
                  echo "<td>";
                  $Sample_series_id[] = $query_row['Sample_series_id'];
                  echo $query_row["gene_entrezid"];
                  echo "</td>";
                  echo "</tr>";
              }
            } else {
              echo "Query FAILED!";
            }
            $mysqli->close();
            //print_r(array_values($gene_entrezid));
          ?>
        </table>
        <!--table SEARCH function-->
        <script>
          function myFunction() {
            var input, filter, table, tr, td, i;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
            // change COLUMN by selecting 0,1 etc.
              td = tr[i].getElementsByTagName("td")[1];
              if (td) {
                console.log(td.outerHTML)
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
              }       
            }

          }
        </script>
      </div>
    </div>

  <div class="container">
    <p>Enter two gene names for which you want to see scatter plot. For example: GSM1113310 and GSM1171525. More example data: GSM1171527, GSM937160, GSM1113308, GSM1113301, GSM1113307, GSM1113302</p>
    <form method="post">
      Sample1: <input type="text" name="gene0">
      Sample2: <input type="text" name="gene1">
      <!--Number of data points: <input type="text" name="dtpts"> -->
      <input type="submit">
    </form>
    <?php 
      $gene0 = $_POST["gene0"];
      $gene1 = $_POST["gene1"];
      $dtpts = $_POST["dtpts"];
      $gene0_data = $gene1_data = array();
      require 'connect.inc.php';
      $query0 = "SELECT $gene0 FROM brstCncr_data";
      $query1 = "SELECT $gene1 FROM brstCncr_data";
      if ($query_run = $mysqli->query($query0)){
        while ($query_row = $query_run->fetch_assoc()) {
            $gene0_data[] = $query_row[$gene0];
        }
      }
      if ($query_run = $mysqli->query($query1)){
        while ($query_row = $query_run->fetch_assoc()) {
            $gene1_data[] = $query_row[$gene1];
        }
      } else {
        echo "<br><b>The gene names are not correct. Check your input.</b><br>";
      }
      //file_put_contents('gene0.txt', print_r(array_values;
      $output = passthru('python sliceArray.py');
        //'python foo.py' . json_encode($associativeArray)

    ?>
    
    <canvas id="scatterPlot" width=50%></canvas>
    <script>

      // get the numbers from div element
      var gene0_data = <?php echo json_encode($gene0_data);?>;
      var gene1_data = <?php echo json_encode($gene1_data);?>;
      var scatterPoints = [];
      
      for (var i = 0; i < gene0_data.length; i++) {
        var temp = {};
        temp['x'] = gene0_data[i];
        temp['y'] = gene1_data[i];
        scatterPoints.push(temp);
      }

      var ctx = document.getElementById("scatterPlot").getContext('2d');
      var scatterChart = new Chart(ctx, {
          type: 'scatter',
          data: {
              datasets: [{
                  label: 'Scatter Dataset',
                  borderColor: "green",
                  data: scatterPoints
              }]
          },
          options: {
              scales: {
                  xAxes: [{
                      type: 'logarithmic',
                      position: 'bottom'
                  }]
              }
          }
      });
    </script>
  </div>

  <div class='content'>
          <!-- /the chart goes here -->
  </div>
  <script type="text/javascript">
    var gene0_data = <?php echo json_encode($gene0_data);?>;
    var gene1_data = <?php echo json_encode($gene1_data);?>;
    var data = [];
    
    for (var i = 0; i < gene0_data.length; i++) {
      var temp = [];
      temp.push(gene0_data[i]);
      temp.push(gene1_data[i]);
      data.push(temp);
    }

    console.log(data[0]);
    //var data = [[5,3], [10,17], [15,4], [2,8]];
       
        var margin = {top: 20, right: 15, bottom: 60, left: 60}
          , width = 960 - margin.left - margin.right
          , height = 960 - margin.top - margin.bottom;
        
        var x = d3.scale.linear()
                  .domain([0, d3.max(data, function(d) { return d[0]; })])
                  .range([ 0, width ]);
        
        var y = d3.scale.linear()
                .domain([0, d3.max(data, function(d) { return d[1]; })])
                .range([ height, 0 ]);
     
        var chart = d3.select('body')
      .append('svg:svg')
      .attr('width', width + margin.right + margin.left)
      .attr('height', height + margin.top + margin.bottom)
      .attr('class', 'chart')

        var main = chart.append('g')
      .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')
      .attr('width', width)
      .attr('height', height)
      .attr('class', 'main')   
            
        // draw the x axis
        var xAxis = d3.svg.axis()
      .scale(x)
      .orient('bottom');

        main.append('g')
      .attr('transform', 'translate(0,' + height + ')')
      .attr('class', 'main axis date')
      .call(xAxis);

        // draw the y axis
        var yAxis = d3.svg.axis()
      .scale(y)
      .orient('left');

        main.append('g')
      .attr('transform', 'translate(0,0)')
      .attr('class', 'main axis date')
      .call(yAxis);

        var g = main.append("svg:g"); 
        
        g.selectAll("scatter-dots")
          .data(data)
          .enter().append("svg:circle")
              .attr("cx", function (d,i) { return x(d[0]); } )
              .attr("cy", function (d) { return y(d[1]); } )
              .attr("r", 1);
  </script>

  </body>
</html>