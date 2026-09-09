<?php
// Simple diagnostic: lint all PHP files and check key files and data dir writable.
echo "Running diagnostics...\n\n";

$root = __DIR__ . '/../';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$phpFiles = [];
foreach ($rii as $file) {
  if ($file->isDir()) continue;
  if (strtolower($file->getExtension()) === 'php') $phpFiles[] = $file->getPathname();
}

echo "PHP lint results:\n";
$errors = 0;
foreach ($phpFiles as $f) {
  $cmd = 'php -l ' . escapeshellarg($f) . ' 2>&1';
  exec($cmd, $out, $rc);
  echo "== $f ==\n";
  foreach ($out as $line) echo $line . "\n";
  if ($rc !== 0) $errors++;
  $out = [];
}

echo "\nSummary: $errors file(s) with lint errors.\n\n";

// Key files
$checks = [
  'data/contact.json','data/applications.json','admin/config.php','admin/layout.php','admin/index.php','admin/applications.php','admissions_apply.php','js/chrome.js','css/style.css'
];
echo "Key file checks:\n";
foreach ($checks as $p) {
  $exists = file_exists($root . $p) ? 'OK' : 'MISSING';
  echo str_pad($p, 30) . ': ' . $exists . "\n";
}

echo "\nData directory writable: ";
echo is_writable($root . 'data') ? 'YES' : 'NO';
echo "\n";

if ($errors === 0) echo "\nAll PHP files passed lint.\n";
