<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$news = load_json('news.json');
$flash = '';

// Handle delete
if (isset($_GET['delete'])) {
  $token = $_GET['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
  $idx = (int)$_GET['delete'];
  if (isset($news[$idx])) {
    array_splice($news, $idx, 1);
    save_json('news.json', $news);
    $flash = 'News item deleted.';
  }
  }
}

// Handle add/edit form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
    $tag = trim($_POST['tag'] ?? 'NEWS');
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $edit_idx = $_POST['edit_idx'] ?? '';

    if ($title !== '' && $body !== '') {
        $item = ['tag' => $tag, 'title' => $title, 'body' => $body];
        if ($edit_idx !== '' && isset($news[(int)$edit_idx])) {
            $news[(int)$edit_idx] = $item;
            $flash = 'News item updated.';
        } else {
            array_unshift($news, $item);
            $flash = 'News item added.';
        }
        save_json('news.json', $news);
        $news = load_json('news.json');
    }
  }
}

$editing = null;
$editing_idx = null;
if (isset($_GET['edit']) && isset($news[(int)$_GET['edit']])) {
    $editing_idx = (int)$_GET['edit'];
    $editing = $news[$editing_idx];
}

admin_header('News & Events');
?>
<div class="admin-topbar">
  <div>
    <h1>News &amp; Events</h1>
    <p style="color:var(--text-soft);margin:0;">Shown on the homepage (latest 3) and the full News &amp; Events page.</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <h3 style="margin-top:0;"><?= $editing ? 'Edit News Item' : 'Add New News Item' ?></h3>
  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <input type="hidden" name="edit_idx" value="<?= $editing_idx !== null ? $editing_idx : '' ?>">
    <label>Tag (shown as a small label, e.g. NEWS, EVENT, UPDATE)</label>
    <input type="text" name="tag" value="<?= htmlspecialchars($editing['tag'] ?? 'NEWS') ?>" required>
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required>
    <label>Description</label>
    <textarea name="body" required><?= htmlspecialchars($editing['body'] ?? '') ?></textarea>
    <div style="margin-top:16px;">
      <button type="submit" class="btn btn-gold btn-sm"><?= $editing ? 'Save Changes' : 'Add News Item' ?></button>
      <?php if ($editing): ?><a href="news.php" class="btn btn-sm" style="background:var(--steel-2);color:var(--ink);">Cancel</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <h3 style="margin-top:0;">Current News Items</h3>
  <table class="admin-table">
    <tr><th>Tag</th><th>Title</th><th>Description</th><th></th></tr>
    <?php foreach ($news as $i => $item): ?>
    <tr>
      <td><span class="icon-btn" style="background:var(--steel-2);color:var(--ink);"><?= htmlspecialchars($item['tag']) ?></span></td>
      <td style="font-weight:600;"><?= htmlspecialchars($item['title']) ?></td>
      <td style="color:var(--text-soft);"><?= htmlspecialchars($item['body']) ?></td>
      <td style="white-space:nowrap;">
        <a href="?edit=<?= $i ?>" class="icon-btn" style="background:var(--steel-2);color:var(--ink);">Edit</a>
        <a href="?delete=<?= $i ?>&csrf=<?= rawurlencode(get_csrf_token()) ?>" onclick="return confirm('Delete this news item?');" class="icon-btn" style="background:#FDEDEA;color:var(--danger);">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($news)): ?><tr><td colspan="4" style="color:var(--text-soft);">No news items yet.</td></tr><?php endif; ?>
  </table>
</div>
<?php
admin_footer();
