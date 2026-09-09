<?php
// SMTP configuration for PHPMailer (optional).
// If you want to use SMTP instead of PHP mail(), fill these values and
// install PHPMailer via Composer: `composer require phpmailer/phpmailer`.

define('SMTP_ENABLED', false);
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'user@example.com');
define('SMTP_PASS', 'yourpassword');
define('SMTP_SECURE', 'tls'); // tls or ssl or empty
define('SMTP_FROM_EMAIL', 'noreply@talanta.ac.tz');
define('SMTP_FROM_NAME', 'Talanta Website');
