<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получаем ID теста из URL
$testId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$testId || empty($_POST['answers'])) {
    header("Location: test.php");
    exit();
}

// Подключение к БД
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Сохраняем результаты теста в базу данных
$user_id = $_SESSION['user_id'];
$score = 0; // Инициализируем счетчик баллов

// Здесь можно реализовать проверку правильности ответов
// Например, если правильные ответы также хранятся в базе данных
// Пока просто увеличиваем счетчик за каждый выбранный ответ
foreach ($_POST['answers'] as $answer) {
    $score++;
}

// Добавляем текущее время как значение для completed_at
$completed_at = date("Y-m-d H:i:s");

// SQL-запрос для вставки результатов теста
$sql = "INSERT INTO test_results (user_id, test_id, score, completed_at) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iids", $user_id, $testId, $score, $completed_at);

if ($stmt->execute()) {
    echo "<p>Тест успешно пройден! Ваш результат: $score баллов.</p>";
} else {
    echo "<p>Ошибка при сохранении результатов теста.</p>";
}

// Возвращаем пользователя к списку тестов
echo '<p><a href="test.php">Вернуться к списку тестов</a></p>';

$stmt->close();
$conn->close();
?>