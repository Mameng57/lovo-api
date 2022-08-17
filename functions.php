<?php
  require_once "config/config.php";

  function get_images(int $sessionID): array
  {
    global $connect;
    $images = array();

    $sql = $connect -> query("SELECT * FROM image WHERE id_session = $sessionID");
    while ( $row = mysqli_fetch_assoc($sql) )
    {
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
