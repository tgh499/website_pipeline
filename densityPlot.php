<div class="container">
    <p>Enter two sample names for which you want to see scatter plot. For example: GSM1113310 and GSM1171525. More example data: GSM1171527, GSM937160, GSM1113308, GSM1113301, GSM1113307, GSM1113302</p>
    <form method="post">
      Sample1: <input type="text" name="samp0">
      Sample2: <input type="text" name="samp1">
      <!--Number of data points: <input type="text" name="dtpts"> -->
      <input type="submit">
    </form>
    <?php 
      $samp0 = $_POST["samp0"];
      $samp1 = $_POST["samp1"];
      $samp0_data = $samp1_data = array();
      require 'connect.inc.php';
      $query0 = "SELECT $samp0 FROM brstCncr_data";
      $query1 = "SELECT $samp1 FROM brstCncr_data";
      if ($query_run = $mysqli->query($query0)){
        while ($query_row = $query_run->fetch_assoc()) {
            $gene0_data[] = $query_row[$samp0];
        }
      }
      if ($query_run = $mysqli->query($query1)){
        while ($query_row = $query_run->fetch_assoc()) {
            $gene1_data[] = $query_row[$samp1];
        }
      } else {
        echo "<br><b>The gene names are not correct. Check your input.</b><br>";
      }
      //file_put_contents('gene0.txt', print_r(array_values;

      $file0 = fopen("samp0.csv","w");
      $file1 = fopen("samp1.csv","w");

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
      exec('/Users/tgh/anaconda2/bin/python density.py');
        //'python foo.py' . json_encode($associativeArray)
    ?>
    <img src="density.png" alt="Trulli" width="800" height="800">
</div?