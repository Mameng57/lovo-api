<?php
  require_once ($_SERVER['DOCUMENT_ROOT'] . "/lovo-api/config/config.php");

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
?>
