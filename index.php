<!DOCTYPE html>
<html lang="en">
  <head>
    <style>
      body {font-family: Arial;}

      /* Style the tab */
      .tab {
          overflow: hidden;
          border: 1px solid #ccc;
          background-color: #f1f1f1;
      }

      /* Style the buttons inside the tab */
      .tab button {
          background-color: inherit;
          float: left;
          border: none;
          outline: none;
          cursor: pointer;
          padding: 14px 16px;
          transition: 0.3s;
          font-size: 17px;
      }

      /* Change background color of buttons on hover */
      .tab button:hover {
          background-color: #ddd;
      }

      /* Create an active/current tablink class */
      .tab button.active {
          background-color: #ccc;
      }

      /* Style the tab content */
      .tabcontent {
          display: none;
          padding: 6px 12px;
          border: 1px solid #ccc;
          border-top: none;
      }
    </style>
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
  </head>
  <body>

    <div class="container">
      <p>Click on tabs to perform intended action.</p>
      <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Table')" id="defaultOpen">Sample Table</button>
        <button class="tablinks" onclick="openTab(event, 'SampleAnnot')">Sample Annotation</button>
        <button class="tablinks" onclick="openTab(event, 'ScatterPlot')">ScatterPlot</button>
        <button class="tablinks" onclick="openTab(event, 'DensityPlot')">DensityPlot</button>
        <button class="tablinks" onclick="openTab(event, 'Box')">Box Plot</button>
        <button class="tablinks" onclick="openTab(event, 'Surv')">Survival Analysis</button>
      </div>
      <div id="Table" class="tabcontent">
        <h3>Sample Table</h3>
        <?php include 'table.php'?>
      </div>

      <div id="Box" class="tabcontent">
        <?php include 'box.php'?> 
      </div>  

      <div id="Surv" class="tabcontent">
        <?php include 'surv.php'?> 
      </div>

      <div id="SampleAnnot" class="tabcontent">
        <?php include 'sampleTable.php';?>
      </div>

      <div id="ScatterPlot" class="tabcontent">
        <?php include 'scatter.php'?>
      </div>

      <div id="DensityPlot" class="tabcontent">
        <?php include 'densityPlot.php'?> 
      </div>


    </div>
    <script>
      function openTab(evt, cityName) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
              tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
              tablinks[i].className = tablinks[i].className.replace(" active", "");
          }
          document.getElementById(cityName).style.display = "block";
          evt.currentTarget.className += " active";
      }
      // Get the element with id="defaultOpen" and click on it
      document.getElementById("defaultOpen").click();
    </script>
  </body>
</html>