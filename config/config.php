<?php
  $hostname = "localhost";
  $username = "root";
  $password = "";
  $database = "lovo-db";

  $connect = mysqli_connect($hostname, $username, $password, $database, 3306);

  if ( !$connect )
  {
    die("Koneksi ke Database gagal..." . mysqli_connect_error());
  }
?>
