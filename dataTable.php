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