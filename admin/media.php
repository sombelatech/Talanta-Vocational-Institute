<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$site_root = realpath(__DIR__ . '/..');
$images_dir = $site_root . '/images';
$docs_dir = $site_root . '/documents';
$flash = '';

// Simple GD thumbnail helper (best-effort; requires GD extension)
function create_simple_thumbnail($src, $dest, $maxW = 200, $maxH = 140) {
  if (!function_exists('getimagesize')) return false;
  $info = @getimagesize($src);
  if (!$info) return false;
  $w = $info[0]; $h = $info[1]; $mime = $info['mime'];
  $ratio = min($maxW / $w, $maxH / $h, 1);
  $tw = (int)($w * $ratio); $th = (int)($h * $ratio);
  $srcImg = null;
  switch ($mime) {
    case 'image/jpeg': $srcImg = @imagecreatefromjpeg($src); break;
    case 'image/png': $srcImg = @imagecreatefrompng($src); break;
    case 'image/gif': $srcImg = @imagecreatefromgif($src); break;
    case 'image/webp': if (function_exists('imagecreatefromwebp')) $srcImg = @imagecreatefromwebp($src); break;
    default: return false;
  }
  if (!$srcImg) return false;
  $thumb = imagecreatetruecolor($tw, $th);
  // preserve PNG transparency
  if ($mime === 'image/png' || $mime === 'image/webp') {
    imagealphablending($thumb, false);
    imagesavealpha($thumb, true);
    $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
    imagefilledrectangle($thumb, 0, 0, $tw, $th, $transparent);
  }
  imagecopyresampled($thumb, $srcImg, 0,0,0,0, $tw, $th, $w, $h);
  $ok = false;
  $ext = strtolower(pathinfo($dest, PATHINFO_EXTENSION));
  switch ($ext) {
    case 'jpg': case 'jpeg': $ok = imagejpeg($thumb, $dest, 82); break;
    case 'png': $ok = imagepng($thumb, $dest); break;
    case 'gif': $ok = imagegif($thumb, $dest); break;
    case 'webp': if (function_exists('imagewebp')) $ok = imagewebp($thumb, $dest, 80); break;
    default: $ok = imagejpeg($thumb, $dest, 82); break;
  }
  imagedestroy($srcImg);
  imagedestroy($thumb);
  return $ok;
}

// Handle deletions
if (isset($_GET['delete_image'])) {
  $name = basename($_GET['delete_image']);
  $token = $_GET['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid token.';
  } else {
  $path = $images_dir . '/' . $name;
  if (is_file($path)) {
    unlink($path);
    // also delete thumbnail
    $thumb = $images_dir . '/_thumbs/' . $name;
    if (is_file($thumb)) unlink($thumb);
    $flash = 'Deleted image: ' . $name;
    // refresh listing
    $images = is_dir($images_dir) ? array_diff(scandir($images_dir), ['.','..']) : [];
  } else {
    $flash = 'Image not found.';
  }
  }
}
if (isset($_GET['delete_doc'])) {
  $name = basename($_GET['delete_doc']);
  $token = $_GET['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid token.';
  } else {
  $path = $docs_dir . '/' . $name;
  if (is_file($path)) {
    unlink($path);
    $flash = 'Deleted document: ' . $name;
    $docs = is_dir($docs_dir) ? array_diff(scandir($docs_dir), ['.','..']) : [];
  } else {
    $flash = 'Document not found.';
  }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['upload'])) {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
  $target = $_POST['target'] === 'documents' ? $docs_dir : $images_dir;
  if (!is_dir($target)) mkdir($target, 0755, true);
  $file = $_FILES['upload'];
  if ($file['error'] === UPLOAD_ERR_OK) {
    $name = preg_replace('/[^A-Za-z0-9._-]/', '-', basename($file['name']));
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allowed_images = ['jpg','jpeg','png','gif','webp'];
    $allowed_docs = ['pdf','doc','docx','xls','xlsx','ppt','pptx','zip','txt'];
    if ($target === $images_dir && !in_array($ext, $allowed_images)) {
      $flash = 'Disallowed image type.';
    } elseif ($target === $docs_dir && !in_array($ext, $allowed_docs)) {
      $flash = 'Disallowed document type.';
    } else {
      $dest = $target . '/' . $name;
      if (move_uploaded_file($file['tmp_name'], $dest)) {
        $flash = 'Uploaded: ' . $name;
        @chmod($dest, 0644);
        // optional thumbnail for images if GD available
        if ($target === $images_dir && function_exists('gd_info')) {
          $thumbDir = $images_dir . '/_thumbs';
          if (!is_dir($thumbDir)) mkdir($thumbDir, 0755, true);
          $thumbPath = $thumbDir . '/' . $name;
          // create simple thumbnail
          create_simple_thumbnail($dest, $thumbPath, 200, 140);
          @chmod($thumbPath, 0644);
        }
      } else {
        $flash = 'Upload failed.';
      }
    }
  } else {
    $flash = 'Upload error code: ' . $file['error'];
  }
  }
}

