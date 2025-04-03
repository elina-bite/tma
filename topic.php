<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверяем, передан ли параметр id в URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: topics.php");
    exit();
}

// Получаем ID темы из URL
$topicId = intval($_GET['id']);

// Подключение к БД
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Запрос для получения информации о теме по ID
$sql = "SELECT * FROM topics WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $topicId);
$stmt->execute();
$result = $stmt->get_result();

// Проверяем, найдена ли тема
if ($result->num_rows === 0) {
    echo "<p>Тема не найдена.</p>";
} else {
    // Получаем данные темы
    $topic = $result->fetch_assoc();

    // Отображаем содержимое темы
    echo '<div class="topic-page">';
    echo '<h2>' . htmlspecialchars($topic['title']) . '</h2>';
    echo '<div class="content">' . nl2br(htmlspecialchars($topic['content'])) . '</div>';
    echo '<div class="actions"><a href="topics.php" class="btn">Вернуться к списку тем</a></div>';
    echo '</div>';
}

// Закрываем соединение с БД
$stmt->close();
$conn->close();
?>