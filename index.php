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
            "Fungsi yang dimaksud tidak ada. Silahkan periksa kembali penggunaan nama fungsi..."
        );

        header("Content-Type: application/json", true, $response['status']);
      break;
    }
  }
  catch ( TypeError )
  {
    $response = array(
      'status' => 400,
      'message' => "Format parameter salah. Silahkan periksa field parameter yang diperlukan...",
    );

    header("Content-Type: application/json", true, $response['status']);
  }
  catch ( mysqli_sql_exception )
  {
    $response = array(
      'status' => 500,
      'message' => "Fatal Error. Terdapat masalah pada sisi server, silahkan hubungi admin...",
    );

    header("Content-Type: application/json", true, $response['status']);
  }
  finally
  {
    mysqli_close($connect);
  }

  echo json_encode($response);
?>
