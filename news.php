<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>News &amp; Events | Talanta Vocational Institute</title>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="topbarMount"></div>
<div id="headerMount"></div>
<div class="page-hero blueprint-bg">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> / News &amp; Events</div>
    <h1>News &amp; Events</h1>
    <p>Announcements, events, and stories from the Talanta community.</p>
  </div>
</div>
<section class="bg-white section-tight">
  <div class="container with-sidebar">
    <aside class="side-nav">
      <div class="sn-title">On This Page</div>
      <a href="#institute-news">Institute News</a>
      <a href="#events">Events Calendar</a>
      <a href="#gallery">Photo &amp; Video Gallery</a>
      <a href="#press">Press Releases</a>
      <a href="#newsletter">Newsletter</a>
    </aside>
    <div class="content-block">
      <div id="institute-news">
        <h2>Institute News</h2>
        <div class="grid grid-2" style="margin-top:16px;">
          <?php
            require_once __DIR__ . '/admin/config.php';
            $news = load_json('news.json');
            foreach ($news as $item): ?>
          <div class="card"><span class="tag-num"><?= htmlspecialchars($item['tag']) ?></span><h3><?= htmlspecialchars($item['title']) ?></h3><p><?= htmlspecialchars($item['body']) ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>
      <div id="events">
        <h2>Events Calendar</h2>
        <p>Institute Launch &amp; Orientation Day details will be announced here and shared via WhatsApp.</p>
      </div>
      <div id="gallery">
        <h2>Photo &amp; Video Gallery</h2>
        <p>Campus and workshop photos will be added as our facility opens.</p>
      </div>
      <div id="press">
        <h2>Press Releases</h2>
        <p>Official statements and media coverage will be listed here.</p>
      </div>
      <div id="newsletter">
        <h2>Newsletter Subscription</h2>
        <form onsubmit="alert('Thanks for subscribing! (Connect this form to your email service.)');return false;" style="display:flex;gap:10px;max-width:420px;flex-wrap:wrap;">
          <input type="email" required placeholder="Your email address" style="flex:1;padding:12px;border:1px solid var(--line);border-radius:2px;min-width:200px;">
          <button type="submit" class="btn btn-gold btn-sm">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</section>
<script src="js/chrome.js"></script>
</body>
</html>
