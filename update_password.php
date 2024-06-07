<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require "./db/connect.php";

$login = $_POST['login'];
$password = $_POST['password'];

if (!isset($login) || !isset($password)) {
  http_response_code(400);
  echo json_encode(["error" => "Отсутствует логин или новый пароль"]);
  exit();
}
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$update_password_sql = "UPDATE users SET password = ? WHERE login = ?";
$stmt_update_password = $conn->prepare($update_password_sql);
$stmt_update_password->bind_param("ss", $hashedPassword, $login);

if ($stmt_update_password->execute()) {
  echo json_encode(["message" => "Пароль успешно обновлен"]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Ошибка при обновлении пароля"]);
}

$stmt_update_password->close();
$conn->close();