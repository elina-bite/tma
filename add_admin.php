<?php
// Подключение к БД
$conn = new mysqli("localhost", "root", "", "edu_site");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Данные администратора
$username = 'admin';
$email = 'admin@example.com';
$password = password_hash('adminpassword', PASSWORD_BCRYPT); // Хешируем пароль с использованием password_hash()
$is_admin = 1;

// SQL-запрос для добавления администратора
$sql = "INSERT INTO users (username, email, password, is_admin) 
        VALUES ('$username', '$email', '$password', $is_admin)";

if ($conn->query($sql) === TRUE) {
    echo "Администратор успешно создан!";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>