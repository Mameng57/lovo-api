<?php
  require_once "config/config.php";

  function user_login(string $username, string $password): array
  {
    global $connect;

    $sql = (
      "SELECT * FROM user WHERE phone = '$username' OR email = '$username'
      AND password = '" . sha1($password) . "'"
    );
    $query = mysqli_query($connect, $sql);
    $result = mysqli_fetch_assoc($query);

    if ( $result )
    {
      $personalKey = md5($username . rand(0, 25));

      $authenticate = mysqli_query(
        $connect, "INSERT INTO token VALUES ('$personalKey', " . $result['id'] . ")"
      );

      if ( $authenticate )
      {
        $response = array(
          'status' => 200,
          'message' => 'Succesfully Logged In.',
          'username' => $username,
          'password' => sha1($password),
          'date' => date("c", time()),
          'personal_key' => $personalKey,
        );
        header("Content-Type: application/json", true, 200);
      }
      else
      {
        $response = array(
          'status' => 500,
          'message' => "Failed to Logged In, Server Internal Failure, Please contact admin...",
        );
        header("Content-Type: application/json", true, 500);
      }
    }
    else
    {
      $response = array(
        'status' => 401,
        'message' => "Failed to Log in, please check credentials...",
      );
    }

    return $response;
  }

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
