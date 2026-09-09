<?php
require_once __DIR__ . '/admin/config.php';
require_once __DIR__ . '/admin/mailer.php';

$flash = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!empty($token)) {
    validate_csrf_token($token);
  }

  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $program = trim($_POST['program'] ?? '');
  $qualification = trim($_POST['qualification'] ?? '');
  $dob = trim($_POST['dob'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name === '') $errors[] = 'Full name is required.';
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
  if ($phone === '') $errors[] = 'Phone number is required.';
  if ($program === '') $errors[] = 'Please specify a programme you are applying for.';

  $resumePath = null;
  if (!empty($_FILES['resume']) && $_FILES['resume']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['resume']['error'] === UPLOAD_ERR_OK) {
      $maxSize = 5 * 1024 * 1024;
      if ($_FILES['resume']['size'] > $maxSize) {
        $errors[] = 'Resume file must be 5MB or smaller.';
      } else {
        $allowedExt = ['pdf', 'doc', 'docx'];
        $origName = $_FILES['resume']['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
          $errors[] = 'Resume must be a PDF or Word document.';
        } else {
          $mime = '';
          if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
              $mime = finfo_file($finfo, $_FILES['resume']['tmp_name']);
              finfo_close($finfo);
            }
          }
          $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
          if ($mime !== '' && !in_array($mime, $allowedMimes, true)) {
            $errors[] = 'Resume file type not allowed.';
          } else {
            $uploadDir = __DIR__ . '/documents/applications';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($origName));
            $target = $uploadDir . '/' . time() . '_' . $safeName;
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $target)) {
              $resumePath = 'documents/applications/' . basename($target);
            } else {
              $errors[] = 'Could not save uploaded resume.';
            }
          }
        }
      }
    } else {
      $errors[] = 'File upload error for resume.';
    }
  }

  if (empty($errors) && !empty($_POST) && !empty($name) && !empty($email) && !empty($phone) && !empty($program)) {
    $submission = [
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'program' => $program,
      'qualification' => $qualification,
      'dob' => $dob,
      'address' => $address,
      'message' => $message,
      'resume' => $resumePath,
      'submitted_at' => time(),
    ];

    $apps = load_json('applications.json');
    $apps[] = $submission;
    save_json('applications.json', $apps);

    $to = 'admissions@talanta.ac.tz';
    $subject = 'New Admission Application: ' . $name;
    $body = "A new application has been submitted:\n\n";
    foreach ($submission as $k => $v) {
      if ($k === 'submitted_at') $v = date('Y-m-d H:i:s', $v);
      $body .= ucfirst(str_replace('_', ' ', $k)) . ': ' . ($v === null ? 'N/A' : $v) . "\n";
    }

    $headers = [];
    $headers[] = 'From: Talanta Website <noreply@talanta.ac.tz>';
    $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    $attachments = [];
    if ($resumePath) $attachments[] = __DIR__ . '/' . $resumePath;
    $sent = mailer_send($to, $subject, $body, $attachments);
    if ($sent) {
      $flash = 'Application submitted. Our admissions team will contact you soon.';
    } else {
      $flash = 'Application saved, but we could not send email notification (server may not be configured). Admissions will still see your submission.';
    }

    $name = $email = $phone = $program = $qualification = $dob = $address = $message = '';
  }
}

function old($key) {
  return htmlspecialchars($_POST[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Apply &mdash; Talanta Vocational Institute</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="topbarMount"></div>
<div id="headerMount"></div>
<div class="page-hero blueprint-bg">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> / Admissions / Apply</div>
    <h1>Online Application</h1>
    <p>Please complete the form below to apply. Fields marked * are required.</p>
  </div>
</div>

<section class="bg-white section-tight">
  <div class="container">
    <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?><div class="flash" style="background:#ffd6d6;color:#800;"><?php foreach($errors as $e) echo htmlspecialchars($e)."<br>"; ?></div><?php endif; ?>

    <div style="margin-bottom:22px; padding:18px 20px; border:1px solid rgba(15,28,38,.08); border-radius:14px; background:#f8fafc;">
      <h3 style="margin:0 0 8px;">Required Admission Documents</h3>
      <p style="margin:0 0 10px;">Please ensure you have the following before completing the application:</p>
      <ul style="margin:0 0 0 18px; padding:0; line-height:1.8;">
        <li>National Identity Card or Birth Certificate</li>
        <li>Academic certificates and transcript or latest school results</li>
        <li>Recent passport-size photograph</li>
        <li>Reference letter or recommendation where required</li>
        <li>Curriculum Vitae / resume when requested</li>
        <li>Proof of payment or fee receipt (if applicable)</li>
        <li>Medical certificate for programmes requiring health clearance</li>
        <li>Parent or guardian consent letter for underage applicants</li>
      </ul>
    </div>

    <form method="post" enctype="multipart/form-data" class="admin-form">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
      <div class="grid grid-2" style="gap:20px;">
        <div>
          <label>Full name *</label>
          <input type="text" name="name" value="<?= old('name') ?>" required>
          <label>Email address *</label>
          <input type="email" name="email" value="<?= old('email') ?>" required>
          <label>Phone number *</label>
          <input type="text" name="phone" value="<?= old('phone') ?>" required>
          <label>Programme applying for *</label>
          <input type="text" name="program" value="<?= old('program') ?>" required>
          <label>Highest qualification</label>
          <input type="text" name="qualification" value="<?= old('qualification') ?>">
        </div>
        <div>
          <label>Date of birth</label>
          <input type="date" name="dob" value="<?= old('dob') ?>">
          <label>Postal / Home address</label>
          <textarea name="address"><?= old('address') ?></textarea>
          <label>Personal statement / Additional information</label>
          <textarea name="message" rows="6"><?= old('message') ?></textarea>
          <label>Upload resume (optional, PDF/DOC)</label>
          <input type="file" name="resume" accept=".pdf,.doc,.docx">
        </div>
      </div>
      <div style="margin-top:16px;">
        <button class="btn btn-gold" type="submit">Submit Application</button>
        <a class="btn btn-ink" href="admissions.html">Back to Admissions</a>
      </div>
    </form>
  </div>
</section>

<script src="js/chrome.js"></script>
<script>
// Client-side validation for nicer UX
document.addEventListener('DOMContentLoaded', function(){
  const form = document.querySelector('form[enctype]');
  if (!form) return;
  form.addEventListener('submit', function(e){
    const name = form.querySelector('[name=name]').value.trim();
    const email = form.querySelector('[name=email]').value.trim();
    const phone = form.querySelector('[name=phone]').value.trim();
    const program = form.querySelector('[name=program]').value.trim();
    const resume = form.querySelector('[name=resume]');
    let errors = [];
    if (!name) errors.push('Full name is required.');
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push('Valid email is required.');
    if (!phone) errors.push('Phone number is required.');
    if (!program) errors.push('Programme is required.');
    if (resume && resume.files && resume.files[0]) {
      const f = resume.files[0];
      if (f.size > 5 * 1024 * 1024) errors.push('Resume must be 5MB or smaller.');
      const allowed = ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
      if (allowed.indexOf(f.type) === -1) errors.push('Resume must be PDF or Word document.');
    }
    if (errors.length) {
      e.preventDefault();
      alert(errors.join('\n'));
    }
  });
});
</script>
</body>
</html>
