<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Вход</h2>

        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Подключение к БД
            $conn = new mysqli("localhost", "root", "Aliy@1994", "edu_site");

            if ($conn->connect_error) {
                die("Ошибка подключения: " . $conn->connect_error);
            }

            // Проверка существования пользователя
            $sql = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = $user['is_admin']; // Сохраняем статус администратора

                    if ($user['is_admin']) {
                        header("Location: admin.php"); // Перенаправление администратора на админ-панель
                    } else {
                        header("Location: profile.php"); // Перенаправление обычного пользователя на профиль
                    }
                    exit();
                } else {
                    echo '<p class="error">Неверный пароль.</p>';
                }
            } else {
                echo '<p class="error">Пользователь с таким email не найден.</p>';
            }
            $conn->close();
        }
        ?>

        <form method="POST" action="" class="login-form">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" required class="form-input">

            <label for="password" class="form-label">Пароль:</label>
            <input type="password" id="password" name="password" required class="form-input">

            <button type="submit" class="btn-primary">Войти</button>
        </form>

        <p class="register-link">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </div>
</body>
</html>