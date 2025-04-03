<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подключение к БД
    $conn = new mysqli("localhost", "root", "", "edu_site");

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
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: profile.php");
            exit();
        } else {
            echo "<p style='color:red;'>Неверный пароль.</p>";
        }
    } else {
        echo "<p style='color:red;'>Пользователь с таким email не найден.</p>";
    }
    $conn->close();
}
?>