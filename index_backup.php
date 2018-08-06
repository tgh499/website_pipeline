
<html lang="en">
<style>
.slidecontainer {
    width: 100%;
}

.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 25px;
    background: #d3d3d3;
    outline: none;
    opacity: 0.7;
    -webkit-transition: .2s;
    transition: opacity .2s;
}

.slider:hover {
    opacity: 1;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 25px;
    height: 25px;
    background: #4CAF50;
    cursor: pointer;
}
</style>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

    <!-- Javascript for live search-->
    <script>
    function showResult(str) {
      if (str.length==0) { 
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";
        return;
      }
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else {  // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          document.getElementById("livesearch").innerHTML=this.responseText;
          document.getElementById("livesearch").style.border="1px solid #A5ACB2";
        }
      }
      xmlhttp.open("GET","livesearch.php?q="+str,true);
      xmlhttp.send();
    }
    </script>

    <title>Hello, world!</title>
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
              if ($query_run = $mysqli->query($query)){

                while ($query_row = $query_run->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>";
                    echo $query_row["Sample_series_id"];
                    echo "</td>";
                    echo "<td>";
                    echo $query_row["Sample_source_name_ch1"];
                    echo "</td>";
                    echo "<td>";
                    echo $query_row["gene_entrezid"];
                    echo "</td>";
                    echo "</tr>";
                }
              } else {
                echo "Query FAILED!";
              }
              $mysqli->close();
            ?>
      </table>
    </div>
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

  <div class="container">
    <form>
      <input type="text" size="30" onkeyup="showResult(this.value)">
      <div id="livesearch"></div>
    </form>
  </div>


  <div class="toshow" style="display:none" id=DIV3>
      <?php
        require 'connect.inc.php';
        $query = "SELECT gene_entrezid FROM newSamples";
        if ($query_run = $mysqli->query($query)){

          while ($query_row = $query_run->fetch_assoc()) {
              echo "<p>";
              echo $query_row["gene_entrezid"];
              echo "</p>";
          }
        } else {
          echo "Query FAILED!";
        }
        $mysqli->close();
      ?>
  </div>

  <div class = container>
      <div class="""slidecontainer">
        <input type="range" min="1" max="10000" value="700" class="slider" id="myRange">
        <p>Value: <span id="demo"></span></p>
      </div>
  </div>

  <script>
      var slider = document.getElementById("myRange");
      var output = document.getElementById("demo");
      output.innerHTML = slider.value;

      slider.oninput = function() {
        output.innerHTML = this.value;
      }
  </script>

  <div class="container">
      <?php
        require 'connect.inc.php';
        $query0 = "SELECT GSM1113310 FROM brstCncr_data";
        $query1 = "SELECT GSM1171525 FROM brstCncr_data";
        echo "<div class=\"toshow\" style=\"display:none\" id=SAMPLE1>";
        if ($query_run = $mysqli->query($query0)){
          while ($query_row = $query_run->fetch_assoc()) {
              echo "<p>";
              echo $query_row["GSM1113310"];
              echo "</p>";
          }
        } else {
          echo "Query FAILED!";
        }
        echo "</div>";

        echo "<div class=\"toshow\" style=\"display:none\" id=SAMPLE2>";
        if ($query_run = $mysqli->query($query1)){
          while ($query_row = $query_run->fetch_assoc()) {
              echo "<p>";
              echo $query_row["GSM1171525"];
              echo "</p>";
          }
        } else {
          echo "Query FAILED!";
        }
        echo "</div>";
        $mysqli->close();
      ?>
  </div>

  <div class="container">
    <canvas id="scatterPlot" width=50%></canvas>
    <script>

      // get the numbers from div element
      var MyDiv3 = document.getElementById('SAMPLE1');
      var string = MyDiv3.outerHTML;
      var numbers0 = string.match(/\d+/g).map(Number);
      console.log(numbers0);

      var MyDiv3 = document.getElementById('SAMPLE2');
      var string = MyDiv3.outerHTML;
      var numbers1 = string.match(/\d+/g).map(Number);
      console.log(numbers1);

      var scatterPoints = [];
      for (var i = 0; i < numbers0.length; i++) {
        var temp = {};
        temp['x'] = numbers0[i];
        temp['y'] = numbers1[i];
        scatterPoints.push(temp);
      }
      console.log(scatterPoints[0]);


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




  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
  </body>
</html>
