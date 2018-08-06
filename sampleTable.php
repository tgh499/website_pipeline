<div class="container">    
    <p>Please enter the name of the sample:</p>
    <form method="post">
      Sample: <input type="text" name="sample_query">
      <input type="submit">
    </form>

    <table class="table" id="myTable">
      <tr class="header">
        <th style="width:40%">Property</th>
        <th style="width:60%">Value</th>
      </tr>
      <p>Example sample names: GSM1113301, GSM1113301, GSM1113302, GSM1113305, GSM937164, GSM1113304</p>
      <?php
        require 'connect.inc.php';
        $sample = $_POST["sample_query"];
        //$query = "SELECT * FROM sample_annotation WHERE `Sample_geo_accession` LIKE 'GSM1113301'";
        $query = "SELECT * FROM sample_annotation WHERE `Sample_geo_accession` LIKE '$sample'";
        if ($query_run = $mysqli->query($query)){
          while ($query_row = $query_run->fetch_assoc()) {
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_channel_count";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_channel_count"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_characteristics_ch1";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_characteristics_ch1"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_address";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_address"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_city";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_city"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_country";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_country"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_department";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_department"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_email";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_email"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_institute";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_institute"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_laboratory";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_laboratory"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_name";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_name"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_contact_phone";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_contact_phone"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_instrument_model";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_instrument_model"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "reads_total";
              echo "</td>";
              echo "<td>";
              echo $query_row["reads_total"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "reads_aligned";
              echo "</td>";
              echo "<td>";
              echo $query_row["reads_aligned"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_title";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_title"];
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              // storing the values of query to pass onto JavaScript later
              echo "Sample_series_id";
              echo "</td>";
              echo "<td>";
              echo $query_row["Sample_series_id"];
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

</div>