<?php
  require_once "config/config.php";

  function get_images(int $id): array {
    global $connect;
    $images = array();

    $sql = $connect -> query("SELECT image FROM images WHERE id_user = $id");
    while ( $row = mysqli_fetch_assoc($sql) ) {
      $images[] = $row['image'];
    }

    $response = array(
      'status' => 200,
      'message' => "Success.",
      'data' => $images,
    );

    return $response;
  }
?>
