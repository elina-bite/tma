<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Подключение к БД
    $conn = new mysqli("localhost", "root", "", "edu_site");

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Проверка существования email
    $check_email = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check_email->num_rows > 0) {
        echo "<p style='color:red;'>Пользователь с таким email уже зарегистрирован.</p>";
    } else {
        // Регистрация нового пользователя
        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES ('$username', '$email', '$password', 0)";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Вы успешно зарегистрировались!</p>";
            header("Refresh:2; url=auth.php");
        } else {
            echo "<p style='color:red;'>Ошибка: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
    $conn->close();
}
?>