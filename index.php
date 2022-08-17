<?php
  require_once "functions.php";

  $fn = $_GET['function'];

  if ( function_exists($fn) )
  {
    $id = 0;

    header("Content-Type: application/json");
    echo json_encode($fn($id));
  }
  else
  {
    echo "Tidak Ada";
  }
?>
