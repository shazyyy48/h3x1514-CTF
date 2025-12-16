<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie CTF Challenge</title>
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
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #00ff88;
        }
        
        h1 {
            color: #00ff88;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
            margin-bottom: 20px;
        }
        
        .cookie-img {
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
            border-radius: 50%;
            border: 3px solid #00ff88;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
        }
        
        .cookie-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
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
        }
        
        .btn:hover {
            background: #00ff88;
            color: #0f2027;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }
        
        .info-box {
            background: rgba(0, 40, 60, 0.7);
            border: 1px solid #0088ff;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #66ccff;
            font-size: 0.9em;
            padding-top: 20px;
            border-top: 1px solid #00ff88;
        }
        
        .glitch {
            animation: glitch 1s linear infinite;
        }
        
        @keyframes glitch {
            2%, 64% {
                transform: translate(2px, 0) skew(0deg);
            }
            4%, 60% {
                transform: translate(-2px, 0) skew(0deg);
            }
            62% {
                transform: translate(0, 0) skew(5deg);
            }
        }
        
        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #00ff88;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            font-family: monospace;
            color: #00ff00;
        }
        
        .blink {
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="cookie-img">
                <!-- Изображение печенья с Википедии -->
                <img src="cookie.png" alt="Cookie">
            </div>
            <h1 class="glitch">🍪Печеньки любят все, да?</h1>
            <p>Измени свою судьбу</p>
        </div>
        
        <div class="menu">
            <a href="register.php" class="btn">📝 Регистрация</a>
            <a href="login.php" class="btn">🔑 Вход</a>
            <?php if(isset($_SESSION['username'])): ?>
                <a href="dashboard.php" class="btn">📊 Дашборд</a>
                <a href="logout.php" class="btn">🚪 Выход</a>
            <?php endif; ?>
        </div>
        
        <div class="terminal">
            <span class="blink">$</span> ./check_access.sh<br>
            > STATUS: ONLINE<br>
            > PORT: 3271<br>
            > AUTH: <?php echo isset($_SESSION['username']) ? 'YES' : 'NO'; ?><br>
            > ACCESS: <?php echo (isset($_COOKIE['Admin']) && $_COOKIE['Admin'] === "True") ? 'ADMIN' : 'USER'; ?>
        </div>
        
        <div class="info-box">
            <p>Зарегистрируйтесь и найдите флаг</p>
            <p>Иногда решение лежит на поверхности...</p>
        </div>
        
        <div class="footer">
            <p>Порт: 3271 | Используй то, что видишь</p>
        </div>
    </div>
</body>
</html>