// list files (exclude thumbnails folder)
$images = [];
if (is_dir($images_dir)) {
  foreach (scandir($images_dir) as $f) {
    if ($f === '.' || $f === '..' || $f === '_thumbs') continue;
    if (is_file($images_dir . '/' . $f)) $images[] = $f;
  }
}
$docs = [];
if (is_dir($docs_dir)) {
  foreach (scandir($docs_dir) as $f) {
    if ($f === '.' || $f === '..') continue;
    if (is_file($docs_dir . '/' . $f)) $docs[] = $f;
  }
}

admin_header('Media Manager');
?>
<div class="admin-topbar">
  <div>
    <h1>Media Manager</h1>
    <p style="color:var(--text-soft);margin:0;">Upload images and documents for use in galleries, pages and downloads.</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?php echo htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <h3 style="margin-top:0;">Upload File</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <label>Target</label>
    <select name="target" style="padding:8px;border:1px solid var(--line);border-radius:3px;">
      <option value="images">Images (images/)</option>
      <option value="documents">Documents (documents/)</option>
    </select>
    <label style="margin-top:12px;">Choose file</label>
    <input type="file" name="upload" required>
    <div style="margin-top:12px;"><button class="btn btn-gold" type="submit">Upload</button></div>
  </form>
</div>

<div class="admin-card">
  <h3 style="margin-top:0;">Images (images/)</h3>
    <?php if (empty($images)): ?><p style="color:var(--text-soft);">No images yet.</p><?php else: ?>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
      <?php foreach ($images as $f): ?>
        <div style="width:120px;text-align:center;padding:6px;border:1px solid var(--line);border-radius:4px;background:#fff;">
          <?php $thumb = is_file($images_dir . '/_thumbs/' . $f) ? '../images/_thumbs/' . rawurlencode($f) : '../images/' . rawurlencode($f); ?>
          <img src="<?= $thumb ?>" alt="<?= htmlspecialchars($f) ?>" style="max-width:100%;height:70px;object-fit:cover;border-radius:4px;margin-bottom:6px;">
          <div style="font-size:12px;color:var(--text-soft);"><?= htmlspecialchars($f) ?></div>
          <div style="margin-top:6px;">
                  <a href="?delete_image=<?= rawurlencode($f) ?>&csrf=<?= rawurlencode(get_csrf_token()) ?>" onclick="return confirm('Delete this image?');" class="btn btn-sm" style="background:#FDEDEA;color:var(--danger);">Delete</a>
                  <a href="../images/<?= rawurlencode($f) ?>" target="_blank" class="btn btn-sm" style="margin-left:6px;">Open</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="admin-card">
  <h3 style="margin-top:0;">Documents (documents/)</h3>
  <?php if (empty($docs)): ?><p style="color:var(--text-soft);">No documents yet.</p><?php else: ?>
    <ul style="color:var(--text-soft);">
      <?php foreach ($docs as $d): ?>
          <li>
            <a href="../documents/<?= rawurlencode($d) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($d) ?></a>
            <a href="?delete_doc=<?= rawurlencode($d) ?>&csrf=<?= rawurlencode(get_csrf_token()) ?>" onclick="return confirm('Delete this document?');" class="btn btn-sm" style="background:#FDEDEA;color:var(--danger);margin-left:8px;">Delete</a>
          </li>
        <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php admin_footer();
