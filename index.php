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
    <p>Enter two sample names for which you want to see scatter plot. For example: GSM1113310 and GSM1171525. More example data: GSM1171527, GSM937160, GSM1113308, GSM1113301, GSM1113307, GSM1113302</p>
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

      $file0 = fopen("gene0.csv","w");
      $file1 = fopen("gene1.csv","w");

      foreach ($gene0_data as $line)
        {
        fputcsv($file0,explode(',',$line));
        }

      fclose($file0); 

      foreach ($gene1_data as $line)
        {
        fputcsv($file1,explode(',',$line));
        }

      fclose($file1); 

      //$output = passthru('python sliceArray.py');
      //exec('/usr/local/bin/python2.7 sliceArray.py');
      exec('/Users/tgh/anaconda2/bin/python sliceArray.py');
      

      foreach($out as $key => $value)
      {
        echo $key." ".$value."<br>";
      }
        //'python foo.py' . json_encode($associativeArray)
    ?>
  </div>

  <div class="container">
    <img src="scatter.png" width="800" height="800">
    
  </div>

  <?php include 'sampleTable.php';?>
  <?php include 'densityPlot.php'?>
  <?php 
      exec('./hello.R');
        foreach($out as $key => $value)
      {
        echo $key." ".$value."<br>";
      }
  ?>
  <div class="container">
    <p>Select Database. Examples for parameter 1: diagnosisAge, lymph_nodes, patientsWeight; Examples for parameter 2: gender, priorCancerOccurence, race.</p>
      <form method="post">
        Database Name: <input type="text" name="databaseName">
      <br><br>
      Choose Parameter: <input type="text" name="param0">
      Choose Parameter:  <input type="text" name="param1">
      <!--Number of data points: <input type="text" name="dtpts"> -->
      <input type="submit">
    </form>
    <?php 
      $param0 = $_POST["param0"];
      $param1 = $_POST["param1"];
  
      require 'connect.inc.php';
      $param0_data = $param1_data = array();
      $query0 = "SELECT $param0 FROM tcga";
      $query1 = "SELECT $param1 FROM tcga";
      if ($query_run = $mysqli->query($query0)){
        while ($query_row = $query_run->fetch_assoc()) {
            $param0_data[] = $query_row[$param0];
        }
      }
      if ($query_run = $mysqli->query($query1)){
        while ($query_row = $query_run->fetch_assoc()) {
            $param1_data[] = $query_row[$param1];
        }
      } else {
        echo "<br><b>The gene names are not correct. Check your input.</b><br>";
      }
      //file_put_contents('gene0.txt', print_r(array_values;

      $file0 = fopen("param0.csv","w");
      $file1 = fopen("param1.csv","w");

      foreach ($param0_data as $line)
        {
        fputcsv($file0,explode(',',$line));
        }

      fclose($file0); 

      foreach ($param1_data as $line)
        {
        fputcsv($file1,explode(',',$line));
        }

      fclose($file1); 
      exec('/usr/local/bin/Rscript hello.R');
      foreach($out as $key => $value)
      {
        echo $key." ".$value."<br>";
      }

    ?>
    <img src="boxplot.png" width="800" height="800">
    
  </div>
  </div>

  </body>
</html>