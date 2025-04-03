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

$sql = "SELECT l.id, l.description, t.title AS topic_title 
        FROM labworks l 
        JOIN topics t ON l.topic_id = t.id";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторные работы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Лабораторные работы</h2>
        <?php if ($result->num_rows > 0) : ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <li>
                        <strong>Тема:</strong> <?php echo htmlspecialchars($row['topic_title']); ?><br>
                        <a href="labwork.php?id=<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p>Нет доступных лабораторных работ.</p>
        <?php endif; ?>
        <p><a href="profile.php">Вернуться в профиль</a></p>
    </div>
</body>
</html>
<?php
$conn->close();
?>