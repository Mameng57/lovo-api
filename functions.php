<?php
  require_once "config/config.php";
  require_once "helpers/tokenization.php";

  function user_register(
    string $name,
    string $phone,
    string $email = null,
    string $address,
    string $password
  ): array
  {
    global $connect;

    $sql = (
      "INSERT INTO user (name, phone, email, address, password)
       VALUES ('$name', '$phone', '$email', '$address', '$password')"
    );
    $query = mysqli_query($connect, $sql);

    if ( $query )
      $response = array(
        'status' => 200,
        'message' => 'Daftar akun berhasil.',
      );

    $response = array(
      'status' => 400,
      'message' => 'Daftar akun gagal, periksa kembali format dan data yang dimasukan.',
    );

    header("Content-Type: application/json", true, $response['status']);
    return $response;
  }

  function user_login(string $username, string $password): array
  {
    global $connect;

    $sql = (
      "SELECT * FROM user WHERE phone = '$username' OR email = '$username'"
    );
    $query = mysqli_query($connect, $sql);
    $result = mysqli_fetch_assoc($query);

    if (
      ($result['phone'] == $username || $result['email'] == $username)
      && $result['password'] == sha1($password)
    )
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
        $images[] = array('id' => (int) $row['id'], 'url' => $imageViewPath . $row['id']);
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

  function mark_images(string $jsonString): array
  {
    global $connect;

    $data = json_decode($jsonString, true);
    $isQueryFailed = false;

    foreach ($data as $imageID => $detail)
    {
      $sql = "INSERT INTO marked (details, id_image) VALUES('$detail', $imageID)";
      $query = mysqli_query($connect, $sql);

      if ( !$query )
        $isQueryFailed = true;
    }

    if ( $isQueryFailed )
      $response = array(
        'status' => 400,
        'message' => "Satu atau lebih gambar gagal untuk ditandai, silahkan coba lagi...",
      );

    $response = array(
      'status' => 200,
      'message' => "Gambar berhasil ditandai.",
    );

    header("Content-Type: application/json", true, $response['status']);
    return $response;
  }
?>
