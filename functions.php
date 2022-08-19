<?php
  require_once "config/config.php";
  require_once "helpers/tokenization.php";

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
          'message' => 'Login berhasil.',
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
          'message' => "Login gagal. Gangguan Internal Server, Silahkan hubungi admin...",
        );
      }
    }
    else
    {
      $response = array(
        'status' => 401,
        'message' => "Login gagal. Silahkan periksa kembali data kredensial akun...",
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
        'message' => "Sukses menerima data.",
        'data' => $images,
      );
    }
    else
    {
      $response = array(
        'status' => 401,
        'message' => "Verifikasi token gagal, silahkan coba lagi...",
      );
    }

    header("Content-Type: application/json", true, $response['status']);
    return $response;
  }
?>
