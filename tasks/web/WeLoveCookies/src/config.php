<?php
$host = getenv('MYSQL_HOST') ?: 'db';
$username = getenv('MYSQL_USER') ?: 'ctf_user';
$password = getenv('MYSQL_PASSWORD') ?: 'ctf_password';
$database = getenv('MYSQL_DATABASE') ?: 'ctf_db';

// Создаем соединение
$conn = new mysqli($host, $username, $password, $database);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>