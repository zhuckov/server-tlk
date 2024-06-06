<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";

 
  $id = $_POST['id'];
  


  if (!isset($id)) {
    http_response_code(400);
    echo json_encode(["error" => "ID поста не указан"]);
    exit();
  }

  $remove_post_sql = "DELETE FROM posts WHERE id = ?";
  $stmt_remove_post = $conn->prepare($remove_post_sql);
  $stmt_remove_post -> bind_param("i", $id);
  if ($stmt_remove_post->execute()) {
    echo json_encode(["message" => "Пост успешно удален"]);
  }else {
    http_response_code(500);
    echo json_encode(["error" => "Ошибка при удалении поста: " . $stmt_remove_post->error]);
  }
  $stmt_remove_post->close();
  $conn->close();


