<?php
session_start();

// Только для авторизованных пользователей
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$flag = "h3x1514{c00k13s_for3v3r_1n_my_h34rt}";
$show_flag = false;

// Проверяем куку Admin (УЯЗВИМОСТЬ!)
if(isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === "True") {
    $show_flag = true;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дашборд - CTF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #00ff88;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(0, 20, 30, 0.9);
            border: 2px solid #00ff88;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #00ff88;
        }
        
        h1 {
            color: #00ff88;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }
        
        .user-info {
            color: #66ffb3;
            font-size: 1.1em;
        }
        
        .message-box {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid #ff4444;
            border-radius: 10px;
            font-size: 1.5em;
        }
        
        .flag-box {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: rgba(0, 255, 0, 0.1);
            border: 2px solid #00ff88;
            border-radius: 10px;
        }
        
        .flag {
            color: gold;
            font-size: 2em;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            margin: 20px 0;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            font-family: monospace;
            letter-spacing: 2px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: transparent;
            color: #00ff88;
            border: 2px solid #00ff88;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            margin: 10px 5px;
        }
        
        .btn:hover {
            background: #00ff88;
            color: #0f2027;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }
        
        .center {
            text-align: center;
            margin-top: 30px;
        }
        
        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #00ff88;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            font-family: monospace;
            color: #00ff00;
            font-size: 0.9em;
        }
        
        .blink {
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        
        .scan-line {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(0, 255, 136, 0.5);
            animation: scan 3s linear infinite;
            pointer-events: none;
        }
        
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
    </style>
</head>
<body>
    <div class="scan-line"></div>
    
    <div class="container">
        <div class="header">
            <h1>📊 Дашборд</h1>
            <div class="user-info">
                Пользователь: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </div>
        </div>
        
        <div class="terminal">
            <span class="blink">$</span> check_permissions --user="<?php echo htmlspecialchars($_SESSION['username']); ?>"<br>
            > USER: <?php echo htmlspecialchars($_SESSION['username']); ?><br>
            > ROLE: <?php echo (isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === "True") ? 'ADMINISTRATOR' : 'USER'; ?><br>
            > FLAG_ACCESS: <?php echo (isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === "True") ? 'GRANTED' : 'DENIED'; ?><br>
            > SESSION: ACTIVE
        </div>
        
        <?php if($show_flag): ?>
            <div class="flag-box">
                <h2>🏆 ДОСТУП РАЗРЕШЕН</h2>
                <div class="flag"><?php echo $flag; ?></div>
                <p>Уязвимость эксплуатирована успешно!</p>
            </div>
        <?php else: ?>
            <div class="message-box">
                ❌ ДЛЯ ТЕБЯ ФЛАГА НЕТУ
            </div>
        <?php endif; ?>
        
        <div class="center">
            <a href="index.php" class="btn">⌂ На главную</a>
            <a href="logout.php" class="btn">🚪 Выход</a>
        </div>
    </div>
</body>

</html>
