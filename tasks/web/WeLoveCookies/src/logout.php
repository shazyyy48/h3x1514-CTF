<?php
session_start();

// Удаляем все куки
foreach($_COOKIE as $key => $value) {
    setcookie($key, '', time() - 3600, "/");
}

// Уничтожаем сессию
session_destroy();

header("Location: index.php");
exit();
?>