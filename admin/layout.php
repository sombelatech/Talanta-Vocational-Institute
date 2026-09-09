<?php
function admin_header($title) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> | Talanta Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
<style>
  body{background:var(--steel);}
  .admin-shell{display:flex;min-height:100vh;}
  .admin-side{width:220px;background:var(--ink);color:#fff;padding:24px 0;flex-shrink:0;}
  .admin-side .brand{padding:0 20px 20px;border-bottom:1px solid #23405E;margin-bottom:16px;}
  .admin-side .brand img{height:36px;}
  .admin-side a{display:block;padding:11px 20px;color:#B8C6D6;font-size:14.5px;font-weight:600;}
  .admin-side a:hover, .admin-side a.active{background:rgba(255,255,255,.08);color:#fff;border-left:3px solid var(--gold);}
  .admin-main{flex:1;padding:32px 40px;}
  .admin-main h1{font-size:26px;margin-bottom:4px;}
  .admin-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;}
  .admin-card{background:#fff;border:1px solid var(--line);border-radius:4px;padding:24px;margin-bottom:20px;}
  .flash{background:#E4F5EA;color:var(--circuit);padding:10px 16px;border-radius:3px;margin-bottom:20px;font-size:14px;}
  table.admin-table{width:100%;border-collapse:collapse;}
  table.admin-table th{text-align:left;font-family:var(--font-mono);font-size:11.5px;text-transform:uppercase;color:var(--text-soft);padding:10px 8px;border-bottom:2px solid var(--line);}
  table.admin-table td{padding:12px 8px;border-bottom:1px solid var(--line);font-size:14px;vertical-align:top;}
  .admin-form label{display:block;font-weight:600;font-size:13.5px;margin:14px 0 6px;color:var(--ink);}
  .admin-form input[type=text], .admin-form input[type=email], .admin-form input[type=password], .admin-form input[type=date], .admin-form input[type=file], .admin-form textarea{
    width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:3px;font-size:14.5px;font-family:var(--font-body);
  }
  .admin-form textarea{min-height:80px;}
  .admin-form input[type=file]{padding:8px 10px;background:#fff;}
  .btn-danger{background:var(--danger);color:#fff;}
  .btn-danger:hover{opacity:.9;}
  .icon-btn{font-family:var(--font-mono);font-size:12px;font-weight:700;padding:6px 12px;border-radius:20px;display:inline-block;}
</style>
</head>
<body>
<div class="admin-shell">
  <div class="admin-side">
    <div class="brand"><img src="../images/talanta-logo.png" alt="Talanta"></div>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">Dashboard</a>
    <a href="news.php" class="<?= basename($_SERVER['PHP_SELF'])=='news.php'?'active':'' ?>">News &amp; Events</a>
    <a href="contact.php" class="<?= basename($_SERVER['PHP_SELF'])=='contact.php'?'active':'' ?>">Contact Info</a>
    <a href="files.php" class="<?= basename($_SERVER['PHP_SELF'])=='files.php'?'active':'' ?>">Files Editor</a>
    <a href="media.php" class="<?= basename($_SERVER['PHP_SELF'])=='media.php'?'active':'' ?>">Media Manager</a>
    <a href="change_password.php" class="<?= basename($_SERVER['PHP_SELF'])=='change_password.php'?'active':'' ?>">Change Password</a>
    <a href="export.php" class="<?= basename($_SERVER['PHP_SELF'])=='export.php'?'active':'' ?>">Export / Backup</a>
    <a href="../index.php" target="_blank" style="margin-top:20px;border-top:1px solid #23405E;padding-top:20px;">View Live Site ↗</a>
    <a href="logout.php">Log Out</a>
  </div>
  <div class="admin-main">
<?php
}

function admin_footer() {
?>
  </div>
</div>
</body>
</html>
<?php
}
