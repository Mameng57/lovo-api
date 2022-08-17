<?php
  require_once "config/config.php";

  function get_images(int $sessionID): array
  {
    global $connect;
    $images = array();
    $imageViewPath = "localhost/lovo-api/helpers/image_view.php?image_id=";

    $sql = $connect -> query("SELECT id FROM image WHERE id_session = $sessionID");
    while ( $row = mysqli_fetch_assoc($sql) )
    {
      $images[] = $imageViewPath . $row['id'];
    }

    $response = array(
      'status' => 200,
      'message' => "Success.",
      'data' => $images,
    );

    return $response;
  }
?>
