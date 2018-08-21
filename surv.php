<div class="container">
  <h3>Survival Analysis</h3>

  <?php 
    $surv0 = "times";
    $surv1 = "patient_vital_status";
    $surv2 = "admin_disease_code";
    $surv3 = "bcr_patient_barcode";

    require 'connect.inc.php';
    $surv0_data = $surv1_data = array();
    $surv2_data = $surv3_data = array();
    $query0 = "SELECT $surv0 FROM brca";
    $query1 = "SELECT $surv1 FROM brca";
    $query2 = "SELECT $surv2 FROM brca";
    $query3 = "SELECT $surv3 FROM brca";


    if ($query_run = $mysqli->query($query0)){
      while ($query_row = $query_run->fetch_assoc()) {
          $surv0_data[] = $query_row[$surv0];
      }
    }

    if ($query_run = $mysqli->query($query1)){
      while ($query_row = $query_run->fetch_assoc()) {
          $surv1_data[] = $query_row[$surv1];
      }
    }
    if ($query_run = $mysqli->query($query2)){
      while ($query_row = $query_run->fetch_assoc()) {
          $surv2_data[] = $query_row[$surv2];
      }
    }
    if ($query_run = $mysqli->query($query3)){
      while ($query_row = $query_run->fetch_assoc()) {
          $surv3_data[] = $query_row[$surv3];
      }
    } else {
      echo "<br><b>The gene names are not correct. Check your input.</b><br>";
    }

    $file0 = fopen("surv0.csv","w");
    $file1 = fopen("surv1.csv","w");
    $file2 = fopen("surv2.csv","w");
    $file3 = fopen("surv3.csv","w");


    foreach ($surv0_data as $line)
      {
      fputcsv($file0,explode(',',$line));
      }
    fclose($file0); 

    foreach ($surv1_data as $line)
      {
      fputcsv($file1,explode(',',$line));
      }
    fclose($file1); 

    foreach ($surv2_data as $line)
      {
      fputcsv($file2,explode(',',$line));
      }
    fclose($file2);

    foreach ($surv3_data as $line)
      {
      fputcsv($file3,explode(',',$line));
      }
    fclose($file3);

    exec('/usr/local/bin/Rscript surv.R');
    foreach($out as $key => $value)
    {
      echo $key." ".$value."<br>";
    }
  ?>
  <img src="surv.png?rand=<?php echo rand(); ?>" width="800" height="800">
</div>