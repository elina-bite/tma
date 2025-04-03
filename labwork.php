<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Подключение к БД
$conn = new mysqli("localhost", "root", "Aliy@1994", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение ID темы из URL
$topicId = isset($_GET['id']) ? intval($_GET['id']) : null;

// Запрос для получения информации о лабораторной работе
$sql = "SELECT * FROM labworks WHERE topic_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $topicId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Лабораторная работа</h2>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Лабораторная работа пока недоступна.</p>
        <?php endif; ?>

        <!-- Кнопка возврата -->
        <a href="topics.php">Вернуться к списку тем</a>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>