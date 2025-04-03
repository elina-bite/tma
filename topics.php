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

// Запрос для получения списка тем
$sql = "SELECT * FROM topics";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список тем</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Главная обертка -->
    <div class="topics-page">
	  <!-- Кнопка для возврата в профиль -->
        <p><a href="profile.php">Вернуться в профиль</a></p>
        <!-- Заголовок страницы -->
        <h2>Список тем</h2>

        <!-- Проверяем, есть ли темы -->
        <?php if ($result->num_rows > 0) : ?>
            <!-- Список тем -->
            <div class="topics-list">
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="topic-card">
                        <a href="topic.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <!-- Сообщение, если тем нет -->
            <p>Нет доступных тем.</p>
        <?php endif; ?>

      
    </div>
</body>
</html>
<?php
$conn->close();
?>