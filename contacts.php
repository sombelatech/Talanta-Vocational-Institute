<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacts | Talanta Vocational Institute</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
require_once __DIR__ . '/admin/config.php';
$c = load_json('contact.json');
$emails = [];
if (!empty($c['emails']) && is_array($c['emails'])) {
  foreach ($c['emails'] as $email) {
    $email = trim((string) $email);
    if ($email !== '') $emails[] = $email;
  }
}
if (empty($emails) && !empty($c['email'])) {
  $email = trim((string) $c['email']);
  if ($email !== '') $emails[] = $email;
}
$primaryEmail = $emails[0] ?? 'admin@talanta.ac.tz';
?>
<div id="topbarMount"></div>
<div id="headerMount"></div>
<div class="page-hero blueprint-bg">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> / Contacts</div>
    <h1>Contacts</h1>
    <p>Reach the Talanta team directly, or find our campus in Mbagala.</p>
  </div>
</div>

<section class="bg-white section-tight">
  <div class="container grid grid-2" style="align-items:start;">
    <div>
      <h2 style="font-size:24px;">Get in Touch</h2>
      <div class="card" style="margin-bottom:16px;">
        <p style="margin-bottom:6px;"><strong>Location:</strong> <?= htmlspecialchars($c['address']) ?></p>
        <p style="margin-bottom:6px;"><strong>Postal Address:</strong> <?= htmlspecialchars($c['postal']) ?></p>
        <p style="margin-bottom:6px;"><strong>Mobile / WhatsApp:</strong> <a href="tel:+<?= htmlspecialchars($c['whatsapp']) ?>"><?= htmlspecialchars($c['phone']) ?></a> / <?= htmlspecialchars($c['phone2']) ?></p>
        <p style="margin-bottom:6px;"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($primaryEmail) ?>"><?= htmlspecialchars($primaryEmail) ?></a></p>
        <?php if (count($emails) > 1): ?>
          <ul style="margin:8px 0 0 18px; padding:0; list-style:disc;">
            <?php foreach ($emails as $email): if ($email === $primaryEmail) continue; ?>
              <li><a href="mailto:<?= htmlspecialchars($email) ?>"><?= htmlspecialchars($email) ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="hero-actions">
        <a href="https://wa.me/<?= htmlspecialchars($c['whatsapp']) ?>" target="_blank" rel="noopener" class="btn btn-gold">WhatsApp Us →</a>
        <a href="mailto:info@talanta.ac.tz" class="btn btn-ink">Send an Email →</a>
      </div>
      <h3 style="margin-top:32px;font-size:18px;">Social Media</h3>
      <div class="social-row" style="margin-top:0;">
        <a href="<?= htmlspecialchars($c['facebook']) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon" style="border-color:var(--line);color:var(--ink);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1" fill="none" />
            <text x="12" y="16" text-anchor="middle" font-size="12" font-family="Arial, Helvetica, sans-serif" fill="currentColor">f</text>
          </svg>
        </a>
        <a href="<?= htmlspecialchars($c['instagram']) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon" style="border-color:var(--line);color:var(--ink);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1" fill="none" />
            <circle cx="12" cy="11" r="3" stroke="currentColor" stroke-width="1" fill="none" />
            <circle cx="17" cy="7" r="0.8" fill="currentColor" />
          </svg>
        </a>
        <a href="<?= htmlspecialchars($c['linkedin']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn" class="social-icon" style="border-color:var(--line);color:var(--ink);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1" fill="none" />
            <rect x="6" y="10" width="3" height="7" fill="currentColor" />
            <path d="M10 10v7" stroke="currentColor" stroke-width="1.2" />
            <circle cx="7.5" cy="7.5" r="1" fill="currentColor" />
          </svg>
        </a>
        <a href="<?= htmlspecialchars($c['youtube']) ?>" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon" style="border-color:var(--line);color:var(--ink);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect x="3" y="6" width="18" height="12" rx="3" stroke="currentColor" stroke-width="1" fill="none" />
            <path d="M10 9l5 3-5 3V9z" fill="currentColor" />
          </svg>
        </a>
        <a href="<?= htmlspecialchars($c['twitter']) ?>" target="_blank" rel="noopener" aria-label="X (Twitter)" class="social-icon" style="border-color:var(--line);color:var(--ink);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5 19c7 0 11-6 11-11v-1c.8-.6 1.4-1.4 1.8-2.3-.7.3-1.5.5-2.3.6.8-.5 1.4-1.2 1.7-2.1-.8.5-1.6.8-2.6 1C13.8 3.7 12.5 3 11 3c-2 0-3.6 1.6-3.6 3.6 0 .3 0 .6.1.9C5.3 7.2 3 6 1.5 4.2c-.4.7-.6 1.6-.6 2.4 0 1.7.9 3.1 2.4 3.9-.6 0-1.2-.2-1.7-.5v.1c0 2.4 1.7 4.4 3.9 4.9-.4.1-.9.1-1.3.1-.3 0-.6 0-.9-.1.6 1.8 2.3 3.1 4.3 3.1" stroke="currentColor" stroke-width="0.6" fill="none" />
          </svg>
        </a>
      </div>
    </div>
    <div>
      <h2 style="font-size:24px;">Find Us</h2>
      <iframe title="Talanta Vocational Institute location" width="100%" height="320" style="border:0;border-radius:3px;" loading="lazy"
        src="https://www.google.com/maps?q=Mbagala+Zakhem,+Temeke,+Dar+es+Salaam&output=embed"></iframe>
    </div>
  </div>
</section>

<section class="bg-steel">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Popular / Quick Links</span>
      <h2>Tanzanian Academic &amp; Regulatory Bodies</h2>
      <p>Official links to the government and sector bodies relevant to vocational and higher education in Tanzania.</p>
    </div>
    <div class="govlink-grid">
      <a class="govlink" href="https://www.heslb.go.tz" target="_blank" rel="noopener">HESLB <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.nacte.go.tz" target="_blank" rel="noopener">NACTE <span class="ext">↗</span></a>
      <a class="govlink" href="https://necta.go.tz" target="_blank" rel="noopener">NECTA <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.tcu.go.tz" target="_blank" rel="noopener">TCU <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.moe.go.tz" target="_blank" rel="noopener">MoEST <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.nbaa.go.tz" target="_blank" rel="noopener">NBAA <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.tie.go.tz" target="_blank" rel="noopener">TIE <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.nida.go.tz" target="_blank" rel="noopener">NIDA <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.nhif.or.tz" target="_blank" rel="noopener">NHIF <span class="ext">↗</span></a>
      <a class="govlink" href="https://iucea.org" target="_blank" rel="noopener">IUCEA <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.aau.org" target="_blank" rel="noopener">AAU <span class="ext">↗</span></a>
      <a class="govlink" href="https://www.ajira.go.tz" target="_blank" rel="noopener">Public Service Recruitment <span class="ext">↗</span></a>
    </div>
    <p style="font-size:13px;margin-top:16px;color:var(--text-soft);">Zanzibar applicants: see ZHELB for loan-related enquiries. Links open the official body's website in a new tab.</p>
  </div>
</section>

<script src="js/chrome.js"></script>
</body>
</html>
