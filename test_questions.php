<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получаем ID теста из URL
$testId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$testId) {
    header("Location: test.php");
    exit();
}

// Подключение к БД
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Запрос для получения информации о тесте
$sql = "SELECT t.id, t.questions, t.title, tt.title AS topic_title 
        FROM tests t 
        JOIN topics tt ON t.topic_id = tt.id 
        WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $testId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Тест не найден.</p>";
    exit();
}

$test = $result->fetch_assoc();
$questions = json_decode($test['questions'], true); // Декодируем JSON-вопросы

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($test['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($test['title']); ?></h2>
        <p><strong>Тема:</strong> <?php echo htmlspecialchars($test['topic_title']); ?></p>

        <!-- Форма для прохождения теста -->
        <form method="POST" action="submit_test.php?id=<?php echo htmlspecialchars($test['id']); ?>">
            <?php foreach ($questions as $index => $question) : ?>
                <div class="question-block">
                    <h3>Вопрос <?php echo $index + 1; ?>:</h3>
                    <p><?php echo htmlspecialchars($question['text']); ?></p>

                    <!-- Варианты ответов (предполагается, что они хранятся в базе данных или добавляются позже) -->
                    <label>
                        <input type="radio" name="answers[<?php echo $index; ?>]" value="1" required> Ответ 1
                    </label><br>
                    <label>
                        <input type="radio" name="answers[<?php echo $index; ?>]" value="2" required> Ответ 2
                    </label><br>
                    <label>
                        <input type="radio" name="answers[<?php echo $index; ?>]" value="3" required> Ответ 3
                    </label><br>
                    <label>
                        <input type="radio" name="answers[<?php echo $index; ?>]" value="4" required> Ответ 4
                    </label><br>
                </div>
            <?php endforeach; ?>

            <!-- Кнопка отправки результатов -->
            <button type="submit" class="btn-primary">Пройти тест</button>
        </form>

        <!-- Кнопка возврата -->
        <p><a href="test.php">Вернуться к списку тестов</a></p>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>