<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (strlen($username) < 3 || strlen($password) < 3) {
        $error = "Логин и пароль должны быть не менее 3 символов";
    } else {
        // Устанавливаем куки
        setcookie("User", $username, time() + 3600, "/");
        setcookie("Pass", md5($password), time() + 3600, "/");
        setcookie("Admin", "False", time() + 3600, "/");
        setcookie("Role", "user", time() + 3600, "/");
        
        $_SESSION['username'] = $username;
        $_SESSION['registered'] = true;
        
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - CTF</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: rgba(0, 20, 30, 0.9);
            border: 2px solid #00ff88;
            border-radius: 10px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #66ffb3;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            background: rgba(0, 40, 60, 0.7);
            border: 1px solid #0088ff;
            border-radius: 5px;
            color: #00ff88;
            font-family: monospace;
            font-size: 16px;
        }
        
        input:focus {
            outline: none;
            border-color: #00ff88;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            background: transparent;
            color: #00ff88;
            border: 2px solid #00ff88;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: #00ff88;
            color: #0f2027;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
        }
        
        .error {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff4444;
            color: #ff4444;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .link {
            text-align: center;
            margin-top: 20px;
        }
        
        .link a {
            color: #66ccff;
            text-decoration: none;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
        
        .terminal {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid #00ff88;
            border-radius: 5px;
            padding: 10px;
            margin: 20px 0;
            font-family: monospace;
            color: #00ff00;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Регистрация</h1>
        
        <?php if(isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="terminal">
            <span>></span> Создание нового пользователя...<br>
            <span>></span> Проверка параметров...
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" required minlength="3">
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required minlength="3">
            </div>
            
            <button type="submit" class="btn">Создать аккаунт</button>
        </form>
        
        <div class="link">
            <a href="index.php">← На главную</a>
        </div>
    </div>
</body>
</html>