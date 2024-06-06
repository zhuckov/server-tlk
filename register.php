<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";
  $login = $_POST['username'];
  $pass = $_POST['password'];

  $sql_check_user = "SELECT * FROM users WHERE login = ?;";
  $stmt_check_user = $conn->prepare($sql_check_user);
  $stmt_check_user->bind_param("s", $login); 
  $stmt_check_user->execute();
  $result = $stmt_check_user->get_result();


  if ($result -> num_rows > 0){
    http_response_code(400);
    echo json_encode(["error" => "Пользователь с таким логином уже существует"]);
  }else {
    $sql_insert_user = "INSERT INTO users (login, password) VALUES (?, ?)";
    $stmt_insert_user = $conn->prepare($sql_insert_user);
    $stmt_insert_user-> bind_param("ss", $login, $pass);
  
    if ($stmt_insert_user->execute()) {
      echo json_encode(["message" => "User registered successfully"]);
    } else {
      http_response_code(500); 
      echo json_encode(["error" => "Error: " . $sql_insert_user . "<br>" . $conn->error]);
    }
    $stmt_insert_user->close();
  }

  $stmt_check_user->close();
  $conn->close();
  
