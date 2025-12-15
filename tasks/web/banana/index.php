<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Banana Lab</title>
<link rel="stylesheet" href="style.css">
</head>
<body>


<header>
<div class="logo">🍌 Banana Lab</div>
<?php if (!isset($_SESSION['auth'])): ?>
<a class="btn" href="login.php">Login</a>
<?php else: ?>
<a class="btn" href="terminal.php">Terminal</a>
<?php endif; ?>
</header>


<!-- login: krutoibanan228 -->
<!-- password: superbanana -->


<div class="container">
<h1>Welcome to Banana Lab</h1>
<img src="https://upload.wikimedia.org/wikipedia/commons/8/8a/Banana-Single.jpg" width="300">
</div>


</body>
</html>