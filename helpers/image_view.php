<?php
  require_once ($_SERVER['DOCUMENT_ROOT'] . "/lovo-api/config/config.php");

  if ( isset($_GET['imageID']) )
  {
    $sql = "SELECT image FROM image WHERE id = " . $_GET['imageID'];
    $query = mysqli_query($connect, $sql);
    $imageData = mysqli_fetch_array($query, MYSQLI_NUM);

    header("Content-Type: image/jpeg");
    echo $imageData[0];
  }
?>
