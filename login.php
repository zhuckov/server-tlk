<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";

  $login = $_POST['username'];
  $pass = $_POST['password'];

  $sql_check_login = "SELECT * FROM users WHERE login = ?;";
  $stmt_check_login = $conn->prepare($sql_check_login);
  $stmt_check_login->bind_param("s", $login); 
  $stmt_check_login->execute();
  $result = $stmt_check_login->get_result();

  if($result -> num_rows == 0){
    http_response_code(400);
    echo json_encode(["error" => "Пользователь с таким логином не найден '$login'"]);
  }else { 
    $user = $result->fetch_assoc();
    if ($pass == $user['password']) {
      echo json_encode(["message" => "Пользователь аутентифицирован успешно"]);
    }
    else {
      http_response_code(400);
      echo json_encode(["error" => "Неправильный пароль"]);
  }
}

$stmt_check_login->close();
$conn->close();