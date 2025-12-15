<?php
session_start();

/* проверка авторизации */
if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}

/* CTF-cookie с частью флага */
setcookie(
    'flag_part',
    'f1nD_4ll_my_',
    time() + 7200,   // 2 час
    '/',             // доступна на всём сайте
    '',              // домен
    false,           // secure (false для http)
    false            // httponly (false, чтобы участники увидели в devtools)
);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Banana Terminal</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="terminal">

<header>
  <div class="logo">🍌 Banana Lab</div>
</header>

<div class="terminal-box">
  <pre id="output">banana@server:$ </pre>

  <div class="cmd-line">
    <input id="cmd" autofocus placeholder="type command">
    <button id="send" type="button">Send</button>
  </div>
</div>

<script src="terminal.js"></script>
</body>
</html>
