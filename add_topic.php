<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

// Подключение к БД (замените на свои данные)
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$title = $_POST['title'];
$content = $_POST['content'];

$sql = "INSERT INTO topics (title, content) VALUES ('$title', '$content')";

if ($conn->query($sql) === TRUE) {
    echo "<p>Тема успешно добавлена!</p>";
} else {
    echo "<p>Ошибка: " . $sql . "<br>" . $conn->error . "</p>";
}

$conn->close();
?>