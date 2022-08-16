<?php
  $hostname = "localhost";
  $username = "root";
  $password = "";
  $database = "lovo-db";

  $connect = mysqli_connect($hostname, $username, $password, $database, 3306);

  if ( !$connect ) {
    die("Connection to Database Failed..." . mysqli_connect_error());
  }
?>
