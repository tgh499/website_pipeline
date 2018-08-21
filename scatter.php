  <div class="container">
    <h3>Scatter Plot</h3>
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

      exec('/anaconda2/bin/python sliceArray.py');

      foreach($out as $key => $value)
      {
        echo $key." ".$value."<br>";
      }
    ?>
    <img src="scatter.png?rand=<?php echo rand(); ?>" width="800" height="800">
  </div>