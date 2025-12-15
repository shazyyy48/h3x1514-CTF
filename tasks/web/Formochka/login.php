<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect("db", "root", "root", "ctf");
if (!$conn) {
    die("DB connection failed");
}

/* ЕСЛИ УЖЕ ЗАЛОГИНЕНЫ — ПОКАЗЫВАЕМ ФЛАГ */
if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    $res = mysqli_query($conn, "SELECT flag FROM flags LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    echo "<h1>Welcome</h1>";
    echo "<pre>{$row['flag']}</pre>";
    exit();
}

/* ЕСЛИ ЭТО POST — ПРОБУЕМ ЛОГИН */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    // НАМЕРЕННО УЯЗВИМЫЙ ЗАПРОС
    $sql = "SELECT * FROM users WHERE login = '$login' OR '1'='1'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['auth'] = true;
        header("Location: login.php");
        exit();
    }

    echo "<script>alert('Invalid credentials'); location.href='index.html';</script>";
    exit();
}

/* ВСЁ ОСТАЛЬНОЕ */
header("Location: index.html");
exit();
