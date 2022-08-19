<?php
  require_once "functions.php";

  $fn = $_GET['function'] ?? null;

  $args = array(
    'username' => $_POST['username'] ?? null,
    'password' => $_POST['password'] ?? null,
    'personal_token' => $_POST['personal_token'] ?? null,
  );

  try
  {
    switch ( $fn )
    {
      case "user_login":
        $response = user_login($args['username'], $args['password']);
        break;
      case "get_images":
        $response = get_images($args['personal_token'], 1);
        break;
      default:
        $response = array(
          'status' => 404,
          'message' => 
            "Function Not Found or Undefined, please check the parameter name."
        );

        header("Content-Type: application/json", true, $response['status']);
      break;
    }
  }
  catch ( TypeError )
  {
    $response = array(
      'status' => 400,
      'message' => "Invalid Parameter Format, please check the required parameters.",
    );

    header("Content-Type: application/json", true, $response['status']);
  }
  catch ( mysqli_sql_exception )
  {
    $response = array(
      'status' => 500,
      'message' => "Encountered some serious issues on the server, please contact admin...",
    );

    header("Content-Type: application/json", true, $response['status']);
  }
  finally
  {
    mysqli_close($connect);
  }

  echo json_encode($response);
?>
