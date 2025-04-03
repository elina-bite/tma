<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Админ-панель</h2>
        <nav>
            <ul>
                <li><a href="#add-topic">Добавить тему</a></li>
                <li><a href="#add-labwork">Добавить лабораторную работу</a></li>
                <li><a href="#add-test">Добавить тест</a></li>
                <li><a href="#view-results">Просмотр результатов</a></li>
            </ul>
        </nav>

        <!-- Форма добавления темы -->
        <section id="add-topic">
            <h3>Добавить новую тему</h3>
            <form method="POST" action="add_topic.php">
                <label for="title">Название:</label>
                <input type="text" id="title" name="title" required>

                <label for="content">Теория:</label>
                <textarea id="content" name="content" rows="5" required></textarea>

                <button type="submit">Сохранить</button>
            </form>
        </section>

        <!-- Форма добавления лабораторной работы -->
        <section id="add-labwork">
            <h3>Добавить лабораторную работу</h3>
            <form method="POST" action="add_labwork.php">
                <label for="topic_id">Тема:</label>
                <select id="topic_id" name="topic_id" required>
                    <?php
                    // Подключение к БД (замените на свои данные)
                    $conn = new mysqli("localhost", "root", "", "edu_site");

                    if ($conn->connect_error) {
                        die("Ошибка подключения: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM topics";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>

                <label for="description">Описание:</label>
                <textarea id="description" name="description" rows="5" required></textarea>

                <button type="submit">Сохранить</button>
            </form>
        </section>

        <!-- Форма добавления теста -->
        <section id="add-test">
            <h3>Добавить тест</h3>
            <form method="POST" action="add_test.php">
                <label for="topic_id">Тема:</label>
                <select id="topic_id" name="topic_id" required>
                    <?php
                    // Подключение к БД (замените на свои данные)
                    $conn = new mysqli("localhost", "root", "", "edu_site");

                    if ($conn->connect_error) {
                        die("Ошибка подключения: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM topics";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>

                <label for="questions">Вопросы (JSON):</label>
                <textarea id="questions" name="questions" rows="10" required></textarea>

                <button type="submit">Сохранить</button>
            </form>
        </section>

        <!-- Просмотр результатов -->
        <section id="view-results">
            <h3>Результаты тестов</h3>
            <?php
            // Подключение к БД (замените на свои данные)
            $conn = new mysqli("localhost", "root", "", "edu_site");

            if ($conn->connect_error) {
                die("Ошибка подключения: " . $conn->connect_error);
            }

            $sql = "SELECT u.username, t.title, tr.score, tr.timestamp FROM test_results tr JOIN users u ON tr.user_id = u.id JOIN tests t ON tr.test_id = t.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>Пользователь</th><th>Тест</th><th>Баллы</th><th>Дата</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row['username'] . "</td><td>" . $row['title'] . "</td><td>" . $row['score'] . "</td><td>" . $row['timestamp'] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Нет результатов.</p>";
            }
            $conn->close();
            ?>
        </section>
    </div>
</body>
</html>