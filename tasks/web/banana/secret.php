<?php
session_start();
if (!isset($_SESSION['auth'])) {
header('Location: login.php');
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Secret</title>
<link rel="stylesheet" href="style.css">
</head>
<body>


<header>
<div class="logo">🍌 Banana Lab</div>
</header>


<div class="container">
<h1>🍌 Banana Secret 🍌</h1>
<p>Last part:</p>
<code>s3cr3ts}</code>
</div>


</body>
</html>