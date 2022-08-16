<?php
  require_once "config.php";

  function get_images(int $id): void {
    global $connect;
    $images = array();

    $sql = $connect -> query("SELECT image FROM images WHERE id_user = $id");
    while ( $row = mysqli_fetch_assoc($sql) ) {
      $images[] = $row['image'];
    }


  }
?>
