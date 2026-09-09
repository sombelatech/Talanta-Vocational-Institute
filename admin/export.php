<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$site_root = realpath(__DIR__ . '/..');
$data_dir = $site_root . '/data';
$images_dir = $site_root . '/images';
$docs_dir = $site_root . '/documents';
$bak_dir = $data_dir . '/backups';
if (!is_dir($bak_dir)) mkdir($bak_dir, 0755, true);

$flash = '';
if (isset($_GET['download'])) {
    $token = $_GET['csrf'] ?? '';
    if (!validate_csrf_token($token)) {
        $flash = 'Invalid session token.';
    } else {
    $zipname = 'site-export-' . date('Ymd-His') . '.zip';
    $zipPath = $bak_dir . '/' . $zipname;
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
        // add data dir
        if (is_dir($data_dir)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($data_dir));
            foreach ($files as $file) {
                if ($file->isDir()) continue;
                $filePath = $file->getRealPath();
                $localPath = 'data/' . substr($filePath, strlen($data_dir)+1);
                $zip->addFile($filePath, $localPath);
            }
        }
        // add images
        if (is_dir($images_dir)) {
            foreach (new DirectoryIterator($images_dir) as $f) {
                if ($f->isDot()) continue;
                $zip->addFile($f->getRealPath(), 'images/' . $f->getFilename());
            }
        }
        // add documents
        if (is_dir($docs_dir)) {
            foreach (new DirectoryIterator($docs_dir) as $f) {
                if ($f->isDot()) continue;
                $zip->addFile($f->getRealPath(), 'documents/' . $f->getFilename());
            }
        }
        $zip->close();
        if (file_exists($zipPath)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipPath) . '"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            exit;
        }
        }
        $flash = 'Failed to create export.';
        }
}

admin_header('Export / Backup');
?>
<div class="admin-topbar">
  <div>
    <h1>Export / Backup</h1>
    <p style="color:var(--text-soft);margin:0;">Create a downloadable ZIP of site data, images and documents.</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?php echo htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <p>Click the button below to create an export ZIP. The file will be placed in <code>data/backups/</code> and offered for download.</p>
    <a href="?download=1&csrf=<?= rawurlencode(get_csrf_token()) ?>" class="btn btn-gold">Create &amp; Download ZIP</a>
</div>

<?php admin_footer();
