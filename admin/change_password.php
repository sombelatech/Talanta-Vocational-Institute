<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$flash = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $error = 'Invalid session token.';
  } else {
  $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!password_verify($current, get_admin_password_hash())) {
        $error = 'Current password is incorrect.';
    } elseif ($new === '' || strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        if (change_admin_password_hash($hash)) {
            // force logout to require re-login with new password
            session_unset();
            session_destroy();
            header('Location: login.php?changed=1');
            exit;
        } else {
            $error = 'Failed to save new password.';
        }
    }
  }
}

admin_header('Change Password');
?>
<div class="admin-topbar">
  <div>
    <h1>Change Admin Password</h1>
    <p style="color:var(--text-soft);margin:0;">Change the admin password used to log in to this panel.</p>
  </div>
</div>

<?php if ($error): ?><div class="flash" style="background:#FDEDEA;color:var(--danger);"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="admin-card">
  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <label>Current Password</label>
    <input type="password" name="current_password" required>
    <label>New Password</label>
    <input type="password" name="new_password" required>
    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" required>
    <div style="margin-top:12px;">
      <button class="btn btn-gold" type="submit">Change Password</button>
    </div>
  </form>
</div>

<?php admin_footer();
