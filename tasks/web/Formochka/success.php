<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

echo "<!-- Debug: Session data: " . print_r($_SESSION, true) . " -->\n";

$conn = mysqli_connect("db", "root", "root", "ctf");
if (!$conn) {
    die("DB connection failed");
}

/* ПРОВЕРЯЕМ АВТОРИЗАЦИЮ */
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo "<!-- Debug: Not authorized, redirecting to index -->\n";
    header("Location: index.html");
    exit();
}

echo "<!-- Debug: User authorized as: " . ($_SESSION['login'] ?? 'unknown') . " -->\n";

/* ПОЛУЧАЕМ ФЛАГ */
$res = mysqli_query($conn, "SELECT flag FROM flags LIMIT 1");
$row = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Успешный вход | CTF</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .success-box {
            background: rgba(0, 0, 0, 0.65);
            padding: 40px;
            width: 450px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.6);
            text-align: center;
        }

        .success-box h1 {
            margin-bottom: 10px;
            font-size: 26px;
            letter-spacing: 1px;
            color: #00c6ff;
        }

        .success-box p {
            margin-bottom: 20px;
            font-size: 14px;
            color: #bbb;
        }

        .flag-container {
            background: rgba(30, 30, 30, 0.8);
            border: 2px solid #00c6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 0 15px rgba(0, 198, 255, 0.3);
        }

        .flag-label {
            font-size: 14px;
            color: #999;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .flag {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            color: #00ff88;
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 6px;
            word-break: break-all;
            border: 1px solid #444;
        }

        .user-info {
            font-size: 14px;
            color: #aaa;
            margin: 15px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-logout {
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
        }

        .btn-again {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .congrats-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <div class="congrats-icon">🎉</div>
        <h1>Поздравляем!</h1>
        <p>Вы успешно эксплуатировали уязвимость SQL-инъекции!</p>
        
        <div class="user-info">
            Вошли как: <strong><?php echo htmlspecialchars($_SESSION['login'] ?? 'гость'); ?></strong>
        </div>
        
        <div class="flag-container">
            <div class="flag-label">Ваш флаг:</div>
            <div class="flag"><?php echo htmlspecialchars($row['flag'] ?? 'FLAG_NOT_FOUND'); ?></div>
            <p style="font-size: 12px; color: #777; margin-top: 10px;">
                Сохраните этот флаг для завершения задания
            </p>
        </div>
        
        <div class="buttons">
            <a href="logout.php" class="btn btn-logout">Выйти</a>
            <a href="index.html" class="btn btn-again">Попробовать снова</a>
        </div>
    </div>
</body>
</html>