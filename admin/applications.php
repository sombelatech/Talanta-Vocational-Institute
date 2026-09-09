<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$apps = load_json('applications.json');
$flash = '';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
    $idx = isset($_POST['idx']) ? (int)$_POST['idx'] : -1;
    if (isset($apps[$idx])) {
      // delete uploaded resume file if present
      if (!empty($apps[$idx]['resume'])) {
        $file = __DIR__ . '/../' . $apps[$idx]['resume'];
        if (is_file($file)) @unlink($file);
      }
      array_splice($apps, $idx, 1);
      save_json('applications.json', $apps);
      $flash = 'Application removed.';
    } else {
      $flash = 'Application not found.';
    }
  }
}

// Search & pagination
$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

// Filter by search query
if ($q !== '') {
  $apps = array_values(array_filter($apps, function($a) use ($q) {
    $hay = strtolower(implode(' ', [
      $a['name'] ?? '', $a['email'] ?? '', $a['phone'] ?? '', $a['program'] ?? '', $a['qualification'] ?? ''
    ]));
    return strpos($hay, strtolower($q)) !== false;
  }));
}

// Export CSV (supports filtered results)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=applications.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['Name','Email','Phone','Program','Qualification','DOB','Address','Message','Resume','Submitted At']);
  foreach ($apps as $a) {
    fputcsv($out, [
      $a['name'] ?? '', $a['email'] ?? '', $a['phone'] ?? '', $a['program'] ?? '', $a['qualification'] ?? '', $a['dob'] ?? '', $a['address'] ?? '', $a['message'] ?? '', $a['resume'] ?? '', isset($a['submitted_at']) ? date('Y-m-d H:i:s', $a['submitted_at']) : ''
    ]);
  }
  exit;
}

// Email CSV to admissions (POST action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'emailcsv') {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
    // generate CSV in memory
    $fp = fopen('php://temp', 'r+');
    fputcsv($fp, ['Name','Email','Phone','Program','Qualification','DOB','Address','Message','Resume','Submitted At']);
    foreach ($apps as $a) {
      fputcsv($fp, [
        $a['name'] ?? '', $a['email'] ?? '', $a['phone'] ?? '', $a['program'] ?? '', $a['qualification'] ?? '', $a['dob'] ?? '', $a['address'] ?? '', $a['message'] ?? '', $a['resume'] ?? '', isset($a['submitted_at']) ? date('Y-m-d H:i:s', $a['submitted_at']) : ''
      ]);
    }
    rewind($fp);
    $csv = stream_get_contents($fp);
    fclose($fp);

    $to = 'admissions@talanta.ac.tz';
    $subject = 'Applications export from website';
    $boundary = md5(uniqid('', true));
    $headers = [];
    $headers[] = 'From: Talanta Website <noreply@talanta.ac.tz>';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $body .= "Please find attached the exported applications CSV.\r\n\r\n";
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/csv; name=applications.csv\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=applications.csv\r\n\r\n";
    $body .= chunk_split(base64_encode($csv)) . "\r\n";
    $body .= "--$boundary--\r\n";

    // Use mailer wrapper (PHPMailer if available)
    // write CSV to temp file and attach
    $tmp = tempnam(sys_get_temp_dir(), 'apps_');
    file_put_contents($tmp, $csv);
    $sent = mailer_send($to, $subject, $body, [$tmp]);
    @unlink($tmp);
    if ($sent) {
      $flash = 'CSV emailed to admissions.';
    } else {
      $flash = 'Could not send CSV by email (server not configured). You can download it instead.';
    }
  }
}

admin_header('Applications');
?>
<div class="admin-topbar">
  <div>
    <h1>Applications</h1>
    <p style="color:var(--text-soft);margin:0;">Submitted applications via the online form. You can export all as CSV or remove entries.</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <div style="color:var(--text-soft);">Total: <?= count($apps) ?> application<?= count($apps)==1?'':'s' ?></div>
    <div>
      <form method="get" style="display:inline;margin-right:6px;">
        <input type="text" name="q" placeholder="Search name, email, program" value="<?= htmlspecialchars($q) ?>">
        <button class="btn btn-ink btn-sm" type="submit">Search</button>
      </form>
      <a class="btn btn-ink btn-sm" href="applications.php?export=csv">Download CSV</a>
      <form method="post" style="display:inline;margin-left:6px;" onsubmit="return confirm('Email CSV to admissions?');">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
        <input type="hidden" name="action" value="emailcsv">
        <button class="btn btn-gold btn-sm" type="submit">Email CSV</button>
      </form>
    </div>
  </div>

  <?php if (empty($apps)): ?>
    <p style="color:var(--text-soft);">No applications yet.</p>
  <?php else: ?>
    <table class="admin-table">
      <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Program</th><th>Submitted</th><th>Actions</th></tr>
      <?php
        $total = count($apps);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $start = ($page - 1) * $perPage;
        $slice = array_slice($apps, $start, $perPage);
        foreach ($slice as $i => $a):
          $idx = $start + $i;
      ?>
          <tr>
            <td><?= $idx+1 ?></td>
          <td><?= htmlspecialchars($a['name'] ?? '') ?></td>
          <td><?= htmlspecialchars($a['email'] ?? '') ?></td>
          <td><?= htmlspecialchars($a['phone'] ?? '') ?></td>
          <td><?= htmlspecialchars($a['program'] ?? '') ?></td>
            <td><?= isset($a['submitted_at']) ? date('Y-m-d H:i', $a['submitted_at']) : '' ?></td>
          <td>
            <?php if (!empty($a['resume'])): ?>
              <a class="btn btn-ink btn-sm" href="../<?= htmlspecialchars($a['resume']) ?>" target="_blank">Download Resume</a>
            <?php endif; ?>
            <form method="post" style="display:inline;margin-left:6px;" onsubmit="return confirm('Delete this application?');">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="idx" value="<?= $idx ?>">
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <?php if ($totalPages > 1): ?>
      <div style="margin-top:12px;text-align:center;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <?php if ($p == $page): ?>
            <strong><?= $p ?></strong>
          <?php else: ?>
            <a href="applications.php?page=<?= $p ?>&q=<?= urlencode($q) ?>"><?= $p ?></a>
          <?php endif; ?>
          &nbsp;
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php admin_footer();
