<?php
session_start();
require_once __DIR__ . '/config.php';
require_login();
require_once __DIR__ . '/layout.php';

$contact = load_json('contact.json');
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token = $_POST['csrf'] ?? '';
  if (!validate_csrf_token($token)) {
    $flash = 'Invalid session token.';
  } else {
    $fields = ['phone','phone2','whatsapp','email','address','postal','office_hours','facebook','instagram','linkedin','youtube','twitter'];
    foreach ($fields as $f) {
      $contact[$f] = trim($_POST[$f] ?? ($contact[$f] ?? ''));
    }

    $rawEmails = $_POST['emails'] ?? '';
    $institutionalEmails = [];
    foreach (preg_split('/\r\n|\r|\n|,/', trim((string) $rawEmails)) as $entry) {
      $value = trim((string) $entry);
      if ($value !== '') {
        $institutionalEmails[] = $value;
      }
    }
    if (!empty($institutionalEmails)) {
      $contact['emails'] = $institutionalEmails;
      $contact['email'] = $institutionalEmails[0];
    } else {
      $contact['emails'] = [];
      if (!empty($contact['email'])) {
        $contact['emails'] = [$contact['email']];
      }
    }

    // normalize whatsapp to digits only
    if (!empty($contact['whatsapp'])) $contact['whatsapp'] = preg_replace('/[^0-9]/','',$contact['whatsapp']);
    save_json('contact.json', $contact);
    $flash = 'Contact information updated.';
  }
}

admin_header('Contact Info');
?>
<div class="admin-topbar">
  <div>
    <h1>Contact Information</h1>
    <p style="color:var(--text-soft);margin:0;">Shown on the Contacts page (phone, email, address, socials).</p>
  </div>
</div>

<?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

<div class="admin-card">
  <form method="post" class="admin-form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(get_csrf_token()) ?>">
    <div class="grid grid-2" style="gap:0 24px;">
      <div>
        <label>Primary Phone (displayed)</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($contact['phone']) ?>">
        <label>Secondary Phone</label>
        <input type="text" name="phone2" value="<?= htmlspecialchars($contact['phone2']) ?>">
        <label>WhatsApp Number (digits only, e.g. 255767424120)</label>
        <input type="text" name="whatsapp" value="<?= htmlspecialchars($contact['whatsapp']) ?>">
        <label>Primary Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($contact['email']) ?>">
        <label>Institutional Email Addresses (one per line)</label>
        <textarea name="emails" rows="5"><?= htmlspecialchars(implode("\n", is_array($contact['emails']) ? $contact['emails'] : [($contact['email'] ?? '')])) ?></textarea>
        <label>Office Hours</label>
        <input type="text" name="office_hours" value="<?= htmlspecialchars($contact['office_hours']) ?>">
      </div>
      <div>
        <label>Physical Address</label>
        <textarea name="address"><?= htmlspecialchars($contact['address']) ?></textarea>
        <label>Postal Address</label>
        <input type="text" name="postal" value="<?= htmlspecialchars($contact['postal']) ?>">
      </div>
    </div>

    <h3 style="margin-top:24px;">Social Media Links</h3>
    <div class="grid grid-2" style="gap:0 24px;">
      <div>
        <label>Facebook URL</label>
        <input type="text" name="facebook" value="<?= htmlspecialchars($contact['facebook']) ?>">
        <label>Instagram URL</label>
        <input type="text" name="instagram" value="<?= htmlspecialchars($contact['instagram']) ?>">
      </div>
      <div>
        <label>LinkedIn URL</label>
        <input type="text" name="linkedin" value="<?= htmlspecialchars($contact['linkedin']) ?>">
        <label>YouTube URL</label>
        <input type="text" name="youtube" value="<?= htmlspecialchars($contact['youtube']) ?>">
        <label>X (Twitter) URL</label>
        <input type="text" name="twitter" value="<?= htmlspecialchars($contact['twitter']) ?>">
      </div>
    </div>

    <div style="margin-top:20px;">
      <button type="submit" class="btn btn-gold btn-sm">Save Changes</button>
    </div>
  </form>
</div>
<?php
admin_footer();
