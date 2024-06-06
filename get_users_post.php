<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";

  $author_login = $_POST["author_login"];

  $sql_get_user_posts = "SELECT * FROM posts WHERE author_login = ?";
  $stmt = $conn->prepare($sql_get_user_posts);
  $stmt->bind_param("s", $author_login);
  $stmt->execute();
  $result = $stmt -> get_result();

  $posts = [];
  while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
  }
  $response = [
    "posts" => $posts
  ];
  
  $stmt->close();
  $conn->close();

  echo json_encode($response);