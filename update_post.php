<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";


  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];

  if (!isset($id) || !isset($title) || !isset($content)) {
    http_response_code(400);
    echo json_encode(["error" => "Отсутствуют необходимые параметры"]);
    exit();
  }

  $update_post_sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
  $stmt_update_post = $conn->prepare($update_post_sql);
  $stmt_update_post->bind_param("ssi", $title, $content, $id);

  if ($stmt_update_post->execute()) {
    echo json_encode(["message" => "Пост успешно обновлен"]);
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Ошибка обновления поста"]);
  }

  $stmt_update_post->close();
  $conn->close();
?>