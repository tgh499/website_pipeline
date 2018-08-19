<!DOCTYPE html>
<html lang="en">
  <head>
    <?php header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); header('Cache-Control: no-store, no-cache, must-revalidate'); header('Cache-Control: post-check=0, pre-check=0', FALSE); header('Pragma: no-cache'); ?>
    <style>
      .navbar {
      overflow: hidden;
      background-color: #333;
      font-family: Arial, Helvetica, sans-serif;
      }

      .navbar a {
      float: left;
      font-size: 16px;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
      }

      .dropdown {
      float: left;
      overflow: hidden;
      }

      .dropdown .dropbtn {
      font-size: 16px;    
      border: none;
      outline: none;
      color: white;
      padding: 14px 16px;
      background-color: inherit;
      font-family: inherit;
      margin: 0;
      }

      .navbar a:hover, .dropdown:hover .dropbtn {
      background-color: red;
      }

      .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      }

      .dropdown-content a {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
      }

      .dropdown-content a:hover {
      background-color: #ddd;
      }

      .dropdown:hover .dropdown-content {
      display: block;
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
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.v2.js"></script>
  </head>
  <body>
  <?php include 'table.php'?>
  <?php include 'scatter.php'?>
  <?php include 'sampleTable.php';?>
  <?php include 'densityPlot.php'?>
  <?php include 'boxplot.php'?>
<p>In this example, we use JavaScript to "click" on the London button, to open the tab on page load.</p>

  <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">London</button>
    <button class="tablinks" onclick="openCity(event, 'Paris')">Paris</button>
    <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button>
  </div>

  <div id="London" class="tabcontent">
    <h3>London</h3>
    <p>London is the capital city of England.</p>
  </div>

  <div id="Paris" class="tabcontent">
    <h3>Paris</h3>
    <p>Paris is the capital of France.</p> 
  </div>

  <div id="Tokyo" class="tabcontent">
    <h3>Tokyo</h3>
    <p>Tokyo is the capital of Japan.</p>
  </div>
  <script>
  function openCity(evt, cityName) {
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