<?php
session_start();

/* захардкоженные креды (CTF) */
$LOGIN = 'krutoibanan228';
$PASSWORD = 'superbanana';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($login === $LOGIN && $password === $PASSWORD) {
        $_SESSION['auth'] = true;
        header('Location: terminal.php');
        exit;
    } else {
        $error = 'Invalid login or password';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login | Banana Lab</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo">🍌 Banana Lab</div>
</header>

<div class="container">
  <h2>Login</h2>

  <?php if ($error): ?>
    <p style="color:#ff6b6b"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <input type="text" name="login" placeholder="login" required>
    <input type="password" name="password" placeholder="password" required>
    <button type="submit">Enter</button>
  </form>
</div>

</body>
</html>
