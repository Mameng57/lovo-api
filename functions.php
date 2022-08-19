<?php
  require_once "config/config.php";

  function generate_token(string $username): string
  {
    return md5($username . rand(0, 255));
  }

  function check_token(string $personalToken): bool
  {
    global $connect;

    $sql = "SELECT id FROM token WHERE id = '$personalToken'";
    $result = mysqli_query($connect, $sql);
    $resultIsValid = mysqli_fetch_all($result);

    if ( $resultIsValid )
      return true;

    return false;
  }

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
      $personalToken = generate_token($username);
      $id = $result['id'];

      $authenticate = mysqli_query(
        $connect,
        "INSERT INTO token VALUES ('$personalToken', $id)
         ON DUPLICATE KEY UPDATE id = '$personalToken'
        "
      );

      if ( $authenticate )
      {
        $response = array(
          'status' => 200,
          'message' => 'Succesfully Logged In.',
          'username' => $username,
          'password' => sha1($password),
          'date' => date("c", time()),
          'personal_token' => $personalToken,
        );
      }
      else
      {
        $response = array(
          'status' => 500,
          'message' => "Failed to Logged In, Server Internal Failure, Please contact admin...",
        );
      }
    }
    else
    {
      $response = array(
        'status' => 401,
        'message' => "Failed to Log in, please check credentials...",
      );
    }

    header("Content-Type: application/json", true, $response['status']);
    return $response;
  }

  function get_images(string $personalToken, int $sessionID): array
  {
    global $connect;

    if ( check_token($personalToken) )
    {
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
    }
    else
    {
      $response = array(
        'status' => 401,
        'message' => "Invalid Token Verification, Please try again...",
      );
    }

    header("Content-Type: application/json", true, $response['status']);
    return $response;
  }
?>
