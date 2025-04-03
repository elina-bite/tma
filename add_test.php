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

$topic_id = intval($_POST['topic_id']);
$questions = $_POST['questions'];

$sql = "INSERT INTO tests (topic_id, questions) VALUES ($topic_id, '$questions')";

if ($conn->query($sql) === TRUE) {
    echo "<p>Тест успешно добавлен!</p>";
} else {
    echo "<p>Ошибка: " . $sql . "<br>" . $conn->error . "</p>";
}

$conn->close();
?>