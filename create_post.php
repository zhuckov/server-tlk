<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";

  $title = $_POST['title'];
  $content = $_POST['content'];
  $author_login = $_POST['author_login'];

  $sql_check_author = "SELECT * FROM users WHERE login = ?";
  $stmt_check_author = $conn->prepare($sql_check_author);
  $stmt_check_author->bind_param("s", $author_login);
  $stmt_check_author->execute();
  $result = $stmt_check_author->get_result();
  if ($result->num_rows == 0) {
    http_response_code(400);
    echo json_encode(["error" => "Автор с таким логином не найден"]);
  } else {
    $sql_insert_post = "INSERT INTO posts (title, author_login, content) VALUES (?, ?, ?)";
    $stmt_insert_post = $conn->prepare($sql_insert_post);
    $stmt_insert_post->bind_param("sss", $title, $author_login, $content);

    if ($stmt_insert_post->execute()) {
        echo json_encode(["message" => "Пост успешно создан"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Ошибка при создании поста: " . $conn->error]);
    }

    $stmt_insert_post->close();
}

$stmt_check_author->close();
$conn->close();