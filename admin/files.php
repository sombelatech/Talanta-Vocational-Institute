<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$site_root = realpath(__DIR__ . '/..');
$allowed_ext = ['html','php','txt','css','js'];

// scan for editable files in site root (not inside admin, data, images, css, js, documents folders)
$all = [];
foreach (glob($site_root . '/*') as $p) {
    if (is_file($p)) {
        $ext = strtolower(pathinfo($p, PATHINFO_EXTENSION));
        $base = basename($p);
        if (in_array($ext, $allowed_ext) && $base !== 'robots.txt') {
            $all[] = $p;
        }
    }
}

$flash = '';
$current = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['open_file'])) {
    // opening a file is read-only but still validate CSRF
    $token = $_POST['csrf'] ?? '';
    if (!validate_csrf_token($token)) {
      $flash = 'Invalid session token.';
    } else {
        $current = $_POST['open_file'];
        // validate
        $real = realpath($current);
        if ($real && strpos($real, $site_root) === 0) {
            $content = file_get_contents($real);
        } else {
            $flash = 'Invalid file selected.';
            $current = '';
        }
      }
    } elseif (!empty($_POST['save_file'])) {
      $token = $_POST['csrf'] ?? '';
      if (!validate_csrf_token($token)) {
        $flash = 'Invalid session token.';
      } else {
        $current = $_POST['save_file'];
        $body = $_POST['file_content'] ?? '';
        $real = realpath($current);
        if ($real && strpos($real, $site_root) === 0) {
            // backup
            $bakDir = $site_root . '/data/backups';
            if (!is_dir($bakDir)) mkdir($bakDir, 0755, true);
            $bak = $bakDir . '/' . basename($real) . '.bak-' . time();
            copy($real, $bak);
            // save
            file_put_contents($real, $body);
            $flash = 'File saved. Backup created: ' . basename($bak);
            $content = $body;
        } else {
          $flash = 'Cannot save: invalid file.';
          $current = '';
        }
        }
    }
}

admin_header('Files Editor');
?>
<div class="admin-topbar">
  <div>
    <h1>Files Editor</h1>
    <p style="color:var(--text-soft);margin:0;">Open and edit root-level HTML, PHP, CSS and JS files. Backups are created automatically.</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?php echo htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <form method="post">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <label>Select file to open</label>
    <select name="open_file" style="width:100%;padding:10px;border:1px solid var(--line);border-radius:3px">
      <option value="">-- choose file --</option>
      <?php foreach ($all as $p): $bn = basename($p); ?>
        <option value="<?= htmlspecialchars($p) ?>" <?= $p==$current? 'selected':'' ?>><?= htmlspecialchars($bn) ?></option>
      <?php endforeach; ?>
    </select>
    <div style="margin-top:12px;"><button type="submit" class="btn btn-sm">Open File</button></div>
  </form>
</div>

<?php if ($current): ?>
<div class="admin-card">
  <h3 style="margin-top:0;">Editing: <?= htmlspecialchars(basename($current)) ?></h3>
  <form method="post">
    <input type="hidden" name="save_file" value="<?= htmlspecialchars($current) ?>">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <label>Contents</label>
    <textarea name="file_content" style="width:100%;min-height:360px;padding:12px;border:1px solid var(--line);border-radius:4px;font-family:monospace;"><?= htmlspecialchars($content) ?></textarea>
    <div style="margin-top:12px;display:flex;gap:12px;align-items:center;">
      <button type="submit" class="btn btn-gold">Save File</button>
      <a href="files.php" class="btn">Close</a>
    </div>
  </form>
</div>
<?php endif; ?>

<?php admin_footer();
