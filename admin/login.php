<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
$info = '';
if (!empty($_GET['changed'])) {
  $info = 'Password changed. Please log in with your new password.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (password_verify($password, get_admin_password_hash())) {
        $_SESSION['talanta_admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Incorrect password. Please try again.';
    }
}
if (!empty($_SESSION['talanta_admin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Talanta Vocational Institute</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
<style>
  body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--steel);}
  .login-box{background:#fff;border:1px solid var(--line);border-radius:4px;padding:40px;max-width:380px;width:100%;box-shadow:var(--shadow-md);}
  .login-box h1{font-size:22px;margin-bottom:4px;}
  .login-box p.sub{color:var(--text-soft);font-size:14px;margin-bottom:24px;}
  .login-box input[type=password]{width:100%;padding:12px;border:1px solid var(--line);border-radius:3px;font-size:15px;margin-bottom:16px;}
  .login-box button{width:100%;}
  .err{background:#FDEDEA;color:var(--danger);padding:10px 14px;border-radius:3px;font-size:13.5px;margin-bottom:16px;}
</style>
</head>
<body>
  <div class="login-box">
    <img src="../images/talanta-logo.png" alt="Talanta" style="height:48px;margin-bottom:16px;">
    <h1>Admin Login</h1>
    <p class="sub">Talanta Vocational Institute — Website Manager</p>
    <?php if ($info): ?><div class="flash" style="background:#E4F5EA;color:var(--circuit);padding:10px;border-radius:3px;margin-bottom:12px"><?= htmlspecialchars($info) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <input type="password" name="password" placeholder="Enter admin password" required autofocus>
      <button type="submit" class="btn btn-gold">Log In</button>
    </form>
  </div>
</body>
</html>
