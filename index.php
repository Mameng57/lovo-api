<?php
  require_once "functions.php";

  $fn = $_GET['function'];

  if ( function_exists($fn) ) {
    header("Content-Type: application/json");
    echo json_encode($fn());
  }
  else {
    echo "Tidak Ada";
  }
?>
