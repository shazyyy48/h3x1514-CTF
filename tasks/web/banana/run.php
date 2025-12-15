<?php
session_start();
header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo json_encode(['out' => 'forbidden']);
    exit;
}

// Флаг (разбит на части)
$FLAG1 = "h3x1514{Y0u_";

// Получаем команду
$data = json_decode(file_get_contents('php://input'), true);
$cmd = trim($data['cmd'] ?? '');

// Ответ по команде
$out = '';
switch ($cmd) {
    case 'ls':
        $out = "flag.txt\nnotes.txt\nsecret.php";
        break;
    case 'whoami':
        $out = "banana-user";
        break;
    case '--help':
        $out = 'говорят, что банан очень любит печенье, интересно, где оно лежит?';
        break;
    case 'cat notes.txt':
    case 'nano notes.txt':
    case 'echo notes.txt':
        $out = 'сколько же работы проделал этот банан!';
        break;
    case 'cat flag.txt':
    case 'nano flag.txt':
    case 'echo flag.txt':
        $out = $FLAG1 ;
        break;
    default:
        $out = $cmd . ": не найдено, напишите '--help' \nдля вывода списка команд";
}

// Возвращаем JSON
echo json_encode(['out' => $out]);
