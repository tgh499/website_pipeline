  <div class="container">
    <h3>Box Plot</h3>
    <p>Select Database. Examples for parameter 1: diagnosisAge, lymph_nodes, patientsWeight; Examples for parameter 2: gender, priorCancerOccurence, race.</p>

    <form method="post">
      Choose Parameter: <input type="text" name="param0">
      Choose Parameter:  <input type="text" name="param1">
      <!--Number of data points: <input type="text" name="dtpts"> -->
      <input type="submit">
    </form>

    <?php 
      $param0 = $_POST["param0"];
      $param1 = $_POST["param1"];

      file_put_contents('param0.txt', $param0);
      file_put_contents('param1.txt', $param1);
  
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
    <img src="boxplot.png?rand=<?php echo rand(); ?>">
  </div>