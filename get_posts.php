<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");

  require "./db/connect.php";

  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

  $totalPostsQuery = "SELECT COUNT(*) AS total FROM posts";
  $totalPostsResult = $conn->query($totalPostsQuery);
  $totalPostsRow = $totalPostsResult->fetch_assoc();
  $totalPosts = (int)$totalPostsRow['total'];

  $offset = ($page - 1) * $limit;
  $sql_get_posts = "SELECT id, title, author_login, created_at, content FROM posts LIMIT ? OFFSET ?";
  $stmt = $conn->prepare($sql_get_posts);
  $stmt->bind_param("ii", $limit, $offset);
  $stmt->execute();
  $result = $stmt -> get_result();

  $posts = [];
  while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
  }
  $response = [
    "totalPosts" => $totalPosts,
    "posts" => $posts
  ];
  
  $stmt->close();
  $conn->close();

  echo json_encode($response);