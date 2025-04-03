<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Основной контейнер -->
    <div class="container">
        <h2 class="register-title">Регистрация</h2>

        <!-- Изображение -->
        <img src="images/reg.jpg" alt="Изображение регистрации" class="reg-image">

        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Подключение к БД
            $conn = new mysqli("localhost", "root", "", "edu_site");

            if ($conn->connect_error) {
                die("Ошибка подключения: " . $conn->connect_error);
            }

            // Проверка существования пользователя
            $sql = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<p class="error">Пользователь с таким email уже зарегистрирован.</p>';
            } else {
                // Регистрация нового пользователя
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
                if ($conn->query($sql) === TRUE) {
                    echo '<p class="success">Вы успешно зарегистрировались!</p>';
                } else {
                    echo '<p class="error">Ошибка: ' . $sql . '<br>' . $conn->error . '</p>';
                }
            }
            $conn->close();
        }
        ?>

        <!-- Форма регистрации -->
        <form method="POST" action="" class="register-form">
            <label for="username" class="form-label">Имя:</label>
            <input type="text" id="username" name="username" required class="form-input">

            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" required class="form-input">

            <label for="password" class="form-label">Пароль:</label>
            <input type="password" id="password" name="password" required class="form-input">

            <button type="submit" class="btn-primary">Зарегистрироваться</button>
        </form>

        <!-- Ссылка на вход -->
        <p class="login-link">Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>