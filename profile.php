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

$user_id = $_SESSION['user_id'];
$sql = "SELECT t.title AS topic_title, tr.score, tr.timestamp 
        FROM test_results tr 
        JOIN tests te ON tr.test_id = te.id 
        JOIN topics t ON te.topic_id = t.id 
        WHERE tr.user_id=$user_id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="profile-page">
        <!-- Заголовок -->
        <h1>Привет, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <!-- Меню навигации -->
        <nav class="menu">
            <a href="topics.php" class="btn">Список тем</a>
            <a href="labworks.php" class="btn">Лабораторные работы</a>
            <a href="test.php" class="btn">Тесты</a>
            <a href="logout.php" class="btn btn-secondary">Выйти</a>
        </nav>

        <!-- Результаты тестов -->
        <section class="results">
            <h2>Вот ваши результаты тестов:</h2>
            <?php if ($result->num_rows > 0) : ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <li>
                            <strong>Тема:</strong> <?php echo htmlspecialchars($row['topic_title']); ?>, 
                            <strong>Баллы:</strong> <?php echo htmlspecialchars($row['score']); ?>, 
                            <strong>Дата:</strong> <?php echo htmlspecialchars($row['timestamp']); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p>Вы ещё не прошли ни одного теста.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
<?php
$conn->close();
?>