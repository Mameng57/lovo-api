<?php
  require_once "config.php";

  $fn = $_GET['function'];

  if ( function_exists($fn) ) {
    echo "Fungsi Ada";
  }
  else {
    echo "Tidak Ada";
  }
?>
