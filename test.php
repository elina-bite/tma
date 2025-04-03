<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Подключение к БД
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT t.id, t.title AS test_title, tt.title AS topic_title 
        FROM tests t 
        JOIN topics tt ON t.topic_id = tt.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тесты</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Тесты</h2>

        <?php if ($result->num_rows > 0) : ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <li>
                        <!-- Ссылка на страницу с вопросами теста -->
                        <a href="test_questions.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                            <strong>Тема:</strong> <?php echo htmlspecialchars($row['topic_title']); ?> <br>
                            <strong>Тест:</strong> <?php echo htmlspecialchars($row['test_title']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p>Нет доступных тестов.</p>
        <?php endif; ?>

        <!-- Кнопка возврата -->
        <p><a href="profile.php">Вернуться в профиль</a></p>
    </div>
</body>
</html>
<?php
$conn->close();
?>