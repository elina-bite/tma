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
$description = $_POST['description'];

$sql = "INSERT INTO labworks (topic_id, description) VALUES ($topic_id, '$description')";

if ($conn->query($sql) === TRUE) {
    echo "<p>Лабораторная работа успешно добавлена!</p>";
} else {
    echo "<p>Ошибка: " . $sql . "<br>" . $conn->error . "</p>";
}

$conn->close();
?>