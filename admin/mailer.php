<?php
require_once __DIR__ . '/smtp_config.php';

function mailer_send($to, $subject, $body, $attachments = []) {
  // Attempt to use PHPMailer if installed via Composer
  $composer = __DIR__ . '/../vendor/autoload.php';
  if (file_exists($composer) && defined('SMTP_ENABLED')) {
    require_once $composer;
    try {
      $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
      if (SMTP_ENABLED) {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        if (!empty(SMTP_SECURE)) $mail->SMTPSecure = SMTP_SECURE;
      }
      $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
      foreach ((array) explode(',', $to) as $addr) {
        $mail->addAddress(trim($addr));
      }
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->AltBody = $body;
      foreach ($attachments as $a) {
        if (is_file($a)) $mail->addAttachment($a);
      }
      return $mail->send();
    } catch (Exception $e) {
      // fallback to mail()
    }
  }

  // Fallback to PHP mail()
  $headers = [];
  $headers[] = 'From: Talanta Website <noreply@talanta.ac.tz>';
  $headers[] = 'Reply-To: noreply@talanta.ac.tz';
  $headers[] = 'Content-Type: text/plain; charset=UTF-8';
  return @mail($to, $subject, $body, implode("\r\n", $headers));
}
