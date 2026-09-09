<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$news = load_json('news.json');
$contact = load_json('contact.json');
$applications = load_json('applications.json');

admin_header('Dashboard');
?>
<div class="admin-topbar">
  <div>
    <h1>Welcome back</h1>
    <p style="color:var(--text-soft);margin:0;">Manage the Talanta Vocational Institute website.</p>
  </div>
</div>
<div class="admin-card">
  <h3 style="margin-top:0;">Server Diagnostics</h3>
  <table class="admin-table">
    <tr><th>Setting</th><th>Value</th></tr>
    <tr><td>PHP Version</td><td><?= phpversion() ?></td></tr>
    <tr><td>GD Installed</td><td><?= function_exists('gd_info') ? 'Yes' : 'No' ?></td></tr>
    <tr><td>ZipArchive</td><td><?= class_exists('ZipArchive') ? 'Yes' : 'No' ?></td></tr>
    <tr><td>file_uploads</td><td><?= ini_get('file_uploads') ? 'On' : 'Off' ?></td></tr>
    <tr><td>upload_max_filesize</td><td><?= ini_get('upload_max_filesize') ?></td></tr>
    <tr><td>post_max_size</td><td><?= ini_get('post_max_size') ?></td></tr>
    <tr><td>Data dir writable</td><td><?= is_writable(__DIR__ . '/../data') ? 'Writable' : 'Not writable' ?></td></tr>
    <tr><td>Images dir writable</td><td><?= is_writable(__DIR__ . '/../images') ? 'Writable' : 'Not writable' ?></td></tr>
    <tr><td>Documents dir writable</td><td><?= is_writable(__DIR__ . '/../documents') ? 'Writable' : 'Not writable' ?></td></tr>
  </table>
</div>

<div class="grid grid-2" style="gap:20px;">
  <div class="admin-card">
    <h3 style="margin-top:0;">News &amp; Events</h3>
    <p style="color:var(--text-soft);"><?= count($news) ?> item<?= count($news)==1?'':'s' ?> currently published on the homepage and News page.</p>
    <a href="news.php" class="btn btn-gold btn-sm">Manage News →</a>
  </div>
  <div class="admin-card">
    <h3 style="margin-top:0;">Contact Information</h3>
    <p style="color:var(--text-soft);">Phone: <?= htmlspecialchars($contact['phone']) ?><br>Email: <?= htmlspecialchars($contact['email']) ?></p>
    <a href="contact.php" class="btn btn-gold btn-sm">Edit Contact Info →</a>
  </div>
</div>

<div class="admin-card">
  <h3 style="margin-top:0;">Applications</h3>
  <p style="color:var(--text-soft);"><?= count($applications) ?> application<?= count($applications)==1?'':'s' ?> submitted via the online form.</p>
  <a href="applications.php" class="btn btn-gold btn-sm">Manage Applications →</a>
</div>

<div class="admin-card" style="margin-top:20px;">
  <h3 style="margin-top:0;">Need something else changed?</h3>
  <p style="color:var(--text-soft);">This panel currently manages news/events and contact details. For everything else — course content, policies, board members, or design changes — contact your developer.</p>
</div>
<?php
admin_footer();
